<?php
$display			=	"<tr><td colspan='7'><center>No Purchase Orders found!</center></td></tr>";
$totalspent			=	0;
if(!empty($purchase_orders)) {
	$display		=	"";
	$ctr =1;
	foreach($purchase_orders as $poctr=>$po) {
		$request_button	=	"<a class='btn btn-info btn-xs btn-block' href=".HTTP_PATH."purchasing/requisition/view/".$po['request_id'].">View Purchase Request</a>";
		$po_button		=	"<a class='btn btn-primary btn-block btn-xs' href=".HTTP_PATH."purchasing/purchase_order/view/".$po['po_id']." data-toggle='modal' data-target='#modalDialog'>View Purchase Order</a>";
		$display		.=	"<tr>";
		$display		.=	"<td>".$ctr."</td>";
		$display		.=	"<td>".date("F j, Y", strtotime($po['po_date']))."</td>";
		$supplier 		= $this->Abas->getSupplier($po['supplier_id']);
		$display		.=	"<td>".$supplier['name']."</td>";
		$display		.=	"<td>".$po['quantity']."</td>";
		$display		.=	"<td>".number_format($po['unit_price'],2)."</td>";
		$display		.=	"<td>".number_format($po['quantity']*$po['unit_price'],2)."</td>";
		$display		.=	"<td>".$request_button.$po_button."</td>";
		$display		.=	"</tr>";
		$totalspent		=	$totalspent+($po['quantity']*$po['unit_price']);
		$ctr++;
	}
}
?>
<h2>Purchase Report for <?php echo $item['description']; ?></h2>
<p>From PO made on <?php echo date("j F Y", strtotime($_GET['dstart'])); ?> to <?php echo date("j F Y", strtotime($_GET['dfinish'])); ?></p>
<p>Total Amount Purchased: <?php echo 'Php '.number_format($totalspent,2); ?> </p>
<table class="table table-bordered table-striped table-hover" data-search="true">
	<thead>
		<tr>
			<th data-align="center" data-sortable="true">#</th>
			<th data-align="center" data-sortable="true">PO Date</th>
			<th data-align="center" data-sortable="true">Supplier</th>
			<th data-align="center" data-sortable="true"><?php echo $item['unit']; ?></th>
			<th data-align="center" data-sortable="true">Unit Price</th>
			<th data-align="center" data-sortable="true">Total Price</th>
			<th data-align="center" data-sortable="true">Related Documents</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $display; ?>
	</tbody>
</table>