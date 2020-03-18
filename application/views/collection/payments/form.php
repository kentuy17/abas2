<?php

$title = "Receive Payment";
$payment_id = "";
$control_number = "";
$company = "";
$payor = "";
$TIN = "";
$address = "";
$business_style = "";
$received_on = "";
$mode = "";
$particulars = "";

$VAT_type = "";
$sales = 0;
$amount_received = 0;
$tax_12_val = 0;
$vatable_amount_val = 0;
$tax_5_val = 0;
$tax_2_val = 0;
$tax_1_val = 0;
$total_witholding_tax =0;
$total_deduction = 0;
$discount=0;
$src_id = "";
$pwd_id = "";
$other_deductions = 0;
$net_amount = 0;


$total_cash = 0;
$total_check = 0;
$total_bank_transfer = 0;

$companyoptions	=	"";
if(!empty($companies)) {
	foreach($companies as $company) {
		if(isset($payment)){
			$companyoptions .=	"<option ".($payment['company_id']==$company->id ? "selected":"")." value='".$company->id."'>".$company->name."</option>";
		}
		else{
			if($company->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$companyoptions	.=	"<option value='".$company->id."'>".$company->name."</option>";
			}
		}
	}
}

$for_OR_flag = 0;
$on_date_checks = 0;
$for_AR_flag = 0;
$for_deposit_flag = 0;

if(isset($payment)){// for updating of payment if deposited

	$payment_id = $payment['id'];
	$title = "Deposit Payment - Control No.".$payment['control_number']." | Transaction Code No." . $payment_id;
	
	$control_number = $payment['control_number'];
	$company = $this->Abas->getCompany($payment['company_id'])->name;
	$payor = $payment['payor'];
	$TIN = $payment['TIN'];
	$address = $payment['address'];
	$business_style = $payment['business_style'];
	$received_on = new DateTime($payment['received_on']);
	$received_on = $received_on->format('Y-m-d\TH:i');
	$mode = $payment['mode_of_collection'];
	$particulars =$payment['particulars'];
	$VAT_type = $payment['vat_type'];
	

	$tax_12_val = number_format($payment['tax_12_percent'],2,".","");
	$vatable_amount_val = number_format($payment['vatable_amount'],2,".","");
	$tax_5_val = number_format($payment['tax_5_percent'],2,".","");
	$tax_2_val = number_format($payment['tax_2_percent'],2,".","");
	$tax_1_val = number_format($payment['tax_1_percent'],2,".","");
	$total_witholding_tax = number_format($payment['tax_5_percent'] + $payment['tax_2_percent'] + $payment['tax_1_percent'],2,".","");


	$percentage = 0;
		if($payment['tax_2_percent']){
			$percentage = $percentage + 0.02;
		}
		if($payment['tax_5_percent']){
			$percentage = $percentage + 0.05;
		}
		if($payment['tax_1_percent']){
			$percentage = $percentage + 0.01;
		}

	if($payment['vat_type']=='VATable'){
		//$sales = number_format($payment['net_amount']+$payment['tax_5_percent'] + $payment['tax_2_percent'] + $payment['tax_1_percent'],2,".","");	

		$sales = number_format($payment['gross_amount']/(1-(1/1.12)*$percentage),2,".","");
	}else{
		//$sales = number_format($payment['net_amount']+$payment['tax_5_percent'] + $payment['tax_2_percent'] + $payment['tax_1_percent'],2,".","");

		$sales = number_format($payment['gross_amount']/(1-(1/1.12)*$percentage),2,".",",");
	}
	
	$discount = number_format($payment['discount'],2,".","");
	$src_id = $payment['senior_citizen_id'];
	$pwd_id = $payment['person_with_disability_id'];
	$other_deductions = $payment['other_deductions'];
	$deductioniones = $tax_5_val + $tax_2_val + $tax_1_val + $discount + $other_deductions;
	$total_deduction = number_format($tax_5_val + $tax_2_val + $tax_1_val + $discount + $other_deductions,2,".","");
	$net_amount = number_format($payment['net_amount'],2,".","");
	$amount_received = number_format($payment['gross_amount'],2,".","");

	$bankaccounts_option	=	"";
	$bankaccounts	=	$this->Collection_model->getBankByCompany($payment['company_id']);
	
	if(!empty($bankaccounts)) {
		foreach($bankaccounts as $account) {
			$bankaccounts_option .=	"<option value='".$account->id."'>".$account->account_name . " - " . $account->name . " (" . $account->account_no . ")</option>";
			;
		}
	}
}

$row_cash = "<td><input type='hidden' id='sorting[]' name='sorting[]' class='form-control sorting'/>
	        	<select type='text' id='denomination[]' name='denomination[]' class='form-control denomination' required/>
	        	</select>
	        </td>
	        <td>
	        	<input type='number' id='quantity[]' name='quantity[]' placeholder='Qty.' class='form-control qty' required>
	        </td>
	        <td>
	        	<input type='number' id='amount[]' name='amount[]' placeholder='Amount' class='form-control amount' readonly>
	        </td>";
$appendable_row_cash	= trim(preg_replace('/\s+/',' ', $row_cash));


$row_check = "<td><input type='hidden' id='sorting[]' name='sorting[]' class='form-control sorting'/>
	        	<input type='text' id='bank_name[]' name='bank_name[]' placeholder='Bank Name' class='form-control' required>
	        </td>
	        <td>
	        	<input type='text' id='bank_branch[]' name='bank_branch[]' placeholder='Bank Branch' class='form-control' required>
	        </td>
	        <td>
	        	<input type='text' id='check_number[]' name='check_number[]' placeholder='Check Number' class='form-control' required>
	        </td>
	        <td>
	        	<input type='date' id='check_date[]' name='check_date[]' placeholder='Check Date' class='form-control check_date' required>
	        </td>
	        <td>
	        	<input type='number' id='amount[]' name='amount[]' placeholder='Amount' class='form-control amount_check'>
	        </td>";//max=".date('Y-m-d')."

$appendable_row_check	= trim(preg_replace('/\s+/',' ', $row_check));

$row_bank_transfer = "<td><input type='hidden' id='sorting[]' name='sorting[]' class='form-control sorting'/>
	        	<input type='text' id='bank_name[]' name='bank_name[]' placeholder='Bank Name' class='form-control' required>
	        </td>
	        <td>
	        	<input type='text' id='bank_branch[]' name='bank_branch[]' placeholder='Bank Branch' class='form-control' required>
	        </td>
	        <td>
	        	<input type='text' id='deposit_reference_number[]' name='deposit_reference_number[]' placeholder='Deposit Ref. No.' class='form-control' required>
	        </td>
	        <td>
	        	<input type='date' id='deposit_date[]' name='deposit_date[]' placeholder='Deposit Date' max=".date('Y-m-d')." class='form-control' required>
	        </td>
	        <td>	
	        	<select type='text' id='deposited_account[]' name='deposited_account[]' class='form-control deposited_account' required>
	        	</select>
	        </td>
	        <td>
	        	<input type='number' id='amount[]' name='amount[]' placeholder='Amount' class='form-control amount_bank_transfer'>
	        </td>";
$appendable_row_bank_transfer	= trim(preg_replace('/\s+/',' ', $row_bank_transfer));

?>
<!DOCTYPE html>
<html>
<head>
	<title>Payments</title>
	<style type="text/css">
		.table td,.table th { min-width: 240px;}
		.green { 
		    background: green;
		}
		.red {
		    background: red;
		}
		.orange {
		    background: orange;
		}
	</style>
</head>
<body>

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title"><?php echo $title;?></h2>
	</div>
