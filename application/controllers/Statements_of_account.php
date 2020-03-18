<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Statements_of_account extends CI_Controller {
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Mmm");
		$this->load->model("Abas");
		$this->load->model("Billing_model");
		$this->load->model("Collection_model");
		$this->load->model("Operation_model");
		define("SIDEMENU","Finance");
		$this->output->enable_profiler(FALSE);
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
	}

	public function index () {
		$data=array();
		$data['viewfile']	=	"billing/statements_of_account_list.php";
		$this->load->view('gentlella_container.php',$data);
	}

	public function load($table = NULL, $filter = NULL) {
		if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$search	=	isset($_GET['search'])?$_GET['search']:"";

			if($filter == "Pending%20for%20Approval"){
				$filter = "Pending for Approval";
			}

			//$search =	isset($filter)?$filter:"";
			$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);

			foreach($data['rows'] as $ctr=>$soa) {

				if($soa['created_by']) {
					$created_by							=	$this->Abas->getUser($soa['created_by']);
					$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
				}
				if($soa['sent_to_client_on']) {
					$data['rows'][$ctr]['sent_to_client_on']	=	date("j F Y", strtotime($soa['sent_to_client_on']));

					//calculate the aging
					if($soa['status']=="Waiting for Payment"){

						$days_limit = "+" . $soa['terms'] . " days";

						$SOA_aging =  $this->Billing_model->getSOAAging($soa['sent_to_client_on'],$days_limit);
						$data['rows'][$ctr]['due'] =$SOA_aging['due'];
						$data['rows'][$ctr]['aging'] = $SOA_aging['aging'];

						$num_days = $SOA_aging['num_days'];

					}
				}
				if($soa['created_on']) {
					$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($soa['created_on']));
				}
				if($soa['client_id']) {
					$client								=	$this->Abas->getClient($soa['client_id']);
					$data['rows'][$ctr]['client_name']	=	$client['company'];
				}
				if($soa['company_id']) {
					$company							=	$this->Abas->getCompany($soa['company_id']);
					$data['rows'][$ctr]['company_name']	=	$company->name;
				}
				if($soa['contract_id']) {
					$contract							=	$this->Abas->getContract($soa['contract_id']);
					$data['rows'][$ctr]['contract']		=	$contract['reference_no'];
				}


				$soa_amount = $this->Billing_model->getSOAAmount($soa['type'],$soa['id']);
				$soa_amount = $soa_amount['grandtotal_tax'];

				$data['rows'][$ctr]['total_amount'] = number_format($soa_amount,2,'.',',');

				$soa_remaining_balance = $this->Billing_model->getSOAPayments($soa['id'])->remaining_balance;


				if($soa['comments']!="" || $soa['comments']!=NULL) {
					if($soa['status']=="Draft"){
						$data['rows'][$ctr]['status']	=	"Returned - For Editing";
					}
				}


			}

			header('Content-Type: application/json');
			echo json_encode($data);

			exit();

		}
	}

	public function view($id) {
		$data=array();
		$data['soa']	=	$this->Billing_model->getStatementOfAccount($id);
		$data['viewfile']	=	"billing/statements_of_account_view.php";

		if($data['soa']['contract_id']){
			$data['mother_contract'] = $this->Operation_model->getMotherContract($data['soa']['contract_id']);
		}else{
			$data['mother_contract'] = "";
		}
		$this->load->view("gentlella_container.php",$data);
	}

	public function add($type=NULL) {
		$data=array();
		$data['companies']			=	$this->Abas->getCompanies();
		$data['clients']			=	$this->Abas->getClients();
		$data['contracts']			=	$this->Operation_model->getContracts();
		$data['service_contract']	=	$this->Abas->getServices();
		$data['type']				=	$type;
		$this->load->view("billing/statements_of_account_form.php",$data);
	}

	public function insert($type=NULL) {
		$data=array();
		$control_number = $this->Abas->getNextSerialNumber('statement_of_accounts',$this->Mmm->sanitize($_POST['company']));
		$insert['control_number']	=	$control_number;
		$insert['contract_id']		=	$this->Mmm->sanitize($_POST['contract']);
		$insert['client_id']		=	$this->Mmm->sanitize($_POST['client']);
		$insert['company_id']		=	$this->Mmm->sanitize($_POST['company']);
		$insert['reference_number']	=	$this->Mmm->sanitize($_POST['reference_number']);
		$insert['out_turn_summary_id']	=	$this->Mmm->sanitize($_POST['out_turn_id']);
		$insert['terms']			=	$this->Mmm->sanitize($_POST['terms']);
		$insert['services']			=	isset($_POST['services'])?$this->Mmm->sanitize($_POST['services']):NULL;
		if($type=="general"){
			$insert['type']="General";
		}elseif($type=="out_turn") {
			$insert['type']="With Out-Turn Summary";
		}
		$insert['description']		=	$this->Mmm->sanitize($_POST['description']);
		$insert['add_tax']			=	isset($_POST['add_tax'])?$_POST['add_tax']:FALSE;
		$insert['vat_12_percent']	=	isset($_POST['vat_12_percent'])?$_POST['vat_12_percent']:FALSE;
		$insert['wtax_15_percent']	=	isset($_POST['wtax_15_percent'])?$_POST['wtax_15_percent']:FALSE;
		$insert['vat_5_percent']	=	isset($_POST['vat_5_percent'])?$_POST['vat_5_percent']:FALSE;
		$insert['wtax_2_percent']	=	isset($_POST['wtax_2_percent'])?$_POST['wtax_2_percent']:FALSE;
		$insert['wtax_1_percent']	=	isset($_POST['wtax_1_percent'])?$_POST['wtax_1_percent']:FALSE;
		$insert['created_on']		=	isset($_POST['created_on'])?$_POST['created_on']:FALSE;//date("Y-m-d H:i:s");
		$insert['created_by']		=	$_SESSION['abas_login']['userid'];
		$insert['status']			=	"Draft";

		$client			=	$this->Abas->getClient($insert['client_id']);
		$company		=	$this->Abas->getCompany($insert['company_id']);

		$check			=	$this->Mmm->dbInsert("statement_of_accounts", $insert, "New ".$company->name." SOA for ".$client['company']." with Control No. ".$control_number);

		if($check){

			$lastInserted  =	$this->Abas->getLastIDByTable('statement_of_accounts');

			if($type=="general") {
				if(isset($_POST['particular'])) {
					foreach($_POST['particular'] as $ctr=>$val) {
						$multiInsert[$ctr]['soa_id']		=	$lastInserted;
						$multiInsert[$ctr]['sorting']		=	$this->Mmm->sanitize($_POST['sorting'][$ctr]);
						$multiInsert[$ctr]['particular']	=	$this->Mmm->sanitize($_POST['particular'][$ctr]);
						$multiInsert[$ctr]['quantity']		=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
						$multiInsert[$ctr]['unit_of_measurement']		=	$this->Mmm->sanitize($_POST['unit_of_measurement'][$ctr]);
						$multiInsert[$ctr]['rate']			=	$this->Mmm->sanitize($_POST['rate'][$ctr]);
						$multiInsert[$ctr]['payment']		=	$this->Mmm->sanitize($_POST['payment'][$ctr]);
						$multiInsert[$ctr]['charges']		=	$this->Mmm->sanitize($_POST['charges'][$ctr]);
						$multiInsert[$ctr]['balance']		=	$this->Mmm->sanitize($_POST['balance'][$ctr]);
					}
					$checkMulti		=	$this->Mmm->multiInsert("statement_of_account_details", $multiInsert, "Details for SOA with Transaction Code No. ".$lastInserted);
					if($checkMulti) {
						$this->Abas->sysMsg("sucmsg", "Successfully created SOA with Transaction Code No.".$lastInserted." under ".$company->name." for ".$client['company']);
						$this->Abas->sysNotif("New SOA", $_SESSION['abas_login']['fullname']." has created SOA with Transaction Code No.".$lastInserted." under ".$company->name." for ".$client['company'],"Finance","info");
					}else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while saving the SOA, please try again!");
						$this->Abas->redirect(HTTP_PATH."statements_of_account");
						die();
					}
				}
			}
			elseif($type=="out_turn") {

				if(isset($_POST['warehouse'])) {

					foreach($_POST['warehouse'] as $ctr=>$val) {

							$detail_insert[$ctr]['soa_id'] 				= $lastInserted;
							$detail_insert[$ctr]['out_turn_summary_id'] = $this->Mmm->sanitize($_POST['out_turn_summary_id'][$ctr]);
							$detail_insert[$ctr]['date_of_delivery'] 	= $this->Mmm->sanitize($_POST['date_of_delivery'][$ctr]);
							$detail_insert[$ctr]['warehouse'] 			= $this->Mmm->sanitize($_POST['warehouse'][$ctr]);

							if(isset($_POST['trucking_company'])){
								$detail_insert[$ctr]['trucking_company']  = $this->Mmm->sanitize($_POST['trucking_company'][$ctr]);
							}

							$detail_insert[$ctr]['quantity'] 			= $this->Mmm->sanitize($_POST['quantity'][$ctr]);
							$detail_insert[$ctr]['total_weight'] 		= $this->Mmm->sanitize($_POST['total_weight'][$ctr]);
							$detail_insert[$ctr]['transaction'] 		= $this->Mmm->sanitize($_POST['transaction'][$ctr]);
							$detail_insert[$ctr]['number_of_moves'] 	= $this->Mmm->sanitize($_POST['no_of_moves'][$ctr]);
							$detail_insert[$ctr]['rate'] 				= $this->Mmm->sanitize($_POST['rate'][$ctr]);
							$detail_insert[$ctr]['amount'] 				= $this->Mmm->sanitize($_POST['balance'][$ctr]);
							$detail_insert[$ctr]['on_board_vessel']		=	$this->Mmm->sanitize($_POST['on_board_vessel']);
							$detail_insert[$ctr]['bill_of_lading_number']	=	$this->Mmm->sanitize($_POST['bol_number']);
							$detail_insert[$ctr]['authority_to_issue_number']	=	$this->Mmm->sanitize($_POST['ai_number'][$ctr]);
							$detail_insert[$ctr]['empty_sacks']	=	$this->Mmm->sanitize($_POST['empty_sacks'][$ctr]);
							$detail_insert[$ctr]['tail_end_handling']	=	isset($_POST['tail_end_handling'])?$_POST['tail_end_handling']:NULL;
							$detail_insert[$ctr]['consignee']	=	$this->Mmm->sanitize($_POST['consignee']);
							$detail_insert[$ctr]['destination']	=	$this->Mmm->sanitize($_POST['destination']);
							$detail_insert[$ctr]['commodity_cargo']	=	$this->Mmm->sanitize($_POST['commodity_cargo']);

					}

							$checkMulti = $this->Mmm->multiInsert("statement_of_account_cargo_out_turn",$detail_insert,"Added details for SOA with Transaction Code No. " . $lastInserted);

					if($checkMulti){

						$this->Abas->sysMsg("sucmsg", "Successfully created SOA with Transaction Code No.".$lastInserted." under ".$company->name." for ".$client['company']);
						$this->Abas->sysNotif("New SOA", $_SESSION['abas_login']['fullname']." has created SOA with Transaction Code No.".$lastInserted." under ".$company->name." for ".$client['company'],"Finance","info");

					}
					else{
						$this->Abas->sysMsg("errmsg", "There was an error occured while saving the SOA, please try again!");
						$this->Abas->redirect(HTTP_PATH."statements_of_account");
						die();
					}
			}
		}
		else{
			$this->Abas->sysMsg("errmsg", "There was an error occured while saving the SOA, please try again!");
			$this->Abas->redirect(HTTP_PATH."statements_of_account");
			die();
		}

		//$this->Mmm->debug($detail_insert);
		$this->Abas->redirect(HTTP_PATH."statements_of_account");
	}
}

	public function edit($id){
		$data=array();

		$data['edit'] = $this->Billing_model->getStatementOfAccount($id);

		$data['companies']	=	$this->Abas->getCompanies();
		$data['clients']	=	$this->Abas->getClients();
		$data['contracts']	=	$this->Abas->getContracts();
		$data['service_contract']	=	$this->Abas->getServices();
		$data['vessels']	=	$this->Abas->getVessels(false);
		$data['services'] 	= 	$data['edit']['services'];
		$total_balance = $this->Billing_model->getSOAAmount($data['edit']['type'],$id);
		$data['edit']['total_balance'] = $total_balance ['grandtotal'];

		$this->load->view("billing/statements_of_account_form.php",$data);
	}

	public function update($id){

		$soa = $this->Billing_model->getStatementOfAccount($id);

		if($soa['status']=="Draft"){

			$update['contract_id']		=	$this->Mmm->sanitize($_POST['contract']);
			$update['client_id']		=	$this->Mmm->sanitize($_POST['client']);
			$update['company_id']		=	$this->Mmm->sanitize($_POST['company']);
			$update['reference_number']	=	$this->Mmm->sanitize($_POST['reference_number']);
			$update['out_turn_summary_id']	=	$this->Mmm->sanitize($_POST['out_turn_id']);
			$update['terms']			=	$this->Mmm->sanitize($_POST['terms']);
			$update['services']			=	$this->Mmm->sanitize($_POST['services']);
			$update['description']		=	$this->Mmm->sanitize($_POST['description']);
			$update['add_tax']			=	isset($_POST['add_tax'])?$_POST['add_tax']:NULL;
			$update['vat_12_percent']	=	isset($_POST['vat_12_percent'])?$_POST['vat_12_percent']:NULL;
			$update['wtax_15_percent']	=	isset($_POST['wtax_15_percent'])?$_POST['wtax_15_percent']:NULL;
			$update['vat_5_percent']	=	isset($_POST['vat_5_percent'])?$_POST['vat_5_percent']:NULL;
			$update['wtax_2_percent']	=	isset($_POST['wtax_2_percent'])?$_POST['wtax_2_percent']:NULL;
			$update['wtax_1_percent']	=	isset($_POST['wtax_1_percent'])?$_POST['wtax_1_percent']:NULL;
			$update['created_by']		=	$_SESSION['abas_login']['userid'];
			$update['created_on']		=	isset($_POST['created_on'])?$_POST['created_on']:NULL;//date("Y-m-d H:i:s");
			$update['comments']			=	"";

			$client		=	$this->Abas->getClient($update['client_id']);
			$company	=	$this->Abas->getCompany($update['company_id']);
			$check = $this->Mmm->dbUpdate("statement_of_accounts",$update,$id,"Updated SOA with Transaction Code No. ".$id);

			if($check){

				if($soa['type']=="General"){

					//delete previous row details first and then insert the updated + if there is new row details
					$delete_details = $this->db->query("DELETE FROM statement_of_account_details WHERE soa_id=".$id);

					if($delete_details){

						foreach($_POST['particular'] as $ctr=>$val){
							$multiInsert[$ctr]['soa_id']		=	$id;
							$multiInsert[$ctr]['sorting']		=	$this->Mmm->sanitize($_POST['sorting'][$ctr]);
							$multiInsert[$ctr]['particular']	=	$this->Mmm->sanitize($_POST['particular'][$ctr]);
							$multiInsert[$ctr]['quantity']		=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
							$multiInsert[$ctr]['unit_of_measurement']		=	$this->Mmm->sanitize($_POST['unit_of_measurement'][$ctr]);
							$multiInsert[$ctr]['rate']			=	$this->Mmm->sanitize($_POST['rate'][$ctr]);
							$multiInsert[$ctr]['payment']		=	$this->Mmm->sanitize($_POST['payment'][$ctr]);
							$multiInsert[$ctr]['charges']		=	$this->Mmm->sanitize($_POST['charges'][$ctr]);
							$multiInsert[$ctr]['balance']		=	$this->Mmm->sanitize($_POST['balance'][$ctr]);

						}

							$this->Mmm->multiInsert("statement_of_account_details", $multiInsert, "Inserted updated details for SOA with Transaction Code No. ".$id);
					}
					else{
						$this->Abas->sysMsg("errmsg", "There was an error updating SOA with Transaction Code No.".$id);
						$this->Abas->redirect(HTTP_PATH."statements_of_account/view/".$id);
						die();
					}
				}
				elseif($soa['type']=="With Out-Turn Summary"){

					$delete_deliveries = $this->db->query("DELETE FROM statement_of_account_cargo_out_turn WHERE soa_id=".$id);

					if($delete_deliveries){

						if(isset($_POST['warehouse'])) {

							foreach($_POST['warehouse'] as $ctr=>$val) {

									$detail_insert[$ctr]['soa_id'] 				= $id;
									$detail_insert[$ctr]['out_turn_summary_id'] = $this->Mmm->sanitize($_POST['out_turn_summary_id'][$ctr]);
									$detail_insert[$ctr]['date_of_delivery'] 	= $this->Mmm->sanitize($_POST['date_of_delivery'][$ctr]);
									$detail_insert[$ctr]['warehouse'] 			= $this->Mmm->sanitize($_POST['warehouse'][$ctr]);

									if(isset($_POST['trucking_company'])){
										$detail_insert[$ctr]['trucking_company']  = $this->Mmm->sanitize($_POST['trucking_company'][$ctr]);
									}

									$detail_insert[$ctr]['quantity'] 			= $this->Mmm->sanitize($_POST['quantity'][$ctr]);
									$detail_insert[$ctr]['total_weight'] 		= $this->Mmm->sanitize($_POST['total_weight'][$ctr]);
									$detail_insert[$ctr]['transaction'] 		= $this->Mmm->sanitize($_POST['transaction'][$ctr]);
									$detail_insert[$ctr]['number_of_moves'] 	= $this->Mmm->sanitize($_POST['no_of_moves'][$ctr]);
									$detail_insert[$ctr]['rate'] 				= $this->Mmm->sanitize($_POST['rate'][$ctr]);
									$detail_insert[$ctr]['amount'] 				= $this->Mmm->sanitize($_POST['balance'][$ctr]);
									$detail_insert[$ctr]['on_board_vessel']		=	$this->Mmm->sanitize($_POST['on_board_vessel']);
									$detail_insert[$ctr]['bill_of_lading_number']	=	$this->Mmm->sanitize($_POST['bol_number']);
									$detail_insert[$ctr]['authority_to_issue_number']	=	$this->Mmm->sanitize($_POST['ai_number'][$ctr]);
									$detail_insert[$ctr]['empty_sacks']	=	$this->Mmm->sanitize($_POST['empty_sacks'][$ctr]);
									$detail_insert[$ctr]['tail_end_handling']	=	isset($_POST['tail_end_handling'])?$_POST['tail_end_handling']:NULL;
									$detail_insert[$ctr]['consignee']	=	$this->Mmm->sanitize($_POST['consignee']);
									$detail_insert[$ctr]['destination']	=	$this->Mmm->sanitize($_POST['destination']);
									$detail_insert[$ctr]['commodity_cargo']	=	$this->Mmm->sanitize($_POST['commodity_cargo']);

							}
									$this->Mmm->multiInsert("statement_of_account_cargo_out_turn",$detail_insert,"Inserted updated out-turn details for SOA with Transaction Code No. ".$id);

						}

					}
					else{
							$this->Abas->sysMsg("errmsg", "There was an error updating SOA with Transaction Code No.".$id);
							$this->Abas->redirect(HTTP_PATH."statements_of_account/view/".$id);
							die();
					}

				}

					$this->Abas->sysMsg("sucmsg", "Successfully updated SOA with Transaction Code No.".$id." under ".$company->name." for ".$client['company']);

					$this->Abas->sysNotif("Edit SOA", $_SESSION['abas_login']['fullname']." has updated SOA with Transaction Code No.".$id." under ".$company->name." for ".$client['company'],"Finance","info");

			}
			else{
				$this->Abas->sysMsg("errmsg", "There was an error updating SOA with Transaction Code No.".$id);
				$this->Abas->redirect(HTTP_PATH."statements_of_account/view/".$id);
				die();
			}

			//$this->Mmm->debug($update);
			$this->Abas->redirect(HTTP_PATH."statements_of_account/view/".$id);

		}else{
			$this->Abas->sysMsg("errmsg", "Cannot update SOA with Transaction Code No.". $soa['id'] . " if it's status is no longer Draft.");
			$this->Abas->redirect(HTTP_PATH."statements_of_account/view/".$id);
			die();
		}

	}

	public function change_status($type,$id,$action="",$date=""){

		$soa = $this->Billing_model->getStatementOfAccount($id);

		$cor = $this->Billing_model->getSOACOR($id);//gets COR related to SOA and get the id
		if(isset($cor)){
			$cor_id = $cor[0]['cor_id'];
		}

		$SOA_amount = $this->Billing_model->getSOAAmount($soa['type'],$soa['id']);
		$remaining_balance = number_format($SOA_amount['grandtotal_tax'] - $this->Billing_model->getSOAPayments($soa['id'])->total_payments,2,".","");

		if($action=="submit" && $soa['status']=="Draft"){
			$update['status'] = "Pending for Approval";
			$msg = "SOA with Transaction Code No." . $soa['id']." has been submitted for manager's approval by " . $_SESSION['abas_login']['fullname'];
		}
		elseif($action=="approve" && $soa['status']=="Pending for Approval"){
			$update['status'] = "Approved";
			$msg = "SOA with Transaction Code No." . $soa['id']." has been approved by " . $_SESSION['abas_login']['fullname'];
		}
		elseif($action=="received" && $soa['status']=="Approved"){
			$update['status'] = "Waiting for Payment";
			$update['sent_to_client_on'] = $date;
			$msg = "SOA with Transaction Code No." . $soa['id']." has been marked as sent to client and is now waiting for payment by " . $_SESSION['abas_login']['fullname'];
		}
		elseif($action=="paid" && $soa['status']=="Waiting for Payment"){ //&& $remaining_balance==0.00){
			$update['status'] = "Paid";
			$msg = "SOA with Transaction Code No." . $soa['id']." has been marked as paid by " . $_SESSION['abas_login']['fullname'];

			if(isset($cor_id)){
				$this->Operation_model->setCORStatus($cor_id,"Paid");//updates COR status to "Paid"
			}
		}
		elseif($action=="cancel"){
			$update['status'] = "Cancelled";
			$msg = "SOA with Transaction Code No." . $soa['id']." has been cancelled by " . $_SESSION['abas_login']['fullname'];
			$check_entry_cancellation			=	false;
			/* voids existing journal entries
			$entries							=	$this->db->query("SELECT * FROM ac_transaction_journal WHERE reference_table='statement_of_accounts' AND reference_id=".$id);
			
			if($entries) {
				$entries						=	$entries->result_array();
				if(!empty($entries)) {
					foreach($entries as $entryctr=>$entry) {
						$update_entry['stat']		=	0;
						$check_entry_cancellation	=	$this->Mmm->dbUpdate("ac_transaction_journal", $update_entry, $entry['id'], "Void entry due to cancellation of statement of account transaction id ".$entry['reference_id']);
					}
				}
			}*/

			if(isset($cor_id)){
				$this->Operation_model->setCORStatus($cor_id,"For Billing");//changes back the COR status to "For Billing" if SOA is cancelled
			}
		}
		elseif($action=="return"){
			$update['status'] = "Draft";
			$msg = "SOA with Transaction Code No." . $soa['id']." has been returned to draft by " . $_SESSION['abas_login']['fullname'];
		}

		$soa_update_status  = $this->Mmm->dbUpdate("statement_of_accounts",$update,$id,$msg);

		if($soa_update_status){
			$this->Abas->sysMsg("sucmsg",$msg);
			$this->Abas->sysNotif("SOA Status", $_SESSION['abas_login']['fullname']." has updated the status of SOA with Transaction Code No.".$soa['id'] . " to '". $update['status'] ."'.","Finance","info");
			if(isset($check_entry_cancellation)) {
				if($check_entry_cancellation) {
					$this->Abas->sysMsg("sucmsg","Related journal entries voided");
				}
			}
		}else{
			$this->Abas->sysMsg("errmsg", "Failure to update status of SOA with Transaction Code No.". $soa['id']);

		}

		$this->Abas->redirect(HTTP_PATH."statements_of_account/view/".$id);

	}

	public function prints($format,$id){
		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
		require(WPATH.'assets/fpdf/fpdf.php');

		$data['soa']	=	$this->Billing_model->getStatementOfAccount($id);
		
		if($data['soa']['contract_id']){
			$data['mother_contract'] = $this->Operation_model->getMotherContract($data['soa']['contract_id']);
		}else{
			$data['mother_contract'] = "";
		}
		

		if($format=="full"){
			$this->load->view("billing/statements_of_account_print_full.php",$data);
		}elseif($format=="data" || $format=="data_alter"){
			$data['format'] = $format;
			$this->load->view("billing/statements_of_account_print_data.php",$data);
		}
	}

	public function view_payments($type,$id){

		if($type=="general"){
			$type = "General";
		}elseif($type=="out-turn"){
			$type = "With Out-Turn Summary";
		}

		$data['payments'] = $this->Collection_model->getPaymentsBySOA($id);
		$soa_amount = $this->Billing_model->getSOAAmount($type,$id);
		$data['soa_amount'] = $soa_amount['grandtotal_tax'];

		if(isset($data)){
			$this->load->view("billing/statements_of_account_payments.php",$data);
		}
	}

