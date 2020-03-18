<h2 id="glyphicons-glyphs">Inventory Returns - For Posting</h2><br>

		
	<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/inventory_returns/load/for_posting';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="return_date" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-search="true" data-filter-control='true'>
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>

				<th data-field="return_date" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Return Date</th>

				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="false" data-filter-control="input">MSRS No.</th>

				<th data-field="company_name" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>

				<th data-field="return_from" data-align="center" data-visible="true" data-filter-control="input" data-sortable="false">Returned From</th>

				<th data-field="return_to" data-align="center" data-visible="true" data-filter-control="input" data-sortable="false">Returned To</th>

				<th data-field="total_amount" data-align="center" data-visible="true" data-filter-control="input" data-sortable="false">Total Amount</th>

				<th data-field="inventory_return_status" data-align="center" data-visible="true" data-filter-control="select" data-sortable="false">Status</th>

				<th data-field="remark" data-align="left" data-visible="false" data-sortable="false">Remark</th>

				<th data-field="operate" data-formatter="viewInventoryReturns" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
			</tr>
		</thead>
	</table>

<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>

<script>

	$(function () {
		var $table1 = $('#data-table');
		$table1.bootstrapTable();
	});

	function viewInventoryReturns(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'accounting/inventory_returns/view/for_posting/';?>'+row['id']+'/'+row['transaction_id']+'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">View</a> ',
		].join('');
	}
	
</script>