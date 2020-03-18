<table data-toggle="table" id="journal-table" class="table table-bordered table-striped table-hover" data-url="<?php echo $_SERVER['REQUEST_URI']; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-sort-name="created_on" data-sort-order="desc" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="company_name" data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Company</th>
			<th data-field="created_on" data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Date</th>
			<th data-field="department_name" data-visible="false" data-align="center" data-sortable="false" class="col-md-1">Department</th>
			<th data-field="vessel_name" data-visible="false" data-align="center" data-sortable="false" class="col-md-1">Vessel</th>
			<th data-field="contract_id" data-visible="false" data-align="center" data-sortable="false" class="col-md-1">Contract</th>
			<th data-field="account_code" data-visible="true" data-align="center" data-sortable="false" class="col-md-1">Account Code</th>
			<th data-field="account_name" data-visible="true" data-align="left" data-sortable="false" class="col-md-2">Account Name</th>
			<th data-field="transaction_id" data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Transaction ID</th>
			<th data-field="reference_table" data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Reference Table</th>
			<th data-field="reference_id" data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Reference Number</th>
			<th data-field="remark" data-visible="true" data-align="left" data-sortable="false" class="col-md-2">Particulars</th>
			<th data-field="posted_by" data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Posted by</th>
			<th data-field="debit_amount" data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Debit</th>
			<th data-field="credit_amount" data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Credit</th>
			<th data-field="operate" data-formatter="operateFormatter" data-halign="center" data-align="center" class="col-md-1">Manage</th>
		</tr>
	</thead>
</table>
<script>
function operateFormatter(value, row, index) {
	return [
		'<a class="force-pageload btn btn-xs btn-default" href="<?php echo HTTP_PATH.'accounting/journal/view_transaction/'; ?>'+row['transaction_id']+'" title="Edit">View Account Transactions</a> '
	].join('');
}
$(function () {
	var $table = $('#journal-table');
	$table.bootstrapTable();
});
</script>