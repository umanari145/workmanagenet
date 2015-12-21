<?php
CakePlugin::load('Environments');
App::uses('Environment', 'Environments.Lib');

//本番と開発での環境の切り分け
if(isset($_SERVER['CAKE_ENV']) && $_SERVER['CAKE_ENV']){
	 define("CAN_SEND_MAIL",TRUE);
	include dirname(__FILE__) . DS . 'environments' . DS . 'heroku.php';
}else{
	define("CAN_SEND_MAIL",FALSE);
	include dirname(__FILE__) . DS . 'environments' . DS . 'development.php';
}

Environment::start();