//for SOA Aging Report ///////////////////////////////////////////////////////////////////////////////////

	public function SOA_aging_report($date_from=NULL,$date_to=NULL,$company_id=NULL,$client_id=NULL,$print=0){

		$sql = "SELECT * FROM statement_of_accounts WHERE status='Waiting for Payment'";

		if($company_id!=NULL){
			$sql = $sql." AND company_id=".$company_id;
		}
		if($client_id!=NULL){
			$sql = $sql." AND client_id=".$client_id;
		}
		if($date_from!=NULL && $date_to!=NULL){
			$sql = $sql." AND sent_to_client_on BETWEEN '".$date_from. "' AND '".$date_to."'";
		}

		$query = $this->db->query($sql);

		if($query){

			$result = $query->result_array();

				foreach($result as $ctr=>$soa) {

						$data['rows'][$ctr]['id'] = $soa['id'];
						$data['rows'][$ctr]['control_number'] = $soa['control_number'];
						$data['rows'][$ctr]['reference_number'] = $soa['reference_number'];
						$data['rows'][$ctr]['type'] = $soa['type'];
						$data['rows'][$ctr]['status'] = $soa['status'];
						$data['rows'][$ctr]['services'] = $soa['services'];
						$data['rows'][$ctr]['date'] =date("j F Y", strtotime($soa['sent_to_client_on']));
						$data['rows'][$ctr]['contract'] = "-";
						$data['rows'][$ctr]['current'] ="-";
						$data['rows'][$ctr]['one_to_thirty_days'] ="-";
						$data['rows'][$ctr]['thirty_one_to_sixty_days']="-";
						$data['rows'][$ctr]['sixty_one_to_one_hundred_twenty_days']="-";
						$data['rows'][$ctr]['over_one_hundred_twenty_days']="-";

						if($soa['created_by']) {
							$created_by							=	$this->Abas->getUser($soa['created_by']);
							$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
						}
						if($soa['sent_to_client_on']) {
							$data['rows'][$ctr]['sent_to_client_on']	=	date("j F Y", strtotime($soa['sent_to_client_on']));

							//calculate the aging
							if($soa['status']=="Waiting for Payment"){

								$days_limit = "+" . $soa['terms'] . " days";

								$SOA_aging =  $this->Billing_model->getSOAAging($soa['sent_to_client_on'],$days_limit);
								$data['rows'][$ctr]['due'] =$SOA_aging['due'];
								$data['rows'][$ctr]['aging'] = $SOA_aging['aging'];

								$num_days = $SOA_aging['num_days'];

							}
						}
						if($soa['created_on']) {
							$data['rows'][$ctr]['created_on']	=	date("j F Y h:i A", strtotime($soa['created_on']));
						}
						if($soa['client_id']) {
							$client								=	$this->Abas->getClient($soa['client_id']);
							$data['rows'][$ctr]['client_name']	=	$client['company'];
						}
						if($soa['company_id']) {
							$company							=	$this->Abas->getCompany($soa['company_id']);
							$data['rows'][$ctr]['company_name']	=	$company->name;
						}
						if($soa['contract_id']) {
							$contract							=	$this->Abas->getContract($soa['contract_id']);
							$data['rows'][$ctr]['contract']		=	$contract['reference_no'];
						}


						$soa_amount = $this->Billing_model->getSOAAmount($soa['type'],$soa['id']);
						$soa_amount = $soa_amount['grandtotal_tax'];
						$data['rows'][$ctr]['total_amount'] = number_format($soa_amount,2,'.',',');

						$soa_remaining_balance = $this->Billing_model->getSOAPayments($soa['id'])->remaining_balance;


						if($soa['comments']!="" || $soa['comments']!=NULL) {
							if($soa['status']=="Draft"){
								$data['rows'][$ctr]['status']	=	"Returned - For Editing";
							}
						}

						$total_aging_amount = 0;
						if($num_days>=1 && $num_days<=30){
							$data['rows'][$ctr]['one_to_thirty_days'] = number_format($soa_remaining_balance,2,'.',',');
							$total_aging_amount = $total_aging_amount + $soa_remaining_balance;
						}
						if($num_days>=31 && $num_days<=60){
							$data['rows'][$ctr]['thirty_one_to_sixty_days'] = number_format($soa_remaining_balance,2,'.',',');
							$total_aging_amount = $total_aging_amount + $soa_remaining_balance;
						}
						if($num_days>=61 && $num_days<=120){
							$data['rows'][$ctr]['sixty_one_to_one_hundred_twenty_days'] = number_format($soa_remaining_balance,2,'.',',');
							$total_aging_amount = $total_aging_amount + $soa_remaining_balance;
						}
						if($num_days>=121){
							$data['rows'][$ctr]['over_one_hundred_twenty_days'] = number_format($soa_remaining_balance,2,'.',',');
							$total_aging_amount = $total_aging_amount + $soa_remaining_balance;
						}
						if($num_days<1){
							$data['rows'][$ctr]['current'] = number_format($soa_remaining_balance,2,'.',',');
						}


						$data['rows'][$ctr]['total_aging_amount'] = number_format($total_aging_amount,2,'.',',');

				}

				$data['date_from'] = $date_from;
				$data['date_to'] = $date_to;
				$data['company_id'] = $company_id;
				$data['client_id'] = $client_id;

			if($print==1){
				$this->load->view('billing/statements_of_account_aging_report_print.php',$data);
			}elseif($print==0){

				$data['viewfile']	=	"billing/statements_of_account_aging_report_list.php";
				$this->load->view("gentlella_container.php",$data);
			}


		}
	}

	public function filter_SOA_aging_report(){
		if(isset($_POST['date_from']) || isset($_POST['company']) || isset($_POST['client'])){
			$this->SOA_aging_report($_POST['date_from'],$_POST['date_to'],$_POST['company'],$_POST['client'],0);
		}else{
			$data['companies']	=	$this->Abas->getCompanies();
			$data['clients']	=	$this->Abas->getClients();
			$this->load->view('billing/statements_of_account_aging_report_modal.php',$data);
		}
	}

	public function print_SOA_aging_report(){

		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

		$date_from = $_GET['date_from'];
		$date_to = $_GET['date_to'];
		$company_id = $_GET['company_id'];
		$client_id = $_GET['client_id'];

		if($date_from!=NULL || $company_id!=NULL || $client_id!=NULL){
			$this->SOA_aging_report($date_from,$date_to,$company_id,$client_id,1);
		}else{
			$this->SOA_aging_report(NULL,NULL,NULL,NULL,1);
		}
	}

