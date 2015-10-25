<div style="width: 500px;">
	<p><?php echo $userInfo["japanese_name"]; ?></p>
    <?php echo $this->Form->create('Worktime'); ?>

    <?php
	if ( $worktimeStatusArray["workstatus"] === 2 ) {
		echo $this->Form->input ( 'id', array (
				'type' => 'hidden',
				'value'=>$workTimeData['Worktime']['id']
		) );
	}
    ?>

    <?php
    echo $this->Form->input('user_id',array(
    'type' => 'hidden',
    'value'=>$userInfo['id']
    )); ?>

	<?php

	switch ($worktimeStatusArray ["workstatus"]) {
		case 1:
			echo $this->Form->input ( 'room_id', array (
					'label' => "部屋",
					'options' => $roomList,
					'value' => ""
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
    echo $this->Form->input('workstatus',array(
    'type' => 'hidden',
    'value'=>$worktimeStatusArray['workstatus']
    )); ?>

    <?php
    switch ($worktimeStatusArray ["workstatus"]) {
    	case 1 :

    echo $this->Form->input('start_time',array(
    'type' => 'hidden',
    'value'=> date('Y-m-d H:i:s')
    ));

    break;
    case 2 :

    echo $this->Form->input('end_time',array(
    	'type' => 'hidden',
    	'value'=> date('Y-m-d H:i:s')
    	));

    	break;
    	default :
    		break;
    	}

    ?>

    <?php
	if ( $worktimeStatusArray["workstatus"] === 2 ) {
		echo $this->Form->input ( 'report', array (
				'type' => 'textarea',
				'label' => "報告",
				'value' => ""
		) );
	}
	?>

    <?php echo $this->Form->submit($worktimeStatusArray["statusMessage"]); ?>
    <?php echo $this->Form->end(); ?>
</div>