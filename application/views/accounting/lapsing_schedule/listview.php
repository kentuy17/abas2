<h2 id="glyphicons-glyphs">Lapsing Schedules</h2>
<?php if($this->Abas->checkPermissions("accounting|add_lapsing_schedule",FALSE)){?>
	<a href="<?php echo HTTP_PATH.'accounting/lapsing_schedule/add';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" data-keyboard="false">Add</a>
<?php }?>
<a href="<?php echo HTTP_PATH."accounting/lapsing_schedule/listview/" ?>" class="btn btn-dark force-pageload">Refresh</a>
<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/lapsing_schedule/load'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">ID</th>
			<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
			<th data-field="company_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
			<th data-field="year" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Year</th>
			<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
			<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
			<th data-field="modified_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Modified By</th>
			<th data-field="modified_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Modified On</th>
			<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
			<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
		</tr>
	</thead>
</table>
<script>
	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'accounting/lapsing_schedule/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});
</script>