</div>


	<?php
		if(!isset($payment)){
			// CI Form 
			$attributes = array('id'=>'payments_form','role'=>'form');
			echo form_open_multipart(HTTP_PATH.CONTROLLER.'/insert/payments',$attributes);
			echo $this->Mmm->createCSRF();
		}else{
			if($mode=="Check"){
				$attributes = array('id'=>'deposit_check_form','role'=>'form');
				echo form_open_multipart(HTTP_PATH.CONTROLLER.'/update/payments/'.$payment_id,$attributes);
				echo $this->Mmm->createCSRF();
			}
		}

	?>

		<div class="panel-body">
			
			<div class='tile-stats col-xs-12 col-md-12'>

				<input type="hidden" id="payment_id" name="payment_id" value="<?php echo $payment_id;?>">
				
				<div class='col-md-12 col-xs-12'>
					<?php 
						if(isset($payment)){
							if($payment['status']=="For Deposit"){
								echo '<br><div class="alert alert-warning alert-dismissible fade in" role="alert">
			                    	<label style="color:white;font-size:32px">' . $payment['status'] . '</label>';
			                   
			                  	echo '</div>';
							}
							elseif($payment['status']=="Unpaid" || $payment['status']=="Cancelled"){
								echo '<br><div class="alert alert-danger alert-dismissible fade in" role="alert">
			                    	<label style="color:white;font-size:32px">' . $payment['status'] . '</label>';
			                    if($payment['comments']!="" || $payment['comments']!=NULL){
									echo '<br><strong>Comments:</strong> '.$payment['comments'];
								}
			                  	echo '</div>';
							}else{
									echo '<br><div class="alert alert-success alert-dismissible fade in" role="alert">
			                    	<label style="color:white;font-size:32px">' . $payment['status'] . '</label>
			                  	 </div>';
							}
						}else{
							echo "<br><div></div>";
						}
					?>		
				</div>

				<div class='col-xs-12 col-md-4'>
					<label for='received_on'>Date and Time Received*</label>
					<?php
						if(isset($payment)){

							echo "<input type='datetime-local' name='received_on' id='received_on' placeholder='Received On' class='form-control' style='text-align:center' value=". $received_on . " readonly/>";
						}else{

							//$received_on = new DateTime();
							//$received_on = $received_on->format('Y-m-d\TH:i'); input type='datetime-local' 

							echo "<input type='datetime-local' name='received_on' id='received_on' placeholder='Received On' class='form-control' style='text-align:center' value=".$received_on." />";
						}
					?>
				</div>

				<div class='col-md-4 col-xs-12'>
					<label for='control_number'>Payment Type*</label>
					<select id='payment_type' name='payment_type' class='form-control' <?php if(isset($payment)){ echo "disabled";}?>>
						<option value=''>Select</option>
						<option value='For Billing' <?php if(isset($payment) && $payment['soa_id']!=0){ echo "selected";}?>>For Billing</option>
						<option value='For Others' <?php if(isset($payment) && $payment['soa_id']==0){ echo "selected";}?>>For Others</option>
					</select>
				</div>
				<div class='col-md-8 col-xs-12'>
					<label for='soa' id="soa_label">Statement of Account</label>
					<?php 
						if(isset($payment)){
							if($payment['soa_id']!=0){ 
								$soa_info = $this->Billing_model->getStatementOfAccount($payment['soa_id']);
								$current_SOA = "SOA No.".$soa_info['control_number']." | Ref. No.".$soa_info['reference_number']. " (Transaction Code No.".$payment['soa_id'].")";
								echo "<input type='textbox' id='soa' name='soa' class='form-control ui-autocomplete-input' placeholder='Search by SOA Transaction Code' value='".$current_SOA."' readonly></input>";
							}else{
								echo "<input type='textbox' id='soa' name='soa' class='form-control ui-autocomplete-input' placeholder='Search by SOA Transaction Code'></input>";
							}
						}else{
							echo "<input type='textbox' id='soa' name='soa' class='form-control ui-autocomplete-input' placeholder='Search by SOA Transaction Code'></input>";
						}
					?>

					<input type='hidden' id='soa_id' name='soa_id' class='form-control'></input>

				</div>

				<?php if(!isset($payment)){ ?>
				<div class='col-md-4 col-xs-12'><label for='soa_remaining_balance' id="soa_remaining_balance_label">Remaining Balance</label>
					<span class='fa fa-user form-control-feedback left' id='PHP' aria-hidden='true'><br>PHP</span>
					<input type='text' id='soa_remaining_balance' name='soa_remaining_balance' style='text-align:right' class='form-control' value='-' readonly></input>

					<input type='hidden' id='soa_grandtotal' name='soa_grandtotal' style='text-align:right' class='form-control' value='-' readonly></input>

				</div>
				<?php } ?>

				<div class='col-md-12 col-xs-12'>
					<label for='company'>Company*</label>
					<select id='company' name='company' class='form-control' <?php if(isset($payment)){ echo "disabled";}?>>
					<option value=''>Select</option>
						<?php echo $companyoptions; ?>
					</select>
				</div>


				<div class='col-xs-12 col-md-8'>
					<label for='payor'>Payor*</label>
					<input type='text' name='payor' id='payor' placeholder='Client' class='form-control' value="<?php echo $payor;?>" <?php if(isset($payment)){ echo "disabled";}?>></input>
				</div>

				<div class='col-xs-12 col-md-4'>
					<label for='TIN'>TIN</label>
					<input type='text' name='TIN' id='TIN' class='form-control' value="<?php echo $TIN;?>" <?php if(isset($payment)){ echo "disabled";}?>></input>
				</div>
				
				<div class='col-xs-12 col-md-8'>
					<label for='address'>Address</label>
					<input type='text' name='address' id='address' class='form-control' value="<?php echo $address;?>" <?php if(isset($payment)){ echo "disabled";}?>></input>
				</div>

				<div class='col-xs-12 col-md-4'>
					<label for='bussiness_style'>Business Style</label>
					<input type='text' name='business_style' id='business_style' class='form-control' value="<?php echo $business_style;?>" <?php if(isset($payment)){ echo "disabled";}?>></input>
				</div>
				

				<div class='col-xs-12 col-md-8'>
					<label>Mode of Collection*</label>
					<br>
					 	<input type="radio" class="rd1" value='Cash' <?php if($mode=="Cash"){ echo "checked";}?> <?php if(isset($payment)){ echo "disabled";}?> onclick="javascript:collection('Cash');">Cash &nbsp  &nbsp
					 	<input type="radio" class="rd2" value='Check' <?php if($mode=="Check"){ echo "checked";}?> <?php if(isset($payment)){ echo "disabled";}?> onclick="javascript:collection('Check');">Check &nbsp  &nbsp
						<input type="radio" class="rd4" value='Bank Deposit/Transfer' <?php if($mode=="Bank Deposit/Transfer"){ echo "checked";}?> <?php if(isset($payment)){ echo "disabled";}?> onclick="javascript:collection('Bank Deposit/Transfer');">Bank Deposit/Transfer

						<input type="hidden" id="mode_of_collection" name="mode_of_collection" value="">
				</div>

				<div class='col-xs-12 col-md-4'>
					<label for='receipt_type' id='receipt_type_label'>Receipt Type*</label>
					<select id='receipt_type' name='receipt_type' class='form-control' <?php if(isset($payment)){ echo "disabled";}?>>
						<option value=''>Select</option>
						<option value='Official Receipt'>For Official Receipt</option>
						<option value='Acknowledgement Receipt'>For Acknowledgement Receipt</option>
					</select>
				</div>

				<div class='col-xs-12 col-md-12'>
					<label for='particular'>Particulars*</label>
					<textarea name='particulars' id='particulars' placeholder='eg. Payment for...' class='form-control' <?php if(isset($payment)){ echo "readonly";}?>><?php echo $particulars;?></textarea>
				</div>

			</div>
		</div>
	
		<div class='panel-body'>
			<div class='tile-stats col-xs-12 col-md-12'>
				<div role="tabpanel" data-example-id="togglable-tabs">

					 <ul id="tab_list" class="nav nav-tabs bar_tabs" role="tablist">
					 	<li role="presentation" id="tab0" class="active"><a href="#tab_details" id="details_tab" name="details_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Payment Details</b></a>
			            </li>
			            <li role="presentation" id="tab1" <?php if(isset($payment) && $mode=="Cash"){ echo "class=''";}else{ echo "class='hidden'"; }?>><a href="#tab_cash" id="cash_tab" name="cash_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Cash Breakdown</b></a>
			            </li>
			            <li role="presentation" id="tab2" <?php if(isset($payment) && $mode=="Check" || isset($payment) && $mode=="Post-dated Check"){ echo "class=''";}else{ echo "class='hidden'"; }?>><a href="#tab_check" role="tab" id="check_tab" name="check_tab" data-toggle="tab" aria-expanded="false"><b>Check Breakdown</b></a>
			            </li>
			            <li role="presentation" id="tab3" <?php if(isset($payment) && $mode=="Bank Deposit/Transfer"){ echo "class=''";}else{ echo "class='hidden'"; }?>><a href="#tab_bank_transfer" role="tab" id="bank_transfer_tab" name="bank_transfer_tab" data-toggle="tab" aria-expanded="false"><b>Bank Deposit/Transfer Breakdown</b></a>
			            </li>
			         </ul>

			        <div id="tab_contents" class="tab-content">
			         	<div role="tabpanel" class="tab-pane fade active in" id="tab_details" aria-labelledby="tab_details">

							<div class='col-xs-12 col-md-12'>
								<table class='table bordered'>
									<thead></thead>
									<tbody>
										<tr>
											<td>
												<!--<div class='col-sm-12 col-md-5'>
													<label for='amount' style='text-align:right;font-size:20px;'>Total Sales*</label>
												</div>
												<div class='col-sm-12 col-md-7'>
													<input type='number' id='gross_amount' name='gross_amount' class='form-control' value='<?php echo $amount_received;?>' <?php if(isset($payment)){ echo "readonly";}?> style='text-align:right;font-size:25px;'/>
													<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
												</div>-->
												<div class='col-xs-12 col-md-12'>
													
														<tr align='left'>
															<td>
																<div class='col-sm-23 col-md-5'>
																	<label for='net_amount' style='text-align:left;font-size:20px;'>Total Amount Collected*</label>
																</div>
																<div class='col-sm-12 col-md-7'>
																	<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
																	<input type='number' id='net_amount' name='net_amount' style='text-align:right;font-size:25px;'class='form-control' value='<?php echo $net_amount;?>' <?php if(isset($payment)){ echo 'disabled';}?>/>
																</div>
															</td>

														</tr>
											
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class='col-sm-12 col-md-5'>
													<label for='taxes'>Select applicable taxes and VAT Type:</label>
												</div>
												<div class='col-sm-12 col-md-7'>                   
													<input type="radio" class="rd5" value='VATable' <?php if($VAT_type=="VATable"){ echo "checked";}?> <?php if(isset($payment)){ echo "disabled";}?> onclick="javascript:vat('VATable');"> VATable Sales &nbsp  &nbsp
							 						<input type="radio" class="rd6" value='VAT Exempted' <?php if($VAT_type=="VAT Exempted"){ echo "checked";}?> <?php if(isset($payment)){ echo "disabled";}?> onclick="javascript:vat('VAT Exempted');"> VAT Exempted Sales &nbsp  &nbsp
													<input type="radio" class="rd7" value='VAT Zero Rated' <?php if($VAT_type=="VAT Zero Rated"){ echo "checked";}?> <?php if(isset($payment)){ echo "disabled";}?> onclick="javascript:vat('VAT Zero Rated');"> VAT Zero Rated Sales

													<input type="hidden" id="vat_type" name="vat_type" value="">

												</div>
											</td>
											</div>
										</tr>
										<!--<tr>
											<td>
												<div class='col-sm-12 col-md-5'>
													<label for='sales' style='text-align:right;'>Total Sales</label>
												</div>
												<div class='col-sm-12 col-md-7'>
													<input type='number' id='sales' name='sales' class='form-control' value='<?php echo $sales?>' <?php if(isset($payment)){echo 'disabled';}?> style='text-align:right;'/>
													<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
												</div>
											</td>
										</tr>-->
										<tr>
											<td>
												<div class='col-sm-5 col-md-5'>
												<label>
								                <?php
								                	if($tax_12_val>0 || $tax_12_val!=""){
								                		if($tax_12_val==0){
								                			echo '<input type="checkbox" class="flat" name="chk_12tax" id="chk_12tax" onclick="chk_12_tax()" disabled>';
								                		}else{
								                			echo '<input type="checkbox" class="flat" name="chk_12tax" id="chk_12tax" onclick="chk_12_tax()" checked disabled>';
								                		}
								                	}
								                	else{
								                		echo '<input type="checkbox" class="flat hidden" name="chk_12tax" id="chk_12tax" onclick="chk_12_tax()">';
								                	}
								                ?>
								                 12% VAT
								                </label>
								                </div>
								                <div class='col-sm-7 col-md-7'>
													<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
													<input type='number' id='txt_12tax' name='txt_12tax' class='form-control' style='text-align:right;' value='<?php echo $tax_12_val;?>' <?php if(isset($payment)){ echo 'readonly';}?>/>
												</div>
												<br><br><br>
												<div class='col-sm-5 col-md-5'>
													<label>VATable Sales</label>
												</div>
												<div class='col-sm-7 col-md-7'>
													<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
													<input type='number' id='txt_vatable_amount' name='txt_vatable_amount' class='form-control' style='text-align:right;' value='<?php echo $vatable_amount_val;?>' <?php if(isset($payment)){ echo 'readonly';}?>/>					
												</div>
											</td>
										</tr>
										
										<tr>
											<td>
												<div class='col-sm-5 col-md-5'>
									                <label>
									                  <?php
									                	if($tax_5_val>0 || $tax_5_val!=""){
									                		if($tax_5_val==0){
									                			echo '<input type="checkbox" class="flat" name="chk_5tax" id="chk_5tax" onclick="chk_5_tax()" disabled>';
									                		}else{
									                			echo '<input type="checkbox" class="flat" name="chk_5tax" id="chk_5tax" onclick="chk_5_tax()" checked disabled>';
									                		}
									                	}
									                	else{
									                		echo '<input type="checkbox" class="flat hidden" name="chk_5tax" id="chk_5tax" onclick="chk_5_tax()">';
									                	}
									                ?>  
									                5% VAT
									                </label>
								           		</div>
								           		<div class='col-sm-7 col-md-7'>
								           			<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
													<input type='number' id='txt_5tax' name='txt_5tax' class='form-control' style='text-align:right;' onchange='calcDeductions()' value='<?php echo $tax_5_val;?>' <?php if(isset($payment)){ echo 'readonly';}?>/>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class='col-sm-5 col-md-5'>
									                <label>
									                  <?php
									                	if($tax_2_val>0 || $tax_2_val!=""){
									                		if($tax_2_val==0){
									                			echo '<input type="checkbox" class="flat" name="chk_2tax" id="chk_2tax" onclick="chk_2_tax()" disabled>';
									                		}else{
									                			echo '<input type="checkbox" class="flat" name="chk_2tax" id="chk_2tax" onclick="chk_2_tax()" checked disabled>';
									                		}
									                	}
									                	else{
									                		echo '<input type="checkbox" class="flat hidden" name="chk_2tax" id="chk_2tax" onclick="chk_2_tax()">';
									                	}
									                ?> 
									                2% Witholding Tax
									                </label>
								           		</div>
								           		<div class='col-sm-7 col-md-7'>
								           			<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
													<input type='number' id='txt_2tax' name='txt_2tax' class='form-control' style='text-align:right;' onchange='calcDeductions()' value='<?php echo $tax_2_val;?>' <?php if(isset($payment)){ echo 'readonly';}?>/>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class='col-sm-5 col-md-5'>
									                <label>
									                  <?php
									                	if($tax_1_val>0 || $tax_1_val!=""){
									                		if($tax_1_val==0){
									                		   echo '<input type="checkbox" class="flat" name="chk_1tax" id="chk_1tax" onclick="chk_1_tax()" disabled>';
									                		}
									                		else{
									                			echo '<input type="checkbox" class="flat" name="chk_1tax" id="chk_1tax" onclick="chk_1_tax()" checked disabled>';
									                		}
									                	}
									                	else{
									                		echo '<input type="checkbox" class="flat hidden" name="chk_1tax" id="chk_1tax" onclick="chk_1_tax()">';
									                	}
									                ?>  
									                1% Witholding Tax
									                </label>
								           		</div>
								           		<div class='col-sm-7 col-md-7'>
								           			<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
													<input type='number' id='txt_1tax' name='txt_1tax' class='form-control' style='text-align:right;' onchange='calcDeductions()' value='<?php echo $tax_1_val;?>' <?php if(isset($payment)){ echo 'readonly';}?>/>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class='col-sm-5 col-md-5'>
													<label>
									                  Total Witholding Tax
									                </label>
												</div>
												<div class='col-sm-7 col-md-7'>
													<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
													<input type='number' min="0" id='txt_total_witholding_tax' name='txt_total_witholding_tax' class='form-control has-feedback-left' style='text-align:right;' value='<?php echo $total_witholding_tax;?>' readonly>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class='col-sm-5 col-md-5'>
													<label>
									                  Less Senior Citizen/Person With Disability Discount
									                </label>
												</div>
												<div class='col-sm-7 col-md-7'>
												<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
												
													<input type='number' min="0" id='txt_discount' name='txt_discount' class='form-control has-feedback-left' onchange='javascript:calcDeductions();' style='text-align:right;' value='<?php echo $discount;?>' <?php if(isset($payment)){echo "readonly";}?>/>

												</div>
												<div class='col-sm-12 col-md-6'>
													<label>
									                 	Senior Citizen TIN/ID
									                </label>
									                <input type='text' min="0" id='senior_citizen_id' name='senior_citizen_id' class='form-control' value='<?php echo $src_id;?>'<?php if(isset($payment)){echo "readonly";}?>/>
												</div>
												<div class='col-sm-12 col-md-6'>
													<label>
									                 	OSCA/PWD ID
									                </label>
									                <input type='text' min="0" id='person_with_disability_id' name='person_with_disability_id' class='form-control' value='<?php echo $pwd_id;?>'<?php if(isset($payment)){echo "readonly";}?>/>
												</div>
												
											</td>
										</tr>
										<tr>
											<td>
												<div class='col-sm-5 col-md-5'>
													<label>
									                  Less Other Deductions (Short-landed, Damages, Missing, etc.):
									                </label>
												</div>
												<div class='col-sm-7 col-md-7'>
													<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
								                		<input type='number' min="0" id='txt_other_deductions' name='txt_other_deductions' class='form-control has-feedback-left' onchange='javascript:calcDeductions();' style='text-align:right;' value='<?php echo $other_deductions;?>' <?php if(isset($payment)){echo "readonly";}?>/>
								                </div>
											</td>
										</tr>
										<tr>
											<td>
												<div class='col-sm-5 col-md-5'>
									                <label>
									                  Total Deductions (W/tax + Discount + Other Deductions)
									                </label>
								           		</div>
												<div class='col-sm-7 col-md-7'>
												<span class="fa fa-user form-control-feedback left" aria-hidden="true">PHP</span>
													<input type='text' id='txt_deductions' name='txt_deductions' class='form-control has-feedback-left' style='text-align:right;' value='<?php echo $total_deduction;?>' readonly/>
												</div>
											</td>
										</tr>
										
									</tbody>
								</table>
							</div>

						</div><!--end tab details-->

						<div role="tabpanel" class="tab-pane fade" id="tab_cash" aria-labelledby="tab_cash">

							<?php if(!isset($payment)){?>  
								<div style='float:left; margin-top:5px; margin-left:5px'>                    
									<a id='btn_add_row_cash' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
									<a id='btn_remove_row_cash' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
								</div>
							<?php } ?>

							<div class="clearfix"></div>
							<div class='panel-body item-row-container-cash' style="overflow-x:auto;">
								<table id="table_cash" data-toggle="table" class="table table-bordered table-striped table-hover" data-row-style="rowStyle">
									<thead>
										<tr>
											<th>#</th>
											<th>Denomination*</th>
											<th>Quantity*</th>
											<th>Amount</th>
											<?php if(isset($payment)){?>
												<th>Deposit Reference No.*</th>
												<th>Deposit Date*</th>
												<th>Deposited By*</th>
												<th>Deposited Account*</th>
												<th>AR No.</th>
												<th>OR No.</th>
												<th data-field="status">Status</th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>

										   <?php 

										  	if(isset($payment)){

										  		$breakdown =  $this->Collection_model->getCashBreakdown($payment['id']);

										  		$ctr = 1;
										  		foreach($breakdown as $item){

										  			echo "<tr>";
										  			echo "<td>".$ctr."</td>";
										  			echo "<td>" . $item->denomination . "</td>";
										  			echo "<td>" . $item->quantity. "</td>";
										  			echo "<td>" . number_format($item->amount,2,".",",") . "</td>";

										  			if(isset($item->deposit_reference_number)){
										  				echo "<td>" . $item->deposit_reference_number . "</td>";
										  				echo "<td>" . $item->deposited_on. "</td>";
										  				echo "<td>" . $item->deposited_by. "</td>";
										  				echo "<td>" . $this->Collection_model->getBankAccountbyID($item->deposited_account)->complete_account . "</td>";

										  				if(isset($item->acknowledgement_receipt_id)){
										  					echo "<td>" . $this->Collection_model->getARNumber($item->acknowledgement_receipt_id)->control_number. "</td>";
										  				}else{
										  					echo "<td>-</td>";
										  				}

										  				if(isset($item->official_receipt_id)){
										  					echo "<td>" . $this->Collection_model->getORNumber($item->official_receipt_id)->control_number. "</td>";
										  				}else{
										  					echo "<td>-</td>";
										  				}

										  			}else{
										  				echo "<td>-</td>";
										  				echo "<td>-</td>";
										  				echo "<td>-</td>";
										  				echo "<td>-</td>";
										  				
										  				if(isset($item->acknowledgement_receipt_id)){
										  					echo "<td>" . $this->Collection_model->getARNumber($item->acknowledgement_receipt_id)->control_number. "</td>";
										  				}else{
										  					echo "<td>-</td>";
										  				}

										  				if(isset($item->official_receipt_id)){
										  					echo "<td>" . $this->Collection_model->getORNumber($item->official_receipt_id)->control_number. "</td>";
										  				}else{
										  					echo "<td>-</td>";
										  				}
										  			}

										  			
										  			echo "<td>" . $item->status . "</td>";
										  			echo "</tr>";
										  			$ctr++;

										  			if($item->official_receipt_id!=0){
										  				$for_OR_flag = 1;
										  			}

										  			if($item->acknowledgement_receipt_id!=0){
										  				$for_AR_flag = 1;
										  			}
										  		}

										  	}else{
										  		 echo "<tr id='row_cash0' class='tbl_row_cash'></tr>";
										  	}

										  ?>

									</tbody>
								</table>
							</div>
							
							<?php if(!isset($payment)){ ?>
							<div class='col-sm-7 col-m-4' style='float:right; margin-top:0px; margin-left:205px'>
								<label class=''>Total Cash Amount</label>
							</div>
							<div class='col-sm-7 col-m-4 pull-right'>
								<span class='fa fa-user form-control-feedback left'  aria-hidden='true'>PHP</span>
								<input type='text' id='total_cash' name='total_cash' class='form-control' style='text-align:right;font-size:25px;' value='<?php number_format($total_cash,2,".",","); ?>' readonly/>
							</div>
							<?php }elseif(isset($payment) && $payment['status']=='For Deposit'){ ?>
								
								<?php
									// CI Form 
									$attributes = array('id'=>'deposit_cash_form','role'=>'form');
									echo form_open_multipart(HTTP_PATH.CONTROLLER.'/update/payments/'.$payment_id,$attributes);
									echo $this->Mmm->createCSRF();
								?>
			
								<div class='col-sm-12 col-md-3'>
									<label>Deposit Slip Reference No.*</label>
									<input type='text' id='deposit_reference_number' name='deposit_reference_number' class='form-control'>
								</div>

								<div class='col-sm-12 col-md-4'>
									<label>Deposit Date*</label>
									<input type='date' id='deposit_date' name='deposit_date' class='form-control' max="<?php echo date('Y-m-d'); ?>">
								</div>

								<div class='col-sm-12 col-md-5'>
									<label>Deposited By*</label>
									<input type='text' id='deposited_by' name='deposited_by' placeholder='Name of Depositor/Collector' class='form-control'>
								</div>

								<div class='col-sm-12 col-md-9'>
									<label>Deposited Account*</label>
									<select type='text' id='deposited_account' name='deposited_account' class='form-control'>
										<option value=''>Select</option>
										<?php echo $bankaccounts_option; ?>
									</select>
								</div>
								<br>
								<div class='col-sm-12 col-md-3'>
									<label style='color:white'>XXXXXXXXXXXXXX</label>
									<input type="button" class="btn btn-success btn-m exclude-pageload" onclick="javascript:depositCash();" value="Mark as Deposited"/>
								</div>

							<?php } ?>
							<br><br>

						</div>

						<div role="tabpanel" class="tab-pane fade" id="tab_check" aria-labelledby="tab_check">
							
							<?php if(!isset($payment)){?> 
								<div style='float:left; margin-top:5px; margin-left:5px'>                          
									<a id='btn_add_row_check' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
									<a id='btn_remove_row_check' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
								</div>
							<?php } ?>

							<div class="clearfix"></div>
							<div class='panel-body item-row-container-cash' style="overflow-x:auto;">
								<table id="table_check" data-toggle="table" class="table table-bordered table-striped table-hover" data-row-style="rowStyle">
									<thead>
										<tr>
											<th>#</th>
											<th>Bank Name*</th>
											<th>Bank Branch*</th>
											<th>Check No.*</th>
											<th>Check Date*</th>
											<th>Amount*</th>
											<?php if(isset($payment)){?>
												<th>AR No.</th>
												<th>OR No.</th>
												<th>Deposit Reference No.*</th>
												<th>Deposit Date*</th>
												<th>Deposited By*</th>
												<th>Deposited Account*</th>
												<th data-field="status">Status</th>	
											<?php } ?>
										</tr>
									</thead>
									<tbody>
				
										   <?php 
										  

										  	if(isset($payment)){

										  		$breakdown =  $this->Collection_model->getCheckBreakdown($payment['id']);

											  		$ctr = 1;
											  		$date_now = date('Y-m-d');
											  		
											  		foreach($breakdown as $item){
											  			echo "<tr>";
											  			echo "<td>".$ctr."</td>";
											  			echo "<td>" . $item->bank_name . "</td>";
											  			echo "<td>" . $item->bank_branch. "</td>";
											  			echo "<td>" . $item->check_number . "</td>";
											  			echo "<td>" . $item->check_date. "</td>";
											  			echo "<td>" . number_format($item->amount,2,".",",") . "</td>";

										  				if($item->acknowledgement_receipt_id==0){
										  					$ar = "-";
										  				}else{
										  					$ar = $this->Collection_model->getARNumber($item->acknowledgement_receipt_id)->control_number;
										  				}

										  				if($item->official_receipt_id==0){
										  					$or = "-";
										  				}else{
										  					$or = $this->Collection_model->getORNumber($item->official_receipt_id)->control_number;
										  				}

											  			if($item->status=="Deposited"){

											  				echo "<td>" . $ar . "</td>";
											  				echo "<td>" . $or . "</td>";
											  				echo "<td>" . $item->deposit_reference_number . "</td>";
											  				echo "<td>" . $item->deposited_on. "</td>";
											  				echo "<td>" . $item->deposited_by. "</td>";
											  				echo "<td>" . $this->Collection_model->getBankAccountbyID($item->deposited_account)->complete_account . "</td>";
											  				echo "<td>".$item->status."</td>";

											  			}elseif($item->status=="For Deposit"){

											  				echo "<td>" . $ar . "</td>";
											  				echo "<td>" . $or . "</td>";
											  				if($item->official_receipt_id!=0 || $item->acknowledgement_receipt_id!=0){
												  				echo "<td>
												  					<input type='hidden' id='check_id[]' name='check_id[]' value=".$item->id.">
												  					<input type='text' id='deposit_reference_number[]' name='deposit_reference_number[]' class='form-control'></td>";
												  				echo "<td><input type='date' id='deposit_date[]' name='deposit_date[]' max='".date('Y-m-d')."' class='form-control'></td>";
												  				echo "<td><input type='text' id='deposited_by[]' name='deposited_by[]' class='form-control'></td>";
												  				echo "<td style='width:2562px'>
												  				       <select type='text' id='deposited_account[]' name='deposited_account[]' class='form-control'>
												  					       <option value=''>Select</option>
												  					       ".$bankaccounts_option."
												  					   </select>
												  					  </td>";
												  				echo "<td>".$item->status."</td>";

												  				
													  			if($item->official_receipt_id!=0){
													  				$for_OR_flag = 1;
													  			}

													  			if($item->acknowledgement_receipt_id!=0){
													  				$for_AR_flag = 1;
													  			}

												  				if($item->status=='For Deposit'){
												  					$for_deposit_flag = 1;	
												  				}
												  				
											  				}
											  			}elseif($item->status=='Post-dated'){
											  				if($item->check_date <= $date_now){
											  					$on_date_checks = 1;
											  					$status = "On-date";
											  				}elseif($item->check_date > $date_now){
											  					
											  					$status = "Post-dated";
											  				}
											  				echo "<td>" . $ar . "</td>";
											  				echo "<td>-</td>";
											  				echo "<td>-</td>";
											  				echo "<td>-</td>";
											  				echo "<td>-</td>";
											  				echo "<td>-</td>";
											  				echo "<td>".$status."</td>";

											  				$for_AR_flag = 1;
											  			}elseif($item->status=="Cancelled"){

											  				if($item->deposit_reference_number){
											  					$dep_ref_no = $item->deposit_reference_number; 
											  					$dep_on = $item->deposited_on;
											  					$dep_by = $item->deposited_by;
											  					$dep_account = $this->Collection_model->getBankAccountbyID($item->deposited_account)->complete_account;
											  				}else{
											  					$dep_ref_no = "-";
											  					$dep_on = "-";
											  					$dep_by = "-";
											  					$dep_account = "-";
											  				}

											  				echo "<td>" . $ar . "</td>";
											  				echo "<td>" . $this->Collection_model->getORNumber($item->official_receipt_id)->control_number. "</td>";
											  				echo "<td>" . $dep_ref_no . "</td>";
											  				echo "<td>" . $dep_on . "</td>";
											  				echo "<td>" . $dep_by . "</td>";
											  				echo "<td>" . $dep_account . "</td>";
											  				echo "<td>".$item->status."</td>";
											  			}
											  			
											  			echo "</tr>";
											  			$ctr++;
											  		}	

										  	}else{
										  		 echo "<tr id='row_check0' class='tbl_row_check'></tr>";
										  	}

										  ?>

									</tbody>
								</table>
							</div>
							
							<?php if(!isset($payment)){ ?>
							<div class='col-sm-7 col-m-4' style='float:right; margin-top:0px; margin-left:205px'>
								<label class=''>Total Check Amount</label>
							</div>
							<div class='col-sm-7 col-m-4 pull-right'>
								<span class='fa fa-user form-control-feedback left'  aria-hidden='true'>PHP</span>
								<input type='text' id='total_check' name='total_check' class='form-control' style='text-align:right;font-size:25px;' value='<?php number_format($total_check,2,".",","); ?>' readonly/>
							</div>

							<?php }elseif(isset($payment) && $payment['status']=='For Deposit'){ ?>
								<?php if($for_deposit_flag==1){?>
									<br>
									<?php if($this->Abas->checkPermissions("finance|add_payments",false)){?>
									<div class='col-sm-12 col-md-3 pull-right'>
										<input type="button" class="btn btn-success btn-m" onclick="javascript:depositCheck();" value="Mark as Deposited"/>
									</div>
									<?php } ?>
								<?php }?>
							<?php }?>
							<?php if($on_date_checks == 1){ ?>
								<?php if($this->Abas->checkPermissions("finance|add_payments",false)){?>
								<div class='col-sm-12 col-md-3 pull-right'>
									<input type="button" class="btn btn-dark btn-m" onclick="javascript:issueOR(<?php echo $payment['id'];?>);" value="Issue OR of on-date checks"/>

								</div>
								<div class='col-sm-12 col-md-3 pull-left'>
									<input type="button" class="btn btn-warning btn-m" onclick="javascript:issueAR(<?php echo $payment['id'];?>);" value="Issue AR of on-date checks"/>
									
								</div>
								<?php } ?>
							<?php } ?>
							<br><br>

						</div>
						<div role="tabpanel" class="tab-pane fade" id="tab_bank_transfer" aria-labelledby="tab_bank_transfer">

							<?php if(!isset($payment)){?> 
								<div style='float:left; margin-top:5px; margin-left:5px'>                          
									<a id='btn_add_row_bank_transfer' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
									<a id='btn_remove_row_bank_transfer' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
								</div>
							<?php } ?>

							<div class="clearfix"></div>
							<div class='panel-body item-row-container-cash' style="overflow-x:auto;">
								<table id="table_bank_transfer" data-toggle="table" class="table table-bordered table-striped table-hover" data-row-style="rowStyle">
									<thead>
										<tr>
											<td>#</td>
											<td>Bank Name*</td>
											<td>Bank Branch*</td>
											<td>Deposit Reference No.*</td>
											<td>Deposit Date*</td>
											<td>Deposited Account*</td>
											<td>Amount</td>
											<?php 
												if(isset($payment)){

													$item =  $this->Collection_model->getBankTransferBreakdown($payment['id']);

													if($item[0]->official_receipt_id!=0){
														echo '<td>OR No.</td>';
													}
													if($item[0]->acknowledgement_receipt_id!=0){
														echo '<td>AR No.</td>';
													}
													echo '<td>Status</td>';
												} 
											?>
										</tr>
									</thead>
									<tbody>
										 
										  <?php 

										  	if(isset($payment)){

										  		$breakdown =  $this->Collection_model->getBankTransferBreakdown($payment['id']);

										  		$ctr = 1;
										  		foreach($breakdown as $item){
										  			echo "<tr>";
										  			echo "<td>".$ctr."</td>";
										  			echo "<td>" . $item->bank_name . "</td>";
										  			echo "<td>" . $item->bank_branch . "</td>";
										  			echo "<td>" . $item->deposit_reference_number . "</td>";
										  			echo "<td>" . $item->deposited_on. "</td>";
										  			echo "<td>" . $this->Collection_model->getBankAccountbyID($item->deposited_account)->complete_account . "</td>";
										  			echo "<td>" . number_format($item->amount,2,".",",") . "</td>";
										  			if($item->official_receipt_id!=0){
										  				echo "<td>" . $this->Collection_model->getORNumber($item->official_receipt_id)->control_number. "</td>";
										  				$for_OR_flag = 1;
										  			}
										  			if($item->acknowledgement_receipt_id!=0){
										  				echo "<td>" . $this->Collection_model->getARNumber($item->acknowledgement_receipt_id)->control_number. "</td>";
										  				$for_AR_flag = 1;
										  			}
										  			echo "<td>" . $item->status . "</td>";
										  			echo "</tr>";
										  			$ctr++;

										  		}

										  	}else{
										  		 echo "<tr id='row_bank_transfer0' class='tbl_row_bank_transfer'></tr>";
										  	}

										  ?>

									</tbody>
								</table>
							</div>
							
							<?php if(!isset($payment)){ ?>
							<div class='col-sm-7 col-m-4' style='float:right; margin-top:0px; margin-left:205px'>
								<label class=''>Total Bank Deposit/Transfer Amount</label>
							</div>
							<div class='col-sm-7 col-m-4 pull-right'>
								<span class='fa fa-user form-control-feedback left'  aria-hidden='true'>PHP</span>
								<input type='text' id='total_bank_transfer' name='total_bank_transfer' class='form-control' style='text-align:right;font-size:25px;' value='<?php number_format($total_bank_transfer,2,".",","); ?>' readonly/>
							</div>
							<?php } ?>
							<br><br>
								
						</div>

					 	
				    </div>
				</div>
			</div>

			
					
					<div class='col-sm-12 col-md-12'>
			
						<hr>
						<span class="pull-right">
					<?php if(!isset($payment)){?>
							<input type="button" class="btn btn-success btn-m" onclick="javascript:checkForm();" value="Accept"/>
							<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal" />
					<?php }?>
					<?php if(isset($payment) && $this->Abas->checkPermissions("finance|add_payments",false)){?>
						
						<?php if($payment['status']=="For Deposit"){?>

							<?php if($mode=="Check" && $for_AR_flag==1 || $mode=="Cash" && $for_AR_flag==1){?>
								<a href="<?php echo HTTP_PATH.CONTROLLER.'/prints/acknowledgement_receipt/'.$payment["id"];?>" class="btn btn-info exclude-pageload" target='_blank'>Print AR</a>
							<?php }?>
							<?php if($for_OR_flag==1){?>
								<a href="<?php echo HTTP_PATH.CONTROLLER.'/prints/official_receipt/'.$payment["id"];?>" class="btn btn-info exclude-pageload" target='_blank'>Print OR</a>
							<?php }?>

							<input type="button" class="btn btn-warning btn-m exclude-pageload" onclick="javascript:cancelPayment(<?php echo $payment['id'];?>);" value="Cancel Payment"/>

						<?php }?>	

						<?php if($payment['status']=="Deposited" && $mode=="Bank Deposit/Transfer"){?>
							<?php if($for_AR_flag==1){?>
								<a href="<?php echo HTTP_PATH.CONTROLLER.'/prints/acknowledgement_receipt/'.$payment["id"];?>" class="btn btn-info exclude-pageload" target='_blank'>Print AR</a>
							<?php }?>
							<?php if($for_OR_flag==1){?>
								<a href="<?php echo HTTP_PATH.CONTROLLER.'/prints/official_receipt/'.$payment["id"];?>" class="btn btn-info exclude-pageload" target='_blank'>Print OR</a>
							<?php }?>
						<?php }?>	
					
							<input type="button" class="btn btn-danger btn-m exclude-pageload" value="Close" data-dismiss="modal" />

					<?php }else{?>

							<input type="button" class="btn btn-danger btn-m exclude-pageload" value="Close" data-dismiss="modal" />
					<?php } ?>	

						</span>
					</div>
				</div>

			    
			
		</div>
		
	</form>

</body>
</html>


<script  type="text/javascript">

$('#div_check_number').hide();
$('#soa').hide();
$('#soa_remaining_balance').hide();
$('#soa_label').hide();
$('#PHP').hide();
$('#soa_remaining_balance_label').hide();
$('#receipt_type_label').hide();
$('#receipt_type').hide();

<?php if(isset($payment) && $payment['payment_type']=='For Billing'){ ?>
		$('#soa').show();
		$('#soa_label').show();
<?php } ?>

$( "#soa" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>collection/auto_complete_statement_of_account",
	minLength: 1,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$("#soa").val( ui.item.label );
		$( "#soa_id" ).val( ui.item.id );
		return false;
	}
});


