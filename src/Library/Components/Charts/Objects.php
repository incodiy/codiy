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
	private function build($type, $name, $label = [], $data = []) {
		if (empty($this->object)) $this->callLibrary();
		
		$chart = $name;
		$chart = $this->object;
		$chart->labels($label);
		$chart->dataset($name, $type, $data);
		
		$this->draw($this->canvas, $chart);
	}
	
	public function column($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function line($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function pie($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function area($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function areaspline($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function spline($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
	}
	
	public function scatter($name, $labels = [], $data = []) {
		$this->build(__FUNCTION__, $name, $labels, $data);
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
}