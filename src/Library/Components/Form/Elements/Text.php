<?php
namespace Incodiy\Codiy\Library\Components\Form\Elements;

/**
 * Created on 19 Mar 2021
 * Time Created	: 03:10:46
 *
 * @filesource	Text.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
trait Text {
	
	/**
	 * Create Input Text
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @param boolean $label
	 */
	public function text($name, $value = null, $attributes = [], $label = true) {
		$this->setParams(__FUNCTION__, $name, $value, $attributes, $label);
		$this->inputDraw(__FUNCTION__, $name);
	}
	
	/**
	 * Create Input Textarea
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @param boolean $label
	 */
	public function textarea($name, $value = null, $attributes = [], $label = true) {
		if (diy_form_check_str_attr($attributes, 'ckeditor')) {
			$this->element_plugins[$name] = 'ckeditor';
		}
		
		$this->setParams(__FUNCTION__, $name, $value, $attributes, $label);
		$this->inputDraw(__FUNCTION__, $name);
	}
	
	/**
	 * Create Input Email
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @param boolean $label
	 */
	public function email($name, $value = null, $attributes = [], $label = true) {
		$this->setParams(__FUNCTION__, $name, $value, $attributes, $label);
		$this->inputDraw(__FUNCTION__, $name);
	}
	
	/**
	 * Create Input Number
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @param boolean $label
	 */
	public function number($name, $value = null, $attributes = [], $label = true) {
		$this->setParams(__FUNCTION__, $name, $value, $attributes, $label);
		$this->inputDraw(__FUNCTION__, $name);
	}
	
	/**
	 * Create Input Password
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @param boolean $label
	 */
	public function password($name, $attributes = [], $label = true) {
		$this->setParams(__FUNCTION__, $name, null, $attributes, $label);
		$this->inputDraw(__FUNCTION__, $name);
	}
}