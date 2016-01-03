<?php echo $this->Form->create('Room',array('class'=>'form-horizontal'));?>
<h2><?php echo __('新規部屋入力画面');?></h2>

<div class="form-group" >
<?php
		echo $this->Form->input ( 'room_name', array (
			'div'=>false,

			'label' => array (
					'class' => 'control-label',
					'text' => '部屋名'
			),
			'class' => "form-control"
	) );
	?>
</div>

<div class="form-group" >
	<?php

		echo $this->Form->input ( 'note', array (
			'div'=>false,
			'type'=>'textarea',
			'label' => array (
					'class' => 'control-label',
					'text' => '備考'
			),
			'class' => "form-control"
	) );
	?>
</div>

<div class='text-center'>
		<?php
			echo $this->Html->link ( '戻る',
			'/admin/roomindex',
			array('class'=>'btn btn-primary btn-lg')
			 );
	    ?>

	<input type="submit"  class="btn btn-primary btn-lg" value="登録する">
</div>

<?php echo $this->Form->end();?>

