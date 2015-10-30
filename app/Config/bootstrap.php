<?php
require_once '../Vendor/autoload.php';
 
include './bootstrap/environments.php';
 
Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));