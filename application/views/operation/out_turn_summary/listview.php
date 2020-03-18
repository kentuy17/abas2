

		<h2 id="glyphicons-glyphs">Out-Turn Summary</h2>
		
		<?php if($this->Abas->checkPermissions("operations|add_out_turn_summary",FALSE)){?>
			<a href="<?php echo HTTP_PATH.'operation/out_turn_summary/add';?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Add</a>
		<?php }?>

		<a href="<?php echo HTTP_PATH.'operation/out_turn_summary/listview'?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'operation/out_turn_summary/load'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false" data-pagination-V-Align="both">
				<thead>
					<tr>
						<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
						<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
						<th data-field="service_order_id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Service Order TSCode No.</th>
						<th data-field="service_contract_id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Reference Contract TSCode No. (Sub-con)</th>
						<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
						<th data-field="reference_no" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Contract Ref. No.</th>
						<th data-field="mother_contract" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Mother Contract</th>
						<th data-field="client" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Client</th>
						<th data-field="type_of_service" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Service Type</th>
						<th data-field="bill_of_lading" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Bill of Lading No.</th>
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
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'operation/out_turn_summary/view'; ?>/'+ row['id'] +'">View</a>'//,
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

</script>

