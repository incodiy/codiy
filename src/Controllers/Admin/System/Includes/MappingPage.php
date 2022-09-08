<?php
namespace Incodiy\Codiy\Controllers\Admin\System\Includes;

/**
 * Created on Sep 6, 2022
 * 
 * Time Created : 1:52:26 PM
 *
 * @filesource	MappingPage.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */

trait MappingPage {
		
	private $mapRoute;
	private $mapTable;
	
	public function renderMap($data) {
		$this->getFieldName($data);
	}
	
	private function getFieldName($data) {		
		$fields = [];
		foreach (diy_get_table_columns($data['table_name']) as $fieldname) {
			$fields[$fieldname] = $fieldname;
		}
		
		$data = json_encode($fields);
		echo $data;
	}
	
	private $model_class_info;
	private function get_data_map() {
		$this->model_class_info = diy_get_model_controllers_info();
	}
	
	private function mapping_box() {
		$this->get_data_map();
		$row_table = [];
		$icon      = '<i class="fa fa-caret-right"></i> &nbsp; ';
		$roleData  = null;
		
		foreach ($this->menu_privileges as $parent => $childs) {
			$parent_title	= ucwords(str_replace('_', ' ', $parent));
			if (!empty($childs->name)) $parent_title = $childs->name;
			$row_table[]	= [diy_table_row_attr($icon . "<b>{$parent_title}</b>", ['style' => 'font-weight:500;text-indent:5pt', 'colspan' => 4])];
			
			foreach ($childs as $child_name => $data_module) {
				if (!isset($data_module->id)) {
					$child_title	= ucwords(str_replace('_', ' ', $child_name));
					if (!empty($data_module->name)) $child_title = $data_module->name;
					
					$row_table[]	= [diy_table_row_attr($icon . $child_title, ['style' => 'font-weight:500;text-indent:15pt', 'colspan' => 4])];
					foreach ($data_module as $module_name => $module_data) {
						if (!empty($this->model_class_info[$module_data->route])) {
							$roleData = $this->model_class_info[$module_data->route];
						}
						
						if (!empty($module_data->id)) {
							
							$routeNameAttribute            = str_replace('.', '-', $module_data->route);
							$routeToAttribute              = 'role__' . $routeNameAttribute . '__' . $roleData['model']['table_map'];
							
							$roleAttributes                = [];
							$roleAttributes['table_name']  = "table_name";
							$roleAttributes['field_name']  = "field_name[{$roleData['model']['table_map']}][]";
							$roleAttributes['field_value'] = "field_value[{$roleData['model']['table_map']}][]";
							
							$roleValues                    = [];
							$roleValues['field_name']      = [];
							$roleValues['field_value']     = [];
							
							$roleColumns                   = [];
							$tableID                       = diy_random_strings(8, false, $roleData['model']['table_map']);
							$roleColumns['table_name']     = '';
							$roleColumns['table_name']    .= '<form method="POST">';
							$roleColumns['table_name']    .= diy_form_checkList($roleAttributes['table_name'] , $roleData['model']['table_map'], $roleData['model']['table_map'], false, 'success read-select full-width text-left', $tableID);
							$roleColumns['table_name']    .= '</form>';
							
						//	$roleColumns['table_name']     = diy_form_checkList($roleAttributes['table_name'] , $roleData['model']['table_map'], $roleData['model']['table_map'], false, 'success read-select full-width text-left');
							
							$fieldID                       = diy_random_strings(8, false, $roleData['model']['table_map']);
							$roleColumns['field_name']     = '<div class="' . $routeToAttribute . ' relative-box" id="role-filter-query role-filter-query-field-table">';
							$roleColumns['field_name']    .= diy_form_selectbox($roleAttributes['field_name'] , $roleValues['field_name'] , false, ['id' => $fieldID, 'class' => $routeToAttribute], false);
							$roleColumns['field_name']    .= '</div>';
							
							$roleColumns['field_value']    = '<div class="' . $routeToAttribute . ' relative-box" id="role-filter-query role-filter-query-field-value-table">';
							$roleColumns['field_value']   .= diy_form_selectbox($roleAttributes['field_value'], $roleValues['field_value'], false, ['class' => $routeToAttribute], false);
							$roleColumns['field_value']   .= '</div>';
							
							$opt                  = ['align' => 'center', 'id' => strtolower($module_name) . '-row'];
							$resultBox            = [];
							$resultBox['head']    = [diy_table_row_attr($icon . $module_name, ['style' => 'text-indent:25pt', 'id' => strtolower($module_name) . '-row'])];
							$resultBox['index']   = [
								diy_table_row_attr($roleColumns['table_name'] , ['align' => 'left', 'id' => strtolower($module_name) . '-row']),
								diy_table_row_attr($roleColumns['field_name'] , $opt),
								diy_table_row_attr($roleColumns['field_value'], $opt)
							];
							$resultBox['scripts']['table'] = [$this->js_rolecheck_table($tableID, $fieldID, $roleAttributes['table_name'], $roleData['model']['table_map'])];
							
							$o  = array_merge_recursive($resultBox['head'], $resultBox['index'], $resultBox['scripts']['table']);
							
							$row_table[] = $o;
						} else {
							/*
							 $module_title = ucwords(str_replace('_', ' ', $module_name));
							 if (!empty($module_data->name)) $module_title = $module_data->name;
							 
							 $row_table[] = [diy_table_row_attr($icon . $module_title, ['style' => 'font-weight:500;text-indent:25pt', 'colspan' => 4])];
							 foreach ($module_data as $third_name => $third_data) {
							 $third_title = ucwords(str_replace('_', ' ', $third_name));
							 if (!empty($third_data->name)) $third_title = $third_data->name;
							 
							 $row_table[] = $this->_checkboxes($third_title, $third_data, $icon, 'text-indent:35pt');
							 } */
						}
					}
				} else {
					$child_title = ucwords(str_replace('_', ' ', $child_name));
					if (!empty($data_module->name)) $child_title = $data_module->name;
					
					//	$row_table[] = $this->_checkboxes($child_title, $data_module, $icon, 'text-indent:15pt');
				}
			}
			
		}
		//	dd($this->model_class_info);
		
		$icon      = '<i class="fa fa-caret-right"></i> &nbsp; ';
		
		
		return $row_table;
	}
	
