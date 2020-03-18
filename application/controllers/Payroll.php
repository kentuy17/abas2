<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends CI_Controller {

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		$this->load->model("Hr_model");
		$this->load->model("Payroll_model");
		$this->load->model("Mmm");
		$this->output->enable_profiler(FALSE);
		define("SIDEMENU","Payroll");
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
		define("PAYROLL_DEBUG_ID", 856);
		define("PAYROLL_DEBUG_TAX", true);
	}
	public function index()	{$data=array();
		$this->Abas->checkPermissions("payroll|view");
		unset($_SESSION['payroll']);
		$data['companies']			=	$this->Abas->getCompanies(true);
		$data['payroll_summaries']	=	$this->Payroll_model->getAllPayrolls();
		$data['viewfile']	=	"payroll/payroll_view.php";
		$this->load->view('gentlella_container.php',$data);
	}
	public function add_payroll(){
		$data['companies']			=	$this->Abas->getCompanies(true);
		$this->load->view('payroll/payroll_add',$data);
	}
	public function create() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		if(isset($_SESSION['payroll'])) {
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		if(!isset($_POST['company'],$_POST['month'],$_POST['period'])) {
			$this->Abas->sysMsg('errmsg',"Submission lacks input; payroll not created!");
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		if(!is_numeric($_POST['company'])) {
			$this->Abas->sysMsg('errmsg',"Invalid company; payroll not created!");
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		if($_POST['company']==10) { $this->Abas->checkPermissions("payroll|view_staff_payroll"); }
		$period		=	$this->Mmm->sanitize($_POST['period']);
		$company_id	=	$this->Mmm->sanitize($_POST['company']);
		$sql		=	"SELECT * FROM hr_payroll WHERE payroll_date='".date("Y-m",strtotime(date("Y").$_POST['month']."-01"))."' AND payroll_coverage='".$period."' AND company_id=".$company_id;
		$check		=	$this->db->query($sql);
		if($check) {
			if($check->row()){
				$check	=	$check->row();
				$this->Abas->sysMsg('warnmsg',"Payroll already exists!");
				$this->Abas->redirect(HTTP_PATH."payroll_history/view/".$check->id);
			}
		}
		if($period=='2nd-half'){
			$previousmonth = date("Y-m",strtotime(date("Y").$_POST['month']));
			$sql2 = "SELECT * FROM hr_payroll WHERE payroll_date='".$previousmonth."' AND payroll_coverage='1st-half' AND company_id=".$company_id;
			$check2		=	$this->db->query($sql2);
			if($check2){
				$count = $check2->row();
				if($count->id==''){
				$this->Abas->sysMsg('warnmsg',"You cannot create the 2nd-half payroll of ".$_POST['month']." ".date('Y')." if the 1st-half was not yet created.");
				$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
		}

		$company	=	$this->Abas->getCompany($company_id);
		if($company == false) {
			$this->Abas->sysMsg('errmsg',"Invalid company; payroll not created!");
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$_SESSION['payroll']=	$_POST;
		$_SESSION['payroll']['year']			=	date("Y");
		$_SESSION['payroll']['company_name']	=	$company->name;

		#####################################
		#####################################
		###                               ###
		###                               ###
		###      Payroll is created       ###
		###             HERE              ###
		###                               ###
		###                               ###
		#####################################
		#####################################
		$payroll		=	"";
		$whole_period	=	array("employee"=>0, "bir"=>0, "sss"=>0, "ph"=>0, "pi"=>0, "elf"=>0);
		$details		=	array();
		$departments	=	$this->Abas->getDepartments();
		$vessels	=	$this->Abas->getVessels();
		foreach($vessels as $vessel) {
			$all_employees_per_dept	=	$this->db->query("SELECT id FROM hr_employees WHERE company_id=".$company->id." AND vessel_id=".$vessel->id." AND stat=1 AND (employee_status<>'Resigned' AND employee_status<>'Retired' AND employee_status<>'Terminated' AND employee_status<>'Separated')"); // all employees per vessel
			if($all_employees_per_dept!=false) {
				if($all_employees_per_dept->row()) {
					$all_employees_per_dept	=	$all_employees_per_dept->result();
					foreach($all_employees_per_dept as $aepd) {
						$aepd	=	$this->Abas->getEmployee($aepd->id);
						if($aepd!=false) {
							// compute!
							$payroll						=	array();
							$payroll['vessel_id']			=	$aepd['vessel_id'];
							$payroll['dept']				=	$aepd['department'];
							$payroll['employee_id']			=	$aepd['id'];
							$payroll['monthly']				=	$aepd['salary_rate'];
							$payroll['allowance']			=	0;
							$payroll['ut']					=	0;
							$payroll['ut_time']				=	0;
							$payroll['absences']			=	0;
							$payroll['absences_amount']		=	0;
							$payroll['ot']					=	array("holiday"=>0, 
																	"regular"=>0,
																	"restday"=>0, 
																	"legal_holiday"=>0,
																	"legal_holiday_restday"=>0,
																	"special_holiday"=>0,
																	"special_holiday_restday"=>0,
																	"total"=>0);
							$payroll['ot_time']				=	array("holiday"=>0, 
																	"regular"=>0,
																	"restday"=>0, 
																	"legal_holiday"=>0,
																	"legal_holiday_restday"=>0,
																	"special_holiday"=>0,
																	"special_holiday_restday"=>0,
																	"total"=>0);
							$payroll['nd']					=	0;
							$payroll['nd_time']				=	0;
							$payroll['bonus']				=	0;
							$payroll['others']				=	0;

							$payroll['withholding']			=	0;
							$payroll['sss']					=	array("loan"=>0, "loan_balance"=>0, "payable"=>0, "employer"=>0);
							$payroll['ph']					=	array("loan"=>0, "loan_balance"=>0, "payable"=>0, "employer"=>0);
							$payroll['pi']					=	array("loan"=>0, "loan_balance"=>0, "payable"=>0, "employer"=>0);
							$payroll['elf']					=	array("loan"=>0, "loan_balance"=>0, "payable"=>0, "employer"=>0);
							$payroll['cash_advance']		=	array("loan"=>0, "loan_balance"=>0, "payable"=>0);
							$payroll['income']				=	0;
							$payroll['deductions']			=	0;
							$payroll['net_pay']				=	0;

							$payroll['rates']				=	$this->Payroll_model->getRates($aepd['salary_rate'], $aepd['vessel_id']);
							$payroll['shielded_monthly']	=	$payroll['rates']['shielded_monthly'];
							$payroll['allowance']			=	$aepd['allowance']/2;


							if($aepd['absences']>0) {
								$payroll['absences']		=	$aepd['absences'];
								$payroll['absences_amount']	=	($payroll['absences'] * $payroll['rates']['daily']);
							}

							// get UT
							$all_uts			=	$this->Payroll_model->computeUT($aepd['id']);
							$total_ut			=	0;
							$total_ut_time		=	0;

							if($all_uts!=false) { // computes all UT deductions
								foreach($all_uts as $ut_record) {
									$this_ut			=	0;
									$this_ut_time		=	0;
									if($ut_record['approved']!=0) { // check if approved
										// $this->Mmm->debug($ut_record);
										// converts time to valid
										$time=explode(":",$ut_record['ut_time']);
										// $time=explode(":",date("H:i", strtotime($ut_record['ut_time'])));
										$ut_time=($time[0]*60)+($time[1]); // 01:30 to 1.5 hours
										$this_ut_time	=	$ut_time;
										$this_ut		=	($ut_time * $payroll['rates']['per_min']); // computes for deductible UT
									}
									$total_ut			=	$total_ut + $this_ut; // adds to total
									$total_ut_time		=	$total_ut_time + $this_ut_time;
								}
							}
							$payroll['ut']				=	$total_ut;
							$payroll['ut_time']			=	$total_ut_time;

							// get OT
							$all_ots			=	$this->Payroll_model->computeOT($aepd['id']);
							$total_ot			=	0;
							$total_holiday_ot	=	0;
							$total_ot_time		=	0;
							$total_holiday_ot_time = 0;

							/*if($all_ots!=false) { // computes all OT income
								// $this->Abas->sysMsg("errmsg", "Hourly: ".$payroll['rates']['hourly']);
								foreach($all_ots as $ot_record) {
									$this_ot			=	0;
									$this_ot_time		=	0;
									if($ot_record['approved']!=0) { // check if approved
										// converts time to valid
										$time=explode(":",$ot_record['ot_time']);
										
										$ot_hours		=	$time[0];
										$ot_minutes		=	$time[1];
										$this_ot_time	=	$ot_hours + ($ot_minutes / 60);
										$base_ot		=	($ot_hours * $payroll['rates']['hourly']) + ($ot_minutes * $payroll['rates']['per_min']);
										$percentage		=	( $base_ot * ($ot_record['rate']/100) );
										$this_ot		=	($base_ot + $percentage); // computes for payable OT
										
									}
									$total_ot			=	$total_ot + $this_ot; // adds to total

									$total_ot_time		=	$total_ot_time + $this_ot_time;

									if($ot_record['rate']==25) {
										$payroll['ot']['regular']	=	$payroll['ot']['regular'] + $this_ot;
										$payroll['ot_time']['regular']	=	$payroll['ot_time']['regular'] + $this_ot_time;
									}
									elseif($ot_record['rate']>25) {
										$payroll['ot']['holiday']	=	$payroll['ot']['holiday'] + $this_ot;
										$payroll['ot_time']['holiday']	=	$payroll['ot_time']['holiday'] + $this_ot_time;
									}
									else {
										$this->Abas->sysMsg("errmsg","OT Error for ".$aepd['full_name']." No rate!");
									}
								}
							}*/

							if($all_ots!=false) { // computes all OT income
								// $this->Abas->sysMsg("errmsg", "Hourly: ".$payroll['rates']['hourly']);
								foreach($all_ots as $ot_record) {
									$this_ot			=	0;
									$this_holiday_ot	=	0;
									$this_ot_time		=	0;
									$this_holiday_ot_time = 0;
									if($ot_record['approved']!=0) { // check if approved
										// converts time to valid
										$time=explode(":",$ot_record['ot_time']);
										$ot_hours		=	$time[0];
										$ot_minutes		=	$time[1];
										$this_ot_time	=	$ot_hours + ($ot_minutes / 60);
										
										$daily_rate = $payroll['rates']['daily'];
										$hourly_rate = $payroll['rates']['hourly'];

										if($ot_record['type']=="Regular Day") {
											$payroll['ot']['regular']	=	$payroll['ot']['regular'] + (($this_ot_time*$hourly_rate)*1.25);
											$this_ot = $this_ot + $payroll['ot']['regular'];
											$payroll['ot_time']['regular']	=	$payroll['ot_time']['regular'] + $this_ot_time;
										}
										elseif($ot_record['type']=="Rest Day") {
											$payroll['ot']['restday']	=	$payroll['ot']['restday']+ (($this_ot_time*$hourly_rate)*1.30);
											$this_ot = $this_ot + $payroll['ot']['restday'];
											$payroll['ot_time']['restday']	=	$payroll['ot_time']['restday'] + $this_ot_time;
										}
										elseif($ot_record['type']=="Legal Holiday") {
											if($this_ot_time<=8){//less than 8 hours
												$payroll['ot']['legal_holiday']	=	$payroll['ot']['legal_holiday'] + (($this_ot_time*$hourly_rate)*1);
											}elseif($this_ot_time>8){//more than 8 hours
												$excess_hours = $this_ot_time-8;
												$payroll['ot']['legal_holiday']	=	$payroll['ot']['legal_holiday'] +  ($daily_rate*1) + (($hourly_rate*$excess_hours)*2.6);
											}
											$this_ot = $this_ot + $payroll['ot']['legal_holiday'];
											$this_holiday_ot = $this_holiday_ot + $payroll['ot']['legal_holiday'];
											$this_holiday_ot_time = $this_holiday_ot_time + $this_ot_time;
											$payroll['ot_time']['legal_holiday']	=	$payroll['ot_time']['legal_holiday'] + $this_ot_time;
										}elseif($ot_record['type']=="Legal Holiday on Rest Day") {
											if($this_ot_time<=8){
												$payroll['ot']['legal_holiday_restday']	= $payroll['ot']['legal_holiday_restday'] + (($this_ot_time*$hourly_rate)*1) + ((($this_ot_time*$hourly_rate)*2)*.30);
											}elseif($this_ot_time>8){
												$excess_hours = $this_ot_time-8;
												$payroll['ot']['legal_holiday_restday']	=	$payroll['ot']['legal_holiday_restday'] + ($daily_rate*1) + (($daily_rate*2)*.30) +(($hourly_rate*2.6)*($excess_hours*1.3));
											}
											$this_ot = $this_ot + $payroll['ot']['legal_holiday_restday'];
											$this_holiday_ot = $this_holiday_ot + $payroll['ot']['legal_holiday_restday'];
											$this_holiday_ot_time = $this_holiday_ot_time + $this_ot_time;
											$payroll['ot_time']['legal_holiday_restday']	=	$payroll['ot_time']['legal_holiday_restday'] + $this_ot_time;
										}elseif($ot_record['type']=="Special Holiday") {
											if($this_ot_time<=8){
												$payroll['ot']['special_holiday']	=	$payroll['ot']['special_holiday'] + (($this_ot_time*$hourly_rate)*.30);
											}elseif($this_ot_time>8){
												$excess_hours = $this_ot_time-8;
												$payroll['ot']['special_holiday']	=	$payroll['ot']['special_holiday'] + ($daily_rate*.30) + (($hourly_rate*1.69)*$excess_hours);
											}
											$this_ot = $this_ot + $payroll['ot']['special_holiday'];
											$this_holiday_ot = $this_holiday_ot + $payroll['ot']['special_holiday'];
											$this_holiday_ot_time = $this_holiday_ot_time + $this_ot_time;
											$payroll['ot_time']['special_holiday']	=	$payroll['ot_time']['special_holiday'] + $this_ot_time;
										}elseif($ot_record['type']=="Special Holiday on Rest Day") {
											if($this_ot_time<=8){
												$payroll['ot']['special_holiday_restday']	=	$payroll['ot']['special_holiday_restday'] +  (($this_ot_time*$hourly_rate)*.50);
											}elseif($this_ot_time>8){
												$excess_hours = $this_ot_time-8;
												$payroll['ot']['special_holiday_restday']	=	 $payroll['ot']['special_holiday_restday'] + ($daily_rate*.50) + (($hourly_rate*1.95)*$excess_hours);
											}
											$this_ot = $this_ot + $payroll['ot']['special_holiday_restday'];
											$this_holiday_ot = $this_holiday_ot + $payroll['ot']['special_holiday_restday'];
											$this_holiday_ot_time = $this_holiday_ot_time + $this_ot_time;
											$payroll['ot_time']['special_holiday_restday']	=	$payroll['ot_time']['special_holiday_restday'] + $this_ot_time;
										}
										else {
											$this->Abas->sysMsg("errmsg","OT Error for ".$aepd['full_name']." No rate!");
										}
										
										$total_ot			=	$total_ot + $this_ot; // adds to total
										$total_ot_time		=	$total_ot_time + $this_ot_time;
										$total_holiday_ot	=	$total_holiday_ot + $this_holiday_ot;
										$total_holiday_ot_time = $total_holiday_ot_time + $this_holiday_ot_time;

									}
								}

										
							}

							$payroll['ot']['holiday'] 		=	$payroll['ot']['legal_holiday'] + $payroll['ot']['legal_holiday_restday'] + $payroll['ot']['special_holiday'] + $payroll['ot']['special_holiday_restday'];

							$payroll['ot']['total']			=	$payroll['ot']['regular'] + $payroll['ot']['restday'] + $payroll['ot']['holiday'];

							$payroll['ot_time']['total']	=	$total_ot_time;
							$payroll['ot_time']['holiday']	=	$total_holiday_ot_time;

							// get ND
							$all_nds			=	$this->Payroll_model->computeND($aepd['id']);
							$total_nd			=	0;
							$total_nd_time		=	0;

							if($all_nds!=false) {
								foreach($all_nds as $nd_record) {
									$this_nd			=	0;
									$this_nd_time		=	0;
									if($nd_record['added_by']!=0) {
										$time=explode(":",$nd_record['night_diff_hours']);
										$nd_hours		=	$time[0];
										$nd_minutes		=	$time[1];
										$this_nd_time	=	$nd_hours + ($nd_minutes / 60);
										$base_nd		=	($nd_hours * ($payroll['rates']['hourly']*0.1)) + ($nd_minutes * ($payroll['rates']['per_min']*0.06));
									
										$total_nd			=	$total_nd + $base_nd; 
										$total_nd_time		=	$total_nd_time + $this_nd_time;
									}else {
										$this->Abas->sysMsg("errmsg","ND Error for ".$aepd['full_name']);
									}
								}
							}
							$payroll['nd']	=	$total_nd;
							$payroll['nd_time'] =	$total_nd_time;

							$payroll['income']			=	(($aepd['salary_rate']/2) - $payroll['ut'] - $payroll['absences_amount']) + $payroll['bonus'] + $payroll['others'] + $payroll['allowance'] + $payroll['ot']['regular'] + $payroll['ot']['restday'] + $payroll['ot']['legal_holiday'] + $payroll['ot']['legal_holiday_restday'] + $payroll['ot']['special_holiday'] + $payroll['ot']['special_holiday_restday'] + $payroll['nd'];
							$mandatories_base			=	(($aepd['salary_rate']) - $payroll['ut']) + $payroll['bonus'] + $payroll['others'] + $payroll['allowance'] + $payroll['ot']['regular'] + $payroll['ot']['restday'] + $payroll['ot']['legal_holiday'] + $payroll['ot']['legal_holiday_restday'] + $payroll['ot']['special_holiday'] + $payroll['ot']['special_holiday_restday'] + $payroll['nd'];

							$tax_payable['monthly']		=	0;
							$tax_payable['per_cutoff']	=	0;

							if($_SESSION['payroll']['period']=="1st-half") { // pi
								$pi_record					=	$this->Payroll_model->computePI();
								$payroll['pi']['payable']	=	$pi_record['contribution'];
								$whole_period['pi']			=	$whole_period['pi'] + ($pi_record['contribution']);

								$payroll['taxable_income']	=	0;//$annuals['salary'];
								$payroll['withholding']	=	0;
							}
							if($_SESSION['payroll']['period']=="2nd-half") { // sss&ph

								$annuals			=	array("salary"=>0, "sss"=>0, "wtax"=>0, "pi"=>0, "ph"=>0);
								// $this->Mmm->debug($aepd);

								$sss_record	=	$this->Payroll_model->computeSSS($aepd['salary_rate']);
								if(!empty($sss_record)) {
									$payroll['sss']['employer']	=	$payroll['sss']['employer'] + ($sss_record['er']);
									$payroll['sss']['payable']	=	$payroll['sss']['payable'] + ($sss_record['ee']);
									$whole_period['sss']		=	$whole_period['sss'] + ($sss_record['er']);
								}
								$ph_record	=	$this->Payroll_model->computePH($aepd['salary_rate']);
								if(!empty($ph_record)) {
									$payroll['ph']['employer']	=	$payroll['ph']['employer'] + ($ph_record['employer']);
									$payroll['ph']['payable']	=	$payroll['ph']['payable'] + ($ph_record['employee']);
									$whole_period['ph']			=	$whole_period['ph'] + ($ph_record['employer']);
								}

								//Aug-24,2019 -  JB temporary fixed! created condition to select only the employees which monthly income is on category table for income tax
								$pi_record					=	$this->Payroll_model->computePI();
								$gross_income_current	=  $payroll['income'];
								$gross_income_previous = $this->Payroll_model->getPreviousGrossIncomeByEmployee($aepd['id']);
								$gov_deduction = $sss_record['ee'] + $ph_record['employee']+$pi_record['contribution'];
								$total_gross_income = ($gross_income_previous + $gross_income_current) - $gov_deduction;
								if($total_gross_income>=20833){
									//Maske's code for tax computation that uses annual tax code table, validate if this is still correct since there are discrepancy between the BIR tax calculator and the system's computation
									$annuals			=	$this->Payroll_model->annualize($aepd, $_POST, $payroll); // $_POST contains 'company', 'month', and 'period'
									// $this->Mmm->debug($annuals);

									$payroll['taxable_income']	=	$annuals['salary'];

									$tax_payable	=	$this->Payroll_model->computeTax($annuals, $aepd, $payroll['rates'],$_POST);
									$payroll['withholding']		=	$tax_payable['monthly'];
								}else{
									$payroll['taxable_income']	=	0;
									$payroll['withholding']		=	0;
								}

								//JB's WTax computation based on monthly table of BIR Revised Wtax 2018-2022
								/*$pi_record					=	$this->Payroll_model->computePI();
								$gov_deduction = $sss_record['ee'] + $ph_record['employee']+$pi_record['contribution'];
								$gross_income_current	=  $payroll['income'];
								$gross_income_previous = $this->Payroll_model->getPreviousGrossIncomeByEmployee($aepd['id']);
								$thirteen_month_pay_distribution = 0;//($this->Payroll_model->compute13thMonth($aepd))/12;
								$compensation = ($gross_income_previous + $gross_income_current) - ($gov_deduction + $thirteen_month_pay_distribution);

								//based on BIR Revised Witholding Tax Table Jan1,2018-Dec31,2022
								if($compensation<20833){
									$payroll['withholding']	= 0;
								}elseif($compensation>=20833 && $compensation<33333){
									$payroll['withholding']	= ($compensation-20833)*0.20;
								}elseif($compensation>=33333 && $compensation<66667){
									$payroll['withholding']	= (($compensation-33333)*0.25)+2500;
								}elseif($compensation>=66667 && $compensation<166667){
									$payroll['withholding']	= (($compensation-66667)*0.30)+10833.33;
								}elseif($compensation>=166667 && $compensation<666667){
									$payroll['withholding']	= (($compensation-166667)*0.32)+40833.33;
								}elseif($compensation>=666667){
									$payroll['withholding']	= (($compensation-666667)*0.35)+200833.33;
								}*/
							}

							$whole_period['bir']		=	$whole_period['bir'] + $payroll['withholding'];

							// get elf
							$payroll['elf']['payable']	=	($aepd['elf_rate'] / 2);
							$whole_period['elf']		=	$whole_period['elf'] + $payroll['elf']['payable'];

							// get loans
							$loans						=	$this->Payroll_model->getAllLoans($aepd['id']);

							// if($aepd['id']==95) {$this->Mmm->debug($loans); die();}
							$payroll['total_loan_payments']	=	0;
							if($loans!=false) {
								foreach($loans as $l) {
									// $this->Mmm->debug($l);
									$loantype			=	strtolower($l['loan_type']);
									$already_paid		=	0;

									if(!isset($payroll[$loantype])) { // sets blank value for loans
										if($loantype=="pagibig") {$loantype="pi";} // something went wrong :(
										if($loantype=="cash advance") {$loantype="cash_advance";}
										//$payroll[$loantype]	=	array("loan"=>0, "loan_balance"=>0, "payable"=>0);
									}

									$payments			=	$this->db->query("SELECT * FROM hr_loan_payments WHERE loan_id=".$l['id']);
									if($payments != false) {
										if($payments->row()) {
											$payments	=	$payments->result_array();
											// $this->Mmm->debug($payments);
											foreach($payments as $p) {
												$already_paid	=	$already_paid + $p['amount'];
											}
										}
									}

									$payroll[$loantype]['loan_balance']	=	(str_replace(",","",$l['amount_loan']) - $already_paid);

									if($payroll[$loantype]['loan_balance'] > 0) { // compute loan only if balance payable exists
										$payroll[$loantype]['loan']		=	(str_replace(",","",$l['monthly_amortization'])/2);
										if($payroll[$loantype]['loan_balance'] < str_replace(",","",$payroll[$loantype]['loan'])) { // if payment is greater than balance, pay only balance
											$payroll[$loantype]['loan']	=	$payroll[$loantype]['loan_balance'];
										}
										if($aepd['vessel_id'] < 99990) { // crew only
											if($_SESSION['payroll']['period']=="1st-half") { // crew loan payment of SSS and PagIbig only on 1st half
												if($loantype=="sss" || $loantype=="pi") {
													$payroll[$loantype]['loan']	=	(str_replace(",","",$payroll[$loantype]['loan']) * 2);
												}
											}
											elseif($_SESSION['payroll']['period']=="2nd-half") { // no SSS and PagIbig loan payment for crew on 2nd half
												if($loantype=="sss" || $loantype=="pi") {
													$payroll[$loantype]['loan']	=	0;
												}
											}
										}
										if($aepd['vessel_id']!=99999 && $loantype=="elf") { // no ELF for non-makati-office employees
											$payroll[$loantype]['loan']	=	0;
										}
									}
									else {
										//$payroll[$loantype]['loan']		=	0;
									}
									if($payroll[$loantype]['loan_balance'] > 0) {
										$payroll['paid_loans'][$l['id']]	=	(str_replace(",","",$payroll[$loantype]['loan']));
									}
									unset($already_paid);
									unset($loantype);
								}
							}

							#############
							##         ##
							##         ##
							## Pagibig ##
							##         ##
							$payroll['pi']['payable']	=	($_SESSION['payroll']['period']=="1st-half")?100:0;
							##         ##
							#############

							// Compute everything! Get the net pay!
							$payroll['deductions']		=	$payroll['pi']['payable'] + $payroll['sss']['payable'] + $payroll['ph']['payable'] + $payroll['pi']['loan'] + $payroll['sss']['loan'] + $payroll['ph']['loan'] + $payroll['cash_advance']['loan'] + $payroll['withholding'];
							$net_pay					=	$payroll['income'] - $payroll['deductions'];
							$payroll['net_pay']			=	$net_pay;
							$whole_period['employee']	=	$whole_period['employee'] + $net_pay;


							// $this->Mmm->debug($payroll);
							// $this->Mmm->debug($rates);
							// $this->Mmm->debug("<hr/>");
							// $this->Mmm->debug($payroll);
							// $this->Mmm->debug($whole_period);

							if($payroll['monthly'] > 0) {
								if($aepd['employee_status']=="Active" || $aepd['employee_status']=="Regular" || $aepd['employee_status']=="Probationary" || $aepd['employee_status']=="Contractual" || $aepd['employee_status']=="Fixed Term" || $aepd['employee_status']=="Casual") {
									$details[]					=	$payroll;
								}
							}
							// $this->Mmm->debug($loans);
							// $this->Mmm->debug($rates);
							// $this->Mmm->debug($all_ots);
							// $this->Mmm->debug($withholding);
							// $this->Mmm->debug($taxrecord);
							// $this->Mmm->debug($aepd);
						}
						else {
							$this->Abas->sysMsg("errmsg", "Payroll creation error!");
						}
					}

				}
				else {
					// $this->Abas->sysMsg("warnmsg", "No employees found for ".$vessel->name);
				}
			}
			else {
				$this->Abas->sysMsg("errmsg", "Payroll creation error!");
			}
		}

		//die($this->Mmm->debug($details));
		// $this->Abas->sysMsg('errmsg', "Makati:".MAKATI_MONTHLY_MINIMUM_WAGE."<br/>"."Cebu:".CEBU_MONTHLY_MINIMUM_WAGE."<br/>"."Vessel:".VESSEL_MONTHLY_MINIMUM_WAGE);
		$_SESSION['payroll']['summary']			=	$whole_period;
		$_SESSION['payroll']['data']			=	$details;
		// $this->Mmm->debug($_SESSION['payroll']);
		// unset($_SESSION['payroll']);
		#####################################
		#####################################
		###                               ###
		###                               ###
		###      Payroll is created       ###
		###             HERE              ###
		###                               ###
		###                               ###
		#####################################
		#####################################

		$this->Abas->sysMsg('sucmsg',"Payroll for ".$_SESSION['payroll']['company_name']." (". $_SESSION['payroll']['period']." ".$_SESSION['payroll']['month']." ".$_SESSION['payroll']['year'].")  has been prepared!");
		$this->Abas->redirect(HTTP_PATH."payroll/summary");
	}
	public function summary() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		if(isset($_SESSION['payroll'])) {
			if(is_numeric($_SESSION['payroll']['company'])) {
				$company	=	$this->Abas->getCompany($_SESSION['payroll']['company']);
				if($company==true) {
					$departments	=	$this->Abas->getDepartments();
					// $vessels		=	$this->Abas->getVesselsByCompany($_SESSION['payroll']['company']);
					$vessels		=	$this->Abas->getVessels();
					if(!empty($vessels)) {
						foreach($vessels AS $d) {
							$sql	=	"SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name FROM hr_employees AS hre WHERE (employee_status<>'Terminated' OR employee_status<>'Retired' OR employee_status<>'Resigned' OR employee_status<>'Separated') AND vessel_id=".$d->id." AND company_id=".$_SESSION['payroll']['company'];
							$employees	=	$this->db->query($sql);
							$data['all_employees_per_vessel'][$d->id]	=	$employees->result();
						}
					}
					else {
						$sql	=	"SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name FROM hr_employees AS hre WHERE (employee_status<>'Terminated' OR employee_status<>'Retired' OR employee_status<>'Resigned' OR employee_status<>'Separated') AND company_id=".$_SESSION['payroll']['company'];
						$employees	=	$this->db->query($sql);
						$data['all_employees']	=	$employees->result();
					}
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company; payroll not created!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Invalid company; payroll not created!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Submission lacks input; payroll not created!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		//$mainview				=	"container.php";
		$mainview				=	"gentlella_container.php";
		$data['viewfile']		=	"payroll/payroll_list.php";
		$this->load->view($mainview,$data);

	}

	public function payslips($id="") {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		// echo "<pre>";print_r($_SESSION);echo "</pre>";
		if(isset($_SESSION['payroll'])) {
			if(is_numeric($_SESSION['payroll']['company'])) {
				$company	=	$this->Abas->getCompany($_SESSION['payroll']['company']);
				if($company==true) {
					if($id=="") {
						$idquery	=	"";
					}
					elseif (is_numeric($id)) {
						$idquery	=	"AND id=".$id;
					}
					$sql	=	"SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name FROM hr_employees AS hre WHERE (employee_status<>'Terminated' OR employee_status<>'Retired' OR employee_status<>'Resigned' OR employee_status<>'Separated') ".$idquery." AND company_id=".$_SESSION['payroll']['company'];
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found; payroll not saved!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Submission lacks input; payroll not saved!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$mainview				=	"payroll/payslip.php";
		$this->load->view($mainview,$data);
	}
	public function bir_report() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		// echo "<pre>";print_r($_SESSION);echo "</pre>";
		if(isset($_SESSION['payroll'])) {
			if(is_numeric($_SESSION['payroll']['company'])) {
				$company	=	$this->Abas->getCompany($_SESSION['payroll']['company']);
					$sql	=	"SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name FROM hr_employees AS hre WHERE (employee_status<>'Terminated' OR employee_status<>'Retired' OR employee_status<>'Resigned' OR employee_status<>'Separated') AND `tin_num`<>'' AND company_id=".$_SESSION['payroll']['company'];
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found; payroll not saved!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
			}
			else {
				$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		$mainview				=	"payroll/tax_report.php";
		$this->load->view($mainview,$data);
	}
	public function bank_report() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		// echo "<pre>";print_r($_SESSION);echo "</pre>";
		if(isset($_SESSION['payroll'])) {
			if(is_numeric($_SESSION['payroll']['company'])) {
				$company	=	$this->Abas->getCompany($_SESSION['payroll']['company']);
					$sql	=	"SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name FROM hr_employees AS hre WHERE (employee_status<>'Terminated' OR employee_status<>'Retired' OR employee_status<>'Resigned' OR employee_status<>'Separated') AND `tin_num`<>'' AND company_id=".$_SESSION['payroll']['company'];
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found; payroll not saved!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
			}
			else {
				$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		$mainview				=	"payroll/bank_report.php";
		$this->load->view($mainview,$data);
	}
	public function sss_report() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		// echo "<pre>";print_r($_SESSION);echo "</pre>";
		if(isset($_SESSION['payroll'])) {
			if(is_numeric($_SESSION['payroll']['company'])) {
				$company	=	$this->Abas->getCompany($_SESSION['payroll']['company']);
					$sql	=	"SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name FROM hr_employees AS hre WHERE (employee_status<>'Terminated' OR employee_status<>'Retired' OR employee_status<>'Resigned' OR employee_status<>'Separated') AND `sss_num`<>'' AND company_id=".$_SESSION['payroll']['company']." ORDER BY last_name ASC";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found; payroll not saved!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
			}
			else {
				$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		$mainview				=	"payroll/sss_report.php";
		$this->load->view($mainview,$data);
	}
	public function ph_report() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		// echo "<pre>";print_r($_SESSION);echo "</pre>";
		if(isset($_SESSION['payroll'])) {
			if(is_numeric($_SESSION['payroll']['company'])) {
				$company	=	$this->Abas->getCompany($_SESSION['payroll']['company']);
					$sql	=	"SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name FROM hr_employees AS hre WHERE (employee_status<>'Terminated' OR employee_status<>'Retired' OR employee_status<>'Resigned' OR employee_status<>'Separated') AND `ph_num`<>'' AND company_id=".$_SESSION['payroll']['company']." ORDER BY last_name ASC";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found; payroll not saved!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
			}
			else {
				$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		$mainview				=	"payroll/ph_report.php";
		$this->load->view($mainview,$data);
	}
	public function pi_report() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		// echo "<pre>";print_r($_SESSION);echo "</pre>";
		if(isset($_SESSION['payroll'])) {
			if(is_numeric($_SESSION['payroll']['company'])) {
				$company	=	$this->Abas->getCompany($_SESSION['payroll']['company']);
					$sql	=	"SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name FROM hr_employees AS hre WHERE (employee_status<>'Terminated' OR employee_status<>'Retired' OR employee_status<>'Resigned' OR employee_status<>'Separated') AND `pagibig_num`<>'' AND company_id=".$_SESSION['payroll']['company']." ORDER BY last_name ASC";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found; payroll not saved!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
			}
			else {
				$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Invalid company; payroll not saved!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		$mainview				=	"payroll/pi_report.php";
		$this->load->view($mainview,$data);
	}
	public function save() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		$sql		=	"SELECT * FROM hr_payroll WHERE payroll_date='".$_SESSION['payroll']['year']."-".date("m",strtotime($_SESSION['payroll']['month']))."' AND payroll_coverage='".$_SESSION['payroll']['period']."' AND company_id=".$_SESSION['payroll']['company'];
		$check		=	$this->db->query($sql);
		if($check) {
			if($check->row()){
				$check	=	$check->row();
				$this->Abas->sysMsg('warnmsg',"Payroll already exists!");
				$this->Abas->redirect(HTTP_PATH."payroll_history/view/".$check->id);
			}
		}
		if(isset($_SESSION['payroll'])) {

			$control_number = $this->Abas->getNextSerialNumber('hr_payroll',$_SESSION['payroll']['company']);

			$summary['payroll_date']		=	$_SESSION['payroll']['year']."-".date("m",strtotime($_SESSION['payroll']['month']));
			$summary['company_id']			=	$_SESSION['payroll']['company'];
			$summary['payroll_coverage']	=	$_SESSION['payroll']['period'];
			$summary['locked']				=	0;
			$summary['control_number']		=	$control_number;
			$summary['payroll_amount']		=	0;
			$summary['stat']				=	1;
			$summary['created_on']			=	date("Y-m-d H:i:s");
			$summary['created_by']			=	$_SESSION['abas_login']['userid'];
			$new_payroll		=	$this->Mmm->dbInsert("hr_payroll",$summary, "New Payroll for ".$summary['payroll_coverage']." ".$summary['payroll_date']." ".$company->name." by ".$_SESSION['abas_login']['userid']);

			if($new_payroll==true) {
				// get payroll id
				$payroll_id			=	$this->db->query("SELECT MAX(id) AS max_id FROM hr_payroll");
				$payroll_id			=	$payroll_id->row();
				$payroll_id			=	$payroll_id->max_id;

				$total_payroll		=	0;
				$details			=	array();
				foreach($_SESSION['payroll']['data'] as $ctr=>$pd) {
					$employee			=	$this->Abas->getEmployee($pd['employee_id']);
					$salary_grade		=	0;
					$elf_contri			=	0;
					$ph_contri			=	0;
					$sss_contri_ee		=	0;
					$sss_contri_er		=	0;
					$pi_contri			=	0;
					$withholding		=	0;
					$position			=	"-";
					$department_name	=	"-";
					$netpay				=	(($pd['monthly']/2) + $pd['allowance'] + $pd['ot']['regular'] + $pd['ot']['restday'] + $pd['ot']['legal_holiday'] + $pd['ot']['legal_holiday_restday'] + $pd['ot']['special_holiday'] + $pd['ot']['special_holiday_restday'] + $pd['nd'] + $pd['bonus'] + $pd['others']) - ($pd['sss']['payable'] + $pd['pi']['payable'] + $pd['ph']['payable'] + $pd['absences_amount'] + $pd['ut'] + $pd['withholding'] + $pd['sss']['loan'] + $pd['pi']['loan'] + $pd['cash_advance']['loan'] + $pd['elf']['payable'] + $pd['elf']['loan']);

					$details[$ctr]['payroll_id']				=	$payroll_id;
					$details[$ctr]['emp_id']					=	$pd['employee_id'];
					$details[$ctr]['vessel_id']					=	$pd['vessel_id'];
					$details[$ctr]['salary']					=	$pd['monthly'] / 2;
					$details[$ctr]['allowance']					=	$pd['allowance'];
					$details[$ctr]['regular_overtime_hr']		=	$pd['ot_time']['regular'];
					$details[$ctr]['regular_overtime_amount']	=	$pd['ot']['regular'];
					$details[$ctr]['restday_overtime_hr']		=	$pd['ot_time']['restday'];
					$details[$ctr]['restday_overtime_amount']	=	$pd['ot']['restday'];
					$details[$ctr]['specialholiday_overtime_hr']		=	$pd['ot_time']['special_holiday'];
					$details[$ctr]['specialholiday_overtime_amount']	=	$pd['ot']['special_holiday'];
					$details[$ctr]['specialholiday_restday_overtime_hr']		=	$pd['ot_time']['special_holiday_restday'];
					$details[$ctr]['specialholiday_restday_overtime_amount']	=	$pd['ot']['special_holiday_restday'];
					$details[$ctr]['legalholiday_overtime_hr']		=	$pd['ot_time']['legal_holiday'];
					$details[$ctr]['legalholiday_overtime_amount']	=	$pd['ot']['legal_holiday'];
					$details[$ctr]['legalholiday_restday_overtime_hr']		=	$pd['ot_time']['legal_holiday_restday'];
					$details[$ctr]['legalholiday_restday_overtime_amount']	=	$pd['ot']['legal_holiday_restday'];
					$details[$ctr]['holiday_overtime_hr']		=	$pd['ot_time']['holiday'];
					$details[$ctr]['holiday_overtime_amount']	=	$pd['ot']['holiday'];
					$details[$ctr]['night_differential_hr']		=	$pd['nd_time'];
					$details[$ctr]['night_differential_amount']	=	$pd['nd'];
					$details[$ctr]['undertime_hr']				=	$pd['ut_time'];
					$details[$ctr]['undertime_amount']			=	$pd['ut'];
					$details[$ctr]['absences']					=	$pd['absences'];
					$details[$ctr]['absences_amount']			=	$pd['absences_amount'];
					$details[$ctr]['bonus']						=	$pd['bonus'];
					$details[$ctr]['others']					=	$pd['others'];
					$details[$ctr]['tax']						=	$pd['withholding'];
					$details[$ctr]['sss_contri_ee']				=	$pd['sss']['payable'];
					$details[$ctr]['sss_contri_er']				=	$pd['sss']['employer'];
					$details[$ctr]['phil_health_contri']		=	$pd['ph']['payable'];
					$details[$ctr]['pagibig_contri']			=	$pd['pi']['payable'];
					$details[$ctr]['elf_contri']				=	$pd['elf']['payable'];
					$details[$ctr]['elf_loan']					=	$pd['elf']['loan'];
					$details[$ctr]['sss_loan']					=	$pd['sss']['loan'];
					$details[$ctr]['pagibig_loan']				=	$pd['pi']['loan'];
					$details[$ctr]['pagibig_loan_balance']		=	$pd['pi']['loan_balance'];
					$details[$ctr]['cash_advance']				=	$pd['cash_advance']['loan'];
					$details[$ctr]['cash_advance_balance']		=	$pd['cash_advance']['loan_balance'];
					$details[$ctr]['elf_loan_balance']			=	$pd['elf']['loan_balance'];
					$details[$ctr]['sss_loan_balance']			=	$pd['sss']['loan_balance'];
					$details[$ctr]['total_elf_contribution']	=	$employee['total_elf_contribution'];

					$leaves = $this->Hr_model->getEmployeeLeaves($pd['employee_id'],date('Y'));
					$leave_credits_balance = $employee['leave_credits'] - $leaves['number_of_filed'];
					
					$details[$ctr]['leave_credits']				=	$leave_credits_balance;
					$details[$ctr]['net_pay']					=	$netpay;

					if(isset($pd['paid_loans'])) {
						foreach($pd['paid_loans'] as $loan_id=>$amt) {
							$checkloan				=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loan_id);
							$checkloan				=	$checkloan->row();
							$balance				=	$checkloan->amount_loan;

							$insert['loan_id']		=	$loan_id;
							$insert['payroll_id']	=	$payroll_id;
							$insert['amount']		=	$amt;
							$insert['date_payment']	=	date("Y-m-d H:i:s");
							$insert['stat']			=	1;
							$inserted				=	$this->Mmm->dbInsert("hr_loan_payments", $insert, $checkloan->loan_type." Loan payment for ".$employee['full_name']." w/ payroll");


							$checkpay	=	$this->Payroll_model->computeLoanPayments($loan_id);
							$remaining_balance	=	$checkpay-$checkloan->amount_loan;
							if($remaining_balance <= 0) {
								$update['stat']	=	0;
								$this->Mmm->dbUpdate("hr_loans", $update, $loan_id, "fully paid loan via payroll for ".$employee['full_name']);
							}
							if($inserted == false) {
								$this->Abas->sysMsg("warnmsg", "A loan payment was not encoded!<pre>Employee: ".$employee['full_name']."<br/>Loan ID: ".$loan_id."<br/>Amount: ".$amt."</pre>");
							}
						}
					}

					$total_payroll		=	$total_payroll + $pd['net_pay'];

					$updateELF			=	"UPDATE hr_employees SET total_elf_contribution=".($employee['total_elf_contribution']+$details[$ctr]['elf_contri'])." WHERE employee_id=".$pd['employee_id']."; ";
					$this->db->query($updateELF);
					$updateABSENCES		=	"";
					if($employee['absences'] > 0) {
						$updateABSENCES		=	"UPDATE hr_employees SET absences=0 WHERE id=".$pd['employee_id']."; ";
						$this->db->query($updateABSENCES);
					}
					$updateOTs			=	"UPDATE hr_overtime SET computed=1, computed_on='".date("Y-m-d H:i:s")."' WHERE computed=0 AND employee_id=".$pd['employee_id']."; ";
					$this->db->query($updateOTs);
					$updateNDs			=	"UPDATE hr_night_differential SET is_computed=1, computed_on='".date("Y-m-d H:i:s")."' WHERE is_computed=0 AND employee_id=".$pd['employee_id']."; ";
					$this->db->query($updateNDs);
					$updateUTs			=	"UPDATE hr_undertime SET computed=1, computed_on='".date("Y-m-d H:i:s")."' WHERE computed=0 AND employee_id=".$pd['employee_id']."; ";
					$this->db->query($updateUTs);
				}
				$result	=	$this->Mmm->multiInsert("hr_payroll_details",$details,"New Payroll");
				$company=	$this->Abas->getCompany($_SESSION['payroll']['company']);
				if(!$company) {
					$this->Abas->sysMsg("errmsg", "Company not found! Please try again.");
				}
				$update_payroll_amount = "UPDATE hr_payroll SET payroll_amount=".$total_payroll." WHERE id=".$payroll_id;
				$success = $this->db->query($update_payroll_amount);
				if($success){
					$this->Abas->sysMsg("sucmsg", "Created new payroll summary for ".$company->name ." (".$summary['payroll_coverage']." ".date('M Y',strtotime($summary['payroll_date'])).")");
					$this->Abas->sysNotif("New Payroll Summary", $_SESSION['abas_login']['fullname']." has created new payroll  for ".$company->name ." (".$summary['payroll_coverage']." ".date('M Y',strtotime($summary['payroll_date'])).")" ,"Payroll","info");
					$this->Abas->redirect(HTTP_PATH."payroll_history/view/".$payroll_id);

				}
			}else{
				$this->Abas->sysMsg("warnmsg", "Payroll summary details were not saved! Please contact Administrator again.");
				$this->Abas->redirect(HTTP_PATH."payroll/");
			}
		}
		else {
			$this->Abas->sysMsg("warnmsg", "Payroll summary was not saved! Please try again.");
			$this->Abas->redirect(HTTP_PATH."payroll/");
		}
	}
	public function edit($session_id=0, $action="") {$data=array();
		if($action=="") {
			if(isset($_SESSION['payroll']['data'][$session_id])) {
				$sessiondata			=	$_SESSION['payroll']['data'][$session_id];
				$data['session_id']		=	$session_id;
				$data['payrollsession']	=	$sessiondata;
				$mainview				=	"payroll/edit.php";
				$this->load->view($mainview,$data);
			}
			else {
				$this->Abas->sysMsg("warnmsg", "Payroll record not found!");
				$this->Abas->redirect(HTTP_PATH."payroll/summary");
			}
		}
		elseif($action=="update") {
			$sessiondata	=	$_SESSION['payroll']['data'][$session_id];
			$e				=	$this->Abas->getEmployee($sessiondata['employee_id']);
			$employee_link	=	"<a href='".HTTP_PATH."payroll/edit/".$session_id."' data-toggle='modal' data-target='#modalDialog' style='cursor:pointer;'>".$e['full_name']."</a>";
	
			if(!empty($_POST)) {
				$monthly					=	$_SESSION['payroll']['data'][$session_id]['monthly'];
				// $monthly					=	$this->Mmm->sanitize($_POST['monthly']);
				$absences					=	$this->Mmm->sanitize($_POST['absences']);
				$allowance					=	$this->Mmm->sanitize($_POST['allowance']);
				$regular_ot					=	$this->Mmm->sanitize($_POST['regular_ot']);
				$restday_ot					=	$this->Mmm->sanitize($_POST['restday_ot']);
				$legalholiday_ot			=	$this->Mmm->sanitize($_POST['legal_holiday_ot']);
				$legalholiday_restday_ot	=	$this->Mmm->sanitize($_POST['legal_holiday_on_rest_day_ot']);
				$specialholiday_ot			=	$this->Mmm->sanitize($_POST['special_holiday_ot']);
				$specialholiday_restday_ot	=	$this->Mmm->sanitize($_POST['special_holiday_on_rest_day_ot']);
				$total_holiday_ot           =   $legalholiday_ot + $legalholiday_restday_ot + $specialholiday_ot + $specialholiday_restday_ot;	
				$ut							=	$this->Mmm->sanitize($_POST['ut']);
				$nd							=	$this->Mmm->sanitize($_POST['night_differential']);
				$bonus						=	$this->Mmm->sanitize($_POST['bonus']);
				$others						=	$this->Mmm->sanitize($_POST['others']);
				$withholding				=	$this->Mmm->sanitize($_POST['withholding']);
				$elf_payable				=	$this->Mmm->sanitize($_POST['elf_payable']);
				$sss_payable				=	$this->Mmm->sanitize($_POST['sss_payable']);
				$ph_payable					=	$this->Mmm->sanitize($_POST['ph_payable']);
				$pi_payable					=	$this->Mmm->sanitize($_POST['pi_payable']);

				$_SESSION['payroll']['data'][$session_id]['allowance']			=	$allowance;
				$_SESSION['payroll']['data'][$session_id]['absences_amount']	=	$absences;
				$_SESSION['payroll']['data'][$session_id]['ot']['regular']		=	$regular_ot;
				$_SESSION['payroll']['data'][$session_id]['ot']['restday']		=	$restday_ot;
				$_SESSION['payroll']['data'][$session_id]['ot']['legal_holiday']		=	$legalholiday_ot;
				$_SESSION['payroll']['data'][$session_id]['ot']['legal_holiday_restday']		=	$legalholiday_restday_ot;
				$_SESSION['payroll']['data'][$session_id]['ot']['special_holiday']		=	$specialholiday_ot;
				$_SESSION['payroll']['data'][$session_id]['ot']['special_holiday_restday']		=	$specialholiday_restday_ot;
				$_SESSION['payroll']['data'][$session_id]['ot']['holiday']		=	$total_holiday_ot;
				$_SESSION['payroll']['data'][$session_id]['ut']					=	$ut;
				$_SESSION['payroll']['data'][$session_id]['nd']					=	$nd;
				$_SESSION['payroll']['data'][$session_id]['bonus']				=	$bonus;
				$_SESSION['payroll']['data'][$session_id]['others']				=	$others;
				$_SESSION['payroll']['data'][$session_id]['withholding']		=	$withholding;

				$_SESSION['payroll']['data'][$session_id]['elf']['payable']			=	$elf_payable;
				$_SESSION['payroll']['data'][$session_id]['sss']['payable']			=	$sss_payable;
				$_SESSION['payroll']['data'][$session_id]['ph']['payable']			=	$ph_payable;
				$_SESSION['payroll']['data'][$session_id]['pi']['payable']			=	$pi_payable;

				// calculate loans!
				$all_loan_payments	=	0;
				$elf_loan_payments	=	0;
				foreach($_POST as $var=>$val) {
					if(strpos($var, "loan") !== false) { // has loan
						$loanid	=	str_replace("loan", "", $var);
						if(is_numeric($loanid)) {
							$check	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loanid);
							if($check->row()) { // loan exists
								$check			=	$check->row();
								$amt_paid		=	$this->Payroll_model->computeLoanPayments($loanid);
								$loan_balance	=	$check->amount_loan - $amt_paid;
								if($val > $loan_balance) {
									$this->Abas->sysMsg("warnmsg", "Loan payment is greater than remaining balance! Loan payment not changed for ".$employee_link.".");
								}
								else {
									$_SESSION['payroll']['data'][$session_id]['paid_loans'][$loanid]	=	$val;
									if($check->loan_type=="ELF") {
										$_SESSION['payroll']['data'][$session_id]['elf']['loan']	=	$val;
										$elf_loan_payments	=	$elf_loan_payments + $val;
									}
									else {
										$loantype			=	strtolower($l['loan_type']);
										if($loantype=="pagibig") {$loantype="pi";} // something went wrong :(
										if($loantype=="cash advance") {$loantype="cash_advance";}
										$_SESSION['payroll']['data'][$session_id][$loantype]['loan']		=	$val;
										$_SESSION['payroll']['data'][$session_id]['paid_loans'][$check->id]	=	$val;
										$all_loan_payments	=	$all_loan_payments + $val;
									}
								}
							}
						}
					}
				}

				$income			=	(($monthly/2)-$ut) + $nd + $bonus + $others + $allowance + $regular_ot + $restday_ot + $legalholiday_ot + $legalholiday_restday_ot + $specialholiday_ot + $specialholiday_restday_ot;
				// $deductions		=	$pi_payable + $sss_payable + $ph_payable + $elf_payable + $pi_loan + $sss_loan + $ph_loan + $elf_loan + $cash_advance + $withholding;
				$deductions		=	$pi_payable + $sss_payable + $ph_payable + $withholding + $all_loan_payments + $absences;
				$elf_deductions	=	$elf_payable + $elf_loan_payments;
				$net_pay					=	$income - $deductions;

				$_SESSION['payroll']['data'][$session_id]['income']		=	$income;
				$_SESSION['payroll']['data'][$session_id]['deductions']	=	$deductions;
				$_SESSION['payroll']['data'][$session_id]['net_pay']	=	$net_pay;
			}
			$this->Abas->sysNotif("Payroll Update", $_SESSION['abas_login']['username']." updated the unsaved payroll of ".$employee_link." for ".$_SESSION['payroll']['period']." ".date("F",strtotime($_SESSION['payroll']['month']."-01"))." in for ".$e['company_name'].".", "Payroll", "warning");
			$this->Abas->sysMsg("msg", "Updated payroll for ".$employee_link."!");
			$this->Abas->redirect(HTTP_PATH."payroll/summary");
		}
	}
	public function generate_reports($type) {
		$data=array();
		$data['companies']	=	$this->Abas->getCompanies(true);
		$data['type']		=	$type;
		$this->load->view('payroll/filter_report.php',$data);
	}
	public function alphalist_report_old() {
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");
		if(empty($_POST)) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please try again!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		if(!isset($_POST['company'], $_POST['month'], $_POST['year'])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please select a company, month and year!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		if($_POST['company']=="" || $_POST['month']=="" || $_POST['year']=="" || $_POST['company']==null || $_POST['month']==null || $_POST['year']==null) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please select a company, month and year!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		if(!is_numeric($_POST['company'])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid company!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		$company		=	(array)$this->Abas->getCompany($_POST['company']);
		$date_requested	=	date("Y-m", strtotime($_POST['year']."-".$_POST['month']."-01"));
		$check	=	$this->db->query("SELECT * FROM hr_payroll WHERE payroll_date='".$date_requested."' AND company_id=".$company['id']." ORDER BY payroll_coverage ASC LIMIT 2");
		if(!$check) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid company!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		if(!$check->row()) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, payroll not found!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		$check	=	$check->result_array();
		if(!isset($check[0])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, no payroll data found!");
		}
		$employees	=	$this->db->query("SELECT id, company_id, stat FROM hr_employees WHERE company_id=".$company['id']." AND stat=1 ORDER BY vessel_id ASC");
		if(!$employees) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee data!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		if(!$employees->row()) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee data!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		$table	=	"";
		$employees	=	$employees->result_array();
		if(!empty($employees)) {
			foreach($employees as $e) {
				$e			=	$this->Abas->getEmployee($e['id']);
				$namedata		=	"<tr>";
				$namedata		.=	"<td>".$e['vessel_name']."</td>";
				$namedata		.=	"<td>".$e['tin_num']."</td>";
				$namedata		.=	"<td>".$e['tax_code']."</td>";
				$namedata		.=	"<td>".$e['full_name']."</td>";
				$namedata		.=	"<td>".$e['position_name']."</td>";
				$namedata		.=	"<td>".date("j F Y",strtotime($e['date_hired']))."</td>";
				$namedata		.=	"<td>".$this->Abas->currencyFormat($e['salary_rate'])."</td>";
				$total		=	array("salary"=>0, "sss"=>0, "pi"=>0, "ph"=>0, "tax"=>0, "net_pay"=>0);
				$rowdata	=	array("1st-half"=>"<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>", "2nd-half"=>"<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>", );
				foreach($check as $pr) {
					$hq		=	$this->db->query("SELECT * FROM hr_payroll_details WHERE emp_id=".$e['id']." AND payroll_id=".$pr['id']);
					if($hq) {
						if($hq->row()) {
							$hq	=	$hq->row();
							//$net_pay=	($hq->salary + $hq->allowance + $hq->regular_overtime_amount + $hq->restday_overtime_amount + $hq->specialholiday_overtime_amount+ $hq->specialholiday_restday_overtime_amount+ $hq->legalholiday_overtime_amount+ $hq->legalholiday_restday_overtime_amount +  $hq->night_differential_amount) + $hq->bonus +$hq->others - ($hq->undertime_amount + $hq->absences_amount + $hq->tax + $hq->sss_contri_ee + $hq->phil_health_contri + $hq->elf_contri + $hq->elf_loan + $hq->pagibig_loan + $hq->cash_advance);

							$salary	=	(($hq->salary+$hq->allowance+$hq->regular_overtime_amount+$hq->restday_overtime_amount + $hq->holiday_overtime_amount +  $hq->night_differential_amount+$hq->bonus+$hq->others)-($hq->absences_amount+$hq->undertime_amount));

							$loans =  $hq->sss_loan + $hq->pagibig_loan + $hq->cash_advance;

							$net_pay	=	$salary - ($hq->sss_contri_ee + $hq->pagibig_contri + $hq->phil_health_contri + $hq->tax) - $loans;

							$total['sss']	= $total['sss'] + $hq->sss_contri_ee;
							$total['pi']	=	$total['pi'] + $hq->pagibig_contri;
							$total['ph']	=	$total['ph'] + $hq->phil_health_contri;
							$total['tax']		=	$total['tax'] + $hq->tax;
							$total['salary']	=	$total['salary'] + $salary;
							$total['loans']	=	$total['loans'] + $loans;
							$total['net_pay']	=	$total['net_pay'] + $net_pay;
							$rowdata[$pr['payroll_coverage']]	=	"<td>".$this->Abas->currencyFormat($salary)."</td>";
							$rowdata[$pr['payroll_coverage']]	.=	"<td>".$this->Abas->currencyFormat($hq->sss_contri_ee)."</td>";
							$rowdata[$pr['payroll_coverage']]	.=	"<td>".$this->Abas->currencyFormat($hq->pagibig_contri)."</td>";
							$rowdata[$pr['payroll_coverage']]	.=	"<td>".$this->Abas->currencyFormat($hq->phil_health_contri)."</td>";
							$rowdata[$pr['payroll_coverage']]	.=	"<td>".$this->Abas->currencyFormat($hq->tax)."</td>";
							$rowdata[$pr['payroll_coverage']]	.=	"<td>".$this->Abas->currencyFormat($loans)."</td>";
							$rowdata[$pr['payroll_coverage']]	.=	"<td>".$this->Abas->currencyFormat($net_pay)."</td>";
						}
					}
				}
				$totalrow	=	"<td>".$this->Abas->currencyFormat($total['salary'])."</td><td>".$this->Abas->currencyFormat($total['sss'])."</td><td>".$this->Abas->currencyFormat($total['pi'])."</td><td>".$this->Abas->currencyFormat($total['ph'])."</td><td>".$this->Abas->currencyFormat($total['tax'])."</td><td>".$this->Abas->currencyFormat($total['loans'])."</td><td>".$this->Abas->currencyFormat($total['net_pay'])."</td>";
				if($total['net_pay'] > 0) {
					$table	.=	$namedata.$rowdata['1st-half'].$rowdata['2nd-half'].$totalrow."</tr>";
				}
			}
		}
		$data['orientation']	=	"L";
		$data['pagetype']		=	"legal";
		$data['title']			=	"Alphalist Report";
		$data['content']		=	'
		<style type=\"text/css\">
				 h1 { font-size:180%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 140%; text-align:center}
				 td {font-size:130%;text-align:center}
			</style>
		<div class="panel">
			<div class="text-center">
		<h1>'.$company['name'].'</h1>
		<h3>'.date("F Y", strtotime($date_requested)).'</h3>
		</div>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" border="1">
					<thead>
		<tr>			
						<th>Vessel/Office</th>
						<th>TIN</th>
						<th>Tax Code</th>
						<th>Employee</th>
						<th>Position</th>
						<th>Date Hired</th>
						<th>Salary</th>

						<th>1st-half Gross Salary</th>
						<th>1st-half SSS</th>
						<th>1st-half HDMF</th>
						<th>1st-half PHIC</th>
						<th>1st-half W-Tax</th>
						<th>1st-half Loans</th>
						<th>1st-half Net-Pay</th>

						<th>2nd-half Gross Salary</th>
						<th>2nd-half SSS</th>
						<th>2nd-half HDMF</th>
						<th>2nd-half PHIC</th>
						<th>2nd-half W-Tax</th>
						<th>2nd-half Loans</th>
						<th>2nd-half Net-Pay</th>

						<th>Total Gross Salary</th>
						<th>Total SSS</th>
						<th>Total HDMF</th>
						<th>Total PHIC</th>
						<th>Total W-Tax</th>
						<th>Total Loans</th>
						<th>Total Net-Pay</th>
		</tr>
		</thead>
		<tbody>
		'.$table.'
		</tbody>
		</table>
			</div>
		</div>
		';
		$this->load->view('pdf-container.php',$data);
		//echo $data['content'];
	}
	public function alphalist_report(){
		$company = $this->Mmm->sanitize($_POST['company']);
		$year = $this->Mmm->sanitize($_POST['year']);

		//if($company==1){//to include the ABISC (Staff) separated company before
		//	$sql_payroll_emp = "SELECT hr_payroll_details.emp_id FROM hr_payroll_details INNER JOIN hr_payroll ON hr_payroll.id=hr_payroll_details.payroll_id WHERE YEAR(hr_payroll.created_on)='".$year."' AND (hr_payroll.company_id=1 OR hr_payroll.company_id=10) GROUP BY hr_payroll_details.emp_id,hr_payroll.company_id";
		//}else{
			$sql_payroll_emp = "SELECT hr_payroll_details.emp_id FROM hr_payroll_details INNER JOIN hr_payroll ON hr_payroll.id=hr_payroll_details.payroll_id WHERE YEAR(hr_payroll.created_on)='".$year."' AND hr_payroll.company_id=".$company." GROUP BY hr_payroll_details.emp_id";
		//}
		
		$query = $this->db->query($sql_payroll_emp);
		if($query){
			$data = array();
			$payroll_emp = $query->result();
			$data['company']['details']= $this->Abas->getCompany($company);
			$data['year'] = $year;
			foreach($payroll_emp as $ctr=>$row){
				$data['employee']['details'][$ctr] = $this->Abas->getEmployee($row->emp_id);
				$data['employee']['payroll'][$ctr] = $this->Payroll_model->getPayrollByEmployee($row->emp_id,$year);
			}
			$data['viewfile'] = 'payroll/alphalist_report.php';
			$this->load->view('gentlella_container.php',$data);
		}
		
	}
	public function annualization_report() {
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");
		if(empty($_POST)) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please try again!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		if(!isset($_POST['employee'])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please select an employee!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		if($_POST['employee']=="" || $_POST['employee']==null) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please select an employee!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		if(!is_numeric($_POST['employee'])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee!");
			$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		}
		$e	=	$this->Abas->getEmployee($_POST['employee']);
		for($x=1; $x<=12; $x++) {
			$tablecontents[sprintf("%02d", $x)]	=	array("1st-half"=>array("salary"=>0, "tax"=>0, "ph"=>0, "pi"=>0, "sss"=>0), "2nd-half"=>array("salary"=>0, "tax"=>0, "ph"=>0, "pi"=>0, "sss"=>0));
		}unset($x);
		// get past payrolls
		$past_payrolls	=	$this->db->query("SELECT hpd.*, p.id, p.payroll_date, p.payroll_coverage FROM hr_payroll_details AS hpd JOIN hr_payroll AS p ON hpd.payroll_id=p.id WHERE emp_id=".$e['id']." AND p.id<>1 AND p.payroll_date=".date("Y"));
		$past_payrolls	=	$past_payrolls->result_array();
		//$this->Mmm->debug($past_payrolls);
		$vessel_id		=	$e['vessel_id'];
		foreach($past_payrolls as $pp) {
			$monthvalue		=	substr($pp['payroll_date'], 5, 2);
			$salary_rate	=	$pp['salary'];
			$shielded		=	$pp['salary'];
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
			$salary			=	$shielded - $pp['absences_amount'] - $pp['undertime_amount'];
			$tablecontents[$monthvalue][$pp['payroll_coverage']]	=	array("salary"=>$salary, "tax"=>$pp['tax'], "ph"=>$pp['phil_health_contri'], "pi"=>$pp['pagibig_contri'], "sss"=>$pp['sss_contri_ee']);
		}

		// assume future payrolls
		$sss_record			=	$this->Payroll_model->computeSSS($e['salary_rate']);
		$ph_record			=	$this->Payroll_model->computePH($e['salary_rate']);
		$pi_record			=	$this->Payroll_model->computePI($e['salary_rate']);

		// compute future tax
		$annuals			=	array("salary"=>0, "sss"=>0, "wtax"=>0, "pi"=>0, "ph"=>0);
		$annualoptions		=	array("company"=>$e['company_id'], "month"=>date("m"), "period"=>(date("d")>15?"2nd half":"1st half"));
		$payroll			=	array("ut"=>0, "absences_amount"=>0,"rates"=>0);
		$annuals			=	$this->Payroll_model->annualize($e, $annualoptions, $payroll);
		$tax_payable		=	$this->Payroll_model->computeTax($annuals, $e, $payroll['rates'], $annualoptions);

		for($monthvalue; $monthvalue<=12; $monthvalue++) {
			$tablecontents[sprintf("%02d", $monthvalue)]['1st-half']	=	array("salary"=>(($e['salary_rate']/2)*0.8), "tax"=>$tax_payable['per_cutoff'], "ph"=>0, "pi"=>$pi_record['contribution'], "sss"=>0);
			$tablecontents[sprintf("%02d", $monthvalue)]['2nd-half']	=	array("salary"=>(($e['salary_rate']/2)*0.8), "tax"=>$tax_payable['per_cutoff'], "ph"=>$ph_record['employee'], "pi"=>0, "sss"=>$sss_record['ee']);
		}

		// rock 'n' roll!
		$table	=	"";
		$total	=	array("salary"=>0, "sss"=>0, "ph"=>0, "pi"=>0);
		foreach($tablecontents as $monthctr=>$tc) {
			foreach($tc as $coverage=>$content) {
				$table	.=	'<tr>';
				$table	.=	'<td>'.date("F",strtotime(date("Y")."-".$monthctr."-01")).' '.$coverage.'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['salary']).'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['tax']).'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['sss']).'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['ph']).'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['pi']).'</td>';
				$table	.=	'</tr>';
				$total['salary']	+=	$content['salary'];
				$total['sss']		+=	$content['sss'];
				$total['ph']		+=	$content['ph'];
				$total['pi']		+=	$content['pi'];
			}
		}

		$total_income	=	$total['salary'];
		$total_deduction=	$total['sss'] + $total['ph'] + $total['pi'];

		$data['orientation']	=	"P";
		$data['pagetype']		=	"letter";
		$data['title']			=	"Annualization Report";
		$data['content']		=	'
		<style type=\"text/css\">
				 h1 { font-size:180%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 140%; text-align:center}
				 td {font-size:130%;text-align:center}
			</style>
		<h1>'.date("Y").' Annualization Report of '.$e['full_name'].'</h1>
		<table border="1" cellpadding="2">
		<thead>
		<tr style="background-color:#000; color:#FFFFFF;" >
		<th></th>
		<th>Salary</th>
		<th>Tax</th>
		<th>SSS</th>
		<th>PhilHealth</th>
		<th>PagIbig</th>
		</tr>
		</thead>
		<tbody>
		'.$table.'
		</tbody>
		</table>
		';
		//echo $data['content'];die();
		$this->load->view('pdf-container.php',$data);
	}
	public function payroll_report(){
	$table = "<style type=\"text/css\">
				 h1 { font-size:180%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 140%; text-align:center}
				 td {font-size:130%;text-align:center}
			</style>";
	$total_gross=0;
	$total_tax=0;
	$total_sss=0;
	$total_tax=0;
	$total_ph=0;
	$total_pi=0;
	$total_loan=0;
	$total_net_pay=0;
	$total_tax=0;
	if(empty($_POST)) {
		$this->Abas->sysMsg("warnmsg", "Report generation failed, please try again!");
		$this->Abas->redirect($_SERVER['HTTP_REFERER']);
	}else{

		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

		$employee_id = $this->Mmm->sanitize($_POST['employee2']);
		$year = $this->Mmm->sanitize($_POST['year2']);

		$sql = "SELECT hpd.*, p.id, p.payroll_date, p.payroll_coverage FROM hr_payroll_details AS hpd JOIN hr_payroll AS p ON hpd.payroll_id=p.id WHERE emp_id='".$employee_id."' AND p.payroll_date=".$year. " ORDER BY p.id DESC";
		$query	=	$this->db->query($sql);
		$payrolls = $query->result_array();

		$employee = $this->Abas->getEmployee($employee_id);

		$table .='<h1>'.$year.' Payroll Summary Report of '.$employee['full_name'].'</h1><br>';

		$table .= '<table border="1" cellpadding="5">
					<thead>
						<tr>
							<th><b>Payroll Coverage</b></th>
							<th><b>Rate</b></th>
							<th><b>Gross Salary</b></th>
							<th><b>Tax</b></th>
							<th><b>SSS</b></th>
							<th><b>PhilHealth</b></th>
							<th><b>Pag-ibig</b></th>
							<th><b>Loans</b></th>
							<th><b>Net-Pay</b></th>
						</tr>
					</thead><tbody>';
		foreach($payrolls as $row) {
			$gross = ($row['salary'] + $row['others'] + $row['bonus'] + $row['allowance']) - $row['absences_amount'] - $row['undertime_amount'];
			$overtime = $row['regular_overtime_amount'] + $row['restday_overtime_amount'] + $row['specialholiday_overtime_amount']+ $row['specialholiday_restday_overtime_amount']+ $row['legalholiday_overtime_amount']+ $row['legalholiday_restday_overtime_amount'] +  $row['night_differential_amount']; 
			$gross = $gross + $overtime;
			$contributions = $row['tax'] + $row['sss_contri_ee']+$row['phil_health_contri']+$row['pagibig_contri']; 
			$loans = $row['elf_loan'] + $row['sss_loan'] + $row['pagibig_loan'] + $row['cash_advance'];
			$net_pay = (($gross - $contributions) - $loans);
				$table	.=	'<tr>';
					$table	.=	'<td>'.date('F',strtotime($row['payroll_date'])).' - '.$row['payroll_coverage'].'</td>';
					$table	.=	'<td>'.number_format($row['salary'],2,'.',',').'</td>';
					$table	.=	'<td>'.number_format($gross,2,'.',',').'</td>';
					$table	.=	'<td>('.number_format($row['tax'],2,'.',',').')</td>';
					$table	.=	'<td>('.number_format($row['sss_contri_ee'],2,'.',',').')</td>';
					$table	.=	'<td>('.number_format($row['phil_health_contri'],2,'.',',').')</td>';
					$table	.=	'<td>('.number_format($row['pagibig_contri'],2,'.',',').')</td>';
					$table	.=	'<td>('.number_format($loans,2,'.',',').')</td>';
					$table	.=	'<td>'.number_format($net_pay,2,'.',',').'</td>';
				$table	.=	'</tr>';

				$total_gross = $total_gross + $gross;
				$total_tax = $total_tax + $row['tax'];
				$total_sss = $total_sss + $row['sss_contri_ee'];
				$total_ph = $total_ph + $row['phil_health_contri'];
				$total_pi = $total_pi + $row['pagibig_contri'];
				$total_loan = $total_loan + $loans;
				$total_net_pay = $total_net_pay + $net_pay;

		}
		$table .='</tbody>
					<tr>
						<td colspan="2"><b>TOTAL</b></td>
						<td>'.number_format($total_gross,2,'.',',').'</td>
						<td>('.number_format($total_tax,2,'.',',').')</td>
						<td>('.number_format($total_sss,2,'.',',').')</td>
						<td>('.number_format($total_ph,2,'.',',').')</td>
						<td>('.number_format($total_pi,2,'.',',').')</td>
						<td>('.number_format($total_loan,2,'.',',').')</td>
						<td>'.number_format($total_net_pay,2,'.',',').'</td>
					 </tr>
		</table>';

		$data['orientation']	=	"P";
		$data['title']			=	"Payroll Summary Report";
		$data['pagetype']		=	"legal";
		$data['content']		=	$table;
		$this->load->view('pdf-container.php',$data);
	}
}
	public function loan_report() {
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$data=array();
		$filter=$company="";
		if($_POST['loantype']=="ELF" || $_POST['loantype']=="PagIbig" || $_POST['loantype']=="Cash Advance" || $_POST['loantype']=="SSS"){ 
			$filter	=	" AND loan_type='".$_POST['loantype']."'"; 
		}
		$loantype = $_POST['loantype'];
		$loans	=	$this->db->query("SELECT * FROM hr_loans WHERE stat=1".$filter." ORDER BY date_loan DESC");
		$loans	=	$loans->result_array();
		if(!empty($loans)) {
			foreach($loans as $loanctr=>$l) {
				$amount_paid	=	0;
				$sqlamount_paid	=	$this->db->query("SELECT SUM(amount) AS total_amount FROM hr_loan_payments WHERE loan_id=".$l['id']);
				if($sqlamount_paid) {
					$sqlamount_paid	=	(array)$sqlamount_paid->row();
					$amount_paid	=	$sqlamount_paid['total_amount'];
				}
				$e								=	$this->Abas->getEmployee($l['emp_id']);
				$loans[$loanctr]['employee']	=	$e;
				$loans[$loanctr]['amount_paid']	=	$amount_paid;
				if($amount_paid>=$l['amount_loan']) { unset($loans[$loanctr]); }
				if($_POST['company']) {
					$company	=	$this->Abas->getCompany($_POST['company']);
					if($e['company_id']!=$company->id) { unset($loans[$loanctr]); }
				}
			}
		}
		$loantable ="<style type=\"text/css\">
						 h1 { font-size:180%;text-align:center; }
						 h2,h3 { text-align:center;font-size:130% }	
						 h5 span { border-bottom: double 3px; }
						 th {background-color: black;color: white; font-size: 140%; text-align:center}
						 td {font-size:130%;text-align:center}
					</style>";
		$loantable	.= "<h1>Loan Summary Report for ".$company->name."</h1>";		
		$loantable	.=	"<table border=\"1\" cellpadding=\"5\">";
		$loantable	.=	"<tr>";
		$loantable	.=	"<th>Name</th>";
		$loantable	.=	"<th>Position</th>";
		if($company==""){ 
			$loantable	.=	"<th>Company</th>"; 
		}
		$loantable	.=	"<th>Date Loaned</th>";
		$loantable	.=	"<th>Amount Loaned</th>";
		$loantable	.=	"<th>Amount Paid</th>";
		$loantable	.=	"<th>Remaining Balance</th>";
		if($loantype!=""){ 
			$loantable	.=	"<th>Loan Type</th>"; 
		}
		$loantable	.=	"</tr>";
		$total_amount_loaned=$total_amount_paid=$remaining_balance=0;
		if(!empty($loans)) {
			foreach($loans as $lctr=>$l){
				$loantable	.=	"<tr>";
				$loantable	.=	"<td>".$l['employee']['full_name']."</td>";
				$loantable	.=	"<td>".$l['employee']['position_name']."</td>";
				if($company==""){ 
					$loantable	.=	"<td>".$l['employee']['company_name']."</td>"; 
				}
				$loantable	.=	"<td>".date("j F Y", strtotime($l['date_loan']))."</td>";
				$loantable	.=	"<td>".number_format((float)$l['amount_loan'],2)."</td>";
				$loantable	.=	"<td>".number_format($l['amount_paid'],2)."</td>";
				$loantable	.=	"<td>".number_format(($l['amount_loan']-$l['amount_paid']),2)."</td>";
				if($loantype!="") $loantable	.=	"<td>".$l['loan_type']."</td>";
				$loantable	.=	"</tr>";
				$total_amount_loaned = $total_amount_loaned+$l['amount_loan'];
				$total_amount_paid = $total_amount_paid+$l['amount_paid'];
				$remaining_balance = $remaining_balance + ($l['amount_loan']-$l['amount_paid']);
			}
			$loantable	.=	"<tr>";
			$loantable	.=	"<td colspan=\"3\"><b>TOTAL</b></td>";
			$loantable	.=	"<td>".number_format($total_amount_loaned,2)."</td>";
			$loantable	.=	"<td>".number_format($total_amount_paid,2)."</td>";
			$loantable	.=	"<td>".number_format($remaining_balance,2)."</td>";
			$loantable	.=	"</tr>";
		}
		else {
			$loantable	.=	"<tr><td colspan=99>No loans found!</td></tr>";
		}
		$loantable		.=	"</table>";

		$data['orientation']	=	"P";
		$data['title']			=	"Loan Summary Report";
		$data['pagetype']		=	"legal";
		$data['content']		=	$loantable;
		//echo $company->name;
		$this->load->view('pdf-container.php',$data);
	}

	public function payroll_autocomplete_list($status,$company_id=null){
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		if($status=='approved'){
			$status=1;
		}
		if($company_id!=null){
			$sql	=	"SELECT * FROM hr_payroll WHERE id LIKE '%".$search."%' AND company_id=".$company_id." AND locked=".$status." AND is_cleared=0 ORDER BY id LIMIT 0,12";	
		}else{
			$sql	=	"SELECT * FROM hr_payroll WHERE id LIKE '%".$search."%' AND locked=".$status."  AND is_cleared=0 ORDER BY id LIMIT 0,12";
		}
		$pr	=	$this->db->query($sql);
		if($pr) {
			if($pr->row()) {
				$pr	=	$pr->result_array();
				$ret	=	array();
				foreach($pr as $ctr=>$i) {
					$ret[$ctr]['label']	=	"Payroll Transaction Code No. ".$i['id']." | Control No. ".$i['control_number']. " (". $i['payroll_coverage']." - ".date("F Y",strtotime($i['payroll_date']."-01")).")";
					$ret[$ctr]['value']	=	$i['id'];
					
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
	}
	public function get_payroll_periods_per_company($company=''){
		$ret = $this->Payroll_model->getPayrollByCompany($company);
		foreach($ret as $ctr=>$x){
			$x->payroll_date = date('F Y',strtotime($x->payroll_date));
		}
		echo json_encode($ret);
		exit();
	}
	public function contribution_report(){
		if($_POST['payroll_period']){
			if($_POST['type']=='SSS'){
				redirect(HTTP_PATH.'payroll_history/sss_printable/'.$_POST['payroll_period']);
			}elseif($_POST['type']=='PhilHealth'){
				redirect(HTTP_PATH.'payroll_history/ph_printable/'.$_POST['payroll_period']);
			}elseif($_POST['type']=='Pag-ibig'){
				redirect(HTTP_PATH.'payroll_history/pi_printable/'.$_POST['payroll_period']);
			}
		}
	}
}
?>
