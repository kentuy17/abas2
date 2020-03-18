<h2 id="glyphicons-glyphs">Request for Payments</h2>

<a href="<?php echo HTTP_PATH.'Corporate_Services/request_for_payment/add';?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Add</a>

<a href="<?php echo HTTP_PATH.'Corporate_Services/request_for_payment/listview'?>" class="btn btn-dark force-pageload">Refresh</a> 

	<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'Corporate_Services/request_for_payment/load/'.$_SESSION['abas_login']['userid']?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="id" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
				<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="payee_type" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payee Type</th>
				<th data-field="payee_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Payment To</th>
				<th data-field="purpose" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Purpose</th>
				<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created By</th>
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

<script>

	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'Corporate_Services/request_for_payment/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

</script>

