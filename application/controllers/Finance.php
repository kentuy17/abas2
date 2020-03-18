<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Finance extends CI_Controller
	{

		public function __construct()
		{
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			$this->load->database();
			$this->load->model("Abas");
			//$this->load->model("vessel_model");
			$this->load->model("Mmm");
			$this->load->model("Accounting_model");
			$this->load->model("Hr_model");
			$this->load->model("Finance_model");
			$this->load->model("Inventory_model");
			$this->output->enable_profiler(FALSE);
			define("SIDEMENU","Finance");
			if (!isset($_SESSION['abas_login'])) {
				header("location:" . HTTP_PATH . "home");
				echo "<script>window.location=" . HTTP_PATH . "</script>";
			}
		}
		public function index()
		{
			$data = array();

			if (isset($_SESSION['tab'])) {
				$data['tab'] = $_SESSION['tab'];
				} else {
				$data['tab'] = 'request';
			}
			$data['services']     = '';
			$data['cash_advance'] = $this->Finance_model->getCashVoucher_ForFunding();

			//$data['cashList'] = $this->Finance_model->getCashAdvances();
			$data['for_funding']  = $this->Finance_model->getVoucherForFunding();


			//$this->load->view('finance/finance_view.php', $data);
			$data['viewfile']			=	"finance/finance_view.php";

			$mainview					=	"gentlella_container.php";
			$this->load->view($mainview, $data);

		}

		// START ACCOUNTS MANAGEMENT

		public function ca_view()
		{
			$data = array();

			if ($data != '') {
				$data['cashList'] = $this->Finance_model->getCashVoucher();
			}

			$this->load->view('finance/ca_view', $data);

		}


		public function accounts_view()
		{
			$data = array();

			if (isset($_SESSION['tab'])) {
				$data['tab'] = $_SESSION['tab'];
				} else {
				$data['tab'] = 'request';
			}
			$location = (isset($_SESSION['abas_login']['user_location'])) ? $_SESSION['abas_login']['user_location'] : '';

			$data['cash_advance']      = $this->Finance_model->getCashAdvances($location);
			//$data['vouchers'] = $this->Accounting_model->getVoucherForRelease(); // commented and replaced by following line due to returning the entire table of check vouchers
			$data['vouchers'] = array();
			$data['bank_accounts']     = $this->Abas->getBanks();

			//$data['supplier_accounts'] = $this->Abas->getSuppliers();// commented and replaced by following line due to returning the entire table of check vouchers
			$data['supplier_accounts']	=	array();
			//$data['service_provider']  = $this->Abas->getServiceproviders();// commented and replaced by following line due to returning the entire table of check vouchers
			$data['service_provider']	=	array();
			//$this->load->view('finance/accounts_view.php', $data);
			$data['viewfile']			=	"finance/accounts_view.php";

			$mainview					=	"gentlella_container.php";
			$this->load->view($mainview, $data);


		}

		public function accounts_view2()
		{
			$data = array();

			if (isset($_SESSION['tab'])) {
				$data['tab'] = $_SESSION['tab'];
				} else {
				$data['tab'] = 'request';
			}
			$location = (isset($_SESSION['abas_login']['user_location'])) ? $_SESSION['abas_login']['user_location'] : '';

			$data['cash_advance']      = $this->Finance_model->getCashAdvances($location);
			$data['vouchers'] = $this->Accounting_model->getVoucherForRelease();
			$data['bank_accounts']     = $this->Abas->getBanks();

			$data['supplier_accounts'] = $this->Abas->getSuppliers();
			$data['service_provider']  = $this->Abas->getServiceproviders();
			$this->load->view('finance/accounts_view2.php', $data);

		}

		public function bank_view()
		{
			$data             = array();
			// echo '<div style="float:right">&nbsp;</div>';
			$data['bank_accounts']     = $this->Abas->getBanks();

			$this->load->view('finance/bank_view', $data);
		}

		public function bank_form($id = '')
		{
			$data = array();
			if ($id != '') {
				$data['bank'] = $this->Abas->getBank($id);
			}
			$this->load->view('forms/bank_form', $data);
		}

		public function add_bank()
		{
			$data = array();

			$msg = '';

			if (isset($_POST)) {
				$id      = $this->Mmm->sanitize($_POST['id']);
				$name    = $this->Mmm->sanitize($_POST['name']);
				$aname   = $this->Mmm->sanitize($_POST['account_name']);
				$anumber = $this->Mmm->sanitize($_POST['account_no']);
				$atype   = $this->Mmm->sanitize($_POST['account_type']);
				$cperson = $this->Mmm->sanitize($_POST['contact_person']);
				$cnumber = $this->Mmm->sanitize($_POST['contact_no']);
				$fnumber = $this->Mmm->sanitize($_POST['fax_no']);
				$curr    = $this->Mmm->sanitize($_POST['currency']);
				$email   = $this->Mmm->sanitize($_POST['email']);
				$stat    = $this->Mmm->sanitize($_POST['stat']);
				$account_code    = $this->Mmm->sanitize($_POST['account_code']);

				if ($id != "") { //edit bank
					$sql = "UPDATE ac_banks SET
					name = '$name',
					account_name = '$aname',
					account_no = '$anumber',
					account_type = '$atype',
					currency = '$curr',
					contact_person = '$cperson',
					contact_no = '$cnumber',
					fax_no = '$fnumber',
					currency = '$curr',
					email = '$email',
					stat = '$stat',
					account_code = '$account_code'
					WHERE id = $id";
					//var_dump($sql);exit;

					if ($sql == true) {
						$r               = $this->Mmm->query($sql);
						$_SESSION['msg'] = "Bank Info edited.";
						} else {
						$_SESSION['msg'] = "Bank Info not edited.";
					}
					} else { //add bank

					$insert['name']           = $this->Mmm->sanitize($_POST['name']);
					$insert['account_name']   = $this->Mmm->sanitize($_POST['account_name']);
					$insert['account_no']     = $this->Mmm->sanitize($_POST['account_no']);
					$insert['account_type']   = $this->Mmm->sanitize($_POST['account_type']);
					$insert['currency']       = $this->Mmm->sanitize($_POST['currency']);
					$insert['contact_person'] = $this->Mmm->sanitize($_POST['contact_person']);
					$insert['contact_no']     = $this->Mmm->sanitize($_POST['contact_no']);
					$insert['fax_no']         = $this->Mmm->sanitize($_POST['fax_no']);
					$insert['currency']       = $this->Mmm->sanitize($_POST['currency']);
					$insert['email']          = $this->Mmm->sanitize($_POST['email']);
					$insert['stat']           = $this->Mmm->sanitize($_POST['stat']);
					$insert['account_code']           = $this->Mmm->sanitize($_POST['account_code']);

					$db = $this->Mmm->dbInsert('ac_banks', $insert);
					if ($db == true) {

						$_SESSION['msg'] = "New Bank Added";
						} else {
						$_SESSION['msg'] = "Bank not Added.";
					}
				}
			}
			header('Location:' . HTTP_PATH . 'finance/bank_view');
			die();
		}

		public function add_fund_form()
		{

			$this->load->view('finance/add_fund_form');
		}

		public function add_fund()
		{

			//do not allow transaction without location
			if(isset($_SESSION['abas_login']['user_location'])){
				$location 		= $_SESSION['abas_login']['user_location'];
				}else{
				//log user out
				header('Location:' . HTTP_PATH . 'home/logout');
				die();
			}

			$date = date("Y-m-d H:i:s");
			$msg = '';

			if (isset($_POST)) {
				$id             = $this->Mmm->sanitize($_POST['id']);
				//var_dump($id);exit;
				$amount         = $this->Mmm->sanitize($_POST['amount']);
				$type           = $this->Mmm->sanitize($_POST['type']);
				$ref_number           = $this->Mmm->sanitize($_POST['ref_number']);
				//var_dump($type);exit;


				if ($id != "") {
					$sql = "UPDATE ac_cash_fund SET
					date_added = '$date',
					amount = '$amount',
					ref_number = '$ref_number',
					type = '$type'
					WHERE id = $id";
					//var_dump($sql);exit;

					$r = $this->Mmm->query($sql,'Update cash fund');
					//var_dump($r);exit;
					if ($r == true) {

						$_SESSION['msg'] = "Cash Fund updated.";
						} else {
						$_SESSION['msg'] = "Problem occured cash fund was not updated.";
					}
					} else {

					//var_dump($status); exit;
					$insert['date_added'] = $date;
					$insert['amount']         = $this->Mmm->sanitize($_POST['amount']);
					$insert['type']           = $this->Mmm->sanitize($_POST['type']);
					$insert['ref_number']     = $this->Mmm->sanitize($_POST['ref_number']);
					$insert['stat']           = 1;
					$insert['location']       = $location;
					$db                       = $this->Mmm->dbInsert('ac_cash_fund', $insert);

					if ($db == true) {


						$_SESSION['msg'] = "Cash Fund added";

						} else {
						$_SESSION['msg'] = "There was a problem adding cash fund.";
					}
				}
			}
			header('Location:' . HTTP_PATH . 'finance/accounts_view##cash_advance');
			die();

		}

		public function cash_form($id = '')
		{
			$data = array();
			if ($id != '') {
				$data['cash'] = $this->Finance_model->getCashAdvance($id);

			}
			//$data['department'] = $this->Abas->getVessels();
			$data['department'] = $this->Abas->getDepartments();
			$data['warehouses'] = $this->Abas->getWarehouses();

			$this->load->view('finance/cash_form', $data);
		}

		public function cash_release_form($id = '')
		{
			$data = array();
			if ($id != '') {
				$data['cash'] = $this->Finance_model->getCashAdvance($id);
			}
			$this->load->view('finance/cash_release_form', $data);
		}

		public function cash_advance_info($id = '') //what is this?
		{
			$data = array();
			if ($id != '') {
				$data['cash'] = $this->Finance_model->getCashAdvance($id);
			}
			$this->load->view('finance/cash_advance_info', $data);
		}

		public function cr_release()
		{
			$data = array();
			$msg = '';
			$date = date("Y-m-d H:i:s");



			if ($_POST){

				$cid = $_POST['id'];
				$vid = $_POST['voucher_id'];

				//update cash request status  *get voucher id as reference
				$sql = "UPDATE ac_cash_advances SET
				status = 'Released',
				date_released = '$date'
				WHERE id = $cid";

				$r = $this->Mmm->query($sql, 'Cash request released');

                if ($r == true) {

					//update voucher status
					$sql = "UPDATE ac_vouchers SET
					status = 'Released'
					WHERE id = $vid";

					$r = $this->Mmm->query($sql, 'CR Voucher released');

                    $_SESSION['msg'] = "Cash request released.";
					$_SESSION['tab'] = "cash_advance";


					print "<script type=\"text/javascript\">
					window.open('".HTTP_PATH."finance/print_cr_receipt/".$cid."');
					window.location.href = '".HTTP_PATH."finance/accounts_view/##cash_advance';
					</script>";


					} else {
                    $_SESSION['msg'] = "Status not Updated.";
				}

				} else {

				echo $_SESSION['msg'] = "Status Not Updated.";

			}

			header('Location:' . HTTP_PATH . 'finance/accounts_view');
			die();
		}

		public function print_cr_receipt($id = ''){



			if($id != ''){


				//get receiving data
				$data['cash_advance'] = $this->Finance_model->getCashAdvance($id);

				if(isset($data['cash_advance'][0]['voucher_id'])){
					$data['voucher'] = $this->Accounting_model->getVoucherInfo($id);
				}


				$this->load->view('finance/print_cr_receipt',$data);


				}else{
				$this->Abas->sysMSg("msg", "There was an error printing receiving report.");
			}

		}
		public function print_voucher_release($id = ''){

			if($id != ''){

				//get receiving data
				$data['voucher'] = $this->Accounting_model->getVoucherInfo($id);
				$data['payto'] = $this->Abas->getSupplier($data['voucher'][0]['pay_to']);
				$data['company'] = $this->Abas->getCompany($data['voucher'][0]['company_id']);


				$this->load->view('finance/print_voucher_release',$data);


				}else{
				$this->Abas->sysMSg("msg", "There was an error printing receiving report.");
			}

		}

		public function cash_forApproval($id = '')
		{
			$data = array();
			$msg = '';


			if ($id!=''){


                $sql = "UPDATE ac_vouchers SET
				status = 'For voucher approval'
				WHERE id = $id";

                $r = $this->Mmm->query($sql, 'Cash request funding approved');


                if ($r == true) {

					$sql = "UPDATE ac_cash_advances SET
					status = 'For voucher approval'
					WHERE voucher_id = $id";

					$r = $this->Mmm->query($sql, 'Updated cash advance status');

                    $_SESSION['msg'] = "Cash request approved.";

					} else {
                    $_SESSION['msg'] = "Status not Updated.";
				}

				} else {

				echo $_SESSION['msg'] = "Status Not Updated.";

			}

			$_SESSION['tab'] = 'cash_advance';
			header('Location:' . HTTP_PATH . 'finance/##cash_advance');
			die();
		}

		public function cash_forVoucherApproval()
		{
			$data = array();

			$msg = '';

			if (isset($_POST)) {
				$id = $this->Mmm->sanitize($_POST['id']);

				$status = 'For Voucher Approval';
				//var_dump($type);exit;

				if ($id != "") { //edit bank
					$sql = "UPDATE ac_cash_advances SET
					status = '$status'
					WHERE id = $id";

					$r = $this->Mmm->query($sql, 'Cash request funding approved');
					//var_dump($r);exit;
					if ($r == true) {

						$_SESSION['msg'] = "Status Updated.";
						} else {
						$_SESSION['msg'] = "Status not Updated.";
					}
					} else { //add bank

					echo $_SESSION['msg'] = "Status Not Updated.";
				}
			}
			header('Location:' . HTTP_PATH . 'finance/');
			die();
		}

		public function add_cash()
		{

			$data = array();
			$date = date("Y-m-d H:i:s");
			$msg = '';

			//do not allow transaction without location
			if(isset($_SESSION['abas_login']['user_location'])){
				$location 		= $_SESSION['abas_login']['user_location'];
				}else{
				//log user out
				header('Location:' . HTTP_PATH . 'home/logout');
				die();
			}


			if (isset($_POST)) {
				$id             = $this->Mmm->sanitize($_POST['id']);
				//var_dump($id);exit;

				//var_dump($date_requested); exit;
				//$date_released = date("Y-m-d",strtotime($_POST['date_released']));
				//var_dump($date_released);exit;
				$requested_by   = $this->Mmm->sanitize($_POST['requested_by_val']);
				$purpose        = $this->Mmm->sanitize($_POST['purpose']);
				$amount         = $this->Mmm->sanitize($_POST['amount']);
				$department     = $this->Mmm->sanitize($_POST['department']);
				$warehouse      = $this->Mmm->sanitize($_POST['warehouse']);
				$type           = $this->Mmm->sanitize($_POST['type']);


				//var_dump($location);exit;


				if ($id != "") {
					$sql = "UPDATE ac_cash_advances SET
					date_requested = '$date',
					date_released = '',
					requested_by = '$requested_by',
					purpose = '$purpose',
					amount = '$amount',
					department = '$department',
					warehouse = '$warehouse',
					type = '$type'
					WHERE id = $id";
					//var_dump($sql);exit;

					$r = $this->Mmm->query($sql,'Update cash advance info');
					//var_dump($r);exit;
					if ($r == true) {

						$_SESSION['msg'] = "Cash Advance Info updated.";
						} else {
						$_SESSION['msg'] = "Problem occured cash advance information was not updated.";
					}
					} else {

					//for cash below 2k it does not need approval
					//NOTE: Revised to remove processing of voucher for above 2000 request
					//		All request will automatically released after entry

					$status = 'Released';
					/*$amount = (int)$_POST['amount'];
						if($amount < 2001){
						$status = 'Released';
						}
					*/
					//var_dump($status); exit;

					$insert['date_requested'] = date("Y-m-d H:i:s");
					//$insert['date_released']  = date("Y-m-d H:i:s");
					$insert['requested_by']   = $this->Mmm->sanitize($_POST['requested_by_val']);
					$insert['purpose']        = $this->Mmm->sanitize($_POST['purpose']);
					$insert['amount']         = $this->Mmm->sanitize($_POST['amount']);
					$insert['department']     = $this->Mmm->sanitize($_POST['department']);
					$insert['warehouse']     = $this->Mmm->sanitize($_POST['warehouse']);
					$insert['type']           = $this->Mmm->sanitize($_POST['type']);
					$insert['stat']           = 1;
					$insert['status']         = $status;
					$insert['location']         = $location;
					//var_dump($insert);exit;
					$db                       = $this->Mmm->dbInsert('ac_cash_advances', $insert, "Add new cash advance: ".$insert['purpose']);

					if ($db == true) {

						//get last insert ID
						$cid = $this->db->insert_id();

						print "<script type=\"text/javascript\">
						window.location.href = '".HTTP_PATH."finance/print_cr_receipt/".$cid."';
						</script>";
						$_SESSION['msg'] = "Cash Request added";
						die();
						/* No need for this
							$this->load->view('finance/print_cr_receipt', $data);
							if($_POST['type'] == 'Petty Cash'){

							$sql = "UPDATE ac_cash_advances SET
							status = 'Released',
							date_released = '$date'
							WHERE id = $cid";
							$r = $this->Mmm->query($sql, 'Cash request released');
							//window.open('".HTTP_PATH."finance/print_cr_receipt/".$cid."');
							//window.location.href = '".HTTP_PATH."finance/accounts_view##cash_advance';
						}*/

						} else {
						$_SESSION['msg'] = "There was a problem adding cash request.";
					}
				}
			}
			header('Location:' . HTTP_PATH . 'finance/accounts_view##cash_advance');
			die();
		}

		public function cash_for_funding()
		{
			$data = array();

			$msg = '';

			if (isset($_POST)) {
				$id     = $this->Mmm->sanitize($_POST['id']);
				//$department = $this->Mmm->sanitize($_POST['department']);
				$status = 'For Funding';
				//var_dump($type);exit;

				if ($id != "") { //edit bank
					$sql = "UPDATE ac_cash_advances SET
					status = '$status'
					WHERE id = $id";
					//var_dump($sql);exit;

					$r = $this->Mmm->query($sql);
					//var_dump($r);exit;
					if ($r == true) {

						$_SESSION['msg'] = "Cash Advance for Funding.";
						} else {
						$_SESSION['msg'] = "Cash Advance not modified.";
					}
					} else { //add bank
					$_SESSION['msg'] = "Cash Advance not modified.";
				}
			}
			header('Location:' . HTTP_PATH . 'accounting/voucher_view');
			die();
		}

		public function liquidation_form($id = '')
		{
			$data = array();
			if ($id != '') {

				$data['department'] = $this->Abas->getVessels(); //this is used as department
				$data['type'] = $this->Accounting_model->getExpenseClassifications();

				$data['cash_liquidation'] = $this->Finance_model->getCashLiquidation($id);

				$data['cash_advance'] = $this->Finance_model->getCashAdvance($id);
				//var_dump($data);exit;
			}
			$this->load->view('finance/liquidation_form', $data);
		}

		public function getLiquidation()
		{

			//$sel_items = $_POST['id'];
			//$itemDetails = explode(",",$sel_items[0]);
			//var_dump($itemDetails);
			//$item = explode("|",$itemDetails[0]);
			//var_dump($item[1]);exit;


			if (isset($_POST['id'])) {

				$ca_amount = $_POST['ca'];
				$selected_items = $_POST['id'];
				$balance = $_POST['bal'];

				$gTotal = 0;


				$items = explode(',', $selected_items);

				$ctr       = count($items)-1;



				$res = "<table class='table table-bordered table-striped table-hover' data-toggle='table' style='font-size:12px'>
				<thead>
				<tr style='font-weight:600; background:#000; color:#FFF'>
				<td width='15%'>Department</td>
				<td width='15%'>Expense Class</td>
				<td width='15%'>Receipt</td>
				<td width='45%'>Particular</td>
				<td width='20%'>Amount</td>
				<td width='5%'>*</td>
				</tr>
				</thead>
				<tbody>
				";



				for ($i = 0; $i < $ctr; $i++) {

					//separate item and qty
					$group   = explode('|', $items[$i]);
					//$department    = $group[3];
					//$type    = $group[4];
					$particular    = $group[2];
					$receipt = $group[1];
					$amount  = $group[0];

					$dept = $this->Abas->getVessel($group[3]);
					$type = $this->Accounting_model->getExpenseClassification($group[4]);

					$res .= "	<tr>
					<td align='left'>" . $dept->name . "</td>
					<td align='left'>" . $type[0]['name'] . "</td>
					<td align='left'>" . $receipt . "</td>
					<td align='left'>" . $particular . "</td>
					<td align='right'>" . number_format($amount,2). "</td>
					<td align='center'><a href='#' id='" . $items[$i] . ",'
					onclick='

					delItem(this.id);

					' title='Remove'><i class='fa fa-minus-square'></i></a></td>
					</tr>
					";
					//var_dump($res);exit;
					$gTotal = $gTotal + $amount;


				}

				$bal = $balance - $gTotal;

				$res .= "<tbody>
				</table>
				<table style='font-size:14px; margin-right:60px' align='right'>
				<tr>
				<td></td>
				<td style='text-align:right;'><strong>Total Liquidation:</strong></td>
				<td align='right'><strong> Php " . number_format($gTotal, 2) . "</strong></td>
				<td></td>
				</tr>

				<tr style='color:#FF0000'>
				<td></td>
				<td style='text-align:right;'><strong>Balance:</strong></td>
				<td align='right'><strong>Php " . number_format($bal, 2) . "</strong></td>
				<td></td>
				</tr>

				</table>";

				} else {

				$res = '<div>No item selected. Please try again.</div>';
			}
			echo $res;

		}

		public function add_liquidation()
		{

			$items     = $_POST['sels'];
			$ca_id     = $_POST['id'];
			$ca_amount = $_POST['ca_amount'];
			$balance = $_POST['balance'];
			$balance_returned	= $_POST['return'];
			$date_liquidated = date("Y-m-d H:i:s");
			$itemGroup = explode(",", $items);
			$ctr       = count($itemGroup) - 1;

			$location 	= $_SESSION['abas_login']['user_location'];


			$total_liquidation = 0;

			for ($i = 0; $i < $ctr; $i++) {

				//separate item and qty
				$group           = explode('|', $itemGroup[$i]);

				$type    = $group[4];
				$dept    = $group[3];
				$particular    = $group[2];
				$receipt = $group[1];
				$amount  = $group[0];

				$total_liquidation = $total_liquidation + $amount;

				$sql = "	INSERT
				INTO ac_ca_liquidation(id, date_liquidated, ca_id, stat, amount, particular, receipt_no, department, type)
				VALUES(0,'$date_liquidated',$ca_id,1,'$amount', '$particular', '$receipt', $dept, $type)";

				$db2 = $this->Mmm->query($sql, 'Add Liquidation');

				$_SESSION['msg'] = "New Liquidation added.";
			}

			//update status of cash advance if amount is liquidated
            //get previous liquidation
			$returned_amount = $ca_amount - $total_liquidation;

            if($balance_returned == 1){

				//insert back the balance to cash request
				// 1. get last id of liquidation - use ca_id
				// 2. get type of fund
				$t = $this->Finance_model->getCashAdvance($ca_id);



				$type = $t[0]['type'];
				$remark = 'Returned';
				$sql = "	INSERT
				INTO ac_cash_fund(id, date_added, amount, type, ref_number, remarks, location)
				VALUES(0,'$date_liquidated', '$returned_amount', '$type','$ca_id', '$remark', '$location')";

				$db = $this->Mmm->query($sql, 'Balance amount returned');


				$sql2 = "	UPDATE ac_cash_advances
				SET status = 'Liquidated'
				WHERE id = $ca_id	";

				$db = $this->Mmm->query($sql2, 'Amount Liquidated');

				}else{

				$liquidated = $this->Finance_model->getTotalLiquidation($ca_id);

            	$tl = (float)$liquidated[0]['total'] + (float)$total_liquidation;
            	$bal = $ca_amount - $tl; // rechecked 01272017

				if($bal == 0 || $bal < 1){

					$sql = "	UPDATE ac_cash_advances
					SET status = 'Liquidated'
					WHERE id = $ca_id
					";

					$db = $this->Mmm->query($sql, 'Amount Liquidated');

				}
			}

			header('Location:' . HTTP_PATH . 'finance/accounts_view##cash_advance');
			die();
		}

		public function liquidation_report_form(){


			$this->load->view('finance/liquidation_report_form');


		}


		public function liquidation_report($type=''){

			if($_POST){

				$data['type'] = $_POST['type'];
				$data['liquidation']= $this->Finance_model->getCashLiquidationReport($data['type']);
				$this->load->view('finance/liquidation_report',$data);

				}else{

				$this->Abas->sysMsg("sucmsg", "Error occured, please contact Administrator");
            	header('Location:' . HTTP_PATH . 'finance');
			}

		}


		public function print_liquidation($id=''){

            if($id!=''){

				$data['cash_advance']= $this->Finance_model->getCashAdvance($id);
                $data['liquidation']= $this->Finance_model->getCashLiquidation($id);
                $this->load->view('finance/print_liquidation',$data);

				}else{

                $this->Abas->sysMsg("sucmsg", "Error occured, please contact Administrator");
                header('Location:' . HTTP_PATH . 'finance/accounts_view');
			}

		}



		//END ACCOUNTS MANAGEMENT


		//BANK RECON
		public function bank_recon_view()
		{
			$data = array();

			if (isset($_SESSION['tab'])) {
				$data['tab'] = $_SESSION['tab'];
				} else {
				$data['tab'] = 'request';
			}
			$location = (isset($_SESSION['abas_login']['user_location'])) ? $_SESSION['abas_login']['user_location'] : '';

			$data['released_checks'] = $this->Finance_model->getIssuedChecks();
			$data['bank_accounts']     = $this->Abas->getBanks();

			$this->load->view('finance/bank_recon_view.php', $data);

		}
		//END BANK RECON


		public function purchasing_funding($id = '')
		{
			$data = array();

			if ($id != '') {
				//$data['delivery_summary']	=	$this->Inventory_model->getDelivery($id);
				$data['voucher']          = $this->Accounting_model->getVoucherInfo($id);
				//var_dump($data['voucher']);
				$data['delivery_summary'] = $this->Accounting_model->getDeliveryByVoucherId($id);
				//var_dump($data['delivery_summary']); exit;
				$data['delivery_detail']  = $this->Inventory_model->getDeliveryDetails($data['delivery_summary'][0]['id']);

				$this->load->view('finance/purchasing_funding.php', $data);
				} else {
				var_dump('Error occured. No Id passed.');
			}
		}
		public function purchase_funding_approval($id = '')
		{
			$data = array();

			if ($_POST) {
				//$data['delivery_summary']	=	$this->Inventory_model->getDelivery($id);
				$vid              = $this->Mmm->sanitize($_POST['voucher_id']);
				$update['status'] = 'For voucher approval';
				$sql              = $this->Mmm->dbUpdate('ac_vouchers', $update, $vid, "Voucher Available Fund");

				$this->Abas->sysMsg("sucmsg", "Voucher Approved");
				header('Location:' . HTTP_PATH . 'finance');

				} else {
				var_dump('Error occured. No Id passed.');
			}
		}



		public function cashier_view()
		{
			$data             = array();
			// echo '<div style="float:right">&nbsp;</div>';
			$data['vouchers'] = $this->Accounting_model->getVoucherForRelease();

			$this->load->view('finance/cashier_view', $data);
		}





		public function deparment_data()
		{
			$search = $this->Mmm->sanitize($_GET['term']);
			$search = str_replace(" ", "%", $search);
			$sql    = "SELECT * FROM departments WHERE name LIKE '%" . $search . "%' ORDER BY name LIMIT 0, 10";
			$items  = $this->db->query($sql);
			if ($items) {
				if ($items->row()) {
					$items = $items->result_array();
					$ret   = array();
					foreach ($items as $ctr => $i) {
						// $ret['id']	=	$i['id'];
						$ret[$ctr]['label'] = $i['name'];
						$ret[$ctr]['value'] = $i['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}

		public function name_data()
		{
			//var_dump($_GET['term']);
			$search = $this->Mmm->sanitize($_GET['term']);
			$search = str_replace(" ", "%", $search);
			$sql    = "SELECT * FROM hr_employees WHERE last_name LIKE '%" . $search . "%' ORDER BY last_name LIMIT 0, 10";
			$items  = $this->db->query($sql);
			if ($items) {
				if ($items->row()) {
					$items = $items->result_array();
					$ret   = array();
					foreach ($items as $ctr => $i) {
						// $ret['id']	=	$i['id'];
						$ret[$ctr]['label'] = $i['last_name'].', '.$i['first_name'] ;
						$ret[$ctr]['value'] = $i['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}

		//end of finance
		public function expense_report()
		{
			$data      = array();
			$vid       = $_POST['vessel'];
			$type      = $_POST['include_on'];
			$class     = $_POST['classification'];
			$from_date = $_POST['from_date'];
			$to_date   = $_POST['to_date'];

			$data['ex_report'] = $this->Accounting_model->getExpenseReport($vid, $from_date, $to_date, $class, $type);
			$data['viewfile']  = "accounting/expense_report.php";

			$this->load->view('container.php', $data);
		}

		public function addExpense()
		{
			$data = array();
			if (isset($_POST)) {
				$eid            = $this->Mmm->sanitize($_POST['id']);
				$voucher_no     = $this->Mmm->sanitize($_POST['voucher_no']);
				$voucher_date   = $this->Mmm->sanitize($_POST['voucher_date']);
				$payee          = $this->Mmm->sanitize($_POST['payee']);
				$particulars    = $this->Mmm->sanitize($_POST['particular']);
				$amount         = $this->Mmm->sanitize($_POST['amount']);
				$reference_no   = $this->Mmm->sanitize($_POST['reference_no']);
				$vessel         = $this->Mmm->sanitize($_POST['vessel']);
				$include_on     = $this->Mmm->sanitize($_POST['include_on']);
				$classification = $this->Mmm->sanitize($_POST['classification']);
				//check if add or edit
				if ($eid !== '') {
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
					$update['check_voucher_date']        = $voucher_date;
					$update['check_voucher_no']          = $voucher_no;
					$update['amount_in_php']             = $amount;
					$update['reference_no']              = $reference_no;
					$update['particulars']               = $particulars;
					$update['vessel_id']                 = $vessel;
					$update['expense_classification_id'] = $classification;
					$update['include_on']                = $include_on;
					$update['account_id']                = $payee;
					$update['status']                    = 'Active';
					$update['modified']                  = date("Y-m-d H:i:s");
					$sql                                 = $this->Mmm->dbUpdate('vessel_expenses', $update, $eid, "Edit Vessel Expense");
					if ($sql == true) {
						$this->Abas->sysMsg("sucmsg", "Vessel Expense Edited!");
						} else {
						$this->Abas->sysMsg("warnmsg", "Vessel Expense Not Edited!");
					}
					//var_dump($sql); exit;
					//$add = $this->db->query($sql);
					} else {
					//add
					// $sql = 'INSERT INTO vessel_expenses(id, check_voucher_date, check_voucher_no, amount_in_php, reference_no, particulars, vessel_id, expense_classification_id, include_on, account_id) VALUES(0,"'.$voucher_date.'","'.$voucher_no.'",'.$amount.',"'.$reference_no.'","'.$particulars.'",'.$vessel.','.$classification.',"'.$include_on.'",'.$payee.')';
					$insert['check_voucher_date']        = $voucher_date;
					$insert['check_voucher_no']          = $voucher_no;
					$insert['amount_in_php']             = $amount;
					$insert['reference_no']              = $reference_no;
					$insert['particulars']               = $particulars;
					$insert['vessel_id']                 = $vessel;
					$insert['expense_classification_id'] = $classification;
					$insert['include_on']                = $include_on;
					$insert['account_id']                = $payee;
					$insert['status']                    = 'Active';
					$insert['created']                   = date("Y-m-d H:i:s");
					$sql                                 = $this->Mmm->dbInsert('vessel_expenses', $insert, "New vessel expense");
					if ($sql == true) {
						$this->Abas->sysMsg("sucmsg", "Vessel Expense Added!");
						} else {
						$this->Abas->sysMsg("warnmsg", "Vessel Expense Not Added!");
					}
					//var_dump($sql); exit;
					//$add = $this->db->query($sql);
				}

				// $add = $this->db->query($sql);

				header('Location:' . HTTP_PATH . 'accounting');

				} else {
				// echo "<div>Error Encountered, please contact administrator.</div>";
				$this->Abas->sysMsg("errmsg", "Error Encountered, please contact administrator.");
			}
		}


		public function view_all_vessels()
		{
			$this->Mmm->debug("use vessels controller->view_all_vessels");
			/* depreciated 15 June 2016
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
			*/
		}
		public function temp()
		{
			$data = array();
			$sql  = '
			SELECT s.id, t.*
			FROM vessel_expenses s
			JOIN (
			SELECT check_voucher_no, vessel_id, check_voucher_date, particulars, count(*) AS qty
			FROM vessel_expenses
			GROUP BY check_voucher_no, vessel_id, check_voucher_date, particulars
			HAVING count(*) > 1
			) t ON s.check_voucher_no = t.check_voucher_no AND s.vessel_id = t.vessel_id AND s.check_voucher_date = t.check_voucher_date AND s.particulars = t.particulars
			ORDER BY particulars DESC
			';
			$data = $this->db->query($sql);
			$this->Mmm->debug($data->result());
		}




		public function payroll_reports()
		{
			$data              = array();
			$data['companies'] = $this->Abas->getCompanies();
			$this->load->view('payroll/accounting_reports.php', $data);
		}
		public function payroll_alphalist()
		{
			$this->load->library('Pdf');
			$this->Abas->checkPermissions("payroll|accounting");
			if (empty($_POST)) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, please try again!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			if (!isset($_POST['company'], $_POST['month'], $_POST['year'])) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, please select a company, month and year!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			if ($_POST['company'] == "" || $_POST['month'] == "" || $_POST['year'] == "" || $_POST['company'] == null || $_POST['month'] == null || $_POST['year'] == null) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, please select a company, month and year!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			if (!is_numeric($_POST['company'])) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid company!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}

			// $this->Mmm->debug($_POST);
			$company        = (array) $this->Abas->getCompany($_POST['company']);
			$date_requested = date("Y-m", strtotime($_POST['year'] . "-" . $_POST['month'] . "-01"));
			// $this->Mmm->debug($company);
			// $this->Mmm->debug($date_requested);

			$check = $this->db->query("SELECT * FROM hr_payroll WHERE payroll_date='" . $date_requested . "' AND company_id=" . $company['id'] . " ORDER BY payroll_coverage ASC LIMIT 2");
			// $this->Mmm->debug($check);
			if (!$check) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid company!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			if (!$check->row()) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, payroll not found!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			$check = $check->result_array();
			$this->Mmm->debug($check);

			if (!isset($check[0])) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, no payroll data found!");
				// header("location:".$_SERVER['HTTP_REFERER']);die();
			}

			$employees = $this->db->query("SELECT id, company_id, stat FROM hr_employees WHERE company_id=" . $company['id'] . " AND stat=1 ORDER BY last_name ASC");
			if (!$employees) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee data!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			if (!$employees->row()) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee data!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}

			$table     = "";
			$employees = $employees->result_array();
			if (!empty($employees)) {
				foreach ($employees as $e) {
					$e        = $this->Abas->getEmployee($e['id']);
					$namedata = "<tr>";
					$namedata .= "<td>" . $e['full_name'] . "</td>";
					$total   = array(
                    "salary" => 0,
                    "tax" => 0,
                    "net_pay" => 0
					);
					$rowdata = array(
                    "1st-half" => "<td>0</td><td>0</td><td>0</td>",
                    "2nd-half" => "<td>0</td><td>0</td><td>0</td>"
					);
					foreach ($check as $pr) {
						$hq = $this->db->query("SELECT * FROM hr_payroll_details WHERE emp_id=" . $e['id'] . " AND payroll_id=" . $pr['id']);
						if ($hq) {
							if ($hq->row()) {
								$hq                               = $hq->row();
								// $this->Mmm->debug($hq);
								$net_pay                          = ($hq->salary + $hq->allowance + $hq->regular_overtime_amount + $hq->holiday_overtime_amount) + $hq->bonus - ($hq->undertime_amount + $hq->absences_amount + $hq->tax + $hq->sss_contri_ee + $hq->phil_health_contri + $hq->elf_contri + $hq->elf_loan + $hq->pagibig_loan + $hq->cash_advance);
								$total['salary']                  = $total['salary'] + $hq->salary;
								$total['tax']                     = $total['tax'] + $hq->tax;
								$total['net_pay']                 = $total['net_pay'] + $net_pay;
								$rowdata[$pr['payroll_coverage']] = "<td>" . $this->Abas->currencyFormat($hq->salary) . "</td>";
								$rowdata[$pr['payroll_coverage']] .= "<td>" . $this->Abas->currencyFormat($hq->tax) . "</td>";
								$rowdata[$pr['payroll_coverage']] .= "<td>" . $this->Abas->currencyFormat($net_pay) . "</td>";
							}
						}
					}
					$totalrow = "<td>" . $this->Abas->currencyFormat($total['salary']) . "</td><td>" . $this->Abas->currencyFormat($total['tax']) . "</td><td>" . $this->Abas->currencyFormat($total['net_pay']) . "</td>";
					if ($total['net_pay'] > 0) {
						$table .= $namedata . $rowdata['1st-half'] . $rowdata['2nd-half'] . $totalrow . "</tr>";
					}
				}
			}

			$data['orientation'] = "P";
			$data['pagetype']    = "legal";
			$data['content']     = '
			<div>
			<h2>' . $company['name'] . '</h2>
			<h3>' . date("F Y", strtotime($date_requested)) . '</h3>
			</div>
			<table border=1>
			<thead style="background:#000; color:#FFFFFF;" >
			<tr>
			<th>Employee</th>

			<th>1st-half Salary</th>
			<th>1st-half Tax</th>
			<th>1st-half Net</th>

			<th>2nd-half Salary</th>
			<th>2nd-half Tax</th>
			<th>2nd-half Net</th>

			<th>Total Salary</th>
			<th>Total Tax</th>
			<th>Total Net</th>
			</tr>
			</thead>
			<tbody>
			' . $table . '
			</tbody>
			</table>
			';
			$data['disp']        = $data['content'];
			$data['viewfile']    = "echo.php";
			$this->load->view('container.php', $data);
			// $this->load->view('pdf-container.php',$data);
		}
		public function annualization()
		{
			$this->load->library('Pdf');
			$this->load->model('Payroll_model');
			$this->Abas->checkPermissions("payroll|accounting");
			// $this->Mmm->debug($_POST);die();
			if (empty($_POST)) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, please try again!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			if (!isset($_POST['employee'])) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, please select an employee!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			if ($_POST['employee'] == "" || $_POST['employee'] == null) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, please select an employee!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}
			if (!is_numeric($_POST['employee'])) {
				$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee!");
				header("location:" . $_SERVER['HTTP_REFERER']);
				die();
			}

			$e     = $this->Abas->getEmployee($_POST['employee']);
			//$this->Mmm->debug($e);
			$rates = $this->Payroll_model->getRates($e['salary_rate'], $e['vessel_id']);


			for ($x = 1; $x <= 12; $x++) {
				$tablecontents[sprintf("%02d", $x)] = array(
                "1st-half" => array(
				"salary" => 0,
				"ph" => 0,
				"pi" => 0,
				"sss" => 0
                ),
                "2nd-half" => array(
				"salary" => 0,
				"ph" => 0,
				"pi" => 0,
				"sss" => 0
                )
				);
			}
			unset($x);

			// get past payrolls
			$past_payrolls = $this->db->query("SELECT hpd.*, p.id, p.payroll_date, p.payroll_coverage FROM hr_payroll_details AS hpd JOIN hr_payroll AS p ON hpd.payroll_id=p.id WHERE emp_id=" . $e['id'] . " AND p.id<>1");
			$past_payrolls = $past_payrolls->result_array();
			//$this->Mmm->debug($past_payrolls);
			foreach ($past_payrolls as $pp) {
				$monthvalue                                          = substr($pp['payroll_date'], 5, 2);
				$salary                                              = ($pp['salary'] * 0.8) - $pp['absences_amount'] - $pp['undertime_amount'];
				$tablecontents[$monthvalue][$pp['payroll_coverage']] = array(
                "salary" => $salary,
                "ph" => $pp['phil_health_contri'],
                "pi" => $pp['pagibig_contri'],
                "sss" => $pp['sss_contri_ee']
				);
			}
			//$this->Mmm->debug($tablecontents);

			// assume future payrolls
			$sss_record = $this->Payroll_model->computeSSS($e['salary_rate']);
			$ph_record  = $this->Payroll_model->computePH($e['salary_rate']);
			$pi_record  = $this->Payroll_model->computePI($e['salary_rate']);

			for ($monthvalue; $monthvalue <= 12; $monthvalue++) {
				$tablecontents[sprintf("%02d", $monthvalue)]['1st-half'] = array(
                "salary" => (($e['salary_rate'] / 2) * 0.8),
                "ph" => 0,
                "pi" => $pi_record['contribution'],
                "sss" => 0
				);
				$tablecontents[sprintf("%02d", $monthvalue)]['2nd-half'] = array(
                "salary" => (($e['salary_rate'] / 2) * 0.8),
                "ph" => $ph_record['employee'],
                "pi" => 0,
                "sss" => $sss_record['ee']
				);
			}

			// rock 'n' roll!
			$table = "";
			$total = array(
            "salary" => 0,
            "sss" => 0,
            "ph" => 0,
            "pi" => 0
			);
			foreach ($tablecontents as $monthctr => $tc) {
				foreach ($tc as $coverage => $content) {
					$table .= '<tr>';
					$table .= '<td>' . date("F", strtotime(date("Y") . "-" . $monthctr . "-01")) . ' ' . $coverage . '</td>';
					$table .= '<td>' . $this->Abas->currencyFormat($content['salary']) . '</td>';
					$table .= '<td>' . $this->Abas->currencyFormat($content['sss']) . '</td>';
					$table .= '<td>' . $this->Abas->currencyFormat($content['ph']) . '</td>';
					$table .= '<td>' . $this->Abas->currencyFormat($content['pi']) . '</td>';
					$table .= '</tr>';
					$total['salary'] += $content['salary'];
					$total['sss'] += $content['sss'];
					$total['ph'] += $content['ph'];
					$total['pi'] += $content['pi'];
				}
			}
			$this->Mmm->debug($total);

			$total_income    = $total['salary'];
			$total_deduction = $total['sss'] + $total['ph'] + $total['pi'];

			$data['orientation'] = "P";
			$data['pagetype']    = "legal";
			$data['content']     = '
			<div>
			<h2>' . $e['full_name'] . '</h2>
			<h3>Annualization for ' . date("Y") . '</h3>
			</div>
			<table border="1" cellpadding="2">
			<thead style="background:#000; color:#FFFFFF;" >
			<tr>
			<th></th>
			<th>Salary</th>
			<th>SSS</th>
			<th>PhilHealth</th>
			<th>PagIbig</th>
			</tr>
			</thead>
			<tbody>
			' . $table . '
			</tbody>
			</table>
			';
			echo $data['content'];
			die();
			$this->load->view('pdf-container.php', $data);
		}
		public function billing($action="") {

			$mainview="gentlella_container.php";
			$data['viewfile']="finance/billing.php";

			if ($action == "json"){
				if(isset($_GET['order']) || isset($_GET['limit']) ||
				isset($_GET['offset'])) {
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$data	=	$this->Abas->createBSTable("ac_billing",$seach,$limit,$offset,$order,$sort);
					header('Content-Type:application/json');
					echo json_encode($data);
					exit();
				}
			}
			elseif ($action == "add_billing"){
				$mainview="finance/add_billing.php";
			}
			elseif ($action == "payment"){
				$mainview="finance/payment_form.php";
			}
			elseif ($action == "soa"){
				$mainview = "finance/soa_pdf.php";
			}
			elseif ($action == "ajax"){
				$client = $_POST['client'];
				$company = $_POST['company'];

				$contracts=$this->db->query("SELECT * FROM service_contracts WHERE company_id='".$company."' AND client_id='".$client."'");
				$contracts=$contracts->result_array();
				$this->Mmm->debug($contracts);
				$contractsoptions="";
				if(!empty($contracts)){
					foreach($contracts as $c){
						$contractsoptions.="<option ".($contract ==$c['id']?"SELECTED":"")." value='".$c['id']."'>".$c['type']."</option>";
					}
				}
				$return = $contractsoptions;
				die($return);
			}
			// elseif ($action == "selected"){
			// if (!empty($company)){
			// if(!empty($client)){
			// $contracts=$this->db->query("SELECT * FROM service_contracts WHERE company_id='".$company."' AND client_id='".$client."'");
			// $contracts=$contracts->result_array();
			// $contractsoptions="";
			// if(!empty($contracts)){
			// foreach($contracts as $c){
			// $contractsoptions.="<option ".($contract ==$c['id']?"SELECTED":"")." value='".$c['id']."'>".$c['type']."</option>";
			// }
			// }
			// }
			// }
			// }
			elseif ($action == "add"){

				$insert['company_id']	= 	$_POST['company'];
				$insert['contract_id']	= 	$_POST['contract'];
				$insert['stat'] 		= 	"1";
				$insert['billed_on'] 	= 	date("Y-m-d H-i-s");

				$checkinsert = $this->Mmm->dbInsert("billing",$insert,"Add New Billing");
				if ($checkinsert){
					$this->Abas->sysMsg("sucmsg","New Billing Added!");
				}
				else{
					$this->Abas->sysMsg("errmsg","Billing not added!");
				}

				if (!empty($_POST['particular'])){
					$billing= $this->db->query("SELECT max(id) as id FROM billing");
					$billing=(array)$billing->row();
					$billing_id=$billing['id'];

					foreach($_POST['particular'] as $ctr=> $particular){
						if ($_POST['amount'][$ctr]!=""){

							$detail[] = array(
							"billing_id"	=>	$billing_id,
							"particular"	=>	$this->Mmm->sanitize($_POST['particular'][$ctr]),
							"amount"		=>	$this->Mmm->sanitize($_POST['amount'][$ctr]),
							"stat"			=>	"1"
							);
						}
					}
					$check = $this->Mmm->multiInsert("billing_details",$detail,"Add New Billing details");
					if($check){
						$this->Abas->sysMsg("sucmsg","New Billing Details Added!");
					}
					else{
						$this->Abas->sysMsg("errmsg","Billing details not added!");
					}
				}
			}

			// if ($action == "table"){
			// $SESSION['particulars'] = $_POST['particulars'];
			// $SESSION['amount'] = $_POST['amount'];
			// if($ajax == "ajax"){
			// $return = "<tr>";
			// $return.="<td>".$SESSION['particulars']."</td>";
			// $return.="<td>".$SESSION['amount']."</td>";
			// $return.="</tr>";
			// die($return);
			// }
			// else{ $this->Abas->redirect(HTTP_PATH."finance"); }
			// }

			$this->load->view($mainview,$data);
		}

		public function check_releasing($action,$id=NULL){
			switch ($action) {
				case 'load':
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$data = $this->Abas->getDataForBSTable("ac_vouchers",$search,$limit,$offset,$order,$sort,"status='For releasing' AND YEAR(voucher_date)>='2019' AND stat=1");

						foreach($data['rows'] as $ctr=>$row){
							if(isset($row['company_id'])){
								$company		=	$this->Abas->getCompany($row['company_id']);
								$data['rows'][$ctr]['company']	=	$company->name;
							}
							if(isset($row['created_by'])){
								$created_by		=	$this->Abas->getEmployee($row['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if(isset($row['created_on'])){
								$data['rows'][$ctr]['created_on']	=	date("j F Y", strtotime($row['created_on']));
							}
							if(isset($row['verified_on'])){
								$data['rows'][$ctr]['verified_on']	=	date("j F Y", strtotime($row['verified_on']));
							}
							if(isset($row['verified_by'])){
								$verified_by		=	$this->Abas->getUser($row['verified_by']);
								$data['rows'][$ctr]['verified_by']	=	$verified_by['full_name'];
							}
							if(isset($row['approved_on'])){
								$data['rows'][$ctr]['approved_on']	=	date("j F Y h:i:s A", strtotime($row['approved_on']));
							}
							if(isset($row['approved_by'])){
								$approved_by		=	$this->Abas->getUser($row['approved_by']);
								$data['rows'][$ctr]['approved_by']	=	$approved_by['full_name'];
							}
							if(isset($row['voucher_date'])){
								$data['rows'][$ctr]['voucher_date']	=	date("j F Y", strtotime($row['voucher_date']));
							}
							if(isset($row['bank_id'])){
								$bank		=	$this->Finance_model->getBanksFromCOA($row['bank_id']);
								$data['rows'][$ctr]['bank']	=	$bank[0]['name'];
							}
							if(isset($row['amount'])){
								$data['rows'][$ctr]['amount']	=	number_format($row['amount'],2,'.',',');
							}
							if(isset($row['payee'])){
								if($row['payee_type']=='Supplier'){
									$supplier = $this->Abas->getSupplier($row['payee']);
									$data['rows'][$ctr]['payee_name']	=	$supplier['name'];
								}else{
									$employee = $this->Abas->getEmployee($row['payee']);
									$data['rows'][$ctr]['payee_name']	=	$employee['full_name'];
								}
							}
							if($row['payee_type']==''){
								$data['rows'][$ctr]['payee_type'] = "Supplier";
							}
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				break;
				
				case 'listview':
					$data['checks'] = $this->Finance_model->getVoucherForRelease();
					$data['viewfile'] = "finance/check_releasing/listview.php";
					$this->load->view('gentlella_container.php',$data);
				break;

				case 'view':
					$data['CV'] = $this->Accounting_model->getVoucher($id);
					$data['CV']['company'] = $this->Abas->getCompany($data['CV']['company_id']);
					if($data['CV']['payee_type']=='Supplier'){
						$payee = $this->Abas->getSupplier($data['CV']['payee']);
						$data['CV']['payee_name'] = $payee['name'];
					}else{
						$payee = $this->Abas->getEmployee($data['CV']['payee']);
						$data['CV']['payee_name'] = $payee['full_name'];
					}
					$bank		=	$this->Finance_model->getBanksFromCOA($data['CV']['bank_id']);
					$data['CV']['bank']	=	$bank[0]['name'];
					if($data['CV']['status']=='Paid'){
						$data['cv_attachments'] = $this->Accounting_model->getVoucherAttachments($id);
					}
					$this->load->view('finance/check_releasing/form.php',$data);
				break;

				case 'release':
					$update1['status'] = 'Paid';
					$update1['released_date'] = date('Y-m-d H:m:s');
					$update1['or_no'] = $this->Mmm->sanitize($_POST['official_receipt']);
					$update1['pay_to'] = $this->Mmm->sanitize($_POST['notes']);
					$update2['status'] = 'Paid';
					$cv = $this->Mmm->dbUpdate('ac_vouchers',$update1,$id,'Released Check Voucher with Transaction Code No.'.$id);
					$check_voucher = $this->Accounting_model->getVoucher($id);

					if($check_voucher['transaction_type']=='non-po'){
						$rfp = $this->Mmm->dbUpdate('ac_request_payment',$update2,$check_voucher['apv_no'],'Marked RFP with Transaction Code No.'.$check_voucher['apv_no']. " to 'Paid'");
					}else{
						$apv = $this->Accounting_model->getAPVoucher($check_voucher['apv_no']);
						$po_id = $apv[0]['po_no'];
						$po = $this->Mmm->dbUpdate('inventory_po',$update2,$po_id,'Marked PO with Transaction Code No.'.$po_id);
					}
					if($cv){
						$multiAttach = array();
						$target_dir = WPATH.'assets/uploads/finance/check_releasing/attachments/';

						foreach($_POST['attachment'] as $ctr=>$val){

							$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$ctr]));
							$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);

							if(end($old_filename)!=""){
								$multiAttach[$ctr]['check_voucher_id']	=	$id;
								$multiAttach[$ctr]['document_name'] 	= 	$this->Mmm->sanitize($_POST['attachment'][$ctr]);
								$multiAttach[$ctr]['filename'] 	= 	$new_filename;
								$multiAttach[$ctr]['stat'] = 1;
								$target_file = $target_dir . $new_filename;
								$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$ctr],$target_file);
							}
						}
						$checkAttach = $this->Mmm->multiInsert("ac_voucher_attachments",$multiAttach,'Inserted attachments for Releasing of Check Voucher with Transaction Code No. '. $id);

						if($check_voucher['payee_type']=='Supplier'){
							$supplier = $this->Abas->getSupplier($check_voucher['payee']);
							$payee    = $supplier['name'];
						}else{
							$employee = $this->Abas->getEmployee($check_voucher['payee']);
							$payee    = $employee['full_name'];
						}

						$company = $this->Abas->getCompany($check_voucher['company_id']);
						$this->Abas->sysNotif("Check Releasing", $_SESSION['abas_login']['fullname']." has successfully released check payment under company " . $company->name . " for payee " . $payee. " amounting PHP ".number_format($check_voucher['amount'],2,'.',','),"Finance","info");

						$this->Abas->sysMsg("sucmsg","Successfully released check payment with CV No." . $check_voucher['voucher_number']);
					}

					$this->Abas->redirect(HTTP_PATH."finance/check_releasing/listview");

				break;

				case 'report':
					if(isset($_POST['date_from']) && isset($_POST['date_to'])){
						$date_from = $this->Mmm->sanitize($_POST['date_from']);
						$date_to = $this->Mmm->sanitize($_POST['date_to']);
						$company = $this->Mmm->sanitize($_POST['company']);
						$payee_type ="";
						$payee="";
						if($_POST['supplier']!=0){
							$payee = $this->Mmm->sanitize($_POST['supplier']);
							$payee_type = "supplier";
						}elseif($_POST['employee']!=0){
							$payee = $this->Mmm->sanitize($_POST['employee']);
							$payee_type = "employee";
						}
						$data['released_checks'] = $this->Finance_model->getReleasedChecks($date_from,$date_to,$company,$payee_type,$payee);
						$data['viewfile'] = "finance/check_releasing/reports.php";
						//$this->Mmm->debug($data);
						$this->load->view('gentlella_container.php',$data);
					}else{
						$data['companies'] = $this->Abas->getCompanies();
						$data['suppliers'] = $this->Abas->getSuppliers();
						$data['employees'] = $this->Abas->getEmployees();
						//$this->Mmm->debug($data['employees']);
						$this->load->view('finance/check_releasing/filter.php',$data);
					}
				break;
			}
		}

		
	}
?>