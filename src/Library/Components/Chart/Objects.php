<?php
namespace Incodiy\Codiy\Library\Components\Chart;

use Incodiy\Codiy\Library\Components\Chart\Models\Line\Basic\LineBasic;

/**
 * Created on Oct 10, 2022
 * 
 * Time Created : 1:15:13 PM
 *
 * @filesource	Objects.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */

class Objects extends Charts {
	use LineBasic;
	
	public $params     = [];
	
	public $elements   = [];
	public $identities = [];
	
	private $chartInfo = 'highcharts';
	private $prefix_indentity;
	
	public function __construct() {
		$this->element_name['chart'] = $this->chartInfo;
		$this->set_prefix();
		
		$this->charts = new Charts();
	}
	
	public function render($object) {
		return $object;
	}
	
	protected function setParams($function_name, $source, $fieldsets = [], $format, $category, $order = null, $group = null) {
		$this->params[$function_name][$source]['fieldsets'] = $fieldsets;
		$this->params[$function_name][$source]['format']    = $format;
		$this->params[$function_name][$source]['category']  = $category;
		$this->params[$function_name][$source]['group']     = $group;
		$this->params[$function_name][$source]['order']     = $order;
	}
	
	private function set_prefix($prefix = 'codiy-charts') {
		$this->prefix_indentity = $prefix . '-' . $this->chartInfo . '-' . diy_random_strings(50, false);
	}
	
	private function setTitle($function_name, $title = null) {
		$titleString = null;
		if (!empty($title)) $titleString = $title;
		if (!empty($this->title)) {
			if (!empty($this->_attributes['title'])) {
				$titleString = $this->_attributes['title'];
			}
		}
		
		$identity = diy_clean_strings("{$this->prefix_indentity}-{$titleString}");
		$this->identities[$function_name][$titleString] = $identity;
	}
	
	private function getTitle($function_name, $title) {
		$this->setTitle($function_name, $title);
		$this->title = $title;
		
		if (!empty($this->identities[$function_name])) {
			if (empty($title) && !empty($this->_attributes['title'])) {
				$this->title = $this->_attributes['title'];
			}
		}
	}
	
	private function draw($initial, $data = []) {
		if ($data) $this->elements[$initial] = $data;
	}
}