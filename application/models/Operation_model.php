<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

##########################################################################
##########################################################################
#######################                         ##########################
#######################  AVEGA BROS INTEGRATED  ##########################
#######################  	it@avegabros.com 	##########################
#######################           -             ##########################
#######################        May 2016         ##########################
#######################           -             ##########################
#######################    Operation Model      ##########################
#######################                         ##########################
##########################################################################
##########################################################################


class Operation_model extends CI_Model{
	public function __construct() {
		// $this->load->database();
		$this->load->model("Abas");
	}
	public function getVesselActivity($vid){
		$res = array("id"=>null, "sched_id"=>null, "report_date"=>null, "user_id"=>null, "message"=>null, "stat"=>null, "unit"=>null, "qty"=>null, "location"=>null, "coordinates"=>null, "fob"=>null, "activity"=>null, "vessel_id"=>null, "issued_to"=>null, "mobile_number"=>null, "weather"=>null);
		$vessel_report			=	$this->db->query('SELECT * FROM ops_report_vessel WHERE vessel_id ='.$vid.' AND stat=1 ORDER BY report_date DESC LIMIT 1' );
		if($vessel_report=(array)$vessel_report->row()){
			$res['sched_id'] 	=	$vessel_report['sched_id'];
			$res['report_date']	=	$vessel_report['report_date'];
			$res['user_id']		=	$vessel_report['user_id'];
			$res['message']		=	$vessel_report['message'];
			$res['stat']		=	$vessel_report['stat'];
			$res['unit']		=	$vessel_report['unit'];
			$res['qty']			=	$vessel_report['qty'];
			$res['location']	=	$vessel_report['location'];
			$res['coordinates']	=	$vessel_report['coordinates'];
		}
		$maintenance_report		=	$this->db->query('SELECT * FROM ops_report_vessel_fuel WHERE vessel_id ='.$vid.' AND stat=1 ORDER BY report_date DESC LIMIT 1' );
		if($maintenance_report=(array)$maintenance_report->row()){
			$res['report_date']	=	$maintenance_report['report_date'];
			$res['fob']			=	$maintenance_report['fuel_reading'];
			$res['message']		=	$maintenance_report['message'];
			$res['location']	=	$maintenance_report['location'];
			$res['coordinates']	=	$maintenance_report['coordinates'];
		}
		$location_heartbeat	=	$this->db->query("SELECT r.*,t.mobile_number, t.issued_to FROM sms_reports AS r JOIN ops_report_tool_registry AS t ON t.mobile_number=r.data_source WHERE t.vessel_id=".$vid." AND r.location IS NOT NULL AND r.location NOT LIKE '%loc%' AND r.location NOT LIKE '%unknown%' ORDER BY r.received_on DESC, t.registered_on DESC LIMIT 1");
		if($location_heartbeat		=	(array)$location_heartbeat->row()){
			$res['report_date']		=	$location_heartbeat['received_on'];
			$res['location']		=	empty($location_heartbeat['formatted_address'])?$location_heartbeat['location']:$location_heartbeat['formatted_address'];
			$res['coordinates']		=	$location_heartbeat['location'];
			$res['issued_to']		=	$location_heartbeat['issued_to'];
			$res['mobile_number']	=	$location_heartbeat['mobile_number'];
			$res['weather']			=	$location_heartbeat['weather'];
		}
		// $this->Mmm->debug($this->Abas->getVessel($vid));
		// $this->Mmm->debug($res);
		return $res;
	}
	public function getVesselsByActivity() { // gets all vessels sorted by latest activity
		$query	=	$this->db->query("SELECT id, name, company FROM vessels WHERE status='Active' ORDER BY name ASC");
		$vessels	=	$query->result_array();
		// $this->Mmm->debug($vessels);
		foreach($vessels as $ctr=>$v) {
			// $v			=	(array)$v;
			// $this->Mmm->debug($v);
			$activity	=	(array)$this->Operation_model->getVesselActivity($v['id']);
			$vessels[$ctr]['activity_id']		=	$activity['id'];
			$vessels[$ctr]['activity_date']		=	$activity['report_date'];
			$vessels[$ctr]['activity_remark']	=	$activity['message'];
			$vessels[$ctr]['activity_stat']		=	$activity['stat'];
			// $vessels[$ctr]['activity_unit']		=	$activity['unit'];
			$vessels[$ctr]['activity_qty']		=	$activity['qty'];
			// $vessels[$ctr]['activity_fob']		=	$activity['fob'];
			$vessels[$ctr]['current_location']	=	$activity['location'];
			$vessels[$ctr]['coordinates']		=	$activity['coordinates'];
			$vessels[$ctr]['activity']			=	$activity['activity'];
			$vessels[$ctr]['issued_to']			=	$activity['issued_to'];
			$vessels[$ctr]['mobile_number']		=	$activity['mobile_number'];
			$vessels[$ctr]['weather']			=	$activity['weather'];
		}

		//sort by timestamp
		$activity_date = array();
		foreach ($vessels as $key => $row) {
			$activity_date[$key] = $row['activity_date'];
		}
		array_multisort($activity_date, SORT_DESC, $vessels);

		return $vessels;
	}
	public function getAllContracts($search="",$limit="",$offset="",$order="",$sort="") {
		/*
		 *
		 * Creates a JSON array formatted to the bootstrap table
		 *
		 */
		$table					=	"service_contracts";
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
		$tablefields			=	$tablefields->result();

		if(isset($limit)) {
			if($limit!="") {
				if(is_numeric($limit)) {
					$limit	=	", ".$limit;
				}
			}
		}else{$limit="";}
		if(isset($offset)) {
			if($offset!="") {
				if(is_numeric($offset)) {
					$offset	=	"LIMIT ".$offset;
				}
			}
		}else{$offset="";}
		if(isset($order)) {
			if($order!="") {
				if(strtolower($order)==='asc' || strtolower($order)==='desc') {
					$order	=	"ORDER BY ".($sort!=""?"gc.".$sort:"gc.id")." ".$order;
				}
			}
		} else {$order="";}
		$searchfields	=	"";
		if(isset($searchstring)) {
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
		}
		$sql	=	"
			SELECT
				gc.*
			FROM ".$table." AS gc
			WHERE gc.stat=1
			$searchfields $order $offset $limit
		";
		$total	=	"
			SELECT
				gc.*
			FROM ".$table." AS gc
			WHERE gc.stat=1
			$searchfields
		";

		$all	=	$this->db->query($sql);
		$total	=	$this->db->query($total);
		$all	=	$all->result_array();

		// $this->Mmm->debug($sql);
		// $this->Mmm->debug($all);
		// $this->Mmm->debug($total);

		if(!empty($all)) {
			foreach($all as $ctr=>$a) {
				$all[$ctr]['company_name']		=	"";
				$all[$ctr]['charterer_name']	=	"";
				if(!empty($a['company_id'])) {
					$company	=	$this->db->query("SELECT * FROM companies WHERE id=".$a['company_id']);
					if($company!=false) {
						$company		=	$company->row();
						$all[$ctr]['company_name']	=	isset($company->name) ? $company->name : $a['company_id'];
					}
				}
				if(!empty($a['date_effective'])) {
					$all[$ctr]['date_effective']	=	($a['date_effective']=="0000-00-00 00:00:00") ? "" : date("j F Y", strtotime($a['date_effective']));
				}
				if(!empty($a['status'])) {
					$all[$ctr]['status']	=	ucfirst(strtolower($a['status']));
				}
				if(!empty($a['amount'])) {
					$all[$ctr]['amount']	=	number_format($a['amount'],2,".",",");
				}
				if(!empty($a['rate'])) {
					$all[$ctr]['rate']	=	number_format($a['rate'],2,".",",");
				}
				if(!empty($a['quantity'])) {
					$all[$ctr]['quantity']	=	number_format($a['quantity'],2,".",",");
				}
				if(!empty($a['client_id'])) {
					$client	=	$this->db->query("SELECT * FROM clients WHERE id=".$a['client_id']);
					if($client) {
						if($client=$client->row()) {
							$all[$ctr]['charterer_name']	=	isset($client->contact_person) ? $client->contact_person : $a['client_id'];
						}
					}
				}
			}
			$data	=	array("total"=>count($total->result_array()),"rows"=>$all); // creates array accdg to bootstrap tables
		}
		else {
			$data	=	false;
		}

		return $data;
	}
	public function getContracts() {

		//$ret = null;
		$sql = "SELECT * FROM service_contracts WHERE stat = 1 AND status<>'Voided' AND status<>'Draft' AND status<>'For Approval'";
		$res = $this->db->query($sql);
		return $res->result_array();

	}
	public function getContract($id) {
		$ret	=	null;
		$contract	=	$this->db->query("SELECT * FROM service_contracts WHERE id=".$id. " AND stat=1 AND status<>'Voided'");
		if($contract!=false) {
			if($contract->row()) {
				$contract	=	(array)$contract->row();
				$contract['company_name']		=	"";
				$contract['charterer_name']	=	"";
				if(!empty($contract['company_id'])) {
					$company	=	$this->db->query("SELECT * FROM companies WHERE id=".$contract['company_id']);
					if($company!=false) {
						$company		=	$company->row();
						$contract['company_name']	=	isset($company->name) ? $company->name : $contract['company_id'];
						$contract['company_address']	=	isset($company->address) ? $company->address : $contract['company_id'];
						$contract['company_contact']	=	isset($company->telephone_no) ? $company->telephone_no : $contract['company_id'];
					}
				}
				if(!empty($contract['date_effective'])) {
					$contract['date_effective']	=	($contract['date_effective']=="0000-00-00 00:00:00") ? "" : date("j F Y", strtotime($contract['date_effective']));
				}
				if(!empty($contract['status'])) {
					$contract['status']	=	ucfirst(strtolower($contract['status']));
				}
				if(!empty($contract['client_id'])) {
					$client	=	$this->db->query("SELECT * FROM clients WHERE id=".$contract['client_id']);
					if($client) {
						if($client=$client->row()) {
							$contract['client']	=	isset($client->company) ? $client->company : $contract['client_id'];
						}
					}
				}
				$ret	=	$contract;
			}
			else { $ret	=	false; }
		}
		else { $ret	=	false; }
		return $ret;
	}
	public function getContractDetails($id) {
		$ret	=	null;
		$contract	=	$this->db->query("SELECT * FROM service_contracts WHERE id=".$id);
		if($contract==false) { return false; }
		if($contract->row()) { return false; }
		$contract		=	(array)$contract->row();
		$detail_table	=	"service_order_detail_".strtolower(str_replace(" ","",$contract['type']));
		$details	=	$this->db->query("SELECT * FROM ".$detail_table." WHERE service_contract_id=".$id);
		if($details!=false) {
			if($details->row()) {
				$details	=	$details->result_array();
				$ret		=	$details;
			}
			else { $ret	=	false; }
		}
		else { $ret	=	false; }
		return $ret;
	}
	public function getContractByStatus($stat) {

		//$sql = "SELECT * FROM service_contract WHERE status = '$stat'";
		$sql = "SELECT * FROM service_contracts as s
				INNER JOIN companies as c ON s.company_id = c.id
				INNER JOIN clients as l ON s.charterer = l.id
				WHERE status = '$stat'";
		$ret =	$query->result();
		return $ret;

	}
	public function getContractsByCompany($company_id){
		$sql = "SELECT * FROM service_contracts WHERE stat=1 AND status<>'Voided' AND company_id=".$company_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getWsrs() {

		//$ret = null;
		$sql = 'SELECT * FROM ops_wsr WHERE stat = 1';
		$res = $this->db->query($sql);
		return $res->result_array();

	}
	public function getWsr($id='') {

		$ret = null;
		if($id!=''){
			$sql = 'SELECT * FROM ops_wsr WHERE id = '.$id;
			$res = $this->db->query($sql);
			$ret = $res->row();
		}

		return $ret;

	}

	//use to get either wsr, wsi or wb
	public function getTransactions($type='') {

		//$ret = null;
		$appendSql = '';
		if($type!=''){
			$appendSql = " AND type = '".$type."'";
		}
		$sql = 'SELECT * FROM ops_transactions WHERE stat = 1'.$appendSql;
		$res = $this->db->query($sql);
		if($res==false) { return false; }

		return $res->result_array();

	}
	public function getTransaction($id='') {

		$ret = null;
		if($id!=''){
			$sql = 'SELECT * FROM ops_transactions WHERE id = '.$id;
			$res = $this->db->query($sql);
			$ret = $res->row();
		}

		return $ret;

	}
	public function getTransactionsByReference($refno='') {

		$ret = null;
		if($refno!=''){
			$sql = 'SELECT * FROM ops_transactions WHERE reference_no = "'.$refno.'"';
			$res = $this->db->query($sql);
			$ret = $res->row();
		}

		return $ret;

	}
	public function getBagsByReference($refno='') {

		$ret = null;
		if($refno!=''){
			$sql = 'SELECT sum(bags) as total_bags FROM ops_transactions WHERE reference_no = "'.$refno.'"';
			$res = $this->db->query($sql);
			$ret = $res->row();
		}

		return $ret;

	}


	//get trucking locations, used for trucking billing
	public function getTruckingLocations() {

		$ret = NULL;
			$sql = 'SELECT * FROM trucking_locations ORDER BY name ASC';
			$res = $this->db->query($sql);
			if($res){
			$ret = $res->result_array();
			}
		return $ret;

	}
	public function getTruckingLocation($id='') {

		$ret = null;
		if($id!=''){

			$sql = 'SELECT * FROM trucking_locations WHERE id ='. $id;
			$res = $this->db->query($sql);
			if($res){
				$ret = $res->row();
			}
		}

		return $ret;

	}


	public function getVesselFuelReport($vid){

		$res = 0;
		if($vid){
			$query = $this->db->query('SELECT * FROM ops_report_vessel_fuel WHERE vessel_id ='.$vid.' ORDER BY report_date DESC' );
			$res = $query->result_array();
		}

		return $res;
	}

	public function getVesselFuelReportPerVoyage($voy){

		$res = 0;
		if($voy){
			$query = $this->db->query('SELECT * FROM ops_report_vessel_fuel WHERE voyage_no ='.$voy.' ORDER BY report_date DESC' );
			$res = $query->result_array();
		}

		return $res;
	}

	//get voyages from fuel report table (this will be used for awhile to get the active voyages)
	public function getVoyageFromFuel($vid){

		$res = 0;
		if($vid){
			$query = $this->db->query('SELECT distinct(voyage_no) from ops_report_vessel_fuel where vessel_id ='.$vid.' ORDER BY report_date DESC');
			$res = $query->result_array();
		}

		return $res;
	}

	//get port distance details
	public function getPortDetails($id){

		$res = 0;
		if($id){
			$query = $this->db->query('SELECT * FROM ops_port_distance WHERE id ='.$id );
			$res = $query->result_array();
		}

		return $res;
	}

	public function getToolsAssignedVessel($data_source) {
		$vessel_id				=	0;
		$check					=	$this->db->query("SELECT * FROM ops_report_tool_registry WHERE mobile_number='".$data['data_source']."' ORDER BY date_registered DESC LIMIT 1");
		if($check) {
			if($check->row()) {
				$check	=	(array)$check->row();
				$vessel	=	$this->Abas->getVessel($check['vessel_id']);
				$vessel_id	=	$vessel->id;
			}
		}
		return $vessel_id;
	}


	public function getTransactionTypes(){

			$query = $this->db->query('SELECT * FROM ops_transaction_types WHERE stat = 1 ORDER BY transaction_name ASC');
			$res = $query->result_array();


		return $res;
	}

	public function getTransactionType($id){

		$res = 0;
		if($id!=''){
			$query = $this->db->query('SELECT * FROM ops_transaction_types WHERE id ='.$id);

			$res = $query->row();
		}

		return $res;
	}
	public function getServiceProviders() {

		//$ret = null;
		$sql = 'SELECT * FROM service_providers WHERE stat = 1';
		$res = $this->db->query($sql);
		return $res->result_array();

	}
	public function getServiceProvider($id='') {

		$ret = null;
		if($id!=''){
			$sql = 'SELECT * FROM service_providers WHERE id = '.$id;
			$res = $this->db->query($sql);
			$ret = $res->row();
		}

		return $ret;

	}
	public function getApBillings() {

		//$ret = null;
		$sql = 'SELECT * FROM ops_billing_ap WHERE stat = 1';
		$res = $this->db->query($sql);
		return $res->result_array();

	}
	public function getApBilling($id='') {

		$ret = null;
		if($id!=''){
			$sql = 'SELECT * FROM ops_billing_ap WHERE id = '.$id;
			$res = $this->db->query($sql);
			$ret = $res->row();
		}

		return $ret;

	}
	public function getApBillingDetails($id='') {

		$ret = null;
		if($id!=''){
			$sql = 'SELECT * FROM ops_billing_ap_details WHERE billing_id = '.$id;
			$res = $this->db->query($sql);
			$ret = $res->result_array();
		}

		return $ret;

	}
	public function getArBillings() {

		//$ret = null;
		$sql = 'SELECT * FROM ops_billing_ar WHERE stat = 1';
		$res = $this->db->query($sql);
		return $res->result_array();

	}
	public function getArBilling($id='') {

		$ret = null;
		if($id!=''){
			$sql = 'SELECT * FROM ops_billing_ar WHERE id = '.$id;
			$res = $this->db->query($sql);
			$ret = $res->row();
		}

		return $ret;

	}
	public function getArBillingDetails($id='') {

		$ret = null;
		if($id!=''){
			$sql = 'SELECT * FROM ops_billing_ar_details WHERE billing_id = '.$id;
			$res = $this->db->query($sql);
			$ret = $res->result_array();
		}

		return $ret;

	}

////START OF OUT-TURN SUMMARY///////////////////////////////////////////////////////////////////////
	public function getOutTurnSummaries(){
		$sql 	=	"SELECT * FROM ops_out_turn_summary WHERE stat=1 AND status='Approved'";
		$query	=	$this->db->query($sql);

		if($query){

			$result =	$query->result_array();
			foreach($result as $ctr=>$row){
				$company = $this->Abas->getCompany($row['company_id']);
				$result[$ctr]['company_name'] = $company->name;
			}

		}
		else{
			$result =	NULL;
		}

		return $result;
	}
	public function getOutTurnSummary($id){
		$sql 	=	"SELECT * FROM ops_out_turn_summary WHERE id=".$id." AND stat=1";
		$query	=	$this->db->query($sql);

		if($query){

			$result =	$query->row();

			$created_by	= $this->Abas->getUser($result->created_by);
			$result->full_name = $created_by['full_name'];
			$result->created_by_signature = $created_by['signature'];
			$result->served_by = $created_by['user_location'];

			$submitted_by = $this->Abas->getUser($result->submitted_by);
			$result->submitted_by_name = $submitted_by['full_name'];

			$verified_by = $this->Abas->getUser($result->verified_by);
			$result->verified_by_name = $verified_by['full_name'];
			$result->verified_by_signature = $verified_by['signature'];

			$approved_by = $this->Abas->getUser($result->approved_by);
			$result->approved_by_name = $approved_by['full_name'];
			$result->approved_by_signature = $approved_by['signature'];

			$company = $this->Abas->getCompany($result->company_id);
			$result->company_name	 = $company->name;
			$result->company_address = $company->address;
			$result->company_contact = $company->telephone_no;

		}
		else{
			$result =	NULL;
		}

		return $result;
	}
	public function getOutTurnSummaryDetails($id){
		$sql 	=	"SELECT * FROM ops_out_turn_summary_details WHERE out_turn_summary_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$result =	$query->row();

			if($result->vessel_id!=0){
				$result->vessel_name = $this->Abas->getVessel($result->vessel_id)->name;
			}else{
				$result->vessel_name = "-";
			}

		}
		else{
			$result =	NULL;
		}

		return $result;
	}
	public function getOutTurnSummaryAttachments($id){
		$sql 	=	"SELECT * FROM ops_out_turn_summary_attachments WHERE out_turn_summary_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$result =	$query->result_array();
		}
		else{
			$result =	NULL;
		}

