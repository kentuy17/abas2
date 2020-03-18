<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('directory');

		$this->load->model("Abas");
		$this->load->model("Mmm");
		$this->load->model("Accounting_model");
		$this->load->model("Purchasing_model");
		$this->load->model("Operation_model");
		$this->load->model("Inventory_model");
		$this->load->model("Admin_model");
		$this->load->model("Accounting_model");
		$this->load->model("Finance_model");

		$this->output->enable_profiler(FALSE);

		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home");}
		define("SIDEMENU", "Manager's Dashboard");
		//var_dump($_SESSION['abas_login']['username']);
	}

	public function index()
	{
		//set default tab

		if(isset($_SESSION['tab'])){
			$data['tab']= $_SESSION['tab'];
		}else{
			$data['tab']= 'request';
		}
		//var_dump($_SESSION['abas_login']['userid']);
		//get all request
			$sql = "SELECT id FROM inventory_requests WHERE (approved_by = ".$_SESSION['abas_login']['userid']." OR approved_by = 0) AND stat=1 ORDER BY tdate DESC LIMIT 100";
			$requests		=	$this->db->query($sql);
			$requests		=	$requests->result_array();
			$sorted			=	array();
			foreach($requests as $rctr=>$request) {
				$request			=	$this->Purchasing_model->getRequest($request['id']);
				$requests[$rctr]	=	$request;
				$sorted[$request['status']][$request['priority']][]	=	$request;
			}

		$data['requests'] = $requests;


		$for_canvass_approval = " AND status LIKE 'For canvass approval'"; // mmmaske: changed '=' to 'LIKE' to avoid case-sensitivity
		$data['canvassCount'] = $this->Admin_model->getRequestDistinctItems($for_canvass_approval);
		$data['canvass'] = $this->Admin_model->getCanvassForApproval();
		$data['purchase_orders'] = $this->Admin_model->getPOForApproval();
		$data['job_orders'] = $this->Admin_model->getJOForApproval();
		
		//get vessels
		$data['vessels'] = $this->Abas->getVessels();
		
		$data['viewfile']			=	"admin/bnv_dashboard.php";
		$mainview					=	"gentlella_container.php";
		$this->load->view($mainview, $data);

	}

	public function po_approval_form($id='')
	{
		$data['po'] = $this->Purchasing_model->getPurchaseOrder($id);
		$data['request'] = $this->Purchasing_model->getRequest($data['po']['request_id']);
		$this->load->view('admin/po_approval_form', $data);
	}
	public function jo_approval_form($id='')
	{
		$data['jo'] = (array)$this->Purchasing_model->getJobOrder($id);
		$data['jo_details'] = $this->Purchasing_model->getJobOrderDetails($id);
		$data['request'] = $this->Purchasing_model->getRequest($data['jo']['request_id']);
		$this->load->view('admin/jo_approval_form', $data);
	}
	public function request_approval_form($id='')
	{

		$data['request'] = $this->Purchasing_model->getRequest($id);

		$sql_append = " AND status != 'Cancelled' AND supplier_id = 0";
		$data['request_details'] = $this->Purchasing_model->getRequestDetails($id,$sql_append);
		//var_dump($data['request_details']); exit;
		$this->load->view('admin/request_approval_form', $data);

	}
	public function canvass_approval_form($id='')
	{

		if($id!=''){
			$data['csummary'] = $this->Purchasing_model->getRequest($id);

			$sql_append = " AND status LIKE 'For canvass approval'";
			$data['cdetail'] = $this->Admin_model->getRequestDetailDistinctItems($id, $sql_append);
			//var_dump($data['cdetail']); exit;
			//$vname = $this->Abas->getVessel($data['csummary']['vessel']);
			$this->load->view('admin/canvass_approval_form', $data);

		}else{
			$this->Abas->redirect(HTTP_PATH."admin/");
		}


	}

	public function request_approve()
	{
		//var_dump($_POST); exit;
		if(isset($_POST)){

			$rid = $_POST['rid'];
			$items = $_POST['approvedItems'];
			$number_of_items = $_POST['number_of_items'];

			$itemGroup = explode(",",$items);
			$ctr = count($itemGroup);

			for($i=0;$i < $ctr; $i++){

				//update request/item status
				$sql = "UPDATE inventory_request_details
						SET status = 'For canvassing',
							request_approved_by = '".$_SESSION['abas_login']['userid']."',

							request_approved_on = '".date('Y-m-d h:m:s')."'

						WHERE id = $itemGroup[$i]
						AND status = 'For Request Approval'"; //AND status = 'For Request Approval'

				$db = $this->Mmm->query($sql,"item: ".$itemGroup[$i]." was approved by ".$_SESSION['abas_login']['userid']." for canvass");


			}

				//check total items in this request
				//if($number_of_items == $ctr){  //will remove this and see the effect
				if($db){
					//update summary status
					$sql1 = "UPDATE inventory_requests
							SET status = 'For canvassing'
							WHERE id = $rid
						";
					/*$sql1 = "	SELECT v.name FROM inventory_requests AS r
								INNER JOIN vessels AS v ON 	r.vessel_id=v.id
								WHERE id = $rid
						"; 	*/
					$db = $this->Mmm->query($sql1, 'Request #'.$rid.' was approved by  '.$_SESSION['abas_login']['username']);

				}

				$this->Abas->sysMSg("msg", "Requested items has been approved for canvass!");


		}
		$_SESSION['tab'] = 'request';
		// $data['msg'] = $msg;
		$this->Abas->redirect(HTTP_PATH."admin/");


	}

	public function po_approve()
	{

		if(isset($_POST)){

			$po_id = $_POST['po_id'];

				$sql = "UPDATE inventory_po
						SET status = 'For ordering',
							approved_by = '".$_SESSION['abas_login']['userid']."',
							approved_on = '".date('Y-m-d h:m:s')."'

						WHERE id = $po_id"; //AND status = 'For Request Approval'


				$db = $this->Mmm->query($sql,"Purchase Order with transaction code: ".$po_id." was approved by ".$_SESSION['abas_login']['username']." for ordering");

				if($db){
					$this->Abas->sysMSg("msg", "Purchase Order with transaction code ".$po_id." has been approved.");
				}else{
					$this->Abas->sysMSg("errmsg", "Purchase order approval encountered problem, please contact Admin.");
				}

		}





		$_SESSION['tab'] = 'purchase_order';
		// $data['msg'] = $msg;
		$this->Abas->redirect(HTTP_PATH."admin/");


	}

	public function jo_approve()
	{
		
		if(isset($_POST)){

			$jo_id = $_POST['jo_id'];
			
				$sql = "UPDATE inventory_job_orders
						SET status = 'For ordering',
							approved_by = '".$_SESSION['abas_login']['userid']."',
							approved_on = '".date('Y-m-d h:m:s')."'
							
						WHERE id = $jo_id"; //AND status = 'For Request Approval'
				
								
				$db = $this->Mmm->query($sql,"Job Order with transaction code: ".$jo_id." was approved by ".$_SESSION['abas_login']['username']." for ordering");
				
				if($db){
					$this->Abas->sysMSg("msg", "Job Order with transaction code ".$jo_id." has been approved.");
				}else{
					$this->Abas->sysMSg("errmsg", "Job order approval encountered problem, please contact Admin.");
				}

		}

		
		$_SESSION['tab'] = 'job_order';
		$this->Abas->redirect(HTTP_PATH."admin/");

	}

	
	public function canvass_approve()
	{

		if($_POST){

			$items = $_POST;
			$request_id = $_POST['request_id'];
			$item_count = $_POST['item_count'];
			$cnt = 0;

			for($i=0;$i < $item_count ; $i++){

				$name = 'item'.$i;
				$detail_id = $_POST[$name];


				// fixed updating of status
				$canvass			=	$this->Purchasing_model->getRequestDetail($detail_id);

				$request			=	$this->Purchasing_model->getRequest($canvass['request_id']);

				$item				=	$this->Inventory_model->getItem($canvass['item_id']);
				$item				=	$item[0];

				$sql				=	"UPDATE inventory_request_details SET status='unselected' WHERE (supplier_id <> ".$canvass['supplier_id']." OR supplier_id <> 0) AND item_id=".$item['id']." AND request_id=".$request['id']." AND status LIKE 'for canvass approval'";
				$unselectCanvass	=	$this->Mmm->query($sql, 'Canvass unselected by '.$_SESSION['abas_login']['username']);
				//var_dump($sql);


				$sql1				=	"	UPDATE inventory_request_details
											SET status='for purchase',
												canvass_approved_by =  '".$_SESSION['abas_login']['userid']."',
												canvass_approved_on =  '".date('Y-m-d h:m:s')."'
											WHERE (supplier_id=".$canvass['supplier_id']." OR supplier_id=0) AND item_id=".$item['id']." AND request_id=".$request['id'] ;
				//var_dump($sql1); exit;
				$checkCanvass		=	$this->Mmm->query($sql1, 'Canvass for request# '.$request_id.' was  approved by '.$_SESSION['abas_login']['username']);
				//var_dump($checkCanvass); exit;
				if($checkCanvass) {
					//var_dump($canvass); exit;
					$this->Abas->sysMsg("sucmsg", "Canvass for ".$item['description']." was approved by ".$_SESSION['abas_login']['username']."!");

				}else{

					//revert back to old status
					$sql				=	"UPDATE inventory_request_details SET status='for canvass approval' WHERE (supplier_id<>".$canvass['supplier_id']." OR supplier_id<>0) AND item_id=".$item['id']." AND request_id=".$request['id'];
					$revertCanvass	=	$this->Mmm->query($sql, 'Canvass reverted to previous status');


					$this->Abas->sysMsg("errmsg", "Error occured in canvass approval."); }

				}
				//update primary table if all items are approved
				// revised to update primary table, problem with presentation on admin approval part
				//if($cnt == $item_count){

						/* NO need, quesries will be based on the details table 3/22/2017
						$sql = "UPDATE inventory_requests
							SET status = 'For purchase'
							WHERE id = $request_id
						";
						$db = $this->db->query($sql);
						*/
						/*UPDATE status of default item (for Maske)  ? need to review this
						$sql = "UPDATE inventory_request_details
								SET status = 'For purchase'
								WHERE request_id = $request_id
								AND item_id =". $d['item_id']."
								AND status != 'unselected'" ;
						$db = $this->db->query($sql);
						*/

				//}

			//$this->Abas->sysMSg("msg", "Selected items has been approved!");

		}

		$_SESSION['tab'] = 'canvass';
		// $data['msg'] = $msg;
		//redirect('admin/'.$tab);
		$this->Abas->redirect(HTTP_PATH."admin");


	}
	public function canvass_disapprove()
	{

		$ret = FALSE;

		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$update['status'] = 'disapproved';
			$update['remark'] =	'disapproved';
			$sql	=	$this->Mmm->dbUpdate('inventory_request_details', $update, $id, "Disapproved request detail id: ".$id);

			if(!$sql){
				$ret = false;
				exit;
			}

			$ret = $update['remark'];
		}
		//var_dump($ret); exit;
		echo $ret;

	}

	public function voucher_approval_form($id='')
	{

		if($id !=''){
		//get the voucher for presentation
			$data['voucher'] = $this->Accounting_model->getVoucherInfo($id);

			$transaction_type = $data['voucher'][0]['transaction_type'];

			if($transaction_type == 'Purchase Order'){
				$data['delivery_summary'] = $this->Accounting_model->getDeliveryByVoucherId($id);
				$data['delivery_detail'] = $this->Inventory_model->getDeliveryDetails($data['delivery_summary'][0]['id']);
			}

			$this->load->view('admin/voucher_approval_form',$data);

		}else{
			$this->index();
		}



	}

	public function voucher_approve()
	{

		if($_POST){

			//var_dump($_POST); exit;
			$voucher_info = $this->Accounting_model->getVoucherInfo($_POST['voucher_id']);

			$insert['id']	=	0;
			$insert['voucher_id']	=	$this->Mmm->sanitize($_POST['voucher_id']);
			$insert['approved_by']	=	$this->Mmm->sanitize($_POST['user_id']);
			$insert['approved_on']	=	date("Y-m-d H:i:s");

			$db	=	$this->Mmm->dbInsert('ac_voucher_approvals', $insert, "Voucher approval");
			//save approval to ac_voucher_approvals


			if($db){
				//note there should be at least 2 approvals before release of the payment
				//check if there are 2 approvals, if yes, update the voucher for release
				$count = $this->Admin_model->checkVoucherApprover($_POST['voucher_id']);
				//update status of delivery table

				if(count($count) > 1){
					$update['status'] = 'For voucher printing';
					$sql	=	$this->Mmm->dbUpdate('ac_vouchers', $update, $_POST['voucher_id'], "Update voucher status");

					if($voucher_info[0]['transaction_type'] == 'Cash Request'){
						$update['status'] = 'For voucher printing';

						$sql	=	"UPDATE ac_cash_advances SET status = 'For voucher printing' WHERE voucher_id = ".$_POST['voucher_id'];
						$this->Mmm->query($sql, "Update cash request status");
					}
				}

				$this->Abas->sysMSg("msg", "Voucher has been approved!");
			}

		}

		$_SESSION['tab'] = 'voucher';
		$this->Abas->redirect(HTTP_PATH."admin");

	}

	public function updatePO_Table() {

		$ret					=	null;

		//$sql 					= "SELECT *  FROM inventory_requests WHERE status='For Canvass Approval' ".$sqlappend;
		$sql 					= "SELECT r.request_id, canvass_approved_on, canvass_approved_by, p.id as po_id FROM `inventory_request_details` as r INNER JOIN inventory_po as p ON r.request_id = p.request_id WHERE canvass_approved_by != ''";
		$db						= $this->db->query($sql);
		if(!$db) 			{ return false; }
		$res					= $db->result_array();



		foreach($res as $r){

			$approve_on = $r['canvass_approved_on'];
			$approve_by = $r['canvass_approved_by'];
			$po_id = $r['po_id'];

			$sq = "UPDATE inventory_po SET approved_by =".$approve_by." , approved_on = '".$approve_on."', status = 'For ordering' WHERE id =".$po_id;

			$d = $this->db->query($sq);

			if($d){
				var_dump($sq);
			}else{
				echo 'Error on:'.$po_id;
			}
			echo '<br>';

		}

		/*
		$ctr=0;
		$arr	 = array();

		foreach($res as $r){

			$sql1	= "SELECT *  FROM inventory_po WHERE id=".$r['id'];
			$d		= $this->db->query($sql1);
			$arr[$ctr]	= $d->result_array();
			$ctr++;

		}
		*/
		//return $res;
	}


	##########################################################
	//REPORT AND MONITORING
	##########################################################

	public function aging_requests()
	{
			//get aging requests
			$data['aging_list'] 		= 	$this->Admin_model->getAgingRequests();

			$data['viewfile']			=	"admin/report_aging_requests.php";
			$mainview					=	"gentlella_container.php";

			$this->load->view($mainview, $data);

	}

	public function request_detail_view($id='')
	{
			if($id !=''){
				//get aging requests
				$data['request_data'] = $this->Purchasing_model->getRequest($id);
				$this->load->view('admin/request_detail_view.php', $data);

			}else{
					echo "	<script>
                			alert('Missing reference, please contact admin.');
                		</script>
                	";

					die();

			}
	}
	public function financial_summary(){
		$data = array();
		$data['companies'] = $this->Abas->getCompanies();
		$data['viewfile']			=	"admin/financial_summary.php";
		$mainview					=	"gentlella_container.php";
		$this->load->view($mainview, $data);
	}

}
?>