$('#payment_type').change(function(){
	if($(this).val()=="For Billing"){

		$('#soa').show();
		$('#soa_remaining_balance').show();
		$('#soa_label').show();
		$('#PHP').show();
		$('#soa_remaining_balance_label').show();

		//Ajax to fill SOA
		$.ajax({
		 type:"POST",
		 url:"<?php echo HTTP_PATH;?>collection/get_statement_of_accounts_by_status",
		 success:function(data){

		    var soa = $.parseJSON(data);    

		    	$('#soa').find('option').remove().end().append('<option value="">Select</option>').val('');

		        for(var i = 0; i < soa.length; i++){
		       		var soa_val = soa[i];
		       		var option = $('<option />');
				    option.attr('value',soa_val.id).text("SOA No."+soa_val.control_number+" | Ref. No."+soa_val.reference_number + "(Transaction Code No."+soa_val.id+")");
				    $('#soa').append(option);
		        }//here
		}
		});
	}

	if($(this).val()=="For Others"){

		$('#div_check_number').hide();
		$('#soa').hide();
		$('#soa').val("");
		$('#soa_remaining_balance').hide();
		$('#soa_label').hide();
		$('#PHP').hide();
		$('#soa_remaining_balance_label').hide();

		$('#soa').find('option').remove().end();
		$('#soa_remaining_balance').val('-');
		$('#company').val('');
	    $('#company').attr('readonly',false);
	    $('#payor').val('');
	    $('#payor').attr('readonly',false);
	    $('#TIN').val('');
	    $('#TIN').attr('readonly',false);
	    $('#address').val('');
	    $('#address').attr('readonly',false);
	    $('#business_style').val('');
	}
});



