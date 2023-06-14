<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Controllers\Core\Craft\Handler;
use Incodiy\Codiy\Models\Admin\System\UserActivity;
use Incodiy\Codiy\Models\Admin\System\User as User;

use Illuminate\Http\Request;

/**
 * Created on Jun 9, 2023
 * 
 * Time Created : 1:48:59 PM
 *
 * @filesource  UserActivityController.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
class UserActivityController extends Controller {
	use Handler;
	
	private $fields = [
		'period',
	//	'roles',
	//	'region',
		'username',
		'fullname',
	//	'user_email',
	//	'last_access',
		'time_hits',
	//	'length_days',
		'user_status'
	];
	
	public function __construct() {
		parent::__construct(UserActivity::class, 'system.managements.user_activity');
		
	}
	
	public function index() {
		$this->setPage();
		$this->sessionFilters();
	//	$this->removeActionButtons(['add']);
		
		$this->table->setUrlValue('user_id');
	
		$this->table->searchable(['period', 'roles', 'region', 'username']);
		$this->table->clickable(false);
		$this->table->sortable();
		/* 
		$this->table->orderby('time_hits', 'asc');
		$this->table->orderby('period', 'desc'); */
		
		$this->table->filterGroups('period', 'selectbox', true);
		$this->table->filterGroups('roles', 'selectbox', true);
		$this->table->filterGroups('region', 'selectbox', true);
		$this->table->filterGroups('username', 'selectbox', false);
		
		$this->table->columnCondition('time_hits', 'cell', '<=', 10, 'background-color', 'rgb(255, 242, 204)');
	//	$this->table->columnCondition('user_status', 'length_days', '==', 'Disabled', 'replace', 'aaa');
		$this->table->columnCondition('user_status', 'cell', '==', 'Disabled', 'background-color', 'rgb(255, 242, 204)');
		$this->table->columnCondition('user_status', 'action', '==', 'Active', 'replace', 'ajax::manage|warning|check-square-o');
		$this->table->columnCondition('user_status', 'action', '==', 'Disabled', 'replace', 'ajax::manage|danger|power-off');
		
		$this->callModel('montly_activity');
		$this->table->lists($this->model_table, $this->fields, ['manage']);
	//	$this->table->lists('temp_user_activity_monthly', $this->fields, ['manage']);
		
		return $this->render();
	}
	
	public function manage(Request $request) {
		if (!empty($request)) {
			$user = new User();
			$reqs = $request->all();
			$id   = intval($reqs['data']);
			$data = $user->find($id)->getAttributes();
			
			$new_array = ['active' => 1];
			if ($data['active'] >= 1) {
				$new_array = ['active' => 0];
			}
			
			$request = new Request();
			$request->merge($new_array);
			
			diy_update($user->find($id), $request, true);
		}
	}
}