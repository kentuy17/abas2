<?php

$WO_options = "<option value=''>Select</option>";
$WO_options .= "<option value='0'>Not Applicable</option>";
if(!empty($WO)){
	foreach($WO as $option){
		if(isset($SRMSF)){
			if($SRMSF['WO_number']!=0){
				$WO_options	.=	"<option ".($SRMSF['WO_number']==$option->id ? "selected":"")." value='".$option->id."'>".$option->WO_number."</option>";
			}else{
				$WO_options .= "<option value='0' selected>Not Applicable</option>";
			}
		}else{
			$WO_options .=	"<option value='".$option->id."'>".$option->WO_number."</option>";
		}
	}
	unset($option);
}else{
	$WO_options .= "<option value='0'>Not Applicable</option>";
}

$rating_options = "<option value=''>Select</option>";
foreach($ratings as $rating){
	$rating_options	.=	"<option value='".$rating."'>".$rating."</option>";
}

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(isset($SRMSF)){
			$company_options	.=	"<option ".($SRMSF['company_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}else{
			if($option->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
	}
	unset($option);
}

$vessel_options = "<option value=''>Select</option>";
if(isset($SRMSF)) {
	foreach($vessels as $option) {
		$vessel_options	.=	"<option ".($SRMSF['vessel_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
	}
unset($option);
}

$dry_docking_date = "";
$dry_docking_location = "";
$disabled = "";
$length_loa = "";
$breadth = "";
$depth = "";
$gross_tonnage = "";
$notes = "";

if(isset($SRMSF)){

	$dry_docking_date = "value='".$SRMSF['dry_docking_date']."'";
	$dry_docking_location = "value='".$SRMSF['dry_docking_location']."'";
	$disabled = "disabled";
	$length_loa = "value='".$vessel_measurements[0]->length_loa."'";
	$breadth = "value='".$vessel_measurements[0]->breadth."'";
	$depth = "value='".$vessel_measurements[0]->depth."'";
	$gross_tonnage = "value='".$vessel_measurements[0]->gross_tonnage."'";
	$notes = $SRMSF['notes'];

}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Ship Repairs and Maintenance Survey Form</title>
	</head>
		<body>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="panel-title">
						<text>
							<?php 
								if(isset($SRMSF)){
									echo "Edit Ship Repairs and Maintenance Survey Form";
								}else{
									echo "Add Ship Repairs and Maintenance Survey Form";
								}
							?>
						</text>
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
					</div>
				</div>
			</div>
				
				<div class="panel-body panel">

					<?php
						// CI Form 
						$attributes = array('id'=>'maintenance_form','role'=>'form');
						if(!isset($SRMSF)){
							$action = "/add/SRMSF";
						}else{
							$action = "/edit/SRMSF/".$SRMSF['id'];
						}
						echo form_open_multipart(HTTP_PATH.CONTROLLER.$action,$attributes);
						echo $this->Mmm->createCSRF();
					?>
					
					<!-- Smart Wizard -->
				   <div class="x_content">

                    	<div id="wizard" class="form_wizard wizard_horizontal" style="overflow-x: auto">
		                      
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

								<div class="col-md-5 col-sm-5 col-xs-12">
	                      			<label>Work Order No.*</label>
	        
	                      			<select name="WO_number" id="WO_number" class="form-control" <?php echo $disabled;?> required>
	                      				<?php echo $WO_options;?>
	                      			</select>
								</div>

	                      		<div class="col-md-7 col-sm-7 col-xs-12">
	                      			<label>Company Name*</label>
	                      			<select name='company' id='company' class='form-control' <?php echo $disabled;?> required>
										<?php echo $company_options; ?>
									</select>
									<input type='hidden' name='company_x' id='company_x'>
								</div>

								<div class="col-md-4 col-sm-4 col-xs-12">
	                      			<label>Vessel Name*</label>
	                      			<select name="vessel" id="vessel" class="form-control" <?php echo $disabled;?> required>
										<option value=''>Select</option>
										<?php echo $vessel_options; ?>
									</select>
									<input type='hidden' name='vessel_x' id='vessel_x'>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-6">
	                      			<label>Length Overall</label>
	                      			<input type="text" class="form-control" id="loa" <?php echo $length_loa; ?> readonly>
	                      		</div>

	                      		<div class="col-md-2 col-sm-2 col-xs-6">
	                      			<label>Breadth</label>
	                      			<input type="text" class="form-control" id="breadth" <?php echo $breadth; ?> readonly>
	                      		</div>

	                      		<div class="col-md-2 col-sm-2 col-xs-6">
	                      			<label>Depth</label>
	                      			<input type="text" class="form-control" id="depth" <?php echo $depth; ?> readonly>
	                      		</div>

	                      		<div class="col-md-2 col-sm-2 col-xs-6">
	                      			<label>Gross-Tonnage</label>
	                      			<input type="text" class="form-control" id="gt" <?php echo $gross_tonnage; ?> readonly>
	                      		</div>

	                      		<div class="col-md-3 col-sm-3 col-xs-12">
	                      			<label>Dry-Docking Date*</label><input type="date" id="dry_docking_date" <?php echo $dry_docking_date;?> name="dry_docking_date" class="form-control" required>
	                      		</div>

	                      		<div class="col-md-9 col-sm-5 col-xs-12">
	                      			<label>Dry-Docking Location*</label><input type="text" id="dry_docking_location" name="dry_docking_location" class="form-control" <?php echo $dry_docking_location;?> required>
	                      		</div>

	                      	
							</div>
                        </div>
					
						<?php
                        	for($i=0;$i<count($steps);$i++){//start loop to display evaluation items per index/category
                        ?>

                        <div id="<?php echo 'step-'.$steps[$i][0]; ?>" style="overflow-y:auto">                 		
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
						                        		if(isset($SRMSF_details)){
						                        			foreach ($SRMSF_details as $detail) {
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
	    
	    //$("#wizard").smartWizard('goBackward');

	    $('#wizard').smartWizard('goToStep', 1);

	    //$('#stepContainer').hide();


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
	            //var val0 = document.getElementById('control_number').value;
	            var val1 = document.getElementById('WO_number').value;
	        	var val2 = document.getElementById('company').value;
	        	var val3 = document.getElementById('vessel').value;
	        	var val4 = document.getElementById('dry_docking_date').value;
	        	var val5 = document.getElementById('dry_docking_location').value;

	        	if( val1 == '' || val2 == '' || val3 == '' || val4 == '' || val5 == ''){
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
	         //validate step F
	        if(stepnumber == 7){
	        	var ratings = document.getElementById('step-F').getElementsByTagName('select');
	        	var spec = document.getElementById('step-F').getElementsByTagName('input');
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
	
	//Ajax to fill vessels dropdown based on company selected
	$('#company').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH.CONTROLLER;?>/vessels_by_company/"+$(this).val(),
	     success:function(data){
	        var vessel_names = $.parseJSON(data);
	       
	        $('#vessel').find('option').remove().end().append('<option value="">Select</option>').val('');

	        document.getElementById('loa').value = "";
	        document.getElementById('breadth').value = "";
	        document.getElementById('depth').value = "";
	        document.getElementById('gt').value = "";

	        for(var i = 0; i < vessel_names.length; i++){
	       		var vessel = vessel_names[i];
	       		var option = $('<option />');
			    option.attr('value', vessel.id).text(vessel.name);
			    $('#vessel').append(option);
	        }

	     }

	  });
	});

	//Ajax to fill measurements of the vessel
	$('#vessel').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH.CONTROLLER;?>/vessel_measurements/"+$(this).val(),
	    // data:"id="+$(this).val(),
	     success:function(data){
	        var vessel = $.parseJSON(data);

	        document.getElementById('loa').value = vessel[0].length_loa;
	        document.getElementById('breadth').value = vessel[0].breadth;
	        document.getElementById('depth').value = vessel[0].depth;
	        document.getElementById('gt').value = vessel[0].gross_tonnage;

	     }

	  });
	});

	//ajax to fill data if Work Order No. is set
	$('#WO_number').change(function() 
	{
		var WO_Number = document.getElementById('WO_number').value;
		if(WO_Number==0 || WO_Number=="Not Applicable" || WO_Number==""){

			 $('#company').prop('disabled',false);
			 $('#vessel').prop('disabled',false);

			   document.getElementById('company').value = "";
			   document.getElementById('vessel').value = "";
			   document.getElementById('loa').value = "";
			   document.getElementById('breadth').value = "";
			   document.getElementById('depth').value = "";
			   document.getElementById('gt').value = "";

			   document.getElementById('company_x').value = "";
			   document.getElementById('vessel_x').value = "";
		}

		if(WO_Number!=0 || WO_Number!="Not Applicable"){

			 $('#company').prop('disabled',true);
			 $('#vessel').prop('disabled',true);

			$.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH.CONTROLLER;?>/work_order_info/"+$(this).val(),
		     success:function(data){
		        var info = $.parseJSON(data);

				document.getElementById('company').value = info.company_id;

				document.getElementById('company_x').value = info.company_id;

				$.ajax({
				     type:"POST",
				     url:"<?php echo HTTP_PATH.CONTROLLER;?>/vessels_by_company/"+document.getElementById('company').value,
				     success:function(data){
				        var vessel_names = $.parseJSON(data);
				       
				        $('#vessel').find('option').remove().end().append('<option value="">Select</option>').val('');

				        document.getElementById('loa').value = "";
				        document.getElementById('breadth').value = "";
				        document.getElementById('depth').value = "";
				        document.getElementById('gt').value = "";

				        for(var i = 0; i < vessel_names.length; i++){
				       		var vessel = vessel_names[i];
				       		var option = $('<option />');
						    option.attr('value', vessel.id).text(vessel.name);
						    $('#vessel').append(option);
				        }

				        document.getElementById('vessel').value = info.vessel_id;

				        document.getElementById('vessel_x').value = info.vessel_id;

				         $.ajax({
						     type:"POST",
						     url:"<?php echo HTTP_PATH.CONTROLLER;?>/vessel_measurements/"+document.getElementById('vessel').value,
						     success:function(data){
						        var vessel = $.parseJSON(data);

						        document.getElementById('loa').value = vessel[0].length_loa;
						        document.getElementById('breadth').value = vessel[0].breadth;
						        document.getElementById('depth').value = vessel[0].depth;
						        document.getElementById('gt').value = vessel[0].gross_tonnage;

						     }

						  });

				        $.ajax({
					     type:"POST",
					     url:"<?php echo HTTP_PATH.CONTROLLER;?>/control_number_by_company/am_vessel_evaluation/"+document.getElementById('company').value,
					     success:function(data){
					        var control_number = $.parseJSON(data);
					       	document.getElementById('control_number').value=control_number;
					     }
					  	});

				     }

				});
						
			 }
			});
		}
	});

</script>