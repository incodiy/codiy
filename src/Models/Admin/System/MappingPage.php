<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Illuminate\Database\Eloquent\Model;

/**
 * Created on 10 Sep 2022
 * Time Created	: 23:58:38
 *
 * @filesource	MappingPage.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */

class MappingPage extends Model {
	public $table = 'base_page_privilege';
	
	public $role_data = [];
	public function current_data($group_id, $data = []) {
		$findata = diy_query($this->table)->where('group_id', intval($group_id));
		
		if (!empty($data)) {
			$findata = $findata->where($data);
		}
		
		if (!empty($findata->get())) {
			foreach ($findata->get() as $role_data) {
				$this->role_data[$role_data->module_id][$role_data->target_table][$role_data->target_field_name] = $role_data;
			}
		}
		
		return $this->role_data;
	}
	
	public function insert_process($role_data) {
		foreach ($role_data as $rowdata) {
			$query = diy_query($this->table)
				->where('group_id'         , $rowdata['group_id'])
				->where('module_id'        , $rowdata['module_id'])
				->where('target_table'     , $rowdata['target_table'])
				->where('target_field_name', $rowdata['target_field_name']);
			
			if (is_empty($query->first())) {
				diy_query($this->table)->insert([
					'group_id'           => $rowdata['group_id'],
					'module_id'          => $rowdata['module_id'],
					'target_table'       => $rowdata['target_table'],
					'target_field_name'  => $rowdata['target_field_name'],
					'target_field_value' => $rowdata['target_field_values']
				]);
			} else {
				diy_query($this->table)->update(['target_field_value' => $rowdata['target_field_values']]);
			}
		}dd($role_data);
	}
	
	public static function getTableFields($data) {
		$fields = [];
		if (is_array($data)) {
			foreach ($data as $tableName) {
				foreach (diy_get_table_columns($tableName) as $fieldname) {
					$fields[$fieldname] = $fieldname;
				}
			}
		} else {
			foreach (diy_get_table_columns($data) as $fieldname) {
				$fields[$fieldname]    = $fieldname;
			}
		}
		
		return json_encode($fields);
	}
	
	public static function getFieldValues($data, $node_id = '__node__') {
		$rows     = [];
		$query    = [];
		$fieldset = [];
		
		if (is_array($data)) {
			foreach ($data as $tablename => $requests) {
				if (is_array($requests)) {
					foreach ($requests as $request) {
						
						$fieldNameValue     = $request;
						if (diy_string_contained($request, '::')) {
							$explode         = explode('::', $request);
							$fieldNameValue  = $explode[1];
						}
						
						$rows['table_name'] = $tablename;
						$rows['field_name'] = $fieldNameValue;
						
						$fieldset = $rows['field_name'];
						$query    = diy_query("SELECT `{$rows['field_name']}` FROM {$rows['table_name']} GROUP BY `{$rows['field_name']}`;", 'SELECT');
					}
				} else {
					$explode = explode('::', $requests);
					
					$rows['table_name'] = explode($node_id, $explode[0])[0];
					$rows['field_name'] = $explode[1];
					
					$fieldset = $rows['field_name'];
					$query    = diy_query("SELECT `{$rows['field_name']}` FROM {$rows['table_name']} GROUP BY `{$rows['field_name']}`;", 'SELECT');
				}
			}
			
			$rows  = [];
			foreach ($query as $row) {
				$rows[$row->{$fieldset}] = $row->{$fieldset};
			}
			
			return json_encode($rows);
		}
	}
	
	public static function getData($data, $usein, $node_id) {
		if ('table_name' === $usein) $output = self::getTableFields($data['table_name']);
		if ('field_name' === $usein) $output = self::getFieldValues($data['field_name'], $node_id);
		
		return $output;
	}
}