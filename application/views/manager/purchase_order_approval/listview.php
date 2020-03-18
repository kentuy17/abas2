<br>
<div class="alert alert-warning alert-dismissible fade in" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
</button>
<p style="color:white">
	<b>Note:</b><br>
	For P50,000 and below: Approved by Purchasing Manager<br>
	For P50,000 to P150,000: Approved by Vice President<br>
	For Over P150,000: Approved by SVP/President<br>
</p>
</div>
<table data-toggle="table" id="data-table-po" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'manager/purchase_orders/load'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="added_on" data-sort-order="asc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="false" data-filter-strict-search="false" data-pagination-V-Align="both" data-card-view="true">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Control No.</th>
				<th data-field="company_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="vessel_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Vessel/Office</th>
				<th data-field="department_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Department</th>
				<th data-field="priority" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Priority</th>
				<th data-field="supplier_name" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Supplier</th>
				<th data-field="amount" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Amount</th>
				<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
				<th data-field="request_approved_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">PR Approved By</th>
				<th data-field="request_approved_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">PR Approved On</th>
				<th data-field="canvass_approved_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Canvass Approved By</th>
				<th data-field="canvass_approved_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Canvass Approved On</th>
				<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
			</tr>
		</thead>
	</table>

<script type="text/javascript">
	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-sm force-pageload" data-toggle="modal" data-target="#modalDialog" href="<?php echo HTTP_PATH.'manager/purchase_orders/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table-po');
		$table.bootstrapTable();
	});

</script>