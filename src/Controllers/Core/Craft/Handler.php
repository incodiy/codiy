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
	private $roleHandlerAlias = ['admin'];
	private $roleHandlerInfo  = [];
	
	private function initHandler() {
		$this->roleAlias(['admin', 'internal']);
		$this->roleInfo(['National']);
	}
	
	private function roleHandlerInfo($role) {
		$this->roleHandlerInfo = $role;
	}
	
	private function roleHandlerAlias($role) {
		$this->roleHandlerAlias = $role;
	}
	
	private function customHandler() {}
	
	protected function sessionFilters() {
		$this->initHandler();
		$user_session_alias = diy_config('user.alias_session_name');
		
		if ('root' !== $this->session['user_group']) {
			if (!in_array($this->session['user_group'], $this->roleHandlerAlias)) {
				if (!empty($this->roleHandlerInfo)) {
					if (!in_array($this->session['group_alias'], $this->roleHandlerInfo)) {
						$this->customHandler();
						if (!empty($this->session[$user_session_alias])) {
							foreach ($this->session[$user_session_alias] as $fieldset => $fieldvalues) {
								$this->filterPage([$fieldset => $fieldvalues], '=');
							}
						}
					}
				} else {
					$this->customHandler();
					if (!empty($this->session[$user_session_alias])) {
						foreach ($this->session[$user_session_alias] as $fieldset => $fieldvalues) {
							$this->filterPage([$fieldset => $fieldvalues], '=');
						}
					}
				}
			}
		}
	}
}