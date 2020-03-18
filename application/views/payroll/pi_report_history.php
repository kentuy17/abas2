<?php
$company	=	$this->Abas->getCompany($summary->company_id);
$table	=	"";
foreach($all_employees as $ae) {
	// $taxable		=	$this->Payroll_model->getTaxable($ae->salary*2);
	$employee_data	=	$this->Abas->getEmployee($ae->emp_id);
	// echo "<pre>";print_r($ae->salary);echo "</pre>";
	$table	.=	"<tr>";
	// echo "<h1>".$ae['full_name']."</h1>";
	if($employee_data['salary_rate']!=0 && $employee_data['pagibig_num']!="") {
		$table	.= "<td style='text-align:center;'>".$employee_data['employee_id']."</td>";
		$table	.= "<td style='text-align:left;'>".$employee_data['full_name']."</td>";
		$table	.= "<td style='text-align:center;'".$employee_data['pagibig_num']."</td>";
		$table	.= "<td>".$ae->pagibig_contri."</td>";
	}
	$table	.=	"</tr>";
}
?>
<div class = "panel panel-default" style="border:#999999 thin solid">
	<div class = "panel-heading" style=" background:#CCC; color:#000">
		<h3 class = "panel-title">
			<span>
				<strong>
					<h4>
						<img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   <?php echo $company->name; ?>
					</h4>
				</strong>
			</span>
		</h3>
		<span style="float:right; margin-right:10px; margin-top:-50px"><h3>Pag-Ibig</h3><a href="<?php echo HTTP_PATH."payroll_history/pi_printable/".$payroll_id; ?>" target="_new">Print</a></span>
   </div>

   <div class = "panel-body" style="width:100%">
		<div style="float:left;margin-left;0px"><h4>PagIbig Contribution Remitance Report</h4></div>
		<div style="float:right; margin-right:10px"><h4>PAYROLL Period: <?php echo $summary->payroll_coverage.", ".date("F Y",strtotime($summary->payroll_date."-01")); ?></h4></div>
		<div style="clear:both;">&nbsp;</div>
		<table class="table table-condensed table-bordered" style="font-size:12px">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr>
					<th width="5%" class="text-center">Employee ID</th>
					<th width="10%" class="text-center">Name</th>
					<th width="8%" class="text-center">PagIbig #</th>
					<th width="5%" class="text-right">Contribution</th>
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
						<th width="35%" class="text-left">_____________________________</th>
						<th width="35%" class="text-left">_____________________________</th>
						<th width="30%" class="text-left">_____________________________</th>
					</tr>
					<tr>
						<th width="35%" class="text-left"><?php echo $_SESSION['abas_login']['fullname']; ?></th>
						<th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
						<th width="30%" class="text-left">Belma A. Hipolito</th>
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