	public function js_rolecheck_table($id, $target_id, $attribute_name, $value) {
		$varAttribute = $id;		
		$current_url  = url('system/config/group');
		$urlset       = [
			'rolemapage' => 'true',
			'_token'     => csrf_token()
		];
		
		$uri      = [];
		foreach ($urlset as $fieldurl => $urlvalue) {
			$uri[] = "{$fieldurl}={$urlvalue}";
		}
		$url      = $current_url . '?' . implode('&', $uri);
		
		$o = "
<script type='text/javascript'>
$(document).ready(function() {
	updateSelectChosen('select#{$target_id}');

	$('#{$id}').change(function(e) {
		var {$varAttribute}checked = $(this).is(':checked');
		loader('{$target_id}', 'remove');

		if ({$varAttribute}checked) {
			$.ajax({
				type    : 'POST',
				url     : '{$url}',
				data    : $(this).serialize(),
				success : function(d) {
					loader('{$target_id}', 'show');
					updateSelectChosen('select#{$target_id}');
					$.each(JSON.parse(d), function(index, item) {
						var {$varAttribute}itemlabel = item.replace('_', ' ');
						$('select#{$target_id}').append('<option value=\"' + item + '\">' + ucwords({$varAttribute}itemlabel) + '</option>');
					});

					$('select#{$target_id}').trigger('chosen:updated');
				},
				error: function() {
					alert('Error!!!');
				},
				complete: function() {
					loader('{$target_id}', 'fadeOut');
				}
			});
		} else {
			loader('{$target_id}', 'fadeOut');
			updateSelectChosen('select#{$target_id}');
		}
	});
});
</script>
		";
			
		return $o;
	}
}