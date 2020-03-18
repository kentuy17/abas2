
<h2>Bill Of Materials for <?php if($BOM['bom_type']=="Vessel"){echo "Vessels";}else{echo "Trucks";}?></h2>
<div>
<?php

$grand_total_amount = 0;

	if($BOM['status']=='Draft'){
		if(($this->Abas->checkPermissions("asset_management|add_vessel_bill_of_materials",FALSE) && $BOM['bom_type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|add_truck_bill_of_materials",FALSE) && $BOM['bom_type']=='Truck')){
		echo '<a href="'.HTTP_PATH.CONTROLLER.'/submit/BOM/'.$BOM['id'].'" class="btn btn-success exclude-pageload" target="">Submit</a>';

		echo '<a href="'.HTTP_PATH.CONTROLLER.'/edit/BOM/'.$BOM['id'].'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit</a>';

		echo '<a href="#" onclick="cancelBOM('.$BOM['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($BOM['status']=='For Verification'){
		if(($this->Abas->checkPermissions("asset_management|verify_vessel_bill_of_materials",FALSE) && $BOM['bom_type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|verify_truck_bill_of_materials",FALSE) && $BOM['bom_type']=='Truck')){
			echo '<a href="#" onclick="verifyBOM('.$BOM['id'].');" class="btn btn-success exclude-pageload">Verify</a>';
			echo '<a href="#" onclick="cancelBOM('.$BOM['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}
	elseif($BOM['status']=='For Approval'){
		if(($this->Abas->checkPermissions("asset_management|approve_vessel_bill_of_materials",FALSE) && $BOM['bom_type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|approve_truck_bill_of_materials",FALSE) && $BOM['bom_type']=='Truck')){
			echo '<a href="#" onclick="approveBOM('.$BOM['id'].');" class="btn btn-success exclude-pageload">Approve</a>';
			echo '<a href="#" onclick="cancelBOM('.$BOM['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($BOM['status']=='Final' || $BOM['status']=='Approved'){

		if(($this->Abas->checkPermissions("asset_management|add_vessel_bill_of_materials",FALSE) && $BOM['bom_type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|add_truck_bill_of_materials",FALSE) && $BOM['bom_type']=='Truck')){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/prints/BOM_Details/'.$BOM['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print BOM Details</a>';
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/prints/BOM_Summary/'.$BOM['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print BOM Summary</a>';
		}
	}
	if(!empty($schedule_log)){
		echo '<a href="'.HTTP_PATH.CONTROLLER.'/view_gantt_chart/'.$schedule_log[0]->id.'" class="btn btn-info" target="_blank">View Gantt Chart</a>';
	}

	if($BOM['bom_type']=="Vessel"){
		echo '<a href="'.HTTP_PATH.CONTROLLER.'/listview/BOM/Vessel" class="btn btn-dark force-pageload">Back</a>';
	}elseif($BOM['bom_type']=="Truck"){
		echo '<a href="'.HTTP_PATH.CONTROLLER.'/listview/BOM/Truck" class="btn btn-dark force-pageload">Back</a>';
	}
		
?>
	
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"> 
				Transaction Code No. <?php echo $BOM['id'];?> | 
				Control No. <?php echo $BOM['control_number']; ?>
				<span class="pull-right">Status: <?php echo $BOM['status'];?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $BOM['company_name']; ?></h3>
		<h4 class="text-center"><?php echo $BOM['company_address']; ?></h3>
		<h4 class="text-center"><?php echo $BOM['company_contact']; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Reference Survey/Evaluation Form No.:</th>
				<td><?php 

					if($BOM['bom_type']=="Vessel"){	

						if($BOM['WO_number']==0){
							$work_order_no="";
						}else{
							$work_order_no="WO No. ".$BOM['WO_number'];
						}

						echo "<b>  SRMSF No." . $BOM['evaluation_form_no'] . " <br>" . $work_order_no . "</b><br><br>"; 
					
				    ?>
				   	 <table class="table table-striped table-bordered">
				    	<tbody>
				    		<tr>
				    			<th>Vessel Name</th>
				    			<th>Dry-Docking Date</th>
				    			<th colspan="2">Dry-Docking Location</th>
				    		</tr>
				    		<tr>
				    			<td><?php echo $BOM['vessel_name'];?></td>
				    			<td><?php echo date("j F Y",strtotime($BOM['dry_docking_date']));?></td>
				    			<td colspan="2"><?php echo $BOM['dry_docking_location'];?></td>
				    		</tr>
				    		<tr>
				    			<th>LOA</th>
				    			<th>Breadth</th>
				    			<th>Depth</th>
				    			<th>GT</th>
				    		</tr>
				    		<tr>
				    			<td><?php echo $BOM['length_loa'];?></td>
				    			<td><?php echo $BOM['breadth'];?></td>
				    			<td><?php echo $BOM['depth'];?></td>
				    			<td><?php echo $BOM['gross_tonnage'];?></td>
				    		</tr>
				    	</tbody>
					<?php	
					}elseif($BOM['bom_type']=="Truck"){


						if($BOM['TRMRF_number']==0){
							$report_form="";
						}else{
							$report_form="TRMRF No. ".$BOM['TRMRF_number'];
						}

						echo "<b>MTDE No." . $BOM['evaluation_form_no'] . " <br>" . $report_form  . "</b><br><br>"; 
					?>	 <table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<th>Truck Plate-No.</th>
									<th>Make</th>
									<th>Model</th>
								</tr>
								<tr>
									<td><?php echo $BOM['plate_number'];?></td>
									<td><?php echo $BOM['make'];?></td>
									<td><?php echo $BOM['model'];?></td>
								</tr>
								<tr>
									<th>Engine No.</th>
									<th>Chassis No.</th>
									<th>Type</th>
								</tr>
								<tr>
									<td><?php echo $BOM['engine_number'];?></td>
									<td><?php echo $BOM['chassis_number'];?></td>
									<td><?php echo $BOM['type'];?></td>
								</tr>
							</tbody>
					<?php 
						}
					?>
				    </table>
			    </td>
			</tr>
			<tr>
				<th>Start Date of Repair:</th>
				<td><?php echo  date("j F Y",strtotime($BOM['start_date_of_repair'])); ?></td>
			</tr>
			<tr>
				<th>Remarks:</th>
				<td><?php echo $BOM['remarks']; ?></td>
			</tr>
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($BOM['created_on'])); ?> by <?php echo $BOM['created_by']; ?></p>
		<?php if($BOM['verified_by']!=NULL){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($BOM['verified_on'])); ?> by <?php echo $BOM['verified_by']; ?></p>
		<?php } ?>
		<?php if($BOM['approved_by']!=NULL){ ?>
		<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($BOM['approved_on'])); ?> by <?php echo $BOM['approved_by']; ?></p>
		<?php } ?>

	</div>

</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body">

		<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true"><!--start accordion-->
			
			<div class="panel panel-info">

			 <?php
			 	$tables = array("Tasks Description","Labor (Dry-docking)","Labor (Afloat Repairs)","Materials and Supplies","Tools and Equipment");
			 	$ctr = 1;

			 	foreach($tables as $table){
			 ?>
			 	<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $ctr;?>" aria-expanded="true" aria-controls="collapse<?php echo $ctr;?>">
			    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">
			    <?php echo $table;?></h4>
			    </a>

			            <div id="collapse<?php echo $ctr;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $ctr;?>">

			              	<div class="panel-body">

			              		<table class="table table-striped table-bordered">

			              		<?php if($table=="Tasks Description"){
			              		?>
									<thead>
				                        <tr>
				                          <th>Task No.</th>
				                          <th>Scope of Work</th>
				                          <th>Total Area (sqm.)</th>
				                          <th>Estimated Time Completion (No. of Days)</th>
				                        </tr>
				                    </thead>

								<?php 
								}elseif($table=="Labor (Dry-docking)"){
			              		?>

			              			<thead>
				                        <tr>
				                          <th>Quantity</th>
				                          <th>Job Description</th>
				                          <th>Task No(s).</th>
				                          <th>Days Needed</th>
				                          <th>Rate per Day</th>
				                          <th>Total Cost</th>
				                        </tr>
				                    </thead>

			              		<?php 
								}elseif($table=="Labor (Afloat Repairs)"){
			              		?>

			              			<thead>
				                        <tr>
				                          <th>Quantity</th>
				                          <th>Job Description</th>
				                          <th>Task No(s).</th>
				                          <th>Days Needed</th>
				                          <th>Rate per Day</th>
				                          <th>Total Cost</th>
				                        </tr>
				                    </thead>

			              		<?php 
								}elseif($table=="Materials and Supplies"){
			              		?>
			              			<thead>
				                        <tr>
				                          <th>Quantity Needed</th>
				                          <th>Item Code</th>
				                          <th>Item Name & Description</th>
				                          <th>Size</th>
				                          <th>Unit Measurement</th>
				                          <th>Warehouse Qty</th>
				                          <th>Warehouse UC</th>
				                          <th>For Purchase Qty</th>
				                          <th>For Purchase UC</th>
				                          <th>Total Cost</th>
				                        </tr>
				                    </thead>

			              		<?php 
								}elseif($table=="Tools and Equipment"){
			              		?>
			              			<thead>
				                        <tr>
				                          <th>Quantity</th>
				                          <th>Tool Name</th>
				                          <th>Estimated Days Used</th>
				                        </tr>
				                    </thead>
								
								<?php
								}
								?>

								<?php if($table=="Tasks Description"){
			              		?>
									<tbody>
				                      <?php 
				                      	$total_no_of_days = 0;
				                        foreach($BOM_tasks as $item){
				                        $total_no_of_days = $total_no_of_days +	$item->estimated_time_to_complete;
				                      ?>
				                      		<tr>
					                          <th>
					                       		<?php echo $item->task_number;?>
					                          </th>
					                          <td>
					                          	<?php echo $item->scope_of_work;?>
					                          </td>
					                          <td>
					                          	<?php echo $item->total_area;?>
					                          </td>
					                          <td>
					                          	<?php echo $item->estimated_time_to_complete;?>
					                          </td>
											</tr>					
				                      <?php
				                        }
				                      ?>
				                     
									</tbody>

								<?php 
								}elseif($table=="Labor (Dry-docking)"){
			              		?>

			              			<tbody>
				                      <?php 
				                      	$total_cost=0;
				                      	$grand_total=0;

				                      	foreach($BOM_labor as $item){
			                      			$is_drydocking = $this->Abas->like_match("%dry%",$item->job_description);
				                        	if($is_drydocking!== false){
					                        	$total_cost = $item->days_needed*($item->quantity*$item->rate_per_day);
					                        	$grand_total = $grand_total + $total_cost;
				                      ?>
				                      		<tr>
				                      		  <th>
					                          	<?php echo $item->quantity;?>
					                          </th>
					                          <td>
					                          	<?php echo $item->job_description;?>
					                          </td>
					                          <td>
					                       		<?php echo $item->task_numbers;?>
					                          </td>
					                          <td>
					                          	<?php echo $item->days_needed;?>
					                          </td>
					                           <td>
					                          	<?php echo number_format($item->rate_per_day,2,'.',',');?>
					                          </td>
					                           <td>
					                          	<?php echo number_format($total_cost,2,'.',',');?>
					                          </td>
											</tr>					
				                      <?php
					                      	$grand_total_amount = $grand_total_amount + $total_cost;
					                        }
				                    	}
				                      ?>
				                      <tr>
				                      	<td colspan="5" style="text-align:right;font-weight:bold;font-size:14px">Total Amount</td>
				                      	<td style="font-weight:bold;font-size:14px"><?php echo "PHP".number_format($grand_total,2,'.',',');?></td>
				                      </tr>
									</tbody>

			              		<?php 
								}elseif($table=="Labor (Afloat Repairs)"){
			              		?>

			              			<tbody>
				                      <?php 
				                      	$total_cost=0;
				                      	$grand_total=0;

				                      	foreach($BOM_labor as $item){
			                      			$is_drydocking = $this->Abas->like_match("%dry%",$item->job_description);
				                        	if($is_drydocking!== true){
					                        	$total_cost = $item->days_needed*($item->quantity*$item->rate_per_day);
					                        	$grand_total = $grand_total + $total_cost;
				                      ?>
				                      		<tr>
				                      		  <th>
					                          	<?php echo $item->quantity;?>
					                          </th>
					                          <td>
					                          	<?php echo $item->job_description;?>
					                          </td>
					                          <td>
					                       		<?php echo $item->task_numbers;?>
					                          </td>
					                          <td>
					                          	<?php echo $item->days_needed;?>
					                          </td>
					                           <td>
					                          	<?php echo number_format($item->rate_per_day,2,'.',',');?>
					                          </td>
					                           <td>
					                          	<?php echo number_format($total_cost,2,'.',',');?>
					                          </td>
											</tr>					
				                      <?php
					                      	$grand_total_amount = $grand_total_amount + $total_cost;
					                        }
				                    	}
				                      ?>
				                      <tr>
				                      	<td colspan="5" style="text-align:right;font-weight:bold;font-size:14px">Total Amount</td>
				                      	<td style="font-weight:bold;font-size:14px"><?php echo "PHP".number_format($grand_total,2,'.',',');?></td>
				                      </tr>
									</tbody>

			              		<?php 
								}elseif($table=="Materials and Supplies"){
			              		?>
			              		    <tbody>
				                      <?php 
				                      	$total_cost=0;
				                      	$grand_total=0;
				                        foreach($BOM_supplies as $item){

				                        	$quantity_for_purchase = ($item->quantity)-($item->warehouse_quantity);
					
											if($quantity_for_purchase<=0){
												$total_cost = ($item->quantity) * ($item->warehouse_unit_cost);
												$purchase_quantity = "-";
												$purchase_unit_cost = "-";
											}else{
												$calc_wh_cost = ($item->warehouse_quantity) * ($item->warehouse_unit_cost);
												$calc_ps_cost = ($item->unit_cost) * $quantity_for_purchase;
												$total_cost =  $calc_wh_cost + $calc_ps_cost;
												$purchase_quantity = $quantity_for_purchase;
												$purchase_unit_cost = $item->unit_cost;
											}

				                        	$grand_total = $grand_total + $total_cost;

				                      ?>
				                      		<tr>
				                      		  <th>
					                          	<?php echo $item->quantity;?>
					                          </th>
					                          <td>
					                       		<?php echo $item->item_code;?>
					                          </td>
					                          <td>
					                       		<?php echo $item->item_description;?>
					                          </td>
					                           <td>
					                       		<?php echo $item->item_size;?>
					                          </td>
					                          <td>
					                       		<?php echo $item->item_unit;?>
					                          </td>  
					                          <td>
					                          	<?php echo $item->warehouse_quantity;?>
					                          </td>
					                          <td>
					                          	<?php echo $item->warehouse_unit_cost;?>
					                          </td>
					                           <td>
					                          	<?php echo $purchase_quantity;?>
					                          </td>
					                           <td>
					                          	<?php echo $purchase_unit_cost;?>
					                          </td>
					                           <td>
					                          	<?php echo number_format($total_cost,2,'.',',');?>
					                          </td>
											</tr>					
				                      <?php
				                      	$grand_total_amount = $grand_total_amount + $total_cost;
				                        }
				                      ?>
				                      <tr>
				                      	<td colspan="9" style="text-align:right;font-weight:bold;font-size:14px">Total Amount</td>
				                      	<td style="font-weight:bold;font-size:14px"><?php echo "PHP".number_format($grand_total,2,'.',',');?></td>
				                      </tr>
									</tbody>

			              		<?php 
								}elseif($table=="Tools and Equipment"){
			              		?>
			              			
			              			<tbody>
				                      <?php 
				                
				                        foreach($BOM_tools as $item){
				                      ?>
				                      		<tr>
				                      		  <th>
					                          	<?php echo $item->quantity;?>
					                          </th>
					                          <td>
					                       		<?php echo $item->tool_name;?>
					                          </td>
					                          <td>
					                          	<?php echo $item->days_used;?>
					                          </td>
											</tr>					
				                      <?php
				                        }
				                      ?>         
									</tbody>
								
								<?php
								}
								?>
				                    
			              		</table>
			              	</div>
			            </div>
			 <?php
			 	$ctr++;
			 	}
			 ?>

			</div>

			 <table class="table table-striped table-bordered">
				<tr>
					<td  style="width:700px">
						<h5 class="pull-right">Grand Total Amount:</h5>
					</td>
					<td>
						<h4 style="text-align:center;font-weight:bold"><?php echo "PHP". number_format($grand_total_amount,2,'.',',');?></h4>
					</td>
				<tr>
			</table>
				
        </div><!--end accordion-->

	</div>
</div>

<script>
function cancelBOM(id){

	//use &#39 for passing single qoute string parameter

    	bootbox.confirm({
       					size: "small",
					    title: "Cancel BOM",
					    message: "Are you sure you want to cancel this Bill Of Materials? (This cannot be undone)",
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
					    	if(result==true){
					    		window.location.href = "../../cancel/BOM/" + id;
					    	}
				
					    }
					});
    }
  function verifyBOM(id){

	//use &#39 for passing single qoute string parameter

    	bootbox.confirm({
       					size: "small",
					    title: "Verify BOM",
					    message: "Are you sure you want to verify this Bill Of Materials? (This cannot be undone)",
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
					    	if(result==true){
					    		window.location.href = "../../verify/BOM/" + id;
					    	}
				
					    }
					});
    }
    function approveBOM(id){

	//use &#39 for passing single qoute string parameter

    	bootbox.confirm({
       					size: "small",
					    title: "Approve BOM",
					    message: "Are you sure you want to approve this Bill Of Materials? (This cannot be undone)",
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
					    	if(result==true){
					    		window.location.href = "../../approve/BOM/" + id;
					    	}
				
					    }
					});
    }
</script>