		return $result;
	}
	public function getOutTurnSummaryDeliveries($id,$group=FALSE){

		$service = $this->getOutTurnSummary($id);

		if($group==FALSE){

			/*if($service->type_of_service=='Trucking'){
				$sql 	=	"SELECT * FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$id." ORDER BY way_bill_number ASC, delivery_receipt_number ASC";
			}elseif($service->type_of_service=='Handling'){
				$sql 	=	"SELECT * FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$id." ORDER BY  warehouse_issuance_form_number ASC, warehouse_receipt_form_number ASC, others ASC";
			}*/

			$sql 	=	"SELECT * FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$id." ORDER BY sorting ASC";

		}elseif($group==TRUE){

			if($service->type_of_service=='Trucking'){

				//$sql 	=	"SELECT *,SUM(quantity) as quantity, SUM(net_weight) as total_net_weight, SUM(gross_weight) as total_gross_weight FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$id." GROUP BY warehouse,trucking_company,transaction ORDER BY warehouse ASC, trucking_company ASC, transaction ASC";

				$sql 	=	"SELECT *,SUM(quantity) as quantity, SUM(net_weight) as total_net_weight, SUM(gross_weight) as total_gross_weight, (SELECT rate FROM service_contracts_rates WHERE service_contract_id = SO.service_contract_id AND warehouse=OS_d.warehouse) AS wh_rate FROM ops_out_turn_summary_deliveries as OS_d INNER JOIN ops_out_turn_summary as OS ON OS.id = OS_d.out_turn_summary_id INNER JOIN service_orders AS SO ON SO.id = OS.service_order_id WHERE OS_d.out_turn_summary_id=".$id." GROUP BY OS_d.warehouse,OS_d.trucking_company,OS_d.transaction ORDER BY OS_d.warehouse ASC, OS_d.trucking_company ASC, OS_d.transaction ASC";

			}elseif($service->type_of_service=='Handling'){

				//$sql 	=	"SELECT *,SUM(quantity) as quantity, SUM(net_weight) as total_net_weight, SUM(gross_weight) as total_gross_weight FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$id." GROUP BY warehouse,number_of_moves,transaction ORDER BY warehouse ASC, transaction ASC, number_of_moves ASC";

				$sql 	=	"SELECT *,SUM(quantity) as quantity, SUM(net_weight) as total_net_weight, SUM(gross_weight) as total_gross_weight, (SELECT rate FROM service_contracts_rates WHERE service_contract_id = SO.service_contract_id AND warehouse=OS_d.warehouse) AS wh_rate FROM ops_out_turn_summary_deliveries as OS_d INNER JOIN ops_out_turn_summary as OS ON OS.id = OS_d.out_turn_summary_id INNER JOIN service_orders AS SO ON SO.id = OS.service_order_id WHERE OS_d.out_turn_summary_id=".$id." GROUP BY OS_d.warehouse,OS_d.number_of_moves,OS_d.transaction ORDER BY OS_d.warehouse ASC, OS_d.transaction ASC, OS_d.number_of_moves ASC";


			}else{

				$sql 	=	"SELECT * FROM ops_out_turn_summary_output WHERE out_turn_summary_id=".$id;
			}

		}

		$query	=	$this->db->query($sql);

		if($query){
			$result =	$query->result_array();
		}
		else{
			$result =	NULL;
		}

		return $result;
	}
	public function getOutTurnSummaryOutput($id){
		$sql 	=	"SELECT * FROM ops_out_turn_summary_output WHERE out_turn_summary_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$result =	$query->row();
		}
		else{
			$result =	NULL;
		}

		return $result;
	}
	public function getOutTurnSummaryByCompany($company_id,$status=NULL,$services=NULL){
		if($status==NULL && $services==NULL){
			$sql = "SELECT * FROM ops_out_turn_summary WHERE company_id=".$company_id." AND stat=1";
		}else{

			$sql = "SELECT * FROM ops_out_turn_summary WHERE company_id=".$company_id." AND status='".$status."' AND type_of_service LIKE '%" . $services . "%' AND stat=1";
		}
		$query	=	$this->db->query($sql);

		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}

		return $result;
	}
	public function getOutTurnSummaryByContract($contract_id){

		//$sql = "SELECT *, OS.id AS OS_ID,OS.control_number AS OS_CN FROM ops_out_turn_summary AS OS INNER JOIN service_orders AS SO ON OS.service_order_id = SO.id  WHERE SO.service_contract_id=".$contract_id." AND OS.status='Approved' AND OS.type_of_service='Handling' OR SO.service_contract_id=".$contract_id." AND OS.status='Approved' AND OS.type_of_service='Trucking'";

		$sql = "SELECT *, OS.id AS OS_ID,OS.control_number AS OS_CN FROM ops_out_turn_summary AS OS INNER JOIN service_orders AS SO ON OS.service_order_id = SO.id  WHERE OS.status='Approved' AND OS.type_of_service='Handling' OR OS.status='Approved' AND OS.type_of_service='Trucking'";


		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}
