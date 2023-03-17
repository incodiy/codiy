<?php
namespace Incodiy\Codiy\Database\Migrations;

use Incodiy\Codiy\Database\Migrations\Tables\BaseTables;
use Incodiy\Codiy\Database\Migrations\Tables\ShopTables;
/**
 * Created on Dec 17, 2022
 * 
 * Time Created : 3:21:08 AM
 * Filename     : Process.php
 *
 * @filesource Process.php
 *
 * @author     wisnuwidi @Incodiy - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@incodiy.com
 */
class Process extends Config {
	
	public function __construct() {
		$this->init();
	}
	
	private $initialize;
	private function init() {
		$this->initialize['base'] = new BaseTables();
		$this->initialize['shop'] = new ShopTables();
	}
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$this->initialize['base']->up();
		$this->initialize['shop']->up();
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$this->initialize['base']->drop();
		$this->initialize['shop']->drop();
		$this->initialize['base']->last_drop();
	}
}