<div style="width: 500px;">
	<p></p>

	<p>
	<?php

		echo $this->Html->link ( __ ( 'ログアウトする' ), array (
			'controller' => 'admin',
			'action' => 'logout'),
    		array('class' => 'btn btn-primary', 'role' => 'button')
			 );
	?></p>

    <?php echo $this->Form->create('Admin'); ?>
	<?php
		echo $this->Form->input('username',array('label'=>'ユーザー名'));
		echo $this->Form->input('email',array('label'=>'メールアドレス'));
		echo $this->Form->input('password',array('label'=>'パスワード'));
	?>
    <?php echo $this->Form->submit(); ?>
    <?php echo $this->Form->end(); ?>
</div>