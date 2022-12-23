<?php
namespace Incodiy\Codiy\Library\Components\Charts;

use ConsoleTVs\Charts\Classes\Highcharts\Chart;

/**
 * Created on Dec 21, 2022
 * 
 * Time Created : 10:15:08 AM
 *
 * @filesource	Objects.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */
class Objects extends Charts {
	
	public $elements     = [];
	public $element_name = [];
	
	public function __construct() {
		parent::__construct();
	}
	
	public function render($object) {
		return $object;
	}
	
	private function draw($initial, $data = []) {
		$this->element_name['charts'] = $this->library;
		if ($data) {
			$this->elements['charts'][$initial] = $data;
		}
	}
	
	private $name   = [];
	private $charts = [];
	
	/**
	 * 
	 * @param string $type
	 * @param string $name
	 * @param array $label
	 * @param array $data
	 * @param array $options
	 * 	[
				'chart' => [
					'params' => [
						['labelsRotation', -20],
						['minimalist', false]
					]
				],
				'data' => ['color' => 'red']
			]
	 */
	private function build($type, $name, $label = [], $data = [], $options = []) {
		if (empty($this->object)) $this->callLibrary();
		
		$chart = $name;
		$chart = $this->object;
		$chart->labels($label);	
		if (!empty($options['chart'])) {
			foreach ($options['chart'] as $opType => $opts) {
				if ('params' === $opType) {
					foreach ($opts as $optChart) {
						$chart->{$optChart[0]}($optChart[1]);
					}
				}
			}
		}
		
		if (!empty($options['data'])) {
			$chart->dataset($name, $type, $data)->options($options['data']);
		} else {
			$chart->dataset($name, $type, $data);
		}
		
		$this->draw($this->canvas, $chart);
	}
	
	public function bar($name, $labels = [], $data = [], $options = []) {
		$this->build(__FUNCTION__, $name, $labels, $data, $options);
	}
	
	public function column($name, $labels = [], $data = [], $options = []) {
		$this->build(__FUNCTION__, $name, $labels, $data, $options);
	}
	
	public function line($name, $labels = [], $data = [], $options = []) {
		$this->build(__FUNCTION__, $name, $labels, $data, $options);
	}
	
	public function pie($name, $labels = [], $data = [], $options = []) {
		$this->build(__FUNCTION__, $name, $labels, $data, $options);
	}
	
	public function area($name, $labels = [], $data = [], $options = []) {
		$this->build(__FUNCTION__, $name, $labels, $data, $options);
	}
	
	public function areaspline($name, $labels = [], $data = [], $options = []) {
		$this->build(__FUNCTION__, $name, $labels, $data, $options);
	}
	
	public function spline($name, $labels = [], $data = [], $options = []) {
		$this->build(__FUNCTION__, $name, $labels, $data, $options);
	}
	
	public function scatter($name, $labels = [], $data = [], $options = []) {
		$this->build(__FUNCTION__, $name, $labels, $data, $options);
	}
	
	
	
	public function gauge($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function arearange($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function areasplinerange($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function columnrange($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function bellcurve($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function columnpyramid($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function errorbar($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
}