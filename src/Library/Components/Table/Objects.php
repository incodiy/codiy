<?php
namespace Incodiy\Codiy\Library\Components\Table;

use Incodiy\Codiy\Library\Components\Table\Craft\Builder;
use Incodiy\Codiy\Library\Components\Form\Elements\Tab;

/**
 * Created on 12 Apr 2021
 * Time Created	: 19:24:03
 * 
 * Marhaban Ya RAMADHAN
 *
 * @filesource	Objects.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Objects extends Builder {
	use Tab;
	
	public $elements      = [];
	public $element_name  = [];
	public $records       = [];
	public $columns       = [];
	public $labels        = [];
	
	private $params       = [];
	private $setDatatable = true;
	private $tableType    = 'datatable';
	
	/**
	 * --[openTabHTMLForm]--
	 */
	private $opentabHTML	= '--[openTabHTMLForm]--';
	
	public function __construct() {
		$this->element_name['table']    = $this->tableType;
		$this->variables['table_class'] = 'table animated fadeIn table-striped table-default table-bordered table-hover dataTable repeater display responsive nowrap';
	}
	
	public $filter_scripts = [];
	private function draw($initial, $data = []) {
		if ($data) {
			$this->elements[$initial] = $data;
			if (!empty($this->filter_object->add_scripts)) {
				$this->filter_scripts = $this->filter_object->add_scripts;
			}
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
		if (true !== $this->setDatatable) {
			$this->tableType = 'self::table';
		}
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
	
	public function orderby($column, $order = 'asc') {
		$this->variables['orderby_column'] = ['column' => $column, 'order' => $order];
	}
	
	/**
	 * Set Sortable Column(s)
	 * 
	 * @param string|array $columns
	 */
	public function sortable($columns = null) {
		$this->variables['sortable_columns'] = $this->checkColumnSet($columns);
	}
	
	/**
	 * Set Clickable Column(s)
	 * 
	 * @param string|array $columns
	 */
	public function clickable($columns = null) {
		$this->variables['clickable_columns'] = $this->checkColumnSet($columns);
	}
	
	public $search_columns = false;
	/**
	 * Set Seachable Column(s)
	 * 
	 * @param string|array $columns
	 */
	public function searchable($columns = null) {
		$this->variables['searchable_columns'] = $this->checkColumnSet($columns);
		if (empty($columns)) {
			if (false === $columns) {
				$filter_columns = false;
			} else {
				$filter_columns = $this->all_columns;
			}
		} else {
			$filter_columns = $columns;
		}
		
		$this->search_columns = $filter_columns;
	}
	
	public $filter_groups = [];
	/**
	 * Set Searching Data Filter
	 * 
	 * @param string $column
	 * 		: field name target
	 * @param string $type
	 * 		: inputbox, selectbox, checkbox, radiobox, datebox, daterangebox
	 * @param boolean|string|array $relate
	 * 		: if false = no relational Data
	 * 		: if true = relational data set to all others columns/fieldname members
	 * 		: if (string)fieldname / other coulumn = relate to just one that column target was setted
	 * 		: if (array)fieldnames / others any columns = relate to any that column target was setted
	 */
	public function filterGroups($column, $type, $relate = false) {
		$filters = [];
		$filters['column'] = $column;
		$filters['type']   = $type;
		$filters['relate'] = $relate;
		
		$this->variables['filter_groups'][] = $filters;
	}
	
	private function check_column_exist($table_name, $fields) {
		$fieldset = [];
		foreach ($fields as $field) {
			if (diy_check_table_columns($table_name, $field)) {
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
	
	private $variables = [];
	private function clear_all_variables() {
		$this->variables['merged_columns']     = [];
		$this->variables['text_align']         = [];
		$this->variables['background_color']   = [];
		$this->variables['attributes']         = [];
		$this->variables['orderby_column']     = [];
		$this->variables['sortable_columns']   = [];
		$this->variables['clickable_columns']  = [];
		$this->variables['searchable_columns'] = [];
		$this->variables['filter_groups']      = [];
	}
	
	public $conditions = [];
	public function where($field_name, $logic_operator = false, $value = false) {
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
		$this->labels[$name] = $label;
		$this->conditions['formula'][]	= [
			'name'          => $name,
			'label'         => $label,
			'field_lists'   => $field_lists,
			'logic'         => $logic,
			'node_location' => $node_location,
			'node_after'    => $node_after_node_location
		];
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
	
	public $tableName	= [];
	private $tableID	= [];
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
					$split_cname = explode(':', $cols);
					$this->labels[$split_cname[0]] = $split_cname[1];
					$recola[$icol] = $split_cname[0];
				} else {
					$recola[$icol] = $cols;
				}
			}
			$fields = $recola;
			
			if (!empty($fields)) {
				$fields = $this->check_column_exist($table_name, $fields);
			} elseif (!empty($this->variables['table_fields'])) {
				$fields = $this->check_column_exist($table_name, $this->variables['table_fields']);
			} else {
				$fields = diy_get_table_columns($table_name);
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
		
		$this->columns[$table_name]['lists']	= $fields;
		$this->columns[$table_name]['actions']	= $actions;
		
		if (!empty($this->variables['text_align']))         $this->columns[$table_name]['align']         = $this->variables['text_align'];
		if (!empty($this->variables['merged_columns']))     $this->columns[$table_name]['merge']         = $this->variables['merged_columns'];
		if (!empty($this->variables['orderby_column']))     $this->columns[$table_name]['orderby']       = $this->variables['orderby_column'];
		if (!empty($this->variables['clickable_columns']))  $this->columns[$table_name]['clickable']     = $this->variables['clickable_columns'];
		if (!empty($this->variables['sortable_columns']))   $this->columns[$table_name]['sortable']      = $this->variables['sortable_columns'];
		if (!empty($this->variables['searchable_columns'])) $this->columns[$table_name]['searchable']    = $this->variables['searchable_columns'];
		if (!empty($this->variables['filter_groups']))      $this->columns[$table_name]['filter_groups'] = $this->variables['filter_groups'];
		
		$this->tableID[$table_name] = diy_clean_strings("CoDIY_{$this->tableType}_" . $table_name . '_' . diy_random_strings(50, false));
		$attributes['table_id']     = $this->tableID[$table_name];
		$attributes['table_class']  = diy_clean_strings("CoDIY_{$this->tableType}_") . ' ' . $this->variables['table_class'];
		if (!empty($this->variables['background_color'])) $attributes['bg_color'] = $this->variables['background_color'];
		
		$this->params[$table_name]['actions']                   = $actions;
		$this->params[$table_name]['buttons_removed']           = $this->button_removed;
		$this->params[$table_name]['numbering']                 = $numbering;
		$this->params[$table_name]['attributes']                = $attributes;
		$this->params[$table_name]['server_side']['status']     = $server_side;
		$this->params[$table_name]['server_side']['custom_url'] = $server_side_custom_url;
		
		if (!empty($this->conditions)) {
			$conditions       = $this->conditions;
			$this->conditions = [];
			if (!empty($conditions['formula'])) {
				$this->formula[$table_name]           = $conditions['formula'];
			}
			$this->params[$table_name]['conditions'] = $conditions;
			$this->conditions[$table_name]           = $this->params[$table_name]['conditions'];
		}
		
		if ('datatable' === $this->tableType) {
			$this->renderDatatable($table_name, $this->columns, $this->params);
		} else {
			$this->renderGeneralTable($table_name, $this->columns, $this->params);
		}
	}
	
	private function renderDatatable($name, $columns = [], $attributes = []) {
		if (!empty($this->variables['table_data_model'])) {
			$attributes[$name]['model'] = $this->variables['table_data_model'];
			asort($attributes[$name]);
		}
		
		$columns[$name]['filters'] = [];
		if (!empty($this->search_columns)) {
			$columns[$name]['filters'] = $this->search_columns;
		}
		
		return $this->draw($this->tableID[$name], $this->table($name, $columns, $attributes));
	}
	
	private function renderGeneralTable($name, $columns = [], $attributes = []) {
		dd($columns);
	}
}