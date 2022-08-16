<?php
namespace Incodiy\Codiy\Controllers\Core\Craft;

use Illuminate\Support\Facades\Session as Sessions;

/**
 * Created on 24 Mar 2021
 * Time Created	: 13:15:08
 *
 * @filesource	Session.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */

trait Session {
	public $session = [];
	
	/**
	 * Set Session From User Logged In
	 */
	public function set_session() {
		$this->session				= Sessions::all();
		$this->data['sessions']	= $this->session;
	}
	
	/**
	 * Get All Session From User Logged In
	 *
	 * @return array [ $this->session ]
	 */
	public function get_session($return_data = false) {
		$this->set_session();
		
		if (true === $return_data) return $this->session;
	}
}