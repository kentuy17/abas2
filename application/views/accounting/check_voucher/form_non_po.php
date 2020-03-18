<style>
	.lbl{font-weight:bold;text-align:right;}
</style>
<?php

$department_code_options = $vessel_code_options = $contract_code_options = $company_options = $bank_options="";
if(!empty($departments)) {
	foreach($departments as $d){
		$department_code_options	.=	"<option value='".$d->accounting_code."'>".$d->name." </option>";
	}
}

if(!empty($vessels)) {
	foreach($vessels as $v){
		$vessel_code_options	.=	"<option value='".$v->id."'>".$v->name." </option>";
	}
}

if(!empty($contracts)) {
	foreach($contracts as $c){
		$contract_code_options	.=	"<option value='".$c['id']."'>".$c['reference_no']." </option>";
	}
}

if(!empty($banks)) {
	foreach($banks as $b){
		$bank_options	.=	"<option value='".$b['id']."'>".$b['name']." (".$b['code'].")</option>";
	}
}

$cv_date = "";
$disabled = "";


?>

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Check Voucher (Non-PO)
		</h2>
	</div>
</div>
	<div class='panel-body'>
		<form id='cv_form' role='form' action='<?php echo HTTP_PATH."accounting/check_voucher/insert/Non-PO"?>' method='POST' enctype='multipart/form-data'>
		<div class='tile-stats col-xs-12 col-md-12'>
			<br><label>Request Details</label><br>
			<div class='col-xs-12 col-md-4 pull-right'>
				<label>Voucher Date*</label>
				<input type='date' id='cv_date' name='cv_date' class='form-control' value='<?php echo $cv_date?>'><br>
			</div>
			<div class='col-xs-12 col-md-8'>
				<table class='table'>
					<tr>
						<td class='lbl'>RFP No:</td>
						<td width="40%"> <?php echo "&nbsp&nbsp".$rfp[0]['control_number']." (TSCode No.".$rfp[0]['id'].")";?>
							<input type="hidden" name="rfp_no" id="rfp_no" value="<?php echo $rfp[0]['id']?>">
						</td>
						<td class='lbl'>Payee:</td>
						<td><?php echo "&nbsp&nbsp".$payee_name." (".$rfp[0]['payee_type'].")";?>
							<input type="hidden" name="payee" id="payee" value="<?php echo $rfp[0]['payee'];?>">
						</td>
					</tr>
					<tr>
						<td class='lbl'>Company:</td>
						<td> <?php echo "&nbsp&nbsp".$company->name;?>
							<input type="hidden" name="company" id="company" value="<?php echo $company->id?>">
						</td>
						<td class='lbl'>Amount:</td>
						<td><?php echo "&nbsp&nbsp".number_format($rfp[0]['amount'],2,'.',',');?>
							<input type="hidden" name="rfp_amount" id="rfp_amount" value="<?php echo $rfp[0]['amount']?>">
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class='tile-stats col-xs-12 col-md-12'>
			<br><label>Check Details</label><br>
			<div class='col-xs-12 col-md-6'>
				<label>Credit to Bank*</label>
				<select id='bank' name='bank' class='form-control' required>
					<option value=''>Select</option>
					<?php echo $bank_options; ?>
				</select>
			</div>
			<div class='col-xs-12 col-md-3'>
				<label>Check Date*</label>
				<input type='date' id='check_date' name='check_date' class='form-control' required>
			</div>
			<div class='col-xs-12 col-md-3'>
				<label>Check Number*</label>
				<input type='text' id='check_num' name='check_num' class='form-control' required>
			</div>
			
			<div class='col-xs-12 col-md-12'>
				<label>Particulars*</label>
				<textarea id='particulars' name='particulars' class='form-control' required><?php echo $rfp[0]['purpose']?></textarea> 
			</div>
			<br><br><br><br><br><br><br><br><br>
		</div>
		<div class='tile-stats col-xs-12 col-md-12'>
			<div role="tabpanel" data-example-id="togglable-tabs">
				<ul id="tab_list" class="nav nav-tabs bar_tabs" role="tablist" >			 	
		            <li role="presentation" id="tab1" class="active">
			            <a href="#tab_entries" id="entries_tab" name="entries_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Accounting Entries</b>
			            </a>
			        </li>
	         	</ul>
			</div>
			<div id="tab_contents" class="tab-content">
				<div role="tabpanel" class='tab-pane fade active in' id="tab_entries" aria-labelledby="tab_entries">

					<div class='col-xs-12 col-md-4'>
						<label>Department*</label>
						<select id='department_code' name='department_code' class='form-control'>
							<option value='00'>00</option>
							<?php echo $department_code_options; ?>
						</select>
					</div>
					<div class='col-xs-12 col-md-4'>
						<label>Vessel*</label>
						<select id='vessel_code' name='vessel_code' class='form-control'>
							<option value='000'"'>000</option>
                            <?php echo $vessel_code_options; ?>
						</select>
					</div>
					<div class='col-xs-12 col-md-4'>
						<label>Contract*</label>
						<select id='contract_code' name='contract_code' class='form-control'>
							<option value='0000'>0000</option>
                            <?php echo $contract_code_options; ?>
						</select>
					</div>
					<div class='col-xs-12 col-md-7'>
						<label>General Ledger Account*</label>
						<input type='text' id='gl_account' name='gl_account' class='account_label form-control ui-autocomplete-input' placeholder="Auto-complete">
						<input type='hidden' id='gl_account_id' name='gl_account_id' class='account_value'>
						<input type='hidden' id='gl_account_code' name='gl_account_code' class='account_code'>
						<input type='hidden' id='gl_account_name' name='gl_account_name' class='account_name'>
					</div>
					<div class='col-xs-12 col-md-3'>
						<label>Amount*</label>
						<input type='number' id='amount' name='amount' class='form-control'>
					</div>
					<div class='col-xs-12 col-md-2'>
						<label>Type*</label>
						<select id='type' name='type' class='form-control'>
							<option value=''>Select</option>
							<option value='Debit'>Debit</option>
							<option value='Credit'>Credit</option>
						</select>
					</div>	
					<div class='col-xs-12 col-md-10'>
						<label>Memo*</label>
						<input type='text' id='memo' name='memo' class='form-control'>
					</div>
					<div class='col-xs-12 col-md-2'>
						<label style='color:white'>XXXXX</label>
						<a class="btn btn-info btn-m btn-block" onclick="javascript:addEntry();">Add</a>
					</div>
					<div class='clear-fix'></div>
					<br><br><br><br><br><br><br><br><br><br><br>

					<div class='col-xs-12 col-md-12' style="overflow-x: auto">
						<table id="table_entries" data-toggle="table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<td>Account Code</td>
									<td>Account Name</td>
									<td>Memo</td>
									<td>Debit</td>
									<td>Credit</td>
									<td></td>
							</thead>
							<tbody>
								<tr id='row_entry0' class='tbl_row_entry'>
									<td>
										<input type='hidden' id='coa_id_bank' name='coa_id[]' value=''>
										<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
										<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
										<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='00000' readonly>
										<text id='bank_account_code'>--</text>
									</td>
									<td><text id='bank_account_name'>--</text></td>
									<td><input type='hidden' id='memo_bank' name='memo[]' class='form-control' value='' readonly><text id='bank_particulars'></text></td>
									<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
									<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='<?php echo number_format($rfp[0]['amount'],2,'.','')?>' style='text-align:right' readonly><text id='bank_account_amount'><?php echo number_format($rfp[0]['amount'],2,'.',',')?></text>
									</td>
									<td></td>
							    </tr>	 
								<tr id='row_entry1' class='tbl_row_entry'></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
			
			<div class='col-sm-12 col-md-12'>
				<span class="pull-right">
					<input type="button" class="btn btn-success btn-m" onclick="javascript:checkForm();" value="Submit"/>
					<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal" />
				</span>
			</div>

		</form>

	</div>


