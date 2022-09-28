<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;

/**
 * Created on Sep 23, 2022
 * 
 * Time Created : 7:51:52 PM
 *
 * @filesource	AjaxController.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */

class AjaxController extends Controller {
	
	public function __construct() {}
		
	public static $ajaxUrli;
	public static function urli($return_data = false) {
		$current_url  = route('ajax.post');
		$urlset       = [
			'AjaxPosF' => 'true'
			,'_token'    => csrf_token()
		];
		
		$uri      = [];
		foreach ($urlset as $fieldurl => $urlvalue) {
			$uri[] = "{$fieldurl}={$urlvalue}";
		}
		
		self::$ajaxUrli = $current_url . '?' . implode('&', $uri);
		if (true === $return_data) {
			return self::$ajaxUrli;
		}
	}
	
	public function post() {
		if (!empty($_GET)) {
			if (!empty($_GET['AjaxPosF'])) {
				unset($_GET['AjaxPosF']);
				unset($_GET['_token']);
				
				$info             = [];
				$info['label']    = null;
				$info['value']    = null;
				$info['selected'] = null;
				$info['query']    = null;
				
				foreach ($_GET as $key => $data) {
					if ('l' === $key) {
						$info['label']    = decrypt($data);
					} elseif ('v' === $key) {
						$info['value']    = decrypt($data);
					} elseif ('s' === $key) {
						$info['selected'] = decrypt($data);
					} else {
						$info['query']    = decrypt($data);
					}
				}
				
				$postKEY   = array_keys($_POST)[0];
				$postValue = array_values($_POST)[0];
				
				$queryData     = [];
				if (!empty($info['query'])) {
					$queryData = diy_query("{$info['query']} WHERE `{$postKEY}` = '{$postValue}' ORDER BY `{$postKEY}` DESC", 'SELECT');
				}
				
				$result = [];
				if (!empty($queryData)) {
					foreach ($queryData as $rowData) {
						$result['data'][$rowData->{$info['value']}] = $rowData->{$info['label']};
					}
				}
				
				if (!empty($info['selected'])) {
					$result['selected'] = $info['selected'];
				}
				$results = $result;
				
				return json_encode($results);
			}
		}
	}
}