<h2>Stock In-Out Summary</h2>
<h4>Receiving and Issuance of items for the period of <?php echo date('F j,Y',strtotime($start_date))?> until <?php echo date('F j,Y',strtotime($end_date)) ?></h4>
	<table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">

		<?php 
	 		echo "<tr>";
	 			echo "<th>#</th>";
	 			echo "<th>Item Code</th>";
	 			echo "<th>Item Name</th>";
	 			echo "<th>Particular</th>";
	 			echo "<th>Unit</th>";
	 			echo "<th>Quantity Received</th>";
	 			echo "<th>Quantity Issued</th>";
	 			echo "<th>Current Balance On-stock</th>";
	 			//echo "<th>Difference</th>";
	 			echo "<th>PO TSCode No.</th>";
	 			//echo "<th>Last Receiving Date</th>";
	 			//echo "<th>Last Issuance Date</th>";
	 			echo "<th>RR Reference</th>";
	 			echo "<th>MSIS Reference</th>";
	 		echo "</tr>";

	 		$ctr=1;

		 		foreach($stock_in_out as $row){

		 			$quantity_received = 0;
		 			$quantity_issued = 0;
		 			$last_receiving_date ="";
		 			$last_issuance_date ="";
		 			$rr_ids = array();
		 			$msis_ids = array();
		 			$po_ids = array();
		 			
		 			$sql_deliveries = "SELECT *,inventory_deliveries.id AS ref_id FROM inventory_deliveries INNER JOIN inventory_delivery_details ON inventory_deliveries.id = inventory_delivery_details.delivery_id WHERE inventory_delivery_details.item_id=".$row->item_id." AND  inventory_deliveries.tdate BETWEEN '".$start_date."' AND '".$end_date."' ";
			 		$query_deliveries = $this->db->query($sql_deliveries);
			 		if($query_deliveries){
			 			$deliveries = $query_deliveries->result();
			 			foreach($deliveries as $delivery){
			 				array_push($rr_ids,$delivery->ref_id);
			 				array_push($po_ids,$delivery->po_no);
			 				$quantity_received = $quantity_received + $delivery->quantity;
			 				//$last_receiving_date =$delivery->tdate;
			 			}
			 		}

			 		$sql_issuances = "SELECT *, inventory_issuance.id AS ref_id FROM inventory_issuance INNER JOIN inventory_issuance_details ON inventory_issuance.id = inventory_issuance_details.issuance_id WHERE inventory_issuance_details.item_id=".$row->item_id." AND  inventory_issuance.issue_date BETWEEN '".$start_date."' AND '".$end_date."' ";
		 			$query_issuances = $this->db->query($sql_issuances);
		 			if($query_issuances){
		 				$issuances = $query_issuances->result();
			 			foreach($issuances as $issuance){
			 				array_push($msis_ids,$issuance->ref_id);
			 				$quantity_issued = $quantity_issued + $issuance->qty;
			 				//$last_issuance_date =$issuance->issue_date;
			 			}
		 			}

		 			$item = $this->Inventory_model->getItem($row->item_id);

		 			//$balance = $this->Inventory_model->getItemQty($row->item_id);
		 			//$quantity_stock = $balance[0]['tayud_qty'] + $balance[0]['mkt_qty'] + $balance[0]['nra_qty'];

		 			$quantity_stock = $this->Inventory_model->getItemQuantityAllCompany($row->item_id);
		 			$quantity_stock = ($quantity_stock[0]->sum_received - $quantity_stock[0]->sum_issued);

			 		echo "<tr>";
						echo "<td>".$ctr."</td>";
						echo "<td>".$item[0]['item_code']."</td>";
						echo "<td>".$item[0]['item_name']."</td>";
						echo "<td>".$item[0]['brand']." ".$item[0]['particular']."</td>";
						echo "<td>".$item[0]['unit']."</td>";
						echo "<td>".$quantity_received."</td>";
						echo "<td>".$quantity_issued."</td>";
						echo "<td>".$quantity_stock."</td>";
						/*$difference = $quantity_received - $quantity_issued;
						if($difference<0){
							echo "<td style='color:red'><b>".abs($difference)."</b></td>";
						}else{
							echo "<td><b>".$difference."</b></td>";
						}*/
						echo "<td>";
							foreach($po_ids as $po){
								echo $po."<br>";
							}
						echo "</td>";
						
						/*if($last_receiving_date!=''){
							echo "<td>".date('Y-m-d',strtotime($last_receiving_date))."</td>";
						}else{
							echo "<td>--</td>";
						}
						if($last_issuance_date!=''){
							echo "<td>".date('Y-m-d',strtotime($last_issuance_date))."</td>";
						}else{
							echo "<td>--</td>";
						}*/
						echo "<td>";
							foreach($rr_ids as $rr){
								echo'<a href="'.HTTP_PATH .'/inventory/view_transaction_history_details/delivery/'.$rr.'" class="btn btn-success btn-xs btn-block" data-toggle="modal" data-target="#modalDialog">'.$rr.'</a><br>';
							}
						echo "</td>";
						echo "<td>";
							foreach($msis_ids as $msis){
								echo'<a href="'.HTTP_PATH .'/inventory/view_transaction_history_details/issuance/'.$msis.'" class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modalDialog">'.$msis.'</a><br>';
							}
						echo "</td>";
					echo "</tr>";
					$ctr++;
					
				}
	    ?>
	</table>