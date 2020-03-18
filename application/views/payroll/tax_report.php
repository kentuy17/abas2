<?php
$table	=	"";
if(!empty($_SESSION['payroll']['data'])) {
	foreach($_SESSION['payroll']['data'] as $pay) {
		$employee_details	=	$this->Abas->getEmployee($pay['employee_id']);
		$employee_id		=	$employee_details['employee_id'];
		$full_name			=	$employee_details['full_name'];
		$tin_num			=	$employee_details['tin_num'];
		$tax_code			=	$employee_details['tax_code'];
		$employee_status	=	strtolower($employee_details['employee_status']);
		if($employee_status == "resigned" || $employee_status == "terminated" || $employee_status == "retired" || $pay['shielded_monthly'] > 0) {
			$table				.=	"<tr>";
				$table			.=	"<td>".$employee_id."</td>";
				$table			.=	"<td>".$full_name."</td>";
				$table			.=	"<td>".$tin_num."</td>";
				// $table			.=	"<td>".$tax_code."</td>";
				// $table			.=	"<td>".$this->Abas->currencyFormat($pay['shielded_monthly']/2)."</td>";
				$table			.=	"<td>".$this->Abas->currencyFormat($pay['withholding'])."</td>";
			$table				.=	"</tr>";
		}
	}
}
?>
<div class = "panel panel-default">
	<div class = "panel-heading" style=" background:#CCC; color:#000">
		<h3 class = "panel-title">
			<span><strong><h4><img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   <?php echo $_SESSION['payroll']['company_name']; ?></h4></strong></span>
			<span style="float:right; margin-right:10px; margin-top:-50px"><h3>BIR</h3></span>
		</h3>
	</div>

	<div class = "panel-body" style="width:100%">
		<div style="float:left;margin-left;0px">
			<h4>TAX Remittance Report</h4>
		</div>
		<div style="float:right; margin-right:10px">
			<h4>PAYROLL Period: <?php echo $_SESSION['payroll']['period'].", ".$_SESSION['payroll']['month']." ".$_SESSION['payroll']['year']; ?></h4>
		</div>
		<div style="clear:both;">&nbsp;</div>
		<table class="table table-condensed table-bordered" style="font-size:12px">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr>
					<th width="5%" class="text-center">Employee ID</th>
					<th width="10%" class="text-center">Name</th>
					<th width="8%" class="text-center">TIN</th>
					<!--th width="5%" class="text-center">Tax Code</th>
					<th width="5%" class="text-center">Salary</th-->
					<th width="5%" class="text-center">Withholding Tax</th>
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
						<th width="35%" class="text-left">_________________________________</th>
						<th width="35%" class="text-left">_________________________________</th>
						<th width="30%" class="text-left">_________________________________</th>
					</tr>
					<tr >
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