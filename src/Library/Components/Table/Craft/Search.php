<?php
namespace Incodiy\Codiy\Library\Components\Table\Craft;

use Incodiy\Codiy\Library\Components\Form\Objects as Form;
use Illuminate\Support\Facades\Request;
/**
 * Created on 24 Apr 2021
 * Time Created : 20:51:52
 *
 * @filesource Search.php
 *
 * @author     wisnuwidi@gmail.com - 2021
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */
class Search {
	
	private $model;
	private $form;
	private $filters;
	private $input_relations;
	private $relations;
	private $foreign_keys;
	private $sql;
	private $table;
	private $info;
	
	private $model_filters = [];
	public function __construct($info, $model = null, $filters = [], $sql = null) {
		$this->info = $info;
		if (!empty($model)) $model = new $model();
		
		if (!empty($filters['filter_model'])) {
			$this->model_filters = $filters['filter_model'];
			$this->model     = $model->where($this->model_filters);
		} else {
			$this->model     = $model;
		}
		
		$this->form         = new Form();
		$this->table        = $filters['table_name'];
		$this->relations    = $filters['relations'];
		$this->foreign_keys = $filters['foreign_keys'];
		$this->filters      = $filters;
		$this->sql          = $sql;
		
		if (!empty($filters['filter_groups'])) $this->getFilterData($filters['filter_groups']);
	}
	
	public function render($info, string $table, array $fields) {
		if ($this->info === $info) {
			$this->search_box($info, $table, $this->getColumnInfo($table, $fields), $this->model);
			
			$data         = [];
			$data['name'] = ucwords(str_replace('-', ' ', diy_clean_strings($table)));
			$data['html'] = $this->html;
			
			return $data;
		}
	}
	
	private function select($sql) {
		return diy_query($sql, 'SELECT');
	}
	
	private $data = [];
	private function getFilterData($data) {
		$all_columns     = [];
		$relists         = [];
		$input_relations = [];
		
		foreach ($this->filters['columns'] as $col) {
			$all_columns[$col] = $col;
		}
		
		foreach ($data as $key => $row) {
			unset($data[$key]);
			
			if (!empty($row['relate'])) {
				if (true === $row['relate']) {
					$relists[$key] = array_keys($all_columns);
				} else {
					$relists[$key] = $row['relate'];
				}
				
			} else {
				$relists[$key] = $row['relate'];
			}
			
			$data[$row['column']]['name']   = $row['column'];
			$data[$row['column']]['type']   = $row['type'];
			$data[$row['column']]['relate'] = $relists[$key];
		}
		$this->data = $data;
		
		if (count($data) >= 2) {
			foreach ($relists as $relist) {
				if (false !== $relist) {
					foreach ($relist as $relation) {
						$input_relations['lists'][] = $relation;
						if (!empty($data[$relation]['type'])) {
							$input_relations['type'][$relation] = $data[$relation]['type'];
						} else {
							$input_relations['type'][$relation] = 'text';
						}
					}
				}
			}
		} else {
			$the_only_data              = array_keys($data);
			$input_relations['lists'][] = $the_only_data[0];
			$input_relations['type']    = [$the_only_data[0] => 'selectbox'];
		}
		
		if (!empty($input_relations['lists'])) $this->input_relations['lists'] = array_unique($input_relations['lists']);
		if (!empty($input_relations['type']))  $this->input_relations['type']  = $input_relations['type'];
	}
	
