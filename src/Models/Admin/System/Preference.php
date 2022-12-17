<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Incodiy\Codiy\Models\Core\Model;

/**
 * Created on Mar 14, 2018
 * Time Created	: 8:49:50 PM
 * Filename		: Preference.php
 *
 * @filesource	Preference.php
 *
 * @author		wisnuwidi@gmail.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Preference extends Model {
	protected $table		= 'base_preference';
	protected $guarded	= [];
	
	public $timestamps	= false;
}