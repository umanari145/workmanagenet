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
		echo $this->Html->css('admin');
		echo $this->Html->css('jquery-ui.min');
		echo $this->Html->script('jquery-1.10.2.min');
		echo $this->Html->script('sugar.min');
		echo $this->Html->script('custom');
		echo $this->Html->script('user');
		echo $this->Html->script('jquery-ui.min');
		echo $this->Html->script('sortable');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

    <script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>

<script>
    $(function() {
        $(".datepicker").datepicker();
      });
    </script>
</head>
<body>
	<div id="container">
		<input type="hidden" id="entry_url" value="<?php echo ENTRY_URL; ?>">
		<div id="header">
			<h1><?php
	echo $this->Html->link ( '勤怠管理アプリ管理画面', array (
			'controller' => 'admin',
			'action' => 'index'
	) );
	?></h1>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<p>勤怠管理アプリ管理画面</p>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
