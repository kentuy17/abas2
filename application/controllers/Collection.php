<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collection extends CI_Controller {

	public function __construct(){

		parent::__construct();

		date_default_timezone_set('Asia/Manila');
		session_start();

		//load libraries and helpers
		$this->load->helper('form');
		$this->load->helper('url_helper');
		$this->load->library('form_validation');

		//load models
		$this->load->model('Abas');
		$this->load->model('Mmm');
		$this->load->model("Billing_model");
		$this->load->model("Collection_model");

		define('SIDEMENU','Finance'); // used by gentellela container for displaying links in sidemenu
		define('CONTROLLER','Collection'); //set base controller path name
		define('VIEW','collection'); //set base base view path name

		//check if user is logged-in
		if(!isset($_SESSION['abas_login'])){
			$this->Abas->redirect(HTTP_PATH."home");
		}

	}

	public function index()
	{
		if($this->Abas->checkPermissions("finance|view_payments",FALSE)){
			$this->listview("payments");
		}

	}

	public function load( $table = NULL ){

		$data = array();

		if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

			$search	=	isset($_GET['search'])?$_GET['search']:"";
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";

			//$search =	"";
			$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);

			foreach($data['rows'] as $ctr=>$row){
				if($table=="payments_daily_report" && isset($row['id'])){
					$data['rows'][$ctr]['total_ending_balance'] = "PHP ".number_format($row['ending_balance'],2,".",",");
				}
				if(isset($row['company_id'])){
					$company	=	$this->Abas->getCompany($row['company_id']);
					$data['rows'][$ctr]['company_id']	= $company->name;
				}
				if(isset($row['net_amount'])){
					$data['rows'][$ctr]['net_amount']	=	"PHP ".number_format($row['net_amount'],2,".",",");
				}
				if(isset($row['received_on'])){
					$data['rows'][$ctr]['received_on']	=	date("j F Y h:i:s A", strtotime($row['received_on']));
				}
				if(isset($row['received_by'])){
					$receiver = $this->Abas->getUser($row['received_by']);
					$data['rows'][$ctr]['received_by']	=	$receiver['full_name'];
				}
				if(isset($row['created_on'])){
					$data['rows'][$ctr]['created_on']	=	date("j F Y", strtotime($row['created_on']));
				}
				if(isset($row['created_by'])){
					$creator = $this->Abas->getUser($row['created_by']);
					$data['rows'][$ctr]['created_by']	=	$creator['full_name'];
				}
				
				if(isset($row['soa_id'])){
					$soa = $this->Billing_model->getStatementOfAccount($row['soa_id']);
					$data['rows'][$ctr]['soa_number']		=	$soa['control_number'];
				}
				if(isset($row['mode_of_collection'])){

					$OR = $this->Collection_model->getOfficialReceipts($row['id']);
					$arr1 = array();
					foreach($OR as $num1){
						$arr1[] = $num1->control_number;
					}
					$OR_str = implode(', ',$arr1);
					$data['rows'][$ctr]['official_receipt_number']	=	$OR_str;

					$AR = $this->Collection_model->getAcknowledgementReceipts($row['id']);
					$arr2 = array();
					foreach($AR as $num2){
						$arr2[] = $num2->control_number;
					}
					$AR_str = implode(', ',$arr2);
					$data['rows'][$ctr]['acknowledgement_receipt_number']	=	$AR_str;

				}
					
					//for DCCRR
					if(isset($row['acknowledgement_receipt_id']) && $table<>'payments'){
						if(isset($row['check_date'])){
							$data['rows'][$ctr]['check_date']		=	date("j F Y", strtotime($row['check_date']));
						}
						if($row['acknowledgement_receipt_id']!=0){
							$ar_number = $this->Collection_model->getARNumber($row['acknowledgement_receipt_id'])->control_number;
						}else{
							$ar_number = "-";
						}
						$data['rows'][$ctr]['ar_id']			=	$ar_number;

						$payment = $this->Collection_model->getPayment($row['payment_id']);

						$data['rows'][$ctr]['company']			=	$payment['company_name'];
						$data['rows'][$ctr]['received_on']		=	date("j F Y h:i:s A", strtotime($payment['received_on']));
						$data['rows'][$ctr]['received_from']	=	$payment['payor'];
						$data['rows'][$ctr]['remarks']			=	$payment['particulars'];
						$data['rows'][$ctr]['amount']			=	"PHP ".number_format($row['amount'],2,'.',',');

						$date_now = strtotime(date('Y-m-d'));

						//for PDC monitoring
						if($table='payments_check_breakdown'){
							if($row['check_date'] < $date_now && $row['official_receipt_id']==0){

								$current_date = strtotime(date("Y-m-d"));
								$tomorrow = date("Y-m-d", strtotime("+1 day"));

								$data['rows'][$ctr]['official_receipt_number'] = "-";

								if(strtotime($row['check_date'])==$current_date){

									$data['rows'][$ctr]['status']	= "Due Today";
								}elseif(date($row['check_date'],strtotime("+1 day"))==$tomorrow){

									$data['rows'][$ctr]['status']	= "Due Tomorrow";
								}
								elseif(strtotime($tomorrow) > strtotime($row['check_date'])){

									$data['rows'][$ctr]['status']	= "Overdue";
								}
								else{
									$data['rows'][$ctr]['status']	= "Post-dated";
								}

								if($payment['status']=='Cancelled'){
									$data['rows'][$ctr]['status']	= "Cancelled Payment";
								}

							}else{

								$data['rows'][$ctr]['official_receipt_number'] = $this->Collection_model->getORNumber($row['official_receipt_id'])->control_number;
								$data['rows'][$ctr]['status']	= "Paid";

								//removes from the rows those are paid
								if($data['rows'][$ctr]['status']=='Paid'){
									unset($data['rows'][$ctr]);
								}
								

							}
						}
					}
			}

		}

		header('Content-Type: application/json');
		echo json_encode($data);
		exit();

		//$this->Mmm->debug($data);
	}

	public function listview( $type = NULL ){

		switch($type){

			case "payments":
				$data['viewfile']	=	VIEW."/payments/listview.php";
			break;

			case "DCCRR":
				$data['viewfile']	=	VIEW."/dccrr/listview.php";
			break;

			case "acknowledgement_receipt":
				$data['viewfile']	=	VIEW."/acknowledgement_receipt/listview.php";
			break;

			case "PDC_monitoring":
				$data['viewfile']	=	VIEW."/pdc_monitoring/listview.php";
			break;

		}

		$this->load->view('gentlella_container.php',$data);
	}

	public function add( $type = NULL ){

		$data = array();

		switch($type){

			case "payments":
				$data['companies']			=	$this->Abas->getCompanies();
				$data['clients']			=	$this->Abas->getClients();

				$this->load->view(VIEW.'/payments/form.php',$data);

			break;

			case "acknowledgement_receipt":
				$data['companies']			=	$this->Abas->getCompanies();

				$this->load->view(VIEW.'/acknowledgement_receipt/form.php',$data);

			break;

			case "DCCRR":
				$data['companies']			=	$this->Abas->getCompanies();

				$this->load->view(VIEW.'/dccrr/form.php',$data);

			break;
		}

	}

	public function insert( $type = NULL, $filter = NULL){

		$data = array();

		//if(!empty($_POST)){//inserts data
			switch($type){
				case "payments":

					$insert = array();
					$insert_receipt = array();

					$or_flag = 0;
					$ar_flag = 0;

					$control_number = $this->Abas->getNextSerialNumber('payments',$_POST['company']);
					$insert['control_number']			=	$control_number;
					$insert['company_id']				=	$this->Mmm->sanitize($_POST['company']);
					$insert['payment_type']				=	$this->Mmm->sanitize($_POST['payment_type']);
					$insert['soa_id']					=	isset($_POST['soa_id'])?$this->Mmm->sanitize($_POST['soa_id']):NULL;
					$insert['payor']					=	$this->Mmm->sanitize($_POST['payor']);
					$insert['TIN']						=	$this->Mmm->sanitize($_POST['TIN']);
					$insert['address']					=	$this->Mmm->sanitize($_POST['address']);
					$insert['business_style']			=	$this->Mmm->sanitize($_POST['business_style']);
					$insert['mode_of_collection']		=	$this->Mmm->sanitize($_POST['mode_of_collection']);
					$insert['particulars']				=	$this->Mmm->sanitize($_POST['particulars']);
					$insert['gross_amount'] 			=	$this->Mmm->sanitize($_POST['net_amount']);//$this->Mmm->sanitize($_POST['gross_amount']);
					$insert['vat_type'] 				=	$this->Mmm->sanitize($_POST['vat_type']);
					$insert['tax_12_percent'] 			=	$this->Mmm->sanitize($_POST['txt_12tax']);
					$insert['vatable_amount'] 			=	$this->Mmm->sanitize($_POST['txt_vatable_amount']);
					$insert['tax_5_percent'] 			=	$this->Mmm->sanitize($_POST['txt_5tax']);
					$insert['tax_2_percent'] 			=	$this->Mmm->sanitize($_POST['txt_2tax']);
					$insert['tax_1_percent'] 			=	$this->Mmm->sanitize($_POST['txt_1tax']);
					$insert['discount'] 				=	str_replace(',','',$_POST['txt_discount']);
					$insert['senior_citizen_id'] 		=	$this->Mmm->sanitize($_POST['senior_citizen_id']);
					$insert['person_with_disability_id'] 		=	$this->Mmm->sanitize($_POST['person_with_disability_id']);
					$insert['other_deductions'] 		=	$this->Mmm->sanitize($_POST['txt_other_deductions']);
					$insert['net_amount'] 				=	str_replace(',','',$_POST['net_amount']);
					$insert['received_on']				=	$this->Mmm->sanitize($_POST['received_on']);
					$insert['received_by']				=	$_SESSION['abas_login']['userid'];

					if($insert['mode_of_collection']=="Bank Deposit/Transfer"){
						$insert['status']					=	"Deposited";
					}
					elseif($insert['mode_of_collection']=="Post-dated Check"){
						$insert['status']					=	"Unpaid";
					}else{
						$insert['status']					=	"For Deposit";
					}


					$company_name = $this->Abas->getCompany($this->Mmm->sanitize($_POST['company']))->name;
					$mode = $this->Mmm->sanitize($_POST['mode_of_collection']);
					$payor =	$this->Mmm->sanitize($_POST['payor']);
					$net_amount	=	str_replace(',','',$_POST['net_amount']);

					$checkInsert = $this->Mmm->dbInsert("payments",$insert,"Received new payment from " . $payor . " amounting PHP". number_format($net_amount,2,",","."). " under " . $company_name);

					if($checkInsert){

						$multiInsert = array();
						$checkMultiInsert = array();

						$last_id_inserted = $this->Abas->getLastIDByTable('payments');

						if($mode=="Cash"){

							if($_POST['receipt_type']=="Official Receipt"){
								$this->Collection_model->setOfficialReceipt($this->Mmm->sanitize($_POST['company']),$last_id_inserted);
								$last_receipt_inserted = $this->Abas->getLastIDByTable('payments_official_receipts');
								$control_no = "OR No.".$this->Collection_model->getORNumber($last_receipt_inserted)->control_number;
							}elseif($_POST['receipt_type']=="Acknowledgement Receipt"){
								$this->Collection_model->setAcknowledgementReceipt($this->Mmm->sanitize($_POST['company']),$last_id_inserted);
								$last_receipt_inserted = $this->Abas->getLastIDByTable('payments_acknowledgement_receipts');
								$control_no = "AR No.".$this->Collection_model->getARNumber($last_receipt_inserted)->control_number;
							}

							foreach($_POST['sorting'] as $ctr=>$val){
								$multiInsert[$ctr]['payment_id']	=	$last_id_inserted;
								$multiInsert[$ctr]['sorting']		=	$this->Mmm->sanitize($_POST['sorting'][$ctr]);

								if($_POST['receipt_type']=="Official Receipt"){
									$multiInsert[$ctr]['official_receipt_id']	=	$last_receipt_inserted;
								}elseif($_POST['receipt_type']=="Acknowledgement Receipt"){
									$multiInsert[$ctr]['acknowledgement_receipt_id']	=	$last_receipt_inserted;
								}

								$multiInsert[$ctr]['denomination']	=	$this->Mmm->sanitize($_POST['denomination'][$ctr]);
								$multiInsert[$ctr]['quantity']		=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
								$multiInsert[$ctr]['amount']		=	$this->Mmm->sanitize($_POST['amount'][$ctr]);
								$multiInsert[$ctr]['status']		=	"For Deposit";
							}

							$checkMultiInsert = $this->Mmm->multiInsert("payments_cash_breakdown",$multiInsert,'Inserted cash breakdown for new payment under ' . $company_name);

							$or_flag = 1;
							$ar_flag = 0;
						}

						if($mode=="Check"){

							$date_now = date('Y-m-d');

							foreach($_POST['check_number'] as $ctr=>$val){

								$check_date = $this->Mmm->sanitize($_POST['check_date'][$ctr]);

								$multiInsert[$ctr]['payment_id']	=	$last_id_inserted;

								if($check_date > $date_now){

									if($ar_flag!=1){//allow only saving of AR once
										$this->Collection_model->setAcknowledgementReceipt($this->Mmm->sanitize($_POST['company']),$last_id_inserted);
										$ar_flag = 1;
									}
									$last_receipt_inserted = $this->Abas->getLastIDByTable('payments_acknowledgement_receipts');
									$control_no = "AR No.".$this->Collection_model->getARNumber($last_receipt_inserted)->control_number;

									$multiInsert[$ctr]['acknowledgement_receipt_id']	=	$last_receipt_inserted;
									$multiInsert[$ctr]['official_receipt_id']	= NULL;
									$status = "Post-dated";

								}elseif($check_date <= $date_now){

									if($_POST['receipt_type']=="Acknowledgement Receipt"){
										if($ar_flag!=1){//allow only saving of AR once
											$this->Collection_model->setAcknowledgementReceipt($this->Mmm->sanitize($_POST['company']),$last_id_inserted);
											$ar_flag = 1;
										}
										$last_receipt_inserted = $this->Abas->getLastIDByTable('payments_acknowledgement_receipts');
										$control_no = "AR No.".$this->Collection_model->getARNumber($last_receipt_inserted)->control_number;
										$multiInsert[$ctr]['acknowledgement_receipt_id']	= $last_receipt_inserted;
										$multiInsert[$ctr]['official_receipt_id']	=	NULL;
									}elseif($_POST['receipt_type']=="Official Receipt"){
										if($or_flag!=1){//allow only saving of OR once
											$this->Collection_model->setOfficialReceipt($this->Mmm->sanitize($_POST['company']),$last_id_inserted);
											$or_flag = 1;
										}
										$last_receipt_inserted = $this->Abas->getLastIDByTable('payments_official_receipts');
										$control_no = "OR No.".$this->Collection_model->getORNumber($last_receipt_inserted)->control_number;
										$multiInsert[$ctr]['acknowledgement_receipt_id']	= NULL;
										$multiInsert[$ctr]['official_receipt_id']	=	$last_receipt_inserted;
									}
									$status = "For Deposit";
								}
					
								$multiInsert[$ctr]['sorting']		=	$this->Mmm->sanitize($_POST['sorting'][$ctr]);
								$multiInsert[$ctr]['bank_name']		=	$this->Mmm->sanitize($_POST['bank_name'][$ctr]);
								$multiInsert[$ctr]['bank_branch']	=	$this->Mmm->sanitize($_POST['bank_branch'][$ctr]);
								$multiInsert[$ctr]['check_number']	=	$this->Mmm->sanitize($_POST['check_number'][$ctr]);
								$multiInsert[$ctr]['check_date']	=	$this->Mmm->sanitize($_POST['check_date'][$ctr]);
								$multiInsert[$ctr]['amount']		=	$this->Mmm->sanitize($_POST['amount'][$ctr]);
								$multiInsert[$ctr]['status']		=	$status;
							}

							$checkMultiInsert = $this->Mmm->multiInsert("payments_check_breakdown",$multiInsert,'Inserted check breakdown for new payment under ' . $company_name);
						}


						if($mode=="Bank Deposit/Transfer"){

							foreach($_POST['sorting'] as $ctr=>$val){

								if($_POST['receipt_type']=="Acknowledgement Receipt"){
									if($ar_flag!=1){//allow only saving of AR once
										$this->Collection_model->setAcknowledgementReceipt($this->Mmm->sanitize($_POST['company']),$last_id_inserted);
										$ar_flag = 1;
									}
									$last_receipt_inserted = $this->Abas->getLastIDByTable('payments_acknowledgement_receipts');
									$control_no = "AR No.".$this->Collection_model->getARNumber($last_receipt_inserted)->control_number;
									$multiInsert[$ctr]['acknowledgement_receipt_id']	= $last_receipt_inserted;
									$multiInsert[$ctr]['official_receipt_id']	=	NULL;
								}elseif($_POST['receipt_type']=="Official Receipt"){
									if($or_flag!=1){//allow only saving of OR once
										$this->Collection_model->setOfficialReceipt($this->Mmm->sanitize($_POST['company']),$last_id_inserted);
										$or_flag = 1;
									}
									$last_receipt_inserted = $this->Abas->getLastIDByTable('payments_official_receipts');
									$control_no = "OR No.".$this->Collection_model->getORNumber($last_receipt_inserted)->control_number;
									$multiInsert[$ctr]['acknowledgement_receipt_id']	= NULL;
									$multiInsert[$ctr]['official_receipt_id']	=	$last_receipt_inserted;
								}

								$multiInsert[$ctr]['payment_id']	=	$last_id_inserted;
								$multiInsert[$ctr]['sorting']		=	$this->Mmm->sanitize($_POST['sorting'][$ctr]);
								$multiInsert[$ctr]['bank_name']		=	$this->Mmm->sanitize($_POST['bank_name'][$ctr]);
								$multiInsert[$ctr]['bank_branch']	=	$this->Mmm->sanitize($_POST['bank_branch'][$ctr]);
								$multiInsert[$ctr]['deposit_reference_number']	=	$this->Mmm->sanitize($_POST['deposit_reference_number'][$ctr]);
								$multiInsert[$ctr]['deposited_on']	=	$this->Mmm->sanitize($_POST['deposit_date'][$ctr]);
								$multiInsert[$ctr]['deposited_by']	=	$payor;
								$multiInsert[$ctr]['deposited_account']	=	$this->Mmm->sanitize($_POST['deposited_account'][$ctr]);
								$multiInsert[$ctr]['amount']		=	$this->Mmm->sanitize($_POST['amount'][$ctr]);
								$multiInsert[$ctr]['status']		=	"Deposited";
							}

							$checkMultiInsert = $this->Mmm->multiInsert("payments_bank_transfer_breakdown",$multiInsert,'Inserted bank deposit/transfer breakdown for new payment under ' . $company_name);

						}

						if($checkMultiInsert){
							$this->Abas->sysNotif("New Payment Received", $_SESSION['abas_login']['fullname']." has received payment	from " . $payor . " amounting PHP". number_format($net_amount,2,".",","). " under " . $company_name . " (".$control_no.")","Finance","info");

							$this->Abas->sysMsg("sucmsg","Received new payment from " . $payor . " amounting PHP". number_format($net_amount,2,".",","). " under " . $company_name . " (".$control_no.")");
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the payment details! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/payments");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the payment details! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/payments");
						die();
					}

					//$this->Mmm->debug($multiInsert);
					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/payments");

				break;

				case "DCCRR":
					$insert = array();
					$company_id = $this->Mmm->sanitize($_POST['company_id']);
					$date_now = $this->Mmm->sanitize($_POST['created_on']);
					$todays_report	= $this->Collection_model->getDCCRRs($_SESSION['abas_login']['userid'],$date_now,$company_id);
					if(count($todays_report)!=0){
						foreach($todays_report as $dccrr) {
							if($dccrr->status=="Active") {
								$this->Abas->sysMsg("warnmsg","DCCRR/s already created on that date. Kindly check and choose another date.");
								$this->Abas->redirect(HTTP_PATH."collection/listview/DCCRR");
							}
						}
					}
					$control_number = $this->Abas->getNextSerialNumber('payments_daily_report',$company_id);
					$insert['control_number'] = $control_number;
					$insert['company_id']	= $company_id;
					$insert['created_on']	= $date_now;
					$insert['created_by']	= $_SESSION['abas_login']['userid'];
					$insert['status']		= 'Active';
					$beginning_balance = 0;
					$total_collection = 0;
					$total_deposits = 0;
					$ending_balance = 0;
					$checkInsert = $this->Mmm->dbInsert("payments_daily_report",$insert,"Added DCCRR");
					$last_id_inserted = $this->Abas->getLastIDByTable('payments_daily_report');
					if($checkInsert){
						$multiInsert = array();
						$payments = $this->Collection_model->getPaymentsBy($_SESSION['abas_login']['userid'],$company_id,$date_now);
						$ctr = 0;
						$pay = 0;
						if(isset($payments)){
							foreach($payments as $payment){
								if($payment->mode_of_collection=='Check'){
									$check_breakdown = $this->Collection_model->getCheckBreakdown($payment->id);
									foreach($check_breakdown as $row1){
										if($row1->status<>'Deposited'){
											$multiInsert[$ctr]['daily_report_id'] = $last_id_inserted;
											$multiInsert[$ctr]['payment_id'] = $payments[$pay]->id;
											$multiInsert[$ctr]['payment_id'] = $payments[$pay]->id;
											$multiInsert[$ctr]['official_receipt_id'] =$row1->official_receipt_id;
											$multiInsert[$ctr]['acknowledgement_receipt_id'] = $row1->acknowledgement_receipt_id;
											$multiInsert[$ctr]['payment_status'] = $row1->status;
											$multiInsert[$ctr]['payment_mode'] = $payments[$pay]->mode_of_collection;
											$multiInsert[$ctr]['check_bank'] = $this->Mmm->sanitize($row1->bank_name . ' - '. $row1->bank_branch . ' - '. $row1->check_number);
											$multiInsert[$ctr]['check_amount'] = $row1->amount;
											$multiInsert[$ctr]['cash_denomination'] = NULL;
											$multiInsert[$ctr]['cash_quantity'] = 0;
											$ctr++;
											$total_collection = $total_collection + $row1->amount;
										}
									}
								}
								elseif($payment->mode_of_collection=='Cash'){
									$cash_breakdown = $this->Collection_model->getCashBreakdown($payment->id);
									foreach($cash_breakdown as $row2){
										if($row2->status<>'Deposited'){
											$multiInsert[$ctr]['daily_report_id'] = $last_id_inserted;
											$multiInsert[$ctr]['payment_id'] = $payments[$pay]->id;
											$multiInsert[$ctr]['official_receipt_id'] = $row2->official_receipt_id;
											$multiInsert[$ctr]['acknowledgement_receipt_id'] = $row2->acknowledgement_receipt_id;
											$multiInsert[$ctr]['payment_status'] = $row2->status;
											$multiInsert[$ctr]['payment_mode'] = $payments[$pay]->mode_of_collection;
											$multiInsert[$ctr]['check_bank'] = "";
											$multiInsert[$ctr]['check_amount'] = 0;
											$multiInsert[$ctr]['cash_denomination'] = $row2->denomination;
											$multiInsert[$ctr]['cash_quantity'] = $row2->quantity;
											$ctr++;
											$total_collection = $total_collection + ($row2->denomination*$row2->quantity);
										}
									}
								}
								elseif($payment->mode_of_collection=='Bank Deposit/Transfer'){
									$bank_transfer_breakdown = $this->Collection_model->getBankTransferBreakdown($payment->id);
									foreach($bank_transfer_breakdown as $row3){
										if($row3->status<>'Deposited'){
											$multiInsert[$ctr]['daily_report_id'] = $last_id_inserted;
											$multiInsert[$ctr]['payment_id'] = $payments[$pay]->id;
											$multiInsert[$ctr]['official_receipt_id'] = $row3->official_receipt_id;
											$multiInsert[$ctr]['acknowledgement_receipt_idid'] = $row3->acknowledgement_receipt_id;
											$multiInsert[$ctr]['payment_status'] = $row3->status;
											$multiInsert[$ctr]['payment_mode'] = $payments[$pay]->mode_of_collection;
											$multiInsert[$ctr]['check_bank'] = $row3->bank_name . ' - '. $row3->bank_branch;
											$multiInsert[$ctr]['check_amount'] = $row3->amount;
											$multiInsert[$ctr]['cash_denomination'] = NULL;
											$multiInsert[$ctr]['cash_quantity'] = 0;
											$ctr++;
											$total_collection = $total_collection + $row3->amount;
										}
									}
								}
								$pay++;
							}
							if(count($multiInsert)>0){
								$checkMultiInsert_Payments = $this->Mmm->multiInsert("payments_daily_report_details",$multiInsert,"Added DCCRR Details - Collection Today");
							}else{
								$checkMultiInsert_Payments = true;
							}
						}
						else {
							$this->Abas->sysMsg("warnmsg","No payments found for that day!");
						} 	
						$multiInsert_D = array();
						$deposits = $this->Collection_model->getDepositsBy($_SESSION['abas_login']['userid'],$company_id,$date_now);
						$ctr = 0;
						$dep = 0;
						if(count($deposits)>0){
							foreach($deposits as $deposit){
								if($deposit->mode_of_collection=='Check'){
									$check_breakdown = $this->Collection_model->getCheckBreakdown($deposit->id);
										foreach($check_breakdown as $row1){
											if(date("Y-m-d", strtotime($row1->deposited_on))==$date_now && $row1->status=='Deposited'){
												$multiInsert_D[$ctr]['daily_report_id'] = $last_id_inserted;
												$multiInsert_D[$ctr]['payment_id'] = $deposits[$dep]->id;
												$multiInsert_D[$ctr]['official_receipt_id'] =$row1->official_receipt_id;
												$multiInsert_D[$ctr]['acknowledgement_receipt_id'] = $row1->acknowledgement_receipt_id;
												$multiInsert_D[$ctr]['payment_status'] = $row1->status;
												$multiInsert_D[$ctr]['payment_mode'] = $deposits[$dep]->mode_of_collection;
												$multiInsert_D[$ctr]['check_bank'] = $this->Mmm->sanitize($row1->bank_name . ' - '. $row1->bank_branch . ' - '. $row1->check_number);
												$multiInsert_D[$ctr]['check_amount'] = $row1->amount;
												$multiInsert_D[$ctr]['cash_denomination'] = NULL;
												$multiInsert_D[$ctr]['cash_quantity'] = 0;
												$ctr++;
												$total_deposits = $total_deposits + $row1->amount;
											}
										}
								}
								elseif($deposit->mode_of_collection=='Cash'){
									$cash_breakdown = $this->Collection_model->getCashBreakdown($deposit->id);
									foreach($cash_breakdown as $row2){
										if(date("Y-m-d", strtotime($row2->deposited_on))==$date_now && $row2->status=='Deposited'){
											$multiInsert_D[$ctr]['daily_report_id'] = $last_id_inserted;
											$multiInsert_D[$ctr]['payment_id'] = $deposits[$dep]->id;
											$multiInsert_D[$ctr]['official_receipt_id'] =$row2->official_receipt_id;
											$multiInsert_D[$ctr]['acknowledgement_receipt_id'] = $row2->acknowledgement_receipt_id;
											$multiInsert_D[$ctr]['payment_status'] = $row2->status;
											$multiInsert_D[$ctr]['payment_mode'] = $deposits[$dep]->mode_of_collection;
											$multiInsert_D[$ctr]['check_bank'] = "";
											$multiInsert_D[$ctr]['check_amount'] = 0;
											$multiInsert_D[$ctr]['cash_denomination'] = $row2->denomination;
											$multiInsert_D[$ctr]['cash_quantity'] = $row2->quantity;
											$ctr++;
											$total_deposits = $total_deposits + ($row2->denomination*$row2->quantity);
										}
									}
								}
								elseif($deposit->mode_of_collection=='Bank Deposit/Transfer'){
									$bank_transfer_breakdown = $this->Collection_model->getBankTransferBreakdown($deposit->id);
									foreach($bank_transfer_breakdown as $row3){
										if(date("Y-m-d", strtotime($row3->deposited_on))==$date_now && $row3->status=='Deposited'){
											$multiInsert_D[$ctr]['daily_report_id'] = $last_id_inserted;
											$multiInsert_D[$ctr]['payment_id'] = $deposits[$dep]->id;
											$multiInsert_D[$ctr]['official_receipt_id'] =$row3->official_receipt_id;
											$multiInsert_D[$ctr]['acknowledgement_receipt_id'] = $row3->acknowledgement_receipt_id;
											$multiInsert_D[$ctr]['payment_status'] = $row3->status;
											$multiInsert_D[$ctr]['payment_mode'] = $deposits[$dep]->mode_of_collection;
											$multiInsert_D[$ctr]['check_bank'] = $row3->bank_name . ' - '. $row3->bank_branch;
											$multiInsert_D[$ctr]['check_amount'] = $row3->amount;
											$multiInsert_D[$ctr]['cash_denomination'] = NULL;
											$multiInsert_D[$ctr]['cash_quantity'] = 0;
											$ctr++;
											$total_deposits = $total_deposits + $row3->amount;
										}
									}
								}
								$dep++;
							}
							if(count($multiInsert_D)>0){
								$checkMultiInsert_Deposits = $this->Mmm->multiInsert("payments_daily_report_details",$multiInsert_D,"Added DCCRR Details - Deposits Today");
							}
							else{
								$checkMultiInsert_Deposits = true;
							}
						}
						else {
							$this->Abas->sysMsg("warnmsg","No deposits found for that day!");
						}
						if($checkMultiInsert_Payments && $checkMultiInsert_Deposits){
							$update = array();
							$todays_collection = $this->Collection_model->getDCCRRTotalCollections($company_id,$_SESSION['abas_login']['userid'],$date_now);
							$todays_deposit = $this->Collection_model->getDCCRRTotalDeposits($company_id,$_SESSION['abas_login']['userid'],$date_now);
							if($total_collection!=0 && $total_deposits!=0){
								if($total_collection == $total_deposits){
									$update['beginning_balance'] = $total_collection - $total_collection;
								}
								else{
									$update['beginning_balance'] = ($total_collection + $total_deposits) - $todays_collection;
								}
							}
							else{
								$update['beginning_balance'] = ($total_collection + $total_deposits) - $todays_collection;
							}
							$update['total_collection'] = $todays_collection;
							$update['total_deposits'] = $todays_deposit;
							$update['ending_balance'] =	($update['beginning_balance']+$todays_collection)-$todays_deposit;
							$this->Mmm->dbUpdate('payments_daily_report',$update,$last_id_inserted,"Compute the beginning and ending balance on DCCR report with Transaction Code #".$last_id_inserted);
							$company_name = $this->Abas->getCompany($company_id)->name;
							$this->Abas->sysNotif("New DCCRR", $_SESSION['abas_login']['fullname']." has created new DCCRR No. ". $control_number ." dated ".date("F j, Y", strtotime($date_now))." under ".$company_name,"Finance","info");
							$this->Abas->sysMsg("sucmsg","Successfully created new DCCRR No. ". $control_number ." dated ".date("F j, Y", strtotime($date_now))." under ".$company_name);
						}
						else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while processing payments when creating the DCCRR! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/DCCRR");
							die();
						}
					}
					else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while creating the DCCRR! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/DCCRR");
						die();
					}
					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/DCCRR");
				break;
			}
	}

	public function view($type = NULL, $id){

		$data = array();

		switch($type){
			case "payments":

			$data['companies']			=	$this->Abas->getCompanies();
			$data['clients']			=	$this->Abas->getClients();
			$data['payment']			=	$this->Collection_model->getPayment($id);

			$this->load->view(VIEW.'/payments/form.php',$data);

			break;

		}

	}

	public function update( $type = NULL, $id ){

		$update = array();
		$change = array();

		switch($type){
			case "payments":

				$payment = $this->Collection_model->getPayment($id);
				$company = $payment['company_name'];

				if($payment['mode_of_collection']=="Cash"){
					if(isset($_POST['deposit_reference_number'])){

						$update['deposit_reference_number'] = $this->Mmm->sanitize($_POST['deposit_reference_number']);
						$update['deposit_date'] 			= $this->Mmm->sanitize($_POST['deposit_date']);
						$update['deposited_by'] 			= $this->Mmm->sanitize($_POST['deposited_by']);
						$update['deposited_account'] 		= $this->Mmm->sanitize($_POST['deposited_account']);

						$checkDeposited = $this->Collection_model->depositCashBreakdown($update,$payment['id']);

						if($this->Collection_model->getOfficialReceipts($payment['id'])[0]->control_number>0){
							$receipt_no = "OR no.".$this->Collection_model->getOfficialReceipts($payment['id'])[0]->control_number;
						}else{
							$receipt_no = "AR no.".$this->Collection_model->getAcknowledgementReceipts($payment['id'])[0]->control_number;
						}

						if($checkDeposited){

							$change['status'] = "Deposited";
							$checkUpdate = $this->Mmm->dbUpdate('payments',$change,$payment['id'],"Payment for " . $receipt_no . " under ".$company." has been marked as Deposited.");

							if($checkUpdate){
								$this->Abas->sysNotif("Deposited Payment", 'Payment for ' . $receipt_no . ' under '.$company.' has been marked as "Deposited" by '.$_SESSION['abas_login']['fullname'],"Finance","info");

								$this->Abas->sysMsg("sucmsg",'Payment for ' . $receipt_no . ' under '.$company.' has been marked as Deposited.');
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while updating the payment deposits! Please try again.");
							}
						}
					}
				}

				if($payment['mode_of_collection']=="Check"){

					foreach($_POST['check_id'] as $ctr=>$val){
						$check_id 									= $this->Mmm->sanitize($_POST['check_id'][$ctr]);
						$update[$ctr]['deposit_reference_number'] 	= $this->Mmm->sanitize($_POST['deposit_reference_number'][$ctr]);
						$update[$ctr]['deposit_date'] 				= $this->Mmm->sanitize($_POST['deposit_date'][$ctr]);
						$update[$ctr]['deposited_by'] 				= $this->Mmm->sanitize($_POST['deposited_by'][$ctr]);
						$update[$ctr]['deposited_account'] 			= $this->Mmm->sanitize($_POST['deposited_account'][$ctr]);

						if($_POST['deposit_reference_number'][$ctr]!="" && $_POST['deposit_date'][$ctr]!=""	&& $_POST['deposited_by'][$ctr]!=""	&& $_POST['deposited_account'][$ctr]!="" ){

							$checkDeposited = $this->Collection_model->depositCheckBreakdown($update[$ctr],$check_id);
						}

						if($this->Collection_model->getOfficialReceipts($payment['id'])[0]->control_number>0){
							$receipt_no = "OR no.".$this->Collection_model->getOfficialReceipts($payment['id'])[0]->control_number;
						}else{
							$receipt_no = "AR no.".$this->Collection_model->getAcknowledgementReceipts($payment['id'])[0]->control_number;
						}
					}

					if($checkDeposited){

						$not_all_deposited = $this->Collection_model->verifyCheckBreakdown($payment['id']);

						if($not_all_deposited==FALSE){

							$change['status'] = "Deposited";
							$checkUpdate = $this->Mmm->dbUpdate('payments',$change,$payment['id'],"Payment for " . $receipt_no	. " under ".$company." has been marked as Deposited.");

							if($checkUpdate){
								$this->Abas->sysNotif("Deposited Payment", 'Payment for ' . $receipt_no	. ' under '.$company.' has been marked as "Deposited" by '.$_SESSION['abas_login']['fullname'],"Finance","info");

								$this->Abas->sysMsg("sucmsg",'Payment for ' . $receipt_no	. ' under '.$company.' has been marked as Deposited.');
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while updating the payment deposits! Please try again.");
							}

						}else{
							$this->Abas->sysMsg("warnmsg",'Note: Only the On-dated Check Payments has been marked as Deposited.');
						}

					}else{
						$this->Abas->sysMsg("warnmsg",'Note: Not all check payments has been marked as Deposited.');
					}

				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/payments");

			break;

			case "set_comments":
				$update['comments'] =  $_POST['comments'];
				$this->Mmm->dbUpdate('payments',$update,$id,"Added comments on cancelled payment with transaction code no.".$id);
			break;
		}
	}

	public function cancel( $type = NULL, $id = NULL ){
		switch($type){
			case "payments":

				$payment = $this->Collection_model->getPayment($id);
				$company = $payment['company_name'];

				if($this->Collection_model->getOfficialReceipts($payment['id'])[0]->control_number>0){
					$receipt_no = "OR no.".$this->Collection_model->getOfficialReceipts($payment['id'])[0]->control_number;
				}else{
					$receipt_no = "AR no.".$this->Collection_model->getAcknowledgementReceipts($payment['id'])[0]->control_number;
				}

				$change['status'] = "Cancelled";
				$checkUpdate = $this->Mmm->dbUpdate('payments',$change,$payment['id'],"Payment for " . $receipt_no	. " under ".$company." has been marked as Cancelled by ".$_SESSION['abas_login']['fullname']);

				if($checkUpdate){

					if($payment['mode_of_collection']=='Cash'){
						$checkBreakdown = $this->db->query("UPDATE payments_cash_breakdown SET status='Cancelled' WHERE payment_id=".$id,"Cancelled Cash Payment with Transaction Code No.".$id);
					}elseif($payment['mode_of_collection']=='Check'){
						$checkBreakdown = $this->db->query("UPDATE payments_check_breakdown SET status='Cancelled' WHERE payment_id=".$id,"Cancelled Cash Payment with Transaction Code No.".$id);
					}else{
						$checkBreakdown = $this->db->query("UPDATE payments_bank_transfer_breakdown SET status='Cancelled' WHERE payment_id=".$id,"Cancelled Cash Payment with Transaction Code No.".$id);
					}

					if($checkBreakdown){
						$this->Abas->sysNotif("Cancelled Payment", 'Payment for ' . $receipt_no . ' under '.$company.' has been marked as "Cancelled" by '.$_SESSION['abas_login']['fullname'],"Finance","info");

						$this->Abas->sysMsg("sucmsg",'Payment for ' . $receipt_no . ' under '.$company.' has been marked as Cancelled.');
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while cancelling the payment! Please try again.");
						die();
					}

				}else{
					$this->Abas->sysMsg("errmsg", "An error has occurred while cancelling the payment! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/payments");

			break;

			case "DCCRR":

				$report = $this->Collection_model->getDCCRR($id);

				if($report['created_by']==$_SESSION['abas_login']['userid']){

					if($report['status']!='Void'){

						$change['status'] = "Void";
						$checkUpdate = $this->Mmm->dbUpdate('payments_daily_report',$change,$report['id'],"DCCRR No." . $report['control_number'] . " under ".$report['company_name']." has been marked as 'Void' by ".$_SESSION['abas_login']['fullname']);

						if($checkUpdate){
							$this->Abas->sysNotif("Void DCCRR", "DCCRR No." . $report['control_number'] . " under ".$report['company_name']." has been marked as 'Void' by ".$_SESSION['abas_login']['fullname'],"Finance","info");

								$this->Abas->sysMsg("sucmsg","DCCRR No." . $report['control_number'] . " under ".$report['company_name']." has been marked as 'Void' by ".$_SESSION['abas_login']['fullname']);
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while voiding the report! Please try again.");
							die();
						}

					}else{
						$this->Abas->sysMsg("warnmsg", "This DCCRR is already voided.".$_SESSION['abas_login']['userid']);
					}

				}else{
					$this->Abas->sysMsg("warnmsg", "You cannot void this DCCRR since it is not created by you.");
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/DCCRR");

			break;
		}
	}

	public function prints( $type = NULL, $id = NULL ){

		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

		$data = array();

		switch($type){

			case "official_receipt":

				$data['OR'] = $this->Collection_model->getOfficialReceipts($id);
				$data['payment'] = $this->Collection_model->getPayment($id);
				$data['type'] = $data['payment']['mode_of_collection'];

				$this->load->view(VIEW.'/payments/print_official_receipt.php',$data);

			break;

			case "acknowledgement_receipt":

				$data['AR'] = $this->Collection_model->getAcknowledgementReceipts($id);
				$data['payment'] = $this->Collection_model->getPayment($id);
				$data['type'] = $data['payment']['mode_of_collection'];

				if($data['type']=="Cash"){
					$data['breakdown'] = $this->Collection_model->getCashBreakdown($id);
				}elseif($data['type']=="Check"){
					$data['breakdown'] = $this->Collection_model->getCheckBreakdown($id);
				}elseif($data['type']=="Bank Deposit/Transfer"){
					$data['breakdown'] = $this->Collection_model->getBankTransferBreakdown($id);
				}

				$this->load->view(VIEW.'/payments/print_acknowledgement_receipt.php',$data);

			break;

			case "PDC_monitoring":

				$data['PDC'] = $this->set_print_data();

				$this->Mmm->debug($data['PDC']);
				$this->load->view(VIEW.'/pdc_monitoring/print.php',$data);

			break;

			case "DCCRR":

				$data['report'] = $this->Collection_model->getDCCRR($id);

				$created_on = $data['report']['created_on'];
				$created_by = $data['report']['created_by'];
				$company_id = $data['report']['company_id'];

				$data['payments'] = $this->Collection_model->getPaymentsBy($created_by,$company_id,$created_on);
				$data['report_details_group'] = $this->Collection_model->getDCCRRDetails($data['report']['id'],TRUE);
				$data['report_details'] = $this->Collection_model->getDCCRRDetails($data['report']['id'],FALSE);

				$this->load->view(VIEW.'/dccrr/print.php',$data);

			break;
		}

	}


///for AJAX/////////////////////////////////////////////////////////////////////////

public function set_control_number($table,$company_id){
	$data['control_number'] = $this->Abas->getNextSerialNumber($table,$company_id);
	echo json_encode( $data['control_number'] );
}

public function get_banks_by_company($company_id){
	$data['bank_accounts'] = $this->Collection_model->getBankByCompany($company_id);
	echo json_encode( $data['bank_accounts'] );
}
public function set_print_data(){
	$data['myData'] = $_POST['myData'];
	return $data['myData'];
}
public function get_statement_of_accounts_by_status(){
	$data['soa'] = $this->Billing_model->getStatementOfAccountsByStatus('Waiting for Payment');
	echo json_encode( $data['soa'] );
}
public function get_statement_of_accounts_by_id($id){
	$data['soa_details'] = $this->Billing_model->getStatementOfAccount($id);
	echo json_encode( $data['soa_details'] );
}
public function get_statement_of_accounts_remaining_balance($id){
	$data['soa_remaining_balance'] = $this->Billing_model->getSOAPayments($id);
	//$this->Mmm->debug($data['soa_remaining_balance']);
	echo json_encode( $data['soa_remaining_balance'] );

}
public function get_statement_of_accounts_grandtotal($id){

	$soa_type = $this->Billing_model->getStatementOfAccount($id);
	$soa_type	 = $soa_type['type'];

	$soa = $this->Billing_model->getSOAAmount($soa_type,$id);
	$data['soa_grandtotal'] = $soa['grandtotal'];
	echo json_encode( $data['soa_grandtotal']);
}
public function set_official_receipt($company,$payment_id){
	$this->Collection_model->setOfficialReceipt($company,$payment_id);
	$last_receipt_inserted = $this->Abas->getLastIDByTable('payments_official_receipts');
	$this->Collection_model->setORCheckBreakdown($payment_id,$last_receipt_inserted);
}
public function set_acknowledgement_receipt($payment_id){
	//sets the ondated checks to for deposit
	$this->Collection_model->setARCheckBreakdown($payment_id);
}
public function auto_complete_statement_of_account(){

	$search	=	$this->Mmm->sanitize($_GET['term']);
	$search	=	str_replace(" ", "%", $search);

	$sql = "SELECT * FROM statement_of_accounts WHERE id LIKE '%".$search."%' AND Status='Waiting for Payment' OR reference_number LIKE '%".$search."%' AND Status='Waiting for Payment' OR control_number LIKE '%".$search."%' AND Status='Waiting for Payment' LIMIT 0, 20";
	$items	=	$this->db->query($sql);

	if($items) {
		if($items->row()) {
			$items	=	$items->result_array();
			$ret	=	array();
			foreach($items as $ctr=>$i) {
				$ret[$ctr]['label']	=	"SOA No.".$i['control_number']." | Ref. No.".$i['reference_number']." (Transaction Code ".$i['id'].")";
				$ret[$ctr]['id']	=	$i['id'];
			}
			header('Content-Type: application/json');
			echo json_encode($ret);
			exit();
		}
	}

}


////////////////////////////////////////////////////////////////////////////////////

}


/* End of file Collection.php */
/* Location: ./application/controllers/Collection.php */
?>