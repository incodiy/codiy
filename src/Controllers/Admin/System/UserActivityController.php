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
		'created_date',
		'roles',
		'role_location',
		'username',
		'user_fullname',
		'user_email',
		'module_name',
		'route_path',
		'page_info',
		'ip_address',
		'user_agent',
		'datetime_start',
		'datetime_end',
		'time_start',
		'time_end',
		'time_hits',
		'time_length',
		'time_length_detail',
		'datetime_refreshed',
		'time_refreshed',
		'user_status',
		'user_info',
		'insert_date',
		'insert_time'
	];
	
	private $fieldset = [
		'user_fullname:User Name',
		'user_group_info:Group Info',
		'route_path',
		'module_name',
		'page_info',
		'urli',
		'method',
		'ip_address',
		'user_agent',
		'created_at'
	];
	
	public function __construct() {
		parent::__construct(UserActivity::class, 'system.managements.user_activity');
	}
	
	public function index() {
		$this->setPage();
		$this->removeActionButtons(['add']);
		
		$this->table->setUrlValue('user_id');
	//	$this->table->lists($this->model_table, $this->fields, ['new_button', 'button_name|warning|tags']);
		$this->table->lists($this->model_table, $this->fields);
		
		return $this->render();
	}
}