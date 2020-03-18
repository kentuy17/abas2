<?php
$action			=	HTTP_PATH."billing/payments/insert";
$title			=	"Receieve Payment";

$bankoptions	=	"";

$bankaccounts = $this->Billing_model->getBankByCompany($company->id);
if(!empty($bankaccounts)) {
	foreach($bankaccounts as $bankaccount) {
		$bankoptions	.=	"<option value='".$bankaccount->id."'>".$bankaccount->account_name. " - " .$bankaccount->name." (".$bankaccount->account_no.")</option>";
	}
}

$companyoptions	=	"";
$companies = $this->Abas->getCompanies();
if(!empty($companies)) {
	foreach($companies as $company) {
		$companyoptions	.=	"<option value='".$company->id."'>".$company->name."</option>";
	}
}

if(isset($soa)) {// if status is Waiting
	
	$title		=	"Receive Payment for SOA Ref.#: ".$soa['reference_number'];
	$action		=	HTTP_PATH."billing/pay_soa/".$soa['id'];
	$gross_amount = number_format($_GET['amt'],2,".","");
	
	$tax_12_val = 0;
	$tax_12_val_less = 0;
	$tax_5_val = 0;
	$tax_2_val = 0;
	$tax_1_val = 0;
	$total_deduction = 0;
	$other_deductions_val=0;
	$other_deductions_description_val = "";
	$deduction_description="";
	$net_amount = $gross_amount;

	$client_name = $client['company'];

	$payment_id = null;
}
if(isset($payment)){ // if status is Received or Paid
	if($soa['status']=="For Deposit"){
		$title		=	"Deposit Payment for SOA Ref.#: ".$soa['reference_number'];
	}
	elseif($soa['status']=="Paid"){
		$title		=	"Payment Details for SOA Ref.#: ".$soa['reference_number'];
	}
	
	$action		=	HTTP_PATH."billing/pay_soa/".$soa['id'];
	$gross_amount = number_format($_GET['amt'],2,".","");

	$tax_12_val = number_format($payment['tax_12_percent'],2,".","");
	$tax_5_val = number_format($payment['tax_5_percent'],2,".","");
	$tax_2_val = number_format($payment['tax_2_percent'],2,".","");
	$tax_1_val = number_format($payment['tax_1_percent'],2,".","");
	$tax_12_val_less = number_format($payment['tax_12_percent_less'],2,".","");
	$other_deductions_val = number_format($payment['other_deductions'],2,".","");
	$other_deductions_description_val = $payment['other_deductions_description'];
	$control_number = $payment['control_number'];

	$client_name = $client['company'];

	$total_deduction = number_format($tax_5_val + $tax_2_val + $tax_1_val + $other_deductions_val,2,".","");
	$net_amount = number_format($gross_amount - $total_deduction,2,".","");

	$payment_id = $payment['id'];

	//$this->Mmm->debug($payment);
}

?>


<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title"><?php echo $title;?></h2>
	</div>
