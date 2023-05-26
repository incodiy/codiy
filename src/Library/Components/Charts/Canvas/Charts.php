<?php
namespace Incodiy\Codiy\Library\Components\Charts\Canvas;
/**
 * Created on May 25, 2023
 * 
 * Time Created : 10:11:30 AM
 *
 * @filesource  Charts.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
class Charts extends Builder {
	
	public $attributes = [];
	public $sync       = [];
	
	public function __construct() {
		parent::__construct();
	}
	
	public function syncWith($object = []) {		
		if (!empty($object)) {
			$this->sync['filter'] = $object->conditions;
		}
	}
	
	public function canvas($type, $source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		$this->setParams($type, $source, $fieldsets, $format, $category, $group, $order);
		
		return $this->chartCanvas($this->sourceIdentity);
	}
	
	public function column($source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		return $this->canvas(__FUNCTION__, $source, $fieldsets, $format, $category, $group, $order);
	}
	
	public function line($source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		return $this->canvas(__FUNCTION__, $source, $fieldsets, $format, $category, $group, $order);
	}
	
	public function bar($source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		return $this->canvas(__FUNCTION__, $source, $fieldsets, $format, $category, $group, $order);
	}
	
	public function title($title, $options = []) {
		$attributes = ['text' => $title];
		if (!empty($options)) $attributes = array_merge_recursive($attributes, $options);
		
		$this->setAttributes(__FUNCTION__, $attributes);
	}
	
	public function subtitle($title, $options = []) {
		$attributes = ['text' => $title];
		if (!empty($options)) $attributes = array_merge_recursive($attributes, $options);
		
		$this->setAttributes(__FUNCTION__, $attributes);
	}
	
	public function axisTitle($title, $options = []) {
		$attributes = ['text' => $title];
		if (!empty($options)) $attributes = array_merge_recursive($attributes, $options);
		
		$this->setAttributes(__FUNCTION__, $attributes);
	}
	
	public function tooltip($title, $options = []) {
		$attributes = ['text' => $title];
		if (!empty($options)) $attributes = array_merge_recursive($attributes, $options);
		
		$this->setAttributes(__FUNCTION__, $attributes);
	}
	
	public function plotOptions($title, $options = []) {
		$attributes = ['text' => $title];
		if (!empty($options)) $attributes = array_merge_recursive($attributes, $options);
		
		$this->setAttributes(__FUNCTION__, $attributes);
	}
}