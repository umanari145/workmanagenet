<?php

$currentDir = __DIR__;
$currentDirUp = dirname($currentDir);

require_once $currentDirUp . '/Vendor/autoload.php';

include __DIR__ .'/bootstrap/environments.php';


define("COMMENT_LENGTH", 20);
define("ADMIN_EMAIL_ADDRESS","info@plink-live.com");
define("USER_REWARD_RATIO", 0.3 );

Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));

CakePlugin::load(array('Migrations'));