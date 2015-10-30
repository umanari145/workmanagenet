<?php
require_once '../Vendor/autoload.php';
 
include './bootstrap/enviroments.php';
 
Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));