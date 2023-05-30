<?php
namespace Incodiy\Codiy\Library\Components\Table;

use Incodiy\Codiy\Library\Components\Table\Craft\Builder;
use Incodiy\Codiy\Library\Components\Form\Elements\Tab;
use Incodiy\Codiy\Library\Components\Charts\Objects as Chart;

/**
 * Created on 12 Apr 2021
 * Time Created : 19:24:03
 * 
 * Marhaban Yaa RAMADHAN
 *
 * @filesource Objects.php
 *
 * @author    wisnuwidi@incodiy.com - 2021
 * @copyright wisnuwidi
 * @email     wisnuwidi@incodiy.com
 */
 
class Objects extends Builder {
	use Tab;
	
	public $elements      = [];
	public $element_name  = [];
	public $records       = [];
	public $columns       = [];
	public $labels        = [];
	public $relations     = [];
	public $connection;
	
	private $params       = [];
	private $setDatatable = true;
	private $tableType    = 'datatable';
	
	/**
	 * --[openTabHTMLForm]--
	 */
	private $opentabHTML  = '--[openTabHTMLForm]--';
	
	public function __construct() {
		$this->element_name['table']    = $this->tableType;
		$this->variables['table_class'] = 'table animated fadeIn table-striped table-default table-bordered table-hover dataTable repeater display responsive nowrap';
	}
	
	public function method($method) {
		$this->method = $method;
	}
	
	public $labelTable = null;
	public function label($label) {
		$this->labelTable = $label;
	}
	
	private $syncElements = false;
	public function chart($chart_type, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		$chart             = new Chart();
		$chart->connection = $this->connection;
		$chart->syncWith($this);
		$chart->{$chart_type}($this->tableName, $fieldsets, $format, $category, $group, $order);
		
		$this->element_name['chart']      = $chart->chartLibrary;
		$tableIdentity                    = $this->tableID[$this->tableName];
		$canvas                           = [];
		$canvas['chart'][$tableIdentity]  = $chart->elements;
		$initTable                        = [];
		$initTable['chart']               = $this->tableID[$this->tableName];
		
		$tableElement  = $this->elements[$tableIdentity];
		$canvasElement = $canvas['chart'][$tableIdentity];
		
		$this->syncElements[$tableIdentity]['chart_info']   = $chart->identities;
		$this->syncElements[$tableIdentity]['filter_table'] = "{$tableIdentity}_cdyFILTERForm";
		
		$chart->modifyFilterTable($this->syncElements[$tableIdentity]);
		
		$syncElements  = [];
		$syncElements['chart'][$tableIdentity] = $tableElement . $chart->script_chart['js'] . implode('', $canvasElement);
		
		$this->draw($initTable, $syncElements);
	}
	
	public $filter_scripts = [];
	private function draw($initial, $data = []) {
		if ($data) {
			$multiElements = [];
			if (is_array($initial)) {
				foreach ($initial as $syncElements) {
					if (is_array($data)) {
						foreach ($data as $dataValue) {
							$initData = $dataValue[$syncElements];
							if (is_array($initData)) {
								$multiElements[$syncElements] = implode('', $initData);
							} else {
								$multiElements[$syncElements] = $initData;
							}
						}
					}
					$this->elements[$syncElements] = $multiElements[$syncElements];
				}
			} else {
				$this->elements[$initial] = $data;
			}
		//	dd($this->filter_object->add_scripts['add_js']);
			if (!empty($this->filter_object->add_scripts)) {
				if (true === array_key_exists('add_js', $this->filter_object->add_scripts)) {
					$scriptCss = [];
					if (isset($this->filter_object->add_scripts['css'])) {
						$scriptCss = $this->filter_object->add_scripts['css'];
						unset($this->filter_object->add_scripts['css']);
					}
					
					$scriptJs = [];
					if (isset($this->filter_object->add_scripts['js'])) {
						$scriptJs = $this->filter_object->add_scripts['js'];
						unset($this->filter_object->add_scripts['js']);
					}
					$scriptAdd = $this->filter_object->add_scripts['add_js'];
					unset($this->filter_object->add_scripts['add_js']);
					
					$this->filter_scripts['css'] = $scriptCss;
					
					$JSScripts = [];
					$JSScripts = $scriptJs;
					foreach ($scriptAdd as $addScripts) {
						$JSScripts[] = $addScripts;
					}
					
					foreach ($JSScripts as $js) {
						$this->filter_scripts['js'][] = $js;
					}
					
				} else {
					$this->filter_scripts = $this->filter_object->add_scripts;
				}
			}
		} else {
			$this->elements[] = $initial;
		}
	}
	
