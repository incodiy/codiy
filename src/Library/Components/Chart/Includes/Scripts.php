<?php
namespace Incodiy\Codiy\Library\Components\Chart\Includes;

/**
 * Created on Oct 24, 2022
 * 
 * Time Created : 1:57:38 PM
 *
 * @filesource	Scripts.php
 *
 * @author     wisnuwidi@incodiy.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@incodiy.com
 */

trait Scripts {
	
	private static function drawJSON($name, $chartData, $setLabel = null) {
		$data = $chartData[$name];
		if ('combined' === $name) {
			$data = array_merge($chartData['series'], $data);
		}
		
		$label = $name;
		if (!empty($setLabel)) $label = $setLabel;
		
		return "{$label}:" . json_encode($data) . ',';
	}
	
	private function scriptChart($type = 'line', $identity, $title, $subtitle, $xAxis, $yAxis, $tooltips, $legends, $series) {
		if ('highcharts' === $this->chartLib) return diy_script_highcharts($type, $identity, $title, $subtitle, $xAxis, $yAxis, $tooltips, $legends, $series);
		if ('apex' === $this->chartLib)       return diy_script_apexcharts($type, $identity, $title, $subtitle, $xAxis, $yAxis, $tooltips, $legends, $series);;
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
}