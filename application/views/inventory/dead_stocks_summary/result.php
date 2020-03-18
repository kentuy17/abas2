<h2>Dead Stocks Summary</h2>
<h4>All items received and issued from the period of <?php echo date('F j,Y',strtotime($start_date))?> until <?php echo date('F j,Y',strtotime($end_date)) ?></h4>
	<table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">

		<?php 
	 		echo "<tr>";
	 			echo "<th>#</th>";
	 			echo "<th>Item ID</th>";
	 			echo "<th>Item Name</th>";
	 			echo "<th>Particular</th>";
	 			echo "<th>Unit</th>";
	 			echo "<th>Stock Qty</th>";
	 			echo "<th>Last RR TSCode No.</th>";
	 			echo "<th>Last MSIS TSCode No.</th>";
	 			echo "<th>Last Unit Cost</th>";
	 			echo "<th>Last Receiving Date</th>";
	 			echo "<th>Last Issuance Date</th>";
	 			echo "<th>Classification</th>";
	 		echo "</tr>";

	 		$ctr=1;

		 		foreach($summary as $row){

		 			$last_receiving_date ="";
		 			$last_issuance_date ="";
		 			$last_rr ="";
		 			$last_msis ="";
		 			$last_price =0;
		 			$classification = "";

		 			//$balance = $this->Inventory_model->getItemQty($row->item_id);
		 			//$quantity_stock = $balance[0]['tayud_qty'] + $balance[0]['mkt_qty'] + $balance[0]['nra_qty'];

		 			$quantity_stock = $this->Inventory_model->getItemQuantityAllCompany($row->item_id);
		 			$quantity_stock = ($quantity_stock[0]->sum_received - $quantity_stock[0]->sum_issued);

		 			if($quantity_stock>0){
			 			$sql_deliveries = "SELECT *,inventory_deliveries.id AS ref_id FROM inventory_deliveries INNER JOIN inventory_delivery_details ON inventory_deliveries.id = inventory_delivery_details.delivery_id WHERE inventory_delivery_details.item_id=".$row->item_id." AND  inventory_deliveries.tdate BETWEEN '".$start_date."' AND '".$end_date."' ORDER BY ref_id DESC LIMIT 1";
				 		$query_deliveries = $this->db->query($sql_deliveries);
				 		if($query_deliveries){
				 			$delivery = $query_deliveries->row();
				 			if(isset($delivery->tdate)){
				 				$last_receiving_date =$delivery->tdate;
				 				$last_rr = $delivery->ref_id;
				 				$last_price = $delivery->unit_price;
				 			}else{
				 				$last_receiving_date = "";
				 			}
				 		}

				 		$sql_issuances = "SELECT *, inventory_issuance.id AS ref_id FROM inventory_issuance INNER JOIN inventory_issuance_details ON inventory_issuance.id = inventory_issuance_details.issuance_id WHERE inventory_issuance_details.item_id=".$row->item_id." AND  inventory_issuance.issue_date BETWEEN '".$start_date."' AND '".$end_date."' ORDER BY ref_id DESC LIMIT 1";
			 			$query_issuances = $this->db->query($sql_issuances);
			 			if($query_issuances){
			 				$issuance = $query_issuances->row();
			 				if(isset($issuance->issue_date)){
				 				$last_issuance_date =$issuance->issue_date;
				 				$last_msis = $issuance->ref_id;
				 				$last_price = $issuance->unit_price;
				 			}else{
				 				$last_issuance_date = "";
				 			}
			 			}

			 			$item = $this->Inventory_model->getItem($row->item_id);

				 		echo "<tr>";
							echo "<td>".$ctr."</td>";
							echo "<td>".$item[0]['item_code']."</td>";
							echo "<td>".$item[0]['item_name']."</td>";
							echo "<td>".$item[0]['brand']." ".$item[0]['particular']."</td>";
							echo "<td>".$item[0]['unit']."</td>";
							echo "<td>".$quantity_stock."</td>";
							echo "<td>".$last_rr."</td>";
							echo "<td>".$last_msis."</td>";
							echo "<td>".number_format($last_price,2,'.',',')."</td>";

							if($last_receiving_date!=''){
								echo "<td>".date('F j, Y',strtotime($last_receiving_date))."</td>";
							}else{
								echo "<td>--</td>";
							}
							if($last_issuance_date!=''){
								echo "<td>".date('F j, Y',strtotime($last_issuance_date))."</td>";
							}else{
								echo "<td>--</td>";
							}

		 					$date_now = new DateTime();
							$date_receive = new DateTime($last_receiving_date);
							$date_issued = new DateTime($last_issuance_date);
							$difference_received= $date_now->diff($date_receive);
							$difference_issued = $date_now->diff($date_issued);

							if($last_receiving_date!=''){
								if($difference_received->y >= 1){
									$classification="Dead Stock";
								}elseif($difference_received->m >= 6 && $difference_received->y==0){
									$classification="Slow Moving Items";
								}elseif($difference_received->m < 6 && $difference_received->y==0){
									$classification="Fast Moving Items";
								}
							}

							if($last_issuance_date!=''){
								if($difference_issued->y >= 1){
									$classification="Dead Stock";
								}elseif($difference_issued->m >= 6 && $difference_issued->y==0){
									$classification="Slow Moving Items";
								}elseif($difference_issued->m < 6 && $difference_issued->y==0){
									$classification="Fast Moving Items";
								}
							}

							if($last_receiving_date=='' && $last_issuance_date==''){
								$classification = "Dead Stock";
							}

							echo "<td>".$classification."</td>";

						echo "</tr>";
						$ctr++;
					}
				}	
	    ?>
	</table>