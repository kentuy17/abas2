<h2 id="glyphicons-glyphs">Fixed Asset Register</h2>
<?php if($this->Abas->checkPermissions("asset_management|add_fixed_asset_register",FALSE)){?>
	<a href="<?php echo HTTP_PATH.'Asset_Management/fixed_asset_register/add';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a>
<?php }?>
<a href="<?php echo HTTP_PATH."Asset_Management/fixed_asset_register/listview/" ?>" class="btn btn-dark force-pageload">Refresh</a>
<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'Asset_Management/fixed_asset_register/load'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
			<th data-field="company_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
			<th data-field="vessel_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Location</th>
			<th data-field="category_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Category</th>
			<th data-field="department_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Department</th>
			<th data-field="asset_code" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Asset Code</th>
			<th data-field="description" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Description</th>
			<th data-field="purchase_cost" data-align="center" data-visible="false" data-sortable="true">Purchase Cost</th>
			<th data-field="date_acquired" data-align="center" data-visible="false" data-sortable="true">Date Acquired</th>
			<th data-field="useful_life" data-align="center" data-visible="false" data-sortable="true">Useful Life</th>
			<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
			<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
			<th data-field="modified_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Modified By</th>
			<th data-field="modified_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Modified On</th>
			<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
			<th data-field="accountable_to" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Accountable To</th>
			<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
		</tr>
	</thead>
</table>
<script>
	function details(value, row, index) {
		if(row['status']=="Unassigned"){
			return [
			'<a class="btn btn-warning btn-xs btn-block force-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false" href="<?php echo HTTP_PATH.'Asset_Management/fixed_asset_register/edit'; ?>/'+ row['id'] +'">Edit</a>'
			].join('');
		}else{
			return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false" href="<?php echo HTTP_PATH.'Asset_Management/fixed_asset_register/edit'; ?>/'+ row['id'] +'">View</a>'
		].join('');
		}
		
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});
</script>