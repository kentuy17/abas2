<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		//$this->load->model("vessel_model");
		$this->load->model("Mmm");
		$this->load->model("Accounting_model");
		$this->load->model("Finance_model");
		$this->load->model("Inventory_model");
		$this->output->enable_profiler(FALSE);
		if(!isset($_SESSION['abas_login'])) { header("location:".HTTP_PATH."home");echo "<script>window.location=".HTTP_PATH."</script>"; }
	}
	public function index()	{$data=array();
		
		if(isset($_SESSION['tab'])){
			$data['tab']= $_SESSION['tab'];
		}else{
			$data['tab']= 'request';
		}
		$data['services'] = '';
		$data['cash_advance'] = '';
		$data['for_funding'] = $this->Finance_model->getVoucherForFunding();		
		$this->load->view('finance/finance_view.php',$data);
		
	}
	
	// START ACCOUNTS MANAGEMENT
	
	public function accounts_view()	{$data=array();
		
		if(isset($_SESSION['tab'])){
			$data['tab']= $_SESSION['tab'];
		}else{
			$data['tab']= 'request';
		}
		
		if($data!=''){
   		$data['bankList']	=	$this->Abas->getBanks();
   		}elseif{
			$data['cashList'] = $this->Finance_model->getCashAdvances();
		}	
		
		$data['bank_accounts'] = '';
		$data['cash_advance'] = '';		
		$data['supplier_accounts'] = '';		
		$this->load->view('finance/accounts_view.php',$data);
		
	}
	
	// START CASH ADVANCE
	
	public function cash_form($id='')	{$data=array();
   	if($id!=''){
   		$data['cash'] = $this->Finance_model->getCashAdvance($id);
   	}
   		$this->load->view('finance/cash_form',$data);
   	}
	
	
	
	public function add_cash()	{$data=array();
	
	$msg ='';
		
   		if(isset($_POST)){
   			$id = $this->Mmm->sanitize($_POST['id']);
   			$date_requested = date("Y-m-d",strtotime($_POST['date_requested']));
			//var_dump($date_requested); exit;
   			$date_released = date("Y-m-d",strtotime($_POST['date_released']));
			//var_dump($date_released);exit;
   			$requested_by = $this->Mmm->sanitize($_POST['requested_by']);
   			$purpose = $this->Mmm->sanitize($_POST['purpose']);
   			$amount = $this->Mmm->sanitize($_POST['amount']);
   			$department = $this->Mmm->sanitize($_POST['department']);
   			$type = $this->Mmm->sanitize($_POST['type']);

   			
   			if ($id!=""){ //edit bank
   			$sql = "UPDATE ac_cash_advances SET 
   			date_requested = '$date_requested',
   			date_released = '$date_released',
   			requested_by = '$requested_by',
   			purpose = '$purpose', 			
   			amount = '$amount',
   			department = '$department',
   			type = '$type'		
   			WHERE id = $id";
   			//var_dump($sql);exit;
 		
		$r = $this->Mmm->query($sql);	
			//var_dump($r);exit;
		if($r==true) {
			        
					$_SESSION['msg'] = "Cash Advance Info edited.";
				}
				else {
					$_SESSION['msg'] = "Cash Advance not edited.";
				}			
   		}
   		else{ //add bank
   			
   			$insert['date_requested'] = date("Y-m-d",strtotime($_POST['date_requested']));	
   			$insert['date_released'] = date("Y-m-d",strtotime($_POST['date_released']));				
   			$insert['requested_by'] = $this->Mmm->sanitize($_POST['requested_by']);			
   			$insert['purpose'] = $this->Mmm->sanitize($_POST['purpose']);					
   			$insert['amount'] = $this->Mmm->sanitize($_POST['amount']);					
   			$insert['department'] = $this->Mmm->sanitize($_POST['department']);					
   			$insert['type'] = $this->Mmm->sanitize($_POST['type']);					  		
   			$insert['stat'] = 1;	
   
   			$db	=	$this->Mmm->dbInsert('ac_cash_advances', $insert);
			if($db==true) {
				    
					$_SESSION['msg'] = "Cash Advance Added";
				}
				else {
					$_SESSION['msg'] = "Cash Advance Added.";
				}			
   	    	} 
   		}
   		header('Location:'.HTTP_PATH.'finance/ca_view'); die(); 	
   	}
	
	public function liquidation_form($id='')	{$data=array();
   	if($id!=''){
   		$data['cash'] = $this->Finance_model->getCashAdvance($id);
   	}
   		$this->load->view('finance/liquidation_form',$data);
   	}
	
		public function getPO(){
			
			
			//$sel_items = $_POST['id'];			
			//$itemDetails = explode(",",$sel_items[0]);
			//var_dump($itemDetails);
			//$item = explode("|",$itemDetails[0]);			
			//var_dump($item[1]);exit;
					
			if(isset($_POST['id'])){
					
					//var_dump($_POST['id']); exit;
					$selected_items = $_POST['id'];
					//$amount = $_POST['amount'];
					//$name = $_POST['name'];
					//var_dump($amount); exit;
					//echo $selected_items[0]; exit;	
					
					$itemGroup = explode(",",$selected_items);
					//var_dump($itemGroup);EXIT;
					$ctr = count($itemGroup) - 1;
					
					
					
					$res = "<table class='table table-striped table-bordered table-hover table-condensed' data-toggle='table' style='font-size:12px'>
                                	<tr align='center'>
										<td width='35%'>Name</td>
                                        <td width='20%' style='text-align:right;'>Line Total</td>
										<td width='5%'>*</td>	
                                    </tr>";
				
					$lineTotal = 0;
					$grandTotal = 0;
					
					for($i=0;$i < $ctr; $i++){				
					
								//separate item and qty
								$group = explode('|',$itemGroup[$i]);
								//var_dump($group);exit;
								//$item_id = $group[0];
								$name = $group[1];
								$amount = $group[0];
								$item_id = $group[3];
								$amount1 = $group[2];
								//var_dump($amount1);exit;
								//$item_id = $group[0];
								
                    			//$sql = "SELECT * FROM inventory WHERE id =".$item_id;
								
								//$db = $this->db->query($sql);
								
								//$result = $db->result_array();
								//var_dump($result);exit;
								$lineTotal = $amount;
												
					             $res.= "<tr>
                                        							
                                        
										<td align='left'>".$name."</td>	
										 <td align='right'>".number_format($lineTotal,2)."</td>
										<td align='center' ><a href='#' id='".$amount1.",' onclick='delItem(this.id); '><i class='glyphicons glyphicon-remove'></i></a></td>	
                                    </tr>
                                    ";
								//var_dump($res);exit;	
								$grandTotal = $grandTotal + $lineTotal;	
								
								$lessTotal = $amount1 - $grandTotal;
					
					};
		
								$res.="<tr>
                                    	
                                        <td style='text-align:right;'><strong>Total Liquidation:</strong></td>
                                        <td align='right'><strong> Php ".number_format($grandTotal,2)."</strong></td>
										<td></td>
                                    </tr> 
									<tr>
                                    	
                                        <td style='text-align:right;'><strong>Less Cash Advance:</strong></td>
                                        <td align='right'><strong>(Php ".number_format($amount1,2).")</strong></td>
										<td></td>
                                    </tr> 									
									<tr>
                                    	
                                        <td style='text-align:right;'><strong>Total:</strong></td>
                                        <td align='right'><strong>Php ".number_format($lessTotal,2)."</strong></td>
										<td></td>
                                    </tr>  									
                                </table>";
			
			}
	else{
				
				$res = '<div>No item selected. Please try again.</div>';
			}
			
			
			
			
		echo $res;
			
	}
	
	public function add_liquidation(){

		$items = $_POST['sel'];
		$itemGroup = explode(",",$items);
		$ctr = count($itemGroup) - 1;

						$grand_total = 0;
		
		//var_dump($items);exit;
		for($i=0;$i < $ctr; $i++){

								//separate item and qty
								$group = explode('|',$itemGroup[$i]);
								$name = $group[1];
								$amount = $group[0];
								$item_id = $group[3];
								$amount1 = $group[2];
								$date_liquidated = date("Y-m-d H:i:s");
								
								//var_dump($item_id);exit;
								
								//$insert['department'] = $this->Mmm->sanitize($_POST['department']);	
								
								$sql3 = "INSERT INTO ac_ca_liquidation(id, date_liquidated, ca_id, stat, amount, name) VALUES(0,'$date_liquidated','$item_id',0,'$amount', '$name')";
								
								//var_dump($sql3);exit;
								
								$db2 = $this->Mmm->query($sql3, 'Liquidation Details');
	//var_dump($db2);exit;
	}
	header('Location:'.HTTP_PATH.'finance'); die(); 	
	}
	
	//END ACCOUNTS MANAGEMENT
	
	public function name_data(){
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT * FROM hr_employees WHERE name LIKE '%".$search."%' ORDER BY name LIMIT 0, 10";
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
	
	
	
	public function purchasing_funding($id='')	{$data=array();
			
			if($id!=''){
				//$data['delivery_summary']	=	$this->Inventory_model->getDelivery($id);
				$data['voucher']	=	$this->Accounting_model->getVoucherInfo($id);
				$data['delivery_summary']	=	$this->Accounting_model->getDeliveryByVoucherId($id);
				$data['delivery_detail']	=	$this->Inventory_model->getDeliveryDetails($data['delivery_summary'][0]['id']);
				
				$this->load->view('finance/purchasing_funding.php',$data);
			}else{ var_dump('Error occured. No Id passed.');}
	}
	public function purchase_funding_approval($id='')	{$data=array();
			
			if($_POST){
				//$data['delivery_summary']	=	$this->Inventory_model->getDelivery($id);
				$vid = $this->Mmm->sanitize($_POST['voucher_id']);			
				$update['status']			=	'For voucher approval';
				$sql	=	$this->Mmm->dbUpdate('ac_vouchers', $update, $vid, "Voucher Available Fund");
				
				$this->Abas->sysMsg("sucmsg", "Voucher Approved");
				header('Location:'.HTTP_PATH.'finance');
				
			}else{ var_dump('Error occured. No Id passed.');}
	}
	
	
	
	public function cashier_view()	{$data=array();
		// echo '<div style="float:right">&nbsp;</div>';
		$data['vouchers'] = $this->Accounting_model->getVoucherForRelease();

		$this->load->view('finance/cashier_view',$data);
	}


	public function deparment_data(){
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT * FROM departments WHERE name LIKE '%".$search."%' ORDER BY name LIMIT 0, 10";
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
	//end of finance
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

	public function addExpense()	{$data=array();
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

			header('Location:'.HTTP_PATH.'accounting');

		}
		else {
			// echo "<div>Error Encountered, please contact administrator.</div>";
			$this->Abas->sysMsg("errmsg", "Error Encountered, please contact administrator.");
		}
	}


	public function view_all_vessels() {
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
	public function temp() {$data=array();
		$sql	=	'
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
		$data	=	$this->db->query($sql);
		$this->Mmm->debug($data->result());
	}




	public function payroll_reports() {$data=array();
		$data['companies']	=	$this->Abas->getCompanies();
		$this->load->view('payroll/accounting_reports.php',$data);
	}
	public function payroll_alphalist() {
		$this->load->library('Pdf');
		$this->Abas->checkPermissions("payroll|accounting");
		if(empty($_POST)) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please try again!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		if(!isset($_POST['company'], $_POST['month'], $_POST['year'])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please select a company, month and year!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		if($_POST['company']=="" || $_POST['month']=="" || $_POST['year']=="" || $_POST['company']==null || $_POST['month']==null || $_POST['year']==null) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please select a company, month and year!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		if(!is_numeric($_POST['company'])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid company!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}

		// $this->Mmm->debug($_POST);
		$company		=	(array)$this->Abas->getCompany($_POST['company']);
		$date_requested	=	date("Y-m", strtotime($_POST['year']."-".$_POST['month']."-01"));
		// $this->Mmm->debug($company);
		// $this->Mmm->debug($date_requested);

		$check	=	$this->db->query("SELECT * FROM hr_payroll WHERE payroll_date='".$date_requested."' AND company_id=".$company['id']." ORDER BY payroll_coverage ASC LIMIT 2");
		// $this->Mmm->debug($check);
		if(!$check) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid company!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		if(!$check->row()) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, payroll not found!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		$check	=	$check->result_array();
		$this->Mmm->debug($check);

		if(!isset($check[0])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, no payroll data found!");
			// header("location:".$_SERVER['HTTP_REFERER']);die();
		}

		$employees	=	$this->db->query("SELECT id, company_id, stat FROM hr_employees WHERE company_id=".$company['id']." AND stat=1 ORDER BY last_name ASC");
		if(!$employees) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee data!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		if(!$employees->row()) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee data!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}

		$table	=	"";
		$employees	=	$employees->result_array();
		if(!empty($employees)) {
			foreach($employees as $e) {
				$e			=	$this->Abas->getEmployee($e['id']);
				$namedata		=	"<tr>";
				$namedata		.=	"<td>".$e['full_name']."</td>";
				$total		=	array("salary"=>0, "tax"=>0, "net_pay"=>0);
				$rowdata	=	array("1st-half"=>"<td>0</td><td>0</td><td>0</td>", "2nd-half"=>"<td>0</td><td>0</td><td>0</td>", );
				foreach($check as $pr) {
					$hq		=	$this->db->query("SELECT * FROM hr_payroll_details WHERE emp_id=".$e['id']." AND payroll_id=".$pr['id']);
					if($hq) {
						if($hq->row()) {
							$hq	=	$hq->row();
							// $this->Mmm->debug($hq);
							$net_pay=	($hq->salary + $hq->allowance + $hq->regular_overtime_amount + $hq->holiday_overtime_amount) + $hq->bonus - ($hq->undertime_amount + $hq->absences_amount + $hq->tax + $hq->sss_contri_ee + $hq->phil_health_contri + $hq->elf_contri + $hq->elf_loan + $hq->pagibig_loan + $hq->cash_advance);
							$total['salary']	=	$total['salary'] + $hq->salary;
							$total['tax']		=	$total['tax'] + $hq->tax;
							$total['net_pay']	=	$total['net_pay'] + $net_pay;
							$rowdata[$pr['payroll_coverage']]	=	"<td>".$this->Abas->currencyFormat($hq->salary)."</td>";
							$rowdata[$pr['payroll_coverage']]	.=	"<td>".$this->Abas->currencyFormat($hq->tax)."</td>";
							$rowdata[$pr['payroll_coverage']]	.=	"<td>".$this->Abas->currencyFormat($net_pay)."</td>";
						}
					}
				}
				$totalrow	=	"<td>".$this->Abas->currencyFormat($total['salary'])."</td><td>".$this->Abas->currencyFormat($total['tax'])."</td><td>".$this->Abas->currencyFormat($total['net_pay'])."</td>";
				if($total['net_pay'] > 0) {
					$table	.=	$namedata.$rowdata['1st-half'].$rowdata['2nd-half'].$totalrow."</tr>";
				}
			}
		}

		$data['orientation']	=	"P";
		$data['pagetype']		=	"legal";
		$data['content']		=	'
		<div>
			<h2>'.$company['name'].'</h2>
			<h3>'.date("F Y", strtotime($date_requested)).'</h3>
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
				'.$table.'
			</tbody>
		</table>
		';
		$data['disp']	=	$data['content'];
		$data['viewfile']	=	"echo.php";
		$this->load->view('container.php',$data);
		// $this->load->view('pdf-container.php',$data);
	}
	public function annualization() {
		$this->load->library('Pdf');
		$this->load->model('Payroll_model');
		$this->Abas->checkPermissions("payroll|accounting");
		// $this->Mmm->debug($_POST);die();
		if(empty($_POST)) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please try again!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		if(!isset($_POST['employee'])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please select an employee!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		if($_POST['employee']=="" || $_POST['employee']==null) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, please select an employee!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}
		if(!is_numeric($_POST['employee'])) {
			$this->Abas->sysMsg("warnmsg", "Report generation failed, invalid employee!");
			header("location:".$_SERVER['HTTP_REFERER']);die();
		}

		$e	=	$this->Abas->getEmployee($_POST['employee']);
		//$this->Mmm->debug($e);
		$rates	=	$this->Payroll_model->getRates($e['salary_rate'], $e['vessel_id']);


		for($x=1; $x<=12; $x++) {
			$tablecontents[sprintf("%02d", $x)]	=	array("1st-half"=>array("salary"=>0, "ph"=>0, "pi"=>0, "sss"=>0), "2nd-half"=>array("salary"=>0, "ph"=>0, "pi"=>0, "sss"=>0));
		}unset($x);

		// get past payrolls
		$past_payrolls	=	$this->db->query("SELECT hpd.*, p.id, p.payroll_date, p.payroll_coverage FROM hr_payroll_details AS hpd JOIN hr_payroll AS p ON hpd.payroll_id=p.id WHERE emp_id=".$e['id']." AND p.id<>1");
		$past_payrolls	=	$past_payrolls->result_array();
		//$this->Mmm->debug($past_payrolls);
		foreach($past_payrolls as $pp) {
			$monthvalue		=	substr($pp['payroll_date'], 5, 2);
			$salary			=	($pp['salary']*0.8) - $pp['absences_amount'] - $pp['undertime_amount'];
			$tablecontents[$monthvalue][$pp['payroll_coverage']]	=	array("salary"=>$salary, "ph"=>$pp['phil_health_contri'], "pi"=>$pp['pagibig_contri'], "sss"=>$pp['sss_contri_ee']);
		}
		//$this->Mmm->debug($tablecontents);

		// assume future payrolls
		$sss_record			=	$this->Payroll_model->computeSSS($e['salary_rate']);
		$ph_record			=	$this->Payroll_model->computePH($e['salary_rate']);
		$pi_record			=	$this->Payroll_model->computePI($e['salary_rate']);

		for($monthvalue; $monthvalue<=12; $monthvalue++) {
			$tablecontents[sprintf("%02d", $monthvalue)]['1st-half']	=	array("salary"=>(($e['salary_rate']/2)*0.8), "ph"=>0, "pi"=>$pi_record['contribution'], "sss"=>0);
			$tablecontents[sprintf("%02d", $monthvalue)]['2nd-half']	=	array("salary"=>(($e['salary_rate']/2)*0.8), "ph"=>$ph_record['employee'], "pi"=>0, "sss"=>$sss_record['ee']);
		}

		// rock 'n' roll!
		$table	=	"";
		$total	=	array("salary"=>0, "sss"=>0, "ph"=>0, "pi"=>0);
		foreach($tablecontents as $monthctr=>$tc) {
			foreach($tc as $coverage=>$content) {
				$table	.=	'<tr>';
				$table	.=	'<td>'.date("F",strtotime(date("Y")."-".$monthctr."-01")).' '.$coverage.'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['salary']).'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['sss']).'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['ph']).'</td>';
				$table	.=	'<td>'.$this->Abas->currencyFormat($content['pi']).'</td>';
				$table	.=	'</tr>';
				$total['salary']	+=	$content['salary'];
				$total['sss']		+=	$content['sss'];
				$total['ph']		+=	$content['ph'];
				$total['pi']		+=	$content['pi'];
			}
		}
		$this->Mmm->debug($total);

		$total_income	=	$total['salary'];
		$total_deduction=	$total['sss'] + $total['ph'] + $total['pi'];

		$data['orientation']	=	"P";
		$data['pagetype']		=	"legal";
		$data['content']		=	'
		<div>
			<h2>'.$e['full_name'].'</h2>
			<h3>Annualization for '.date("Y").'</h3>
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
				'.$table.'
			</tbody>
		</table>
		';
		echo $data['content'];die();
		$this->load->view('pdf-container.php',$data);
	}
	
	
}

?>
