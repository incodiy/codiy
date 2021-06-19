<?php
/**
 * Created on 15 Mar 2021
 * Time Created	: 00:44:02
 *
 * @filesource	Template.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */

if (!function_exists('diy_js')) {
	
	function diy_js($scripts, $position = 'bottom', $as_script_code = false) {
		$template = new Incodiy\Codiy\Library\Components\Template();
		
		return $template->js($scripts, $position, $as_script_code);
	}
}

if (!function_exists('diy_css')) {
	
	function diy_css($scripts, $position = 'top', $as_script_code = false) {
		$template = new Incodiy\Codiy\Library\Components\Template();
		
		return $template->js($scripts, $position, $as_script_code);
	}
}

if (!function_exists('diy_gird')) {
	
	/**
	 * Draw HTML Gird Container
	 *
	 * created @Mar 16, 2021
	 * author: wisnuwidi
	 *
	 * @param string $name
	 * 		: [start|container|container-fluid|end|bootstrap classname element]
	 * @param bool|string|mixed $addHTML
	 * @param bool $single
	 */
	function diy_gird($name = 'start', $set_column = false, $addHTML = false, $single = false) {
		$numberColumn = 12;
		if (!empty($set_column)) {
			$numberColumn = intval(12 - $set_column);
		}
		
		$col = " col-{$numberColumn}";
		
		if ('end' === $name) {
			$single  = false;
			$addHTML = false;
			
			return '</div></div></div>';
		} else {
			if (!empty($addHTML)) {
				$single = true;
			}
			
			if (true === $single) {
				return "<div class=\"{$name}\">{$addHTML}</div>";
			} else {
				if ('start' === $name || 'container' === $name) {
					return '<div class="container"><div class="row"><div class="col' . $col . '">';
				} elseif ('container-fluid' === $name) {
					return '<div class="container-fluid"><div class="row"><div class="col' . $col . '">';
				} else {
					return "<div class=\"row\"><div class=\"col' . $col . '\"><div class=\"{$name}\">";
				}
			}
		}
	}
}

if (!function_exists('diy_set_gird_column')) {
	
	/**
	 * Draw HTML With Gird Column Setting
	 *
	 * created @Mar 16, 2021
	 * author: wisnuwidi
	 *
	 * @param string $html
	 * @param boolean $set_column
	 *
	 * @return string
	 */
	function diy_set_gird_column($html, $set_column = false) {
		$numberColumn = 12;
		if (!empty($set_column)) {
			$numberColumn = intval(12 / $set_column);
		}
		$col = " col-{$numberColumn}";
		
		return "<div class=\"col{$col}\">{$html}</div>";
	}
}

if (!function_exists('diy_breadcrumb')) {
    
    /**
     * Create Breadcrumb Tag
     *
     * @param string $title
     * @param array $links
     * @param string $icon_title
     * @param string $icon_links
     *
     * @return string
     */
    function diy_breadcrumb($title, $links = [], $icon_title = false, $icon_links = false, $type = false) {
        if ('blankon' === $type) {
            $n = 0;
            $linkIcons = false;
            if (false !== $icon_links) {
                foreach ($icon_links as $link_icon) {
                    $linkIcons[] = "<i class=\"fa fa-{$link_icon}\"></i> ";
                }
            }
            
            $o  = "<div class=\"header-content\">";
            $o .= "<h4 style=\"margin:3px 6px !important\">";
            if (false !== $icon_title) {
                $o .= "<i class=\"fa fa-{$icon_title}\"></i> ";
            }
            $o .= $title;
            $o .= "</h4>";
            $o .= "<div class=\"breadcrumb-wrapper hidden-xs\">";
            if ($links) {
                $o .= "<ol class=\"breadcrumb\">";
                foreach ($links as $link_title => $link_url) {
                    $n++;
                    
                    $index		= $n - 1;
                    $linkTitle	= underscore_to_camelcase($link_title);
                    
                    $o .= "<li>";
                    if ($linkIcons[$index]) $o .= $linkIcons[$index];
                    if (0 !== $link_title) {
                        $o .= "<a href=\"{$link_url}\">{$linkTitle}</a>";
                    } else {
                        $linkTitle	= ucwords($link_url);
                        $o .= "<a>{$linkTitle}</a>";
                    }
                    $o .= "<i class=\"fa fa-angle-right\"></i>";
                    $o .= "</li>";
                }
                $o .= "</ol>";
            }
            $o .= "</div>";
            $o .= "</div>";
        } else {
            
            $o  = "<div class=\"page-title-area shadow\">";
            $o .= "<div class=\"row align-items-center\">";
            $o .= "<div class=\"col-sm-12\">";
            $o .= "<div class=\"breadcrumbs-area clearfix\">";
            $o .= "<h4 class=\"page-title pull-left\">{$title}</h4>";
            
            $n = 0;
            $linkIcons = false;
            if (false !== $icon_links) {
                foreach ($icon_links as $link_icon) {
                    $linkIcons[] = "<i class=\"fa fa-{$link_icon}\"></i> ";
                }
            }
            
            if ($links) {
                $o .= "<ul class=\"breadcrumbs pull-right\">";
                foreach ($links as $link_title => $link_url) {
                    $n++;
                    
                    $index		= $n - 1;
                    $linkTitle	= diy_underscore_to_camelcase($link_title);
                    
                    $o .= "<li>";
                    if (0 !== $link_title) {
                        $o .= "<a href=\"{$link_url}\">{$linkTitle}</a>";
                    } else {
                        $linkTitle	= ucwords($link_url);
                        $o .= "<span>{$linkTitle}</span>";
                    }
                    $o .= "</li>";
                }
                $o .= "</ul>";
            }
            
            $o .= "</div></div></div></div>";
        }
        
        return $o;
    }
}