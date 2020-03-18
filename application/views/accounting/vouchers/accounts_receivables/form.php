<?php

$department_code_options = $bank_code_options = $vessel_code_options = $contract_code_options = "";
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

$a1=$a2=$a3=$a4 = "";
if(!empty($transaction)){

	if(!empty($transaction_attachments)){
		foreach($transaction_attachments as $attachment){
			switch($attachment['document_name']){
				case "Approved & Signed Copy of Statement of Account":
					$a1=$attachment['document_file'];
				break;
				case "Signed Copy of Billing Summary":
					$a2=$attachment['document_file'];
				break;
				case "Signed Copy of Cargo Out-turn Report":
					$a3=$attachment['document_file'];
				break;
				case "Other Supporting Documents":
					$a4=$attachment['document_file'];
				break;
			}
		}
	}
}

?>


	<style>
		.lbl{font-weight:bold;text-align:right;}
	</style>

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Accounts Receivables 
		</h2>
	</div>
</div>

	<div class='panel-body'>
	
		<form id='accounts_receivable_form' role='form' action='<?php echo HTTP_PATH."accounting/insert/accounts_receivables"?>' method='POST' enctype='multipart/form-data'>

		<div class='tile-stats col-xs-12 col-md-12'>
			<br><label>Statement Of Account Details</label><br>

			<input type='hidden' id='soa_id' name='soa_id' value='<?php echo $SOA['id'];?>'>

			<input type='hidden' id='company_id' name='company_id' value='<?php echo $SOA['company_id'];?>'>

			<table class="table">
				<tr>
					<td class='lbl'>SOA #:</td>
					<td><?php echo $SOA['control_number'] . " (Transaction Code No.".$SOA['id'].")"?></td>
				</tr>
				<tr>
					<td class='lbl'>Company:</td>
					<td><?php echo $SOA['company']->name;?></td>
					<td class='lbl'>Date SOA Created:<td>
					<td><?php echo date("j F Y h:i A", strtotime($SOA['created_on']));?></td>
				</tr>
				<tr>
					<td class='lbl'>Client:</td>
					<td><?php echo $SOA['client']['company'];?></td>
					<td class='lbl'>Date SOA Received by Client:<td>
						<?php 
							if(isset($SOA['sent_to_client_on'])){
								$date_received = date("j F Y h:i A", strtotime($SOA['sent_to_client_on']));
							}else{
								$date_received = "(Not yet received)";
							}
						?>
					<td><?php echo $date_received;?></td>
				</tr>
				<tr>
					<td class='lbl'>Total Amount:</td>
					<td >
						<?php
							if($SOA['add_tax']==1){
								$soa_amount = number_format($SOA_amount['grandtotal_add_tax'],2,'.','');
								echo "PHP ".number_format($SOA_amount['grandtotal_add_tax'],2,'.',',');	
							}else{
								$soa_amount = number_format($SOA_amount['grandtotal_less_tax'],2,'.','');
								echo "PHP ".number_format($SOA_amount['grandtotal_less_tax'],2,'.',',');
							}
							//$soa_amount = $SOA_amount['grandtotal'];
							//echo "PHP ".number_format($SOA_amount['grandtotal'],2,'.',',');
						?>

						<input type='hidden' id='soa_amount' name='soa_amount' value='<?php echo number_format($soa_amount,2,'.','');?>'>

					</td>
					<td class='lbl'>Services:<td>
					<td><?php echo $SOA['services'];?></td>
				</tr>
				<tr>
					<td class='lbl'>Description:</td>
					<td><?php echo $SOA['description'];?></td>
					<td class='lbl'>Status:<td>
					<td>
					<?php 
						if(empty($transaction)){
							echo $SOA['status'];
						}else{
							if($transaction['stat']==0){
								echo "For Posting";
							}elseif($transaction['stat']==1){
								echo "Posted";
							}
						}
					?>
					</td>
				</tr>
			</table>
		</div>

		<div class='tile-stats col-xs-12 col-md-12'>
			<br><label>Documents Submitted</label><br>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<td></td>
						<td>Documents</td>
						<td>Scanned Copies (PDF or Image format)</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='soa_check' value='Approved & Signed Copy of Statement of Account'
						<?php 
							if(!empty($transaction)){
								if($a1){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Approved & Signed Copy of Statement of Account</td>
						<?php 
							if(!empty($transaction)) {
								if($a1!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a1.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a1.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="soa_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='bill_check' value='Signed Copy of Billing Summary' <?php 
							if(!empty($transaction)){
								if($a2){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Signed Copy of Billing Summary</td>
						<?php 
							if(!empty($transaction)) {
								if($a2!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a2.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a2.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="billing_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='cor_check' value='Signed Copy of Cargo Out-turn Report'
						<?php 
							if(!empty($transaction)){
								if($a3){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Signed Copy of Cargo Out-turn Report</td>
						<?php 
							if(!empty($transaction)) {
								if($a3!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a3.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a3.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="cor_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='others_check' value='Other Supporting Documents'
						<?php 
							if(!empty($transaction)){
								if($a4){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Other Supporting Documents</td>
						<?php 
							if(!empty($transaction)) {
								if($a4!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a4.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a4.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="others_file" disabled></td>';
							}
						?>
					</tr>
					
				</tbody>
			</table>
		</div>

		<div class='tile-stats col-xs-12 col-md-12'>
			<div role="tabpanel" data-example-id="togglable-tabs">
				<ul id="tab_list" class="nav nav-tabs bar_tabs" role="tablist" >			 	
		            <li role="presentation" id="tab1" class="active">
			            <a href="#tab_entries" id="entries_tab" name="entries_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Accounting Entries</b>
			            </a>
		            </li>
		             <?php if(isset($transaction) && $transaction['stat']==1){ ?>
		            <li role="presentation" id="tab2">
			            <a href="#tab_reconcile" id="entries_tab" name="reconcile_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Posted Reconciling Entries</b>
			            </a>
		            </li>
		            <?php } ?>
	         	</ul>
			</div>

			<div id="tab_contents" class="tab-content">
				<div role="tabpanel" class='tab-pane fade active in' id="tab_entries" aria-labelledby="tab_entries">
					<?php if(empty($transaction)) {?>
						<div class='col-xs-12 col-md-4'>
							<label>Department*</label>
							<select id='department_code' name='department_code' class='form-control'>
								<!--<option value=''>Select</option>-->
								<option value='00'>00</option>
								<?php echo $department_code_options; ?>
							</select>
						</div>
						<div class='col-xs-12 col-md-4'>
							<label>Vessel*</label>
							<select id='vessel_code' name='vessel_code' class='form-control'>
								<!--<option value=''>Select</option>-->
								<option value='000'"'>000</option>
	                            <?php echo $vessel_code_options; ?>
							</select>
						</div>
						<div class='col-xs-12 col-md-4'>
							<label>Contract*</label>
							<select id='contract_code' name='contract_code' class='form-control'>
								<!--<option value=''>Select</option>-->
								<option value='0000'>0000</option>
	                            <?php echo $contract_code_options; ?>
							</select>
						</div>
						<div class='col-xs-12 col-md-5'>
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
						<div class='col-xs-12 col-md-2'>
							<label style='color:white'>XXXXX</label>
							<a class="btn btn-info btn-m btn-block" onclick="javascript:addEntry();">Add</a>
						</div>
						<div class='clear-fix'></div>
						<br><br><br><br><br><br><br><br>
					<?php } ?>
					
					<div class='col-xs-12 col-md-12'>
						<table id="table_entries" data-toggle="table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<td>Account Code</td>
									<td>Account Name</td>
									<td>Debit</td>
									<td>Credit</td>
									<?php if(empty($transaction)) {?>
										<td></td>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
									<?php 

									$total_debit = 0;
									$total_credit = 0;

										if(empty($transaction)) {
											echo "
												  <tr id='row_entry0' class='tbl_row_entry'>
													<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='10'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-00-000-0000-1102-1150
													</td>
													<td>Trade receivables</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount'  value='".$soa_amount."' style='text-align:right' readonly>".number_format($soa_amount,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='0' style='text-align:right' readonly>0</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>
												  <tr id='row_entry1' class='tbl_row_entry'></tr>";

										}else{
											echo "<tr id='row_entry0' class='tbl_row_entry'></tr>";
										}
									?>
									<?php 
										if(!empty($transaction)) {
											foreach($transaction_journal_entries as $row){
												if($row['reference_table']=='statement_of_accounts'){
													echo "<tr>";
														$account = $this->Accounting_model->getAccount($row['coa_id']);
														
														if($row['department_id'] == 0){
															$department_code = "00";
														}else{
															$department_code = $row['department_id'];
														}
														if($row['vessel_id'] == 0){
															$vessel_code = "000";
														}else{
															$vessel_code = $row['vessel_id'];
														}
														if($row['contract_id'] == 0){
															$contract_code = "0000";
														}else{
															$contract_code =$row['contract_id'];
														}

														//echo "<td>".$department_code ."-".$vessel_code."-".$contract_code."-".substr($account['code'],2)."</td>";
														//echo "<td>".$account['name']."</td>";
														echo "<td>".$row['account_code']."</td>";
														echo "<td>".$row['account_name']."</td>";
														echo "<td style='text-align:right'>".number_format($row['debit_amount'],2,'.',',')."</td>";
														echo "<td style='text-align:right'>".number_format($row['credit_amount'],2,'.',',')."</td>";
													echo "</tr>";

													$total_debit = $total_debit + $row['debit_amount'];
													$total_credit = $total_credit + $row['credit_amount'];
												}
											}

											echo "<tr>
													<td colspan='2' style='text-align:right'>Total</td>
													<td style='text-align:right'>".number_format($total_debit,2,'.',',')."</td>
													<td style='text-align:right'>".number_format($total_credit,2,'.',',')."</td>
												</tr>";
										} 
									?>
							</tbody>
						</table>
					</div>
				</div>

				<div role="tabpanel" class='tab-pane fade in' id="tab_reconcile" aria-labelledby="tab_reconcile">
					<div class='col-xs-12 col-md-12'>
						<table id="table_entries" data-toggle="table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<td align='center'>OR #</td>
									<td>Account Code</td>
									<td>Account Name</td>
									<td>Debit</td>
									<td>Credit</td>
								</tr>
							</thead>
							<tbody>
								<?php 

									$total_debit = 0;
									$total_credit = 0;

										if(!empty($transaction)) {
											//foreach($reconciling_entries as $row){
											foreach($transaction_journal_entries as $row){
												if($row['reference_table']=='payments' && $row['coa_id']==10 && $row['stat']==1){
													echo "<tr>";
														$account = $this->Accounting_model->getAccount($row['coa_id']);
														
														if($row['department_id'] == 0){
															$department_code = "00";
														}else{
															$department_code = $row['department_id'];
														}
														if($row['vessel_id'] == 0){
															$vessel_code = "000";
														}else{
															$vessel_code = $row['vessel_id'];
														}
														if($row['contract_id'] == 0){
															$contract_code = "0000";
														}else{
															$contract_code =$row['contract_id'];
														}

														$OR = $this->Collection_model->getOfficialReceipts($row['reference_id']);
														$arr1 = array();
														foreach($OR as $num1){
															$arr1[] = $num1->control_number;
														}
														$OR_str = implode(', ',$arr1);

														echo "<td align='center'>".$OR_str."</td>";

														//echo "<td>".$department_code ."-".$vessel_code."-".$contract_code."-".substr($account['code'],2)."</td>";
														//echo "<td>".$account['name']."</td>";
														echo "<td>".$row['account_code']."</td>";
														echo "<td>".$row['account_name']."</td>";
														echo "<td style='text-align:right'>".number_format($row['debit_amount'],2,'.',',')."</td>";
														echo "<td style='text-align:right'>".number_format($row['credit_amount'],2,'.',',')."</td>";
													echo "</tr>";
													
													$total_debit = $total_debit + $row['debit_amount'];
													$total_credit = $total_credit + $row['credit_amount'];
												}
											}
											echo "<tr>
													<td colspan='3' style='text-align:right'>Total</td>
													<td style='text-align:right'>".number_format($total_debit,2,'.',',')."</td>
													<td style='text-align:right'>".number_format($total_credit,2,'.',',')."</td>
												</tr>";
										}
									?>
							</tbody>
						</table>
					</div>
				</div>

			</div>

		</div>
			
			<div class='col-sm-12 col-md-12'>
				<span class="pull-right">
					<?php if(empty($transaction)) {?>
						<input type="button" class="btn btn-success btn-m" onclick="javascript:checkForm();" value="Submit"/>
						<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal" />
					<?php } ?>
					<?php if(!empty($transaction)) {?>
						<?php if($transaction['stat']==0){?>
							<?php if($this->Abas->checkPermissions("accounting|approve_vouchers",false)){ ?>
								<input type="button" class="btn btn-success btn-m" onclick="javascript:approveAR(<?php echo $SOA['id'];?>,<?php echo $transaction['id'];?>);" value="Approve"/>
							<?php } ?>
						<?php } ?>
						<input type="button" class="btn btn-danger btn-m" value="Close" data-dismiss="modal" />
					<?php } ?>

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


$('#soa_check').change(function(){
   $(".soa_file").prop("disabled", !$(this).is(':checked'));
    $(".soa_file").val("");
});
$('#bill_check').change(function(){
   $(".billing_file").prop("disabled", !$(this).is(':checked'));
    $(".billing_file").val("");
});
$('#cor_check').change(function(){
   $(".cor_file").prop("disabled", !$(this).is(':checked'));
    $(".cor_file").val("");
});
$('#others_check').change(function(){
   $(".others_file").prop("disabled", !$(this).is(':checked'));
    $(".others_file").val("");
});


var i_check=1;

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
	
function checkForm() {
	var msg="";

	var row_count =$("#table_entries > tbody > tr").length;
	if (row_count<=1){
		msg+="Please add accounting entries before submitting! <br/>";
	}

    /*if( document.getElementById("attach_file[0]").files.length == 0 ){
    	msg+="Please make sure that an Approved and Signed Copy of Statement of Account is attached! <br/>";
	}*/

	var flag_debit_credit = 0;
	var flag_credit_soa = 0;
    $("#table_entries input").each(function() {
    	  
	      var total_debit_amount = 0;
	      var total_credit_amount = 0;
  		  var debit_amount = document.getElementsByName('debit[]');
  		  var credit_amount = document.getElementsByName('credit[]');
  		  var soa_amount = parseFloat($('#soa_amount').val()).toFixed(2);

		  for (var i = 0; i < debit_amount.length; i++) {
			var debit=debit_amount[i];
		     total_debit_amount = parseFloat((total_debit_amount*1) + (debit.value*1)).toFixed(2);
		  }

		  for (var x = 0; x < credit_amount.length; x++) {
			var credit=credit_amount[x];
		     total_credit_amount = parseFloat((total_credit_amount*1) + (credit.value*1)).toFixed(2);
		  }


		  //console.log(soa_amount + "--" + total_credit_amount);

		  if(total_debit_amount != total_credit_amount){
		  	  flag_debit_credit = 1;
		  }else{
		  	  flag_debit_credit = 0;
		  	  if(total_credit_amount != soa_amount){
		  	  	flag_credit_soa = 1;
		  	  }else{
		  	  	flag_credit_soa = 0;
		  	  }
		  }
		
    });

    if(flag_debit_credit==1){
    	msg+="Debit and Credit amount are not balance! <br/>";
    }
    if(flag_credit_soa==1){
    	msg+="Total Credit amount is not equal with SOA amount! <br/>";
    }

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Accounts Receivables",
			size: 'small',
		    message: "Are you sure you want to submit this entry?",
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

		    		$('body').addClass('is-loading'); 
					$('#modalDialog').modal('toggle'); 

			        document.getElementById("accounts_receivable_form").submit();
			        return true;
		    	}
		    }
		});

	}
}

function approveAR(soa_id,transaction_id) {

		bootbox.confirm({
			title: "Accounts Receivables",
			size: 'small',
		    message: "Are you sure you want to approve this entry?",
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
		    	if(result==true){

		    		$('body').addClass('is-loading'); 
					$('#modalDialog').modal('toggle'); 
					
					  window.location.href = "<?php echo HTTP_PATH;?>accounting/update/accounts_receivables/" + soa_id + "/" + transaction_id;
				}
		    }
		});

}

</script>