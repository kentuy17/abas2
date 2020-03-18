<h3 class="text-center">Project Orders for <?php echo $schedule_log['reference_number'] ?></h3>
<h2 class="text-center">As of <?php echo date('F j, Y');?></h2>

<table border="1" width="100%" class="table table-bordered table-striped table-hover">
	<thead>
		<td colspan="12"><h4><b>Bill of Materials</b></h4></td>
	</thead>
	<tr>
		<th>#</th>
		<th>Transaction Code No.</th>
		<th>Control No.</th>
		<th>Company</th>
		<th>Vessel</th>
		<th>Start Date of Repair</th>
		<th>Status</th>
		<th>Amount</th>
	</tr>
	<tbody>
		<?php 
			$ctr = 1;
			if($boms){
				$total_bom_amount = 0;
				foreach($boms as $bom){
					echo "<tr>";
						echo "<td>".$ctr."</td>";
						echo "<td>".$bom['id']."</td>";
						echo "<td>".$bom['control_number']."</td>";
						echo "<td>".$bom['company_name']."</td>";
						echo "<td>".$bom['vessel_name']."</td>";
						echo "<td>".date('Y-m-d',strtotime($bom['start_date_of_repair']))."</td>";
						echo "<td>".$bom['status']."</td>";
						$amount = $this->Asset_Management_model->getBOMAmount($bom['id']);
						echo "<td>".number_format($amount,2,'.',',')."</td>";
					echo "</tr>";
					$total_bom_amount = $total_bom_amount + $amount;
					$ctr++;
				}
				echo "<tr><td colspan='7' style='text-align:right'><b>Total BOM Amount</b></td><td>".number_format($total_bom_amount,2,'.',',')."</td></tr>";
			}else{
				echo "<tr><td colspan='8'><center>No record found</center></td></tr>";
			}
		?>
	</tbody>
</table>

<table border="1" width="100%" class="table table-bordered table-striped table-hover">
	<thead>
		<td colspan="12"><h4><b>Purchase Orders</b></h4></td>
	</thead>
	<tr>
		<th>#</th>
		<th>Transaction Code No.</th>
		<th>Control No.</th>
		<th>Company</th>
		<th>Vessel</th>
		<th>Department</th>
		<th>Supplier</th>
		<th>Created Date</th>
		<th>Delivery Date</th>
		<th>Status</th>
		<th>Amount</th>
		<th>Manage</th>
	</tr>
	<tbody>
		<?php
		$ctr=1;
		if($purchase_orders){
			$total_po_amount =0;
			foreach($purchase_orders as $po){
				$request = $this->Purchasing_model->getRequest($po->request_id);
				$supplier = $this->Abas->getSupplier($po->supplier_id);
				$requisition_no	= $request['control_number'];
				$company_name	= $request['company']->name;
				$vessel_name	= $request['vessel_name'];
				$department_name = $request['department_name'];
				echo "<tr>";
					echo "<td>".$ctr."</td>";
					echo "<td>".$po->id."</td>";
					echo "<td>".$po->control_number."</td>";
					echo "<td>".$company_name."</td>";
					echo "<td>".$vessel_name."</td>";
					echo "<td>".$department_name."</td>";
					echo "<td>".$supplier['name']."</td>";
					echo "<td>".date('F j, Y',strtotime($po->added_on))."</td>";
					echo "<td>".date('F j, Y',strtotime($po->deliver_on))."</td>";
					echo "<td>".$po->status."</td>";
					echo "<td>".number_format($po->amount,2,'.',',')."</td>";

					$total_po_amount = $total_po_amount + $po->amount;

					echo "<td><a class='btn btn-xs btn-primary exclude-pageload' onclick='javascript:showPODetails(".$ctr.");''><span class='glyphicon glyphicon-chevron-down'></span> View Details</a></td>";

						echo "<tr id='item".$ctr."' class='hide'><td colspan='99'>";
							echo "<table class='table table-bordered table-striped table-hover'>";
							echo "<tr>";
								echo "<th>#</th>";
								echo "<th>Item</th>";
								echo "<th>Quantity</th>";
								echo "<th>Unit</th>";
								echo "<th>Unit Price</th>";
								echo "<th>Total Price</th>";
							echo "</tr>";
							$po_details = $this->Purchasing_model->getPurchaseOrderDetails($po->id);
							if($po_details){
								$ctx=1;
								foreach($po_details as $detail) {
									$item = $this->Inventory_model->getItem($detail['item_id']);
									echo "<tr>";
									echo "<td>".$ctx."</td>";
									echo "<td>".$item[0]['description']."</td>";
									echo "<td>".$detail['quantity']."</td>";
									echo "<td>".$item[0]['unit']."</td>";
									echo "<td>".number_format($detail['unit_price'],2,'.',',')."</td>";
									echo "<td>".number_format(($detail['unit_price'] * $detail['quantity']),2,'.',',')."</td>";
									echo "</tr>";
									$ctx++;
								}
								echo "<tr><td colspan='6'>Remarks: ".$po->remark."</td></tr>";
							}else{
								echo "<tr><td colspan='6'><center>No record found</center></td></tr>";
							}
						    echo "</table>";
						echo "</tr>";
				echo "</tr>";
				$ctr++;
			}
			echo "<tr><td colspan='10' style='text-align:right'><b>Total PO Amount</b></td><td>".number_format($total_po_amount,2,'.',',')."</td></tr>";
		}else{
			echo "<tr><td colspan='12'><center>No record found</center></td></tr>";
		}
		?>
	</tbody>
