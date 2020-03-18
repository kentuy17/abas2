<a class="btn btn-info" href="<?php echo HTTP_PATH.'mastertables/clients/add/'; ?>" data-toggle="modal" data-target="#modalDialog">Add Client</a>
<table data-toggle="table" id="clients-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/clients/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="company" data-align="left" data-sortable="true">Company</th>
			<th data-field="address" data-visible="true" data-sortable="true">Address</th>
			<th data-field="city" data-visible="true" data-sortable="true">City</th>
			<th data-field="province" data-visible="false" data-sortable="false">Province</th>
			<th data-field="country" data-visible="false" data-sortable="false">Country</th>
			<th data-field="contact_no" data-visible="true" data-sortable="true">Contact Number</th>
			<th data-field="fax_no" data-visible="false" data-sortable="true">Fax Number</th>
			<th data-field="email" data-visible="false" data-sortable="true">Email</th>
			<th data-field="website" data-visible="false" data-sortable="true">Website</th>
			<th data-field="contact_person" data-visible="false" data-sortable="true">Contact Person</th>
			<th data-field="position" data-visible="false" data-sortable="true">Position</th>
			<th data-field="lead_person" data-visible="true" data-sortable="true">Lead Person</th>
			<th data-field="stat" data-visible="false" data-sortable="true">Stat</th>
			<th data-field="tin_no" data-visible="false" data-sortable="true">Tin Number</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
		'<a class="btn btn-default btn-xs btn-warning" href="<?php echo HTTP_PATH.'mastertables/clients/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Edit</a> '
		].join('');
	}
	$(function () {
		var $table = $('#clients-table');
		$table.bootstrapTable();
	});
</script>