	private $selections = [];
	private function selections($table, $fields = [], $condition = null) {
		$strfields = implode(',', $fields);
		$where     = null;
		
		if (!empty($condition)) $where = "WHERE `{$table}`.id IS NOT NULL ";
		if (!empty($this->model_filters)) {
			$mf_where = [];
			$n        = 0;
			
			foreach ($this->model_filters as $mf_field => $mf_values) {
				$n ++;
				$mf_cond = 'AND ';
				if ($n <= 1) $mf_cond = 'WHERE ';
				if (!is_array($mf_values)) {
					$mf_where[] = "{$mf_cond}{$mf_field} = '{$mf_values}'";
				} else {
					$mf_value   = implode("', '", $mf_values);
					$mf_value   = " IN ('{$mf_value}')";
					$mf_where[] = "{$mf_cond}{$mf_field}{$mf_value}";
				}
			}
			
			$where = implode(' ', $mf_where);
		}
		
		if (!empty($this->relations)) {
			if (!empty($this->relations[$strfields]['relation_data'])) {
				foreach ($this->relations[$strfields]['relation_data'] as $relationData) {
					$this->selections[$strfields][$relationData['field_value']] = $relationData['field_value'];
				}
				return $this;
			}
		}
		
		if (!empty($strfields)) {
			$query = $this->select("SELECT {$strfields} FROM `{$table}` {$where}GROUP BY {$strfields};");
			if (!empty($query)) {
				$selections = [];
				foreach ($query as $rows) {
					foreach ($rows as $fieldname => $fieldvalue) {
						$selections[$fieldname][$fieldvalue] = $fieldvalue;
					}
				}
				
				foreach ($fields as $field) {
					$this->selections[$field] = array_unique($selections[$field]);
				}
			}
		}
		
		return $this;
	}
	
	private function set_first_selectbox($name, $field_value, $field) {
		$values[$field]       = null;
		$field_value[$field]  = $this->selections($name, [$field]);
		if (!empty($field_value[$field]->selections[$field])) {
			if (!empty($field_value[$field]->selections[$field])) {
				$values[$field] = $field_value[$field]->selections[$field];
			}
		}
		
		return $values[$field];
	}
	
	private $html = false;
	private function search_box($info, $tablename, $data, $model) {
		$this->form->excludeFields = ['password_field'];
		$this->form->hideFields    = ['id'];
		
		$script_elements = [];
		if (!empty($this->input_relations['type'])) {
			$field_value  = [];
			$open_field   = null;
			
			if (!empty($this->input_relations['lists'][0])) {
				$open_field = $this->input_relations['lists'][0];
			}
			
			if (!empty($open_field)) {
				foreach ($this->input_relations['type'] as $field => $type) {
					$values[$field] = null;
					
					if ($open_field === $field) $values[$field] = $this->set_first_selectbox($tablename, $field_value, $field);
					
					if (!empty($values[$field])) {
						$attributes = ['id' => $field];
					} else {
						$attributes = ['disabled' => 'disabled'];
					}
					
					$field_label = ucwords(diy_clean_strings($field, ' '));
					if ('selectbox' === $type) {
						if (null === $values[$field]) {
							$values[$field][null] = 'No Data ' . $field_label . ' Found';
							ksort($values[$field]);
						} else {
							$values[$field][null] = 'Select ' . $field_label;
							ksort($values[$field]);
						}
					}
					
					if ('radiobox' === $type) {
						if (null !== $values[$field] && count($values[$field]) > 1) $values[$field][null] = 'Clear!';
					}
					
					switch ($type) {
						case 'selectbox':
							$this->form->selectbox($field, $values[$field], false, $attributes, true, false);
							break;
						case 'checkbox':
							if (!empty($values[$field])) {
								if (!in_array('', $values[$field]) || !in_array(null, $values[$field])) $this->form->checkbox($field, $values[$field]);
							}
						break;
						case 'radiobox':
							if (!empty($values[$field])) {
								if (!in_array('', $values[$field]) || !in_array(null, $values[$field])) $this->form->radiobox($field, $values[$field]);
							}
							break;
						default:
							if (!empty($values[$field])) $this->form->text($field, $values[$field], ['id' => $field]);
	        		}
	        		
	        		$script_elements[$info][$field] = $type;
				}
			}
		} else {
			
			foreach ($data as $field => $type) {
				switch ($type) {
					case 'string':
						$this->form->text($field, null, ['id' => $field]);
						break;
					case 'text':
						$this->form->text($field, null, ['id' => $field]);
						break;
					case 'smallint':
						$this->form->selectbox($field, [], false, ['id' => $field]);
						break;
					case 'date':
						$this->form->date($field, null, ['id' => $field]);
						break;
					case 'datetime':
						$this->form->datetime($field, null, ['id' => $field]);
						break;
					case 'time':
						$this->form->time($field, null, ['id' => $field]);
						break;
					case 'daterange':
						$this->form->daterange($field, null, ['id' => $field]);
						break;
					default:
						$this->form->text($field, null, ['id' => $field]);
				}
				
				$script_elements[$info][$field] = $type;
			}
		}
	    
		$boxTitle   = ucwords(str_replace('-', ' ', diy_clean_strings($tablename)));
		$boxName    = $info . 'modalBOX';//diy_clean_strings($name);
		$this->addScriptsTemplate($script_elements, $tablename, $boxName);
		$this->html = diy_modal_content_html($boxName, $boxTitle, $this->form->elements);
	}
	
