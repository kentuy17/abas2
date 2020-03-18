<?php
	$r_tab_active = 'class="active"';
	$r_in_active = 'in active';
	$c_tab_active = '';
	$c_in_active = '';
	$v_tab_active = '';
	$v_in_active = '';

	if(isset($_SESSION['tab'])) {
		if($_SESSION['tab'] == 'voucher_deliveries'){
			$c_tab_active = 'class="active"';
			$c_in_active = 'in active';
		}
		elseif($_SESSION['tab'] == 'services'){
			$v_tab_active = 'class="active"';
			$v_in_active = 'in active';
		}
		elseif($_SESSION['tab'] == 'cash_advance'){
			$r_tab_active = 'class="active"';
			$r_in_active = 'in active';
		}
	}
?>

<ul class="nav nav-tabs bg-success table-responsive">
	<li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##purchasing">Purchases <span class="badge"><?php echo count($voucher_deliveries) ?></span></a></li>
	<li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##services">Requests for Payment <span class="badge"><?php echo count($requestpaymentList) ?></span></a></li>
	<li <?php echo $v_tab_active; ?>><a data-toggle="tab" href="##cash_advance">Cash Requests  <span class="badge" ><?php echo count($cash_advance) ?></span></a> </li>
</ul>

<div  class="tab-content">

	<div id="purchasing" class="tab-pane fade <?php echo $r_in_active ?>">
		<table data-toggle="table" id="vouchers-for-delivery-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."accounting/vouchers/purchasing_voucher_json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
			<thead>
				<tr>
					<th data-field="tdate" data-visible="true" data-sortable="true">Date Delivered</th>
					<th data-field="voucher_number" data-visible="true" data-sortable="false">Voucher Number</th>
					<th data-field="po_no" data-visible="true" data-sortable="true">Purchase Order Number</th>
					<th data-field="supplier_name" data-visible="true" data-sortable="false">Supplier</th>
					<th data-field="amount" data-visible="true" data-sortable="true">Amount</th>
					<th data-field="voucher_status" data-visible="true" data-sortable="false">Status</th>
					<th data-field="location" data-visible="false" data-sortable="true">Location</th>
					<th data-field="remark" data-visible="false" data-sortable="true">Remark</th>
					<th data-field="operate" data-formatter="manageVouchersForDelivery" data-halign="center" data-align="center">Manage</th>
				</tr>
			</thead>
		</table>
	</div>
	<div id="services" class="tab-pane fade <?php echo $c_in_active ?> table-responsive">
		<br>
		 <hr />
		 <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
	  <thead>
		 <tr>
			<th width="10%">Request Date</th>
			<th width="20%">Particular</th>
			<th width="10%">Amount</th>
			<th width="5%">Type</th>
			<th width="15%">Payee</th>
			<th width="7%">Reference No</th>
			<th width="10%">Remark</th>
			<th width="10%">Status</th>
			<th width="5%">Manage</th>
		 </tr>
	  </thead>
	  <tbody>
	   <?php
		if( !empty($requestpaymentList) ) {
			foreach ($requestpaymentList as $request_payment)

			{
				$id = $request_payment['id'];
				$reference_no = $request_payment['reference_no'];
				$requested_by = $request_payment['requested_by'];
				$request_date = $request_payment['request_date'];
				$particular = $request_payment['particular'];
				$amount = $request_payment['amount'];
				$type = $request_payment['type'];
				$payee = $request_payment['payee'];
				$remark = $request_payment['remark'];
				$status = $request_payment['status'];
				//var_dump($request_payment);exit;
				//var_dump($portList);exit;
				//echo (empty($bank)? "<h2 style='margin-top:30px;'><center>You have no Reservations!</center></h2>": ""); //result not empty
			?>
		 <tr>
			<td align="center"><?php  echo date('F j, Y', strtotime($request_date)) ?></td>
			<td align="left"><?php  echo $particular; ?></td>
			<td align="right"><?php  echo number_format($amount,2); ?>&nbsp;</td>
			<td><?php  echo $type; ?></td>
			<td><?php  echo $payee; ?></td>
			<td><?php  echo $reference_no; ?></td>
			<td align="left"><?php  echo $remark; ?></td>
			<td><?php  echo $status; ?></td>

			<td align="center">
			   <a class="like" href="<?php echo HTTP_PATH ?>accounting/vouchers/create/<?php echo $id; ?>/rfp" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
			   <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
			   </a>
			</td>
		 </tr>

			   <?php

					}
				}else{

						echo '<tr><td  colspan="9">No record found</td></tr>';
				}
				?>
			</tbody>
		 </table>


	</div>

	<div id="cash_advance" class="tab-pane fade <?php echo $v_in_active ?>">
		<table data-toggle="table" id="cash-requests-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."accounting/vouchers/cash_request_json"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
			<thead>
				<tr>
					<th data-field="date_requested" data-visible="true" data-sortable="true">Date Requested</th>
					<th data-field="requested_by" data-visible="true" data-sortable="false">Requested By</th>
					<th data-field="amount" data-visible="true" data-sortable="true">Amount</th>
					<th data-field="purpose" data-visible="true" data-sortable="false">Purpose</th>
					<th data-field="department_name" data-visible="true" data-sortable="true">Department</th>
					<th data-field="type" data-visible="true" data-sortable="false">Request Type</th>
					<th data-field="voucher_id" data-visible="false" data-sortable="true">Voucher ID</th>
					<th data-field="location" data-visible="false" data-sortable="true">Location</th>
					<th data-field="voucher_status" data-visible="true" data-sortable="true">Status</th>
					<th data-field="operate" data-formatter="manageCashRequests" data-halign="center" data-align="center">Manage</th>
				</tr>
			</thead>
		</table>
	</div>

</div>
<script>
	function manageVouchersForDelivery(value, row, index) {
		if(row['voucher_status']=='For Processing') {
			return [
			'<a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH ?>accounting/vouchers/create/'+row['id']+'/purchase" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">Create Voucher</a>'
			].join('');
		}
		else if(row['voucher_status']=='For Voucher Printing' || row['voucher_status'] == 'For Releasing') {
			return [
				'<div class="dropup">',
					'<button class="btn btn-default dropdown-toggle btn-xs" type="button" id="printmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Print <span class="caret"></span></button>',
					'<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="printmenu">',
						'<li><a href="<?php echo HTTP_PATH ?>accounting/vouchers/print_voucher/'+row['voucher_id']+'">Voucher</a></li>',
						'<li><a href="<?php echo HTTP_PATH ?>accounting/vouchers/print_2307/'+row['voucher_id']+'">Form 2307</a></li>',
					'</ul>',
				'</div>'
			].join('');
		}
		else {
			return [''].join('');
		}
	}
	function manageCashRequests(value, row, index) {
		if(row['voucher_status']=='For Processing') {
			return [
			//'<a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH ?>accounting/voucher/edit_cash_request/'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">Edit</a>'
			'<a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH ?>accounting/voucher_CRform/'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">Create Voucher</a>'
			].join('');
		}
		else if(row['voucher_status']=='For Voucher Printing' || row['voucher_status'] == 'For Releasing') {
			return [
				'<a class="btn btn-success btn-xs" href="<?php echo HTTP_PATH ?>accounting/print_cash_request/'+row['id']+'" data-toggle="modal" data-target="#modalDialog">Print</a>'
			].join('');
		}
		else {
			return [''].join('');
		}
	}
	$(function () {
		var $table = $('#vouchers-for-delivery-table');
		$table.bootstrapTable();
	});
</script>