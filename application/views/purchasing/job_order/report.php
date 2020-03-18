<?php
$jotablecontents=$encodercontents=$approvercontents=$locationcontents=$topitemcontents	=	"";
if(!empty($job_orders)) {
	$monthly			=	array();
	$summary			=	array("encoder"=>array(), "location"=>array());
	$total_vatable=$total_vat=$total_discount=$total_etax=$total_amount=$total_served=0;
	foreach($job_orders as $joctr=>$job_order) {
		$summary['encoder'][$job_order['added_by']]['count']	=	(isset($summary['encoder'][$job_order['added_by']]['count']))?$summary['encoder'][$job_order['added_by']]['count']+1:1;
		$summary['approver'][$job_order['approved_by']]['count']	=	(isset($summary['approver'][$job_order['approved_by']]['count']))?$summary['approver'][$job_order['approved_by']]['count']+1:1;
		$summary['location'][$job_order['added_at']]['count']	=	(isset($summary['location'][$job_order['added_at']]['count']))?$summary['location'][$job_order['added_at']]['count']+1:1;
		$summary['encoder'][$job_order['added_by']]['amount']	=	(isset($summary['encoder'][$job_order['added_by']]['amount']))?$summary['encoder'][$job_order['added_by']]['amount']+$job_order['amount']:$job_order['amount'];
		$summary['approver'][$job_order['approved_by']]['amount']	=	(isset($summary['approver'][$job_order['approved_by']]['amount']))?$summary['approver'][$job_order['approved_by']]['amount']+$job_order['amount']:$job_order['amount'];
		$summary['location'][$job_order['added_at']]['amount']	=	(isset($summary['location'][$job_order['added_at']]['amount']))?$summary['location'][$job_order['added_at']]['amount']+$job_order['amount']:$job_order['amount'];

		$delivery_ids ="";
		$request_payments		=	$this->db->query("SELECT id FROM ac_request_payment WHERE reference_id=".$job_order['id']." AND reference_table='inventory_job_orders'");
		$request_payments		=	$request_payments->result_array();
		$request_payment_ids	=	"";
		$jo_button		=	'<a class="btn btn-primary btn-xs" href="'.HTTP_PATH.'purchasing/job_order/view/'.$job_order['id'].'" data-toggle="modal" data-target="#modalDialog">View</a>';
		foreach($request_payments as $dctr=>$request_payment) {
			$delivery_ids	.=	"<a href='".HTTP_PATH."purchasing/job_order/view_request_for_payment/".$request_payment['id']."' data-toggle='modal' data-target='#modalDialog' class='btn-xs btn ".(($dctr>0)?"btn-danger":"btn-default")."'>RFP T#".$request_payment['id']."</a>";
		}
		if(!empty($request_payments)) {
			$total_served++;
			$summary['encoder'][$job_order['added_by']]['unserved']	=	(isset($summary['encoder'][$job_order['added_by']]['unserved']))?$summary['encoder'][$job_order['added_by']]['unserved']+1:1;
			$summary['approver'][$job_order['approved_by']]['unserved']	=	(isset($summary['approver'][$job_order['approved_by']]['unserved']))?$summary['approver'][$job_order['approved_by']]['unserved']+1:1;
			$summary['location'][$job_order['added_at']]['unserved']	=	(isset($summary['location'][$job_order['added_at']]['unserved']))?$summary['location'][$job_order['added_at']]['unserved']+1:1;
		}
		if(strtolower($job_order['supplier_vatable'])=='vatable'){	
			$vat				=	(($job_order['amount']-$job_order['discount'])-(($job_order['amount']-$job_order['discount'])/1.12));
			$vatable_purchases	=	($job_order['amount']-$job_order['discount'])-$vat;
		}else{
			$vat = 0;
			$vatable_purchases = 0;
		}		
		$etax				=	($vatable_purchases*($job_order['extended_tax']/100));
		$total_discount	+=	$job_order['discount'];
		$total_vat		+=	$vat;
		$total_vatable	+=	$vatable_purchases;
		$total_etax		+=	$etax;
		$total_amount	+=	$job_order['amount'];
		$jotablecontents	.=	"<tr>";
		$jotablecontents	.=	(!isset($company->id))?"<td>".$job_order['company_name']."</td>":"";

		$Xvessel = $this->Abas->getVessel($job_order['vessel_id']);

		$jotablecontents	.=	"<td>".$Xvessel->name."</td>";
		
		$jotablecontents	.=	"<td>".date("j F Y", strtotime($job_order['tdate']))." by ".$job_order['added_by']."</td>";
		$jotablecontents	.=	"<td>".$job_order['request_id']."</td>";
		$jotablecontents	.=	"<td>".$job_order['id']."/".$job_order['control_number']."</td>";
		$jotablecontents	.=	"<td>".$job_order['status']."</td>";
		$jotablecontents	.=	"<td>".date("j F Y", strtotime($job_order['approved_on']))." by ".$job_order['approved_by']."</td>";
		$jotablecontents	.=	"<td>".$job_order['supplier_name']."</td>";
		$jotablecontents	.=	"<td class='text-right'>".number_format($vat,2)."</td>";
		$jotablecontents	.=	"<td class='text-right'>".number_format($vatable_purchases,2)."</td>";
		$jotablecontents	.=	"<td class='text-right'>".number_format($etax,2)."</td>";
		$jotablecontents	.=	"<td class='text-right'>".number_format($job_order['amount'],2)."</td>";
		$jotablecontents	.=	"<td>".$jo_button.$delivery_ids."</td>";
		$jotablecontents	.=	"</tr>";
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
			$approvercontents	.=	"<td>".(isset($value['unserved'])?$value['unserved']:0)." / ".$value['count']."</td>";
			$approvercontents	.=	"<td class='text-right'>".number_format($value['amount'],2)."</td>";
			$approvercontents	.=	"</tr>";
		}
	}
	if(!empty($summary['location'])) {
		foreach($summary['location'] as $location=>$value) {
			$locationcontents	.=	"<tr>";
			$locationcontents	.=	"<td>".$location."</td>";
			$locationcontents	.=	"<td>".(isset($value['unserved'])?$value['unserved']:0)." / ".$value['count']."</td>";
			$locationcontents	.=	"<td class='text-right'>".number_format($value['amount'],2)."</td>";
			$locationcontents	.=	"</tr>";
		}
	}
	$summarytables		=	"<tr><th>Total</th><th>".$total_served." / ".($joctr+1)."</th><th>".number_format($total_amount,2)."</th></tr>";
	$encodercontents	.=	$summarytables;
	$approvercontents	.=	$summarytables;
	$locationcontents	.=	$summarytables;
	if(!isset($company->id)){
		$jotablecontents		.=	"<th colspan='8'>Total</th>";
	}else{
		$jotablecontents		.=	"<th colspan='7'>Total</th>";
	}
	$jotablecontents		.=	"<th class='text-right'>".number_format($total_vat,2)."</th>";
	$jotablecontents		.=	"<th class='text-right'>".number_format($total_vatable,2)."</th>";
	$jotablecontents		.=	"<th class='text-right'>".number_format($total_etax,2)."</th>";
	$jotablecontents		.=	"<th class='text-right'>".number_format($total_amount,2)."</th>";
	$jotablecontents		.=	"<th>Served: ".$total_served." / ".($joctr+1)."</th>";
}
if(!empty($top_items)) {
	foreach($top_items as $itemctr=>$top_item) {
		$topitemcontents	.=	"<tr>";
		$topitemcontents	.=	"<td>".($itemctr+1)."</td>";
		$topitemcontents	.=	"<td>".$top_item['description']."</td>";
		$topitemcontents	.=	"<td class='text-right'>".$top_item['quantity']."</td>";
		$topitemcontents	.=	"</tr>";
	}
}
?>
<h2 class="text-center"><?php echo $company->name; ?></h2>
<h3 class="text-center">Job Order Report</h3>
<p>From <?php echo date("j F Y",strtotime($_GET['dstart'])); ?> to <?php echo date("j F Y",strtotime($_GET['dfinish'])); ?></p>
<div class="col-md-4">
	<table id="encoder_report" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>JO Count (Served/Unserved)</th>
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
				<th>JO Count (Served/Unserved)</th>
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
				<th>JO Count (Served/Unserved)</th>
				<th>Amount</th>
			</tr>
		</thead>
		<?php echo $locationcontents; ?>
	</table>
</div>
<div class="col-md-12 col-lg-12 col-sm-12" style='overflow-x: auto'>
<table id="jo_report" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<?php if(!isset($company->id)): ?>
			<th>Company</th>
			<?php endif; ?>
			<th>Vessel/Office</th>
			<th>Created On</th>
			<th>Requisition Transaction Code </th>
			<th>JO Transaction Code <br/>/<br/>Control Number</th>
			<th>Status</th>
			<th>Approved by</th>
			<th>Supplier</th>
			<th>VAT</th>
			<th>VATable Purchases</th>
			<th>Expanded Withholding Tax</th>
			<th>Amount</th>
			<th>Related documents TCode</th>
		</tr>
	</thead>
	<?php echo $jotablecontents; ?>
</table>
</div>
<hr>
<div class="col-md-12 col-lg-12 col-sm-12" style='overflow-x: auto'>
<h3 class="text-center">Top 30 Services</h3>
<table id="item_report" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th></th>
			<th>Description</th>
			<th>Quantity</th>
		</tr>
	</thead>
	<?php echo $topitemcontents; ?>
</table>
</div>