<div class="items index">
	<h2><?php echo __('勤務コメント');?></h2>

	<?php echo $workdetail["Worktime"]["id"];?>
	<?php echo $workdetail["User"]["japanese_name"];?>
	<?php echo $workdetail["Room"]["room_name"]; ?>
	<?php echo $workdetail["Worktime"]["start_time"];?>
	<?php echo $workdetail["Worktime"]["end_time"];?>
	<?php echo $workdetail["Worktime"]["report"];?>
</div>
<?php echo $this->element('left'); ?>