<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Components;

use Incodiy\Codiy\Library\Components\MetaTags As Meta;

/**
 * Created on 26 Mar 2021
 * Time Created	: 17:06:51
 *
 * @filesource	MetaTags.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
trait MetaTags {
	
	public $meta = [];
	private function initMetaTags() {
		$this->meta					= new Meta();
		$this->plugins['meta']		= $this->meta;
	}
}