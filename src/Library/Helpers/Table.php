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
		
		$key_columns = array_flip($columns);
		$f_node      = [];
		
		$c_action    = false;
		if (!empty($key_columns['action'])) $c_action = true;
		
		$c_lists     = false;
		if (isset($key_columns['number_lists'])) $c_lists = true;
		
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
			
			$f_node[$formula_data['name']]['field_key']     = $key_columns[$f_node[$formula_data['name']]['field_name']];
			$f_node[$formula_data['name']]['node_after']    = $formula_data['node_after'];
			$f_node[$formula_data['name']]['node_location'] = $formula_data['node_location'];
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
				$html .= '<button type="button" id="' . $name . '-cancel" class="btn btn-danger btn-slideright pull-right" data-dismiss="modal">Cancel</button>';
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
	 * 	: true, 
	 * 	: false, 
	 * 	: index|insert|update|delete, 
	 * 	: show|create|modify|destroy, 
	 * 	: [index, insert, update, delete], 
	 * 	: [show, create, modify, destroy]
	 *
	 * @return string
	 */
	function diy_table_action_button($row_data, $current_url, $action, $removed_button = null) {
	//	$privileges              = session()->all()['privileges'];
		$path                    = [];
		$addActions              = [];
		$add_path                = false;
		$enabledAction           = [];
		$enabledAction['read']   = true;
	//	$enabledAction['write']  = true;
		$enabledAction['modify'] = true;
		$enabledAction['delete'] = true;
		/* 
		$actions = null;
		if (in_array(current_route(), $privileges)) {
			foreach ($privileges as $roles) {
				if (diy_string_contained($roles, routelists_info()['base_info'])) {
					if (!in_array(routelists_info($roles)['last_info'], ['index', 'insert', 'update', 'destroy'])) {
						$actions[routelists_info()['base_info']][] = routelists_info($roles)['last_info'];
					}
				}
			}
			
			$action = $actions[routelists_info()['base_info']];
		}
		*/
		if (!empty($removed_button)) {
			if (is_array($removed_button)) {
				foreach ($removed_button as $remove) {
					if (in_array($remove, ['show', 'view', 'index'])) {
						$enabledAction['read']   = false;
					} elseif (in_array($remove, ['insert'])) {
					//	$enabledAction['write']  = false;
					} elseif (in_array($remove, ['edit', 'modify'])) {
						$enabledAction['modify'] = false;
					} elseif (in_array($remove, ['delete', 'destroy'])) {
						$enabledAction['delete'] = false;
					} else {
						$enabledAction[$removed_button] = false;
					}
				}
			}
		}
		
		// Add Action Button if the $action parameter above set with array
		if (is_array($action)) {
			foreach ($action as $action_data) {
				if (diy_string_contained($action_data, '|')) {
					$action_info = diy_add_action_button_by_string($action_data);
					$addActions[key($action_info)] = $action_info[key($action_info)];
					$enabledAction[key($action_info)] = true;
				} else {
					/* 
					if (in_array($action_data, ['view', 'index'])) {
						$action_info = diy_add_action_button_by_string("{$action_data}|success|eye");
						$addActions[$action_data] = $action_info[$action_data];
						$enabledAction['read']    = true;
						
					} elseif (in_array($action_data, ['edit', 'modify'])) {
						$action_info = diy_add_action_button_by_string("{$action_data}|primary|pencil");
						$addActions[$action_data] = $action_info[$action_data];
						$enabledAction['write']   = true;
						$enabledAction['modify']  = true;
						
					} elseif (in_array($action_data, ['delete', 'destroy'])) {
						$action_info = diy_add_action_button_by_string("{$action_data}|danger|times");
						$addActions[$action_data] = $action_info[$action_data];
						$enabledAction['delete']  = true;
						
					} else {
						$action_info = diy_add_action_button_by_string("{$action_data}|default|link");
						$addActions[$action_data] = $action_info[$action_data];
					}
					 */
				}
			}
		} else {			
			if (is_string($action)) {
				if (diy_string_contained($action, '|')) {
					$addActions = diy_add_action_button_by_string($action);
				} else {
					$addActions = diy_add_action_button_by_string("{$action}|default|link");
				}
			} else {
				/* 
				if (is_bool($action)) {
					if (true === $action) {
						$addActions = diy_add_action_button_by_string($action);
					}
				}
				 */
			}
		}
		
		// Default Action
		$path['view'] = "{$current_url}/{$row_data->id}";
		$path['edit'] = "{$current_url}/{$row_data->id}/edit";
		if (!empty($row_data->deleted_at)) {
			$path['delete'] = "{$current_url}/{$row_data->id}/restore_deleted";
		} else {
			$path['delete'] = "{$current_url}/{$row_data->id}/delete";
		}
		
		if (false === $enabledAction['read'])   $path['view']   = false;
	//	if (false === $enabledAction['write'])  $path['insert'] = false;
		if (false === $enabledAction['modify']) $path['edit']   = false;
		if (false === $enabledAction['delete']) $path['delete'] = false;
		
		if (count($addActions) >= 1) {
			foreach ($addActions as $action_name => $action_values) {
				$add_path[$action_name]['url'] = "{$current_url}/{$row_data->id}/{$action_name}";
				if (is_array($action_values)) {
					foreach ($action_values as $actionKey => $actionValue) {
						$add_path[$action_name][$actionKey] = $actionValue;
					}
				}
			}
		}
	//	dd($action, $removed_button, $enabledAction, $path);
		return create_action_buttons($path['view'], $path['edit'], $path['delete'], $add_path);
	}
}

