<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Components;

use Incodiy\Codiy\Library\Components\Messages\Email\Objects;
/**
 * Created on Jul 1, 2023
 * 
 * Time Created : 9:36:25 PM
 *
 * @filesource  Email.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 */
trait Email {
	public $email;
	
	private function initEmail() {
		$this->email = new Objects();
		$this->plugins['email'] = $this->email;
	}
}