	public function render($object) {
		$tabObj = "";
		if (true === is_array($object)) $tabObj = implode('', $object);
		
		if (true === diy_string_contained($tabObj, $this->opentabHTML)) {
			return $this->renderTab($object);
		} else {
			return $object;
		}
	}
	
	public function setDatatableType($set = true) {
		$this->setDatatable = $set;
		if (true !== $this->setDatatable) $this->tableType = 'self::table';
		$this->element_name['table'] = $this->tableType;
	}
	
	public function setName($table_name) {
		$this->variables['table_name'] = $table_name;
	}
	
	public function setFields($fields) {
		$this->variables['table_fields'] = $fields;
	}
	
	public function model($model) {
		$this->variables['table_data_model'] = $model;
	}
	
	public function query($sql) {
		$this->variables['query'] = $sql;
		$this->model('sql');
	}
	
	public function setActions($action = true) {
		$this->variables['table_actions'] = $action;
	}
	
	public function setServerSide($server_side = true) {
		$this->variables['table_server_side'] = $server_side;
	}
	
	public function mergeColumns($label, $merged_columns = [], $label_position = 'top') {
		$this->variables['merged_columns'][$label] = ['position' => $label_position, 'counts' => count($merged_columns), 'columns' => $merged_columns];
	}
	
	public $hidden_columns = [];
	public function setHiddenColumns($fields = []) {
		$this->variables['hidden_columns'] = $fields;
	}
	
	public function fixedColumns($left_pos = null, $right_pos = null) {
		if (!empty($left_pos))  $this->variables['fixed_columns']['left']  = $left_pos;
		if (!empty($right_pos)) $this->variables['fixed_columns']['right'] = $right_pos;
	}
	
	public function clearFixedColumns() {
		if (!empty($this->variables['fixed_columns'])) unset($this->variables['fixed_columns']);
	}
	
	/**
	 * Set Column Alignment
	 *
	 * @param string $align ['right', 'center', 'left']
	 * @param array $columns
	 * @param boolean $header
	 * @param boolean $body
	 */
	public function setAlignColumns(string $align, $columns = [], $header = true, $body = true) {
		$this->variables['text_align'][$align] = ['columns' => $columns, 'header' => $header, 'body' => $body];
	}
	
	public function setRightColumns($columns = [], $header = true, $body = true) {
		$this->setAlignColumns('right', $columns, $header, $body);
	}
	
	public function setCenterColumns($columns = [], $header = true, $body = true) {
		$this->setAlignColumns('center', $columns, $header, $body);
	}
	
	public function setLeftColumns($columns = [], $header = true, $body = true) {
		$this->setAlignColumns('left', $columns, $header, $body);
	}
	
	public function setBackgroundColor($color, $text_color = null, $columns = null, $header = true, $body = false) {
		$this->variables['background_color'][$color] = ['code' => $color, 'text' => $text_color, 'columns' => $columns, 'header' => $header, 'body' => $body];
	}
	
	public function setColumnWidth($field_name, $width = false) {
		$this->variables['column_width'][$field_name] = $width;
	}
	
	public function addAttributes($attributes = []) {
		$this->variables['add_table_attributes'] = $attributes;
	}
	
	public function setWidth(int $width, string $measurement = 'px') {
		return $this->addAttributes(['style' => "min-width:{$width}{$measurement};"]);
	}
	
	private $all_columns = 'all::columns';
	private function checkColumnSet($columns) {
		if (empty($columns)) {
			if (false === $columns) {
				$value = [$this->all_columns => false];
			} else {
				$value = [$this->all_columns => true];
			}
		} else {
			$value = $columns;
		}
		
		return $value;
	}
	