</table>

<table border="1" width="100%" class="table table-bordered table-striped table-hover">
	<thead>
		<td colspan="12"><h4><b>Job Orders</b></h4></td>
	</thead>
	<tr>
		<th>#</th>
		<th>Transaction Code No.</th>
		<th>Control No.</th>
		<th>Company</th>
		<th>Vessel</th>
		<th>Department</th>
		<th>Supplier</th>
		<th>Created Date</th>
		<th>Delivery Date</th>
		<th>Status</th>
		<th>Amount</th>
		<th>Manage</th>
	</tr>
	<tbody>
		<?php
		$ctr=1;
		if($job_orders){
			$total_jo_amount=0;
			foreach($job_orders as $jo){
				$request = $this->Purchasing_model->getRequest($jo->request_id);
				$supplier = $this->Abas->getSupplier($jo->supplier_id);
				$requisition_no	= $request['control_number'];
				$company_name	= $request['company']->name;
				$vessel_name	= $request['vessel_name'];
				$department_name = $request['department_name'];
				echo "<tr>";
					echo "<td>".$ctr."</td>";
					echo "<td>".$jo->id."</td>";
					echo "<td>".$jo->control_number."</td>";
					echo "<td>".$company_name."</td>";
					echo "<td>".$vessel_name."</td>";
					echo "<td>".$department_name."</td>";
					echo "<td>".$supplier['name']."</td>";
					echo "<td>".date('F j, Y',strtotime($jo->added_on))."</td>";
					echo "<td>".date('F j, Y',strtotime($jo->deliver_on))."</td>";
					echo "<td>".$jo->status."</td>";
					echo "<td>".number_format($jo->amount,2,'.',',')."</td>";

					$total_jo_amount = $total_jo_amount + $jo->amount;

					echo "<td><a class='btn btn-xs btn-primary exclude-pageload' onclick='javascript:showJODetails(".$ctr.");''><span class='glyphicon glyphicon-chevron-down'></span> View Details</a></td>";

						echo "<tr id='itemx".$ctr."' class='hide'><td colspan='99'>";
							echo "<table class='table table-bordered table-striped table-hover'>";
							echo "<tr>";
								echo "<th>#</th>";
								echo "<th>Item</th>";
								echo "<th>Quantity</th>";
								echo "<th>Unit</th>";
								echo "<th>Unit Price</th>";
								echo "<th>Total Price</th>";
							echo "</tr>";
							$jo_details = $this->Purchasing_model->getJobOrderDetails($jo->id);
							if($jo_details){
								$ctx=1;
								foreach($jo_details as $detail) {
									$item = $this->Inventory_model->getItem($detail->item_id);
									echo "<tr>";
									echo "<td>".$ctx."</td>";
									echo "<td>".$item[0]['description']."</td>";
									echo "<td>".$detail->quantity."</td>";
									echo "<td>".$item[0]['unit']."</td>";
									echo "<td>".number_format($detail->unit_price,2,'.',',')."</td>";
									echo "<td>".number_format(($detail->unit_price * $detail->quantity),2,'.',',')."</td>";
									echo "</tr>";
									$ctx++;
								}
								echo "<tr><td colspan='6'>Remarks: ".$jo->remark."</td></tr>";
							}else{
								echo "<tr><td colspan='6'><center>No record found</center></td></tr>";
							}
						    echo "</table>";
						echo "</tr>";
				echo "</tr>";
				$ctr++;
			}
			echo "<tr><td colspan='10' style='text-align:right'><b>Total JO Amount</b></td><td>".number_format($total_jo_amount,2,'.',',')."</td></tr>";
		}else{
			echo "<tr><td colspan='12'><center>No record found</center></td></tr>";
		}
		?>
	</tbody>
</table>

<script type="text/javascript">
	function showPODetails(itemid) {
		$("#item"+itemid).toggleClass('hide');
	}
	function showJODetails(itemid) {
		$("#itemx"+itemid).toggleClass('hide');
	}
</script>