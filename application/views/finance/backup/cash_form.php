<?php
   $id = "";
   $date_requested= "";
   $date_released = "";
   $requested_by = "";
   $purpose = "";
   $amount = "";
   $department = "";
  $type = "";
   
   if(isset($cash)){
   		//var_dump($item[0]['id']);exit;
   		$id = $cash[0]['id'];
   		$requested_by = $cash[0]['requested_by'];
   		$purpose = $cash[0]['purpose'];
   		$amount = $cash[0]['amount'];
   		$department = $cash[0]['department'];
   		$type = $cash[0]['type'];
   		//var_dump($date_released);exit;

   }
   ?>
<style>
   .modal-dialog{width: 400px !important;height: 300px;}
</style>
<form class="form-horizontal" role="form" name="bankForm" id="bankForm" action="<?php echo HTTP_PATH.'finance/add_cash'; ?>" method="post" style="height: 400px;">
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
  <!--- waybill activity --->
   <div style="width:250px; float:left; margin-left:0px;">
      <div class="container">
         <div class="panel panel-primary" style="font-size:12px; width:550px; height:443px">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Cash Advance Information&nbsp;</strong>
            </div>
            <div class="panel-body" role="tab">
			<p>Label with asterisk (*) is required</p><hr />
               <div style="width:466px; margin-left:20px; float:left">
			   <div class="row">
			    <div class="col-md-6">
               
				  <div class="form-group">
                     <label>Requested by:</label>
                     <div>
                        <input type="text" class="form-control input-sm" name="requested_by" class="form-control abas_Formcontrol" value="<?php echo $requested_by;  ?>" required />
                     </div>
                  </div>
				  <div class="form-group">
                     <label>Amount:</label>
                     <div>
                        <input type="text" class="form-control input-sm" name="amount" class="form-control abas_Formcontrol" value="<?php echo $amount;  ?>" required>
                     </div>
                  </div>
				  <div class="form-group">
                     <label>Purpose:</label>
                     <div>
                        <input type="text" class="form-control input-sm" name="purpose" class="form-control abas_Formcontrol" value="<?php echo $purpose;  ?>" required>
                     </div>
                  </div>
                  </div>
				  <div class="col-md-6">
				  <div class="form-group">
                     <label>Department:</label>
                     <div>
                        <input type="text" class="form-control input-sm" id="autocomplete" name="department" class="form-control abas_Formcontrol" value="<?php echo $department;  ?>" required>
                     </div>
                  </div>
				  <div class="form-group">
                     <label>Type of Request:</label>
                     <div>
                        <input type="text" class="form-control input-sm" name="type" class="form-control abas_Formcontrol" value="<?php echo $type;  ?>" required>
                     </div>
                  </div>
                  </div>
				  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal" onclick="myFunction()">Close</button>
               <button type="submit" class="btn btn-primary">Save</button>
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
						$( "#selitem" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						$("#qty").focus();
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
						// alert(ui.item.value);
						//alert(ui.item.value);
						$( "#autocomplete" ).val( ui.item.label );
						$( "#selitem" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						$("#qty").focus();
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