///END OF OUT-TURN SUMMARY//////////////////////////////////////////////////////////////////////////

///START OF SERVICE ORDER///////////////////////////////////////////////////////////////////////////////

public function getServiceOrder($id){
	$sql = "SELECT * FROM service_orders WHERE id=".$id;
	$query = $this->db->query($sql);

	if($query){
		$result = $query->row();
		$company = $this->Abas->getCompany($result->company_id);
		$result->company_name = $company->name;
		$result->company_address = $company->address;
		$result->company_contact = $company->telephone_no;
		$created_by	= $this->Abas->getUser($result->created_by);
		$result->full_name = $created_by['full_name'];
		$result->created_by_signature = $created_by['signature'];
		$approved_by	= $this->Abas->getUser($result->approved_by);
		$result->approved_by_full_name = $approved_by['full_name'];
		$result->approved_by_signature = $approved_by['signature'];
		$result->contract = $this->Abas->getContract($result->service_contract_id);
		$result->details = $this->getServiceOrderDetail($result->type,$result->id);

	}else{
		$result = NULL;
	}

	return $result;
}

public function getServiceOrders($status=NULL){

	if($status==NULL){
		$sql = "SELECT * FROM service_orders WHERE stat=1";
	}else{
		$sql = "SELECT * FROM service_orders WHERE stat=1 AND status='".$status."' AND type='Shipping' OR type='Trucking' OR type='Handling' OR type='Lighterage' OR type='Time Charter' OR type='Towing'";
	}

	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
	}else{
		$result = NULL;
	}

	return $result;
}

