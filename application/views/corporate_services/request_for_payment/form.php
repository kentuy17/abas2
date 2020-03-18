<?php
   $r			=	array("company_id"=>"","vessel_id"=>"");


	$heading	=	"Add Request for Payment";
	$action		=	HTTP_PATH."Corporate_Services/request_for_payment/insert";
	$request_date = "";
	$payee_type = "";
	$payee_id = "";
	$payee = "";
	$purpose = "";
	$payee_type = "";
	$reference_table = "";
	$reference_id= "";
	$total_amount = 0;
	$vesseloptions	=	"";
	if(!empty($vessels)) {
		foreach($vessels as $v) {
			$vesseloptions	.=	"<option value='".$v->id."'>".$v->name."</option>";
		}
	}
	$companyoptions	=	"";
	if(!empty($companies)) {
		foreach($companies as $c) {
			if(isset($request)){
				$companyoptions	.=	"<option ".($request[0]['company_id']==$c->id ? "selected":"")." value='".$c->id."'>".$c->name."</option>";
			}else{
				$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
			}
		}
	}
  

   $rowfields ="";
   $rowfields .="<div class='row detail-row command-row'>
					<div class='col-sm-11 col-xs-12'>
						<label>Particulars*</label>
						<input type='text' id='particulars[]' name='particulars[]' class='form-control md-input particular' value='' required/>
					</div>
					<div class='col-sm-6 col-xs-12'>
						<label>Charge To</label>
						<select id='charge_to[]' name='charge_to[]' class='form-control md-input' required>
							<option val=''></option>
							".$vesseloptions."
						</select>
					</div>
					<div class='col-sm-5 col-xs-12'>
						<label>Amount*</label>
						<input type='number' max='999999999' id='amount[]' name='amount[]' class='form-control md-input amount' value='0' required/>
					</div>
					<div class='col-sm-12 col-xs-12'>
					<hr>
					</div>
					<a class='btn-remove-row btn btn-danger btn-xs col-m-1' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
				  </div>";
	$detailform		=	$rowfields;
	$appendable	= trim(preg_replace('/\s+/',' ', $rowfields));

	$rowfields_attach ="";
	$rowfields_attach .="<div class='row detail-row_attach command-row'>
						<div class='col-sm-11 col-xs-12'>
						<label>Document Name/Remarks:</label>
							<input type='text' name='file_name[]' id='file_name[]' class='form-control' placeholder='Type of the file (eg. PO, OR, Invoice, etc.)' required>
						</div>
						<div class='col-sm-11 col-xs-12'>
						<br>
							<input type='file' name='attach_file[]' id='attach_file[]' class='' accept='image/*,application/pdf,application/vnd.ms-excel' required>
						</div>
						<div class='col-sm-12 col-xs-12'>
							<hr>
						</div>
						<a class='btn-remove-row_attach btn btn-danger btn-xs col-m-1' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a></div";
	$detailform_attach		=	$rowfields_attach;
	$appendable_attach	= trim(preg_replace('/\s+/',' ', $rowfields_attach));

