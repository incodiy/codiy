<?php
namespace Incodiy\Codiy\Library\Components\Table\Craft;

use Yajra\DataTables\DataTables as DataTable;
use Incodiy\Codiy\Models\Admin\System\DynamicTables;
use Incodiy\Codiy\Controllers\Core\Craft\Includes\Privileges;

/**
 * Created on 21 Apr 2021
 * Time Created	: 12:45:06
 *
 * @filesource	Datatables.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Datatables {
	use Privileges;
	
	public $filter_model   = [];
	private $image_checker = ['jpg', 'jpeg', 'png', 'gif'];
	
	private function setAssetPath($file_path, $http = false, $public_path = 'public') {
		if (true === $http) {
			$assetsURL = explode('/', url()->asset('assets'));
			$stringURL = explode('/', $file_path);
			
			return implode('/', array_unique(array_merge_recursive($assetsURL, $stringURL)));
		}
		
		$file_path = str_replace($public_path . '/', public_path("\\"), $file_path);
		
		return $file_path;
	}
	
	private function checkValidImage($string, $local_path = true) {
		$filePath = $this->setAssetPath($string);
		
		if (true === file_exists($filePath)) {
			foreach ($this->image_checker as $check) {
				if (false !== strpos($string, $check)) {
					return true;
				} else {
					return false;
				}
			}
			
		} else {
			$filePath = explode('/', $string);
			$lastSrc  = array_key_last($filePath);
			$lastFile = $filePath[$lastSrc];
			$info     = "This File [ {$lastFile} ] Do Not or Never Exist!";
			
			return "<div class=\"show-hidden-on-hover missing-file\" title=\"{$info}\"><i class=\"fa fa-warning\"></i>&nbsp;{$lastFile}</div><!--div class=\"hide\">{$info}</div-->";
		}
	}
	
	public function process($data, $filters = [], $filter_page = []) {
		if (!empty($data->datatables->model[$_GET['difta']['name']])) {
			
			$model_type   = $data->datatables->model[$_GET['difta']['name']]['type'];
			$model_source = $data->datatables->model[$_GET['difta']['name']]['source'];
			
			if ('model' === $model_type) {
				$model_data = $model_source;
				$table_name = $model_data->getTable();
			}
			
			$order_by = [];
			if (!empty($data->datatables->columns[$table_name]['orderby'])) {
				$order_by = $data->datatables->columns[$table_name]['orderby'];
			}
			
			// DEVELOPMENT STATUS | @WAITINGLISTS
			if ('sql' === $model_type) {
				$model_data = new DynamicTables($model_source);
			}
		}
		
		$privileges         = $this->set_module_privileges();
		$index_lists        = $data->datatables->records['index_lists'];
		$column_data        = $data->datatables->columns;
		$action_list        = false;
		$_action_lists      = [];
		$removed_privileges = [];
		
		if (!empty($column_data[$table_name]['actions']) || is_array($column_data[$table_name]['actions'])) {
			
			if (true === $column_data[$table_name]['actions']) {
				$action_list = ['view', 'insert', 'edit', 'delete'];
			} else {				
				$action_list = $column_data[$table_name]['actions'];
			}
			
			$actions = null;
			if ($privileges['role_group'] > 1) {
				if (!empty($privileges['role'])) {
					
					if (!empty(strpos(json_encode($privileges['role']), routelists_info()['base_info']))) {
						foreach ($privileges['role'] as $roles) {
							
							if (diy_string_contained($roles, routelists_info()['base_info'])) {
								
								$routename = routelists_info($roles)['last_info'];
								if (in_array($routename, ['index', 'show'])) {
									$actions[routelists_info()['base_info']]['view'] = 'view';
									
								} elseif (in_array($routename, ['create', 'insert'])) {
									$actions[routelists_info()['base_info']]['insert'] = 'insert';
									
								} elseif (in_array($routename, ['edit', 'modify', 'update'])) {
									$actions[routelists_info()['base_info']]['edit'] = 'edit';
									
								} elseif (in_array($routename, ['destroy', 'delete'])) {
									$actions[routelists_info()['base_info']]['delete'] = 'delete';
								}
							}
						}
						
						if (!empty($actions)) {
							foreach ($action_list as $_list) {
								if (isset($actions[routelists_info()['base_info']][$_list])) {
									$_action_lists[] = $actions[routelists_info()['base_info']][$_list];
								} else {
									if (!in_array($_list, ['view', 'insert', 'edit', 'delete'])) {
										$_action_lists[] = $_list;
									}
								}
							}
						}
					}
				}
			}
			
			if (!empty(array_diff($action_list, $_action_lists))) {
				$removed_privileges = array_diff($action_list, $_action_lists);
			}
		}
		
		$limitTotal      = 0;
		$limit           = [];
		$limit['start']  = 0;
		$limit['length'] = 10;
		
//		$model           = $model_data->skip($limit['start'])->take($limit['length']);
		
		// Conditions [ Where ]
		$model_condition  = [];
		$where_conditions = [];
		if (!empty($data->datatables->conditions[$table_name]['where'])) {
			foreach ($data->datatables->conditions[$table_name]['where'] as $conditional_where) {
				if (!is_array($conditional_where['value'])) {
					$where_conditions['o'][] = [$conditional_where['field_name'], $conditional_where['operator'], $conditional_where['value']];
				} else {
					$where_conditions['i'][$conditional_where['field_name']] = $conditional_where['value'];
				}
			}
			
			$model_condition = $model_data->where($where_conditions['o']);
			if (!empty($where_conditions['i'])) {
				foreach ($where_conditions['i'] as $if => $iv) {
					$model_condition = $model_condition->whereIn($if, $iv);
				}
			}
			
			$model = $model_condition;
		}
		
		// Filter
		$fstrings	= [];
		$_ajax_url	= 'renderDataTables';
		
		if (!empty($where_conditions)) {
			$model_filters = $model_condition;
		} else {
			$model_filters = $model_data;
		}
		
		if (!empty($filters) && true == $filters) {
			foreach ($filters as $name => $value) {
				if ('filters'!== $name && '' !== $value) {
					if (
						$name !== $_ajax_url &&
						$name !== 'draw'     &&
						$name !== 'columns'  &&
						$name !== 'order'    &&
						$name !== 'start'    &&
						$name !== 'length'   &&
						$name !== 'search'   &&
						$name !== 'difta'    &&
						$name !== '_token'   &&
						$name !== '_'
					) {
						if (!is_array($value)) {
							$fstrings[]    = [$name => $value];
						} else {
							foreach ($value as $val) {
								$fstrings[] = [$name => $val];
							}
						}
					}
				}
			}
		}
		
		if (!empty($fstrings)) {
			$filters = [];
			foreach ($fstrings as $fdata) {
				foreach ($fdata as $fkey => $fvalue) {
					$filters[$fkey][] = $fvalue;
				}
			}
			
			if (!empty($filters)) {
				
				$fconds = [];
				foreach ($filters as $fieldname => $rowdata) {
					foreach ($rowdata as $dataRow) {
						$fconds[$fieldname] = $dataRow;
					}
				}
				
				$model = $model_filters->where($fconds);
			}
			$limitTotal = count($model->get());
		} else {
			$limitTotal = count($model_filters->get());
		}
		
		$limit['total'] = intval($limitTotal);
		
		if (!empty(request()->get('start')))  $limit['start']  = request()->get('start');
		if (!empty(request()->get('length'))) $limit['length'] = request()->get('length');
		
		$model->skip($limit['start'])->take($limit['length']);
		
		$datatables = DataTable::of($model)
			->setTotalRecords($limit['total'])
			->blacklist(['password', 'action', 'no'])
			->orderColumn('id', 'id desc')
			->smart(true);
		
		$is_image = [];
		if (!empty($this->form->imageTagFieldsDatatable)) {
			$is_image = array_keys($this->form->imageTagFieldsDatatable);
			$datatables->rawColumns(array_merge_recursive(['action', 'flag_status'], $is_image));
		}
		
		if (!empty($order_by)) {
			$datatables->order(function ($query) use($order_by) {
				$query->orderBy($order_by['column'], $order_by['order']);
			});
		}
		
		$object_called	= get_object_called_name($model);
		$rowModel		= [];
		
		foreach ($model->get() as $modelData) {
			if ('builder' === $object_called) {
				$rowModel = (object) $modelData->getAttributes();
			} else {
				$rowModel = $modelData;
			}
			
			$this->imageViewColumn($rowModel, $datatables);
			
			if (!empty($rowModel->flag_status))    $datatables->editColumn('flag_status',    function($model) {return diy_unescape_html(diy_form_internal_flag_status($model->flag_status));});
			if (!empty($rowModel->active))         $datatables->editColumn('active',         function($model) {return diy_form_set_active_value($model->active);});
			if (!empty($rowModel->update_status))  $datatables->editColumn('update_status',  function($model) {return diy_form_set_active_value($model->update_status);});
			if (!empty($rowModel->request_status)) $datatables->editColumn('request_status', function($model) {return diy_form_request_status(true, $model->request_status);});
			if (!empty($rowModel->ip_address))     $datatables->editColumn('ip_address',     function($model) {if ('::1' == $model->ip_address) return diy_form_get_client_ip(); else return $model->ip_address;});
		}
		
		if (!empty($data->datatables->formula[$table_name])) {
			$data_formula = $data->datatables->formula[$table_name];
			$data->datatables->columns[$table_name]['lists'] = diy_set_formula_columns($data->datatables->columns[$table_name]['lists'], $data_formula);
			
			foreach ($data_formula as $formula) {
				$datatables->editColumn($formula['name'], function($data) use ($formula) {
					// ambil referensi dari: calculateFormulaCells() wpdatatables
					$logic = new Formula($formula, $data);
					return $logic->calculate();
				});
			}
		}
		
		if (!empty($data->datatables->columns[$table_name]['format_data'])) {
			$data_format = $data->datatables->columns[$table_name]['format_data'];
			
			foreach ($data_format as $field => $format) {
				$datatables->editColumn($format['field_name'], function($data) use ($field, $format) {
					if ($field === $format['field_name']) {
						$dataValue = $data->getAttributes();
						return diy_format($dataValue[$field], $format['decimal_endpoint'], $format['separator'], $format['format_type']);
					}
				});
			}
		}
		
		$rlp                     = false;
		$row_attributes          = [];
		$row_attributes['class'] = null;
		$row_attributes['rlp']   = null;
		if (!empty($column_data[$table_name]['clickable'])) {
			if (count($column_data[$table_name]['clickable']) >= 1) {
				$rlp = function($model) { return diy_unescape_html(encode_id(intval($model->id))); };
			}
			$row_attributes['class'] = 'row-list-url';
			$row_attributes['rlp']   = $rlp;
		}
		$datatables->setRowAttr($row_attributes);
		
		$action_data = [];
		$action_data['model']          = $model;
		$action_data['current_url']    = diy_current_url();
		$action_data['action']['data'] = $action_list;
		if ($privileges['role_group'] > 1) {
			if (!empty($removed_privileges)) {
				$action_data['action']['removed'] = $removed_privileges;
			} else {
				$action_data['action']['removed'] = $data->datatables->button_removed;
			}
		} else {
			$action_data['action']['removed']    = $data->datatables->button_removed;
		}
		
		$datatables->addColumn('action', function($model) use($action_data) {
			return $this->setRowActionURLs($model, $action_data);
		});
		
		$tableData = [];
		if (true === $index_lists) {
			// 'ga ada id, jadi di index'
			$tableData = $datatables->addIndexColumn()->make(true);
		} else {
			// 'ada id, jadi ga di index'
			$tableData = $datatables->make();
		}
		
		return $tableData;
	}
	
	private function setRowActionURLs($model, $data) {
		return diy_table_action_button($model, $data['current_url'], $data['action']['data'], $data['action']['removed']);
	}
		
	private function imageViewColumn($model, $datatables) {
		$imageField = [];
		
		foreach ($model as $field => $strImg) {
			// Image Manipulation Data
			if (false !== $this->checkValidImage($strImg)) {
				$checkImage = $this->checkValidImage($strImg);
				if (true === $checkImage) $imageField[$field] = $checkImage;
			}
		}
		
		foreach ($imageField as $field => $imgSrc) {
			$imgSrc = 'imgsrc::';
			if (isset($model->{$field})) {
				$datatables->editColumn($field, function($model) use ($field, $imgSrc) {
					$label    = ucwords(str_replace('-', ' ', diy_clean_strings($field)));
					$thumb    = false;
					$imgCheck = $this->checkValidImage($model->{$field});
					
					if (false !== $imgCheck) {
						
						// Check Thumbnail
						$filePath = explode('/', $model->{$field});
						$lastSrc  = array_key_last($filePath);
						$lastFile = $filePath[$lastSrc];
						unset($filePath[$lastSrc]);
						$thumb    = implode('/', $filePath) . '/thumb/tnail_' . $lastFile;
						$filePath = $model->{$field};
						if (!empty($this->setAssetPath($thumb))){
							$filePath = $thumb;
						}
						// Check Thumbnail
						
						if (true === $imgCheck) {
							$alt = $imgSrc.$label;
							return diy_unescape_html("<center><img class=\"cdy-img-thumb\" src=\"{$filePath}\" alt=\"{$alt}\" /></center>");
						} else {
							return diy_unescape_html($imgCheck);
						}
					} else {
						$filePath = explode('/', $filePath);
						$lastSrc  = array_key_last($filePath);
						
						return $filePath[$lastSrc];
					}
				});
			}
		}
	}
	
	public $filter_datatables = [];
	public function filter_datatable($request) {
		$this->filter_datatables = $request->all();
	}
	
	public function init_filter_datatables($get = [], $post = []) {
		
		if (!empty($get['filterDataTables'])) {
			$fdata  = explode('::', $post['_fita']);
			$table  = $fdata[1];
			$target = $fdata[2];
			$prev   = $fdata[3];
			
			unset($post['filterDataTables']);
			unset($post['_fita']);
			unset($post['_token']);
			unset($post['_n']);
			
			$wheres = [];
			foreach ($post as $key => $value) {
				$wheres[] = "`{$key}` = '{$value}'";
			}
			
			$wherepPrefious = null;
			if ('#null' !== $prev) {
				$previous  = explode("#", $prev);
				$preFields = explode('|', $previous[0]);
				$preFieldt = explode('|', $previous[1]);
				
				$prevields = [];
				foreach ($preFields as $idf => $prev_field) {
					$prevields[$idf] = $prev_field;
				}
				
				$previeldt = [];
				foreach ($preFieldt as $idd => $prev_field_data) {
					$previeldt[$idd] = $prev_field_data;
				}
				
				$previousData = [];
				foreach ($prevields as $idp => $prev_data) {
					$previousData[$prev_data] = $previeldt[$idp];
				}
				
				$previousdata = [];
				foreach ($previousData as $_field => $_value) {
					$previousdata[] = "`{$_field}` = '{$_value}'";
				}
				
				$wherepPrefious = ' AND ' . implode(' AND ', $previousdata);
			}
			
			$wheres = implode(' AND ', $wheres);
			$rows   = diy_query("SELECT DISTINCT `{$target}` FROM `{$table}` WHERE {$wheres}{$wherepPrefious}", "SELECT");
			
			return $rows;
		}
	}
}