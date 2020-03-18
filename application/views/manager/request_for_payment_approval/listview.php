<br>
<div class="alert alert-warning alert-dismissible fade in" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
</button>
<p style="color:white">
	<b>Note:</b><br>
	For P10,000 and below: Verified by Department Supervisor | Approved by Department Manager<br>
	For P10,000 to P100,000: Verified by Department Manager | Approved by Vice President<br>
	For Over P100,000: Verified by Vice-President | Approved by SVP/President<br>
</p>
</div>
<table data-toggle="table" id="data-table-rfp" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'manager/request_for_payment/load'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="id" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="false" data-filter-strict-search="false" data-pagination-V-Align="both" data-card-view="true">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
				<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="payee_type" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Payee Type</th>
				<th data-field="payee_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payment To</th>
				<th data-field="purpose" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Purpose</th>
				<th data-field="created_by" data-align="center" data-visible="True" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Prepared By</th>
				<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created On</th>
				<th data-field="verified_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Verified By</th>
				<th data-field="verified_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Verified On</th>
				<th data-field="approved_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Approved By</th>
				<th data-field="approved_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Approved On</th>
				<th data-field="amount" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>
				<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
				<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
			</tr>
		</thead>
	</table>

<script type="text/javascript">
	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-sm force-pageload" data-toggle="modal" data-target="#modalDialog" href="<?php echo HTTP_PATH.'manager/request_for_payment/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table-rfp');
		$table.bootstrapTable();
	});

</script>

