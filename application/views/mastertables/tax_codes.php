<a class="btn btn-info" href="<?php echo HTTP_PATH.'mastertables/tax_codes/add/'; ?>" data-toggle="modal" data-target="#modalDialog">Add Tax Code</a>
<table data-toggle="table" id="tax_codes-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."mastertables/tax_codes/json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="from_sal" data-align="left" data-sortable="true">Salary (From)</th>
			<th data-field="to_sal" data-visible="true" data-sortable="true">Salary (To)</th>
			<th data-field="over" data-visible="true" data-sortable="true">Over</th>
			<th data-field="amount" data-visible="true" data-sortable="false">Amount</th>
			<th data-field="stat" data-visible="false" data-sortable="true">Stat</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
		'<a class="btn btn-default btn-xs btn-warning" href="<?php echo HTTP_PATH.'mastertables/tax_codes/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Edit</a> '
		].join('');
	}
	$(function () {
		var $table = $('#tax_codes-table');
		$table.bootstrapTable();
	});
</script>