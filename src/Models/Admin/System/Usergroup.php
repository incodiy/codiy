<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Incodiy\Codiy\Models\Core\Model;

/**
 * Created on Jan 14, 2018
 * Time Created	: 12:37:50 AM
 * Filename		: Usergroup.php
 *
 * @filesource	Usergroup.php
 *
 * @author		wisnuwidi@IncoDIY - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
 */
 
class Usergroup extends Model {
	protected $table   = 'base_user_group';
	protected $guarded = [];
	
	public $timestamps = false;
}