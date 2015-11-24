	<?php	if($worktimeStatusArray ["workstatus"] === 2) echo "勤務開始時間　<div id='start_time'>". date( "Y年m月d日 H時i時s秒", strtotime($workTimeData["Worktime"]["start_time"])) ."</div>" ;?>




<div>



	<p><?php echo $userInfo["japanese_name"]; ?></p>
	<div>
<?php if( !empty($montlyReward)):?>
	<span>稼働データ(<?php echo $montlyReward[0][0]["last_active_time"];?> 付け )</span>

		<dl>
			<dt>総稼働時間:</dt>
			<dd>
	<?php
	echo $this->Customize->convertSecondTohms ( $montlyReward [0] [0] ["active_time"] );
	?>
	</dd>

			<dt>総獲得ポイント:</dt>
			<dd>
	<?php
	echo $montlyReward [0] [0] ["sum_point"] . "ポイント";
	?>
	</dd>

			<dt>総獲得報酬:</dt>
			<dd>
	<?php
	echo $montlyReward [0] [0] ["sum_reward"] . "円";
	?>
	</dd>
		</dl>
<?php endif;?>
</div>

	<p>
	<?php

		echo $this->Html->link ( __ ( 'ログアウトする' ), array (
			'controller' => 'users',
			'action' => 'logout'),
    		array('data-role' => 'button', 'role' => 'button')
			 );
	?></p>

    <?php echo $this->Form->create('Worktime'); ?>

    <?php
				if ($worktimeStatusArray ["workstatus"] === 2) {
					echo $this->Form->input ( 'id', array (
							'type' => 'hidden',
							'value' => $workTimeData ['Worktime'] ['id']
					) );
				}
				?>

    <?php
				echo $this->Form->input ( 'user_id', array (
						'type' => 'hidden',
						'value' => $userInfo ['id']
				) );
				?>

	<?php

	switch ($worktimeStatusArray ["workstatus"]) {
		case 1 :

			$roomVal = ( !empty($lastUsedRoomId))? $lastUsedRoomId:"";

			echo $this->Form->input ( 'room_id', array (
					'label' => "部屋",
					'options' => $roomList,
					'value' => $roomVal
			) );
			break;
		case 2 :
			echo $this->Html->tag ( 'span', "", array (
					'class' => 'welcome'
			) );
			break;
		default :
			break;
	}

	?>

    <?php
				echo $this->Form->input ( 'workstatus', array (
						'type' => 'hidden',
						'value' => $worktimeStatusArray ['workstatus']
				) );
				?>


    <?php
				if ($worktimeStatusArray ["workstatus"] === 2) {

					echo $this->Form->input ( 'report', array (
							'type' => 'textarea',
							'label' => "報告",
							'value' => ""
					) );
				}
				?>
    <?php echo $this->Form->button($worktimeStatusArray["statusMessage"],$worktimeStatusArray["javascript"]); ?>

    <?php echo $this->Form->end(); ?>
     </div>

</div>
