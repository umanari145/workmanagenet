<div class="items index">
<?php echo $this->Form->create('Room');?>
	<fieldset>
		<legend><?php echo __('新規部屋入力画面');?></legend>
	<?php
		echo $this->Form->input('room_name',array('label'=>'部屋名'));
		echo $this->Form->input('note',array('label'=>'備考','type' => 'textarea'));
	?>
	</fieldset>
	<div class="submit">
		<input type="submit" value="登録する">
	</div>
<?php echo $this->Form->end();?>
</div>
<?php echo $this->element('left'); ?>