<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Incodiy\Codiy\Models\Core\Model;

/**
 * Created on Jun 9, 2023
 * 
 * Time Created : 2:11:03 PM
 *
 * @filesource  UserActivity.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
class UserActivity extends Model {
	protected $table   = 'view_table_user_activities';
//	protected $table   = 'log_user_activities';
	protected $guarded = [];
	
	public $timestamps = false;
}