<!DOCTYPE html>
<html>
<head>
	<title>View Out-Turn Summary</title>

<style>
	.table td, .table th {
   	 min-width: 100px;
	}
</style>

</head>
<body>
<h2>Out-Turn Summary</h2>
<div>
<?php
	if($OS->status=="Draft"){
		if($this->Abas->checkPermissions("operations|add_out_turn_summary",FALSE)){
			echo '<a href="#" onclick="submitOS('.$OS->id.');" class="btn btn-success exclude-pageload">Submit</a>';
			echo '<a href="'.HTTP_PATH.'operation/out_turn_summary/edit/'.$OS->id.'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit</a>';
		}
	}elseif($OS->status=="For Verification"){
		if(($this->Abas->checkPermissions("operations|verify_out_turn_summary",FALSE) && $_SESSION['abas_login']['user_location'] == $OS->served_by) || ($this->Abas->checkPermissions("operations|verify_out_turn_summary",FALSE) && $_SESSION['abas_login']['role']=='Administrator')){
			echo '<a href="#" onclick="verifyOS('.$OS->id.');" class="btn btn-success exclude-pageload">Verify</a>';
			echo '<a href="#" onclick="returnOS('.$OS->id.');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelOS('.$OS->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($OS->status=="For Approval"){
		if(($this->Abas->checkPermissions("operations|approve_out_turn_summary",FALSE) && $_SESSION['abas_login']['user_location'] == $OS->served_by) || ($this->Abas->checkPermissions("operations|approve_out_turn_summary",FALSE) && $_SESSION['abas_login']['role']=='Administrator')){
			echo '<a href="#" onclick="approveOS('.$OS->id.');" class="btn btn-success exclude-pageload">Approve</a>';
			echo '<a href="#" onclick="returnOS('.$OS->id.');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelOS('.$OS->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($OS->status=="Approved"){
		if($this->Abas->checkPermissions("operations|add_out_turn_summary",FALSE)){
			echo '<a href="'.HTTP_PATH.'operation/out_turn_summary/print/'.$OS->id.'" class="btn btn-info" target="_blank">Print Option 1</a>';
			echo '<a href="'.HTTP_PATH.'operation/out_turn_summary/preview/'.$OS->id.'" class="btn btn-info" target="_blank">Print Option 2</a>';
			if($OS->type_of_service=="Time Charter"){
				//echo '<a href="'.HTTP_PATH.'operation/out_turn_summary/edit/'.$OS->id.'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit</a>';
			}
		}
		$has_billing = $this->Operation_model->checkOutTurnHasSOA($OS->id);
		if($this->Abas->checkPermissions("operations|approve_out_turn_summary",FALSE) && $has_billing==false){
			echo '<a href="#" onclick="returnOS('.$OS->id.');" class="btn btn-warning exclude-pageload">Return</a>';
		}
		
	}

	echo '<a href="'.HTTP_PATH.'operation/out_turn_summary/listview" class="btn btn-dark force-pageload">Back</a>';
		
?>
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code: <?php echo $OS->id; ?>
				| Control No. <?php echo $OS->control_number; ?>
				<span class="pull-right">Status: <?php echo $OS->status; ?></span>
			</h3>
		</div>
		
		<div class="panel-body">
			<h3 class="text-center"><?php echo $OS->company_name; ?></h3>
			<h4 class="text-center"><?php echo $OS->company_address; ?></h3>
			<h4 class="text-center"><?php echo $OS->company_contact; ?></h4>
		
		<?php 
			if($OS->comments!="" || $OS->comments!=NULL){
				echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    	<strong>Comments:</strong> '.$OS->comments.'
                  	  </div>';
			}

			$mother_contract = $this->Operation_model->getMotherContract($contract['id']);
			if($mother_contract==NULL){
				$mother_contract = "";
			}else{
				$mother_contract = " | Mother Contract Ref. No.: ".$mother_contract->reference_no;
			}

		?>

			<table class="table table-striped table-bordered">
				<tr>
					<td><b>Contract Reference No.:</b></td>
					<td><?php echo $contract['reference_no'].$mother_contract?></td>
				</tr>
				<tr>
					<td><b>Client:</b></td>
					<td><?php echo $contract['client']['company'] ?></td>
				</tr>
				<tr>
					<td><b>Type of Service:</b></td>
					<td><?php echo $OS->type_of_service ?></td>
				</tr>
				<?php if(isset($SO)){?>
					<tr>
						<td><b>Vessel:</b></td>
						<td><?php echo $SO->details->vessel;?></td>
					</tr>
				<?php } ?>
				<?php if(isset($SO_Details)){?>
					<tr>
						<td><b>Cargo Description:</b></td>
						<td><?php echo $SO_Details->cargo_description;?></td>
					</tr>
				<?php } ?>
				<tr>
					<td><b>Remarks:</b></td>
					<?php 
						if($OS->remarks!=""){
							echo "<td colspan='3'>".$OS->remarks."</td>";
						}
						else{
							echo "<td colspan='3'>No Remarks</td>";
						} 
					?>
				</tr>
			</table>

			<p>Created on <?php echo date("h:i:s a j F Y", strtotime($OS->created_on)); ?> by <?php echo $OS->full_name; ?></p>
			
			<?php if($OS->submitted_on!=0){?>
				<p>Submitted on <?php echo date("h:i:s a j F Y", strtotime($OS->submitted_on)); ?> by <?php echo $OS->submitted_by_name; ?></p>
			<?php }?>
			
			<?php if($OS->verified_on!=0){?>
				<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($OS->verified_on)); ?> by <?php echo $OS->verified_by_name; ?></p>
			<?php }?>

			<?php if($OS->approved_on!=0){?>
				<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($OS->approved_on)); ?> by <?php echo $OS->approved_by_name; ?></p>
			<?php }?>
			
			<?php if($OS->times_returned_to_draft>0){?>
				<p>No. of times returned to 'Draft' status on: <?php echo $OS->times_returned_to_draft; ?></p>
			<?php }?>

		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Details</h3>
		</div>
		<div class="panel-body">
		
		<?php if($OS->type_of_service=="Shipping"){ ?>
			<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Details</h4>
				</a>
				 <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
					<div class="panel-body">
						<table class="table table-striped">
							<?php if($OS_Details->bill_of_lading_number!=0){ ?>
							<tr>
								<td><b>Bill of Lading:</b></td>
								<td><?php echo $OS_Details->bill_of_lading_number?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->quantity_per_bill_of_lading!=0){ ?>
							<tr>
								<td><b>Quantity per BOL:</b></td>
								<td><?php echo $OS_Details->quantity_per_bill_of_lading?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->weight_per_bill_of_lading!=0){ ?>
							<tr>
								<td><b>Weight per BOL:</b></td>
								<td><?php echo $OS_Details->weight_per_bill_of_lading?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->shipper!=""){ ?>
							<tr>
								<td><b>Shipper:</b></td>
								<td><?php echo $OS_Details->shipper?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->consignee!=""){ ?>
							<tr>
								<td><b>Consignee:</b></td>
								<td><?php echo $OS_Details->consignee?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->surveyor!=""){ ?>
							<tr>
								<td><b>Surveyor:</b></td>
								<td><?php echo $OS_Details->surveyor?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->arrastre!=""){ ?>
							<tr>
								<td><b>Arrastre:</b></td>
								<td><?php echo $OS_Details->arrastre?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->vessel_id!=0){ ?>
							<tr>
								<td><b>Local Vessel:</b></td>
								<td><?php echo $OS_Details->vessel_name;?>
								</td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->mother_vessel!=""){ ?>
							<tr>
								<td><b>Mother Vessel:</b></td>
								<td><?php echo $OS_Details->mother_vessel?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->voyage_number!=0){ ?>
							<tr>
								<td><b>Voyage Number:</b></td>
								<td><?php echo $OS_Details->voyage_number?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->port_of_origin!=""){ ?>
							<tr>
								<td><b>Port of Origin:</b></td>
								<td><?php echo $OS_Details->port_of_origin?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->port_of_destination!=""){ ?>
							<tr>
								<td><b>Port of Destination:</b></td>
								<td><?php echo $OS_Details->port_of_destination?></td>
							</tr>
							<?php } ?>
							<tr>
								<td><b>Loading Arrival Date:</b></td>
								<td><?php echo date("j F Y", strtotime($OS_Details->loading_arrival))?></td>
							</tr>
							<tr>
								<td><b>Loading - Start Date/Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->loading_start))?></td>
							</tr>
							<tr>
								<td><b>Loading - Ended Date/Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->loading_ended))?></td>
							</tr>
							<tr>
								<td><b>Loading - Departure Date:</b></td>
								<td><?php echo date("j F Y", strtotime($OS_Details->loading_departure))?></td>
							</tr>
							<tr>
								<td><b>Loading - Quantity/Volume:</b></td>
								<td><?php echo $OS_Details->loading_quantity_volume?></td>
							</tr>
							<tr>
								<td><b>Unloading - Arrival Date:</b></td>
								<td><?php echo date("j F Y", strtotime($OS_Details->unloading_arrival))?></td>
							</tr>
							<tr>
								<td><b>Unloading - Start Date/Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->unloading_start))?></td>
							</tr>
							<tr>
								<td><b>Unloading - Ended Date/Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->unloading_ended))?></td>
							</tr>
							<tr>
								<td><b>Unloading - Departure Date:</b></td>
								<td><?php echo date("j F Y", strtotime($OS_Details->unloading_departure))?></td>
							</tr>
							<tr>
								<td><b>Unloading - Quantity/Volume:</b></td>
								<td><?php echo $OS_Details->unloading_quantity_volume?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		<?php }?>

		<?php if($OS->type_of_service=="Lighterage" || $OS->type_of_service=="Time Charter"){ ?>
			<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Details</h4>
				</a>
				 <div id="collapse1" class="panel-collapse" role="tabpanel" aria-labelledby="heading1">
					<div class="panel-body">
						<table class="table table-striped">

							<?php if($OS_Details->lighterage_receipt_number!=""){ ?>
							<tr>
								<td><b>Lighter Receipt No.:</b></td>
								<td><?php echo $OS_Details->lighterage_receipt_number?></td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->trip_ticket_number!=""){ ?>
							<tr>
								<td><b>Trip Ticket No.:</b></td>
								<td><?php echo $OS_Details->trip_ticket_number?></td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->statement_of_facts_number!=""){ ?>
							<tr>
								<td><b>Statement of Facts Ref.:</b></td>
								<td><?php echo $OS_Details->statement_of_facts_number?></td>
							</tr>
							<?php } ?>
							
							<?php if($OS_Details->shipper!=""){ ?>
							<tr>
								<td><b>Shipper:</b></td>
								<td><?php echo $OS_Details->shipper?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->consignee!=""){ ?>
							<tr>
								<td><b>Consignee:</b></td>
								<td><?php echo $OS_Details->consignee?></td>
							</tr>
							<?php } ?>
							
							
							<?php if($OS_Details->vessel_id!=0){ ?>
							<tr>
								<td><b>Tugboat/Barge:</b></td>
								<td><?php echo $OS_Details->vessel_name;?>
								</td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->vessel_id!=0){ ?>
							<tr>
								<td><b>Barge Patron:</b></td>
								<td><?php echo $OS_Details->barge_patron;?>
								</td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->mother_vessel!=""){ ?>
							<tr>
								<td><b>Mother Vessel:</b></td>
								<td><?php echo $OS_Details->mother_vessel?></td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->voyage_number!=0){ ?>
							<tr>
								<td><b>Voyage Number:</b></td>
								<td><?php echo $OS_Details->voyage_number?></td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->port_of_origin!=""){ ?>
							<tr>
								<td><b>Loading Point:</b></td>
								<td><?php echo $OS_Details->port_of_origin?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->port_of_destination!=""){ ?>
							<tr>
								<td><b>Unloading Point:</b></td>
								<td><?php echo $OS_Details->port_of_destination?></td>
							</tr>
							<?php } ?>
							<tr>
								<td><b>Loading Arrival Date:</b></td>
								<td><?php echo date("j F Y", strtotime($OS_Details->loading_arrival))?></td>
							</tr>
							<tr>
								<td><b>Loading - Start Date/Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->loading_start))?></td>
							</tr>
							<tr>
								<td><b>Loading - Ended Date/Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->loading_ended))?></td>
							</tr>
							<tr>
								<td><b>Loading - Departure Date:</b></td>
								<td><?php echo date("j F Y", strtotime($OS_Details->loading_departure))?></td>
							</tr>
							<tr>
								<td><b>Loading - Quantity/Volume:</b></td>
								<td><?php echo $OS_Details->loading_quantity_volume?></td>
							</tr>
							<tr>
								<td><b>Loading - Batch Weight:</b></td>
								<td><?php echo $OS_Details->loading_batch_weight?></td>
							</tr>

							<tr>
								<td><b>Unloading - Arrival Date:</b></td>
								<td><?php echo date("j F Y", strtotime($OS_Details->unloading_arrival))?></td>
							</tr>
							<tr>
								<td><b>Unloading - Start Date/Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->unloading_start))?></td>
							</tr>
							<tr>
								<td><b>Unloading - Ended Date/Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->unloading_ended))?></td>
							</tr>
							<tr>
								<td><b>Unloading - Departure Date:</b></td>
								<td><?php echo date("j F Y", strtotime($OS_Details->unloading_departure))?></td>
							</tr>
							<tr>
								<td><b>Unloading - Quantity/Volume:</b></td>
								<td><?php echo $OS_Details->unloading_quantity_volume?></td>
							</tr>
							<tr>
								<td><b>Unloading - Batch Weight:</b></td>
								<td><?php echo $OS_Details->unloading_batch_weight?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		<?php }?>

		<?php if($OS->type_of_service=="Towing"){ ?>
			<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Details</h4>
				</a>
				 <div id="collapse1" class="panel-collapse" role="tabpanel" aria-labelledby="heading1">
					<div class="panel-body">
						<table class="table table-striped">

							

							<?php if($OS_Details->trip_ticket_number!=""){ ?>
							<tr>
								<td><b>Trip Ticket No.:</b></td>
								<td><?php echo $OS_Details->trip_ticket_number?></td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->voyage_number!=0){ ?>
							<tr>
								<td><b>Voyage Number:</b></td>
								<td><?php echo $OS_Details->voyage_number?></td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->shipper!=""){ ?>
							<tr>
								<td><b>Account/Customer:</b></td>
								<td><?php echo $OS_Details->shipper?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->consignee!=""){ ?>
							<tr>
								<td><b>Consignee:</b></td>
								<td><?php echo $OS_Details->consignee?></td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->vessel_id!=0){ ?>
							<tr>
								<td><b>Master/Patron:</b></td>
								<td><?php echo $OS_Details->barge_patron;?>
								</td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->vessel_id!=0){ ?>
							<tr>
								<td><b>Servicing Vessel:</b></td>
								<td><?php echo $OS_Details->vessel_name;?>
								</td>
							</tr>
							<?php } ?>

							<?php if($OS_Details->mother_vessel!=""){ ?>
							<tr>
								<td><b>Craft Towed:</b></td>
								<td><?php echo $OS_Details->mother_vessel?></td>
							</tr>
							<?php } ?>

						
							<?php if($OS_Details->port_of_origin!=""){ ?>
							<tr>
								<td><b>Departure Location:</b></td>
								<td><?php echo $OS_Details->port_of_origin?></td>
							</tr>
							<?php } ?>
							<?php if($OS_Details->port_of_destination!=""){ ?>
							<tr>
								<td><b>Arrival Location:</b></td>
								<td><?php echo $OS_Details->port_of_destination?></td>
							</tr>
							<?php } ?>
							
							<tr>
								<td><b>Departure Date and Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->loading_start))?></td>
							</tr>
						
							<tr>
								<td><b>Arrival Date and Time:</b></td>
								<td><?php echo date("j F Y h:i:s a ", strtotime($OS_Details->unloading_ended))?></td>
							</tr>
				
						</table>
					</div>
				</div>
			</div>
		<?php }?>

		<?php if($OS->type_of_service=="Trucking" || $OS->type_of_service=="Handling"){ ?>

			<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Breakdown of Deliveries</h4>
				</a>
				 <div id="collapse1" class="panel-collapse" role="tabpanel" aria-labelledby="heading1">
					<div class="panel-body" style="overflow: auto">
			            <table class="table table-striped">
							
								 <thead>

				            		<?php 
				            			if($OS->type_of_service=='Trucking'){
				            				echo  "<th class='text-center' style='width:10px'></th>
				            						<th class='text-center' style='width:10px'>Trip #</th>
								                    <th class='text-center'>Date</th>
								                    <th class='text-center'>Plate No.</th>
								                    <th class='text-center'>Driver</th>
								                    <th class='text-center'>Trucking Co.</th>
								                    <th class='text-center'>WH/Consignee</th>
								                    <th class='text-center'>Item Description</th>
								                    <th class='text-center'>Quantity</th>
								                    <th class='text-center'>Gross Wt.</th>
								                    <th class='text-center'>Tare Wt.</th>
								                    <th class='text-center'>Net Wt.</th>
								                    <th class='text-center'>Transaction</th>
								                    <th class='text-center'>DR No.</th>
								                    <th class='text-center'>WT No.</th>
								                    <th class='text-center'>WIF No.</th>
								                    <th class='text-center'>WRF No.</th>
								                    <th class='text-center'>WB No.</th>
								                    <th class='text-center'>ATL No.</th>
								                    <th class='text-center'>CR No.</th>
								                    <th class='text-center'>Others</th>";
				            			}elseif($OS->type_of_service=='Handling'){
				            				echo  "<th class='text-center' style='width:10px'></th>
				            						<th class='text-center' style='width:10px'>#</th>
								                    <th class='text-center'>Date</th>
								                    <th class='text-center'>Warehouse</th>
								                    <th class='text-center'>Quantity</th>
								                    <th class='text-center'>Weight</th>
								                    <th class='text-center'>No. of Moves</th>
								                    <th class='text-center'>Variety</th>
								                    <th class='text-center'>Transaction</th>
								                    <th class='text-center'>WIF No.</th>
								                    <th class='text-center'>WRF No.</th>
								                    <th class='text-center'>Others</th>";
				            			}

				            		?>
				           		 </thead>

				           		 <tbody>
									<?php

										$ctr=1;
										$total_bags =0;
										$total_gross_wt = 0;
										$total_tare_wt = 0;
										$toyal_net_wt = 0;

										foreach($OS_Deliveries as $delivery){
											
											if($OS->type_of_service=='Trucking'){
					            				echo  "<tr>
					            							 <td class='text-center'><input type='checkbox'></td>
					            							 <td class='text-center'>".$ctr."</td>
															 <td class='text-center'>".$delivery['delivery_date']."</td>
															 <td class='text-center'>".$delivery['truck_plate_number']."</td>
											    			 <td class='text-center'>".$delivery['truck_driver']."</td>
											    			 <td class='text-center'>".$delivery['trucking_company']."</td>
											    			 <td class='text-center'>".$delivery['warehouse']."</td>
											    			 <td class='text-center'>".$delivery['variety_item']."</td>
											    			 <td class='text-center'>".number_format($delivery['quantity'],4,'.',',')."</td>
											    			 <td class='text-center'>".number_format($delivery['gross_weight'],4,'.',',')."</td>
											    			 <td class='text-center'>".number_format($delivery['tare_weight'],4,'.',',')."</td>
											    			 <td class='text-center'>".number_format($delivery['net_weight'],4,'.',',')."</td>
											    			 <td class='text-center'>".$delivery['transaction']."</td>
											    			 <td class='text-center'>".$delivery['delivery_receipt_number']."</td>
											    			 <td class='text-center'>".$delivery['weighing_ticket_number']."</td>
											    			 <td class='text-center'>".$delivery['warehouse_issuance_form_number']."</td>
											    			 <td class='text-center'>".$delivery['warehouse_receipt_form_number']."</td>
											    			 <td class='text-center'>".$delivery['way_bill_number']."</td>
											    			 <td class='text-center'>".$delivery['authority_to_load_number']."</td>
											    			 <td class='text-center'>".$delivery['cargo_receipt_number']."</td>
											    			 <td class='text-center'>".$delivery['others']."</td>
					            				       </tr>";
					            			}elseif($OS->type_of_service=='Handling'){
					            				echo "<tr>	
					            							<td class='text-center'><input type='checkbox'></td>
															 <td class='text-center'>".$ctr."</td>
															 <td class='text-center'>".$delivery['delivery_date']."</td>
											    			 <td class='text-center'>".$delivery['warehouse']."</td>
											    			 <td class='text-center'>".number_format($delivery['quantity'],4,'.',',')."</td>
											    			 <td class='text-center'>".number_format($delivery['gross_weight'],4,'.',',')."</td>
											    			 <td class='text-center'>".$delivery['number_of_moves']."</td>	
											    			 <td class='text-center'>".$delivery['variety_item']."</td>
											    			 <td class='text-center'>".$delivery['transaction']."</td>
											    			 <td class='text-center'>".$delivery['warehouse_issuance_form_number']."</td>
											    			 <td class='text-center'>".$delivery['warehouse_receipt_form_number']."</td>
											    			 <td class='text-center'>".$delivery['others']."</td>
					            					  </tr>";
					            			}
					            			
					            			$total_bags = $total_bags + $delivery['quantity'];
											$total_gross_wt = $total_gross_wt + $delivery['gross_weight'];
											$total_tare_wt = $total_tare_wt + $delivery['tare_weight'];
											$toyal_net_wt = $toyal_net_wt + $delivery['net_weight'];

					            			$ctr++;
										}

											if($OS->type_of_service=='Handling'){
												echo "
																<tr>
																	<td colspan='4'></td>
																	<td class='text-center'>".number_format($total_bags,4,'.',',')."</td>
																	<td class='text-center'>".number_format($total_gross_wt,4,'.',',')."</td>
																</tr>";
											}elseif($OS->type_of_service=='Trucking'){
												echo "
																<tr>
																	<td colspan='8'></td>
																	<td class='text-center'><b>".number_format($total_bags,4,'.',',')."</b></td>
																	<td class='text-center'><b>".number_format($total_gross_wt,4,'.',',')."</b></td>
																	<td class='text-center'><b>".number_format($total_tare_wt,4,'.',',')."</b></td>
																	<td class='text-center'><b>".number_format($toyal_net_wt,4,'.',',')."</b></td>
																</tr>";
											}
									?>
								</tbody>

			            </table>
			        </div>
				 </div>
			
			<?php } ?>

			<?php if($OS->type_of_service=='Shipping'){ ?>
			<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading" role="tab" id="heading2" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Attachments</h4>
				</a>
				 <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
					<div class="panel-body">
			            <table class="table table-striped">
							<thead>
								<th>#</th>
								<th>Document Name</th>
							</thead>
							<tbody>
								<?php
									
									if(count($OS_Attachments)!=0){
										$ctr=1;
										foreach($OS_Attachments as $attachment){
											echo "<tr>
													<td>".$ctr."</td>
													<td>".$attachment['document_name']."</td>
												 </tr>";
										$ctr++;
										}		
									}else{
										echo "<tr>
												<td>No Attachments</td>
											 </tr>";
								}

								?>
							</tbody>
			            </table>
			        </div>
				 </div>
			
			<?php } ?>
			<?php if($OS->type_of_service=='Shipping'){ ?>
			<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading" role="tab" id="heading3" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Final Output</h4>
				</a>
				 <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
					<div class="panel-body">

					<?php if($OS->type_of_service!='Warehousing/Cargo Handling' && $OS->type_of_service!='Shipping - Vessel to Vessel'){?>
			            <table class="table table-striped table-bordered">
							<thead>
								<th>Cargo Declared Per:</th>
								<th>No. of Bags</th>
								<th>Weight</th>
							</thead>
							<tbody>
								<tr>
									<td><center>Shipper</center></td>
									<td><?php echo $OS_Output->shipper_number_of_bags?></td>
									<td><?php echo $OS_Output->shipper_weight?></td>
								</tr>
								<tr>
									<td><center>Consignee</center></td>
									<td><?php echo $OS_Output->consignee_number_of_bags?></td>
									<td><?php echo $OS_Output->consignee_weight?></td>
								</tr>
								<tr>
									<td><center>Variance</center></td>
									<td><?php echo $OS_Output->variance_number_of_bags?></td>
									<td><?php echo $OS_Output->variance_weight?></td>
								</tr>
								<tr>
									<td><center>Percentage</center></td>
									<td><?php echo $OS_Output->percentage_number_of_bags . "%"?></td>
									<td><?php echo $OS_Output->percentage_weight . "%"?></td>
								</tr>
								<tr><td colspan='2'><b>Accounted For:</b></td></tr>
								<tr>
									<td><center>Good</center></td>
									<td><?php echo $OS_Output->good_number_of_bags?></td>
								</tr>
								<tr>
									<td><center>Damaged</center></td>
									<td><?php echo $OS_Output->damaged_number_of_bags?></td>
								</tr>
								<tr>
									<td><center>Total</center></td>
									<td><?php echo $OS_Output->total_number_of_bags?></td>
								</tr>
							</tbody>
			            </table>
			            <?php } ?>



			        </div>
				 </div>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
</html>

<script>
	function submitOS(id){

	bootbox.confirm({
   					size: "small",
   					title: "Out-Turn Summary",
				    message: "Are you sure you want to submit this Out-Turn Summary?",
				    buttons: {
				        confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				    	if(result){
				    		window.location.href = "../../out_turn_summary/submit/" + id;
				    	}
				    }
				});
	}

	function verifyOS(id){

	bootbox.confirm({
   					size: "small",
   					title: "Out-Turn Summary",
				    message: "Are you sure you want to verify this Out-Turn Summary?",
				    buttons: {
				       confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				    	if(result){
				    		window.location.href = "../../out_turn_summary/verify/" + id;
				    	}
				    }
				});
	}

	function approveOS(id){

	bootbox.confirm({
   					size: "small",
   					title: "Out-Turn Summary",
				    message: "Are you sure you want to approve this Out-Turn Summary?",
				    buttons: {
				       confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				    	if(result){
				    		window.location.href = "../../out_turn_summary/approve/" + id;
				    	}
				    }
				});
	}

	function returnOS(id){

	bootbox.prompt({
   					size: "medium",
   					title: "Are you sure you want to return this Out-Turn Summary to 'Draft' status? (Please provide reason below.)",
				    inputType: 'textarea',
				    buttons: {
				        confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				    	
				    	if(result==null || result==""){
				    	}else{
				    		window.location.href = "../../out_turn_summary/return/" + id;

				    		$.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH;?>operation/setOutTurnSummaryComments/"+id,
							     data: {comments:result}
							  });
				    	}

				    }
				});
	}

	function cancelOS(id){

	bootbox.prompt({
   					size: "medium",
				    title: "Are you sure you want to cancel this Out-Turn Summary? (Please provide reason below.)",
				    inputType: 'textarea',
				    buttons: {
				        confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {

				    	if(result==null || result==""){
				    	}else{
				    		window.location.href = "../../out_turn_summary/cancel/" + id;

				    		$.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH;?>operation/setOutTurnSummaryComments/"+id,
							     data: {comments:result}
							  });
				    	}
				    }
				});
	}
</script>
