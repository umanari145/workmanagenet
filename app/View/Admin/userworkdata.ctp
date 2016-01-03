<?php echo $this->Html->script('editable'); ?>
<div class="items index">
	<h2><?php echo __('勤務記録一覧');?></h2>

	<?php echo $this->Form->create('Worktime'); ?>

	<table cellpadding="0" cellspacing="0" class="sortable" id="workline">
		<tr>
			<th width="15%">スタッフ名</th>
			<th width="18%">入室時間</th>
			<th width="18%">退出時間</th>
			<th width="14%">稼働時間</th>
			<th width="35%">コメント</th>
		</tr>
        <?php foreach ($workLine as $line): ?>
	<tr>
			<input type="hidden" class="work_time_id" id="worktime_id_<?php echo $line['Worktime']['id'];?>">
			<td><?php echo $line['User']['japanese_name']; ?></td>
			<td><div class="work_time edit" id="start_<?php echo $line['Worktime']['id'];?>"><?php echo $line['Worktime']['start_time']; ?></div></td>
			<td><div class="work_time edit" id="end_<?php echo $line['Worktime']['id'];?>"><?php echo ( !empty($line['Worktime']['end_time']))?$line['Worktime']['end_time']:"";?></div></td>
			<td><div class="work_time working_time" id="working_<?php echo $line['Worktime']['id'];?>" ><?php echo $this->Customize->viewState($line["Worktime"]["workstatus"], $line['Worktime']['working_time']); ?></div></td>
			<td><?php echo $this->Html->link($this->Customize->viewComment($line['Worktime']['report'],COMMENT_LENGTH),array('action' => 'workdetail',$line['Worktime']['id'])); ?>&nbsp;</td>
		</tr>
        <?php endforeach;?>
    <!--items_area end-->
	</table>

	<?php echo $this->Form->end();  ?>

</div>