</div>
	<form action='<?php echo $action; ?>' method="POST" id="bill_form">
		<div class="panel-body">
			<?php if(!isset($soa)): ?>
				<div class='col-sm-12 col-md-12'>
					<label for='particular'>Particular</label>
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
				
				<div class='x_panel col-sm-12 col-md-12'>
					
	
					<input type="hidden" id="payor" name="payor" value="<?php echo $soa['client_id'];?>">
					<input type="hidden" id="payment_id" name="payment_id" value="<?php echo $payment_id;?>">

					<div>
						<label for='amount'>Statement of Account - Amount</label>
					</div>

					<div class='col-sm-12 col-md-12'>
						<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
						<input type='number' id='gross_amount' name='gross_amount' class='form-control' value='<?php echo $gross_amount;?>' style='text-align:right;font-size:25px;' readonly/><br>
					</div>
					<div class='col-sm-12 col-md-12'>
						<label for='taxes'>Select applicable taxes:</label>
					</div>
					<div class="x_panel">
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
			                		echo '<input type="checkbox" class="flat" name="chk_12tax" id="chk_12tax" onclick="chk_12_tax()">';
			                	}
			                ?>
			                 12% VAT
			                </label>
		           		</div>

						<div class='col-sm-7 col-md-7'>
							<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
							<input type='number' id='txt_12tax' name='txt_12tax' class='form-control' style='text-align:right;' value='<?php echo $tax_12_val;?>' readonly/>
						</div>
					
						<hr>
						<div class='col-sm-12 col-md-12'>
							
							<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
							<input type='number' id='txt_12tax_less' name='txt_12tax_less' class='form-control' style='text-align:right;' value='<?php echo $tax_12_val_less;?>' readonly/>
							
						</div>
					</div>
	           		
	           		<div class="x_panel">	
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
			                		echo '<input type="checkbox" class="flat" name="chk_5tax" id="chk_5tax" onclick="chk_5_tax()">';
			                	}
			                ?>  
			                5% VAT
			                </label>
		           		</div>
		           		<div class='col-sm-7 col-md-7'>
		           			<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
							<input type='number' id='txt_5tax' name='txt_5tax' class='form-control' style='text-align:right;' value='<?php echo $tax_5_val;?>' readonly/>
						</div>
					</div>

					<div class="x_panel">
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
			                		echo '<input type="checkbox" class="flat" name="chk_2tax" id="chk_2tax" onclick="chk_2_tax()">';
			                	}
			                ?> 
			                2% witholding-tax
			                </label>
		           		</div>
		           		<div class='col-sm-7 col-md-7'>
		           			<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
							<input type='number' id='txt_2tax' name='txt_2tax' class='form-control' style='text-align:right;' value='<?php echo $tax_2_val;?>' readonly/>
						</div>
					</div>
					<div class="x_panel">
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
			                		echo '<input type="checkbox" class="flat" name="chk_1tax" id="chk_1tax" onclick="chk_1_tax()">';
			                	}
			                ?>  
			                1% witholding-tax
			                </label>
		           		</div>
		           		<div class='col-sm-7 col-md-7'>
		           			<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
							<input type='number' id='txt_1tax' name='txt_1tax' class='form-control' style='text-align:right;' value='<?php echo $tax_1_val;?>' readonly/>
						</div>
					</div>
					<div class="x_panel">
						<div class='col-sm-5 col-md-5'>
							<label>
			                  Other Deductions
			                </label>
						</div>
						<div class='col-sm-7 col-md-7'>
						<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
						
							<input type='number' min="0" id='txt_other_deductions' name='txt_other_deductions' class='form-control has-feedback-left' onchange='javascript:type_other_deductions();' style='text-align:right;' value='<?php echo $other_deductions_val;?>' <?php if($soa['status']=="For Deposit" || $soa['status']=="Paid"){echo "readonly";}?>/>

						</div>
						<div class='col-sm-5 col-md-5'>
							<label>
			                  Description
			                </label>
						</div>
						<div class='col-sm-12 col-md-12'>
						<?php 
							if($soa['status']=="For Deposit"  || $soa['status']=="Paid"){
								echo '<textarea name="txt_other_deductions_description" rows="2" style="width: 315px;" readonly>' . $other_deductions_description_val .'</textarea>';
							}
							else{
								echo '<textarea name="txt_other_deductions_description" rows="2" style="width: 315px;"></textarea>';
							}
						?>
							
						</div>
					</div>
					<div class="x_panel">
						<div class='col-sm-5 col-md-5'>
			                <label>
			                  Total Deductions
			                </label>
		           		</div>
						<div class='col-sm-7 col-md-7'>
						<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
							<input type='number' id='txt_deductions' name='txt_deductions' class='form-control has-feedback-left' style='text-align:right;' value='<?php echo $total_deduction;?>' readonly/>
						</div>
					</div>
					
				</div>
				
				<div class='x_panel col-sm-12 col-md-12'>
					<div><label for='net_amount'>Total Net Amount - Receivables</label></div>
				<div class='col-sm-12 col-md-12'>
					<span class="fa fa-user form-control-feedback left" aria-hidden="true">Php</span>
					<input type='number' id='net_amount' name='net_amount' style='text-align:right;font-size:25px;'class='form-control' value='<?php echo $net_amount;?>' readonly/>
				</div>
				</div>

			</div>

			<div class="tile-stats">
				<br>
				<div class="col-sm-12 col-md-12"><label>Control Number</label></div>

				<center><h1><font color="red"><?php echo $control_number;?></font></h2></center>
				<input type="hidden" name="control_number" id="control_number" value="<?php echo $control_number;?>">

			</div>

			<div class="tile-stats">
				<br>
				<div class="col-sm-12 col-md-12"><label>Recieving Details</label></div>
				<div class='col-sm-12 col-md-12'>
					<div class='col-sm-12 col-md-12'>
						<label for='received_on'>Received On</label>
						<?php
							if($soa['status']=="For Deposit" || $soa['status']=="Paid"){
								echo "<input type='date' name='received_on' id='received_on' placeholder='Received On' class='form-control' value=" . $payment['received_on'] . " readonly/>";
							}elseif($soa['status']=="Waiting for Payment"){
								echo "<input type='date' name='received_on' id='received_on' placeholder='Received On' class='form-control'/>";
							}
						?>
					</div>

					<div class='col-sm-12 col-md-12'>
						<label for='received_by'>Received By</label>
						<?php 
							if($soa['status']=="For Deposit" || $soa['status']=="Paid"){
								$temp = $this->Abas->getUser($payment['received_by']);
								$received_by = $temp['full_name'];
								echo "<input type='text' name='received_by' id='received_by' placeholder='Received By' class='form-control' value='" . $received_by . "' readonly/>";

							}elseif($soa['status']=="Waiting for Payment"){
								$received_by = $_SESSION['abas_login']['userid'];

								echo "<input type='hidden' name='received_by' id='received_by' placeholder='Received By' class='form-control' value='" . $received_by . "' readonly/>";
								$temp = $this->Abas->getUser($received_by);
								$received_by_name = $temp['full_name'];
								echo "<input type='text' name='' id='' placeholder='' class='form-control' value='" .  $received_by_name . "' readonly/>";
							}
						?>
						
					</div>

					<div class='col-sm-12 col-md-12'>
						<label for='or_no'>Official Receipt No.</label>
						<?php
							if($soa['status']=="For Deposit" || $soa['status']=="Paid"){
								echo "<input type='text' id='or_no' name='or_no' placeholder='OR No.' class='form-control' value='" . $payment['official_receipt_number'] . "' readonly/>";
							}elseif($soa['status']=="Waiting for Payment"){
								echo "<input type='text' id='or_no' name='or_no' placeholder='OR No.' class='form-control'/>";
							}
						?>
					</div>

				</div>
			</div>	

				<div class="tile-stats">
					<br>
						<div class="col-sm-12 col-md-12"><label>Deposit Details</label></div>
					<div class='col-sm-12 col-md-12'>
						<div class='col-sm-12 col-md-12'>
							<label for='deposited_on'>Date Deposited</label>
							<?php
								if($soa['status']=="Paid"){
									echo "<input type='date' name='deposited_on' id='deposited_on' placeholder='Date Deposited' class='form-control' value='" . $payment['deposited_on'] . "' onchange='javascript:checkDepositDate();' readonly/>";
								}
								else{
									echo "<input type='date' name='deposited_on' id='deposited_on' placeholder='Date Deposited' class='form-control' onchange='javascript:checkDepositDate();'/>";
								}
							?>
						</div>
						
						<div class='col-sm-12 col-md-12'>
							<label for='method'>Payment Method</label>
							<?php
								if($soa['status']=="Paid"){
									echo "<input type='text' class='form-control' value='" . $payment['method'] . "' readonly>";
								}
								else{
									echo "<select name='method' id='method' class='form-control' onchange='javascript:payMethod();'>
											<option value=''>Choose One</option>
											<option value='Cash Deposit'>Cash Deposit</option>
											<option value='Check Deposit'>Check Deposit</option>
										</select>";
								}
							?>
						</div>
						
						<?php
								if($soa['status']=="Paid" && $payment['method']=="Check Deposit"){

								echo "<div class='col-sm-12 col-md-12'>
									<label for='method'>Check No.</label>
									<input type='text' class='form-control' value='" . $payment['check_number'] . "' readonly>
									</div>";

						        }else{

								echo "<div id='div_check_number' name='div_check_number' class='col-sm-12 col-md-12'>
									<label for='method'>Check No.</label>
									<input type='text' id='check_number' name='check_number' class='form-control' placeholder='Check No.' value=''>
								     </div>";
	
						        } 
						?>

						<div>
						</div>

						<div class='col-sm-12 col-md-12'>
							<label for='bank_account'>Deposited on Bank Account</label>
							<?php
								if($soa['status']=="Paid"){

									$bank = $payment['bank_account']['account_name'] . " - ". $payment['bank_account']['name'] . "(" . $payment['bank_account']['account_no'] . ")"; 
									echo "<input type='text' class='form-control' value='" . $bank . "' readonly>";
								}
								else{
									echo "<select id='bank_account' name='bank_account' class='form-control'>
												<option value=''>Choose One</option>
												" . $bankoptions . "
										  </select>";
								}
							?>
							
						</div>

						<div class='col-sm-12 col-md-12'>
							<label for='deposited_by'>Deposited By</label>

							<?php 
								if($soa['status']!="Paid"){
							?>
							<div>
								
								  <input type="radio" id="depositor" name="depositor" value="collector" onclick="javascript:depositedBy('collector');" checked>Company Collector/Cashier &nbsp  &nbsp
								  <input type="radio" id="depositor" name="depositor" value="client" onclick="javascript:depositedBy(<?php echo "'" . $client_name . "'"?>);">Client<br><br>
								 							
                          	</div>
                          	<?php 
                          		} 
                          	?>
							<?php
								if($soa['status']=="Paid"){
									echo "<input type='text' class='form-control' value='" . $payment['deposited_by']. "' readonly>";
								}
								else{
									echo "<input type='text' id='deposited_by' name='deposited_by' placeholder='Name of Depositor' class='form-control' value=''/>";
								}
							?>
							
						</div>
						
						<div class='col-sm-12 col-md-12'>
							<label for='ref_no'>Deposit Reference No.</label>
							<?php
								if($soa['status']=="Paid"){
									echo "<input type='text' class='form-control' value='" . $payment['deposit_reference_number']. "' readonly>";
								}
								else{
									echo "<input type='text' id='ref_no' name='ref_no' placeholder='Deposit Reference No.' class='form-control' value=''/>";
								}
							?>
					
						</div>
					</div>
				</div>
				
				<div class='col-sm-12 col-md-12'>
					<span class="pull-right">
				<?php if($soa['status']!="Paid"){?>
						
						<input type="button" class="btn btn-success btn-m" onclick="javascript:checkform();" value="Submit"/>
				<?php }?>

						<input type="button" class="btn btn-danger btn-m" value="Close" data-dismiss="modal" />

					</span>
				</div>
			</div>
		
		

		</div>
		
	</form>

