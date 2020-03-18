<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
##########################################################################
##########################################################################
#######################                         ##########################
#######################  AVEGA BROS INTEGRATED  ##########################
#######################     it@avegabros.com    ##########################
#######################           -             ##########################
#######################     August 2015         ##########################
#######################           -             ##########################
#######################                         ##########################
#######################    Purchasing Model     ##########################
#######################                         ##########################
##########################################################################
##########################################################################

class Purchasing_model extends CI_Model{
	public function getLowestRequestStatus($details) {
		/*
		 * possible statuses are as follows:
		 * For Request Approval, For Canvassing, For Canvass Approval, For Purchase, For Delivery, For Clearing, Paid, Cancelled
		 *
		 */
		$ret					=	null;
		$possible_statuses[8]	=	"for request approval";
		$possible_statuses[7]	=	"for canvassing";
		$possible_statuses[6]	=	"for canvass approval";
		$possible_statuses[5]	=	"for purchase";
		$possible_statuses[4]	=	"for delivery";
		$possible_statuses[3]	=	"for clearing";
		$possible_statuses[2]	=	"paid";
		$possible_statuses[1]	=	"cancelled";
		$possible_statuses[0]	=	"unknown";
		$current_status			=	0;
		if(!empty($details)) {
			foreach($details as $detail) {
				foreach($possible_statuses as $heirarchy=>$status) {
					if(strtolower($detail['status'])==$status) {
						if($heirarchy > $current_status) {
							$current_status	=	$heirarchy;
						}
					}
				}
			}
			$ret			=	ucwords($possible_statuses[$current_status]);
		}
		return $ret;
	}
	public function getRequest($id="") {
		$ret					=	null;
		if($id=="") 			{ return null; }
		if(!is_numeric($id))	{ return false; }
		$request				=	$this->db->query("SELECT * FROM inventory_requests WHERE id=".$id);
		if(!$request) 			{ return false; }
		if(!$request->row())	{ return false; }
		$request				=	(array)$request->row();
		$request['details']		=	array();
		$details				=	$this->db->query("SELECT * FROM inventory_request_details WHERE request_id=".$id);
		$request['status']		=	$this->Purchasing_model->getLowestRequestStatus($details->result_array());
		if($details) {
			if($details=$details->result_array()) {
				$request['status']		=	$this->Purchasing_model->getLowestRequestStatus($details);
				foreach($details as $ctr=>$detail) {
					$details[$ctr]	=	$this->Purchasing_model->getRequestDetail($detail['id']);
				}
				$request['details']	=	$details;
			}
		}
		$request['vessel_name']=$request['department_name']="";
		if(isset($request['vessel_id'])) {
			$vessel	=	$this->Abas->getVessel($request['vessel_id']);
			if($vessel) {
				$request['vessel_name']	=	$vessel->name;
				$request['vessel_id']	=	$vessel->id;
				$request['company'] = $this->Abas->getCompany($vessel->company);
			}
		}
		if(isset($request['department_id'])) {
			$dept	=	$this->Abas->getDepartment($request['department_id']);
			if($dept) {
				$request['department_name']	=	$dept->name;
				$request['department_id']	=	$dept->id;
			}
		}
		if($request['approved_by']!=0) {
			$approver	=	$this->Abas->getUser($request['approved_by']);
			if($approver) {
				$request['approved_by_name']	=	$approver['full_name'];
			}
		}
		if($request['added_by']!=0) {
			$requested_by	=	$this->Abas->getUser($request['added_by']);
			if($requested_by) {
				$request['requested_by_name']	=	$requested_by['full_name'];
				$request['requested_by_signature']	=	$requested_by['signature'];
			}
		}
		$ret					=	$request;
		return $ret;
	}
	public function getRequests($sqlappend="") {
		$ret					=	null;
		$sql = "SELECT * FROM inventory_requests WHERE stat=1 ".$sqlappend;
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
	public function getRequestsForAdmin($sqlappend="") {
		$ret					=	null;
		$sql = "SELECT * FROM inventory_requests WHERE stat=1 ".$sqlappend;

		$requests				=	$this->db->query($sql);
		if(!$requests) 			{ return null; }
		if(!$requests->row())	{ return null; }
		$requests				=	$requests->result_array();

		$ret					=	$requests;
		return $ret;
	}
	public function getRequestDetails($request_id="",$sqlappend="") {
		$ret					=	null;
		$check					=	$this->Purchasing_model->getRequest($request_id);
		if($check!=true) 		{ return false; }
		$details				=	$this->db->query("SELECT * FROM inventory_request_details WHERE request_id=".$check['id']." ".$sqlappend);
		if(!$details) 			{ return false; }
		if(!$details->row())	{ return false; }
		$ret					=	$details->result_array();
		if($ret) {
			foreach($ret as $ctr=>$r) {
				$ret[$ctr]		=	$this->Purchasing_model->getRequestDetail($r['id']);
			}
		}
		return $ret;
	}
	public function getRequestDetail($detail_id="") {
		$ret				=	null;
		$detail				=	$this->db->query("SELECT * FROM inventory_request_details WHERE id=".$detail_id."");
		if(!$detail) 		{ return false; }
		if(!$detail->row())	{ return false; }
		$ret				=	(array)$detail->row();
		$item				=	$this->Inventory_model->getItem($ret['item_id']);
		$ret['item_details']=	array("item_code"=>"", "description"=>"", "unit"=>"", "unit_price"=>"", "reorder_level"=>"", "discontinued"=>"", "sub_category"=>"", "stat"=>"", "qty"=>"", "category"=>"", "location"=>"", "stock_location"=>"", "account_type"=>"", "requested"=>"");
		if(isset($ret['item_id'])) {
			$item	=	$this->Inventory_model->getItem($ret['item_id']);
			if($item) {
				$item	=	$item[0];
				$ret['item_details']	=	$item;
			}
			$ret['request_assigned_to']	=	$this->Abas->getUser($ret['assigned_to']);
			$ret['request_approved_by']	=	$this->Abas->getUser($ret['request_approved_by']);
			$ret['canvass_approved_by']	=	$this->Abas->getUser($ret['canvass_approved_by']);
		}
		return $ret;
	}
	public function getRequestDetailDistinctItems($request_id="",$sqlappend="") {
		$ret					=	null;
		$check					=	$this->Purchasing_model->getRequest($request_id);
		if($check!=true) 		{ return false; }
		$details				=	$this->db->query("SELECT distinct(item_id), request_id, quantity FROM inventory_request_details WHERE request_id=".$check['id']." ".$sqlappend);
		if(!$details) 			{ return false; }
		if(!$details->row())	{ return false; }
		$ret					=	$details->result_array();
		return $ret;
	}
	public function renderRequest($request_id, $parent_element="", $hide_buttons=false) {
		/*
		 * $parent_element	-	used in bootstrap's collapsible, and is the ID of the div
		 * 						that will contain the output of this function
		 */
		$ret		=	"";
		$summary	=	$this->Purchasing_model->getRequest($request_id);
		if($summary!=true) { return false; }
		$details	=	$this->Purchasing_model->getRequestDetails($request_id, "AND supplier_id=0");
		// $this->Mmm->debug($vessel);
		// $this->Mmm->debug($department);
		// $this->Mmm->debug($summary);
		// $this->Mmm->debug($details);

		$display['summary']	=	"";
		$assigned_count		=	0;
		if($summary['requisitioner']!="") { $display['summary']	.=	"requested by ".$summary['requisitioner']." "; }
		if($summary['vessel_name']!="") { $display['summary']	.=	"for ".ucwords(strtolower($summary['vessel_name']))." "; }
		if($summary['truck_id']!=0) { 
			$truck = $this->Abas->getTruck($summary['truck_id']);
			$display['summary']	.=	" with Plate No.".$truck[0]['plate_number']." "; 
		}
		if($summary['department_name']!="") { $display['summary']	.=	"in ".ucwords(strtolower($summary['department_name']))." "; }
		if($summary['tdate']!="") { $display['summary']	.=	"made on ".date("j F Y H:i", strtotime($summary['tdate']))." "; }
		if($summary['reference_number']!="") { $display['summary']	.=	" under Project Reference No. ".$summary['reference_number']." "; }
		if($summary['approved_by']!="0") {
			$approver			=	$this->Abas->getUser($summary['approved_by']);
			$display['summary']	.=	"to be approved by ".$approver['full_name']." ";
		}

		$priorityclass			=	"default";
		if($summary['priority']=="High") $priorityclass="danger";
		if($summary['priority']=="Medium") $priorityclass="info";
		$detail_table			=	"";
		$contains_purchaseable	=	false;
		$contains_approvable	=	false;
		if(!empty($details)) {
			foreach($details as $d) {
				// $this->Mmm->debug($d);
				$canvass_table	=	"";
				$add_canvass_btn=	"<tr><td colspan='99' class='text-center'><a href='".HTTP_PATH."purchasing/canvass_details/".$d['id']."' data-toggle='modal' data-target='#modalDialog' class='btn btn-primary btn-xs'><span class='hidden-sm hidden-xs'>Add</span> Canvass</a></td></tr>";
				$statusbtn		=	"<a class='view_canvasses-btn btn btn-info btn-xs' onclick='javascript: toggleHide(".$d['id'].")'><span class='glyphicon glyphicon-th-list'></span> ".ucwords(strtolower($d['status']))."</a> ";
				$approvebtn	=	($this->Abas->checkPermissions("purchasing|approve_request",false) || $this->Abas->checkPermissions("purchasing|approve_canvass",false)) ? " <a class='request-item-approve-btn btn btn-success btn-xs' onclick='javascript: confirmApproveRequestItem(".$d['id'].")'><span class='glyphicon glyphicon-ok'></span></a>" : "";
				$cancelbtn	=	$this->Abas->checkPermissions("purchasing|cancel_item",false) ? " <a class='request-item-cancel-btn btn btn-danger btn-xs' onclick='javascript: confirmCancelRequestItem(".$d['id'].")'><span class='glyphicon glyphicon-remove'></span></a>" : "";
				if($hide_buttons!=false) {
					$add_canvass_btn=$approvebtn=$cancelbtn="";
				}
				$item	=	$this->Inventory_model->getItem($d['item_id']);
				$item	=	$item[0];
				if(strtolower($d['status'])!="for request approval") {
					$sql_append = "AND r.id=".$d['request_id']." AND rd.item_id=".$d['item_id'];
					$canvass	=	$this->Purchasing_model->getCanvassedItems($sql_append);
					$canvass_table	.=	"<tr class='hide canvassdetails".$d['id']."'><td colspan='99'><table class='table table-striped table-bordered'>";
					$canvass_table	.=	"<tr><th>Action</th><th>Supplier</th><th>Unit Price</th><th>Total Price</th><th>Time Canvassed</th><th>Remark</th><th>Approved By</th><th>Status</th>";
					$edit_canvass_price_btn	=	"";
					if($canvass!=false) {
						foreach($canvass as $c) {
							if(strtolower($c['status']) == "for purchase") {$contains_purchaseable=true;}
							$canvass_status			=	ucwords(strtolower($c['status']));
							$approve_canvass_btn	=	ucwords(strtolower($c['status']));
							$cancel_canvass_btn		=	"";
							if($canvass_status=="For Canvass Approval" || $canvass_status=="For Purchase" || $canvass_status=="For Delivery") {
								$canvass_approved_by_name	=	"-";
								if(strtolower($d['status']) == strtolower("For Canvass Approval")) {
									$contains_approvable=	true;
									$cancel_canvass_btn	=	"<a class='request-item-cancel-btn btn btn-danger btn-xs' onclick='javascript: confirmCancelCanvassItem(".$c['id'].")'><span class='glyphicon glyphicon-remove'></span></a>";
									if($this->Abas->checkPermissions("purchasing|approve_canvass",false)) {
										$approve_canvass_btn	=	"<input type='radio' id='canvass".$c['id']."' name='canvass[".$d['item_id']."]' value='".$c['id']."' /> <label for='canvass".$c['id']."'>Approve</label>";
									}
								}
								elseif(strtolower($d['status']) == strtolower("For Delivery")) {
									$edit_canvass_price_btn	=	"<a class='glyphicon glyphicon-pencil btn btn-warning btn-xs edit-unit-price'></a>";
								}
								if(!empty($c['canvass_approved_by'])) {
									$approved_by			=	$this->Abas->getUser($c['canvass_approved_by']);
									$canvass_approved_by_name	=	$approved_by['full_name'];
								}
								$supplier		=	$this->Abas->getSupplier($c['supplier_id']);
								$canvass_table	.=	"<tr>";
								$canvass_table	.=	"<td>".$approve_canvass_btn."</td>";
								$canvass_table	.=	"<td>".$c['id'].") ".$supplier['name']."</td>";
								$canvass_table	.=	"<td>".number_format($c['unit_price'],2)."</td>";
								$canvass_table	.=	"<td>P".number_format(($d['quantity']*$c['unit_price']),2)."</td>";
								$canvass_table	.=	"<td>".date("j F Y H:i", strtotime($c['added_on']))."</td>";
								$canvass_table	.=	"<td>".$c['remark']."</td>";
								$canvass_table	.=	"<td>".$canvass_approved_by_name."</td>";
								$canvass_table	.=	"<td>".$canvass_status." ".$cancel_canvass_btn."</td>";
								// $canvass_table	.=	"<td>".ucwords(strtolower($c['status']))."</td>";
								$canvass_table	.=	"</tr>";
							}
						}
					}
					if((strtolower($d['status']) == strtolower("For Canvassing") || strtolower($d['status']) == strtolower("For Canvass Approval")) && $this->Abas->checkPermissions("purchasing|canvass_item",false)) {
						$canvass_table	.=	$add_canvass_btn;
					}
					$canvass_table	.=	"</table></td></tr>";
				}
				$detail_approval=	"";
				if(strtolower($d['status'])=="for request approval" && $this->Abas->checkPermissions("purchasing|approve_request", false)) {
					if(isset($approver)) { // check if approver is designated
						if($approver['id']==$_SESSION['abas_login']['userid']) { // allow only tagged approver to approve this item
							$contains_approvable=	true;
							$detail_approval=	"<input type='checkbox' id='request".$d['id']."' name='detail[]' value='".$d['id']."'> <label for='request".$d['id']."'>Approve</label>";
						}
					}
					else { // if no approver is designated, allow all
						$contains_approvable=	true;
						$detail_approval=	"<input type='checkbox' id='request".$d['id']."' name='detail[]' value='".$d['id']."'> <label for='request".$d['id']."'>Approve</label>";
					}
				}
				if(strtolower($d['status'])=="for delivery" || strtolower($d['status'])=="for clearing" || strtolower($d['status'])=="paid" || strtolower($d['status'])=="cancelled") {
					$cancelbtn	=	"";
				}
				$assignment		=	$this->Abas->getUser($d['assigned_to']);
				if($d['assigned_to']==$_SESSION['abas_login']['userid']) {
					$assigned_count++;
				}
				$request_approved_by_name	=	"-";
				if(!empty($d['request_approved_by'])) {
					$request_approved_by_name	=	$d['request_approved_by']['full_name'];
				}
				$detail_table	.=	"
					<tr>
						<td>".$detail_approval."</td>
						<td>".$item['item_code']."</td>
						<td>".$item['item_name'].",".$item['brand']." ".$item['particular']."</td>
						<td>".$d['quantity']."</td>";
					if($d['packaging']==''){
						$detail_table	.=	"<td>".ucfirst(strtolower($item['unit']))."</td>";
					}else{
						$detail_table	.=	"<td>".ucfirst(strtolower($d['packaging']))."</td>";
					}
				$detail_table	.=	"<td>".$assignment['full_name']."</td>
						<td>".$d['remark']."</td>
						<td>".$request_approved_by_name."</td>
						<td>".$statusbtn.$cancelbtn."</td>
					</tr>
				";
				if($this->Abas->checkPermissions("purchasing|view_canvassed_items",false) && strtolower($d['status'])!="cancelled") {
					$detail_table	.=	$canvass_table;
				}
			}
		}
		else {
			$detail_table	=	"<tr><td colspan=99>No info found!</td></tr>";
		}
		$edit_button				=	' <a href="'.HTTP_PATH.'purchasing/requisition/edit/'.$request_id.'" class="exclude-pageload btn btn-warning btn-xs pull-right" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit</a>';
		$approve_selected_button	=	' <a onclick="javascript:confirmApproveRequest('.$request_id.')" class="exclude-pageload btn btn-success btn-xs pull-right">Approve Selected Items</a>';
		$create_po_button			=	' <a class="exclude-pageload btn btn-xs btn-success pull-right" onclick="javascript:createPO('.$request_id.')" >Create PO/JO</a>';
		$cancel_request_button		=	' <a class="exclude-pageload btn btn-xs btn-danger pull-right" onclick="javascript:cancelRequest('.$request_id.')" >Cancel</a>';
		if($contains_purchaseable==false) {$create_po_button="";}
		// if(strtolower($summary['status'])!="for purchase") {$create_po_button="";}
		if($hide_buttons==true) {$edit_button=$create_po_button=$approve_all_button="";}
		if(($this->Abas->checkPermissions("purchasing|approve_request",false) || $this->Abas->checkPermissions("purchasing|approve_canvass",false)) && $contains_approvable) {
			$approve_selected_button	=	' <a onclick="javascript:confirmApproveRequest('.$request_id.')" class="exclude-pageload btn btn-success btn-xs pull-right">Approve Selected Items</a>';
		}
		if(!$this->Abas->checkPermissions("purchasing|cancel_item",false)) {
			$cancel_request_button		=	"";
		}
		$ret		=	'
			<div class="panel panel-'.$priorityclass.'">
				<div class="panel-heading" role="tab" id="headingRequest'.$request_id.'">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#'.$parent_element.'" href="#bodyRequest'.$request_id.'" aria-expanded="true" aria-controls="bodyRequest'.$request_id.'">
							Transaction Code: '.$request_id.' | Control No. '.$summary['control_number'].' - '.$summary['company']->name.' ('.count($details).' items)
						</a>
						'.$create_po_button.'
						'.$cancel_request_button.'
					</h4>
				</div>
				<div id="bodyRequest'.$request_id.'" class="panel-collapse collapse '.($parent_element==""?"in":"").'" role="tabpanel" aria-labelledby="headingRequest'.$request_id.'">
					<div class="panel-body">
						<form id="request_approve_'.$request_id.'" method="POST" action="'.HTTP_PATH.'purchasing/requisition/multiApprove/'.$request_id.'">
							<div class="col-xs-12 col-sm-12  col-md-12 ">
								'.ucfirst($display['summary']).'
							</div>
							<div class="clearfix"><br/></div>
							<div class="table-responsive">
								<table class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th colspan="9">
												'.$edit_button.'
												'.$approve_selected_button.'
											</th>
										</tr>
										<tr>
											<th>Action</th>
											<th>Item Code</th>
											<th>Description</th>
											<th>Quantity</th>
											<th>Unit/Packaging</th>
											<th>Assigned To</th>
											<th>Remark</th>
											<th>Approved by</th>
											<th>Manage</th>
										</tr>
									</thead>
									<tbody>
										'.$detail_table.'
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		';
		return $ret;
	}
	public function renderCanvassingItem($request_detail_id="") {
		$rdi	=	$request_detail_id;
		$ret	=	null;
		if(!$rdi) return null;
		if(!is_numeric($rdi)) return null;
		$detail	=	$this->Purchasing_model->getRequestDetail($rdi);
		$requestinfo	=	$this->Purchasing_model->getRequest($detail['request_id']);
		// $this->Mmm->debug($requestinfo);
		// $this->Mmm->debug($detail);
		$priorityclass	=	"default";
		if($requestinfo['priority']=="High") $priorityclass="danger";
		if($requestinfo['priority']=="Medium") $priorityclass="info";
		// if($requestinfo['priority']=="Low") $priorityclass="info";
		$display['summary']	=	"For Request#".$requestinfo['id']." ";
		// if($requestinfo['requisitioner']!="") { $display['summary']	.=	"requested by ".$requestinfo['requisitioner']." "; }
		if($requestinfo['vessel_name']!="") { $display['summary']	.=	"for ".ucwords(strtolower($requestinfo['vessel_name']))." "; }
		// if($requestinfo['department_name']!="") { $display['summary']	.=	"in ".ucwords(strtolower($requestinfo['department_name']))." "; }
		if($requestinfo['tdate']!="") { $display['summary']	.=	"made on ".date("j F Y", strtotime($requestinfo['tdate']))." "; }
		$display['assigned']	=	"";
		if($detail['assigned_to']==0) { $display['assigned']	=	"<span class='glyphicon glyphicon-user'></span>"; }
		$sql_append = "AND r.id=".$detail['request_id']." AND rd.item_id=".$detail['item_id']." AND rd.supplier_id<>0";
		$canvass	=	$this->Purchasing_model->getCanvassedItems($sql_append);
		$btn_addcanvass	=	$this->Abas->checkPermissions("purchasing|canvass_item",false)?'<a href="'.HTTP_PATH.'purchasing/canvass_details/'.$detail['id'].'" data-toggle="modal" data-target="#modalDialog" class="btn btn-'.$priorityclass.' btn-block"><span class="hidden-sm hidden-xs">Add</span> Canvass</a>':'';
		$btn_viewrequest=	'<a href="'.HTTP_PATH.'purchasing/request_details/'.$detail['id'].'" data-toggle="modal" data-target="#modalDialog" class="btn btn-'.$priorityclass.' btn-block"><span class="hidden-sm hidden-xs">View</span> Request</a>';
		$ret	=	'
		<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-xs-6 canvassing-item">
			<div class="panel panel-'.$priorityclass.'">
				<div class="panel-heading">
					<strong>'.$detail['quantity'].' '.$detail['item_details']['unit'].' of '.$detail['item_details']['description'].'</strong> '.$display['assigned'].'
				</div>
				<div class="panel-body">
					<div class="col-xs-12 col-lg-12">'.ucfirst($display['summary']).'</div>
					<div class="col-xs-12 col-lg-12">
						<p class="hidden-xs">Prices encoded: '.count($canvass).'</p>
						'.$btn_viewrequest.'
						'.$btn_addcanvass.'
					</div>
				</div>
			</div>
		</div>

		';
		return $ret;
	}
	public function getItemsForCanvassing($sqlappend="") {
		/*
		 * inventory_request_details data has the alias of 'rd' while the parent (inventory_requests) has the alias of 'r'.
		 *
		 */
		$ret				=	null;
		$sql				=	"SELECT rd.*, r.status AS request_status, r.id AS request_id, r.priority FROM inventory_request_details AS rd JOIN inventory_requests AS r ON r.id=rd.request_id WHERE (rd.status LIKE 'For Canvassing' OR rd.status LIKE 'For Canvass Approval' OR rd.status NOT LIKE 'Cancelled') AND rd.supplier_id=0 ".$sqlappend." ORDER BY rd.item_id, r.status";
		$detail				=	$this->db->query($sql);
		if(!$detail) 		{ return false; }
		if(!$detail->row())	{ return false; }
		$ret				=	(array)$detail->result_array();
		return $ret;
	}
	public function getCanvassedItems($sqlappend="") {
		/*
		 * inventory_request_details data has the alias of 'rd' while the parent (inventory_requests) has the alias of 'r'.
		 *
		 */
		$ret				=	null;
		$sql				=	"SELECT rd.*, r.status AS request_status, r.id AS request_id, r.priority FROM inventory_request_details AS rd JOIN inventory_requests AS r ON r.id=rd.request_id WHERE (rd.status NOT LIKE 'Cancelled' OR 'For Request Approval' OR 'For Purchase Order') AND rd.supplier_id<>0 ".$sqlappend." ORDER BY r.status";

		$detail				=	$this->db->query($sql);
		if(!$detail) 		{ return null; }
		if(!$detail->row())	{ return null; }
		$ret				=	(array)$detail->result_array();
		if(!empty($ret)) {
			foreach($ret as $ctr=>$r) {
				$item	=	$this->Inventory_model->getItem($r['item_id']);
				$ret[$ctr]['item_details']	=	$item[0];
			}
		}
		return $ret;
	}
	public function getCanvassedItemLists($sqlappend) {
		/*For presentation*/
		$ret				=	null;
		$sql				=	"	SELECT request_id, item_id FROM inventory_request_details
									WHERE status = 'For Canvass Approval'
									GROUP by item_id, request_id";

		$detail				=	$this->db->query($sql);
		if(!$detail) 		{ return null; }
		if(!$detail->row())	{ return null; }
		$ret				=	(array)$detail->result_array();
		if(!empty($ret)) {
			foreach($ret as $ctr=>$r) {
				$item	=	$this->Inventory_model->getItem($r['item_id']);
				$request = $this->getRequest($r['request_id']);
				$ret[$ctr]['item_details']	=	$item[0];
				$ret[$ctr]['request_info']	=	$request;
			}
		}
		return $ret;
	}
	public function getApprovedItems() {
		$ret							=	null;
		$pre_canvas_items				=	$this->db->query("SELECT * FROM inventory_request_details WHERE status='For Canvassing' AND stat=1");
		if(!$pre_canvas_items)			{ $ret=false; }
		if(!$pre_canvas_items->row())	{ $ret=false; }
		$ret							=	$pre_canvas_items->result_array();
		return $ret;
	}
	public function getPurchaseOrder($id) {
		$ret					=	null;
		if($id=="") 			{ return null; }
		if(!is_numeric($id))	{ return false; }
		$po						=	$this->db->query("SELECT * FROM inventory_po WHERE id=".$id);
		if(!$po)	 			{ return false; }
		if(!$po->row())			{ return false; }
		$po						=	(array)$po->row();
		$po['vessel_name']		=	$po['department_name']=$po['supplier_name']=$po['company_name']=$po['approver_name']="";
		$po['service_status']	=	"Unserved";
		$po['number_of_items']	=	0;
		$po['details']			=	array();
		$details				=	$this->db->query("SELECT id FROM inventory_po_details WHERE po_id=".$id);
		if($details) {
			if($details=$details->result_array()) {
				if(!empty($details)) {
					$previtem			=	0;
					foreach($details as $dctr=>$detail) {
						$details[$dctr]			=	$this->Purchasing_model->getPurchaseOrderDetail($detail['id']);
						if($previtem!=$details[$dctr]['item_id']) {
							$previtem				=	$details[$dctr]['item_id'];
							$po['number_of_items']++;
						}
					}
					$po['details']	=	$details;
				}
			}
		}
		if(isset($po['request_id'])) {
			$request				=	$this->Purchasing_model->getRequest($po['request_id']);
			if(isset($request['vessel_id'])) {
				$vessel	=	$this->Abas->getVessel($request['vessel_id']);
				if(!empty($vessel)) {
					$po['vessel_name']		=	$vessel->name;
					$po['vessel_id']		=	$vessel->id;
				}
			}
			if(isset($request['department_id'])) {
				$dept	=	$this->Abas->getDepartment($request['department_id']);
				if(!empty($dept)) {
					$po['department_name']	=	$dept->name;
					$po['department_id']	=	$dept->id;
				}
			}
		}
		if(isset($po['tdate'])) {
			$po['tdate']		=	date("j F Y",strtotime($po['tdate']));
		}
		if(isset($po['supplier_id'])) {
			$supplier	=	$this->Abas->getSupplier($po['supplier_id']);
			if(!empty($supplier)) {
				$po['supplier']				=	$supplier;
				$po['supplier_name']		=	$supplier['name'];
			}
		}
		if(isset($po['company_id'])) {
			$company	=	$this->Abas->getCompany($po['company_id']);
			if(!empty($company)) {
				$po['company_name']		=	$company->name;
			}
		}
		if(isset($po['approved_by'])) {
			$approver	=	$this->Abas->getUser($po['approved_by']);
			if(!empty($approver)) {
				$po['approved_by']		=	$approver;
				$po['approver_name']	=	$approver['full_name'];
				$po['approved_on']		=	date("j F Y H:i:s",strtotime($po['approved_on']));
			}
		}
		$po['user_can_approve']	=	false;
		if($po['stat']==1) {
			if(empty($po['approved_by']) || empty($po['approved_on'])) {
				if($po['amount']<=50000) {
					if($this->Abas->checkPermissions("purchasing|approve_low_amount_po",false)) $po['user_can_approve']		=	true;
				}
				elseif($po['amount']>50000 && $po['amount']<=150000) {
					if($this->Abas->checkPermissions("purchasing|approve_medium_amount_po",false)) $po['user_can_approve']	=	true;
				}
				elseif($po['amount']>150000) {
					if($this->Abas->checkPermissions("purchasing|approve_high_amount_po",false)) $po['user_can_approve']	=	true;
				}
			}
		}
		$receiving_report		=	$this->db->query("SELECT id FROM inventory_deliveries WHERE po_no=".$id);
		if($receiving_report) {
			if($receiving_report->row()) {
				$receiving_report	=	$receiving_report->result_array();
				if(count($receiving_report)>0) {
					$po['service_status']	=	"Served";
				}
			}
		}
		$ret	=	$po;
		return $ret;
	}
	public function getPurchaseOrderDetail($detail_id) {
		$ret	=	null;
		if(!is_numeric($detail_id)) return $ret;
		$detail	=	$this->db->query("SELECT * FROM inventory_po_details WHERE id=".$detail_id);
		if($detail) {
			if($detail=(array)$detail->row()) {
				if(!empty($detail)) {
					$item						=	$this->Inventory_model->getItem($detail['item_id']);
					$detail['item']				=	$item[0];
					$detail['request_detail']	=	$this->Purchasing_model->getRequestDetail($detail['request_detail_id']);
					$ret						=	$detail;
				}
			}
		}
		return $ret;
	}
	function getPurchaseOrderDetails($po_id="") {
		$ret	=	null;
		if(is_numeric($po_id)) {
			$po_details	=	$this->db->query("SELECT * FROM inventory_po_details WHERE po_id=".$po_id);
			if($po_details) {
				if($po_details->row()) {
					$ret	=	$po_details->result_array();
				}
			}
		}
		return $ret;
	}
	public function updateRequestStatus($request_id="") {
		$ret	=	null;
		$request=	$this->Purchasing_model->getRequest($request_id);
		// $this->Mmm->debug($request);
		if(!$request) { return false; }
		$details=	$this->Purchasing_model->getRequestDetails($request_id);
		if(empty($details)) { return false; }
		// $this->Mmm->debug($details);

		$update_to	=	"Complete";
		$check	=	array("for request approval", "for canvassing", "for canvass approval", "for purchase", "for delivery", "for payment", "paid", "cancelled");
		foreach($details as $ctr=>$d) {

		}
		$this->Mmm->debug($check);
		$this->Mmm->debug($ret);
		return $ret;
	}
	public function getItemsForPurchasing($supplier_id="") {
		$filter	=	"<>0";
		$ret	=	null;
		if($supplier_id!="" && is_numeric($supplier_id)) {
			$filter	=	"=".$supplier_id;
		}
		$for_purchase	=	$this->db->query("SELECT * FROM inventory_request_details WHERE status LIKE 'For Purchase' AND supplier_id".$filter." ORDER BY supplier_id");
		if(!$for_purchase) { $ret=null; }
		if(!$for_purchase->row()) { $ret=null; }
		$for_purchase	=	$for_purchase->result_array();
		if(empty($for_purchase)) { $ret=null; }
		$ret	=	$for_purchase;
		return $ret;
	}
	public function renderPO($supplier_id="", $po_items="") { // depreciated 2017-03-07 - mmm
		$this->Abas->sysMsg("errmsg", "Depreciated function in ". __class__ ."->". __function__ ." as of 2017-03-07 - mmm");
		$this->Abas->redirect($_SERVER['HTTP_REFERER']);
		$ret	=	"";
		$priorityclass	=	"default";
		// $this->Mmm->debug($supplier_id);
		// $this->Mmm->debug($po_items);
		if($supplier_id=="") { return null; }
		if($po_items=="") { return null; }
		$supplier	=	$this->Abas->getSupplier($supplier_id);
		// $this->Mmm->debug($supplier);
		if(!$supplier) { return null; }
		$itemstable	=	"";
		if(empty($po_items)) { return null; }
		$totalcost	=	0;
		$request_id	=	0;
		foreach($po_items as $ctr=>$pi) {
			// $this->Mmm->debug($pi);
			$itempriorityclass	=	"default";
			$item	=	$this->Inventory_model->getItem($pi['item_id']);
			$item	=	$item[0];
			$request=	$this->Purchasing_model->getRequest($pi['request_id']);
			if($priorityclass=="default") {
				if($request['priority']=="High") { $priorityclass="danger"; }
				if($request['priority']=="Medium") { $priorityclass="info"; }
			}
			if($itempriorityclass=="default") {
				if($request['priority']=="High") { $itempriorityclass="danger"; }
				if($request['priority']=="Medium") { $itempriorityclass="info"; }
			}
			$totalcost	=	$totalcost+($pi['quantity']*$pi['unit_price']);
			$itemstable	.=	"<tr>";
			$itemstable	.=	"<td><a href='".HTTP_PATH."purchasing/request_details/".$pi['id']."' data-toggle='modal' data-target='#modalDialog' class='btn btn-".$itempriorityclass." btn-xs'>".$item['description']."</td>";
			$itemstable	.=	"<td>".$pi['quantity']." ".$item['unit']."</td>";
			$itemstable	.=	"<td class='text-right'>P".number_format($pi['unit_price'],2)."</td>";
			$itemstable	.=	"<td class='text-right'>P".number_format(($pi['quantity']*$pi['unit_price']),2)."</td>";
			$itemstable	.=	"</tr>";
		}
		$totalcost		=	number_format($totalcost,2);
		$companies		=	$this->Abas->getCompanies();
		$companyoptions	=	"";
		if(!empty($companies)) {
			foreach($companies as $c) {
				$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
			}
		}
		unset($c);

		$ret	=	'
		<div class="panel panel-'.$priorityclass.'">
			<div class="panel-heading" role="tab" id="poHeadingRequest'.$supplier_id.'">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#po_container" href="#poBodyRequest'.$supplier_id.'" aria-expanded="true" aria-controls="bodyRequest'.$supplier_id.'">
						Supplier: <b>'.$supplier['name'].'</b> (P'.$totalcost.')
					</a>
				</h4>
			</div>
			<div id="poBodyRequest'.$supplier_id.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="poHeadingRequest'.$supplier_id.'">
				<form action="'.HTTP_PATH.'purchasing/purchase_order/create/'.$supplier_id.'" role="form" method="POST" id="purchase_form'.$supplier_id.'" enctype="multipart/form-data">
					'.$this->Mmm->createCSRF().'
					<div class="panel-body">
						<div class="col-xs-12 col-sm-5 col-md-5 col-lg-4 pull-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<label for="company'.$supplier_id.'">Company</label>
								<select class="form-control" id="company'.$supplier_id.'" name="company">
									<option value="">-</option>
									'.$companyoptions.'
								</select>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
								<label for="tdate'.$supplier_id.'">Date</label>
								<input type="text" class="form-control tdate" name="tdate" id="tdate'.$supplier_id.'" value="'.date("Y-m-d").'" />
								<script>$(".tdate").datepicker({dateFormat: "yy-mm-dd"});</script>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
								<label for="location'.$supplier_id.'">Location</label>
								<input type="text" class="form-control" name="location" id="location'.$supplier_id.'" value="" placeholder="Location" />
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<label for="remark'.$supplier_id.'">Remark</label>
								<textarea class="form-control" name="remark" id="remark'.$supplier_id.'"></textarea>
							</div>
							<div class="clearfix">&nbsp;</div>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
								<input class="btn btn-success btn-block" type="button"  value="Finalize PO" onclick="javascript:checkpo('.$supplier_id.')" id="submitbtn">
							</div>
						</div>
						<div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 pull-right">
							<div class="table-responsive">
								<table class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Item</th>
											<th>Quantity</th>
											<th>Unit Price</th>
											<th>Total Price</th>
										</tr>
									<thead>
									<tbody>
										'.$itemstable.'
									</tbody>
									<tfoot class="text-right">
										<tr><td colspan=3>Subtotal</td><td>P'.$totalcost.'</td></tr>
										<!--tr><td colspan=3>Discount</td><td><input type="text" class="form-xs text-right" name="discount" id="discount'.$supplier_id.'" value="" placeholder="0.00" /></td></tr>
										<tr><td colspan=3>12% VAT</td><td><input type="text" class="form-xs text-right" name="vat" id="vat'.$supplier_id.'" value="" placeholder="'.number_format(($totalcost*.12),2).'" /></td></tr-->
									</tfoot>
								</table>
							</div>
							<div class="col-md-6 col-md-offset-3">
								Remarks: '.$request['remark'].'
							</div>
						</div>
						<div class="clearfix"><br/></div>
					</div>
				</form>
			</div>
		</div>
		';

		return $ret;
	}
	function getSupplierCanvass($item_id="",$request_id="") {
		$ret				=	null;
		$details				=	$this->db->query("SELECT * from inventory_request_details WHERE item_id = $item_id and request_id = $request_id");
		if(!$details) 			{ return false; }
		if(!$details->row())	{ return false; }
		$ret					=	$details->result_array();
		return $ret;
	}
	function unselectSupplierCanvass($item_id="",$request_id="") { //change status to "unselected" for unselected supplier of the item
		$sql = "UPDATE inventory_request_details SET status = 'unselected' WHERE item_id = $item_id and request_id = $request_id";
		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details;
	}
	public function getRequestApprovers() {
		$ret		=	array();
		$all_permitted	=	$this->db->query("SELECT user_id FROM users_permissions WHERE page='purchasing|approve_request'");
		if($all_permitted) {
			if($all_permitted=$all_permitted->result_array()) {
				foreach($all_permitted as $permitted) {
					$user	=	$this->Abas->getUser($permitted['user_id']);
					$ret[]	=	$user;
				}
			}
		}
		return $ret;
	}
	public function getJobOrders(){
		$sql = "SELECT * FROM inventory_job_orders";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getJobOrder($id){
		$sql = "SELECT * FROM inventory_job_orders WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
			
				if(isset($result->request_id)) {
					$request				=	$this->Purchasing_model->getRequest($result->request_id);
					if(isset($request['vessel_id'])) {
						$vessel	=	$this->Abas->getVessel($request['vessel_id']);
						if(!empty($vessel)) {
							$result->vessel_name		=	$vessel->name;
							$result->vessel_id		=	$vessel->id;
						}
					}
					if(isset($request['department_id'])) {
						$dept	=	$this->Abas->getDepartment($request['department_id']);
						if(!empty($dept)) {
							$result->department_name	=	$dept->name;
							$result->department_id	=	$dept->id;
						}
					}
				}
				if(isset($result->tdate)) {
					$result->tdate		=	date("j F Y",strtotime($result->tdate));
				}
				if(isset($result->supplier_id)) {
					$supplier	=	$this->Abas->getSupplier($result->supplier_id);
					if(!empty($supplier)) {
						$result->supplier				=	$supplier;
						$result->supplier_name		=	$supplier['name'];
					}
				}
				if(isset($result->company_id)) {
					$company	=	$this->Abas->getCompany($result->company_id);
					if(!empty($company)) {
						$result->company_name		=	$company->name;
					}
				}
				if(isset($result->approved_by)) {
					$approver	=	$this->Abas->getUser($result->approved_by);
					if(!empty($approver)) {
						$result->approved_by		=	$approver;
						$result->approver_name	=	$approver['full_name'];
						$result->approved_on		=	date("j F Y H:i:s",strtotime($result->approved_on));
					}
				}
				$result->user_can_approve	=	false;
				if($result->stat==1) {
					if(empty($result->approved_by) || empty($result->approved_on)) {
						if($result->amount<=50000) {
							if($this->Abas->checkPermissions("purchasing|approve_low_amount_jo",false)) $result->user_can_approve		=	true;
						}
						elseif($result->amount>50000 && $result->amount<=150000) {
							if($this->Abas->checkPermissions("purchasing|approve_medium_amount_jo",false)) $result->user_can_approve	=	true;
						}
						elseif($result->amount>150000) {
							if($this->Abas->checkPermissions("purchasing|approve_high_amount_jo",false)) $result->user_can_approve	=	true;
						}
					}
				}

		}else{
			$result = NULL;
		}

		return $result;
	}
	public function getJobOrderDetails($job_order_id){
		$sql = "SELECT * FROM inventory_job_order_details WHERE job_order_id=".$job_order_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getCanvassers(){
		$sql = "SELECT users_permissions.user_id,users.last_name,users.first_name,users.middle_name FROM users INNER JOIN users_permissions ON users.id=users_permissions.user_id WHERE users.role='Purchasing' AND users_permissions.page='purchasing|canvass_item'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getVesselPO($vessel_id,$date_from=NULL,$date_to=NULL){
		$year = date('Y');
		if($date_from==NULL && $date_to==NULL){
			$sql = "SELECT ipo.*,ir.vessel_id,ir.requisitioner FROM inventory_po AS ipo INNER JOIN inventory_requests AS ir ON ipo.request_id=ir.id WHERE ipo.status<>'Cancelled' AND ir.vessel_id=".$vessel_id." AND YEAR(ipo.added_on)='".$year."' ORDER BY ipo.added_on DESC";
		}else{
			$sql = "SELECT ipo.*,ir.vessel_id,ir.requisitioner FROM inventory_po AS ipo INNER JOIN inventory_requests AS ir ON ipo.request_id=ir.id WHERE ipo.status<>'Cancelled' AND ir.vessel_id=".$vessel_id. " AND ipo.added_on BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY ipo.added_on DESC";
		}
		
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$d) {
				$query2		=	$this->db->query("SELECT id FROM inventory_deliveries WHERE po_no=".$result[$ctr]->id);
				if($query2) {
					$rr = $query2->result();
					if(count($rr)>0){
						$result[$ctr]->delivery_status	=	"Served";
					}else{
						$result[$ctr]->delivery_status	=	"Unserved";	
					}
				}else{
					$result[$ctr]->delivery_status	=	"Unserved";
				}


				$supplier = $this->Abas->getSupplier($result[$ctr]->supplier_id);
				$result[$ctr]->supplier_name = $supplier['name'];

				$po_details = $this->getPurchaseOrderDetails($result[$ctr]->id);
				$result[$ctr]->po_details = $po_details;
		
			}
		}else{
			$result = null;
		}
		return $result;
	}
	public function getPurchaseOrderHistory($item_id,$company_id,$date_from,$date_to){
		$sql = "SELECT inventory_po.* FROM inventory_po INNER JOIN inventory_po_details ON inventory_po.id = inventory_po_details.po_id WHERE inventory_po_details.item_id=".$item_id." AND inventory_po.company_id=".$company_id." AND inventory_po.approved_by <> '' AND inventory_po.added_on BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY inventory_po.added_on DESC";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$d){
				$company = $this->Abas->getCompany($result[$ctr]->company_id);
				$result[$ctr]->company_name	=	$company->name;
				$supplier = $this->Abas->getSupplier($result[$ctr]->supplier_id);
				$result[$ctr]->supplier_name	=	$supplier['name'];
			}
		}else{
			$result = NULL;
		}
		return $result;
	}
}
?>
