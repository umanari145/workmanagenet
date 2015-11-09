<div date-role="fieldcontain">
	<fieldset data-role="controlgroup">
	<p><?php echo $userInfo["japanese_name"]; ?></p>

	<span id="start_time">
	<?php
	if($worktimeStatusArray ["workstatus"] === 2){
		echo $workTimeData["Worktime"]["start_time"];
	}
	?>

	</span>

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
    <?php echo $this->Form->submit($worktimeStatusArray["statusMessage"],$worktimeStatusArray["javascript"]); ?>

    <?php echo $this->Form->end(); ?>
     </fieldset>
</div>
