<div class="items index">
	<h2><?php echo __('勤務記録一覧');?></h2>

    <?php echo $this->Form->create('Admin', array('type' => 'get')); ?>


	<div class="aggregate">
		<label>集計期間</label>
	<?php
	echo $this->Form->input ( 'aggregate_start_date', array (
			'label' => false,
			'id' => 'aggregate_start_date',
			'class' => 'datepicker',
			'div' => false,
			'value'=> $query["aggregate_start_date"],
			'readonly' => 'readonly'
	) );
	?>
	～
	<?php
	echo $this->Form->input ( 'aggregate_end_date', array (
			'label' => false,
			'id' => 'aggregate_end_date',
			'class' => 'datepicker',
			'div' => false,
			'value'=> $query["aggregate_end_date"],
			'readonly' => 'readonly'
	) );
	?>
	</div>

	<?php echo $this->Form->submit('集計する',array('div'=>false ,'name'=>'aggregate')); ?>
	<?php echo $this->Form->submit ( '振込CSV', array ('div'=>false,'name'=>'download'));?>
    <?php echo $this->Form->end(); ?>


	<table cellpadding="0" cellspacing="0" class="sortable" id="workline">
		<tr>
			<th width="15%">スタッフ名</th>
			<th width="18%">開始時間</th>
			<th width="18%">終了時間</th>
			<th width="14%">ポイント</th>
			<th width="35%">報酬</th>
		</tr>
        <?php foreach ($activeWorkData as $line): ?>
	<tr>
			<td><?php echo $line['User']['japanese_name']; ?></td>
			<td><div class="active_time "
					id="begin_<?php echo $line['Activeworktime']['id'];?>"><?php echo $line['Activeworktime']['begin']; ?></div></td>
			<td><div class="active_time "
					id="end_<?php echo $line['Activeworktime']['id'];?>"><?php echo $line['Activeworktime']['end']; ?></div></td>
			<td><?php echo $line['Activeworktime']["point"];?></td>
			<td><?php echo $line['Activeworktime']["reward"];?></td>
		</tr>
        <?php endforeach;?>
    <!--items_area end-->
	</table>

</div>
<?php echo $this->element('left'); ?>