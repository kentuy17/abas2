<?php

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(isset($TRMRF)){
			$company_options	.=	"<option ".($TRMRF['company_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}
		else{
			if($option->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
	}
	unset($option);
}

$truck_options = "<option value=''>Select</option>";
if(isset($TRMRF)) {
	foreach($trucks as $option) {
		$truck_options	.=	"<option ".($TRMRF['truck_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->plate_number."</option>";
	}
unset($option);
}


$details = '<div class="row item-row-details command-row-details">
			<hr>
				<div class="col-md-11 col-sm-11 col-xs-12">
					<label>Complaints*</label>
					<input type="text" id="complaints[]" name="complaints[]" class="form-control complaint" required>
				</div>

				<a class="btn-remove-row-details btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>

				<div class="col-md-11 col-sm-11 col-xs-12">
					<label>Cause and Corrections*</label>
					<textarea id="cause_corrections[]" name="cause_corrections[]" class="form-control remarks" row="3" required></textarea>
				</div>

				<div class="col-md-11 col-sm-11 col-xs-12">
					<label>Remarks*</label>
					<textarea id="remarks[]" name="remarks[]" class="form-control remarks" row="3" required></textarea>
				</div>
					
	        </div>';

$details_append= trim(preg_replace('/\s+/',' ', $details));

$driver="";
$location="";
$priority="";
$make="";
$model="";
$engine_number="";
$chassis_number="";
$truck_details="";
$disabled="";

if(isset($TRMRF)){

	$driver="value='".$TRMRF['driver']."'";
	$location="value='".$TRMRF['location']."'";
	$priority="<option value='".$TRMRF['priority']."' selected>".$TRMRF['priority']."</option>";
	$make="value='".$TRMRF['make']."'";
	$model="value='".$TRMRF['model']."'";
	$engine_number="value='".$TRMRF['engine_number']."'";
	$chassis_number="value='".$TRMRF['chassis_number']."'";
	$disabled="disabled";

	foreach($TRMRF_details as $row){
		$truck_details .= '<div class="row item-row-details command-row-details">
						<hr>
						<div class="col-md-11 col-sm-11 col-xs-12">
							<label>Complaints*</label>
							<input type="text" id="complaints[]" name="complaints[]" class="form-control complaint" value="'.$row->complaints.'" required>
						</div>

						<a class="btn-remove-row-details btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>

						<div class="col-md-11 col-sm-11 col-xs-12">
							<label>Cause and Corrections*</label>
							<textarea id="cause_corrections[]" name="cause_corrections[]" class="form-control remarks" row="3" required>'.$row->cause_corrections.'</textarea>
						</div>

						<div class="col-md-11 col-sm-11 col-xs-12">
							<label>Remarks*</label>
							<textarea id="remarks[]" name="remarks[]" class="form-control remarks" row="3" required>'.$row->remarks.'</textarea>
						</div>
							
			        </div>';
	}
}

?>


<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>
					<?php 
						if(!isset($TRMRF)){
							echo "Add Truck Repairs and Maintenance Report Form";
						}else{
							echo "Edit Truck Repairs and Maintenance Report Form";
						}
					?>
				</text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>

	<?php
		// CI Form 
		$attributes = array('id'=>'truck_repairs_form','role'=>'form');
		if(!isset($TRMRF)){
			$action = '/add/TRMRF';
		}
		else{
			$action = '/edit/TRMRF/'.$TRMRF['id'];
		}
		echo form_open_multipart(HTTP_PATH.CONTROLLER.$action,$attributes);
		echo $this->Mmm->createCSRF();
	?>
		
		<div class='panel-body panel'>
			
			<div class='panel-group' id='TRMRFFormDivider' role='tablist' aria-multiselectable='true'>

				<div class='panel panel-info'>

					<div class='panel-heading' role='tab' id='general'>	
						<a role='button' data-toggle='collapse' data-parent='#TRMRFFormDivider' href='#TRMRFGeneral' aria-expanded='true' aria-controls='WOGeneral'>
						General Information
						<span class='glyphicon glyphicon-chevron-down pull-right'></span>
						</a>
					</div>

					<div id='TRMRFGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='TRMRFGeneral'>

						<div class='panel-body'>

							<!--<div class='col-md-3 col-sm-3 col-xs-12'>
								<label>Control Number</label>
								<input type="text" id='control_number' name='control_number' class='form-control control_number' style="text-align:center"readonly>
							</div>-->

							<div class='col-md-9 col-sm-9 col-xs-12'>
								<label>Company*</label>
								<select id='company' name='company' class='form-control company' <?php echo $disabled;?> required>
									<?php echo $company_options;?>
								</select>
							</div>
							<div class='col-md-3 col-sm-3 col-xs-12'>
								<label>Plate Number*</label>
								<select id='truck' name='truck' class='form-control truck' required>
									<option val=''>Select</option>
									<?php echo $truck_options;?>
								</select>
							</div>
							<div class='col-md-9 col-sm-9 col-xs-12'>
								<label>Driver*</label>
								<input type="text" id='driver' name='driver' class='form-control' <?php echo $driver;?> required>
							</div>

							<div class='col-md-3 col-sm-3 col-xs-12'>
								<label>Priority*</label>
								<select id='priority' name='priority' class='form-control' required>
									<option value=''>Select</option>
									<option value='For Immediate Repair'>For Immediate Repair</option>
									<option value='For Normal Repair'>For Normal Repair</option>
									<?php echo $priority;?>
								</select>
							</div>

							<div class='col-md-12 col-sm-12 col-xs-12'>
								<label>Current Location*</label>
								<input type="text" id='location' name='location' class='form-control' <?php echo $location;?> required>
							</div>
							
							<div class='col-md-3 col-sm-3 col-xs-12'>
								<label>Make</label>
								<input type="text" id='make' name='make' class='form-control make' <?php echo $make;?> readonly>
							</div>

							<div class='col-md-3 col-sm-3 col-xs-12'>
								<label>Model</label>
								<input type="text" id='model' name='model' class='form-control model' <?php echo $model;?> readonly>
							</div>

							<div class='col-md-3 col-sm-3 col-xs-12'>
								<label>Engine Number</label>
								<input type="text" id='engine_number' name='engine_number' class='form-control engine_number' <?php echo $engine_number;?> readonly>
							</div>

							<div class='col-md-3 col-sm-3 col-xs-12'>
								<label>Chassis Number</label>
								<input type="text" id='chassis_number' name='chassis_number' class='form-control chassis_number' <?php echo $chassis_number;?> readonly>
							</div>

							
						</div>

					</div>

				</div>

				<div class='panel panel-info'>

					<div class='panel-heading' role='tab' id='general'>	
						<a role='button' data-toggle='collapse' data-parent='#TRMRFFormDivider' href='#TRMRFDetails' aria-expanded='true' aria-controls='TRMRFDetails'>
						Details
						<span class='glyphicon glyphicon-chevron-down pull-right'></span>
						</a>
					</div>

					<div id='TRMRFDetails' class='panel-collapse collapse' role='tabpanel' aria-labelledby='TRMRFDetails'>

						<div style='float:left; margin-top:5px; margin-left:5px'>                          
							<a id='btn_add_row_details' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
							<a id='btn_remove_row_details' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
						</div>

						<div class='panel-body item-row-container-details'>
							<?php 
								if(!isset($TRMRF)){
									echo $details;
								}else{
									echo $truck_details;
								}
							?>
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
	
			

<script type='text/javascript'>
	
	$('#btn_remove_row_details').click(function(){
		$('.item-row-details:last').remove();
	});
	$(document).on('click', '.btn-remove-row-details', function() {
		$(this).parent().remove();
	});
	$('#btn_add_row_details').click(function(){
		$('.item-row-container-details').append('<?php echo $details_append; ?>');
	});
	
	//Ajax to fill trucks dropdown based on company selected
	$('#company').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH.CONTROLLER;?>/trucks_by_company/"+$(this).val(),
	     success:function(data){
	        var trucks = $.parseJSON(data);
	       
	        $('#truck').find('option').remove().end().append('<option value="">Select</option>').val('');

	        document.getElementById('make').value = "";
	        document.getElementById('model').value = "";
	        document.getElementById('engine_number').value = "";
	        document.getElementById('chassis_number').value = "";

	        for(var i = 0; i < trucks.length; i++){
	       		var truck = trucks[i];
	       		var option = $('<option />');
			    option.attr('value', truck.id).text(truck.plate_number);
			    $('#truck').append(option);
	        }

	     }

	  });
	});

	$('#truck').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH.CONTROLLER;?>/truck_info/"+$(this).val(),
	     success:function(data){
	        var truck = $.parseJSON(data);

	        document.getElementById('make').value = truck[0].make;
	        document.getElementById('model').value = truck[0].model;
	        document.getElementById('engine_number').value = truck[0].engine_number;
	        document.getElementById('chassis_number').value = truck[0].chassis_number;

	     }

	  });
	});

	//fill control number
	/*$('#company').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php //echo HTTP_PATH.CONTROLLER;?>/control_number_by_company/am_truck_repairs/"+$(this).val(),
	     success:function(data){
	        var control_number = $.parseJSON(data);
	       	document.getElementById('control_number').value=control_number;
	     }

	  });
	});*/

	function validateForm(){

		var gen_selects = document.getElementById('TRMRFGeneral').getElementsByTagName('select');
    	var gen_inputs = document.getElementById('TRMRFGeneral').getElementsByTagName('input');

    	var gen_flag=0;
        for(var i = 0; i < gen_selects.length; i++){         
            if (gen_selects[i].value==""){
            	gen_flag=1;
            } 
        }
        for(var x = 0; x < gen_inputs.length; x++){
        	if (gen_inputs[x].value==""){
            	gen_flag=1;
            }
        }

        if(gen_flag==1){
        	toastr['error']("Please fill-out all required* fields in General Information Tab!", "ABAS says:");
			return false;
        }

        var details_divs = document.getElementsByClassName('item-row-details')
    	var details_inputs = document.getElementById('TRMRFDetails').getElementsByTagName('input');
    	var details_texts = document.getElementById('TRMRFDetails').getElementsByTagName('textarea');

    	var details_flag=0;
    	if(details_divs.length > 0){
	        for(var x = 0; x < details_inputs.length; x++){
	        	if (details_inputs[x].value==""){
	            	details_flag=1;
	            }
	        }	
	    }
	    if(details_divs.length > 0){
	        for(var x = 0; x < details_texts.length; x++){
	        	if (details_texts[x].value==""){
	            	details_flag=1;
	            }
	        }	
	    }
	    if(details_divs.length == 0){
	    	details_flag==1;
	    }
        if(details_flag==1){
        	toastr['error']("Please fill-out all required* fields in Details Tab!", "ABAS says:");
			return false;
        }

        if(gen_flag==0 && details_flag==0) {

        	$('body').addClass('is-loading'); 
			$('#modalDialog').modal('toggle'); 

			document.getElementById("truck_repairs_form").submit();
			return true;
		}
	}

</script>