<br>
<table data-toggle="table" id="data-table-rr" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/accounts_payable_voucher/load_rr'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[100, 200, 500, 1000, 2500, 5000]" data-page-size="10" data-search="true" data-filter-control="true" data-filter-strict-search="false">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">RR No.</th>
			<th data-field="po_no" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">PO No.</th>
			<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true"data-filter-control="input" data-filter-strict-search="false">Control No.</th>
			<th data-field="company_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
			<th data-field="delivery_date" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Delivery Date</th>
			<th data-field="supplier_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payee</th>
			<th data-field="amount" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>
			<th data-field="location" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Location</th>
			<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
			<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
			<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
		</tr>
	</thead>
</table>
<script type="text/javascript">
	$(function () {
		var $table = $('#data-table-rr');
		$table.bootstrapTable();
	});
	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'accounting/accounts_payable_voucher/add'; ?>/'+ row['id'] +'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Process</a>'
		].join('');
	}
</script>