public function getServiceOrderDetail($type,$service_order_id){

	if($type=='Shipping'){
		$table = "service_order_detail_voyage";
	}elseif($type=='Lighterage'){
		$table = "service_order_detail_lighterage";
	}elseif($type=='Time Charter'){
		$table = "service_order_detail_timecharter";
	}elseif($type=='Towing'){
		$table = "service_order_detail_towing";
	}elseif($type=='Trucking'){
		$table = "service_order_detail_trucking";
	}elseif($type=='Handling'){
		$table = "service_order_detail_handling";
	}elseif($type=='Storage'){
		$table = "service_order_detail_storage";
	}elseif($type=='Equipment Rental'){
		$table = "service_order_detail_rental";
	}

	$sql = "SELECT * FROM ".$table." WHERE service_order_id=".$service_order_id. " AND stat=1";
	$query = $this->db->query($sql);

	if($query){
		$result = $query->row();
		if(isset($result->vessel_id)){
			$result->vessel = $this->Abas->getVessel($result->vessel_id)->name;
		}
		/*if(isset($result->truck_id)){
			$truck = $this->Abas->getTruck($result->truck_id);
			$result->truck = $truck[0]['plate_number'] . " (".$truck[0]['make']."-".$truck[0]['model']." ".$truck[0]['type'].")";
		}*/
	}else{
		$result = NULL;
	}

	return $result;
}
public function getServiceOrdersByCompany($company_id=NULL){

	//$sql = "SELECT * FROM service_orders WHERE stat=1 AND status='Approved' AND company_id=".$company_id;
	$sql = "SELECT * FROM service_orders WHERE stat=1 AND status='Approved' AND company_id=".$company_id. " AND type='Shipping' AND NOT EXISTS (SELECT service_order_id FROM ops_out_turn_summary WHERE ops_out_turn_summary.service_order_id = service_orders.id AND ops_out_turn_summary.status<>'Cancelled')
	    OR stat=1 AND status='Approved' AND company_id=".$company_id. " AND type='Trucking' AND NOT EXISTS (SELECT service_order_id FROM ops_out_turn_summary WHERE ops_out_turn_summary.service_order_id = service_orders.id  AND ops_out_turn_summary.status<>'Cancelled')
	    OR stat=1 AND status='Approved' AND company_id=".$company_id. " AND type='Handling' AND NOT EXISTS (SELECT service_order_id FROM ops_out_turn_summary WHERE ops_out_turn_summary.service_order_id = service_orders.id  AND ops_out_turn_summary.status<>'Cancelled')
	    OR stat=1 AND status='Approved' AND company_id=".$company_id. " AND type='Lighterage' AND NOT EXISTS (SELECT service_order_id FROM ops_out_turn_summary WHERE ops_out_turn_summary.service_order_id = service_orders.id  AND ops_out_turn_summary.status<>'Cancelled')
	    OR stat=1 AND status='Approved' AND company_id=".$company_id. " AND type='Time Charter' AND NOT EXISTS (SELECT service_order_id FROM ops_out_turn_summary WHERE ops_out_turn_summary.service_order_id = service_orders.id  AND ops_out_turn_summary.status<>'Cancelled')
	    OR stat=1 AND status='Approved' AND company_id=".$company_id. " AND type='Towing' AND NOT EXISTS (SELECT service_order_id FROM ops_out_turn_summary WHERE ops_out_turn_summary.service_order_id = service_orders.id  AND ops_out_turn_summary.status<>'Cancelled')";

	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
	}else{
		$result = NULL;
	}

	return $result;
}


