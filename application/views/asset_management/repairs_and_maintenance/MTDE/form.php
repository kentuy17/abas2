<?php

$TRMRF_options = "<option value=''>Select</option>";
$TRMRF_options .= "<option value='Not Applicable'>Not Applicable</option>";
if(!empty($TRMRF)){
	foreach($TRMRF as $option){
		
		if(isset($MTDE)){
			if($MTDE['TRMRF_number']!=0){
				$TRMRF_options	.=	"<option ".($MTDE['TRMRF_number']==$option->id ? "selected":"")." value='".$option->id."'>".$option->TRMRF_number."</option>";
			}else{
				$TRMRF_options .= "<option value='Not Applicable' selected>Not Applicable</option>";
			}
		}else{
			$TRMRF_options .=	"<option value='".$option->id."'>".$option->TRMRF_number."</option>";
		}

	}

	unset($option);
}

$rating_options = "<option value=''>Select</option>";
foreach($ratings as $rating){
	$rating_options	.=	"<option value='".$rating."'>".$rating."</option>";
}

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(isset($MTDE)){
				$company_options	.=	"<option ".($MTDE['company_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
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
if(isset($MTDE)) {
	foreach($trucks as $option) {
		$truck_options	.=	"<option ".($MTDE['truck_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->plate_number."</option>";
	}
unset($option);
}

$driver="";
$make="";
$model="";
$engine_number="";
$chassis_number="";
$type="";
$notes="";
$disabled = "";

if(isset($MTDE)){
	$driver = "value='".$MTDE['driver']."'";
	$make = "value='".$truck_info[0]->make."'";
	$model = "value='".$truck_info[0]->model."'";
	$engine_number = "value='".$truck_info[0]->engine_number."'";
	$chassis_number = "value='".$truck_info[0]->chassis_number."'";
	$type = "value='".$truck_info[0]->type."'";
	$disabled = "disabled";
	$notes=$MTDE['notes'];
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Motorpool Truck Diagnostic Evaluation</title>
	</head>
		<body>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="panel-title">
						<?php 
							if(isset($MTDE)){
								echo "<text>Edit Motorpool Truck Diagnostic Evaluation</text>";
							}else{
								echo "<text>Add Motorpool Truck Diagnostic Evaluation</text>";
							}
						?>
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
					</div>
				</div>
			</div>
				
				<div class="panel-body panel">

					<?php
						// CI Form 
						$attributes = array('id'=>'maintenance_form','role'=>'form');
						if(!isset($MTDE)){
							$action = '/add/MTDE';
						}else{
							$action = '/edit/MTDE/'.$MTDE['id'];
						}
						echo form_open_multipart(HTTP_PATH.CONTROLLER.$action,$attributes);
						echo $this->Mmm->createCSRF();
					?>
					
					<!-- Smart Wizard -->
				   <div class="x_content">

                    	<div id="wizard" class="form_wizard wizard_horizontal">
		                      
		                      <ul class="wizard_steps">

		                        <li><a href="#step-Info"><span class="step_no"><small>Info</small></span><span class="step_descr">General Information</a></li>

		                        <?php 
		                        	for($i=0;$i<count($steps);$i++){//loop to display index/category smartwizard links
		                        		echo '<li><a href="#step-'.$steps[$i][0].'"><span class="step_no">'.$steps[$i][0].'</span><span class="step_descr">'.$steps[$i][1].'</a></li>';
		                        	}
		                        ?>

		                        <li><a href="#step-Notes"><span class="step_no"><small>Notes</small></span><span class="step_descr"></a></li>

		                      </ul>
							
                        <div id="step-Info">
                      		<div class="x_panel">
								<h2 class="StepTitle">General Information</h2>

								<!--<div class="col-md-2 col-sm-2 col-xs-12">
	                      			<label>Control Number</label>
	                      			<input type="text" name="control_number" id="control_number" class="form-control" style="text-align:center" required readonly>
								</div>-->

								<div class="col-md-4 col-sm-4 col-xs-12">
	                      			<label>Truck Report Form No.*</label>
	                      			<!--<input type="number" min="0" name="TRMRF_number" id="TRMRF_number" class="form-control" required>-->
	                      			<select name="TRMRF_number" id="TRMRF_number" class="form-control" <?php echo $disabled;?>>
	                      				<?php echo $TRMRF_options;?>
	                      			</select>
								</div>

	                      		<div class="col-md-8 col-sm-8 col-xs-12">
	                      			<label>Company Name*</label>
	                      			<select name='company' id='company' class='form-control' <?php echo $disabled;?> required>
										<?php echo $company_options; ?>
									</select>
									<input type='hidden' name='company_x' id='company_x'>
								</div>

								<div class="col-md-4 col-sm-4 col-xs-12">
	                      			<label>Plate Number*</label>
	                      			<select name="truck" id="truck" class="form-control" <?php echo $disabled;?> required>
										<option value=''>Select</option>
										<?php echo $truck_options; ?>
									</select>
									<input type='hidden' name='truck_x' id='truck_x'>
								</div>


								<div class="col-md-8 col-sm-8 col-xs-12">
	                      			<label>Driver's Name*</label>
	                      			<input type="text" class="form-control" name="driver" id="driver" <?php echo $driver;?> <?php echo $disabled;?> required>
	                      			<input type='hidden' name='driver_x' id='driver_x'>
	                      		</div>
								
								<div class="col-md-3 col-sm-3 col-xs-12">
	                      			<label>Make</label>
	                      			<input type="text" class="form-control" id="make" <?php echo $make;?> readonly>
	                      		</div>

								<div class="col-md-3 col-sm-3 col-xs-12">
	                      			<label>Model</label>
	                      			<input type="text" class="form-control" id="model" <?php echo $model;?> readonly>
	                      		</div>

								<div class="col-md-3 col-sm-3 col-xs-12">
	                      			<label>Engine Number</label>
	                      			<input type="text" class="form-control" id="engine_number" <?php echo $engine_number;?> readonly>
	                      		</div>

	                      		<div class="col-md-3 col-sm-3 col-xs-12">
	                      			<label>Chassis Number</label>
	                      			<input type="text" class="form-control" id="chassis_number" <?php echo $chassis_number;?>readonly>
	                      		</div>

								<div class="col-md-12 col-sm-12 col-xs-12">
	                      			<label>Type</label>
	                      			<input type="text" class="form-control" id="type" <?php echo $type;?> readonly>
	                      		</div>
	                      	
							</div>
                        </div>
					
						<?php
                        	for($i=0;$i<count($steps);$i++){//start loop to display evaluation items per index/category
                        ?>

                        <div id="<?php echo 'step-'.$steps[$i][0]; ?>">                 		
                      		<div class="x_panel">
                      			<h2 class="StepTitle"><?php echo $steps[$i][1]; ?></h2>

								<table class="table table-striped">

				                      <thead>
				                        <tr>
				                          <th>Index</th>
				                          <th>Item/Particulars</th>
				                          <th>Rating*</th>
				                          <th>Remarks</th>
				                        </tr>
				                      </thead>

				                      <tbody>
				                        <?php
					                        if(isset($index[$steps[$i][0]])){
						                        foreach($index[$steps[$i][0]] as $item){
						                        	if($item->enabled==TRUE){


						                        		$rating = "";
						                        		$make = "";
			                        					$model = "";
			                        					$remarks = "";
						                        		if(isset($MTDE_details)){
						                        			foreach ($MTDE_details as $detail) {
						                        				if($detail->evaluation_item_id==$item->id){
						                        					$rating = "<option value='". $detail->rating ."' selected>".$detail->rating."</option>";
						                        					$make = "value='".$detail->make."'";
						                        					$model = "value='".$detail->model."'";
						                        					$remarks = $detail->remarks;
						                        				}
						                        			}
							                        	}

							                        	echo "<input type='hidden' id='item_id[]' name='item_id[]' value='". $item->id ."'>
							                        	 	  <input type='hidden' id='item_name[]' name='item_name[]' value='".$item->item_name."'>
							                        	      <tr>
								                        		   <th scope='row'>". $item->item_index .".". $item->item_set .".". $item->item_sub_set ."	
								                        		   </th>
																   <td>". $item->item_name ."</td>
																   <td style='width:110px'>
																		<select id='rating[]' name='rating[]'>
																			". $rating_options ."
																			". $rating ."
																		</select>
																   </td>
																   <td>";
														if($item->ask_spec==TRUE){
														echo       "<div class='col-md-2 col-sm-2 col-xs-2'>
																   			<input style='width:80px' type='text' name='make[]' id='make[]' placeholder='Make*' ".$make.">
																    </div>
																   	<div class='col-md-2 col-sm-2 col-xs-2'>
																   			<input style='width:80px' type='text' name='model[]' id='model[]' placeholder='Model*' ".$model.">
																   	</div>
																   	<br><br>";
														}
														else{
														//add default value N/A for items that have no specs
														echo 		"<input type='hidden' name='make[]' id='make[]' value='N/A'>
																	 <input type='hidden' name='model[]' id='model[]' value='N/A'>";	
														}
													    echo        "<div class='col-md-8 col-sm-8 col-xs-8'>
																			<textarea type='text' name='remarks[]' id='remarks[]'>".$remarks."</textarea>
																	 </div>
							                        		  		</td>
							                        		  </tr>";
						                        	}
						                        }
						                    }
				                        ?>
									</tbody>

			                    </table>

	                        </div>
	                    </div>

	                    <?php 
	                		}//end loop to display evaluation items per index/category
	                    ?>

                      	<div id="step-Notes">                       	
                        	<div class="x_panel">
                        		<h2 class="StepTitle">Notes/Others/General Remarks:</h2>
		                        <div class="col-md-12 col-sm-12 col-xs-12">
	                      			<textarea id="notes" name="notes" class="input form-control" rows="5" cols="50"><?php echo $notes;?></textarea>
	                      		</div>
	                        </div>
                      	</div>
                    </div>
                    <!-- End SmartWizard Content -->

					</form><!--CI Form-->
				</div>
			
		</body>
</html>

<script src="<?php echo LINK ?>assets/gentelella-master/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>

<script type="text/javascript">

	$(document).ready(function(){
	    // Smart Wizard         
	    $('#wizard').smartWizard({
	       onLeaveStep:leaveAStepCallback,
	       onFinish:onFinishCallback
	    });

	    $("#wizard").smartWizard('goBackward');

	    $('#stepContainer').hide();

	    function setError(stepnumber){
            $('#wizard').smartWizard('setError',{stepnum:stepnumber,iserror:true});
        }

	    function leaveAStepCallback(obj, context){
	        return validateSteps(context.fromStep); 
	    }

	    function onFinishCallback(objs, context){
	        if(validateAllSteps()){

	        	$('body').addClass('is-loading'); 
				$('#modalDialog').modal('toggle'); 

	            document.getElementById('maintenance_form').submit();
	        }
	    }

	    // Your Step validation logic
	    function validateSteps(stepnumber){
	        var isStepValid = true;

	        // validate step General Info
	        if(stepnumber == 1){
	            // field validation
	           // var val0 = document.getElementById('control_number').value;
	        	var val1 = document.getElementById('TRMRF_number').value;
	        	var val2 = document.getElementById('company').value;
	        	var val3 = document.getElementById('truck').value;
	        	var val4 = document.getElementById('driver').value;

	        	if(val1 == '' || val2 == '' || val3 == '' || val4 == ''){
	        		toastr['error']("Please fill-out all required* fields!", "ABAS says:");
	        		isStepValid = false;
	        	}
	    
	        }
	        //validate step A
	        if(stepnumber == 2){
				var ratings = document.getElementById('step-A').getElementsByTagName('select');
				var spec = document.getElementById('step-A').getElementsByTagName('input');
	        	var flag=0;
	        	if(ratings.length > 0){
			        for(var i = 0; i < ratings.length; i++){         
			            if (ratings[i].value==""){
			            	flag=1;
			            } 
			        }
			        for(var x = 0; x < spec.length; x++){
			        	if (spec[x].value==""){
			            	flag=1;
			            }
			        }
			    }else{
			    	toastr['error']("Cannot proceed since there is no Evaluation Item provided.", "ABAS says:");
		        	isStepValid = false;
			    }
		        if(flag==1){
		        	toastr['error']("Please fill-out all required* fields!", "ABAS says:");
		        	isStepValid = false;
		        }
	        }
	        //validate step B
	        if(stepnumber == 3){
	        	var ratings = document.getElementById('step-B').getElementsByTagName('select');
	        	var spec = document.getElementById('step-B').getElementsByTagName('input');
	        	var flag=0;
	        	if(ratings.length > 0){
			        for(var i = 0; i < ratings.length; i++){         
			            if (ratings[i].value==""){
			            	flag=1;
			            } 
			        }
			        for(var x = 0; x < spec.length; x++){
			        	if (spec[x].value==""){
			            	flag=1;
			            }
			        }
			    }else{
			    	toastr['error']("Cannot proceed since there is no Evaluation Item provided.", "ABAS says:");
		        	isStepValid = false;
			    }
		        if(flag==1){
		        	toastr['error']("Please fill-out all required* fields!", "ABAS says:");
		        	isStepValid = false;
		        }
	        }
	        //validate step C
	        if(stepnumber == 4){
	        	var ratings = document.getElementById('step-C').getElementsByTagName('select');
	        	var spec = document.getElementById('step-C').getElementsByTagName('input');
	        	var flag=0;
	        	if(ratings.length > 0){
			        for(var i = 0; i < ratings.length; i++){         
			            if (ratings[i].value==""){
			            	flag=1;
			            } 
			        }
			        for(var x = 0; x < spec.length; x++){
			        	if (spec[x].value==""){
			            	flag=1;
			            }
			        }
			    }else{
			    	toastr['error']("Cannot proceed since there is no Evaluation Item provided.", "ABAS says:");
		        	isStepValid = false;
			    }
		        if(flag==1){
		        	toastr['error']("Please fill-out all required* fields!", "ABAS says:");
		        	isStepValid = false;
		        }
	        }
	        //validate step D
	        if(stepnumber == 5){
	        	var ratings = document.getElementById('step-D').getElementsByTagName('select');
	        	var spec = document.getElementById('step-D').getElementsByTagName('input');
	        	var flag=0;
	        	if(ratings.length > 0){
			        for(var i = 0; i < ratings.length; i++){         
			            if (ratings[i].value==""){
			            	flag=1;
			            } 
			        }
			        for(var x = 0; x < spec.length; x++){
			        	if (spec[x].value==""){
			            	flag=1;
			            }
			        }
			    }else{
			    	toastr['error']("Cannot proceed since there is no Evaluation Item provided.", "ABAS says:");
		        	isStepValid = false;
			    }
		        if(flag==1){
		        	toastr['error']("Please fill-out all required* fields!", "ABAS says:");
		        	isStepValid = false;
		        }
	        }
	        //validate step E
	        if(stepnumber == 6){
	        	var ratings = document.getElementById('step-E').getElementsByTagName('select');
	        	var spec = document.getElementById('step-E').getElementsByTagName('input');
	        	var flag=0;
	        	if(ratings.length > 0){
			        for(var i = 0; i < ratings.length; i++){         
			            if (ratings[i].value==""){
			            	flag=1;
			            } 
			        }
			        for(var x = 0; x < spec.length; x++){
			        	if (spec[x].value==""){
			            	flag=1;
			            }
			        }
			     }else{
			    	toastr['error']("Cannot proceed since there is no Evaluation Item provided.", "ABAS says:");
		        	isStepValid = false;
			    }
		        if(flag==1){
		        	toastr['error']("Please fill-out all required* fields!", "ABAS says:");
		        	isStepValid = false;
		        }
	        }

	        return  isStepValid;    
	    }
	    function validateAllSteps(){
	        var isStepValid = true;   
	        return isStepValid;
	    }          
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
	        document.getElementById('type').value = "";

	        for(var i = 0; i < trucks.length; i++){
	       		var truck = trucks[i];
	       		var option = $('<option />');
			    option.attr('value', truck.id).text(truck.plate_number);
			    $('#truck').append(option);
	        }

	     }

	  });
	});


	//Ajax to set control number based on company selected
	/*$('#company').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php //echo HTTP_PATH.CONTROLLER;?>/control_number_by_company/am_truck_evaluation/"+$(this).val(),
	     success:function(data){
	        var control_number = $.parseJSON(data);
	       	document.getElementById('control_number').value=control_number;
	     }

	  });
	});*/

	//Ajax to fill info of the truck
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
	        document.getElementById('type').value = truck[0].type;

	     }

	  });
	});

	//ajax to fill data if Truck Report No. is set
	$('#TRMRF_number').change(function() 
	{
		var TRMRF_number = document.getElementById('TRMRF_number').value;
		if(TRMRF_number=="Not Applicable" || TRMRF_number==""){


			 $('#company').prop('disabled',false);
			 $('#truck').prop('disabled',false);
			 $('#driver').prop('disabled',false);

			document.getElementById('company').value = "";
			document.getElementById('truck').value = "";
	        document.getElementById('make').value = "";
	        document.getElementById('model').value = "";
	        document.getElementById('engine_number').value = "";
	        document.getElementById('chassis_number').value = "";
	        document.getElementById('type').value = "";
	        document.getElementById('type').prop

		}

		if(TRMRF_number!="Not Applicable"){

			$('#company').prop('disabled',true);
			$('#truck').prop('disabled',true);
			$('#driver').prop('disabled',true);

			$.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH.CONTROLLER;?>/truck_repairs_info/"+$(this).val(),
		     success:function(data){
		        var info = $.parseJSON(data);

				document.getElementById('company').value = info.company_id;
				document.getElementById('driver').value = info.driver;

				document.getElementById('company_x').value = info.company_id;
				document.getElementById('truck_x').value = info.truck_id;
				document.getElementById('driver_x').value = info.driver;

					 $.ajax({
				     type:"POST",
				     url:"<?php echo HTTP_PATH.CONTROLLER;?>/trucks_by_company/"+document.getElementById('company').value,
				     success:function(data){
					        var trucks = $.parseJSON(data);
					       
					        $('#truck').find('option').remove().end().append('<option value="">Select</option>').val('');

					        document.getElementById('make').value = "";
					        document.getElementById('model').value = "";
					        document.getElementById('engine_number').value = "";
					        document.getElementById('chassis_number').value = "";
					        document.getElementById('type').value = "";

					        for(var i = 0; i < trucks.length; i++){
					       		var truck = trucks[i];
					       		var option = $('<option />');
							    option.attr('value', truck.id).text(truck.plate_number);
							    $('#truck').append(option);	
					        }

					         document.getElementById('truck').value = info.truck_id;

					         $.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH.CONTROLLER;?>/truck_info/"+document.getElementById('truck').value,
							     success:function(data){
							        var truck = $.parseJSON(data);

							        document.getElementById('make').value = truck[0].make;
							        document.getElementById('model').value = truck[0].model;
							        document.getElementById('engine_number').value = truck[0].engine_number;
							        document.getElementById('chassis_number').value = truck[0].chassis_number;
							        document.getElementById('type').value = truck[0].type;

							     }

							 });

					         /*$.ajax({
							     type:"POST",
							     url:"<?php //echo HTTP_PATH.CONTROLLER;?>/control_number_by_company/am_truck_evaluation/"+document.getElementById('company').value,
							     success:function(data){
							        var control_number = $.parseJSON(data);
							       	document.getElementById('control_number').value=control_number;
							     }

							  });*/

					  }

				  	});

			  }

			});	 	
		}

	});

</script>

