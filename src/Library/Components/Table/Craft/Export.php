<?php
namespace Incodiy\Codiy\Library\Components\Table\Craft;

use Incodiy\Codiy\Models\Admin\System\DynamicTables;
use Illuminate\Support\Facades\Response;

/**
 * Created on Dec 7, 2022
 * 
 * Time Created : 11:46:28 PM
 * Filename     : Export.php
 *
 * @filesource Export.php
 *
 * @author     wisnuwidi @Incodiy - 2022
 * @copyright  wisnuwidi
 * @email      eclipsync@gmail.com
 */
 
class Export {
	
	public  $delimeter   = '|';
	private $export_path = 'assets/resources/exports';
	
	public function csv($path = null, $link = null) {
		return $this->process('csv', $path, $link);
	}
	
	private function process($type = 'csv', $path = null, $link = null) {
		if (empty($path)) {
			$path = $this->export_path;
		} else {
			$this->export_path = $path;
		}
		
		$data = [];
		if (!empty($_GET['exportDataTables'])) {
			if (true == $_GET['exportDataTables']) {
				if (!empty($_POST['lurExp'])) $link = diy_decrypt($_POST['lurExp']);
				unset($_POST['lurExp']);
				unset($_POST['exportData']);
				
				$table_source = $_GET['difta']['name'];
				$model_source = $_GET['difta']['source'];
				$token        = $_POST['_token'];
				unset($_POST['_token']);
				$filters      = $_POST;
				
				if ('dynamics' === $model_source) {
					$model = new DynamicTables(null, $link);
					$model->setTable($table_source);
					if (!empty(array_filter($filters))) {
						$filterData = [];
						foreach ($filters as $field_name => $field_value) {
							if (!empty($field_value)) {
								$filterData[$field_name] = $field_value;
							}
						}
						$model = $model->where($filterData);
					}
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
						
						if ('csv' === $type) {
							return $this->exportCSV($data[$table_source]['export'], $path, "{$user}-$table_source");
						}
					}
				}
			}
		}
	}
	
	private function generate($type = 'csv', $data = [], $path = null, $filename = 'diyExportData') {
		$pathFile = public_path();
		if (!file_exists($pathFile."/{$path}")) {
			diy_make_dir($pathFile . "/{$path}", 0777, true, true);
		}
		
		$filepath = str_replace('\/', '/', $pathFile . "/{$path}/{$filename}.{$type}");
		$headers  = [
			'Content-Type'        => 'text/' . $type,
			'Content-Disposition' => 'attachment; filename=' . $filename . '.' . $type,
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
		
		if ('csv' === $type) self::createFileCSV($filepath, $columns, $rows);
		
		if (false === diy_string_contained(diy_config('baseURL'), 'public')) {
			$uri = url(diy_config('baseURL') . '/' . $path . '/' . $filename . '.' . $type);
		} else {
			$uri = url()->asset(str_replace('\\', '/', explode('public', $filepath)[1]));
		}
		
		Response::streamDownload($uri, "{$filename}.{$type}", $headers);
		
		return json_encode(['diyExportStreamPath' => $uri]);
	}
	
	private static function createFileCSV($filepath, $columns, $rows) {
		$_columns = [];
		foreach ($columns as $column) {
			$columnLabel = ucwords(str_replace('_', ' ', $column));
			$_columns[$columnLabel] = $columnLabel;
		}
		$columns = $_columns;		
		$handle  = fopen($filepath, 'w');
		fputcsv($handle, array_values($columns), '|');
		foreach ($rows as $row) {
			fputcsv($handle, str_replace(';', ' ', $row), '|');
		}
		fclose($handle);
	}
	
	private function exportCSV($data, $path = null, $filename = 'diyExportDataCSV') {
		return $this->generate('csv', $data, $path, $filename);
		
		/* 
		$pathFile = public_path();		
		$realPath = str_replace('//', '/', "{$pathFile}/{$path}");
		if (!file_exists($realPath)) {
			diy_make_dir($realPath, 0777, true, true);
		}
		 */
	}
}