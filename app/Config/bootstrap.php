<?php

require_once '../Vendor/autoload.php';

include __DIR__ .'/bootstrap/environments.php';

define("COMMENT_LENGTH", 20);


Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));