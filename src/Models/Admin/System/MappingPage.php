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
	
	public static function getFieldName($data, $usein, $node_id) {
		if ('table_name' === $usein) {
			$fields = [];
			foreach (diy_get_table_columns($data['table_name']) as $fieldname) {
				$fields[$fieldname] = $fieldname;
			}
			
			$data = json_encode($fields);
		}
		
		if ('field_name' === $usein) {
			$rows  = [];
			$query = [];
			if (is_array($data['field_name'])) {
				
				$fieldset = [];
				foreach ($data['field_name'] as $value) {
					$explode = explode('::', $value);
					
					$rows['table_name'] = explode($node_id, $explode[0])[0];
					$rows['field_name'] = $explode[1];
					
					$fieldset = $rows['field_name'];
					$query    = diy_query("SELECT `{$rows['field_name']}` FROM {$rows['table_name']} GROUP BY `{$rows['field_name']}`;", 'SELECT');
				}
				
				$rows  = [];
				foreach ($query as $row) {
					$rows[$row->{$fieldset}] = $row->{$fieldset};
				}
				
				$data = json_encode($rows);
			}
		}
		
		echo $data;
	}
}