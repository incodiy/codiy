<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

/**
 * Created on 10 Mar 2021
 * Time Created	: 13:28:50
 *
 * @filesource	App.php
 *Schema::getColumnListing($table_name);
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */

if (!function_exists('diy_config')) {
	
	/**
	 * Get Config
	 *
	 * created @Sep 28, 2018
	 * author: wisnuwidi
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function diy_config($string, $fileNameSettings = 'settings') {
		return Illuminate\Support\Facades\Config::get("diy.{$fileNameSettings}.{$string}");
	}
}

if (!function_exists('diy_template_config')) {
	
	/**
	 * Get Template Config Data
	 *
	 * created @Sep 28, 2018
	 * author: wisnuwidi
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function diy_template_config($string) {
		return diy_config("{$string}", 'templates');
	}
}

if (!function_exists('diy_current_template')) {
	
	/**
	 * Get Current Used Template
	 *
	 * created @Sep 28, 2018
	 * author: wisnuwidi
	 *
	 * @return string
	 */
	function diy_current_template() {
		return diy_config('template');
	}
}

if (!function_exists('diy_base_assets')) {
	
	/**
	 * Get Base Assets URL
	 *
	 * created @Sep 28, 2018
	 * author: wisnuwidi
	 *
	 * @return string
	 */
	function diy_base_assets() {
		return diy_config('baseURL') . '/' . diy_config('base_template') . '/' . diy_config('template');
	}
}

if (!function_exists('diy_object')) {
    
    /**
     * Create Object
     *
     * @param mixed $array
     *
     * @return object
     */
    function diy_object($array) {
        return (object) $array;
    }
}

if (!function_exists('diy_get_model')) {
	
	/**
	 * Get Model
	 *
	 * @param object $model
	 * @param boolean $find
	 *
	 * @return object|array
	 */
	function diy_get_model($model, $find = false) {
		$model = new $model;
		if (false !== $find) {
			$model = $model->find($find);
		}
		
		return $model;
	}
}

if (!function_exists('diy_query')) {
	
	function diy_query($sql, $type = 'select') {
		return Illuminate\Support\Facades\DB::{$type}($sql);
	}
}

if (!function_exists('diy_get_table_name_from_sql')) {
	
	function diy_get_table_name_from_sql($sql) {
		$query = explode('from ', $sql);
		$query = explode(' ', $query[1]);
		
		return $query[0];
	}
}

if (!function_exists('diy_encrypt')) {
	
	/**
	 * Encrypt
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function diy_encrypt($string) {
		return Illuminate\Support\Facades\Crypt::encryptString($string);
	}
}

if (!function_exists('diy_decrypt')) {
	
	/**
	 * Decrypt
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function diy_decrypt($string) {
		return Illuminate\Support\Facades\Crypt::decryptString($string);
	}
}

if (!function_exists('diy_clean_strings')) {
	
	/**
	 * Clean Strings
	 *
	 * @param string $strings
	 * @return string
	 */
	function diy_clean_strings($strings) {
		$strings = trim(preg_replace('/[;\.\/\?\\\:@&=+\$,_\~\*\'"\!\|%<>\{\}\^\[\]`\-]/', ' ', $strings));
		
		return strtolower(preg_replace('/\s+/', '-', $strings));
	}
}

if (!function_exists('diy_string_contained')) {
	
	/**
	 * Find contained character in string(s)
	 *
	 * @param string $string
	 * @param string $find
	 *
	 * @return boolean
	 */
	function diy_string_contained($string, $find) {
		if (is_array($find)) {
			foreach ($find as $str) if (strpos($string, $str) !== false) return true;
		} else {
			if (strpos($string, $find) !== false) return true;
		}
		
		return false;
	}
}

