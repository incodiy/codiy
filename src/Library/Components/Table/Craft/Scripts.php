<?php
namespace Incodiy\Codiy\Library\Components\Table\Craft;

use Incodiy\Codiy\Models\Admin\System\DynamicTables;

/**
 * Created on 22 May 2021
 * Time Created	: 00:29:19
 *
 * @filesource	Scripts.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
trait Scripts {
	
	/**
	 * Javascript Config for Rendering Datatables
	 *
	 * created @Oct 11, 2018
	 * author: wisnuwidi
	 *
	 * @param string $attr_id
	 * @param string $columns
	 * @param string $data_info
	 * @param boolean $server_side
	 * @param boolean $filters
	 * @param boolean|string|array $custom_link
	 *
	 * @return string
	 */
	protected function datatables($attr_id, $columns, $data_info = [], $server_side = false, $filters = false, $custom_link = false) {
		$varTableID		= explode('-', $attr_id);
		$varTableID		= implode('', $varTableID);
		$current_url	= url(diy_current_route()->uri);
		
		$buttonConfig	= 'exportOptions:{columns:":visible:not(:last-child)"}';
		$buttonset		= $this->setButtons($attr_id, [
			'excel|text:"<i class=\"fa fa-external-link\" aria-hidden=\"true\"></i> <u>E</u>xcel"|key:{key:"e",altKey:true}',
			'csv|'		. $buttonConfig,
			'pdf|'		. $buttonConfig,
			'copy|'		. $buttonConfig,
			'print|'	. $buttonConfig
		]);
				
		$initComplete	= null;
		$default_set	= '
			"searching":true,
			"processing":true,
			"retrieve":true,
			"paginate":true,
			"searchDelay":700,
			"bDeferRender":true,
			"responsive":false,
			"autoWidth":false,
			"dom":"lBfrtip",
			lengthMenu: [
				[10, 25, 50, 100, 250, 500, 1000, 9999999999],
				["10", "25", "50", "100", "250", "500", "1000", "Show All"]
			],
			"buttons":' . $buttonset . ',
			"initComplete": function() {' . $initComplete . '},
		';
		$responsive		= "rowReorder: {selector:'td:nth-child(2)'},responsive: false,";
		$js_conditional	= null;
		if (!empty($data_info['conditions']['columns'])) {
			$js_conditional = $this->conditionalColumns($data_info['conditions']['columns'], $data_info['columns']);
		}
		
		$filter_button	= false;
		$filter_js		= false;
		$js				= '<script type="text/javascript">jQuery(function($) {';
		if (false !== $server_side) {
			$diftaURI	= "&difta[name]={$data_info['name']}&difta[source]=dynamics";
			$link_url	= "renderDataTables=true{$diftaURI}";
			
			if (false !== $custom_link) {
				if (is_array($custom_link)) {
					$link_url = "{$custom_link[0]}={$custom_link[1]}";
				} else {
					$link_url = "{$custom_link}=true";
				}
			}
			
			$scriptURI		= "{$current_url}?{$link_url}";
			
			$colDefs		= ",columnDefs:[{targets:[1],visible:true,searchable:false,className:'control hidden-column'}";
			$orderColumn	= ",order:[[1, 'asc']]{$colDefs}]";
			$columns		= ",columns:{$columns}{$orderColumn}";
			$url_path		= url(diy_current_route()->uri);
			$hash			= hash_code_id();
			$clickAction	= ".on('click','td.clickable',function(){var getRLP=$(this).parent('tr').attr('rlp');if(getRLP != false) {var _rlp=parseInt(getRLP.replace('{$hash}','')-8*800/80);window.location='{$url_path}/'+_rlp+'/edit'}});";
			$initComplete	= ',' . $this->initComplete($attr_id, false);
			if (false !== $filters) {
				if (is_array($filters) && empty($filters)) {
					$filters = null;
				}
				$filter_button	= "$('div#{$attr_id}_wrapper>.dt-buttons').append('<span class=\"cody_{$attr_id}_diy-dt-filter-box\"></span>')";
				$filter_js		= $this->filter($attr_id, $scriptURI);
			}
			
			$documentLoad = "
				$(document).ready(function() {
					$('#{$attr_id}').wrap('<div class=\"diy-wrapper-table\"></div>');
					{$filter_js}
				});
			";
			
			$js .= "cody_{$varTableID}_dt = $('#{$attr_id}').DataTable({ {$responsive} {$default_set} 'serverSide':true, ajax:'{$scriptURI}{$filters}'{$columns}{$initComplete}{$js_conditional} }){$clickAction}{$filter_button}";
		} else {
			$js .= "cody_{$varTableID}_dt = $('#{$attr_id}').DataTable({{$default_set}columns:{$columns}});";
		}
		$js .= '});' . $documentLoad . '</script>';
		
		return $js;
	}
	
	private function getJsContainMatch($data, $match_contained = null) {
		if ('!=' === $match_contained || '!==' === $match_contained) {
			$match = false;
		}
		
		if ('==' === $match_contained || '===' === $match_contained) {
			$match = true;
		}
		
		if (true  == $match) return ":contains(\"{$data}\")";
		if (false == $match) return ":not(:contains(\"{$data}\"))";		
	}
	
	private function conditionalColumns($data, $columns) {
		$icols = [];
		foreach ($columns as $i => $v) {
			$icols[$v] = $i;
		}
		
		foreach ($data as $idx => $_data) {
			$data[$idx]['node'] = $icols[$_data['field_name']];
		}
		
		$js = null;
		if (!empty($data)) {
			$js .= ", 'createdRow': function(row, data, dataIndex, cells) {";
			foreach ($data as $condition) {
				$logic = $condition['logic_operator'];
				$js .= "\n";
				$js .= "if (data.{$condition['field_name']} {$logic} '{$condition['value']}') {";
				
				if ('row' === $condition['field_target']) {
					$js .= "$(row).children('td').css({'{$condition['rule']}': '{$condition['action']}'});";
				}
				
				if ('cell' === $condition['field_target']) {
				//	$td_rule = $this->getJsContainMatch($condition['value'], $logic);$js .= "$(row).children('td{$td_rule}').css({'{$condition['rule']}': '{$condition['action']}'});";
					if ('prefix' !== $condition['rule'] && 'suffix' !== $condition['rule']) {
						$js .= "$(cells[\"{$condition['node']}\"]).css({'{$condition['rule']}': '{$condition['action']}'});";
					}
					if ('prefix' === $condition['rule']) $js .= "$(cells[\"{$condition['node']}\"]).text(\"{$condition['action']}\" + data.{$condition['field_name']});";
					if ('suffix' === $condition['rule']) $js .= "$(cells[\"{$condition['node']}\"]).text(data.{$condition['field_name']} + \"{$condition['action']}\");";
					if ('replace' === $condition['rule']) {
						$js .= "$(cells[\"{$condition['node']}\"]).text('{$condition['action']}');";
					}
				}
				
				if ('column' === $condition['field_target']) {
					
				}
				
				$js .= "}";
			}
			$js .= "}";
		}
		
		return $js;
	}
	
	protected function filterButton(array $data) {
		if (!empty($data['searchable'])) {
			if (!empty($data['searchable']['all::columns'])) {
				if (false === $data['searchable']['all::columns']) {
					return false;
				}
			}
			
			if (false !== $data['searchable'] && !empty($data['class'])) {
				$btn_class = $data['class'];
				if (empty($data['class'])) $btn_class = 'btn btn-primary btn-flat btn-lg mt-3';
				
				return '<button type="button" class="' . $btn_class . ' ' . $data['id'] . '" data-toggle="modal" data-target=".' . $data['id'] . '">' . $data['button_label'] . '</button>';
			}
		}
		
		return false;
	}
	
	protected function filterModalbox(array $data) {
		$current_url = url(diy_current_route()->uri);
		if (!empty($data['searchable'])) {
			if (!empty($data['searchable']['all::columns'])) {
				if (false === $data['searchable']['all::columns']) {
					return false;
				}
			}
			
			if (!empty($data['modal_content']['html'])) {
				$attributes = '';
				if (!empty($data['attributes'])) {
					foreach ($data['attributes'] as $key => $attr) {
						$attributes .= " {$key}=\"{$attr}\"";
					}
				}
				
				$title = null;
				if (!empty($data['modal_title'])) $title = $data['modal_title'];
				$name = null;
				if (!empty($data['modal_content']['name'])) $name = $data['modal_content']['name'];
				$content = null;
				if (!empty($data['modal_content']['html'])) $content = $data['modal_content']['html'];
				
				$html  = '<div ' . $attributes . '>';
					$html .= '<div id="' . $data['id'] . '_cdyFILTERFormBox" class="modal-dialog modal-lg" role="document">';
						$html .= '<form action="' . $current_url . '?renderDataTables=true&filters=true" method="GET" id="' . $data['id'] . '_cdyFILTERForm" role="form">';
							$html .= '<div class="modal-content">';
								$html .= '<div id="' . $data['id'] . '_cdyProcessing" class="dataTables_processing" style="display:none"></div>';
								$html .= '<div class="modal-header">';
									$html .= '<h5 class="modal-title" id="">' . $title . ' Data ' . $name . '</h5>';
									$html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
										$html .= '<span aria-hidden="true">&times;</span>';
									$html .= '</button>';
								$html .= '</div>';
								$html .= '<input type="hidden" name="_token" value="' . csrf_token() . '" />';
								$html .= $content;
							$html .= '</div>';
						$html .= '</form>';
					$html .= '</div>';
				$html .= '</div>';
				
				return $html;
			}
		}
		
	}
	
	private function filter($id, $url) {
		$varTableID	= explode('-', $id);
		$varTableID	= implode('', $varTableID);
		
		$js = "
			$('#diy-{$id}-search-box').appendTo('.cody_{$id}_diy-dt-filter-box');
			$('.diy-dt-search-box').removeClass('hide');
			$('#{$id}_cdyFILTERForm').on('submit', function(event) {
				$('#{$id}_cdyProcessing').hide();
				event.preventDefault();
				var form = $(this);
				$.ajax ({
					type: 'GET',
					data: form.serialize(),
					url: '{$url}&filters=true',
					beforeSend: function() {
						$('#{$id}_cdyProcessing').show();
					},
					success: function(data) {
						var {$varTableID}_inputData = data.input;
						var {$varTableID}_filterURI = [];
						$.each({$varTableID}_inputData, function(index, value) {
							if (
								index != 'renderDataTables' &&
								index != 'difta' &&
								index != 'filters' &&
								index != '_token' &&
								null  != value &&
								'____-__-__ __:__:__' != value
							) {
								if ('string' === typeof(value)) {
									{$varTableID}_filterURI.push(index + '=' + value);
								} else if ('object' === typeof(value)) {
									$.each(value, function(idx, _val) {
										{$varTableID}_filterURI.push(index + '[' + idx + ']' + '=' + _val);
									});
								}
							}
						});
						var {$varTableID}_filterURL = '{$url}&' + {$varTableID}_filterURI.join('&') + '&filters=true';
						cody_{$varTableID}_dt.ajax.url({$varTableID}_filterURL).load();
					},
					complete: function() {
						$('#{$id}_cdyProcessing').hide();
						$('#{$id}_cdyFILTER').modal('hide');
					}
				});
			});
		";
								
		return $js;
	}
	
	private function initComplete($id, $location = 'footer') {
		if (false === $location) {
			$js = "initComplete: function() {document.getElementById('{$id}').deleteTFoot();}";
		} else {
			if (true === $location) {
				$location = 'footer';
			}
			
			$js = "
				initComplete: function() {
					this.api().columns().every(function(n) {
						if (n > 1) {
			                var column = this;
			                var input  = document.createElement(\"input\");
			                $(input)
								.attr({
									'class':'form-control',
									'placeholder': 'search'
								})
								.appendTo($(column.{$location}()).empty()).on('change', function () {
				                    column.search($(this).val(), false, false, true).draw();
				                });
						}
		            });
		        }
			";
		}
		
		return $js;
	}

	/** 
	$buttonset = '[
		{
			extend:"collection",
			exportOptions:{columns:":visible:not(:last-child)"},
			text:"<i class=\"fa fa-external-link\" aria-hidden=\"true\"></i> <u>E</u>xport",
			buttons:[{text:"Excel",buttons:"excel"}, "csv", "pdf"],
			key:{key:"e",altKey:true}
		},
		"copy",
		"print"
	]';
	 */
	private function setButtons($id, $button_sets = []) {
		$buttons = [];
		foreach ($button_sets as $button) {
			
			$button = trim($button);
			$option = null;
			$options[$button] = [];
			
			if (diy_string_contained($button, '|')) {
				$splits = explode('|', $button);
				foreach ($splits as $split) {
					if (diy_string_contained($split, ':')) {
						$options[$button][] = $split;
					} else {
						$button = $split;
					}
				}
			}
			
			if (!empty($options[$button])) $option = implode(',', $options[$button]) . ',';
			$buttons[] = '{extend:"' . $button . '", ' . $option . '}';
		}
		
		return '[' . implode(',', $buttons) . ']';
	}
}