$('#soa').change(function(){

	var soa_id = $('#soa_id').val();

	//Ajax to fill payment details as per SOA
		$.ajax({
		 type:"POST",
		 url:"<?php echo HTTP_PATH;?>collection/get_statement_of_accounts_by_id/"+soa_id,
			 success:function(data){

			    var soa_details = $.parseJSON(data);    

			    $('#company').val(soa_details.company_id);
			    $('#company').attr('readonly',true);
			    $('#payor').val(soa_details.client.company);
			    $('#payor').attr('readonly',true);
			    $('#TIN').val(soa_details.client.tin_no);
			    $('#TIN').attr('readonly',true);
			    $('#address').val(soa_details.client.address);
			    $('#address').attr('readonly',true);

			}
		});

		//fill remaining balance as per SOA
		$.ajax({
			type:  "POST",
			url:"<?php echo HTTP_PATH;?>collection/get_statement_of_accounts_remaining_balance/"+soa_id,
			 success:function(data){
			 	var soa = $.parseJSON(data);  	
			 	var remaining_balance = parseFloat(soa.remaining_balance).toFixed(2);
			 	$('#soa_remaining_balance').val(remaining_balance);
			 }

		});

		//fill grantotal as per SOA
		$.ajax({
			type:  "POST",
			url:"<?php echo HTTP_PATH;?>collection/get_statement_of_accounts_grandtotal/"+soa_id,
			 success:function(data){
			 	var soa = $.parseJSON(data);  	
			 	var grandtotal = parseFloat(soa).toFixed(2);
			 	$('#soa_grandtotal').val(grandtotal);
			 }

		});

});


