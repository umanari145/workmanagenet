
<div date-role="fieldcontain">
	<fieldset data-role="controlgroup">
    <?php echo $this->Session->flash('auth'); ?>
    <?php echo $this->Form->create('User',array('data-ajax'=>"false")); ?>
    <?php echo $this->Form->input('username',array('label'=>"ユーザー名")); ?>
    <?php echo $this->Form->input('password',array('label'=>"パスワード")); ?>
    <?php echo $this->Form->submit('ログイン'); ?>
    <?php echo $this->Form->end(); ?>
     </fieldset>
</div>
