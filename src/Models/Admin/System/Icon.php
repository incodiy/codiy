<?php
/**
 * Created on Mar 15, 2018
 * Time Created	: 9:29:14 AM
 * Filename		: Icon.php
 *
 * @filesource	Icon.php
 *
 * @author		wisnuwidi@incodiy.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
 */
 
namespace Incodiy\Codiy\Models\Admin\System;

use Incodiy\Codiy\Models\Core\Model;

/**
 * Created on Jan 14, 2018
 * Time Created	: 12:06:59 AM
 * Filename		: Group.php
 *
 * @filesource	Group.php
 *
 * @author		wisnuwidi@IncoDIY - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
 */
class Icon extends Model {
	protected $table   = 'base_icon';
	protected $guarded = [];
	
	public $timestamps = false;
}