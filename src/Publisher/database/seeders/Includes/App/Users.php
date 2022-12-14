<?php
namespace Database\Seeders\Includes\App;

use Incodiy\Codiy\Models\Admin\System\User;
/**
 * Created on Dec 12, 2022
 * 
 * Time Created : 5:43:44 PM
 *
 * @filesource	Users.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
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
		User::create(['username' => 'internal', 'fullname' => 'Internal Team', 'email' => 'sf@team.net', 'password' => bcrypt('@Internal'), 'cryptcode' => diy_user_cryptcode('sf@team.net', 'sf@team.net'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => 'regional', 'fullname' => 'Regional', 'email' => 'reg@sf.test', 'password' => bcrypt('@Regional'), 'cryptcode' => diy_user_cryptcode('regional', 'reg@sf.test'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
	}
}