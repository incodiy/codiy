<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Models\Admin\System\Log;

/**
 * Created on Jan 16, 2018
 * Time Created	: 11:23:32 PM
 * Filename		: LogController.php
 *
 * @filesource	LogController.php
 *
 * @author		wisnuwidi@Expresscode - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class LogController extends Controller {
	public $data;
	
	private $route_group	= 'system';
	private $model_table	= 'log_activities';
	
	private $_hide_fields	= ['id'];
	private $_set_tab		= [];
	private $_tab_config	= [];
	
	public function __construct() {
		parent::__construct();
		
		$this->base_route	= "{$this->route_group}.";
	}
	
	public function index() {
		$this->set_page('Log Activity', 'Logs');
		$this->hide_button_actions();
		$this->model_query(Log::class, true);
		
		$this->form->set_relational_list_value('user_id', 'email', 'users', 'id', 'User');
		$this->form->lists($this->model_table, ['info', 'uri', 'method', 'user_id', 'ip_address', 'created_at'], $this->model_data, false, true);
		
		$this->searchInputElement('info', 'string');
		$this->searchInputElement('uri', 'string');
		$this->searchInputElement('method', 'string');
		$this->searchInputElement('user_id', 'string');
		
		$this->searchDraw($this->model_table, ['id'], false);
		
		return $this->render();
	}
}