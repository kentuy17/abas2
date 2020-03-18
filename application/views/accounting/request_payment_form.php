<?php
   $id = "";
   $reference_no = "";
   $particular = "";
   $amount = "";
   $type = "";
   $payee = "";  
   $voucher_id = "";
   $remark = "";
   $request_date = "";
  // var_dump($remark);exit;
   if(isset($request_payment)){
	   //var_dump($request_payment);exit;
   		
	   $id	=	$request_payment[0]['id'];
	   $reference_no	=	$request_payment[0]['reference_no'];
	   $particular	=	$request_payment[0]['particular'];
	   $amount	=	$request_payment[0]['amount'];
	   $type	=	$request_payment[0]['type'];
	   $payee	=	$request_payment[0]['payee'];
	   $voucher_id	=	$request_payment[0]['voucher_id'];
	   $remark	=	$request_payment[0]['remark'];
	   $request_date = $request_payment[0]['request_date'];
	   
	 }
	 
	 
	 
   ?>
<!--
<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
<script src="<?php echo LINK.'assets/toastr/toastr.js'; ?>"></script> 
-->  

<script>

function validateAmount(v){
		
	var amt = +v;
	if (isNaN(amt)){
    	alert("Invalid amount entered! Please remove comma or string characters.");
		$('#amount').focus();
		return false;
	}
}


</script>

<style>

#required{ color:#FF0000; font-weight:600; font-size:14px

}

</style>

