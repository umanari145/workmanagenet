<nav class="navbar navbar-inverse">

	<div class="container">

	    <div class="navbar-header">
	    <?php
	echo $this->Html->link ( '勤怠管理アプリ管理画面',
			'/admin/index',
			array('class'=>'navbar-brand')
			 );
	    ?>

	    </div>

		<ul class="nav navbar-nav">
			<!-- 勤務管理 -->
			<li class="dropdown"><a href="#" class="dropdown-toggle"
				data-toggle="dropdown">勤務管理<b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><?php echo $this->Html->link(__('スタッフ勤務履歴'), array('controller'=>'admin','action' => 'userworkdata')); ?></li>
					<li><?php echo $this->Html->link(__('スタッフ稼働履歴管理'), array('controller'=>'admin','action' => 'useractiveworkdata')); ?></li>
					<li><?php echo $this->Html->link(__('スタッフ報酬比率'), array('controller'=>'admin','action' => 'serviceindex')); ?></li>
				</ul></li>

			<!-- 予約部屋一覧 -->
			<li class="dropdown"><a href="#" class="dropdown-toggle "
				data-toggle="dropdown">予約管理<b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><?php echo $this->Html->link(__('部屋予約一覧'), array('controller'=>'admin','action' => 'reserveroom')); ?></li>
				</ul></li>

			<!-- スタッフマスタ -->
			<li class="dropdown"><a href="#" class="dropdown-toggle "
				data-toggle="dropdown">スタッフマスタ<b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><?php echo $this->Html->link(__('スタッフ一覧'), array('controller'=>'admin','action' => 'userindex')); ?></li>
					<li><?php echo $this->Html->link(__('スタッフ登録'), array('controller'=>'admin','action' => 'useradd')); ?></li>
				</ul></li>

			<!-- 部屋マスタ -->
			<li class="dropdown"><a href="#" class="dropdown-toggle "
				data-toggle="dropdown">部屋マスタ<b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><?php echo $this->Html->link(__('部屋一覧'), array('controller'=>'admin','action' => 'roomindex')); ?></li>
					<li><?php echo $this->Html->link(__('部屋登録'), array('controller'=>'admin','action' => 'roomadd')); ?></li>
				</ul></li>

			<li><?php echo $this->Html->link(__('ログアウトする'), array('controller'=>'admin','action' => 'logout')); ?></li>
		</ul>
	</div>
</nav>

