<?php
   
   $id = "";   
   $amount = "";   
   $type = "";
   $ref_number = "";
   
   if(isset($cash)){
   		//var_dump($item[0]['id']);exit;l
   		$id = $cash[0]['id'];   		
   		$amount = $cash[0]['amount'];   		
   		$type = $cash[0]['type'];
		$ref_number = $cash[0]['ref_number'];;
   		//var_dump($date_released);exit;


   }
   
   
   ?>



<style>
   .modal-dialog{width: 600px !important;height: 200px; margin-top:0px}
   .modal-header{background:#3A5276; color:#FFFFFF}
</style>	

    
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Add Fund</h4>
            </div>			<!-- /modal-header -->
            <div class="modal-body clearfix">
            	
               <form class="form-horizontal" role="form" name="fundForm" id="fundForm" action="<?php echo HTTP_PATH.'finance/add_fund'; ?>" method="post">
                    <?php echo $this->Mmm->createCSRF() ?>
                   <input type="hidden" name="stat" value="1">
                   <input type="hidden" name="id" value="<?php echo $id;  ?>">
                  <!--- waybill activity --->
                  
                            
                               <div style="margin-left:20px; float:left">
                                <div class="row">
                                    <br /><br />
                                    <div class="col-md-6">
                               
                                        <div class="form-group">
                                         <label>Type of Fund:</label>
                                         <select name="type" id="type"  class="form-control input-sm ui-autocomplete-input" value="<?php echo $type;  ?>" required>
                                              <option value="<?php echo $type;  ?>"><?php echo $type;  ?></option>                          
                                              <option value="Petty Cash">Petty Cash</option>
                                              <option value="Revolving Fund">Revolving Fund</option>                          
                                              <option value="Operational Fund">Operational Fund</option>
                                              
                                              
                                            </select>
                                        </div>
                                        <div class="form-group">
                                         	<label>Amount:</label>
                                         	<div>
                                            	<input type="text" class="form-control input-sm" name="amount" id="amount" value="<?php echo $amount;  ?>" required>
                                         	</div>
                                      	</div>
                                    </div>
                                  
                                    <div class="col-md-6">
                                        
                                        <div class="form-group">
                                             <label>Reference # (Voucher ID):</label>
                                             <div>
                                                <input type="text" class="form-control input-sm" name="ref_number" id="ref_number" value="<?php echo $ref_number;  ?>" required>
                                             </div>
                                      	</div>
                                      	
                                    </div>
                                </div>
                               
                </form>
                
            
            </div>			<!-- /modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" 
                	onclick="
                    				
                                    							var t = $('#type').val();		                                    									
                                                                var a = $('#amount').val();	                                     										
                                                                
                                                                if(t == ''){
                                                                	alert('Please select Request Type.');
                                                                    $('#type').focus();	                                                     
                                                               	}else if(a == ''){
                                                                 	alert('Please enter amount.');
                                                                    $('#amount').focus();	       										     
                                                                }else{
                                                                	if(confirm('You are about to add fund, click Ok to continue.')){
                                                                    	$('#fundForm').submit();
                                                                        $('#processing').show(); //for testing
                                                                    }    
                                                                }    
                                    
                                    
                    		
                " >Save</button>
                
            </div>			<!-- /modal-footer -->
        </div>         <!-- /modal-content -->
    </div>     <!-- /modal-dialog -->














	




