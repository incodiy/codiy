<?php
/**
 * Created on Mar 30, 2017
 * Time Created	: 11:31:38 AM
 * Filename		: settings.php
 *
 * @filesource	settings.php
 *
 * @author		wisnuwidi @Incodiy - 2017
 * @copyright	wisnuwidi, incodiy
 * @email		wisnuwidi@gmail.com, wisnuwidi@incodiy.com
 */

$multiPlatform		       = false;

$platform                = [];
$platform['type']        = 'single';
$platform['table']       = false;
$platform['key']         = false;
$platform['name']        = false;
$platform['label']       = false;
$platform['route']       = false;

if (true === $multiPlatform) {
	// You can be free to change this variable value
	$platform['type']     = 'multiple';
	$platform['table']    = 'base_masjid';
	$platform['key']      = 'masjid_id';
	$platform['name']     = 'masjid';
	$platform['label']    = 'Masjid';
	$platform['route']    = 'modules.masjid';
}

return [
	'baseURL'             => 'http://localhost/eclipsync/incodiy/.dev/public',
	'index_folder'        => 'public',
	'template'            => 'default',
	'base_template'       => 'assets/templates',
	'base_resources'      => 'assets/resources',
	'app_name'            => 'IncoDIY',
	'app_desc'            => 'CoDIY Application Website from DIY',
	'version'             => 'cbxpsscdeis-v3.0.0',
	'lang'                => 'en',
	'charset'             => 'UTF-8',
	'encryption_key'      => 'IDRIS',
	'encode_separate'     => '|',
	'maintenance'         => false,
	// maintenance: if true, we can bypass with this code[login?as=username|email]
	// this set config file used to make sure if set database maintenance status changed by others or hacked or crashed database
	// so, the application will be read based on this file set.
		
	// PLATFORM
	'platform_type'       => $platform['type'],	// ['single', 'multiple']
	'platform_table'      => $platform['table'],	// if single = false
	'platform_key'        => $platform['key'],	// if single = false
	'platform_name'       => $platform['name'],	// if single = false
	'platform_label'      => $platform['label'],	// if single = false
	'platform_route'      => $platform['route'],	// if single = false
	
	// COPYRIGHT INFO
	'copyrights'          => 'CoDIY & All Muslim in the world',
	'location'            => 'Jakarta',
	'location_abbr'       => 'ID',
	'created_at'          => '2017 - ' . date('Y'),
	'email'               => 'wisnuwidi@gmail.com',
	'website'             => 'codiy.co.id',

	// Meta Tags
	'meta_author'         => 'Wisnu Widiantoko',
	'meta_title'          => 'CoDIY',
	'meta_keywords'       => 'CoDIY',
	'meta_description'    => 'CoDIY Application Website',
	'meta_viewport'       => 'width=device-width, initial-scale=1.0, maximum-scale=1.0',
	'meta_http_equiv'     => [
		'type'             => 'X-UA-Compatible',
		'content'          => 'IE=edge,chrome=1'
	],
	
	'log_activity'        => [
		'run_status'       => 'unexceptions',
		'exceptions'       => [
			'controllers'   => [
				App\Http\Controllers\Admin\System\LogController::class
			],
			'groups' => [
				'admin'
			]
		]
	]
];
