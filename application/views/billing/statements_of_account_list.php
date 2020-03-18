
<link rel="stylesheet" type="text/css" href="<?php echo LINK?>assets/gentelella-master/vendors/bootstrap-table-filter/bootstrap-table-filter-control.css">
<script src="<?php echo LINK?>assets/gentelella-master/vendors/bootstrap-table-filter/bootstrap-table-filter-control.js"></script>

<h2>Statement of Accounts</h2>


<div class="btn-group">
	 <?php if($this->Abas->checkPermissions("finance|add_statement_of_account",FALSE)){?>
	      <button type="button" class="btn btn-success">Add</a></button>
	      <button type="button" class="btn btn-success dropdown-toggle exclude-pageload" data-toggle="dropdown" aria-expanded="false">
	        <span class="caret"></span>
	        <span class="sr-only">Toggle Dropdown</span>
	      </button>
	      <ul class="dropdown-menu" role="menu">
				<li><a href="<?php echo HTTP_PATH.'statements_of_account/add/general'; ?>" data-toggle="modal" data-target="#modalDialog" class='exclude-pageload' data-backdrop="static"> Statement of Account (Shipping,Time-charter,Sales,etc.)</a></li>
				<li><a href="<?php echo HTTP_PATH.'statements_of_account/add/out_turn'; ?>" data-toggle="modal" data-target="#modalDialog" class='exclude-pageload' data-backdrop="static"> Statement of Account (Trucking and Handling only)</a></li>
	      </ul>
		  &nbsp
	  <?php }?>
      <a href="<?php echo HTTP_PATH."statements_of_account"; ?>">
	 	<button type="button" class="btn btn-dark force-pageload">Refresh</button>
	  </a>
</div>


<?php

if($this->Abas->checkPermissions("finance|add_statement_of_account",FALSE) || $this->Abas->checkPermissions("finance|view_statement_of_account",FALSE)){
	$load = "statements_of_account/load/statement_of_accounts";
}
elseif($this->Abas->checkPermissions("finance|approve_statement_of_account",FALSE)){
	$load = "statements_of_account/load/statement_of_accounts/Pending%20for%20Approval";
}

?>

<table data-toggle="table" id="statements-of-account-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.$load; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-filter-control="true" data-filter-strict-search="false" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>
			<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">Control No.</th>
			<th data-field="contract" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Contract Ref. No.</th>
			<th data-field="reference_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">SOA Ref. No.</th>
			<th data-field="out_turn_summary_id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Out-turn Summary TSCode No.</th>
			<th data-field="company_name" data-align="left" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>
			<th data-field="client_name" data-align="left" data-visible="true" data-filter-control="select" data-sortable="false">Client</th>
			<th data-field="created_on" data-align="left" data-visible="false" data-sortable="true" data-filter-control="input">Created On</th>
			<th data-field="created_by" data-align="left" data-visible="false" data-sortable="true" data-filter-control="select">Created By</th>	
			<th data-field="sent_to_client_on" data-align="left" data-visible="true" data-sortable="true" data-filter-control="input">Date Received by Client</th>
			<th data-field="due" data-align="left" data-visible="true" data-sortable="true" data-filter-control="input">Due On</th>
			<th data-field="aging" data-align="left" data-visible="true" data-sortable="false" data-filter-control="input">Aging</th>
			<th data-field="type" data-align="left" data-visible="false" data-sortable="true" data-filter-control="select">Type</th>
			<th data-field="services" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Service Type</th>
			<th data-field="total_amount" data-align="right" data-visible="true" data-sortable="false" data-filter-control="input">Total Amount</th>	
			<th data-field="status" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Status</th>

			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-align="center" data-align="center">Details</th>
		</tr>
	</thead>
</table>



<script>
	function operateFormatter(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'statements_of_account/view/'; ?>'+row['id']+'">View</a> ',
		].join('');
	}
	$(function () {
		var $table = $('#statements-of-account-table');
		$table.bootstrapTable();
	});
</script>