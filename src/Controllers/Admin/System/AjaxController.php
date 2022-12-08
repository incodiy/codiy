<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Library\Components\Table\Craft\Datatables;
use Illuminate\Http\Request;
use Incodiy\Codiy\Models\Admin\System\DynamicTables;
use Illuminate\Support\Facades\Response;
use Incodiy\Codiy\Library\Components\Table\Craft\Export;

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
	
	private $ajaxConnection = null;
	
	public function __construct($connection = null) {
		if (!empty($connection)) $this->ajaxConnection = $connection;
	}
		
	public static $ajaxUrli;
	/**
	 * Ajax Post URL Address
	 * 
	 * @param string $init_post
	 * 	: Initialize Post Key
	 * 	  ['AjaxPosF'         : by default]
	 * 	  ['filterDataTables' : for datatables filtering]
	 * @param boolean $return_data
	 * @return string
	 */
	public static function urli($init_post = 'AjaxPosF', $return_data = false) {
		$current_url  = route('ajax.post');
		if ('filterDataTables' === $init_post) {
			$urlset = [$init_post => 'true'];
		} else {
			$urlset = [$init_post => 'true' ,'_token'  => csrf_token()];
		}
		
		$uri = [];
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
				return $this->post_filters();
			} elseif (!empty($_GET['filterDataTables'])) {
				return $this->initFilterDatatables($_GET, $_POST);
			}
		}
	}
	
	private function post_filters() {
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
			$sql       = "{$info['query']} WHERE `{$postKEY}` = '{$postValue}' ORDER BY `{$postKEY}` DESC";
			$queryData = diy_query($sql, 'SELECT', $this->ajaxConnection);
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
	
	private $datatables = [];
	private function datatableClass() {
		$this->datatables = new Datatables();
	}
	
	public $filter_datatables = [];
	protected function filterDataTable(Request $request) {
		$this->datatableClass();
		$this->filter_datatables = $this->datatables->filter_datatable($request);
		
		return $this;
	}
	
	private function initFilterDatatables() {
		if (!empty($_GET['filterDataTables'])) {
			$this->datatableClass();
			return $this->datatables->init_filter_datatables($_GET, $_POST, $this->ajaxConnection);
		}
	}
	
	public function export() {
		$export = new Export();
		return $export->run('assets/resources/exports');
	}
}