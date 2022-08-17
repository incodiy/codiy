Bismillah.

This package used for simplifying some code with Laravel framework. This code inspired by Muntilan-CMS code developed by .::bit.


1). INSTALL LARAVEL
--------------------------------------------------------------------------------
	composer create-project --prefer-dist laravel/laravel eclipsync


2). COMPOSER JSON FILE SETTING:
--------------------------------------------------------------------------------
    "require": {
        "incodiy/codiy": "dev-master"
    },
    "repositories": [{
    	"type"      : "vcs",
    	"url"       : "git@github.com:incodiy/codiy.git",
    	"options"   : {"symlink": false}
    }],
	// FOR LOCAL PATH
	"repositories": [{
		"type"      : "path",
		"url"       : "C:/your/local/path/incodiy/codiy",
		"options"   : {
			"symlink" : true
		}
	}],

3). COMPOSER UPDATE
--------------------------------------------------------------------------------
	cd eclipsync/
	composer update


4). ARTISAN PUBLISH
--------------------------------------------------------------------------------
	php artisan vendor:publish --force
	

5). AUTH FILE SETTING
--------------------------------------------------------------------------------
	'providers' => [
		'users' => [
			'driver' => 'eloquent',
			'model'  => App\Models\Admin\System\User::class,
		],
	],


6). DUMP AUTOLOAD
--------------------------------------------------------------------------------
	composer dump-autoload


7). SET APP SERVICE PROVIDER
--------------------------------------------------------------------------------
Rename \public Folder to \page Folder

	public function register() {
		$this->app->bind('path.public', function() {
			return base_path('page');
		});
	}


8). MIGRATION TABLES
--------------------------------------------------------------------------------

	php artisan migrate:refresh --seed
