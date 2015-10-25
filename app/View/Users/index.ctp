

<div class="items index">
    <h2><?php echo __('スタッフ一覧');?></h2>

<table cellpadding="0" cellspacing="0" >
	<tr>
		<th>No</th>
   		<th>ログインユーザー名</th>
		<th>名前</th>
		<th>メールアドレス</th>
	</tr>

        <?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo $user['User']['id']; ?></td>
		<td><?php echo $this->Html->link($user['User']['username'],array('action' => 'update',$user['User']['id'])); ?>&nbsp;</td>
		<td><?php echo $user['User']['japanese_name']; ?></td>
		<td><?php echo $user['User']['email']; ?></td>
	</tr>
        <?php endforeach;?>
    <!--items_area end-->
</table>

    <div class="paging">
    <?php

    echo $this->Paginator->prev('< ' . __('前へ'), array(), null, array('class' => 'prev disabled'));
    echo $this->Paginator->numbers(array('separator' => ''));
    echo $this->Paginator->next(__('次へ') . ' >', array(), null, array('class' => 'next disabled'));
    ?>
    </div>
    <!--paging end-->

</div>
<?php echo $this->element('left'); ?>