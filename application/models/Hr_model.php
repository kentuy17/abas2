<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

##########################################################################
##########################################################################
#######################                         ##########################
#######################  AVEGA BROS INTEGRATED  ##########################
#######################     it@avegabros.com    ##########################
#######################           -             ##########################
#######################    October 2015         ##########################
#######################           -             ##########################
#######################         HR Model        ##########################
#######################                         ##########################
##########################################################################
##########################################################################


class Hr_model extends CI_Model{
	public function __construct() {
		// $this->load->database();
	}
	public function getAllEmployees($searchstring="", $limit="", $offset="", $order="", $sort="") {
		/*
		 *
		 * Creates a JSON array formatted to the bootstrap table
		 *
		 */
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='hr_employees' AND TABLE_SCHEMA='".DBNAME."'");
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
				if($sort=="company_name") $sort="company_id";
				if($sort=="full_name") $sort="last_name";
				$order	=	"ORDER BY ".($sort!=""?"hre.".$sort:"hre.last_name")." ".$order;
			}
		}
		$searchfields	=	"";
		if($searchstring!="") {
			$searchfields	=	"";
			foreach($tablefields as $tf) {
				if($searchfields=="")  {
					$searchfields.="AND ";
				}
				else {
				$searchfields.="OR ";
				}
				$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' AND stat=1 ";
			}
		}
		// $sql	=	"
			// SELECT
				// hre.*,
				// p.id AS position_id,
				// p.name AS position_name,
				// CONCAT(first_name , ' ' , middle_name , ' ' , last_name) AS full_name,
				// d.id AS department_id,
				// d.name AS department_name
			// FROM hr_employees AS hre
			// JOIN positions AS p
				// ON p.id=hre.position
			// JOIN departments AS d
				// ON d.id=hre.department
			// WHERE hre.stat=1
			// $searchfields $order $offset $limit
		// ";
		// $total	=	"
			// SELECT
				// hre.*,
				// p.id AS position_id,
				// p.name AS position_name,
				// CONCAT(first_name , ' ' , middle_name , ' ' , last_name) AS full_name,
				// d.id AS department_id,
				// d.name AS department_name
			// FROM hr_employees AS hre
			// JOIN positions AS p
				// ON p.id=hre.position
			// JOIN departments AS d
				// ON d.id=hre.department
			// WHERE hre.stat=1
			// $searchfields
		// ";
		$sql	=	"
			SELECT
				hre.*,
				concat(last_name,', ',first_name,' ', LEFT(middle_name, 1)) as full_name
			FROM hr_employees AS hre
			WHERE hre.stat=1
			$searchfields $order $offset $limit
		";
		$total	=	"
			SELECT
				hre.*
			FROM hr_employees AS hre
			WHERE hre.stat=1
			$searchfields
		";

		$all	=	$this->db->query($sql);
		$total	=	$this->db->query($total);
		$all	=	$all->result_array();

		if(!empty($all)) {
			foreach($all as $ctr=>$a) {
				$a['vessel_name']		=	"-";
				$a['position_name']		=	"-";
				$a['department_name']	=	"-";
				$a['company_name']		=	"-";
				$a['date_regularized']	=	"-";
				$a['employment_length']	=	"-";
				if(!empty($a['company_id'])) {
					$company	=	$this->db->query("SELECT * FROM companies WHERE id=".$a['company_id']);
					if($company!=false) {
						$company		=	$company->row();
						$all[$ctr]['company_name']	=	isset($company->name) ? $company->name : $a['company_id'];
					}
				}
				if(!empty($a['birth_date'])) {
					$all[$ctr]['birth_date']	=	($a['birth_date']=="0000-00-00 00:00:00") ? "" : date("j F Y", strtotime($a['birth_date']));
				}
				if(!empty($a['date_hired'])) {
					$all[$ctr]['date_hired']	=	($a['date_hired']=="0000-00-00 00:00:00") ? "" : date("j F Y", strtotime($a['date_hired']));
				}
				if(!empty($a['vessel_id'])) {
					if($a['vessel_id']==0) {
						$vessel	=	"N/A";
					}
					else {
						$vessel	=	$this->Abas->getVessel($a['vessel_id']);
						if(!empty($vessel)) {
							$all[$ctr]['vessel_name']	=	isset($vessel->name) ? $vessel->name : $a['vessel_id'];
						}
					}
				}
				if(!empty($a['position'])) {
					$position	=	$this->db->query("SELECT * FROM positions WHERE id=".$a['position']);
					if($position!=false) {
						$position	=	$position->row();
						$all[$ctr]['position_name']		=	isset($position->name) ? $position->name : $a['position'];
					}
				}
				if(!empty($a['department'])) {
					$dept		=	$this->db->query("SELECT * FROM departments WHERE id=".$a['department']);
					if($dept!=false) {
						$dept		=	$dept->row();
						$all[$ctr]['department_name']	=	isset($dept->name) ? $dept->name : $a['department'];
					}
				}
				if(!empty($a['salary_grade'])) {
					$sg		=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$a['salary_grade']);
					if($sg!=false) {
						$sg		=	$sg->row();
						$all[$ctr]['salary_grade_id']	=	isset($sg->id) ? $sg->id : $a['salary_grade'];
						$all[$ctr]['salary_grade']	=	isset($sg->grade) ? $sg->grade : $a['salary_grade'];
						$all[$ctr]['salary_rate']	=	isset($sg->rate) ? $sg->rate : $a['salary_grade'];
					}
				}

				$regularization	=	$this->db->query("SELECT * FROM hr_employment_history WHERE employee_id=".$a['id']." AND (value_changed='Employee Status' OR value_changed='Employment Status') AND to_val='Regular' ORDER BY effectivity_date DESC LIMIT 1");
				if($regularization) {
					if($regularization=$regularization->row()) {
						
						$all[$ctr]['date_regularized']	=	date("j F Y",strtotime($regularization->effectivity_date));
					}
				}

				$employment_status		=	$this->getLatestEmploymentStatus($a['id']);
				if($employment_status){
					//if($employment_status->to_val=='Inactive'){
					//	$all[$ctr]['employment_status'] = "On Leave";
					//}else{
						$employment_status = $employment_status->to_val;

					//}
				}else{
					$employment_status = "(Not set)";
				}

				$all[$ctr]['employment_status'] = $employment_status;

				//if($a['employee_status']<>'Resigned' && $a['employee_status']<>'Retired' && $a['employee_status']<>'AWOL' && $a['employee_status']<>'Terminated' && $a['employee_status']<>'On-leave'){
				if($employment_status<>'Resigned' && $employment_status<>'Retired' && $employment_status<>'Terminated' &&  $employment_status<>'Separated' && $employment_status<>'(Not set)'){
					$all[$ctr]['employee_status'] = "Active";
				}else{
					$all[$ctr]['employee_status'] = "Inactive";
					$separation	=	$this->db->query("SELECT * FROM hr_employment_history WHERE employee_id=".$a['id']." AND (to_val='Resigned' OR to_val='Retired' OR to_val='Terminated' OR to_val='Separated') ORDER BY effectivity_date DESC LIMIT 1");
					if($separation) {
						if($separation=$separation->row()) {
							$all[$ctr]['date_separated']	=	date("j F Y",strtotime($separation->effectivity_date));
						}
					}
				}

				$access_leave	=	$this->Abas->checkPermissions("human_resources|leave",false);
				$access_elf		=	$this->Abas->checkPermissions("human_resources|elf",false);
				$access_salary	=	$this->Abas->checkPermissions("human_resources|salary_viewing",false);
				if($a['middle_name']!="") { // adds '.' if middle name exists
					$all[$ctr]['full_name']	.=	".";
				}
				if($access_leave==false) {$all[$ctr]['leave_credits']=null;}
				if($access_elf==false) {$all[$ctr]['elf_rate']=null;}
				if($access_salary==false) {$all[$ctr]['salary_grade']=null;$all[$ctr]['salary_grade_id']=null;$all[$ctr]['salary_rate']=null;}
			}
			$data	=	array("total"=>count($total->result_array()),"rows"=>$all); // creates array accdg to bootstrap tables
		}
		else {
			$data	=	false;
		}
		return $data;
	}
	public function getEmployeeLoan($type='') {
		$sqls = "
			SELECT *, concat(e.last_name,', ',e.first_name,' ',LEFT(e.middle_name, 1),'.') as fullname, l.id as loan_id
			FROM `hr_loans` AS l
			INNER JOIN hr_employees AS e ON l.emp_id = e.id
			WHERE 1=1
		";
		if($type != ''){
			$sqls .= " AND loan_type ='".$type."'";
		}
		$sqls .= " ORDER BY  loan_type, fullname ASC, date_loan";
		$res1 = $this->db->query($sqls);
		return $res1->result_array();
	}
	public function getEmployeeTotalPayment($loanId = '') {
		$sql = "
			SELECT sum(amount) as total_payment FROM hr_loan_payments
			WHERE loan_id =".$loanId;
		$res = $this->db->query($sql);
		return $res->result_array();
	}
	public function getElfContribution($id = '') {
		$sql = "
			SELECT employee_id, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as fullname , total_elf_contribution, date_hired
			FROM `aabs_db`.`hr_employees` ORDER BY fullname ASC";
		$res = $this->db->query($sql);
		return $res->result_array();
	}
	public function getLatestEmploymentStatus($emp_id){
		$sql = "SELECT * FROM hr_employment_history WHERE employee_id=".$emp_id." AND (value_changed='Employee Status' OR value_changed='Employment Status') ORDER BY effectivity_date DESC LIMIT 1;";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getUnusedLeaveCredits($emp_id,$year){
		
		$unused_leaves = 0;
		$used_leaves =0;
		
		$emp = $this->Abas->getEmployee($emp_id);
		$leave_credits = $emp['leave_credits'];
		
		$sql1 = "SELECT * FROM hr_employment_history WHERE to_val='Regular' AND employee_id=".$emp_id;
		$query1 = $this->db->query($sql1);
		if($query1){
			$res = $query1->row();
			if(isset($res)){
				$effective_year = date('Y',strtotime($res->effectivity_date));
				if($effective_year<=$year){

					$sql2 = "SELECT * FROM hr_leaves WHERE emp_id=".$emp_id." AND leave_type<>'Absence' AND YEAR(date_from)='".$year."' AND stat=1";
					$query2 = $this->db->query($sql2);
					
					if($query2){
						$result = $query2->result();
						foreach($result as $row){
							$used_leaves = $used_leaves + $row->no_of_days;
						}
						$unused_leaves = $leave_credits - $used_leaves;
					}else{
						$unused_leaves = 0;
					}
				}
			}
		}
		
		return $unused_leaves;
	}
	public function getEmployeeLeaves($emp_id,$year){
		$sql = "SELECT * FROM hr_leaves WHERE emp_id=".$emp_id." AND stat=1 ORDER BY date_from DESC";
		$query	=	$this->db->query($sql);
		if($query){
			$leaves = $query->result_array();
			$num_leaves = 0;
			$num_absences = 0;
			foreach($leaves as $l) {
				$leaveType			=	$l['leave_type'];
				$leaveDateYear		=	date("Y",strtotime($l['date_from']));
				$no_of_days			=	$l['no_of_days'];
				if($leaveType!="Absence"  && $leaveDateYear==$year){
					$num_leaves = $num_leaves + $no_of_days;	
				}
				if($leaveType=="Absence" && $leaveDateYear==$year){
					$num_absences = $num_absences + 1;
				}
			}
			$leaves['number_of_filed'] = $num_leaves;
			$leaves['number_of_absences'] =$num_absences;
		}else{
			$leaves = null;
		}
		return $leaves;
	}
	public function getEmployeeForAWOL(){
		$date_now = date('Y-m-d');
		$sql = "SELECT hr_employees.*,hr_employment_history.to_date FROM hr_employment_history INNER JOIN hr_employees ON hr_employees.id=hr_employment_history.employee_id WHERE hr_employees.employee_status='On-leave' AND hr_employment_history.value_changed='Employment Status' AND hr_employment_history.to_val='On-leave' AND hr_employment_history.to_date<='".$date_now."'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getEmployeeBonus($release_date,$type,$status=""){
		if($status=="Approved"){
			$sql = "SELECT * FROM hr_bonus WHERE stat=1 AND release_date='".$release_date."' AND type='".$type."' AND approved_by<>0";
		}else{
			$sql = "SELECT * FROM hr_bonus WHERE stat=1 AND release_date='".$release_date."' AND type='".$type."'";
		}
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getSection($id){
		$this->db->from('department_sections');
		$this->db->where('department_id',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function getSubSection($id){
		$this->db->from('department_sub_sections');
		$this->db->where('section_id',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function getSectionById($id=''){
		$this->db->select('ds.name department_name');
		$this->db->from('hr_employees he');
		$this->db->join('department_sections ds','ds.id=he.section_id','left');
		$this->db->where('he.id',$id);
		$query = $this->db->get();
		if($id == '' or $id == 0){
			return '-';
		}else{
			return $query->row()->department_name;
		}
	}

	public function getSubSectionById($id=''){
		$this->db->select('ds.name section_name');
		$this->db->from('hr_employees he');
		$this->db->join('department_sub_sections ds','ds.id=he.sub_section_id','left');
		$this->db->where('he.id',$id);
		$query = $this->db->get();
		if($id == '' or $id == 0){
			return '-';
		}else{
			return $query->row()->section_name;
		}
	}

	public function getDivisionById($id=''){
		$this->db->select('d.name division_name');
		$this->db->from('hr_employees he');
		$this->db->join('divisions d','d.id=he.division_id','left');
		$this->db->where('he.id',$id);
		$query = $this->db->get();
		if($id == '' or $id == 0){
			return null;
		}else{
			return $query->row()->division_name;
		}
	}

	public function getSectionsByDepartment($department_id){
		if($department_id!=''){
			$sql = "SELECT * FROM department_sections WHERE department_id=".$department_id;
			$query = $this->db->query($sql);
			if($query){
				$result = $query->result();
			}else{
				$result = NULL;
			}
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getSubsectionsBySection($section_id){
		if($section_id != ''){
			$sql = $this->db->query("SELECT * FROM department_sub_sections WHERE section_id=$section_id");
			return $sql->result();
		}else{
			return null;
		}
	}

	public function crewHaveEntry($id)
	{
		$this->db->from('hr_crew_movements');
		$this->db->where('employee_id',$id);
		$query = $this->db->get();
		if($query->num_rows()){
			return true;
		}else{
			return false;
		}
	}

	public function getCrewCount($id)
	{
		$sql = $this->db->query("SELECT count(*) as item_count FROM hr_employees WHERE stat=1 AND vessel_id=$id
			AND NOT (employee_status = 'Resigned' or employee_status = 'Retired')");
		return $sql->row()->item_count;
	}


	public function getVesselCrew($id)
	{
		$sql = $this->db->query("SELECT * FROM hr_employees WHERE stat=1 AND vessel_id=$id
			AND NOT (employee_status = 'Resigned' or employee_status = 'Retired')");
		return $sql->result();
	}

	public function getPositionCount($vessel,$position)
	{
		$sql = $this->db->query("SELECT count(*) as item_count FROM hr_employees where stat=1
			AND vessel_id=$vessel AND position=$position 
			AND NOT (employee_status = 'Resigned' or employee_status = 'Retired')");
		return $sql->row()->item_count;
	}

	public function getEmployeeCount()
	{
		$sql = $this->db->query("SELECT count(*) as item_count FROM hr_employees WHERE stat=1
			AND NOT (employee_status = 'Resigned' OR employee_status = 'Retired' OR employee_status = 'Terminated' OR employee_status = 'Separated')");
		return $sql->row()->item_count;
	}

	public function getEmployeeForTransfer()
	{
		$sql = $this->db->query("
		SELECT * FROM hr_crew_movements
 			WHERE id IN (
               SELECT max(id) 
                 FROM hr_crew_movements 
                 WHERE stat=1
                GROUP BY employee_id
             );");
		return $sql->result();
	}

	public function getEmpForTransCount()
	{
		$sql = $this->db->query("
		SELECT count(*) as row_count FROM hr_crew_movements
 			WHERE id IN (
               SELECT max(id) 
                 FROM hr_crew_movements 
                 WHERE stat=1
                GROUP BY employee_id
             );");
		return $sql->row()->row_count;
	}

	public function getLeaveForApprovalCount()
	{
		$sql = $this->db->query("SELECT count(*) row_count FROM employee_leave
			WHERE status='FOR PROCESSING'");
		return $sql->row()->row_count; 
	}

	public function getOvertimeForApprovalCount()
	{
		$sql = $this->db->query("SELECT count(*) row_count FROM employee_overtime
			WHERE status='FOR PROCESSING'");
		return $sql->row()->row_count; 
	}

	public function getLeaveForApproval()
	{
		$sql = $this->db->query("SELECT * FROM employee_leave
			WHERE status='FOR PROCESSING'");
		return $sql->result();
	}

	function getAllLeaves()
	{
		$sql = $this->db->query("SELECT * FROM employee_leave
			WHERE status='FOR PROCESSING' or status='PROCESSED' or status='REJECTED'
			ORDER BY date_filed desc");
		return $sql->result();
	}

	function getLeaveProcessed()
	{
		$sql = $this->db->query("SELECT * FROM employee_leave
			WHERE status='PROCESSED'
			ORDER BY date_filed desc");
		return $sql->result();
	}

	function getLeaveRejected()
	{
		$sql = $this->db->query("SELECT * FROM employee_leave
			WHERE status='REJECTED'
			ORDER BY date_filed desc");
		return $sql->result();
	}

	function getEmpFullName($id)
	{
		$sql = $this->db->query("SELECT 
			concat(last_name,', ',first_name,' ',middle_name,' (',$id,')') as 'emp_name'
			FROM hr_employees WHERE id=$id");
		return $sql->row()->emp_name;
	}

	function getOvertimeType($rate)
	{
		$sql = $this->db->query("SELECT * FROM hr_overtime_rate
			WHERE rate=$rate and status=1");
		return $sql->row()->type;
	}

	function getAllOvertime()
	{
		$sql = $this->db->query("SELECT * FROM employee_overtime
			WHERE status='FOR PROCESSING' or status='PROCESSED' or status='REJECTED'
			ORDER BY date_filed DESC");
		return $sql->result();
	}

	function getOvertime($status)
	{
		$sql = $this->db->query("SELECT * FROM employee_overtime WHERE status='".$status."'
			ORDER BY date_filed DESC");
		return $sql->result();
	}
}
?>
