<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created on Nov 8, 2018
 * Time Created	: 4:51:40 PM
 * Filename		: Maintenance.php
 *
 * @filesource	Maintenance.php
 *
 * @author		wisnuwidi@gmail.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Maintenance extends Model {
	use SoftDeletes;
	
	protected $table   = 'base_maintenance';
	protected $guarded = [];
	protected $dates   = ['deleted_at'];
}