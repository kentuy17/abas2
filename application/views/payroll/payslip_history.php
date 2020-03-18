
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
<div style="margin-left:10px; width:950px">
	<span style="float:right; margin-right:10px; margin-top:0px">
		<button type="button" class="close" data-dismiss="modal" onclick="javascript:window.location.reload()">
			<span class="glyphicon glyphicon-remove"> </span>
		</button>
	</span>
</div>

<div class = "panel panel-default" style="margin:0px 10px;border:#999999 thin solid">
<a href="<?php echo HTTP_PATH."payroll_history/payslip_printable/".$payroll_id."/employee"; ?>" target="_new">Print</a>
<?php
	foreach($details as $payslip) {
		$this->Mmm->debug($payslip);
		$employee_data	=	$this->Abas->getEmployee($payslip['emp_id']);
		$tax_code		=	$employee_data['tax_code'];
		$position		=	$employee_data['position_name'];
		$company		=	$employee_data['company_name'];
		$department		=	$employee_data['department_name'];

		// compute from loan payments per type
		// $total_loan_payments	=	$payslip['elf']['loan'] + $payslip['sss']['loan'] + $payslip['pi']['loan'];
		$total_loan_payments	=	array("all"=>0, "pi"=>0, "ph"=>0, "sss"=>0, "elf"=>0, "cash advance"=>0);
		if(isset($payslip['paid_loans'])) {
			foreach($payslip['paid_loans'] as $loan_id=>$loan_amt) {
				$loan_details	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loan_id);
				if($loan_details != false) {
					if($loan_details->row()) {
						$loan_details	=	$loan_details->row();
						$total_loan_payments[strtolower($loan_details->loan_type)]	=	$loan_amt;
					}
				}
			}
			foreach($total_loan_payments as $total_per_loan) {
				$total_loan_payments['all']	=	$total_loan_payments['all'] + $total_per_loan;
			}
		}

		if($payslip['salary']>0) {
			$total_income		=	$payslip['salary'] + $payslip['allowance'] + $payslip['regular_overtime_amount'] + $payslip['holiday_overtime_amount'] + $payslip['bonus'];
			$total_deduction	=	$payslip['tax'] + $payslip['sss_contri_ee'] + $payslip['phil_health_contri'] + $payslip['pagibig_contri'] + $payslip['undertime_amount'] + $payslip['cash_advance'];
			$total_loans		=	$payslip['pagibig_loan'] + $payslip['sss_loan'] + $payslip['cash_advance'];
			$total_elf_deduction=	$payslip['elf_contri'] + $payslip['elf_loan'];
			echo '
				<div class = "panel-header" style=" background:#999; color:#000; margin-bottom:0px; height:30px">&nbsp;
					<span style="margin-left:5px; margin-top:5px; float:left">&nbsp;
					<img src="'.HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png" width="25px" align="absmiddle">
					<span style="font-weight:600; color:#000066">'.$company.'</span>
					</span>
					<span style="margin-right:255px; margin-top:5px; float:right">&nbsp;
					'.
					((isset($summary))?'<span style="font-weight:600; color:#000066">Pay Period: '.$summary->payroll_coverage.'&nbsp;&nbsp;'.date('F Y',strtotime($summary->payroll_date)).'</span>':'')
					.'</span>
				</div>
				<div class = "panel-body" style="width:100%">
				<table width="100%" cellpadding="5px" cellspacing="5" border="1">
					<thead>
						<tr height="10%">
							<strong>
								<th width="25%" colspan="3" align="left">
									'.$employee_data['full_name']." (".$employee_data['employee_id'].")".'
									<br>'.$department.'-'.ucwords($position).'
								</th>
							</strong>
						</tr>
						<tr style="background:#000; color:#FFFFFF;">
							<th width="33%" class="text-center">Income</th>
							<th width="33%" class="text-center">Deductions</th>
							<th width="33%" class="text-center">Loans</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td align="left" valign="top">
								<table class="table table-condensed" width="98%"  style="font-size:12px">
									<tbody>
										<tr>
											<td>Basic Salary:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['salary']*2).'</td>
										</tr>
										<tr>
											<td>Salary for this Pay Period:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['salary']).'</td>
										</tr>
										<tr>
											<td>Regular Overtime:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['regular_overtime_amount']).'</td>
										</tr>
										<tr>
											<td>Holiday Overtime:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['holiday_overtime_amount']).'</td>
										</tr>
										<tr>
											<td>Allowance:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['allowance']).'</td>
										</tr>
										<tr>
											<td>Others (Specify):</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['bonus']).'</td>
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
											<td align="right">'.$this->Abas->currencyFormat($payslip['tax']).'</td>
										</tr>
										<tr>
											<td>SSS Contribution:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['sss_contri_ee']).'</td>
										</tr>
										<tr>
											<td>Philhealth Contribution:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['phil_health_contri']).'</td>
										</tr>
										<tr>
											<td>Pagibig Contribution:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['pagibig_contri']).'</td>
										</tr>
										<tr>
											<td>Absences/Tardiness:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['undertime_amount']).'</td>
										</tr>
										<tr>
											<td>Others (Specify):</td>
											<td align="right"><!--- use this for other deductions ---></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td align="right">&nbsp;</td>
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
										<td align="right">'.$this->Abas->currencyFormat($payslip['cash_advance_balance']).'</td>
										</tr>
										<tr>
										<td>Cash Advance Payment:</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['cash_advance']).'</td>
										</tr>
										<tr>
										<td>SSS (Salary Loan):</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['sss_loan_balance']).'</td>
										</tr>
										<tr>
										<td>SSS Loan Payment:</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['sss_loan']).'</td>
										</tr>
										<tr>
										<td>Pagibig Loan:</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['pagibig_loan_balance']).'</td>
										</tr>
										<tr>
										<td>Pagibig Loan Payment:</td>
										<td align="right">'.$this->Abas->currencyFormat($payslip['pagibig_loan']).'</td>
										</tr>
										<tr style="font-weight:600">
										<td align="right"><strong>Total Payments:</strong></td>
										<td align="right"><strong>'.$this->Abas->currencyFormat($total_loans).'</strong></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td align="left" valign="top">
								<table class="table table-condensed" width="98%"  style="font-size:12px">
									<tbody>
										<tr>
											<td>Contribution:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['elf_contri']).'</td>
										</tr>
										<tr>
											<td>Elf Loan Payment:</td>
											<td align="right">'.$this->Abas->currencyFormat($payslip['elf_loan']).'</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="background-color:#F4F4F4;">
								<div style="width:100%; text-align:right;">
									<span style="font-weight:600;">
										Net Pay: Php '.$this->Abas->currencyFormat(($total_income - $total_deduction - $total_loans)).'
									</span>
								</div>
							</td>
						</tr>
					</tbody>

				</table>
				</div>


				';
		}
	}
?>


</div>