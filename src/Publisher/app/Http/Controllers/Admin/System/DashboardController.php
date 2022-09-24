<?php
namespace App\Http\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;

/**
 * Created on May 17, 2018
 * Time Created	: 8:53:34 AM
 * Filename		: DashboardController.php
 *
 * @filesource	DashboardController.php
 *
 * @author		wisnuwidi@gmail.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class DashboardController extends Controller {
	
	private $name			= 'article';
	private $route_group	= 'modules.article';
	public $table			= 'base_article';
	
	private $_hide_fields	= ['id'];
	private $_set_tab		= [];
	private $_tab_config	= [];
	private $flag			= [];
	
	public function __construct() {
		parent::__construct();
	}
	
	private function set_route($path) {
		return "{$this->route_group}.{$path}";
	}
	
	private function table_config() {
		$this->form->table_hide_fields($this->table, $this->_hide_fields);
	}
	
	public function index() {
		$this->set_page(camel_case($this->name) . ' Lists', $this->name);
		
		return $this->render();
	}
}