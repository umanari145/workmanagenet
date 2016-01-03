<div class="row">
<?php echo $this->Form->create('User',array('class'=>'form-horizontal col-xs-12 center-block'));?>

	<h2><?php echo __('新規スタッフ入力画面');?></h2>

	<div class="form-group" >
		<?php

			echo $this->Form->input ( 'username', array (
				'div'=>false,

				'label' => array (
						'class' => 'control-label',
						'text' => 'ユーザー名'
				),
				'class' => "form-control"
		) );
		?>
	</div>

	<div class="form-group" >
	<?php
	echo $this->Form->input ( 'chatgirl_id', array (
				'div'=>false,
				'type' =>'text',
				'label' => array (
						'class' => 'control-label',
						'text' => 'DMMのchatgirlID'
				),
				'class' => "form-control"
	) );
	?>
	</div>

	<div class="form-group" >
	<?php
	echo $this->Form->input ( 'japanese_name', array (
			'div'=>false,
			'label' => array (
					'class' => 'control-label',
					'text' => '名前'
			),
			'class' => "form-control"
	) );
	?>
	</div>


	<div class="form-group" >
	<?php
	echo $this->Form->input ( 'email', array (
			'div'=>false,
			'label' => array (
					'class' => 'control-label',
					'text' => 'メールアドレス'
			),
			'class' => "form-control"
	) );
	?>
	</div>

	<div class="form-group" >
	<?php
	echo $this->Form->input ( 'password', array (
			'div'=>false,
			'label' => array (
					'class' => 'control-label',
					'text' => 'パスワード'
			),
			'class' => "form-control"
	) );
	?>
	</div>

	<div class="form-group" >
	<?php
	echo $this->Form->input ( 'transfer_account', array (
			'div'=>false,
			'label' => array (
					'class' => 'control-label',
					'text' => '口座情報'
			),
			'class' => "form-control"
	) );
	?>
	</div>

	<div class='text-center'>
		<?php
			echo $this->Html->link ( '戻る',
			'/admin/userindex',
			array('class'=>'btn btn-primary btn-lg')
			 );
	    ?>
		<input type="submit"  class="btn btn-primary btn-lg" value="登録する">
	</div>

	<?php echo $this->Form->end();?>
</div>