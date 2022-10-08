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
				Route::resource('module',     'App\Http\Controllers\Admin\System\ModulesController',    ['as' => 'system.config']);
				Route::resource('preference', 'App\Http\Controllers\Admin\System\PreferenceController', ['as' => 'system.config']);
				Route::resource('group',      'App\Http\Controllers\Admin\System\GroupController',      ['as' => 'system.config']);
				
			//	Route::resource('icon',       'App\Http\Controllers\Admin\System\IconController',       ['as' => 'system.config']);
				Route::resource('log',        'App\Http\Controllers\Admin\System\LogController',        ['as' => 'system.config']);
		    
			});

			// ACCOUNTS
			Route::group(['prefix' => 'accounts'], function() {
				Route::resource('user', 'App\Http\Controllers\Admin\System\UserController', ['as' => 'system.accounts']);
			});
		});

		Route::group(['prefix' => 'modules'], function() {
			Route::resource('form', 'App\Http\Controllers\Admin\Modules\FormController', ['as' => 'modules']);
			Route::group(['prefix' => 'mantra'], function() {
				Route::resource('kpi_distributors', 'App\Http\Controllers\Admin\Modules\Mantra\KpiDistributorControllers', ['as' => 'modules.mantra']);
			});
		});

		Route::group(['prefix' => 'ajax'], function() {
			Route::post('post', ['uses' => 'App\Http\Controllers\Admin\System\AjaxController@post', 'as' => 'ajax.post']);
		});
	});
});