<?php
namespace Incodiy\Codiy\Library\Components\Chart\Models\Line;

/**
 * Created on Oct 11, 2022
 * 
 * Time Created : 10:12:56 AM
 *
 * @filesource	Script.php
 *
 * @author     wisnuwidi@incodiy.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@incodiy.com
 */
trait Script {
	/* 
	private static function drawJSON($name, $chartData, $setLabel = null) {
		$label = $name;
		if (!empty($setLabel)) $label = $setLabel;
		
		return "{$label}:" . json_encode($chartData[$name]) . ',';
	}
	
	private static function scriptChart($type = 'line', $identity, $title, $subtitle, $xAxis, $yAxis, $tooltips, $legends, $series) {
		return diy_script_chart($type, $identity, $title, $subtitle, $xAxis, $yAxis, $tooltips, $legends, $series);
	}
	
	private static function axisData($position = 'x', $data = []) {
		$axisPos      = "{$position}Axis";
		$axisCategory = false;
		$axis         = null;
		
		if (!empty($data[$axisPos])) {
			if (!empty($data[$axisPos]['category'])) {
				$axisCategory = true;
				unset($data[$axisPos]['category']);
				$data[$axisPos]['categories'] = $data['category'];
			}
			
			$axis = static::drawJSON($axisPos, $data);
		}
		
		return ['data' => $axis, 'category' => $axisCategory];
	}
	 */
	private function line_script($identity, $data = []) {
		$chartData = $data['data'];
				
		$series = null;
		if (!empty($chartData['series'])) {
			$series = static::drawJSON('series', $chartData);
		}
		
		$title = null;
		if (!empty($chartData['title'])) {
			$title = static::drawJSON('title', $chartData);
		}
		
		$subtitle = null;
		if (!empty($chartData['subtitle'])) {
			$subtitle = static::drawJSON('subtitle', $chartData);
		}
		
		$legends  = null;
		if (!empty($chartData['legend'])) {
			$legends = static::drawJSON('legend', $chartData);
		}
		
		$tooltips = null;
		if (!empty($chartData['tooltip'])) {
			$tooltips = static::drawJSON('tooltip', $chartData);
		}
		
		$axisCat      = [];
		
		$xAxisData    = self::axisData('x', $chartData);
		$xAxis        = $xAxisData['data'];
		$axisCat['x'] = $xAxisData['category'];
		
		$yAxisData    = self::axisData('y', $chartData);
		$yAxis        = $yAxisData['data'];
		$axisCat['y'] = $yAxisData['category'];
		
		$axisCategory = in_array(true, $axisCat);
		if (false === $axisCategory) {
			$chartData['xAxis']['categories'] = $chartData['category'];
			$xAxis = static::drawJSON('xAxis', $chartData);
		}
		
		$this->elements[$identity] .= self::scriptChart('line', $identity, $title, $subtitle, $xAxis, $yAxis, $tooltips, $legends, $series);
	}
}