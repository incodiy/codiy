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
	 * 	: table source name
	 * @param array  $fieldsets
	 * 	: [fieldname1, fieldname2, fieldname3]
	 * @param string $format
	 * 	: name:fieldname|data:fieldname::[sum|count|avg,-etc]
	 * @param string $category
	 * 	: fieldname used for chart category
	 * @param string $order
	 * 	: fieldname::[DESC|ASC] order
	 * @param string $group
	 * 	: fieldname group
	 */
	public function line($source, $fieldsets = [], $format, $category = null, $order = null, $group = null) {
		$this->setParams(__FUNCTION__, $source, $fieldsets, $format, $category, $order, $group);
		$this->craft();
	}
	
	private function craft() {
		if (!empty($this->params)) {
			foreach ($this->params as $chartType => $chartData) {
				foreach ($chartData as $identifier => $sourceConstruct) {
					$sourceData = $sourceConstruct['construct'];
					
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
								$splitOrder = explode('::', $orderData);
								$sourceOrder[$splitOrder[0]] = "`{$splitOrder[0]}` {$splitOrder[1]}";
							}
						}
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
							
							if (!empty($sQueryData['un_group'][$fieldset])) unset($sQueryData['group'][$fieldset]);
							
							if (!empty($sourceOrder)) {
								foreach ($sourceOrder as $field_order => $order) {
									if (!diy_string_contained($order, '`')) {
										$str_order = "`{$order}`";
									} else {
										$str_order = $order;
									}
									
									$sQueryData['order'][$field_order] = $str_order;
								}
							}
						}
						
						$str_field = implode(', ', $fieldsets);
						$str_group = '';
						$str_order = '';
						
						if (!empty($sourceGroup)) $str_group = ' GROUP BY ' . implode(', ', $sQueryData['group']);
						if (!empty($sourceOrder)) $str_order = ' ORDER BY ' . implode(', ', $sQueryData['order']);
						
						// DATA LINE HERE
						$queryData          = diy_query("SELECT {$str_field} FROM {$sourceData['source']}{$str_group}{$str_order};", 'SELECT');
						$sQueryData['data'] = self::manipulate($queryData, $formatData['param_as'], $sourceData['category']);
						
						$buffers            = [];
						$buffers['data']    = array_merge_recursive($sQueryData['data'], $this->params[$chartType][$identifier]['attributes']);
						
						$this->addParams($chartType, $identifier, 'buffers', $buffers);
						$this->build($identifier, $buffers);
					}
				}
			}
		}
	}
		
	private static function manipulate($source, $parameters, $category) {
		$paramCharts = [];
		foreach ($parameters as $param_field => $param_chart) {
			$paramCharts[$param_chart] = $param_field;
		}
		
		$chartData             = [];
		$chartData['data']     = [];
		$chartData['category'] = [];
		
		foreach ($source as $data) {
			if (!empty($data->{$paramCharts['name']})) {				
				if (!empty($data->{$paramCharts['data']})) {
					$chartData['data'][$data->{$paramCharts['name']}][] = $data->{$paramCharts['data']};
				} else {
					$chartData['data'][$data->{$paramCharts['name']}][null] = null;
				}
			}
			
			if (!empty($data->{$category})) $chartData['category'][$data->{$category}] = $data->{$category};
		}
		
		$buffers             = [];
		$buffers['series']   = [];
		$buffers['category'] = [];
		
		foreach ($chartData['category'] as $category) {
			$buffers['category'][] = $category;
		}
		
		foreach ($chartData['data'] as $name => $data) {
			$buffers['series'][] = [
				'name' => $name,
				'data' => $data
			];
		}
		
		$resultData             = [];
		$resultData['category'] = $buffers['category'];
		$resultData['series']   = $buffers['series'];
		
		return $resultData;
	}
	
	private function build($identity, $data) {
		$canvas = [];
		if (!empty($data['data']['canvas'])) $canvas = $data['data']['canvas'];
		
		$attributes = [];
		if (!empty($canvas)) {
			foreach ($canvas as $attr_name => $attr_value) {
				$attributes[] = "{$attr_name}=\"{$attr_value}\"";
			}
		}
		$attributes = ' ' . implode(' ', $attributes);
		
		$this->elements[$identity] = '<div id="' . $identity . '"' . $attributes . '></div>';
		$this->line_script($identity, $data);
		
		$this->draw($this->elements);
	}
}