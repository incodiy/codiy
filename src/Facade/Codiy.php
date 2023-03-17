<?php
namespace Incodiy\Codiy\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Created on Mar 22, 2018
 * Time Created	: 5:00:32 PM
 * Filename		: IncoDIY.php
 *
 * @filesource	IncoDIY.php
 *
 * @author		wisnuwidi@incodiy.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
 */
 
class Codiy extends Facade {
	protected static function getFacadeAccessor() {
		return 'Codiy';
	}
}