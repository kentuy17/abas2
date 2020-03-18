<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Accounting extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			$this->load->database();
			$this->load->model("Abas");
			$this->load->model("Mmm");
			$this->load->model("Accounting_model");
			$this->load->model("Inventory_model");
			$this->load->model("Payroll_model");
			$this->load->model("Purchasing_model");
			$this->load->model("Finance_model");
			$this->load->model("Billing_model");
			$this->load->model("Collection_model");
			$this->load->model("Asset_Management_model");
			$this->output->enable_profiler(FALSE);
			if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
			define("SIDEMENU", "Accounting");
		}
		public function index()	{$data=array();
			$this->Abas->redirect(HTTP_PATH."accounting/chart_of_accounts");
		}
		public function vouchers($action='', $id='' , $type='') {$data=array();
			$this->Abas->checkPermissions("accounting|view_vouchers");
			$data['voucher_deliveries']	=	$this->Accounting_model->getDeliveriesForVoucher();
			$data['cash_advances']		=	$this->Finance_model->getCashRequest_ForVoucher();
			$data['banks']				=	$this->Abas->getBanks();
			$data['viewfile']			=	"accounting/vouchers.php";
			$mainview					=	"gentlella_container.php";
			if($id=="") {
				if($action=="purchasing_voucher_json") {
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"desc";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"tdate";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$table					=	"inventory_deliveries";
					$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
					$tablefields			=	$tablefields->result();
					if($limit!="") {
						if(is_numeric($limit)) {
							$limit	=	", ".$limit;
						}
					}
					if($offset!="") {
						if(is_numeric($offset)) {
							$offset	=	"LIMIT ".$offset;
						}
					}
					if($order!="") {
						if(strtolower($order)==='asc' || strtolower($order)==='desc') {
							$order	=	"ORDER BY ".($sort!=""?$sort:"tdate")." ".$order;
						}
					}
					if($search!="") {
						$searchfields	=	"";
						foreach($tablefields as $tf) {
							if($searchfields!="") $searchfields.="OR ";
							$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$search."%' ";
						}
					}
					else {
						$searchfields	=	"1=1 ";
					}
					$sql	=	"SELECT * FROM ".$table." WHERE $searchfields AND stat=0 AND is_cleared=1 $order $offset $limit";
					$total	=	"SELECT * FROM ".$table." WHERE $searchfields AND stat=0 AND is_cleared=1";
					$all	=	$this->db->query($sql);
					$total	=	$this->db->query($total);
					if($all) {
						$all=	$all->result_array();
						foreach($all as $ctr=>$a) {
							$all[$ctr]['supplier_name']			=	"";
							$all[$ctr]['voucher_number']		=	"";
							$all[$ctr]['voucher_status']		=	"For Processing";
							$all[$ctr]['tdate']					=	$a['tdate']!=null ? date("j F Y", strtotime($a['tdate'])):"";
							$all[$ctr]['amount']				=	is_numeric($a['amount']) ? number_format($a['amount'],2):$a['amount'];
							if($a['voucher_id']!=null) {
								$voucher				=	$this->Accounting_model->getVoucher($a['voucher_id']);
								$all[$ctr]['voucher_number']	=	$voucher['voucher_number'];
								$all[$ctr]['voucher_status']	=	ucwords(strtolower(trim($voucher['status'])));
							}
							if($a['supplier_id']!=null) {
								$supplier						=	$this->Abas->getSupplier($a['supplier_id']);
								$all[$ctr]['supplier_name']		=	$supplier['name'];
							}
						}
						$data	=	array("total"=>count($total->result_array()),"rows"=>$all);
					}
					else {
						$data	=	false;
					}
					die(json_encode($data));
				}
				elseif($action=="cash_request_json") {
					if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) { // formats for bootstrap table
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("ac_cash_advances",$search,$limit,$offset,$order,$sort);
						if($data!=false) {
							foreach($data['rows'] as $ctr=>$cash_request) {
								$data['rows'][$ctr]['requested_by']			=	"";
								$data['rows'][$ctr]['department_name']		=	"";
								$data['rows'][$ctr]['voucher_number']		=	"";
								$data['rows'][$ctr]['voucher_status']		=	"For Processing";
								$data['rows'][$ctr]['date_released']		=	$cash_request['date_released']!=null ? date("j F Y h:i a", strtotime($cash_request['date_released'])):"";
								$data['rows'][$ctr]['date_requested']		=	$cash_request['date_requested']!=null ? date("j F Y h:i a", strtotime($cash_request['date_requested'])):"";
								$data['rows'][$ctr]['amount']				=	is_numeric($cash_request['amount']) ? number_format($cash_request['amount'],2):$cash_request['amount'];
								if(is_numeric($cash_request['requested_by'])) {
									$requisitioner							=	$this->Abas->getEmployee($cash_request['requested_by']);
									$data['rows'][$ctr]['requested_by']		=	$requisitioner['full_name'];
								}
								if(is_numeric($cash_request['department'])) {
									$department								=	$this->Abas->getDepartment($cash_request['department']);
									$data['rows'][$ctr]['department_name']	=	$department->name;
								}
								if(is_numeric($cash_request['voucher_id'])) {
									$voucher								=	$this->Accounting_model->getVoucher($cash_request['voucher_id']);
									$data['rows'][$ctr]['status']			=	$voucher['status'];
									$data['rows'][$ctr]['voucher_number']	=	$voucher['voucher_number'];
								}
							}
							header('Content-Type: application/json');
							echo json_encode($data);
							exit();
						}
						die(json_encode($data));
					}
				}
			}
			elseif(is_numeric($id)) {
					$data['id']				=	$id;
					$data['type']			=	$type;
					if(isset($_POST['type'])){ $type 	= $_POST['type'];}
					if($type == 'rfp'){
						$data['request_payment']	=	$this->Accounting_model->getRfp_ForVoucher($id);
						$data['type']				= 	$type;
						$data['company']			=	$this->Abas->getCompany($data['request_payment'][0]['company_id']);
						$amount						= 	$data['request_payment'][0]['amount'];
						$supplier					=	$this->Abas->getSupplier($data['request_payment'][0]['payee']);
						$transaction_type			= 	'Request for Payment';
						$tab						=  	'services';
					}
					elseif($type=="purchase") {
						$delivery					=	$this->Inventory_model->getDelivery($id);
						$delivery					=	$delivery[0];
						$amount						=	$delivery['amount'];
						$purchase_order				=	$this->Purchasing_model->getPurchaseOrder($delivery['po_no']);
						$supplier					=	$this->Abas->getSupplier($delivery['supplier_id']);
						//$data['supplier']			=	$supplier;
						$data['company']			=	$this->Abas->getCompany($purchase_order['company_id']);
						$data['purchase_order']		=	$purchase_order;
						$data['delivery']			=	$delivery;
						$data['delivery_detail']	=	$this->Inventory_model->getDeliveryDetails($id);
						$transaction_type			= 	"Purchase Order";
						$tab						=  	'purchasing';
					}
				$data['supplier']		=	$supplier;
				if($action=="create") {
					$mainview				=	'accounting/voucher_form.php';
				}
				elseif($action=="insert") {
					$insert['type']				=	$this->Mmm->sanitize($_POST['voucher_type']);
					$insert['bank_id']			=	$this->Mmm->sanitize($_POST['bank']); //this will be the coa_id
					$insert['check_num']		=	$this->Mmm->sanitize($_POST['check_no']);
					$insert['voucher_number']	=	$this->Mmm->sanitize($_POST['voucher_no']);
					$insert['amount']			=	$amount; //this will be the AP amount - after tax
					$insert['wtax']				=	$this->Mmm->sanitize($_POST['wtax']);
					$insert['payee']			=	$supplier['id'];
					$insert['remark']			=	$this->Mmm->sanitize($_POST['remark']);
					$insert['added_by']			=	$this->Mmm->sanitize($_SESSION['abas_login']['userid']);
					$insert['vtax']				=	$this->Mmm->sanitize($_POST['vat']);
					$insert['bir_visible']		=	$supplier['issues_reciepts'];
					$insert['status']			=	'For funding approval';
					$insert['voucher_date']		=	date("Y-m-d H:i:s");
					$insert['stat']				=	1;
					$insert['transaction_type']	=	$transaction_type;
					$insert['company_id']		=	$data['company']->id;
					$db	=	$this->Mmm->dbInsert('ac_vouchers', $insert, "New voucher");
					if($db==true) {
						$voucher_id				=	$this->db->insert_id();
						//handle update of table of origin
						if($type == 'non-po'){
							$update['voucher_id']	=	$voucher_id;
							$update['status']		=	'For funding approval';
							$sql					=	$this->Mmm->dbUpdate('ac_request_payment', $update, $id, "Update request for payment with voucher id");

						}elseif($type=="po") {
							$update['voucher_id']	=	$voucher_id;
							$sql					=	$this->Mmm->dbUpdate('inventory_deliveries', $update, $id, "Update delivery with voucher id");
						}

						$notif_msg				=	$_SESSION['abas_login']['fullname']." has created a new voucher.";
						$this->Abas->sysNotif("New voucher", $notif_msg, "Finance");
						$this->Abas->sysMsg("sucmsg", "New Voucher Created!");
						$this->Abas->redirect(HTTP_PATH.'accounting/payables_view/##'.$tab);
					}
				}
				elseif($action=="print_voucher") {
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['viewfile']	=	"printable_voucher.php";
					$mainview			=	"pdf-container.php";
				}
				elseif($action=="print_2307") {
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['viewfile']	=	"printable_2307.php";
					$mainview			=	"pdf-container.php";
				}
			}
			$this->load->view($mainview,$data);
		}
		public function voucher_view()	{$data=array();
			$this->Abas->redirect(HTTP_PATH."accounting/vouchers");
			if(isset($_SESSION['tab'])){
			$data['tab']= $_SESSION['tab'];
			}else{
				$data['tab']= 'request';
			}
			$data['voucher_deliveries'] = $this->Accounting_model->getDeliveriesForVoucher();
			$data['services'] = '';
			$data['cash_advance'] = $this->Finance_model->getCashRequest_ForVoucher();
			$this->load->view('accounting/voucher_view.php',$data);
		}
		public function ap_form($id='')	{$data=array();
			if($id!=''){
				$data['ap_voucher']	=	$this->Accounting_model->getAPVoucher($id);
				$ttype				=	$this->uri->segment(4);
				$data['ttype']		=	$ttype;
				if($data['ttype']=='po'){
					$del_id						= $data['ap_voucher'][0]['rr_no'];
					if($del_id==NULL){
						$this->Abas->sysMsg("errmsg", "Delivery not found! Please try again.");
						$this->Abas->redirect(HTTP_PATH);
					}
					$data['po_info']			= 	$this->Purchasing_model->getPurchaseOrder($data['ap_voucher'][0]['po_no']);
					$data['apv_info']			=	$this->Inventory_model->getDelivery($del_id);//have to work this out
					$data['delivery_detail']	=	$this->Inventory_model->getDeliveryDetails($del_id);
					$data['payee']				=	$this->Abas->getSupplier($data['po_info']['supplier_id']);
					$data['payee_name']			=	$data['po_info']['supplier_name'];
 					$data['company_id']			=	$data['po_info']['company_id'];
					$data['company_name']		=	$data['po_info']['company_name'];
					$data['ap_amount']			=	$this->Accounting_model->getJournalEntry($data['ap_voucher'][0]['journal_id']);
					$ref_table					=	'ac_ap_vouchers';
					$data['ac_entries']			=	$this->Inventory_model->getAccountingEntry($id,$ref_table);
				}
				else{
					//$data['ttype']				= 'non-po';
					$data['apv_info']	= 	$this->Accounting_model->getRequestPayment($id);
					if($data['apv_info'][0]['payee_type'] == 'Supplier'){
						$data['payee'] 	= $this->Abas->getSupplier($data['apv_info'][0]['payee']);
						$data['payee_name'] = $data['payee']['name'];
					}
					elseif($data['apv_info'][0]['payee_type'] == 'Employee'){
						$data['payee'] 	= $this->Abas->getEmployee($data['apv_info'][0]['payee']);
						$data['payee_name'] = $data['payee']['full_name'];
					}
					else{
						$data['payee']['id']	= '100001'; //others refers to payee_others
						$data['payee_name'] = $data['apv_info'][0]['payee_others'];
					}
					if($data['apv_info'][0]['payee']==''){
						$data['payee_name'] = $data['apv_info'][0]['payee_others'];
					}
					//$data['company']	= 	$this->Abas->getCompany($data['apv_info'][0]['company_id']);
					$data['company_id'] 		= 	$data['apv_info'][0]['company_id'];
					$company 					= 	$this->Abas->getCompany($data['apv_info'][0]['company_id']);
					$data['company_name']		= 	$company->name;
				}
				if(!isset($data['company_id'])){
					$this->Abas->errsMsg("errmsg", "Missing company in Check Voucher preparation, please contact admin!");
				}
				//revise to get company banks only
				$data['banks']				=	$this->Accounting_model->getBanksFromCOA($data['company_id']);
				$data['vessels']			=	$this->Abas->getVesselsByCompany($data['company_id']);
				$data['departments']		=	$this->Abas->getDepartments();
				$data['contracts']			=	$this->Abas->getContracts($data['company_id']);
				//$data['banks']				=	$this->Abas->getBanks();

				$this->load->view('accounting/ap_form.php',$data);
			}else{ var_dump('Error occured. No Id passed.');}
		}
		public function ap_voucher_history($id='')	{$data=array();


				if($_POST){

				}

				$sql = "SELECT * FROM ac_ap_vouchers order by ";

				$data['viewfile']			=	"accounting/ap_voucher_history.php";
				$mainview					=	"gentlella_container.php";

				$this->load->view($mainview, $data);
		}
		public function voucher_CRform($id='')	{$data=array();

			if($id!=''){
				$data['cash_advance'] = $this->Finance_model->getCashAdvance($id);

				$data['banks']	=	$this->Abas->getBanks();

				$this->load->view('accounting/voucher_CRform.php',$data);
			}else{ var_dump('Error occured. No Id passed.');}
		}
		public function ap_vouchers($transaction_type="")	{$data=array();
			if(isset($_SESSION['tab'])){
				$data['tab']= $_SESSION['tab'];
			}
			else{
				$data['tab']= 'request';
			}

			if(!empty($_GET) && $transaction_type=="po") {
				$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
				$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
				$order	=	isset($_GET['order'])?$_GET['order']:"desc";
				$sort	=	isset($_GET['sort'])?$_GET['sort']:"v.created_on";
				$search	=	isset($_GET['search'])?$_GET['search']:"";
				$table					=	"ac_transaction_journal";
				$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
				$tablefields			=	$tablefields->result();
				if($limit!="") {
					if(is_numeric($limit)) {
						$limit	=	", ".$limit;
					}
				}
				if($offset!="") {
					if(is_numeric($offset)) {
						$offset	=	"LIMIT ".$offset;
					}
				}
				if($order!="") {
					if(strtolower($order)==='asc' || strtolower($order)==='desc') {
						$order	=	"ORDER BY ".($sort!=""?$sort:"v.created_on")." ".$order;
					}
				}
				if($search!="") {
					$searchfields	=	"po_no LIKE '%".$search."%'";
					/*foreach($tablefields as $tf) {
						if($searchfields!="") $searchfields.="OR ";
						if($tf->COLUMN_NAME=="id") $tf->COLUMN_NAME="`v`.`id`";
						elseif($tf->COLUMN_NAME=="company_id") $tf->COLUMN_NAME="`v`.`company_id`";
						elseif($tf->COLUMN_NAME=="stat") $tf->COLUMN_NAME="`v`.`stat`";
						elseif($tf->COLUMN_NAME=="created_on") $tf->COLUMN_NAME="`v`.`created_on`";
						elseif($tf->COLUMN_NAME=="created_by") $tf->COLUMN_NAME="`v`.`created_by`";
						elseif($tf->COLUMN_NAME=="control_number") $tf->COLUMN_NAME="`v`.`control_number`";
						else $tf->COLUMN_NAME="`".$tf->COLUMN_NAME."`";
						$searchfields	.=	$tf->COLUMN_NAME." LIKE '%".$search."%' ";
					}
					$searchfields	.=	"OR `j`.`credit_amount` LIKE '%".$search."%'";
					$searchfields	.=	"OR `j`.`debit_amount` LIKE '%".$search."%'";*/
				}
				else {
					$searchfields	=	"1=1";
				}
				$sql	=	"SELECT (SELECT id FROM ac_ap_vouchers WHERE id=j.reference_id) AS id, (SELECT control_number FROM ac_ap_vouchers WHERE id=j.reference_id) AS control_number, (SELECT rfp_no FROM ac_ap_vouchers WHERE id=j.reference_id) AS rfp_no, (SELECT payee FROM ac_ap_vouchers WHERE id=j.reference_id) AS payee, (SELECT po_no FROM ac_ap_vouchers WHERE id=j.reference_id) AS po_no, j.company_id, j.credit_amount, j.debit_amount, j.transaction_id, j.posted_on  FROM ac_transaction_journal AS j JOIN ac_ap_vouchers AS v ON j.reference_id=v.id WHERE reference_table = 'ac_ap_vouchers' AND v.stat=1 AND coa_id = ".TRADE_PAYABLE." OR coa_id = ".PAYABLE_OTHERS." AND j.reconciling_id IS NOT NULL AND ".$searchfields." ".$order." ".$offset." ".$limit;
				$sql	=	"SELECT v.id,v.po_no,v.rfp_no,v.payee,v.control_number,v.company_id,v.stat, (SELECT credit_amount FROM ac_transaction_journal WHERE reference_id=v.id AND coa_id = ".TRADE_PAYABLE." OR coa_id = ".PAYABLE_OTHERS." LIMIT 1) AS credit_amount, (SELECT debit_amount FROM ac_transaction_journal WHERE reference_id=v.id AND coa_id = ".TRADE_PAYABLE." OR coa_id = ".PAYABLE_OTHERS." LIMIT 1) AS debit_amount, (SELECT transaction_id FROM ac_transaction_journal WHERE reference_id=v.id AND coa_id = ".TRADE_PAYABLE." OR coa_id = ".PAYABLE_OTHERS." LIMIT 1) AS transaction_id, (SELECT posted_on FROM ac_transaction_journal WHERE reference_id=v.id AND coa_id = ".TRADE_PAYABLE." OR coa_id = ".PAYABLE_OTHERS." LIMIT 1) AS posted_on FROM ac_ap_vouchers AS v WHERE stat=1 AND ".$searchfields." ".$order." ".$offset." ".$limit;
				// echo $sql;
				$total	=	"SELECT (SELECT id FROM ac_ap_vouchers WHERE id=j.reference_id) FROM ac_transaction_journal AS j JOIN ac_ap_vouchers AS v ON j.reference_id=v.id WHERE reference_table = 'ac_ap_vouchers' AND v.stat=1 AND coa_id = ".TRADE_PAYABLE." OR coa_id = ".PAYABLE_OTHERS." AND ".$searchfields;
				$all	=	$this->db->query($sql);
				$total	=	$this->db->query($total);
				if($all) {
					$all=	$all->result_array();
					foreach($all as $ctr=>$a) {
						$payee_name			=	'';
						$bgcolor			=	'';
						$payment_schedule	=	date('F j, Y');
						if($a['rfp_no']!=0){	//non-po
							$sql			=	"SELECT * FROM ac_request_payment WHERE transaction_id=".$a['transaction_id'];
							$d				=	$this->db->query($sql);
							$ret			=	(array)$d->row();
							$company		=	$this->Abas->getCompany($ret['company_id']);
							$company_name	=	$company->name;
							$ptype			=	$ret['payee_type'];
							if($ptype=='' || $a['payee']==''){
								$payee		=	$ret['payee_others'];
								$payee_name	=	$ret['payee_others'];
							}
							if($ptype=='Supplier'){
								$payee		=	$this->Abas->getSupplier($a['payee']);
								$payee_name	=	$payee['name'];
								if($payee['payment_terms']!=''){
									$payment_schedule	=	date('F j, Y', strtotime("+".$payee['payment_terms']." days"));
								}
							}
							if($ptype=='Employee'){
								$payee		=	$this->Abas->getEmployee($a['payee']);
								$payee_name	=	$payee['full_name'];
							}
						}
						if($a['po_no']!=''){	//po
							//get po detail
							$po_det			=	$this->Purchasing_model->getPurchaseOrder($a['po_no']);
							$company_name	=	$po_det['company_name'];
							$payee_name		=	$po_det['supplier_name'];
							if($po_det['supplier']['payment_terms']!=''){
								$payment_schedule	=	date('F j, Y', strtotime("+".$po_det['supplier']['payment_terms']." days"));
							}
						}
						if($a['credit_amount'] > 0){
							$amount	=	$a['credit_amount'];
						}
						else{
							$amount	=	$a['debit_amount'];
						}
						$all[$ctr]['created_on']		=	date("j F Y",strtotime($a['posted_on']));
						$all[$ctr]['company_name']		=	$company_name;
						$all[$ctr]['payee_name']		=	$payee_name;
						$all[$ctr]['amount']			=	$amount;
						$all[$ctr]['payment_schedule']	=	$payment_schedule;
						$all[$ctr]['manage']			=	'<a class="btn btn-default btn-xs btn-block"href="'.HTTP_PATH.'accounting/ap_form/'.$a['id'].'/po" data-toggle="modal" data-target="#modalDialog" title="View Details">Create Check Voucher</a>';
					}
					$data	=	array("total"=>count($total->result_array()),"rows"=>$all);
				}
				else {
					$data	=	false;
				}
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			}

			//NON-PO transactions
			$sql1 = "SELECT * FROM ac_request_payment WHERE status='For voucher'";
			$db1 = $this->db->query($sql1);
			//PO transactions
			$sql = "SELECT j.reference_table,j.reference_id,j.credit_amount,j.debit_amount,v.payee,v.po_no,v.date_created,v.id,j.reconciling_id,v.rfp_no
					FROM ac_transaction_journal AS j
						INNER JOIN ac_ap_vouchers AS v ON j.reference_id=v.id
					WHERE reference_table = 'ac_ap_vouchers'
						AND coa_id=".TRADE_PAYABLE." OR coa_id=".PAYABLE_OTHERS."
						HAVING reconciling_id is NULL
						ORDER BY posted_on DESC";
			$db = $this->db->query($sql);
			if($db) {
				if($db->row()) {
					$data['ap_vouchers']	=	$db->result_array();
				}
			}
			if($db1) {
				if($db1->row()) {
					$data['non_po']			=	$db1->result_array();
				}
			}
			// die();
			$data['viewfile']			=	"accounting/ap_voucher.php";
			$mainview					=	"gentlella_container.php";
			$this->load->view($mainview, $data);
		}
		public function ap_voucher_search()	{$data=array();

			//var_dump($data);exit;
			$this->load->view('accounting/ap_voucher_search.php');
 			//$data['ap_vouchers'] = $this->Accounting_model->getApprovedVoucher();
			//$data['ap_vouchers'] = $this->Accounting_model->getAP_vouchers();
			//$this->load->view('accounting/ap_voucher.php',$data);

		}
		public function search_cv_voucher()	{$data=array();


			$tcode = '';
			$check_no = '';

			if(isset($_POST['transaction_code'])  && $_POST['transaction_code'] !=''){
				$tcode = ' AND id = '.$_POST['transaction_code'];
			}

			if(isset($_POST['check_no'])  && $_POST['check_no'] !=''){
				$check_no = ' AND check_num = '.$_POST['check_no'];
			}




			$sql = "SELECT * FROM ac_vouchers WHERE 1 = 1".$tcode." ".$check_no;

			$db = $this->db->query($sql);
			$res = $db->result_array();

			if($res){

				if(is_numeric($res[0]['id'])){
					//call print_voucher
					$this->Abas->redirect(HTTP_PATH.'accounting/print_voucher/'.$res[0]['id']);
				}
			}else{
				echo "
					<script>alert('No record found.');</script>
				";

				$this->ap_vouchers();
			}


			//var_dump($data);exit;
			//$this->load->view('accounting/ap_voucher_search.php');
 			//$data['ap_vouchers'] = $this->Accounting_model->getApprovedVoucher();
			//$data['ap_vouchers'] = $this->Accounting_model->getAP_vouchers();
			//$this->load->view('accounting/ap_voucher.php',$data);

		}
		public function voucher_entries($id='')	{$data=array();


			$entries = $_POST['id'];
			$action = $_POST['action'];
			//var_dump($entries); exit;
			if(count($entries)==0){
				var_dump('Please enter accounting entry.');
				die();
			}

			if($action== 'del'){

				$group = explode(",",$entries);
			}else{

				$group = explode(",",$entries[0]);
			}
			//var_dump($group);exit;
			$ctr = count($group) - 1;



			$htm = '
			<table id="datatable-responsive" style="margin-top:10px; float:left" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
									  <thead>
										<tr>
										  <th width="15%">Account Code</th>
										  <th width="20%">Account Name</th>
										  <th width="15%">Debit</th>
										  <th width="15%">Credit</th>
										  <th width="40%">Memo</th>
										  <th width="10%">*</th>
										</tr>
									  </thead>
									  <tbody>';

			for($i=0;$i < $ctr; $i++){


				$entry = explode('|',$group[$i]);
				//var_dump($entry); exit;

				$dept_code 		= $entry[0];
				$vessel_code 	= $entry[1];
				$contract_code 	= $entry[2];
				$gl_code 		= $entry[3];
				$amount 		= $entry[4];
				$entry_type		= $entry[5];
				$memo			= $entry[6];

				//setup id for deletion
				$id 		= $dept_code."|".$vessel_code."|".$contract_code."|".$gl_code."|".$amount."|".$entry_type."|".$memo;

				//get last 8 characters in GL code
				$gl 			= $this->Accounting_model->getAccount($gl_code);
				$new_gl 		= substr($gl['code'], 6);
				$new_code 		= $dept_code."-".$vessel_code."-".$contract_code."-".$new_gl;

				$debit_amount 	= ($entry_type == 'debit') ? $amount : 0;
				$credit_amount 	= ($entry_type == 'credit') ? $amount : 0;

				//var_dump($new_code);
				//$coa_code 		= $dept_code.'-'.$vessel_code.'-'.$contract_code;
				$htm.= "<tr>
					<td align='left'>".$new_code."</td>
					<td align='left'>".$gl['name']."</td>
					<td align='right'>".number_format($debit_amount,2)."</td>
					<td align='right'>".number_format($credit_amount,2)."</td>
					<td align='left'>".$memo."</td>
					<td align='center' >

					<a href='#' id='".$id.",' onclick='delEntry(this.id);'>x</a></td>

					</tr>
					";


			 }



			$htm.=	'						  </tbody>
									</table>';

			echo $htm;

		}
		public function add_voucher($id='')	{$data=array();



			if(isset($_POST)){

				$del_id = $this->Mmm->sanitize($_POST['del_id']);
				$request_type = $this->Mmm->sanitize($_POST['type']);
				$company_id	= $this->Mmm->sanitize($_POST['company_id']);
				$rfp_id		= $this->Mmm->sanitize($_POST['apv_no']);
				$selected_accounts = $this->Mmm->sanitize($_POST['sel_accounts']);
				$voucher_date	= date('Y-m-d H:m:s', strtotime($_POST['voucher_date']) );
				$remark			= '';

				//for additional COA code
				//$vessel_code		= (isset($_POST['vessel_code']))? $this->Mmm->sanitize($_POST['vessel_code']) : 0;
				//$contract_code		= (isset($_POST['contract_code']))? $this->Mmm->sanitize($_POST['contract_code']) : 0;
				//$department_code	= (isset($_POST['department_code']))? $this->Mmm->sanitize($_POST['department_code']) : 0;

				$insert['company_id']	=	$company_id;
				$insert['type']			=	$this->Mmm->sanitize($_POST['voucher_type']);
				$insert['bank_id']		=	$this->Mmm->sanitize($_POST['bank']); //this is the coa_id
				$insert['check_num']	=	$this->Mmm->sanitize($_POST['check_no']);
				$insert['check_date']	=	$this->Mmm->sanitize(date('Y-m-d', strtotime($_POST['check_date'])));
				$insert['voucher_number'] =	$this->Mmm->sanitize($_POST['voucher_no']);
				$insert['amount']		=	$this->Mmm->sanitize($_POST['amount']);
				$insert['wtax']			=	$this->Mmm->sanitize($_POST['wtax']);
				$insert['payee']		=	$this->Mmm->sanitize($_POST['payee']);
				$insert['payee_type']	=	$this->Mmm->sanitize($_POST['payee_type']);
				$insert['remark']		=	$this->Mmm->sanitize($_POST['remark']);
				$insert['added_by']		=	$this->Mmm->sanitize($_SESSION['abas_login']['userid']);
				$insert['voucher_date']	=	$voucher_date;
				$insert['stat']			=	1;
				$insert['status']		=	'For funding approval';
				$insert['transaction_type']	=	$this->Mmm->sanitize($_POST['type']);
				$insert['apv_no']		=	$rfp_id;  //value came from the form either apv or rfp
				$insert['created_on']	=	date('Y-m-d H:i:s');
				$insert['created_by']	=	$_SESSION['abas_login']['userid'];

				$db	=	$this->Mmm->dbInsert('ac_vouchers', $insert, "New voucher");
				//$this->Mmm->debug($db);exit;
				//var_dump($db);exit;

				if($db==true) {

					//get voucher id
					//USE for accounting entry
					$check_voucher_id		= $this->db->insert_id();
					$ref_table 				= 'ac_vouchers';
					$entry_amount 			= $this->Mmm->sanitize($_POST['amount']);
					$credit_account			= $this->Mmm->sanitize($_POST['bank']);
					$transaction_id			= $this->Mmm->sanitize($_POST['transaction_id']);
					$remark					= $this->Mmm->sanitize($_POST['remark']);


					//update source table
					$update['voucher_id'] = $check_voucher_id;
					if($request_type == 'po'){
						$debit_account = TRADE_PAYABLE; //AP trade
						$sql	=	$this->Mmm->dbUpdate('inventory_deliveries', $update, $del_id, "Update delivery with voucher id");

					}

					if($request_type == 'non-po'){
						//$debit_account = PAYABLE_OTHERS; //direct this to user selected
						//$debit_account = $this->Mmm->sanitize($_POST['debit_account']); //direct this to user selected
						//update status of delivery table

						$sql	=	$this->Mmm->dbUpdate('ac_request_payment', $update, $rfp_id, "Update ac_cash_advances with voucher id");

						//have to create new journal transaction
						$insertTran['stat']			=	1;
						$insertTran['date']			= 	$voucher_date;
						$insertTran['remark']		= 	"RFP# ".$del_id."  Particular ".$remark;
						$insertTran['company_id']	= 	$company_id;
						//var_dump($insert); exit;
						$trans					=	$this->Mmm->dbInsert("ac_transactions", $insertTran, "New transaction added");
						//get transaction id
						$sql_last = "SELECT max(id) as id FROM ac_transactions";
						$db1 = $this->db->query($sql_last);
						$last_id = $db1->result_array();

						$transaction_id = $last_id[0]['id'];

						//we need to do the multi-entries here
						$group = explode(",",$selected_accounts);
						$ctr = count($group) - 1;

						 for($i=0;$i < $ctr; $i++){

								$entry = explode('|',$group[$i]);
								$dept_code 		= $entry[0];
								$vessel_code 	= $entry[1];
								$contract_code 	= $entry[2];
								$gl_code 		= $entry[3];
								$amount 		= $entry[4];
								$entry_type		= $entry[5];
								$memo			= $entry[6];

									$debit = array();
									$debit['account'] 			= $gl_code;

									if($entry_type == 'debit'){
										$debit['debit_amount'] 		= round($amount,2);
										$debit['credit_amount'] 	= 0;
									}else{
										$debit['debit_amount'] 		= 0;
										$debit['credit_amount'] 	= round($amount,2);
									}

									$debit['company'] 			= $company_id;
									$debit['transaction_id'] 	= $transaction_id;
									$debit['reference_table'] 	= 'ac_vouchers';
									$debit['reference_id'] 		= $check_voucher_id;
									$debit['remark'] 			= $memo;
									$debit['department'] 		= $dept_code;
									$debit['vessel'] 			= $vessel_code;
									$debit['contract'] 			= $contract_code;
									$debit['posted_on']		= $voucher_date;
								//var_dump($debit); exit;
									$debit_entry = $this->Accounting_model->newJournalEntry($debit);

									if($debit_entry==FALSE){

										//NOTE: might have to remove all entries related to this transaction here
										$this->Abas->sysMsg("errmsg", "Problem occured in Debit Entry, please contact admin!");

										$this->Abas->redirect(HTTP_PATH."accounting/ap_vouchers/");
									}
						}


					}

					/*ACCOUNTING ENTRY*/

					if($entry_amount > 0){

								//This willbe for po transaction only
								if($request_type == 'po'){
									//DEBIT
									//DEBIT  Vat input
									//NOTE: the debit entry block should be transfered above to accomodate the multi accounting entry
									$debit = array();
									$debit['account'] 			= $debit_account;
									$debit['debit_amount'] 		= round($entry_amount,2);
									$debit['credit_amount'] 	= 0;
									$debit['company'] 			= $company_id;
									$debit['transaction_id'] 	= $transaction_id;
									$debit['reference_table'] 	= 'ac_vouchers';
									$debit['reference_id'] 		= $check_voucher_id;
									$debit['remark'] 			= $remark;
									$debit['department'] 		= $department_code;
									$debit['vessel'] 			= $vessel_code;
									$debit['contract'] 			= $contract_code;
									$debit['posted_on']		= $voucher_date;

									//debit
									$debit_entry = $this->Accounting_model->newJournalEntry($debit);

									if($debit_entry==FALSE){
										$this->Abas->sysMsg("errmsg", "Problem occured in Debit Entry, please contact admin!");
										$this->Abas->redirect(HTTP_PATH."accounting/ap_vouchers/");
									}
								}



									//CREDIT
									$credit = array();
									$credit['account'] 			= $credit_account;
									$credit['debit_amount'] 	= 0;
									$credit['credit_amount']	= round($entry_amount,2);
									$credit['company'] 			= $company_id;
									$credit['transaction_id'] 	= $transaction_id;
									$credit['reference_table'] 	= 'ac_vouchers';
									$credit['reference_id'] 	= $check_voucher_id;
									$credit['remark'] 			= $remark;
									$credit['department'] 		= 0;
									$credit['vessel'] 			= 0;
									$credit['contract'] 		= 0;
									$credit['posted_on']		= $voucher_date;

									$credit_entry = $this->Accounting_model->newJournalEntry($credit);

									if($credit_entry==FALSE){
										$this->Abas->sysMsg("errmsg", "Problem occured in Credit Entry, please contact admin!");
										$this->Abas->redirect(HTTP_PATH."accounting/ap_vouchers/");
									}
					/*END ACCOUNTING ENTRY*/


									if($credit_entry){

										//do some update of record here
										$updateStat['status']					=	'For releasing';
										$sql	=	$this->Mmm->dbUpdate('ac_vouchers', $updateStat, $check_voucher_id, "Voucher for releasing");

										if($request_type == 'non-po'){
											$sql	=	$this->Mmm->dbUpdate('ac_request_payment', $updateStat, $rfp_id, "RFP for releasing");
										}

										//this will be for po transactions, still need to review how to use with multi entry
										if($request_type == 'po'){
											$reconcile = $this->Accounting_model->reconcileEntries($transaction_id,$debit_account);

											if($reconcile==false){
												$this->Abas->sysMsg("errmsg", "Problem reconciling Accounts payable transaction.");
												$this->Abas->redirect(HTTP_PATH."accounting/ap_vouchers/");
												die();

											}
										}

										$this->Abas->sysMsg("sucmsg", "New Check Voucher Created!");
										$this->Abas->redirect(HTTP_PATH.'accounting/print_voucher/'.$check_voucher_id);

									}


						}
				}else{
						$this->Abas->sysMsg("errmsg", "Problem inserting voucher.");
				}

			}

			$this->Abas->redirect(HTTP_PATH.'accounting/ap_vouchers/');
		}
		public function print_voucher($id='')	{

			//var_dump($id);
			if($id!=''){

				//check if po or non-po transaction to determine type of payee
				$data['voucher'] = $this->Accounting_model->getVoucher($id);
				//get accounting entry
				$ref_table = 'ac_vouchers';
				$data['ac_entries'] = $this->Inventory_model->getAccountingEntry($id,$ref_table);
				$data['bank']	 = $this->Accounting_model->getAccount($data['voucher']['bank_id']);

				if($data['voucher']['transaction_type']=='po'){
					//payee is supplier
					$data['ttype']	= 'po';
					$data['payee']	= $this->Abas->getSupplier($data['voucher']['payee']);
					$data['payee_name'] = $data['payee']['name'];
					$data['payee_address'] = $data['payee']['address'];

					$data['company'] = $this->Abas->getCompany($data['ac_entries'][0]['company_id']);
					$data['computed_amount']['accounts_payable'] = $data['voucher']['amount'];
					$data['payable'] 	= $data['voucher']['amount'];
					//$data['computed_amount'] = $this->Abas->computePurchaseTaxes($data['voucher']['amount'],$data['payee']['id'], $data['payee']['taxation_percentile'], $data['ac_entries'][0]['company_id']);

				}

				if($data['voucher']['transaction_type']=='non-po'){
					//payee can be supplier employee or others

					$data['ttype']				= 'non-po';

					/*
					$sql = "SELECT * FROM ac_transaction_journal As j
							INNER JOIN ac_request_payment AS v
								ON j.reference_id = v.id
							WHERE transaction_id =".$data['ac_entries'][0]['transaction_id']." AND reference_table = 'ac_vouchers'";

					$db = $this->db->query($sql);
					$r	= $db->result_array();
					$ref_id = $r[0]['rfp_no'];
					*/

					$data['rfp_info']	= 	$this->Accounting_model->getRequestPayment($data['voucher']['apv_no']);
					$data['payable'] 	= $data['rfp_info'][0]['amount'];
					//var_dump($data['payable']); exit;
					//var_dump($data['rfp_info']);
					if($data['rfp_info'][0]['payee_type'] == 'Supplier'){

						$data['payee'] 	= $this->Abas->getSupplier($data['rfp_info'][0]['payee']);
						$data['payee_name'] = $data['payee']['name'];
						$data['payee_address'] = $data['payee']['address'];

						$data['computed_amount'] = $this->Abas->computeServiceTaxes($data['voucher']['amount'],$data['payee']['id'], $data['rfp_info'][0]['expanded_tax'], $data['rfp_info'][0]['company_id']);
						//var_dump($data['computed_amount']);

					}elseif($data['rfp_info'][0]['payee_type'] == 'Employee'){

						$data['payee'] 	= $this->Abas->getEmployee($data['rfp_info'][0]['payee']);
						$data['payee_name'] = $data['payee']['full_name'];
						$data['payee_address'] = $data['payee']['address'];
						$data['computed_amount']['accounts_payable'] = $data['rfp_info'][0]['amount'];

					}else{

						$data['payee']	= '';
						$data['payee_name'] = $data['rfp_info'][0]['payee_others'];
						$data['payee_address'] = '';
						$data['computed_amount']['accounts_payable'] = $data['rfp_info'][0]['amount'];
					}

					if($data['rfp_info'][0]['payee']==''){
						$data['payee']	= '';
						$data['payee_name'] = $data['rfp_info'][0]['payee_others'];
						$data['payee_address'] = '';
						$data['computed_amount']['accounts_payable'] = $data['rfp_info'][0]['amount'];
					}


					$data['company'] = $this->Abas->getCompany($data['rfp_info'][0]['company_id']);
				}

				$vid = $data['voucher']['id'];






				$this->load->view('accounting/print_voucher',$data);

			}else{
				$this->Abas->sysMsg("errmsg", "Missing parameter!");
				$this->load->view('accounting/payables_view');
			}

		}
		public function print_ap_voucher($id='')	{

			//var_dump($id);
			if($id!=''){

				$data['ap_voucher'] = $this->Accounting_model->getAPVoucher($id);

				//check to see if po or non-po and get payee name and company
				if($data['ap_voucher'][0]['po_no'] != ''){

					$supplier 	= $this->Abas->getSupplier($data['ap_voucher'][0]['payee']);
					$payment_terms = ($supplier['payment_terms']!='')?$supplier['payment_terms']:0;
					$data['payee']	= $supplier['name'];
					$data['terms']	= $payment_terms." days";
					$data['return_url']	= 'ap_clearing_view';

					$data['company'] = $this->Abas->getCompany($data['ap_voucher'][0]['company_id']);

					//get RR control number
					$po = $this->Purchasing_model->getPurchaseOrder($data['ap_voucher'][0]['po_no']);
					$data['po_control_number'] = $po['control_number'];

					//get PO control number
					$rr = $this->Inventory_model->getDelivery($data['ap_voucher'][0]['rr_no']);
					$data['rr_control_number'] = $rr[0]['control_number'];


					//$c	= $this->Accounting_model->getCompanyFromPO($data['ap_voucher'][0]['po_no']); // recheck
					//$data['company'] = $data['ap_voucher'][0]['company_id'];

				}elseif($data['ap_voucher'][0]['rfp_no'] != ''){
					//get request info
					$rp = $this->Accounting_model->getRequestPayment($data['ap_voucher'][0]['rfp_no']);
					$data['return_url']	= 'request_payment_view';
					$data['terms']	= "";
					if($rp[0]['payee_type'] == 'Supplier'){
						$p 	= $this->Abas->getSupplier($data['ap_voucher'][0]['payee']);

						$data['payee']	= $p['name'];
					}elseif($rp[0]['payee_type'] == 'Employee'){
						$p 	= $this->Abas->getEmployee($data['ap_voucher'][0]['payee']);

						$data['payee']	= $p['full_name'];
					}else{
						$data['payee']	= $rp[0]['payee_others'];
					}

					$c	= $this->Accounting_model->getRequestPayment($data['ap_voucher'][0]['rfp_no']);
					$data['company'] = $this->Abas->getCompany($c[0]['company_id']);


				}else{
					$this->Abas->sysMsg("sucmsg", "Error occured in APV printing, no reference found.");
				}

				$ref_table = 'ac_ap_vouchers';
				$data['entry'] =  $this->Inventory_model->getAccountingEntry($id,$ref_table);

				$this->load->view('accounting/print_ap_voucher',$data);

			}else{
				$this->Abas->sysMsg("sucmsg", "Missing parameter!");
				$this->load->view('accounting/ap_clearing_view');
			}

		}
		public function print_cr_voucher($id='')	{

			if($id!=''){

				$data['cash_advance'] = $this->Accounting_model->getVoucherInfo($id);

				//we assume that documents will be printed so we need to update status to 'For releasing'

					if(count($data['cash_advance']) > 0){

						$sql	=	"UPDATE ac_vouchers SET status = 'For releasing' WHERE id =".$id;
						$db = $this->Mmm->query($sql, 'Voucher ready for releasing');

						$sql	=	"UPDATE ac_cash_advances SET status = 'For releasing' WHERE voucher_id =".$id;
						$db = $this->Mmm->query($sql, 'Cash Request ready for releasing');

						$this->load->view('accounting/print_cr_voucher',$data);

					}else{

						echo 'There was an error retrieving voucher information,please contact IT administrator';
					}



			}

		}
		public function cashier_view()	{$data=array();
			//$data['suppliers'] = $this->Accounting_model->getSuppliers();
			//$data['vessels'] = $this->Abas->getVessels();
			//$data['classifications'] = $this->Accounting_model->getExpenseClassification();

			$data['vouchers'] = $this->Accounting_model->getVoucherForRelease();

			//$data['viewfile']	=	"accounting/cashier_view.php";

			$this->load->view('accounting/cashier_view.php',$data);
		}
		public function voucher_release_form($id='')	{$data=array();


			if($id!=''){
				//$data['delivery_summary']	=	$this->Inventory_model->getDelivery($id);
				$data['voucher']	=	$this->Accounting_model->getVoucherInfo($id);

				$data['delivery_summary']	=	$this->Accounting_model->getDeliveryByVoucherId($id);
				$data['delivery_detail']	=	$this->Inventory_model->getDeliveryDetails($data['delivery_summary'][0]['id']);

				$this->load->view('accounting/voucher_release_form.php',$data);
			}else{ var_dump('Error occured. No Id passed.');}

		}
		public function release_voucher()	{$data=array();

			if($_POST){
					$voucher_id = $_POST['voucher_id'];
					$or_no = $_POST['or_no'];

					$update['status']				   =   'Released';
					$update['or_no']					=   $or_no;
					$update['released_date']			=   date("Y-m-d H:i:s");
					$sql	=   $this->Mmm->dbUpdate('ac_vouchers', $update, $voucher_id, "Voucher Released");

					if($sql){

						print "<script type=\"text/javascript\">
												window.open('".HTTP_PATH."finance/print_voucher_release/".$voucher_id."');
												window.location.href = '".HTTP_PATH."'finance/accounts_view/##vouchers';
								</script>";

						$_SESSION['tab'] ='voucher';
						$this->Abas->sysMsg("sucmsg", "Voucher Released");
						$this->Abas->redirect(HTTP_PATH."finance/print_voucher_release/".$voucher_id);
					}else{
						$this->Abas->sysMsg("sucmsg", "Problem encountered.");
						$this->Abas->redirect(HTTP_PATH.'finance/accounts_view/##vouchers');
					}

			}else{ var_dump('Error occured. No Id passed.');}
		}
		public function request_payment_view()	{$data=array();

			$data['viewfile']			=	"accounting/request_payment_view.php";
			$mainview					=	"gentlella_container.php";
			$data['requestpaymentList'] = $this->Accounting_model->getRFPsForVoucher();

			$this->load->view($mainview,$data);

		}
		public function request_payment_form($id='')	{$data=array();

			if($id!=''){
				$data['request_payment'] = $this->Accounting_model->getRequestPayment($id);
			}
			$data['companies'] = $this->Abas->getCompanies();
			$data['office'] = $this->Abas->getVessels();
			$this->load->view('accounting/request_payment_form.php',$data);

		}
		public function add_request_payment($id='')	{$data=array();

			if($_POST){

					//set payee
					$payee_supplier 			= 	($_POST['payee_supplier'] != '') ? $this->Mmm->sanitize($_POST['payee_supplier']) : '';
					$payee_employee 			= 	($_POST['payee_employee'] != '') ? $this->Mmm->sanitize($_POST['payee_employee']) : '';
					$payee 						= 	$this->Mmm->sanitize($_POST['payee_id']);
					$request_date				=   date('Y-m-d', strtotime($_POST['request_date']));
					$company_id 				= 	$this->Mmm->sanitize($_POST['company']);

					//GENERATE CONTROL NUMBER
					$rfp_control_no 			= $this->Abas->getNextSerialNumber('ac_request_payment', $company_id);

					//var_dump($payee); exit;
					/*if($payee_supplier != ''){
						$payee = $payee_supplier;
					}
					if($payee_employee != ''){
						$payee = $payee_employee;
					}*/

					//insert
					$insert['reference_id']		=	$this->Mmm->sanitize($_POST['reference_no']);
					$insert['reference_table']	=	$this->Mmm->sanitize($_POST['reference_type']);

					$insert['request_date']		=	$request_date; //date('Y-m-d h:m:s');
					$insert['amount']			=	$this->Mmm->sanitize($_POST['amount']);
					$insert['type']				=	$this->Mmm->sanitize($_POST['type']);
					$insert['payee']			=	$payee;
					$insert['payee_type']		=	$this->Mmm->sanitize($_POST['payee_type']);
					$insert['payee_others']		=	$this->Mmm->sanitize($_POST['payee_others']);
					$insert['remark']			=	$this->Mmm->sanitize($_POST['remark']);
					$insert['stat']				=	1;
					$insert['status']			=	'For voucher';

					$insert['company_id']		=	$company_id;
					$insert['vessel_id']		=	$this->Mmm->sanitize($_POST['vessel_id']);
					$insert['purpose']			=	$this->Mmm->sanitize($_POST['purpose']);

					//to be removed
					$insert['added_by']			=	$this->Mmm->sanitize($_SESSION['abas_login']['userid']);
					$insert['date_added']		=	date('Y-m-d h:m:s');

					//applied for uniformity
					$insert['created_by']			=	$this->Mmm->sanitize($_SESSION['abas_login']['userid']);
					$insert['created_on']		=	date('Y-m-d h:m:s');

					$insert['expanded_tax']		=	$this->Mmm->sanitize($_POST['etax']);

					$insert['control_number']	=	$rfp_control_no;

					$db	=	$this->Mmm->dbInsert('ac_request_payment', $insert, "New RFP added");

					if($db){
						$this->Abas->sysMsg("sucmsg", "New RFP added.!");

					}

			}else{

				$this->Abas->sysMsg("sucmsg", "Problem occured in submission.!");

			}

			$this->Abas->redirect(HTTP_PATH."accounting/request_payment_view/");


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
						$data	=	$this->Abas->createBSTable("ac_request_payment",$search,$limit,$offset,$order,$sort);
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
					$data['viewfile']	=	"accounting/request_for_payment/listview.php";
					$this->load->view("gentlella_container.php",$data);
				break;
				case "add":
					$data['vessels']		=	$this->Abas->getVessels();
					$data['companies']		=	$this->Abas->getCompanies();
					$this->load->view('accounting/request_for_payment/form.php',$data);
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
								$details_amount = $this->Mmm->dbUpdate("ac_request_payment", $update,$request_for_payment_id,'Update Amount of with RFP Transaction Code No. '.$request_for_payment_id);

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

				case "edit_amount":
					$data['request_detail'] = $this->Accounting_model->getRequestPaymentDetail($id);
					$this->load->view("accounting/request_for_payment/edit_amount.php",$data);
				break;

				case "save_amount":
					if($_POST['rfp_detail_amount']){
						$amount = $this->Mmm->sanitize($_POST['rfp_detail_amount']);
						$wtax = $this->Mmm->sanitize($_POST['rfp_wtax']);
						$wtax_amount = $this->Mmm->sanitize($_POST['rfp_wtax_amount']);
						$input_tax = $this->Mmm->sanitize($_POST['rfp_input_tax_amount']);
						$vat = $this->Mmm->sanitize($_POST['rfp_vat_amount']);
						$query = $this->Mmm->query('UPDATE ac_request_payment_details SET wtax="'.$wtax.'",wtax_amount='.$wtax_amount.', vat_amount='.$vat.', input_tax_amount='.$input_tax.', amount = '.$amount. ' WHERE id='.$id,"Edited amount of RFP with TScode No.".$id);
						if($query){
							$request_detail = $this->Accounting_model->getRequestPaymentDetail($id);
							$request = $this->Accounting_model->getRequestPayment($request_detail->request_payment_id);
							$request_Details = $this->Accounting_model->getRequestPaymentDetails($request[0]['id']);
							$total_amount = 0;
							foreach($request_Details as $row){
								$total_amount = $total_amount + $row['amount'];
							}
							$query = $this->Mmm->query('UPDATE ac_request_payment SET amount = '.$total_amount. ' WHERE id='.$request[0]['id'],"Edited amount of RFP with TScode No.".$id);
							$this->Abas->sysMsg("sucmsg", "Succesfully edited amount by ".$_SESSION['abas_login']['fullname'].".");
						}else{
							$this->Abas->sysMsg("errmsg", "An error ocurred while saving the record. Please contact your administrator.");
						}
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
					$data['viewfile']	=	"accounting/request_for_payment/view.php";
					$this->load->view("gentlella_container.php",$data);
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
					$this->load->view('accounting/request_for_payment/print.php',$data);
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
		public function payables() {
			$data['voucher_deliveries'] = $this->Accounting_model->getDeliveriesForVoucher();
			$data['requestpaymentList'] = $this->Accounting_model->getRFPsForVoucher();
			$data['services'] = '';
			$data['cash_advance'] = $this->Finance_model->getCashRequest_ForVoucher();
			$data['viewfile']			=	"accounting/payables.php";
			$mainview					=	"gentlella_container.php";

			$this->load->view($mainview,$data);
		}
		public function payables_view()	{$data=array();
			if(isset($_SESSION['tab'])){
			$data['tab']= $_SESSION['tab'];
			}else{
				$data['tab']= 'request';
			}
			$data['voucher_deliveries'] = $this->Accounting_model->getDeliveriesForVoucher();
			$data['requestpaymentList'] = $this->Accounting_model->getRFPsForVoucher();
			$data['services'] = '';
			$data['cash_advance'] = $this->Finance_model->getCashRequest_ForVoucher();
			$this->load->view('accounting/payables_view.php',$data);
		}
		public function ap_clearing_view()	{$data=array();
			if(isset($_SESSION['tab'])){
				$data['tab']= $_SESSION['tab'];
			}else{
				$data['tab']= 'request';
			}
			$data['voucher_deliveries'] = $this->Inventory_model->getAPForClearing();

			$data['viewfile']			=	"accounting/ap_clearing_view.php";
			$mainview			 		=	"gentlella_container.php";
			$this->load->view($mainview, $data);
		}
		public function ap_clearing_form($id='',$type=''){$data=array();

				if($type=='po'){
					$data['type']	= 'po';
					$data['ap'] = $this->Accounting_model->getAPClearingInfo($id);

					//need to get PO info here

					$delivery 	= $this->Inventory_model->getDelivery($data['ap'][0]['reference_id']);
					$data['po_info'] 	= $this->Purchasing_model->getPurchaseOrder($delivery[0]['po_no']);

					//check if this has RFP
					if($data['po_info']['payment_terms'] != NULL || $data['po_info']['payment_terms'] != 'Termed Payment'){
						//var_dump($data['po_info']['payment_terms']); exit;
						//get rfp by po id
						$sql = "SELECt * FROM ac_request_payment WHERE reference_id =".$data['po_info']['id'];

					}

					//$data['delivery'] = $this->Accounting_model->getAPClearingInfo($id);
					$ref_table = 'inventory_deliveries';
					$data['ac_entries'] = $this->Inventory_model->getAccountingEntry($data['ap'][0]['reference_id'],$ref_table);

				}elseif($type=='non-po'){
					$data['type']	= 'non-po';
					$data['ap'] = $this->Accounting_model->getRfp_ForVoucher($id);

				}
				//$delivery= $this->db->query("SELECT * from inventory_deliveries WHERE id=".$id);
				//$delivery=(array)$delivery->row();

				$this->load->view('accounting/ap_clearing_form',$data);

		}
		public function ap_clear()	{$data=array();


			if($_POST){

				$posted = 0; //use this as indicator in ap voucher to make sure if the entries successfully posted
							// Need to develop mechanism to handle this issue (if something went wrong there should be a way to trace)
				//set references
				$reference_id 	= $_POST['reference_id'];
				$transaction_id = $_POST['transaction_id'];
				$journal_id 	= $_POST['journal_id'];
				$ttype 			= $_POST['ttype'];

				$apv_date		= (isset($_POST['apv_date'])) ? date('Y-m-d h:m:s',strtotime($_POST['apv_date'])) : date('Y-m-d h:m:s');



				if($ttype == 'po'){

					//redirect url
					$url = '/ap_clearing_view';
					//set references
					$reference_id = $_POST['reference_id'];
					$transaction_id = $_POST['transaction_id'];
					$journal_id = $_POST['journal_id'];


					$doc_rr = (isset($_POST['doc_rrv'])) ? $_POST['doc_rrv'] : 0;
					$doc_dr = (isset($_POST['doc_drv'])) ? $_POST['doc_drv'] : 0;
					$doc_po = (isset($_POST['doc_pov'])) ? $_POST['doc_pov'] : 0;

					$is_cleared = 0;
					$apv = (isset($_POST['apv'])) ? $_POST['apv'] : '';

					$values = array($doc_rr, $doc_dr, $doc_po);

					if(count(array_unique($values)) === 1) {

						//note: do not process/print ap_voucher if the documents are lacking 3/9/2017

						$update['doc_rr']	=	$doc_rr;
						$update['doc_dr']	=	$doc_dr;
						$update['doc_po']	=	$doc_po;
						$update['is_cleared']	=	1;

						$sql	=	$this->Mmm->dbUpdate('inventory_deliveries', $update, $reference_id, "Update AP clearing");

						if($sql==FALSE) {

							$this->Abas->sysMsg("warnmsg", "Problem updating delivery status, please report to your administrator!");

						}else{

							$this->Abas->sysMsg("sucmsg", "AP Cleared!");
							$deliveryInfo = $this->Inventory_model->getDelivery($reference_id);
							$po_info = $this->Inventory_model->getPO($deliveryInfo[0]['po_no']);
							$company_id = $po_info[0]['company_id'];
							$remark = $po_info[0]['remark'];
							$amount = $deliveryInfo[0]['amount'];
							$apv_control_no = $this->Abas->getNextSerialNumber('ac_ap_vouchers', $company_id);

							$insert['payee'] 		= $deliveryInfo[0]['supplier_id'];
							$insert['po_no'] 		= $deliveryInfo[0]['po_no'];
							$insert['invoice_no'] 	= $deliveryInfo[0]['sales_invoice_no'];
							$insert['rr_no'] 		= $deliveryInfo[0]['id'];
							$insert['control_number']= $apv_control_no;
							$insert['company_id']	= $company_id;



							//get company and remark from po
							$computed_amount = $this->Abas->computePurchaseTaxes($amount,$deliveryInfo[0]['supplier_id'], $po_info[0]['extended_tax'], $company_id);

							if($computed_amount == FALSE){
								$this->Abas->sysMsg("warnmsg", "Problem occurred in computation, please report to IT admin!");
								die();
							}
							//var_dump($po_info[0]['extended_tax']);

							//var_dump($computed_amount); exit;
						}
					}
				//END PO
				}


				if($ttype =='non-po'){

							$url = '/request_payment_view';
							$requestInfo 	= $this->Accounting_model->getRequestPayment($reference_id);
							$computed_amount = $requestInfo[0]['amount'];
							$company_id = $requestInfo[0]['company_id'];
							$remark = $requestInfo[0]['purpose'];
							$rfp_id = $requestInfo[0]['id'];

							$insert['payee'] = $requestInfo[0]['payee'];
							$insert['rfp_no'] = $requestInfo[0]['id'];

				}
				//END NON-PO

				 			//addnew ap vuocher
							$insert['date_created'] = $apv_date;
							$insert['created_on'] = date('Y-m-d H:i:s');
							$insert['created_by'] =	$_SESSION['abas_login']['userid'];
							$sql	=	$this->Mmm->dbInsert('ac_ap_vouchers', $insert, "New AP Voucher created.");

							if($sql == TRUE){

								//get last id
								$sql_last = "SELECT max(id) as id FROM ac_ap_vouchers";
								$db1 = $this->db->query($sql_last);
								$last_id = $db1->result_array();
								$lastid = $last_id[0]['id'];

								/*###########################################################################################################*/
										//ACCOUNTING ENTRY
										// Account Payable Trade ID : 104
										// AP CLearing ID : 283  AP clearing account
										// Transaction Type: Purchasing
										// what to put in memo?
										// 109: VAT Payable

										//define("TRADE_PAYABLE",	71);
										//define("AP_CLEARING",	291);
										//define("INPUT_TAX",	27);//VAT input
										//define("WITHOLDING_TAX_EXPANDED",	76);
										//define("MATERIALS_AND_SUPPLIES",	29);
								/*#########################################################################################################*/

								/*
								 * entry	-	array('debit_amount'=>double, 'credit_amount'=>double, 'account'=>int, 'department'=>int, 'vessel'=>int, 'contract'=>int)
								 * details	-	array('company'=>int, 'transaction_id'=>int, 'reference_table'=>varchar, 'reference_id'=>int, 'remark'=>text)
								 *
								 */
								if($ttype =='po'){

										if($computed_amount['gross_purchases'] > 0){

											//make sure references is exisiting
											if(($transaction_id) == '' || $lastid == ''){
												$this->Abas->sysMsg("errmsg", "Missing transaction references, please contact admin!");
												$this->Abas->redirect(HTTP_PATH.'accounting'.$url);

											}

											//DEBIT  AP Clearing
											$apc_debit = array();
											$apc_debit['account'] 			= AP_CLEARING; //AP-Clearing after vat
											$apc_debit['debit_amount'] 		= round($computed_amount['vatable_purchases'],2);
											$apc_debit['credit_amount'] 	= 0;
											$apc_debit['company'] 			= $company_id;
											$apc_debit['transaction_id'] 	= $transaction_id;
											$apc_debit['reference_table'] 	= 'ac_ap_vouchers';
											$apc_debit['reference_id'] 		= $lastid;
											$apc_debit['remark'] 			= $remark;
											$apc_debit['department'] 		= 0;
											$apc_debit['vessel'] 			= 0;
											$apc_debit['contract'] 			= 0;
											$apc_debit['posted_on']			= $apv_date;
											//var_dump($apc_debit); exit;


											//CREDIT Accounts Payable
											//$credit_amt = $computed_amount['gross_purchases'] - $computed_amount['withholding_tax_expanded'];

											$apc_credit = array();
											$apc_credit['account'] 			= TRADE_PAYABLE; //AP-Trade , after etax
											$apc_credit['credit_amount']	= round($computed_amount['accounts_payable'],2);
											$apc_credit['debit_amount']		= 0;
											$apc_credit['company'] 			= $company_id;
											$apc_credit['transaction_id'] 	= $transaction_id;
											$apc_credit['reference_table'] 	= 'ac_ap_vouchers';
											$apc_credit['reference_id'] 	= $lastid;
											$apc_credit['remark'] 			= $remark;
											$apc_credit['department'] 		= 0;
											$apc_credit['vessel'] 			= 0;
											$apc_credit['contract'] 		= 0;
											$apc_credit['posted_on']		= $apv_date;
											//DEBIT  Vat input
											$vat_debit = array();
											$vat_debit['account'] 			= INPUT_TAX; //VAT Payable
											$vat_debit['debit_amount'] 		= round($computed_amount['vat'],2);
											$vat_debit['credit_amount'] 	= 0;
											$vat_debit['company'] 			= $company_id;
											$vat_debit['transaction_id'] 	= $transaction_id;
											$vat_debit['reference_table'] 	= 'ac_ap_vouchers';
											$vat_debit['reference_id'] 		= $lastid;
											$vat_debit['remark'] 			= $remark;
											$vat_debit['department'] 		= 0;
											$vat_debit['vessel'] 			= 0;
											$vat_debit['contract'] 			= 0;
											$vat_debit['posted_on']			= $apv_date;
											//CREDIT EWtax

											/*
											#############################################################
												NOTE: THIS IS ONLY A QUICKFIX NEED TO APPLY DYNAMIC FIX
												TO APPLY EXPANDED TAX ONLY TO TOP 20000 COMPANIES
											#############################################################
												when company table is ready put verification here
											*/
											$company_top_20000 = $this->Abas->isCompanyTop20000($company_id);

											if($company_top_20000==TRUE){
												$etax_credit = array();
												$etax_credit['account'] 		= WITHOLDING_TAX_EXPANDED; //Witholding Tax - Expanded
												$etax_credit['debit_amount'] 	= 0;
												$etax_credit['credit_amount'] 	= round($computed_amount['withholding_tax_expanded'],2);
												$etax_credit['company'] 		= $company_id;
												$etax_credit['transaction_id'] 	= $transaction_id;
												$etax_credit['reference_table'] = 'ac_ap_vouchers';
												$etax_credit['reference_id'] 	= $lastid;
												$etax_credit['remark'] 			= $remark;
												$etax_credit['department'] 		= 0;
												$etax_credit['vessel'] 			= 0;
												$etax_credit['contract'] 		= 0;
												$etax_credit['posted_on']		= $apv_date;

											}


											//do some validation before posting
											//check the following before initiate recording

											//ap clearing debit (need to recheck validation here, make sure no error will occur)

											/* DEPRECIATED
											if($apc_debit['debit_amount'] == ''){
												$this->Abas->sysMsg("errmsg", "missing debit amount, please contact admin!");
												$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
											}

											//ap credit
											if($ap_credit_amount['credit'] == ''){
												$this->Abas->sysMsg("errmsg", "missing credit amount, please contact admin!");
												$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
											}
												*/
											//vat debit need to add validation depending on supplier's vat computation
											/*
											if($vat_debit_amount['debit'] == ''){
												$this->Abas->sysMsg("sucmsg", "missing vat debit amount, please contact admin!");
												$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
											}

											//etax credit - need to add validation depending on supplier's ewtax
											if($etax_credit_amount['credit'] == ''){
												$this->Abas->sysMsg("sucmsg", "Missing etax credit amount, please contact admin!");
												//$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
											}*/



											//ap clearing
											$ap_clearing_entry = $this->Accounting_model->newJournalEntry($apc_debit);
											if($ap_clearing_entry === FALSE){
												$this->Abas->sysMsg("errmsg", "Error in accounting clearing entry, please report to IT admin.");												$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
											}

											//ap
											$ap_entry = $this->Accounting_model->newJournalEntry($apc_credit);
											if($ap_entry === FALSE){
												$this->Abas->sysMsg("errmsg", "Error in accounting AP entry, please report to IT admin.");
												$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
											}

											//vat
											$vat_entry = $this->Accounting_model->newJournalEntry($vat_debit);
											if($vat_entry === FALSE){
												$this->Abas->sysMsg("errmsg", "Error in accounting VAT entry, please report to IT admin.");
												$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
											}

											//tax
											$company_top_20000 = $this->Abas->isCompanyTop20000($company_id);

											if($company_top_20000==TRUE){
												$etax_entry = $this->Accounting_model->newJournalEntry($etax_credit);
												if($etax_entry === FALSE){
													$this->Abas->sysMsg("errmsg", "Error in accounting ETAX entry, please report to IT admin.");
													$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
												}
											}

												//NOte:  need to reconcile AP clearing entry
												$reconcile = $this->Accounting_model->reconcileEntries($transaction_id,AP_CLEARING);
												//var_dump($reconcile); exit;

												if($reconcile==FALSE){
													$this->Abas->sysMsg("errmsg", "Problem reconciling AP clearing entry.");
												}else{

															//get last id of the entry
															//NOTE: not sure if this is needed
															$s = "SELECT max(id) as last_id FROM ac_transaction_journal WHERE coa_id = ".AP_CLEARING;
															$db = $this->db->query($s);
															$res = $db->result_array();

															//update ap_voucher table with journal_id
															$upd['journal_id']	 = $res[0]['last_id'];
															$up = $this->Mmm->dbUpdate('ac_ap_vouchers', $upd, $lastid, "AP status updated.");

															if($up == TRUE){
																$this->Abas->redirect(HTTP_PATH."accounting/print_ap_voucher/".$lastid);
															}else{
																$this->Abas->sysMsg("errmsg", "Problem updating ap_voucher table.");
															}




												}
										}
								} //end po accounting entry

								//non-po accounting entry  RFP
								/////////////////////////////////////////////////////////////////////////////////
								//NOTE: CODES FOR NON-PO TRANSACTION IS DEPRECIATED HERE CONSIDER REMOVING CODE//
								/////////////////////////////////////////////////////////////////////////////////
								if($ttype =='non-po'){

									//create transaction before doing accounting entries
									$insert2['stat']			=	1;
									$insert2['date']			= $apv_date;
									$insert2['company_id']		= $requestInfo[0]['company_id'];
									$insert2['remark']			= $requestInfo[0]['purpose']." RFP#".$requestInfo[0]['id'];

									$ins	=	$this->Mmm->dbInsert('ac_transactions', $insert2, "New transaction added.");

									// get last id
									$s = "SELECT max(id) as tid FROM ac_transactions";
									$res = $this->db->query($s);
									$r = $res->result_array();
									$transaction_id = $r[0]['tid'];

									//update rfp record
									$sq = "UPDATE ac_request_payment SET transaction_id =".$transaction_id.", status = 'For voucher approval' WHERE id =".$rfp_id;
									$up = $this->db->query($sq);

									if($ins==FALSE){
										$this->Abas->sysMsg("errmsg", "Problem creating new transaction (line#1118), please report to administrator.");
										$this->Abas->redirect(HTTP_PATH."accounting/".$url);
										die();
									}

									if($computed_amount > 0){

												//Debit
												$ap_debit_amount = array();
												$ap_debit_account = $_POST['debit'];
												$ap_debit_amount['debit'] = $computed_amount;
												$ap_debit_amount['credit'] = 0;

												//CREDIT
												$ap_credit_account = $_POST['credit'];
												$ap_credit_amount = array();
												$ap_credit_amount['debit'] = 0;
												$ap_credit_amount['credit'] = $computed_amount;

												$details = array();
												$transaction_type = 'ac_request_payment';

												$details['transaction_id'] = $transaction_id;
												$details['reference_id'] = $lastid; //last id of ap voucher
												$details['reference_table'] = 'ac_ap_vouchers';
												$details['status'] = 'open';
												$details['remark'] = $remark;
												$details['company'] = $company_id;

												//do validation
												if($ap_debit_amount['debit'] == ''){
													$this->Abas->sysMsg("errmsg", "missing debit amount, please contact admin!");
													$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
												}
												if($ap_debit_account == ''){
													$this->Abas->sysMsg("errmsg", "missing debit account, please contact admin!");
													$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
												}
												if($ap_credit_amount['credit'] == ''){
													$this->Abas->sysMsg("errmsg", "missing credit amount, please contact admin!");
													$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
												}
												if($ap_credit_account == ''){
													$this->Abas->sysMsg("errmsg", "missing credit account, please contact admin!");
													$this->Abas->redirect(HTTP_PATH.'accounting'.$url);
												}

												if(($details['transaction_id']) == '' || $details['reference_id'] == '' || $details['reference_table'] == '' ){
													$this->Abas->sysMsg("errmsg", "System was not able to create transaction (line#1105), please contact admin!");
													$this->Abas->redirect(HTTP_PATH.'accounting'.$url);

												}

												//post entries
												$ap_clearing_entry = $this->Accounting_model->newJournalEntry($ap_debit_amount, $ap_debit_account, $details);

												$ap_entry = $this->Accounting_model->newJournalEntry($ap_credit_amount, $ap_credit_account, $details);

										}

								}






							}else{//ap voucher insert
									$this->Abas->sysMsg("errmsg", "Problem occured adding ap voucher.");
							}

				}

			$this->Abas->redirect(HTTP_PATH.'accounting'.$url);

		}
		public function bank_data(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM ac_banks WHERE name LIKE '%".$search."%' ORDER BY name LIMIT 0, 10";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						// $ret['id']	=	$i['id'];
						$ret[$ctr]['label']	=	$i['name'];
						$ret[$ctr]['value']	=	$i['id'];
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
			$sql	=	"SELECT * FROM suppliers WHERE name LIKE '%".$search."%' ORDER BY name LIMIT 0, 10";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						// $ret['id']	=	$i['id'];
						$ret[$ctr]['label']	=	$i['name'];
						$ret[$ctr]['value']	=	$i['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function expense_entry()	{$data=array();
			$data['suppliers'] = $this->Accounting_model->getSuppliers();
			$data['vessels'] = $this->Abas->getVessels();
			$data['classifications'] = $this->Accounting_model->getExpenseClassification();
			$data['expenses'] = $this->Accounting_model->getAllExpenses();
			$data['viewfile']	=	"accounting/account_form.php";

			$this->load->view('container.php',$data);
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
			else {
				// $_SESSION['errmsg']	=	"An error has occurred! <pre>Error ". __class__ .":". __function__ .":". __line__ ."</pre>";
			}
		}
		public function viewExpense($id) {$data=array();

			if(isset($id)){
				$data['viewExpense'] = 	$this->Accounting_model->getExpense($id);
			}
			$data['suppliers'] = $this->Accounting_model->getSuppliers();
			$data['vessels'] = $this->Abas->getVessels();
			$data['classifications'] = $this->Accounting_model->getExpenseClassification();
			$data['expenses'] = $this->Accounting_model->getAllExpenses();
			$data['viewfile']	=	"accounting/account_form.php";

			$this->load->view('container.php',$data);
		}
		public function expense_report_form() {$data=array();
			// echo '<div style="float:right">&nbsp;</div>';
			$data['vessels'] = $this->Abas->getVessels();
			$data['classifications'] = $this->Accounting_model->getExpenseClassification();
			$data['viewfile']	=	"accounting/expense_report_form.php";

			$this->load->view('container-noheader.php',$data);
		}
		public function expense_report() {$data=array();
			$vid = $_POST['vessel'];
			$type = $_POST['include_on'];
			$class = $_POST['classification'];
			$from_date = $_POST['from_date'];
			$to_date = $_POST['to_date'];

			$data['ex_report'] = $this->Accounting_model->getExpenseReport($vid,$from_date,$to_date,$class,$type);
			$data['viewfile']	=	"accounting/expense_report.php";

			$this->load->view('container.php',$data);
		}
		public function addExpense() {$data=array();
			if(isset($_POST)){
				$eid = $this->Mmm->sanitize($_POST['id']);
				$voucher_no = $this->Mmm->sanitize($_POST['voucher_no']);
				$voucher_date = $this->Mmm->sanitize($_POST['voucher_date']);
				$payee = $this->Mmm->sanitize($_POST['payee']);
				$particulars = $this->Mmm->sanitize($_POST['particular']);
				$amount = $this->Mmm->sanitize($_POST['amount']);
				$reference_no = $this->Mmm->sanitize($_POST['reference_no']);
				$vessel = $this->Mmm->sanitize($_POST['vessel']);
				$include_on = $this->Mmm->sanitize($_POST['include_on']);
				$classification = $this->Mmm->sanitize($_POST['classification']);
				//check if add or edit
				if($eid !== ''){
					//edit
					// $sql = 'UPDATE vessel_expenses
					// SET check_voucher_date = "'.$voucher_date.'",
					// check_voucher_no = "'.$voucher_no.'",
					// amount_in_php = "'.$amount.'",
					// reference_no = "'.$reference_no.'",
					// particulars = "'.$particulars.'",
					// vessel_id = '.$vessel.',
					// expense_classification_id = '.$classification.',
					// include_on = "'.$include_on.'",
					// account_id = '.$payee.'
					// WHERE id = '.$eid;
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
					//$add = $this->db->query($sql);
				}
				else {
					//add
					// $sql = 'INSERT INTO vessel_expenses(id, check_voucher_date, check_voucher_no, amount_in_php, reference_no, particulars, vessel_id, expense_classification_id, include_on, account_id) VALUES(0,"'.$voucher_date.'","'.$voucher_no.'",'.$amount.',"'.$reference_no.'","'.$particulars.'",'.$vessel.','.$classification.',"'.$include_on.'",'.$payee.')';
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
					//var_dump($sql); exit;
					//$add = $this->db->query($sql);
				}

				// $add = $this->db->query($sql);

				$this->Abas->reidrect(HTTP_PATH.'accounting');

			}
			else {
				// echo "<div>Error Encountered, please contact administrator.</div>";
				$this->Abas->sysMsg("errmsg", "Error Encountered, please contact administrator.");
			}
		}
		public function accounting_view() {
			$data = array();

			if (isset($_SESSION['tab'])) {
				$data['tab'] = $_SESSION['tab'];
			} else {
				$data['tab'] = 'request';
			}

			$data['bank_accounts']	 = $this->Abas->getBanks();
			$data['cash_advance']	  = $this->Finance_model->getCashAdvances();
			$data['supplier_accounts'] = $this->Abas->getSuppliers();
			$data['service_provider']  = $this->Abas->getServiceproviders();
			$data['vesselList'] = $this->Abas->getVessels();
			$data['truckList'] = $this->Abas->getTrucks();
			$data['craneList'] = $this->Abas->getCranes();
			$data['clientList'] = $this->Abas->getClients();
			$data['companyList'] = $this->Abas->getCompanies();
			$data['requestpaymentList'] = $this->Abas->getRequestPayments();
			$this->load->view('accounting/accounting_view.php', $data);

		}

		//START KENNETH
		public function accounts_classification()
		{
			$data['accounts'] = $this->Abas->getItems('ac_accounts_classification');

			$data['viewfile'] =	"accounting/accounts_classification.php";
			$mainview =	"gentlella_container.php";
			$this->load->view($mainview,$data);
		}

		public function accounts_classification_form()
		{
			$mainview = 'accounting/accounts_classification_form';
			$this->load->view($mainview);
		}
		//END KENNETH

		public function chart_of_accounts($action="",$id="") 
		{
			$data=array();
			$this->Abas->checkPermissions("accounting|view_chart_of_accounts");
			$data['viewfile']	=	"accounting/chart_of_accounts.php";
			// $data['viewfile']	=	"echo.php";
			$mainview			=	"gentlella_container.php";
			if($id!="" && !is_numeric($id)) {
				$this->Abas->sysMsg("errmsg", "Invalid ID!");
				$this->Abas->redirect(HTTP_PATH."accounting");
			}
			elseif($id=="") {
				if($action=="add") {
					$this->Abas->checkPermissions("accounting|edit_chart_of_accounts");
					$mainview	=	"accounting/chart_of_accounts_form.php";
				}
				elseif($action=="insert") {
					$this->Abas->checkPermissions("accounting|edit_chart_of_accounts");
					if(empty($_POST)) {
						$this->Mmm->sysMsg("errmsg", "No input found, please try again.");
						$this->Abas->redirect(HTTP_PATH."accounting");
					}
					$ins['general_ledger_code']			=	$this->Mmm->sanitize($_POST['general_ledger_code']);
					$ins['financial_statement_code']	=	$this->Mmm->sanitize($_POST['financial_statement_code']);
					$ins['code']						=	str_pad($ins['financial_statement_code'].$ins['general_ledger_code'], 22, '0', STR_PAD_LEFT);
					$ins['name']						=	$this->Mmm->sanitize($_POST['name']);
					$ins['description']					=	$this->Mmm->sanitize($_POST['description']);
					$ins['classification']				=	$this->Mmm->sanitize($_POST['classification']);
					$ins['type']						=	$this->Mmm->sanitize($_POST['type']);
					$checkname	=	$this->db->query("SELECT * FROM ac_accounts WHERE name='".$ins['name']."'");
					if($checkname) {
						if($checkname->row()) {
							$this->Abas->sysMsg("errmsg", "That account name already exists. Please choose another and try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/". __function__);
						}
					}
					$checkcode	=	$this->db->query("SELECT * FROM ac_accounts WHERE financial_statement_code='".$ins['code']."' AND general_ledger_code='".$ins['general_ledger_code']."'");
					if($checkcode) {
						if($checkcode->row()) {
							$this->Abas->sysMsg("errmsg", "That account code already exists. Please choose another and try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/". __function__);
						}
					}
					$check	=	$this->Mmm->dbInsert("ac_accounts", $ins, "New account in chart of accounts");
					if($check) {
						$this->Abas->sysMsg("sucmsg", $ins['name']." added to the chart of accounts!");
						$this->Abas->sysNotif("Chart of accounts edited", $_SESSION['abas_login']['fullname']." has added the account ".$ins['name']."(".$ins['code'].")", "everyone", "info");
					}
					else {
						$this->Abas->sysMsg("errmsg", "Account not added, please try again.");
					}
					$this->Abas->redirect(HTTP_PATH."accounting/". __function__);
				}
				elseif($action=="csv") {
					$headers = array("name", "general_ledger_code", "financial_statement_code", "");
					$table	=	'ac_accounts';
					$this->Mmm->tableToCSV($table,$headers);
				}
			}
			elseif(is_numeric($id)) {
				$account			=	$this->Accounting_model->getAccount($id);
				if($account) {
					if($action=="edit") {
						$data['account']	=	$account;
						$mainview	=	"accounting/chart_of_accounts_form.php";
					}
					elseif($action=="update") {
						$this->Mmm->debug($_POST);
						$update['general_ledger_code']		=	$this->Mmm->sanitize($_POST['general_ledger_code']);
						$update['financial_statement_code']	=	$this->Mmm->sanitize($_POST['financial_statement_code']);
						$update['code']						=	str_pad($update['general_ledger_code'].$update['financial_statement_code'], 22, '0', STR_PAD_LEFT);
						$update['name']						=	$this->Mmm->sanitize($_POST['name']);
						$update['description']				=	$this->Mmm->sanitize($_POST['description']);
						$update['classification']			=	$this->Mmm->sanitize($_POST['classification']);
						$update['type']						=	$this->Mmm->sanitize($_POST['type']);
						$update['sub_type']					=	$this->Mmm->sanitize($_POST['sub_type']);
						$check								=	$this->Mmm->dbUpdate("ac_accounts", $update, $id, "Update Chart of Accounts");
						if($check) {
							$this->Abas->sysNotif("Chart of accounts edited", $_SESSION['abas_login']['fullname']." has edited account number ".$update['code']." (".$update['name'].")", "everyone", "info");
							$this->Abas->sysMsg("sucmsg", "Successfully updated ".$account['name']." account!");
						}
						else {
							$this->Abas->sysMsg("errmsg", "An error has ocurred in updating ".$account['name']." account!");
						}
						$this->Abas->redirect(HTTP_PATH."accounting/chart_of_accounts");
					}
					elseif($action=="delete") {

					}
					elseif($action=="view_entries") {
						if(!empty($_GET['order']) || !empty($_GET['limit']) || !empty($_GET['offset'])) { // get output for bootstrap table
							$limit			=	isset($_GET['limit'])?$_GET['limit']:"";
							$offset			=	isset($_GET['offset'])?$_GET['offset']:"";
							$order			=	isset($_GET['order'])?$_GET['order']:"";
							$sort			=	isset($_GET['sort'])?$_GET['sort']:"";
							$searchstring	=	isset($_GET['search'])?$_GET['search']:"";
							$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='ac_transaction_journal' AND TABLE_SCHEMA='".DBNAME."'");
							$tablefields			=	$tablefields->result();
							if($limit!="") {
								if(is_numeric($limit)) {
									$limit	=	", ".$limit;
								}
							}
							if($offset!="") {
								if(is_numeric($offset)) {
									$offset	=	" LIMIT ".$offset;
								}
							}
							if($order!="") {
								if(strtolower($order)==='asc' || strtolower($order)==='desc') {
									$order	=	" ORDER BY ".($sort!=""?$sort:"id")." ".$order;
								}
							}
							if($searchstring!="") {
								$searchfields	=	"";
								foreach($tablefields as $tf) {
									if($searchfields!="") $searchfields.="OR ";
									$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' ";
								}
							}
							else {
								$searchfields	=	"1=1 ";
							}
							$sql	=	"SELECT * FROM ac_transaction_journal WHERE coa_id=".$id." AND (".$searchfields.")".$order.$offset.$limit;
							$total	=	"SELECT id FROM ac_transaction_journal WHERE coa_id=".$id." AND (".$searchfields.")";
							$all	=	$this->db->query($sql);
							$total	=	$this->db->query($total);
							if($all) {
								$data	=	array("total"=>count($total->result_array()),"rows"=>$all->result_array());
							}
							else {
								$data	=	false;
							}
							if($data!=false) {
								foreach($data['rows'] as $ctr=>$entry) {
									$data['rows'][$ctr]['account_code']		=	"-"; // taken from ac_accounts using ac_transaction_journal.coa_id
									$data['rows'][$ctr]['account_name']		=	"-"; // taken from ac_accounts using ac_transaction_journal.coa_id
									$data['rows'][$ctr]['poster_name']		=	"-"; // taken from users using ac_transaction_journal.posted_by
									$data['rows'][$ctr]['checker_name']		=	"-"; // taken from users using ac_transaction_journal.checked_by
									$data['rows'][$ctr]['company_name']		=	"-"; // taken from users using ac_transaction_journal.company_id
									if(is_numeric($entry['coa_id'])) {
										$coa	=	$this->Accounting_model->getJournalEntry($entry['id']);
										if($coa) {
											$data['rows'][$ctr]['account_code']	=	$coa['account_code'];
											$data['rows'][$ctr]['account_name']	=	$coa['account_name'];
										}
									}
									if(is_numeric($entry['department_id'])) {
										$department	=	$this->Abas->getDepartment($entry['department_id']);
										if($department) {
											$data['rows'][$ctr]['department_name']	=	$department->name;
										}
									}
									if(is_numeric($entry['company_id'])) {
										$company	=	$this->Abas->getCompany($entry['company_id']);
										if(!empty($company)) {
											$data['rows'][$ctr]['company_name']	=	$company->name;
										}
									}
									if(!empty($entry['created_on'])) {
										$data['rows'][$ctr]['created_on']	=	($entry['created_on']=="0000-00-00 00:00:00") ? "" : date("j F Y H:i:s", strtotime($entry['created_on']));
									}
									if(!empty($entry['date_checked'])) {
										$data['rows'][$ctr]['date_checked']	=	($entry['date_checked']=="0000-00-00 00:00:00") ? "" : date("j F Y H:i:s", strtotime($entry['date_checked']));
									}
								}
								header('Content-Type: application/json');
								echo json_encode($data);
								die();
							}
						}
						$data['viewfile']			=	"accounting/journal.php";
						$mainview					=	"gentlella_container.php";
					}
					else {
						$this->Abas->sysMsg("errmsg", "Invalid Action!");
						$this->Abas->redirect(HTTP_PATH."accounting/". __function__);

					}
				}
				else {
					$this->Abas->sysMsg("errmsg", "Account not found!");
					$this->Abas->redirect(HTTP_PATH."accounting/". __function__);
				}
			}
			$this->load->view($mainview,$data);
		}
		public function journal($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("accounting|view_transaction_journal");
			if($action!="view_vouchers") {
				if(!empty($_GET['order']) || !empty($_GET['limit']) || !empty($_GET['offset'])) { // get output for bootstrap table
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$data	=	$this->Abas->createBSTable("ac_transaction_journal",$search,$limit,$offset,$order,$sort);
					if($data!=false) {
						foreach($data['rows'] as $ctr=>$entry) {
							$entry	=	$this->Accounting_model->getJournalEntry($entry['id']);
							$data['rows'][$ctr]['account_code']		=	$entry['account_code'];
							$data['rows'][$ctr]['account_name']		=	$entry['account']['name'];
							$data['rows'][$ctr]['posted_by']		=	$entry['created_by']['full_name'];
							$data['rows'][$ctr]['checker_name']		=	"-";
							$data['rows'][$ctr]['company_name']		=	$entry['company']['name'];
							$data['rows'][$ctr]['department_name']	=	$entry['department']['name'];
							$data['rows'][$ctr]['vessel_name']		=	$entry['vessel']['name'];
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				}
			}
			elseif($action=="view_vouchers") {
				if(!empty($_GET['order']) || !empty($_GET['limit']) || !empty($_GET['offset'])) { // get output for bootstrap table
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$data	=	$this->Abas->createBSTable("ac_journal_vouchers",$search,$limit,$offset,$order,$sort);
					if($data!=false) {
						foreach($data['rows'] as $ctr=>$voucher) {
							$entries								=	json_decode($voucher['journal_ids']);
							$firstEntry								=	$this->Accounting_model->getJournalEntry($entries[0]);
							$lastEntry								=	$this->Accounting_model->getJournalEntry(end($entries));
							$data['rows'][$ctr]['status']			=	"For Approval";
							$data['rows'][$ctr]['company_name']		=	"-";
							$data['rows'][$ctr]['entry_count']		=	count(json_decode($voucher['journal_ids']));
							if($firstEntry['stat']==1) {
								$data['rows'][$ctr]['status']		=	"Approved";
							}
							if($lastEntry!='') {
								$data['rows'][$ctr]['remark']		=	$lastEntry['remark'];
							}
							$total_credit = 0;
							foreach($entries as $entry){
								$jv = $this->Accounting_model->getJournalEntry($entry);
								$total_credit = $total_credit + $jv['credit_amount'];
							}
							$data['rows'][$ctr]['amount']		=	number_format($total_credit,2,'.',',');
							if(!empty($voucher['disapproved_by'])){
								$data['rows'][$ctr]['status']		=	"Disapproved";
							}
							if(is_numeric($voucher['company_id'])) {
								$company	=	$this->Abas->getCompany($voucher['company_id']);
								if(!empty($company)) {
									$data['rows'][$ctr]['company_name']	=	$company->name;
								}
							}
							if(is_numeric($voucher['company_id'])) {
								$company	=	$this->Abas->getCompany($voucher['company_id']);
								if(!empty($company)) {
									$data['rows'][$ctr]['company_name']	=	$company->name;
								}
							}
							if(!empty($voucher['created_on'])) {
								$data['rows'][$ctr]['created_on']	=	($voucher['created_on']=="0000-00-00 00:00:00") ? "" : date("j F Y H:i:s", strtotime($voucher['created_on']));
							}
							if(!empty($voucher['posted_on'])) {
								$data['rows'][$ctr]['posted_on']	=	($voucher['posted_on']=="0000-00-00 00:00:00") ? "" : date("j F Y", strtotime($voucher['posted_on']));
							}
							if(!empty($voucher['created_by'])) {
								$created_by							=	$this->Abas->getUser($voucher['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if(!empty($voucher['viewed_on'])) {
								$data['rows'][$ctr]['viewed_on']	=	($voucher['viewed_on']=="0000-00-00 00:00:00") ? "" : date("j F Y H:i:s", strtotime($voucher['viewed_on']));
							}
							if(!empty($voucher['viewed_by'])) {
								$viewed_by							=	$this->Abas->getUser($voucher['viewed_by']);
								if($viewed_by) {
									$data['rows'][$ctr]['status']		=	"Approved & Printed";
									$data['rows'][$ctr]['viewed_by']	=	$viewed_by['full_name'];
								}
								else {
									$data['rows'][$ctr]['viewed_by']	=	"-";
								}
							}
							if(!empty($voucher['approved_by'])) {
								$approved_by							=	$this->Abas->getUser($voucher['approved_by']);
								$data['rows'][$ctr]['approved_by']	=	$approved_by['full_name'];
							}
							if(!empty($voucher['approved_on'])) {
								$data['rows'][$ctr]['approved_on']	=	date("j F Y", strtotime($voucher['approved_on']));
							}
							if(!empty($voucher['disapproved_by'])) {
								$disapproved_by							=	$this->Abas->getUser($voucher['disapproved_by']);
								$data['rows'][$ctr]['disapproved_by']	=	$disapproved_by['full_name'];
							}
							if(!empty($voucher['disapproved_on'])) {
								$data['rows'][$ctr]['disapproved_on']	=	date("j F Y", strtotime($voucher['disapproved_on']));
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				}
			}
			$data['general_accounts']	=	$this->Accounting_model->getAccounts();
			$data['transaction_types']	=	$this->Accounting_model->getTransactionTypes();
			$data['companies']			=	$this->Abas->getCompanies();
			$data['viewfile']			=	"accounting/journal.php";
			$mainview					=	"gentlella_container.php";
			if($id=="") {
				if($action=="view_vouchers") {
					$data['viewfile']	=	"accounting/journal_vouchers.php";
				}
			}
			elseif(is_numeric($id)) {
				if($action=="view_transaction") {
					$data['transaction']=	$this->Accounting_model->getTransaction($id);
					$data['viewfile']	=	"accounting/transaction_entries.php";
				}
				elseif($action=="reconciliation") {
					$entry				=	$this->Accounting_model->getJournalEntry($id);
					if(!$entry) {
						$this->Abas->sysMsg("errmsg", "Journal Entry not found!");
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$data['selected_entry']		=	$entry;
					$transaction		=	$this->Accounting_model->getTransaction($entry['transaction_id']);
					if(!$transaction) {
						$this->Abas->sysMsg("errmsg", "Journal Entry not found!");
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$data['transaction']=	$transaction;
					$mainview			=	"accounting/reconciliation.php";
				}
				elseif($action=="view_voucher") {
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$voucher			=	$this->db->query("SELECT * FROM ac_journal_vouchers WHERE id=".$id);
					$voucher			=	(array)$voucher->row();
					$company			=	$this->Abas->getCompany($voucher['company_id']);
					$created_by			=	$this->Abas->getUser($voucher['created_by']);
					if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$created_by['signature'])) {
						$created_by['signature']	=	'';
					}
					else {
						if($created_by['signature']!="") {
							$created_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$created_by['signature'].'" width="200px" align="absmiddle" />';
						}
					}
					$approved_by		=	$this->Abas->getUser($voucher['approved_by']);
					if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$approved_by['signature'])) {
						$approved_by['signature']	=	'';
					}
					else {
						if($approved_by['signature']!="") {
							$approved_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$approved_by['signature'].'" width="200px" align="absmiddle" />';
						}
					}
					$table				=	'<table cellpadding="1" border="1" style="font-size:12px;">';
					$table				.=	'<tr style="text-align:center; background-color:#AAAAAA;">';
					$table				.=	'<th>Account Code</th>';
					$table				.=	'<th>Account Title</th>';
					$table				.=	'<th>Debit</th>';
					$table				.=	'<th>Credit</th>';
					$table				.=	'</tr>';
					$journal_ids		=	json_decode($voucher['journal_ids']);
					$memo				=	'';
					if(!empty($journal_ids)) {
						$total['debit']		=	0;
						$total['credit']	=	0;
						foreach($journal_ids as $ctr=>$journal_id) {
							$entry		=	$this->Accounting_model->getJournalEntry($journal_id);
							$total['debit']		=	$total['debit']+$entry['debit_amount'];
							$total['credit']	=	$total['credit']+$entry['credit_amount'];
							$table		.=	'<tr>';
							$table		.=	'<td style="text-align:right;">'.$entry['account_code'].'</td>';
							$table		.=	'<td>'.$entry['account_name'].'</td>';
							$table		.=	'<td style="text-align:right;">'.number_format($entry['debit_amount'],2).'</td>';
							$table		.=	'<td style="text-align:right;">'.number_format($entry['credit_amount'],2).'</td>';
							$table		.=	'</tr>';
							$memo		=	$entry['remark'];
						}
						$table			.=	'<tr style="text-align:right;">';
						$table			.=	'<td colspan="2">Total</td>';
						$table			.=	'<td>'.number_format($total['debit'],2).'</td>';
						$table			.=	'<td>'.number_format($total['credit'],2).'</td>';
						$table			.=	'</tr>';
					}
					$table				.=	'</table>';

					$content			=	'
					<div style="text-align:center;">
						<p style="font-size:10px">
						<div style="font-size:20px; font-weight:600"><strong>'.$company->name.'</strong></div>
						'.$company->address.'<br>
							Tel. Number'.$company->telephone_no.' Fax Number: '.$company->fax_no.'
						</p>
						<div style="font-size:18px; font-weight:600">Journal Voucher</div>
						<div style="font-size:10px; font-weight:600">Filing No. '.$voucher['created_at'].'-'.$voucher['filing_number'].'</div>
						'.(($voucher['viewed_by']!=null)? "- This copy is a reprint -":"" ).'
					</div>
					<br/><br/>
					<table style="font-size:11px;">
						<tr>
							<td style="text-align:right;">JV Number: </td>
							<td>'.$voucher['control_number'].'</td>
							<td style="text-align:right;">Date Prepared: </td>
							<td>'.date("j F Y", strtotime($voucher['posted_on'])).'</td>
						</tr>
						<tr>
							<td style="text-align:right;">Transaction Code: </td>
							<td>'.$voucher['id'].'</td>
							<td style="text-align:right;">Date Printed: </td>
							<td>'.date("j F Y").'</td>
						</tr>
					</table>
					<br/><br/>
					'.$table.'
					<br/><br/>
					<div style="font-size:11px;">Particulars: '.$memo.'</div>
					<br/><br/>
					<table style="font-size:11px; margin:10px;">
						<tr>
							<td>Prepared by:</td>
							<td>Reviewed by:</td>
							<td>Approved by:</td>
						</tr>
						<tr>
							<td>'.$created_by['signature'].'</td>
							<td></td>
							<td>'.$approved_by['signature'].'</td>
						</tr>
						<tr>
							<td>'.$created_by['full_name'].'</td>
							<td>Accounting Analyst/Officer</td>
							<td>'.$approved_by['full_name'].'</td>
						</tr>
					</table>
					';
					unset($data);
					if($voucher['approved_by']==true) {
						if($voucher['viewed_by']==null) {
							$update['viewed_by']	=	$_SESSION['abas_login']['userid'];
							$udpate['viewed_on']	=	date("Y-m-d H:i:s");
							$this->Mmm->dbUpdate("ac_journal_vouchers", $update, $voucher['id'],"Initial viewing of a printable journal voucher");
						}
						$data['content']		=	$content;
						$data['orientation']	=	"P";
						$data['pagetype']		=	"letter";
						$mainview				=	"pdf-container.php";
					}
					else {
						$data['voucher']	=	$voucher;
						$data['viewfile']	=	"accounting/journal_voucher.php";
						$mainview			=	"gentlella_container.php";
					}
				}
				elseif($action=="print_voucher") {
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$voucher			=	$this->db->query("SELECT * FROM ac_journal_vouchers WHERE id=".$id);
					$voucher			=	(array)$voucher->row();
					$company			=	$this->Abas->getCompany($voucher['company_id']);
					$created_by			=	$this->Abas->getUser($voucher['created_by']);
					if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$created_by['signature'])) {
						$created_by['signature']	=	'';
					}
					else {
						if($created_by['signature']!="") {
							$created_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$created_by['signature'].'" width="200px" align="absmiddle" />';
						}
					}
					$approved_by		=	$this->Abas->getUser($voucher['approved_by']);
					if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$approved_by['signature'])) {
						$approved_by['signature']	=	'';
					}
					else {
						if($approved_by['signature']!="") {
							$approved_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$approved_by['signature'].'" width="200px" align="absmiddle" />';
						}
					}
					$table				=	'<table cellpadding="1" border="1" style="font-size:12px;">';
					$table				.=	'<tr style="text-align:center; background-color:#AAAAAA;">';
					$table				.=	'<th>Account Code</th>';
					$table				.=	'<th>Account Title</th>';
					$table				.=	'<th>Debit</th>';
					$table				.=	'<th>Credit</th>';
					$table				.=	'</tr>';
					$journal_ids		=	json_decode($voucher['journal_ids']);
					$memo				=	'';
					if(!empty($journal_ids)) {
						$total['debit']		=	0;
						$total['credit']	=	0;
						foreach($journal_ids as $ctr=>$journal_id) {
							$entry		=	$this->Accounting_model->getJournalEntry($journal_id);
							$total['debit']		=	$total['debit']+$entry['debit_amount'];
							$total['credit']	=	$total['credit']+$entry['credit_amount'];
							$table		.=	'<tr>';
							$table		.=	'<td style="text-align:right;">'.$entry['account_code'].'</td>';
							$table		.=	'<td>'.$entry['account_name'].'</td>';
							$table		.=	'<td style="text-align:right;">'.number_format($entry['debit_amount'],2).'</td>';
							$table		.=	'<td style="text-align:right;">'.number_format($entry['credit_amount'],2).'</td>';
							$table		.=	'</tr>';
							$memo		=	$entry['remark'];
						}
						$table			.=	'<tr style="text-align:right;">';
						$table			.=	'<td colspan="2">Total</td>';
						$table			.=	'<td>'.number_format($total['debit'],2).'</td>';
						$table			.=	'<td>'.number_format($total['credit'],2).'</td>';
						$table			.=	'</tr>';
					}
					$table				.=	'</table>';

					$content			=	'
					<div style="text-align:center;">
						<p style="font-size:10px">
						<div style="font-size:20px; font-weight:600"><strong>'.$company->name.'</strong></div>
						'.$company->address.'<br>
							Tel. Number'.$company->telephone_no.' Fax Number: '.$company->fax_no.'
						</p>
						<div style="font-size:18px; font-weight:600">Journal Voucher</div>
						<div style="font-size:10px; font-weight:600">Filing No. '.$voucher['created_at'].'-'.$voucher['filing_number'].'</div>
						'.(($voucher['viewed_by']!=null)? "- This copy is a reprint -":"" ).'
					</div>
					<br/><br/>
					<table style="font-size:11px;">
						<tr>
							<td style="text-align:right;">JV Number: </td>
							<td>'.$voucher['control_number'].'</td>
							<td style="text-align:right;">Date Prepared: </td>
							<td>'.date("j F Y", strtotime($voucher['posted_on'])).'</td>
						</tr>
						<tr>
							<td style="text-align:right;">Transaction Code: </td>
							<td>'.$voucher['id'].'</td>
							<td style="text-align:right;">Date Printed: </td>
							<td>'.date("j F Y").'</td>
						</tr>
					</table>
					<br/><br/>
					'.$table.'
					<br/><br/>
					<div style="font-size:11px;">Particulars: '.$memo.'</div>
					<br/><br/>
					<table style="font-size:11px; margin:10px;">
						<tr>
							<td>Prepared by:</td>
							<td>Reviewed by:</td>
							<td></td>
						</tr>
						<tr>
							<td>'.$created_by['signature'].'</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>'.$created_by['full_name'].'</td>
							<td>Accounting Analyst/Officer</td>
							<td></td>
						</tr>
					</table>
					';
					unset($data);
					$data['content']		=	$content;
					$data['orientation']	=	"P";
					$data['pagetype']		=	"letter";
					$mainview				=	"pdf-container.php";
				}
				elseif($action=="approve_voucher") {
					$this->Abas->checkPermissions("accounting|approve_journal_vouchers");
					$voucher['approved_by']	=	$_SESSION['abas_login']['userid'];
					$voucher['approved_on']	=	date("Y-m-d H:i:s");
					$check					=	$this->Mmm->dbUpdate("ac_journal_vouchers", $voucher, $id, "Approve journal voucher");
					if($check) {
						$voucher			=	$this->db->query("SELECT * FROM ac_journal_vouchers WHERE id=".$id);
						$voucher			=	(array)$voucher->row();
						$entries			=	json_decode($voucher['journal_ids']);
						if(!empty($entries)) {
							foreach($entries as $ctr=>$entry) {
								$entry		=	$this->Accounting_model->getJournalEntry($entry);
								$this->Mmm->debug($entry);
								$update['stat']	=	1;
								$check_journal	=	$this->Mmm->dbUpdate("ac_transaction_journal", $update, $entry['id'], "Journalize newly approved entries in journal voucher transaction number ".$id);
								if($check_journal) {
									$this->Abas->sysMsg("sucnmsg", "Entry for ".$entry['account_name']." successfully journalized");
								}
								else {
									$this->Abas->sysMsg("warnmsg", "Failed to journalize entry for ".$entry['account_name']);
								}
							}
						}
					}
					else {
						$this->Abas->sysMsg("errmsg", "Failed to approve journal voucher! Please try again.");
					}
					$this->Abas->redirect(HTTP_PATH."accounting/journal/view_vouchers");
				}
				elseif($action=="disapprove_voucher") {
					$this->Abas->checkPermissions("accounting|approve_journal_vouchers");
					$voucher['disapproved_by']	=	$_SESSION['abas_login']['userid'];
					$voucher['disapproved_on']	=	date("Y-m-d H:i:s");
					$check						=	$this->Mmm->dbUpdate("ac_journal_vouchers", $voucher, $id, "Disapprove journal voucher");
					if($check) {
						$this->Abas->sysMsg("sucnmsg", "Journal Voucher Transaction code ".$id." successfully disapproved");
					}
					else {
						$this->Abas->sysMsg("warnmsg", "Failed to disapprove journal voucher transaction code ".$id);
					}
					$this->Abas->redirect(HTTP_PATH."accounting/journal/view_vouchers");
				}
			}
			$this->load->view($mainview,$data);
		}
		public function transactions($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("accounting|view_transaction_journal");
			if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) { // get output for bootstrap table
				$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
				$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
				$order	=	isset($_GET['order'])?$_GET['order']:"";
				$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
				$search	=	isset($_GET['search'])?$_GET['search']:"";
				$data	=	$this->Abas->createBSTable("ac_transactions",$search,$limit,$offset,$order,$sort);

				if($data!=false) {
					foreach($data['rows'] as $ctr=>$transaction) {
						$data['rows'][$ctr]['company_name']		=	"";
						$data['rows'][$ctr]['total_debit']		=	0;
						$data['rows'][$ctr]['total_credit']		=	0;
						$data['rows'][$ctr]['status']			=	1;
						$transaction_data						=	$this->Accounting_model->getTransaction($transaction['id']);
						if(!empty($transaction_data)) {
							$company								=	$this->Abas->getCompany($transaction_data['company_id']);
							if($company) {
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							$data['rows'][$ctr]['date']				=	date("j F Y H:i:s", strtotime($transaction_data['date']));
							$data['rows'][$ctr]['total_debit']		=	number_format($transaction_data['total_debit'],2);
							$data['rows'][$ctr]['total_credit']		=	number_format($transaction_data['total_credit'],2);
							$data['rows'][$ctr]['status']			=	number_format($transaction_data['status'],2);
							if($transaction_data['total_debit']==$transaction_data['total_credit']){
								$data['rows'][$ctr]['balance']	= 'Yes';
							}else{
								$data['rows'][$ctr]['balance']	= 'No';
							}
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					die();
				}
			}
			$data['companies']			=	$this->Abas->getCompanies();
			$data['viewfile']			=	"accounting/transactions.php";
			$mainview					=	"gentlella_container.php";
			if(is_numeric($id)) {
				$transaction			=	$this->Accounting_model->getTransaction($id);
				/*if(empty($transaction)) {
					$this->Abas->sysMsg("errmsg", "Transaction not found!");
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}*/
				$data['transaction']	=	$transaction;
				if($action=="add_entry") {
					$mainview	=	"accounting/journal_form.php";
				}elseif($action=="edit_entry"){
					$entry = $this->Accounting_model->getJournalEntry($id);
					$data['entry_debit_amount'] = $entry['debit_amount'];
					$data['entry_credit_amount'] = $entry['credit_amount'];
					$data['entry_id'] = $entry['id'];
					$data['entry_transaction_id'] = $entry['transaction_id'];
					$mainview = "accounting/transaction_edit_entry.php";
				}elseif($action=="update_entry"){
					if(isset($_POST)){
						$entry_id					=	$this->Mmm->sanitize($_POST['entry_id']);
						$entry_transaction_id		=	$this->Mmm->sanitize($_POST['entry_transaction_id']);
						$old_debit_amount			=	$this->Mmm->sanitize($_POST['old_debit_amount']);
						$old_credit_amount			=	$this->Mmm->sanitize($_POST['old_credit_amount']);
						$update['debit_amount']		=	$this->Mmm->sanitize($_POST['new_debit_amount']);
						$update['credit_amount']	=	$this->Mmm->sanitize($_POST['new_credit_amount']);
						$check						=	$this->Mmm->dbUpdate("ac_transaction_journal", $update, $entry_id, "Updated Transaction with ID ".$entry_transaction_id." (Change debit amount from ".$old_debit_amount." to ".$update['debit_amount']. " and credit amount from ".$old_credit_amount. " to ".$update['credit_amount'].")");
						if($check){
							$this->Abas->sysMsg("sucmsg","Updated Transaction with ID ".$entry_transaction_id." (Changed debit amount from ".$old_debit_amount." to ".$update['debit_amount']. " and credit amount from ".$old_credit_amount. " to ".$update['credit_amount'].")");
						}else{
							$this->Abas->sysMsg("errmsg","An error occurred while updating the record.");
						}
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
				}
				elseif($action=="insert_entry") {
					if(empty($_POST)) {
						$this->Abas->sysMsg("errmsg", "No input detected!");
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$entry['transaction_id']	=	$transaction['id'];
					$entry['company']			=	$transaction['company_id'];
					//$this->Mmm->debug($_POST);
					$entry['remark']			=	$this->Mmm->sanitize($_POST['remark']);
					$entry['reference_table']	=	isset($_POST['reference_table'])?$this->Mmm->sanitize($_POST['reference_table']):"";
					$entry['reference_id']		=	isset($_POST['reference_number'])?$this->Mmm->sanitize($_POST['reference_number']):0;
					if(!empty($_POST['account']) && !empty($_POST['amount']) && !empty($_POST['debit_or_credit'])) {
						$total_values			=	array("debit"=>0, "credit"=>0);
						$continue				=	true;
						foreach($_POST['account'] as $ctr=>$encoded) { // validation for all entries
							$account						=	$this->Accounting_model->getAccount($_POST['account'][$ctr]);
							$amount							=	is_numeric($_POST['amount'][$ctr]) ? $_POST['amount'][$ctr] : 0;
							$total_values['debit']			=	$_POST['debit_or_credit'][$ctr]=="Debit" ? $amount+$total_values['debit'] : $total_values['debit'];
							$total_values['credit']			=	$_POST['debit_or_credit'][$ctr]=="Credit" ? $amount+$total_values['credit'] : $total_values['credit'];
							if($amount<=0) {
								$continue	=	false;
								$this->Abas->sysMsg("warnmsg", "Invalid amount detected! Aborting journalization.");
							}
							if(empty($account)) {
								$continue	=	false;
								$this->Abas->sysMsg("warnmsg", "Invalid account detected! Aborting journalization.");
							}
							if(strtolower($_POST['debit_or_credit'][$ctr])!="debit" && strtolower($_POST['debit_or_credit'][$ctr])!="credit") {
								$continue	=	false;
								$this->Abas->sysMsg("warnmsg", "Invalid debit/credit selection detected! Aborting journalization.");
							}
						}
						if((round($total_values['debit'],2)-round($total_values['credit'],2))!=0) {
							$continue	=	false;
							$this->Abas->sysMsg("warnmsg", "Invalid total amount! Aborting journalization.");
						}
						if($continue==false) {
							$this->Abas->sysMsg("warnmsg", "There was a problem with your entry. Please try again.");
							$this->Abas->redirect($_SERVER['HTTP_REFERER']);
						}
						foreach($_POST['account'] as $ctr=>$encoded) {
							$account_id					=	$this->Mmm->sanitize($_POST['account'][$ctr]);
							$account					=	$this->Accounting_model->getAccount($account_id);
							$amount						=	is_numeric($_POST['amount'][$ctr]) ? $_POST['amount'][$ctr] : 0;
							$entry['posted_on']			=	date("Y-m-d", strtotime($_POST['posted_on']));
							$entry['debit_amount']		=	$_POST['debit_or_credit'][$ctr]=="Debit" ? $amount : 0;
							$entry['credit_amount']		=	$_POST['debit_or_credit'][$ctr]=="Credit" ? $amount : 0;
							$entry['department']		=	is_numeric($_POST['department'][$ctr]) ? $_POST['department'][$ctr] : 0;
							$entry['vessel']			=	is_numeric($_POST['vessel'][$ctr]) ? $_POST['vessel'][$ctr] : 0;
							$entry['contract']			=	is_numeric($_POST['contract'][$ctr]) ? $_POST['contract'][$ctr] : 0;
							$entry['account']			=	$account_id;
							$entry['stat']				=	0;
							$check						=	$this->Accounting_model->newJournalEntry($entry);
							if($check) {
								$this->Abas->sysMsg("sucmsg","Journal entry for account ".$account['code']." (".$account['name'].") encoded successfully!");
								$latest	=	$this->db->query("SELECT MAX(id) AS id FROM ac_transaction_journal");
								$latest	=	(array)$latest->row();
								$journal_voucher_ids[]	=	$latest['id'];
							}
							else {
								$this->Abas->sysMsg("errmsg","Journal entry for account ".$account['code']." (".$account['name'].") was not encoded!");
							}
						}
						$journal_voucher['company_id']		=	$transaction['company_id'];
						$journal_voucher['control_number']	=	$this->Abas->getNextSerialNumber("ac_journal_vouchers", $transaction['company_id']);
						$journal_voucher['filing_number']	=	$this->Abas->getNextFilingNumberByLocation("ac_journal_vouchers",$transaction['company_id'],$_SESSION['abas_login']['user_location']);
						$journal_voucher['journal_ids']		=	json_encode($journal_voucher_ids);
						$journal_voucher['posted_on']		=	date("Y-m-d", strtotime($_POST['posted_on']));
						$journal_voucher['created_on']		=	date("Y-m-d H:i:s");
						$journal_voucher['created_at']		=	$_SESSION['abas_login']['user_location'];
						$journal_voucher['created_by']		=	$_SESSION['abas_login']['userid'];
						$check_voucher						=	$this->Mmm->dbInsert("ac_journal_vouchers", $journal_voucher, "New journal voucher with ".$ctr." entries");
						if($check_voucher) {
							if(!empty($journal_voucher_ids)) {
								$newest_journal_voucher		=	$this->db->query("SELECT MAX(id) AS id FROM ac_journal_vouchers");
								$newest_journal_voucher		=	(array)$newest_journal_voucher->row();
								foreach($journal_voucher_ids as $ctr=>$entry_id) {
									$this->db->query("UPDATE ac_transaction_journal SET reference_table='ac_journal_vouchers' AND reference_id=".$newest_journal_voucher['id']." WHERE id=".$entry_id);
								}
							}
							$this->Abas->sysMsg("sucmsg", "Journal voucher created! This voucher is pending approval, and will be available for printing as soon as it is approved. Click <a href='".HTTP_PATH."accounting/journal/view_vouchers' ckass='btn btn-default btn-xs'>HERE</a> to view the journal vouchers");
						}
						else {
							$this->Abas->sysMsg("errmsg", "Journal voucher not created! Please notify the IT department immediately!");
							$this->Abas->sysNotif("Critical Accounting Error", "Manual journal entry failed to generate a voucher!", "Administrator", "danger");
						}
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
			}
			else {
				if($action=="add") {
					$mainview	=	"accounting/journal_form.php";
				}
				elseif($action=="insert") {
					if(empty($_POST)) {
						$this->Abas->sysMsg("errmsg", "No input detected!");
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$new_transaction			=	false;
					if($_POST['transaction_id']=="new") {
						$insert['date']			=	(isset($_POST['posted_on'])) ? date("Y-m-d H:i:s",strtotime($_POST['posted_on'])) : date("Y-m-d H:i:s");
						$insert['company_id']	=	$this->Mmm->sanitize($_POST['company']);
						$insert['remark']		=	$this->Mmm->sanitize($_POST['remark']);
						$insert['status']		=	"Active";
						$insert['stat']			=	1;
						$insert['created_on']	=	date("Y-m-d H:i:s");
						$insert['created_by']	=	$_SESSION['abas_login']['userid'];
						$check					=	$this->Mmm->dbInsert("ac_transactions", $insert, "Manually added new transaction");
						if($check) {
							$new_transaction	=	true;
						}
						else {
							$this->Abas->sysMsg("errmsg", "Transaction not encoded! Please try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/transactions");
						}
						$maxid				=	$this->db->query("SELECT MAX(id) AS maxid FROM ac_transactions");
						$maxid				=	(array)$maxid->row();
						$transaction_id		=	$maxid['maxid'];
					}
					elseif(is_numeric($_POST['transaction_id'])) {
						$transaction_id		=	$this->Mmm->sanitize($_POST['transaction_id']);
						$insert				=	$this->Accounting_model->getTransaction($_POST['transaction_id']);
					}
					$total_values			=	array("debit"=>0, "credit"=>0);
					$continue				=	true;
					foreach($_POST['account'] as $ctr=>$encoded) { // validation for all entries
						$account						=	$this->Accounting_model->getAccount($_POST['account'][$ctr]);
						$amount							=	is_numeric($_POST['amount'][$ctr]) ? $_POST['amount'][$ctr] : 0;
						$total_values['debit']			=	$_POST['debit_or_credit'][$ctr]=="Debit" ? $amount+$total_values['debit'] : $total_values['debit'];
						$total_values['credit']			=	$_POST['debit_or_credit'][$ctr]=="Credit" ? $amount+$total_values['credit'] : $total_values['credit'];
						if($amount<=0) {
							$continue	=	false;
							$this->Abas->sysMsg("warnmsg", "Invalid amount detected! Aborting journalization.");
						}
						if(empty($account)) {
							$continue	=	false;
							$this->Abas->sysMsg("warnmsg", "Invalid account detected! Aborting journalization.");
						}
						if(strtolower($_POST['debit_or_credit'][$ctr])!="debit" && strtolower($_POST['debit_or_credit'][$ctr])!="credit") {
							$continue	=	false;
							$this->Abas->sysMsg("warnmsg", "Invalid debit/credit selection detected! Aborting journalization.");
						}
					}
					if((round($total_values['debit'],2)-round($total_values['credit'],2))!=0) {
						$continue	=	false;
						$this->Abas->sysMsg("warnmsg", "Invalid total amount! Aborting journalization. <pre>Balance: ".round($total_values['debit']-$total_values['credit'],2)."</pre>");
					}
					if($continue==false) {
						if($new_transaction==true) $this->db->query("DELETE FROM ac_transactions WHERE id=".$transaction_id);
						$this->Abas->sysMsg("warnmsg", "There was a problem with your entry. Please try again.");
						$this->Abas->redirect(HTTP_PATH."accounting/transactions");
					}
					if($new_transaction==true) $this->Abas->sysMsg("sucmsg", "Transaction successfully encoded!");
					$transaction				=	$this->Accounting_model->getTransaction($transaction_id);
					$entry['transaction_id']	=	$transaction_id;
					$entry['company']			=	$insert['company_id'];
					$entry['remark']			=	$this->Mmm->sanitize($_POST['remark']);
					$entry['reference_table']	=	"ac_journal_vouchers";
					$entry['reference_id']		=	0; // this gets updated upon creation of journal voucher record
					if(!empty($_POST['account']) && !empty($_POST['amount']) && !empty($_POST['debit_or_credit'])) {
						foreach($_POST['account'] as $ctr=>$encoded) {
							$account_id					=	$this->Mmm->sanitize($_POST['account'][$ctr]);
							$account					=	$this->Accounting_model->getAccount($account_id);
							$amount						=	is_numeric($_POST['amount'][$ctr]) ? $_POST['amount'][$ctr] : 0;
							$entry['posted_on']			=	(isset($_POST['posted_on'])) ? date("Y-m-d",strtotime($_POST['posted_on'])) : date("Y-m-d H:i:s");
							$entry['debit_amount']		=	$_POST['debit_or_credit'][$ctr]=="Debit" ? $amount : 0;
							$entry['credit_amount']		=	$_POST['debit_or_credit'][$ctr]=="Credit" ? $amount : 0;
							$entry['department']		=	is_numeric($_POST['department'][$ctr]) ? $_POST['department'][$ctr] : 0;
							$entry['vessel']			=	is_numeric($_POST['vessel'][$ctr]) ? $_POST['vessel'][$ctr] : 0;
							$entry['contract']			=	is_numeric($_POST['contract'][$ctr]) ? $_POST['contract'][$ctr] : 0;
							$entry['account']			=	$account_id;
							$entry['stat']				=	0;
							if(empty($account)) {
								$this->Abas->sysMsg("errmsg", "The account (".$_POST['account_label'][$ctr].") was not found! Please try again.");
							}
							else {
								$check	=	$this->Accounting_model->newJournalEntry($entry);
								if($check) {
									$this->Abas->sysMsg("sucmsg","Journal entry for account ".$account['code']." (".$account['name'].") encoded successfully!");
									$latest	=	$this->db->query("SELECT MAX(id) AS id FROM ac_transaction_journal");
									$latest	=	(array)$latest->row();
									$journal_voucher_ids[]	=	$latest['id'];
								}
								else {
									$this->Abas->sysMsg("errmsg","Journal entry for account ".$account['code']." (".$account['name'].") was not encoded!");
								}
							}
						}
						$journal_voucher['company_id']		=	$transaction['company_id'];
						$journal_voucher['control_number']	=	$this->Abas->getNextSerialNumber("ac_journal_vouchers", $transaction['company_id']);
						$journal_voucher['filing_number']	=	$this->Abas->getNextFilingNumberByLocation("ac_journal_vouchers",$transaction['company_id'],$_SESSION['abas_login']['user_location']);
						$journal_voucher['journal_ids']		=	json_encode($journal_voucher_ids);
						$journal_voucher['posted_on']		=	(isset($_POST['posted_on'])) ? date("Y-m-d",strtotime($_POST['posted_on'])) : date("Y-m-d H:i:s");
						$journal_voucher['created_on']		=	date("Y-m-d H:i:s");
						$journal_voucher['created_at']		=	$_SESSION['abas_login']['user_location'];
						$journal_voucher['created_by']		=	$_SESSION['abas_login']['userid'];
						$check_voucher						=	$this->Mmm->dbInsert("ac_journal_vouchers", $journal_voucher, "New journal voucher with ".$ctr." entries");
						if($check_voucher) {
							$latest	=	$this->db->query("SELECT MAX(id) AS id FROM ac_journal_vouchers");
							$latest	=	(array)$latest->row();
							$journal_voucher_ids=	json_decode($journal_voucher['journal_ids']);
							foreach($journal_voucher_ids as $entry_id) {
								//$this->db->query("UPDATE ac_transaction_journal SET reference_id=".$latest['id']." WHERE id=".$entry_id);
								$this->db->query("UPDATE ac_transaction_journal SET reference_table='ac_journal_vouchers', reference_id=".$latest['id']." WHERE id=".$entry_id);
							}
							$this->Abas->sysMsg("sucmsg", "Journal voucher created! This voucher is pending approval, and will be available for printing as soon as it is approved.  Click <a href='".HTTP_PATH."accounting/journal/view_vouchers' ckass='btn btn-default btn-xs'>HERE</a> to view the journal vouchers");
						}
						else {
							$this->Abas->sysMsg("errmsg", "Journal voucher not created! Please notify the IT department immediately!");
							$this->Abas->sysNotif("Critical Accounting Error", "Manual journal entry failed to generate a voucher!", "Administrator", "danger");
						}
					}
					$this->Abas->redirect(HTTP_PATH."accounting/journal/view_transaction/".$transaction_id);
				}
			}
			$this->load->view($mainview,$data);
		}
		public function trial_balance($action="") {$data=array();
			$this->Abas->checkPermissions("accounting|view_reports");
			if($action=="filter") {
				$data['companies']			=	$this->Abas->getCompanies();
				$mainview				=	"accounting/trial_balance/filter.php";
			}
			elseif($action=="report") {
				require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
				$previous_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:HTTP_PATH;
				$data['orientation']	=	"L";
				$data['pagetype']		=	"letter";
				$mainview				=	"pdf-container.php";
				$data['company']		=	(object)array("name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
				$company_query	=	" AND (company_id<>7 OR company_id<>11)";
				$department_query=$vessel_query=$contract_query="";
				$data['business_unit_code']=$data['department_account_code']="00";
				$data['company_account_code']=$data['vessel_account_code']="000";
				$data['contract_account_code']="0000";
				if(isset($_GET['company']) && isset($_GET['dstart']) && isset($_GET['dfinish'])) {
					if(is_numeric($_GET['company'])) {
						$company		=	$this->Abas->getCompany($_GET['company']);
						if($company) {
							$data['company']				=	$company;
							if($company->id==1){
								$company_query					=	' AND (tj.company_id='.$company->id.' OR tj.company_id=10)';
							}else{
								$company_query					=	' AND tj.company_id='.$company->id;
							}
							$data['company_account_code']	=	str_pad($company->id, 3, '0', STR_PAD_LEFT);
						}
					}
					elseif($_GET['company']=="side-by-side") { // this is a terrible, terrible hack
						$report_parameters	=	$_GET;
						$companies			=	$this->Abas->getCompanies();
						$javascript			=	'<script>';
						foreach($companies as $company) {
							$report_parameters['company']	=	$company->id;
							$url			=	HTTP_PATH."accounting/trial_balance/report?".http_build_query($report_parameters);
							$javascript		.=	'window.open("'.$url.'", "_blank");';
						}
						$javascript			.=	'window.location("'.HTTP_PATH.'accounting")';
						$javascript			.=	'</script>';
						die($javascript);
					}
					elseif($_GET['company']=="consolidate") {
						// do nothing, trial balance consolidates by default!
					}
				}
				if(isset($_GET['department'])) {
					if(is_numeric($_GET['department'])) {
						$department			=	$this->Abas->getDepartment($_GET['department']);
						if($department) {
							$department_query					=	' AND tj.department_id='.$department->id;
							$data['department_account_code']	=	str_pad($department->accounting_code, 2, '0', STR_PAD_LEFT);
						}
					}
				}
				if(isset($_GET['vessel'])) {
					if(is_numeric($_GET['vessel'])) {
						$vessel			=	$this->Abas->getVessel($_GET['vessel']);
						if($vessel){
							$vessel_query					=	' AND tj.vessel_id='.$vessel->id;
							$data['vessel_account_code']	=	str_pad($vessel->id, 3, '0', STR_PAD_LEFT);
						}
					}
				}
				if(isset($_GET['contract'])) {
					if(is_numeric($_GET['contract'])) {
						$contract				=	$this->Abas->getContract($_GET['contract']);
						$contract_query					=	' AND tj.contract_id='.$contract['id'];
						$data['contract_account_code']	=	str_pad($contract['reference_no'], 4, '0', STR_PAD_LEFT);
					}
				}
				$start_report	=	date("Y-m-d",strtotime($_GET['dstart']))." 00:00:00";
				$finish_report	=	date("Y-m-d",strtotime($_GET['dfinish']))." 23:59:59";
				$sql			=	"select coa.id as id, coa.financial_statement_code, coa.general_ledger_code, coa.name, if((sum(tj.debit_amount)-sum(tj.credit_amount)) > 0, (sum(tj.debit_amount)-sum(tj.credit_amount)), 0) as debit_total, if((sum(tj.debit_amount)-sum(tj.credit_amount)) < 0, abs(sum(tj.debit_amount)-sum(tj.credit_amount)), 0) as credit_total from ac_transaction_journal as tj join ac_accounts as coa on coa.id=tj.coa_id where tj.stat=1 and tj.posted_on between '".$start_report."' and '".$finish_report."' ".$company_query." ".$vessel_query." ".$contract_query." group by coa_id order by coa.financial_statement_code, coa.general_ledger_code"; // This query filters by date, company, vessel, and contract. It fetches the sum of debit/credit lines, and joins the account codes and names
				$accounts		=	$this->db->query($sql);
				$data['accounts']		=	$accounts->result_array();
				$data['viewfile']		=	"accounting/trial_balance/print.php";
				$mainview				=	"gentlella_container.php";
			}
			$this->load->view($mainview, $data);
		}
		public function statement_of_financial_position($action="") {$data=array();
			$this->Abas->checkPermissions("accounting|view_reports");
			$mainview						=	"gentlella_container.php";
			if($action=="filter") {
				$data['companies']			=	$this->Abas->getCompanies();
				$mainview					=	"accounting/financial_statement/financial_position_filter.php";
			}
			elseif($action=="report") {
				$mainview="accounting/financial_statement/financial_position_print.php";
			}
			$this->load->view($mainview, $data);
		}
		public function statement_of_income($action="") {$data=array();
			$this->Abas->checkPermissions("accounting|view_reports");
			$mainview						=	"gentlella_container.php";
			if($action=="filter") {
				$data['companies']			=	$this->Abas->getCompanies();
				$mainview					=	"accounting/financial_statement/income_filter.php";
			}
			elseif($action=="report") {
				$data['viewfile']			=	"accounting/financial_statement/income_print.php";
			}
			$this->load->view($mainview, $data);
		}
		public function books($action="") {$data=array();
			$this->Abas->checkPermissions("accounting|view_reports");
			$previous_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:HTTP_PATH;
			if($action=="") {
				$data['companies']			=	$this->Abas->getCompanies();
				$mainview				=	"accounting/books/filter.php";
			}
			elseif($action=="print") {
				$mainview	=	"accounting/books/print.php";
			}
			elseif($action=="report") {
				if(empty($_GET)) {
					$this->Abas->redirect($previous_page);
				}
				$mainview			=	"gentlella_container.php";
				$data['viewfile']	=	"accounting/books/report.php";
				$company				=	(object)array("name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
				$company_query=$department_query=$vessel_query=$contract_query="";
				$business_unit_code=$department_account_code="00";
				$company_account_code=$vessel_account_code="000";
				$contract_account_code="0000";
				if(!isset($_GET['dstart']) || !isset($_GET['dfinish'])) {
					$this->Abas->sysMsg("warnmsg", "No report date selected!");
					$this->Abas->redirect($previous_page);
				}
				$date_start		=	date("Y-m-d", strtotime($_GET['dstart']))." 00:00:00";
				$date_finish	=	date("Y-m-d", strtotime($_GET['dfinish']))." 23:59:59";
				if(!isset($_GET['journal_type'])) {
					$this->Abas->sysMsg("warnmsg", "No report type selected!");
					$this->Abas->redirect($previous_page);
				}
				if(isset($_GET['company'])) {
					if(is_numeric($_GET['company'])) {
						$company		=	$this->Abas->getCompany($_GET['company']);
						if($company) {
							if($company->id==1){
								$company_query			=	' AND (company_id='.$company->id.' OR company_id=10)';
							}else{
								$company_query			=	' AND company_id='.$company->id;
							}
							$company_account_code	=	str_pad($company->id, 3, '0', STR_PAD_LEFT);
						}
					}
				}
				$data['company']		=	$company;
				if(isset($_GET['department'])) {
					if(is_numeric($_GET['department'])) {
						$department			=	$this->Abas->getDepartment($_GET['department']);
						if($department) {
							$department_query			=	' AND department_id='.$department->id;
							$department_account_code	=	str_pad($department->accounting_code, 2, '0', STR_PAD_LEFT);
						}
					}
				}
				if(isset($_GET['vessel'])) {
					if(is_numeric($_GET['vessel'])) {
						$vessel			=	$this->Abas->getVessel($_GET['vessel']);
						if($vessel){
							$vessel_query			=	' AND vessel_id='.$vessel->id;
							$vessel_account_code	=	str_pad($vessel->id, 3, '0', STR_PAD_LEFT);
						}
					}
				}
				if(isset($_GET['contract'])) {
					if(is_numeric($_GET['contract'])) {
						$contract				=	$this->Abas->getContract($_GET['contract']);
						$contract_query			=	' AND contract_id='.$contract['id'];
						$contract_account_code	=	str_pad($contract['reference_no'], 4, '0', STR_PAD_LEFT);
					}
				}
				$entity				=	"Unknown Entity";
				$column_names		=	array("created_on"=>"Unknown");
				if($_GET['journal_type']=="general") {
					$entity						=	"Journal Voucher";
					$reference_table			=	"ac_journal_vouchers";
					$data['view_link']			=	HTTP_PATH."accounting/journal/view_voucher/";
					$column_names['created_on']	=	"posted_on";
					$jvsql						=	"SELECT id,control_number,posted_on FROM ac_journal_vouchers WHERE posted_on>='".$date_start."' AND posted_on<='".$date_finish."' ".$company_query;
					$apvsql						=	"SELECT id,date_created,control_number FROM ac_ap_vouchers WHERE date_created>='".$date_start."' AND date_created<='".$date_finish."' ".$company_query;
					$issuancesql						=	"SELECT id,issue_date,control_number FROM inventory_issuance WHERE issue_date>='".$date_start."' AND issue_date<='".$date_finish."' ".$company_query;
					$jvdocuments				=	$this->db->query($jvsql);
					$apvdocuments				=	$this->db->query($apvsql);
					$issuancedocuments			=	$this->db->query($issuancesql);
					$jvdocuments				=	$jvdocuments->result_array();
					$apvdocuments				=	$apvdocuments->result_array();
					$issuancedocuments			=	$issuancedocuments->result_array();
					$documents					=	$jvdocuments;
					// merge apvdocuments and jvdocuments and issuancedocuments
					$jvctr						=	count($jvdocuments);
					if(!empty($apvdocuments)) {
						foreach($apvdocuments as $apv) {
							$jvctr++;
							$documents[$jvctr]	=	array(
														"id"=>$apv['id'],
														"posted_on"=>$apv['date_created'],
														"control_number"=>$apv['control_number'],
														"is_accounts_payable"=>true,
													);
						}
					}
					$issuancectr						=	count($documents);
					if(!empty($issuancedocuments)) {
						foreach($issuancedocuments as $msis) {
							$issuancectr++;
							$documents[$issuancectr]	=	array(
														"id"=>$msis['id'],
														"posted_on"=>$msis['issue_date'],
														"control_number"=>$msis['control_number'],
														"is_material_issuance"=>true,
													);
						}
					}
				}
				else {
					if($_GET['journal_type']=="purchase") {
						$entity						=	"Receiving Report";
						$reference_table			=	"inventory_deliveries";
						$column_names['created_on']	=	"tdate";
					}
					elseif($_GET['journal_type']=="sales") {
						$entity						=	"Statement of Account";
						$reference_table			=	"statement_of_accounts";
						$column_names['created_on']	=	"created_on";
						$documents					=	"Statement of account";
					}
					elseif($_GET['journal_type']=="cash receipt") {
						$entity						=	"Payment Reciept";
						$reference_table			=	"payments";
						$column_names['created_on']	=	"received_on";
						$documents					=	"reciepts";
					}
					elseif($_GET['journal_type']=="disbursement") {
						$entity						=	"Check Voucher";
						$reference_table			=	"ac_vouchers";
						$column_names['created_on']	=	"voucher_date";
					}

					else {
						$this->Abas->sysMsg("errmsg", "Invalid report type - ".$_GET['journal_type']);
						$this->Abas->redirect($previous_page);
					}
					$sql			=	"SELECT * FROM ".$reference_table." WHERE ".$column_names['created_on'].">='".$date_start."' AND ".$column_names['created_on']."<='".$date_finish."' ".$company_query." ORDER BY ".$column_names['created_on']." DESC";
					$documents		=	$this->db->query($sql);
					$documents		=	$documents->result_array();
				}
				if(!empty($documents)) {
					$data['documents']			=	$documents;
					$data['company']			=	$company;
					$data['entity']				=	$entity;
					$data['reference_table']	=	$reference_table;
					$data['column_names']		=	$column_names;
				}
			}
			$this->load->view($mainview, $data);
		}
		public function general_ledger($action="") {$data=array();
			$this->Abas->checkPermissions("accounting|view_reports");
			$previous_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:HTTP_PATH;
			if($action=="") {
				$data['companies']			=	$this->Abas->getCompanies();
				$mainview				=	"accounting/general_ledger/filter.php";
			}
			elseif($action=="print") {
				$mainview	=	"accounting/general_ledger/print.php";
			}
			elseif($action=="report") {
				if(empty($_GET)) {
					$this->Abas->redirect($previous_page);
				}
				$mainview			=	"gentlella_container.php";
				$data['viewfile']	=	"accounting/general_ledger/report.php";
				$company				=	(object)array("name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
				$company_query=$department_query=$vessel_query=$contract_query="";
				$business_unit_code=$department_account_code="00";
				$company_account_code=$vessel_account_code="000";
				$contract_account_code="0000";
				if(!isset($_GET['dstart']) || !isset($_GET['dfinish'])) {
					$this->Abas->sysMsg("warnmsg", "No report date selected!");
					$this->Abas->redirect($previous_page);
				}
				$date_start		=	date("Y-m-d", strtotime($_GET['dstart']))." 00:00:00";
				$date_finish	=	date("Y-m-d", strtotime($_GET['dfinish']))." 23:59:59";
				if(!isset($_GET['account'])) {
					$this->Abas->sysMsg("warnmsg", "No account selected!");
					$this->Abas->redirect($previous_page);
				}
				$daterange		=	array("start"=>$date_start, "finish"=>$date_finish);
				$account		=	$this->Accounting_model->getAccount($_GET['account'],$daterange);
				if($account==false) {
					$this->Abas->sysMsg("warnmsg", "Account not found!");
					$this->Abas->redirect($previous_page);
				}
				if(isset($_GET['company'])) {
					if(is_numeric($_GET['company'])) {
						$company		=	$this->Abas->getCompany($_GET['company']);
						if($company) {
							if($company->id==1){
								$company_query			=	' AND (company_id='.$company->id.' OR company_id=10)';
							}else{
								$company_query			=	' AND company_id='.$company->id;
							}
							$company_account_code	=	str_pad($company->id, 3, '0', STR_PAD_LEFT);
						}
					}
				}
				$data['company']		=	$company;
				if(isset($_GET['department'])) {
					if(is_numeric($_GET['department'])) {
						$department			=	$this->Abas->getDepartment($_GET['department']);
						if($department) {
							$department_query			=	' AND department_id='.$department->id;
							$department_account_code	=	str_pad($department->accounting_code, 2, '0', STR_PAD_LEFT);
						}
					}
				}
				if(isset($_GET['vessel'])) {
					if(is_numeric($_GET['vessel'])) {
						$vessel			=	$this->Abas->getVessel($_GET['vessel']);
						if($vessel){
							$vessel_query			=	' AND vessel_id='.$vessel->id;
							$vessel_account_code	=	str_pad($vessel->id, 3, '0', STR_PAD_LEFT);
						}
					}
				}
				if(isset($_GET['contract'])) {
					if(is_numeric($_GET['contract'])) {
						$contract				=	$this->Abas->getContract($_GET['contract']);
						$contract_query			=	' AND contract_id='.$contract['id'];
						$contract_account_code	=	str_pad($contract['reference_no'], 4, '0', STR_PAD_LEFT);
					}
				}
				$tablecontents	=	'';
				$sql			=	"SELECT id FROM ac_transaction_journal WHERE posted_on>='".$date_start."' AND posted_on<='".$date_finish."' AND coa_id='".$account['id']."' ".$company_query.$department_query.$vessel_query.$contract_query." ORDER BY posted_on ASC";
				$entries		=	$this->db->query($sql);
				if(!empty($entries)) {
					$data['entries']			=	$entries;
					$data['company']			=	$company;
					$data['account']			=	$account;
				}
			}
			$this->load->view($mainview, $data);
		}
		public function autocomplete_account(){
			$table	=	"ac_accounts";
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM ".$table." WHERE code LIKE '__________".$search."%' OR name LIKE '%".$search."%' LIMIT 0, 10";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						$ret[$ctr]['label']	=	$i['financial_statement_code'].$i['general_ledger_code']." | ".$i['name'];
						if(isset($i['id'])) {
							$ret[$ctr]['account_code']	=	$i['financial_statement_code'].$i['general_ledger_code'];
							$ret[$ctr]['account_long_code']	=	$i['code'];
							$ret[$ctr]['account_name']	=	$i['name'];
							$ret[$ctr]['value']	=	$i['id'];
						}
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function migrate() {$data=array(); //used to migrate service provider data to supplier's table
			if(ENVIRONMENT!="development") { $this->Abas->redirect(HTTP_PATH); } // works only on dev! do NOT run on prod unless you're sure!
			$db		=	$this->db->query("SELECT * FROM service_providers");
			$data	= 	$db->result_array();
			foreach($data as $d){
				$sql = 'INSERT
						INTO suppliers(id, name, address, tin, type)
						VALUES(0,"'.$d['company_name'].'","'.$d['address'].'","'.$d['tin'].'","Services" )
						'
						;
				//$sql = 'UPDATE service_providers SET address = "", region ="'.$d['address'].'" WHERE id ='.$d['id'];
				//$db		=	$this->db->query($sql);
				echo $sql.'<br>';
				//echo $d['company_name'].'|';
				//echo $d['address'].'|';
				//echo $d['tin'].'<br>';
			}
		}
		public function remove_zero_gl_accounts() {
			if(ENVIRONMENT!="development") { $this->Abas->redirect(HTTP_PATH); } // works only on dev! do NOT run on prod unless you're sure!
			$accounts	=	$this->db->query("SELECT * FROM ac_accounts");
			if($accounts) {
				if($accounts=$accounts->result_array()) {
					foreach($accounts as $account) {
						$gl	=	substr($account['code'], 10,4);
						if($gl=="0000") {
							$this->db->query("DELETE FROM ac_accounts WHERE id=".$account['id']);
						}
					}
				}
			}
			$this->Abas->redirect(HTTP_PATH."accounting/all_accounts_are_parents");
		}
		public function all_accounts_are_parents() {
			if(ENVIRONMENT!="development") { $this->Abas->redirect(HTTP_PATH); } // works only on dev! do NOT run on prod unless you're sure!
			$accounts	=	$this->db->query("SELECT * FROM ac_accounts");
			if($accounts) {
				if($accounts=$accounts->result_array()) {
					$this->db->query("truncate ac_account_xref");
					foreach($accounts as $account) {
						$sql	=	"INSERT INTO ac_account_xref (parent_id, child_id) VALUES (0, ".$account['id'].")";
						echo $sql."<br/>";
						$this->db->query($sql);
					}
				}
			}
			$this->Abas->redirect(HTTP_PATH."accounting");
		}
		public function load($table = NULL, $reference_table = NULL, $posted=0){
			$data = array();
			if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
				$search	=	isset($_GET['search'])?$_GET['search']:"";
				$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
				$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
				$order	=	isset($_GET['order'])?$_GET['order']:"";
				$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
				if($table=="statement_of_accounts" && $reference_table==NULL){
					$table = "ac_accounts_receivable_for_clearing";
				}
				if($table=="ac_transactions" && $reference_table=="statement_of_accounts"){
					if($posted==0){
						$table = "ac_accounts_receivable_for_posting";
					}
					else{
						$table = "ac_accounts_receivable_posted";
					}
				}
				if($table=="payments" && $reference_table==NULL){
					$table = "ac_accounts_collection_for_clearing";
				}
				if($table=="ac_transactions" && $reference_table=="payments"){
					if($posted==0){
						$table = "ac_accounts_collection_for_posting";
					}
					else{
						$table = "ac_accounts_collection_posted";
					}
				}
				$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);
				foreach($data['rows'] as $ctr=>$row){
					if(isset($row['status']) && $row['status']=='Approved' || isset($row['status']) && $row['status']=='Waiting for Payment'){
						$data['rows'][$ctr]['status']	=	"For Clearing";
						$soa_amount = $this->Billing_model->getSOAAmount($row['type'],$row['id']);
						$data['rows'][$ctr]['total_amount']	=	number_format($soa_amount['grandtotal_tax'],2,'.',',');
					}
					if(isset($row['status']) && $row['status']=='For Deposit' || isset($row['status']) && $row['status']=='Deposited'){
						$OR = $this->Collection_model->getOfficialReceipts($row['id']);
						$arr1 = array();
						foreach($OR as $num1){
							$arr1[] = $num1->control_number;
						}
						$OR_str = implode(', ',$arr1);
						$AR = $this->Collection_model->getAcknowledgementReceipts($row['id']);
						$arr2 = array();
						foreach($AR as $num2){
							$arr2[] = $num2->control_number;
						}
						$AR_str = implode(', ',$arr2);
						$data['rows'][$ctr]['OR_number'] = $OR_str;
						$data['rows'][$ctr]['AR_number'] = $AR_str;
						$data['rows'][$ctr]['status']	=	"For Clearing";
					}
					if(isset($row['stat']) && $row['stat']==0){
						$data['rows'][$ctr]['status']	=	"For Posting";
					}
					if(isset($row['stat']) && $row['stat']==1){
						$data['rows'][$ctr]['status']	=	"Posted";
						$count = 0;
						$transaction_journal_entries =	$this->Accounting_model->getTransactionJournalEntries($row['transaction_id']);
						foreach($transaction_journal_entries as $rowx){
							$date_posted = date('Y-m-d',strtotime($rowx['posted_on']));
							if($rowx['reference_table']=='payments' && $rowx['coa_id']==10 && $rowx['stat']==1){
								$count = $count + 1;
							}
						}
						$data['rows'][$ctr]['date_posted'] = $date_posted;
						if($count>0){
							$data['rows'][$ctr]['reconciled']	=	"Yes";
						}
						else{
							$data['rows'][$ctr]['reconciled']	=	"No";
						}
					}
					if(isset($row['created_by'])) {
						$created_by							=	$this->Abas->getUser($row['created_by']);
						$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
						$data['rows'][$ctr]['served_by']	=	$created_by['user_location'];
					}
					if(isset($row['trans_created_by'])) {
						$created_by							=	$this->Abas->getUser($row['trans_created_by']);
						$data['rows'][$ctr]['trans_created_by']	=	$created_by['full_name'];
					}
					if(isset($row['created_on'])) {
						$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($row['created_on']));
					}
					if(isset($row['trans_created_on'])) {
						$data['rows'][$ctr]['trans_created_on']	=	date("j F Y h:i A", strtotime($row['trans_created_on']));
					}
					if(isset($row['client_id'])) {
						$client								=	$this->Abas->getClient($row['client_id']);
						$data['rows'][$ctr]['client_name']	=	$client['company'];
					}
					if(isset($row['company_id'])) {
						$company							=	$this->Abas->getCompany($row['company_id']);
						$data['rows'][$ctr]['company_name']	=	$company->name;
					}
					if(isset($row['contract_id'])) {
						$contract							=	$this->Abas->getContract($row['contract_id']);
						$data['rows'][$ctr]['contract']		=	$contract['reference_no'];
					}
					if(isset($row['reference_id'])) {
						$soa	=	$this->Billing_model->getStatementOfAccount($row['reference_id']);
						$data['rows'][$ctr]['soa_number']			=	$soa['control_number'];
						$data['rows'][$ctr]['reference_number']		=	$soa['reference_number'];
						$data['rows'][$ctr]['client_name']			=	$soa['client']['company'];
						$data['rows'][$ctr]['type']					=	$soa['type'];
						$data['rows'][$ctr]['services']				=	$soa['services'];
						$soa_amount 	=  $this->Billing_model->getSOAAmount($soa['type'],$row['reference_id']);
						$data['rows'][$ctr]['total_amount']			= number_format($soa_amount['grandtotal_tax'],2,'.',',');
							$soa_amount;
					}
					if(isset($row['soa_id'])){
						$soa = $this->Billing_model->getStatementOfAccount($row['soa_id']);
						$data['rows'][$ctr]['soa_number']		=	$soa['control_number'];
					}
					if(isset($row['received_on'])) {
						$data['rows'][$ctr]['received_on']	=	date("j F Y h:i A", strtotime($row['received_on']));
					}
					if(isset($row['received_by'])) {
						$received_by						=	$this->Abas->getUser($row['received_by']);
						$data['rows'][$ctr]['received_by']	=	$received_by['full_name'];
					}
					if(isset($row['net_amount'])) {
						$data['rows'][$ctr]['total_amount']	=	number_format($row['net_amount'],2,'.',',');
					}
				}
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			}
		}
		public function listview( $type = NULL, $status){

			$this->Abas->checkPermissions("accounting|view_vouchers");

			switch($type){

				case "accounts_receivables":
					if($status=='for_clearing'){
						$data['viewfile'] ='accounting/vouchers/accounts_receivables/listview_for_clearing.php';
					}elseif($status=='for_posting'){
						$data['viewfile'] ='accounting/vouchers/accounts_receivables/listview_for_posting.php';
					}else{
						$data['viewfile'] ='accounting/vouchers/accounts_receivables/listview_posted.php';
					}
				break;

				case "accounts_collection";
					if($status=='for_clearing'){
						$data['viewfile'] ='accounting/vouchers/accounts_collection/listview_for_clearing.php';
					}elseif($status=='for_posting'){
						$data['viewfile'] ='accounting/vouchers/accounts_collection/listview_for_posting.php';
					}else{
						$data['viewfile'] ='accounting/vouchers/accounts_collection/listview_posted.php';
					}
				break;

			}

			$this->load->view('gentlella_container.php',$data);

		}
		public function add( $type = NULL, $id = NULL){

			$this->Abas->checkPermissions("accounting|view_vouchers");

			switch($type){

				case "accounts_receivables":

					$data['SOA'] 				= 	$this->Billing_model->getStatementOfAccount($id);
					$data['SOA_amount'] 		= 	$this->Billing_model->getSOAAmount($data['SOA']['type'],$id);

					$data['vessels']			=	$this->Abas->getVesselsByCompany($data['SOA']['company_id']);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($data['SOA']['company_id']);

					$this->load->view('accounting/vouchers/accounts_receivables/form.php',$data);

				break;

				case "accounts_collection";

					$data['payment']			=	$this->Collection_model->getPayment($id);

					$data['vessels']			=	$this->Abas->getVesselsByCompany($data['payment']['company_id']);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($data['payment']['company_id']);


					if($data['payment']['payment_type']=='For Billing'){
						$transaction = $this->Accounting_model->getTransactionJournalEntriesByReference('statement_of_accounts',$data['payment']['soa_id']);
						if($transaction){
							$data['transaction_journal_entries']=	$this->Accounting_model->getTransactionJournalEntries($transaction[0]['transaction_id']);
						}
					}


					$this->load->view('accounting/vouchers/accounts_collection/form.php',$data);

				break;

			}

		}
		public function insert( $type = NULL){

			$this->Abas->checkPermissions("accounting|view_vouchers");

			switch($type){

				case "accounts_receivables":

					$SOA = $this->Billing_model->getStatementOfAccount($this->Mmm->sanitize($_POST['soa_id']));
					$soa_id = $SOA['id'];
					$soa_control_number = $SOA['control_number'];
					$company_name = $SOA['company']->name;
					$client_name = $SOA['client']['company'];

					$transaction = array();
					$transaction['date']		=	date("Y-m-d H:i:s");//$SOA['created_on'];
					$transaction['company_id']	=	$this->Mmm->sanitize($_POST['company_id']);
					$remark						=	"SOA #".$soa_control_number. "(".$company_name.") for billing of ".$SOA['services']." services to client: ".$client_name;
					$transaction['remark']		=	$remark;
					$transaction['status']		=	"Active";
					$transaction['stat']		=	0;
					$transaction['reference_table'] 	= 'statement_of_accounts';
					$transaction['reference_id'] 		= $soa_id;
					$transaction['created_on']	=	date("Y-m-d H:i:s");
					$transaction['created_by']	=	$_SESSION['abas_login']['userid'];

					$checkTransaction			=	$this->Mmm->dbInsert("ac_transactions", $transaction, "Added new transaction for billing with SOA with Transaction Code No.".$soa_id);

					$last_transaction_id = $this->Abas->getLastIDByTable('ac_transactions');

					if($checkTransaction){

						$multiAttach = array();

						$target_dir = WPATH.'assets/uploads/accounting/attachments/';

						foreach($_POST['attachment'] as $ctr=>$val){

							$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
							$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);

							if(end($old_filename)!=""){
								$multiAttach[$ctr]['transaction_id']	=	$last_transaction_id;
								$multiAttach[$ctr]['document_name'] 	= 	$this->Mmm->sanitize($_POST['attachment'][$ctr]);
								$multiAttach[$ctr]['document_file'] 	= 	$new_filename;

								$target_file = $target_dir . $new_filename;
								$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
							}
						}

						if(count($multiAttach)>0){
							$checkAttach = $this->Mmm->multiInsert("ac_transaction_attachments",$multiAttach,'Inserted attachments for Account Receivables with Transaction Code No. ' . $last_transaction_id);
						}else{
							$checkAttach = TRUE;
						}

						if($checkAttach){

							$entry = array();
							$multiInsert = array();

							foreach($_POST['coa_id'] as $ctr=>$val){

								$entry['transaction_id'] 	= $last_transaction_id;
								$entry['remark'] 			= $remark;
								$entry['company'] 			= $this->Mmm->sanitize($_POST['company_id']);
								$entry['reference_table'] 	= 'statement_of_accounts';
								$entry['reference_id'] 		= $soa_id;
								$entry['posted_on']			= NULL;
								$entry['debit_amount']		= $this->Mmm->sanitize($_POST['debit'][$ctr]);
								$entry['credit_amount']		= $this->Mmm->sanitize($_POST['credit'][$ctr]);

								$department = $this->Accounting_model->getDepartmentIDByAccountingCode($this->Mmm->sanitize($_POST['department'][$ctr]));

								$entry['department']		= isset($department->id)?$department->id:0;
								$entry['vessel']			= $this->Mmm->sanitize($_POST['vessel'][$ctr]);
								$entry['contract']			= $this->Mmm->sanitize($_POST['contract'][$ctr]);
								$entry['account']			= $this->Mmm->sanitize($_POST['coa_id'][$ctr]);
								$entry['stat']				= 0;

								$checkEntry = $this->Accounting_model->newJournalEntry($entry);
							}

							if($checkEntry){

								$this->Mmm->query("UPDATE statement_of_accounts SET is_cleared=1 WHERE id=".$soa_id, "Cleared SOA on Accounting.");

								$this->Abas->sysNotif("Accounts Receivables", $_SESSION['abas_login']['fullname']." has successfully cleared SOA No." . $soa_control_number . " under company " . $company_name . " for client " . $client_name,"Accounting","info");

								$this->Abas->sysMsg("sucmsg","Successfully processed the clearing for SOA No." . $soa_control_number . " under company " . $company_name . " for client " . $client_name);
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the Accounts Receivables for this SOA! Please try again.");
								$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_receivables");
								die();
							}

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while uploading the attachments for this Accounts Receivables! Please try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_receivables");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the Accounts Receivables for this SOA! Please try again.");
						$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_receivables");
							die();
					}

					//$this->Mmm->debug($uploaded);
					$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_receivables/for_clearing");

				break;

				case "accounts_collection";

					$payment =	$this->Collection_model->getPayment($this->Mmm->sanitize($_POST['payment_id']));

					$OR = $this->Collection_model->getOfficialReceipts($payment['id']);
					$arr1 = array();
					foreach($OR as $num1){
						$arr1[] = $num1->control_number;
					}
					$OR_str = implode(', ',$arr1);

					if($OR_str!=""){
						$control_number = "OR #".$OR_str;
					}else{

						$AR = $this->Collection_model->getAcknowledgementReceipts($payment['id']);
						$arr2 = array();
						foreach($AR as $num2){
							$arr2[] = $num2->control_number;
						}
						$AR_str = implode(', ',$arr2);

						$control_number = "AR #".$AR_str;
					}

					//get the transaction id of current statement of account
					$transaction_id = $this->Accounting_model->getJournalTransactionIDByReference('statement_of_accounts',$payment['soa_id'])->transaction_id;

					$payment_id = $payment['id'];
					$company_name = $payment['company_name'];
					$payor = $payment['payor'];


					$remark	= $control_number. "(".$company_name.") for the payment of ".$payment['particulars']." by client: ".$payor;


					if($payment['payment_type']=='For Others'){

						$transaction = array();
						$transaction['date']		=	date("Y-m-d H:i:s");//$payment['received_on'];
						$transaction['company_id']	=	$this->Mmm->sanitize($_POST['company_id']);
						$transaction['remark']		=	$this->Mmm->sanitize($remark);
						$transaction['status']		=	"Active";
						$transaction['stat']		=	0;
						$transaction['reference_table'] 	= 'payments';
						$transaction['reference_id'] 		= $payment_id;
						$transaction['created_on']	=	date("Y-m-d H:i:s");
						$transaction['created_by']	=	$_SESSION['abas_login']['userid'];

						$checkTransaction			=	$this->Mmm->dbInsert("ac_transactions", $transaction, "Added new transaction for payment with Transaction Code No.".$payment_id);

						$transaction_id = $this->Abas->getLastIDByTable('ac_transactions');

					}else{
						$checkTransaction = TRUE;
					}

					if($checkTransaction){

						$multiAttach = array();

						$target_dir = WPATH.'assets/uploads/accounting/attachments/';

						foreach($_POST['attachment'] as $ctr=>$val){

							$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
							$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);

							if(end($old_filename)!=""){
								$multiAttach[$ctr]['transaction_id']	=	$transaction_id;//$last_transaction_id;
								$multiAttach[$ctr]['document_name'] 	= 	$this->Mmm->sanitize($_POST['attachment'][$ctr]);
								$multiAttach[$ctr]['document_file'] 	= 	$new_filename;

								$target_file = $target_dir . $new_filename;
								$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
							}

						}

						if(count($multiAttach)>0){
							$checkAttach = $this->Mmm->multiInsert("ac_transaction_attachments",$multiAttach,'Inserted attachments for Account Collection for Transaction No. ' . $transaction_id);
						}else{
							$checkAttach = TRUE;
						}

						if($checkAttach){

							$entry = array();
							$multiInsert = array();

							foreach($_POST['coa_id'] as $ctr=>$val){

								$entry['transaction_id'] 	= $transaction_id;//$last_transaction_id;
								$entry['remark'] 			= $this->Mmm->sanitize($remark);
								$entry['company'] 			= $this->Mmm->sanitize($_POST['company_id']);
								$entry['reference_table'] 	= 'payments';
								$entry['reference_id'] 		= $payment_id;
								$entry['posted_on']			= NULL;
								$entry['debit_amount']		= $this->Mmm->sanitize($_POST['debit'][$ctr]);
								$entry['credit_amount']		= $this->Mmm->sanitize($_POST['credit'][$ctr]);

								$department = $this->Accounting_model->getDepartmentIDByAccountingCode($this->Mmm->sanitize($_POST['department'][$ctr]));

								$entry['department']		= isset($department->id)?$department->id:0;
								$entry['vessel']			= $this->Mmm->sanitize($_POST['vessel'][$ctr]);
								$entry['contract']			= $this->Mmm->sanitize($_POST['contract'][$ctr]);
								$entry['account']			= $this->Mmm->sanitize($_POST['coa_id'][$ctr]);
								$entry['stat']				= 0;

								$checkEntry = $this->Accounting_model->newJournalEntry($entry);

							}


								if($checkEntry){

									$this->Mmm->query("UPDATE payments SET is_cleared=1 WHERE id=".$payment_id, "Cleared Payment on Accounting.");

									$this->Abas->sysNotif("Accounts Collection", $_SESSION['abas_login']['fullname']." has successfully cleared " . $control_number . " under payor " . $company_name . " for client " . $payor,"Accounting","info");

									$this->Abas->sysMsg("sucmsg","Successfully processed the clearing for " . $control_number . " under company " . $company_name . " for payor " . $payor);

									//gets the total amount paid for the SOA
									$total_payments = $this->Billing_model->getSOAPayments($payment['soa_id'])->total_payments;

									//gets the SOA amount
									$SOA 	= 	$this->Billing_model->getStatementOfAccount($payment['soa_id']);
									$SOA_amount = 	$this->Billing_model->getSOAAmount($SOA['type'],$SOA['id']);

									//if SOA is fully paid then do the reconcilation otherwise nothing
									$reconciled = FALSE;
									if(number_format($total_payments,2,'.','') == number_format($SOA_amount['grandtotal_tax'],2,'.','')){


										if($payment['payment_type']=="For Billing"){
											//For reconciling Trade Receivables (COA id=10)
											$reconciled = $this->Accounting_model->reconcileEntries($transaction_id,10);
										}else{
											$reconciled = TRUE;
										}

										if($reconciled==FALSE){

											$this->Abas->sysMsg("warnmsg","Entries were not reconciled due to error. Contact Administrator immediately.");

										}else{

											$this->Abas->sysMsg("sucmsg","Succesfully reconciled the entries for this transaction.");
										}
									}else{
											if($payment['payment_type']=="For Billing"){
												$this->Abas->sysMsg("warnmsg","Entries for this transaction were not yet reconciled since the SOA amount is not yet fully-paid.");
											}
									}


								}else{
									$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the Accounts Collection for this payment! Please try again.");
									$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_collection");
									die();
								}

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while uploading the attachments for this Accounts Collection! Please try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_collection");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the Accounts Collection for this payment! Please try again.");
						$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_collection");
						die();
					}

					//$this->Mmm->debug(number_format($total_payments,2,'.','') ." == ". number_format($SOA_amount['grandtotal_tax'],2,'.','') . " transaction_id:" .$transaction_id. " reconciled??:" .$reconciled);
					$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_collection/for_clearing");

				break;

			}

		}
		public function view( $type = NULL, $reference_id = NULL, $transaction_id = NULL){

			$this->Abas->checkPermissions("accounting|view_vouchers");

			switch($type){

				case "accounts_receivables":

					$data['SOA'] 				= 	$this->Billing_model->getStatementOfAccount($reference_id);
					$data['SOA_amount'] 		= 	$this->Billing_model->getSOAAmount($data['SOA']['type'],$reference_id);

					if($transaction_id!=NULL){
						$data['transaction']		=	$this->Accounting_model->getTransaction($transaction_id);
						$data['transaction_journal_entries']=	$this->Accounting_model->getTransactionJournalEntries($transaction_id);
						$data['transaction_attachments'] =	$this->Accounting_model->getTransactionAttachments($transaction_id);
					}

					$data['reconciling_entries'] = $this->Accounting_model->getReconcilingEntries($reference_id);

					$data['vessels']			=	$this->Abas->getVesselsByCompany($data['SOA']['company_id']);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($data['SOA']['company_id']);

					$this->load->view('accounting/vouchers/accounts_receivables/form.php',$data);

				break;

				case "accounts_collection";

					$data['payment']			=	$this->Collection_model->getPayment($reference_id);

					if($transaction_id!=NULL){
						$data['transaction']		=	$this->Accounting_model->getTransaction($transaction_id);
						$data['transaction_journal_entries']=	$this->Accounting_model->getTransactionJournalEntries($transaction_id);
						$data['transaction_attachments']=	$this->Accounting_model->getTransactionAttachments($transaction_id);
					}

					$data['vessels']			=	$this->Abas->getVesselsByCompany($data['payment']['company_id']);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($data['payment']['company_id']);


					$this->load->view('accounting/vouchers/accounts_collection/form.php',$data);

				break;

			}

		}
		public function update( $type = NULL, $reference_id = NULL, $transaction_id = NULL){

			$this->Abas->checkPermissions("accounting|view_vouchers");

			switch($type){

				case "accounts_receivables":

					//approve the account receivables
					$SOA = 	$this->Billing_model->getStatementOfAccount($reference_id);
					$control_number = $SOA['control_number'];
					$company_name = $SOA['company']->name;
					$client_name = $SOA['client']['company'];

					$sql_transaction = "UPDATE ac_transactions SET stat=1 WHERE id=".$transaction_id;
					$query_transaction = $this->db->query($sql_transaction);

					$posted_by = $_SESSION['abas_login']['userid'];
					$posted_on = $SOA['created_on'];//date("Y-m-d H:i:s");

					if($query_transaction){
						$sql_entry = "UPDATE ac_transaction_journal SET stat=1, posted_on='".$posted_on."', posted_by=".$posted_by." WHERE transaction_id=".$transaction_id. " AND reference_id=".$reference_id;
						$query_entry = $this->db->query($sql_entry);
					}

					if($query_transaction && $query_entry){
						$this->Abas->sysNotif("Accounts Receivables", $_SESSION['abas_login']['fullname']." has approved Accounts Receivables for SOA No." . $control_number . " under company " . $company_name . " for client " . $client_name,"Accounting","info");

						$this->Abas->sysMsg("sucmsg","Successfully approved Accounts Receivables for SOA No." . $control_number . " under company " . $company_name . " for client " . $client_name);
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while updating the status of Accounts Receivables for this SOA! Please try again.");
					}

					//$this->Mmm->debug($transaction_id);
					$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_receivables/for_posting");

				break;

				case "accounts_collection";

					//approve the account collection
					$payment			=	$this->Collection_model->getPayment($reference_id);

					$OR = $this->Collection_model->getOfficialReceipts($reference_id);
					$arr1 = array();
					foreach($OR as $num1){
						$arr1[] = $num1->control_number;
					}
					$OR_str = implode(', ',$arr1);
					$control_number = $OR_str;

					$company_name = $payment['name'];
					$payor = $payment['payor'];

					$sql_transaction = "UPDATE ac_transactions SET stat=1 WHERE id=".$transaction_id;
					$query_transaction = $this->db->query($sql_transaction);

					$posted_by = $_SESSION['abas_login']['userid'];
					$posted_on = $payment['received_on'];//date("Y-m-d H:i:s");

					if($query_transaction){
						$sql_entry = "UPDATE ac_transaction_journal SET stat=1, posted_on='".$posted_on."', posted_by=".$posted_by." WHERE transaction_id=".$transaction_id . " AND reference_id=".$reference_id;
						$query_entry = $this->db->query($sql_entry);
					}

					if($query_transaction && $query_entry){
						$this->Abas->sysNotif("Accounts Collection", $_SESSION['abas_login']['fullname']." has approved Accounts Collection for OR No." . $control_number . " under company " . $company_name . " for client " . $payor,"Accounting","info");

						$this->Abas->sysMsg("sucmsg","Successfully approved Accounts Collection for OR No." . $control_number . " under company " . $company_name . " for client " . $payor);
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while updating the status of Accounts Collection for this payment! Please try again.");
					}

					//$this->Mmm->debug($transaction_id);
					$this->Abas->redirect(HTTP_PATH."accounting/listview/accounts_collection/for_posting");

				break;
			}
		}
		public function subsidiary_ledger($action="") {$data=array();
			$previous_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:HTTP_PATH;
			if($action=="filter") {
				$data['companies']			=	$this->Abas->getCompanies();
				$mainview				=	"accounting/subsidiary_ledger/filter.php";
			}
			elseif($action=="print") {
				$mainview	=	"accounting/subsidiary_ledger/print.php";
			}
			elseif($action=="report") {
				if(empty($_GET)) {
					$this->Abas->redirect($previous_page);
				}
				$mainview			=	"gentlella_container.php";
				$data['viewfile']	=	"accounting/subsidiary_ledger/report.php";
				$company				=	(object)array("name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
				$company_query=$department_query=$vessel_query=$contract_query="";
				$business_unit_code=$department_account_code="00";
				$company_account_code=$vessel_account_code="000";
				$contract_account_code="0000";
				if(!isset($_GET['dstart']) || !isset($_GET['dfinish'])) {
					$this->Abas->sysMsg("warnmsg", "No report date selected!");
					$this->Abas->redirect($previous_page);
				}
				$date_start		=	date("Y-m-d", strtotime($_GET['dstart']))." 00:00:00";
				$date_finish	=	date("Y-m-d", strtotime($_GET['dfinish']))." 23:59:59";
				if(!isset($_GET['account'])) {
					$this->Abas->sysMsg("warnmsg", "No account selected!");
					$this->Abas->redirect($previous_page);
				}
				$daterange		=	array("start"=>$date_start, "finish"=>$date_finish);
				$account		=	$this->Accounting_model->getAccount($_GET['account'],$daterange);
				if($account==false) {
					$this->Abas->sysMsg("warnmsg", "Account not found!");
					$this->Abas->redirect($previous_page);
				}
				if(isset($_GET['company'])) {
					if(is_numeric($_GET['company'])) {
						$company		=	$this->Abas->getCompany($_GET['company']);
						if($company) {
							if($company->id==1){
								$company_query			=	' AND (company_id='.$company->id.' OR company_id=10)';
							}else{
								$company_query			=	' AND company_id='.$company->id;
							}
							$company_account_code	=	str_pad($company->id, 3, '0', STR_PAD_LEFT);
						}
					}
				}
				$data['company']		=	$company;
				if(isset($_GET['department'])) {
					if(is_numeric($_GET['department'])) {
						$department			=	$this->Abas->getDepartment($_GET['department']);
						if($department) {
							$department_query			=	' AND department_id='.$department->id;
							$department_account_code	=	str_pad($department->accounting_code, 2, '0', STR_PAD_LEFT);
						}
					}
				}
				if(isset($_GET['vessel'])) {
					if(is_numeric($_GET['vessel'])) {
						$vessel			=	$this->Abas->getVessel($_GET['vessel']);
						if($vessel){
							$vessel_query			=	' AND vessel_id='.$vessel->id;
							$vessel_account_code	=	str_pad($vessel->id, 3, '0', STR_PAD_LEFT);
						}
					}
				}
				if(isset($_GET['contract'])) {
					if(is_numeric($_GET['contract'])) {
						$contract				=	$this->Abas->getContract($_GET['contract']);
						$contract_query			=	' AND contract_id='.$contract['id'];
						$contract_account_code	=	str_pad($contract['reference_no'], 4, '0', STR_PAD_LEFT);
					}
				}
				$tablecontents	=	'';
				$sql			=	"SELECT id FROM ac_transaction_journal WHERE stat=1 AND posted_on>='".$date_start."' AND posted_on<='".$date_finish."' AND coa_id='".$account['id']."' ".$company_query." ORDER BY transaction_id";
				$this->Mmm->debug($sql);
				$entries		=	$this->db->query($sql);
				if(!empty($entries)) {
					$data['entries']			=	$entries;
					$data['company']			=	$company;
					$data['account']			=	$account;
				}
			}
			$this->load->view($mainview, $data);
		}
		public function summary_report($type){

			$this->Abas->checkPermissions("accounting|view_reports");
			$data = array();

			if($type=='official_receipts' || $type=='acknowledgement_receipts'){

				if(isset($_POST['date_from'])){
					$date_from = $this->Mmm->sanitize($_POST['date_from']);
				}else{
					$date_from=NULL;
				}
				if(isset($_POST['date_to'])){
					$date_to = $this->Mmm->sanitize($_POST['date_to']);
				}else{
					$date_to=NULL;
				}
				if(isset($_POST['company'])){
					$company = $this->Mmm->sanitize($_POST['company']);
				}else{
					$company=NULL;
				}

				$data['receipts'] = $this->Accounting_model->getPaymentReceipts($type,$date_from,$date_to,$company);
				$data['viewfile']	=	"accounting/summary_report/".$type."/listview.php";
				$data['date_from'] = $date_from;
				$data['date_to'] = $date_to;
				$data['company'] = $company;

			}elseif($type=="MSIS" || $type=="MSIS_consolidated"){


					if(isset($_POST['date_from']) && isset($_POST['date_to'])){
						if($_POST['filter']=="" && $_POST['location']!=""){
							$type = "MSIS";
							$date_from = $_POST['date_from'];
							$date_to = $_POST['date_to'];
							$location = $_POST['location'];
							$filter = null;

							//for consolidated per company
							if($_POST['company']!=""){
								$type = "MSIS_consolidated";
								$filter = $_POST['company'];
							}
						}

						if($_POST['filter']!="" && $_POST['location']!=""){
							$type = "MSIS";
							$date_from = $_POST['date_from'];
							$date_to = $_POST['date_to'];
							$location = $_POST['location'];
							$filter = $_POST['filter'];
						}

						if($_POST['filter']=="" && $_POST['location']==""){
							$type = "MSIS";
							$date_from = $_POST['date_from'];
							$date_to = $_POST['date_to'];
							$location = null;
							$filter = null;

							//for consolidated per company
							if($_POST['company']!=""){
								$type = "MSIS_consolidated";
								$filter = $_POST['company'];
							}
						}

						if($_POST['filter']!="" && $_POST['location']==""){
							$type = "MSIS";
							$date_from = $_POST['date_from'];
							$date_to = $_POST['date_to'];
							$location = null;
							$filter = $_POST['filter'];

						}
					}
					elseif(!isset($_POST['date_from']) && !isset($_POST['date_to'])){

						if(!isset($_POST['filter']) && !isset($_POST['location'])){
							$type = "MSIS";
							$date_from = null;
							$date_to = null;
							$location = null;
							$filter = null;

							//for consolidated per company
							if(isset($_POST['company'])){
								$type = "MSIS_consolidated";
								$filter = $_POST['company'];
							}else{
								$type = "MSIS";
							}

						}
						else{
							if($_POST['filter']=="" && $_POST['location']!=""){
								$type = "MSIS";
								$date_from = null;
								$date_to = null;
								$location = $_POST['location'];
								$filter = null;

								//for consolidated per company
								if(isset($_POST['company'])){
									$type = "MSIS_consolidated";
									$filter = $_POST['company'];
								}

							}

							if($_POST['filter']!="" && $_POST['location']!=""){
								$type = "MSIS";
								$date_from = null;
								$date_to = null;
								$location = $_POST['location'];
								$filter = $_POST['filter'];
							}

							if($_POST['filter']=="" && $_POST['location']==""){
								$type = "MSIS";
								$date_from = null;
								$date_to = null;
								$location = null;
								$filter = null;

								//for consolidated per company
								if(isset($_POST['company'])){
									$type = "MSIS_consolidated";
									$filter = $_POST['company'];
								}
							}

							if($_POST['filter']!="" && $_POST['location']==""){
								$type = "MSIS";
								$date_from = null;
								$date_to = null;
								$location = null;
								$filter = $_POST['filter'];
							}
						}

					}

				$data = $this->Accounting_model->getMaterialSuppliesIssuances($type,$date_from,$date_to,$location,$filter);
				$data['viewfile']	=	"accounting/summary_report/material_and_supplies_issuance/listview.php";

			}

			$this->load->view("gentlella_container.php",$data);

		}
		public function filter_summary_report($type){

			$data = array();

			$data['type'] = $type;
			$data['companies'] = $this->Abas->getCompanies(false);

			if($type=="official_receipts"){
				$this->load->view("accounting/summary_report/official_receipts/filter.php",$data);
			}elseif($type=="acknowledgement_receipts"){
				$this->load->view("accounting/summary_report/acknowledgement_receipts/filter.php",$data);
			}elseif($type=="MSIS" || $type=="MSIS_consolidated"){
				$data['locations']=$this->Inventory_model->getInventoryLocation();
				$this->load->view('accounting/summary_report/material_and_supplies_issuance/filter.php',$data);
			}

		}
		public function print_summary_report($type){

			require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

			$data = array();

			if($type=='official_receipts' || $type=='acknowledgement_receipts'){

				if($_GET['date_from']!=""){
					$date_from = $this->Mmm->sanitize($_GET['date_from']);
				}else{
					$date_from=NULL;
				}
				if($_GET['date_to']!=""){
					$date_to = $this->Mmm->sanitize($_GET['date_to']);
				}else{
					$date_to=NULL;
				}
				if($_GET['company']!=""){
					$company = $this->Mmm->sanitize($_GET['company']);
				}else{
					$company=NULL;
				}

				$data['receipts'] = $this->Accounting_model->getPaymentReceipts($type,$date_from,$date_to,$company);
				$data['date_from'] = $date_from;
				$data['date_to'] = $date_to;
				$data['company'] = $company;
				$this->load->view("accounting/summary_report/".$type."/print.php",$data);

			}elseif($type=="MSIS" || $type=="MSIS_consolidated"){


				if(isset($_GET['date_from']) && isset($_GET['date_to'])){
					if(!isset($_GET['filter']) && isset($_GET['location'])){
						$date_from = $_GET['date_from'];
						$date_to = $_GET['date_to'];
						$location = $_GET['location'];
						$filter = null;
					}

					if(isset($_GET['filter']) && isset($_GET['location'])){
						$date_from = $_GET['date_from'];
						$date_to = $_GET['date_to'];
						$location = $_GET['location'];
						$filter = $_GET['filter'];
					}

					if(!isset($_GET['filter']) && !isset($_GET['location'])){
						$date_from = $_GET['date_from'];
						$date_to = $_GET['date_to'];
						$location = null;
						$filter = null;
					}

					if(isset($_GET['filter']) && !isset($_GET['location'])){
						$date_from = $_GET['date_from'];
						$date_to = $_GET['date_to'];
						$location = null;
						$filter = $_GET['filter'];
					}
				}
				elseif(!isset($_GET['date_from']) && !isset($_GET['date_to'])){
					if(!isset($_GET['filter']) && isset($_GET['location'])){
						$date_from = null;
						$date_to = null;
						$location = $_GET['location'];
						$filter = null;
					}

					if(isset($_GET['filter']) && isset($_GET['location'])){
						$date_from = null;
						$date_to = null;
						$location = $_GET['location'];
						$filter = $_GET['filter'];
					}

					if(!isset($_GET['filter']) && !isset($_GET['location'])){
						$date_from = null;
						$date_to = null;
						$location = null;
						$filter = null;
					}

					if(isset($_GET['filter']) && !isset($_GET['location'])){
						$date_from = null;
						$date_to = null;
						$location = null;
						$filter = $_GET['filter'];
					}
				}

				$data = $this->Accounting_model->getMaterialSuppliesIssuances($type,$date_from,$date_to,$location,$filter);

				$this->load->view('accounting/summary_report/material_and_supplies_issuance/print.php',$data);
			}
		}
		public function vessels_by_company( $company_id ){
			$data['vessels'] = $this->Inventory_model->getVesselsByCompany($company_id);
			echo json_encode( $data['vessels'] );
		}

		public function inventory_issuances($action,$reference_table=NULL,$reference_id=NULL,$transaction_id=NULL){

			$this->Abas->checkPermissions("accounting|view_vouchers");

			switch($action){
				case "load":
					$data = array();
					//if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";

						if($reference_table=="for_clearing"){
							$table = "ac_inventory_issuances_for_clearing";
							$status = "For Clearing";
						}elseif($reference_table=="for_posting"){
							$table = "ac_inventory_issuances_for_posting";
							$status = "For Posting";
						}elseif($reference_table=="posted"){
							$table = "ac_inventory_issuances_posted";
							$status = "Posted";
						}

						$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);

						foreach($data['rows'] as $ctr=>$row){

							if(isset($row['vessel_id'])) {
								$vessel 							=	$this->Abas->getVessel($row['vessel_id']);
								$data['rows'][$ctr]['issued_for']	=	$vessel->name;
								$company							=	$this->Abas->getCompany($vessel->company);
								$data['rows'][$ctr]['company_name']	=	$company->name;
								$data['rows'][$ctr]['inventory_issuance_status']		=	$status;
								$posted =	$this->Accounting_model->getTransactionJournalEntriesByReference('inventory_issuance',$row['id']);
								$data['rows'][$ctr]['posted_on']		= date('Y-m-d',strtotime($posted[0]['posted_on']));

								$issued_items = $this->Inventory_model->getIssuanceDetails($row['id']);
								$amount =0;
								foreach($issued_items as $item){
									$amount = $amount + ($item['unit_price'] *  $item['qty']);
								}

								$data['rows'][$ctr]['total_amount']		= number_format($amount,2,'.',',');
							}

						}

						header('Content-Type: application/json');
						echo json_encode($data);

						exit();

					//}
				break;

				case "listview":

					if($reference_table=="for_clearing"){
						$data['viewfile'] ='accounting/vouchers/inventory_issuances/listview_for_clearing.php';
					}elseif($reference_table=="for_posting"){
						$data['viewfile'] ='accounting/vouchers/inventory_issuances/listview_for_posting.php';
					}elseif($reference_table=="posted"){
						$data['viewfile'] ='accounting/vouchers/inventory_issuances/listview_posted.php';
					}

					$this->load->view('gentlella_container.php',$data);

				break;

				case "add":

					$data=array();

					$data['MSIS'] 				= 	$this->Inventory_model->getIssuances($reference_id);
					$data['MSIS_details'] 		= 	$this->Inventory_model->getIssuanceDetails($reference_id);

					$vessel = $this->Abas->getVessel($data['MSIS'][0]['vessel_id']);
					$data['MSIS'][0]['issued_for']	=	$vessel->name;

					$company = $this->Abas->getCompany($vessel->company);
					$data['MSIS'][0]['company']	=	$company->name;
					$data['MSIS'][0]['company_id']	=	$company->id;

					$data['vessels']			=	$this->Abas->getVesselsByCompany($vessel->company);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($vessel->company);

					$this->load->view('accounting/vouchers/inventory_issuances/form.php',$data);

				break;

				case "insert":

					$MSIS = $this->Inventory_model->getIssuances($reference_id);
					$msis_id = $MSIS[0]['id'];
					$msis_control_number = $MSIS[0]['control_number'];

					$vessel = $this->Abas->getVessel($MSIS [0]['vessel_id']);
					$msis_vessel_name	=	$vessel->name;

					$company = $this->Abas->getCompany($vessel->company);
					$msis_company_name=	$company->name;

					$transaction = array();

					$transaction['date']		=	date("Y-m-d H:i:s");
					$transaction['company_id']	=	$this->Mmm->sanitize($_POST['company_id']);
					$remark						=	"MSIS #".$msis_control_number. "(".$msis_company_name.") issued for ".$msis_vessel_name;
					$transaction['remark']		=	$remark;
					$transaction['status']		=	"Active";
					$transaction['stat']		=	0;
					$transaction['reference_table'] 	= 'inventory_issuance';
					$transaction['reference_id'] 		= $reference_id;
					$transaction['created_on']	=	date("Y-m-d H:i:s");
					$transaction['created_by']	=	$_SESSION['abas_login']['userid'];

					$checkTransaction			=	$this->Mmm->dbInsert("ac_transactions", $transaction, "Added new transaction for MSIS with Transaction Code No.".$msis_id);

					$isCleared = $this->db->query('UPDATE inventory_issuance SET is_cleared=1 WHERE id='.$msis_id,'Marked MSIS with Transaction Code No.'.$msis_id. ' cleared.');

					$last_transaction_id = $this->Abas->getLastIDByTable('ac_transactions');

					if($checkTransaction && $isCleared){

						$multiAttach = array();

						$target_dir = WPATH.'assets/uploads/accounting/attachments/';

						foreach($_POST['attachment'] as $ctr=>$val){

							$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
							$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);

							if(end($old_filename)!=""){
								$multiAttach[$ctr]['transaction_id']	=	$last_transaction_id;
								$multiAttach[$ctr]['document_name'] 	= 	$this->Mmm->sanitize($_POST['attachment'][$ctr]);
								$multiAttach[$ctr]['document_file'] 	= 	$new_filename;

								$target_file = $target_dir . $new_filename;
								$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
							}
						}

						if(count($multiAttach)>0){
							$checkAttach = $this->Mmm->multiInsert("ac_transaction_attachments",$multiAttach,'Inserted attachments for Inventory Issuances with Transaction Code No. ' . $last_transaction_id);
						}else{
							$checkAttach = TRUE;
						}

						if($checkAttach){

							$entry = array();
							$multiInsert = array();

							foreach($_POST['coa_id'] as $ctr=>$val){

								$entry['transaction_id'] 	= $last_transaction_id;
								$entry['remark'] 			= $remark;
								$entry['company'] 			= $this->Mmm->sanitize($_POST['company_id']);
								$entry['reference_table'] 	= 'inventory_issuance';
								$entry['reference_id'] 		= $reference_id;
								$entry['posted_on']			= NULL;
								$entry['debit_amount']		= $this->Mmm->sanitize($_POST['debit'][$ctr]);
								$entry['credit_amount']		= $this->Mmm->sanitize($_POST['credit'][$ctr]);

								$department = $this->Accounting_model->getDepartmentIDByAccountingCode($this->Mmm->sanitize($_POST['department'][$ctr]));

								$entry['department']		= isset($department->id)?$department->id:0;
								$entry['vessel']			= $this->Mmm->sanitize($_POST['vessel'][$ctr]);
								$entry['contract']			= $this->Mmm->sanitize($_POST['contract'][$ctr]);
								$entry['account']			= $this->Mmm->sanitize($_POST['coa_id'][$ctr]);
								$entry['stat']				= 0;

								$checkEntry = $this->Accounting_model->newJournalEntry($entry);
							}

							if($checkEntry){
								$this->Abas->sysNotif("Inventory Issuances", $_SESSION['abas_login']['fullname']." has successfully cleared MSIS No." . $msis_control_number . " under company " . $msis_company_name . " issued for ".$msis_vessel_name,"Accounting","info");

								$this->Abas->sysMsg("sucmsg","Successfully processed the clearing for MSIS No." . $msis_control_number . " under company " . $msis_company_name . " issued for ".$msis_vessel_name);
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the entries for this Inventory Issuance! Please try again.");
								$this->Abas->redirect(HTTP_PATH."accounting/inventory_issuances/listview/for_clearing");
								die();
							}

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while uploading the attachments for this Inventory Issuance! Please try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/inventory_issuances/listview/for_clearing");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the transaction for this Inventory Issuance! Please try again.");
						$this->Abas->redirect(HTTP_PATH."accounting/inventory_issuances/listview/for_clearing");
							die();
					}

					$this->Abas->redirect(HTTP_PATH."accounting/inventory_issuances/listview/for_clearing");

				break;

				case "view":

					$data['MSIS'] 				= 	$this->Inventory_model->getIssuances($reference_id);
					$data['MSIS_details'] 		= 	$this->Inventory_model->getIssuanceDetails($reference_id);

					$vessel = $this->Abas->getVessel($data['MSIS'][0]['vessel_id']);
					$data['MSIS'][0]['issued_for']	=	$vessel->name;

					$company = $this->Abas->getCompany($vessel->company);
					$data['MSIS'][0]['company']	=	$company->name;
					$data['MSIS'][0]['company_id']	=	$company->id;

					$data['vessels']			=	$this->Abas->getVesselsByCompany($vessel->company);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($vessel->company);

					$data['transaction']		=	$this->Accounting_model->getTransaction($transaction_id);
					$data['transaction_journal_entries']=	$this->Accounting_model->getTransactionJournalEntries($transaction_id);
					$data['transaction_attachments']=	$this->Accounting_model->getTransactionAttachments($transaction_id);

					$this->load->view('accounting/vouchers/inventory_issuances/form.php',$data);

				break;

				case "approve":

					$data['MSIS'] 				= 	$this->Inventory_model->getIssuances($reference_id);

					$control_number = $data['MSIS'][0]['control_number'];

					$vessel = $this->Abas->getVessel($data['MSIS'][0]['vessel_id']);
					$vessel_name	=	$vessel->name;

					$company = $this->Abas->getCompany($vessel->company);
					$company_name	=	$company->name;

					$sql_transaction = "UPDATE ac_transactions SET stat=1 WHERE id=".$transaction_id;
					$query_transaction = $this->db->query($sql_transaction);

					$posted_by = $_SESSION['abas_login']['userid'];
					$posted_on = $data['MSIS'][0]['issue_date'];//date("Y-m-d H:i:s");

					if($query_transaction){
						$sql_entry = "UPDATE ac_transaction_journal SET stat=1, posted_on='".$posted_on."', posted_by=".$posted_by." WHERE transaction_id=".$transaction_id. " AND reference_id=".$reference_id;
						$query_entry = $this->db->query($sql_entry);
					}

					if($query_transaction && $query_entry){
						$this->Abas->sysNotif("Inventory Issuances", $_SESSION['abas_login']['fullname']." has approved Inventory Issuance for MSIS No." . $control_number . " under company " . $company_name . " issued for " . $vessel_name,"Accounting","info");

						$this->Abas->sysMsg("sucmsg","Successfully approved Inventory Issuance for MSIS No." . $control_number . " under company " . $company_name . " issued for " . $vessel_name);
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while updating the status for this Inventory Issuance! Please try again.");
					}

					$this->Abas->redirect(HTTP_PATH."accounting/inventory_issuances/listview/for_posting");

				break;
			}
		}
		public function inventory_returns($action,$reference_table=NULL,$reference_id=NULL,$transaction_id=NULL){

			$this->Abas->checkPermissions("accounting|view_vouchers");

			switch($action){
				case "load":
					$data = array();
					//if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";

						if($reference_table=="for_clearing"){
							$table = "ac_inventory_returns_for_clearing";
							$status = "For Clearing";
						}elseif($reference_table=="for_posting"){
							$table = "ac_inventory_returns_for_posting";
							$status = "For Posting";
						}elseif($reference_table=="posted"){
							$table = "ac_inventory_returns_posted";
							$status = "Posted";
						}

						$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);

						foreach($data['rows'] as $ctr=>$row){

							if(isset($row['return_from'])) {
								$vessel 							=	$this->Abas->getVessel($row['return_from']);
								$data['rows'][$ctr]['return_from']	=	$vessel->name;
								$data['rows'][$ctr]['inventory_return_status']		=	$status;

								$posted =	$this->Accounting_model->getTransactionJournalEntriesByReference('inventory_return',$row['id']);
								$data['rows'][$ctr]['posted_on']		= date('Y-m-d',strtotime($posted[0]['posted_on']));

								$returned_items = $this->Inventory_model->getReturnDetails($row['id']);
								$amount =0;
								foreach($returned_items as $item){
									$amount = $amount + ($item['unit_price'] *  $item['qty']);
								}

								$data['rows'][$ctr]['total_amount']		= number_format($amount,2,'.',',');
							}

						}

						header('Content-Type: application/json');
						echo json_encode($data);

						exit();

					//}
				break;

				case "listview":

					if($reference_table=="for_clearing"){
						$data['viewfile'] ='accounting/vouchers/inventory_returns/listview_for_clearing.php';
					}elseif($reference_table=="for_posting"){
						$data['viewfile'] ='accounting/vouchers/inventory_returns/listview_for_posting.php';
					}elseif($reference_table=="posted"){
						$data['viewfile'] ='accounting/vouchers/inventory_returns/listview_posted.php';
					}

					$this->load->view('gentlella_container.php',$data);

				break;

				case "add":

					$data=array();

					$data['MSRS'] 				= 	$this->Inventory_model->getReturns($reference_id);
					$data['MSRS_details'] 		= 	$this->Inventory_model->getReturnDetails($reference_id);

					$vessel = $this->Abas->getVessel($data['MSRS'][0]['return_from']);
					$data['MSRS'][0]['return_from']	=	$vessel->name;

					$company = $this->Abas->getCompany($vessel->company);
					$data['MSRS'][0]['company_name']	=	$company->name;

					$data['vessels']			=	$this->Abas->getVesselsByCompany($vessel->company);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($vessel->company);

					$this->load->view('accounting/vouchers/inventory_returns/form.php',$data);

				break;

				case "insert":

					$MSRS = $this->Inventory_model->getReturns($reference_id);
					$msrs_id = $MSRS[0]['id'];
					$msrs_control_number = $MSRS[0]['control_number'];

					$vessel = $this->Abas->getVessel($MSRS[0]['return_from']);
					$msrs_vessel_name	=	$vessel->name;

					$company = $this->Abas->getCompany($vessel->company);
					$msrs_company_name=	$company->name;

					$transaction = array();

					$transaction['date']		=	date("Y-m-d H:i:s");
					$transaction['company_id']	=	$this->Mmm->sanitize($_POST['company_id']);
					$remark						=	"MSRS #".$msrs_control_number. "(".$msrs_company_name.") returned from ".$msrs_vessel_name;
					$transaction['remark']		=	$remark;
					$transaction['status']		=	"Active";
					$transaction['stat']		=	0;
					$transaction['reference_table'] 	= 'inventory_return';
					$transaction['reference_id'] 		= $reference_id;
					$transaction['created_on']	=	date("Y-m-d H:i:s");
					$transaction['created_by']	=	$_SESSION['abas_login']['userid'];

					$checkTransaction			=	$this->Mmm->dbInsert("ac_transactions", $transaction, "Added new transaction for MSRS with Transaction Code No.".$msrs_id);

					$isCleared = $this->db->query('UPDATE inventory_return SET is_cleared=1 WHERE id='.$msrs_id,'Marked MSRS with Transaction Code No.'.$msrs_id. ' cleared.');

					$last_transaction_id = $this->Abas->getLastIDByTable('ac_transactions');

					if($checkTransaction && $isCleared){

						$multiAttach = array();

						$target_dir = WPATH.'assets/uploads/accounting/attachments/';

						foreach($_POST['attachment'] as $ctr=>$val){

							$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
							$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);

							if(end($old_filename)!=""){
								$multiAttach[$ctr]['transaction_id']	=	$last_transaction_id;
								$multiAttach[$ctr]['document_name'] 	= 	$this->Mmm->sanitize($_POST['attachment'][$ctr]);
								$multiAttach[$ctr]['document_file'] 	= 	$new_filename;

								$target_file = $target_dir . $new_filename;
								$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
							}
						}

						if(count($multiAttach)>0){
							$checkAttach = $this->Mmm->multiInsert("ac_transaction_attachments",$multiAttach,'Inserted attachments for Inventory Returns with Transaction Code No. ' . $last_transaction_id);
						}else{
							$checkAttach = TRUE;
						}

						if($checkAttach){

							$entry = array();
							$multiInsert = array();

							foreach($_POST['coa_id'] as $ctr=>$val){

								$entry['transaction_id'] 	= $last_transaction_id;
								$entry['remark'] 			= $remark;
								$entry['company'] 			= $this->Mmm->sanitize($_POST['company_id']);
								$entry['reference_table'] 	= 'inventory_return';
								$entry['reference_id'] 		= $reference_id;
								$entry['posted_on']			= NULL;
								$entry['debit_amount']		= $this->Mmm->sanitize($_POST['debit'][$ctr]);
								$entry['credit_amount']		= $this->Mmm->sanitize($_POST['credit'][$ctr]);

								$department = $this->Accounting_model->getDepartmentIDByAccountingCode($this->Mmm->sanitize($_POST['department'][$ctr]));

								$entry['department']		= isset($department->id)?$department->id:0;
								$entry['vessel']			= $this->Mmm->sanitize($_POST['vessel'][$ctr]);
								$entry['contract']			= $this->Mmm->sanitize($_POST['contract'][$ctr]);
								$entry['account']			= $this->Mmm->sanitize($_POST['coa_id'][$ctr]);
								$entry['stat']				= 0;

								$checkEntry = $this->Accounting_model->newJournalEntry($entry);
							}

							if($checkEntry){
								$this->Abas->sysNotif("Inventory Returns", $_SESSION['abas_login']['fullname']." has successfully cleared MSRS No." . $msrs_control_number . " under company " . $msrscompany_name . " returned from ".$msrs_vessel_name,"Accounting","info");

								$this->Abas->sysMsg("sucmsg","Successfully processed the clearing for MSRS No." . $msrs_control_number . " under company " . $msrs_company_name . " returned from ".$msrs_vessel_name);
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the entries for this Inventory Return! Please try again.");
								$this->Abas->redirect(HTTP_PATH."accounting/inventory_returns/listview/for_clearing");
								die();
							}

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while uploading the attachments for this Inventory Return! Please try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/inventory_returns/listview/for_clearing");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the transaction for this Inventory Return! Please try again.");
						$this->Abas->redirect(HTTP_PATH."accounting/inventory_returns/listview/for_clearing");
							die();
					}

					$this->Abas->redirect(HTTP_PATH."accounting/inventory_returns/listview/for_clearing");

				break;

				case "view":

					$data['MSRS'] 				= 	$this->Inventory_model->getReturns($reference_id);
					$data['MSRS_details'] 		= 	$this->Inventory_model->getReturnDetails($reference_id);

					$vessel = $this->Abas->getVessel($data['MSRS'][0]['return_from']);
					$data['MSRS'][0]['returned_from']	=	$vessel->name;

					$company = $this->Abas->getCompany($vessel->company);
					$data['MSRS'][0]['company_name']	=	$company->name;
					$data['MSRS'][0]['company_id']	=	$company->id;

					$data['vessels']			=	$this->Abas->getVesselsByCompany($vessel->company);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($vessel->company);

					$data['transaction']		=	$this->Accounting_model->getTransaction($transaction_id);
					$data['transaction_journal_entries']=	$this->Accounting_model->getTransactionJournalEntries($transaction_id);
					$data['transaction_attachments']=	$this->Accounting_model->getTransactionAttachments($transaction_id);

					$this->load->view('accounting/vouchers/inventory_returns/form.php',$data);

				break;

				case "approve":

					$data['MSRS'] 				= 	$this->Inventory_model->getReturns($reference_id);

					$control_number = $data['MSRS'][0]['control_number'];

					$vessel = $this->Abas->getVessel($data['MSRS'][0]['return_from']);
					$vessel_name	=	$vessel->name;

					$company = $this->Abas->getCompany($vessel->company);
					$company_name	=	$company->name;

					$sql_transaction = "UPDATE ac_transactions SET stat=1 WHERE id=".$transaction_id;
					$query_transaction = $this->db->query($sql_transaction);

					$posted_by = $_SESSION['abas_login']['userid'];
					$posted_on = $data['MSRS'][0]['return_date'];//date("Y-m-d H:i:s");

					if($query_transaction){
						$sql_entry = "UPDATE ac_transaction_journal SET stat=1, posted_on='".$posted_on."', posted_by=".$posted_by." WHERE transaction_id=".$transaction_id. " AND reference_id=".$reference_id." AND reference_table='inventory_return'";
						$query_entry = $this->db->query($sql_entry);
					}

					if($query_transaction && $query_entry){
						$this->Abas->sysNotif("Inventory Returns", $_SESSION['abas_login']['fullname']." has approved Inventory Return for MSRS No." . $control_number . " under company " . $company_name . " returned from " . $vessel_name,"Accounting","info");

						$this->Abas->sysMsg("sucmsg","Successfully approved Inventory Return for MSRS No." . $control_number . " under company " . $company_name . " returned from " . $vessel_name);
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while updating the status for this Inventory Return! Please try again.");
					}

					$this->Abas->redirect(HTTP_PATH."accounting/inventory_returns/listview/for_posting");

				break;
			}
		}
		public function payroll_entries($action,$reference_table=NULL,$reference_id=NULL,$transaction_id=NULL){

			$this->Abas->checkPermissions("accounting|view_vouchers");

			switch($action){
				case "load":
					$data = array();
					//if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";

						if($reference_table=="for_clearing"){
							$table = "ac_payroll_entries_for_clearing";
							$status = "For Clearing";
						}elseif($reference_table=="for_posting"){
							$table = "ac_payroll_entries_for_posting";
							$status = "For Posting";
						}elseif($reference_table=="posted"){
							$table = "ac_payroll_entries_posted";
							$status = "Posted";
						}

						$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);

						foreach($data['rows'] as $ctr=>$row){

							if(isset($row['id'])) {
								
								$company =	$this->Abas->getCompany($row['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
								$data['rows'][$ctr]['payroll_date'] = date('F Y',strtotime($row['payroll_date']));
								$data['rows'][$ctr]['payroll_amount']	=	number_format($row['payroll_amount'],2,'.',',');
							
								$data['rows'][$ctr]['status']	=	$status;

							}

						}

						header('Content-Type: application/json');
						echo json_encode($data);

						exit();

					//}
				break;

				case "listview":

					if($reference_table=="for_clearing"){
						$data['viewfile'] ='accounting/vouchers/payroll_entries/listview_for_clearing.php';
					}elseif($reference_table=="for_posting"){
						$data['viewfile'] ='accounting/vouchers/payroll_entries/listview_for_posting.php';
					}elseif($reference_table=="posted"){
						$data['viewfile'] ='accounting/vouchers/payroll_entries/listview_posted.php';
					}

					$this->load->view('gentlella_container.php',$data);

				break;

				case "add":

					$data=array();

					$data['payroll'] 				= 	$this->Payroll_model->getPayroll($reference_id);
					$data['payroll_details'] 		= 	$this->Payroll_model->getPayrollDetails($reference_id);
					$company = $this->Abas->getCompany($data['payroll']->company_id);
					$data['payroll']->company_name	=	$company->name;
					$data['vessels']			=	$this->Abas->getVesselsByCompany($company->id);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($company->id);
					$data['gross_per_vessel'] 	= $this->Payroll_model->getPayrollGrossPerVessel($reference_id);

					//$this->Mmm->debug($data['gross_per_vessel']);

					$this->load->view('accounting/vouchers/payroll_entries/form.php',$data);

				break;

				case "insert":

					$payroll = $this->Payroll_model->getPayroll($reference_id);
					$payroll_id = $payroll->id;

					$this->Mmm->debug($payroll_id);
					
					$company = $this->Abas->getCompany($payroll->company_id);
					$company_name=	$company->name;

					$transaction = array();

					$transaction['date']		=	date("Y-m-d H:i:s");

					if($payroll->company_id==10){ //ABISC (Staff)
						$company_id = 1; //turn to ABISC
					}else{
						$company_id = $payroll->company_id;
					}
					$transaction['company_id']	=	$company_id;
					$remark						=	"To take up payroll for the month of ".$payroll->payroll_coverage." ".date('F Y',strtotime($payroll->payroll_date));
					$transaction['remark']		=	$remark;
					$transaction['status']		=	"Active";
					$transaction['stat']		=	0;
					$transaction['reference_table'] 	= 'hr_payroll';
					$transaction['reference_id'] 		= $reference_id;
					$transaction['created_on']	=	date("Y-m-d H:i:s");
					$transaction['created_by']	=	$_SESSION['abas_login']['userid'];

					$checkTransaction			=	$this->Mmm->dbInsert("ac_transactions", $transaction, "Added new transaction for Payroll with Transaction Code No.".$payroll_id);

					$isCleared = $this->db->query('UPDATE hr_payroll SET is_cleared=1 WHERE id='.$payroll_id,'Marked Payroll with Transaction Code No.'.$payroll_id. ' cleared.');

					$last_transaction_id = $this->Abas->getLastIDByTable('ac_transactions');

					if($checkTransaction && $isCleared){

						$multiAttach = array();

						$target_dir = WPATH.'assets/uploads/accounting/attachments/';

						foreach($_POST['attachment'] as $ctr=>$val){

							$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
							$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);

							if(end($old_filename)!=""){
								$multiAttach[$ctr]['transaction_id']	=	$last_transaction_id;
								$multiAttach[$ctr]['document_name'] 	= 	$this->Mmm->sanitize($_POST['attachment'][$ctr]);
								$multiAttach[$ctr]['document_file'] 	= 	$new_filename;

								$target_file = $target_dir . $new_filename;
								$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
							}
						}

						if(count($multiAttach)>0){
							$checkAttach = $this->Mmm->multiInsert("ac_transaction_attachments",$multiAttach,'Inserted attachments for Payroll Entries with Transaction Code No. ' . $last_transaction_id);
						}else{
							$checkAttach = TRUE;
						}

						if($checkAttach){

							$entry = array();
							$multiInsert = array();

							foreach($_POST['coa_id'] as $ctr=>$val){

								$entry['transaction_id'] 	= $last_transaction_id;
								$entry['remark'] 			= $remark;
								$company_id = $this->Mmm->sanitize($_POST['company_id']);
								if($company_id==10){ //ABISC (Staff)
									$company_id = 1; //turn to ABISC
								}
								$entry['company'] 			= $company_id;
								$entry['reference_table'] 	= 'hr_payroll';
								$entry['reference_id'] 		= $reference_id;
								$entry['posted_on']			= NULL;
								$entry['debit_amount']		= $this->Mmm->sanitize($_POST['debit'][$ctr]);
								$entry['credit_amount']		= $this->Mmm->sanitize($_POST['credit'][$ctr]);

								$department = $this->Accounting_model->getDepartmentIDByAccountingCode($this->Mmm->sanitize($_POST['department'][$ctr]));

								$entry['department']		= isset($department->id)?$department->id:0;
								$entry['vessel']			= $this->Mmm->sanitize($_POST['vessel'][$ctr]);
								$entry['contract']			= $this->Mmm->sanitize($_POST['contract'][$ctr]);
								$entry['account']			= $this->Mmm->sanitize($_POST['coa_id'][$ctr]);
								$entry['stat']				= 0;

								$checkEntry = $this->Accounting_model->newJournalEntry($entry);
							}

							if($checkEntry){
								$this->Abas->sysNotif("Payroll Entries", $_SESSION['abas_login']['fullname']." has successfully cleared Payroll for " . $payroll->payroll_coverage." ".date('F Y',strtotime($payroll->payroll_date)) . " under company " . $company_name,"Accounting","info");

								$this->Abas->sysMsg("sucmsg","Successfully cleared Payroll for " . $payroll->payroll_coverage." ".date('F Y',strtotime($payroll->payroll_date)) . " under company " . $company_name);
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the entries for this Payroll! Please try again.");
								$this->Abas->redirect(HTTP_PATH."accounting/payroll_entries/listview/for_clearing");
								die();
							}

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while uploading the attachments for this Payroll! Please try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/payroll_entries/listview/for_clearing");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the transaction for this Payroll! Please try again.");
						$this->Abas->redirect(HTTP_PATH."accounting/payroll_entries/listview/for_clearing");
							die();
					}

					$this->Abas->redirect(HTTP_PATH."accounting/payroll_entries/listview/for_clearing");

				break;

				case "view":

					$data['payroll'] 			= 	$this->Payroll_model->getPayroll($reference_id);
					$data['payroll_details'] 	= 	$this->Payroll_model->getPayrollDetails($reference_id);

					$company = $this->Abas->getCompany($data['payroll']->company_id);
					$data['payroll']->company_name	=	$company->name;
					$data['payroll']->company_id	=	$company->id;

					$data['vessels']			=	$this->Abas->getVesselsByCompany($company->id);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($company->id);

					$data['transaction']		=	$this->Accounting_model->getTransaction($transaction_id);
					$data['transaction_journal_entries']=	$this->Accounting_model->getTransactionJournalEntries($transaction_id);
					$data['transaction_attachments']=	$this->Accounting_model->getTransactionAttachments($transaction_id);

					$this->load->view('accounting/vouchers/payroll_entries/form.php',$data);

				break;

				case "approve":

					$payroll= 	$this->Payroll_model->getPayroll($reference_id);

					$company = $this->Abas->getCompany($payroll->company_id);
					$company_name	=	$company->name;

					$sql_transaction = "UPDATE ac_transactions SET stat=1 WHERE id=".$transaction_id;
					$query_transaction = $this->db->query($sql_transaction);

					$posted_by = $_SESSION['abas_login']['userid'];
					$posted_on = $payroll->created_on;//date("Y-m-d H:i:s");

					if($query_transaction){
						$sql_entry = "UPDATE ac_transaction_journal SET stat=1, posted_on='".$posted_on."', posted_by=".$posted_by." WHERE transaction_id=".$transaction_id. " AND reference_id=".$reference_id;
						$query_entry = $this->db->query($sql_entry);
					}

					if($query_transaction && $query_entry){
						$this->Abas->sysNotif("Payroll Entries", $_SESSION['abas_login']['fullname']." has approved Payroll Entries for ".$payroll->payroll_coverage." ".date('F Y',strtotime($payroll->payroll_date)) . " under company " . $company_name,"Accounting","info");

						$this->Abas->sysMsg("sucmsg","Successfully approved Payroll Entries for ". $payroll->payroll_coverage." ".date('F Y',strtotime($payroll->payroll_date)) . " under company " . $company_name);
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while updating the status for this Payroll Entries! Please try again.");
					}

					$this->Abas->redirect(HTTP_PATH."accounting/payroll_entries/listview/for_posting");

				break;
			}
		}
		public function lapsing_schedule($action,$id=NULL){
			switch ($action) {
				case 'load':
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$data	=	$this->Abas->createBSTable('ac_lapsing_schedules',$search,$limit,$offset,$order,$sort);

						foreach($data['rows'] as $ctr=>$row){
							if(isset($row['company_id'])){
								$company		=	$this->Abas->getCompany($row['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							if(isset($row['created_on'])){
								$data['rows'][$ctr]['created_on']	=	date("j F Y", strtotime($row['created_on']));
							}
							if(isset($row['created_by'])){
								$created_by		=	$this->Abas->getUser($row['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if(isset($row['modified_on'])){
								$data['rows'][$ctr]['modified_on']	=	date("j F Y h:i:s A", strtotime($row['modified_on']));
							}
							if(isset($row['modified_by'])){
								$modified_by		=	$this->Abas->getUser($row['modified_by']);
								$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
							}
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				break;

				case 'listview':
					$data['viewfile'] = 'accounting/lapsing_schedule/listview.php';
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'add':
					$data['companies'] = $this->Abas->getCompanies();
					$this->load->view('accounting/lapsing_schedule/form.php',$data);
				break;

				case 'insert':
					if($_POST['company']){
						$sql = "SELECT * FROM ac_lapsing_schedules WHERE company_id=".$_POST['company']. " AND year=".$_POST['year'];
						$check		=	$this->db->query($sql);
						if($check) {
							if($check->row()){
								$check	=	$check->row();
								$this->Abas->sysMsg("warnmsg", "A lapsing schedule with similar company and year has already been created. Please choose another one.");
								$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$check->id);
							}else{
								$insert = array();
								$insert['control_number']	=	$this->Abas->getNextSerialNumber('ac_lapsing_schedules',$this->Mmm->sanitize($_POST['company']));
								$insert['company_id']		=	$this->Mmm->sanitize($_POST['company']);
								$insert['year']				=	$this->Mmm->sanitize($_POST['year']);
								$company = $this->Abas->getCompany($insert['company_id']);
								$insert['status']			=	"Active";
								$insert['created_by']		=	$_SESSION['abas_login']['userid'];
								$insert['created_on']		=	date("Y-m-d H:i:s");
								$insert['stat']				=	1;
								$checkInsert	=	$this->Mmm->dbInsert("ac_lapsing_schedules",$insert,"Added new Lapsing Schedule of ".$company->name. " for year ".$insert['year']);
								if($checkInsert){
									$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('ac_lapsing_schedules');
									$multiInsert = array();
									$fixed_assets = $this->Asset_Management_model->getFixedAssets($insert['company_id']);
									foreach($fixed_assets as $ctr=>$row){
										if($row->status<>'Loss/Damaged' && $row->status<>'Disposed'){
											$multiInsert[$ctr]['lapsing_schedule_id']	=	$last_id_inserted;
											$multiInsert[$ctr]['fixed_asset_id']	=	$row->id;
											$multiInsert[$ctr]['total_cost']	=	$row->purchase_cost;
											$multiInsert[$ctr]['salvage_value']	=	$row->purchase_cost*0.10;
											$multiInsert[$ctr]['depreciable_amount']	=	$row->purchase_cost - $multiInsert[$ctr]['salvage_value'];
											$multiInsert[$ctr]['useful_life']	=	$row->useful_life;
											$multiInsert[$ctr]['annual_depreciation']	=	$multiInsert[$ctr]['depreciable_amount']/$multiInsert[$ctr]['useful_life'];
											$multiInsert[$ctr]['monthly_depreciation']	=	$multiInsert[$ctr]['annual_depreciation']/12;
											
											$ead = $this->Accounting_model->getPreviousEndAccumulatedDepreciation($insert['company_id'],$row->id,$insert['year']-1);
											$multiInsert[$ctr]['begin_accumulated_depreciation']	= $ead->end_accumulated_depreciation;
											$enbv = $this->Accounting_model->getPreviousEndNetBookValue($insert['company_id'],$row->id,$insert['year']-1);
											$multiInsert[$ctr]['begin_net_book_value']	= $enbv->end_net_book_value;
											$multiInsert[$ctr]['stat']	=	1;
										}
									}
									$checkMultiInsert = $this->Mmm->multiInsert('ac_lapsing_schedule_details',$multiInsert,'Added Fixed Assets on the new Lapsing Schedule of '.$company->name. ' for year '.$insert['year']);
									if($checkMultiInsert){
										$created_by  	=   $_SESSION['abas_login']['fullname'];
										$this->Abas->sysNotif("Lapsing Schedule", "New Lapsing Schedule of ".$company->name. " for year ".$insert['year'] ." was successfully added by ".$created_by,'Accounting',"info");
										$this->Abas->sysMsg("sucmsg", "New Lapsing Schedule of ".$company->name. " for year ".$insert['year'] ." was successfully added by ".$created_by);
										$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/listview");
									}else{
										$this->db->query('DELETE FROM ac_lapsing_schedules WHERE id='.$last_id_inserted);
										$this->Abas->sysMsg("warnmsg", "This company has no fixed-assets to include in the lapsing schedule.");
										$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/listview");
										
									}
									
								}else{
									$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Lapsing Schedule! Please try again.");
									$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/listview");
									die();
								}
							}
						}	
					}
				break;

				case "view":
					$data['lapsing_schedule']	=	$this->Accounting_model->getLapsingSchedule($id);
					$data['lapsing_schedule_details']	=	$this->Accounting_model->getLapsingScheduleDetails($id);
					$data['viewfile'] = 'accounting/lapsing_schedule/view.php';
					$data['categories'] = $this->Inventory_model->getCategories();
					$this->load->view('gentlella_container.php',$data);
				break;

				case "edit":
					$data['lapsing_schedule_detail']	=	$this->Accounting_model->getLapsingScheduleDetail($id);
					$this->load->view('accounting/lapsing_schedule/detail.php',$data);
				break;

				case "update":
					if($_POST['detail_id']){
						$update = array();

						$lapsing_detail = $this->Accounting_model->getLapsingScheduleDetail($id);
						$lapsing = $this->Accounting_model->getLapsingSchedule($lapsing_detail->lapsing_schedule_id);

						if($_POST['begin_accumulated_depreciation']){
							$update['begin_accumulated_depreciation'] = $this->Mmm->sanitize($_POST['begin_accumulated_depreciation']);
						}
						if($_POST['begin_net_book_value']){
							$update['begin_net_book_value'] = $this->Mmm->sanitize($_POST['begin_net_book_value']);
						}
						$update['january_depreciation']	= $this->Mmm->sanitize($_POST['january']);
						$update['february_depreciation']	= $this->Mmm->sanitize($_POST['february']);
						$update['march_depreciation']	= $this->Mmm->sanitize($_POST['march']);
						$update['april_depreciation']	= $this->Mmm->sanitize($_POST['april']);
						$update['may_depreciation']		= $this->Mmm->sanitize($_POST['may']);
						$update['june_depreciation']		= $this->Mmm->sanitize($_POST['june']);
						$update['july_depreciation']		= $this->Mmm->sanitize($_POST['july']);
						$update['august_depreciation']	= $this->Mmm->sanitize($_POST['august']);
						$update['september_depreciation']= $this->Mmm->sanitize($_POST['september']);
						$update['october_depreciation']	= $this->Mmm->sanitize($_POST['october']);
						$update['november_depreciation']	= $this->Mmm->sanitize($_POST['november']);
						$update['december_depreciation']	= $this->Mmm->sanitize($_POST['december']);

						$update['end_accumulated_depreciation'] = $update['begin_accumulated_depreciation'] + $update['january_depreciation'] + $update['february_depreciation'] + $update['march_depreciation'] + $update['april_depreciation'] + $update['may_depreciation'] + $update['june_depreciation'] + $update['july_depreciation'] + $update['august_depreciation'] + $update['september_depreciation'] + $update['october_depreciation'] + $update['november_depreciation'] + $update['december_depreciation'];

						$update['end_net_book_value'] = $update['begin_net_book_value'] - $update['january_depreciation'] - $update['february_depreciation'] - $update['march_depreciation'] - $update['april_depreciation'] - $update['may_depreciation'] - $update['june_depreciation'] - $update['july_depreciation'] - $update['august_depreciation'] - $update['september_depreciation'] - $update['october_depreciation'] - $update['november_depreciation'] - $update['december_depreciation'];

						$checkUpdate=	$this->Mmm->dbUpdate("ac_lapsing_schedule_details",$update,$id,"Updated Lapsing Schedule Detail for ".$lapsing_detail->asset_code);

						if($checkUpdate){
							$edited_by  	=   $_SESSION['abas_login']['fullname'];
							$this->Abas->sysNotif("Lapsing Schedule", "Depreciation of asset ".$lapsing_detail->asset_code. " for the year ".$lapsing->year." was successfully edited by ".$edited_by,'Accounting',"info");
							$this->Abas->sysMsg("sucmsg", "Depreciation of asset ".$lapsing_detail->asset_code. " was edited by ".$edited_by);
							$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$lapsing->id);
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Lapsing Schedule Detail! Please try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$lapsing->id);
							die();
						}
					}
				break;

				case 'recalculate':
					$lapsing = $this->Accounting_model->getLapsingSchedule($id);
					$lapsing_details = $this->Accounting_model->getLapsingScheduleDetails($id);
					$month_now = date('F');
					$year_now = date('Y');
					foreach($lapsing_details as $row_detail){
						switch ($month_now) {
							case 'January':
								$sql = "UPDATE ac_lapsing_schedule_details SET january_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the January ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'February':
								$sql = "UPDATE ac_lapsing_schedule_details SET february_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the February ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'March':
								$sql = "UPDATE ac_lapsing_schedule_details SET march_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the March ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'April':
								$sql = "UPDATE ac_lapsing_schedule_details SET april_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the April ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'May':
								$sql = "UPDATE ac_lapsing_schedule_details SET may_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the May ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'June':
								$sql = "UPDATE ac_lapsing_schedule_details SET june_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the June ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'July':
								$sql = "UPDATE ac_lapsing_schedule_details SET july_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the July ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'August':
								$sql = "UPDATE ac_lapsing_schedule_details SET august_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the August ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'September':
								$sql = "UPDATE ac_lapsing_schedule_details SET september_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the September ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'October':
								$sql = "UPDATE ac_lapsing_schedule_details SET october_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the October ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'November':
								$sql = "UPDATE ac_lapsing_schedule_details SET november_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the November ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
							case 'December':
								$sql = "UPDATE ac_lapsing_schedule_details SET december_depreciation=".$row_detail->monthly_depreciation. " WHERE id=".$row_detail->id;
								$this->Mmm->query($sql,"Set the December ".$year_now." - Monthly Depreciation of Asset ".$row_detail->asset_code);
							break;
						}

						$end_accumulated_depreciation = $this->Accounting_model->getComputedEndAccumulatedDepreciation($row_detail->id);
						$end_net_book_value = $this->Accounting_model->getComputedEndNetBookValue($row_detail->id);

						$sql2 = "UPDATE ac_lapsing_schedule_details SET end_accumulated_depreciation=".$end_accumulated_depreciation.",end_net_book_value=".$end_net_book_value." WHERE id=".$row_detail->id;
						$this->Mmm->query($sql2,"Set the ".$year_now." Ending Accumulated Depreciation and Net Book Value of Asset ".$row_detail->asset_code);

					}
					$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$id);
				break;

				case "lock":
					$locked_by  	=   $_SESSION['abas_login']['fullname'];
					$lapsing = $this->Accounting_model->getLapsingSchedule($id);
					$sql = "UPDATE ac_lapsing_schedules SET status='Locked' WHERE id=".$id;
					$locked = $this->Mmm->query($sql,"Locked Lapsing Schedule with TSCode No.".$id);
					if($locked){
						$this->Abas->sysNotif("Lapsing Schedule", "Lapsing Schedule of ".$lapsing->company_name. " for the year ".$lapsing->year." was successfully locked by ".$locked_by,'Accounting',"info");
						$this->Abas->sysMsg("sucmsg", "Lapsing Schedule of ".$lapsing->company_name. " for the year ".$lapsing->year." was locked by ".$locked_by);
						$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$id);
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Lapsing Schedule! Please try again.");
						$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$id);
						die();
					}
				break;

				case "print":
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['lapsing_schedule']	=	$this->Accounting_model->getLapsingSchedule($id);
					$data['lapsing_schedule_details']	=	$this->Accounting_model->getLapsingScheduleDetails($id);
					$data['categories'] = $this->Inventory_model->getCategories();
					$this->load->view('accounting/lapsing_schedule/print.php',$data);
				break;

				case 'add_fixed_asset':
					$data['lapsing_schedule'] = $this->Accounting_model->getLapsingSchedule($id);
					$this->load->view('accounting/lapsing_schedule/add_fixed_asset_form.php',$data);
				break;

				case 'insert_fixed_asset':
					$insert_fixed_asset = array();
					if(isset($_POST['fixed_asset_id'])){
						$lapsing_schedule_id	=	$this->Mmm->sanitize($_POST['lapsing_schedule_id']);
						$fixed_asset_id			=	$this->Mmm->sanitize($_POST['fixed_asset_id']);
						$sql = "SELECT * FROM ac_lapsing_schedule_details WHERE lapsing_schedule_id=".$lapsing_schedule_id." AND fixed_asset_id=".$fixed_asset_id;
						$query = $this->db->query($sql);
						$result = count($query->result());
						if($result==0){
							$lapsing_year = $this->Mmm->sanitize($_POST['lapsing_schedule_year']);
							$insert_fixed_asset['lapsing_schedule_id']	=	$lapsing_schedule_id;
							$insert_fixed_asset['fixed_asset_id']	=	$fixed_asset_id;
							$fixed_asset = $this->Asset_Management_model->getFixedAsset($insert_fixed_asset['fixed_asset_id']);
							$insert_fixed_asset['total_cost']	=	$fixed_asset->purchase_cost;
							$insert_fixed_asset['salvage_value']	=	$fixed_asset->purchase_cost*0.10;
							$insert_fixed_asset['depreciable_amount']	=	$fixed_asset->purchase_cost - $insert_fixed_asset['salvage_value'];
							$insert_fixed_asset['useful_life']	=	$fixed_asset->useful_life;
							$insert_fixed_asset['annual_depreciation']	=	$insert_fixed_asset['depreciable_amount']/$insert_fixed_asset['useful_life'];
							$insert_fixed_asset['monthly_depreciation']	=	$insert_fixed_asset['annual_depreciation']/12;
							$ead = $this->Accounting_model->getPreviousEndAccumulatedDepreciation($fixed_asset->company_id,$fixed_asset->id,$lapsing_year-1);
							$insert_fixed_asset['begin_accumulated_depreciation']	= (isset($ead->end_accumulated_depreciation))?$ead->end_accumulated_depreciation:0;
							$enbv = $this->Accounting_model->getPreviousEndNetBookValue($fixed_asset->company_id,$fixed_asset->id,$lapsing_year-1);
							$insert_fixed_asset['begin_net_book_value']	= (isset($enbv->end_net_book_value))?$enbv->end_net_book_value:0;
							$insert_fixed_asset['stat']	=	1;
							
							$company = $this->Abas->getCompany($fixed_asset->company_id);
							$checkInsert = $this->Mmm->dbInsert("ac_lapsing_schedule_details",$insert_fixed_asset,"Added new Fixed-Asset on Lapsing Schedule of ".$company->name. " for year ".$lapsing_year);
							if($checkInsert){
								$this->Abas->sysNotif("Lapsing Schedule", "Added new Fixed-Asset on Lapsing Schedule of ".$company->name. " for year ".$lapsing_year,'Accounting',"info");
								$this->Abas->sysMsg("sucmsg", "Added new Fixed-Asset on Lapsing Schedule of ".$company->name. " for year ".$lapsing_year);
								$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$lapsing_schedule_id);
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Lapsing Schedule! Please try again.");
								$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$lapsing_schedule_id);
								die();
							}
						}else{
							$this->Abas->sysMsg("warnmsg", "This asset is already included in this Lapsing Schedule!");
							$this->Abas->redirect(HTTP_PATH."accounting/lapsing_schedule/view/".$lapsing_schedule_id);
						}
					}
				break;
			}
		}
		public function summarize_entries() {
			$this->Abas->checkPermissions("accounting|summarize_entries");
			if(!empty($_POST)) {
				$this->Mmm->debug($_POST);
				if(empty($_POST['dstart']) || empty($_POST['dfinish']) || !is_numeric($_POST['company'])) {
					$this->Abas->sysMsg("warnmsg", "Invalid input! Please try again.");
					$this->Abas->redirect(HTTP_PATH."accounting");
				}
				$company	=	$this->Abas->getCompany($_POST['company']);
				if(empty($company)) {
					$this->Abas->sysMsg("warnmsg", "Company not found! Please try again.");
					$this->Abas->redirect(HTTP_PATH."accounting");
				}
				$transaction['date']		=	date("Y-m-d",strtotime($_POST['post_on']))." 00:00:00";
				$transaction['status']		=	"Active";
				$transaction['company_id']	=	$company->id;
				$transaction['created_by']	=	$_SESSION['abas_login']['userid'];
				$transaction['created_on']	=	date("Y-m-d H:i:s");
				$transaction['remark']		=	"Summary of entries - ".date("j F Y",strtotime($_POST['dstart']))." to ".date("j F Y",strtotime($_POST['dfinish']))." [".$this->Mmm->sanitize($_POST['remarks'])."]";
				$checkTransaction	=	$this->Mmm->dbInsert('ac_transactions',$transaction,$transaction['remark']);
				if(!$checkTransaction) {
					$this->Abas->sysMsg("errmsg","Transaction creation failed when creating entry summaries! Please try again.");
					$this->Abas->redirect(HTTP_PATH."accounting");
				}
				$last_transaction		=	$this->db->query("select max(id) as id from ac_transactions");
				$last_transaction		=	$last_transaction->row();
				$last_transaction_id	=	$last_transaction->id;
				$sql			=	"select coa.id as id, if((sum(tj.debit_amount)-sum(tj.credit_amount)) > 0, (sum(tj.debit_amount)-sum(tj.credit_amount)), 0) as debit_total, if((sum(tj.debit_amount)-sum(tj.credit_amount)) < 0, abs(sum(tj.debit_amount)-sum(tj.credit_amount)), 0) as credit_total from ac_transaction_journal as tj join ac_accounts as coa on coa.id=tj.coa_id where tj.stat=1 and tj.posted_on>='".date("Y-m-d",strtotime($_POST['dstart']))." 00:00:00' AND tj.posted_on<='".date("Y-m-d",strtotime($_POST['dfinish']))." 23:59:59' AND tj.company_id=".$company->id." group by coa_id order by coa.financial_statement_code, coa.general_ledger_code"; // Taken from trial balance and modified to select entries from the year selected
				$this->Mmm->debug($sql);
				$accounts					=	$this->db->query($sql);
				$accounts					=	$accounts->result_array();
				$this->Mmm->debug($accounts);
				foreach($accounts as $accctr=>$account) {
					$entries[$accctr]['coa_id']			=	$account['id'];
					$entries[$accctr]['debit_amount']	=	$account['debit_total'];
					$entries[$accctr]['credit_amount']	=	$account['credit_total'];
					$entries[$accctr]['remark']			=	$transaction['remark'];
					$entries[$accctr]['created_on']		=	$transaction['created_on'];
					$entries[$accctr]['posted_on']		=	$transaction['date'];
					$entries[$accctr]['posted_by']		=	$transaction['created_by'];
					$entries[$accctr]['company_id']		=	$transaction['company_id'];
					$entries[$accctr]['stat']			=	1;
					$entries[$accctr]['transaction_id']	=	$last_transaction_id;
				}
				$this->Mmm->debug($entries);
				$checkEntries	=	$this->Mmm->multiInsert('ac_transaction_journal',$entries,'Entries for '.$transaction['remark']);
				if($checkEntries) {
					$this->Abas->sysMsg("sucmsg","Summary of entries created at transaction ID ".$last_transaction_id);
				}
				else {
					$this->Abas->sysMsg("errmsg","Critical accounting error in generation of summary of entries for transaction ID ".$last_transaction_id."!");
				}
				// die();
				$this->Abas->redirect(HTTP_PATH."accounting/journal/view_transaction/".$last_transaction_id);
			}
			$data['companies']			=	$this->Abas->getCompanies();
			$this->load->view("accounting/beginning_balances_form.php",$data);
		}
		public function accounts_payable_voucher($action,$id=''){
			switch ($action) {
				case 'load_rr':
					$data = array();
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$data = $this->Abas->createBSTable("ac_accounts_payable_for_clearing",$search,$limit,$offset,$order,$sort);

						foreach($data['rows'] as $ctr=>$row){
							if(isset($row['tdate'])){
								$data['rows'][$ctr]['delivery_date']	=	date('F j, Y',strtotime($row['tdate']));
							}
							if(isset($row['company_id'])){
								$company		=	$this->Abas->getCompany($row['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							if(isset($row['supplier_id'])){
								$supplier = $this->Abas->getSupplier($row['supplier_id']);
								$data['rows'][$ctr]['supplier_name']	=	$supplier['name'];
							}
							if(isset($row['amount'])){
								$data['rows'][$ctr]['amount']	=	number_format($row['amount'],2,'.',',');
							}
							if(isset($row['created_on'])){
								$data['rows'][$ctr]['created_on']	=	date("j F Y", strtotime($row['created_on']));
							}
							if(isset($row['created_by'])){
								$created_by		=	$this->Abas->getUser($row['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				break;

				case 'load_apv':
					$data = array();
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$data = $this->Abas->createBSTable("ac_ap_vouchers",$search,$limit,$offset,$order,$sort);

						foreach($data['rows'] as $ctr=>$row){
							if(isset($row['company_id'])){
								$company		=	$this->Abas->getCompany($row['company_id']);
								$data['rows'][$ctr]['company_name2']	=	$company->name;
							}
							if(isset($row['rr_no'])){
								$data['rows'][$ctr]['rr_no2']	=	$row['rr_no'];
							}
							if(isset($row['po_no'])){
								$data['rows'][$ctr]['po_no2']	=	$row['po_no'];
							}
							if(isset($row['control_number'])){
								$data['rows'][$ctr]['control_number2']	=	$row['control_number'];
							}
							if(isset($row['payee'])){
								$supplier = $this->Abas->getSupplier($row['payee']);
								$data['rows'][$ctr]['supplier_name2']	=	$supplier['name'];
							}
							if(isset($row['date_created'])){
								$data['rows'][$ctr]['apv_date']	=	date('F j, Y',strtotime($row['date_created']));
							}
							if(isset($row['rr_no'])){
								$rr = $this->Inventory_model->getDelivery($row['rr_no']);
								$data['rows'][$ctr]['amount2']	=	number_format($rr[0]['amount'],2,'.',',');
								$data['rows'][$ctr]['location2']	=	$rr[0]['location'];
							}
							if(isset($row['created_on'])){
								$data['rows'][$ctr]['created_on2']	=	date("j F Y", strtotime($row['created_on']));
							}
							if(isset($row['created_by'])){
								$created_by		=	$this->Abas->getUser($row['created_by']);
								$data['rows'][$ctr]['created_by2']	=	$created_by['full_name'];
							}
							
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				break;

				case 'listview':
					$data = array();
					$data['viewfile'] = "accounting/accounts_payable_voucher/page_tab.php";
					$this->load->view("gentlella_container.php",$data);
				break;

				case 'listview_rr':
					$this->load->view("accounting/accounts_payable_voucher/rr_listview.php");
				break;

				case 'listview_apv':
					$this->load->view("accounting/accounts_payable_voucher/ap_listview.php");
				break;

				case 'add':
					$data = array();

					$data['rr'] 	= $this->Inventory_model->getDelivery($id);
					$data['po'] 	= $this->Purchasing_model->getPurchaseOrder($data['rr'][0]['po_no']);
					$data['reconciling_entries'] = $this->Accounting_model->getAccountingEntry($id,'inventory_deliveries');
					$data['tax_codes'] = $this->Accounting_model->getExpandedTaxCodes();
					$data['company'] = $this->Abas->getCompany($data['rr'][0]['company_id']);
					$data['supplier'] = $this->Abas->getSupplier($data['rr'][0]['supplier_id']);

					$data['vessels']			=	$this->Abas->getVesselsByCompany($data['rr'][0]['company_id']);
					$data['departments']		=	$this->Abas->getDepartments();
					$data['contracts']			=	$this->Abas->getContracts($data['rr'][0]['company_id']);

					$this->load->view('accounting/accounts_payable_voucher/form.php',$data);
				break;

				case 'insert':
					$apv = array();
					
					if($_POST['rr_id']){

						$apv_date		= (isset($_POST['apv_date'])) ? date('Y-m-d H:i:s',strtotime($_POST['apv_date'])) : date('Y-m-d H:i:s');

						$rr_id = $this->Mmm->sanitize($_POST['rr_id']);
						$RR = $this->Inventory_model->getDelivery($rr_id);
						$rr_control_number = $RR[0]['control_number'];
						$company = $this->Abas->getCompany($RR[0]['company_id']);
						$control_number = $this->Abas->getNextSerialNumber('ac_ap_vouchers', $company->id);
						$PO 	= $this->Purchasing_model->getPurchaseOrder($RR[0]['po_no']);
						$payee  = $this->Abas->getSupplier($RR[0]['supplier_id']);
						$remark = $RR[0]['remark'];

						$apv['payee']		= 	$payee['id'];
						$apv['date_created']=	$apv_date;
						$apv['po_no']		=	$PO['id'];
						$apv['invoice_no']	=	$RR[0]['sales_invoice_no'];
						$apv['rr_no']		=	$rr_id;
						$apv['journal_id']	=	0;
						//$apv['status']		=	null;
						$apv['stat']		=	1;
						$apv['control_number'] = $control_number;
						$apv['company_id']	=	$company->id;
						$apv['created_on']	=	date("Y-m-d H:i:s");
						$apv['created_by']	=	$_SESSION['abas_login']['userid'];
						
						$checkAPV	=	$this->Mmm->dbInsert("ac_ap_vouchers", $apv, "Added new APV for Receiving Report with Transaction Code No.".$rr_id);

						$last_apv_id = $this->Abas->getLastIDByTable('ac_ap_vouchers');

						$this->db->query("UPDATE inventory_deliveries SET is_cleared=1 WHERE id=".$rr_id);//update the RR clearing status

						$transaction = $this->Accounting_model->getAccountingEntry($rr_id,'inventory_deliveries');
						$last_transaction_id = $transaction[0]['tid'];//get the PO/RR Accounting Trans ID

						if($checkAPV){

							$multiAttach = array();

							$target_dir = WPATH.'assets/uploads/accounting/accounts_payable/attachments/';

							foreach($_POST['attachment'] as $ctr=>$val){

								$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
								$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);

								if(end($old_filename)!=""){
									$multiAttach[$ctr]['ap_voucher_id']	=	$last_apv_id;
									$multiAttach[$ctr]['document_name'] 	= 	$this->Mmm->sanitize($_POST['attachment'][$ctr]);
									$multiAttach[$ctr]['document_file'] 	= 	$new_filename;
									$multiAttach[$ctr]['stat'] 	= 	1;

									$target_file = $target_dir . $new_filename;
									$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
								}
							}

							if(count($multiAttach)>0){
								$checkAttach = $this->Mmm->multiInsert("ac_ap_voucher_attachments",$multiAttach,'Inserted attachments for Accounts Payables with Transaction Code No. ' . $last_apv_id);
							}else{
								$checkAttach = TRUE;
							}

							if($checkAttach){

								$entry = array();

								foreach($_POST['coa_id'] as $ctr=>$val){

									$entry['transaction_id'] 	= $last_transaction_id;
									$entry['remark'] 			= $remark;
									$entry['company'] 			= $company->id;
									$entry['reference_table'] 	= 'ac_ap_vouchers';
									$entry['reference_id'] 		= $last_apv_id;
									$entry['posted_on']			= $apv_date;
									$entry['debit_amount']		= $this->Mmm->sanitize($_POST['debit'][$ctr]);
									$entry['credit_amount']		= $this->Mmm->sanitize($_POST['credit'][$ctr]);

									$department = $this->Accounting_model->getDepartmentIDByAccountingCode($this->Mmm->sanitize($_POST['department'][$ctr]));

									$entry['department']		= isset($department->id)?$department->id:0;
									$entry['vessel']			= $this->Mmm->sanitize($_POST['vessel'][$ctr]);
									$entry['contract']			= $this->Mmm->sanitize($_POST['contract'][$ctr]);
									$entry['account']			= $this->Mmm->sanitize($_POST['coa_id'][$ctr]);
									$entry['stat']				= 1;//included to books already since no online approval yet

									$checkEntry = $this->Accounting_model->newJournalEntry($entry);
									
								}	

								if(isset($_POST['atc_codeX'])){
									$multiWTax = array();
									foreach($_POST['atc_codeX'] as $ctrx=>$valx){

										$multiWTax[$ctrx]['ap_voucher_id'] 	= $last_apv_id;
										$multiWTax[$ctrx]['wtax_amount'] 	= $this->Mmm->sanitize($_POST['wtax_amountX'][$ctrx]);
										$multiWTax[$ctrx]['atc'] 	= $this->Mmm->sanitize($_POST['atc_codeX'][$ctrx]);
										$multiWTax[$ctrx]['atc_description'] 	= $this->Mmm->sanitize($_POST['atc_descriptionX'][$ctrx]);
										$multiWTax[$ctrx]['taxable_amount'] 	= $this->Mmm->sanitize($_POST['taxable_amountX'][$ctrx]);
										$multiWTax[$ctrx]['tax_rate'] 	= $this->Mmm->sanitize($_POST['tax_rateX'][$ctrx]);
										$multiWTax[$ctrx]['stat'] 	= 1;
										
									}

									$checkATC = $this->Mmm->multiInsert("ac_ap_voucher_wtax",$multiWTax,'Inserted attachments for Accounts Payable Voucher with Transaction Code No. '.$last_apv_id);
								}else{
									$checkATC = true;	
								}

								if($checkEntry && $checkATC){

									$reconcile = $this->Accounting_model->reconcileEntries($last_transaction_id,AP_CLEARING);
										if($reconcile==FALSE){
											$this->Abas->sysMsg("errmsg", "Problem reconciling AP clearing entry.");
										}else{

											$this->Abas->sysNotif("Accounts Payable Voucher", $_SESSION['abas_login']['fullname']." has successfully created APV with Control No." . $control_number . " under company " . $company->name,"Accounting","info");

											$this->Abas->sysMsg("sucmsg","Successfully created the Accounts Payable Voucher for with Control No." . $control_number . " under company " . $company->name);

										}
									
								}else{
									$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the Accounts Payable Voucher! Please try again.");
									$this->Abas->redirect(HTTP_PATH."accounting/accounts_payable_voucher/listview");
									die();
								}

							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while uploading the attachments for this Accounts Payable Voucher! Please try again.");
								$this->Abas->redirect(HTTP_PATH."accounting/accounts_payable_voucher/listview");
								die();
							}

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the Accounts Payable Voucher! Please try again.");
							$this->Abas->redirect(HTTP_PATH."accounting/accounts_payable_voucher/listview");
								die();
						}

						$this->Abas->redirect(HTTP_PATH."accounting/accounts_payable_voucher/listview");

					}

				break;

				case 'view':
					$data = array();
					$data['apv']	=	$this->Accounting_model->getAccountsPayableVoucher($id);
					$data['apv_entries']	=	$this->Accounting_model->getAccountingEntry($id,'ac_ap_vouchers');
					$data['apv_wtax']	=	$this->Accounting_model->getAccountsPayableWTax($id);
					$data['apv_attachments']	=	$this->Accounting_model->getAccountsPayableAttachments($id);
					$data['reconciling_entries'] = $this->Accounting_model->getAccountingEntry($data['apv'][0]['rr_no'],'inventory_deliveries');
					$data['rr']= $this->Inventory_model->getDelivery($data['apv'][0]['rr_no']);
					$data['company']= $this->Abas->getCompany($data['apv'][0]['company_id']);
					$data['po']	= $this->Purchasing_model->getPurchaseOrder($data['apv'][0]['po_no']);
					$data['supplier'] = $this->Abas->getSupplier($data['po']['supplier_id']);
					$this->load->view('accounting/accounts_payable_voucher/form.php',$data);
				break;

				case 'print':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data = array();
					$data['APV']	=	$this->Accounting_model->getAccountsPayableVoucher($id);
					$data['APV_entries']	=	$this->Accounting_model->getAccountingEntry($id,'ac_ap_vouchers');
					$data['apv_wtax']	=	$this->Accounting_model->getAccountsPayableWTax($id);
					$data['RR']= $this->Inventory_model->getDelivery($data['APV'][0]['rr_no']);
					$data['company']= $this->Abas->getCompany($data['APV'][0]['company_id']);
					$data['PO']	= $this->Purchasing_model->getPurchaseOrder($data['APV'][0]['po_no']);
					$data['supplier'] = $this->Abas->getSupplier($data['PO']['supplier_id']);
					$this->load->view('accounting/accounts_payable_voucher/print.php',$data);
				break;

				case 'get_ATC_description':
					$tax = $this->Accounting_model->getExpandedTaxCodes($id);
					echo json_encode($tax[0]);
				break;
			}
		}
		public function check_voucher($action,$id='',$idx=''){
			switch ($action) {
				case 'load_cv':
					$data = array();
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	'DESC';
						$sort	=	'created_on';
						$data = $this->Abas->getDataForBSTable("ac_vouchers",$search,$limit,$offset,$order,$sort,"status='For releasing'");

						foreach($data['rows'] as $ctr=>$row){
							if(isset($row['company_id'])){
								$company		=	$this->Abas->getCompany($row['company_id']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							if($row['transaction_type']=='po'){
								if($row['payee_type']=='Supplier'){
									$payee = $this->Abas->getSupplier($row['payee']);
									$data['rows'][$ctr]['payee_name']	=	$payee['name'] . "<br>(Supplier)";
								}elseif($row['payee_type']=='Employee'){
									$payee = $this->Abas->getEmployee($row['payee']);
									$data['rows'][$ctr]['payee_name']	=	$payee['full_name'] . "<br>(Employee)";
								}else{
									$payee = $this->Abas->getSupplier($row['payee']);
									$data['rows'][$ctr]['payee_name']	=	$payee['name'] . "<br>(Supplier)";
								}
							}elseif($row['transaction_type']=='non-po'){
								$rfp = $this->Accounting_model->getRequestPayment($row['apv_no']);

								if($rfp[0]['payee_type']=='Supplier'){
									$payee = $this->Abas->getSupplier($rfp[0]['payee']);
									$data['rows'][$ctr]['payee_name']	=	$payee['name'] . "<br>(Supplier)";
								}elseif($rfp[0]['payee_type']=='Employee'){
									$payee = $this->Abas->getEmployee($rfp[0]['payee']);
									$data['rows'][$ctr]['payee_name']	=	$payee['full_name'] . "<br>(Employee)";
								}

								if($rfp[0]['payee']==''){
									$data['rows'][$ctr]['payee_name']	= $rfp[0]['payee_others'];
								}
								
							}
							if(isset($row['bank_id'])){
								$account = $this->Accounting_model->getAccount($row['bank_id']);
								$data['rows'][$ctr]['bank_account']	=	$account['name'];
							}
							if(isset($row['transaction_type'])){
								if($row['transaction_type']=='po'){
									$data['rows'][$ctr]['transaction_type']	=	"PO";
								}else{
									$data['rows'][$ctr]['transaction_type']	=	"Non-PO";
								}
							}
							if($row['check_num']=='' || $row['check_num']==0){
								$data['rows'][$ctr]['check_num']	=	"~UBP Check Writer~";
							}
							if(isset($row['check_date'])){
								$data['rows'][$ctr]['check_date']	=	date("F j, Y", strtotime($row['check_date']));
							}
							if(isset($row['amount'])){
								$data['rows'][$ctr]['amount']	=	number_format($row['amount'],2,'.',',');
							}
							if(isset($row['created_on'])){
								$data['rows'][$ctr]['created_on']	=	date("j F Y", strtotime($row['created_on']));
							}
							if(isset($row['created_by'])){
								$created_by		=	$this->Abas->getUser($row['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if(isset($row['verified_on'])){
								$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
							}
							if(isset($row['verified_by'])){
								$modified_by		=	$this->Abas->getUser($row['verified_by']);
								$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
							}
							if(isset($row['approved_on'])){
								$data['rows'][$ctr]['approved_on']	=	date("j F Y h:i:s A", strtotime($row['approved_on']));
							}
							if(isset($row['approved_by'])){
								$modified_by		=	$this->Abas->getUser($row['approved_by']);
								$data['rows'][$ctr]['approved_by']	=	$modified_by['full_name'];
							}
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				break;

				case 'listview':
					$data = array();
					$data['viewfile'] = "accounting/check_voucher/page_tab.php";
					$this->load->view("gentlella_container.php",$data);
				break;

				case 'listview_apv':
					$data = array();
					$data['apv_processing'] = $this->Accounting_model->getAPVperSupplier();
					$this->load->view("accounting/check_voucher/ap_listview.php",$data);
				break;

				case 'listview_rfp':
					$data = array();
					$data['rfp_processing'] = $this->Accounting_model->getRFPsForVoucher();
					$this->load->view("accounting/check_voucher/rfp_listview.php",$data);
				break;

				case 'listview_cv':
					$this->load->view("accounting/check_voucher/cv_listview.php");
				break;

				case 'add':
					$data = array();
					if($idx=='PO'){
						$data['supplier'] = $this->Abas->getSupplier($id);
						$data['supplier_apvs'] = $this->Accounting_model->getAPVperSupplier($id);
						$data['companies'] = $this->Abas->getCompanies();

						$this->load->view("accounting/check_voucher/form_po.php",$data);

					}elseif($idx=='Non-PO'){

						$data['vessels']			=	$this->Abas->getVessels();
						$data['departments']		=	$this->Abas->getDepartments();
						$data['contracts']			=	$this->Abas->getContracts();

						$data['rfp'] = $this->Accounting_model->getRequestPayment($id);
						$data['company'] = $this->Abas->getCompany($data['rfp'][0]['company_id']);

						$data['banks'] =	$this->Accounting_model->getBanksFromCOA($data['company']->id);

						if($data['rfp'][0]['payee_type']=='Employee'){
							$payee = $this->Abas->getEmployee($data['rfp'][0]['payee']);
							$data['payee_name'] = $payee['full_name'];
						}else{
							$payee = $this->Abas->getSupplier($data['rfp'][0]['payee']);
							$data['payee_name'] = $payee['name'];
						}
						if($data['rfp'][0]['payee']==''){
							$data['payee_name'] = $data['rfp'][0]['payee_others'];
						}

						$this->load->view("accounting/check_voucher/form_non_po.php",$data);
					}
				break;

				case 'insert':
					$insert = array();
					if($id=='PO'){
						$multi_apv = array();
						if($_POST){
							$insert['company_id'] = $this->Mmm->sanitize($_POST['company']);
							$company = $this->Abas->getCompany($insert['company_id']);
							if(count($_POST['merge_to_cv'])==1){
								$insert['apv_no'] = $this->Mmm->sanitize($_POST['merge_to_cv'][0]);
								array_push($multi_apv,$insert['apv_no']);
							}else{
								foreach($_POST['merge_to_cv'] as $ctr=>$val){
									$apv = $this->Mmm->sanitize($_POST['merge_to_cv'][$ctr]);
									array_push($multi_apv,$apv);	
								}
								$insert['multi_apv_no'] = implode(',',$multi_apv);
							}

							$insert['type'] ='Check Voucher';
							$insert['bank_id'] = $this->Mmm->sanitize($_POST['bank']);
							$insert['check_num'] = $this->Mmm->sanitize($_POST['check_num']);
							$insert['check_date'] = $this->Mmm->sanitize($_POST['check_date']);
							$insert['created_on']	=	date('Y-m-d H:i:s');
							$insert['created_by']	=	$_SESSION['abas_login']['userid'];
							$tp_debit = $this->Mmm->sanitize($_POST['tp_debit']);
							$bank_credit =$this->Mmm->sanitize($_POST['bank_credit']);
							$insert['amount']	=	$tp_debit;
							$insert['payee'] = $this->Mmm->sanitize($_POST['payee']);
							$supplier = $this->Abas->getSupplier($_POST['payee']);
							$insert['payee_type'] = 'Supplier';
							$insert['remark'] = $this->Mmm->sanitize($_POST['particulars']);
							$insert['added_by']	=	$_SESSION['abas_login']['userid'];
							$insert['voucher_date'] = $this->Mmm->sanitize($_POST['cv_date']);
							$insert['stat'] =1;
							$insert['status'] ='For releasing';
							$insert['transaction_type'] ='po';
							$control_number = $this->Abas->getNextSerialNumber('ac_vouchers', $insert['company_id'] );
							$insert['control_number'] = $control_number;
							$checkInsert = $this->Mmm->dbInsert('ac_vouchers',$insert,'Added new Check Voucher with Control No.'.$control_number.' for payee '.$supplier['name'].' under payor '.$company->name);

							$last_cv_id = $this->Abas->getLastIDByTable('ac_vouchers');				

							if($checkInsert){
								$entry = array();
								$total_tp = 0;
								$total_bank = 0;
								foreach($multi_apv as $ctx=>$apv_id){
									$ap_voucher = $this->Accounting_model->getAPVoucher($apv_id);

									$this->Mmm->query('UPDATE ac_ap_vouchers SET check_voucher_id='.$last_cv_id.' WHERE id='.$apv_id,'Add CV reference ID for APV with TSCode No.'.$apv_id);

									$this->Mmm->query('UPDATE inventory_deliveries SET voucher_id='.$last_cv_id." WHERE id=".$ap_voucher[0]['rr_no'],'Add CV reference ID for RR with TSCode No.'.$ap_voucher[0]['rr_no']);

									$rr_entries = $this->Accounting_model->getAccountingEntry($ap_voucher[0]['rr_no'],'inventory_deliveries');
									$last_transaction_id = $rr_entries[0]['tid'];

									$apv_entries = $this->Accounting_model->getAccountingEntry($apv_id,'ac_ap_vouchers');
									$amount =0;
									foreach($apv_entries as $row){
										if($row['coa_id'] == TRADE_PAYABLE){//get Trade receivale amount
											$amount = $row['credit_amount'];
										}
									}

									//trade payable
									$entry_tp['transaction_id'] 	= $last_transaction_id;
									$entry_tp['remark'] 			= $insert['remark'];
									$entry_tp['company'] 			= $insert['company_id'];
									$entry_tp['reference_table'] 	= 'ac_vouchers';
									$entry_tp['reference_id'] 		= $last_cv_id;
									$entry_tp['posted_on']			= date('Y-m-d H:m:s',strtotime($insert['voucher_date']));
									$entry_tp['debit_amount']		= $amount;
									$entry_tp['credit_amount']		= 0;
									$entry_tp['department']			= 0;
									$entry_tp['vessel']				= 0;
									$entry_tp['contract']			= 0;
									$entry_tp['account']			= TRADE_PAYABLE;
									$entry_tp['stat']				= 1;//included to books already since no online approval yet
									$checkEntry1 = $this->Accounting_model->newJournalEntry($entry_tp);
									$total_tp = $total_tp + $amount;
									//bank
									$entry_bk['transaction_id'] 	= $last_transaction_id;
									$entry_bk['remark'] 			= $insert['remark'];
									$entry_bk['company'] 			= $insert['company_id'];
									$entry_bk['reference_table'] 	= 'ac_vouchers';
									$entry_bk['reference_id'] 		= $last_cv_id;
									$entry_bk['posted_on']			=  date('Y-m-d H:m:s',strtotime($insert['voucher_date']));
									$entry_bk['debit_amount']		= 0;
									$entry_bk['credit_amount']		= $amount;
									$entry_bk['department']			= 0;
									$entry_bk['vessel']				= 0;
									$entry_bk['contract']			= 0;
									$entry_bk['account']			= $insert['bank_id'];//get the bank coa id
									$entry_bk['stat']				= 1;//included to books already since no online approval yet
									$checkEntry2 = $this->Accounting_model->newJournalEntry($entry_bk);
									$total_bank = $total_bank + $amount;

								}

								if($tp_debit==$total_tp && $bank_credit==$total_bank){
									$this->Abas->sysNotif("Check Voucher", $_SESSION['abas_login']['fullname']." has successfully created CV with Control No." . $control_number . " for payee ".$supplier['name']." under payor ".$company->name,"Accounting","info");

									$this->Abas->sysMsg("sucmsg","Successfully created Check Voucher with Control No." . $control_number . " for payee ".$supplier['name']." under payor ".$company->name);
									$this->Abas->redirect(HTTP_PATH."accounting/check_voucher/listview");
								}else{

									$this->Abas->sysNotif("Check Voucher", $_SESSION['abas_login']['fullname']." has successfully created CV with Control No." . $control_number . " for payee ".$supplier['name']." under payor ".$company->name,"Accounting","info");

									$this->Abas->sysMsg("warnmsg", "Successfully created Check Voucher with Control No." . $control_number . " for payee ".$supplier['name']." under payor ".$company->name. " but there were entrie(s) that might be unbalanced eg.(.1) difference. Kindly check the transaction history.");
									$this->Abas->redirect(HTTP_PATH."accounting/check_voucher/listview");
								}
								
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred creating the Check Voucher! Please contact ypur admininistrator.");
								$this->Abas->redirect(HTTP_PATH."accounting/check_voucher/listview");
								die();
							}
						}
					}elseif($id=='Non-PO'){

						if($_POST){
							$insert['company_id'] = $this->Mmm->sanitize($_POST['company']);
							$company = $this->Abas->getCompany($insert['company_id']);
							$insert['type'] ='Check Voucher';
							$insert['bank_id'] = $this->Mmm->sanitize($_POST['bank']);
							$insert['check_num'] = $this->Mmm->sanitize($_POST['check_num']);
							$insert['check_date'] = $this->Mmm->sanitize($_POST['check_date']);
							$insert['created_on']	=	date('Y-m-d H:i:s');
							$insert['created_by']	=	$_SESSION['abas_login']['userid'];
							$insert['amount']	=	$this->Mmm->sanitize($_POST['rfp_amount']);

							$rfp_id =  $this->Mmm->sanitize($_POST['rfp_no']);
							$insert['apv_no'] = $rfp_id;
							$rfp = $this->Accounting_model->getRequestPayment($rfp_id);
							if($rfp[0]['payee_type']=='Supplier'){
								$supplier = $this->Abas->getSupplier($_POST['payee']);
								$payee_name = $supplier['name'];
							}else{
								$employee = $this->Abas->getEmployee($_POST['payee']);
								$payee_name = $employee['full_name'];
							}
							if($rfp[0]['payee']==''){
								$payee_name = $rfp[0]['payee_others'];
							}

							$insert['payee'] = $this->Mmm->sanitize($_POST['payee']);
							$insert['payee_type'] = $rfp[0]['payee_type'];
							$insert['remark'] = $this->Mmm->sanitize($_POST['particulars']);
							$insert['added_by']	=	$_SESSION['abas_login']['userid'];
							$insert['voucher_date'] = $this->Mmm->sanitize($_POST['cv_date']);
							$insert['stat'] =1;
							$insert['status'] ='For releasing';
							$insert['transaction_type'] ='non-po';
							$control_number = $this->Abas->getNextSerialNumber('ac_vouchers', $insert['company_id'] );
							$insert['control_number'] = $control_number;
							$checkInsert = $this->Mmm->dbInsert('ac_vouchers',$insert,'Added new Check Voucher with Control No.'.$control_number.' for payee '.$payee_name.' under payor '.$company->name);

							$last_cv_id = $this->Abas->getLastIDByTable('ac_vouchers');

							if($checkInsert){
								$entry = array();
								$insertTrans = array();

								$this->Mmm->query('UPDATE ac_request_payment SET voucher_id='.$last_cv_id.",status='For releasing' WHERE id=".$rfp_id,'Add CV reference ID for RFP with TSCode No.'.$rfp_id);

								//Create new journal transaction for RFP
								$insertTrans['date']		= 	$insert['voucher_date'];
								$insertTrans['remark']		= 	"RFP# ".$rfp_id." Particular: ".$insert['remark'];
								$insertTrans['status']		= 	'Active';
								$insertTrans['stat']			=	1;
								$insertTrans['reference_table']	=	'ac_request_payment';
								$insertTrans['reference_id']	=	$rfp_id;
								$insertTrans['company_id']	= 	$company->id;
								$insertTrans['created_on']	= 	date('Y-m-d');
								$insertTrans['created_by']	= 	$_SESSION['abas_login']['userid'];
								$checkTransaction =	$this->Mmm->dbInsert("ac_transactions", $insertTrans, "New transaction added for RFP with TSCode No.".$rfp_id);
								$last_transaction_id = $this->Abas->getLastIDByTable('ac_transactions');

								if($checkTransaction){
									foreach($_POST['coa_id'] as $ctr=>$val){

										$entry['transaction_id'] 	= $last_transaction_id;
										$entry['remark'] 			= $this->Mmm->sanitize($_POST['memo'][$ctr]);
										$entry['company'] 			= $company->id;
										$entry['reference_table'] 	= 'ac_vouchers';
										$entry['reference_id'] 		= $last_cv_id;
										$entry['posted_on']			= $insert['voucher_date'];
										$entry['debit_amount']		= $this->Mmm->sanitize($_POST['debit'][$ctr]);
										$entry['credit_amount']		= $this->Mmm->sanitize($_POST['credit'][$ctr]);

										$department = $this->Accounting_model->getDepartmentIDByAccountingCode($this->Mmm->sanitize($_POST['department'][$ctr]));

										$entry['department']		= isset($department->id)?$department->id:0;
										$entry['vessel']			= $this->Mmm->sanitize($_POST['vessel'][$ctr]);
										$entry['contract']			= $this->Mmm->sanitize($_POST['contract'][$ctr]);
										$entry['account']			= $this->Mmm->sanitize($_POST['coa_id'][$ctr]);
										$entry['stat']				= 1;//included to books already since no online approval yet

										$checkEntry = $this->Accounting_model->newJournalEntry($entry);
										
									}


									if($checkEntry){
										$this->Abas->sysNotif("Check Voucher", $_SESSION['abas_login']['fullname']." has successfully created CV with Control No." . $control_number . " for payee ".$payee_name." under payor ".$company->name,"Accounting","info");

										$this->Abas->sysMsg("sucmsg","Successfully created Check Voucher with Control No." . $control_number . " for payee ".$payee_name." under payor ".$company->name);
										$this->Abas->redirect(HTTP_PATH."accounting/check_voucher/listview");
									}else{
										$this->Abas->sysMsg("errmsg", "An error has occurred creating the Check Voucher! Please contact ypur admininistrator.");
										$this->Abas->redirect(HTTP_PATH."accounting/check_voucher/listview");
										die();
									}

								}else{
									$this->Abas->sysMsg("errmsg", "An error has occurred creating the Check Voucher! Please contact ypur admininistrator.");
									$this->Abas->redirect(HTTP_PATH."accounting/check_voucher/listview");
									die();
								}
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred creating the Check Voucher! Please contact ypur admininistrator.");
								$this->Abas->redirect(HTTP_PATH."accounting/check_voucher/listview");
								die();
							}
						}
					}
				break;

				case 'print':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data = array();
					$apv_ids = array();

					$data['CV']	=	$this->Accounting_model->getVoucher($id);
					$data['CV_entries'] = $this->Accounting_model->getAccountingEntry($id,'ac_vouchers');
					$data['company']= $this->Abas->getCompany($data['CV']['company_id']);

					$data['payee_name'] = '';
					if($data['CV']['payee_type']=='Employee'){
						$payee = $this->Abas->getEmployee($data['CV']['payee']);
						$data['payee_name'] = $payee['full_name'];
					}else{
						$payee = $this->Abas->getSupplier($data['CV']['payee']);
						$data['payee_name'] = $payee['name'];
					}
					if($data['CV']['payee']=='' && $data['CV']['transaction_type']=='non-po'){
						$rfp = $this->Accounting_model->getRequestPayment($data['CV']['apv_no']);
						$data['payee_name'] = $rfp[0]['payee_others'];
					}
					if($idx=='legal'){
						$data['papersize'] = 'legal';
					}else{
						$data['papersize'] = 'letter';
					}
					$this->load->view('accounting/check_voucher/print.php',$data);
				break;

				case 'get_APV_supplier_by_company':
					$data = $this->Accounting_model->getAPVperSupplier($id,$idx);
					echo json_encode($data);
				break;

				case 'get_APV_amount':
					$ac = $this->Accounting_model->getAccountingEntry($id,'ac_ap_vouchers');
					$amount =0;
					foreach($ac as $row){
						if($row['coa_id'] == TRADE_PAYABLE){//get Trade receivale amount
							$amount = $row['credit_amount'];
						}
					}
					echo json_encode($amount);
				break;

				case 'get_bank_by_coa':
					 $data =	$this->Accounting_model->getAccount($id);
					 echo json_encode($data);
				break;

				case 'get_banks_by_company':
					 $data =	$this->Accounting_model->getBanksFromCOA($id);
					 echo json_encode($data);
				break;

				case 'export_textfile':

					$file_name = "UBTXT_".round(microtime(true)). rand(999999,99999999);
					$file_extension = ".txt";
					$file_url = WPATH."assets/downloads/accounting/ub_exports/".$file_name.$file_extension;
					$handle = fopen($file_url, "w");
					if($_POST['cv_id']){
						foreach($_POST['cv_id'] as $cv){
							fwrite($handle, $cv);
						}
					}
				    fclose($handle);
				    header('Content-Type: application/octet-stream');
				    header('Content-Disposition: attachment; filename='.basename($file_name.$file_extension));
				    header('Expires: 0');
				    header('Cache-Control: must-revalidate');
				    header('Pragma: public');
				    header('Content-Length: ' . filesize($file_url));
				    ob_clean();
				  	flush();
				  	readfile($file_url);

				break;
				case 'export_template':

				  	require_once WPATH.'assets/phpexcel/Classes/PHPExcel/IOFactory.php';
				  	$objPHPExcel = new PHPExcel();

				  	$file_name = "AV_UB".round(microtime(true)). rand(999999,99999999);
					$file_extension = ".xls";
					header('Content-Type: application/vnd.ms-excel');
					header('Content-Disposition: attachment;filename="' . $file_name.$file_extension . '"');
					header('Cache-Control: max-age=0');
					
					//set up the file properties
					$objPHPExcel->getProperties()->setCreator("ABAS");
					$objPHPExcel->getProperties()->setLastModifiedBy("ABAS");
					$objPHPExcel->getProperties()->setTitle("CV Export for UB Template");
					$objPHPExcel->getProperties()->setSubject("CV Export for UB Template");
					$objPHPExcel->getProperties()->setDescription("CV Export for UB Template");
					
					//set up the column titles at row 1
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'CV / Ref No');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Payee Name');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Check Amt.');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Particulars');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Check Date');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'WTaxAmt');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'ATC');
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'ATDescription');
					$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'TaxableAmt');
					$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'BeneTIN');
					$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'NotifyName');
					$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'NotifyAddress1');
					$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'NotifyAddress2');
					$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'ACCOUNT CODE');
					$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'ACCOUNT NAME');
					$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'DEBIT');
					$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'CREDIT');

					$last_line_per_row =2;///start at row 2

					$inv_ctr = 1;
					if($_POST['cv_id']){
						foreach($_POST['cv_id'] as $id){
							$payee_name = "";
							$tin = "";
							$address1 = "";
							$address2 = "";
							$contact_person = "";
							$voucher = $this->Accounting_model->getVoucher($id);
							if($voucher['payee_type']=='Supplier'){
								$payee = $this->Abas->getSupplier($voucher['payee']);
								$payee_name = $payee['name'];
								$tin = $payee['tin'];
								$address1 = $payee['address'];
								$contact_person = $payee['contact_person'];
							}else{
								$payee = $this->Abas->getEmployee($voucher['payee']);
								$payee_name = $payee['full_name'];
								$tin = $payee['tin_num'];
								$address1 = $payee['address'];
								$address2 = $payee['address2'].$payee['city'];
								$contact_person = $payee['full_name'];
							}
							$amount = number_format($voucher['amount'],2,'.','');
							$particulars = $voucher['remark'];
							$check_date = date('m/d/Y',strtotime($voucher['check_date']));

							$entries = $this->Accounting_model->getAccountingEntry($id,'ac_vouchers');


							$temp_row = $last_line_per_row;//store the row number where the voucher begins

							$objPHPExcel->getActiveSheet()->SetCellValue('A'.$last_line_per_row,$id);
							$objPHPExcel->getActiveSheet()->SetCellValue('B'.$last_line_per_row,$payee_name);
							$objPHPExcel->getActiveSheet()->SetCellValue('C'.$last_line_per_row,$amount);
							$objPHPExcel->getActiveSheet()->getStyle("C".$last_line_per_row)->getNumberFormat()->setFormatCode('0.00'); 
							$objPHPExcel->getActiveSheet()->SetCellValue('D'.$last_line_per_row,$particulars);
							$objPHPExcel->getActiveSheet()->SetCellValue('E'.$last_line_per_row,$check_date);

							$objPHPExcel->getActiveSheet()->SetCellValue('J'.$last_line_per_row,$tin);
							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$last_line_per_row,$contact_person);
							$objPHPExcel->getActiveSheet()->SetCellValue('L'.$last_line_per_row,$address1);
							$objPHPExcel->getActiveSheet()->SetCellValue('M'.$last_line_per_row,$address2);


							$wtax = array();
							if($voucher['transaction_type']=='po'){
								if($voucher['apv_no']==''){
									$apv_ids = explode(',',$voucher['multi_apv_no']);
									$last_line_per_row = $temp_row;//set the beginning row number of the wtax based on the voucher begins
									foreach($apv_ids as $apv_id){
										$wtax = $this->Accounting_model->getAccountsPayableWTax($apv_id);

										
										if(isset($wtax)){
											foreach($wtax as $tax){

												$wtax_amount = number_format($tax['wtax_amount'],2,'.','');
												$atc = $tax['atc'];
												$atc_description = $tax['atc_description'];
												$taxable_amount = number_format($tax['taxable_amount'],2,'.','');

												$objPHPExcel->getActiveSheet()->SetCellValue('F'.$last_line_per_row,$wtax_amount);
												$objPHPExcel->getActiveSheet()->SetCellValue('G'.$last_line_per_row,$atc);
												$objPHPExcel->getActiveSheet()->SetCellValue('H'.$last_line_per_row,$atc_description);
												$objPHPExcel->getActiveSheet()->SetCellValue('i'.$last_line_per_row,$taxable_amount);

												$last_line_per_row = $objPHPExcel->getActiveSheet()->getHighestDataRow();
												$last_line_per_row = $last_line_per_row + 1;//increments to next row
											}
										}

									}
								}else{
									$wtax = $this->Accounting_model->getAccountsPayableWTax($voucher['apv_no']);

									$last_line_per_row = $temp_row;//set the beginning row number of the wtax based on the voucher begins
									if(isset($wtax)){
										foreach($wtax as $tax){

											$wtax_amount = number_format($tax['wtax_amount'],2,'.','');
											$atc = $tax['atc'];
											$atc_description = $tax['atc_description'];
											$taxable_amount = number_format($tax['taxable_amount'],2,'.','');

											$objPHPExcel->getActiveSheet()->SetCellValue('F'.$last_line_per_row,$wtax_amount);
											$objPHPExcel->getActiveSheet()->SetCellValue('G'.$last_line_per_row,$atc);
											$objPHPExcel->getActiveSheet()->SetCellValue('H'.$last_line_per_row,$atc_description);
											$objPHPExcel->getActiveSheet()->SetCellValue('i'.$last_line_per_row,$taxable_amount);

											$last_line_per_row = $objPHPExcel->getActiveSheet()->getHighestDataRow();
											$last_line_per_row = $last_line_per_row + 1;//increments to next row
										}
									}
								}
							}


							$last_line_per_row = $temp_row;//set the beginning row number of the accounting entries based on the voucher begins
							foreach($entries as $entry){
								
								$account = $this->Accounting_model->getAccount($entry['coa_id']);
								$dept_code = ($entry['department_id']!= NULL ) ? $entry['department_id'] : 00;
								$vessel_code= ($entry['vessel_id']!= NULL ) ? $entry['vessel_id'] : 000;
								$contract_code= ($entry['contract_id']!= NULL )? $entry['contract_id'] : 0000;
								$account_code = 	'00000'.sprintf('%02d',$dept_code).''.sprintf('%03d',$vessel_code).''.sprintf('%04d',$contract_code).'-'.$account['financial_statement_code'].''.$account['general_ledger_code'];

								$objPHPExcel->getActiveSheet()->SetCellValue('N'.$last_line_per_row,$account_code);
								$objPHPExcel->getActiveSheet()->SetCellValue('O'.$last_line_per_row,$account['name']);
								$debit_amount = number_format($entry['debit_amount'],2,'.','');
								$credit_amount = number_format($entry['credit_amount'],2,'.','');
								$objPHPExcel->getActiveSheet()->SetCellValue('P'.$last_line_per_row,$debit_amount);
								$objPHPExcel->getActiveSheet()->getStyle("P".$last_line_per_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
								$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$last_line_per_row,$credit_amount);
								$objPHPExcel->getActiveSheet()->getStyle("Q".$last_line_per_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

								$last_line_per_row = $last_line_per_row + 1;//increments to next row
								
							}
							
							$last_line_per_row = $objPHPExcel->getActiveSheet()->getHighestDataRow();
							$last_line_per_row = $last_line_per_row + 2;//offsets row for spacing between vouchers
						}
					}

					//set the cursor to cell A1
					$objPHPExcel->getActiveSheet()->setSelectedCell('A1');

					//set-up the headers and output the spreadsheet as downloadable file
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="'.$file_name.$file_extension.'"');
					header('Cache-Control: max-age=0');
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					$objWriter->save('php://output');
					exit();

				break;
			}
		}
		public function checkFinancialStatementCodeIfExist($fs_code){
			//checks if there is a similar financial statement code already added 
			$sql = "SELECT financial_statement_code FROM ac_accounts WHERE financial_statement_code=".$fs_code;
			$query = $this->db->query($sql);
			if($query){
				$result = $query->row();
				$fs = $result->financial_statement_code;
				if($fs!='' || !empty($fs)){
					$return = 'has';
				}
			}else{
				$return = 'nope';
			}
			echo  json_encode($return) ;
		}
		public function accounting_entries_summary_report($action=''){
			$data = array();
			switch ($action) {
				case 'filter':
					$data['companies'] = $this->Abas->getCompanies();
					$this->load->view('accounting/summary_report/accounting_entries/filter.php',$data);
					break;
				case 'result':
					if(isset($_POST)){
						$company_id = $this->Mmm->sanitize($_POST['company']);
						$from_date = $this->Mmm->sanitize($_POST['date_from']);
						$to_date = $this->Mmm->sanitize($_POST['date_to']);
						$status = $this->Mmm->sanitize($_POST['status']);
						if($status=='posted'){
							$sql = "SELECT * FROM ac_transaction_journal WHERE company_id=".$company_id." AND stat=1 AND posted_on BETWEEN '".$from_date."' AND '".$to_date."' ORDER BY coa_id,vessel_id,contract_id,department_id ASC";
						}else{
							$sql = "SELECT * FROM ac_transaction_journal WHERE company_id=".$company_id." AND stat=0 AND posted_on BETWEEN '".$from_date."' AND '".$to_date."' ORDER BY coa_id,vessel_id,contract_id,department_id ASC";
						}
					
						$query = $this->db->query($sql);
						if($query){
							$result = $query->result();
							foreach($result as $ctr=>$row){
								$company = $this->Abas->getCompany($row->company_id);
								$result[$ctr]->company_name =$company->name;
								if($row->vessel_id<>0){
									$vessel = $this->Abas->getVessel($row->vessel_id);
									$result[$ctr]->vessel_name = $vessel->name;
								}else{
									$result[$ctr]->vessel_name = "-";
								}
								if($row->contract_id<>0){
									$contract = $this->Abas->getContract($row->contract_id);
									$result[$ctr]->contract_name = $contract['reference_no'];
								}else{
									$result[$ctr]->contract_name = "-";
								}
								if($row->department_id<>0){
									$department = $this->Abas->getDepartment($row->department_id);
									if(isset($department->name)){
										$result[$ctr]->department_name = $department->name;
									}else{
										$result[$ctr]->department_name = "-";
									}
								}else{
									$result[$ctr]->department_name = "-";
								}
								$account = $this->Accounting_model->getAccount($row->coa_id);
								$result[$ctr]->account_code = $account['code'];
								$result[$ctr]->account_name= $account['name'];
								$user = $this->Abas->getUser($row->posted_by);
								$result[$ctr]->posted_by_name = $user['username'];
								if($row->transaction_id == ''){
									$trans_id = 0;
								}else{
									$trans_id = $row->transaction_id;
								}
								$sql_t = "SELECT remark FROM ac_transactions WHERE id=".$trans_id;
								$query_t=$this->db->query($sql_t);
								if($query_t){
									$transaction = $query_t->result();
									$result[$ctr]->particular = $transaction[0]->remark;
								}
								if($row->stat==0){
									$result[$ctr]->posted_on = "-";
								}else{
									$result[$ctr]->posted_on = date('F j, Y',strtotime($row->posted_on));
								}
							}
						}else{
							$result = NULL;
						}
						$data['result'] =  $result;
					}
					$data['company'] = $this->Abas->getCompany($company_id);
					$data['from_date'] = $from_date;
					$data['to_date'] = $to_date;
					$data['status'] = ucwords($status);
					$data['viewfile'] = 'accounting/summary_report/accounting_entries/listview.php';
					$this->load->view('gentlella_container.php',$data);
				break;
			}
		}
	}
?>