<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Corporate_Services extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			$this->load->model("Abas");
			$this->load->model("Purchasing_model");
			$this->load->model("Inventory_model");
			$this->load->model("Accounting_model");
			$this->load->model("Corporate_Services_model");
			$this->load->model("Mmm");
			$this->output->enable_profiler(FALSE);
			define("SIDEMENU","Corporate Services");
		}

		public function index()
		{
			$data['viewfile'] =	"corporate_services/dashboard.php";
			$this->load->view('gentlella_container.php',$data);
		}

		public function purchase_requests($action="", $id="") {
			$data=array();
			switch($action) {
				case "load":
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data = $this->Abas->getDataForBSTable("inventory_requests",$search,$limit,$offset,$order,$sort,"added_by=",$id);
						foreach($data['rows'] as $ctr=>$request){
							if($request['added_by']) {
								$created_by							=	$this->Abas->getUser($request['added_by']);
								$data['rows'][$ctr]['requested_by']	=	$created_by['full_name'];
							}
							if($request['added_on']) {
								$data['rows'][$ctr]['requested_on']	=	date("j F Y h:i A", strtotime($request['added_on']));
							}
							if($request['approved_by']) {
								$approved_by						=	$this->Abas->getUser($request['approved_by']);
								$data['rows'][$ctr]['approved_by']	=	$approved_by['full_name'];
							}
							if($request['approved_on']) {
								$data['rows'][$ctr]['approved_on']	=	date("j F Y h:i A", strtotime($request['approved_on']));
							}
							if($request['vessel_id']){
								$vessel_office = $this->Abas->getVessel($request['vessel_id']);
								$data['rows'][$ctr]['vessel_office'] = $vessel_office->name;

								$company = $this->Abas->getCompany($vessel_office->company);
								$data['rows'][$ctr]['company'] = $company->name;
							}
							if($request['department_id']){
								$department = $this->Abas->getDepartment($request['department_id']);
								$data['rows'][$ctr]['department'] = $department->name;
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				break;
				case "listview":
					$data['viewfile']	=	"corporate_services/purchase_requests/listview.php";
					$this->load->view("gentlella_container.php",$data);
				break;
				case "add":
					$users					=	$this->db->query("SELECT * FROM users WHERE stat=1 AND role='Purchasing' ORDER BY last_name ASC");
					$data['users']			=	$users->result_array();
					$data['vessels']		=	$this->Abas->getVessels();
					$data['trucks']			=	$this->Abas->getTrucks();
					$data['departments']	=	$this->Abas->getDepartments();
					$data['categories']		=	$this->Abas->getItemCategory();
					$data['approvers']		=	$this->Purchasing_model->getRequestApprovers();
					$this->load->view('corporate_services/purchase_requests/form.php',$data);
				break;
				case "insert":
					$summary = array();
					$summary['tdate']			=	$this->Mmm->sanitize($_POST['date_needed']);;
					$summary['requisitioner']	=	$this->Mmm->sanitize($_POST['requisitioner']);
					$summary['vessel_id']		=	$this->Mmm->sanitize($_POST['vessel']);
					$summary['company_id']		=	$this->Mmm->sanitize($_POST['company_id']);
					$summary['truck_id']		=	$this->Mmm->sanitize($_POST['truck']);
					$summary['reference_number']	=	$this->Mmm->sanitize($_POST['reference_no']);
					$summary['control_number']	=	$this->Abas->getNextSerialNumber("inventory_requests", $_POST['company_id']);
					$summary['department_id']	=	$this->Mmm->sanitize($_POST['department']);
					$summary['priority']		=	$this->Mmm->sanitize($_POST['priority']);
					$summary['remark']			=	$this->Mmm->sanitize($_POST['remark'])."\n".$_SESSION['abas_login']['fullname']."\n".date("H:i j F Y");
					$summary['added_by']		=	$_SESSION['abas_login']['userid'];
					$summary['added_on']		=	date('Y-m-d H:i:s');
					$summary['approved_by']		=	$this->Mmm->sanitize($_POST['approved_by']);
					$summary['stat']			=	1;
					$summary['status']			=	"For request approval";
					$vessel						=	$this->Abas->getVessel($_POST['vessel']);
					$check	=	$this->Mmm->dbInsert("inventory_requests", $summary, "New requisition for ".$vessel->name);
					if($check==true) {
						if(!empty($_POST['itemvalue'])) {
							$request	=	$this->db->query("SELECT max(id) AS id FROM inventory_requests");
							$request	=	(array)$request->row();
							$new_id		=	$request['id'];
							foreach($_POST['itemvalue'] as $ctr=>$itemname) {
								if($_POST['itemvalue'][$ctr]!="") {
									$detail[]	=	array(
									"request_id"	=>	$new_id,
									"item_id"		=>	$this->Mmm->sanitize($_POST['itemvalue'][$ctr]),
									"unit"		=>	$this->Mmm->sanitize($_POST['itemunit'][$ctr]),
										"packaging"		=>	$this->Mmm->sanitize($_POST['packaging'][$ctr]),
									"quantity"		=>	$this->Mmm->sanitize($_POST['quantity'][$ctr]),
									"assigned_to"	=>	$this->Mmm->sanitize($_POST['assign_to'][$ctr]),
									"supplier_id"	=>	0,
									"stat"			=>	1,
									"added_by"		=>	$_SESSION['abas_login']['userid'],
									"added_on"		=>	date("Y-m-d H:i:s"),
									"status"		=>	"For Request Approval",
									"remark"		=>	$_POST['item_remark'][$ctr]
									);
								}
							}
							if(!empty($detail)) {
								$check_items	=	$this->Mmm->multiInsert("inventory_request_details", $detail, "Insert requisition items for request ".$request['id']." for ".$vessel->name);
								if($check_items==true) {
									$notif_msg	=	"A new request for ".$vessel->name." has been added by ".$_SESSION['abas_login']['fullname'].".";
									$this->Abas->sysMsg("sucmsg", "Request encoded and is now awaiting approval.");
								}else{
									$this->Abas->sysMsg("errmsg", "Requested items not added! Please contact the administrator.");
								}
							}
						}else{
							$this->Abas->sysMsg("errmsg", "No request items found!");
						}
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				break;
				case "view":
					$data['request'] = $this->Purchasing_model->getRequest($id);
					$data['viewfile']	=	"corporate_services/purchase_requests/view.php";
					$this->load->view("gentlella_container.php",$data);
				break;
				case "print":
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['request'] = $this->Purchasing_model->getRequest($id);
					$this->load->view('corporate_services/purchase_requests/print.php',$data);
				break;
			}
		}

		public function request_for_payment($action="", $id="") {
			$data=array();
			switch($action) {
				case "load":
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data = $this->Abas->getDataForBSTable("ac_request_payment",$search,$limit,$offset,$order,$sort,"created_by=",$id);
						foreach($data['rows'] as $ctr=>$request){
							if(isset($request['created_by'])) {
								$created_by							=	$this->Abas->getUser($request['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if(isset($request['created_on'])) {
								$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($request['created_on']));
							}
							if(isset($request['verified_by'])) {
								$verified_by						=	$this->Abas->getUser($request['verified_by']);
								$data['rows'][$ctr]['verified_by']	=	$verified_by['full_name'];
							}
							if(isset($request['verified_on'])) {
								$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i A", strtotime($request['verified_on']));
							}
							if(isset($request['approved_by'])) {
								$approved_by						=	$this->Abas->getUser($request['approved_by']);
								$data['rows'][$ctr]['approved_by']	=	$approved_by['full_name'];
							}
							if(isset($request['approved_on'])) {
								$data['rows'][$ctr]['approved_on']	=	date("j F Y h:i A", strtotime($request['approved_on']));
							}
								if($request['payee_type']=='Employee') {
								$employee	=	$this->Abas->getEmployee($request['payee']);
								$data['rows'][$ctr]['payee_name']	= $employee['full_name'];
							}elseif($request['payee_type']=='Supplier'){
								$supplier =	$this->Abas->getSupplier($request['payee']);
								$data['rows'][$ctr]['payee_name']	= $supplier['name'];
							}
							if($request['payee']==''){
								$data['rows'][$ctr]['payee_name']	= $request['payee_others'];
							}
							if(isset($request['amount'])) {
								$data['rows'][$ctr]['amount']	=	number_format($request['amount'],2,".",",");
							}
							if(isset($request['company_id'])){
								$company = $this->Abas->getCompany($request['company_id']);
								$data['rows'][$ctr]['company'] = $company->name;
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				break;
				case "listview":
					$data['viewfile']	=	"corporate_services/request_for_payment/listview.php";
					$this->load->view("gentlella_container.php",$data);
				break;
				case "add":
					$data['vessels']		=	$this->Abas->getVessels();
					$data['companies']		=	$this->Abas->getCompanies();
					$this->load->view('corporate_services/request_for_payment/form.php',$data);
				break;
				case "insert":
					$summary = array();
					$summary['request_date']	=	$this->Mmm->sanitize($_POST['request_date']);
					$summary['company_id']		=	$this->Mmm->sanitize($_POST['company']);
					$control_number = $this->Abas->getNextSerialNumber("ac_request_payment", $summary['company_id']);
					$summary['control_number']	=	$control_number;
					$summary['payee_type']		=	$this->Mmm->sanitize($_POST['payee_type']);
					$summary['payee']			=	$this->Mmm->sanitize($_POST['payee_id']);
					if($summary['payee_type']=='Supplier'){
						$summary['payee_others']			=	$this->Mmm->sanitize($_POST['payee_supplier']);
					}else{
						$summary['payee_others']			=	$this->Mmm->sanitize($_POST['payee_employee']);
					}
					$summary['reference_table']	=	$this->Mmm->sanitize($_POST['reference_document']);
					$summary['reference_id']	=	$this->Mmm->sanitize($_POST['reference_id']);
					$summary['purpose']			=	$this->Mmm->sanitize($_POST['purpose']);
					$requested_by = $this->Abas->getUser($_SESSION['abas_login']['userid']);
					$summary['requested_by']	=	$requested_by['full_name'];
					if($summary['reference_table']=='inventory_po'){
						$summary['remark']		=	$this->Mmm->sanitize($_POST['reference_id_purchase_order']);
					}elseif($summary['reference_table']=='inventory_job_orders'){
						$summary['remark']		=	$this->Mmm->sanitize($_POST['reference_id_job_order']);
					}elseif($summary['reference_table']=='hr_payroll'){
						$summary['remark']		=	$this->Mmm->sanitize($_POST['reference_id_payroll']);
					}elseif($summary['reference_table']=='service_contracts'){
						$summary['remark']		=	$this->Mmm->sanitize($_POST['reference_id_contract']);
					}
					$summary['added_by']		=	$_SESSION['abas_login']['userid'];
					$summary['date_added']		=	date('Y-m-d H:i:s');
					$summary['created_by']		=	$_SESSION['abas_login']['userid'];
					$summary['created_on']		=	date('Y-m-d H:i:s');
					$summary['status']			=	"For Verification";
					$summary['stat']			=	1;

					$payee					=	$summary['payee_others'];
					$check	=	$this->Mmm->dbInsert("ac_request_payment", $summary, "New request for payment added for ".$payee);
					if($check) {
						if(!empty($_POST['particulars'])) {
							$request_for_payment_id = $this->Abas->getLastIDByTable('ac_request_payment');
							$total_amount =0;
							foreach($_POST['particulars'] as $ctr=>$row) {
								$details[]	=	array(
								"request_payment_id"	=>	$request_for_payment_id,
								"particulars"			=>	$this->Mmm->sanitize($_POST['particulars'][$ctr]),
								"amount"				=>	$this->Mmm->sanitize($_POST['amount'][$ctr]),
								"charge_to"				=>	$this->Mmm->sanitize($_POST['charge_to'][$ctr]),
								"stat"					=>	1
								);
								$total_amount = $total_amount + $this->Mmm->sanitize($_POST['amount'][$ctr]);
							}

							$target_dir = WPATH.'assets/uploads/accounting/request_for_payments/attachments/';
							if($_POST['file_name']){
								foreach($_POST['file_name'] as $ctr=>$row) {
									$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
									$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);
									if(end($old_filename)!=""){
										$details_attach[]	=	array(
										"request_payment_id"	=>	$request_for_payment_id,
										"file_name"			=>	$this->Mmm->sanitize($_POST['file_name'][$ctr]),
										"file_path"			=>	$new_filename,
										"stat"					=>	1
										);
										$target_file = $target_dir . $new_filename;
										$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
									}
								}
							}

							if(!empty($details)) {
								$check_details	=	$this->Mmm->multiInsert("ac_request_payment_details", $details, "Added request for payments details with RFP Transaction Code No. ".$request_for_payment_id);

								if($uploaded){
									$check_details_attach	=	$this->Mmm->multiInsert("ac_request_payment_attachments", $details_attach, "Added attachment(s) for request for payments with RFP Transaction Code No. ".$request_for_payment_id);
								}

								$update['amount'] = $total_amount;
								$details_amount = $this->Mmm->dbUpdate("ac_request_payment", $update,$request_for_payment_id,'Update Amount of RFP Transaction Code No. '.$request_for_payment_id);

								if($check_details && $details_amount) {
									$notif_msg	=	"New Request for Payment has been sucessfully added by ".$_SESSION['abas_login']['fullname']." for payment to ".$payee;
									$this->Abas->sysMsg("sucmsg", $notif_msg);
								}else{
									$this->Abas->sysMsg("errmsg", "An error ocurred while saving the record. Please contact your administrator.");
								}
							}
						}
					}else{
						$this->Abas->sysMsg("errmsg", "An error ocurred while saving the record. Please contact your administrator.");
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				break;
				case "view":
					$data['request'] = $this->Accounting_model->getRequestPayment($id);
					$data['request_details'] = $this->Accounting_model->getRequestPaymentDetails($id);
					$data['request_attachments'] = $this->Accounting_model->getRequestPaymentAttachments($id);
					$data['company'] = $this->Abas->getCompany($data['request'][0]['company_id']);
					if($data['request'][0]['payee_type']=='Employee') {
						$employee	=	$this->Abas->getEmployee($data['request'][0]['payee']);
						$data['payee']		= $employee['full_name'];
					}elseif($data['request'][0]['payee_type']=='Supplier'){
						$supplier =	$this->Abas->getSupplier($data['request'][0]['payee']);
						$data['payee']		= $supplier['name'];
					}
					if($data['request'][0]['payee']==''){
						$data['payee']		= $data['request'][0]['payee_others'];
					}
					if($data['request'][0]['reference_table']=='inventory_po'){
						$data['request'][0]['reference_document']		=	"Purchase Order";
					}elseif($data['request'][0]['reference_table']=='inventory_job_orders'){
						$data['request'][0]['reference_document']		=	"Job Order";
					}elseif($data['request'][0]['reference_table']=='hr_payroll'){
						$data['request'][0]['reference_document']		=	"Payroll";
					}elseif($data['request'][0]['reference_table']=='service_contracts'){
						$data['request'][0]['reference_document']		=	"Contract";
					}else{
						$data['request'][0]['reference_document']		= "None";
					}
					$data['request'][0]['created_by_name'] = $this->Abas->getUser($data['request'][0]['created_by']);
					$data['request'][0]['verified_by_name'] = $this->Abas->getUser($data['request'][0]['verified_by']);
					$data['request'][0]['approved_by_name'] = $this->Abas->getUser($data['request'][0]['approved_by']);
					$data['viewfile']	=	"corporate_services/request_for_payment/view.php";
					$this->load->view("gentlella_container.php",$data);
				break;
				case "edit":
					$data['request'] = $this->Accounting_model->getRequestPayment($id);
					$data['request_details'] = $this->Accounting_model->getRequestPaymentDetails($id);
					$data['request_attachments'] = $this->Accounting_model->getRequestPaymentAttachments($id);
					$data['vessels']		=	$this->Abas->getVessels();
					$data['companies']		=	$this->Abas->getCompanies();
					$this->load->view('corporate_services/request_for_payment/form.php',$data);
				break;
				case "update":
					$summary = array();
					$summary['purpose']			=	$this->Mmm->sanitize($_POST['purpose']);
					$summary['payee_type']		=	$this->Mmm->sanitize($_POST['payee_type']);
					$summary['payee']			=	$this->Mmm->sanitize($_POST['payee_id']);
					$summary['added_by']		=	$_SESSION['abas_login']['userid'];
					$summary['date_added']		=	date('Y-m-d H:i:s');
					$summary['created_by']		=	$_SESSION['abas_login']['userid'];
					$summary['created_on']		=	date('Y-m-d H:i:s');
					$summary['status']			=	"For Verification";
					$summary['stat']			=	1;
					$check	=	$this->Mmm->dbUpdate("ac_request_payment", $summary,$id, "Edited request for payment with TSCode No.".$id);
					if($check) {
						if(!empty($_POST['particulars'])) {
							$this->db->query('DELETE FROM ac_request_payment_details WHERE request_payment_id='.$id);
							$request_for_payment_id = $id;
							$total_amount =0;
							foreach($_POST['particulars'] as $ctr=>$row) {
								$details[]	=	array(
								"request_payment_id"	=>	$request_for_payment_id,
								"particulars"			=>	$this->Mmm->sanitize($_POST['particulars'][$ctr]),
								"amount"				=>	$this->Mmm->sanitize($_POST['amount'][$ctr]),
								"charge_to"				=>	$this->Mmm->sanitize($_POST['charge_to'][$ctr]),
								"stat"					=>	1
								);
								$total_amount = $total_amount + $this->Mmm->sanitize($_POST['amount'][$ctr]);
							}

							$target_dir = WPATH.'assets/uploads/accounting/request_for_payments/attachments/';
							if($_POST['file_name']){
								$this->db->query('DELETE FROM ac_request_payment_attachments WHERE request_payment_id='.$id);
								$details_attach[]	=	array();
								$count_me = 0;
								if(isset($_POST['attach_file_name'])){
									foreach($_POST['attach_file_name'] as $ctr=>$row) {
										$details_attach[$ctr]["request_payment_id"]	=	$id;
										$details_attach[$ctr]["file_name"]		=	$this->Mmm->sanitize($_POST['file_name'][$ctr]);
										$details_attach[$ctr]["file_path"]			=	$this->Mmm->sanitize($_POST['attach_file_name'][$ctr]);
										$details_attach[$ctr]["stat"]			=	1;
										$count_me++;
									}
										$this->Mmm->multiInsert("ac_request_payment_attachments", $details_attach, "Updated attachment(s) for request for payments with RFP Transaction Code No. ".$request_for_payment_id);
								}

								$details_attach2[]	=	array();
								if(isset($_FILES["attach_file"]["name"])){
									foreach($_FILES["attach_file"]["name"] as $ctr=>$row) {
										$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
										$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);
										if(end($old_filename)!=""){
											$details_attach2[$ctr]["request_payment_id"]	=	$request_for_payment_id;
											$details_attach2[$ctr]["file_name"]			=	$this->Mmm->sanitize($_POST['file_name'][$count_me]);
											$details_attach2[$ctr]["file_path"]			=	$new_filename;
												$details_attach2[$ctr]["stat"]			=	1;
											$target_file = $target_dir . $new_filename;
											$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
										}
									}
										$this->Mmm->multiInsert("ac_request_payment_attachments", $details_attach2, "Updated attachment(s) for request for payments with RFP Transaction Code No. ".$request_for_payment_id);
								}

							}

							if(!empty($details)) {
								$check_details	=	$this->Mmm->multiInsert("ac_request_payment_details", $details, "Updated request for payments details with RFP Transaction Code No. ".$request_for_payment_id);

								
								$update['amount'] = $total_amount;
								$details_amount = $this->Mmm->dbUpdate("ac_request_payment", $update,$request_for_payment_id,'Update Amount of RFP Transaction Code No. '.$request_for_payment_id);

								if($check_details && $details_amount) {
									$notif_msg	=	"Request for Payment has been sucessfully edited by ".$_SESSION['abas_login']['fullname'];
									$this->Abas->sysMsg("sucmsg", $notif_msg);
								}else{
									$this->Abas->sysMsg("errmsg", "An error ocurred while saving the record. Please contact your administrator.");
								}
							}
						}
					}else{
						$this->Abas->sysMsg("errmsg", "An error ocurred while saving the record. Please contact your administrator.");
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				break;
				case "print":
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['request'] = $this->Accounting_model->getRequestPayment($id);
					$data['request_details'] = $this->Accounting_model->getRequestPaymentDetails($id);
					$data['company'] = $this->Abas->getCompany($data['request'][0]['company_id']);
					if($data['request'][0]['payee_type']=='Employee') {
						$employee	=	$this->Abas->getEmployee($data['request'][0]['payee']);
						$data['payee']		= $employee['full_name'];
					}elseif($data['request'][0]['payee_type']=='Supplier'){
						$supplier =	$this->Abas->getSupplier($data['request'][0]['payee']);
						$data['payee']		= $supplier['name'];
					}
					if($data['request'][0]['payee']==''){
						$data['payee']		= $data['request'][0]['payee_others'];
					}
					if($data['request'][0]['reference_table']=='inventory_po'){
						$data['request'][0]['reference_document']		=	"Purchase Order";
					}elseif($data['request'][0]['reference_table']=='inventory_job_orders'){
						$data['request'][0]['reference_document']		=	"Job Order";
					}elseif($data['request'][0]['reference_table']=='hr_payroll'){
						$data['request'][0]['reference_document']		=	"Payroll";
					}elseif($data['request'][0]['reference_table']=='service_contracts'){
						$data['request'][0]['reference_document']		=	"Contract";
					}else{
						$data['request'][0]['reference_document']		= "None";
					}
					$data['request'][0]['created_by_name'] = $this->Abas->getUser($data['request'][0]['created_by']);
					$data['request'][0]['verified_by_name'] = $this->Abas->getUser($data['request'][0]['verified_by']);
					$data['request'][0]['approved_by_name'] = $this->Abas->getUser($data['request'][0]['approved_by']);
					$this->load->view('corporate_services/request_for_payment/print.php',$data);
				break;

				case "cancel":
					$update['status'] =  'Cancelled';
					$update['remark'] =  $_POST['comments'];
					$this->Mmm->dbUpdate('ac_request_payment',$update,$id,"Cancelled Request for Payment with transaction code no.".$id. "by ".$_SESSION['abas_login']['fullname'].".");
					$notif_msg	=	"Request for Payment with transaction code no.".$id." has been sucessfully cancelled by ".$_SESSION['abas_login']['fullname'];
					$this->Abas->sysMsg("sucmsg", $notif_msg);
					$this->Abas->sysNotif("Request For Payment", $notif_msg, "Accounting");
				break;
			}
		}
	//------------------------------------------------------------------------------------------------------	
		public function sunday_count($from,$to) {
			$start = new DateTime($from);
			$end = new DateTime($to);
			
			$days = $start->diff($end, true)->days;
			$sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

			return $sundays;
		}

		public function leave($action='',$id='') {
			$data['action'] = $action;
			$sid = $_SESSION['abas_login']['userid'];
			$employee_id = $this->Abas->getEmpId($sid);
			$curr_year = date('Y');

			if($action == 'insert' or $action == 'update'){
				$diff = strtotime($_POST['date_to']) - strtotime($_POST['date_from']);
				$days = ($diff / (60 * 60 * 24)) + 1;
				$sundays = $this->sunday_count($_POST['date_from'],$_POST['date_to']);
				$days_no_sunday = $days - $sundays;
				
				$array = array(
					'employee_id' => $employee_id,
					'type' => $_POST['type'],
					'date_from' => $_POST['date_from'],
					'date_to' => $_POST['date_to'],
					'reason' => $_POST['reason'],
					'address' => $_POST['address'],
					'contact_number' => $_POST['contact_no'],
					'status' => 'FOR APPROVAL',
					'days' => $days_no_sunday,
					'approver_id' => $_POST['approver']
				);
			}

			if($action == 'add' or $action == 'edit' or $action == 'view'){
				$approver = $this->Abas->getItems('employee_approver',array('document'=>'leave'));
				foreach ($approver as $key => $val) {
					$name = $this->Abas->getEmpName($val->approver_id);
					$approver_array[$key] = array(
						'id' => $val->id,
						'name' => $name,
						'emp_id' => $val->approver_id
					);
				}
				$data['approver'] = $approver_array;
				$data['leave_types'] = $this->Abas->getItems('leave_types');
			}

			if(isset($_GET['date_from'])){
				$data['from'] = $_GET['date_from'];
				$data['to'] = $_GET['date_to'];

				$year_filter = "date_from >= '".$_GET['date_from']."' AND date_from <= '".$_GET['date_to']."'";
				$string = '';

				if(isset($_GET['for_approval'])){
					$string .= "or status='FOR APPROVAL' ";
				}
				if(isset($_GET['for_processing'])){
					$string .= "or status='FOR PROCESSING' ";
				}
				if(isset($_GET['processed'])){
					$string .= "or status='PROCESSED' ";
				}
				if(isset($_GET['rejected'])){
					$string .= "or status='REJECTED' ";
				}
				if(isset($_GET['cancelled'])){
					$string .= "or status='CANCELLED' ";
				}

				$pattern1 = '/ or /';
				$string2 = preg_replace('/ or /', 'dummy_string', $string);
				$string3 = preg_replace('/or/', '', $string2);
				$status_filter = preg_replace('/dummy_string/', ' or ', $string3);

			}else{
				$year = date('Y');
				$data['from'] = $year.'-01-01';
				$data['to'] = $year.'-12-31';
			}

			switch ($action) {
				case 'add':
					$data['submit'] = HTTP_PATH.'Corporate_Services/leave/insert';
					$data['employee'] = $this->Abas->getItemById('hr_employees',array('user_id'=>$employee_id));
					$this->load->view('corporate_services/applications/leave_form',$data);
				break;

				case 'insert':
					$insert = array('date_filed' => date('Y-m-d'));
					
					$insert = array_merge($insert,$array);
					if(isset($_POST['emp_auto_complete'])){
						unset($insert['employee_id']);
						$emp_id = strtok($_POST['emp_auto_complete'],')');
						$explode = explode("(", $emp_id, 2);
						$employee_id = $explode[1];
						$insert = array_merge($insert,array('employee_id'=>$employee_id));
						$this->Abas->updateItem('hr_employees',array('user_id'=>$sid),array('id'=>$employee_id),"updated user_id");
					}

					$bal = $this->Corporate_Services_model->getLeaveBal($employee_id);
					
					if($bal >= $days+1){
						$insert = array_merge($insert,array('is_with_pay'=>1));
						$this->Abas->insertItem('employee_leave',$insert);
						$this->Abas->sysMsg("sucmsg","Successfully Filed Leave!");
					}
					else{
						$insert = array_merge($insert,array('is_with_pay'=>0));
						$this->Abas->insertItem('employee_leave',$insert);
						$this->Abas->sysMsg("warnmsg","Insuficient Leave Credits. Filed as LWOP.");	
					}
					redirect(HTTP_PATH.'Corporate_Services/leave');
				break;

				case 'edit':
					$data['item'] = $this->Abas->getItemById('employee_leave',array('id'=>$id));
					$data['submit'] = HTTP_PATH.'Corporate_Services/leave/update/'.$id;
					$this->load->view('corporate_services/applications/leave_form',$data);
				break;

				case 'update':
					$array = array_merge($array,array('is_with_pay'=>$_POST['with_pay']));
					$this->Abas->updateItem('employee_leave',$array,array('id'=>$id));
					$this->Abas->sysMsg("sucmsg","Successfully Edited Leave!");
					redirect(HTTP_PATH.'Corporate_Services/leave');
				break;

				case 'delete':
					$this->Abas->delItem('employee_leave',array('id'=>$id));
					$this->Abas->sysMsg("sucmsg","Successfully Deleted Leave!");
					redirect(HTTP_PATH.'Corporate_Services/leave');
				break;

				case 'cancel':
					$cancel = array('status'=>'CANCELLED');
					$this->Abas->updateItem('employee_leave',$cancel,array('id'=>$id),"cancelled leave application");
					$this->Abas->sysMsg("sucmsg","Successfully Cancelled Leave!");
					redirect(HTTP_PATH.'Corporate_Services/leave');
				break;

				case 'view':
					$data['item'] = $this->Abas->getItemById('employee_leave',array('id'=>$id));
					$this->load->view('corporate_services/applications/leave_form',$data);
				break;

				case 'filter':
					$from = $_POST['date_from'];
					$to = $_POST['date_to'];
					$cbProcessed = isset($_POST['cbProcessed']) ? true : false;
					$cbForApproval = isset($_POST['cbForApproval']) ? true : false;
					$cbForProcessing = isset($_POST['cbForProcessing']) ? true : false;
					$cbRejected = isset($_POST['cbRejected']) ? true : false;
					$cbCancelled = isset($_POST['cbCancelled']) ? true : false;

					$url = HTTP_PATH.'Corporate_Services/leave?date_from='.$from.'&date_to='.$to;
					if($cbProcessed){
						$url = $url.'&processed=true';
					}
					if($cbForApproval){
						$url = $url.'&for_approval=true';
					}
					if($cbForProcessing){
						$url = $url.'&for_processing=true';
					}
					if($cbRejected){
						$url = $url.'&rejected=true';
					}
					if($cbCancelled){
						$url = $url.'&cancelled=true';
					}

					redirect($url);
				break;
				
				default:
					$data['balance'] = $this->Corporate_Services_model->getLeaveBal($employee_id);

					if(isset($_GET['date_from'])){
						if($status_filter != ''){
							$data['leave'] = $this->Corporate_Services_model->getEmpLeaveFiltered($employee_id,$year_filter,$status_filter);	
						}else{
							$data['leave'] = array();
						}
					}else{
						$data['leave'] = $this->Corporate_Services_model->getEmpLeave($employee_id);
					}
					
					$data['viewfile'] =	"corporate_services/applications/leave.php";
					$this->load->view('gentlella_container.php',$data);
				break;
			}
		}

		public function overtime($action='',$id='') {
			$data['action'] = $action;
			$sid = $_SESSION['abas_login']['userid'];
			$employee_id = $this->Abas->getEmpId($sid);

			if($action == 'add' or $action == 'edit' or $action == 'view'){
				$approver = $this->Abas->getItems('employee_approver',array('document'=>'leave'));
				foreach ($approver as $key => $val) {
					$name = $this->Abas->getEmpName($val->approver_id);
					$approver_array[$key] = array(
						'id' => $val->id,
						'name' => $name,
						'emp_id' => $val->approver_id
					);
				}
				$data['approver'] = $approver_array;
				$data['leave_types'] = $this->Abas->getItems('leave_types');
			}

			if($action == 'update' or $action == 'insert'){
				$time_from = new DateTime($_POST['time_from']);
				$time_to = new DateTime($_POST['time_to']);
				$diff = $time_from->diff($time_to);

				$overtime = array(
					'employee_id' => $employee_id,
					'date_filed' => date('Y-m-d H:i:s'),
					'render_date' => $_POST['render_date'],
					'time_from' => $_POST['time_from'],
					'time_to' => $_POST['time_to'],
					'reason' => $_POST['reason'],
					'total_hours' => $diff->format("%H:%I:%S"),
					'approver_id' => $_POST['approver']
				);
			}

			if(isset($_GET['date_from'])){
				$data['from'] = $_GET['date_from'];
				$data['to'] = $_GET['date_to'];

				$year_filter = "render_date >= '".$_GET['date_from']."' AND render_date <= '".$_GET['date_to']."'";
				$string = '';

				if(isset($_GET['for_approval'])){
					$string .= "or status='FOR APPROVAL' ";
				}
				if(isset($_GET['for_processing'])){
					$string .= "or status='FOR PROCESSING' ";
				}
				if(isset($_GET['processed'])){
					$string .= "or status='PROCESSED' ";
				}
				if(isset($_GET['rejected'])){
					$string .= "or status='REJECTED' ";
				}
				if(isset($_GET['cancelled'])){
					$string .= "or status='CANCELLED' ";
				}

				$pattern1 = '/ or /';
				$string2 = preg_replace('/ or /', 'dummy_string', $string);
				$string3 = preg_replace('/or/', '', $string2);
				$status_filter = preg_replace('/dummy_string/', ' or ', $string3);

			}else{
				$year = date('Y');
				$data['from'] = $year.'-01-01';
				$data['to'] = $year.'-12-31';
			}
			
			switch ($action) {
				case 'add':
					$data['submit'] = HTTP_PATH.'Corporate_Services/overtime/insert';
					$data['employee'] = $this->Abas->getItemById('hr_employees',array('user_id'=>$employee_id));
					$this->load->view('corporate_services/applications/overtime_form',$data);
				break;

				case 'insert':
					if(isset($_POST['emp_auto_complete'])){
						unset($overtime['employee_id']);
						$emp_id = strtok($_POST['emp_auto_complete'],')');
						$explode = explode("(", $emp_id, 2);
						$employee_id = $explode[1];
						$overtime = array_merge($overtime,array('employee_id'=>$employee_id));
						$this->Abas->updateItem('hr_employees',array('user_id'=>$sid),array('id'=>$employee_id),"updated user_id");
					}

					$overtime = array_merge($overtime,array('status' => 'FOR APPROVAL'));
					$this->Abas->insertItem('employee_overtime',$overtime,"Insert record for overtime");
					$this->Abas->sysMsg("sucmsg","Successfully Filed Overtime!");
					redirect(HTTP_PATH.'Corporate_Services/overtime');
				break;

				case 'cancel':
					$update = array('status' => 'CANCELLED');
					$where = array('id' => $id);
					$this->Abas->updateItem('employee_overtime',$update,$where,"Record cancelled for overtime");
					$this->Abas->sysMsg("sucmsg","Successfully Filed Overtime!");
					redirect(HTTP_PATH.'Corporate_Services/overtime');
				break;

				case 'view':
					$data['submit'] = HTTP_PATH.'Corporate_Services/overtime/view/'.$id;
					$data['item'] = $this->Abas->getItemById('employee_overtime',array('id'=>$id));
					$data['employee'] = $this->Abas->getItemById('hr_employees',array('user_id'=>$employee_id));
					$this->load->view('corporate_services/applications/overtime_form',$data);
				break;

				case 'edit':
					$data['submit'] = HTTP_PATH.'Corporate_Services/overtime/update/'.$id;
					$data['item'] = $this->Abas->getItemById('employee_overtime',array('id'=>$id));
					$data['employee'] = $this->Abas->getItemById('hr_employees',array('user_id'=>$employee_id));
					$this->load->view('corporate_services/applications/overtime_form',$data);
				break;

				case 'update':
					$where = array('id'=>$id);
					$item = $this->Abas->getItemById('employee_overtime',$where);
					$overtime = array_merge($overtime,array('status' => $item->status));
					$this->Abas->updateItem('employee_overtime',$overtime,$where,"Record updated for overtime");
					$this->Abas->sysMsg("sucmsg","Successfully Edited Overtime!");
					redirect(HTTP_PATH.'Corporate_Services/overtime');
				break;

				case 'filter':
					$from = $_POST['date_from'];
					$to = $_POST['date_to'];
					$cbProcessed = isset($_POST['cbProcessed']) ? true : false;
					$cbForApproval = isset($_POST['cbForApproval']) ? true : false;
					$cbForProcessing = isset($_POST['cbForProcessing']) ? true : false;
					$cbRejected = isset($_POST['cbRejected']) ? true : false;
					$cbCancelled = isset($_POST['cbCancelled']) ? true : false;

					$url = HTTP_PATH.'Corporate_Services/overtime?date_from='.$from.'&date_to='.$to;
					if($cbProcessed){
						$url = $url.'&processed=true';
					}
					if($cbForApproval){
						$url = $url.'&for_approval=true';
					}
					if($cbForProcessing){
						$url = $url.'&for_processing=true';
					}
					if($cbRejected){
						$url = $url.'&rejected=true';
					}
					if($cbCancelled){
						$url = $url.'&cancelled=true';
					}
					redirect($url);
				break;
				
				default:
					if(isset($_GET['date_from'])){
						if($status_filter != ''){
							$data['overtime'] = $this->Corporate_Services_model->getEmpOvertimeFiltered($employee_id,$year_filter,$status_filter);	
						}else{
							$data['overtime'] = array();
						}
						
					}else{
						$data['overtime'] = $this->Corporate_Services_model->getEmpOvertime($employee_id);
					}

					$data['viewfile'] =	"corporate_services/applications/overtime.php";
					$this->load->view('gentlella_container.php',$data);
				break;
			}
		}

		public function approval($action='',$id=''){
			switch ($action) {
				case 'view':
					$data['item'] = $this->Abas->getItemById('employee_leave',array('id'=>$id));
					$this->load->view('corporate_services/applications/approve_form',$data);
				break;

				case 'approve':
					if($_GET['form'] == 'overtime'){
						$tbl = 'employee_overtime';
					}elseif ($_GET['form'] == 'leave') {
						$tbl = 'employee_leave';
					}else{
						$tbl = 'employee_leave';
					}

					$data = array(
						'date_approved' => date('Y-m-d'),
						'status' => 'FOR PROCESSING'
					);
					$this->Abas->updateItem($tbl,$data,array('id'=>$id));
					$this->Abas->sysMsg("sucmsg","Application has been Approved!");
					redirect(HTTP_PATH.'Corporate_Services/approval/'.$_GET['form']);
				break;

				case 'reject':
					if($_GET['form'] == 'overtime'){
						$tbl = 'employee_overtime';
					}elseif ($_GET['form'] == 'leave') {
						$tbl = 'employee_leave';
					}else{
						$tbl = 'employee_leave';
					}
					$data = array(
						'status' => 'REJECTED'
					);
					$this->Abas->updateItem('employee_leave',$data,array('id'=>$id));
					$this->Abas->sysMsg("sucmsg","Application has been Rejected!");
					redirect(HTTP_PATH.'Corporate_Services/approval');
				break;

				case 'filter':
					$from = $_POST['date_from'];
					$to = $_POST['date_to'];
					$cbProcessed = isset($_POST['cbProcessed']) ? true : false;
					$cbForApproval = isset($_POST['cbForApproval']) ? true : false;
					$cbForProcessing = isset($_POST['cbForProcessing']) ? true : false;
					$cbRejected = isset($_POST['cbRejected']) ? true : false;
					$cbCancelled = isset($_POST['cbCancelled']) ? true : false;
					$form = $_GET['form'];

					$url = HTTP_PATH.'Corporate_Services/approval/'.$form.'?date_from='.$from.'&date_to='.$to;
					if($cbProcessed){
						$url = $url.'&processed=true';
					}
					if($cbForApproval){
						$url = $url.'&for_approval=true';
					}
					if($cbForProcessing){
						$url = $url.'&for_processing=true';
					}
					if($cbRejected){
						$url = $url.'&rejected=true';
					}
					if($cbCancelled){
						$url = $url.'&cancelled=true';
					}
					redirect($url);
				break;

				case 'leave':
					$hr_id = $this->Abas->getEmpId($_SESSION['abas_login']['userid']);
					$approver = $this->Abas->getItemById('employee_approver',array('approver_id'=>$hr_id));
					$where = array('approver_id' => $hr_id, 'status' => 'FOR APPROVAL');
					$status = "";
					if(isset($_GET['date_from'])){
						$date = "date_from >= '".$_GET['date_from']."' AND date_from <= '".$_GET['date_to']."'";
						if(isset($_GET['for_approval'])){
							$status .= "status = 'FOR APPROVAL' ";
						}
						if(isset($_GET['for_processing'])){
							$status .= "or status = 'FOR PROCESSING' ";
						}
						if(isset($_GET['processed'])){
							$status .= "or status='PROCESSED' ";
						}
						if(isset($_GET['rejected'])){
							$status .= "or status='REJECTED' ";
						}
						if(isset($_GET['cancelled'])){
							$status .= "or status='CANCELLED' ";
						}

						$string1 = preg_replace('/ or /', 'dummy_string', $status);
						$string2 = preg_replace('/or/', '', $string1);
						$status = preg_replace('/dummy_string/', ' or ', $string2);
						if($status == ''){
							$status = "status = 'FOR APPROVAL'";
							$yr = date('Y');
							$from = $yr.'-01-31';
							$to = $yr.'-12-31';
							$date = "date_from >= '".$from."' AND date_from <= '".$to."'";
						}
					}else{
						$status = "status = 'FOR APPROVAL'";
						$yr = date('Y');
						$from = $yr.'-01-31';
						$to = $yr.'-12-31';
						$date = "date_from >= '".$from."' AND date_from <= '".$to."'";
					}
					$leave = $this->Corporate_Services_model->getLeaveByApprover($hr_id,$status,$date);

					foreach ($leave as $ctr => $row) {
						$leave_array[$ctr] = array(
							'id' => $row->id,
							'date_filed' => $row->date_filed,
							'name' => $this->Abas->getEmpName($row->employee_id),
							'date_from' => $row->date_from,
							'date_to' => $row->date_to,
							'days' => $row->days,
							'with_pay' => ($row->is_with_pay=='1' ? "<span class='glyphicon glyphicon-ok'/>" : "<span class='glyphicon glyphicon-remove'/>"),
							'status' => $row->status,
							'balance' => $this->Corporate_Services_model->getLeaveBal($row->employee_id)
						);	
					}
					if(isset($leave_array)){
						$data['leave'] = $leave_array;
					}else{
						$data['leave'] = array();
					}
					//-------------------------------------------------------------------------
					$data['leave_count'] = $this->Abas->getLeaveCount();
					$data['overtime_count'] = $this->Abas->getOvertimeCount();

					$data['viewfile'] =	"corporate_services/applications/approve.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'overtime':
					$hr_id = $this->Abas->getEmpId($_SESSION['abas_login']['userid']);
					$approver = $this->Abas->getItemById('employee_approver',array('approver_id'=>$hr_id));
					$where = array('approver_id' => $hr_id, 'status' => 'FOR APPROVAL');
					$status = "";
					if(isset($_GET['date_from'])){
						$date = "render_date >= '".$_GET['date_from']."' AND render_date <= '".$_GET['date_to']."'";
						if(isset($_GET['for_approval'])){
							$status .= "status = 'FOR APPROVAL' ";
						}
						if(isset($_GET['for_processing'])){
							$status .= "or status = 'FOR PROCESSING' ";
						}
						if(isset($_GET['processed'])){
							$status .= "or status='PROCESSED' ";
						}
						if(isset($_GET['rejected'])){
							$status .= "or status='REJECTED' ";
						}
						if(isset($_GET['cancelled'])){
							$status .= "or status='CANCELLED' ";
						}
						$string1 = preg_replace('/ or /', 'dummy_string', $status);
						$string2 = preg_replace('/or/', '', $string1);
						$status = preg_replace('/dummy_string/', ' or ', $string2);
						if($status == ''){
							$status = "status = 'FOR APPROVAL'";
							$yr = date('Y');
							$from = $yr.'-01-31';
							$to = $yr.'-12-31';
							$date = "render_date >= '".$from."' AND render_date <= '".$to."'";
						}
					}else{
						$status = "status = 'FOR APPROVAL'";
						$yr = date('Y');
						$from = $yr.'-01-31';
						$to = $yr.'-12-31';
						$date = "render_date >= '".$from."' AND render_date <= '".$to."'";
					}
					$data['overtime'] = $this->Corporate_Services_model->getOvertimeByApprover($hr_id,$status,$date);

					$data['leave_count'] = $this->Abas->getLeaveCount();
					$data['overtime_count'] = $this->Abas->getOvertimeCount();

					$data['viewfile'] =	"corporate_services/applications/approve_overtime.php";
					$this->load->view('gentlella_container.php',$data);
				break;
				
				default:
					redirect(HTTP_PATH.'Corporate_Services/approval/leave');
				break;
			}
		}
		
		public function autocomplete_employee(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT id, last_name, first_name, middle_name, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.','(',id,')') as full_name FROM hr_employees WHERE last_name LIKE '%".$search."%' OR first_name LIKE '%".$search."%' OR middle_name LIKE '%".$search."%' ORDER BY last_name LIMIT 0, 10";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						
						$ret[$ctr]['label']	=	$i['full_name'];
						$ret[$ctr]['value']	=	$i['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
	}
?>