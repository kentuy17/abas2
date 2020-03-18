<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
##########################################################################
##########################################################################
#######################                         ##########################
#######################  AVEGA BROS INTEGRATED  ##########################
#######################  	it@avegabros.com 	##########################
#######################           -             ##########################
#######################     August 2015         ##########################
#######################           -             ##########################
#######################                         ##########################
#######################    Purchasing Model     ##########################
#######################                         ##########################
##########################################################################
##########################################################################

class Admin_model extends CI_Model{
	public function getRequest($id="") {
		$ret					=	null;
		if($id=="") 			{ return null; }
		if(!is_numeric($id))	{ return false; }
		$request				=	$this->db->query("SELECT * FROM inventory_requests WHERE id=".$id);
		if(!$request) 			{ return false; }
		if(!$request->row())	{ return false; }
		$request				=	(array)$request->row();
		$ret					=	$request;
		return $ret;
	}
	public function getRequests($sqlappend="") {
		$ret					=	null;
		$sql = "SELECT * FROM inventory_requests WHERE 1=1 ".$sqlappend;
		var_dump($sql); exit;

		$request				=	$this->db->query($sql);
		if(!$request) 			{ return null; }
		if(!$request->row())	{ return null; }
		$request				=	$request->result_array();
		$ret					=	$request;
		return $ret;
	}
	public function getRequestDetails($request_id="",$sqlappend="") {
		$ret					=	null;
		$check					=	$this->Purchasing_model->getRequest($request_id);
		if($check!=true) 		{ return false; }
		$details				=	$this->db->query("SELECT * FROM inventory_request_details WHERE status != 'Cancelled' AND request_id=".$check['id']." ".$sqlappend);
		if(!$details) 			{ return false; }
		if(!$details->row())	{ return false; }
		$ret					=	$details->result_array();
		return $ret;
	}
	public function getRequestDetailDistinctItems($request_id="",$sqlappend="") {
		$ret					=	null;
		$check					=	$this->Purchasing_model->getRequest($request_id);
		if($check!=true) 		{ return false; }
		$sql 					= "SELECT distinct(item_id), request_id  FROM inventory_request_details WHERE request_id=".$check['id']." ".$sqlappend; 
		
		$details				=	$this->db->query($sql);
		
		if(!$details) 			{ return false; }
		if(!$details->row())	{ return false; }
		$ret					=	$details->result_array();
		return $ret;
	}
	public function getCanvassForApproval($request_id="",$sqlappend="") {
		
		$ret					=	null;		
		
		
		//$sql 					= "SELECT *  FROM inventory_requests WHERE status='For Canvass Approval' ".$sqlappend; 
		$sql 					= "SELECT distinct(request_id) as rid  FROM inventory_request_details WHERE status='For Canvass Approval' "; 
		$db						= $this->db->query($sql);
		if(!$db) 			{ return false; }
		$res					= $db->result_array();
		
		
		$ctr=0;
		$arr	 = array();
		
		foreach($res as $r){
			
			$sql1	= "SELECT *  FROM inventory_requests WHERE id=".$r['rid']; 
		
			$d		= $this->db->query($sql1);
			$arr[$ctr]	= $d->result_array();
			$ctr++;
			
		}
		
		return $arr;
	}
	
