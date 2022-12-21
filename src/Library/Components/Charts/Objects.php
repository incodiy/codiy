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
	
	public $elements = [];
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
	
	private function build($type, $name, $labels = [], $data = []) {
		if (empty($this->object)) {
			$this->callLibrary();
		}
		
		if (!empty($this->canvasIdentifier)) {
			$chartId = $this->canvasIdentifier;
		} else {
			$chartId = diy_random_strings(22, false, 'diy');
		}
		
		$chart = $name;
		$chart = $this->object;
		
		$chart->id = $chartId;
		$chart->labels($labels);
		$chart->dataset($name, $type, $data);
		
		$this->draw($name, $chart);
	}
	
	private $canvasIdentifier = null;
	public function canvas() {
		$this->canvasIdentifier = diy_random_strings(22, false, 'diy');
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