public function deleteServiceOrderDetail($service_order_id){

	$sql1 = "DELETE FROM service_order_detail_voyage WHERE service_order_id=".$service_order_id;
	$query1 = $this->db->query($sql1);

	$sql2 = "DELETE FROM service_order_detail_lighterage WHERE service_order_id=".$service_order_id;
	$query2 = $this->db->query($sql2);

	$sql3 = "DELETE FROM service_order_detail_timecharter WHERE service_order_id=".$service_order_id;
	$query3 = $this->db->query($sql3);

	$sql4 = "DELETE FROM service_order_detail_towing WHERE service_order_id=".$service_order_id;
	$query4 = $this->db->query($sql4);

	$sql5 = "DELETE FROM service_order_detail_trucking WHERE service_order_id=".$service_order_id;
	$query5 = $this->db->query($sql5);

	$sql6 = "DELETE FROM service_order_detail_handling WHERE service_order_id=".$service_order_id;
	$query6 = $this->db->query($sql6);

	$sql7 = "DELETE FROM service_order_detail_storage WHERE service_order_id=".$service_order_id;
	$query7 = $this->db->query($sql7);

	$sql8 = "DELETE FROM service_order_detail_rental WHERE service_order_id=".$service_order_id;
	$query8 = $this->db->query($sql8);

	if($query1 || $query2 || $query3 || $query4 || $query5 || $query6 || $query7 || $query8){
		$result = TRUE;
	}else{
		$result = FALSE;
	}

	return $result;
}


