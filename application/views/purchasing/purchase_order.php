<?php
$po=$selected_po;
//$this->Mmm->debug($po);
if(!empty($po['details'])) {
	$itemstable	=	"";
	$totalcost	=	0;
	foreach($po['details'] as $d) {
		$item		=	$this->Inventory_model->getItem($d['item_id']);
		$item		=	$item[0];
		$totalcost	=	$totalcost + ($d['unit_price']*$d['quantity']);
		$itemstable	.=	"<tr>";
			$itemstable	.=	"<td>".$item['description']."</td>";
			$itemstable	.=	"<td>".$d['request_detail']['canvass_approved_by']['full_name']."</td>";
			$itemstable	.=	"<td>".$d['quantity']."</td>";
			if($d['packaging']==''){
				$itemstable	.=	"<td>".$item['unit']."</td>";
			}else{
				$itemstable	.=	"<td>".$d['packaging']."</td>";
			}
			$itemstable	.=	"<td>P".number_format($d['unit_price'],2)."</td>";
			$itemstable	.=	"<td>P".number_format(($d['unit_price']*$d['quantity']),2)."</td>";
		$itemstable	.=	"</tr>";
	}
}
$supplierdata		=	$this->Abas->getSupplier($po['supplier_id']);
//$this->Mmm->debug($supplierdata);
$vat				=	0;
$vatable_purchases	=	0;
$gross_purchases	=	0;
$vat				=	0;
$etax				=	0;
$approver			=	"";
$vatable_purchases	=	$totalcost;
$grand_total		=	$totalcost;
if($supplierdata['issues_reciepts']==1) {
	$gross_purchases	=	$totalcost-$po['discount'];
	if(strtolower($supplierdata['vat_computation'])=='vatable') {
		$vat				=	(($totalcost-$po['discount'])-(($totalcost-$po['discount'])/1.12));
		$vatable_purchases	=	($totalcost-$po['discount'])-$vat;
	}
	$etax				=	($vatable_purchases*($po['extended_tax']/100));//(($po['extended_tax']/$totalcost)*100);
	$etax_percentage	=	0;
	if($po['extended_tax']>0) {
		$etax_percentage=	$po['extended_tax'];
		$grand_total	=	$gross_purchases-$po['extended_tax'];
	}
}
$grand_total		=	$gross_purchases-$etax-$po['discount'];
$approver			=	'';
$print_po_btn		=	'<a href="'.HTTP_PATH.'purchasing/purchase_order/print/'.$po['id'].'" class="btn btn-info" target="_new">Print</a>';
$approval_btn		=	'<a href="'.HTTP_PATH.'purchasing/purchase_order/approve/'.$po['id'].'" class="btn btn-success">Approve</a>';
$cancel_po_btn		=	'<a class="btn btn-danger" onclick="javascript:confirmCancelPo('.$po['id'].')" target="_new">Cancel</a>';
if($po['status']=="Cancelled") {
	$print_po_btn=$cancel_po_btn="";
}
if(!$this->Abas->checkPermissions("purchasing|cancel_po",false)) {
	$cancel_po_btn	=	"";
}
if(empty($po['approved_by']) || empty($po['approved_on'])) {
	$print_po_btn	=	"";
}
else {
	$approver		=	"<p>PO approved by ".$po['approved_by']['full_name']." on ".date("j F Y h:i a", strtotime($po['approved_on']))."</p>";
}
$request			=	$this->Purchasing_model->getRequest($po['request_id']);
//$this->Mmm->debug($request);
$request_approver	=	$request['details'][0]['request_approved_by']['full_name']." on ".date('j F Y h:i a',strtotime($request['details'][0]['request_approved_on']));
?>

<div class="panel panel-info">
	<div class="panel-heading" role="tab" id="headingPO<?php echo $po['id']; ?>">
		<h4 class="panel-title">
			<a role="button" data-toggle="collapse" data-parent="#po_container" href="#bodyPO<?php echo $po['id']; ?>" aria-expanded="true" aria-controls="bodyPO<?php echo $po['id']; ?>">
				<?php echo 'Transaction Code: '.$po['id'].' | Control No.'.$po['control_number']; ?>
				<span class="pull-right">Status: <?php echo ucwords($po['status']); ?></span>
			</a>
		</h4>
	</div>
</div>
	<div id="bodyPO<?php echo $po['id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingPO<?php echo $po['id']; ?>">
		<div class="col-lg-12 col-md-12 col-xs-12 text-center">
			<h3><?php echo $po['supplier_name']; ?></b></h3>
			<h2><?php echo $po['company_name']; ?></h2>
			<p><?php echo $po['vessel_name']; ?></p>
			<p><?php echo $approver; ?></p>
			<p><?php echo (!empty($request_approver))?"Requisition approved by ".$request_approver:""; ?></p>
		</div>
		<div class="col-lg-12 col-md-12 col-xs-12">
			<?php echo ($po['user_can_approve'])?$approval_btn:""; ?>
			<?php echo $print_po_btn; ?>
			<?php echo $cancel_po_btn; ?>
			<div class="table table-border">
				<table class="table table-striped table-bordered text-center">
					<thead>
						<tr>
							<th>Item</th>
							<th>Canvass Approved By</th>
							<th>Quantity</th>
							<th>Unit/Packaging</th>
							<th>Unit Price</th>
							<th>Total Price</th>
						</tr>
					<thead>
					<tbody>
						<?php echo $itemstable; ?>
					</tbody>
					<tfoot>
						<tr><td class="text-right" colspan=5>Gross Purchases</td><td>P<?php echo number_format($gross_purchases,2); ?></td></tr>
						<tr><td class="text-right" colspan=5>VATable Purchases</td><td>P<?php echo number_format($vatable_purchases,2); ?></td></tr>
						<tr><td class="text-right" colspan=5>12% VAT</td><td>P<?php echo number_format($vat,2); ?></td></tr>
						<tr><td class="text-right" colspan=5>Withholding Tax - Expanded</td><td>(P<?php echo number_format($etax,2); ?>)</td></tr>
						<tr><td class="text-right" colspan=5>Total Amount Payable</td><td>P<?php echo number_format($grand_total,2); ?></td></tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="clearfix"><br/></div>
	</div>