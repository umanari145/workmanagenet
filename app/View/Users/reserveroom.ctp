<?php
$room = $roomScheduleeArr[1];


echo $this->Form->input ( 'room_id', array (
		'label' => "部屋",
		'options' => $roomList,
		'value' => $roomId
) );

?>


<input type="hidden" id="user_id" value="<?php echo $userInfo["id"];?>" />

<div id="time_question">

	<input type="hidden" id="user_id" value="<?php echo $userInfo["id"];?>" />
	<input type="hidden" id="regist_room_id" value="" />
	<input type="hidden" id="regist_start_timeline_id" value="" />
	<input type="hidden" id="regist_date_id" value="" />

	<p id="regist_date_disp_id"></p>

	<p id="start_time_label_id">

	</p>
	<?php
	echo $this->Form->input ( 'end_time_id', array (
			'label' => "終了時間",
			'options' =>$masterTimelineArr,
			'value' => ""
	) );
	?>

	<p id="full_work_time_disp_id"></p>

</div>

<table >

	<tr>
		<th width="100px;">
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
		<?php  foreach ( $room["timeline"] as $dateLabel =>$timelineIdArr ):?>
		<td>
		<?php if($timelineIdArr[$timelinId] === true ): ?>
				予約済
		<?php else:?>
				<input type="button" id="timeline_<?php echo $dateLabel . "_" . $timelinId . "_". $timelabel; ?>" class="dialog" value="空き">
		<?php endif;?>
		</td>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>

</table>

