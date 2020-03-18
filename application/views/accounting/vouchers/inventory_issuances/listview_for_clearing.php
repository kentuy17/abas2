<h2 id="glyphicons-glyphs">Inventory Issuances - For Clearing</h2><br>

		
	<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/inventory_issuances/load/for_clearing';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="issue_date" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-search="true" data-filter-control='true'>
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>

				<th data-field="issue_date" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Issuance Date</th>

				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="false" data-filter-control="input">MSIS No.</th>

				<th data-field="company_name" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>

				<th data-field="issued_to" data-align="center" data-visible="true" data-filter-control="input" data-sortable="false">Issued To</th>

				<th data-field="issued_for" data-align="center" data-visible="true" data-filter-control="input" data-sortable="false">Issued For</th>

				<th data-field="from_location" data-align="center" data-visible="true" data-filter-control="select" data-sortable="false">Location</th>

				<th data-field="total_amount" data-align="center" data-visible="true" data-filter-control="input" data-sortable="false">Total Amount</th>

				<th data-field="inventory_issuance_status" data-align="center" data-visible="true" data-filter-control="select" data-sortable="false">Status</th>

				<th data-field="remark" data-align="left" data-visible="false" data-sortable="false">Remark</th>

				<th data-field="operate" data-formatter="processInventoryIssuance" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
			</tr>
		</thead>
	</table>

<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>

<script>

	$(function () {
		var $table1 = $('#data-table');
		$table1.bootstrapTable();
	});

	function processInventoryIssuance(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'accounting/inventory_issuances/add/for_clearing/';?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Process</a> ',
		].join('');
	}
	
</script>