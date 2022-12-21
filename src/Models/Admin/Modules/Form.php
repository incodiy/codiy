<?php
namespace Incodiy\Codiy\Models\Admin\Modules;

use Incodiy\Codiy\Models\Core\Model;

/**
 * Created on 24 Mar 2021
 * Time Created	: 10:39:03
 *
 * @filesource	Form.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class Form extends Model {
	protected $connection = 'mysql_mantra_etl';
	
	protected $table	 = 'report_data_summary_ho_program_keren_merapi';//'test_inputform';
	protected $guarded = [];
}