	public function getRequestDistinctItems($sqlappend="") {
		$ret					=	null;
		
		$sql 					= "SELECT distinct(item_id), request_id  FROM inventory_request_details WHERE 0=0 ".$sqlappend; 
	
		$details				=	$this->db->query($sql);
		
		if(!$details) 			{ return NULL; }
		if(!$details->row())	{ return NULL; }
		$ret					=	$details->result_array();
		
		return $ret;
	}
	public function getRequestQty($item_id="", $request_id="") {
		$ret					=	null;
		$check					=	$this->Purchasing_model->getRequest($request_id);
		if($check!=true) 		{ return false; }
		$details				=	$this->db->query("SELECT * FROM inventory_request_details WHERE item_id =".$item_id." AND request_id=".$check['id']);
		if(!$details) 			{ return false; }
		if(!$details->row())	{ return false; }
		$ret					=	$details->result_array();
		return $ret;
	}
	/*
	public function getRequestsByPriority($priority="") {
		$ret					=	null;
		$priority				=	$this->Mmm->debug($priority);
		if($priority=="") 		{ return null; }
		$request				=	$this->db->query("SELECT * FROM inventory_requests WHERE priority='".$priority."'");
		if(!$request) 			{ return false; }
		if(!$request->row())	{ return false; }
		$request				=	(array)$request->row();
		$ret					=	$request;
		return $ret;
	}
	// */
	function renderRequest($request_id, $parent_element="") {
		/*
		 * $parent_element	-	used in bootstrap's collapsible, and is the ID of the div
		 * 						that will contain the output of this function
		 */
		$ret		=	"";
		$summary	=	$this->Purchasing_model->getRequest($request_id);
		if($summary!=true) { return false; }
		$details	=	$this->Purchasing_model->getRequestDetails($request_id);
		$vessel		=	$this->Abas->getVessel($summary['vessel']);
		$department	=	$this->Abas->getDepartment($summary['department']);
		// $this->Mmm->debug($vessel);
		// $this->Mmm->debug($department);
		// $this->Mmm->debug($summary);
		// $this->Mmm->debug($details);
		$display['summary']	=	"";
		if($summary['requisitioner']!="") { $display['summary']	.=	"requested by ".$summary['requisitioner']." "; }
		if($summary['vessel']!="") { $display['summary']	.=	"for ".$vessel->name." "; }
		if($summary['department']!="") { $display['summary']	.=	"in ".$department->name." "; }
		if($summary['request_date']!="") { $display['summary']	.=	"made on ".date("j F Y H:i", strtotime($summary['request_date']))." "; }

		$detail_table	=	"";
		if(!empty($details)) {
			foreach($details as $d) {
				$item	=	$this->Inventory_model->getItem($d['item_id']);
				$item	=	$item[0];
				if($d['status']=="For Approval") {
					$approvebtn	=	$this->Abas->checkPermissions("purchasing|approve_request",false) ? "<a class='request-item-approve-btn btn btn-success btn-xs' onclick='javascript: confirmApproveRequestItem(".$d['id'].")'><span class='glyphicon glyphicon-ok'></span></a>" : "";
					$cancelbtn	=	"<a class='request-item-cancel-btn btn btn-danger btn-xs' onclick='javascript: confirmCancelRequestItem(".$d['id'].")'><span class='glyphicon glyphicon-remove'></span></a>";
					$buttons	=	rand(0,1) == 0 ? $approvebtn.$cancelbtn : $cancelbtn.$approvebtn;
					// $buttons	=	$cancelbtn.$approvebtn;
				}
				else {
					$buttons	=	$d['status'];
				}
				$detail_table	.=	"
					<tr>
						<td>".$item['item_code']."</td>
						<td>".$item['description']."</td>
						<td>".$d['quantity']."</td>
						<td>".$d['assigned_to']."</td>
						<td>".$d['remark']."</td>
						<td>".$buttons."</td>
					</tr>
				";
			}
		}
		$ret		=	'
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingRequest'.$request_id.'">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#'.$parent_element.'" href="#bodyRequest'.$request_id.'" aria-expanded="true" aria-controls="bodyRequest'.$request_id.'">
						Request for '.$vessel->name.', '.$department->name.' ('.count($details).' items) - '.$summary['status'].'
					</a>
				</h4>
			</div>
			<div id="bodyRequest'.$request_id.'" class="panel-collapse collapse '.($parent_element==""?"in":"").'" role="tabpanel" aria-labelledby="headingRequest'.$request_id.'">
				<div class="panel-body">
					<div class="col-xs-7 col-xs-offset-3" ailgn="left">
						'.ucfirst($display['summary']).'
					</div>
					<div class="clearfix"><br/></div>
					<div class="table-responsive">
						 <table id="datatable-responsive" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th colspan="8">
										<a href="'.HTTP_PATH.'purchasing/requisition/edit/'.$request_id.'" class="btn btn-warning btn-xs pull-right" data-toggle="modal" data-target="#modalDialog">Edit</a>
										'.($this->Abas->checkPermissions("purchasing|approve_request",false) ? '<a onclick="javascript:confirmApproveRequest('.$request_id.')" class="btn btn-success btn-xs pull-right">Approve All Pending Items</a>':"").'
									</th>
								</tr>
								<tr>
									<th>Item Code</th>
									<th>Description</th>
									<th>Quantity</th>
									<th>Assigned To</th>
									<th>Remark</th>
									<th>Manage</th>
								</tr>
							</thead>
							<tbody>
								'.$detail_table.'
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		';
		return $ret;
	}
	function getRequestDetail($detail_id="") {
		$ret				=	null;
		$detail				=	$this->db->query("SELECT * FROM inventory_request_details WHERE id=".$detail_id);
		if(!$detail) 		{ return false; }
		if(!$detail->row())	{ return false; }
		$ret				=	(array)$detail->row();
		return $ret;
	}


