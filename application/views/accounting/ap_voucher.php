<?php
$r_tab_active=$r_in_active=$c_tab_active=$c_in_active=$v_tab_active=$v_in_active='';
if($tab=='voucher_deliveries'){
	$c_tab_active	=	'class="active"';
	$c_in_active	=	'in active';
}
elseif($tab=='services'){
	$v_tab_active	=	'class="active"';
	$v_in_active	=	'in active';
}
elseif($tab=='cash_advance'){
	$r_tab_active	=	'class="active"';
	$r_in_active	=	'in active';
}
else{
	$r_tab_active	=	'class="active"';
	$r_in_active	=	'in active';
}
?>
<div class="x_title">
	<h2>Payables</h2>
	<div class="clearfix"></div>
</div>
<div class="nav navbar-right panel_toolbox">
	<a class="btn btn-warning btn-xs" href="<?php echo HTTP_PATH ?>accounting/ap_voucher_search" data-toggle="modal" data-target="#modalDialog" title="Search Voucher">Search Check Voucher</a>
</div>
<div class="nav navbar-left panel_toolbox">
	<input type="text" id="search" class="form-control" value="" placeholder="APV Transaction Code"/>
	<a class="btn btn-primary btn-xs" id="create_from_ap_voucher" href="" data-toggle="modal" data-target="#modalDialog" title="Search Voucher">Create from AP Voucher</a>
</div>
<div class="x_content">
	<ul class="nav nav-tabs bg-success table-responsive">
		<li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##purchasing">PO Transactions <span class="badge"><?php echo count($ap_vouchers) ?></span></a></li>
		<li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##services">Non-PO Transactions <span class="badge"><?php echo count($non_po) ?></span></a></li>
	</ul>
	<div class="tab-content">
		<div id="purchasing" class="tab-pane fade <?php echo $r_in_active ?> table-responsive">
			<table data-toggle="table" id="hr-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."accounting/ap_vouchers/po"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
				<thead>
					<tr>
						<th data-field="created_on" data-align="center" data-sortable="true" data-filter-control="input">APV Date</th>
						<th data-field="control_number" data-align="left" data-sortable="true" data-filter-control="input">AP Voucher #</th>
						<th data-field="po_no" data-align="left" data-sortable="true" data-filter-control="input">PO Number</th>
						<th data-field="payee_name" data-align="center" data-sortable="true" data-filter-control="input">Payee</th>
						<th data-field="amount" data-align="center" data-sortable="false" data-filter-control="input">Amount</th>
						<th data-field="payment_schedule" data-align="center" data-sortable="false" data-filter-control="input">Schedule of Payment</th>
						<th data-field="company_name" data-align="center" data-sortable="true" data-filter-control="input">Company</th>
						<th data-field="manage" data-halign="center" data-align="center">Manage</th>
					</tr>
				</thead>
			</table>
		</div>
		<div id="services" class="tab-pane fade <?php echo $c_in_active ?> table-responsive">
			<br/>
			<hr/>
			<table id="datatable-responsive1" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="10%">Request Date</th>
						<th width="15%">Payee</th>
						<th width="33%">Particular</th>
						<th width="10%">Amount</th>
						<th width="7%">Reference No</th>
						<th width="10%">Remark</th>
						<th width="10%">Status</th>
						<th width="5%">Manage</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if( !empty($non_po) ) {
						foreach ($non_po as $request_payment) {
							$id				=	$request_payment['id'];
							$reference_no	=	$request_payment['reference_no'];
							$requested_by	=	$request_payment['requested_by'];
							$request_date	=	$request_payment['request_date'];
							$particular		=	$request_payment['purpose'];
							$amount			=	$request_payment['amount'];
							$type			=	$request_payment['type'];
							$payee_id		=	$request_payment['payee'];
							$payee_type		=	$request_payment['payee_type'];
							$remark			=	$request_payment['remark'];
							$status			=	$request_payment['status'];
							$payee			= '';
							if($payee_type=='Employee'){
								//get employee name
								$p			=	$this->Abas->getEmployee($payee_id);
								$payee		=	$p['full_name'];
							}elseif($payee_type=='Supplier'){
								$p			=	$this->Abas->getSupplier($payee_id);
								$payee		=	$p['name'];
							}else{
								$payee		=	$request_payment['payee_others'];
							}
							if($payee_id==''){
								$payee		=	$request_payment['payee_others'];
							}
							?>
							<tr>
								<td align="center"><?php echo date('F j, Y', strtotime($request_date)) ?></td>
								<td><?php echo $payee; ?></td>
								<td align="left"><?php echo $particular; ?></td>
								<td align="right"><?php echo number_format($amount,2); ?></td>
								<td><?php echo $reference_no; ?></td>
								<td align="left"><?php echo $remark; ?></td>
								<td><?php echo $status; ?></td>
								<td align="center">
									<a class="like" href="<?php echo HTTP_PATH ?>accounting/ap_form/<?php echo $id ?>/non-po" data-toggle="modal" data-target="#modalDialog" title="View Details">
										<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
									</a>
								</td>
							</tr>
							<?php
						}
					}
					else{
						echo '<tr><td	colspan="9">No record found</td></tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$('#datatable-responsive1').DataTable();
});
$(function () {
	var $table = $('#hr-table');
	$table.bootstrapTable();
});
$('#search').keyup(function(event) {
	var apvtc=$("#search").val();
	alert("<?php echo HTTP_PATH."accounting/ap_form/"; ?>"+apvtc+"/po");
	$("#create_from_ap_voucher").attr("href","<?php echo HTTP_PATH."accounting/ap_form/"; ?>"+apvtc+"/po");
});
</script>
