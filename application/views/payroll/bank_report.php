<?php
$table	=	"";
if(!empty($_SESSION['payroll']['data'])) {
	foreach($_SESSION['payroll']['data'] as $pay) {
		$this->Mmm->debug($pay);
		$employee_details	=	$this->Abas->getEmployee($pay['employee_id']);
		$employee_id		=	$employee_details['employee_id'];
		$full_name			=	$employee_details['full_name'];
		$bank_num			=	$employee_details['bank_account_num'];
		if($bank_num == "" && $employee_details['vessel_id'] < 99990) {
			$vessel_bank_num=	$this->db->query("SELECT id, bank_account_num FROM vessels WHERE id=".$employee_details['vessel_id']);
			if($vessel_bank_num) {
				if($vessel_bank_num->row()) {
					$vessel_bank_num	=	$vessel_bank_num->row();
					$bank_num	=	$vessel_bank_num->bank_account_num;
				}
			}
		}
		$tax_code			=	$employee_details['tax_code'];
		$employee_status	=	strtolower($employee_details['employee_status']);
		if($employee_status == "resigned" || $employee_status == "terminated" || $employee_status == "retired" || $employee_status == "separated" || $pay['shielded_monthly'] > 0) {
			$table				.=	"<tr>";
				$table			.=	"<td>".$employee_id."</td>";
				$table			.=	"<td>".$full_name."</td>";
				$table			.=	"<td>".$bank_num."</td>";
				// $table			.=	"<td>".$tax_code."</td>";
				// $table			.=	"<td>".$this->Abas->currencyFormat($pay['shielded_monthly']/2)."</td>";
				$table			.=	"<td>".$this->Abas->currencyFormat($pay['net_pay'] - $pay['elf']['loan'] - $pay['elf']['payable'])."</td>";
			$table				.=	"</tr>";
		}
	}
}
?>
<div class = "panel panel-default">
	<div class = "panel-heading" style=" background:#CCC; color:#000">
		<h3 class = "panel-title">
			<span><strong><h4><img src="<?php echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   <?php echo $_SESSION['payroll']['company_name']; ?></h4></strong></span>
			<span style="float:right; margin-right:10px; margin-top:-50px"><h3>Bank Report</h3></span>
		</h3>
	</div>

	<div class = "panel-body" style="width:100%">
		<div style="float:left;margin-left;0px">
			<h4>Bank Report</h4>
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
					<th width="8%" class="text-center">Account Number</th>
					<!--th width="5%" class="text-center">Tax Code</th>
					<th width="5%" class="text-center">Salary</th-->
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