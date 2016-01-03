<div class="items index service_ratio">
    <h2><?php echo __('報酬比率');?></h2>
<?php echo $this->Form->create('Service');?>
<table>
		<tr>
			<th width="50%">サービス名</th>
			<th width="50%">報酬比率</th>
		</tr>
        <?php foreach ($serviceList as $service): ?>
		<tr>
			<td><?php echo $service['Service']['service_name']; ?></td>

			<td><input type="text" style="text-align:right;width:50px;" maxlength="3" size="1" name="<?php echo $service['Service']['id']; ?>" value="<?php echo $service['Service']['ratio'];?>">%</td>
		</tr>
		<?php endforeach; ?>
</table>
<?php echo $this->Form->submit('保存');?>
<?php echo $this->Form->end();?>
</div>
