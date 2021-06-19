<?php
namespace Incodiy\Codiy\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Created on Mar 22, 2018
 * Time Created	: 5:00:32 PM
 * Filename		: Expresscode.php
 *
 * @filesource	Expresscode.php
 *
 * @author		wisnuwidi@gmail.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Codiy extends Facade {
	protected static function getFacadeAccessor() {
		return 'Codiy';
	}
}