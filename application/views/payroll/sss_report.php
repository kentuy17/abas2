<?php
$table	=	"";
foreach($all_employees as $ae) {
	$salary_grade	=	"-";
	$sss['ee']		=	"-";
	$sss['er']		=	"-";
	if(is_numeric($ae->salary_grade)) {
		$sql_salary_grade	=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$ae->salary_grade);
		if($sql_salary_grade!=false) {
			$salary_grade		=	$sql_salary_grade->row();
			// echo "<pre>";print_r($salary_grade);echo "</pre>";
			if(isset($salary_grade->rate)) {
				$salary_grade		=	$salary_grade->rate;
			}
		}
	}
	$sss_data		=	$this->Payroll_model->computeSSS($salary_grade);
	if($sss_data!=false) {
		$sss['ee']	=	$sss_data->ee;
		$sss['er']	=	$sss_data->er;
	}
	$table	.=	"<tr href=".HTTP_PATH.'payroll/payslips/'.$ae->id." class='' data-toggle='modal' data-target='#modalDialog' title='Payslip' style='cursor:pointer;'>";
	// echo "<h1>".$ae['full_name']."</h1>";
	if($ae->employee_status!="Terminated" && $ae->employee_status!="Retired" && $ae->employee_status!="Separated" && $ae->employee_status!="Resigned") {
		if($ae->salary_grade!=0) {
			$table	.= "<td style='text-align:center;'>".$ae->employee_id."</td>";
			$table	.= "<td style='text-align:left;'>".$ae->full_name."</td>";
			$table	.= "<td style='text-align:center;'>".$ae->sss_num."</td>";
			$table	.= "<td>".$salary_grade."</td>";
			if(is_numeric($sss['ee']) && is_numeric($sss['er'])) {
				$table	.= "<td>".$sss['ee']."</td>";
				$table	.= "<td>".$sss['er']."</td>";
				$table	.= "<td>".($sss['ee']+$sss['er'])."</td>";
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
		$sss_num			=	$employee_details['sss_num'];
		$employee_status	=	strtolower($employee_details['employee_status']);
		if($employee_status == "resigned" || $employee_status == "terminated" || $employee_status == "separated" || $employee_status == "retired" || $pay['shielded_monthly'] > 0) {
			$sss_record		=	$this->Payroll_model->computeSSS($pay['shielded_monthly']);
			if($sss_record != false) {
				$table				.=	"<tr>";
					$table			.=	"<td>".$employee_id."</td>";
					$table			.=	"<td>".$full_name."</td>";
					$table			.=	"<td>".$sss_num."</td>";
					$table			.=	"<td>".$this->Abas->currencyFormat($pay['shielded_monthly'])."</td>";
					$table			.=	"<td>".$sss_record->er."</td>";
					$table			.=	"<td>".$sss_record->ee."</td>";
					$table			.=	"<td>".$sss_record->total."</td>";
				$table				.=	"</tr>";
			}
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
						<img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   <?php echo $_SESSION['payroll']['company_name']; ?>
					</h4>
				</strong>
			</span>
		</h3>
		<span style="float:right; margin-right:10px; margin-top:-50px"><h3>SSS</h3></span>
	</div>
	<div class = "panel-body">
	<div style="float:left;margin-left;0px"><h4>SSS Contribution Remittance Report</h4></div>
	<div style="float:right; margin-right:10px"><h4>PAYROLL Period: <?php echo $_SESSION['payroll']['period'].", ".$_SESSION['payroll']['month']." ".$_SESSION['payroll']['year']; ?></h4></div>
	<div style="clear:both;">&nbsp;</div>
	<table class="table table-condensed table-bordered" style="font-size:12px">
		<thead style="background:#000; color:#FFFFFF;" >
			<tr>
				<th width="5%" class="text-center">Employee ID</th>
				<th width="10%" class="text-center">Name</th>
				<th width="8%" class="text-center">SSS #</th>
				<th width="5%" class="text-center">Salary</th>
				<th width="5%" class="text-center">Employer</th>
				<th width="5%" class="text-center">Employee</th>
				<th width="5%" class="text-center">Total</th>
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
				<thead>
					<tr>
						<th width="35%" class="text-left">Prepared by:</th>
						<th width="35%" class="text-left">Checked by:</th>
						<th width="30%" class="text-left">Noted by:</th>
					</tr>
				</thead>
				<tbody>
					<tr>
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