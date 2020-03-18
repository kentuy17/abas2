<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Budget_model extends CI_Model{
	
	public function getAccntExcept($id)
	{
		$this->db->from('budget_percentage');
		$this->db->where('`account_id` NOT IN
			(SELECT `account_id` FROM `budget_opex` WHERE `budget_id`='.$id.')');
		$query = $this->db->get();
		return $query->result();
	}

	public function getSumByClassificationCurr($year,$class_id)
	{
		$sql = "SELECT sum(b.curr_budget) as 'total' FROM budget_opex b
				LEFT JOIN ac_accounts a ON b.account_id = a.id
				LEFT JOIN ac_accounts_classification c ON a.classification = c.id
				WHERE b.year = $year AND c.id = $class_id";
		$query = $this->db->query($sql);
		return $query->row();
	}

	public function getSumByClassificationPrev($year,$class_id)
	{
		$sql = "SELECT sum(b.prev_budget) as 'total' FROM budget_opex b
				LEFT JOIN ac_accounts a ON b.account_id = a.id
				LEFT JOIN ac_accounts_classification c ON a.classification = c.id
				WHERE b.year = $year AND c.id = $class_id";
		$query = $this->db->query($sql);
		return $query->row();
	}

	public function getItemByClassification($year,$class_id)
	{
		$sql = "SELECT b.id id, b.department_id department_id, b.company_id company_id, a.name account_name,
				b.curr_budget curr_budget, b.prev_budget prev_budget
				FROM budget_opex b
				LEFT JOIN ac_accounts a ON b.account_id = a.id
				LEFT JOIN ac_accounts_classification c ON a.classification = c.id
				WHERE b.year = $year AND c.id = $class_id
				ORDER BY b.company_id";
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function getItemsGroup($tbl,$data=array(),$col,$custom=array())
	{
		$this->db->from($tbl);
		$this->db->where($data);
		$this->db->where($custom);
		$this->db->group_by($col);
		$query = $this->db->get();
		return $query->result();
	}

	public function getRevenue($data)
	{
		$this->db->select_sum('curr_budget');
		$this->db->where($data);
		$this->db->where('a.type','Revenue');
		$this->db->join('ac_accounts a','a.id=budget_opex.account_id','left');
		$query = $this->db->get('budget_opex');
		return $query->row();
	}

	public function getDirectCost($data)
	{
		$this->db->select_sum('curr_budget');
		$this->db->where($data);
		$this->db->where('a.type','Cost of Sales');
		$this->db->join('ac_accounts a','a.id=budget_opex.account_id','left');
		$query = $this->db->get('budget_opex');
		return $query->row();
	}

	public function getOtherIncome($data)
	{
		$this->db->select_sum('curr_budget');
		$this->db->where($data);
		$this->db->where('a.type','Other Income');
		$this->db->join('ac_accounts a','a.id=budget_opex.account_id','left');
		$query = $this->db->get('budget_opex');
		return $query->row()->curr_budget;
	}

	public function getOpEx($code,$data)
	{
		$this->db->select_sum('curr_budget');
		$this->db->where($data);
		$this->db->like('a.financial_statement_code',$code,'after');
		$this->db->join('ac_accounts a','a.id=budget_opex.account_id','left');
		$query = $this->db->get('budget_opex');
		return $query->row();
	}

	public function getRevenueAccounts($financial,$general,$data)
	{
		$this->db->select_sum('curr_budget');
		$this->db->where($data);
		$this->db->where('a.financial_statement_code',$financial);
		$this->db->where('a.general_ledger_code',$general);
		$this->db->join('ac_accounts a','a.id=budget_opex.account_id','left');
		$query = $this->db->get('budget_opex');
		return $query->row();
	}

	public function getTransactionJournal($cond)
	{
		$this->db->where($cond);
		//$this->db->where('debit_amount !=','0.00');
		$query = $this->db->get('ac_transaction_journal');
		return $query->result();
	}

	public function getDebitSumTmp($coa_id,$from,$to)
	{	
		$sql = "SELECT sum(debit_amount) from ac_transaction_journal
				where posted_on between $from and $to
				and coa_id=$coa_id
				and (posted_on not like '%-12-30 %' and posted_on not like '%-12-31 %')
				and debit_amount!=0";
		$query = $this->db->query($sql);
		return $query->row();
	}

	public function getDebitSum($code)
	{	
		$this->db->select_sum('debit_amount');
		$this->db->where($code);
		$this->db->where('`posted_on` not like "%-12-30 %" and `posted_on` not like "%-12-31 %"');
		$query = $this->db->get('ac_transaction_journal');
		return $query->row();
		//$this->fucking_bullshit_function();
	}

	public function getCreditSum($code)
	{	
		$this->db->select_sum('credit_amount');
		$this->db->where($code);
		$this->db->where('`posted_on` not like "%-12-30 %" and `posted_on` not like "%-12-31 %"');
		$query = $this->db->get('ac_transaction_journal');
		return $query->row();
	}

	public function getAccountTypeSum($type,$budget_id)
	{	
		$cond = array('account_type'=>$type,'budget_id'=>$budget_id);
		$this->db->select_sum('curr_budget');
		$this->db->select_sum('prev_budget');
		$this->db->where($cond);
		$query = $this->db->get('budget_opex');
		return $query->row();
	}

	public function insertBudgetType($cond,$account_type_data)
	{	
		$cond = array_merge($cond,$account_type_data);
		$this->db->insert('budget_summary',$cond);
	}

	public function getAccountCodeSum($cond,$id)
	{	
		$cond = array_merge($cond,array('account_id'=>$id));
		$this->db->select_sum('curr_budget');
		$this->db->where($cond);
		$query = $this->db->get('budget_opex');
		return $query->row()->curr_budget;
	}

	public function getCompanyName($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('companies');
		if($query->num_rows()){
			return $query->row()->name;	
		}else{
			return null;
		}
	}

	public function getDepartmentName($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('departments');
		if($query->num_rows()){
			return $query->row()->name;	
		}else{
			return null;
		}
	}

	public function getVesselName($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('vessels');
		if($id == 0){
			return null;
		}elseif($query->num_rows()){
			return $query->row()->name;	
		}else{
			return null;
		}
	}

	public function getSums()
	{
		$this->db->select_sum('total_amount');
		$this->db->select_sum('prev_budget');
		$query = $this->db->get('budget_summary');
		return $query->row();
	}

	public function getSumByCol($tbl,$col,$data=array())
	{
		$this->db->select_sum($col);
		$this->db->where($data);
		$query = $this->db->get($tbl);
		return $query->row()->$col;
	}

	public function covertOperation($from,$to)
	{
		$array = array(
			'posted_on >=' => $from,
			'posted_on <=' => $to,
			
		);
		$this->db->set('department_id',14);
		$this->db->where($array);
		$this->db->update('budget_opex');
	}

	public function companyBudgetCurr($cid,$yr,$type)
	{
		$array = array(
			'company_id' => $cid,
			'year' => $yr,
			'type' => $type
		);
		$this->db->select_sum('total_amount');
		$this->db->where($array);
		$query = $this->db->get('budget_summary');
		return $query->row()->total_amount;
	}

	public function companyBudgetPrev($cid,$yr,$type)
	{
		$array = array(
			'company_id' => $cid,
			'year' => $yr,
			'type' => $type
		);
		$this->db->select_sum('prev_budget');
		$this->db->where($array);
		$query = $this->db->get('budget_summary');
		return $query->row()->prev_budget;
	}

}
?>