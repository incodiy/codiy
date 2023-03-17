<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Components;

use Incodiy\Codiy\Library\Components\Template as Theme;

/**
 * Created on 26 Mar 2021
 * Time Created	: 17:18:23
 *
 * @filesource	Template.php
 *
 * @author		wisnuwidi@incodiy.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
 */
 
trait Template {
	
	public $template = [];
	private function initTemplate() {
		$this->template = new Theme();
		$this->plugins['template']	= $this->template;
	}
}