<script  type="text/javascript">

$('#div_check_number').hide();

function payMethod(){

	var paymethod = document.getElementById('method').value;

    if ( paymethod ==  "Check Deposit"){                             
     	$('#div_check_number').show();
	}else{
		document.getElementById('check_number').value = "";     
		$('#div_check_number').hide();	
	}

}

function depositedBy(depositor){
	
	if(depositor == "collector"){
		document.getElementById('deposited_by').value = "";
		document.getElementById('deposited_by').readOnly = false;
	}else{
		document.getElementById('deposited_by').value = depositor;
		document.getElementById('deposited_by').readOnly = true;
	}

}

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
function checkDepositDate(){
	var date_receive = document.getElementById("received_on").value;
	var date_deposit = document.getElementById("deposited_on").value;
	
	if(date_deposit < date_receive){
		toastr['error']("Please select a Deposited date not later than the Received date!", "ABAS says:");
		document.getElementById("deposited_on").value = "";
	}

}
function chk_12_tax(){

   	document.getElementById('txt_12tax_less').value = 0;
    document.getElementById('txt_12tax').value = 0;
    document.getElementById('txt_5tax').value = 0;
    document.getElementById('chk_5tax').checked = false;
    document.getElementById('txt_2tax').value = 0;
    document.getElementById('chk_2tax').checked = false;
    document.getElementById('txt_1tax').value = 0;
    document.getElementById('chk_1tax').checked = false;
    document.getElementById('txt_deductions').value = 0;
    document.getElementById('txt_other_deductions').value = 0;

    document.getElementById('net_amount').value  = document.getElementById('gross_amount').value;

    if(document.getElementById('chk_12tax').checked){
         var vatable_amount = parseFloat((document.getElementById('gross_amount').value / 1.12) * 0.12).toFixed(2);
         document.getElementById('txt_12tax').value = vatable_amount;
    }else{
    	document.getElementById('txt_12tax_less').value = 0;
        document.getElementById('txt_12tax').value = 0;
        document.getElementById('txt_5tax').value = 0;
        document.getElementById('chk_5tax').checked = false;
        document.getElementById('txt_2tax').value = 0;
        document.getElementById('chk_2tax').checked = false;
        document.getElementById('txt_1tax').value = 0;
        document.getElementById('chk_1tax').checked = false;
        document.getElementById('txt_deductions').value = 0;
        document.getElementById('txt_other_deductions').value = 0;
    }

    var less_12tax = parseFloat(document.getElementById('gross_amount').value) - vatable_amount;
    document.getElementById('txt_12tax_less').value = parseFloat(less_12tax).toFixed(2); 

}
function chk_5_tax(){
    if(document.getElementById('chk_5tax').checked){
    	if(document.getElementById('chk_12tax').checked){
    		document.getElementById('txt_5tax').value = parseFloat(document.getElementById('txt_12tax_less').value * 0.05).toFixed(2);
    	}else{
    		document.getElementById('txt_5tax').value = parseFloat(document.getElementById('gross_amount').value * 0.05).toFixed(2);
    	}
    }else{
        document.getElementById('txt_5tax').value = 0;
    }

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_other_deductions').value);  
    document.getElementById('txt_deductions').value = parseFloat(result).toFixed(2);  

    var net_amount = parseFloat(document.getElementById('gross_amount').value).toFixed(2) - parseFloat(document.getElementById('txt_deductions').value).toFixed(2);  
    document.getElementById('net_amount').value = parseFloat(net_amount).toFixed(2);
}
function chk_2_tax(){
    if(document.getElementById('chk_2tax').checked){
    	if(document.getElementById('chk_12tax').checked){
    		   document.getElementById('txt_2tax').value = parseFloat(document.getElementById('txt_12tax_less').value * 0.02).toFixed(2);
    	}else{
    		   document.getElementById('txt_2tax').value = parseFloat(document.getElementById('gross_amount').value * 0.02).toFixed(2);
    	}
    }else{
        document.getElementById('txt_2tax').value = 0;    
    }

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_other_deductions').value);  
    document.getElementById('txt_deductions').value = parseFloat(result).toFixed(2);   

    var net_amount = parseFloat(document.getElementById('gross_amount').value).toFixed(2) - parseFloat(document.getElementById('txt_deductions').value).toFixed(2);  
    document.getElementById('net_amount').value = parseFloat(net_amount).toFixed(2);
}
function chk_1_tax(){
    if(document.getElementById('chk_1tax').checked){
    	if(document.getElementById('chk_12tax').checked){
    		   document.getElementById('txt_1tax').value = parseFloat(document.getElementById('txt_12tax_less').value * 0.01).toFixed(2);
    	}else{
    	       document.getElementById('txt_1tax').value = parseFloat(document.getElementById('gross_amount').value * 0.01).toFixed(2);	
    	}
    }else{
        document.getElementById('txt_1tax').value = 0;
    }

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_other_deductions').value);  
    document.getElementById('txt_deductions').value = parseFloat(result).toFixed(2);

     var net_amount = parseFloat(document.getElementById('gross_amount').value).toFixed(2) - parseFloat(document.getElementById('txt_deductions').value).toFixed(2);  
    document.getElementById('net_amount').value = parseFloat(net_amount).toFixed(2);
}

