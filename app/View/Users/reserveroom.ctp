<?php echo $this->Form->create('User',array('id'=>'room_change_form')); ?>

<?php
echo $this->Form->input ( 'room_id', array (
		'label' => "部屋",
		'options' => $roomList,
		'value' => $roomId
) );
?>
<?php echo $this->Form->end(); ?>

<input type="hidden" id="user_id" value="<?php echo $userInfo["id"];?>" />

<div id="time_question">
<?php echo $this->Form->create('User',array('id'=>'room_reserve_form'));?>
	<input type="hidden" name="form_mode" value="room_reserve" />
	<input type="hidden" id="regist_room_id" name="regist_room_id" value="" />

	<p id="regist_date_disp_id">

	</p>

	<p id="start_time_label_id">
		<label >開始時刻</label>
		<p id="start_time_disp_id"></p>
		<input type="hidden" name="start_time" id="start_time_hidden_id">
	</p>

	<p id="end_time_label_id">
		<label >終了時刻</label>
		<select id="end_time_pull_down_id" name="end_time">
		</select>
	</p>

<?php echo $this->Form->end(); ?>
</div>

<table >

	<tr>
		<th width="120px;">
			時間
		</th>
		<?php foreach ($weekArr as $day):?>
		<th>
			<?php
			echo $day;
			?>
		</th>
		<?php endforeach; ?>
	<tr>


	<?php foreach ($masterTimelineArr as $timelinId => $timelabel):?>
	<tr>
		<th>
			<?php echo $timelabel;?>
		</th>
		<?php  foreach ( $roomScheduleeArr["timeline"] as $dateLabel =>$timelineIdArr ):?>
		<td>
		<?php if($timelineIdArr[$timelinId] === true ): ?>
				予約済
		<?php else:?>
				<input type="button" id="timeline_<?php echo $dateLabel . "_". $timelabel; ?>" class="dialog" value="空き">
		<?php endif;?>
		</td>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>

</table>