//for AJAX ///////////////////////////////////////////////////////////////////////////////////////////////
	public function retrieve_vessels_by_company($company_id){
		$data['vessels'] = $this->Billing_model->getVesselByCompany($company_id);
		echo json_encode( $data['vessels'] );
	}
	public function set_control_number($company_id){
		$data['control_number'] = $this->Abas->getNextSerialNumber('statement_of_accounts',$company_id);
		echo json_encode( $data['control_number'] );
	}
	public function get_contract_details($contract_id){
		$data['contract_details'] = $this->Abas->getContract($contract_id);
		echo json_encode($data['contract_details']);
	}
	public function get_contract_rates($contract_id,$destination=NULL){
		$data['contract_rates'] = $this->Operation_model->getContractRates($contract_id,$destination);
		echo json_encode($data['contract_rates']);
	}
	public function get_out_turn_summary_by_contract($contract_id){
		$data['out_turn_summary_by_contract'] = $this->Operation_model->getOutTurnSummaryByContract($contract_id);
		echo json_encode($data['out_turn_summary_by_contract']);
	}
	public function get_out_turn($id){
		$data['out_turn'] = $this->Operation_model->getOutTurnSummary($id);
		echo json_encode($data['out_turn']);
	}
	public function get_out_turn_deliveries($out_turn_summary_id){
		$data['out_turn_summary_deliveries'] = $this->Operation_model->getOutTurnSummaryDeliveries($out_turn_summary_id,true);
		echo json_encode($data['out_turn_summary_deliveries']);
	}
	public function set_comments($id){
		$comments = $_POST['comments'];
		$data['commented'] = $this->Billing_model->setSOAComments($id,$comments);
	}
	public function auto_complete_unit_of_measurement(){
		$keyword = $this->Mmm->sanitize($_GET['term']);
		$data = $this->Billing_model->getUnitOfMeasurements($keyword);
		echo json_encode($data);
	}
	public function auto_complete_out_turn_summary(){

		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);

		//$sql = "SELECT *, OS.id AS OS_ID,OS.control_number AS OS_CN FROM ops_out_turn_summary AS OS WHERE OS.status='Approved' AND OS.type_of_service='Handling' AND OS.id LIKE '%".$search."%' OR OS.status='Approved' AND OS.type_of_service='Trucking' AND OS.id LIKE '%".$search."%' LIMIT 0, 20";

		$sql = "SELECT *, OS.id AS OS_ID,OS.control_number AS OS_CN FROM ops_out_turn_summary AS OS WHERE OS.status='Approved' AND OS.id LIKE '%".$search."%'";

		$items	=	$this->db->query($sql);
		if($items) {
			if($items->row()) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					$ret[$ctr]['label']	=	"OS No.".$i['OS_CN']." | ".$i['type_of_service']." (Transaction Code ".$i['OS_ID'].")";
					$ret[$ctr]['id']	=	$i['OS_ID'];
					$ret[$ctr]['service']	=	$i['type_of_service'];
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}

	}

	public function auto_complete_warehouse($contract_id){
			$search = $this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM service_contracts_rates WHERE service_contract_id =".$contract_id." AND warehouse LIKE '%".$search."%' LIMIT 0, 10";
			$query	=	$this->db->query($sql);

			if($query) {
				if($query->row()) {
					$query	=	$query->result_array();
					$ret	=	array();
					foreach($query as $ctr=>$wh) {
						$ret[$ctr]['label']	=	$wh['warehouse'];	
						$ret[$ctr]['qty']	=	$wh['quantity'];
						$ret[$ctr]['unit']	=	$wh['unit'];
						$ret[$ctr]['rate']	=	$wh['rate'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}

	}

//for AJAX ///////////////////////////////////////////////////////////////////////////////////////////////
}
?>