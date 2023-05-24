<?php
namespace Incodiy\Codiy\Library\Components\Charts;

use Incodiy\Codiy\Library\Components\Charts\Canvas\Builder;
use Incodiy\Codiy\Library\Components\Form\Elements\Tab;
use Incodiy\Codiy\Library\Components\Charts\Canvas\DataModel;

/**
 * Created on May 23, 2023
 * 
 * Time Created : 4:29:05 PM
 *
 * @filesource  Objects.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
class Objects extends Builder {
	use Tab, DataModel;
	
	public $elements      = [];
	public $element_name  = [];
	public $params        = [];
	public $connection;
	
	private $chartLibrary = 'highcharts';
	
	/**
	 * --[openTabHTMLForm]--
	 */
	private $opentabHTML  = '--[openTabHTMLForm]--';
	
	public function __construct() {
		$this->element_name['chart'] = $this->chartLibrary;
	}
	
	public function connection($db_connection) {
		$this->connection = $db_connection;
	}
	
	public function method($method) {
		$this->method = $method;
	}
	
	private function draw($initial, $data = []) {
		if ($data) {
			$this->elements[$initial] = $data;
		} else {
			$this->elements[] = $initial;
		}
	}
	
	public function render($object) {
		$tabObj = "";
		if (true === is_array($object)) $tabObj = implode('', $object);
		
		if (true === diy_string_contained($tabObj, $this->opentabHTML)) {
			return $this->renderTab($object);
		} else {
			return $object;
		}
	}
	
	public $identities = [];
	private $sourceIdentity;
	private function setParams($type, $source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		$this->sourceIdentity = diy_clean_strings("CoDIY_{$this->chartLibrary}_" . $source . '_' . diy_random_strings(50, false));
		
		$this->identities[$this->sourceIdentity]['code']   = $this->sourceIdentity;
		$this->identities[$this->sourceIdentity]['source'] = $source;
		$this->identities[$this->sourceIdentity]['string'] = str_replace('-', '', $this->sourceIdentity);
		
		$this->params[$this->sourceIdentity]['type']       = $type;
		$this->params[$this->sourceIdentity]['source']     = $source;
		$this->params[$this->sourceIdentity]['fields']     = $fieldsets;
		$this->params[$this->sourceIdentity]['format']     = $format;
		$this->params[$this->sourceIdentity]['category']   = $category;
		$this->params[$this->sourceIdentity]['group']      = $group;
		$this->params[$this->sourceIdentity]['order']      = $order;
		$this->params[$this->sourceIdentity]['series']     = [];
	}
	
	public function canvas($type, $source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		$identity = diy_clean_strings("CoDIY_{$this->chartLibrary}_" . $source . '_' . diy_random_strings(50, false));
		
		$this->identities[$identity]['source'] = $source;
		$this->identities[$identity]['string'] = str_replace('-', '', $identity);
		
		$this->params[$identity]['type']     = $type;
		$this->params[$identity]['source']   = $source;
		$this->params[$identity]['fields']   = $fieldsets;
		$this->params[$identity]['format']   = $format;
		$this->params[$identity]['category'] = $category;
		$this->params[$identity]['group']    = $group;
		$this->params[$identity]['order']    = $order;
		$this->params[$identity]['series']   = [];/* 
		
		$this->setSeries($identity, 'Data 1', [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]);
		$this->setSeries($identity, 'Data 2', [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]);
		$this->setSeries($identity, 'Data 3', [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]);
		$this->setSeries($identity, 'Target', [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2], 'line');
		 */
		return $this->draw($identity, $this->chartCanvas($identity, $this->params));
		
		dd($type, $source, $fieldsets, $format, $category, $group, $order, $this->params);
	}
	
	public function column($source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		$this->setParams('column', $source, $fieldsets, $format, $category, $group, $order);
		
		return $this->chartCanvas($this->sourceIdentity, $this->params);
	}
	
	public function line($source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		$this->setParams('line', $source, $fieldsets, $format, $category, $group, $order);
		
		return $this->chartCanvas($this->sourceIdentity, $this->params);
	}
	/* 
	public function setSeries($identity, $name, $data, $type = 'column') {
		$data = [
			'name' => $name,
			'data' => $data,
			'type' => $type
		];
		
		$this->params[$identity]['series'][] = json_encode($data);
	}
	 */
	private $canvas = [];
	private function canvasBuilder($identity, $parameters = []) {
		$this->canvas[$identity] = "
var {$this->identities[$identity]['string']} = new Highcharts.chart({
  chart: {
	renderTo: '{$identity}',
    type: 'column',
	events: {
		load: requestData{$this->identities[$identity]['string']}
	}
  },
  title: {
    text: 'Monthly Average Rainfall'
  },
  subtitle: {
    text: 'Source: WorldClimate.com'
  },
  xAxis: {
    categories: [],
    crosshair: true
  },
  yAxis: {
    min: 0,
    title: {
      text: 'Rainfall (mm)'
    }
  },
  tooltip: {
    headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
    pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
      '<td style=\"padding:0\"><b>{point.y:.1f} mm</b></td></tr>',
    footerFormat: '</table>',
    shared: true,
    useHTML: true
  },
  plotOptions: {
    column: {
      pointPadding: 0.2,
      borderWidth: 0
    }
  },
	series: {}
});";
	}
	
	private $post = [];
	private $chartPostData = 'diyChartData';
	public function process($post) {
		$this->post[$this->chartPostData] = [];
		$this->post[$this->chartPostData] = [
			['name'=>'test', 'data'=>[1,2,3], 'type'=>'column'],
			['name'=>'test 1', 'data'=>[15,14,18], 'type'=>'column'],
			['name'=>'test 1', 'data'=>[5,4,8], 'type'=>'line']
		];
		
		$postData = json_decode(diy_decrypt($post[$this->chartPostData]));
		$this->construct($postData);
		
		echo json_encode(['category' => $this->category, 'series' => $this->series]);
		exit;
	}
	
	public function chartCanvas($identity = []) {
		
		$chartIdentity = str_replace('-', '', $identity);
		$this->canvasBuilder($identity);
		
		$scriptSeries = '';
		if (!empty($this->params[$identity]['series'])) {
			foreach ($this->params[$identity]['series'] as $series) {
				$scriptSeries .= "{$this->identities[$identity]['string']}.addSeries({$series}, false);";
			}
		}
		
		$sourceName         = $this->identities[$identity]['source'];
		$chartURI           = url(diy_current_route()->uri) . "?renderCharts=true&difta[name]={$sourceName}&difta[source]=dynamics";
		
		$dataAjax           = [];
		$dataAjax['info']   = $this->identities[$identity];
		$dataAjax['params'] = $this->params[$identity];
		$methodValues       = json_encode([$this->chartPostData => diy_encrypt(json_encode($dataAjax))]);
		$token              = csrf_token();
		$ajax               = "
function requestData{$this->identities[$identity]['string']}() {
    $.ajax({
        url      : '{$chartURI}',
        type     : 'POST',
		headers  : {'X-CSRF-TOKEN': '{$token}'},
        dataType : 'json',
        data     : {$methodValues},
        success  : function(data) {
			$.each(data.series.{$this->chartPostData}.series, function(i, chart) {
				{$this->identities[$identity]['string']}.addSeries({
					name : chart.name,
					data : chart.data,
					type : chart.type
	            });
			});
			$.each(data.category.{$this->chartPostData}, function(i, chart) {
				{$this->identities[$identity]['string']}.xAxis[0].setCategories(chart);
			});
        },
        cache: false
    });
}
		";
		
		return $this->draw("
<div id=\"{$identity}\">IncoDIY Canvas</div>
<script type=\"text/javascript\">{$ajax}</script>
<script type=\"text/javascript\">{$this->canvas[$identity]}</script>

<!-- script type=\"text/javascript\">{$scriptSeries}</script>
<script type=\"text/javascript\">{$chartIdentity}.redraw();</script -->
		");
	}
}