<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'accounting/add_request_payment'; ?>" method="post" >
<?php echo $this->Mmm->createCSRF() ?>
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
								<!--- waybill activity --->
								<div class="panel panel-primary">
                                    <div class="panel-heading" role="tab">
                                        <strong>Request for Payment</strong>
                                        
                                    </div>
                             
                             		<div class="panel-body" role="tab">
												
                                                <div style="margin-top: 16px;">
													<div class="row">
                                                    	<div class="col-sm-6">
                                                        	
                                                             <div class="form-group">
                                                                <label>Select Request Date:</label>
                                                                <div>
                                                                <input class="form-control input-sm" type="text" name="request_date" id="request_date" value="<?php echo $request_date;  ?>" />
                                                                </div>
                                                            </div>
                                                                  
                                                            
                                                            <div class="form-group">
                                                             <label for="category">Select Company <span id="required">*</span> :</label>
                                                             <div>
                                                             <select name="company"  class="form-control input-sm ui-autocomplete-input" value="<?php echo $type;  ?>" required>
                                                                  <option value=""></option>
                                                                  	<?php foreach($companies as $c){ ?>
                                                                  		
                                                                        <?php if($c->id != 10){ ?>
                                                                        <option value="<?php echo $c->id ?>"><?php echo $c->name ?></option>             													 																 	<?php   } } 	?>	
                                                                </select>
                                                             </div>
                                                          </div>
                                                        
                                                        <div class="form-group">
                                                                <label>Amount <span id="required">*</span> :</label>
                                                                <div><input type="text" name="amount" id="amount" class="form-control input-sm ui-autocomplete-input" value="<?php echo $amount;  ?>" onchange="javascript:validateAmount(this.value)"  required />
                                                                </div>
                                                            </div>	
                                                        
                                                          
                                                        <div class="form-group">
														<label>Payee Type <span id="required">*</span> :</label>
														<div>
                                                        <select name="payee_type" id="payee_type"  class="form-control input-sm" required 
                                                        onchange="
                                                        	var t = this.value;
                                                           
                                                            if(t == 'Supplier'){
                                                            	$('#payee_others').hide();
                                                                $('#payee_employee').hide();
                                                                $('#payee_supplier').show();  
                                                                $('#payee_title').show();                                                  
                                                            }
                                                            if(t == 'Employee'){
                                                            	$('#payee_others').hide();
                                                                $('#payee_supplier').hide();
                                                                $('#payee_employee').show(); 
                                                                $('#payee_title').show();                                                   
                                                            }
                                                            if(t == 'Others'){
                                                            	
                                                                $('#payee_supplier').hide();
                                                                $('#payee_employee').hide();
                                                                $('#payee_others').show(); 
                                                                $('#payee_title').show();                                                   
                                                            }
                                                        ">
                                                                  <option value=""></option>
                                                                  
                                                                  <option value="Supplier">Supplier/Service Provider</option>
                                                                  <option value="Employee">Employee</option>
                                                                  <!--
                                                                  <option value="Others">Others</option>
                                                                  --->
                                                                </select>
                                                        
                                                        	
                                                       			
														</div>
													</div>	
                                                    <div class="form-group">
														<label id="payee_title" style="display:none">Select Payee (type name) <span id="required">*</span> :</label>
														<div><input type="text" name="payee_others" id="payee_others" class="form-control input-sm ui-autocomplete-input" style="display:none" />			
                                                        <input type="text" name="payee_supplier" id="payee_supplier" class="form-control input-sm ui-autocomplete-input" style="display:none"  />			
                                                        <input type="text" name="payee_employee" id="payee_employee" class="form-control input-sm ui-autocomplete-input" style="display:none"  />		
                                                         <input type="hidden" name="payee_id" id="payee_id" class="form-control input-sm"  />			
                                                        <input type="hidden" name="employee_id" id="employee_id" class="form-control input-sm"  />
														</div>
													</div>												
													<div class="form-group">
														<label>Purpose <span id="required">*</span> :</label>
														<div><input type="text" name="purpose" class="form-control input-sm ui-autocomplete-input" value="<?php echo $remark;  ?>" required/>
														</div>
													</div>
                                                    
                                                    <div class="form-group" style="display:none">
														<label>Apply Expanded Tax (select tax percentage if applicable):</label>
														<div>
                                                        <select name="etax" id="etax"  class="form-control input-sm">
                                                                  <option value=""></option>
                                                                  <option value="12">1%</option>
                                                                  <option value="2">2%</option>                                                                  
                                                                  <option value="5">5%</option>
                                                                  <option value="10">10%</option>
                                                                  
                                                        </select>
														</div>
													</div>
                                                          
                                                           
                                                          
                                                            
                                                            
                                                            
                                                            
												 	 	</div>
												  <div class="col-sm-6">
												 	
                                                    <div class="form-group">
                                                            	<label>Reference No. (enter transaction code):</label> <i></i>
                                                                <div>
                                                                    <input class="form-control input-sm" name="reference_no" class="form-control abas_Formcontrol" value="<?php echo $reference_no;  ?>">
                                                                </div>
                                                        	</div>
                                                    <div class="form-group">
                                                             <label for="category">Reference Document Type:</label>
                                                             <div>
                                                             <select name="reference_type"  class="form-control input-sm">
                                                                  <option value=""></option>
                                                                  <option value="inventory_po">Purchase Order</option>
                                                                   <option value="inventory_job_orders">Job Order</option>
                                                                  <option value="hr_payroll">Payroll</option>   
                                                                  <option value="service_contracts">Contract</option>
                                                             </select>
                                                         </div>
                                                      </div>
                                                     
													<div class="form-group">
                                                             <label for="category">Request for? (optional):</label>
                                                             <div>
                                                             <select name="vessel_id"  class="form-control input-sm ui-autocomplete-input" value="<?php echo $type;  ?>">
                                                                  <option value="">Select vessel or office</option>
                                                                  <?php foreach($office as $f){ ?>
                                                                  <option value="<?php echo $f->id ?>"><?php echo $f->name ?></option>
                                                                  <?php } ?>
                                                                </select>
                                                             </div>
                                                          </div>	
                                                   
                                                    <div class="form-group">
														<label>Remark (optional):</label>
														<div><input type="text" name="remark" class="form-control input-sm ui-autocomplete-input" value="<?php echo $remark;  ?>" />
														</div>
													</div>
																											
													</div>															
													</div>															
												</div>
											</div>
                                           
											<div class="modal-footer">
                                             <strong style="color:#0033FF">Required (<span id="required">*</span>)</strong>&nbsp;&nbsp;&nbsp;
												  <button type="button" class="btn btn-default" style="background-color:#FF0000; color:#FFFFFF" data-dismiss="modal" onclick="myFunction()">Cancel</button>
												  <button type="submit" class="btn btn-primary" style="width:150px">Save</button>
											</div>
										</div>
									</div>
					</div>
</form>




<style>

.autocomplete {
    z-index: 5000;
}

.ui-autocomplete {
  z-index: 215000000 !important;
}
</style>

	<script type="text/javascript">

		$(document).ready(function () {

				$( "#payee_supplier" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>inventory/supplier_data",
					minLength: 2,
					search: function(event, ui) {
						toastr['info']('Loading, please wait...');
					},
					response: function(event, ui) {
						toastr.clear();
					},
					select: function( event, ui ) {
						// alert(ui.item.value);
						//alert(ui.item.value);
						$( "#payee_supplier" ).val( ui.item.label );
						$( "#payee_id" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						$("#remark").focus();
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
							toastr['warning']	=	"No suppliers found";
						}
						else {
							toastr.clear();
						}
					},
					select: function( event, ui ) {
						$( this ).val( ui.item.label );
						$( "#payee_employee" ).val( ui.item.label );
						$( "#payee_id" ).val( ui.item.value );
						return false;
					}
				});
				
				 $( "#request_date" ).datepicker();
				
		});
		
		
		

	</script>

<input type="hidden" id="selitem" name="selitem" />