if(isset($request)){
	$heading = "Edit Request for Payment";
	$action		=	HTTP_PATH."Corporate_Services/request_for_payment/update/".$request[0]['id'];
	$request_date = date('Y-m-d',strtotime($request[0]['request_date']));
	$payee_type = $request[0]['payee_type'];
	if($request[0]['payee']<>''){
		$payee = $request[0]['payee'];
		$payee_id = $request[0]['payee'];
	}else{
		$payee_id = '';
		$payee = $request[0]['payee_others'];
	}
	$purpose = $request[0]['purpose'];
	$payee_type = $request[0]['payee_type'];
	$reference_table = $request[0]['reference_table'];
	$reference_id= ($request[0]['reference_id']==0)?'N/A':$request[0]['reference_id'];
	if($reference_table=='inventory_po'){
		$reference_document		=	"<option value='inventory_po'>Purchase Order</option>";
	}elseif($reference_table=='inventory_job_orders'){
		$reference_document		=	"<option value='inventory_job_orders'>Job Order</option>";
	}elseif($reference_table=='hr_payroll'){
		$reference_document		=	"<option value='hr_payroll'>Payroll</option>";
	}elseif($reference_table=='service_contracts'){
		$reference_document		=	"<option value='service_contracts'>Contract</option>";
	}

	$rowfields_edit='';
	if(isset($request_details)){
		$total_amount = 0;
		foreach($request_details as $detail){
			if($detail['charge_to']!=0 && $detail['charge_to']!=''){
				$vessel = $this->Abas->getVessel($detail['charge_to']);
				$vessel_name = $vessel->name;
				$vessel_id = $detail['charge_to'];
			}else{
				$vessel_id = "";
				$vessel_name = "";
			}
			 $rowfields_edit .="<div class='row detail-row command-row'>
					<div class='col-sm-11 col-xs-12'>
						<label>Particulars*</label>
						<input type='text' id='particulars[]' name='particulars[]' class='form-control md-input particular' value='".$detail['particulars']."' required/>
					</div>
					<div class='col-sm-6 col-xs-12'>
						<label>Charge To</label>
						<select id='charge_to[]' name='charge_to[]' class='form-control md-input' required>
							<option value='".$vessel_id."' selected>".$vessel_name."</option>
							<option value=''></option>
							".$vesseloptions."
						</select>
					</div>
					<div class='col-sm-5 col-xs-12'>
						<label>Amount*</label>
						<input type='number' max='999999999' id='amount[]' name='amount[]' class='form-control md-input amount' value='".$detail['amount']."' required/>
					</div>
					<div class='col-sm-12 col-xs-12'>
						<hr>
					</div>
					<a class='btn-remove-row btn btn-danger btn-xs col-m-1' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
				  </div>";
				  $total_amount = $total_amount + $detail['amount'];
		}
		$detailform_edit	=	$rowfields_edit;
	}
	if(isset($request_attachments)){
		$rowfields_attach_edit = '';
		foreach($request_attachments as $attachment){
			$rowfields_attach_edit .="<div class='row detail-row_attach command-row'>
						<div class='col-sm-11 col-xs-12'>
							<label>Document Name/Remarks:</label>
							<input type='text' name='file_name[]' id='file_name[]' class='form-control' placeholder='Type of the file (eg. PO, OR, Invoice, etc.)' value='".$attachment['file_name']."'>
						</div>
						<div class='col-sm-11 col-xs-12'>
							<br>Attached file: 
							<input type='text' name='attach_file_name[]' id='attach_file_name[]' style='border:0' value='".$attachment['file_path']."' readonly>
						</div>
						<div class='col-sm-12 col-xs-12'>
							<hr>
						</div>
						<a class='btn-remove-row_attach btn btn-danger btn-xs col-m-1' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
						</div>";
		}
		$detailform_attach_edit		=	$rowfields_attach_edit;
	}
}
?>
<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title"><?php echo $heading ?><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2></div></div>
	<div class='panel-body'>
		<form action='<?php echo $action; ?>' role='form' method='POST' id='request_for_payment_form' enctype='multipart/form-data'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="panel-group" id="rfpFormDivider" role="tablist" aria-multiselectable="true">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="summary">
						<a role="button" data-toggle="collapse" data-parent="#rfpFormDivider" href="#rfpSummary" aria-expanded="true" aria-controls="rfpSummary">
						Summary
						<span class="glyphicon glyphicon-chevron-down pull-right"></span>
						</a>
					</div>
					<div id="rfpSummary" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="summary">
						<div class="panel-body" id="summary_container">
							<div class='col-xs-12 col-sm-12 col-md-3'>
								<label for='request_date'>Request Date*</label>
								<input type='date' id='request_date' name='request_date'  class='form-control'
									<?php 
										if(isset($request)){
											echo "value='".$request_date."'";
											echo "disabled"; 
										}
									?> 
								required />
							</div>
							<div class='col-xs-12 col-sm-12 col-md-9'>
								<label for='company'>Company*</label>
								<select name='company' id='company' class='form-control'<?php if(isset($request)){echo 'disabled';}?> required>
									<option value=''>Select</option>
									<?php echo $companyoptions; ?>
								</select>
							</div>
							<div class='col-xs-12 col-sm-6 col-md-3'>
								<label for='payee_type'>Payee Type*</label>
								<select name='payee_type' id='payee_type' class='form-control' required <?php if(isset($request)){echo "readonly";}?>>
									<option value=''>Select</option>
									<option value='Supplier' <?php if($payee_type=='Supplier'){echo "selected";}?>>Supplier</option>
									<option value='Employee' <?php if($payee_type=='Employee'){echo "selected";}?>>Employee</option>
								</select>
							</div>
							<div class='col-xs-12 col-sm-12 col-md-9'>
								<label for='payment_to'>Payment To*</label>
								<?php if(!isset($request)){ ?>
									<input type="text" name="payee_default" id="payee_default" class="form-control" readonly placeholder="Input the name of payee">
									<input type="text" name="payee_supplier" id="payee_supplier" class="form-control ui-autocomplete-input" style="display:none">	
				               		 <input type="text" name="payee_employee" id="payee_employee" class="form-control ui-autocomplete-input" style="display:none">
								<?php }else{
									if($payee_type=='Supplier'){
										echo '<input type="text" name="payee_supplier" id="payee_supplier" class="form-control ui-autocomplete-input" value="'.$payee.'" disabled>';
									}elseif($payee_type=='Employee'){
										echo '<input type="text" name="payee_employee" id="payee_employee" class="form-control ui-autocomplete-input" value="'.$payee.'" disabled>';
									}
								}?>
				                <input type="hidden" name="payee_id" id="payee_id" class="form-control input-sm" value="<?php echo $payee_id;?>">     
							</div>
							<div class='col-xs-12 col-sm-12 col-md-3'>
								<label for='reference_document'>Reference Document*</label>
								<select name='reference_document' id='reference_document' class='form-control' required <?php if(isset($request)){echo "disabled";}?>>
									<?php 
										if(isset($request)){ 
											echo $reference_document;
										}
									?>
									<option value='none'>None</option>
								</select>
							</div>
							<div class='col-xs-12 col-sm-12 col-md-9'>
								<label for='reference_id'>Reference Transaction*</label>
								<?php if(!isset($request)){ ?>
									<input type="text" name="reference_id_default" id="reference_id_default" class="form-control" readonly placeholder="Input the transaction code number">
									<input type="text" name="reference_id_purchase_order" id="reference_id_purchase_order" class="form-control ui-autocomplete-input2" style="display:none">	
					                <input type="text" name="reference_id_job_order" id="reference_id_job_order" class="form-control ui-autocomplete-input2" style="display:none">	
					                <input type="text" name="reference_id_payroll" id="reference_id_payroll" class="form-control ui-autocomplete-input2" style="display:none">
					                <input type="text" name="reference_id_contract" id="reference_id_contract" class="form-control ui-autocomplete-input2" style="display:none">
					            <?php }else{
					            	if($reference_table=='inventory_po'){
					            		echo '<input type="text" name="reference_id_purchase_order" id="reference_id_purchase_order" class="form-control ui-autocomplete-input2" value="'.$reference_id.'" disabled>';
					            	}elseif($reference_table=='inventory_job_orders'){
					            		echo ' <input type="text" name="reference_id_job_order" id="reference_id_job_order" class="form-control ui-autocomplete-input2" value="'.$reference_id.'" disabled>';
					            	}elseif($reference_table=='hr_payroll'){
					            		echo '<input type="text" name="reference_id_payroll" id="reference_id_payroll" class="form-control ui-autocomplete-input2" value="'.$reference_id.'" disabled>';
					            	}elseif($reference_table=='service_contracts'){
					            		echo '<input type="text" name="reference_id_contract" id="reference_id_contract" class="form-control ui-autocomplete-input2" value="'.$reference_id.'" disabled>';
					            	}
					            }
					            ?>
				                <input type="hidden" name="reference_id" id="reference_id" class="form-control input-sm">
							</div>
							<div class='col-xs-12 col-sm-12 col-md-12'>
								<label for='purpose'>Purpose</label>
								<textarea id='purpose' name='purpose' class='form-control'><?php echo $purpose?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="details">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#rfpFormDivider" href="#rfpDetails" aria-expanded="false" aria-controls="rfpDetails">
						Details
						<span class="glyphicon glyphicon-chevron-down pull-right"></span>
						</a>
					</div>
					<div id="rfpDetails" class="panel-collapse collapse" role="tabpanel" aria-labelledby="details">
						<div style="float:left; margin-top:5px; margin-left:5px" class="pull-right">
							<a id="btn_add_row" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
							<a id="btn_remove_row" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
						</div>
						<div class="clearfix"><br/></div>
						<div class="panel-body detail-row-container" id="details_container">
							<?php
								if(!isset($request)){
									echo $detailform;
								}else{
									echo $detailform_edit;
								}
							?>
						</div>
						<div class='panel-body'>
							<div class='col-sm-4 col-m-3 pull-right'>
								<label class=''>Total Amount</label>
								<input type='text' id='total_amount' name='total_amount' class='form-control' value='<?php echo "PHP ". number_format($total_amount,2,'.',',');?>'style='text-align:right;font-size:20px;'  readonly/>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="attachments">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#rfpFormDivider" href="#rfpAttachments" aria-expanded="false" aria-controls="rfpAttachments">
						Attachments
						<span class="glyphicon glyphicon-chevron-down pull-right"></span>
						</a>
					</div>
					<div id="rfpAttachments" class="panel-collapse collapse" role="tabpanel" aria-labelledby="details">
						<div style="float:left; margin-top:5px; margin-left:5px" class="pull-right">
							<a id="btn_add_row_attach" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
							<a id="btn_remove_row_attach" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
						</div>
						<div class="clearfix"><br/></div>
						<div class="panel-body detail-row-container_attach" id="details_container">
							<div class="alert alert-info alert-dismissible fade in" role="alert">
		            			<strong>Note:</strong> Please do not forget to attach all reference documents for this request. 
		          	  		</div>
							<?php
								if(!isset($request)){
									echo $detailform_attach;
								}else{
									echo $detailform_attach_edit;
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<br>
				<span class="pull-right">
					<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript:checkautoform();'/>
					<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
				</span>
			</div>
		</form>
	</div>

<script>


	$("#btn_remove_row").click(function(){
		$('.detail-row:last').remove();
	});
	$(document).on('click', '.btn-remove-row', function() {
		$(this).parent().remove();
	});
	$("#btn_add_row").click(function(){
		$('.detail-row-container').append("<?php echo $appendable; ?>");
	});

	$("#btn_remove_row_attach").click(function(){
		$('.detail-row_attach:last').remove();
	});
	$(document).on('click', '.btn-remove-row_attach', function() {
		$(this).parent().remove();
	});
	$("#btn_add_row_attach").click(function(){
		$('.detail-row-container_attach').append("<?php echo $appendable_attach; ?>");
	});


	$( "#payee_type" ).change(function(){
		var payee = this.value;
	    if(payee == 'Supplier'){
	    	$('#payee_default').hide();
	        $('#payee_employee').hide();
	        $('#payee_supplier').show();
	        $('#payee_supplier').val(""); 
	        $('#payee_employee').val("");  
	        $('#payee_id').val("");  
	        $('#reference_document').empty(); 
	        $('#reference_document').append('<option value="none" selected>None</option>');
	        $('#reference_document').append('<option value="inventory_po">Purchase Order</option>');
	        $('#reference_document').append('<option value="inventory_job_orders">Job Order</option>'); 
	        $('#reference_document').append('<option value="service_contracts">Contract</option>');     
	        $('#reference_id_default').show();
	        $('#reference_id_purchase_order').hide();
	        $('#reference_id_job_order').hide();
	        $('#reference_id_payroll').hide(); 
	        $('#reference_id_contract').hide();
	        $('#reference_id_purchase_order').val(""); 
	        $('#reference_id_job_order').val(""); 
	        $('#reference_id_payroll').val(""); 
	        $('#reference_id_contract').val(""); 
	        $('#reference_id').val("");                   
	    }
	    if(payee == 'Employee'){
	    	$('#payee_default').hide();
	        $('#payee_supplier').hide();
	        $('#payee_employee').show(); 
	        $('#payee_supplier').val(""); 
	        $('#payee_employee').val("");  
	        $('#payee_id').val("");
	        $('#reference_document').empty(); 
	        $('#reference_document').append('<option value="none" selected>None</option>');
	        $('#reference_document').append('<option value="hr_payroll">Payroll</option>');
	        $('#reference_document').append('<option value="service_contracts">Contract</option>');     
	        $('#reference_id_default').show();
	        $('#reference_id_purchase_order').hide();
	        $('#reference_id_job_order').hide();
	        $('#reference_id_payroll').hide(); 
	        $('#reference_id_contract').hide();
	        $('#reference_id_purchase_order').val(""); 
	        $('#reference_id_job_order').val(""); 
	        $('#reference_id_payroll').val(""); 
	        $('#reference_id_contract').val(""); 
	        $('#reference_id').val("");    
	    }
	    if(payee == ''){
	    	$('#payee_default').show();
	        $('#payee_supplier').hide();
	        $('#payee_employee').hide();
	        $('#payee_supplier').val(""); 
	        $('#payee_employee').val("");  
	        $('#payee_id').val("");
	        $('#reference_document').empty(); 
	        $('#reference_document').append('<option value="none" selected>None</option>');
	        $('#reference_id_default').show();
	        $('#reference_id_purchase_order').hide();
	        $('#reference_id_job_order').hide();
	        $('#reference_id_payroll').hide(); 
	        $('#reference_id_contract').hide();
	        $('#reference_id_purchase_order').val(""); 
	        $('#reference_id_job_order').val(""); 
	        $('#reference_id_payroll').val(""); 
	        $('#reference_id_contract').val(""); 
	        $('#reference_id').val("");   
	    }
	});

	$( "#reference_document" ).change(function(){
		var table = this.value;
		
	    if(table == 'inventory_po'){
	    	$('#reference_id_default').hide();
	        $('#reference_id_purchase_order').show();
	        $('#reference_id_job_order').hide();
	        $('#reference_id_payroll').hide(); 
	        $('#reference_id_contract').hide();
	        $('#reference_id_purchase_order').val(""); 
	        $('#reference_id_job_order').val(""); 
	        $('#reference_id_payroll').val(""); 
	        $('#reference_id_contract').val(""); 
	        $('#reference_id').val("");                                           
	    }
	    if(table == 'inventory_job_orders'){
	    	$('#reference_id_default').hide();
	        $('#reference_id_purchase_order').hide();
	        $('#reference_id_job_order').show();
	        $('#reference_id_payroll').hide(); 
	        $('#reference_id_contract').hide();
	        $('#reference_id_purchase_order').val(""); 
	        $('#reference_id_job_order').val(""); 
	        $('#reference_id_payroll').val(""); 
	        $('#reference_id_contract').val(""); 
	        $('#reference_id').val("");                                                 
	    }
	    if(table == 'hr_payroll'){
	    	$('#reference_id_default').hide();
	        $('#reference_id_purchase_order').hide();
	        $('#reference_id_job_order').hide();
	        $('#reference_id_payroll').show(); 
	        $('#reference_id_contract').hide();
	        $('#reference_id_purchase_order').val(""); 
	        $('#reference_id_job_order').val(""); 
	        $('#reference_id_payroll').val(""); 
	        $('#reference_id_contract').val(""); 
	        $('#reference_id').val("");                                                 
	    }
	    if(table == 'service_contracts'){
	    	$('#reference_id_default').hide();
	        $('#reference_id_purchase_order').hide();
	        $('#reference_id_job_order').hide();
	        $('#reference_id_payroll').hide(); 
	        $('#reference_id_contract').show();
	        $('#reference_id_purchase_order').val(""); 
	        $('#reference_id_job_order').val(""); 
	        $('#reference_id_payroll').val(""); 
	        $('#reference_id_contract').val(""); 
	        $('#reference_id').val("");                                                
	    }
	    if(table == ''){
	    	$('#reference_id_default').show();
	        $('#reference_id_purchase_order').hide();
	        $('#reference_id_job_order').hide();
	        $('#reference_id_payroll').hide(); 
	        $('#reference_id_contract').hide();
	        $('#reference_id_purchase_order').val(""); 
	        $('#reference_id_job_order').val(""); 
	        $('#reference_id_payroll').val(""); 
	        $('#reference_id_contract').val(""); 
	        $('#reference_id').val("");                                             
	    }
	});
	
	$( "#payee_supplier" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>inventory/supplier_data",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr['warning']	=	"No supplier found!";
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$( "#payee_supplier" ).val( ui.item.label );
			$( "#payee_id" ).val( ui.item.value );
			return false;
		}
	});
		
	$( "#payee_employee" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>hr/employee_autocomplete_list",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr['warning']	=	"No suppliers found!";
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$( "#payee_employee" ).val( ui.item.label );
			$( "#payee_id" ).val( ui.item.value );
			return false;
		}
	});

	$( "#reference_id_purchase_order" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>purchasing/purchase_order_autocomplete_list/for_delivery",
		minLength: 1,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr['warning']	=	"No PO found!";
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$( "#reference_id_purchase_order" ).val( ui.item.label );
			$( "#reference_id" ).val( ui.item.value );
			return false;
		}
	});

	$( "#reference_id_job_order" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>purchasing/job_order_autocomplete_list/for_delivery",
		minLength: 1,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr['warning']	=	"No JO found!";
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$( "#reference_id_job_order" ).val( ui.item.label );
			$( "#reference_id" ).val( ui.item.value );
			return false;
		}
	});

	$( "#reference_id_payroll" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>payroll/payroll_autocomplete_list/approved",
		minLength: 1,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr['warning']	=	"No Payroll found!";
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$( "#reference_id_payroll" ).val( ui.item.label );
			$( "#reference_id" ).val( ui.item.value );
			return false;
		}
	});

	$( "#reference_id_contract" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>operation/service_contract_autocomplete_list/1",
		minLength: 1,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr['warning']	=	"No Contract found!";
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$( "#reference_id_contract" ).val( ui.item.label );
			$( "#reference_id" ).val( ui.item.value );
			return false;
		}
	});

	$(document).on('keyup', "div.command-row input", multInputs);

	  function multInputs() {	 	

	  	var previous_amount = 0;

	    $("div.command-row").each(function() {
	    	if($('.amount', this).val()>0){
	    	  var amount  = $('.amount', this).val();
		      amount  = parseFloat(amount) + parseFloat(previous_amount);
		      previous_amount = amount;
		      var total_amount = parseFloat(amount).toFixed(2);
			  document.getElementById("total_amount").value = "PHP "+ formatNumber(total_amount);
			}
	    });
	    	
	  }

  	function formatNumber (num) {
		return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
  	}
	
	function checkautoform() {
		var msg="";
		var summary_flag=0;
		var summary_inputs = $('#summary_container').find('input').filter('[required]');
		var summary_selects = document.getElementById('summary_container').getElementsByTagName('select');
		var detail_divs = document.getElementsByClassName('detail-row'); 
    	var detail_inputs = $('.detail-row-container').find('input').filter('[required]');
    	var detail_flag=0;
    	var no_detail_flag=0;
		for(var x = 0; x < summary_inputs.length; x++){
        	if (summary_inputs[x].value==""){
            	summary_flag=1;
            }
        }
    	if(detail_divs.length > 0){
	        for(var y = 0; y < detail_inputs.length; y++){
	        	if (detail_inputs[y].value=="" || detail_inputs[y].value==0 ){
	            	detail_flag=1;
	            }
	        }
	    }else{
	    	no_detail_flag =1;
	    }
        for(var i = 0; i < summary_selects.length; i++){         
            if (summary_selects[i].value==""){
            	summary_flag=1;
            } 
        }
	    if(summary_flag==1){
        	msg+="Please fill-out all required fields (*) in Summary Tab!<br/>";
    	}
	    if(detail_flag==1){
        	msg+="Please fill-out all required fields (*) in Details Tab!<br/>";
    	}
    	if(no_detail_flag==1){
        	msg+="Please add at least one(1) particular in Details Tab!<br/>";
    	}
		if(msg!="") {
			toastr["error"](msg,"ABAS Says");
			return false;
		}
		else{
			$('body').addClass('is-loading');
			$('#modalDialog').modal('toggle');
			$("#request_for_payment_form").submit();
			return true;
		}
	}
	
</script>
