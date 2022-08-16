<?php
namespace Incodiy\Codiy\Library\Components\Table\Craft;

use Incodiy\Codiy\Models\Admin\System\DynamicTables;

/**
 * Created on 21 Apr 2021
 * Time Created	: 08:13:39
 *
 * @filesource	Builder.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Builder {
	use Scripts;
	
	public $model;
	
	protected function table($name, $columns = [], $attributes = []) {
		$data = [];
		
		if (!empty($attributes[$name]['model'])) {
			if ('sql' === $attributes[$name]['model']) {
				$data[$name]['model']	= 'sql';
				$data[$name]['sql']		= $attributes[$name]['query'];
			} else {
				$model = new $attributes[$name]['model']();
				$data[$name]['model']	= $attributes[$name]['model'];
			}
		} else {
			$model = new DynamicTables();
			$model->setTable($name);
			$data[$name]['model']       = get_class($model);
			$attributes[$name]['model'] = get_class($model);
		}
		
		if (!empty($model)) {
			$this->model[$name]['type']   = 'model';
			$this->model[$name]['source'] = $model;
		} else {
			$this->model[$name]['type']   = 'sql';
			$this->model[$name]['source']	= $data[$name]['sql'];
		}
		
		if (!empty($attributes[$name])) {
			$tableID          = $attributes[$name]['attributes']['table_id'];
			$tableClass       = $attributes[$name]['attributes']['table_class'];
			$this->serverSide = $attributes[$name]['server_side']['status'];
			$this->customURL  = $attributes[$name]['server_side']['custom_url'];
		}
		
		$data[$name]['name']       = $name;
		$data[$name]['columns']    = $columns[$name];
		$data[$name]['attributes'] = $attributes[$name];
		
		// FORMULATION
		if (!empty($data[$name]['attributes']['conditions']['formula'])) {
			if (!empty($data[$name]['columns']['lists'])) {
				$data[$name]['columns']['lists'] = $this->setFormulaColumns($data[$name]['columns']['lists'], $data[$name]);
			}
		}
		
		// RENDER DATA TABLE
		if (false !== $name) {
			$titleText  = ucwords(str_replace('_', ' ', $name)) . ' List(s)';
			$tableTitle = '<div class="panel-heading"><div class="pull-left"><h3 class="panel-title">' . $titleText . '</h3></div><div class="clearfix"></div></div>';
		}
		/* 
		$foot  = '<tfoot><tr>';
		$foot .= '<th></th>';
		$foot .= '<th></th>';
		foreach ($data[$name]['columns']['lists'] as $columnLists) {
			$foot .= "<th>{$columnLists}</th>";
		}
		$foot .= '<th></th>';
		$foot .= '</tr></tfoot>';
		 */
		$table  = '<div class="panel-body no-padding">';
		$table .= '<table' . $this->setAttributes(['id' => $tableID, 'class' => $tableClass]) . '>';
		$table .= $this->header($data[$name]);
		$table .= null;
		//	$table .= $foot;
		$table .= '</table>';
		$table .= '</div>';
		// RENDER DATA TABLE
		
		$datatable_columns = $this->body($data[$name]);
		$html  = '<div class="row">';
		$html .= '<div class="col-md-12">';
		$html .= '<div class="panel">' . $tableTitle . '<br />';
		$html .= '<div class="relative diy-table-box-' . $tableID . '">';
		if (!empty($this->filter_contents[$tableID]['id']) && $tableID === $this->filter_contents[$tableID]['id']) {
			$html .= '<span class="diy-dt-search-box hide" id="diy-' . $tableID . '-search-box">' . $this->filterButton($this->filter_contents[$tableID]) . '</span>';
			$html .= $this->filterModalbox($this->filter_contents[$tableID]);
		}
		$html .= $table . $datatable_columns;
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	private function checkColumnLabel($check_labels, $columns) {
		$labels = [];
		foreach ($columns as $icol => $vcol) {
			if (!empty($this->labels[$vcol])) {
				$labels[$icol] = $this->labels[$vcol];
			} else {
				$labels[$icol] = $vcol;
			}
		}
		
		return $labels;
	}
	
	private function header($data = []) {
		$columns	= $data['columns'];
		$attributes = $data['attributes'];
		
		$sortable	= false;
		if (!empty($data['columns']['sortable'])) $sortable	= $data['columns']['sortable'];
		
		$actions	= false;
		$numbering	= false;
		
		// COLUMN DATA MANIPULATION
		$attributes['sortable_columns']					= $sortable;
		$attributes['attributes']['column']['id']		= [];
		$attributes['attributes']['column']['class']	= [];
		if (!empty($attributes)) {
			if (!empty($attributes['actions']))		$actions	= $attributes['actions'];
			if (!empty($attributes['numbering']))	$numbering	= $attributes['numbering'];
		}
		
		$alignColumn = [];
		if (!empty($columns['align'])) {
			foreach ($columns['align'] as $align => $column_data) {
				if (true === $column_data['header']) {
					foreach ($column_data['columns'] as $field) {
						$alignColumn['header'][$field] = $align;
					}
				}
			}
		}
		
		$mergeColumn = null;
		if (!empty($columns['merge'])) {
			// Manipulation Column Merged Label
			if (!empty($this->labels)) {
				$merged_labels = [];
				foreach ($columns['merge'] as $colmergename => $colmerged) {
					$merged_labels[$colmergename]['position']	= $colmerged['position'];
					$merged_labels[$colmergename]['counts']		= $colmerged['counts'];
					$merged_labels[$colmergename]['columns']	= $this->checkColumnLabel($this->labels, $colmerged['columns']);
				}
				if (!empty($merged_labels)) $columns['merge'] = $merged_labels;
			}
			
			$mergeColumn = $columns['merge'];
		}
		if (!empty($columns['lists'])) $columns	 	= $columns['lists'];
		if (true === $numbering) {
			$number  = ['number_lists'];
			$columns = array_merge($number, $columns);
		}
		
		if (!empty($actions)) {
			array_push($columns, 'action');
		}
		
		if (!empty($this->labels)) {
			$columns = $this->checkColumnLabel($this->labels, $columns);
		}
		// COLUMN DATA MANIPULATION
		
		// COLORING BACKGROUD
		$columnColor = [];
		$headerColor = null;
		$bgColor	 = null;
		if (!empty($attributes['attributes']['bg_color'])) $bgColor = $attributes['attributes']['bg_color'];
		$tableColor  = $this->backgroundColor($bgColor);
		if (!empty($tableColor['columns'])) $columnColor = $tableColor['columns'];
		if (!empty($tableColor['header']))  $headerColor = $tableColor['header'];
		// COLORING BACKGROUD
		
		// BUILD HTML TABLE
		if (!empty($mergeColumn)) {
			$headerTable = '<thead>';
		} else {
			$headerTable = '<thead><tr>';
		}
		
		if (!empty($columns)) {
			if (is_array($columns)) {
				if (!empty($mergeColumn)) {
					// If any set field(s) merged
					if (!empty($alignColumn['header'])) {
						$attributes['attributes']['column']['class'] = array_merge_recursive($attributes['attributes']['column']['class'], $alignColumn['header']);
					}
					$headerTable .= $this->mergeColumns($mergeColumn, $columns, $attributes);
				} else {
					// If no one set field(s) merged
					foreach ($columns as $column) {
						$class				= null;
						$classAttributes	= null;
						
						if (!empty($alignColumn['header'][$column])) {
							$classAttributes .= $alignColumn['header'][$column];//$this->setAttributes(['class' => $alignColumn['header'][$column]]);
						}
						
						if ('action' === strtolower($column)) {
							$classAttributes .= ' diy-column-action';
						}
						
						if (!empty($classAttributes)) {
							$class = $this->setAttributes(['class' => $classAttributes]);
						}
						
						$headerLabel = ucwords(str_replace('_', ' ', $column));
						if ('no' === strtolower($column) || 'id' === strtolower($column) || 'nik' === strtolower($column)) {
							$headerTable .= "<th width=\"50\"{$headerColor}>{$headerLabel}</th>";
						} elseif ('number_lists' === strtolower($column)) {
							$headerTable .= '<th width="30"' . $headerColor . '>No</th>';
							$headerTable .= '<th width="30"' . $headerColor . '>ID</th>';
						} else {							
							if (!empty($columnColor[$column])) {
								$headerTable .= "<th{$class}{$headerColor}{$columnColor[$column]}>{$headerLabel}</th>";
							} else {
								$headerTable .= "<th{$class}{$headerColor}>{$headerLabel}</th>";
							}
						}
					}
				}
			}
		}
		
		if (!empty($mergeColumn)) {
			$headerTable .= '</thead>';
		} else {
			$headerTable .= '</tr></thead>';
		}
		// BUILD HTML TABLE
		
		return $headerTable;
	}
	
	private function mergeColumns($mergeColumn = [], $columns = [], $attributes = []) {
		
		$headerTable  = null;
		$mergedTable  = null;
		$setMergeText = '::merge::';
		
		$columnColor = [];
		$headerColor = null;
		if (!empty($attributes['attributes']['bg_color'])) {
			$tableColor = $this->backgroundColor($attributes['attributes']['bg_color']);
		}
		if (!empty($tableColor['columns'])) $columnColor = $tableColor['columns'];
		if (!empty($tableColor['header']))  $headerColor = $tableColor['header'];
		
		if (!empty($mergeColumn)) {
			$mergedTable .= '<tr>';
			foreach ($columns as $index => $column) {
				$columnClass = null;
				$headerLabel = ucwords(str_replace('_', ' ', $column));
				
				if (!empty($mergeColumn)) {
					foreach ($mergeColumn as $mergeLabel => $mergeData) {
						foreach ($mergeData['columns'] as $merge_column) {
							if ($column === $merge_column) {
								if (!empty($attributes['attributes']['column']['class'][$merge_column])) {
									$columnClass  = $this->setAttributes(['class' => $attributes['attributes']['column']['class'][$merge_column]]);
									
									// coloring background
									if (!empty($columnColor[$column])) {
										$mergedTable .= "<th{$columnClass}{$headerColor}{$columnColor[$column]}>{$headerLabel}</th>";
									} else {
										$mergedTable .= "<th{$columnClass}{$headerColor}>{$headerLabel}</th>";
									}
								} else {
									if (!empty($columnColor[$column])) {
										$mergedTable .= "<th{$headerColor}{$columnColor[$column]}>{$headerLabel}</th>";
									} else {
										$mergedTable .= "<th{$headerColor}>{$headerLabel}</th>";
									}
								}
								
								unset($columns[$index]);
								$columns[$index] = $mergeLabel . $setMergeText . $mergeData['counts'];
							}
						}
					}
				}
			}
			$mergedTable .= '</tr>';
			
			$columns = array_unique($columns);
			ksort($columns);
			
			$headerTable .= '<tr>';
			foreach ($columns as $index => $column) {
				$columnClass = null;
				$headerLabel = ucwords(str_replace('_', ' ', str_replace($setMergeText, '', $column)));
				
				if (str_contains($column, $setMergeText)) {
					$merge_label  = explode($setMergeText, $column);
					$colspan	  = intval($merge_label[1]);
					$headerLabel  = ucwords(str_replace('_', ' ', $merge_label[0]));
					$headerTable .= "<th colspan=\"{$colspan}\"{$headerColor}>{$headerLabel}</th>";
				} else {
					if ('no' === strtolower($column) || 'id' === strtolower($column) || 'nik' === strtolower($column)) {
						$headerTable .= "<th rowspan=\"2\" width=\"50\"{$headerColor}>{$headerLabel}</th>";
					} elseif ('number_lists' === strtolower($column)) {
						$headerTable .= "<th rowspan=\"2\" width=\"30\"{$headerColor}>No</th><th rowspan=\"2\" width=\"30\"{$headerColor}>ID</th>";
					} else {
						$classAttributes = null;
						if (!empty($attributes['attributes']['column']['class'][$column])) {
							$classAttributes .= $attributes['attributes']['column']['class'][$column];
						}
						
						if ('action' === strtolower($column)) {
							$classAttributes .= ' diy-column-action';
						}
						
						if (!empty($classAttributes)) {
							$columnClass = $this->setAttributes(['class' => $classAttributes]);
						}
						
						if (!empty($columnColor[$column])) {
							$headerTable .= "<th rowspan=\"2\"{$columnClass}{$headerColor}{$columnColor[$column]}>{$headerLabel}</th>";
						} else {
							$headerTable .= "<th rowspan=\"2\"{$columnClass}{$headerColor}>{$headerLabel}</th>";
						}
					}
				}
			}
			$headerTable .= '</tr>';
			
			$headerTable .= $mergedTable;
		}
		
		return $headerTable;
	}
	
	private function setColumnElements($name, $column_data, $columns) {
		$element = [];
		if (!empty($column_data[$name])) {
			if (!empty($column_data[$name]['all::columns'])) {
				if (true === $column_data[$name]['all::columns']) {
					if (!empty($columns['columns']['lists'])) {
						foreach ($columns['columns']['lists'] as $clickList) {
							$element[$clickList] = true;
						}
					}
				}
			} else {
				foreach ($column_data[$name] as $clicKey) {
					$element[$clicKey] = true;
				}
			}
		}
		
		return $element;
	}
	
	private function setFormulaColumns($columns, $data) {
		return diy_set_formula_columns($columns, $data['attributes']['conditions']['formula']);
	}
	
	public $filter_contents		= [];
	protected $filter_object	= [];
	private function body($data = []) {
		$datatables  = [];
		$name		 = $data['name'];
		$attributes  = $data['attributes'];
		$columnData	 = $data['columns'];
		$server_side = $data['attributes']['server_side']['status'];
		
		$actions	 = false;
		$numbering	 = false;
		if (!empty($attributes['attributes']['table_id'])) {
			$tableID = $attributes['attributes']['table_id'];
		}
		if (!empty($attributes['actions']))		$actions	= $attributes['actions'];
		if (!empty($attributes['numbering']))	$numbering	= $attributes['numbering'];
		
		// COLUMN DATA MANIPULATION
		$columns	 = $columnData['lists'];
		if (true === $numbering) {
			$number  = ['number_lists'];
			$columns = array_merge($number, $columns);
		}
		if (!empty($actions)) array_push($columns, 'action');
		
		// ALIGNMENTS
		$alignment = [];
		if (!empty($columnData['align'])) {
			foreach ($columnData['align'] as $align => $col_data) {
				if (true === $col_data['body']) {
					foreach ($col_data['columns'] as $field) {
						$alignment['body'][$field] = $align;
					}
				}
			}
		}
		
		// SORTABLE
		$sortable	= $this->setColumnElements('sortable', $columnData, $data);
		// SEARCHABLE
		$searchable	= $this->setColumnElements('searchable', $columnData, $data);
		// CLICKABLE
		$clickable	= $this->setColumnElements('clickable', $columnData, $data);
		
		$dt_columns = [];
		$jsonData	= [];
		
		$column_id  = [];
		if (false !== $server_side) {
			$column_id['data'] = 'id';
			$column_id['name'] = 'id';
		}
		
		$formula_fields = [];
		if (!empty($data['attributes']['conditions']['formula'])) {
			foreach ($data['attributes']['conditions']['formula'] as $formula) {
				$formula_fields[$formula['name']] = $formula['name'];
			}
		}
		
		foreach ($columns as $column) {
			$jsonData['data']			= $column;
			$jsonData['name']			= $column;
			$jsonData['sortable']	= false;
			$jsonData['searchable']	= false;
			$jsonData['class']		= 'auto-cut-text';
			$jsonData['onclick']		= 'return false';
			
			$formula_column = null;
			if (!empty($formula_fields[$column])) {
				$formula_column = $formula_fields[$column];
			}
			
			if ('number_lists' === $column) {
				$columnName			= 'DT_RowIndex';
				
				$jsonData['data']	= $columnName;
				$jsonData['name']	= $columnName;
				$jsonData['class']	= 'center un-clickable';
				
				$dt_columns[]		= $jsonData;
				if (!empty($column_id))	{
					$dt_columns[]	= $column_id;
				}
				$jsonData			= [];
			} else if ($formula_column === $column) {
				$jsonData['data']	= $column;
				$jsonData['name']	= $column;
				
				if (!empty($alignment['body'][$column])) {
					$jsonData['class'] = $jsonData['class'] . " {$alignment['body'][$column]}";
				}
				
				if (!empty($clickable[$column]))  {
					unset($jsonData['onclick']);
					$jsonData['class'] = $jsonData['class'] . " clickable";
				}
				
				$dt_columns[] = $jsonData;
			} else {
				$jsonData['data']	= $column;
				$jsonData['name']	= $column;
				
				if (!empty($alignment['body'][$column])) {
					$jsonData['class'] = $jsonData['class'] . " {$alignment['body'][$column]}";
				}
				
				if (!empty($sortable[$column]))   $jsonData['sortable']   = $sortable[$column];
				if (!empty($searchable[$column])) $jsonData['searchable'] = $searchable[$column];
				if (!empty($clickable[$column]))  {
					unset($jsonData['onclick']);
					$jsonData['class'] = $jsonData['class'] . " clickable";
				}
				
				$dt_columns[] = $jsonData;
			}
		}
		
		$new_data_columns = [];
		foreach ($dt_columns as $dtcols) {
			if ('DT_RowIndex' === $dtcols['name']) {
				$new_data_columns[] = 'number_lists';
			} else {
				$new_data_columns[] = $dtcols['name'];
			}
		}
		
		$dt_info = [];
		$dt_info['searchable']	= [];
		$dt_info['name']			= $name;
		if (!empty($data['columns']['sortable'])) $dt_info['sortable'] = $data['columns']['sortable'];
		if (!empty($data['attributes']['conditions'])) {
			$dt_info['conditions']	= $data['attributes']['conditions'];
			$dt_info['columns']		= $new_data_columns;
		}
		
		$filter = false;
		if (!empty($searchable)) {
			$filter = true;
			$dt_info['searchable'] = $data['columns']['searchable'];
			if (!empty($data['columns']['filters'])) {
				
				$search_data					= [];
				$search_data['table_name']	= $name;
				$search_data['searchable']	= $data['columns']['searchable'];
				$search_data['columns']		= $data['columns']['filters'];
				
				if (!empty($data['columns']['filter_groups'])) {
					$search_data['filter_groups'] = $data['columns']['filter_groups'];
				}
				
				if (!empty($data['sql'])) {
					$data_model = null;
					$data_sql	= $data['sql'];
				} else {
					$data_model	= $data['model'];
					$data_sql	= null;
				}
				
				$search_object			= new Search($data_model, $search_data, $data_sql);
				$this->filter_object	= $search_object;
				
				$dt_info['id']			= $tableID;
				$dt_info['class']		= 'dt-button buttons-filter';
				$dt_info['attributes']	= [
					'id'				=> "{$tableID}_cdyFILTER",
					'class'				=> "modal fade {$tableID}",
					'role'				=> 'dialog',
					'tabindex'			=> '-1',
					'aria-hidden'		=> 'true',
					'aria-controls'		=> $tableID,
					'aria-labelledby'	=> $tableID,
					'data-backdrop'		=> 'static',
					'data-keyboard'		=> 'false'
				];
				$dt_info['button_label']	= '<i class="fa fa-filter"></i> Filter';
				$dt_info['modal_title']		= '<i class="fa fa-filter"></i> &nbsp; Filter';
				$dt_info['modal_content']	= $search_object->render($dt_info['name'], $data['columns']['filters']);
			}
		}
		$datatables[$name]['columns']		= $dt_columns;
		
		$this->filter_contents[$tableID]	= $dt_info;
		
		$filter_data = [];
		if (true === $filter) {
			$filter_data = $this->getFilterDataTables();
		}
		
		$dt_columns = diy_clear_json(json_encode($dt_columns));
		$datatable  = $this->datatables($tableID, $dt_columns, $dt_info, true, $filter_data);
		
		return $datatable;
	}
	
	private function getFilterDataTables() {
		$filter_strings = null;
		if (!empty($_GET['filters'])) {
			$input_filters	= [];
			$_ajax_url		= 'renderDataTables';
			foreach ($_GET as $name => $value) {
				if ('filters'!== $name && '' !== $value) {
					if (!is_array($value)) {
						if (
							$name !== $_ajax_url	&&
							$name !== 'draw'		&&
							$name !== 'columns'		&&
							$name !== 'order'		&&
							$name !== 'start'		&&
							$name !== 'length'		&&
							$name !== 'search'		&&
							$name !== '_token'		&&
							$name !== '_'
						) {
							$input_filters[] = "infil[{$name}]={$value}";
						}
					}
				}
			}
			$filter_strings = '&filters=true&' . implode('&', $input_filters);
		}
		
		return $filter_strings;
	}
	
	private function backgroundColor($attributes = []) {
		if (!empty($attributes)) {
			$tableDataColor = [];
			foreach ($attributes as $colorCode => $dataColor) {
				if (!empty($dataColor['text'])) $textColor = " color:{$dataColor['text']};";
				if (!empty($dataColor['columns'])) {
					foreach ($dataColor['columns'] as $columnName) {
						$tableDataColor['columns'][$columnName] = $this->setAttributes(['style' => "background-color:{$colorCode} !important;{$textColor}"]);
					}
				}
				
				if (empty($dataColor['columns'])) {
					if (true === $dataColor['header']) {
						$tableDataColor['header'] = $this->setAttributes(['style' => "background-color:{$colorCode} !important;{$textColor}"]);
					}
				}
			}
			
			return $tableDataColor;
		}
	}
	
	private function setAttributes($attributes = []) {
		$textAttribute = null;
		if (is_array($attributes)) {
			foreach ($attributes as $key => $value) {
				$textAttribute .= " {$key}=\"{$value}\"";
			}
		}
		
		return $textAttribute;
	}
}