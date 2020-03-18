<?php

$company_options = $bank_options="";

if(!empty($companies)) {
	foreach($companies as $x){
		$company_options	.=	"<option value='".$x->id."'>".$x->name."</option>";
	}
}

$cv_date = "";
$disabled = "";

?>

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Check Voucher (PO)
		</h2>
	</div>
</div>

	<div class='panel-body'>
	
		<form id='cv_form' role='form' action='<?php echo HTTP_PATH."accounting/check_voucher/insert/PO"?>' method='POST' enctype='multipart/form-data'>
		<div class='tile-stats col-xs-12 col-md-12'>
			<div class='col-xs-12 col-md-8'>
				<br><label>Company*</label><br>
				<select id='company' name='company' class='form-control' required>
					<option value=''>Select</option>
					<?php echo $company_options; ?>
				</select>
				<input type="hidden" name="payee" id="payee" value="<?php echo $supplier['id']?>">
			</div>

			<div class='col-xs-12 col-md-4 pull-right'>
				<br><label>Voucher Date*</label><br>
				<input type='date' id='cv_date' name='cv_date' class='form-control' value='<?php echo $cv_date?>' <?php echo $disabled?>>
			</div>
			<br><br><br><br>
			<hr>
			<br><label>Accounts Payable Voucher(s) for <?php echo $supplier['name'];?></label><br>
			<div style='overflow-x: auto'>
				<table id="apv_table" name="apv_table" class="table table-bordered table-striped table-hover">
					<tr>
						<td>Merge?</td>
						<td>PO TSCode No.</td>
						<td>APV TSCode No.</td>
						<td>APV Control No.</td>
						<td>Company</td>
						<td>APV Date</td>
						<td>Amount</td>
					</tr>
					<?php
						foreach($supplier_apvs as $apv){
							echo "<tr>";
								echo "<td><input type='checkbox' id='merge_to_cv[]' name='merge_to_cv[]' value='".$apv->id."' class='form-control merge_apv'></td>";
								echo "<td>".$apv->po_no."</td>";
								echo "<td>".$apv->id."</td>";
								echo "<td>".$apv->control_number."</td>";
								echo "<td>".$apv->company_name."</td>";
								echo "<td>".date('Y-m-d',strtotime($apv->date_created))."</td>";
								echo "<td>".number_format($apv->amount,2,'.',',')."</td>";
							echo "</tr>";
						}
					?>
				</table>
				<text>Note: You may select multiple APVs to merge into one CV.</text>
				<br><br>
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
				<label>Check Number</label>
				<input type='text' id='check_num' name='check_num' class='form-control'>
			</div>
			
			<div class='col-xs-12 col-md-12'>
				<label>Particulars*</label>
				<textarea id='particulars' name='particulars' class='form-control' required placeholder='Payment for Materials and Supplies...'></textarea> 
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
					<div class='col-xs-12 col-md-12' style="overflow-x: auto">
						<table id="table_entries" data-toggle="table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<td>Account Code</td>
									<td>Account Name</td>
									<td>Debit</td>
									<td>Credit</td>
							</thead>
							<tbody>
								<tr id='row_entry0' class='tbl_row_entry'>
									<td>
										00-000-0000-21212000
									</td>
								<td>Trade payables</td>
									<td style='text-align:right'><input type='hidden' id='tp_debit' name='tp_debit' class='form-control debit_amount' value='' style='text-align:right' readonly><text id='trade_payable_amount'>0</text>
									</td>
									<td style='text-align:right'><input type='hidden' id='tp_credit' name='tp_credit' class='form-control credit_amount'  value='0' style='text-align:right' readonly>0</td>
							  </tr>
							   <tr id='row_entry1' class='tbl_row_entry'>
									<td>
										<text id='bank_account_code'>--</text>
									</td>
									<td><text id='bank_account_name'>--</text></td>
									<td style='text-align:right'><input type='hidden' id='bank_debit' name='bank_debit' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
									<td style='text-align:right'><input type='hidden' id='bank_credit' name='bank_credit' class='form-control credit_amount'  value='' style='text-align:right' readonly><text id='bank_account_amount'>0</text>
									</td>
							  </tr>	 
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

var new_trade_payable_amount = 0;
var new_bank_amount = 0;

