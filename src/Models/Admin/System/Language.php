<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Incodiy\Codiy\Models\Core\Model;

/**
 * Created on Jan 15, 2018
 * Time Created	: 2:09:13 PM
 * Filename		: Language.php
 *
 * @filesource	Language.php
 *
 * @author		wisnuwidi@Expresscode - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class Language extends Model {
	protected $table		= 'base_language';
	protected $guarded		= [];
	
	public $timestamps		= false;
}