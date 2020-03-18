<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller {

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		$this->load->model("Mmm");
		$this->load->model("Billing_model");
		$this->output->enable_profiler(FALSE);

		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
		define("SIDEMENU", "Finance");
	}
	public function index()	{
		$data=array();
		//$data['viewfile']	=	"billing/billing_entry.php";
		//$this->load->view('container.php',$data);
	}
	public function monitoring()	{
		$data=array();
		$data['viewfile']	=	"operation/ship_monitoring.php";
		$this->load->view('container.php',$data);
	}
	public function pay_soa($id) {
		$data=array();
		$soa		=	$this->Billing_model->getStatementOfAccount($id);
		$data['soa'] = $soa;

		if(!empty($_POST) && $soa['status']=="Waiting for Payment") {
	
			$insert['soa_id']		=	$id;
			$insert['tax_12_percent'] =	$this->Mmm->sanitize($_POST['txt_12tax']);
			$insert['tax_5_percent'] =	$this->Mmm->sanitize($_POST['txt_5tax']);
			$insert['tax_2_percent'] =	$this->Mmm->sanitize($_POST['txt_2tax']);
			$insert['tax_1_percent'] =	$this->Mmm->sanitize($_POST['txt_1tax']);
			$insert['gross_amount'] =	$this->Mmm->sanitize($_POST['gross_amount']);
			$insert['net_amount'] =	$this->Mmm->sanitize($_POST['net_amount']);
			$insert['received_on']	=	$this->Mmm->sanitize($_POST['received_on']);
			$insert['received_by']	=	$this->Mmm->sanitize($_POST['received_by']);
			$insert['official_receipt_number']		=	$this->Mmm->sanitize($_POST['or_no']);
			$insert['company_id']	=	$this->Mmm->sanitize($soa['company_id']);
			$insert['payor']		=	$this->Mmm->sanitize($soa['client_id']);
			$insert['particular']	=	null;
			$insert['method']		=	$this->Mmm->sanitize($_POST['method']);
			$insert['deposited_on']		=	$this->Mmm->sanitize($_POST['deposited_on']);
			$insert['deposited_by']		=	$this->Mmm->sanitize($_POST['deposited_by']);
			$insert['deposit_reference_number']		=	$this->Mmm->sanitize($_POST['ref_no']);
			$insert['bank_account']		=	$this->Mmm->sanitize($_POST['bank_account']);
			$insert['tax_12_percent_less'] =	$this->Mmm->sanitize($_POST['txt_12tax_less']);
			$insert['other_deductions'] =	$this->Mmm->sanitize($_POST['txt_other_deductions']);
			$insert['other_deductions_description'] =	$this->Mmm->sanitize($_POST['txt_other_deductions_description']);
			$insert['check_number'] =	$this->Mmm->sanitize($_POST['check_number']);
			$insert['control_number'] =	$this->Mmm->sanitize($_POST['control_number']);

			$insert['stat']			=	1;

			$checkinsert = $this->Mmm->dbInsert("payments", $insert, "Received payment of Php".number_format($insert['net_amount'],2,".",","). " from ".$soa['client']['company']);

			if($checkinsert){
				if($_POST['deposited_on']=="" || $_POST['method']=="" || $_POST['bank_account']=="" || $_POST['deposited_by']=="" || $_POST['ref_no']==""){
					$update['status'] = "For Deposit";

					$checkupdate = $this->Mmm->dbUpdate("statement_of_accounts",$update,$id,"SOA with Reference No." . $soa['reference_number']."has been mark as received.");

					$this->Abas->sysMsg("sucmsg", "Successfully received payment of Php".number_format($insert['net_amount'],2,".",",")." from ".$soa['client']['company']);

				}elseif($_POST['deposited_on']!="" && $_POST['method']!="" && $_POST['bank_account']!="" && $_POST['deposited_by']!="" && $_POST['ref_no']!=""){
					$update['status'] = "Paid";

					$this->Abas->sysMsg("sucmsg", "Successfully received and deposited payment of Php".number_format($insert['net_amount'],2,".",",")." from ".$soa['client']['company']);

					$checkupdate = $this->Mmm->dbUpdate("statement_of_accounts",$update,$id,"SOA with Reference No." . $soa['reference_number']."has been mark as paid.");
				}

			}else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
			}
			
			$this->Abas->redirect(HTTP_PATH."/statements_of_account/view/".$id);
		}
		if(!empty($_POST) && $soa['status']=="For Deposit") {

			if($_POST['payment_id']!="" && $_POST['deposited_on']!="" && $_POST['method']!="" && $_POST['bank_account']!="" && $_POST['deposited_by']!="" && $_POST['ref_no']!=""){
				
				if($_POST['method']=="Check Deposit" && $_POST['check_number']==""){
					$this->Abas->sysNotif("ABAS says", "Please complete all deposit details", "Accounting", "danger");
				}
				else{
					$payment_id	=	$_POST['payment_id'];
					$update['method']		=	$this->Mmm->sanitize($_POST['method']);
					$update['deposited_on']		=	$this->Mmm->sanitize($_POST['deposited_on']);
					$update['check_number']		=	$this->Mmm->sanitize($_POST['check_number']);
					$update['deposited_by']		=	$this->Mmm->sanitize($_POST['deposited_by']);
					$update['deposit_reference_number']		=	$this->Mmm->sanitize($_POST['ref_no']);
					$update['bank_account']		=	$this->Mmm->sanitize($_POST['bank_account']);
					$update['net_amount'] =	$this->Mmm->sanitize($_POST['net_amount']);

					$checkupdate = $this->Mmm->dbUpdate("payments",$update,$payment_id,"Updated Payment for SOA Ref. #" . $soa['reference_number']);

					if($checkupdate){
						$edit['status'] = "Paid";
						$this->Mmm->dbUpdate("statement_of_accounts",$edit,$id,"SOA with Reference No." . $soa['reference_number']."has been mark as paid.");
						$this->Abas->sysMsg("sucmsg", "Successfully deposited payment of Php".number_format($update['net_amount'],2,".",",")." from ".$soa['client']['company']);
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					}
				}
			}
			else{
				$this->Abas->sysNotif("ABAS says", "Please complete all deposit details", "Accounting", "danger");
			}

			$this->Abas->redirect(HTTP_PATH."/statements_of_account/view/".$id);
			
		}
		else{
			if($soa['status']=="For Deposit" || $soa['status']=="Paid"){
				$data['payment'] = $this->Billing_model->getSOAPayment($id);
			}else{
				$data['control_number'] = $this->Abas->getNextSerialNumber('payments',$soa['company_id']);
			}
				
				$data['company'] = $this->Abas->getCompany($soa['company_id']);
				$data['client'] = $this->Abas->getClient($soa['client_id']);

			$this->load->view("billing/bill_form.php",$data);
		}
		
	}
	public function payments($action="", $id="") {$data=array();
		$mainview			=	"gentlella_container.php";
		$data['viewfile']	=	"billing/payments.php";
		if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$search	=	isset($_GET['search'])?$_GET['search']:"";
			$data	=	$this->Abas->createBSTable("payments",$search,$limit,$offset,$order,$sort);
			foreach($data['rows'] as $ctr=>$payment) {
				$data['rows'][$ctr]['company_name']	=	"";
				$data['rows'][$ctr]['company_name']	=	"";
				$data['rows'][$ctr]['received_on']	=	date("j F Y H:i:s", strtotime($payment['received_on']));
				if($payment['company_id']!=0) {
					$company	=	$this->Abas->getCompany($payment['company_id']);
					$data['rows'][$ctr]['company_name']	=	$company->name;
				}
			}
			header('Content-Type: application/json');
			// $this->Mmm->debug($data);
			echo json_encode($data);
			exit();
		}
		if($action=="add") {
			$mainview		=	"billing/bill_form.php";
		}
		elseif($action=="insert") {
			$this->Mmm->debug($_POST);
			if(!is_numeric($_POST['amount'])) {
				$this->Abas->sysMsg("warnmsg", "Amount is not valid!");
				$this->Abas->redirect($_SERVER['HTTP_REFERER']);
			}
			$insert['company_id']	=	$this->Mmm->sanitize($_POST['company']);
			$insert['soa_id']		=	0;
			$insert['payor']		=	$this->Mmm->sanitize($_POST['payor']);
			$insert['particular']	=	$this->Mmm->sanitize($_POST['particular']);
			$insert['method']		=	$this->Mmm->sanitize($_POST['method']);
			$insert['amount']		=	$this->Mmm->sanitize($_POST['amount']);
			$insert['stat']			=	1;
			$checkinsert			=	$this->Mmm->dbInsert("payments", $insert, "Receieved payment of ".$insert['amount']. " from ".$insert['payor']);
			if($checkinsert) {
				$this->Abas->sysMsg("sucmsg", "Successfully received payment of ".$insert['amount']." from ".$insert['payor']);
			}
			else {
				$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
			}
			$this->Abas->redirect(HTTP_PATH."billing/payments");
		}
		$this->load->view($mainview,$data);
	}

}
?>