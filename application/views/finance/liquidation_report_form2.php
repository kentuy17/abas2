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
   .modal-dialog{width: 400px !important;height: 300px;}
</style>
<form class="form-horizontal" role="form" name="fundForm" id="fundForm" action="<?php echo HTTP_PATH.'finance/add_fund'; ?>" method="post" style="height: 400px;">
	<?php echo $this->Mmm->createCSRF() ?>
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
  <!--- waybill activity --->
   <div >
      <div class="container">
         <div class="panel panel-primary" style="font-size:12px; width:550px; height:401px">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Liquidation Report Form&nbsp;</strong>
            </div>
            <div class="panel-body">
			
               <div style="width:466px; margin-left:20px; float:left">
			   	<div class="row">
			    	<br /><br />
                    <div class="col-md-6">
               
				   		<div class="form-group">
                         <label>Type of Fund:</label>
                         <select name="type"  class="form-control input-sm ui-autocomplete-input" value="<?php echo $type;  ?>" required>
                              <option value="<?php echo $type;  ?>"><?php echo $type;  ?></option>                          
                              <option value="Petty Cash">Petty Cash</option>
                              <option value="Revolving Fund">Revolving Fund</option>                          
                              <option value="Operational Fund">Operational Fund</option>
                              
                              
                            </select>
                  		</div>
                        <div class="form-group">
                         <label>Reference # (Voucher ID):</label>
                         <div>
                            <input type="text" class="form-control input-sm" name="ref_number" id="ref_number" value="<?php echo $ref_number;  ?>" required>
                         </div>
                      </div>
                 	</div>
				  
                  	<div class="col-md-6">
                      <div class="form-group">
                        
                      </div>
                 	</div>
				</div>
               </div>
            </div>
            <div class="modal-footer">
               <div style="float:right; margin-right:25px">
               
               <button type="submit" class="btn btn-primary" >Create</button>
               </div>
            </div>
         </div>
      </div>
   </div>
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



