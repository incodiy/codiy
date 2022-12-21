<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Components;

use Incodiy\Codiy\Library\Components\Charts\Objects;
/**
 * Created on Dec 21, 2022
 * 
 * Time Created : 10:17:14 AM
 *
 * @filesource	Charts.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */
trait Charts {
	public $charts;
	
	private function initCharts() {
		$this->charts = new Objects();
		$this->plugins['charts'] = $this->charts;
	}
}