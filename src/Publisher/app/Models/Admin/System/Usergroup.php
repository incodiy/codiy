<?php
namespace App\Models\Admin\System;

use Illuminate\Database\Eloquent\Model;

/**
 * Created on Jan 14, 2018
 * Time Created	: 12:37:50 AM
 * Filename		: Usergroup.php
 *
 * @filesource	Usergroup.php
 *
 * @author		wisnuwidi@Expresscode - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Usergroup extends Model {
	protected $table	= 'base_user_group';
	protected $guarded	= [];
	
	public $timestamps	= false;
}