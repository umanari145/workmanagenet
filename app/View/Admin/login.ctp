<div style="width: 500px;">
    <?php echo $this->Session->flash('auth'); ?>
    <?php echo $this->Form->create('Admin'); ?>
    <?php echo $this->Form->input('username'); ?>
    <?php echo $this->Form->input('password'); ?>
    <?php echo $this->Form->submit('ログイン'); ?>
    <?php echo $this->Form->end(); ?>
</div>