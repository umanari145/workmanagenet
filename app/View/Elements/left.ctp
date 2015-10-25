<!--sidebar-->
<div id="sidebar">

    <!--item_regist-->
    <div id="item_regist">
        <h3><?php echo __('メニュー'); ?></h3>
        <p><?php echo $this->Html->link(__('スタッフ一覧画面に戻る'), array('controller'=>'users','action' => 'index')); ?></p>
        <p><?php echo $this->Html->link(__('スタッフを登録する'), array('controller'=>'users','action' => 'add')); ?></p>
        <p><?php echo $this->Html->link(__('ログアウトする'), array('controller'=>'users','action' => 'logout')); ?></p>
    </div>
    <!--item_regist end -->

</div>
<!--sidebar end-->