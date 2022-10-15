<?php
namespace Incodiy\Codiy\Library\Components\Chart\Models\Line\Basic;

/**
 * Created on Oct 11, 2022
 * 
 * Time Created : 10:12:27 AM
 *
 * @filesource	LineBasic.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */
trait LineBasic {
	use Script;
	
	/**
	 * Build Line Chart
	 * 
	 * @param string $source
	 * @param array $fieldsets
	 * @param string $format
	 * @param string $order
	 */
	public function line($source, $fieldsets = [], $format, $category = null, $order = null, $group = null) {
		$this->setParams(__FUNCTION__, $source, $fieldsets, $format, $category, $order, $group);
		$this->dataProcessing();
	}
	
	private $modeling;
	private function dataProcessing() {
		$params = [];
		if (!empty($this->params)) {
			foreach ($this->params as $chartType => $chartData) {
				
				foreach ($chartData as $sourceName => $sourceData) {
					$sourceGroup = [];
					if (!empty($sourceData['group'])) {
						if (diy_string_contained($sourceData['group'], ',')) {
							$sourceGroup = explode(',', str_replace(' ', '', $sourceData['group']));
						} else {
							$sourceGroup = [$sourceData['group']];
						}
					}
					if (!empty($sourceData['order'])) {
						if (diy_string_contained($sourceData['order'], ',')) {
							$dataOrders  = explode(',', str_replace(' ', '', $sourceData['order']));
						} else {
							$dataOrders  = [$sourceData['order']];
						}
						
						foreach ($dataOrders as $orderData) {
							if (diy_string_contained($orderData, '::')) {
								$splitOrder = explode('::', $orderData);dd($splitOrder);
								foreach ($splitOrder as $orderField => $orderParam) {
									$sourceOrder[] = "`{$orderField}` ";
								}
							}
						}
						dd($sourceOrder);
						dd($sourceOrder);
					}
					
					if (!empty($sourceData['format'])) {
						
						$formatData                       = [];
						$formatData['param_as']           = [];
						$formatData['calculation_format'] = [];
						
						$sQueryData                       = [];
						$sQueryData['fields']             = [];
						$sQueryData['group']              = [];
						$sQueryData['un_group']           = [];
						$sQueryData['order']              = [];
						$sQueryData['data']               = [];
						
						$data_format                      = explode('|', $sourceData['format']);
						
						foreach ($data_format as $format_info) {
							if (!empty($format_info)) {
								if (diy_string_contained($format_info, '::')) {
									$slices = explode('::', $format_info);
									if (!empty($slices)) {
										foreach ($slices as $as_calc_format) {
											if (diy_string_contained($as_calc_format, ':')) {
												$slice = explode(':', $as_calc_format);
												
												$formatData['param_as'][$slice[1]] = $slice[0];
												$sQueryData['un_group'][$slice[1]] = $slice[1];
											} else {
												$formatData['calculation_format']  = $as_calc_format;
											}
										}
										
										$slicesData = explode(':', $slices[0]);
										$sQueryData['fields'][$slicesData[1]] = "{$slices[1]}({$slicesData[1]}) AS `{$slicesData[1]}`";
									}
								} else {
									
									$slice = explode(':', $format_info);
									$formatData['param_as'][$slice[1]] = $slice[0];
									$sQueryData['fields'][$slice[1]]   = $slice[1];
								}
							}
						}
						
						$fieldsets = [];
						foreach ($sourceData['fieldsets'] as $fieldset) {
							if (empty($sQueryData['fields'][$fieldset])) {
								$fieldsets[$fieldset] = "`{$fieldset}`";
							} else {
								$fieldsets[$fieldset] = $sQueryData['fields'][$fieldset];
							}
							
							if (!empty($sourceGroup)) {
								foreach ($sourceGroup as $group) {
									if (!empty($fieldsets[$group])) {
										$sQueryData['group'][$group] = $fieldsets[$group];
									} else {
										$sQueryData['group'][$group] = "`{$group}`";
									}
								}
							}
							
							if (!empty($sourceOrder)) {
								foreach ($sourceOrder as $order) {
									if (!empty($fieldsets[$order])) {
										$sQueryData['order'][$order] = $fieldsets[$order];
									} else {
										$sQueryData['order'][$order] = "`{$group}`";
									}
								}
							}
							
							$sQueryData['order'][$fieldset] = "{$fieldset} DESC";
							if (!empty($sQueryData['un_group'][$fieldset])) {
								unset($sQueryData['group'][$fieldset]);
								unset($sQueryData['order'][$fieldset]);
							}
						}
						dd($sourceOrder, $sQueryData['order']);
						$str_field = implode(', ', $fieldsets);
						$str_group = '';
						if (!empty($sourceGroup)) {
							$str_group = ' GROUP BY ' . implode(', ', $sQueryData['group']);
						}
						$str_order = ' ORDER BY ' . implode(', ', $sQueryData['order']) . ", {$sourceData['order']}";
						
						$queryData = diy_query("SELECT {$str_field} FROM {$sourceName}{$str_group}{$str_order};", 'SELECT');
						$sQueryData['data'] = $this->dataManipulations($queryData);
						
						unset($this->params[$chartType][$sourceName]);
						
						$params[$chartType][$sourceName]['fieldsets'] = $fieldsets;
						$params[$chartType][$sourceName]['group']     = $sQueryData['group'];
						$params[$chartType][$sourceName]['order']     = $sourceData['order'];
						$params[$chartType][$sourceName]['data']      = $sQueryData['data'];
						$params[$chartType][$sourceName]['format']    = $formatData;
						
						$this->params[$chartType][$sourceName]        = $params[$chartType][$sourceName];
					}
				}
			}
		}
		dd($this->params);
	}
	
	private function dataManipulations($source) {
		foreach ($source as $data) {
			dump($data);
		}
		dd($source);
	}
	
	public function linex($data, $title = null, $attributes = []) {
		$this->getTitle(__FUNCTION__, $title);
		
		$identity = $this->identities[__FUNCTION__][$this->lineTitle];
		$_attr    = [];
		if (!empty($attributes)) {
			foreach ($attributes as $attr_name => $attr_value) {
				$_attr[] = "{$attr_name}=\"{$attr_value}\"";
			}
			$attributes = [];
		}
		$attributes    = ' ' . implode(' ', $_attr);
		
		$this->elements[$identity] = '<div id="' . $identity . '"' . $attributes . '></div>';
		$this->line_script($this->lineTitle, $identity, $data);
		
		$this->draw($this->elements);
	}
}