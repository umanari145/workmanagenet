<script type="text/javascript">
$(function() {
	getDataTables("roomApi");
})
</script>

<h2><?php echo __('部屋一覧');?></h2>
<div class="row">
	<table id="recordTable" cellpadding="0" cellspacing="0"
		class="table table-condensed dataTable">

		<thead>
			<tr>
				<th class="col-xs-1">No</th>
				<th>部屋名</th>
				<th class="col-xs-1"><input type="checkbox" class="select_all" size></th>
			</tr>
		</thead>
		<!--items_area end-->
	</table>
</div>
<div class="row">
	<div class="pull-right">
		<input type="button" class="btn btn-primary" value="削除">
	</div>
</div>