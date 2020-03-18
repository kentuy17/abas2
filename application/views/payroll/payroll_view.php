<h2>Payroll List</h2>
<?php if($this->Abas->checkPermissions("payroll|add", false)): ?>
<a href="<?php echo HTTP_PATH.'payroll/add_payroll'; ?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" class="btn btn-success"> Add</a>
<?php endif; ?>
<a href="<?php echo HTTP_PATH.'payroll'; ?>" class="btn btn-dark force-pageload">Refresh</a>
	<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'payroll_history/view_all_payrolls'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="false" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="false" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
				<th data-field="company_name" data-align="center" data-sortable="false" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="payroll_coverage" data-align="center" data-sortable="false" data-filter-control="select" data-filter-strict-search="false">Period</th>
				<th data-field="payroll_date" data-align="center" data-sortable="true"  data-filter-control="input" data-filter-strict-search="false">Date</th>
				<th data-field="status" data-align="center" data-sortable="false" data-filter-control="select" data-filter-strict-search="false">Status</th>
				<!--<th data-field="locked" data-formatter="lockingFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Locking</th>-->
				<th data-field="created_on" data-visible="false" data-halign="center" data-align="center" data-filter-control="input" data-filter-strict-search="false">Created On</th>
				<th data-field="created_by" data-visible="false" data-halign="center" data-align="center" data-filter-control="input" data-filter-strict-search="false">Created By</th>
				<th data-field="approved_on" data-visible="false" data-halign="center" data-align="center" data-filter-control="input" data-filter-strict-search="false">Approved On</th>
				<th data-field="approved_by"  data-visible="false"data-halign="center" data-align="center" data-filter-control="input" data-filter-strict-search="false">Approved By</th>
				<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Details</th>
			</tr>
		</thead>
	</table>
<script>
$(function () {
	var $table = $('#payroll-table');
	$table.bootstrapTable();
});
function operateFormatter(value, row, index) {
	id = row['id']; //alert(id);
	return [
		'<a class="btn btn-info btn-xs force-pageload" href="<?php echo HTTP_PATH; ?>payroll_history/view/'+id+'">',
			'View',
		'</a>'
	].join('');
}
function lockingFormatter(value, row, index) {
	id = row['id']; //alert(id);
	// alert(value);
	if(value=='locked') {
		return [
			'<span class="glyphicon glyphicon-lock"></span>'
		].join('');
	}
	<?php if($this->Abas->checkPermissions("payroll|locking",false)): ?>
		if(value=='') {
			return [
				'<a title="Lock Payroll" class="btn btn-danger btn-xs" onclick="lockingConfirm('+id+')">',
					'Lock',
				'</a>'
			].join('');
		}
	<?php endif; ?>
}

function lockingConfirm(id) {
	toastr['warning']('<a href="<?php echo HTTP_PATH; ?>payroll_history/lock/'+id+'" class="btn btn-danger btn-sm">Lock Payroll</a>', "Are you sure?");
}


</script>
