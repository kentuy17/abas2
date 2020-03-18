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
	<li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##purchasing">Purchasing Vouchers <span class="badge"><?php echo count($voucher_deliveries) ?></span></a></li>
	<li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##services">Service Providers <span class="badge"><?php //echo count($services) ?></span></a></li>
	<li <?php echo $v_tab_active; ?>><a data-toggle="tab" href="##cash_advance">Cash Requests <span class="badge" ><?php //echo count($cash_advance) ?></span></a> </li>
</ul>
<div class="tab-content">
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
	<div id="services" class="tab-pane fade <?php echo $c_in_active ?>">
		Services
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
			'<a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH ?>accounting/vouchers/create/'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">Create Voucher</a>'
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