<script type="text/javascript">

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
		$( ".account_label" ).val( ui.item.label );
		$( ".account_value" ).val( ui.item.value );
		$( ".account_code" ).val( ui.item.account_long_code );
		$( ".account_name" ).val( ui.item.account_name );
		return false;
	}
});

$("#bank").change(function(){
	var bank_coa_id = $(":selected",this).val();
	var particulars = $("#particulars").val();
	$.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>accounting/check_voucher/get_bank_by_coa/"+bank_coa_id,
     success:function(data){
     	var account = $.parseJSON(data);   

     		var code = '00-000-0000-'+account.financial_statement_code+account.general_ledger_code;
     		$('#bank_account_code').text(code);
     		$('#bank_account_name').text(account.name);
     		$('#coa_id_bank').val(bank_coa_id);
     		$('#bank_particulars').text(particulars);
     		$('#memo_bank').val(particulars);
	  }
   });
});

$("#particulars").change(function(){
	var particulars = $(this).val();
	$('#bank_particulars').text(particulars);
     $('#memo_bank').val(particulars);
});

var i_check=1;
var i_tax=0;

function addEntry(){

	var msg="";

	var department = $('#department_code').val();
	var vessel = $('#vessel_code').val();
	var contract = $('#contract_code').val();
	var gl_account_code = $('#gl_account_code').val();
	var gl_account_name = $('#gl_account_name').val();
	var gl_account_id = $('#gl_account_id').val();
	var memo = $('#memo').val();
	var amount = $('#amount').val();
	var type = $('#type').val();

	if(department==""){
		msg+="Department is required.<br>";
	}
	if(vessel==""){
		msg+="Vessel is required.<br>";
	}
	if(contract==""){
		msg+="Contract is required.<br>";
	}
	if(gl_account_id==""){
		msg+="General Ledger Account is required.<br>";
	}
	if(amount==""){
		msg+="Amount is required.<br>";
	}
	if(type==""){
		msg+="Type is required.<br>";
	}
	if(memo==""){
		msg+="Memo is required.<br>";
	}
	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}else{

		var account_code = department+"-"+vessel+"-"+contract+"-"+gl_account_code.substr(6);
		var account_name = gl_account_name;

		if(type=='Debit'){
			var debit_amount = parseFloat(amount).toFixed(2);
			var credit_amount = 0;
		}else{
			var debit_amount = 0;
			var credit_amount = parseFloat(amount).toFixed(2);
		}

		var row_entry;
		row_entry = "<td><input type='hidden' id='coa_id[]' name='coa_id[]' value='"+gl_account_id+"'><input type='hidden' id='department[]' name='department[]' class='form-control' value='"+department+"' readonly><input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='"+vessel+"' readonly><input type='hidden' id='contract[]' name='contract[]' class='form-control' value='"+contract+"' readonly>"+account_code+"</td><td>"+account_name+"</td><td><input type='hidden' id='memo[]' name='memo[]' class='form-control' value='"+memo+"' readonly>"+memo+"</td><td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount'  value='"+debit_amount+"' style='text-align:right' readonly>"+formatNumber(debit_amount)+"</td><td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='"+credit_amount+"' style='text-align:right' readonly>"+formatNumber(credit_amount)+"</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a></td>";
	
		$('#row_entry'+i_check).html(row_entry);
		$('#table_entries').append('<tr class="tbl_row_entry" id="row_entry'+(i_check+1)+'"></tr>');
		i_check++;

		$('#department_code').val('00');
		$('#vessel_code').val('000');
		$('#contract_code').val('0000');
		$('#gl_account').val('');
		$('#amount').val('');
		$('#type').val('');
		$('#memo').val('');

	}
}

