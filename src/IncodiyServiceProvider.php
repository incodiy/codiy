<?php
namespace Incodiy\Codiy;

use Illuminate\Support\ServiceProvider;
use Incodiy\Codiy\Controllers\Core\Controller as Codiy;

/**
 * Created on Mar 22, 2018
 * Time Created : 4:52:52 PM
 * Filename :  Incodiy\IncodiyServiceProvider.php
 *
 * @filesource Incodiy\IncodiyServiceProvider.php
 *            
 * @author    wisnuwidi@incodiy.com - 2018
 * @copyright wisnuwidi
 * @email     wisnuwidi@incodiy.com
 */
class IncodiyServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		if ($this->app->routesAreCached()) {
			require_once __DIR__ . '/routes/web.php';
		}

		$this->loadViewsFrom(base_path('resources/views'), 'Codiy');
		$publish_path = __DIR__ . '/Publisher/';
		
		if ($this->app->runningInConsole()) {
			$this->publishes([ 
				"{$publish_path}database/migrations" => database_path('migrations'),
				"{$publish_path}database/seeders"    => database_path('seeders'),
				"{$publish_path}config"              => base_path('config'),
				"{$publish_path}routes"              => base_path('routes'),
				"{$publish_path}app"                 => base_path('app'),
				"{$publish_path}resources/views"     => base_path('resources/views')
			], 'Codiy');
			
			$this->publishes([ 
				"{$publish_path}public" => base_path('public')
			], 'IncoDIY Public Folder');
		}
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->singleton(Codiy::class, function ($app) {
			return new Codiy();
		});
	}
}