	public $relational_data = [];
	private function relation_draw($relation, $relation_function, $fieldname, $label) {
		if (!empty($relation->{$relation_function})) {
			$dataRelate = $relation->{$relation_function}->getAttributes();
			$relateKEY  = intval($relation['id']);
		} else {
			$dataRelate = $relation->getAttributes();
			$relateKEY  = intval($dataRelate['id']);
		}
		
		$fieldReplacement = null;
		if (diy_string_contained($fieldname, '::')) {
			$fieldsplit       = explode('::', $fieldname);
			$fieldReplacement = $fieldsplit[0];
			$fieldname        = $fieldsplit[1];
			$data_relation    = $dataRelate[$fieldname];
			$data_value       = $dataRelate[$fieldname];
		} else {
			$data_relation    = $dataRelate[$fieldname];
			$data_value       = $dataRelate[$fieldname];
		}
		
		if (!empty($data_relation)) {
			$fieldset = $fieldname;
			if (!is_empty($fieldReplacement)) $fieldset = $fieldReplacement;
			
			$this->relational_data[$relation_function]['field_target'][$fieldset]['field_name']  = $fieldset;
			$this->relational_data[$relation_function]['field_target'][$fieldset]['field_label'] = $label;
			
			if (!empty($relation->pivot)) {
				foreach ($relation->pivot->getAttributes() as $pivot_field => $pivot_data) {
					$this->relational_data[$relation_function]['field_target'][$fieldset]['relation_data'][$relateKEY][$pivot_field] = $pivot_data;
				}
			}
			
			$this->relational_data[$relation_function]['field_target'][$fieldset]['relation_data'][$relateKEY]['field_value'] = $data_value;
		}
	}
	
	/**
	 * Set Relation Data Table
	 * 
	 * @param object $model
	 * @param string $relation_function
	 * @param string $field_display
	 * @param array  $filter_foreign_keys :[
	 *			'base_user_group:user_id' => 'users:id',
	 *			'base_group:id'           => 'base_user_group:group_id'
	 *	]
	 * @param string $label
	 * @param string $field_connect
	 * 
	 * @return array
	 */
	private function relationship($model, $relation_function, $field_display, $filter_foreign_keys = [], $label = null, $field_connect = null) {
		if (!empty($model->with($relation_function)->get())) {
			$relational_data = $model->with($relation_function)->get();
			if (empty($label)) {
				$label = ucwords(diy_clean_strings($field_display, ' '));
			}
			
			foreach ($relational_data as $item) {
				if (!empty($item->{$relation_function})) {
					if (diy_is_collection($item->{$relation_function})) {
						foreach ($item->{$relation_function} as $relation) {
							$this->relation_draw($relation, $relation_function, $field_display, $label);
						}
					} else {
						$this->relation_draw($item, $relation_function, "{$field_connect}::{$field_display}", $label);
					}
				}
			}
			
			if (!empty($filter_foreign_keys)) $this->relational_data[$relation_function]['foreign_keys'] = $filter_foreign_keys;
		}
	}
	
	/**
	 * Set Simple Relation Data Table
	 * 
	 * @param object $model
	 * @param string $relation_function
	 * @param string $field_display
	 * @param array  $filter_foreign_keys :[
	 *			'base_user_group:user_id' => 'users:id',
	 *			'base_group:id'           => 'base_user_group:group_id'
	 *	]
	 * @param string $label
	 * 
	 * @return array
	 */
	public function relations($model, $relation_function, $field_display, $filter_foreign_keys = [], $label = null) {
		return $this->relationship($model, $relation_function, $field_display, $filter_foreign_keys, $label, null);
	}
	
	/**
	 * Change Fieldname Value With Relational Data
	 *
	 * @param object $model
	 * @param string $relation_function
	 * @param string $field_display
	 * @param string $label
	 * @param string $field_connect
	 *
	 * @return array
	 */
	public function fieldReplacementValue($model, $relation_function, $field_display, $label = null, $field_connect = null) {
		return $this->relationship($model, $relation_function, $field_display, [], $label, $field_connect);
	}
	
