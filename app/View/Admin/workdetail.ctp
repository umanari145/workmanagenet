<div class="items index">
	<h2><?php echo __('勤務コメント');?></h2>

	<dl>
		<dt>スタッフ名</dt>
		<dd><?php echo $workdetail["User"]["japanese_name"]; ?>	</dd>
		<dt>使用部屋名</dt>
		<dd><?php echo $workdetail["Room"]["room_name"]; ?></dd>
		<dt>勤務開始時間</dt>
		<dd><?php echo $workdetail["Worktime"]["start_time"]; ?></dd>
		<dt>勤務終了時間</dt>
		<dd><?php echo $workdetail["Worktime"]["end_time"]; ?>	</dd>
		<dt>稼働時間</dt>
		<dd><?php echo $this->Customize->viewState($workdetail["Worktime"]["workstatus"],$workdetail["Worktime"]["working_time"]); ?>	</dd>
		<dt>報告</dt>
		<dd><?php echo $workdetail["Worktime"]["report"]; ?></dd>
	</dl>
</div>
