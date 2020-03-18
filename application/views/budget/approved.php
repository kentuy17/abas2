<?php
	$prev_year = date('Y') - 1;
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title">Final Approved Budget
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<table data-toggle="table" id="budget-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."manager/verify_item_data/".$id; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">ID</th>
				<th data-field="code" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Code</th>
				<th data-field="account_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Account Name</th>
				<th data-field="format_prev_budget" data-align="right" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false"><?=$prev_year?></th>
				<th data-field="increment" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Increment %</th>
				<th data-field="format_curr_budget" data-align="right" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Estimated Budget</th>
				<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Company</th>
				<th data-field="department" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Department</th>
				<th data-field="author" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Added by</th>
				<th data-field="date_created" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Added on</th>
				<th data-field="updated_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Updated by</th>
				<th data-field="date_updated" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Updated on</th>
				<th data-field="operate" data-formatter="view" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
			</tr>
		</thead>
	</table>
<?php if($this->Abas->checkPermissions("manager|revert",false)){ ?>
	<a href="<?=HTTP_PATH.'manager/approved/revert/'.$id?>"><button class="btn btn-danger btn-sm pull-right">Revert</button></a>
<?php } ?>

	<script>

		function view(value, row, index) {
			return [
				'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/view'; ?>/'+ row['id'] +'">Delete</a>'
			].join('');
		}
		$(function () {
			var $table = $('#budget-table');
			$table.bootstrapTable();
		});

	</script>
</div>
