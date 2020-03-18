<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends CI_Controller{

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		$this->load->model("Mmm");
		$this->load->model("Purchasing_model");
		$this->load->model("Inventory_model");
		$this->load->model("Operation_model");
		$this->load->model("Manager_model");
		$this->load->model("Accounting_model");
		$this->load->model("Finance_model");
		$this->load->model("Asset_Management_model");
		$this->load->model("Corporate_Services_model");
		$this->output->enable_profiler(FALSE);
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home");}
		define("SIDEMENU", "Manager's Dashboard");
	}

	//Global Variables (Class Level)
	//protected $sid = $_SESSION['abas_login']['userid'];

	public function index(){
		$data['viewfile']			=	"manager/page_tab.php";
		$mainview					=	"gentlella_container.php";
		$this->load->view($mainview, $data);
	}

	public function purchase_requests($action="",$id=""){
		switch ($action) {
			case 'load':
				if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
				$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
				$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
				$order	=	isset($_GET['order'])?$_GET['order']:"";
				$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
				$search	=	isset($_GET['search'])?$_GET['search']:"";
				$data = $this->Abas->getDataForBSTable("inventory_requests_for_approval",$search,$limit,$offset,$order,$sort,"(approved_by = ".$_SESSION['abas_login']['userid']." OR approved_by = 0) AND stat=1");
				foreach($data['rows'] as $ctr=>$request){
					if(isset($request['tdate'])) {
						$data['rows'][$ctr]['tdate']	=	date("j F Y h:i A", strtotime($request['tdate']));
					}
					if(isset($request['added_by'])) {
						$added_by							=	$this->Abas->getUser($request['added_by']);
						$data['rows'][$ctr]['added_by']		=	$added_by['full_name'];
					}
					if(isset($request['added_on'])) {
						$data['rows'][$ctr]['added_on']	=	date("j F Y h:i A", strtotime($request['added_on']));
					}
					if(isset($request['approved_by'])) {
						$approved_by						=	$this->Abas->getUser($request['approved_by']);
						$data['rows'][$ctr]['approved_by']	=	$approved_by['full_name'];
						$data['rows'][$ctr]['authorized_approver']		=	$approved_by['full_name'];
					}
					if($request['approved_by']==0){
						$data['rows'][$ctr]['authorized_approver']		= "Any Manager";
					}
					if(isset($request['approved_on'])) {
						$data['rows'][$ctr]['approved_on']	=	date("j F Y h:i A", strtotime($request['approved_on']));
					}
					if(isset($request['vessel_id'])){
						$vessel = $this->Abas->getVessel($request['vessel_id']);
						$data['rows'][$ctr]['vessel_name'] = $vessel->name;

						$company = $this->Abas->getCompany($vessel->company);
						$data['rows'][$ctr]['company_name'] = $company->name;
					}
					if(isset($request['department_id'])){
						$department = $this->Abas->getDepartment($request['department_id']);
						$data['rows'][$ctr]['department_name'] = $department->name;
					}
					if(isset($request['status'])){
						$data['rows'][$ctr]['status'] = ucwords($request['status']);
					}
				}
				header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}
			break;

			case 'listview':
				$this->load->view('manager/purchase_request_approval/listview.php');
			break;

			case 'view':
				$data['request'] = $this->Purchasing_model->getRequest($id);
				$vessel = $this->Abas->getVessel($data['request']['vessel_id']);
				$data['vessel_name'] = $vessel->name;
				$company = $this->Abas->getCompany($vessel->company);
				$data['company_name'] = $company->name;
				$department = $this->Abas->getDepartment($data['request']['department_id']);
				$data['department_name'] = $department->name;
				if($data['request']['approved_by']==''){
					$approver	=	$this->Abas->getUser($data['request']['approved_by']);
				$data['approver_name']	=	$approver['full_name'];
				}else{
					$data['approver_name']	=	'Any Manager';
				}
				$sql_append = " AND status != 'Cancelled' AND status !='For canvassing' AND supplier_id = 0";
				$data['request_details'] = $this->Purchasing_model->getRequestDetails($id,$sql_append);
				$this->load->view('manager/purchase_request_approval/form.php', $data);
			break;

			case 'save':
				if(isset($_POST)){
					$rid = $_POST['request_id'];
					$approved_items = $_POST['approved_items'];
					$cancelled_items = $_POST['cancelled_items'];
					$number_of_items = $_POST['number_of_items'];

					$approved_items = explode(",",$approved_items);
					$cancelled_items = explode(",",$cancelled_items);

						$ctr_approved = 0;
						$ctr_cancelled = 0;

						foreach($_POST['selected_id'] as $ctr_approved=>$val){
							$id = $this->Mmm->sanitize($_POST['selected_id'][$ctr_approved]);
							$update['request_approved_by']	= $_SESSION['abas_login']['userid'];
							$update['request_approved_on']	= date('Y-m-d h:m:s');
							$update['status']	= "For canvassing";
							$checkMulti		=	$this->Mmm->dbUpdate("inventory_request_details", $update,$id, "Purchase Request (TSCode No.".$rid.") with Item ID: ".$id." was approved by ".$_SESSION['abas_login']['userid']." for canvassing.");
							$ctr_approved++;
						}

						foreach($_POST['cancelled_id'] as $ctr_cancelled=>$valx){
							$idx = $this->Mmm->sanitize($_POST['cancelled_id'][$ctr_cancelled]);
							$updatex['status']	= "Cancelled";
							$checkMultix		=	$this->Mmm->dbUpdate("inventory_request_details", $updatex,$idx, "Purchase Request (TSCode No.".$rid.") with Item ID: ".$idx." was cancelled by ".$_SESSION['abas_login']['userid']);
							$ctr_cancelled++;
						}

						//check total items in this request
						if($number_of_items == ($ctr_approved + $ctr_cancelled)){
							//update summary status
							$sql1 = "UPDATE inventory_requests SET status = 'For canvassing' WHERE id = $rid";
							$db = $this->Mmm->query($sql1, 'Request #'.$rid.' was approved by  '.$_SESSION['abas_login']['username']);
						}

						$notif_msg	=	"Requested item(s) for Purchase Request with Transaction Code No. ".$rid." has been sucessfully approved by ".$_SESSION['abas_login']['fullname'].".";
						$this->Abas->sysNotif("Approve Purchase Request", $notif_msg,"Purchasing","info");
						$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect(HTTP_PATH."manager/");

			break;

			default:
				return NULL;
			break;
		}
	}

	public function canvass($action="",$id=""){
		switch ($action) {
			case 'load':
				if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
				$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
				$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
				$order	=	isset($_GET['order'])?$_GET['order']:"";
				$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
				$search	=	isset($_GET['search'])?$_GET['search']:"";
				$data = $this->Abas->getDataForBSTable("inventory_request_details",$search,$limit,$offset,$order,$sort,"status='For Canvass Approval' GROUP BY request_id");
				foreach($data['rows'] as $ctr=>$canvass){
					$request = $this->Purchasing_model->getRequest($canvass['request_id']);
					
					if(isset($request['vessel_id'])){
						$vessel = $this->Abas->getVessel($request['vessel_id']);
						$data['rows'][$ctr]['vessel_name'] = $vessel->name;

						$company = $this->Abas->getCompany($vessel->company);
						$data['rows'][$ctr]['company_name'] = $company->name;
					}
					if(isset($request['status'])){
						$data['rows'][$ctr]['request_status'] = ucwords($request['status']);
					}
					if(isset($request['control_number'])){
						$data['rows'][$ctr]['control_number'] = $request['control_number'];
					}

					if(isset($request['department_id'])){
						$department = $this->Abas->getDepartment($request['department_id']);
						$data['rows'][$ctr]['department_name'] = $department->name;
					}
					if(isset($request['priority'])){
						$data['rows'][$ctr]['priority'] = $request['priority'];
					}
					if(isset($request['remark'])){
						$data['rows'][$ctr]['purpose'] = $request['remark'];
					}
					if(isset($canvass['added_by'])) {
						$added_by							=	$this->Abas->getUser($canvass['added_by']);
						$data['rows'][$ctr]['added_by']		=	$added_by['full_name'];
					}
					if(isset($canvass['added_on'])) {
						$data['rows'][$ctr]['added_on']	=	date("j F Y h:i A", strtotime($canvass['added_on']));
					}
					if(isset($canvass['request_approved_by'])) {
						$approved_by						=	$this->Abas->getUser($canvass['request_approved_by']);
						$data['rows'][$ctr]['request_approved_by']	=	$approved_by['full_name'];
					}
					if(isset($canvass['request_approved_on'])) {
						$data['rows'][$ctr]['request_approved_on']	=	date("j F Y h:i A", strtotime($canvass['request_approved_on']));
					}
					if(isset($canvass['supplier_id'])){
						$supplier = $this->Abas->getSupplier($canvass['supplier_id']);
						$data['rows'][$ctr]['supplier_name'] = $supplier['name'];
					}
				}
				header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}
			break;

			case 'listview':
				$this->load->view('manager/canvass_approval/listview.php');
			break;

			case 'view':
				$data['request'] = $this->Purchasing_model->getRequest($id);
				$vessel = $this->Abas->getVessel($data['request']['vessel_id']);
				$data['vessel_name'] = $vessel->name;
				$company = $this->Abas->getCompany($vessel->company);
				$data['company_name'] = $company->name;
				$department = $this->Abas->getDepartment($data['request']['department_id']);
				$data['department_name'] = $department->name;
				$data['rd'] = $this->Purchasing_model->getRequestDetails($id);
				$sql_append = " AND status ='For Canvass Approval' AND supplier_id!=0 GROUP BY request_id,item_id ORDER BY id ASC";
				$data['request_details'] = $this->Purchasing_model->getRequestDetails($id,$sql_append);
				$this->load->view('manager/canvass_approval/form.php', $data);
			break;

			case 'save':
				if(isset($_POST)){

					$rid = $_POST['request_id'];
					$number_of_items = $_POST['item_count'];

						for($i=0; $i < $number_of_items ; $i++){
							$name = "item_".$i;
							if(isset($_POST[$name])){
								$id = $this->Mmm->sanitize($_POST[$name]);
								$update['canvass_approved_by']	= $_SESSION['abas_login']['userid'];
								$update['canvass_approved_on']	= date('Y-m-d h:m:s');
								$update['status']	= "for purchase";
								$selectCanvass		=	$this->Mmm->dbUpdate("inventory_request_details", $update,$id, "Canvass for Purchase Request (TSCode No.".$rid.") was approved by ".$_SESSION['abas_login']['userid']);
								echo $_POST[$name]."<br>";
							}
						}

						$sql1 =	"UPDATE inventory_request_details SET status='for purchase',canvass_approved_by=".$update['canvass_approved_by'].",canvass_approved_on='".$update['canvass_approved_on']."' WHERE request_id=".$rid." AND supplier_id=0 AND status LIKE 'for canvass approval'";
						$unselectCanvass	=	$this->Mmm->query($sql1, 'Set Purchase Request (TSCode No.'.$rid.') status to "For Purchase"');

						$sql2 =	"UPDATE inventory_request_details SET status='unselected',canvass_approved_by=".$update['canvass_approved_by'].",canvass_approved_on='".$update['canvass_approved_on']."' WHERE request_id=".$rid." AND supplier_id<>0 AND status LIKE 'for canvass approval'";
						$unselectCanvass	=	$this->Mmm->query($sql2, 'Set unselected canvass from Purchase Request (TSCode No.'.$rid.')');

						$notif_msg	=	"Canvass for Purchase Request with Transaction Code No. ".$rid." has been sucessfully approved by ".$_SESSION['abas_login']['fullname'].".";
						$this->Abas->sysNotif("Approve Canvass", $notif_msg,"Purchasing","info");
						$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect(HTTP_PATH."manager/");

			break;

			default:
				return NULL;
			break;
		}
	}


	public function purchase_orders($action="",$id=""){
		switch ($action) {
			case 'load':
				if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
				$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
				$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
				$order	=	isset($_GET['order'])?$_GET['order']:"";
				$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
				$search	=	isset($_GET['search'])?$_GET['search']:"";
				$data = $this->Abas->getDataForBSTable("inventory_po",$search,$limit,$offset,$order,$sort,"status='For Purchase Order Approval'");
				foreach($data['rows'] as $ctr=>$po){
					$request = $this->Purchasing_model->getRequest($po['request_id']);
					$request_details = $this->Purchasing_model->getRequestDetails($po['request_id']);
					
					if(isset($request['vessel_id'])){
						$vessel = $this->Abas->getVessel($request['vessel_id']);
						$data['rows'][$ctr]['vessel_name'] = $vessel->name;
					}

					if(isset($po['company_id'])){
						$company = $this->Abas->getCompany($po['company_id']);
						$data['rows'][$ctr]['company_name'] = $company->name;
					}

					if(isset($po['status'])){
						$data['rows'][$ctr]['status'] = ucwords($po['status']);
					}

					if(isset($request['department_id'])){
						$department = $this->Abas->getDepartment($request['department_id']);
						$data['rows'][$ctr]['department_name'] = $department->name;
					}

					if(isset($request['priority'])){
						$data['rows'][$ctr]['priority'] = $request['priority'];
					}
					if(isset($request['remark'])){
						$data['rows'][$ctr]['purpose'] = $request['remark'];
					}

					if(isset($po['added_by'])) {
						$added_by							=	$this->Abas->getUser($po['added_by']);
						$data['rows'][$ctr]['added_by']		=	$added_by['full_name'];
					}
					if(isset($po['added_on'])) {
						$data['rows'][$ctr]['added_on']	=	date("j F Y h:i A", strtotime($po['added_on']));
					}
					if(isset($request_details[0]['request_approved_by'])) {
						$data['rows'][$ctr]['request_approved_by']	=	$request_details[0]['request_approved_by']['full_name'];
					}
					if(isset($request_details[0]['request_approved_on'])) {
						$data['rows'][$ctr]['request_approved_on']	=	date("j F Y h:i A", strtotime($request_details[0]['request_approved_on']));
					}
					if(isset($request_details[0]['canvass_approved_by'])) {
						$data['rows'][$ctr]['canvass_approved_by']	=	$request_details[0]['canvass_approved_by']['full_name'];
					}
					if(isset($request_details[0]['canvass_approved_on'])) {
						$data['rows'][$ctr]['canvass_approved_on']	=	date("j F Y h:i A", strtotime($request_details[0]['canvass_approved_on']));
					}
					if(isset($po['supplier_id'])){
						$supplier = $this->Abas->getSupplier($po['supplier_id']);
						$data['rows'][$ctr]['supplier_name'] = $supplier['name'];
					}
					if(isset($po['amount'])){
						$data['rows'][$ctr]['amount'] = number_format($po['amount'],2,'.',',');
					}
				}
				header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}
			break;

			case 'listview':
				$this->load->view('manager/purchase_order_approval/listview.php');
			break;

			case 'view':
				$data['purchase_order'] = $this->Purchasing_model->getPurchaseOrder($id);
				$data['request'] = $this->Purchasing_model->getRequest($data['purchase_order']['request_id']);
				$data['request_details'] = $this->Purchasing_model->getRequestDetails($data['purchase_order']['request_id']);
				$this->load->view('manager/purchase_order_approval/form.php', $data);
			break;

			case 'save':
				$sql =	"UPDATE inventory_po SET status = 'For ordering', approved_by = '".$_SESSION['abas_login']['userid']."',approved_on = '".date('Y-m-d h:m:s')."' WHERE id=".$id;
				$approvePO	=	$this->Mmm->query($sql, 'Approve PO (TSCode No.'.$id.') and set status "For Ordering"');

				if($approvePO){
					$notif_msg	=	"Purchase Order with Transaction Code No. ".$id." has been sucessfully approved by ".$_SESSION['abas_login']['fullname'].".";
					$this->Abas->sysNotif("Approve Purchase Order", $notif_msg,"Purchasing","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect(HTTP_PATH."manager/");
			break;

			case 'cancel':
				$po = $this->Purchasing_model->getPurchaseOrder($id);
				$po_details = $this->Purchasing_model->getPurchaseOrderDetails($id);
				foreach($po_details as $pod){
					$request_details = $this->Purchasing_model->getRequestDetails($po['request_id']);
					foreach($request_details as $rd){
						if($rd['status']=="For Delivery") {
							$request_detail_canvasses_sql	=	"UPDATE inventory_request_details SET status='Cancelled' WHERE item_id=".$pod['item_id']." AND supplier_id<>0 AND request_id=".$po['request_id'];
							$request_detail_parent_sql		=	"UPDATE inventory_request_details SET status='Cancelled' WHERE item_id=".$pod['item_id']." AND supplier_id=0 AND request_id=".$po['request_id'];
							$request_detail_canvasses		=	$this->db->query($request_detail_canvasses_sql);
							$request_detail_parent			=	$this->db->query($request_detail_parent_sql);
							
						}
					}
				}

				$sql =	"UPDATE inventory_po SET status = 'Cancelled' WHERE id=".$id;
				$cancelPO	=	$this->Mmm->query($sql, 'Cancelled PO (TSCode No.'.$id.')');

				if($cancelPO){
					$notif_msg	=	"Purchase Order with Transaction Code No. ".$id." has been cancelled by ".$_SESSION['abas_login']['fullname'].".";
					$this->Abas->sysNotif("Cancel Purchase Order", $notif_msg,"Purchasing","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect(HTTP_PATH."manager/");
			break;

			default:
				return NULL;
			break;
		}
	}

	public function job_orders($action="",$id=""){
		switch ($action) {
			case 'load':
				if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
				$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
				$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
				$order	=	isset($_GET['order'])?$_GET['order']:"";
				$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
				$search	=	isset($_GET['search'])?$_GET['search']:"";
				$data = $this->Abas->getDataForBSTable("inventory_job_orders",$search,$limit,$offset,$order,$sort,"status='For Job Order Approval'");
				foreach($data['rows'] as $ctr=>$po){
					$request = $this->Purchasing_model->getRequest($po['request_id']);
					$request_details = $this->Purchasing_model->getRequestDetails($po['request_id']);
					
					if(isset($request['vessel_id'])){
						$vessel = $this->Abas->getVessel($request['vessel_id']);
						$data['rows'][$ctr]['vessel_name'] = $vessel->name;
					}

					if(isset($po['company_id'])){
						$company = $this->Abas->getCompany($po['company_id']);
						$data['rows'][$ctr]['company_name'] = $company->name;
					}

					if(isset($po['status'])){
						$data['rows'][$ctr]['status'] = ucwords($po['status']);
					}

					if(isset($request['department_id'])){
						$department = $this->Abas->getDepartment($request['department_id']);
						$data['rows'][$ctr]['department_name'] = $department->name;
					}

					if(isset($request['priority'])){
						$data['rows'][$ctr]['priority'] = $request['priority'];
					}
					if(isset($request['remark'])){
						$data['rows'][$ctr]['purpose'] = $request['remark'];
					}

					if(isset($po['added_by'])) {
						$added_by							=	$this->Abas->getUser($po['added_by']);
						$data['rows'][$ctr]['added_by']		=	$added_by['full_name'];
					}
					if(isset($po['added_on'])) {
						$data['rows'][$ctr]['added_on']	=	date("j F Y h:i A", strtotime($po['added_on']));
					}
					if(isset($request_details[0]['request_approved_by'])) {
						$data['rows'][$ctr]['request_approved_by']	=	$request_details[0]['request_approved_by']['full_name'];
					}
					if(isset($request_details[0]['request_approved_on'])) {
						$data['rows'][$ctr]['request_approved_on']	=	date("j F Y h:i A", strtotime($request_details[0]['request_approved_on']));
					}
					if(isset($request_details[0]['canvass_approved_by'])) {
						$data['rows'][$ctr]['canvass_approved_by']	=	$request_details[0]['canvass_approved_by']['full_name'];
					}
					if(isset($request_details[0]['canvass_approved_on'])) {
						$data['rows'][$ctr]['canvass_approved_on']	=	date("j F Y h:i A", strtotime($request_details[0]['canvass_approved_on']));
					}
					if(isset($po['supplier_id'])){
						$supplier = $this->Abas->getSupplier($po['supplier_id']);
						$data['rows'][$ctr]['supplier_name'] = $supplier['name'];
					}
					if(isset($po['amount'])){
						$data['rows'][$ctr]['amount'] = number_format($po['amount'],2,'.',',');
					}
				}
				header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}
			break;

			case 'listview':
				$this->load->view('manager/job_order_approval/listview.php');
			break;

			case 'view':
				$data['job_order'] = $this->Purchasing_model->getJobOrder($id);
				$data['job_order_details'] = $this->Purchasing_model->getJobOrderDetails($id);
				$data['request'] = $this->Purchasing_model->getRequest($data['job_order']->request_id);
				$data['request_details'] = $this->Purchasing_model->getRequestDetails($data['job_order']->request_id);
				$this->load->view('manager/job_order_approval/form.php', $data);
			break;

			case 'save':
				$sql =	"UPDATE inventory_job_orders SET status = 'For ordering', approved_by = '".$_SESSION['abas_login']['userid']."',approved_on = '".date('Y-m-d h:m:s')."' WHERE id=".$id;
				$approveJO	=	$this->Mmm->query($sql, 'Approve JO (TSCode No.'.$id.') and set status "For Ordering"');

				if($approveJO){
					$notif_msg	=	"Job Order with Transaction Code No. ".$id." has been sucessfully approved by ".$_SESSION['abas_login']['fullname'].".";
					$this->Abas->sysNotif("Approve Job Order", $notif_msg,"Purchasing","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect(HTTP_PATH."manager/");
			break;

			case 'cancel':
				$jo = $this->Purchasing_model->getJobOrder($id);
				$jo_details = $this->Purchasing_model->getJobOrderDetails($id);
				foreach($jo_details as $jod){
					$request_details = $this->Purchasing_model->getRequestDetails($jo->request_id);
					foreach($request_details as $rd){
						if($rd['status']=="For Delivery") {
							$request_detail_canvasses_sql	=	"UPDATE inventory_request_details SET status='Cancelled' WHERE item_id=".$jod->item_id." AND supplier_id<>0 AND request_id=".$jo->request_id;
							$request_detail_parent_sql		=	"UPDATE inventory_request_details SET status='Cancelled' WHERE item_id=".$jod->item_id." AND supplier_id=0 AND request_id=".$jo->request_id;
							$request_detail_canvasses		=	$this->db->query($request_detail_canvasses_sql);
							$request_detail_parent			=	$this->db->query($request_detail_parent_sql);
							
						}
					}
				}

				$sql =	"UPDATE inventory_job_orders SET status = 'Cancelled' WHERE id=".$id;
				$cancelJO	=	$this->Mmm->query($sql, 'Cancelled PO (TSCode No.'.$id.')');

				if($cancelJO){
					$notif_msg	=	"Job Order with Transaction Code No. ".$id." has been cancelled by ".$_SESSION['abas_login']['fullname'].".";
					$this->Abas->sysNotif("Cancel Job Order", $notif_msg,"Purchasing","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect(HTTP_PATH."manager/");
			break;

			default:
				return NULL;
			break;
		}
	}

	public function request_for_payment($action="",$id=""){
		switch ($action) {
			case 'load':
				if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$data = $this->Abas->getDataForBSTable("ac_request_payment",$search,$limit,$offset,$order,$sort,"(status='For Verification' OR status='For Approval')");
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
						if($request['payee']=='') {
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

			case 'listview':
				$this->load->view('manager/request_for_payment_approval/listview.php');
				break;

			case 'view':
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
				if($data['request'][0]['payee']=='') {
					$data['payee']	= $data['request'][0]['payee_others'];
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
				$data['request'][0]['verfied_by_name'] = $this->Abas->getUser($data['request'][0]['verified_by']);
				$data['request'][0]['approved_by_name'] = $this->Abas->getUser($data['request'][0]['approved_by']);
				$this->load->view("manager/request_for_payment_approval/form.php",$data);
				break;
			case 'verify':
				$rfp = $this->Accounting_model->getRequestPayment($id);
				$update['status']			=	"For Approval";
				$update['verified_by']		=	$_SESSION['abas_login']['userid'];
				$update['verified_on']		=	date('Y-m-d H:i:s');
				$verified	=	$this->Mmm->dbUpdate("ac_request_payment", $update, $id, "Verified RFP with transaction code no.".$id);
				if($verified){
					$control_number = $rfp[0]['control_number'];
					$company =  $this->Abas->getCompany($rfp[0]['company_id']);
					if($rfp[0]['payee_type']=='Employee') {
						$employee	=	$this->Abas->getEmployee($rfp[0]['payee']);
						$payee	= $employee['full_name'];
					}elseif($rfp[0]['payee_type']=='Supplier'){
						$supplier =	$this->Abas->getSupplier($rfp[0]['payee']);
						$payee	= $supplier['name'];
					}
					$notif_msg	=	"Request for Payment with Control No. ".$control_number." under ".$company->name." has been sucessfully verified by ".$_SESSION['abas_login']['fullname']." for payment to ".$payee;
					$this->Abas->sysNotif("Verify Request for Payment", $notif_msg,"Accounting","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				break;

			case 'approve':
				$rfp = $this->Accounting_model->getRequestPayment($id);
				$update['status']			=	"For Voucher";
				$update['approved_by']		=	$_SESSION['abas_login']['userid'];
				$update['approved_on']		=	date('Y-m-d H:i:s');
				$approved	=	$this->Mmm->dbUpdate("ac_request_payment", $update, $id, "Approved RFP with transaction code no.".$id);
				if($approved){
					$control_number = $rfp[0]['control_number'];
					$company =  $this->Abas->getCompany($rfp[0]['company_id']);
					if($rfp[0]['payee_type']=='Employee') {
						$employee	=	$this->Abas->getEmployee($rfp[0]['payee']);
						$payee	= $employee['full_name'];
					}elseif($rfp[0]['payee_type']=='Supplier'){
						$supplier =	$this->Abas->getSupplier($rfp[0]['payee']);
						$payee	= $supplier['name'];
					}
					$notif_msg	=	"Request for Payment with Control No. ".$control_number." under ".$company->name." has been sucessfully approved by ".$_SESSION['abas_login']['fullname']." for payment to ".$payee;
					$this->Abas->sysNotif("Approved Request for Payment", $notif_msg,"Accounting","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				break;

			case 'cancel':
				$rfp = $this->Accounting_model->getRequestPayment($id);
				$update['status']			=	"Cancelled";
				$update['approved_by']		=	$_SESSION['abas_login']['userid'];
				$update['approved_on']		=	date('Y-m-d H:i:s');
				$cancelled	=	$this->Mmm->dbUpdate("ac_request_payment", $update, $id, "Cancelled RFP with transaction code no.".$id);
				if($cancelled){
					$control_number = $rfp[0]['control_number'];
					$company =  $this->Abas->getCompany($rfp[0]['company_id']);
					if($rfp[0]['payee_type']=='Employee') {
						$employee	=	$this->Abas->getEmployee($rfp[0]['payee']);
						$payee	= $employee['full_name'];
					}elseif($rfp[0]['payee_type']=='Supplier'){
						$supplier =	$this->Abas->getSupplier($rfp[0]['payee']);
						$payee	= $supplier['name'];
					}
					$notif_msg	=	"Request for Payment with Control No. ".$control_number." under ".$company->name." has been cancelled by ".$_SESSION['abas_login']['fullname']." for payment to ".$payee;
					$this->Abas->sysNotif("Cancelled Request for Payment", $notif_msg,"Accounting","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				break;
			
			default:
				return NULL;
			break;
		}
		
	}
	public function accountability_forms($action="",$id=""){
		switch ($action) {
			case 'load':
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$data = $this->Abas->getDataForBSTable("am_fixed_asset_accountability",$search,$limit,$offset,$order,$sort,"(status='For Verification' OR status='For Approval')");
					foreach($data['rows'] as $ctr=>$row){
						if(isset($row['company_id'])){
							$company		=	$this->Abas->getCompany($row['company_id']);
							$data['rows'][$ctr]['company_name']	=	$company->name;
						}
						if(isset($row['requested_by'])){
							$requested_by		=	$this->Abas->getEmployee($row['requested_by']);
							$data['rows'][$ctr]['requested_by']	=	$requested_by['full_name'];
						}
						if(isset($row['requested_on'])){
							$data['rows'][$ctr]['requested_on']	=	date("j F Y h:i:s A", strtotime($row['requested_on']));
						}
						if(isset($row['created_on'])){
							$data['rows'][$ctr]['created_on']	=	date("j F Y h:i:s A", strtotime($row['created_on']));
						}
						if(isset($row['created_by'])){
							$created_by		=	$this->Abas->getUser($row['created_by']);
							$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
						}
						if(isset($row['verified_on'])){
							$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
						}
						if(isset($row['verified_by'])){
							$verified_by		=	$this->Abas->getUser($row['verified_by']);
							$data['rows'][$ctr]['verified_by']	=	$verified_by['full_name'];
						}
						if(isset($row['approved_on'])){
							$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
						}
						if(isset($row['approved_on'])){
							$approved_on		=	$this->Abas->getUser($row['approved_on']);
							$data['rows'][$ctr]['approved_on']	=	$approved_on['full_name'];
						}

						$num = $this->Asset_Management_model->getAccountabilityFormDetails($row['id']);
						$data['rows'][$ctr]['number_of_assigned_assets']	=	count($num);
					}
				}
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			break;

			case 'view':
				$data['AC']	=	$this->Asset_Management_model->getAccountabilityForm($id);
				$data['AC_details']	=	$this->Asset_Management_model->getAccountabilityFormDetails($id);
				$this->load->view('manager/accountability_form_approval/form.php',$data);
			break;

			case 'listview':
				$this->load->view('manager/accountability_form_approval/listview.php');
			break;

			case 'cancel':
				$AC = $this->Asset_Management_model->getAccountabilityForm($id);
				$update['status']			=	"Cancelled";
				$cancelled	=	$this->Mmm->dbUpdate("am_fixed_asset_accountability", $update, $id, "Cancelled Accountability Form with transaction code no.".$id);
				if($cancelled){
					$control_number = $AC->control_number;
					$company =  $this->Abas->getCompany($AC->company_id);
					$notif_msg	=	"Accountability Form with Control No. ".$control_number." under ".$company->name." has been sucessfully cancelled by ".$_SESSION['abas_login']['fullname'];
					$this->Abas->sysNotif("Cancel Accountability Form", $notif_msg,"Asset Management","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
			break;

			case 'verify':
				$AC = $this->Asset_Management_model->getAccountabilityForm($id);
				$update['status']			=	"For Approval";
				$update['verified_by']		=	$_SESSION['abas_login']['userid'];
				$update['verified_on']		=	date('Y-m-d H:i:s');
				$verified	=	$this->Mmm->dbUpdate("am_fixed_asset_accountability", $update, $id, "Verified Accountability Form with transaction code no.".$id);
				if($verified){
					$control_number = $AC->control_number;
					$company =  $this->Abas->getCompany($AC->company_id);
					$notif_msg	=	"Accountability Form with Control No. ".$control_number." under ".$company->name." has been sucessfully verified by ".$_SESSION['abas_login']['fullname'];
					$this->Abas->sysNotif("Verify Accountability Form", $notif_msg,"Asset Management","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
			break;

			case 'approve':
				$AC = $this->Asset_Management_model->getAccountabilityForm($id);
				$update['status']			=	"Approved";
				$update['approved_by']		=	$_SESSION['abas_login']['userid'];
				$update['approved_on']		=	date('Y-m-d H:i:s');
				$approved	=	$this->Mmm->dbUpdate("am_fixed_asset_accountability", $update, $id, "Approved Accountability Form with transaction code no.".$id);
				if($approved){
					$AC_details	=	$this->Asset_Management_model->getAccountabilityFormDetails($id);
					foreach($AC_details as $row){
						$update2['status']			=	"Cleared";
						$approved2	=	$this->Mmm->dbUpdate("am_fixed_asset_accountability_details", $update2, $row->id, "Cleared Asset(s) on Accountability Form with transaction code no.".$id);

						$this->db->query('UPDATE am_fixed_assets SET status="Assigned" WHERE id='.$row->fixed_asset_id);
					}
					$control_number = $AC->control_number;
					$company =  $this->Abas->getCompany($AC->company_id);
					$notif_msg	=	"Accountability Form with Control No. ".$control_number." under ".$company->name." has been sucessfully approved by ".$_SESSION['abas_login']['fullname'];
					$this->Abas->sysNotif("Approve Accountability Form", $notif_msg,"Asset Management","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
			break;

			default:
				return NULL;
			break;
		}
	}

	public function disposal_slips($action="",$id=""){
		switch ($action) {
			case 'load':
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$data = $this->Abas->getDataForBSTable("am_fixed_asset_disposals",$search,$limit,$offset,$order,$sort,"(status='For Verification' OR status='For Approval')");
					foreach($data['rows'] as $ctr=>$row){
						if(isset($row['company_id'])){
							$company		=	$this->Abas->getCompany($row['company_id']);
							$data['rows'][$ctr]['company_name']	=	$company->name;
						}
						if(isset($row['requested_by'])){
							$requested_by		=	$this->Abas->getEmployee($row['requested_by']);
							$data['rows'][$ctr]['requested_by']	=	$requested_by['full_name'];
						}
						if(isset($row['requested_on'])){
							$data['rows'][$ctr]['requested_on']	=	date("j F Y", strtotime($row['requested_on']));
						}
						if(isset($row['created_on'])){
							$data['rows'][$ctr]['created_on']	=	date("j F Y h:i:s A", strtotime($row['created_on']));
						}
						if(isset($row['created_by'])){
							$created_by		=	$this->Abas->getUser($row['created_by']);
							$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
						}
						if(isset($row['verified_on'])){
							$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
						}
						if(isset($row['verified_by'])){
							$verified_by		=	$this->Abas->getUser($row['verified_by']);
							$data['rows'][$ctr]['verified_by']	=	$verified_by['full_name'];
						}
						if(isset($row['approved_on'])){
							$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
						}
						if(isset($row['approved_on'])){
							$approved_on		=	$this->Abas->getUser($row['approved_on']);
							$data['rows'][$ctr]['approved_on']	=	$approved_on['full_name'];
						}
						
					}
				}
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			break;

			case 'view':
				$data['disposal']	=	$this->Asset_Management_model->getDisposalSlip($id);
				$data['disposal_details']	=	$this->Asset_Management_model->getDisposalSlipDetails($id);
				$this->load->view('manager/disposal_slips_approval/form.php',$data);
			break;

			case 'listview':
				$this->load->view('manager/disposal_slips_approval/listview.php');
			break;

			case 'cancel':
				$disposal = $this->Asset_Management_model->getDisposalSlip($id);
				$update['status']			=	"Cancelled";
				$cancelled	=	$this->Mmm->dbUpdate("am_fixed_asset_disposals", $update, $id, "Cancelled Disposal Slip with transaction code no.".$id);
				if($cancelled){
					$control_number = $disposal->control_number;
					$company =  $this->Abas->getCompany($disposal->company_id);
					$notif_msg	=	"Disposal Slip with Control No. ".$control_number." under ".$company->name." has been sucessfully cancelled by ".$_SESSION['abas_login']['fullname'];
					$this->Abas->sysNotif("Cancel Disposal Slip", $notif_msg,"Asset Management","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
			break;

			case 'verify':
				$disposal = $this->Asset_Management_model->getDisposalSlip($id);
				$update['status']			=	"For Approval";
				$update['verified_by']		=	$_SESSION['abas_login']['userid'];
				$update['verified_on']		=	date('Y-m-d H:i:s');
				$verified	=	$this->Mmm->dbUpdate("am_fixed_asset_disposals", $update, $id, "Verified Disposal Slip with transaction code no.".$id);
				if($verified){
					$control_number = $disposal->control_number;
					$company =  $this->Abas->getCompany($disposal->company_id);
					$notif_msg	=	"Disposal Slip with Control No. ".$control_number." under ".$company->name." has been sucessfully verified by ".$_SESSION['abas_login']['fullname'];
					$this->Abas->sysNotif("Verify Disposal Slip", $notif_msg,"Asset Management","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
			break;

			case 'approve':
				$disposal = $this->Asset_Management_model->getDisposalSlip($id);
				$update['status']			=	"Approved";
				$update['approved_by']		=	$_SESSION['abas_login']['userid'];
				$update['approved_on']		=	date('Y-m-d H:i:s');
				$approved	=	$this->Mmm->dbUpdate("am_fixed_asset_disposals", $update, $id, "Approved Disposal Slip with transaction code no.".$id);
				if($approved){
					$disposal_details	=	$this->Asset_Management_model->getDisposalSlipDetails($id);
					foreach($disposal_details as $row){
						$update2['status']			=	"Disposed";
						$approved2	=	$this->Mmm->dbUpdate("am_fixed_assets", $update2, $row->asset_id, " Asset(s) were marked as 'Disposed' on Disposal Slip with transaction code no.".$id);
					}
					$control_number = $disposal->control_number;
					$company =  $this->Abas->getCompany($disposal->company_id);
					$notif_msg	=	"Disposal Slip with Control No. ".$control_number." under ".$company->name." has been sucessfully approved by ".$_SESSION['abas_login']['fullname'];
					$this->Abas->sysNotif("Approve Disposal Slip", $notif_msg,"Asset Management","info");
					$this->Abas->sysMsg("sucmsg", $notif_msg);
				}else{
					$this->Abas->sysMsg("errmsg", "An error ocurred while updating the record. Please contact your administrator.");
				}
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
			break;

			default:
				return NULL;
			break;
		}
	}

	public function corporate_financial_summary(){
		$data = array();
		$data['companies'] = $this->Abas->getCompanies();
		$data['viewfile']			=	"manager/reports/corporate_financial_summary.php";
		$mainview					=	"gentlella_container.php";
		$this->load->view($mainview, $data);
	}

	public function material_aging_requests_report($request_id=NULL){
		$data = array();
		if($request_id==NULL){
			$data['aging_list'] 		= 	$this->Manager_model->getMaterialAgingRequests();
			$data['viewfile']			=	"manager/reports/material_aging_requests_report.php";
			$mainview					=	"gentlella_container.php";
		}else{
			$data['request_data'] 		= $this->Purchasing_model->getRequest($request_id);
			$mainview					=	"manager/reports/material_aging_requests_view.php";
		}
		$this->load->view($mainview,$data);
	}
	public function vessel_expenses_report($action="",$id=NULL){
		$data['vessels'] = $this->Abas->getVessels(false);
		if($action=="filter"){
			$this->load->view('manager/reports/vessel_expenses_filter.php',$data);
		}elseif($action=="result"){
			if($_POST['vessel']){
				$vessel_id = $this->Mmm->sanitize($_POST['vessel']);
				$data['vessel'] = $this->Abas->getVessel($vessel_id);
				$data['type']= $this->Mmm->sanitize($_POST['type']);
				$data['date_from'] = $this->Mmm->sanitize($_POST['date_from']);
				$data['date_to'] = $this->Mmm->sanitize($_POST['date_to']);
				$data['expenses'] = $this->Manager_model->getVesselExpenses($vessel_id,$data['type'],$data['date_from'],$data['date_to']);
			}
			$data['viewfile'] = 'manager/reports/vessel_expenses_report.php';
			$this->load->view('gentlella_container.php',$data);
		}
	}
	public function vessel_repairs_statistics($action="",$id=NULL){
		$data['vessels'] = $this->Abas->getVessels(false);
		if($action=="filter"){
			$this->load->view('manager/reports/vessel_repairs_statistics_filter.php',$data);
		}elseif($action=="result"){
			if($_POST['vessel']){
				$vessel_id = $this->Mmm->sanitize($_POST['vessel']);
				$schedule_log_id = $this->Mmm->sanitize($_POST['project_reference']);
				$data['vessel'] = $this->Abas->getVessel($vessel_id);
				$data['date_from'] = $this->Mmm->sanitize($_POST['date_from']);
				$data['date_to'] = $this->Mmm->sanitize($_POST['date_to']);
				$data['jo_expenses'] = $this->Manager_model->getJobOrderExpenseSummary($vessel_id,$data['date_from'],$data['date_to']);
				$data['po_expenses'] = $this->Manager_model->getPurchaseOrderExpenseSummary($vessel_id,$data['date_from'],$data['date_to']);
				$data['issuance_expenses'] = $this->Manager_model->getIssuanceExpenseSummary($vessel_id,$data['date_from'],$data['date_to']);
				if($schedule_log_id==''){
					$data['bom'] = $this->Manager_model->getBOMAmountByVessel($vessel_id,$data['date_from'],$data['date_to']);
				}else{
					$query = $this->db->query("SELECT DISTINCT bill_of_materials_id FROM am_schedule_log_tasks WHERE schedule_log_id=".$schedule_log_id);
					$boms = $query->result();
					$amount = 0;
					$total_bom_amount = 0;
					foreach($boms as $bom){
						$amount = $this->Asset_Management_model->getBOMAmount($bom->bill_of_materials_id);
						$total_bom_amount = $total_bom_amount + $amount;
					}
					$data['bom']['grand_total_amount'] = $total_bom_amount;
				}
			}
			$data['viewfile'] = 'manager/reports/vessel_repairs_statistics_report.php';
			$this->load->view('gentlella_container.php',$data);
		}
	}
	public function vessel_project_references($vessel_id){
		$references = $this->Asset_Management_model->getProjectReferencesByVessel($vessel_id);
		echo json_encode( $references );
	}
	public function project_reference_dates($schedule_id){
		$tasks = $this->Asset_Management_model->getScheduleLogTasks($schedule_id);
		$actual_start_dates = array();
		$actual_end_dates = array();
		foreach($tasks as $task){
			array_push($actual_start_dates,$task['actual_start_date']);
			array_push($actual_end_dates,date('Y-m-d',strtotime($task['actual_start_date']."+ ".$task['actual_work_duration']." day")));
		}
		$data = array();
		$data['from_date'] = min($actual_start_dates);
		$data['to_date'] = max($actual_end_dates);
		echo json_encode( $data );
	}

	public function getPaymentTo($type,$id)
	{
		if($type == 'Employee')
		{
			$var = $this->Abas->getEmployee($id);
			return $var->full_name;
		}
		elseif($type == 'Supplier')
		{
			$var = $this->Abas->getSupplier($id);
			return $var->name;
		}
		else
		{
			return 'Undefined Payee Type';
		}
	}


	public function rfp_view($filter = false)
	{
		if(isset($_POST['filter'])){
			redirect(HTTP_PATH.'manager/rfp_view/'.$_POST['filter']);
		}

		$items = $this->Manager_model->getRFP($filter);
		foreach ($items as $ctr => $row) {
			$company = $this->Abas->getCompany($row->company_id);
			$prepared_by = $this->Abas->getUser($row->created_by);
			$created_on = strtotime($row->created_on);
			$verified_by = $this->Abas->getUser($row->verified_by);
			$verified_on = strtotime($row->verified_on);
			$approved_by = $this->Abas->getUser($row->approved_by);
			$approved_on = strtotime($row->approved_on);

			$rfp[$ctr] = [
				'transaction_code' => $row->id,
				'control_number'   => $row->control_number,
				'company'		   => $company->name,
				'payee_type'	   => $row->payee_type,
				'payment_to'	   => $this->getPaymentTo($row->payee_type,$row->payment_to),
				'purpose'		   => $row->purpose,
				'prepared_by'	   => $prepared_by['full_name'],
				'created_on'	   => date("j F Y h:i A",$created_on),
				'verified_by'	   => $verified_by['full_name'],
				'verified_on'	   => date("j F Y h:i A",$verified_on),
				'approved_by'	   => $approved_by['full_name'],
				'approved_on'	   => date("j F Y h:i A",$approved_on),
				'amount'		   => number_format($row->amount,2),
				'status'		   => $row->status
			];
		}
		$data['rfp'] = $rfp;
		
		$data['viewfile'] = 'manager/request_for_payment_approval/per_manager.php';
		$this->load->view('gentlella_container.php',$data);
	}
}
?>