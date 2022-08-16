<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Components;

use Incodiy\Codiy\Library\Components\Table\Objects;

/**
 * Created on 12 Apr 2021
 * Time Created	: 19:35:40
 * 
 * Marhaban Ya RAMADHAN
 *
 * @filesource	Table.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
trait Table {
	public $table;
	
	private function initTable() {
		$this->table = new Objects();
		$this->plugins['table']	= $this->table;
	}
}