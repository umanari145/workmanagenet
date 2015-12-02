<?php echo $this->Form->create('User',array('id'=>'roomChange')); ?>
	<p><?php echo $userInfo["japanese_name"]; ?></p>

	<p>
	<?php

		echo $this->Html->link ( __ ( 'ログアウトする' ), array (
			'controller' => 'users',
			'action' => 'logout'),
    		array('data-role' => 'button', 'role' => 'button')
			 );
	?></p>

	<p><?php
			echo $this->Html->link ( __ ( '自分が予約している部屋を見る' ), array (
			'controller' => 'users',
			'action' => 'viewreservelist'),
    		array('data-role' => 'button', 'role' => 'button')
			 );

	?></p>

	<p>
	<?php

		echo $this->Html->link ( __ ( '作業を開始する' ), array (
			'controller' => 'users',
			'action' => 'regist'),
    		array('data-role' => 'button', 'role' => 'button')
			 );
	?></p>

<?php
echo $this->Form->input ( 'room_id',array (
		'label' => "部屋",
		'options' => $roomList,
		'value' => $roomId
) );
?>
<?php echo $this->Form->input('registMood' ,array('type' => 'hidden','id'=>'regist_mood_id'));?>
<?php echo $this->Form->button('部屋を予約する', array('type' => 'button','id'=>'reserve_dialog_button'));?>
<?php echo $this->Form->end(); ?>

<div id="time_question">
<?php echo $this->Form->create('User',array('id'=>'roomReserve')); ?>
<?php echo $this->Form->input('user_id' ,array('type' => 'hidden','value'=>$userInfo['id']));?>
<?php echo $this->Form->input('room_id' ,array('type' => 'hidden','value'=>$roomId));?>
<p>
<?php echo $this->Form->input( 'start_date_pull_down_id', array ('label' => "開始日時",	'div' => false) );	?>
<?php echo $this->Form->input( 'start_hour_pull_down_id', array ('label' => "開始時刻",	'div' => false) );	?>
</p>
<p>
<?php echo $this->Form->input ('end_date_pull_down_id', array ('label' => "終了日時",'div' => false) );?>
<?php echo $this->Form->input ('end_hour_pull_down_id', array ('label' => "終了時刻",'div' => false) );?>
</p>

<p id="reserveRoomErrorMessage" style="color:#ff0000;"></p>
<?php echo $this->Form->end(); ?>
</div>


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
		<?php if($timelineIdArr[$timelinId] === true ): ?>
				×
		<?php else:?>
				〇
		<?php endif;?>
		</td>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>

</table>


