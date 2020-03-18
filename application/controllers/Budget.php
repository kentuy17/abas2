<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Budget extends CI_Controller
	{

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		$this->load->model("Mmm");
		$this->load->model("Budget_model");
		$this->output->enable_profiler(FALSE);
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home");}
		define("SIDEMENU", "Manager's Dashboard");
	}

	public function index()
	{
		redirect(HTTP_PATH.'budget/budget_view');
	}
		
		// START KENNETH
	public function myFunction()
	{
		$budget_year = $this->Abas->getItemsByGroup('budget_opex',array(),'year');
		$this->Mmm->debug($budget_year);
	}

	public function budget_view($year='')
	{
		$cond = array('generated_by' => $_SESSION['abas_login']['userid']);

		($year != '' ? $cond = array_merge($cond,array('year'=>$year)) : '');
		$generated = $this->Abas->getItems('budget_summary_index',$cond);
		$array = array();
		
		foreach ($generated as $ctr => $row) {

			$company = $this->Budget_model->getCompanyName($row->company_id);
			$department = $this->Budget_model->getDepartmentName($row->department_id);
			$vessel = $this->Budget_model->getVesselName($row->vessel_id);
			$total_debit = $row->cost_of_sales + $row->operating_expenses;
			$total_credit = $row->revenue;

			$array[$ctr] = array(
				'id' => $row->id,
				'company' => $company,
				'department' => $department,
				'vessel' => $vessel,
				'year' => $row->year,
				'revenue' => number_format($row->revenue,2),
				'cost_of_sales' => number_format($row->cost_of_sales,2),
				'operating_expenses' => number_format($row->operating_expenses,2),
				'assets' => number_format($row->assets,2),
				'liabilities' => number_format($row->liabilities,2),
				'equity' => number_format($row->equity,2),
				'total_debit' => $total_debit,
				'total_credit' => $total_credit,
				'status' => strtoupper($row->status)
			);
		}

		if(is_null($array)){
			$array = array();
		}else{
			$data['items'] = $array;
		}
		
		$data['viewfile'] = "budget/budget_view_tmp.php";
		$mainview = "gentlella_container.php";
		$this->load->view($mainview,$data);
	}

	public function verify_item_data($id)
	{
		$items = $this->Abas->getItems('budget_summary',array('index_id'=>$id));
		foreach ($items as $ctr => $row) {
			$generator = $this->Abas->getItemById('hr_employees',array('user_id'=>$row->generated_by));
			$company = $this->Abas->getItemById('companies',array('id'=>$row->company_id));
			if($row->department_id != 0){
				$tmp = $this->Abas->getItemById('departments',array('id'=>$row->department_id));
				$department = $tmp->name;
			}else{
				$department = 'All';
			}
			if($row->vessel_id != 0){
				$tmp = $this->Abas->getItemById('vessels',array('id'=>$row->vessel_id));
				$vessel = $tmp->name;
			}else{
				$vessel = 'All';
			}

			$item_array[$ctr] = array(
				'id' => $row->id,
				'type' => $row->type,
				'company' => $company->name,
				'department' => $department,
				'vessel' => $vessel,
				'total_amount' => number_format($row->total_amount,2)
			);
		}
		$data['total'] = $ctr + 1;
		$data['rows'] = $item_array;

		header('Content-Type: application/json');
		echo json_encode($data);
		exit();
	}

	public function view_account_type($id)
	{
		$budget_summary = $this->Abas->getItemById('budget_summary',array('id'=>$id));
		if($budget_summary != null){
			$cond = array(
				'budget_id' => $budget_summary->index_id,
				'account_type' => $budget_summary->type
			);
			$items = $this->Abas->getItems('budget_opex',$cond);
			$sum = $this->Abas->getSum('budget_opex','curr_budget',$cond);

			foreach ($items as $ctr => $row) {
				$generator = $this->Abas->getItemById('hr_employees',array('user_id'=>$row->created_by));
				$accounts = $this->Abas->getItemById('ac_accounts',array('id'=>$row->account_id));
				$generator = $this->Abas->getItemById('hr_employees',array('user_id'=>$row->created_by));
				$company = $this->Abas->getItemById('companies',array('id'=>$row->company_id));
				if($row->department_id != 0){
					$tmp = $this->Abas->getItemById('departments',array('id'=>$row->department_id));
					$department = $tmp->name;
				}else{
					$department = 'All';
				}
				if($row->vessel_id != 0){
					$tmp = $this->Abas->getItemById('vessels',array('id'=>$row->vessel_id));
					$vessel = $tmp->name;
				}else{
					$vessel = 'All';
				}
				$accounts_array[$ctr] = array(
					'id' => $row->id,
					'budget_aydi' => $budget_summary->id,
					'code' => $accounts->financial_statement_code.$accounts->general_ledger_code,
					'account' => $accounts->name,
					'company' => $company->name,
					'department' => $department,
					'vessel' => $vessel,
					'increment' => $row->increment,
					'generated_by' => $generator->last_name.', '.$generator->first_name,
					'generated_on' => $row->date_created,
					'prev_budget' => number_format($row->prev_budget,2),
					'curr_budget' => number_format($row->curr_budget,2),
					'account_type' => $row->account_type
				);
			}
			$data['status'] = $budget_summary->status;
			$data['type'] = $budget_summary->type;
			$data['sum'] = $sum->curr_budget;
			$data['items'] = $accounts_array;
			$data['id'] = $id;
			$data['viewfile'] = 'budget/for_approving.php';
		}else{
			$data['viewfile'] = 'prohibited.php';
		}
		$mainview = "gentlella_container.php";
		$this->load->view($mainview,$data);
	}


	public function verify_item_view($id)
	{	$data['id'] = $id;
		$data['index'] = $this->Abas->getItemById('budget_summary_index',array('id'=>$id));

		$this->load->view('budget/verify_item',$data);
	}

	public function item_view($id)
	{
		$data['id'] = $id;
		$data['index'] = $this->Abas->getItemById('budget_summary_index',array('id'=>$id));
		$budget_summary = $this->Abas->getItems('budget_summary',array('index_id' => $id,));
		foreach ($budget_summary as $ctr => $row) {
			if($row->type == 'Revenue' or $row->type == 'Cost of Sales'
			 or $row->type == 'Operating Expenses' or $row->type == 'Other Income'
			 or $row->type == 'Other Expense')
			$array[$ctr] = array(
				'id' => $row->id,
				'type' => $row->type,
				'company' => $this->Budget_model->getCompanyName($row->company_id),
				'department' => $this->Budget_model->getDepartmentName($row->department_id),
				'vessel' => $this->Budget_model->getVesselName($row->vessel_id),
				'total_amount' => $row->total_amount,
				'prev_budget' => $row->prev_budget
			);
		}
		$data['item'] = $array;
		$this->load->view('budget/item_info',$data);
	}

	public function approval_item_view($id)
	{	$data['id'] = $id;
		$this->load->view('budget/approval_item',$data);
	}

	public function approved_item_view($id)
	{
		$data['id'] = $id;
		$this->load->view('budget/approved',$data);
	}

	public function approve($action,$id)
	{
		$item = $this->Abas->getItemById('budget_summary_index',array('id'=>$id));
		switch ($action) {
			case 'verified':
				$status = 'for approval';
				$data = array(
					'status' => $status,
					'verified_by' => $_SESSION['abas_login']['userid'],
					'verified_on' => date('Y-m-d H:i:s')
				);
				break;

			case 'approved':
				$status = 'approved';
				$data = array(
					'status' => $status,
					'approved_by' => $_SESSION['abas_login']['userid'],
					'approved_on' => date('Y-m-d H:i:s')
				);
				break;

			case 'submit':
				$status = 'for verification';
				$data = array(
					'status' => $status,
					'generated_by' => $_SESSION['abas_login']['userid'],
					'generated_on' => date('Y-m-d H:i:s')
				);
				break;
			
			case 'reject':
				$status = 'draft';
				$data = array(
					'status' => $status
				);
				break;

			default:
				$status = 'for verification';
				break;
		}
		
		if(is_numeric($id)){
			$this->Abas->updateItem('budget_summary',$data,array('index_id'=>$id));
			$this->Abas->updateItem('budget_summary_index',array('status'=>$status),array('id'=>$id));
			$this->Abas->updateItem('budget_opex',array('status'=>$status),array('budget_id'=>$id));

		}else{
			$str = str_replace('_', ' ', $id);
			$this->Abas->updateItem('budget_summary',$data,array('status'=>$str));
			$this->Abas->updateItem('budget_summary_index',array('status'=>$status),array('status'=>$str));
			$this->Abas->updateItem('budget_opex',array('status'=>$status),array('status'=>$str));
		}
		
		if($action == 'submit'){
			redirect(HTTP_PATH.'budget/budget_view?year='.$item->year.'&status=submitted');	
		}else{
			redirect(HTTP_PATH.'budget/budget_approval');
		}
		
	}
	
	public function generate_budget()
	{	
		$custom = array();
		$status = 'draft';
		$session_id = $_SESSION['abas_login']['userid'];
		$user = $this->Abas->getItemById('hr_employees',array('user_id'=>$session_id));
		$reference_from = $this->input->post('reference_from');
		$reference_to = $this->input->post('reference_to');
		$target_year = $this->input->post('target_year');
		$remark = 'To take up closing entries as per BP and Company.';

		$ts1 = strtotime($reference_from);
		$ts2 = strtotime($reference_to);

		$year1 = date('Y', $ts1);
		$year2 = date('Y', $ts2);

		$month1 = date('m', $ts1);
		$month2 = date('m', $ts2);

		$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

		if($this->Abas->checkPermissions("manager|generate_all_budget",false)){
			//have access
			$company_id = $this->input->post('company_id');
			$department_id = $_POST['department_id'];

			if(isset($_POST['vessel_id'])){
				$department_id = 14;
				if($_POST['vessel_id'] != 'All'){
					$vessel_id = $_POST['vessel_id'];	
				}
			}
		}else{
			//no access
			$company_id = $user->company_id;
			$department_id = $user->department;
		}

		$cond = array(
			'company_id' => $company_id,
			'posted_on >=' => $reference_from,
			'posted_on <=' => $reference_to,
		);

		$del = array(
			'company_id' => $company_id,
			'year' => $target_year
		);

		$summary = array(
			'year' => $target_year,
			'generated_by' => $session_id,
			'generated_on' => date('Y-m-d H:i:s'),
			'company_id' => $company_id,
			'status' => $status
		);

		if(isset($vessel_id)){
			$cond = array_merge($cond,array('vessel_id' => $vessel_id));
			$del = array_merge($del,array('vessel_id' => $vessel_id));
			$summary = array_merge($summary,array('vessel_id'=>$vessel_id));
		}

		if ($department_id != 'All') {
			if($department_id != 14){
				$cond = array_merge($cond,array('department_id'=>$department_id));
			}
			$del = array_merge($del,array('department_id'=>$department_id));
			$summary = array_merge($summary,array('department_id'=>$department_id));
		}

		$this->Abas->delItem('budget_opex',$del);
		$this->Abas->delItem('budget_summary',$del);
		$this->Abas->delItem('budget_summary_index',$del);

		$account_codes = $this->Budget_model->getItemsGroup('ac_transaction_journal',$cond,'coa_id');

		$this->Abas->insertItem('budget_summary_index',$summary);
		$budget_id = $this->Abas->last_item('budget_summary_index');

		foreach ($account_codes as $key => $value) {
			$cond_sum = array(
				'company_id' => $company_id,
				'posted_on >=' => $reference_from,
				'posted_on <=' => $reference_to,
				'coa_id' => $value->coa_id,
				'remark !=' => $remark,
			);

			if(isset($vessel_id)){
				$cond_sum = array_merge($cond_sum,array('vessel_id'=>$vessel_id));
			}elseif ($department_id != 'All') {
				$cond_sum = array_merge($cond_sum,array('department_id'=>$department_id));
			}
			
			$total_debit = $this->Budget_model->getDebitSum($cond_sum);
			$total_credit = $this->Budget_model->getCreditSum($cond_sum);
			$account_type = $this->Abas->getItemById('ac_accounts',array('id'=>$value->coa_id));
			$percentage = $this->Abas->getItemById('budget_percentage',array('account_id'=>$value->coa_id));
			
			if($account_type->type == 'Revenue' or $account_type->type == 'Liabilities' or $account_type->type == 'Equity' or $account_type->type == 'Other Income'){
				$estimate_budget = $total_credit->credit_amount - $total_debit->debit_amount;
			}elseif ($account_type->type == 'Assets' or $account_type->type == 'Cost of Sales' or $account_type->type == 'Operating Expenses' or $account_type->type == 'Other Expense') {
				$estimate_budget = $total_debit->debit_amount - $total_credit->credit_amount;
			}else{
				$estimate_budget = 0;
			}

			$data = array(
				'account_id' => $value->coa_id,
				'year' => $target_year,
				'increment' => $percentage->percentage,
				'created_by' => $session_id,
				'date_created' => date('Y-m-d H:i:s'),
				'prev_budget' => $estimate_budget,
				'curr_budget' => (($estimate_budget * ($percentage->percentage/100)) + $estimate_budget),
				'company_id' => $company_id,
				'budget_id' => $budget_id->id,
				'account_type' => $account_type->type,
				'status' => $status
			);

			if(isset($vessel_id)){
				$data = array_merge($data,array('vessel_id' => $vessel_id));
			}

			if(isset($department_id)){
				$data = array_merge($data,array('department_id' => $department_id));
			}
			$this->Abas->insertItem('budget_opex',$data);
		}

		$account_type_array = array(
			'revenue' => 'Revenue',
			'cost_of_sales' => 'Cost of Sales',
			'operating_expenses' => 'Operating Expenses',
			'assets' => 'Assets',
			'liabilities' => 'Liabilities',
			'equity' => 'Equity',
			'other_income' => 'Other Income',
			'other_expense' => 'Other Expense'
		);

		foreach ($account_type_array as $key => $value) {
			${$key} = $this->Budget_model->getAccountTypeSum($value,$budget_id->id);
			$account_type_data = array(
				'type' => $value,
				'index_id' => $budget_id->id,
				'total_amount' => ${$key}->curr_budget,
				'prev_budget' => ${$key}->prev_budget,
				'status' => $status
			);
			$this->Budget_model->insertBudgetType($summary,$account_type_data);
		}

		$index_data = array(
			'revenue' => $revenue->curr_budget,
			'cost_of_sales' => $cost_of_sales->curr_budget,
			'operating_expenses' => $operating_expenses->curr_budget,
			'assets' => $assets->curr_budget,
			'liabilities' => $liabilities->curr_budget,
			'equity' => $equity->curr_budget
		);

		$this->Abas->updateItem('budget_summary_index',$index_data,array('id'=>$budget_id->id));

		redirect(HTTP_PATH.'budget/budget_view/'.$target_year);
	}

	public function generate_budget_form()
	{
		$data['user'] = $this->Abas->getItemById('hr_employees',array('user_id'=>$_SESSION['abas_login']['userid']));
		$data['departments'] = $this->Abas->getItems('departments');
		$data['companies'] = $this->Abas->getItems('companies');
		$data['vessels'] = $this->Abas->getItems('vessels');
		$this->load->view('budget/dialog',$data);
	}

	public function budget_approval()
	{
		$data['verify'] = $this->Abas->recordExist('budget_summary_index',array('status'=>'for verification'));
		$data['approval'] = $this->Abas->recordExist('budget_summary_index',array('status'=>'for approval'));
		$data['approved'] = $this->Abas->recordExist('budget_summary_index',array('status'=>'approved'));
		
		$data['viewfile'] = "budget/percentage.php";
		$mainview = "gentlella_container.php";
		$this->load->view($mainview,$data);
	}

	public function budget_percentage()
	{
		$year = date('Y');
		$user = $this->Abas->getItemById('hr_employees',array('user_id'=>$_SESSION['abas_login']['userid']));
		$limit  = isset($_GET['limit'])?$_GET['limit']:"";
		$offset	= isset($_GET['offset'])?$_GET['offset']:"";
		$order  = isset($_GET['order'])?$_GET['order']:"";
		$sort   = isset($_GET['sort'])?$_GET['sort']:"";
		$search	= isset($_GET['search'])?$_GET['search']:"";
		$where = "year=2019";
		$data  = $this->Abas->getDataForBSTable('budget_percentage',$search,$limit,$offset,$order,$sort,$where);

		foreach($data['rows'] as $ctr=>$entry) {
			$ac_accounts = $this->Abas->getItemById('ac_accounts',array('id'=>$entry['account_id']));
			$users = $this->Abas->getItemById('users',array('id'=>$entry['updated_by']));
			$data['rows'][$ctr]['account_name'] = $ac_accounts->name;
			$data['rows'][$ctr]['code'] = $ac_accounts->financial_statement_code.$ac_accounts->general_ledger_code;

			if($entry['updated_by'] != null){
				$data['rows'][$ctr]['updator'] = $users->last_name.', '.$users->first_name;
			}else{
				$data['rows'][$ctr]['updator'] = null;
			}
		}
		header('Content-Type: application/json');
		echo json_encode($data);
		exit();
	}

	public function edit_percentage($id)
	{ 
		$data['account'] = $this->Abas->getItemById('budget_percentage',array('id'=>$id));
		$data['ac_accounts'] = $this->Abas->getItemById('ac_accounts',array('id'=>$data['account']->account_id));
		$this->load->view('budget/percentage_dialog',$data);
	}

	public function set_increment()
	{
		$this->load->view('budget/set_increment');	
	}

	public function add_account_dialog($id)
	{
		$user = $this->Abas->getItemById('hr_employees',array('user_id'=>$_SESSION['abas_login']['userid']));
		$budget_summary = $this->Abas->getItemById('budget_summary',array('id'=>$id));
		$budget_percentage = $this->Budget_model->getAccntExcept($budget_summary->index_id);
		foreach ($budget_percentage as $key => $value) {
			$accounts = $this->Abas->getItemById('ac_accounts',array('id'=>$value->account_id));
			$updated_by = $this->Abas->getItemById('users',array('id'=>$value->updated_by));
			$item = array(
				'id' => $value->account_id,
				'code' => $accounts->financial_statement_code.$accounts->general_ledger_code,
				'name' => $accounts->name,
				'percentage' => $value->percentage,
				'updated_by' => $updated_by->last_name.', '.$updated_by->first_name,
				'updated_on' => $value->updated_on,
				'type' => $accounts->type
			);
			$data['budget_percentage'][$key] = $item;
			$data['summary_id'] = $id;
		}
		$this->load->view('budget/add_account',$data);
	}

	public function add_account($summary_id,$id,$amount)
	{
		$user_id = $_SESSION['abas_login']['userid'];
		$budget_summary = $this->Abas->getItemById('budget_summary',array('id'=>$summary_id));
		$percentage = $this->Abas->getItemById('budget_percentage',array('id'=>$id));
		$account = $this->Abas->getItemById('ac_accounts',array('id'=>$id));
		$index = $this->Abas->getItemById('budget_summary_index',array('id'=>$budget_summary->index_id));

		$budget_summary2 = $this->Abas->getItemById('budget_summary',array(
			'index_id' => $budget_summary->index_id,
			'type' => $account->type
		));

		if($budget_summary2 == null){
			$this->Abas->insertItem('budget_summary',array(
				'status' => 'draft',
				'type' => $account->type,
				'year' => $budget_summary->year,
				'generated_by' => $user_id,
				'generated_on' => date('Y-m-d H:i:s'),
				'department_id' => $budget_summary->department_id,
				'company_id' => $budget_summary->company_id,
				'vessel_id' => $budget_summary->vessel_id,
				'index_id' => $budget_summary->index_id,
			));
		}

		$item = array(
			'account_id' => $id,
			'department_id' => $budget_summary->department_id,
			'year' => $budget_summary->year,
			'increment' => 0,
			'created_by' => $user_id,
			'date_created' => date('Y-m-d H:i:s'),
			'company_id' => $budget_summary->company_id,
			'budget_id' => $budget_summary->index_id,
			'vessel_id' => $budget_summary->vessel_id,
			'account_type' => $account->type,
			'status' => 'draft',
			'curr_budget' => $amount
		);
		$this->Abas->insertItem('budget_opex',$item);
		$cond = array(
			'budget_id' => $budget_summary->index_id,
			'account_type' => $account->type
		);

		$total_type = $this->Budget_model->getSumByCol('budget_opex','curr_budget',$cond);
		$this->Abas->updateItem('budget_summary',array('total_amount' => $total_type),array(
			'index_id' => $budget_summary->index_id,
			'type' => $account->type
		));
		$this->Abas->updateItem('budget_summary_index',
			array($this->typeSwitch($account->type) => $total_type),
			array('id' => $budget_summary->index_id)
		);
		
		$redirect = $this->Abas->getItemById('budget_summary',array(
			'index_id' => $budget_summary->index_id,
			'type' => $account->type
		));

		redirect(HTTP_PATH.'budget/view_account_type/'.$redirect->id.'?status=added');
	}

	public function del_budget($id)
	{
		$this->Abas->delItem('budget_opex',array('budget_id'=>$id));
		$this->Abas->delItem('budget_summary',array('index_id'=>$id));
		$this->Abas->delItem('budget_summary_index',array('id'=>$id));

		redirect(HTTP_PATH.'budget/budget_view?action=delete&status=success');
	}

	public function del_account($id)
	{
		$item = $this->Abas->getItemById('budget_opex',array('id'=>$id));
		$this->Abas->delItem('budget_opex',array('id'=>$id));
		$sum = $this->Abas->getSum('budget_opex','curr_budget',array('budget_id'=>$item->budget_id));
		$this->Abas->updateItem('budget_summary',array('total_amount'=>$sum->curr_budget),array('id'=>$item->budget_id));

		redirect(HTTP_PATH.'budget/budget_view');
	}

	public function edit_percent($id,$percent)
	{	
		$sid = $_SESSION['abas_login']['userid'];
		$item = $this->Abas->getItemById('budget_opex',array('id'=>$id));
		$budget_summary = $this->Abas->getItemById('budget_summary',array(
			'index_id' => $item->budget_id,
			'type' => $item->account_type
		));
		$budget_index = $this->Abas->getItemById('budget_summary_index',array('id'=>$item->budget_id));
		$curr_budget = (($percent/100) + 1) * $item->prev_budget;

		$data = array(
			'increment' => $percent,
			'curr_budget' => $curr_budget,
			'updated_by' => $sid,
			'date_updated' => date('Y-m-d H:i:s')
		);
		$this->Abas->updateItem('budget_opex',$data,array('id'=>$id));

		$cond = array(
			'budget_id' => $item->budget_id,
			'account_type' => $item->account_type
		);
		$sum_budget_opex = $this->Budget_model->getSumByCol('budget_opex','curr_budget',$cond);
		$this->Abas->updateItem('budget_summary',array('total_amount'=>$sum_budget_opex),array('id'=>$budget_summary->id));
		$this->Abas->updateItem('budget_summary_index',
			array($this->typeSwitch($item->account_type) => $sum_budget_opex),
			array('id' => $item->budget_id)
		);
		
		redirect(HTTP_PATH.'budget/view_account_type/'.$budget_summary->id.'?status=success');
	}

	public function edit_amount($id,$amount)
	{	
		if(is_numeric($amount)){
			$session_id = $_SESSION['abas_login']['userid'];
			$item = $this->Abas->getItemById('budget_opex',array('id'=>$id));
			$cond = array(
				'budget_id' => $item->budget_id,//86
				'account_type' => $item->account_type
			);

			$budget_item = $this->Abas->getItemById('budget_summary',
				array( 
					'index_id' => $item->budget_id,
					'type' => $item->account_type
				)
			);
			$index = $this->Abas->getItemById('budget_summary_index',array('id'=>$item->budget_id));
			
			if($item->prev_budget == 0){
				$new_percent = '';
			}else{
				$new_percent = (($amount - $item->prev_budget) * 100)/$item->prev_budget;	
			}
			
			$data = array(
				'increment' => $new_percent,
				'curr_budget' => $amount,
				'updated_by' => $session_id,
				'date_updated' => date('Y-m-d H:i:s'),
			);
			$this->Abas->updateItem('budget_opex',$data,array('id' => $id));
			
			$sum_budget_opex = $this->Budget_model->getSumByCol('budget_opex','curr_budget',$cond);

			$this->Abas->updateItem('budget_summary',
				array('total_amount' => $sum_budget_opex),
				array('id' => $budget_item->id)
			);
			$type = $this->typeSwitch($item->account_type);
			$this->Abas->updateItem('budget_summary_index',array($type => $sum_budget_opex));
			redirect(HTTP_PATH.'budget/view_account_type/'.$budget_item->id.'?status=success');
		}else{
			redirect(HTTP_PATH.'budget/budget_view?status=error');
		}
	}

	public function update_percentage($id)
	{
		$year = date('Y');
		$percentage = $this->input->post('percentage');
		$account = $this->Abas->getItemById('budget_percentage',array('id'=>$id));
		$where = array('account_id'=>$account->account_id,'year'=>$year);
		$items = $this->Abas->getItems('budget_opex',$where);

		$data = array(
			'updated_by' => $_SESSION['abas_login']['userid'],
			'updated_on' => date('Y-m-d H:i:s'),
			'percentage' => $percentage
		);

		foreach ($items as $key => $value) {
			$tmp = ($value->prev_budget * ($percentage/100)) + $value->prev_budget;
			$curr_budget = number_format($tmp,2);
			$budget = array(
				'updated_by' => $_SESSION['abas_login']['userid'],
				'date_updated' => date('Y-m-d H:i:s'),
				'increment' => $percentage,
				'curr_budget' => $curr_budget,
			);
			$this->Abas->updateItem('budget_opex',$budget,$where);
			$this->Mmm->debug($budget);
		}

		$this->Abas->updateItem('budget_percentage',$data,array('id'=>$id));

		redirect(HTTP_PATH.'budget/budget_approval');
	}

	public function switchType($type) {
		switch ($type) {
			case 'revenue':
				$account_type = 'Revenue';
				break;

			case 'cost_of_sales':
				$account_type = 'Cost of Sales';
				break;

			case 'operating_expenses':
				$account_type = 'Operating Expenses';
				break;

			case 'assets':
				$account_type = 'Assets';
				break;

			case 'liabilities':
				$account_type = 'Liabilities';
				break;

			case 'other_income':
				$account_type = 'Other Incme';
				break;
			
			default:
				$account_type = 'Other Accounts';
				break;
		}

		return $account_type;
	}

	public function typeSwitch($type) {
		switch ($type) {
			case 'Revenue':
				return 'revenue';
				break;
			
			case 'Cost of Sales':
				return 'cost_of_sales';
				break;

			case 'Operating Expenses':
				return 'operating_expenses';
				break;

			case 'Assets':
				return 'assets';
				break;

			case 'Liabilities':
				return 'liabilities';
				break;

			case 'Other Income':
				return 'other_income';
				break;

			default:
				return 'Other Accounts';
				break;
		}
	}
	public function verify_budget_summary($type='')
	{
		$str = str_replace('_', ' ', $type);

		$budget_index = $this->Abas->getItems('budget_summary_index',array('status'=>$str));
		foreach ($budget_index as $ctr => $row) {
			$generator = $this->Abas->getItemById('hr_employees',array('user_id'=>$row->generated_by));
			$company = $this->Abas->getItemById('companies',array('id'=>$row->company_id));
			if($row->department_id != 0){
				$tmp = $this->Abas->getItemById('departments',array('id'=>$row->department_id));
				$department = $tmp->name;
			}else{
				$department = 'All';
			}
			if($row->vessel_id != 0){
				$tmp = $this->Abas->getItemById('vessels',array('id'=>$row->vessel_id));
				$vessel = $tmp->name;
			}else{
				$vessel = 'All';
			}
			$index_array[$ctr] = array(
				'id' => $row->id,
				'year' => $row->year,
				'generated_by' => $generator->last_name.', '.$generator->first_name,
				'generated_on' => $row->generated_on,
				'department' => $department,
				'company' => $company->name,
				'vessel' => $vessel,
				'revenue' => number_format($row->revenue,2),
				'cost_of_sales' => number_format($row->cost_of_sales,2),
				'operating_expenses' => number_format($row->operating_expenses,2),
				'assets' => number_format($row->assets,2),
				'liabilities' => number_format($row->liabilities,2),
				'equity' => number_format($row->equity,2),
			);
		}
		$data['total'] = $ctr + 1;
		$data['rows'] = $index_array;
		//$this->Mmm->debug($data);
		header('Content-Type: application/json');
		echo json_encode($data);
		exit();
	}

	public function confirm($action)
	{
		if($action == 'increment'){
			$percentage = $this->input->post('percentage');
			$item = $this->Abas->getItems('budget_opex',array('year'=>2019));
			$summary = $this->Abas->getItems('budget_summary');
			
			foreach ($item as $key => $value) {
				$data = array(
					'increment' => $percentage,
					'updated_by' => $_SESSION['abas_login']['userid'],
					'date_updated' => date('Y-m-d H:i:s'),
					'curr_budget' => ($value->prev_budget * ($percentage/100)) + $value->prev_budget
				);
				$this->Abas->updateItem('budget_opex',$data,array('year'=>2019));
			}

			foreach ($summary as $key => $value) {
				$sum = $this->Abas->getSum('budget_opex','curr_budget',array('budget_id'=>$value->id));
				$this->Abas->updateItem('budget_summary',array('total_amount'=>$sum->curr_budget),array('id'=>$value->id));
			}
			$data2 = array(
				'percentage' => $percentage,
				'updated_by' => $_SESSION['abas_login']['userid'],
				'updated_on' => date('Y-m-d H:i:s')
			);
			$char = $this->Abas->updateItem('budget_percentage',$data2,array('year'=>2019));

			$data3 = array(
				'percentage' => $percentage,
				'created_by' => $_SESSION['abas_login']['userid'],
				'created_on' => date('Y-m-d H:i:s'),
				'status' => true
			);

			$this->Abas->updateItem('budget_default_percentage',array('status'=>false),array());
			$this->Abas->insertItem('budget_default_percentage',$data3);

			redirect(HTTP_PATH.'budget/budget_approval');
		}
	}

	public function company_summary_report($get_year='')
	{
		if($get_year != ''){
			$year = $get_year;
		}else{
			$year = date('Y');
		}
		$user = $this->Abas->getItemById('hr_employees',array('user_id'=>$_SESSION['abas_login']['userid']));
		$companies = $this->Abas->getItems('companies');
		$ac_accounts = $this->Abas->getItems('ac_accounts');
		$classifications = $this->Abas->getItems('ac_accounts_classification');
		$data['budget_year'] = $this->Abas->getItemsByGroup('budget_summary',array(),'year');
		
		foreach ($companies as $key => $value) {
			$cur_revenue = $this->Budget_model->companyBudgetCurr($value->id,$year,'Revenue');
			$cur_cost_of_sales = $this->Budget_model->companyBudgetCurr($value->id,$year,'Cost of Sales');
			$cur_opex = $this->Budget_model->companyBudgetCurr($value->id,$year,'Operating Expenses');
			$cur_other_income = $this->Budget_model->companyBudgetCurr($value->id,$year,'Other Income');
			$cur_other_expense = $this->Budget_model->companyBudgetCurr($value->id,$year,'Other Expense');

			$prev_revenue = $this->Budget_model->companyBudgetPrev($value->id,$year,'Revenue');
			$prev_cost_of_sales = $this->Budget_model->companyBudgetPrev($value->id,$year,'Cost of Sales');
			$prev_opex = $this->Budget_model->companyBudgetPrev($value->id,$year,'Operating Expenses');
			$prev_other_income = $this->Budget_model->companyBudgetPrev($value->id,$year,'Other Income');
			$prev_other_expense = $this->Budget_model->companyBudgetPrev($value->id,$year,'Other Expense');

			$array = array(
				'id' => $value->id,
				'name' => $value->name,
				'abbreviation' => $value->abbreviation,
				'total_credit' => $cur_revenue + $cur_other_income,
				'total_debit' => $cur_cost_of_sales + $cur_opex + $cur_other_expense,
				'last_year' => ($prev_revenue + $prev_other_income) - ($prev_cost_of_sales + $prev_opex + $prev_other_expense),
				'this_year' => ($cur_revenue + $cur_other_income) - ($cur_cost_of_sales + $cur_opex + $cur_other_expense),
				'year' => $year
			);
			$data['company'][$key] = $array;
		}

		//---------------------------------------------------------------------------------------
		foreach ($ac_accounts as $key => $value) {
			$curr_budget2 = 0;
			$prev_budget2 = 0;
			$this_yr_data2 = array(
				'account_id' => $value->id,
				'year' => $year
			);
			$this_yr2 = $this->Abas->getSum('budget_opex','curr_budget',$this_yr_data2);
			$last_yr2 = $this->Abas->getSum('budget_opex','prev_budget',$this_yr_data2);
			$array2 = array(
				'id' => $value->id,
				'code' => $value->financial_statement_code.$value->general_ledger_code,
				'name' => $value->name,
				'last_year' => $last_yr2->prev_budget,
				'this_year' => $this_yr2->curr_budget,
				'type' => $value->type,
				'year' => $year
			);
			$data['accounts'][$key] = $array2;
		}
		//---------------------------------------------------------------------------------------
		foreach ($classifications as $key => $value) {
			$this_yr3 = $this->Budget_model->getSumByClassificationCurr($year,$value->id);
			$last_yr3 = $this->Budget_model->getSumByClassificationPrev($year,$value->id);

			$array3 = array(
				'id' => $value->id,
				'classification' => $value->name,
				'last_year' => $last_yr3->total,
				'this_year' => $this_yr3->total,
				'year' => $year
			);
			$data['classifications'][$key] = $array3;
		}
		$data['viewfile'] = "budget/company_summary_report.php";
		$mainview = "gentlella_container.php";
		$this->load->view($mainview,$data);
	}

	public function view_company_report($id)
	{
		$data['id'] = $id;
		$get_year = $this->input->get('year');
		if($get_year == null){
			$year = date('Y');
		}else{
			$year = $get_year;
		}
		$data['company'] = $this->Abas->getItemById('companies',array('id'=>$id));
		$items = $this->Abas->getItems('budget_summary_index',array('company_id'=>$id,'year'=>$year));

		foreach ($items as $key => $value) {
			$department = $this->Abas->getItemById('departments',array('id'=>$value->department_id));
			$user = $this->Abas->getItemById('hr_employees',array('user_id'=>$value->generated_by));
			$department != null ? $department_name = $department->name : $department_name = 'All';
			$total_credit = $value->revenue;
			$total_debit = $value->cost_of_sales + $value->operating_expenses;
			$budget = $total_credit - $total_debit;
			$array = array(
				'id' => $key+1,
				'department' => $department_name,
				'debit' => $total_debit,
				'credit' => $total_credit,
				'status' => strtoupper($value->status),
				'generated_by' => $user->last_name.', '.$user->first_name,
				'generated_on' => $value->generated_on,
			);
			$data['budget'][$key] = $array;
		}
		$this->load->view('budget/view_company_report',$data);
	}

	public function view_account_report($id)
	{
		$get_year = $this->input->get('year');
		$get_year==null ? $year=date('Y') : $year=$get_year;
		$items = $this->Abas->getItems('budget_opex',array('year'=>$year,'account_id'=>$id));
		$data['ac_account'] = $this->Abas->getItemById('ac_accounts',array('id'=>$id));

		foreach ($items as $key => $value) {
			$company = $this->Abas->getItemById('companies',array('id'=>$value->company_id));
			$department = $this->Abas->getItemById('departments',array('id'=>$value->department_id));
			$department !=null ? $department_name=$department->name : $department_name='All';
			$array = array(
				'id' => $value->id,
				'company' => $company->name,
				'department' => $department_name,
				'last_year' => $value->prev_budget,
				'this_year' => $value->curr_budget,
				'increment' => $value->increment
			);
			$data['account'][$key] = $array;
		}
		$this->load->view('budget/view_account_report',$data);
	}

	public function view_classification_report($id)
	{
		$get_year = $this->input->get('year');
		if($get_year == null){
			$year = date('Y');
		}else{
			$year = $get_year;
		}
		$items = $this->Budget_model->getItemByClassification($year,$id);
		$classification = $this->Abas->getItemById('ac_accounts_classification',array('id'=>$id));
		//$this->Mmm->debug($classification);
		//$this->Mmm->debug($items);
		foreach ($items as $key => $value) {
			$company = $this->Abas->getItemById('companies',array('id'=>$value->company_id));
			$department = $this->Abas->getItemById('departments',array('id'=>$value->department_id));
			$array = array(
				'id' => $value->id,
				'company' => $company->name,
				'department' => $department->name,
				'account_name' => $value->account_name,
				'last_year' => $value->prev_budget,
				'this_year' => $value->curr_budget,
			);
			$data['classification'][$key] = $array;
		}
		$data['item_name'] = $classification->name;
		$this->load->view('budget/view_classification_report',$data);
	}

	public function graph()
	{
		$companies = $this->Abas->getItems('companies');
		$total = $this->Abas->getSum('budget_summary','total_amount',array('status'=>'approved'));

		$data['companies'] = $this->Abas->getItems('companies');
		$data['sum'] = $total->total_amount;
		$get_year = $this->input->get('year');
		if($get_year != null){
			$year = $get_year;
		}else{
			$year = date('Y');
		}

		foreach ($companies as $key => $value) {
			$cTotal = 0;
			$cTotalData = array(
				'company_id' => $value->id,
				'status' => 'approved',
				'year' => $year
			);
			$company_total = $this->Abas->getSum('budget_summary','total_amount',$cTotalData);
			
			if($company_total->total_amount != null){
				$cTotal = $company_total->total_amount;
			}
			$array = array(
				'company_id' => $value->id,
				'name' => $value->name,
				'abbreviation' => $value->abbreviation,
				'company_budget' => $cTotal
			);
			$data['item'][$key] = $array;
		}
		$data['viewfile'] = "budget/graph.php";

		$mainview = "gentlella_container.php";
		$this->load->view($mainview,$data);
	}

	public function summary_report($action='')
	{
		if($action == 'report'){
			//Checking
			if(!isset($_POST['target_year']) or !isset($_POST['company_id'])){
				redirect(HTTP_PATH.'manager/summary_report');
			}

			//Initialise
			$cond = array(
				'budget_opex.year' => $_POST['target_year'],
				'status' => 'approved'
			);
			$data['target_year'] = $_POST['target_year'];
			
			if(isset($_POST['department_id'])){
				if($_POST['department_id'] != 0){
					$tmp = $this->Abas->getItemById('departments',array('id'=>$_POST['department_id']));	
					$data['department_name'] = $tmp->name;
					$cond = array_merge($cond,array('budget_opex.department_id'=>$_POST['department_id']));
				}
			}

			if(isset($_POST['vessel_id'])){
				if($_POST['vessel_id'] != 0){
					$tmp = $this->Abas->getItemById('vessels',array('id'=>$_POST['vessel_id']));	
					$data['vessel_name'] = $tmp->name;
					$cond = array_merge($cond,array('budget_opex.vessel_id'=>$_POST['vessel_id']));
				}
				$data['vessel_name'] = '';
			}
				

			if(is_numeric($_POST['company_id'])){
				$tmp = $this->Abas->getItemById('companies',array('id'=>$_POST['company_id']));
				$data['company'] = $tmp->name;
				$cond = array_merge($cond,array('budget_opex.company_id'=>$_POST['company_id']));
			}
			//$this->Mmm->debug($cond);

			//Revenue
			$revenue_acc = $this->Abas->getItems('ac_accounts',array('type'=>'Revenue'));
			foreach ($revenue_acc as $key => $value) {
				$revenue_array[$value->name] = array(
					'financial'=> $value->financial_statement_code,
					'general' => $value->general_ledger_code
				);
			}
			$tmp_revenue = array();
			foreach ($revenue_array as $key => $value) {
				$sum_revenue = $this->Budget_model->getRevenueAccounts($value['financial'],$value['general'],$cond);
				$tmp_revenue = array_merge($tmp_revenue,array($key => $sum_revenue->curr_budget));
			}
			$data['revenue_accounts'] = $tmp_revenue;
			$data['revenue'] = $this->Budget_model->getRevenue($cond);

			//Direct Cost
			$direct_cost_acc = $this->Abas->getItems('ac_accounts',array('type'=>'Cost of Sales'));
			foreach ($direct_cost_acc as $key => $value) {
				$direct_cost_array[$value->name] = array(
					'financial'=> $value->financial_statement_code,
					'general' => $value->general_ledger_code
				);
			}
			$tmp_direct_cost = array();

			foreach ($direct_cost_array as $key => $value) {
				$sum_direct_cost = $this->Budget_model->getRevenueAccounts($value['financial'],$value['general'],$cond);
				$tmp_direct_cost = array_merge($tmp_direct_cost,array($key => $sum_direct_cost->curr_budget));
			}
			$data['direct_cost_accounts'] = $tmp_direct_cost;
			$data['direct_cost'] = $this->Budget_model->getDirectCost($cond);

			//Other Income
			$other_income_acc = $this->Abas->getItems('ac_accounts',array('type'=>'Other Income'));
			foreach ($other_income_acc as $key => $value) {
				$other_income_array[$value->name] = array(
					'financial'=> $value->financial_statement_code,
					'general' => $value->general_ledger_code
				);
			}
			$tmp_other_income = array();

			foreach ($other_income_array as $key => $value) {
				$sum_other_income = $this->Budget_model->getRevenueAccounts($value['financial'],$value['general'],$cond);
				$tmp_other_income = array_merge($tmp_other_income,array($key => $sum_other_income->curr_budget));
			}
			$data['other_income_accounts'] = $tmp_other_income;
			$data['other_income'] = $this->Budget_model->getOtherIncome($cond);

			$data['gross_income'] = $data['revenue']->curr_budget - $data['direct_cost']->curr_budget;

			$opex_array = array(
				'Employees\' salaries, wages and...' => '6166',
				'Power, Light and Water' => '6167',
				'Rental' => '6168',
				'Insurance' => '6169',
				'Office Supplies' => '6170',
				'Transportation and Travel' => '6171',
				'Fuel, Oil and Lubricants' => '6172',
				'Entertainment, Recreation and ...' => '6173',
				'Repairs and Maintenance' =>'6174',
				'Depreciation' => '6175',
				'Amortization' => '6176',
				'Taxes and Licenses' => '6177',
				'Professional Fees' => '6178',
				'Advertising and Promotions' => '6179',
				'Security Services' => '6180',
				'Donations and Contributions' => '6181',
				'Communication and Postage' => '6182',
				'Interest' => '6183',
				'Provision for Doubtful Account...' => '6184',
				'Dues and Subscriptions' => '6185',
				'Conference and Meetings' => '6186',
				'Miscellaneous Expense' => '6195',
			);

			$tmp = array();
			$total_sum = 0;
			foreach ($opex_array as $key => $value) {
				$sum = $this->Budget_model->getOpex($value,$cond);
				$tmp = array_merge($tmp,array($key => $sum->curr_budget));
				$total_sum += $sum->curr_budget;
			}
			$data['operating_expenses'] = $tmp;
			$data['sub_total'] = $total_sum;
			$data['interest_expense'] = $this->Budget_model->getAccountCodeSum($cond,271);

			$data['viewfile'] = "budget/summary_report.php";
			$mainview = "gentlella_container.php";
			
		}
		elseif($action == 'filter')
		{	
			$user_id = $_SESSION['abas_login']['userid'];
			$data['user'] = $this->Abas->getItemById('hr_employees',array('user_id'=>$user_id));
			$data['departments'] = $this->Abas->getItems('departments');
			$data['companies'] = $this->Abas->getItems('companies');
			$data['vessels'] = $this->Abas->getItems('vessels');

			$mainview = "budget/summary_report_filter.php";
		}
		else{
			$data['viewfile'] = "iring.php";
			$mainview = "gentlella_container.php";
		}
		$this->load->view($mainview,$data);
		//-----------------------------------------------------
	}

	// END KENNETH
		
	}
?>