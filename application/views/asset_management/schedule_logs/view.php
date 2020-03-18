<h2><?php 
if($schedule_log['type']=="Vessel"){
	echo "Dry Dock Schedule";
}else{
	echo "Motorpool Repairs and Maintenance Schedule Logs";
}?>
</h2>
<div>
<?php

	if($schedule_log['status']=='Draft'){
		if(($this->Abas->checkPermissions("asset_management|add_vessel_schedule_log",FALSE) && $schedule_log['type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|add_truck_schedule_log",FALSE) && $schedule_log['type']=='Truck')){
			echo '<a href="#" onclick="submitScheduleLog('.$schedule_log['id'].');" class="btn btn-success exclude-pageload" target="">Submit</a>';

			echo '<a href="'.HTTP_PATH.CONTROLLER.'/edit/schedule_logs/'.$schedule_log['id'].'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Edit</a>';

			echo '<a href="#" onclick="cancelScheduleLog('.$schedule_log['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($schedule_log['status']=='For Verification'){
		if(($this->Abas->checkPermissions("asset_management|verify_vessel_schedule_log",FALSE) && $schedule_log['type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|verify_truck_schedule_log",FALSE) && $schedule_log['type']=='Truck')){
			echo '<a href="#" onclick="verifyScheduleLog('.$schedule_log['id'].');" class="btn btn-success exclude-pageload" target="">Verify</a>';
			echo '<a href="#" onclick="returnScheduleLog('.$schedule_log['id'].');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelScheduleLog('.$schedule_log['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($schedule_log['status']=='For Approval'){
		if(($this->Abas->checkPermissions("asset_management|approve_vessel_schedule_log",FALSE) && $schedule_log['type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|approve_truck_schedule_log",FALSE) && $schedule_log['type']=='Truck')){
			echo '<a href="#" onclick="approveScheduleLog('.$schedule_log['id'].');" class="btn btn-success exclude-pageload" target="">Approve</a>';
			echo '<a href="#" onclick="returnScheduleLog('.$schedule_log['id'].');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelScheduleLog('.$schedule_log['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($schedule_log['status']=='Approved'||$schedule_log['status']=='Final'){

		if(($this->Abas->checkPermissions("asset_management|add_vessel_schedule_log",FALSE) && $schedule_log['type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|add_truck_schedule_log",FALSE) && $schedule_log['type']=='Truck')){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/edit/schedule_logs/'.$schedule_log['id'].'" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Update</a>';
		}

		if(($this->Abas->checkPermissions("asset_management|approve_vessel_schedule_log",FALSE) && $schedule_log['type']=='Vessel') || ($this->Abas->checkPermissions("asset_management|approve_truck_schedule_log",FALSE) && $schedule_log['type']=='Truck')){
			echo '<a href="#" onclick="returnScheduleLog('.$schedule_log['id'].');" class="btn btn-warning exclude-pageload">Return</a>';
		}

			echo '<a href="'.HTTP_PATH.CONTROLLER.'/view_gantt_chart/'.$schedule_log['id'].'" class="btn btn-info" target="_blank">View Gantt Chart</a>';

			echo '<a href="'.HTTP_PATH.CONTROLLER.'/view_project_orders/'.$schedule_log['id'].'" class="btn btn-info" target="_blank">View PO/JO</a>';

	}
		echo '<a href="'.HTTP_PATH.CONTROLLER.'/listview/schedule_logs/'.$schedule_log['type'].'" class="btn btn-dark force-pageload">Back</a>';	
?>
</div>

<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"> 
				Transaction Code No. <?php echo $schedule_log['id'];?> | 
				Control No. <?php echo $schedule_log['control_number']; ?>
				<span class="pull-right">Status: <?php echo $schedule_log['status'];?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $schedule_log['company_name']; ?></h3>
		<h4 class="text-center"><?php echo $schedule_log['company_address']; ?></h3>
		<h4 class="text-center"><?php echo $schedule_log['company_contact']; ?></h4>
		
		<?php 
			if($schedule_log['type']=='Vessel'){
				$report_form = "Work Order No.:";
				$survey_form = "Ship Repairs and Maintenance Survey Form No.:";
				$asset = "Vessel Name:";
			}else{
				$report_form = "Truck Repairs and Maintenance Report Form No.:";
				$survey_form = "Motorpool Truck Diagnostic Evaluation No.:";
				$asset = "Truck Plate No.:";
			}
		?>

		<table class="table table-striped table-bordered">
			<tr><td>Project Reference No.:</td><td>
				<?php 
					if($schedule_log['reference_number']!=''){
						echo $schedule_log['reference_number'];
					}else{
						echo 'N/A';
					}
				?>
			</td></tr>
			<tr><td><?php echo $report_form;?></td><td><?php echo $schedule_log['report_form_no']?></td></tr>
			<tr><td><?php echo $survey_form;?></td><td><?php echo $schedule_log['evaluation_form_no']. " (TSCode No.".$schedule_log['evaluation_form_id'].")"?></td></tr>
			<tr><td>Bill of Materials No.:</td><td><?php echo $schedule_log['bill_of_materials_no']. " (TSCode No.".$schedule_log['bill_of_materials_id'].")"?></td></tr>
			<tr><td><?php echo $asset;?></td><td><?php echo $schedule_log['asset_name']?></td></tr>
			<?php
				if($schedule_log['type'] == 'Vessel'){
					echo "<tr><td>Dry-Docking Location:</td><td>".$schedule_log['dry_docking_location']."</td></tr>";
					echo "<tr><td>Dry-Docking Date:</td><td>".date('j F Y',strtotime($schedule_log['dry_docking_date']))."</td></tr>";
					echo "<tr><td>Start Date of Repair:</td><td>".date('j F Y',strtotime($schedule_log['start_date_of_repair']))."</td></tr>";
					

					$date_drydock = date_create($schedule_log['dry_docking_date']);
					$date_repair =  date_create($schedule_log['start_date_of_repair']);
					$date_trial =  date_create($schedule_log['trial_date']);
					$date_diff1 = date_diff($date_repair,$date_trial);
					$date_diff2 = date_diff($date_drydock,$date_trial);
					
					if($schedule_log['trial_date']<>0){
						echo "<tr><td>Sea-Trial Date:</td><td>".date('j F Y',strtotime($schedule_log['trial_date']))."</td></tr>";
						$total_days_repair = $date_diff1->format("%R%a days");
						$total_days_drydock = $date_diff2->format("%R%a days");
					}else{
						echo "<tr><td>Sea-Trial Date:</td><td>--</td></tr>";
						$total_days_repair = "--";
						$total_days_drydock = "--";
					}

					echo "<tr><td>Total Days of Repair:</td><td>".$total_days_repair."</td></tr>";
					echo "<tr><td>Total Days from Dry-dock:</td><td>".$total_days_drydock."</td></tr>";
				}else{
					echo "<tr><td>Make-Model:</td><td>".$schedule_log['make_model']."</td></tr>";
					echo "<tr><td>Engine No.:</td><td>".$schedule_log['engine_number']."</td></tr>";
					echo "<tr><td>Chassis No.:</td><td>".$schedule_log['chassis_number']."</td></tr>";
					echo "<tr><td>Start Date of Repair:</td><td>".date('j F Y',strtotime($schedule_log['start_date_of_repair']))."</td></tr>";
					if($schedule_log['trial_date']<>0){
						echo "<tr><td>Road-Trial Date:</td><td>".$schedule_log['trial_date']."</td></tr>";
					}else{
						echo "<tr><td>Road-Trial Date:</td><td>--</td></tr>";
					}
					
				}

			?>
			
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($schedule_log['created_on'])); ?> by <?php echo $schedule_log['created_by']; ?></p>
		<?php if($schedule_log['verified_by']!=NULL){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($schedule_log['verified_on'])); ?> by <?php echo $schedule_log['verified_by']; ?></p>
		<?php } ?>
		<?php if($schedule_log['approved_by']!=NULL){ ?>
		<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($schedule_log['approved_on'])); ?> by <?php echo $schedule_log['approved_by']; ?></p>
		<?php } ?>

		<?php 
			if(isset($schedule_log['updated_by'])){
				echo "Updated on " . date("h:i:s a j F Y", strtotime($schedule_log['updated_on']))." by ". $schedule_log['updated_by_full_name'];
			}
		?>

	</div>

</div>

<div class="panel panel-primary">
	<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body">

		<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
			<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
			    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Tasks List</h4>
			</a>
			 <div id="collapse1" class="panel-collapse" role="tabpanel" aria-labelledby="heading1">
				<div class="panel-body">
					<div  style="overflow-x: auto">
			            <table class="table table-striped table-bordered">
			            	<thead>
			            		<th>From BOM</th>
			            		<th>Task No.</th>
			            		<th>Scope of Work</th>
			            		<th>Estimated Time Completion</th>
			            		<th>Personnel In-charge</th>
			            		<th>Plan Start Date</th>
			            		<th>Plan End Date</th>
			            		<th>Actual Start Date</th>
	            				<th>Actual End Date</th>
	            				<th>Actual Work Duration</th>
	            				<th>Percentage</th>
	            				<th>Remarks</th>
			            	</thead>
			            	<tbody>
			            		<?php
			            			$ctr =0;
			            			$overall_percentage =0;
			            			foreach($schedule_log_tasks as $row){
			            				echo "<tr>";
			            					echo "<td>BOM TSCode ".$row['bill_of_materials_id']."</td>";
			            					echo "<td>".$row['task_number']."</td>";
			            					echo "<td>".$row['scope_of_work']."</td>";
			            					echo "<td>".$row['estimated_time_to_complete']." Day(s)</td>";
			            					echo "<td>".$row['personnel_in_charge']."</td>";
			            					echo "<td>".$row['plan_start_date']."</td>";
			            					$plan_end_date = date('Y-m-d',strtotime($row['plan_start_date'].' + '.$row['estimated_time_to_complete'].' days'));

			       							if($row['actual_work_duration']<>0){
			       								$actual_start_date = $row['actual_start_date'];
			       								$actual_end_date = date('Y-m-d',strtotime($row['actual_start_date'].' + '.$row['actual_work_duration'].' days'));
			       								$actual_work_duration = $row['actual_work_duration'] . " Day(s)";
			       								$percentage = $row['percentage'] . "%";
			       								$remarks = $row['remarks'];
			       							}else{
			       								$actual_start_date  = "--";
			       								$actual_end_date = "--";
			       								$actual_work_duration = "--";
			       								$percentage = "--";
			       								$remarks = "--";
			       							}
			            					echo "<td>".$plan_end_date."</td>";
		            						echo "<td>".$actual_start_date."</td>";
		            						echo "<td>".$actual_end_date."</td>";
		            						echo "<td>".$actual_work_duration."</td>";
		            						echo "<td>".$percentage."</td>";
		            						echo "<td>".$remarks."</td>";
			            				echo "</tr>";
			            				$overall_percentage = (double)$overall_percentage + (double)$percentage;
			            				$ctr++;
			            			}
			            			echo "<tr><td colspan='10' style='text-align:right'><b>Overall Percentage:</b></td><td>".number_format(($overall_percentage/$ctr),2,'.','')."%</td></tr>";
			            		?>
			            	</tbody>
			            </table>
		        	</div>
		        </div>
		     </div>

		</div>
	</div>
</div>
<script>

function cancelScheduleLog(id){

    	bootbox.confirm({
       					size: "small",
       					 title: "Cancel Schedule Log",
					    message: "Are you sure you want to cancel this Schedule Log? (This cannot be undone)",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {

					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>Asset_Management/cancel/schedule_logs/" + id;
					    	}
				
					    }
					});
    }

function submitScheduleLog(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Submit Schedule Log",
					    message: "Are you sure you want to submit this Schedule Log?",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>Asset_Management/submit/schedule_logs/" + id;
					    	}
				
					    }
					});
    }
function verifyScheduleLog(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Verify Schedule Log",
					    message: "Are you sure you want to verify this Schedule Log?",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>Asset_Management/verify/schedule_logs/" + id;
					    	}
				
					    }
					});
    }
 function approveScheduleLog(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Approve Schedule Log",
					    message: "Are you sure you want to approve this Schedule Log?",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>Asset_Management/approve/schedule_logs/" + id;
					    	}
				
					    }
					});
    }
 function returnScheduleLog(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Return Schedule Log",
					    message: "Are you sure you want to return this Schedule Log to 'Draft'?",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>Asset_Management/returnToDraft/schedule_logs/" + id;
					    	}
				
					    }
					});
    }
</script>