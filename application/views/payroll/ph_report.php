<?php
$table	=	"";
foreach($all_employees as $ae) {
	$salary_grade	=	"-";
	$ph['ee']		=	"-";
	$ph['er']		=	"-";
	$ae				=	$this->Abas->getEmployee($ae->id);
	$ph_data		=	$this->Payroll_model->computePH($ae['salary_rate']);
	if($ph_data!=false) {
		$ph['ee']	=	$ph_data['employee'];
		$ph['er']	=	$ph_data['employer'];
	}
	$table	.=	"<tr href=".HTTP_PATH.'payroll/payslips/'.$ae['id']." class='' title='Payslip' style='cursor:pointer;'>";
	// echo "<h1>".$ae['full_name']."</h1>";
	if($ae['employee_status']!="Terminated" && $ae['employee_status']!="Retired" && $ae['employee_status']!="Resigned" && $ae['employee_status']!="Separated") {
		if($ae['salary_grade']!=0) {
			$table	.= "<td style='text-align:center;'>".$ae['employee_id']."</td>";
			$table	.= "<td style='text-align:left;'>".$ae['full_name']."</td>";
			$table	.= "<td style='text-align:center;'>".$ae['ph_num']."</td>";
			$table	.= "<td>".$salary_grade."</td>";
			if(is_numeric($ph['ee']) && is_numeric($ph['er'])) {
				$table	.= "<td>".$ph['er']."</td>";
				$table	.= "<td>".$ph['ee']."</td>";
				$table	.= "<td>".($ph['ee']+$ph['er'])."</td>";
			}
			else {
				$table	.= "<td colspan='3'>-</td>";
			}
		}
		else {
			// $table	.=	"<tr><td colspan=99 style='text-align:center;'>Invalid Salary grade for ".$ae->full_name."(".$ae->employee_id.")!</td></tr>";
		}
	}
	else {
		// $table	.=	"<tr><td colspan=99>".$ae->full_name."(".$ae->employee_id.") Employee status is not eligible for payroll!</td></tr>";
	}
	$table	.=	"</tr>";
}
?>
<?php
$table	=	"";
if(!empty($_SESSION['payroll']['data'])) {
	foreach($_SESSION['payroll']['data'] as $pay) {
		$employee_details	=	$this->Abas->getEmployee($pay['employee_id']);
		$employee_id		=	$employee_details['employee_id'];
		$full_name			=	$employee_details['full_name'];
		$ph_num				=	$employee_details['ph_num'];

		$employee_status	=	strtolower($employee_details['employee_status']);
		if($employee_status == "resigned" || $employee_status == "terminated" || $employee_status == "retired" || $pay['shielded_monthly'] > 0) {
			$ph_record			=	$this->Payroll_model->computePH($pay['shielded_monthly']);
			$table				.=	"<tr>";
				$table			.=	"<td>".$employee_id."</td>";
				$table			.=	"<td>".$full_name."</td>";
				$table			.=	"<td>".$ph_num."</td>";
				$table			.=	"<td>".$this->Abas->currencyFormat($pay['shielded_monthly'])."</td>";
				$table			.=	"<td>".$this->Abas->currencyFormat($ph_record['employer'])."</td>";
				$table			.=	"<td>".$this->Abas->currencyFormat($ph_record['employee'])."</td>";
				$table			.=	"<td>".$this->Abas->currencyFormat($ph_record['employee'] + $ph_record['employer'])."</td>";
			$table				.=	"</tr>";
		}
	}
}
?>
<div class = "panel panel-default" style="border:#999999 thin solid">
	<div class = "panel-heading" style=" background:#CCC; color:#000">
		<h3 class = "panel-title">
			<span>
				<strong>
					<h4>
						<img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">
						<?php echo $_SESSION['payroll']['company_name']; ?>
					</h4>
				</strong>
			</span>
		</h3>
		<span style="float:right; margin-right:10px; margin-top:-50px"><h3>PhilHealth</h3></span>
	</div>
   <div class = "panel-body" style="width:100%">
		<div style="float:left;margin-left;0px"><h4>PhilHealth Contribution Remitance Report</h4></div>
		<div style="float:right; margin-right:10px">
			<h4>
				PAYROLL Period: <?php echo $_SESSION['payroll']['period'].", ".$_SESSION['payroll']['month']." ".$_SESSION['payroll']['year']; ?>
			</h4>
		</div>
		<div style="clear:both;">&nbsp;</div>
		<table class="table table-condensed table-bordered" style="font-size:12px">
			<thead style="background:#000; color:#FFFFFF;">
				<tr>
					<th width="5%" class="text-center">Employee ID</th>
					<th width="10%" class="text-center">Name</th>
					<th width="8%" class="text-center">Policy #</th>
					<th width="5%" class="text-center">Salary</th>
					<th width="5%" class="text-center">Employer</th>
					<th width="5%" class="text-center">Employee</th>
					<th width="5%" class="text-center">Line Total</th>
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
			<table width="100%" cellpadding="1px" cellspacing="5">
				<thead>
					<tr>
						<th width="35%" class="text-left">Prepared by:</th>
						<th width="35%" class="text-left">Checked by:</th>
						<th width="30%" class="text-left">Noted by:</th>
					</tr>
				</thead>
				<tbody >
					<tr >
						<th width="35%" class="text-left">_________________________________</th>
						<th width="35%" class="text-left">_________________________________</th>
						<th width="30%" class="text-left">_________________________________</th>
					</tr>
					<tr>
						<th width="35%" class="text-left"><?php echo $_SESSION['abas_login']['fullname']; ?></th>
						<th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
						<th width="30%" class="text-left">Belma A. Hipolito / Janice D. Suyao</th>
					</tr>
				</tbody>
		</table>
		</div>
		<div style="float:right; margin-top:10px">
			<img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="25px" align="absmiddle">
			<span style="font-weight:600; color:#000066">aps</span>
			<span style="font-weight:600; color:#000; float:right; margin-right:20px"></span>
		</div>
	</div>
</div>
