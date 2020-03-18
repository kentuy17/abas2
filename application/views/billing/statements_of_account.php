<table data-toggle="table" id="statements-of-account-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."statements_of_account/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true">Number</th>
			<th data-field="company_name" data-align="left" data-sortable="false">Company</th>
			<th data-field="client_name" data-align="left" data-visible="true" data-sortable="false">Client</th>
			<th data-field="created_on" data-align="left" data-visible="true" data-sortable="true">Created On</th>
			<th data-field="status" data-align="left" data-visible="true" data-sortable="true">Status</th>
			<th data-field="type" data-align="left" data-visible="false" data-sortable="true">Type</th>
			<th data-field="created_by" data-align="left" data-visible="false" data-sortable="true">Created By</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
			'<a class="btn btn-default btn-xs btn-block" href="<?php echo HTTP_PATH.'statements_of_account/view/'; ?>'+row['id']+'">View</a> ',
			'<a class="btn btn-default btn-xs btn-block" href="<?php echo HTTP_PATH.'billing/pay_soa/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Receive Payment</a> '
		].join('');
	}
	$(function () {
		var $table = $('#statements-of-account-table');
		$table.bootstrapTable();
	});
</script>