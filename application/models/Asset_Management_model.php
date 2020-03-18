<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_Management_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

//===============================================================================================================//
//============================== GENERAL  USE ==================================================================//


	public function getUser( $user_id = NULL ){
		$result = NULL;
		$sql = "SELECT * FROM users WHERE id=".$user_id;
		$query	=	$this->db->query($sql);

		if($query){
			if($query->row()){
				$row =	(array)$query->row();
				$result['full_name']	=	$row['last_name'].", ".$row['first_name']." ".$row['middle_name'];
				$result['signature']	=	$row['signature'];
			}
		}else{
			$result	=	NULL;
		}

		return $result;
	}

	public function getCompanies() {
		
		$sql = "SELECT * FROM companies WHERE stat=1 ORDER BY name ASC";
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getCompany(	$company_id = NULL) {
		$sql = "SELECT * FROM companies WHERE id=".$company_id;
		$query	=	$this->db->query("SELECT * FROM companies WHERE id=".$company_id);

		if($query){
			if($query->row()){
				$row =	(array)$query->row();
				$result['name']	=	$row['name'];
				$result['address']	=	$row['address'];
				$result['telephone_no']	=	$row['telephone_no'];
			}
		}else{
			$result	=	NULL;
		}

		return $result;
	}

	public function getVessels() {
		
		$sql = "SELECT * FROM vessels WHERE status='Active' ORDER BY name ASC";
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getVessel( $vessel_id = NULL) {
		
		$sql = "SELECT * FROM vessels WHERE status='Active' AND id=".$vessel_id;
		$query	=	$this->db->query($sql);

		if($query){
			if($query->row()){
				$row =	(array)$query->row();
				$result['name']	=	$row['name'];
			}
		}else{
			$result	=	NULL;
		}

		return $result;
	}

	public function getVesselMeasurements( $vessel_id = NULL ){
		$sql	=	"SELECT length_loa,breadth,depth,gross_tonnage FROM vessels WHERE id=".$vessel_id;
		$query	= 	$this->db->query($sql);
		
		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getVesselsByCompany( $company_id = NULL) {

		$sql	=	"SELECT id, name FROM vessels WHERE status='Active' AND company=".$company_id." ORDER BY name ASC";
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getTrucks() {
		
		$sql = "SELECT * FROM trucks WHERE stat=1 ORDER BY name ASC";
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getTruck( $truck_id = NULL) {
		
		$sql = "SELECT * FROM trucks WHERE stat=1 AND id=".$truck_id;
		$query	=	$this->db->query($sql);

		if($query){
			if($query->row()){
				$row =	(array)$query->row();
				$result['plate_number']	=	$row['plate_number'];
			}
		}else{
			$result	=	NULL;
		}

		return $result;
	}

	public function getTruckInfo( $truck_id = NULL ){
		$sql	=	"SELECT make,model,plate_number,engine_number,chassis_number,type FROM trucks WHERE id=".$truck_id;
		$query	= 	$this->db->query($sql);
		
		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getTrucksByCompany( $company_id = NULL) {

		$sql	=	"SELECT id, plate_number FROM trucks WHERE stat=1 AND company=".$company_id." ORDER BY plate_number ASC";
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
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
//===============================================================================================================//
//====================== MAINTENANCE - COMPLAINT FORMS=========================================================//
	public function getWOs(){
		$sql	=	"SELECT * FROM am_vessel_work_order WHERE (status='Final' OR status='Approved');";
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
			foreach($results as $row){
				$row->WO_number = "WO No. " . $row->control_number . " - " . date("F j, Y", strtotime($row->created_on));
			}
			
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getWO( $id = NULL ){
		$sql	=	"SELECT * FROM am_vessel_work_order WHERE id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	(array)$query->row();

			$created_by	=	$this->getUser($results['created_by']);
			$results['full_name'] = $created_by['full_name'];
			$results['created_by_signature'] = $created_by['signature'];
			
			if($results['status']=='Approved'){
				$approved_by	=	$this->getUser($results['approved_by']);
				$results['approved_by_full_name'] = $approved_by['full_name'];
				$results['approved_by_signature'] = $approved_by['signature'];
			}

			$verified_by	=	$this->Abas->getUser($results['verified_by']);
			$results['verified_by'] = $verified_by['full_name'];

			$approved_by	=	$this->Abas->getUser($results['approved_by']);
			$results['approved_by'] = $approved_by['full_name'];

			$company	=	$this->getCompany($results['company_id']);
			$results['company_name'] = $company['name'];
			$results['company_address'] = $company['address'];
			$results['company_contact'] = $company['telephone_no'];

			$vessel =	 $this->getVessel($results['vessel_id']);
			$results['vessel_name'] = $vessel['name'];

		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getWODetails( $id = NULL ){
		$sql	=	"SELECT * FROM am_vessel_work_order_details WHERE vessel_work_order_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	(array)$query->result();	
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}


	public function updateWOStatus( $id = NULL, $status = NULL ){

		$updated_by = $_SESSION['abas_login']['userid'];
		$updated_on = date("Y-m-d H:i:s");

		if($status=='For Approval'){
			$sql 	= "UPDATE am_vessel_work_order SET status='" . $status . "', verified_by=".$updated_by.", verified_on='".$updated_on."' WHERE id=".$id;
		}elseif($status=='Approved'){
			$sql 	= "UPDATE am_vessel_work_order SET status='" . $status . "', approved_by=".$updated_by.", approved_on='".$updated_on."' WHERE id=".$id;
		}else{
			$sql 	= "UPDATE am_vessel_work_order SET status='" . $status . "' WHERE id=".$id;
		}
		$query 	= $this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;

	}

	public function deleteWO( $id = NULL ){

		$sql	=	"DELETE FROM am_vessel_work_order WHERE id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function deleteWODetails( $id = NULL ){

		$sql	=	"DELETE FROM am_vessel_work_order_details WHERE vessel_work_order_id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function getTRMRFs(){
		$sql	=	"SELECT * FROM am_truck_repairs WHERE (status='Final' OR status='Approved');";
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
			foreach($results as $row){
				$row->TRMRF_number = "TRMRF No. " . $row->control_number . " - " . date("F j Y", strtotime($row->created_on));
			}
			
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getTRMRF( $id = NULL ){

		$sql	=	"SELECT * FROM am_truck_repairs WHERE id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	(array)$query->row();

			$created_by	=	$this->Abas->getUser($results['created_by']);
			$results['created_by'] = $created_by['full_name'];
			$results['created_by_signature'] = $created_by['signature'];

			$verified_by	=	$this->Abas->getUser($results['verified_by']);
			$results['verified_by'] = $verified_by['full_name'];
			$results['verified_by_signature'] = $verified_by['signature'];

			$approved_by	=	$this->Abas->getUser($results['approved_by']);
			$results['approved_by'] = $approved_by['full_name'];
			$results['approved_by_signature'] = $approved_by['signature'];

			$company	=	$this->getCompany($results['company_id']);
			$results['company_name'] = $company['name'];
			$results['company_address'] = $company['address'];
			$results['company_contact'] = $company['telephone_no'];

			$truck =	 $this->getTruck($results['truck_id']);
			$results['plate_number'] = $truck['plate_number'];

			$info = $this->getTruckInfo($results['truck_id']);
			$results['make'] = $info[0]->make;
			$results['model']= $info[0]->model;
			$results['engine_number']= $info[0]->engine_number;
			$results['chassis_number']= $info[0]->chassis_number;
			$results['type']= $info[0]->type;
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getTRMRFDetails( $id = NULL ){
		$sql	=	"SELECT * FROM am_truck_repairs_details WHERE truck_repairs_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	(array)$query->result();	
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function updateTRMRFStatus( $id = NULL, $status = NULL ){

		$updated_by = $_SESSION['abas_login']['userid'];
		$updated_on = date("Y-m-d H:i:s");

		if($status=='For Approval'){
			$sql 	= "UPDATE am_truck_repairs SET status='" . $status . "', verified_by=".$updated_by.", verified_on='".$updated_on."' WHERE id=".$id;
		}elseif($status=='Approved'){
			$sql 	= "UPDATE am_truck_repairs SET status='" . $status . "', approved_by=".$updated_by.", approved_on='".$updated_on."' WHERE id=".$id;
		}else{
			$sql 	= "UPDATE am_truck_repairs SET status='" . $status . "' WHERE id=".$id;
		}
		$query 	= $this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;

	}

	public function deleteTRMRF( $id = NULL ){

		$sql	=	"DELETE FROM am_truck_repairs WHERE id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function deleteTRMRFDetails( $id = NULL ){

		$sql	=	"DELETE FROM am_truck_repairs_details WHERE truck_repairs_id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}


//===============================================================================================================//
//====================== MAINTENANCE - EVALUATION FORMS ========================================================//

	public function prepareEvaluationItems(){
		$data['index']	= 	array("A","B","C","D","E","F");
		return $data;
	}

	public function prepareSRMSF(){
		$data['ratings'] = array("Passed","For Repair","For Replacement","For Verification","Not Applicable");
		$data['steps'] = array(
			  array("A","Vessel Deck"),
			  array("B","Deck Machineries"),
			  array("C","Main Engine"),
			  array("D","Auxillary Engines"),
			  array("E","Electrical Machinery"),
			  array("F","Ancillary Equipment")
		  );
		return $data;
	}

	public function prepareMTDE(){
		$data['ratings'] = array("Passed","For Repair","For Replacement","For Verification","Not Applicable");
		$data['steps'] = array(
				  array("A","Basic Test"),
				  array("B","Vehicle Exterior"),
				  array("C","Vehicle Interior"),
				  array("D","Underhood"),
				  array("E","Underbody")
			  );
		return $data;
	}

	public function checkIndexing( $type = NULL, $item_index = NULL, $item_set = NULL, $item_sub_set = NULL, $item_id){

		if($item_id==0){
			$sql   =	"SELECT * FROM am_evaluation_items WHERE type='" . $type . "' AND item_index='" . $item_index . "' AND item_set=" . $item_set . " AND item_sub_set=" . $item_sub_set;
		}else{
			$sql   =	"SELECT * FROM am_evaluation_items WHERE type='" . $type . "' AND item_index='" . $item_index . "' AND item_set=" . $item_set . " AND item_sub_set=" . $item_sub_set . " AND id<>".$item_id;
		}
		
		$query = $this->db->query($sql);

		if(count($query->result())>0){
			$result	=	TRUE;
		}else{
			$result	=	FALSE;
		}

		return $result;

	}

	public function getEvaluationItem( $id = NULL ){

		$sql  	=   "SELECT * FROM am_evaluation_items WHERE id=".$id;
		$query 	= 	$this->db->query($sql);

		if($query){
			$results =(array)$query->row();
		}else{
			$results	=	NULL;
		}

		return $results;

	}

	public function getEvaluationItemsPerIndex( $index = NULL , $type = NULL ) {

		$sql 	= "SELECT * FROM am_evaluation_items WHERE item_index='". $index ."' AND type='".$type."' ORDER BY item_set,item_sub_set ASC";
		$query	= $this->db->query($sql);

		if($query){
			$results = $query->result();
		}else{
			$results = NULL;
		}

		return $results;
	}

	public function deleteEvaluationItem( $id = NULL ){

		$sql   =  "DELETE FROM am_evaluation_items WHERE id=".$id;
		$query =  $this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function checkEvaluationItemIfUsed( $id = NULL, $type = NULL ){

		if($type=="Vessel"){
			$sql	=	"SELECT * FROM am_vessel_evaluation_details WHERE evaluation_item_id=".$id;
		}
		elseif($type=="Truck"){
			$sql	=	"SELECT * FROM am_truck_evaluation_details WHERE evaluation_item_id=".$id;
		}

		$query = $this->db->query($sql);

		if($query){

			$count = count($query->result());
			
			if($count>=1){
				$results = TRUE;
			}
			else{
				$results = FALSE;
			}

		}else{
			$results = FALSE;
		}

		return $results;
		
	}


	public function getSRMSF( $id = NULL ){
		$sql	=	"SELECT * FROM am_vessel_evaluation WHERE id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	(array)$query->row();

			$created_by	=	$this->Abas->getUser($results['created_by']);
			$results['created_by'] = $created_by['full_name'];
			$results['created_by_signature'] = $created_by['signature'];

			$verified_by	=	$this->Abas->getUser($results['verified_by']);
			$results['verified_by'] = $verified_by['full_name'];
			$results['verified_by_signature'] = $verified_by['signature'];

			$approved_by	=	$this->Abas->getUser($results['approved_by']);
			$results['approved_by'] = $approved_by['full_name'];
			$results['approved_by_signature'] = $approved_by['signature'];

			$company	=	$this->getCompany($results['company_id']);
			$results['company_name'] = $company['name'];
			$results['company_address'] = $company['address'];
			$results['company_contact'] = $company['telephone_no'];

			if($results['WO_number']!=0){
				$results['WO_number']	=	$this->getWO($results['WO_number'])['control_number'];
			}
			
			$vessel =	 $this->getVessel($results['vessel_id']);
			$results['vessel_id'] = $results['vessel_id'];
			$results['vessel_name'] = $vessel['name'];

			$measurements = $this->getVesselMeasurements($results['vessel_id']);
			$results['length_loa'] = $measurements[0]->length_loa;
			$results['breadth']= $measurements[0]->breadth;
			$results['depth']= $measurements[0]->depth;
			$results['gross_tonnage']= $measurements[0]->gross_tonnage;
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getSRMSFDetails( $id = NULL ){
		$sql	=	"SELECT * FROM am_vessel_evaluation_details WHERE vessel_evaluation_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	(array)$query->result();	

			foreach($results as $row){
				if($row->evaluation_item_id){
					$item = $this->Asset_Management_model->getEvaluationItem($row->evaluation_item_id);
					$row->index = $item['item_index'];
					$row->set = $item['item_set'];
					$row->sub_set = $item['item_sub_set'];
					$row->item_name = $item['item_name'];
				}
			}	
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function updateSRMSFStatus( $id = NULL, $status = NULL ){

		$updated_by = $_SESSION['abas_login']['userid'];
		$updated_on = date("Y-m-d H:i:s");

		if($status=='For Approval'){
			$sql 	= "UPDATE am_vessel_evaluation SET status='" . $status . "', verified_by=".$updated_by.", verified_on='".$updated_on."' WHERE id=".$id;
		}elseif($status=='Approved'){
			$sql 	= "UPDATE am_vessel_evaluation SET status='" . $status . "', approved_by=".$updated_by.", approved_on='".$updated_on."' WHERE id=".$id;
		}else{
			$sql 	= "UPDATE am_vessel_evaluation SET status='" . $status . "' WHERE id=".$id;
		}
		$query 	= $this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;

	}

	public function deleteSRMSF( $id = NULL ){

		$sql	=	"DELETE FROM am_vessel_evaluation WHERE id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function deleteSRMSFDetails( $id = NULL ){

		$sql	=	"DELETE FROM am_vessel_evaluation_details WHERE vessel_evaluation_id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function getMTDE( $id = NULL ){

		$sql	=	"SELECT * FROM am_truck_evaluation WHERE id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	(array)$query->row();

			$created_by	=	$this->Abas->getUser($results['created_by']);
			$results['created_by'] = $created_by['full_name'];
			$results['created_by_signature'] = $created_by['signature'];

			$verified_by	=	$this->Abas->getUser($results['verified_by']);
			$results['verified_by'] = $verified_by['full_name'];
			$results['verified_by_signature'] = $verified_by['signature'];

			$approved_by	=	$this->Abas->getUser($results['approved_by']);
			$results['approved_by'] = $approved_by['full_name'];
			$results['approved_by_signature'] = $approved_by['signature'];

			$company	=	$this->getCompany($results['company_id']);
			$results['company_name'] = $company['name'];
			$results['company_address'] = $company['address'];
			$results['company_contact'] = $company['telephone_no'];

			if($results['TRMRF_number']!=0){
				$results['TRMRF_number']	=	$this->getTRMRF($results['TRMRF_number'])['control_number'];
			}

			$truck =	 $this->getTruck($results['truck_id']);
			$results['truckl_id'] = $results['truck_id'];
			$results['plate_number'] = $truck['plate_number'];

			$info = $this->getTruckInfo($results['truck_id']);
			$results['make'] = $info[0]->make;
			$results['model']= $info[0]->model;
			$results['engine_number']= $info[0]->engine_number;
			$results['chassis_number']= $info[0]->chassis_number;
			$results['type']= $info[0]->type;
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getMTDEDetails( $id = NULL ){
		$sql	=	"SELECT * FROM am_truck_evaluation_details WHERE truck_evaluation_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	(array)$query->result();	

			foreach($results as $row){
				if($row->evaluation_item_id){
					$item = $this->Asset_Management_model->getEvaluationItem($row->evaluation_item_id);
					$row->index = $item['item_index'];
					$row->set = $item['item_set'];
					$row->sub_set = $item['item_sub_set'];
					$row->item_name = $item['item_name'];
				}
			}	
		}
		else{
			$results	=	NULL;
		}

		return $results;
	}

	public function updateMTDEStatus( $id = NULL, $status = NULL ){

		$updated_by = $_SESSION['abas_login']['userid'];
		$updated_on = date("Y-m-d H:i:s");

		if($status=='For Approval'){
			$sql 	= "UPDATE am_truck_evaluation SET status='" . $status . "', verified_by=".$updated_by.", verified_on='".$updated_on."' WHERE id=".$id;
		}elseif($status=='Approved'){
			$sql 	= "UPDATE am_truck_evaluation SET status='" . $status . "', approved_by=".$updated_by.", approved_on='".$updated_on."' WHERE id=".$id;
		}else{
			$sql 	= "UPDATE am_truck_evaluation SET status='" . $status . "' WHERE id=".$id;
		}
		$query 	= $this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;

	}

	public function updateScheduleLogStatus( $id = NULL, $status = NULL ){

		$updated_by = $_SESSION['abas_login']['userid'];
		$updated_on = date("Y-m-d H:i:s");

		if($status=='For Approval'){
			$sql 	= "UPDATE am_schedule_logs SET status='" . $status . "', verified_by=".$updated_by.", verified_on='".$updated_on."' WHERE id=".$id;
		}elseif($status=='Approved'){
			$sql 	= "UPDATE am_schedule_logs SET status='" . $status . "', approved_by=".$updated_by.", approved_on='".$updated_on."' WHERE id=".$id;
		}else{
			$sql 	= "UPDATE am_schedule_logs SET status='" . $status . "' WHERE id=".$id;
		}		

		$query 	= $this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;

	}

	public function deleteMTDE( $id = NULL ){

		$sql	=	"DELETE FROM am_truck_evaluation WHERE id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function deleteMTDEDetails( $id = NULL ){

		$sql	=	"DELETE FROM am_truck_evaluation_details WHERE truck_evaluation_id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}



//===============================================================================================================//
//============================== BILL OF MATERIALS =============================================================//

	public function getBOM( $id = NULL ){

		$sql	=	"SELECT * FROM am_bill_of_materials WHERE id=".$id;
		$query	=	$this->db->query($sql);

		if($query){

			$results					=	(array)$query->row();
			$company					=	$this->getCompany($results['company_id']);
			$results['company_name'] 	=   $company['name'];
			$results['company_address'] =   $company['address'];
			$results['company_contact'] =   $company['telephone_no'];

			$created_by	=	$this->Abas->getUser($results['created_by']);
			$results['full_name'] = $created_by['full_name'];
			$results['created_by'] = $created_by['full_name'];
			$results['created_by_signature'] = $created_by['signature'];

			$verified_by	=	$this->Abas->getUser($results['verified_by']);
			$results['verified_by'] = $verified_by['full_name'];
			$results['verified_by_signature'] = $verified_by['signature'];

			$approved_by	=	$this->Abas->getUser($results['approved_by']);
			$results['approved_by'] = $approved_by['full_name'];
			$results['approved_by_signature'] = $approved_by['signature'];

			if($results['bom_type'] == "Vessel"){

				$SRMSF = $this->getSRMSF($results['evaluation_id']);
				$results['vessel_id'] = $SRMSF['vessel_id'];
				$results['vessel_name'] = $SRMSF['vessel_name'];
				$results['WO_number'] = $SRMSF['WO_number'];
				$results['dry_docking_date'] = $SRMSF['dry_docking_date'];
				$results['dry_docking_location'] = $SRMSF['dry_docking_location'];
				$results['length_loa'] = $SRMSF['length_loa'];
				$results['breadth']= $SRMSF['breadth'];
				$results['depth']= $SRMSF['depth'];
				$results['gross_tonnage']= $SRMSF['gross_tonnage'];

				$results['evaluation_form_no'] = $SRMSF['control_number'];

			}elseif($results['bom_type'] == "Truck"){

				$MTDE =	 $this->getMTDE($results['evaluation_id']);
				$results['truck_id'] = $MTDE['truck_id'];
				$results['driver'] = $MTDE['driver'];
				$results['plate_number'] = $MTDE['plate_number'];
				$results['TRMRF_number'] = $MTDE['TRMRF_number'];
				$results['make'] = $MTDE['make'];
				$results['model']= $MTDE['model'];
				$results['engine_number']= $MTDE['engine_number'];
				$results['chassis_number']= $MTDE['chassis_number'];
				$results['type']= $MTDE['type'];

				$results['evaluation_form_no'] = $MTDE['control_number'];
			}
			

		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getBOMTasks( $id = NULL ){

		$sql	=	"SELECT * FROM am_bill_of_materials_tasks WHERE bill_of_materials_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getBOMLabor( $id = NULL ){

		$sql	=	"SELECT * FROM am_bill_of_materials_labor WHERE bill_of_materials_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getBOMSupplies( $id = NULL ){

		$sql	=	"SELECT * FROM am_bill_of_materials_supplies WHERE bill_of_materials_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
			foreach($results as $row){
				if($row->item_id!=0){
					$item = $this->getInventoryItem($row->item_id);
					$row->item_code	=	$item->item_code;
					$row->item_description	=	$item->description.", ".$item->particular;
					$row->item_unit	=	$item->unit;
					$row->warehouse_quantity	=	$item->qty;
					$row->warehouse_unit_cost	=	$item->unit_price;
				}else{
					$row->item_code	=	"-";
					$row->item_unit	=	$row->item_unit_measurement;
					$row->warehouse_quantity	=	"-";
					$row->warehouse_unit_cost	=	"-";
				}
			}
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function getBOMTools( $id = NULL ){

		$sql	=	"SELECT * FROM am_bill_of_materials_tools WHERE bill_of_materials_id=".$id;
		$query	=	$this->db->query($sql);

		if($query){
			$results	=	$query->result();
		}else{
			$results	=	NULL;
		}

		return $results;
	}

	public function updateBOMStatus( $id = NULL, $status = NULL ){

		$updated_by = $_SESSION['abas_login']['userid'];
		$updated_on = date("Y-m-d H:i:s");

		if($status=='For Approval'){
			$sql 	= "UPDATE am_bill_of_materials SET status='" . $status . "', verified_by=".$updated_by.", verified_on='".$updated_on."' WHERE id=".$id;
		}elseif($status=='Approved'){
			$sql 	= "UPDATE am_bill_of_materials SET status='" . $status . "', approved_by=".$updated_by.", approved_on='".$updated_on."' WHERE id=".$id;
		}else{
			$sql 	= "UPDATE am_bill_of_materials SET status='" . $status . "' WHERE id=".$id;
		}		

		$query 	= $this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;

	}

	public function getBOMAmount($bom_id){
		$labor_amount = 0;
		$supplies_amount = 0;

		$bom = $this->getBOM($bom_id);
		$labors = $this->getBOMLabor($bom['id']);
		foreach($labors as $labor){
			$labor_amount = $labor_amount + (($labor->rate_per_day * $labor->quantity)*$labor->days_needed);
		}
		$supplies = $this->getBOMSupplies($bom['id']);
		foreach($supplies as $item){
			$quantity_for_purchase = ((double)$item->quantity)-((double)$item->warehouse_quantity);
			if($quantity_for_purchase<=0){
				$total_cost = ($item->quantity) * ($item->warehouse_unit_cost);
			}else{
				$calc_wh_cost = (double)$item->warehouse_quantity * (double)$item->warehouse_unit_cost;
				$calc_ps_cost = $item->unit_cost * $quantity_for_purchase;
				$total_cost =  $calc_wh_cost + $calc_ps_cost;
			}
        	$supplies_amount = $supplies_amount + $total_cost;
		}

		$result = $labor_amount + $supplies_amount;
		return $result;
	}

	public function deleteBOM( $id = NULL ){

		$sql	=	"DELETE FROM am_bill_of_materials WHERE id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function deleteBOMDetails( $id = NULL ){

		$sql	=	"DELETE FROM am_bill_of_materials_tasks WHERE bill_of_materials_id=".$id;
		$query  =	$this->db->query($sql);

		$sql	=	"DELETE FROM am_bill_of_materials_labor WHERE bill_of_materials_id=".$id;
		$query  =	$this->db->query($sql);

		$sql	=	"DELETE FROM am_bill_of_materials_supplies WHERE bill_of_materials_id=".$id;
		$query  =	$this->db->query($sql);

		$sql	=	"DELETE FROM am_bill_of_materials_tools WHERE bill_of_materials_id=".$id;
		$query  =	$this->db->query($sql);

		if($query){
			$results = TRUE;
		}else{
			$results = FALSE;
		}

		return $results;
	}

	public function deleteScheduleLogTasks( $id = NULL ){

		$sql = "DELETE FROM am_schedule_log_tasks WHERE schedule_log_id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = TRUE;
		}else{
			$result = FALSE;
		}

		return $result;

	}

//================= SCHEDULE LOGS =====================================================================

	public function getScheduleLog($id){
		
		$sql = "SELECT * FROM am_schedule_logs WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){

			$result						=	(array)$query->row();
			$company					=	$this->getCompany($result['company_id']);
			$result['company_name'] 	=   $company['name'];
			$result['company_address'] 	=   $company['address'];
			$result['company_contact'] 	=   $company['telephone_no'];

			$bom = $this->Asset_Management_model->getBOM($result['bill_of_materials_id']);
			$result['start_date_of_repair'] = $bom['start_date_of_repair'];

			if($result['type']=="Vessel"){
				$eval = $this->Asset_Management_model->getSRMSF($bom['evaluation_id']);
				if($eval['WO_number']==0){
					$report_form_no = "N/A";
				}else{
					$report_form_no = $eval['WO_number'];
				}
				$result['report_form_no'] = $report_form_no;
				$result['evaluation_form_id'] = $bom['evaluation_id'];
				$result['evaluation_form_no'] = $eval['control_number'];
				$result['bill_of_materials_no'] = $bom['control_number'];
				$result['asset_name'] = $this->Asset_Management_model->getVessel($result['asset_id'])['name'];
				$result['dry_docking_date'] = $eval['dry_docking_date'];
				$result['dry_docking_location'] = $eval['dry_docking_location'];
			
			}elseif($result['type']=="Truck"){
				$eval = $this->Asset_Management_model->getMTDE($bom['evaluation_id']);
				if($eval['TRMRF_number']==0){
					$report_form_no = "N/A";
				}else{
					$report_form_no = $eval['TRMRF_number'];
				}
				$result['report_form_no'] = $report_form_no;
				$result['evaluation_form_no'] = $eval['control_number'];
				$result['bill_of_materials_no'] = $bom['control_number'];
				$result['asset_name'] = $this->Asset_Management_model->getTruck($result['asset_id'])['plate_number'];
				$result['driver'] = $eval['driver'];
				$result['make_model'] = $eval['make']. "-" . $eval['model'];
				$result['engine_number'] = $eval['engine_number'];
				$result['chassis_number'] = $eval['chassis_number'];
				
			}

			$created_by	=	$this->Abas->getUser($result['created_by']);
			$result['created_by'] = $created_by['full_name'];

			$verified_by	=	$this->Abas->getUser($result['verified_by']);
			$result['verified_by'] = $verified_by['full_name'];

			$approved_by	=	$this->Abas->getUser($result['approved_by']);
			$result['approved_by'] = $approved_by['full_name'];

			if(isset($result['updated_by'])){
				$updated_by	=	$this->Abas->getUser($result['updated_by']);
				$result['updated_by_full_name'] = $updated_by['full_name'];
			}
			

		}else{
			$result = NULL;
		}

		return $result;
	}
	public function getScheduleLogs($for){
		
		
		$sql = "SELECT * FROM am_schedule_logs WHERE status<>'Cancelled' AND type='".$for."'";
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getScheduleLogTasks( $schedule_log_id ){
		
		$sql = "SELECT * FROM am_schedule_log_tasks WHERE schedule_log_id=".$schedule_log_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result_array();

			foreach($result as $ctr=>$row) {

				$sql2 = "SELECT * FROM am_bill_of_materials_tasks WHERE id=".$row['task_id'];
				$query2 = $this->db->query($sql2);
				
				if($query2){
					$task = $query2->row();

					$result[$ctr]['task_number'] = $task->task_number;
					$result[$ctr]['scope_of_work'] = $task->scope_of_work;
					$result[$ctr]['estimated_time_to_complete'] = $task->estimated_time_to_complete;
				}
				
			}

		}else{
			$result = NULL;
		}

		return $result;

	}

	public function getScheduleLogBOM($bom_id){
		$sql = "SELECT * FROM am_schedule_logs WHERE bill_of_materials_id=".$bom_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

//=====================================================================================================

	public function getEvaluationFormNumber( $type = NULL, $company_id = NULL, $id = "" ){

		if($type=="Vessel"){
			if($id!=""){
				$sql	=	"SELECT id,control_number,company_id,vessel_id FROM am_vessel_evaluation WHERE (status='Final' OR status='Approved') AND company_id=".$company_id." AND id=".$id;
			}else{
				$sql	=	"SELECT id,control_number,company_id,vessel_id FROM am_vessel_evaluation WHERE (status='Final' OR status='Approved') AND company_id=".$company_id;
			}
		}elseif($type=="Truck"){
			if($id!=""){
				$sql	=	"SELECT id,control_number,company_id,truck_id FROM am_truck_evaluation WHERE (status='Final' OR status='Approved') AND company_id=".$company_id." AND id=".$id;
			}else{
				$sql	=	"SELECT id,control_number,company_id,truck_id FROM am_truck_evaluation WHERE (status='Final' OR status='Approved') AND company_id=".$company_id;
			}
		}
		$query	=	$this->db->query($sql);

		if($query){
			
			if($id!=""){
				$results = $query->row();
				$results->maintenance_form = ($type=="Vessel")?"SRMSF No.":"MTDE No.";
				if($type=="Vessel"){
						$results->asset_name = $this->Asset_Management_model->getVessel($results->vessel_id)['name'];	
				}elseif($type=="Truck"){
						$results->asset_name = "Plate No. ".$this->Asset_Management_model->getTruck($results->truck_id)['plate_number'];
				}
			}else{
				$results = $query->result();
				foreach($results as $row){

					if($type=="Vessel"){
						$row->asset_name = $this->Asset_Management_model->getVessel($row->vessel_id)['name'];	
					}elseif($type=="Truck"){
						$row->asset_name = "Plate No. ".$this->Asset_Management_model->getTruck($row->truck_id)['plate_number'];
					}
					
					$row->maintenance_form = ($type=="Vessel")?"SRMSF No.":"MTDE No.";
				}
			}
		}
		else{
			$results = NULL;
		}
		return $results;
	}

	public function getBOMByCompany( $type = NULL, $company_id = NULL, $id = "" ){

		if($type=="Vessel"){
			if($id!=""){
				//$sql	=	"SELECT id,control_number,company_id,(SELECT vessel_id FROM am_vessel_evaluation WHERE am_vessel_evaluation.id=am_bill_of_materials.evaluation_id) as vessel_id FROM am_bill_of_materials WHERE (status='Final' OR status='Approved') AND company_id=".$company_id." AND id=".$id;
				$sql	=	"SELECT id,control_number,company_id,(SELECT vessel_id FROM am_vessel_evaluation WHERE am_vessel_evaluation.id=am_bill_of_materials.evaluation_id) as vessel_id FROM am_bill_of_materials WHERE status!='Cancelled' AND company_id=".$company_id." AND id=".$id;
			}else{
				//$sql	=	"SELECT id,control_number,company_id,(SELECT vessel_id FROM am_vessel_evaluation WHERE am_vessel_evaluation.id=am_bill_of_materials.evaluation_id) as vessel_id FROM am_bill_of_materials WHERE (status='Final' OR status='Approved') AND company_id=".$company_id." AND bom_type='Vessel' ";
				$sql	=	"SELECT id,control_number,company_id,(SELECT vessel_id FROM am_vessel_evaluation WHERE am_vessel_evaluation.id=am_bill_of_materials.evaluation_id) as vessel_id FROM am_bill_of_materials WHERE status!='Cancelled' AND company_id=".$company_id." AND bom_type='Vessel' ";
			}
		}elseif($type=="Truck"){
			if($id!=""){
				$sql	=	"SELECT id,control_number,company_id,(SELECT truck_id FROM am_truck_evaluation WHERE am_truck_evaluation.id=am_bill_of_materials.evaluation_id) as truck_id FROM am_bill_of_materials WHERE (status='Final' OR status='Approved') AND company_id=".$company_id." AND id=".$id;
			}else{
				$sql	=	"SELECT id,control_number,company_id,(SELECT truck_id FROM am_truck_evaluation WHERE am_truck_evaluation.id=am_bill_of_materials.evaluation_id) as truck_id FROM am_bill_of_materials WHERE (status='Final' OR status='Approved') AND company_id=".$company_id." AND bom_type='Truck' ";
			}
		}
		$query	=	$this->db->query($sql);

		if($query){
			
			if($id!=""){
				$results = $query->row();
				
				if($type=="Vessel"){
						$results->asset_name = $this->Asset_Management_model->getVessel($results->vessel_id)['name'];	
				}elseif($type=="Truck"){
						$results->asset_name = "Plate No. ".$this->Asset_Management_model->getTruck($results->truck_id)['plate_number'];
				}
			}else{
				$results = $query->result();
				foreach($results as $row){

					if($type=="Vessel"){
						$row->asset_name = $this->Asset_Management_model->getVessel($row->vessel_id)['name'];	
					}elseif($type=="Truck"){
						$row->asset_name = "Plate No. ".$this->Asset_Management_model->getTruck($row->truck_id)['plate_number'];
					}
				}
			}
		}
		else{
			$results = NULL;
		}
		return $results;
	}

	public function getItemBySearch($keyword){
			

			$search = $keyword;
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT * FROM inventory_items WHERE description LIKE '%".$search."%' AND stat=1 ORDER BY item_code";
			$query	=	$this->db->query($sql);

			if($query) {
				if($query->row()) {
					$query	=	$query->result_array();
					$result	=	array();
					foreach($query as $ctr=>$item) {
						
						$result[$ctr]['label']	=	$item['description'].", ".$item['particular'];
						
						if(isset($item['id'])) {
							$result[$ctr]['item_id']	=	$item['id'];
						}
						if(isset($item['unit'])) {
							$result[$ctr]['unit_measurement']	=	$item['unit'];
						}
						if(isset($item['qty'])) {
							$result[$ctr]['quantity']	=	$item['qty'];
						}
						if(isset($item['unit_price'])) {
							$result[$ctr]['unit_cost']	=	$item['unit_price'];
						}
						if(isset($item['unit_price'])) {
							$result[$ctr]['unit_cost']	=	$item['unit_price'];
						}
						if(isset($item['item_code'])) {
							$result[$ctr]['label']	=	$item['item_code']." - ".$result[$ctr]['label'];
						}
					}

					return  $result;

				}
			}

	}

	public function getInventoryItem( $id = NULL){

		$sql = "SELECT * FROM inventory_items WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function checkMaintenanceFormIfUsed( $evaluation_form_id, $evaluation_form_type ){

		$sql	=	"SELECT evaluation_id FROM am_bill_of_materials WHERE bom_type='".$evaluation_form_type."' AND status<>'Cancelled' AND evaluation_id=".$evaluation_form_id;
		$query = $this->db->query($sql);

		if($query){
			$count = count($query->result());
			
			if($count>=1){
				$result = TRUE;
			}
			else{
				$result = FALSE;
			}
		}
		else{
			$result = FALSE;
		}
		return $result;
	}

	public function getScheduleLogOverallPercentage($schedule_log_id){

		$sql = "SELECT * FROM am_schedule_log_tasks WHERE schedule_log_id=".$schedule_log_id;
		$query = $this->db->query($sql);

		if($query){
			$schedule_logs = $query->result();
			$overall_percentage = 0;
			$ctr = 0;
			foreach($schedule_logs as $row){
				$overall_percentage = $overall_percentage + $row->percentage;
				$ctr++;
			}

			$result =  ($overall_percentage/$ctr);
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getFixedAsset($id){
		$sql = "SELECT * FROM am_fixed_assets WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getFixedAssets($company_id=NULL){
		if($company_id!=NULL){
			$sql = "SELECT * FROM am_fixed_assets WHERE stat=1 AND company_id=".$company_id." AND include_lapsing=1";
		}else{
			$sql = "SELECT * FROM am_fixed_assets WHERE stat=1";
		}
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getAccountableForIssuedAsset($asset_id){
		$sql = "SELECT * FROM am_fixed_asset_accountability_details WHERE status='Issued' AND fixed_asset_id=".$asset_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getAccountabilityForm($id){
		$sql = "SELECT * FROM am_fixed_asset_accountability WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();

			$company	=	$this->getCompany($result->company_id);
			$result->company_name = $company['name'];
			$result->company_address = $company['address'];
			$result->company_contact = $company['telephone_no'];

			$result->requested_by_id = $result->requested_by;

			$requested_by = $this->Abas->getEmployee($result->requested_by);
			$result->requested_by = ucwords(strtolower($requested_by['full_name']));

			$position = $this->Abas->getPosition($requested_by['position']);
			$result->position = $position->name;

			$department = $this->Abas->getDepartment($requested_by['department']);
			$result->department = $department->name;

			$created_by = $this->Abas->getUser($result->created_by);
			$result->created_by = $created_by['full_name'];
			$result->created_by_signature = $created_by['signature'];

			$verified_by = $this->Abas->getUser($result->verified_by);
			$result->verified_by = $created_by['full_name'];
			$result->verified_by_signature = $verified_by['signature'];

			$approved_by = $this->Abas->getUser($result->approved_by);
			$result->approved_by = $approved_by['full_name'];
			$result->approved_by_signature = $approved_by['signature'];

		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getAccountabilityFormDetails($accountability_id){
		$sql = "SELECT * FROM am_fixed_asset_accountability_details WHERE accountability_id=".$accountability_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$x) {
				$fixed_asset = $this->Asset_Management_model->getFixedAsset($result[$ctr]->fixed_asset_id);
				if($fixed_asset->item_id!=0){
					$item = $this->Inventory_model->getItem($fixed_asset->item_id);
					$result[$ctr]->item_id = $item[0]['id'];
					$result[$ctr]->item_name = $item[0]['item_name'];
					$result[$ctr]->item_particular = $item[0]['particular'];
					$location1 = $item[0]['location'];
					$location2 = $this->Abas->getVessel($fixed_asset->location);
					$result[$ctr]->location = $location2->name." - ".$location1;
				}else{
					$result[$ctr]->item_id = 0;
					$result[$ctr]->item_name = $fixed_asset->item_name;
					$result[$ctr]->item_particular = $fixed_asset->particular;
					$location2 = $this->Abas->getVessel($fixed_asset->location);
					$result[$ctr]->location = $location2->name;
				}
				$result[$ctr]->asset_code = $fixed_asset->asset_code."-".$fixed_asset->control_number;
				$received_by = $this->Abas->getUser($result[$ctr]->received_by);
				$result[$ctr]->received_by = $received_by['full_name'];
			}
			
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getAccountabilityFormDetail($id){
		$sql = "SELECT * FROM am_fixed_asset_accountability_details WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getDisposalSlip($id){
		$sql = "SELECT * FROM am_fixed_asset_disposals WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();

			$company	=	$this->getCompany($result->company_id);
			$result->company_name = $company['name'];
			$result->company_address = $company['address'];
			$result->company_contact = $company['telephone_no'];

			$result->requested_by_id = $result->requested_by;

			$requested_by = $this->Abas->getEmployee($result->requested_by);
			$result->requested_by = ucwords(strtolower($requested_by['full_name']));

			$position = $this->Abas->getPosition($requested_by['position']);
			$result->position = $position->name;

			$department = $this->Abas->getDepartment($requested_by['department']);
			$result->department = $department->name;

			$result->checked_by_id = $result->checked_by;
			$checked_by = $this->Abas->getEmployee($result->checked_by);
			$result->checked_by = ucwords(strtolower($checked_by['full_name']));

			$checked_by_position = $this->Abas->getPosition($checked_by['position']);
			$result->checked_by_position = $checked_by_position->name;

			$checked_by_department = $this->Abas->getDepartment($checked_by['department']);
			$result->checked_by_department = $checked_by_department->name;

			$created_by = $this->Abas->getUser($result->created_by);
			$result->created_by = $created_by['full_name'];
			$result->created_by_signature = $created_by['signature'];

			$verified_by = $this->Abas->getUser($result->verified_by);
			$result->verified_by = $verified_by['full_name'];
			$result->verified_by_signature = $verified_by['signature'];

			$approved_by = $this->Abas->getUser($result->approved_by);
			$result->approved_by = $approved_by['full_name'];
			$result->approved_by_signature = $approved_by['signature'];

		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getDisposalSlipDetails($disposal_id){
		$sql = "SELECT * FROM am_fixed_asset_disposal_details WHERE disposal_id=".$disposal_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$x) {
				$fixed_asset = $this->Asset_Management_model->getFixedAsset($result[$ctr]->asset_id);
				$item = $this->Inventory_model->getItem($fixed_asset->item_id);
				$result[$ctr]->item_id = $item[0]['id'];
				$result[$ctr]->asset_code = $fixed_asset->asset_code."-".$fixed_asset->control_number;
				$result[$ctr]->item_name = $item[0]['item_name'];
				$result[$ctr]->item_particular = $item[0]['particular'];
				$result[$ctr]->original_cost = $fixed_asset->purchase_cost;
				$result[$ctr]->date_purchased = $fixed_asset->date_acquired;
			}
			
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getProjectOrders($schedule_log_id,$type){

		$schedule_logs = $this->Asset_Management_model->getScheduleLog($schedule_log_id);

		if($type=='Purchase Orders'){
			$sql_po = "SELECT inventory_po.* FROM inventory_po INNER JOIN inventory_requests ON inventory_po.request_id = inventory_requests.id WHERE inventory_requests.reference_number='".$schedule_logs['reference_number']."'";
			$query_po = $this->db->query($sql_po);
			if($query_po){
				$result = $query_po->result();
			}else{
				$result = NULL;
			}
		}

		if($type=='Job Orders'){
			$sql_jo = "SELECT inventory_job_orders.* FROM inventory_job_orders INNER JOIN inventory_requests ON inventory_job_orders.request_id = inventory_requests.id WHERE inventory_requests.reference_number='".$schedule_logs['reference_number']."'";
			$query_jo = $this->db->query($sql_jo);
			if($query_jo){
				$result = $query_jo->result();
			}else{
				$result = NULL;
			}
		}

		return $result;
	}
	public function getProjectReferencesByVessel($vessel_id){
		$sql = "SELECT * FROM am_schedule_logs WHERE asset_id=".$vessel_id." AND type='Vessel'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$row){
				$result[$ctr]->approved_on = date('F - Y',strtotime($result[$ctr]->approved_on));
			}
		}else{
			$result = NULL;
		}
		return $result;
	}
	
}
/* End of file Asset_Management_model.php */
/* Location: ./application/models/Asset_Management_model.php */
?>