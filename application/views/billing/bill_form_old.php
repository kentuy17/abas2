<?php
$action			=	HTTP_PATH."billing/payments/insert";
$title			=	"Receieve Payment";
$companyoptions	=	"";
if(!empty($companies=$this->Abas->getCompanies())) {
	foreach($companies as $company) {
		$companyoptions	.=	"<option value='".$company->id."'>".$company->name."</option>";
	}
}
$bankoptions	=	"";
if(!empty($bankaccounts=$this->Abas->getBanks())) {
	foreach($bankaccounts as $bankaccount) {
		$bankoptions	.=	"<option value='".$bankaccount['id']."'>".$bankaccount['name']."</option>";
	}
}
if(isset($soa)) {
	$this->Mmm->debug($soa);
	$title		=	"Receive Payment for SOA: ".$soa['reference_number'];
	$action		=	HTTP_PATH."billing/pay_soa/".$soa['id'];
}
?>
<div class="panel panel-primary">
	<form action='<?php echo $action; ?>' method="POST" id="bill_form">
		<div class="panel-heading">
			<h3 class="panel-title">
				<?php echo $title; ?>
				<span class="pull-right">
					<input type="button" class="btn btn-success btn-xs" onclick="javascript:checkform();" value="Submit" />
					<input type="button" class="btn btn-danger btn-xs" value="Cancel" data-dismiss="modal" />
				</span>
			</h3>
		</div>
		<div class="panel-body">
			<?php if(!isset($soa)): ?>
				<div class='col-sm-12 col-md-12'>
					<label for='amount'>Particular</label>
					<textarea name='particular' id='particular' placeholder='Particular' class='form-control'></textarea>
				</div>
				<div class='col-sm-12 col-md-6'>
					<label for='company'>Company</label>
					<select name='company' class='form-control' id='company'>
					<option value=''>Select One</option>
					<?php echo $companyoptions; ?>
					</select>
				</div>
			<?php endif; ?>
			<div class='col-sm-6 col-md-6'>
				<label for='deposited_on'>Date Deposited</label>
				<input type="text" name='deposited_on' id='deposited_on' placeholder='Date Deposited' class='form-control' />
			</div>
			<div class='col-sm-6 col-md-6'>
				<label for='method'>Payment Type</label>
				<select name='method' id='method' class='form-control'>
					<option value=''>Choose One</option>
					<option value='Cash Deposit'>Cash Deposit</option>
					<option value='Cheque Deposit'>Cheque Deposit</option>
				</select>
			</div>
			<div class='col-sm-6 col-md-6'>
				<label for='amount'>Amount</label>
				<input type='text' id='amount' name='amount' placeholder='Amount' class='form-control' />
			</div>
			<div class='col-sm-6 col-md-6'>
				<label for='bank_account'>Bank Account</label>
				<select id='bank_account' name='bank_account' class='form-control'>
					<option>Choose One</option>
					<?php echo $bankoptions; ?>
				</select>
			</div>
		</div>
	</form>
</div>
<script>
function validateEmail(email) {
	var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}
function validateRadio (radios)	{
	for (var i = 0; i < radios.length; i++)	{
		if (radios[i].checked) {return true;}
	}
	return false;
}
function checkform() {
	var msg="";
	var patt1=/^[0-9]+$/i;

	var amount=document.getElementById("amount").value;
	if (amount!="") {
		if (!patt1.test(amount)) {
			msg+="Only numbers are allowed in amount! <br/>";
		}
	}
	else {
		msg+="Amount is required! <br/>";
	}
	var method=document.getElementById("method").selectedIndex;
	if (method=="") {
		msg+="Method is required! <br/>";
	}
	if(msg!="") {
		toastr['error'](msg, "You have missing input!");
		return false;
	}
	else {
		document.getElementById("bill_form").submit();
		return true;
	}

}
</script>