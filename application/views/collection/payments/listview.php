
		<h2 id="glyphicons-glyphs">Payments</h2>
		
		<?php if($this->Abas->checkPermissions("finance|add_payments",false)): ?>
		<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/payments';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Add</a>
		<?php endif ?>
		<a href="<?php echo HTTP_PATH.CONTROLLER.'/listview/payments'; ?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/payments'; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="received_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
				<thead>
					<tr>

						<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
						<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
						<th data-field="acknowledgement_receipt_number" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">AR No.</th>
						<th data-field="official_receipt_number" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">OR No.</th>
						<th data-field="soa_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">SOA No.</th>
						<th data-field="soa_id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">SOA TSCode No.</th>
						<th data-field="company_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
						<th data-field="payor" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payor</th>
						<th data-field="particulars" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Particular</th>
						<th data-field="mode_of_collection" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select">Mode of Collection</th>
						<th data-field="net_amount" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Total Amount</th>
						<th data-field="received_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Received By</th>
						<th data-field="received_on" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Received On</th>
						<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>

						<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>

					</tr>
				</thead>
			</table>


<script>

	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" href="<?php echo HTTP_PATH.CONTROLLER.'/view/payments'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

</script>