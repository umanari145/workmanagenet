<?php

require_once '../Vendor/autoload.php';
 
include __DIR__ .'/bootstrap/environments.php';

Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));