	function updateRequestStatus($request_id="") {
		$ret	=	null;
		$request=	$this->Purchasing_model->getRequest($request_id);
		if(!$request) { return false; }
		$pending_details=	$this->Purchasing_model->getRequestDetails($request_id, "AND status='For Approval'");
		if(count($pending_details) == 0) {
			$ret	=	$this->db->query("UPDATE inventory_requests SET status='For Canvassing' WHERE id=".$request_id);
		}
		else {
			$ret	=	count($pending_details);
		}
		return $ret;
	}


	/* FOR CANVASS */
	function getSupplierCanvass($item_id="",$request_id="") {
		$ret				=	null;
		// $details				=	$this->db->query("SELECT * from inventory_request_details WHERE item_id = $item_id and request_id = $request_id");
		//$sql = "SELECT * from inventory_request_details WHERE item_id = $item_id and request_id = $request_id AND supplier_id <> 0 AND status !='Cancelled'";
		$ret = false;
		if($item_id!=''){
		
		$sql = "SELECT * from inventory_request_details WHERE item_id = $item_id and request_id = $request_id AND supplier_id <> 0 AND status !='Cancelled'";
		
		$details				=	$this->db->query($sql); // mmmaske: added condition regarding supplier id
		if(!$details) 			{ return false; }
		if(!$details->row())	{ return false; }
		$ret					=	$details->result_array();
		}
		return $ret;
	}
	
	
	
	/* FOR VOUCHER */
	function checkVoucherApprover($id='') {
		$ret				=	false;
		
		if($id!=''){
			$sql = "SELECT * from ac_voucher_approvals WHERE voucher_id =".$id;
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }
			if(!$details->row())	{ return false; }
			$ret					=	$details->result_array();
		}
		
		return $ret;
	}
	
	
	public function getPOForApproval($po_id="",$sqlappend="") {
		
		$ret					=	null;	
		
		//$sql 					= "SELECT *  FROM inventory_requests WHERE status='For Canvass Approval' ".$sqlappend; 
		$sql 					= "SELECT *  FROM inventory_po WHERE approved_by is NULL and stat = 1 "; 
		$db						= $this->db->query($sql);
		if(!$db) 			{ return false; }
		$res					= $db->result_array();
		
		/*		
		$ctr=0;
		$arr	 = array();
		
		foreach($res as $r){
			
			$sql1	= "SELECT *  FROM inventory_po WHERE id=".$r['id'];		
			$d		= $this->db->query($sql1);
			$arr[$ctr]	= $d->result_array();
			$ctr++;
			
		}
		*/
		return $res;
	}

	public function getJOForApproval() {
			 
		$sql 		= "SELECT * FROM inventory_job_orders WHERE approved_by is NULL and stat=1"; 
		$query		= $this->db->query($sql);
		
		if($query){ 
			$result	= $query->result_array();
		}else{
			$result = NULL;
		}
		
		return $result;
	}
	
	##########################################################
	//REPORT AND MONITORING
	##########################################################
	function getAgingRequests() {
		
			$sql = "SELECT DISTINCT(request_id), i.tdate, datediff(sysdate(), i.tdate ) - 7 as aging, requisitioner, vessel_id, department_id, priority, 		
						purpose, control_number, d.status, i.id as rid, i.added_by  
					FROM inventory_request_details as d 
					INNER JOIN inventory_requests as i ON d.request_id = i.id 
					WHERE d.status = 'For request approval' OR d.status = 'For canvass approval' OR d.status = 'For canvassing' 
					ORDER BY priority DESC, aging";
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }
			if(!$details->row())	{ return false; }
			return	$details->result_array();
	
		
			
	}
}


?>