$('#gross_amount').change(function(){ 
	 
	var gross = $('#gross_amount').val();
	gross = parseFloat(gross).toFixed(2);
	$('#net_amount').val(formatNumber(gross)); 
	//$(this).attr('readonly','readonly');

	$('#sales').val(0.00);
	$('#chk_12tax').attr('checked', false);
	$('#chk_5tax').attr('checked', false);
	$('#chk_2tax').attr('checked', false);
	$('#chk_1tax').attr('checked', false);
	$('#txt_12tax').val(0.00);
	$('#txt_5tax').val(0.00);
	$('#txt_2tax').val(0.00);
	$('#txt_1tax').val(0.00);
	$('#txt_total_witholding_tax').val(0.00);
	$('#senior_citizen_id').val("");
	$('#person_with_disability_id').val("");
	$('#txt_discount').val(0.00);
	$('#txt_deductions').val(0.00);
	$('#txt_vatable_amount').val(0.00);

});

////Mode of collection Tabs Breakdown ///////////////////////////////////////////////////////////////
var i_cash=0;
$("#btn_add_row_cash").click(function(){

	var $row_cash;
	$row_cash = "<?php echo $appendable_row_cash;?>";

	$('#row_cash'+i_cash).html("<td class='text-center'>"+ (i_cash+1) +"</td>" + $row_cash);
	$('#table_cash').append('<tr class="tbl_row_cash" id="row_cash'+(i_cash+1)+'"></tr>');
	i_cash++; 

	var denomination = new Array('Select','1000.00','500.00','200.00','100.00','50.00','20.00','10.00','5.00','1.00','0.50','0.25','0.10','0.05','0.01');

	//$('.denomination').find('option').remove().end();
	for(var i = 0; i < 15; i++){
   		var option = $('<option />');
	    option.attr('value',denomination[i]).text(denomination[i]);
	    $('.denomination').append(option);
    }

	var ctr = 0;
	$(".tbl_row_cash").each(function() {
		ctr = ctr + 1;
		$('.sorting', this).val(ctr);
	});

});
$("#btn_remove_row_cash").click(function(){

	 if(i_cash>1){
	 	$("#row_cash"+(i_cash-1)).html('');
	 	i_cash--;
	 }

	 calcInputs();
	
});

