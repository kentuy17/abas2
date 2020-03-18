<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class inventory extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			$this->load->library('form_validation');
			$this->load->helper('url');
			$this->load->database();
			$this->load->model("Abas");
			$this->load->model("Mmm");
			$this->load->model("Inventory_model");
			$this->load->model("Accounting_model");
			$this->load->model("Purchasing_model");
			$this->output->enable_profiler(FALSE);
			if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
			if($_SESSION['abas_login']['user_location']=='') {
				$this->Abas->sysMsg("errmsg", "User location is not set, please contact admin to set your location");
				$this->Abas->redirect(HTTP_PATH."home");
			}
			define("SIDEMENU", "Inventory");
		}
		public function index()	{$data=array();
			//$this->Abas->redirect(HTTP_PATH."inventory/item_list");
			$this->Abas->redirect(HTTP_PATH."inventory/items/listview");//new inventory listview
			$data['items']		=	$this->Inventory_model->getItems();
			$mainview			=	"gentlella_container.php";
			$this->load->view($mainview,$data);
		}
		public function item_list() {$data=array();
			$data['viewfile']	=	"inventory/item_list.php"; // old inventory listview
			$mainview			=	"gentlella_container.php";
			$this->load->view($mainview,$data);
		}
		public function inventory_transaction()	{$data=array();
			$data['pos']		=	$this->Inventory_model->getPos();
			$data['requests']	=	$this->Inventory_model->getRequests();
			$data['receiving']	=	$this->Inventory_model->getDeliveries();
			$data['issuance']	=	$this->Inventory_model->getIssuances();
			$data['transfer']	=	$this->Inventory_model->getTransfers();
			$data['viewfile']	=	"inventory/inventory_transactions.php";
			$this->load->view('container.php',$data);
		}
		public function inventory_request()	{$data=array();
			$data['items']		=	$this->Inventory_model->getItemRequest();
			$data['viewfile']	=	"inventory/inventory_request.php";
			$this->load->view('container.php',$data);
		}
		public function viewRequest()	{$data=array();
			$data['requests']	=	$this->Inventory_model->getRequests();
			$data['viewfile']	=	"inventory/viewRequest.php";
			$this->load->view('container.php',$data);
		}
		public function view_all_items() {
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$search	=	isset($_GET['search'])?$_GET['search']:"";
			$data	=	$this->Inventory_model->getAllItems($search,$limit,$offset,$order,$sort);
			if($data!=false) {
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			}
		}
		public function return_view()	{$data=array();
			$data['location']			=	$_SESSION['abas_login']['user_location'];
			$data['inventory_return']	=	$this->Inventory_model->getInventoryReturn();
			$data['viewfile']			=	"inventory/return_view.php";
			$mainview					=	"gentlella_container.php";
			$this->load->view($mainview,$data);
		}
		public function return_form($id='')	{$data=array();
			$data['vessels']			=	$this->Abas->getVessels();
			$data['locals']				=	$this->Inventory_model->getInventoryLocation();
			$data['companies']			=	$this->Abas->getCompanies();
			$data['items']				=	$this->Inventory_model->getItems();
			$data['units']				=	$this->Inventory_model->getUnits();
			$data['classifications']	=	$this->Accounting_model->getExpenseClassification();
			$this->load->view('inventory/return_form',$data);
		}
		public function transfer_view()	{$data=array();
			$data['location']			=	$_SESSION['abas_login']['user_location'];
			$data['inventory_transfer']	=	$this->Inventory_model->getInventoryTransfer();
			$data['viewfile']			=	"inventory/transfer_viewer.php";
			$mainview					=	"gentlella_container.php";
			$this->load->view($mainview,$data);
		}
		public function transfer_form($id='')	{$data=array();
			$data['vessels']			=	$this->Abas->getVessels();
			$data['locals']				=	$this->Inventory_model->getInventoryLocation();
			$data['companies']			=	$this->Abas->getCompanies();
			$data['items']				=	$this->Inventory_model->getItems();
			$data['units']				=	$this->Inventory_model->getUnits();
			$data['classifications']	=	$this->Accounting_model->getExpenseClassification();
			$this->load->view('inventory/transfer_form',$data);
		}
		public function transfer_receiving($id='')	{$data=array();
			$data['summary']	=	$this->Inventory_model->getTransfer($id);
			$data['details']	=	$this->Inventory_model->getTransferDetails($id);
			$data['company']	=	$this->Abas->getCompany($data['summary'][0]['company_id']);
			$this->load->view('inventory/transfer_receiving',$data);
		}
		public function receiving_form($id='')	{$data=array();
			if($id!=''){
				$data['item']		=	$this->Inventory_model->getItems($id);
				$data['supplier']	=	$this->Inventory_model->getSuplpiers($id);
			}
			$data['vessels']	=	$this->Abas->getVessels();
			$data['suppliers']	=	$this->Abas->getSuppliers();
			$data['categories']	=	$this->Inventory_model->getCategories();
			$data['items']		=	$this->Inventory_model->getItems();
			$data['units']		=	$this->Inventory_model->getUnits();
			$this->load->view('inventory/receiving_form',$data);
		}
		public function addTransferReceiving()	{$data=array();
			$msg	='';
			if(isset($_POST)){
				$received_date		=	date('Y-m-d h:m:s');
				$transfer_id		=	$this->Mmm->sanitize($_POST['transfer_id']);
				$receiving_remark	=	$this->Mmm->sanitize($_POST['receiving_remark']);
				$received_by		=	$_SESSION['abas_login']['userid'];
				if($transfer_id	!=	''){
					$transfer_summary	=	$this->Inventory_model->getInventoryTransfer($transfer_id);
					$update['receiving_remark']				=	$receiving_remark;
					$update['received_date']				=	date('Y-m-d h:m:s');
					$update['received_by']					=	$received_by;
					$update['is_received']					=	1;
					$sql	=	$this->Mmm->dbUpdate('inventory_transfer', $update, $transfer_id, "Inventory transfer has been received by ".$_SESSION['abas_login']['username']);
					if($sql==true) {
						//get detail
						$transfer_detail	=	$this->Inventory_model->getTransferDetails($transfer_id);
						foreach($transfer_detail as $transfer){
							$transfered_qty	=	$transfer['qty'];
							$item_id	=	$transfer['item_id'];
							$cur_qty	=	$this->Inventory_model->getItemQty($item_id);
							switch ($transfer_summary[0]['to_location']) {
								case 'Makati':
								$loc_qty	=	($cur_qty[0]['mkt_qty']) + $transfered_qty;
								$sqUp	=	"UPDATE inventory_location SET mkt_qty	=	$loc_qty";
								break;
								case 'NRA':
								$loc_qty	=	($cur_qty[0]['nra_qty']) + $transfered_qty;
								$sqUp	=	"UPDATE inventory_location SET nra_qty	=	$loc_qty";
								break;
								case 'Tayud':
								$loc_qty	=	($cur_qty[0]['tayud_qty']) + $transfered_qty;
								$sqUp	=	"UPDATE inventory_location SET tayud_qty	=	$loc_qty";
								break;
								case 'Tacloban':
								$loc_qty	=	($cur_qty[0]['tac_qty']) + $transfered_qty;
								$sqUp	=	"UPDATE inventory_location SET tac_qty	=	$loc_qty";
								break;
								case 'Direct Delivery':
								$loc_qty	=	($cur_qty[0]['direct_qty']) + $transfered_qty;
								$sqUp	=	"UPDATE inventory_location SET direct_qty	=	$loc_qty";
								break;
							}
							$sqUp .=" WHERE item_id	=	$item_id";
							$rUp	=	$this->db->query($sqUp);
							if(!$rUp){
								$this->Abas->sysMsg("warnmsg", "Problem occured in updating current quantity for ".$transfer_summary[0]['to_location']." , please contact admin.");
							}
						}
						$this->Abas->sysMsg("sucmsg", "Inventory transfer from ".$transfer_summary[0]['from_location']." has been received by ".$_SESSION['abas_login']['username']);
					}
				}
				else {
					$this->Abas->sysMsg("warnmsg", "Problem occured in inventory transfer receiving: TRANSACTION CODE: ".$transfer_id.", please contact admin.");
				}
			}
			$this->Abas->redirect(HTTP_PATH."inventory/transaction_history/transfer");
		}
		public function issuance_form($id='')	{$data=array();
			if($id!=''){
				$data['item']		=	$this->Inventory_model->getItems($id);
				$data['supplier']	=	$this->Inventory_model->getSuppliers($id);
			}
			$data['vessels']		=	$this->Abas->getVessels();
			$data['categories']		=	$this->Inventory_model->getCategories();
			$data['items']			=	$this->Inventory_model->getItems();
			$data['units']			=	$this->Inventory_model->getUnits();
			$data['units']			=	$this->Inventory_model->getUnits();
			$this->load->view('inventory/issuance_form',$data);
		}
		public function audit_view($id='')	{$data=array(); //auditing
			if($id!=''){
				$data['item']	=	$this->Inventory_model->getItem($id);
			}

			$data['categories']	=	$this->Inventory_model->getCategories();
			$data['items']		=	$this->Inventory_model->getItems();
			$data['units']		=	$this->Inventory_model->getUnits();
			$data['viewfile']	=	"inventory/audit_view.php";
			$mainview			=	"gentlella_container.php";
			$this->load->view($mainview,$data);
		}
		public function print_audit_form()	{$data=array();
			$data['items']	=	$this->Inventory_model->getItems();
			$this->load->view('inventory/print_audit_form',$data);
		}
		public function add_audit()	{
			if(!isset($_SESSION['abas_login']['user_location'])){
				$this->Abas->sysMSg("msg", "User location is required for this module, please contact Avega IT.");
				$this->Abas->redirect(HTTP_PATH."inventory/audit_view");
			}
			$location	=	$_SESSION['abas_login']['user_location'];
			$counted_by	=	$_POST['counted_by'];
			$ctr		=	2;
			$rec='';
			foreach($_POST as $key=>$value){
				$i	=	explode('_',$key);
				$item_id	=	$i[0];
				if($ctr>3){
					if($value!=''){
						if(strpos($key, 'counted')	==	true) {
							$rec	=	$rec.'|'.$value;
						}
						elseif(strpos($key, 'diff')	==	true) {
							$rec	=	$rec.'|'.$value;
						}
						elseif(strpos($key, 'remark')	==	true) {
							$rec	=	$rec.'|'.$value;
						}
						if($ctr%3==0) {
							$rec	=	$rec.'|'.$item_id.'-';
						}
					}
				}
				$ctr++;
			}
			$this->db->trans_start();
			$insert['audited_by']	=	$this->Mmm->sanitize($_POST['counted_by']);
			$insert['audit_date']	=	date("Y-m-d H:i:s");
			$insert['location']		=	$location;
			$insert['stat']			=	1;
			$sql					=	$this->Mmm->dbInsert('inventory_audit', $insert, "New audit added.");
			$last_id				=	$this->db->insert_id();
			$items					=	explode('-',$rec);
			$count					=	count($items) - 1;
			for($i=0 ; $i < $count; $i++){
				$det		=	explode('|', $items[$i]); //getting error here need to review procedure
				$counted	=	$det[1];
				$difference	=	($det[2]!=0)?$det[2]:0;
				$remark		=	($det[3]!='na')?$det[3]:'';
				$item_id	=	$det[4];
				$qty		=	'';
				$cur_qty	=	$this->Inventory_model->getItemQty($item_id);
				if($counted!=0){
					switch ($location) {
						case 'Makati':
						$c_qty	=	$cur_qty[0]['mkt_qty'];
						$sqUp	=	"UPDATE inventory_location SET mkt_qty	=	$counted";
						break;
						case 'NRA':
						$c_qty	=	$cur_qty[0]['nra_qty'];
						$sqUp	=	"UPDATE inventory_location SET nra_qty	=	$counted";
						break;
						case 'Tayud':
						$c_qty	=	$cur_qty[0]['tayud_qty'];
						$sqUp	=	"UPDATE inventory_location SET tayud_qty	=	$counted";
						break;
						case 'Tacloban':
						$c_qty	=	$cur_qty[0]['tac_qty'];
						$sqUp	=	"UPDATE inventory_location SET tac_qty	=	$counted";
						break;
					}
					$sqlChk	=	"SELECT * FROM inventory_location WHERE item_id	=	".$item_id;
					$db_chk	=	$this->db->query($sqlChk);
					if($db_chk->num_rows==0){
						$insertChk['item_id']		=	$item_id;
						$insertChk['mkt_qty']		=	0;
						$insertChk['nra_qty']		=	0;
						$insertChk['tayud_qty']		=	0;
						$insertChk['tac_qty']		=	0;
						$insertChk['direct_qty']	=	0;
						$sq							=	$this->Mmm->dbInsert('inventory_location', $insertChk, "Item added in inventory location table.");
					}
					$sqUp		.=	' WHERE item_id	=	'.$item_id;
					$update		=	$this->db->query($sqUp);
					if($update){
						$insertDetail['audit_id']			=	$last_id;
						$insertDetail['item_id']			=	$item_id;
						$insertDetail['current_qty']		=	$c_qty;
						$insertDetail['counted_qty']		=	$counted;
						$insertDetail['remarks']			=	$remark;
						$insertDetail['stat']				=	1;
						$sql								=	$this->Mmm->dbInsert('inventory_audit_details', $insertDetail, "Audit detail added.");
					}
					else{
						$this->Abas->sysMsg("sucmsg", "Problem occured inserting audit details.");
					}
				}
			}
			$this->db->trans_complete();
			if ($this->db->trans_status()	===	FALSE) {
				$this->Abas->sysMsg("sucmsg", "Problem occured somewhere, please contact admin!");
			}
			else{
				$this->Abas->sysMsg("sucmsg", "Successfully added inventory audit");
			}
			$this->Abas->redirect(HTTP_PATH."inventory/audit_view");
		}
		public function addReturn()	{$data=array();
			$msg	='';
			if(isset($_POST)){
				$returned_from	=	$this->Mmm->sanitize($_POST['returned_from']);
				$returned_by	=	$this->Mmm->sanitize($_POST['return_by']);
				$to_location	=	$this->Mmm->sanitize($_POST['to_location']);
				$remark			=	$this->Mmm->sanitize($_POST['remark']);
				$items			=	$_POST['sels'];
				$return_date	=	date("Y-m-d", strtotime($_POST['return_date']));
				$vessel	=	$this->Abas->getVessel($returned_from);
				$company_id	= $vessel->company;
				if($to_location!=''){
					$tran_control_number	=	$this->Abas->getNextSerialNumber('inventory_return', $company_id);
					$sql					=	"INSERT INTO inventory_return(id, control_number,company_id,return_date,return_to,return_from,return_by,remark,stat) VALUES(0, '$tran_control_number','$company_id','$return_date','$to_location','$returned_from','$returned_by','$remark',1)";
					$r						=	$this->Mmm->query($sql, 'Inventory Return');
					if($r){
						$tran_id		=	$this->db->insert_id();
						$itemGroup		=	explode(",",$items);
						$ctr			=	count($itemGroup) - 1;
						$grand_total	=	0;
						for($i=0;$i<$ctr;$i++){
							$group			=	explode('|',$itemGroup[$i]);
							$item_id		=	$group[0];
							$qty			=	$group[1];
							$sql			=	"SELECT * FROM inventory_items WHERE id	=".$item_id;
							$db				=	$this->db->query($sql);
							$result			=	$db->result_array();
							$lineTotal		=	$qty * $result[0]['unit_price'];
							$grand_total	=	$lineTotal + $grand_total;
							$unit			=	$result[0]['unit'];
							$unit_price		=	$result[0]['unit_price'];
							
							$sqGet			=	"SELECT * FROM inventory_location WHERE item_id	=	$item_id";
							$rdb			=	$this->db->query($sqGet);
							$rGet			=	$rdb->result_array();
							switch ($to_location) {
								case 'Makati':
								$old_qty    = $rGet[0]['mkt_qty'];
								$loc_qty	=	($rGet[0]['mkt_qty']) + $qty;
								$sqUp		=	"UPDATE inventory_location SET mkt_qty	=	$loc_qty";
								break;
								case 'NRA':
								$old_qty    = $rGet[0]['nra_qty'];
								$loc_qty	=	($rGet[0]['nra_qty']) + $qty;
								$sqUp		=	"UPDATE inventory_location SET nra_qty	=	$loc_qty";
								break;
								case 'Tayud':
								$old_qty    = $rGet[0]['tayud_qty'];
								$loc_qty	=	($rGet[0]['tayud_qty']) + $qty;
								$sqUp		=	"UPDATE inventory_location SET tayud_qty	=	$loc_qty";
								break;
								case 'Tacloban':
								$old_qty    = $rGet[0]['tac_qty'];
								$loc_qty	=	($rGet[0]['tac_qty']) + $qty;
								$sqUp		=	"UPDATE inventory_location SET tac_qty	=	$loc_qty";
								break;
								case 'Direct Delivery':
								$old_qty    = $rGet[0]['direct_qty'];
								$loc_qty	=	($rGet[0]['direct_qty']) + $qty;
								$sqUp		=	"UPDATE inventory_location SET direct_qty	=	$loc_qty";
								break;
							}
							$sqUp .=" WHERE item_id	=	$item_id";
							$rUp	=	$this->db->query($sqUp);

							$sql3			=	"INSERT INTO inventory_return_details(id, return_id, item_id, unit, unit_price, qty,old_qty, stat) VALUES(0,$tran_id,$item_id,'$unit','$unit_price',$qty,$old_qty,1)";
							$db2			=	$this->Mmm->query($sql3, 'Inventory Return Details');

						}
						$this->Abas->redirect(HTTP_PATH."inventory/print_rt/".$tran_id);
					}
				}
			}
			$this->Abas->sysMsg("sucmsg", "New inventory transfer request has been created.");
			$this->Abas->redirect(HTTP_PATH."inventory/transfer_view");
		}
		public function addIssuance()	{$data=array();
			$msg	='';
			if(isset($_POST)){
				$request_date	=	date('Y-m-d h:m:s',strtotime($_POST['request_date']));
				$issued_to		=	$_POST['issued_to'];
				$vessel_id		=	$_POST['issued_for'];
				$company_id		=	$_POST['company_id'];
				$from_location	=	$_POST['location'];
				$remark			=	$_POST['remark'];
				$items			=	$_POST['sels'];
				$amount			=	0;
				$datenow		=	date('Y-m-d');
				if($vessel_id!=''){
					$table_name				=	'inventory_issuance';
					$company_identifier		=	$company_id;
					$issuance_control_no	=	$this->Abas->getNextSerialNumber($table_name, $company_identifier);
					$sql					=	"INSERT INTO inventory_issuance(id, issue_date, request_no, issued_to, vessel_id, from_location, stat, remark, control_number,is_cleared) VALUES(0, '$datenow','1','$issued_to','$vessel_id', '$from_location', 0, '$remark',$issuance_control_no,0)";
					$this->db->trans_start();
					$r						=	$this->Mmm->query($sql, 'New Issuance added');
					if($r){
						$issue_id		=	$this->db->insert_id();
						$itemGroup		=	explode(",",$items);
						$ctr			=	count($itemGroup) - 1;
						$grand_total	=	0;
						for($i=0;$i < $ctr; $i++){
							$group			=	explode('|',$itemGroup[$i]);
							$item_id		=	$group[0];
							$qty			=	$group[1];
							$unit_price		=	$group[2];
							$sql			=	"SELECT * FROM inventory_items WHERE id	=".$item_id;
							$db				=	$this->db->query($sql);
							$result			=	$db->result_array();
							//$lineTotal		=	$qty * $result[0]['unit_price'];	// revisit price, should implement FIFO method
							if($unit_price==''||$unit_price==0){
								$unit_price = $result[0]['unit_price'];
							}
							$lineTotal		=	$qty * $unit_price;
							$grand_total	=	$lineTotal + $grand_total;
							$unit			=	$result[0]['unit'];
							//$unit_price		=	$result[0]['unit_price'];
							$sql3			=	"INSERT INTO inventory_issuance_details(id, issuance_id, item_id, unit, unit_price, qty, stat) VALUES(0,$issue_id,$item_id,'$unit','$unit_price',$qty,0)";
							$db2			=	$this->Mmm->query($sql3, 'Issuance Detail');
							$sql4			=	"SELECT qty FROM inventory_items WHERE id=".$item_id;
							$db4			=	$this->db->query($sql4);
							$resqty			=	$db4->result_array();
							$actual_qty		=	($resqty[0]['qty']) - $qty;
							$sql5			=	"UPDATE inventory_items SET qty	=	$actual_qty WHERE id	=	$item_id";
							$r5				=	$this->db->query($sql5);
							$sqGet			=	"SELECT * FROM inventory_location WHERE item_id	=	$item_id";
							$rdb			=	$this->db->query($sqGet);
							$rGet			=	$rdb->result_array();
							switch ($from_location) {
								case 'Makati':
								$loc_qty	=	($rGet[0]['mkt_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET mkt_qty	=	$loc_qty";
								break;
								case 'NRA':
								$loc_qty	=	($rGet[0]['nra_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET nra_qty	=	$loc_qty";
								break;
								case 'Tayud':
								$loc_qty	=	($rGet[0]['tayud_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET tayud_qty	=	$loc_qty";
								break;
								case 'Tacloban':
								$loc_qty	=	($rGet[0]['tac_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET tac_qty	=	$loc_qty";
								break;
								case 'Direct Delivery':
								$loc_qty	=	($rGet[0]['direct_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET direct_qty	=	$loc_qty";
								break;
							}
							$sqUp .=" WHERE item_id	=	$item_id";
							$rUp	=	$this->db->query($sqUp);

							//deduct qty to inventory_quantity
							$sqlx = "UPDATE inventory_quantity SET quantity=(quantity-".$qty.") WHERE item_id=".$item_id." AND company_id=".$company_id." AND location='".$location."'";
							$queryx = $this->Mmm->query($sqlx,"Updated quantity on Item Inventory per company.");
						}
						$this->db->trans_complete();
						if ($this->db->trans_status()	===	FALSE) {
							$this->Abas->sysMsg("errmsg", "Problem occured somewhere, please contact admin!");
							$this->Abas->redirect(HTTP_PATH."inventory");
						}
						else{
							$gatepass	=	$_POST['gatepass'];
							if($gatepass==1){
								$gatepass_control_no	=	$this->Abas->getNextSerialNumber('inventory_gatepass', $company_id);
								$sqlg =	"INSERT INTO inventory_gatepass(id, vessel_id, control_number, issuance_id) VALUES(0, '$vessel_id','$gatepass_control_no','$issue_id')";
								$g	=	$this->Mmm->query($sqlg, 'New Gate Pass added');
							}
							$this->Abas->sysMsg("sucmsg", "New issuance added.");
						}
						$this->Abas->redirect(HTTP_PATH."inventory/print_is/".$issue_id."/0/".$gatepass);
					}
				}
			}
			$data['msg']	=	$msg;
			$this->Abas->redirect(HTTP_PATH."inventory");
		}
		public function addTransfer()	{$data=array();
			$msg	='';
			if(isset($_POST)){
				$transfered_by	=	$this->Mmm->sanitize($_POST['transfered_by']);
				$from_location	=	$this->Mmm->sanitize($_POST['loc']);
				$to_location	=	$this->Mmm->sanitize($_POST['to_location']);
				$remark			=	$this->Mmm->sanitize($_POST['remark']);
				$items			=	$_POST['sels'];
				$transfer_date	=	date("Y-m-d", strtotime($_POST['transfer_date']));
				$company_id		=	$this->Mmm->sanitize($_POST['company']);
				if($from_location!='' && $to_location!=''){
					$tran_control_number	=	$this->Abas->getNextSerialNumber('inventory_transfer', $company_id);
					$sql					=	"INSERT INTO inventory_transfer(id, transfer_date, transfered_by, from_location, to_location, stat, remark, is_received, control_number, company_id) VALUES(0, '$transfer_date','$transfered_by','$from_location','$to_location',0, '$remark',0, $tran_control_number, $company_id)";
					$r						=	$this->Mmm->query($sql, 'Inventory Transfer');
					if($r){
						$tran_id		=	$this->db->insert_id();
						$itemGroup		=	explode(",",$items);
						$ctr			=	count($itemGroup) - 1;
						$grand_total	=	0;
						for($i=0;$i<$ctr;$i++){
							$group			=	explode('|',$itemGroup[$i]);
							$item_id		=	$group[0];
							$qty			=	$group[1];
							$sql			=	"SELECT * FROM inventory_items WHERE id	=".$item_id;
							$db				=	$this->db->query($sql);
							$result			=	$db->result_array();
							$lineTotal		=	$qty * $result[0]['unit_price'];
							$grand_total	=	$lineTotal + $grand_total;
							$unit			=	$result[0]['unit'];
							$unit_price		=	$result[0]['unit_price'];
							$sql3			=	"INSERT INTO inventory_transfer_details(id, transfer_id, item_id, unit, unit_price, qty, stat) VALUES(0,$tran_id,$item_id,'$unit','$unit_price',$qty,0)";
							$db2			=	$this->Mmm->query($sql3, 'Inventory Transfer Details');
							$sqGet			=	"SELECT * FROM inventory_location WHERE item_id	=	$item_id";
							$rdb			=	$this->db->query($sqGet);
							$rGet			=	$rdb->result_array();
							switch ($from_location) {
								case 'Makati':
								$loc_qty	=	($rGet[0]['mkt_qty']) - $qty;
								$sqUp		=	"UPDATE inventory_location SET mkt_qty	=	$loc_qty";
								break;
								case 'NRA':
								$loc_qty	=	($rGet[0]['nra_qty']) - $qty;
								$sqUp		=	"UPDATE inventory_location SET nra_qty	=	$loc_qty";
								break;
								case 'Tayud':
								$loc_qty	=	($rGet[0]['tayud_qty']) - $qty;
								$sqUp		=	"UPDATE inventory_location SET tayud_qty	=	$loc_qty";
								break;
								case 'Tacloban':
								$loc_qty	=	($rGet[0]['tac_qty']) - $qty;
								$sqUp		=	"UPDATE inventory_location SET tac_qty	=	$loc_qty";
								break;
								case 'Direct Delivery':
								$loc_qty	=	($rGet[0]['direct_qty']) - $qty;
								$sqUp		=	"UPDATE inventory_location SET direct_qty	=	$loc_qty";
								break;
							}
							$sqUp .=" WHERE item_id	=	$item_id";
							$rUp	=	$this->db->query($sqUp);

						}
						$this->Abas->redirect(HTTP_PATH."inventory/print_tr/".$tran_id);
					}
				}
			}
			$this->Abas->sysMsg("sucmsg", "New inventory transfer request has been created.");
			$this->Abas->redirect(HTTP_PATH."inventory/transfer_view");
		}
		public function addDelivery()	{$data=array();
			$msg	='';
			if(isset($_POST)){
				$delivered_to		=	$this->Mmm->sanitize($_POST['issued_for']); //for direct delivery
				$issue_id			=	'';
				$delivery_no		=	$this->Mmm->sanitize($_POST['delivery_no']);
				$sales_invoice_no	=	$this->Mmm->sanitize($_POST['sales_invoice_no']);
				$delivery_date		=	$this->Mmm->sanitize($_POST['delivery_date']);
				$delivery_date		=	date('Y-m-d h:m:s', strtotime($delivery_date));
				$pono				=	$this->Mmm->sanitize($_POST['selpono']);
				$is_nod				=	$this->Mmm->sanitize($_POST['is_notice_of_discrepancy']);
				if($is_nod==1){
					$nod 			= 	$this->Inventory_model->getNoticeOfDiscrepancyByPO($pono);
					if($nod){
						$nod_id 		=	$nod->id;
						$nod_note       =   " (With Notice of Discrepancy Ctrl. No.".$nod->control_number." - TS Code No.".$nod_id.")";
					}else{
						$nod_id 		= 	0;
						$nod_note       =  "";
					}
				}else{
					$nod_id 		= 	0;
					$nod_note       =  "";
				}
				$location			=	$this->Mmm->sanitize($_POST['location']);
				$remark				=	$this->Mmm->sanitize($_POST['remark']).$nod_note;
				$items				=	$_POST['sels'];
				$date_now			=	date('Y-m-d H:m:s');
				$amount				=	0;
				$created_on			=	date('Y-m-d H:m:s');
				$created_by			=	$_SESSION['abas_login']['userid'];
				if($items==''){
					$items			=	$this->Mmm->sanitize($_POST['selspo']);
				}
				if(isset($pono)) {
					$po_summary		=	$this->Purchasing_model->getPurchaseOrder($pono);
					if($po_summary==FALSE) {
						$this->Abas->sysMsg("errmsg", "The PO entered is not existing.");
						$this->Abas->redirect(HTTP_PATH."inventory/");
					}
					$item_count			=	count($po_summary['details']);
					$po_items			=	array_reverse($po_summary['details']);
					$ctr2				=	count($po_summary['details']);
					$itemGroup			=	explode(",",$items);
					$ctr				=	count($itemGroup) - 1;
					$grand_total		=	0;
				}
				else{
					$this->Abas->sysMsg("errmsg", "Invalid transaction Purchase Order not found, please try again.");
					$this->Abas->redirect(HTTP_PATH."inventory/");
				}
				$supplier_id			=	$po_summary['supplier']['id'];
				$company_id				=	$po_summary['company_id'];
				$delivery_control_no	=	$this->Abas->getNextSerialNumber('inventory_deliveries', $company_id);
				$is_issued				=	($delivered_to !=	'')? 1: 0;
				$sql					=	"INSERT INTO inventory_deliveries(id, tdate, delivery_no, sales_invoice_no, po_no, supplier_id, amount, location, remark, control_number, company_id, is_issued,notice_of_discrepancy_id, stat, created_on, created_by) VALUES(0, '$delivery_date','$delivery_no','$sales_invoice_no','$pono','$supplier_id','$amount', '$location', '$remark', $delivery_control_no, $company_id, $is_issued,$nod_id, 1, '$created_on', $created_by )";
				$r						=	$this->Mmm->query($sql,'New delivery received.');
				if($r){
					$delivery_id		=	$this->db->insert_id();
				}
				else{
					$this->Abas->sysMsg("errmsg", "Problem occured, please contact admin. (err:".$sql." )");
					$this->Abas->redirect(HTTP_PATH."inventory/");
				}
				if($delivered_to !=	''){
					$issuance_control_no	=	$this->Abas->getNextSerialNumber('inventory_issuance', $company_id);
					$request_id	=	$po_summary['details'][0]['request_id'];
					$sq	=	"INSERT INTO inventory_issuance(id, issue_date, request_no, vessel_id, stat, delivery_id, company_id, control_number, remark,is_cleared) VALUES(0,'$delivery_date','$request_id',$delivered_to ,1, $delivery_id, $company_id, $issuance_control_no, 'Direct delivery',0)";
					$issue_db	=	$this->Mmm->query($sq, 'New Issuance');
					if($issue_db){
						$issue_id	=	$this->db->insert_id();
					}
					else{
						$this->Abas->sysMsg("errmsg", "Problem in insert, please contact admin!");
						$this->Abas->redirect(HTTP_PATH."inventory/");
					}
				}
				if($r==TRUE){
					$itemGroup	=	explode(",",$items);
					$ctr	=	count($itemGroup) - 1;
					$grand_total	=	0;
					for($i=0;$i < $ctr; $i++){
						$group	=	explode('|',$itemGroup[$i]);
						$item_id	=	$group[0];
						$qty	=	$group[1];
						$sql_detail	=	"SELECT * FROM inventory_po_details WHERE po_id	=".$pono." AND item_id	=	".$item_id;
						//$sql	=	"SELECT * FROM inventory_items WHERE id	=".$item_id;
						$db_detail	=	$this->db->query($sql_detail);
						//trap missing item
						if($db_detail	==	FALSE){
							$this->Abas->sysMsg("errmsg", "Missing item info, please contact admin!");
							$this->Abas->redirect(HTTP_PATH."inventory/");
						}
						$result			=	$db_detail->result_array();
						$lineTotal		=	$qty * $result[0]['unit_price'];
						$grand_total	=	$lineTotal + $grand_total;
						$unit			=	$result[0]['unit'];
						$unit_price		=	$result[0]['unit_price'];
						$sql2			=	"INSERT INTO inventory_delivery_details(id, delivery_id, item_id, unit, unit_price, quantity, stat) VALUES(0,$delivery_id,$item_id,'$unit','$unit_price',$qty,0)";
						$db2			=	$this->Mmm->query($sql2, 'Delivery Details');
						$sqGet			=	"SELECT * FROM inventory_location WHERE item_id	=	$item_id";
						$rdb			=	$this->db->query($sqGet);
						$rGet			=	$rdb->result_array();
						if(count($rGet)==0){
							$sql_item	=	"INSERT INTO inventory_location(id, item_id) VALUES(0,$item_id)";
							$db			=	$this->Mmm->query($sql_item, 'New Item added to item location');
							if($db==false){
								$this->Abas->sysMsg("errmsg", "problem occured adding item in item location, please contact admin!");
								$this->Abas->redirect(HTTP_PATH."inventory");
							}
							$sqGet		=	"SELECT * FROM inventory_location WHERE item_id	=	$item_id";
							$rdb		=	$this->db->query($sqGet);
							$rGet		=	$rdb->result_array();
						}
						$amount			=	$grand_total;
						if($delivered_to!=''){
							if($issue_id!=''){
								$sq	=	"INSERT INTO inventory_issuance_details(id, issuance_id, item_id, unit, unit_price, qty, stat) VALUES(0,$issue_id,$item_id,'$unit','$unit_price',$qty,0)";
								$db	=	$this->Mmm->query($sq, 'Issuance Details');
							}
							switch ($location) {
								case 'Makati':
								$loc_qty	=	($rGet[0]['mkt_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET mkt_qty	=	$loc_qty";
								break;
								case 'NRA':
								$loc_qty	=	($rGet[0]['nra_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET nra_qty	=	$loc_qty";
								break;
								case 'Tayud':
								$loc_qty	=	($rGet[0]['tayud_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET tayud_qty	=	$loc_qty";
								break;
								case 'Tacloban':
								$loc_qty	=	($rGet[0]['tac_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET tac_qty	=	$loc_qty";
								break;
								case 'Direct Delivery':
								$loc_qty	=	($rGet[0]['direct_qty']) - $qty;
								$sqUp	=	"UPDATE inventory_location SET direct_qty	=	$loc_qty";
								break;
							}
							$sqUp	.=	" WHERE item_id	=	$item_id";
							$rUp	=	$this->Mmm->query($sqUp,'Added quantity on location.');
						}
						$sql3		=	"UPDATE inventory_deliveries SET amount	=	'$grand_total' WHERE id	=	$delivery_id";
						$r3			=	$this->db->query($sql3);
						$sql4		=	"SELECT qty FROM inventory_items WHERE id=".$item_id;
						$db4		=	$this->db->query($sql4);
						$resqty		=	$db4->result_array();
						$actual_qty	=	($resqty[0]['qty']) + $qty;
						$sql5		=	"UPDATE inventory_items SET qty=".$actual_qty." WHERE id=".$item_id;
						$r5			=	$this->db->query($sql5);
						switch ($location) {
							case 'Makati':
							$loc_qty	=	($rGet[0]['mkt_qty']) + $qty;
							$sqUp		=	"UPDATE inventory_location SET mkt_qty=".$loc_qty;
							break;
							case 'NRA':
							$loc_qty	=	($rGet[0]['nra_qty']) + $qty;
							$sqUp		=	"UPDATE inventory_location SET nra_qty=".$loc_qty;
							break;
							case 'Tayud':
							$loc_qty	=	($rGet[0]['tayud_qty']) + $qty;
							$sqUp		=	"UPDATE inventory_location SET tayud_qty=".$loc_qty;
							break;
							case 'Tacloban':
							$loc_qty	=	($rGet[0]['tac_qty']) + $qty;
							$sqUp	=	"UPDATE inventory_location SET tac_qty=".$loc_qty;
							break;
							case 'Direct Delivery':
							$loc_qty	=	($rGet[0]['direct_qty']) + $qty;
							$sqUp	=	"UPDATE inventory_location SET direct_qty=".$loc_qty;
							break;
						}
						$sqUp	.=	" WHERE item_id	=	$item_id";
						$rUp	=	$this->Mmm->query($sqUp,'Added quantity on location.');
						$sql	=	"UPDATE inventory_items SET requested=0 WHERE id=".$item_id;
						$db		=	$this->db->query($sql);

						//insert to inventory_quantity
						$sqGetx			=	"SELECT * FROM inventory_quantity WHERE item_id=".$item_id." AND company_id=".$company_id. " AND location='".$location."'";
						$rdbx			=	$this->db->query($sqGetx);
						$rGetx			=	$rdbx->result_array();
						if(count($rGetx)==0){
							$sql2 = "INSERT INTO inventory_quantity (item_id,company_id,location,quantity,stat) VALUES(".$item_id.",".$company_id.",'".$location."',".$qty.",1)";
							$queryx = $this->Mmm->query($sql2,"Added quantity on Item Inventory per company and location.");
						}else{
							$sql3 = "UPDATE inventory_quantity SET quantity=(quantity+".$qty.") WHERE item_id=".$item_id." AND company_id=".$company_id. " AND location='".$location."'";
							$queryx = $this->Mmm->query($sql3,"Updated quantity on Item Inventory per company and location.");
						}

					}
					$p							=	"UPDATE inventory_po SET status='For clearing' WHERE id=".$pono;
					$up							=	$this->db->query($p);
					$s							=	"UPDATE inventory_request_details SET status='For clearing' WHERE status='For Delivery' AND item_id=".$item_id." AND request_id=".$po_summary['details'][0]['request_detail_id'];
					$d							=	$this->db->query($s);
					$debit_account				=	MATERIALS_AND_SUPPLIES;
					$insertTran['stat']			=	1;
					$insertTran['date']			=	$delivery_date;//date('Y-m-d h:m:s');
					$insertTran['remark']		=	"PO# ".$po_summary['id']."	for ".$po_summary['vessel_name'];
					$insertTran['company_id']	=	$po_summary['company_id'];
					$trans						=	$this->Mmm->dbInsert("ac_transactions", $insertTran, "New transaction added");
					$sql_last					=	"SELECT max(id) as id FROM ac_transactions";
					$db1						=	$this->db->query($sql_last);
					$last_id					=	$db1->result_array();
					$transaction_id				=	$last_id[0]['id'];
					$computed_amount			=	$this->Abas->computePurchaseTaxes($amount,$supplier_id,0,$company_id);
					if($computed_amount['vatable_purchases']>0) {
						$debit						=	array();
						$debit['account']			=	$debit_account; //AP-Clearing after vat
						$debit['debit_amount']		=	round($computed_amount['vatable_purchases'],2);
						$debit['credit_amount']		=	0;
						$debit['company']			=	$company_id;
						$debit['transaction_id']	=	$transaction_id;
						$debit['reference_table']	=	'inventory_deliveries';
						$debit['reference_id']		=	$delivery_id;
						$debit['remark']			=	'For '.$po_summary['vessel_name'];
						$debit['department']		=	0;
						$debit['vessel']			=	0;
						$debit['contract']			=	0;
						$debit['posted_on']			=	$delivery_date;
						$credit						=	array();
						$credit['account']			=	AP_CLEARING; //AP-Clearing after vat
						$credit['debit_amount']		=	0;
						$credit['credit_amount']	=	round($computed_amount['vatable_purchases'],2);
						$credit['company']			=	$company_id;
						$credit['transaction_id']	=	$transaction_id;
						$credit['reference_table']	=	'inventory_deliveries';
						$credit['reference_id']		=	$delivery_id;
						$credit['remark']			=	'For '.$po_summary['vessel_name'];
						$credit['department']		=	0;
						$credit['vessel']			=	0;
						$credit['contract']			=	0;
						$credit['posted_on']		=	$delivery_date;
						$debit_entry				=	$this->Accounting_model->newJournalEntry($debit);
						if(!$debit_entry){
							$this->Abas->sysMsg("errmsg", "problem occured in debit entry, please contact admin!");
							$this->Abas->redirect(HTTP_PATH."inventory/");
						}
						$credit_entry	=	$this->Accounting_model->newJournalEntry($credit);
						if(!$credit_entry){
							$this->Abas->sysMsg("errmsg", "problem occured in credit entry, please contact admin!");
							$this->Abas->redirect(HTTP_PATH."inventory/");
						}
						$this->Abas->sysMsg("sucmsg", "Delivery received.");
						$this->Abas->redirect(HTTP_PATH."inventory/print_rr/".$delivery_id);

					}
					else{
						$this->Abas->sysMsg("errmsg", "Missing amount, please contact admin!");
						$this->Abas->redirect(HTTP_PATH."inventory/");
					}
				}
			}
			else{
				$this->Abas->sysMsg("errmsg", "Problem occured in delivery summary.");
				$this->Abas->redirect(HTTP_PATH."inventory/");
			}
			$this->Abas->sysMsg("errmsg", "Problem occured in posting.");
			$this->Abas->redirect(HTTP_PATH."inventory");
		}
		public function addRequest() {
			$request_by		=	$_SESSION['abas_login'];
			$requested_by	=	$request_by['userid'];
			$requestDate	=	date('Y-m-d');
			$location		=	$request_by['user_location'];
			$items			=	$_POST['reqItems'];
			$exploded		=	explode(',',$items);
			$ctr			=	count($exploded);
			$sql			=	"INSERT INTO inventory_request(id, request_date, request_by, location, stat) VALUES(0, '$requestDate', $requested_by, '$location', 0)";
			$db				=	$this->Mmm->query($sql, 'New Inventory Request Created');
			$reqid			=	$this->db->insert_id();
			if($db){
				for($i=0;$i<$ctr;$i++) {
					$group		=	explode('|',$exploded[$i]);
					$item_id	=	$group[0];
					$qty		=	$group[1];
					$sql		=	"INSERT INTO inventory_request_details(id, request_id, item_id, qty, stat) VALUES (0, $reqid, $item_id, $qty, 0)";
					$db			=	$this->Mmm->query($sql, 'Inventory Request Details');
					$sql		=	"UPDATE inventory_items SET req	=	1 WHERE id	=	$item_id";
					$db			=	$this->db->query($sql);
				}
				$_SESSION['msg']	=	"New Request has been created.";
				print "<script type=\"text/javascript\">
				window.open('".HTTP_PATH."inventory/print_req/".$reqid."');
				window.location.href	=	'".HTTP_PATH."inventory/';
				</script>";
			}
			$this->Abas->redirect(HTTP_PATH."inventory/");
		}
		public function print_rr($id='',$reprint=false){
			require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
			if($id!='') {
				$data['summary']	=	$this->Inventory_model->getDelivery($id);
				$data['po_info']	=	$this->Purchasing_model->getPurchaseOrder($data['summary'][0]['po_no']);
				$data['company']	=	$this->Abas->getCompany($data['summary'][0]['company_id']);
				$data['supplier']	=	$this->Abas->getSupplier($data['summary'][0]['supplier_id']);
				$data['request']	=	$this->Purchasing_model->getRequest($data['po_info']['request_id']);
				$data['details']	=	$this->Inventory_model->getDeliveryDetails($id);
				$ref_table			=	'inventory_deliveries';
				$data['entry']		=	$this->Inventory_model->getAccountingEntry($id,$ref_table);
				$data['reprint']	=	$reprint;

				$issuance = $this->Inventory_model->getIssuanceByDeliveryID($id);
				if($issuance){
				    $sql				=	"SELECT * FROM inventory_issuance WHERE id=".$issuance[0]['id'];
					$db					=	$this->db->query($sql);
					$data['summary_issuance']	=	$db->result_array();
					$data['vessel_issuance']		=	$this->Abas->getVessel($data['summary_issuance'][0]['vessel_id']);
					$data['company_issuance']	=	$this->Abas->getCompany($data['vessel_issuance']->company);
					$sql2				=	"SELECT * FROM inventory_issuance_details WHERE issuance_id	=	".$issuance[0]['id'];
					$db2				=	$this->db->query($sql2);
					$data['details_issuance']	=	$db2->result_array();
				}

				$this->load->view('inventory/print_receiving',$data);
			}
			else{
				$this->Abas->sysMSg("msg", "There was an error printing receiving report.");
			}
		}
		public function print_is($id='',$reprint=false,$gatepass=false){
			require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
			if($id !=	''){
				$sql				=	"SELECT * FROM inventory_issuance WHERE id=".$id;
				$db					=	$this->db->query($sql);
				$data['summary']	=	$db->result_array();
				$data['vessel']		=	$this->Abas->getVessel($data['summary'][0]['vessel_id']);
				$data['company']	=	$this->Abas->getCompany($data['vessel']->company);
				$sql2				=	"SELECT * FROM inventory_issuance_details WHERE issuance_id	=	".$id;
				$db2				=	$this->db->query($sql2);
				$data['details']	=	$db2->result_array();
				$data['reprint']	=	$reprint;
				if($gatepass==1){
					$sql3				=	"SELECT * FROM inventory_gatepass WHERE issuance_id	=".$id;
					$db3				=	$this->db->query($sql3);
					$data['gatepass']	=	$db3->row();
				}
				$this->load->view('inventory/print_issuance',$data);
			}
			else{
				$this->Abas->sysMSg("msg", "There was an error printing issuance report.");
			}
		}
		public function print_req($id=''){
			if($id !=	''){
				$sql				=	"SELECT * FROM inventory_request WHERE id=".$id;
				$db					=	$this->db->query($sql);
				$data['summary']	=	$db->result_array();
				$sql2				=	"SELECT * FROM inventory_request_details WHERE request_id=".$id;
				$db2				=	$this->db->query($sql2);
				$data['details']	=	$db2->result_array();
				$this->load->view('inventory/print_request',$data);
			}
			else{
				$this->Abas->sysMSg("msg", "There was an error printing inventory request.");
			}
		}
		public function print_tr($id='',$reprint=false){
			require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
			if($id !=	''){
				$sql				=	"SELECT * FROM inventory_transfer WHERE id=".$id;
				$db					=	$this->db->query($sql);
				$data['summary']	=	$db->result_array();
				$sql2				=	"SELECT * FROM inventory_transfer_details WHERE transfer_id=".$id;
				$db2				=	$this->db->query($sql2);
				$data['details']	=	$db2->result_array();
				$data['company']	=	$this->Abas->getCompany($data['summary'][0]['company_id']);
				$data['reprint']	=	$reprint;
				$this->load->view('inventory/print_transfer',$data);
			}
			else{
				$this->Abas->sysMsg("msg", "There was an error printing inventory transfer.");
			}
		}
		public function print_rt($id='',$reprint=false){
			require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
			if($id !=	''){
				$sql				=	"SELECT * FROM inventory_return WHERE id=".$id;
				$db					=	$this->db->query($sql);
				$data['summary']	=	$db->result_array();
				$vessel = $this->Abas->getVessel($data['summary'][0]['return_from']);
				$data['summary'][0]['returned_from'] = $vessel->name;
				$sql2				=	"SELECT * FROM inventory_return_details WHERE return_id=".$id;
				$db2				=	$this->db->query($sql2);
				$data['details']	=	$db2->result_array();
				$data['company']	=	$this->Abas->getCompany($data['summary'][0]['company_id']);
				$data['reprint']	=	$reprint;
				$this->load->view('inventory/print_return',$data);
			}
			else{
				$this->Abas->sysMsg("msg", "There was an error printing inventory transfer.");
			}
		}
		public function print_gatepass($id){
			require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
			if($id !=	''){
				$sql				=	"SELECT * FROM inventory_issuance WHERE id=".$id;
				$db					=	$this->db->query($sql);
				$data['summary']	=	$db->result_array();
				$data['vessel']		=	$this->Abas->getVessel($data['summary'][0]['vessel_id']);
				$data['company']	=	$this->Abas->getCompany($data['vessel']->company);
				$sql2				=	"SELECT * FROM inventory_issuance_details WHERE issuance_id	=	".$id;
				$db2				=	$this->db->query($sql2);
				$data['details']	=	$db2->result_array();
				$sql3				=	"SELECT * FROM inventory_gatepass WHERE issuance_id	=	".$id;
				$db3				=	$this->db->query($sql3);
				$data['gatepass']	=	$db3->row();
				$this->load->view('inventory/print_gatepass',$data);
			}
			else{
				$this->Abas->sysMsg("msg", "There was an error printing gatepass.");
			}
		}
		public function item_form($id='')	{$data=array();
			if($id!=''){
				$data['item']		=	$this->Inventory_model->getItems($id);
			}
			$data['categories']		=	$this->Inventory_model->getCategories();
			$data['sub_categories']	=	$this->Inventory_model->getSubCategories();
			$data['units']			=	$this->Inventory_model->getUnits();
			$data['vessels']		=   $this->Abas->getVessels(true);
			$this->load->view('inventory/item_form',$data);
		}
		public function request_form($id='')	{$data=array();
			if($id!=''){
				$data['requests']	=	$this->Inventory_model->getItemRequest($id);
			}
			$data['requests']		=	$this->Inventory_model->getItemRequest();
			$this->load->view('inventory/request_form',$data);
		}
		public function getCurrentPrice($id)	{
			if(isset($id)){
				$sql	=	"SELECT unit_price FROM inventory_items WHERE id=".$id;
				$r		=	$this->db->query($sql);
				$c		=	$r->result_array();
				return $c[0]['unit_price'];
			}
		}
		public function addItem()	{$data=array();
			$msg	='';
			$picture ='';
			if(isset($_POST)){
				$fromModule		=	$this->Mmm->sanitize($_POST['fromModule']);
				$item_code		=	($_POST['item_code'] !=	'') ? $this->Mmm->sanitize($_POST['item_code']) : 0;
				$asset_code		=	($_POST['asset_code'] !='') ? $this->Mmm->sanitize($_POST['asset_code']) : 0;
				$description	=	$this->Mmm->sanitize($_POST['description']);
				$particular		=	$this->Mmm->sanitize($_POST['particular']);
				$unit			=	$this->Mmm->sanitize($_POST['unit']);
				$unit_price		=	($_POST['unit_cost'] !=	'') ? $this->Mmm->sanitize($_POST['unit_cost']) : 0;
				$reorder		=	($_POST['reorder'] !=	'') ? $this->Mmm->sanitize($_POST['reorder']) : 0;
				$category		=	$this->Mmm->sanitize($_POST['category']);
				$sub_category	=	($_POST['sub_category'] !=	'') ? $this->Mmm->sanitize($_POST['sub_category']) : 0;
				$location		=	$_SESSION['abas_login']['user_location'];
				$stock_location	=	$this->Mmm->sanitize($_POST['stock_location']);
				$stat			=	$this->Mmm->sanitize($_POST['stat']);
				$type           =   $this->Mmm->sanitize($_POST['type']);
				$id				=	$_POST['id'];
				$datenow		=	date('Y-m-d');
				$qty			=	($_POST['qty'] !='') ? $this->Mmm->sanitize($_POST['qty']) : 0;

				$config = array();
				$config['upload_path'] = WPATH .'assets'.DS.'uploads'.DS.'inventory'.DS.'item_images'.DS;
				$config['allowed_types'] = 'jpg';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('picture')) {
					$error = array('error' => $this->upload->display_errors());
					$_SESSION['warnmsg'] = $error['error'];
				}
				else {
					$upload_data=$this->upload->data();
					$picture	=	$upload_data['file_name'];
					$_SESSION['sucmsg']		=	"Item image has been successfully uploaded!";
				}

				if($id!='') {
					$this->Abas->checkPermissions("inventory|edit_item");
					$current_price	=	$this->getCurrentPrice($id);
					if($picture){
						$sql	=	"UPDATE inventory_items SET item_code='$item_code',asset_code='$asset_code', description='$description', particular='$particular', unit='$unit', unit_price='$unit_price', qty=$qty, reorder_level=$reorder , category=$category, sub_category='$sub_category', location='$location', stock_location='$stock_location', type='$type', picture='$picture', stat='$stat' WHERE id=$id";
					}else{
						$sql	=	"UPDATE inventory_items SET item_code='$item_code',asset_code='$asset_code', description='$description', particular='$particular', unit='$unit', unit_price='$unit_price', qty=$qty, reorder_level=$reorder , category=$category, sub_category='$sub_category', location='$location', stock_location='$stock_location', type='$type', stat='$stat' WHERE id=$id";
					}
					
					$r		=	$this->Mmm->query($sql, 'Item info updated');
					if($r){
						$sqlook		=	"SELECT * FROM inventory_location WHERE item_id=$id";
						$sqr		=	$this->db->query($sqlook);
						if(!$sqr->row()){
							$sq		=	"INSERT INTO inventory_location(id, item_id, mkt_qty, tayud_qty, nra_qty, tac_qty, direct_qty) VALUES(0,$id, 0, 0, 0, 0, 0)";
							$dbres	=	$this->db->query($sq);
						}
						switch ($location) {
							case 'Makati':
							$sqUp	=	"UPDATE inventory_location SET mkt_qty	=	$qty WHERE item_id	=	$id";
							break;
							case 'NRA':
							$sqUp	=	"UPDATE inventory_location SET nra_qty	=	$qty WHERE item_id	=	$id";
							break;
							case 'Tayud':
							$sqUp	=	"UPDATE inventory_location SET tayud_qty	=	$qty WHERE item_id	=	$id";
							break;
							case 'Tacloban':
							$sqUp	=	"UPDATE inventory_location SET tac_qty	=	$qty WHERE item_id	=	$id";
							break;
							case 'Direct Delivery':
							$sqUp	=	"UPDATE inventory_location SET direct_qty	=	$qty WHERE item_id	=	$id";
							break;
						}
						$db		=	$this->db->query($sqUp);
						if($current_price!=$unit_price) {
							$sq	=	"	INSERT INTO inventory_price_history(id, item_id, unit_price, date_recorded, stat) VALUES(0,$id,'$unit_price','$datenow', 0)";
							$r	=	$this->Mmm->query($sq, 'Price update');
						}
						$this->Abas->sysMsg("sucmsg", "Item info has been updated by ".$_SESSION['abas_login']['username']);
						$this->Abas->redirect(HTTP_PATH."inventory/");
					}
				}
				else{
					$s	=	"SELECT * FROM inventory_items WHERE description='$description' AND particular='$particular' AND unit='$unit'";
					$r	=	$this->db->query($s);
					if($r->result_array()){
						$this->Abas->sysMsg("errmsg", "Item already exist!");
					}
					else{
						$qty		=	($_POST['qty'] !='') ? $this->Mmm->sanitize($_POST['qty']) : 0;
						$sql_new	=	"INSERT INTO inventory_items(id, item_code,asset_code, description, particular, unit, unit_price, qty, reorder_level, category, sub_category, stat, location, stock_location, type, picture, account_type, requested) VALUES(0, '$item_code','$asset_code','$description','$particular','$unit','$unit_price', $qty, $reorder, $category, $sub_category,1, '$location', '$stock_location','$type','$picture', 0, 0)";
						$db_new		=	$this->Mmm->query($sql_new, 'New item added');
						if($db_new==TRUE){
							$newid	=	$this->db->insert_id();
							$sq		=	"	INSERT INTO inventory_price_history(id, item_id, unit_price, date_recorded, stat) VALUES(0,$newid,'$unit_price','$datenow', 0)";
							$r		=	$this->Mmm->query($sq, 'Price update');
							$sql3	=	"SELECT * FROM inventory_location WHERE item_id	=	$newid";
							$r3		=	$this->db->query($sql3);
							$chk	=	$r3->result_array();
							if(count($chk)	==	0){
								$sql	=	"INSERT INTO inventory_location(id, item_id, tayud_qty, nra_qty, mkt_qty, tac_qty, direct_qty)
								VALUES(0, $newid, 0,0,0,0,0)";
								$r		=	$this->Mmm->query($sql, 'Inventory location created');
								if($r==FALSE){
									$this->Abas->sysMsg("errmsg", "Problem occured adding item to location.");
								}
							}
							switch ($location) {
								case 'Makati':
								$sqUp	=	"UPDATE inventory_location SET mkt_qty=$qty WHERE item_id=$newid";
								break;
								case 'NRA':
								$sqUp	=	"UPDATE inventory_location SET nra_qty=$qty WHERE item_id=$newid";
								break;
								case 'Tayud':
								$sqUp	=	"UPDATE inventory_location SET tayud_qty=$qty WHERE item_id=$newid";
								break;
								case 'Tacloban':
								$sqUp	=	"UPDATE inventory_location SET tac_qty=$qty WHERE item_id=$newid";
								break;
								case 'Direct Delivery':
								$sqUp	=	"UPDATE inventory_location SET direct_qty	=	$qty WHERE item_id	=	$newid";
								break;
							}
							$this->db->query($sqUp);
							$this->Abas->sysMsg("sucmsg", "New item (".$description." - ".$particular.") has been added by ".$_SESSION['abas_login']['username']);
							$requestUrl	=	$_POST['fromModule'];
							$searchStr=	"inventory";
							if(strpos($requestUrl,$searchStr)) {
								$this->Abas->redirect(HTTP_PATH."inventory/");
							}
							else {
								$this->Abas->redirect(HTTP_PATH."purchasing/");
							}
						}
					}
				}
			}
			$data['msg']	=	$msg;
			$this->Abas->redirect(HTTP_PATH."inventory");
		}
		public function inventory_forms(){
			$this->load->view('inventory_forms.php');
		}
		public function print_rr2($id=""){
			$this->load->library('Pdf');
			$table	=	"
			<style>
			#header{margin-top:30px}
			#title{ font-size:18px; font-weight:600}
			#ttype{ font-size:18px; font-weight:600; margin-top:20px}
			#rr_no{ margin-top:-20px; float:right; font-weight:600;}
			#receive_from{ margin-top:10px; float:left}
			#date{ margin-top:10px; margin-right:100px;	float:right}
			#po_no{ margin-top:30px;; margin-left:-100px; float:left}
			#pr_no{ margin-top:30px; margin-right:-50px; float:right}
			#si_no{ margin-top:50px; margin-left:-100px; float:left}
			#dr_no{ margin-top:50px; margin-right:-50px; float:right}
			#items{ margin-top:20px;}
			#received_by{ margin-top:80px; float:left}
			#inspected_by{ margin-top:80px; margin-left:200px; float:left}
			#noted_by{ margin-top:-20px; margin-left:500px; float:left; width:150px}
			#copy{ margin-top:0px; font-size:12px; font-weight:600; float:left; position:absolute}
			</style>
			";
			$table	.=	'
			<div align="center" style="width:800px; margin-left:40px">
			<div id="header" style="margin-top:30px">
			<div id="copy" style="margin-top:0px; font-size:12px; font-weight:600; float:left; position:absolute">(ACCOUNTING COPY)</div>
			<div id="title" align="center" style="font-size:16px; font-weight:600" >AVEGA BROS INTEGRATED SHIPPING CORP.</div>
			<div style="font-size:12px;">J. De Veyra St. NRA, Cebu City</div>
			<div id="ttype" style="font-size:18px; font-weight:600; margin-top:20px">RECEIVING REPORT</div>
			<div id="rr_no" style="margin-top:-20px; float:right; font-weight:600">RR No.: 09230</div>
			<div id="receive_from" style="margin-top:10px; float:left">Received From:</div>
			<div id="date" style="margin-top:10px; margin-right:100px;	float:right">Date:</div>
			<div id="po_no" style="margin-top:30px;; margin-left:-100px; float:left">PO No.:</div>
			<div id="pr_no" style="margin-top:30px; margin-right:-50px; float:right">PR No.:</div>
			<div id="si_no" style="margin-top:50px; margin-left:-100px; float:left">SI No.:</div>
			<div id="dr_no" style=" margin-top:50px; margin-right:-50px; float:right">DR No.:'.$id.'</div>
			<div>
			';
			$table .="
			<div id='table'>
			<table style='width:800px; border:thin 1px #000000 solid; font-size:12px' border='1px'>
			<thead>
			<tr align='center'>
			<td width='15%'>Item Code</td>
			<td width='50%'>Description</td>
			<td width='5%'>Qty</td>
			<td width='5%'>Unit</td>
			<td width='10%'>Unit Price</td>
			<td width='15%'>Line Total</td>
			</tr>
			</thead>
			<tbody>";
			for($i=0;$i<10;$i++){
				$table .="
				<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>*</td>
				</tr>
				";
			};
			$table .="
			</tbody>
			</table>
			</div>
			</div>
			<div id='received_by'>Received by:</div>
			<div id='inspected_by'>Inspected by:</div>
			<div id='noted_by'>Noted by:</div>
			</div>
			";
			$data['orientation']	=	"P";
			$data['pagetype']		=	"legal";
			$data['content']		=	$table;
			$this->load->view('pdf-container.php',$data);
		}
		public function item_data($include_old=false){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			if($include_old==false){
				$sql	=	"SELECT * FROM inventory_items WHERE (description LIKE '%".$search."%' OR item_code LIKE '%".$search."%') AND stat=1 ORDER BY description";
			}elseif($include_old==true){
				$sql	=	"SELECT * FROM inventory_items WHERE (description LIKE '%".$search."%' OR item_code LIKE '%".$search."%') ORDER BY description";
			}
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						// $ret['id']	=	$i['id'];
						$ret[$ctr]['label']	=	$i['item_code']. " | ".$i['description'].", ".$i['brand']." ".$i['particular'];//." (".$i['unit'].")";
						$ret[$ctr]['value']	=	$i['id'];
						$ret[$ctr]['item_code']	=	$i['item_code'];
						$ret[$ctr]['description']	=	$i['description'];
						$ret[$ctr]['particular']	=	$i['brand']." ".$i['particular'];
						//$ret[$ctr]['qty']	=	$i['qty'];
						/*$item_loc_qty = $this->Inventory_model->getItemQty($i['id']);
						$location = $_SESSION['abas_login']['user_location'];
						if($location=='Tayud'){
							$ret[$ctr]['qty']	= $item_loc_qty[0]['tayud_qty'];
						}elseif($location=='NRA'){
							$ret[$ctr]['qty']	= $item_loc_qty[0]['nra_qty'];
						}elseif($location=='Makati'){
							$ret[$ctr]['qty']	= $item_loc_qty[0]['mkt_qty'];
						}elseif($location=='Tacloban'){
							$ret[$ctr]['qty']	= $item_loc_qty[0]['tac_qty'];
						}*/
						$ret[$ctr]['unit']	=	$i['unit'];
						$ret[$ctr]['unit_price']	=	$i['unit_price'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function po_data(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM inventory_po WHERE id LIKE '%".$search."%' AND (status='For Delivery' OR status='For clearing') AND stat=1 ORDER BY id LIMIT 0, 50";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						// $ret['id']	=	$i['id'];
						$ret[$ctr]['label']	=	$i['id'] . " (PO No. " .$i['control_number']. ")";
						$ret[$ctr]['value']	=	$i['id'];
						$ret[$ctr]['control_no']	=	$i['control_number'];
						$company = $this->Abas->getCompany($i['company_id']);
						$ret[$ctr]['company']	=	$company->name;
						$ret[$ctr]['company_id']	=	$company->id;
						$supplier = $this->Abas->getSupplier($i['supplier_id']);
						$ret[$ctr]['supplier']	=	$supplier['name'];
						$ret[$ctr]['supplier_id']	=	$supplier['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function supplier_data(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM suppliers WHERE name LIKE '%".$search."%' ORDER BY name LIMIT 0, 12";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						$ret[$ctr]['label']	=	$i['name'];
						$ret[$ctr]['value']	=	$i['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function chkQty(){
			if(isset($_POST)){
				$location	=	$_POST['location'];
				$company	=	$_POST['company_id'];
				$item_id	=	$_POST['id'];
				$qty		=	$_POST['qty'];
				$sqChk		=	"SELECT * FROM inventory_location WHERE item_id	=$item_id";
				$rDb		=	$this->db->query($sqChk);
				$rGet		=	$rDb->result_array();
				$qtyAvail	=	0;
				switch ($location) {
					case 'Makati':
					$qtyAvail	=	$rGet[0]['mkt_qty'];
					break;
					case 'NRA':
					$qtyAvail	=	$rGet[0]['nra_qty'];
					break;
					case 'Tayud':
					$qtyAvail	=	$rGet[0]['tayud_qty'];
					break;
					case 'Tacloban':
					$qtyAvail	=	$rGet[0]['tac_qty'];
					break;
					case 'Direct Delivery':
					$qtyAvail	=	$rGet[0]['direct_qty'];
					break;
				}
				if($qtyAvail>0){
					$sqChk2		=	"SELECT * FROM inventory_quantity WHERE item_id=".$item_id." AND company_id=".$company." AND locaion='".$location."' AND stat=1";
					$rDb2		=	$this->db->query($sqChk2);
					$rGet2		=	$rDb2->result_array();
					$qtyAvail	=	$rGet2[0]['quantity'];
					echo $qtyAvail;
				}
			}
			else{
				return 'err';
			}
		}
		public function checkInventoryLocation(){
			$sql	=	"SELECT * FROM inventory_items";
			$db		=	$this->db->query($sql);
			$res	=	$db->result_array();
			foreach($res as $r){
				$sqChk	=	"SELECT * FROM inventory_location WHERE item_id	=	".$r['id'];
				$rDb	=	$this->db->query($sqChk);
				$rGet	=	$rDb->result_array();
				$ctr	=	0;
				if(count($rGet)	==	0){
					$insertSql	=	"	INSERT INTO inventory_location(id,item_id, tayud_qty, nra_qty, mkt_qty, tac_qty, direct_qty) VALUES(0,".$r['id'].",0,0,0,0,0)";
					$db	=	$this->db->query($insertSql);
					if($db){
						echo $insertSql."<br><br>";
						$ctr=$ctr+1;
					}
				}
			}
			echo $ctr." items has been updated.<br><br>";
		}
		public function checkPOItem(){
			if(isset($_POST['pono'])) {
				$qty	=	$_POST['qty'];
				$sql	=	"SELECT * FROM inventory_po_details WHERE po_id	= ".$_POST['pono']." AND item_id	=	".$_POST['item_id'];
				$db		=	$this->db->query($sql);
				$res	=	$db->result_array();
				if(count($res) > 0) {
					$sq	=	"SELECT sum(quantity) as total_delivered FROM inventory_deliveries as i INNER JOIN inventory_delivery_details as d ON i.id=d.delivery_id WHERE po_no=".$_POST['pono']." and d.item_id=".$_POST['item_id'];
					$d	=	$this->db->query($sq);
					$r	=	$d->result_array();
					if(count($r) > 0) {
						$bal	=	$res[0]['quantity'] - $r[0]['total_delivered'];
					}
					else{
						$bal	=	$res[0]['quantity'];
					}
					if($bal<=0) {
						echo '3'; // item qty is fully delivered on PO
					}
					elseif($bal>0) {
						if($qty>$bal) {
							echo '0'; // qty exceed than the PO
						}
						else {
							echo '1'; //OK
						}
					}
				}
				else{
					echo '2'; // item is not in PO
				}
			}
			else{
				return false;
			}
		}
		public function checkPOItemPrice(){
			$result = array();
			if(isset($_POST['pono'])) {
				$sql	=	"SELECT * FROM inventory_po_details WHERE po_id = ".$_POST['pono']." AND item_id	= ".$_POST['item_id'];
				$db		=	$this->db->query($sql);
				$res	=	$db->result_array();
				if($res){
					$result['unit_price'] = $res[0]['unit_price'];
					$result['packaging'] = $res[0]['packaging'];
				}
			}
			header('Content-Type: application/json');
			echo  json_encode($result);
			exit();
		}
		public function getDelivery(){
			
			if(isset($_POST['id'])){
				$location	=	$_POST['location'];
				$action	=	$_POST['action'];
				if(isset($_POST['pono'])){
					$poid		=	$_POST['pono'];
				}
				$selected_items	=	$_POST['id'];
				//$unit_prices = array_push($unit_prices,$_POST['unit_price']);
				//$unit_price = $_POST['unit_price'];

				if(is_array($selected_items)) {
					$itemGroup	=	explode(",",$selected_items[0]);
				}
				else{
					$itemGroup	=	explode(",",$selected_items);
				}
				$ctr	=	count($itemGroup) - 1;
				$res	=	"<table id='datatable-responsive' style='font-size:11px' class='table table-bordered table-striped table-hover' cellspacing='0'>
							<thead>
								<tr>
									<th width='15%'>Item Code</th>
									<th width='15%'>Item Name</th>
									<th width='20%'>Particular</th>
									<th width='5%'>Qty</th>
									<th width='5%'>Unit</th>
									<th width='15%'>Unit Price</th>
									<th width='20%'>Line Total</th>
									<th width='5%'></th>
								</tr>
							</thead>
								";
				$lineTotal	=	0;
				$grandTotal	=	0;
				for($i=0;$i < $ctr; $i++) {
					$group		=	explode('|',$itemGroup[$i]);
					$item_id	=	$group[0];
					$qty		=	$group[1];
					$unit_price =   $group[2];
					if($action!=='receive' && $action!=='del'){
						$sqChk	=	"SELECT * FROM inventory_location WHERE item_id	=	$item_id";
						$rDb	=	$this->db->query($sqChk);
						$rGet	=	$rDb->result_array();
						$qtyAvail	=	0; //1=not enough; 0=enough
						switch ($location) {
							case 'Makati':
							$qtyAvail	=	($qty > $rGet[0]['mkt_qty']) ? 1 : 0;
							break;
							case 'NRA':
							$qtyAvail	=	($qty > $rGet[0]['nra_qty']) ? 1 : 0;
							break;
							case 'Tayud':
							$qtyAvail	=	($qty > $rGet[0]['tayud_qty']) ? 1 : 0;
							break;
							case 'Tacloban':
							$qtyAvail	=	($qty > $rGet[0]['tac_qty']) ? 1 : 0;
							break;
							case 'Direct Delivery':
							$qtyAvail	=	($qty > $rGet[0]['direct_qty']) ? 1 : 0;
							break;
						}
						if($qtyAvail	===	1) {
							$qty	=	0;
							echo '<div class="alert alert-warning">
							<strong>Qty is not sufficient in selected location!</strong>
							</div>'	;
						}
						$sql	=	"SELECT * FROM inventory_items WHERE id	=".$item_id;
					}
					else{
						$sql	=	"SELECT p.id, p.item_id, item_code, description,particular, p.unit, p.unit_price FROM `inventory_po_details` as p INNER JOIN inventory_items as i ON p.item_id	=	i.id WHERE p.item_id	=	".$item_id." AND p.po_id	=	".$poid;
					}
					$db	=	$this->db->query($sql);
					$result	=	$db->result_array();

					if($action=='issuance'){
						if($unit_price==''||$unit_price==0){
							$unit_price = $result[0]['unit_price'];
						}
						$lineTotal	=	$qty * $unit_price;
						$res.=	"<tr>
									<td align='center'>".$result[0]['item_code']."</td>
									<td align='left' class='pull-left'>".$result[0]['description']."</td>
									<td>".$result[0]['particular']."</td>
									<td align='right' id='n".$result[0]['id']."' onclick='alert(this.id)'><span id='c".$result[0]['id']."'>".$qty."</span></td>
									<td align='center'>".$result[0]['unit']."</td>
									<td align='right'>".number_format($unit_price,2)."</td>
									<td align='right'>".number_format($lineTotal,2)."</td>
									<td align='center' ><a href='#' id='".$result[0]['id']."|".$qty.",' onclick='delItem(this.id); '>x</a></td>
								</tr>";
					}else{
						$lineTotal	=	$qty * $result[0]['unit_price'];
						$res.=	"<tr>
									<td align='center'>".$result[0]['item_code']."</td>
									<td align='left' class='pull-left'>".$result[0]['description']."</td>
									<td>".$result[0]['particular']."</td>
									<td align='right' id='n".$result[0]['id']."' onclick='alert(this.id)'><span id='c".$result[0]['id']."'>".$qty."</span></td>
									<td align='center'>".$result[0]['unit']."</td>
									<td align='right'>".number_format($result[0]['unit_price'],2)."</td>
									<td align='right'>".number_format($lineTotal,2)."</td>
									<td align='center' ><a href='#' id='".$result[0]['id']."|".$qty.",' onclick='delItem(this.id); '>x</a></td>
								</tr>";
					}

					$grandTotal	=	$grandTotal + $lineTotal;
				}
				$res.="<tr>
				<td colspan='5'></td>
				<td><strong>Total:</strong></td>
				<td align='right'><strong> Php ".number_format($grandTotal,2)."</strong></td>
				<td></td>
				</tr>
				</table>";
			}
			else{
				$res	=	'<div>No item selected. Please try again.</div>';
			}
			echo $res;
		}
		public function getIssuance(){
			if(isset($_POST['id'])){
				$location	=	$_POST['location'];
				$action		=	$_POST['action'];
				$selected_items	=	$_POST['id'];
				if(is_array($selected_items)){
					$itemGroup	=	explode(",",$selected_items[0]);
				}
				else{
					$itemGroup	=	explode(",",$selected_items);
				}
				$ctr	=	count($itemGroup) - 1;
				$res	=	"<table id='datatable-responsive' style='font-size:11px' class='table table-striped table-bordered dt-responsive nowrap jambo_table' cellspacing='0'>
							<thead>
								<tr>
									<th width='15%'>Item Code</th>
									<th width='15%'>Item Name</th>
									<th width='20%'>Particular</th>
									<th width='5%'>Qty</th>
									<th width='5%'>Unit</th>
									<th width='15%'>Unit Price</th>
									<th width='20%'>Line Total</th>
									<th width='5%'>*</th>
								</tr>
							</thead>
								";
				$lineTotal	=	0;
				$grandTotal	=	0;
				for($i=0;$i<$ctr; $i++){
					$group		=	explode('|',$itemGroup[$i]);
					$item_id	=	$group[0];
					$qty		=	$group[1];
					$sqChk		=	"SELECT * FROM inventory_location WHERE item_id	=	$item_id";
					$rDb		=	$this->db->query($sqChk);
					$rGet		=	$rDb->result_array();
					$qtyAvail	=	0; //1=not enough; 0=enough
					if($action!=='receive' && $action!=='del'){
						switch ($location) {
							case 'Makati':
							$qtyAvail	=	($qty > $rGet[0]['mkt_qty']) ? 1 : 0;
							break;
							case 'NRA':
							$qtyAvail	=	($qty > $rGet[0]['nra_qty']) ? 1 : 0;
							break;
							case 'Tayud':
							$qtyAvail	=	($qty > $rGet[0]['tayud_qty']) ? 1 : 0;
							break;
							case 'Tacloban':
							$qtyAvail	=	($qty > $rGet[0]['tac_qty']) ? 1 : 0;
							break;
							case 'Direct Delivery':
							$qtyAvail	=	($qty > $rGet[0]['direct_qty']) ? 1 : 0;
							break;
						}
						if($qtyAvail	===	1){
							$qty	=	0;
							echo '<div class="alert alert-warning">
							<strong>Qty is not sufficient in selected location!</strong>
							</div>'	;
						}
					}
					$sql		=	"SELECT * FROM inventory_items WHERE id	=".$item_id;
					$db			=	$this->db->query($sql);
					$result		=	$db->result_array();
					$lineTotal	=	$qty * $result[0]['unit_price'];
					$res		.=	"
							<tr>
								<td align='center'>".$result[0]['item_code']."</td>
								<td align='left' class='pull-left'>".$result[0]['description']."</td>
								<td>".$result[0]['particular']."</td>
								<td align='left' class='pull-left'>".$result[0]['particular']."</td>
								<td align='center' id='n".$result[0]['id']."' onclick='alert(this.id)'><span id='c".$result[0]['id']."'>".$qty."</span></td>
								<td align='center'>".$result[0]['unit']."</td>
								<td align='right' class='pull-right'>".number_format($result[0]['unit_price'],2)."</td>
								<td align='right'>".number_format($lineTotal,2)."</td>
								<td align='center' ><a href='#' id='".$result[0]['id']."|".$qty.",' onclick='delItem(this.id); '>x</a></td>
							</tr>
					";
				}
				$grandTotal	=	$grandTotal + $lineTotal;
				$res.="
					<tr>
						<td colspan='5'></td>
						<td><strong>Total:</strong></td>
						<td align='right'><strong> Php ".number_format($grandTotal,2)."</strong></td>
						<td></td>
					</tr>
				</table>";
			}
			else{
				$res	=	'<div>No item selected. Please try again.</div>';
			}
			echo $res;
		}
		public function getPoDetail() {
			if(isset($_POST['id'])){
				$id	=	$_POST['id'];
				$items	=	$this->Inventory_model->getPODetail($id);
				$htm	=	"<table id='datatable-responsive' class='table table-striped table-bordered dt-responsive nowrap jambo_table' cellspacing='0'>
							<thead>
								<tr>
									<th width='20%'>Item Code</th>
									<th width='50%'>Item Name</th>
									<th width='5%'>Qty Ordered</th>
									<th width='5%'>Unit</th>
									<th width='15%'>Qty Received</th>
									<th width='5%'>*</th>
								</tr>
							</thead>
								";
				$line_total	=	0;
				$grand_total	=	0;
				$itempo	=	'';
				foreach($items as $item){
					$item_info	=	$this->Inventory_model->getItem($item['item_id']);
					//var_dump($item['item_id']);
					$line_total	=	$item['quantity'] * $item['unit_price'];
					$htm.="
					<tr>
					<td align='center'>".$item_info[0]['item_code']."</td>
					<td align='left'>".$item_info[0]['description']."</td>
					<td align='center'>".$item['quantity']."</td>
					<td align='center'>".$item['unit']."</td>
					<td align='center'><input type='text' id='".$item_info[0]['id']."' style='width:60px'></td>
					<td align='center'>
					<a href='##'>
					<a href='#' id='".$item_info[0]['id']."|".$item['quantity'].",' onclick='delItem(this.id); '>x</a>
					</a>
					</td>
					</tr>
					";
					$grand_total	=	$grand_total + $line_total;
					$poitem	=	$item['item_id'].'|'.$item['quantity'];
					$itempo	=	$poitem.','.$itempo;
				}
				$htm.=	"
				<tr align='right'>
				<td colspan='6'></td>
				<td></td>
				</tr>
				</table>
				<input type='hidden' id='selspo' name='selspo' value='".$itempo."' />
				";
				echo $htm;
			}
		}
		public function getPOInfo($id){
			if(isset($id)){
				$data['po_info']	=	$this->Inventory_model->getPO($id);
				$company = $this->Abas->getCompany($data['po_info'][0]['company_id']);
				$supplier = $this->Abas->getSupplier($data['po_info'][0]['supplier_id']);
				$data['po_info'][0]['company_name'] = $company->name;
				$data['po_info'][0]['supplier_name'] = $supplier['name'];
				echo json_encode( $data['po_info']);
			}
		}
		public function getPOItems($id){
			if(isset($id)){
				$data['po_details']	=	$this->Inventory_model->getPOItems($id);
				echo json_encode( $data['po_details']);
			}
		}
		public function inventory_report() {
			$data['vessels']	=	$this->Abas->getVessels();
			$data['companies']	=	$this->Abas->getCompanies();
			$data['supplier']	=	$this->Inventory_model->getSuppliers();
			$data['locals']		=	$this->Inventory_model->getInventoryLocation();
			$data['viewfile']	=	"inventory/inventory_reports.php";
			$this->load->view('container.php',$data);
		}
		public function inventory_report_result() {
			if($_POST){
				$action	=	$_POST['action'];
				$data['location']	=	'';
				switch ($action) {
					case 'issuance':
						$from_date			=	$_POST['from_date'];
						$to_date			=	$_POST['to_date'];
						$vessel				=	$_POST['vessel'];
						$location			=	$_POST['location'];
						$data['sub_type']	=	'PER ISSUANCE SLIP';
						$sql				=	"SELECT * FROM `inventory_issuance` as i INNER JOIN `inventory_issuance_details` as d ON i.id	=	d.issuance_id WHERE 1=1";
						if($vessel !=''){
							$sql				.=	" AND issued_for	=".$vessel;
							$data['sub_type']	=	'PER DEPT/VESSEL';
							$data['vessel']		=	$this->Inventory_model->getVessels($vessel);
						}
						if($location !=''){
							$sql				.=	" AND from_location	='".$location."'";
							$data['location']	=	$location;
						}
						if($from_date!='' || $to_date!=''){
							$sql				.=	" AND issue_date between '".$from_date."' AND '".$to_date."'";
							$data['from_date']	=	$from_date;
							$data['to_date']	=	$to_date;
						}
						$sql			.=	" ORDER BY issue_date DESC ";
						$res			=	$this->db->query($sql);
						$data['type']	=	'ISSUANCE';
						$data['title']	=	'DAILY SUMMARY REPORT OF GOODS ISSUANCE (PER ISSUANCE SLIP)';
						$data['type']	=	'ISSUANCE';
					break;
					case 'receiving':
						$from_date	=	$_POST['rfrom_date'];
						$to_date	=	$_POST['rto_date'];
						$supplier	=	$_POST['supplier'];
						$location	=	$_POST['location'];
						$data['sub_type']	=	'PER RR';
						$sql	=	"SELECT * FROM `inventory_delivery` as i INNER JOIN `inventory_delivery_details` as d ON i.id	=	d.delivery_id WHERE 1=1";
						if($supplier !=''){
							$data['supplier']	=	$this->Accounting_model->getSuppliers($supplier);
							$data['sub_type']	=	'PER SUPPLIER';
							$sql .=	" AND supplier_id	=".$supplier;
						}
						if($location !=''){
							$data['location']	=	$location;
							$sql .=	" AND location	='".$location."'";
						}
						if($from_date !=	'' || $to_date !=	''){
							$sql .=	" AND delivery_date between '".$from_date."' AND '".$to_date."'";
							$data['from_date']	=	$from_date;
							$data['to_date']	=	$to_date;
						}
						$sql			.=	" ORDER BY delivery_date DESC ";
						$res			=	$this->db->query($sql);
						$data['title']	=	'DAILY SUMMARY REPORT OF GOODS RECEIPT(PER RR)';
						$data['type']	=	'RECEIVING';
					break;
					case 'transfer':
						$from_date			=	$_POST['tfrom_date'];
						$to_date			=	$_POST['tto_date'];
						$from_location		=	$_POST['tfrom_location'];
						$to_location		=	$_POST['tto_location'];
						$sql				=	"SELECT * FROM `inventory_transfer` as i INNER JOIN `inventory_transfer_details` as d ON i.id=d.transfer_id WHERE 1=1";
						if($from_location !='' and $to_location !=''){
							$sql			.=	" AND from_location='".$from_location."' AND to_location='".$to_location."'";
						}
						if($from_date!='' || $to_date!=''){
							$sql .=	" AND transfer_date between '".$from_date."' AND '".$to_date."'";
						}
						$sql			.=	" ORDER BY transfer_date DESC ";
						$res			=	$this->db->query($sql);
						$data['type']	=	'TRANSFER';
						$data['title']	=	'DAILY SUMMARY REPORT OF GOODS TRANSFERED';
					break;
					case 'count':
						$site_location	=	$_POST['site_location'];
						$sql	=	"SELECT * FROM `inventory_items` as i INNER JOIN inventory_location as l on i.id=l.item_id WHERE 1=1";
						if(isset($site_location)){
							$sql .=	" AND location	=	'$site_location'";
							$data['location']	=	$site_location;
						}
						$sql			.=	" ORDER BY i.category, description";
						$res			=	$this->db->query($sql);
						$data['type']	=	'COUNT';
						$data['title']	=	'INVENTORY COUNT & LIST REPORT';
					break;
					case 'po':
						$from_date			=	$_POST['pfrom_date'];
						$to_date			=	$_POST['pto_date'];
						$company			=	$_POST['pcompany'];
						$data['sub_type']	=	'PER INCLUSIVE DATE';
						$sql				=	"SELECT * FROM `inventory_po` as i INNER JOIN `inventory_po_details` as d ON i.id=d.po_id WHERE 1=1";
						if($company !=''){
							$data['company']	=	$this->Abas->getCompany($company);
							$data['sub_type']	=	'PER COMPANY';
							$sql				.=	" AND company_id=".$company;
						}
						if($from_date!='' || $to_date!=''){
							$sql				.=	" AND po_date between '".$from_date."' AND '".$to_date."'";
							$data['from_date']	=	$from_date;
							$data['to_date']	=	$to_date;
						}
						$sql .=	" ORDER BY po_date DESC ";
						$res	=	$this->db->query($sql);
						$data['title']	=	'SUMMARY REPORT OF PURCHASE ORDERS';
						$data['type']	=	'PURCHASE ORDER';
					break;
					default:
				}
			}
			$dbres			=	$res->result_array();
			$data['report']	=	$dbres;
			$this->load->view('inventory/print_report',$data);
		}
		public function print_report2($report) {
			var_dump($res); exit;
			$data['result']		=	0;
			if($data !=	''){
				$data['result']	=	$data;
			}
			$this->load->view('inventory/print_report',$data);
		}
		public function autocomplete() {
			$sql			=	"SELECT id, description FROM inventory_items ORDER BY description";
			$db				=	$this->db->query($sql);
			$res			=	$db->result_array();
			$data['items']	=	$res;
			$this->load->view('inventory/autocomplete',$data);
		}
		public function view_all_expenses() {
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$search	=	isset($_GET['search'])?$_GET['search']:"";
			$data	=	$this->Accounting_model->getAllExpenses($search,$limit,$offset,$order,$sort);
			if($data!=false) {
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			}
		}
		public function viewExpense($id)	{$data=array();
			if(isset($id)){
				$data['viewExpense']	=	$this->Accounting_model->getExpense($id);
			}
			$data['suppliers']			=	$this->Accounting_model->getSuppliers();
			$data['vessels']			=	$this->Abas->getVessels();
			$data['classifications']	=	$this->Accounting_model->getExpenseClassification();
			$data['expenses']			=	$this->Accounting_model->getAllExpenses();
			$data['viewfile']			=	"accounting/account_form.php";
			$this->load->view('container.php',$data);
		}
		public function expense_report_form()	{$data=array();
			$data['vessels']			=	$this->Abas->getVessels();
			$data['classifications']	=	$this->Accounting_model->getExpenseClassification();
			$data['viewfile']			=	"accounting/expense_report_form.php";
			$this->load->view('container-noheader.php',$data);
		}
		public function expense_report() {$data=array();
			$vid				=	$_POST['vessel'];
			$type				=	$_POST['include_on'];
			$class				=	$_POST['classification'];
			$from_date			=	$_POST['from_date'];
			$to_date			=	$_POST['to_date'];
			$data['ex_report']	=	$this->Accounting_model->getExpenseReport($vid,$from_date,$to_date,$class,$type);
			$data['viewfile']	=	"accounting/expense_report.php";
			$this->load->view('container.php',$data);
		}
		public function addExpense()	{$data=array();
			if(isset($_POST)){
				$eid	=	$this->Mmm->sanitize($_POST['id']);
				$voucher_no	=	$this->Mmm->sanitize($_POST['voucher_no']);
				$voucher_date	=	$this->Mmm->sanitize($_POST['voucher_date']);
				$payee	=	$this->Mmm->sanitize($_POST['payee']);
				$particulars	=	$this->Mmm->sanitize($_POST['particular']);
				$amount	=	$this->Mmm->sanitize($_POST['amount']);
				$reference_no	=	$this->Mmm->sanitize($_POST['reference_no']);
				$vessel	=	$this->Mmm->sanitize($_POST['vessel']);
				$include_on	=	$this->Mmm->sanitize($_POST['include_on']);
				$classification	=	$this->Mmm->sanitize($_POST['classification']);
				//check if add or edit
				if($eid !==	''){
					//edit
					// $sql	=	'UPDATE vessel_expenses
					// SET check_voucher_date	=	"'.$voucher_date.'",
					// check_voucher_no	=	"'.$voucher_no.'",
					// amount_in_php	=	"'.$amount.'",
					// reference_no	=	"'.$reference_no.'",
					// particulars	=	"'.$particulars.'",
					// vessel_id	=	'.$vessel.',
					// expense_classification_id	=	'.$classification.',
					// include_on	=	"'.$include_on.'",
					// account_id	=	'.$payee.'
					// WHERE id	=	'.$eid;
					$update['check_voucher_date']		=	$voucher_date;
					$update['check_voucher_no']			=	$voucher_no;
					$update['amount_in_php']			=	$amount;
					$update['reference_no']				=	$reference_no;
					$update['particulars']				=	$particulars;
					$update['vessel_id']				=	$vessel;
					$update['expense_classification_id']=	$classification;
					$update['include_on']				=	$include_on;
					$update['account_id']				=	$payee;
					$update['status']					=	'Active';
					$update['modified']					=	date("Y-m-d H:i:s");
					$sql	=	$this->Mmm->dbUpdate('vessel_expenses', $update, $eid, "Edit Vessel Expense");
					if($sql==true) {
						$this->Abas->sysMsg("sucmsg", "Vessel Expense Edited!");
					}
					else {
						$this->Abas->sysMsg("warnmsg", "Vessel Expense Not Edited!");
					}
					//var_dump($sql); exit;
					//$add	=	$this->db->query($sql);
				}
				else {
					//add
					// $sql	=	'INSERT INTO vessel_expenses(id, check_voucher_date, check_voucher_no, amount_in_php, reference_no, particulars, vessel_id, expense_classification_id, include_on, account_id) VALUES(0,"'.$voucher_date.'","'.$voucher_no.'",'.$amount.',"'.$reference_no.'","'.$particulars.'",'.$vessel.','.$classification.',"'.$include_on.'",'.$payee.')';
					$insert['check_voucher_date']		=	$voucher_date;
					$insert['check_voucher_no']			=	$voucher_no;
					$insert['amount_in_php']			=	$amount;
					$insert['reference_no']				=	$reference_no;
					$insert['particulars']				=	$particulars;
					$insert['vessel_id']				=	$vessel;
					$insert['expense_classification_id']=	$classification;
					$insert['include_on']				=	$include_on;
					$insert['account_id']				=	$payee;
					$insert['status']					=	'Active';
					$insert['created']					=	date("Y-m-d H:i:s");
					$sql	=	$this->Mmm->dbInsert('vessel_expenses', $insert, "New vessel expense");
					if($sql==true) {
						$this->Abas->sysMsg("sucmsg", "Vessel Expense Added!");
					}
					else {
						$this->Abas->sysMsg("warnmsg", "Vessel Expense Not Added!");
					}
				}
				$this->Abas->redirect(HTTP_PATH."accounting");
			}
			else {
				$this->Abas->sysMsg("errmsg", "Error Encountered, please contact administrator.");
			}
		}
		public function view_all_vessels() {
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$data	=	$this->hr_model->getAllVessels($limit,$offset,$order,$sort);
			if($data!=false) {
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			}
			else {
				die("An unknown error has occurred! <pre>Error ". __class__ .":". __function__ .":". __line__ ."</pre>");
			}
		}
		public function delivery_history($action="") { $data=	array();
			$data['viewfile']	=	"inventory/delivery_history.php";
			if ($action=="json"){
				if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$data	=	$this->Abas->createBSTable("inventory_deliveries",$search, $limit, $offset, $order, $sort);
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}
			}
			$this->load->view('container.php',$data);
		}
		public function issuance_history(){$data=array();
			$data['viewfile']	=	"inventory/issuance_history.php";
			$issuance			=	$this->db->query("SELECT * FROM inventory_issuance");
			$issuance			=	(array)$issuance->row();
			if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
				$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
				$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
				$order	=	isset($_GET['order'])?$_GET['order']:"";
				$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
				$search	=	isset($_GET['search'])?$_GET['search']:"";
				$data	=	$this->Abas->createBSTable("inventory_issuance",$search, $limit, $offset, $order, $sort);
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			}
			$this->load->view('container.php',$data);
		}
		public function filter_transaction_history($type){
			if($type=="issuance"){
				$data['history_type']	=	"issuance";
				$data['vessels']		=	$this->Abas->getVessels();
			}
			elseif($type=="delivery"){
				$data['history_type']	=	"delivery";
				$data['suppliers']		=	$this->Abas->getSuppliers();
			}
			elseif($type=="transfer"){
				$data['history_type']	=	"transfer";
			}
			elseif($type=="return"){
				$data['history_type']	=	"return";
				$data['vessels']		=	$this->Abas->getVessels();
			}
			$this->load->view('inventory/transaction_history_filter_modal.php',$data);
		}
		public function view_transaction_history_details($type,$transaction_id){
			if($type=="issuance"){
				$data['history_type']		=	"issuance";
				$data['history_details']	=	$this->Inventory_model->getIssuanceDetails($transaction_id);
				$data['gatepass']			=	$this->Inventory_model->getGatePass($transaction_id);
			}
			elseif($type=="delivery"){
				$data['history_type']		=	"delivery";
				$data['history_details']	=	$this->Inventory_model->getDeliveryDetails($transaction_id);
			}
			elseif($type=="transfer"){
				$data['history_type']		=	"transfer";
				$data['history_details']	=	$this->Inventory_model->getTransferDetails($transaction_id);
				$data['history_main'] 		=	$this->Inventory_model->getTransfers($transaction_id);
			}
			elseif($type=="return"){
				$data['history_type']		=	"return";
				$data['history_details']	=	$this->Inventory_model->getReturnDetails($transaction_id);
				$data['history_main'] 		=	$this->Inventory_model->getReturns($transaction_id);
			}
			$data['transaction_id']			=	$transaction_id;
			$this->load->view('inventory/transaction_history_details_modal.php',$data);
		}
		public function print_transaction_history(){
			$user_loc	=	$_SESSION['abas_login']['user_location'];
			if(isset($_GET['date_from']) && isset($_GET['date_to']) && isset($_GET['filter'])){
				$date_from	=	$_GET['date_from'];
				$date_to	=	$_GET['date_to'];
				$type		=	$_GET['type'];
				$filter		=	$_GET['filter'];
			}
			elseif(!isset($_GET['date_from']) && !isset($_GET['date_to']) && isset($_GET['filter'])){
				$date_from	=	null;
				$date_to	=	null;
				$type		=	$_GET['type'];
				$filter		=	$_GET['filter'];
			}
			else{
				$date_from	=	null;
				$date_to	=	null;
				$filter		=	null;
			}
			$data	=	$this->Inventory_model->getTransactionHistory($type,$date_from,$date_to,$filter);
			$this->load->view('inventory/transaction_history_print.php',$data);
		}
		public function transaction_history($type){
			$user_loc	=	$_SESSION['abas_login']['user_location'];
			if(isset($_POST['date_from']) && isset($_POST['date_to']) && isset($_POST['filter'])){
				$date_from	=	$_POST['date_from'];
				$date_to	=	$_POST['date_to'];
				$filter		=	$_POST['filter'];
			}
			elseif(!isset($_POST['date_from']) && !isset($_POST['date_to']) && isset($_POST['filter'])){
				$date_from	=	null;
				$date_to	=	null;
				$filter		=	$_POST['filter'];
			}
			else{
				$date_from	=	null;
				$date_to	=	null;
				$filter		=	null;
			}
			$data				=	$this->Inventory_model->getTransactionHistory($type,$date_from,$date_to,$filter);
			$data['viewfile']	=	"inventory/transaction_history_result.php";
			$this->load->view("gentlella_container.php",$data);
		}
		public function print_view_report()	{
			require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
			$data			=array();
			$date_from				=	$_GET['date_from'];
			$date_to				=	$_GET['date_to'];
			$vesselfilter			=	$_GET['vesseloffice_filter'];
			$filtername				=	$_GET['supplier_name'];
			$report_type			=	$_GET['report_type'];
			$data['report_type']	=	$report_type;
			if($_GET['report_type']=="delivery") {
				$datefrom			=	"";
				$dateto				=	"";
				$filtername			=	"";
					if(isset($_GET['date_from']))	{
						$datefrom	=	" AND tdate >=	'".$date_from."'";
					}
					if(isset($_GET['date_to'])) {
						if(!empty($_GET['date_to'])){
						$dateto		=	" AND tdate <=	'".$date_to."'";
					}}
					if(isset($_GET['supplier_name'])) {
						if(!empty($_GET['supplier_name'])){
					$filtername		=	" AND supplier_id=".$_GET['supplier_name'];
					} }
				$sql="SELECT * FROM inventory_deliveries WHERE stat=1 ".$datefrom."".$dateto."".$filtername;
				$deliveries	=	$this->db->query($sql);
				$deliveries	=	$deliveries->result_array();
				$data['deliveries']	=	$deliveries;
				if(!empty($deliveries)) {
					$inventorytable	=	"";
					$inventorytable		.=		"<thead>";
					$inventorytable		.=		"<tr>";
					$inventorytable		.=		"<th><b>Delivery Date</b></th>";
					$inventorytable		.=		"<th><b>Supplier Name</b></th>";
					$inventorytable		.=		"<th><b>P.O. Number</b></th>";
					$inventorytable		.=		"<th><b>Amount</b></th>";
					$inventorytable		.=		"<th><b>Receipt Number</b></th>";
					$inventorytable		.=		"</tr>";
					$inventorytable		.=		"</thead>";
					foreach($deliveries as $delivery)	{
						$itemsupplier		=	$this->Abas->getSupplier($delivery['supplier_id']);
						$inventorytable		.=		"<tr>";
						$inventorytable		.=		"<td>".($delivery['tdate']!="0000-00-00 00:00:00:" && $delivery['tdate']!="1970-01-01 00:00:00" ? date ("F j, Y", strtotime($delivery['tdate'])):"")."</td>";
						$inventorytable		.=		"<td>".$itemsupplier['name']."</td>";
						$inventorytable		.=		"<td>".$delivery['po_no']."</td>";
						$inventorytable		.=		"<td>".number_format($delivery['amount'],2)."</td>";
						$inventorytable		.=		"<td>".$delivery['receipt_num']."</td>";
						$inventorytable		.=		"</tr>";
					}
				}
				$data['orientation']	=	"P";
				$data['pagetype']		=	"legal";
				$data['content']		=	'<title><strong><h2>DELIVERY REPORT</h2></strong></title>
					<br><br><br><br><table width="100%" border="1" cellpadding="5px" cellspacing="1">'.$inventorytable.'</table>';//PDF CONTENT
				$this->load->view('pdf-container.php',$data);
			}//delivery
			if($_GET['report_type']=="issuance") {
				$datefrom		=	"";
				$dateto			=	"";
				$vesselfilter	=	"";
				if(isset($_GET['date_from']))	{
					$datefrom	=	" AND issue_date >=	'".$date_from."'";
				}
				if(isset($_GET['date_to'])) {
					if(!empty($_GET['date_to'])) {
					$dateto		=	" AND issue_date <=	'".$date_to."'";
					}
				}
				if(isset($_GET['vesseloffice_filter'])) {
					if(!empty($_GET['vesseloffice_filter'])) {
						$vesselfilter	=	" AND issued_for=".$_GET['vesseloffice_filter'];
					}
				}
				$sql		=	"SELECT * FROM inventory_issuance WHERE 1=1 ".$datefrom."".$dateto."".$vesselfilter;
				$issuances	=	$this->db->query ($sql);
				$issuances	=	$issuances->result_array();
				$data['issuances']	=	$issuances;
				if(!empty($issuances))	{
					$line_total	=	0;
					$grand_total	=	0;
					$inventorytable	=	"";
					$inventorytable		.=		"<thead>";
					$inventorytable		.=		"<tr>";
					$inventorytable		.=		"<th><b>Issue Date</b></th>";
					$inventorytable		.=		"<th><b>Vessel/Office</b></th>";
					$inventorytable		.=		"<th><b>Item Code</b></th>";
					$inventorytable		.=		"<th><b>Description</b></th>";
					$inventorytable		.=		"<th><b>Quantity</b></th>";
					$inventorytable		.=		"<th><b>Unit</b></th>";
					$inventorytable		.=		"<th><b>Unit Price</b></th>";
					$inventorytable		.=		"<th><b>Total</b></th>";
					$inventorytable		.=		"</tr>";
					$inventorytable		.=		"</thead>";
					foreach($issuances as $issuance) {
						$details	=	$this->db->query("SELECT * FROM inventory_issuance_details WHERE issuance_id=".$issuance['id']);
						if(!empty($details)) {
							if($details->row())	{
								$details	=	$details->result_array();
								foreach($details as $detail)	{
									$item		=	$this->Inventory_model->getItem($detail['item_id']);
									$item		=	$item[0];
									$itemvo		=	$this->Abas->getVessel($issuance['issued_for']);
									$line_total		=	$detail['qty'] * $detail['unit_price'];
									$grand_total		=		$grand_total	+	$line_total;
									$inventorytable		.=		"<tr>";
									$inventorytable		.=		"<td>".($issuance['issue_date']!="0000-00-00 00:00:00" && $issuance['issue_date']!="1970-01-01 00:00:00" ? date ("F j, Y", strtotime($issuance['issue_date'])):"")."</td>";
									$inventorytable		.=		"<td>".$itemvo->name."</td>";
									$inventorytable		.=		"<td>".$item['item_code']."</td>";
									$inventorytable		.=		"<td>".$item['description']."</td>";
									$inventorytable		.=		"<td>".$detail['qty']."</td>";
									$inventorytable		.=		"<td>".$detail['unit']."</td>";
									$inventorytable		.=		"<td>".number_format($detail['unit_price'],2)."</td>";
									$inventorytable		.=		"<td>".number_format($line_total,2)."</td>";
									$inventorytable		.=		"</tr>";
								}
							}
						}
					}
					$inventorytable		.=		"<tfoot>";
					$inventorytable		.=		"<tr>";
					$inventorytable		.=		"<td></td>";
					$inventorytable		.=		"<td></td>";
					$inventorytable		.=		"<td></td>";
					$inventorytable		.=		"<td></td>";
					$inventorytable		.=		"<td></td>";
					$inventorytable		.=		"<td></td>";
					$inventorytable		.=		"<td> <b>GRAND TOTAL:</b></td>";
					$inventorytable		.=		"<td>".number_format($grand_total,2)."</td>";
					$inventorytable		.=		"</tr>";
					$inventorytable		.=		"</tfoot>";
				}
				$data['orientation']	=	"P";
				$data['pagetype']		=	"legal";
				$data['content']		=	'<title><strong><h2>ISSUANCE REPORT</h2></strong></title>
				<br><br><br><br><table width="100%" border="1" cellpadding="5px" cellspacing="1">'.$inventorytable.'</table>';
				$this->load->view('pdf-container.php',$data);
			}
			$linkreport_type	=	"";
			$linkdatefrom		=	"";
			$linkdateto			=	"";
			$linkvessel			=	"";
			$linkname			="";
			if(isset($_GET['report_type']))	{
				$linkreport_type	=	"report_type=".$_GET['report_type'];
			}
			if(isset($_GET['date_from'])) {
				$linkdatefrom	=	"datefrom=".$_GET['date_from'];
			}
			if(isset($_GET['date_to'])) {
				$linkdateto	=	"dateto=".$_GET['date_to'];
			}
			if(isset($_GET['vesseloffice_filter']))	{
				$linkvessel	=	"vesseloffice_filter=".$_GET['vesseloffice_filter'];
			}
			if(isset($_GET['supplier_name'])) {
				$linkname	=	"supplier_name	=".$_GET['supplier_name'];
			}
		}
		public function rebaseIssuancePrice()	{
			$sql	=	"SELECT i.id, i.issue_date, vessel_id, item_id, unit_price, qty, d.id as iid	FROM inventory_issuance_details as d INNER JOIN inventory_issuance as i on i.id=d.issuance_id ORDER BY issue_date";
			$db		=	$this->db->query($sql);
			$res	=	$db->result_array();
			foreach($res as $r){
				$s	=	"SELECT * FROM inventory_price_history_view WHERE item_id	=	".$r['item_id']." ORDER BY tdate";
				$d	=	$this->db->query($s);
				$re	=	$d->result_array();
					foreach($re as $e){
						$quantity_available		=	$e['quantity'] - $e['quantity_issued'];
						$issued_qty				=	$r['qty'];
						$quantity_issued		=	0;
						$detail_id				=	$e['detail_id'];
						if($quantity_available>=$issued_qty) {
							if($e['unit_price'] !=	0){
								$unit_price			=	$e['unit_price'];
								$quantity_issued	=	$e['quantity_issued'];
								$total_issuance		=	$issued_qty + $e['quantity_issued'];
								$sqldel				=	"UPDATE inventory_delivery_details SET quantity_issued	=	".$total_issuance." WHERE id	=	".$detail_id." and item_id	=".$r['item_id'];
								$dbdel	=	$this->db->query($sqldel);
								if($dbdel){
									$usql			=	"UPDATE inventory_issuance_details SET unit_price=".$unit_price.", delivery_detail_id=".$detail_id." WHERE id=".$r['iid']." and item_id	=".$r['item_id'];
									$db				=	$this->db->query($usql);
								}
								break;
							}
						}
						elseif($issued_qty > $quantity_available){
							echo $e['tdate']." | ".$e['item_id']." | ".$e['unit_price']." | ".$e['quantity']." | ".$e['quantity_issued']." | ".$r['qty']."<br>";
						}
						$quantity_issued	=	$quantity_issued + $r['qty'];
					}
					if(isset($sqldel)){
						var_dump($sqldel);
						echo "<br>";
					}
			}
		}
		public function fix_control_numberXXX($cid)	{$data=array();
			$sql	=	"SELECT v.id, v.date_created, p.company_id, v.control_number FROM `ac_ap_vouchers` as v inner join inventory_po as p on v.po_no=p.id WHERE p.company_id=".$cid." ORDER BY v.date_created";
			$db		=	$this->db->query($sql);
			$res	=	$db->result_array();
			$ctr	=	1;
			foreach($res as $r){
				if($r['control_number']	==	0){
					$control_no	=	$ctr++;
					$s			=	"UPDATE ac_ap_vouchers SET company_id	=".$r['company_id'].", control_number	=	".$control_no." WHERE id=".$r['id'];
					echo $s.'<br>';
				}
			}
			echo $ctr." records has been updated.<br><br>";
		}
		public function transferDeliveryItems($id) {$data=array();
			if($id!=''){
				$from	=	'Makati';
				$to		=	'Tayud';
				$sql	=	"SELECT * FROM inventory_deliveries AS i INNER JOIN inventory_delivery_details AS d ON i.id=d.delivery_id WHERE i.id=".$id;
				$db		=	$this->db->query($sql);
				$res	=	$db->result_array();
				$ctr	=	0;
				foreach($res as $r){
					$item_id	=	$r['item_id'];
					$qty		=	$r['quantity'];
					$sqUp		=	"UPDATE inventory_location SET tayud_qty=tayud_qty + ".$qty.", mkt_qty=mkt_qty - ".$qty." WHERE item_id=".$item_id;
					$ctr++;
					echo "<br>";
				}
				echo "Update ".$ctr." records.";
			}
			else{
				echo "Parameter required, no parameter passed.";
			}
		}
		public function purchase_report($action="", $id="") {
			$data=array();
			$company_query='';
			$vessel_query ='';
			$mainview		=	"gentlella_container.php";
			$item			=	$this->Inventory_model->getItem($id);
			if(!$item) {
				$this->Abas->sysMsg("warnmsg", "Material/Service not found! Please try again.");
				$this->Abas->redirect(HTTP_PATH."inventory");
			}
			$item			=	$item[0];
			$data['item']	=	$item;
			if($action=="filter") {
				$mainview	=	"inventory/reports/purchase_filter.php";
			}
			elseif($action=="result") {
				if(!isset($_GET['dstart']) || !isset($_GET['dfinish'])) {
					$this->Abas->sysMsg("warnmsg", "No report date selected!");
					$this->Abas->redirect($previous_page);
				}
				$date_start		=	date("Y-m-d", strtotime($_GET['dstart']));
				$date_finish	=	date("Y-m-d", strtotime($_GET['dfinish']));

				$category = $this->Inventory_model->getCategory($data['item']['category']);

				if(isset($_GET['company'])) {
					if(is_numeric($_GET['company'])) {
						$company		=	$this->Abas->getCompany($_GET['company']);
						if($category->category=='Service') {
							$company_query			=	' AND inventory_job_orders.company_id='.$company->id;
						}else{
							$company_query			=	' AND inventory_po.company_id='.$company->id;
						}
					}
				}
				if(isset($_GET['vessel'])) {
					if(is_numeric($_GET['vessel'])) {
						$vessel			=	$this->Abas->getVessel($_GET['vessel']);
						if($vessel){
							$vessel_query			=	' AND inventory_requests.vessel_id='.$vessel->id;
						}
					}
				}
				
				if($category->category=='Service') {
					$purchase_orders = "SELECT *,inventory_requests.id AS request_id,inventory_job_orders.id AS jo_id,inventory_job_orders.status AS po_status,inventory_job_orders.tdate AS po_date FROM inventory_job_orders INNER JOIN inventory_job_order_details ON inventory_job_order_details.job_order_id=inventory_job_orders.id INNER JOIN inventory_requests ON inventory_requests.id=inventory_job_orders.request_id WHERE inventory_job_order_details.item_id=".$id.$company_query.$vessel_query." AND inventory_job_orders.tdate BETWEEN '".$date_start."' AND '".$date_finish."'";
				}else{
					$purchase_orders = "SELECT *,inventory_requests.id AS request_id,inventory_po.id AS po_id,inventory_po.status AS po_status,inventory_po.tdate AS po_date FROM inventory_po INNER JOIN inventory_po_details ON inventory_po_details.po_id=inventory_po.id INNER JOIN inventory_requests ON inventory_requests.id=inventory_po.request_id WHERE inventory_po_details.item_id=".$id.$company_query.$vessel_query." AND inventory_po.tdate BETWEEN '".$date_start."' AND '".$date_finish."'";
				}
			
				
				$purchase_orders	=	$this->db->query($purchase_orders);
				if($purchase_orders) {
					if($purchase_orders->row()) {
						$data['purchase_orders']	=	$purchase_orders->result_array();
					}
				}
				$data['category']   = $category->category;
				$data['viewfile']	=	"inventory/reports/purchase_report.php";
			}
			$this->load->view($mainview,$data);
		}
		public function item_unit_conversion($action,$item_id=NULL){
			switch ($action) {
				case 'form':
					$data = array();
					$data['units'] = $this->Inventory_model->getUnits();
					$this->load->view('inventory/item_unit_conversion_form.php',$data);
					break;
				case 'convert':
					if(isset($_POST['stock_item_desc'])){
						$deduct = false;
						$sql = "SELECT * FROM inventory_items WHERE description='".$_POST['stock_item_desc']."' AND particular='".$_POST['stock_item_particular']."' AND unit='".$_POST['unit_after_convert']."'";
						$query = $this->db->query($sql);
						if($query){
							if($query->row()){
								$update = array();
								$deduct = true;
								$update2 = array();
								$location = $_SESSION['abas_login']['user_location'];
								$row = $query->row();
								$item = $this->Inventory_model->getItemQty($row->id);
								if($location == 'Tayud'){
									$update2['tayud_qty']	=	$item[0]['tayud_qty'] + $this->Mmm->sanitize($_POST['qty_after_convert']);
								}elseif($location == 'NRA'){
									$update2['nra_qty']		=	$item[0]['nra_qty'] + $this->Mmm->sanitize($_POST['qty_after_convert']);
								}elseif($location == 'Makati'){
									$update2['mkt_qty']		=	$item[0]['mkt_qty'] + $this->Mmm->sanitize($_POST['qty_after_convert']);
								}elseif($location == 'Tacloban'){
									$update2['tac_qty']		=	$item[0]['tac_qty'] + $this->Mmm->sanitize($_POST['qty_after_convert']);
								}
								$sql_temp = "SELECT * FROM inventory_location WHERE item_id=".$row->id;
								$query_temp = $this->db->query($sql_temp);
								if($query_temp){
									$item_loc = $query_temp->row();
									$this->Mmm->dbUpdate("inventory_location",$update2,$item_loc->id,"Updated item quantity");	
								}
							}else{
								$sql3 = "SELECT * FROM inventory_items WHERE id=".$_POST['stock_item_id'];
								$query3 = $this->db->query($sql3);
								if($query3){
									$insert = array();
									$item3 = $query3->row(); 
									$insert['item_code'] = $item3->item_code;
									$insert['description'] = $this->Mmm->sanitize($_POST['new_item_desc']);
									$insert['particular'] = $this->Mmm->sanitize($_POST['new_particulars']);
									$insert['unit']		=	$this->Mmm->sanitize($_POST['unit_after_convert']);
									$insert['unit_price']	=	$this->Mmm->sanitize($_POST['price_after_covert']);
									$insert['reorder_level'] = $item3->reorder_level;
									$insert['discontinued'] = null;
									$insert['sub_category'] = $item3->sub_category;
									$insert['stat'] = $item3->stat;
									$insert['qty']		=	$this->Mmm->sanitize($_POST['qty_after_convert']);
									$insert['category'] = $item3->category;
									$insert['location'] = $item3->location;
									$insert['stock_location'] = $item3->stock_location;
									$insert['account_type'] = $item3->account_type;
									$insert['requested'] = $item3->requested;
									$insert['created_on'] = date('Y-m-d H:m:s');
									$insert['created_by'] = $_SESSION['abas_login']['userid'];
									$inserted = $this->Mmm->dbInsert("inventory_items",$insert,"Inserted new unit for item");
									if($inserted){
										$deduct = true;	
										$insert2 = array();
										$insert2['item_id'] = $this->Abas->getLastIDByTable('inventory_items');
										$location = $_SESSION['abas_login']['user_location'];
										if($location == 'Tayud'){
											$insert2['tayud_qty'] = $this->Mmm->sanitize($_POST['qty_after_convert']);
											$insert2['nra_qty'] = 0;
											$insert2['mkt_qty'] = 0;
											$insert2['tac_qty'] = 0;
											$insert2['direct_qty'] = 0;
										}elseif($location == 'NRA'){
											$insert2['tayud_qty'] = 0;
											$insert2['nra_qty'] = $this->Mmm->sanitize($_POST['qty_after_convert']);
											$insert2['mkt_qty'] = 0;
											$insert2['tac_qty'] = 0;
											$insert2['direct_qty'] = 0;
										}elseif($location == 'Makati'){
											$insert2['tayud_qty'] = 0;
											$insert2['nra_qty'] = 0;
											$insert2['mkt_qty'] = $this->Mmm->sanitize($_POST['qty_after_convert']);
											$insert2['tac_qty'] = 0;
											$insert2['direct_qty'] = 0;
										}elseif($location == 'Tacloban'){
											$insert2['tayud_qty'] = 0;
											$insert2['nra_qty'] = 0;
											$insert2['mkt_qty'] = 0;
											$insert2['tac_qty'] = $this->Mmm->sanitize($_POST['qty_after_convert']);
											$insert2['direct_qty'] = 0;
										}
										$this->Mmm->dbInsert("inventory_location",$insert2,"Inserted new quantity for item");
									}else{
										$this->Abas->sysMsg("errmsg", "There was an error converting the item. Please contact your administrator." );
										die();
									}
								}
							}
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error converting the item. Please contact your administrator." );
							die();
						}

						if($deduct){
							$location = $_SESSION['abas_login']['user_location'];
							if($location == 'Tayud'){
								$qty_loc = 'tayud_qty';
							}elseif($location == 'NRA'){
								$qty_loc = 'nra_qty';
							}elseif($location == 'Makati'){
								$qty_loc = 'mkt_qty';
							}elseif($location == 'Tacloban'){
								$qty_loc = 'tac_qty';
							}
							$deduct_qty = $_POST['stock_qty'] - $_POST['qty_to_convert'];
							$sql3 = "UPDATE inventory_location SET ".$qty_loc."=".$deduct_qty." WHERE item_id=".$_POST['stock_item_id'];
							$query3 = $this->db->query($sql3);
							if($query3){
								$this->Abas->sysMsg("sucmsg", "Successfully converted.");
								$this->Abas->sysNotif("Item Unit Conversion", $_SESSION['abas_login']['fullname']." has  converted item.","Inventory","info");
							}else{
								$this->Abas->sysMsg("errmsg", "There was an error converting the item. Please contact your administrator." );
								die();
							}
						}
					}else{
						$this->Abas->sysMsg("errmsg", "No Data submitted.");
						die();
					}
					$this->Abas->redirect(HTTP_PATH."inventory/item_list");
					break;
			}
		}
		public function print_qr_code($item_id){
			$data = array();
			$data['item']	=	$this->Inventory_model->getItem($item_id);
			$qty	=	$this->Inventory_model->getItemQty($item_id);

			$location = $_SESSION['abas_login']['user_location'];
			if($location == 'Tayud'){
				$data['qty_loc'] = $qty[0]['tayud_qty'];
			}elseif($location == 'NRA'){
				$data['qty_loc']  = $qty[0]['nra_qty'];
			}elseif($location == 'Makati'){
				$data['qty_loc'] = $qty[0]['mkt_qty'];
			}

			$this->load->view("inventory/items/print_qr_code.php",$data);
		}
		public function qr_selected_item($item_id){
			$data['item']	=	$this->Inventory_model->getItem($item_id);
			echo json_encode($data['item']);
		}
		public function print_rr_qr_code($rr_id){
			$data = array();
			$data['delivery'] = $this->Inventory_model->getDelivery($rr_id);
			$data['deliveries'] = $this->Inventory_model->getDeliveryDetails($rr_id);
			$this->load->view("inventory/print_receiving_qr_code.php",$data);
		}
		public function qr_code_scanner(){
			$this->load->view("inventory/qr_code_reader_tool.php");
		}
		public function qr_code_scanner_data($item_id,$inventory_qty_id=''){
			$data['item']	=	$this->Inventory_model->getItem($item_id);
			if($inventory_qty_id!=''){
				$data['item']['inventory_qty'] = $this->Inventory_model->getInventoryQuantityDetail($inventory_qty_id);
				$data['item']['delivery'] = $this->Inventory_model->getDelivery($data['item']['inventory_qty'][0]->delivery_id);
				$data['item']['supplier'] = $this->Abas->getSupplier($data['item']['delivery'][0]['supplier_id']);
				$data['item']['company'] = $this->Abas->getCompany($data['item']['delivery'][0]['company_id']);
			}
			echo json_encode($data);
		}
		public function qr_code_read_delivery_data($item_id,$delivery_id='',$location=''){
			$data['item']	=	$this->Inventory_model->getItem($item_id);
			$qty = $this->Inventory_model->getItemQty($item_id);
			if($location==''){
				$location = $_SESSION['abas_login']['user_location'];	
			}
			if($location == 'Tayud'){
				$data['item']['quantity'] = $qty[0]['tayud_qty'];
			}elseif($location == 'NRA'){
				$data['item']['quantity'] = $qty[0]['nra_qty'];
			}elseif($location == 'Makati'){
				$data['item']['quantity'] = $qty[0]['makati_qty'];
			}

			if($delivery_id!=''){
				$data['item']['delivery'] = $this->Inventory_model->getDelivery($delivery_id);
				$data['item']['supplier'] = $this->Abas->getSupplier($data['item']['delivery'][0]['supplier_id']);
			}
			echo json_encode($data['item']);
		}
		public function notice_of_discrepancy($action,$id=NULL){
			$data=array();
			switch ($action) {
				case 'load':

					$table = "inventory_notice_of_discrepancy";

					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);
						foreach($data['rows'] as $ctr=>$nod) {
							if($nod['purchase_order_id']) {
								$po	=	$this->Inventory_model->getPO($nod['purchase_order_id']);
								$data['rows'][$ctr]['purchase_order_number']	=	"PO# ".$po[0]['control_number'] . " (TS Code No. ".$po[0]['id'].")";
							}
							if($nod['created_by']) {
								$created_by							=	$this->Abas->getUser($nod['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if($nod['created_on']) {
								$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($nod['created_on']));
							}
							if($nod['company_id']) {
								$company							=	$this->Abas->getCompany($nod['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
					break;
				case 'listview':
					$data['viewfile']	=	"inventory/notice_of_discrepancy/listview.php";
					$this->load->view('gentlella_container.php',$data);
					break;
				case 'add':
					$this->load->view("inventory/notice_of_discrepancy/form.php",$data);
					break;
				case 'insert':
					$po_id = $this->Mmm->sanitize($_POST['po_id']);
					$sql = "SELECT * FROM inventory_notice_of_discrepancy WHERE purchase_order_id=".$po_id;
					$query = $this->db->query($sql);
					if(count($query->result())>=1){
						$this->Abas->sysMsg("warnmsg", "This PO has Notice of Discrepancy already, kindly verify.");
					}else{
						$control_number = $this->Abas->getNextSerialNumber('inventory_notice_of_discrepancy',$this->Mmm->sanitize($_POST['company_id']));
						$insert['control_number']	=	$control_number;
						$insert['company_id']		=	$this->Mmm->sanitize($_POST['company_id']);
						$insert['supplier_id']		=	$this->Mmm->sanitize($_POST['supplier_id']);
						$insert['purchase_order_id']=	$po_id;
						//$insert['reason_of_discrepancy'] =	$this->Mmm->sanitize($_POST['reason']);
						$insert['date_of_delivery'] =	$this->Mmm->sanitize($_POST['date_delivery']);
						$insert['delivery_receipt_number'] =	$this->Mmm->sanitize($_POST['dr_no']);
						$insert['vehicle_plate_number'] =	$this->Mmm->sanitize($_POST['plate_no']);
						$insert['name_of_driver'] =	$this->Mmm->sanitize($_POST['driver']);
						$insert['other_remarks'] =	$this->Mmm->sanitize($_POST['other_remarks']);
						$insert['created_by']		=	$_SESSION['abas_login']['userid'];
						$insert['created_on']		=	date("Y-m-d H:i:s");
						$insert['status']			=	"Draft";

						$po_id = $this->Mmm->sanitize($_POST['po_id']);
						$check	=	$this->Mmm->dbInsert("inventory_notice_of_discrepancy", $insert, "New NoD created for PO with transaction code no. ".$po_id);

						if($check){
							$lastInserted  =	$this->Abas->getLastIDByTable('inventory_notice_of_discrepancy');
							if(isset($_POST['quantity_dr'])) {
								foreach($_POST['quantity_dr'] as $ctr=>$val) {
									$multiInsert[$ctr]['notice_of_discrepancy_id']		=	$lastInserted;
									$multiInsert[$ctr]['item_id']		=	$this->Mmm->sanitize($_POST['item_id'][$ctr]);
									$multiInsert[$ctr]['unit']	=	$this->Mmm->sanitize($_POST['unit'][$ctr]);
									$multiInsert[$ctr]['packaging']	=	$this->Mmm->sanitize($_POST['packaging'][$ctr]);
									$multiInsert[$ctr]['unit_price']	=	$this->Mmm->sanitize($_POST['unit_price'][$ctr]);
									$multiInsert[$ctr]['quantity_po']	=	$this->Mmm->sanitize($_POST['quantity_po'][$ctr]);
									$multiInsert[$ctr]['quantity_dr']	=	$this->Mmm->sanitize($_POST['quantity_dr'][$ctr]);
									$multiInsert[$ctr]['quantity_received']	=	$this->Mmm->sanitize($_POST['quantity_received'][$ctr]);
									$multiInsert[$ctr]['remarks']		=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
								}
								$checkMulti		=	$this->Mmm->multiInsert("inventory_notice_of_discrepancy_details", $multiInsert, "Details for Notice of Discrepancy with Transaction Code No. ".$lastInserted);
								if($checkMulti){
									$this->Abas->sysMsg("sucmsg", "Successfully created Notice of Discrepancy for PO with transaction code no. ".$po_id);
									$this->Abas->sysNotif("New Notice of Discrepancy", $_SESSION['abas_login']['fullname']." has created Notice of Discrepancy for PO with transaction code no. ".$po_id,"Inventory","info");
								}else{
									$this->Abas->sysMsg("errmsg", "There was an error occured while saving the Notice of Discrepancy, please contact Administrator!");
									$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
									die();
								}
							}else{
								$this->Abas->sysMsg("errmsg", "There was an error occured while saving the Notice of Discrepancy, please contact Administrator!");
								$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
								die();
							}
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error occured while saving the Notice of Discrepancy, please contact Administrator!");
							$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
							die();
						}
					}
					$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
					break;
				case 'view':
					$data['nod'] = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$data['nod_details'] = $this->Inventory_model->getNoticeOfDiscrepancyDetails($id);
					$data['viewfile']	=	"inventory/notice_of_discrepancy/view.php";
					$this->load->view('gentlella_container.php',$data);
					break;
				case 'edit':
					$data['nod'] = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$data['nod_details'] = $this->Inventory_model->getNoticeOfDiscrepancyDetails($id);
					$this->load->view("inventory/notice_of_discrepancy/form.php",$data);
					break;
				case 'update':
					$update['company_id']		=	$this->Mmm->sanitize($_POST['company_id']);
					$update['supplier_id']		=	$this->Mmm->sanitize($_POST['supplier_id']);
					$update['purchase_order_id']=	$this->Mmm->sanitize($_POST['po_id']);
					//$update['reason_of_discrepancy'] =	$this->Mmm->sanitize($_POST['reason']);
					$update['date_of_delivery'] =	$this->Mmm->sanitize($_POST['date_delivery']);
					$update['delivery_receipt_number'] =	$this->Mmm->sanitize($_POST['dr_no']);
					$update['vehicle_plate_number'] =	$this->Mmm->sanitize($_POST['plate_no']);
					$update['name_of_driver'] =	$this->Mmm->sanitize($_POST['driver']);
					$update['other_remarks'] =	$this->Mmm->sanitize($_POST['other_remarks']);
					$update['created_by']		=	$_SESSION['abas_login']['userid'];
					$update['created_on']		=	date("Y-m-d H:i:s");
					$update['status']			=	"Draft";

					$po_id = $this->Mmm->sanitize($_POST['po_id']);
					$check	=	$this->Mmm->dbUpdate("inventory_notice_of_discrepancy", $update,$id,"Edited NoD for PO with transaction code no. ".$po_id);

					if($check){
						
						$delete_details = $this->db->query("DELETE FROM inventory_notice_of_discrepancy_details WHERE notice_of_discrepancy_id=".$id);

						if($delete_details){
							if(isset($_POST['quantity_dr'])) {
								foreach($_POST['quantity_dr'] as $ctr=>$val) {
									$multiInsert[$ctr]['notice_of_discrepancy_id']		=	$id;
									$multiInsert[$ctr]['item_id']		=	$this->Mmm->sanitize($_POST['item_id'][$ctr]);
									$multiInsert[$ctr]['unit_price']	=	$this->Mmm->sanitize($_POST['unit_price'][$ctr]);
									$multiInsert[$ctr]['quantity_po']	=	$this->Mmm->sanitize($_POST['quantity_po'][$ctr]);
									$multiInsert[$ctr]['quantity_dr']	=	$this->Mmm->sanitize($_POST['quantity_dr'][$ctr]);
									$multiInsert[$ctr]['quantity_received']	=	$this->Mmm->sanitize($_POST['quantity_received'][$ctr]);
									$multiInsert[$ctr]['remarks']		=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
								}
								$checkMulti		=	$this->Mmm->multiInsert("inventory_notice_of_discrepancy_details", $multiInsert, "Details for Notice of Discrepancy with Transaction Code No. ".$lastInserted);
								if($checkMulti){
									$this->Abas->sysMsg("sucmsg", "Successfully edited Notice of Discrepancy for PO with transaction code no. ".$po_id);
									$this->Abas->sysNotif("Edited Notice of Discrepancy", $_SESSION['abas_login']['fullname']." has edited Notice of Discrepancy for PO with transaction code no. ".$po_id,"Inventory","info");
								}else{
									$this->Abas->sysMsg("errmsg", "There was an error occured while saving the Notice of Discrepancy, please contact Administrator!");
									$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
									die();
								}
							}else{
								$this->Abas->sysMsg("errmsg", "There was an error occured while saving the NoD, please contact Administrator!");
								$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
								die();
							}
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error occured while saving the Notice of Discrepancy, please contact Administrator!");
							$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
							die();
						}
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while saving the Notice of Discrepancy, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
						die();
					}
					$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/listview");
					break;
				case 'submit':
					$nod = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$po_id	=	$nod->purchase_order_id;

					$check	=	$this->Mmm->query("UPDATE inventory_notice_of_discrepancy SET status='For Verification' WHERE id=".$id,"Updated status of NOD Tscode".$id." to Verification.");
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully submitted Notice of Discrepancy for PO with transaction code no. ".$po_id);
						$this->Abas->sysNotif("Submit Notice of Discrepancy", $_SESSION['abas_login']['fullname']." has submitted Notice of Discrepancy for PO with transaction code no. ".$po_id. " for Verification.","Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of Notice of Discrepancy, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					}
					$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					break;
				case 'verify':
					$nod = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$po_id	=	$nod->purchase_order_id;
					$user_id = $_SESSION['abas_login']['userid'];
					$date_now = date('Y-m-d H:m:s');
					$check	=	$this->Mmm->query("UPDATE inventory_notice_of_discrepancy SET status='For Level-1 Approval', verified_by=".$user_id.", verified_on='".$date_now."' WHERE id=".$id,"Updated status of NOD Tscode".$id." to Level-1 Approval.");
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully verified Notice of Discrepancy for PO with transaction code no. ".$po_id);
						$this->Abas->sysNotif("Verify Notice of Discrepancy", $_SESSION['abas_login']['fullname']." has verified Notice of Discrepancy for PO with transaction code no. ".$po_id. " for next approval.","Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of Notice of Discrepancy, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					}
					$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					break;
				case 'approve_1':
					$nod = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$nod_details = $this->Inventory_model->getNoticeOfDiscrepancyDetails($id);
					$po_id	=	$nod->purchase_order_id;
					$user_id = $_SESSION['abas_login']['userid'];
					$date_now = date('Y-m-d H:m:s');
					$over = false;
					foreach($nod_details as $details){
						if($details['remarks']=='Over Delivery'){
							$over = true;
						}
					}
					if($over==true){
						$check	=	$this->Mmm->query("UPDATE inventory_notice_of_discrepancy SET status='For Level-2 Approval', level1_approved_by=".$user_id.", level1_approved_on='".$date_now."' WHERE id=".$id,"Updated status of NOD Tscode".$id." to Level-2 Approval.");
					}else{
						$check	=	$this->Mmm->query("UPDATE inventory_notice_of_discrepancy SET status='Approved' WHERE id=".$id,"Updated status of NOD Tscode".$id." to Approved.");
					}
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully approved Notice of Discrepancy for PO with transaction code no. ".$po_id);
						$this->Abas->sysNotif("Approve Notice of Discrepancy", $_SESSION['abas_login']['fullname']." has approved Notice of Discrepancy for PO with transaction code no. ".$po_id. " for next approval.","Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of Notice of Discrepancy, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					}
					$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					break;
				case 'approve_2':
					$nod = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$po_id	=	$nod->purchase_order_id;
					$user_id = $_SESSION['abas_login']['userid'];
					$date_now = date('Y-m-d H:m:s');
					$check	=	$this->Mmm->query("UPDATE inventory_notice_of_discrepancy SET status='For Level-3 Approval', level2_approved_by=".$user_id.", level2_approved_on='".$date_now."' WHERE id=".$id,"Updated status of NOD Tscode".$id." to Level-3 Approval.");
					$next_approval = " for final approval.";
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully approved Notice of Discrepancy for PO with transaction code no. ".$po_id);
						$this->Abas->sysNotif("Approve Notice of Discrepancy", $_SESSION['abas_login']['fullname']." has approved Notice of Discrepancy for PO with transaction code no. ".$po_id. $next_approval,"Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of Notice of Discrepancy, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					}
					$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					break;
				case 'approve_3':
					$nod = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$po_id	=	$nod->purchase_order_id;
					$user_id = $_SESSION['abas_login']['userid'];
					$date_now = date('Y-m-d H:m:s');
					$check	=	$this->Mmm->query("UPDATE inventory_notice_of_discrepancy SET status='Approved', level3_approved_by=".$user_id.", level3_approved_on='".$date_now."' WHERE id=".$id,"Updated status of NOD Tscode".$id." to Approved.");
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully approved Notice of Discrepancy for PO with transaction code no. ".$po_id);
						$this->Abas->sysNotif("Approve Notice of Discrepancy", $_SESSION['abas_login']['fullname']." has approved Notice of Discrepancy for PO with transaction code no. ".$po_id,"Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of Notice of Discrepancy, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					}
					$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					break;
				case 'cancel':
					$nod = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$po_id	=	$nod->purchase_order_id;
					$user_id = $_SESSION['abas_login']['userid'];
					$date_now = date('Y-m-d H:m:s');
					$check	=	$this->Mmm->query("UPDATE inventory_notice_of_discrepancy SET status='Cancelled', cancelled_by=".$user_id.",cancelled_on='".$date_now."' WHERE id=".$id,"Updated status of NOD Tscode".$id." to Cancelled.");
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully cancelled Notice of Discrepancy for PO with transaction code no. ".$po_id);
						$this->Abas->sysNotif("Cancel Notice of Discrepancy", $_SESSION['abas_login']['fullname']." has cancelled Notice of Discrepancy for PO with transaction code no. ".$po_id,"Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of Notice of Discrepancy, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					}
					$this->Abas->redirect(HTTP_PATH."inventory/notice_of_discrepancy/view/".$id);
					break;
				case 'print':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

					$data['nod'] = $this->Inventory_model->getNoticeOfDiscrepancy($id);
					$data['nod_details'] = $this->Inventory_model->getNoticeOfDiscrepancyDetails($id);
					$data['prepared_by_details'] = $this->Abas->getUser($data['nod']->created_by);
					$data['verified_by_details'] = $this->Abas->getUser($data['nod']->verified_by);
					$data['level1_approved_by_details'] = $this->Abas->getUser($data['nod']->level1_approved_by);
					$data['level2_approved_by_details'] = $this->Abas->getUser($data['nod']->level2_approved_by);
					$data['level3_approved_by_details'] = $this->Abas->getUser($data['nod']->level3_approved_by);

					$this->load->view("inventory/notice_of_discrepancy/print.php",$data);
					break;
			}
		}
		public function checkNoticeOfDiscrepancy($po_id){
			$nod = $this->Inventory_model->getNoticeOfDiscrepancyByPO($po_id);
			header('Content-Type: application/json');
			echo json_encode($nod);
			exit();
		}
		public function loadNoticeOfDiscrepancyItems($nod_id){
			$nod_details = $this->Inventory_model->getNoticeOfDiscrepancyDetails($nod_id);
			//header('Content-Type: application/json');
			echo json_encode($nod_details);
			exit();
		}
		public function audit($action,$id=NULL){
			switch ($action) {
				case 'load':
					$table = "inventory_audit";

					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);
						foreach($data['rows'] as $ctr=>$audit) {
							
							if($audit['created_by']) {
								$created_by							=	$this->Abas->getUser($audit['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if($audit['verified_by']) {
								$created_by							=	$this->Abas->getUser($audit['verified_by']);
								$data['rows'][$ctr]['verified_by']	=	$created_by['full_name'];
							}
							if($audit['noted_by']) {
								$created_by							=	$this->Abas->getUser($audit['noted_by']);
								$data['rows'][$ctr]['noted_by']	=	$created_by['full_name'];
							}
							if($audit['posted_by']) {
								$created_by							=	$this->Abas->getUser($audit['posted_by']);
								$data['rows'][$ctr]['posted_by']	=	$created_by['full_name'];
							}
							
							if($audit['audit_date']) {
								$data['rows'][$ctr]['audit_date']	=	date("j F Y", strtotime($audit['audit_date']));
							}
							if($audit['company_id']) {
								$company							=	$this->Abas->getCompany($audit['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							if($audit['type_of_inventory']) {
								$type							=	$this->Abas->getItemCategory($audit['type_of_inventory']);
								$data['rows'][$ctr]['type_of_inventory']	=	$type->category;
								date("Y-m-d H:i:s");
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
					break;
				case 'listview':
					$data['viewfile']	=	"inventory/audit/listview.php";
					$this->load->view('gentlella_container.php',$data);
					break;

				case 'add':
					$data['companies']	=	$this->Abas->getCompanies();
					$data['categories']	=	$this->Abas->getItemCategory();
					$data['inventory_locations']		=	$this->Abas->getUserLocations();
					$this->load->view("inventory/audit/form.php",$data);
					break;

				case 'insert':
					$insert = array();
					if(isset($_POST['company'])){
						$control_number = $this->Abas->getNextSerialNumber('inventory_audit',$this->Mmm->sanitize($_POST['company']));
						$insert['control_number']	=	$control_number;
						$insert['company_id']		=	$this->Mmm->sanitize($_POST['company']);
						$insert['audit_date'] 		=	$this->Mmm->sanitize($_POST['audit_date']);
						$insert['type_of_inventory'] =	$this->Mmm->sanitize($_POST['type_of_inventory']);
						$insert['location'] 		=	$this->Mmm->sanitize($_POST['location']);
						$insert['audited_by']		=	$this->Mmm->sanitize($_POST['audited_by']);
						$insert['created_by']		=	$_SESSION['abas_login']['userid'];
						$insert['created_on']		=	date("Y-m-d H:i:s");
						$insert['status']			=	"Draft";

						$check	=	$this->Mmm->dbInsert("inventory_audit", $insert, "New Inventory Audit (Count Sheet) was created by ".$_SESSION['abas_login']['fullname']);

						if($check){
							$lastInserted  =	$this->Abas->getLastIDByTable('inventory_audit');

							$items = $this->Inventory_model->getItemsForAudit($insert['type_of_inventory'],$insert['company_id'],$insert['location']);
							$multiInsertItems = array();
							foreach($items as $ctrx=>$row){
								$multiInsertItems[$ctrx]['audit_id']		=	$lastInserted;
								$multiInsertItems[$ctrx]['inventory_quantity_id']			=	$row->id;
								$multiInsertItems[$ctrx]['item_id']			=	$row->item_id;
								$multiInsertItems[$ctrx]['unit']			=	$row->unit;
								$multiInsertItems[$ctrx]['unit_price']		=	$row->unit_price;
								//$multiInsertItems[$ctrx]['current_qty']		=	$row->qty;
								$multiInsertItems[$ctrx]['current_qty']		=	($row->quantity-$row->quantity_issued);

								$multiInsertItems[$ctrx]['counted_qty']		=	0;
								$multiInsertItems[$ctrx]['shelf_number']	=	'';
								$multiInsertItems[$ctrx]['location']		=	$row->location;
								$multiInsertItems[$ctrx]['stat']			=	1;
								$multiInsertItems[$ctrx]['remarks']			=	'';
							}
							$checkMultiItems	=	$this->Mmm->multiInsert("inventory_audit_details", $multiInsertItems, "Added Items for Inventory Audit with Transaction Code No. ".$lastInserted);

							$multiInsertDocs = array();
							if(isset($_POST['document_name'])) {
								foreach($_POST['document_name'] as $ctr=>$val) {
									$multiInsertDocs[$ctr]['audit_id']		=	$lastInserted;
									$multiInsertDocs[$ctr]['document_name']		=	$this->Mmm->sanitize($_POST['document_name'][$ctr]);
									$multiInsertDocs[$ctr]['last_used']	=	$this->Mmm->sanitize($_POST['last_used'][$ctr]);
									$multiInsertDocs[$ctr]['date_last_used']	=	$this->Mmm->sanitize($_POST['date_last_used'][$ctr]);
									$multiInsertDocs[$ctr]['first_unused']	=	$this->Mmm->sanitize($_POST['first_unused'][$ctr]);
								}
								$checkMultiDocs		=	$this->Mmm->multiInsert("inventory_audit_cutoff_documents", $multiInsertDocs, "Added Cut-off Documents for Inventory Audit with Transaction Code No. ".$lastInserted);
								if($checkMultiDocs){
									$this->Abas->sysMsg("sucmsg", "Successfully created Inventory Audit (Count Sheet) with transaction code no. ".$lastInserted);
									$this->Abas->sysNotif("New Inventory Audit (Count Sheet)", $_SESSION['abas_login']['fullname']." has created Inventory Audit (Count Sheet) with transaction code no. ".$lastInserted,"Inventory","info");
								}else{
									$this->Abas->sysMsg("errmsg", "There was an error occured while saving the Inventory Audit (Count Sheet), please contact Administrator!");
									$this->Abas->redirect(HTTP_PATH."inventory/audit/listview");
									die();
								}
							}else{
								$this->Abas->sysMsg("errmsg", "There was an error occured while saving the  Inventory Audit (Count Sheet), please contact Administrator!");
								$this->Abas->redirect(HTTP_PATH."inventory/audit/listview");
								die();
							}
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error occured while saving the  Inventory Audit (Count Sheet), please contact Administrator!");
							$this->Abas->redirect(HTTP_PATH."inventory/audit/listview");
							die();
						}
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/listview");

				break;

				case 'view':
					$data = array();
					$data['audit'] = $this->Inventory_model->getInventoryAudit($id);
					$data['audit_details'] = $this->Inventory_model->getInventoryAuditDetails($id);
					$data['audit_cutoff_documents'] = $this->Inventory_model->getInventoryAuditCutOffDocuments($id);
					$data['viewfile']	=	"inventory/audit/view.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'add_item_count':
					$data = array();
					$data['audit'] = $this->Inventory_model->getInventoryAudit($id);
					$this->load->view("inventory/audit/item_count_form.php",$data);
				break;

				case 'insert_item_count':
					if(isset($_POST['audit_id'])){
						if($this->Inventory_model->checkItemIfAudited($_POST['audit_id'],$_POST['item_id'])==FALSE){
							$insert['audit_id']	=	$this->Mmm->sanitize($_POST['audit_id']);
							$insert['item_id']		=	$this->Mmm->sanitize($_POST['item_id']);
							$insert['current_qty'] 		=	$this->Mmm->sanitize($_POST['quantity_per_book']);
							$insert['counted_qty'] =	$this->Mmm->sanitize($_POST['quantity_per_count']);
							$insert['shelf_number']		=	$this->Mmm->sanitize($_POST['shelf_number']);
							$insert['remarks']		=	$this->Mmm->sanitize($_POST['remarks']);
							$insert['stat']			=	1;
							$check	=	$this->Mmm->dbInsert("inventory_audit_details", $insert, "Updated Item count for Inventory Audit with TScode No. ".$insert['audit_id']);
							$item = $this->Inventory_model->getItem($insert['item_id']);
							if($check){
								$this->Abas->sysMsg("sucmsg", "Successfully added Item Count for ".$item[0]['description'].".");
								$this->Abas->sysNotif("New Item Count", $_SESSION['abas_login']['fullname']." has added Item Count for ".$item[0]['description'].".","Inventory","info");
								$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$insert['audit_id']);
							}else{
								$this->Abas->sysMsg("errmsg", "There was an error occured while adding the Item Count for ".$item[0]['description'].", please contact Administrator!");
								$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$insert['audit_id']);
								die();
							}
						}else{
							$this->Abas->sysMsg("errmsg", "You already added this item on the count sheet.");
							$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$_POST['audit_id']);
						}
					}
				break;

				case 'update_item_count':
					$data = array();
					if(isset($_POST['item_id'])){
						$audit = $this->Inventory_model->getInventoryAudit($id);
						$location = $audit->location;
						$sql_del = "DELETE FROM inventory_audit_details WHERE audit_id=".$id;
						$query_del = $this->db->query($sql_del);
						$multiInsertDetails= array();
						if($query_del){
							foreach($_POST['item_id'] as $ctry=>$val){
								$multiInsertDetails[$ctry]['audit_id']		=	$id;
								$multiInsertDetails[$ctry]['inventory_quantity_id']			=	$this->Mmm->sanitize($_POST['inventory_qty_id'][$ctry]);
								$multiInsertDetails[$ctry]['item_id']			=	$this->Mmm->sanitize($_POST['item_id'][$ctry]);
								$multiInsertDetails[$ctry]['unit']			=	$this->Mmm->sanitize($_POST['unit'][$ctry]);
								$multiInsertDetails[$ctry]['unit_price']		=	$this->Mmm->sanitize($_POST['unit_price'][$ctry]);
								$multiInsertDetails[$ctry]['current_qty']		=	$this->Mmm->sanitize($_POST['current_qty'][$ctry]);
								$multiInsertDetails[$ctry]['counted_qty']		=	$this->Mmm->sanitize($_POST['counted_qty'][$ctry]);
								$multiInsertDetails[$ctry]['shelf_number']	=	$this->Mmm->sanitize($_POST['shelf_number'][$ctry]);
								$multiInsertDetails[$ctry]['location']		=	$location;
								$multiInsertDetails[$ctry]['stat']			=	1;
								$multiInsertDetails[$ctry]['remarks']			=	$this->Mmm->sanitize($_POST['remarks'][$ctry]);
							}
						}
						
						$checkMultiDetails=	$this->Mmm->multiInsert("inventory_audit_details",$multiInsertDetails, "Added Inventory Count Details for Inventory Audit with Transaction Code No. ".$id);

						if($checkMultiDetails){
							$this->Abas->sysMsg("sucmsg", "Successfully updated Inventory Audit (Count Sheet Details) with transaction code no. ".$lastInserted);
							$this->Abas->sysNotif("Updated Inventory Audit (Count Sheet Details)", $_SESSION['abas_login']['fullname']." has updated Inventory Audit (Count Sheet Details) with transaction code no. ".$id,"Inventory","info");
							$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error occured while updating the  Inventory Audit (Count Sheet Details), please contact Administrator!");
								$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
								die();
						}

					}	
				break;

				case 'remove_item_count':
					$sql1 = "SELECT audit_id FROM inventory_audit_details WHERE id=".$id;
					$query1 = $this->db->query($sql1);
					if($query1){
						$row = $query1->row();
						$sql2 = "DELETE FROM inventory_audit_details WHERE id=".$id;
						$query2 = $this->Mmm->query($sql2,"Remove Item Count for Inventory Audit with TSCode No.".$row->audit_id);
						if($query2){
							$this->Abas->sysMsg("sucmsg", "Successfully removed Item Count for Inventory Audit with TSCode No.".$row->audit_id);
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error occurred while removing this Item Count.");
						}
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$row->audit_id);
				break;

				case 'edit':
					$data = array();
					$data['companies']	=	$this->Abas->getCompanies();
					$data['categories']	=	$this->Abas->getItemCategory();
					$data['inventory_locations']		=	$this->Inventory_model->getInventoryLocation();
					$data['audit'] = $this->Inventory_model->getInventoryAudit($id);
					$data['audit_cutoff_documents'] = $this->Inventory_model->getInventoryAuditCutOffDocuments($id);
					$this->load->view("inventory/audit/form.php",$data);
				break;
				
				case 'update':
					
					if(isset($_POST['audit_date'])){
						
						$update['audit_date'] 		=	$this->Mmm->sanitize($_POST['audit_date']);
						//$update['type_of_inventory'] =	$this->Mmm->sanitize($_POST['type_of_inventory']);
						//$update['location'] 		=	$this->Mmm->sanitize($_POST['location']);
						$update['audited_by']		=	$this->Mmm->sanitize($_POST['audited_by']);
						$update['created_by']		=	$_SESSION['abas_login']['userid'];
						$update['created_on']		=	date("Y-m-d H:i:s");
						$update['status']			=	"Draft";

						$check	=	$this->Mmm->dbUpdate("inventory_audit", $update,$id, "Inventory Audit (Count Sheet) was updated by ".$_SESSION['abas_login']['fullname']);

						if($check){

							$delete_details = $this->db->query("DELETE FROM inventory_audit_cutoff_documents WHERE audit_id=".$id);

							if(isset($_POST['document_name'])) {
								foreach($_POST['document_name'] as $ctr=>$val) {
									$multiInsert[$ctr]['audit_id']		=	$id;
									$multiInsert[$ctr]['document_name']		=	$this->Mmm->sanitize($_POST['document_name'][$ctr]);
									$multiInsert[$ctr]['last_used']	=	$this->Mmm->sanitize($_POST['last_used'][$ctr]);
									$multiInsert[$ctr]['date_last_used']	=	$this->Mmm->sanitize($_POST['date_last_used'][$ctr]);
									$multiInsert[$ctr]['first_unused']	=	$this->Mmm->sanitize($_POST['first_unused'][$ctr]);
								}
								$checkMulti		=	$this->Mmm->multiInsert("inventory_audit_cutoff_documents", $multiInsert, "Updated Cut-off Documents for Inventory Audit with Transaction Code No. ".$id);
								if($checkMulti){
									$this->Abas->sysMsg("sucmsg", "Successfully updated Inventory Audit (Count Sheet) with transaction code no. ".$id);
									$this->Abas->sysNotif("New Inventory Audit (Count Sheet)", $_SESSION['abas_login']['fullname']." has updated Inventory Audit (Count Sheet) with transaction code no. ".$id,"Inventory","info");
								}else{
									$this->Abas->sysMsg("errmsg", "There was an error occured while saving the Inventory Audit (Count Sheet), please contact Administrator!");
									$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
									die();
								}
							}else{
								$this->Abas->sysMsg("errmsg", "There was an error occured while saving the  Inventory Audit (Count Sheet), please contact Administrator!");
								$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
								die();
							}
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error occured while saving the  Inventory Audit (Count Sheet), please contact Administrator!");
							$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
							die();
						}
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);

				break;

				case 'submit':
					$audit_details = $this->Inventory_model->getInventoryAuditDetails($id);
					if(count($audit_details)>0){
						$check	=	$this->Mmm->query("UPDATE inventory_audit SET status='For Verification' WHERE id=".$id,"Updated status of Inventory Audit with TSCode No. ".$id." to Verification.");
						if($check){
							$this->Abas->sysMsg("sucmsg", "Successfully submitted Inventory Audit (Count Sheet) with TSCode No. ".$id);
							$this->Abas->sysNotif("Submit Inventory Audit", $_SESSION['abas_login']['fullname']." has submitted Inventory Audit (Count Sheet) with TSCode No. ".$id." for Verification.","Inventory","info");
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of Inventory Audit, please contact Administrator!");
							$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
							die();
						}
					}else{
						$this->Abas->sysMsg("errmsg", "Please add Actual Item Count before submitting.");
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
				break;
				
				case 'verify':
					$user		=	$_SESSION['abas_login']['userid'];
					$now		=	date("Y-m-d H:i:s");
					$check	=	$this->Mmm->query("UPDATE inventory_audit SET status='For Note',verified_by=".$user.",verified_on='".$now."' WHERE id=".$id,"Updated status of Inventory Audit with TSCode No. ".$id." to Noting.");
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully verified Inventory Audit (Count Sheet) with TSCode No. ".$id);
						$this->Abas->sysNotif("Verify Inventory Audit", $_SESSION['abas_login']['fullname']." has verified Inventory Audit (Count Sheet) with TSCode No. ".$id." for  Note.","Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of  Inventory Audit, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
						die();
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
				break;

				case 'note':
					$user		=	$_SESSION['abas_login']['userid'];
					$now		=	date("Y-m-d H:i:s");
					$check	=	$this->Mmm->query("UPDATE inventory_audit SET status='For Approval',noted_by=".$user.",noted_on='".$now."' WHERE id=".$id,"Updated status of Inventory Audit with TSCode No. ".$id." to Noted.");
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully noted Inventory Audit (Count Sheet) with TSCode No. ".$id);
						$this->Abas->sysNotif("Note Inventory Audit", $_SESSION['abas_login']['fullname']." has noted Inventory Audit (Count Sheet) with TSCode No. ".$id." for Approval.","Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of  Inventory Audit, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
						die();
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
				break;

				case 'approve':
					$user		=	$_SESSION['abas_login']['userid'];
					$now		=	date("Y-m-d H:i:s");
					$check	=	$this->Mmm->query("UPDATE inventory_audit SET status='For Posting',approved_by=".$user.",approved_on='".$now."' WHERE id=".$id,"Updated status of Inventory Audit with TSCode No. ".$id." to Approved.");
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully approved Inventory Audit (Count Sheet) with TSCode No. ".$id);
						$this->Abas->sysNotif("Approve Inventory Audit", $_SESSION['abas_login']['fullname']." has approved Inventory Audit (Count Sheet) with TSCode No. ".$id." for Approval.","Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of  Inventory Audit, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
						die();
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
				break;

				case 'return':
					$audit_details = $this->Inventory_model->getInventoryAuditDetails($id);
					if(count($audit_details)>0){
						$check	=	$this->Mmm->query("UPDATE inventory_audit SET status='Draft' WHERE id=".$id,"Updated status of Inventory Audit with TSCode No. ".$id." to Draft.");
						if($check){
							$this->Abas->sysMsg("sucmsg", "Successfully returned Inventory Audit (Count Sheet) with TSCode No. ".$id);
							$this->Abas->sysNotif("Return Inventory Audit", $_SESSION['abas_login']['fullname']." has returned Inventory Audit (Count Sheet) with TSCode No. ".$id." to Draft.","Inventory","info");
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of Inventory Audit, please contact Administrator!");
							$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
							die();
						}
					}else{
						$this->Abas->sysMsg("errmsg", "Please add Actual Item Count before submitting.");
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
				break;

				case 'post':
					$user		=	$_SESSION['abas_login']['userid'];
					$now		=	date("Y-m-d H:i:s");
					$sql0 = "UPDATE inventory_audit SET status='Posted',posted_by=".$user.",posted_on='".$now."' WHERE id=".$id;
					$query0	=	$this->Mmm->query($sql0,"Updated status of Inventory Audit with TSCode No. ".$id." to 'Posted'.");
					if($query0){
						$audit_details = $this->Inventory_model->getInventoryAuditDetails($id);
						foreach($audit_details as $detail){
							
							/*$sql1 =	"UPDATE inventory_quantity SET stat=0 WHERE item_id=".$detail['item_id']." AND unit='".$detail['unit']."' AND unit_price=".$detail['unit_price']." AND company_id=".$audit->company_id." AND location='".$audit->location."' AND quantity>=quantity_issued";
							$query1 = $this->Mmm->query($sql1,"Closed the old quantity of item in order to post the newly audited quantity with Item TSCode No.".$detail['item_id']);
							
							$sql2 = "INSERT INTO inventory_quantity (item_id,delivery_id,unit,unit_price,company_id,location,quantity,quantity_issued,stat) VALUES(".$detail['item_id'].",0,'".$detail['unit']."',".$detail['unit_price'].",".$audit->company_id.",'".$audit->location."',".$detail['counted_qty'].",0,1)";
							$query2 = $this->Mmm->query($sql2,"Added the newly audited quantity on Item Inventory per company and location.");*/

							$sql1 =	"UPDATE inventory_quantity SET quantity=".$detail['counted_qty'].",quantity_issued=0 WHERE id=".$detail['inventory_quantity_id'];
							$query1 = $this->Mmm->query($sql1,"Closed the old quantity of item in order to post the newly audited quantity with Item TSCode No.".$detail['item_id']);

						}

						if($query1){
							$this->Abas->sysMsg("sucmsg", "Successfully posted Inventory Audit (Count Sheet) with TSCode No. ".$id);
							$this->Abas->sysNotif("Posted Inventory Audit", $_SESSION['abas_login']['fullname']." has posted Inventory Audit (Count Sheet) with TSCode No. ".$id." for Posted.","Inventory","info");
						}else{
							$this->Abas->sysMsg("errmsg", "Problem occured while updating the item quantity per company, please contact admin.");
								die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of  Inventory Audit, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
						die();
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
				break;

				case 'cancel':
					$check	=	$this->Mmm->query("UPDATE inventory_audit SET status='Cancelled' WHERE id=".$id,"Updated status of Inventory Audit with TSCode No. ".$id." to Noting.");
					if($check){
						$this->Abas->sysMsg("sucmsg", "Successfully cancelled Inventory Audit (Count Sheet) with TSCode No. ".$id);
						$this->Abas->sysNotif("Cancelled Inventory Audit", $_SESSION['abas_login']['fullname']." has cancelled Inventory Audit (Count Sheet) with TSCode No. ".$id." for Approval.","Inventory","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while updating the status of  Inventory Audit, please contact Administrator!");
						$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
						die();
					}
					$this->Abas->redirect(HTTP_PATH."inventory/audit/view/".$id);
				break;

				case 'print_manual_count_sheet':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['audit'] = $this->Inventory_model->getInventoryAudit($id);
					//$data['items'] = $this->Inventory_model->getItemsPerCategory($data['audit']->type_of_inventory);
					$data['items'] = $this->Inventory_model->getItemsForAudit($data['audit']->type_of_inventory,$data['audit']->company_id,$data['audit']->location);
					$data['category_name'] = $this->Abas->getItemCategory($data['audit']->type_of_inventory);
					$data['company'] = $this->Abas->getCompany($data['audit']->company_id);
					$this->load->view('inventory/audit/print_manual_count_sheet',$data);
				break;

				case 'print_inventory_count_sheet':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['audit'] = $this->Inventory_model->getInventoryAudit($id);
					$data['audit_details'] = $this->Inventory_model->getInventoryAuditDetails($id);
					$data['category_name'] = $this->Abas->getItemCategory($data['audit']->type_of_inventory);
					$data['audit_cutoff_documents'] = $this->Inventory_model->getInventoryAuditCutOffDocuments($id);
					$this->load->view('inventory/audit/print_inventory_count_sheet',$data);
				break;


			}
		}
		public function stock_card($action){
			switch ($action) {
				case 'filter':
					$this->load->view('inventory/stock_card/filter.php');
				break;

				case 'result':
					$data = array();
					$item_id = $this->Mmm->sanitize($_POST['item_id']);
					$data['companies'] = $this->Abas->getCompanies();
					$data['item'] = $this->Inventory_model->getItem($item_id);
					$data['item_qty'] = $this->Inventory_model->getItemQty($item_id);
					$data['stock_in_out'] = $this->Inventory_model->getStockInOut($item_id);
					$data['viewfile'] = 'inventory/stock_card/result.php';
					$this->load->view('gentlella_container.php',$data);
				break;
			}
		}

		public function get_company_id($vessel_id){
			$vessel = $this->Abas->getVessel($vessel_id);
			if($vessel){
				$company = $this->Abas->getCompany($vessel->company);
				$data['company_id'] = $company->id;
				echo json_encode($data);
			}
		}

		public function receiving($action,$id=NULL,$idx=NULL){
			switch ($action) {
				case 'load':
					$table = "inventory_deliveries";
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);
						foreach($data['rows'] as $ctr=>$rr) {
							if($rr['amount']) {
								$data['rows'][$ctr]['amount']	=	number_format($rr['amount'],2,'.',',');
							}
							if($rr['tdate']) {
								$data['rows'][$ctr]['tdate']	=	date("j F Y", strtotime($rr['tdate']));
							}
							if($rr['created_by']) {
								$created_by							=	$this->Abas->getUser($rr['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if($rr['created_on']) {
								$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($rr['created_on']));
							}
							if($rr['company_id']) {
								$company							=	$this->Abas->getCompany($rr['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							if($rr['supplier_id']) {
								$supplier							=	$this->Abas->getSupplier($rr['supplier_id']);
								$data['rows'][$ctr]['supplier_name']	=	$supplier['name'];
							}
							if($rr['notice_of_discrepancy_id']!=0) {
								$data['rows'][$ctr]['discrepancy']	=	"Yes";
							}else{
								$data['rows'][$ctr]['discrepancy']	=	"No";
							}
							if($rr['is_cleared']==1) {
								$data['rows'][$ctr]['is_cleared']	=	"Yes";
							}else{
								$data['rows'][$ctr]['is_cleared']	=	"No";
							}
							if($rr['is_issued']==1) {
								$data['rows'][$ctr]['is_issued']	=	"Yes";
							}else{
								$data['rows'][$ctr]['is_issued']	=	"No";
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
					break;
				case 'listview':
					$data['viewfile']	=	"inventory/receiving/listview.php";
					$this->load->view('gentlella_container.php',$data);
					break;
				case 'add':
					$data['vessels']	=	$this->Abas->getVessels();
					$this->load->view("inventory/receiving/form.php",$data);
					break;
				case 'insert':
					if($_POST['selected_po']){
						$insert = array();
						$company_name = $_POST['company'];
						$supplier_name = $_POST['supplier'];
						$company_id = $this->Mmm->sanitize($_POST['selected_company']);
						$supplier_id = $this->Mmm->sanitize($_POST['selected_supplier']);
						$insert['control_number']	=	$this->Abas->getNextSerialNumber('inventory_deliveries',$company_id);
						$delivery_date = $this->Mmm->sanitize($_POST['delivery_date']);
						$insert['tdate']	=	$delivery_date;
						$delivery_no	=	$this->Mmm->sanitize($_POST['delivery_no']);
						$insert['delivery_no']	=	$delivery_no;
						$insert['sales_invoice_no']	=	$this->Mmm->sanitize($_POST['sales_invoice_no']);
						$po_id = $this->Mmm->sanitize($_POST['selected_po']);
						$insert['po_no']	=	$po_id;
						$insert['company_id']	=	$company_id;
						$insert['supplier_id']	=	$supplier_id;
						$insert['amount']	=	0;
						$insert['stat']	=	1;
						$insert['location']	=	$_SESSION['abas_login']['user_location'];
						$insert['remark']	=	$this->Mmm->sanitize($_POST['remarks']);
						$insert['created_on']	= date('Y-m-d H:m:s');
						$insert['created_by']	= $_SESSION['abas_login']['userid'];
						$insert['received_by']	= $this->Mmm->sanitize($_POST['received_by']);
						$checkInsert = $this->Mmm->dbInsert("inventory_deliveries",$insert,"Added New Receiving Report for ".$company_name." from supplier ".$supplier_name);
						if($checkInsert){
							$last_id_inserted = $this->Abas->getLastIDByTable('inventory_deliveries');
							$multiInsert = array();
							$grand_total = 0;
							foreach($_POST['item'] as $ctr=>$val){
								$delivery_id = $last_id_inserted;
								$multiInsert[$ctr]['delivery_id']	=	$last_id_inserted;
								$item_id = $this->Mmm->sanitize($_POST['item'][$ctr]);
								$multiInsert[$ctr]['item_id']	=	$item_id;
								$unit	=	$this->Mmm->sanitize($_POST['packaging'][$ctr]);
								$multiInsert[$ctr]['unit']	=	$unit;
								$unit_price	=	$this->Mmm->sanitize($_POST['price'][$ctr]);
								$multiInsert[$ctr]['unit_price']	=	$unit_price;
								$qty = abs($this->Mmm->sanitize($_POST['quantity'][$ctr]));
								$multiInsert[$ctr]['quantity']	=	$qty;
								$multiInsert[$ctr]['stat']	=	1;
								$grand_total = $grand_total + ($multiInsert[$ctr]['unit_price']*$qty);
								$location = $_SESSION['abas_login']['user_location'];
								

								/*insert item price history
								$insert_price  = array();
								$insert_price['item_id'] 		= $item_id;
								$insert_price['unit_price'] 	= $multiInsert[$ctr]['unit_price'];
								$insert_price['date_recorded'] 	= date('Y-m-d H:m:s');
								$insert_price['stat'] 			= 1;
								$checkInsertPrice = $this->Mmm->dbInsert('inventory_price_history',$insert_price,"Added new price history for item with Item ID No.".$item_id);*/
							
								//disabled since we will implement FIFO
								/*$location = $_SESSION['abas_login']['user_location'];
								$sql5			=	"SELECT * FROM inventory_quantity WHERE item_id=".$item_id." AND company_id=".$company_id." AND location='".$location."' AND stat=1";
								$query5			=	$this->db->query($sql5);
								$result5			=	$query5->result_array();
								
								if(count($result5)==0){
									$sql6 = "INSERT INTO inventory_quantity (item_id,company_id,location,quantity,stat) VALUES(".$item_id.",".$company_id.",'".$location."',".$qty.",1)";
									$query6 = $this->Mmm->query($sql6,"Added quantity on Inventory per company and location.");
								}else{
									$sql7 = "UPDATE inventory_quantity SET quantity	= (quantity + ".$qty.") WHERE item_id=".$item_id." AND company_id=".$company_id. " AND location='".$location."' AND stat=1";
									$query7 = $this->Mmm->query($sql7,"Added quantity on Inventory per company and location.");
								}
							
								$sql8	=	"UPDATE inventory_items SET qty	= (qty + ".$qty.") WHERE id	=".$item_id;
								$query8	=	$this->db->query($sql8);*/

								//converts the packaging to smallest unit that was set on the item's conversion table
								$packagings = $this->Inventory_model->getPackagingByItem($item_id);
								if(count($packagings)>0){
									foreach($packagings as $packaging){
										if($multiInsert[$ctr]['unit']==$packaging->packaging){
											$qty	=	($qty*$packaging->conversion);
											$unit   =    $packaging->unit;
											$unit_price = ($multiInsert[$ctr]['unit_price']/$packaging->conversion);
										}
									}
								}

								
								if($_POST['direct_del']<>1){//to check if direct delivery
									$qty_iss = 0; // if not do not add qty issued
								}elseif($_POST['direct_del']==1){
									$qty_iss = $qty; //if direct delivery, then put qty issued equal to qty received
								}

								//insert to inventory_quantity every receiving
								$sql6 = "INSERT INTO inventory_quantity (item_id,delivery_id,unit,unit_price,company_id,location,quantity,quantity_issued,stat) VALUES(".$item_id.",".$delivery_id.",'".$unit."',".$unit_price.",".$company_id.",'".$location."',".$qty.",".$qty_iss.",1)";
								$query6 = $this->Mmm->query($sql6,"Added quantity on Inventory per company and location.");

								$po = $this->Purchasing_model->getPurchaseOrder($po_id);
								$sql9	=	"UPDATE inventory_request_details SET status='For clearing' WHERE status='For Delivery' AND item_id=".$item_id." AND request_id=".$po['details'][0]['request_detail_id'];
								$query9	=	$this->db->query($sql9);

							}
							$checkMultiInsert = $this->Mmm->multiInsert('inventory_delivery_details',$multiInsert,'Added delivery items for RR with transaction code no.'.$last_id_inserted);
							if($checkMultiInsert){

								$entries_notification = FALSE;
								$msis_notification = FALSE;
								$nod_notification = FALSE;

								$sql10	=	"UPDATE inventory_deliveries SET amount	=".$grand_total." WHERE id	=".$delivery_id;
								$query10	=	$this->db->query($sql10);

								$sql11	=	"UPDATE inventory_po SET status='For clearing' WHERE id=".$po_id;
								$query11	=	$this->db->query($sql11);
								
								//create entries
								$insertTran['date']			=	$delivery_date;
								$insertTran['status']		=	'Active';
								$insertTran['remark']		=	"PO# ".$po['id']."	for ".$po['vessel_name'];
								$insertTran['stat']			=	1;
								$insertTran['reference_table']		=	'inventory_deliveries';
								$insertTran['reference_id']		=	$delivery_id;
								$insertTran['company_id']	=	$po['company_id'];
								$insertTran['created_on']	=	date('Y-m-d H:m:s');
								$insertTran['created_by']	=	$_SESSION['abas_login']['userid'];
								$trans						=	$this->Mmm->dbInsert("ac_transactions", $insertTran, "New Accounting transaction has been added");
								$transaction_id				=	$this->Abas->getLastIDByTable('ac_transactions');
								$computed_amount			=	$this->Abas->computePurchaseTaxes($grand_total,$supplier_id,0,$company_id);
								if($computed_amount['vatable_purchases']>0) {
									$debit						=	array();
									$debit['account']			=	MATERIALS_AND_SUPPLIES; //AP-Clearing after vat
									$debit['debit_amount']		=	$computed_amount['vatable_purchases'];//round($computed_amount['vatable_purchases'],2);
									$debit['credit_amount']		=	0;
									$debit['company']			=	$company_id;
									$debit['transaction_id']	=	$transaction_id;
									$debit['reference_table']	=	'inventory_deliveries';
									$debit['reference_id']		=	$delivery_id;
									$debit['remark']			=	'For '.$po['vessel_name'];

									$requisition = $this->Purchasing_model->getRequest($po['request_id']);

									$debit['department']		=	$requisition['department_id'];
									$debit['vessel']			=	$requisition['vessel_id'];
									$debit['contract']			=	0;
									$debit['posted_on']			=	$delivery_date;
									$credit						=	array();
									$credit['account']			=	AP_CLEARING; //AP-Clearing after vat
									$credit['debit_amount']		=	0;
									$credit['credit_amount']	=	$computed_amount['vatable_purchases'];//round($computed_amount['vatable_purchases'],2);
									$credit['company']			=	$company_id;
									$credit['transaction_id']	=	$transaction_id;
									$credit['reference_table']	=	'inventory_deliveries';
									$credit['reference_id']		=	$delivery_id;
									$credit['remark']			=	'For '.$po['vessel_name'];
									$credit['department']		=	$requisition['department_id'];
									$credit['vessel']			=	$requisition['vessel_id'];
									$credit['contract']			=	0;
									$credit['posted_on']		=	$delivery_date;
									$debit_entry				=	$this->Accounting_model->newJournalEntry($debit);
									if(!$debit_entry){
										$this->Abas->sysMsg("errmsg", "Problem occured in debit entry, please contact your administrator!");
										$this->Abas->redirect(HTTP_PATH."inventory/receiving/listview");
									}
									$credit_entry	=	$this->Accounting_model->newJournalEntry($credit);
									if(!$credit_entry){
										$this->Abas->sysMsg("errmsg", "Problem occured in credit entry, please contact your administrator!");
										$this->Abas->redirect(HTTP_PATH."inventory/receiving/listview");
									}

									if(!$debit_entry && !$credit_entry){
										$entries_notification = TRUE;
									}

								}

								//execute MSIS if direct delivery
								if($_POST['direct_del']==1){
									$insert_issuance = array();
									$issuance_control_no	=	$this->Abas->getNextSerialNumber('inventory_issuance', $company_id);
									$po = $this->Purchasing_model->getPurchaseOrder($po_id);

									$insert_issuance['issue_date']	= date('Y-m-d H:m:s');
									$insert_issuance['request_no']	=	$po['details'][0]['request_id'];
									$vessel_id = $this->Mmm->sanitize($_POST['issued_for']);
									$vessel = $this->Abas->getVessel($vessel_id);
									$insert_issuance['issued_to']	=	$vessel->name;
									$insert_issuance['vessel_id']	=	$vessel_id;
									$location 	=	$_SESSION['abas_login']['user_location'];
									$insert_issuance['from_location']	=	$location;
									$insert_issuance['stat']	=	1;
									$insert_issuance['delivery_id']	=	$delivery_id;
									$insert_issuance['company_id']	=	$company_id;
									$insert_issuance['control_number']	=	$issuance_control_no;
									$insert_issuance['remark']	=	'Direct delivery';
									$insert_issuance['is_cleared']	=	0;
									$insert_issuance['created_on']	=	date('Y-m-d H:m:s');
									$insert_issuance['created_by']	=	$_SESSION['abas_login']['userid'];
									
									$checkIssuance	=	$this->Mmm->dbInsert('inventory_issuance',$insert_issuance,'Added Material and Supplies Issuance Slip for '.$company_name);
									if($checkIssuance){
										$last_id_inserted = $this->Abas->getLastIDByTable('inventory_issuance');
										$multiInsertIssuance = array();
										foreach($_POST['item'] as $ctr=>$val){
											$multiInsertIssuance[$ctr]['issuance_id']	=	$last_id_inserted;
											$item_id = $this->Mmm->sanitize($_POST['item'][$ctr]);
											$multiInsertIssuance[$ctr]['item_id']	=	$item_id;
											$multiInsertIssuance[$ctr]['unit']	=	$this->Mmm->sanitize($_POST['unit'][$ctr]);
											$multiInsertIssuance[$ctr]['unit_price']	=	$this->Mmm->sanitize($_POST['price'][$ctr]);
											$qty = $this->Mmm->sanitize($_POST['quantity'][$ctr]);
											$multiInsertIssuance[$ctr]['qty']	=	$qty;
											$multiInsertIssuance[$ctr]['stat']	=	1;
											
											//no need to deduct since no quantity added during receiving since we disabled from the top codes
											/*$sql14 = "UPDATE inventory_quantity SET quantity	= (quantity - ".$qty.") WHERE item_id=".$item_id." AND company_id=".$company_id. " AND location='".$location."' AND stat=1";
											$query14 = $this->Mmm->query($sql14,"Direct issuance - Deducted quantity on Inventory per company and location.");*/

										}

										$checkMultiInsertIssuance = $this->Mmm->multiInsert('inventory_issuance_details',$multiInsertIssuance,'Added issuance items for MSIS with transaction code no.'.$last_id_inserted);

										if($checkMultiInsertIssuance){

											$sql16	=	"UPDATE inventory_deliveries SET is_issued=1, issuance_id=".$last_id_inserted." WHERE id =".$delivery_id;
											$query16	=	$this->db->query($sql16);

											$msis_notification = TRUE;
										}
										
									}	

								}

								//execute Notice of Discrepancy
								if($_POST['is_notice_of_discrepancy']==1 && $_POST['nod_id']){

									$sql17	=	"UPDATE inventory_deliveries SET notice_of_discrepancy_id=".$this->Mmm->sanitize($_POST['nod_id'])." WHERE id =".$delivery_id;
									$query17	=	$this->db->query($sql17);

									$nod_notification = TRUE;

									/*$insert_discrepancy = array();
									$discrepancy_control_no	=	$this->Abas->getNextSerialNumber('inventory_notice_of_discrepancy', $company_id);
									$po = $this->Purchasing_model->getPurchaseOrder($po_id);
									$po_details = $this->Purchasing_model->getPurchaseOrderDetails($po_id);

									$insert_discrepancy['control_number']	=	$discrepancy_control_no;
									$insert_discrepancy['company_id']	=	$company_id;
									$insert_discrepancy['supplier_id']	=	$supplier_id;
									$insert_discrepancy['purchase_order_id']	=	$po_id;
									$insert_discrepancy['date_of_delivery']	=	$delivery_date;
									$insert_discrepancy['delivery_receipt_number']	=	$delivery_no;
									$insert_discrepancy['vehicle_plate_number']	=	$this->Mmm->sanitize($_POST['plate_no']);
									$insert_discrepancy['name_of_driver']	=	$this->Mmm->sanitize($_POST['driver']);
									$insert_discrepancy['other_remarks']	=	$this->Mmm->sanitize($_POST['remarks']);
									$insert_discrepancy['created_on']	=	date('Y-m-d H:m:s');
									$insert_discrepancy['created_by']	=	$_SESSION['abas_login']['userid'];
									$insert_discrepancy['status']	=	'Approved';

									$checkDiscrepancy	=	$this->Mmm->dbInsert('inventory_notice_of_discrepancy',$insert_discrepancy,'Added Notice of Discrepancy for PO with TSCode No. '.$po_id.' under '.$company_name);
									if($checkDiscrepancy){
										$last_id_inserted = $this->Abas->getLastIDByTable('inventory_notice_of_discrepancy');
										$multiInsertDiscrepancy = array();
										foreach($_POST['item'] as $ctr=>$val){

											$item_id = $this->Mmm->sanitize($_POST['item'][$ctr]);
											$multiInsertDiscrepancy[$ctr]['notice_of_discrepancy_id']	=	$last_id_inserted;
											$multiInsertDiscrepancy[$ctr]['item_id']	=	$item_id;

											$qty_po =0;
											foreach($po_details as $row){
												if($row['item_id'] == $item_id){
													$qty_po = $row['quantity'];
													break;
												}
											}

											$multiInsertDiscrepancy[$ctr]['quantity_po']	=	$qty_po;
											$qty_received	=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
											$multiInsertDiscrepancy[$ctr]['quantity_dr']	=	$qty_received;
											$multiInsertDiscrepancy[$ctr]['quantity_received']	=	$qty_received;
											$multiInsertDiscrepancy[$ctr]['remarks']	=	$this->Mmm->sanitize($_POST['reason'][$ctr]);

										}

										$checkMultiInsertDiscrepancy= $this->Mmm->multiInsert('inventory_notice_of_discrepancy_details',$multiInsertDiscrepancy,'Added items for Notice of Discrepancy with transaction code no.'.$last_id_inserted);

										if($checkMultiInsertDiscrepancy){

											$sql17	=	"UPDATE inventory_deliveries SET notice_of_discrepancy_id=".$last_id_inserted." WHERE id =".$delivery_id;
											$query17	=	$this->db->query($sql17);

											$nod_notification = TRUE;
										}
									}*/

								}

								$this->Abas->sysNotif("Inventory Receiving", "New ". $insert['location']." delivery from supplier ".$supplier_name." was successfully received by ".$_SESSION['abas_login']['fullname']. " under ".$company_name,'Inventory',"info");

								$this->Abas->redirect(HTTP_PATH."inventory/receiving/listview");
								
								if($entries_notification == TRUE){
									$this->Abas->sysNotif("Inventory Receiving", "New Receiving Report with Accounting Entries were successfully craeted by ".$_SESSION['abas_login']['fullname']. " under ".$company_nam,'Accounting',"info");
								}

								if($msis_notification == TRUE){
									$this->Abas->sysNotif("Inventory Issuance", "New Material and Services Issuance Slip for ".$vessel->name." (Direct Delivery) was successfully created by ".$_SESSION['abas_login']['fullname']. " under ".$company_name,'Inventory',"info");
								}

								if($nod_notification == TRUE){
									$this->Abas->sysNotif("Notice of Discrepancy", "New Notice of Discrepancy for Purchase Order No.".$po['control_number']." was successfully created by ".$_SESSION['abas_login']['fullname']. " under ".$company_name,'Inventory',"info");
								}

								$this->Abas->sysMsg("sucmsg", "Successfully created Receiving Report under ".$company_name);

							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Receiving Report! Please try again.");
								$this->Abas->redirect(HTTP_PATH."inventory/receiving/listview");
								die();
							}
						}
					}
				break;

				case 'view':
					$data['RR']	=	$this->Inventory_model->getDelivery($id);
					$data['RR_details']	=	$this->Inventory_model->getDeliveryDetails($id);
					$data['company']		=	$this->Abas->getCompany($data['RR'][0]['company_id']);
					$data['received_by']	=	$this->Abas->getUser($data['RR'][0]['created_by']);
					$data['supplier']	=	$this->Abas->getSupplier($data['RR'][0]['supplier_id']);
					$data['viewfile']	=	"inventory/receiving/view.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'print_rr':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					if($id!='') {
						$data['summary']	=	$this->Inventory_model->getDelivery($id);
						$data['po_info']	=	$this->Purchasing_model->getPurchaseOrder($data['summary'][0]['po_no']);
						$data['company']	=	$this->Abas->getCompany($data['summary'][0]['company_id']);
						$data['supplier']	=	$this->Abas->getSupplier($data['summary'][0]['supplier_id']);
						$data['request']	=	$this->Purchasing_model->getRequest($data['po_info']['request_id']);
						$data['details']	=	$this->Inventory_model->getDeliveryDetails($id);
						$ref_table			=	'inventory_deliveries';
						$data['entry']		=	$this->Inventory_model->getAccountingEntry($id,$ref_table);

						$issuance = $this->Inventory_model->getIssuanceByDeliveryID($id);
						if($issuance){
						    $sql				=	"SELECT * FROM inventory_issuance WHERE id=".$issuance[0]['id'];
							$db					=	$this->db->query($sql);
							$data['summary_issuance']	=	$db->result_array();
							$data['vessel_issuance']		=	$this->Abas->getVessel($data['summary_issuance'][0]['vessel_id']);
							$data['company_issuance']	=	$this->Abas->getCompany($data['vessel_issuance']->company);
							$sql2				=	"SELECT * FROM inventory_issuance_details WHERE issuance_id	=	".$issuance[0]['id'];
							$db2				=	$this->db->query($sql2);
							$data['details_issuance']	=	$db2->result_array();
						}
						$this->load->view('inventory/receiving/print_receiving_report',$data);
					}
					else{
						$this->Abas->sysMSg("msg", "There was an error printing Receiving Report.");
					}
				break;

				case 'print_msis':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					if($id!='') {
						$data['summary']	=	$this->Inventory_model->getDelivery($id);
						$data['po_info']	=	$this->Purchasing_model->getPurchaseOrder($data['summary'][0]['po_no']);
						$data['request']	=	$this->Purchasing_model->getRequest($data['po_info']['request_id']);
						
						$issuance = $this->Inventory_model->getIssuanceByDeliveryID($id);
						if($issuance){
							$data['summary_issuance']	=	$this->Inventory_model->getIssuances($issuance[0]['id']);
							$data['vessel_issuance']	=	$this->Abas->getVessel($data['summary_issuance'][0]['vessel_id']);
							$data['company_issuance']	=	$this->Abas->getCompany($data['vessel_issuance']->company);
							$data['details_issuance']	=	$this->Inventory_model->getIssuanceDetails($issuance[0]['id']);
						}
						$this->load->view('inventory/receiving/print_material_supplies_issuance_slip',$data);
					}
					else{
						$this->Abas->sysMSg("msg", "There was an error printing Materials and Supplies Issuance Slip.");
					}
				break;

				case 'print_qr_code':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data = array();
					$data['delivery'] = $this->Inventory_model->getDelivery($id);
					$data['company'] = $this->Abas->getCompany($data['delivery'][0]['company_id']);
					//$data['deliveries'] = $this->Inventory_model->getInventoryQuantityByDeliveryID($id);
					//$data['viewfile'] = "inventory/receiving/print_qr_code.php";
					//$this->load->view("gentlella_container.php",$data);
					$delivery_item = $this->Inventory_model->getInventoryQuantityByDeliveryIDandItemID($id,$idx);
					$data['qr_data'] = $delivery_item;
					$data['item'] = $this->Inventory_model->getItem($delivery_item[0]->item_id);
					$this->load->view('inventory/receiving/print_qr_code.php',$data);
				break;
			}

		}
		public function issuance($action,$id=''){
			switch ($action) {
				case 'load':
					$table = "inventory_issuance";
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						if($id!=''){
							$where = "company_id=".$id;
						}else{
							$where = "";
						}
						$data = $this->Abas->getDataForBSTable($table,$search,$limit,$offset,$order,$sort,$where);
						foreach($data['rows'] as $ctr=>$issuance) {
							
							if($issuance['created_by']) {
								$created_by							=	$this->Abas->getUser($issuance['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if($issuance['created_on']) {
								$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($issuance['created_on']));
							}

							if($issuance['issue_date']) {
								$data['rows'][$ctr]['issue_date']	=	date("j F Y", strtotime($issuance['issue_date']));
							}

							if($issuance['vessel_id']) {
								$vessel 							= $this->Abas->getVessel($issuance['vessel_id']);
								$data['rows'][$ctr]['issued_for']	=	$vessel->name;

								if($issuance['company_id']) {
									$company							=	$this->Abas->getCompany($issuance['company_id']);
									$data['rows'][$ctr]['company_name']	=	$company->name;
								}else{
									$company							=	$this->Abas->getCompany($vessel->company);
									$data['rows'][$ctr]['company_name']	=	$company->name;
								}
							}

							if($issuance['is_cleared']==1) {
								$data['rows'][$ctr]['is_cleared']	=	"Yes";
							}else{
								$data['rows'][$ctr]['is_cleared']	=	"No";
							}

							if($issuance['id']) {
								$x = $this->Inventory_model->getIssuanceAmount($issuance['id']);
								$data['rows'][$ctr]['total_amount']	=	number_format($x->issuance_amount,2,'.',',');

								$y = $this->Inventory_model->getGatePass($issuance['id']);
								if($y){
									$data['rows'][$ctr]['gatepass']	= "Yes";
								}else{
									$data['rows'][$ctr]['gatepass']	= "No";
								}
								
							}
							
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				break;
				
				case 'listview':
					$data['viewfile']	=	"inventory/issuance/listview.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'view':
					$data['MSIS'] =	$this->Inventory_model->getIssuances($id);
					$data['MSIS_details'] =	$this->Inventory_model->getIssuanceDetails($id);
					$data['gatepass'] = $this->Inventory_model->getGatePass($id);
					$data['created_by']		=	$this->Abas->getUser($data['MSIS'][0]['created_by']);
					$data['vessel']	=	$this->Abas->getVessel($data['MSIS'][0]['vessel_id']);
					$data['company']		=	$this->Abas->getCompany($data['vessel']->company);
					$data['viewfile']	=	"inventory/issuance/view.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'add':
					$data['vessels']		=	$this->Abas->getVessels();
					$data['items']			=	$this->Inventory_model->getItems();
					$data['units']			=	$this->Inventory_model->getUnits();
					$data['location']		= 	$_SESSION['abas_login']['user_location'];
					$this->load->view('inventory/issuance/form.php',$data);
				break;

				case 'insert':
					$insert = array();
					if(isset($_POST)){
						$insert['issue_date']		= date('Y-m-d h:m:s',strtotime($_POST['issue_date']));
						$insert['issued_to']		= $this->Mmm->sanitize($_POST['issued_to']);
						$insert['vessel_id']		= $this->Mmm->sanitize($_POST['issued_for']);
						$location 					= $_SESSION['abas_login']['user_location'];
						$insert['from_location']	= $location;
						$insert['stat']				=	1;
						$insert['remark']			=	$this->Mmm->sanitize($_POST['remark']);
						$company_id					= $this->Mmm->sanitize($_POST['company_id']);
						$company                    = $this->Abas->getCompany($company_id);
						$control_number	=	$this->Abas->getNextSerialNumber('inventory_issuance',$company_id);
						$insert['control_number']  = $control_number;
						$insert['reference_number'] = $this->Mmm->sanitize($_POST['reference_no']);
						$insert['company_id']		= $company_id;
						$insert['created_on']	=	date('Y-m-d h:m:s');
						$insert['created_by']	=	$_SESSION['abas_login']['userid'];
						$insert['is_cleared']	= 	0;
						$checkIssuance	=	$this->Mmm->dbInsert('inventory_issuance',$insert,'Added Material and Supplies Issuance Slip for '.$company->name);
						if($checkIssuance){
							$last_id_inserted = $this->Abas->getLastIDByTable('inventory_issuance');
							$multiInsertIssuance = array();
							foreach($_POST['item_qty_id'] as $ctr=>$val){
								$multiInsertIssuance[$ctr]['issuance_id']	=	$last_id_inserted;
								$item_quantity_id = $this->Mmm->sanitize($_POST['item_qty_id'][$ctr]);
								$item_id = $this->Mmm->sanitize($_POST['item'][$ctr]);
								$multiInsertIssuance[$ctr]['item_id']	=	$item_id;
								$multiInsertIssuance[$ctr]['unit']	=	$this->Mmm->sanitize($_POST['unit'][$ctr]);
								$multiInsertIssuance[$ctr]['unit_price']	=	$this->Mmm->sanitize($_POST['price'][$ctr]);
								$qty = $this->Mmm->sanitize($_POST['quantity'][$ctr]);
								$multiInsertIssuance[$ctr]['qty']	=	$qty;
								$multiInsertIssuance[$ctr]['stat']	=	1;

								$price_history_id = $this->Mmm->sanitize($_POST['price_history_id'][$ctr]);

								//converts the packaging to smallest unit that was set on the item's conversion table
								$packagings = $this->Inventory_model->getPackagingByItem($item_id);
								if(count($packagings)>0){
									foreach($packagings as $pckg){
										if($multiInsertIssuance[$ctr]['unit']==$pckg->packaging){
											$qty	=	($pckg->conversion*$qty);
										}
									}

								}

								//deduct to inventory_quantity
								$sql14 = "UPDATE inventory_quantity SET quantity_issued	= ( quantity_issued + ".$qty.") WHERE id=".$item_quantity_id." AND stat=1";
								$query14 = $this->Mmm->query($sql14,"Deducted quantity on Inventory per company.");

								//$sql15 = "UPDATE inventory_price_history SET stat=0 WHERE id=".$price_history_id;
								//$query15 = $this->Mmm->query($sql15,"Used and locked the price history of item with ID no.".$item_id);

							}

							$checkMultiInsertIssuance = $this->Mmm->multiInsert('inventory_issuance_details',$multiInsertIssuance,'Added issuance items for MSIS with transaction code no.'.$last_id_inserted);

							if($checkMultiInsertIssuance){
								$insert_gatepass = array();
								$gatepass	=	$_POST['include_gatepass'];
								if($gatepass==1){
									$gatepass_control_no	=	$this->Abas->getNextSerialNumber('inventory_gatepass', $company_id);

									$insert_gatepass['vessel_id'] = $insert['vessel_id'];
									$insert_gatepass['company_id'] = $company_id;
									$insert_gatepass['control_number'] = $gatepass_control_no;
									$insert_gatepass['issuance_id'] = $last_id_inserted;

									$checkGatePass	=	$this->Mmm->dbInsert('inventory_gatepass',$insert_gatepass,'Added Gate-pass for MSIS No. '.$control_number.' under '.$company->name);
									if(!$checkGatePass){
										$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Gate-pass! Please try again.");
										$this->Abas->redirect(HTTP_PATH."inventory/issuance/listview");
										die();
									}
								}
								$this->Abas->redirect(HTTP_PATH.'inventory/issuance/view/'.$last_id_inserted);

								$this->Abas->sysNotif("Inventory Issuance", "New Material & Supplies Issuance Slip were successfully created by ".$_SESSION['abas_login']['fullname']. " under ".$company->name,'Inventory',"info");
								$this->Abas->sysMsg("sucmsg", "Successfully created Material & Supplies Issuance Slip under ".$company->name);

							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Material and Supplies Issuance Slip! Please try again.");
								$this->Abas->redirect(HTTP_PATH."inventory/issuance/listview");
								die();
							}
							
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Material and Supplies Issuance Slip! Please try again.");
								$this->Abas->redirect(HTTP_PATH."inventory/issuance/listview");
								die();
						}		
							
					}
					
				break;

				case 'print_gatepass':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['MSIS'] =	$this->Inventory_model->getIssuances($id);
					$data['MSIS_details'] =	$this->Inventory_model->getIssuanceDetails($id);
					$data['gatepass'] = $this->Inventory_model->getGatePass($id);
					$data['company']		=	$this->Abas->getCompany($data['MSIS'][0]['company_id']);
					$data['created_by']		=	$this->Abas->getUser($data['MSIS'][0]['created_by']);
					$data['vessel']	=	$this->Abas->getVessel($data['MSIS'][0]['vessel_id']);
					$this->load->view('inventory/issuance/print_gatepass',$data);
				break;

				case 'print_msis':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					if($id!='') {
						$data['summary_issuance']	=	$this->Inventory_model->getIssuances($id);
						$data['vessel_issuance']	=	$this->Abas->getVessel($data['summary_issuance'][0]['vessel_id']);
						$data['company_issuance']	=	$this->Abas->getCompany($data['vessel_issuance']->company);
						$data['details_issuance']	=	$this->Inventory_model->getIssuanceDetails($id);
						$data['summary_receiving']	=	$this->Inventory_model->getDelivery($data['summary_issuance'][0]['delivery_id']);

						$this->load->view('inventory/issuance/print_material_supplies_issuance_slip',$data);
					}
					else{
						$this->Abas->sysMSg("msg", "There was an error printing Materials and Supplies Issuance Slip.");
					}
				break;
				
			}
		}
		public function transfer($action,$id='',$idx=''){
			$data = array();
			switch ($action) {
				case 'load':
					$table = "inventory_transfer";
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						if($id!=''){
							$where = "company_id=".$id;
						}else{
							$where = "";
						}
						$data = $this->Abas->getDataForBSTable($table,$search,$limit,$offset,$order,$sort,$where);
						foreach($data['rows'] as $ctr=>$transfer) {

							if($transfer['company_id']) {
								$company						=	$this->Abas->getCompany($transfer['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							
							if($transfer['transfer_date']) {
								$data['rows'][$ctr]['transfer_date']	=	date("j F Y", strtotime($transfer['transfer_date']));
							}

							if($transfer['created_by']) {
								$created_by							=	$this->Abas->getUser($transfer['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if($transfer['created_on']) {
								$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($transfer['created_on']));
							}
							
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				break;

				case 'listview':
					$data['viewfile']	=	"inventory/transfer/listview.php";
					$this->load->view('gentlella_container.php',$data);
				break;	

				case 'add':
					$data['vessels']		=	$this->Abas->getVessels();
					$data['locations']	= $this->Abas->getUserLocations();
					$data['companies']	=	$this->Abas->getCompanies();
					$this->load->view('inventory/transfer/form_material_transfer_request.php',$data);
				break;

				case 'insert':
					$insert = array();
					if(isset($_POST)){
						$insert['transfer_date']		= date('Y-m-d h:m:s',strtotime($_POST['transfer_date']));
						$insert['from_location']		= $this->Mmm->sanitize($_POST['from_location']);
						$insert['to_location']		= $this->Mmm->sanitize($_POST['to_location']);
						$insert['remark']			=	$this->Mmm->sanitize($_POST['remark']);
						$insert['stat']				=	1;
						$insert['requested_for'] 		= $this->Mmm->sanitize($_POST['requested_for']);
						$company_id					= $this->Mmm->sanitize($_POST['company_id']);
						$company                    = $this->Abas->getCompany($company_id);
						$control_number	=	$this->Abas->getNextSerialNumber('inventory_transfer',$company_id);
						$insert['control_number']  = $control_number;
						$insert['company_id']		= $company_id;
						$insert['created_by']	=	$_SESSION['abas_login']['userid'];
						$insert['created_on']	=	date('Y-m-d h:m:s');
						$insert['status']	=	'For Transfer';

						$checkTransfer	=	$this->Mmm->dbInsert('inventory_transfer',$insert,'Added Material Transfer Request for '.$company->name);
						if($checkTransfer){
							$last_id_inserted = $this->Abas->getLastIDByTable('inventory_transfer');
							$multiInsertTransfer= array();
							foreach($_POST['item_qty_id'] as $ctr=>$val){
								$multiInsertTransfer[$ctr]['transfer_id']	=	$last_id_inserted;
								$item_id = $this->Mmm->sanitize($_POST['item'][$ctr]);
								$multiInsertTransfer[$ctr]['item_id']	=	$item_id;
								$multiInsertTransfer[$ctr]['unit']	=	$this->Mmm->sanitize($_POST['unit'][$ctr]);
								$multiInsertTransfer[$ctr]['unit_price']	=	$this->Mmm->sanitize($_POST['price'][$ctr]);
								$multiInsertTransfer[$ctr]['qty']	=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
								$multiInsertTransfer[$ctr]['stat']	=	1;
							}
							$checkMultiInsertTransfer = $this->Mmm->multiInsert('inventory_transfer_details',$multiInsertTransfer,'Added transfer items for MTR with transaction code no.'.$last_id_inserted);

							if($checkMultiInsertTransfer){
						
								$gatepass_control_no	=	$this->Abas->getNextSerialNumber('inventory_gatepass', $company_id);

								$insert_gatepass['vessel_id'] = $insert['requested_for'];
								$insert_gatepass['company_id'] = $company_id;
								$insert_gatepass['control_number'] = $gatepass_control_no;
								$insert_gatepass['stock_transfer_receipt_id'] = $last_id_inserted;

								$checkGatePass	=	$this->Mmm->dbInsert('inventory_gatepass',$insert_gatepass,'Added Gate-pass for STR No. '.$control_number.' under '.$company->name);
								if(!$checkGatePass){
									$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Gate-pass! Please try again.");
									$this->Abas->redirect(HTTP_PATH."inventory/issuance/listview");
									die();
								}
								
								$this->Abas->redirect(HTTP_PATH.'inventory/transfer/view/'.$last_id_inserted);

								$this->Abas->sysNotif("Inventory Transfer", "New Material Transfer Request were successfully created by ".$_SESSION['abas_login']['fullname']. " under ".$company->name,'Inventory',"info");
								$this->Abas->sysMsg("sucmsg", "Successfully created Material Transfer Request under ".$company->name);

							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Material Transfer Request! Please try again.");
								$this->Abas->redirect(HTTP_PATH."inventory/transfer/listview");
								die();
							}
							
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Material Transfer Request! Please try again.");
								$this->Abas->redirect(HTTP_PATH."inventory/transfer/listview");
								die();
						}		
							
					}
				break;

				case 'view':
					$data['MTR'] =	$this->Inventory_model->getTransfer($id);
					$data['MTR_details'] =	$this->Inventory_model->getTransferDetails($id);
					$data['STR_details'] =	$this->Inventory_model->getTransferReceiptDetails($id);
					//$data['gatepass'] = $this->Inventory_model->getGatePass($id);
					$data['created_by']		=	$this->Abas->getUser($data['MTR'][0]['created_by']);
					$data['company']		=	$this->Abas->getCompany($data['MTR'][0]['company_id']);
					$data['viewfile']	=	"inventory/transfer/view.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'print_mtr':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['MTR'] =	$this->Inventory_model->getTransfer($id);
					$data['MTR_details'] =	$this->Inventory_model->getTransferDetails($id);
					$data['company']		=	$this->Abas->getCompany($data['MTR'][0]['company_id']);
					$this->load->view('inventory/transfer/print_material_transfer_request',$data);
				break;

				case 'add_str':
					$data['MTR'] =	$this->Inventory_model->getTransfer($id);
					$data['MTR_details'] =	$this->Inventory_model->getTransferDetails($id);
					$data['company']		=	$this->Abas->getCompany($data['MTR'][0]['company_id']);
					$data['requested_for'] = $this->Abas->getVessel($data['MTR'][0]['requested_for']);
					$this->load->view('inventory/transfer/form_stock_transfer_receipt',$data);
				break;

				case 'insert_str':
					if(isset($_POST)){
						$multiInsertTransfer= array();
						$company = $this->Abas->getCompany($_POST['company_id']);
						$transfer_id = $this->Mmm->sanitize($_POST['transfer_id']);
						foreach($_POST['item_qty_id'] as $ctr=>$val){
							$item_quantity_id = $this->Mmm->sanitize($_POST['item_qty_id'][$ctr]);
							$multiInsertTransfer[$ctr]['transfer_id']	= $transfer_id;
							$item_id = $this->Mmm->sanitize($_POST['item'][$ctr]);
							$multiInsertTransfer[$ctr]['item_id']	=	$item_id;
							$multiInsertTransfer[$ctr]['unit']	=	$this->Mmm->sanitize($_POST['unit'][$ctr]);
							$multiInsertTransfer[$ctr]['unit_price']	=	$this->Mmm->sanitize($_POST['price'][$ctr]);
							$qty = $this->Mmm->sanitize($_POST['quantity'][$ctr]);
							$multiInsertTransfer[$ctr]['qty']	= $qty;	
							$multiInsertTransfer[$ctr]['stat']	=	1;

							//converts the packaging to smallest unit that was set on the item's conversion table
							$packagings = $this->Inventory_model->getPackagingByItem($item_id);
							if(count($packagings)>0){
								foreach($packagings as $pckg){
									if($multiInsertTransfer[$ctr]['unit']==$pckg->packaging){
										$qty	=	($pckg->conversion*$qty);
									}
								}

							}

							//deduct to inventory_quantity
							$sql14 = "UPDATE inventory_quantity SET quantity_issued	= ( quantity_issued + ".$qty.") WHERE id=".$item_quantity_id." AND stat=1";
							$query14 = $this->Mmm->query($sql14,"Deducted quantity on Inventory per company.");

						}
						$checkMultiInsertTransfer = $this->Mmm->multiInsert('inventory_transfer_receipt_details',$multiInsertTransfer,'Added actual transfer items for STR with MTR transaction code no.'.$transfer_id);

						if($checkMultiInsertTransfer){

							$sql15 = "UPDATE inventory_transfer SET status='For Receiving' WHERE id=".$transfer_id;
							$query15 = $this->Mmm->query($sql15,"Update status of Material Transfer Request from 'For Transfer' to 'For Receiving'");

							if($query15){
								
								$this->Abas->sysNotif("Inventory Transfer", "New Stock Transfer Receipt were successfully created by ".$_SESSION['abas_login']['fullname']. " under ".$company->name,'Inventory',"info");
								$this->Abas->sysMsg("sucmsg", "Successfully created Stock Transfer Receipt under ".$company->name);

								$this->Abas->redirect(HTTP_PATH.'inventory/transfer/view/'.$transfer_id);
							}

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Stock Transfer Receipt! Please try again.");
							$this->Abas->redirect(HTTP_PATH."inventory/transfer/listview");
							die();
						}
							
					}
				break;

				case 'print_str':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['MTR'] =	$this->Inventory_model->getTransfer($id);
					$data['STR_details'] =	$this->Inventory_model->getTransferReceiptDetails($id);
					$data['company']		=	$this->Abas->getCompany($data['MTR'][0]['company_id']);
					$this->load->view('inventory/transfer/print_stock_transfer_receipt',$data);
				break;

				case 'print_gatepass':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['MTR'] =	$this->Inventory_model->getTransfer($id);
					$data['STR_details'] =	$this->Inventory_model->getTransferReceiptDetails($id);
					$data['gatepass'] = $this->Inventory_model->getGatePassByTransferID($id);
					$data['company']		=	$this->Abas->getCompany($data['MTR'][0]['company_id']);
					$data['created_by']		=	$this->Abas->getUser($data['MTR'][0]['created_by']);
					$data['vessel']	=	$this->Abas->getVessel($data['MTR'][0]['requested_for']);
					$this->load->view('inventory/transfer/print_gatepass',$data);
				break;

				case 'receive_str':
					$receive = array();
					$receive['remarks'] =  $_POST['comment'];
					$receive['received_on'] =  date('Y-m-d');
					$receive['received_by'] =  $_SESSION['abas_login']['userid'];
					$received = $this->Mmm->dbUpdate('inventory_transfer_receipt_details',$receive,$idx,"Received item from Stock Transfer Receipt with TSCode No. ".$id);
					if($received){

						$transfer =	$this->Inventory_model->getTransfer($id);
						$detail =	$this->Inventory_model->getTransferReceiptByID($idx);
						$company_id = $transfer[0]['company_id'];
						$location = $transfer[0]['to_location'];
						$unit = $detail[0]['unit'];
						$unit_price = $detail[0]['unit_price'];
						$qty = $detail[0]['qty'];

						//converts the packaging to smallest unit that was set on the item's conversion table
						$packagings = $this->Inventory_model->getPackagingByItem($detail[0]['item_id']);
						if(count($packagings)>0){
							foreach($packagings as $packaging){
								if($detail[0]['unit']==$packaging->packaging){
									$qty	=	($qty*$packaging->conversion);
									$unit   =    $packaging->unit;
									$unit_price = ($detail[0]['unit_price']/$packaging->conversion);
								}
							}
						}

						//insert to inventory_quantity every transferred
						$sql_transfer = "INSERT INTO inventory_quantity (item_id,delivery_id,unit,unit_price,company_id,location,quantity,quantity_issued,stat) VALUES(".$detail[0]['item_id'].",0,'".$unit."',".$unit_price.",".$company_id.",'".$location."',".$qty.",0,1)";
						$transferred = $this->Mmm->query($sql_transfer,"Transferred quantity on Inventory per company and location.");

						if($transferred){

							$receipts =	$this->Inventory_model->checkTransferReceiptUnreceived($id);
							if(count($receipts)==0){
								$sql = "UPDATE inventory_transfer SET status='Received' WHERE id=".$id;
								$this->Mmm->query($sql,'Update status of STR with TSCode No.'.$id.'to "Received"');
							}

							$this->Abas->sysNotif("Inventory Transfer", "Transferred item was successfully received by ".$_SESSION['abas_login']['fullname']." from Stock Transfer Receipt No.".$id,'Inventory',"info");
					    	$this->Abas->sysMsg("sucmsg", "Successfully received transferred item");

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while transferring the item quantity during transfer receipt! Please contact your administrator.");
						}
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while transferring the item quantity during transfer receipt! Please contact your administrator.");
					}
				break;

				case 'print_qr_code':
					$data['deliveries'] = $this->Inventory_model->getInventoryQuantityDetail($id);
					$data['viewfile'] = "inventory/transfer/print_qr_code.php";
					$this->load->view("gentlella_container.php",$data);
				break;

			}
		}
		public function return($action,$id=''){
			switch ($action) {
				case 'load':
					$table = "inventory_return";
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						if($id!=''){
							$where = "company_id=".$id;
						}else{
							$where = "";
						}
						$data = $this->Abas->getDataForBSTable($table,$search,$limit,$offset,$order,$sort,$where);
						foreach($data['rows'] as $ctr=>$return) {

							if($return['company_id']) {
								$company						=	$this->Abas->getCompany($return['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							
							if($return['return_date']) {
								$data['rows'][$ctr]['return_date']	=	date("j F Y", strtotime($return['return_date']));
							}

							if($return['return_from']) {
								$vessel = $this->Abas->getVessel($return['return_from']);
								$data['rows'][$ctr]['return_from']	=	$vessel->name;
							}

							if($return['created_by']) {
								$created_by							=	$this->Abas->getUser($return['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if($return['created_on']) {
								$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($return['created_on']));
							}
							
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				break;

				case 'listview':
					$data['viewfile']	=	"inventory/return/listview.php";
					$this->load->view('gentlella_container.php',$data);
				break;	

				case 'add':
					$data['vessels']		=	$this->Abas->getVessels();
					$data['locations']	= $this->Abas->getUserLocations();
					$data['companies']	=	$this->Abas->getCompanies();
					$this->load->view('inventory/return/form.php',$data);
				break;

				case 'insert':
					$insert = array();
					if(isset($_POST)){
						$insert['return_date']		= date('Y-m-d h:m:s',strtotime($_POST['return_date']));
						$insert['return_from']		= $this->Mmm->sanitize($_POST['return_from']);
						$location = $this->Mmm->sanitize($_POST['return_to']);
						$insert['return_to']		= $location;
						$insert['remark']			=	$this->Mmm->sanitize($_POST['remark']);
						$insert['stat']				=	1;
						$insert['return_by'] 		= $this->Mmm->sanitize($_POST['return_by']);
						$company_id					= $this->Mmm->sanitize($_POST['company_id']);
						$company                    = $this->Abas->getCompany($company_id);
						$control_number	=	$this->Abas->getNextSerialNumber('inventory_return',$company_id);
						$insert['control_number']  = $control_number;
						$insert['company_id']		= $company_id;
						$insert['created_by']	=	$_SESSION['abas_login']['userid'];
						$insert['created_on']	=	date('Y-m-d h:m:s');
						$insert['is_cleared']	=	0;
						$insert['stat']	=	'1';
						$insert['status']	=	'Returned';

						$checkReturn	=	$this->Mmm->dbInsert('inventory_return',$insert,'Added Material and Supplies Return Slip for company '.$company->name);
						if($checkReturn){
							$last_id_inserted = $this->Abas->getLastIDByTable('inventory_return');
							$multiInsertReturn= array();
							foreach($_POST['item'] as $ctr=>$val){
								$multiInsertReturn[$ctr]['return_id']	=	$last_id_inserted;
								$item_id = $this->Mmm->sanitize($_POST['item'][$ctr]);
								$multiInsertReturn[$ctr]['item_id']	=	$item_id;
								$unit	=	$this->Mmm->sanitize($_POST['unit'][$ctr]);
								$multiInsertReturn[$ctr]['unit'] = $unit;
								$unit_price = $this->Mmm->sanitize($_POST['price'][$ctr]);
								$multiInsertReturn[$ctr]['unit_price']	=	$unit_price;
								$qty = abs($this->Mmm->sanitize($_POST['quantity'][$ctr]));
								$multiInsertReturn[$ctr]['qty'] = $qty;
								$multiInsertReturn[$ctr]['stat']	=	1;

								//convert to default unit
								$packagings = $this->Inventory_model->getPackagingByItem($item_id);
								if(count($packagings)>0){
									foreach($packagings as $packaging){
										if($unit==$packaging->packaging){
											$qty	=	($qty*$packaging->conversion);
											$unit   =    $packaging->unit;
											$unit_price = ($unit_price/$packaging->conversion);
										}
									}
								}

								//insert to inventory_quantity every returned items
								$sqlx = "INSERT INTO inventory_quantity (item_id,delivery_id,unit,unit_price,company_id,location,quantity,quantity_issued,stat) VALUES(".$item_id.",0,'".$unit."',".$unit_price.",".$company_id.",'".$location."',".$qty.",0,1)";
								$queryx = $this->Mmm->query($sqlx,"Added quantity on Inventory per company and location.");

							}
							$checkMultiInsertReturn = $this->Mmm->multiInsert('inventory_return_details',$multiInsertReturn,'Added return items for MSRS with transaction code no.'.$last_id_inserted);

							if($checkMultiInsertReturn){
								
								$this->Abas->redirect(HTTP_PATH.'inventory/return/view/'.$last_id_inserted);
								$this->Abas->sysNotif("Inventory Return", "New Material and Supplies Return Slip were successfully created by ".$_SESSION['abas_login']['fullname']. " under ".$company->name,'Inventory',"info");
								$this->Abas->sysMsg("sucmsg", "Successfully created Material and Supplies Return Slip under ".$company->name);

							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Material and Supplies Return Slip! Please try again.");
								$this->Abas->redirect(HTTP_PATH."inventory/return/listview");
								die();
							}
							
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Material and Supplies Return Slip! Please try again.");
								$this->Abas->redirect(HTTP_PATH."inventory/return/listview");
							die();
						}
					}	
				break;

				case 'view':
					$data['MSRS'] =	$this->Inventory_model->getReturns($id);
					$data['MSRS_details'] =	$this->Inventory_model->getReturnDetails($id);
					$data['vessel']		=	$this->Abas->getVessel($data['MSRS'][0]['return_from']);
					$data['created_by']		=	$this->Abas->getUser($data['MSRS'][0]['created_by']);
					$data['company']		=	$this->Abas->getCompany($data['MSRS'][0]['company_id']);
					$data['viewfile']	=	"inventory/return/view.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'print_msrs':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['MSRS'] =	$this->Inventory_model->getReturns($id);
					$data['MSRS_details'] =	$this->Inventory_model->getReturnDetails($id);
					$data['vessel']		=	$this->Abas->getVessel($data['MSRS'][0]['return_from']);
					$data['created_by']		=	$this->Abas->getUser($data['MSRS'][0]['created_by']);
					$data['company']		=	$this->Abas->getCompany($data['MSRS'][0]['company_id']);
					$this->load->view('inventory/return/print',$data);
				break;

			}
		}
		public function item_conversion_data(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$company_id = $this->Mmm->sanitize($_GET['company_id']);
			$location = $this->Mmm->sanitize($_GET['location']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM inventory_items_per_company WHERE description LIKE '%".$search."%' AND stat=1 AND company_id=".$company_id." AND location='".$location."' ORDER BY description";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						// $ret['id']	=	$i['id'];
						$ret[$ctr]['label']	=	$i['description'].", ".$i['particular']." (".$i['unit'].")";
						$ret[$ctr]['value']	=	$i['id'];
						$ret[$ctr]['item_code']	=	$i['item_code'];
						$ret[$ctr]['description']	=	$i['description'];
						$ret[$ctr]['particular']	=	$i['particular'];
						$ret[$ctr]['qty'] = $i['quantity'];
						$ret[$ctr]['unit']	=	$i['unit'];
						$ret[$ctr]['unit_price']	=	$i['unit_price'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}

		public function items($action,$id='',$idx='',$idxx=''){

			switch ($action) {
				case 'load':
					$table = "inventory_items_per_company";
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						if($id!=''){
							if($idx!=''){
								$where = "company_id=".$id. " AND location='".$idx."'";
							}else{
								$where = "company_id=".$id;
							}
						}else{
							if($idx!=''){
								$where = "location='".$idx."'";
							}else{
								$where = "";
							}
						}
						$data = $this->Abas->getDataForBSTable($table,$search,$limit,$offset,$order,$sort,$where);
						foreach($data['rows'] as $ctr=>$item) {
							
							if(isset($item['created_by'])) {
								$created_by							=	$this->Abas->getUser($item['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if(isset($item['created_on'])) {
								$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($item['created_on']));
							}
							if(isset($item['category'])) {
								$category							=	$this->Abas->getItemCategory($item['category']);
								if($category!=''){
									$data['rows'][$ctr]['category_name']	=	$category->category;
								}else{
									$data['rows'][$ctr]['category_name']	=	"Uncategorized";
								}
							}
							if(isset($item['company_id'])) {
								$company							=	$this->Abas->getCompany($item['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							if(isset($item['total_quantity_received'])) {
								$balance							=	$item['total_quantity_received'] - $item['total_quantity_issued'];
								$data['rows'][$ctr]['quantity']	=	number_format($balance,2,'.',',');
							}
							
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}	
				break;

				case 'listview':
					$data['companies']	= $this->Abas->getCompanies();
					$data['locations']	= $this->Abas->getUserLocations();
					$data['company']	= $this->Mmm->sanitize($id);
					$data['location']	= $this->Mmm->sanitize($idx);
					$data['viewfile']	=	"inventory/items/listview.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'add':
					$data['categories']		=	$this->Inventory_model->getCategories();
					$data['sub_categories']	=	$this->Inventory_model->getSubCategories();
					$data['units']			=	$this->Inventory_model->getUnits();
					$data['vessels']		=   $this->Abas->getVessels(true);
					$data['companies']		=	$this->Abas->getCompanies();
					$this->load->view('inventory/items/form.php',$data);
				break;

				case 'insert':
					if(isset($_POST)){
						$insert_item = array();
						$insert_item['item_code'] 	= ($_POST['item_code'] != '') ? $this->Mmm->sanitize($_POST['item_code']) : 0;
						$insert_item['description']		=	$this->Mmm->sanitize($_POST['description']);
						$insert_item['brand']		=	$this->Mmm->sanitize($_POST['brand']);
						$description = $insert_item['description'];
						$insert_item['particular']		=	$this->Mmm->sanitize($_POST['particular']);
						$particular = $insert_item['particular'];
						$insert_item['unit']			=	$this->Mmm->sanitize($_POST['unit']);
						$unit							=	$insert_item['unit'];
						$insert_item['unit_price']		=	($_POST['unit_price'] != '') ? $this->Mmm->sanitize($_POST['unit_price']) : 0;
						$initial_qty				=	($_POST['qty'] !='') ? $this->Mmm->sanitize($_POST['qty']) : 0;
						$insert_item['reorder_level']	=	($_POST['reorder'] !=	'') ? $this->Mmm->sanitize($_POST['reorder']) : 0;
						$insert_item['category']		=	$this->Mmm->sanitize($_POST['category']);
						$insert_item['location']		=	$_SESSION['abas_login']['user_location'];
						$insert_item['stock_location']	=	$this->Mmm->sanitize($_POST['stock_location']);
						$insert_item['type']           	=   $this->Mmm->sanitize($_POST['type']);
						$insert_item['created_on']      =  date('Y-m-d');
						$insert_item['created_by']      =  $_SESSION['abas_login']['userid'];
						$insert_item['stat']			=	1;
						$company_id 					= ($_POST['company'] != '') ? $this->Mmm->sanitize($_POST['company']) : 1;//default if not selected is ABISC

						$sqlCheckExistItem	=	"SELECT * FROM inventory_items WHERE description='".$description."' AND particular='".$particular."' AND unit='".$unit."'";
						$existItem	=	$this->db->query($sqlCheckExistItem);
						if($existItem->result_array()){
							$this->Abas->sysMsg("warnmsg", "This item already exists!");
						}else{
							$config = array();
							$config['upload_path'] = WPATH .'assets/uploads/inventory/item_images/';
							$config['allowed_types'] = 'jpg';
							$this->load->library('upload', $config);
							if (!$this->upload->do_upload('picture')) {
								$error = array('error' => $this->upload->display_errors());
								//$this->Abas->sysMsg("errmsg", "Item image was not uploaded!");
							}
							else {
								$upload_data=$this->upload->data();
								$insert_item['picture']	=	$upload_data['file_name'];
								//$this->Abas->sysMsg("sucmsg", "Item image has been Successfully uploaded!");
							}
							
							$checkInsertItem = $this->Mmm->dbInsert('inventory_items',$insert_item,"Added new item (".$description.", ".$particular.")");
							if($checkInsertItem){	

								$last_id_inserted = $this->Abas->getLastIDByTable('inventory_items');
								$insert_price  = array();
								$item_id = $last_id_inserted;
								$insert_price['item_id'] 		= $item_id;
								$insert_price['unit_price'] 	= $insert_item['unit_price'];
								$insert_price['date_recorded'] 	= date('Y-m-d');
								$insert_price['stat'] = 1;

								$checkInsertPrice = $this->Mmm->dbInsert('inventory_price_history',$insert_price,"Added new price history for item (".$description.", ".$particular.")");

								//insert to inventory_quantity
								$company = $this->Abas->getCompany($company_id);
								$insert_company = array();
								$insert_company['item_id'] 		= $item_id;
								$insert_company['unit'] 		= $insert_item['unit'];
								$insert_company['unit_price'] 	= $insert_item['unit_price'];
								$insert_company['company_id'] 	= $company->id;
								$insert_company['location'] 	= $_SESSION['abas_login']['user_location'];
								$insert_company['quantity']		= $initial_qty;
								$insert_company['quantity_issued']		= 0;
								$insert_company['stat'] 		= 1;

								$checkInsertCompany= $this->Mmm->dbInsert('inventory_quantity',$insert_company,"Added initial quantity for item (".$description.", ".$particular. ") on ".$company->name);

								if($checkInsertPrice && $checkInsertCompany){
									$multiInsertUnit = array();
									if(isset($_POST['packaging'])){
										foreach($_POST['packaging'] as $ctr=>$val){
											if($_POST['packaging'][$ctr]!='' &&  $_POST['default_unit'][$ctr]!='' && $_POST['conversion'][$ctr]!=''){
												$multiInsertUnit[$ctr]['item_id']	=	$item_id;
												$multiInsertUnit[$ctr]['unit']	=	$this->Mmm->sanitize($_POST['default_unit'][$ctr]);
												$multiInsertUnit[$ctr]['packaging']	=	$this->Mmm->sanitize($_POST['packaging'][$ctr]);
												$multiInsertUnit[$ctr]['conversion']	=	$this->Mmm->sanitize($_POST['conversion'][$ctr]);
												$multiInsertUnit[$ctr]['stat']	=	1;
											}
										}
										$checkMultiInsertUnit = $this->Mmm->multiInsert('inventory_packaging',$multiInsertUnit,'Added packaging for item '.$description.", ".$particular);
									}
									$this->Abas->sysMsg("sucmsg", "New item (".$description." - ".$particular.") has been added by ".$_SESSION['abas_login']['username'] ." on ".$company->name);
									$this->Abas->sysNotif("New Item", $_SESSION['abas_login']['fullname']." has added new item (".$description." - ".$particular.")","Inventory","info");
								}else{
									$this->Abas->sysMsg("errmsg", "There was an error while adding the details of the item. Kindly contact Administrator.");
								}

							}else{
								$this->Abas->sysMsg("errmsg", "There was an error while adding the item. Kindly contact Administrator.");
							}

						}

						$requestUrl	=	$_POST['fromModule'];
						$searchStr=	"inventory";
						if(strpos($requestUrl,$searchStr)) {
							$this->Abas->redirect(HTTP_PATH."inventory/items/listview/".$company->id);
						}
						else {
							$this->Abas->redirect(HTTP_PATH."purchasing/");
						}

					}
				break;

				case 'edit':
					$data['company_idx']	=	$idx;
					$data['locationx']		=	$idxx;
					$data['item']			=	$this->Inventory_model->getItems($id);
					$data['categories']		=	$this->Inventory_model->getCategories();
					$data['sub_categories']	=	$this->Inventory_model->getSubCategories();
					$data['units']			=	$this->Inventory_model->getUnits();
					$data['packaging']		=	$this->Inventory_model->getPackagingByItem($id);
					$data['vessels']		=   $this->Abas->getVessels(true);
					$this->load->view('inventory/items/form.php',$data);
				break;

				case 'update':
					if(isset($_POST)){
						$update_item = array();
						$update_item['item_code'] 		= ($_POST['item_code'] != '') ? $this->Mmm->sanitize($_POST['item_code']) : 0;
						$update_item['description']		=	$this->Mmm->sanitize($_POST['description']);
						$update_item['brand']		=	$this->Mmm->sanitize($_POST['brand']);
						$description 					= $update_item['description'];
						$update_item['particular']		=	$this->Mmm->sanitize($_POST['particular']);
						$particular = $update_item['particular'];
						$update_item['unit']			=	$this->Mmm->sanitize($_POST['unit']);
						$unit							=	$update_item['unit'];
						$update_item['unit_price']		=	($_POST['unit_price'] != '') ? $this->Mmm->sanitize($_POST['unit_price']) : 0;
						//$update_item['qty']				=	($_POST['qty'] !='') ? $this->Mmm->sanitize($_POST['qty']) : 0;
						//$initial_qty					=	$update_item['qty'];
						$update_item['reorder_level']	=	($_POST['reorder'] !=	'') ? $this->Mmm->sanitize($_POST['reorder']) : 0;
						$update_item['category']		=	$this->Mmm->sanitize($_POST['category']);
						$update_item['location']		=	$_SESSION['abas_login']['user_location'];
						$update_item['stock_location']	=	$this->Mmm->sanitize($_POST['stock_location']);
						$update_item['type']           	=   $this->Mmm->sanitize($_POST['type']);
						$update_item['created_on']      =  date('Y-m-d');
						$update_item['created_by']      =  $_SESSION['abas_login']['userid'];
						$update_item['stat']			=	$this->Mmm->sanitize($_POST['stat']);

						$sqlCheckExistItem	=	"SELECT * FROM inventory_items WHERE description='".$description."' AND particular='".$particular."' AND unit='".$unit."' AND id<>".$id;
						$existItem	=	$this->db->query($sqlCheckExistItem);
						if($existItem->result_array()){
							$this->Abas->sysMsg("warnmsg", "This item already exists!");
						}else{
							$config = array();
							$config['upload_path'] = WPATH .'assets/uploads/inventory/item_images/';
							$config['allowed_types'] = 'jpg';
							$this->load->library('upload', $config);
							if (!$this->upload->do_upload('picture')) {
								$error = array('error' => $this->upload->display_errors());
								//$this->Abas->sysMsg("errmsg", "Item image was not uploaded!");
							}
							else {
								$upload_data=$this->upload->data();
								$update_item['picture']	=	$upload_data['file_name'];
								//$this->Abas->sysMsg("sucmsg", "Item image has been Successfully uploaded!");
							}
							
							$checkUpdateItem = $this->Mmm->dbUpdate('inventory_items',$update_item,$id,"Edited item (".$description.", ".$particular.")");
							if($checkUpdateItem){

								$this_item = $this->Inventory_model->getItem($id);
								$item_id = $this_item[0]['id'];
								if($update_item['unit_price']<>$this_item[0]['unit_price']){
									$insert_price  = array();
									$insert_price['item_id'] 		= $item_id;
									$insert_price['unit_price'] 	= $update_item['unit_price'];
									$insert_price['date_recorded'] 	= date('Y-m-d');
									$insert_price['stat'] = 1;
									$checkInsertPrice = $this->Mmm->dbInsert('inventory_price_history',$insert_price,"Added new price history for item (".$description.", ".$particular.")");
								}

								$sqlupdateprice = "UPDATE inventory_quantity SET unit_price=".$update_item['unit_price']." WHERE item_id=".$id." AND unit_price=0 AND location='".$_SESSION['abas_login']['user_location']."' AND stat=1";
								$queryupdateprice = $this->db->query($sqlupdateprice);

								$this->db->query('DELETE FROM inventory_packaging WHERE item_id='.$id);
								$multiInsertUnit = array();
								if(isset($_POST['packaging'])){
									foreach($_POST['packaging'] as $ctr=>$val){
										if($_POST['packaging'][$ctr]!='' &&  $_POST['default_unit'][$ctr]!='' && $_POST['conversion'][$ctr]!=''){
											$multiInsertUnit[$ctr]['item_id']	=	$item_id;
											$multiInsertUnit[$ctr]['unit']	=	$this->Mmm->sanitize($_POST['default_unit'][$ctr]);
											$multiInsertUnit[$ctr]['packaging']	=	$this->Mmm->sanitize($_POST['packaging'][$ctr]);
											$multiInsertUnit[$ctr]['conversion']	=	$this->Mmm->sanitize($_POST['conversion'][$ctr]);
											$multiInsertUnit[$ctr]['stat']	=	1;
										}
									}

									$checkMultiInsertUnit = $this->Mmm->multiInsert('inventory_packaging',$multiInsertUnit,'Updated packaging for item '.$description.", ".$particular);
								}

								$this->Abas->sysMsg("sucmsg", "Item (".$description." - ".$particular.") has been edited by ".$_SESSION['abas_login']['username']);
								$this->Abas->sysNotif("Edit Item", $_SESSION['abas_login']['fullname']." has edited item (".$description." - ".$particular.")","Inventory","info");

							}else{
								$this->Abas->sysMsg("errmsg", "There was an error while editing the item. Kindly contact Administrator.");
							}

						}

						$company_id = $_POST['company_edit_id'];
						$requestUrl	=	$_POST['fromModule'];
						$searchStr=	"inventory";
						if(strpos($requestUrl,$searchStr)) {
							$this->Abas->redirect(HTTP_PATH."inventory/items/listview/".$company_id);
						}
						else {
							$this->Abas->redirect(HTTP_PATH."purchasing/");
						}

					}
				break;

				case 'add_conversion':
					$data = array();
					$data['units'] = $this->Inventory_model->getUnits();
					$data['companies'] = $this->Abas->getCompanies();
					$data['locations'] = $this->Abas->getUserLocations();
					$this->load->view('inventory/items/convert_form.php',$data);
				break;

				case 'convert':
					if(isset($_POST['stock_item_desc'])){
						$deduct = false;
						$location = $this->Mmm->sanitize($_POST['location_namex']);
						$qty_to_add = $this->Mmm->sanitize($_POST['qty_after_convert']);
						$company_id = $this->Mmm->sanitize($_POST['company_idx']);
						$sql = "SELECT * FROM inventory_items WHERE description='".$_POST['stock_item_desc']."' AND particular='".$_POST['stock_item_particular']."' AND unit='".$_POST['unit_after_convert']."'";
						$query = $this->db->query($sql);
						if($query){
							if($query->row()){
								//adds the quantity to the item of converted unit
								$deduct = true;
								$row = $query->row();
								$sql_add = "UPDATE inventory_quantity SET quantity=(quantity+".$qty_to_add.") WHERE item_id=".$row->id. " AND company_id=".$company_id." AND location='".$location."'";
								$query_add = $this->db->query($sql_add);
							}else{
								//insert new item if the item has no similar unit 
								$sql_insert = "SELECT * FROM inventory_items WHERE id=".$_POST['stock_item_id'];
								$query_insert = $this->db->query($sql_insert);
								if($query_insert){
									$insert = array();
									$item_insert = $query_insert->row(); 
									$insert['item_code'] = $item_insert->item_code;
									$insert['description'] = $this->Mmm->sanitize($_POST['new_item_desc']);
									$insert['particular'] = $this->Mmm->sanitize($_POST['new_particulars']);
									$insert['unit']		=	$this->Mmm->sanitize($_POST['unit_after_convert']);
									$insert['unit_price']	=	$this->Mmm->sanitize($_POST['price_after_covert']);
									$insert['reorder_level'] = 0;
									$insert['discontinued'] = null;
									$insert['sub_category'] = $item_insert->sub_category;
									$insert['type'] = $item_insert->type;
									$insert['stat'] = $item_insert->stat;
									$insert['qty']		=	0;
									$insert['category'] = $item_insert->category;
									$insert['location'] = $item_insert->location;
									$insert['stock_location'] = $item_insert->stock_location;
									$insert['account_type'] = $item_insert->account_type;
									$insert['requested'] = $item_insert->requested;
									$insert['created_on'] = date('Y-m-d H:m:s');
									$insert['created_by'] = $_SESSION['abas_login']['userid'];
									$inserted = $this->Mmm->dbInsert("inventory_items",$insert,"Inserted new unit for item");
									if($inserted){
										$deduct = true;	
										$insert2 = array();
										$insert2['item_id'] = $this->Abas->getLastIDByTable('inventory_items');
										$insert2['company_id'] = $company_id;
										$insert2['location'] = $location;
										$insert2['quantity'] = $qty_to_add;
										$insert2['stat'] = 1;
										$this->Mmm->dbInsert("inventory_quantity",$insert2,"Inserted quantity for the newly converted item with item ID:".$_POST['stock_item_id']);

									}else{
										$this->Abas->sysMsg("errmsg", "There was an error converting the item. Please contact your administrator." );
										die();
									}
								}
							}
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error converting the item. Please contact your administrator." );
							die();
						}

						//if true, deducts the quantity from the stock item
						if($deduct){

							$insert_convert = array();
							$insert_convert['item_id'] = $this->Mmm->sanitize($_POST['stock_item_id']);
							$insert_convert['company_id'] = $this->Mmm->sanitize($_POST['company_id']);
							$insert_convert['location'] = $this->Mmm->sanitize($_POST['location_namex']);
							$insert_convert['converted_item_id'] = (isset($insert2['item_id']))?$insert2['item_id']:0;
							$insert_convert['from_unit'] = 	$this->Mmm->sanitize($_POST['stock_unit']);
							$insert_convert['to_unit'] = $this->Mmm->sanitize($_POST['unit_after_convert']);
							$insert_convert['from_quantity'] = $this->Mmm->sanitize($_POST['qty_to_convert']);
							$insert_convert['to_quantity'] = $this->Mmm->sanitize($_POST['qty_after_convert']);
							$insert_convert['from_price'] =  $this->Mmm->sanitize($_POST['stock_unit_price']);
							$insert_convert['to_price'] = $this->Mmm->sanitize($_POST['price_after_covert']);
							$insert_convert['created_on'] = date('Y-m-d H:m:s');
							$insert_convert['created_by'] = $_SESSION['abas_login']['userid'];
							$insert_convert['stat'] = 1;
							$this->Mmm->dbInsert("inventory_conversions",$insert_convert,"Inserted conversion for item with ID:".$insert_convert['item_id']);

							$deduct_qty = ($_POST['stock_qty'] - $_POST['qty_to_convert']);
							$sql_deduct = "UPDATE inventory_quantity SET quantity=".$deduct_qty." WHERE item_id=".$_POST['stock_item_id']." AND company_id=".$company_id." AND location='".$location."'";

							$query_deduct = $this->db->query($sql_deduct);
							if($query_deduct){
								$this->Abas->sysMsg("sucmsg", "Successfully converted.");
								$this->Abas->sysNotif("Item Unit Conversion", $_SESSION['abas_login']['fullname']." has converted item ".$_POST['stock_item_desc'].".","Inventory","info");
							}else{
								$this->Abas->sysMsg("errmsg", "There was an error converting the item. Please contact your administrator." );
								die();
							}
						}
					}else{
						$this->Abas->sysMsg("errmsg", "No data submitted.");
						die();
					}
					$this->Abas->redirect(HTTP_PATH."inventory/items/listview/".$company_id."/".$location);
				break;

				case "conversion_history":
					$data = array();
					if(!$_POST){
						$data['companies'] = $this->Abas->getCompanies();
						$data['locations'] = $this->Abas->getUserLocations();
						$this->load->view('inventory/items/convert_filter.php',$data);
					}else{
						$company_id = $this->Mmm->sanitize($_POST['company_id']);
						$item_id = $this->Mmm->sanitize($_POST['item_id']);
						$location = $this->Mmm->sanitize($_POST['location']);
						$from_date = $this->Mmm->sanitize($_POST['dstart']);
						$data['dstart'] = $from_date;
						$to_date = $this->Mmm->sanitize($_POST['dfinish']);
						$data['dfinish'] = $to_date;
						$data['history'] = $this->Inventory_model->getItemUOMConversions($item_id,$company_id,$location,$from_date,$to_date);
						$data['item'] = $this->Inventory_model->getItem($item_id);
						$data['viewfile'] = 'inventory/items/convert_report.php';
						$this->load->view('gentlella_container.php',$data);
						
					}
				break;

				case 'print_qr_code':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data = array();
					$item = $this->Inventory_model->getInventoryQuantityDetail($id);
					$data['qr_data'] = $item;
					$data['delivery'] = $this->Inventory_model->getDelivery($item[0]->delivery_id);
					$data['company'] = $this->Abas->getCompany($item[0]->company_id);
					$data['item'] = $this->Inventory_model->getItem($item[0]->item_id);
					$this->load->view('inventory/items/print_qr_code.php',$data);
				break;

			}

		}

		public function autocomplete_employee(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT id, last_name, first_name, middle_name, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as full_name, position,department FROM hr_employees WHERE last_name LIKE '%".$search."%' OR first_name LIKE '%".$search."%' OR middle_name LIKE '%".$search."%' ORDER BY last_name LIMIT 0, 10";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						
						$ret[$ctr]['label']	=	$i['full_name'];
						$ret[$ctr]['value']	=	$i['id'];

						$position = $this->Abas->getPosition($i['position']);
						$ret[$ctr]['position']	=	$position->name;

						$department = $this->Abas->getDepartment($i['department']);
						$ret[$ctr]['department']	=	$department->name;
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}

		public function autocomplete_receiving_report(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM inventory_deliveries WHERE id LIKE '%".$search."%' LIMIT 0, 10";
			$rr	=	$this->db->query($sql);
			if($rr) {
				if($rr->row()) {
					$rr	=	$rr->result_array();
					$ret	=	array();
					foreach($rr as $ctr=>$i) {
						
						$ret[$ctr]['label']	=	$i['id'];
						$ret[$ctr]['value']	=	$i['id'];
						$ret[$ctr]['company_id']	=	$i['company_id'];
						$company = $this->Abas->getCompany($i['company_id']);
						$ret[$ctr]['company_name']	=	$company->name;

					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}

		public function check_item_quantity($item_quantity_id,$qty_to_issue,$user_location=''){
			if($user_location==''){
				$location = $_SESSION['abas_login']['user_location'];
			}else{
				$location = $user_location;
			}
			//$inventory = $this->Inventory_model->getItemQuantityPerCompany($item_id,$company_id,$location);
			$inventory = $this->Inventory_model->getInventoryQuantityDetail($item_quantity_id);
			$item_id = $inventory[0]->item_id;
			if($inventory){
				if($inventory[0]->location==$location){
					//$balance = $inventory[0]->total_quantity_received - $inventory[0]->total_quantity_issued;
					$balance = $inventory[0]->quantity - $inventory[0]->quantity_issued;
					if($_POST['unitx']==$_POST['packagingx']){
						if($balance>= $qty_to_issue){
							echo 1;
						}elseif($balance < $qty_to_issue){
							echo 2;
						}
					}else{
						$pck = $this->Inventory_model->getPackagingByItem($item_id);
						foreach($pck as $pckg){
							$packaging = str_replace('"','',$_POST['packagingx']);
							if($pckg->packaging==$packaging){
								$converted_qty = $pckg->conversion * $qty_to_issue;
								if($balance>= $converted_qty){
									echo 1;
								}else{
									echo 2;
								}
							}
						}
							
					}
				}else{
					echo 0;
				}
			}
			else{
				echo 0;
			}

		}

		public function get_item_quantity_for_issuance($item_quantity_id){
			$sql = "SELECT * FROM inventory_quantity WHERE id=".$item_quantity_id;
			$query = $this->db->query($sql);
			if($query){
				$items = $query->row();
				$packagings = $this->Inventory_model->getPackagingByItem($items->item_id);
				if(count($packagings)>0){
					foreach($packagings as $packaging){
						if($_POST['packagingx']=='"'.$packaging->packaging.'"'){
							$items->unit_price = ($items->unit_price*$packaging->conversion);
						}	
					}
				}
			}else{
				$items = NULL;
			}
			header('Content-Type: application/json');
			echo json_encode($items);
			exit();
		}

		public function autocomplete_items_for_issuance(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$company_id = $this->Mmm->sanitize($_GET['company']);
			$location = $this->Mmm->sanitize($_GET['location']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT *, inventory_quantity.id AS item_quantity_id,inventory_quantity.unit_price AS price FROM inventory_quantity INNER JOIN inventory_items ON inventory_quantity.item_id=inventory_items.id  WHERE (inventory_items.description LIKE '%".$search."%' OR inventory_items.item_code LIKE '%".$search."%') AND inventory_items.stat=1 AND inventory_quantity.stat=1 AND inventory_quantity.company_id=".$company_id." AND inventory_quantity.location='".$location."' AND inventory_quantity.quantity>inventory_quantity.quantity_issued ORDER BY inventory_items.description";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						$qty_available = ($i['quantity']-$i['quantity_issued']);
						$ret[$ctr]['label']	=	$i['item_code']. " | ".$i['description'].", ".$i['brand']." ".$i['particular']." (Qty: ".$qty_available." ".$i['unit'].", PHP ".$i['price'].")";
						$ret[$ctr]['value']	=	$i['id'];
						$ret[$ctr]['item_quantity_id']	=	$i['item_quantity_id'];
						$ret[$ctr]['item_code']	=	$i['item_code'];
						$ret[$ctr]['description']	=	$i['description'];
						$ret[$ctr]['particular']	=	$i['brand']." ".$i['particular'];
						$ret[$ctr]['quantity_available']	=	$qty_available;
						$ret[$ctr]['unit_price']	=	$i['price'];
						$ret[$ctr]['unit']	=	$i['unit'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}

		/*public function check_item_price_by_rr($item_id,$rr_id){
			$rr_details = $this->Inventory_model->getDeliveryDetails($rr_id);
			foreach($rr_details as $row){
				if($row['item_id']==$item_id){
					$ret['included_rr'] = 1;
					$ret['unit_price'] = $row['unit_price'];
					$ret['unit'] = $row['unit'];
					$inventory = $this->Inventory_model->getItemPriceHistory($item_id);
					if($inventory){
						foreach($inventory as $row2){
							if($row2->unit_price==$row['unit_price']){
								$ret['id'] = $row2->id;
							}else{
								$ret['id'] = 0;
							}
						}
					}else{
						$ret['id'] = 0;
					}
					break;
				}
			}
			header('Content-Type: application/json');
			echo json_encode($ret);
			exit();
		}*/

		public function check_item_for_issuance($item_qty_id){
			$item_issuance = $this->Inventory_model->getInventoryQuantityDetail($item_qty_id);
			$item = $this->Inventory_model->getItem($item_issuance[0]->item_id);
			$item_issuance[0]->item_code = $item[0]['item_code'];
			$item_issuance[0]->item_name = $item[0]['item_name'];
			$item_issuance[0]->particular = $item[0]['brand']." ".$item[0]['particular'];
			header('Content-Type: application/json');
			echo json_encode($item_issuance);
			exit();
		}
		public function get_item_packaging($item_id){
			$packagings = $this->Inventory_model->getPackagingByItem($item_id);
			header('Content-Type: application/json');
			echo json_encode($packagings);
			exit();
		}
		public function get_vessels_by_company($company_id) {
			$vessels		=	$this->Abas->getVesselsByCompany($company_id,true);
			header('Content-Type: application/json');
			echo json_encode( $vessels);
			exit();
		}
		public function autocomplete_msis(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM inventory_issuance WHERE stat=1 AND is_cleared=1 AND id LIKE '%".$search."%' ORDER BY id";
			$issuance	=	$this->db->query($sql);
			if($issuance) {
				if($issuance->row()) {
					$issuance	=	$issuance->result_array();
					$ret	=	array();
					foreach($issuance as $ctr=>$i) {
						$ret[$ctr]['label']	= $i['id']." (MSIS No.".$i['control_number'].")";
						$ret[$ctr]['value']	=	$i['id'];
						$ret[$ctr]['company_id']	=	$i['company_id'];
						$company = $this->Abas->getCompany($ret[$ctr]['company_id']);
						$ret[$ctr]['company_name']	=	$company->name;
						$ret[$ctr]['vessel_id']	=	$i['vessel_id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function autocomplete_items_for_return(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$company_id = $this->Mmm->sanitize($_GET['company']);
			$msis_id = $this->Mmm->sanitize($_GET['msis']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT inventory_issuance_details.id, inventory_issuance_details.issuance_id,inventory_issuance_details.item_id,inventory_issuance_details.unit,inventory_issuance_details.unit_price,inventory_issuance_details.qty,inventory_items.item_code,inventory_items.description,inventory_items.brand,inventory_items.particular FROM inventory_issuance_details INNER JOIN inventory_issuance ON inventory_issuance_details.issuance_id = inventory_issuance.id INNER JOIN inventory_items ON inventory_issuance_details.item_id = inventory_items.id WHERE (inventory_items.description LIKE '%".$search."%' OR inventory_items.item_code LIKE '%".$search."%') AND inventory_items.stat=1 AND inventory_issuance.company_id=".$company_id." AND inventory_issuance.id=".$msis_id." ORDER BY inventory_items.description";
			
			$items	=	$this->db->query($sql);
			if($items) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					$ret[$ctr]['label']	=	$i['item_code']. " | ".$i['description'].", ".$i['brand']." ".$i['particular']." (".$i['unit'].", PHP ".$i['unit_price'].")";
					$ret[$ctr]['value']	=	$i['item_id'];
					$ret[$ctr]['item_code']	=	$i['item_code'];
					$ret[$ctr]['description']	=	$i['description'];
					$ret[$ctr]['particular']	=	$i['brand']." ".$i['particular'];
					$ret[$ctr]['unit_price']	=	$i['unit_price'];
					$ret[$ctr]['unit']	=	$i['unit'];
					$ret[$ctr]['quantity_issued']	=	$i['qty'];
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
		public function get_item_issued($issuance_id,$item_id){
			$sql = "SELECT * FROM inventory_issuance_details WHERE issuance_id=".$issuance_id." AND item_id=".$item_id;
			$query = $this->db->query($sql);
			if($query){
				$items = $query->row();
				$packagings = $this->Inventory_model->getPackagingByItem($item_id);
				if(count($packagings)>0){
					foreach($packagings as $packaging){
						if($_POST['packagingx']=='"'.$packaging->packaging.'"'){
							$items->unit_price = ($items->unit_price*$packaging->conversion);
						}	
					}
				}
			}else{
				$items = NULL;
			}
			header('Content-Type: application/json');
			echo json_encode($items);
			exit();
		}
		public function check_item_issued($issuance_id,$item_id){
			$item_issued[0] = (object)array();
			$sql = "SELECT * FROM inventory_issuance_details WHERE issuance_id=".$issuance_id." AND item_id=".$item_id;
			$query = $this->db->query($sql);
			if($query){
				$issuance = $query->row();
				if($issuance){
					$item = $this->Inventory_model->getItem($item_id);
					$item_issued[0]->item_id = $item[0]['id'];
					$item_issued[0]->item_code = $item[0]['item_code'];
					$item_issued[0]->item_name = $item[0]['item_name'];
					$item_issued[0]->particular = $item[0]['particular'];
					$item_issued[0]->unit = $issuance->unit;
					$item_issued[0]->unit_price = $issuance->unit_price;
					$item_issued[0]->quantity_issued = $issuance->qty;
				}
			}else{
				$item_issued[0]->item_id = 0;
			}

			header('Content-Type: application/json');
			echo json_encode($item_issued);
			exit();
		}
		public function stock_in_out_summary($action){

			switch ($action) {
				case 'filter':
					$this->load->view('inventory/stock_in_out_summary/filter.php');
				break;

				case 'result':
					$data = array();
					$start_date = $this->Mmm->sanitize($_POST['start_date']);
					$end_date = $this->Mmm->sanitize($_POST['end_date']);

					$sql = "SELECT * FROM (SELECT 'Receiving' AS type, inventory_deliveries.tdate AS trans_date,inventory_deliveries.remark AS remark,idel.delivery_id AS ref_id,idel.item_id AS item_id,idel.unit AS unit,idel.unit_price AS unit_price,idel.quantity AS quantity FROM (inventory_delivery_details idel JOIN inventory_deliveries ON(idel.delivery_id = inventory_deliveries.id)) UNION SELECT 'Issuance' AS type, inventory_issuance.issue_date AS trans_date,inventory_issuance.remark AS remark,iiss.issuance_id AS ref_id,iiss.item_id AS item_id,iiss.unit AS unit,iiss.unit_price AS unit_price,iiss.qty AS quantity FROM (inventory_issuance_details iiss JOIN inventory_issuance on(iiss.issuance_id = inventory_issuance.id))) data WHERE trans_date BETWEEN '".$start_date."' AND '".$end_date."' GROUP BY item_id ORDER BY item_id, unit ASC";
		 			$query = $this->db->query($sql);
		 			if($query){
		 				$data['stock_in_out'] = $query->result();
		 			}
		 			$data['start_date'] = $start_date;
		 			$data['end_date'] = $end_date;
					$data['viewfile'] = 'inventory/stock_in_out_summary/result.php';
					$this->load->view('gentlella_container.php',$data);
				break;
			}
		}
		public function dead_stocks_summary($action){

			switch ($action) {
				case 'filter':
					$this->load->view('inventory/dead_stocks_summary/filter.php');
				break;

				case 'result':
					$data = array();
					$start_date = $this->Mmm->sanitize($_POST['start_date']);
					$end_date = $this->Mmm->sanitize($_POST['end_date']);

					$sql = "SELECT * FROM (SELECT 'Receiving' AS type, inventory_deliveries.tdate AS trans_date,inventory_deliveries.remark AS remark,idel.delivery_id AS ref_id,idel.item_id AS item_id,idel.unit AS unit,idel.unit_price AS unit_price,idel.quantity AS quantity FROM (inventory_delivery_details idel JOIN inventory_deliveries ON(idel.delivery_id = inventory_deliveries.id)) UNION SELECT 'Issuance' AS type, inventory_issuance.issue_date AS trans_date,inventory_issuance.remark AS remark,iiss.issuance_id AS ref_id,iiss.item_id AS item_id,iiss.unit AS unit,iiss.unit_price AS unit_price,iiss.qty AS quantity FROM (inventory_issuance_details iiss JOIN inventory_issuance on(iiss.issuance_id = inventory_issuance.id))) data WHERE trans_date BETWEEN '".$start_date."' AND '".$end_date."'  GROUP BY item_id ORDER BY item_id, unit ASC";
		 			$query = $this->db->query($sql);
		 			if($query){
		 				$data['summary'] = $query->result();
		 			}
		 			$data['start_date'] = $start_date;
		 			$data['end_date'] = $end_date;
					$data['viewfile'] = 'inventory/dead_stocks_summary/result.php';
					$this->load->view('gentlella_container.php',$data);
				break;
			}
		}
		public function monthly_inventory_report($action,$id=''){
			
			switch ($action) {
				case 'load':
					$table = "inventory_monthly_reports";
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data = $this->Abas->getDataForBSTable($table,$search,$limit,$offset,$order,$sort);
						foreach($data['rows'] as $ctr=>$item) {
							if(isset($item['company_id'])) {
								$company							=	$this->Abas->getCompany($item['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							if(isset($item['created_by'])) {
								$created_by							=	$this->Abas->getUser($item['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if(isset($item['created_on'])) {
								$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($item['created_on']));
								$data['rows'][$ctr]['month_of'] = date("F-Y", strtotime($item['created_on']));
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				break;

				case 'listview':
					$data['viewfile']	=	"inventory/monthly_inventory_report/listview.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'add':
					$data['companies']		=	$this->Abas->getCompanies();
					$this->load->view('inventory/monthly_inventory_report/form.php',$data);
				break;

				case 'insert':

					$created_on = date('Y-m-d H:m:s');
					$month = date('m');
					$year = date('Y');
					$company_id =$this->Mmm->sanitize($_POST['company_id']);
					$company = $this->Abas->getCompany($company_id);
					$location = $_SESSION['abas_login']['user_location'];

					$sql = "SELECT * FROM inventory_monthly_reports WHERE company_id=".$company_id." AND MONTH(created_on)='".$month."' AND YEAR(created_on)= '".$year."' AND location='".$location."' AND status='Active'";
					$query = $this->db->query($sql);

					if($query){
						$result = $query->result();
						if(count($result)==0){
							
							$insert = array();
							$insert['company_id'] = $company_id;
							$insert['location'] = $location;
							$insert['created_on'] = $created_on;
							$month_of = date('F-Y');
							$insert['created_by'] = $_SESSION['abas_login']['userid'];
							$insert['status'] = 'Active';
							$insert['stat'] = 1;
							$insertMonthly = $this->Mmm->dbInsert("inventory_monthly_reports",$insert,"Added new Monthly Inventory Report");

							if($insertMonthly){
								$last_id_inserted = $this->Abas->getLastIDByTable('inventory_monthly_reports');
								
								$inventory = $this->Inventory_model->getInventoryPerCompany($company_id,$location);
								$multiInsertMonthly = array();
								foreach($inventory as $ctr=>$item){
									$multiInsertMonthly[$ctr]['monthly_report_id']	=	$last_id_inserted;
									$multiInsertMonthly[$ctr]['item_id']	=	$item->item_id;
									$multiInsertMonthly[$ctr]['location']	=	$item->location;
									$multiInsertMonthly[$ctr]['code']	=	'';
									$multiInsertMonthly[$ctr]['remarks']	=	'';
									$multiInsertMonthly[$ctr]['costing_method']	=	'';
									$multiInsertMonthly[$ctr]['unit']	=	$item->unit;
									$multiInsertMonthly[$ctr]['unit_price']	=	$item->unit_price;
									$multiInsertMonthly[$ctr]['quantity']	=	$item->qty_on_stock;
									$multiInsertMonthly[$ctr]['stat']	= 1;
								}

								$multiInsertMonthly = $this->Mmm->multiInsert('inventory_monthly_report_details',$multiInsertMonthly,'Added inventory per company on Monthly Inventory Report.');

								if($multiInsertMonthly){
									$this->Abas->sysMsg("sucmsg", "New Monthly Inventory Report for the month of ".$month_of." has been added by ".$_SESSION['abas_login']['username'] ." for company ".$company->name);
									$this->Abas->sysNotif("New Monthly Inventory Report", $_SESSION['abas_login']['fullname']." has added new Monthly Inventory Report for the month of ".$month_of." has been added by ".$_SESSION['abas_login']['username'] ." for company ".$company->name,"Inventory","info");
								}else{
									$this->Abas->sysMsg("errmsg", "There was an error while adding the details of the report. Kindly contact Administrator.");
								}
							}
						}else{
							$this->Abas->sysMsg("warnmsg", "A similar monthly report has already been created for company ".$company->name);
						}
					}else{
						$this->Abas->sysMsg("warnmsg", "A similar monthly report has already been created for company ".$company->name);
					}
					$this->Abas->redirect(HTTP_PATH."inventory/monthly_inventory_report/listview");
				break;

				case 'print':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data = array();
					$data['report'] =	$this->Inventory_model->getMonthlyInventoryReport($id);
					$data['report_details'] =	$this->Inventory_model->getMonthlyInventoryReportDetails($id);
					$data['company']		=	$this->Abas->getCompany($data['report']->company_id);
					$this->load->view('inventory/monthly_inventory_report/print',$data);
				break;

				case 'void':
					$report = $this->Inventory_model->getMonthlyInventoryReport($id);
					if($report->status<>'Void'){
						$company = $this->Abas->getCompany($report->company_id);
						$month_of = date('F-Y',strtotime($report->created_on));
						$update = array();
						$update['status'] = 'Void';
						$updateMonthly = $this->Mmm->dbUpdate("inventory_monthly_reports",$update,$id,"Void Monthly Inventory Report with Transaction Code No.".$id);
						if($updateMonthly){
							$this->Abas->sysMsg("sucmsg", "Monthly Inventory Report for the month of ".$month_of." has been voided by ".$_SESSION['abas_login']['username'] ." under company ".$company->name);
							$this->Abas->sysNotif("Void Monthly Inventory Report", $_SESSION['abas_login']['fullname']." has voided onthly Inventory Report for the month of ".$month_of." has been voided by ".$_SESSION['abas_login']['username'] ." under company ".$company->name,"Inventory","info");
						}else{
							$this->Abas->sysMsg("errmsg", "There was an error while adding the details of the report. Kindly contact Administrator.");
						}
					}else{
						$this->Abas->sysMsg("warnmsg", "This Monthly Inventory Report is already voided.");
					}
					$this->Abas->redirect(HTTP_PATH."inventory/monthly_inventory_report/listview");
				break;
			}
		}
		public function stock_level($action){
			$data = array();
			switch ($action) {
				case 'filter':
					$data['companies'] = $this->Abas->getCompanies();
					$data['locations']	= $this->Abas->getUserLocations();
					$this->load->view('inventory/stock_level/filter.php',$data);
				break;

				case 'result':
					$company_id = $this->Mmm->sanitize($_POST['company_filter']);
					$location = $this->Mmm->sanitize($_POST['location_filter']);

					$sql = "SELECT inventory_items_per_company.*,inventory_items.item_code,inventory_items.brand,inventory_items.description,inventory_items.particular,inventory_items.unit,inventory_items.category,inventory_items.stock_location,inventory_items.type,inventory_items.reorder_level FROM inventory_items_per_company INNER JOIN inventory_items ON inventory_items.id=inventory_items_per_company.item_id WHERE inventory_items_per_company.company_id=".$company_id." AND inventory_items_per_company.location='".$location."'";
		 			$query = $this->db->query($sql);

		 			if($query){
		 				$data['summary'] = $query->result();
		 			}else{
		 				$data['summary'] = "";
		 			}

		 			$company = $this->Abas->getCompany($company_id);
		 			$data['company_name'] = $company->name;
		 			$data['location'] = $location;
					$data['viewfile'] = 'inventory/stock_level/result.php';
					$this->load->view('gentlella_container.php',$data);
				break;
			}

		}
		public function company_quantity_transfer($inv_qty_id){
			$data = array();
			if($_POST){
				
				$item_id = $this->Mmm->sanitize($_POST['item_id']);
				$quantity = $this->Mmm->sanitize($_POST['transfer_qty']);
				$company_id = $this->Mmm->sanitize($_POST['to_company']);
				//$inv_qty_id = $this->Mmm->sanitize($_POST['inv_qty_id']);
				$inv = $this->Inventory_model->getInventoryQuantityDetail($inv_qty_id);

				$insert = array();
				$insert['item_id']	= $item_id;
				$insert['delivery_id']	= 0;
				$insert['company_id']	= $company_id;
				$insert['location']	= $_SESSION['abas_login']['user_location'];
				$insert['unit']	= $inv[0]->unit;
				$insert['unit_price']	= $inv[0]->unit_price;
				$insert['quantity']	= $quantity;
				$insert['quantity_issued']	= 0;
				$insert['stat']	= 1;
				$insert['rack']	= '';

				$checkInsert = $this->Mmm->dbInsert("inventory_quantity",$insert,"Added the transferred quantity.");
				if($checkInsert){
					$update = array();
					$update['quantity'] = $inv[0]->quantity - $quantity;
					$updateCheck = $this->Mmm->dbUpdate("inventory_quantity",$update,$inv_qty_id,"Deducted the transferred quantity.");
					if($updateCheck){
						$this->Abas->sysMsg("sucmsg", "Succesfully transferred the quantity to another company.");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error while transferring the quantity. Kindly contact Administrator.");
					}
				}else{
					$this->Abas->sysMsg("errmsg", "There was an error while transferring the quantity. Kindly contact Administrator.");
				}

				$this->Abas->redirect(HTTP_PATH."inventory/items/listview");

			}else{

				$data['companies'] = $this->Abas->getCompanies();
				$inv = $this->Inventory_model->getInventoryQuantityDetail($inv_qty_id);

				$company = $this->Abas->getCompany($inv[0]->company_id);
				$data['company_name'] = $company->name;
				$data['company_id'] = $company->id;

				$data['item']  = $this->Inventory_model->getItem($inv[0]->item_id);

				$data['current_qty'] = $inv[0]->quantity-$inv[0]->quantity_issued;
				$data['inv_qty_id'] = $inv_qty_id;

				$mainview	=	"inventory/items/company_quantity_transfer_form.php";
				$this->load->view($mainview,$data);
			}
		}
	}
?>