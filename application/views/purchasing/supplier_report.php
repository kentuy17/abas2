<?php
$tablecontents			=	"<tr><th>No items found!</th></tr>";
$totalprice				=	array("supplier"=>0, "item"=>0);
if(!empty($purchased_items)) {
	$tablecontents		=	"";
	foreach($purchased_items as $itemctr=>$itemdata) {
		$totalprice['item']	=	0;
		$innertable			=	"<tr id='item".$itemctr."' class='hide'><td colspan='99'>";
		$innertable			.=	"<table class='table table-bordered table-striped table-hover'>";
		$innertable			.=	"<tr><th>PO Created On</th><th>Unit Price (".$itemdata['unit'].")</th><th>Quantity</th><th>Total Price</th><th>Manage</th></tr>";
		foreach($itemdata['prices'] as $prices) {
			$po_view				=	"<a href='".HTTP_PATH."purchasing/purchase_order/view/".$prices['po_id']."' class='btn btn-info btn-xs' data-target='#modalDialog' data-toggle='modal'>View PO</a>";
			$innertable				.=	"<tr>";
			$innertable				.=	"<td>".date("j F Y", strtotime($prices['tdate']))."</td>";
			$innertable				.=	"<td>P".number_format($prices['unit_price'],2)."</td>";
			$innertable				.=	"<td>".$prices['quantity']."</td>";
			$innertable				.=	"<td>P".number_format($prices['unit_price']*$prices['quantity'], 2)."</td>";
			$innertable				.=	"<td>".$po_view."</td>";
			$innertable				.=	"</tr>";
			$totalprice['item']		=	$totalprice['item']+($prices['unit_price']*$prices['quantity']);
			$totalprice['supplier']	=	$totalprice['supplier']+($prices['unit_price']*$prices['quantity']);
		}
		$innertable		.=	"</table>";
		$innertable		.=	"</td></tr>";
		$item_toggle	=	"<a class='btn btn-xs btn-primary exclude-pageload' onclick='javascript:showPrices(".$itemctr.");'>Prices</a>";
		$tablecontents	.=	"<tr>";
		$tablecontents	.=	"<td>".$itemdata['description'].", ".$itemdata['brand']." ".$itemdata['particular']."</td>";
		$tablecontents	.=	"<td>".count($itemdata['prices'])."</td>";
		$tablecontents	.=	"<td class='text-right'>P".number_format($totalprice['item'],2)."</td>";
		$tablecontents	.=	"<td>".$item_toggle."</td>";
		$tablecontents	.=	"</tr>";
		$tablecontents	.=	$innertable;
	}
	$tablecontents	.=	"<tr><th>Total </th><th></th><th class='text-right'>P".number_format($totalprice['supplier'],2)."</th><th></th></tr>";
}
?>
<h2>Items Purchased from <?php echo $supplier['name']; ?></h2>
<p>From <?php echo date("j F Y",strtotime($_GET['dstart'])); ?> to <?php echo date("j F Y",strtotime($_GET['dfinish'])); ?></p>
<div class="panel-group" id="purchases" role="tablist" aria-multiselectable="true">
	<table id="po_report" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>Item</th>
				<th>Times Purchased</th>
				<th>Amount Spent</th>
				<th>Manage</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $tablecontents; ?>
		</tbody>
	</table>
</div>
<script>
function showPrices(itemid) {
	$("#item"+itemid).toggleClass('hide');
}
</script>