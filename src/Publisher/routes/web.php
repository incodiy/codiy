<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */

Route::get('/', function () {
	return view('welcome');
});

Route::get('/home',        'App\Http\Controllers\Front\Modules\HomeController@index')->name('home');
Route::get('/home/create', 'App\Http\Controllers\Front\Modules\HomeController@create')->name('home');
Route::post('/home/store', 'App\Http\Controllers\Front\Modules\HomeController@store')->name('home');

Route::group(['middleware' => ['web']], function() {

	Auth::routes();

	Route::get('/login',            ['as' => 'login',           'uses' => 'App\Http\Controllers\Admin\System\AuthController@login']);
	Route::post('/login_processor', ['as' => 'login_processor', 'uses' => 'App\Http\Controllers\Admin\System\AuthController@login_processor']);
	Route::get('/logout',           ['as' => 'logout',          'uses' => 'App\Http\Controllers\Admin\System\AuthController@logout']);

	Route::group(['middleware' => 'auth'], function() {

		Route::resource('dashboard', 'App\Http\Controllers\Admin\System\DashboardController');

		// SYSTEM
		Route::group(['prefix' => 'system'], function() {

			// CONFIGURATION
			Route::group(['prefix' => 'config'], function() {
				Route::resource('module',     'App\Http\Controllers\Admin\System\ModulesController',     ['as' => 'system.config']);
				Route::resource('preference', 'App\Http\Controllers\Admin\System\PreferenceController',  ['as' => 'system.config']);
				Route::resource('group',      'App\Http\Controllers\Admin\System\GroupController',       ['as' => 'system.config']);
				
			//	Route::resource('icon',       'App\Http\Controllers\Admin\System\IconController',        ['as' => 'system.config']);
				Route::resource('log',        'App\Http\Controllers\Admin\System\LogController',         ['as' => 'system.config']);
				Route::resource('etl',        'App\Http\Controllers\Admin\System\ExtransloadController', ['as' => 'system.config']);
			});

			// ACCOUNTS
			Route::group(['prefix' => 'accounts'], function() {
				Route::resource('user',       'App\Http\Controllers\Admin\System\UserController',           ['as' => 'system.accounts']);
				Route::resource('import_csv', 'App\Http\Controllers\Admin\System\ImportAccountsController', ['as' => 'system.accounts']);
			});
		});

		Route::group(['prefix' => 'modules'], function() {
			Route::resource('form', 'App\Http\Controllers\Admin\Modules\FormController', ['as' => 'modules']);
			Route::group(['prefix' => 'programs'], function() {
				Route::resource('program_keren',           'App\Http\Controllers\Admin\Modules\Programs\ProgramKerenControllers',          ['as' => 'modules.programs']);
				Route::resource('program_merapi',          'App\Http\Controllers\Admin\Modules\Programs\ProgramMerapiControllers',         ['as' => 'modules.programs']);
				Route::resource('program_low_denom',       'App\Http\Controllers\Admin\Modules\Programs\ProgramLowDenomControllers',       ['as' => 'modules.programs']);
			#	Route::resource('program_natuna_anambas',  'App\Http\Controllers\Admin\Modules\Programs\ProgramNatunaAnambasControllers',  ['as' => 'modules.programs']);
			#	Route::resource('program_trikom_wireless', 'App\Http\Controllers\Admin\Modules\Programs\ProgramTrikomWirelessControllers', ['as' => 'modules.programs']);
			});
			
			Route::group(['prefix' => 'shop'], function() {
				Route::resource('product', 'App\Http\Controllers\Admin\Modules\Shop\ProductController',   ['as' => 'modules.shop']);
				Route::resource('category', 'App\Http\Controllers\Admin\Modules\Shop\CategoryController', ['as' => 'modules.shop']);
			});
		});

		Route::group(['prefix' => 'ajax'], function() {
			Route::post('post',   ['uses' => 'App\Http\Controllers\Admin\System\AjaxController@post',   'as' => 'ajax.post']);			
			Route::post('export', ['uses' => 'App\Http\Controllers\Admin\System\AjaxController@export', 'as' => 'ajax.export']);
		});
	});
});