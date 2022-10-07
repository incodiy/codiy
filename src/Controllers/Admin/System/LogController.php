<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Models\Admin\System\Log;
use Illuminate\Support\Facades\Request;

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
	
	private $field_lists = [
		'user_fullname',
		'user_email',
		'user_group_info',
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
		parent::__construct(Log::class, 'system.config.log');
	}
	
	public function index() {
		$this->setPage();
		
		$this->table->searchable($this->field_lists);
		$this->table->clickable(false);
		$this->table->sortable();
		
		$this->table->filterGroups('user_fullname', 'selectbox', true);
		$this->table->filterGroups('user_group_info', 'selectbox', true);
		$this->table->filterGroups('method', 'selectbox', true);
		$this->table->filterGroups('module_name', 'selectbox', true);
		
		$this->table->lists($this->model_table, $this->field_lists, false);
		
		return $this->render();
	}
}