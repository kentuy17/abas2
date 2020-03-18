<!DOCTYPE html>
<html>
	<head>
		<title>Collection</title>
		<link rel="stylesheet" type="text/css" href="<?php echo LINK?>assets/gentelella-master/vendors/bootstrap-table-filter/bootstrap-table-filter-control.css">
		<script src="<?php echo LINK?>assets/gentelella-master/vendors/bootstrap-table-filter/bootstrap-table-filter-control.js"></script>
	</head>
		<body>

		<h2 id="glyphicons-glyphs">Acknowledgement Receipts</h2>

		<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/acknowledgement_receipt';?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Add</a>

		<a href="<?php echo HTTP_PATH.CONTROLLER.'/listview/acknowledgement_receipt'; ?>" class="btn btn-dark">Clear Filter</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/acknowledgement_receipts'; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="received_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="false" data-filter-control="true" data-filter-strict-search="false">
				<thead>
					<tr>

						<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
						<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
						<th data-field="company_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
						<th data-field="received_from" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Received From</th>
						<th data-field="TIN" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">TIN</th>
						<th data-field="remarks" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">Purpose of payment</th>
						<th data-field="type" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Type</th>
						<th data-field="total" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Total Amount</th>
						<th data-field="received_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Received By</th>
						<th data-field="received_on" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Received On</th>
						<!--<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>-->

						<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>

					</tr>
				</thead>
			</table>

		</body>
</html>

<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>

<script>

	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" href="<?php echo HTTP_PATH.CONTROLLER.'/view/acknowledgement_receipt'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

</script>