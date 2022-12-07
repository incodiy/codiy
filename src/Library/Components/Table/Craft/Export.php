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
	
	public function run($name = null, $path = 'export', $link = null) {
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
				$filters  = $_POST;
				
				if ('dynamics' === $model_source) {
					$model = new DynamicTables(null, $link);
					$model->setTable($table_source);
					if (!empty(array_filter($filters))) {
						$model  = $model->where($filters);
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
						
						return $this->exportCSV($data[$table_source]['export'], $path, "{$user}-$table_source");
					}
				}
			}
		}
	}
	
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
		fputcsv($handle, array_values($columns), '|');
		foreach ($rows as $row) {
			fputcsv($handle, str_replace(';', ' ', $row), '|');
		}
		fclose($handle);
		
		$uri = url()->asset(str_replace('\\', '/', explode('public', $filepath)[1]));
		
		Response::streamDownload($uri, "{$filename}.csv", $headers);
		
		return json_encode(['diyExportStreamPath' => $uri]);
	}
}