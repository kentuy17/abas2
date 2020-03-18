<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="panel-title">
			<text>Edit Amount</text>
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		</div>
	</div>
</div>
<div class="panel-body">
	<form action='<?php echo HTTP_PATH."accounting/request_for_payment/save_amount/".$request_detail->id?>' role='form' method='POST' id='entry_form' enctype='multipart/form-data'>
		<?php echo $this->Mmm->createCSRF(); 
		 ?>
		 <div class='col-xs-12 col-sm-12 col-md-12'>
			<label>Add With-holding Tax*</label>
			 <select name="rfp_wtax" id="rfp_wtax" class="form-control">
			 	<option value='' selected>Select</option>
			 	<option value='1%'>1%</option>
			 	<option value='2%'>2%</option>
			 	<option value='5%'>5%</option>
			 	<option value='10%'>10%</option>
			 	<option value='1% with VAT'>1% with VAT</option>
			 	<option value='2% with VAT'>2% with VAT</option>
			 	<option value='5% with VAT'>5% with VAT</option>
			 	<option value='10% with VAT'>10% with VAT</option>
			 </select>
		</div>
		 <div class='col-xs-12 col-sm-12 col-md-12'>
			<label>Amount</label>
			 <input type="number" id="rfp_detail_orig_amount" value="<?php echo $request_detail->amount?>" class="form-control" >
		 </div>
		 <div class='col-xs-12 col-sm-12 col-md-12'>
			<label>VAT</label>
			 <input type="number" id="rfp_vat_amount" name="rfp_vat_amount" class="form-control" >
		 </div>
		<div class='col-xs-12 col-sm-12 col-md-12'>
			<label>Input Tax</label>
			 <input type="number" id="rfp_input_tax_amount"  name="rfp_input_tax_amount" value="" class="form-control" >
		</div>
		<div class='col-xs-12 col-sm-12 col-md-12'>
			<label>W-Tax Expanded</label>
			 <input type="number" id="rfp_wtax_amount"  name="rfp_wtax_amount" value="" class="form-control" >
		</div>
		<div class='col-xs-12 col-sm-12 col-md-12'>
			<label>Amount less W-Tax</label>
			 <input type="number" name="rfp_detail_amount" id="rfp_detail_amount" value="" class="form-control" >
		</div>
</div>
<div class="modal-footer">
	<input type="button" onclick="javascript: checkform();" class="btn btn-success btn-m" value="Save" />
	<input type='button' value='Discard' name='btnDiscard' class='btn btn-danger btn-m' data-dismiss="modal"/>
</div>
</form>	

<script type="text/javascript">
function checkform() {
	var msg = "";
	var wtax=$('#rfp_wtax').val();
	var amount=$('#rfp_detail_amount').val();
	if (amount==null || amount=="") {
		msg+=" W-Tax is required.<br/>";
	}
	if (amount==null || amount=="") {
		msg+=" Amount is required.<br/>";
	}
	if(msg!="") {
		toastr["warning"](msg,"ABAS Says");
		return false;
	}
	else{
		document.getElementById("entry_form").submit();
		return true;
	}
}
$('#rfp_wtax').change(function() 
	{   
	  var orig_amount = $('#rfp_detail_orig_amount').val();
	  if(orig_amount>0){
	  	var wtax = $('#rfp_wtax').val();
	  	var wtax_amount;
	  	var input_tax;
	  	var vat_amount = orig_amount/1.12;
	  	var final_amount;
	  	if(wtax=='1%'){
	  		vat_amount = 0;
	  		input_tax = 0;
	  		wtax_amount = (orig_amount) * 0.01;
	  	}
	  	if(wtax=='2%'){
	  		vat_amount = 0;
	  		input_tax = 0;
	  		wtax_amount = (orig_amount) * 0.02;
	  	}
	  	if(wtax=='5%'){
	  		vat_amount = 0;
	  		input_tax = 0;
	  		wtax_amount = (orig_amount) * 0.05;
	  	}
	  	if(wtax=='10%'){
	  		vat_amount = 0;
	  		input_tax = 0;
	  		wtax_amount = (orig_amount) * 0.10;
	  	}
	  	if(wtax=='1% with VAT'){
	  		input_tax = orig_amount - vat_amount;
	  		wtax_amount = vat_amount * 0.01;
	  	}
	  	if(wtax=='2% with VAT'){
	  		input_tax = orig_amount - vat_amount;
	  		wtax_amount = vat_amount * 0.02;
	  	}
	  	if(wtax=='5% with VAT'){
	  		input_tax = orig_amount - vat_amount;
	  		wtax_amount = vat_amount * 0.05;
	  	}
	  	if(wtax=='10% with VAT'){
	  		input_tax = orig_amount - vat_amount;
	  		wtax_amount = vat_amount * 0.10;
	  	}
	  	final_amount =  orig_amount - wtax_amount;
	  	$('#rfp_vat_amount').val(vat_amount);
	  	$('#rfp_input_tax_amount').val(input_tax);
	  	$('#rfp_wtax_amount').val(wtax_amount);
	  	$('#rfp_detail_amount').val(final_amount);
	  }
});
</script>