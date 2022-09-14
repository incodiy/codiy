<?php
namespace Incodiy\Codiy\Controllers\Admin\System\Includes;

use Incodiy\Codiy\Models\Admin\System\MappingPage as MappingData;

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
	
	public $mapping_page      = [];
	
	private $mapRoute;
	private $mapTable;
	private $model_class_info;
	private $ajaxUrli         = null;
	private $nodeID           = '__node__';
	private $nodeActionButton = '__btnact__';
		
	public function rolepage($data, $usein) {
		return MappingData::getFieldName($data, $usein, $this->nodeID);
	}

	public function mapping_before_insert($request, $group) {
		
	}
	
	private function mapping() {
		$title_id                         = 'page_privileges_' . diy_random_strings(50, false) . ' role-priv';
		
		$headerData                       = [];
		$headerData['module_id']          = [diy_table_row_attr('Module Name' , ['style' => 'text-align:center', 'rowspan' => 2])];
		$headerData['target_table']       = [diy_table_row_attr('Table Name'  , ['style' => 'text-align:center', 'rowspan' => 2])];
		$headerData['target_roles']       = [
			[
				'column' => diy_table_row_attr('Role Query'  , ['style' => 'text-align:center;min-width:420px;', 'colspan' => 2]),
				'merge'  => [
					diy_table_row_attr('Field Name'  , ['style' => 'text-align:center']),
					diy_table_row_attr('Field Value' , ['style' => 'text-align:center'])
				]
			]
		];
		$headerData['action_button']      = [diy_table_row_attr('Action'  , ['style' => 'text-align:center', 'rowspan' => 2])];
		
		$header    = array_merge_recursive($headerData['module_id'], $headerData['target_table'], $headerData['target_roles'], $headerData['action_button']);		
		$row_table = $this->mapping_box();
		
		return $this->form->draw(diy_generate_table('Set Role Module Page', $title_id, $header, $row_table, false, false, false));
	}
	
	private function get_data_map() {
		$this->model_class_info = diy_get_model_controllers_info();
	}
	
	private function setID($string, $node = null) {
		if (empty($node))	$node = $this->nodeID;
		return diy_random_strings(8, false, $string, '__node__');
	}
	
	private function mapping_box() {
		$this->get_data_map();
		
		$row_table = [];
		$icon      = '<i class="fa fa-caret-right"></i> &nbsp; ';
		$roleData  = null;
		
		foreach ($this->menu_privileges as $parent => $childs) {
			$parent_title	= ucwords(str_replace('_', ' ', $parent));
			if (!empty($childs->name)) $parent_title = $childs->name;
			$row_table[]	= [diy_table_row_attr($icon . "{$parent_title}", ['style' => 'font-weight:500;text-indent:5pt;color:black', 'colspan' => 5])];
			
			foreach ($childs as $child_name => $data_module) {
				if (!isset($data_module->id)) {
					$child_title	= ucwords(str_replace('_', ' ', $child_name));
					if (!empty($data_module->name)) $child_title = $data_module->name;
					
					$row_table[]	= [diy_table_row_attr($icon . $child_title, ['style' => 'font-weight:500;text-indent:15pt;color:green', 'colspan' => 5])];
					foreach ($data_module as $module_name => $module_data) {
						if (!empty($this->model_class_info[$module_data->route])) {
							$roleData = $this->model_class_info[$module_data->route];
						}
						
						if (!empty($module_data->id)) {
							if (!empty($roleData)) {
								$row_table[] = $this->buildRoleBox($roleData, $module_name, $module_data, $icon);
							}
						} else {
							$module_title = ucwords(str_replace('_', ' ', $module_name));
							if (!empty($module_data->name)) $module_title = $module_data->name;
							 
							$row_table[] = [diy_table_row_attr($icon . $module_title, ['style' => 'font-weight:500;text-indent:25pt', 'colspan' => 4])];
							foreach ($module_data as $third_name => $third_data) {
							if (!empty($this->model_class_info[$third_data->route])) {
								$roleData = $this->model_class_info[$third_data->route];
							}
							 	
							$third_title = ucwords(str_replace('_', ' ', $third_name));
							if (!empty($third_data->name)) $third_title = $third_data->name;
								 
								$row_table[] = $this->buildRoleBox($roleData, $third_title, $third_data, $icon);
							}
						}
					}
				} else {
					$child_title = ucwords(str_replace('_', ' ', $child_name));
					if (!empty($data_module->name)) $child_title = $data_module->name;
					
					if (!empty($this->model_class_info[$data_module->route])) {
						$roleData = $this->model_class_info[$data_module->route];
					}
					
					if (!empty($roleData)) {
						$row_table[] = $this->buildRoleBox($roleData, $child_name, $data_module, $icon, 'text-indent:15pt');
					}
				}
			}
			
		}		
		
		return $row_table;
	}
	
	private function buildRoleBox($roleData, $module_name, $module_data, $icon, $indent = false) {
		if ($roleData) {
			
			$routeName = strtolower($module_data->route);
		//	dump($routeName);
			$routeNameAttribute            = str_replace('.', '-', $module_data->route);
			$routeToAttribute              = 'role__' . $routeNameAttribute . '__' . $roleData['model']['table_map'];
			
			$roleAttributes                = [];
			$roleAttributes['table_name']  = "table_name";
			$roleAttributes['field_name']  = "field_name[]";
			$roleAttributes['field_value'] = "field_value[]";
			
			$roleValues                    = [];
			$roleValues['field_name']      = [];
			$roleValues['field_value']     = [];
			
			$roleColumns                   = [];
			
			$tableID                       = $this->setID($roleData['model']['table_map']);
			$tableLabel                    = ucwords(str_replace('_', ' ', str_replace('view_', ' ', str_replace('t_', ' ', $roleData['model']['table_map']))));
			$roleColumns['table_name']     = diy_form_checkList($roleAttributes['table_name'] , $roleData['model']['table_map'], $tableLabel, false, 'success read-select full-width text-left', $tableID);
			/* 
			$fieldID                       = $this->setID($roleData['model']['table_map']);
			$roleColumns['field_name']     = '<div class="' . $routeToAttribute . ' relative-box role-filter-query" id="role-filter-query role-filter-query-field-table">';
			$roleColumns['field_name']    .= "<div id=\"row-box-{$fieldID}\" class=\"relative-box row-box-{$fieldID}\">" . diy_form_selectbox($roleAttributes['field_name'] , $roleValues['field_name'] , false, ['id' => $fieldID, 'class' => $routeToAttribute], false, false);
			$roleColumns['field_name']    .= "<span id=\"remove-row{$fieldID}\" class=\"remove-row{$fieldID} multi-chain-buttons\" style=\"display: none;\"><i class='fa fa-recycle warning' aria-hidden='true'></i></span>";
			$roleColumns['field_name']    .= "</div></div>";
			
			$valueID                       = $this->setID($roleData['model']['table_map']);
			$roleColumns['field_value']    = '<div class="' . $routeToAttribute . ' relative-box role-filter-query" id="role-filter-query role-filter-query-field-value-table">';
			$roleColumns['field_value']   .= "<div id=\"row-box-{$valueID}\" class=\"relative-box row-box-{$fieldID}\">" . diy_form_selectbox($roleAttributes['field_value'] , $roleValues['field_value'] , false, ['id' => $valueID, 'class' => $routeToAttribute, 'multiple'], false, false);
			$roleColumns['field_value']   .= "</div></div>";
			 */
			
			
			
			$fieldID                       = $this->setID($roleData['model']['table_map']);
			$roleColumns['field_name']     = diy_form_selectbox($roleAttributes['field_name'] , $roleValues['field_name'] , false, ['id' => $fieldID, 'class' => $routeToAttribute], false, false);
						
			$valueID                       = $this->setID($roleData['model']['table_map']);
			$roleColumns['field_value']    = diy_form_selectbox($roleAttributes['field_value'] , $roleValues['field_value'] , false, ['id' => $valueID, 'class' => $routeToAttribute, 'multiple'], false, false);
			
			
			
			$module_name_label    = ucwords(str_replace('_', ' ', str_replace('view_', ' ', str_replace('t_', ' ', $module_name))));
			$opt                  = ['align' => 'center', 'id' => strtolower($module_name) . '-row', 'colspan' => 2, 'style' => 'padding: 0 !important;'];
			$moduleInfoBox        = "<input type=\"hidden\" name=\"{$routeName}\" value=\"{$module_data->id}\" />";
			
			$mergeBox = $this->drawQueryMapTable($routeToAttribute, $fieldID, $valueID, $roleColumns);
			
			$resultBox            = [];
			$resultBox['head']    = [diy_table_row_attr($icon . $module_name_label . $moduleInfoBox, ['style' => 'text-indent:25pt', 'id' => strtolower($module_name) . '-row'])];
			$resultBox['body']    = [
				diy_table_row_attr($roleColumns['table_name'] , ['align' => 'left', 'id' => strtolower($module_name) . '-row']),
				diy_table_row_attr($mergeBox , $opt),
			//	diy_table_row_attr($roleColumns['field_value'], $opt)
			];
			
			$nodebtn = "node_btn_{$tableID}{$this->nodeActionButton}{$fieldID}{$this->nodeActionButton}{$valueID}";
			$resultBox['scripts']['table'] = [
				diy_table_row_attr (
					$this->buttonAdd($nodebtn, $tableID, $fieldID, $valueID) . 
					$this->js_rolemap_table($tableID, $fieldID, $valueID, $nodebtn) .
					$this->js_rolemap_fieldname($fieldID, $valueID),
					['align' => 'center', 'id' => strtolower($module_name) . '-row', 'width' => 100, 'style' => 'padding:8px']
				)
			];
			
			$o = array_merge_recursive($resultBox['head'], $resultBox['body'], $resultBox['scripts']['table']);
			
			return $o;
		}
	}
	
	private function drawQueryMapTable($name, $field_id, $value_id, $data) {
		//	'$routeToAttribute, $fieldID, $valueID, $roleColumns';
		return "
<table class=\"table display responsive mapping-table {$name}\" style=\"background-color:transparent;border:none !important\">
	<tr id=\"row-box-{$field_id}\" class=\"relative-box row-box-{$field_id}\" style=\"background-color:transparent;border:none !important\">
		<td class=\"qmap-box-{$field_id} relative-box field-name-box\" style=\"background-color:transparent;min-width:200px;border:none !important;vertical-align:top;\" valign=\"top\">
			{$data['field_name']}
			<span id=\"remove-row{$field_id}\" class=\"remove-row{$field_id} multi-chain-buttons\" style=\"display: none;\">
				<i class='fa fa-recycle warning' aria-hidden='true'></i>
			</span>
		</td>
		<td class=\"qmap-box-{$field_id} relative-box field-value-box\" style=\"background-color:transparent;min-width:200px;border:none !important;vertical-align:top;\">{$data['field_value']}</td>
	</tr>
</table>
		";
	}
	
	private function ajax_urli($usein, $return_data = false) {
		$current_url  = url(str_replace('.', '/', diy_current_baseroute()));
		$urlset       = [
			'rolemapage' => 'true',
			'usein'      => $usein,
			'_token'     => csrf_token()
		];
		
		$uri      = [];
		foreach ($urlset as $fieldurl => $urlvalue) {
			$uri[] = "{$fieldurl}={$urlvalue}";
		}
		
		$this->ajaxUrli = $current_url . '?' . implode('&', $uri);
		
		if (true === $return_data) {
			return $this->ajaxUrli;
		}
	}
	
	private function js_rolemap_table($id, $target_id, $second_target, $nodebtn) {
		$this->ajax_urli('table_name');
		return "<script type='text/javascript'>$(document).ready(function() { mappingPageTableFieldname('{$id}', '{$target_id}', '{$this->ajaxUrli}', '{$second_target}', '{$nodebtn}'); });</script>";
	}
	
	private function js_rolemap_fieldname($id, $target_id) {
		$this->ajax_urli('field_name');
		return "<script type='text/javascript'>$(document).ready(function() { mappingPageFieldnameValues('{$id}', '{$target_id}', '{$this->ajaxUrli}'); });</script>";
	}
	
	private function buttonAdd($node_btn, $id, $target_id, $second_target) {
		$this->ajax_urli('field_name');
		
		return diy_mappage_button_add($this->ajaxUrli, $node_btn, $id, $target_id, $second_target);
	}
}