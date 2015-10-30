<?php
require_once __DIR__ . '/../vendor/autoload.php';
 
include __DIR__ . '/bootstrap/environments.php';
 
Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));