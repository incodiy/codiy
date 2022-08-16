<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Illuminate\Database\Eloquent\Model;

/**
 * Created on Jan 15, 2018
 * Time Created	: 2:22:05 PM
 * Filename		: Timezone.php
 *
 * @filesource	Timezone.php
 *
 * @author		wisnuwidi@Expresscode - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Timezone extends Model {
	protected $table		= 'base_timezone';
	protected $guarded	= [];
	
	public $timestamps	= false;
}