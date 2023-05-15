<?php
/**
 * Created on Oct 19, 2022
 * 
 * Time Created : 5:33:34 PM
 *
 * @filesource	Chart.php
 *
 * @author     wisnuwidi@incodiy.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@incodiy.com
 */

if (!function_exists('diy_script_highcharts')) {
	
	function diy_script_highcharts($type = 'line', $identity = null, $title = null, $subtitle = null, $xAxis = null, $yAxis = null, $tooltips = null, $legends = null, $series = null) {
		$chartType = "chart: {type: '{$type}'},";
		return "<script type=\"text/javascript\">$(function () { $('#{$identity}').highcharts({ {$chartType}{$title}{$subtitle}{$xAxis}{$yAxis}{$tooltips}{$legends}{$series} }); });</script>";
	}
}

if (!function_exists('diy_script_apexcharts')) {
	
	function diy_script_apexcharts($type = 'line', $identity = null, $title = null, $subtitle = null, $xAxis = null, $yAxis = null, $tooltips = null, $legends = null, $series = null) {
		return "
<!--script type=\"text/javascript\" src=\"http://localhost/incodiy/.dev/public/assets/templates/default/js/apexcharts.min.js\"></script -->
<script type=\"text/javascript\">

  var options = {
  chart: {
    type: 'bar'
  },
  series: [{
    name: 'sales',
    data: [30,40,45,50,49,60,70,91,125]
  }],
  xaxis: {
    categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
  }
}

var chart = new ApexCharts(document.querySelector('#{$identity}'), options);

chart.render();

</script>";
	}
}
