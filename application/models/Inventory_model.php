<?php
Class Inventory_model extends CI_Model{
	public function getItem($id=''){
		if($id=="") return null;
		if(!is_numeric($id)) return null;
		$sql="SELECT *, description as item_name, CONCAT(description,', ',brand,' | ',particular) as description FROM inventory_items WHERE id=".$id;
		$query=$this->db->query($sql);
		if($query==false) return false;
		if(!$query->row()) return false;
		return $query->result_array();
	}
	public function getItemQty($id=''){
		if($id=="") return null;
		if(!is_numeric($id)) return null;
		$sql="SELECT item_id, tayud_qty, mkt_qty, nra_qty FROM `inventory_items` AS i INNER JOIN inventory_location AS l ON i.id=l.item_id WHERE l.item_id=".$id;
		$query=$this->db->query($sql);
		if($query==false) return false;
		if(!$query->row()) return false;
		return $query->result_array();
	}
	public function getItems($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_items WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM inventory_items WHERE stat=1 ORDER BY description ASC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getSuppliers($id=''){
		if($id!=''){
			$sql="SELECT * FROM suppliers WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM suppliers ORDER BY name";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getVessels($id=''){
		if($id!=''){
			$sql="SELECT * FROM vessels WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM vessels ORDER BY name";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getInventoryLocation($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_location_name WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM inventory_location_name ORDER BY location_name";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getItemRequest($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_items WHERE qty <=reorder_level and id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_items` WHERE qty <=reorder_level ORDER BY description, req ASC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getDelivery($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_deliveries WHERE id=".$id;
			$query=$this->db->query($sql);
		}
		if(!$query){ return false; }
		return $query->result_array();
	}
	public function getDeliveries($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_deliveries WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_deliveries` WHERE stat=0 ORDER BY delivery_date DESC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getDeliveryDetail($id){
		$sql="SELECT * FROM inventory_delivery_details WHERE id=".$id;
		$query=$this->db->query($sql);
		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getDeliveryDetails($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_delivery_details WHERE delivery_id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_delivery_details` WHERE stat=0 ORDER BY delivery_id DESC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getPos($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_po WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_po` WHERE stat=0 ORDER BY po_date DESC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getInventoryTransfer($id='', $from='',$to=''){
		$return= false;
		$sql="SELECT * FROM inventory_transfer WHERE 1=1";
		if($from!=''){
			$sql.=" AND from_location=".$from ;
		}
		if($to!=''){
			$sql.=" AND to_location=".$to ;
		}
		if($id!=''){
			$sql.=" AND id=".$id ;
		}
		$sql .=" ORDER BY is_received, transfer_date DESC";
		$query=$this->db->query($sql);
		$return=$query->result_array();
		return $return;
	}
	public function getInventoryTransferDetails($id=''){
		$return= false;
		if($id!=''){
			$sql="SELECT * FROM inventory_transfer_details WHERE id=".$id ;
			$query=$this->db->query($sql);
			$return=$query->result_array();
		}
		return $return;
	}
	public function getTransfers($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_transfer WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_transfer` WHERE stat=0 ORDER BY transfer_date DESC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getReturns($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_return WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_return` WHERE stat=0 ORDER BY return_date DESC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getTransfer($id=''){
		$ret=FALSE;
		if($id!=''){
			$sql="SELECT * FROM inventory_transfer WHERE id=".$id;
			$query=$this->db->query($sql);
			$ret=$query->result_array();
		}
		return $ret;
	}
	public function getTransferDetails($id=''){
		$ret=FALSE;
		if($id!=''){
			$sql="SELECT * FROM inventory_transfer_details WHERE transfer_id=".$id;
			$query=$this->db->query($sql);
			$ret=$query->result_array();
		}
		return $ret;
	}
	public function getTransferReceiptDetails($id=''){
		$ret=FALSE;
		if($id!=''){
			$sql="SELECT * FROM inventory_transfer_receipt_details WHERE transfer_id=".$id;
			$query=$this->db->query($sql);
			$ret=$query->result_array();
		}
		return $ret;
	}
	public function getTransferReceiptByID($id=''){
		$ret=FALSE;
		if($id!=''){
			$sql="SELECT * FROM inventory_transfer_receipt_details WHERE id=".$id;
			$query=$this->db->query($sql);
			$ret=$query->result_array();
		}
		return $ret;
	}
	public function checkTransferReceiptUnreceived($id){
		$sql = "SELECT * FROM inventory_transfer_receipt_details WHERE id=".$id. " AND received_by=0";
		$query=$this->db->query($sql);
		if($query){
			$result = $query->result_array();	
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getReturnDetails($id=''){
		$ret=FALSE;
		if($id!=''){
			$sql="SELECT * FROM inventory_return_details WHERE return_id=".$id;
			$query=$this->db->query($sql);
			$ret=$query->result_array();
		}
		return $ret;
	}
	public function getInventoryReturn($id='', $from='',$to='') {
		$return= false;
		$sql="SELECT * FROM inventory_return WHERE 1=1";
		if($from!=''){
			$sql.=" AND from_location=".$from ;
		}
		if($to!=''){
			$sql.=" AND to_location=".$to ;
		}
		if($id!=''){
			$sql.=" AND id=".$id ;
		}
		$sql.=" ORDER BY created_date DESC";
		$query=$this->db->query($sql);
		$return=$query->result_array();
		return $return;
	}
	public function getInventoryReturnDetails($id=''){
		$return= false;
		if($id!=''){
			$sql="SELECT * FROM inventory_return_details WHERE id=".$id ;
			$query=$this->db->query($sql);
			$return=$query->result_array();
		}
		return $return;
	}
	public function getRequests($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_request WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_request` WHERE stat=0 ORDER BY request_date DESC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getIssuances($id=''){
		if($id!=''){
			$sql="SELECT * FROM inventory_issuance WHERE id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_issuance` WHERE stat=0 ORDER BY issue_date DESC";
		}
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getIssuanceDetails($id=''){

		if($id!=''){
			$sql="SELECT * FROM inventory_issuance_details WHERE issuance_id=".$id;
		}
		else{
			$sql="SELECT * FROM `inventory_issuance_details` WHERE stat=0 ORDER BY issuance_id DESC";
		}
		$query=$this->db->query($sql);
		if($query){
			$result=$query->result_array();
			foreach($result as $ctr=>$row) {
				$item=$this->getItem($result[$ctr]['item_id']);
				$result[$ctr]['item_code']=$item[0]['item_code'];
				$result[$ctr]['item_description']=$item[0]['item_name'].",".$item[0]['brand']." ".$item[0]['particular'];
				$result[$ctr]['item_unit_price']=$item[0]['unit_price']; 
			}
		}
		else{
			$result=NULL;
		}
		return $result;
	}
	public function getTransactionHistory($type,$date_from,$date_to,$filter="",$location=""){
			if($type=="issuance"){
				$table="inventory_issuance";
				$date_field="issue_date";
				$loc_field="from_location";
				$filter_field="vessel_id";
			}
			elseif($type=="delivery"){
				$table="inventory_deliveries";
				$date_field="tdate";
				$loc_field="location";
				$filter_field="supplier_id";
			}
			elseif($type=="transfer"){
				$table="inventory_transfer";
				$date_field="transfer_date";
				$loc_field="from_location";
				$filter_field="transfered_by";
			}
			elseif($type=="return"){
				$table="inventory_return";
				$date_field="return_date";
				$loc_field="return_to";
				$filter_field="return_from";
			}
			if($date_from!="" && $date_to!=""){
				if($filter=="" && $location!=""){
					$sql="SELECT * FROM " . $table . " WHERE " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . "' AND " . $loc_field . "='" . $location . "'";
				}
				if($filter!="" && $location!=""){
					$sql="SELECT * FROM " . $table . " WHERE " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . "' AND " . $filter_field . "='" . $filter . "' AND " . $loc_field . "='" . $location . "'";
				}
				if($filter=="" && $location==""){
					$sql="SELECT * FROM " . $table . " WHERE " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
				}
				if($filter!="" && $location==""){
					$sql="SELECT * FROM " . $table . " WHERE " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . "' AND " . $filter_field . "='" . $filter . "'";
				}
			}
			elseif($date_from=="" && $date_to==""){
				if($filter=="" && $location!=""){
					$sql="SELECT * FROM " . $table . " WHERE " . $loc_field . "='" . $location . "'";
				}
				if($filter!="" && $location!=""){
					$sql="SELECT * FROM " . $table . " WHERE " . $loc_field . "='" . $location . "' AND " . $filter_field . "='" . $filter . "'";
				}
				if($filter=="" && $location==""){
					$sql="SELECT * FROM " . $table;
				}
				if($filter!="" && $location==""){
					$sql="SELECT * FROM " . $table . " WHERE " . $filter_field . "='" . $filter . "'";
				}
			}
			if($date_from!="" && $date_to=="" || $date_from=="" && $date_to!=""){
				$sql=null;
			}
			else{
				$query=$this->db->query($sql);
				if($query){
					$data['history_main']=$query->result_array();
				}
			}
				$data['history_type']=$type;
				$data['date_from']=$date_from;
				$data['date_to']=$date_to;
				$data['date_to']=$date_to;
				$data['filter']=$filter;
				return $data;
	}
	public function getVesselsByCompany($company_id) {
		$ret=null;
		$query=$this->db->query("SELECT id, name, company FROM vessels WHERE status='Active' AND company=".$company_id." ORDER BY name ASC");
		if($query) {
			$ret=$query->result();
			 if($company_id==1) {
				$ret[]=array('id'=>99999, 'name'=>'Makati Office', 'company'=>1);
				$ret[]=array('id'=>99998, 'name'=>'Cebu Office', 'company'=>1);
				$ret[]=array('id'=>99997, 'name'=>'Tacloban Office', 'company'=>1);
				$ret[]=array('id'=>99996, 'name'=>'Maintenance', 'company'=>1);
				$ret[]=array('id'=>99995, 'name'=>'Tayud Office', 'company'=>1);
				$ret[]=array('id'=>99993, 'name'=>'Machine Shop', 'company'=>1);
				$ret[]=array('id'=>99990, 'name'=>'Crane', 'company'=>1);
			}
			if($company_id==5){
				$ret[]=array('id'=>99994, 'name'=>'Avega Trucking', 'company'=>5);
			}
			if($company_id==4){
				$ret[]=array('id'=>99992, 'name'=>'Consolacion, Tayud (Ligaya Maritime Ventures Corp.)', 'company'=>4);
			}
			if($company_id==11){
				$ret[]=array('id'=>99991, 'name'=>'Consolacion, Tayud (Tayud Shipworks Inc.)', 'company'=>11);
			}
			if($company_id==9){
				$ret[]=array('id'=>99989, 'name'=>'Importation (Phil. Commercial Tramp Shipping Corp.)', 'company'=>9);
			}
		}
		else {
			$ret=false;
		}
		return $ret;
	}
	public function getUniqueValuesInArray($array,$column){
		$temp=array();
		foreach($array as $element){
			$temp[]=$element[$column];
		}
		$result=array_unique($temp);
		return $result;
	}
	public function getUnits(){
		$sql="SELECT * FROM inventory_unit";
		$query=$this->db->query($sql);
		return $query->result_array();

	}
	public function getCategory($id){
		$sql = "SELECT * FROM inventory_category WHERE id=".$id;
		$query=$this->db->query($sql);
		return $query->row();
	}
	public function getCategories(){
		$sql="SELECT * FROM inventory_category WHERE stat=1 AND parent=0";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function getSubCategories(){
		$sql="SELECT * FROM inventory_category WHERE parent !=0 ORDER BY category ASC";
		$query=$this->db->query($sql);
		return $query->result_array();

	}
	public function getPO($id=''){
		$ret=null;
		if($id!=''){
			$sql="SELECT * FROM inventory_po WHERE id=".$id;
			$query=$this->db->query($sql);
			$ret= $query->result_array();
		}
		return $ret;
	}
	public function getPODetail($id=''){
		$ret=null;
		if($id!=''){
			$sql="SELECT * FROM inventory_po_details WHERE po_id=".$id;
			$query=$this->db->query($sql);
			$ret= $query->result_array();
		}
		return $ret;
	}
	public function getPOItems($id=''){
		$sql = "SELECT * FROM inventory_po_details WHERE po_id=".$id;
		$query=$this->db->query($sql);
		if($query){
			$result = $query->result_array();
			foreach($result as $ctr=>$row) {
				$item=$this->getItem($result[$ctr]['item_id']);
				$result[$ctr]['item_code']=$item[0]['item_code'];
				$result[$ctr]['item_description']=$item[0]['description'];
				$result[$ctr]['item_particular']=$item[0]['particular']; 
			}
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getDeliveryInfoByVoucherId($id=''){
		$ret=null;
		if($id!=''){
			$sql="SELECT * FROM inventory_deliveries where voucher_id=".$id;
			$query=$this->db->query($sql);
			$ret= $query->result_array();
		}
		return $ret;
	}
	public function getAccountingEntry($id='',$ref_table='') {
		$ret=false;
		if($id!=''){
			$sql="SELECT *, t.transaction_id as tid, t.id as jid FROM ac_transaction_journal AS t INNER JOIN ac_accounts AS a ON t.coa_id=a.id WHERE reference_id=$id AND reference_table='".$ref_table."'";
			$query=$this->db->query($sql);
			$ret= $query->result_array();
		}
		return $ret;
	}
	public function getAPForClearing() {
			$sql="SELECT *, j.id as jid FROM ac_transaction_journal AS j INNER JOIN inventory_deliveries AS d ON j.reference_id=d.id WHERE coa_id=".AP_CLEARING." and j.reconciling_id IS NULL";
			$query=$this->db->query($sql);
			if(!$query){ return false; };
			return	$query->result_array();
	}
	public function getAllItems($searchstring="", $limit="", $offset="", $order="", $sort="") {
		/*
		 *
		 * Creates a JSON array formatted to the bootstrap table
		 *
		 */
		$tablefields	=$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='inventory_items' AND TABLE_SCHEMA='".DBNAME."'");
		$tablefields	=$tablefields->result();
		if($limit!="") {
			if(is_numeric($limit)) {
				$limit=", ".$limit;
			}
		}
		if($offset!="") {
			if(is_numeric($offset)) {
				$offset="LIMIT ".$offset;
			}
		}
		if($order!="") {
			if(strtolower($order)==='asc' || strtolower($order)==='desc') {
				if($sort=="company_name") $sort="company_id";
				if($sort=="full_name") $sort="last_name";
				$order="ORDER BY ".($sort!=""?"".$sort:"i.id")." ".$order;
			}
		}
		$searchfields="";
		if($searchstring!="") {
			$searchfields	.="AND `i`.`description` LIKE '%".$searchstring."%' ";
		}
		$sql="
			SELECT
				DISTINCT(i.id),
				i.*,
				c.category AS category_name,
				q.tayud_qty AS tayud_quantity,
				q.nra_qty AS nra_quantity,
				q.mkt_qty AS makati_quantity
			FROM inventory_items AS i
			JOIN inventory_category AS c
				ON c.id=i.category
			JOIN inventory_location AS q
				ON q.item_id=i.id
			WHERE i.stat=1
			$searchfields $order $offset $limit
		";
		$total="
			SELECT
				i.*
			FROM inventory_items AS i
			WHERE i.stat=1
			$searchfields
		";
		$all=$this->db->query($sql);
		$total=$this->db->query($total);
		$all=$all->result_array();

		if(!empty($all)) {
			foreach($all as $ctr=>$a) {

			}
			$data=array("total"=>count($total->result_array()),"rows"=>$all); // creates array accdg to bootstrap tables
		}
		else {
			$data=false;
		}
		return $data;
	}
	public function getNoticeOfDiscrepancy($id){
		if(is_numeric($id)){
			$sql = "SELECT * FROM inventory_notice_of_discrepancy WHERE id=".$id;
			$query = $this->db->query($sql);
			if($query){
				$result = $query->row();
				$company = $this->Abas->getCompany($result->company_id);
				$result->company_name = $company->name;
				$result->company_address = $company->address;
				$result->company_contact = $company->telephone_no;
				$supplier = $this->Abas->getSupplier($result->supplier_id);
				$result->supplier_name = $supplier['name'];
				$user = $this->Abas->getUser($result->created_by);
				$result->full_name = $user['full_name'];

				$verified_by = $this->Abas->getUser($result->verified_by);
				$result->verified_by_full_name = $verified_by['full_name'];

				$approved_by1 = $this->Abas->getUser($result->level1_approved_by);
				$result->approved_by_level1_full_name = $approved_by1['full_name'];

				$approved_by2 = $this->Abas->getUser($result->level2_approved_by);
				$result->approved_by_level2_full_name = $approved_by2['full_name'];

				$approved_by3 = $this->Abas->getUser($result->level3_approved_by);
				$result->approved_by_level3_full_name = $approved_by3['full_name'];

				$result->po = $this->Inventory_model->getPO($result->purchase_order_id);
			}else{
				$result = NULL;
			}
		}else{
			$sql = "SELECT * FROM inventory_notice_of_discrepancy";
			$query = $this->db->query($sql);
			if($query){
				$result = $query->result_array();
				foreach($result as $ctr=>$row) {
					$company = $this->Abas->getCompany($result->company_id);
					$result[$ctr]['company_name'] = $company->name;
					$result[$ctr]['company_address'] = $company->address;
					$result[$ctr]['company_contact'] = $company->telephone_no;
					$supplier = $this->Abas->getSupplier($result->supplier_id);
					$result[$ctr]['supplier_name'] = $supplier['name'];
					$user = $this->Abas->getUser($result->created_by);
					$result[$ctr]['full_name'] = $user['full_name'];
					$result[$ctr]['po'] = $this->Inventory_model->getPO($result->purchase_order_id);
				}
			}else{
				$result = NULL;
			}
		}
		
		return $result;
	}
	public function getNoticeOfDiscrepancyDetails($nod_id){
		$sql = "SELECT * FROM inventory_notice_of_discrepancy_details WHERE notice_of_discrepancy_id=".$nod_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
			foreach($result as $ctr=>$row) {
				$item=$this->getItem($result[$ctr]['item_id']);
				$result[$ctr]['item_code']=$item[0]['item_code'];
				$result[$ctr]['item_name']=$item[0]['item_name'];
				$result[$ctr]['item_description']=$item[0]['description'];
				$result[$ctr]['item_particular']=$item[0]['brand']." ".$item[0]['particular'];
				$result[$ctr]['item_unit']=$item[0]['unit'];
			}
		}else{
			$result = NULL;
		}
		return $result;	
	}
	public function getNoticeOfDiscrepancyByPO($po_id){
		$sql = "SELECT * FROM inventory_notice_of_discrepancy WHERE purchase_order_id=".$po_id. " AND status='Approved'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;	
	}
	public function getGatePass($issuance_id){
		$sql = "SELECT * FROM inventory_gatepass WHERE issuance_id=".$issuance_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;	
	}
	public function getGatePassByTransferID($transfer_id){
		$sql = "SELECT * FROM inventory_gatepass WHERE stock_transfer_receipt_id=".$transfer_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;	
	}
	public function getInventoryAudit($audit_id){
		$sql = "SELECT * FROM inventory_audit WHERE id=".$audit_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
			$company = $this->Abas->getCompany($result->company_id);
			$inventory = $this->Abas->getItemCategory($result->type_of_inventory);
			$result->category_name = $inventory->category;
			$result->company_name = $company->name;
			$result->company_address = $company->address;
			$result->company_contact = $company->telephone_no;
			$created_by = $this->Abas->getUser($result->created_by);
			$result->created_by = $created_by['full_name'];
			$result->created_by_signature = $created_by['signature'];
			$verified_by = $this->Abas->getUser($result->verified_by);
			$result->verified_by = $verified_by['full_name'];
			$result->verified_by_signature = $verified_by['signature'];
			$noted_by = $this->Abas->getUser($result->noted_by);
			$result->noted_by = $noted_by['full_name'];
			$result->noted_by_signature = $noted_by['signature'];
			$approved_by = $this->Abas->getUser($result->approved_by);
			$result->approved_by = $approved_by['full_name'];
			$result->approved_by_signature = $approved_by['signature'];
			$posted_by = $this->Abas->getUser($result->posted_by);
			$result->posted_by = $posted_by['full_name'];
		}else{
			$result = NULL;
		}
		return $result;	
	}
	public function getInventoryAuditDetails($audit_id){
		$sql = "SELECT * FROM inventory_audit_details WHERE audit_id=".$audit_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
			foreach($result as $ctr=>$row) {
				$item=$this->getItem($result[$ctr]['item_id']);
				$result[$ctr]['item_code']=$item[0]['item_code'];
				$result[$ctr]['item_description']=$item[0]['item_name'].",".$item[0]['brand']." ".$item[0]['particular'];
			}
		}else{
			$result = NULL;
		}
		return $result;	
	}
	public function getInventoryAuditCutOffDocuments($audit_id){
		$sql = "SELECT * FROM inventory_audit_cutoff_documents WHERE audit_id=".$audit_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function checkItemIfAudited($audit_id,$item_id){
		$sql = "SELECT * FROM inventory_audit_details WHERE audit_id=".$audit_id. " AND item_id=".$item_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
			if(count($result)==1){
				$result = TRUE;
			}else{
				$result = FALSE;
			}
		}
		return $result;
	}
	public function getItemsPerCategory($category_id){
		$sql = "SELECT * FROM inventory_items WHERE category=".$category_id. " AND stat=1";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getIssuanceByDeliveryID($delivery_id){
		$sql = "SELECT * FROM inventory_issuance WHERE delivery_id=".$delivery_id." AND stat=1";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getReceivingByItemID($item_id,$date_from='',$date_to=''){
		if($date_from=='' && $date_to==''){
			$sql = "SELECT inventory_deliveries.tdate, inventory_deliveries.remark, idel.delivery_id, idel.item_id, idel.unit, idel.unit_price, idel.quantity FROM inventory_delivery_details AS idel INNER JOIN inventory_deliveries ON idel.delivery_id= inventory_deliveries.id WHERE idel.item_id=".$item_id." ORDER BY inventory_deliveries.tdate ASC";
		}else{
			$sql = "SELECT inventory_deliveries.tdate, inventory_deliveries.remark, idel.delivery_id, idel.item_id, idel.unit, idel.unit_price, idel.quantity FROM inventory_delivery_details AS idel INNER JOIN inventory_deliveries ON idel.delivery_id= inventory_deliveries.id WHERE idel.item_id=".$item_id." AND inventory_deliveries.tdate BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY inventory_deliveries.tdate ASC";
		}

		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getIssuanceByItemID($item_id,$date_from='',$date_to=''){
		if($date_from=='' && $date_to==''){
			$sql = "SELECT inventory_issuance.issue_date, inventory_issuance.remark, iiss.issuance_id, iiss.item_id, iiss.unit, iiss.unit_price, iiss.qty FROM inventory_issuance_details AS iiss INNER JOIN inventory_issuance ON iiss.issuance_id= inventory_issuance.id WHERE iiss.item_id=".$item_id." ORDER BY inventory_issuance.issue_date ASC";
		}else{
			$sql = "SELECT inventory_issuance.issue_date, inventory_issuance.remark, iiss.issuance_id, iiss.item_id, iiss.unit, iiss.unit_price, iiss.qty FROM inventory_issuance_details AS iiss INNER JOIN inventory_issuance ON iiss.issuance_id= inventory_issuance.id WHERE iiss.item_id=".$item_id."  AND inventory_issuance.issue_date BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY inventory_issuance.issue_date ASC";
		}
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getStockInOut($item_id){
		$sql = "SELECT * FROM (SELECT 'Receiving' AS type, inventory_deliveries.tdate AS trans_date,inventory_deliveries.remark AS remark,idel.delivery_id AS ref_id,idel.item_id AS item_id,idel.unit AS unit,idel.unit_price AS unit_price,idel.quantity AS quantity FROM (inventory_delivery_details idel JOIN inventory_deliveries ON(idel.delivery_id = inventory_deliveries.id)) UNION SELECT 'Issuance' AS type, inventory_issuance.issue_date AS trans_date,inventory_issuance.remark AS remark,iiss.issuance_id AS ref_id,iiss.item_id AS item_id,iiss.unit AS unit,iiss.unit_price AS unit_price,iiss.qty AS quantity FROM (inventory_issuance_details iiss JOIN inventory_issuance on(iiss.issuance_id = inventory_issuance.id))) data WHERE item_id=".$item_id." ORDER BY trans_date,ref_id ASC";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getIssuanceAmount($issuance_id){
		$sql = "SELECT issuance_id, SUM(unit_price*qty) AS issuance_amount FROM inventory_issuance_details WHERE issuance_id=".$issuance_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getItemQuantityPerCompany($item_id,$company_id,$location=''){
		$sql		=	"SELECT * FROM inventory_items_per_company WHERE item_id=".$item_id." AND company_id=".$company_id." AND location='".$location."' AND stat=1";
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getInventoryPerCompany($company_id,$location=''){
		if($location==''){
			$sql		=	"SELECT inventory_quantity.*,sum(inventory_quantity.quantity-inventory_quantity.quantity_issued) as qty_on_stock FROM inventory_quantity INNER JOIN inventory_items ON inventory_items.id = inventory_quantity.item_id WHERE inventory_quantity.company_id=".$company_id."  AND inventory_quantity.quantity>inventory_quantity.quantity_issued AND inventory_items.stat =1 AND inventory_quantity.stat=1 GROUP BY inventory_quantity.unit_price ORDER BY inventory_quantity.location ASC";
		}else{
			$sql		=	"SELECT inventory_quantity.*,sum(inventory_quantity.quantity-inventory_quantity.quantity_issued) as qty_on_stock FROM inventory_quantity INNER JOIN inventory_items ON inventory_items.id = inventory_quantity.item_id WHERE inventory_quantity.company_id=".$company_id."  AND inventory_quantity.quantity>inventory_quantity.quantity_issued AND inventory_items.stat =1 AND inventory_quantity.stat=1 AND inventory_quantity.location='".$location."' GROUP BY inventory_quantity.unit_price ORDER BY inventory_quantity.location ASC";
		}
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getItemQuantityAllCompany($item_id){
		$sql		=	"SELECT sum(total_quantity_received) as sum_received, sum(total_quantity_issued) as sum_issued FROM inventory_items_per_company WHERE item_id=".$item_id." AND stat=1";
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getItemForIssuance($item_id,$company_id,$location=''){
		$sql		=	"SELECT * FROM inventory_quantity WHERE item_id=".$item_id." AND company_id=".$company_id." AND location='".$location."' AND stat=1 AND quantity>quantity_issued";
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getInventoryQuantityDetail($item_quantity_id){
		$sql		=	"SELECT * FROM inventory_quantity WHERE id=".$item_quantity_id." AND stat=1 AND quantity>quantity_issued";
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getInventoryQuantityByDeliveryID($delivery_id){
		$sql		=	"SELECT * FROM inventory_quantity WHERE delivery_id=".$delivery_id;
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getInventoryQuantityByDeliveryIDandItemID($delivery_id,$item_id){
		$sql		=	"SELECT * FROM inventory_quantity WHERE delivery_id=".$delivery_id." AND item_id=".$item_id;
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getItemPriceHistory($item_id){
		$sql		=	"SELECT * FROM inventory_price_history WHERE item_id=".$item_id." AND stat=1 ORDER BY date_recorded ASC";
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	public function getItemUOMConversions($item_id,$company_id,$location='',$from_date='',$to_date=''){
		if($location!=''){
			$location_query = " AND location='".$location."'";
		}
		if($from_date!=''){
			$date_query = " AND (DATE(created_on) BETWEEN '".$from_date."' AND '".$to_date."')";
		}
		$sql = "SELECT * FROM inventory_conversions WHERE item_id=".$item_id." AND company_id=".$company_id.$location_query.$date_query." ORDER BY created_on ASC";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getPackagingByItem($item_id){
		$sql		=	"SELECT * FROM inventory_packaging WHERE item_id=".$item_id." AND stat=1";
			$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getItemsForAudit($category,$company_id,$location=''){
		//$sql		=	"SELECT *,sum(inventory_quantity.quantity-inventory_quantity.quantity_issued) as qty,inventory_quantity.unit_price as price FROM inventory_quantity INNER JOIN inventory_items ON inventory_items.id = inventory_quantity.item_id WHERE inventory_quantity.company_id=".$company_id." AND inventory_quantity.location='".$location."' AND inventory_items.category=".$category." AND inventory_quantity.stat=1 AND inventory_quantity.quantity>inventory_quantity.quantity_issued GROUP BY inventory_quantity.item_id, inventory_quantity.unit_price";
		$sql		=	"SELECT inventory_quantity.*,inventory_items.category,inventory_items.stock_location,inventory_items.item_code,inventory_items.description,inventory_items.brand,inventory_items.particular FROM inventory_quantity INNER JOIN inventory_items ON inventory_items.id = inventory_quantity.item_id WHERE inventory_quantity.company_id=".$company_id." AND inventory_quantity.location='".$location."' AND inventory_items.category=".$category." AND inventory_quantity.stat=1 AND inventory_quantity.quantity>inventory_quantity.quantity_issued";
		$query		=	$this->db->query($sql);
		if($query){
			$result		=	$query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getMonthlyInventoryReport($id){
		$sql = "SELECT * FROM inventory_monthly_reports WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getMonthlyInventoryReportDetails($id){
		$sql = "SELECT * FROM inventory_monthly_report_details WHERE monthly_report_id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
}
?>
