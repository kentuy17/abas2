<h2 id="glyphicons-glyphs">Materials/Services Requests</h2>

<a href="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/add';?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Add</a>

<a href="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/listview'?>" class="btn btn-dark force-pageload">Refresh</a> 

	<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/load/'.$_SESSION['abas_login']['userid']?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="id" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
				<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="vessel_office" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Vessel/Office</th>
				<th data-field="department" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Department</th>
				<th data-field="priority" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Priority</th>
				<th data-field="requisitioner" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Requisitioner</th>
				<th data-field="requested_by" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Requested By</th>
				<th data-field="requested_on" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Requested On</th>
				<th data-field="approved_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Approved By</th>
				<th data-field="approved_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Approved On</th>
				<!--<th data-field="status_po" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">PO Status</th>
				<th data-field="status_jo" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">JO Status</th>-->
				<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
			</tr>
		</thead>
	</table>

<script>

	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

</script>

