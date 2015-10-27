

<div class="items index">
    <h2><?php echo __('部屋一覧');?></h2>

<table cellpadding="0" cellspacing="0" >
	<tr>
		<th>No</th>
   		<th>部屋名</th>
   		<th>削除</th>
	</tr>

        <?php foreach ($rooms as $room): ?>
	<tr>
		<td><?php echo $room['Room']['id']; ?></td>
		<td><?php echo $this->Html->link($room['Room']['room_name'],array('action' => 'roomupdate',$room['Room']['id'])); ?>&nbsp;</td>
		<td>
			<?php echo $this->form->postLink('削除', array('action'=>'roomdelete', $room['Room']['id']),
				 array('class'=>'link-style'), 'この部屋を本当に削除しますか?');?>
		 </td>
	</tr>
        <?php endforeach;?>
    <!--items_area end-->
</table>
</div>
<?php echo $this->element('left'); ?>