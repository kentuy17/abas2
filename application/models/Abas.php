<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

##########################################################################
##########################################################################
#######################                         ##########################
#######################  AVEGA BROS INTEGRATED  ##########################
#######################     it@avegabros.com    ##########################
#######################           -             ##########################
#######################       October 2015      ##########################
#######################           -             ##########################
#######################       Global Model      ##########################
#######################                         ##########################
#######################                         ##########################
#######################   Adding an office:     ##########################
#######################   see functions         ##########################
#######################    getVessels           ##########################
#######################    getVessel            ##########################
#######################    getOffices           ##########################
#######################    getVesselsByCompany  ##########################
#######################    getEmployee          ##########################
#######################    getWHEmployee        ##########################
#######################                         ##########################
##########################################################################
##########################################################################

class Abas extends CI_Model{
	public function __construct() {
		// $this->load->database();
	}
	public function createBSTable($table, $searchstring="", $limit="", $offset="", $order="", $sort="") {
		/*
		 *
		 * Creates a JSON array formatted to the bootstrap table
		 *
		 */
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
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
				$order	=	"ORDER BY ".($sort!=""?$sort:"id")." ".$order;
			}
		}
		if($searchstring!="") {
			$searchfields	=	"";
			foreach($tablefields as $tf) {
				if($searchfields!="") $searchfields.="OR ";
				$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' ";
			}
		}
		else {
			$searchfields	=	"1=1 ";
		}
		$sql	=	"SELECT * FROM ".$table." WHERE $searchfields $order $offset $limit";
		$total	=	"SELECT id FROM ".$table." WHERE $searchfields";
		$all	=	$this->db->query($sql);
		$total	=	$this->db->query($total);
		if($all) {
			$data	=	array("total"=>count($total->result_array()),"rows"=>$all->result_array());
		}
		else {
			$data	=	false;
		}

		return $data;
	}
	public function getData($table) {
		$ret				=	array("columns"=>array(),"content"=>array());
		$column_query		=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
		if($column_query) {
			if($column_query->row()) {
				$columns		=	$column_query->result();
				$ret['columns']	=	$columns;
				if($id=='') {
					$data_query		=	$this->db->query("SELECT * FROM ".$table);
					if($data_query) {
						if($data_query->row()) {
							$data			=	$data_query->result_array();
							$ret['content']	=	$data;
						}
					}
				}
			}
		}
		return $ret;
	}
	public function getDataForBSTable($table,  $searchstring='', $limit='', $offset='', $order='', $sort='',$where='',$filter='',$innerjoin=''){
		$wfilter ="";
		$inner_join ="";
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
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
				$order	=	"ORDER BY ".($sort!=""?$sort:"id")." ".$order;
			}
		}
		if($where!="") {
			$wfilter	.=	"AND ".$where.$filter;
		}
		if($searchstring!="") {
			$searchfields	=	"";
			foreach($tablefields as $tf) {
				if($searchfields!="") $searchfields.="OR ";
				$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' ";
			}
		}
		else {
			$searchfields	=	"1=1 ";
		}
		if($innerjoin!=""){
			$inner_join=$innerjoin;
		}
		$sql	=	"SELECT * FROM ".$table." ".$inner_join." WHERE ($searchfields) $wfilter $order $offset $limit";
		$total	=	"SELECT ".$table.".id FROM ".$table." ".$inner_join." WHERE ($searchfields) $wfilter";
		$all	=	$this->db->query($sql);
		$total	=	$this->db->query($total);
		if($all) {
			$data	=	array("total"=>count($total->result_array()),"rows"=>$all->result_array());
		}
		else {
			$data	=	false;
		}

		return $data;
	}
	public function getClients() {
		$ret	=	null;
		$query	=	$this->db->query("SELECT * FROM clients WHERE stat=1 ORDER BY company ASC");
		if($query) {
			$ret=	$query->result_array();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getClient($id="") {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM clients WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
				}
				else {
					$ret=	false;
				}
			}
			else {
				$ret=	false;
			}
		}
		return $ret;
	}
	public function getServiceprovider($id=''){

		if($id!=''){
			$sql = "SELECT * FROM service_providers WHERE id =".$id;
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
	}
	public function getServiceproviders() {
		$ret		=	null;
			$services	=	$this->db->query("SELECT * FROM service_providers");

		if($services) {
			if($services->row()) {
				$ret	=	$services->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	public function getBank($id=''){
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM ac_banks WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
				}
				else {
					$ret=	false;
				}
			}
			else {
				$ret=	false;
			}
		}
		return $ret;
	}
	public function getBanks() {
		$ret		=	null;
			$banks	=	$this->db->query("SELECT * FROM ac_banks");

		if($banks) {
			if($banks->row()) {
				$ret	=	$banks->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	public function getTrucks() {
		$ret		=	null;
			$trucks	=	$this->db->query("SELECT * FROM trucks");

		if($trucks) {
			if($trucks->row()) {
				$ret	=	$trucks->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	public function getTruck($id=''){

		if($id!=''){
			$sql = "SELECT * FROM trucks WHERE id =".$id;
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
	}
	public function getCranes() {
		$ret		=	null;
			$cranes	=	$this->db->query("SELECT * FROM cranes");

		if($cranes) {
			if($cranes->row()) {
				$ret	=	$cranes->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	public function getCrane($id=''){

		if($id!=''){
			$sql = "SELECT * FROM cranes WHERE id =".$id;
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
	}
	public function getCompanies($include_duplicates=false) {
		/*
		 * The include_duplicates boolean determines whether to display Avega Integrated (Staff)
		 * with the company ID 10.
		 *
		 * This was easier to implement compared to returning to each and every function call of
		 * getCompanies and removing the duplicate entry for Integrated.
		 *
		 * The 'Staff' company is used by the Payroll Department and may possibly be the
		 * 'Shared Service' company in the future.
		 */
		$ret	=	null;
		$sql = "SELECT * FROM companies WHERE stat=1 ".($include_duplicates ? "":" AND id<>10")." ORDER BY name ASC";
		$query	=	$this->db->query($sql);
		if($query) {
			$ret=	$query->result();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getCompany($id="") {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM companies WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	$query->row();
				}
				else {
					$ret=	false;
				}
			}
			else {
				$ret=	false;
			}
		}
		return $ret;
	}
	public function getRequestPayments() {
		$ret	=	null;
		$query	=	$this->db->query("SELECT * FROM ac_request_payment");
		if($query) {
			$ret=	$query->result_array();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getRequestPayment($id="") {
		$ret	=	null;
		$query	=	$this->db->query("SELECT * FROM ac_request_payment WHERE id=".$id);
		if($query!=false) {
			if($query->row()) {
				$ret=	(array)$query->row();
			}
			else {
				$ret=	false;
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getUsers() {
		$ret	=	null;
		$sql = "SELECT * FROM users WHERE stat=1 ORDER BY last_name ASC";
		$query	=	$this->db->query($sql);
		if($query) {
			$ret=	$query->result();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getUser($id="") {
		$ret	=	null;
		// if($id=="" && isset($_SESSION['abas_login']['userid'])) { $id=$_SESSION['abas_login']['userid']; } // gets id from current session if not set
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM users WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
					$ret['full_name']	=	$ret['last_name'].", ".$ret['first_name']." ".$ret['middle_name'];
				}
			}
		}
		return $ret;
	}
	public function getTaxCodes() {
		$ret	=	null;
		$query	=	$this->db->query("SELECT DISTINCT(tax_code) FROM tax_codes WHERE stat=1");
		if($query) {
			$ret=	$query->result();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getSalaryGrades() {
		$ret	=	null;
		$query	=	$this->db->query("SELECT * FROM salary_grades WHERE stat=1");
		if($query) {
			$ret=	$query->result();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getSalaryGrade($id=""){
		$ret	=	null;
		$query	=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$id);
		if($query) {
			$ret=	$query->row();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getVessels($include_offices=true) {
		$ret	=	null;
		$query	=	$this->db->query("SELECT id, name, company FROM vessels WHERE status='Active' ORDER BY name ASC");
		if($query) {
			$ret=	$query->result();
			if($include_offices==true) {
				$ret[count($ret)+1]	=	(object)array('id'=>99999, 'name'=>'MAKATI OFFICE (ABISC)', 'company'=>1, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99984, 'name'=>'MAKATI OFFICE (ABMCI)', 'company'=>2, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99985, 'name'=>'MAKATI OFFICE (LMVC)', 'company'=>4, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99986, 'name'=>'MAKATI OFFICE (PCTSC)', 'company'=>9, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99987, 'name'=>'MAKATI OFFICE (SVSC)', 'company'=>3, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99988, 'name'=>'MAKATI OFFICE (VISC)', 'company'=>8, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99998, 'name'=>'CEBU OFFICE (ABISC)', 'company'=>1, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99995, 'name'=>'TAYUD OFFICE (ABISC)', 'company'=>1, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99992, 'name'=>'TAYUD OFFICE (LMVC)', 'company'=>4, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99983, 'name'=>'TAYUD OFFICE (ABMCI)', 'company'=>2, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99982, 'name'=>'TAYUD OFFICE (SVSC)', 'company'=>3, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99981, 'name'=>'TAYUD OFFICE (VISC)', 'company'=>8, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99991, 'name'=>'TAYUD OFFICE (TSI)', 'company'=>11, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99997, 'name'=>'TACLOBAN OFFICE (ABISC)', 'company'=>1, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99990, 'name'=>'CRANE', 'company'=>1, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99996, 'name'=>'MAINTENANCE', 'company'=>1, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99993, 'name'=>'MACHINE SHOP', 'company'=>1, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99994, 'name'=>'MOTORPOOL (TRUCKING)', 'company'=>5, 'bank_account_name'=>'', 'bank_account_num'=>'');
				$ret[count($ret)+1]	=	(object)array('id'=>99989, 'name'=>'IMPORTATION (PCTSC)', 'company'=>9, 'bank_account_name'=>'', 'bank_account_num'=>'');
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getPorts() {
		$ret	=	null;

		$query	=	$this->db->query("SELECT distinct(from_port) FROM ops_port_distance WHERE stat=1 ORDER BY from_port ASC");

		$ret =	$query->result();


		return $ret;

	}
	public function getPort($id="") {
		$ret	=	null;

		if($id !=''){
			$query	=	$this->db->query("SELECT distinct(from_port) FROM ops_port_distance WHERE stat=1 AND id = $id");
			$ret =	$query->result();
		}

		return $ret;

	}
	public function getWarehouses() {
		$ret		=	null;
		$warehouses	=	$this->db->query("SELECT * FROM whr_warehouses");
		if($warehouses) {
			if($warehouses->row()) {
				$ret=	$warehouses->result_array();
			}
			else { $ret	=	false; }
		}
		else { $ret	=	false; }
		return $ret;
	}
	public function getWarehouse($id="") {
		$ret	=	null;

		if($id !=''){
			$query	=	$this->db->query("SELECT * FROM whr_warehouses WHERE id = $id");
			$ret =	$query->result();
		}

		return $ret;

	}
	public function getRegions() {
		$ret	=	null;

		$query	=	$this->db->query("SELECT * FROM whr_regions WHERE stat=1 ORDER BY name ASC");

		$ret =	$query->result();


		return $ret;

	}
	public function getRegion($id="") {
		$ret	=	null;

		if($id !=''){
			$query	=	$this->db->query("SELECT * FROM whr_regions WHERE id = $id");
			$ret =	$query->result();
		}

		return $ret;

	}
	public function getSupplier($id=''){
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM suppliers WHERE id= $id");
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
				}
				else {
					$ret=	false;
				}
			}
			else {
				$ret=	false;
			}
		}
		return $ret;
	}
	public function getSuppliers() {
		$ret		=	null;
		$supplier	=	$this->db->query("SELECT * FROM suppliers");
		if($supplier) {
			if($supplier->row()) {
				$ret	=	$supplier->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	public function getOffices($include_vessels=true) {
		$ret	=	null;
		$query	=	$this->db->query("SELECT id, name FROM vessels WHERE status='Active' ORDER BY name ASC");
		if($query) {
			if($include_vessels==true) {
				$ret=	$query->result();
			}
			$ret[count($ret)+1]	=	(object)array('id'=>99999, 'name'=>'Makati Office', 'company'=>1);
			$ret[count($ret)+1]	=	(object)array('id'=>99998, 'name'=>'Cebu Office', 'company'=>1);
			$ret[count($ret)+1]	=	(object)array('id'=>99997, 'name'=>'Tacloban Office', 'company'=>1);
			$ret[count($ret)+1]	=	(object)array('id'=>99996, 'name'=>'Maintenance Crew', 'company'=>1);
			$ret[count($ret)+1]	=	(object)array('id'=>99995, 'name'=>'Tayud Office', 'company'=>1);
			$ret[count($ret)+1]	=	(object)array('id'=>99994, 'name'=>'Avega Trucking', 'company'=>5);
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getVesselsByCompany($company_id,$include_offices=false) {
		$ret	=	null;
		$query	=	$this->db->query("SELECT id, name FROM vessels WHERE status='Active' AND company=".$company_id." ORDER BY name ASC");
		if($query) { // Offices are only for Avega Bros Integrated
			$ret=	$query->result();
			if($include_offices==true) {
				if($company_id==1){
					$ret[count($ret)+1]	=	(object)array('id'=>99999, 'name'=>'MAKATI OFFICE (ABISC)');
					$ret[count($ret)+1]	=	(object)array('id'=>99998, 'name'=>'CEBU OFFICE (ABISC)');
					$ret[count($ret)+1]	=	(object)array('id'=>99995, 'name'=>'TAYUD OFFICE (ABISC)');
					$ret[count($ret)+1]	=	(object)array('id'=>99997, 'name'=>'TACLOBAN OFFICE (ABISC)');
					$ret[count($ret)+1]	=	(object)array('id'=>99990, 'name'=>'CRANE', 'company'=>1);
					$ret[count($ret)+1]	=	(object)array('id'=>99996, 'name'=>'MAINTENANCE');
					$ret[count($ret)+1]	=	(object)array('id'=>99993, 'name'=>'MACHINE SHOP');
				}elseif($company_id==2){
						$ret[count($ret)+1]	=	(object)array('id'=>99984, 'name'=>'MAKATI OFFICE (ABMCI)');
						$ret[count($ret)+1]	=	(object)array('id'=>99983, 'name'=>'TAYUD OFFICE (ABMCI)');
				}elseif($company_id==3){
					$ret[count($ret)+1]	=	(object)array('id'=>99987, 'name'=>'MAKATI OFFICE (SVSC)');
					$ret[count($ret)+1]	=	(object)array('id'=>99982, 'name'=>'TAYUD OFFICE (SVSC)');
				}elseif($company_id==4){
					$ret[count($ret)+1]	=	(object)array('id'=>99985, 'name'=>'MAKATI OFFICE (LMVC)');
					$ret[count($ret)+1]	=	(object)array('id'=>99992, 'name'=>'TAYUD OFFICE (LMVC)');
				}elseif($company_id==5){
					$ret[count($ret)+1]	=	(object)array('id'=>99994, 'name'=>'MOTORPOOL (TRUCKING)');
				}elseif($company_id==8){
					$ret[count($ret)+1]	=	(object)array('id'=>99988, 'name'=>'MAKATI OFFICE (VISC)');
					$ret[count($ret)+1]	=	(object)array('id'=>99981, 'name'=>'TAYUD OFFICE (VISC)');
				}elseif($company_id==9){
					$ret[count($ret)+1]	=	(object)array('id'=>99986, 'name'=>'MAKATI OFFICE (PCTSC)');
					$ret[count($ret)+1]	=	(object)array('id'=>99989, 'name'=>'IMPORTATION (PCTSC)');
				}elseif($company_id==11){
					$ret[count($ret)+1]	=	(object)array('id'=>99991, 'name'=>'TAYUD OFFICE (TSI)');
				}
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getTrucksByCompany($company_id) {
		$ret	=	null;
		$query	=	$this->db->query("SELECT * FROM trucks WHERE stat=1 AND company=".$company_id." ORDER BY plate_number ASC");
		if($query) {
			$ret =	$query->result();
		}
		else {
			$ret =	false;
		}
		return $ret;
	}
	public function getEmployees(){
		$sql = "SELECT * FROM hr_employees ORDER by last_name";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getEmployee($id="") {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , LEFT(middle_name, 1)) AS full_name FROM hr_employees AS hre WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	$query->row();

					//* details here
					$a	=	$query->result_array();
					$a	=	$a[0];
					$company=$position=$div=$dept=$sec=$subsec=$salary=$vessel=$employee_status=$date_regularized=false;
					if($a['vessel_id']!="") {
						$vessel	=	$this->Abas->getVessel($a['vessel_id']);
					}
					if($a['company_id']!="") {
						$company	=	$this->db->query("SELECT * FROM companies WHERE id=".$a['company_id']);
					}
					if($a['position']!="") {
						$position	=	$this->db->query("SELECT * FROM positions WHERE id=".$a['position']);
					}
					if($a['division_id']!="") {
						$div		=	$this->db->query("SELECT * FROM divisions WHERE id=".$a['division_id']);
					}
					if($a['department']!="") {
						$dept		=	$this->db->query("SELECT * FROM departments WHERE id=".$a['department']);
					}
					if($a['section_id']!="" || $a['section_id']<>0) {
						$sec		=	$this->db->query("SELECT * FROM department_sections WHERE id=".$a['section_id']);
					}
					if($a['sub_section_id']!="") {
						$subsec		=	$this->db->query("SELECT * FROM department_sub_sections WHERE id=".$a['sub_section_id']);
					}
					if($a['salary_grade']!="") {
						$salary		=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$a['salary_grade']);
					}
					$employee_status		=	$this->db->query("SELECT * FROM hr_employment_history WHERE employee_id=".$a['id']." AND ( value_changed='Employee Status' OR value_changed='Employment Status') ORDER BY id DESC LIMIT 1");
					$regularization			=	$this->db->query("SELECT * FROM hr_employment_history WHERE employee_id=".$a['id']." AND (value_changed='Employee Status' OR value_changed='Employment Status') AND to_val='Regular' ORDER BY id DESC LIMIT 1");
					$separation = $this->db->query("SELECT * FROM hr_employment_history WHERE employee_id=".$a['id']." AND (to_val='Resigned' OR to_val='Retired' OR to_val='Terminated' OR to_val='Separated') ORDER BY effectivity_date DESC LIMIT 1");
					if($vessel) {
						$a['vessel_name']	=	isset($vessel->name) ? $vessel->name : $a['vessel_id'];
					}
					else {$a['vessel_name']	=	isset($a['vessel']) ? $a['vessel'] : "";}
					if($position) {
						$position	=	$position->row();
						$a['position_name']		=	isset($position->name) ? $position->name : $a['position'];
					}
					else {$a['position_name']	=	isset($a['position']) ? $a['position'] : "";}
					if($div) {
						$div		=	$div->row();
						if($div){
							$a['division_name']	=	$div->name;
						}else{
							$a['division_name']	= "";
						}
					}else{
						$a['division_name']	= "";
					}
					if($dept) {
						$dept		=	$dept->row();
						$a['department_name']	=	isset($dept->name) ? $dept->name : $a['department'];
					}else {
						$a['department_name']	=	isset($a['department']) ? $a['department'] : "";
					}
					if($sec) {
						$sec		=	$sec->row();
						if($sec){
							$a['section_name']	=	$sec->name;
						}else{
							$a['section_name']	=	"";
						}
					}else{
						$a['section_name']	=	"";
					}
					if($subsec) {
						$subsec		=	$subsec->row();
						if($subsec){
							$a['sub_section_name']	=	$subsec->name;
						}else{
							$a['sub_section_name']	=	"";
						}
					}else{
						$a['sub_section_name']	=	"";
					}
					if($company) {
						$company		=	$company->row();
						$a['company_name']	=	isset($company->name) ? $company->name : $a['company_id'];
					}
					else {$a['company_name']	=	isset($a['company_id']) ? $a['company_id'] : "";}
					if($salary && $this->Abas->checkPermissions("human_resources|salary_viewing", false)) {
						$salary		=	$salary->row();
						$a['salary_grade']	=	isset($salary->grade) ? $salary->grade : $a['salary_grade'];
						$a['salary_rate']	=	isset($salary->rate) ? $salary->rate : 0;
						$a['salary_grade_id']	=	isset($salary->id) ? $salary->id : 0;
					}
					else {
						$a['salary_grade']		=	isset($a['salary_grade']) ? $a['salary_grade'] : "";
						$a['salary_rate']		=	isset($a['salary_rate']) ? $a['salary_grade'] : 0;
					}
					if($employee_status) {
						$employee_status		=	$employee_status->row();
						$a['employee_status']	=	isset($employee_status->employment_status) ? $employee_status->employment_status : $a['employee_status'];
					}
					else {
						$a['employee_status']	=	$a['employee_status'];
					}
					if($regularization) {
						$regularization			=	$regularization->row();
						$a['date_regularized']	=	isset($regularization->effectivity_date) ? $regularization->effectivity_date : "0000-00-00 00:00:00";
					}
					else {
						$a['date_regularized']	=	"0000-00-00 00:00:00";
					}

					if($separation) {
						$separation			=	$separation->row();
						$a['date_separated']	=	isset($separation->effectivity_date) ? $separation->effectivity_date : "0000-00-00 00:00:00";
					}else{
						$a['date_separated']	=	"0000-00-00 00:00:00";
					}
					if($a['middle_name']!="" && $a['middle_name']!=null) { // adds '.' if middle name exists
						$a['full_name']	.=	".";
					}

					$ret=	$a;
					//*/
				}
				else {
					$ret=	false;
				}
			}
			else {
				$ret=	false;
			}
		}
		return $ret;
	}
	public function getWHEmployee($id="") {
		$ret	=	null;
		$query	=	$this->db->query("SELECT hre.*,CONCAT(last_name , ', ' , first_name , ' ' , LEFT(middle_name, 1)) AS full_name FROM whr_employees AS hre WHERE id=".$id);
		if($query!=false) {
			if($query->row()) {
				$ret=	$query->row();

				//* details here
				$a	=	$query->result_array();
				$a	=	$a[0];
				$company=$position=$dept=$salary=$vessel=$employee_status=$warehouse=$region=false;
				if($a['vessel_id']!="") {
					$vessel	=	$this->db->query("SELECT * FROM vessels WHERE id=".$a['vessel_id']);
				}
				if($a['company_id']!="") {
					$company	=	$this->db->query("SELECT * FROM companies WHERE id=".$a['company_id']);
				}
				if($a['position']!="") {
					$position	=	$this->db->query("SELECT * FROM positions WHERE id=".$a['position']);
				}
				if($a['region']!="") {
					$region	=	$this->db->query("SELECT * FROM whr_regions WHERE id=".$a['region']);
				}
				if($a['warehouse']!="") {
					$warehouse	=	$this->db->query("SELECT * FROM whr_warehouses WHERE id=".$a['warehouse']);
				}
				if($a['department']!="") {
					$dept		=	$this->db->query("SELECT * FROM departments WHERE id=".$a['department']);
				}
				if($a['salary_grade']!="") {
					$salary		=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$a['salary_grade']);
				}
				$employee_status		=	$this->db->query("SELECT * FROM whr_employment_history WHERE employee_id=".$a['id']." AND value_changed='Employee Status' ORDER BY id DESC LIMIT 1");
				if($vessel) {
					$vessel		=	$vessel->row();
					$a['vessel_name']	=	isset($vessel->name) ? $vessel->name : $a['vessel_id'];
					// get office based
					if($a['vessel_name'] == 99999) $a['vessel_name'] = "Makati Office Based";
					if($a['vessel_name'] == 99998) $a['vessel_name'] = "Cebu Office Based";
					if($a['vessel_name'] == 99997) $a['vessel_name'] = "Tacloban Office Based";
					if($a['vessel_name'] == 99996) $a['vessel_name'] = "Maintenance Crew";
					if($a['vessel_name'] == 99995) $a['vessel_name'] = "Tayud Office";
					if($a['vessel_name'] == 99994) $a['vessel_name'] = "Avega Trucking";
				}
				if($position) {
					$position	=	$position->row();
					$a['position_name']		=	isset($position->name) ? $position->name : $a['position'];
				}
				else {$a['position_name']		=	isset($a['position']) ? $a['position'] : "";}
				if($region) {
					$region	=	$region->row();
					$a['region_name']		=	isset($region->name) ? $region->name : $a['region'];
				}
				else {$a['region_name']		=	isset($a['region']) ? $a['region'] : "";}
				if($warehouse) {
					$warehouse	=	$warehouse->row();
					$a['warehouse_name']		=	isset($warehouse->name) ? $warehouse->name : $a['warehouse'];
				}
				else {$a['warehouse_name']		=	isset($a['warehouse']) ? $a['warehouse'] : "";}
				if($dept) {
					$dept		=	$dept->row();
					$a['department_name']	=	isset($dept->name) ? $dept->name : $a['department'];
				}
				else {$a['department_name']	=	isset($a['department']) ? $a['department'] : "";}
				if($company) {
					$company		=	$company->row();
					$a['company_name']	=	isset($company->name) ? $company->name : $a['company_id'];
				}
				else {$a['company_name']		=	isset($a['company_id']) ? $a['company_id'] : "";}
				if($salary) {
					$salary		=	$salary->row();
					$a['salary_grade']	=	isset($salary->grade) ? $salary->grade : $a['salary_grade'];
					$a['salary_rate']	=	isset($salary->rate) ? $salary->rate : 0;
					$a['salary_grade_id']	=	isset($salary->id) ? $salary->id : 0;
				}
				else {
					$a['salary_grade']		=	isset($a['salary_grade']) ? $a['salary_grade'] : "";
					$a['salary_rate']		=	isset($a['salary_rate']) ? $a['salary_grade'] : 0;
				}
				if($employee_status) {
					$employee_status		=	$employee_status->row();
					$a['employee_status']	=	isset($employee_status->employment_status) ? $employee_status->employment_status : $a['employee_status'];
				}
				else {
					$a['employee_status']	=	$a['employee_status'];
				}
				if($a['middle_name']!="" && $a['middle_name']!=null) { // adds '.' if middle name exists
					$a['full_name']	.=	".";
				}
				$ret=	$a;
				//*/
			}
			else {
				$ret=	false;
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getDepartments() {
		$ret	=	null;
		$sql = "SELECT * FROM departments ORDER BY name ASC";
		$query	=	$this->db->query($sql);
		if($query) {
			$ret=	$query->result();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getDepartment($id="") {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM departments WHERE id=".$id." ORDER BY name ASC");
			if($query!=false) {
				if($query->row()) {
					$ret=	$query->row();
				}
				else {
					$ret=	false;
				}
			}
			else {
				$ret=	false;
			}
		}
		return $ret;
	}
	public function getPositions() {
		$sql = "SELECT * FROM positions";
		$query	=	$this->db->query($sql);
		if($query) {
			$ret=	$query->result();
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getPosition($id="") {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM positions WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	$query->row();
				}
				else {
					$ret=	false;
				}
			}
			else {
				$ret=	false;
			}
		}
		return $ret;
	}
	public function getVessel($id="") {
		$ret	=	null;
		if(is_numeric($id)) {

			if($id==99999) {
				$ret	=	array("id"=>99999, "name"=>"MAKATI OFFICE (ABISC)", "company"=>1, "bank_account_name"=>"", "bank_account_number"=>"");
			}elseif($id==99984) {
				$ret	=	array("id"=>99984, "name"=>"MAKATI OFFICE (ABMCI)", "company"=>2, "bank_account_name"=>"", "bank_account_number"=>"");
			}elseif($id==99985) {
				$ret	=	array("id"=>99985, "name"=>"MAKATI OFFICE (LMVC)", "company"=>4, "bank_account_name"=>"", "bank_account_number"=>"");
			}elseif($id==99986) {
				$ret	=	array("id"=>99986, "name"=>"MAKATI OFFICE (PCTSC)", "company"=>9, "bank_account_name"=>"", "bank_account_number"=>"");
			}elseif($id==99987) {
				$ret	=	array("id"=>99987, "name"=>"MAKATI OFFICE (SVSC)", "company"=>3, "bank_account_name"=>"", "bank_account_number"=>"");
			}elseif($id==99988) {
				$ret	=	array("id"=>99998, "name"=>"MAKATI OFFICE (VISC)", "company"=>8, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99998) {
				$ret	=	array("id"=>99998, "name"=>"CEBU OFFICE (ABISC)", "company"=>1, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99995) {
				$ret	=	array("id"=>99995, "name"=>"TAYUD OFFICE (ABISC)", "company"=>1, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99992) {
				$ret	=	array("id"=>99992, "name"=>"TAYUD OFFICE (LMVC)", "company"=>4, "bank_account_name"=>"", "bank_account_number"=>"");
			}elseif($id==99983) {
				$ret	=	array("id"=>99983, "name"=>"TAYUD OFFICE (ABMCI)", "company"=>2, "bank_account_name"=>"", "bank_account_number"=>"");
			}elseif($id==99982) {
				$ret	=	array("id"=>99982, "name"=>"TAYUD OFFICE (SVSC)", "company"=>3, "bank_account_name"=>"", "bank_account_number"=>"");
			}elseif($id==99981) {
				$ret	=	array("id"=>99981, "name"=>"TAYUD OFFICE (VISC)", "company"=>8, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99991) {
				$ret	=	array("id"=>99991, "name"=>"TAYUD OFFICE (TSI)", "company"=>11, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99997) {
				$ret	=	array("id"=>99997, "name"=>"TACLOBAN OFFICE (ABISC)", "company"=>1, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99990) {
				$ret	=	array("id"=>99990, "name"=>"CRANE", "company"=>1, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99996) {
				$ret	=	array("id"=>99996, "name"=>"MAINTENANCE", "company"=>1, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99993) {
				$ret	=	array("id"=>99993, "name"=>"MACHINE SHOP", "company"=>1, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99994) {
				$ret	=	array("id"=>99994, "name"=>"MOTORPOOL (TRUCKING)", "company"=>5, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==101) {//Added since there are existing records on issuance table with id like this - probably for Avega Trucking
				$ret	=	array("id"=>101, "name"=>"MOTORPOOL (TRUCKING)", "company"=>5, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			elseif($id==99989){
				$ret	=   array('id'=>99989, 'name'=>'IMPORTATION (PCTSC)', 'company'=>9, "bank_account_name"=>"", "bank_account_number"=>"");
			}
			else {
				$query	=	$this->db->query("SELECT * FROM vessels WHERE id=".$id);
				if($query!=false) {
					if($query->row()) {
						$ret=	$query->result_array();
						$ret=	$ret[0];
					}
					else {
						$ret=	false;
					}
				}
				else {
					$ret=	false;
				}
			}
		}
		return (object) $ret;
	}
	public function geolocate($latlong) { // $variable['results'][0]['formatted_address'] is name of location
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latlong."&sensor=true";
		$data = @file_get_contents($url);
		$jsondata = json_decode($data,true);
		// $this->Mmm->debug($jsondata);
		return $jsondata;
	}
	public function getWeather($latlong) { // $variable['weather'][0]['description'] is the weather at the specified coordinates
		$coordinates	=	explode(",",$latlong);
		$url			=	"http://api.openweathermap.org/data/2.5/weather?lat=".$coordinates[0]."&lon=".$coordinates[1]."&APPID=17dfbecbfd3203ccee1b0de0bea908f0";
		$data			=	@file_get_contents($url);
		$jsondata		=	json_decode($data,true);
		return $jsondata;
	}
	public function checkPermissions($accessed, $redirect=true) {
		if(isset($_SESSION['abas_login'])) {
			$userid=$_SESSION['abas_login']['userid'];
			$user	=	$this->Abas->getUser($userid);
			$sql	=	"SELECT * FROM users_permissions WHERE user_id=".$userid." AND page='".$accessed."'";
			$checkDB	=	$this->db->query($sql);
			// echo "<pre>";print_r($checkDB->result_id->num_rows);echo "</pre>";die();
			if($checkDB==false) {
				if($redirect==true) {
					$this->Abas->sysNotif("ABAS Says", $user['username']." attempted to access ".$accessed." and was prohibited", "Administrator", "danger");
					$this->Abas->redirect(HTTP_PATH."home/stop/1");
				}
				return false;
			}
			else {
				if($checkDB->result_id->num_rows == 0) {
					if($redirect==true) {
						$this->Abas->sysNotif("ABAS Says", $user['username']." attempted to access ".$accessed." and was prohibited", "Administrator", "danger");
						$this->Abas->redirect(HTTP_PATH."home/stop/2");
					}
					return false;
				}
				else {
					$checkUser	=	$this->db->query("SELECT stat FROM users WHERE id=".$userid);
					if($checkUser) {
						if($checkUser->row()) {
							$checkUser	=	$checkUser->row();
							if($checkUser->stat==0) {
								$this->Abas->sysNotif("ABAS Says", "The disabled account of ".$user['username']." attempted to access ".$accessed." and was prohibited", "Administrator", "danger");
								unset($_SESSION['abas_login']);
								$this->Abas->sysMsg("errmsg", "Your account has been disabled! Please contact IT for further information.");
								return false;
							}
							return true;
						}
					}
					return false;
				}
			}
		}
		else {
			if($redirect==true) {
				$this->Abas->redirect(HTTP_PATH);
			}
		}
	}
	public function sysMsg($type, $message) {
		// types = array("errmsg", "warnmsg", "msg", "sucmsg");
		$debug		=	"";
		//*
		if($type == "errmsg" && ENVIRONMENT=="development") {
			$debug	=	debug_backtrace();
			$file	=	$debug[0]['file'];
			$line	=	$debug[0]['line'];
			$debug	=	" [Debug in ".$file." at ".$line." by ".$_SESSION['abas_login']['username']."]";
			$this->Abas->sysNotif("System Error", "A system error has been logged by the automated reporter. ".$debug, "Administrator", "danger");
		}
		//*/
		if(isset($_SESSION[$type])) {
			$ctr	=	count($_SESSION[$type])+1;
		}
		else {
			$ctr	=	0;
		}
		$_SESSION[$type][$ctr]	=	$message.$debug;
		// $this->Mmm->debug($_SESSION);
	}
	public function sysNotif($title="ABAS says", $content="", $audience="everyone", $type="info") {
		/*
		 * $title - keep it short. Usually a small label will suffice
		 * $content - accepts HTML values
		 * $audience - dependent on user role
		 * $type - see Abas->sysMsg for types, or read toastr documentation (http://codeseven.github.io/toastr/)
		 *
		 */
		$ret	=	null;
		if($type!="info" && $type!="success" && $type!="warning" && $type!="danger") {
			$this->Abas->sysMsg("errmsg","Invalid notification type!");die($this->Mmm->debug("Invalid notification type!"));
		}
		if($type=="danger") $type="error"; // made a boo-boo :(
		$getAudience	=	$this->db->query("SELECT DISTINCT(role) AS role FROM users");
		if(!$getAudience) {
			$this->Abas->sysMsg("errmsg","No users present in system!");die($this->Mmm->debug("No users present in system!"));
		}
		if(!$getAudience->row()) {
			$this->Abas->sysMsg("errmsg","No users present in system!");die($this->Mmm->debug("No users present in system!"));
		}
		$getAudience	=	$getAudience->result_array();
		foreach($getAudience as $ctr=>$ga) {
			$validAudience[]	=	$ga['role'];
		}
		$validAudience[]	=	"everyone";
		if(!in_array($audience, $validAudience)) {
			$this->Mmm->debug($validAudience);
			$this->Abas->sysMsg("errmsg","Invalid audience!");die($this->Mmm->debug("Invalid audience!"));
		}
		if($content!="") {
			$debug		=	"";
			if($type == "danger") {
				$debug	=	debug_backtrace();
				$file	=	$debug[0]['file'];
				$line	=	$debug[0]['line'];
				$debug	=	"Debug in ".$file." at ".$line." by ".$_SESSION['abas_login']['username']."";
			}
			$content=$this->Mmm->sanitize($content);
			$query	=	"INSERT INTO notifications (tdate, title, content, audience, type, referrer) VALUES ('".date("Y-m-d H:i:s")."', '".$title."', '".$content."', '".$audience."', '".$type."', '".$debug."')";
			$ret	=	$this->db->query($query);
		}
		else {
			$this->Abas->sysMsg("errmsg","No content!");die($this->Mmm->debug("No content!"));
		}
		return $ret;
	}
	public function display_messages() { // only works when called inside <script>
		if(isset($_SESSION['errmsg'])) {
			if(is_array($_SESSION['errmsg'])) {
				foreach($_SESSION['errmsg'] AS $ctr=>$er) {
					unset($_SESSION['errmsg'][$ctr]);
					echo 'toastr["error"]("'.$er.'","ABAS says");';
				}
				unset($_SESSION['errmsg']);
			}
			else {
				echo 'toastr["error"]("'.$_SESSION['errmsg'].'","ABAS says");';
				unset($_SESSION['errmsg']);
			}
		}
		if(isset($_SESSION['msg'])) {
			if(is_array($_SESSION['msg'])) {
				foreach($_SESSION['msg'] AS $ctr=>$msg) {
					unset($_SESSION['msg'][$ctr]);
					echo 'toastr["info"]("'.$msg.'","ABAS says");';
				}
				unset($_SESSION['msg']);
			}
			else {
				echo 'toastr["info"]("'.$_SESSION['msg'].'","ABAS says");';
				unset($_SESSION['msg']);
			}
		}
		if(isset($_SESSION['warnmsg'])) {
			if(is_array($_SESSION['warnmsg'])) {
				foreach($_SESSION['warnmsg'] AS $ctr=>$warn) {
					echo 'toastr["warning"]("'.$warn.'","ABAS says");';
				}
				unset($_SESSION['warnmsg']);
			}
			else {
				echo 'toastr["warning"]("'.$_SESSION['warnmsg'].'","ABAS says");';
				unset($_SESSION['warnmsg']);
			}
		}
		if(isset($_SESSION['sucmsg'])) {
			if(is_array($_SESSION['sucmsg'])) {
				foreach($_SESSION['sucmsg'] AS $ctr=>$sucmsg) {
					echo 'toastr["success"]("'.$sucmsg.'","ABAS says");';
				}
				unset($_SESSION['sucmsg']);
			}
			else {
				echo 'toastr["success"]("'.$_SESSION['sucmsg'].'","ABAS says");';
				unset($_SESSION['sucmsg']);
			}
		}
		return true;
	}
	public function currencyFormat($number) {
		$number	=	number_format((double)$number, 2, '.', ',');
		return $number;
	}
	public function getNotifications() {
		// gets all notifications within 15 minutes period
		$toastr_notifs		=	"";
		if($_SESSION['abas_login']['role']!="Administrator") {
			$notifs				=	$this->db->query("SELECT * FROM notifications WHERE tdate>='".date("Y-m-d H:i:s", strtotime("-15 minutes"))."' AND (audience='everyone' OR audience='".$_SESSION['abas_login']['role']."') ORDER BY id LIMIT 20");
		}
		else {
			$notifs				=	$this->db->query("SELECT * FROM notifications WHERE tdate>='".date("Y-m-d H:i:s", strtotime("-15 minutes"))."' ORDER BY id LIMIT 20");
		}
		if($notifs) {
			if($notifs=$notifs->result_array()) {
				foreach($notifs as $ctr=>$notif) {
					if($notif['type']=="danger") $notif['type']="error";
					$toastr_notifs	.=	"toastr['".$notif['type']."']('".addslashes($notif['content'])."', '".$notif['title']."');";
				}
			}
		}

		/*if($_SESSION['abas_login']['role']=="Human Resources" || $_SESSION['abas_login']['role']=="Administrator") {
			$hr_notifs			=	$this->notifsFromHR();
			if($hr_notifs!="") {
				if($hr_notifs!="") { $toastr_notifs	.=	$hr_notifs; }
			}
		}*/

		return $toastr_notifs;
	}
	public function notifsFromHR() {
		$toastr_notifs		=	"";
		$all_employees	=	$this->db->query("SELECT id FROM hr_employees");
		$all_employees	=	$all_employees->result();
		################################
		###                          ###
		###     employee reminders   ###
		###                          ###
		################################

		//no employee status
		$check	=	$this->db->query("SELECT count(*) AS no_employee_status FROM hr_employees WHERE employee_status=''");
		$check	=	$check->row();
		if($check->no_employee_status > 0) {
			$toastr_notifs	.=	"toastr['warning']('There are ".$check->no_employee_status." employees without employee status', 'Reminder');";
		}

		//no salary grade
		if($this->Abas->checkPermissions("human_resources|salary",false)) {
			$check	=	$this->db->query("SELECT count(*) AS no_salary_grades FROM hr_employees WHERE salary_grade=0 OR salary_grade=''");
			$check	=	$check->row();
			if($check->no_salary_grades > 0) {
				$toastr_notifs	.=	"toastr['warning']('There are ".$check->no_salary_grades." employees without salary grades', 'Reminder');";
			}
		}

		################################
		###                          ###
		###     employee reminders   ###
		###                          ###
		################################

		foreach($all_employees as $ae) {
			$e			=	$this->Abas->getEmployee($ae->id);
			$employee_link	=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$ae->id."' data-toggle='modal' data-target='#modalDialog'>".$e['full_name']."</a> - ".$e['vessel_name'];

			################################
			###                          ###
			###     notifies for bday    ###
			###                          ###
			################################
			$yearly_bday		=	date("Y-m-d", strtotime(date("Y").substr($e['birth_date'],4,6)));
			// $yearly_bday		=	date("Y").substr($e['birth_date'],4,6);
			$week_before_bday	=	date("Y-m-d", strtotime($yearly_bday." -7 days"));
			if($yearly_bday >= date("Y-m-d") && $week_before_bday < date("Y-m-d")) {
				$toastr_notifs	.=	"toastr['success']('".str_replace("'","\'",$employee_link)." has a birthday on ".date("j F", strtotime($yearly_bday))."!', 'Birthday');";
			}
			################################
			###                          ###
			###     notifies for bday    ###
			###                          ###
			################################

			if($status		=	$this->db->query("SELECT * FROM hr_employment_history WHERE to_val='Regular' AND employee_id=".$e['id']." ORDER BY effectivity_date DESC LIMIT 1")) { // get date of regularization
				if($status->row()) {
					$status	=	$status->row();
					$anniv_date	=	date("m-d", strtotime($status->effectivity_date));
					$anniv_date	=	date("Y")."-".$anniv_date;

					// echo $week_before_anniv." < ".date("Y-m-d")." > ".$anniv_date."<br/>";
					// $this->Mmm->debug($anniv_date);
					if($status->to_val=="Regular") {
						$week_before_anniv	=	date("Y-m-d", strtotime($anniv_date." -7 days"));
						if($week_before_anniv < date("Y-m-d") && $anniv_date >= date("Y-m-d")) {
							$toastr_notifs	.=	"toastr['info']('".str_replace("'","\'",$employee_link)." has a work anniversary on ".date("F j", strtotime($anniv_date))."', 'Regularization Anniversary');";
						}
					}
					elseif($status->to_val=="Probationary" || $status->to_val=="Contractual") {
						$week_before_anniv	=	date("Y-m-d", strtotime($anniv_date." -7 days"));
						if($week_before_anniv < date("Y-m-d") && $anniv_date >= date("Y-m-d")) {
							$toastr_notifs	.=	"toastr['info']('".str_replace("'","\'",$employee_link)." is up for review');";
						}
					}
				}
				else { // base anniversary on date hired
					$anniv_date	=	date("m-d", strtotime($e['date_hired']));
					$anniv_date	=	date("Y")."-".$anniv_date;
					$week_before_anniv	=	date("Y-m-d", strtotime($anniv_date." -7 days"));
					if($week_before_anniv < date("Y-m-d") && $anniv_date >= date("Y-m-d")) {
						$toastr_notifs	.=	"toastr['info']('".str_replace("'","\'",$employee_link)." has a work anniversary on ".date("F j", strtotime($anniv_date))."', 'Date Hire Anniversary');";
					}
				}
			}

		}
		return $toastr_notifs;
	}
	public function redirect($destination) {
		header("location:".$destination);
		echo "<script>window.location='".$destination."'</script>";
		die("<script>window.location='".$destination."'</script>");
		return true;
	}
	public function computePurchaseTaxes($amount, $supplier_id, $etax, $company) {
		if($amount<=0) {
			$this->Abas->sysMsg("errmsg","Amount cannot be less than 0!");
			return false;
		}
		$ret['gross_purchases']	=	$amount;
		$supplier	=	$this->Abas->getSupplier($supplier_id);
		if(!$supplier) {
			$this->Abas->sysMsg("errmsg","Supplier not found!");
			return false;
		}

		if($supplier['vat_computation']=='') {
			$this->Abas->sysMsg("errmsg","Supplier's VAT computation is missing! Click <a href='".HTTP_PATH."mastertables/suppliers/edit/".$supplier['id']."'>HERE</a> to edit this supplier (".$supplier['name'].")");
			return false;
		}
		if(strtolower($supplier['vat_computation'])=="non-vat") {
			$ret	=	array("gross_purchases"=>$amount, "vatable_purchases"=>$amount, "withholding_tax_expanded"=>0, "vat"=>0, "grand_total"=>$amount, "accounts_payable"=>$amount);
		}
		else {
			//note: trade payable = (gross - etax)
			$ret['vat']					=	$ret['gross_purchases']-($ret['gross_purchases']/1.12);
			if(strtolower($supplier['vat_computation'])=="vatable") {
				//$ret['vatable_purchases']		=	$ret['gross_purchases']-$ret['vat'];
				$ret['vatable_purchases']		=	$ret['gross_purchases']/1.12;
			}
			else {
				$ret['vat']					=	0;
				$ret['vatable_purchases']	=	$ret['gross_purchases'];
			}

			$ret['withholding_tax_expanded']	= 0;
			//$ret['accounts_payable']			=	$ret['vatable_purchases']-$ret['withholding_tax_expanded'];
			$ret['accounts_payable']			=	$ret['gross_purchases'];
			//$ret['grand_total']					=	$ret['gross_purchases']-$ret['withholding_tax_expanded'];
			//var_dump($company); exit;
			$company_top_20000 = $this->isCompanyTop20000($company);

			if($company_top_20000==TRUE){

				//we have to make sure we deduct 1% for integrated transaction.  Note that purchaser might not indicate the tax required for Integrated
				if($etax == 0){
					$etax= 1;
				}

				$ret['withholding_tax_expanded']	=	$ret['vatable_purchases']*($etax/100);
				$ret['accounts_payable']			=	$ret['gross_purchases']-$ret['withholding_tax_expanded'];

			}
		}
		return $ret;
	}
	public function computeServiceTaxes($amount, $supplier_id, $etax, $company) {
		if($amount<=0) {
			$this->Abas->sysMsg("errmsg","Amount cannot be less than 0!");
			return false;
		}
		$ret['gross_purchases']	=	$amount;
		$supplier	=	$this->Abas->getSupplier($supplier_id);
		if(!$supplier) {
			$this->Abas->sysMsg("errmsg","Supplier not found!");
			return false;
		}

		if($supplier['vat_computation']=='') {
			$this->Abas->sysMsg("errmsg","Supplier's VAT computation is missing! Click <a href='".HTTP_PATH."mastertables/suppliers/edit/".$supplier['id']."'>HERE</a> to edit this supplier (".$supplier['name'].")");
			return false;
		}




		if(strtolower($supplier['vat_computation'])=="vatable") {


			$ret['vatable_purchases']	=	$ret['gross_purchases']/1.12;
			$ret['vat']					=	$ret['gross_purchases']-$ret['vatable_purchases'];


		}
		else {


			$ret['vatable_purchases']	=	$ret['gross_purchases'];
			$ret['vat']	=	0;

			//note: trade payable = (gross - etax)
			$ret['vat']					=	$ret['gross_purchases']-($ret['gross_purchases']/1.12);
			if(strtolower($supplier['vat_computation'])=="inclusive") {
				//$ret['vatable_purchases']		=	$ret['gross_purchases']-$ret['vat'];
				$ret['vatable_purchases']		=	$ret['gross_purchases']/1.12;
			}
			elseif(strtolower($supplier['vat_computation'])=="exclusive") {
				$ret['vatable_purchases']		=	$ret['gross_purchases']+$ret['vat'];
			}
			else {
				$ret['vat']					=	0;
				$ret['vatable_purchases']	=	$ret['gross_purchases'];
			}

			$ret['withholding_tax_expanded']	= 0;
			//$ret['accounts_payable']			=	$ret['vatable_purchases']-$ret['withholding_tax_expanded'];
			$ret['accounts_payable']			=	$ret['gross_purchases'];
			//$ret['grand_total']					=	$ret['gross_purchases']-$ret['withholding_tax_expanded'];
			//var_dump($company); exit;

		}


		//ETAX

		$ret['withholding_tax_expanded']	= 0;
		$ret['accounts_payable']			=	$ret['gross_purchases'];

				//we have to make sure we deduct 1% for integrated transaction.  Note that purchaser might not indicate the tax required for Integrated
				if($etax > 0){
					$ret['withholding_tax_expanded']	=	$ret['vatable_purchases']*($etax/100);
					$ret['accounts_payable']			=	$ret['gross_purchases']-$ret['withholding_tax_expanded'];
				}




		return $ret;
	}
	public function getContracts($searchobject=array()) {

		$ret	=	null;
		$query	='';
		if(isset($searchobject['vessel_id'])) {
			if(is_numeric($searchobject['vessel_id'])) {
				$query	.=	" AND vessel_id=".$searchobject['vessel_id'];
			}
		}
		if(isset($searchobject['company_id'])) {

			if(is_numeric($searchobject['company_id'])) {
				$query	.=	" AND company_id=".$searchobject['company_id'];

			}
		}
		$sql = "SELECT id FROM service_contracts WHERE 1=1".$query;
		$query	=	$this->db->query($sql);
		if($query) {
			$contracts	=	$query->result_array();
			if(!empty($contracts)) {
				foreach($contracts as $contract) {
					$ret[]	=	$this->Abas->getContract($contract['id']);
				}
			}
		}
		else {
			$ret=	false;
		}

		$ret = array_filter($ret);
		return $ret;

	}
	public function getContract($id="") {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM service_contracts WHERE id=".$id. " AND stat=1");
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
					$ret['company']	=	$this->Abas->getCompany($ret['company_id']);
					$ret['client']	=	$this->Abas->getClient($ret['client_id']);
					if($ret['created_by']) {
						$ret['created_by']	=	$this->Abas->getUser($ret['created_by']);
					}
					if($ret['updated_by']) {
						$ret['updated_by']	=	$this->Abas->getUser($ret['updated_by']);
					}
					if($ret['parent_contract_id']) {
						$ret['mother_contract']	=	$this->Abas->getContract($ret['parent_contract_id']);
					}
					if($ret['created_on']) {
						$ret['created_on']	=	date("j F Y h:i A", strtotime($ret['created_on']));
					}
					if($ret['contract_date']) {
						$ret['contract_date']	=	date("Y-m-d", strtotime($ret['contract_date']));
					}
				}
			}
		}
		return $ret;
	}
	public function getStatementOfAccount($id="") {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM statement_of_accounts WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
					$ret['details']	=	array();
					$ret['company']	=	$this->Abas->getCompany($ret['company_id']);
					$ret['client']	=	$this->Abas->getClient($ret['client_id']);
					if($ret['created_by']) {
						$ret['created_by']	=	$this->Abas->getUser($ret['created_by']);
					}
					if($ret['created_on']) {
						$ret['created_on']	=	date("j F Y h:i A", strtotime($ret['created_on']));
					}

					$nfa_type_check		=	$this->db->query("SELECT * FROM statement_of_account_nfa_details WHERE soa_id=".$id);
					$avega_type_check	=	$this->db->query("SELECT * FROM statement_of_account_details WHERE soa_id=".$id);
					if($nfa_type_check->row() && !$avega_type_check->row()) { // NFA SOA
						$ret['details']	=	$nfa_type_check->result_array();
					}
					elseif(!$nfa_type_check->row() && $avega_type_check->row()) { // Avega SOA
						$ret['details']	=	$avega_type_check->result_array();
					}
					elseif(!$nfa_type_check->row() && !$avega_type_check->row()) { // No details found
						$ret['details']	=	"";
					}
					elseif($nfa_type_check->row() && $avega_type_check->row()) { // ???
						$this->Abas->sysMsg("errmsg","An unknown error has occurred.");
						$ret['details']	=	"";
					}
				}
			}
		}
		return $ret;
	}
	public function getStatementsOfAccount() {
		$ret					=	null;
		$sql = "SELECT * FROM statement_of_accounts WHERE status='Active'";
		$requests				=	$this->db->query($sql);
		if(!$requests) 			{ return null; }
		if(!$requests->row())	{ return null; }
		$requests				=	$requests->result_array();
		if(!empty($requests)) {
			foreach($requests as $ctr=>$request) {
				$requests[$ctr]	=	$this->Purchasing_model->getRequest($request['id']);
			}
		}
		$ret					=	$requests;
		return $ret;
	}
	public function getSOAPayment($soa_id){
		$ret	=	null;
		if(is_numeric($soa_id)) {
			$query	=	$this->db->query("SELECT * FROM payments WHERE soa_id=".$soa_id);
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
					$ret['company']	=	$this->Abas->getCompany($ret['company_id']);
					$ret['client']	=	$this->Abas->getClient($ret['payor']);
					$ret['bank_account']	=	$this->Abas->getBank($ret['bank_account']);
				}
			}
		}
		return $ret;
	}
	public function getBankByCompany($company_id){
		$ret	=	null;

		if(is_numeric($company_id)) {
			$query	=	$this->db->query("SELECT * FROM ac_banks WHERE company_id=".$company_id);
			if($query) {
				$ret = $query->result();
			}
		}
		return $ret;
	}
	public function getLastIDByTable ( $table = NULL ){
		$sql	=	"SELECT MAX(id) as last_id FROM ".$table;
		$query	=	$this->db->query($sql);

		if($query){
			$row 	  = $query->row();
			$last_id  =	$row->last_id;
		}
		else{
			$last_id  = NULL;
		}
		return $last_id;
	}
	public function getServices($category=null){
		$result = null;

		if($category==null){
			$sql	=	"SELECT * FROM services WHERE stat=1;";
			$query	=	$this->db->query($sql);

			if($query){
				$result = $query->result();
			}
		}
		else{
			$sql	=	"SELECT * FROM services WHERE category='".$category."' AND stat=1;";

			$query	=	$this->db->query($sql);

			if($query){
				$result = $query->row();
			}
		}

		return $result;
	}
	public function getItemCategory($id=null){
		$result = null;

		if($id==null){
			$sql	=	"SELECT * FROM inventory_category WHERE stat=1 AND parent=0";
			$query	=	$this->db->query($sql);

			if($query){
				$result = $query->result();
			}
		}
		else{
			$sql	=	"SELECT * FROM inventory_category WHERE id=".$id;

			$query	=	$this->db->query($sql);

			if($query){
				$result = $query->row();
			}
		}

		return $result;
	}
	public function getItemUnit($id=null){
		$result = null;

		if($id==null){
			$sql	=	"SELECT * FROM inventory_unit WHERE stat=1";
			$query	=	$this->db->query($sql);

			if($query){
				$result = $query->result();
			}
		}
		else{
			$sql	=	"SELECT * FROM inventory_unit WHERE id=".$id;

			$query	=	$this->db->query($sql);

			if($query){
				$result = $query->row();
			}
		}

		return $result;
	}
	public function getNextSerialNumber($table_name, $company_identifier) {
		/*
		 * Company identifier variable can be a company ID or vessel ID.
		 * The function will automagically get the actual company id
		 * from the vessel if it is not available in the table
		//*/
		// $this->Mmm->debug($company_identifier);
		$serialized		=	false;
		$sql			=	"DESCRIBE ".$table_name;
		$table_columns	=	$this->db->query($sql);
		$company		=	0;
		$sqlappend		=	"";
		//disabled to only use the company ID for control number iteration, to resolve issue on requisition ,gatepass, and issuance control numbering 
		/*if($table_columns=$table_columns->result_array()) { // get company from vessel
			foreach($table_columns as $column) {
				if($column['Field']=="vessel_id") {
					$vessel				=	$this->Abas->getVessel($company_identifier);
					if($vessel) {
						$sqlappend			.=	" vessel_id=".$company_identifier;
						$company			=	$this->Abas->getCompany($vessel->company);
						if($company) {
							$sibling_vessels	=	$this->db->query("SELECT id FROM vessels WHERE company=".$company->id);
							$sibling_vessels	=	$sibling_vessels->result_array();
							foreach($sibling_vessels as $sibling_vessel) {
								$sqlappend			.=	" OR vessel_id=".$sibling_vessel['id'];
							}
						}
					}
				}
			}
		}*/
		//if(!is_object($company)) {
			if($company==0 && is_numeric($company_identifier)) {
				$company	=	$this->Abas->getCompany($company_identifier);
				if($company) {
					$sqlappend	=	" OR company_id=".$company->id;
				}
			}
		//}
		$sqlappend			=	trim($sqlappend, " OR");
		$checksql			=	"SELECT MAX(control_number) AS last_used_control_number FROM ".$table_name." WHERE ".$sqlappend;
		// $this->Mmm->debug($checksql);
		$check				=	$this->db->query($checksql);
		if($check) {
			if($check->row()) {
				$check						=	(array)$check->row();
				$last_used_control_number	=	(int)$check['last_used_control_number'];
				$serialized					=	$last_used_control_number + 1;
			}
		}
		//Note: Disabled this else-condition as it likely to cause page load problem after succesful form submission.
		//Produces the following error below:
		//Uncaught SyntaxError: Invalid hexadecimal escape sequence
		/*else {
			$this->Abas->sysMsg("errmsg", "Serialization query error; check ".$table_name." for required columns."); // most likely the column control_number is missing
		}*/
		return $serialized;
	}
	public function getNextFilingNumberByLocation($table,$company_id,$user_location){
		$sql = "SELECT MAX(filing_number) as last_filing_number FROM ".$table." WHERE created_at='".$user_location."' AND company_id=".$company_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
			$filing_number = ($result->last_filing_number)+1;
		}
		return $filing_number;
	}
	public function computeCoordinateDistance($departure, $arrival, $earthRadius = 6371000) {
		/* This function calculates the distance in kilometers between two points on the globe */
		$latFrom = deg2rad($departure['latitude']);
		$lonFrom = deg2rad($departure['longitude']);
		$latTo = deg2rad($arrival['latitude']);
		$lonTo = deg2rad($arrival['longitude']);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
		cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * $earthRadius;
	}
	public function getVesselCertificates($vessel_id){
		$sql = "SELECT * FROM vessel_certificates WHERE vessel_id=".$vessel_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getTableByStatus($table,$status = NULL){//can be use to restrict listview to display depending on the status relating to approval scheme

		if($status!=NULL){
			$sql 	=	"SELECT * FROM ".$table." WHERE status='".$status."' ORDER BY id DESC";
		}else{
			$sql 	=	"SELECT * FROM ".$table. " ORDER BY id DESC";
		}

		$query	=	$this->db->query($sql);

		if($query){
			$data = array("total"=>count($query->result_array()),"rows"=>$query->result_array());

		}else{
			$data = NULL;
		}

		return $data;
	}
	public function isCompanyTop20000($company_id){
		$sql = "SELECT * FROM companies WHERE is_top_20000=1 AND id=".$company_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
			if(isset($result->is_top_20000) && $result->is_top_20000==1){
				$result = TRUE;
			}else{
				$result = FALSE;
			}
		}
		return $result;
	}
	public function readChangeLog(){
	    $filename = "changelog.txt";
	    if ( file_exists($filename) && ($fp = fopen($filename, "rb"))!==false ) {
			$ver['num'] = file($filename)[0];
			$ver['logs'] = fread($fp,filesize($filename));
			fclose($fp);
		}else{
			$ver['num'] = "";
			$ver['logs'] = "";
		}
		return $ver;
	}
	public function like_match($pattern, $subject){
	    $pattern = str_replace('%', '.*', preg_quote(strtolower($pattern), '/'));
	    return (bool) preg_match("/^{$pattern}$/i", $subject);
	}
	public function vlookup($lookup_value, $lookup_array){
	    foreach ($lookup_array as $item) {
            if ($item == $lookup_value) {
                return TRUE;
            }
        }
        return FALSE;
	}
	public function getUserLocations($loc=''){
		if($loc!=''){
			$sql = "SELECT * FROM user_locations WHERE location_name LIKE '".$loc."'";
		}else{
			$sql = "SELECT * FROM user_locations";
		}
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getDivisions(){
		$sql = "SELECT * FROM divisions";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getDivision($id){
		$sql = "SELECT * FROM divisions WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getSections(){
		$sql = "SELECT * FROM department_sections";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getSection($id){
		$sql = "SELECT * FROM department_sections WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getSubsections(){
		$sql = "SELECT * FROM department_sub_sections";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getSubsection($id){
		$sql = "SELECT * FROM department_sub_sections WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function dateFormat($date){
		$format = date("M d, Y", strtotime($date));
		return $format;
	}

	public function getItems($tbl,$data=array()){
		$this->db->from($tbl);
		$this->db->where($data);
		$query = $this->db->get();
		return $query->result();
	}

	public function getItemById($tbl,$data=array()){
		$this->db->from($tbl);
		$this->db->where($data);
		$query = $this->db->get();
		return $query->row();
	}

	public function countItems($tbl,$data){
	    $this->db->where($data);
	    $this->db->from($tbl);
	    return $this->db->count_all_results();
	}

	function dbActivity($action=''){
		$query = $this->db->last_query();
		$referer = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "");
		$insert = array(
			'ip' => $_SERVER['REMOTE_ADDR'],
			'session_id' => $_SESSION['uniqid'],
			'timestamp' => date('Y-m-d H:i:s'),
			'query' => $query,
			'action' => $action,
			'page' => $_SERVER['REQUEST_URI'],
			'referrer' => $referer,
			'source' => '',
		);
		$this->db->insert('db_activity',$insert);
	}

	public function insertItem($tbl,$data,$action=''){
		$this->db->insert($tbl,$data);
		$this->dbActivity($action);
	}

	public function insertOnly($tbl,$data){
		$this->db->insert($tbl,$data);
	}

	public function updateItem($tbl,$data,$where=array(),$action='')
	{
		$this->db->set($data);
		$this->db->where($where);
		$this->db->update($tbl);
		$this->dbActivity($action);
	}

	public function getSum($tbl,$col,$data=array())
	{
		$this->db->select_sum($col);
		$this->db->where($data);
		$query = $this->db->get($tbl);
		return $query->row();
	}

	public function last_item($tbl)
	{
		$query = $this->db->query("SELECT * FROM $tbl ORDER BY id DESC");
   		return $query->row();
	}

	public function lastItemByCol($tbl,$data){
		$this->db->from($tbl);
		$this->db->where($data);
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function delItem($tbl,$data=array())
	{
		$this->db->where($data);
		$this->db->delete($tbl);
	}

	public function getItemsByGroup($tbl,$data=array(),$group_by)
	{
		$this->db->from($tbl);
		$this->db->where($data);
		$this->db->group_by($group_by);
		$query = $this->db->get();
		return $query->result();
	}

	public function recordExist($tbl,$data=array())
	{
		$this->db->where($data);
		$query = $this->db->get($tbl);
		if($query->num_rows()){
			return true;
		}else{
			return false;
		}
	}

	public function getVesselById($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('vessels');
		return $query->row()->name;
	}

	public function getEmpId($userid)
	{
		$this->db->where('user_id',$userid);
		$query = $this->db->get('hr_employees');
		if($query->num_rows()){
			return $query->row()->id;
		}else{
			return null;	
		}
	}

	public function getEmpIdByName($name){
		substr($name, strpos($data, ", ") + 1); 
	}

	public function getLeaveApprover($id)
	{
		$data = array(
			'role' => 'approver',
			'approver_id' => $id,
			'document' => 'leave',
			'status' => true
		);
		$this->db->where($data);
		$query = $this->db->get('employee_approver');
		if($query->num_rows()){
			return true;
		}else{
			return false;
		}
	}

	public function getLeaveCount($dept='')
	{
		$emp_id = $this->getEmpId($_SESSION['abas_login']['userid']);
		$data = array(
			'status' => 'FOR APPROVAL',
			'approver_id' => $emp_id
		);
		$this->db->where($data);
		$this->db->from('employee_leave');
		return $this->db->count_all_results();
	}

	public function getOvertimeCount()
	{
		$emp_id = $this->getEmpId($_SESSION['abas_login']['userid']);
		$data = array(
			'status' => 'FOR APPROVAL',
			'approver_id' => $emp_id
		);
		$this->db->where($data);
		$this->db->from('employee_overtime');
		return $this->db->count_all_results();
	}

	public function getEmpName($id)
	{
		$query = $this->db->query("SELECT concat(last_name,', ',first_name,' ',middle_name) as 'name'
			FROM hr_employees WHERE id=$id");
		if($query->num_rows()){
			return $query->row()->name;	
		}else{
			return null;
		}
	}

	public function getEmpNameWithId($id)
	{
		if($id == 0 or $id == null){
			return null;

		}else{
			$query = $this->db->query("SELECT concat(last_name,', ',first_name,' ',middle_name,'(',id,')') as 'name'
				FROM hr_employees WHERE user_id=$id");
			if($query->num_rows()){
				return $query->row()->name;	
			}else{
				return null;
			}	
		}
		
	}
}
?>