<div class="items index">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('新規スタッフ入力画面');?></legend>
	<?php
		echo $this->Form->input('username',array('label'=>'ユーザー名'));
		echo $this->Form->input('chatgirl_id',array('type'=>'text','label'=>'DMMのchatgirl_id'));
		echo $this->Form->input('japanese_name',array('label'=>'名前'));
		echo $this->Form->input('email',array('label'=>'メールアドレス' ));
		echo $this->Form->input('password',array('label'=>'パスワード'));
		echo $this->Form->input('transfer_account',array('label'=>'口座情報'));
	?>
	</fieldset>
	<div class="submit">
		<input type="submit" value="登録する">
	</div>
<?php echo $this->Form->end();?>
</div>
<?php echo $this->element('left'); ?>