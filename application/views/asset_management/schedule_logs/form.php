<?php

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(isset($schedule_log)){
			$company_options	.=	"<option ".($schedule_log['company_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}else{
			if($option->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
	}
	unset($option);
}


$bom_options = "<option value=''>Select</option>";
if(!empty($bill_of_materials)) {
	foreach($bill_of_materials as $optionx) {
		if(isset($schedule_log)){

			$bom_options	.=	"<option ".($schedule_log['bill_of_materials_id']==$optionx->id ? "selected":"")." value='".$optionx->id."'>BOM No.".$optionx->control_number . " (" . $optionx->asset_name.")</option>";

		}else{
			$bom_options	.=	"<option value='".$optionx->id."'>".$optionx->name."</option>";
		}
	}
	unset($optionx);
}

?>

<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>
					<?php 
						if(!isset($schedule_log)){
							if($for=="Vessel"){
								echo "Add Dry Dock Schedule";
								$report_form = "WO No.";
								$evaluation_form = "SRMSF No.";
							}else{
								echo "Add Motorpool Repairs and Maintenance Schedule Log";
								$report_form = "TRMRF No.";
								$evaluation_form = "MTDE No.";
							}
							$disabled = "";

							
							
							$report_form_no = "";
							$evaluation_form_no = "";
							$bill_of_materials_no = "";
							$start_date_of_repair = "";
							$bom_no = "";
							$asset_id = "";
							$reference_no = "";
							$trial = "Sea-Trial Date* (d-m-Y)";
							$trial_date = "";
		
						}else{
							if($schedule_log['type']=="Vessel"){
								echo "Edit Dry Dock Schedule";
								$report_form = "WO No.";
								$evaluation_form = "SRMSF No.";
								$trial = "Sea-Trial Date* (d-m-Y)";
							}else{
								echo "Edit Motorpool Repairs and Maintenance Schedule Log";
								$report_form = "TRMRF No.";
								$evaluation_form = "MTDE No.";
								$trial = "Road-Trial Date* (d-m-Y)";
							}

							$for = $schedule_log['type'];

							$disabled = "disabled";

							$report_form_no = $schedule_log['report_form_no'];
							$evaluation_form_no = $schedule_log['evaluation_form_no'];
							$bill_of_materials_no = $schedule_log['bill_of_materials_no'];
							$start_date_of_repair = $schedule_log['start_date_of_repair'];
							$reference_no = $schedule_log['reference_number'];

							$asset_id = $schedule_log['asset_id'];
							$trial_date = $schedule_log['trial_date'];

						}
					?>
				</text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>

<?php
	// CI Form 
	$attributes = array('id'=>'schedule_log_form','role'=>'form');
	if(!isset($schedule_log)){
		$action = '/add/schedule_logs';
	}else{
		$action = '/edit/schedule_logs/'.$schedule_log['id'];
	}
	echo form_open_multipart(HTTP_PATH.CONTROLLER.$action,$attributes);
	echo $this->Mmm->createCSRF();
?>

<div class='panel-body panel'>
	<div class='panel-group' id='SLFormDivider' role='tablist' aria-multiselectable='true'>
		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='general'>	
					<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#SLGeneral' aria-expanded='true' aria-controls='SLGeneral'>
					General Information
					<span class='glyphicon glyphicon-chevron-down pull-right'></span>
					</a>
			</div>

			<div id='SLGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='SLGeneral'>

				<div class='panel-body'>
					<input type='hidden' id='bom_type' name='bom_type' value='<?php echo $for;?>'>
					<input type='hidden' id='asset_id' name='asset_id' value='<?php echo $asset_id;?>'>
					<div class='col-md-12 col-sm-12 col-xs-12'>
						<label>Company*</label>
						<select type='text' id='company' name='company' class='form-control' <?php echo $disabled;?> required>
							<?php echo $company_options;?>
						</select>
					</div>
					<div class='col-md-4 col-sm-8 col-xs-12'>
						<label>Project Reference No.</label>
						<input type='text' id='reference_no' name='reference_no' class='form-control' value='<?php echo $reference_no;?>'>
					</div>
					<div class='col-md-6 col-sm-8 col-xs-12'>
						<label>Bill of Materials No.*</label>
						<select type='text' id='bom_no' name='bom_no' class='form-control' required>
							<?php echo $bom_options;?>
						</select>
					</div>
					<div class='col-md-1 col-sm-1 col-xs-4'>
						<br>
							<input type='button' value='Load' name='btnLoad' class='btn btn-success btn-s' onclick='javascript: loadTasksList()' data-toggle='tooltip'/>
					</div>
					<div class='col-md-1 col-sm-1 col-xs-1'>
						<br>
							<input type='button' value='Clear' name='btnClear' class='btn btn-danger btn-s' onclick='javascript: clearTasksList()'/>
					</div>
					<div class='col-md-4 col-sm-4 col-xs-12'>
						<label><?php echo $report_form;?></label>
						<input type='text' class='form-control' style='text-align:center' id='report_form_no' name='report_form_no' value='<?php echo $report_form_no;?>' readonly>
					</div>
					<div class='col-md-4 col-sm-4 col-xs-12'>
						<label><?php echo $evaluation_form;?></label>
						<input type='text' class='form-control' style='text-align:center' id='evaluation_form_no' name='evaluation_form_no' value='<?php echo $evaluation_form_no;?>' readonly>
					</div>
					<div class='col-md-4 col-sm-4 col-xs-12'>
						<label>Start Date of Repair (d-m-Y)</label>
						<input type='date' class='form-control' style='text-align:center' id='start_date_of_repair' name='start_date_of_repair' value='<?php echo $start_date_of_repair;?>' readonly>
					</div>
					<?php if(isset($schedule_log)){ ?>
						<div class='col-md-4 col-sm-4 col-xs-12'>
							<label><?php echo $trial;?></label>
							<input type='date' class='form-control' style='text-align:center' id='trial_date' name='trial_date' value='<?php echo $trial_date;?>'>
						</div>
					<?php } ?>
					<div class='col-md-12 col-sm-12 col-xs-12'>
						<label>Asset For Repair:</label>
							<table id='for_repair_table' class='table table-striped'>
								<?php 
									if(!isset($schedule_log)){
										echo "<tbody>
												<tr>
													<td>...</td>
												</tr>
											</tbody>";
									}elseif(isset($schedule_log)){
										if(isset($vessel)){
											echo "<tbody>
												<tr>
													<th>Vessel Name</th>
													<th>Dry-Docking Date</th>
													<th>Dry-Docking Location</th>
													<th>LOA</th>
													<th>Breadth</th>
													<th>Depth</th>
													<th>GT</th>
												</tr>";
											echo "<tr>
													<td>".$schedule_log['asset_name']."</td>
													<td>".$vessel['dry_docking_date']."</td>
													<td>".$vessel['dry_docking_location']."</td>
													<td>".$vessel_measurement[0]->length_loa."</td>
													<td>".$vessel_measurement[0]->breadth."</td>
													<td>".$vessel_measurement[0]->depth."</td>
													<td>".$vessel_measurement[0]->gross_tonnage."</td>
												</tr>
												</tbody>";
										}elseif(isset($truck)){
											echo "<tbody>
												<tr>
													<th>Truck Plate-No.</th>
													<th>Make</th>
													<th>Model</th>
													<th>Engine No.</th>
													<th>Chasis No.</th>
													<th>Type</th>
												</tr>";
											echo "<tr>
													<td>".$truck[0]->plate_number."</td>
													<td>".$truck[0]->make."</td>
													<td>".$truck[0]->model."</td>
													<td>".$truck[0]->engine_number."</td>
													<td>".$truck[0]->chassis_number."</td>
													<td>".$truck[0]->type."</td>
												</tr>
												</tbody>";
										}

									} 

								?>
							</table>		
					</div>
				</div>

			</div>
			
		</div>

		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='general'>	
				<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#SLTasklist' aria-expanded='true' aria-controls='SLTasklist'>
				Tasks List
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>

			<div id='SLTasklist' class='panel-collapse collapse' role='tabpanel' aria-labelledby='SLTasklist'>
				<div class='panel-body' >
					<div style="overflow-x: scroll">
						<table data-toggle="table" id="table_tasks_list" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th style="min-width: 100px;">From BOM</th>
									<th style="min-width: 50px;">Task No.</th>
									<th style="min-width: 500px;">Scope of Work</th>
									<th style="min-width: 80px;">Estimated Time Completion</th>
									<th style="min-width: 200px;">Personnel In-charge*</th>
									<th style="min-width: 100px;">Plan Start Date</th>
									<th style="min-width: 100px;">Actual Start Date*</th>
									<th style="min-width: 80px;">Actual Work Duration (Days)*</th>
									<th style="min-width: 80px;">Percentage*</th>
									<th style="min-width: 500px;">Remarks</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if(isset($schedule_log_tasks)){
										foreach($schedule_log_tasks as $row){

					            				echo "<tr>";
					            					echo "<td><input type='hidden' id='bom_id[]' name='bom_id[]' value='".$row['bill_of_materials_id']."'>BOM TSCode ".$row['bill_of_materials_id']."</td>";
					            					echo "<td><input type='hidden' id='task_id[]' name='task_id[]' value='".$row['task_id']."'>".$row['task_number']."</td>";
					            					echo "<td>".$row['scope_of_work']."</td>";
					            					echo "<td width='20%'>".$row['estimated_time_to_complete']." day(s)</td>";
					            					echo "<td><input type='text' id='personnel_in_charge[]' name='personnel_in_charge[]' class='form-control tool_name' value='".$row['personnel_in_charge']."'></td>";
					            					echo "<td><input type='date' id='plan_start_date[]' name='plan_start_date[]' class='form-control' value='".$row['plan_start_date']."'></td>";
					            					echo "<td><input type='date' id='actual_start_date[]' name='actual_start_date[]' class='form-control' value='".$row['actual_start_date']."'  required></td>";
					            					echo "<td><input type='number' id='actual_work_duration[]' name='actual_work_duration[]' class='form-control' value='".$row['actual_work_duration']."' required></td>";
					            					echo "<td><input type='number' id='percentage[]' name='percentage[]' class='form-control' min='0' max='100' value='".$row['percentage']."' required></td>";
					            					echo "<td><input type='text' id='remarks[]' name='remarks[]' class='form-control' value='".$row['remarks']."'></td>";
					            				echo "</tr>";
					            			
			            				}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>


<div class='col-xs-12 col-sm-12 col-lg-12'>
	<span class='pull-right'>
		<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: validateForm()' />
		<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
	</span>
</div>
<br><br><br>

</form>


<script type="text/javascript">

	$('#company').change(function() 
	{   
	  var company_id = document.getElementById('company').value;
	  var type = document.getElementById('bom_type').value;
	  if(company_id!=""){
		  $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH.CONTROLLER;?>/bom_by_company/"+type+"/"+company_id,
		     success:function(data){
		        var bom_no = $.parseJSON(data);

		        $('#bom_no').find('option').remove().end().append('<option value="">Select</option>').val('');
				for(var i = 0; i < bom_no.length; i++){
		       		var bom = bom_no[i];
		       		var option = $('<option />');
				    option.attr('value', bom.id).text("BOM No."+bom.control_number + " ("+ bom.asset_name + ")");
				    $('#bom_no').append(option);
		        }
		     } 	
		  });
	  }

	  $('#for_repair_table tbody').remove();
	  var default_table = "<tbody><tr><td>...</td></tr></tbody>";
	  $('#for_repair_table').append(default_table);

	  $('#table_tasks_list tbody').remove();
	  var default_table = "<tbody></tbody>";
	  $('#table_tasks_list').append(default_table);

	  $('#asset_id').val("");
	  $('#report_form_no').val("");
	  $('#evaluation_form_no').val("");
	  $('#start_date_of_repair').val("");
	});	

	$('#bom_no').change(function(){

	  $('#for_repair_table tbody').remove();
	  var default_table = "<tbody><tr><td>...</td></tr></tbody>";
	  $('#for_repair_table').append(default_table);

	  /*$('#table_tasks_list tbody').remove();
	  var default_table = "<tbody></tbody>";
	  $('#table_tasks_list').append(default_table);*/

	  $('#asset_id').val("");
	  $('#report_form_no').val("");
	  $('#evaluation_form_no').val("");
	  $('#start_date_of_repair').val("");

		var type = document.getElementById('bom_type').value;

			$.ajax({
				type:"POST",
				url:"<?php echo HTTP_PATH.CONTROLLER;?>/bom_info/"+type+"/"+$(this).val(),
				success:function(data){

					var table_data = $.parseJSON(data);

						$('#for_repair_table tbody').remove();

						if(type=="Vessel"){
							var newRow = "<tbody><tr><th>Vessel Name</th><th>Dry-Docking Date</th><th>Dry-Docking Location</th><th>LOA</th><th>Breadth</th><th>Depth</th><th>GT</th></tr><tr><td>"+table_data.vessel_name+"</td><td>"+table_data.dry_docking_date+"</td><td>"+table_data.dry_docking_location+"</td><td>"+table_data.length_loa+"</td><td>"+table_data.breadth+"</td><td>"+table_data.depth+"</td><td>"+table_data.gross_tonnage+"</td></tr></tbody>";

								$('#asset_id').val(table_data.vessel_id);

								if(table_data.WO_number==0){
									var report_form_no = "N/A";
								}else{
									var report_form_no = table_data.WO_number;
								}

								$('#report_form_no').val(report_form_no);
								$('#evaluation_form_no').val(table_data.control_number);
								$('#start_date_of_repair').val(table_data.start_date_of_repair);
						}else{
							var newRow = "<tbody><tr><th>Truck Plate-No.</th><th>Make</th><th>Model</th><th>Engine No.</th><th>Chassis No.</th><th>Type</th></tr><tr><td>"+table_data.plate_number+"</td><td>"+table_data.make+"</td><td>"+table_data.model+"</td><td>"+table_data.engine_number+"</td><td>"+table_data.chassis_number+"</td><td>"+table_data.type+"</td></tr></tbody>";

								$('#asset_id').val(table_data.truck_id);

								if(table_data.TRMRF_number==0){
									var report_form_no = "N/A";
								}else{
									var report_form_no = table_data.TRMRF_number;
								}

								$('#report_form_no').val(report_form_no);
								$('#evaluation_form_no').val(table_data.control_number);
								$('#start_date_of_repair').val(table_data.start_date_of_repair);
						}

						$('#for_repair_table').append(newRow);
				}
			});


			/*$.ajax({
				type:"POST",
				url:"<?php echo HTTP_PATH.CONTROLLER;?>/check_schedule_log_bom/"+bom_id,
				success:function(data){
					var bom = $.parseJSON(data);

					if(bom.chk_bom2 == true){
						toastr['error']("A Schedule Log has already been created for this company asset. Please select another one.", "ABAS says:");
						document.getElementById('bom_no').value = "";

					}
				}
			});*/

	});

	function loadTasksList(){

		var bom_id = document.getElementById('bom_no').value;

		$.ajax({
				type:"POST",
				url:"<?php echo HTTP_PATH.CONTROLLER;?>/bom_tasks_list/"+bom_id,
				success:function(data){
					var table_data = $.parseJSON(data);

					var start_date_of_repair = $('#start_date_of_repair').val();

				    $.each(table_data, function(idx, elem){
				        $("#table_tasks_list").append("<tr><td><input type='hidden' id='bom_id[]' name='bom_id[]' value='"+bom_id+"'>BOM TSCode "+bom_id+"</td><td>"+elem.task_number+"</td><td>"+elem.scope_of_work+"</td> <td>"+elem.estimated_time_to_complete+" Day(s)</td><td><input type='hidden' id='task_id[]' name='task_id[]' value='"+elem.id+"'><input type='type' id='personnel_in_charge[]' name='personnel_in_charge[]' class='form-control tool_name' required></td><td><input type='date' id='plan_start_date[]' name='plan_start_date[]' class='form-control' min='"+start_date_of_repair+"' required></td><td><input type='date' id='actual_start_date[]' name='actual_start_date[]' class='form-control' required></td><td><input type='number' id='actual_work_duration[]' name='actual_work_duration[]' class='form-control' required></td><td><input type='number' id='percentage[]' name='percentage[]' class='form-control' required></td><td><input type='text' id='remarks[]' name='remarks[]' class='form-control'></td></tr>");
				    });
				}
			});

	}

	function clearTasksList(){
	 	$('#table_tasks_list tbody').remove();
	  	var default_table = "<tbody></tbody>";
	  	$('#table_tasks_list').append(default_table);
	 	return true;
	 }

	function validateForm(){

		var row_count = $('#table_tasks_list tr').length;

		if(row_count<=1){
        	toastr['error']("Please add at least one task list.", "ABAS says:");
			return false;
        }

		var gen_selects = document.getElementById('SLGeneral').getElementsByTagName('select');

    	var gen_flag=0;
        for(var i = 0; i < gen_selects.length; i++){         
            if (gen_selects[i].value==""){
            	gen_flag=1;
            } 
        }

        if(gen_flag==1){
        	toastr['error']("Please fill-out all required* fields in General Information Tab!", "ABAS says:");
			return false;
        }

    	var task_input = document.getElementById('SLTasklist').getElementsByTagName('input');



    	var tasks_flag=0;
    	for(var i = 0; i < task_input.length; i++){         
            if (task_input[i].value=="" && task_input[i].required==true){
            	tasks_flag=1;
            } 
        }

        if(tasks_flag==1){
        	toastr['error']("Please fill-out all required* fields in Tasks List Tab!", "ABAS says:");
			return false;
        }


        if(row_count>0 && gen_flag==0 && tasks_flag==0) {

        	$('body').addClass('is-loading'); 
			$('#modalDialog').modal('toggle'); 

			document.getElementById("schedule_log_form").submit();
			return true;
		}

	}

</script>