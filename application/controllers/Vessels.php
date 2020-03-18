<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vessels extends CI_Controller {
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		$this->load->model("Mmm");
		$this->load->model("Operation_model");
		$this->load->model("Purchasing_model");
		$this->load->model("Accounting_model");
		$this->load->model("Inventory_model");
		if($_SESSION['abas_login']['role']=='Purchasing'){
			define("SIDEMENU","Purchasing");	
		}else{
			define("SIDEMENU","Operations");
		}
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
	}
	public function index()	{$data=array();
		$data['viewfile']	=	"vessels/vessels.php";
		$this->load->view('gentlella_container.php',$data);
	}
	public function view_all_vessels() {
		$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
		$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
		$order	=	isset($_GET['order'])?$_GET['order']:"";
		$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
		$search = 	"";
		$data	=	$this->Abas->createBStable("vessels",$search,$limit,$offset,$order,$sort);
		if($data!=false) {
			foreach($data['rows'] as $ctr=>$vessel) {
				if(isset($vessel['company'])) {
					$company							=	$this->Abas->getCompany($vessel['company']);
					$data['rows'][$ctr]['company_name']	=	$company->name;
				}
				if($vessel['created_by']) {
					$created_by							=	$this->Abas->getUser($vessel['created_by']);
					$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
				}
				if($vessel['modified_by']) {
					$modified_by						=	$this->Abas->getUser($vessel['created_by']);
					$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
				}
			}
			header('Content-Type: application/json');
			echo json_encode($data);
			exit();
		}
		else {
			die("An unknown error has occurred! <pre>Error ". __class__ .":". __function__ .":". __line__ ."</pre>");
		}
	}
	public function profile($action="", $id="") {$data=array();
		$viewfile			=	"vessel/vessel_view.php";
		$mainview			=	"gentlella_container.php";
		$data['companies']	=	$this->Abas->getCompanies();
		$data['positions']	=	$this->Abas->getPositions();
		$data['departments']=	$this->Abas->getDepartments();
		if($id=="") {
			$this->Abas->redirect(HTTP_PATH."vessels");
		}
		else {
			if(is_numeric($id)) {
				$vessel_record			=	$this->Abas->getVessel($id);
				if(!empty($vessel_record)) {
					$data['vessel_record']	=	$vessel_record;
					if($action=="edit") {
						$mainview	=	"basic_info_form.php";
					}
					elseif($action=="update") {
						if(!isset($_POST['name'], $_POST['company'])) {
							/* profile pic upload */
							$config['upload_path'] = WPATH .'assets'.DS.'images'.DS.'vessel_photos'.DS;
							$config['allowed_types'] = 'jpg';
							$this->load->library('upload',$config);
							if (!$this->upload->do_upload('picture')) {
								$error = array('error' => $this->upload->display_errors());
								$data['errmsg'] = $error['error'];
							}
							else {
								$upload_data=$this->upload->data();
								$titleimage	=	$upload_data['file_name'];
								$update['photo_path']	=	$titleimage;
							}
							/* profile pic upload */
							$update['modified_by']				=	$_SESSION['abas_login']['userid'];
							$update['modified_on']				=	date("Y-m-d H:i:s");

							$query	=	$this->Mmm->dbUpdate("vessels",$update, $id, "Update vessel record ".$id);
							if($query==true) { $this->Abas->sysMsg("sucmsg", "Vessel record updated!"); }
							else { $this->Abas->sysMsg("errmsg", "Vessel not updated!"); }
						}
						else {
							$this->Abas->sysMsg("warnmsg", "Please make sure the basic info is filled in!");
						}
						$this->Abas->redirect(HTTP_PATH."vessels");
					}
					elseif($action=="view") {
						//get vessel data
						$data['vessel'] = $this->Abas->getVessel($id);
						$data['company']= $this->Abas->getCompany($data['vessel']->company);
						//get vessel certificates
						$data['certificates'] = $this->Abas->getVesselCertificates($id);
						//get activity history
						//$data['activity'] = $this->Operation_model->getVesselActivity($id);
						//get fuel consumption history
						//$data['fuel_report'] = $this->Operation_model->getVesselFuelReport($id);
						//get voyages
						//$data['voyages'] = $this->Operation_model->getVoyageFromFuel($id);
						//get vessel purchases
						$data['purchase_orders'] = $this->Purchasing_model->getVesselPO($id);
						$data['crew'] = $this->getVesselCrew($id);
						$data['operational_expenses'] = $this->Accounting_model->getRFPForVessel($id);

						$mainview	=	"vessels/profile.php";
					}
				}
				else {
					$this->Abas->sysMsg("warnmsg", "Vessel record not found!");
					$this->Abas->redirect(HTTP_PATH."vessels");
				}
			}
			else {
				$this->Abas->sysMsg("warnmsg", "Invalid ID!");
				$this->Abas->redirect(HTTP_PATH."vessels");
			}
		}
		$data['viewfile']		=	$viewfile;
		$this->load->view($mainview,$data);
	}
	public function getEmployeePosition($pos_id){
		$sql = "SELECT * FROM positions WHERE id=".$pos_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = null;
		}
		return $result;
	}
	public function getEmployeeWorkHistory($emp_id,$value_changed){
		$sql = "SELECT * FROM hr_employment_history WHERE stat=1 AND employee_id=".$emp_id." AND value_changed='".$value_changed."' ORDER BY effectivity_date DESC LIMIT 1";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = null;
		}
		return $result;
	}
	public function getVesselCrew($vessel_id){
		$sql = "SELECT * FROM hr_employees WHERE vessel_id=".$vessel_id." AND stat=1";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr => $row){
				$position = $this->getEmployeePosition($row->position);
				$result[$ctr]->position = $position->name;
				$status = $this->getEmployeeWorkHistory($row->id,'Employment Status');
				if($status){
					$result[$ctr]->status = $status->to_val;
				}else{
					$result[$ctr]->status = "-";
				}
			}
		}else{
			$result = null;
		}
		return $result;
	}
		public function getVesselCerts() {
			$table	=	"vessel_certificates";
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$searchstring	=	isset($_GET['search'])?$_GET['search']:"";

			//$searchstring="";	
			$data	=	$this->Abas->createBSTable($table,$searchstring,$limit,$offset,$order,$sort);

			foreach($data['rows'] as $ctr=>$cert) {
				if(isset($cert['cert_date'])) {
					$data['rows'][$ctr]['cert_date']	=	date("j F Y", strtotime($cert['cert_date']));
				}
				if(isset($cert['expiration_date'])) {
					$data['rows'][$ctr]['expiration_date']	=	date("j F Y", strtotime($cert['expiration_date']));
				}
				if(isset($cert['vessel_id'])){
					$vessel			=	$this->Abas->getVessel($cert['vessel_id']);
					$data['rows'][$ctr]['vessel_id']	=	$vessel->name;
				}
				if(isset($cert['expiration_date'])){
					$date_now = date('Y-m-d');
					if($date_now<$cert['expiration_date']){
						$data['rows'][$ctr]['status']	=	"Active";
					}else{
						$data['rows'][$ctr]['status']	=	"Expired";
					}

				}
			}

			header('Content-Type: application/json');
			echo json_encode($data);
			
			exit();
			
		}
		public function getVesselPurchases() {
			
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$searchstring	=	isset($_GET['search'])?$_GET['search']:"";

			$inner_join = " INNER JOIN inventory_requests ON inventory_requests.id=inventory_po.request_id";
			$data	=	$this->Abas->getDataForBSTable('inventory_po',$searchstring,$limit,$offset,$order,$sort,'','',$inner_join);

			/*foreach($data['rows'] as $ctr=>$cert) {
				if(isset($cert['cert_date'])) {
					$data['rows'][$ctr]['cert_date']	=	date("j F Y", strtotime($cert['cert_date']));
				}
				if(isset($cert['expiration_date'])) {
					$data['rows'][$ctr]['expiration_date']	=	date("j F Y", strtotime($cert['expiration_date']));
				}
				if(isset($cert['vessel_id'])){
					$vessel			=	$this->Abas->getVessel($cert['vessel_id']);
					$data['rows'][$ctr]['vessel_id']	=	$vessel->name;
				}
				if(isset($cert['expiration_date'])){
					$date_now = date('Y-m-d');
					if($date_now<$cert['expiration_date']){
						$data['rows'][$ctr]['status']	=	"Active";
					}else{
						$data['rows'][$ctr]['status']	=	"Expired";
					}

				}
			}*/

			header('Content-Type: application/json');
			echo json_encode($data);
			
			exit();
			
		}
		public function vessel_certificates($action="", $id="") {
			if($this->Abas->checkPermissions("operations|view_vessel_certificates",true)){
				if(!isset($_SESSION['abas_login'])) { header("location:index"); }
				$table					=	"vessel_certificates";
				$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
				$tablefields			=	$tablefields->result();
				$data['table']			=	$table;
				$data['tablefields']	=	$tablefields;
				$viewfile				=	"vessels/vessel_cert_view.php";

				if($id=="") {
					if($action=="check") {
						$week_before	=	date("Y-m-d H:i:s", strtotime("+7 days"));
						$certs			=	$this->db->query("SELECT * FROM vessel_certificates WHERE tdate<='".$week_before."'");
						if($certs!=false) {
							$this->Mmm->debug($certs->result_array());
						}
					}
					elseif($action=="add") {
						$viewfile			=	"vessels/vessel_cert_form.php";
					}
					elseif($action=="insert") {
						foreach($tablefields as $tf) {
							if($tf->COLUMN_NAME=="id" || $tf->COLUMN_NAME=="stat" || $tf->COLUMN_NAME=="notification") {}else{
								$insert[$tf->COLUMN_NAME]	=	$this->Mmm->sanitize($_POST[$tf->COLUMN_NAME]);
							}

						}
						$res	=	$this->Mmm->dbInsert($table,$insert,"Encoder inserted new record in ".$table);

						if($res==true) {
							//$_SESSION['msg']	=	"Record Inserted!";

							$this->Abas->sysMsg("sucmsg", "Successfully added new Vessel Certificate.");

							$this->Abas->sysNotif("New Vessel Certificate", $_SESSION['abas_login']['fullname']." has successfully added new Vessel Certificate.","Operations","info");
						}
						else {
							//$_SESSION['errmsg']	=	"Record Not Inserted!";
							$this->Abas->sysMsg("errmsg", "Vessel Certificate not added, please try again!");
						}

						$this->Abas->redirect(HTTP_PATH."vessels/vessel_certificates");
					}
				}
				else {
					if($action=="edit") {
						$data['recid']		=	$id;
						$viewfile			=	"vessels/vessel_cert_form.php";
					}
					elseif($action=="update") {
						foreach($tablefields as $tf) {
							if($tf->COLUMN_NAME=="id" || $tf->COLUMN_NAME=="stat" || $tf->COLUMN_NAME=="notification") {}else{
								if($tf->COLUMN_NAME=="password") {
									$update[$tf->COLUMN_NAME]	=	md5($_POST[$tf->COLUMN_NAME]);
								}
								else {
									$update[$tf->COLUMN_NAME]	=	$this->Mmm->sanitize($_POST[$tf->COLUMN_NAME]);
								}
							}
						}
						// $update['stat']	=	1;
						$res	=	$this->Mmm->dbUpdate($table,$update,$id,"Encoder updated record in ".$table." with id .".$id);
						if($res==true) {
							//$_SESSION['msg']	=	"Record Updated!";

							$this->Abas->sysMsg("sucmsg", "Successfully updated Vessel Certificate.");

							$this->Abas->sysNotif("Edit Vessel Certificate", $_SESSION['abas_login']['fullname']." has successfully updated a Vessel Certificate.","Operations","info");
						}
						else {
							//$_SESSION['errmsg']	=	"Record Not Updated!";
							$this->Abas->sysMsg("errmsg", "Vessel Certificate not updated, please try again!");
						}

						$this->Abas->redirect(HTTP_PATH."vessels/vessel_certificates");
					}
				}

				//start email notif
				$nextweek	=	date("Y-m-d", strtotime("+2 months"));
				$today		=	date("Y-m-d");
				$expiring	=	$this->db->query("SELECT * FROM vessel_certificates WHERE expiration_date BETWEEN '".$today."' AND '".$nextweek."' AND notification=0");
				if($expiring!=false) {
					if($expiring->row()) {
						$expiring	=	$expiring->result();
						foreach($expiring as $e) {
							$update['notification']	=	1;
							$this->Mmm->dbUpdate("vessel_certificates", $update, $e->id, "Notify for vessel certificate expiry");

							$vessel	=	$this->db->query("SELECT * FROM vessels WHERE id=".$e->vessel_id);
							$vessel	=	$vessel->row();

							$this->Mmm->sendEmail("marketing@avegabros.com", "Vessel Certificate Expiry", "The ".$e->type." of ".$vessel->name." is expiring on ".date("j F Y", strtotime($nextweek))."!");

							$this->Mmm->sendEmail("operations@avegabros.com", "Vessel Certificate Expiry", "The ".$e->type." of ".$vessel->name." is expiring on ".date("j F Y", strtotime($nextweek))."!");

							$this->Mmm->sendEmail("it@avegabros.com", "Vessel Certificate Expiry", "The ".$e->type." of ".$vessel->name." is expiring on ".date("j F Y", strtotime($nextweek))."!");
						}
					}
				}
				// end email notif

				if($action=="add" || $action=="edit"){
					$this->load->view($viewfile,$data);
				}else{
					$data['viewfile']		=	$viewfile;
					$this->load->view("gentlella_container.php",$data);
				}
				

			}
		}
}
?>