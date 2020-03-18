<?php
	/*
	if(!isset($employees)) { header("location:".HTTP_PATH."accounting");die("<script>window.location='".HTTP_PATH."accounting'</script>"); }
	if(empty($employees)) { header("location:".HTTP_PATH."accounting");die("<script>window.location='".HTTP_PATH."accounting'</script>"); }
	$employeetable	=	"<table class='table table-striped table-bordered'><tr><th>Name</th><th>Position</th><th>Manage</th></tr>";
	foreach($employees as $e) {
		$e	=	$this->Abas->getEmployee($e['id']);
		$loans	=	$this->Payroll_model->getAllLoans($e['id']);
		if($loantype != "All") {
			$loans	=	$this->db->query("SELECT * FROM hr_loans WHERE emp_id=".$e['id']." AND loan_type LIKE '".$loantype."'");
			$loans	=	$loans->result_array();
		}
		if(!empty($loans)) {
			$employeetable	.=	"<tr>";
			$employeetable	.=	"<td>".$e['full_name']."</td>";
			$employeetable	.=	"<td>".$e['position_name']."</td>";
			$employeetable	.=	"<td><a class='btn btn-info btn-xs' onclick='javascript: toggleHide(".$e['id'].")'><span class='glyphicon glyphicon-th-list'></span> View Loans</a></td>";
			$employeetable	.=	"</tr>";
			$loantable	=	"<table class='table table-bordered table-striped'><tr><th>Type</th><th>Date Loaned</th><th>Due Date</th><th>Monthly Amortization</th><th>Amount</th><th>Remaining Balance</th><th>Remark</th></tr>";
			foreach($loans as $l) {
				// $this->Mmm->debug($l);
				$payment	=	$this->Payroll_model->computeLoanPayments($l['id']);
				$remaining_balance	=	$l['amount_loan'] - $payment;
				// if($remaining_balance > 0) {
					$loantable	.=	"<tr>";
						$loantable	.=	"<td>".($l['loan_type'])."</td>";
						$loantable	.=	"<td>".(($l['date_loan']=="1970-01-01 00:00:00")?"":date("j F Y",strtotime($l['date_loan'])))."</td>";
						$loantable	.=	"<td>".(($l['due_date_loan']=="1970-01-01 00:00:00")?"":date("j F Y",strtotime($l['due_date_loan'])))."</td>";
						$loantable	.=	"<td>".$l['amount_loan']."</td>";
						$loantable	.=	"<td>".$l['monthly_amortization']."</td>";
						$loantable	.=	"<td>".($remaining_balance)."</td>";
						$loantable	.=	"<td>".$l['remark']."</td>";
					$loantable	.=	"</tr>";
				// }
			}
			$loantable		.=	"</table>";
			$employeetable	.=	"<tr class='loan".$e['id']." hide'><td colspan='99'>".$loantable."</td></tr>";
		}
	}
	$employeetable		.=	"";
	*/
	$loantable	=	"<table class='table table-striped table-bordered'>";
	$loantable	.=	"<tr>";
	$loantable	.=	"<th>Name</th>";
	$loantable	.=	"<th>Position</th>";
	if($company=="") { $loantable	.=	"<th>Company</th>"; }
	$loantable	.=	"<th>Date Loaned</th>";
	$loantable	.=	"<th>Amount Loaned</th>";
	$loantable	.=	"<th>Amount Paid</th>";
	$loantable	.=	"<th>Remaining Balance</th>";
	if($loantype!="") { $loantable	.=	"<th>Loan Type</th>"; }
	// $loantable	.=	"<th>Manage</th>";
	$loantable	.=	"</tr>";
	if(!empty($loans)) {
		foreach($loans as $lctr=>$l) {
			$loantable	.=	"<tr>";
			$loantable	.=	"<td>".$l['employee']['full_name']."</td>";
			$loantable	.=	"<td>".$l['employee']['position_name']."</td>";
			if($company=="") { $loantable	.=	"<td>".$l['employee']['company_name']."</td>"; }
			// $loantable	.=	"<td>".($l['amount_loan'])."</td>";
			$loantable	.=	"<td>".date("j F Y", strtotime($l['date_loan']))."</td>";
			$loantable	.=	"<td>".number_format((float)$l['amount_loan'],2)."</td>";
			$loantable	.=	"<td>".number_format($l['amount_paid'],2)."</td>";
			$loantable	.=	"<td>".number_format(($l['amount_loan']-$l['amount_paid']),2)."</td>";
			if($loantype!="") $loantable	.=	"<td>".$l['loan_type']."</td>";
			$loantable	.=	"</tr>";
		}
	}
	else {
		$loantable	.=	"<tr><td colspan=99>No loans found!</td></tr>";
	}
	$loantable		.=	"</table>";

?>
<div class="container">
	<h3><?php //echo $company['name']." - ".$loantype." loans"; ?></h3>
	<div class='table-responsive'>
			<?php echo $loantable; ?>
		</table>
	</div>
</div>
<script>
function toggleHide(e) {
	$(".loan"+e).toggleClass("hide");
}
</script>