$('#apv_table').on('click','.merge_apv',function(){
	var apv_id = $(this).val();
	var tp_debit = $('#tp_debit').val();
	var bank_credit = $('#bank_credit').val();
	var is_checked = $(this).prop('checked');

	$.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>accounting/check_voucher/get_APV_amount/"+apv_id,
     success:function(data){
     	var amount = $.parseJSON(data);
	     	if(is_checked==true){
		     	new_trade_payable_amount = new_trade_payable_amount + parseFloat(amount);
		     	new_bank_amount = new_bank_amount + parseFloat(amount);
	     	}else{
	     		new_trade_payable_amount = new_trade_payable_amount - parseFloat(amount);
	     		new_bank_amount = new_bank_amount - parseFloat(amount);
	     	}
	     $('#tp_debit').val(new_trade_payable_amount);
		 $('#trade_payable_amount').text(number_format(new_trade_payable_amount,2,'.',','));
		 $('#bank_credit').val(new_bank_amount);
		 $('#bank_account_amount').text(number_format(new_bank_amount,2,'.',','));
     }	
   });	
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
		$( ".account_label" ).val( ui.item.label );
		$( ".account_value" ).val( ui.item.value );
		$( ".account_code" ).val( ui.item.account_long_code );
		$( ".account_name" ).val( ui.item.account_name );
		return false;
	}
});

$("#company").change(function(){
	var company_id = $(this).val();
	var supplier_id = $('#payee').val();
	var apvs

	$('#tp_debit').val(0);
	$('#trade_payable_amount').text(0);
	$('#bank_credit').val(0);
	$('#bank_amount').text(0);

	 $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>accounting/check_voucher/get_APV_supplier_by_company/"+supplier_id+"/"+company_id,
	     success:function(data){
	     	var apvs = $.parseJSON(data);

	     	$("#apv_table").empty();

	     	$("#apv_table").append("<tr><td>Merge?</td><td>PO TSCode No.</td><td>APV TSCode No.</td><td>APV Control No.</td><td>Company</td><td>APV Date</td><td>Amount</td></tr>");

		    $.each(apvs, function(idx, elem){
		        $("#apv_table").append("<tr><td><input type='checkbox' id='merge_to_cv[]' name='merge_to_cv[]' value='"+elem.id+"' class='form-control merge_apv'></td><td>"+elem.po_no+"</td><td>"+elem.id+"</td><td>"+elem.control_number+"</td><td>"+elem.company_name+"</td><td>"+date_format(elem.date_created)+"</td><td>"+number_format(elem.amount,2,'.',',')+"</td></tr>");
		    });

		   new_trade_payable_amount = 0;
		   new_bank_amount = 0;
	     }
	  });

	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>accounting/check_voucher/get_banks_by_company/"+company_id,
	     success:function(data){

	     	var bank = $.parseJSON(data);   

     	   $('#bank').find('option').remove().end().append('<option value="">Select</option>').val('');

	        for(var i = 0; i < bank.length; i++){
	       		var dest = bank[i];
	       		var option = $('<option />');
			    option.attr('value',dest.id).text(dest.name + " (" + dest.code + ")");
			    $('#bank').append(option);
	        }

		  }
	   });
});

$("#bank").change(function(){
	var bank_coa_id = $(":selected",this).val();
	$.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>accounting/check_voucher/get_bank_by_coa/"+bank_coa_id,
     success:function(data){
     	var account = $.parseJSON(data);   

     		var code = '00-000-0000-'+account.financial_statement_code+account.general_ledger_code;
     		$('#bank_account_code').text(code);
     		$('#bank_account_name').text(account.name);
	  }
   });
});


var i_check=10016;
var i_tax=0;

function addEntry(){

	var msg="";

	var department = $('#department_code').val();
	var vessel = $('#vessel_code').val();
	var contract = $('#contract_code').val();
	var gl_account_code = $('#gl_account_code').val();
	var gl_account_name = $('#gl_account_name').val();
	var gl_account_id = $('#gl_account_id').val();
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
		row_entry = "<td><input type='hidden' id='coa_id[]' name='coa_id[]' value='"+gl_account_id+"'><input type='hidden' id='department[]' name='department[]' class='form-control' value='"+department+"' readonly><input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='"+vessel+"' readonly><input type='hidden' id='contract[]' name='contract[]' class='form-control' value='"+contract+"' readonly>"+account_code+"</td><td>"+account_name+"</td><td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount'  value='"+debit_amount+"' style='text-align:right' readonly>"+formatNumber(debit_amount)+"</td><td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='"+credit_amount+"' style='text-align:right' readonly>"+formatNumber(credit_amount)+"</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a></td>";
	
		$('#row_entry'+i_check).html(row_entry);
		$('#table_entries').append('<tr class="tbl_row_entry" id="row_entry'+(i_check+1)+'"></tr>');
		i_check++;

		$('#department_code').val('00');
		$('#vessel_code').val('000');
		$('#contract_code').val('0000');
		$('#gl_account').val('');
		$('#amount').val('');
		$('#type').val('');

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

	var particulars = $('#particulars').val();
	if(particulars==''){
		msg+="Particulars is required!<br/>";
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