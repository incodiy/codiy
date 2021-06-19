<?php
/**
 * Created on 13 Apr 2021
 * Time Created	: 04:05:22
 *
 * @filesource	Table.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
if (!function_exists('diy_get_model_table')) {
	
	/**
	 * Get Table Name From Data Model
	 *
	 * @param object $model
	 * @param boolean $find
	 *
	 * @return object|array
	 */
	function diy_get_model_table($model, $find = false) {
		$model = diy_get_model($model, $find);
		
		return $model->getTable();
	}
}

if (!function_exists('diy_check_table_columns')) {
	
	/**
	 * Check if Table Column(s) Exist
	 *
	 * @param string $field_name
	 *
	 * @return array
	 */
	function diy_check_table_columns($table_name, $field_name) {
		return Illuminate\Support\Facades\Schema::hasColumn($table_name, $field_name);
	}
}

if (!function_exists('diy_get_table_columns')) {
	
	/**
	 * Get Table Column(s)
	 *
	 * @param string $table_name
	 *
	 * @return array
	 */
	function diy_get_table_columns($table_name) {
		return Illuminate\Support\Facades\Schema::getColumnListing($table_name);
	}
}

if (!function_exists('diy_get_table_column_type')) {
	
	/**
	 * Get Table Column(s)
	 *
	 * @param string $table_name
	 * @param string $field_name
	 *
	 * @return string
	 */
	function diy_get_table_column_type($table_name, $field_name) {
		return Illuminate\Support\Facades\Schema::getColumnType($table_name, $field_name);
	}
}

if (!function_exists('diy_set_formula_columns')) {
	
	function diy_set_formula_columns($columns, $data) {
		arsort($data);
		
		$key_columns	= array_flip($columns);
		$f_node			= [];
		$c_action		= false;
		if (!empty($key_columns['action'])) {
			$c_action	= true;
		}
		$c_lists		= false;
		if (isset($key_columns['number_lists'])) {
			$c_lists	= true;
		}
		
		foreach ($data as $formula_data) {
			$for_node = $formula_data['node_location'];
			$f_node[$formula_data['name']]['field_label'] = $formula_data['label'];
			
			if (empty($for_node)) {
				$f_node[$formula_data['name']]['field_name'] = end($formula_data['field_lists']);
			} else {
				if ('first' === $for_node) {
					$f_node[$formula_data['name']]['field_name'] = $columns[0];
				} elseif ('last' === $for_node) {
					$f_node[$formula_data['name']]['field_name'] = $columns[array_key_last($columns)];
				} else {
					$f_node[$formula_data['name']]['field_name'] = $for_node;
				}
			}
			
			$f_node[$formula_data['name']]['field_key']		= $key_columns[$f_node[$formula_data['name']]['field_name']];
			$f_node[$formula_data['name']]['node_after']	= $formula_data['node_after'];
			$f_node[$formula_data['name']]['node_location']	= $formula_data['node_location'];
		}
		
		foreach ($f_node as $key => $fdata) {
			if ('first' === $fdata['node_location']) {
				if (true === $c_lists) {
					diy_array_insert($columns, intval($fdata['field_key'])+1, $key);
				} else {
					diy_array_insert($columns, intval($fdata['field_key']), $key);
				}
			} elseif ('last' === $fdata['node_location']) {
				if (true === $fdata['node_after']) {
					if (true === $c_action) {
						diy_array_insert($columns, intval($fdata['field_key']), $key);
					} else {
						array_push($columns, $key);
					}
				} else {
					diy_array_insert($columns, intval($fdata['field_key']), $key);
				}
			} else {
				if (true === $fdata['node_after']) {
					diy_array_insert($columns, intval($fdata['field_key'])+1, $key);
				} else {
					diy_array_insert($columns, intval($fdata['field_key']), $key);
				}
			}
		}
		
		return $columns;
	}
}

if (!function_exists('diy_modal_content_html')) {
	
	function diy_modal_content_html($name, $title, $elements) {
		
		$html  = '<div class="modal-body">';
			$html .= '<div id="' . $name . '">';
				$html .= implode('', $elements);
			$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="modal-footer">';
			$html .= '<div class="diy-action-box">';
				$html .= '<button type="button" class="btn btn-danger btn-slideright pull-right" data-dismiss="modal">Cancel</button>';
				$html .= '<button id="submitFilterButton" class="btn btn-primary btn-slideright pull-right" type="submit">';
					$html .= '<i class="fa fa-filter"></i> &nbsp; Filter Data ' . $title;
				$html .= '</button>';
			$html .= '</div>';
		$html .= '</div>';
		
		return $html;
	}
}

