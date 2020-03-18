<div  class="tab-content">
	<a href="<?php echo HTTP_PATH.'accounting/transactions/add'; ?>" data-toggle="modal" data-target="#modalDialog" class="btn btn-info" >
		<span class="glyphicon glyphicon glyphicon-briefcase"></span> Add Transaction
	</a>
	<table data-toggle="table" id="transaction-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."accounting/transactions"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-sort-name="date" data-sort-order="desc" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-filter='true'>
		<thead>
			<tr>
				<th data-field="date" data-visible="true" data-align="center" data-sortable="false" class="col-md-3">Created On</th>
				<th data-field="company_name" data-visible="true" data-align="center" data-sortable="false" class="col-md-4">Company</th>
				<th data-field="remark" data-visible="true" data-align="center" data-sortable="false" class="col-md-4">Memo</th>
				<th data-field="balance" data-visible="true" data-align="center" data-sortable="false" class="col-md-4">Balance?</th>
				<th data-field="operate" data-formatter="operateFormatter" data-halign="center" data-align="center" class="col-md-1">Manage</th>
			</tr>
		</thead>
	</table>
</div>
<script>

function operateFormatter(value, row, index) {
	return [
		'<a class="btn btn-xs btn-default" href="<?php echo HTTP_PATH.'accounting/journal/view_transaction/'; ?>'+row['id']+'">View Transaction Entries</a> '
	].join('');
}
$(function () {
	var $table = $('#transaction-table');
	$table.bootstrapTable();
});
</script>