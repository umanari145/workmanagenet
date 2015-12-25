<div class="items index">

	<h2><?php echo __('稼働履歴CSVファイルアップロード');?></h2>

		<?php echo $this->Form->create('Activeworktime',array('type'=>'file')); ?>
		<?php echo $this->Form->input('CsvFile',array('label'=>'','type'=>'file')); ?>
		<?php echo $this->Form->submit('Upload',array('name'=>"upload")); ?>
	<hr>
	<p>
		<label>集計期間</label>
		<p>
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
		</p>
		<div class="submit">
			<?php echo $this->Form->submit('集計する',array('div'=>false ,'name'=>'aggregate')); ?>
			<?php echo $this->Form->submit( '振込CSV', array ('div'=>false,'name'=>'download'));?>
		</div>
	</p>
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
			<td><?php echo $this->Number->currency( $line['Activeworktime']["point"],'JPY',array('wholeSymbol'=>"",'places'=>0));?></td>
			<td><?php echo $this->Number->currency( $line['Activeworktime']["reward"],'JPY',array('places'=>0)); ?>
			</td>
		</tr>
        <?php endforeach;?>
    <!--items_area end-->
	</table>

</div>
<?php echo $this->element('left'); ?>