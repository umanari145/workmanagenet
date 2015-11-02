<div class="items index">
	<h2><?php echo __('部屋一覧');?></h2>

	<table cellpadding="0" cellspacing="0" class="sortable" id="workline">
		<tr>
			<th>スタッフ名</th>
			<th>入室時間</th>
			<th>退出時間</th>
			<th>コメント</th>
		</tr>
        <?php foreach ($workLine as $line): ?>
	<tr>
			<td><?php echo $line['User']['japanese_name']; ?></td>
			<td><?php echo $line['Worktime']['start_time']; ?></td>
			<td><?php echo ( !empty($line['Worktime']['end_time']))? $line['Worktime']['end_time']:"作業中";?></td>
			<td><?php echo $this->Html->link($this->Customize->viewComment($line['Worktime']['report'],COMMENT_LENGTH),array('action' => 'workdetail',$line['Worktime']['id'])); ?>&nbsp;</td>
		</tr>
        <?php endforeach;?>
    <!--items_area end-->
	</table>
</div>
<?php echo $this->element('left'); ?>