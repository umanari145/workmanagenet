	<?php	if($worktimeStatusArray ["workstatus"] === 2) echo "勤務開始時間　<div id='start_time'>". date( "Y年m月d日 H時i時s秒", strtotime($workTimeData["Worktime"]["start_time"])) ."</div>" ;?>


<div>

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
			echo $this->Html->link ( __ ( 'チャットルームの予約をする' ), array (
			'controller' => 'users',
			'action' => 'reserveroom'),
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

	<div>
		<p><?php echo $this->Form->Create();?></p>
		<p><?php

			echo $this->Form->input ( 'target_month_pulldown_id', array (
				'label' => "対象月",
				'options' => $rewardMonthList,
				'value' => $targetMonthVal
		) );
		?>
<?php if( !empty($montlyReward)):?>

		<dl>
			<dt>総稼働時間:</dt>
			<dd>
				<span id="active_work_sum_time"><?php
	echo $this->Customize->convertSecondTohms ( $montlyReward [0] [0] ["active_time"] );
	?></span>
			</dd>

			<dt>総獲得ポイント:</dt>
			<dd>
				<span id="active_work_sum_point">
	<?php
	echo $montlyReward [0] [0] ["sum_point"] . "ポイント";
	?></span>
			</dd>

			<dt>総獲得報酬:</dt>
			<dd>
				<span id="active_work_sum_reward">
	<?php
	echo $montlyReward [0] [0] ["sum_reward"] . "円";
	?></span>
			</dd>
		</dl>
<?php endif;?>
	<?php echo $this->Form->end();?>
</div>



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
