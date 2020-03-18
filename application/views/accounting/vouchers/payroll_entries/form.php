<style>
		.lbl{font-weight:bold;text-align:right;}
	</style>
<?php

$department_code_options = $vessel_code_options = $contract_code_options = "";
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

$a1=$a2 = "";
if(!empty($transaction)){

	if(!empty($transaction_attachments)){
		foreach($transaction_attachments as $attachment){
			switch($attachment['document_name']){
				case "Payroll Print-out":
					$a1=$attachment['document_file'];
				break;
				case "Other Supporting Documents":
					$a2=$attachment['document_file'];
				break;
			}
		}
	}
}


//$this->Mmm->debug($payroll->id);

?>

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Payroll Entries
		</h2>
	</div>
</div>

	<div class='panel-body'>
	
		<form id='payroll_entries_form' role='form' action='<?php echo HTTP_PATH."accounting/payroll_entries/insert/for_clearing/".$payroll->id?>' method='POST' enctype='multipart/form-data'>

		<input type='hidden' id='company_id' name='company_id' value='<?php echo $payroll->company_id;?>'>

		<div class='tile-stats col-xs-12 col-md-12'>
			<br><label>Payroll Details</label><br>

			<table class="table">
				<tr>
					<td class='lbl'>Payroll Transaction Code#:</td>
					<td><?php echo $payroll->id?></td>
					<td class='lbl'>Payroll Period:<td>
					<td><?php echo $payroll->payroll_coverage. " of " .date("F Y", strtotime($payroll->payroll_date));?></td>
				</tr>
				<tr>
					<td class='lbl'>Company:</td>
					<td><?php echo $payroll->company_name;?></td>
					<td class='lbl'>Payroll Amount:<td>
					<td><?php echo number_format($payroll_details['total_net_payroll'],2,'.',',');?></td>
				</tr>
				<tr>
					<td class='lbl'>Created On:</td>
					<td><?php echo date("j F Y h:m:s A", strtotime($payroll->created_on));?></td>
					<td class='lbl'>Payroll Locked?<td>
					<td><?php echo ($payroll->locked==1)?"Yes":"No";?></td>
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
						<td align='center'><input type='checkbox' name='attachment[]' id='payroll_printout_check' value='Payroll Print-out'
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
						<td>Payroll Print-out</td>
						<?php 
							if(!empty($transaction)) {
								if($a1!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a1.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a1.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="payroll_printout_file" disabled></td>';
							}
						?>
					</tr>
					
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='others_check' value='Other Supporting Documents'
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
						<td>Other Supporting Documents</td>
						<?php 
							if(!empty($transaction)) {
								if($a2!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/attachments/'.$a2.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a2.'</td>';
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

									$pagibig_contri_vessels =0;
									$sss_contri_vessels=0;
									$philhealth_contri_vessels=0;
									$advances=0;
									$sss_loan_payable=0;
									$sss_balance=0;
									$pagibig_loan_payable=0;
									$pagibig_balance=0;
									$allowance=0;
									$witholding_tax=0;
									$sss_premium_payable=0;
									$pagibig_premium_payable=0;
									$philhealth_premium_payable=0;
									$sss_contri=0;
									$pagibig_contri=0;
									$philhealth_contri=0;
	
										if(empty($transaction)) {

											
											$ctr=0;
											foreach($gross_per_vessel as $gv_row){
												echo "<tr id='row_entry".$ctr."' class='tbl_row_entry'>";
												if($gv_row['vessel_id']=='99996' || $gv_row['vessel_id']=='99998' || $gv_row['vessel_id']=='99995' || $gv_row['vessel_id']=='99997' || $gv_row['vessel_id']=='99999'){

													if($gv_row['vessel_id']=='99996'){
														$dept_code = 12;
													}elseif($payroll->company_id==5){
														$dept_code = 14;	
													}else{
														$dept_code = 00;
													}

													echo "<td><input type='hidden' id='coa_id[]' name='coa_id[]' value='207'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='".$dept_code	."' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														".$dept_code."-000-0000-61666000
													</td>
													<td>Salaries and Wages (".$gv_row['vessel_name'].")</td>";
													$pagibig_contri = $pagibig_contri + $gv_row['pagibig_amount'];
													$sss_contri = $sss_contri + $gv_row['sss_employer_amount'];
													$philhealth_contri = $philhealth_contri + $gv_row['philhealth_amount'];
												}else{
													echo "<td><input type='hidden' id='coa_id[]' name='coa_id[]' value='168'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='".$gv_row['vessel_id']."' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-".$gv_row['vessel_id']."-0000-51575067
														</td>
														<td>Direct Cost - Salaries and Wages (".$gv_row['vessel_name'].")</td>";
													$pagibig_contri_vessels = 	$pagibig_contri_vessels + $gv_row['pagibig_amount'];
													$sss_contri_vessels = $sss_contri_vessels + $gv_row['sss_employer_amount'];
													$philhealth_contri_vessels  = $philhealth_contri_vessels + $gv_row['philhealth_amount'];
												}

												if($gv_row['vessel_id']=='99998' || $gv_row['vessel_id']=='99995' || $gv_row['vessel_id']=='99997' || $gv_row['vessel_id']=='99999'){
													$salary = $gv_row['gross_amount']-$gv_row['allowance_amount'];
												}else{
													$salary = $gv_row['gross_amount'];
												}

												echo "<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount'  value='".$salary."' style='text-align:right' readonly>".number_format($salary,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='0' style='text-align:right' readonly>0</td>
													</td>
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";
												  $ctr++;

												  $advances = 	$advances + $gv_row['advances_amount'];
												  $sss_loan_payable = $sss_loan_payable + $gv_row['sss_loan_payable_amount'];
												  $sss_balance =  $sss_balance + $gv_row['sss_loan_balance_amount'];
												  $pagibig_loan_payable = $pagibig_loan_payable + $gv_row['pagibig_loan_payable_amount'];
												  $pagibig_balance =  $pagibig_balance + $gv_row['pagibig_loan_balance_amount'];

												  $allowance =  $allowance + $gv_row['allowance_amount'];
												  $witholding_tax =  $witholding_tax + $gv_row['tax_amount'];

												  $sss_premium_payable = $sss_premium_payable + $gv_row['sss_amount'];
												  $pagibig_premium_payable = $pagibig_premium_payable + $gv_row['pagibig_amount'];
												  $philhealth_premium_payable = $philhealth_premium_payable + $gv_row['philhealth_amount'];

											}

											if($allowance!=0 && ($gv_row['vessel_id']=='99998' || $gv_row['vessel_id']=='99995' || $gv_row['vessel_id']=='99997' || $gv_row['vessel_id']=='99999')){
												echo "<tr id='row_entry10001' class='tbl_row_entry'>
														<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='214'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-61666007
													</td>
													<td>Employee Benefits - Transportation Allowance</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='".$allowance ."' style='text-align:right' readonly>".number_format($allowance,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='0' style='text-align:right' readonly>0
													</td>
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";
											}

											if($sss_contri!=0){
												echo "<tr id='row_entry10002' class='tbl_row_entry'>
														<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='210'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-61666003
													</td>
													<td>Employee Benefits - SSS Premium</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='".$sss_contri ."' style='text-align:right' readonly>".number_format($sss_contri,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='0' style='text-align:right' readonly>0
													</td>
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";
											}


											if($pagibig_contri!=0){
												echo "<tr id='row_entry10003' class='tbl_row_entry'>
														<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='211'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-61666004
													</td>
													<td>Employee Benefits - HMDF Premium</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='".$pagibig_contri ."' style='text-align:right' readonly>".number_format($pagibig_contri,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='0' style='text-align:right' readonly>0
													</td>
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";
											}

											if($philhealth_contri!=0){
												 echo "<tr id='row_entry10004' class='tbl_row_entry'>
														<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='212'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-61666005
													</td>
													<td>Employee Benefits - Philhealth Premium</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='".$philhealth_contri ."' style='text-align:right' readonly>".number_format($philhealth_contri,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='0' style='text-align:right' readonly>0
													</td>
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";
											}

											if($sss_contri_vessels!=0){
												  echo "<tr id='row_entry10005' class='tbl_row_entry'>
														<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='171'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-51575070
													</td>
													<td>Direct Cost - Employee Benefits - SSS Premium</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='".$sss_contri_vessels ."' style='text-align:right' readonly>".number_format($sss_contri_vessels,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='0' style='text-align:right' readonly>0
													</td>
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";
											}
											
											if($pagibig_contri_vessels!=0){
												echo "<tr id='row_entry10006' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='172'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-51575071
														</td>
													<td>Direct Cost - Employee Benefits - HMDF Premium</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='".$pagibig_contri_vessels ."' style='text-align:right' readonly>".number_format($pagibig_contri_vessels,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='0' style='text-align:right' readonly>0
													</td>
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";
											}

											if($philhealth_contri_vessels!=0){
											  echo "<tr id='row_entry10007' class='tbl_row_entry'>
														<td>
														<input type='hidden' id='coa_id[]' name='coa_id[]' value='173'>
														<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
														<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
														<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
														00-000-0000-51575072
													</td>
													<td>Direct Cost - Employee Benefits - Philhealth Premium</td>
													<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='".$philhealth_contri_vessels ."' style='text-align:right' readonly>".number_format($philhealth_contri_vessels,2,'.',',')."
													</td>
													<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='0' style='text-align:right' readonly>0
													</td>
													<td align='center'>
														<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
													</td>
												  </tr>";
											}
											
											if($advances!=0){
												echo "<tr id='row_entry10008' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='12'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-11021180
														</td>
														<td>Advances to Officers & Employees</td>
														
														<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
														</td>
														<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='".$advances."' style='text-align:right' readonly>".number_format($advances,2,'.',',')."
														</td>
														<td align='center'>
															<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
														</td>
													  </tr>";
											}

											if($witholding_tax!=0){
												echo "<tr id='row_entry10009' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='75'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-21212035
														</td>
														<td>Withholding tax - Compensation</td>
														
														<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
														</td>
														<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='".$witholding_tax."' style='text-align:right' readonly>".number_format($witholding_tax,2,'.',',')."
														</td>
														<td align='center'>
															<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
														</td>
													  </tr>";
											}

											if($sss_premium_payable!=0){
												echo "<tr id='row_entry10010' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='79'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-21212050
														</td>
														<td>SSS Premium Payable</td>
														
														<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
														</td>
														<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='".$sss_premium_payable."' style='text-align:right' readonly>".number_format($sss_premium_payable,2,'.',',')."
														</td>
														<td align='center'>
															<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
														</td>
													  </tr>";
											}

											if($sss_loan_payable!=0){
												echo "<tr id='row_entry10011' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='80'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-21212051
														</td>
														<td>SSS Loan Payable</td>
														
														<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
														</td>
														<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='".$sss_loan_payable."' style='text-align:right' readonly>".number_format($sss_loan_payable,2,'.',',')."
														</td>
														<td align='center'>
															<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
														</td>
													  </tr>";
											}

											if($pagibig_premium_payable!=0){
												echo "<tr id='row_entry10012' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='81'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-21212055
														</td>
														<td>HDMF Premium Payable</td>
														
														<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
														</td>
														<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='".($pagibig_premium_payable*2)."' style='text-align:right' readonly>".number_format(($pagibig_premium_payable*2),2,'.',',')."
														</td>
														<td align='center'>
															<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
														</td>
													  </tr>";
											}

											if($pagibig_loan_payable!=0){
												echo "<tr id='row_entry10013' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='82'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-21212056
														</td>
														<td>HDMF Loan Payable</td>
														
														<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
														</td>
														<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='".$pagibig_loan_payable."' style='text-align:right' readonly>".number_format($pagibig_loan_payable,2,'.',',')."
														</td>
														<td align='center'>
															<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
														</td>
													  </tr>";
											}

											if($philhealth_premium_payable!=0){
												echo "<tr id='row_entry10014' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='83'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-21212060
														</td>
														<td>PHIC Premium Payable</td>
														
														<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
														</td>
														<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount' value='".($philhealth_premium_payable*2)	."' style='text-align:right' readonly>".number_format(($philhealth_premium_payable*2),2,'.',',')."
														</td>
														<td align='center'>
															<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
														</td>
													  </tr>";
											}

											if($payroll_details!=0){
												echo "<tr id='row_entry10015' class='tbl_row_entry'>
														<td>
															<input type='hidden' id='coa_id[]' name='coa_id[]' value='72'>
															<input type='hidden' id='department[]' name='department[]' class='form-control' value='00' readonly>
															<input type='hidden' id='vessel[]' name='vessel[]' class='form-control' value='000' readonly>
															<input type='hidden' id='contract[]' name='contract[]' class='form-control' value='0000' readonly>
															00-000-0000-21212020
														</td>
														<td>Accrued Salaries and Wages</td>
														
														<td style='text-align:right'><input type='hidden' id='debit[]' name='debit[]' class='form-control debit_amount' value='0' style='text-align:right' readonly>0</td>
														</td>
														<td style='text-align:right'><input type='hidden' id='credit[]' name='credit[]' class='form-control credit_amount'  value='".$payroll_details['total_net_payroll']."' style='text-align:right' readonly>".number_format($payroll_details['total_net_payroll'],2,'.',',')."
														</td>
														<td align='center'>
															<a class='btn-remove-row btn btn-danger btn-xs'>×</a>
														</td>
													  </tr>";
											}

											echo "<tr id='row_entry10016' class='tbl_row_entry'></tr>";

										}else{
											echo "<tr id='row_entry0' class='tbl_row_entry'></tr>";
										}
									 
										if(!empty($transaction)) {
											foreach($transaction_journal_entries as $row){
												if($row['reference_table']=='hr_payroll'){
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
								<input type="button" class="btn btn-success btn-m" onclick="javascript:approvePayrollEntries(<?php echo $payroll->id;?>,<?php echo $transaction['id'];?>);" value="Approve"/>
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


$('#payroll_printout_check').change(function(){
   $(".payroll_printout_file").prop("disabled", !$(this).is(':checked'));
    $(".payroll_printout_file").val("");
});
$('#others_check').change(function(){
   $(".others_file").prop("disabled", !$(this).is(':checked'));
    $(".others_file").val("");
});


var i_check=10016;

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
	var flag_credit_payroll = 0;
	var xdebit_amount = 0;
	 var xcredit_amount = 0;
    $("#table_entries input").each(function() {
    	  
	      var total_debit_amount = 0;
	      var total_credit_amount = 0;
  		  var debit_amount = document.getElementsByName('debit[]');
  		  var credit_amount = document.getElementsByName('credit[]');
  		  var payroll_amount = parseFloat($('#payroll_amount').val()).toFixed(2);

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
		  	  /*if(total_credit_amount != payroll_amount){
		  	  	flag_credit_payroll = 1;
		  	  }else{
		  	  	flag_credit_payroll = 0;
		  	  }*/
		  }
		
    });

    if(flag_debit_credit==1){
    	msg+="Debit("+xdebit_amount+") and Credit("+xcredit_amount+") amount are not balance! <br/>";
    }
    if(flag_credit_payroll==1){
    	msg+="Total Credit amount is not equal with total payroll amount! <br/>";
    }

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Payroll Entries",
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
			        document.getElementById("payroll_entries_form").submit();
			        return true;
		    	}
		    }
		});

	}
}

function approvePayrollEntries(payroll_id,transaction_id) {

		bootbox.confirm({
			title: "Payroll Entries",
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
					  window.location.href = "<?php echo HTTP_PATH;?>accounting/payroll_entries/approve/for_posting/" + payroll_id + "/" + transaction_id;
				}
		    }
		});

}

</script>

