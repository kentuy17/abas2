<style>
		.lbl{font-weight:bold;text-align:right;}
	</style>
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

$a1=$a2=$a3 = "";
if(!empty($transaction)){

	if(!empty($transaction_attachments)){
		foreach($transaction_attachments as $attachment){
			switch($attachment['document_name']){
				case "Material and Supplies Issuance Slip":
					$a1=$attachment['document_file'];
				break;
				case "Material and Supplies Return Slip":
					$a2=$attachment['document_file'];
				break;
				case "Other Supporting Documents":
					$a3=$attachment['document_file'];
				break;
			}
		}
	}
}

?>



<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Inventory Returns
		</h2>
	</div>
</div>

	<div class='panel-body'>
	
		<form id='inventory_return_form' role='form' action='<?php echo HTTP_PATH."accounting/inventory_returns/insert/for_clearing/".$MSRS[0]['id']?>' method='POST' enctype='multipart/form-data'>

		<input type='hidden' id='company_id' name='company_id' value='<?php echo $MSRS[0]['company_id'];?>'>

		<div class='tile-stats col-xs-12 col-md-12'>
			<br><label>Materials and Supplies Return Details</label><br>

			<table class="table">
				<tr>
					<td class='lbl'>MSRS #:</td>
					<td><?php echo $MSRS[0]['control_number'] . " (Transaction Code No.".$MSRS[0]['id'].")"?></td>
					<td class='lbl'>Date Returned:</td>
					<td><?php echo date("j F Y h:i A", strtotime($MSRS[0]['return_date']));?></td>
				</tr>
				<tr>
					<td class='lbl'>Company:</td>
					<td><?php echo $MSRS[0]['company_name'];?></td>
					<td class='lbl'>Returned By:</td>
					<td><?php echo $MSRS[0]['return_by'];?></td>
				</tr>
				<tr>
					<td class='lbl'>Returned To:</td>
					<td><?php echo $MSRS[0]['return_to'];?></td>
					<td class='lbl'>Returned From:</td>
					<td><?php echo $MSRS[0]['return_from'];?></td>
				</tr>
				<tr>
					<td class='lbl'>Remarks:</td>
					<td><?php echo $MSRS[0]['remark'];?><td>
				</tr>
			</table>
			<table class="table table-bordered table-striped">
				<thead>
					<th style='text-align:center'>#</th>
					<th style='text-align:center'>Item Code</th>
					<th style='text-align:center'>Description</th>
					<th style='text-align:center'>Quantity</th>
					<th style='text-align:center'>Unit</th>
					<th style='text-align:center'>Unit Price</th>
					<th style='text-align:center'>Amount</th>
				</thead>
				<tbody>
				<?php 
					$total_amount =0;
					$ctr=1;
					foreach($MSRS_details as $row){
						echo "<tr>";
							$item = $this->Inventory_model->getItem($row['item_id']);
							echo "<td>".$ctr."</td>";
							echo "<td>".$item[0]['item_code']."</td>";
							echo "<td>".$item[0]['item_name'].",".$item[0]['brand']." ".$item[0]['particular']."</td>";
							echo "<td>".number_format($row['qty'],2,'.',',')."</td>";
							echo "<td>".$row['unit']."</td>";
             				$unit_price = $row['unit_price']; 
              				echo "<td style='text-align:right'>".number_format($unit_price,2,'.',',')."</td>";
              				echo "<td style='text-align:right'>".number_format(($unit_price*$row['qty']),2,'.',',')."</td>";
						echo "</tr>";
						$ctr++;
						$total_amount = $total_amount + ($unit_price*$row['qty']);
					}
				?>
				<tr>
					<td colspan='6' style='text-align:right'>Total Amount</td>
					<td style='text-align:right'><?php echo number_format($total_amount,2,'.',',');?></td>
					<input type='hidden' id='MSRS_amount' value='<?php echo number_format($total_amount,2,'.','');?>'readonly>
				</tr>
				</tbody>
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
						<td align='center'><input type='checkbox' name='attachment[]' id='MSIS_check' value='Material and Supplies Issuance Slip'
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
						<td>Material and Supplies Issuance Slip (Accounting Copy)</td>
						<?php 
							if(!empty($transaction)) {
								if($a1!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a1.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a1.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="MSIS_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='MSRS_check' value='Material and Supplies Return Slip'
						<?php 
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
						<td>Material and Supplies Return Slip (Accounting Copy)</td>
						<?php 
							if(!empty($transaction)) {
								if($a1!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a2.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a2.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="MSRS_file" disabled></td>';
							}
						?>
					</tr>
					
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='others_check' value='Other Supporting Documents'
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
						<td>Other Supporting Documents</td>
						<?php 
							if(!empty($transaction)) {
								if($a3!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a3.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a3.'</td>';
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
	         	</ul>
			</div>

			<div id="tab_contents" class="tab-content">
				<div role="tabpanel" class='tab-pane fade active in' id="tab_entries" aria-labelledby="tab_entries">
					<?php if(empty($transaction)) {?>
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
											echo "<tr id='row_entry0' class='tbl_row_entry'>
													<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='29'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-11101502
													</td>
													<td>Materials and Supplies</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control credit_amount'  value='".$total_amount."' style='text-align:right' readonly>".number_format($total_amount,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
													</td>
													
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>
												  <tr id='row_entry1' class='tbl_row_entry'></tr>";

										}else{
											echo "<tr id='row_entry0' class='tbl_row_entry'></tr>";
										}
									 
										if(!empty($transaction)) {
											foreach($transaction_journal_entries as $row){
												if($row['reference_table']=='inventory_return'){
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
								<input type="button" class="btn btn-success btn-m" onclick="javascript:approveInventoryReturns(<?php echo $MSRS[0]['id'];?>,<?php echo $transaction['id'];?>);" value="Approve"/>
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

$('#MSIS_check').change(function(){
   $(".MSIS_file").prop("disabled", !$(this).is(':checked'));
    $(".MSIS_file").val("");
});
$('#MSRS_check').change(function(){
   $(".MSRS_file").prop("disabled", !$(this).is(':checked'));
    $(".MSRS_file").val("");
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

	var flag_debit_credit = 0;
	var flag_credit_MSRS = 0;
    $("#table_entries input").each(function() {
    	  
	      var total_debit_amount = 0;
	      var total_credit_amount = 0;
  		  var debit_amount = document.getElementsByName('debit[]');
  		  var credit_amount = document.getElementsByName('credit[]');
  		  var MSRS_amount = parseFloat($('#MSRS_amount').val()).toFixed(2);

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
		  }else{
		  	  flag_debit_credit = 0;
		  	  if(total_credit_amount != MSRS_amount){
		  	  	flag_credit_MSRS = 1;
		  	  }else{
		  	  	flag_credit_MSRS = 0;
		  	  }
		  }
		
    });

    if(flag_debit_credit==1){
    	msg+="Debit and Credit amount are not balance! <br/>";
    }
    if(flag_credit_MSRS==1){
    	msg+="Total Credit amount is not equal with total MSRS amount! <br/>";
    }

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Inventory Returns",
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
			        document.getElementById("inventory_return_form").submit();
			        return true;
		    	}
		    }
		});

	}
}

function approveInventoryReturns(MSRS_id,transaction_id) {

		bootbox.confirm({
			title: "Inventory Returns",
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
					  window.location.href = "<?php echo HTTP_PATH;?>accounting/inventory_returns/approve/for_posting/" + MSRS_id + "/" + transaction_id;
				}
		    }
		});

}

</script>