<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Incodiy\Codiy\Database\Seeders\IncodiyTableSeeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		$this->call(IncodiyTableSeeder::class);
	}
}
