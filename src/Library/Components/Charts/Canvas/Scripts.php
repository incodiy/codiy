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
	
	protected function ajaxProcess($identity, $url, $dataValues, $postData) {
		$token  = csrf_token();
		$script = "
		function requestData{$identity} () {
		    $.ajax({
		        url      : '{$url}',
		        type     : 'POST',
				headers  : {'X-CSRF-TOKEN': '{$token}'},
		        dataType : 'json',
		        data     : {$dataValues},
		        success  : function(data) {
					$.each(data.series.{$postData}.series, function(i, chart) {
						{$identity}.addSeries({
							name : chart.name,
							data : chart.data,
							type : chart.type
			            });
					});
					
					$.each(data.category.{$postData}, function(i, chart) {
						{$identity}.xAxis[0].setCategories(chart);
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
		var {$identity_string} = new Highcharts.chart({
			chart: {
				renderTo: '{$identity_chart}',
				type: '{$params['type']}',
				events: {
					load: requestData{$identity_string}
				}
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
			plotOptions: {$plotOptions}
		});";
		return $canvas;
	}
}