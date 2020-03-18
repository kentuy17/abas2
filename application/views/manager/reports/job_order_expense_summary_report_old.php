<h2>Vessel Repairs Expense Statistics</h2>
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
							$drydocking_amount =$drydocking_amount + $total_payable;
							
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

								$is_sandblasting = $this->Manager_model->like_match("%sandblast%",$job_description);
								$is_painting = $this->Manager_model->like_match("%paint%",$job_description);
								$is_cropout = $this->Manager_model->like_match("%crop%",$job_description);
								$is_welding = $this->Manager_model->like_match("%weld%",$job_description);
								$is_install = $this->Manager_model->like_match("%install%",$job_description);
								$is_cut = $this->Manager_model->like_match("%cut%",$job_description);
								$is_reconditioning = $this->Manager_model->like_match("%recondition%",$job_description);
								$is_renew = $this->Manager_model->like_match("%renew%",$job_description);
								$is_fabrication = $this->Manager_model->like_match("%fabrica%",$job_description);
								$is_electrical = $this->Manager_model->like_match("%electrical%",$job_description);
								$is_machining = $this->Manager_model->like_match("%machining%",$job_description);
								$is_cleaning = $this->Manager_model->like_match("%cleaning%",$job_description);

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
								}else{
									$others_amount = $others_amount + $item_amount;
									if($supplier_id != 383 && $supplier_id != 263 && $supplier_id != 827 && $is_shipyard=="No"){
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
									}else{
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
			        	$fuel_amount = 0;
				        $oil_amount = 0;
				        $lpg_amount =0;
				        $oxygen_amount =0;
				        $lubricant_amount =0;
				        $water_amount = 0;
				        $paint_amount = 0;
				        $jotun_amount=0;
				        $otherx_amount =0;
				        $fuel_table = "";
				        $oil_table = "";
				        $lpg_table = "";
				        $paints_table = "";
				        $water_table = "";
				        $otherx_table = "";
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
								$sql = "SELECT * FROM inventory_items WHERE id=".$detail2['item_id'];
								$query = $this->db->query($sql);
								if($query){
									$result = $query->result();
									$purchase_description = $result[0]->description . " " .$result[0]->particular;
									$unit_price = $detail2['unit_price'];
									$quantity = $detail2['quantity'];
									$item_amount = $unit_price * $quantity;

									$is_fuel = $this->Manager_model->like_match("%fuel (ado)%",$purchase_description);
									$is_oil = $this->Manager_model->like_match("%oil%",$purchase_description);
									$is_lpg = $this->Manager_model->like_match("%lpg%",$purchase_description);
									$is_oxygen = $this->Manager_model->like_match("%oxygen%",$purchase_description);
									$is_lubricant= $this->Manager_model->like_match("%lubricant%",$purchase_description);
									$is_paint= $this->Manager_model->like_match("%paint%",$purchase_description);
									$is_jotun= $this->Manager_model->like_match("%jotun%",$purchase_description);
									$is_water = $this->Manager_model->like_match("%fresh water%",$purchase_description);


									if($is_fuel!== false){
										$fuel_amount = $fuel_amount + $item_amount ; 

										$fuel_table .= "<tr>
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

									}elseif($is_oil!== false ){
										$oil_amount = $oil_amount + $item_amount; 

										$oil_table .= "<tr>
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
									}elseif($is_lpg!== false ){
										$lpg_amount = $lpg_amount + $item_amount; 

										$lpg_table .= "<tr>
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
									}elseif($is_oxygen!== false ){
										$oxygen_amount = $oxygen_amount + $item_amount; 

										$lpg_table .= "<tr>
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
									}elseif($is_lubricant!== false){
										$lubricant_amount = $lubricant_amount + $item_amount; 

										$oil_table .= "<tr>
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
									}elseif($is_paint!== false){
										$paint_amount = $paint_amount + $item_amount; 

										$paints_table .= "<tr>
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
									}elseif($is_jotun!== false){
										$jotun_amount = $jotun_amount + $item_amount; 

										$paints_table .= "<tr>
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
									}elseif($is_water!== false){
										$water_amount = $water_amount + $item_amount; 

										$water_table .= "<tr>
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
									}else{
										$otherx_amount = $otherx_amount + $item_amount; 

										$otherx_table .= "<tr>
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
									}

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
			        	$total_amount_issuance = 0;
			        	$steel_amount =0;
			        	$spareparts_amount =0;
			        	$otherxx_amount =0;
			        	$steel_table ="";
			        	$spareparts_table = "";
			        	$otherxx_table = "";
				        $ctr=1;
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
			        			foreach($row3->details as $detail3){
			        				$item_amount = $detail3['unit_price'] * $detail3['qty'];
			        				$issuance_amount = $issuance_amount + $item_amount;

			        				$sql3 = "SELECT * FROM inventory_items WHERE id=".$detail3['item_id'];
			        				$query3 = $this->db->query($sql3);
			        				if($query3){
			        					$result3 = $query3->result();
			        					if($result3[0]->category==4){//steel materials and pipes
			        						$steel_amount = $steel_amount + $item_amount;

			        						$steel_table .= "<tr>
														<td>
															".$row3->id."
														</td>
														<td>
															".$result3[0]->description.",".$result3[0]->particular."
														</td>
														<td>
															".$row3->issue_date."
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

			        					}elseif($result3[0]->category==5){//spare parts
			        						$spareparts_amount = $spareparts_amount + $item_amount;

			        						$spareparts_table .= "<tr>
														<td>
															".$row3->id."
														</td>
														<td>
															".$result3[0]->description.",".$result3[0]->particular."
														</td>
														<td>
															".$row3->issue_date."
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
			        					}else{

			        						$otherxx_amount  = $otherxx_amount + $item_amount;
			        						$otherxx_table .= "<tr>
														<td>
															".$row3->id."
														</td>
														<td>
															".$result3[0]->description.",".$result3[0]->particular."
														</td>
														<td>
															".$row3->issue_date."
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

			        	<td style="text-align: center"><?php echo number_format($others_amount-$drydocking_amount,2,'.',',');?>
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
			        		<?php $total_job_amount = ($sandblasting_amount + $painting_amount + $cropout_amount + $reconditioning_amount + $electrical_amount + $cleaning_amount + $welding_amount + $fabrication_amount + $machining_amount + $others_amount)-$drydocking_amount;
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
			        	<td colspan="2" style="font-size:14px"><b>Materials & Supplies</b></td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showFuel();'> <b><u>Fuel</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($fuel_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='fuel' class='hide'>
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
			        				<?php echo $fuel_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showOil();'> <b><u>Oil & Lubricants</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($oil_amount+$lubricant_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='oil' class='hide'>
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
			        				<?php echo $oil_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showLPG();'> <b><u>LPG & Oxygen Tanks</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($lpg_amount + $oxygen_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='lpg' class='hide'>
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
			        				<?php echo $lpg_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showPaints();'> <b><u>Paints</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($paint_amount+$jotun_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='paints' class='hide'>
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
			        				<?php echo $paints_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showWater();'> <b><u>Water</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($water_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='water' class='hide'>
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
			        				<?php echo $water_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showOthersX();'> <b><u>Others</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($otherx_amount,2,'.',',');?></td>
			        </tr>
			        <tr id='otherX' class='hide'>
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
			        				<?php echo $otherx_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td style="text-align: right"><b>Total PO Amount</b></td>
			        	<td style="text-align: center"><b>
			        		<?php $total_consumable_amount = ($fuel_amount + $oil_amount + $lubricant_amount + + $lpg_amount + $oxygen_amount + $paint_amount + $jotun_amount + $water_amount + $otherx_amount);
								echo number_format($total_consumable_amount,2,'.',',');
			        		?></b></td>
			        </tr>
			        
		    </table>
			</div>

		</div>
		 <table class='table table-bordered'>
		 	<tr>
	        	<td style="text-align: right;font-size:18px; background-color: lightblue"><b>Grand Total (Dry-docking + Afloat Repairs + Materials & Supplies)</b></td>
	        	<td style="text-align: center;font-size:18px"><?php echo number_format($drydocking_amount + $total_job_amount+$total_consumable_amount,2,'.',',');?></td>
	        </tr>
	     </table>

	    <div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Warehouse Issuances</h3>
			</div>
			<div class="panel-body">
				
			    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
			        <tr>
			        	<td colspan="2" style="font-size:14px"><b>Category</b></td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showSteel();'> <b><u>Steel Materials and Pipes</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($steel_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='steel' class='hide'>
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
			        				<?php echo $steel_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showSpareParts();'> <b><u>Mechanical and Spare Parts</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($spareparts_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='spareparts' class='hide'>
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
			        				<?php echo $spareparts_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			        <tr>
			        	<td>&nbsp<a class='exclude-pageload' onclick='javascript:showOthersXX();'> <b><u>Others</u></b> </a></td>
			        	<td style="text-align: center"><?php echo number_format($otherxx_amount,2,'.',',');?></td>
			        </tr>
			         <tr id='otherXX' class='hide'>
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
			        				<?php echo $otherxx_table; ?>
			        			</tbody>
			        		</table>
			  			</td>
			        </tr>
			         <tr>
			        	<td style="text-align: right"><b>Total Issuance Amount</b></td>
			        	<td style="text-align: center"><b>
			        		<?php $total_issuance_amount = ($steel_amount + $spareparts_amount + $otherxx_amount);
								echo number_format($total_issuance_amount,2,'.',',');
			        		?></b></td>
			        </tr>
		       </table>
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


    // specify chart configuration item and data
    var option_pie = {
        title: {
            text: 'Pie Chart',
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
            {value:<?php echo $others_amount-$drydocking_amount;?>, name:'Others'}]
        }]
    };

    var option_bar = {
        title: {
            text: 'Bar Graph',
            x:'center'
        },
        tooltip: {
        },
        xAxis: {
	        type: 'category',
	        data: ['Fuel', 'Oil & Lubricants', 'LPG & Oxygen Tanks', 'Paints', 'Water', 'Others']
	    },
	    yAxis: {
	        type: 'value'
	    },
	    series: [{
	        data: [<?php echo $fuel_amount?>, <?php echo $oil_amount + $lubricant_amount ?>, <?php echo $lpg_amount + $oxygen_amount ?>, <?php echo $paint_amount + $jotun_amount?>, <?php echo $water_amount?>, <?php echo $otherx_amount?>],
	        type: 'bar'
	    }]
    };

    // use configuration item and data specified to show chart
    pieChart.setOption(option_pie);
    barChart.setOption(option_bar);

</script>