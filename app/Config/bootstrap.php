<?php

require_once '../Vendor/autoload.php';

include __DIR__ .'/bootstrap/environments.php';


define("COMMENT_LENGTH", 20);
define("ADMIN_EMAIL_ADDRESS","matsumoto@donow.jp");

Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));