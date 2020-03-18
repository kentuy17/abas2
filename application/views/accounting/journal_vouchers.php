
<table data-toggle="table" id="journal-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."accounting/journal/view_vouchers"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-sort-name="created_on" data-sort-order="desc" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="id" data-visible="false" data-align="right" data-sortable="true" class="col-md-1">Transaction Code No.</th>
			<th data-field="control_number" data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Control Number</th>
			<th data-field="company_name" data-visible="true" data-align="left" data-sortable="false" class="col-md-2">Company</th>
			<th data-field="entry_count" data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Entries</th>
			<th data-field="remark" data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Remark</th>
			<th data-field="amount" data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Amount</th>
			<th data-field="posted_on" data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Posted On</th>
			<th data-field="created_on" data-visible="false" data-align="center" data-sortable="true" class="col-md-1">Created On</th>
			<th data-field="created_by" data-visible="true" data-align="center" data-sortable="false" class="col-md-1">Created By</th>
			<th data-field="viewed_on" data-visible="false" data-align="center" data-sortable="true" class="col-md-1">Viewed On</th>
			<th data-field="viewed_by" data-visible="false" data-align="center" data-sortable="false" class="col-md-1">Viewed By</th>
			<th data-field="approved_on" data-visible="false" data-align="center" data-sortable="true" class="col-md-1">Approved On</th>
			<th data-field="approved_by" data-visible="true" data-align="center" data-sortable="false" class="col-md-1">Approved By</th>
			<th data-field="disapproved_on" data-visible="false" data-align="center" data-sortable="true" class="col-md-1">Disapproved On</th>
			<th data-field="disapproved_by" data-visible="false" data-align="center" data-sortable="false" class="col-md-1">Disapproved By</th>
			<th data-field="status" data-visible="true" data-align="center" data-sortable="false" class="col-md-1">Status</th>
			<?php if($this->Abas->checkPermissions("accounting|view_journal_vouchers",false)): ?>
				<th data-field="operate" data-formatter="operateFormatter" data-halign="center" data-align="center" class="col-md-1">Manage</th>
			<?php endif; ?>
		</tr>
	</thead>
</table>
<script>
$(function () {
	var $table = $('#journal-table');
	$table.bootstrapTable();
});
<?php if($this->Abas->checkPermissions("accounting|view_journal_vouchers",false)): ?>
	function operateFormatter(value, row, index) {
		if(row['disapproved_by']==null) {
			if(row['approved_by']!=null) {
				if(row['viewed_by']==null) { return ['<a class="btn btn-xs btn-warning" onclick="javascript:viewVoucher('+row['id']+');">View Voucher</a> '].join(''); }
				else { return ['<a class="btn btn-xs btn-default" href="<?php echo HTTP_PATH.'accounting/journal/view_voucher/'; ?>'+row['id']+'" title="Edit">View Reprintable Voucher</a> '].join(''); }
			}
			else {
				return [
					'<a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH; ?>accounting/journal/view_voucher/'+row['id']+'">View</a>'
				].join('');
			}
		}
		else {
			return [
				'<a class="btn btn-danger btn-xs" href="<?php echo HTTP_PATH; ?>accounting/journal/view_voucher/'+row['id']+'">View Disapproved Voucher</a>'
			].join('');
		}
	}
	function viewVoucher(voucherid) {
		toastr.clear();
		toastr['warning']('This will mark the voucher as "viewed", and all future printouts will be marked as a reprint. <a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>accounting/journal/view_voucher/'+voucherid+'">Continue</a>', "Are you sure?");
	}
<?php endif; ?>
</script>