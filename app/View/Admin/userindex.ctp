

<div class="items index">
    <h2><?php echo __('スタッフ一覧');?></h2>

<table cellpadding="0" cellspacing="0" >
	<tr>
		<th>No</th>
   		<th>ログインユーザー名</th>
		<th>名前</th>
		<th>最終ログイン日時</th>
		<th>当月稼働回数</th>
		<th>当月稼働合計時間</th>
		<th>削除</th>
	</tr>

        <?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo $user['User']['id']; ?></td>
		<td><?php echo $this->Html->link($user['User']['username'],array('action' => 'userupdate',$user['User']['id'])); ?>&nbsp;</td>
		<td><?php echo $user['User']['japanese_name']; ?></td>
		<td><?php echo $user["User"]["last_login_time"]; ?></td>
		<td><?php echo $user["User"]["work_count"]; ?>回</td>
		<td><?php echo $this->Customize->convertSecondTohms($user["User"]["work_sum_time"]);?></td>
		<td>
			<?php echo $this->form->postLink('削除', array('action'=>'userdelete', $user['User']['id']),
				 array('class'=>'link-style'), 'このスタッフを本当に削除しますか?');?>
		 </td>
	</tr>
        <?php endforeach;?>
    <!--items_area end-->
</table>
</div>
<?php echo $this->element('left'); ?>