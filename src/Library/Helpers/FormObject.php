<?php
/**
 * Created on 16 Mar 2021
 * Time Created	: 03:17:49
 *
 * @filesource	FormObject.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */

if (!function_exists('diy_form_check_str_attr')) {
	
	/**
	 * Check String Contains In Attribute
	 *
	 * created @Sep 21, 2018
	 * author: wisnuwidi
	 * 
	 * @param array $attributes
	 * @param string $string
	 * 
	 * @return boolean
	 */
	function diy_form_check_str_attr($attributes, $string) {
		if ((isset($attributes['class']) && 
				true === str_contains($attributes['class'], $string)) || 
			isset($attributes['id']) && 
				true === str_contains($attributes['id'], $string)
		) {
			return true;
		}
	}
}

if (!function_exists('diy_form_button')) {
	
	/**
	 * Button Builder
	 *
	 * @param string $name
	 * 		: default|primary|info|success|info|warning|danger|inverse|link
	 * 		  pink|purple|yellow|grey|light => this for background coloring
	 *
	 * @param string $label
	 * @param string $tag
	 * 		: button|a
	 *
	 * @param mixed  $link
	 * 		: string => '#link_url' | array ['href', '#link_url']
	 * @param string $color
	 * 		: pink|purple|yellow|grey|light
	 * 		: How to? *) it can be mixed like this 'btn-white btn-yellow'
	 *
	 * @param string $border
	 * 		: bold|round
	 *
	 * @param string $size
	 * 		: minier|xs|sm|lg
	 *
	 * @param string $disabled
	 * 		: disabled
	 *
	 * @param string $icon_name
	 * 		: check|pencil|trash|flag
	 * 		: All icon(s) name
	 *
	 * @param string $icon_color
	 * 		: pink|purple|yellow|grey|light
	 * 		: It would ignored when the $icon_name is false
	 *
	 * @return string
	 */
	function diy_form_button (
		$name,
		$label		= false,
		$action		= [],
		$tag		= 'button',
		$link		= false,
		$color		= 'white',
		$border		= false,
		$size		= false,
		$disabled	= false,
		$icon_name	= false,
		$icon_color	= false
	) {
				
		$url = false;
		if (false !== $link) {
			if (is_array($link)) {
				$keyLink = key($link);
				$urlLink = $link[$keyLink];
				
				$url = " {$keyLink}=\"{$urlLink}\"";
			} else {
				$url = ' href="' . $link . '"';
			}
		}
		
		$buttonColor = false;
		if (false !== $color)  $buttonColor = " btn-{$color}";
		
		$buttonTag = $tag;
		if (false === $tag)  $buttonTag = 'button';
		
		$buttonBorder = false;
		if (false !== $border) $buttonBorder = " btn-{$border}";
		
		$buttonDisabled = false;
		if (false !== $disabled) $buttonDisabled = ' disabled';
		
		$icon		= false;
		$iconName	= false;
		if (false !== $icon_name) {
			$iconColor	= false;
			if (false !== $icon_color) $iconColor = " {$icon_color}";
			$iconName	= $icon_name;
			$icon		= '<i class="fa fa-' . $iconName . ' bigger-120' . $iconColor . '"></i>&nbsp; ';
		}
		
		$actions = [];
		if (count($action) >= 1) {
			foreach ($action as $key => $val) {
				$actions[$key] = " {$key} = '{$val}' ";
			}
			$actionElm = implode(' ', $actions);
		} else {
			$actionElm = false;
		}
		
		$button = '<' . $buttonTag . $url . ' class="btn ' . $buttonColor . ' btn-' . $name . $buttonBorder . $buttonDisabled . '" ' . $actionElm . '>';
		if (false !== $icon)  $button .= $icon;
		if (false !== $label) $button .= $label;
		$button .= '</' . $buttonTag . '>';
		
		return $button;
	}
}