///END OF SERVICE ORDERS//////////////////////////////////////////////////////////////////////////

///START OF SERVICE CONTRACTS/////////////////////////////////////////////////////////////////////
public function getMotherContract($sub_contract_id){
	if(!is_int($sub_contract_id)) return false;
	$sql = "SELECT parent_contract_id FROM service_contracts WHERE id=".$sub_contract_id;
	$query = $this->db->query($sql);

	if($query){
		$parent_contract_id = $query->row();

		if($parent_contract_id->parent_contract_id){
			$sql = "SELECT reference_no FROM service_contracts WHERE id=".$parent_contract_id->parent_contract_id;
			$query = $this->db->query($sql);

			if($query){
				$result = $query->row();
			}else{
				$result = NULL;
			}

		}else{
			$result = NULL;
		}

	}else{
		$result = NULL;
	}

	return $result;
}

//returns true if contract reference number is already in use.
public function checkContractReferenceNo($contract_reference_no,$id){

	$sql = "SELECT * FROM service_contracts WHERE reference_no='".$contract_reference_no."' AND status<>'Voided'";
	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
		$count = count($result);

		if($count>0){
			foreach($result as $row){
				if($row->id == $id){
					$ret = FALSE;
				}else{
					$ret = TRUE;
				}
			}
		}else{
			$ret = FALSE;
		}

	}else{
		$ret = FALSE;
	}

	return $ret;
}
public function getAllSubContracts($mother_contract_id){
	$sql = "SELECT * FROM service_contracts WHERE parent_contract_id=".$mother_contract_id;
	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
		foreach($result as $ctr => $row){
			$company = $this->Abas->getCompany($row->company_id);
			$result[$ctr]->company = $company->name;
			$client = $this->Abas->getClient($row->client_id);
			$result[$ctr]->client = $client['company'];
		}
	}else{
		$result = NULL;
	}
	return $result;
}
public function getAllServiceOrdersByContract($contract_id){
	$sql = "SELECT *,service_orders.id as x_id, service_orders.control_number as x_control_number, service_orders.status as x_status,service_orders.type as x_type FROM service_orders INNER JOIN service_contracts ON service_orders.service_contract_id= service_contracts.id WHERE service_orders.service_contract_id=".$contract_id;
	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
		foreach($result as $ctr => $row){
			$company = $this->Abas->getCompany($row->company_id);
			$result[$ctr]->company = $company->name;
			$client = $this->Abas->getClient($row->client_id);
			$result[$ctr]->client = $client['company'];
			$result[$ctr]->type = $row->x_type;
			$result[$ctr]->status = $row->x_status;
		}
	}else{
		$result = NULL;
	}
	return $result;
}
public function getAllOutTurnSummaryByContract($contract_id){

	$sql = "SELECT ops_out_turn_summary.id as x_id, ops_out_turn_summary.control_number as x_control_number, ops_out_turn_summary.company_id, service_contracts.client_id,ops_out_turn_summary.type_of_service, ops_out_turn_summary.created_on, ops_out_turn_summary.status as x_status, service_contracts.id as x_contract_id FROM ops_out_turn_summary INNER JOIN service_orders ON ops_out_turn_summary.service_order_id = service_orders.id INNER JOIN service_contracts ON service_orders.service_contract_id= service_contracts.id WHERE service_orders.service_contract_id=".$contract_id;
	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
		foreach($result as $ctr => $row){
			$company = $this->Abas->getCompany($row->company_id);
			$result[$ctr]->company = $company->name;
			$client = $this->Abas->getClient($row->client_id);
			$result[$ctr]->client = $client['company'];
			$result[$ctr]->status = $row->x_status;
		}
	}else{
		$result = NULL;
	}
	return $result;
}
public function getAllBillingByContract($contract_id){
	$sql = "SELECT * FROM statement_of_accounts WHERE contract_id=".$contract_id;
	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
		foreach($result as $ctr => $row){
			$company = $this->Abas->getCompany($row->company_id);
			$result[$ctr]->company = $company->name;
			$client = $this->Abas->getClient($row->client_id);
			$result[$ctr]->client = $client['company'];
		}
	}else{
		$result = NULL;
	}
	return $result;
}
public function getAllCollectionByContract($contract_id){
	$sql = "SELECT *,payments.id as x_id,payments.status as x_status FROM payments INNER JOIN statement_of_accounts ON payments.soa_id=statement_of_accounts.id WHERE statement_of_accounts.contract_id=".$contract_id;
	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
		foreach($result as $ctr => $row){
			$company = $this->Abas->getCompany($row->company_id);
			$result[$ctr]->company = $company->name;
		}
	}else{
		$result = NULL;
	}
	return $result;
}
public function getAllRequestPaymentsByContract($contract_id){
	$sql = "SELECT * FROM ac_request_payment WHERE reference_id='".$contract_id."' AND reference_table='service_contracts'";
	$query = $this->db->query($sql);

	if($query){
		$result = $query->result();
		foreach($result as $ctr => $row){
			$company = $this->Abas->getCompany($row->company_id);
			$result[$ctr]->company = $company->name;
			if($row->payee_type=='Employee'){
				$payee	= $this->Abas->getUser($row->payee);
				$result[$ctr]->payee_name = $payee['full_name'];
			}else{
				$payee	= $this->Abas->getSupplier($row->payee);
				$result[$ctr]->payee_name = $payee['name'];
			}
			if(isset($row->vessel_id)){
				$vessel = $this->Abas->getVessel($row->vessel_id);
				$result[$ctr]->vessel = $vessel->name;
			}
		}
	}else{
		$result = NULL;
	}
	return $result;
}
public function getContractRates($contract_id,$warehouse=NULL){

	if($warehouse==NULL){
		$sql = "SELECT * FROM service_contracts_rates WHERE service_contract_id=".$contract_id;
	}else{
		$sql = "SELECT * FROM service_contracts_rates WHERE service_contract_id=".$contract_id. " AND warehouse='".$warehouse."'";
	}

	$query = $this->db->query($sql);

	if($query){
		$result = $query->result_array();
	}else{
		$result = NULL;
	}
	return $result;
}
///END OF SERVICE CONTRACTS/////////////////////////////////////////////////////////////////////

