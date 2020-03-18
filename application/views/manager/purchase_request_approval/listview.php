<br>
<table data-toggle="table" id="data-table-pr" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'manager/purchase_requests/load'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="added_on" data-sort-order="asc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="false" data-filter-strict-search="false" data-pagination-V-Align="both" data-card-view="true">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
				<th data-field="company_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="vessel_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Vessel/Office</th>
				<th data-field="department_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Department</th>
				<th data-field="priority" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Priority</th>
				<th data-field="remark" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Purpose</th>
				<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
				<th data-field="authorized_approver" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Authorized Approver</th>
				<th data-field="requisitioner" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Requested By</th>
				<th data-field="tdate" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Requested On</th>
				<th data-field="added_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Verified By</th>
				<th data-field="added_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Verified On</th>
				<th data-field="approved_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Approved By</th>
				<th data-field="approved_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Approved On</th>
				<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
			</tr>
		</thead>
	</table>

<script type="text/javascript">
	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-sm force-pageload" data-toggle="modal" data-target="#modalDialog" href="<?php echo HTTP_PATH.'manager/purchase_requests/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table-pr');
		$table.bootstrapTable();
	});

</script>