if (!function_exists('diy_underscore_to_camelcase')) {
    
    /**
     * Convert Character with an Underscore to Camel/Uppercase
     *
     * @param string $str
     *
     * @return string
     * 		This function will convert string to UPPERCASE if string length <= 3
     */
    function diy_underscore_to_camelcase($str) {
        $string = false;
        if (true === str_contains($str, '_')) {
            $slices		= explode('_', $str);
            $strings	= [];
            
            foreach ($slices as $str) {
                $_str = ucwords($str);
                if (strlen($str) <= 3) $_str = strtoupper($str);
                
                $strings[] = $_str;
            }
            $new_str = implode(' ', $strings);
            $string = ucwords($new_str);
        } else {
            $string = ucwords($str);
        }
        
        return $string;
    }
}

if (!function_exists('diy_url')) {
    
    /**
     * Get Url
     *
     * @param string $string
     *
     * @return string
     */
    function diy_url($string) {
        return url()->{$string}();
    }
}

if (!function_exists('diy_array_to_object_recursive')) {
    
    /**
     * Converting multidimensional array to object
     *
     * @param array $array
     * @return StdClass|array
     *
     * @link: https://stackoverflow.com/questions/9169892/how-to-convert-multidimensional-array-to-object-in-php
     */
    function diy_array_to_object_recursive($array) {
        if (is_array($array) ) {
            foreach($array as $key => $value) {
                $array[$key] = diy_array_to_object_recursive($value);
            }
            
            return (object) $array;
        }
        
        return $array;
    }
}

if (!function_exists('diy_array_insert')) {
	
	function diy_array_insert(&$array, $position, $insert) {
		if (is_int($position)) {
			array_splice($array, $position, 0, $insert);
		} else {
			$pos   = array_search($position, array_keys($array));
			$array = array_merge (
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
			);
		}
	}
}

if (!function_exists('diy_exist_url')) {
    
    /**
     * Check if url exist
     * 
     * @param string $url
     * 
     * @return boolean
     */
    function diy_exist_url($url) {
        $file_headers = @get_headers($url);
        
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        
        return true;
    }
}

if (!function_exists('diy_not_empty')) {
    
    /**
     * Checking Not Empty Data
     *
     * @param mixed $data
     * @return mixed|boolean
     */
    function diy_not_empty($data) {
        if (isset($data) && !empty($data) && '' != $data && NULL != $data) {
            return $data;
        } else {
            return false;
        }
    }
}

if (!function_exists('diy_is_empty')) {
    
    /**
     * Checking Empty Data
     *
     * @param mixed $data
     * @return boolean
     */
    function diy_is_empty($data) {
        return !diy_not_empty($data);
    }
}

if (!function_exists('diy_object')) {
	
	/**
	 * Create Object
	 *
	 * @param mixed $array
	 *
	 * @return object
	 */
	function diy_object($array) {
		return (object) $array;
	}
}

if (!function_exists('camel_case')) {
	
	/**
	 * Camel Case
	 *
	 * created @Sep 21, 2018
	 * author: wisnuwidi
	 *
	 * @param string $string
	 * @return Illuminate\Support\Str
	 */
	function camel_case($string) {
		return ucfirst($string);
	}
}

if (!function_exists('diy_random_strings')) {
	
	/**
	 * Random String
	 *
	 * @param number $length
	 * @return string
	 */
	function diy_random_strings($length = 8, $symbol = true) {
		$random_strings	= '';
		$strSymbol		= false;
		if (true === $symbol) {
			$strSymbol = '!@#$%';
		}
		$strings		= "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz{$strSymbol}";
		$stringsLength	= strlen($strings);
		
		for ($i = 0; $i < $length; $i ++) {
			$random_strings .= $strings[rand(0, $stringsLength - 1)];
		}
		
		return $random_strings;
	}
}

if (!function_exists('diy_unescape_html')) {
	
	/**
	 * Returning Back Escaped HTML
	 *
	 * created @Sep 7, 2018
	 * author: wisnuwidi
	 *
	 * @param string $html
	 *
	 * @return \Illuminate\Support\HtmlString
	 */
	function diy_unescape_html($html) {
		return new Illuminate\Support\HtmlString($html);
	}
}