if (!function_exists('diy_form_change_input_attribute')) {
	
	/**
	 * Change/Add Input Class Name Attribute
	 * 
	 * @param array $attribute
	 * @param boolean $key
	 * @param boolean $value
	 * 
	 * @return array
	 */
	function diy_form_change_input_attribute($attribute, $key = false, $value = false) {
		$new_attribute	= [$key => $value];
		$attributes		= array_merge_recursive($attribute, $new_attribute);
		
		foreach ($attributes as $keys => $values) {
			if ($key === $keys) $_values = $values;
		}
		
		if (true === is_array($_values)) {
			$values = implode(' ', $_values);
		} else {
			$values = $_values;
		}
		
		$_attribute	= [$key => $values];
		$attribute	= array_merge($attribute, $_attribute);
		
		return $attribute;
	}
}

if (!function_exists('diy_form_set_icon_attributes')) {
	
	/**
	 * Set Icon Attribute for Inputbox
	 *
	 * @param string $string
	 * @param string $attributes
	 *
	 * @return array ['name', 'data']
	 */
	function diy_form_set_icon_attributes($string, $attributes = [], $pos = 'left') {
		$data				= [];
		$data['attr']		= [];
		$data['name']		= $string;
		$str_icon			= false;
		$str_pos			= $pos;
		
		if (true === str_contains($string, '|')) {
			$_string		= explode('|', $string);
			$data['name']	= $_string[0];
			$str_icon		= $_string[1];
			
			if (count($_string) >= 3) {
				$str_pos = $_string[2];
			}
			
			$_attr			= array_merge_recursive($attributes,	['input_icon'		=> $str_icon]);
			$data['attr']	= array_merge_recursive($_attr,			['icon_position'	=> $str_pos]);
		}
		
		return diy_object($data);
	}
}

if (!function_exists('diy_form_active_box')) {
	
	/**
	 * Active Status Combobox Value
	 *
	 * created @Sep 21, 2018
	 * author: wisnuwidi
	 *
	 * @param boolean $en
	 * @return string['No', 'Yes']
	 */
	function diy_form_active_box($en = true) {
		if (true === $en) {
			return [null => ''] + ['No', 'Yes'];
		} else {
			return [null => ''] + ['Tidak Aktif', 'Aktif'];
		}
	}
}


if (!function_exists('diy_form_checkList')) {
	
	/**
	 * Simple Checkbox List Builder
	 *
	 * @param mixed $name
	 * @param string $value
	 * @param string $label
	 * @param string $checked
	 * @param string $class
	 * @param string $id
	 *
	 * @return string
	 */
	function diy_form_checkList($name, $value = false, $label = false, $checked = false, $class = 'success', $id = false) {
		$nameAttr	= false;
		$valueAttr	= false;
		$idAttr		= false;
		$idForAttr	= false;
		$labelName	= '&nbsp;';
		$checkBox	= false;
		
		if (false !== $name)	$nameAttr	= ' name="' . $name . '"';
		if (false !== $value)	$valueAttr	= ' value="' . $value . '"';
		if (false !== $id)		{
			$idAttr		= ' id="' . $id . '"';
			$idForAttr	= ' for="' . $id . '"';
		} else {
			$idAttr		= ' id="' . $name . '"';
			$idForAttr	= ' for="' . $name . '"';
		}
		if (false !== $label)	$labelName	= "&nbsp; {$label}";
		if (false !== $checked)	$checkBox	= ' checked="checked"';
		
		return "<div class=\"ckbox ckbox-{$class}\"><input type=\"checkbox\"{$valueAttr}{$nameAttr}{$idAttr}{$checkBox}><label{$idForAttr}>{$labelName}</label></div>";
	}
}


if (!function_exists('diy_form_alert_message')) {
	
	function diy_form_alert_message($message = 'Success', $type = 'success', $title = 'Success', $prefix = 'fa-check', $extra = false) {
		$prefix_tag = false;
		if (false !== $prefix) $prefix_tag = "<strong><i class=\"fa {$prefix}\"></i> {$title}</strong>";
		
		$o  = "<div class=\"alert alert-block alert-{$type} animated fadeInDown alert-dismissable\">";
		$o .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">";
		$o .= "<i class=\"fa fa-times\"></i>";
		$o .= "</button>";
		$o .= "<p>{$prefix_tag} {$message}</p>";
		$o .= $extra;
		$o .= "</div>";
		
		return $o;
	}
}

