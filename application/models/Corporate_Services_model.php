<?php
Class Corporate_Services_model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
	}
	function getBudgetOpex()
	{
		$this->db->select('*, ac.general_ledger_code code, ac.name account_name, d.name department, c.name company');
		$this->db->from('budget_opex bo');
		$this->db->join('ac_accounts ac','ac.id=bo.account_id','left');
		$this->db->join('departments d','d.id=bo.department_id','left');
		$this->db->join('companies c','c.id=bo.company_id','left');
		return $this->db->get()->result();
	}

	function getItemsById($tbl,$where){
	    $this->db->from($tbl);
	    $this->db->where($where);
	    return $this->db->get()->result();
	}

	function getEmpLeave($id='')
	{

        if($id != ''){
            $sql = $this->db->query("SELECT * FROM employee_leave
			WHERE employee_id = $id AND YEAR(date_from) = YEAR(CURDATE())
			ORDER BY date_filed DESC");
			return $sql->result();	
		}else{
		    return null;
		}
	}

	function getEmpLeaveFiltered($id='',$date,$status)
	{
	    if($id != ''){
	        $sql = $this->db->query("SELECT * FROM employee_leave
			WHERE employee_id = $id AND $date AND ($status) 
			ORDER BY date_filed DESC");
			return $sql->result();	
		}else{
		    return null;
		}
	}

	function getLeaveByApprover($id,$status,$date)
	{
		$sql = $this->db->query("SELECT * FROM employee_leave
			WHERE approver_id=$id AND $date AND ($status)
			ORDER BY date_from DESC");
		return $sql->result();
	}

	function getOvertimeByApprover($id,$status,$date)
	{
		$sql = $this->db->query("SELECT * FROM employee_overtime
			WHERE approver_id=$id AND $date AND ($status)
			ORDER BY render_date DESC");
		return $sql->result();
	}

	function getEmpOvertimeFiltered($id='',$date,$status)
	{
	    if($id != ''){
	        $sql = $this->db->query("SELECT * FROM employee_overtime
				WHERE employee_id = $id AND $date AND ($status) 
				ORDER BY date_filed DESC");
			return $sql->result();
		}else{
		    return null;
		}
	}

	function getUsedLeave($id)
	{
		$query = $this->db->query("SELECT count(*) as 'counts', sum(no_of_days) as 'total' from hr_leaves where leave_type != 'Absence' and year(date_from) = year(curdate()) and emp_id=$id");
		if($query->row()->counts != 0){
			return $query->row()->total;
		}else{
			return 0;
		}
	}

	function getLeaveBal($id)
	{
		if($id != 0){
			$used = $this->getUsedLeave($id);
			$query = $this->db->query("SELECT (leave_credits - $used) as 'balance' 
				FROM hr_employees WHERE id=$id");
			return $query->row()->balance;
		}else{
			return "N/A";
		}
	}

	function getEmpOvertime($id)
	{
		if($id != ''){
            $sql = $this->db->query("SELECT * FROM employee_overtime
			WHERE employee_id = $id AND YEAR(render_date) = YEAR(CURDATE())
			ORDER BY date_filed DESC");
			return $sql->result();	
		}else{
		    return null;
		}	
	}

}