	public $add_scripts  = [];
	private function addScriptsTemplate(array $element_scripts, string $table, $node) {
		$current_template = diy_template_config('admin.' . diy_current_template());
		unset($current_template['position']);
		
		$nodElm           = str_replace('modalBOX', '', $node);
		$fields           = [];
		$scriptElements   = array_keys($element_scripts[$nodElm]);
		$fields['others'] = $scriptElements;
		
		$this->script_config($scriptElements);
		foreach ($scriptElements as $index => $field) {
			unset($scriptElements[$index]);
			
			$fields['current'] = [$index => $field];
			
			$this->script_next_data($node, $field, $fields, $table);
		}
		
		foreach ($element_scripts[$nodElm] as $type) {
			if ('selectbox' === $type) $type = 'select';
			
			foreach ($current_template as $element => $data) {
				if ($element === $type) {
					foreach ($data as $script_type => $script_paths) {
						if ('js' === $script_type) {
							foreach ($script_paths as $script_path) {
								$this->add_scripts['js'][]  = diy_script_check_string_path(str_replace('last:js', 'js', $script_path));
							}
						} else {
							foreach ($script_paths as $script_path) {
								$this->add_scripts['css'][] = diy_script_check_string_path(str_replace('last:css', 'css', $script_path));
							}
						}
					}
				}
			}
		}
	}
	
