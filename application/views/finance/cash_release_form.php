<?php
   $id = "";
   $date_requested= "";
   $date_released = "";
   $requested_by = "";
   $requested_by_val = "";
   $purpose = "";
   $amount = "";
   $department = "";
   $department_val = "";
   $type = "";
   $voucher_id = '';
   
   if(isset($cash)){
   		//var_dump($item[0]['id']);exit;l
   		$id = $cash[0]['id'];
   		$requested_by = $cash[0]['requested_by'];
		
   		$purpose = $cash[0]['purpose'];
   		$amount = $cash[0]['amount'];
   		$department = $cash[0]['department'];
		
   		$type = $cash[0]['type'];
		$voucher_id = $cash[0]['voucher_id'];
   		//var_dump($date_released);exit;
		$requested_by = $this->Abas->getEmployee($cash[0]['requested_by']);
		$department  = $this->Abas->getDepartments($cash[0]['department']);
		$requested_by_val = $requested_by['full_name'];
		$department_val = $department[0]->name;
   }
   ?>
<style>
   .modal-dialog{width: 400px !important;height: 300px;}
</style>
<form class="form-horizontal" role="form" name="cashForm" id="cashForm" action="<?php echo HTTP_PATH.'finance/cr_release'; ?>" method="post" style="height: 400px;">
	
   
      
         <div class="panel panel-primary" style="width:700px; height:401px; font-size:15px">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Cash Request Release Form&nbsp;</strong>
            </div>
            <div class="panel-body" role="tab" style="background:#F4F4F4">
			
               
			   <div class="row">
			    <div class="col-md-6">
               
				   	<div class="form-group">
                     	<label>Type of Request:</label>					 
					 	<?php echo $type; ?>                     
                  	</div>
                  	<div class="form-group">
                     	<label>Requested by:</label>
                    	<?php echo $requested_by['full_name']; ?>   
                    </div>
				  	<div class="form-group">
                     	<label>Department:</label>                    
                     	<?php echo $department_val; ?>               
                  	</div>
                  	<div class="form-group">
                    	<label>Amount:</label>
                    	<?php echo number_format($amount,2); ?>   
                    </div>
                    <div class="form-group">
                    	<label>Purpose:</label>
                    	<?php echo $purpose; ?>                               
                    </div>
                    
                    <div class="form-group">
                    	<label>Date Approved:</label>
                    	<?php //echo $purpose; ?>                               
                    </div>
                    
               	</div>
            
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal" onclick="myFunction()">Close</button>
               <button type="button" class="btn btn-primary"  onclick="
               		
                    if(confirm('You are about to release payment of this request.')){
                    	document.forms['cashForm'].submit();
                    }else{
                    	return false;
                    }
               
               	">Release</button>
            </div>
         </div>
     
   
   <?php echo $this->Mmm->createCSRF() ?>
   	<input type="hidden" name="stat" value="1">
   	<input type="hidden" name="id" value="<?php echo $id;  ?>">
   	<input type="hidden" name="voucher_id" value="<?php echo $voucher_id;  ?>">
  
</form>
	<script type="text/javascript">

		$(document).ready(function () {

				$( "#autocomplete" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>finance/deparment_data",
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
						$( "#autocomplete" ).val( ui.item.label );
						$( "#department_val" ).val( ui.item.value );
					
						//document.getElementById("autocomplete").value = ui.item.label;
						//$("#qty").focus();
						return false;
					}
				});
				
				$( "#autocomplete1" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>finance/name_data",
					minLength: 2,
					search: function(event, ui) {
						toastr['info']('Loading, please wait...');
					},
					response: function(event, ui) {
						toastr.clear();
					},
					select: function( event, ui ) {
					
						//alert(ui.item.value);
						$( "#autocomplete1" ).val( ui.item.label );
						$( "#requested_by_val" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						//$("#qty").focus();
						return false;
					}
				});
		});

		

	</script>

<style>

.autocomplete {
    z-index: 5000;
}

.ui-autocomplete {
  z-index: 215000000 !important;
}


</style>											
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
<input type="hidden" id="selitem" name="selitem" />



