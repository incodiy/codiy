<?php
/**
 * Created on Oct 19, 2022
 * 
 * Time Created : 5:33:34 PM
 *
 * @filesource	Chart.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */

if (!function_exists('diy_script_chart')) {
	
	function diy_script_chart($type = 'line', $identity, $title, $subtitle, $xAxis, $yAxis, $tooltips, $legends, $series) {
		$chartType = "chart: {type: '{$type}'},";
		return "<script type=\"text/javascript\">$(function () { $('#{$identity}').highcharts({ {$chartType}{$title}{$subtitle}{$xAxis}{$yAxis}{$tooltips}{$legends}{$series} }); });</script>";
	}
}