if (!function_exists('get_object_called_name')) {
	
	/**
	 * Get Called Name Object
	 *
	 * created @Sep 7, 2018
	 * author: wisnuwidi
	 *
	 * @param object $object
	 *
	 * @return string
	 */
	function get_object_called_name($object) {
		return strtolower(last(explode('\\', get_class($object))));
	}
}

if (!function_exists('current_route')) {
	
	/**
	 * Get Current Route Name
	 *
	 * created @Sep 7, 2018
	 * author: wisnuwidi
	 *
	 * @return string
	 */
	function current_route() {
		return Route::currentRouteName();
	}
}

if (!function_exists('diy_current_route')) {
	/**
	 * Get Current Route
	 * created @Dec 11, 2018
	 * author: wisnuwidi
	 *
	 * @param boolean $facadeRoot
	 * @return object|mixed|string[]|object[]|\Illuminate\Contracts\Foundation\Application
	 */
	function diy_current_route($facadeRoot = false) {
		if (false === $facadeRoot) return Route::getCurrentRoute();
		else return Route::getFacadeRoot();
	}
}

if (!function_exists('diy_current_baseroute')) {
	/**
	 * Get Base Route From Current Route
	 *
	 * created @Dec 11, 2018
	 * author: wisnuwidi
	 *
	 * @param boolean $facadeRoot
	 * @return mixed
	 */
	function diy_current_baseroute() {
		$lastRoute = _last_explode('.', _current_route()->getName());
		
		return str_replace(".{$lastRoute}", '', _current_route()->getName());
	}
}

if (!function_exists('diy_get_current_route_id')) {
	
	/**
	 * Get ID From Current Route
	 *
	 * created @Apr 12, 2021
	 * author: wisnuwidi
	 *
	 * @return string
	 */
	function diy_get_current_route_id() {
		$currentDataURL = explode('/', diy_current_url());
		unset($currentDataURL[array_key_last($currentDataURL)]);
		
		return intval($currentDataURL[array_key_last($currentDataURL)]);
	}
}

if (!function_exists('diy_route_request_value')) {
	
	/**
	 * Get Route Value From Request
	 *
	 * created @Sep 21, 2018
	 * author: wisnuwidi
	 *
	 * @param string $field
	 * 
	 * @return string
	 */
	function diy_route_request_value($field) {
		$request = new Request();
		$request->route($field);
	}
}

if (!function_exists('diy_current_url')) {

	/**
	 * Get Current URL
	 *
	 * created @Sep 21, 2018
	 * author: wisnuwidi
	 *
	 * @return string
	 */
	function diy_current_url() {
		return url()->current();
	}
}

if (!function_exists('minify_code')) {
	
	/**
	 * Sanitizing Output
	 *
	 * Copyed From: http://php.net/manual/en/function.ob-start.php#71953:sanitize_output
	 *
	 * @param array $buffer
	 * @return mixed
	 */
	function sanitize_output($buffer) {
		/**
		 * (1) Strip whitespaces after tags, except space
		 * (2) Strip whitespaces before tags, except space
		 * (3) Shorten multiple whitespace sequences
		 * (4) Remove empty lines (between HTML tags)
		 * 			: cannot remove just any line-end characters because in inline JS they can matter!
		 * (5) Remove unwanted HTML comments <!-- text -->
		 * (6) Remove unwanted HTML comments /**
		 */
		
		$search  = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/\>[\r\n\t ]+\</s', '/<!--(.|\s)*?-->/', '~^\s*//.*$\s*~m');
		$replace = array('>', '<', '\\1', '><', '', '');
		
		return set_break_line_html("\n", 1986) . preg_replace($search, $replace, $buffer);
	}
	
	/**
	 * Minified HTML output in a single line
	 * 		: Remember to remove all double slash comment(s) in your javascript code !!!
	 *
	 * @param string $output
	 */
	function minify_code($output = true) {
		if (false !== $output) {
			ob_start('sanitize_output');
		}
	}
}



