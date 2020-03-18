<h2 id="glyphicons-glyphs">Accounts Receivables - For Clearing</h2><br>
		
	<a href="<?php echo HTTP_PATH."accounting/listview/accounts_receivables/for_clearing"; ?>" class="btn btn-dark force-pageload">Refresh</a>

		
					<table data-toggle="table" id="data-table1" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/load/statement_of_accounts';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-search="true" data-filter-control='true'>

						<thead>
							<tr>
								<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>

								<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">SOA No.</th>
								
								<th data-field="reference_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">Reference No.</th>

								<th data-field="company_name" data-align="left" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>

								<th data-field="client_name" data-align="left" data-visible="true" data-filter-control="select" data-sortable="false">Client</th>

								<th data-field="type" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Type</th>

								<th data-field="services" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Services</th>

								<th data-field="total_amount" data-align="right" data-visible="true" data-sortable="false" data-filter-control="input">Total Amount</th>

								<th data-field="created_on" data-align="left" data-visible="false" data-sortable="true" data-filter-control="input">Created On</th>

								<th data-field="created_by" data-align="left" data-visible="false" data-sortable="true" data-filter-control="select">Created By</th>	

								<th data-field="status" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Status</th>

								<th data-field="operate" data-formatter="processAR" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
							</tr>
						</thead>
					</table>
	

<script>

	$(function () {
		var $table1 = $('#data-table1');
		$table1.bootstrapTable();
	});

	function processAR(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'accounting/add/accounts_receivables/';?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Process</a> ',
		].join('');
	}

</script>