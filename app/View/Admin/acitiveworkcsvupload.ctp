
<div class="items index">
	<h2><?php echo __('稼働履歴CSVファイルアップロード');?></h2>

	<?php echo $this->Form->create('Activeworktime',array('type'=>'file')); ?>
	<?php echo $this->Form->input('CsvFile',array('label'=>'','type'=>'file')); ?>
	<?php echo $this->Form->end('Upload'); ?>

</div>
<?php echo $this->element('left'); ?>