	public function orderby($column, $order = 'asc') {
		$this->variables['orderby_column'] = [];
		$this->variables['orderby_column'] = ['column' => $column, 'order' => $order];
	}
	
	/**
	 * Set Sortable Column(s)
	 * 
	 * @param string|array $columns
	 */
	public function sortable($columns = null) {
		$this->variables['sortable_columns'] = [];
		$this->variables['sortable_columns'] = $this->checkColumnSet($columns);
	}
	
	/**
	 * Set Clickable Column(s)
	 * 
	 * @param string|array $columns
	 */
	public function clickable($columns = null) {
		$this->variables['clickable_columns'] = [];
		$this->variables['clickable_columns'] = $this->checkColumnSet($columns);
	}
	
	public $search_columns = false;
	/**
	 * Set Seachable Column(s)
	 * 
	 * @param string|array $columns
	 */
	public function searchable($columns = null) {
		$this->variables['searchable_columns'] = [];
		$this->variables['searchable_columns'] = $this->checkColumnSet($columns);
		if (empty($columns)) {
			if (false === $columns) {
				$filter_columns = false;
			} else {
				$filter_columns = $this->all_columns;
			}
		} else {
			$filter_columns    = $columns;
		}
		
		$this->search_columns = $filter_columns;
	}
	
	/**
	 * Set Searching Data Filter
	 * 
	 * @param string $column
	 * 		: field name target
	 * @param string $type
	 * 		: inputbox     [no relational data $relate auto set with false], 
	 *         datebox      [no relational data $relate auto set with false], 
	 *         daterangebox [no relational data $relate auto set with false], 
	 *         selectbox    [single or multi], 
	 *         checkbox, 
	 *         radiobox
	 * @param boolean|string|array $relate
	 * 		: if false = no relational Data
	 * 		: if true  = relational data set to all others columns/fieldname members
	 * 		: if (string) fieldname / other column = relate to just one that column target was setted
	 * 		: if (array) fieldnames / others any columns = relate to any that column target was setted
	 */
	public function filterGroups($column, $type, $relate = false) {
		$filters           = [];
		$filters['column'] = $column;
		$filters['type']   = $type;
		$filters['relate'] = $relate;
		
		$this->variables['filter_groups'][] = $filters;
	}
	
	public function displayRowsLimitOnLoad($limit = 10) {
		if (is_string($limit)) {
			if (in_array(strtolower($limit), ['*', 'all'])) {
				$this->variables['on_load']['display_limit_rows'] = '*';
			}
		} else {
			$this->variables['on_load']['display_limit_rows'] = intval($limit);
		}
	}
	
	public function clearOnLoad() {
		unset($this->variables['on_load']['display_limit_rows']);
	}
	
	protected $filter_model = [];
	public function filterModel(array $data = []) {
		$this->filter_model = $data;
	}
	
	private function check_column_exist($table_name, $fields, $connection = 'mysql') {
		$fieldset = [];
		foreach ($fields as $field) {
			if (diy_check_table_columns($table_name, $field, $connection)) {
				$fieldset[] = $field;
			}
		}
		
		return $fieldset;
	}
	
	private $clear_variables = null;
	private function clearVariables($clear_set = true) {
		$this->clear_variables = $clear_set;
		if (true === $this->clear_variables) {
			$this->clear_all_variables();
		}
	}
	
	public function clear($clear_set = true) {
		return $this->clearVariables($clear_set);
	}
	
	public function clearVar($name) {
		$this->variables[$name] = [];
	}
	
	private $variables = [];
	private function clear_all_variables() {
		$this->variables['on_load']              = [];
		$this->variables['merged_columns']       = [];
		$this->variables['text_align']           = [];
		$this->variables['background_color']     = [];
		$this->variables['attributes']           = [];
		$this->variables['orderby_column']       = [];
		$this->variables['sortable_columns']     = [];
		$this->variables['clickable_columns']    = [];
		$this->variables['searchable_columns']   = [];
		$this->variables['filter_groups']        = [];
		$this->variables['column_width']         = [];
		$this->variables['format_data']          = [];
		$this->variables['add_table_attributes'] = [];
		$this->variables['fixed_columns']        = [];
	}
	
