<?php
namespace Incodiy\Codiy\Controllers\Core\Craft;
/**
 * Created on 2 Apr 2023
 * 
 * Time Created : 19:50:57
 *
 * @filesource  Handler.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
trait Handler {
	private $roleAlias = ['admin'];
	private $roleInfo  = [];
	
	private function roleHandlerInfo($role) {
		$this->roleInfo = $role;
	}
	
	private function roleHandlerAlias($role) {
		$this->roleAlias = $role;
	}
	
	private function initHandler() {}
	
	private function customHandler() {}
	
	protected function sessionFilters() {
		$this->initHandler();
		if ('root' !== $this->session['user_group']) {
			if (!in_array($this->session['user_group'], $this->roleAlias)) {
				$this->customHandler();
				$this->sessionConfig();
			}
		}
	}
	
	private function sessionConfig() {
		$user_session_alias = diy_config('user.alias_session_name');
		if (!empty($this->session[$user_session_alias])) {
			foreach ($this->session[$user_session_alias] as $fieldset => $fieldvalues) {
				$this->filterPage([$fieldset => $fieldvalues], '=');
			}
		}
	}
}