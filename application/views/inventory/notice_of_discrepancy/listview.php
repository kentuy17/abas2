<h2 id="glyphicons-glyphs">Notice of Discrepancy</h2>
	
	<?php if($this->Abas->checkPermissions("inventory|add_notice_of_discrepancy",false)){ ?>
		<a href="<?php echo HTTP_PATH.'inventory/notice_of_discrepancy/add';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a>
	<?php } ?>
	<a href="<?php echo HTTP_PATH."inventory/notice_of_discrepancy/listview"; ?>" class="btn btn-dark force-pageload">Refresh</a> 

		<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'inventory/notice_of_discrepancy/load'; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="true" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
			<thead>
				<tr>
					<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
					<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
					<th data-field="company_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
					<th data-field="purchase_order_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">PO Details</th>
					<th data-field="delivery_receipt_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">DR No.</th>
					<!--<th data-field="reason_of_discrepancy" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Reason</th>-->
					<th data-field="other_remarks" data-align="center" data-visible="false" data-sortable="true">Remarks</th>
					<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
					<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
					<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
					<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
				</tr>
			</thead>
		</table>

<script>

function details(value, row, index) {
	return [
		'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'inventory/notice_of_discrepancy/view/'; ?>'+ row['id'] +'">View</a>'
	].join('');
}
$(function () {
	var $table = $('#data-table');
	$table.bootstrapTable();
});

$(function () {
    $('#data-table').bootstrapTable({
        showExport: true,
        exportOptions: {
            fileName: 'custom_file_name'
        }
    });
});

</script>