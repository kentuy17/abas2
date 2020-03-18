<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_history extends CI_Controller {

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
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home");echo "<script>window.location=".HTTP_PATH."</script>"; }
	}
	public function index()	{$data=array();
		$this->Abas->checkPermissions("payroll|view");
		unset($_SESSION['payroll']);
		$all_employees				=	$this->Hr_model->getAllEmployees();
		$data['companies']			=	$this->Abas->getCompanies();
		$data['payroll_summaries']	=	$this->Payroll_model->getAllPayrolls();
		$data['viewfile']	=	"payroll/payroll_view.php";
		$this->load->view('container.php',$data);
	}
	public function payslips($type="payroll", $id) {$data=array();
		$this->Abas->checkPermissions("payroll|view_staff_payroll");
		// echo "<pre>";print_r($summary);echo "</pre>";
		if($type=="payroll") {
			$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
			if($summary!=false) {

				if($summary->row()) {
					$summary	=	$summary->row();
					$data['summary'] = $summary;
					$details	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE payroll_id=".$id);

					if($details!=false) {
						if($details->result_array()) {
							$details	=	$details->result_array();
							$data['details']	=	$details;
						}
					}
					// echo "<pre>";print_r($details);echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Payroll record not found!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		elseif($type=="employee") {
			$details	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE id=".$id);
			if($details!=false) {
				if($details->result_array()) {
					$details	=	$details->result_array();
					$data['details']	=	$details;
				}
			}
			// echo "<pre>";print_r($employee);echo "</pre>";
		}
		$mainview				=	"payroll/payslip_history.php";
		$data['payroll_id']		=	$id;
		$this->load->view($mainview,$data);
	}
	public function bir_report($id) {$data=array();
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$data['payroll_id']		=	$id;
		$mainview				=	"payroll/tax_report_history.php";
		$this->load->view($mainview,$data);
	}
	public function bank_report($id) {$data=array();
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$mainview				=	"payroll/bank_report_history.php";
		$data['payroll_id']		=	$id;
		$this->load->view($mainview,$data);
	}
	public function sss_report($id) {$data=array();
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$mainview				=	"payroll/sss_report_history.php";
		$data['payroll_id']		=	$id;
		$this->load->view($mainview,$data);
	}
	public function ph_report($id) {$data=array();
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$mainview				=	"payroll/ph_report_history.php";
		$data['payroll_id']		=	$id;
		$this->load->view($mainview,$data);
	}
	public function pi_report($id) {$data=array();
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$data['all_employees']	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$mainview				=	"payroll/pi_report_history.php";
		$data['payroll_id']		=	$id;
		$this->load->view($mainview,$data);
	}
	public function view($payroll_id) {$data=array();
		$this->Abas->checkPermissions("payroll|view");
		$summary				=	"";
		$details				=	"";
		$sql_summary			=	"SELECT * FROM hr_payroll WHERE id=".$payroll_id;
		$sql_summary			=	$this->db->query($sql_summary);
		if($sql_summary!=false) {
			if($sql_summary->row()) {
				$summary		=	$sql_summary->row();
			}
		}
		if($summary->company_id==10) {
			$this->Abas->checkPermissions("payroll|view_staff_payroll");
		}
		$sql_details			=	"SELECT e.last_name, p.*, s.sorting FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON e.id=p.emp_id JOIN positions AS s ON e.position=s.id WHERE payroll_id=".$payroll_id." ORDER BY p.vessel_id DESC, s.sorting ASC, e.last_name ASC";
		$sql_details			=	$this->db->query($sql_details);
		if($sql_details!=false) {
			if($sql_details->row()) {
				$details		=	$sql_details->result_array();
			}
		}

		$data['summary']		=	$summary;
		$data['details']		=	$details;
		//$mainview				=	"container.php";
		$mainview				=	"gentlella_container.php";
		$data['viewfile']		=	"payroll/payroll_history.php";
		$this->load->view($mainview,$data);
	}

	public function summary($payroll_id) {$data=array();
		$summary				=	"";
		$details				=	"";
		$sql_summary			=	"SELECT * FROM hr_payroll WHERE id=".$payroll_id;
		$sql_summary			=	$this->db->query($sql_summary);
		if($sql_summary!=false) {
			if($sql_summary->row()) {
				$summary		=	$sql_summary->row();
			}
		}
		$sql_details			=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$payroll_id;
		$sql_details			=	$this->db->query($sql_details);
		if($sql_details!=false) {
			if($sql_details->row()) {
				$details		=	$sql_details->result_array();
			}
		}

		$data['payroll_buttons']=	false;
		$data['summary']		=	$summary;
		$data['details']		=	$details;
		$mainview				=	"payroll/payroll_history.php";
		$this->load->view($mainview,$data);
	}

	public function summary_printable($payroll_id) {$data=array();
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");

		$summary				=	"";
		$details				=	"";
		$sql_summary			=	"SELECT * FROM hr_payroll WHERE id=".$payroll_id;
		$sql_summary			=	$this->db->query($sql_summary);
		if($sql_summary!=false) {
			if($sql_summary->row()) {
				$summary		=	$sql_summary->row();
			}
		}
		$sql_details			=	"SELECT e.last_name, p.*, s.sorting FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON e.id=p.emp_id JOIN positions AS s ON e.position=s.id WHERE payroll_id=".$payroll_id." ORDER BY p.vessel_id DESC, s.sorting ASC, e.last_name ASC";
		$sql_details			=	$this->db->query($sql_details);
		if($sql_details!=false) {
			if($sql_details->row()) {
				$details		=	$sql_details->result_array();
			}
		}


		$company	=	$this->Abas->getCompany($summary->company_id);
		$creator	=	$this->Abas->getUser($summary->created_by);
		$approver	=	$this->Abas->getUser($summary->approved_by);
		$table		=	"";
		$payroll	=	array("salary"=>0, "allowance"=>0, "others"=>0, "wtax"=>0, "sss"=>0, "ph"=>0, "pi"=>0, "elf"=>0, "loan"=>0, "netpay"=>0);
		$payroll_total	=	array(
					"salary"=>0,
					"allowance"=>0,
					"absences"=>0,
					"ut"=>0,
					"regularOT"=>0,
					"restdayOT" => 0,
					"legalholidayOT" => 0,
					"legalholiday_restdayOT" => 0,
					"specialholidayOT" => 0,
					"specialholiday_restdayOT" => 0,
					"holidayOT"=>0,
					"nightDiff"=>0,
					"bonus"=>0,
					"others"=>0,
					"gross"=>0,
					"wtax"=>0,
					"sss"=>0,
					"ph"=>0,
					"pi"=>0,
					"sssloan"=>0,
					"piloan"=>0,
					"elfloan"=>0,
					"ca"=>0,
					"elf"=>0,
					"netpay"=>0
				);
		$s_total = 0;
		if(!empty($details)) {
			// $old_dept		=	0;
			$old_vessel		=	0;
			$colspan = 20;


			foreach($details AS $ctr=>$display) {
				// $this->Mmm->debug($display);
				$employee_details	=	$this->Abas->getEmployee($display['emp_id']);
				if($display['salary'] > 0) {
					$color="";
					if($display['net_pay'] <= 1000) { $color="background-color:#FFFF55;"; }
					if($display['net_pay'] <= 0) { $color="background-color:#FF5555;"; }
					$display['emp_id']		=	!empty($employee_details['employee_id'])?$employee_details['employee_id']:"-";
					$display['full_name']	=	!empty($employee_details['full_name'])?$employee_details['full_name']:"-";
					$display['position']	=	!empty($employee_details['position_name'])?$employee_details['position_name']:"-";
					$holiday_overtime = $display['legalholiday_overtime_amount']+$display['legalholiday_restday_overtime_amount']+$display['specialholiday_overtime_amount']+$display['specialholiday_restday_overtime_amount'];
					if($holiday_overtime==0){
						$holiday_overtime =  $display['holiday_overtime_amount'];
					}
					$subtotal	=	$display['salary'] + $display['allowance'] + $display['regular_overtime_amount'] +$display['restday_overtime_amount'] + $holiday_overtime + $display['night_differential_amount']  + $display['bonus'] + $display['others'] - ($display['absences_amount'] + $display['undertime_amount']);
					// $subtotal=	$display['salary']+$display['allowance'];
					// $dept	=	$employee_details['department'];
					$vessel		=	$employee_details['vessel_id'];
					$taxcode	=	$employee_details['tax_code'];
					$loans		=	$display['sss_loan'] + $display['pagibig_loan'] + $display['cash_advance'];
					$net_pay 	=	$subtotal - ($display['elf_contri']+$display['elf_loan']+$display['sss_loan']+$display['cash_advance']+$display['sss_contri_ee']+$display['phil_health_contri']+$display['pagibig_contri']+$display['pagibig_loan']+$display['tax']);

					if($vessel!=$old_vessel) {
						// $old_dept	=	$employee_details['department'];

						$old_vessel	=	$employee_details['vessel_id'];
						$vessel_total[$vessel]	=array(
										"salary"=>0,
										"allowance"=>0,
										"absences"=>0,
										"ut"=>0,
										"regularOT"=>0,
										"restdayOT" => 0,
										"legalholidayOT" => 0,
										"legalholiday_restdayOT" => 0,
										"specialholidayOT" => 0,
										"specialholiday_restdayOT" => 0,
										"holidayOT"=>0,
										"nightDiff"=>0,
										"bonus"=>0,
										"others"=>0,
										"gross"=>0,
										"wtax"=>0,
										"sss"=>0,
										"ph"=>0,
										"pi"=>0,
										"sssloan"=>0,
										"piloan"=>0,
										"elfloan"=>0,
										"ca"=>0,
										"elf"=>0,
										"netpay"=>0
									);
						$table.=	'
							<tr bgcolor="#bdbdbd">
								<td colspan="'.(($summary->payroll_coverage=="2nd-half")?27:28).'">'.$employee_details['vessel_name'].'</td>
							</tr>

						';
					}
					$payroll_total['salary']		=	$payroll_total['salary'] + $display['salary'];
					$payroll_total['allowance']	=	$payroll_total['allowance'] + $display['allowance'];
					$payroll_total['absences']	=	$payroll_total['absences'] + $display['absences_amount'];
					$payroll_total['ut']			=	$payroll_total['ut'] + $display['undertime_amount'];
					$payroll_total['regularOT']	=	$payroll_total['regularOT'] + $display['regular_overtime_amount'];
					$payroll_total['restdayOT']	=	$payroll_total['restdayOT'] + $display['restday_overtime_amount'];
					$payroll_total['legalholidayOT']	=	$payroll_total['legalholidayOT'] + $display['legalholiday_overtime_amount'];
					$payroll_total['legalholiday_restdayOT']	=	$payroll_total['legalholiday_restdayOT'] + $display['legalholiday_restday_overtime_amount'];
					$payroll_total['specialholidayOT']	=	$payroll_total['specialholidayOT'] + $display['specialholiday_overtime_amount'];
					$payroll_total['specialholiday_restdayOT']	=	$payroll_total['specialholiday_restdayOT'] + $display['specialholiday_restday_overtime_amount'];
					$payroll_total['holidayOT']	=	$payroll_total['holidayOT'] + $holiday_overtime;
					$payroll_total['nightDiff']	=	$payroll_total['nightDiff'] + $display['night_differential_amount'];
					$payroll_total['bonus']			=	$payroll_total['bonus'] + $display['bonus'];
					$payroll_total['others']		=	$payroll_total['others'] + $display['others'];
					$payroll_total['gross']			=	$payroll_total['gross'] + $subtotal;
					$payroll_total['wtax']			=	$payroll_total['wtax'] + $display['tax'];
					$payroll_total['sss']			=	$payroll_total['sss'] + $display['sss_contri_ee'];
					$payroll_total['ph']			=	$payroll_total['ph'] + $display['phil_health_contri'];
					$payroll_total['pi']			=	$payroll_total['pi'] + $display['pagibig_contri'];
					$payroll_total['sssloan']		=	$payroll_total['sssloan'] + $display['sss_loan'];
					$payroll_total['piloan']		=	$payroll_total['piloan'] + $display['pagibig_loan'];
					$payroll_total['elfloan']		=	$payroll_total['elfloan'] + $display['elf_loan'];
					$payroll_total['ca']			=	$payroll_total['ca'] + $display['cash_advance'];
					$payroll_total['elf']			=	$payroll_total['elf'] + $display['elf_contri'];
					$payroll_total['netpay']		=	$payroll_total['netpay'] + $net_pay;

					$vessel_total[$vessel]['salary']	+=	$display['salary'];
					$vessel_total[$vessel]['allowance']	+=	$display['allowance'];
					$vessel_total[$vessel]['absences']	+=	$display['absences_amount'];
					$vessel_total[$vessel]['ut']		+=	$display['undertime_amount'];
					$vessel_total[$vessel]['regularOT']	+=	$display['regular_overtime_amount'];
					$vessel_total[$vessel]['restdayOT']	+=	$display['restday_overtime_amount'];
					$vessel_total[$vessel]['legalholidayOT']	+=	$display['legalholiday_overtime_amount'];
					$vessel_total[$vessel]['legalholiday_restdayOT']	+=	$display['legalholiday_restday_overtime_amount'];
					$vessel_total[$vessel]['specialholidayOT']	+=	$display['specialholiday_overtime_amount'];
					$vessel_total[$vessel]['specialholiday_restdayOT']	+=	$display['specialholiday_restday_overtime_amount'];
					$vessel_total[$vessel]['holidayOT']	+=	$holiday_overtime;
					$vessel_total[$vessel]['nightDiff']	+=	$display['night_differential_amount'];
					$vessel_total[$vessel]['bonus']		+=	$display['bonus'];
					$vessel_total[$vessel]['others']	+=	$display['others'];
					$vessel_total[$vessel]['gross']		+=	$subtotal;
					$vessel_total[$vessel]['wtax']		+=	$display['tax'];
					$vessel_total[$vessel]['sss']		+=	$display['sss_contri_ee'];
					$vessel_total[$vessel]['ph']		+=	$display['phil_health_contri'];
					$vessel_total[$vessel]['pi']		+=	$display['pagibig_contri'];
					$vessel_total[$vessel]['sssloan']	+=	$display['sss_loan'];
					$vessel_total[$vessel]['piloan']	+=	$display['pagibig_loan'];
					$vessel_total[$vessel]['elfloan']	+=	$display['elf_loan'];
					$vessel_total[$vessel]['ca']		+=	$display['cash_advance'];
					$vessel_total[$vessel]['elf']		+=	$display['elf_contri'];
					$vessel_total[$vessel]['netpay']	+=	$net_pay;

					$table	.=	'<tr style="'.$color.' font-size:8px;" align="right"> ';
					$table	.=	'<td align="center">'.$display['emp_id'].'</td>';
					$table	.=	'<td align="left">'.$display['full_name'].'</td>';
					$table	.=	'<td align="left">'.ucwords(strtolower($display['position'])).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['salary']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['allowance']).'</td>';
					$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['absences_amount']).")</td>"; // absences
					$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['undertime_amount']).')</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['regular_overtime_amount']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['restday_overtime_amount']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['legalholiday_overtime_amount']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['legalholiday_restday_overtime_amount']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['specialholiday_overtime_amount']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['specialholiday_restday_overtime_amount']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($holiday_overtime).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['night_differential_amount']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['bonus']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($display['others']).'</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($subtotal).'</td>';
					$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['tax']).')</td>';
					if($summary->payroll_coverage=="2nd-half") {
						$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['sss_contri_ee']).')</td>';
						$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['phil_health_contri']).')</td>';
					}
					elseif($summary->payroll_coverage=="1st-half") {
						$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['pagibig_contri']).')</td>';
					}
					$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['sss_loan']).')</td>';
					$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['pagibig_loan']).')</td>';
					$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['elf_loan']).')</td>';
					$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['cash_advance']).')</td>';
					$table	.=	'<td align="right">('.$this->Abas->currencyFormat($display['elf_contri']).')</td>';
					$table	.=	'<td align="right">'.$this->Abas->currencyFormat($net_pay).'</td>';
					$table	.=	'</tr>';

					$payroll['salary']		=	$payroll['salary'] + $display['salary'];
					$payroll['allowance']	=	$payroll['allowance'] + $display['allowance'];
					$payroll['others']	=	$payroll['others'] + $display['others'];
					$payroll['wtax']	=	$payroll['wtax'] + $display['tax'];
					$payroll['sss']	=	$payroll['sss'] + $display['sss_contri_ee'];
					$payroll['ph']	=	$payroll['ph'] + $display['phil_health_contri'];
					$payroll['pi']	=	$payroll['pi'] + $display['pagibig_contri'];
					$payroll['elf']	=	$payroll['elf'] + $display['elf_contri'];
					$payroll['netpay']	=	$payroll['netpay'] + $net_pay;

					if(isset($details[$ctr+1])) {
						$next_emp		=	$this->Abas->getEmployee($details[$ctr+1]['emp_id']);
						$next_vessel_id	=	$next_emp['vessel_id'];
						$next_vessel	=	$this->Abas->getVessel($next_vessel_id);
						if($next_vessel->id!=$old_vessel) { // next vessel
							$table	.=	'<tr align="right">';
								$table	.=	'<td colspan="3">Sub Totals:</td>';
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['salary']).'</td>';
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['allowance']).'</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['absences']).')</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['ut']).')</td>';
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['regularOT']).'</td>';
								$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['restdayOT'])."</td>";
								$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['legalholidayOT'])."</td>";
								$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['legalholiday_restdayOT'])."</td>";
								$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['specialholidayOT'])."</td>";
								$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['specialholiday_restdayOT'])."</td>";
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['holidayOT']).'</td>';
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['nightDiff']).'</td>';
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['bonus']).'</td>';
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['others']).'</td>';
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['gross']).'</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['wtax']).')</td>';
								if($summary->payroll_coverage == "2nd-half") {
									$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['sss']).')</td>';
									$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['ph']).')</td>';
								}
								if($summary->payroll_coverage == "1st-half") {
									$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['pi']).')</td>';
								}
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['sssloan']).')</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['piloan']).')</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['elfloan']).')</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['ca']).')</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['elf']).')</td>';
								$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['netpay']).'</td>';
							$table	.=	'</tr>';
						}
					}
					else { // last vessel
						$table	.=	'<tr align="right">';
							$table	.=	'<td colspan="3">Sub Totals:</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['salary']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['allowance']).'</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['absences']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['ut']).')</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['regularOT']).'</td>';
							$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['restdayOT'])."</td>";
							$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['legalholidayOT'])."</td>";
							$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['legalholiday_restdayOT'])."</td>";
							$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['specialholidayOT'])."</td>";
							$table	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel]['specialholiday_restdayOT'])."</td>";
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['holidayOT']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['nightDiff']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['bonus']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['others']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['gross']).'</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['wtax']).'</td>';
							if($summary->payroll_coverage == "2nd-half") {
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['sss']).')</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['ph']).')</td>';
							}
							if($summary->payroll_coverage == "1st-half") {
								$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['pi']).')</td>';
							}
							$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['sssloan']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['piloan']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['elfloan']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['ca']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($vessel_total[$vessel]['elf']).')</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($vessel_total[$vessel]['netpay']).'</td>';
						$table	.=	'</tr>';

						$table	.=	'<tr align="right"  bgcolor="#2b2b2b" style="color:#FFFFFF">';
							$table	.=	'<td colspan="3">Grand Total:</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['salary']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['allowance']).'</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['absences']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['ut']).')</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['regularOT']).'</td>';
							$table	.=	"<td>".$this->Abas->currencyFormat($payroll_total['restdayOT'])."</td>";
							$table	.=	"<td>".$this->Abas->currencyFormat($payroll_total['legalholidayOT'])."</td>";
							$table	.=	"<td>".$this->Abas->currencyFormat($payroll_total['legalholiday_restdayOT'])."</td>";
							$table	.=	"<td>".$this->Abas->currencyFormat($payroll_total['specialholidayOT'])."</td>";
							$table	.=	"<td>".$this->Abas->currencyFormat($payroll_total['specialholiday_restdayOT'])."</td>";
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['holidayOT']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['nightDiff']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['bonus']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['others']).'</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['gross']).'</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['wtax']).')</td>';
							if($summary->payroll_coverage == "2nd-half") {
								$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['sss']).')</td>';
								$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['ph']).')</td>';
							}
							if($summary->payroll_coverage == "1st-half") {
								$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['pi']).')</td>';
							}
							$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['sssloan']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['piloan']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['elfloan']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['ca']).')</td>';
							$table	.=	'<td>('.$this->Abas->currencyFormat($payroll_total['elf']).')</td>';
							$table	.=	'<td>'.$this->Abas->currencyFormat($payroll_total['netpay']).'</td>';
						$table	.=	'</tr>';

					}


				}




			}


			// $table	.=	"<tr style='cursor:pointer; font-size:10px;'>";
			// $table	.=	"<td class='c-align' colspan='3'>Total</td>";
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['salary'])."</td>";
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['allowance'])."</td>";
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['others'])."</td>";
			// $table	.=	"<td>-</td>";
			// $table	.=	"<td>-</td>";
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['wtax'])."</td>";
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['sss'])."</td>";
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['ph'])."</td>";
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['pi'])."</td>";
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['elf'])."</td>";
			// $table	.=	"<td></td>"; //loans?
			// $table	.=	"<td>".$this->Abas->currencyFormat($payroll['netpay'])."</td>";
			// $table	.=	"</tr>";
		}
		else {
			$table	=	"<tr><td colspan='99'>No details found!</td></tr>";
		}

		$html = '
			<table border="0" cellspacing="2">
				<tr>
					<td><img src="'. PDF_LINK . 'assets/images/AvegaLogo.jpg" alt="Avega_Logo" style="width:120px;height:80px;"></td>
					<td colspan="8">
						<h1 align="left" style="font-size:20px">  '.$company->name.'</h1>
						<h2 align="left">  '.$company->address.'</h2>
						<h3 align="left">  '.$company->telephone_no.'</h3>
					</td>
					<td>
						Transaction Code No. '.$summary->id.'
					</td>
				</tr>
				<tr>
					<td colspan="7"><br><h1 align="left" style="font-size:25px">Payroll Summary</h1></td>
					<td><h1 align="right">Control No. '.$summary->control_number.'</h1></td>
				</tr>
			</table>

			<span style="float:right"><h1>Pay Period: '.$summary->payroll_coverage.' - '.date("F Y",strtotime($summary->payroll_date)).' </h1></span>
			<br><br>

		<table border="1" style="font-size:8px" cellspacing="1" cellpadding="1">

		<tr align="center" bgcolor="#2b2b2b" style="color:#FFFFFF">
			<th class="text-center" width="4%">EID</th>
			<th class="text-center" width="4%">Name</th>
			<th class="text-center" width="4%">Position</th>
			<th class="text-center" width="4%">Basic</th>
			<th class="text-center" width="4%">Allowance</th>
			<th class="text-center" width="4%">Absences</th>
			<th class="text-center" width="4%">Late/UT</th>
			<th class="text-center" width="4%">Regular OT</th>
			<th class="text-center" width="4%">Rest Day OT</th>
			<th class="text-center" width="4%">Legal Holiday OT</th>
			<th class="text-center" width="4%">Legal Holiday on Rest Day OT</th>
			<th class="text-center" width="4%">Special Holiday OT</th>
			<th class="text-center" width="4%">Special Holiday on Rest Day OT</th>
			<th class="text-center" width="4%">Total Holiday OT</th>
			<th class="text-center" width="4%">Night Differential</th>
			<th class="text-center" width="2%">Bonus</th>
			<th class="text-center" width="4%">Adjustments/Others</th>
			<th class="text-center" width="4%">Gross</th>
			<th class="text-center" width="4%">W-Tax</th>
			';
			if($summary->payroll_coverage=="2nd-half") {
				$html	.=	'<th class="text-center" width="4%">SSS</th>
				<th class="text-center" width="4%">PHIC</th>';
			}
			elseif($summary->payroll_coverage=="1st-half") {
				$html	.=	'<th class="text-center" width="4%">HMDF</th>';
			}
			$html	.=	'
			<th class="text-center" width="4%">SSS Loan</th>
			<th class="text-center" width="4%">HMDF Loan</th>
			<th class="text-center" width="2%">ELF Loan</th>
			<th class="text-center" width="4%">Cash Advance</th>
			<th class="text-center" width="2%">ELF</th>
			<th class="text-center" width="4%">Net Pay</th>
		</tr>

		'.$table.'

		</table>
		
		<div class = "panel-footer">
			<div style="margin-bottom:10px">
				<table width="100%" cellpadding="1px" cellspacing="5">
					<thead>
						<tr>
							<th width="35%" class="text-left"><b>Prepared by:</b><br><br></th>
							<th width="35%" class="text-left"><b>Checked by:</b><br><br></th>
							<th width="30%" class="text-left"><b>Approved by:</b><br><br></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th width="35%" class="text-left"><u>'.$creator['full_name'].'</u></th>
							<th width="35%" class="text-left">_________________________________</th>
							<th width="30%" class="text-left"><u>'.$approver['full_name'].'</u></th>
						</tr>
						<tr>
							<th width="35%" class="text-left">Signature Over Printed Name</th>
							<th width="35%" class="text-left">Signature Over Printed Name</th>
							<th width="30%" class="text-left">Signature Over Printed Name</th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		';
		$data['orientation']	=	"L";
		$data['title']			=	"Payroll Summary";
		$data['pagetype']		=	"legal";
		$data['content']		=	$html;
		$this->load->view('pdf-container.php',$data);
	}

	public function bir_printable($id) {$data=array();
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$all_employees	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		$company	=	$this->Abas->getCompany($summary->company_id);
		$table	=	"";
		$total	=	0;
		$ctr=1;
		foreach($all_employees as $ae) {
			// $this->Mmm->debug($ae);
			$employee_data	=	$this->Abas->getEmployee($ae->emp_id);

			// echo "<h1>".$ae['full_name']."</h1>";
			if($employee_data['salary_rate']!=0) {
				$table	.=	"<tr>";
				$table	.= "<td style='text-align:center;'>".$ctr."</td>";
				$table	.= "<td style='text-align:center;'>".$employee_data['employee_id']."</td>";
				$table	.= "<td style='text-align:left;'>".$employee_data['full_name']."</td>";
				$table	.= "<td style='text-align:center;'>".$employee_data['tin_num']."</td>";
				//$table	.= "<td>".$this->Abas->currencyFormat($withholding['per_cutoff']['tax_payable'])."</td>";
				// $table	.= "<td>".$this->Abas->currencyFormat($taxable['per_cutoff'])."</td>";
				$table	.= "<td>".$this->Abas->currencyFormat($ae->tax)."</td>";
				$table	.=	"</tr>";
				$total	=	$total + $ae->tax;
				$ctr++;
			}
		}
		$table	.=	'<tr><td colspan="4"><b>GRAND TOTAL</b></td><td>'.$this->Abas->currencyFormat($total).'</td></tr>';
		// echo "<table>".$table."</table>";die();
		$data['orientation']	=	"P";
		$data['pagetype']		=	"legal";
		$data['title']			=	"BIR W-Tax Summary";
		$data['content']		=	'<style type=\"text/css\">
				 h1 { font-size:240%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 120%; text-align:center}
				 td {font-size:130%;text-align:center}
			</style>
		<div>
			<h1>BIR Witholding Tax Summary for '.$company->name.'</h1>
			<h2>Pay Period: '.$summary->payroll_coverage.' - '.date("F Y",strtotime($summary->payroll_date)).'</h2>
		</div>
		<table class="table table-condensed table-bordered" style="font-size:8px" border="1">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Employee ID</th>
					<th class="text-center">Name</th>
					<th class="text-center">TIN</th>
					<th class="text-center">Withholding Tax</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
		';
		$this->load->view('pdf-container.php',$data);
	}
	public function bank_printable($id) {$data=array();
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$all_employees	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		$company	=	$this->Abas->getCompany($summary->company_id);
		$table	=	"";
		$total	=	0;
		$empctr	=	0;
		$ctr = 1;
		foreach($all_employees as $ae) {
			$employee_data	=	$this->Abas->getEmployee($ae->emp_id);
			if($employee_data['salary_rate']!=0) {

				$bankaccount	=	$employee_data['bank_account_num'];
				if($bankaccount!="") {
					$net_pay=	($ae->salary + $ae->allowance + $ae->regular_overtime_amount + $ae->restday_overtime_amount + $ae->holiday_overtime_amount - $ae->absences_amount - $ae->undertime_amount) + $ae->bonus + $ae->others - ($ae->tax + $ae->sss_contri_ee + $ae->phil_health_contri + $ae->pagibig_contri + $ae->elf_contri + $ae->sss_loan + $ae->pagibig_loan + $ae->cash_advance + $ae->elf_loan);
					$value	=	$ae->net_pay - $ae->elf_contri - $ae->elf_loan;
					$table	.=	'<tr>';
					$table	.= '<td style="text-align:center; width:5%">'.$ctr.'</td>';
					$table	.= '<td style="text-align:left; width:50%">'.$employee_data['full_name'].'</td>';
					$table	.= '<td style="text-align:leftr; width:25%">'.$bankaccount.'</td>';
					$table	.= '<td style="text-align:right;width:20%">'.$this->Abas->currencyFormat($net_pay).'</td>';
					$table	.=	'</tr>';
					$total	=	$total + $net_pay;
					$empctr++;
					$ctr++;
				}
			}
		}
		$deets	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE payroll_id=".$id." ORDER BY vessel_id DESC");
		if($deets) {
			if($deets->row()) {
				$old_vessel_id	=	0;
				$deets			=	$deets->result_array();
				$subtotal		=	0;
				$gtotal			=	0;
				foreach($deets as $ridectr=>$ae) {
					$net_pay=	($ae['salary'] + $ae['allowance'] + $ae['regular_overtime_amount'] + $ae['restday_overtime_amount'] + $ae['night_differential_amount'] + $ae['holiday_overtime_amount'] - ($ae['absences_amount'] + $ae['undertime_amount'])) + $ae['bonus'] + $ae['others'] - ($ae['tax'] + $ae['sss_contri_ee'] + $ae['phil_health_contri'] + $ae['pagibig_contri'] + $ae['elf_contri'] + $ae['sss_loan'] + $ae['pagibig_loan'] + $ae['cash_advance'] + $ae['elf_loan']);
					$e	=	$this->Abas->getEmployee($ae['emp_id']);
					$v	=	$this->Abas->getVessel($ae['vessel_id']);
					if($e['bank_account_num']=="") {
						$subtotal	=	$subtotal + $net_pay;
					}
					if(isset($deets[$ridectr+1])) {
						if($ae['vessel_id'] != $deets[$ridectr+1]['vessel_id'] && $subtotal>0) {
							$table	.=	'<tr>';
							$table	.= '<td style="text-align:center; width:5%">'.$ctr++.'</td>';
							$table	.= '<td style="text-align:left; width:50%">'.$v->bank_account_name.' ('.$v->name.')</td>';
							if(isset($v->bank_account_num)){
								$bank_account_num = $v->bank_account_num;
							}else{
								$bank_account_num = 'none';
							}
							$table	.= '<td style="text-align:leftr; width:25%">'.$bank_account_num.'</td>';
							$table	.= '<td style="text-align:right;width:20%">'.$this->Abas->currencyFormat($subtotal).'</td>';
							$table	.=	'</tr>';
							$subtotal	=	0;
						}
					}
					elseif($subtotal>0) {
						$table	.=	'<tr>';
						$table	.= '<td style="text-align:center; width:5%">'.$ctr++.'</td>';
						$table	.= '<td style="text-align:left; width:50%">'.$v->bank_account_name.' ('.$v->name.')</td>';
						$table	.= '<td style="text-align:leftr; width:25%">'.$v->bank_account_num.'</td>';
						$table	.= '<td style="text-align:right;width:20%">'.$this->Abas->currencyFormat($subtotal).'</td>';
						$table	.=	'</tr>';
						$subtotal	=	0;
					}


				}
			}
		}
		$table	.=	'<tr>

					<td colspan="3"><b>GRAND TOTAL</b></td><td style="text-align:right;">'.$this->Abas->currencyFormat($total).'</td></tr>';

		// echo "<table>".$table."</table>";die();
		$data['orientation']	=	"P";
		$data['pagetype']		=	"legal";
		$data['title']			=	"Bank Remittance Report";
		$data['content']		=	'<style type=\"text/css\">
				 h1 { font-size:240%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 120%; text-align:center}
				 td {font-size:130%;text-align:center}
			</style>
		<div>
			<h1>Bank Remittance Report for '.$company->name.'</h1>
			<h2>Pay Period: '.$summary->payroll_coverage.' - '.date("F Y",strtotime($summary->payroll_date)).'</h2>
		</div>
		<table class="table table-condensed table-bordered" style="font-size:8px" border="1px">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr style="text-align:center;">
					<th style="width:5%">#</th>
					<th style="width:50%">Name</th>
					<th style="width:25%">Bank Account Number</th>
					<th style="width:20%">Amount</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
		';
		$this->load->view('pdf-container.php',$data);
	}
	public function sss_printable($id) {$data=array();
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$summary	=	$summary;
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.vessel_id, e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$all_employees	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$company		=	$this->Abas->getCompany($summary->company_id);
		$table			=	"";
		$total			=	array("er"=>0, "ee"=>0);
		$current_vessel	=	0;
		$ctr=1;
		foreach($all_employees as $ae) {
			$employee_data	=	$this->Abas->getEmployee($ae->emp_id);
			if($current_vessel!=$ae->vessel_id) {
				$vessel		=	$this->Abas->getVessel($ae->vessel_id);
				$table		.=	'<tr><th colspan="7" style="text-align:left; background-color:#c7cac4; color:#000000">'.$vessel->name."</th></tr>";
				$current_vessel	=	$ae->vessel_id;
			}
			if($ae->sss_contri_ee>0) {
				$table	.=	"<tr>";
				$table	.= "<td style='text-align:center;'>".$ctr."</td>";
				$table	.= "<td style='text-align:center;'>".$employee_data['employee_id']."</td>";
				$table	.= "<td style='text-align:left;'>".$employee_data['full_name']."</td>";
				$table	.= "<td style='text-align:center;'>".$employee_data['sss_num']."</td>";
				$table	.= "<td>".$this->Abas->currencyFormat($ae->sss_contri_ee)."</td>";
				$table	.= "<td>".$this->Abas->currencyFormat($ae->sss_contri_er)."</td>";
				$table	.= "<td>".$this->Abas->currencyFormat(($ae->sss_contri_ee+$ae->sss_contri_er))."</td>";
				$table	.=	"</tr>";
				$total['ee']=	$total['ee'] + $ae->sss_contri_ee;
				$total['er']=	$total['er'] + $ae->sss_contri_er;
				$ctr++;
			}
		}
		$table	.=	'<tr><td colspan="4"><b>GRAND TOTAL</b></td><td>'.$this->Abas->currencyFormat($total['ee']).'</td><td>'.$this->Abas->currencyFormat($total['er']).'</td><td>'.$this->Abas->currencyFormat($total['ee']+$total['er']).'</td></tr>';
		$data['orientation']	=	"P";
		$data['pagetype']		=	"legal";
		$data['title']			=	"SSS Contribution Summary";
		$data['content']		=	'<style type=\"text/css\">
				 h1 { font-size:240%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 120%; text-align:center}
				 td {font-size:130%;text-align:center}
			</style>
		<div>
			<h1>SSS Contribution Summary for '.$company->name.'</h1>
			<h2>Pay Period: '.$summary->payroll_coverage.' - '.date("F Y",strtotime($summary->payroll_date)).'</h2>
		</div>
		<table class="table table-condensed table-bordered" style="font-size:8px" border="1">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Employee ID</th>
					<th class="text-center">Name</th>
					<th class="text-center">SSS #</th>
					<th class="text-center">Employee</th>
					<th class="text-center">Employer</th>
					<th class="text-center">Total</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
		';
		$this->load->view('pdf-container.php',$data);
	}
	public function sss_printable_masterlist($id) {$data=array();
		$this->load->library('Pdf');
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$all_employees	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}


		$company	=	$this->Abas->getCompany($summary->company_id);
		$table	=	"";
		foreach($all_employees as $ae) {
			$employee_data	=	$this->Abas->getEmployee($ae->emp_id);

			$upgrade_date	=	"";
			$check_position	=	$this->db->query("SELECT * FROM hr_employement_history WHERE employee_id=".$ae->emp_id." AND value_changed='Position' AND to_val='".$employee_data['position_name']."'");
			if($check_position) {
				if($check_position->row()) {
					$check_position	=	$check_position->row();
					$upgrade_date	=	$check_position->effectivity_date;
					$upgrade_date	=	date("j F Y",strtotime($upgrade_date));
				}
			}
			$current_cutoff_gross_pay	=	$ae->salary + $ae->allowance + $ae->regular_overtime_amount + $ae->holiday_overtime_amount - $ae->undertime_amount;
			// $this->Mmm->debug($ae->salary + $ae->allowance + $ae->regular_overtime_amount + $ae->holiday_overtime_amount - $ae->undertime_amount);
			$table	.=	"<tr>";
			$table	.= "<td style='text-align:center;'>".$employee_data['vessel_name']."</td>";
			$table	.= "<td style='text-align:left;'>".$employee_data['position_name']."</td>";
			$table	.= "<td style='text-align:center;'>".date("j F Y",strtotime($employee_data['date_hired']))."</td>";
			$table	.= "<td style='text-align:center;'>".$upgrade_date."</td>";
			$table	.= "<td style='text-align:center;'>".$employee_data['full_name']."</td>";
			$table	.= "<td>".""."</td>"; // gross pay - prev cutoff
			$table	.= "<td>".$this->Abas->currencyFormat($current_cutoff_gross_pay)."</td>"; // gross pay - current cutoff
			$table	.= "<td>".""."</td>"; // gross pay - full month
			$table	.= "<td>".$this->Abas->currencyFormat($ae->sss_contri_er)."</td>";
			$table	.= "<td>".$this->Abas->currencyFormat($ae->sss_contri_ee)."</td>";
			$table	.= "<td>".$this->Abas->currencyFormat(($ae->sss_contri_ee+$ae->sss_contri_er))."</td>";
			$table	.= "<td>".$this->Abas->currencyFormat(("ec sched"))."</td>"; // EC Sched (?)
			$table	.= "<td>".$this->Abas->currencyFormat(($ae->sss_contri_ee+$ae->sss_contri_er))."</td>";
			$table	.=	"</tr>";
		}
		$data['orientation']	=	"L";
		$data['pagetype']		=	"legal";
		$data['content']		=	'
		<table cellpadding="1" border="1" style="font-size:8px">
			<thead>
				<tr style="background-color:#000000; color:#FFFFFF;">
					<th>Vessel</th>
					<th>Position</th>
					<th>Date Hired</th>
					<th>Upgrade Position</th>
					<th>Name</th>
					<th>Gross - Prev Cutoff</th>
					<th>Gross - Curr Cutoff</th>
					<th>Gross Pay</th>
					<th>Employer</th>
					<th>Employee</th>
					<th>Total</th>
					<th>New Sched EC</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
		';

		$this->load->view('pdf-container.php',$data);
	}
	public function ph_printable($id) {$data=array();
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");
		// echo "<pre>";print_r($_SESSION);echo "</pre>";
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$all_employees	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}


		$company	=	$this->Abas->getCompany($summary->company_id);
		$table	=	"";
		$total	=	0;
		$ctr=1;
		foreach($all_employees as $ae) {
			// $taxable		=	$this->Payroll_model->getTaxable($ae->salary*2);
			$employee_data	=	$this->Abas->getEmployee($ae->emp_id);
			// echo "<pre>";print_r($ae->salary);echo "</pre>";
			// echo "<h1>".$ae['full_name']."</h1>";
			// if($employee_data['ph_num']!="" && $ae->salary > 0) {
			if($ae->salary > 0) {
			$table	.=	"<tr>";
				$table	.= "<td style='text-align:center;'>".$ctr."</td>";
				$table	.= "<td style='text-align:center;'>".$employee_data['employee_id']."</td>";
				$table	.= "<td style='text-align:left;'>".$employee_data['full_name']."</td>";
				$table	.= "<td style='text-align:center;'>".$employee_data['ph_num']."</td>";
				//$table	.= "<td>".$ae->salary."</td>";
				$table	.= "<td>".$ae->phil_health_contri."</td>";
				$table	.= "<td>".$ae->phil_health_contri."</td>";
				$table	.= "<td>".($ae->phil_health_contri+$ae->phil_health_contri)."</td>";
			$table	.=	"</tr>";
			$total	=	$total + $ae->phil_health_contri;
			$ctr++;
			}
		}
		$table	.=	'<tr>
			<td colspan="4"><b>GRAND TOTAL</b></td>
			<td>'.$this->Abas->currencyFormat($total).'</td>
			<td>'.$this->Abas->currencyFormat($total).'</td>
			<td>'.$this->Abas->currencyFormat($total*2).'</td>
			</tr>';
		$data['orientation']	=	"P";
		$data['pagetype']		=	"legal";
		$data['title']			=	"PhilHealth Contribution Summary";
		$data['content']		=	'<style type=\"text/css\">
				 h1 { font-size:240%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 120%; text-align:center}
				 td {font-size:130%;text-align:center}
			</style>
		<div>
			<h1>PhilHealth Contribution Summary for '.$company->name.'</h1>
			<h2>Pay Period: '.$summary->payroll_coverage.' - '.date("F Y",strtotime($summary->payroll_date)).'</h2>
		</div>
		<table class="table table-condensed table-bordered" style="font-size:8px" border="1">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Employee ID</th>
					<th class="text-center">Name</th>
					<th class="text-center">Policy #</th>
					<th class="text-center">Employer</th>
					<th class="text-center">Employee</th>
					<th class="text-center">Total</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
		';
		$this->load->view('pdf-container.php',$data);
	}
	public function pi_printable($id) {$data=array();
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				// echo "<pre>";print_r($summary);echo "</pre>";
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$all_employees	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
					// echo "<pre>";print_r($employees->result());echo "</pre>";
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}


		$company	=	$this->Abas->getCompany($summary->company_id);
		$table	=	"";
		$total	=	0;
		$ctr=1;
		foreach($all_employees as $ae) {
			$employee_data	=	$this->Abas->getEmployee($ae->emp_id);
			// if($employee_data['salary_rate']!=0 && $employee_data['pagibig_num']!="") {
			if($employee_data['salary_rate']!=0) {
			$table	.=	'<tr>';
				$table	.= '<td style="text-align:center;">'.$ctr.'</td>';
				$table	.= '<td style="text-align:center;">'.$employee_data['employee_id'].'</td>';
				$table	.= '<td style="text-align:left;">'.$employee_data['full_name'].'</td>';
				$table	.= '<td style="text-align:center;">'.$employee_data['pagibig_num'].'</td>';
				$table	.= '<td>'.$ae->pagibig_contri.'</td>';
			$table	.=	'</tr>';
			$total	=	$total + $ae->pagibig_contri;
			$ctr++;
			}
		}
		$table	.=	'<tr><td colspan="4"><b>GRAND TOTAL</b></td><td>'.$this->Abas->currencyFormat($total).'</td></tr>';
		$data['orientation']	=	"P";
		$data['pagetype']		=	"legal";
		$data['title']			=	"Pag-ibig Contribution Summary";
		$data['content']		=	'<style type=\"text/css\">
				 h1 { font-size:240%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 120%; text-align:center}
				 td {font-size:130%;text-align:center}
			</style>
		<div>
			<h1>Pag-ibig Contribution Summary for '.$company->name.'</h1>
			<h2>Pay Period: '.$summary->payroll_coverage.' - '.date("F Y",strtotime($summary->payroll_date)).'</h2>
		</div>
		<table class="table table-condensed table-bordered" style="font-size:8px" border="1">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Employee ID</th>
					<th class="text-center">Name</th>
					<th class="text-center">PagIbig #</th>
					<th class="text-right">Total</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
		';
		$this->load->view('pdf-container.php',$data);
	}
	public function payslip_printable($id, $type="payroll") {$data=array();
	 ######################################
	########################################
	####                                ####
	####       W A R N I N G ! !        ####
	####                                ####
	####   Ugly code ahead! Sorry.      ####
	####                                ####
	####     Max execution time is      ####
	####   returned when running it     ####
	####   like the other printables.   ####
	####                                ####
	####    Went with the 'procedural'  ####
	####   approach compared with the   ####
	####   object-oriented one above.   ####
	####                                ####
	########################################
	 ######################################
		// echo WPATH.'tcpdf'.DS.'tcpdf.php';
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		// $this->load->library('Pdf'); // Let's begin.
		$this->Abas->checkPermissions("payroll|view");

		$width = 330.2;//legal size
		$height = 215.9;

		$pagelayout = array($width, $height); //  or array($height, $width)
		$pdf = new TCPDF('P', PDF_UNIT, $pagelayout, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(' ');
		$pdf->SetTitle('Payslips');
		$pdf->SetSubject(' ');
		$pdf->SetKeywords(' ');

	
		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('dejavusans', '', 10, '', true);
		$pdf->AddPage();
	

		// fetch data for payslip
		if($type=="payroll") {
			$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
			if($summary!=false) {
				if($summary->row()) {
					$summary	=	$summary->row();
					if(is_numeric($summary->company_id)) {
						$company	=	$this->Abas->getCompany($summary->company_id);
						//$sql	=	"SELECT * FROM hr_payroll_details WHERE payroll_id=".$summary->id;
						$sql	=	"SELECT p.*, e.last_name FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
						$details	=	$this->db->query($sql);
						if($details->row()) {
							$details	=	$details->result_array();
						}
						else {
							$_SESSION['errmsg']	=	"No employees found!";
							$this->Abas->redirect(HTTP_PATH."payroll");
						}
					}
					else {
						$_SESSION['errmsg']	=	"Invalid company!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
				}
				else {
					$_SESSION['errmsg']	=	"Payroll record not found!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$sql	=	"SELECT p.*, e.id FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE p.id=".$id;
			$details	=	$this->db->query($sql);
			if($details->row()) {
				$details	=	$details->result_array();
				// $this->Mmm->debug($details);
				$sql=	"SELECT * FROM hr_payroll WHERE id=".$details[0]['payroll_id'];
				$summary	=	$this->db->query($sql);
				if($summary->row()) {
					$summary=	$summary->row();
				}
			}
			else {
				$_SESSION['errmsg']	=	"No employees found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}

		// write payslip over and over again
		foreach($details as $ctr=>$payslip) {
			$employee_data	=	$this->Abas->getEmployee($payslip['emp_id']);
			$tax_code		=	$employee_data['tax_code'];
			$position		=	$employee_data['position_name'];
			$company		=	$employee_data['company_name'];
			$department		=	$employee_data['department_name'];
			$vessel			=	$employee_data['vessel_name'];

			$payslip['leave_credits']	=	(empty($payslip['leave_credits'])) ? "(No Data)":$payslip['leave_credits'];

			$total_loan_payments	=	array("all"=>($payslip['pagibig_loan'] + $payslip['sss_loan'] + $payslip['cash_advance']), "pi"=>$payslip['pagibig_loan'], "ph"=>0, "sss"=>$payslip['sss_loan'], "elf"=>$payslip['elf_loan'], "cash advance"=>$payslip['cash_advance']);
			if(isset($payslip['paid_loans'])) {
				foreach($payslip['paid_loans'] as $loan_id=>$loan_amt) {
					$loan_details	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$loan_id);
					if($loan_details != false) {
						if($loan_details->row()) {
							$loan_details	=	$loan_details->row();
							$total_loan_payments[strtolower($loan_details->loan_type)]	=	$loan_amt;
						}
					}
				}
				foreach($total_loan_payments as $total_per_loan) {echo __LINE__;
					$total_loan_payments['all']	=	$total_loan_payments['all'] + $total_per_loan;
				}
			}
			if($payslip['salary']>0) {
				$total_holiday_ot = $payslip['specialholiday_overtime_amount'] + $payslip['specialholiday_restday_overtime_amount'] + $payslip['legalholiday_overtime_amount'] + $payslip['legalholiday_restday_overtime_amount'];
				if($total_holiday_ot==0){
					$total_holiday_ot = $payslip['holiday_overtime_amount'];
				}
				$total_income	=	($payslip['salary'] + $payslip['allowance'] + $payslip['regular_overtime_amount'] + $payslip['restday_overtime_amount'] + $total_holiday_ot + $payslip['night_differential_amount']) + $payslip['bonus'] + $payslip['others'];
				$total_deduction=	$payslip['absences_amount'] + $payslip['tax'] + $payslip['undertime_amount'] + $payslip['sss_contri_ee'] + $payslip['phil_health_contri'] + $payslip['pagibig_contri'];

				$text	=	'
					<table width="100%" cellpadding="1" border="1">
						<thead>
							<tr style="font-size:10px;" >
								<th width="7.3%" bgcolor="#f30c0c">
									<img src="'. PDF_LINK . 'assets/images/AvegaLogo.jpg" alt="Avega_Logo" style="width:50px;height:40px;">
								</th>
								<th width="92.7%" colspan="2">
									 '.$employee_data['full_name']." (".$employee_data['employee_id'].")".'
									 '.$summary->payroll_coverage.' - '.date("M Y",strtotime($summary->payroll_date)).'
									<br> '.$company.'
									<br> '.$department.' - '.ucwords($position).' - '.ucwords($vessel).' - '.ucwords($employee_data['employee_status']).'
								</th>
							</tr>
							<tr style="background-color:#F4F4F4; color:#000000; text-align:center; font-size:10px;">
								<th width="33.3%">Income</th>
								<th width="33.3%">Deductions</th>
								<th width="33.3%">Loans</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align="left" valign="top">
									<table width="98%" style="font-size:10px;">
										<tbody>
											
											<tr>
												<td>Basic Salary:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['salary'] * 2).'</td>
											</tr>
											<tr>
												<td>Salary this Period:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['salary']).'</td>
											</tr>
											<tr>
												<td>Regular Overtime:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['regular_overtime_amount']).'</td>
											</tr>
											<tr>
												<td>Rest Day Overtime:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['restday_overtime_amount']).'</td>
											</tr>
											<tr>
												<td>Holiday Overtime:</td>
												<td align="right">'.$this->Abas->currencyFormat($total_holiday_ot).'</td>
											</tr>
											<tr>
												<td>Night Differential:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['night_differential_amount']).'</td>
											</tr>
											<tr>
												<td>Allowance:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['allowance']).'</td>
											</tr>
											<tr>
												<td>Others:______________</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['bonus']+$payslip['others']).'</td>
											</tr>
											<tr style="font-weight:600">
												<td align="right">
													<strong><br>Sub Total:</strong>
												</td>
												<td align="right">
													<strong><br>'.$this->Abas->currencyFormat($total_income).'</strong>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td align="left" valign="top">
									<table style="font-size:10px;">
										<tbody>
											<tr>
												<td>W-Tax('.$tax_code.'):</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['tax']).'</td>
											</tr>
											<tr>
												<td>SSS Contri:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['sss_contri_ee']).'</td>
											</tr>
											<tr>
												<td>Philhealth Contri:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['phil_health_contri']).'</td>
											</tr>
											<tr>
												<td>Pagibig Contri:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['pagibig_contri']).'</td>
											</tr>
											<tr>
												<td>Undertime:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['undertime_amount']).'</td>
											</tr>
											<tr>
												<td>Absences:</td>
												<td align="right">'.($payslip['absences_amount']>0?$this->Abas->currencyFormat($payslip['absences_amount']):"0.00").'</td>
											</tr>
											<tr>
												<td colspan=2></td>
											</tr>
											<tr style="font-weight:600">
												<td align="right"><strong><br><br>Sub Total:</strong></td>
												<td align="right"><strong><br><br>'.$this->Abas->currencyFormat($total_deduction).'</strong></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td align="left" valign="top">
									<table style="font-size:10px;">
										<tbody>
											<tr>
												<td>Cash Advance (Bal):</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['cash_advance_balance']).'</td>
											</tr>
											<tr>
												<td>Cash Advance Pay:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['cash_advance']).'</td>
											</tr>
											<tr>
												<td>SSS (Salary Loan):</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['sss_loan_balance']).'</td>
											</tr>
											<tr>
												<td>SSS Loan Pay:</td>
												<td align="right">'.$this->Abas->currencyFormat($total_loan_payments['sss']).'</td>
											</tr>
											<tr>
												<td>Pagibig Loan:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['pagibig_loan_balance']).'</td>
											</tr>
											<tr>
												<td>Pagibig Loan Pay:</td>
												<td align="right">'.$this->Abas->currencyFormat($total_loan_payments['pi']).'</td>
											</tr>
											
											<tr style="font-weight:600">
												<td align="right"><strong><br><br><br>Sub Total:</strong></td>
												<td align="right"><strong><br><br><br>'.$this->Abas->currencyFormat($total_loan_payments['all']).'</strong></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td align="left" valign="top">
									<table width="98%" style="font-size:10px;">
										<tbody>
											<tr>
												<td>Contribution:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['elf_contri']).'</td>
											</tr>
											<tr>
												<td>Elf Loan Payment:</td>
												<td align="right">'.$this->Abas->currencyFormat($payslip['elf_loan']).'</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
							<td align="left" style="background-color:#F4F4F4;">Leave Credit Balance: '.$payslip['leave_credits'].' day(s)</td>
								<td colspan="3" style="background-color:#F4F4F4;">
									<div style="width:100%; text-align:right;">
										<span style="font-weight:600;">
											Net Pay: Php '.$this->Abas->currencyFormat(($total_income - $total_deduction - $payslip['elf_contri'] - $total_loan_payments['all'])).'
										</span>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<div style="clear:both;">&nbsp;</div>
					<div style="clear:both;">&nbsp;</div>
				';
				// echo $text;
				$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);
			}
			unset($details[$ctr]);
		}

		while (ob_get_level())
        ob_end_clean();
		header("Content-Encoding: None", true);
		// flush();
		$pdf->Output('payroll.pdf', 'I');
	}
	public function edit($payroll_detail_id=0, $action="") {$data=array();
		if($action=="") {
			if(is_numeric($payroll_detail_id)) {
			$check	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE id=".$payroll_detail_id);
				if($check) {
					if($check->row()) {
						$check					=	$check->row();
						//$this->Mmm->debug($check);
						$data['payroll_detail_id']	=	$payroll_detail_id;
						$data['payrollsession']		=	$check;
						$data['employeedata']		=	$this->Abas->getEmployee($check->emp_id);

						$loansql=	"
							SELECT lp.*, l.emp_id
							FROM hr_loan_payments AS lp
							INNER JOIN hr_loans AS l
							WHERE lp.payroll_id=".$check->payroll_id."
								AND l.emp_id=".$check->emp_id;
						//$this->Mmm->debug($loansql);
						$loans	=	$this->db->query($loansql);
						if($loans) {
							if($loans->row()) {

							}
						}

						$mainview					=	"payroll/edit_history.php";
						$this->load->view($mainview,$data);
					}
					else {
						$this->Abas->sysMsg("warnmsg", "Payroll record not found!");
						$this->Abas->redirect(HTTP_PATH."payroll/summary");
					}
				}
				else {
					$this->Abas->sysMsg("warnmsg", "Payroll record not found!");
					$this->Abas->redirect(HTTP_PATH."payroll/summary");
				}
			}
			else {
				$this->Abas->sysMsg("warnmsg", "Invalid payroll record!");
				$this->Abas->redirect(HTTP_PATH."payroll/summary");
			}
		}
		elseif($action=="update") {
			// $sessiondata	=	$_SESSION['payroll']['data'][$session_id];
			$check	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE id=".$payroll_detail_id);
			$check	=	$check->row();
			$company=	$check->company_id;
			$check_lock	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$check->payroll_id);
			if($check_lock) {
				if($check_lock=$check_lock->row()) {
					if($check_lock->locked==true) {
						$this->Abas->sysMsg("warnmsg", "This payroll is locked and cannot be edited.");
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
				}
			}
			$e				=	$this->Abas->getEmployee($check->emp_id);
			$employee_link	=	"<a href='".HTTP_PATH."payroll_history/edit/".$payroll_detail_id."' data-toggle='modal' data-target='#modalDialog' style='cursor:pointer;'>".$e['full_name']."</a>";
			// $this->Mmm->debug($_POST);
			// $this->Mmm->debug($_SESSION);
			if(!empty($_POST)) {
				// $monthly					=	$_SESSION['payroll']['data'][$session_id]['monthly'];
				$salary						=	$this->Mmm->sanitize($_POST['salary']);
				$allowance					=	$this->Mmm->sanitize($_POST['allowance']);
				$absences					=	$this->Mmm->sanitize($_POST['absences']);
				$regular_ot					=	$this->Mmm->sanitize($_POST['regular_ot']);
				$restday_ot					=	$this->Mmm->sanitize($_POST['restday_ot']);
				$legal_holiday_ot			=	$this->Mmm->sanitize($_POST['legal_holiday_ot']);
				$legal_holiday_restday_ot	=	$this->Mmm->sanitize($_POST['legal_holiday_on_rest_day_ot']);
				$special_holiday_ot			=	$this->Mmm->sanitize($_POST['special_holiday_ot']);
				$special_holiday_restday_ot	=	$this->Mmm->sanitize($_POST['special_holiday_on_rest_day_ot']);
				$total_holiday_ot			=	$legal_holiday_ot +$legal_holiday_restday_ot + $special_holiday_ot	+ $special_holiday_restday_ot;
				$night_diff					=	$this->Mmm->sanitize($_POST['night_differential']);
				$ut							=	$this->Mmm->sanitize($_POST['ut']);
				$bonus						=	$this->Mmm->sanitize($_POST['bonus']);
				$others						=	$this->Mmm->sanitize($_POST['others']);
				$withholding				=	$this->Mmm->sanitize($_POST['withholding']);
				$sss_payable				=	$this->Mmm->sanitize($_POST['sss_payable']);
				$pi_payable					=	$this->Mmm->sanitize($_POST['pi_payable']);
				$ph_payable					=	$this->Mmm->sanitize($_POST['ph_payable']);
				$elf_payable				=	$this->Mmm->sanitize($_POST['elf_payable']);

				$sss_loan                   =	$this->Mmm->sanitize($_POST['sss_loan']);
				$pagibig_loan               =	$this->Mmm->sanitize($_POST['pagibig_loan']);
				$cash_advance               =	$this->Mmm->sanitize($_POST['cash_advance']);

				$update['salary']					=	$salary;
				$update['absences_amount']			=	$absences;
				$update['allowance']				=	$allowance;
				$update['regular_overtime_amount']	=	$regular_ot;
				$update['restday_overtime_amount']	=	$restday_ot;
				$update['legalholiday_overtime_amount']	=	$legal_holiday_ot;
				$update['legalholiday_restday_overtime_amount']	=	$legal_holiday_restday_ot;
				$update['specialholiday_overtime_amount']	=	$special_holiday_ot;
				$update['specialholiday_restday_overtime_amount']	=	$special_holiday_restday_ot;
				$update['holiday_overtime_amount']  =	$total_holiday_ot;
				$update['night_differential_amount']=	$night_diff;
				$update['undertime_amount']			=	$ut;
				$update['bonus']					=	$bonus;
				$update['others']					=	$others;
				$update['tax']						=	$withholding;
				$update['sss_contri_ee']			=	$sss_payable;
				// $update['sss_contri_er']			=	$sss_employer;
				$update['pagibig_contri']			=	$pi_payable;
				$update['phil_health_contri']		=	$ph_payable;
				$update['elf_contri']				=	$elf_payable;
				$income			=	($salary + $allowance + $regular_ot + $restday_ot + $legal_holiday_ot + $legal_holiday_restday_ot + $special_holiday_ot + $special_holiday_restday_ot + $night_diff) + $bonus + $others - ($ut + $absences);
				// $deductions		=	$pi_payable + $sss_payable + $ph_payable + $elf_payable + $pi_loan + $sss_loan + $ph_loan + $elf_loan + $cash_advance + $withholding;
				
				$update['sss_loan']					=	$sss_loan;
				$update['pagibig_loan']				=	$pagibig_loan;
				$update['cash_advance']				=	$cash_advance;

				$loans = $sss_loan + $pi_loan + $cash_advance;

				$deductions		=	$pi_payable + $sss_payable + $ph_payable + $withholding + $loans;
				
				$net_pay					=	$income - $deductions;
				$update['net_pay']	=	$net_pay;
				
				// $upd	=	$this->Mmm->dbUpdate("hr_payroll_details", $update, $payroll_detail_id, "debug");
				$upd	=	$this->Mmm->dbUpdate("hr_payroll_details", $update, $payroll_detail_id, "Update Payroll History for ".$e['full_name']." in ".$company->name." ");
				if($upd==true) {
					$this->Abas->sysNotif("Payroll Update", $_SESSION['abas_login']['username']." updated the payroll of ".$employee_link." for ".$check_lock->payroll_coverage." ".date("F",strtotime($check_lock->payroll_date."-01"))." in ".$e['company_name'].".", "Payroll", "warning");
					$this->Abas->sysMsg("msg", "Updated payroll for ".$employee_link."!");
				}
				else {
					$this->Abas->sysMsg("warnmsg", "Did not update payroll for ".$employee_link."!");
				}
			}
			$this->Abas->redirect(HTTP_PATH."payroll_history/view/".$check->payroll_id);
		}
	}
	public function add() {$data=array();
		$this->Abas->checkPermissions("payroll|add");
		if(empty($_POST)) {
			$employeesql				=	"SELECT id, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as full_name FROM hr_employees WHERE stat=1 AND (employee_status='Regular' OR employee_status='Probationary' OR employee_status='Contractual' OR employee_status='Fixed Term' OR employee_status='Casual')";
			$employees					=	$this->db->query($employeesql);
			$employees					=	$employees->result_array();
			$payroll_periods			=	$this->db->query("SELECT * FROM hr_payroll");
			$payroll_periods			=	$payroll_periods->result_array();
			$data['payroll_periods']	=	$payroll_periods;
			$data['employees']			=	$employees;
			$this->load->view('payroll/add_payroll.php',$data);
		}
		else {
			$e				=	$this->Abas->getEmployee($_POST['employee_id']);
			$payroll_period	=	$this->Mmm->sanitize($_POST['payroll_id']);
			$payroll_period	=	explode("-",$payroll_period);
			$half			=	($payroll_period[0]==0) ? "1st-half" : "2nd-half" ;
			$payroll_date	=	$payroll_period[2]."-".$payroll_period[1];
			$sql			=	"SELECT * FROM hr_payroll WHERE company_id=".$payroll_period[3]." AND payroll_date LIKE '".$payroll_date."%' ORDER BY id DESC";
			$company		=	$this->Abas->getCompany($payroll_period[3]);
			// $this->Mmm->debug($sql);
			$check			=	$this->db->query($sql);
			// $this->Mmm->debug($check);
			if($check) {
				if($check->row()) {
					$selected_payroll	=	$check->row();
					$insert['payroll_id']			=	$selected_payroll->id;
					$insert['emp_id']				=	$this->Mmm->sanitize($_POST['employee_id']);
					$insert['vessel_id']			=	$e['vessel_id'];
					// $insert['salary']				=	$this->Mmm->sanitize($_POST['salary']);
					// $insert['sss_contri_ee']		=	$this->Mmm->sanitize($_POST['sss']);
					// $insert['tax']					=	$this->Mmm->sanitize($_POST['wtax']);
					// $insert['pagibig_contri']		=	$this->Mmm->sanitize($_POST['pi']);
					// $insert['phil_health_contri']	=	$this->Mmm->sanitize($_POST['ph']);
					$ins		=	$this->Mmm->dbInsert("hr_payroll_details", $insert, "Add ".$e['full_name']." to payroll");
					if($ins==true) {
						$inserted	=	$this->db->query("SELECT MAX(id) AS max_id FROM hr_payroll_details WHERE emp_id=".$e['id']);
						$inserted	=	$inserted->row();
						$link		=	"<a href='".HTTP_PATH."payroll_history/edit/".$inserted->id."'>".$e['full_name']."</a>";
						$this->Abas->sysMsg("sucmsg", "".$link." added to payroll period!");
						$this->Abas->sysNotif("Payroll Update", $link." was added to the payroll of ".$company->name." ".$half." ".date("j Y",strtotime($payroll_date)));
					}
					else { $this->Abas->sysMsg("warnmsg", "".$e['full_name']." not added to payroll period!"); }
				}
				else { $this->Abas->sysMsg("warnmsg", "Payroll period not found for that company!"); }
			}
			else { $this->Abas->sysMsg("warnmsg", "Payroll period not found for that company!"); }
			$this->Abas->redirect(HTTP_PATH."payroll_history/view/".$selected_payroll->id);
		}
	}
	public function approve($id) {$data=array();
		$this->Abas->checkPermissions("payroll|approve");
		if(!is_numeric($id)) {
			$this->Abas->sysMsg("warnmsg", "Payroll does not exist!");
			$this->Abas->redirect(HTTP_PATH."payroll");
		}

		$check	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$id);
		if(!$check) {
			$this->Abas->sysMsg("warnmsg", "Payroll does not exist!");
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		if(!$check->row()) {
			$this->Abas->sysMsg("warnmsg", "Payroll does not exist!");
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$check	=	$check->row();
		if($check->locked==false) {
			$update['locked']	=	true;
			$pay	=	$this->db->query("SELECT SUM(net_pay) as total_net_pay FROM hr_payroll_details WHERE payroll_id=".$id);
			$pay = $pay->row();
			$update['payroll_amount']		=	$pay->total_net_pay;
			$update['approved_on']			=	date("Y-m-d H:i:s");
			$update['approved_by']			=	$_SESSION['abas_login']['userid'];
			$updated	=	$this->Mmm->dbUpdate("hr_payroll", $update, $id, "Locked payroll");
			if($updated==true) {
				$company	=	$this->Abas->getCompany($check->company_id);
				$this->Abas->sysMsg("sucmsg","Payroll has been successfully approved and locked.");
				$this->Abas->sysNotif("Approved Payroll Summary", $_SESSION['abas_login']['fullname']." has approved Payroll  " .$check->payroll_coverage." ".$check->payroll_date." for ".$company->name,"Payroll","info");
			}
			else {
				$this->Abas->sysMsg("errmsg","An error has occurred, please try again!");
			}
		}
		else {
			$this->Abas->sysMsg("msg","Payroll is already locked!");
		}
		$this->Abas->redirect(HTTP_PATH."payroll_history/view/".$id);
	}
	public function rides_printable($payroll_id, $vessel_id=0) {
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		$this->Abas->checkPermissions("payroll|reports");
		$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$payroll_id);
		if($summary!=false) {
			if($summary->row()) {
				$summary	=	$summary->row();
				$data['summary']	=	$summary;
				if(is_numeric($summary->company_id)) {
					$company	=	$this->Abas->getCompany($summary->company_id);
					$sql	=	"SELECT * FROM hr_payroll_details AS p INNER JOIN hr_employees AS e ON p.emp_id = e.id WHERE payroll_id=".$summary->id." ORDER BY e.last_name";
					$employees	=	$this->db->query($sql);
					if($employees->row()) {
						$all_employees	=	$employees->result();
					}
					else {
						$_SESSION['errmsg']	=	"No employees found!";
						$this->Abas->redirect(HTTP_PATH."payroll");
					}
				}
				else {
					$_SESSION['errmsg']	=	"Invalid company!";
					$this->Abas->redirect(HTTP_PATH."payroll");
				}
			}
			else {
				$_SESSION['errmsg']	=	"Payroll record not found!";
				$this->Abas->redirect(HTTP_PATH."payroll");
			}
		}
		else {
			$_SESSION['errmsg']	=	"Payroll record not found!";
			$this->Abas->redirect(HTTP_PATH."payroll");
		}
		$vquery	=	"";
		$table	=	"";
		if($vessel_id!=0) { $vquery	=	"AND vessel_id=".$vessel_id; }
		$deets	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE payroll_id=".$payroll_id." ".$vquery." ORDER BY vessel_id DESC");
		if($deets) {
			if($deets->row()) {
				$old_vessel_id	=	0;
				$deets	=	$deets->result_array();
				$total	=	0;
				$gtotal = 0;
				$empctr = 0;
				foreach($deets as $ctr=>$d) {
					$e	=	$this->Abas->getEmployee($d['emp_id']);
					$v	=	$this->Abas->getVessel($d['vessel_id']);
					if($d['vessel_id'] != $old_vessel_id) { // employee is on different vessel
						
							if(isset($v->bank_account_num)){
								$bank_account_num = $v->bank_account_num;
							}else{
								$bank_account_num = "none";
							}
						$table	.=	'<tr>
										<td colspan="2" style="font-size:10px;background-color: #c7cac4;color: #000000; text-align:left"> '.$v->name.' - Bank Account: '.$v->bank_account_name.' ('.$bank_account_num.')</td>
									</tr>';
						
						$old_vessel_id	=	$e['vessel_id'];
						$total	=	0;
					}
					if($e['bank_account_num']=="") {
						$net_salary = $d['net_pay']-($d['elf_loan']+$d['elf_contri']);
						$table	.=	'<tr>';
						$table	.=	'<td>'.$e['full_name'].'</td>';
						$table	.=	'<td style="">'.$this->Abas->currencyFormat($net_salary).'</td>';
						$table	.=	'</tr>';
						$empctr++;
						$total	=	$total + $net_salary;
						$gtotal	=	$gtotal + $net_salary;
					}
					if(isset($deets[$ctr+1])) {
						if($d['vessel_id'] != $deets[$ctr+1]['vessel_id']) {
							$table	.=	'<tr><td>TOTAL:</td><td>P'.$this->Abas->currencyFormat($total).'</td></tr>';
						}
					}
					else {
						$table	.=	'<tr><td>TOTAL:</td><td>P'.$this->Abas->currencyFormat($total).'</td></tr>';
						$table	.=	'<tr style="font-size:10px;"><td><b>GRAND TOTAL</b></td><td>'.$this->Abas->currencyFormat($gtotal).'</td></tr>';
						$table	.=	'</table>';
					}
				}
			}
		}
		$data['orientation']	=	"P";
		$data['pagetype']		=	"legal";
		$data['title']			=	"Rides Summary";
		$data['content']		=	'<style type=\"text/css\">
				 h1 { font-size:220%;text-align:center; }
				 h2,h3 { text-align:center;font-size:100% }	
				 h5 span { border-bottom: double 3px; }
				 th {background-color: black;color: white; font-size: 110%; text-align:center}
				 td {text-align:center}
			</style>
		<div>
			<h1>Rides Summary for '.$company->name.'</h1>
			<h2>Pay Period: '.$summary->payroll_coverage.' - '.date("F Y",strtotime($summary->payroll_date)).'</h2>
		</div>
		<table border="1" style="font-size:8px">
		<tr>
			<th style="font-size:8px;">Name</th>
			<th style="font-size:8px;">Amount</th>
		</tr>
		'.$table.'
		';
		//echo $data['content'];
		$this->load->view('pdf-container.php',$data);
	}
	public function view_all_payrolls() {
		$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
		$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
		$order	=	isset($_GET['order'])?$_GET['order']:"";
		$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
		$search	=	isset($_GET['search'])?$_GET['search']:"";
		//$data	=	$this->Payroll_model->getAllPayrolls($search,$limit,$offset,$order,$sort);
		$data	=	$this->Abas->createBSTable("hr_payroll",$search,$limit,$offset,$order,$sort);
		if($data!=false) {
			foreach($data['rows'] as $ctr=>$payroll) {
				if($payroll['created_by']){
					$created_by = $this->Abas->getUser($payroll['created_by']);
					$data['rows'][$ctr]['created_by'] = $created_by['full_name'];	
				}
				if($payroll['approved_by']){
					$approved_by = $this->Abas->getUser($payroll['approved_by']);
					$data['rows'][$ctr]['approved_by'] = $approved_by['full_name'];
				}
				if($payroll['company_id']){
					$company = $this->Abas->getCompany($payroll['company_id']);
					$data['rows'][$ctr]['company_name'] = $company->name;
				}
				if($payroll['payroll_date']){
					$data['rows'][$ctr]['payroll_date'] = date("F Y", strtotime($payroll['payroll_date']));
				}
				if($payroll['locked']==1){
					$data['rows'][$ctr]['status'] = "Approved";
				}else{
					$data['rows'][$ctr]['status'] = "For Approval";
				}
			}
			header('Content-Type: application/json');
			echo json_encode($data);
			exit();
		}
	}
}

?>
