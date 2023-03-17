<?php
namespace Incodiy\Codiy\Database\Seeders\Includes\App;

use Incodiy\Codiy\Models\Admin\System\User;
/**
 * Created on Dec 12, 2022
 * 
 * Time Created : 5:43:44 PM
 *
 * @filesource	Users.php
 *
 * @author     wisnuwidi@incodiy.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@incodiy.com
 */

trait Users {
	
	/**
	 * Insert Users
	 *
	 * SELECT
			nik username,
			`name` fullname,
			email,
			CONCAT("
				User::create([
					'username' => '", nik ,"', 
					'fullname' => '", `name` ,"', 
					'email' => '", email ,"', 
					'password' => bcrypt('@", nik ,"'), 
					'cryptcode' => diy_user_cryptcode('", nik ,"', '", email ,"'), 
					'active' => 1, 
					'created_by' => 1, 
					'updated_by' => 1
				]);
			") sql_query
		FROM user_data_regional_keren
		GROUP BY nik, email;
	 */
    private function insertUsers() {
        User::create(['username' => 'sales.reporting', 'fullname' => 'Sales Reporting', 'email' => 'sales.reporting@smartfren.com', 'password' => bcrypt('@Internal'), 'cryptcode' => diy_user_cryptcode('sales.reporting@smartfren.com', 'sales.reporting@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
        User::create(['username' => 'customer.analytics', 'fullname' => 'Customer Analytics', 'email' => 'customer-analytics@smartfren.com', 'password' => bcrypt('@sfDJca#2023!Mar'), 'cryptcode' => diy_user_cryptcode('customer-analytics@smartfren.com', 'customer-analytics@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
	}
}