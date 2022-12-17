<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Incodiy\Codiy\Models\Core\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created on Oct 2, 2018
 * Time Created	: 1:48:35 PM
 * Filename		: Messages.php
 *
 * @filesource	Messages.php
 *
 * @author		wisnuwidi@gmail.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */

class Messages extends Model {
	use SoftDeletes;
	
	protected $table   = 'mod_messages';
	protected $guarded = [];
	protected $dates   = ['deleted_at'];
}