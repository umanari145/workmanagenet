<div class="items index">
<?php echo $this->Form->create('User',array('id'=>'roomChange')); ?>

<?php
echo $this->Form->input ( 'reserve_period',array (
		'class' =>'reserve_info_data',
		'label' => "期間",
		'options' => $weekPullDownList,
		'value' => $startPeriod
) );
?>
<?php echo $this->Form->end(); ?>
<table class="table">

	<!-- 一番上の日にちのヘッダ -->
	<tr>
		<th width="120px;">時間</th>
		<?php foreach ($weekArr as $day):?>
		<!-- 部屋数分だけ日付の横列をつなぐ -->
		<th colspan="<?php echo count($roomScheduleeArr);?>">
			<?php
			echo $this->Customize->showDataWithWeek($day);
			?>
		</th>
		<?php endforeach; ?>
	</tr>

	<tr>
	<!--部屋名のヘッダ-->
		<td>
		<!--  時間のヘッダなので空 -->
		</td>
		<!-- 日にち分部屋のヘッダを入れる -->
		<?php for( $i=0;$i<7;$i++):?>

			<?php foreach ( $roomScheduleeArr as $roomNo0 => $roomScheduleeArrByEachRoom0 ):?>
			<td>
				<!-- 部屋名 -->
				<?php echo $roomScheduleeArrByEachRoom0['room_name']; ?>
			</td>
			<?php endforeach;?>

		<?php endfor;?>
	</tr>



	<!-- 具体的なスケジュール -->
	<?php foreach ($masterTimelineArr as $timelinId => $timelabel):?>
	<tr>
		<!-- 時間の出力 -->
		<th>
			<?php echo $timelabel;?>
		</th>

		<!-- 部屋ごとにスケジュールを出力 -->
		<?php foreach ( $roomScheduleeArr as $roomVal => $roomScheduleeArrByEachRoom ):?>
			<!-- 一部屋ごとの出力 -->
			<?php  foreach ( $roomScheduleeArrByEachRoom["timeline"] as $dateLabel =>$timelineIdArr ):?>
				<td>
					<?php if($timelineIdArr[$timelinId] !== false ): ?>
						<?php echo $timelineIdArr[$timelinId]; ?>
					<?php else:?>
							〇
					<?php endif;?>
				</td>
			<?php endforeach; ?>

		<?php endforeach; ?>

	</tr>
	<?php endforeach; ?>

</table>
</div>


