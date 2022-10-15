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
	
	private function line_script($title, $identity, $data = []) {
		$series = null;
		if (!empty($data)) {
			$data_series = json_encode($data);
			$series = "series:{$data_series}";
		}
		
		$title    = null;
		if (!empty($this->title))    $title    = $this->title;
		
		$subtitle = null;
		if (!empty($this->subtitle)) $subtitle = $this->subtitle;
		
		$legends  = null;
		if (!empty($this->legends))  $legends  = $this->legends;
		
		$tooltips = null;
		if (!empty($this->tooltips)) $tooltips = $this->tooltips;
		
		$category      = [];
		$category['x'] = null;
		$category['y'] = null;
		if (!empty($this->categories)) {
			$category[$this->categories['axis']] = $this->categories['data'];
		}
		
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