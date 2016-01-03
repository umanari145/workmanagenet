<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		勤怠管理アプリ管理画面
	</title>
<link rel="stylesheet"
	href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/redmond/jquery-ui.css">

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('jquery-ui.min');
		echo $this->Html->css('bootstrap');
		echo $this->Html->script('jquery-1.11.3.min');
		echo $this->Html->script('sugar.min');
		echo $this->Html->script('custom');
		echo $this->Html->script('user');
		echo $this->Html->script('jquery-ui.min');
		echo $this->Html->script('sortable');
		echo $this->Html->script('bootstrap.min');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>

	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>
	<input type="hidden" id="entry_url" value="<?php echo ENTRY_URL; ?>">
	<div id="footer">
		<p>勤怠管理アプリ管理画面</p>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
