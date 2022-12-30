<?php
namespace Incodiy\Codiy\Library\Components\Charts;

/**
 * Created on Dec 21, 2022
 * 
 * Time Created : 1:32:18 PM
 *
 * @filesource	Charts.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */
class Charts {
	
	public $canvas   = [];
	public $object   = [];
	public $library  = 'highcharts';
	
	private $libraries = [
		'highcharts' => 'ConsoleTVs\Charts\Classes\Highcharts\Chart',
		'chartjs'    => 'ConsoleTVs\Charts\Classes\Chartjs\Chart'
	];
	
	public function __construct() {
		$this->library;
		$this->canvas = diy_random_strings(22, false, 'diy_canvas' . $this->library);
	}
	
	protected function callLibrary() {
		$this->object = new $this->libraries[$this->library]();
	}
	
	public function canvas($library = null) {
		if (!empty($library)) $this->library = $library;
		$this->canvas = diy_random_strings(22, false, 'diy_canvas' . $this->library);
		
		return $this->callLibrary();
	}
}