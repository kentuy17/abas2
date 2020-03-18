<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

##########################################################################
##########################################################################
#######################                         ##########################
#######################  AVEGA BROS INTEGRATED  ##########################
#######################  	it@avegabros.com 	##########################
#######################           -             ##########################
#######################    October 2015         ##########################
#######################           -             ##########################
#######################    Payroll Model        ##########################
#######################                         ##########################
#######################        sorry            ##########################
#######################                         ##########################
##########################################################################
##########################################################################


class Payroll_model extends CI_Model{
	public function __construct() {
		// $this->load->database();
		$this->load->model("Abas");
	}
	public function getSalaryGrade($userid) {
		$ret		=	null;
		$employee	=	$this->Abas->getEmployee($userid);
		if($employee!=false) {
			if($employee['salary_rate']!=0) {
				$salaryrate		=	$employee['salary_rate'];
				$taxable		=	$this->getTaxable($salaryrate);
			}
			$sql	=	 "SELECT * FROM tax_codes WHERE tax_code='".$employee['tax_code']."' AND `from_sal`<=".($taxable['per_cutoff'])." AND `to_sal`>".($taxable['per_cutoff']." LIMIT 1");
			$taxcode=	$this->db->query($sql);
			if($taxcode) {
				$taxcode			=	$taxcode->row();
				$ret['taxcode']		=	$taxcode;
			}
			else {
				$ret['error']	=	"salgrade_missing";
				$ret['id']		=	$employee['id'];
				$ret['emp_id']	=	$employee['employee_id'];
			}
		}
		else {
			$ret['error']	=	"employee_404";
		}
		return $ret;
	}
	public function getTaxRecord($taxcode, $monthly) {
		$record		=	null;
		if($taxcode!="") {
			if(is_numeric($monthly)) {
				if($monthly>0) {
					$sql		=	"SELECT * FROM tax_codes WHERE tax_code='".$taxcode."' AND (from_sal<".$monthly." AND to_sal>=".$monthly.")";
					$record		=	$this->db->query($sql);
					if($record) {
						if($record->row()) {
							$record		=	$record->row();
						}
						else {
							$this->Abas->sysMsg("errmsg","Tax Record not found! (". __CLASS__ . __LINE__ .")");
						}
					}
					else {
						$this->Abas->sysMsg("errmsg","Tax Record not found! (". __CLASS__ . __LINE__ .")");
					}
				}
				else {
					// $this->Abas->sysMsg("errmsg","Invalid salary grade found! (". __CLASS__ . __LINE__ .")");
				}
			}
			else {
				// $this->Abas->sysMsg("errmsg","Monthly is not a number! (". __CLASS__ . __LINE__ .")");
			}
		}
		else {
			// $this->Abas->sysMsg("errmsg","Tax code is not set! (". __CLASS__ . __LINE__ .")");
		}
		// echo "<pre>".$taxcode."|".$monthly;print_r($record);echo "</pre>";
		return $record;
	}
	public function getOT($userid) {
		$employee_details	=	$this->Abas->getEmployee($userid);
		$ret				=	false;
		if($employee_details!=false) {
			$overtimes			=	$this->db->query("SELECT * FROM hr_overtime WHERE employee_id=".$userid);
			if($overtimes!=false) {
				if($overtimes->row()) {
					$rates['monthly']	=	$employee_details['salary_rate'];
					$rates['per_cutoff']=	$rates['monthly'] / 2;
					$rates['daily']		=	$rates['monthly'] / 26.08; //as per DOLE
					$rates['hourly']	=	$rates['daily']/8; // 8 working hours
					$rates['per_min']	=	$rates['hourly'] / 60;

					$ots				=	$overtimes->result_array();

					$ret				=	array("rates"=>$rates, "overtimes"=>$ots);
				}
			}
		}
		return $ret;
	}
	public function computeOT($userid) {
		$employee_details	=	$this->Abas->getEmployee($userid);
		$ret				=	false;
		if($employee_details!=false) {
			$overtimes			=	$this->db->query("SELECT * FROM hr_overtime WHERE employee_id=".$userid." AND computed=0 AND stat=1");
			if($overtimes!=false) {
				if($overtimes->row()) {
					$ret				=	$overtimes->result_array();
				}
			}
		}
		return $ret;
	}
	public  function computeND($userid){
		$employee_details	=	$this->Abas->getEmployee($userid);
		$ret				=	false;
		if($employee_details!=false) {
			$night_diff			=	$this->db->query("SELECT * FROM hr_night_differential WHERE employee_id=".$userid." AND is_computed=0 AND stat=1");
			if($night_diff!=false) {
				if($night_diff->row()) {
					$ret				=	$night_diff->result_array();
				}
			}
		}
		return $ret;
	}
	public function computeUT($userid) {
		$employee_details	=	$this->Abas->getEmployee($userid);
		$ret				=	false;
		if($employee_details!=false) {
			$undertimes			=	$this->db->query("SELECT * FROM hr_undertime WHERE employee_id=".$userid." AND computed=0 AND stat=1");
			if($undertimes!=false) {
				if($undertimes->row()) {
					$ret				=	$undertimes->result_array();
				}
			}
		}
		return $ret;
	}
	public function getLoan($loanid) {
		$ret		=	false;
		$loans	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loanid);
		if($loans!=false) {
			if($loans->row()) {
				$ret	=	(array)$loans->row();
			}
		}
		return $ret;
	}
	public function getAllLoans($userid) {
		$employee	=	$this->Abas->getEmployee($userid);
		$ret		=	false;
		if($employee!=false) {
			$loans	=	$this->db->query("SELECT * FROM hr_loans WHERE emp_id=".$userid." ORDER BY id DESC");
			if($loans!=false) {
				if($loans->row()) {
					$ret	=	$loans->result_array();
				}
			}
		}
		return $ret;
	}
	public function computeLoanPayments($loan_id) {
		$amt_paid	=	null;
		$loan		=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loan_id);
		if($loan) {
			if($loan->row()) {
				$amt_paid	=	0;
				$loan		=	$loan->result_array();
				$payments	=	$this->db->query("SELECT * FROM hr_loan_payments WHERE loan_id=".$loan_id);
				if($payments) {
					if($payments->row()) {
						$payments	=	$payments->result_array();
						foreach($payments as $p) {
							$amt_paid	=	$amt_paid + $p['amount'];
						}
					}
				}
			}
		}
		return $amt_paid;
	}
	public function payLoan($loan_id, $amount) {
		/*
		 *
		 * if loan does not exist: return false
		 * if payment > loan: return 0
		 * if payment < loan: return remaining balance
		 * if payment = loan: return true
		 *
		 */
		$result	=	false;
		$loan_details	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loan_id);
		if($loan_details != false) {
			if($loan_details->row()) { // loan exists
				$loan_details		=	$loan_details->row();
				// get payments
				$payments	=	$this->Payroll_model->computeLoanPayments($loan_id);
				if($payments > 0) {
					$loan_balance	=	$loan_details->amount_loan - $payments;
					if($amount > $loan_balance) {
						$result	=	0;
					}
					else {
						$result	=	$loan_balance - $amount;
						if($result==0) $result=true;
					}
				}
			}
		}
		return $result;
	}
	public function getSummary($ae) { //used to create row in payroll/summary
		$table			=	"";
		$employee_details	=	$this->Abas->getEmployee($ae->id);
		// echo "<pre>";print_r($employee_details);echo "</pre>";
		$position		=	"-";
		$salary_grade	=	"-";
		$table	.=	"<tr href=".HTTP_PATH.'payroll/payslips/'.$ae->id." class='' data-toggle='modal' data-target='#modalDialog' title='Payslip' style='cursor:pointer;'>";
		if($ae->employee_status!="Terminated" && $ae->employee_status!="Retired" && $ae->employee_status!="Resigned") {
			if($employee_details['salary_rate']!=0) {
				$employee_id	=	$employee_details['employee_id'];
				$full_name		=	$employee_details['full_name'];
				$position		=	$employee_details['position_name'];
				$salary			=	$employee_details['salary_rate']; // before tax shield
				// $disp_salary			=	$this->getTaxable($salary); // after tax shield
				$disp_salary['monthly']=$salary;
				$disp_salary['per_cutoff']=$salary/2;
				$gross_pay		=	$salary/2;
				$taxcode		=	$employee_details['tax_code'];

				$deductions		=	0;
				$ot_income		=	0;
				$display_loan	=	$this->Abas->currencyFormat(0);
				$ot				=	$this->Payroll_model->getOT($ae->id);
				// $this->Mmm->debug($ot);
				if(isset($ot['overtimes'])) {
					foreach($ot['overtimes'] as $ots) {
						if($ots['approved']!=0) {
							$computed_ot=	($ots['ot_time'] * $ot['rates']['hourly']);
							$computed_ot=	($computed_ot + ( $computed_ot * ($ots['rate']/100) ));
							$ot_income	=	$ot_income + $computed_ot;
						}
					}
				}
				$loans			=	$this->Payroll_model->getAllLoans($ae->id);
				// $this->Mmm->debug($loans);
				$total_loan['payable']=0;
				if($loans!=false) {
					$loan_per_type['ELF']		=	array("balance"=>0, "payable"=>0, "monthly"=>0);
					$loan_per_type['SSS']		=	array("balance"=>0, "payable"=>0, "monthly"=>0);
					$loan_per_type['PagIbig']	=	array("balance"=>0, "payable"=>0, "monthly"=>0);
					foreach($loans as $loan) {
						$payments	=	$this->db->query("SELECT * FROM hr_loans_payments WHERE loan_id=".$loan['id']." AND stat=1 ORDER BY id DESC");
						$principal			=	$loan['amount_loan'];
						$monthly_payment	=	$loan['monthly_amortization'];
						$per_cutoff_payment	=	$monthly_payment / 2;
						$loan_balance		=	$principal;
						$loan_per_type[$loan['loan_type']]['balance']		=	$loan_per_type[$loan['loan_type']]['balance']+$principal;
						$loan_per_type[$loan['loan_type']]['payable']		=	$loan_per_type[$loan['loan_type']]['payable']+$per_cutoff_payment;
						if($payments!=false) {
							if($payments->row()) {
								$payments	=	$payments->result_array();
								foreach($payments as $payment) {
									$loan_balance	=	$loan_balance - $payment['amount'];
								}
							}
						}

					}
					$total_loan['balance']	=	0;
					$total_loan['payable']	=	0;
					foreach($loan_per_type as $lpt) {
						$total_loan['balance']	=	$total_loan['balance']+$lpt['balance'];
						$total_loan['payable']	=	$total_loan['payable']+$lpt['payable'];
					}
					$display_loan		=	$this->Abas->currencyFormat($total_loan['payable']);
				}

				$total_income	=	$gross_pay+$ot_income-$total_loan['payable']; // add allowances and others

				$table	.= "<td>".$employee_id."</td>";
				$table	.= "<td>".$full_name."</td>";
				$table	.= "<td class='position-cell'>".ucwords(strtolower($position))."</td>";
				$table	.= "<td>".$this->Abas->currencyFormat($disp_salary['per_cutoff'])."</td>"; // after tax shield (display only)
				$table	.= "<td></td>"; //allowance
				$table	.= "<td>".$this->Abas->currencyFormat($ot_income)."</td>"; //others
				$table	.= "<td>".$this->Abas->currencyFormat($total_income)."</td>";
				if($taxcode!="") {
					$withholding	=	$this->Payroll_model->getTax($ae->id);
					// echo "<pre>";print_r($withholding);echo "</pre>";
					$table	.= "<td>".$taxcode."</td>";
					$table	.= "<td>".$this->Abas->currencyFormat($withholding['per_cutoff']['tax_payable'])."</td>";
					$deductions	=	$deductions-$withholding['per_cutoff']['tax_payable'];
				}
				else {
					$table	.= "<td>-</td>";
				}
				if($gross_pay > 0) {
					if($ae->sss_num!="") {
						$sss_contri	=	$this->Payroll_model->computeSSS($gross_pay);
						// echo "<pre>";print_r($sss_contri);echo "</pre>";
						if(isset($sss_contri->ee)) {
							$table	.=	"<td>".$this->Abas->currencyFormat($sss_contri->ee)."</td>";
							$deductions	=	$deductions-$sss_contri->ee;
						}
						else {
							$table	.=	"<td>0</td>";
						}
					}
					else {
						$table	.=	"<td>No SSS#</td>";
					}
					if($ae->ph_num!="") {
						$ph_contri	=	$this->Payroll_model->computePH($gross_pay);
						$table	.=	"<td>".$this->Abas->currencyFormat($ph_contri->employee)."</td>";
						$deductions	=	$deductions-$ph_contri->employee;
					}
					else {
						$table	.=	"<td>No PH#</td>";
					}
				}
				else {
					$this->Abas->sysMsg('warnmsg', 'Invalid salary grade for '.$employee_details['full_name'].' ('.$employee_details['employee_id'].')!');
				}
				if($ae->pagibig_num!="") {
					$pi_contri	=	$this->Payroll_model->computePI($ae->id);
					$table	.=	"<td>".$this->Abas->currencyFormat($pi_contri['contribution'])."</td>";
					$deductions	=	$deductions-$pi_contri['contribution'];
				}
				else {
					$table	.=	"<td>No PI#</td>";
				}
				if($ae->elf_rate!="") {
					$elf_contri	=	$this->Payroll_model->computeELF($ae->id);
					$table	.=	"<td>".$this->Abas->currencyFormat($elf_contri['per_cutoff'])."</td>";
					$deductions	=	$deductions-$elf_contri['per_cutoff'];
				}
				else {
					$table	.=	"<td>No ELF Rate</td>";
				}
				$table	.=	"<td>".$display_loan."</td>"; //loan
				$net_pay	=	$total_income+$deductions;
				$table	.=	"<td>".$this->Abas->currencyFormat($net_pay)."</td>";
			}
			else {
				// $table	.=	"<tr><td colspan=99 style='text-align:center;'>Invalid Salary grade for ".$ae->full_name."(".$ae->employee_id.")!</td></tr>";
			}
		}
		else {
			// $table	.=	"<tr><td colspan=99>".$ae->full_name."(".$ae->employee_id.") Employee status is not eligible for payroll!</td></tr>";
		}
		$table	.=	"</tr>";
		return $table;
	}
	public function getNetPay($ae) {
		// echo "<pre>";print_r($ae);echo "</pre>";
		$employee_details	=	$this->Abas->getEmployee($ae->id);
		$position			=	$employee_details['position_name'];
		$company			=	$employee_details['company_name'];
		$department			=	$employee_details['department_name'];
		$salgrade			=	$employee_details['salary_grade'];
		$salrate			=	$employee_details['salary_rate'];


		if(is_numeric($salrate) && !empty($salgrade)) {
			$salary_per_cutoff				=	($salrate / 2);
			$withholding	=	$this->Payroll_model->getTax($ae->id);
			$sss_contri		=	$this->Payroll_model->computeSSS($salrate);
			$ph_contri		=	$this->Payroll_model->computePH($salrate);
			$pi_contri		=	$this->Payroll_model->computePI($ae->id);
			$elf_contri		=	$this->Payroll_model->computeELF($ae->id);
			if(isset($sss_contri->ee, $ph_contri->employee)) {
				$total_deduction=	($withholding['per_cutoff']['tax_payable']+$sss_contri->ee+$ph_contri->employee+$pi_contri['contribution']+$elf_contri['per_cutoff']);
				$total_income	=	($salary_per_cutoff); // add allowances and others
			}
			else {
				$total_deduction=	"0";
				$total_income	=	"0";
			}
			return ($total_income-$total_deduction);
		}
		else {
			return 'Invalid Salary grade for '.$ae->full_name." (".$ae->employee_id.")";
		}
	}
	public function saveSummary($ae) {
		$table			=	"";
		$position		=	"-";
		$salary_grade	=	"-";
		if(is_numeric($ae->position)) {
			$sql_position	=	$this->db->query("SELECT * FROM positions WHERE id=".$ae->position);
			if($sql_position!=false) {
				$position		=	$sql_position->row();
				if(isset($position->name)) {
					$position		=	$position->name;
				}
			}
		}
		if(is_numeric($ae->salary_grade)) {
			$sql_salary_grade	=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$ae->salary_grade);
			if($sql_salary_grade!=false) {
				$salary_grade		=	$sql_salary_grade->row();
				if(isset($salary_grade->rate)) {
					$salary_grade		=	$salary_grade->rate;
				}
			}
		}
		if($ae->employee_status!="Terminated" && $ae->employee_status!="Retired" && $ae->employee_status!="Resigned") {
			if($ae->salary_grade!=0) {
				if($ae->tax_code!="") {
					$withholding	=	$this->Payroll_model->getTax($ae->id);
					$withholding	=	$withholding['per_cutoff']['tax_payable'];
				}
				else {
					$table	.= "<td>-</td>";
				}
				if($ae->sss_num!="") {
					$sss_contri	=	$this->Payroll_model->computeSSS($salary_grade['monthly']);
					$sss_contri	=	$sss_contri->ee;
				}
				else {
					$table	.=	"<td>No SSS#</td>";
				}
				if($ae->ph_num!="") {
					$ph_contri	=	$this->Payroll_model->computePH($salary_grade);
					$ph_contri	=	$ph_contri->employee;
				}
				else {
					$table	.=	"<td>No PH#</td>";
				}
				if($ae->pagibig_num!="") {
					$pi_contri	=	$this->Payroll_model->computePI($ae->id);
					$pi_contri	=	$pi_contri['contribution'];
				}
				else {
					$table	.=	"<td>No PI#</td>";
				}
				if($ae->elf_rate!="") {
					$elf_contri	=	$this->Payroll_model->computeELF($ae->id);
					$elf_contri	=	$elf_contri['per_cutoff'];
				}
				else {
					$table	.=	"<td>No ELF Rate</td>";
				}
			}
			else {
				$table	.=	"<tr><td colspan=99 style='text-align:center;'>Invalid Salary grade for ".$ae->full_name."(".$ae->employee_id.")!</td></tr>";
			}
		}
		else {
			$table	.=	"<tr><td colspan=99>".$ae->full_name."(".$ae->employee_id.") Employee status is not eligible for payroll!</td></tr>";
		}
		$table	.=	"</tr>";
		return $table;
	}
	public function getRates($salary_rate, $vessel_id) { // returns array with indices 'monthly', 'daily', 'hourly' and 'per_min'
		$rates				=	array();
		$daily_rate_multiplier	=	$vessel_id > 99990 ? 26.08 : 30; // 26.08(as per DOLE) for office employees, 30 for vessel employees
		$shielded			=	$salary_rate;
		if(APPLY_TAXSHIELD == true) {
			if($vessel_id==99999) { // makati office
				if($salary_rate > 14000) {
					$shielded	=	$salary_rate - ($salary_rate * 0.2);
				}
				elseif($salary_rate <= 14000 && $salary_rate > MAKATI_MONTHLY_MINIMUM_WAGE) {
					$shielded	=	$salary_rate - ($salary_rate * 0.1);
				}
				elseif($salary_rate <= MAKATI_MONTHLY_MINIMUM_WAGE) {
					$shielded	=	$salary_rate;
				}
			}
			elseif($vessel_id==99998 || $vessel_id==99996 || $vessel_id==99995 || $vessel_id==99994) { // cebu office, tayud office, trucking and maintenance crew
				if($salary_rate > 14000) {
					$shielded	=	$salary_rate - ($salary_rate * 0.2);
				}
				elseif($salary_rate <= 14000 && $salary_rate > CEBU_MONTHLY_MINIMUM_WAGE) {
					$shielded	=	$salary_rate - ($salary_rate * 0.1);
				}
				elseif($salary_rate <= CEBU_MONTHLY_MINIMUM_WAGE) {
					$shielded	=	$salary_rate;
				}
			}
			elseif($vessel_id==99997) { // tacloban office
				if($salary_rate > 14000) {
					$shielded	=	$salary_rate - ($salary_rate * 0.2);
				}
				elseif($salary_rate <= 14000 && $salary_rate > TACLOBAN_MONTHLY_MINIMUM_WAGE) {
					$shielded	=	$salary_rate - ($salary_rate * 0.1);
				}
				elseif($salary_rate <= TACLOBAN_MONTHLY_MINIMUM_WAGE) {
					$shielded	=	$salary_rate;
				}
			}
			else { // crew
				if($salary_rate > 14000) {
					$shielded	=	$salary_rate - ($salary_rate * 0.2);
				}
				elseif($salary_rate <= 14000 && $salary_rate > VESSEL_MONTHLY_MINIMUM_WAGE) {
					$shielded	=	$salary_rate - ($salary_rate * 0.1);
				}
				elseif($salary_rate <= VESSEL_MONTHLY_MINIMUM_WAGE) {
					$shielded	=	$salary_rate;
				}
			}
		}

		$rates['monthly']			=	$salary_rate;
		$rates['shielded_monthly']	=	$shielded;
		$rates['per_cutoff']		=	$rates['monthly'] / 2;
		$rates['daily']				=	$rates['monthly'] / $daily_rate_multiplier;
		$rates['hourly']			=	$rates['daily']/8; // 8 working hours
		$rates['per_min']			=	$rates['hourly'] / 60;

		return $rates;
	}
	public function computeSSS($monthly) {
		$ret					=	null;
		if(is_numeric($monthly)) {
			if($monthly > 0) {
				$sql				=	"SELECT * FROM sss_table WHERE `from_sal`<=".$monthly." AND `to_sal`>=".$monthly." AND stat=1 LIMIT 1";
				$sss_record			=	$this->db->query($sql);
				if($sss_record!=false) {
					if($sss_record->row()) {
						$sss_record			=	$sss_record->result_array();
						$ret				=	$sss_record[0];
					}
				}
				else {
					$this->Abas->sysMsg('errmsg',"SSS not found for salary ".$monthly);
					$ret				=	array('er'=>0, 'ee'=>0, 'from_sal'=>0, 'to_sal'=>0, 'stat'=>0);
				}
			}
			else {
				$ret				=	array('er'=>0, 'ee'=>0, 'from_sal'=>0, 'to_sal'=>0, 'stat'=>0);
			}
		}
		else {
			$this->Abas->sysMsg('errmsg',"Monthly Salary is not a number! (". __CLASS__ . __LINE__ .")");
			$ret				=	0;
		}
		return $ret;
	}
	public function computePH($monthly) {
		$ret					=	null;
		if(is_numeric($monthly)) {
			if($monthly<10000.01) {
				$ret			=	array("employer"=>150.00, "employee"=>150.00);
			}
			elseif($monthly>59999.99) {
				$ret			=	array("employer"=>900.00, "employee"=>900.00);
			}
			else {
				$total			=	($monthly*0.03);
				$ret			=	array("employer"=>($total/2), "employee"=>($total/2));
			}
		}
		else {
			$ret				=	"Monthly rate is not a number!";
			$this->Abas->sysMsg('errmsg', "Monthly salary is not a number! (". __CLASS__ . __LINE__ .")");
		}
		return $ret;
	}
	public function computePI() {
		return array("contribution"=>100);
	}
	public function compute13thMonth($aepd) {
		$ret			=	$aepd['salary_rate'];
		$deduction		=	0;
		$year_hired		=	date("Y",strtotime($aepd['date_hired']));
		$month_hired	=	date("m",strtotime($aepd['date_hired']));
		$day_hired		=	date("d",strtotime($aepd['date_hired']));
		if($aepd['salary_rate'] > 0) {
			if(date("Y") <= $year_hired) {
				$daily_prorated		=	0;
				$remaining_days		=	0;
				$remaining_months	=	12-$month_hired;
				$monthly_prorated	=	($aepd['salary_rate'] / 12) * $remaining_months;
				if($aepd['vessel_id'] < 99990) { // crew computation
					$remaining_days		=	date("t", strtotime($month_hired)) - $day_hired;
					$daily_prorated		=	(($aepd['salary_rate'] / 12)/30) * $remaining_days;
				}
				else {
					$monthly_prorated	=	($aepd['salary_rate'] / 12) * ($remaining_months+1);
				}
				$ret				=	$monthly_prorated + $daily_prorated;
			}
		}
		if(!empty($ret)) {
			$payrolls				=	$this->db->query("SELECT id FROM hr_payroll WHERE payroll_date LIKE '".date("Y")."%'");
			if($payrolls) {
				if($payrolls->row()) {
					$payrolls		=	$payrolls->result_array();
					foreach($payrolls as $payroll) {
						$details	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE emp_id=".$aepd['id']." AND payroll_id=".$payroll['id']);
						if($details) {
							if($details->row()) {
								$details	=	$details->result_array();
								foreach($details as $detail) {
									$deduction	+=	$detail['undertime_amount']+$detail['absences_amount'];
								}
							}
						}
					}
				}
			}
		}
		if($deduction>$ret) {
			$ret	=	0;
			$this->Abas->sysMsg("warnmsg", "Computation for ".$aepd['full_name']." 13th Month Pay deduction is greater than the payable! Defaulting to P0.00");
		}
		else {
			$ret	=	$ret-$deduction;
		}
		return $ret;
	}
	public function annualize($aepd, $payroll_summary, $current_payroll) {
		/* backbone of the tax computation and supported by
		 * the following payroll model functions
		 * 		compute13thMonth
		 * 		computeBonus
		 * 		computeLeaves
		 */
		$annuals=	array("salary"=>0, "deductions"=>0, "overtimes"=>0,"night_differential"=>0, "allowance"=>0, "shielded_salary"=>0, "sss"=>array("ee"=>0, "er"=>0), "wtax"=>0, "pi"=>0, "ph"=>array("ee"=>0, "er"=>0),"others"=>0,);
		$past	=	$annuals;

		// $this->Mmm->debug($aepd);die();

		// get payroll of the month
		$current_deductions	=	($current_payroll['ut']+$current_payroll['absences_amount']);
		// $current_additions	=	$current_payroll['ot']['total'];
		$current_additions	=	0; // OT not taxable
		$payroll_date	=	date("Y-m",strtotime(date("Y")."-".$payroll_summary['month']."-01"));
		$mandatory_base	=	$aepd['salary_rate'];
		if($payroll_summary['period'] == "2nd-half") {
			$month	=	$this->db->query("SELECT id FROM hr_payroll WHERE payroll_date LIKE '".$payroll_date."%' AND payroll_coverage='1st-half'");
			if($month) {
				if($month->row()) {
					$month	=	$month->row();
					$payroll_1sthalf	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE emp_id=".$aepd['id']." AND payroll_id=".$month->id);
					if($payroll_1sthalf) {
						if($payroll_1sthalf->row()) {
							$payroll_1sthalf=	$payroll_1sthalf->row();
							$payroll_1sthalf=	$payroll_1sthalf->salary + $payroll_1sthalf->regular_overtime_amount + $payroll_1sthalf->restday_overtime_amount +  $payroll_1sthalf->holiday_overtime_amount + $payroll_1sthalf->night_differential_amount - ($payroll_1sthalf->undertime_amount + $payroll_1sthalf->absences_amount);
							$mandatory_base	=	$payroll_1sthalf + (($aepd['salary_rate'] + $current_additions)-$current_deductions);
						}
					}
				}
			}
		}

		// get salary + mandatories data
		$rates			=	$this->Payroll_model->getRates($aepd['salary_rate'], $aepd['vessel_id']);

		$sss_record		=	$this->Payroll_model->computeSSS($aepd['salary_rate']);
		$ph_record		=	$this->Payroll_model->computePH($aepd['salary_rate']);
		$pi_record		=	$this->Payroll_model->computePI();

		// get previous payrolls
		$sql	=	"
			SELECT
				d.*,
				p.payroll_date
			FROM
				hr_payroll_details AS d
			JOIN hr_payroll AS p
				ON p.id = d.payroll_id
			WHERE
				d.emp_id=".$aepd['id']." AND
				p.payroll_date LIKE '".date("Y")."%' AND
				d.payroll_id<>1 AND
				d.payroll_id<>2
		"; // get previous employees' payrolls for current year not including initial deposit (payrolls before payroll system implementation)
		$prevdata	=	$this->db->query($sql);
		if($prevdata) {
			if($prevdata->row()) {
				$prevdata	=	$prevdata->result();
				foreach($prevdata as $d) { // loop through previous payrolls
					// $past['salary'] = (($d->salary >= 0) && ($d->undertime_amount >= 0)) ? $past['salary'] + ($d->salary - $d->undertime_amount):$past['salary'];
					$past['salary'] = ($d->salary >= 0) ? $past['salary'] + ($d->salary):$past['salary'];
					$past['sss']['ee'] = ($d->sss_contri_ee > 0)?$past['sss']['ee']+$d->sss_contri_ee:$past['sss']['ee'];
					$past['sss']['er'] = ($d->sss_contri_ee > 0)?$past['sss']['er']+$d->sss_contri_ee:$past['sss']['er'];
					$past['ph']['ee'] = ($d->phil_health_contri > 0)?$past['ph']['ee']+$d->phil_health_contri:$past['ph']['ee'];
					$past['ph']['er'] = ($d->phil_health_contri > 0)?$past['ph']['er']+$d->phil_health_contri:$past['ph']['er'];
					$past['pi'] = ($d->pagibig_contri > 0)?$past['pi']+$d->pagibig_contri:$past['pi'];
					$past['wtax'] = ($d->tax > 0)?$past['wtax']+$d->tax:$past['wtax'];
					$past['deductions'] = ($d->undertime_amount > 0)?$past['deductions']+$d->undertime_amount:$past['deductions'];
					$past['deductions'] = ($d->absences_amount > 0)?$past['deductions']+$d->absences_amount:$past['deductions'];
					$past['overtimes'] = ($d->regular_overtime_amount > 0)?$past['overtimes']+$d->regular_overtime_amount:$past['overtimes'];
					$past['overtimes'] = ($d->restday_overtime_amount > 0)?$past['overtimes']+$d->restday_overtime_amount:$past['overtimes'];
					$past['overtimes'] = ($d->holiday_overtime_amount > 0)?$past['overtimes']+$d->holiday_overtime_amount:$past['overtimes'];
					
					$past['night_differential'] = ($d->night_differential_amount > 0)?$past['night_differential']+$d->night_differential_amount:$past['night_differential'];
					$past['allowance'] = ($d->allowance > 0)?$past['allowance']+$d->allowance:$past['allowance'];
				}
			}
		}

		// get future payrolls
		if($payroll_summary['period']=="1st-half") {
			$periodval	=	1;
		}
		else {
			$periodval	=	0;
		}
		$monthval	=	date("m",strtotime(date("Y")."-".$payroll_summary['month']."-01"));

		/*
		 * get current payroll number
		 * 1st-half october = 20
		 *
		 */
		$payroll_ctr	=	($monthval*2) - $periodval;
		$remaining_payrolls_ctr	=	24 - $payroll_ctr; // 24 payrolls in a year
		$remaining_months_ctr	=	12 - $monthval;

		// calculate future payrolls!
		$future['salary']	=	($rates['monthly']/2) * $remaining_payrolls_ctr;
		$future['sss']['ee']=	$sss_record['ee'] * $remaining_months_ctr;
		$future['sss']['er']=	$sss_record['er'] * $remaining_months_ctr;
		$future['ph']['ee']	=	$ph_record['employee'] * $remaining_months_ctr;
		$future['ph']['er']	=	$ph_record['employer'] * $remaining_months_ctr;
		$future['pi']		=	$pi_record['contribution'] * $remaining_months_ctr;

		$annuals['salary']			=	$future['salary'] + ($rates['monthly']/2) + $past['salary'];
		$annuals['deductions']		=	$current_deductions + $past['deductions'];
		if(isset($current_payroll['ot'])){
			$annuals['overtimes']		=	$current_payroll['ot']['regular'] + $current_payroll['ot']['restday'] + $current_payroll['ot']['holiday'] + $past['overtimes'];
		}
		if(isset($current_payroll['nd'])){
			$annuals['night_differential'] = $current_payroll['nd'] + $past['night_differential'];
		}
		if(isset($current_payroll['allowance'])){
			$annuals['allowance']		=	$current_payroll['allowance'] + $past['allowance'];
		}else{
			
		}
		$annuals['sss']['ee']		=	$future['sss']['ee'] + $sss_record['ee'] + $past['sss']['ee'];
		$annuals['sss']['er']		=	$future['sss']['er'] + $sss_record['er'] + $past['sss']['er'];
		$annuals['ph']['ee']		=	$future['ph']['ee'] + $ph_record['employee'] + $past['ph']['ee'];
		$annuals['ph']['er']		=	$future['ph']['er'] + $ph_record['employer'] + $past['ph']['er'];
		$annuals['pi']				=	$future['pi'] + (($payroll_summary['period']=="1st-half")?$pi_record['contribution']:0) + $past['pi'];
		$annuals['wtax']			=	$past['wtax'];
		$annuals['bonus']			=	$this->Payroll_model->computeBonus($aepd['id']);
		$annuals['leaves']			=	$this->Payroll_model->computeLeaves($aepd['id']);
		$annuals['13thmonth']		=	$this->Payroll_model->compute13thMonth($aepd);

		// computations for label-specific values
		$annuals['nontaxables']							=	$annuals['bonus'] + $annuals['leaves']['total'] + $annuals['13thmonth'] + $annuals['sss']['ee'] + $annuals['ph']['ee'] + $annuals['pi'];
		$annuals['above_ceiling']						=	(($annuals['nontaxables']-90000)>0) ? $annuals['nontaxables']-90000 : 0;
		$annuals['gross_compensation_income']			=	($annuals['salary']+$annuals['leaves']['total']+$annuals['13thmonth']+$annuals['allowance']+$annuals['others']+$annuals['overtimes']+$annuals['night_differential']) - $annuals['deductions'];
		$annuals['nontaxable_statuatory_deductions']	=	$annuals['sss']['ee'] + $annuals['ph']['ee'] + $annuals['pi'];
		$annuals['taxable_basic_salary']				=	$annuals['gross_compensation_income']-$annuals['nontaxables'];
		return $annuals;
	}
	public function computeTax($annuals, $employee_details, $rates, $payroll_summary) {
		$e					=	$employee_details;
		$tax_payable		=	array('annual'=>0, 'monthly'=>0, 'per_cutoff'=>0);
		if($annuals['salary'] <= 0) { return false; }
		if($annuals['taxable_basic_salary'] > 0) {
			$trec			=	$this->db->query("SELECT * FROM annual_tax_codes WHERE (from_sal<".$annuals['taxable_basic_salary']." AND to_sal>=".$annuals['taxable_basic_salary'].")");
			$tax_record		=	$trec->row();
			$percentage		=	($tax_record->over / 100);
			$withholding	=	(($annuals['taxable_basic_salary'] - $tax_record->from_sal) * $percentage) + $tax_record->amount;
			$tax_payable['annual']		=	$withholding;
			$tax_payable['monthly']		=	$withholding / 12;
			if($withholding < 0) {
				$this->Abas->sysMsg("warnmsg","Negative Withholding Tax for ".$e['full_name']."!<pre>WHolding:".$withholding."<br/>Taxable: ".$taxable_income."<br/>From Sal:".$tax_record->from_sal."<br/>Percentage: ".$percentage."<br/>Exemption:".$tax_record->amount."</pre>");
				$tax_payable['annual']		=	0;
				$tax_payable['monthly']		=	0;
				$tax_payable['per_cutoff']	=	0;
			}
		}
		return $tax_payable;
	}
	public function savePayrollEmployee($payroll_id, $ctr, $pd, $employee) {
		$details[$ctr]['payroll_id']			=	$payroll_id;
		$details[$ctr]['emp_id']				=	$pd['employee_id'];
		$details[$ctr]['vessel_id']				=	$pd['vessel_id'];
		$details[$ctr]['salary']				=	$pd['monthly'] / 2;
		$details[$ctr]['allowance']				=	$pd['allowance'];
		$details[$ctr]['regular_overtime_hr']		=	$pd['ot_time']['regular'];
		$details[$ctr]['regular_overtime_amount']	=	$pd['ot']['regular'];
		$details[$ctr]['holiday_overtime_hr']		=	$pd['ot_time']['holiday'];
		$details[$ctr]['holiday_overtime_amount']	=	$pd['ot']['holiday'];
		$details[$ctr]['undertime_hr']			=	$pd['ut_time'];
		$details[$ctr]['undertime_amount']		=	$pd['ut'];
		$details[$ctr]['absences']				=	$pd['absences'];
		$details[$ctr]['absences_amount']		=	$pd['absences_amount'];
		$details[$ctr]['bonus']					=	$pd['bonus'];
		$details[$ctr]['tax']					=	$pd['withholding'];
		$details[$ctr]['sss_contri_ee']			=	$pd['sss']['payable'];
		$details[$ctr]['sss_contri_er']			=	$pd['sss']['employer'];
		$details[$ctr]['phil_health_contri']	=	$pd['ph']['payable'];
		$details[$ctr]['pagibig_contri']		=	$pd['pi']['payable'];
		$details[$ctr]['elf_contri']			=	$pd['elf']['payable'];
		$details[$ctr]['elf_loan']				=	$pd['elf']['loan'];
		$details[$ctr]['sss_loan']				=	$pd['sss']['loan'];
		$details[$ctr]['pagibig_loan']			=	$pd['pi']['loan'];
		$details[$ctr]['pagibig_loan_balance']	=	$pd['pi']['loan_balance'];
		$details[$ctr]['cash_advance']			=	$pd['cash_advance']['loan'];
		$details[$ctr]['cash_advance_balance']	=	$pd['cash_advance']['loan_balance'];
		$details[$ctr]['elf_loan_balance']		=	$pd['elf']['loan_balance'];
		$details[$ctr]['sss_loan_balance']		=	$pd['sss']['loan_balance'];
		$details[$ctr]['total_elf_contribution']=	$employee['total_elf_contribution'];
		$details[$ctr]['net_pay']				=	$pd['net_pay'];

		if(isset($pd['paid_loans'])) {
			foreach($pd['paid_loans'] as $loan_id=>$amt) {
				$insert['loan_id']		=	$loan_id;
				$insert['amount']		=	$amt;
				$insert['date_payment']	=	date("Y-m-d H:i:s");
				$insert['stat']			=	1;
				$inserted				=	$this->Mmm->dbInsert("hr_loan_payments", $insert, "Loan payment for ".$pd['employee_id']." w/ payroll");

				$checkloan				=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loan_id);
				if($checkloan) {
					if(!$checkloan->row()) {
						$checkloan		=	$checkloan->row();
						$balance		=	$checkloan->amount;
						$checkpay		=	$this->db->query("SELECT * FROM hr_loan_payments WHERE loan_id=".$loan_id);
						if($checkpay) {
							if(!$checkpay->row()) {
								$checkpay	=	$checkpay->result_array();
								foreach($checkpay as $payment) {
									$balance	=	$balance - $payment['amount'];
									if($balance <= 0) {
										$update['stat']	=	0;
										$this->Mmm->dbUpdate("hr_loans", $update, $loan_id, "fully paid loan via payroll");
									}
								}
							}
						}
					}
				}

				if($inserted == false) {
					$this->Abas->sysMsg("warnmsg", "A loan payment was not encoded!<pre>Employee: ".$employee['full_name']."<br/>Loan ID: ".$loan_id."<br/>Amount: ".$amt."</pre>");
				}
			}
		}
	}
	public function getAllPayrolls($searchstring="", $limit="", $offset="", $order="", $sort="") {
		/*
		 *
		 * Creates a JSON array formatted to the bootstrap table
		 *
		 */
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='hr_payroll' AND TABLE_SCHEMA='".DBNAME."'");
		$tablefields			=	$tablefields->result();
		if($limit!="") {
			if(is_numeric($limit)) {
				$limit	=	", ".$limit;
			}
		}
		if($offset!="") {
			if(is_numeric($offset)) {
				$offset	=	"LIMIT ".$offset;
			}
		}
		// if($order!="") {
			// if(strtolower($order)==='asc' || strtolower($order)==='desc') {
				// $order	=	"ORDER BY ".($sort!=""?"".$sort:"id")." ".$order;
				$order	=	"ORDER BY id DESC";
			// }
		// }
		$searchfields	=	"";
		if($searchstring!="") {
			$searchfields	=	"";
			foreach($tablefields as $tf) {
				if($searchfields=="")  {
					$searchfields.="AND ";
				}
				else {
				$searchfields.="OR ";
				}
				$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' ";
			}
		}
		$totalsql	=	"
			SELECT
				*
			FROM hr_payroll
			$searchfields $order
		";
		$sql	=	"
			SELECT
				*
			FROM hr_payroll
			$searchfields $order $offset $limit
		";
		$all	=	$this->db->query($sql);
		$total	=	$this->db->query($totalsql);
		$all	=	$all->result_array();

		if(!empty($all)) {
			foreach($all as $ctr=>$a) {
				$payroll_locking	=	$this->Abas->checkPermissions("payroll|locking",false);
				if($a['locked'] == true) {
					// $lock_icon	=	"";
					$lock_icon	=	"Locked";
				}
				else {

					$lock_icon	=	"";
				}

				$company	=	$this->Abas->getCompany($a['company_id']);
				$all[$ctr]['payroll_date']	=	date("F Y", strtotime($a['payroll_date']));
				$all[$ctr]['company_name']	=	$company->name;
				$all[$ctr]['locked']		=	$lock_icon;
			}
			$data	=	array("total"=>count($total->result_array()),"rows"=>$all); // creates array accdg to bootstrap tables
		}
		else {
			$data	=	false;
		}
		return $data;
	}
	public function computeLeaves($employee_id) {
		$ret				=	null;
		$employee			=	$this->Abas->getEmployee($employee_id);
		$rates				=	$this->Payroll_model->getRates($employee['salary_rate'], $employee['vessel_id']);
		if(!empty($employee) && !empty($rates)) {
			if(date("m")<=6 && $employee['vessel_id']>99900) {
				$employee['leave_credits']	=	$employee['leave_credits']+15;
			}
			$ret['remaining_leave_credits']	=	$employee['leave_credits'];
			$ret['total']		=	$rates['daily'] * $employee['leave_credits'];
			$ret['de_minimis']	=	$rates['daily'] * (($employee['leave_credits']>10)?10:$employee['leave_credits']);
			if($ret['total']>$ret['de_minimis']) {
				$ret['de_minimis_excess']	=	$ret['total']-$ret['de_minimis'];
			}
			else {
				$ret['de_minimis_excess']	=	$ret['total'];
			}
		}
		return $ret;
	}
	public function computeBonus($employee_id) {
		$ret		=	null;
		$employee	=	$this->Abas->getEmployee($employee_id);
		if(!empty($employee)) {
			$hired_on		=	explode("-",date("Y-m-d",strtotime($employee['date_hired'])));
			$computed_on	=	explode("-",date("Y-m-d",strtotime("November 30")));
			$years			=	$computed_on[0]-$hired_on[0];
			if($years<=0)		$multiplier	=	0;
			elseif($years==1)	$multiplier	=	0.2;
			elseif($years==2)	$multiplier	=	0.4;
			elseif($years==3)	$multiplier	=	0.6;
			elseif($years==4)	$multiplier	=	0.8;
			elseif($years>=5)	$multiplier	=	1;
			$ret		=	$employee['salary_rate']*$multiplier;
		}
		return $ret;
	}
	public function getPayroll($id){
		$sql = "SELECT * FROM hr_payroll WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = null;
		}
		return $result;
	}
	public function getPayrollDetails($payroll_id){
		$sql = "SELECT * FROM hr_payroll_details WHERE payroll_id=".$payroll_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			$total_net_payroll = 0;
			foreach($result as $row){
				$total_net_payroll  = $total_net_payroll + $row->net_pay;
			}
			$result['total_net_payroll']= $total_net_payroll;
		}else{
			$result = null;
		}
		return $result;
	}
	public function getPayrollGrossPerVessel($payroll_id){
		$vessel_gross = array();
		$gross = array();
		$allowance = array();
		$sss_contribution = array();
		$sss_employee_contribution = array();
		$sss_employer_contribution = array();
		$pagibig_contribution = array();
		$philhealth_contribution = array();
		$advances_to_officers = array();
		$tax = array();
		$sss_loan_payable = array();
		$pagibig_loan_payable = array();
		$sss_loan_balance = array();
		$pagibig_loan_balance = array();
		$sql = "SELECT * FROM hr_payroll_details WHERE payroll_id=".$payroll_id. " GROUP BY vessel_id";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $row){
				$sql2 = "SELECT * FROM hr_payroll_details WHERE vessel_id=".$row->vessel_id." AND payroll_id=".$payroll_id;
				$query2 = $this->db->query($sql2);
				if($query2){
					$result2 = $query2->result();
					foreach($result2 as $row2){
						$gross[$row->vessel_id] = $gross[$row->vessel_id] + (($row2->salary + $row2->allowance + $row2->regular_overtime_amount + $row2->restday_overtime_amount + $row2->specialholiday_overtime_amount + $row2->specialholiday_restday_overtime_amount + $row2->legalholiday_overtime_amount + $row2->legalholiday_restday_overtime_amount + $row2->night_differential_amount + $row2->bonus + $row2->others) - ($row2->undertime_amount + $row2->absences_amount));
						$allowance[$row->vessel_id] = $allowance[$row->vessel_id] + $row2->allowance;
						$pagibig_contribution[$row->vessel_id] = $pagibig_contribution[$row->vessel_id] + $row2->pagibig_contri;
						$sss_contribution[$row->vessel_id] = $sss_contribution[$row->vessel_id] + ($row2->sss_contri_ee + $row2->sss_contri_er);
						$sss_employee_contribution[$row->vessel_id] = $sss_employee_contribution[$row->vessel_id] + $row2->sss_contri_ee;
						$sss_employer_contribution[$row->vessel_id] = $sss_employer_contribution[$row->vessel_id] + $row2->sss_contri_er;
						$philhealth_contribution[$row->vessel_id] = $philhealth_contribution[$row->vessel_id] + $row2->phil_health_contri;
						$advances_to_officers[$row->vessel_id] = $advances_to_officers[$row->vessel_id] + $row2->cash_advance;
						$tax[$row->vessel_id] = $tax[$row->vessel_id] + $row2->tax;
						$sss_loan_payable[$row->vessel_id] = $sss_loan_payable[$row->vessel_id] + $row2->sss_loan;
						$pagibig_loan_payable[$row->vessel_id] = $pagibig_loan_payable[$row->vessel_id] + $row2->pagibig_loan;
						$sss_loan_balance[$row->vessel_id] = $sss_loan_balance[$row->vessel_id] + $row2->sss_loan_balance;
						$pagibig_loan_balance[$row->vessel_id] = $pagibig_loan_balance[$row->vessel_id] + $row2->pagibig_loan_balance;
					}
					$vessel_gross[$row->vessel_id]['vessel_id'] = $row->vessel_id;
					$vessel_gross[$row->vessel_id]['gross_amount'] = $gross[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['allowance_amount'] = $allowance[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['vessel_name'] = $this->Abas->getVessel($row->vessel_id)->name;
					$vessel_gross[$row->vessel_id]['pagibig_amount'] = $pagibig_contribution[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['sss_amount'] = $sss_contribution[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['sss_employee_amount'] = $sss_employee_contribution[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['sss_employer_amount'] = $sss_employer_contribution[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['philhealth_amount'] = $philhealth_contribution[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['advances_amount'] = $advances_to_officers[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['tax_amount'] = $tax[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['sss_loan_payable_amount'] = $sss_loan_payable[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['pagibig_loan_payable_amount'] = $pagibig_loan_payable[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['sss_loan_balance_amount'] = $sss_loan_balance[$row->vessel_id];
					$vessel_gross[$row->vessel_id]['pagibig_loan_balance_amount'] = $pagibig_loan_balance[$row->vessel_id];

				}
			}
		}
		return $vessel_gross;
	}
	public function getPreviousGrossIncomeByEmployee($emp_id){
		$sql = "SELECT * FROM hr_payroll_details WHERE emp_id=".$emp_id." ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		$gross_income =0;
		if($query){
			$row = $query->row();
			if($row->salary<>''){
				$gross_income = $row->salary + $row->allowance + $row->regular_overtime_amount + $row->holiday_overtime_amount + $row->night_differential_amount + $row->bonus + $row->others - ($row->undertime_amount + $row->absences_amount);
			}
		}

		return $gross_income;
	}
	public function getPayrollByCompany($company_id){
		$sql = "SELECT * FROM hr_payroll WHERE company_id=".$company_id." AND stat=1 ORDER BY payroll_date DESC";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getPayrollByEmployee($employee_id,$year=''){
		$sql = "SELECT * FROM hr_payroll_details INNER JOIN hr_payroll ON hr_payroll.id = hr_payroll_details.payroll_id WHERE hr_payroll_details.emp_id=".$employee_id." AND YEAR(hr_payroll.created_on)='".$year."'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
}
?>