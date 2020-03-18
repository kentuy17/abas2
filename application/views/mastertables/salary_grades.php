<a class="btn btn-info" href="<?php echo HTTP_PATH.'mastertables/salary_grades/add/'; ?>" data-toggle="modal" data-target="#modalDialog">Add Salary Grade</a>
<table data-toggle="table" id="salary_grades-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/salary_grades/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			
			<th data-field="grade" data-align="left" data-sortable="true">Grade</th>
			<th data-field="rate" data-visible="true" data-sortable="true">Rate</th>
			<th data-field="level" data-visible="true" data-sortable="false">Level</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-align="center" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
			'<a class="btn btn-default btn-xs btn-warning" href="<?php echo HTTP_PATH.'mastertables/salary_grades/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Edit</a> '
		].join('');
	}
	$(function () {
		var $table = $('#salary_grades-table');
		$table.bootstrapTable();
	});
</script>