function type_other_deductions(){

    var result = parseFloat(document.getElementById('txt_5tax').value) + parseFloat(document.getElementById('txt_2tax').value) + parseFloat(document.getElementById('txt_1tax').value) + parseFloat(document.getElementById('txt_other_deductions').value);  
    document.getElementById('txt_deductions').value = parseFloat(result).toFixed(2);  

    var net_amount = parseFloat(document.getElementById('gross_amount').value).toFixed(2) - parseFloat(document.getElementById('txt_deductions').value).toFixed(2);  
    document.getElementById('net_amount').value = parseFloat(net_amount).toFixed(2);
}

function checkform() {
	var msg="";
	//var patt1=/^[0-9]+$/i;
	var patt1=/^-?(?:\d+|\d*\.\d+)$/;

	var amount=document.getElementById("net_amount").value;
	if (amount<=0) {
		msg+="Total Net Amount is required! <br/>";
	}
	var received_on=document.getElementById("received_on").value;
	if (received_on==""){
		msg+="Date Received On is required! <br/>";
	}
	var or_no=document.getElementById("or_no").value;
	if (or_no==""){
		msg+="OR number is required! <br/>";
	}
	/*var method=document.getElementById("method").selectedIndex;
	if (method=="") {
		msg+="Method is required! <br/>";
	}*/

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {
		document.getElementById("bill_form").submit();
		return true;
	}

}
</script>