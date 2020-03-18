<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Mastertables extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			$this->load->database();
			$this->load->model("Mmm");
			$this->load->model("Abas");
			$this->load->model("Purchasing_model");
			$this->load->model("Inventory_model");
			$this->output->enable_profiler(FALSE);
			define("SIDEMENU", "Master Tables");
			if(!isset($_SESSION['abas_login'])) { header("location:home"); }
		}
		public function index () {
			$data['viewfile']	=	"echo.php";
			$this->load->view('gentlella_container.php',$data);
		}
		public function vessels ($action="", $id="") {$data=array();
			$target_dir		=	WPATH.'assets/uploads/operations/vessels/';
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/vessels.php";
			$mainview	=	"gentlella_container.php";
			if($id=="") {
				if($action=="add") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview=("mastertables/vessel_form.php");
				}
				elseif($action=="insert") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST['company']) && isset($_POST['name'])){
						$insert['company']								=	$this->Mmm->sanitize($_POST['company']);
						if($_FILES["photo_path"]){
							$old_filename	=	explode(".", basename($_FILES["photo_path"]["name"]));
							$new_filename	=	round(microtime(true)) . '.' . end($old_filename);
							$target_file	=	$target_dir . $new_filename;
							$uploaded		=	move_uploaded_file($_FILES["photo_path"]["tmp_name"],$target_file);
							if($uploaded){
								$insert['photo_path']					=	$new_filename;
							}
						}
						$insert['name']									=	$this->Mmm->sanitize($_POST['name']);
						$insert['ex_name']								=	$this->Mmm->sanitize($_POST['ex_name']);
						$insert['price_sold']							=	$this->Mmm->sanitize($_POST['price_sold']);
						$insert['price_paid']							=	$this->Mmm->sanitize($_POST['price_paid']);
						$insert['length_loa']							=	$this->Mmm->sanitize($_POST['length_loa']);
						$insert['length_lr']							=	$this->Mmm->sanitize($_POST['length_lr']);
						$insert['length_lbp']							=	$this->Mmm->sanitize($_POST['length_lbp']);
						$insert['breadth']								=	$this->Mmm->sanitize($_POST['breadth']);
						$insert['depth']								=	$this->Mmm->sanitize($_POST['depth']);
						$insert['draft']								=	$this->Mmm->sanitize($_POST['draft']);
						$insert['year_built']							=	$this->Mmm->sanitize($_POST['year_built']);
						$insert['builder']								=	$this->Mmm->sanitize($_POST['builder']);
						$insert['place_built']							=	$this->Mmm->sanitize($_POST['place_built']);
						$insert['jap_dwt']								=	$this->Mmm->sanitize($_POST['jap_dwt']);
						$insert['bale_capacity']						=	$this->Mmm->sanitize($_POST['bale_capacity']);
						$insert['grain_capacity']						=	$this->Mmm->sanitize($_POST['grain_capacity']);
						$insert['hatch_size']							=	$this->Mmm->sanitize($_POST['hatch_size']);
						$insert['hatch_type']							=	$this->Mmm->sanitize($_POST['hatch_type']);
						$insert['year_last_drydocked']					=	$this->Mmm->sanitize($_POST['year_last_drydocked']);
						$insert['phil_dwt']								=	$this->Mmm->sanitize($_POST['phil_dwt']);
						$insert['gross_tonnage']						=	$this->Mmm->sanitize($_POST['gross_tonnage']);
						$insert['net_tonnage']							=	$this->Mmm->sanitize($_POST['net_tonnage']);
						$insert['main_engine']							=	$this->Mmm->sanitize($_POST['main_engine']);
						$insert['main_engine_rating']					=	$this->Mmm->sanitize($_POST['main_engine_rating']);
						$insert['main_engine_actual_rating']			=	$this->Mmm->sanitize($_POST['main_engine_actual_rating']);
						$insert['model_serial_no']						=	$this->Mmm->sanitize($_POST['model_serial_no']);
						$insert['estimated_fuel_consumption']			=	$this->Mmm->sanitize($_POST['estimated_fuel_consumption']);
						$insert['bow_thrusters']						=	$this->Mmm->sanitize($_POST['bow_thrusters']);
						$insert['propeller']							=	$this->Mmm->sanitize($_POST['propeller']);
						$insert['call_sign']							=	$this->Mmm->sanitize($_POST['call_sign']);
						$insert['imo_no']								=	$this->Mmm->sanitize($_POST['imo_no']);
						$insert['monthly_amortization_no_of_months']	=	$this->Mmm->sanitize($_POST['monthly_amortization_no_of_months']);
						$insert['tc_proj_mo_income']					=	$this->Mmm->sanitize($_POST['tc_proj_mo_income']);
						$insert['hm_agreed_value']						=	$this->Mmm->sanitize($_POST['hm_agreed_value']);
						$insert['maiden_voyage']						=	$this->Mmm->sanitize($_POST['maiden_voyage']);
						$insert['replacement_cost_new']					=	$this->Mmm->sanitize($_POST['replacement_cost_new']);
						$insert['sound_value']							=	$this->Mmm->sanitize($_POST['sound_value']);
						$insert['market_value']							=	$this->Mmm->sanitize($_POST['market_value']);
						$insert['status']								=	$this->Mmm->sanitize($_POST['status']);
						$insert['created_by']							=	 $_SESSION['abas_login']['userid'];
						$insert['created']								=	date ("Y-m-d H-i-s");
						$insert['bank_account_num']						=	$this->Mmm->sanitize($_POST['bank_account_num']);
						$insert['bank_account_name']					=	$this->Mmm->sanitize($_POST['bank_account_name']);
						$checkinsert	=	$this->Mmm->dbInsert("vessels", $insert, "Add New Vessel");
						if($checkinsert){
							$notif_msg	=	"A new vessel (".$insert['name'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New Vessel", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","New vessel details added!");
						}
						else { $this ->Abas->sysMsg("errmsg","Vessel not added! Please try again" );}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
				elseif($action=="json") {
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("vessels",$search,$limit,$offset,$order,$sort);

						if($data!=false){
							foreach($data['rows'] as $ctr=>$vessels) {
								$data['rows'][$ctr]['company_name']		=	"";
								if(!empty($vessels['company'])) {
									$company							=	$this->Abas->getCompany($vessels['company']);
									$data['rows'][$ctr]['company_name']	=	$company->name;
								}
								if(!empty($vessels['created_by'])) {
									$created_by							=	$this->Abas->getUser($vessels['created_by']);
									$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
								}
								if(!empty($vessels['modified_by'])) {
									$modified_by						=	$this->Abas->getUser($vessels['modified_by']);
									$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
								}
								if(!empty($vessels['modified'])) {
									$data['rows'][$ctr]['modified']	=	date("j F Y H:i", strtotime($vessels['modified']));
								}
							}
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();
						}
					}
				}
			}
			elseif(is_numeric($id)) {
				if($action=="edit") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$vessel			=	$this->db->query("SELECT * FROM vessels WHERE id=".$id);
					$vessel			=	(array)$vessel->row();
					$data['existing']	=	$vessel;
					//$vesseldata			=	$vesseldata[0];
					//$company		=	$this->Abas->getCompany($vesseldata['company']);
					//$vesseldata['company_name']	=	$company->name;
					//$data['vessel']		=	$vesseldata;
					$mainview	=	"mastertables/vessel_form.php";
				}
				elseif($action=="update") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );

					$vessel			=	$this->db->query("SELECT * FROM vessels WHERE id=".$id);
					$vessel			=	(array)$vessel->row();

					if (isset($_POST['company'])){
						$update['company']								=	$this->Mmm->sanitize($_POST['company']);
						
						if($_FILES["photo_path"]){
							unlink($target_dir.$vessel['photo_path']);
							$old_filename	=	explode(".", basename($_FILES["photo_path"]["name"]));
							$new_filename	=	round(microtime(true)) . '.' . end($old_filename);
							$target_file	=	$target_dir . $new_filename;
							$uploaded		=	move_uploaded_file($_FILES["photo_path"]["tmp_name"],$target_file);
							if($uploaded){
								$update['photo_path']					=	$new_filename;
							}
						}
						$update['name']									=	$this->Mmm->sanitize($_POST['name']);
						$update['ex_name']								=	$this->Mmm->sanitize($_POST['ex_name']);
						$update['price_sold']							=	$this->Mmm->sanitize($_POST['price_sold']);
						$update['price_paid']							=	$this->Mmm->sanitize($_POST['price_paid']);
						$update['length_loa']							=	$this->Mmm->sanitize($_POST['length_loa']);
						$update['length_lr']							=	$this->Mmm->sanitize($_POST['length_lr']);
						$update['length_lbp']							=	$this->Mmm->sanitize($_POST['length_lbp']);
						$update['breadth']								=	$this->Mmm->sanitize($_POST['breadth']);
						$update['depth']								=	$this->Mmm->sanitize($_POST['depth']);
						$update['draft']								=	$this->Mmm->sanitize($_POST['draft']);
						$update['year_built']							=	$this->Mmm->sanitize($_POST['year_built']);
						$update['builder']								=	$this->Mmm->sanitize($_POST['builder']);
						$update['place_built']							=	$this->Mmm->sanitize($_POST['place_built']);
						$update['jap_dwt']								=	$this->Mmm->sanitize($_POST['jap_dwt']);
						$update['bale_capacity']						=	$this->Mmm->sanitize($_POST['bale_capacity']);
						$update['grain_capacity']						=	$this->Mmm->sanitize($_POST['grain_capacity']);
						$update['hatch_size']							=	$this->Mmm->sanitize($_POST['hatch_size']);
						$update['hatch_type']							=	$this->Mmm->sanitize($_POST['hatch_type']);
						$update['year_last_drydocked']					=	$this->Mmm->sanitize($_POST['year_last_drydocked']);
						$update['phil_dwt']								=	$this->Mmm->sanitize($_POST['phil_dwt']);
						$update['gross_tonnage']						=	$this->Mmm->sanitize($_POST['gross_tonnage']);
						$update['net_tonnage']							=	$this->Mmm->sanitize($_POST['net_tonnage']);
						$update['main_engine']							=	$this->Mmm->sanitize($_POST['main_engine']);
						$update['main_engine_rating']					=	$this->Mmm->sanitize($_POST['main_engine_rating']);
						$update['main_engine_actual_rating']			=	$this->Mmm->sanitize($_POST['main_engine_actual_rating']);
						$update['model_serial_no']						=	$this->Mmm->sanitize($_POST['model_serial_no']);
						$update['estimated_fuel_consumption']			=	$this->Mmm->sanitize($_POST['estimated_fuel_consumption']);
						$update['bow_thrusters']						=	$this->Mmm->sanitize($_POST['bow_thrusters']);
						$update['propeller']							=	$this->Mmm->sanitize($_POST['propeller']);
						$update['call_sign']							=	$this->Mmm->sanitize($_POST['call_sign']);
						$update['imo_no']								=	$this->Mmm->sanitize($_POST['imo_no']);
						$update['monthly_amortization_no_of_months']	=	$this->Mmm->sanitize($_POST['monthly_amortization_no_of_months']);
						$update['tc_proj_mo_income']					=	$this->Mmm->sanitize($_POST['tc_proj_mo_income']);
						$update['hm_agreed_value']						=	$this->Mmm->sanitize($_POST['hm_agreed_value']);
						$update['maiden_voyage']						=	$this->Mmm->sanitize($_POST['maiden_voyage']);
						$update['replacement_cost_new']					=	$this->Mmm->sanitize($_POST['replacement_cost_new']);
						$update['sound_value']							=	$this->Mmm->sanitize($_POST['sound_value']);
						$update['market_value']							=	$this->Mmm->sanitize($_POST['market_value']);
						$update['status']								=	$this->Mmm->sanitize($_POST['status']);
						$update['modified_by']							=	$_SESSION['abas_login']['userid'];
						$update['modified']								=	date("Y-m-d H:i:s");
						$update['bank_account_num']						=	$this->Mmm->sanitize($_POST['bank_account_num']);
						$update['bank_account_name']					=	$this->Mmm->sanitize($_POST['bank_account_name']);
						$checkupdate	=	$this->Mmm->dbUpdate("vessels", $update, $id, "Update Vessel");
						if($checkupdate){
							$notif_msg	=	"A new vessel (".$update['name'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New Vessel", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Vessel details updated!");
						}
						else { $this ->Abas->sysMsg("errmsg","Vessel details not updated! Please try again" );}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->load->view($mainview, $data);
		}
		public function trucks ($action="", $id="") {$data=array();
			$target_dir		=	WPATH.'assets/uploads/operations/trucks/';
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/trucks.php";
			$mainview	=	"gentlella_container.php";
			if($id=="") {
				if($action=="add") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview=("mastertables/truck_form.php");
				}
				elseif($action=="insert") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST['company'])){
						$insert['company']								=	$this->Mmm->sanitize($_POST['company']);
						if($_FILES["photo_path"]){
							$old_filename	=	explode(".", basename($_FILES["photo_path"]["name"]));
							$new_filename	=	round(microtime(true)) . '.' . end($old_filename);
							$target_file	=	$target_dir . $new_filename;
							$uploaded		=	move_uploaded_file($_FILES["photo_path"]["tmp_name"],$target_file);
							if($uploaded){
								$insert['photo_path']					=	$new_filename;
							}
						}
						$insert['make']									=	$this->Mmm->sanitize($_POST['make']);
						$insert['model']								=	$this->Mmm->sanitize($_POST['model']);
						$insert['plate_number']							=	$this->Mmm->sanitize($_POST['plate_number']);
						$insert['engine_number']						=	$this->Mmm->sanitize($_POST['engine_number']);
						$insert['chassis_number']						=	$this->Mmm->sanitize($_POST['chassis_number']);
						$insert['type']									=	$this->Mmm->sanitize($_POST['vehicle_type']);
						$insert['color']								=	$this->Mmm->sanitize($_POST['color']);
						$insert['date_acquired']						=	$this->Mmm->sanitize($_POST['date_acquired']);
						$insert['registration_month']					=	$this->Mmm->sanitize($_POST['registration_month']);
						$insert['aquisition_cost']						=	$this->Mmm->sanitize($_POST['acquisition_cost']);
						$insert['stat']									=	$this->Mmm->sanitize($_POST['status']);
						$insert['created_by']							=	$_SESSION['abas_login']['userid'];
						$insert['created_on']								=	date("Y-m-d H:i:s");
						$checkinsert	=	$this->Mmm->dbInsert("trucks", $insert, "Add New Truck");
						if($checkinsert){
							$notif_msg	=	"A new truck with plate-number(".$insert['plate_number'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New Truck", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","New Truck details added!");
						}
						else { $this ->Abas->sysMsg("errmsg","Truck not added! Please try again" );}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
				elseif($action=="json") {
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("trucks",$search,$limit,$offset,$order,$sort);

						if($data!=false){
							foreach($data['rows'] as $ctr=>$trucks) {
								$data['rows'][$ctr]['company_name']		=	"";
								if(!empty($trucks['company'])) {
									$company							=	$this->Abas->getCompany($trucks['company']);
									$data['rows'][$ctr]['company_name']	=	$company->name;
								}
								if(!empty($trucks['created_by'])) {
									$created_by							=	$this->Abas->getUser($trucks['created_by']);
									$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
								}
								if(!empty($trucks['modified_by'])) {
									$modified_by						=	$this->Abas->getUser($trucks['modified_by']);
									$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
								}
								if(!empty($trucks['modified'])) {
									$data['rows'][$ctr]['modified']	=	date("j F Y H:i", strtotime($trucks['modified']));
								}
								if($trucks['stat']==1){
									$data['rows'][$ctr]['status']	= "Active";
								}else{
									$data['rows'][$ctr]['status']	= "Inactive";
								}
							}
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();
						}
					}
				}
			}
			elseif(is_numeric($id)) {
				if($action=="edit") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$truck			=	$this->db->query("SELECT * FROM trucks WHERE id=".$id);
					$truck			=	(array)$truck->row();
					$data['existing']	=	$truck;
					$mainview	=	"mastertables/truck_form.php";
				}
				elseif($action=="update") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST['company'])){
						$update['company']								=	$this->Mmm->sanitize($_POST['company']);
						if($_FILES["photo_path"]){
							$old_filename	=	explode(".", basename($_FILES["photo_path"]["name"]));
							$new_filename	=	round(microtime(true)) . '.' . end($old_filename);
							$target_file	=	$target_dir . $new_filename;
							$uploaded		=	move_uploaded_file($_FILES["photo_path"]["tmp_name"],$target_file);
							if($uploaded){
								$update['photo_path']					=	$new_filename;
							}
						}
						$update['make']									=	$this->Mmm->sanitize($_POST['make']);
						$update['model']								=	$this->Mmm->sanitize($_POST['model']);
						$update['plate_number']							=	$this->Mmm->sanitize($_POST['plate_number']);
						$update['engine_number']						=	$this->Mmm->sanitize($_POST['engine_number']);
						$update['chassis_number']						=	$this->Mmm->sanitize($_POST['chassis_number']);
						$update['type']									=	$this->Mmm->sanitize($_POST['vehicle_type']);
						$update['color']								=	$this->Mmm->sanitize($_POST['color']);
						$update['date_acquired']						=	$this->Mmm->sanitize($_POST['date_acquired']);
						$update['registration_month']					=	$this->Mmm->sanitize($_POST['registration_month']);
						$update['aquisition_cost']						=	$this->Mmm->sanitize($_POST['acquisition_cost']);
						$update['stat']									=	$this->Mmm->sanitize($_POST['status']);
						$update['modified_by']							=	$_SESSION['abas_login']['userid'];
						$update['modified_on']								=	date("Y-m-d H:i:s");
						$checkupdate	=	$this->Mmm->dbUpdate("trucks", $update, $id, "Update Truck");
						if($checkupdate){
							$notif_msg	=	"Truck with plate number(".$update['plate_number'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Update Truck", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Truck details updated!");
						}
						else { $this ->Abas->sysMsg("errmsg","Truck details not updated! Please try again" );}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->load->view($mainview, $data);
		}
		public function suppliers($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/suppliers.php";
			$mainview			=	"gentlella_container.php";
			if($id=="") {
				if($action=="add") {
				$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview	=	"mastertables/suppliers_form.php";
				}
				elseif($action=="insert") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if(!empty($_POST['name']) && !empty($_POST['type'])){
						$insert['name']					=	$this->Mmm->sanitize($_POST['name']);
						$insert['address']				=	$this->Mmm->sanitize($_POST['address']);
						$insert['contact_person']		=	$this->Mmm->sanitize($_POST['contact_person']);
						$insert['telephone_no']			=	str_replace("-", "", $this->Mmm->sanitize($_POST['telephone_no']));
						$insert['email']				=	$this->Mmm->sanitize($_POST['email']);
						$insert['fax_no']				=	str_replace("-", "", $this->Mmm->sanitize($_POST['fax_no']));
						$insert['payment_terms']		=	$this->Mmm->sanitize($_POST['payment_terms']);
						$insert['status']				=	$this->Mmm->sanitize($_POST['status']);
						$insert['tin']					=	str_replace("-", "", $this->Mmm->sanitize($_POST['tin']));
						$insert['issues_reciepts']		=	1;
						$insert['taxation_percentile']	=	$this->Mmm->sanitize($_POST['taxation_percentile']);
						$insert['type']					=	$this->Mmm->sanitize($_POST['type']);
						$insert['vat_computation']		=	$this->Mmm->sanitize($_POST['vat_computation']);
						$insert['bank_name']			=	$this->Mmm->sanitize($_POST['bank_name']);
						$insert['account_name']			=	$this->Mmm->sanitize($_POST['account_name']);
						$insert['bank_account_no']		=	str_replace("-", "", $this->Mmm->sanitize($_POST['bank_account_no']));
						$insert['created_by']			=	$_SESSION['abas_login']['userid'];
						$insert['created_on']			=	date("Y-m-d H:i:s");
						$check							=	$this->db->query("SELECT * FROM suppliers WHERE name LIKE '".$insert['name']."'");
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That supplier already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkinsert	=	$this->Mmm->dbInsert("suppliers",$insert,"Add new supplier");
					}
					if($checkinsert){
						$notif_msg	=	"A new supplier (".$insert['name'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
						$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
						$this->Abas->sysMsg("sucmsg","New supplier added!");
					}
					else { $this ->Abas->sysMsg("errmsg","Supplier not added! Please try again" ); }
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
				elseif($action=="json") {
					if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("suppliers",$search,$limit,$offset,$order,$sort);
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
						if($data!=false) {
							foreach($data['rows'] as $ctr=>$supplier) {
								$data['rows'][$ctr]['issues_reciepts']	=	$supplier['issues_reciepts'] ==	0 ? "No":"Yes";
								$data['rows'][$ctr]['vat_registered']	=	$supplier['vat_registered'] ==	0 ? "No":"Yes";
								$data['rows'][$ctr]['created_on']		=	($supplier['created_on']=="0000-00-00 00:00:00") ? "":date("j F Y",strtotime($supplier['created_on']));
								$data['rows'][$ctr]['modified_on']		=	($supplier['modified_on']=="0000-00-00 00:00:00") ? "":date("j F Y",strtotime($supplier['modified_on']));
								if($supplier['taxation_percentile']==1) $data['rows'][$ctr]['taxation_percentile']='1%';
								if($supplier['taxation_percentile']==2) $data['rows'][$ctr]['taxation_percentile']='2%';
								if($supplier['taxation_percentile']==3) $data['rows'][$ctr]['taxation_percentile']='3%';
								if(!empty($supplier['created_by'])) {
									$created_by							=	$this->Abas->getUser($supplier['created_by']);
									$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
								}
								if(!empty($supplier['modified_by'])) {
									$modified_by						=	$this->Abas->getUser($supplier['modified_by']);
									$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
								}
							}


						}
					}
				}
			}
			elseif(is_numeric($id)) {
				$supplier			=	$this->Abas->getSupplier($id);
				if(empty($supplier)) {
					$this->Abas->sysMsg("warnmsg", "Supplier not found! Please try again.");
					$this->Abas->redirect(HTTP_PATH."mastertables/suppliers");
				}
				$data['supplier']	=	$supplier;
				if($action=="edit") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview	=	"mastertables/suppliers_form.php";
				}
				elseif($action=="update") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					//$this->Mmm->debug($_POST); exit;
					if (!empty($_POST['name']) && !empty($_POST['type'])){
						$update['name']					=	$this->Mmm->sanitize($_POST['name']);
						$update['address']				=	$this->Mmm->sanitize($_POST['address']);
						$update['contact_person']		=	$this->Mmm->sanitize($_POST['contact_person']);
						$update['telephone_no']			=	str_replace("-", "", $this->Mmm->sanitize($_POST['telephone_no']));
						$update['email']				=	$this->Mmm->sanitize($_POST['email']);
						$update['fax_no']				=	str_replace("-", "", $this->Mmm->sanitize($_POST['fax_no']));
						$update['payment_terms']		=	$this->Mmm->sanitize($_POST['payment_terms']);
						$update['status']				=	$this->Mmm->sanitize($_POST['status']);
						$update['tin']					=	str_replace("-", "", str_replace("-", "", $this->Mmm->sanitize($_POST['tin'])));
						$update['vat_computation']		=	$this->Mmm->sanitize($_POST['vat_computation']);
						$update['taxation_percentile']	=	$this->Mmm->sanitize($_POST['taxation_percentile']);
						$update['type']					=	$this->Mmm->sanitize($_POST['type']);
						$update['issues_reciepts']		=	1;
						$update['bank_name']			=	$this->Mmm->sanitize($_POST['bank_name']);
						$update['account_name']			=	$this->Mmm->sanitize($_POST['account_name']);
						$update['bank_account_no']		=	str_replace("-", "", $this->Mmm->sanitize($_POST['bank_account_no']));
						$update['modified_by']			=	$_SESSION['abas_login']['userid'];
						$update['modified_on']			=	date("Y-m-d H:i:s");
						$this->Mmm->debug($update);
						$check							=	$this->db->query("SELECT * FROM suppliers WHERE id<>".$id." AND name LIKE '".$insert['name']."'");
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That supplier already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkupdate =$this->Mmm->dbUpdate("suppliers",$update,$id, "Update Supplier");
						if($checkupdate==TRUE) {
							$notif_msg	=	"The supplier (".$update['name'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Supplier Updated!");
						}
						else {
							$this->Abas->sysMsg("errmsg","Failed to update supplier! Please try again.");
						}
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
				elseif($action=="report_purchases_filter") {
					$mainview					=	"mastertables/supplier_report_filter.php";
				}
				elseif($action=="report_purchases") {
					$this->Mmm->debug($_GET);
					if(!isset($_GET['dstart']) || !isset($_GET['dfinish'])) {
						$this->Abas->sysMsg("warnmsg", "No report date selected!");
						$this->Abas->redirect($previous_page);
					}
					$date_start		=	date("Y-m-d", strtotime($_GET['dstart']))." 00:00:00";
					$date_finish	=	date("Y-m-d", strtotime($_GET['dfinish']))." 23:59:59";
					if(isset($_GET['company'])) {
						if(is_numeric($_GET['company'])) {
							$company		=	$this->Abas->getCompany($_GET['company']);
							if($company) {
								$company_query			=	' AND company_id='.$company->id;
							}
						}
					}
					if(isset($_GET['vessel'])) {
						if(is_numeric($_GET['vessel'])) {
							$vessel			=	$this->Abas->getVessel($_GET['vessel']);
							if($vessel){
								$vessel_query			=	' AND vessel_id='.$vessel->id;
							}
						}
					}
					$itemssql					=	"SELECT i.id AS item_id, i.description,i.particular, i.unit,i.brand FROM inventory_po AS po JOIN inventory_po_details AS d ON d.po_id=po.id JOIN inventory_items AS i ON i.id=d.item_id WHERE po.approved_on IS NOT NULL AND po.supplier_id=".$id." AND po.stat=1 GROUP BY d.item_id";
					$purchased_items			=	$this->db->query($itemssql);
					$ret						=	array();
					if($purchased_items) {
						if($purchased_items->row()) {
							$purchased_items	=	$purchased_items->result_array();
							foreach($purchased_items as $itemctr=>$purchased_item) {
								$ret[$itemctr]	=	$purchased_item;
								$pricessql		=	"SELECT po.id AS po_id, po.tdate, d.unit_price, d.quantity FROM inventory_po AS po JOIN inventory_po_details AS d ON d.po_id=po.id JOIN inventory_items AS i ON i.id=d.item_id WHERE po.approved_on IS NOT NULL AND i.id=".$purchased_item['item_id']." AND po.supplier_id=".$id." AND po.stat=1 ORDER BY d.item_id ASC, po.tdate DESC";
								$prices			=	$this->db->query($pricessql);
								if($prices) {
									if($prices->row()) {
										$prices	=	$prices->result_array();
										foreach($prices as $pricectr=>$price) {
											$ret[$itemctr]['prices'][$pricectr]	=	$price;
										}
									}
								}
							}
						}
					}
					$data['purchased_items']	=	$ret;
					$data['viewfile']			=	"purchasing/supplier_report.php";
				}
			}
			$this->load->view($mainview,$data);
		}
		public function clients ($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/clients.php";
			$mainview			=	"gentlella_container.php";
			if($id=="") {
				if($action=="add") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview	=	"mastertables/clients_form.php";
				}
				elseif($action=="insert") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if(!empty($_POST['company'])){
						$insert['company']					=	$this->Mmm->sanitize($_POST['company']);
						$insert['address']					=	$this->Mmm->sanitize($_POST['address']);
						$insert['city']						=	$this->Mmm->sanitize($_POST['city']);
						$insert['province']					=	$this->Mmm->sanitize($_POST['province']);
						$insert['country']					=	$this->Mmm->sanitize($_POST['country']);
						$insert['contact_no']				=	$this->Mmm->sanitize($_POST['contact_no']);
						$insert['fax_no']					=	$this->Mmm->sanitize($_POST['fax_no']);
						$insert['email']					=	$this->Mmm->sanitize($_POST['email']);
						$insert['website']					=	$this->Mmm->sanitize($_POST['website']);
						$insert['contact_person']			=	$this->Mmm->sanitize($_POST['contact_person']);
						$insert['position']					=	$this->Mmm->sanitize($_POST['position']);
						$insert['lead_person']				=	$this->Mmm->sanitize($_POST['lead_person']);
						$insert['stat']						=	$this->Mmm->sanitize($_POST['stat']);
						$insert['tin_no']					=	$this->Mmm->sanitize($_POST['tin_no']);
						$check								=	$this->db->query("SELECT * FROM clients WHERE company LIKE '".$insert['company']."'");
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That client already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkinsert	=	$this->Mmm->dbInsert("clients",$insert,"Add new client");
					}
					if($checkinsert){
						$notif_msg	=	"A new client (".$insert['company'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
						$this->Abas->sysNotif("New client", $notif_msg, "everyone");
						$this->Abas->sysMsg("sucmsg","New client added!");
					}
					else { $this ->Abas->sysMsg("errmsg","client not added! Please try again" ); }
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
				elseif($action=="json") {
					if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("clients",$search,$limit,$offset,$order,$sort);
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
						if($data!=false) {
							foreach($data['rows'] as $ctr=>$clients) {
								$data['rows'][$ctr]['issues_reciepts']	=	$clients['issues_reciepts'] ==	0 ? "No":"Yes";
								$data['rows'][$ctr]['vat_registered']	=	$clients['vat_registered'] ==	0 ? "No":"Yes";
								$data['rows'][$ctr]['created_on']		=	($clients['created_on']=="0000-00-00 00:00:00") ? "":date("j F Y",strtotime($clients['created_on']));
								$data['rows'][$ctr]['modified_on']		=	($clients['modified_on']=="0000-00-00 00:00:00") ? "":date("j F Y",strtotime($clients['modified_on']));
								if($clients['taxation_percentile']==1) $data['rows'][$ctr]['taxation_percentile']='1%';
								if($clients['taxation_percentile']==2) $data['rows'][$ctr]['taxation_percentile']='2%';
								if($clients['taxation_percentile']==3) $data['rows'][$ctr]['taxation_percentile']='3%';
								if(!empty($clients['created_by'])) {
									$created_by							=	$this->Abas->getUser($clients['created_by']);
									$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
								}
								if(!empty($clients['modified_by'])) {
									$modified_by						=	$this->Abas->getUser($clients['modified_by']);
									$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
								}
							}


						}
					}
				}
				elseif($action=="randomize_tax_info") {
					if(ENVIRONMENT!="development") {
						$this->Abas->sysMsg("errmsg", "This can only be accessed from the development server!");
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$clients	=	$this->db->query("SELECT id FROM clients");
					if(!$clients) {
						if($clients->row()) {
							$checkAllQueries			=	true;
							foreach($clients as $clients) {
								$vat_choices			=	array("non-vat","inclusive","exclusive");
								$taxation_choices		=	array("1","2","5","10");
								$vat_computation		=	$vat_choices[rand(0,2)];
								$taxation_percentile	=	$taxation_choices[rand(0,3)];
								$updateSQL				=	"UPDATE clients SET vat_computation=".$vat_computation." AND taxation_percentile=".$taxation_percentile." WHERE id=".$client['id'];
								$check					=	$this->db->query($updateSQL);
								if(!$check) {
									$checkAllQueries	=	false;
								}
							}
							if($checkAllQueries) { $this->Abas->sysMsg("errmsg", "Not all clients were updated!"); }
							else { $this->Abas->sysMsg("sucmsg", "All clients were updated!"); }
						} else { $this->Abas->sysMsg("errmsg", "clients not found!"); }
					} else { $this->Abas->sysMsg("errmsg", "clients not found!"); }
					$this->Abas->redirect(HTTP_PATH."mastertables/clients");
				}
			}
			elseif(is_numeric($id)) {
				if($action=="edit") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$clients	=	$this->db->query("SELECT * FROM clients WHERE id=".$id);
					$clients=	(array)$clients->row();
					$data['existing']	=	$clients;
					$mainview	=	"mastertables/clients_form.php";
				}
				elseif($action=="update") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					//$this->Mmm->debug($_POST); exit;
					if (!empty($_POST['company'])){
						$update['company']					=	$this->Mmm->sanitize($_POST['company']);
						$update['address']					=	$this->Mmm->sanitize($_POST['address']);
						$update['city']						=	$this->Mmm->sanitize($_POST['city']);
						$update['province']					=	$this->Mmm->sanitize($_POST['province']);
						$update['country']					=	$this->Mmm->sanitize($_POST['country']);
						$update['contact_no']				=	$this->Mmm->sanitize($_POST['contact_no']);
						$update['fax_no']					=	$this->Mmm->sanitize($_POST['fax_no']);
						$update['email']					=	$this->Mmm->sanitize($_POST['email']);
						$update['website']					=	$this->Mmm->sanitize($_POST['website']);
						$update['contact_person']			=	$this->Mmm->sanitize($_POST['contact_person']);
						$update['position']					=	$this->Mmm->sanitize($_POST['position']);
						$update['lead_person']				=	$this->Mmm->sanitize($_POST['lead_person']);
						$update['stat']						=	$this->Mmm->sanitize($_POST['stat']);
						$update['tin_no']					=	$this->Mmm->sanitize($_POST['tin_no']);
						$update['modified_by']				=	$_SESSION['abas_login']['userid'];
						$update['modified_on']				=	date("Y-m-d H:i:s");
						$this->Mmm->debug($update);
						$check								=	$this->db->query("SELECT * FROM clients WHERE company LIKE '".$update['company']."' AND id<>".$id);
						if($check) {
							$result = $check->result();
							if($result) {
								$this->Abas->sysMsg("warnmsg", "That client already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkupdate =$this->Mmm->dbUpdate("clients",$update,$id, "Update clients");
						if($checkupdate) {
							$notif_msg	=	"The client (".$update['company'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New client", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Client Updated!");
						}
						else {
							$this->Abas->sysMsg("errmsg","Failed to update client! Please try again.");
						}
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
			}
			$this->load->view($mainview,$data);
		}
		public function departments($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/departments.php";
			$mainview			=	"gentlella_container.php";
			if($id=="") {
				if($action=="add") {
				$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview	=	"mastertables/departments_form.php";
				}
				elseif($action=="insert") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$this->Mmm->debug($_POST);
					if(isset($_POST['name'])){
						$insert['name']					=	$this->Mmm->sanitize($_POST['name']);
						$insert['stat']					=	$this->Mmm->sanitize($_POST['stat']);
						$insert['accounting_code']		=	$this->Mmm->sanitize($_POST['accounting_code']);
						$insert['sorting']				=	$this->Mmm->sanitize($_POST['sorting']);
						$insert['created_by']			=	$_SESSION['abas_login']['userid'];
						$insert['created']				=	date("Y-m-d H:i:s");
						$check							=	$this->db->query("SELECT * FROM departments WHERE name LIKE '".$insert['name']."'");
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That department already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkinsert					=	$this->Mmm->dbInsert("departments",$insert,"Add new department");
						if($checkinsert){
							$notif_msg	=	"A new department (".$insert['name'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New department", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","New department added!");
						}
						else { $this ->Abas->sysMsg("errmsg","Department not added! Please try again" ); }
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
				elseif($action=="json") {
					if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("departments",$search,$limit,$offset,$order,$sort);
						if($data!=false) {
							foreach($data['rows'] as $ctr=>$department) {
								$data['rows'][$ctr]['created']		=	($department['created']=="0000-00-00 00:00:00") ? "":date("j F Y",strtotime($department['created']));
								$data['rows'][$ctr]['modified']		=	($department['modified']=="0000-00-00 00:00:00") ? "":date("j F Y",strtotime($department['modified']));
								if(!empty($department['created_by'])) {
									$created_by							=	$this->Abas->getUser($department['created_by']);
									$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
								}
								if(!empty($department['modified_by'])) {
									$modified_by						=	$this->Abas->getUser($department['modified_by']);
									$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
								}
							}
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();
						}
					}
				}
			}
			elseif(is_numeric($id)) {
				if($action=="edit") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$department			=	$this->db->query("SELECT * FROM departments WHERE id=".$id);
					$department			=	(array)$department->row();
					$data['existing']	=	$department;
					$mainview			=	"mastertables/departments_form.php";
				}
				elseif($action=="update") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST['name'])){
						$update['name']				=	$this->Mmm->sanitize($_POST['name']);
						$update['stat']				=	$this->Mmm->sanitize($_POST['stat']);
						$update['accounting_code']	=	$this->Mmm->sanitize($_POST['accounting_code']);
						$update['sorting']			=	$this->Mmm->sanitize($_POST['sorting']);
						$update['modified_by']		=	$_SESSION['abas_login']['userid'];
						$update['modified']			=	date("Y-m-d H:i:s");
						$check						=	$this->db->query("SELECT * FROM departments WHERE id<>".$id." AND name LIKE '".$update['name']."'");
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That department already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkupdate =$this->Mmm->dbUpdate("departments",$update,$id, "Update Department");
						if($checkupdate) {
							$notif_msg	=	"The department (".$update['name'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New department", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Department Updated!");
						}
						else {$this->Abas->sysMsg("errmsg","Failed to update department! Please try again.");}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->load->view($mainview,$data);
		}
		public function salary_grades($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/salary_grades.php";
			$mainview			=	"gentlella_container.php";
			if($id=="") {
				if($action=="add") {
			$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview	=	"mastertables/salary_grade_form.php";
				}
				elseif($action=="insert") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$this->Mmm->debug($_POST);
					if(isset($_POST['grade']) && isset($_POST['rate'])) {
						$insert['grade']				=	$this->Mmm->sanitize($_POST['grade']);
						$insert['rate']					=	$this->Mmm->sanitize($_POST['rate']);
						$insert['level']				=	$this->Mmm->sanitize($_POST['level']);
						$insert['stat']					=	1;
						$check							=	$this->db->query("SELECT * FROM salary_grades WHERE grade LIKE '".$insert['grade']."' OR rate=".$insert['rate']);
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That salary grade already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkinsert					=	$this->Mmm->dbInsert("salary_grades",$insert,"Add new salary grade");
						if($checkinsert){
							$notif_msg	=	"A new salary grade (".$insert['grade'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New salary grade", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","New salary grade added!");
						}
						else { $this ->Abas->sysMsg("errmsg","Salary grade not added! Please try again" ); }
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
				elseif($action=="json") {
					if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("salary_grades",$search,$limit,$offset,$order,$sort);
						if($data!=false) {
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();
						}
					}
				}
			}
			elseif(is_numeric($id)) {
				if($action=="edit") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$salary_grade		=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$id);
					$salary_grade		=	(array)$salary_grade->row();
					$data['existing']	=	$salary_grade;
					$mainview			=	"mastertables/salary_grade_form.php";
				}
				elseif($action=="update") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST['grade']) && isset($_POST['rate'])){
						$update['grade']				=	$this->Mmm->sanitize($_POST['grade']);
						$update['rate']					=	$this->Mmm->sanitize($_POST['rate']);
						$update['level']				=	$this->Mmm->sanitize($_POST['level']);
						$update['stat']					=	1;
						$check							=	$this->db->query("SELECT * FROM salary_grades WHERE id<>".$id." AND grade LIKE '".$update['grade']."' AND rate=".$update['rate']);
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That salary grade already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkupdate =$this->Mmm->dbUpdate("salary_grades",$update,$id, "Update Salary Grade");
						if($checkupdate) {
							$notif_msg	=	"The salary grade (".$update['grade'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Edited salary grade", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Salary Grade Updated!");
						}
						else {$this->Abas->sysMsg("errmsg","Failed to update salary grade! Please try again.");}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->load->view($mainview,$data);
		}
		public function positions($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/positions.php";
			$mainview			=	"gentlella_container.php";
			if ($id ==""){
				if ($action ==	"add"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview="mastertables/positions_form.php";
				}
				elseif($action=="insert"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST['name'])){
						$insert['department_id']=	$this->Mmm->sanitize($_POST['department_id']);
						$insert['name']			=	$this->Mmm->sanitize($_POST['name']);
						$insert['sorting']		=	$this->Mmm->sanitize($_POST['sorting']);
						$insert['stat']			=	1;
						$insert['created_by']	=	$this->Mmm->sanitize($_POST['created_by']);
						$insert['created']		=	date("Y-m-d H-i-s");
						$this->Mmm->debug($insert);
						$check					=	$this->db->query("SELECT * FROM positions WHERE name LIKE '".$insert['name']."' AND department_id=".$insert['department_id']);
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That position already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$this->Mmm->debug($check);
						$checkinsert	=	$this->Mmm->dbInsert("positions",$insert,"Add new position");

						if($checkinsert){
							$notif_msg	=	"A new position (".$insert['name'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","New position added!");
						}
						else { $this ->Abas->sysMsg("errmsg","Position not added! Please try again" ); }
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}

				elseif($action=="json") {
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("positions",$search,$limit,$offset,$order,$sort);

						if($data!=false){
							foreach($data['rows'] as $ctr=>$position) {
								$data['rows'][$ctr]['department_name']	=	"";
								if(!empty($position['department_id'])) {
									$department							=	$this->Abas->getDepartment($position['department_id']);
									// $this->Mmm->debug($department);
									$data['rows'][$ctr]['department_name']	=	$department->name;
								}
								if(!empty($position['created_by'])) {
									$created_by							=	$this->Abas->getUser($position['created_by']);
									$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
								}
								if(!empty($position['modified_by'])) {
									$modified_by						=	$this->Abas->getUser($position['modified_by']);
									$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
								}
							}
							// $this->Mmm->debug($data);
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();

						}
					}
				}
			}
			elseif (is_numeric($id)){
				if ($action=="edit"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$position			=	$this->db->query("SELECT * FROM positions WHERE id=".$id);
					$position			=	(array)$position->row();
					$data['existing']	=	$position;
					$mainview			=	"mastertables/positions_form";
				}
				elseif ($action==	"update"){
					if (isset($_POST['name'])){
						$update['department_id']	=	$this->Mmm->sanitize($_POST['department_id']);
						$update['name']				=	$this->Mmm->sanitize($_POST['name']);
						$update['sorting']			=	$this->Mmm->sanitize($_POST['sorting']);
						$update['stat']				=	$this->Mmm->sanitize($_POST['stat']);
						$update['created_by']		=	$this->Mmm->sanitize($_POST['created_by']);
						$update['modified_by']		=	$this->Mmm->sanitize($_POST['modified_by']);
						$update['created']			=	$this->Mmm->sanitize($_POST['created']);
						$update['modified']			=	date("Y-m-d H-i-s");
						$check					=	$this->db->query("SELECT * FROM positions WHERE id<>".$id." AND name LIKE '".$update['name']."' AND department_id=".$update['department_id']);
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That position already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkupdate				=	$this->Mmm->dbUpdate("positions",$update, $id,"Update Position");
						if($checkupdate) {
							$notif_msg				=	"The position (".$update['name'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Position Updated!");
						}
						else {$this->Abas->sysMsg("errmsg","Failed to update Position! Please try again.");}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->load->view($mainview,$data);
		}
		public function tax_codes($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/tax_codes.php";
			$mainview			=	"gentlella_container.php";
			if ($id ==""){
				if ($action ==	"add"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview="mastertables/tax_codes_form.php";
				}
				elseif($action=="insert"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST)){
						$insert['from_sal']		=	$this->Mmm->sanitize($_POST['from_sal']);
						$insert['to_sal']		=	$this->Mmm->sanitize($_POST['to_sal']);
						$insert['over']			=	$this->Mmm->sanitize($_POST['over']);
						$insert['amount']		=	$this->Mmm->sanitize($_POST['amount']);
						$insert['stat']			=	1;
						
						$checkinsert	=	$this->Mmm->dbInsert("annual_tax_codes",$insert,"Add new Tax Code");

						if($checkinsert){
							$notif_msg	=	"A new Tax Code has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","New Tax Code added!");
						}
						else { $this ->Abas->sysMsg("errmsg","Tax Code not added! Please try again" ); }
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}

				elseif($action=="json") {
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("annual_tax_codes",$search,$limit,$offset,$order,$sort);
						if($data!=false){
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();
						}
					}
				}
			}
			elseif (is_numeric($id)){
				if ($action=="edit"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$tax_codes			=	$this->db->query("SELECT * FROM annual_tax_codes WHERE id=".$id);
					$tax_codes			=	(array)$tax_codes->row();
					$data['existing']	=	$tax_codes;
					$mainview			=	"mastertables/tax_codes_form";
				}
				elseif ($action==	"update"){
					if (isset($_POST)){
						$update['from_sal']		=	$this->Mmm->sanitize($_POST['from_sal']);
						$update['to_sal']		=	$this->Mmm->sanitize($_POST['to_sal']);
						$update['over']			=	$this->Mmm->sanitize($_POST['over']);
						$update['amount']		=	$this->Mmm->sanitize($_POST['amount']);
						$update['stat']			=	$this->Mmm->sanitize($_POST['stat']);
						
						$checkupdate				=	$this->Mmm->dbUpdate("annual_tax_codes",$update, $id,"Update Tax Code");
						if($checkupdate) {
							$notif_msg				=	"Tax Code has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Tax Code Updated!");
						}
						else {$this->Abas->sysMsg("errmsg","Failed to update Tax Code! Please try again.");}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->load->view($mainview,$data);
		}
		public function companies($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/companies.php";
			$mainview			=	"gentlella_container.php";
			if ($id ==""){
				if ($action ==	"add"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview="mastertables/companies_form.php";
				}
				elseif($action=="insert"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST)){
						$insert['name']					=	$this->Mmm->sanitize($_POST['company_name']);
						$insert['address']				=	$this->Mmm->sanitize($_POST['address']);
						$insert['telephone_no']			=	$this->Mmm->sanitize($_POST['telephone_no']);
						$insert['fax_no']				=	$this->Mmm->sanitize($_POST['fax_no']);
						$insert['company_tin']			=	$this->Mmm->sanitize($_POST['company_tin']);
						$insert['is_top_20000']			=	$this->Mmm->sanitize($_POST['is_top_20000']);
						$insert['created_by']			=	$_SESSION['abas_login']['userid'];
						$insert['created']				=	date("Y-m-d H-i-s");
						$insert['stat']					=	1;
						
						$checkinsert	=	$this->Mmm->dbInsert("companies",$insert,"Add new Company");

						if($checkinsert){
							$notif_msg	=	"A new Company (".$insert['name'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","New Company added!");
						}
						else { $this ->Abas->sysMsg("errmsg","Company not added! Please try again" ); }
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}

				elseif($action=="json") {
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("companies",$search,$limit,$offset,$order,$sort);
						if($data!=false){

							foreach($data['rows'] as $ctr=>$company) {
								if($company['is_top_20000']==1) {
									$data['rows'][$ctr]['is_top_20000']	=	"Yes";
								}else{
									$data['rows'][$ctr]['is_top_20000']	=	"No";
								}

								if(isset($company['created_by'])) {
									$user = $this->Abas->getUser($company['created_by']);
									$data['rows'][$ctr]['created_by']	= $user['full_name'];
								}
								if(isset($company['modified_by'])) {
									$user = $this->Abas->getUser($company['modified_by']);
									$data['rows'][$ctr]['modified_by']	= $user['full_name'];
								}
								if(isset($company['created'])) {
									$data['rows'][$ctr]['created']	= date('Y-m-d H:mm:ss',strtotime($company['created']));
								}
								if(isset($company['modified'])) {
									$data['rows'][$ctr]['modified']	= date('Y-m-d H:mm:ss',strtotime($company['modified']));
								}

							}
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();
						}
					}
				}
			}
			elseif (is_numeric($id)){
				if ($action=="edit"){
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$company			=	$this->db->query("SELECT * FROM companies WHERE id=".$id);
					$company			=	(array)$company->row();
					$data['existing']	=	$company;
					$mainview			=	"mastertables/companies_form";
				}
				elseif ($action==	"update"){
					if (isset($_POST)){
						$update['name']					=	$this->Mmm->sanitize($_POST['company_name']);
						$update['address']				=	$this->Mmm->sanitize($_POST['address']);
						$update['telephone_no']			=	$this->Mmm->sanitize($_POST['telephone_no']);
						$update['fax_no']				=	$this->Mmm->sanitize($_POST['fax_no']);
						$update['company_tin']			=	$this->Mmm->sanitize($_POST['company_tin']);
						$update['is_top_20000']			=	$this->Mmm->sanitize($_POST['is_top_20000']);
						$update['modified_by']			=	$_SESSION['abas_login']['userid'];
						$update['modified']				=	date("Y-m-d H-i-s");
						$update['stat']					=	$this->Mmm->sanitize($_POST['stat']);
						
						$checkupdate				=	$this->Mmm->dbUpdate("companies",$update, $id,"Update Company");
						if($checkupdate) {
							$notif_msg				=	"Company (".$update['name'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Company Updated!");
						}
						else {$this->Abas->sysMsg("errmsg","Failed to update Company! Please try again.");}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->load->view($mainview,$data);
		}
		public function inventory_items($action="", $id=""){$data=array();
			$this->Abas->checkPermissions("mastertables|". __function__ );
			$data['viewfile']	=	"mastertables/inventory_items.php";
			$mainview ="gentlella_container.php";
			if ($id==""){
				if ($action=="add"){
					$mainview="mastertables/inventory_items_form.php";
				}
				elseif ($action=="insert"){
					if (isset($_POST['item_code'])){
						$insert['item_code']		=	$this->Mmm->sanitize($_POST['item_code']);
						$insert['description']		=	$this->Mmm->sanitize($_POST['description']);
						$insert['particular']		=	$this->Mmm->sanitize($_POST['particular']);
						$insert['unit']				=	$this->Mmm->sanitize($_POST['unit']);
						$insert['unit_price']		=	$this->Mmm->sanitize($_POST['unit_price']);
						$insert['reorder_level']	=	$this->Mmm->sanitize($_POST['reorder_level']);
						$insert['discontinued']		=	$this->Mmm->sanitize($_POST['discontinued']);
						$insert['sub_category']		=	$this->Mmm->sanitize($_POST['sub_category']);
						$insert['stat']				=	$this->Mmm->sanitize($_POST['stat']);
						$insert['qty']				=	$this->Mmm->sanitize($_POST['qty']);
						$insert['category']			=	$this->Mmm->sanitize($_POST['category']);
						$insert['location']			=	$this->Mmm->sanitize($_POST['location']);
						$insert['stock_location']	=	$this->Mmm->sanitize($_POST['stock_location']);
						$insert['account_type']		=	$this->Mmm->sanitize($_POST['account_type']);
						$insert['requested']		=	$this->Mmm->sanitize($_POST['requested']);
						$checkinsert				=	$this->Mmm->dbInsert("inventory_items", $insert, "Add new item");
						if($checkinsert) {
							$notif_msg	=	"The item (".$insert['item_code'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Item Added!");
						}
						else {$this->Abas->sysMsg("errmsg","Failed to add item! Please try again.");}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
				elseif($action=="json") {
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("inventory_items",$search,$limit,$offset,$order,$sort);
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				}
			}
			elseif (is_numeric($id)){
				if ($action=="edit"){
					$items				=	$this->db->query("SELECT * FROM inventory_items WHERE id=".$id);
					$items				=	(array)$items->row();
					$data['existing']	=	$items;
					$mainview="mastertables/inventory_items_form.php";
				}
				elseif ($action=="update"){
					if (isset($_POST['item_code'])){
						$update['item_code']		=	$this->Mmm->sanitize($_POST['item_code']);
						$update['description']		=	$this->Mmm->sanitize($_POST['description']);
						$update['particular']		=	$this->Mmm->sanitize($_POST['particular']);
						$update['unit']				=	$this->Mmm->sanitize($_POST['unit']);
						$update['unit_price']		=	$this->Mmm->sanitize($_POST['unit_price']);
						$update['reorder_level']	=	$this->Mmm->sanitize($_POST['reorder_level']);
						$update['discontinued']		=	$this->Mmm->sanitize($_POST['discontinued']);
						$update['sub_category']		=	$this->Mmm->sanitize($_POST['sub_category']);
						$update['stat']				=	$this->Mmm->sanitize($_POST['stat']);
						$update['qty']				=	$this->Mmm->sanitize($_POST['qty']);
						$update['category']			=	$this->Mmm->sanitize($_POST['category']);
						$update['location']			=	$this->Mmm->sanitize($_POST['location']);
						$update['stock_location']	=	$this->Mmm->sanitize($_POST['stock_location']);
						$update['account_type']		=	$this->Mmm->sanitize($_POST['account_type']);
						$update['requested']		=	$this->Mmm->sanitize($_POST['requested']);

						$checkupdate	=	$this->Mmm->dbUpdate("inventory_items", $update, $id, "Update Item");
						/*if($checkupdate) {
							$notif_msg	=	"The item (".$update['item_code'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("Master Table Record", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Item Updated!");
							}
							else {
							$this->Abas->sysMsg("errmsg","Failed to update item! Please try again.");
						}*/
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);

					}
				}
			}
			$this->load->view($mainview,$data);
		}
		public function ports($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['viewfile']	=	"mastertables/ports.php";
			$mainview			=	"gentlella_container.php";
			if($id=="") {
				if($action=="add") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$mainview	=	"mastertables/ports_form.php";
				}
				elseif($action=="insert") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$this->Mmm->debug($_POST);
					if(isset($_POST['name'])){
						$insert['name']					=	$this->Mmm->sanitize($_POST['name']);
						$insert['region']				=	$this->Mmm->sanitize($_POST['region']);
						$insert['address']				=	$this->Mmm->sanitize($_POST['address']);
						$insert['stat']					=	$this->Mmm->sanitize($_POST['stat']);
						$insert['code']					=	$this->Mmm->sanitize($_POST['code']);
						$insert['date_added']			=	date("Y-m-d H:i:s");
						$check							=	$this->db->query("SELECT * FROM ports WHERE name LIKE '".$insert['name']."'");
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That port already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkinsert					=	$this->Mmm->dbInsert("ports",$insert,"Add new port");
						if($checkinsert){
							$notif_msg	=	"A new port (".$insert['name'].") has been added by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New port", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","New port added!");
						}
						else { $this ->Abas->sysMsg("errmsg","Port not added! Please try again" ); }
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
				elseif($action=="json") {
					if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("ports",$search,$limit,$offset,$order,$sort);

						if($data!=false){
							foreach($data['rows'] as $ctr=>$port) {
								$data['rows'][$ctr]['stat']	=	"";
								if($port['stat'] == 1) {

									$data['rows'][$ctr]['stat']	=	"Active";
									}
								else if($port['stat'] == 0){
									$data['rows'][$ctr]['stat']	=	"Inactive";
									}

							}
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();

							}
						}
					}
				}

			elseif(is_numeric($id)) {
				if($action=="edit") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					$port				=	$this->db->query("SELECT * FROM ports WHERE id=".$id);
					$port		=	(array)$port->row();
					$data['existing']	=	$port;
					$mainview			=	"mastertables/ports_form.php";
				}
				elseif($action=="update") {
					$this->Abas->checkPermissions("mastertables|edit_". __function__ );
					if (isset($_POST['name'])){
						$update['name']					=	$this->Mmm->sanitize($_POST['name']);
						$update['region']				=	$this->Mmm->sanitize($_POST['region']);
						$update['address']				=	$this->Mmm->sanitize($_POST['address']);
						$update['stat']					=	$this->Mmm->sanitize($_POST['stat']);
						$update['code']					=	$this->Mmm->sanitize($_POST['code']);


						$check						=	$this->db->query("SELECT * FROM ports WHERE id<>".$id." name LIKE '".$insert['name']."'");
						if($check) {
							if($check->row()) {
								$this->Abas->sysMsg("warnmsg", "That port already exists!");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
						}
						$checkupdate =$this->Mmm->dbUpdate("ports",$update,$id, "Update Port");
						if($checkupdate) {
							$notif_msg	=	"The port (".$update['name'].") has been updated by ".$_SESSION['abas_login']['fullname'].".";
							$this->Abas->sysNotif("New port", $notif_msg, "everyone");
							$this->Abas->sysMsg("sucmsg","Port Updated!");
						}
						else {$this->Abas->sysMsg("errmsg","Failed to update port! Please try again.");}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->load->view($mainview,$data);
		}

		public function leave_credit_codes($action="", $id="") {
			$this->Abas->checkPermissions("mastertables|view_". __function__ );
			$data['action'] = $action;

			if($id != ""){
				$data['item'] = $this->Abas->getItemById('leave_credits',['id'=>$id]);
			}else{
				$post = array();
				foreach ($_POST as $key => $value) {
					$post = array_merge($post,[$key => $value]);
				}
			}

			/*if($action == 'insert' or $action == 'update'){
				$post = [

				];
			}*/

			if($action == 'view'){
				$this->Abas->checkPermissions("mastertables|edit_". __function__ );
				$this->load->view('mastertables/leave_credit_codes_form',$data);
			}elseif($action == 'add'){
				$this->load->view('mastertables/leave_credit_codes_form',$data);
			}elseif($action == 'insert'){
				$insert = $this->Abas->insertItem('leave_credits',$post,"Inserted by ".$_SESSION['abas_login']['username']);
				redirect(HTTP_PATH.'mastertables/leave_credit_codes');
				
				if ($insert) {
					$this->Abas->sysMsg("sucmsg","Successfully Added");
				}else{
					$this->Abas->sysMsg("errmsg","Not Inserted");	
				}
			}elseif($action == 'update'){
				$this->Mmm->debug($post);
			}else{
				$data['leave_credit_codes'] = $this->Abas->getItems('leave_credits');
				$data['viewfile'] = 'mastertables/leave_credit_codes.php';
				$mainview = 'gentlella_container.php';
				$this->load->view($mainview,$data);	
			}
			
		}
	}
?>
