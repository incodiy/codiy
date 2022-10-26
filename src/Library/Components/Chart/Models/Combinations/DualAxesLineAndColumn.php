<?php
namespace Incodiy\Codiy\Library\Components\Chart\Models\Combinations;

/**
 * Created on Oct 24, 2022
 * 
 * Time Created : 4:19:42 PM
 *
 * @filesource	DualAxesLineAndColumn.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */

trait DualAxesLineAndColumn {
	use Script;
	
	public function dualAxesLineAndColumn($source, $fieldsets = [], $format, $category = null, $order = null, $group = null) {
		$this->setParams(__FUNCTION__, $source, $fieldsets, $format, $category, $order, $group);
		$this->construct($this->params);
	}
}