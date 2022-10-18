<?php
namespace Incodiy\Codiy\Library\Components\Chart\Models\Line\Basic;

/**
 * Created on Oct 11, 2022
 * 
 * Time Created : 10:12:56 AM
 *
 * @filesource	Script.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */
trait Script {
	
	private function line_script($identity, $data = []) {
		$chartData = $data['data'];
		
		$series    = null;
		if (!empty($chartData['series'])) {
			$data_series = json_encode($chartData['series']);
			$series      = "series:{$data_series}";
		}
		
		if (!empty($chartData['category'])) {
			$category      = [];
			$category['x'] = null;
			$category['y'] = null;
			
			$data_category = json_encode($chartData['category']);
			$category['x'] = "categories:{$data_category}";
		}
		
		$title = null;
		if (!empty($chartData['title'])) $title = 'title:' . json_encode(['text' => $chartData['title']]) . ',';
		
		$subtitle = null;
	//	if (!empty($this->subtitle)) $subtitle = $this->subtitle;
		
		$legends  = null;
	//	if (!empty($this->legends))  $legends  = $this->legends;
		
		$tooltips = null;
	//	if (!empty($this->tooltips)) $tooltips = $this->tooltips;
		
		$script = "<script type=\"text/javascript\">
$(function () {
    $('#{$identity}').highcharts({
        {$title}
        {$subtitle}
        xAxis: {
            {$category['x']}
        },
        yAxis: {
            title: {
                text: 'Temperature (C)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        {$tooltips}
        {$legends}
        {$series}
    });
});
        </script>";
        $this->elements[$identity] .= $script;
	}
}