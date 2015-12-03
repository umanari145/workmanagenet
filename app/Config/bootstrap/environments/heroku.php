<?php
Environment::configure('heroku', true, [
], function () {
    // Heroku 用設定
	    App::uses('CakeLog', 'Log');

    define("ENTRY_URL","http://fm-liveworks.herokuapp.com/");

    CakeLog::config('debug', array(
        'engine' => 'File',
        'types' => array('notice', 'info', 'debug'),
        'file' => 'debug',
    ));
    CakeLog::config('error', array(
        'engine' => 'File',
        'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
        'file' => 'error',
    ));

   // Database settings
    if (empty(getenv('CLEARDB_DATABASE_URL'))) {
        throw new CakeException('no DATABASE_URL environment variable');
    }
    $url = parse_url(env('CLEARDB_DATABASE_URL'));

    Configure::write('DATABASE_OPTIONS', [
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => $url['host'],
        'login' => $url['user'],
        'password' => $url['pass'],
        'database' => substr($url['path'], 1),
    ]);

    Configure::write('TEST_DATABASE_OPTIONS', [
        'datasource' => 'Database/Postgres',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'shin',
        'password' => '',
        'database' => 'app_test',
    ]);

    // Cache settings
    Cache::config('default', array('engine' => 'File'));

});