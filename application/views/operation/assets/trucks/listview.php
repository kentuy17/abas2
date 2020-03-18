
	<h2>Truck Profiles</h2>
	<a href="<?php echo HTTP_PATH.'operation/truck_profiles/listview'; ?>" class="btn btn-dark">Refresh</a>
	<table data-toggle="table" id="trucks-table" class="table table-striped table-hover" data-url="<?php echo HTTP_PATH."operation/truck_profiles/load"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
		<thead>
			<tr>
				<th data-field="company_name" data-align= "center" data-sortable="false" data-visible="true" data-filter-control='select'>Company</th>
				<th data-field="photo_path" data-align= "center" data-sortable="false" data-visible="false" data-filter-control='input'>Photo Path</th>
				<th data-field="make" data-align="center" data-sortable="false" data-visible="true" data-filter-control='select'>Make</th>
				<th data-field="model" data-align="center" data-sortable="false" data-visible="true" data-filter-control='input'>Model</th>
				<th data-field= "plate_number" data-align= "center" data-sortable="false" data-visible="true" data-filter-control='input'>Plate Number</th>
				<th data-field="engine_number" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Engine Number</th>
				<th data-field="chassis_number" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Chassis Number</th>
				<th data-field="type" data-align="center" data-sortable="false" data-visible="true" data-filter-control='input'>Vehicle Type/Description</th>
				<th data-field="color" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Color</th>
				<th data-field="date_acquired" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Date Acquired</th>
				<th data-field="registration_month" data-align="center" data-sortable="false" data-visible="true" data-filter-control='input'>Registration Month</th>
				<th data-field="aquisition_cost" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Acquisition Cost</th>
				<th data-field="status" data-align="center" data-sortable="false" data-filter-control='input'>Status</th>
				<th data-field="created_by" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Created By</th>
				<th data-field="created_on" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Created On</th>
				<th data-field="modified_by" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>modified_by</th>
				<th data-field="modified_on" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Modified On</th>
				<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
			</tr>
		</thead>
	</table>

<script>
function operateFormatter(value, row, index) {
	return [
	'<a class="btn btn-xs btn-block btn-info" href="<?php echo HTTP_PATH.'operation/truck_profiles/view/';?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">View',
	'</a> ',
	].join('');

}
$(function () {
	var $table = $('#trucks-table');
	$table.bootstrapTable();
});
</script>
