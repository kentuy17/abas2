<h2 id="glyphicons-glyphs">Accounts Collection</h2><br>
		
		<!--<a href="<?php //echo HTTP_PATH."accounting/listview/accounts_collection"; ?>" class="btn btn-dark">Clear Filter</a>-->

		<div role="tabpanel" data-example-id="togglable-tabs">

			 <ul id="tab_list" class="nav nav-tabs bar_tabs" role="tablist">
			 	<li role="presentation" id="link_for_clearing" class="active"><a href="#tab_for_clearing" name="link_for_clearing" role="tab" data-toggle="tab" aria-expanded="true"><b>For Clearing</b></a>
	            </li>
	            <li role="presentation" id="link_for_clearing"><a href="#tab_for_posting" name="link_for_posting" role="tab" data-toggle="tab" aria-expanded="true"><b>For Approval and Posting</b></a>
	            </li>
	             <li role="presentation" id="link_posted"><a href="#tab_posted" name="link_posted" role="tab" data-toggle="tab" aria-expanded="true"><b>Posted</b></a>
	            </li>
	           
	         </ul>

	        <div id="tab_contents" class="tab-content">
	         	<div role="tabpanel" class="tab-pane fade active in" id="tab_for_clearing" aria-labelledby="tab_for_clearing">		
					<table data-toggle="table" id="data-table1" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/load/payments';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="received_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-search="true">
						<thead>
							<tr>
								<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>

								<th data-field="AR_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">AR No.</th>

								<th data-field="OR_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">OR No.</th>
								
								<th data-field="soa_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">SOA No.</th>

								<th data-field="company_name" data-align="left" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>

								<th data-field="payor" data-align="left" data-visible="true" data-filter-control="input" data-sortable="false">Payor</th>

								<th data-field="payment_type" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Payment Type</th>

								<th data-field="mode_of_collection" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Mode of Collection</th>

								<th data-field="particulars" data-align="left" data-visible="false" data-sortable="true" data-filter-control="select">Particulars</th>

								<th data-field="total_amount" data-align="right" data-visible="true" data-sortable="false" data-filter-control="input">Total Amount</th>

								<th data-field="received_on" data-align="left" data-visible="false" data-sortable="true" data-filter-control="input">Received On</th>

								<th data-field="received_by" data-align="left" data-visible="false" data-sortable="true" data-filter-control="input">Received By</th>	

								<th data-field="status" data-align="left" data-visible="true" data-sortable="true" data-filter-control="select">Status</th>

								<th data-field="operate" data-formatter="processAC" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
							</tr>
						</thead>
					</table>
	         	</div>
	         	<div role="tabpanel" class="tab-pane fade" id="tab_for_posting" aria-labelledby="tab_for_posting">

					<table data-toggle="table" id="data-table2" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/load/ac_transactions/payments';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="transaction_id" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-search="true">

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
	         	</div>

	         	<div role="tabpanel" class="tab-pane fade" id="tab_posted" aria-labelledby="tab_posted">
		
					<table data-toggle="table" id="data-table3" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/load/ac_transactions/payments/1';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="transaction_id" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-search="true">
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
	         	</div>
	         	
		</div>
	</div>
		
<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>

<script>

	$(function () {
		var $table1 = $('#data-table1');
		$table1.bootstrapTable();
		var $table2 = $('#data-table2');
		$table2.bootstrapTable();
		var $table3 = $('#data-table3');
		$table3.bootstrapTable();
	});

	function processAC(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'accounting/add/accounts_collection/';?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Process</a> ',
		].join('');
	}

	function viewAC(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'accounting/view/accounts_collection/';?>'+row['reference_id']+'/'+row['transaction_id']+'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">View</a> ',
		].join('');
	}

	
</script>