	public $conditions = [];
	public function where($field_name, $logic_operator = false, $value = false) {
		$this->conditions['where'] = [];
		if (is_array($field_name)) {
			foreach ($field_name as $fieldname => $fieldvalue) {
				$this->conditions['where'][] = [
					'field_name' => $fieldname,
					'operator'   => '=',
					'value'      => $fieldvalue
				];
			}
		} else {
			$this->conditions['where'][] = [
				'field_name' => $field_name,
				'operator'   => $logic_operator,
				'value'      => $value
			];
		}
	}
	
	/**
	 * Filter Table
	 * 
	 * @param array $filters
	 * 		: $this->model_filters
	 * @return array
	 */
	public function filterConditions($filters = []) {
		return $this->where($filters);
	}
	
	/**
	 * Set Data Condition By Column
	 * 
	 * @param string $field_name
	 * @param string $target
	 *       : [ row, cell, field_name ]
	 * @param string $logic_operator
	 *       : [ =, != ] { dev:contains, !contain }
	 * @param string $value
	 * @param string $rule
	 *       : [ css style, prefix, suffix, prefix&suffix, replace, integer, float [ code: float or float|2 ] ]
	 * @param string|array $action
	 *       : array used for "prefix&suffix" rule type.
	 *         First array should be prefix value and second/last array should be suffix value.
	 * @example
	 *       : $this->table->columnCondition('text_field', 'cell', '!==', 'Testing', 'prefix', '! ');
	 *       : $this->table->columnCondition('text_field', 'row', '!==', 'Testing', 'background-color', '#F1F7CB');
	 */
	public function columnCondition(string $field_name, string $target, string $logic_operator = null, string $value = null, string $rule, $action) {
		$this->conditions['columns'][] = [
			'field_name'     => $field_name,
			'field_target'   => $target,
			'logic_operator' => $logic_operator,
			'value'          => $value,
			'rule'           => $rule,
			'action'         => $action
		];
	}
	
	public $formula = [];
	/**
	 * Create Formula For Calculate Each Data Column
	 * 
	 * @param string $name
	 * @param string $label
	 * @param array $field_lists
	 * @param string $logic
	 * @param string $node_location
	 * @param bool $node_after_node_location
	 */
	public function formula(string $name, string $label = null, array $field_lists, string $logic, string $node_location = null, bool $node_after_node_location = true) {
		$this->labels[$name]           = $label;
		$this->conditions['formula'][] = [
			'name'          => $name,
			'label'         => $label,
			'field_lists'   => $field_lists,
			'logic'         => $logic,
			'node_location' => $node_location,
			'node_after'    => $node_after_node_location
		];
	}
	
	/**
	 * Format Data
	 *
	 * @param string|array $fields
	 * @param int $decimal_endpoint
	 * 	: Specifies how many decimals
	 * @param string $separator
	 * 	: [,], [.]
	 * @param string $format
	 * 	: number, boolean
	 */
	public function format($fields, int $decimal_endpoint = 0, $separator = '.', $format = 'number') {
		if (is_array($fields)) {
			foreach ($fields as $field) {
				$this->variables['format_data'][$field] = [
					'field_name'       => $field,
					'decimal_endpoint' => $decimal_endpoint,
					'format_type'      => $format,
					'separator'        => $separator
				];
			}
			
		} else {
			$this->variables['format_data'][$fields] = [
				'field_name'          => $fields,
				'decimal_endpoint'    => $decimal_endpoint,
				'format_type'         => $format,
				'separator'           => $separator
			];
		}
	}
	
	public function set_regular_table() {
		$this->tableType = 'regular';
	}
	
	public $button_removed = [];
	public function destroyButton($remove = null) {
		if (!empty($remove)) {
			if (is_array($remove)) {
				$this->button_removed = $remove;
			} else {
				$this->button_removed = [$remove];
			}
		}
	}
	
