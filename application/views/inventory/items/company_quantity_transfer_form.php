<?php
	$companyoptions	=	"<option value=''>Select</option>";
	if(!empty($companies)) {
		foreach($companies as $c) {
			if($c->id<>$company_id){
				$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";	
			}
		}
	}
?>
<div class="panel panel-danger">
	<div class="panel-heading">
		Transfer Quantity to another Company
	</div>
</div>
	<div class="panel-body">
		<form action="<?php echo HTTP_PATH.'inventory/company_quantity_transfer/'.$inv_qty_id?>" method="POST" onsubmit="javascript: checkform();" enctype="multipart/form-data" name="transfer_form" id="transfer_form">
			<?php echo $this->Mmm->createCSRF(); ?>

			<div class='col-sm-12 col-xs-12'>
				<label for='company'>From Company:</label>
				<input type='text' id="company_name" name='company_name' class='form-control md-input' value='<?php echo $company_name;?>' readonly/>
				<input type='hidden' id="company_id" name='company_id' class='form-control md-input' value='<?php echo $company_id;?>' readonly/>
				<input type='hidden' id='inv_qty_id' name='inv_qty_id' class='form-control md-input' value='<?php echo $inv_qty_id;?>'>
			</div>
			
			<div class='col-sm-12 col-xs-12'>
				<label for='item'>Item:</label>
				<input type='text' id="item" name='item' class='form-control md-input' value='<?php echo $item[0]['description'];?>' readonly/>
				<input type='hidden' id='item_id' name='item_id' class='form-control md-input' value='<?php echo $item[0]['id'];?>'>
			</div>

			<div class='col-sm-12 col-xs-12'>
				<label for='current_qty'>Current Quantity</label>
				<input type='number' id='current_qty' name='current_qty' class='form-control md-input' value='<?php echo $current_qty;?>' readonly>
			</div>

			<div class='col-sm-12 col-xs-12'>
				<label for='transfer_qty'>Quantity To Transfer</label>
				<input type='number' id='transfer_qty' name='transfer_qty' class='form-control md-input' value=''>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="company" class="control-label">To Company:</label>
				<select class="form-control" id="to_company" name="to_company">
					<?php echo $companyoptions; ?>
				</select>
			</div>
			
	</div>
	<div class="modal-footer">
		<input type="button" onclick="javascript: checkform();" class="btn btn-success" value="Transfer" />
	</div>
	</form>
</div>
<script type="text/javascript">
function checkform() {
	$(this).prop("disabled", true);
	var company = $('#to_company').val();
	var current_qty = $('#current_qty').val();
	var transfer_qty = $('#transfer_qty').val();
	var msg="";

	if(transfer_qty==''){
		msg+="Quantity to transfer is required.<br>";
	}
	console.log(transfer_qty);
	console.log(current_qty);

	if(parseFloat(transfer_qty)>parseFloat(current_qty)){
		msg+="Quantity to transfer must not be more than the current quantity.<br>";
	}
	if(company==''){
		msg+="Transfer to company is required.<br>";
	}

	if(msg!="") {
		$(this).prop("disabled", false);
		toastr["error"](msg,"ABAS Says");
		return false;
	}
	else {
		$(this).prop("disabled", false);
		document.getElementById("transfer_form").submit();
		return true;
	}
}
</script>