/////////////////////////////////////////////////////////////////////////////////////////////

var i_check=0;
$("#btn_add_row_check").click(function(){

	var $row_check;
	$row_check = "<?php echo $appendable_row_check;?>";

	$('#row_check'+i_check).html("<td class='text-center'>"+ (i_check+1) +"</td>" + $row_check);
	$('#table_check').append('<tr class="tbl_row_check" id="row_check'+(i_check+1)+'"></tr>');
	i_check++; 

	var ctr = 0;
	$(".tbl_row_check").each(function() {
		ctr = ctr + 1;
		$('.sorting', this).val(ctr);
	});

});
$("#btn_remove_row_check").click(function(){

	 if(i_check>1){
	 	$("#row_check"+(i_check-1)).html('');
	 	i_check--;
	 }

	 calcInputs2();
	
});

////////////////////////////////////////////////////////////////////////////////////////////////

var i_bank_transfer=0;
$("#btn_add_row_bank_transfer").click(function(){

	var $row_bank_transfer;
	$row_bank_transfer = "<?php echo $appendable_row_bank_transfer;?>";

	$('#row_bank_transfer'+i_bank_transfer).html("<td class='text-center'>"+ (i_bank_transfer+1) +"</td>" + $row_bank_transfer);
	$('#table_bank_transfer').append('<tr class="tbl_row_bank_transfer" id="row_bank_transfer'+(i_bank_transfer+1)+'"></tr>');
	i_bank_transfer++; 

	var ctr = 0;
	$(".tbl_row_bank_transfer").each(function() {
		ctr = ctr + 1;
		$('.sorting', this).val(ctr);
	});


     $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH.CONTROLLER;?>/get_banks_by_company/"+$('#company').val(),
		     success:function(data){

		        var bank_accounts = $.parseJSON(data);    

		        	//$('.deposited_account').append('<option value="">Select</option>').val('');

			        for(var xx = 0; xx < bank_accounts.length; xx++){
			       		var bank = bank_accounts[xx];
			       		var option = $('<option />');
					    option.attr('value',bank.id).text(bank.account_name + "-" + bank.name + "(" + bank.account_no + ")");
					    $('.deposited_account').append(option);
			        }

		     }

		  });

});
$("#btn_remove_row_bank_transfer").click(function(){

	 if(i_bank_transfer>1){
	 	$("#row_bank_transfer"+(i_bank_transfer-1)).html('');
	 	i_bank_transfer--;
	 }

	 calcInputs3();
	
});

////////////////////////////////////////////////////////////////////////////////////////////////

 $(document).on('keyup', "#table_cash input", calcInputs);

  function calcInputs() {	 	
    $("tr").each(function() {
    	  var $denomination  = $('.denomination', this).val();
	      var $qty  = $('.qty', this).val();
	
	      var $amount = parseFloat((($denomination*1)*($qty*1))).toFixed(2);
	      $('.amount', this).val($amount);

	      var total_amount = 0;
  		  var inps = document.getElementsByName('amount[]');
		  for (var i = 0; i < inps.length; i++) {
			var inp=inps[i];
		     total_amount = parseFloat((total_amount*1) + (inp.value*1)).toFixed(2);
		  }

		  //var res = new Intl.NumberFormat().format(total_amount);
		  res = total_amount;
		  document.getElementById("total_cash").value = formatNumber(res);
		
    });

  }

///////////////////////////////////////////////////////////////////////////////////////////////

  $(document).on('keyup', "#table_check input", calcInputs2);

  function calcInputs2() {	 	
    $("tr").each(function() {
    	  
	      var total_amount = 0;
  		  var inps = document.getElementsByName('amount[]');
		  for (var i = 0; i < inps.length; i++) {
			var inp=inps[i];
		     total_amount = parseFloat((total_amount*1) + (inp.value*1)).toFixed(2);
		  }

		  //var res = new Intl.NumberFormat().format(total_amount);
		  res = total_amount;
		  document.getElementById("total_check").value = formatNumber(res);
		
    });

  }

 ///////////////////////////////////////////////////////////////////////////////////////////////

  $(document).on('keyup', "#table_bank_transfer input", calcInputs3);

  function calcInputs3() {	 	
    $("tr").each(function() {
    	  
	      var total_amount = 0;
  		  var inps = document.getElementsByName('amount[]');
		  for (var i = 0; i < inps.length; i++) {
			var inp=inps[i];
		     total_amount = parseFloat((total_amount*1) + (inp.value*1)).toFixed(2);
		  }

		  //var res = new Intl.NumberFormat().format(total_amount);
		  res = total_amount;
		  document.getElementById("total_bank_transfer").value = formatNumber(res);
		
    });

  }

//////////////////////////////////////////////////////////////////////////////////////////////

function collection(mode){

	 if(mode=="Cash"){
	 	$('#tab1').removeClass();

	 	$('#tab2').addClass('hidden');
	 	$('#tab3').addClass('hidden');

	 	$('.rd1').attr('disabled','disabled');
	 	$('.rd2').attr('disabled','disabled');
	 	$('.rd3').attr('disabled','disabled');
	 	$('.rd4').attr('disabled','disabled');

	 	$('#mode_of_collection').val(mode);

	 	$('#receipt_type_label').show();
		$('#receipt_type').show();

	 }
	 if(mode=="Check"){
	 	$('#tab2').removeClass();

	 	$('#tab1').addClass('hidden');
	 	$('#tab3').addClass('hidden');

	 	$('.rd1').attr('disabled','disabled');
	 	$('.rd2').attr('disabled','disabled');
	 	$('.rd3').attr('disabled','disabled');
	 	$('.rd4').attr('disabled','disabled');

	 	$('#mode_of_collection').val(mode);

	 	if(mode=="Post-dated Check"){
	 		$('#or_no').val("");
	 		$('#or_no_div').addClass('hidden');
	 	}

	 	$('#receipt_type_label').show();
		$('#receipt_type').show();

	 }
	 if(mode=="Cash and Check"){
	 	$('#tab1').removeClass();
	 	$('#tab2').removeClass();

	 	$('#tab3').addClass('hidden');

	 	$('.rd1').attr('disabled','disabled');
	 	$('.rd2').attr('disabled','disabled');
	 	$('.rd3').attr('disabled','disabled');
	 	$('.rd4').attr('disabled','disabled');

	 	$('#mode_of_collection').val(mode);

	 	$('#receipt_type_label').show();
		$('#receipt_type').show();

	 }
	  if(mode=="Bank Deposit/Transfer"){

	  	$('#tab3').removeClass();

	  	$('#tab1').addClass('hidden');
	 	$('#tab2').addClass('hidden');

	 	$('.rd1').attr('disabled','disabled');
	 	$('.rd2').attr('disabled','disabled');
	 	$('.rd3').attr('disabled','disabled');
	 	$('.rd4').attr('disabled','disabled');

	 	$('#mode_of_collection').val(mode);

	 	$('#receipt_type_label').show();
		$('#receipt_type').show();

	 }

}



