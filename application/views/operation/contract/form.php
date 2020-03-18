<?php


$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(!isset($SC)){
			$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
		}
		elseif(isset($SC)){
			$company_options	.=	"<option ".($SC['company_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}
	}
	unset($option);
}

$client_options = "<option value=''>Select</option>";
if(!empty($clients)) {
	foreach($clients as $option) {
		if(!isset($SC)){
			$client_options	.=	"<option value='".$option['id']."'>".$option['company']."</option>";
		}
		elseif(isset($SC)){
			$client_options	.=	"<option ".($SC['client_id']==$option['id'] ? "selected":"")." value='".$option['id']."'>".$option['company']."</option>";
		}
	}
	unset($option);
}

$service_options = "<option value=''>Select</option>";
if(!empty($services)) {
	foreach($services as $option) {
		if(!isset($SC)){
			$service_options	.=	"<option value='".$option->type."'>".$option->type."</option>";
		}
		elseif(isset($SC)){
			$service_options	.=	"<option ".($SC['type']==$option->type ? "selected":"")." value='".$option->type."'>".$option->type."</option>";
		}
	}
	unset($option);
}

$contract_options = "<option value=''>Select</option>";
if(!empty($contracts)) {
	foreach($contracts as $option) {
		$company_name = $this->Abas->getCompany($option['company_id'])->name;
		if(!isset($SC)){
			$contract_options	.=	"<option value='".$option['id']."'>".$option['reference_no']." (".$company_name.")</option>";
		}
		elseif(isset($SC)){
			if($SC['id']!==$option['id']){
				$contract_options	.=	"<option ".($SC['parent_contract_id']==$option['id'] ? "selected":"")." value='".$option['id']."'>".$option['reference_no']." (".$company_name.")</option>";
			}
		}
	}
	unset($option);
}

$row_fields ="<div class='row item-row command-row'>
						<div class='col-sm-4 col-xs-12'>
							<label for='warehouse[]'>Warehouse/Port/Destination*</label>
							<input type='text' id='warehouse[]' name='warehouse[]' class='form-control' value='' />
						</div>
						<div class='col-sm-4 col-xs-12'>
							<label for='quantity[]'>Quantity*</label>
							<input type='number' id='quantity[]' name='quantity[]'  class='form-control md-input qty' value=''/>
						</div>
						<div class='col-sm-3 col-xs-12'>
							<label for='unit[]'>Unit*</label>
							<select type='text' id='unit[]' name='unit[]' class='form-control' required>
								<option value=''>Select</option>
							 	<option value='Bags'>Bags</option>
							 	<option value='Metric Ton'>Metric Ton</option>
					            <option value='Kilograms'>Kilograms</option>
					            <option value='Days'>Days</option>
					            <option value='Months'>Months</option>
					            <option value='Hours'>Hours</option>
					            <option value='Trips'>Trips</option>
							</select>
						</div>
						<a class='btn-remove-row btn btn-danger btn-xs col-m-1' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
						<div class='col-sm-4 col-xs-12'>
							<label for='rate[]'>Rate*</label>
							<input type='number' id='rate[]' name='rate[]' class='form-control md-input rte'/>
						</div>
						<div class='col-sm-4 col-xs-12'>
							<label for='additional_charges[]'>Additional Charges</label>
							<input type='number' id='additional_charges[]' name='additional_charges[]'  class='form-control addtl' value='' data-toggle='tooltip' title='eg. Arrastre, Handling rates'/>
						</div>
						<div class='col-sm-4 col-xs-12'>
							<label for='total_amount'>Total Amount</label>
							<input type='number' id='total_amount' name='total_amount'  class='form-control total' value='' readonly/>
						</div>
						
					  <br><br><br><br><hr></div>";

if(!isset($SC)){

	$title = "Add Contract";

	$reference_no = "";
	$type = "";
	$contract_date="";
	$contract_details = "";
	$quantity="";
	$unit =  "";
	$rate = "";
	$parent_contract_id = NULL;
	$vat_type = "";
	$grand_total = "";

	$contract_rates_row = "";//$row_fields;
	$appendable_row	= trim(preg_replace('/\s+/',' ', $row_fields));

}elseif(isset($SC)){

	$title = "Edit Contract";

	$reference_no = $SC['reference_no'];
	$type = $SC['type'];
	$contract_date = $SC['contract_date'];
	$contract_details = $SC['details'];
	$quantity = $SC['quantity'];
	$unit = $SC['unit'];
	$rate = number_format($SC['rate'],2,".","");
	$grand_total = number_format($SC['amount'],2,".","");
	$parent_contract_id = $SC['parent_contract_id'];
	$vat_type = $SC['vat_type'];
	$contract_rates_row="<br>";

	foreach($SC_Rates as $detail){

		$bags=$metric_ton=$kilograms=$days=$months=$hours=$trips = "";
		if($detail['unit']=='Bags'){
			$bags = 'SELECTED';
		}elseif($detail['unit']=='Metric Ton'){
			$metric_ton = 'SELECTED';
		}elseif($detail['unit']=='Kilograms'){
			$kilograms = 'SELECTED';
		}elseif($detail['unit']=='Days'){
			$days = 'SELECTED';
		}elseif($detail['unit']=='Months'){
			$months = 'SELECTED';
		}elseif($detail['unit']=='Hours'){
			$hours = 'SELECTED';
		}elseif($detail['unit']=='Trips'){
			$trips = 'SELECTED';
		}

		$total_amount = ($detail['rate']*$detail['quantity'])+$detail['additional_charges'];

		$contract_rates_row	.= "<div class='row item-row command-row'>
									<div class='col-sm-4 col-xs-12'>
										<label for='warehouse[]'>Warehouse/Port/Destination*</label>
										<input type='text' id='warehouse[]' name='warehouse[]'  class='form-control' value='" . $detail['warehouse'] . "' />
									</div>
									<div class='col-sm-4 col-xs-12'>
										<label for='quantity[]'>Quantity*</label>
										<input type='number' id='quantity[]' name='quantity[]'  class='form-control md-input qty' value='" . $detail['quantity'] . "' />
									</div>";
		$contract_rates_row	.=	"<div class='col-sm-3 col-xs-12'>
										<label for='unit[]'>Unit*</label>
										<select type='text' id='unit[]' name='unit[]' class='form-control' required>
											<option value=''>Select</option>
											<option ". $bags ." value='Bags'>Bags</option>
										 	<option ". $metric_ton ." value='Metric Ton'>Metric Ton</option>
								            <option ". $kilograms ." value='Kilograms'>Kilograms</option>
								            <option ". $days ." value='Days'>Days</option>
								            <option ". $months ." value='Months'>Months</option>
								            <option ". $hours ." value='Hours'>Hours</option>
								            <option ". $trips ." value='Trips'>Trips</option>
										</select>
									</div>";
		$contract_rates_row	.=	"<a class='btn-remove-row btn btn-danger btn-xs col-m-1' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
									<div class='col-sm-4 col-xs-12'>
										<label for='rate[]'>Rate*</label>
										<input type='number' id='rate[]' name='rate[]' class='form-control md-input rte' value='" . $detail['rate'] . "'/>
									</div>
									<div class='col-sm-4 col-xs-12'>
										<label for='charges[]'>Additional Charges</label>
										<input type='number' id='additional_charges[]' name='additional_charges[]'  class='form-control md-input addtl' value='" . $detail['additional_charges'] . "' data-toggle='tooltip' title='eg. Arrastre, Handling rates'/>
									</div>
									<div class='col-sm-4 col-xs-12'>
										<label for='total_amount[]'>Total Amount</label>
										<input type='number' id='total_amount' name='total_amount'  class='form-control total' value='" . $total_amount . "' readonly/>
									</div>
								<br><br><br><br><hr></div>";
	}

	$appendable_row	= trim(preg_replace('/\s+/',' ', $row_fields));
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Contract</title>
</head>
	<style type="text/css">
		.table td,.table th { min-width: 150px;}
	</style>
<body>

<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text><?php echo $title?></text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>

<?php
	// CI Form 
	$attributes = array('id'=>'contract_form','role'=>'form');
	if(!isset($SC)){
		echo form_open_multipart(HTTP_PATH.'operation/service_contract/insert',$attributes);
	}else{
		echo form_open_multipart(HTTP_PATH.'operation/service_contract/update/'.$SC['id'],$attributes);
	}
	echo $this->Mmm->createCSRF();
?>

<div class='panel-body panel'>

	<div class="panel-group" id="contractFormDivider" role="tablist" aria-multiselectable="true">
		<div class="panel panel-info">
			<div class="panel-heading" role="tab" id="contract_info">
				<a role="button" data-toggle="collapse" data-parent="#contractFormDivider" href="#contract_info_tab" aria-expanded="true" aria-controls="contract_info_tab">
				General Information
				<span class="glyphicon glyphicon-chevron-down pull-right"></span>
				</a>		
			</div>
			<div id="contract_info_tab" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="contract_info">
				<div class="panel-body">
			
					<div class='col-md-6 col-sm-6 col-xs-12'>
						<label>Company (Contractor)*</label>
						 <select type='text' id='company' name='company' class='form-control' required <?php if(isset($SC)){echo 'readonly';}?>>
								<?php echo $company_options;?>
						</select>
					</div>

					<div class='col-md-6 col-sm-6 col-xs-12'>
						<label>Client*</label>
						 <select type='text' id='client' name='client' class='form-control' required>
								<?php echo $client_options;?>
						</select>
					</div>

					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Contract Reference No.*</label>
						<input type='text' id='reference_no' name='reference_no' class='form-control' value='<?php echo $reference_no?>'>
					</div>


					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Tax Type*</label>
						 <select type='text' id='vat_type' name='vat_type' class='form-control' required>
						 	<option value="">Select</option>
						 	<option <?php echo ($vat_type=="With VAT")?"SELECTED":""; ?> value='With VAT'>With Value Added Tax</option>
						 	<option <?php echo ($vat_type=="Non-VAT")?"SELECTED":""; ?> value='Non-VAT'>Non-Value Added Tax</option>
						</select>
					</div>

					<div class='col-md-6 col-sm-6 col-xs-12'>
						<label>Service Type*</label>
						 <select type='text' id='service_type' name='service_type' class='form-control' required>
								<?php //echo $service_options;?>
								<option value="">Select</option>
				                <option <?php if($type=='Shipping'){ echo 'selected';}?> value="Shipping">Shipping</option>
				                <option <?php if($type=='Lighterage'){ echo 'selected';}?> value="Lighterage">Lighterage</option>
				                <option <?php if($type=='Time Charter'){ echo 'selected';}?> value="Time Charter">Time Charter</option>
				                <option <?php if($type=='Towing'){ echo 'selected';}?> value="Towing">Towing</option>
				                <option <?php if($type=='Trucking'){ echo 'selected';}?> value="Trucking">Trucking</option>
				                <option <?php if($type=='Handling'){ echo 'selected';}?> value="Handling">Handling</option>
				                <option <?php if($type=='ICHS'){ echo 'selected';}?> value="ICHS">Integrated Cargo Handling Services</option>
				                <option <?php if($type=='Storage'){ echo 'selected';}?> value="Storage">Storage</option>
				                <option <?php if($type=='Equipment Rental'){ echo 'selected';}?> value="Equipment Rental">Equipment Rental</option>
						</select>
					</div>
					
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Contract Date*</label>
						<input type='date' id='contract_date' name='contract_date' class='form-control' value='<?php echo $contract_date?>' required>
					</div>

					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Contract Type*</label>
						 <select type='text' id='contract_type' name='contract_type' class='form-control' required>
						 	<option value="">Select</option>
						 	<option <?php echo ($parent_contract_id==NULL || $parent_contract_id==0)?"SELECTED":""; ?> value="Mother Contract">Mother Contract</option>
						 	<option <?php echo ($parent_contract_id!=0)?"SELECTED":""; ?> value="Sub Contract">Sub Contract</option>
						</select>
					</div>

					<div class='col-md-6 col-sm-6 col-xs-12'>
						<label>Mother Contract Reference No.*</label>
						 <select type='text' id='mother_contract' name='mother_contract' class='form-control' <?php if(!isset($SC['parent_contract_id']) || $SC['parent_contract_id']==0){echo 'disabled';}?>>
						 	<?php echo $contract_options;?>
						</select>
					</div>


					<!--<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Total Quantity</label>
						<input type='number' id='quantity' name='quantity' class='form-control' value='<?php echo $quantity?>' placeholder='If applicable'>
					</div>

					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Unit</label>
						 <select type='text' id='unit' name='unit' class='form-control' required>
						 	<option value="">Select</option>
						 	<option <?php echo ($unit=="Bags")?"SELECTED":""; ?> value='Bags'>Bags</option>
						 	<option <?php echo ($unit=="Metric Ton")?"SELECTED":""; ?> value='Metric Ton'>Metric Ton</option>
				            <option <?php echo ($unit=="Kilograms")?"SELECTED":""; ?> value='Kilograms'>Kilograms</option>
				            <option <?php echo ($unit=="Days")?"SELECTED":""; ?> value='Days'>Days</option>
				            <option <?php echo ($unit=="Months")?"SELECTED":""; ?> value='Months'>Months</option>
				            <option <?php echo ($unit=="Hours")?"SELECTED":""; ?> value='Hours'>Hours</option>
				            <option <?php echo ($unit=="Trips")?"SELECTED":""; ?> value='Trips'>Trips</option>
						</select>
					</div>
					
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Fixed Rate</label>
						<input type='number' id='fixed_rate' name='fixed_rate' class='form-control' value='<?php echo $rate?>' placeholder='If applicable'>
					</div>
					
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Total Contract Amount</label>
						<input type='number' id='amount' name='amount' class='form-control' value='<?php echo $amount?>' placeholder='If applicable'>
					</div>-->



					<div class='col-md-12 col-sm-12 col-xs-12'>
						<label>Contract Details</label>
						<textarea id='contract_details' name='contract_details' class='form-control' rows='6' cols='40'><?php echo $contract_details?></textarea>
					</div>	
				</div>
			</div>
		</div>

		<div class="panel panel-info">
			<div class="panel-heading" role="tab" id="contract_rates">
				<a role="button" data-toggle="collapse" data-parent="#contractFormDivider" href="#contract_rates_tab" aria-expanded="true" aria-controls="contract_rates_tab">
				Contract Rates
				<span class="glyphicon glyphicon-chevron-down pull-right"></span>
				</a>		
			</div>
			<div id="contract_rates_tab" class="panel-collapse collapse" role="tabpanel" aria-labelledby="contract_rates">
				<div style="float:left; margin-top:5px; margin-left:5px">
					<a id="btn_add_row" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
					<a id="btn_remove_row" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
					<a data-placement="bottom" class='hide add_item btn btn-info btn-xs'>Add Item</a>
				</div><div class="clearfix"><br/></div>
				
				<div class="panel-body item-row-container">
					<?php echo $contract_rates_row; ?>
				</div>
				<br>
				<div class='col-sm-5 col-m-4' style='float:right; margin-top:-50px; margin-left:205px'>
					<label class=''>Total Contract Amount</label>
				</div>
				<div class='col-sm-5 col-m-4 pull-right' style='float:right; margin-top:-35px; margin-left:205px'>
					<span class='fa fa-user form-control-feedback left'  aria-hidden='true'>Php</span>
					<input type='text' id='grand_total' name='grand_total' class='form-control gr_total' style='text-align:right;font-size:25px;' value='<?php echo $grand_total; ?>' readonly/>
				</div>
				<br>
			</div>
		</div>

	</div>


</div>

	<div class='col-xs-12 col-sm-12 col-lg-12'>
		<span class='pull-right'>
			<?php 
				if(!isset($SC)){
					echo "<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: validateForm(0)' />";
				}else{
					echo "<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: validateForm(".$SC['id'].")' />";
				}
			?>
			<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
		</span>
	</div>

	<br><br><br>
</form>

</body>
</html>

<script type="text/javascript">

$('#contract_type').on('change', function() {
  	if( this.value == 'Sub Contract' ){
		$('#mother_contract').prop('disabled',false);
	}else{
		$('#mother_contract').prop('disabled',true);
		$('#mother_contract').val("");
	}
});


$("#btn_remove_row").click(function(){
	$('.item-row:last').remove();
	computeTotalAmount();
});
$(document).on('click', '.btn-remove-row', function() {
	$(this).parent().remove();
	computeTotalAmount();
});
	
$("#btn_add_row").click(function(){
	$('.item-row-container').append("<?php echo $appendable_row; ?>");
});

$(document).on('keyup', "div.command-row input", computeTotalAmount);

  function computeTotalAmount() {	 	

  var grand_total =0;

    $("div.command-row").each(function() {

    	var qty = $('.qty',this).val(); 
	  	var rate = $('.rte',this).val();
	  	var addtl = $('.addtl',this).val();
	  	var total_amount = 0;

    	total_amount = parseFloat((qty*rate)+(addtl*1));
    	grand_total = grand_total + total_amount;

    	$('.total',this).val(total_amount);
    	$('#grand_total').val(parseFloat(grand_total).toFixed(2));
    });

 }

function validateForm(id){

	console.log(id);

	var msg="";
	var flag_ref_no = "";
		
	var company=document.getElementById('company').value;	
	if (company==null || company=="") {
		msg+="Company is required! <br/>";
	}
	var client=document.getElementById('client').value;	
	if (client==null || client=="") {
		msg+="Client is required! <br/>";
	}
	var reference_no=document.getElementById('reference_no').value;	
	if (reference_no==null || reference_no=="") {
		msg+="Contract Reference No. is required! <br/>";
	}
	var service_type=document.getElementById('service_type').value;	
	if (service_type==null || service_type=="") {
		msg+="Service Type is required! <br/>";
	}
	var contract_date=document.getElementById('contract_date').value;	
	if (contract_date==null || contract_date=="") {
		msg+="Contract Date is required! <br/>";
	}
	var contract_type=document.getElementById('contract_type').value;	
	if (contract_type==null || contract_type=="") {
		msg+="Contract Type is required! <br/>";
	}else if(contract_type=="Sub Contract"){
		var mother_contract =document.getElementById('mother_contract').value;
		if(mother_contract==null || mother_contract==""){
			msg+="Mother Contract Reference No. is required! <br/>";
		}
	}
	var vat_type=document.getElementById('vat_type').value;
	if (vat_type==null || vat_type=="") {
		msg+="VAT Type is required! <br/>";
	}
	var warehouse = document.getElementsByName('warehouse[]');
	var flag1=0;           
    for(var i = 0; i < warehouse.length; i++){         
        if (warehouse[i].value==""){flag1=1;} 
    }
    if(flag1==1){msg+="All Warehouse fields are required! <br/>";}
    var rate = document.getElementsByName('rate[]');
	var flag2=0;           
    for(var i = 0; i < rate.length; i++){         
        if (rate[i].value=="" || rate[i].value==0){flag2=1;} 
    }
    if(flag2==1){msg+="All Rate fields are required! <br/>";}

    var quantity = document.getElementsByName('quantity[]');
	var flag3=0;           
    for(var i = 0; i < quantity.length; i++){         
        if (quantity[i].value=="" || quantity[i].value==0){flag3=1;} 
    }
    if(flag3==1){msg+="All Quantity fields are required! <br/>";}

    var unit = document.getElementsByName('unit[]');
	var flag4=0;           
    for(var i = 0; i < unit.length; i++){         
        if (unit[i].value==""){flag4=1;} 
    }
    if(flag4==1){msg+="All Unit fields are required! <br/>";}

	$.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>/operation/check_contract_reference_no/"+reference_no+"/"+id,
	     success:function(data){
	        
	        var is_already_exist = $.parseJSON(data);   

	    	if(is_already_exist==true){
				msg +="This Contract Reference No. is already in use. Please use another one. <br/>";
			}
			if(msg!="") {
				toastr['error'](msg,"ABAS Says");
				return false;
			}else {

				$('body').addClass('is-loading'); 
				$('#modalDialog').modal('toggle'); 

				document.forms['contract_form'].submit(); 
				return true;
			}
	     }
  	});
	

}

</script>