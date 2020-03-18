<!DOCTYPE html>
<html>
<head>
	<title>View Contract</title>
	<style type="text/css">
		.ui-widget-content {
		    border: 0px solid #666;
		    background: rgba(0, 0, 0, 0.3);
		    color: #fff;
		}
	</style>
</head>
<body>
<h2>Contract</h2>
<div>
<?php

	if($SC['status']!="Voided"){
		if($this->Abas->checkPermissions("operations|add_contract",FALSE)){
			if($SC['status']=="Draft"){
				echo '<a href="#" onclick="submitContract('.$SC['id'].');" class="btn btn-success exclude-pageload">Submit</a>';
				echo '<a href="'.HTTP_PATH.'operation/service_contract/edit/'.$SC['id'].'" class="btn btn-warning" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit</a>';
				echo '<a href="#" onclick="voidContract('.$SC['id'].');" class="btn btn-danger exclude-pageload">Void</a>';
			}
		}
		if($this->Abas->checkPermissions("operations|approve_contract",FALSE)){
			if($SC['status']=="For Approval"){
				echo '<a href="#" onclick="approveContract('.$SC['id'].');" class="btn btn-success exclude-pageload">Approve</a>';
			}
			
			if($SC['status']!="Draft"){
				echo '<a href="'.HTTP_PATH.'operation/service_contract/edit/'.$SC['id'].'" class="btn btn-warning" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit</a>';
				echo '<a href="#" onclick="voidContract('.$SC['id'].');" class="btn btn-danger exclude-pageload">Void</a>';
			}

		}
	}

	echo '<a href="'.HTTP_PATH.'operation/service_contract/listview" class="btn btn-dark">Back</a>';