///CONTRACT PERCENTAGE/////////////////////////////////////////////////////////////////////////
	public function calcPercentage($arr_percentage){

		$overall_percentage = 0;

		for($i=0;$i<count($arr_percentage);$i++){
			$overall_percentage = $overall_percentage + ($arr_percentage[$i]/100);
		}

		return round(($overall_percentage/count($arr_percentage))*100,2);
	}
	public function updateContractsOverallPercentage(){
		$sql1 = "SELECT * FROM service_contracts WHERE status!='Voided' AND status!='Draft' AND status!='For Approval'";
		$query1 = $this->db->query($sql1);

		if($query1){
			$result = $query1->result();
			foreach($result as $row){
				$sub_contracts_percentage = $this->getSubContractsPercentage($row->id);
				$service_orders_percentage = $this->getServiceOrdersPercentage($row->id);
				$out_turn_summary_percentage	=	$this->getOutTurnSummaryPercentage($row->id);
				$billing_percentage	=	$this->getBillingPercentage($row->id);
				$collection_percentage	=	$this->getCollectionPercentage($row->id);
				$request_payments_percentage	=	$this->getRequestPaymentsPercentage($row->id);

				$arr_percentages = array($service_orders_percentage,$out_turn_summary_percentage,$billing_percentage,$collection_percentage);

				if($sub_contracts_percentage!=0){
					 array_push($arr_percentages,$sub_contracts_percentage);
				}

				if($request_payments_percentage!=0){
					array_push($arr_percentages,$request_payments_percentage);
				}

				$overall_percentage = $this->calcPercentage($arr_percentages)."%";

				$sql2 = "UPDATE service_contracts SET status='".$overall_percentage."' WHERE id=".$row->id;
				$query2 = $this->db->query($sql2);
			}
		}
	}
	public function getSubContractsPercentage($mother_contract_id){

		$ctr = 0;
		$percentages = array();
		$sub_contract_percentage=0;

		$sub_contracts = $this->getAllSubContracts($mother_contract_id);

		if(count($sub_contracts)>0){
			foreach($sub_contracts as $sub_contract){
				$percentages[$ctr] = $sub_contract->status;
				$ctr++;
			}

			$sub_contract_percentage = $this->calcPercentage($percentages);


		}

		return $sub_contract_percentage;

	}
	public function getServiceOrdersPercentage($contract_id){

		$ctr = 0;
		$percentages = array();
		$service_orders_percentage =0;

		$service_orders = $this->getAllServiceOrdersByContract($contract_id);

		if(count($service_orders)>0){

			foreach($service_orders as $service_order){
				if($service_order->status=='Draft'){
					$percentages[$ctr] = 10;
				}
				elseif($service_order->status=='For Approval'){
					//$percentages[$ctr] = 25;
					$percentages[$ctr] = 50;
				}
				elseif($service_order->status=='Approved'){
					//$percentages[$ctr] = 50;
					$percentages[$ctr] = 100;
				}
				//elseif($service_order->status=='On-going'){
				//	$percentages[$ctr] = 75;
				//}
				//elseif($service_order->status=='Completed'){
				//	$percentages[$ctr] = 100;
				//}
				$ctr++;
			}

			$service_orders_percentage = $this->calcPercentage($percentages);

		}

		return $service_orders_percentage;

	}
	public function getOutTurnSummaryPercentage($contract_id){

		$ctr = 0;
		$percentages = array();
		$out_turn_summary_percentage = 0;

		$out_turns = $this->getAllOutTurnSummaryByContract($contract_id);

		if(count($out_turns)>0){

			foreach($out_turns as $out_turn){
				if($out_turn->status=='Draft'){
					$percentages[$ctr] = 10;
				}
				elseif($out_turn->status=='For Verification'){
					$percentages[$ctr] = 25;
				}
				elseif($out_turn->status=='For Approval'){
					$percentages[$ctr] = 50;
				}
				elseif($out_turn->status=='Approved'){
					$percentages[$ctr] = 100;
				}
				$ctr++;
			}

			$out_turn_summary_percentage = $this->calcPercentage($percentages);

		}

		return $out_turn_summary_percentage;

	}
	public function getBillingPercentage($contract_id){

		$ctr = 0;
		$percentages = array();
		$billing_percentage=0;

		$billings = $this->getAllBillingByContract($contract_id);

		if(count($billings)>0){

			foreach($billings as $billing){
				if($billing->status=='Draft'){
					$percentages[$ctr] = 10;
				}
				elseif($billing->status=='Pending for Approval'){
					$percentages[$ctr] = 25;
				}
				elseif($billing->status=='Approved'){
					$percentages[$ctr] = 50;
				}
				elseif($billing->status=='Waiting for Payment'){
					$percentages[$ctr] = 75;
				}
				elseif($billing->status=='Paid'){
					$percentages[$ctr] = 100;
				}
				elseif($billing->status=='Cancelled'){
					$percentages[$ctr] = 0;
				}
				$ctr++;
			}

			$billing_percentage = $this->calcPercentage($percentages);

		}

		return $billing_percentage;

	}
	public function getCollectionPercentage($contract_id){

		$ctr = 0;
		$percentages = array();
		$collection_percentage=0;

		$collections = $this->getAllCollectionByContract($contract_id);

		if(count($collections)>0){

			foreach($collections as $collection){
				if($collection->x_status=='For Deposit'){
					$percentages[$ctr] = 50;
				}
				elseif($collection->x_status=='Deposited'){
					$percentages[$ctr] = 100;
				}
				elseif($billing->status=='Cancelled'){
					$percentages[$ctr] = 0;
				}
				$ctr++;
			}

			$collection_percentage = $this->calcPercentage($percentages);
		}

		return $collection_percentage;
	}
	public function getRequestPaymentsPercentage($contract_id){

		$ctr = 0;
		$percentages = array();
		$rfp_percentage = 0;

		$request_payments = $this->getAllRequestPaymentsByContract($contract_id);

		if(count($request_payments)>0){

			foreach($request_payments as $request_payment){
				if($request_payment->status=='For voucher'){
					$percentages[$ctr] = 50;
				}
				elseif($request_payment->status=='For releasing'){
					$percentages[$ctr] = 100;
				}
				//elseif($request_payment->status=='Released'){
				//	$percentages[$ctr] = 100;
				//}
				$ctr++;
			}

			$rfp_percentage = $this->calcPercentage($percentages);

		}

		return $rfp_percentage;
	}

