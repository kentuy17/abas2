<a class="btn btn-info" href="<?php echo HTTP_PATH.'mastertables/supply/add/'; ?>" data-toggle="modal" data-target="#modalDialog">Add supply</a>
<table data-toggle="table" id="supply-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/supply/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-sortable="true">ID</th>
			<th data-field="name" data-align="left" data-sortable="true">Company</th>
			<th data-field="address" data-visible="true" data-sortable="true">Address</th>
			<th data-field="region" data-visible="true" data-sortable="true">City</th>
			<th data-field="contact_person" data-visible="true" data-sortable="false">Province</th>
			<th data-field="telephone_no" data-visible="true" data-sortable="false">Country</th>
			<th data-field="fax_no" data-visible="false" data-sortable="true">Contact Number</th>
			<th data-field="payment_terms" data-visible="true" data-sortable="true">Fax Number</th>
			<th data-field="email" data-visible="false" data-sortable="true">Email</th>
			<th data-field="vat_registered" data-visible="false" data-sortable="true">Website</th>
			<th data-field="vat_computation" data-visible="false" data-sortable="true">Contact Person</th>
			<th data-field="taxation_percentile" data-visible="false" data-sortable="true">Position</th>
			<th data-field="type" data-visible="true" data-sortable="true">Lead Person</th>
			<th data-field="issues_reciepts" data-visible="false" data-sortable="true">Stat</th>
			<th data-field="created_by" data-visible="false" data-sortable="true">Tin Number</th>
			<th data-field="bank_account_no" data-visible="false" data-sortable="true">Account Number</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
		'<a class="btn btn-default btn-xs btn-warning" href="<?php echo HTTP_PATH.'mastertables/supply/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Edit</a> '
		].join('');
	}
	$(function () {
		var $table = $('#supply-table');
		$table.bootstrapTable();
	});
</script>