<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title"><?=strtoupper($index->status)?>
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<table data-toggle="table" id="budget-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."budget/verify_item_data/".$id; ?>" data-cache="false" data-side-pagination="server" >
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">ID</th>
				<th data-field="type" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Account Type</th>
				<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="department" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Department</th>
				<th data-field="vessel" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Vessel</th>
				<th data-field="total_amount" data-align="right" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Estimated Budget</th>
				<th data-field="operate" data-formatter="view" data-events="operateEvents"  data-halign="center" data-align="center">Manage</th>
			</tr>
		</thead>
	</table>
	<br/>
	
<?php 
	if($this->Abas->checkPermissions("manager|budget_verify",false)){
		switch ($index->status) {
			case 'for verification':
				$status = 'verified';
				break;

			case 'for approval':
				$status = 'approved';
				break;

			case 'approved':
				$status = 'approved';
				break;
		}
?>
	<a href="<?=HTTP_PATH.'budget/approve/reject/'.$id?>">
		<button class="btn btn-danger btn-sm pull-right">Reject</button>
	</a>
	<a href="<?=HTTP_PATH.'budget/approve/'.$status.'/'.$id?>">
		<button class="btn btn-success btn-sm pull-right">Approve</button>
	</a>
<?php 
	} 
?>

<?php
	function getTypes($type){
		switch ($type) {
			case 'Revenue':
				$account_type = 'revenue';
				break;

			case 'Cost of Sales':
				$account_type = 'cost_of_sales';
				break;

			case 'Operating Expenses':
				$account_type = 'operating_expenses';
				break;

			case 'Assets':
				$account_type = 'assets';
				break;

			case 'Liabilities':
				$account_type = 'liabilities';
				break;
			
			default:
				$account_type = 'Other Accounts';
				break;
		}
		return $account_type;
	}
?>

	<script>

		function view(value, row, index) {
			return [
				'<a class="btn btn-primary btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'budget/view_account_type/'; ?>'+ row['id'] +'/" target="_blank">View</a>'
			].join('');
		}
		$(function () {
			var $table = $('#budget-table');
			$table.bootstrapTable();
		});

	</script>
</div>
