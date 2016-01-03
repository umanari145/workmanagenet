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
					'readonly' => 'readonly'
			) );
			?>
		</p>

		<p>
			<?php
			echo $this->Form->input ( 'user_id',array (
					'class' =>'',
					'label' => "スタッフ",
					'options' => $userList,
			) );
			?>

			<?php
			echo $this->Form->input ( 'account_statues',array (
					'class' =>'',
					'label' => "支払い状態",
					'options' => $accountStatusList,
			) );
			?>
		</p>
		<div class="submit" >
			<?php echo $this->Form->submit('検索する',array('div'=>false ,'name'=>'search')); ?>
		</div>

		<div class="submit">
			<?php echo $this->Form->button('全てチェック',array('div'=>false ,'id'=>'all_check')); ?>
			<?php echo $this->Form->button('全てチェックを外す',array('div'=>false ,'id'=>'all_check_out')); ?>
			<?php echo $this->Form->submit('支払済にする',array('div'=>false ,'name'=>'payment')); ?>

			<?php echo $this->Form->submit( '振込CSV', array ('div'=>false,'name'=>'download'));?>
		</div>
	</p>

	<table cellpadding="0" cellspacing="0" class="sortable" id="workline">
		<tr>
			<th width="5%">

			</th>
			<th width="15%">スタッフ名</th>
			<th width="18%">開始時間</th>
			<th width="18%">終了時間</th>
			<th width="14%">ポイント</th>
			<th width="15%">報酬</th>
			<th width="15%">支払状態</th>
		</tr>
        <?php foreach ($activeWorkData as $line): ?>
		<tr>
			<td><input type="checkbox" class="account_status_check_box" name="account_check[]" value="<?php echo $line['Activeworktime']['id']; ?>" ></td>
			<td><?php echo $line['User']['japanese_name']; ?></td>
			<td><div class="active_time "
					id="begin_<?php echo $line['Activeworktime']['id'];?>"><?php echo $line['Activeworktime']['begin']; ?></div></td>
			<td><div class="active_time "
					id="end_<?php echo $line['Activeworktime']['id'];?>"><?php echo $line['Activeworktime']['end']; ?></div></td>
			<td><?php echo $this->Number->currency( $line['Activeworktime']["point"],'JPY',array('wholeSymbol'=>"",'places'=>0));?></td>
			<td><?php echo $this->Number->currency( $line['Activeworktime']["reward"],'JPY',array('places'=>0)); ?>
			</td>
			<td>
				<?php echo $accountStatusList[$line['Activeworktime']['account_statues']]; ?>
			</td>

		</tr>
        <?php endforeach;?>
    <!--items_area end-->
	</table>
	<?php echo $this->Form->end(); ?>
</div>
