<?php
namespace Incodiy\Codiy\Library\Components\Charts;

use Incodiy\Codiy\Library\Components\Charts\Canvas\Builder;
use Incodiy\Codiy\Library\Components\Form\Elements\Tab;

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
	use Tab;
	
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
	public function canvas($type, $source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		$identity = diy_clean_strings("CoDIY_{$this->chartLibrary}_" . $source . '_' . diy_random_strings(50, false));
		$this->identities[$identity]         = $source;
		
		$this->params[$identity]['type']     = $type;
		$this->params[$identity]['source']   = $source;
		$this->params[$identity]['fields']   = $fieldsets;
		$this->params[$identity]['format']   = $format;
		$this->params[$identity]['category'] = $category;
		$this->params[$identity]['group']    = $group;
		$this->params[$identity]['order']    = $order;
		$this->params[$identity]['series']   = [];
		
		$this->setSeries($identity, 'Data 1', [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]);
		$this->setSeries($identity, 'Data 2', [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]);
		$this->setSeries($identity, 'Data 3', [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]);
		$this->setSeries($identity, 'Target', [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2], 'line');
		
		return $this->draw($identity, $this->chartCanvas($identity, $this->params));
		
		dd($type, $source, $fieldsets, $format, $category, $group, $order, $this->params);
	}
	
	private function setSeries($identity, $name, $data, $type = 'column') {
		$data = [
			'name' => $name,
			'data' => $data,
			'type' => $type
		];
		
		$this->params[$identity]['series'][] = json_encode($data);
	}
	
	private function chartCanvas($identity, $parameters = []) {
		
		$chartIdentity = str_replace('-', '', $identity);
		$scriptCore = "var {$chartIdentity} = new Highcharts.chart('{$identity}', {
  chart: {
    type: 'column'
  },
  title: {
    text: 'Monthly Average Rainfall'
  },
  subtitle: {
    text: 'Source: WorldClimate.com'
  },
  xAxis: {
    categories: [
      'Jan',
      'Feb',
      'Mar',
      'Apr',
      'May',
      'Jun',
      'Jul',
      'Aug',
      'Sep',
      'Oct',
      'Nov',
      'Dec'
    ],
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
  }
});";
		$scriptSeries = '';
		if (!empty($this->params[$identity]['series'])) {
			foreach ($this->params[$identity]['series'] as $series) {
				$scriptSeries .= "{$chartIdentity}.addSeries({$series}, false);";
			}
		}
		
		return "
<div id=\"{$identity}\">Un Drawn Canvas</div>
<script type=\"text/javascript\">{$scriptCore}</script>
<script type=\"text/javascript\">{$scriptSeries}</script>
<script type=\"text/javascript\">{$chartIdentity}.redraw();</script>
		";
	}
}