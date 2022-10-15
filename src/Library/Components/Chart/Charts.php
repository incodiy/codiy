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
	
	private $_attributes = [];
	private function set_attributes($function_name, $value) {
		$this->_attributes[$function_name] = $value;
	}
	
	protected $title;
	public function title($title = []) {
		$_title = null;
		if (!empty($title)) {
			if (!empty($title['text'])) $_title = $title['text'];
		}
		
		$this->set_attributes(__FUNCTION__, $_title);
		$this->title = 'title:' . json_encode($title) . ',';
	}
	
	protected $subtitle;
	public function subtitle($subtitle = []) {
		$this->set_attributes(__FUNCTION__, $subtitle);
		$this->subtitle = 'subtitle:' . json_encode($subtitle) . ',';
	}
	
	protected $legends;
	public function legends($legends = []) {
		$this->set_attributes(__FUNCTION__, $legends);
		$this->legends = 'legend:' . json_encode($legends) . ',';
	}
	
	protected $tooltips;
	public function tooltips($tooltips = []) {
		$this->set_attributes(__FUNCTION__, $tooltips);
		$this->tooltips = 'tooltip:' . json_encode($tooltips) . ',';
	}
	
	protected $categories;
	public function category($category = [], $axis = 'x') {
		$this->set_attributes(__FUNCTION__, $category);
		$this->categories['data'] = 'categories:' . json_encode($category) . ',';
		$this->categories['axis'] = $axis;
	}
	
}