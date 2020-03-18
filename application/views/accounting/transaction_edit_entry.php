<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="panel-title">
			<text>Edit Transaction Entry</text>
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		</div>
	</div>
</div>
<div class="panel-body">
	<form action='<?php echo HTTP_PATH."accounting/transactions/update_entry/0"?>' role='form' method='POST' id='entry_form' enctype='multipart/form-data'>
		<?php echo $this->Mmm->createCSRF(); ?>
		<input type="hidden" name="entry_id" id="entry_id" value="<?php echo $entry_id?>" class="form-control">
		<input type="hidden" name="entry_transaction_id" id="entry_transaction_id" value="<?php echo $entry_transaction_id?>" class="form-control">
		<div class='col-xs-12 col-sm-12 col-md-6'>
			<label for='company'>Debit Amount*</label>
			 <input type="hidden" name="old_debit_amount" id="old_debit_amount" value="<?php echo $entry_debit_amount?>" class="form-control">
			 <input type="number" name="new_debit_amount" id="new_debit_amount" value="<?php echo $entry_debit_amount?>" class="form-control">
		</div>
		<div class='col-xs-12 col-sm-12 col-md-6'>
			<label for='company'>Credit Amount*</label>
			 <input type="hidden" name="old_credit_amount" id="old_credit_amount" value="<?php echo $entry_credit_amount?>" class="form-control">
			 <input type="number" name="new_credit_amount" id="new_credit_amount" value="<?php echo $entry_credit_amount?>" class="form-control">
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
	var debit=$('#new_debit_amount').val();
	if (debit==null || debit=="") {
		msg+=" Debit amount required.<br/>";
	}
	var credit=$('#new_credit_amount').val();
	if (credit==null || credit=="") {
		msg+=" Credit amount required.<br/>";
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
</script>