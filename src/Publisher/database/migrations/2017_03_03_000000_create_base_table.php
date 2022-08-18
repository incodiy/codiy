<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Created on Mar 3, 2017
 * Time Created	: 11:03:33 PM
 * Filename			: 2017_03_03_000000_create_base_table.php
 *
 * @author		wisnuwidi @Expresscode - 2017
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class CreateBaseTable extends Migration {
		
	private $engine				= 'MyISAM';
	private $is_multiplatform	= false;
	private $platform_table		= false;
	private $platform_key		= false;
	
	public function __construct() {
		$this->is_multiplatform = is_multiplatform();
		if (true === $this->is_multiplatform) {
			$this->platform_table	= diy_config('settings.platform_table');
			$this->platform_key		= diy_config('settings.platform_key');
		}
	}
	
	private function set_engine($table, $engine_name) {
		if (false !== $this->engine) {
			if (true === $this->engine) {
				$table = 'MyISAM';
			} else {
				$table = $engine_name;
			}
		}
	}
	
	private function multiple_main_tables() {
		// Web Preference
		Schema::create('base_preference', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			
			// WEB IDENTITY
			$table->string('title', 30)->nullable();
			$table->string('sub_title', 150)->nullable();
			$table->text('logo')->nullable();
			$table->text('logo_thumb')->nullable();
			$table->text('header')->nullable();
			$table->text('footer')->nullable();
			$table->string('template', 150)->nullable();
			$table->string('language', 150)->nullable();
			$table->string('timezone', 150)->nullable();
			
			// SESSIONS
			$table->string('session_name', '100')->nullable();
			$table->integer('session_lifetime')->nullable();
			
			// SEO
			$table->string('meta_author', 50)->nullable();
			$table->string('meta_title', 255)->nullable();
			$table->text('meta_keywords')->nullable();
			$table->text('meta_description')->nullable();
			
			// CONTACT EMAIL
			$table->string('email_person', 100)->nullable();			
			$table->string('email_address', 255)->nullable();
			
			// SMTP
			$table->string('smtp_host', 255)->nullable();
			$table->integer('smtp_port')->nullable();
			$table->smallInteger('smtp_secure')->nullable();
			$table->string('smtp_user', 255)->nullable();
			$table->string('smtp_password', 255)->nullable();
			
			// MAINTENANCE
			$table->integer('login_attempts')->nullable();
			$table->smallInteger('change_password')->nullable();
			$table->smallInteger('debug')->nullable();
			$table->smallInteger('maintenance')->nullable();
		});
		
		// Users Table
		Schema::create('users', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigInteger('id', true)->unsigned();
			
			$table->string('username', 20);
			$table->string('fullname', 50)->nullable();
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			
			$table->string('gender', 5)->nullable();
			$table->date('birth_date')->nullable();
			$table->string('birth_place', 50)->nullable();
			$table->string('photo')->nullable();
			$table->string('photo_thumb')->nullable();
			$table->string('file_info')->nullable();
			$table->text('address')->nullable();
			$table->string('phone', 20)->nullable();
			$table->string('language', 10)->nullable();
			
			$table->string('timezone', 20)->nullable();
			$table->string('ip_address', 20)->unique()->nullable();
			
			$table->dateTime('reg_date')->nullable();
			$table->dateTime('last_visit_date')->nullable();
			$table->dateTime('past_visit_date')->nullable();
			$table->rememberToken();
			
			$table->smallInteger('change_password')->nullable();
			$table->dateTime('last_change_password_date')->nullable();
			$table->dateTime('expire_date')->nullable();
			
			$table->bigInteger('created_by');
			$table->bigInteger('updated_by')->nullable();
			$table->timestamps();
			
			$table->softDeletes();
			$table->smallInteger('active')->default(0);
			
			$table->index('email');
			$table->index('username');
			$table->index('fullname');
		});
		
		// Groups Table
		Schema::create('base_group', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			
			$table->string('group_name', 30);
			$table->text('group_info')->nullable();
			if (true === $this->is_multiplatform) $table->bigInteger($this->platform_key)->unsigned();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->index('group_name');
			if (true === $this->is_multiplatform) $table->index($this->platform_key);
			
			if (true === $this->is_multiplatform) $table->foreign($this->platform_key)->references('id')->on($this->platform_table)->onUpdate('cascade')->onDelete('cascade');
		});
		
		// Module Table
		Schema::create('base_module', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			
			$table->string('route_path', 50);
			$table->string('parent_name', 50)->nullable();
			$table->string('module_name', 50)->nullable();
			$table->text('module_info')->nullable();
			$table->text('icon')->nullable();
			
			$table->string('menu_sort', 20)->nullable();
			$table->smallInteger('flag_status')->default(0);
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
		});
		
		// Language Table
		Schema::create('base_language', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->string('abbr', 10)->nullable();
			$table->string('lang', 5)->nullable();
			$table->string('language', 150)->nullable();
			$table->string('charset', 10)->nullable();
			
			$table->primary('abbr');
			$table->index('lang');
		});
		
		// Timezone Table
		Schema::create('base_timezone', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('timezone', 30)->nullable();
		});
		
		// Log Activities
		Schema::create('log_activities', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			$table->string('user_id', 10)->nullable();
			$table->string('info');
			$table->text('uri');
			$table->string('method', 10);
			$table->string('ip_address', 30)->nullable();
			$table->string('user_agent')->nullable();
			$table->text('sql_dump')->nullable();
			
			$table->index('user_id');
			$table->index('info');
			
			$table->timestamps();
		});
			
		// Icon
		Schema::create('base_icon', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			$table->string('type');
			$table->string('tag');
			$table->string('class');
			$table->string('label');
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
		
		// POSTAL CODE TABLE
		Schema::create('base_postal_code', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			
			// WEB IDENTITY
			$table->string('province', 80);
			$table->string('regency', 100)->nullable();
			$table->string('sub_district', 100)->nullable();
			$table->string('urban_village', 100)->nullable();
			$table->string('postal_code', 5)->nullable();
			
			$table->index('postal_code');
			$table->timestamps();
		});
		
		// MAINTENANCE
		Schema::create('base_maintenance', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('title', 100);
			$table->text('description');
			$table->text('logo')->nullable();
			$table->text('logo_thumb')->nullable();
			$table->text('image')->nullable();
			$table->text('image_thumb')->nullable();
			$table->string('time_duration', 100);
			$table->smallInteger('subscribe_button')->default(0);
			$table->text('subscribe_text')->nullable();
			$table->smallInteger('status')->default(0);
			
			$table->timestamps();
			$table->softDeletes();
		});
		
		if (true === $this->is_multiplatform) {
			// Messages Table
			Schema::create('mod_messages', function (Blueprint $table) {
				$this->set_engine($table->engine, $this->engine);
				
				$table->increments('id')->unsigned();
				$table->bigInteger($this->platform_key)->unsigned()->nullable();
				$table->bigInteger('user_id')->unsigned()->nullable();
				
				$table->string('from', 250)->nullable();
				$table->string('subject', 250);
				$table->text('message')->nullable();
				$table->smallInteger('read_status')->default(0);
				
				$table->softDeletes();
				$table->timestamps();
				
				$table->index($this->platform_key);
				$table->index('user_id');
				
				$table->foreign($this->platform_key)->references('id')->on($this->platform_table)->onUpdate('cascade')->onDelete('cascade');
				$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			});
		}		
	}
	
	private function multiple_relation_tables() {
		// User Group Table
		Schema::create('base_user_group', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigInteger('id', true)->unsigned();
			
			$table->bigInteger('user_id')->unsigned();
			$table->integer('group_id')->unsigned();
			
			$table->index('user_id');
			$table->index('group_id');
			
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('group_id')->references('id')->on('base_group')->onUpdate('cascade')->onDelete('cascade');
		});
		
		// Group Privilege Table
		Schema::create('base_group_privilege', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigInteger('id', true)->unsigned();
			
			$table->integer('group_id')->unsigned();
			$table->integer('module_id')->unsigned();
			
			$table->string('admin_privilege', 7)->nullable();
			$table->string('index_privilege', 7)->nullable();
			
			$table->index('group_id');
			$table->index('module_id');
			
			$table->foreign('group_id')->references('id')->on('base_group')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('module_id')->references('id')->on('base_module')->onUpdate('cascade')->onDelete('cascade');
		});
	}
	
	private function multiple_modular_tables() {
		Schema::create("{$this->platform_table}_type", function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('title', 80);
			$table->string('description', 80)->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
		
		Schema::create("{$this->platform_table}_land_status", function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('title', 80);
			$table->string('description', 80)->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
		
		// MASJID TABLE
		Schema::create($this->platform_table, function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			
			// WEB IDENTITY
			$table->string('name', 30);
			$table->date('since')->nullable();
			$table->string('email', 50)->nullable();
			$table->string('phone', 20)->nullable();
			
			$table->text('images')->nullable();
			$table->text('images_thumb')->nullable();
			$table->string('latitude', 100)->nullable();
			$table->string('longitude', 100)->nullable();
			$table->string('postal_code', 5)->nullable();
			$table->string('urban_village', 100)->nullable();
			$table->string('sub_district', 100)->nullable();
			$table->string('regency', 100)->nullable();
			$table->string('province', 100)->nullable();
			$table->text('address')->nullable();
			
			$table->string('surface_area', 50)->nullable();
			$table->string('building_area', 50)->nullable();
			$table->string('people_volume', 50)->nullable();
			
			$table->integer('type_id')->unsigned()->nullable();
			$table->integer('land_status_id')->unsigned()->nullable();
			
			$table->text('description')->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
			
			$table->foreign('type_id')->references('id')->on("{$this->platform_table}_type")->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('land_status_id')->references('id')->on("{$this->platform_table}_land_status")->onUpdate('cascade')->onDelete('cascade');
		});
		
		// IMAM SHOLAT TABLE
		Schema::create('mod_sholat_imam', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id', true)->unsigned();
			$table->bigInteger($this->platform_key)->unsigned();
			
			$table->string('fullname', 100);
			$table->string('nickname', 50)->nullable();
			$table->date('birth_date')->nullable();
			$table->string('birth_place', 100)->nullable();
			
			$table->string('email', 150)->nullable();
			$table->string('phone', 15)->nullable();
			$table->text('photo')->nullable();
			$table->text('photo_thumb')->nullable();
			
			$table->smallInteger('active')->default(0);
						
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign($this->platform_key)->references('id')->on($this->platform_table)->onUpdate('cascade')->onDelete('cascade');
		});
		
		// JADWAL SHOLAT TABLE
		Schema::create('mod_sholat_jadwal', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			$table->bigInteger($this->platform_key)->unsigned();
			$table->bigInteger('imam_id')->unsigned();
			
			$table->integer('imam_subuh')->unsigned();
			$table->integer('imam_dzuhur')->unsigned();
			$table->integer('imam_ashar')->unsigned();
			$table->integer('imam_maghrib')->unsigned();
			$table->integer('imam_isya')->unsigned();
			
			$table->text('event_name')->nullable();
			$table->date('open_period')->nullable();
			$table->date('closed_period')->nullable();
			$table->smallInteger('input_method')->default(0);
			$table->smallInteger('general_flag')->default(0);
			
			$table->foreign($this->platform_key)->references('id')->on($this->platform_table)->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('imam_id')->references('id')->on('mod_sholat_imam')->onUpdate('cascade')->onDelete('cascade');
		});
		
		// PENGISI KAJIAN TABLE
		Schema::create('mod_kajian_pengisi', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id', true)->unsigned();
			$table->bigInteger($this->platform_key)->unsigned();
			
			$table->string('fullname', 100);
			$table->string('nickname', 50)->nullable();
			$table->date('birth_date')->nullable();
			$table->string('birth_place', 100)->nullable();
			
			$table->string('email', 150)->nullable();
			$table->string('phone', 15)->nullable();
			$table->text('photo')->nullable();
			$table->text('photo_thumb')->nullable();
			
			$table->smallInteger('active')->default(0);
			
			$table->timestamps();
			$table->softDeletes();
			
			$table->index($this->platform_key);
			
			$table->foreign($this->platform_key)->references('id')->on($this->platform_table)->onUpdate('cascade')->onDelete('cascade');
		});
		
		// JADWAL KAJIAN TABLE
		Schema::create('mod_kajian_jadwal', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			$table->bigInteger($this->platform_key)->unsigned();
			
			$table->bigInteger('pengisi_kajian_id')->unsigned();
			
			$table->string('topic', 10);
			$table->string('image', 280);
			$table->string('file', 280);
			$table->string('file_thumb', 300);
			$table->text('description');
			$table->string('tags', 250);
			
			$table->string('durations', 100);
			$table->dateTime('start_date');
			$table->dateTime('end_date');
			
			$table->dateTime('start_reg');
			$table->dateTime('end_reg');
			
			$table->index('tags');
			$table->index($this->platform_key);
			
			$table->foreign($this->platform_key)->references('id')->on($this->platform_table)->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('pengisi_kajian_id')->references('id')->on('mod_kajian_pengisi')->onUpdate('cascade')->onDelete('cascade');
		});
		
		// APPROVALS MODULES
		$this->approvals_modules();
			
		// About Table
		Schema::create('base_about', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			
			$table->string('title', 50);
			$table->text('content')->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
			
		// Teams Table
		Schema::create('base_teams', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			
			$table->string('name', 50);
			$table->string('job_title', 50);
			$table->text('photo')->nullable();
			$table->text('photo_thumb')->nullable();
			$table->string('gender', 5)->nullable();
			$table->string('facebook', 50)->nullable();
			$table->string('twitter', 50)->nullable();
			$table->string('website', 50)->nullable();
			$table->string('whatsapp', 50)->nullable();
			$table->string('phone', 50)->nullable();
			$table->string('address', 50)->nullable();
			$table->text('content')->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
			
		// Contact Table
		Schema::create('base_contact', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			
			$table->string('title', 50);
			$table->string('name', 50);
			$table->string('email', 50);
			$table->string('phone', 50);
			$table->text('message')->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
			
		// FAQ Table
		Schema::create('base_faq', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			
			$table->text('question')->nullable();
			$table->text('answer')->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
				
		Schema::create('prayer_time_adjustment', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->integer($this->platform_key)->unsigned();
			
			$table->string('prayer_time_name', 25);
			$table->string('time_adjustment', 15);
			
			$table->smallInteger('is_default')->default(0);
			
			$table->timestamps();
			$table->softDeletes();
		});
			
		Schema::create('tapi_client', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('api_key', 255);
			$table->string('app_name', 255);
			$table->smallInteger('status')->default(0);
			
			$table->timestamps();
			$table->softDeletes();
		});
		
		Schema::create('subscribers', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('subscriber_id')->unsigned();
			$table->string('full_name', 50)->nullable();
			$table->string('username', 25)->nullable();
			$table->string('email', 50)->nullable();
			$table->text('password')->nullable();
			$table->date('birthday')->nullable();
			$table->string('birth_place', 50)->nullable();
			$table->string('phone_identifier', 5)->nullable()->default(62);
			$table->string('phone_number', 25)->nullable();
			
			$table->smallInteger('status')->default(1);
			
			$table->timestamps();
			$table->softDeletes();
		});
		
		Schema::create('subscriber_token', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('token_id')->unsigned();
			$table->integer('subscriber_id')->unsigned();
			$table->string('token', 255)->nullable();
			$table->smallInteger('status')->default(1);
			$table->timestamps();
		});
		
		// ZISWAF
		Schema::create('mod_ziswaf_category', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('name', 255)->nullable();
			$table->smallInteger('status')->default(1);
			$table->timestamps();
		});
			
		Schema::create('mod_ziswaf_donation_type', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('name', 255)->nullable();
			$table->smallInteger('status')->default(1);
			$table->timestamps();
		});
			
		Schema::create('mod_ziswaf_status', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('name', 255)->nullable();
			$table->smallInteger('status')->default(1);
			$table->timestamps();
		});
		
		Schema::create('mod_ziswaf', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->integer('subs_id')->unsigned();
			
			$table->string('subscriber_alias', 100)->nullable();
			$table->string('trx_number', 255)->nullable();
			$table->text('payment_docs')->nullable();
			
			$table->integer('category_id')->unsigned();
			$table->integer('donation_type_id')->unsigned();
			
			$table->string('nominal_values', 80)->nullable();
			$table->string('nominal_extension', 25)->nullable();
			
			$table->text('notes')->nullable();
			$table->datetime('transfer_time');
			
			$table->smallInteger('status')->default(1);
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('subs_id')->references('subscriber_id')->on('subscribers')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('category_id')->references('id')->on('mod_ziswaf_category')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('donation_type_id')->references('id')->on('mod_ziswaf_donation_type')->onUpdate('cascade')->onDelete('cascade');
		});
	}
	
	private function approvals_modules() {
		// BANNER TYPE
		Schema::create('base_banners_type', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('title', 80);
			$table->text('description')->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
		
		// BANNER MODULE
		Schema::create('mod_banners', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			$table->bigInteger($this->platform_key)->unsigned();
			
			$table->string('images', 280);
			$table->string('images_thumb', 300);
			
			$table->text('title_1')->nullable();
			$table->text('title_2')->nullable();
			$table->text('title_3')->nullable();
			$table->text('url')->nullable();
			$table->string('tags', 250)->nullable();
			
			$table->integer('banner_type')->unsigned()->nullable();
			$table->smallInteger('relational_flag')->default(0);
			$table->smallInteger('active')->default(0);
			
			$table->timestamps();
			$table->softDeletes();
			
			$table->index($this->platform_key);
			$table->index('banner_type');
			$table->index('tags');
			$table->index('relational_flag');
			
			$table->foreign($this->platform_key)->references('id')->on($this->platform_table)->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('banner_type')->references('id')->on('base_banners_type')->onUpdate('cascade')->onDelete('cascade');
		});
		
		// BANNER APPROVAL TABLE
		Schema::create('base_approval_banners', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id', true)->unsigned();
			
			$table->bigInteger('relation_id')->unsigned();
			$table->integer('request_status')->unsigned();
			$table->integer('update_status')->unsigned()->nullable();
			$table->text('logs')->nullable();
			
			$table->bigInteger('created_by')->nullable();
			$table->bigInteger('updated_by')->nullable();
			$table->timestamps();
			
			$table->index('relation_id');
			
			$table->foreign('relation_id')->references('id')->on('mod_banners')->onUpdate('cascade')->onDelete('cascade');
		});
		
		// BASE BANNER
		Schema::create('base_banners', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			
			$table->index('banner_type');
			$table->bigInteger('approval_id')->unsigned()->nullable();
			
			$table->string('images', 280);
			$table->string('images_thumb', 300);
			
			$table->text('title_1')->nullable();
			$table->text('title_2')->nullable();
			$table->text('title_3')->nullable();
			$table->text('url')->nullable();
			$table->string('tags', 250)->nullable();
			
			$table->integer('banner_type')->unsigned()->nullable();
			$table->smallInteger('active')->default(0);
			
			$table->index('tags');
			
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('banner_type')->references('id')->on('base_banners_type')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('approval_id')->references('id')->on('base_approval_banners')->onUpdate('cascade')->onDelete('cascade');
		});
		
		// ARTICLE TYPE
		Schema::create('base_articles_type', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->increments('id')->unsigned();
			$table->string('title', 80);
			$table->text('description')->nullable();
			
			$table->smallInteger('active')->default(0);
			$table->softDeletes();
			
			$table->timestamps();
		});
		
		// ARTICLE MODULE
		Schema::create('mod_articles', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			$table->bigInteger($this->platform_key)->unsigned();
			
			$table->string('title', 150);
			$table->string('title_uri', 180);
			$table->string('images', 250)->nullable();
			$table->string('images_thumb', 250)->nullable();
			$table->string('video', 250)->nullable();
			$table->string('file', 250)->nullable();
			$table->text('content')->nullable();
			
			$table->string('tags', 250)->nullable();
			$table->string('author', 250)->nullable();
			$table->string('author_alias', 250)->nullable();
			
			$table->bigInteger('hit');
			$table->smallInteger('sticky')->default(0);
			$table->smallInteger('share_button')->default(0);
			$table->smallInteger('enable_comment')->default(0);
			
			$table->integer('article_type')->unsigned()->nullable();
			$table->smallInteger('relational_flag')->default(0);
			$table->smallInteger('active')->default(0);
			
			$table->timestamps();
			$table->softDeletes();
			
			$table->index($this->platform_key);
			$table->index('article_type');
			$table->index('author');
			$table->index('title_uri');
			$table->index('tags');
			$table->index('relational_flag');
			
			$table->foreign($this->platform_key)->references('id')->on($this->platform_table)->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('article_type')->references('id')->on('base_articles_type')->onUpdate('cascade')->onDelete('cascade');
		});
			
		// ARTICLE APPROVAL TABLE
		Schema::create('base_approval_articles', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id', true)->unsigned();
			
			$table->bigInteger('relation_id')->unsigned();
			$table->integer('request_status')->unsigned();
			$table->integer('update_status')->unsigned()->nullable();
			$table->text('logs')->nullable();
			
			$table->bigInteger('created_by')->nullable();
			$table->bigInteger('updated_by')->nullable();
			$table->timestamps();
			
			$table->index('relation_id');
			
			$table->foreign('relation_id')->references('id')->on('mod_articles')->onUpdate('cascade')->onDelete('cascade');
		});
			
		// BASE BANNER
		Schema::create('base_articles', function (Blueprint $table) {
			$this->set_engine($table->engine, $this->engine);
			
			$table->bigIncrements('id')->unsigned();
			$table->bigInteger('approval_id')->unsigned()->nullable();
			
			$table->string('title', 150);
			$table->string('title_uri', 180);
			$table->string('images', 250)->nullable();
			$table->string('images_thumb', 250)->nullable();
			$table->string('video', 250)->nullable();
			$table->string('file', 250)->nullable();
			$table->text('content')->nullable();
			
			$table->string('tags', 250)->nullable();
			$table->string('author', 250)->nullable();
			$table->string('author_alias', 250)->nullable();
			
			$table->bigInteger('hit');
			$table->smallInteger('sticky')->default(0);
			$table->smallInteger('share_button')->default(0);
			$table->smallInteger('enable_comment')->default(0);
			
			$table->integer('article_type')->unsigned()->nullable();
			$table->smallInteger('relational_flag')->default(0);
			$table->smallInteger('active')->default(0);
			
			$table->index('article_type');
			$table->index('author');
			$table->index('title_uri');
			$table->index('tags');
			
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('article_type')->references('id')->on('base_articles_type')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('approval_id')->references('id')->on('base_approval_articles')->onUpdate('cascade')->onDelete('cascade');
		});
	}
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (true === $this->is_multiplatform) {
			$this->multiple_modular_tables();		
			$this->multiple_main_tables();
			$this->multiple_relation_tables();
		} else {
			$this->multiple_main_tables();
			$this->multiple_relation_tables();
		}
	}
	
	private function multiple_drop_schema() {
		// RELATIONAL
		Schema::dropIfExists('base_group_privilege');
		Schema::dropIfExists('base_user_group');
		
		// BASE ( MAIN ) TABLES
		Schema::dropIfExists('base_module');
		Schema::dropIfExists('base_group');
		Schema::dropIfExists('base_language');
		Schema::dropIfExists('base_timezone');
		Schema::dropIfExists('base_postal_code');
		Schema::dropIfExists('base_preference');
		Schema::dropIfExists('base_icon');
		Schema::dropIfExists('base_maintenance');
		if (true === $this->is_multiplatform) {
			Schema::dropIfExists('base_about');
			Schema::dropIfExists('base_teams');
			Schema::dropIfExists('base_contact');
			Schema::dropIfExists('base_faq');
			Schema::dropIfExists('tapi_client');
		}
		
		if (true === $this->is_multiplatform) {
			// APPROVALS
			Schema::dropIfExists('base_banners');
			Schema::dropIfExists('base_approval_banners');
			Schema::dropIfExists('mod_banners');
			Schema::dropIfExists('base_banners_type');
			Schema::dropIfExists('base_articles');
			Schema::dropIfExists('base_approval_articles');
			Schema::dropIfExists('mod_articles');
			Schema::dropIfExists('base_articles_type');
		
			// MODULARS
			Schema::dropIfExists('mod_articles');
			Schema::dropIfExists('mod_sholat_jadwal');
			Schema::dropIfExists('mod_sholat_imam');
			Schema::dropIfExists('mod_kajian_jadwal');
			Schema::dropIfExists('mod_kajian_pengisi');
			Schema::dropIfExists('mod_messages');
		}
		
		Schema::dropIfExists('users');
		Schema::dropIfExists('log_activities');
		
		if (true === $this->is_multiplatform) {
			Schema::dropIfExists('mod_ziswaf');
			Schema::dropIfExists('mod_ziswaf_status');
			Schema::dropIfExists('mod_ziswaf_category');
			Schema::dropIfExists('mod_ziswaf_donation_type');
			
			Schema::dropIfExists('subscribers');
			Schema::dropIfExists('subscriber_token');
			Schema::dropIfExists('prayer_time_adjustment');
			
			// PLATFORM TABLES
			Schema::dropIfExists($this->platform_table);
			Schema::dropIfExists("{$this->platform_table}_type");
			Schema::dropIfExists("{$this->platform_table}_land_status");
		}
	}
	
	/**
	 * Reverse the migrations.
	 * 
	 * @return void
	 */
	public function down() {
		/* 
		if (true === $this->is_multiplatform) {
			$this->multiple_drop_schema();
		}
		 */
		$this->multiple_drop_schema();
	}
}