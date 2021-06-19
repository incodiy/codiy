<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'App\Http\Controllers\Front\Modules\HomeController@index')->name('home');
Route::get('/home/create', 'App\Http\Controllers\Front\Modules\HomeController@create')->name('home');
Route::post('/home/store', 'App\Http\Controllers\Front\Modules\HomeController@store')->name('home');

Route::group(['middleware' => ['web']], function () {
	
	Auth::routes();
	
	Route::get	('/login',				['as' => 'login',			'uses' => 'App\Http\Controllers\Admin\System\AuthController@login']);
	Route::post	('/login_processor',	['as' => 'login_processor',	'uses' => 'App\Http\Controllers\Admin\System\AuthController@login_processor']);
	Route::get	('/logout',				['as' => 'logout',			'uses' => 'App\Http\Controllers\Admin\System\AuthController@logout']);
	
    Route::group(['middleware' => 'auth'], function() {
    	Route::group(['prefix' => 'modules'], function() {
    		Route::resource('form', 'App\Http\Controllers\Admin\Modules\FormController', ['as' => 'modules']);
    	});
    });
});