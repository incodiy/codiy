<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Components;

use Incodiy\Codiy\Library\Components\Chart\Objects;

/**
 * Created on Oct 10, 2022
 * 
 * Time Created : 1:20:42 PM
 *
 * @filesource	Chart.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */

trait Chart {
	public $chart;
	
	private function initChart() {
		$this->chart = new Objects();
		$this->plugins['chart'] = $this->chart;
	}
}