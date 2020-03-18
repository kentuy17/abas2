<h2 id="glyphicons-glyphs">Check Releasing</h2>

<a href="<?php echo HTTP_PATH.'finance/check_releasing/listview'?>" class="btn btn-dark force-pageload">Refresh</a> 

	<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'finance/check_releasing/load'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="voucher_date" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
				<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="payee_type" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Payee Type</th>
				<th data-field="payee_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Payment To</th>
				<th data-field="voucher_date" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Voucher Date</th>
				<th data-field="transaction_type" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Type</th>
				<th data-field="apv_no" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">APV / RFP</th>
				<th data-field="remark" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Remark</th>

				<th data-field="bank" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Bank</th>
				<th data-field="check_num" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Check No.</th>
				<th data-field="amount" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>

				<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created By</th>
				<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created On</th>
				<th data-field="verified_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Verified By</th>
				<th data-field="verified_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Verified On</th>
				<th data-field="approved_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Approved By</th>
				<th data-field="approved_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Approved On</th>

				<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
				<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
			</tr>
		</thead>
	</table>

<script>

	function details(value, row, index) {
		return [
			'<a class="btn btn-warning btn-xs btn-block force-pageload" data-toggle="modal" data-target="#modalDialog" href="<?php echo HTTP_PATH.'finance/check_releasing/view'; ?>/'+ row['id'] +'">Release</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

</script>

