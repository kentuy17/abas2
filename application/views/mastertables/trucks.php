<a href='<?php echo HTTP_PATH;?>mastertables/trucks/add' class="btn btn-info" title="Add New Truck" style="cursor:pointer; float:left;" data-toggle="modal" data-target="#modalDialog">Add Truck</a>
<div class="panel-body">
	<table data-toggle="table" id="trucks-table" class="table table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/trucks/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true">
		<thead>
			<tr>
				<th data-field="company_name" data-align= "center" data-sortable="false" data-visible="true">Company</th>
				<th data-field="photo_path" data-align= "center" data-sortable="false" data-visible="false">Photo Path</th>
				<th data-field="make" data-align="center" data-sortable="false" data-visible="true">Make</th>
				<th data-field="model" data-align="center" data-sortable="false" data-visible="true">Model</th>
				<th data-field= "plate_number" data-align= "center" data-sortable="false" data-visible="true">Plate Number</th>
				<th data-field="engine_number" data-align="center" data-sortable="false" data-visible="false">Engine Number</th>
				<th data-field="chassis_number" data-align="center" data-sortable="false" data-visible="false">Chassis Number</th>
				<th data-field="type" data-align="center" data-sortable="false" data-visible="true">Vehicle Type/Description</th>
				<th data-field="color" data-align="center" data-sortable="false" data-visible="false">Color</th>
				<th data-field="date_acquired" data-align="center" data-sortable="false" data-visible="false">Date Acquired</th>
				<th data-field="registration_month" data-align="center" data-sortable="false" data-visible="true">Registration Month</th>
				<th data-field="aquisition_cost" data-align="center" data-sortable="false" data-visible="false">Acquisition Cost</th>
				<th data-field="status" data-align="center" data-sortable="false">Status</th>
				<th data-field="created_by" data-align="center" data-sortable="false" data-visible="false">Created By</th>
				<th data-field="created_on" data-align="center" data-sortable="false" data-visible="false">Created On</th>
				<th data-field="modified_by" data-align="center" data-sortable="false" data-visible="false">modified_by</th>
				<th data-field="modified_on" data-align="center" data-sortable="false" data-visible="false">Modified On</th>
				<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
			</tr>
		</thead>
	</table>
</div>
<script>
function operateFormatter(value, row, index) {
	return [
	'<a class="btn btn-xs btn-warning" href="<?php echo HTTP_PATH.'mastertables/trucks/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit">',
	'Edit',
	'</a> ',
	].join('');

}
$(function () {
	var $table = $('#trucks-table');
	$table.bootstrapTable();
});
</script>
