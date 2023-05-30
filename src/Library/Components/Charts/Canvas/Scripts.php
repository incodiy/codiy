<?php
namespace Incodiy\Codiy\Library\Components\Charts\Canvas;

/**
 * Created on May 23, 2023
 * 
 * Time Created : 4:34:23 PM
 *
 * @filesource  Scripts.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
trait Scripts {
	
	public $script_chart = [];
	
	protected function filterTableInjectionScript($data) {
		$formIdentity  = $data['filter_table'];
		$formIDString  = str_replace('-', '', $formIdentity);
		$chartIdentity = $data['chart_info'];
		
		foreach ($chartIdentity as $chartInfo) {
			unset($chartIdentity);
			$chartIdentity[$formIdentity] = $chartInfo;
		}
		
		$submitFilterButton = str_replace('_cdyFILTERForm', '_submitFilterButton', "{$formIdentity}");
		$chartFilterButton  = "{$chartIdentity[$formIdentity]['string']}submitChartFilter";
		$chartFilterBox     = "{$chartIdentity[$formIdentity]['string']}filterChartBox";
		
		$urli  = $this->chartURI[$chartIdentity[$formIdentity]['code']] . '&diyChartDataFilter=' . $chartIdentity[$formIdentity]['string'];
		$token = csrf_token();
		
		$script = "
<script type=\"text/javascript\">
	$( document ).ready(function() {
		var ajaxFromTable{$formIDString} = {};
		
		$('#{$submitFilterButton}').click(function() {
			var postFromTable{$chartIdentity[$formIdentity]['string']} = [];
			ajaxFromTable{$formIDString}     = $('#{$formIdentity}').serializeArray();
			
			if ('_token' === ajaxFromTable{$formIDString}[0].name) {
				$.each(ajaxFromTable{$formIDString}, function(index, item) {
					if ('_token' !== item.name && '' != item.value) {
						postFromTable{$chartIdentity[$formIdentity]['string']}.push({
							name : item.name,
							value: item.value
						});
					}
				});
			}

			var filterData{$formIDString} = {postFromTable{$chartIdentity[$formIdentity]['string']}};
			requestData{$chartIdentity[$formIdentity]['string']} ('{$urli}', filterData{$formIDString});
	    });
	});
</script>";
		
		$this->script_chart['js'] = $script;
	}
	
	protected function ajaxProcess($identity, $url, $dataValues, $postData) {
		$data               = [];
		$data['identity']   = $identity;
		$data['url']        = $url;
		$data['dataValues'] = $dataValues;
		$data['postData']   = $postData;
		
		$token  = csrf_token();
		$script = "
		function requestData{$data['identity']} (urliReq, dataValues) {
			if ('object' == typeof urliReq) {
				var urliReq    = '{$url}';
				var dataValues = {$dataValues};
			}
			
		    $.ajax({
		        url      : urliReq,
		        type     : 'POST',
				headers  : {'X-CSRF-TOKEN': '{$token}'},
		        dataType : 'json',
		        data     : dataValues,
		        success  : function(data) {
					$.each(data.series.{$postData}.series, function(i, chart) {
						{$data['identity']}.addSeries({
							name : chart.name,
							data : chart.data,
							type : chart.type
			            });
					});
					
					$.each(data.category.{$postData}, function(i, chart) {
						{$data['identity']}.xAxis[0].setCategories(chart);
					});
		        },
		        cache: false
		    });
		}";
		
		return $script;
	}
	
	private function setTitle($title = null) {
		if (diy_string_contained($title, 't_view')) {
			$setTitle = ucwords(str_replace('_', ' ', str_replace('t_view_', '', $title)));
		} else {
			$setTitle = ucwords(str_replace('_', ' ', $title));
		}
		
		return $setTitle;
	}
	
	protected function canvascipt($identity_chart, $identity_string, $params = [], $attributes = []) {
		$title       = json_encode(['text' => $this->setTitle($params['source'])]);
		$subtitle    = '{}';
		$axisTitle   = '{}';
		$tooltip     = "{
			headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
			pointFormat : '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' + '<td style=\"padding:0\"><b>{point.y:.1f} mm</b></td></tr>',
			footerFormat: '</table>',
			shared      : true,
			useHTML     : true
		}";
		$plotOptions = "{
			column: {
				pointPadding: 0.2,
				borderWidth : 0
			}
		}";
		
		if (!empty($attributes)) {
			if (!empty($attributes['title']))       $title       = json_encode($attributes['title']);
			if (!empty($attributes['subtitle']))    $subtitle    = json_encode($attributes['subtitle']);
			if (!empty($attributes['axisTitle']))   $axisTitle   = json_encode($attributes['axisTitle']);
			if (!empty($attributes['tooltip']))     $tooltip     = json_encode($attributes['tooltip']);
			if (!empty($attributes['plotOptions'])) $plotOptions = json_encode($attributes['plotOptions']);
		}
		
		$canvas = "
		var filterData{$identity_string} = [];
		var {$identity_string} = new Highcharts.chart({
			chart: {
				renderTo: '{$identity_chart}',
				type: '{$params['type']}',
				events: {
					load: requestData{$identity_string}
				}
			},
	        dataSource: {
	            data: filterData{$identity_string}
	        },
			title: {$title},
			subtitle: {$subtitle},
			xAxis: {
				categories: [],
				crosshair: true
			},
			yAxis: {
				min: 0,
				title: {$axisTitle}
			},
			tooltip: {$tooltip},
			plotOptions: {$plotOptions},
			responsive: {
				rules: [{
					condition: {
						maxWidth: 500
					},
					chartOptions: {
						legend: {
							enabled: false
						}
					}
				}]
			}
		});";
		return $canvas;
	}
}