function formatNumber (num) {
	return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

$(document).on('click', '.btn-remove-row', function() {
	 $(this).closest('tr').remove();
});

function date_format(datex){
  var date = new Date(datex);
  var day = date.getDate();
  var month = date.getMonth();
  var year = date.getFullYear();

  return year + '-' + (month+1) + '-' + day;
}
function number_format (number, decimals, dec_point, thousands_sep) {
    number = parseFloat(number).toFixed(decimals);

    var nstr = number.toString();
    nstr += '';
    x = nstr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? dec_point + x[1] : '';
    var rgx = /(\d+)(\d{3})/;

    while (rgx.test(x1))
        x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

    return x1 + x2;
}
	
function checkForm() {
	var msg="";

	var row_count =$("#table_entries > tbody > tr").length;
	if (row_count<=1){
		msg+="Please add accounting entries before submitting! <br/>";
	}

	var company = $('#company').val();
	if(company==''){
		msg+="Company is required!<br/>";
	}

	var cv_date = $('#cv_date').val();
	if(cv_date==''){
		msg+="Voucher Date is required!<br/>";
	}

	var bank = $('#bank').val();
	if(bank==''){
		msg+="Credit to Bank is required!<br/>";
	}

	var check_date = $('#check_date').val();
	if(check_date==''){
		msg+="Check Date is required!<br/>";
	}

	var check_num = $('#check_num').val();
	if(check_num==''){
		msg+="Check No. is required!<br/>";
	}

	var particulars = $('#particulars').val();
	if(particulars==''){
		msg+="Particulars is required!<br/>";
	}

	var bank_coa_id = $('#coa_id_bank').val();
	if(bank_coa_id==''){
		$('#bank').val('');
		msg+="There was an error occured during selection of the bank. Kindly select the bank again.<br/>";
	}

	var flag_debit_credit = 0;
	var xdebit_amount = 0;
	 var xcredit_amount = 0;
    $("#table_entries input").each(function() {
    	  
	      var total_debit_amount = 0;
	      var total_credit_amount = 0;
  		  var debit_amount = document.getElementsByName('debit[]');
  		  var credit_amount = document.getElementsByName('credit[]');

		  for (var i = 0; i < debit_amount.length; i++) {
			var debit=debit_amount[i];
		     total_debit_amount = parseFloat((total_debit_amount*1) + (debit.value*1)).toFixed(2);
		  }

		  for (var x = 0; x < credit_amount.length; x++) {
			var credit=credit_amount[x];
		     total_credit_amount = parseFloat((total_credit_amount*1) + (credit.value*1)).toFixed(2);
		  }

	
		  if(total_debit_amount != total_credit_amount){
		  	  flag_debit_credit = 1;
		  	  xdebit_amount = total_debit_amount;
		  	  xcredit_amount = total_credit_amount;
		  }else{
		  	  flag_debit_credit = 0;
		  }
		
    });

    if(flag_debit_credit==1){
    	msg+="Debit("+xdebit_amount+") and Credit("+xcredit_amount+") amount are not balance! <br/>";
    }

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Check Voucher",
			size: 'small',
		    message: "Are you sure you want to submit this voucher?",
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-success'
		        },
		        cancel: {
		            label: 'No',
		            className: 'btn-danger'
		        }
		    },
		    callback: function (result) {
		    	if(result){
			        document.getElementById("cv_form").submit();
			        return true;
		    	}
		    }
		});

	}
}
</script>