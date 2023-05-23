<?php
namespace Incodiy\Codiy\Library\Components\Charts\Canvas;

use Incodiy\Codiy\Library\Components\Chart\Includes\Scripts;

/**
 * Created on May 23, 2023
 * 
 * Time Created : 4:31:19 PM
 *
 * @filesource  Builder.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
class Builder {
	use Scripts;
	
	public $model;
	public $method = 'GET';
	
	protected function setMethod($method) {
		$this->method = $method;
	}
}