<?php
	$year = date('Y');
	$prev_year = $year - 1;
	$gwapo = false;
	
?>
<h2 id="glyphicons-glyphs">Budget Percentage: <?=$year?></h2>


	<table data-toggle="table" id="percentage-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."corporate_services/budget_percentage"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">ID</th>
				<th data-field="code" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Code</th>
				<th data-field="account_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Account Name</th>
				<th data-field="percentage" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Increment %</th>
				<th data-field="updator" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Updated by</th>
				<th data-field="updated_on" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Updated on</th>
				<th data-field="operate" data-formatter="view" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
			</tr>
		</thead>
	</table>

<script>

	function view(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'Corporate_Services/edit_percentage'; ?>/'+ row['id'] +'" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a>'
		].join('');
	}
	$(function () {
		var $table = $('#percentage-table');
		$table.bootstrapTable();
	});

</script>

