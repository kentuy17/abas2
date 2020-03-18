<h2 id="glyphicons-glyphs">Accounts Collection - For Approval and Posting</h2><br>
		
	<a href="<?php echo HTTP_PATH."accounting/listview/accounts_collection/for_posting"; ?>" class="btn btn-dark force-pageload">Refresh</a>

					<table data-toggle="table" id="data-table2" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/load/ac_transactions/payments';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="transaction_id" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-search="true" data-filter-control='true'>

						<thead>
							<tr>
								<th data-field="transaction_id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>

								<th data-field="AR_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">AR No.</th>

								<th data-field="OR_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">OR No.</th>

								<th data-field="soa_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">SOA No.</th>

								<th data-field="company_name" data-align="left" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>

								<th data-field="payor" data-align="left" data-visible="true" data-filter-control="input" data-sortable="false">Payor</th>

								<th data-field="payment_type" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Payment Type</th>

								<th data-field="mode_of_collection" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Mode of Collection</th>

								<th data-field="particulars" data-align="left" data-visible="false" data-sortable="true" data-filter-control="select">Particulars</th>

								<th data-field="total_amount" data-align="right" data-visible="true" data-sortable="false" data-filter-control="input">Total Amount</th>

								<th data-field="created_on" data-align="left" data-visible="false" data-sortable="true" data-filter-control="input">Created On</th>

								<th data-field="created_by" data-align="left" data-visible="false" data-sortable="true" data-filter-control="input">Created By</th>

								<th data-field="served_by" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Served By</th>

								<th data-field="status" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Status</th>

								<th data-field="operate" data-formatter="viewAC" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
							</tr>
						</thead>
					</table>

<script>

	$(function () {
		var $table2 = $('#data-table2');
		$table2.bootstrapTable();
	});

	function viewAC(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'accounting/view/accounts_collection/';?>'+row['reference_id']+'/'+row['transaction_id']+'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">View</a> ',
		].join('');
	}

	
</script>