	private $scriptToHTML = 'diyScriptNode::';
	private function script_next_data($node, $identity, $fields, $table) {
		$currKey     = key($fields['current']);
		$next_target = null;
		
		if (!empty($fields['others'][$currKey+1])) $next_target = $fields['others'][key($fields['current'])+1];
		
		$nests       = [];		
		$prev        = null;
		$prevscript  = "null";
		$prevscripts = [];
		
		foreach ($fields['others'] as $idx => $value) {
			if ($idx < $currKey) {
				$nests['pref'][$idx] = $value;
			} else {
				if ($idx !== $currKey+1) $nests['next'][$idx] = $value;
			}
		}
		
		if (!empty($nests['pref'])) {
			$prev = implode('|', $nests['pref']);
			foreach ($nests['pref'] as $preval) {
				$prevscripts[] = "$('#{$preval}').val()";
			}
			$prevscript = implode("+'|'+", $prevscripts);
		}
		
		$nest     = null;
		$nesCript = null;
		if (!empty($nests['next'])) {
			$nest      = implode('|', $nests['next']);
			
			$nesCript  = "var _nx{$next_target}      = '{$next_target}';";
			$nesCript .= "var _reident{$next_target} = _nx{$next_target}.replace('_', ' ');";
			$nesCript .= "$('#{$next_target}').empty()";
				$nesCript .= ".append('<option value=\"\">No Data ' + ucwords(_reident{$next_target}) + ' Found</option>')";
				$nesCript .= ".prop('disabled', true).trigger('chosen:updated');";
			$nesCript .= "if (null != '{$nest}' && '' != '{$nest}') {";
				$nesCript .= "var _spldt{$identity} = '{$nest}';";
				$nesCript .= "var _spl{$identity} = _spldt{$identity}.split('|');";
				$nesCript .= "$.each(_spl{$identity}, function(i,obj) {";
					$nesCript .= "if (null != obj && '{$identity}' != obj) {";
						$nesCript .= "var _reident{$identity} = obj.replace('_', ' ');";
						$nesCript .= "$('#' + obj).empty()";
						$nesCript .= ".append('<option value=\"\">No Data ' + ucwords(_reident{$identity}) + ' Found</option>')";
						$nesCript .= ".prop('disabled', true).trigger('chosen:updated');";
					$nesCript .= "}";
				$nesCript .= "});";
			$nesCript .= "}";
		}
		
		$forkey = [];
		if (!empty($this->foreign_keys)) {
			$forkey   = $this->foreign_keys;
		}
		$forkeys     = json_encode($forkey);
		
		$uri         = diy_get_ajax_urli('filterDataTables');
		$token       = csrf_token();
		$target      = ucwords(str_replace('_', ' ', $next_target));
		$ajaxSuccess = null;
		
		if (!empty($next_target)) {
			$ajax_data = "{'{$identity}':_val{$identity},'_fita':'{$token}::{$table}::{$next_target}::{$prev}#' + _prefS{$identity} + '::{$nest}','_token':'{$token}','_n':'{$nest}','_forKeys':'{$forkeys}'}";
			
			$ajaxSuccess  = "var _next{$next_target}	= '{$target}';";
			$ajaxSuccess .= "var _prefS{$identity}	= {$prevscript};";
			$ajaxSuccess .= "$.ajax ({";
				$ajaxSuccess .= "type : 'POST',";
				$ajaxSuccess .= "url : '{$uri}',";
				$ajaxSuccess .= "data : {$ajax_data},";
				$ajaxSuccess .= "dataType : 'json',";
				$ajaxSuccess .= "beforeSend : function() {";
					$ajaxSuccess .= "$('#cdyInpLdr{$next_target}').show();";
				$ajaxSuccess .= "},";
				$ajaxSuccess .= "success : function(data) {";
					$ajaxSuccess .= "if (data) {";
						$ajaxSuccess .= "if ('' != '{$next_target}' && null != '{$next_target}') {";
							$ajaxSuccess .= "$('#{$next_target}').removeAttr('disabled').trigger('chosen:updated');";
							$ajaxSuccess .= "$('#{$next_target}').empty();";
							$ajaxSuccess .= "$('#{$next_target}').append('<option value=\"\">Select ' + _next{$next_target} + '</option>').trigger('chosen:updated');";
							$ajaxSuccess .= "$.each(data, function(key, value) {";
								$ajaxSuccess .= "$('#{$next_target}').append('<option value=\"'+ value.{$next_target} +'\">' + value.{$next_target} + '</option>').trigger('chosen:updated');";
							$ajaxSuccess .= "});";
						$ajaxSuccess .= "}";
					$ajaxSuccess .= "}";
				$ajaxSuccess .= "},";
				$ajaxSuccess .= "complete : function() {";
					$ajaxSuccess .= "$('#cdyInpLdr{$next_target}').hide();";
				$ajaxSuccess .= "}";
			$ajaxSuccess .= "});";
		}
		
		$script = null;
		if (!empty($identity)) {
			$script = "jQuery(function($) {";
			
				$script .= "$('#{$node}').children('div.form-group').each(function (x, y) {";
					$script .= "var iterate{$this->cleardash($node)} = $(this).find('select#{$identity}');";
					
					$script .= "iterate{$this->cleardash($node)}.change(function (i, x) {";
						$script .= "var _val{$identity} = $(this).val();";
						$script .= "if (_val{$identity} != '0' && _val{$identity} != null && _val{$identity} != '') {";
							$script .= "{$ajaxSuccess}";
						$script .= "} else {";
							$script .= "{$nesCript}";
						$script .= "}";
					$script .= "});";
					
				$script .= "});";
				
			$script .= "});";
		}
		
		$this->add_scripts['js'][] = "{$this->scriptToHTML}{$script}";
	}
	
	private function cleardash($string) {
		return str_replace('-', '_', $string);
	}
	
	private function script_config($fields) {
		$FieldSets = [];
		if (!empty($fields)) {
			foreach ($fields as $index => $field) {
				if ($index >= 1) $FieldSets[] = "loader('{$field}');";
			}
		}
		$fieldScripts = implode('', $FieldSets);
				
		$this->add_scripts['js'][] = "{$this->scriptToHTML}{$fieldScripts}";
	}
	
	private function getColumnInfo(string $table, array $fields) {
		$columns = [];
		foreach ($this->getColumns($table) as $column) {
			$columns[$column] = $this->getColumnType($table, $column);
		}
		
		$info = [];
		foreach ($fields as $field) {
			if (!empty($columns[$field])) {
				$info[$field] = $columns[$field];
			}
		}
		
		return $info;
	}
	
	private function getColumns($table) {
		return diy_get_table_columns($table);
	}
	
	private function getColumnType($table, $column) {
		return diy_get_table_column_type($table, $column);
	}
}