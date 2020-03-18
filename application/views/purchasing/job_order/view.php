<?php

$request			=	$this->Purchasing_model->getRequest($jo->request_id);
$request_approver	=	$request['details'][0]['request_approved_by']['full_name'];

if(!empty($jo_details)) {
	$servicestable	=	"";
	$totalcost	=	0;
	foreach($jo_details as $d) {
		$item		=	$this->Inventory_model->getItem($d->item_id);
		$item		=	$item[0];
		$sql = "SELECT * FROM inventory_request_details WHERE request_id=".$request['id']." AND item_id=".$d->item_id." AND unit_price is null";
		$query = $this->db->query($sql);
		$service_description = $query->row(); 
		$totalcost	=	$totalcost + ($d->unit_price*$d->quantity);
		$servicestable	.=	"<tr>";
			$servicestable	.=	"<td>".$item['item_name'].",".$item['brand']." ".$item['particular']." - ".$service_description->remark."</td>";
			$canvass = $this->Purchasing_model->getRequestDetail($d->request_detail_id);
			$servicestable	.=	"<td>".$canvass['canvass_approved_by']['full_name']."</td>";
			$servicestable	.=	"<td>".$d->quantity."</td>";
			$servicestable	.=	"<td>P".number_format($d->unit_price,2)."</td>";
			$servicestable	.=	"<td>P".number_format((round($d->unit_price,2)*$d->quantity),2)."</td>";
		$servicestable	.=	"</tr>";
	}
}
$supplierdata		=	$this->Abas->getSupplier($jo->supplier_id);
$vat				=	0;
$vatable_purchases	=	0;
$gross_purchases	=	0;
$vat				=	0;
$etax				=	0;
$approver			=	"";
$vatable_purchases	=	$totalcost;
$grand_total		=	$totalcost;
if($supplierdata['issues_reciepts']==1) {
	$gross_purchases	=	$totalcost-$jo->discount;
	if(strtolower($supplierdata['vat_computation'])=='vatable') {
		$vat				=	(($totalcost-$jo->discount)-(($totalcost-$jo->discount)/1.12));
		$vatable_purchases	=	($totalcost-$jo->discount)-$vat;
	}
	$etax				=	($vatable_purchases*($jo->extended_tax/100));
	$etax_percentage	=	0;
	if($jo->extended_tax>0) {
		$etax_percentage=	$jo->extended_tax;
		$grand_total	=	$gross_purchases-$jo->extended_tax;
	}
	else {
		$grand_total	=	$totalcost-$jo->discount;
	}
}
$grand_total = $gross_purchases-$etax-$jo->discount;
$approver			=	'';
$print_btn		=	'<a href="'.HTTP_PATH.'purchasing/job_order/print/'.$jo->id.'" class="btn btn-info" target="_new">Print</a>';
$approval_btn		=	'<a href="'.HTTP_PATH.'purchasing/job_order/approve/'.$jo->id.'" class="btn btn-success">Approve</a>';
$cancel_btn		=	'<a class="btn btn-danger" onclick="javascript:confirmCancelJo('.$jo->id.')" target="_new">Cancel</a>';
if($jo->status=="Cancelled") {
	$print_btn=$cancel_btn="";
}
if(!$this->Abas->checkPermissions("purchasing|cancel_jo",false)) {
	$cancel_btn	=	"";
}
if(empty($jo->approved_by) || empty($jo->approved_on)) {
	$print_btn	=	"";
}
else {
	$approver		=	"<p>JO approved by ".$jo->approved_by['full_name']." on ".date("j F Y h:i a", strtotime($jo->approved_on))."</p>";
}
$request			=	$this->Purchasing_model->getRequest($jo->request_id);
$request_approver	=	$request['details'][0]['request_approved_by']['full_name']." on ".date('j F Y h:i a',strtotime($request['details'][0]['request_approved_on']));

?>

<div class="panel panel-info">
	<div class="panel-heading" role="tab" id="headingJO<?php echo $jo->id; ?>">
		<h4 class="panel-title">
			<a role="button" data-toggle="collapse" data-parent="#jo_container" href="#bodyJO<?php echo $jo->id; ?>" aria-expanded="true" aria-controls="bodyJO<?php echo $jo->id; ?>">
				<?php echo 'Transaction Code: '.$jo->id.' | Control No.'.$jo->control_number; ?>
				<span class="pull-right">Status: <?php echo ucwords($jo->status); ?></span>
			</a>
		</h4>
	</div>
	<div id="bodyJO<?php echo $jo->id; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingJO<?php echo $jo->id; ?>">
		<div class="col-lg-12 col-md-12 col-xs-12 text-center">
			<h3><b><?php echo $jo->supplier_name; ?></b></h3>
			<h3><?php echo $jo->company_name; ?></h3>
			<p><?php echo $jo->vessel_name; ?></p>
			<p><?php echo $approver; ?></p>
			<p><?php echo (!empty($request_approver))?"Requisition approved by ".$request_approver:""; ?></p>
		</div>
	</div>
		<div class="col-lg-12 col-md-12 col-xs-12">
			<?php echo ($jo->user_can_approve)?$approval_btn:""; ?>
			<?php echo $print_btn; ?>
			<?php echo $cancel_btn; ?>
			<div class="table-responsive">
				<table class="table table-striped table-bordered text-center">
					<thead>
						<tr>
							<th>Service</th>
							<th>Canvass Approved By</th>
							<th>Quantity</th>
							<th>Unit Price</th>
							<th>Total Price</th>
						</tr>
					<thead>
					<tbody>
						<?php echo $servicestable; ?>
					</tbody>
					<tfoot>
						<tr><td class="text-right" colspan=4>Gross Purchases</td><td>P<?php echo number_format($gross_purchases,2); ?></td></tr>
						<tr><td class="text-right" colspan=4>VATable Purchases</td><td>P<?php echo number_format($vatable_purchases,2); ?></td></tr>
						<tr><td class="text-right" colspan=4>12% VAT</td><td>P<?php echo number_format($vat,2); ?></td></tr>
						<tr><td class="text-right" colspan=4>Withholding Tax - Expanded</td><td>(P<?php echo number_format($etax,2); ?>)</td></tr>
						<tr><td class="text-right" colspan=4>Total Amount Payable</td><td>P<?php echo number_format($grand_total,2); ?></td></tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="clearfix"><br/></div>
	</div>