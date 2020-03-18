<?php
$action				=	HTTP_PATH.'accounting/transactions/insert';

if(isset($transaction)) {
	$this->Mmm->debug($transaction);
	$action			=	HTTP_PATH.'accounting/transactions/insert_entry/'.$transaction['id'];
}
else {
	$transaction	=	array("id"=>null, "company_id"=>null, );
}
$transactions		=	$this->db->query("SELECT * FROM ac_transactions WHERE stat=1");
$transactions		=	$transactions->result_array();
$transactionoptions	=	"<option value='new'>New Transaction</option>";
if(isset($transactions)) {
	if(!empty($transactions)) {
		foreach($transactions as $t) {
			$selected			=	"";
			if(isset($transaction)) {
				if($transaction['id']==$t['id']) $selected	=	"selected";
			}
			$transactionoptions	.=	"<option ".$selected." value='".$t['id']."'>".$t['id'].". ".$t['remark']."</option>";
		}
	}
}
$companyoptions		=	"";
if(isset($companies)) {
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option ".(($transaction['company_id']==$c->id)?"SELECTED":"")." value='".$c->id."'>".$c->name."</option>";
		}
	}
}
$departmentoptions		=	"";
$departments			=	$this->Abas->getDepartments();
if(!empty($departments)) {
	foreach($departments as $d) {
		$departmentoptions	.=	'<option value="'.$d->id.'">'.$d->name.'</option>';
	}
}
$vesseloptions		=	"";
$vessels			=	$this->Abas->getVessels(false);
if(!empty($vessels)) {
	foreach($vessels as $v) {
		$vesseloptions	.=	'<option value="'.$v->id.'">'.$v->name.'</option>';
	}
}
$contractoptions	=	"";
$contracts			=	$this->Abas->getContracts();
if(!empty($contracts)) {
	foreach($contracts as $c) {
		$contractoptions.=	'<option value="'.$c['id'].'">'.$c['reference_no'].'</option>';
	}
}
$rfpoptions	=	"";
if(isset($requestsforpayment)) {
	if(!empty($requestsforpayment)) {
		foreach($requestsforpayment as $rfp) {
			$rfpoptions	.=	'<option value="'.$rfp['id'].'">'.$rfp['remark'].'</option>';
		}
	}
}
$reconciliationoptions	=	"";
if(isset($journalentries)) {
	if(!empty($journalentries)) {
		foreach($journalentries as $je) {
			$reconciliationoptions	.=	'<option value="'.$je['id'].'">'.$je['remark'].'</option>';
		}
	}
}
$journal_entry_form	=	'
	<div class="journal-entry-row">
		<div class="col-xs-12 col-sm-2 col-md-2">
			<label for="account_label">Account:</label>
			<input type="text" id="account_label[]" class="form-control ui-autocomplete-input account_label" name="account_label[]" placeholder="Account Number (Autocomplete)" />
			<input type="text" id="account[]" class="hide account" name="account[]"/>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<label for="department">Department:</label>
			<select class="form-control" type="text" name="department[]" id="department[]">
				<option value="">Choose One</option>
				<option value="0">None</option>
				'.$departmentoptions.'
			</select>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<label for="vessel">Vessel:</label>
			<select class="form-control" type="text" name="vessel[]" id="vessel[]">
				<option value="">Choose One</option>
				<option value="0">None</option>
				'.$vesseloptions.'
			</select>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<label for="contract">Contract:</label>
			<select class="form-control" type="text" name="contract[]" id="contract[]">
				<option value="">Choose One</option>
				<option value="0">None</option>
				'.$contractoptions.'
			</select>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<label for="amount">Amount:</label>
			<input class="form-control amount" type="number" name="amount[]" placeholder="Amount" id="amount[]" placeholder="Amount" />
		</div>
		<div class="col-xs-11 col-sm-2 col-md-2">
			<label for="debit_or_credit">Debit or Credit</label>
			<select class="form-control debit-or-credit" type="text" name="debit_or_credit[]" id="debit_or_credit[]">
				<option value="">Choose One</option>
				<option value="Debit">Debit</option>
				<option value="Credit">Credit</option>
			</select>
		</div>
		<div class="col-xs-1 col-sm-1 col-md-1 col-md-offset-11">
			<a class="btn btn-danger btn-remove-row">-</a>
		</div>
	</div>
';
?>
<form role="form" id="journal_form" name="journal_form"  action="<?php echo $action; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF(); ?>
	<div class="panel panel-default">
		<div class="panel-heading" role="tab">
			<strong>Journal Entry</strong>
			<span class="pull-right">
				<input class="btn btn-xs btn-success" type="button" value="Save" onclick="javascript:promptSubmit()" id="submitbtn">
				<input class="btn btn-xs btn-default" type="button" value="Cancel" data-dismiss="modal">
			</span>
		</div>
		<div class="panel-body" role="tab">
			<div class="col-xs-12 col-sm-4 col-md-4">
				<label for="company">Company:</label>
				<select class="form-control" name="company" id="company" <?php echo (!empty($transaction['company_id'])) ? "disabled='disabled'":""; ?>>
					<option>Choose One</option>
					<?php echo $companyoptions; ?>
				</select>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4">
				<label for="transaction">Transaction:</label>
				<input type='text' id='transaction' name='transaction' placeholder='Create New Transaction' <?php echo (!empty($transaction['id'])) ? "disabled='disabled'":""; ?> class='form-control' value='<?php echo (!empty($transaction['id'])) ? $transaction['remark']:""; ?>' />
				<input type='text' id='transaction_id' name='transaction_id' placeholder='Memo' class='hide form-control' value='<?php echo (!empty($transaction['id'])) ? $transaction['id']:"new"; ?>' />
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4">
				<label for="posted_on">Posted On:</label>
				<input type='text' id='posted_on' name='posted_on' placeholder='' class='form-control' value='<?php echo date("Y-m-d H:i:s"); ?>' />
				<script>$("#posted_on").datepicker({dateFormat: "yy-mm-dd"});</script>
			</div>
			<div class="journal-entry-container">
				<?php echo $journal_entry_form; ?>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12"><br/></div>
			<div class="col-xs-6 col-sm-6 col-md-6 text-right">
				<a id="btn_add_row" class="btn btn-success">+</a>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 text-left">
				<a id="btn_remove_row" class="btn btn-danger">-</a>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<label for="remark">Memo (remark):</label>
				<textarea id='remark' name='remark' class='form-control'></textarea>
			</div>
		</div>
	</div>
