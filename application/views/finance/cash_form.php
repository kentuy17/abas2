<?php
   $id = "";
   $date_requested= "";
   $date_released = "";
   $requested_by = "";
   $requested_by_val = "";
   $purpose = "";
   $amount = "";
   $dept = "";
   $dep_name = "";
   $department_val = "";
   $type = "";
   $warehouse_id = '';
   $warehouse_name = '';
   
   if(isset($cash)){
   		//var_dump($item[0]['id']);exit;l
   		$id = $cash[0]['id'];
   		$requested_by = $cash[0]['requested_by'];
		$requested_by_val = '';
   		$purpose = $cash[0]['purpose'];
   		$amount = $cash[0]['amount'];
   		$dept = $cash[0]['department'];
		$dep_name = $this->Abas->getVessel($cash[0]['department']);
   		$type = $cash[0]['type'];
		$warehouse_id = $cash[0]['warehouse'];
	   	$warehouse_name = $this->Abas->getWarehouse($cash[0]['warehouse']);
		
		
   		//var_dump($warehouse_name);exit;


   }
   
   
   
 // $this->load->view('includes_header');
   
   ?>
<!---
<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
<script src="<?php echo LINK.'assets/toastr/toastr.js'; ?>"></script>  
--->
<style>
   .modal-dialog{width: 600px !important;height: 300px; margin-top:0px}
   .modal-header{background:#3A5276; color:#FFFFFF}
</style>


<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Cash Request</h4>
            </div>			<!-- /modal-header -->
            <div class="modal-body clearfix">
            	
               <form class="form-horizontal" role="form" name="cashForm" id="cashForm" action="<?php echo HTTP_PATH.'finance/add_cash'; ?>" method="post" >
	<?php echo $this->Mmm->createCSRF() ?>
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
  
			
               <div style="width:466px; margin-left:20px; float:left">
			   <div class="row">
			    <div class="col-md-6">
               
				   <div class="form-group">
                     <label>Type of Request:</label>
					 <select name="type" id="type"  class="form-control input-sm ui-autocomplete-input" value="<?php echo $type;  ?>" required>
                              <option value="<?php echo $type;  ?>"><?php echo $type;  ?></option>                          
                              <option value="Petty Cash">Petty Cash</option>
                              <option value="Revolving Fund">Revolving Fund</option>                          
                              <option value="Operational Fund">Operational Fund</option>
                              
                              
                            </select>
                  </div>
                  <div class="form-group">
                     <label>Account of:</label>
                     <div>
                        <input type="text" class="form-control input-sm" id="autocomplete1" name="requested_by"  value="<?php echo $requested_by;  ?>" required />
                        <input type="hidden" class="form-control input-sm" id="requested_by_val" name="requested_by_val"  value="<?php echo $requested_by;  ?>" />
                     </div>
                  </div>
				  <div class="form-group">
                     <label>Department:</label>
                     <div>
                     	
                        <select class="form-control input-sm" name="department" id="department" >
                                                    <option value="<?php echo $dept ?>"><?php echo $dep_name ?></option>
                                                    <?php foreach($department as $dep){ ?>
                                                    <option value="<?php echo $dep->id ?>"><?php echo $dep->name ?></option>
                                                    <?php } ?>
                                               </select>
                        
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Warehouse (optional):</label>
                     <div>
                     	
                        <select class="form-control input-sm" name="warehouse" id="warehouse" >
                                                    <option value="<?php echo $warehouse_id ?>"><?php echo $warehouse_name ?></option>
                                                    <?php foreach($warehouses as $w){ ?>
                                                    <option value="<?php echo $w['id'] ?>"><?php echo $w['name'] ?></option>
                                                    <?php } ?>
                                               </select>
                        
                     </div>
                  </div>
                   </div>
				  <div class="col-md-6">
                      <div class="form-group">
                         <label>Amount:</label>
                         <div>
                            <input type="number" class="form-control input-sm" name="amount" id="amount"  value="<?php echo $amount;  ?>" required>
                         </div>
                      </div>
                      <div class="form-group">
                         <label>Purpose:</label>
                         <div>
                            
                            
                            <textarea class="form-control input-sm" rows="4" name="purpose" required><?php echo $purpose;  ?></textarea>
                            
                         </div>
                      </div>                
				 
                  </div>
				  </div>
               </div>
             

                
            
            </div>			<!-- /modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="
               
               											 		var t = $('#type').val();										
                                                                var r = $('#requested_by_val').val();										
                                                                var am = $('#amount').val();		
                                                                var d = $('#department').val();										
                                                                var p = $('#purpose').val();										
                                                                
                                                                if(t == ''){
                                                                	alert('Please select Request Type.');
                                                                    $('#type').focus();	
                                                                }else if(r == ''){
                                                                 	alert('Please enter the name of requester.');
                                                                    $('#autocomplete1').focus();	                                                          
                                                                }else if(d == ''){
                                                                 	alert('Please select department.');
                                                                    $('#department').focus();
                                                               	}else if(am == ''){
                                                                 	alert('Please enter amount.');
                                                                    $('#amount').focus();	       										     
                                                                }else{
                                                                	if(confirm('You are about to submit a new request.')){
                                                                    	$('#cashForm').submit()
                                                                    }    
                                                                }    
                                                                
                                                                
                                                                " >Save</button>
               
                
            </div>			<!-- /modal-footer -->
            </form>
        </div>         <!-- /modal-content -->
    </div>     <!-- /modal-dialog -->


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



