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
	private $checkId     = null;
	public function __construct() {
		parent::__construct();
		
	}
	
	public function render($object) {
		return $object;
	}
	
	private function draw($initial, $data = []) {
		$charts = [];
		foreach ($data as $key => $item) {
			$charts[$key] = $item;
		}
		dump($charts);
	/* 	$this->element_name['charts'] = $this->library;
		if ($data) {
			$this->elements['charts'][$initial] = $data;
		} */
	}
	
	private $name   = [];
	private $charts = [];
	private function build($type, $name, $label = [], $data = []) {
		if (empty($this->object)) $this->callLibrary();
		
		$chart = $name;
		$chart = $this->object;/* 
		$chart->labels($label);
		$chart->dataset($name, $type, $data);
		 */
		$this->name = diy_clean_strings($name);
		$this->charts[$this->identity[$this->canvaser]][$this->name] = $chart;/* 
		$this->charts[$this->identity[$this->canvaser]][$this->name] = $chart->labels($label);
		$this->charts[$this->identity[$this->canvaser]][$this->name] = $chart->dataset($name, $type, $data); */
		
		$this->canvaser($this->charts);
	}
	
	private function canvaser($charts) {
		$this->draw($this->name, $charts[$this->identity[$this->canvaser]]);
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
}