</form>
<script>
$( "#transaction" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>home/autocomplete/ac_transactions/remark",
	minLength: 2,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		if (ui.content.length === 0) {
			toastr.clear();
			toastr["warning"]("Transaction not found!", "ABAS Says");
		}
		else {
			toastr.clear();
		}
	},
	select: function( event, ui ) {
		$(this).prop("disabled", true);
		$( this ).val( ui.item.label );
		$( this ).next().val( ui.item.value );
		return false;
	}
});
function checkautoform() {
	<?php $n=$this->security->get_csrf_token_name(); $h=$this->security->get_csrf_hash(); ?>
	$("#btnSubmit").visible=false;
	var msg="";
	//var patt1=/^[0-9]+$/i;
	var patt1=/^\d+(\.\d+)*$/i;
	var company0=document.forms.journal_form.company.selectedIndex;
	if (company0==null || company0=="") {
		msg+="Company is required! <br/>";
	}
	var transaction=document.forms.journal_form.transaction_id.value;
	if (transaction==null || transaction=="") {
		msg+="Transaction is required! <br/>";
	}
	var posted_on=document.forms.journal_form.posted_on.value;
	if (posted_on==null || posted_on=="") {
		msg+="Posting date is required! <br/>";
	}
	var remark=document.forms.journal_form.remark.value;
	if (remark==null || remark=="") {
		msg+="Memo is required! <br/>";
	}
	// check items if they were selected from dropdown
	var nonempty = $('.account').filter(function() { return this.value != ''; });
	if (nonempty.length == 0) {
		msg+="An account was not selected properly! Please select it in the dropdown after typing.<br/>";
	}
	var total_debit		=	0;
	var total_credit	=	0;
	$('.amount').each(function(index) {
		if($(".debit-or-credit")[index].value=="Debit") {
			total_debit		=	(total_debit+parseFloat($(".amount")[index].value).toFixed(2));
		}
		else if($(".debit-or-credit")[index].value=="Credit") {
			total_credit	=	(total_credit+parseFloat($(".amount")[index].value).toFixed(2));
		}
		else {
			if($(".account_label")[index].value!="") {
				msg+="Debit/credit classification for "+$(".account_label")[index].value+" has not been set!";
			}
			else {
				msg+="Debit/credit classification for an account has not been set!";
			}
		}
	});
	total_debit		=	(Math.round(total_debit * 100) / 100).toFixed(2);
	total_credit	=	(Math.round(total_credit * 100) / 100).toFixed(2);
	balance			=	total_debit-total_credit;
	balance			=	parseFloat(balance).toFixed(2);
	// if(balance>0.02 || balance<-0.02) {
		// msg+="Debit/Credit totals are unbalanced! <pre>(Debit-Credit)=Balance<br/>("+total_debit+"-"+total_credit+")="+(balance)+"</pre>";
	// }

	if(msg!="") {
		$("#btnSubmit").visible=true;
		toastr["warning"](msg,"ABAS Says");
		return false;
	}
	else {
		$("#btnSubmit").visible=true;
		document.getElementById("journal_form").submit();
		return true;
	}
}
$("#btn_remove_row").click(function(){
	$('.journal-entry-row:last').remove();
});
$(document).on('click', '.btn-remove-row', function() {
	$(this).parent().parent().remove();
});
$( ".account_label" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>accounting/autocomplete_account",
	minLength: 2,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$(this).prop("disabled", true);
		$( ".account_label" ).val( ui.item.label );
		$( ".account" ).val( ui.item.value );
		return false;
	}
});
$('input.number').keyup(function(event) {
	// skip for arrow keys
	if(event.which >= 37 && event.which <= 40) return;

	// format number
	$(this).val(function(index, value) {
	return value
	.replace(/\D/g, "")
	.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
	;
	});
});
$("#btn_add_row").click(function(){
	$( ".account_label" ).autocomplete( "destroy" );
	$('.journal-entry-container').append('<?php echo trim(preg_replace('/\s\s+/', ' ', $journal_entry_form)); ?>');
	$( ".account_label" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>accounting/autocomplete_account",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr["warning"]("Account not found!", "ABAS Says");
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$(this).prop("disabled", true);
			$( this ).val( ui.item.label );
			$( this ).next().val( ui.item.value );
			return false;
		}
	});
});
function promptSubmit() {
	toastr.clear();
	toastr['warning']('Are you sure? <a class="btn btn-success btn-sm" onclick="javascript:checkautoform()">Continue</a>', "Are you sure?");
}
</script>