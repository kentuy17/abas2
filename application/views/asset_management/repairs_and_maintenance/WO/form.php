<?php

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(isset($work_order)){
			$company_options	.=	"<option ".($work_order['company_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}else{
			if($option->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
	}
	unset($option);
}

$vessel_options = "<option value=''>Select</option>";
if(!empty($vessels)) {
	foreach($vessels as $option) {
		if(isset($work_order)){
			$vessel_options	.=	"<option ".($work_order['vessel_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}
	}
	unset($option);
}

$details = "";
$details_edit = "";

$current_location  = "";
$requisitioner = "";
$designation = "";
$disabled = "";

$details = '<div class="row item-row-details command-row-details">
			<hr>
				<div class="col-md-11 col-sm-11 col-xs-12">
					<label>Complaint/Particulars*</label>
					<input type="text" id="complaints[]" name="complaints[]" class="form-control complaint" required>
				</div>

				<a class="btn-remove-row-details btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>

				<div class="col-md-11 col-sm-11 col-xs-12">
					<label>Status/Remarks*</label>
					<textarea id="remarks[]" name="remarks[]" class="form-control remarks" row="3" required></textarea>
				</div>
					
	        </div>';

if(isset($work_order)){

$current_location  = $work_order['location'];
$requisitioner = $work_order['requisitioner'];
$designation = $work_order['designation'];
$disabled = "disabled";
	
	foreach($work_order_details as $row){
		$details_edit .= '<div class="row item-row-details command-row-details">
				<hr>
					<div class="col-md-11 col-sm-11 col-xs-12">
						<label>Complaint/Particulars*</label>
						<input type="text" id="complaints[]" name="complaints[]" class="form-control complaint" value="'.$row->complaint_particulars.'" required>
					</div>

					<a class="btn-remove-row-details btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>

					<div class="col-md-11 col-sm-11 col-xs-12">
						<label>Status/Remarks*</label>
						<textarea id="remarks[]" name="remarks[]" class="form-control remarks" row="3" required>'.$row->status_remarks.'</textarea>
					</div>
						
		        </div>';
	}

	
}


$details_append= trim(preg_replace('/\s+/',' ', $details));


?>


<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>
				<?php 
					if(!isset($work_order)){
						echo "Add Vessel Work Order";
					}else{
						echo "Edit Vessel Work Order";
					}
				?>
				</text>
				
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>

	<?php
		// CI Form 
		$attributes = array('id'=>'work_order_form','role'=>'form');
		if(!isset($work_order)){
			$action = '/add/WO';
		}else{
			$action = '/edit/WO/'.$work_order['id'];
		}
		echo form_open_multipart(HTTP_PATH.CONTROLLER.$action,$attributes);
		echo $this->Mmm->createCSRF();
	?>
		
		<div class='panel-body panel'>
			
			<div class='panel-group' id='WOFormDivider' role='tablist' aria-multiselectable='true'>

				<div class='panel panel-info'>

					<div class='panel-heading' role='tab' id='general'>	
						<a role='button' data-toggle='collapse' data-parent='#WOFormDivider' href='#WOGeneral' aria-expanded='true' aria-controls='WOGeneral'>
						General Information
						<span class='glyphicon glyphicon-chevron-down pull-right'></span>
						</a>
					</div>

					<div id='WOGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='WOGeneral'>

						<div class='panel-body'>

							<!--<div class='col-md-3 col-sm-3 col-xs-12'>
								<label>Control Number</label>
								<input type="text" id='control_number' name='control_number' class='form-control control_number' style="text-align:center"readonly>
							</div>-->
							<div class='col-md-7 col-sm-7 col-xs-12'>
								<label>Company*</label>
								<select id='company' name='company' class='form-control company' <?php echo $disabled;?> required>
									<?php echo $company_options;?>
								</select>
							</div>
							<div class='col-md-5 col-sm-5 col-xs-12'>
								<label>Vessel*</label>
								<select id='vessel' name='vessel' class='form-control vessel' <?php echo $disabled;?> required>
									<?php echo $vessel_options;?>
								</select>
							</div>
							<div class='col-md-12 col-sm-12 col-xs-12'>
								<label>Current Location*</label>
								<input type="text" id='location' name='location' class='form-control' value='<?php echo $current_location;?>' required>
							</div>
							<div class='col-md-7 col-sm-7 col-xs-12'>
								<label>Requisitioner's Name*</label>
								<input type="text" id='requisitioner' name='requisitioner' class='form-control' value='<?php echo $requisitioner;?>' required>
							</div>
							<div class='col-md-5 col-sm-5 col-xs-12'>
								<label>Designation*</label>
								<input type="text" id='designation' name='designation' class='form-control' value='<?php echo $designation;?>' required>
							</div>

						</div>

					</div>

				</div>

				<div class='panel panel-info'>

					<div class='panel-heading' role='tab' id='general'>	
						<a role='button' data-toggle='collapse' data-parent='#WOFormDivider' href='#WODetails' aria-expanded='true' aria-controls='WODetails'>
						Details
						<span class='glyphicon glyphicon-chevron-down pull-right'></span>
						</a>
					</div>

					<div id='WODetails' class='panel-collapse collapse' role='tabpanel' aria-labelledby='WODetails'>

						<div class="pull-right" style='float:left; margin-top:5px; margin-left:5px'>                          
							<a id='btn_add_row_details' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
							<a id='btn_remove_row_details' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
						</div>

						<div class='panel-body item-row-container-details'>
							<?php 
								if(!isset($work_order)){
									echo $details;
								}else{
									echo $details_edit;
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
	
	//fill vessels per company
	$('#company').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH.CONTROLLER;?>/vessels_by_company/"+$(this).val(),
	     success:function(data){
	        var vessel_names = $.parseJSON(data);
	       
	        $('#vessel').find('option').remove().end().append('<option value="">Select</option>').val('');

	        for(var i = 0; i < vessel_names.length; i++){
	       		var vessel = vessel_names[i];
	       		var option = $('<option />');
			    option.attr('value', vessel.id).text(vessel.name);
			    $('#vessel').append(option);
	        }

	     }

	  });
	});

	//fill control number
	/*$('#company').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php //echo HTTP_PATH.CONTROLLER;?>/control_number_by_company/am_vessel_work_order/"+$(this).val(),
	     success:function(data){
	        var control_number = $.parseJSON(data);
	       	document.getElementById('control_number').value=control_number;
	     }

	  });
	});*/

	function validateForm(){

		var gen_selects = document.getElementById('WOGeneral').getElementsByTagName('select');
    	var gen_inputs = document.getElementById('WOGeneral').getElementsByTagName('input');

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
    	var details_inputs = document.getElementById('WODetails').getElementsByTagName('input');
    	var details_texts = document.getElementById('WODetails').getElementsByTagName('textarea');

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

			document.getElementById("work_order_form").submit();
			return true;
		}
	}

</script>