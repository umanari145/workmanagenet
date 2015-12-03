
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
			echo $this->Html->link ( __ ( '出勤の予約をする' ), array (
			'controller' => 'users',
			'action' => 'reserveroom'),
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

<table>

	<div>
		<p>予約している部屋一覧</p>
	</div>
	<tr>
		<th>部屋</th>
		<th>開始日時</th>
		<th>終了日時</th>
		<th>削除</th>
	<tr>

	<?php foreach ($reserveList as $reserve):?>
	<tr>
		<td>
			<?php echo $reserve['Room']['room_name']; ?>
		</td>
		<td>
			<?php echo $reserve['Reserve']['start_reserve_date']; ?>
		</td>
		<td>
			<?php echo $reserve['Reserve']['end_reserve_date']; ?>
		</td>
		<td>
			<?php echo $this->form->postLink('削除', array('action'=>'viewreservelist', $reserve['Reserve']['id']),
				 array('class'=>'link-style'), 'この予約を本当に削除しますか?');?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>

