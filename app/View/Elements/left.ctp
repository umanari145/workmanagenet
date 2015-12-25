<!--sidebar-->
<div id="sidebar">

    <!--item_regist-->
    <div id="item_regist">
        <h3><?php echo __('メニュー'); ?></h3>
        <p><?php echo $this->Html->link(__('スタッフ一覧'), array('controller'=>'admin','action' => 'userindex')); ?></p>
        <p><?php echo $this->Html->link(__('スタッフ登録'), array('controller'=>'admin','action' => 'useradd')); ?></p>
        <p><?php echo $this->Html->link(__('スタッフ勤務履歴'), array('controller'=>'admin','action' => 'userworkdata')); ?></p>
        <p><?php echo $this->Html->link(__('スタッフ稼働履歴管理'), array('controller'=>'admin','action' => 'useractiveworkdata')); ?></p>
        <p><?php echo $this->Html->link(__('スタッフ報酬比率'), array('controller'=>'admin','action' => 'serviceindex')); ?></p>
        <p><?php echo $this->Html->link(__('部屋一覧'), array('controller'=>'admin','action' => 'roomindex')); ?></p>
        <p><?php echo $this->Html->link(__('部屋登録'), array('controller'=>'admin','action' => 'roomadd')); ?></p>
        <p><?php echo $this->Html->link(__('部屋予約一覧'), array('controller'=>'admin','action' => 'reserveroom')); ?></p>
        <p><?php echo $this->Html->link(__('ログアウトする'), array('controller'=>'admin','action' => 'logout')); ?></p>
    </div>
    <!--item_regist end -->

</div>
<!--sidebar end-->