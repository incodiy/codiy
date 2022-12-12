<?php
namespace Database\Seeders\Includes\App;

use Illuminate\Support\Facades\DB;
/**
 * Created on Dec 12, 2022
 * 
 * Time Created : 2:16:26 PM
 *
 * @filesource	Groups.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */

trait Groups {
	
	/**
	 * Insert Group
	 * 
	 * SELECT 
			role_type, 
			region, 
			CONCAT(
				"DB::table('base_group')->insert(['group_name' => '", 
				REPLACE(lower(CONCAT(role_type, '', region)), ' ', ''), 
				"', 'group_info' => '", CONCAT(role_type, ' ', region),
				"', 'active' => 1]);") _query
		FROM `user_data_regional_keren` GROUP BY 1, 2 ORDER BY 1, 2;
	 */
	private function insertGroups() {
		DB::table('base_group')->insert(['group_name' => 'internal',             'group_info' => 'Internal',                'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'hoheadoffice',         'group_info' => 'Head Office',             'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'outlet',               'group_info' => 'Outlet',                  'active' => 1]);
		
		DB::table('base_group')->insert(['group_name' => 'rhbalinusra',          'group_info' => 'RH BALI NUSRA',           'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhcentralsumatera',    'group_info' => 'RH CENTRAL SUMATERA',     'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhjabo1',              'group_info' => 'RH JABO 1',               'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhjabo2',              'group_info' => 'RH JABO 2',               'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhjabo3',              'group_info' => 'RH JABO 3',               'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhkalimantan',         'group_info' => 'RH KALIMANTAN',           'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhnorthcentraljava',   'group_info' => 'RH NORTH CENTRAL JAVA',   'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhnortheastjava',      'group_info' => 'RH NORTH EAST JAVA',      'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhsouthcentraljava',   'group_info' => 'RH SOUTH CENTRAL JAVA',   'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhsoutheastjava',      'group_info' => 'RH SOUTH EAST JAVA',      'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhsouthsumatera',      'group_info' => 'RH SOUTH SUMATERA',       'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhsulawesi',           'group_info' => 'RH SULAWESI',             'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rhwestjava',           'group_info' => 'RH WEST JAVA',            'active' => 1]);
		
		DB::table('base_group')->insert(['group_name' => 'robmbalinusra',        'group_info' => 'ROBM BALI NUSRA',         'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmcentralsumatera',  'group_info' => 'ROBM CENTRAL SUMATERA',   'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmjabo1',            'group_info' => 'ROBM JABO 1',             'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmjabo2',            'group_info' => 'ROBM JABO 2',             'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmjabo3',            'group_info' => 'ROBM JABO 3',             'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmkalimantan',       'group_info' => 'ROBM KALIMANTAN',         'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmnorthcentraljava', 'group_info' => 'ROBM NORTH CENTRAL JAVA', 'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmnortheastjava',    'group_info' => 'ROBM NORTH EAST JAVA',    'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmnorthsumatera',    'group_info' => 'ROBM NORTH SUMATERA',     'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmsouthcentraljava', 'group_info' => 'ROBM SOUTH CENTRAL JAVA', 'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmsouthsumatera',    'group_info' => 'ROBM SOUTH SUMATERA',     'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmsulawesi',         'group_info' => 'ROBM SULAWESI',           'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'robmwestjava',         'group_info' => 'ROBM WEST JAVA',          'active' => 1]);
		
		DB::table('base_group')->insert(['group_name' => 'rsmbalinusra',         'group_info' => 'RSM BALI NUSRA',          'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmcentralsumatera',   'group_info' => 'RSM CENTRAL SUMATERA',    'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmjabo1',             'group_info' => 'RSM JABO 1',              'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmjabo2',             'group_info' => 'RSM JABO 2',              'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmjabo3',             'group_info' => 'RSM JABO 3',              'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmkalimantan',        'group_info' => 'RSM KALIMANTAN',          'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmnorthcentraljava',  'group_info' => 'RSM NORTH CENTRAL JAVA',  'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmnortheastjava',     'group_info' => 'RSM NORTH EAST JAVA',     'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmnorthsumatera',     'group_info' => 'RSM NORTH SUMATERA',      'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmsoutheastjava',     'group_info' => 'RSM SOUTH EAST JAVA',     'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmsouthsumatera',     'group_info' => 'RSM SOUTH SUMATERA',      'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmsulawesi',          'group_info' => 'RSM SULAWESI',            'active' => 1]);
		DB::table('base_group')->insert(['group_name' => 'rsmwestjava',          'group_info' => 'RSM WEST JAVA',           'active' => 1]);
	}
	
	private static function getGroupInfo() {
		$group_id  = DB::select("SELECT id, group_name FROM base_group GROUP BY group_name ORDER BY id");
		$groupInfo = [];
		foreach ($group_id as $group_info) {
			$groupInfo[$group_info->group_name] = $group_info->id;
		}
		return $groupInfo;
	}
	
	private static function getUserInfo() {
		$user_id  = DB::select("SELECT id, email FROM users GROUP BY email ORDER BY id");
		$userInfo = [];
		foreach ($user_id as $user_info) {
			$userInfo[$user_info->email] = $user_info->id;
		}
		return $userInfo;
	}
	
	/**
	 * SELECT
			email,
			REPLACE(lower(CONCAT(role_type, '', region)), ' ', '') role,
			CONCAT("
				DB::table('base_user_group')->insert(['user_id'	=> $userInfo['", email ,"'], 'group_id' => $groupInfo['", REPLACE(lower(CONCAT(role_type, '', region)), ' ', '') ,"']]);
			")
		FROM user_data_regional_keren
		GROUP BY email
	 */
	private function insertUserGroup() {
		$groupInfo = self::getGroupInfo();
		$userInfo  = self::getUserInfo();
		
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['nila.narulita@smartfren.com'], 'group_id' => $groupInfo['hoheadoffice']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['mohammad.wiyanto@smartfren.com'], 'group_id' => $groupInfo['hoheadoffice']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['angelia.silalahi@smartfren.com'], 'group_id' => $groupInfo['hoheadoffice']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['yudanto.budianto@smartfren.com'], 'group_id' => $groupInfo['rhsouthcentraljava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['jimmy.tambunan@smartfren.com'], 'group_id' => $groupInfo['rhbalinusra']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['yoni.indra@smartfren.com'], 'group_id' => $groupInfo['rhcentralsumatera']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['edward.bambang@smartfren.com'], 'group_id' => $groupInfo['rhjabo1']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['vidi.waluya@smartfren.com'], 'group_id' => $groupInfo['rhjabo2']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['ervantoro.hermawan@smartfren.com'], 'group_id' => $groupInfo['rhjabo3']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['teddy.hendarman@smartfren.com'], 'group_id' => $groupInfo['rhkalimantan']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['mochamad.fadillah@smartfren.com'], 'group_id' => $groupInfo['rhnorthcentraljava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['joseph.gultom@smartfren.com'], 'group_id' => $groupInfo['rhnorthcentraljava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['yulianto@smartfren.com'], 'group_id' => $groupInfo['rhnortheastjava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['bongbong.asmarawan@smartfren.com'], 'group_id' => $groupInfo['rhsoutheastjava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['wanda.yudhistira@smartfren.com'], 'group_id' => $groupInfo['rhsouthsumatera']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['lazarus.tembang@smartfren.com'], 'group_id' => $groupInfo['rhsulawesi']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['albert.reza@smartfren.com'], 'group_id' => $groupInfo['rhwestjava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['putu.kanaiya@smartfren.com'], 'group_id' => $groupInfo['robmbalinusra']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['rahmed.junaidi@smartfren.com'], 'group_id' => $groupInfo['robmcentralsumatera']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['hariyanom.utomo@smartfren.com'], 'group_id' => $groupInfo['robmjabo1']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['gala.manurung@smartfren.com'], 'group_id' => $groupInfo['robmjabo2']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['icksan.arief@smartfren.com'], 'group_id' => $groupInfo['robmjabo3']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['dhafi.akbar@smartfren.com'], 'group_id' => $groupInfo['robmkalimantan']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['hardi.triputro@smartfren.com'], 'group_id' => $groupInfo['robmnorthcentraljava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['kristyawan.sunaryo@smartfren.com'], 'group_id' => $groupInfo['robmnortheastjava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['linda.sitompul@smartfren.com'], 'group_id' => $groupInfo['robmnorthsumatera']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['uhda.hedyawan@smartfren.com'], 'group_id' => $groupInfo['robmsouthcentraljava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['renawati.natalia@smartfren.com'], 'group_id' => $groupInfo['robmsouthsumatera']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['putu.darsana@smartfren.com'], 'group_id' => $groupInfo['robmsulawesi']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['handy.tamarudin@smartfren.com'], 'group_id' => $groupInfo['robmwestjava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['komang.susanta@smartfren.com'], 'group_id' => $groupInfo['rsmbalinusra']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['muhamad.alfiana@smartfren.com'], 'group_id' => $groupInfo['rsmcentralsumatera']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['paul.hutagalung@smartfren.com'], 'group_id' => $groupInfo['rsmjabo1']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['mujibudin@smartfren.com'], 'group_id' => $groupInfo['rsmjabo2']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['muh.ilham@smartfren.com'], 'group_id' => $groupInfo['rsmjabo3']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['deri.agustian@smartfren.com'], 'group_id' => $groupInfo['rsmkalimantan']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['dika.nugroho@smartfren.com'], 'group_id' => $groupInfo['rsmnorthcentraljava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['imam.bukori@smartfren.com'], 'group_id' => $groupInfo['rsmnortheastjava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['y.permana@smartfren.com'], 'group_id' => $groupInfo['rsmnorthsumatera']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['deckey.prasetiyo@smartfren.com'], 'group_id' => $groupInfo['rsmsoutheastjava']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['pulo.sinurat@smartfren.com'], 'group_id' => $groupInfo['rsmsouthsumatera']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['muhammad.natsir@smartfren.com'], 'group_id' => $groupInfo['rsmsulawesi']]);
		DB::table('base_user_group')->insert(['user_id'	=> $userInfo['yepi.septiana@smartfren.com'], 'group_id' => $groupInfo['rsmwestjava']]);
	}
}