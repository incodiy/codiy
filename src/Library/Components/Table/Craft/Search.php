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
		
		$this->form		= new Form();
		$this->filters	= $filters;
		$this->table	= $filters['table_name'];
		$this->sql		= $sql;
		
		if (!empty($filters['filter_groups'])) $this->getFilterData($filters['filter_groups']);
	}
	
	public function render(string $table, array $fields) {
		$this->search_box($table, $this->getColumnInfo($table, $fields), $this->model);
		
		$data		  = [];
		$data['name'] = ucwords(str_replace('-', ' ', diy_clean_strings($table)));
		$data['html'] = $this->html;
		
		return $data;
	}
	
	private function select($sql) {
		return diy_query($sql, 'select');
	}
	
	private $data = [];
	private function getFilterData($data) {
		$all_columns	= [];
		$relists		= [];
		$relations		= [];
		
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
			
			$data[$row['column']]['name']	= $row['column'];
			$data[$row['column']]['type']	= $row['type'];
			$data[$row['column']]['relate']	= $relists[$key];
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
		$this->relations['lists']	= array_unique($relations['lists']);
		$this->relations['type']	= $relations['type'];
	}
	
	private $selections = [];
	private function selections($table, $fields = [], $condition = null) {
		$strfields	= implode(',', $fields);
		$where		= null;
		if (!empty($condition)) $where = "WHERE ID IS NOT NULL ";
		$query		= $this->select("SELECT {$strfields} FROM `{$table}` {$where}GROUP BY {$strfields};");
		
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
	
	private $html = false;
	private function search_box($name, $data, $model) {
		$this->form->excludeFields	= ['password_field'];
		$this->form->hideFields		= ['id'];
		
		$script_elements = [];
		if (!empty($this->relations['type'])) {
			$field_value = [];
			$values		 = null;
			
			foreach ($this->relations['type'] as $field => $type) {
				
				$field_value[$field] = $this->selections($name, [$field]);
				if (!empty($field_value[$field]->selections[$field])) {
					$values = $field_value[$field]->selections[$field];
				}
				if ('selectbox' === $type) {
					$values[null] = 'Select';
					ksort($values);
				}
				if ('radiobox' === $type) {
					$values[null] = 'Clear!';
				}
				
				switch ($type) {
					case 'selectbox':
						$this->form->selectbox($field, $values, false, ['id' => $field], true, false);
						break;
					case 'checkbox':
						$this->form->checkbox($field, $values);
						break;
					case 'radiobox':
						$this->form->radiobox($field, $values);
						break;
					default:
						$this->form->text($field, $values, ['id' => $field]);
				}
				
				$script_elements[$field] = $type;
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
		
		
		$this->addScriptsTemplate($script_elements);
		
		$title		= ucwords(str_replace('-', ' ', diy_clean_strings($name)));
		$name		= diy_clean_strings($name);
		$this->html = diy_modal_content_html($name, $title, $this->form->elements);
	}
	
	public $add_scripts = [];
	private function addScriptsTemplate($element_scripts = []) {
		$current_template = diy_template_config('admin.' . diy_current_template());
		unset($current_template['position']);
		
		foreach ($element_scripts as $type) {
			foreach ($current_template as $element => $data) {
				if ($element === $type) {
					foreach ($data as $script_type => $script_paths) {
						if ('js' === $script_type) {
							foreach ($script_paths as $script_path) {
								$this->add_scripts['js'][] = diy_script_check_string_path(str_replace('last:js', 'js', $script_path));
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