function formatNumber (num) {
return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

$(function () {

  $(".check_date").datepicker({dateFormat: "yy-mm-dd"});

  var $table1 = $('#table_cash');
  $table1.bootstrapTable();

  var $table2 = $('#table_check');
  $table2.bootstrapTable();

  var $table3 = $('#table_bank_transfer');
  $table3.bootstrapTable();

});

function rowStyle(row, index) {
	var classes = ['active', 'success', 'info', 'warning', 'danger'];

	if(row.status=='Deposited'){
	  return {classes : "active" };
	}
	if(row.status=='Post-dated'){
	  return {classes : "warning" };
	}
	if(row.status=='On-date'){
	  return {classes : "danger" };
	}
	if(row.status=='For Deposit'){
	  return {classes : "success" };
	}
	
}

/*$("#chk_12tax").change(function() {
    $("#txt_12tax").prop("readonly", $(this).is(":checked"));
    $("#txt_vatable_amount").prop("readonly", $(this).is(":checked"));
});

$("#chk_5tax").change(function() {
    $("#txt_5tax").prop("readonly", $(this).is(":checked"));
});

$("#chk_2tax").change(function() {
    $("#txt_2tax").prop("readonly", $(this).is(":checked"));
});

$("#chk_1tax").change(function() {
    $("#txt_1tax").prop("readonly", $(this).is(":checked"));
});*/


function vat(type){

	if(type=="VAT Exempted" || type=="VAT Zero Rated"){
		$('#chk_12tax').attr('disabled','disabled');
		$('#txt_12tax').attr('disabled','disabled');
		$('#txt_vatable_amount').attr('disabled','disabled');
	}

	$('#chk_12tax').attr('checked', false);
	$('#chk_5tax').attr('checked', false);
	$('#chk_2tax').attr('checked', false);
	$('#chk_1tax').attr('checked', false);
	$('#txt_12tax').val(0.00);
	$('#txt_5tax').val(0.00);
	$('#txt_2tax').val(0.00);
	$('#txt_1tax').val(0.00);
	$('#txt_total_witholding_tax').val(0.00);
	$('#senior_citizen_id').val("");
	$('#person_with_disability_id').val("");
	$('#txt_discount').val(0.00);
	$('#txt_deductions').val(0.00);
	$('#txt_vatable_amount').val(0.00);

	//$('#net_amount').val(formatNumber(parseFloat($('#gross_amount').val()).toFixed(2)));

 	$('.rd5').attr('disabled','disabled');
 	$('.rd6').attr('disabled','disabled');
 	$('.rd7').attr('disabled','disabled');

 	$('#vat_type').val(type);
}

function vatSales(witholding_tax){
	var gross_amount = $('#gross_amount').val();
	var sales_vat  = parseFloat(Number(gross_amount)/(1-(1/1.12)*witholding_tax)).toFixed(2);
	return sales_vat;
}

function vatExcempt(witholding_tax){
	var gross_amount = $('#gross_amount').val();
	var payment_type = $('#payment_type').val();
	if(payment_type=="For Others"){
		var vat_excempt  = parseFloat(Number(gross_amount)+(Number(gross_amount)*witholding_tax)).toFixed(2);
	}else{

		var soa_amount = $('#soa_grandtotal').val();
		var vat_excempt = parseFloat((Number(soa_amount)*witholding_tax)+Number(gross_amount)).toFixed(2);
	}
	return vat_excempt;
}

function chk_12_tax(){

	$('#txt_12tax').val(0.00);

   // document.getElementById('net_amount').value  = document.getElementById('sales').value;

    if(document.getElementById('chk_12tax').checked){
    	if(document.getElementById('sales').value==0){
    		var gross = Number(document.getElementById('gross_amount').value);
    		var vatable_amount = parseFloat((gross/ 1.12) * 0.12).toFixed(2);
    	}else{
    		var vatable_amount = parseFloat((document.getElementById('sales').value / 1.12) * 0.12).toFixed(2);
    	}
    
         document.getElementById('txt_12tax').value = vatable_amount;
         document.getElementById('txt_total_witholding_tax').value = 0;
    }else{
    	document.getElementById('gross_amount').value = 0;
    	document.getElementById('txt_vatable_amount').value = 0;
        document.getElementById('txt_12tax').value = 0;
        document.getElementById('txt_5tax').value = 0;
        document.getElementById('chk_5tax').checked = false;
        document.getElementById('txt_2tax').value = 0;
        document.getElementById('chk_2tax').checked = false;
        document.getElementById('txt_1tax').value = 0;
        document.getElementById('chk_1tax').checked = false;
        document.getElementById('txt_total_witholding_tax').value = 0;
        document.getElementById('txt_deductions').value = 0;
        document.getElementById('txt_discount').value = 0;
    }

    if(document.getElementById('sales').value==0){
    	var less_12tax = parseFloat(document.getElementById('gross_amount').value).toFixed(2) - vatable_amount;
    	document.getElementById('sales').value = parseFloat(document.getElementById('gross_amount').value).toFixed(2);
    }else{
    	var less_12tax = parseFloat(document.getElementById('sales').value).toFixed(2) - vatable_amount;
    }
    
    document.getElementById('txt_vatable_amount').value = parseFloat(less_12tax).toFixed(2); 

    var amount_received = parseFloat(document.getElementById('sales').value).toFixed(2);
    document.getElementById('net_amount').value = formatNumber(amount_received);

    if(document.getElementById('chk_12tax').checked==false){
    	document.getElementById('sales').value = 0;
    }

}
function chk_5_tax(){

	var percentage = 0;	
	if(document.getElementById('chk_5tax').checked){
		percentage = percentage + 0.05;
	}
	if(document.getElementById('chk_2tax').checked){
		percentage = percentage + 0.02;
	}
	if(document.getElementById('chk_1tax').checked){
		percentage = percentage + 0.01;
	}

	if($('#vat_type').val()=="VATable"){

		$('#sales').val(vatSales(percentage));
		chk_12_tax();

	}else{

		$('#sales').val(vatExcempt(percentage));

	}
	
    if(document.getElementById('chk_5tax').checked){
    	if(document.getElementById('chk_12tax').checked){
    		document.getElementById('txt_5tax').value = parseFloat(document.getElementById('txt_vatable_amount').value * 0.05).toFixed(2);
    	}else{
    		if($('#vat_type').val()=="VATable"){
    			document.getElementById('txt_5tax').value = parseFloat(document.getElementById('sales').value * 0.05).toFixed(2);

    		}else{

    			var payment_type = $('#payment_type').val();
				if(payment_type=="For Others"){
    				document.getElementById('txt_5tax').value = parseFloat(document.getElementById('gross_amount').value * 0.05).toFixed(2);
    			}else{
    				document.getElementById('txt_5tax').value = parseFloat(document.getElementById('sales').value * 0.05).toFixed(2);
    			}
    		}
    	}
    }else{
        document.getElementById('txt_5tax').value = 0;
    }

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_discount').value);  
    document.getElementById('txt_deductions').value = formatNumber(parseFloat(result).toFixed(2));  

    var witholding_tax = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value);
    document.getElementById('txt_total_witholding_tax').value = parseFloat(witholding_tax).toFixed(2);

    var amount_received=0;
    amount_received = document.getElementById('sales').value;

    var deductions = document.getElementById('txt_deductions').value;
    var net_amount = parseFloat(amount_received.replace(/,/g,'')).toFixed(2) - parseFloat(deductions.replace(/,/g,'')).toFixed(2);  
    
    document.getElementById('net_amount').value = formatNumber(parseFloat(net_amount).toFixed(2));

}
function chk_2_tax(){


	var percentage = 0;
	if(document.getElementById('chk_5tax').checked){
		percentage = percentage + 0.05;
	}
	if(document.getElementById('chk_2tax').checked){
		percentage = percentage + 0.02;
	}
	if(document.getElementById('chk_1tax').checked){
		percentage = percentage + 0.01;
	}

	if($('#vat_type').val()=="VATable"){

		$('#sales').val(vatSales(percentage));
		chk_12_tax();

	}else{

		$('#sales').val(vatExcempt(percentage));

	}

    if(document.getElementById('chk_2tax').checked){
    	if(document.getElementById('chk_12tax').checked){
    		   document.getElementById('txt_2tax').value = parseFloat(document.getElementById('txt_vatable_amount').value * 0.02).toFixed(2);
    	}else{
    		if($('#vat_type').val()=="VATable"){
    		   document.getElementById('txt_2tax').value = parseFloat(document.getElementById('sales').value * 0.02).toFixed(2);
    		  }else{
    		  	var payment_type = $('#payment_type').val();
				if(payment_type=="For Others"){
    		  		document.getElementById('txt_2tax').value = parseFloat(document.getElementById('gross_amount').value * 0.02).toFixed(2);
    		  	}else{
    		  		document.getElementById('txt_2tax').value = parseFloat(document.getElementById('sales').value * 0.02).toFixed(2);
    		  	}
    		  }
    	}
    }else{
        document.getElementById('txt_2tax').value = 0;  
    }

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_discount').value);  
    document.getElementById('txt_deductions').value = formatNumber(parseFloat(result).toFixed(2));   

    var witholding_tax = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value);
    document.getElementById('txt_total_witholding_tax').value = parseFloat(witholding_tax).toFixed(2);

    var amount_received=0;
    amount_received = document.getElementById('sales').value;

    var deductions = document.getElementById('txt_deductions').value;
    var net_amount = parseFloat(amount_received.replace(/,/g,'')).toFixed(2) - parseFloat(deductions.replace(/,/g,'')).toFixed(2);   
   
    document.getElementById('net_amount').value = formatNumber(parseFloat(net_amount).toFixed(2));
}
function chk_1_tax(){

	var percentage = 0;
	if(document.getElementById('chk_5tax').checked){
		percentage = percentage + 0.05;
	}
	if(document.getElementById('chk_2tax').checked){
		percentage = percentage + 0.02;
	}
	if(document.getElementById('chk_1tax').checked){
		percentage = percentage + 0.01;
	}
	
	if($('#vat_type').val()=="VATable"){

		$('#sales').val(vatSales(percentage));
		chk_12_tax();

	}else{
		$('#sales').val(vatExcempt(percentage));
	}

    if(document.getElementById('chk_1tax').checked){
    	if(document.getElementById('chk_12tax').checked){
    		   document.getElementById('txt_1tax').value = parseFloat(document.getElementById('txt_vatable_amount').value * 0.01).toFixed(2);
    	}else{
    		if($('#vat_type').val()=="VATable"){
    	       document.getElementById('txt_1tax').value = parseFloat(document.getElementById('sales').value * 0.01).toFixed(2);
    	     }else{
    	     	var payment_type = $('#payment_type').val();
				if(payment_type=="For Others"){
    	     		document.getElementById('txt_1tax').value = parseFloat(document.getElementById('gross_amount').value * 0.01).toFixed(2);
    	     	}else{
    	     		document.getElementById('txt_1tax').value = parseFloat(document.getElementById('sales').value * 0.01).toFixed(2);
    	     	}
    	     }
    	}
    }else{
        document.getElementById('txt_1tax').value = 0;
    }

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_discount').value);  
    document.getElementById('txt_deductions').value = formatNumber(parseFloat(result).toFixed(2));

    var witholding_tax = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value);
    document.getElementById('txt_total_witholding_tax').value = parseFloat(witholding_tax).toFixed(2);

    var amount_received=0;
    amount_received = document.getElementById('sales').value;

    var deductions = document.getElementById('txt_deductions').value;
    var net_amount = parseFloat(amount_received.replace(/,/g,'')).toFixed(2) - parseFloat(deductions.replace(/,/g,'')).toFixed(2); 

    document.getElementById('net_amount').value = formatNumber(parseFloat(net_amount).toFixed(2));
}