if (!function_exists('diy_clear_json')) {
	
	function diy_clear_json($data) {
		$json = str_replace('"data"', "data", $data);
		$json = str_replace('"name"', "name", $json);
		$json = str_replace('"', "'", $json);
		
		return $json;
	}
}

if (!function_exists('diy_table_action_button')) {
	
	/**
	 * Set Action Button URL Used For create_action_buttons() Function
	 *
	 * created @Sep 6, 2018
	 * author: wisnuwidi
	 *
	 * @param array $row_data
	 * @param string $current_url
	 * @param bool|array $action
	 *
	 * @return string
	 */
	function diy_table_action_button($row_data, $current_url, $action, $as_root = false) {
		$path						= [];
		$addActions					= [];
		$add_path					= false;
		$enabledAction				= [];
		$enabledAction['read']		= false;
		$enabledAction['write']		= false;
		$enabledAction['modify']	= false;
		$enabledAction['delete']	= false;
		
		// Add Action Button if the $action parameter above set with array
		if (true === is_array($action)) {
			foreach ($action as $action_data) {
				if (!is_array($action_data)) {
					$str_action = explode('|', $action_data);
					$str_name	= reset($str_action);
					$actionAttr = [];
					
					if (count($str_action) >= 2) {
						$actionAttr['color']		= false;
						if (isset($str_action[1])) {
							$actionAttr['color']	= $str_action[1];
						}
						
						$actionAttr['icon'] = false;
						if (isset($str_action[2])) {
							$actionAttr['icon']		= $str_action[2];
						}
						$addActions[$str_name]		= $actionAttr;
					} else {
						$addActions[$action_data]	= $action_data;
					}
				} else {
					foreach ($action_data as $actionValues) {
						if ('index' === $actionValues || 'show' === $actionValues) {
							$enabledAction['read']	 = true;
						}
						if ('create' === $actionValues || 'insert' === $actionValues) {
							$enabledAction['write']	 = true;
						}
						if ('edit' === $actionValues || 'update' === $actionValues) {
							$enabledAction['modify'] = true;
						}
						if ('destroy' === $actionValues) {
							$enabledAction['delete'] = true;
						}
					}
				}
			}
		}
		
		// Default Action
		$path['view']		= "{$current_url}/{$row_data->id}";
		$path['edit']		= "{$current_url}/{$row_data->id}/edit";
		
		if (!empty($row_data->deleted_at)) {
			$path['delete']	= "{$current_url}/{$row_data->id}/restore_deleted";
		} else {
			$path['delete']	= "{$current_url}/{$row_data->id}/delete";
		}
		
		if (false === $enabledAction['read']   && false === $as_root) $path['view'] = false;
		if (false === $enabledAction['write']  && false === $enabledAction['modify'] && false === $as_root) $path['edit'] = false;
		if (false === $enabledAction['delete'] && false === $as_root) $path['delete'] = false;
		
		if (count($addActions) >= 1) {
			foreach ($addActions as $action_name => $action_values) {
				$add_path[$action_name]['url'] = "{$current_url}/{$row_data->id}/{$action_name}";
				foreach ($action_values as $actionKey => $actionValue) {
					$add_path[$action_name][$actionKey] = $actionValue;
				}
			}
		}
		
		return create_action_buttons($path['view'], $path['edit'], $path['delete'], $add_path, $as_root);
	}
}