if (!function_exists('diy_insert')) {
	
	/**
	 * Simply Insert POST Data to Database
	 *
	 * @param object $model
	 * @param array $request
	 * @param string $get_field
	 * 
	 * @return string last inserted ID
	 */
	function diy_insert($model, $data, $get_field = false) {
		$request = [];
		if (true === is_object($data)) {
			$request = $data;
		} else {
			$req		= new Request();
			$request	= $req->merge($data);
		}
		
		$requests = [];
		foreach ($request->all() as $key => $value) {
			// manipulate value requested by checkbox and/or multiple selectbox
			if (is_array($value)) {
				$value = implode(',', $value);
			}
			
			// manipulate value requested by date and/or datetime
			if ("____-__-__" === $value || "____-__-__ __:__:__" === $value) {
				$value = null;
			}
			
			$requests[$key] = $value;
		}
		$request->merge($requests);
		
		$modelName = new $model($request->all());
		if (true === array_key_exists('password', $request->all())) {
			$modelName->fill(['password' => Hash::make($request->get('password'))]);
		}
		
		$modelName = $model::create($request->all());
		
		if (false !== $get_field) {
			if (true === $get_field) {
				return $modelName->id;
			} else {
				return $modelName->{$get_field};
			}
		}
	}
}

if (!function_exists('diy_update')) {
	
	/**
	 * Get Current Route Name
	 *
	 * created @Sep 7, 2018
	 * author: wisnuwidi
	 *
	 * @return string
	 */
	function diy_update($model, $data) {
		$request = [];
		if (true === is_object($data)) {
			$request = $data;
		} else {
			$req = new Request();
			$request = $req->merge($data);
		}
		
		$requests = [];
		foreach ($request->all() as $key => $value) {
			// manipulate value requested by checkbox and/or multiple selectbox
			if (is_array($value)) {
				$value = implode(',', $value);
			}
			
			// manipulate value requested by date and/or datetime
			if ("____-__-__" === $value || "____-__-__ __:__:__" === $value) {
				$value = null;
			}
			
			$requests[$key] = $value;
		}
		$request->merge($requests);
		
		$modelName = new $model($request->all());
		if (true === array_key_exists('password', $request->all())) {
			$modelName->fill(['password' => Hash::make($request->get('password'))]);
		}
		
		$modelName = $model->update($request->all());
	}
}

if (!function_exists('diy_delete')) {
	
	/**
	 * Simply Delete(Soft) and or Restore deleted row from database
	 *
	 * @param object $request
	 * @param int $id
	 * @param object $model_name
	 *
	 * created @Aug 10, 2018
	 * author: wisnuwidi
	 */
	function diy_delete($request, $data, $id) {
		$model = $data->find($id);
		if (!empty($model->id)) {
			$model->delete();
		//	$model->update(['active' => 0]);
		} else {
			$remodel = $model::withTrashed()->find($id);
			$remodel->restore();
		//	$remodel->update(['active' => 1]);
		}
	}
}

if (!function_exists('diy_set_filesize')) {
	
	/**
	 * Set File Size
	 *
	 * created @Sep 8, 2018
	 * author: wisnuwidi
	 *
	 * @param integer $size
	 * @param string $type
	 *
	 * @return number
	 */
	function diy_set_filesize($size, $type = 'M') {
		if ('M' === $type) $megabytes = 1024;
		$filesize = intval($megabytes*intval($size));
		ini_set('upload_max_filesize', "{$filesize}{$type}");
		ini_set('post_max_size', "{$filesize}{$type}");
		
		return $filesize;
	}
}

if (!function_exists('diy_image_validations')) {
	
	/**
	 * Set Image Validations
	 *
	 * created @Sep 8, 2018
	 * author: wisnuwidi
	 *
	 * @param boolean|integer $max_size
	 *
	 * @return string
	 */
	function diy_image_validations($max_size = false) {
		$max = false;
		if (false !== $max_size) $max = "|max:{$max_size}";
		
		return "image|mimes:jpeg,png,jpg,gif,svg{$max}";
	}
}

