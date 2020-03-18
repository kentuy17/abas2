<h2>Service Order</h2>
<div>
<?php

	
	if($SO->status=="Draft"){
		if($this->Abas->checkPermissions("operations|add_service_order",FALSE)){
			echo '<a href="#" onclick="submitSO('.$SO->id.');" class="btn btn-success exclude-pageload">Submit</a>';
			echo '<a href="'.HTTP_PATH.'operation/service_order/edit/'.$SO->id.'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit</a>';
		}
	}elseif($SO->status=="For Approval"){
		if($this->Abas->checkPermissions("operations|approve_service_order",FALSE)){
			echo '<a href="#" onclick="approveSO('.$SO->id.');" class="btn btn-success exclude-pageload">Approve</a>';
			echo '<a href="#" onclick="returnSO('.$SO->id.');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelSO('.$SO->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($SO->status=="Approved"){
		if($this->Abas->checkPermissions("operations|add_service_order",FALSE)){
			echo '<a href="'.HTTP_PATH.'operation/service_order/print/'.$SO->id.'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
			//echo '<a href="#" onclick="commenceSO('.$SO->id.');" class="btn btn-primary">Commence Service Order</a>';
		}
		if($this->Abas->checkPermissions("operations|approve_service_order",FALSE)){
			echo '<a href="#" onclick="returnSO('.$SO->id.');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelSO('.$SO->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}/*elseif($SO->status=="On-going"){
		if($this->Abas->checkPermissions("operations|add_service_order",FALSE)){
			//echo '<a href="#" onclick="completedSO('.$SO->id.');" class="btn btn-success">Mark as Completed</a>';
		}
	}*/

	echo '<a href="'.HTTP_PATH.'operation/service_order/listview" class="btn btn-dark force-pageload">Back</a>';
		
?>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			Transaction Code: <?php echo $SO->id; ?>
			| Control No. <?php echo $SO->control_number; ?>
			<span class="pull-right">Status: <?php echo $SO->status; ?></span>
		</h3>
	</div>
	<div class="panel-body">
		<h3 class="text-center"><?php echo $SO->company_name; ?></h3>
		<h4 class="text-center"><?php echo $SO->company_address; ?></h3>
		<h4 class="text-center"><?php echo $SO->company_contact; ?></h4>

		<?php 
			if($SO->comments!="" || $SO->comments!=NULL){
				echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    	<strong>Comments:</strong> '.$SO->comments.'
                  	  </div>';
			}
		?>
	
		<table class="table table-striped table-bordered">
			<tr>
				<td><b>Contract Reference No:</b></td>
				<td><?php 
					echo $SO->contract['reference_no'];
					
					if($SO->contract['parent_contract_id']!=0){
						$mother_contract = $this->Abas->getContract($SO->contract['parent_contract_id']);
						echo " | Mother Contract Ref. No. - " . $mother_contract['reference_no'];
					}

					 ?></td>
			</tr>
			<tr>
				<td><b>Client:</b></td>
				<td><?php echo $SO->contract['client']['company'] ?></td>
			</tr>
			<tr>
				<td><b>Service Order Type:</b></td>
				<td><?php echo $SO->type ?></td>
			</tr>
			<tr>
				<td><b>Service Order Date:</b></td>
				<td><?php echo date("j F Y", strtotime($SO->date_needed)) ?></td>
			</tr>
		</table>

		<table class="table table-striped table-bordered">
		
			<?php

			$detail="";

				if($SO->type=='Shipping'){
					$detail = "
						           <tr>
						           		<td><b>Servicing Vessel:</b></td>
						           		<td>".$SO_detail->vessel."</td>
						           </tr>
						           <tr>
						           		<td><b>Port of Loading:</b></td>
						           		<td>".$SO_detail->from_location."</td>
						           </tr>
						           <tr>
						           		<td><b>Port of Discharge:</b></td>
						           		<td>".$SO_detail->to_location."</td>
						           </tr>
						           <tr>
						           		<td><b>Cargo Description:</b></td>
						           		<td>".$SO_detail->cargo_description."</td>
						           </tr>
						           <tr>
						           		<td><b>Quantity/Volume:</b></td>
						           		<td>".$SO_detail->quantity." ".$SO_detail->unit."</td>
						           </tr>
									<tr>
						           		<td><b>Remarks:</b></td>
						           		<td>".$SO->remarks."</td>
						           </tr>
							   "; 
				}elseif($SO->type=='Lighterage'){
					$detail = "
						           <tr>
						           		<tr>
						           		<td><b>Servicing Vessel:</b></td>
						           		<td>".$SO_detail->vessel."</td>
						           </tr>
						           <tr>
						           		<tr>
						           		<td><b>Source/Vessel:</b></td>
						           		<td>".$SO_detail->source_vessel."</td>
						           </tr>
						           <tr>
						           		<td><b>Source Location:</b></td>
						           		<td>".$SO_detail->vessel_location."</td>
						           </tr>
						           <tr>
						           		<td><b>Discharge Location:</b></td>
						           		<td>".$SO_detail->discharge_location."</td>
						           </tr>
						           <tr>
						           		<td><b>Cargo Description:</b></td>
						           		<td>".$SO_detail->cargo_description."</td>
						           </tr>
						           <tr>
						           		<td><b>Quantity/Volume:</b></td>
						           		<td>".$SO_detail->quantity." ".$SO_detail->unit."</td>
						           </tr>
									<tr>
						           		<td><b>Remarks:</b></td>
						           		<td>".$SO->remarks."</td>
						           </tr>
							   "; 
				}elseif($SO->type=='Time Charter'){
					$detail = "
						           <tr>
						           		<tr>
						           		<td><b>Vessel:</b></td>
						           		<td>".$SO_detail->vessel."</td>
						           </tr>
						           <tr>
						           		<td><b>Start Location:</b></td>
						           		<td>".$SO_detail->start_location."</td>
						           </tr>
						           <tr>
						           		<td><b>Start Date-Time:</b></td>
						           		<td>".date("j F Y h:i:s A", strtotime($SO_detail->start_datetime))."</td>
						           </tr>
						           <tr>
						           		<td><b>End Location:</b></td>
						           		<td>".$SO_detail->end_location."</td>
						           </tr>
						           <tr>
						           		<td><b>End Date-Time:</b></td>
						           		<td>".date("j F Y h:i:s A", strtotime($SO_detail->end_datetime))."</td>
						           </tr>
						           <tr>
						           		<td><b>Cargo Description:</b></td>
						           		<td>".$SO_detail->cargo_description."</td>
						           </tr>
						           <tr>
						           		<td><b>Duration:</b></td>
						           		<td>".$SO_detail->quantity." ".$SO_detail->unit."</td>
						           </tr>
									<tr>
						           		<td><b>Remarks:</b></td>
						           		<td>".$SO->remarks."</td>
						           </tr>
							   "; 
				}elseif($SO->type=='Towing'){
					$detail = "
						           <tr>
						           		<tr>
						           		<td><b>Servicing Vessel:</b></td>
						           		<td>".$SO_detail->vessel."</td>
						           </tr>
						           <tr>
						           		<tr>
						           		<td><b>Craft Towed:</b></td>
						           		<td>".$SO_detail->craft_towed."</td>
						           </tr>
						           <tr>
						           		<td><b>From Location:</b></td>
						           		<td>".$SO_detail->from_location."</td>
						           </tr>
						           <tr>
						           		<td><b>To Location:</b></td>
						           		<td>".$SO_detail->to_location."</td>
						           </tr>
						           <tr>
						           		<td><b>Cargo Description:</b></td>
						           		<td>".$SO_detail->cargo_description."</td>
						           </tr>
						           <tr>
						           		<td><b>Duration:</b></td>
						           		<td>".$SO_detail->quantity." ".$SO_detail->unit."</td>
						           </tr>
									<tr>
						           		<td><b>Remarks:</b></td>
						           		<td>".$SO->remarks."</td>
						           </tr>
							   "; 
				}elseif($SO->type=='Trucking'){

					//$drop_off_points = explode(" | ",$SO_detail->to_location);

					if($SO_detail->drop_off_point_1!=""){
						$drop_off_1 = $SO_detail->drop_off_point_1." | ".$SO_detail->drop_off_quantity_1." ".$SO_detail->unit;
					}else{
						$drop_off_1 = "NA";
					}
					if($SO_detail->drop_off_point_2!=""){
						$drop_off_2 = $SO_detail->drop_off_point_2." | ".$SO_detail->drop_off_quantity_2." ".$SO_detail->unit;
					}else{
						$drop_off_2 = "NA";
					}
					if($SO_detail->drop_off_point_3!=""){
						$drop_off_3 = $SO_detail->drop_off_point_3." | ".$SO_detail->drop_off_quantity_3." ".$SO_detail->unit;
					}else{
						$drop_off_3 = "NA";
					}
					if($SO_detail->drop_off_point_4!=""){
						$drop_off_4 = $SO_detail->drop_off_point_4." | ".$SO_detail->drop_off_quantity_4." ".$SO_detail->unit;
					}else{
						$drop_off_4 = "NA";
					}
		

					$detail = " 
						           <tr>
						           		<td><b>Loading Point:</b></td>
						           		<td>".$SO_detail->from_location."</td>
						           </tr>
						           <tr>
						           		<td><b>Drop-off Point 1 | Quantity:</b></td>
						           		<td>".$drop_off_1."</td>
						           </tr>
						            <tr>
						           		<td><b>Drop-off Point 2 | Quantity:</b></td>
						           		<td>".$drop_off_2."</td>
						           </tr>
						           <tr>
						           		<td><b>Drop-off Point 3 | Quantity:</b></td>
						           		<td>".$drop_off_3."</td>
						           </tr>
						           <tr>
						           		<td><b>Drop-off Point 4 | Quantity:</b></td>
						           		<td>".$drop_off_4."</td>
						           </tr>
						           <tr>
						           		<td><b>Cargo Description:</b></td>
						           		<td>".$SO_detail->cargo_description."</td>
						           </tr>
						           <!--<tr>
						           		<td><b>Quantity/Weight/Duration:</b></td>
						           		<td>".$SO_detail->quantity." ".$SO_detail->unit."</td>
						           </tr>-->
									<tr>
						           		<td><b>Remarks:</b></td>
						           		<td>".$SO->remarks."</td>
						           </tr>
							   "; 
				}elseif($SO->type=='Handling'){
					$detail = "
						           <tr>
						           		<td><b>Cargo Description:</b></td>
						           		<td>".$SO_detail->cargo_description."</td>
						           </tr>
						           <tr>
						           		<td><b>Quantity/Weight:</b></td>
						           		<td>".$SO_detail->quantity." ".$SO_detail->unit."</td>
						           </tr>
									<tr>
						           		<td><b>Remarks:</b></td>
						           		<td>".$SO->remarks."</td>
						           </tr>
							   "; 
				}elseif($SO->type=='Storage'){
					$detail = "
						           <tr>
						           		<td><b>Cargo Description:</b></td>
						           		<td>".$SO_detail->cargo_description."</td>
						           </tr>
						           <tr>
						           		<td><b>Storage Location:</b></td>
						           		<td>".$SO_detail->storage_location."</td>
						           </tr>
						           <tr>
						           		<td><b>Quantity/Weight:</b></td>
						           		<td>".$SO_detail->quantity." ".$SO_detail->unit."</td>
						           </tr>
						           <tr>
						           		<td><b>Start Date:</b></td>
						           		<td>".date("j F Y", strtotime($SO_detail->start_date))."</td>
						           </tr>
						             <tr>
						           		<td><b>End Date:</b></td>
						           		<td>".date("j F Y", strtotime($SO_detail->end_date))."</td>
						           </tr>
									<tr>
						           		<td><b>Remarks:</b></td>
						           		<td>".$SO->remarks."</td>
						           </tr>
							   "; 
				}elseif($SO->type=='Equipment Rental'){
					$detail = "
						           <tr>
						           		<td><b>Equipment Name/Type:</b></td>
						           		<td>".$SO_detail->equipment_name."</td>
						           </tr>
						           <tr>
						           		<td><b>Description:</b></td>
						           		<td>".$SO_detail->description."</td>
						           </tr>
						           <tr>
						           		<td><b>Quantity:</b></td>
						           		<td>".$SO_detail->quantity." ".$SO_detail->unit."</td>
						           </tr>
						           <tr>
						           		<td><b>Start Date/Time:</b></td>
						           		<td>".date("j F Y", strtotime($SO_detail->start_date))."</td>
						           </tr>
						            <tr>
						           		<td><b>End Date/Time:</b></td>
						           		<td>".date("j F Y", strtotime($SO_detail->end_date))."</td>
						           </tr>
						           <tr>
						           		<td><b>From:</b></td>
						           		<td>".$SO_detail->from_location."</td>
						           </tr>
						           <tr>
						           		<td><b>To:</b></td>
						           		<td>".$SO_detail->to_location."</td>
						           </tr>
									<tr>
						           		<td><b>Remarks:</b></td>
						           		<td>".$SO->remarks."</td>
						           </tr>
							   "; 
				}

				echo $detail;
			?>
			
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($SO->created_on)); ?> by <?php echo $SO->full_name; ?></p>
		<?php if($SO->approved_on!=0) { ?>
			<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($SO->approved_on)); ?> by <?php echo $SO->approved_by_full_name; ?></p>
		<?php } ?>
	</div>

	
</div>

<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>

<script type="text/javascript" >
	
	function submitSO(id){

	bootbox.confirm({
   					size: "small",
   					title: "Service Order",
				    message: "Are you sure you want to submit this Service Order?",
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
				    		window.location.href = "../../service_order/submit/" + id;
				    	}
				    }
				});
	}

	function approveSO(id){

	bootbox.confirm({
   					size: "small",
   					title: "Service Order",
				    message: "Are you sure you want to approve this Service Order?",
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
				    		window.location.href = "../../service_order/approve/" + id;
				    	}
				    }
				});
	}

	function approveSO(id){

	bootbox.confirm({
   					size: "small",
   					title: "Service Order",
				    message: "Are you sure you want to approve this Service Order?",
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
				    		window.location.href = "../../service_order/approve/" + id;
				    	}
				    }
				});
	}

	function commenceSO(id){

	bootbox.confirm({
   					size: "small",
   					title: "Service Order",
				    message: "Are you sure you want to commence this Service Order?",
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
				    		window.location.href = "../../service_order/commence/" + id;
				    	}
				    }
				});
	}

	function completedSO(id){

	bootbox.confirm({
   					size: "small",
   					title: "Service Order",
				    message: "Are you sure you want to mark the Service Order as 'Completed'?",
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
				    		window.location.href = "../../service_order/completed/" + id;
				    	}
				    }
				});
	}

	function returnSO(id){

	bootbox.prompt({
   					size: "medium",
   					title: "Are you sure you want to return this Service Order to 'Draft' status? (Please provide reason below.)",
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
				    		window.location.href = "../../service_order/return/" + id;

				    		$.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH;?>operation/setServiceOrderComments/"+id,
							     data: {comments:result}
							  });
				    	}

				    }
				});
	}

	function cancelSO(id){

	bootbox.prompt({
   					size: "medium",
				    title: "Are you sure you want to cancel this Service Order? (Please provide reason below.)",
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
				    		window.location.href = "../../service_order/cancel/" + id;

				    		$.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH;?>operation/setServiceOrderComments/"+id,
							     data: {comments:result}
							  });
				    	}
				    }
				});
	}

</script>