if (!function_exists('diy_add_action_button_by_string')) {
	
	function diy_add_action_button_by_string($action, $is_array = false) {
		$addActions = [];
		if (is_bool($action)) {
			if (true === $action) {
				$addActions['view']['color']   = 'success';
				$addActions['view']['icon']    = 'eye';
				
				$addActions['edit']['color']   = 'primary';
				$addActions['edit']['icon']    = 'pencil';
				
				$addActions['delete']['color'] = 'danger';
				$addActions['delete']['icon']  = 'times';
			}
		} else {
			if (diy_string_contained($action, '|')) {
				$str_action = explode('|', $action);
				$str_name	= reset($str_action);
			} else {
				$str_action = $action;
				$str_name   = false;
			}
			
			$actionAttr = [];
			
			if (count($str_action) >= 2) {
				$actionAttr['color']    = false;
				if (isset($str_action[1])) {
					$actionAttr['color'] = $str_action[1];
				}
				
				$actionAttr['icon'] = false;
				if (isset($str_action[2])) {
					$actionAttr['icon']  = $str_action[2];
				}
				$addActions[$str_name]  = $actionAttr;
			} else {
				$addActions[$action]	   = $action;
			}
		}
		
		return $addActions;
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
		
		$deleteURL          = false;
		$delete_id          = false;
		$buttonDelete       = false;
		$buttonDeleteMobile = false;
		$restoreDeleted     = false;
		
		if (false !== $delete) {
			$deletePath            = explode('/', $delete);
			$deleteFlag            = end($deletePath);
			$delete_id             = intval($deletePath[count($deletePath)-2]);
			$deleteURL             = str_replace('@index', '@destroy', diy_current_route()->getActionName());
			$buttonDeleteAttribute = 'class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Delete"';
			$iconDeleteAttribute   = 'fa fa-times';
			
			if ('restore_deleted' === $deleteFlag) {
				$restoreDeleted        = true;
				$buttonDeleteAttribute = 'class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Restore"';
				$iconDeleteAttribute   = 'fa fa-recycle';
			}
			
			$delete_action      = '<form action="' . action($deleteURL, $delete_id) . '" method="post" class="btn btn_delete" style="padding:0 !important">' . csrf_field() . '<input name="_method" type="hidden" value="DELETE">';
			$buttonDelete       = $delete_action . '<button ' . $buttonDeleteAttribute . ' type="submit"><i class="' . $iconDeleteAttribute . '"></i></button></form>';
			$buttonDeleteMobile = '<li><a href="' . $delete . '" class="tooltip-error btn_delete" data-rel="tooltip" title="Delete"><span class="red"><i class="fa fa-trash-o bigger-120"></i></span></a></li>';
		}
		
		$buttonView       = false;
		$buttonViewMobile = false;
		if (false != $view) {
			if (true === $restoreDeleted) {
				$viewVisibilityAttr = 'readonly disabled class="btn btn-default btn-xs btn_view" data-toggle="tooltip" data-placement="top" data-original-title="View detail"';
			} else {
				$viewVisibilityAttr = 'href="' . $view . '" class="btn btn-success btn-xs btn_view" data-toggle="tooltip" data-placement="top" data-original-title="View detail"';
			}
			$buttonView       = '<a ' . $viewVisibilityAttr . '><i class="fa fa-eye"></i></a>';
			$buttonViewMobile = '<li class="btn_view"><a href="' . $view . '" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="fa fa-search-plus bigger-120"></i></span></a></li>';
		}
		
		$buttonEdit       = false;
		$buttonEditMobile = false;
		if (false != $edit) {
			if (true === $restoreDeleted) {
				$editVisibilityAttr = ' readonly disabled class="btn btn-default btn-xs btn_edit" data-toggle="tooltip" data-placement="top" data-original-title="Edit"';
			} else {
				$editVisibilityAttr = ' href="' . $edit . '" class="btn btn-primary btn-xs btn_edit" data-toggle="tooltip" data-placement="top" data-original-title="Edit"';
			}
			$buttonEdit       = '<a ' . $editVisibilityAttr . '><i class="fa fa-pencil"></i></a>';
			$buttonEditMobile = '<li class="btn_edit"><a href="' . $edit . '" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="fa fa-pencil-square-o bigger-120"></i></span></a></li>';
		}
		
		$buttonNew       = '';
		$buttonNewMobile = '';
		if (true === is_array($add_action)) {
			if (count($add_action) >= 1) {
				foreach ($add_action as $new_action_name => $new_action_values) {
					$row_name  = camel_case($new_action_name);
					$row_url   = $new_action_values['url'];
					$row_color = $new_action_values['color'];
					$row_icon  = $new_action_values['icon'];
					
					if (true === $restoreDeleted) {
						$actionVisibilityAttr = ' readonly disabled class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="' . $row_name . '"';
					} else {
						$actionVisibilityAttr = ' href="' . $row_url . '" class="btn btn-' . $row_color. ' btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="' . $row_name . '"';
					}
					$buttonNew       .= '<a' . $actionVisibilityAttr . '><i class="fa fa-' . $row_icon . '"></i></a>';
					$buttonNewMobile .= '<li><a href="' . $row_url . '" class="tooltip-error" data-rel="tooltip" title="' . $row_name . '"><span class="red"><i class="fa fa-' . $row_icon . ' bigger-120"></i></span></a></li>';
				}
			}
		}
		
		$buttons       = $buttonView       . $buttonEdit       . $buttonDelete       . $buttonNew;
		$buttonsMobile = $buttonViewMobile . $buttonEditMobile . $buttonDeleteMobile . $buttonNewMobile;
		
		return '<div class="action-buttons-box"><div class="hidden-sm hidden-xs action-buttons">' . $buttons . '</div><div class="hidden-md hidden-lg"><div class="inline pos-rel"><button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="fa fa-caret-down icon-only bigger-120"></i></button><ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' . $buttonsMobile . '</ul></div></div></div>';
	}
}



