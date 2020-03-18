<a class="btn btn-info" href="<?php echo HTTP_PATH.'mastertables/departments/add/'; ?>" data-toggle="modal" data-target="#modalDialog">Add Department</a>
<table data-toggle="table" id="departments-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/departments/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="name" data-align="left" data-sortable="true">Department Name</th>
			<th data-field="sorting" data-visible="true" data-sortable="true">Sorting</th>
			<th data-field="accounting_code" data-visible="true" data-sortable="true">Accounting Code</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-align="center" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
			'<a class="btn btn-default btn-xs btn-warning" href="<?php echo HTTP_PATH.'mastertables/departments/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Edit</a> '
		].join('');
	}
	$(function () {
		var $table = $('#departments-table');
		$table.bootstrapTable();
	});
</script>

