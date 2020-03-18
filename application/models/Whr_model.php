<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

##########################################################################
##########################################################################
#######################                         ##########################
#######################  AVEGA BROS INTEGRATED  ##########################
#######################  	it@avegabros.com 	##########################
#######################           -             ##########################
#######################    	  June 2016         ##########################
#######################           -             ##########################
#######################        wHR Model        ##########################
#######################                         ##########################
##########################################################################
##########################################################################


class Whr_model extends CI_Model{
	public function __construct() {
		// $this->load->database();
	}
	public function getAllEmployees($searchstring="", $limit="", $offset="", $order="", $sort="") {
		/*
		 *
		 * Creates a JSON array formatted to the bootstrap table
		 *
		 */
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='whr_employees' AND TABLE_SCHEMA='".DBNAME."'");
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
				$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' ";
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
				concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as full_name
			FROM whr_employees AS hre
			WHERE hre.stat=1
			$searchfields $order $offset $limit
		";
		$total	=	"
			SELECT
				hre.*
			FROM whr_employees AS hre
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
				$a['warehouse_name']	=	"-";
				$a['region_name']		=	"-";
				$a['department_name']	=	"-";
				$a['company_name']		=	"-";
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
						$vessel	=	$this->db->query("SELECT * FROM vessels WHERE id=".$a['vessel_id']);
						if($vessel!=false) {
							$vessel		=	$vessel->row();
							$all[$ctr]['vessel_name']	=	isset($vessel->name) ? $vessel->name : $a['vessel_id'];
							// get office based
							if($all[$ctr]['vessel_name'] == 99999) $all[$ctr]['vessel_name'] = "Makati Office Based";
							if($all[$ctr]['vessel_name'] == 99998) $all[$ctr]['vessel_name'] = "Cebu Office Based";
							if($all[$ctr]['vessel_name'] == 99997) $all[$ctr]['vessel_name'] = "Tacloban Office Based";
							if($all[$ctr]['vessel_name'] == 99996) $all[$ctr]['vessel_name'] = "Maintenance Crew";
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
				if(!empty($a['region'])) {
					$region	=	$this->db->query("SELECT * FROM whr_regions WHERE id=".$a['region']);
					if($region!=false) {
						$region	=	$region->row();
						$all[$ctr]['region_name']		=	isset($region->name) ? $region->name : $a['region'];
					}
				}
				if(!empty($a['warehouse'])) {
					$warehouse	=	$this->db->query("SELECT * FROM whr_warehouses WHERE id=".$a['warehouse']);
					if($warehouse!=false) {
						$warehouse	=	$warehouse->row();
						$all[$ctr]['warehouse_name']		=	isset($warehouse->name) ? $warehouse->name : $a['warehouse'];
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
				$access_leave	=	$this->Abas->checkPermissions("employee_profile|leave",false);
				$access_elf		=	$this->Abas->checkPermissions("employee_profile|elf",false);
				$access_salary	=	$this->Abas->checkPermissions("employee_profile|salary",false);
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

	public function getEmployeeReport($company='',$from_date='',$to_date='',$department='',$empstat='', $assignment='', $with_salary='') {
		if($with_salary != ''){
			$sql = "
				SELECT *, employee_id, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as fullname, e.employee_status, s.rate, p.name, d.name
				FROM `whr_employees` AS e
				  INNER JOIN salary_grades AS s ON e.salary_grade = s.id
				  INNER JOIN positions AS p ON e.position = p.id
				  INNER JOIN departments AS d ON e.department = d.id
				WHERE employee_status != 'Resigned' and employee_status != 'Terminated'
			";
		}else{
			$sql = "SELECT *, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as fullname
					FROM whr_employees as e
					WHERE 1=1";
		}
		if($company !=''){
			$sql .= " AND e.company_id =".$company;
		}
		if($from_date != '' || $to_date != ''){
			$sql .= " AND e.date_hired between '".$from_date."' AND '".$to_date."'";
		}
		if($department !=''){
			$sql .= " AND e.department =".$department;
		}
		if($empstat !=''){
			$sql .= " AND e.employee_status ='".$empstat."'";
		}
		if($assignment != ''){
			$sql .= " AND e.vessel_id =".$assignment;
		}
		$sql .= " ORDER BY e.last_name ASC, e.company_id, e.department ";

		$res = $this->db->query($sql);
		return $res->result_array();
	}
	public function getEmployeeReport1($company='',$from_date='',$to_date='',$department='',$empstat='', $assignment='', $with_salary='') {
		if($with_salary != ''){
			$sql = "
				SELECT *, employee_id, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as fullname, e.employee_status, s.rate, p.name, d.name
				FROM `whr_employees` AS e
				  INNER JOIN salary_grades AS s ON e.salary_grade = s.id
				  INNER JOIN positions AS p ON e.position = p.id
				  INNER JOIN departments AS d ON e.department = d.id
				WHERE employee_status != 'Resigned' and employee_status != 'Terminated'
			";
		}else{
			$sql = "SELECT *, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as fullname
					FROM whr_employees as e
					WHERE 1=1";
		}
		if($company !=''){
			$sql .= " AND e.company_id =".$company;
		}
		if($from_date != '' || $to_date != ''){
			$sql .= " AND e.date_hired between '".$from_date."' AND '".$to_date."'";
		}
		if($department !=''){
			$sql .= " AND e.department =".$department;
		}
		if($empstat !=''){
			$sql .= " AND e.employee_status ='".$empstat."'";
		}
		if($assignment != ''){
			if($assignment == 'Office'){
				$sql .= " AND e.vessel_id <= 99990";
			}elseif ($assignment == 'Vessel'){
				$sql .= " AND e.vessel_id > 99990";
			}
		}
		$sql .= " ORDER BY e.last_name ASC, e.company_id, e.department ";
		$res = $this->db->query($sql);
		return $res->result_array();
	}
	public function getEmployeeLoan($type='') {
		$sqls = "
			SELECT *, concat(e.last_name,', ',e.first_name,' ',LEFT(e.middle_name, 1),'.') as fullname, l.id as loan_id
			FROM `whr_loans` AS l
			INNER JOIN whr_employees AS e ON l.emp_id = e.id
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
			SELECT sum(amount) as total_payment FROM whr_loan_payments
			WHERE loan_id =".$loanId;
		$res = $this->db->query($sql);
		return $res->result_array();
	}
	public function getElfContribution($id = '') {
		$sql = "
			SELECT employee_id, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as fullname , total_elf_contribution, date_hired
			FROM `aabs_db`.`whr_employees` ORDER BY fullname ASC";
		$res = $this->db->query($sql);
		return $res->result_array();
	}
	public function getRegions($id = '') {
		$ret	=	null;
		$sql = "SELECT * FROM whr_regions WHERE stat=1 ORDER BY sorting ASC";
		if($id!=''){
			$sql = "SELECT * FROM whr_regions WHERE id =".$id;
		}
		$query	=	$this->db->query($sql);
		if($query) {
			$ret=	$query->result();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getWarehouses($id = '') {
		$ret	=	null;
		$sql = "SELECT * FROM whr_warehouses WHERE stat=1 ORDER BY sorting ASC";
		if($id!=''){
			$sql = "SELECT * FROM whr_warehouses WHERE id =".$id;
		}
		$query	=	$this->db->query($sql);
		if($query) {
			$ret=	$query->result();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}


}
?>
