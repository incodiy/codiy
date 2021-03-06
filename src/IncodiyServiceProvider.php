<?php
namespace Incodiy\Codiy;

use Illuminate\Support\ServiceProvider;
use Incodiy\Codiy\Controllers\Core\Controller as Codiy;

/**
 * Created on Mar 22, 2018
 * Time Created	: 4:52:52 PM
 * Filename		: Incodiy\CmsServiceProvider.php
 *
 * @filesource	Incodiy\CmsServiceProvider.php
 *
 * @author		wisnuwidi@gmail.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
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
			//	"{$publish_path}database/migrations"	=> database_path('migrations'),
			//	"{$publish_path}database/seeds"			=> database_path('seeds'),
				"{$publish_path}config"					=> base_path('config'),
				"{$publish_path}routes"					=> base_path('routes'),
				"{$publish_path}app"					=> base_path('app'),
				"{$publish_path}resources/views"		=> base_path('resources/views')
			], 'Codiy');
			$this->publishes(["{$publish_path}public"				=> base_path('public')],		'Codiy Public Folder');
		//	$this->publishes(["{$publish_path}database/factories"	=> database_path('factories')],	'Codiy Model Factory');
		}
	}
	
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {		
		$this->app->singleton (Codiy::class, function ($app) {
			return new Codiy();
		});
	}
}