if (!function_exists('diy_file_validations')) {
	
	/**
	 * Set File Validations
	 *
	 * created @Oct 16, 2018
	 * author: wisnuwidi
	 *
	 * @param string $mimes
	 * @param boolean|integer $max_size
	 *
	 * @return string
	 */
	function diy_file_validations($mimes = "txt", $max_size = false) {
		$max = false;
		if (false !== $max_size) $max = "|max:{$max_size}";
		
		return "text|mimes:{$mimes}{$max}";
	}
}

if (!function_exists('diy_file_exist')) {
	
	function diy_file_exist($filePath) {
		return File::exists($filePath);
	}
}

if (!function_exists('diy_make_dir')) {
	
	/**
	 * Create New Directory
	 *
	 * created @Jul 13, 2018
	 * author: wisnuwidi
	 *
	 * @param string $filePath
	 * @param number $mode
	 * @param bool $recursive
	 * @param bool $force
	 */
	function diy_make_dir($filePath, $mode = 0777, $recursive = true, $force = true) {
		if (false === diy_file_exist($filePath)) {
			File::makeDirectory($filePath, $mode, $recursive, $force);
		}
	}
}

if (!function_exists('diy_action_buttons')) {
	
	function diy_action_buttons($route_info, $background_color = 'white') {
		if (!empty($route_info)) {
			$box = '';
			$box .= "<div class=\"header {$background_color}\">";
			foreach ($route_info->action_page as $key => $value) {
				$keys	= explode('|', $key);
				$color	= $keys[0];
				$text	= $keys[1];
				
				if (!diy_string_contained($text, 'delete')) {
					$box .= diy_action_button_box($value, $text, $color);
				} else {
					$routeInfo	= explode('::', $value);
					$routeUri	= [$routeInfo[0], (int)$routeInfo[1]];
					
					$box .= Collective\Html\FormFacade::open(['route'=> $routeUri, 'method'=>'Delete', 'onsubmit' => 'confirm("Are you sure?")']);
					$box .= diy_action_button_box('submitButtonTag', $text, $color);
					$box .= Collective\Html\FormFacade::close();
				}
			}
			$box .= "</div>";
			
			return $box;
		}
	}
}

if (!function_exists('encode_id')) {
	
	function encode_id($id, $hashing = true) {
		$hash = false;
		if (true === $hashing) $hash = hash_code_id();
		
		return intval($id + 8 * 800 / 80) . $hash;
	}
}

if (!function_exists('decode_id')) {
	
	function decode_id($id, $hashing = true) {
		$hash = false;
		if (true === $hashing) $hash = hash_code_id();
		$ID = str_replace($hash, "", $id);
		
		return intval($ID - 8 * 800 / 80) . $hash;
	}
}

if (!function_exists('hash_code_id')) {
	
	function hash_code_id() {
		return hash('haval128,4', 'IBHRS');
	}
}

if (!function_exists('diy_action_button_box')) {
	
	function diy_action_button_box($url, $button_text, $url_class = false, $panel_class = 'panel-title header-list-panel') {
		$buttonText = ucwords($button_text);
		if (empty($url_class)) {
			$urlClass = 'btn btn-default btn_create btn-slideright button-app action-button pull-right';
		} else {
			$urlClass = "btn btn-{$url_class} btn_create btn-slideright button-app action-button pull-right";
		}
		
		$box  = "<h3 class=\"{$panel_class}\">";
		if ('submitButtonTag' === $url) {
			$box .= "<button class=\"{$urlClass}\" type=\"submit\">{$buttonText}</button>";
		} else {
			$box .= "<a href=\"{$url}\" class=\"{$urlClass}\">{$buttonText}</a>";
		}
		$box .= "</h3>";
		
		return $box;
	}
}