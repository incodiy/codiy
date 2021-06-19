<?php
namespace Incodiy\Codiy\Library\Components;

/**
 * Created on 10 Mar 2021
 * Time Created	: 11:11:53
 *
 * @filesource	Template.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */

class Template extends Scripts {
    
    /**
     * 'assets/templates/default/'
     */
    public $currentTemplate;
    
    public function __construct() {
        parent::__construct();
        
        $this->currentTemplate = diy_config('template');
        
        $this->templateScripts('js');
        $this->templateScripts('css');
    }
    
    private function templatePath($scriptPath) {
        return "{$this->assetPath}/{$scriptPath}";
    }
    
    private function templateScripts($type) {
        $type									= strtolower($type);
        $scriptConfig							= [];
        $scriptConfig[$type]['top']				= diy_config("admin.default.position.top.{$type}", 'templates');
        $scriptConfig[$type]['bottom']			= diy_config("admin.default.position.bottom.{$type}", 'templates');
        $scriptConfig[$type]['bottom']['first'] = diy_config("admin.default.position.bottom.first.{$type}", 'templates');
        $scriptConfig[$type]['bottom']['last']	= diy_config("admin.default.position.bottom.last.{$type}", 'templates');
        
        foreach ($scriptConfig as $scriptPositions) {
        	foreach ($scriptPositions as $position => $scriptPath) {
                foreach ($scriptPath as $pos => $scriptCheck) {
                	if (is_array($scriptCheck)) {
                		foreach ($scriptCheck as $scriptURL) {
                            $scriptURL = $this->templatePath($scriptURL);
                            $this->{$type}($scriptURL, "{$position}_{$pos}");
                        }
                    } else {
                        $scriptURL = $this->templatePath($scriptCheck);
                        $this->{$type}($scriptURL, $position);
                    }
                }
            }
        }
    }
    
    /**
     * Create Breadcrumb
     *
     * @param string $title
     * @param array $links
     * @param string $icon_title
     * @param string $icon_links
     */
    public function set_breadcrumb($title, $links = [], $icon_title = false, $icon_links = false) {
        $this->breadcrumbs = diy_breadcrumb($title, $links, $icon_title, $icon_links);
    }
}