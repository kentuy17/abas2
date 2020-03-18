<a class="btn btn-info" href="<?php echo HTTP_PATH.'mastertables/suppliers/add/'; ?>" data-toggle="modal" data-target="#modalDialog">Add Supplier</a>
<table data-toggle="table" id="suppliers-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/suppliers/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-sortable="true">ID</th>
			<th data-field="name" data-align="left" data-sortable="true">Name</th>
			<th data-field="address" data-visible="true" data-sortable="true">Address</th>
			<th data-field="type" data-visible="true" data-sortable="true">Type</th>
			<th data-field="region" data-visible="false" data-sortable="true">Region</th>
			<th data-field="contact_person" data-visible="true" data-sortable="false">Contact Person</th>
			<th data-field="telephone_no" data-visible="true" data-sortable="false">Telephone Number</th>
			<th data-field="fax_no" data-visible="false" data-sortable="true">Fax Number</th>
			<th data-field="payment_terms" data-visible="false" data-sortable="true">Payment Terms</th>
			<th data-field="email" data-visible="false" data-sortable="true">Email</th>
			<th data-field="vat_registered" data-visible="false" data-sortable="true">VAT Registered</th>
			<th data-field="vat_computation" data-visible="false" data-sortable="true">VAT Computation</th>
			<th data-field="taxation_percentile" data-visible="false" data-sortable="true">Expanded Withholding Tax</th>
			<th data-field="issues_reciepts" data-visible="false" data-sortable="true">Issues Reciepts</th>
			<th data-field="created_by" data-visible="false" data-sortable="true">Created By</th>
			<th data-field="modified_by" data-visible="false" data-sortable="true">Modified By</th>
			<th data-field="created" data-visible="false" data-sortable="true">Created On</th>
			<th data-field="modified" data-visible="false" data-sortable="true">Last Modified On</th>
			<th data-field="tin" data-visible="false" data-sortable="true">Tax Identification Number</th>
			<th data-field="bank_name" data-visible="false" data-sortable="true">Bank</th>
			<th data-field="account_name" data-visible="false" data-sortable="true">Account Name</th>
			<th data-field="bank_account_no" data-visible="false" data-sortable="true">Account Number</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
		'<a class="btn btn-warning btn-xs" href="<?php echo HTTP_PATH.'mastertables/suppliers/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Edit</a> ',
		'<a class="btn btn-dark btn-xs" href="<?php echo HTTP_PATH.'mastertables/suppliers/report_purchases_filter/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Purchase Report</a> '
		].join('');
	}
	$(function () {
		var $table = $('#suppliers-table');
		$table.bootstrapTable();
	});
</script>