if (!function_exists('diy_table_row_attr')) {
	/**
	 * Set Default Row Attributes for Table
	 *
	 * @param string $str_value
	 * @param string $attributes
	 * 		=> colspan=2|id=idLists OR ['colspan' => 2, 'id' => 'idLists']
	 *
	 * @return string
	 */
	function diy_table_row_attr($str_value, $attributes) {
		$attr = $attributes;
		if (is_array($attributes)) {
			$attribute = [];
			foreach ($attributes as $key => $value) {
				$attribute[] = "{$key}=\"{$value}\"";
			}
			$attr = implode(' ', $attribute);
		}
		
		return "{$str_value}{:}$attr";
	}
}



if (!function_exists('diy_generate_table')) {
	
	/**
	 * Table Builder
	 *
	 * @param string $title
	 * @param string $title_id
	 * @param array  $header
	 * @param array  $body
	 * @param array  $attributes
	 * @param string $numbering
	 * @param string $containers
	 * 		: draw <div> container box, defalult true
	 * @param string $server_side
	 * @param boolean|string|array $server_side_custom_url
	 *
	 * @return string
	 */
	function diy_generate_table($title = false, $title_id = false, $header = array(), $body = array(), $attributes = array(), $numbering = false, $containers = true, $server_side = false, $server_side_custom_url = false) {
		// set attributes
		$datatableClass = 'expresscode-table table animated fadeIn table-striped table-default table-bordered table-hover dataTable repeater display responsive nowrap';
		if (false !== $attributes && is_array($attributes)) {
			if (empty($attributes)) {
				$_attributes = array (
					'id'    => "datatable-{$title_id}",
					'class' => $datatableClass
				);
			} else {
				if (empty($attributes['id'])) {
					$_attributes['id'] = "datatable-{$title_id}";
				}
				if (empty($attributes['class'])) {
					$_attributes['class'] = $datatableClass;
				}
				foreach ($attributes as $attrField => $attrValue) {
					$_attributes[$attrField] = $attrValue;
				}
			}
		} else {
			$_attributes = array (
				'id'    => "datatable-{$title_id}",
				'class' => $datatableClass
			);
		}
		
		$attributes = ' ' . rtrim(diy_attributes_to_string($_attributes));
		
		// set header table
		$hNumber   = false;
		$hEmpty    = false;
		$_header   = false;
		
		if (true === $numbering) {
			$number = ['number_lists'];
			$header = array_merge($number, $header);
		}
		
		if (false !== $header) {
			$_merge   = [];
			$_header  = '<thead><tr>';
			foreach ($header as $hIndex => $hList) {
				if (is_array($hList)) {
					$_merge[$hIndex] = $hList['merge'];
				}
				
				if (is_array($hList)) {
					$_header .= tableColumn($header, $hIndex, $hList['column']);
				} else {
					$_header .= tableColumn($header, $hIndex, $hList);
				}
			}
			$_header .= '</tr>';
			
			if (!empty($_merge)) {
				foreach ($_merge as $_mergedata) {
					foreach ($_mergedata as $idx => $mdList) {
						$_header .= tableColumn($_mergedata, $idx, $mdList);
					}
				}
			}
			$_header .= '</thead>';
		}
		
		// set body list(s) table
		$_body = false;
		$num   = false;
		
		if (false === $server_side) {
			if (false !== $body) {
				$_body = '<tbody>';
				
				$array_keys = array_keys($body);
				$first_key  = reset($array_keys);
				
				foreach ($body as $bIndex => $bLists) {
					$rowClickAction = false;
					if (!empty($bLists['row_data_url']) && false !== $bLists['row_data_url']) {
						$rowClickAction = ' onclick="location.href=\'' . $bLists['row_data_url'] . '\'" class="row-list-url"';
					}
					
					unset($bLists['row_data_url']);
					
					$_body .= '<tr>';
					for ($row = 0; $row <= count($body); $row++) {
						if ($bIndex === $row) {
							
							if (true === $numbering) {
								if ($first_key <= 0)	$numLists = intval($row)+1;
								else                 $numLists = intval($row);
								
								$_body .= "<td class=\"center\">{$numLists}</td>";
							}
							
							foreach ($bLists as $index => $list) {
								$row_attr = false;
								if ('action' === $index) {
									$rowClickAction = false;
								}
								if (true === str_contains($list, '{:}')) {
									$reList = explode('{:}', $list);
									$list   = $reList[0];
									
									if (isset($reList[1])) {
										$rowAttr  = explode('|', $reList[1]);
										$row_attr = ' ' . implode(' ', $rowAttr);
									}
									
									$row_list = "<td{$row_attr}{$rowClickAction}>{$list}</td>";
								} else {
									$row_list = "<td{$rowClickAction}>{$list}</td>";
								}
								
								if ($hNumber === $index) {
									if (is_empty($list)) $num = intval($row)+1;
									else $num = $list;
									
									$_body .= "<td class=\"center\">{$num}</td>";
								} else if ($hEmpty === $index) {
									$_body .= "<td class=\"center\">{$list}</td>";
								} else if ('active' === $index) {
									$_list  = set_active_value($list);
									$_body .= "<td align=\"center\">{$_list}</td>";
								} else if ('flag_status' === $index) {
									$_list  = internal_flag_status($list);
									$_body .= "<td align=\"center\"{$rowClickAction}>{$_list}</td>";
								} else if ('request_status' === $index) {
									$_list  = request_status(true, $list);
									$_body .= "<td align=\"center\">{$_list}</td>";
								} else if ('update_status' === $index) {
									$_list  = active_box();
									$_body .= "<td align=\"center\">{$_list[$list]}</td>";
								} else if ('action' === $index) {
									$_body .= "<td align=\"center\"{$rowClickAction}>{$list}</td>";
								} else {
									$_body .= $row_list;
								}
							}
						}
					}
					
					$_body .= '</tr>';
				}
				
				$_body .= '</tbody>';
			} else {
				$_body = '<tbody><tr><td>Found no data</td></tr></tbody>';
			}
		} else {
			$_body = null;
		}
		
		
		$table = "<table{$attributes}>{$_header}{$_body}</table>";
		
		return $table;
	}
	
	function tableColumn($header, $hIndex, $hList) {
		$hNumber   = false;
		$hCheck    = false;
		$hEmpty    = false;
		$_header   = false;
		
		$HKEY       = false;
		$HVAL       = false;
		if (is_array($hList)) {
			$keyList = array_keys($hList);
			$HKEY    = $keyList[0];
			$HVAL    = $hList[$HKEY];
		} else {
			$HKEY    = $hList;
			$HVAL    = trim(ucwords(str_replace('_', ' ', $HKEY)));
		}
		$hList      = $HKEY;
		$hLabel     = $HVAL;
		
		$hListFields = $hList;
		if (true === str_contains($hList, '|')) {
			$newHList    = explode('|', $hList);
			$hList       = $newHList[1];
			$hListFields = $hList;
		}
		if (true === str_contains($hList, '.')) {
			$newHList = explode('.', $hList);
			$hList    = $newHList[0];
		}
		
		// check if header label : no|id|nik
		$idHeader = $header[$hIndex];
		if (is_array($idHeader)) {
			$fHead    = array_keys($idHeader);
			$idHeader = $fHead[0];
		}
		if ('no' === strtolower($idHeader) || 'id' === strtolower($idHeader) || 'nik' === strtolower($idHeader)) $hNumber = $hIndex;
		
		if (true === diy_string_contained($hList, '<input type="checkbox"')) $hCheck = $hIndex;
		if (is_empty($hList)) $hEmpty = $hIndex;
		
		$hList = trim(ucwords(str_replace('_', ' ', $hList)));
		if ($hNumber === $hIndex) {
			$_header     .= "<th class=\"center\" width=\"50\">{$hList}</th>";
		} else if (true === str_contains($hList, ':changeHeaderName:')) {
			$newHList     = explode(':changeHeaderName:', $hList);
			$hList        = ucwords($newHList[1]);
			$hListFields  = $hList;
			$_header     .= "<th class=\"center\" width=\"120\">{$hListFields}</th>";
		} else if ($hCheck === $hIndex) {
			$_header     .= "<th width=\"50\">{$hList}</th>";
		} else if ($hEmpty === $hIndex) {
			$_header		.= "<th class=\"center\" width=\"120\">{$hList}</th>";
		} else if ('Action' === $hList) {
			$_header    .= "<th class=\"center\" width=\"120\">{$hList}</th>";
		} else if ('Active' === $hList) {
			$_header    .= "<th class=\"center\" width=\"120\">{$hList}</th>";
		} else if ('Flag Status' === $hList) {
			$_header    .= "<th class=\"center\" width=\"120\">{$hList}</th>";
		} else {
			if ('number_lists' === strtolower($idHeader)) {
				$_header    .= "<th class=\"center\" width=\"30\">No</th><th class=\"center\" width=\"30\">ID</th>";
			} else {
				$row_attr = false;
				if (true === str_contains($hList, '{:}')) {
					$reList = explode('{:}', $hList);
					$hList  = $reList[0];
					
					if (isset($reList[1])) {
						$rowAttr  = explode('|', $reList[1]);
						$row_attr = ' ' . implode(' ', $rowAttr);
					}
					
					$row_list = "<th{$row_attr}>{$hList}</th>";
				} else {
					$row_list = "<th>{$hLabel}</th>";
				}
				
				$_header    .= $row_list;
			}
		}
		
		return $_header;
	}
}

if (!function_exists('diy_draw_query_map_page_table')) {
	
	function diy_draw_query_map_page_table($name, $field_id, $value_id, $data) {
		
		$o  = "<table class=\"table mapping-table display responsive relative-box {$name}\">";
			$o .= "<tr id=\"row-box-{$field_id}\" class=\"relative-box row-box-{$field_id}\">";
				$o .= "<td class=\"qmap-box-{$field_id} field-name-box\">";
					$o .= "{$data['field_name']}";
				$o .= "</td>";
				$o .= "<td class=\"qmap-box-{$field_id} relative-box field-value-box\">";
					$o .= "{$data['field_value']}";
					$o .= "<span id=\"remove-row{$field_id}\" class=\"remove-row{$field_id} multi-chain-buttons\" style=\"display:none;\">";
						$o .= "<i class='fa fa-recycle warning' aria-hidden='true'></i>";
					$o .= "</span>";
				$o .= "</td>";
			$o .= "</tr>";
		$o .= "</table>";
		
		return $o;
	}
}