<a class="btn btn-info" href="<?php echo HTTP_PATH.'mastertables/companies/add/'; ?>" data-toggle="modal" data-target="#modalDialog">Add Company</a>
<table data-toggle="table" id="companies-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/companies/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="name" data-align="left" data-sortable="true">Name</th>
			<th data-field="address" data-align="left" data-sortable="true">Address</th>
			<th data-field="telephone_no" data-visible="false" data-align="left" data-sortable="true">Tel. No.</th>
			<th data-field="fax_no" data-visible="false" data-align="left" data-sortable="true">Fax No.</th>
			<th data-field="company_tin" data-visible="false" data-align="left" data-sortable="true">TIN</th>
			<th data-field="is_top_20000" data-align="left" data-sortable="true">Is top 20,000?</th>
			<th data-field="created_by" data-visible="false" data-align="left" data-sortable="true">Created By</th>
			<th data-field="created" data-visible="false" data-align="left" data-sortable="true">Created On</th>
			<th data-field="modified_by" data-visible="false" data-align="left" data-sortable="true">Modified By</th>
			<th data-field="modified" data-visible="false" data-align="left" data-sortable="true">Modified On</th>
			<th data-field="stat" data-visible="false" data-sortable="true">Stat</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
		'<a class="btn btn-default btn-xs btn-warning" href="<?php echo HTTP_PATH.'mastertables/companies/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Edit</a> '
		].join('');
	}
	$(function () {
		var $table = $('#companies-table');
		$table.bootstrapTable();
	});
</script>