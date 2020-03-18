<br>
<table data-toggle="table" id="data-table-apv" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/accounts_payable_voucher/load_apv'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[100, 200, 500, 1000, 2500, 5000]" data-page-size="10" data-search="true" data-filter-control="true" data-filter-strict-search="false">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">APV No.</th>
			<th data-field="rr_no2" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">RR No.</th>
			<th data-field="po_no2" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">PO No.</th>
			<th data-field="control_number2" data-align="center" data-visible="true" data-sortable="true"data-filter-control="input" data-filter-strict-search="false">Control No.</th>
			<th data-field="company_name2" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
			<th data-field="apv_date" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">AP Date</th>
			<th data-field="supplier_name2" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payee</th>
			<th data-field="amount2" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>
			<th data-field="location2" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Location</th>
			<th data-field="created_by2" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
			<th data-field="created_on2" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
			<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
		</tr>
	</thead>
</table>
<script type="text/javascript">
	$(function () {
		var $table2 = $('#data-table-apv');
		$table2.bootstrapTable();
	});
	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" href="<?php echo HTTP_PATH.'accounting/accounts_payable_voucher/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
</script>