if (!function_exists('create_action_buttons')) {
	
	/**
	 * Action Button(s) Builder
	 *
	 * created @Sep 6, 2018
	 * author: wisnuwidi
	 *
	 * @param string $view
	 * @param string $edit
	 * @param string $delete
	 * @param string $add_action
	 * @param string $as_root
	 *
	 * @return string
	 */
	function create_action_buttons($view = false, $edit = false, $delete = false, $add_action = [], $as_root = false) {
		
		$deleteURL			= false;
		$delete_id			= false;
		$buttonDelete		= false;
		$buttonDeleteMobile	= false;
		$restoreDeleted		= false;
		
		if (false !== $delete || true === $as_root) {
			$deletePath				= explode('/', $delete);
			$deleteFlag				= end($deletePath);
			$delete_id				= intval($deletePath[count($deletePath)-2]);
			$deleteURL				= str_replace('@index', '@destroy', diy_current_route()->getActionName());
			$buttonDeleteAttribute	= 'class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Delete"';
			$iconDeleteAttribute	= 'fa fa-times';
			
			if ('restore_deleted' === $deleteFlag) {
				$restoreDeleted			= true;
				$buttonDeleteAttribute	= 'class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Restore"';
				$iconDeleteAttribute	= 'fa fa-recycle';
			}
			
			$delete_action		= '<form action="' . action($deleteURL, $delete_id) . '" method="post" class="btn btn_delete" style="padding:0 !important">' . csrf_field() . '<input name="_method" type="hidden" value="DELETE">';
			$buttonDelete		= $delete_action . '<button ' . $buttonDeleteAttribute . ' type="submit"><i class="' . $iconDeleteAttribute . '"></i></button></form>';
			$buttonDeleteMobile	= '<li><a href="' . $delete . '" class="tooltip-error btn_delete" data-rel="tooltip" title="Delete"><span class="red"><i class="fa fa-trash-o bigger-120"></i></span></a></li>';
		}
		
		$buttonView 		= false;
		$buttonViewMobile 	= false;
		if (false != $view || true === $as_root) {
			if (true === $restoreDeleted) {
				$viewVisibilityAttr = 'readonly disabled class="btn btn-default btn-xs btn_view" data-toggle="tooltip" data-placement="top" data-original-title="View detail"';
			} else {
				$viewVisibilityAttr = 'href="' . $view . '" class="btn btn-success btn-xs btn_view" data-toggle="tooltip" data-placement="top" data-original-title="View detail"';
			}
			$buttonView			= '<a ' . $viewVisibilityAttr . '><i class="fa fa-eye"></i></a>';
			$buttonViewMobile	= '<li class="btn_view"><a href="' . $view . '" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="fa fa-search-plus bigger-120"></i></span></a></li>';
		}
		
		$buttonEdit			= false;
		$buttonEditMobile	= false;
		if (false != $edit || true === $as_root) {
			if (true === $restoreDeleted) {
				$editVisibilityAttr = ' readonly disabled class="btn btn-default btn-xs btn_edit" data-toggle="tooltip" data-placement="top" data-original-title="Edit"';
			} else {
				$editVisibilityAttr = ' href="' . $edit . '" class="btn btn-primary btn-xs btn_edit" data-toggle="tooltip" data-placement="top" data-original-title="Edit"';
			}
			$buttonEdit			= '<a ' . $editVisibilityAttr . '><i class="fa fa-pencil"></i></a>';
			$buttonEditMobile	= '<li class="btn_edit"><a href="' . $edit . '" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="fa fa-pencil-square-o bigger-120"></i></span></a></li>';
		}
		
		$buttonNew			= '';
		$buttonNewMobile	= '';
		if (true === is_array($add_action)) {
			if (count($add_action) >= 1) {
				foreach ($add_action as $new_action_name => $new_action_values) {
					$row_name	= camel_case($new_action_name);
					$row_url		= $new_action_values['url'];
					$row_color	= $new_action_values['color'];
					$row_icon	= $new_action_values['icon'];
					
					if (true === $restoreDeleted) {
						$actionVisibilityAttr = ' readonly disabled class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="' . $row_name . '"';
					} else {
						$actionVisibilityAttr = ' href="' . $row_url . '" class="btn btn-' . $row_color. ' btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="' . $row_name . '"';
					}
					$buttonNew			.= '<a' . $actionVisibilityAttr . '><i class="fa fa-' . $row_icon . '"></i></a>';
					$buttonNewMobile	.= '<li><a href="' . $row_url . '" class="tooltip-error" data-rel="tooltip" title="' . $row_name . '"><span class="red"><i class="fa fa-' . $row_icon . ' bigger-120"></i></span></a></li>';
				}
			}
		}
		
		$buttons		= $buttonView		. $buttonEdit		. $buttonDelete			. $buttonNew;
		$buttonsMobile	= $buttonViewMobile	. $buttonEditMobile	. $buttonDeleteMobile	. $buttonNewMobile;
		
		return '<div class="action-buttons-box"><div class="hidden-sm hidden-xs action-buttons">' . $buttons . '</div><div class="hidden-md hidden-lg"><div class="inline pos-rel"><button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="fa fa-caret-down icon-only bigger-120"></i></button><ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' . $buttonsMobile . '</ul></div></div></div>';
	}
}