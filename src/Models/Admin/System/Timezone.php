<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Incodiy\Codiy\Models\Core\Model;

/**
 * Created on Jan 15, 2018
 * Time Created	: 2:22:05 PM
 * Filename		: Timezone.php
 *
 * @filesource	Timezone.php
 *
 * @author		wisnuwidi@IncoDIY - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
 */
 
class Timezone extends Model {
	protected $table		= 'base_timezone';
	protected $guarded	= [];
	
	public $timestamps	= false;
}