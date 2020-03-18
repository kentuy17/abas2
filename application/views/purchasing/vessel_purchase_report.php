<?php
	echo "<h2>Purchase Orders for ".$vessel->name."</h2>";
	echo "From ". date('j F Y',strtotime($_GET['dstart'])) . " to " . date('j F Y',strtotime($_GET['dfinish']));
	echo "<br><br>";
?>
<table class="table table-bordered table-striped table-hover" data-url="" data-show-columns="true">
	<thead>
		<tr>
			<th>Purchase Order No.</th>
			<th>PO Created On</th>
			<th>Supplier</th>
			<th>Requested By</th>
			<th>Total Amount</th>
			<th>Status</th>
			<th>Manage</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$total_purchase_amount = 0;
			$total_served =0;
			$total_unserved =0;
			$total_po =0;
			$itemctr = 1;
			foreach($purchase_orders as $row){
				echo "<tr>";
					echo "<td>".$row->control_number."</td>";
					echo "<td>". date("j F Y", strtotime($row->added_on))."</td>";
					echo "<td>".$row->supplier_name."</td>";
					echo "<td>".$row->requisitioner."</td>";
					echo "<td>".number_format($row->amount,2,'.',',')."</td>";
					echo "<td>".$row->delivery_status."</td>";
					echo "<td><a class='btn btn-xs btn-primary exclude-pageload' onclick='javascript:showPO(".$itemctr.");''>View</a></td>";
				echo "</tr>";
				$total_purchase_amount  = $total_purchase_amount + $row->amount;
				if($row->delivery_status=='Served'){
					$total_served = $total_served+1;
				}else{
					$total_unserved = $total_unserved+1;
				}
				echo "<tr id='item".$itemctr."' class='hide'><td colspan='99'>";
					echo "<table class='table table-bordered table-striped table-hover'>";
						echo "<tr><th>Item</th><th>Unit</th><th>Price</th><th>Quantity</th><th>Total Price</th></tr>";
							$po_details = $row->po_details;
							foreach($po_details as $detail) {
								$item = $this->Inventory_model->getItem($detail['item_id']);
								echo	"<tr>";
									echo	"<td>".$item[0]['description'].",".$item[0]['particular']."</td>";
									echo	"<td>".$detail['unit']."</td>";
									echo	"<td>".number_format($detail['unit_price'],2)."</td>";
									echo	"<td>".$detail['quantity']."</td>";
									echo	"<td>".number_format($detail['unit_price']*$detail['quantity'],2)."</td>";
								echo	"</tr>";
							}
					echo	"</table>";
				echo	"</td></tr>";
				$itemctr++;
			}
			echo "<tr><td colspan='4' style='text-align:right'><b>Total Purchase Amount:</b></td><td>".number_format($total_purchase_amount,2,'.',',')."</td></tr>";
			echo "<tr><td colspan='4' style='text-align:right'><b>Total No. of PO:</b></td><td>".number_format(count($purchase_orders),0,'.',',')."</td></tr>";
			echo "<tr><td colspan='4' style='text-align:right'><b>Total Served PO:</b></td><td>".number_format($total_served,0,'.',',')."</td></tr>";
			echo "<tr><td colspan='4' style='text-align:right'><b>Total Unserved PO:</b></td><td>".number_format($total_unserved,0,'.',',')."</td></tr>";
		?>
	</tbody>
</table>
<script>
function showPO(itemid) {
	$("#item"+itemid).toggleClass('hide');
}
</script>