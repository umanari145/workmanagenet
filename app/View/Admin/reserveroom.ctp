<div class="items index">
<?php echo $this->Form->create('User',array('id'=>'roomChange')); ?>

<?php
echo $this->Form->input ( 'room_id',array (
		'label' => "部屋",
		'options' => $roomList,
		'value' => $roomId
) );
?>

<?php echo $this->Form->end(); ?>

<table>

	<tr>
		<th width="120px;">時間</th>
		<?php foreach ($weekArr as $day):?>
		<th>
			<?php
			echo $this->Customize->showDataWithWeek($day);
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
		<?php if($timelineIdArr[$timelinId] !== false ): ?>
		<?php echo $timelineIdArr[$timelinId]; ?>
		<?php else:?>
				〇
		<?php endif;?>
		</td>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>

</table>
</div>
<?php echo $this->element('left'); ?>

