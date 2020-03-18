<?php
   $id = "";
   $reference_no = "";
   $particular = "";
   $amount = "";
   $type = "";
   $payee = "";  
   $voucher_id = "";
   $remark = "";
  // var_dump($remark);exit;
   if(isset($request_payment)){
	  // var_dump($request);exit;
   		
	   $id	=	$request_payment->id;
	   $reference_no	=	$request_payment->reference_no;
	   $particular	=	$request_payment->particular;
	   $amount	=	$request_payment->amount;
	   $type	=	$request_payment->type;
	   $payee	=	$request_payment->payee;
	   $voucher_id	=	$request_payment->voucher_id;
	   $remark	=	$request_payment->remark;
	 
	   
	 }
   ?>
<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'forms/add_request_payment'; ?>" method="post" style="height: 300px;">
<?php echo $this->Mmm->createCSRF() ?>
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
								<!--- waybill activity --->
								<div style="width:250px; float:left; margin-left:0px;">
									<div class="container">
										<div class="panel panel-primary" style="font-size:12px; width:399px; height:400px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Request Payment Information&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab">
												<div style="    width: 366px; float:left; margin-top: 16px;">
												<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label>Reference No:</label> <i></i>
														<div>
															<input class="form-control input-sm" name="reference_no" class="form-control abas_Formcontrol" value="<?php echo $reference_no;  ?>" required>
														</div>
													</div>
													<div class="form-group">
														<label>Particular:</label>
														<div><input type="text" name="particular" class="form-control input-sm ui-autocomplete-input" value="<?php echo $particular;  ?>"/>
														</div>
													</div>	
													<div class="form-group">
														<label>Amount:</label>
														<div><input type="text" name="amount" class="form-control input-sm ui-autocomplete-input" value="<?php echo $amount;  ?>"/>
														</div>
													</div>	
													<div class="form-group">
													 <label for="category">Type:</label>
													 <div>
													 <select name="type"  class="form-control input-sm ui-autocomplete-input" value="<?php echo $type;  ?>" required>
														  <option value="trucking">trucking</option>
														  <option value="handling">handling</option>
														  <option value="shipment">handling</option>
														</select>
													 </div>
												  </div>
												  </div>
												  <div class="col-sm-6">
												 
													<div class="form-group">
														<label>Payee:</label>
														<div><input type="text" name="payee" id="autocomplete" class="form-control input-sm ui-autocomplete-input" value="<?php echo $payee;  ?>"/>
														</div>
													</div>													
													<div class="form-group">
														<label>Remark:</label>
														<div><input type="text" name="remark" class="form-control input-sm ui-autocomplete-input" value="<?php echo $remark;  ?>"/>
														</div>
													</div>
													<div class="form-group">
														<label>Voucher ID:</label>
														<div><input type="text" name="voucher_id" class="form-control input-sm ui-autocomplete-input" value="<?php echo $voucher_id;  ?>"/>
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

<style>
.modal-dialog{width: 400px !important;height: 250px;}
.autocomplete {
    z-index: 5000;
}

.ui-autocomplete {
  z-index: 215000000 !important;
}
</style>

	<script type="text/javascript">

		$(document).ready(function () {

				$( "#autocomplete" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>forms/service_provider_data",
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

<input type="hidden" id="selitem" name="selitem" />