<?php
$potablecontents=$encodercontents=$approvercontents=$locationcontents=$topitemcontents	=	"";
if(!empty($purchase_orders)) {

	$monthly			=	array();
	$summary			=	array("encoder"=>array(), "location"=>array());
	$total_vatable=$total_vat=$total_discount=$total_etax=$total_amount=$total_served=0;
	foreach($purchase_orders as $poctr=>$purchase_order) {
		$summary['encoder'][$purchase_order['added_by']]['count']	=	(isset($summary['encoder'][$purchase_order['added_by']]['count']))?$summary['encoder'][$purchase_order['added_by']]['count']+1:1;
		$summary['approver'][$purchase_order['approved_by']]['count']	=	(isset($summary['approver'][$purchase_order['approved_by']]['count']))?$summary['approver'][$purchase_order['approved_by']]['count']+1:1;
		$summary['location'][$purchase_order['added_at']]['count']	=	(isset($summary['location'][$purchase_order['added_at']]['count']))?$summary['location'][$purchase_order['added_at']]['count']+1:1;
		$summary['encoder'][$purchase_order['added_by']]['amount']	=	(isset($summary['encoder'][$purchase_order['added_by']]['amount']))?$summary['encoder'][$purchase_order['added_by']]['amount']+$purchase_order['amount']:$purchase_order['amount'];
		$summary['approver'][$purchase_order['approved_by']]['amount']	=	(isset($summary['approver'][$purchase_order['approved_by']]['amount']))?$summary['approver'][$purchase_order['approved_by']]['amount']+$purchase_order['amount']:$purchase_order['amount'];
		$summary['location'][$purchase_order['added_at']]['amount']	=	(isset($summary['location'][$purchase_order['added_at']]['amount']))?$summary['location'][$purchase_order['added_at']]['amount']+$purchase_order['amount']:$purchase_order['amount'];
		$deliveries		=	$this->db->query("SELECT id FROM inventory_deliveries WHERE po_no=".$purchase_order['id']);
		$deliveries		=	$deliveries->result_array();
		$delivery_ids	=	"";
		$po_button		=	'<a class="btn btn-primary btn-xs" href="'.HTTP_PATH.'purchasing/purchase_order/view/'.$purchase_order['id'].'" data-toggle="modal" data-target="#modalDialog">View</a>';
		foreach($deliveries as $dctr=>$delivery) {
			$delivery_ids	.=	"<a href='".HTTP_PATH."inventory/print_rr/".$delivery['id']."' target='_new' class='btn-xs btn ".(($dctr>0)?"btn-danger":"btn-default")."'>RR T#".$delivery['id']."</a>";
		}
		if(!empty($deliveries)) {
			$total_served++;
			$summary['encoder'][$purchase_order['added_by']]['unserved']	=	(isset($summary['encoder'][$purchase_order['added_by']]['unserved']))?$summary['encoder'][$purchase_order['added_by']]['unserved']+1:1;
			$summary['approver'][$purchase_order['approved_by']]['unserved']	=	(isset($summary['approver'][$purchase_order['approved_by']]['unserved']))?$summary['approver'][$purchase_order['approved_by']]['unserved']+1:1;
			$summary['location'][$purchase_order['added_at']]['unserved']	=	(isset($summary['location'][$purchase_order['added_at']]['unserved']))?$summary['location'][$purchase_order['added_at']]['unserved']+1:1;
		}
		$accounts_payable_vouchers_sql	=	$this->db->query("SELECT id,payee FROM ac_ap_vouchers WHERE po_no=".$purchase_order['id']);
		$accounts_payable_vouchers_sql	=	$accounts_payable_vouchers_sql->result_array();
		$accounts_payable_vouchers	=	"";
		$cv_button="";
		$rfp_button="";
		$paid_status = "Unpaid";
		foreach($accounts_payable_vouchers_sql as $apvctr=>$apv) {

			$accounts_payable_vouchers	.=	"<a class='btn-xs btn ".(($apvctr>0)?"btn-danger":"btn-default")."'>APV T#".$apv['id']."</a>";
		
				$check_vouchers		=	$this->db->query("SELECT id,apv_no,transaction_type,status FROM ac_vouchers WHERE apv_no=".$apv['id']." AND payee=".$apv['payee']);
				$check_vouchers		=	$check_vouchers->result_array();
				
				foreach($check_vouchers as $dctr=>$check_voucher) {
					if($check_voucher['transaction_type']=='non-po'){
						$rfp_button	.=	"<a target='_new' class='btn-xs btn ".(($dctr>0)?"btn-danger":"btn-default")."'>RFP T#".$check_voucher['apv_no']."</a>";
					}else{
						$cv_button	.=	"<a target='_new' class='btn-xs btn ".(($dctr>0)?"btn-danger":"btn-default")."'>CV T#".$check_voucher['id']."</a>";
						if($check_voucher['status']=='Paid'){
							$paid_status = $check_voucher['status'];
						}
					}
				}
		}
		if(strtolower($purchase_order['supplier_vatable'])=='vatable'){
			$vat				=	(($purchase_order['amount']-$purchase_order['discount'])-(($purchase_order['amount']-$purchase_order['discount'])/1.12));
			$vatable_purchases	=	($purchase_order['amount']-$purchase_order['discount'])-$vat;
		}else{
			$vat = 0;
			$vatable_purchases = 0;
		}

		$request_payments		=	$this->db->query("SELECT id FROM ac_request_payment WHERE reference_id=".$purchase_order['id']." AND reference_table='inventory_po'");
		$request_payments		=	$request_payments->result_array();
		foreach($request_payments as $dctr=>$request_payment) {
			$rfp_button	.=	"<a target='_new' class='btn-xs btn ".(($dctr>0)?"btn-danger":"btn-default")."'>RFP T#".$request_payment['id']."</a>";
		}

		$etax				=	($vatable_purchases*($purchase_order['extended_tax']/100));
		$total_discount	+=	$purchase_order['discount'];
		$total_vat		+=	$vat;
		$total_vatable	+=	$vatable_purchases;
		$total_etax		+=	$etax;
		$total_amount	+=	$purchase_order['amount'];
		$potablecontents	.=	"<tr>";
		$potablecontents	.=	(!isset($company->id))?"<td>".$purchase_order['company_name']."</td>":"";

		$Xvessel = $this->Abas->getVessel($purchase_order['vessel_id']);

		$potablecontents	.=	"<td>".$Xvessel->name."</td>";
		$potablecontents	.=	"<td>".date("j F Y", strtotime($purchase_order['tdate']))." by ".$purchase_order['added_by']."</td>";
		$potablecontents	.=	"<td>".$purchase_order['request_id']."</td>";
		$potablecontents	.=	"<td>".$purchase_order['id']."/".$purchase_order['control_number']."</td>";
		$potablecontents	.=	"<td>".$purchase_order['status']."</td>";
		$potablecontents	.=	"<td>".date("j F Y", strtotime($purchase_order['approved_on']))." by ".$purchase_order['approved_by']."</td>";
		$potablecontents	.=	"<td>".$purchase_order['supplier_name']."</td>";
		$potablecontents	.=	"<td class='text-right'>".number_format($vat,2)."</td>";
		$potablecontents	.=	"<td class='text-right'>".number_format($vatable_purchases,2)."</td>";
		$potablecontents	.=	"<td class='text-right'>".number_format($etax,2)."</td>";
		$potablecontents	.=	"<td class='text-right'>".number_format($purchase_order['amount'],2)."</td>";
		
		$potablecontents		.=	"<th class='text-right'>".$paid_status."</th>";
		
		$potablecontents	.=	"<td>".$po_button.$delivery_ids.$accounts_payable_vouchers.$cv_button.$rfp_button."</td>";
		$potablecontents	.=	"</tr>";
	}
	if(!empty($summary['encoder'])) {
		foreach($summary['encoder'] as $encoder=>$value) {
			$encodercontents	.=	"<tr>";
			$encodercontents	.=	"<td>".$encoder."</td>";
			$encodercontents	.=	"<td>".(isset($value['unserved'])?$value['unserved']:0)." / ".(isset($value['count'])?$value['count']:0)."</td>";
			$encodercontents	.=	"<td class='text-right'>".number_format($value['amount'],2)."</td>";
			$encodercontents	.=	"</tr>";
		}
	}
	if(!empty($summary['approver'])) {
		foreach($summary['approver'] as $approver=>$value) {
			$approvercontents	.=	"<tr>";
			$approvercontents	.=	"<td>".$approver."</td>";
			$approvercontents	.=	"<td>".(isset($value['unserved'])?$value['unserved']:0)." / ".(isset($value['count'])?$value['count']:0)."</td>";
			$approvercontents	.=	"<td class='text-right'>".number_format($value['amount'],2)."</td>";
			$approvercontents	.=	"</tr>";
		}
	}
	if(!empty($summary['location'])) {
		foreach($summary['location'] as $location=>$value) {
			$locationcontents	.=	"<tr>";
			$locationcontents	.=	"<td>".$location."</td>";
			$locationcontents	.=	"<td>".(isset($value['unserved'])?$value['unserved']:0)." / ".(isset($value['count'])?$value['count']:0)."</td>";
			$locationcontents	.=	"<td class='text-right'>".number_format($value['amount'],2)."</td>";
			$locationcontents	.=	"</tr>";
		}
	}
	$summarytables		=	"<tr><th>Total</th><th>".$total_served." / ".($poctr+1)."</th><th>".number_format($total_amount,2)."</th></tr>";
	$encodercontents	.=	$summarytables;
	$approvercontents	.=	$summarytables;
	$locationcontents	.=	$summarytables;
	if(!isset($company->id)){
		$potablecontents		.=	"<th colspan='8'>Total</th>";
	}else{
		$potablecontents		.=	"<th colspan='7'>Total</th>";
	}
	$potablecontents		.=	"<th class='text-right'>".number_format($total_vat,2)."</th>";
	$potablecontents		.=	"<th class='text-right'>".number_format($total_vatable,2)."</th>";
	$potablecontents		.=	"<th class='text-right'>".number_format($total_etax,2)."</th>";
	$potablecontents		.=	"<th class='text-right'>".number_format($total_amount,2)."</th>";
	$potablecontents		.=	"<th>Served: ".$total_served." / ".($poctr+1)."</th>";
}
if(!empty($top_items)) {
	$itemctr=1;
	foreach($top_items as $itemctr=>$top_item) {
		$topitemcontents	.=	"<tr>";
		$topitemcontents	.=	"<td>".($itemctr+1)."</td>";
		$topitemcontents	.=	"<td class='text-center'>".$top_item['company_name']."</td>";
		$vessel = $this->Abas->getVessel($top_item['vessel_id']);
		$topitemcontents	.=	"<td>".$vessel->name."</td>";
		$topitemcontents	.=	"<td>".$top_item['description'].", ".$top_item['particular']."</td>";
		$topitemcontents	.=	"<td>".$top_item['category_name']."</td>";
		$topitemcontents	.=	"<td class='text-center'>".$top_item['po_id']."</td>";
		$topitemcontents	.=	"<td class='text-center'>".date('Y-m-d',strtotime($top_item['tdate']))."</td>";
		$topitemcontents	.=	"<td>".number_format($top_item['total_amount'],2,'.',',')."</td>";
		$topitemcontents	.=	"<td class='text-right'>".number_format($top_item['total_quantity'],2,'.',',')."</td>";
		$topitemcontents	.=	"<td class='text-right'>".$top_item['supplier_name']."</td>";

		$deliveries		=	$this->db->query("SELECT id FROM inventory_deliveries WHERE po_no=".$top_item['po_id']);
		$deliveries		=	$deliveries->result_array();

		if(count($deliveries)>0){
			$status = 'Delivered';
		}else{
			$status = 'For Delivery';
		}
		
		$topitemcontents	.=	"<td class='text-center'>".$status."</td>";
		$topitemcontents    .=  '<td><a class="btn btn-primary btn-xs" href="'.HTTP_PATH.'purchasing/purchase_order/view/'.$top_item['po_id'].'" data-toggle="modal" data-target="#modalDialog">View</a></td>';
		$topitemcontents	.=	"</tr>";
	}
}
?>
<h2 class="text-center"><?php echo $company->name; ?></h2>
<h3 class="text-center">Purchase Order Report</h3>
<p>From <?php echo date("j F Y",strtotime($_GET['dstart'])); ?> to <?php echo date("j F Y",strtotime($_GET['dfinish'])); ?></p>
<div class="col-md-4">
	<table id="encoder_report" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>PO Count (Served/Unserved)</th>
				<th>Amount</th>
			</tr>
		</thead>
		<?php echo $encodercontents; ?>
	</table>
