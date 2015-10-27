<div style="width: 500px;">
    <?php echo $this->Session->flash('auth'); ?>
    <?php echo $this->Form->create('Admin'); ?>
    <?php echo $this->Form->input('username',array('label'=>"ユーザー名")); ?>
    <?php echo $this->Form->input('password',array('label'=>"パスワード")); ?>
    <?php echo $this->Form->submit('ログイン'); ?>
    <?php echo $this->Form->end(); ?>
</div>