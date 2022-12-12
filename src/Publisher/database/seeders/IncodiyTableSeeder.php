<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Incodiy\Codiy\Models\Admin\System\User;

use Database\Seeders\Includes\Timezone;
use Database\Seeders\Includes\Languages;
use Database\Seeders\Includes\Icons;
use Database\Seeders\Includes\App\Groups;
use Database\Seeders\Includes\App\Users;

/**
 * Created on Mar 6, 2017
 * Time Created : 1:31:32 PM
 * Filename     : IncodiyTableSeeder.php
 *
 * @filesource	IncodiyTableSeeder.php
 *
 * @author		wisnuwidi @Expresscode - 2017
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class IncodiyTableSeeder extends Seeder {
	use Timezone, Languages, Icons;
	use Groups, Users;
	
	public function run() {
		
		// WEB PREFERENCE
		DB::table('base_preference')->delete();
		DB::table('base_preference')->insert([
			'template'         => 'default',
			'session_name'     => 'cbxpsscdeis',
			'session_lifetime' => 1800,
			'meta_author'      => 'Wisnu Widiantoko',
			'email_person'     => 'wisnuwidi',
			'email_address'    => 'wisnuwidi@gmail.com',
			'login_attempts'   => 8,
			'debug'            => false,
			'maintenance'      => false
		]);
		
		// GROUP TABLE
		DB::table('base_group')->delete();
		DB::table('base_group')->insert(['group_name' => 'root' , 'group_info' => 'Super Admin'  , 'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'admin', 'group_info' => 'Administrator', 'active' => 1]);
		
		// USER TABLE
		DB::table('users')->delete();
		User::create(['username' => 'eclipsync', 'fullname' => 'Eclip Sync'   , 'email' => 'eclipsync@gmail.com', 'password' => bcrypt('@eclipsync'), 'cryptcode' => diy_user_cryptcode('eclipsync', 'eclipsync@gmail.com'), 'active' => 1, 'created_by' => 0, 'updated_by' => 0]);
		User::create(['username' => 'admin'    , 'fullname' => 'Administrator', 'email' => 'admin@gmail.com'    , 'password' => bcrypt('@admin')    , 'cryptcode' => diy_user_cryptcode('admin'    , 'admin@gmail.com')    , 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		
		// USER RELATION GROUP TABLE
		DB::table('base_user_group')->delete();
		DB::table('base_user_group')->insert(['user_id'	=> 1, 'group_id' => 1]);
		DB::table('base_user_group')->insert(['user_id'	=> 2, 'group_id' => 2]);
		
		// MODULE TABLE
		DB::table('base_module')->delete();
		DB::table('base_module')->insert(['route_path' => 'dashboard'           ,     'parent_name' => 'Dashboard'    , 'module_name' => 'Dashboard',  'icon' => 'fa fa-dashboard',   'flag_status' => 2, 'active' => 1]);
		DB::table('base_module')->insert(['route_path' => 'system.config.module',     'parent_name' => 'System Config', 'module_name' => 'Module',     'icon' => 'fa fa-gear',        'flag_status' => 1, 'active' => 1]);
		DB::table('base_module')->insert(['route_path' => 'system.config.group' ,     'parent_name' => 'System Config', 'module_name' => 'Group',                                     'flag_status' => 1, 'active' => 1]);
		DB::table('base_module')->insert(['route_path' => 'system.accounts.user',     'parent_name' => 'System Config', 'module_name' => 'User',       'icon' => 'fa fa-user-secret', 'flag_status' => 1, 'active' => 1]);
		DB::table('base_module')->insert(['route_path' => 'system.config.preference', 'parent_name' => 'System Config', 'module_name' => 'Preference',                                'flag_status' => 1, 'active' => 1]);
		DB::table('base_module')->insert(['route_path' => 'system.config.log',        'parent_name' => 'System Config', 'module_name' => 'Log',                                       'flag_status' => 1, 'active' => 1]);
		
		// GROUP PRIVILEGES MODULE TABLE
		DB::table('base_group_privilege')->delete();
		DB::table('base_group_privilege')->insert(['group_id' => 2, 'module_id' => 1, 'admin_privilege' => '8:4:2:1']);
		DB::table('base_group_privilege')->insert(['group_id' => 2, 'module_id' => 2, 'admin_privilege' => '8:4:2:1']);
		DB::table('base_group_privilege')->insert(['group_id' => 2, 'module_id' => 3, 'admin_privilege' => '8:4:2:1']);
		DB::table('base_group_privilege')->insert(['group_id' => 2, 'module_id' => 4, 'admin_privilege' => '8:4:2:1']);
		DB::table('base_group_privilege')->insert(['group_id' => 2, 'module_id' => 5, 'admin_privilege' => '8:2']);
		DB::table('base_group_privilege')->insert(['group_id' => 2, 'module_id' => 6, 'admin_privilege' => '8:2']);
		
		$this->includes();
	}
	
	private function includes() {
		$this->insertTimezone();
		$this->insertLanguages();
		$this->insertIcons();
		
		$this->insertUsers();
		$this->insertGroups();
		$this->insertUserGroup();
	}
}