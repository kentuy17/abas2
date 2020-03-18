<?php
$display			=	"<tr><td colspan='9'><center>No record found!</center></td></tr>";
$totalspent			=	0;
if(!empty($purchase_orders)) {
	$display		=	"";
	$ctr =1;
	foreach($purchase_orders as $poctr=>$po) {
		$request_button	=	"<a class='btn btn-info btn-xs btn-block' target='_blank' href=".HTTP_PATH."purchasing/requisition/view/".$po['request_id'].">View Purchase Request</a>";
		if($category=='Service'){
			$po_button		=	"<a class='btn btn-primary btn-block btn-xs' href=".HTTP_PATH."purchasing/job_order/view/".$po['job_order_id']." data-toggle='modal' data-target='#modalDialog'>View Job Order</a>";
		}else{
			$po_button		=	"<a class='btn btn-primary btn-block btn-xs' href=".HTTP_PATH."purchasing/purchase_order/view/".$po['po_id']." data-toggle='modal' data-target='#modalDialog'>View Purchase Order</a>";
		}
		$display		.=	"<tr>";
		$display		.=	"<td>".$ctr."</td>";
		$display		.=	"<td>".date("j F Y", strtotime($po['tdate']))."</td>";
		$display		.=	"<td>".date("j F Y", strtotime($po['po_date']))."</td>";
		$supplier 		= $this->Abas->getSupplier($po['supplier_id']);
		$display		.=	"<td>".$supplier['name']."</td>";
		$display		.=	"<td>".$po['quantity']."</td>";
		$display		.=	"<td>".number_format($po['unit_price'],2)."</td>";
		$display		.=	"<td>".number_format($po['quantity']*$po['unit_price'],2)."</td>";
		$display		.=	"<td>".$po['po_status']."</td>";
		$display		.=	"<td>".$request_button.$po_button."</td>";
		$display		.=	"</tr>";
		$totalspent		=	$totalspent+($po['quantity']*$po['unit_price']);
		$ctr++;
	}
}
?>
<h2>Purchase History </h2>
<h4>Item Name: <?php echo $item['description']." - ".$item['unit']; ?></h4>
<p>For Purchases made from <?php echo date("j F Y", strtotime($_GET['dstart'])); ?> to <?php echo date("j F Y", strtotime($_GET['dfinish'])); ?></p>
<table class="table table-bordered table-striped table-hover" data-search="true">
	<thead>
		<tr>
			<th data-align="center" data-sortable="true">#</th>
			<th data-align="center" data-sortable="true">Request Date</th>
			<th data-align="center" data-sortable="true">Purchase Date</th>
			<th data-align="center" data-sortable="true">Supplier</th>
			<th data-align="center" data-sortable="true">Quantity</th>
			<th data-align="center" data-sortable="true">Unit Price</th>
			<th data-align="center" data-sortable="true">Total Amount</th>
			<th data-align="center" data-sortable="true">Status</th>
			<th data-align="center" data-sortable="true">Manage</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $display; ?>
		<tr><td colspan='6' style='text-align:right'><b>Total Amount: </b></td><td><?php echo number_format($totalspent,2,'.',','); ?></td></tr>
	</tbody>
</table>