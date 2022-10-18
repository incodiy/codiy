<?php
namespace Incodiy\Codiy\Library\Components\Chart;

/**
 * Created on Oct 10, 2022
 * 
 * Time Created : 1:50:54 PM
 *
 * @filesource	Charts.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */
class Charts {
	
	public $attributes = [];
	
	private function setAttributes($function_name, $value) {
		$this->attributes[$function_name] = $value;
	}
	
	public function title($title) {
		$this->setAttributes(__FUNCTION__, $title);
	}
	
	public function subtitle($subtitle) {
		$this->setAttributes(__FUNCTION__, $subtitle);
	}
	
	public function legends($legends) {
		$this->setAttributes(__FUNCTION__, $legends);
	}
	
	public function tooltips($tooltips) {
		$this->setAttributes(__FUNCTION__, $tooltips);
	}
	
	public function category($category, $axis = 'x') {
		$this->set_attributes(__FUNCTION__, ['data' => $category, 'axis' => $axis]);
	}
}