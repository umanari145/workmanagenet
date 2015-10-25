<div class="items index">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('新規スタッフ入力画面');?></legend>
	<?php
		echo $this->Form->input('username',array('label'=>'ログインID'));
		echo $this->Form->input('japanese_name',array('label'=>'名前'));
		echo $this->Form->input('email',array('label'=>'メールアドレス'));
		echo $this->Form->input('password',array('label'=>'パスワード'));
	?>
	</fieldset>
<input type="submit" id="ItemAdd" value="登録する">
</div>
<?php echo $this->element('left'); ?>