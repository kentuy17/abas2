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
   
   
   
 // $this->load->view('includes_header');
   
   ?>
<style>
   .modal-dialog{width: 900px !important;height: 200px; margin-top:0px}
   .modal-header{background:#3A5276; color:#FFFFFF}
</style>	
    
   <form class="form-horizontal" role="form" name="reportForm" id="reportForm" action="<?php echo HTTP_PATH.'finance/liquidation_report'; ?>" method="post" style="height: 400px;"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Liquidation Report</h4>
            </div>			<!-- /modal-header -->
            <div class="modal-body clearfix">
            	
                <div style="width:466px; margin-left:20px; float:left">
			   	<div class="row">
			    	<br /><br />
                    <div class="col-md-8">
               			
				   		<div class="form-group">
                         <label>Select Type of Fund:</label>
                         <select name="type" id="type"  class="form-control input-sm ui-autocomplete-input" value="<?php echo $type;  ?>" required>
                              <option value="<?php echo $type;  ?>"><?php echo $type;  ?></option>                          
                              <option value="Petty Cash">Petty Cash</option>
                              <option value="Revolving Fund">Revolving Fund</option>                          
                              <option value="Operations Fund">Operations Fund</option>
                              
                              
                            </select>
                  		</div>
                        <div class="form-group">
                         <label>Date Inclusive:</label>
                         <div>
                             <div style="float:left">
                                From: <input type="date" class="form-control input-sm" name="date_from" id="date_from" width="100px" >
                                
                             </div>
                             <div style="float:right">
                                
                                To:	<input type="date" class="form-control input-sm" name="date_to" id="date_to" width="100px">
                             </div>
                         </div>
                      </div>
                 	</div>
				  
                  	<div class="col-md-6">
                      <div class="form-group">
                        
                      </div>
                 	</div>
				</div>
               </div>
                
            
            </div>			<!-- /modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" onclick="$('#reportForm').submit()" >Create</button>
                
            </div>			<!-- /modal-footer -->
        </div>         <!-- /modal-content -->
    </div>     <!-- /modal-dialog -->
    
    </form>