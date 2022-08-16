<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Illuminate\Database\Eloquent\Model;

/**
 * Created on Jan 14, 2018
 * Time Created	: 12:20:33 AM
 * Filename		: Privilege.php
 *
 * @filesource	Privilege.php
 *
 * @author		wisnuwidi@Expresscode - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Privilege extends Model {
	protected $table		= 'base_group_privilege_copy';
	protected $guarded	= [];
	
	public $timestamps	= false;
}