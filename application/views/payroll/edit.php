<?php
$ps	=	$payrollsession;
$e	=	$this->Abas->getEmployee($ps['employee_id']);

//$this->Mmm->debug($e);
//$this->Mmm->debug($payrollsession);

//$editable['salary']				=	$e['salary_rate']/2;
$editable['allowance']			=	$ps['allowance'];
$editable['absences']			=	$ps['absences_amount'];
$editable['ut']					=	$ps['ut'];
$editable['regular_ot']			=	$ps['ot']['regular'];
$editable['restday_ot']			=	$ps['ot']['restday'];
$editable['legal_holiday_ot']	=	$ps['ot']['legal_holiday'];
$editable['legal_holiday_on_rest_day_ot']	=	$ps['ot']['legal_holiday_restday'];
$editable['special_holiday_ot']	=	$ps['ot']['special_holiday'];
$editable['special_holiday_on_rest_day_ot']	=	$ps['ot']['special_holiday_restday'];
$editable['night_differential']	=	$ps['nd'];
$editable['bonus']				=	$ps['bonus'];
$editable['others']				=	$ps['others'];
$editable['withholding']		=	$ps['withholding'];
$editable['elf_payable']		=	$ps['elf']['payable'];
$editable['sss_payable']		=	$ps['sss']['payable'];
$editable['ph_payable']			=	$ps['ph']['payable'];
$editable['pi_payable']			=	$ps['pi']['payable'];

// create the form!
$link			=	HTTP_PATH."payroll/edit/".$session_id."/update";
$title			=	"Edit payroll for ".str_replace(",","",str_replace(".","",$e['full_name']));
foreach($editable as $n=>$d) {
	// $this->Mmm->debug($d);
	$caption	=	ucfirst(str_replace("_"," ",$n));
	if($n == "ut") $caption	=	ucfirst(str_replace("_"," ","Undertime"));
	if($n == "others") $caption	=	"Adjustments/Others";
	$fields[]	=	array("caption"=>$caption, "name"=>$n, "datatype"=>"text", "validation"=>"str", "value"=>$d);
}
if(isset($ps['paid_loans'])) {
	foreach($ps['paid_loans'] as $loanid=>$loan_payable_amt) {
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

		$loan_title	=	$loandata['loan_type']." Loan - P".$this->Abas->currencyFormat($total_paid)." of P".$this->Abas->currencyFormat((str_replace(",","",$loandata['amount_loan'])));
		// if($loan_payable_amt > 0) {
			$fields[]	=	array("caption"=>$loan_title, "name"=>"loan".$loanid."", "datatype"=>"text", "validation"=>"str", "value"=>$loan_payable_amt);
		// }
	}
}
echo	$this->Mmm->createInput($link,$title,$fields);
?>
