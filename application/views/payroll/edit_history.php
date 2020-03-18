<?php
$ps	=	$payrollsession;
$e	=	$employeedata;

// $this->Mmm->debug($e);
// $this->Mmm->debug($payrollsession);

$editable['salary']				=	$ps->salary;
$editable['allowance']			=	$ps->allowance;

$editable['absences']			=	$ps->absences_amount;
$editable['ut']					=	$ps->undertime_amount;

$editable['regular_ot']			=	$ps->regular_overtime_amount;
$editable['restday_ot']			=	$ps->restday_overtime_amount;
$editable['legal_holiday_ot']	=	$ps->legalholiday_overtime_amount;
$editable['legal_holiday_on_rest_day_ot']	=	$ps->legalholiday_restday_overtime_amount;
$editable['special_holiday_ot']	=	$ps->specialholiday_overtime_amount;
$editable['special_holiday_on_rest_day_ot']	=	$ps->specialholiday_restday_overtime_amount;

$editable['night_differential']	=	$ps->night_differential_amount;

$editable['bonus']				=	$ps->bonus;
$editable['others']				=	$ps->others;
$editable['withholding']		=	$ps->tax;

$editable['sss_payable']		=	$ps->sss_contri_ee;
$editable['sss_employer']		=	$ps->sss_contri_er;
$editable['ph_payable']			=	$ps->phil_health_contri;
$editable['pi_payable']			=	$ps->pagibig_contri;
$editable['elf_payable']		=	$ps->elf_contri;

$editable['sss_loan']			=	$ps->sss_loan;
$editable['pagibig_loan']		=	$ps->pagibig_loan;
$editable['cash_advance']		=	$ps->cash_advance;

// create the form!
$link			=	HTTP_PATH."payroll_history/edit/".$payroll_detail_id."/update";
$title			=	"Edit Payroll of ".str_replace(",","",str_replace(".","",$e['full_name']));
foreach($editable as $n=>$d) {
	// $this->Mmm->debug($d);
	$caption	=	ucwords(str_replace("_"," ",$n));
	if($n == "ut") $caption	=	ucfirst(str_replace("_"," ","Late/Undertime"));
	if($n == "others") $caption	=	"Adjustments/Others";
	if($n == "elf_payable") $caption	=	"ELF Contribution";
	if($n == "withholding") $caption	=	"Witholding Tax";
	if($n == "sss_payable") $caption	=	"SSS Employee Contribution";
	if($n == "sss_employer") $caption	=	"SSS Employer Contribution";
	if($n == "ph_payable") $caption	=	"PhilHealth Employee Contribution";
	if($n == "pi_payable") $caption	=	"Pag-ibig Employee Contribution";
	if($n == "sss_loan") $caption	=	"SSS Loan Payment Deduction";
	if($n == "pagibig_loan") $caption	=	"Pag-ibig Loan Payment Deduction";
	if($n == "cash_advance") $caption	=	"Cash Advance Payment Deduction";
	$fields[]	=	array("caption"=>$caption, "name"=>$n, "datatype"=>"text", "validation"=>"str", "value"=>$d);
}
// if(isset($ps['paid_loans'])) {
	// foreach($ps['paid_loans'] as $loanid=>$loan_payable_amt) {
		// $loandata	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loanid);
		// $loandata	=	$loandata->result_array();
		// $loandata	=	$loandata[0];
		// $loan_title	=	$loandata['remark']!="" ? $loandata['remark'] : $loandata['id'];
		// $this->Mmm->debug($loandata);
		// $loan_title	=	$loandata['loan_type']." Loan - ".date("j F Y",strtotime($loandata['due_date_loan']));
		// $fields[]	=	array("caption"=>$loan_title, "name"=>"loan".$loanid."", "datatype"=>"text", "validation"=>"str", "value"=>$loan_payable_amt);
	// }
// }
if(isset($ps->paid_loans)) {
	//$this->Mmm->debug($ps->paid_loans);
	foreach($ps->paid_loans as $loanid=>$loan_payment) {
		$loandata	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loanid);
		$loandata	=	$loandata->result_array();
		$loandata	=	$loandata[0];
		// $loan_title	=	$loandata['remark']!="" ? $loandata['remark'] : $loandata['id'];
		//$this->Mmm->debug($loandata);

		// payments
		$total_paid	=	0;
		$payments	=	$this->db->query("SELECT SUM(amount) AS total_paid FROM hr_loan_payments WHERE loan_id=".$loandata['id']);
		if($payments) {
			if($payments->row()) {
				$payments	=	$payments->row();
				$total_paid	=	$payments->total_paid;
			}
		}
		$loan_title	=	$loandata['loan_type']." Loan - P".$this->Abas->currencyFormat($total_paid)." of P".$this->Abas->currencyFormat($loandata['amount_loan']);
		// if($loan_payment['amount'] > 0) {
			$fields[]	=	array("caption"=>$loan_title, "name"=>"loan".$loan_payment['id']."", "datatype"=>"text", "validation"=>"str", "value"=>$loan_payment['amount']);
		// }
	}
}
echo	$this->Mmm->createInput($link,$title,$fields);
?>
