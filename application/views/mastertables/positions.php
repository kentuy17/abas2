<div>
	<a class='btn btn-info'  href='<?php echo HTTP_PATH; ?>mastertables/positions/add' role ='button'  data-toggle='modal' data-target='#modalDialog'>Add Position</a>
</div>

<table data-toggle="table" id="positions-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/positions/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-sortable="true" data-align="center" data-field='department_name'>Department</th>
			<th data-sortable="true" data-align="center" data-field='name'>Name</th>
			<th data-sortable="true" data-align="center" data-field='sorting'>Sorting</th>
			<th data-sortable="true" data-visible="false" data-align="center" data-field='created_by' >Created by</th>
			<th data-sortable="true" data-visible="false"  data-align="center" data-field='modified_by'>Modified by</th>
			<th data-sortable="true" data-visible="false"  data-align="center" data-field='created'>Created</th>
			<th data-sortable="true" data-visible="false"  data-align="center" data-field='modified'>Modified</th>
			<th data-align="center" data-field="operate" data-formatter="operateFormatter" data-events="operateEvents">Manage</th>
		</tr>
	</thead>
	<script>
		function operateFormatter(value, row, index){
			return[
			'<a class="btn btn-xs btn-warning" href="<?php echo HTTP_PATH.'mastertables/positions/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit"> Edit </a>',
			].join('');
		}
		$ (function (){
			var $table = $('#positions-table');
			$table.bootstrapTable();
		});
	</script>
</table>