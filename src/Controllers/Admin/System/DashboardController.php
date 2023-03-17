<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;

/**
 * Created on May 17, 2018
 * Time Created	: 8:53:34 AM
 * Filename		: DashboardController.php
 *
 * @filesource	DashboardController.php
 *
 * @author		wisnuwidi@incodiy.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
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
	
	public function index() {
		$this->setPage();
		
		return $this->render();
	}
}