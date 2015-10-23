<div class="items index">
    <h2><?php echo __('スタッフ一覧');?></h2>

    <div class="item_area clearFix">
        <?php foreach ($users as $user): ?>
		<p><?php echo $user['User']['username']?><?php echo $user['User']['japanese_name']?></p>
        <?php endforeach;?>		
    </div>
    <!--items_area end-->
    
    <div class="paging">
    <?php
    
    echo $this->Paginator->prev('< ' . __('前へ'), array(), null, array('class' => 'prev disabled'));
    echo $this->Paginator->numbers(array('separator' => ''));
    echo $this->Paginator->next(__('次へ') . ' >', array(), null, array('class' => 'next disabled'));
    ?>
    </div>
    <!--paging end-->

</div>