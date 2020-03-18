
<style>
	#content{ margin-top:-20px}
    .demo-content{
        padding: 15px;
        font-size: 18px;
        background: #dbdfe5;
        margin-bottom:0px;
    }
    .demo-content.bg-alt{
        background: #abb1b8;
    }
	#heading{ min-height: 50px;}
	table thead tr th {
		padding:5px;
	}
</style>
<div style="margin-left:10px">
	<span style="float:right; margin-right:10px; margin-top:0px">
		<button type="button" class="close" data-dismiss="modal" onclick="javascript:window.location.reload()">
			<span class="glyphicon glyphicon-remove"> </span>
		</button>
	</span>
	<h3>Payslip</h3>
</div>

<div class = "panel panel-default" style="margin:0px 10px;border:#999999 thin solid">
<?php
foreach($_SESSION['payroll']['data'] as $payslip) {
	$employee_data	=	$this->Abas->getEmployee($payslip['employee_id']);
	$tax_code		=	$employee_data['tax_code'];
	$position		=	$employee_data['position_name'];
	$company		=	$employee_data['company_name'];
	$department		=	$employee_data['department_name'];

	$this->Mmm->debug($payslip);

	// compute from loan payments per type
	// $total_loan_payments	=	$payslip['elf']['loan'] + $payslip['sss']['loan'] + $payslip['pi']['loan'];
	$total_loan_payments	=	array("all"=>0, "pi"=>0, "ph"=>0, "sss"=>0, "elf"=>0, "cash advance"=>0);
	$total_elf_loan_payments=	0;
	if(isset($payslip['paid_loans'])) {
		foreach($payslip['paid_loans'] as $loan_id=>$loan_amt) {
			$loan_details	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loan_id);
			if($loan_details != false) {
				if($loan_details->row()) {
					$loan_details	=	$loan_details->row();
					if($loan_details->loan_type != "ELF") {
						$total_loan_payments[strtolower($loan_details->loan_type)]	=	$loan_amt;
					}
					else {
						$total_elf_loan_payments	=	$total_elf_loan_payments + $loan_amt;
					}
				}
			}
		}
		foreach($total_loan_payments as $total_per_loan) {
			$total_loan_payments['all']	=	$total_loan_payments['all'] + $total_per_loan;
		}
		// $this->Mmm->debug($total_loan_payments);
	}
	$total_income	=	$payslip['monthly']/2 + $payslip['allowance'] + $payslip['ot_time']['regular'] + $payslip['ot_time']['holiday'] + $payslip['bonus'];
	$total_deduction=	$payslip['withholding'] + $payslip['sss']['payable'] + $payslip['ph']['payable'] + $payslip['pi']['payable'] + $payslip['cash_advance']['payable'] + $payslip['ut'];
	if(($total_income - $total_deduction) > 0) {
			echo '
				<div class = "panel-header" style=" background:#999; color:#000; margin-bottom:0px; height:30px">&nbsp;
					<span style="margin-left:5px; margin-top:5px; float:left">&nbsp;
					<img src="'.HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png" width="25px" align="absmiddle">
					<span style="font-weight:600; color:#000066">'.$company.'</span>
					</span>
					<span style="margin-right:255px; margin-top:5px; float:right">&nbsp;
					<span style="font-weight:600; color:#000066">Pay Period: '.$_SESSION['payroll']['period'].'&nbsp;&nbsp;'.date('F Y',strtotime($_SESSION['payroll']['month']." ".$_SESSION['payroll']['year'])).'</span>
					</span>
				</div>
				<div class = "panel-body" style="width:100%">
				<table width="100%" cellpadding="5px" cellspacing="5" border="1">
					<thead>
						<tr height="10%">
							<th width="25%" colspan="3" align="left">
							<strong>'.$employee_data['full_name']." (".$employee_data['employee_id'].")".'
							<br>'.$department.'-'.ucwords($position).'</strong>
							</td>

							<th width="25%">
								<strong>ELF Contribution</strong>
								<p><small>(Total Contribution:'.$this->Abas->currencyFormat($employee_data['total_elf_contribution']).')</small></p>
							</td>

						</tr>
						<tr style="background:#000; color:#FFFFFF;">
							<th width="25%" class="text-center">Income</th>
							<th width="25%" class="text-center">Deductions</th>
							<th width="25%" class="text-center">Loans</th>
							<th width="25%">'.$employee_data['employee_id'].'</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td align="left" valign="top">
								<table class="table table-condensed" width="98%"  style="font-size:12px">
									<tbody>
										<tr>
											<td>Basic Salary:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['monthly']).'</td>
										</tr>
										<tr>
											<td>Salary for this Pay Period:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['monthly'] / 2).'</td>
										</tr>
										<tr>
											<td>Regular Overtime:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['ot']['regular']).'</td>
										</tr>
										<tr>
											<td>Holiday Overtime:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['ot']['holiday']).'</td>
										</tr>
										<tr>
											<td>Allowance:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['allowance']).'</td>
										</tr>
										<tr>
											<td>Others (Specify):</td>
											<td align="right">0.00</td>
										</tr>
										<tr>
											<td>Leave Credits:</td>
											<td align="right">'.$employee_data['leave_credits'].' days</td>
										</tr>
										<tr style="font-weight:600">
											<td align="right">
												<strong>Total Income:</strong>
											</td>
											<td align="right">
												<strong>'.$this->Abas->currencyFormat($total_income).'</strong>
											</td>
										</tr>

									</tbody>
								</table>
							</td>
							<td align="left" valign="top">
								<table class="table table-condensed" width="98%"  style="font-size:12px">
									<tbody>
										<tr>
											<td>Withholding Tax('.$tax_code.'):</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['withholding']).'</td>
										</tr>
										<tr>
											<td>SSS Contribution:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['sss']['payable']).'</td>
										</tr>
										<tr>
											<td>Philhealth Contribution:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['ph']['payable']).'</td>
										</tr>
										<tr>
											<td>Pagibig Contribution:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['pi']['payable']).'</td>
										</tr>
										<tr>
											<td>Absences/Tardiness:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['ut']).'</td>
										</tr>
										<tr>
											<td colspan=99>&nbsp;</td>
										</tr>
										<tr>
											<td colspan=99>&nbsp;</td>
										</tr>
										<tr style="font-weight:600">
											<td align="right"><strong>Total Deductions:</strong></td>
											<td align="right"><strong>'.$this->Abas->currencyFormat($total_deduction).'</strong></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td align="left" valign="top">
								<table class="table table-condensed" width="98%"  style="font-size:12px">
									<tbody>
										<tr>
										<td>Cash Advance (Balance):</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['cash_advance']['loan_balance']).'</td>
										</tr>
										<tr>
										<td>Cash Advance<br/>Payment:</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['cash_advance']['payable']).'</td>
										</tr>
										<tr>
										<td>SSS (Salary Loan):</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['sss']['loan_balance']).'</td>
										</tr>
										<tr>
										<td>SSS Loan Payment:</td>
										<td align="right">'.$this->Abas->currencyFormat($total_loan_payments['sss']).'</td>
										</tr>
										<tr>
										<td>Pagibig Loan:</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['pi']['loan_balance']).'</td>
										</tr>
										<tr>
										<td>Pagibig Loan Payment:</td>
										<td align="right">'.$this->Abas->currencyFormat($total_loan_payments['pi']).'</td>
										</tr>
										<tr>
										<td>Others (Specify):</td>
										<td align="right">&nbsp;<!--- use this for other payment ---></td>
										</tr>
										<tr style="font-weight:600">
										<td align="right"><strong>Total Payments:</strong></td>
										<td align="right"><strong>'.$this->Abas->currencyFormat($total_loan_payments['all']).'</strong></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td align="left" valign="top">
								<table class="table table-condensed" width="98%"  style="font-size:12px">
									<tbody>
										<tr>
											<td>Contribution:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['elf']['payable']).'</td>
										</tr>
										<tr>
											<td>Elf Loan Payment:</td>
											<td align="right">'.$this->Abas->currencyFormat($total_elf_loan_payments).'</td>
										</tr>

									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="background:#000; color:#FFFFFF">
							<img src="'.HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png" width="25px" align="absmiddle">
					<span style="font-weight:600; color:#33CCFF">aps</span>
							<span style="font-weight:600; float:right; margin-right:20px">Net Pay: Php '.$this->Abas->currencyFormat(($total_income - $total_deduction)).'</span>
							</td>
							<td>'.$this->Abas->currencyFormat(($total_income - $total_deduction - $total_elf_loan_payments - $payslip['elf']['payable'])).'</td>
						</tr>
					</tbody>

				</table>
				</div>


				';
			}
}

?>


</div>