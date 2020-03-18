<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Hr extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			$this->load->database();
			$this->load->model("Abas");
			$this->load->model("Hr_model");
			$this->load->model("Payroll_model");
			$this->load->model("Inventory_model");
			$this->load->model("Asset_Management_model");
			$this->load->model("Corporate_Services_model");
			$this->load->model("Mmm");
			$this->output->enable_profiler(FALSE);
			if(!isset($_SESSION['abas_login'])) {$this->Abas->redirect(HTTP_PATH."home"); }
			define("SIDEMENU", "Human Resources");
		}
		public function index()
		{
			$this->Abas->checkPermissions("human_resources|view");
			$data['viewfile'] = "hr/dashboard.php";
			$data['emp_count'] = $this->Hr_model->getEmployeeCount();
			$data['emp_for_transfer'] = $this->Hr_model->getEmpForTransCount();
			$data['for_awol'] = count($this->Hr_model->getEmployeeForAWOL());
			$data['leave'] = $this->Hr_model->getLeaveForApprovalCount();
			$data['overtime'] = $this->Hr_model->getOvertimeForApprovalCount();
			$this->load->view('gentlella_container.php',$data);
		}

		public function employees()
		{
			$data=array();
			$this->Abas->checkPermissions("human_resources|view");
			$data['viewfile']	=	"hr/hr_view.php";
			$this->load->view('gentlella_container.php',$data);

			if($_SESSION['abas_login']['role']=="Human Resources" || $_SESSION['abas_login']['role']=="Administrator")
			{
				$result = $this->Hr_model->getEmployeeForAWOL();
					if(count($result)>0){
						unset($_SESSION["warnmsg"]);
						$this->Abas->sysMsg("warnmsg", count($result)." employee(s) which can be set to AWOL status. Click <a href='".HTTP_PATH."hr/employees_for_awol'><u>here</u></a> to view.");	
					}
				
			}
		}
		public function view_all_employees() {
			$this->Abas->checkPermissions("human_resources|view");
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$search	=	isset($_GET['search'])?$_GET['search']:"";
			$data	=	$this->Hr_model->getAllEmployees($search,$limit,$offset,$order,$sort);
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
			$module					=	__function__ ;
			$this->Abas->checkPermissions("human_resources|add");
			$data['vessels']		=	$this->Abas->getVessels();
			$data['companies']		=	$this->Abas->getCompanies(true);
			$data['positions']		=	$this->Abas->getPositions();
			$data['departments']	=	$this->Abas->getDepartments();
			$data['taxcodes']		=	$this->Abas->getTaxCodes();
			$data['salarygrades']	=	$this->Abas->getSalaryGrades();
			$data['divisions'] 		= 	$this->Abas->getItems('divisions');
			$data['sections'] 		= 	$this->Abas->getItems('department_sections');
			$data['sub_sections']	=	$this->Abas->getItems('department_sub_sections');
			if($id=="") {
				if($action=="add") {
					$this->load->view("hr/employee_form.php",$data);
				}
				elseif($action=="insert") {
					if(isset($_POST['last_name'], $_POST['first_name'], $_POST['gender'], $_POST['company_id'])) {
						if($_POST['last_name']!="" && $_POST['first_name']!="" && $_POST['gender']!="" && $_POST['company_id']!="") {
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

							if($_POST['elf_rate']=="" || !is_numeric($_POST['elf_rate'])) {
								//$this->Abas->sysMsg("warnmsg","ELF rate not set! Defaulting P1000 / Mo.");
								//$elfrate	=	1000; //disabled due to ELF deduction no longer allowed
								$elfrate	=	0;
							}
							else {
								$elfrate	=	$this->Mmm->sanitize($_POST['elf_rate']);
							}
							if($_POST['allowance']=="" || !is_numeric($_POST['allowance'])) {
								//$this->Abas->sysMsg("warnmsg","Allowance not set! Defaulting P0 / Mo.");
								$allowance	=	0;
							}
							else {
								$allowance	=	$this->Mmm->sanitize($_POST['allowance']);
							}
							$insert['last_name']				=	$this->Mmm->sanitize($_POST['last_name']);
							$insert['first_name']				=	$this->Mmm->sanitize($_POST['first_name']);
							$insert['middle_name']				=	$this->Mmm->sanitize($_POST['middle_name']);
							$insert['birth_date']				=	$_POST['birth_date']=="" ? null : date("Y-m-d",strtotime($_POST['birth_date']));
							$insert['gender']					=	$this->Mmm->sanitize($_POST['gender']);
							$insert['mobile']					=	$this->Mmm->sanitize($_POST['mobile']);
							$insert['email']					=	$this->Mmm->sanitize($_POST['email']);
							$insert['civil_status']				=	$this->Mmm->sanitize($_POST['civil_status']);
							$insert['employee_id']				=	$this->Mmm->sanitize($_POST['employee_id']);
							$insert['address']					=	$this->Mmm->sanitize($_POST['address']);
							$insert['city']						=	$this->Mmm->sanitize($_POST['city']);
							$insert['emergency_contact_num']	=	$this->Mmm->sanitize($_POST['emergency_contact_num']);
							$insert['emergency_contact_person']	=	$this->Mmm->sanitize($_POST['emergency_contact_person']);
							$insert['date_hired']				=	$_POST['date_hired']=="" ? null : date("Y-m-d",strtotime($_POST['date_hired']));
							$insert['company_id']				=	$this->Mmm->sanitize($_POST['company_id']);
							$insert['position']					=	$this->Mmm->sanitize($_POST['position']);
							$insert['salary_grade']				=	$this->Mmm->sanitize($_POST['salary_grade']);
							$insert['department']				=	$this->Mmm->sanitize($_POST['department_id']);
							$insert['experience']				=	$this->Mmm->sanitize($_POST['experience']);
							$insert['elf_rate']					=	$elfrate;
							$insert['allowance']				=	$allowance;
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
							$insert['department_group']			= 	$_POST['group'];
							$insert['section_id']				=	$_POST['section_id'];
							$insert['division_id']				=	$_POST['division_id'];
							$insert['sub_section_id']			=	$_POST['sub_section_id'];
							
							$check	=	$this->db->query("SELECT * FROM hr_employees WHERE first_name='".$insert['first_name']."' AND last_name='".$insert['last_name']."' AND birth_date='".$insert['birth_date']."'");

							if(!empty($check)) {
								if(!$check->row()) {
									$query	=	$this->Mmm->dbInsert("hr_employees",$insert,"Insert to HR Employees");
									echo $this->db->last_query();
									// The following adds content to employment history
									$getInserted	=	$this->db->query("SELECT MAX(id) AS new_record_id FROM hr_employees LIMIT 1");
									$getInserted	=	$getInserted->row();
									$new_record_id	=	$getInserted->new_record_id;

									if(!empty($_POST['dependent_first_name']) && !empty($_POST['dependent_middle_name']) && !empty($_POST['dependent_last_name']) && !empty($_POST['dependent_birth_date'])) {
										foreach($_POST['dependent_first_name'] as $ctr=>$x) {
											if($_POST['dependent_first_name'][$ctr]!="" && $_POST['dependent_last_name'][$ctr]!="") {
												$dependents[$ctr]['employee_id']	=	$new_record_id;
												$dependents[$ctr]['first_name']		=	$this->Mmm->sanitize($_POST['dependent_first_name'][$ctr]);
												$dependents[$ctr]['middle_name']	=	$this->Mmm->sanitize($_POST['dependent_middle_name'][$ctr]);
												$dependents[$ctr]['last_name']		=	$this->Mmm->sanitize($_POST['dependent_last_name'][$ctr]);
												$dependents[$ctr]['birth_date']		=	$_POST['dependent_birth_date']=="" ? null : date("Y-m-d",strtotime($_POST['dependent_birth_date'][$ctr]));
												$dependents[$ctr]['dependent_relationship']		=	$this->Mmm->sanitize($_POST['dependent_relationship'][$ctr]);
											}
										}
										if(!empty($dependents)) {
											$this->Mmm->multiInsert("hr_employee_dependents",$dependents,"Add dependent for ".$insert['last_name'].", ".$insert['first_name']." ".$insert['middle_name']);
										}
									}

									$set_status['added_by']			=	$_SESSION['abas_login']['userid'];
									$set_status['added_on']			=	date("Y-m-d H:i:s");
									$set_status['employee_id']		=	$new_record_id;
									$set_status['stat']				=	1;
									$set_status['effectivity_date']	=	$insert['date_hired'];

									if($_POST['empstat'] != "") {
										$set_status['value_changed']		=	"Employment Status";
										$set_status['from_val']				=	"-";
										$set_status['to_val']				=	$this->Mmm->sanitize($_POST['empstat']);
										if($set_status['to_val'] == "Regular") {
											$set_status['effectivity_date']	=	$insert['date_hired'];
										}
										$this->Mmm->dbInsert("hr_employment_history", $set_status, "Set employee status");
									}
									if($_POST['salary_grade'] != "") {
										$set_status['value_changed']		=	"Salary Grade";
										$set_status['from_val']				=	"-";
										$set_status['to_val']				=	$this->Mmm->sanitize($_POST['salary_grade']);
										$this->Mmm->dbInsert("hr_employment_history", $set_status, "Set salary grade");
									}
									if($_POST['position'] != "") {
										$set_status['value_changed']		=	"Position";
										$set_status['from_val']				=	"-";
										$set_status['to_val']				=	$this->Mmm->sanitize($_POST['position']);
										$this->Mmm->dbInsert("hr_employment_history", $set_status, "Set position");
									}

									if($_POST['department_id'] != "") {
										$set_status['value_changed']		=	"Department";
										$set_status['from_val']				=	"-";
										$set_status['to_val']				=	$this->Mmm->sanitize($_POST['department_id']);
										$this->Mmm->dbInsert("hr_employment_history", $set_status, "Set department");
									}
									if($_POST['vessel'] != "") {
										$set_status['value_changed']		=	"Assigned To";
										$set_status['from_val']				=	"-";
										$set_status['to_val']				=	$this->Mmm->sanitize($_POST['vessel']);
										$this->Mmm->dbInsert("hr_employment_history", $set_status, "Set vessel");
									}

									if($query==true) {
										$notif_msg	=	"A new employee (".$insert['last_name'].", ".$insert['first_name']." ".$insert['middle_name'].") has been added to the HRIS by ".$_SESSION['abas_login']['fullname'].".";
										$this->Abas->sysNotif("New employee", $notif_msg, "Human Resources");
										$this->Abas->sysMsg("sucmsg", "Employee record successfully added!");
										$this->Abas->redirect(HTTP_PATH."hr/employees");
									}else{ 
										$this->Abas->sysMsg("errmsg", "An unknown error has occurred!");
									}
								}else {
									$this->Abas->sysMsg("warnmsg", "That employee already exists!");
								}
							}else {	
								$this->Abas->sysMsg("warnmsg", "That employee already exists!");
							}
						}else {
							$this->Abas->sysMsg("warnmsg", "Please make sure the basic info is filled in!");
						}
					}else {	
						$this->Abas->sysMsg("warnmsg", "Please make sure the basic info is filled in!");
					}
				}
			}
			else {
				if(is_numeric($id)) {
					$employee_record			=	$this->Abas->getEmployee($id);
					if(!empty($employee_record)) {
						$data['employee_record']=	$employee_record;
						$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$id."'>".$employee_record['full_name']."</a>";
						$dependents			=	$this->db->query("SELECT * FROM hr_employee_dependents WHERE employee_id=".$id." ORDER BY birth_date DESC");
						if($dependents!=false) {
							if($dependents->row()) {
								$dependents	=	$dependents->result_array();
								$data['dependents']	=	$dependents;
							}
						}
						if($action=="edit") {
							$this->load->view("hr/employee_form.php",$data);
						}
						elseif($action=="update") {
							if(isset($_POST['last_name'], $_POST['first_name'])) {
								if($_POST['last_name']!="" && $_POST['first_name']!="") {
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
									if($_POST['elf_rate']=="" || !is_numeric($_POST['elf_rate'])) {
										//$this->Abas->sysMsg("warnmsg","ELF rate not set! Defaulting P1000 / Mo.");
										//$elfrate	=	1000; // disabled
										$elfrate	=	0;
									}
									else {
										$elfrate	=	$this->Mmm->sanitize($_POST['elf_rate']);
									}
									if($_POST['allowance']=="" || !is_numeric($_POST['allowance'])) {
										//$this->Abas->sysMsg("warnmsg","Allowance not set! Defaulting P0 / Mo.");
										$allowance	=	0;
									}
									else {
										$allowance	=	$this->Mmm->sanitize($_POST['allowance']);
									}
									//edith
									/*$update_array = array(
										'last_name' => $_POST['last_name'],
										'first_name' => $_POST['first_name'],
										'middle_name' => $_POST['middle_name'],
										'birth_date' => $_POST['birth_date'],
										'gender' => $_POST['gender'],
										'mobile' => $_POST['mobile'],
										'email' => $_POST['email'],
										'civil_status' => $_POST['civil_status'],
										'employee_id' => $_POST['employee_id'],
										'address' => $_POST['address'],
										'city' => $_POST['city'],
										'emergency_contact_num' => $_POST['emergency_contact_num'],
										'emergency_contact_person' => $_POST['emergency_contact_person'],
										'date_hired' => $_POST['date_hired'],
										'experience' => $_POST['experience'],
										'elf_rate' => $_POST['elf_rate'],
										'allowance' => $_POST['allowance'],
										'tax_code' => $_POST['tax_code'],
										'tin_num' => $_POST['tin_num'],
										'sss_num' => $_POST['sss_num'],
										'ph_num' => $_POST['ph_num'],
										'pagibig_num' => $_POST['pagibig_num'],
										'bank_account_num' => $_POST['bank_account_num'],
										'modified_by' => $_SESSION['abas_login']['userid'],
										'modified_on' => date('Y-m-d'),
										'section_id' => $_POST['section_id'],
										'department_group' => $_POST['group'],
										'company_id' => $_POST['company_id'],
										'department' => $_POST['department_id'],
										'division_id' => $_POST['division_id'],
										'sub_section_id' => $_POST['sub_section_id']
									);*/

									//$this->Abas->updateItem('hr_employees',$update_array,array('id'=>$id));
									
									$update['last_name']				=	$this->Mmm->sanitize($_POST['last_name']);
									$update['first_name']				=	$this->Mmm->sanitize($_POST['first_name']);
									$update['middle_name']				=	$this->Mmm->sanitize($_POST['middle_name']);
									$update['birth_date']				=	($_POST['birth_date']!="")?date("Y-m-d H:i:s",strtotime($_POST['birth_date'])):null;
									$update['gender']					=	$this->Mmm->sanitize($_POST['gender']);
									$update['mobile']					=	$this->Mmm->sanitize($_POST['mobile']);
									$update['email']					=	$this->Mmm->sanitize($_POST['email']);
									$update['civil_status']				=	$this->Mmm->sanitize($_POST['civil_status']);
									$update['employee_id']				=	$this->Mmm->sanitize($_POST['employee_id']);
									//if(isset($_POST['empstat'])) {
									//	$update['employee_status']			=	$this->Mmm->sanitize($_POST['empstat']);
									//}
									$update['address']					=	$this->Mmm->sanitize($_POST['address']);
									$update['city']						=	$this->Mmm->sanitize($_POST['city']);
									//$update['zipcode']					=	$this->Mmm->sanitize($_POST['zip']);
									$update['emergency_contact_num']	=	$this->Mmm->sanitize($_POST['emergency_contact_num']);
									$update['emergency_contact_person']	=	$this->Mmm->sanitize($_POST['emergency_contact_person']);
									$update['date_hired']				=	($_POST['date_hired']!="")?date("Y-m-d H:i:s",strtotime($_POST['date_hired'])):null;
									if(isset($_POST['company'])) {
										$update['company_id']				=	$this->Mmm->sanitize($_POST['company']);
									}
									if(isset($_POST['position'])) {
										$update['position']					=	$this->Mmm->sanitize($_POST['position']);
									}
									if(isset($_POST['salary_grade'])) {
										$update['salary_grade']				=	$this->Mmm->sanitize($_POST['salary_grade']);
									}
									$update['department']				=	$this->Mmm->sanitize($_POST['department_id']);
									$update['experience']				=	$this->Mmm->sanitize($_POST['experience']);
									$update['elf_rate']					=	$elfrate;
									$update['allowance']				=	$allowance;
									$update['tax_code']					=	$taxcode;
									$update['tin_num']					=	$this->Mmm->sanitize($_POST['tin_num']);
									$update['sss_num']					=	$this->Mmm->sanitize($_POST['sss_num']);
									$update['ph_num']					=	$this->Mmm->sanitize($_POST['ph_num']);
									$update['pagibig_num']				=	$this->Mmm->sanitize($_POST['pagibig_num']);
									$update['bank_account_num']			=	$this->Mmm->sanitize($_POST['bank_account_num']);
									//if(isset($_POST['vessel'])) {
									//	$update['vessel_id']				=	$this->Mmm->sanitize($_POST['vessel']);
									//}
									$update['modified_by']				=	$_SESSION['abas_login']['userid'];
									$update['modified_on']				=	date("Y-m-d H:i:s");
									$update['department_group']					=	$this->Mmm->sanitize($_POST['group']);
									$update['section_id']				=	$this->Mmm->sanitize($_POST['section_id']);
									$update['sub_section_id']			=	$this->Mmm->sanitize($_POST['sub_section_id']);
									
									$query	=	$this->Mmm->dbUpdate("hr_employees",$update, $id, "Update HR Employee record ".$update['last_name'].", ".$update['first_name']);
									if(!empty($_POST['dependent_first_name']) && !empty($_POST['dependent_middle_name']) && !empty($_POST['dependent_last_name']) && !empty($_POST['dependent_birth_date'])) {
										$this->db->query("DELETE FROM hr_employee_dependents WHERE employee_id=".$id);
										foreach($_POST['dependent_first_name'] as $ctr=>$x) {
											if($_POST['dependent_first_name'][$ctr]!="" && $_POST['dependent_last_name'][$ctr]!="") {
												$dependent[$ctr]['employee_id']	=	$id;
												$dependent[$ctr]['first_name']	=	$this->Mmm->sanitize($_POST['dependent_first_name'][$ctr]);
												$dependent[$ctr]['middle_name']	=	$this->Mmm->sanitize($_POST['dependent_middle_name'][$ctr]);
												$dependent[$ctr]['last_name']	=	$this->Mmm->sanitize($_POST['dependent_last_name'][$ctr]);
												$dependent[$ctr]['birth_date']	=	$_POST['dependent_birth_date']=="" ? null : date("Y-m-d",strtotime($_POST['dependent_birth_date'][$ctr]));
												$dependent[$ctr]['dependent_relationship']		=	$this->Mmm->sanitize($_POST['dependent_relationship'][$ctr]);
											}
										}
										if(!empty($dependent)) {
											$this->Mmm->multiInsert("hr_employee_dependents",$dependent,"Add dependent for ".$_POST['last_name'].", ".$_POST['first_name']." ".$_POST['middle_name']);
										}
									}

									if($query){
										$notif_msg	=	"The profile of ".$employee_link." has been updated by ".$_SESSION['abas_login']['fullname'].".";
										$this->Abas->sysNotif("Edited profile", $notif_msg, "Human Resources");
										$this->Abas->sysMsg('sucmsg',"Profile of ".$employee_link." successfully updated!");
										$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$id);
									}else{
										$this->Abas->sysMsg('errmsg',"Please make sure the basic info is filled in!");
									}
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
							$payroll_info	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE emp_id=".$id." ORDER BY id DESC");
							if($payroll_info!=false) {
								if($payroll_info->row()) {
									$payroll_info	=	$payroll_info->result();
									$data['payroll_info']	=	$payroll_info;
								}
							}
							$bonuses		=	$this->db->query("SELECT * FROM hr_bonus WHERE employee_id=".$id." AND stat=1 ORDER BY id DESC");
							if($bonuses!=false) {
								if($bonuses->row()) {
									$bonuses	=	$bonuses->result_array();
									$data['bonuses']	=	$bonuses;
								}
							}
							$employee_history	=	$this->db->query("SELECT * FROM hr_employment_history WHERE employee_id=".$id." AND stat=1 ORDER BY effectivity_date DESC");
							if($employee_history!=false) {
								if($employee_history->row()) {
									$employee_history	=	$employee_history->result_array();
									$data['employee_history']	=	$employee_history;
								}
							}
							$overtimes		=	$this->db->query("SELECT * FROM hr_overtime WHERE employee_id=".$id." AND stat=1 ORDER BY id DESC");
							if($overtimes!=false) {
								if($overtimes->row()) {
									$overtimes	=	$overtimes->result_array();
									$data['overtimes']	=	$overtimes;
								}
							}
							$night_diffs		=	$this->db->query("SELECT * FROM hr_night_differential WHERE employee_id=".$id." AND stat=1 ORDER BY id DESC");
							if($night_diffs!=false) {
								if($night_diffs->row()) {
									$night_diffs	=	$night_diffs->result_array();
									$data['night_diffs']	=	$night_diffs;
								}
							}
							$undertimes		=	$this->db->query("SELECT * FROM hr_undertime WHERE employee_id=".$id." AND stat=1 ORDER BY id DESC");
							if($undertimes!=false) {
								if($undertimes->row()) {
									$undertimes	=	$undertimes->result_array();
									$data['undertimes']	=	$undertimes;
								}
							}
							$loans			=	$this->db->query("SELECT * FROM hr_loans WHERE emp_id=".$id." ORDER BY id DESC");
							if($loans!=false) {
								if($loans->row()) {
									$loans	=	$loans->result_array();
									$data['loans']	=	$loans;
								}
							}
							$leaves			=	$this->db->query("SELECT * FROM hr_leaves WHERE emp_id=".$id." AND stat=1 ORDER BY date_from DESC");
							if($leaves!=false) {
								if($leaves->row()) {
									$leaves	=	$leaves->result_array();
									$data['leaves']	=	$leaves;
								}
							}

							$start['fulldate']	=	$employee_record['date_hired'];
							$out_of_company = $this->db->query("SELECT * FROM hr_employment_history WHERE employee_id=".$employee_record['id']." AND (to_val='Resigned' OR to_val='Retired' OR to_val='Terminated' OR to_val='Separated') ORDER BY effectivity_date DESC LIMIT 1");
							if($out_of_company){
								if($row=$out_of_company->row()){
									$finish['fulldate'] = date('Y-m-d',strtotime($row->effectivity_date));	
								}else{
									$finish['fulldate'] = date('Y-m-d');
								}
							}

							$d1 = new DateTime($start['fulldate']);
							$d2 = new DateTime($finish['fulldate']);
							$diff = $d1->diff($d2);

							$years_difference	=	$diff->y." year(s) and ";
							$months_difference	=	$diff->m." month(s) and ";
							$days_difference	=	$diff->d." day(s)";

							$data['time_in_company']	=	$years_difference.$months_difference.$days_difference;

							$fixed_assets = $this->db->query("SELECT x.id,x.fixed_asset_id,x.remarks,x.status, x.date_issued, x.date_returned,x.condition_of_returned_item,x.received_by,z.requested_on,z.requested_by,z.id as accountability_id FROM am_fixed_asset_accountability_details AS x INNER JOIN am_fixed_asset_accountability AS z ON z.id=x.accountability_id WHERE z.requested_by=".$id);
							if($fixed_assets->row()) {
								$data['fixed_assets']	=	$fixed_assets->result_array();
							}

							$data['viewfile']		=	"hr/employee_profile.php";
							$this->load->view("gentlella_container.php",$data);
						
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
		}
		public function update_leave($id) {
			$this->Abas->checkPermissions("human_resources|leave");
			if($e=$this->Abas->getEmployee($id)) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$id."'>".$e['full_name']."</a>";
				if(isset($_POST)) {
					if(is_numeric($_POST['leave_credits'])) {
						$update['leave_credits']	=	$this->Mmm->sanitize($_POST['leave_credits']);
						$upd	=	$this->Mmm->dbUpdate("hr_employees",$update,$id,"Update leave credits for ".$e['full_name']);
						if($upd==true) {
							$this->Abas->sysMsg('sucmsg',"Leave credits for Employee ".$employee_link." updated.");
							
						}
						else {
							$this->Abas->sysMsg('errmsg',"Leave credits not updated.");
						
						}
					}
					else {
						$this->Abas->sysMsg('errmsg',"Your input is invalid! Leave credits not updated.");
					
					}
				}
				else {
					$this->Abas->sysMsg('errmsg',"No input detected! Leave credits not updated.");
			
				}
			}
			else {
				$this->Abas->sysMsg('errmsg',"Employee not found! Leave credits not updated.");
				
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$id);
		}
		public function update_elf($id) {
			$this->Abas->checkPermissions("human_resources|elf");
			if($e=$this->Abas->getEmployee($id)) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$id."'>".$e['full_name']."</a>";
				if(isset($_POST)) {
					if($_POST['total_elf_contribution']>=0) {
						$update['total_elf_contribution']	=	$this->Mmm->sanitize($_POST['total_elf_contribution']);
						$upd	=	$this->Mmm->dbUpdate("hr_employees",$update,$id,"Update ELF contribution for ".$e['full_name']);
						if($upd==true) {
							$this->Abas->sysMsg('sucmsg',"Total ELF contribution for ".$employee_link." updated.");
						
						}
						else {
							$this->Abas->sysMsg('errmsg',"Total ELF contribution not updated.");
						
						}
					}
					else {
						$this->Abas->sysMsg('errmsg',"Your input is invalid! Total ELF contribution not updated.");
					
					}
				}
				else {
					$this->Abas->sysMsg('errmsg',"No input detected! Total ELF contribution not updated.");
				
				}
			}
			else {
				$this->Abas->sysMsg('errmsg',"Employee not found! Total ELF contribution not updated.");
				
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$id);
		}
		public function update_loan($id) {
			$this->Abas->checkPermissions("human_resources|loan");
			if($e=$this->Abas->getEmployee($id)) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$id."'>".$e['full_name']."</a>";
				if(isset($_POST)) {
					if(isset($_POST['loanType'], $_POST['loanPrincipal'], $_POST['loanAmortization'], $_POST['loanDate'])) {
						$newloan['emp_id']				=	$id;
						$newloan['loan_type']			=	$this->Mmm->sanitize($_POST['loanType']);
						$newloan['date_loan']			=	$_POST['loanDate']!=""?date("Y-m-d", strtotime($_POST['loanDate'])):date("Y-m-d");
						// $newloan['date_added']			=	date("Y-m-d");
						$newloan['due_date_loan']		=	date("Y-m-d", strtotime($_POST['loanDueDate']));
						$newloan['amount_loan']			=	$this->Mmm->sanitize($_POST['loanPrincipal']);
						$newloan['monthly_amortization']=	$this->Mmm->sanitize($_POST['loanAmortization']);
						$newloan['remark']				=	$this->Mmm->sanitize($_POST['loanRemark']);
						$newloan['stat']				=	1;

						$upd	=	$this->Mmm->dbInsert("hr_loans",$newloan, "New loan application for ".$e['full_name']);
						if($upd==true) {
							$this->Abas->sysMsg('sucmsg',"Loan for Employee ".$employee_link." updated.");
							
						}
						else {
							$this->Abas->sysMsg('errmsg',"Loan not updated.");
			
						}
					}
					else {
						$this->Abas->sysMsg('errmsg',"Your input is invalid! Loan not updated.");
					
					}
				}
				else {
					$this->Abas->sysMsg('errmsg',"No input detected! Loan not updated.");
			
				}
			}
			else {
				$this->Abas->sysMsg('errmsg',"Employee not found! Loan not updated.");
				
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$id);
		}
		public function recommend($id) {$data=array();
			$this->Abas->checkPermissions("human_resources|update");
			if($e=$this->Abas->getEmployee($id)) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$id."'>".$e['full_name']."</a>";
				if(isset($_POST)) {
					$update['employee_id']			=	$id;
					$update['employment_status']	=	"Recommended by ".$_SESSION['abas_login']['fullname'].": ".$this->Mmm->sanitize($_POST['recommendation_type']);
					$update['position']				=	$e['position'];
					$update['remarks']				=	$this->Mmm->sanitize($_POST['recommendation_remarks']);
					$update['stat']					=	0;
					$update['start_date']			=	date("Y-m-d");
					$update['review_date']			=	date("Y-m-d",strtotime("1 Jan 1970"));

					$upd	=	$this->Mmm->dbInsert("hr_employment_history",$update,"Recommendation");
					if($upd==true) {
						$this->Abas->sysMsg('sucmsg',"Recommendation for ".$employee_link." sent.");
						$this->Abas->redirect(HTTP_PATH."hr");
					}
					else {
						$this->Abas->sysMsg('errmsg',"Recommendation.");
						$this->Abas->redirect(HTTP_PATH."hr");
					}
				}
				else {
					$this->Abas->sysMsg('errmsg',"No input detected! Recommendation not sent.");
					$this->Abas->redirect(HTTP_PATH."hr");
				}
			}
			else {
				$this->Abas->sysMsg('errmsg',"Employee not found! Recommendation not sent.");
				$this->Abas->redirect(HTTP_PATH."hr");
			}
		}
		public function bonus($action="", $eid="", $ndid=0) {
			$this->Abas->checkPermissions("human_resources|update");
			$employee			=	$this->Abas->getEmployee($eid);
			if($employee!=false) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$eid."'>".$employee['full_name']."</a>";
				if($action=="insert") {
					if(isset($_POST['bonus_type'])) { 
						
						$bonus_date	=	date("Y-m-d",strtotime($_POST['bonus_date']));
						$bonus_amount=	$this->Mmm->sanitize($_POST['bonus_amount']);
						$bonus_type		=	$this->Mmm->sanitize($_POST['bonus_type']);
						$bonus_remarks		=	$this->Mmm->sanitize($_POST['bonus_remarks']);

						$add['employee_id']	=	$eid;
						$add['release_date']		=	$bonus_date;
						$add['amount']		=	$bonus_amount;
						$add['type']		=	$bonus_type;
						$add['remarks']		=	$bonus_remarks;
						$add['added_by']	=	$_SESSION['abas_login']['userid'];
						$add['added_on']	=	date("Y-m-d H:i:s");
						$add['is_computed']	=	0;
						$add['stat']		=	1;

						$added				=	$this->Mmm->dbInsert("hr_bonus", $add, "New Bonus for ".$employee['full_name']);
						if($added==true) {
							$this->Abas->sysMsg('sucmsg', 'Bonus for '.$employee_link.' Added! It will be automatically computed in the next payroll period.');
						}
						else {
							$this->Abas->sysMsg('errmsg', 'An error has occurred! Please try again.');
						}
					}
					else {
						$this->Abas->sysMsg('errmsg', 'Your submission lacks input! Please try again.');
					}
				}
				elseif($action=="cancel") {
					$check	=	$this->db->query("SELECT * FROM hr_bonus WHERE id=".$ndid);
					if($check!=false) {
						$nd['stat']	=	0;
						$cancelled	=	$this->Mmm->dbUpdate("hr_bonus", $nd, $ndid, "Cancelled Bonus for ".$employee['full_name']);
						if($cancelled==true) {
							$this->Abas->sysMsg("sucmsg", "Bonus for ".$employee_link." has been cancelled.");
						}
						else {
							$this->Abas->sysMsg("errmsg", "Bonus record for ".$employee_link." not cancelled! Please try again.");
						}
					}
					else {
						$this->Abas->sysMsg("errmsg", "Bonus record for ".$employee_link." not found! Please try again.");
					}
				}
			}
			else {
				$this->Abas->sysMsg('errmsg', 'Employee not found! Please try again.');
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$eid);
		}
		public function bonus_report($action,$bonus_id=""){
			if($action=="filter"){
				$this->load->view('hr/bonus/filter.php');
			}elseif($action=="report"){
				if(isset($_POST['release_date'])){
					$release_date = $this->Mmm->sanitize($_POST['release_date']);
					$type = $this->Mmm->sanitize($_POST['type']);
					$data['bonuses'] = $this->Hr_model->getEmployeeBonus($release_date,$type);
					$data['release_date'] = $release_date;
					$data['type'] = $type ;
					$data['viewfile'] = "hr/bonus/report.php";
					$this->load->view('gentlella_container.php',$data);
				}
			}elseif($action=="approve_all"){
				$this->Abas->checkPermissions("human_resources|approve_bonus");
				if(isset($_POST['release_date'])){
					$release_date = $this->Mmm->sanitize($_POST['release_date']);
					$type = $this->Mmm->sanitize($_POST['type']);
					$approver = $_SESSION['abas_login']['userid'];
					$now = date('Y-m-d H:m:s');
					$sql = "UPDATE hr_bonus SET approved_by=".$approver.", approved_on='".$now."' WHERE release_date='".$release_date."' AND type='".$type."'AND approved_by=0";
					$query = $this->db->query($sql);
					$notif_msg = $_SESSION['abas_login']['fullname']." has approved all employee Bonus or 13th Month Pay.";
					$this->Abas->sysNotif("Approved All Employee Bonus/13th Month", $notif_msg, "Human Resources");
					$this->Abas->sysMsg("sucmsg",$notif_msg);
					$data['bonuses'] = $this->Hr_model->getEmployeeBonus($release_date,$type);
					$data['release_date'] = $release_date;
					$data['type'] = $type ;
					$data['viewfile'] = "hr/bonus/report.php";
					$this->load->view('gentlella_container.php',$data);
				}else{
					$this->Abas->sysMsg("errmsg","An error occurred. Please try again.");
				}
			}
		}
		public function overtime($action="", $eid="", $otid=0) {
			$this->Abas->checkPermissions("human_resources|update");
			$employee			=	$this->Abas->getEmployee($eid);
			if($employee!=false) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$eid."'>".$employee['full_name']."</a>";
				if($action=="insert") {
					if(isset($_POST['ot_time'], $_POST['ot_type'])) { // adding new
						
						$ot_time	=	$this->Mmm->sanitize($_POST['ot_time']);
						$ot_type		=	$this->Mmm->sanitize(str_replace("%","",$_POST['ot_type']));
						$ot_date	=	date("Y-m-d",strtotime($_POST['ot_date']));
						$reason		=	$this->Mmm->sanitize($_POST['ot_reason']);

						$add['employee_id']	=	$eid;
						$add['ot_time']		=	$ot_time;
						$add['ot_date']		=	$ot_date;
						$add['type']		=	$ot_type;
						if($ot_type=="Regular Day"){
							$ot_rate = "25";
						}elseif($ot_type=="Rest Day" || $ot_type=="Special Holiday"){
							$ot_rate = "30";
						}elseif($ot_type=="Legal Holiday"){
							$ot_rate = "200";
						}elseif($ot_type="Legal Holiday on Rest Day"){
							$ot_rate = "260";
						}elseif($ot_type="Special Holiday on Rest Day"){
							$ot_rate = "50";
						}
						$add['rate']		=	$ot_rate;
						$add['reason']		=	$reason;
						$add['computed']	=	0;
						$add['approved']	=	$_SESSION['abas_login']['userid'];
						$add['stat']		=	1;

						$added				=	$this->Mmm->dbInsert("hr_overtime", $add, "New Overtime for ".$employee['full_name']);
						if($added==true) {
							$this->Abas->sysMsg('sucmsg', 'Overtime for '.$employee_link.' Added! It will be automatically computed in the next payroll period.');
						}
						else {
							$this->Abas->sysMsg('errmsg', 'An error has occurred! Please try again.');
						}
					}
					else {
						$this->Abas->sysMsg('errmsg', 'Your submission lacks input! Please try again.');
					}
				}
				elseif($action=="cancel") {
					$check	=	$this->db->query("SELECT * FROM hr_overtime WHERE id=".$otid);
					if($check!=false) {
						$ot['stat']	=	0;
						$cancelled	=	$this->Mmm->dbUpdate("hr_overtime", $ot, $otid, "Cancelled OT for ".$employee['full_name']);
						if($cancelled==true) {
							$this->Abas->sysMsg("sucmsg", "OT for ".$employee_link." has been cancelled.");
						}
						else {
							$this->Abas->sysMsg("errmsg", "OT record for ".$employee_link." not cancelled! Please try again.");
						}
					}
					else {
						$this->Abas->sysMsg("errmsg", "OT record for ".$employee_link." not found! Please try again.");
					}
				}
			}
			else {
				$this->Abas->sysMsg('errmsg', 'Employee not found! Please try again.');
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$eid);
		}
		public function undertime($action="", $eid="", $otid=0) {
			$this->Abas->checkPermissions("human_resources|update");
			$employee			=	$this->Abas->getEmployee($eid);
			if($employee!=false) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$eid."'>".$employee['full_name']."</a>";
				if($action=="insert") {
					if(isset($_POST['ut_time'])) { // adding new
						// echo "<pre>";print_r($_POST);echo "</pre>";
						$ut_time	=	$this->Mmm->sanitize($_POST['ut_time']);
						// $rate		=	$this->Mmm->sanitize(str_replace("%","",$_POST['ut_rate']));
						$ut_date	=	date("Y-m-d",strtotime($_POST['ut_date']));
						$reason		=	$this->Mmm->sanitize($_POST['ut_reason']);

						$add['employee_id']	=	$eid;
						$add['ut_time']		=	$ut_time;
						$add['ut_date']		=	$ut_date;
						// $add['rate']		=	$rate;
						$add['reason']		=	$reason;
						$add['computed']	=	0;
						$add['approved']	=	$_SESSION['abas_login']['userid'];
						$add['stat']		=	1;

						$added				=	$this->Mmm->dbInsert("hr_undertime", $add, "New Undertime for ".$employee['full_name']);
						if($added==true) {
							$this->Abas->sysMsg('sucmsg', 'Undertime for '.$employee_link.' Added! It will be automatically computed in the next payroll period.');
						}
						else {
							$this->Abas->sysMsg('errmsg', 'An error has occurred! Please try again.');
						}
					}
					else {
						$this->Abas->sysMsg('errmsg', 'Your submission lacks input! Please try again.');
					}
				}
				elseif($action=="cancel") {
					$check	=	$this->db->query("SELECT * FROM hr_undertime WHERE id=".$otid);
					if($check!=false) {
						$ot['stat']	=	0;
						$cancelled	=	$this->Mmm->dbUpdate("hr_undertime", $ot, $otid, "Cancelled UT for ".$employee['full_name']);
						if($cancelled==true) {
							$this->Abas->sysMsg("sucmsg", "UT for ".$employee_link." has been cancelled.");
						}
						else {
							$this->Abas->sysMsg("errmsg", "UT record for ".$employee_link." not cancelled! Please try again.");
						}
					}
					else {
						$this->Abas->sysMsg("errmsg", "UT record for ".$employee_link." not found! Please try again.");
					}
				}
			}
			else {
				$this->Abas->sysMsg('errmsg', 'Employee not found! Please try again.');
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$eid);
		}
		public function night_differential($action="", $eid="", $ndid=0) {
			$this->Abas->checkPermissions("human_resources|update");
			$employee			=	$this->Abas->getEmployee($eid);
			if($employee!=false) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$eid."'>".$employee['full_name']."</a>";
				if($action=="insert") {
					if(isset($_POST['nd_time'])) { 
						$nd_time	=	$this->Mmm->sanitize($_POST['nd_time']);
						$nd_date	=	date("Y-m-d",strtotime($_POST['nd_date']));
						$reason		=	$this->Mmm->sanitize($_POST['nd_reason']);

						$add['employee_id']	=	$eid;
						$add['night_diff_date']		=	$nd_date;
						$add['night_diff_hours']		=	$nd_time;
						$add['reason']		=	$reason;
						$add['added_by']	=	$_SESSION['abas_login']['userid'];
						$add['added_on']	=	date("Y-m-d H:m:s");
						$add['is_computed']	=	0;
						$add['stat']		=	1;
						$added				=	$this->Mmm->dbInsert("hr_night_differential", $add, "New Night Differential for ".$employee['full_name']);
						if($added==true) {
							$this->Abas->sysMsg('sucmsg', 'Night Differential for '.$employee_link.' Added! It will be automatically computed in the next payroll period.');
						}
						else {
							$this->Abas->sysMsg('errmsg', 'An error has occurred! Please try again.');
						}
					}
					else {
						$this->Abas->sysMsg('errmsg', 'Your submission lacks input! Please try again.');
					}
				}
				elseif($action=="cancel") {
					$check	=	$this->db->query("SELECT * FROM hr_night_differential WHERE id=".$ndid);
					if($check!=false) {
						$nd['stat']	=	0;
						$cancelled	=	$this->Mmm->dbUpdate("hr_night_differential", $nd, $ndid, "Cancelled Night Differential for ".$employee['full_name']);
						if($cancelled==true) {
							$this->Abas->sysMsg("sucmsg", "Night Differential for ".$employee_link." has been cancelled.");
						}
						else {
							$this->Abas->sysMsg("errmsg", "Night Differential record for ".$employee_link." not cancelled! Please try again.");
						}
					}
					else {
						$this->Abas->sysMsg("errmsg", "Night Differential record for ".$employee_link." not found! Please try again.");
					}
				}
			}
			else {
				$this->Abas->sysMsg('errmsg', 'Employee not found! Please try again.');
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$eid);
		}
		public function apply_leave($id, $leaveid="") {
			$this->Abas->checkPermissions("human_resources|update");
			if($e=$this->Abas->getEmployee($id)) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$id."'>".$e['full_name']."</a>";
				if(isset($_POST['leave_type']) && $leaveid=="") { // adds leave
					$insert['emp_id']				=	$id;
					$insert['leave_type']			=	$this->Mmm->sanitize($_POST['leave_type']);
					$insert['date_created']			=	date("Y-m-d");
					$insert['date_from']			=	date("Y-m-d", strtotime($_POST['leave_start']));
					$insert['date_to']				=	date("Y-m-d", strtotime($_POST['leave_end']));
					$insert['no_of_days']			=	$this->Mmm->sanitize($_POST['leave_limit']);
					$insert['reason']				=	$this->Mmm->sanitize($_POST['leave_reason']);
					$insert['stat']					=	1;
					$insert['calculate']			=	0;
					$ins	=	$this->Mmm->dbInsert("hr_leaves",$insert,"Add Leave for ".$e['full_name']);

					if($insert['leave_type']=="Absence") {
						$update['absences']		=	$e['absences']+$insert['no_of_days'];
					}
					else {

						//For non-vessel employee
						if($e['vessel_id']>99989) { // $this->Abas->sysMsg("errmsg","not in vessel".$update['leave_credits']);
							if($update['leave_credits'] < 0) {
								$this->Abas->sysMsg("errmsg","No more leave credits.");
								$update['absences']		=	$e['absences']+abs($update['leave_credits']);
								$update['leave_credits']=	0;
							}
						}
						else {
							//For vessel crew only
							/*if($insert['leave_type']=="Disembarkation"){
								$insertX['employee_id']				=	$id;
								$insertX['effectivity_date']		=	$insert['date_from'];
								$insertX['value_changed']			=	"Employment Status";
								$insertX['from_val']				=	$e['employee_status'];
								$insertX['to_val']					=	"On-leave";
								$insertX['stat']					=	1;
								$insertX['added_by']				=	$_SESSION['abas_login']['userid'];
								$insertX['added_on']				=	date("Y-m-d H:i:s");
								$this->Mmm->dbInsert("hr_employment_history",$insertX,"Add On-leave status for ".$e['full_name']);
								$update['employee_status']	=	"On-leave";;
								$this->Mmm->dbUpdate("hr_employees", $update, $id, "Update Employee History for ".$e['full_name']);
								$update['absences']		=	$e['absences']+$insert['no_of_days'];
							}else{*/
								$update['absences']			=	$e['absences']+$insert['no_of_days'];
								$update['leave_credits']	=	$e['leave_credits'];
							//}

						}
					}
					$upd	=	$this->Mmm->dbUpdate("hr_employees",$update,$id,"Use leave credits of ".$e['full_name']);

					if($upd==true) {
						$this->Abas->sysMsg('sucmsg',"Leave for ".$employee_link." sent.");
					}
					else {
						$this->Abas->sysMsg('errmsg',"Leave not encoded!");
					}
				}
				elseif(!isset($_POST['leave_type']) && $leaveid!="") { // cancels leave
					$check	=	$this->db->query("SELECT * FROM hr_leaves WHERE id=".$leaveid." AND emp_id=".$id);
					if($check) {
						if($check=$check->row()) {
							$empupdate['leave_credits']		=	$e['leave_credits'] + abs($check->no_of_days);

							if($e['vessel_id']>99990) {
								$empupdate['leave_credits']		=	$e['leave_credits'] + abs($check->no_of_days);
								$upd	=	$this->Mmm->dbUpdate("hr_employees",$empupdate,$id,"Refund leave credits for ".$e['full_name']);
							}

							$this->Abas->sysMsg("sucmsg", "Leave credits refund: ".$check->no_of_days.".");
							if($upd==true) { $this->Abas->sysMsg("sucmsg", "Leave credits refunded for ".$employee_link."."); }
							else { $this->Abas->sysMsg("warnmsg", "Leave credits not refunded for ".$employee_link."."); }

							$update['stat']	=	0;
							$result	=	$this->Mmm->dbUpdate("hr_leaves", $update, $leaveid, "Cancel leave for ".$e['full_name']);
							if($result==true) { $this->Abas->sysMsg("sucmsg", "Leave cancelled for ".$employee_link."."); }
							else { $this->Abas->sysMsg("warnmsg", "An error has occurred, leave not updated for ".$employee_link."."); }
						}
					}
				}
				else { // ??
					$this->Abas->sysMsg('errmsg',"No input detected! Leave not sent.");
				}
			}
			else {
				$this->Abas->sysMsg('errmsg',"Employee not found! Leave not sent.");
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$id);
		}
		public function ehistory($action="", $id="", $history_id="") {
			$this->Abas->checkPermissions("human_resources|update");
			$access_salary	=	$this->Abas->checkPermissions("human_resources|salary_editing",false);
			$access_status	=	$this->Abas->checkPermissions("human_resources|edit",false);

			if($e=$this->Abas->getEmployee($id)) {
				$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$id."'>".$e['full_name']."</a>";
				if(!empty($_POST)) {
					if(date("Y-m-d", strtotime($_POST['effectivity_date'])) != "1970-01-01") {
						$changed						=	false;
						$insert['added_by']				=	$_SESSION['abas_login']['userid'];
						$insert['added_on']				=	date("Y-m-d H:i:s");
						$insert['employee_id']			=	$id;
						$insert['effectivity_date']		=	date("Y-m-d", strtotime($_POST['effectivity_date']));
						$insert['stat']					=	1;
						$position						=	isset($_POST['position']) ? $_POST['position'] : 0;
						$empstat						=	isset($_POST['empstat']) ? $_POST['empstat'] : 0;
						$salgrade						=	isset($_POST['salgrade']) ? $_POST['salgrade'] : 0;
						$assignedto						=	isset($_POST['assignedto']) ? $_POST['assignedto'] : 0;

						if($position != $e['position']) {

							$new_department				=	$this->Abas->getPosition($_POST['position']);

							$insert['value_changed']	=	"Position";
							$insert['from_val']			=	$e['position'];
							$insert['to_val']			=	$this->Mmm->sanitize($_POST['position']);
							$update['position']			=	$this->Mmm->sanitize($_POST['position']);
							$update['department']		=	$new_department->department_id;
							$ins						=	$this->Mmm->dbInsert("hr_employment_history", $insert, "Change Position for ".$e['full_name']);
							if($ins==true) {
								$changed				=	true;
								$this->Abas->sysMsg("msg", $insert['value_changed']." changed");
							}

							if($new_department->department_id != $e['department']){
								$insert['value_changed']	=	"Department";
								$insert['from_val']			=	$e['department'];
								$insert['to_val']			=	$new_department->department_id;
								$ins						=	$this->Mmm->dbInsert("hr_employment_history", $insert, "Change Department");
								if($ins==true) {
								$changed				=	true;
								$this->Abas->sysMsg("msg", $insert['value_changed']." changed");
								}
							}
						}


						if($assignedto != $e['vessel_id']) {
							$new_vessel					=	$this->Abas->getVessel($_POST['assignedto']);

							$insert['value_changed']	=	"Assigned To";
							$insert['from_val']			=	$e['vessel_id'];
							$insert['to_val']			=	$this->Mmm->sanitize($_POST['assignedto']);
							$update['vessel_id']		=	$this->Mmm->sanitize($_POST['assignedto']);
							$update['company_id']		=	$new_vessel->company;
							$ins						=	$this->Mmm->dbInsert("hr_employment_history", $insert, "Change Vessel/Office for ".$e['full_name']);
							if($ins==true) {
								$changed				=	true;
								$this->Abas->sysMsg("msg", $insert['value_changed']." changed");
							}

							if($new_vessel->company != $e['company_id']){
								$insert['value_changed']	=	"Company";
								$insert['from_val']			=	$e['company_id'];
								$insert['to_val']			=	$new_vessel->company;
								$ins						=	$this->Mmm->dbInsert("hr_employment_history", $insert, "Change Company");
								if($ins==true) {
								$changed				=	true;
								$this->Abas->sysMsg("msg", $insert['value_changed']." changed");
								}
							}
						}

						if($_POST['empstat'] != $e['employee_status'] && $access_status==true) {
							$insert['value_changed']	=	"Employment Status";
							$insert['from_val']			=	$e['employee_status'];
							$insert['to_val']			=	$this->Mmm->sanitize($_POST['empstat']);
							$emp_stat = $this->Mmm->sanitize($_POST['empstat']);
							$update['employee_status']	=	$this->Mmm->sanitize($_POST['empstat']);
							if($_POST['from_date']!='1970-01-01' && $_POST['to_date']!='1970-01-01' && ($emp_stat=='Preventive Suspension' || $emp_stat=='Suspended' || $emp_stat=='On-leave')){
								$insert['from_date']		= $this->Mmm->sanitize($_POST['from_date']);
								$insert['to_date']			= $this->Mmm->sanitize($_POST['to_date']);
							}
							$ins						=	$this->Mmm->dbInsert("hr_employment_history", $insert, "Change Employment Status of ".$e['full_name']. " from ".$e['employee_status']." to ".$_POST['empstat']);
							if($ins==true) {
								$changed				=	true;
								$this->Abas->sysMsg("msg", $insert['value_changed']." of ".$e['full_name']."was changed.");
							}
						}

						$salgrade_record			=	$this->db->query("SELECT * FROM salary_grades WHERE grade='".$e['salary_grade']."'");
						$salgrade_record			=	$salgrade_record->row();
						if($_POST['salgrade'] != $salgrade_record->id && $access_salary==true) {
							$insert['value_changed']	=	"Salary Grade";
							$insert['from_val']			=	$salgrade_record->id;
							$insert['to_val']			=	$this->Mmm->sanitize($_POST['salgrade']);
							$update['salary_grade']		=	$this->Mmm->sanitize($_POST['salgrade']);
							$ins						=	$this->Mmm->dbInsert("hr_employment_history", $insert, "Change Salary Grade for ".$e['full_name']);
							if($ins==true) {
								$changed				=	true;
								$this->Abas->sysMsg("msg", $insert['value_changed']." changed");
							}
						}
						$upd							=	false;
						$apply_immediately				=	false;
						if($insert['effectivity_date'] < date("Y-m-d") || $_POST['apply_immediately']==true) { $apply_immediately	=	true; }
						if($changed==true && $apply_immediately==true) {
							$upd						=	$this->Mmm->dbUpdate("hr_employees", $update, $id, "Update Employee History for ".$e['full_name']);
						}
						else {
							$insert['value_changed']	=	"No change";
							$insert['from_val']			=	"";
							$insert['to_val']			=	"";
							// $ins						=	$this->Mmm->dbInsert("hr_employment_history", $insert, "Change Salary Grade");
							$this->Abas->sysMsg("msg", "No employee history changes");
						}

						if($upd==true) {
							$this->Abas->sysMsg('sucmsg',"Employee status for ".$employee_link." updated.");
						}
						else {
							$this->Abas->sysMsg('warnmsg',"Employee status for ".$employee_link." not updated.");
						}
					}
					else {
						$this->Abas->sysMsg('errmsg',"Employee status for ".$employee_link." not updated.");
					}
				}
				else {
					if($action=="delete") {
						$this->Mmm->query("DELETE FROM hr_employment_history WHERE id=".$history_id, "Delete employee history");
					
						$latest_employment_status = $this->Hr_model->getLatestEmploymentStatus($e['id']);
						$update['employee_status']	=	$latest_employment_status->to_val;

						$upd = $this->Mmm->dbUpdate("hr_employees", $update, $e['id'], "Update Employee History for ".$e['full_name']);
						if($upd){
								$this->Abas->sysMsg("sucmsg", "Employee history deleted!");
						}

						$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$e['id']);

					}
					$this->Abas->sysMsg('errmsg',"No input detected! Not updated.");
				}
			}
			else {
				$this->Abas->sysMsg('errmsg',"Employee not found! Not updated.");
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$id);
		}
		public function pay_loan($id) {
			$this->Abas->checkPermissions("human_resources|update");
			if(is_numeric($id)) {
				$loan	=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$id);
				if($loan != false) {
					if($loan->row()) {
						$loan	=	$loan->row();
						$e		=	$this->Abas->getEmployee($loan->emp_id);
						$employee_link			=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$loan->emp_id."'>".$e['full_name']."</a>";
						if(is_numeric($_POST['loanPayAmt']) && $_POST['loanPayAmt']>0) {
							// payments
							$total_paid	=	0;
							$payments	=	$this->db->query("SELECT * FROM hr_loan_payments WHERE loan_id=".$id);
							if($payments != false) {
								if($payments->row()) {
									$payments	=	$payments->result();
									foreach($payments as $p) {
										$total_paid	=	$total_paid + $p->amount;
									}
								}
							}
							// payments
							if(($total_paid+$_POST['loanPayAmt']) <= $loan->amount_loan) {

								$checkloan				=	$this->db->query("SELECT * FROM hr_loans WHERE id=".$id);
								if($checkloan) {
									if(!$checkloan->row()) {
										$checkloan		=	$checkloan->row();
										$balance		=	$checkloan->amount;
										$checkpay		=	$this->db->query("SELECT * FROM hr_loan_payments WHERE loan_id=".$id);
										if($checkpay) {
											if(!$checkpay->row()) {
												$checkpay	=	$checkpay->result_array();
												foreach($checkpay as $payment) {
													$balance	=	$balance - $payment['amount'];
													if($balance <= 0) {
														$update['stat']	=	0;
														$this->Mmm->dbUpdate("hr_loans", $update, $loan_id, "fully paid loan via payroll for ".$e['full_name']);
														$this->Abas->sysMsg("warnmsg", "Loan already paid in full, payment not encoded!");
														$this->Abas->redirect(HTTP_PATH."hr");
													}
												}
											}
										}
									}
								}
								$payment['amount']			=	$this->Mmm->sanitize($_POST['loanPayAmt']);
								$payment['loan_id']			=	$id;
								$payment['date_payment']	=	date("Y-m-d", strtotime($_POST['loanPayDate']));
								$paid	=	$this->Mmm->dbInsert("hr_loan_payments", $payment, "Manual Loan Payment for ".$e['full_name']);
								if($paid == true) {
									$this->Abas->sysMsg("sucmsg", "Loan payment encoded for ".$employee_link."!");
								}
								else {
									$this->Abas->sysMsg("warnmsg", "An error has occurred. Please try again!");
								}
							}
							else {
								$this->Abas->sysMsg("warnmsg", "That payment is more than the remaining balance for ".$employee_link.".");
							}
						}
						else {
							$this->Abas->sysMsg("errmsg", "Invalid payment amount for loan of ".$employee_link.".");
						}
					}
					else {
						$this->Abas->sysMsg("errmsg", "Loan not found. Please try again!");
					}
				}
				else {
					$this->Abas->sysMsg("errmsg", "Loan not found. Please try again!");
				}
			}
			$this->Abas->redirect(HTTP_PATH."hr/employee_profile/view/".$loan->emp_id);
		}
		public function employee_autocomplete_list() {
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT id, last_name, first_name, middle_name, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as full_name FROM hr_employees WHERE last_name LIKE '%".$search."%' OR first_name LIKE '%".$search."%' OR middle_name LIKE '%".$search."%' ORDER BY last_name LIMIT 0, 10";
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
		
		public function employee_report() {$data=array();
			$this->Abas->checkPermissions("human_resources|reports");
			$data['companies']		=	$this->Abas->getCompanies(true);
			$data['divisions']		=	$this->Abas->getDivisions();
			$data['sections']		=	$this->Abas->getSections();
			$data['subsections']	=	$this->Abas->getSubsections();
			$data['departments']	=	$this->Abas->getDepartments();
			$data['vessels']		=	$this->Abas->getVessels();
			$data['positions']		=	$this->Abas->getPositions();
			$mainview				=	'hr/hr_report_form.php';
			if($_POST) {
				$company = $this->Mmm->sanitize($_POST['company']);
				$assignment = $this->Mmm->sanitize($_POST['vessel']);
				$civil_status = $this->Mmm->sanitize($_POST['civil_status']);
				$gender = $this->Mmm->sanitize($_POST['gender']);
				$group = $this->Mmm->sanitize($_POST['group']);
				$division = $this->Mmm->sanitize($_POST['division']);
				$department = $this->Mmm->sanitize($_POST['department']);
				if(isset($_POST['section'])){
					$section = $this->Mmm->sanitize($_POST['section']);
				}else{
					$section ="";
				}
				if(isset($_POST['subsection'])){
					$subsection = $this->Mmm->sanitize($_POST['subsection']);
				}else{
					$subsection = "";
				}
				$position = $this->Mmm->sanitize($_POST['position']);
				$empstat = $this->Mmm->sanitize($_POST['empstat']);
				$emplstat = $this->Mmm->sanitize($_POST['emplstat']);
				$from_date = $this->Mmm->sanitize($_POST['from_date']);
				$to_date = $this->Mmm->sanitize($_POST['to_date']);
				$bankacct = $this->Mmm->sanitize($_POST['atmstat']);
				$abasacct = $this->Mmm->sanitize($_POST['abasaccount']);
				$with_salary = '';
				if(isset($_POST['salary'])){
					$with_salary = $this->Mmm->sanitize($_POST['salary']);
				}
				if($with_salary != ''){
					$sql = "
					SELECT *, employee_id, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as fullname, e.employee_status, s.rate, p.name, d.name
					FROM `hr_employees` AS e
					INNER JOIN salary_grades AS s ON e.salary_grade = s.id
					INNER JOIN positions AS p ON e.position = p.id
					INNER JOIN departments AS d ON e.department = d.id
					WHERE employee_status != 'Resigned' and employee_status != 'Terminated' and employee_status != 'Retired' and employee_status != 'Separated'
					AND e.stat=1";
				}
				else{
					$sql = "SELECT *, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as fullname
					FROM hr_employees as e
					WHERE stat=1";
				}
				if($company !=''){
					$sql .= " AND e.company_id =".$company;
				}
				if($civil_status !=''){
					$sql .= " AND e.civil_status ='".$civil_status."'";;
				}
				if($gender !=''){
					$sql .= " AND e.gender ='".$gender."'";
				}
				if($from_date != '' || $to_date != ''){
					$sql .= " AND e.date_hired between '".$from_date."' AND '".$to_date."'";
				}
				if($group !=''){
					$sql .= " AND e.department_group ='".$group."'";
				}
				if($division !=''){
					$sql .= " AND e.division_id =".$division;
				}
				if($section !=''){
					$sql .= " AND e.section_id =".$section;
				}
				if($subsection !=''){
					$sql .= " AND e.sub_section_id =".$subsection;
				}
				if($department !=''){
					$sql .= " AND e.department =".$department;
				}
				if($position !=''){
					$sql .= " AND e.position =".$position;
				}
				if($empstat !=''){
					$sql .= " AND e.employee_status ='".$empstat."'";
				}
				if($emplstat !=''){
					if($emplstat=='Active'){
						$sql .= " AND (e.employee_status!='Retired' AND e.employee_status!='Resigned' AND e.employee_status!='Terminated' AND e.employee_status!='Separated')";
					}else{
						$sql .= " AND (e.employee_status='Retired' OR e.employee_status='Resigned' OR e.employee_status='Terminated' OR e.employee_status='Separated')";
					}
				}
				if($assignment != ''){
					$sql .= " AND e.vessel_id =".$assignment;
				}
				if($bankacct=="Yes") {
					$sql .= " AND e.bank_account_num NOT LIKE ''";
				}
				if($bankacct=="No") {
					$sql .= " AND e.bank_account_num=''";
				}
				if($abasacct=="Yes") {
					$sql .= " AND e.user_id IS NOT NULL";
				}
				if($abasacct=="No") {
					$sql .= " AND e.user_id IS NULL";
				}
				$sql .= " ORDER BY e.last_name ASC, e.company_id, e.department ";
				$res = $this->db->query($sql);
				$employees = $res->result_array();

				$data['employees']	=	$employees;
				$data['viewfile']	=	"hr/employee_report.php";
				$mainview	=	"gentlella_container.php";
			}

			$this->load->view($mainview,$data);
		}
		public function loans_report() {$data=array();
			$this->Abas->checkPermissions("human_resources|reports");
			$this->load->view('hr/loans_report.php',$data);
		}
		public function loans_report_result() {$data=array();
			$this->Abas->checkPermissions("human_resources|reports");
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
			$this->Abas->checkPermissions("human_resources|reports");
			$data['elf'] = $this->Hr_model->getElfContribution();
			$data['viewfile']	=	"hr/elf_report_result.php";
			$this->load->view('container.php',$data);
		}
		public function clean_db() { // outputs queries used to clean up hr_employees table
			if(ENVIRONMENT!="development") { die("Function will not run on prod!"); }
			$table	=	"hr_employees";
			$get	=	$this->db->query("SELECT * FROM ".$table);
			if($get) {
				if($get=$get->result_array()) {
					foreach($get as $g) {
						// do query per row
					}
				}
			}
			$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
			$tablefields			=	$tablefields->result_array();
			foreach($tablefields as $t) {
				// $this->Mmm->debug($t);
				echo "UPDATE `".$table."` SET `".$t['COLUMN_NAME']."` = REPLACE( `".$t['COLUMN_NAME']."` , '\\t' , '&nbsp;' );<br/>";
				echo "UPDATE `".$table."` SET `".$t['COLUMN_NAME']."` = REPLACE( `".$t['COLUMN_NAME']."` , '\\n' , '&nbsp;' );<br/>";
				echo "UPDATE `".$table."` SET `".$t['COLUMN_NAME']."` = REPLACE( `".$t['COLUMN_NAME']."` , '&nbsp;&nbsp;' , '&nbsp;' );<br/>";
				echo "UPDATE `".$table."` SET `".$t['COLUMN_NAME']."` = TRIM(`".$t['COLUMN_NAME']."`);<br/>";
			}
		}
		public function vcard() {
			$this->Abas->checkPermissions("human_resources|view");
			$data="";
			$employee=$this->db->query("SELECT id FROM hr_employees WHERE mobile<>'' AND employee_status<>'Resigned' AND employee_status<>'Retired' AND employee_status<>'Terminated' AND employee_status<>'Separated'");
			$employee=$employee->result_array();
			foreach($employee as $e) {
				$e=$this->Abas->getEmployee($e['id']);
				$data.="BEGIN:VCARD<br/>
				VERSION:2.1<br/>
				N:".ucwords(strtolower($e['last_name'])).";Avega ".ucwords(strtolower($e['first_name'])).";;;<br/>
				FN:".ucwords(strtolower($e['full_name']))."<br/>
				TEL;CELL:".$e['mobile']."<br/>
				END:VCARD<br/>";
			}
			echo $data;
		}
		public function public_users($action="",$id=""){
			$this->Abas->checkPermissions("human_resources|view");
			$data['viewfile']="hr/public_users.php";
			$mainview = "gentlella_container.php";

			$sql= $this->db->query("SELECT * FROM public_users");
			$validate	=	array("total"=>count($sql->result_array()),"rows"=>$sql->result_array());
			if ($action=="json"){
				foreach($validate['rows'] as $ctr=>$val){
					$validate['rows'][$ctr]['best_guess']	=	"";
					$best_guess_sql							=	"SELECT id FROM hr_employees WHERE first_name LIKE '".$val['first_name']."' AND last_name LIKE '".$val['last_name']."' AND birth_date='".$val['birth_date']."'";
					$best_guess								=	$this->db->query($best_guess_sql);
					if($best_guess) {
						if($best_guess=(array)$best_guess->row()) {
							$validate['rows'][$ctr]['best_guess']	=	$best_guess['id'];
						}
					}
					if (!empty($val['validated_by'])){
						$validated_by	=	$this->Abas->getUser($val['validated_by']);
						$validate['rows'][$ctr]['validated_by']	=	$validated_by['full_name'];
					}
					if (!empty($val['birth_date'])){
						$validate['rows'][$ctr]['birth_date']	=	date("j F Y", strtotime($val['birth_date']));
					}
					if (!empty($val['validated_on'])){
						$validate['rows'][$ctr]['validated_on']	=	date("j F Y H:i", strtotime($val['validated_on']));
					}
					if (!empty($val['created_on'])){
						$validate['rows'][$ctr]['created_on']	=	date("j F Y H:i", strtotime($val['created_on']));
					}
					if (!empty($val['confirmed_on'])){
						$validate['rows'][$ctr]['confirmed_on']	=	date("j F Y H:i", strtotime($val['confirmed_on']));
					}

				}
				header('Content-Type: application/json');
				echo json_encode($validate);
				exit();
			}
			else if ($action=="edit"){$data=array();
			if(is_numeric($id)) {
				$check	=	$this->db->query("SELECT * FROM public_users WHERE id=".$id);
					if($check->row()) {
						$check	=	(array)$check->row();
						$data['existing']	=	$check;
					}
					else { $this->Abas->sysMsg("errmsg","Record not found"); }
				if(!empty($_POST)) {
					$pword			=	$_POST['password'];
					$pword2			=	$_POST['password2'];
					if($pword!="")	{
						if($pword==$pword2)	{
							$update['password'] =	md5($pword);
							$msg="
							<p>Dear ".$check['firstname'].",</p>
							<p>Your Password in Avega Crew has been changed.</p>";
							$this->Mmm->sendEmail($check, "Avega Crew Password changed!", $msg);
							$this->Abas->sysMsg('sucmsg',"Password updated!");
							$passupdate	=	$this->Mmm->dbUpdate("public_users",$update,$id, "Update user password");
							$this->Abas->redirect(HTTP_PATH."hr/public_users");
						}
						else {
							$this->Abas->sysMsg('errmsg',"Password do not match! Password unchanged.");
							$this->Abas->redirect(HTTP_PATH."hr/public_users");
						}
					}
				}
			}

			else { $this->Abas->sysMsg("errmsg","ID not set"); }
			$mainview	=	"hr/public_users_form.php";
			}
			elseif ($action=="validate"){$data=array();
				$user		=	$this->db->query("SELECT * FROM public_users WHERE id=".$id);
				$user		=	(array)$user->row();
				$best_guess=$best_guess_id=	"";
				$best_guess_sql		=	"SELECT id FROM hr_employees WHERE first_name LIKE '".$user['first_name']."' AND last_name LIKE '".$user['last_name']."' AND birth_date='".$user['birth_date']."'";
				$best_guess			=	$this->db->query($best_guess_sql);
				if($best_guess) {
					if($best_guess=(array)$best_guess->row()) {
						$best_guess_id	=	$best_guess['id'];
					}
				}
				$data['public_user_id']	=	$id;
				$data['best_guess_id']	=	$best_guess_id;
				$mainview= "hr/validate_form.php";
			}
			elseif($action=="submit"){
				if(!isset($_POST['employee_id'])){
					$this->Abas->sysMsg("errmsg","No input detected! Please try again.");
					$this->Abas->redirect(HTTP_PATH."hr/public_users");
				}
				if(!is_numeric($_POST['employee_id'])){
					$this->Abas->sysMsg("errmsg","Invalid input detected! Please try again.");
					$this->Abas->redirect(HTTP_PATH."hr/public_users");
				}
				$employee							=	$this->Abas->getEmployee($_POST['employee_id']);
				if(!$employee) {
					$this->Abas->sysMsg("errmsg","Employee not found.");
					$this->Abas->redirect(HTTP_PATH."hr/public_users");
				}
				$user								=	$this->db->query("SELECT * FROM public_users WHERE id=".$id);
				if(!$user) {
					$this->Abas->sysMsg("errmsg","User not found! Please try again.");
					$this->Abas->redirect(HTTP_PATH."hr/public_users");
				}
				if(!$user=(array)$user->row()) {
					$this->Abas->sysMsg("errmsg","User not found! Please try again.");
					$this->Abas->redirect(HTTP_PATH."hr/public_users");
				}
				$update['validated_on']				=	date("Y-m-d H:i:s");
				$update['validated_by']				=	$_SESSION['abas_login']['userid'];
				$update['employee_id']				=	$this->Mmm->sanitize($_POST['employee_id']);
				$check								=	$this->Mmm->dbUpdate("public_users", $update, $id, "Attach public user account of ".$user['email']." to employee ".$employee['full_name']);
				if($check) {
					$lastInsert						=	$this->db->query("SELECT MAX(id) AS id FROM public_users");
					$lastInsert						=	(array)$lastInsert->row();
					$permission['public_user_id']	=	$lastInsert['id'];
					$permission['permission']		=	"payslip|view";
					$this->Abas->sysMsg("sucmsg","You have successfully validated ".$user['email']);
					$msg = "
						<p>Thank you for your patience ".$user['first_name'].".</p>
						<p>Your account has been successfully validated. Please use this link to login.</p>
						<h2><a href='http://crew.avegabros.com'>Login Link</a></h2>
					";
					$this->Mmm->sendEmail($user['email'],"Avega Crew Account Validated",$msg);
				}
				else {
					$this->Abas->sysMsg("errmsg",$user['email']." not validated! Please try again.");
				}
				$this->Abas->redirect(HTTP_PATH."hr/public_users");
			}
			elseif($action=="permissions") {
				if($id!="") {
					$check	=	$this->db->query("SELECT * FROM public_user_permissions WHERE public_user_id=".$id);
					$user	=	$this->db->query("SELECT * FROM public_users WHERE id=".$id);
					if($user) {
						$check	=	$check->result();
						$user	=	$user->row();
						$data['user']		=	$user;
						$data['existing']	=	$check;
					}
					else {
						$this->Abas->sysMsg("errmsg","Record not found");
					}
				}
				$mainview	=	"hr/public_users_permissions_form.php";
			}
			if($action=="update_permissions") {
				if(is_numeric($id)) {
					if(count($_POST)>0) {
						$userid	=	$this->Mmm->sanitize($id);
						$del	=	$this->db->query("DELETE FROM public_user_permissions WHERE public_user_id=".$userid);
						$insq	=	"INSERT INTO public_user_permissions (public_user_id, permission, updated_on, updated_by) VALUES ";
						foreach($_POST as $module => $functions) {
							if($module!="ci_csrf_token") {
								if(is_array($functions)) {
									foreach($functions as $fi=>$f) {
										$page		=	$this->Mmm->sanitize($module."|".$_POST[$module][$fi]);
										$insq	.=	"(";
										$insq		.=	"'".$userid."', ";
										$insq		.=	"'".$page."', " ;
										$insq		.=	"'".date("Y-m-d H:i:s")."', ";
										$insq		.=	"'".$_SESSION['abas_login']['userid']."'";
										$insq	.=	"), ";
									}
								}
								else {
									$page		=	$this->Mmm->sanitize($module."|".$functions);
									$insq	.=	"(";
									$insq		.=	"'".$userid."', ";
									$insq		.=	"'".$page."', " ;
									$insq		.=	"'".date("Y-m-d H:i:s")."', ";
									$insq		.=	"'".$_SESSION['abas_login']['userid']."'";
									$insq	.=	"), ";
								}
							}
						}
						$insq	=	rtrim($insq, ", ");
						$this->Mmm->query($insq, "Update public user permissions");
						// echo $insq;
						$this->Abas->sysMsg("sucmsg","Permissions updated!");
						$this->Abas->redirect(HTTP_PATH."hr/public_users");
					}
					else {
						$this->Abas->sysMsg("warnmsg","User permissions unchanged.");
						$this->Abas->redirect(HTTP_PATH."hr/public_users");
					}
				}
				else {
					$this->Abas->sysMsg("errmsg","Invalid user ID!");
					$this->Abas->redirect(HTTP_PATH."hr/public_users");
				}
			}
			$this->load->view($mainview,$data);
		}
		public function employees_for_awol(){
			$this->Abas->checkPermissions("human_resources|view");
			$data['viewfile']	=	"hr/employee_for_awol.php";
			$data['employees']  = $this->Hr_model->getEmployeeForAWOL();
			$this->load->view('gentlella_container.php',$data);
		}
		public function set_sections($department_id){
			$data['sections'] = $this->Hr_model->getSectionsByDepartment($department_id);
			echo json_encode( $data['sections']);
		}
		public function set_subsections($section_id){
			$data['subsections'] = $this->Hr_model->getSubsectionsBySection($section_id);
			echo json_encode( $data['subsections']);
		}
//-----------------------------------------------------------------------------------------//
		public function crew_movement($action,$id=''){
			$item = $this->Abas->getItemById('hr_crew_movements',array('id'=>$id));
			if($action == 'insert' or $action == 'update'){
				$array = array(
					'vessel_from' => $_POST['assigned_from'],
					'vessel_to' => $_POST['assigned_to'],
					'added_by' => $_SESSION['abas_login']['username'],
					'added_on' => date('Y-m-d'),
					'transfer_date' => $_POST['transfer_date'],
					'embarkation_start' => $_POST['embarkation_start'],
					'embarkation_end' => $_POST['embarkation_end']
				);
			}
			$data['vessels'] = $this->Abas->getItems('vessels',array('id !='=>0));

			switch ($action) {
				case 'add':
					$data['submit'] = HTTP_PATH.'hr/crew_movement/insert/'.$id;
					$data['action'] = $action;
					$have_entry = $this->Hr_model->crewHaveEntry($id);
					if($have_entry){
						$vessel_from = $this->Abas->lastItemByCol('hr_crew_movements',
							array('employee_id'=>$id)
						);
						$data['vessel_from'] = $vessel_from->vessel_to;
					}else{
						$vessel_from = $this->Abas->getItemById('hr_employees',array('id'=>$id));
						$data['vessel_from'] = $vessel_from->vessel_id;
					}

					$this->load->view('hr/crew_movement',$data);
					break;

				case 'edit':
					$data['submit'] = HTTP_PATH.'hr/crew_movement/update/'.$id;
					$data['action'] = $action;
					$data['item'] = $item;
					$this->load->view('hr/crew_movement',$data);
					break;

				case 'insert':
					$this->Abas->updateItem('hr_crew_movements',array('stat'=>0),array('employee_id'=>$id));
					$array = array_merge($array,array('employee_id'=>$id,'stat'=>1));
					$this->Abas->insertItem('hr_crew_movements',$array);
					$this->Abas->sysMsg("sucmsg","Crew Movement Inserted!");
					redirect(HTTP_PATH.'hr/employee_profile/view/'.$id);
					break;

				case 'update':
					$array = array_merge($array,array('employee_id'=>$item->employee_id));
					$this->Abas->updateItem('hr_crew_movements',$array,array('id'=>$id));
					$this->Abas->sysMsg("sucmsg","Crew Movement Updated!");
					redirect(HTTP_PATH.'hr/employee_profile/view/'.$item->employee_id);
					break;

				case 'delete':
					$this->Abas->delItem('hr_crew_movements',array('id'=>$id));
					$this->Abas->sysMsg("sucmsg","Crew Movement Deleted!");
					redirect(HTTP_PATH.'hr/employee_profile/view/'.$item->employee_id);
					break;

				case 'revert':
					$revert = array('stat' => 0);
					$where = array('employee_id' => $id);
					$log = 'Assign employee to its original vessel/assignment';
					$this->Abas->updateItem('hr_crew_movements',$revert,$where,$log);
					$this->Abas->sysMsg("sucmsg","Successfully re-assigned crew to its original assignment!");
					redirect(HTTP_PATH.'hr/employee_profile/view/'.$id);
					break;
			}
		}

		public function crew_movement_summary($action='',$id=''){
			if($action == '')
			{
				$vessels = $this->Abas->getItems('vessels',array('id !='=>0, 'status'=>'Active'));
				foreach ($vessels as $key => $row) {
					$company = $this->Abas->getItemById('companies',array('id'=>$row->company));
					$crew_count = $this->Hr_model->getCrewCount($row->id);
					$array[$key] = array(
						'id' => $row->id,
						'ctr' => $key,
						'vessel' => $row->name,
						'company' => $company->name,
						'status' => $row->status,
						'crew_count' => $crew_count
					);
				}
				$data['items'] = $array;

				$data['viewfile'] =	"hr/crew_movement_summary.php";
				$this->load->view('gentlella_container.php',$data);	
			}
			elseif($action == 'view')
			{
				$employees = $this->Hr_model->getVesselCrew($id);
				$data['vessel'] = $this->Abas->getItemById('vessels',array('id'=>$id));
				foreach ($employees as $ctr => $row) {
					$department = $this->Abas->getItemById('departments',array('id'=>$row->department));
					if($department != null){
						$department_name = $department->name;
					}else{
						$department_name = null;
					}
					
					$position = $this->Abas->getItemById('positions',array('id'=>$row->position));
					$array[$ctr] = array(
						'id' => $row->id,
						'emp_id' => $row->employee_id,
						'employee_status' => $row->employee_status,
						'name' => $row->last_name.', '.$row->first_name.' '.$row->middle_name,
						'department' => $department_name,
						'position' => $position->name,
					);
				}
				if($employees == null){
					$data['employees'] = array();
				}else{
					$data['employees'] = $array;
				}

				$vessel_positions = $this->Abas->getItems('hr_vessel_positions',array('vessel_id'=>$id));
				foreach ($vessel_positions as $key => $val) {
					$position = $this->Abas->getItemById('positions',array('id'=>$val->position_id));
					$user = $this->Abas->getItemById('users',array('id'=>$val->added_by));
					$position_count = $this->Hr_model->getPositionCount($id,$val->position_id);
					if($position_count == $val->quantity){
						$status = 'OK';
					}elseif($position_count > $val->quantity){
						$status = 'Over';
					}else{
						$status = 'Insuficient';
					}
					$array2[$key] = array(
						'id' => $val->id,
						'position' => $position->name,
						'quantity' => $val->quantity,
						'added_by' => $user->username,
						'date_added' => $val->date_added,
						'status' => $status,
						'position_count' => $position_count
					);
				}
				if($vessel_positions == null){
					$data['vessel_positions'] = array();
				}else{
					$data['vessel_positions'] = $array2;	
				}

				$data['viewfile'] =	"hr/crew_movement_view.php";
				$this->load->view('gentlella_container.php',$data);	
			}
			elseif($action == 'edit')
			{
				$data['item'] = $this->Abas->getItemById('hr_vessel_positions',array('id'=>$id));
				$data['positions'] = $this->Abas->getItems('positions');
				$data['submit'] = HTTP_PATH.'hr/crew_movement_summary/update/'.$id;
				$data['action'] = $action;
				$this->load->view('hr/crew_movements/position',$data);
			}
			elseif($action == 'update')
			{	
				$update_array = array(
					'position_id' => $_POST['position'],
					'quantity' => $_POST['quantity'],
					'added_by' => $_SESSION['abas_login']['userid'],
					'date_added' => date('Y-m-d')
				);
				$this->Abas->updateItem('hr_vessel_positions',$update_array,array('id'=>$id));
				$item = $this->Abas->getItemById('hr_vessel_positions',array('id'=>$id));
				$this->Abas->sysMsg("sucmsg","Vessel Position Updated!");
				redirect(HTTP_PATH.'hr/crew_movement_summary/view/'.$item->vessel_id);
			}
			elseif($action == 'add')
			{
				$data['item'] = array();
				$data['positions'] = $this->Abas->getItems('positions');
				$data['submit'] = HTTP_PATH.'hr/crew_movement_summary/insert/'.$id;
				$data['action'] = $action;
				$this->load->view('hr/crew_movements/position',$data);
			}
			elseif ($action == 'insert')
			{
				$insert_array = array(
					'position_id' => $_POST['position'],
					'quantity' => $_POST['quantity'],
					'added_by' => $_SESSION['abas_login']['userid'],
					'date_added' => date('Y-m-d'),
					'vessel_id' => $id,
				);
				$this->Abas->insertItem('hr_vessel_positions',$insert_array);
				$this->Abas->sysMsg("sucmsg","Vessel Position Added!");
				redirect(HTTP_PATH.'hr/crew_movement_summary/view/'.$id);
			}
			elseif($action == 'delete')
			{
				$item = $this->Abas->getItemById('hr_vessel_positions',array('id'=>$id));
				$this->Abas->delItem('hr_vessel_positions',array('id'=>$id));
				$this->Abas->sysMsg("sucmsg","Vessel Position Deleted!");
				redirect(HTTP_PATH.'hr/crew_movement_summary/view/'.$item->vessel_id);
			}
		}

		public function employee_for_transfer($action){
			if($action == 'view')
			{
				$emp_for_transfer = $this->Hr_model->getEmployeeForTransfer();
				foreach ($emp_for_transfer as $key => $val) {
					$hr_employees = $this->Abas->getItemById('hr_employees',array('id'=>$val->employee_id));
					$date1 = strtotime($val->transfer_date);  
					$date2 = strtotime(date('Y-m-d'));
					$diff = $date2 - $date1;
					$days = round($diff / (60 * 60 * 24));
					if($days > 180){
						$status = 'SERVICE EXCEEDED';
					}elseif($days > 150){
						$status = 'FOR TRANSFER';						
					}else{
						$status = 'GOOD';
					}

					$array[$key] = array(
						'id' => $val->id,
						'emp_id' => $val->employee_id,
						'id_no' => $hr_employees->employee_id,
						'name' => $hr_employees->last_name.', '.$hr_employees->first_name,
						'vessel_from' => $this->Abas->getVesselById($val->vessel_from),
						'vessel_to' => $this->Abas->getVesselById($val->vessel_to),
						'added_on' => $val->added_on,
						'embarkation_start' => $val->embarkation_start,
						'embarkation_end' => $val->embarkation_end,
						'transfer_date' => $val->transfer_date,
						'status' => $status,
						'days' => $days
					);
				}
				$data['emp_for_transfer'] = $array;

				$data['viewfile'] =	"hr/crew_movements/employee_for_transfer.php";
				$this->load->view('gentlella_container.php',$data);	
			}
			
		}

		public function sunday_count($from,$to)
		{
			$start = new DateTime($from);
			$end = new DateTime($to);
			
			$days = $start->diff($end, true)->days;
			$sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

			return $sundays;
		}

		public function leave($action='',$id=''){
			if($id != '')
			{
				$data['item'] = $this->Abas->getItemById('employee_leave',array('id'=>$id));
				$emp_id = $data['item']->employee_id;
			}
			$data['leave_types'] = $this->Abas->getItems('leave_types');
			if($action == 'view')
			{
				$data['emp_name'] = $this->Hr_model->getEmpFullName($emp_id);
				$data['approved_by'] = $this->Hr_model->getEmpFullName($data['item']->approver_id);
				$this->load->view('hr/leave/view',$data);	
			}
			elseif($action == 'process')
			{
				$diff = strtotime($_POST['date_to']) - strtotime($_POST['date_from']);
				$days = ($diff / (60 * 60 * 24)) + 1;
				$sundays = $this->sunday_count($_POST['date_from'],$_POST['date_to']);
				$days_no_sunday = $days - $sundays;

				$with_pay = $_POST['type'] == "Absence" ? 0 : 1;
				$update = array(
					'status' => 'PROCESSED',
					'days' => $days_no_sunday,
					'date_processed' => date('Y-m-d'),
					'processed_by' => $_SESSION['abas_login']['username'],
					'date_from' => $_POST['date_from'],
					'date_to' => $_POST['date_to'],
					'type' => $_POST['type'],
					'is_with_pay' => $with_pay
				);

				$hr_leave = array(
					'leave_type' => $_POST['type'],
					'date_created' => date('Y-m-d H:i:s'),
					'date_from' => $_POST['date_from'],
					'date_to' => $_POST['date_to'],
					'reason' => $_POST['reason'],
					'no_of_days' => $days_no_sunday,
					'stat' => 1,
					'emp_id' => $emp_id,
					'calculate' => 0,
				);
				
				$this->Abas->insertItem('hr_leaves',$hr_leave,"Insert Leave item");
				if($with_pay)
				{
					$leave_bal = $this->Corporate_Services_model->getLeaveBal($emp_id);
					$bal_array = array('leave_credits'=>($leave_bal - $days_no_sunday));
					$this->Abas->updateItem('hr_employees',$bal_array,array('id'=>$emp_id),"Update leave credit");
				}
				$this->Abas->updateItem('employee_leave',$update,array('id'=>$id),"Update temp leave table");
				$this->Abas->sysMsg("sucmsg","Leave Application has been Apporved!");
				redirect(HTTP_PATH.'hr/leave?filter=for_processing');
			}
			elseif($action == 'hr_process')
			{
				$emp_id = strtok($_POST['emp_auto_complete'],')');
				$explode = explode("(", $emp_id, 2);
				$with_pay = $_POST['type'] == 'Absence' ? 0 : 1;
				$diff = strtotime($_POST['date_to']) - strtotime($_POST['date_from']);
				$days = ($diff / (60 * 60 * 24)) + 1;
				$sundays = $this->sunday_count($_POST['date_from'],$_POST['date_to']);
				$days_no_sunday = $days - $sundays;

				$emp_leave = array(
					'employee_id' => $explode[1],
					'date_filed' => date('Y-m-d'),
					'type' => $_POST['type'],
					'is_with_pay' => $with_pay,
					'date_from' => $_POST['date_from'],
					'date_to' => $_POST['date_to'],
					'days' => $days_no_sunday,
					'status' => 'PROCESSED',
					'reason' => $_POST['reason'],
					'date_approved' => date('Y-m-d'),
					'date_processed' => date('Y-m-d'),
					'processed_by' => $_SESSION['abas_login']['userid'],	
					'approver_id' => $this->Abas->getEmpId($_SESSION['abas_login']['userid'])
				);

				$hr_leave = array(
					'leave_type' => $_POST['type'],
					'date_created' => date('Y-m-d H:i:s'),
					'date_from' => $_POST['date_from'],
					'date_to' => $_POST['date_to'],
					'reason' => $_POST['reason'],
					'no_of_days' => $days_no_sunday,
					'stat' => 1,
					'emp_id' => $explode[1],
					'calculate' => 0
				);

				if($with_pay){
					$bal = $this->Corporate_Services_model->getLeaveBal($explode[1]);
					$total = $bal - $days_no_sunday;
					$total_array = array('leave_credits' => $total);
					$this->Abas->updateItem('hr_employees',$total_array,array('id'=>$explode[1]),"updated Leave Credits");
					$this->Abas->sysMsg("warnmsg","Leave Credit/s has been deducted. Remaining $total");
				}
				
				$this->Abas->insertItem('employee_leave',$emp_leave,"Insert item to employee_leave");
				$this->Abas->insertItem('hr_leaves',$hr_leave,"Insert item to hr_leave");
				$this->Abas->sysMsg("sucmsg","Leave Application has been Processed!");
				redirect(HTTP_PATH.'hr/leave?filter=for_processing');
			}
			elseif($action == 'reject')
			{
				$update = array(
					'status' => 'REJECTED',
					'processed_by' => $_SESSION['abas_login']['username']
				);
				$this->Abas->updateItem('employee_leave',$update,array('id'=>$id),"updated status to rejected");
				$this->Abas->sysMsg("sucmsg","Leave Application has been Rejected!");
				redirect(HTTP_PATH.'hr/leave?approved=rejected');
			}
			elseif($action == 'add')
			{
				$data['leave_types'] = $this->Abas->getItems('leave_types');
				$this->load->view('hr/leave/add',$data);
			}
			else
			{
				if(isset($_GET['filter'])){
					$filter = $_GET['filter'];
					if($filter == 'for_processing'){
						$leave = $this->Hr_model->getLeaveForApproval();
					}elseif($filter == 'processed'){
						$leave = $this->Hr_model->getLeaveProcessed();
					}elseif($filter == 'rejected'){
						$leave = $this->Hr_model->getLeaveRejected();
					}elseif($filter == 'all'){
						$leave = $this->Hr_model->getAllLeaves();
					}else{
						$leave = $this->Hr_model->getAllLeaves();	
					}
				}else{
					$leave = $this->Hr_model->getLeaveForApproval();
				}
				
				foreach ($leave as $ctr => $val) {
					$array[$ctr] = array(
						'id' => $val->id,
						'date_filed' => $this->Abas->dateFormat($val->date_filed),
						'emp_name' => $this->Abas->getEmpName($val->employee_id),
						'type' => $val->type,
						'date_from' => $this->Abas->dateFormat($val->date_from),
						'date_to' => $this->Abas->dateFormat($val->date_to),
						'days' => $val->days,
						'pay' => ($val->is_with_pay==1 ? "<span class='glyphicon glyphicon-ok'/>" : "<span class='glyphicon glyphicon-remove'/>"),
						'credit' => $this->Corporate_Services_model->getLeaveBal($val->employee_id),
						'status' => $val->status
					);
				}
				
				if(isset($array)){
					$data['leave'] = $array;	
				}else{
					$data['leave'] = array();
				}

				$data['viewfile'] =	"hr/leave/item_list.php";
				$this->load->view('gentlella_container.php',$data);	
			}
		}

		public function overtime_approval($action='',$id=''){
			$data['action'] = $action;
			$sid = $_SESSION['abas_login']['userid'];
			$username = $_SESSION['abas_login']['username'];
			$user_emp_id = $this->Abas->getEmpId($sid);
			if($id != '')
			{
				$item = $this->Abas->getItemById('employee_overtime',array('id'=>$id));
				$emp_id = $item->employee_id;
				$data['item'] = $item;
			}
			if($action == 'view')
			{	
				$data['submit'] = HTTP_PATH.'hr/overtime_approval/process/'.$id;
				$data['item'] = $this->Abas->getItemById('employee_overtime',array('id'=>$id));
				$data['emp_name'] = $this->Hr_model->getEmpFullName($emp_id);
				$data['approved_by'] = $this->Hr_model->getEmpFullName($emp_id);
				$this->load->view('hr/overtime/view',$data);	
			}
			elseif($action == 'process')
			{
				$temp = array(
					'status' => 'PROCESSED',
					'processed_by' => $user_emp_id
				);

				$hr_overtime = array(
					'employee_id' => $item->employee_id,
					'ot_date' => $item->render_date,
					'ot_time' => $item->total_hours,
					'rate' => $_POST['rate'],
					'type' => $this->Hr_model->getOvertimeType($_POST['rate']),
					'reason' => $item->reason,
					'approved' => $sid,
					'computed' => 0,
					'stat' => 1
				);

				$this->Abas->updateItem('employee_overtime',$temp,array('id'=>$id),"$username Update");
				$this->Abas->insertItem('hr_overtime',$hr_overtime,"$username Update");
				$this->Abas->sysMsg("sucmsg","Leave Application has been Apporved!");
				redirect(HTTP_PATH.'hr/overtime_approval');
			}
			elseif($action == 'hr_process')
			{
				$emp_id = strtok($_POST['emp_auto_complete'],')');
				$explode = explode("(", $emp_id, 2);
				$start = new DateTime($_POST['time_start']);
				$end = new DateTime($_POST['time_end']);
				$diff = $start->diff($end);

				$employee_overtime = array(
					'employee_id' => $explode[1],
					'date_filed' => date('Y-m-d H:i:s'),
					'render_date' => $_POST['render_date'],
					'time_from' => $_POST['time_start'],
					'time_to' => $_POST['time_end'],
					'total_hours' => $diff->format("%H:%I"),
					'reason' => $_POST['reason'],
					'approver_id' => $user_emp_id,
					'status' => 'PROCESSED'
				);

				$hr_overtime = array(
					'employee_id' => $explode[1],
					'ot_date' => date('Y-m-d H:i:s'),
					'ot_time' => $diff->format("%H:%i"),
					'rate' => $_POST['type'],
					'type' => $this->Hr_model->getOvertimeType($_POST['type']),
					'reason' => $_POST['reason'],
					'approved' => $sid,
					'computed' => 0,
					'stat' => 1
				);
				
				$this->Abas->insertItem('employee_overtime',$employee_overtime,"$username Insert");
				$this->Abas->insertItem('hr_overtime',$hr_overtime,"$username Insert");
				$this->Abas->sysMsg("sucmsg","Leave Application has been Processed!");
				redirect(HTTP_PATH.'hr/overtime_approval?filter=for_processing');
			}
			elseif($action == 'reject')
			{
				$update = array(
					'status' => 'REJECTED',
					'processed_by' => $_SESSION['abas_login']['username']
				);
				$this->Abas->updateItem('employee_overtime',$update,array('id'=>$id),"updated status to rejected");
				$this->Abas->sysMsg("sucmsg","Leave Application has been Rejected!");
				redirect(HTTP_PATH.'hr/overtime_approval');
			}
			elseif($action == 'add')
			{
				$data['overtime_types'] = $this->Abas->getItems('hr_overtime_rate');
				$this->load->view('hr/overtime/add',$data);
			}
			else
			{
				if(isset($_GET['filter'])){
					$filter = $_GET['filter'];
					if($filter == 'for_processing'){
						$overtime = $this->Hr_model->getOvertime('FOR PROCESSING');
					}elseif($filter == 'processed'){
						$overtime = $this->Hr_model->getOvertime('PROCESSED');
					}elseif($filter == 'rejected'){
						$overtime = $this->Hr_model->getOvertime('REJECTED');
					}elseif($filter == 'all'){
						$overtime = $this->Hr_model->getAllOvertime();
					}else{
						$overtime = $this->Hr_model->getAllOvertime();
					}
				}else{
					$overtime = $this->Abas->getItems('employee_overtime',array('status'=>'FOR PROCESSING'));
				}
				$data['overtime'] = $overtime;

				$data['viewfile'] =	"hr/overtime/item_list.php";
				$this->load->view('gentlella_container.php',$data);
			}
		}

		public function autocomplete_employee(){
			$search	= $this->Mmm->sanitize($_GET['term']);
			$search	= str_replace(" ", "%", $search);
			$sql = "SELECT id, last_name, first_name, middle_name, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.','(',id,')') as full_name FROM hr_employees WHERE last_name LIKE '%".$search."%' OR first_name LIKE '%".$search."%' OR middle_name LIKE '%".$search."%' ORDER BY last_name LIMIT 0, 10";
			$items = $this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items = $items->result_array();
					$ret = array();
					foreach($items as $ctr=>$i) {
						$ret[$ctr]['label']	= $i['full_name'];
						$ret[$ctr]['value']	= $i['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
	}
?>