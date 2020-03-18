<?php
	$year = date('Y');
	$prev_year = $year - 1;
	$gwapo = false;
	
?>
<h2 id="glyphicons-glyphs">
	Year: <?=$year?><br/>
	Company: <?=$company->name?><br/>
	Department: <?=$department->name?>
</h2>
<?php if($this->Abas->checkPermissions("corporate_services|generate_budget",false)){?>
<a href="<?php echo HTTP_PATH.'Corporate_Services/generate_budget_form';?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
<!--a href="<?php echo HTTP_PATH.'Corporate_Services/generate_budget'?>"-->
	<button <?php if($count != 0) echo "disabled"; ?> class="btn btn-success">Generate Budget</button>
</a>
<?php if($count != 0){ ?>
<a href="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/listview'?>" class="btn btn-dark force-pageload">Add Account</a> 
<?php } } ?>

	<table data-toggle="table" id="budget-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."corporate_services/budget_items"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">ID</th>
				<th data-field="code" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Code</th>
				<th data-field="account_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Account Name</th>
				<th data-field="prev_budget" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false"><?=$prev_year?></th>
				<th data-field="increment" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Increment %</th>
				<th data-field="curr_budget" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Estimated Budget</th>
				<th data-field="department" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Department</th>
				<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Company</th>
				<th data-field="author" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Added by</th>
				<th data-field="date_created" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Added on</th>
				<th data-field="updated_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Updated by</th>
				<th data-field="date_updated" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Updated on</th>
				<th data-field="operate" data-formatter="view" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
			</tr>
		</thead>
	</table>


<script>

	function view(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#budget-table');
		$table.bootstrapTable();
	});

</script>

