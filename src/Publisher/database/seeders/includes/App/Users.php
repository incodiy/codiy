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
		User::create(['username' => '50002099', 'fullname' => 'NILA NARULITA', 'email' => 'nila.narulita@smartfren.com', 'password' => bcrypt('@50002099'), 'cryptcode' => diy_user_cryptcode('50002099', 'nila.narulita@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002108', 'fullname' => 'MOHAMMAD WIYANTO', 'email' => 'mohammad.wiyanto@smartfren.com', 'password' => bcrypt('@50002108'), 'cryptcode' => diy_user_cryptcode('50002108', 'mohammad.wiyanto@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003132', 'fullname' => 'ANGELIA SILALAHI', 'email' => 'angelia.silalahi@smartfren.com', 'password' => bcrypt('@88003132'), 'cryptcode' => diy_user_cryptcode('88003132', 'angelia.silalahi@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10003634', 'fullname' => 'YUDANTO ANDRI BUDIANTO', 'email' => 'yudanto.budianto@smartfren.com', 'password' => bcrypt('@10003634'), 'cryptcode' => diy_user_cryptcode('10003634', 'yudanto.budianto@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88001550', 'fullname' => 'JIMMY TAMBUNAN', 'email' => 'jimmy.tambunan@smartfren.com', 'password' => bcrypt('@88001550'), 'cryptcode' => diy_user_cryptcode('88001550', 'jimmy.tambunan@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10003298', 'fullname' => 'YONI INDRA PRESDIANA SETIADI SH', 'email' => 'yoni.indra@smartfren.com', 'password' => bcrypt('@10003298'), 'cryptcode' => diy_user_cryptcode('10003298', 'yoni.indra@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10001515', 'fullname' => 'EDWARD BAMBANG', 'email' => 'edward.bambang@smartfren.com', 'password' => bcrypt('@10001515'), 'cryptcode' => diy_user_cryptcode('10001515', 'edward.bambang@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003025', 'fullname' => 'VIDI FIRDAUS', 'email' => 'vidi.waluya@smartfren.com', 'password' => bcrypt('@88003025'), 'cryptcode' => diy_user_cryptcode('88003025', 'vidi.waluya@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10003234', 'fullname' => 'ERVANTORO HERMAWAN', 'email' => 'ervantoro.hermawan@smartfren.com', 'password' => bcrypt('@10003234'), 'cryptcode' => diy_user_cryptcode('10003234', 'ervantoro.hermawan@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10003250', 'fullname' => 'TEDDY DUMYATI SE', 'email' => 'teddy.hendarman@smartfren.com', 'password' => bcrypt('@10003250'), 'cryptcode' => diy_user_cryptcode('10003250', 'teddy.hendarman@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10002057', 'fullname' => 'MOCHAMAD FADILLAH', 'email' => 'mochamad.fadillah@smartfren.com', 'password' => bcrypt('@10002057'), 'cryptcode' => diy_user_cryptcode('10002057', 'mochamad.fadillah@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002222', 'fullname' => 'JOSEPH MARTHINUS GULTOM', 'email' => 'joseph.gultom@smartfren.com', 'password' => bcrypt('@50002222'), 'cryptcode' => diy_user_cryptcode('50002222', 'joseph.gultom@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003639', 'fullname' => 'YULIANTO', 'email' => 'yulianto@smartfren.com', 'password' => bcrypt('@88003639'), 'cryptcode' => diy_user_cryptcode('88003639', 'yulianto@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003233', 'fullname' => 'BONG BONG ASMARAWAN ST', 'email' => 'bongbong.asmarawan@smartfren.com', 'password' => bcrypt('@88003233'), 'cryptcode' => diy_user_cryptcode('88003233', 'bongbong.asmarawan@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002516', 'fullname' => 'WANDA YUDHISTIRA', 'email' => 'wanda.yudhistira@smartfren.com', 'password' => bcrypt('@50002516'), 'cryptcode' => diy_user_cryptcode('50002516', 'wanda.yudhistira@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88001593', 'fullname' => 'LAZARUS BAKO TEMBANG', 'email' => 'lazarus.tembang@smartfren.com', 'password' => bcrypt('@88001593'), 'cryptcode' => diy_user_cryptcode('88001593', 'lazarus.tembang@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10003291', 'fullname' => 'ALBERT REZA LESMANA', 'email' => 'albert.reza@smartfren.com', 'password' => bcrypt('@10003291'), 'cryptcode' => diy_user_cryptcode('10003291', 'albert.reza@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002925', 'fullname' => 'I PUTU CHIO KANAIYA', 'email' => 'putu.kanaiya@smartfren.com', 'password' => bcrypt('@50002925'), 'cryptcode' => diy_user_cryptcode('50002925', 'putu.kanaiya@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88002640', 'fullname' => 'RAHMED EL JUNAIDI', 'email' => 'rahmed.junaidi@smartfren.com', 'password' => bcrypt('@88002640'), 'cryptcode' => diy_user_cryptcode('88002640', 'rahmed.junaidi@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003235', 'fullname' => 'E. HARIYANOM PANDU UTOMO', 'email' => 'hariyanom.utomo@smartfren.com', 'password' => bcrypt('@88003235'), 'cryptcode' => diy_user_cryptcode('88003235', 'hariyanom.utomo@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003288', 'fullname' => 'GALA INDRA MANURUNG', 'email' => 'gala.manurung@smartfren.com', 'password' => bcrypt('@88003288'), 'cryptcode' => diy_user_cryptcode('88003288', 'gala.manurung@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003125', 'fullname' => 'ICKSAN ARIEF K', 'email' => 'icksan.arief@smartfren.com', 'password' => bcrypt('@88003125'), 'cryptcode' => diy_user_cryptcode('88003125', 'icksan.arief@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003127', 'fullname' => 'DHAFI AKBAR RAMADHAN PUTRA', 'email' => 'dhafi.akbar@smartfren.com', 'password' => bcrypt('@88003127'), 'cryptcode' => diy_user_cryptcode('88003127', 'dhafi.akbar@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003247', 'fullname' => 'HARDI AGUS TRI PUTRO', 'email' => 'hardi.triputro@smartfren.com', 'password' => bcrypt('@88003247'), 'cryptcode' => diy_user_cryptcode('88003247', 'hardi.triputro@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002628', 'fullname' => 'KRISTYAWAN ADI SUNARYO S.AB.', 'email' => 'kristyawan.sunaryo@smartfren.com', 'password' => bcrypt('@50002628'), 'cryptcode' => diy_user_cryptcode('50002628', 'kristyawan.sunaryo@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002368', 'fullname' => 'LINDA YASMIN SITOMPUL', 'email' => 'linda.sitompul@smartfren.com', 'password' => bcrypt('@50002368'), 'cryptcode' => diy_user_cryptcode('50002368', 'linda.sitompul@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50003021', 'fullname' => 'UHDA HEDYAWAN', 'email' => 'uhda.hedyawan@smartfren.com', 'password' => bcrypt('@50003021'), 'cryptcode' => diy_user_cryptcode('50003021', 'uhda.hedyawan@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10000512', 'fullname' => 'RENAWATI NATALIA', 'email' => 'renawati.natalia@smartfren.com', 'password' => bcrypt('@10000512'), 'cryptcode' => diy_user_cryptcode('10000512', 'renawati.natalia@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002446', 'fullname' => 'PUTU DARSANA', 'email' => 'putu.darsana@smartfren.com', 'password' => bcrypt('@50002446'), 'cryptcode' => diy_user_cryptcode('50002446', 'putu.darsana@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88001970', 'fullname' => 'HANDY TAMARUDIN', 'email' => 'handy.tamarudin@smartfren.com', 'password' => bcrypt('@88001970'), 'cryptcode' => diy_user_cryptcode('88001970', 'handy.tamarudin@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10004747', 'fullname' => 'I KOMANG FERRY SUSANTA', 'email' => 'komang.susanta@smartfren.com', 'password' => bcrypt('@10004747'), 'cryptcode' => diy_user_cryptcode('10004747', 'komang.susanta@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002229', 'fullname' => 'MUHAMMAD FAJRAN ALFIANA', 'email' => 'muhamad.alfiana@smartfren.com', 'password' => bcrypt('@50002229'), 'cryptcode' => diy_user_cryptcode('50002229', 'muhamad.alfiana@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88000667', 'fullname' => 'PAUL TULUS HUTAGALUNG', 'email' => 'paul.hutagalung@smartfren.com', 'password' => bcrypt('@88000667'), 'cryptcode' => diy_user_cryptcode('88000667', 'paul.hutagalung@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002562', 'fullname' => 'MUJIBUDIN', 'email' => 'mujibudin@smartfren.com', 'password' => bcrypt('@50002562'), 'cryptcode' => diy_user_cryptcode('50002562', 'mujibudin@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10003895', 'fullname' => 'MUHAMAD ILHAM', 'email' => 'muh.ilham@smartfren.com', 'password' => bcrypt('@10003895'), 'cryptcode' => diy_user_cryptcode('10003895', 'muh.ilham@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002686', 'fullname' => 'DERI AGUSTIAN', 'email' => 'deri.agustian@smartfren.com', 'password' => bcrypt('@50002686'), 'cryptcode' => diy_user_cryptcode('50002686', 'deri.agustian@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002055', 'fullname' => 'DIKA ERRA NUGROHO', 'email' => 'dika.nugroho@smartfren.com', 'password' => bcrypt('@50002055'), 'cryptcode' => diy_user_cryptcode('50002055', 'dika.nugroho@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88001469', 'fullname' => 'IMAM BUKORI', 'email' => 'imam.bukori@smartfren.com', 'password' => bcrypt('@88001469'), 'cryptcode' => diy_user_cryptcode('88001469', 'imam.bukori@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88003186', 'fullname' => 'YOGA PERMANA', 'email' => 'y.permana@smartfren.com', 'password' => bcrypt('@88003186'), 'cryptcode' => diy_user_cryptcode('88003186', 'y.permana@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10001782', 'fullname' => 'DECKEY PRASETIYO', 'email' => 'deckey.prasetiyo@smartfren.com', 'password' => bcrypt('@10001782'), 'cryptcode' => diy_user_cryptcode('10001782', 'deckey.prasetiyo@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '10003717', 'fullname' => 'PULO HOTTUA SINURAT', 'email' => 'pulo.sinurat@smartfren.com', 'password' => bcrypt('@10003717'), 'cryptcode' => diy_user_cryptcode('10003717', 'pulo.sinurat@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '88001599', 'fullname' => 'MUHAMMAD NATSIR', 'email' => 'muhammad.natsir@smartfren.com', 'password' => bcrypt('@88001599'), 'cryptcode' => diy_user_cryptcode('88001599', 'muhammad.natsir@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
		User::create(['username' => '50002725', 'fullname' => 'YEPI SEPTIANA', 'email' => 'yepi.septiana@smartfren.com', 'password' => bcrypt('@50002725'), 'cryptcode' => diy_user_cryptcode('50002725', 'yepi.septiana@smartfren.com'), 'active' => 1, 'created_by' => 1, 'updated_by' => 1]);
	}
}