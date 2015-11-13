<?php
Environment::configure('development', false, [
], function () {
    // Log settings
    App::uses('CakeLog', 'Log');

    define("ENTRY_URL","http://localhost/fm-liveworks/");

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
    Configure::write('DATABASE_OPTIONS', [
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'work_management',
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