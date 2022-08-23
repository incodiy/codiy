<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Includes;

use Illuminate\Support\Facades\Route;
use Incodiy\Codiy\Models\Admin\System\Modules;

/**
 * Created on 9 Apr 2021
 * Time Created	: 14:49:04
 *
 * @filesource	Privileges.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */

trait Privileges {
	public $menu              = [];
	public $module_class;
	public $module_privilege  = [];
	public $is_module_granted = false;
	
	/**
	 * Get Privileges Module
	 *
	 * created @Dec 11, 2018
	 * author: wisnuwidi
	 */
	private function module_privileges() {
		if (!is_null(Session('group_id'))) {
			$root_flag = false;
			$pageType  = false;
			$this->module_class = new Modules();
			
			if (1 === intval(Session('group_id'))) if (true === isset($this->session['flag'])) $root_flag = true;
			if (isset($this->data['page_type'])) $pageType = $this->data['page_type'];
			
			$this->menu                      = $this->module_class->privileges(Session('group_id'), $pageType, $root_flag);
			$this->module_privilege['roles'] = $this->module_class->roles;
			$this->module_privilege['info']  = $this->module_class->privileges;
			
			$this->access_role();
		}
	}
	
	private function access_role() {
		$this->is_module_granted = in_array(current_route(), $this->module_class->roles);
	}
}