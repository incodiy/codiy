<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Library\Components\Table\Craft\Datatables;
use Illuminate\Http\Request;
use Incodiy\Codiy\Models\Admin\System\DynamicTables;
use Illuminate\Support\Facades\Response;

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
	//	if (!empty($_POST)) dd($_POST, $connection);
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
	
	public function export($name = null, $path = 'export') {
		$data = [];
		if (!empty($_GET['exportDataTables'])) {
			if (true == $_GET['exportDataTables']) {
				$table_source = $_GET['difta']['name'];
				$model_source = $_GET['difta']['source'];
				$token        = $_POST['_token'];
				unset($_POST['_token']);
				
				if ('dynamics' === $model_source) {
					$model = new DynamicTables(null, $this->connection);
					$model->setTable($table_source);
					$data[$table_source]['model'] = get_class($model);
					
					foreach ($model->get() as $i => $mod) {
						foreach ($mod->getAttributes() as $fieldname => $fieldvalue) {
							$data[$table_source]['export']['head'][$fieldname]       = $fieldname;
							$data[$table_source]['export']['values'][$i][$fieldname] = $fieldvalue;
						}
					}
					
					if (!empty($data)) {
						$user = auth()->user()->username;
						$time = date('Ymd');
						$path = "{$path}/{$user}/{$token}/{$time}/{$table_source}";
						
						return $this->exportCSV($data[$table_source]['export'], $path, "{$user}-$table_source");
					}
				}
			}
		}
	}
	
	public $exportRedirection = null;
	
	private function exportCSV($data, $path = null, $filename = 'diyExportDataCSV') {
		if (!file_exists(public_path()."/{$path}")) {
			diy_make_dir(public_path() . "/{$path}", 0777, true, true);
		}
		
		$filepath = public_path("{$path}/{$filename}.csv");
		$headers  = [
			'Content-Type'        => 'text/csv',
			'Content-Type'        => 'application/octet-stream',
			'Content-Disposition' => 'attachment; filename=' . $filepath,
			"Pragma"              => "no-cache",
			"Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
			"Expires"             => "0"
		];
		
		$columns  = $data['head'];
		$values   = $data['values'];
		
		$rows     = [];
		foreach ($values as $i => $valueData) {
			foreach ($valueData as $fieldname => $value) {
				$rows[$i][$fieldname] = $value;
			}
		}
		
		$handle   = fopen($filepath, 'w');
		fputcsv($handle, array_values($columns));
		foreach ($rows as $row) {
			fputcsv($handle, $row);
		}
		fclose($handle);
		
		//	$filepath = explode('public', $filepath);
		$this->exportRedirection = url()->asset(str_replace('\\', '/', explode('public', $filepath)[1]));
		
		Response::streamDownload($this->exportRedirection, "{$filename}.csv", $headers);
		
		$jsonData = json_encode(['diyExportStreamPath' => $this->exportRedirection]);
		
		return $jsonData;
	}
}