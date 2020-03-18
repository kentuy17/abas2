<?php $this->Mmm->debug($selected_entry); ?>
<?php $this->Mmm->debug($transaction); ?>
<div class="panel panel-default">
	<div class="panel-heading" role="tab">
		<strong>Journal Entry</strong>
		<span class="pull-right">
			<input class="btn btn-xs btn-success" type="button" value="Save" onclick="javascript:checkautoform()" id="submitbtn">
			<input class="btn btn-xs btn-default" type="button" value="Cancel" data-dismiss="modal">
		</span>
	</div>
	<div class="panel-body" role="tab">
		<table data-toggle="table" id="journal-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."accounting/journal"; ?>" data-cache="true" data-side-pagination="server" data-pagination="false" data-click-to-select="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
			<thead>
				<tr>
					<th data-field="checkbox" data-checkbox="true"></th>
					<th data-field="created_on" data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Date Posted</th>
					<th data-field="account_code" data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Account Code</th>
					<th data-field="account_name" data-visible="true" data-align="left" data-sortable="true" class="col-md-2">Account Name</th>
					<th data-field="debit_amount" data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Debit</th>
					<th data-field="credit_amount" data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Credit</th>
					<th data-field="remark" data-visible="true" data-align="left" data-sortable="false" class="col-md-2">Memo</th>
					<th data-field="poster_name" data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Posted by</th>
					<th data-field="checker_name" data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Checked by</th>
					<th data-field="date_checked" data-visible="false" data-align="center" data-sortable="true" class="col-md-1">Date Checked</th>
					<th data-field="operate" data-formatter="operateFormatter" data-halign="center" data-align="center" class="col-md-1">Manage</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<script>
function operateFormatter(value, row, index) {
	return [
		'<a class="btn btn-xs btn-default" href="<?php echo HTTP_PATH.'accounting/journal/view_transaction/'; ?>'+row['transaction_id']+'" title="Edit">View Account Transactions</a> '
	].join('');
}
$(function () {
	var $table = $('#journal-table');
	$table.bootstrapTable();
});
</script>