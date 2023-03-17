<?php
use Incodiy\Codiy\Library\Components\MetaTags;

/**
 * Created on 14 Mar 2021
 * Time Created	: 23:02:18
 *
 * @filesource	MetaTags.php
 *
 * @author		wisnuwidi@incodiy.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
 */
 
if (!function_exists('diy_meta_tags')) {
    
    /**
     * Get Asset Path
     *
     * @return string
     */
    function diy_meta_tags($as = 'html') {
        $metaTags = new MetaTags();
        
        return $metaTags->tags($as);
    }
}