?>
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code: <?php echo $SC['id']; ?>
				| Control No. <?php echo $SC['control_number']; ?>
				<span class="pull-right">Overall Status:
					 <?php 
					 	if($SC['status']!="Voided"){
							echo $overall_percentage;
						}else{
							echo "Voided at ".$overall_percentage; 
						}
					 ?>
				</span>
			</h3>
		</div>
		
		<div class="panel-body">
			<h3 class="text-center"><?php echo $SC['company']->name; ?></h3>
			<h4 class="text-center"><?php echo $SC['company']->address; ?></h3>
			<h4 class="text-center"><?php echo $SC['company']->telephone_no; ?></h4>
		
		<?php 
			if($SC['remark']!="" || $SC['remark']!=NULL){
				echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    	<strong>Comments:</strong> '.$SC['remark'].'
                  	  </div>';
			}
		?>

			<table class="table table-striped table-bordered">
				<tr>
					<td><b>Contract Reference No.:</b></td>
					<td><?php echo $SC['reference_no'] ?></td>
				</tr>
				<tr>
					<td><b>Client:</b></td>
					<td><?php echo $SC['client']['company'] ?></td>
				</tr>
				<tr>
					<td><b>Service Type:</b></td>
					<td><?php echo $SC['type'] ?></td>
				</tr>
				<tr>
					<td><b>Contract Date:</b></td>
					<td><?php echo $SC['contract_date'] ?></td>
				</tr>
				<tr>
					<td><b>Contract Type:</b></td>
					<td>
						<?php 
							if($SC['parent_contract_id']==NULL || $SC['parent_contract_id']==0){
								echo "Mother Contract";
							}else{
								echo "Sub Contract";
							}
						?>
					</td>
				</tr>
				<?php 
					if($SC['parent_contract_id']!=0){ ?>
						<tr>
							<td><b>Mother Contract Reference No.:</b></td>
							<td><?php echo $SC['mother_contract']['reference_no'] . " (" . $SC['mother_contract']['company']->name . ")"?></td>
						</tr>
				<?php } ?>
				<!--<tr>
					<td><b>Quantity:</b></td>
					<td><?php //echo $SC['quantity'] ?></td>
				</tr>
				<tr>
					<td><b>Unit:</b></td>
					<td><?php //echo $SC['unit'] ?></td>
				</tr>
				<tr>
					<td><b>Fixed Rate:</b></td>
					<td><?php //echo number_format($SC['rate'],2,".",",") ?></td>
				</tr>-->
				<tr>
					<td><b>Total Contract Amount:</b></td>
					<td><?php echo number_format($SC['amount'],2,".",",") ?></td>
				</tr>
				
				
			</table>

			<table class="table table-striped table-bordered">
				<tr>
					<td><b>Contract Details:</b></td>
				</tr>
				<tr>
					<?php 
						if($SC['details']!=""){
							echo "<td>".$SC['details']."</td>";
						}
						else{
							echo "<td>No Details</td>";
						} 
					?>
				</tr>
			</table>
			
			<?php if(count($SC_Rates)>0){?>
			<div style="overflow-x: auto">
			<table class="table table-striped table-bordered">
				<thead>
					<th><center>Warehouse/Port/Destination</center></th>
					<th><center>Quantity</center></th>
					<th><center>Unit</center></th>
					<th><center>Rate</center></th>
					<th><center>Total Amount (Qty x Rate)</center></th>
					<th><center>Addtl Charges</center></th>
					<th><center>Total Amount + Addtl Charges</center></th>
				</thead>
				<tbody>
				<?php
					$total_qty=$total_addtl=$grand_total1=$grand_total2=0;
					foreach($SC_Rates as $detail){
						$total_amount1 = ($detail['quantity']*$detail['rate']);
						$total_amount2 = ($detail['quantity']*$detail['rate'])+$detail['additional_charges'];
						$total_qty = $total_qty + $detail['quantity'];
						$total_addtl = $total_addtl + $detail['additional_charges'];
						$grand_total1 = $grand_total1 + $total_amount1;
						$grand_total2 = $grand_total2 + $total_amount2;
						echo "<tr>";
							echo "<td align='center'>".$detail['warehouse']."</td>";
							echo "<td align='center'>".$detail['quantity']."</td>";
							echo "<td align='center'>".$detail['unit']."</td>";
							echo "<td align='center'>".number_format($detail['rate'],2,'.',',')."</td>";
							echo "<td align='center'>".number_format($total_amount1,2,'.',',')."</td>";
							echo "<td align='center'>".number_format($detail['additional_charges'],2,'.',',')."</td>";
							echo "<td align='center'>".number_format($total_amount2,2,'.',',')."</td>";
						echo "</tr>";


					}
					echo "<tr>";
						echo "<td align='center'></td>";
						echo "<td align='center'>".$total_qty."</td>";
						echo "<td align='center' colspan='2'></td>";
						echo "<td align='center'>PHP ".number_format($grand_total1,2,'.',',')."</td>";
						echo "<td align='center'>PHP ".number_format($total_addtl,2,'.',',')."</td>";
						echo "<td align='center'>PHP ".number_format($grand_total2,2,'.',',')."</td>";
					echo "</tr>";
				?>
				</tbody>
			</table>
			</div>
			<?php } ?>

			<p>Created on <?php echo date("h:i:s a j F Y", strtotime($SC['created_on'])); ?> by <?php echo $SC['created_by']['full_name']; ?></p>
			
			<?php if($SC['updated_by']['full_name']!=NULL){ ?>
				<p>Last Updated on <?php echo date("h:i:s a j F Y", strtotime($SC['updated_on'])); ?> by <?php echo $SC['updated_by']['full_name']; ?></p>
			<?php } ?>

		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Monitoring
			</h3>
		</div>
		
		<div class="panel-title panel-info">
		  
			<?php //if($SC['parent_contract_id']!=NULL){?>
         	<div class="accordion panel-default" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading panel-info" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Sub-contracts</h4>
				<br>
				<div class='progress'>
					<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="70"
					  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $sub_contracts_percentage;?>%">
					    <center><?php echo $sub_contracts_percentage;?>% completed</center>
					 </div>
				</div>
				 <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
					<div class="panel-body" style="overflow: auto">
						<table class='table table-striped table-bordered'>
							<thead>
								<th>#</th>
								<th><center>Transaction Code No.</center></th>
								<th><center>Control No.</center></th>
								<th><center>Contract Ref. No.</center></th>
								<th>Company</th>
								<th>Client</th>
								<th>Service Type</th>
								<th>Contract Date</th>
								<th>Contract Amount</th>
								<th>Overall Status</th>
							</thead>
							<tbody>
						<?php 
							$ctr = 1;
							$ctr = 1;
							$contract_amount = 0;
							if($sub_contracts){
								foreach($sub_contracts as $row1){
									echo "<tr>";
									echo "<td>".$ctr."</td>";
									echo "<td><center>".$row1->id."</center></td>";
									echo "<td><center>".$row1->control_number."</center></td>";
									echo "<td><center>".$row1->reference_no."</center></td>";
									echo "<td>".$row1->company."</td>";
									echo "<td>".$row1->client."</td>";
									echo "<td>".$row1->type."</td>";
									echo "<td>".date('Y-m-d',strtotime($row1->contract_date))."</td>";
									echo "<td align='right'>".number_format($row1->amount,2,'.',',')."</td>";
									echo "<td>".$row1->status."</td>";
									echo "</tr>";
									$ctr++;
									$contract_amount = $contract_amount + $row1->amount;
								}

								echo "<tr align='right'><td colspan='8'></td><td>".number_format($contract_amount,2,'.',',')."</td><td></td></tr>";

							}else{
								echo "<tr><td colspan='10'><center>No Records Found.</center></td></tr>";
							}
						?>
							</tbody>
						</table>
					</div>
				</div>
				</a>
			</div>
			<?php //} ?>
			<div class="accordion panel-default" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading panel-info" role="tab" id="heading2" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Service Orders</h4>
				<br>
				<div class='progress'>
					<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="70"
					  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $service_orders_percentage;?>%">
					    <center><?php echo $service_orders_percentage;?>% completed</center>
					 </div>
				</div>
				 <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
					<div class="panel-body" style="overflow: auto">
						<table class='table table-striped table-bordered'>
							<thead>
								<th>#</th>
								<th><center>Transaction Code No.</center></th>
								<th><center>Control No.</center></th>
								<th>Company</th>
								<th>Client</th>
								<th>Service Order Type</th>
								<th>Service Order Date</th>
								<th>Quantity</th>
								<th>Status</th>
							</thead>
							<tbody>
						<?php 
							$ctr = 1;
							$so_qty =0;
							if($service_orders){
								foreach($service_orders as $row2){

									echo "<tr>";
									echo "<td>".$ctr."</td>";
									echo "<td><center>".$row2->x_id."</center></td>";
									echo "<td><center>".$row2->x_control_number."</center></td>";
									echo "<td>".$row2->company."</td>";
									echo "<td>".$row2->client."</td>";
									echo "<td>".$row2->type."</td>";
									echo "<td>".date('Y-m-d',strtotime($row2->date_needed))."</td>";
									$SO_details = $this->Operation_model->getServiceOrderDetail($row2->type,$row2->x_id);
									if($row2->type=="Trucking"){
										$qty = $SO_details->drop_off_quantity_1 + $SO_details->drop_off_quantity_2 + $SO_details->drop_off_quantity_3 + $SO_details->drop_off_quantity_4;
									}else{
										$qty = $SO_details->quantity;	
									}
									echo "<td align='right'>".number_format($qty,2,'.',',')." ".$SO_details->unit."</td>";
									echo "<td>".$row2->x_status."</td>";
									echo "</tr>";
									$ctr++;
									$so_qty = $so_qty + $qty;
								}

								echo "<tr align='right'><td colspan='7'></td><td>".number_format($so_qty,2,'.',',')."</td><td></td></tr>";

							}else{
								echo "<tr><td colspan='9'><center>No Records Found.</center></td></tr>";
							}

						?>
							</tbody>
						</table>
					</div>
				</div>
				</a>
			</div>

			<div class="accordion panel-default" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading panel-info" role="tab" id="heading3" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Out-turn Summary</h4>
				<br>
				<div class='progress'>
					<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="70"
					  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $out_turn_summary_percentage;?>%">
					    <center><?php echo $out_turn_summary_percentage;?>% completed</center>
					  </div>
				</div>
				 <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
					<div class="panel-body" style="overflow: auto">
						<table class='table table-striped table-bordered'>
							<thead>
								<th>#</th>
								<th><center>Transaction Code No.</center></th>
								<th><center>Control No.</center></th>
								<th>Company</th>
								<th>Client</th>
								<?php 
									if($SC['type']!="Handling" && $SC['type']!="Trucking"){
										echo "<th>Vessel</th>";
									}
								?>
								<th>Service Type</th>
								<th>Date Created</th>
								<th>Quantity</th>
								<th>Status</th>
							</thead>
							<tbody>
						<?php 
							$ctr = 1;
							$os_qty=0;
							$os_wt=0;
							if(isset($out_turn_summary)){
								foreach($out_turn_summary as $row3){
									$deliveries = $this->Operation_model->getOutTurnSummaryDeliveries($row3->x_id,TRUE);
									
									echo "<tr>";
									echo "<td>".$ctr."</td>";
									echo "<td><center>".$row3->x_id."</center></td>";
									echo "<td><center>".$row3->x_control_number."</center></td>";
									echo "<td>".$row3->company."</td>";
									echo "<td>".$row3->client."</td>";

									if($row3->type_of_service!="Handling" && $row3->type_of_service!="Trucking"){
										$os_detail = $this->Operation_model->getOutTurnSummaryDetails($row3->x_id);
										$vessel = $this->Abas->getVessel($os_detail->vessel_id);
										echo "<td>".$vessel->name."</td>";
									}

									echo "<td>".$row3->type_of_service."</td>";
									echo "<td>".date('Y-m-d',strtotime($row3->created_on))."</td>";

									if($row3->type_of_service=="Handling" || $row3->type_of_service=="Trucking"){
										echo "<td align='right'>".number_format($deliveries[0]['quantity'],4,'.',',')."</td>";
										$os_qty=$os_qty+$deliveries[0]['quantity'];
									}else{
										echo "<td align='right'>".number_format($deliveries[0]['good_number_of_bags'],4,'.',',')."</td>";
										$os_qty=$os_qty+$deliveries[0]['good_number_of_bags'];
									}
									
									echo "<td>".$row3->status."</td>";
									echo "</tr>";
									$ctr++;

								}
								if($SC['type']!="Handling" && $SC['type']!="Trucking"){
										echo "<tr align='right'><td colspan='8'></td><td>".number_format($os_qty,4,'.',',')."</td><td></td></tr>";
								}else{
									echo "<tr align='right'><td colspan='7'></td><td>".number_format($os_qty,4,'.',',')."</td><td></td></tr>";
								}
							}else{
								echo "<tr><td colspan='9'><center>No Records Found.</center></td></tr>";
							}
						?>
							</tbody>
						</table>
					</div>
				</div>
				</a>
			</div>
			
			

			<div class="accordion panel-default" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading panel-info" role="tab" id="heading4" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="true" aria-controls="collapse4">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Billing</h4>
				<br>
				<div class='progress'>
					<div class="progress-bar progress-bar-default" role="progressbar" aria-valuenow="70"
					  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $billing_percentage;?>%">
					    <center><?php echo $billing_percentage;?>% completed</center>
					  </div>
				</div>
				 <div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
					<div class="panel-body" style="overflow: auto">
						<table class='table table-striped table-bordered'>
							<thead>
								<th>#</th>
								<th><center>Transaction Code No.</center></th>
								<th><center>Control No.</center></th>
								<th>Company</th>
								<th>Client</th>
								<th>SOA Ref. No.</th>
								<th>Service Type</th>
								<th>Date Received by Client</th>
								<th>Amount</th>
								<th>Status</th>
							</thead>
							<tbody>
						<?php 
							$ctr = 1;
							$billing_amount = 0;
							if($billing){
								foreach($billing as $row4){
									echo "<tr>";
									echo "<td>".$ctr."</td>";
									echo "<td><center>".$row4->id."</center></td>";
									echo "<td><center>".$row4->control_number."</center></td>";
									echo "<td>".$row4->company."</td>";
									echo "<td>".$row4->client."</td>";
									echo "<td>".$row4->reference_number."</td>";
									echo "<td>".$row4->services."</td>";

									if($row4->sent_to_client_on){
										$recieved_by_client = date('Y-m-d',strtotime($row4->sent_to_client_on));
									}else{
										$recieved_by_client = "--";
									}

									$soa_amount = $this->Billing_model->getSOAAmount($row4->type,$row4->id);

									echo "<td>".$recieved_by_client."</td>";
									echo "<td align='right'>".number_format($soa_amount['grandtotal_tax'],2,'.',',')."</td>";
									echo "<td>".$row4->status."</td>";
									echo "</tr>";
									$ctr++;
									$billing_amount = $billing_amount +$soa_amount['grandtotal_tax'];
								}
								echo "<tr align='right'><td colspan='8'></td><td>".number_format($billing_amount,2,'.',',')."</td><td></td></tr>";
							}else{
								echo "<tr><td colspan='10'><center>No Records Found.</center></td></tr>";
							}
						?>
							</tbody>
						</table>
					</div>
				</div>
				</a>
			</div>

			<div class="accordion panel-default" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading panel-info" role="tab" id="heading5" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="true" aria-controls="collapse4">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Collection</h4>
				<br>
				<div class='progress'>
					<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="70"
					  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $collection_percentage;?>%">
					    <center><?php echo $collection_percentage;?>% completed</center>
					  </div>
				</div>
				 <div id="collapse5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading5">
					<div class="panel-body" style="overflow: auto">
						<table class='table table-striped table-bordered'>
							<thead>
								<th>#</th>
								<th><center>Transaction Code No.</center></th>
								<th><center>Control No.</center></th>
								<th>Company</th>
								<th>Payor</th>
								<th>SOA Ref. No.</th>
								<th>Date Paid</th>
								<th>Mode of Collection</th>
								<th>Amount</th>
								<th>Status</th>
							</thead>
							<tbody>
						<?php 
							$ctr = 1;
							$collection_amount=0;
							if($collection){
								foreach($collection as $row5){
									echo "<tr>";
									echo "<td>".$ctr."</td>";
									echo "<td><center>".$row5->x_id."</center></td>";
									echo "<td><center>".$row5->control_number."</center></td>";
									echo "<td>".$row5->company."</td>";
									echo "<td>".$row5->payor."</td>";
									echo "<td>".$row5->reference_number."</td>";
									echo "<td>".date('Y-m-d',strtotime($row5->received_on))."</td>";
									echo "<td>".$row5->mode_of_collection."</td>";
									echo "<td align='right'>".number_format($row5->net_amount,2,'.',',')."</td>";
									echo "<td>".$row5->x_status."</td>";
									echo "</tr>";
									$ctr++;
									$collection_amount=$collection_amount+$row5->net_amount;
								}
								echo "<tr align='right'><td colspan='8'></td><td>".number_format($collection_amount,2,'.',',')."</td><td></td></tr>";
							}else{
								echo "<tr><td colspan='10'><center>No Records Found.</center></td></tr>";
							}
						?>
							</tbody>
						</table>
					</div>
				</div>
				</a>
			</div>

			<div class="accordion panel-default" id="accordion" role="tablist" aria-multiselectable="true">
				<a class="panel-heading panel-info" role="tab" id="heading0" data-toggle="collapse" data-parent="#accordion" href="#collapse0" aria-expanded="true" aria-controls="collapse0">
				    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Request for Payment</h4>
				<br>
				<div class='progress'>
					<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="70"
					  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $request_payments_percentage;?>%">
					    <center><?php echo $request_payments_percentage;?>% completed</center>
					  </div>
				</div>

				 <div id="collapse0" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading0">
					<div class="panel-body" style="overflow: auto">
						<table class='table table-striped table-bordered'>
							<thead>
								<th>#</th>
								<th><center>Transaction Code No.</center></th>
								<th><center>Control No.</center></th>
								<th>Company</th>
								<th>Payee Type</th>
								<th>Payee Name</th>
								<th>Date Requested</th>
								<th>Purpose</th>
								<th>Amount</th>
								<th>Status</th>
							</thead>
							<tbody>
						<?php 
							$ctr = 1;
							$rfp_amount=0;
							if($request_for_payments){
								foreach($request_for_payments as $row0){
									echo "<tr>";
									echo "<td>".$ctr."</td>";
									echo "<td><center>".$row0->id."</center></td>";
									echo "<td><center>".$row0->control_number."</center></td>";
									echo "<td>".$row0->company."</td>";
									echo "<td>".$row0->payee_type."</td>";
									echo "<td>".$row0->payee_name."</td>";	
									echo "<td>".date('Y-m-d',strtotime($row0->request_date))."</td>";
									echo "<td>".$row0->purpose."</td>";
									echo "<td>".number_format($row0->amount,2,'.',',')."</td>";
									echo "<td>".$row0->status."</td>";
									echo "</tr>";

									$ctr++;
									$rfp_amount= $rfp_amount + $row0->amount;
								}
								
								echo "<tr align='right'><td colspan='8'></td><td>".number_format($rfp_amount,2,'.',',')."</td><td></td></tr>";
							}else{
								echo "<tr><td colspan='10'><center>No Records Found.</center></td></tr>";
							}
						?>
							</tbody>
						</table>
					</div>
				</div>
				</a>
			</div>

		</div>
	</div>

</body>
</html>
<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>

<script>


 $(this).removeClass("ui-widget-content");

	function submitContract(id){

	bootbox.confirm({
   					size: "small",
   					title: "Contract",
				    message: "Are you sure you want to submit this Contract?",
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
				    		window.location.href = "../../service_contract/submit/" + id;
				    	}
				    }
				});
	}

	function approveContract(id){

	bootbox.confirm({
   					size: "small",
   					title: "Contract",
				    message: "Are you sure you want to approve this Contract?",
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
				    		window.location.href = "../../service_contract/approve/" + id;
				    	}
				    }
				});
	}


	function voidContract(id){

	bootbox.prompt({
   					size: "medium",
				    title: "Are you sure you want to void this Contract? (Please provide reason below.)",
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

				    		window.location.href = "../../service_contract/void/" + id;

				    		$.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH;?>operation/setContractRemark/"+id,
							     data: {comments:result}
							  });
				    	}
				    }
				});
	}

</script>