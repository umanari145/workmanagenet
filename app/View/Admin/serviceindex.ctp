<?php echo $this->Html->script('serviceratio.editable'); ?>
<div class="items index service_ratio">
	<h2><?php echo __('サービス名一覧');?></h2>
	<div class="service_ratio_menu">
		<input type="button" id="service_add_button_id" class="service_add"
			value="追加する" />
	</div>

<div id="service_add">
<?php echo $this->Form->create('Service',array('url' => array(
		'controller' => 'admin', 'action' => 'serviceadd')));
	?>
<?php echo $this->Form->input('service_name',array('label'=>"サービス名",'style'=>"width:150px;"));?>
<?php echo $this->Form->input('ratio',array('label'=>"報酬比率(%)",'style'=>"width:60px;",'maxlength'=>"3"));?>
<?php echo $this->Form->submit("登録する"); ?>
<?php echo $this->Form->end(); ?>
</div>

	<table cellpadding="0" cellspacing="0">
		<tr>
			<th width="40%">サービス名</th>
			<th width="40%">報酬比率(%)</th>
			<th width="20%">削除</th>
		</tr>
        <?php foreach ($serviceList as $service): ?>
		<tr>
			<td><div class="service_name"><?php echo $service['Service']['service_name']; ?></div></td>
			<td><div class="edit service_ratio_point"
					id="service_<?php echo $service['Service']['id'];?>"><?php echo $service['Service']['ratio']; ?></div></td>
			<td>
			<?php echo $this->form->postLink('削除', array('action'=>'servicedelete', $service['Service']['id']),
				 array('class'=>'link-style'), 'このサービスを本当に削除しますか?');?>
			 </td>
		</tr>
		<?php endforeach; ?>
</table>

</div>

