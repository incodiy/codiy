<?php
namespace Incodiy\Codiy\Models\Admin\Modules\Shop;

use Incodiy\Codiy\Models\Core\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created on Dec 17, 2022
 * 
 * Time Created : 5:51:33 PM
 * Filename     : Category.php
 *
 * @filesource Category.php
 *
 * @author     wisnuwidi @Incodiy - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@incodiy.com
 */
class Category extends Model {
	use SoftDeletes;
	
	protected $table	 = 'shop_category';
	protected $guarded = [];
}