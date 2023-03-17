<?php
namespace Incodiy\Codiy\Library\Components\Chart\Models\Line;

/**
 * Created on Oct 11, 2022
 * 
 * Time Created : 10:12:27 AM
 *
 * @filesource	Basic.php
 *
 * @author     wisnuwidi@incodiy.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@incodiy.com
 */
trait Basic {
	use Script;
	
	/**
	 * Build Line Chart
	 * 
	 * @param string $source
	 * 	: table source name
	 * @param array  $fieldsets
	 * 	: [fieldname1, fieldname2, fieldname3]
	 * @param string $format
	 * 	: name:fieldname|data:fieldname::[sum|count|avg,-etc]
	 * @param string $category
	 * 	: fieldname used for chart category
	 * @param string $order
	 * 	: fieldname::[DESC|ASC] order
	 * @param string $group
	 * 	: fieldname group
	 */
	public function line($source, $fieldsets = [], $format, $category = null, $order = null, $group = null) {
		$this->setParams(__FUNCTION__, $source, $fieldsets, $format, $category, $order, $group);
		$this->construct($this->params);
	}
}