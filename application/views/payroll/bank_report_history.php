<?php
$company	=	$this->Abas->getCompany($summary->company_id);
$table	=	"";
foreach($all_employees as $ae) {
	// echo "<pre>";print_r($ae);echo "</pre>";
	$employee_data	=	$this->Abas->getEmployee($ae->emp_id);
	// $withholding	=	$this->Payroll_model->getTax($ae->id);
	// $taxable	=	$this->Payroll_model->getTaxable($ae->salary*2);
	// $employee_data	=	$this->Abas->getEmployee($ae->emp_id);
	$table	.=	"<tr>";
	// echo "<h1>".$ae['full_name']."</h1>";
	if($employee_data['salary_rate']!=0) {
		$total_income		=	$ae->salary + $ae->allowance + $ae->regular_overtime_amount + $ae->holiday_overtime_amount + $ae->bonus;
		$total_deduction	=	$ae->tax + $ae->sss_contri_ee + $ae->phil_health_contri + $ae->pagibig_contri + $ae->undertime_amount + $ae->cash_advance;
		$total_elf_deduction=	$ae->elf_contri + $ae->elf_loan;
		$table	.= "<td style='text-align:center;'>".$employee_data['employee_id']."</td>";
		$table	.= "<td style='text-align:left;'>".$employee_data['full_name']."</td>";
		$table	.= "<td style='text-align:center;'>".$employee_data['bank_account_num']."</td>";
		//$table	.= "<td>".$this->Abas->currencyFormat($withholding['per_cutoff']['tax_payable'])."</td>";
		// $table	.= "<td>".$this->Abas->currencyFormat($taxable['per_cutoff'])."</td>";
		$table	.= "<td>".$this->Abas->currencyFormat(($total_income - ($total_deduction + $total_elf_deduction)))."</td>";
	}
	else {
		// $table	.=	"<tr><td colspan=99 style='text-align:center;'>Invalid Salary grade for ".$employee_data['full_name']."(".$employee_data['employee_id'].")!</td></tr>";
	}
	$table	.=	"</tr>";
}
?>
<div class = "panel panel-default">
	<div class = "panel-heading" style=" background:#CCC; color:#000">
		<h3 class = "panel-title">
			<span><strong><h4><img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   <?php echo $company->name; ?></h4></strong></span>
			<span style="float:right; margin-right:10px; margin-top:-50px"><h3>Bank Report</h3></span>
		</h3>
	</div>

	<div class = "panel-body" style="width:100%">
		<div style="float:left;margin-left;0px">
			<h5>Bank Report</h5>
			<a href="<?php echo HTTP_PATH."payroll_history/bank_printable/".$payroll_id; ?>" target="_new">Print</a>
		</div>
		<div style="float:right; margin-right:10px">
			<h5>Payroll Period: <?php echo $summary->payroll_coverage.", ".date("F Y",strtotime($summary->payroll_date."-01")); ?></h5>
		</div>

		<table class="table table-condensed table-bordered" style="font-size:12px">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr>
					<th width="3%" class="text-center">Employee ID</th>
					<th width="12%" class="text-center">Name</th>
					<th width="5%" class="text-center">Account Number</th>
					<th width="5%" class="text-center">Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php
				echo $table;
				?>
			</tbody>
		</table>
	</div>


	<div class = "panel-footer">
		<div style="margin-bottom:10px">
			<table width="100%" cellpadding="1px" cellspacing="5"   >
				<thead >
					<tr >
						<th width="35%" class="text-left">Prepared by:</th>
						<th width="35%" class="text-left">Checked by:</th>
						<th width="30%" class="text-left">Noted by:</th>
					</tr>
				</thead>
				<tbody >
					<tr >
						<th width="35%" class="text-left">_____________________________</th>
						<th width="35%" class="text-left">_____________________________</th>
						<th width="30%" class="text-left">_____________________________</th>
					</tr>
					<tr >
						<th width="35%" class="text-left"><?php echo $_SESSION['abas_login']['fullname']; ?></th>
						<th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
						<th width="30%" class="text-left">Belma A. Hipolito</th>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="float:right; margin-top:0px">
			<img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="25px" align="absmiddle">
			<span style="font-weight:600; color:#000066">aps</span>
			<span style="font-weight:600; color:#000; float:right; margin-right:20px"></span>
		</div>
	</div>
</div>