function calcDiscount(){


	/*if($('#vat_type').val()=="VATable"){
		var amount = $('#sales').val();
	}else{
		var amount = $('#gross_amount').val();
	}*/

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_discount').value) + parseFloat(document.getElementById('txt_other_deductions').value);  
    document.getElementById('txt_deductions').value = formatNumber(parseFloat(result).toFixed(2));  

    var amount_received = document.getElementById('gross_amount').value;
    var deductions = document.getElementById('txt_deductions').value;
    var net_amount = parseFloat(amount_received.replace(/,/g,'')).toFixed(2) - parseFloat(deductions.replace(/,/g,'')).toFixed(2);   
    //document.getElementById('net_amount').value = formatNumber(parseFloat(net_amount).toFixed(2));
}

function calcOtherDeductions(){

	/*if($('#vat_type').val()=="VATable"){
		var amount = $('#sales').val();
	}else{
		var amount = $('#gross_amount').val();
	}*/

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_discount').value) + parseFloat(document.getElementById('txt_other_deductions').value);   
    document.getElementById('txt_deductions').value = formatNumber(parseFloat(result).toFixed(2));  

    var amount_received = amount;//document.getElementById('gross_amount').value;
    var deductions = document.getElementById('txt_deductions').value;
    var net_amount = parseFloat(amount_received.replace(/,/g,'')).toFixed(2) - parseFloat(deductions.replace(/,/g,'')).toFixed(2);   
    //document.getElementById('net_amount').value = formatNumber(parseFloat(net_amount).toFixed(2));
}


function calcDeductions(){
	var wtax = parseFloat($("#txt_5tax").val()) + parseFloat($("#txt_2tax").val()) + parseFloat($("#txt_1tax").val());
	$("#txt_total_witholding_tax").val(wtax);

	var deductions = wtax + parseFloat($("#txt_discount").val()) + parseFloat($("#txt_other_deductions").val());
	$("#txt_deductions").val(deductions);


	if($('#vat_type').val()=="VATable"){
		var net_amount = parseFloat($("#gross_amount").val()).toFixed(2) - parseFloat(deductions).toFixed(2); 
	}else{
		var net_amount = parseFloat($("#gross_amount").val()).toFixed(2) - parseFloat(deductions).toFixed(2); 
	}

    
   //$("#net_amount").val(formatNumber(parseFloat(net_amount).toFixed(2)));
}


function depositCash(){
	var msg = "";

	var deposit_reference_number=document.getElementById("deposit_reference_number").value;
	if (deposit_reference_number==""){
		msg+="Deposit Reference No. is required! <br/>";
	}
	var deposit_date=document.getElementById("deposit_date").value;
	if (deposit_date==""){
		msg+="Deposit Date is required! <br/>";
	}
	var deposited_account=document.getElementById("deposited_account").value;
	if (deposited_account==""){
		msg+="Deposited Account is required! <br/>";
	}

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}else{

		bootbox.confirm({
			title: "Deposit Payment",
			size: 'small',
		    message: 'Are you sure you want to mark this payment as "Deposited"?',
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
			        document.getElementById("deposit_cash_form").submit();
			        return true;
		    	}
		    }
		});

	}
}

function depositCheck(){
	var msg="";

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}else{

		bootbox.confirm({
		title: "Deposit Payment",
		size: 'small',
	    message: 'Are you sure you want to mark this payment as "Deposited"?',
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
		        document.getElementById("deposit_check_form").submit();
		        return true;
	    	}
	    }
		});

	}

}

function issueOR(payment_id){

	bootbox.confirm({
	title: "Issue OR",
	size: 'small',
    message: 'Are you sure you want to issue OR for on-dated checks?',
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
	       
	       $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH.CONTROLLER;?>/set_official_receipt/"+$('#company').val()+"/"+payment_id,
		     success:function(data){

		     	//closes modal payment modal and refershes the payment listview
		     	toastr['success']("Successfully created OR for current on-dated checks.", "ABAS says:");

		        location.reload(true);
		        
		     }

		  });

	        return true;
    	}
    }
	});

}

function issueAR(payment_id){

	bootbox.confirm({
	title: "Issue AR",
	size: 'small',
    message: 'Are you sure you want to issue AR for on-dated checks?',
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
	       
	       $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH.CONTROLLER;?>/set_acknowledgement_receipt/"+payment_id,
		     success:function(data){

		     	//closes modal payment modal and refershes the payment listview
		     	toastr['success']("Successfully created AR for current on-dated checks.", "ABAS says:");

		        location.reload(true);
		        
		     }

		  });

	        return true;
    	}
    }
	});

}

function cancelPayment(payment_id){
	/*bootbox.confirm({
			title: "Cancel Payment",
			size: 'small',
		    message: "Are you sure you want to cancel this payment?",
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
			        window.location.href = "<?php //echo HTTP_PATH.CONTROLLER;?>/cancel/payments/" + payment_id;
		    	}
		    }
		});*/
	bootbox.prompt({
   					size: "medium",
				    title: "Are you sure you want to cancel this payment? (Please provide comments below.)",
				    inputType: 'textarea',
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
				    	if(result==null || result==""){
				    		console.log("Do nothing");
				    	}else{
				    		window.location.href = "<?php echo HTTP_PATH.CONTROLLER;?>/cancel/payments/" + payment_id;
				    		$.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH.CONTROLLER;?>/update/set_comments/"+payment_id,
							     data: {comments:result}
							  });
				    	}
				    	
				    }
				});
}

function checkForm() {
	var msg="";

	var company=document.getElementById("company").value;
	if (company==""){
		msg+="Company is required! <br/>";
	}
	var payment_type=document.getElementById("payment_type").value;
	if (payment_type==""){
		msg+="Payment Type is required! <br/>";
	}
	var amount=document.getElementById("net_amount").value;
	if (amount<=0) {
		msg+="Total Amount Collected is required! <br/>";
	}
	var received_on=document.getElementById("received_on").value;
	if (received_on==""){
		msg+="Date & Time Received is required! <br/>";
	}
	var payor=document.getElementById("payor").value;
	if (payor==""){
		msg+="Payor is required! <br/>";
	}
	var mode=document.getElementById("mode_of_collection").value;
	if (mode==""){
		msg+="Mode of collection is required! <br/>";
	}
	if(mode=="Cash" || mode=="Check" || mode=="Bank Deposit/Transfer"){
		var receipt_type = document.getElementById("receipt_type").value;
		if(receipt_type==""){
			msg+="Receipt Type is required! <br/>";
		}
		if(payment_type=="For Billing" && receipt_type=="Acknowledgement Receipt"){
			msg+="Receipt Type must be set to 'For Official Receipt' for this bill payment! <br/>";
		}
	}
	var vat=document.getElementById("vat_type").value;
	if (vat==""){
		msg+="VAT Type is required! <br/>";
	}
	var particulars=document.getElementById("particulars").value;
	if (particulars==""){
		msg+="Particulars is required! <br/>";
	}
	var discount = document.getElementById("txt_discount").value;
	if(discount!=0){
		if(document.getElementById("senior_citizen_id").value==""){
			msg+="Senior Citizen ID is required! <br/>";
		}
		if(document.getElementById("person_with_disability_id").value==""){
			msg+="PWD ID is required! <br/>";
		}
	}
	
	var payment_type = document.getElementById("payment_type").value;
	var soa_remaining_balance = document.getElementById("soa_remaining_balance").value;
	var soa = document.getElementById("soa").value;
	var amount_received = parseFloat(document.getElementById("net_amount").value).toFixed(2);
	var net_amount = parseFloat(document.getElementById("net_amount").value).toFixed(2);
	if(payment_type=="For Billing"){

		if(soa==""){
			msg+="SOA is required! <br/>";
		}

		if(soa_remaining_balance == 0.00){
			msg+="This SOA has already been paid.<br/>";
		}

		if(Number(amount_received) > Number(soa_remaining_balance)){
			msg+="Total Amount Collected should not be greater than SOA Remaining Balance! <br/>";
		}	
			
	}

	//if(Number(net_amount) > Number(amount_received)){
	//	msg+="Actual Amount Received should not be greater than Total Amount for Collection! <br/>";
	//}

	var mode=$("#mode_of_collection").val();
	var net_amount=formatNumber(parseFloat($("#net_amount").val()).toFixed(2));

	console.log(net_amount);

	if(mode=="Cash"){
		var total_cash=$("#total_cash").val();
		if (total_cash=="" || total_cash==0){
			msg+="Total Cash Amount is required! <br/>";
		}
		if(total_cash!=net_amount){
			msg+="Total Cash Amount is not equal with Total Amount Collected! <br/>";
		}
	}
	if(mode=="Check"){
		var total_check=$("#total_check").val();
		if (total_check=="" || total_check==0){
			msg+="Total Check Amount is required! <br/>";
		}
		if(total_check!=net_amount){
			msg+="Total Check Amount is not equal with Total Amount Collected! <br/>";
		}
	}
	if(mode=="Bank Deposit/Transfer"){
		var total_bank_transfer=$("#total_bank_transfer").val();
		if (total_bank_transfer=="" || total_bank_transfer==0){
			msg+="Total Bank Deposit/Transfer Amount is required! <br/>";
		}
		if(total_bank_transfer!=net_amount){
			msg+="Total Bank Deposit/Transfer Amount is not equal with Total Amount Collected! <br/>";
		}
	}

	$('#table_cash input').each(function() {
        if(!$(this).val()){
           msg+="Please complete all required fields in Cash Breakdown!<br/>";
           return false;
        }
    });

    $('#table_check input').each(function() {
        if(!$(this).val()){
           msg+="Please complete all required fields in Check Breakdown!<br/>";
           return false;
        }
    });

    $('#table_bank_transfer input').each(function() {
        if(!$(this).val()){
           msg+="Please complete all required fields in Bank Deposit/Transfer Breakdown!<br/>";
           return false;
        }
    });
	
	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Receive Payment",
			size: 'small',
		    message: "Are you sure you want to accept this payment?",
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

			        document.getElementById("payments_form").submit();
			        return true;
		    	}
		    }
		});

	}

}
</script>