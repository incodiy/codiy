<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Controllers\Core\Craft\Handler;
use Incodiy\Codiy\Models\Admin\System\UserActivity;

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
		'roles',
		'region',
		'username',
		'fullname',
		'user_email',
		'last_access',
		'hits',
		'length_days',
		'user_status'
	];
	
	public function __construct() {
		parent::__construct(UserActivity::class, 'system.managements.user_activity');
	}
	
	public function index() {
		$this->setPage();
		$this->removeActionButtons(['add']);
		
		$this->table->setUrlValue('user_id');
	
		$this->table->searchable(['period', 'roles', 'region', 'username']);
		$this->table->clickable();
		$this->table->sortable();
		 
		$this->table->orderby('period', 'desc');
		
		$this->table->filterGroups('period', 'selectbox', true);
		$this->table->filterGroups('roles', 'selectbox', true);
		$this->table->filterGroups('region', 'selectbox', true);
		$this->table->filterGroups('username', 'selectbox', false);
		
		$this->table->columnCondition('hits', 'cell', '<=', 10, 'background-color', 'rgb(255, 242, 204)');
		
		$this->table->lists($this->model_table, $this->fields, ['action_check|warning|power-off']);
		
		return $this->render();
	}
}