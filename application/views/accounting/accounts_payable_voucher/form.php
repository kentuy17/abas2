<style>
		.lbl{font-weight:bold;text-align:right;}
	</style>
<?php

$department_code_options = $vessel_code_options = $contract_code_options = $tax_code_options ="";
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

if(!empty($tax_codes)) {
	foreach($tax_codes as $x){
		$tax_code_options	.=	"<option value='".$x->id."'>".$x->tax_code." (".($x->tax_rate*100)."%) </option>";
	}
}

$apv_date = "";
$disabled = "";

$a1=$a2=$a3=$a4= "";
if(!empty($apv)){

	$disabled = "readonly";
	$apv_date = date('Y-m-d',strtotime($apv[0]['date_created']));



	if(!empty($apv_attachments)){
		foreach($apv_attachments as $attachment){
			switch($attachment['document_name']){
				case "Sales Invoice":
					$a1=$attachment['document_file'];
				break;
				case "Purchase Order":
					$a2=$attachment['document_file'];
				break;
				case "Receiving Report":
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

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Accounts Payable
		</h2>
	</div>
</div>

	<div class='panel-body'>
	
		<form id='apv_form' role='form' action='<?php echo HTTP_PATH."accounting/accounts_payable_voucher/insert"?>' method='POST' enctype='multipart/form-data'>

		<input type='hidden' id='company_id' name='company_id' value='<?php echo $rr[0]['company_id'];?>'>


		
		<div class='tile-stats col-xs-12 col-md-12'>
			<div class='col-xs-12 col-md-4 pull-right'>
				<br><label>Voucher Date*</label><br>
				<input type='date' id='apv_date' name='apv_date' class='form-control' value='<?php echo $apv_date?>' <?php echo $disabled?>>
			</div>
			<br><br><br><br>
			<br><label>Payable Details</label><br>
			<table class="table">
				<tr>
					<td class='lbl'>RR #:</td>
					<td><?php echo $rr[0]['id']?></td>
					<td class='lbl'>Delivery Date:</td>
					<td><?php echo date('F j, Y',strtotime($rr[0]['tdate']))?></td>
					<input type='hidden' id='rr_id' name='rr_id' value ='<?php echo $rr[0]['id']?>'>
				</tr>
				<tr>
					<td class='lbl'>PO #:</td>
					<td><?php echo $po['id']?></td>
					<td class='lbl'>Payee:</td>
					<td><?php echo $supplier['name'];?></td>
					
				</tr>
				<tr>
					<td class='lbl'>Company:</td>
					<td><?php echo $company->name;?></td>
					
					<td class='lbl'>Amount:</td>
					<td><?php echo number_format($rr[0]['amount'],2,'.',',');?>
					</td>
				</tr>
				<tr>
					<td class='lbl'>Remarks:</td>
					<td colspan='2'><?php echo $rr[0]['remark'];?></td>
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
						<td align='center'><input type='checkbox' name='attachment[]' id='sales_invoice_check' value='Sales Invoice'
						<?php 
							if(!empty($apv)){
								if($a1){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Sales Invoice*</td>
						<?php 
							if(!empty($apv)) {
								if($a1!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/accounts_payable/attachments/'.$a1.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a1.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="sales_invoice_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='purchase_order_check' value='Purchase Order'
						<?php 
							if(!empty($apv)){
								if($a2){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Purchase Order*</td>
						<?php 
							if(!empty($apv)) {
								if($a2!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/accounts_payable/attachments/'.$a2.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a2.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="purchase_order_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='receiving_report_check' value='Receiving Report'
						<?php 
							if(!empty($apv)){
								if($a3){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Receiving Report*</td>
						<?php 
							if(!empty($apv)) {
								if($a3!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/accounts_payable/attachments/'.$a3.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a3.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="receiving_report_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='others_check' value='Other Supporting Documents'
						<?php 
							if(!empty($apv)){
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
							if(!empty($apv)) {
								if($a4!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/accounts_payable/attachments/'.$a4.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a4.'</td>';
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
			            <?php 
			            	$company_top_20000 = $this->Abas->isCompanyTop20000($company->id);
			            	if($company_top_20000==TRUE){ ?>
					             <li role="presentation" id="tab2">
						            <a href="#tab_taxcode" id="taxcode_tab" name="taxcode_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Expanded Withholding Tax Code</b>
						            </a>
					            </li>
					    <?php } ?>
		             <li role="presentation" id="tab3">
			            <a href="#tab_reconcile" id="reconcile_tab" name="reconcile_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Posted Reconciling Entries</b>
			            </a>
		            </li>
	         	</ul>
			</div>

			<div id="tab_contents" class="tab-content">
				<div role="tabpanel" class='tab-pane fade active in' id="tab_entries" aria-labelledby="tab_entries">
					<?php if(empty($apv)) {?>
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
					
					<div class='col-xs-12 col-md-12' style="overflow-x: auto">
						<table id="table_entries" data-toggle="table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<td>Account Code</td>
									<td>Account Name</td>
									<td>Debit</td>
									<td>Credit</td>
									<?php if(empty($apv)){ ?>
									<td></td>
									<?php } ?>
							</thead>
							<tbody>
								<?php 

									$total_debit = 0;
									$total_credit = 0;
									if(empty($apv)){
										$po_amount = $rr[0]['amount'];

										$po_taxes = $this->Abas->computePurchaseTaxes($po_amount,$rr[0]['supplier_id'], $po['extended_tax'], $company->id);

											echo "
												  <tr id='row_entry0' class='tbl_row_entry'>
													<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='".AP_CLEARING."'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-71997902
													</td>
													<td>AP Clearing</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount'  value='".$po_taxes['vatable_purchases']."' style='text-align:right' readonly>".number_format($po_taxes['vatable_purchases'],2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='0' style='text-align:right' readonly>0</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>

												  <tr id='row_entry1' class='tbl_row_entry'>
													<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='".TRADE_PAYABLE."'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-21212000
													</td>
													<td>Trade payables</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount'  value='0' style='text-align:right' readonly>0</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='".$po_taxes['accounts_payable']."' style='text-align:right' readonly>".number_format($po_taxes['accounts_payable'],2,'.',',')."
													</td>
													<td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>

												   <tr id='row_entry2' class='tbl_row_entry'>
													<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='".INPUT_TAX."'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-11101500
													</td>
													<td>Input Tax</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount'  value='".$po_taxes['vat']."' style='text-align:right' readonly>".number_format($po_taxes['vat'],2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='0' style='text-align:right' readonly>0</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";

												  echo "<input type='hidden' id='taxable_amount' name='taxable_amount' value='".$po_taxes['vatable_purchases']."'>";

												  if($company_top_20000==TRUE){

												  	echo "<input type='hidden' id='wtax_amount' name='wtax_amount' value='".$po_taxes['withholding_tax_expanded']."'>";

												  	echo "<tr id='row_entry3' class='tbl_row_entry'>
															<td>
																<input type='hidden' id='coa_id[]' name='coa_id[]' value='".WITHOLDING_TAX_EXPANDED."'>
																<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
																<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
																<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
																00-000-0000-21212036
															</td>
															<td>Withholding tax - Expanded</td>
															<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount'  value='0' style='text-align:right' readonly>0</td>
															<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='".$po_taxes['withholding_tax_expanded']."' style='text-align:right' readonly>".number_format($po_taxes['withholding_tax_expanded'],2,'.',',')."
															</td>
															<td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a>
															</td>
														  </tr>";
												  }
												 
												  echo "<tr id='row_entry4' class='tbl_row_entry'></tr>";
									}else{
										foreach($apv_entries as $entries){
											$account = $this->Accounting_model->getAccount($entries['coa_id']);
											echo '<tr>';
												echo '<td style="text-align:center">'.$account['code'].'</td>';
												echo '<td>'.$account['name'].'</td>';
												echo'<td style="text-align:right">'.number_format($entries['debit_amount'],2,'.',',').'</td>';
												echo '<td style="text-align:right">'.number_format($entries['credit_amount'],2,'.',',').'</td>';
											echo '</tr>';
											$total_debit = $total_debit + $entries['debit_amount'];
											$total_credit = $total_credit + $entries['credit_amount'];
										}

										echo '<tr>';
											echo '<td colspan="2" style="text-align:right">Total</td>';
											echo '<td style="text-align:right">'.number_format($total_debit,2,'.',',').'</td>';
											echo '<td style="text-align:right">'.number_format($total_credit,2,'.',',').'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div role="tabpanel" class='tab-pane fade in' id="tab_taxcode" aria-labelledby="tab_taxcode">
					<?php if(empty($apv)){ ?>
					<div class='col-xs-12 col-md-3'>
						<label>ATC*</label>
						<select id='atc' name='atc' class='form-control' required>
							<option value=''>Select</option>
							<?php echo $tax_code_options; ?>
						</select>
					</div>
					<div class='col-xs-12 col-md-7'>
						<label>Description*</label>
						<input type='text' id='atc_desc' name='atc_desc' class='form-control' required>
						<input type='hidden' id='atc_code' name='atc_code' class='form-control' required>
						<input type='hidden' id='tax_rate' name='tax_rate' class='form-control' required>
					</div>
					<div class='col-xs-12 col-md-2'>
						<label style='color:white'>XXXXX</label>
						<a class="btn btn-info btn-m btn-block" onclick="javascript:addATC();">Add</a>
					</div>
					<div class='clear-fix'></div>
					<br><br><br><br><br>
					<?php } ?>
					<div class='col-xs-12 col-md-12' style="overflow-x: auto">
						<table id="table_tax" data-toggle="table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<td>Tax Code</td>
									<td>Description</td>
									<td>Tax Rate</td>
									<td>W-tax Amount</td>
									<td>Taxable Amount</td>
									<?php if(empty($apv)){ ?>
									<td></td>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<tr id='row_tax0' class='tbl_row_tax'></tr>
								<?php 
									if(!empty($apv_wtax)){
										foreach($apv_wtax as $tax){
											echo "<tr>";
												echo "<td>".$tax['atc']."</td>";
												echo "<td>".$tax['atc_description']."</td>";
												echo "<td>".(number_format($tax['tax_rate']*100,2,'.',''))."%</td>";
												echo "<td>".number_format($tax['wtax_amount'],2,'.',',')."</td>";
												echo "<td>".number_format($tax['taxable_amount'],2,'.',',')."</td>";
											echo "</tr>";
										}
									}else{
										if(!empty($apv)){
											echo "<tr>";
												echo "<td colspan='5'><center>No matching records found</center></td>";
											echo "</tr>";
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div role="tabpanel" class='tab-pane fade in' id="tab_reconcile" aria-labelledby="tab_reconcile">
					<div class='col-xs-12 col-md-12' style="overflow-x: auto">
						<table id="table_entriesx" data-toggle="table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<td>Account Code</td>
									<td>Account Name</td>
									<td>Debit</td>
									<td>Credit</td>
									<td>Memo</td>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach($reconciling_entries as $entry){
										echo "<tr>";
											echo "<td>00-000-0000-".$entry['financial_statement_code'].$entry['general_ledger_code']."</td>";
											echo "<td>".$entry['name']."</td>";
											echo "<td>".number_format($entry['debit_amount'],2,'.',',')."</td>";
											echo "<td>".number_format($entry['credit_amount'],2,'.',',')."</td>";
											echo "<td>".$entry['remark']."</td>";
										echo "</tr>";
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
					<?php if(empty($apv)) {?>
						<input type="button" class="btn btn-success btn-m" onclick="javascript:checkForm();" value="Submit"/>
						<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal" />
					<?php }else{?>
						<a class="btn btn-info btn-m force-pageload" target="_blank" href="<?php echo HTTP_PATH.'accounting/accounts_payable_voucher/print/'.$apv[0]['id'];?>">Print</a>
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

$("#atc").change(function(){
	var atc = $('#atc').val();
	 $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>accounting/accounts_payable_voucher/get_ATC_description/"+atc,
	     success:function(data){
	     	var tax = $.parseJSON(data);
	        $('#atc_code').val(tax.tax_code);
	        $('#atc_desc').val(tax.description);
	        $('#tax_rate').val(tax.tax_rate);
	     }
	  });
});

$('#sales_invoice_check').change(function(){
   $(".sales_invoice_file").prop("disabled", !$(this).is(':checked'));
    $(".sales_invoice_file").val("");
});
$('#purchase_order_check').change(function(){
   $(".purchase_order_file").prop("disabled", !$(this).is(':checked'));
    $(".purchase_order_file").val("");
});
$('#receiving_report_check').change(function(){
   $(".receiving_report_file").prop("disabled", !$(this).is(':checked'));
    $(".receiving_report_file").val("");
});
$('#others_check').change(function(){
   $(".others_file").prop("disabled", !$(this).is(':checked'));
    $(".others_file").val("");
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

function addATC(){

	var msg="";

	var taxable_amount = $('#taxable_amount').val();
	var wtax_amount_orig = $('#wtax_amount').val();
	var atc_code = $('#atc_code').val();
	var atc_desc = $('#atc_desc').val();
	var tax_rate = $('#tax_rate').val();

	if(atc==""){
		msg+="ATC is required.<br>";
	}
	if(atc_desc==""){
		msg+="Description is required.<br>";
	}
	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}else{

		var wtax_amount_atc = taxable_amount * tax_rate;
		if(parseFloat(wtax_amount_orig).toFixed(6) == parseFloat(wtax_amount_atc).toFixed(6)){
			var row_tax;
			row_tax = "<td><input type='hidden' id='atc_codeX[]' name='atc_codeX[]' value='"+atc_code+"' readonly><input type='hidden' id='atc_descriptionX[]' name='atc_descriptionX[]' value='"+atc_desc+"' readonly><input type='hidden' id='tax_rateX[]' name='tax_rateX[]' value='"+tax_rate+"' readonly><input type='hidden' id='taxable_amountX[]' name='taxable_amountX[]' value='"+taxable_amount+"' readonly>"+atc_code+"<input type='hidden' id='wtax_amountX[]' name='wtax_amountX[]' value='"+wtax_amount_atc+"' readonly></td><td>"+atc_desc+"</td><td>"+formatPercentage(tax_rate)+"</td><td>"+formatNumber(parseFloat(wtax_amount_atc).toFixed(2))+"</td><td>"+formatNumber(parseFloat(taxable_amount).toFixed(2))+"</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a></td>";
		
			$('#row_tax'+i_tax).html(row_tax);
			$('#table_tax').append('<tr class="tbl_row_tax" id="row_tax'+(i_tax+1)+'"></tr>');
			i_tax++;

			$('#atc_code').val('');
			$('#atc_desc').val('');
			$('#tax_rate').val('');
			$('#atc').val('');

		}else{
			toastr['error']("Entry Amount for Withholding Tax - Expanded is not equal to the ATC's computed W-tax Amount. Please select the correct ATC.", "ABAS says:");
			return false;
		}

	}
}

function formatNumber (num) {
	return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

function formatPercentage (num){
	var percentage = (parseFloat(num)*100).toFixed(2) + "%";
	return percentage;
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

	var apv_date = $('#apv_date').val();
	if(apv_date==''){
		msg+="Voucher Date is required!<br/>";
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

	var flag_docs = 0;
    if($('#sales_invoice_check').prop("checked") == false){
    	 flag_docs = 1;
    }
    if($('#purchase_order_check').prop("checked") == false){
    	 flag_docs = 1;
    }
    if($('#receiving_report_check').prop("checked") == false){
    	 flag_docs = 1;
    }

    if(flag_docs==1){
    	msg+="Please make sure you have received all required documents and marked it check!<br/>";
    }

    if(flag_debit_credit==1){
    	msg+="Debit("+xdebit_amount+") and Credit("+xcredit_amount+") amount are not balance! <br/>";
    }

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Accounts Payable",
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
			        document.getElementById("apv_form").submit();
			        return true;
		    	}
		    }
		});

	}
}
</script>