<h2>Vessel Repairs Statistics</h2>
<br>
<h4>Vessel Name: <?php echo $vessel->name."<br>" ?></h4>
<h4>From period of <?php echo date('F j, Y',strtotime($date_from)). " to " . date('F j, Y',strtotime($date_to)); ?></h4>
<div style="overflow-x: auto">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="##summary-tab">Summary</a></li>
		<li><a data-toggle="tab" href="##jo-tab">Job Orders</a></li>
		<li><a data-toggle="tab" href="##po-tab">Purchase Orders</a></li>
		<li><a data-toggle="tab" href="##issuance-tab">Warehouse Issuances</a></li>
	</ul>

<div class="tab-content">
	<div id="jo-tab" class="tab-pane fade in">
		<table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
		    <thead>
		        <tr>
		        	<th>#</th>
		        	<th>Transaction Code No.</th>
		            <th>Job Order No.</th>
		            <th>Date</th>
		            <th>Company</th>
		            <th>Supplier</th>                                    
		            <th>Purpose</th>
		            <th>Amount</th>
		            <th>Manage</th>
		        </tr>
		    </thead>
		    <tbody>
		        <?php
		        $total_amount_jo = 0;
		        $drydocking_amount = 0;
		        $afloat_repair_amount = 0;
		        $category_amount = 0;
		        $specific_amount = 0;
		        $sandblasting_amount = 0;
		        $painting_amount = 0;
		        $cropout_amount = 0;
		        $welding_amount = 0;
		        $reconditioning_amount = 0;
		        $fabrication_amount = 0;
		        $electrical_amount = 0;
		        $machining_amount =0;
		        $cleaning_amount = 0;
		        $others_amount = 0;
		        $drydock_table = "";
		        $sandblasting_table = "";
		        $painting_table = "";
		        $hotworks_table = "";
		        $fabrication_table = "";
		        $reconditioning_table = "";
		        $electrical_table = "";
		        $machining_table = "";
		        $tankcleaning_table = "";
		        $others_table = "";
		        $ctr=1;
		        	foreach($jo_expenses as $row){
		        		$total_payable = 0;
		        		echo "<tr>";
		        			echo "<td>".$ctr."</td>";
		        			echo "<td>".$row->id."</td>";
		        			echo "<td>".$row->control_number."</td>";
		        			echo "<td>".date('F j, Y',strtotime($row->tdate))."</td>";
		        			echo "<td>".$row->company_name."</td>";
		        			echo "<td>".$row->supplier_name."</td>";
		        			echo "<td>".$row->remark." ".$row->purpose."</td>";

		        			$supplierdata		=	$this->Abas->getSupplier($row->supplier_id);
		        			$vat				=	0;
							$vatable_purchases	=	0;
							$gross_purchases	=	0;
							$vat				=	0;
							$etax				=	0;
							$totalcost			=	$row->amount;
							$vatable_purchases	=	$totalcost;
							$total_payable		=	$totalcost;
							if($supplierdata['issues_reciepts']==1) {
								$gross_purchases	=	$totalcost-$row->discount;
								if(strtolower($supplierdata['vat_computation'])=='vatable') {
									$vat				=	(($totalcost-$row->discount)-(($totalcost-$row->discount)/1.12));
									$vatable_purchases	=	($totalcost-$row->discount)-$vat;
								}
								$etax				=	($vatable_purchases*($row->extended_tax/100));
								$etax_percentage	=	0;
								if($row->extended_tax>0) {
									$etax_percentage=	$row->extended_tax;
									$total_payable	=	$gross_purchases-$row->extended_tax;
								}
								else {
									$total_payable	=	$totalcost-$row->discount;
								}
							}

							//$total_payable = $gross_purchases-$etax-$row->discount;

							$total_payable = $row->amount;

		        			echo "<td>".number_format($total_payable,2,'.',',')."</td>";
		        			echo "<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a></td>";
		        		echo "</tr>";
		        		$ctr++;
		        		
		        		$total_amount_jo = $total_amount_jo + $total_payable;

		        		$supplier_id = $row->supplier_id;
		        		$is_shipyard = strstr(strtolower($row->supplier_name),"shipyard")?"Yes":"No";
					
		        		if($supplier_id == 383 || $supplier_id == 263 || $supplier_id == 827 || $is_shipyard=="Yes"){
							$drydocking_amount_chart =$drydocking_amount_chart + $total_payable;
						}else{
							$afloat_repair_amount =$afloat_repair_amount + $total_payable;
						}

						$job_description = "";
						foreach($row->details as $detail){
							$sql = "SELECT * FROM inventory_request_details WHERE request_id=".$row->request_id." AND item_id=".$detail->item_id." AND unit_price is null";
							$query = $this->db->query($sql);
							if($query){
								$result = $query->result();
								$job_description = $result[0]->remark;

								$unit_price = $detail->unit_price;
								$quantity = $detail->quantity;
								$item_amount = $unit_price * $quantity;

								$is_sandblasting = $this->Abas->like_match("%sandblast%",$job_description);
								$is_painting = $this->Abas->like_match("%paint%",$job_description);
								$is_cropout = $this->Abas->like_match("%crop%",$job_description);
								$is_welding = $this->Abas->like_match("%weld%",$job_description);
								$is_install = $this->Abas->like_match("%install%",$job_description);
								$is_cut = $this->Abas->like_match("%cut%",$job_description);
								$is_reconditioning = $this->Abas->like_match("%recondition%",$job_description);
								$is_renew = $this->Abas->like_match("%renew%",$job_description);
								$is_fabrication = $this->Abas->like_match("%fabrica%",$job_description);
								$is_modification= $this->Abas->like_match("%modif%",$job_description);
								$is_calibration= $this->Abas->like_match("%calibrat%",$job_description);
								$is_replating= $this->Abas->like_match("%replat%",$job_description);
								$is_electrical = $this->Abas->like_match("%electrical%",$job_description);
								$is_machining = $this->Abas->like_match("%machining%",$job_description);
								$is_cleaning = $this->Abas->like_match("%cleaning%",$job_description);

								if($is_sandblasting!== false){
									$sandblasting_amount = $sandblasting_amount + $item_amount;
									$sandblasting_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";

								}elseif($is_painting!== false){
									$painting_amount = $painting_amount + $item_amount;
									$painting_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_cropout!== false){
									$cropout_amount = $cropout_amount + $item_amount;
									$hotworks_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_fabrication!== false){
									$fabrication_amount = $fabrication_amount + $item_amount;
									$fabrication_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_modification!== false){
									$fabrication_amount = $fabrication_amount + $item_amount;
									$fabrication_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_replating!== false){
									$welding_amount = $welding_amount + $item_amount;
									$hotworks_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_welding!== false){
									$welding_amount = $welding_amount + $item_amount;
									$hotworks_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_install!== false){
									$welding_amount = $welding_amount + $item_amount;
									$hotworks_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_cut!== false){
									$cropout_amount = $cropout_amount + $item_amount;
									$hotworks_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_renew!== false){
									$welding_amount = $welding_amount + $item_amount;
									$hotworks_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_electrical!== false){
									$electrical_amount = $electrical_amount + $item_amount; 
									$electrical_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_machining!== false){
									$machining_amount = $machining_amount + $item_amount;
									$machining_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_cleaning !== false){
									$cleaning_amount = $cleaning_amount + $item_amount;
									$tankcleaning_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_reconditioning!== false){
									$reconditioning_amount = $reconditioning_amount + $item_amount;
									$reconditioning_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}elseif($is_calibration!== false){
									$reconditioning_amount = $reconditioning_amount + $item_amount;
									$reconditioning_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
								}else{
									
									if($supplier_id != 383 && $supplier_id != 263 && $supplier_id != 827 && $is_shipyard=="No"){
										if($job_description==''){
											$parts_from_labor = $this->Inventory_model->getItem($detail->item_id);
											$job_description = $parts_from_labor[0]['description']. " (Parts and Labor)";
										}
										$others_table .= "<tr>
															<td>
																".$row->id."
															</td>
															<td>
																".$row->supplier_name."
															</td>
															<td>
																".$job_description."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";

										$others_amount = $others_amount + $item_amount;

									}else{
										if($detail->item_id<>9952 && $detail->item_id<>10526){
											$drydock_table .= "<tr>
													<td>
														".$row->id."
													</td>
													<td>
														".$row->supplier_name."
													</td>
													<td>
														".$job_description."
													</td>
													<td>
														".number_format($item_amount,2,".",",")."
													</td>
													<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/job_order/view/".$row->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
													</td>
												  </tr>";
												  $drydocking_amount = $drydocking_amount + $item_amount; 
										}
									}
								}

							}
						}

						
		        	}
		        	echo "<tr>";
	        			echo "<td colspan='7' style='text-align: right'><b>Total JO Amount</b></td>";
	        			echo "<td>".number_format($total_amount_jo,2,'.',',')."</td>";
	        		echo "</tr>";

	        		?>
	        </tbody>
	    </table>
	</div>

	 <div id="po-tab" class="tab-pane fade in">  
	   <table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
			    <thead>
			        <tr>
			        	<th>#</th>
			        	<th>Transaction Code No.</th>
			            <th>Purchase Order No.</th>
			            <th>Date</th>
			            <th>Company</th>
			            <th>Supplier</th>                                    
			            <th>Purpose</th>
			            <th>Amount</th>
			            <th>Manage</th>
			        </tr>
			    </thead>
			    <tbody>

					<?php

				        $total_amount_po = 0;
				        $po_paints_amount = 0;
				        $po_paints_table = "";
				        $po_hardware_amount = 0;
				        $po_hardware_table = "";
				        $po_fuel_amount = 0;
				        $po_fuel_table = "";
				        $po_steel_amount = 0;
				        $po_steel_table = "";
				        $po_spare_amount = 0;
				        $po_spare_table = "";
				        $po_equipment_amount = 0;
				        $po_equipment_table = "";
				        $po_marine_amount = 0;
				        $po_marine_table = "";
				        $po_tools_amount = 0;
				        $po_tools_table = "";
				        $po_electrical_amount = 0;
				        $po_electrical_table = "";
				        $po_ropes_amount = 0;
				        $po_ropes_table = "";
				        $po_others_amount = 0;
				        $po_others_table = "";

				        $ctr=1;
		        		foreach($po_expenses as $row2){

		        			echo "<tr>";
			        			echo "<td>".$ctr."</td>";
			        			echo "<td>".$row2->id."</td>";
			        			echo "<td>".$row2->control_number."</td>";
			        			echo "<td>".date('F j, Y',strtotime($row2->tdate))."</td>";
			        			echo "<td>".$row2->company_name."</td>";
			        			echo "<td>".$row2->supplier_name."</td>";
			        			echo "<td>".$row2->remark."</td>";


		    				$supplierdata		=	$this->Abas->getSupplier($row2->supplier_id);
		        			$vat				=	0;
							$vatable_purchases	=	0;
							$gross_purchases	=	0;
							$vat				=	0;
							$etax				=	0;
							$totalcost			=	$row2->amount;
							$vatable_purchases	=	$totalcost;
							$total_payable		=	$totalcost;
							if($supplierdata['issues_reciepts']==1) {
								$gross_purchases	=	$totalcost-$row2->discount;
								if(strtolower($supplierdata['vat_computation'])=='vatable') {
									$vat				=	(($totalcost-$row2->discount)-(($totalcost-$row2->discount)/1.12));
									$vatable_purchases	=	($totalcost-$row2->discount)-$vat;
								}
								$etax				=	($vatable_purchases*($row2->extended_tax/100));
								$etax_percentage	=	0;
								if($row->extended_tax>0) {
									$etax_percentage=	$row2->extended_tax;
									$total_payable	=	$gross_purchases-$row2->extended_tax;
								}
								else {
									$total_payable	=	$totalcost-$row2->discount;
								}
							}

								//$total_payable = $gross_purchases-$etax-$row2->discount;

								$total_payable = $row2->amount;

								echo "<td>".number_format($total_payable,2,'.',',')."</td>";
		        				echo "<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/purchase_order/view/".$row2->id."' data-toggle='modal' data-target='#modalDialog'>View</a></td>";
		        				echo "</tr>";

		        				$total_amount_po  =$total_amount_po +$total_payable;

		        				$ctr++;

		        			foreach($row2->details as $detail2){
								
									$po_item = $this->Inventory_model->getItem($detail2['item_id']);
									$purchase_description = $po_item[0]['description'];
									$unit_price = $detail2['unit_price'];
									$quantity = $detail2['quantity'];
									$item_amount = $unit_price * $quantity;

									$po_row = "<tr>
													<td>
														".$row2->id."
													</td>
													<td>
														".$row2->supplier_name."
													</td>
													<td>
														".$purchase_description."
													</td>
													<td>
														".number_format($item_amount,2,".",",")."
													</td>
													<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."purchasing/purchase_order/view/".$row2->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
													</td>
												  </tr>";

			        				if($po_item[0]['category']==1){//Paints and Thinner
			        					$po_paints_amount = $po_paints_amount + $item_amount;
		        						$po_paints_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==2){//Hardware and Consumable
		        						$po_hardware_amount = $po_hardware_amount + $item_amount;
		        						$po_hardware_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==3){//Fuel, Oil,Lubricants,and Chemicals
		        						$po_fuel_amount = $po_fuel_amount + $item_amount;
		        						$po_fuel_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==4){//steel materials and pipes
		        						$po_steel_amount = $po_steel_amount + $item_amount;
		        						$po_steel_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==5){//Mechanical and Spare Parts
		        						$po_spare_amount = $po_spare_amount + $item_amount;
		        						$po_spare_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==6){//Equipment and Machineries
		        						$po_equipment_amount = $po_equipment_amount + $item_amount;
		        						$po_equipment_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==7){//Marine Materials and Supplies
		        						$po_marine_amount = $po_marine_amount + $item_amount;
		        						$po_marine_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==8){//Tools and Machine Shop Supplies
		        						$po_tools_amount = $po_tools_amount + $item_amount;
		        						$po_tools_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==9){//Electrical and Electronic Parts
		        						$po_electrical_amount = $po_electrical_amount + $item_amount;
		        						$po_electrical_table .= $po_row;
		        					}
		        					elseif($po_item[0]['category']==79){//Ropes, Cables, and Fittings
		        						$po_ropes_amount = $po_ropes_amount + $item_amount;
		        						$po_ropes_table .= $po_row;
		        					}
		        					else{//others
		        						$po_others_amount = $po_others_amount + $item_amount;
		        						$po_others_table .= $po_row;
		        					}
		        				

							}
							
		        		}

		        			echo "<tr>";
	        			echo "<td colspan='7' style='text-align: right'><b>Total PO Amount</b></td>";
	        			echo "<td>".number_format($total_amount_po,2,'.',',')."</td>";
	        		echo "</tr>";

					?>
  				</tbody>
    		</table>
    </div>

    <div id="issuance-tab" class="tab-pane fade in"> 
		<table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
			    <thead>
			        <tr>
			        	<th>#</th>
			        	<th>Transaction Code No.</th>
			            <th>Issuance No.</th>
			            <th>Date</th>
			            <th>Company</th>
			            <th>Issued To</th>                                    
			            <th>Purpose</th>
			            <th>Amount</th>
			            <th>Manage</th>
			        </tr>
			    </thead>
			    <tbody>
					<?php
			        	
			        	/*$steel_amount =0;
			        	$spareparts_amount =0;
			        	$otherxx_amount =0;
			        	$steel_table ="";
			        	$spareparts_table = "";
			        	$otherxx_table = "";*/

				        $total_amount_issuance = 0;
				        $issuance_paints_amount = 0;
				        $issuance_paints_table = "";
				        $issuance_hardware_amount = 0;
				        $issuance_hardware_table = "";
				        $issuance_fuel_amount = 0;
				        $issuance_fuel_table = "";
				        $issuance_steel_amount = 0;
				        $issuance_steel_table = "";
				        $issuance_spare_amount = 0;
				        $issuance_spare_table = "";
				        $issuance_equipment_amount = 0;
				        $issuance_equipment_table = "";
				        $issuance_marine_amount = 0;
				        $issuance_marine_table = "";
				        $issuance_tools_amount = 0;
				        $issuance_tools_table = "";
				        $issuance_electrical_amount = 0;
				        $issuance_electrical_table = "";
				        $issuance_ropes_amount = 0;
				        $issuance_ropes_table = "";
				        $issuance_others_amount = 0;
				        $issuance_others_table = "";

						$ctr=1;

	        			$array_po = array();
	        			foreach($po_expenses as $row4){
	        				foreach($row4->details as $detail4){
	        					array_push($array_po,$detail4['item_id']);
	        				}
	        			}

		        		foreach($issuance_expenses as $row3){
		        			echo "<tr>";
			        			echo "<td>".$ctr."</td>";
			        			echo "<td>".$row3->id."</td>";
			        			echo "<td>".$row3->control_number."</td>";
			        			echo "<td>".date('F j, Y',strtotime($row3->issue_date))."</td>";
			        			echo "<td>".$row3->company_name."</td>";
			        			echo "<td>".$row3->issued_to."</td>";
			        			echo "<td>".$row3->remark."</td>";

			        			$issuance_amount = 0;
			        			$flag_already_in_po = FALSE;
			        			foreach($row3->details as $detail3){

			        				$issuance_item = $this->Inventory_model->getItem($detail3['item_id']);
									$issuance_description = $issuance_item[0]['description'];

			        				$item_amount = $detail3['unit_price'] * $detail3['qty'];
						        	$issuance_amount = $issuance_amount + $item_amount;

				        			$flag_already_in_po = $this->Abas->vlookup($detail3['item_id'],$array_po);
				        			
		        					if($flag_already_in_po==FALSE){

		        						$issuance_row = "<tr>
															<td>
																".$row3->id."
															</td>
															<td>
																".$issuance_description."
															</td>
															<td>
																".date('Y-m-d',strtotime($row3->issue_date))."
															</td>
															<td>
																".$row3->issued_to."
															</td>
															<td>
																".$row3->remark."
															</td>
															<td>
																".number_format($item_amount,2,".",",")."
															</td>
															<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."inventory/view_transaction_history_details/issuance/".$row3->id."' data-toggle='modal' data-target='#modalDialog'>View</a>
															</td>
														  </tr>";
		        						
				        				
			        					if($issuance_item[0]['category']==1){//Paints and Thinner
				        					$issuance_paints_amount = $issuance_paints_amount + $item_amount;
			        						$issuance_paints_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==2){//Hardware and Consumable
			        						$issuance_hardware_amount = $issuance_hardware_amount + $item_amount;
			        						$issuance_hardware_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==3){//Fuel, Oil,Lubricants,and Chemicals
			        						$issuance_fuel_amount = $issuance_fuel_amount + $item_amount;
			        						$issuance_fuel_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==4){//steel materials and pipes
			        						$issuance_steel_amount = $issuance_steel_amount + $item_amount;
			        						$issuance_steel_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==5){//Mechanical and Spare Parts
			        						$issuance_spare_amount = $issuance_spare_amount + $item_amount;
			        						$issuance_spare_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==6){//Equipment and Machineries
			        						$issuance_equipment_amount = $issuance_equipment_amount + $item_amount;
			        						$issuance_equipment_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==7){//Marine Materials and Supplies
			        						$issuance_marine_amount = $issuance_marine_amount + $item_amount;
			        						$issuance_marine_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==8){//Tools and Machine Shop Supplies
			        						$issuance_tools_amount = $issuance_tools_amount + $item_amount;
			        						$issuance_tools_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==9){//Electrical and Electronic Parts
			        						$issuance_electrical_amount = $issuance_electrical_amount + $item_amount;
			        						$issuance_electrical_table .= $issuance_row;
			        					}
			        					elseif($issuance_item[0]['category']==79){//Ropes, Cables, and Fittings
			        						$issuance_ropes_amount = $issuance_ropes_amount + $item_amount;
			        						$issuance_ropes_table .= $issuance_row;
			        					}
			        					else{//others
			        						$issuance_others_amount = $issuance_others_amount + $item_amount;
			        						$issuance_others_table .= $issuance_row;
			        					}
				        				
				        			}
				        				
			        			}

			        			$total_amount_issuance = $total_amount_issuance + $issuance_amount;

				        		echo "<td>".number_format($issuance_amount,2,'.',',')."</td>";
			        			echo "<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."inventory/view_transaction_history_details/issuance/".$row3->id."' data-toggle='modal' data-target='#modalDialog'>View</a></td>";
			        		echo "</tr>";
			        		$ctr++;
			        	}

				        	echo "<tr>";
		        			echo "<td colspan='7' style='text-align: right'><b>Total Issuance Amount</b></td>";
		        			echo "<td>".number_format($total_amount_issuance,2,'.',',')."</td>";
		        		echo "</tr>";
			        	?>
			        </tbody>
		</table>
	</div>

	<div id="summary-tab" class="tab-pane fade in active">  
		<br><br>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Job Orders</h3>
			</div>
	
		<div class="panel-body">

			<div id="pie_chart" style="position: center; overflow-x: auto; width: 850px; height: 400px;"><br></div>
		
		    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
		    		
			      
			        <tr>
			        	<td style="font-size:14px">&nbsp<a class='exclude-pageload' onclick='javascript:showDryDock();'> <b><u>Dry-Docking (Shipyard)</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($drydocking_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='drydocking' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $drydock_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td colspan="2" style="font-size:14px"><b>Afloat Repairs (Specific Jobs)</b></td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showSandBlasting();'> <b><u>Sand-Blasting</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($sandblasting_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='sandblasting' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $sandblasting_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPainting();'> <b><u>Painting</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($painting_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='painting' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $painting_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showHotWorks();'> <b><u>Hotworks</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($cropout_amount+$welding_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='hotworks' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $hotworks_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showFabrication();'> <b><u>Fabrication</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($fabrication_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='fabrication' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $fabrication_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showReconditioning();'> <b><u>Reconditioning (Mechanical)</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($reconditioning_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='reconditioning' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $reconditioning_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showElectrical();'> <b><u>Electrical</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($electrical_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='electrical' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $electrical_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showMachining();'> <b><u>Machining</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($machining_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='machining' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $machining_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showTankCleaning();'> <b><u>Tank Cleaning</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($cleaning_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='tankcleaning' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $tankcleaning_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showOthers();'> <b><u>Others</u></b> </a></td>

			        	<td style="text-align: center"><?php echo number_format($others_amount,2,'.',',');?>
			        	</td>

			        </tr>
			        <tr id='others' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>JO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Job Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $others_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td style="text-align: right"><b>Sub-total</b></td>
			        	<td style="text-align: center"><b>
			        		<?php $total_job_amount = ($sandblasting_amount + $painting_amount + $cropout_amount + $reconditioning_amount + $electrical_amount + $cleaning_amount + $welding_amount + $fabrication_amount + $machining_amount + $others_amount);
								echo number_format($total_job_amount,2,'.',',');
			        		?></b></td>
			        </tr>
			        <tr>
			        	<td style="text-align: right"><b>Total JO Amount</b></td>
			        	<td style="text-align: center"><b><?php echo number_format($total_job_amount+$drydocking_amount,2,'.',',');?></b>
			        	</td>
			        </tr>
		   </table>
		</div>
	</div>

	<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Purchase Orders</h3>
			</div>
	
		<div class="panel-body">

			<div id="bar_chart" style="position: center; overflow-x: auto; width: 850px; height: 400px;"><br></div>

		   <table class="table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
			         <tr>
			        	<td colspan="2" style="font-size:14px"><b>Materials & Supplies Purchases</b></td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOPaints();'> <b><u>Paints and Thinners</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_paints_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_paints' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_paints_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOHardware();'> <b><u>Hardware and Consumables</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_hardware_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_hardware' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_hardware_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOFuel();'> <b><u>Fuel, Oil, Lubricants, and Chemicals</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_fuel_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_fuel' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_fuel_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOSteel();'> <b><u>Steel Materials and Pipes</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_steel_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_steel' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_steel_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOSpare();'> <b><u>Mechanical and Spare Parts</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_spare_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_spare' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_spare_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOEquipment();'> <b><u>Equipment and Machineries</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_equipment_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_equipment' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_equipment_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOMarine();'> <b><u>Marine Materials and Supplies</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_marine_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_marine' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_marine_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOTools();'> <b><u>Tools and Machine Shop Supplies</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_tools_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_tools' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_tools_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOElectrical();'> <b><u>Electrical and Electronic Parts</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_electrical_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_electrical' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_electrical_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPORopes();'> <b><u>Ropes, Cables, and Fittings</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_ropes_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_ropes' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_ropes_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPOOthers();'> <b><u>Others</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($po_others_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='po_others' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>PO Transaction Code</th>
			        				<th>Supplier</th>
			        				<th>Item Description</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $po_others_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td style="text-align: right"><b>Total PO Amount</b></td>
			        	<td style="text-align: center"><b>
			        		<?php $total_po_amount = ($po_paints_amount + $po_hardware_amount + $po_fuel_amount + $po_steel_amount + $po_spare_amount + $po_equipment_amount + $po_marine_amount + $po_tools_amount + $po_electrical_amount + $po_ropes_amount + $po_others_amount);
								echo number_format($total_po_amount,2,'.',',');
			        		?></b></td>
			        </tr>
		    </table>
			</div>

		</div>
		 

	    <div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Warehouse Inventory</h3>
			</div>
			<div class="panel-body">

				<div id="bar_chart2" style="position: center; overflow-x: auto; width: 850px; height: 400px;"><br></div>
				
			    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
			        <tr>
			        	<td colspan="2" style="font-size:14px"><b>Materials & Supplies Issuances</b></td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuancePaints();'> <b><u>Paints and Thinners</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_paints_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_paints' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_paints_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceHardware();'> <b><u>Hardware and Consumables</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_hardware_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_hardware' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_hardware_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceFuel();'> <b><u>Fuel, Oil, Lubricants, and Chemicals</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_fuel_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_fuel' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_fuel_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceSteel();'> <b><u>Steel Materials and Pipes</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_steel_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_steel' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_steel_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceSpare();'> <b><u>Mechanical and Spare Parts</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_spare_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_spare' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_spare_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceEquipment();'> <b><u>Equipment and Machineries</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_equipment_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_equipment' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_equipment_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceMarine();'> <b><u>Marine Materials and Supplies</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_marine_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_marine' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_marine_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceTools();'> <b><u>Tools and Machine Shop Supplies</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_tools_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_tools' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_tools_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceElectrical();'> <b><u>Electrical and Electronic Parts</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_electrical_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_electrical' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_electrical_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceRopes();'> <b><u>Ropes, Cables, and Fittings</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_ropes_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_ropes' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>IssuanceTransaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_ropes_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showIssuanceOthers();'> <b><u>Others</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($issuance_others_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='issuance_others' class='hide'>
			        	<td colspan='99'>
			        		<table class='table table-bordered table-striped table-hover'>
			        			<thead>
			        				<th>Issuance Transaction Code</th>
			        				<th>Item Description</th>
			        				<th>Issue Date</th>
			        				<th>Issued To</th>
			        				<th>Purpose</th>
			        				<th>Amount</th>
			        				<th>Manage</th>
			        			</thead>
			        			<tbody>
			        				<?php echo $issuance_others_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td style="text-align: right"><b>Total Issuance Amount</b></td>
			        	<td style="text-align: center"><b>
			        		<?php $total_issuance_amount = ($issuance_paints_amount + $issuance_hardware_amount + $issuance_fuel_amount + $issuance_steel_amount + $issuance_spare_amount + $issuance_equipment_amount + $issuance_marine_amount + $issuance_tools_amount + $issuance_electrical_amount + $issuance_ropes_amount + $issuance_others_amount);
								echo number_format($total_issuance_amount,2,'.',',');
			        		?></b></td>
			        </tr>
		       </table>
			</div>
		</div>
    	<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
		    <div class="tile-stats">
				<div class="count"><?php echo number_format($bom['grand_total_amount'],2,'.',',');?></div>
				<h3>Total Estimate</h3>
				<p>Bill of Materials</p>
			</div>
		</div>
		<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
		    <div class="tile-stats">
				<div class="count"><?php 
	        		$totalexpenses = $drydocking_amount + $total_job_amount+$total_po_amount+$total_issuance_amount;
	        		echo number_format($totalexpenses,2,'.',',');?></div>
				<h3>Total Expenses</h3>
				<p>Job Orders + Purchase Orders + Issuances</p>
			</div>
		</div>
		<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
		    <div class="tile-stats">
				<div class="count">
					<?php 
						$variance = $bom['grand_total_amount'] - $totalexpenses;
						if($variance<0){
							$totalvariance = "(".number_format(abs($variance),2,'.',',').")";
							echo '<span style="color:red">'.$totalvariance.'</span>';
						}else{
							$totalvariance = number_format($variance,2,'.',',');
							echo $totalvariance;
						}
        			?>
				</div>
				<h3>Variance</h3>
				<p>Total Estimate - Total Expenses</p>
			</div>
		</div>
	</div>    	 
</div>	
</div>

<script type="text/javascript">


	function showDryDock() {
		$("#drydocking").toggleClass('hide');
	}

	function showSandBlasting() {
		$("#sandblasting").toggleClass('hide');
	}

	function showPainting() {
		$("#painting").toggleClass('hide');
	}

	function showHotWorks() {
		$("#hotworks").toggleClass('hide');
	}

	function showFabrication() {
		$("#fabrication").toggleClass('hide');
	}

	function showReconditioning() {
		$("#reconditioning").toggleClass('hide');
	}

	function showElectrical() {
		$("#electrical").toggleClass('hide');
	}

	function showMachining() {
		$("#machining").toggleClass('hide');
	}

	function showTankCleaning() {
		$("#tankcleaning").toggleClass('hide');
	}

	function showOthers() {
		$("#others").toggleClass('hide');
	}

	function showFuel() {
		$("#fuel").toggleClass('hide');
	}

	function showOil() {
		$("#oil").toggleClass('hide');
	}

	function showLPG() {
		$("#lpg").toggleClass('hide');
	}

	function showPaints() {
		$("#paints").toggleClass('hide');
	}

	function showWater() {
		$("#water").toggleClass('hide');
	}

	function showOthersX() {
		$("#otherX").toggleClass('hide');
	}

	function showPOPaints() {
		$("#po_paints").toggleClass('hide');
	}

	function showPOHardware() {
		$("#po_hardware").toggleClass('hide');
	}

	function showPOFuel() {
		$("#po_fuel").toggleClass('hide');
	}

	function showPOSteel() {
		$("#po_steel").toggleClass('hide');
	}

	function showPOSpare() {
		$("#po_spare").toggleClass('hide');
	}

	function showPOEquipment() {
		$("#po_equipment").toggleClass('hide');
	}

	function showPOMarine() {
		$("#po_marine").toggleClass('hide');
	}

	function showPOTools() {
		$("#po_tools").toggleClass('hide');
	}

	function showPOElectrical() {
		$("#po_electrical").toggleClass('hide');
	}

	function showPORopes() {
		$("#po_ropes").toggleClass('hide');
	}

	function showPOOthers() {
		$("#po_others").toggleClass('hide');
	}

	function showIssuancePaints() {
		$("#issuance_paints").toggleClass('hide');
	}

	function showIssuanceHardware() {
		$("#issuance_hardware").toggleClass('hide');
	}

	function showIssuanceFuel() {
		$("#issuance_fuel").toggleClass('hide');
	}

	function showIssuanceSteel() {
		$("#issuance_steel").toggleClass('hide');
	}

	function showIssuanceSpare() {
		$("#issuance_spare").toggleClass('hide');
	}

	function showIssuanceEquipment() {
		$("#issuance_equipment").toggleClass('hide');
	}

	function showIssuanceMarine() {
		$("#issuance_marine").toggleClass('hide');
	}

	function showIssuanceTools() {
		$("#issuance_tools").toggleClass('hide');
	}

	function showIssuanceElectrical() {
		$("#issuance_electrical").toggleClass('hide');
	}

	function showIssuanceRopes() {
		$("#issuance_ropes").toggleClass('hide');
	}

	function showIssuanceOthers() {
		$("#issuance_others").toggleClass('hide');
	}

	function showSteel() {
		$("#steel").toggleClass('hide');
	}

	function showSpareParts() {
		$("#spareparts").toggleClass('hide');
	}

	function showOthersXX() {
		$("#otherXX").toggleClass('hide');
	}

	// based on prepared DOM, initialize echarts instance
    var pieChart = echarts.init(document.getElementById('pie_chart'));
    var barChart = echarts.init(document.getElementById('bar_chart'));
    var barChart2 = echarts.init(document.getElementById('bar_chart2'));


    // specify chart configuration item and data
    var option_pie = {
        title: {
            text: '',
            x:'center'
        },
        tooltip: {
        	 trigger: 'item',
   			 formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            type: 'scroll',
	        orient: 'vertical',
	        right: 10,
	        top: 20,
	        bottom: 20,
	        data:["Dry-docking","Sand-blasting","Painting","Hot-works","Fabrication","Reconditioning","Electrical","Machining","Tank Cleaning","Others"]
        },
        series: [{
        	name: "Job Orders",
            type: 'pie',
            data:[
            {value:<?php echo $drydocking_amount;?>, name:'Dry-docking'},
            {value:<?php echo $sandblasting_amount;?>, name:'Sand-blasting'},
            {value:<?php echo $painting_amount;?>, name:'Painting'},
            {value:<?php echo $cropout_amount + $welding_amount;?>, name:'Hot-works'},
            {value:<?php echo $fabrication_amount;?>, name:'Fabrication'},
            {value:<?php echo $reconditioning_amount;?>, name:'Reconditioning'},
            {value:<?php echo $electrical_amount;?>, name:'Electrical'},
            {value:<?php echo $machining_amount;?>, name:'Machining'},
            {value:<?php echo $cleaning_amount;?>, name:'Tank Cleaning'},
            {value:<?php echo $others_amount;?>, name:'Others'}]
        }]
    };

    var option_bar = {
        title: {
            text: '',
            x:'center'
        },
        tooltip: {
        	trigger: 'axis',
	        axisPointer : { 
	            type : 'shadow'
	        }
        },
        xAxis: {
	        type: 'category',
	        data: ['Paints and Thinners', 'Hardware and Consumables', 'Fuel, Oil, Lubricants, and Chemicals', 'Steel Materials and Pipes', 'Mechanical and Spare Parts', 'Equipment and Machineries', 'Marine Materials and Supplies', 'Tools and Machine Shop Supplies', 'Electrical and Electronic Parts', 'Ropes, Cables, and Fittings', 'Others'],
	        axisLabel: {
                interval: 2,
            }
	    },
	    yAxis: {
	        type: 'value'
	    },
	    series: [{
	        data: [<?php echo $po_paints_amount?>, <?php echo $po_hardware_amount?>, <?php echo $po_fuel_amount ?>, <?php echo $po_steel_amount?>, <?php echo $po_spare_amount?>, <?php echo $po_equipment_amount?>,<?php echo $po_marine_amount?>,<?php echo $po_tools_amount?>,<?php echo $po_electrical_amount?>,<?php echo $po_ropes_amount?>,<?php echo $po_others_amount?>],
	        type: 'bar'
	    }]
    };

     var option_bar2 = {
        title: {
            text: '',
            x:'center'
        },
        tooltip: {
        	trigger: 'axis',
	        axisPointer : { 
	            type : 'shadow'
	        }
        },
        xAxis: {
	        type: 'category',
	        data: ['Paints and Thinners', 'Hardware and Consumables', 'Fuel, Oil, Lubricants, and Chemicals', 'Steel Materials and Pipes', 'Mechanical and Spare Parts', 'Equipment and Machineries', 'Marine Materials and Supplies', 'Tools and Machine Shop Supplies', 'Electrical and Electronic Parts', 'Ropes, Cables, and Fittings', 'Others'],
	        axisLabel: {
                interval: 2,
            }
	    },
	    yAxis: {
	        type: 'value'
	    },
	    series: [{
	        data: [<?php echo $issuance_paints_amount?>, <?php echo $issuance_hardware_amount?>, <?php echo $issuance_fuel_amount ?>, <?php echo $issuance_steel_amount?>, <?php echo $issuance_spare_amount?>, <?php echo $issuance_equipment_amount?>,<?php echo $issuance_marine_amount?>,<?php echo $issuance_tools_amount?>,<?php echo $issuance_electrical_amount?>,<?php echo $issuance_ropes_amount?>,<?php echo $issuance_others_amount?>],
	        type: 'bar'
	    }]
    };

    // use configuration item and data specified to show chart
    pieChart.setOption(option_pie);
    barChart.setOption(option_bar);
    barChart2.setOption(option_bar2);

</script>