if (!function_exists('diy_form_create_header_tab')) {
	
	/**
	 * HTML Header Tab Builder
	 *
	 * @param string $data
	 * @param string $pointer
	 * @param string $active
	 * @param string $class
	 *
	 * @return string
	 */
	function diy_form_create_header_tab($data, $pointer, $active = false, $class = false) {
		$activeClass	= false;
		$classTag		= false;
		$tabName		= ucwords(str_replace('_', ' ', $data));
		
		if ($active) $activeClass	= '' . $active . '';
		if ($class)  $classTag		= '<i class="' . $class . '"></i>';
		
		$string = "<li class=\"nav-item\"><a class=\"nav-link {$activeClass}\" data-toggle=\"tab\" role=\"tab\" href=\"#{$pointer}\">{$classTag}{$tabName}</a></li>";
		
		return $string;
	}
}

if (!function_exists('diy_form_create_content_tab')) {
	
	/**
	 * HTML Content Tab Builder
	 *
	 * @param string $data
	 * @param string $pointer
	 * @param string $active
	 *
	 * @return string
	 */
	function diy_form_create_content_tab($data, $pointer, $active = false) {
		$activeClass = false;
		if (false !== $active) $activeClass = " active show";
		$string = "<div id=\"{$pointer}\" class=\"tab-pane fade{$activeClass}\" role=\"tabpanel\">{$data}</div>";
		
		return $string;
	}
}


if (!function_exists('diy_form_set_active_value')) {
	
	/**
	 * Set Active Value
	 *
	 * created @Sep 7, 2018
	 * author: wisnuwidi
	 *
	 * @param integer|string $value
	 *
	 * @return string
	 */
	function diy_form_set_active_value($value) {
		$val = 'No';
		if (1 == $value) $val = 'Yes';
		
		return $val;
	}
}

if (!function_exists('diy_form_internal_flag_status')) {
	
	/**
	 * Set Flag Status Value
	 *
	 * created @Sep 7, 2018
	 * author: wisnuwidi
	 *
	 * @param integer|string $flag_row
	 *
	 * @return string
	 */
	function diy_form_internal_flag_status($flag_row) {
		$flaging = intval($flag_row);
		if (0 == intval($flaging)) {
			$flag_status = 'Internal <sup>( root )</sup>';
		} elseif (1 == $flaging)  {
			$flag_status = 'End User';
		} else {
			$flag_status = 'Normal <sup>( all )</sup>';
		}
		
		return $flag_status;
	}
}

if (!function_exists('diy_form_request_status')) {
	
	/**
	 * Request Status For Combobox Value
	 *
	 * created @Sep 21, 2018
	 * author: wisnuwidi
	 *
	 * @param boolean $en
	 * @param boolean|integer $num
	 *
	 * @return string['Pending', 'Accept', 'Blocked', 'Banned']
	 */
	function diy_form_request_status($en = true, $num = false) {
		$data = [];
		if (true === $en) {
			$data = ['Pending', 'Accept', 'Blocked', 'Ban'];
		} else {
			$data = ['Pending', 'Terima', 'Block', 'Ban'];
		}
		
		if (false === $num) {
			return $data;
		} else {
			return $data[$num];
		}
	}
}

if (!function_exists('diy_form_get_client_ip')) {
	
	/**
	 * Get Client IP
	 *
	 * author: https://stackoverflow.com/questions/15699101/get-the-client-ip-address-using-php
	 *
	 * @return string
	 * created @Dec 29, 2018
	 */
	function diy_form_get_client_ip() {
		$ipaddress = '';
		
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} else if(isset($_SERVER['REMOTE_ADDR'])) {
			if ('::1' === $_SERVER['REMOTE_ADDR']) {
				$ipaddress = '127.0.0.1';
			} else {
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			}
		} else {
			$ipaddress = 'UNKNOWN';
		}
		
		return $ipaddress;
	}
}