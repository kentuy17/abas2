<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Purchasing extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			$this->load->database();
			$this->load->model("Abas");
			$this->load->model("Mmm");
			$this->load->model("Inventory_model");
			$this->load->model("Accounting_model");
			$this->load->model("Purchasing_model");
			$this->output->enable_profiler(FALSE);
			define("SIDEMENU","Purchasing");
			if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
		}
		public function index()	{$data=array();
			$this->Abas->checkPermissions("purchasing|view");
			$this->Abas->redirect(HTTP_PATH."purchasing/requisition");
		}
		public function requisition($action="", $id="") {$data=array();
			$this->Abas->checkPermissions("purchasing|view_requests");
			$mainview	=	"gentlella_container.php";
			$users					=	$this->db->query("SELECT * FROM users WHERE stat=1 AND role='Purchasing' ORDER BY last_name ASC");
			$data['vessels']		=	$this->Abas->getVessels();
			$data['departments']	=	$this->Abas->getDepartments();
			$data['trucks']			=	$this->Abas->getTrucks();
			$data['users']			=	$users->result_array();
			$data['approvers']		=	$this->Purchasing_model->getRequestApprovers();
			$data['viewfile']		=	"purchasing/requests.php";
			if($id=="") {
				if($action=="add") {
					//old form
					//$mainview	=	"purchasing/requisition_form.php";

					//new form
					$mainview	=	"purchasing/requisition/form.php";
				}
				elseif($action=="insert") {
				//die($this->Mmm->debug($_POST));
					if(!isset($_POST['requisitioner'], $_POST['vessel'], $_POST['department'], $_POST['priority'])) {
						$this->Abas->sysMsg("errmsg", "Request details incomplete!");
						$this->Abas->redirect(HTTP_PATH."purchasing");
					}
					$summary['tdate']			=	$this->Mmm->sanitize($_POST['date_needed']);
					$summary['requisitioner']	=	$this->Mmm->sanitize($_POST['requisitioner']);
					$summary['vessel_id']		=	$this->Mmm->sanitize($_POST['vessel']);
					$summary['truck_id']		=	$this->Mmm->sanitize($_POST['truck']);
					$summary['reference_number']	=	$this->Mmm->sanitize($_POST['reference_no']);
					$summary['control_number']	=	$this->Abas->getNextSerialNumber("inventory_requests", $_POST['company_id']);
					$summary['department_id']	=	$this->Mmm->sanitize($_POST['department']);
					$summary['priority']		=	$this->Mmm->sanitize($_POST['priority']);
					$summary['remark']			=	$this->Mmm->sanitize($_POST['remark']);
					$summary['added_by']		=	$_SESSION['abas_login']['userid'];
					$summary['added_on']		=	date('Y-m-d H:i:s');
					$summary['approved_by']		=	$this->Mmm->sanitize($_POST['approved_by']);
					$summary['stat']			=	1;
					$summary['status']			=	"For request approval";
					$vessel						=	$this->Abas->getVessel($_POST['vessel']);
					if(empty($vessel)) {
						$this->Abas->sysMsg("errmsg", "This vessel does not exist! Please try again.");
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$check	=	$this->Mmm->dbInsert("inventory_requests", $summary, "New requisition for ".$vessel->name);
					if($check==true) {
						if(!empty($_POST['itemvalue'])) {
							$request	=	$this->db->query("SELECT max(id) AS id FROM inventory_requests");
							$request	=	(array)$request->row();
							$new_id		=	$request['id'];
							foreach($_POST['itemvalue'] as $ctr=>$itemname) {
								if($_POST['itemvalue'][$ctr]!="") {
									if($_POST['quantity'][$ctr]>0) {
										$detail[]	=	array(
										"request_id"	=>	$new_id,
										"item_id"		=>	$this->Mmm->sanitize($_POST['itemvalue'][$ctr]),
										"unit"		=>	$this->Mmm->sanitize($_POST['itemunit'][$ctr]),
										"packaging"		=>	$this->Mmm->sanitize($_POST['packaging'][$ctr]),
										"quantity"		=>	$this->Mmm->sanitize($_POST['quantity'][$ctr]),
										"assigned_to"	=>	$this->Mmm->sanitize($_POST['assign_to'][$ctr]),
										"supplier_id"	=>	0,
										"stat"			=>	1,
										"added_by"		=>	$_SESSION['abas_login']['userid'],
										"added_on"		=>	date("Y-m-d H:i:s"),
										"status"		=>	"For Request Approval",
										"remark"		=>	$_POST['item_remark'][$ctr]
										);
										$already_added[]=	$_POST['itemvalue'][$ctr];
									}
									else {
										$this->Abas->sysMsg("warnmsg", "The item ".$_POST['itemname'][$ctr]." has an invalid quantity, and has not been added.");
									}
								}
							}
							if(!empty($detail)) {
								$check_items	=	$this->Mmm->multiInsert("inventory_request_details", $detail, "Insert requisition items for request ".$request['id']." for ".$vessel->name);
							}
							if($check_items==true) {
								$notif_msg	=	"A new request for ".$vessel->name." has been added by ".$_SESSION['abas_login']['fullname'].". Click <a href='".HTTP_PATH."purchasing/requisition/view/".$new_id."'>HERE</a> to view.";
								$this->Abas->sysMsg("sucmsg", "Request encoded and is now awaiting approval.");
							}else{
								$this->Abas->sysMsg("errmsg", "Request items not added! Please click here to add items.");
							}
						}else{
							$this->Abas->sysMsg("errmsg", "No request items found! Please click here to add items.");
						}
					}else{
						$this->Abas->sysMsg("errmsg", "Request not added! Please try again.");
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
				else {
					$requests		=	$this->db->query("SELECT DISTINCT(r.id) FROM inventory_requests AS r INNER JOIN inventory_request_details AS rd ON rd.request_id=r.id WHERE r.stat=1 AND (rd.status LIKE 'for request approval' OR rd.status LIKE 'for canvassing' OR rd.status LIKE 'for canvass approval' OR rd.status LIKE 'for purchase') ORDER BY tdate ASC");
					$requests		=	$requests->result_array();
					$sorted			=	array();
					foreach($requests as $rctr=>$request) {
						$request			=	$this->Purchasing_model->getRequest($request['id']);
						$requests[$rctr]	=	$request;
						$sorted[$request['status']][$request['priority']][]	=	$request;
					}
					$data['sorted_by_status']		=	$sorted;
				}
			}
			elseif(is_numeric($id)) {
				$request			=	$this->Purchasing_model->getRequest($id);
				$data['request']	=	$request;
				if($data['request']!=true) {
					$this->Abas->sysMsg("errmsg", "Request not found!");
					$this->Abas->redirect(HTTP_PATH."purchasing");
				}
				$data['request_details']	=	$this->Purchasing_model->getRequestDetails($id,"AND supplier_id=0");
				if($action=="edit") {
					//$mainview	=	"purchasing/requisition_form.php"; old requisition form
					$mainview	=	"purchasing/requisition/form.php";
				}
				elseif($action=="update") {
					if($request!=true) {
						$this->Abas->sysMsg("errmsg", "Request not found!");
						$this->Abas->redirect(HTTP_PATH."purchasing");
					}
					if(!isset($_POST['requisitioner'], $_POST['vessel'], $_POST['department'], $_POST['priority'])) {
						$this->Abas->sysMsg("errmsg", "Request details incomplete!");
						$this->Abas->redirect(HTTP_PATH."purchasing");
					}
					$summary['tdate']			=	$this->Mmm->sanitize($_POST['date_needed']);
					$summary['requisitioner']	=	$this->Mmm->sanitize($_POST['requisitioner']);
					$summary['vessel_id']		=	$this->Mmm->sanitize($_POST['vessel']);
					$summary['company_id']		=	$this->Mmm->sanitize($_POST['company_id']);
					$summary['truck_id']		=	$this->Mmm->sanitize($_POST['truck']);
					$summary['reference_number']	=	$this->Mmm->sanitize($_POST['reference_no']);
					$summary['department_id']	=	$this->Mmm->sanitize($_POST['department']);
					$summary['priority']		=	$this->Mmm->sanitize($_POST['priority']);
					$summary['remark']			=	$this->Mmm->sanitize($_POST['remark']);
					$summary['added_by']		=	$_SESSION['abas_login']['userid'];
					$summary['stat']			=	1;
					$summary['status']			=	"For request approval";
					$summary['approved_by']		=	$this->Mmm->sanitize($_POST['approved_by']);
					$vessel						=	$this->Abas->getVessel($_POST['vessel']);
					if(empty($vessel)) {
						$this->Abas->sysMsg("errmsg", "Vessel not found!");
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$check	=	$this->Mmm->dbUpdate("inventory_requests", $summary, $id, "Edited requisition for ".$vessel->name);
					if($check==true) {
						if(!empty($_POST['itemvalue'])) {
							$already_added			=	array();
							foreach($_POST['itemvalue'] as $ctr=>$itemname) {
								if(!in_array($_POST['itemvalue'][$ctr], $already_added)) {
									if($_POST['itemvalue'][$ctr]!="") {
										if($_POST['quantity'][$ctr]>0) {
											$detail[]	=	array(
											"request_id"	=>	$request['id'],
											"item_id"		=>	$this->Mmm->sanitize($_POST['itemvalue'][$ctr]),
											"unit"		=>	$this->Mmm->sanitize($_POST['itemunit'][$ctr]),
											"packaging"		=>	$this->Mmm->sanitize($_POST['packaging'][$ctr]),
											"quantity"		=>	$this->Mmm->sanitize($_POST['quantity'][$ctr]),
											"assigned_to"	=>	$this->Mmm->sanitize($_POST['assign_to'][$ctr]),
											"stat"			=>	1,
											"supplier_id"	=>	0,
											"status"		=>	"For Request Approval",
											"remark"		=>	$_POST['item_remark'][$ctr]
											);
											$already_added[]=	$_POST['itemvalue'][$ctr];
										}
										else {
											$this->Abas->sysMsg("warnmsg", "The item ".$_POST['itemname'][$ctr]." has an invalid quantity, and has not been added.");
										}
									}
								}
								else {
									$this->Abas->sysMsg("warnmsg", "The item ".$_POST['itemname'][$ctr]." already exists in this request, and has not been added.");
								}
							}
							if(!empty($detail)) {
								$clearchildren	=	$this->db->query("DELETE FROM inventory_request_details WHERE request_id=".$id." AND status LIKE 'for request approval'");
								if($clearchildren!=false) {
									if(!empty($detail)) {
										$check_items	=	$this->Mmm->multiInsert("inventory_request_details", $detail, "Update requisition items for request ".$request['id']." for ".$vessel->name);
									}
								}
								else {
									$this->Abas->sysMsg("errmsg", "Unable to clear details for input. Please try again.");
								}
							}
							else { $this->Abas->sysMsg("errmsg", "Request items not added! Please click here to add items."); }
							if($check_items==true) { $this->Abas->sysMsg("sucmsg", "Request updated and is now awaiting approval."); }
							else { $this->Abas->sysMsg("errmsg", "Request items not added! Please click here to add items."); }
							$this->Purchasing_model->updateRequestStatus($request['id']);
						}
						else { $this->Abas->sysMsg("errmsg", "No request items found! Please click here to add items."); }
					}
					else { $this->Abas->sysMsg("errmsg", "Request not added! Please try again."); }
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
				elseif($action=="multiApprove") {
					//$this->Mmm->debug($_POST);
					$user	=	$this->Abas->getUser($_SESSION['abas_login']['userid']);
					if(isset($_POST['detail'])) {
						if($this->Abas->checkPermissions("purchasing|approve_request",false)) {
							foreach($_POST['detail'] as $dctr=>$detail) {
								$detail	=	$this->Purchasing_model->getRequestDetail($detail);
								$item	=	$this->Inventory_model->getItem($detail['item_id']);
								$item	=	$item[0];
								$sql	=	"UPDATE inventory_request_details SET status='for canvassing', request_approved_by=".$_SESSION['abas_login']['userid'].", request_approved_on='".date("Y-m-d H:i:s")."' WHERE id=".$detail['id'];
								$checkDetail	=	$this->Mmm->query($sql, "Approve ".$item['description']." in request# ".$request['id']);
								if($checkDetail) { $this->Abas->sysMsg("sucmsg", "Request Item ".$item['description']." Approved!"); }
							}
							$this->Abas->sysNotif("ABAS Says", $user['username']." approved ".($dctr+1)." item(s) in request ".$request['control_number']." for ".$request['vessel_name'], "Purchasing", "success");
						}
						else {
							$this->Abas->sysMsg("errmsg", "You are not permitted to approve requests!");
						}
					}
					if(isset($_POST['canvass'])) {
						if($this->Abas->checkPermissions("purchasing|approve_canvass",false)) {
							$dctr	=	0;
							foreach($_POST['canvass'] as $item_id=>$canvass_id) {
								$dctr++;
								$item				=	$this->Inventory_model->getItem($item_id);
								$item				=	$item[0];
								$canvass			=	$this->Purchasing_model->getRequestDetail($canvass_id);
								$request			=	$this->Purchasing_model->getRequest($canvass['request_id']);
								$sql				=	"UPDATE inventory_request_details SET status='unselected' WHERE (supplier_id<>".$canvass['supplier_id']." AND supplier_id<>0) AND item_id=".$item['id']." AND request_id=".$request['id']." AND status LIKE 'For Canvass Approval'";
								$unselectCanvass	=	$this->db->query($sql);
								$sql				=	"UPDATE inventory_request_details SET status='for purchase', canvass_approved_by=".$_SESSION['abas_login']['userid'].", canvass_approved_on='".date("Y-m-d H:i:s")."' WHERE (supplier_id=".$canvass['supplier_id']." OR supplier_id=0) AND item_id=".$item['id']." AND request_id=".$request['id']." AND status LIKE 'For Canvass Approval'";
								$checkCanvass		=	$this->Mmm->query($sql, "Approve canvass at for ".$canvass['quantity']." x ".$item['description']." at P".$canvass['unit_price']."  in request# ".$request['id']);
								if($checkCanvass) { $this->Abas->sysMsg("sucmsg", "Canvass for ".$item['description']." approved at ".$canvass['unit_price']."/".$item['unit']."!"); }
							}
							$this->Abas->sysNotif("ABAS Says", $user['username']." approved ".($dctr)." item(s) in request transaction code ".$request['id']." for ".$request['vessel_name'], "Purchasing", "success");
						}
						else {
							$this->Abas->sysMsg("errmsg", "You are not permitted to approve canvasses!");
						}
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
				elseif($action=="view") {
					//$this->Mmm->debug($this->Purchasing_model->getRequest($id));
					$hide_buttons		=	false;
					$data['disp']		=	$this->Purchasing_model->renderRequest($id,"",$hide_buttons);
					$data['viewfile']	=	"purchasing/request.php";
					$mainview			=	"gentlella_container.php";
				}
				elseif($action=="modal_view") {
					$this->Mmm->debug($this->Purchasing_model->getRequest($id));
					$hide_buttons		=	true;
					$data['disp']		=	$this->Purchasing_model->renderRequest($id,"",$hide_buttons);
					$mainview			=	"purchasing/request.php";
				}
				elseif($action=="create_po") {
					$mainview	=	"purchasing/po_form.php";
				}
				elseif($action=="cancel") {
					$this->Abas->checkPermissions("purchasing|cancel_item");
					if(!empty($request['details'])) {
						$cancelsql	=	"UPDATE inventory_request_details SET status='Cancelled' WHERE request_id=".$id;
						$check		=	$this->Mmm->query($cancelsql, "Cancel Request for ".$request['vessel_name'].", ".$request['department_name']);
						if($check) {
							$this->Abas->sysMsg("sucmsg", count($request['details'])." details the request for ".$request['vessel_name'].", ".$request['department_name']." has been cancelled!");
							$this->Abas->sysNotif("Cancelled Request", $_SESSION['abas_login']['fullname']." has cancelled ".count($request['details'])." items in the request for ".$request['vessel_name'].", ".$request['department_name'],"Purchasing");
						}
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
			}
			$this->load->view($mainview,$data);
		}
		public function requisition_item($request_id="", $action="") {
			$this->Abas->checkPermissions("purchasing|view_requests");
			$data['viewfile']	=	"purchasing/purchasing_view.php";
			$user	=	$this->Abas->getUser($_SESSION['abas_login']['userid']);
			if($request_id=="") {
				$this->Abas->sysMsg("errmsg", "No request detail ID found!");
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			if(!is_numeric($request_id)) {
				$this->Abas->sysMsg("errmsg", "Invalid request detail ID!");
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			if($action=="") {
				$this->Abas->sysMsg("errmsg", "No action specified for request transaction number ".$request_id."!");
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			$detail	=	$this->Purchasing_model->getRequestDetail($request_id);
			if($detail!=true) {
				$this->Abas->sysMsg("errmsg", "Request detail not found!");
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			elseif($action=="cancel") {
				$this->Abas->checkPermissions("purchasing|cancel_item");
				$checkupdate	=	$this->Mmm->query("UPDATE inventory_request_details SET status='Cancelled' WHERE request_id=".$detail['request_id']." AND item_id=".$detail['item_id'],"Cancel ".$detail['item_details']['description']." in request #".$detail['request_id']); // cancels item and all canvasses
				if($checkupdate) { $this->Abas->sysMsg("sucmsg", "Item cancelled!"); }
				else { $this->Abas->sysMsg("errmsg", "Item not approved! Please try again."); }
			}
			if($action=="approve_request" || $action=="approve_canvass" || $action=="cancel" || !empty($_POST)) {
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}

		}
		public function canvass_details($id, $action="", $detail_id="") {
			$this->Abas->checkPermissions("purchasing|view_canvassed_items");
			$parent_detail	=	$this->Purchasing_model->getRequestDetail($id);
			$request		=	$this->Purchasing_model->getRequest($parent_detail['request_id']);
			$item			=	$this->Inventory_model->getItem($parent_detail['item_id']);
			$item			=	$item[0];
			if($parent_detail==false) {
				$this->Abas->sysMsg("errmsg", "No request detail found!");
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			if($request==false) {
				$this->Abas->sysMsg("errmsg", "No request found!");
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			if($action=="canvass") {
				$check	=	false;
				if(!empty($_POST['supplier_id'])) {
					foreach($_POST['supplier_id'] as $ctr=>$supplier_id) {
						$siblings				=	$this->db->query("SELECT * FROM inventory_request_details WHERE request_id=".$request['id']." AND item_id=".$parent_detail['item_id']." AND status NOT LIKE 'Cancelled'");
						if($siblings) {
							if($siblings=$siblings->result_array()) {
								foreach($siblings as $sibling) {
									if($sibling['supplier_id']==$supplier_id) {
										$this->Abas->sysMsg("warnmsg", "A supplier has already been canvassed! Please select another or delete the existing canvass for that supplier.");
										$this->Abas->redirect(HTTP_PATH."purchasing");
									}
								}
							}
						}
						$multiInsert[$ctr]['request_id']	=	$parent_detail['request_id'];
						$multiInsert[$ctr]['item_id']		=	$parent_detail['item_id'];
						$multiInsert[$ctr]['unit']			=	$parent_detail['unit'];
						$multiInsert[$ctr]['packaging']		=	$parent_detail['packaging'];
						$multiInsert[$ctr]['quantity']		=	$parent_detail['quantity'];
						$multiInsert[$ctr]['stat']			=	1;
						$multiInsert[$ctr]['status']		=	"For Canvass Approval";
						$multiInsert[$ctr]['added_by']		=	$_SESSION['abas_login']['userid'];
						$multiInsert[$ctr]['added_on']		=	date("Y-m-d H:i:s");
						$multiInsert[$ctr]['supplier_id']	=	$_POST['supplier_id'][$ctr];
						$multiInsert[$ctr]['unit_price']	=	$_POST['unit_price'][$ctr];
						$multiInsert[$ctr]['remark']		=	$_POST['remark'][$ctr];
						$supplier	=	$this->Abas->getSupplier($supplier_id);
						if(!$supplier) {
							$this->Abas->sysMsg("errmsg", "Supplier not found! Please try again.");
							$this->Abas->redirect(HTTP_PATH."purchasing");
						}
					}
					$check	=	$this->Mmm->multiInsert("inventory_request_details", $multiInsert, "Insert canvasses for request ".$request['id']." for ".$request['vessel_name']);
				}
				if($check) {
					$this->Abas->sysMsg("sucmsg", "Your canvass details have been submitted and is pending approval.");
					$this->db->query("UPDATE inventory_request_details SET status='For Canvass Approval' WHERE id=".$parent_detail['id']." AND status NOT LIKE 'Cancelled'");
				}
				else { $this->Abas->sysMsg("errmsg", "Your canvass details have not been submitted. Please try again."); }
				$this->Abas->redirect(HTTP_PATH."purchasing/requisition/view/".$parent_detail['request_id']);
			}
			if($action=="cancel") {
				$this->Abas->checkPermissions("purchasing|cancel_item");
				//$checkupdate	=	$this->Mmm->query("UPDATE inventory_request_details SET status='Cancelled' WHERE id=".$id,"Cancel canvass");
				$checkupdate	=	$this->Mmm->query("DELETE FROM inventory_request_details WHERE id=".$id,"Cancel canvass for (".$item['name'].") in request #".$request['id']);
				if($checkupdate) { $this->Abas->sysMsg("sucmsg", "Item cancelled!"); }

				// Check for sibling canvasses
				$checkitem		=	$this->db->query("SELECT * FROM inventory_request_details WHERE item_id=".$parent_detail['item_id']." AND request_id=".$request['id']." AND supplier_id<>0");
				if($checkitem=$checkitem->row()) {
					if(empty($checkitem)) {
						// Reset status to "For Canvassing" if no other canvasses are found
						$this->db->query("UPDATE inventory_request_details SET status='For Canvassing' WHERE item_id=".$parent_detail['item_id']." AND request_id=".$request['id']." AND supplier_id=0");
					}
				}
				else { $this->Abas->sysMsg("errmsg", "Item not approved! Please try again."); }
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			$parent				=	$this->Purchasing_model->getItemsForCanvassing("AND rd.request_id=".$parent_detail['request_id']." AND rd.item_id=".$parent_detail['item_id']." AND rd.status NOT LIKE 'Cancelled'");
			$canvassed			=	$this->Purchasing_model->getCanvassedItems("AND rd.request_id=".$parent_detail['request_id']." AND rd.item_id=".$parent_detail['item_id']." AND rd.status NOT LIKE 'Cancelled'");
			$data['history']	=	$this->Purchasing_model->getCanvassedItems("AND rd.status LIKE 'For Purchase' AND rd.item_id=".$parent_detail['item_id']." AND rd.status NOT LIKE 'Cancelled'");
			$data['request']	=	$request;
			$data['parent']		=	$parent_detail;
			$data['canvassed']	=	$canvassed;
			$this->load->view("purchasing/canvassing_detail.php",$data);
		}
		public function request_details($id) {
			$this->Abas->checkPermissions("purchasing|view_requests");
			$detail	=	$this->Purchasing_model->getRequestDetail($id);
			if($detail==false) {
				$this->Abas->sysMsg("errmsg", "No request detail found!");
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			$hide_buttons	=	true;
			$data['disp']	=	$this->Purchasing_model->renderRequest($detail['request_id'],"",$hide_buttons);
			$this->load->view("echo.php",$data);
		}
		public function autocomplete_request($category='') {
			$field	=	"description";
			$table	=	"inventory_items";
			if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."index"); }
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			if($category!=''){
				$category_query = " AND category=".$category;
			}else{
				$category_query = '';
			}
			$sql	=	"SELECT ".$field.",unit,id,item_code,brand,particular FROM ".$table." WHERE (".$field." LIKE '%".$search."%' OR item_code LIKE '%".$search."%') ".$category_query." AND stat=1 ORDER BY ".$field;
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						$ret[$ctr]['label']	=	$i[$field].", ".$i['brand']." ".$i['particular'];
						if(isset($i['id'])) {
							$ret[$ctr]['value']	=	$i['id'];
						}
						if(isset($i['unit'])) {
							$ret[$ctr]['label']	=	$i['item_code']." | ".$ret[$ctr]['label'];//." | ".$i['unit'];
							$ret[$ctr]['unit']	=	$i['unit'];
						}
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function purchase_order($action="", $id="") {
			$this->Abas->checkPermissions("purchasing|view_purchase_orders");
			$po		=	$this->Purchasing_model->getPurchaseOrder($id);
			$request=	$this->Purchasing_model->getRequest($po['request_id']);
			$pos	=	$this->db->query("SELECT * FROM inventory_po ORDER BY id DESC");
			if($action=="create") {
				$this->Abas->checkPermissions("purchasing|create_po");
				if(empty($_POST)) {
					$this->Abas->sysMsg("warnmsg", "No input recieved! Please try again.");
					$this->Abas->redirect(HTTP_PATH."purchasing");
				}
				$this->Mmm->debug($_POST);
				$request			=	$this->Purchasing_model->getRequest($id);
				$vessel				=	$this->Abas->getVessel($request['vessel_id']);
				$company			=	$this->Abas->getCompany($vessel->company);
				$request_details	=	$this->Purchasing_model->getRequestDetails($id,"AND supplier_id=0 AND status LIKE 'for purchase'");
				if(!empty($request_details)) {
					foreach($request_details as $ctr=>$pi) {
						$canvasssql			=	"SELECT * FROM inventory_request_details WHERE request_id=".$request['id']." AND item_id=".$pi['item_id']." AND supplier_id<>0 AND status LIKE 'for purchase'";
						$approved_canvass	=	$this->db->query($canvasssql);
						if($approved_canvass) {
							if($canvass=$approved_canvass->result_array()) {
								foreach($canvass as $ctr=>$c) {
									$suppliers[$c['supplier_id']][]	=	$c; // sorts PO items by supplier
									$suppliers[$c['supplier_id']]['totalcost']	=	(!isset($suppliers[$c['supplier_id']]['totalcost']))?($c['unit_price'] * $c['quantity']):$suppliers[$c['supplier_id']]['totalcost']+($c['unit_price'] * $c['quantity']);
								}
							}
						}
					}
				}
				$this->Mmm->debug($suppliers);
				unset($request_details);
				if(!empty($suppliers)) {
					foreach($suppliers as $sctr=>$items) {
						if(isset($_POST['supplier'][$sctr])) {
							if($_POST['purchase_type'][$sctr]=="PO"){
								$control_number = $this->Abas->getNextSerialNumber("inventory_po",$company->id);
								$status = "For Purchase Order Approval";
								$order_type = "PO";
								$table = "inventory_po";
								$table_details = "inventory_po_details";
								$target_dir = WPATH.'assets/uploads/purchasing/purchase_order/attachments/';
							}elseif($_POST['purchase_type'][$sctr]=="JO"){
								$control_number = $this->Abas->getNextSerialNumber("inventory_job_orders",$company->id);
								$status = "For Job Order Approval";
								$order_type = "JO";
								$table = "inventory_job_orders";
								$table_details = "inventory_job_order_details";
								$target_dir = WPATH.'assets/uploads/purchasing/job_order/attachments/';
							}
							$supplierdata					=	$this->Abas->getSupplier($sctr);
							$vat							=	0;
							if(strtolower($po_supplier['vat_computation'])=='vatable') {
								$vat						=	($items['totalcost']-($items['totalcost']/1.12));
							}
							$create_po['tdate']				=	date("Y-m-d H:i:s");
							$create_po['deliver_on']		=	date("Y-m-d H:i:s", strtotime($_POST['estimated_delivery_date']));
							$create_po['supplier_id']		=	$supplierdata['id'];
							$create_po['company_id']		=	$company->id;
							$create_po['control_number']	=	$control_number;
							$create_po['amount']			=	$items['totalcost'];
							$create_po['location']			=	$this->Mmm->sanitize($_POST['location']);
							$create_po['remark']			=	$this->Mmm->sanitize($_POST['remark']);
							$create_po['purpose']			=	"";
							$create_po['reference_num']		=	"";
							$create_po['extended_tax']		=	$this->Mmm->sanitize($_POST['etax'][$sctr]); // $vatable_purchases*($_POST['etax'][$sctr]/100);
							$create_po['value_added_tax']	=	0; // $this->Mmm->sanitize($_POST['vat'][$sctr]); computed VAT is no longer stored since it is computed at every use
							$create_po['discount']			=	$this->Mmm->sanitize($_POST['discount'][$sctr]);
							$create_po['added_by']			=	$_SESSION['abas_login']['userid'];
							$create_po['added_on']			=	date("Y-m-d H:i:s");
							$create_po['request_id']		=	$request['id'];
							$create_po['stat']				=	1;
							$create_po['status']			=	$status;
							$create_po['payment_terms']		=	$this->Mmm->sanitize($_POST['payment_terms'][$sctr]);
							
							if($_POST['purchase_type']){
								$old_filename = explode(".", basename($_FILES["attach_file"]["name"][$sctr]));
								$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);
								if(end($old_filename)!=""){
									$create_po['file_path'] = $new_filename;
									$target_file = $target_dir . $new_filename;
									$uploaded = move_uploaded_file($_FILES["attach_file"]["tmp_name"][$sctr],$target_file);
								}
							}
							$checkPO						=	$this->Mmm->dbInsert($table, $create_po, "Create new ".$supplierdata['name']." ".$order_type." for ".$company->name." from ".$vessel->name." request ID ".$request['id']);
							unset($po);
							if(!$checkPO) {
								$this->Abas->sysMsg("errmsg", "Error creating PO/JO! Please try again.");
								$this->Abas->redirect($_SERVER['HTTP_REFERER']);
							}
							//$new_po_id	=	$this->db->insert_id(); //disable as it causes not to save the PO/JO id on the details; replaced by this code below
							if($_POST['purchase_type'][$sctr]=="PO"){
								$new_po_id = $this->Abas->getLastIDByTable('inventory_po');
							}else{
								$new_po_id = $this->Abas->getLastIDByTable('inventory_job_orders');
							}
							if(!empty($items)) {
								unset($pod);
								foreach($items as $cctr=>$canvass) {
									$item	=	$this->Inventory_model->getItem($canvass['item_id']);
									$item	=	$item[0];
									$this->Mmm->debug($canvass);
									if(is_array($canvass)) {
										if($_POST['purchase_type'][$sctr]=="PO"){
											$pod[$cctr]['po_id']	=	$new_po_id;
										}else{
											$pod[$cctr]['job_order_id']	=	$new_po_id;
										}
										$pod[$cctr]['item_id']				=	$item['id'];
										$pod[$cctr]['unit']					=	$item['unit'];
										if($_POST['purchase_type'][$sctr]=="PO"){
											$pod[$cctr]['packaging']			=	$canvass['packaging'];
										}
										$pod[$cctr]['unit_price']			=	$canvass['unit_price'];
										$pod[$cctr]['quantity']				=	$canvass['quantity'];
										$pod[$cctr]['stat']					=	1;
										$pod[$cctr]['remarks']				=	$canvass['remark'];
										$pod[$cctr]['request_detail_id']	=	$canvass['id'];
										$status_sql							=	"UPDATE inventory_request_details SET status='For Delivery' WHERE request_id=".$request['id']." AND (supplier_id=".$canvass['supplier_id']." OR supplier_id=0) AND item_id=".$canvass['item_id']." AND status LIKE 'For Purchase'";
										$this->Mmm->query($status_sql,"Update request details for ".$item['description']." during PO finalization");
										$unitprice_sql = "UPDATE inventory_items SET unit_price='".$canvass['unit_price']."' WHERE id=".$canvass['item_id'];
										$this->Mmm->query($unitprice_sql,"Update item unitprice details for ".$item['description']." during PO finalization");
									}
								}
								unset($pod['totalcost']);
								$checkPOD	=	$this->Mmm->multiInsert($table_details, $pod, "Create details for ".$order_type."#".$new_po_id);
								if($checkPOD) {
									$this->Abas->sysMsg("sucmsg", "Successfully encoded the ".$order_type." for ".$supplierdata['name']);
								}
								else {
									$this->Abas->sysMsg("warnmsg", "Failed to encode the ".$order_type." for ".$supplierdata['name']);
								}
							}
						}
					}
				}
				$this->Abas->redirect(HTTP_PATH."purchasing");
			}
			elseif ($action=="print") {
				if($po['status']=="Cancelled") {
					$this->Abas->sysMsg("warnmsg", "This PO has been cancelled!");
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
				if(empty($po['approved_by']) || empty($po['approved_on'])) {
					$this->Abas->sysMsg("warnmsg", "This PO is not yet approved!");
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
				}
				require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
				$check		=	$this->db->query("SELECT * FROM inventory_po WHERE id=".$id);
				if(!$check) {
					$this->Abas->sysMsg("errmsg", "This PO does not exist!");
					$this->Abas->redirect(HTTP_PATH."purchasing");
				}
				if(!$check->row()) {
					$this->Abas->sysMsg("errmsg", "PO not found!");
					$this->Abas->redirect(HTTP_PATH."purchasing");
				}
				$po			=	(array)$check->row();
				$po_content	=	$this->db->query("SELECT * FROM inventory_po_details WHERE po_id=".$id);
				if(!$po_content) {
					$this->Abas->sysMsg("errmsg", "This PO does not exist!");
					$this->Abas->redirect(HTTP_PATH."purchasing");
				}
				if(!$po_content->row()) {
					$this->Abas->sysMsg("errmsg", "PO not found!");
					$this->Abas->redirect(HTTP_PATH."purchasing");
				}
				$po_content	=	$po_content->result_array();
				if(empty($po_content)) {
					$this->Abas->sysMsg("errmsg", "This PO does not have any items!");
					$this->Abas->redirect(HTTP_PATH."purchasing");
				}
				$po_supplier			=	$this->Abas->getSupplier($po['supplier_id']);
				$po['supplier_name']	=	$po_supplier['name'];
				$update['status']		=	"For Delivery";
				$check					=	$this->Mmm->dbUpdate("inventory_po", $update, $po['id'], "Print Purchase Order for ".$po['supplier_name']);
				if($check) {
					$this->Abas->sysMsg("sucmsg","Purchase Order for ".$po['supplier_name']." marked as For Delivery!");
				}
				$signatures['sir_jojo']		=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/55c44a7bfd3133286053a42ac3fe78da.png" width="200px" align="absmiddle" />';
				$signatures['sir_boyet']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/fd87612157d2ee30b2f670a3fb2a5d5a.png" width="200px" align="absmiddle" />';
				$signatures['sir_johnque']	=	'';
				$po_company				=	(array)$this->Abas->getCompany($po['company_id']);
				$po['company_name']		=	$po_company['name'];
				$request					=	$this->Purchasing_model->getRequest($po['request_id']);
				$table	=	"";
				$totalcost	=	0;
				foreach($po_content as $ctr=>$poi) {
					$item	=	$this->Inventory_model->getItem($poi['item_id']);
					$item	=	$item[0];
					$table	.=	'<tr style="text-align:center;">';
						$table	.= '<td width="10%">'.$item['item_code'].'</td>';

						$table	.= '<td width="40%" align="left">'.$item['item_name'].",".$item['brand'].$item['particular'].'</td>';
						$table	.= '<td width="10%">'.$poi['quantity'].'</td>';
						if($poi['packaging']==''){
							$table	.= '<td width="10%">'.$item['unit'].'</td>';
						}else{
							$table	.= '<td width="10%">'.$poi['packaging'].'</td>';
						}
						$table	.= '<td width="15%" style="text-align:right;">P'.number_format($poi['unit_price'],2).'</td>';
						$table	.= '<td width="15%" style="text-align:right;">P'.number_format($poi['unit_price']*$poi['quantity'],2).'</td>';
					$table	.=	'</tr>';
					$totalcost	=	$totalcost+($poi['unit_price']*$poi['quantity']);
				}
				$added_by				=	$this->Abas->getUser($po['added_by']);
				if(empty($added_by['full_name'])) {	$added_by['full_name']	=	"N/A <h1 style='color:#FF0000;'>This PO is NOT VALID</h1>"; }
				if(empty($added_by['signature'])) {
					$added_by['signature']	=	'';
				}
				if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$added_by['signature'])) {
					$added_by['signature']	=	'';
				}
				else {
					if($added_by['signature']!="") {
						$added_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$added_by['signature'].'" width="200px" align="absmiddle" />';
					}
				}
				$approved_by					=	$this->Abas->getUser($po['approved_by']);
				if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$approved_by['signature'])) {
					$approved_by['signature']	=	"";
				}
				else {
					if($approved_by['signature']!="") {
						$approved_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$approved_by['signature'].'" width="200px" align="absmiddle" />';
					}
				}
				if(!empty($request['details'])) {
					foreach($request['details'] as $request_detail) {
						$noted_by					=	$this->Abas->getUser($request_detail['request_approved_by']);
						if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$noted_by['signature'])) {
							$noted_by['signature']	=	"";
						}
						else {
							if($noted_by['signature']!="") {
								$noted_by['signature']	=	'<img src="'.LINK.'assets/images/digitalsignatures/'.$noted_by['signature'].'" width="200px" align="absmiddle" />';
							}
						}
						// force no value
						$noted_by					=	array("full_name"=>"", "signature"=>"");
					}
				}
				$user	=	$this->Abas->getUser($_SESSION['abas_login']['userid']);
				$gross_purchases			=	0;
				$vat						=	0;
				$etax						=	0;
				$vatable_purchases			=	$totalcost;
				$grand_total				=	$totalcost;
				if(strtolower($user['user_location'])!="makati") {
					$noted_by['full_name']	=	"Johnson Que";
					$noted_by['signature']	=	"";
				}else{
					$noted_by['full_name']	= "____________________________";
					$noted_by['signature']	=	"";
				}
				if($po_supplier['issues_reciepts']==1) {
					$gross_purchases		=	$totalcost;
					if(strtolower($po_supplier['vat_computation'])=='vatable') {
						$vat				=	($totalcost-($totalcost/1.12));
						$vatable_purchases	=	$totalcost-$vat;
					}
					$etax					=	($vatable_purchases*($po['extended_tax']/100));//(($po['extended_tax']/$totalcost)*100);
					$etax_percentage		=	0;
					if($po['extended_tax']>0) {
						$etax_percentage	=	$po['extended_tax'];
						$grand_total		=	$totalcost-$po['extended_tax'];
					}
					else {
						$grand_total		=	$totalcost;
					}
				}
				
				$table	.=	'<tr style="text-align:right;">';
				$table	.=	'<td colspan="5">Gross Purchases</td>';
				$table	.=	'<td>P'.number_format($gross_purchases,2).'</td>';
				$table	.=	'</tr>';
				$table	.=	'<tr style="text-align:right;">';
				$table	.=	'<td colspan="5">VATable Purchases</td>';
				$table	.=	'<td>P'.number_format($vatable_purchases,2).'</td>';
				$table	.=	'</tr>';
				$table	.=	'<tr style="text-align:right;">';
				$table	.=	'<td colspan="5">12% VAT</td>';
				$table	.=	'<td>P'.number_format($vat,2).'</td>';
				$table	.=	'</tr>';
				$table	.=	'<tr style="text-align:right;">';
				$table	.=	'<td colspan="5">Withholding Tax - Expanded ('.$etax_percentage.'%)</td>';
				$table	.=	'<td>(P'.number_format($etax,2).')</td>';
				$table	.=	'</tr>';
				$table	.=	'<tr style="text-align:right;">';
				$table	.=	'<td colspan="5">Amount Payable</td>';
				$table	.=	'<td>P'.number_format(($gross_purchases-$etax-$po['discount']),2).'</td>';
				$table	.=	'</tr>';
				$data['orientation']		=	"P";
				$data['pagetype']			=	"letter";
				$data['title']				=	"Purchase Order #" . $po['control_number'];
				$data['content']			=	'
				<style>
					div {
						font-size:12px;
					}
					table {
						margin:10px;
						font-size:10px;
					}
					.table-label {
						background-color:#000000;
						color:#FFFFFF;
						text-align:center;
					}
					#signature{
						float:left;
						margin-left:250px;
						margin-top:-250px
					}
					#signature_title{
						float:left;
						margin-left:250px;
						margin-top:-250px
					}
				</style>
				<div style="text-align:center;">
					<p style="font-size:10px">
					<div style="font-size:20px; font-weight:600"><strong>'.$po_company['name'].'</strong></div>
					'.$po_company['address'].'<br>
						Tel. Number: '.$po_company['telephone_no'].' Fax Number: '.$po_company['fax_no'].'<br>
						TIN: '.$po_company['company_tin'].'
					</p>
					<div style="font-size:18px; font-weight:600">Purchase Order</div>
				</div>
				<div>
					<table width="100%">
						<tr>
							<td style="text-align:right;">Vendor: </td>
							<td>'.$po_supplier['name'].'</td>
							<td style="text-align:right;">Date: </td>
							<td>'.date("j F Y", strtotime($po['tdate'])).'</td>
						</tr>
						<tr>
							<td style="text-align:right;">Attention: </td>
							<td>'.$po_supplier['contact_person'].'</td>
							<td style="text-align:right;">PO Number: </td>
							<td>'.$po['control_number'].'</td>
						</tr>
						<tr>
							<td style="text-align:right;">Fax #: </td>
							<td>'.$po_supplier['fax_no'].'</td>
							<td style="text-align:right;">Request #: </td>
							<td>'.$request['control_number'].'</td>
						</tr>
						<tr>
							<td style="text-align:right;">Tel. #: </td>
							<td>'.$po_supplier['telephone_no'].'</td>
							<td style="text-align:right;">VAT Type:</td>
							<td>'.$po_supplier['vat_computation'].'</td>
						</tr>
						<tr>
							<td style="text-align:right;">Address: </td>
							<td>'.$po_supplier['address'].'</td>
							<td style="text-align:right;">TIN: </td>
							<td>'.$po_supplier['tin'].'</td>
						</tr>
					</table>
				</div>
				<div style="clear:both;"><br/></div>
				<table border="1" cellpadding="5">
					<thead>
						<tr>
							<th class="table-label" width="10%">Item Code</th>
							<th class="table-label" width="40%">Description</th>
							<th class="table-label" width="10%">Quantity</th>
							<th class="table-label" width="10%">Unit</th>
							<th class="table-label" width="15%">Unit Price</th>
							<th class="table-label" width="15%">Total Price</th>
						</tr>
					</thead>
					<tbody>
						'.$table.'
					</tbody>
				</table>
				<br><br>
				<div style="font-size:12px">Request Transaction code: '.$request['id'].'</div>
				<div style="font-size:12px">Purchase Order Transaction code: '.$po['id'].'</div>
				<div style="font-size:12px">Terms: '.($po_supplier['payment_terms']==0?"":$po_supplier['payment_terms']).'</div>
				<div style="font-size:12px">For: '.$request['vessel_name'].'</div>
				<div style="font-size:12px">Remark: '.$po['remark'].'</div>
				<br><br>
				<table id="signature_title">
					<tr>
						<td>Prepared by:</td>
						<td>Noted by:</td>
						<td>Approved by:</td>
					</tr>
				</table>
				<table id="signature">
					<tr>
						<td>'.$added_by['signature'].'</td>
						<td>'.$noted_by['signature'].'</td>
						<td>'.$approved_by['signature'].'</td>
					</tr>
					<tr style="text-align:center;">
						<td><u>'.$added_by['full_name'].'</u></td>
						<td><u>'.$noted_by['full_name'].'</u></td>
						<td><u>'.$approved_by['full_name'].'</u></td>
					</tr>
				</table>
				';
				$this->load->view('pdf-container.php',$data);
			}
			elseif ($action=="cancel") {
				$this->Abas->checkPermissions("purchasing|cancel_po");
				if(!empty($po['approved_by']) || !empty($po['approved_on'])) {
					if($po['approved_by']['id']!=$_SESSION['abas_login']['userid']) {
						$this->Abas->sysNotif("Blocked PO Cancellation", $_SESSION['abas_login']['fullname']." attempted to cancel a purchase order approved by ".$po['approved_by']['full_name']."!", "Administrator", "warning");
						$this->Abas->sysMsg("warnmsg", "This PO has already been approved! Only ".$po['approved_by']['full_name']." may cancel this purchase order.");
						$this->Abas->redirect(HTTP_PATH."purchasing/purchase_order");
					}
				}
				$update['stat']		=	0;
				$update['status']	=	"Cancelled";
				$changesummary		=	$this->Mmm->dbUpdate("inventory_po", $update, $po['id'], "Cancel PO to ".$po['supplier_name']);
				if($changesummary==true) {
					if(!empty($po['details'])) {
						foreach($po['details'] as $ctr=>$detail) {
							if($detail['request_detail']['status']=="For Delivery") {
								$request_detail_canvasses_sql	=	"UPDATE inventory_request_details SET status='Cancelled' WHERE item_id=".$detail['item_id']." AND supplier_id<>0 AND request_id=".$po['request_id'];
								$request_detail_parent_sql		=	"UPDATE inventory_request_details SET status='Cancelled' WHERE item_id=".$detail['item_id']." AND supplier_id=0 AND request_id=".$po['request_id']." AND status LIKE 'For Delivery'";
								$request_detail_canvasses		=	$this->db->query($request_detail_canvasses_sql);
								$request_detail_parent			=	$this->db->query($request_detail_parent_sql);
							}
						}
					}
					$notif_msg	=	"Purchase order for ".$po['supplier_name']." has been cancelled by ".$_SESSION['abas_login']['fullname'].". Click <a href='".HTTP_PATH."purchasing/purchase_order/view/".$id."' data-toggle='modal' data-target='#modalDialog'>HERE</a> to view.";
					$this->Abas->sysNotif("Cancelled Purchase Order", $notif_msg, "Administrator");
					$this->Abas->sysMsg("msg", "You have successfully cancelled PO#".$po['control_number']." for ".$po['vessel_name']." from ".$po['supplier_name']);
				}
				$this->Abas->redirect(HTTP_PATH."purchasing/purchase_order");
			}
			elseif($action=="json") {
				if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$data	=	$this->Abas->createBSTable("inventory_po",$search,$limit,$offset,$order,$sort);
					if($data!=false) {
						foreach($data['rows'] as $ctr=>$po) {
							$data['rows'][$ctr]	=	$this->Purchasing_model->getPurchaseOrder($po['id']);
							$data['rows'][$ctr]['amount']	=	number_format($data['rows'][$ctr]['amount'],2);
							if(!empty($po['approved_by'])) {
								$data['rows'][$ctr]['approver_name']	=	$data['rows'][$ctr]['approved_by']['full_name'];
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				}
			}
			elseif($action=="approved_json" || $action=="unapproved_json") {
				if(isset($_GET['order']) && isset($_GET['limit']) && isset($_GET['offset'])) {
					$limit			=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset			=	isset($_GET['offset'])?$_GET['offset']:"";
					$order			=	isset($_GET['order'])?$_GET['order']:"";
					$sort			=	isset($_GET['sort'])?$_GET['sort']:"";
					$searchstring	=	isset($_GET['search'])?$_GET['search']:"";
					$table			=	"inventory_po";
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
					$searchfields		=	"";
					if($searchstring!="") {
						$searchfields	=	"";
						foreach($tablefields as $tf) {
							if($searchfields!="") $searchfields.="OR ";
							$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' ";
						}
					}
					$approval	=	"";
					if($action=="approved_json") $approval		=	"`approved_by` IS NOT NULL";
					if($action=="unapproved_json") $approval	=	"`approved_by` IS NULL";
					$sql	=	"SELECT * FROM ".$table." WHERE status NOT LIKE 'cancelled' AND $approval $searchfields $order $offset $limit";
					$total	=	"SELECT id FROM ".$table." WHERE status NOT LIKE 'cancelled' AND $approval $searchfields";
					$all	=	$this->db->query($sql);
					$total	=	$this->db->query($total);
					if($all) {
						$data	=	array("total"=>count($total->result_array()),"rows"=>$all->result_array());
					}
					else {
						$data	=	false;
					}
					if($data!=false) {
						foreach($data['rows'] as $ctr=>$po) {
							$data['rows'][$ctr]['amount']	=	number_format($data['rows'][$ctr]['amount'],2);
							$data['rows'][$ctr]	=	$this->Purchasing_model->getPurchaseOrder($po['id']);
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				}
			}
			elseif($action=="view") {
				//$this->Mmm->debug($po);
				$data['selected_po']	=	$po;
				$this->load->view('purchasing/purchase_order.php',$data);
			}
			elseif($action=="view_unapproved") {
				$data['jsonview']			=	"unapproved";
				$data['viewfile']			=	"purchasing/purchase_orders.php";
				$this->load->view('gentlella_container.php',$data);
			}
			elseif($action=="view_approved") {
				$data['jsonview']			=	"approved";
				$data['viewfile']			=	"purchasing/purchase_orders.php";
				$this->load->view('gentlella_container.php',$data);

			}
			elseif($action=="approve") {
				if($po['user_can_approve']) {
					$update['approved_by']	=	$_SESSION['abas_login']['userid'];
					$update['approved_on']	=	date("Y-m-d H:i:s");
					$update['status']		=	"For Ordering";
					$check					=	$this->Mmm->dbUpdate("inventory_po", $update, $po['id'], "Approve Purchase Order for ".$po['supplier']['name']);
					if($check) {
						$this->Abas->sysMsg("sucmsg","Purchase Order for ".$po['supplier']['name']." approved!");
						$this->Abas->sysNotif("Approved Purchase Order", $_SESSION['abas_login']['fullname']." approved purchase order transaction code ".$po['id']."!", "Purchasing");
					}
					else {
						$this->Abas->sysMsg("errmsg","Purchase Order for ".$po['supplier']['name']." not approved! Please try again.");
					}
				}
				else {
					$this->Abas->sysMsg("warnmsg","You lack the neccessary permission to approve purchase orders. This action has been reported.");
					$this->Abas->sysNotif("Blocked Approval - Purchase Order", $_SESSION['abas_login']['fullname']." attempted to approve purchase order with transaction code ".$po['id'].".", "Purchasing");
				}
				$this->Abas->redirect(HTTP_PATH."purchasing/purchase_order");
			}
			else {
				$data['viewfile']			=	"purchasing/purchase_orders.php";
				$this->load->view('gentlella_container.php',$data);
			}
		}
		public function purchase_order_report($action="") {$data=array();
			$this->Abas->checkPermissions("purchasing|view_purchase_orders");
			$mainview	=	"gentlella_container.php";
			if($action=="filter") {
				$mainview	=	"purchasing/purchase_order_report_filter.php";
			}
			elseif($action=="report") {
				if(empty($_GET)) {
					$this->Abas->sysMsg("warnmsg", "Invalid report parameters! Please try again.");
					$this->Abas->redirect(HTTP_PATH."purchasing/purchase_order");
				}
				$dstart		=	date("Y-m-d",strtotime($_GET['dstart']))." 00:00:00";
				$dfinish	=	date("Y-m-d",strtotime($_GET['dfinish']))." 23:59:59";
				if($dstart=="1970-01-01 00:00:00" || $dfinish=="1970-01-01 23:59:59" || (strtotime($dstart)>strtotime($dfinish))) {
					$this->Abas->sysMsg("warnmsg", "Invalid selected date(s)! Please try again.");
					$this->Abas->redirect(HTTP_PATH."purchasing/purchase_order");
				}
				$supplier		=	$this->Abas->getSupplier($_GET['supplier']);
				$supplier_query	=	"";
				if(!empty($supplier)) {
					$supplier_query	=	"AND supplier_id=".$supplier['id'];
				}
				$category	=	isset($_GET['category'])?$_GET['category']:"";
				if(!empty($category)){
					$category_query	=	"AND i.category=".$category;
				}
				$data['company']		=	(object)array("name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
				$company		=	$this->Abas->getCompany($_GET['company']);
				$company_query	=	"";
				$category_query	=	"";
				if(!empty($company)) {
					$data['company']=	$company;
					$company_query	=	"AND company_id=".$company->id;
				}
				$status_query		=	"AND po.status NOT LIKE 'Cancelled'";
				if($_GET['status']=="Cancelled") {
					$status_query	=	"AND po.status LIKE 'Cancelled'";
				}
				/* M-M-M-Monster query!
				 *
				 * Gets PO data and related data from the related tables
				 * The formula as amount is the sum of the quantity and unit price
				 * of all the details associated with the purchase order.
				 * This ensures accuracy in the display of the summary and of the details.
				 */

				if($_GET['service_status']=='all'){
					$service_status_query = "";
				}elseif($_GET['service_status']=='served'){
					$service_status_query = " AND EXISTS (SELECT id FROM inventory_deliveries WHERE po_no = po.id)";
				}elseif($_GET['service_status']=='unserved'){
					$service_status_query = " AND NOT EXISTS (SELECT id FROM inventory_deliveries WHERE po_no = po.id)";
				}else{
					$service_status_query = "";
				}

				$query		=	"
					SELECT
						c.name AS company_name,
						po.id,
						po.tdate,
						po.status,
						po.discount,
						po.control_number,
						po.approved_on,
						po.added_on,
						po.request_id,
						s.name AS supplier_name,
						s.vat_computation AS supplier_vatable,
						adu.username AS added_by,
						adu.user_location AS added_at,
						apu.username AS approved_by,
						apu.user_location AS approved_at,
						po.extended_tax,
						po.value_added_tax,
						(SELECT sum(unit_price * quantity) from inventory_po_details WHERE po_id=po.id) AS amount,
						(SELECT vessel_id FROM inventory_requests WHERE id=po.request_id) AS vessel_id 
					FROM inventory_po AS po
					JOIN inventory_requests AS ir ON po.request_id=ir.id
					JOIN users AS adu ON po.added_by=adu.id
					JOIN users AS apu ON po.approved_by=apu.id
					JOIN suppliers AS s ON s.id=po.supplier_id
					JOIN companies AS c ON c.id=po.company_id
					WHERE
						po.tdate>='".$dstart."' AND po.tdate<='".$dfinish."'
						".$company_query."
						".$supplier_query."
						".$status_query."
						".$service_status_query."
					ORDER BY po.tdate DESC
					";
						

				$purchase_orders	=	$this->db->query($query);
				if($purchase_orders) {
					if($purchase_orders->row()) {
						$data['purchase_orders']	=	$purchase_orders->result_array();
					}
				}
				if(!isset($data['purchase_orders'])) {
					// $this->Abas->sysMsg("warnmsg", "No purchase orders found! Please try again.");
					// $this->Abas->redirect(HTTP_PATH."purchasing/purchase_order");
				}
				/*$top_items_query	=	"
				SELECT
					i.description,
					i.particular,
					i.category,
					(SELECT category FROM inventory_category WHERE id=i.category) AS category_name,
					SUM(pod.quantity) AS total_quantity,
					SUM(pod.unit_price*pod.quantity) AS total_amount
				FROM inventory_po_details AS pod
				JOIN inventory_items AS i ON i.id=pod.item_id
				JOIN inventory_po AS po ON pod.po_id=po.id
				JOIN suppliers AS s ON s.id=po.supplier_id
				JOIN companies AS c ON c.id=po.company_id
				WHERE
					po.tdate>='".$dstart."' AND po.tdate<='".$dfinish."'
					".$company_query."
					".$supplier_query."
					".$status_query."
				GROUP BY i.description, i.particular
				ORDER BY pod.quantity DESC
				";*/

				$top_items_query	=	"
				SELECT
					i.description,
					i.particular,
					i.category,
					ir.vessel_id,
					pod.po_id,
					po.control_number,
					po.status,
					po.tdate,
					(SELECT name FROM companies WHERE id=po.company_id) AS company_name,
					(SELECT category FROM inventory_category WHERE id=i.category) AS category_name,
					(SELECT name FROM suppliers WHERE id=po.supplier_id) AS supplier_name,
					SUM(pod.quantity) AS total_quantity,
					SUM(pod.unit_price*pod.quantity) AS total_amount
				FROM inventory_po_details AS pod
				JOIN inventory_items AS i ON i.id=pod.item_id
				JOIN inventory_po AS po ON pod.po_id=po.id
				JOIN inventory_requests AS ir ON ir.id=po.request_id
				JOIN suppliers AS s ON s.id=po.supplier_id
				JOIN companies AS c ON c.id=po.company_id
				WHERE
					po.tdate>='".$dstart."' AND po.tdate<='".$dfinish."'
					".$company_query."
					".$category_query."
					".$supplier_query."
					".$status_query."
				GROUP BY i.description, i.particular,ir.vessel_id
				ORDER BY pod.quantity DESC
				";

				$top_items	=	$this->db->query($top_items_query);
				if($top_items) {
					if($top_items->row()) {
						$data['top_items']	=	$top_items->result_array();
					}
				}
				$data['viewfile']	=	"purchasing/purchase_order_report.php";
			}
			elseif($action=="view") {

			}
			elseif($action=="print") {

			}
			$this->load->view($mainview, $data);
		}
		public function canvass($action='',$value=''){
			$this->Abas->checkPermissions("purchasing|view_canvassed_items");
			$data = array();
			switch ($action) {
				case 'load':
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					if($value=='approved'){
						$where = "canvass_approved_on IS NOT NULL AND supplier_id<>0 AND (status='For Purchase' OR status='For Delivery')";
						$filter = ' GROUP BY request_id';
					}elseif($value=='unapproved'){
						$where = "canvass_approved_on IS NULL AND supplier_id<>0 AND status='For Canvass Approval'";
						$filter = ' GROUP BY request_id';
					}
					$data = $this->Abas->getDataForBSTable("inventory_request_details",$search,$limit,$offset,$order,$sort,$where,$filter);
					foreach($data['rows'] as $ctr=>$row) {
						if($row['request_id']!=NULL){
							$request = $this->Purchasing_model->getRequest($row['request_id']);
							$data['rows'][$ctr]['control_number']	= $request['control_number'];
							$data['rows'][$ctr]['company_name']	= $request['company']->name;
							$data['rows'][$ctr]['vessel_name']	= $request['vessel_name'];
							$data['rows'][$ctr]['department_name']	= $request['department_name'];
						}
						if($row['canvass_approved_on']!=NULL) {
							$data['rows'][$ctr]['approved_on']	=	date("j F Y h:i A", strtotime($row['canvass_approved_on']));
						}
						if($row['canvass_approved_by']!=NULL) {
							$data['rows'][$ctr]['approved_by']	=	$this->Abas->getUser($row['canvass_approved_by'])['full_name'];
						}
						if($row['added_on']!=NULL) {
							$data['rows'][$ctr]['canvassed_on']	=	date("j F Y h:i A", strtotime($row['added_on']));
						}
						if($row['added_by']!=NULL) {
							$data['rows'][$ctr]['canvassed_by']	=	$this->Abas->getUser($row['added_by'])['full_name'];
						}
						$status = ucwords($row['status']);
						if($status=='For Purchase' || $status=='For Delivery') {
							$data['rows'][$ctr]['status']	=	"Canvass Approved";
						}

					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}	
				break;
				case 'listview':
					$data['filter']=$value;
					$data['viewfile']	='purchasing/canvass/listview.php';
					$this->load->view("gentlella_container.php",$data);
				break;
				case 'filter':
					$this->load->view('purchasing/canvass/filter.php');
				break;
				case 'print':
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					require(WPATH.'assets/fpdf/fpdf.php');
					$data['CR'] = $this->Purchasing_model->getRequest($value);
					$this->load->view('purchasing/canvass/print.php',$data);
				break;
				case 'report':
					$vessel_id	=	isset($_GET['vessel'])?$_GET['vessel']:"";
					$canvassed_by_id	=	isset($_GET['canvassed_by'])?$_GET['canvassed_by']:"";
					$data['dstart']	=	isset($_GET['dstart'])?$_GET['dstart']:"";
					$data['dfinish']	=	isset($_GET['dfinish'])?$_GET['dfinish']:"";
					$append = "";

					if($vessel_id!=""){
						$data['vessel'] =  $this->Abas->getVessel($vessel_id);
						$append .= " AND inventory_requests.vessel_id=".$vessel_id;
					}
					if($canvassed_by_id!=""){
						$data['canvasser'] = $this->Abas->getuser($canvassed_by_id);
						$append .= " AND inventory_request_details.added_by=".$canvassed_by_id;
					}
					if($data['dstart']!=""){
						$append .= " AND inventory_request_details.added_on>='".date("Y-m-d",strtotime($data['dstart']))."' AND inventory_request_details.added_on<='".date("Y-m-d",strtotime($data['dfinish']))."'";
					}
					$sql = "SELECT * FROM inventory_request_details INNER JOIN inventory_requests ON inventory_request_details.request_id = inventory_requests.id  WHERE inventory_request_details.canvass_approved_on IS NOT NULL AND inventory_request_details.supplier_id<>0 AND (inventory_request_details.status='For Purchase' OR inventory_request_details.status='For Delivery')".$append." GROUP BY inventory_request_details.request_id";
					$query = $this->db->query($sql);
					if($query){
						$data['CR'] = $query->result();
					}
					$data['viewfile']	='purchasing/canvass/report.php';
					$this->load->view("gentlella_container.php",$data);
					//$this->Mmm->debug($sql);
				break;
			}
		}
		public function job_order($action='',$value=''){
			switch ($action) {
				case 'load':
					if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						if($value=='approved'){
							$where = "approved_by IS NOT NULL AND status<>'Cancelled'";
							$filter = '';
						}elseif($value=='unapproved'){
							$where = "approved_by IS NULL AND status<>'Cancelled'";
							$filter = '';
						}elseif($value=='all'){
							$where = '';
							$filter = '';
						}
						$data = $this->Abas->getDataForBSTable("inventory_job_orders",$search,$limit,$offset,$order,$sort,$where,$filter);
						foreach($data['rows'] as $ctr=>$row) {
							if(isset($row['company_id'])){
								$company = $this->Abas->getCompany($row['company_id']);
								$data['rows'][$ctr]['company_name'] = $company->name;
							}
							if(isset($row['supplier_id'])){
								$supplier = $this->Abas->getSupplier($row['supplier_id']);
								$data['rows'][$ctr]['supplier_name'] = $supplier['name'];
							}
							if(isset($row['request_id'])){
								$request = $this->Purchasing_model->getRequest($row['request_id']);
								$data['rows'][$ctr]['vessel_name'] = $request['vessel_name'];
							}
							if(isset($row['id'])){
								$request_payments		=	$this->db->query("SELECT id FROM ac_request_payment WHERE reference_id=".$row['id']." AND reference_table='inventory_job_orders'");
								$request_payments		=	$request_payments->result_array();
								if(count($request_payments)>0){
									$data['rows'][$ctr]['service_status'] = "Served";
								}else{
									$data['rows'][$ctr]['service_status'] = "Unserved";
								}
							}
							if(isset($row['approved_by'])){
								$user = $this->Abas->getUser($row['approved_by']);
								$data['rows'][$ctr]['approver_name'] = $user['full_name'];
							}
							if(isset($row['amount'])){
								$data['rows'][$ctr]['amount'] = number_format($row['amount'],2,'.',',');
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
					break;
				case 'listview':
					$this->Abas->checkPermissions("purchasing|view_job_orders");
					$data['filter'] = $value;
					$data['viewfile']	=	"purchasing/job_order/listview.php";
					$this->load->view('gentlella_container.php',$data);
					break;
				case 'view':
					$this->Abas->checkPermissions("purchasing|view_job_orders");
					$data['jo'] = $this->Purchasing_model->getJobOrder($value);
					$data['jo_details'] = $this->Purchasing_model->getJobOrderDetails($data['jo']->id);
					$this->load->view('purchasing/job_order/view.php',$data);
					break;
				case 'approve':
					$jo = $this->Purchasing_model->getJobOrder($value);
					if($jo->user_can_approve) {
						$update['approved_by']	=	$_SESSION['abas_login']['userid'];
						$update['approved_on']	=	date("Y-m-d H:i:s");
						$update['status']		=	"For Ordering";
						$check					=	$this->Mmm->dbUpdate("inventory_job_orders", $update, $jo->id, "Approve Job Order for ".$jo->supplier_name);
						if($check) {
							$this->Abas->sysMsg("sucmsg","Job Order for ".$jo->supplier_name." has been approved!");
							$this->Abas->sysNotif("Approved Job Order", $_SESSION['abas_login']['fullname']." approved job order with transaction code ".$jo->id."!", "Purchasing");
						}
						else {
							$this->Abas->sysMsg("errmsg","Job Order for ".$jo->supplier_name." not approved! Please try again.");
						}
					}
					else {
						$this->Abas->sysMsg("warnmsg","You lack the neccessary permission to approve job orders. This action has been reported.");
						$this->Abas->sysNotif("Blocked Approval - Purchase Order", $_SESSION['abas_login']['fullname']." attempted to approve job order with transaction code ".$jo->id.".", "Purchasing");
					}
						$this->Abas->redirect(HTTP_PATH."purchasing/job_order/listview/approved");
					break;
				case 'cancel':
					$this->Abas->checkPermissions("purchasing|cancel_jo");
					$jo = $this->Purchasing_model->getJobOrder($value);
					$jo_details = $this->Purchasing_model->getJobOrderDetails($jo->id);
					if(!empty($jo->approved_by) || !empty($jo->approved_on)) {
						if($jo->approved_by['id']!=$_SESSION['abas_login']['userid']) {
							$this->Abas->sysNotif("Blocked JO Cancellation", $_SESSION['abas_login']['fullname']." attempted to cancel a job order approved by ".$jo->approver_name."!", "Administrator", "warning");
							$this->Abas->sysMsg("warnmsg", "This JO has already been approved! Only ".$jo->approver_name." may cancel this job order.");
							$this->Abas->redirect($_SERVER['HTTP_REFERER']);
						}
					}
					$update['stat']		=	0;
					$update['status']	=	"Cancelled";
					$changesummary		=	$this->Mmm->dbUpdate("inventory_job_orders", $update, $jo->id, "Cancel JO to ".$jo->supplier_name);
					if($changesummary==true) {
						if(!empty($jo_details)) {
							foreach($jo_details as $ctr=>$detail) {
									$request_detail_canvasses_sql	=	"UPDATE inventory_request_details SET status='Cancelled' WHERE item_id=".$detail->item_id." AND status='For Delivery' AND supplier_id<>0 AND request_id=".$jo->request_id;
									$request_detail_parent_sql		=	"UPDATE inventory_request_details SET status='Cancelled' WHERE item_id=".$detail->item_id." AND supplier_id=0 AND status='For Delivery' AND request_id=".$jo->request_id." AND status LIKE 'For Delivery'";
									$request_detail_canvasses		=	$this->db->query($request_detail_canvasses_sql);
									$request_detail_parent			=	$this->db->query($request_detail_parent_sql);
							}
						}
						$notif_msg	=	"Job order for ".$po['supplier_name']." has been cancelled by ".$_SESSION['abas_login']['fullname'].". Click <a href='".HTTP_PATH."purchasing/job_order/view/".$value."' data-toggle='modal' data-target='#modalDialog'>HERE</a> to view.";
						$this->Abas->sysNotif("Cancelled Job Order", $notif_msg, "Administrator");
						$this->Abas->sysMsg("msg", "You have successfully cancelled JO with Transaction Code No.".$jo->id." for ".$jo->vessel_name." from ".$jo->supplier_name);
					}
					$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					break;
				case 'print':
					$this->Abas->checkPermissions("purchasing|view_job_orders");
					$data['jo'] = $this->Purchasing_model->getJobOrder($value);
					$data['jo_details'] = $this->Purchasing_model->getJobOrderDetails($data['jo']->id);
					$update['status']	=	"For Delivery";
					$changesummary		=	$this->Mmm->dbUpdate("inventory_job_orders", $update, $data['jo']->id, "Marked JO with Transaction Code No.".$data['jo']->id." as 'For Delivery!'");
					if($changesummary) {
						require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
						$this->Abas->sysMsg("sucmsg","Job Order with Transaction Code No.".$data['jo']->id." for ".$data['jo']->supplier_name." has been marked as 'For Delivery'!");
						$this->load->view('purchasing/job_order/print.php',$data);
					}else{
						$this->Abas->sysMsg("warnmsg", "There was an error while viewing the print for this Job Order.");
					}
					break;
				case 'view_request_for_payment':
					$this->Abas->checkPermissions("purchasing|view_job_orders");
					$data['request_for_payment'] = $this->Accounting_model->getRequestPayment($value);
					$this->load->view('purchasing/job_order/view_request_for_payment.php',$data);
					break;
				case 'filter':
					$this->Abas->checkPermissions("purchasing|view_job_orders");
					$this->load->view('purchasing/job_order/filter.php');
					break;
				case 'report':
					$this->Abas->checkPermissions("purchasing|view_job_orders");
					$data=array();
					$dstart		=	date("Y-m-d",strtotime($_GET['dstart']))." 00:00:00";
					$dfinish	=	date("Y-m-d",strtotime($_GET['dfinish']))." 23:59:59";
					if($dstart=="1970-01-01 00:00:00" || $dfinish=="1970-01-01 23:59:59" || (strtotime($dstart)>strtotime($dfinish))) {
						$this->Abas->sysMsg("warnmsg", "Invalid selected date(s)! Please try again.");
						$this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$supplier		=	$this->Abas->getSupplier($_GET['supplier']);
					$supplier_query	=	"";
					if(!empty($supplier)) {
						$supplier_query	=	"AND supplier_id=".$supplier['id'];
					}
					$data['company']		=	(object)array("name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
					$company		=	$this->Abas->getCompany($_GET['company']);
					$company_query	=	"";
					if(!empty($company)) {
						$data['company']=	$company;
						$company_query	=	"AND company_id=".$company->id;
					}
					$status_query		=	"AND jo.status NOT LIKE 'Cancelled'";
					if($_GET['status']=="Cancelled") {
						$status_query	=	"AND jo.status LIKE 'Cancelled'";
					}
					$query				=	"
					SELECT
						c.name AS company_name,
						jo.id,
						jo.tdate,
						jo.status,
						jo.discount,
						jo.request_id,
						jo.control_number,
						jo.approved_on,
						jo.added_on,
						s.name AS supplier_name,
						s.vat_computation AS supplier_vatable,
						adu.username AS added_by,
						adu.user_location AS added_at,
						apu.username AS approved_by,
						apu.user_location AS approved_at,
						jo.extended_tax,
						jo.value_added_tax,
						(SELECT sum(unit_price * quantity) from inventory_job_order_details WHERE job_order_id=jo.id) AS amount,
						(SELECT vessel_id FROM inventory_requests WHERE id=jo.request_id) AS vessel_id
					FROM inventory_job_orders AS jo
					JOIN users AS adu ON jo.added_by=adu.id
					JOIN users AS apu ON jo.approved_by=apu.id
					JOIN suppliers AS s ON s.id=jo.supplier_id
					JOIN companies AS c ON c.id=jo.company_id
					WHERE
						jo.tdate>='".$dstart."' AND jo.tdate<='".$dfinish."'
						".$company_query."
						".$supplier_query."
						".$status_query."
					ORDER BY jo.tdate DESC
					";
					$job_orders	=	$this->db->query($query);
					if($job_orders) {
						if($job_orders->row()) {
							$data['job_orders']	=	$job_orders->result_array();
						}
					}
					if(!isset($data['job_orders'])) {
						// $this->Abas->sysMsg("warnmsg", "No job orders found! Please try again.");
						// $this->Abas->redirect($_SERVER['HTTP_REFERER']);
					}
					$top_items_query	=	"
					SELECT
						i.description,
						jod.quantity
					FROM inventory_job_order_details AS jod
					JOIN inventory_items AS i ON i.id=jod.item_id
					JOIN inventory_job_orders AS jo ON jod.job_order_id=jo.id
					JOIN suppliers AS s ON s.id=jo.supplier_id
					JOIN companies AS c ON c.id=jo.company_id
					WHERE
						jo.tdate>='".$dstart."' AND jo.tdate<='".$dfinish."'
						".$company_query."
						".$supplier_query."
						".$status_query."
					GROUP BY i.description
					ORDER BY jod.quantity DESC LIMIT 30
					";
					$top_items	=	$this->db->query($top_items_query);
					if($top_items) {
						if($top_items->row()) {
							$data['top_items']	=	$top_items->result_array();
						}
					}
					//$this->Mmm->debug($data['job_orders']);
					$data['viewfile']	=	"purchasing/job_order/report.php";
					$this->load->view("gentlella_container.php", $data);
					break;
			}
		}
		public function get_company_name($vessel_id){
			$vessel = $this->Abas->getVessel($vessel_id);
			if($vessel){
				$company = $this->Abas->getCompany($vessel->company);
				$data['company_name'] = $company->name;
				$data['company_id'] = $company->id;
				echo json_encode($data);
			}
		}
		public function vessel_purchases($action){
			if($action=='filter'){
				$data['vessels'] = $this->Abas->getVessels(false);
				$this->load->view("purchasing/vessel_purchase_report_filter.php", $data);
			}elseif($action=='report'){
				$vessel_id = $_GET['vessel'];
				$date_from = $_GET['dstart'];
				$date_to = $_GET['dfinish'];
				$data['vessel'] = $this->Abas->getVessel($vessel_id);
				$data['purchase_orders'] = $this->Purchasing_model->getVesselPO($vessel_id,$date_from,$date_to);
				$data['viewfile']	=	"purchasing/vessel_purchase_report.php";
				$this->load->view("gentlella_container.php", $data);
			}
		}
		public function purchase_order_autocomplete_list($status,$company_id=null){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			if($status=='for_ordering'){
				$status='For ordering';
			}
			if($status=='for_delivery'){
				$status='For Delivery';
			}
			if($company_id!=null){
				$sql	=	"SELECT * FROM inventory_po WHERE id LIKE '%".$search."%' AND company_id=".$company_id." AND status='".$status."' ORDER BY id LIMIT 0,12";	
			}else{
				$sql	=	"SELECT * FROM inventory_po WHERE id LIKE '%".$search."%' AND status='".$status."' ORDER BY id LIMIT 0,12";
			}
			$po	=	$this->db->query($sql);
			if($po) {
				if($po->row()) {
					$po	=	$po->result_array();
					$ret	=	array();
					foreach($po as $ctr=>$i) {
						$ret[$ctr]['label']	=	"PO Transaction Code No. ".$i['id']." | Control No. ".$i['control_number']. " (".$i['remark'].")";
						$ret[$ctr]['value']	=	$i['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function job_order_autocomplete_list($status,$company_id=null){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			if($status=='for_ordering'){
				$status='For Ordering';
			}
			if($status=='for_delivery'){
				$status='For Delivery';
			}
			if($company_id!=null){
				$sql	=	"SELECT * FROM inventory_job_orders WHERE id LIKE '%".$search."%' AND company_id=".$company_id." AND status='".$status."' ORDER BY id LIMIT 0,12";	
			}else{
				$sql	=	"SELECT * FROM inventory_job_orders WHERE id LIKE '%".$search."%' AND status='".$status."' ORDER BY id LIMIT 0,12";
			}
			$po	=	$this->db->query($sql);
			if($po) {
				if($po->row()) {
					$po	=	$po->result_array();
					$ret	=	array();
					foreach($po as $ctr=>$i) {
						$ret[$ctr]['label']	=	"JO Transaction Code No. ".$i['id']." | Control No. ".$i['control_number']. " (".$i['remark'].")";
						$ret[$ctr]['value']	=	$i['id'];
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		public function project_reference_autocomplete_list(){
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);

			$sql	=	"SELECT * FROM am_schedule_logs WHERE reference_number LIKE '%".$search."%' AND ( status='Approved' OR status='Final') ORDER BY id LIMIT 0,12";
			$po	=	$this->db->query($sql);
			if($po) {
				if($po->row()) {
					$po	=	$po->result_array();
					$ret	=	array();
					foreach($po as $ctr=>$i) {
						$ret[$ctr]['label']	=	$i['reference_number'];
						$company = $this->Abas->getCompany($i['company_id']);
						$ret[$ctr]['company_id']	=	$company->id;
						$ret[$ctr]['company']	=	$company->name;
						if($i['type']=='Vessel'){
							$vessel = $this->Abas->getVessel($i['asset_id']);
							$ret[$ctr]['asset']	= $vessel->id;
						}elseif($i['type']=='Truck'){
							$ret[$ctr]['asset']	=	99994;//selects Avega Trucking	
						}
						
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}

		}
		public function get_item_packaging($item_id){
			$ret = $this->Inventory_model->getPackagingByItem($item_id);
			header('Content-Type: application/json');
			echo json_encode($ret);
			exit();
		}
	}
?>