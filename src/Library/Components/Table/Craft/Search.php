<?php
namespace Incodiy\Codiy\Library\Components\Table\Craft;

use Incodiy\Codiy\Library\Components\Form\Objects as Form;

/**
 * Created on 24 Apr 2021
 * Time Created	: 20:51:52
 *
 * @filesource	Search.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Search {
	
	private $model;
	private $form;
	private $filters;
	private $relations;
	private $sql;
	private $table;
	
	public function __construct($model = null, $filters = [], $sql = null) {
		if (!empty($model)) $this->model = new $model();
		
		$this->form    = new Form();
		$this->filters = $filters;
		$this->table   = $filters['table_name'];
		$this->sql     = $sql;
		
		if (!empty($filters['filter_groups'])) $this->getFilterData($filters['filter_groups']);
	}
	
	public function render(string $table, array $fields) {
		$this->search_box($table, $this->getColumnInfo($table, $fields), $this->model);
		
		$data         = [];
		$data['name'] = ucwords(str_replace('-', ' ', diy_clean_strings($table)));
		$data['html'] = $this->html;
		
		return $data;
	}
	
	private function select($sql) {
		return diy_query($sql, 'select');
	}
	
	private $data   = [];
	private function getFilterData($data) {
		$all_columns = [];
		$relists     = [];
		$relations   = [];
		
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
		
		foreach ($relists as $relist) {
			if (false !== $relist) {
				foreach ($relist as $relation) {
					$relations['lists'][] = $relation;
					if (!empty($data[$relation]['type'])) {
						$relations['type'][$relation] = $data[$relation]['type'];
					} else {
						$relations['type'][$relation] = 'text';
					}
				}
			}
		}
		if (!empty($relations['lists'])) {
		    $this->relations['lists'] = array_unique($relations['lists']);
		}
		if (!empty($relations['type'])) {
		    $this->relations['type']  = $relations['type'];
		}
	}
	
	private $selections = [];
	private function selections($table, $fields = [], $condition = null) {
		$strfields = implode(',', $fields);
		$where     = null;
		if (!empty($condition)) {
		    $where = "WHERE ID IS NOT NULL ";
		}
		
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
			
			return $this;
		}
	}
	
	private $html = false;
	private function search_box($name, $data, $model) {
		$this->form->excludeFields = ['password_field'];
		$this->form->hideFields    = ['id'];
		
		$script_elements = [];
		if (!empty($this->relations['type'])) {
			$field_value  = [];
			$values       = null;
			$open_field   = null;
			
			if (!empty($this->relations['lists'][0])) {
				$open_field = $this->relations['lists'][0];
			}
			
			if (!empty($open_field)) {
				foreach ($this->relations['type'] as $field => $type) {
					if ($open_field === $field) {
						
						$field_value[$field] = $this->selections($name, [$field]);
						if (!empty($field_value[$field]->selections[$field])) {
							if (!empty($field_value[$field]->selections[$field])) {
								$values = $field_value[$field]->selections[$field];
							}
						}
						
					} else {
						$values = null;
					}
					
					if (!empty($values)) {
						$attributes = ['id' => $field];
					} else {
						$attributes = ['disabled' => 'disabled'];
					}
					
					$field_label = ucwords(diy_clean_strings($field, ' '));
					if ('selectbox' === $type) {
						if (null === $values) {
							$values[null] = 'No Data ' . $field_label . ' Found';
							ksort($values);
						} else {
							$values[null] = 'Select ' . $field_label;
							ksort($values);
						}
					}
					
					if ('radiobox' === $type) {
						if (null !== $values && count($values) > 1) $values[null] = 'Clear!';
					}
					
					switch ($type) {
						case 'selectbox':
							$this->form->selectbox($field, $values, false, $attributes, true, false);
							break;
						case 'checkbox':
							if (!empty($values)) {
								if (!in_array('', $values) || !in_array(null, $values)) {
									$this->form->checkbox($field, $values);
								}
							}
						break;
						case 'radiobox':
							if (!empty($values)) {
								if (!in_array('', $values) || !in_array(null, $values)) {
									$this->form->radiobox($field, $values);
								}
							}
							break;
						default:
							if (!empty($values)) $this->form->text($field, $values, ['id' => $field]);
	        		}
	        		$script_elements[$field] = $type;
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
				$script_elements[$field] = $type;
			}
		}
	    
		$this->addScriptsTemplate($script_elements, $name);
		$title      = ucwords(str_replace('-', ' ', diy_clean_strings($name)));
		$name       = diy_clean_strings($name);
		$this->html = diy_modal_content_html($name, $title, $this->form->elements);
	}
	
	public $add_scripts  = [];
	private function addScriptsTemplate(array $element_scripts, string $table) {
		$current_template = diy_template_config('admin.' . diy_current_template());
		unset($current_template['position']);
		
		$fields           = [];
		$scriptElements   = array_keys($element_scripts);
		$fields['others'] = $scriptElements;
		
		$this->script_config($scriptElements);
		foreach ($scriptElements as $index => $field) {
			unset($scriptElements[$index]);
			
			$fields['current'] = [$index => $field];
			
			$this->script_next_data($field, $fields, $table);
		}
		
		foreach ($element_scripts as $type) {
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
	private function script_next_data($identity, $fields, $table) {;
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
			$nest     = implode('|', $nests['next']);
			$nesCript = "
				var _nx{$next_target}      = '{$next_target}';
				var _reident{$next_target} = _nx{$next_target}.replace('_', ' ');
				
				$('#{$next_target}')
					.empty()
					.append('<option value=\"\">No Data ' + _ucwords(_reident{$next_target}) + ' Found</option>')
					.prop('disabled', true)
					.trigger('chosen:updated');
				
				if (null != '{$nest}' && '' != '{$nest}') {
					var _spldt{$identity} = '{$nest}';
					var _spl{$identity} = _spldt{$identity}.split('|');
					
					$.each(_spl{$identity}, function(i,obj) {
						if (null != obj && '{$identity}' != obj) {
							var _reident{$identity} = obj.replace('_', ' ');
							$('#' + obj)
								.empty()
								.append('<option value=\"\">No Data ' + _ucwords(_reident{$identity}) + ' Found</option>')
								.prop('disabled', true)
								.trigger('chosen:updated');
						}
					});
				}
			";
		}
		
		$uri         = url(diy_current_route()->uri) . '?filterDataTables=true';
		$token       = csrf_token();
		$target      = ucwords(str_replace('_', ' ', $next_target));
		$ajaxSuccess = null;
		if (!empty($next_target)) {
			$ajaxSuccess = "
				var _next{$next_target}	= '{$target}';
				var _prefS{$identity}	= {$prevscript};
				
				$.ajax ({
					type       : 'POST',
					url        : '{$uri}',
					data       : {'{$identity}':_val{$identity},'_fita':'{$token}::{$table}::{$next_target}::{$prev}#' + _prefS{$identity} + '::{$nest}','_token':'{$token}','_n':'{$nest}'},
					dataType   : 'json',
					beforeSend : function() {
						$('#cdyInpLdr{$next_target}').show();
					},
					success    : function(data) {
						if (data) {
							if ('' != '{$next_target}' && null != '{$next_target}') {
								$('#{$next_target}').removeAttr('disabled').trigger('chosen:updated');
								$('#{$next_target}').empty();
								$('#{$next_target}').append('<option value=\"\">Select ' + _next{$next_target} + '</option>').trigger('chosen:updated');
								
								$.each(data, function(key, value) {
									$('#{$next_target}').append('<option value=\"'+ value.{$next_target} +'\">' + value.{$next_target} + '</option>').trigger('chosen:updated');
								});
							}
						}
					},
					complete	: function() {
						$('#cdyInpLdr{$next_target}').hide();
					}
				});
			";
		}
		
		$script = "
			jQuery(function($) {
				$('#{$identity}').change(function() {
					var _val{$identity} = $(this).val();
					if (_val{$identity} != '0' && _val{$identity} != null && _val{$identity} != '') { {$ajaxSuccess} } else { {$nesCript} }
				});
			});
		";
		$this->add_scripts['js'][] = "{$this->scriptToHTML}{$script}";
	}
	
	private function script_config($fields) {
		$FieldSets = [];
		if (!empty($fields)) {
			foreach ($fields as $index => $field) {
				if ($index >= 1) {
					$FieldSets[] = "$('#{$field}').before('<span class=\"inputloader loader hide\" id=\"cdyInpLdr{$field}\"></span>');";
				}
			}
		}
		$fieldScripts = implode('', $FieldSets);
		
		$script = "
			function _ucwords (str) {
			    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
			        return $1.toUpperCase();
			    });
			}
			{$fieldScripts}
		";
		
		$this->add_scripts['js'][] = "{$this->scriptToHTML}{$script}";
	}
	
	private function getColumnInfo(string $table, array $fields) {
		$columns = [];
		foreach ($this->getColumns($table) as $column) {
			$columns[$column] = $this->getColumnType($table, $column);
		}
		
		$info = [];
		foreach ($fields as $field) {
			$info[$field] = $columns[$field];
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