</div>
<div class="col-md-4">
	<table id="approver_report" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>PO Count (Served/Unserved)</th>
				<th>Amount</th>
			</tr>
		</thead>
		<?php echo $approvercontents; ?>
	</table>
</div>
<div class="col-md-4">
	<table id="location_report" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>Location</th>
				<th>PO Count (Served/Unserved)</th>
				<th>Amount</th>
			</tr>
		</thead>
		<?php echo $locationcontents; ?>
	</table>
</div>
<div class="col-md-12 col-sm-12" style="overflow-x: auto;">
	<table id="po_report" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<?php if(!isset($company->id)): ?>
				<th>Company</th>
				<?php endif; ?>
				<th>Vessel/Office</th>
				<th>Created On</th>
				<th>Requisition Transaction Code </th>
				<th>PO Transaction Code <br/>/<br/>Control Number</th>
				<th>Status</th>
				<th>Approved by</th>
				<th>Supplier</th>
				<th>VAT</th>
				<th>VATable Purchases</th>
				<th>Expanded Withholding Tax</th>
				<th>Amount</th>
				<th>Payment?</th>
				<th>Related documents TCode</th>
			</tr>
		</thead>
		<?php echo $potablecontents; ?>
	</table>
</div>
<hr>
<div class="col-md-12 col-sm-12" style="overflow-x: auto;">
<h3 class="text-center">Purchased Items Summary</h3>
<table id="item_report" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Company</th>
			<th>Vessel/Office</th>
			<th>Item Description</th>
			<th>Category</th>
			<th>PO Transaction Code</th>
			<th>PO Date</th>
			<th>Total Amount</th>
			<th>Total Quantity</th>
			<th>Supplier</th>
			<th>Status</th>
			<th>Related PO</th>
		</tr>
	</thead>
	<?php echo $topitemcontents; ?>
</table>
</div>
<script>
function showPerVessel(itemid) {
	$("#item"+itemid).toggleClass('hide');
}
</script>