	private $objectInjections = [];
	public $filterPage = [];
	/**
	 * Initiate Configuration
	 * 
	 * @param string $connection
	 * @param array $object
	 */
	public function config($object = []) {
		if (!empty($this->connection)) {
			$this->connection($this->connection);
		}
		
		if (!empty($this->filter_page)) {
			$this->filterPage = $this->filter_page;
		}
	}
	
	public function connection($db_connection) {
		$this->connection = $db_connection;
	}
	
	public function resetConnection() {
		$this->connection = null;
	}
	
	public $tableName = [];
	public $tableID   = [];
	/**
	 * Create List(s) Data Table
	 * 
	 * @param string $table_name
	 * @param array $fields
	 * @param boolean|string|array $actions
	 * 	: format => string = 'button_name|button_color|button_icon'
	 * 	: format => array  = ['view', 'edit', 'delete', 'new_button', 'button_name|button_color|button_icon']
	 * @param boolean $server_side
	 * @param boolean $numbering
	 * @param array $attributes
	 * @param boolean $server_side_custom_url
	 */
	public function lists(string $table_name = null, $fields = [], $actions = true, $server_side = true, $numbering = true, $attributes = [], $server_side_custom_url = false) {
		if (null === $table_name) {
			if (!empty($this->variables['table_data_model'])) {
				if ('sql' === $this->variables['table_data_model']) {
					$sql        = $this->variables['query'];
					$table_name = diy_get_table_name_from_sql($sql);
					$this->params[$table_name]['query'] = $sql;
				} else {
					$table_name = diy_get_model_table($this->variables['table_data_model']);
				}
			}
			
			$this->variables['table_name'] = $table_name;
		}
		$this->tableName = $table_name;
		$this->records['index_lists'] = $numbering;
		
		if (is_array($fields)) {
			// Check if any column(s) set label by colon(:) separator
			$recola = [];
			foreach ($fields as $icol => $cols) {
				if (diy_string_contained($cols, ':')) {
					$split_cname   = explode(':', $cols);
					$this->labels[$split_cname[0]] = $split_cname[1];
					$recola[$icol] = $split_cname[0];
				} else {
					$recola[$icol] = $cols;
				}
			}
			$fields         = $recola;
			$fieldset_added = $fields;
			
			if (!empty($fields)) {
				$fields = $this->check_column_exist($table_name, $fields, $this->connection);
			} elseif (!empty($this->variables['table_fields'])) {
				$fields = $this->check_column_exist($table_name, $this->variables['table_fields']);
			} else {
				$fields = diy_get_table_columns($table_name, $this->connection);
			}
			
			// RELATIONAL PROCESS
			$relations         = [];
			$field_relations   = [];
			$fieldset_changed  = [];
			if (!empty($this->relational_data)) {
				foreach ($this->relational_data as $relData) {
					if (!empty($relData['field_target'])) {
						foreach ($relData['field_target'] as $fr_name => $relation_fields) {
							$field_relations[$fr_name] = $relation_fields;
							if (in_array($fr_name, $fields)) {
								$fieldset_changed[$fr_name] = $fr_name;
							}
						}
					}
					if (!empty($relData['foreign_keys'])) $this->columns[$table_name]['foreign_keys'] = $relData['foreign_keys'];
				}
			}
			
			if (!empty($field_relations)) {
				$checkFieldSet = array_diff($fieldset_added, $fields);
				if (!empty($fieldset_changed)) {
					$fieldsetChanged = [];
					foreach ($fields as $fid => $fval) {
						if (!empty($fieldset_changed[$fval])) {
							$fieldsetChanged[$fid] = $fieldset_changed[$fval];
							unset($fields[$fid]);
						}
					}
					$checkFieldSet = array_merge_recursive_distinct($checkFieldSet, $fieldsetChanged);
				}
				
				if (!empty($checkFieldSet)) {
					foreach ($checkFieldSet as $index => $field_diff) {
						if (!empty($field_relations[$field_diff])) {
							$relational_data                                      = $field_relations[$field_diff];
							$this->labels[$relational_data['field_name']]         = $relational_data['field_label'];
							$relations[$index]                                    = $relational_data['field_name'];
							$this->columns[$table_name]['relations'][$field_diff] = $relational_data;
						}
					}
				}
				
				$refields = [];
				if (!empty($relations)) {
					foreach ($relations as $reid => $relation_name) {
						$refields = diy_array_insert($fields, $reid, $relation_name);
					}
				}
				if (!empty($refields)) $fields = $refields;
			}
		}
		
		$search_columns = false;
		if (!empty($this->search_columns)) {
			if ($this->all_columns === $this->search_columns) {
				$search_columns = $fields;
			} else {
				$search_columns = $this->search_columns;
			}
		}
		$this->search_columns = $search_columns;
		
		if (false === $actions) $actions       = [];
		$this->columns[$table_name]['lists']   = $fields;
		$this->columns[$table_name]['actions'] = $actions;
		
		if (!empty($this->variables['text_align']))         $this->columns[$table_name]['align']         = $this->variables['text_align'];
		if (!empty($this->variables['merged_columns']))     $this->columns[$table_name]['merge']         = $this->variables['merged_columns'];
		if (!empty($this->variables['orderby_column']))     $this->columns[$table_name]['orderby']       = $this->variables['orderby_column'];
		if (!empty($this->variables['clickable_columns']))  $this->columns[$table_name]['clickable']     = $this->variables['clickable_columns'];
		if (!empty($this->variables['sortable_columns']))   $this->columns[$table_name]['sortable']      = $this->variables['sortable_columns'];
		if (!empty($this->variables['searchable_columns'])) $this->columns[$table_name]['searchable']    = $this->variables['searchable_columns'];
		if (!empty($this->variables['filter_groups']))      $this->columns[$table_name]['filter_groups'] = $this->variables['filter_groups'];
		if (!empty($this->variables['format_data']))        $this->columns[$table_name]['format_data']   = $this->variables['format_data'];
		if (!empty($this->variables['hidden_columns'])) {
			$this->columns[$table_name]['hidden_columns'] =  $this->variables['hidden_columns'];
			$this->variables['hidden_columns']            =  [];
		}
		
		$this->tableID[$table_name] = diy_clean_strings("CoDIY_{$this->tableType}_" . $table_name . '_' . diy_random_strings(50, false));
		$attributes['table_id']     = $this->tableID[$table_name];
		$attributes['table_class']  = diy_clean_strings("CoDIY_{$this->tableType}_") . ' ' . $this->variables['table_class'];
		if (!empty($this->variables['background_color'])) $attributes['bg_color'] = $this->variables['background_color'];
		
		if (!empty($this->variables['on_load'])) {
			if (!empty($this->variables['on_load']['display_limit_rows'])) {
				$this->params[$table_name]['on_load']['display_limit_rows'] = $this->variables['on_load']['display_limit_rows'];
			}
		}
		
		if (!empty($this->variables['fixed_columns'])) $this->params[$table_name]['fixed_columns'] = $this->variables['fixed_columns'];
		
		$this->params[$table_name]['actions']                         = $actions;
		$this->params[$table_name]['buttons_removed']                 = $this->button_removed;
		$this->params[$table_name]['numbering']                       = $numbering;
		$this->params[$table_name]['attributes']                      = $attributes;
		$this->params[$table_name]['server_side']['status']           = $server_side;
		$this->params[$table_name]['server_side']['custom_url']       = $server_side_custom_url;
		if (!empty($this->variables['column_width'])) {
			$this->params[$table_name]['attributes']['column_width']  = $this->variables['column_width'];
		}
		
		if (!empty($this->variables['add_table_attributes'])) {
			$this->params[$table_name]['attributes']['add_attributes'] = $this->variables['add_table_attributes'];
		}
		
		if (!empty($this->conditions)) {
			$this->params[$table_name]['conditions']      = $this->conditions;
			if (!empty($this->conditions['formula'])) {
				$this->formula[$table_name]               = $this->conditions['formula'];
				unset($this->conditions['formula']);
				$this->conditions[$table_name]['formula'] = $this->formula[$table_name];
			}
			if (!empty($this->conditions['where'])) {
				$whereConds = [];//$this->conditions['where'];
				foreach ($this->conditions['where'] as $where_conds) {
					$whereConds[$where_conds['field_name']][$where_conds['operator']]['field_name'][$where_conds['field_name']] = $where_conds['field_name'];
					$whereConds[$where_conds['field_name']][$where_conds['operator']]['operator'][$where_conds['operator']]     = $where_conds['operator'];
					$whereConds[$where_conds['field_name']][$where_conds['operator']]['values'][]                               = $where_conds['value'];
				}
				$whereConditions = [];
				foreach ($whereConds as $whereFields => $whereFieldValues) {
					foreach ($whereFieldValues as $whereOperators => $whereOperatorValues) {
						foreach ($whereOperatorValues as $whereOperatorDataKey => $whereOperatorDataValues) {
							if ('values' === $whereOperatorDataKey) {
								if (is_array($whereOperatorDataValues)) {
									foreach ($whereOperatorDataValues as $whereOperatorDataValue) {
										if (is_array($whereOperatorDataValue)) {
											foreach ($whereOperatorDataValue as $_whereOperatorDataValue) {												
												$whereConditions[$whereFields][$whereOperators][$whereOperatorDataKey][$_whereOperatorDataValue] = $_whereOperatorDataValue;
											}
										} else {
											$whereConditions[$whereFields][$whereOperators][$whereOperatorDataKey][$whereOperatorDataValue] = $whereOperatorDataValue;
										}
									}
								}
							} else {
								$whereConditions[$whereFields][$whereOperators][$whereOperatorDataKey] = $whereOperatorDataValues;
							}
						}
						
					}
				}
				
				$whereConditionals = [];
				foreach ($whereConditions as $whereConditionsFieldName => $whereConditionsDataFields) {
					foreach ($whereConditionsDataFields as $whereOperatorsType => $whereConditionalData) {
						$whereConditionals[$whereConditionsFieldName][$whereOperatorsType]['field_name'] = $whereConditionsFieldName;
						$whereConditionals[$whereConditionsFieldName][$whereOperatorsType]['operator']   = $whereOperatorsType;
						$whereConditionals[$whereConditionsFieldName][$whereOperatorsType]['value']      = $whereConditionalData['values'];
					}
				}
				
				$whereDataConditions = [];
				foreach ($whereConditionals as $whereConditionalsFieldData) {
					foreach ($whereConditionalsFieldData as $whereConditionalsFieldSets) {
						$whereDataConditions[] = $whereConditionalsFieldSets;
					}
				}
				
				$this->conditions[$table_name]['where'] = $whereDataConditions;
			}
			
			if (!empty($this->conditions['columns'])) {
				$columnCond = $this->conditions['columns'];
				unset($this->conditions['columns']);
				$this->conditions[$table_name]['columns'] = $columnCond;
			}
		}
		
		if (!empty($this->filter_model)) $this->params[$table_name]['filter_model'] = $this->filter_model;
		
		$label = null;
		if (!empty($this->variables['table_name'])) $label = $this->variables['table_name'];
		
		if ('datatable' === $this->tableType) {
			$this->renderDatatable($table_name, $this->columns, $this->params, $label);
		} else {
			$this->renderGeneralTable($table_name, $this->columns, $this->params);
		}
	}
	
	private function renderDatatable($name, $columns = [], $attributes = [], $label = null) {
		if (!empty($this->variables['table_data_model'])) {
			$attributes[$name]['model'] = $this->variables['table_data_model'];
			asort($attributes[$name]);
		}
		
		$columns[$name]['filters'] = [];
		if (!empty($this->search_columns)) {
			$columns[$name]['filters'] = $this->search_columns;
		}
		
		$this->setMethod($this->method);
		
		if (!empty($this->labelTable)) {
			$label = $this->labelTable . ':setLabelTable';
			$this->labelTable = null;
		}
		
		$this->draw($this->tableID[$name], $this->table($name, $columns, $attributes, $label));
	}
	
	private function renderGeneralTable($name, $columns = [], $attributes = []) {
		dd($columns);
	}
}