///END CONTRACT PERCENTAGE/////////////////////////////////////////////////////////////////////
	public function checkOutTurnHasSOA($out_turn_id){
		$sql = "SELECT * FROM statement_of_accounts WHERE out_turn_summary_id=".$out_turn_id. " AND status<>'Draft'";
		$query = $this->db->query($sql);
		if($query){
			$count = count($query->result());
			if($count>0){
				$result = TRUE;
			}else{
				$result = FALSE;
			}
		}
		return $result;
	}
	public function getServiceOrdersByType($service_type,$date_from,$date_to,$vessel_id=NULL){
	
		$sql = "SELECT service_orders.* FROM service_orders INNER JOIN service_order_detail_voyage ON service_orders.id=service_order_detail_voyage.service_order_id  WHERE service_order_detail_voyage.vessel_id=".$vessel_id." AND service_orders.type='".$service_type."' AND service_orders.status='Approved' AND service_orders.date_needed BETWEEN '".$date_from."' AND '".$date_to."'";
		
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getVoyage($vessel_id,$date_from,$date_to){
		$sql = "SELECT ops_out_turn_summary_details.*,ops_out_turn_summary.remarks,ops_out_turn_summary.control_number,ops_out_turn_summary.company_id, ops_out_turn_summary.type_of_service,ops_out_turn_summary.service_order_id FROM ops_out_turn_summary INNER JOIN ops_out_turn_summary_details ON ops_out_turn_summary.id=ops_out_turn_summary_details.out_turn_summary_id WHERE ops_out_turn_summary.status='Approved' AND (ops_out_turn_summary.type_of_service='Time Charter' OR ops_out_turn_summary.type_of_service='Shipping') AND ops_out_turn_summary.service_order_id <> 0 AND ops_out_turn_summary_details.vessel_id=".$vessel_id." AND ops_out_turn_summary_details.loading_arrival BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY ops_out_turn_summary_details.loading_arrival ASC";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getSOAbyOutturn($outturn_id,$contract_id=''){
		$sql = "SELECT * FROM statement_of_accounts WHERE out_turn_summary_id=".$outturn_id. " AND status<>'Cancelled'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			if(!$result){
				if($contract_id!=''){
					$sql2 = "SELECT * FROM statement_of_accounts WHERE contract_id=".$contract_id." AND status<>'Cancelled'";
					$query2 = $this->db->query($sql2);
					if($query2){
						$result = $query2->result();
					}else{
						$result = NULL;
					}
				}else{
					$result = NULL;
				}
			}
		}else{
			$result = NULL;
		}
		return $result;
	}
}

?>