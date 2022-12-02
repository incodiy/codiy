بِسْمِ ٱللَّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ
-----------------------------

In the name of ALLAH SWT,
-----------------------------

Alhamdulillah because of Allah SWT, this code successfully created piece by piece start from Mar 29, 2017.

This package used for simplifying some code with Laravel framework, hopefully can help us all to build web-app. This code inspired by Muntilan-CMS code developed by .::bit, by the way.


1). INSTALL LARAVEL
--------------------------------------------------------------------------------
	composer create-project --prefer-dist laravel/laravel incodiy


2). COMPOSER JSON FILE SETTING:
--------------------------------------------------------------------------------
    "require": {
        "incodiy/codiy": "dev-master"
    },
    "repositories": [{
    	"type"      : "vcs",
    	"url"       : "git@github.com:incodiy/codiy.git"
    }]

3). COMPOSER UPDATE
--------------------------------------------------------------------------------
	cd incodiy/
	composer update


4). ARTISAN PUBLISH
--------------------------------------------------------------------------------
	php artisan vendor:publish --force


5). CHECK DATABASE FILE
--------------------------------------------------------------------------------
	check file path database/migrations/2014_10_12_000000_create_users_table.php (delete it!)


6). MIGRATION TABLES
--------------------------------------------------------------------------------

	php artisan migrate:refresh --seed


7). CONFIG FILE
--------------------------------------------------------------------------------
	change baseURL path in config file with your own path[config/diy.settings.php line:35]


8). DEMO ACCESS
--------------------------------------------------------------------------------
	username: admin@gmail.com
	password: @admin
	
--------------------------------------------------------------------------------
Visit the demo site at [demo.incodiy.com](https://demo.incodiy.com/login)
