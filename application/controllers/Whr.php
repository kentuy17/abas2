<?php
defined('BASEPATH') OR exit('No direct script access allowed');

###############################################################
###############################################################
###############################################################
###                                                         ###
###      Warehouse Human Resources Information System       ###
###                                                         ###
###  Forked off from the original HRIS and stripped down    ###
###  to include only employee information, it has it's      ###
###  own database table `whr_employees` and it's own view   ###
###  folder, 'whr'. It is NOT inteded to be a replacement   ###
###  for the HRIS, but merely to segregate the warehouse    ###
###  personnel from the others.                             ###
###                                                         ###
###                                    mmm 2016-06-03       ###
###                                                         ###
###############################################################
###############################################################
###############################################################

class Whr extends CI_Controller {

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		$this->load->model("Whr_model");
		$this->load->model("Mmm");
		$this->output->enable_profiler(FALSE);
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
	}
	public function index()	{$data=array();
		$data['viewfile']	=	"whr/hr_view.php";
		$this->load->view('container.php',$data);
	}
	public function view_all_employees() {
		$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
		$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
		$order	=	isset($_GET['order'])?$_GET['order']:"";
		$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
		$search	=	isset($_GET['search'])?$_GET['search']:"";
		$data	=	$this->Whr_model->getAllEmployees($search,$limit,$offset,$order,$sort);
		if($data!=false) {
			header('Content-Type: application/json');
			echo json_encode($data);
			exit();
		}
		else {
			// $_SESSION['errmsg']	=	"An error has occurred! <pre>Error ". __class__ .":". __function__ .":". __line__ ."</pre>";
		}
	}
	public function employee_profile($action="", $id="") {$data=array();
		//$module					=	__function__ ;
		//$this->Abas->checkPermissions($module ."|". $action );
		$viewfile				=	"whr/hr_view.php";
		$mainview				=	"container.php";
		$data['vessels']		=	$this->Abas->getVessels();
		$data['companies']		=	$this->Abas->getCompanies();
		$data['positions']		=	$this->Abas->getPositions();
		// $data['departments']	=	$this->Abas->getDepartments();
		$data['regions']		=	$this->Whr_model->getRegions();
		$data['warehouses']		=	$this->Whr_model->getWarehouses();
		$data['taxcodes']		=	$this->Abas->getTaxCodes();
		$data['salarygrades']	=	$this->Abas->getSalaryGrades();
		if($id=="") {
			if($action=="add") {
				$mainview	=	"whr/employee_form.php";
			}
			elseif($action=="insert") {
				if(isset($_POST['last_name'], $_POST['first_name'], $_POST['gender'], $_POST['company'])) {
					if($_POST['last_name']!="" && $_POST['first_name']!="" && $_POST['gender']!="" && $_POST['company']!="") {
						/* profile pic upload */
						$config['upload_path'] = WPATH .'assets'.DS.'images'.DS.'employeepic'.DS;
						$config['allowed_types'] = 'jpg';
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('picture')) {
							$error = array('error' => $this->upload->display_errors());
							$_SESSION['warnmsg'] = $error['error'];
						}
						else {
							$upload_data=$this->upload->data();
							$titleimage	=	$upload_data['file_name'];
							$insert['profile_pic']	=	$titleimage;
							$_SESSION['sucmsg']		=	"Image uploaded!";
						}
						/* profile pic upload */
						if($_POST['tax_code']=="") {
							$_SESSION['warnmsg']	=	"Tax code not set! Defaulting to Maximum Tax.";
							$taxcode	=	"S";
						}
						else {
							$taxcode	=	$this->Mmm->sanitize($_POST['tax_code']);
						}
						if($_POST['elfrate']=="") {
							$_SESSION['warnmsg']	=	"ELF rate not set! Defaulting P1000 / Mo.";
							$elfrate	=	1000;
						}
						else {
							$elfrate	=	$this->Mmm->sanitize($_POST['elfrate']);
						}
						$insert['last_name']				=	$this->Mmm->sanitize($_POST['last_name']);
						$insert['first_name']				=	$this->Mmm->sanitize($_POST['first_name']);
						$insert['middle_name']				=	$this->Mmm->sanitize($_POST['middle_name']);
						$insert['birth_date']				=	$_POST['birth_date']=="" ? null : date("Y-m-d",strtotime($_POST['birth_date']));
						$insert['gender']					=	$this->Mmm->sanitize($_POST['gender']);
						$insert['mobile']					=	$this->Mmm->sanitize($_POST['mobile']);
						$insert['email']					=	$this->Mmm->sanitize($_POST['email']);
						$insert['civil_status']				=	$this->Mmm->sanitize($_POST['civil_status']);
						$insert['employee_id']				=	$this->Mmm->sanitize($_POST['eid']);
						$insert['address']					=	$this->Mmm->sanitize($_POST['address']);
						$insert['city']						=	$this->Mmm->sanitize($_POST['city']);
						$insert['zipcode']					=	$this->Mmm->sanitize($_POST['zip']);
						$insert['emergency_contact_num']	=	$this->Mmm->sanitize($_POST['emergency_num']);
						$insert['emergency_contact_person']	=	$this->Mmm->sanitize($_POST['emergency_contact_person']);
						$insert['date_hired']				=	$_POST['birth_date']=="" ? null : date("Y-m-d",strtotime($_POST['date_hired']));
						$insert['company_id']				=	$this->Mmm->sanitize($_POST['company']);
						$insert['position']					=	$this->Mmm->sanitize($_POST['position']);
						$insert['region']					=	$this->Mmm->sanitize($_POST['region']);
						$insert['warehouse']				=	$this->Mmm->sanitize($_POST['warehouse']);
						$insert['salary_grade']				=	$this->Mmm->sanitize($_POST['salary_grade']);
						$insert['department']				=	$this->Mmm->sanitize($_POST['department']);
						$insert['experience']				=	$this->Mmm->sanitize($_POST['experience']);
						$insert['elf_rate']					=	$elfrate;
						$insert['tax_code']					=	$taxcode;
						$insert['tin_num']					=	$this->Mmm->sanitize($_POST['tin_num']);
						$insert['sss_num']					=	$this->Mmm->sanitize($_POST['sss_num']);
						$insert['ph_num']					=	$this->Mmm->sanitize($_POST['ph_num']);
						$insert['pagibig_num']				=	$this->Mmm->sanitize($_POST['pagibig_num']);
						$insert['bank_account_num']			=	$this->Mmm->sanitize($_POST['bank_account_num']);
						$insert['vessel_id']				=	$this->Mmm->sanitize($_POST['vessel']);
						$insert['input_by']					=	$_SESSION['abas_login']['userid'];
						$insert['input_on']					=	date("Y-m-d H:i:s");
						$insert['stat']						=	1;
						$insert['employee_status']			=	$this->Mmm->sanitize($_POST['empstat']);

						$check	=	$this->db->query("SELECT * FROM whr_employees WHERE first_name='".$insert['first_name']."' AND last_name='".$insert['last_name']."' AND birth_date='".$insert['birth_date']."'");
						if(!empty($check)) {
							if(!$check->row()) {
								$query	=	$this->Mmm->dbInsert("whr_employees",$insert,"Insert to WHR Employees");
								if($query==true) {
									$_SESSION['msg']		=	"Employee record successfully added!";
									$this->Abas->redirect(HTTP_PATH."whr");
								}
								else { $_SESSION['errmsg']	=	"An unknown error has occurred!"; }
							}
							else {	$_SESSION['warnmsg']	=	"That employee already exists!";	}
						}
						else {	$_SESSION['warnmsg']	=	"That employee already exists!";	}
					}
					else {	$_SESSION['errmsg']	=	"Please make sure the basic info is filled in!";	}
				}
				else {	$_SESSION['errmsg']	=	"Please make sure the basic info is filled in!";	}
			}
		}
		else {
			if(is_numeric($id)) {
				$employee_record			=	$this->Abas->getWHEmployee($id);
				if(!empty($employee_record)) {
					$data['employee_record']=	$employee_record;
					$employee_link			=	"<a href='".HTTP_PATH."whr/employee_profile/view/".$id."' data-toggle='modal' data-target='#modalDialog' style='cursor:pointer;'>".$employee_record['full_name']."</a>";
					if($action=="edit") {
						$mainview	=	"whr/employee_form.php";
					}
					elseif($action=="update") {
						$this->Mmm->debug($_POST);
						if(isset($_POST['last_name'], $_POST['first_name'],$_POST['company'])) {
							if($_POST['last_name']!="" && $_POST['first_name']!="" && $_POST['company']!="") {
								/* profile pic upload */
								$config['upload_path'] = WPATH .'assets'.DS.'images'.DS.'employeepic'.DS;
								$config['allowed_types'] = 'jpg';
								$this->load->library('upload',$config);
								if (!$this->upload->do_upload('picture')) {
									$error = array('error' => $this->upload->display_errors());
									$this->Abas->sysMsg('warnmsg',$error['error']);
								}
								else {
									$upload_data=$this->upload->data();
									$titleimage	=	$upload_data['file_name'];
									$update['profile_pic']	=	$titleimage;
									$this->Abas->sysMsg('sucmsg',"Image uploaded!");
								}
								/* profile pic upload */
								if($_POST['tax_code']=="") {
									$this->Abas->sysMsg('warnmsg',"Tax code not set! Defaulting to Maximum Tax.");
									$taxcode	=	"S";
								}
								else {
									$taxcode	=	$this->Mmm->sanitize($_POST['tax_code']);
								}
								$update['last_name']				=	$this->Mmm->sanitize($_POST['last_name']);
								$update['first_name']				=	$this->Mmm->sanitize($_POST['first_name']);
								$update['middle_name']				=	$this->Mmm->sanitize($_POST['middle_name']);
								$update['birth_date']				=	($_POST['birth_date']!="")?date("Y-m-d H:i:s",strtotime($_POST['birth_date'])):null;
								$update['gender']					=	$this->Mmm->sanitize($_POST['gender']);
								$update['mobile']					=	$this->Mmm->sanitize($_POST['mobile']);
								$update['email']					=	$this->Mmm->sanitize($_POST['email']);
								$update['civil_status']				=	$this->Mmm->sanitize($_POST['civil_status']);
								$update['employee_id']				=	$this->Mmm->sanitize($_POST['eid']);
								if(isset($_POST['empstat'])) {
									$update['employee_status']			=	$this->Mmm->sanitize($_POST['empstat']);
								}
								$update['address']					=	$this->Mmm->sanitize($_POST['address']);
								$update['city']						=	$this->Mmm->sanitize($_POST['city']);
								$update['zipcode']					=	$this->Mmm->sanitize($_POST['zip']);
								$update['emergency_contact_num']	=	$this->Mmm->sanitize($_POST['emergency_num']);
								$update['emergency_contact_person']	=	$this->Mmm->sanitize($_POST['emergency_contact_person']);
								$update['date_hired']				=	($_POST['date_hired']!="")?date("Y-m-d H:i:s",strtotime($_POST['date_hired'])):null;
								$update['company_id']				=	$this->Mmm->sanitize($_POST['company']);
								if(isset($_POST['position'])) {
									$update['position']					=	$this->Mmm->sanitize($_POST['position']);
								}
								if(isset($_POST['salary_grade'])) {
									$update['salary_grade']				=	($_POST['salary_grade']=="")?0:$this->Mmm->sanitize($_POST['salary_grade']);
								}
								if(isset($_POST['department'])) {
									$update['department']				=	$this->Mmm->sanitize($_POST['department']);
								}
								$update['experience']				=	$this->Mmm->sanitize($_POST['experience']);
								$update['elf_rate']					=	$this->Mmm->sanitize($_POST['elfrate']);
								$update['tax_code']					=	$taxcode;
								$update['tin_num']					=	$this->Mmm->sanitize($_POST['tin_num']);
								$update['sss_num']					=	$this->Mmm->sanitize($_POST['sss_num']);
								$update['region']					=	$this->Mmm->sanitize($_POST['region']);
								$update['warehouse']				=	$this->Mmm->sanitize($_POST['warehouse']);
								$update['ph_num']					=	$this->Mmm->sanitize($_POST['ph_num']);
								$update['pagibig_num']				=	$this->Mmm->sanitize($_POST['pagibig_num']);
								$update['bank_account_num']			=	$this->Mmm->sanitize($_POST['bank_account_num']);
								if(isset($_POST['vessel'])) {
									$update['vessel_id']				=	$this->Mmm->sanitize($_POST['vessel']);
								}
								$update['modified_by']				=	$_SESSION['abas_login']['userid'];
								$update['modified_on']				=	date("Y-m-d H:i:s");

								// $check	=	$this->db->query("SELECT * FROM hr_employees WHERE first_name='".$update['first_name']."' AND last_name='".$update['last_name']."' AND birth_date='".$update['birth_date']."' AND id<>".$id);
								// if(empty($check)) {
									// if(!$check->row()) {
										// change employee status? add to history!
										if($employee_record['employee_status']!=$this->Mmm->sanitize($_POST['empstat'])) {
											$history['employment_status']	=	$this->Mmm->sanitize($_POST['empstat']);
											$history['employee_id']			=	$id;
											$history['position']			=	$this->Mmm->sanitize($_POST['position']);
											$history['start_date']			=	date("Y-m-d", strtotime($_POST['change_effective_date']));
											$history['review_date']			=	date("Y-m-d", strtotime($_POST['status_review_date']));
											$history['stat']				=	1;
											$history['remarks']				=	$this->Mmm->sanitize($_POST['status_remarks']);
											// $query	=	$this->Mmm->dbInsert("hr_employment_history",$history);
											// if($query==false) {
												// $this->Abas->sysMsg("errmsg","Error adding to history!");
											// }
										}

										$query	=	$this->Mmm->dbUpdate("whr_employees",$update, $id, "Update WHR Employee record ".$update['last_name'].", ".$update['first_name'], false);
										if($query==true) {
											$this->Abas->sysMsg('sucmsg',$employee_link." successfully updated!");
											$this->Abas->redirect(HTTP_PATH."whr");
										}
										else {
											$this->Abas->sysMsg('errmsg',"An unknown error has occurred!");
										}
									// }
									// else {	$this->Abas->sysMsg('errmsg',"An employee with that name already exists!");	}
								// }
								// else {	$this->Abas->sysMsg('errmsg',"An employee with that name already exists!");	}
							}
							else {
								$this->Abas->sysMsg('errmsg',"Please make sure the basic info is filled in!");
							}
						}
						else {
							$this->Abas->sysMsg('errmsg',"Please make sure the basic info is filled in!");
						}
					}
					elseif($action=="view") {
						$payroll_info	=	$this->db->query("SELECT * FROM whr_payroll_details WHERE emp_id=".$id." ORDER BY id DESC");
						$data['payroll_info']	=	null;
						if($payroll_info!=false) {
							if($payroll_info->row()) {
								$payroll_info	=	$payroll_info->result();
								$data['payroll_info']	=	$payroll_info;
							}
						}
						$employee_history	=	$this->db->query("SELECT * FROM whr_employment_history WHERE employee_id=".$id." AND stat=1 ORDER BY id DESC");
						$data['employee_history']	=	null;
						if($employee_history!=false) {
							if($employee_history->row()) {
								$employee_history	=	$employee_history->result_array();
								$data['employee_history']	=	$employee_history;
							}
						}
						$overtimes		=	$this->db->query("SELECT * FROM whr_overtime WHERE employee_id=".$id." AND stat=1 ORDER BY id DESC");
						$data['overtimes']	=	null;
						if($overtimes!=false) {
							if($overtimes->row()) {
								$overtimes	=	$overtimes->result_array();
								$data['overtimes']	=	$overtimes;
							}
						}
						$undertimes		=	$this->db->query("SELECT * FROM whr_undertime WHERE employee_id=".$id." AND stat=1 ORDER BY id DESC");
						$data['undertimes']	=	null;
						if($undertimes!=false) {
							if($undertimes->row()) {
								$undertimes	=	$undertimes->result_array();
								$data['undertimes']	=	$undertimes;
							}
						}
						$loans			=	$this->db->query("SELECT * FROM whr_loans WHERE emp_id=".$id." AND stat=1 ORDER BY id DESC");
						$data['loans']	=	null;
						if($loans!=false) {
							if($loans->row()) {
								$loans	=	$loans->result_array();
								$data['loans']	=	$loans;
							}
						}
						$leaves			=	$this->db->query("SELECT * FROM whr_leaves WHERE emp_id=".$id." AND stat=1 ORDER BY id DESC");
						$data['leaves']	=	null;
						if($leaves!=false) {
							if($leaves->row()) {
								$leaves	=	$leaves->result_array();
								$data['leaves']	=	$leaves;
							}
						}
						$mainview	=	"whr/employee_profile.php";
						// $viewfile	=	"whr/employee_profile.php";
					}
				}
				else {
					$this->Abas->sysMsg('errmsg',"Employee not found!");
				}
			}
			else {
				$this->Abas->sysMsg('errmsg',"Invalid ID!");
			}
		}
		$data['viewfile']		=	$viewfile;
		$this->load->view($mainview,$data);
	}
	public function employee_autocomplete_list() {
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT id, last_name, first_name, middle_name, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as full_name FROM whr_employees WHERE last_name LIKE '%".$search."%' OR first_name LIKE '%".$search."%' OR middle_name LIKE '%".$search."%' ORDER BY last_name LIMIT 0, 10";
		$items	=	$this->db->query($sql);
		if($items) {
			if($items->row()) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					// $ret['id']	=	$i['id'];
					$ret[$ctr]['label']	=	$i['full_name'];
					$ret[$ctr]['value']	=	$i['id'];
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
	}



	###############################
	###                         ###
	###         Reports         ###
	###                         ###
	###############################
	public function hr_report() {$data=array();
		$data['companies']		=	$this->Abas->getCompanies();
		$data['departments']	=	$this->Abas->getDepartments();
		$data['vessels']		=	$this->Abas->getVessels();
		$this->load->view('hr/hr_report_form.php',$data);
	}
	public function hr_report_result() {$data=array();

		//note: vessel = assignment

		if(isset($_POST['company'], $_POST['vessel'], $_POST['department'], $_POST['empstat'], $_POST['from_date'], $_POST['to_date'])) {
			$company = $this->Mmm->sanitize($_POST['company']);
			$assignment = $this->Mmm->sanitize($_POST['vessel']);
			$department = $this->Mmm->sanitize($_POST['department']);
			$empstat = $this->Mmm->sanitize($_POST['empstat']);
			$from_date = $this->Mmm->sanitize($_POST['from_date']);
			$to_date = $this->Mmm->sanitize($_POST['to_date']);
			$with_salary = '';
			if(isset($_POST['salary'])){
				$with_salary = $this->Mmm->sanitize($_POST['salary']);
			}
			$data['employees'] = $this->Hr_model->getEmployeeReport($company,$from_date,$to_date,$department,$empstat,$assignment,$with_salary);

			$data['viewfile']	=	"hr/employee_report.php";
			$this->load->view('container.php',$data);
		}
		else {
			$this->Abas->sysMsg("warnmsg", "Viewing HR Report has failed. Please try again.");
			$this->Abas->redirect(HTTP_PATH."hr/hr_report");
		}
	}
	public function loans_report() {$data=array();
		$this->load->view('hr/loans_report.php',$data);
	}
	public function loans_report_result() {$data=array();
		if(isset($_POST['loanType'])) {
			$type = $this->Mmm->sanitize($_POST['loanType']);
			$data['loans'] = $this->Hr_model->getEmployeeLoan($type);
			$data['viewfile']	=	"hr/loans_report_result.php";
			$this->load->view('container.php',$data);
		}
		else {
			$this->Abas->sysMsg("warnmsg", "Viewing loan report has failed. Please try again.");
			$this->Abas->redirect(HTTP_PATH."hr/hr_report");
		}
	}
	public function elf_report_result() {$data=array();
		$data['elf'] = $this->Hr_model->getElfContribution();
		$data['viewfile']	=	"hr/elf_report_result.php";
		$this->load->view('container.php',$data);
	}
	###############################
	###                         ###
	###         Reports         ###
	###                         ###
	###############################

	public function apply_history() {
		// $all_employees	=	$this->db->query("SELECT id FROM hr_employees");
		// $all_employees	=	$all_employees->result();
		// foreach($all_employees as $ctr=>$ae) {
			// $employee	=	$this->Abas->getEmployee($ae->id);
			// if($employee['employee_status']!="") {
				// $history[$ctr]['employee_id']		=	$ae->id;
				// $history[$ctr]['effectivity_date']	=	$employee['date_hired'];
				// $history[$ctr]['value_changed']		=	"Employee Status";
				// $history[$ctr]['from_val']			=	"";
				// $history[$ctr]['to_val']			=	$employee['employee_status'];
				// $history[$ctr]['stat']				=	1;

				// $salgrade_record			=	$this->db->query("SELECT * FROM salary_grades WHERE grade='".$employee['salary_grade']."'");
				// if($salgrade_record !=false) {
					// $salgrade_record			=	$salgrade_record->row();
					// $history2[$ctr]['employee_id']		=	$ae->id;
					// $history2[$ctr]['effectivity_date']	=	$employee['date_hired'];
					// $history2[$ctr]['value_changed']	=	"Salary Grade";
					// $history2[$ctr]['from_val']			=	"";
					// $history2[$ctr]['to_val']			=	$salgrade_record->id;
					// $history2[$ctr]['stat']				=	1;
				// }

				// $history3[$ctr]['employee_id']		=	$ae->id;
				// $history3[$ctr]['effectivity_date']	=	$employee['date_hired'];
				// $history3[$ctr]['value_changed']	=	"Position";
				// $history3[$ctr]['from_val']			=	"";
				// $history3[$ctr]['to_val']			=	$employee['position'];
				// $history3[$ctr]['stat']				=	1;



			// }
		// }
		// $keystring	=	"";
		// $valstring	=	"";
		// $done		=	false;
		// $this->db->query("truncate hr_employment_history");
		// $historied	=	$this->Mmm->multiInsert("hr_employment_history",$history, "add employment history");
		// $historied	=	$this->Mmm->multiInsert("hr_employment_history",$history2, "add employment history");
		// $historied	=	$this->Mmm->multiInsert("hr_employment_history",$history3, "add employment history");
		// if($historied==true) {
			// echo "Added to History!";
		// }
		// else {
			// echo "Not Added to History!";
		// }
	}
	public function temp() {
		// $data	=	$this->db->query("SELECT * FROM TABLE_54 WHERE REMARKS<>'ENCODED'");
		// $data	=	$data->result_array();
		// echo count($data)."<br/>";
		// foreach($data as $ctr=>$ed) {

			// $insert[$ctr]['last_name']				=	$this->Mmm->sanitize($ed['LAST NAME']);
			// $insert[$ctr]['first_name']				=	$this->Mmm->sanitize($ed['FIRST NAME']);
			// $insert[$ctr]['middle_name']				=	$this->Mmm->sanitize($ed['MIDDLE NAME']);
			// $insert[$ctr]['birth_date']				=	date("Y-m-d",strtotime($ed['BIRTHDATE']));
			// $insert[$ctr]['gender']					=	'';
			// $insert[$ctr]['mobile']					=	$this->Mmm->sanitize($ed['MOBILE #']);
			// $insert[$ctr]['email']					=	'';
			// $insert[$ctr]['civil_status']				=	$this->Mmm->sanitize($ed['CIVIL STATUS']);
			// $insert[$ctr]['employee_id']				=	'';
			// $insert[$ctr]['address']					=	$this->Mmm->sanitize($ed['ADDRESS']);
			// $insert[$ctr]['city']						=	'';
			// $insert[$ctr]['zipcode']					=	$this->Mmm->sanitize($ed['ZIPCODE']);
			// $insert[$ctr]['emergency_contact_num']	=	$this->Mmm->sanitize($ed['EMERGENCY CONTACT DETAILS']);
			// $insert[$ctr]['emergency_contact_person']	=	$this->Mmm->sanitize($ed['EMERGENCY CONTACT DETAILS']);
			// $insert[$ctr]['date_hired']				=	date("Y-m-d",strtotime($ed['DATE HIRED']));
			// $insert[$ctr]['company_id']				=	$this->Mmm->sanitize($ed['COMPANY']);
			// $insert[$ctr]['position']					=	$ed['POSITION'];
			// $insert[$ctr]['salary_grade']				=	0;
			// $insert[$ctr]['department']				=	$ed['DEPARTMENT'];
			// $insert[$ctr]['experience']				=	'';
			// $insert[$ctr]['elf_rate']					=	1000;
			// $insert[$ctr]['tax_code']					=	$ed['TAX CODE'] == "" ? "S" : $this->Mmm->sanitize($ed['TAX CODE']);
			// $insert[$ctr]['tin_num']					=	$this->Mmm->sanitize($ed['TIN']);
			// $insert[$ctr]['sss_num']					=	$this->Mmm->sanitize($ed['SSS']);
			// $insert[$ctr]['ph_num']					=	$this->Mmm->sanitize($ed['PHILHEALTH']);
			// $insert[$ctr]['pagibig_num']				=	$this->Mmm->sanitize($ed['PAGIBIG']);
			// $insert[$ctr]['bank_account_num']			=	"";
			// $insert[$ctr]['vessel_id']				=	$ed['VESSEL'];
			// $insert[$ctr]['input_by']					=	$_SESSION['abas_login']['userid'];
			// $insert[$ctr]['input_on']					=	date("Y-m-d H:i:s");
			// $insert[$ctr]['stat']						=	1;
			// $insert[$ctr]['employee_status']			=	$this->Mmm->sanitize($ed['EMPLOYEE STATUS']);
		// }
		// $this->Mmm->multiInsert("hr_employees",$insert,"MASS ENCODING");
	}
}

?>
