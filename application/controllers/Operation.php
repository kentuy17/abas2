<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Operation extends CI_Controller {
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('url_helper');
		$this->load->library('form_validation');
		$this->load->model("Abas");
		$this->load->model("Operation_model");
		$this->load->model("Accounting_model");
		$this->load->model("Billing_model");
		$this->load->model("Collection_model");
		$this->load->model("Mmm");
		$this->output->enable_profiler(FALSE);
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
		$this->Abas->checkPermissions("operations|view");
		define("SIDEMENU", "Operations" );
	}
	public function index() {
		$data=array();
		$data['contracts']	=	$this->Operation_model->getContracts();
		if($this->Abas->checkPermissions("operations|view_contract",false)) {
			$data['viewfile']			=	"operation/contract/listview.php";
		}
		elseif($this->Abas->checkPermissions("operations|view_service_order",false)) {
			$data['viewfile']			=	"operation/service_order/listview.php";
		}
		elseif($this->Abas->checkPermissions("operations|view_out_turn_summary",false)) {
			$data['viewfile']			=	"operation/out_turn_summary/listview.php";
		}
		elseif($this->Abas->checkPermissions("operations|view_vessel_certificates",false)) {
			$data['viewfile']			=	"vessels/vessel_cert_view.php";
		}else{
			$this->Abas->checkPermissions("NO PERMISSION",true);
		}
		$this->load->view("gentlella_container.php",$data);
	}
	public function dashboard() {$data=array();
		$data['ap_billings']	=	$this->Operation_model->getApBillings();
		$data['ar_billings']	=	$this->Operation_model->getArBillings();
		$data['contracts']		=	$this->Operation_model->getContracts();
		$this->load->view('operation/dashboard',$data);
	}
	public function ap_approval_form($id='') {$data=array();
		if($id!='') {
			$data['summary']	=	$this->Operation_model->getApBilling($id);
			$data['details']	=	$this->Operation_model->getApBillingDetails($id);
			$this->load->view('operation/ap_approval_form',$data);
		}
	}
	public function contract_status_detail($id='') {$data=array();
		if($id!='') {
			$data['contract']	=	$this->Operation_model->getContract($id);
			if($data['contract']['sub_reference_no'] !='') {
				$ref							=	$data['contract']['sub_reference_no'];
				$data['billed_transaction']		=	$this->Operation_model->getArBilling($ref);
				$data['unbilled_transaction']	=	$this->Operation_model->getArBilling($ref);
				$this->load->view('operation/contract_status_detail',$data);
			}
		}
		$this->Abas->redirect(HTTP_PATH."operation/dashboard");
	}
	public function new_contract_form($id='') {$data=array();
		$data['companies']	=	$this->Abas->getCompanies();
		$data['clients']	=	$this->Abas->getClients();
		$data['vessels']	=	$this->Abas->getVessels(false);
		$data['services']	=	$this->Billing_model->getServices();
		$data['contracts']	=	$this->Operation_model->getContracts();
		if($id!='') {
			$data['contract']	=	$this->Operation_model->getContract($id);
		}
		$this->load->view('operation/new_contract_form',$data);
	}
	public function transaction_view($id='') {$data=array();
		$data['transaction_types']	=	$this->Operation_model->getTransactionTypes();
		$data['regions']			=	$this->Abas->getRegions();
		$data['warehouses']			=	$this->Operation_model->getTruckingLocations();
		$data['wsrs']				=	$this->Operation_model->getWsrs();
		if($id!='') {
			$data['transaction']	=	$this->Operation_model->getTransaction($id);
		}
		$this->load->view('operation/transaction_view',$data);
	}
	public function wsr_view() {$data=array();
		$data['transaction_types']	=	$this->Operation_model->getTransactionTypes();
		$data['regions']			=	$this->Abas->getRegions();
		$data['warehouses']			=	$this->Operation_model->getTruckingLocations();
		$data['transactions']		=	$this->Operation_model->getTransactions('WSR');
		$this->load->view('operation/wsr_view',$data);
	}
	public function wsr_form($id='') {$data=array();
		$data['transaction_types']	=	$this->Operation_model->getTransactionTypes();
		$data['regions']			=	$this->Abas->getRegions();
		$data['warehouses']			=	$this->Operation_model->getTruckingLocations();
		$data['vessels']			=	$this->Abas->getVessels();
		$data['service_providers']	=	$this->Operation_model->getServiceProviders();
		if($id!='') {
			$data['transaction']	=	$this->Operation_model->getTransaction($id);
		}
		$this->load->view('operation/wsr_form',$data);
	}
	public function wsi_view($id='') {$data=array();
		$data['transaction_types']	=	$this->Operation_model->getTransactionTypes();
		$data['regions']			=	$this->Abas->getRegions();
		$data['warehouses']			=	$this->Operation_model->getTruckingLocations();
		$data['transactions']		=	$this->Operation_model->getTransactions('WSI');
		if($id!='') {
			$data['transaction']	=	$this->Operation_model->getTransaction($id);
		}
		$this->load->view('operation/wsi_view',$data);
	}
	public function wsi_form($id='') {$data=array();
		$data['transaction_types']	=	$this->Operation_model->getTransactionTypes();
		$data['regions']			=	$this->Abas->getRegions();
		$data['warehouses']			=	$this->Operation_model->getTruckingLocations();
		$data['vessels']			=	$this->Abas->getVessels();
		$data['service_providers']	=	$this->Operation_model->getServiceProviders();
		if($id!='') {
			$data['transaction']	=	$this->Operation_model->getTransaction($id);
		}
		$this->load->view('operation/wsi_form',$data);
	}
	public function wb_view() {$data=array();

		$data['transaction_types']	=	$this->Operation_model->getTransactionTypes();
		$data['regions']	=	$this->Abas->getRegions();
		$data['warehouses']	=	$this->Operation_model->getTruckingLocations();
		$data['transactions']	=	$this->Operation_model->getTransactions('WB');

		//var_dump($data['warehouses']);exit;
		$this->load->view('operation/wb_view',$data);

	}
	public function wb_form($id='') {$data=array();
		$data['transaction_types']	=	$this->Operation_model->getTransactionTypes();
		$data['regions']			=	$this->Abas->getRegions();
		$data['warehouses']			=	$this->Operation_model->getTruckingLocations();
		$data['vessels']			=	$this->Abas->getVessels();
		$data['service_providers']	=	$this->Operation_model->getServiceProviders();
		if($id!='') {
			$data['transaction']	=	$this->Operation_model->getTransaction($id);
		}
		$this->load->view('operation/wb_form',$data);
	}
	public function addTransaction() {$data=array();
		$msg	='';
		if(isset($_POST)) {
			$transaction_type	=	$_POST['transaction_type']; //trucking/handling or voyage
			$reference_no	=	$this->Mmm->sanitize($_POST['reference_no']);
			$wsr_no	=			$this->Mmm->sanitize($_POST['wsr_no']);
			$waybill_no	=		(isset($_POST['waybill_no'])) ? $this->Mmm->sanitize($_POST['waybill_no']) : '';
			$wsi_no	=			$this->Mmm->sanitize($_POST['wsi_no']);
			$issue_date	=		$this->Mmm->sanitize($_POST['issue_date']);
			$from_location	=$this->Mmm->sanitize($_POST['from_location']);
			$to_location	=		$this->Mmm->sanitize($_POST['to_location']);
			$region	=	(isset($_POST['region'])) ? $this->Mmm->sanitize($_POST['region']) : 0;
			$age	=	(isset($_POST['age'])) ? $this->Mmm->sanitize($_POST['age']) : '';
			$stock_condition	=	(isset($_POST['stock_condition'])) ? $this->Mmm->sanitize($_POST['stock_condition']) : '';
			$service_provider	=	($_POST['service_provider'] !=	'') ? $this->Mmm->sanitize($_POST['service_provider']) : 0;
			$truck_plate_no	=		$this->Mmm->sanitize($_POST['truck_plate_no']);
			$bags	=				$this->Mmm->sanitize($_POST['bags']);
			$gross_weight	=		(isset($_POST['gross_weight'])) ? $this->Mmm->sanitize($_POST['gross_weight']) : 0;
			$net_weight	=			$this->Mmm->sanitize($_POST['net_weight']);
			$variety	=				$this->Mmm->sanitize($_POST['variety']);
			$vessel	=				$this->Mmm->sanitize($_POST['vessel']);
			$voyage_no	=			(isset($_POST['voyage_no'])) ? $this->Mmm->sanitize($_POST['voyage_no']) : NULL;
			$date_added	=	date('Y-m-d');
			$id	=	$_POST['id']; //if existing then info for update
			$type	=	$_POST['type']; // goes to document_type column
			if($type	==	'WB') {
				$return_to_path	=	'wb_view' ;
			}
			else if($type	==	'WSI') {
				$return_to_path	=	'wsi_view' ;
			}
			else if($type	==	'WSR') {
				$return_to_path	=	'wsr_view' ;
			}
			else{
				$return_to_path	=	'' ;
			}
			if($id!='') {//edit
				$sql	=	"
						UPDATE ops_transactions
						SET wsr_no	=	'$wsr_no',
							waybill_no	=	'$waybill_no',
							wsi_no	=	'$wsi_no',
							from_location	=	'$from_location',
							to_location	=	'$to_location',
							issue_date	=	'$issue_date',
							service_provider	=	$service_provider,
							truck_plate_no	=	'$truck_plate_no',
							transaction_type	=	$transaction_type,
							bags	=	$bags ,
							gross_weight	=	'$gross_weight',
							net_weight	=	'$net_weight',
							variety	=	'$variety',
							age	=	'$age',
							stock_condition	=	'$stock_condition',
							reference_no	=	'$reference_no',
							region	=	$region,
							type	=	'$type',
							vessel_id	=	$vessel,
							voyage_no	=	'$voyage_no'
						WHERE id	=	$id	";
				$r	=	$this->Mmm->query($sql, 'Transaction updated');
				$_SESSION['msg']	=	"Transaction updated.";
				$this->Abas->redirect(HTTP_PATH."operation/".$return_to_path);
			}
			else{//new
				//validate number if existing
				if($type	==	'WB') {
					$s	=	"SELECT * FROM ops_transactions WHERE waybill_no	=	'$waybill_no'";
					$m	=	'Waybill';
				}else if($type	==	'WSI') {
					$s	=	"SELECT * FROM ops_transactions WHERE wsi_no	=	'$wsi_no'";
					$m	=	'WSI';
				}else if($type	==	'WSR') {
					$s	=	"SELECT * FROM ops_transactions WHERE wsr_no	=	'$wsr_no'";
					$m	=	'WSR';
				}

				$r	=	$this->db->query($s);

				if($r->result_array()) {
					$_SESSION['msg']	=	$m." number already exist!";
					$this->Abas->redirect(HTTP_PATH."operation/".$return_to_path);
				}
				else{
					$sql	=	"INSERT INTO ops_transactions(
								id,
								wsr_no,
								waybill_no,
								wsi_no,
								from_location,
								to_location,
								issue_date,
								truck_plate_no,
								transaction_type,
								bags,
								gross_weight,
								net_weight,
								variety,
								age,
								stock_condition,
								stat,
								service_provider,
								date_added,
								reference_no,
								region,
								type,
								vessel_id,
								voyage_no )
							VALUES(0,
								'$wsr_no',
								'$waybill_no',
								'$wsi_no',
								'$from_location',
								'$to_location',
								'$issue_date',
								'$truck_plate_no',
								$transaction_type,
								$bags,
								$gross_weight,
								$net_weight,
								'$variety',
								'$age',
								'$stock_condition',
								1,
								$service_provider,
								'$date_added',
								'$reference_no',
								$region,
								'$type',
								$vessel,
								'$voyage_no')";
					$r	=	$this->Mmm->query($sql, 'New Transaction added');
					if($r) {
						$_SESSION['msg']	=	"New Transaction has been added.";
						$this->Abas->redirect(HTTP_PATH."operation/".$return_to_path);
					}
				}
			}
		}
		$data['msg']	=	$msg;
		$this->index();
	}
	public function wsr_data() {
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT * FROM ops_transactions WHERE wsr_no LIKE '%".$search."%' ORDER BY wsr_no LIMIT 0, 10";
		$items	=	$this->db->query($sql);
		if($items) {
			if($items->row()) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					$ret[$ctr]['label']	=	$i['wsr_no'];
					$ret[$ctr]['value']	=	$i['id'];
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
	}
	public function wsi_data() {
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT * FROM ops_transactions WHERE wsi_no LIKE '%".$search."%' ORDER BY wsi_no LIMIT 0, 10";
		$items	=	$this->db->query($sql);
		if($items) {
			if($items->row()) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					// $ret['id']	=	$i['id'];
					$ret[$ctr]['label']	=	$i['wsi_no'];
					$ret[$ctr]['value']	=	$i['id'];
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
	}
	public function waybill_data() {
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT * FROM ops_transactions WHERE waybill_no LIKE '%".$search."%' ORDER BY wsr_no LIMIT 0, 10";
		$items	=	$this->db->query($sql);
		if($items) {
			if($items->row()) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					// $ret['id']	=	$i['id'];
					$ret[$ctr]['label']	=	$i['waybill_no'];
					$ret[$ctr]['value']	=	$i['id'];
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
	}
	public function getSelectedWsr() {
		if(isset($_POST['id'])) {
			$action	=	$_POST['action'];

			$selected	=	$_POST['id'];
			//var_dump(gettype($selected_items));

			if(is_array($selected)) {
				$sGroup	=	explode(",",$selected[0]);
			}
			else{
				$sGroup	=	explode(",",$selected);
			}
			$ctr	=	count($sGroup) - 1;
			if($action	==	'wsr') {
				$res	=	"
					<table data-toggle='table' id='wsr-table' class='table table-striped table-hover table-responsive' data-cache='false'	style='font-size:12px'>
						<thead>
							<tr style='background:#000000; color:#FFFFFF'>
								<th width='3%'>*</th>
								<th width='6%' >WSR #</th>
								<th width='13%' >Issue Date</th>
								<th width='12%' >Reference #</th>
								<th	width='15%'>Issued From</th>
								<th	width='17%'>Delivered To</th>
								<th width='8%' >No. Bags</th>
								<th width='15%' align='center' ><div style='margin-left:30px;'>Gross Wt</div></th>
								<th width='15%'>Net Wt</th>
							</tr>
						</thead>
						<tbody>";
				$bagsTotal	=	0;
				$gwTotal	=	0;
				$nwTotal	=	0;
				for($i=0;$i < $ctr; $i++) {
							//check if wsr status if alredy billed
							$sqChk	=	"SELECT * FROM ops_transactions WHERE id	=	".$sGroup[$i]." AND ap_billed	=	1";
							$rDb	=	$this->db->query($sqChk);
							$rChk	=	$rDb->result_array();
							$result	=	$this->Operation_model->getTransaction($sGroup[$i]);
							//var_dump($result->from_location); exit;
											$issued_from	=	$this->Abas->getWarehouse($result->from_location);
											$delivered_to	=	$this->Abas->getWarehouse($result->to_location);
											$region	=	$this->Abas->getRegion($result->region);
							$res.=	"<tr>
										<td align='center' ><a href='#' id='".$result->id.",' onclick='delItem(this.id); '><i class='fa fa-close' ></i></a></a></td>
										<td	align='left'>".$result->wsr_no."</td>
										<td align='left'>".date('F j, Y',strtotime($result->issue_date))."</td>
										<td	align='left'>".$result->reference_no."</td>
										<td	align='left'>".$issued_from[0]->name."</td>
										<td align='left'>".$delivered_to[0]->name."</td>
										<td align='center'>".$result->bags."</td>
										<td align='right'>".number_format($result->gross_weight,0)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td align='right'>".number_format($result->net_weight,0)."</td>
									</tr>
								";
								$bagsTotal	=	$bagsTotal + $result->bags;
								$gwTotal	=	$gwTotal + $result->gross_weight;
								$nwTotal	=	$nwTotal + $result->net_weight;
							}
							$res.="<tr style='font-weight:600'>
										<td colspan='6' align='right'>Totals:&nbsp;&nbsp;</td>
										<td align='center'>".number_format($bagsTotal,0)."</td>
										<td align='right'>".number_format($gwTotal,0)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td align='right'>".number_format($nwTotal,0)."</td>
									</tr>
							</table>";
			}//end WSR presetation;
			if($action	==	'wsi') {
				$res	=	"<table data-toggle='table' id='wsr-table' class='table table-striped table-hover table-responsive' data-cache='false'	style='font-size:12px'>
								<thead>
									<tr style='background:#000000; color:#FFFFFF'>
										<th width='3%'>*</th>
										<th width='6%' >WSI #</th>
										<th width='13%' >Issue Date</th>
										<th width='12%' >Reference #</th>
										<th	width='15%'>Issued From</th>
										<th	width='17%'>Issued To</th>
										<th width='8%' >No. Bags</th>
										<th width='15%' align='center' ><div style='margin-left:30px;'>Weight</div></th>
									</tr>
								</thead>
								<tbody>";
				$bagsTotal	=	0;
				$gwTotal	=	0;
				$nwTotal	=	0;
				for($i=0;$i < $ctr; $i++) {
					//check if wsi status if alredy billed
					$sqChk	=	"SELECT * FROM ops_transactions WHERE id	=	".$sGroup[$i]." AND ap_billed	=	1";
					$rDb	=	$this->db->query($sqChk);
					$rChk	=	$rDb->result_array();
					$result	=	$this->Operation_model->getTransaction($sGroup[$i]);
					//var_dump($result->from_location); exit;
									$issued_from	=	$this->Abas->getWarehouse($result->from_location);
									$delivered_to	=	$this->Abas->getWarehouse($result->to_location);
									$region	=	$this->Abas->getRegion($result->region);
					$res.=	"<tr>
								<td align='center' ><a href='#' id='".$result->id.",' onclick='delItem(this.id); '><i class='fa fa-close' ></i></a></a></td>
								<td	align='left'>".$result->wsi_no."</td>
								<td align='left'>".date('F j, Y',strtotime($result->issue_date))."</td>
								<td	align='left'>".$result->reference_no."</td>
								<td	align='left'>".$issued_from[0]->name."</td>
								<td align='left'>".$delivered_to[0]->name."</td>
								<td align='center'>".$result->bags."</td>
								<td align='right'>".number_format($result->net_weight,0)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
						";
					$bagsTotal	=	$bagsTotal + $result->bags;
					$nwTotal	=	$nwTotal + $result->net_weight;
				}
				$res.="<tr style='font-weight:600'>
							<td colspan='6' align='right'>Totals:&nbsp;&nbsp;</td>
							<td align='center'>".number_format($bagsTotal,0)."</td>
							<td align='right'>".number_format($nwTotal,0)."</td>
						</tr>
				</table>";
			}//end WSI presetation;
			if($action	==	'wb') {
				$res	=	"<table data-toggle='table' id='wsr-table' class='table table-striped table-hover table-responsive' data-cache='false'	style='font-size:12px'>
								<thead>
									<tr style='background:#000000; color:#FFFFFF'>
										<th width='3%'>*</th>
										<th width='6%' >Waybill #</th>
										<th width='13%' >Issue Date</th>
										<th width='12%' >Reference #</th>
										<th	width='15%'>Loading At</th>
										<th	width='17%'>Unloading At</th>
										<th width='8%' >No. Bags</th>
										<th width='15%' align='center' ><div style='margin-left:30px;'>Gross Weight</div></th>
										<th width='8%' >Net Weight</th>
									</tr>
								</thead>
								<tbody>";
				$bagsTotal	=	0;
				$gwTotal	=	0;
				$nwTotal	=	0;
				for($i=0;$i < $ctr; $i++) {
					//separate grouped item
					$item	=	explode('|',$sGroup[$i]);
					$tid	=	$item[0];
					$trate	=	$item[1];
					//check if wsi status if alredy billed
					$sqChk			=	"SELECT * FROM ops_transactions WHERE id	=	".$tid." AND ap_billed	=	1";
					$rDb			=	$this->db->query($sqChk);
					$rChk			=	$rDb->result_array();
					$result			=	$this->Operation_model->getTransaction($tid );
					$issued_from	=	$this->Abas->getWarehouse($result->from_location);
					$delivered_to	=	$this->Abas->getWarehouse($result->to_location);
					$region			=	$this->Abas->getRegion($result->region);
					$res.=	"<tr>
								<td align='center' ><a href='#' id='".$result->id.",' onclick='delItem(this.id); '><i class='fa fa-close' ></i></a></a></td>
								<td	align='left'>".$result->waybill_no."</td>
								<td align='left'>".date('F j, Y',strtotime($result->issue_date))."</td>
								<td	align='left'>".$result->reference_no."</td>
								<td	align='left'>".$issued_from[0]->name."</td>
								<td align='left'>".$delivered_to[0]->name."</td>
								<td align='center'>".$result->bags."</td>
								<td align='right'>".number_format($result->gross_weight,0)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td align='right'>".number_format($result->net_weight,0)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
						";
					$bagsTotal	=	$bagsTotal + $result->bags;
					$gwTotal	=	$gwTotal + $result->gross_weight;
					$nwTotal	=	$nwTotal + $result->net_weight;
				}
				$res	.=	"<tr style='font-weight:600'>
								<td colspan='6' align='right'>Totals:&nbsp;&nbsp;</td>
								<td align='center'>".number_format($bagsTotal,0)."</td>
								<td align='right'>".number_format($gwTotal,0)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td align='right'>".number_format($nwTotal,0)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>";
			}//end WAYBILL presetation;
		}
		else{
			$res	=	'<div>No items selected. Please try again.</div>';
		}
		echo $res;
	}
	public function request_payment_form() {$data=array();
		$data['service_providers']	=	$this->Operation_model->getServiceProviders();
		$data['wsrs']				=	$this->Operation_model->getWsrs();
		$this->load->view('operation/request_payment_form',$data);
	}
	public function statement_account_form() {$data=array();
		$data['clients']	=	$this->Abas->getClients();
		$data['wsrs']		=	$this->Operation_model->getWsrs();
		$this->load->view('operation/statement_account_form',$data);
	}
	public function addRequestPayment() {$data=array();
		if($_POST) {
			$date_created	=	date('Y-m-d');
			$billing_type	=	$_POST['billing_type'];
			$reference_no	=	$_POST['reference_no'];
			$service_provider	=	$_POST['service_provider'];
			//$rate	=	$_POST['rate'];
			$moves	=	$_POST['moves'];
			$sels	=	$_POST['sels'];
			$wsrs	=	explode(',',$sels);
			$ctr	=	count($wsrs) - 1;
			$total_amount	=	0;
			//begin transaction
			$this->db->trans_begin();
			//summary
			$sql	=	"	INSERT INTO ops_billing_ap(id, date_created, service_provider_id, reference_no, amount, type, stat, moves) VALUES(0,'$date_created',$service_provider,'$reference_no','$total_amount', '$billing_type', 1, $moves)";
			//$r	=	$this->Mmm->query($sql, 'New AP Biling added');
			$dbres	=	$this->db->query($sql);
			$bid	=	$this->db->insert_id();
			if(is_numeric($bid)) {
				//details
				for($i=0; $i<$ctr; $i++) {
					$item		=	explode('|',$wsrs[$i]);
					//var_dump($item[0]);
					$tid		=	$item[0];
					$rate		=	$item[1];
					$wsrInfo	=	$this->Operation_model->getTransaction($tid);
					if($billing_type=='Trucking') {
					//FOR TRUCKING (base on net weight)**********
					//need to get the total amount	(rate * (weight/1000))
						$trucking_amount	=	$rate * ($wsrInfo->net_weight / 1000);
						$total_amount		=	$total_amount + $trucking_amount;
					}
					if($billing_type	==	'Handling') {
					//FOR HANDLING (base on number of bags)*********
						$handling_amount	=	$rate * ($wsrInfo->bags * $moves);
						$total_amount	=	$total_amount + $handling_amount;
					}
					$sql	=	"INSERT INTO ops_billing_ap_details(id, billing_id, wsr_id, stat, rate) VALUES(0, $bid, $wsrInfo->id, 1, '$rate')";
					$dbres	=	$this->Mmm->query($sql, 'New AP Biling added');
					if($dbres) {
						$sql	=	"UPDATE ops_transactions SET ap_billed	=	1 WHERE id	=	".$tid;
						$dbr	=	$this->db->query($sql);
					}
				}
			}
			$sql	=	"UPDATE ops_billing_ap SET amount	=	'$total_amount' WHERE id	=	".$bid;
			$dbr	=	$this->db->query($sql);
			if ($this->db->trans_status()===FALSE) {
				$this->db->trans_rollback();
			}
			else{
				$this->db->trans_commit();
				$this->Abas->sysMsg("warnmsg", "Billing Created");
				echo "	<script language=\"javascript\">
							window.open('".HTTP_PATH."operation/print_rfp/".$bid."');
							window.location.href	=	'".HTTP_PATH."operation/request_payment_form';
						</script>";
				exit;
			}
		}
		else{
			$this->Abas->sysMsg("warnmsg", "Problem encountered please try again.");
		}
		$this->Abas->redirect(HTTP_PATH."operation/request_payment_form");
	}
	public function addSOA() {$data=array();
		if($_POST) {
			$date_created	=	date('Y-m-d');
			$billing_type	=	$_POST['billing_type'];
			$reference_no	=	$_POST['reference_no'];
			$client			=	$_POST['client'];
			$rate			=	$_POST['rate'];
			$moves			=	$_POST['moves'];
			$sels			=	$_POST['sels'];
			$wsrs			=	explode(',',$sels);
			$ctr			=	count($wsrs) - 1;
			$total_amount	=	0;
			//begin transaction
			$this->db->trans_begin();
			//summary
			$sql	=	"	INSERT INTO ops_billing_ar(id, date_created, client, rate, reference_no, amount, type, moves,stat) VALUES(0,'$date_created',$client,'$rate','$reference_no','$total_amount', '$billing_type', $moves, 1)";
			$dbres	=	$this->db->query($sql);
			$bid	=	$this->db->insert_id();
			if($bid !=	'') {
				//details
				for($i=0; $i<$ctr; $i++) {
					$wsrInfo	=	$this->Operation_model->getTransaction($wsrs[$i]);
					if($billing_type	==	'Trucking') {
					//FOR TRUCKING (base on net weight)/
					//need to get the total amount	(rate * (weight/1000))
						$trucking_amount	=	$rate * ($wsrInfo->net_weight / 1000);
						$total_amount		=	$total_amount + $trucking_amount;
					}
					if($billing_type	==	'Handling') {
					// FOR HANDLING (base on number of bags)
						$handling_amount	=	$rate * ($wsrInfo->bags * $moves);
						$total_amount		=	$total_amount + $handling_amount;
					}
					$sql	=	"INSERT INTO ops_billing_ar_details(id, billing_id, wsr_id, stat) VALUES(0, $bid, $wsrInfo->id, 1)";
					$dbres	=	$this->Mmm->query($sql, 'New AR Biling added');
					if($dbres) {
						$sql	=	"UPDATE ops_transactions SET ap_billed=1 WHERE id=".$wsrInfo->id;
						$dbr	=	$this->db->query($sql);
					}
				}
			}
			$sql	=	"UPDATE ops_billing_ar SET amount='$total_amount' WHERE id=".$bid;
			$dbr	=	$this->db->query($sql);
			if ($this->db->trans_status()	===	FALSE) {
				$this->db->trans_rollback();
			}
			else{
				$this->db->trans_commit();
				$this->Abas->sysMsg("warnmsg", "Billing Created");
				echo "	<script language=\"javascript\">
							window.open('".HTTP_PATH."operation/print_soa/".$bid."');
							window.location.href	=	'".HTTP_PATH."operation/statement_account_form';
						</script>";
				$this->Abas->sysMSg("msg", "New SOA has been created.");
				exit;
			}
		}
		else{
			$this->Abas->sysMsg("warnmsg", "Problem encountered please try again.");
		}
		$this->Abas->redirect(HTTP_PATH."operation/statement_account_form");
	}
	public function print_soa($id='') {
		if($id !=	'') {
			$data['summary']	=	$this->Operation_model->getArBilling($id);
			$data['details']	=	$this->Operation_model->getArBillingDetails($id);
			$this->load->view('operation/print_soa',$data);
		}
		else{
			$this->Abas->sysMSg("msg", "There was an error encountered please try again.");
		}
	}
	public function print_rfp($id='') {
		if($id !=	'') {
			$data['summary']	=	$this->Operation_model->getApBilling($id);
			$data['details']	=	$this->Operation_model->getApBillingDetails($id);
			$this->load->view('operation/print_rfp',$data);
		}
		else{
			$this->Abas->sysMSg("msg", "There was an error encountered please try again.");
		}
	}
	public function contracts($action="", $id="") {$data=array();
		$data['companies']	=	$this->Abas->getCompanies();
		$data['clients']	=	$this->Abas->getClients();
		$data['vessels']	=	$this->Abas->getVessels(false);
		$data['viewfile']	=	"operation/service_view.php";
		$mainview	=	"responsive_container.php";
		if($id!="" && is_numeric($id)) {
			$contract	=	$this->Operation_model->getContract($id);
			if($contract!=false) {
				$data['contract']	=	$contract;
				if($action=="view") {
					// $this->Mmm->debug($contract);
					$data['details']	=	$this->Operation_model->getContractDetails($id);
					$mainview	=	"operation/contract_view.php";
				}
				elseif($action=="edit") {
					$details			=	$this->Operation_model->getContractDetails($id);
					$mainview			=	"operation/contract_form.php";
				}
				elseif($action=="update") {
					if(empty($_POST)) {
						$this->Abas->sysMsg("warnmsg", "Contract not found!");
						$this->Abas->redirect(HTTP_PATH."operation");
					}
					else {
						// $update['created']		=	date("Y-m-d H:i:s");
						// $update['created_by']	=	$_SESSION['abas_login']['userid'];
						// $update['status']	=	"Active";
						$update['stat']		=	1;
						$update['company_id']		=	$this->Mmm->sanitize($_POST['company']);
						$update['date_effective']	=	date("Y-m-d", strtotime($_POST['date_effective']))." 00:00:00";
						$update['client_id']		=	$this->Mmm->sanitize($_POST['client']);
						$update['type']				=	$this->Mmm->sanitize($_POST['type']); //service_type
						$update['rate']				=	$this->Mmm->sanitize($_POST['rate']);
						$update['quantity']			=	$this->Mmm->sanitize($_POST['quantity']);
						$update['unit']				=	$this->Mmm->sanitize($_POST['unit']);
						$update['amount']			=	$this->Mmm->sanitize($_POST['amount']); //contract amount
						$update['reference_no']		=	$this->Mmm->sanitize($_POST['reference_no']);
						$update['parent_id']		=	$this->Mmm->sanitize($_POST['parent_id']);
						$update['details']			=	$this->Mmm->sanitize($_POST['details']);
						$check	=	$this->db->query("SELECT * FROM service_contracts WHERE reference_no='".$update['reference_no']."' AND id<>".$id);
						if($check) {
							if(!$check->row()) {
								// $submit	=	$this->Mmm->dbUpdate("service_contracts", $update, $id, "debug");
								$submit	=	$this->Mmm->dbUpdate("service_contracts", $update, $id, "Update contract");
								if($submit==true) {
									$this->Abas->sysMsg("sucmsg", "Contract edited!");
								}
								else {
									$this->Abas->sysMsg("errmsg", "Contract not edited!");
								}
							}
							else {
								$this->Abas->sysMsg("errmsg", "Contract not edited! That reference number is already assigned to another contract.");
							}
						}
						else {
							$this->Abas->sysMsg("errmsg", "Contract not edited! That reference number is already assigned to another contract.");
						}
						$this->Abas->redirect(HTTP_PATH."operation");
					}
				}
			}
			else {
				$this->Abas->sysMsg("warnmsg", "Contract not found!");
				$this->Abas->redirect(HTTP_PATH."operation");
			}
		}
		else {
			if($action=="add") {
				$mainview	=	"operation/new_contract_form.php";
			}
			elseif($action=="insert") {
				//var_dump($_POST);exit;
				$insert['created_on']	=	date("Y-m-d H:i:s");
				$insert['created_by']	=	$_SESSION['abas_login']['userid'];
				// $insert['status']	=	"Active";
				$insert['stat']		=	1;
				$insert['company_id']		=	$this->Mmm->sanitize($_POST['company']);
				$insert['date_effective']	=	date("Y-m-d", strtotime($_POST['date_effective']));
				$insert['client_id']		=	$this->Mmm->sanitize($_POST['client']);
				$insert['type']				=	$this->Mmm->sanitize($_POST['type']);
				$insert['rate']				=	$this->Mmm->sanitize($_POST['rate']);
				$insert['quantity']			=	$this->Mmm->sanitize($_POST['quantity']);
				$insert['unit']				=	$this->Mmm->sanitize($_POST['unit']);
				$insert['amount']			=	$this->Mmm->sanitize($_POST['amount']);
				$insert['reference_no']		=	$this->Mmm->sanitize($_POST['reference_no']);
				//$insert['sub_reference_no']		=	$this->Mmm->sanitize($_POST['sub_reference_no']);
				$insert['details']			=	$this->Mmm->sanitize($_POST['details']);
				$check	=	$this->db->query("SELECT * FROM service_contracts WHERE reference_no='".$insert['reference_no']."'");
				if(count($check)>=1) {
					// $submit	=	$this->Mmm->dbInsert("service_contracts", $insert, "debug");
					//var_dump($insert);
					$submit	=	$this->Mmm->dbInsert("service_contracts", $insert, "New contract");
					//var_dump($submit); exit;
					if($submit==true) {
						$this->Abas->sysMsg("sucmsg", "Contract added!");
					}
					else {
						$this->Abas->sysMsg("errmsg", "Contract not added!");
					}
				}
				else {
					$this->Abas->sysMsg("errmsg", "Contract not added! That reference number is already assigned to another contract.");
				}
				$this->Abas->redirect(HTTP_PATH."operation");
			}
		}
		$this->load->view($mainview, $data);
	}
	public function contract_details($contract_id, $action="", $id="") {
		$contract	=	$this->Operation_model->getContract($contract_id);
		if($contract!=true) {
			$this->Abas->sysMsg("errmsg", "Contract not found!");
			$this->Abas->redirect(HTTP_PATH."operation");
		}
	}
	public function view_all_contracts() {
		$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
		$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
		$order	=	isset($_GET['order'])?$_GET['order']:"";
		$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
		$search	=	isset($_GET['search'])?$_GET['search']:"";
		$data	=	$this->Operation_model->getAllContracts($search,$limit,$offset,$order,$sort);
		if($data!=false) {
			header('Content-Type: application/json');
			echo json_encode($data);
			exit();
		}
	}
	public function fuel_report_form() {$data=array();
		$data['vessels']	=	$this->Abas->getVessels();
		$data['ports']	=	$this->Abas->getPorts();
		$this->load->view('operation/fuel_report_form',$data);
	}
	public function addFuelReport() {$data=array();
		if(isset($_POST)) {
			$datenow		=	date('Y-m-d h:m:s');
			$vessel			=	$_POST['vessel'];
			$from_port		=	$_POST['from_port'];
			$to_port		=	$_POST['to_port'];
			$voyage_no		=	$_POST['voyage_no'];
			$remark			=	$_POST['remark'];
			$fuel_reading	=	$_POST['fuel_reading'];
			$ftype			=	$_POST['ftype'];
			$q				=	"	SELECT * FROM ops_port_distance WHERE from_port	=	'$from_port' AND to_port	=	'$to_port'";
			$db				=	$this->db->query($q);
			$res			=	$db->result_array();
			if($res) {
				$port_id	=	$res[0]['id'];
			}
			else{
				$port_id	=	0;
			}
			$sql			=	"INSERT INTO ops_report_vessel_fuel(id, vessel_id, report_date, fuel_reading, remark, stat, port_id, activity, voyage_no) VALUES(0, $vessel,'$datenow', $fuel_reading, '$remark', 0, $port_id, '$ftype' , $voyage_no )
			";
			$db				=	$this->Mmm->query($sql, 'Fuel Report');
		}
		$this->Abas->redirect(HTTP_PATH."operation/monitoring");
	}
	public function clean_general_charters() { // outputs queries used to clean up general_charters table
		if(ENVIRONMENT!="development") { die("Function will not run on prod!"); }
		$get	=	$this->db->query("SELECT * FROM general_charters");
		if($get) {
			if($get=$get->result_array()) {
				foreach($get as $g) {
					// removes 'Attention: $charters_name' from column charterers_place_of_business
					$cpob	=	$g['charterers_place_of_business'];
					$until	=	strpos($cpob, "Attention:");
					$remove	=	substr($cpob, $until);
					$remove_len	=	strlen($remove);
					$cpob	=	substr($cpob, 0, ((-1) * $remove_len)-2);
					if($cpob!="") {
						$sql	=	'UPDATE general_charters SET charterers_place_of_business="'.$cpob.'" WHERE id='.$g['id'].';';
						echo $sql."</br>";
					}
				}
			}
		}
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='general_charters' AND TABLE_SCHEMA='".DBNAME."'");
		$tablefields			=	$tablefields->result_array();
		foreach($tablefields as $t) {
			// $this->Mmm->debug($t);
			echo "UPDATE general_charters SET `".$t['COLUMN_NAME']."`	=	REPLACE( `".$t['COLUMN_NAME']."` , '\\t' , '&nbsp;' );<br/>";
			echo "UPDATE general_charters SET `".$t['COLUMN_NAME']."`	=	REPLACE( `".$t['COLUMN_NAME']."` , '\\n' , '&nbsp;' );<br/>";
			echo "UPDATE general_charters SET `".$t['COLUMN_NAME']."`	=	REPLACE( `".$t['COLUMN_NAME']."` , '&nbsp;&nbsp;' , '&nbsp;' );<br/>";
			echo "UPDATE `general_charters` SET `".$t['COLUMN_NAME']."`	=	TRIM(`".$t['COLUMN_NAME']."`);<br/>";
		}
	}
	public function service_contract($action=NULL,$id=NULL) {
		$data	=	array();
		switch($action) {
			case "load":
				$table	=	"service_contracts";
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					//$search	=	"";
					$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);

					foreach($data['rows'] as $ctr=>$row) {
						if(isset($row['company_id'])) {
							$data['rows'][$ctr]['company']			=	$this->Abas->getCompany($row['company_id'])->name;
						}
						if(isset($row['client_id'])) {
							$client	=	$this->Abas->getClient($row['client_id']);
							$data['rows'][$ctr]['client']			=	$client['company'];
						}
						if(isset($row['amount'])) {
							$data['rows'][$ctr]['amount']			=	number_format($row['amount'],2,".",",");
						}
						if($row['parent_contract_id']!=0) {
							$data['rows'][$ctr]['contract_type']	=	"Sub Contract";
							$mother_contract	=	$this->Abas->getContract($row['parent_contract_id']);
							$data['rows'][$ctr]['mother_contract'] = $mother_contract['reference_no'];
						}
						if($row['parent_contract_id']==0) {
							$data['rows'][$ctr]['contract_type']	=	"Mother Contract";
						}
						if(isset($row['contract_date'])){
							$data['rows'][$ctr]['contract_date']	=	date("Y-m-d", strtotime($row['contract_date']));
						}
						if(isset($row['created_on'])) {
							$data['rows'][$ctr]['created_on']		=	date("j F Y h:i:s A", strtotime($row['created_on']));
						}
						if(isset($row['created_by'])) {
							$user	=	$this->Abas->getUser($row['created_by']);
							$data['rows'][$ctr]['created_by']		=	$user['full_name'];
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}
			break;
			case "listview":

				//updates all the contracts overall percentage every page visit
				$this->Operation_model->updateContractsOverallPercentage();

				$data['viewfile']	=	"operation/contract/listview.php";
				$this->load->view('gentlella_container.php',$data);

			break;
			case "view":

				$data['SC']			=	$this->Abas->getContract($id);
				$data['SC_Rates']		=	$this->Operation_model->getContractRates($id);
				$data['sub_contracts']	=	$this->Operation_model->getAllSubContracts($id);
				$data['sub_contracts_percentage'] =	$this->Operation_model->getSubContractsPercentage($id);

				$data['service_orders']	=	$this->Operation_model->getAllServiceOrdersByContract($id);
				$data['service_orders_percentage'] =	$this->Operation_model->getServiceOrdersPercentage($id);

				$data['out_turn_summary']	=	$this->Operation_model->getAllOutTurnSummaryByContract($id);
				$data['out_turn_summary_percentage']	=	$this->Operation_model->getOutTurnSummaryPercentage($id);

				$data['billing']		=	$this->Operation_model->getAllBillingByContract($id);
				$data['billing_percentage']	=	$this->Operation_model->getBillingPercentage($id);

				$data['collection']		=	$this->Operation_model->getAllCollectionByContract($id);
				$data['collection_percentage']	=	$this->Operation_model->getCollectionPercentage($id);

				$contract =	$this->Operation_model->getContract($id);
				$data['request_for_payments']=	$this->Operation_model->getAllRequestPaymentsByContract($id);
				$data['request_payments_percentage']	=	$this->Operation_model->getRequestPaymentsPercentage($id);


				$arr_percentages =	array($data['service_orders_percentage'],$data['out_turn_summary_percentage'],$data['billing_percentage'],$data['collection_percentage']);

				if($data['sub_contracts_percentage']!=0){
					 array_push($arr_percentages,$data['sub_contracts_percentage']);
				}

				if($data['request_payments_percentage']!=0){
					 array_push($arr_percentages,$data['request_payments_percentage']);
				}

				if($data['SC']['status']=='Draft') {
					$data['overall_percentage'] =	'Draft';
				}elseif($data['SC']['status']=='For Approval') {
					$data['overall_percentage'] =	'For Approval';
				}else{
					$data['overall_percentage'] =	$this->Operation_model->calcPercentage($arr_percentages)."%";
				}
				$data['viewfile']		=	"operation/contract/view.php";
				$this->load->view('gentlella_container.php',$data);

			break;
			case "add":
				$data['companies']		=	$this->Abas->getCompanies();
				$data['clients']		=	$this->Abas->getClients();
				$data['services']		=	$this->Abas->getServices();
				$data['contracts']		=	$this->Operation_model->getContracts();
				$this->load->view('operation/contract/form.php',$data);
			break;
			case "insert":
				if(isset($_POST['reference_no'])) {
					$insert	=	array();
					$insert['control_number']				=	$this->Abas->getNextSerialNumber('service_contracts',$this->Mmm->sanitize($_POST['company']));
					$insert['company_id']					=	$this->Mmm->sanitize($_POST['company']);
					$insert['client_id']					=	$this->Mmm->sanitize($_POST['client']);
					$insert['reference_no']					=	$this->Mmm->sanitize($_POST['reference_no']);
					$insert['type']							=	$this->Mmm->sanitize($_POST['service_type']);
					$insert['contract_date']				=	$this->Mmm->sanitize($_POST['contract_date']);
					$insert['parent_contract_id']			=	$this->Mmm->sanitize($_POST['mother_contract']);
					$insert['quantity']						=	$this->Mmm->sanitize($_POST['quantity']);
					$insert['unit']							=	$this->Mmm->sanitize($_POST['unit']);
					$insert['rate']							=	$this->Mmm->sanitize($_POST['fixed_rate']);
					$insert['amount']						=	$this->Mmm->sanitize($_POST['grand_total']);
					$insert['vat_type']						=	$this->Mmm->sanitize($_POST['vat_type']);
					$insert['details']						=	$this->Mmm->sanitize($_POST['contract_details']);
					$insert['created_on']					=	date("Y-m-d H:i:s");
					$insert['created_by']					=	$_SESSION['abas_login']['userid'];
					$insert['stat']							=	1;
					$insert['status']						=	"Draft";
					$control_number							=	$insert['control_number'];
					$company_name							=	$this->Abas->getCompany($insert['company_id'])->name;
					$reference_no							=	$insert['reference_no'];
					$checkInsert	=	$this->Mmm->dbInsert('service_contracts',$insert,'Added new Contract with Control No.' . $control_number . ' under ' . $company_name . " with Reference No." .$reference_no);
					if($checkInsert) {

						$lastInserted	=	$this->Abas->getLastIDByTable('service_contracts');

						foreach($_POST['warehouse'] as $ctr=>$val) {
							$multiInsert[$ctr]['service_contract_id']		=	$lastInserted;
							$multiInsert[$ctr]['warehouse']		=	$this->Mmm->sanitize($_POST['warehouse'][$ctr]);
							$multiInsert[$ctr]['rate']		=	$this->Mmm->sanitize($_POST['rate'][$ctr]);
							$multiInsert[$ctr]['quantity']		=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
							$multiInsert[$ctr]['unit']		=	$this->Mmm->sanitize($_POST['unit'][$ctr]);
							$multiInsert[$ctr]['additional_charges']		=	$this->Mmm->sanitize($_POST['additional_charges'][$ctr]);
						}
						if(!empty($multiInsert)) {
							$checkInsertRates =	$this->Mmm->multiInsert("service_contracts_rates", $multiInsert, "Added rates for the contract with transaction id:".$lastInserted);
						}
						else {
							$this->Abas->sysMsg("warnmsg", 'No rates encoded.');
						}

						//if($checkInsertRates) {
							$this->Abas->sysNotif("New Contract", $_SESSION['abas_login']['fullname']." has created new Contract with Control No." . $control_number . " under " . $company_name . " with Reference No." .$reference_no,"Operations","info");
							$this->Abas->sysMsg("sucmsg", 'Added new Contract with Control No.' . $control_number . ' under ' . $company_name . " with Reference No." .$reference_no);
						//}else{
						//	$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Contract! Please try again.");
						//}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Contract! Please try again.");
						$this->Abas->redirect(HTTP_PATH."operation/service_contract/listview");
						die();
					}
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Contract! Please try again.");
					$this->Abas->redirect(HTTP_PATH."operation/service_contract/listview");
					die();
				}
				$this->Abas->redirect(HTTP_PATH."operation/service_contract/listview");
			break;
			case "edit":
				$data['SC']				=	$this->Abas->getContract($id);
				$data['SC_Rates']		=	$this->Operation_model->getContractRates($id);
				$data['companies']		=	$this->Abas->getCompanies();
				$data['clients']		=	$this->Abas->getClients();
				$data['services']		=	$this->Abas->getServices();
				$data['contracts']		=	$this->Abas->getContracts();
				$this->load->view('operation/contract/form.php',$data);
			break;
			case "update":
					if(isset($_POST['reference_no'])) {
					$update	=	array();
					$update['company_id']					=	$this->Mmm->sanitize($_POST['company']);
					$update['client_id']					=	$this->Mmm->sanitize($_POST['client']);
					$update['reference_no']					=	$this->Mmm->sanitize($_POST['reference_no']);
					$update['type']							=	$this->Mmm->sanitize($_POST['service_type']);
					$update['contract_date']				=	$this->Mmm->sanitize($_POST['contract_date']);
					$update['parent_contract_id']			=	$this->Mmm->sanitize($_POST['mother_contract']);
					$update['quantity']						=	$this->Mmm->sanitize($_POST['quantity']);
					$update['unit']							=	$this->Mmm->sanitize($_POST['unit']);
					$update['rate']							=	$this->Mmm->sanitize($_POST['fixed_rate']);
					$update['vat_type']						=	$this->Mmm->sanitize($_POST['vat_type']);
					$update['amount']						=	$this->Mmm->sanitize($_POST['grand_total']);
					$update['details']						=	$this->Mmm->sanitize($_POST['contract_details']);
					$update['updated_on']					=	date("Y-m-d H:i:s");
					$update['updated_by']					=	$_SESSION['abas_login']['userid'];
					$control_number							=	$this->Operation_model->getContract($id)->control_number;
					$company_name							=	$this->Abas->getCompany($update['company_id'])->name;
					$reference_no							=	$update['reference_no'];
					$checkUpdate							=	$this->Mmm->dbUpdate('service_contracts',$update,$id,'Edited Contract with Control No.' . $control_number . ' under ' . $company_name . " with Reference No." .$reference_no);
					if($checkUpdate) {
						//delete previous rates
						$delete_details =	$this->db->query("DELETE FROM service_contracts_rates WHERE service_contract_id=".$id);
						foreach($_POST['warehouse'] as $ctr=>$val) {
							$multiInsert[$ctr]['service_contract_id']		=	$id;
							$multiInsert[$ctr]['warehouse']		=	$this->Mmm->sanitize($_POST['warehouse'][$ctr]);
							$multiInsert[$ctr]['rate']		=	$this->Mmm->sanitize($_POST['rate'][$ctr]);
							$multiInsert[$ctr]['quantity']		=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
							$multiInsert[$ctr]['unit']		=	$this->Mmm->sanitize($_POST['unit'][$ctr]);
							$multiInsert[$ctr]['additional_charges']		=	$this->Mmm->sanitize($_POST['additional_charges'][$ctr]);
						}
						$checkUpdateRate =	$this->Mmm->multiInsert("service_contracts_rates", $multiInsert, "Updated rates for the contract with transaction id:".$id);
						//if($checkUpdateRate) {
							$this->Abas->sysNotif("Edit Contract", $_SESSION['abas_login']['fullname']." has edited Contract with Control No." . $control_number . " under " . $company_name . " with Reference No." .$reference_no,"Operations","info");

							$this->Abas->sysMsg("sucmsg", 'Edited Contract with Control No.' . $control_number . ' under ' . $company_name . " with Reference No." .$reference_no);
						//}else{
						//	$this->Abas->sysMsg("errmsg", "An error has occurred while updating the Contract! Please try again.");
						//}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while updating the Contract! Please try again.");
						$this->Abas->redirect(HTTP_PATH."operation/service_contract/view/".$id);
						die();
					}
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred while updating the Contract! Please try again.");
					$this->Abas->redirect(HTTP_PATH."operation/service_contract/view/".$id);
					die();
				}
				$this->Abas->redirect(HTTP_PATH."operation/service_contract/view/".$id);
			break;
			case "submit":
				$contract			=	$this->Operation_model->getContract($id);
				$control_number		=	$contract['control_number'];
				$company_name		=	$this->Abas->getCompany($contract['company_id'])->name;
				$reference_no		=	$contract['reference_no'];
				$update['status']	=	"For Approval";
				$submit	=	$this->Mmm->dbUpdate('service_contracts',$update,$id,$_SESSION['abas_login']['fullname']." has submitted Contract with Transaction code No.".$id." for approval.");
				if($submit) {
					$this->Abas->sysNotif("Submit Contract", $_SESSION['abas_login']['fullname']." has submitted Contract with Control No." . $control_number . " under " . $company_name . " with Reference No." .$reference_no,"Operations","info");
					$this->Abas->sysMsg("sucmsg", 'Sucessfully submitted Contract with Control No.' . $control_number . ' under ' . $company_name . " with Reference No." .$reference_no);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred while submitting the Contract! Please try again.");
					die();
				}
				$this->Abas->redirect(HTTP_PATH."operation/service_contract/view/".$id);
			break;
			case "approve":
				$contract			=	$this->Operation_model->getContract($id);
				$control_number		=	$contract['control_number'];
				$company_name		=	$this->Abas->getCompany($contract['company_id'])->name;
				$reference_no		=	$contract['reference_no'];
				$update['status']	=	"0%";
				$approve	=	$this->Mmm->dbUpdate('service_contracts',$update,$id,$_SESSION['abas_login']['fullname']." has approved Contract with Transaction code No.".$id);
				if($approve) {
					$this->Abas->sysNotif("Approve Contract", $_SESSION['abas_login']['fullname']." has approved Contract with Control No." . $control_number . " under " . $company_name . " with Reference No." .$reference_no,"Operations","info");
					$this->Abas->sysMsg("sucmsg", 'Sucessfully approved Contract with Control No.' . $control_number . ' under ' . $company_name . " with Reference No." .$reference_no);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred while approving the Contract! Please try again.");
					die();
				}
				$this->Abas->redirect(HTTP_PATH."operation/service_contract/view/".$id);
			break;
			case "void":
				$contract			=	$this->Operation_model->getContract($id);
				$control_number		=	$contract['control_number'];
				$company_name		=	$this->Abas->getCompany($contract['company_id'])->name;
				$reference_no		=	$contract['reference_no'];
				$update['status']	=	"Voided";
				$voided	=	$this->Mmm->dbUpdate('service_contracts',$update,$id,$_SESSION['abas_login']['fullname']." has voided Contract with Transaction code No.".$id);
				if($voided) {
					$this->Abas->sysNotif("Void Contract", $_SESSION['abas_login']['fullname']." has voided Contract with Control No." . $control_number . " under " . $company_name . " with Reference No." .$reference_no,"Operations","info");
					$this->Abas->sysMsg("sucmsg", 'Voided Contract with Control No.' . $control_number . ' under ' . $company_name . " with Reference No." .$reference_no);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred while voiding the Contract! Please try again.");
					die();
				}
				$this->Abas->redirect(HTTP_PATH."operation/service_contract/view/".$id);
			break;
		}
	}
	public function setContractRemark($contract_id) {
		if(isset($_POST['comments'])) {
			$update['remark']	=	$this->Mmm->sanitize($_POST['comments']);
			$this->Mmm->dbUpdate('service_contracts',$update,$contract_id,'Added reamrk on Contract with Transaction No. '.$contract_id);
		}
	}
	public function service_order($action=NULL,$id=NULL) {
		$data	=	array();
		switch($action) {
			case "load":
				$table	=	"service_orders";
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					//$search	=	"";
					$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);
					foreach($data['rows'] as $ctr=>$row) {
						if(isset($row['service_contract_id'])) {
							$contract	=	$this->Abas->getContract($row['service_contract_id']);
							$data['rows'][$ctr]['reference_no']	=	$contract['reference_no'];
							if($contract['parent_contract_id']!=NULL) {
								$parent_contract	=	$this->Abas->getContract($contract['parent_contract_id']);
								$data['rows'][$ctr]['parent_reference_no']	=	$parent_contract['reference_no'];
							}
							else{
								$data['rows'][$ctr]['parent_reference_no']	=	"-";
							}
							$client	=	$this->Abas->getClient($contract['client_id']);
							$data['rows'][$ctr]['client']		=	$client['company'];
						}
						if(isset($row['company_id'])) {
							$data['rows'][$ctr]['company']		=	$this->Abas->getCompany($row['company_id'])->name;
						}
						if(isset($row['client_id'])) {
							$data['rows'][$ctr]['client']		=	$this->Abas->getClient($row['client_id'])->name;
						}
						if(isset($row['created_on'])) {
							$data['rows'][$ctr]['created_on']	=	date("j F Y h:i:s A", strtotime($row['created_on']));
						}
						if(isset($row['date_needed'])) {
							$data['rows'][$ctr]['date_needed']	=		date("j F Y", strtotime($row['date_needed']));
						}
						if(isset($row['created_by'])) {
							$user	=	$this->Abas->getUser($row['created_by']);
							$data['rows'][$ctr]['created_by']	=	$user['full_name'];
						}
						if(isset($row['comments'])) {
							if($row['status']=='Draft') {
								if($row['comments']!="" || $row['comments']!=NULL) {
									$data['rows'][$ctr]['status']	=	"For Editing";
								}
							}
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}
			break;
			case "listview":
				$data['viewfile']	=	"operation/service_order/listview.php";
				$this->load->view('gentlella_container.php',$data);
			break;
			case "view":
				$data['SO']			=	$this->Operation_model->getServiceOrder($id);
				$data['SO_detail']	=	$this->Operation_model->getServiceOrderDetail($data['SO']->type,$data['SO']->id);
				$data['viewfile']	=	"operation/service_order/view.php";
				$this->load->view('gentlella_container.php',$data);
			break;
			case "add":
				$data['contracts']	=	$this->Operation_model->getContracts();
				$this->load->view('operation/service_order/form.php',$data);
			break;
			case "insert";
				if(isset($_POST['contract_id'])) {
					$insert	=	array();
					$insert['service_contract_id']	=	$this->Mmm->sanitize($_POST['contract_id']);
					$insert['control_number']		=	$this->Abas->getNextSerialNumber('service_orders',$this->Mmm->sanitize($_POST['company_id']));
					$insert['company_id']			=	$this->Mmm->sanitize($_POST['company_id']);
					$insert['type']					=	$this->Mmm->sanitize($_POST['service_type']);
					$insert['date_needed']			=	$this->Mmm->sanitize($_POST['date_needed']);
					$insert['created_on']			=	date("Y-m-d H:i:s");
					$insert['created_by']			=	$_SESSION['abas_login']['userid'];
					$insert['remarks']				=	$this->Mmm->sanitize($_POST['remarks']);
					$insert['stat']					=	1;
					$insert['status']				=	"Draft";
					$control_number					=	$insert['control_number'];
					$company_name					=	$this->Abas->getCompany($insert['company_id'])->name;
					$contract						=	$this->Abas->getContract($insert['service_contract_id']);
					$reference_no					=	$contract['reference_no'];
					$checkInsert					=	$this->Mmm->dbInsert('service_orders',$insert,'Added new SO with Control No.' . $control_number . ' under ' . $company_name . " with Service Contract Ref. No." .$reference_no);
					if($checkInsert) {
						$insert_detail		=	array();
						$last_id_inserted	=	$this->Abas->getLastIDByTable('service_orders');
						if($insert['type']=="Shipping") {
							$table								=	"service_order_detail_voyage";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['vessel_id']			=	$this->Mmm->sanitize($_POST['vessel1']);
							$insert_detail['from_location']		=	$this->Mmm->sanitize($_POST['loading_port1']);
							$insert_detail['to_location']		=	$this->Mmm->sanitize($_POST['unloading_port1']);
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description1']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit1']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty1']);
							$insert_detail['stat']				=	1;
						}
						elseif($insert['type']=="Lighterage") {
							$table									=	"service_order_detail_lighterage";
							$insert_detail['service_order_id']		=	$last_id_inserted;
							$insert_detail['vessel_id']				=	$this->Mmm->sanitize($_POST['vessel3']);
							$insert_detail['source_vessel']			=	$this->Mmm->sanitize($_POST['source_vessel3']);
							$insert_detail['vessel_location']		=	$this->Mmm->sanitize($_POST['source_location3']);
							$insert_detail['discharge_location']	=	$this->Mmm->sanitize($_POST['discharge_location3']);
							$insert_detail['cargo_description']		=	$this->Mmm->sanitize($_POST['cargo_description3']);
							$insert_detail['unit']					=	$this->Mmm->sanitize($_POST['unit3']);
							$insert_detail['quantity']				=	$this->Mmm->sanitize($_POST['qty3']);
							$insert_detail['stat']					=	1;
						}
						elseif($insert['type']=="Time Charter") {
							$table	=	"service_order_detail_timecharter";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['vessel_id']			=	$this->Mmm->sanitize($_POST['vessel2']);
							$insert_detail['start_datetime']	=	$this->Mmm->sanitize($_POST['start_datetime2']);
							$insert_detail['end_datetime']		=	$this->Mmm->sanitize($_POST['end_datetime2']);
							$insert_detail['start_location']	=	$this->Mmm->sanitize($_POST['start_location2']);
							$insert_detail['end_location']		=	$this->Mmm->sanitize($_POST['end_location2']);
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description2']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit2']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty2']);
							$insert_detail['stat']				=	1;
						}
						elseif($insert['type']=="Towing") {
							$table	=	"service_order_detail_towing";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['vessel_id']			=	$this->Mmm->sanitize($_POST['vessel4']);
							$insert_detail['craft_towed']		=	$this->Mmm->sanitize($_POST['craft_towed4']);
							$insert_detail['from_location']		=	$this->Mmm->sanitize($_POST['from_location4']);
							$insert_detail['to_location']		=	$this->Mmm->sanitize($_POST['to_location4']);
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description4']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit4']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty4']);
							$insert_detail['stat']				=	1;
						}
						elseif($insert['type']=="Trucking") {
							$table									=	"service_order_detail_trucking";
							$insert_detail['service_order_id']		=	$last_id_inserted;
							$insert_detail['truck_id']				=	$this->Mmm->sanitize($_POST['truck5']);
							$insert_detail['from_location']			=	$this->Mmm->sanitize($_POST['from_location5']);
							//$insert_detail['to_location']			=	$this->Mmm->sanitize($_POST['to_location5'])." | ".$this->Mmm->sanitize($_POST['to_location6'])." | ".$this->Mmm->sanitize($_POST['to_location7'])." | ".$this->Mmm->sanitize($_POST['to_location8']);
							$insert_detail['to_location']			=	$this->Mmm->sanitize($_POST['to_location5']);
							$insert_detail['drop_off_point_1']		=	$this->Mmm->sanitize($_POST['to_location5']);
							$insert_detail['drop_off_point_2']		=	$this->Mmm->sanitize($_POST['to_location6']);
							$insert_detail['drop_off_point_3']		=	$this->Mmm->sanitize($_POST['to_location7']);
							$insert_detail['drop_off_point_4']		=	$this->Mmm->sanitize($_POST['to_location8']);
							$insert_detail['drop_off_quantity_1']	=	$this->Mmm->sanitize($_POST['drop_qty1']);
							$insert_detail['drop_off_quantity_2']	=	$this->Mmm->sanitize($_POST['drop_qty2']);
							$insert_detail['drop_off_quantity_3']	=	$this->Mmm->sanitize($_POST['drop_qty3']);
							$insert_detail['drop_off_quantity_4']	=	$this->Mmm->sanitize($_POST['drop_qty4']);
							$insert_detail['destination']			=	$this->Mmm->sanitize($_POST['destination5']);
							$insert_detail['warehouse']				=	$this->Mmm->sanitize($_POST['warehouse5']);
							$insert_detail['cargo_description']		=	$this->Mmm->sanitize($_POST['cargo_description5']);
							$insert_detail['unit']					=	$this->Mmm->sanitize($_POST['unit5']);
							$insert_detail['quantity']				=	$this->Mmm->sanitize($_POST['qty5']);
							$insert_detail['stat']					=	1;
						}
						elseif($insert['type']=="Handling") {
							$table								=	"service_order_detail_handling";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['warehouse']			=	$this->Mmm->sanitize($_POST['warehouse6']);
							$insert_detail['number_of_moves']	=	$this->Mmm->sanitize($_POST['moves6']);
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description6']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit6']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty6']);
							$insert_detail['stat']				=	1;
						}
						elseif($insert['type']=="Storage") {
							$table								=	"service_order_detail_storage";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description7']);
							$insert_detail['storage_location']	=	$this->Mmm->sanitize($_POST['storage_location7']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty7']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit7']);
							$insert_detail['start_date']		=	$this->Mmm->sanitize($_POST['start_date7']);
							$insert_detail['end_date']			=	$this->Mmm->sanitize($_POST['end_date7']);
							$insert_detail['stat']				=	1;
						}
						elseif($insert['type']=="Equipment Rental") {
							$table								=	"service_order_detail_rental";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['equipment_name']	=	$this->Mmm->sanitize($_POST['equipment_name8']);
							$insert_detail['description']		=	$this->Mmm->sanitize($_POST['description8']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty8']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit8']);
							$insert_detail['start_date']		=	$this->Mmm->sanitize($_POST['start_date8']);
							$insert_detail['end_date']			=	$this->Mmm->sanitize($_POST['end_date8']);
							$insert_detail['from_location']		=	$this->Mmm->sanitize($_POST['from_location8']);
							$insert_detail['to_location']			=	$this->Mmm->sanitize($_POST['to_location8']);
							$insert_detail['stat']				=	1;
						}
						$checkInsertDetails	=	$this->Mmm->dbInsert($table,$insert_detail,'Added new SO Detail for Service Order with Transaction Code No.'.$last_id_inserted);
						if($checkInsertDetails) {
							$this->Abas->sysNotif("New Service Order", $_SESSION['abas_login']['fullname']." has created new Service Order with Control No." . $control_number . " under " . $company_name . " with Service Contract Ref. No." .$reference_no,"Operations","info");
							$this->Abas->sysMsg("sucmsg", "Added new Service Order with Control No." . $control_number . " under " . $company_name . " with Service Contract Ref. No." .$reference_no);
						}
						else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the SO! Please try again.");
							$this->Abas->redirect(HTTP_PATH."operation/service_order/listview");
							die();
						}
					}
					else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Service Order! Please try again.");
						$this->Abas->redirect(HTTP_PATH."operation/service_order/listview");
						die();
					}
				}
				$this->Abas->redirect(HTTP_PATH."operation/service_order/listview");
			break;
			case "edit":
				$data['SO']			=	$this->Operation_model->getServiceOrder($id);
				$data['SO_detail']	=	$this->Operation_model->getServiceOrderDetail($data['SO']->type,$data['SO']->id);
				$data['vessels']	=	$this->Abas->getVesselsByCompany($data['SO']->company_id);
				$data['trucks']		=	$this->Abas->getTrucksByCompany($data['SO']->company_id);
				$data['contracts']	=	$this->Operation_model->getContracts();
				$this->load->view('operation/service_order/form.php',$data);
			break;
			case "update":
				if(isset($_POST['contract_id'])) {
					$update	=	array();
					$update['service_contract_id']			=	$this->Mmm->sanitize($_POST['contract_id']);
					$update['company_id']					=	$this->Mmm->sanitize($_POST['company_id']);
					$update['type']							=	$this->Mmm->sanitize($_POST['service_type']);
					$update['date_needed']					=	$this->Mmm->sanitize($_POST['date_needed']);
					$update['created_on']					=	date("Y-m-d H:i:s");
					$update['created_by']					=	$_SESSION['abas_login']['userid'];
					$update['remarks']						=	$this->Mmm->sanitize($_POST['remarks']);
					$update['comments']						=	NULL;
					$update['stat']							=	1;
					$update['status']						=	"Draft";
					$control_number							=	$this->Operation_model->getServiceOrder($id)->control_number;
					$company_name							=	$this->Abas->getCompany($update['company_id'])->name;
					$contract								=	$this->Abas->getContract($update['service_contract_id']);
					$reference_no							=	$contract['reference_no'];
					$checkUpdate							=	$this->Mmm->dbUpdate('service_orders',$update,$id,'Edited SO with Control No.' . $control_number . ' under ' . $company_name . " with Service Contract Ref. No." .$reference_no);
					$checkRemoved							=	$this->Operation_model->deleteServiceOrderDetail($id);
					if($checkUpdate && $checkRemoved) {
						$insert_detail	=	array();
						$last_id_inserted	=	$id;
						if($update['type']=="Shipping") {
							$table	=	"service_order_detail_voyage";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['vessel_id']			=	$this->Mmm->sanitize($_POST['vessel1']);
							$insert_detail['from_location']		=	$this->Mmm->sanitize($_POST['loading_port1']);
							$insert_detail['to_location']		=	$this->Mmm->sanitize($_POST['unloading_port1']);
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description1']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit1']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty1']);
							$insert_detail['stat']				=	1;
						}
						elseif($update['type']=="Lighterage") {
							$table	=	"service_order_detail_lighterage";
							$insert_detail['service_order_id']		=	$last_id_inserted;
							$insert_detail['vessel_id']				=	$this->Mmm->sanitize($_POST['vessel3']);
							$insert_detail['source_vessel']			=	$this->Mmm->sanitize($_POST['source_vessel3']);
							$insert_detail['vessel_location']		=	$this->Mmm->sanitize($_POST['source_location3']);
							$insert_detail['discharge_location']	=	$this->Mmm->sanitize($_POST['discharge_location3']);
							$insert_detail['cargo_description']		=	$this->Mmm->sanitize($_POST['cargo_description3']);
							$insert_detail['unit']					=	$this->Mmm->sanitize($_POST['unit3']);
							$insert_detail['quantity']				=	$this->Mmm->sanitize($_POST['qty3']);
							$insert_detail['stat']					=	1;
						}
						elseif($update['type']=="Time Charter") {
							$table	=	"service_order_detail_timecharter";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['vessel_id']			=	$this->Mmm->sanitize($_POST['vessel2']);
							$insert_detail['start_datetime']	=	$this->Mmm->sanitize($_POST['start_datetime2']);
							$insert_detail['end_datetime']		=	$this->Mmm->sanitize($_POST['end_datetime2']);
							$insert_detail['start_location']	=	$this->Mmm->sanitize($_POST['start_location2']);
							$insert_detail['end_location']		=	$this->Mmm->sanitize($_POST['end_location2']);
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description2']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit2']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty2']);
							$insert_detail['stat']				=	1;
						}
						elseif($update['type']=="Towing") {
							$table	=	"service_order_detail_towing";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['vessel_id']			=	$this->Mmm->sanitize($_POST['vessel4']);
							$insert_detail['craft_towed']		=	$this->Mmm->sanitize($_POST['craft_towed4']);
							$insert_detail['from_location']		=	$this->Mmm->sanitize($_POST['from_location4']);
							$insert_detail['to_location']		=	$this->Mmm->sanitize($_POST['to_location4']);
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description4']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit4']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty4']);
							$insert_detail['stat']				=	1;
						}
						elseif($update['type']=="Trucking") {
							$table	=	"service_order_detail_trucking";
							$insert_detail['service_order_id']		=	$last_id_inserted;
							$insert_detail['truck_id']				=	$this->Mmm->sanitize($_POST['truck5']);
							$insert_detail['from_location']			=	$this->Mmm->sanitize($_POST['from_location5']);
							//$insert_detail['to_location']			=	$this->Mmm->sanitize($_POST['to_location5'])." | ".$this->Mmm->sanitize($_POST['to_location6'])." | ".$this->Mmm->sanitize($_POST['to_location7'])." | ".$this->Mmm->sanitize($_POST['to_location8']);
							$insert_detail['to_location']			=	$this->Mmm->sanitize($_POST['to_location5']);
							$insert_detail['drop_off_point_1']		=	$this->Mmm->sanitize($_POST['to_location5']);
							$insert_detail['drop_off_point_2']		=	$this->Mmm->sanitize($_POST['to_location6']);
							$insert_detail['drop_off_point_3']		=	$this->Mmm->sanitize($_POST['to_location7']);
							$insert_detail['drop_off_point_4']		=	$this->Mmm->sanitize($_POST['to_location8']);
							$insert_detail['drop_off_quantity_1']	=	$this->Mmm->sanitize($_POST['drop_qty1']);
							$insert_detail['drop_off_quantity_2']	=	$this->Mmm->sanitize($_POST['drop_qty2']);
							$insert_detail['drop_off_quantity_3']	=	$this->Mmm->sanitize($_POST['drop_qty3']);
							$insert_detail['drop_off_quantity_4']	=	$this->Mmm->sanitize($_POST['drop_qty4']);
							$insert_detail['destination']			=	$this->Mmm->sanitize($_POST['destination5']);
							$insert_detail['warehouse']				=	$this->Mmm->sanitize($_POST['warehouse5']);
							$insert_detail['cargo_description']		=	$this->Mmm->sanitize($_POST['cargo_description5']);
							$insert_detail['unit']					=	$this->Mmm->sanitize($_POST['unit5']);
							$insert_detail['quantity']				=	$this->Mmm->sanitize($_POST['qty5']);
							$insert_detail['stat']					=	1;
						}
						elseif($update['type']=="Handling") {
							$table								=	"service_order_detail_handling";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['warehouse']			=	$this->Mmm->sanitize($_POST['warehouse6']);
							$insert_detail['number_of_moves']	=	$this->Mmm->sanitize($_POST['moves6']);
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description6']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit6']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty6']);
							$insert_detail['stat']				=	1;
						}elseif($update['type']=="Storage") {
							$table								=	"service_order_detail_storage";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['cargo_description']	=	$this->Mmm->sanitize($_POST['cargo_description7']);
							$insert_detail['storage_location']	=	$this->Mmm->sanitize($_POST['storage_location7']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty7']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit7']);
							$insert_detail['start_date']		=	$this->Mmm->sanitize($_POST['start_date7']);
							$insert_detail['end_date']			=	$this->Mmm->sanitize($_POST['end_date7']);
							$insert_detail['stat']				=	1;
						}elseif($update['type']=="Equipment Rental") {
							$table								=	"service_order_detail_rental";
							$insert_detail['service_order_id']	=	$last_id_inserted;
							$insert_detail['equipment_name']	=	$this->Mmm->sanitize($_POST['equipment_name8']);
							$insert_detail['description']		=	$this->Mmm->sanitize($_POST['description8']);
							$insert_detail['quantity']			=	$this->Mmm->sanitize($_POST['qty8']);
							$insert_detail['unit']				=	$this->Mmm->sanitize($_POST['unit8']);
							$insert_detail['start_date']		=	$this->Mmm->sanitize($_POST['start_date8']);
							$insert_detail['end_date']			=	$this->Mmm->sanitize($_POST['end_date8']);
							$insert_detail['from_location']		=	$this->Mmm->sanitize($_POST['from_location8']);
							$insert_detail['to_location']		=	$this->Mmm->sanitize($_POST['to_location8']);
							$insert_detail['stat']				=	1;
						}
						$checkInsertDetails	=	$this->Mmm->dbInsert($table,$insert_detail,'Edited SO Detail for Service Order with Transaction Code No.'.$last_id_inserted);
						if($checkInsertDetails) {
							$this->Abas->sysNotif("Edit Service Order", $_SESSION['abas_login']['fullname']." has edited Service Order with Control No." . $control_number . " under " . $company_name . " with Service Contract Ref. No." .$reference_no,"Operations","info");
							$this->Abas->sysMsg("sucmsg", "Edited Service Order with Control No." . $control_number . " under " . $company_name . " with Service Contract Ref. No." .$reference_no);
						}
						else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while updating the SO! Please try again.");
							$this->Abas->redirect(HTTP_PATH."operation/service_order/view/".$id);
							die();
						}
					}
					else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while updating the Service Order! Please try again.");
						$this->Abas->redirect(HTTP_PATH."operation/service_order/view/".$id);
						die();
					}
				}
				$this->Abas->redirect(HTTP_PATH."operation/service_order/view/".$id);
			break;
			case "submit":
				$this->updateServiceOrderStatus("For Approval",$id);
				$this->Abas->redirect(HTTP_PATH."operation/service_order/view/".$id);
			break;
			case "approve":
				$this->updateServiceOrderStatus("Approved",$id);
				$this->Abas->redirect(HTTP_PATH."operation/service_order/view/".$id);
			break;
			case "return":
				$this->updateServiceOrderStatus("Draft",$id);
				$this->Abas->redirect(HTTP_PATH."operation/service_order/view/".$id);
			break;
			case "cancel":
				$this->updateServiceOrderStatus("Cancelled",$id);
				$this->Abas->redirect(HTTP_PATH."operation/service_order/view/".$id);
			break;
			case "print":
				require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
				$data['SO']			=	$this->Operation_model->getServiceOrder($id);
				$data['SO_detail']	=	$this->Operation_model->getServiceOrderDetail($data['SO']->type,$data['SO']->id);
				$this->load->view("operation/service_order/print.php",$data);
			break;
		}
	}
	public function setServiceOrderComments($service_order_id) {
		if(isset($_POST['comments'])) {
			$update['comments']	=	$this->Mmm->sanitize($_POST['comments']);
			$this->Mmm->dbUpdate('service_orders',$update,$service_order_id,'Added comments on Service Order with Transaction No. '.$service_order_id);
		}
	}
	private function updateServiceOrderStatus($status,$service_order_id) {
		$service_order		=	$this->Operation_model->getServiceOrder($service_order_id);
		$control_number		=	$service_order->control_number;
		$company_name		=	$this->Abas->getCompany($service_order->company_id)->name;
		$contract			=	$this->Abas->getContract($service_order->service_contract_id);
		$reference_no		=	$contract['reference_no'];
		if($status=="Draft") {
			$action 		=	"returned";
		}
		elseif($status=="For Approval") {
			$action			=	"submitted";
		}
		else{
			$update['approved_by']	=	$_SESSION['abas_login']['userid'];
			$update['approved_on']	=	date('Y-m-d H:m:s');
			$action			=	$status;
		}
		$update['status']	=	$status;
		
		$checkUpdate		=	$this->Mmm->dbUpdate('service_orders',$update,$service_order_id,'Updated status of SO with Transaction Code No.' . $id . " to '".$status."'");
		if($checkUpdate) {
			$this->Abas->sysNotif("Service Order", $_SESSION['abas_login']['fullname']." has ".strtolower($action)." Service Order with Control No." . $control_number . " under " . $company_name . " with Service Contract Ref. No." .$reference_no,"Operations","info");
			$this->Abas->sysMsg("sucmsg", ucwords($action)." Service Order with Control No." . $control_number . " under " . $company_name . " with Service Contract Ref. No." .$reference_no);
		}
		else{
			$this->Abas->sysMsg("errmsg", "An error has occurred while updating the status of Service Order! Please try again.");
		}
	}
	public function out_turn_summary($action=NULL,$id=NULL) {
		$data	=	array();
		switch($action) {
			case "load":
				$table	=	'ops_out_turn_summary';
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);
					foreach($data['rows'] as $ctr=>$row) {
						if($row['service_order_id']!=0) {
							$service_order					=	$this->Operation_model->getServiceOrder($row['service_order_id']);
						$contract							=	$service_order->contract;
						$data['rows'][$ctr]['reference_no']	=	$contract['reference_no'];
						$client								=	$this->Abas->getClient($contract['client_id']);
						$data['rows'][$ctr]['client']		=	$client['company'];
							
							if(isset($contract['mother_contract']['reference_no'])){
								$data['rows'][$ctr]['mother_contract']	=	$contract['mother_contract']['reference_no'];
							}	
						
						}
						else{
							$contract							=	$this->Operation_model->getContract($row['service_contract_id']);
							$client								=	$this->Abas->getClient($contract['client_id']);
							$data['rows'][$ctr]['reference_no']	=	$contract['reference_no'];
							$data['rows'][$ctr]['client']		=	$client['company'];
							if(isset($contract['mother_contract']['reference_no'])){
								$data['rows'][$ctr]['mother_contract']	=	$contract['mother_contract']['reference_no'];
							}	
						}
						if($row['type_of_service']=='Shipping') {
							$details	=	$this->Operation_model->getOutTurnSummaryDetails($row['id']);
							$data['rows'][$ctr]['bill_of_lading']	=	$details->bill_of_lading_number==0?"-":$details->bill_of_lading_number;
						}
						else{
							$data['rows'][$ctr]['bill_of_lading']	=	"-";
						}
						if(isset($row['company_id'])) {
							$data['rows'][$ctr]['company']			=	$this->Abas->getCompany($row['company_id'])->name;
						}
						if(isset($row['created_on'])) {
							$data['rows'][$ctr]['created_on']		=	date("j F Y h:i:s A", strtotime($row['created_on']));
						}
						if(isset($row['created_by'])) {
							$user	=	$this->Abas->getUser($row['created_by']);
							$data['rows'][$ctr]['created_by']		=	$user['full_name'];
						}
						if($row['comments']!="" || $row['comments']!=null) {
							if($row['status']=="Draft") {
								$data['rows'][$ctr]['status']		=	"Returned - For Editing";
							}
						}
					}
					header('Content-Type: application/json');
					echo json_encode($data);
					exit();
				}
			break;
			case "listview":
				$data['viewfile']	=	"operation/out_turn_summary/listview.php";
				$this->load->view('gentlella_container.php',$data);
			break;
			case "view":
				$data['OS']					=	$this->Operation_model->getOutTurnSummary($id);
				if($data['OS']->type_of_service=="Shipping") {
					$data['OS_Details']		=	$this->Operation_model->getOutTurnSummaryDetails($id);
					$data['OS_Attachments']	=	$this->Operation_model->getOutTurnSummaryAttachments($id);
					$data['OS_Output']		=	$this->Operation_model->getOutTurnSummaryOutput($id);
				}
				elseif($data['OS']->type_of_service=="Trucking" || $data['OS']->type_of_service=="Handling") {
					$data['OS_Deliveries']	=	$this->Operation_model->getOutTurnSummaryDeliveries($id);
				}
				elseif($data['OS']->type_of_service=="Lighterage" || $data['OS']->type_of_service=="Time Charter" || $data['OS']->type_of_service=="Towing") {
					$data['OS_Details']		=	$this->Operation_model->getOutTurnSummaryDetails($id);
				}
				if($data['OS']->service_order_id!=0) {
					$data['SO']			=	$this->Operation_model->getServiceOrder($data['OS']->service_order_id);
					$data['SO_Details']	=	$this->Operation_model->getServiceOrderDetail($data['SO']->type,$data['OS']->service_order_id);
					$data['contract']	=	$this->Abas->getContract($data['SO']->service_contract_id);
				}
				else{
					$data['contract']	=	$this->Abas->getContract($data['OS']->service_contract_id);
				}
				$data['viewfile']	="operation/out_turn_summary/view.php";
				$this->load->view('gentlella_container.php',$data);
			break;
			case "add":
				$data['companies']	=	$this->Abas->getCompanies();
				$this->load->view('operation/out_turn_summary/form.php',$data);
			break;
			case "insert":
				if(isset($_POST['company'])) {
					$insert	=	array();
					$insert[0]['control_number']		=	$this->Abas->getNextSerialNumber('ops_out_turn_summary',$this->Mmm->sanitize($_POST['company']));
					$insert[0]['service_order_id']		=	$this->Mmm->sanitize($_POST['service_order']);
					$insert[0]['service_contract_id']	=	$this->Mmm->sanitize($_POST['reference_no_OS']);
					$insert[0]['company_id']			=	$this->Mmm->sanitize($_POST['company']);
					$insert[0]['type_of_service']		=	$this->Mmm->sanitize($_POST['service_order_type']);
					$insert[0]['created_by']			=	$_SESSION['abas_login']['userid'];
					$insert[0]['created_on']			=	date("Y-m-d H:i:s");
					$insert[0]['remarks']				=	$this->Mmm->sanitize($_POST['remarks']);
					$insert[0]['stat']					=	1;
					$insert[0]['status']				=	"Draft";
					$insert[0]['comments']				=	NULL;
					$control_number						=	$insert[0]['control_number'];
					$company_name						=	$this->Abas->getCompany($insert[0]['company_id'])->name;
					$service_type						=	$insert[0]['type_of_service'];
					$checkInsert		=	$this->Mmm->dbInsert('ops_out_turn_summary',$insert[0],'Added new Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
					$last_id_inserted	=	$this->Abas->getLastIDByTable('ops_out_turn_summary');
					if(isset($_POST['SO_radio'])) {
						if($checkInsert && $service_type	=='Shipping') {
							$insert[1]['out_turn_summary_id']			=	$last_id_inserted;
							$insert[1]['bill_of_lading_number']			=	isset($_POST['bol_number'])?$this->Mmm->sanitize($_POST['bol_number']):NULL;
							$insert[1]['quantity_per_bill_of_lading']	=	isset($_POST['qty_bol'])?$this->Mmm->sanitize($_POST['qty_bol']):NULL;
							$insert[1]['weight_per_bill_of_lading']		=	isset($_POST['weight_bol'])?$this->Mmm->sanitize($_POST['weight_bol']):NULL;
							$insert[1]['shipper']						=	isset($_POST['shipper'])?$this->Mmm->sanitize($_POST['shipper']):NULL;
							$insert[1]['consignee']						=	isset($_POST['consignee'])?$this->Mmm->sanitize($_POST['consignee']):NULL;
							$insert[1]['surveyor']						=	isset($_POST['surveyor'])?$this->Mmm->sanitize($_POST['surveyor']):NULL;
							$insert[1]['arrastre']						=	isset($_POST['arrastre'])?$this->Mmm->sanitize($_POST['arrastre']):NULL;
							$insert[1]['vessel_id']						=	isset($_POST['vessel_id'])?$this->Mmm->sanitize($_POST['vessel_id']):NULL;
							$insert[1]['mother_vessel']					=	isset($_POST['mother_vessel'])?$this->Mmm->sanitize($_POST['mother_vessel']):NULL;
							$insert[1]['voyage_number']					=	isset($_POST['voyage_number'])?$this->Mmm->sanitize($_POST['voyage_number']):NULL;
							$insert[1]['port_of_origin']				=	isset($_POST['port_origin'])?$this->Mmm->sanitize($_POST['port_origin']):NULL;
							$insert[1]['port_of_destination']			=	isset($_POST['port_destination'])?$this->Mmm->sanitize($_POST['port_destination']):NULL;
							$insert[1]['loading_arrival']				=	$this->Mmm->sanitize($_POST['loading_arrival_date']);
							$insert[1]['loading_start']					=	$this->Mmm->sanitize($_POST['loading_start_datetime']);
							$insert[1]['loading_ended']					=	$this->Mmm->sanitize($_POST['loading_ended_datetime']);
							$insert[1]['loading_departure']				=	$this->Mmm->sanitize($_POST['loading_departure_date']);
							$insert[1]['loading_quantity_volume']		=	$this->Mmm->sanitize($_POST['loading_quantity_volume']);
							$insert[1]['unloading_arrival']				=	$this->Mmm->sanitize($_POST['unloading_arrival_date']);
							$insert[1]['unloading_start']				=	$this->Mmm->sanitize($_POST['unloading_start_datetime']);
							$insert[1]['unloading_ended']				=	$this->Mmm->sanitize($_POST['unloading_ended_datetime']);
							$insert[1]['unloading_departure']			=	$this->Mmm->sanitize($_POST['unloading_departure_date']);
							$insert[1]['unloading_quantity_volume']		=	$this->Mmm->sanitize($_POST['unloading_quantity_volume']);
							$checkInsertDetails	=	$this->Mmm->dbInsert('ops_out_turn_summary_details',$insert[1],'Added details on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
							if($checkInsertDetails) {
								if(isset($_POST['attachments'])) {
									foreach($_POST['attachments'] as $ctr=>$val) {
										$multiInsertAttach[$ctr]['out_turn_summary_id']			=	$last_id_inserted;
										$multiInsertAttach[$ctr]['document_name']				=	$this->Mmm->sanitize($_POST['attachments'][$ctr]);
									}
									$checkInsertAttach	=	$this->Mmm->multiInsert('ops_out_turn_summary_attachments',$multiInsertAttach,'Added atachments on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
								}
								else{
									//ok to proceed if no attachements
									$checkInsertAttach	=	true;
								}
							}
							else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
								$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
								die();
							}
							if($checkInsertAttach) {
								if(isset($_POST['shipper_number_of_bags'])) {
									$insert[2]['out_turn_summary_id']			=	$last_id_inserted;
									$insert[2]['shipper_number_of_bags']		=	$this->Mmm->sanitize($_POST['shipper_number_of_bags']);
									$insert[2]['shipper_weight']				=	$this->Mmm->sanitize($_POST['shipper_weight']);
									$insert[2]['consignee_number_of_bags']		=	$this->Mmm->sanitize($_POST['consignee_number_of_bags']);
									$insert[2]['consignee_weight']				=	$this->Mmm->sanitize($_POST['consignee_weight']);
									$insert[2]['variance_number_of_bags']		=	$this->Mmm->sanitize($_POST['variance_number_of_bags']);
									$insert[2]['variance_weight']				=	$this->Mmm->sanitize($_POST['variance_weight']);
									$insert[2]['percentage_number_of_bags']		=	$this->Mmm->sanitize($_POST['percentage_number_of_bags']);
									$insert[2]['percentage_weight']				=	$this->Mmm->sanitize($_POST['percentage_weight']);
									$insert[2]['good_number_of_bags']			=	$this->Mmm->sanitize($_POST['good_number_of_bags']);
									$insert[2]['damaged_number_of_bags']		=	$this->Mmm->sanitize($_POST['damaged_number_of_bags']);
									$insert[2]['total_number_of_bags']			=	$this->Mmm->sanitize($_POST['total_number_of_bags']);
								}
								$checkInsertFinal	=	$this->Mmm->dbInsert("ops_out_turn_summary_output",$insert[2],"Added Final Output and Variance on Out-Turn Summary with Control No." . $control_number . " under " . $company_name);
							}
							else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
								$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
								die();
							}
						}
						elseif($checkInsert && $service_type=='Trucking' || $checkInsert && $service_type=='Handling') {
							if(isset($_POST['sorting'])) {
								foreach($_POST['sorting'] as $ctr=>$val) {
									$multiInsert[$ctr]['sorting']							=	$this->Mmm->sanitize($_POST['sorting'][$ctr]);
									$multiInsert[$ctr]['out_turn_summary_id']				=	$last_id_inserted;
									$multiInsert[$ctr]['delivery_date']						=	$this->Mmm->sanitize($_POST['delivery_date'][$ctr]);
									$multiInsert[$ctr]['trucking_company']					=	isset($_POST['trucking_company'][$ctr])?$this->Mmm->sanitize($_POST['trucking_company'][$ctr]):NULL;
									$multiInsert[$ctr]['truck_plate_number']				=	isset($_POST['truck_plate_number'][$ctr])?$this->Mmm->sanitize($_POST['truck_plate_number'][$ctr]):NULL;
									$multiInsert[$ctr]['truck_driver']						=	isset($_POST['truck_driver'][$ctr])?$this->Mmm->sanitize($_POST['truck_driver'][$ctr]):NULL;
									$multiInsert[$ctr]['warehouse']							=	$this->Mmm->sanitize($_POST['warehouse'][$ctr]);
									$multiInsert[$ctr]['quantity']							=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
									$multiInsert[$ctr]['gross_weight']						=	$this->Mmm->sanitize($_POST['gross_weight'][$ctr]);
									$multiInsert[$ctr]['tare_weight']						=	isset($_POST['tare_weight'][$ctr])?$this->Mmm->sanitize($_POST['tare_weight'][$ctr]):NULL;
									$multiInsert[$ctr]['net_weight']						=	isset($_POST['net_weight'][$ctr])?$this->Mmm->sanitize($_POST['net_weight'][$ctr]):NULL;
									$multiInsert[$ctr]['number_of_moves']					=	isset($_POST['number_of_moves'][$ctr])?$this->Mmm->sanitize($_POST['number_of_moves'][$ctr]):NULL;
									$multiInsert[$ctr]['variety_item']						=	isset($_POST['variety_item'][$ctr])?$this->Mmm->sanitize($_POST['variety_item'][$ctr]):NULL;
									$multiInsert[$ctr]['transaction']						=	isset($_POST['transaction'][$ctr])?$this->Mmm->sanitize($_POST['transaction'][$ctr]):NULL;
									$multiInsert[$ctr]['delivery_receipt_number']			=	isset($_POST['dr_number'][$ctr])?$this->Mmm->sanitize($_POST['dr_number'][$ctr]):NULL;
									$multiInsert[$ctr]['weighing_ticket_number']			=	isset($_POST['wt_number'][$ctr])?$this->Mmm->sanitize($_POST['wt_number'][$ctr]):NULL;
									$multiInsert[$ctr]['way_bill_number']					=	isset($_POST['wb_number'][$ctr])?$this->Mmm->sanitize($_POST['wb_number'][$ctr]):NULL;
									$multiInsert[$ctr]['authority_to_load_number']			=	isset($_POST['atl_number'][$ctr])?$this->Mmm->sanitize($_POST['atl_number'][$ctr]):NULL;
									$multiInsert[$ctr]['cargo_receipt_number']				=	isset($_POST['cr_number'][$ctr])?$this->Mmm->sanitize($_POST['cr_number'][$ctr]):NULL;
									$multiInsert[$ctr]['others']							=	isset($_POST['others'][$ctr])?$this->Mmm->sanitize($_POST['others'][$ctr]):NULL;
									$multiInsert[$ctr]['warehouse_issuance_form_number']	=	isset($_POST['wif_number'][$ctr])?$this->Mmm->sanitize($_POST['wif_number'][$ctr]):NULL;
									$multiInsert[$ctr]['warehouse_receipt_form_number']		=	isset($_POST['wrf_number'][$ctr])?$this->Mmm->sanitize($_POST['wrf_number'][$ctr]):NULL;
								}
								$checkInsertDeliveries	=	$this->Mmm->multiInsert('ops_out_turn_summary_deliveries',$multiInsert,'Added deliveries on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
							}
							elseif($_FILES["uploaded_file"]["name"]!=NULL) {
								$target_dir		=	WPATH.'assets/uploads/operations/out_turn_summary/';
								$old_filename	=	explode(".", basename($_FILES["uploaded_file"]["name"]));
								$new_filename	=	round(microtime(true)) . '.' . end($old_filename);
								$target_file	=	$target_dir . $new_filename;
								$uploaded		=	move_uploaded_file($_FILES["uploaded_file"]["tmp_name"],$target_file);//import the file on assets/upload
								/*$config['upload_path'] = $target_dir;
								$config['allowed_types'] = '*';
								$config['file_name'] = $new_filename;
								$this->load->library('upload', $config);
								if (!$this->upload->do_upload('uploaded_file')) {
									$error = array('error' => $this->upload->display_errors());
									$_SESSION['warnmsg'] = $error['error'];
									$uploaded = FALSE;
								}
								else {
									$uploaded = TRUE;
								}*/
								//check if uploaded on server
								if($uploaded) {
									//Check if succesfully imported on db
									$imported	=	$this->import_out_turn_deliveries($service_type,$new_filename,$last_id_inserted);
									if($imported) {
										$checkInsertDeliveries	=	TRUE;
									}
									else{
										$checkInsertDeliveries=	FALSE;
										$this->Abas->sysNotif("Out-Turn Summary", "Out-turn Summary file was not successfully imported, please check if the file you are uploading contains no error and try again!","Operations","danger");
										$this->Abas->sysMsg("errmsg", "Out-turn Summary file was not successfully imported, please check if the file you are uploading contains no error and try again!");
										$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
										die();
									}
								}
								else{
									$checkInsertDeliveries=	FALSE;
									$this->Abas->sysNotif("Out-Turn Summary", "There was an error occured while uploading the Out-turn Summary File, please contact your administrator.!","danger");
									$this->Abas->sysMsg("errmsg", "There was an error occured while uploading the Out-turn Summary File, please contact your administrator.!");
									$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
									die();
								}
							}
						}
						elseif($checkInsert && $service_type=='Lighterage' || $checkInsert && $service_type=='Time Charter' || $checkInsert && $service_type=='Towing') {
							$insert[3]['out_turn_summary_id']			=	$last_id_inserted;
							$insert[3]['shipper']						=	isset($_POST['shipper'])?$this->Mmm->sanitize($_POST['shipper']):NULL;
							$insert[3]['consignee']						=	isset($_POST['consignee'])?$this->Mmm->sanitize($_POST['consignee']):NULL;
							$insert[3]['vessel_id']						=	isset($_POST['vessel_id'])?$this->Mmm->sanitize($_POST['vessel_id']):NULL;
							$insert[3]['mother_vessel']					=	isset($_POST['mother_vessel'])?$this->Mmm->sanitize($_POST['mother_vessel']):NULL;
							$insert[3]['voyage_number']					=	isset($_POST['voyage_number'])?$this->Mmm->sanitize($_POST['voyage_number']):NULL;
							if($service_type=='Towing'){
								$insert[3]['port_of_origin']				=	isset($_POST['departure_location'])?$this->Mmm->sanitize($_POST['departure_location']):NULL;
								$insert[3]['port_of_destination']			=	isset($_POST['arrival_location'])?$this->Mmm->sanitize($_POST['arrival_location']):NULL;
							}else{
								$insert[3]['port_of_origin']				=	isset($_POST['port_origin'])?$this->Mmm->sanitize($_POST['port_origin']):NULL;
								$insert[3]['port_of_destination']			=	isset($_POST['port_destination'])?$this->Mmm->sanitize($_POST['port_destination']):NULL;

								if($service_type=='Time Charter'){
									$soa_id		=	isset($_POST['soa_id'])?$this->Mmm->sanitize($_POST['soa_id']):NULL;
									if($soa_id!=NULL){
										$sqlz = "UPDATE statement_of_accounts SET out_turn_summary_id=".$last_id_inserted." WHERE id=".$soa_id;
										$this->Mmm->query($sqlz,'Linked the Time-charter Out-turn Summary to SOA TSCode No.'.$soa_id);
									}
								}
							}
							$insert[3]['loading_arrival']				=	$this->Mmm->sanitize($_POST['loading_arrival_date']);
							$insert[3]['loading_start']					=	$this->Mmm->sanitize($_POST['loading_start_datetime']);
							$insert[3]['loading_ended']					=	$this->Mmm->sanitize($_POST['loading_ended_datetime']);
							$insert[3]['loading_departure']				=	$this->Mmm->sanitize($_POST['loading_departure_date']);
							$insert[3]['loading_quantity_volume']		=	$this->Mmm->sanitize($_POST['loading_quantity_volume']);
							$insert[3]['unloading_arrival']				=	$this->Mmm->sanitize($_POST['unloading_arrival_date']);
							$insert[3]['unloading_start']				=	$this->Mmm->sanitize($_POST['unloading_start_datetime']);
							$insert[3]['unloading_ended']				=	$this->Mmm->sanitize($_POST['unloading_ended_datetime']);
							$insert[3]['unloading_departure']			=	$this->Mmm->sanitize($_POST['unloading_departure_date']);
							$insert[3]['unloading_quantity_volume']		=	$this->Mmm->sanitize($_POST['unloading_quantity_volume']);
							$insert[3]['lighterage_receipt_number']		=	$this->Mmm->sanitize($_POST['lighterage_receipt_no']);
							$insert[3]['trip_ticket_number']			=	$this->Mmm->sanitize($_POST['trip_ticket_no']);
							$insert[3]['statement_of_facts_number']		=	$this->Mmm->sanitize($_POST['statement_of_facts_no']);
							$insert[3]['barge_patron']					=	$this->Mmm->sanitize($_POST['barge_patron']);
							$insert[3]['loading_batch_weight']			=	$this->Mmm->sanitize($_POST['loading_batch_weight']);
							$insert[3]['unloading_batch_weight']		=	$this->Mmm->sanitize($_POST['unloading_batch_weight']);
							$checkInsertDetails	=	$this->Mmm->dbInsert('ops_out_turn_summary_details',$insert[3],'Added details on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
						}
						else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
							$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
							die();
						}
					}
					elseif(isset($_POST['OS_radio'])) {
						if($checkInsert && $service_type =='Shipping') {
							$OS_Details =	$this->Operation_model->getOutTurnSummaryDetails($_POST['out_turn_summary']);
							$OS_Attachments =	$this->Operation_model->getOutTurnSummaryAttachments($_POST['out_turn_summary']);
							$OS_Output =	$this->Operation_model->getOutTurnSummaryOutput($_POST['out_turn_summary']);
							$insert[1]['out_turn_summary_id']			=	$last_id_inserted;
							$insert[1]['bill_of_lading_number']			=	$OS_Details->bill_of_lading_number;
							$insert[1]['quantity_per_bill_of_lading']	=	$OS_Details->quantity_per_bill_of_lading;
							$insert[1]['weight_per_bill_of_lading']		=	$OS_Details->weight_per_bill_of_lading;
							$insert[1]['shipper']						=	$OS_Details->shipper;
							$insert[1]['consignee']						=	$OS_Details->consignee;
							$insert[1]['surveyor']						=	$OS_Details->surveyor;
							$insert[1]['arrastre']						=	$OS_Details->arrastre;
							$insert[1]['vessel_id']						=	$OS_Details->vessel_id;
							$insert[1]['mother_vessel']					=	$OS_Details->mother_vessel;
							$insert[1]['voyage_number']					=	$OS_Details->voyage_number;
							$insert[1]['port_of_origin']				=	$OS_Details->port_of_origin;
							$insert[1]['port_of_destination']			=	$OS_Details->port_of_destination;
							$insert[1]['loading_arrival']				=	$OS_Details->loading_arrival;
							$insert[1]['loading_start']					=	$OS_Details->loading_start;
							$insert[1]['loading_ended']					=	$OS_Details->loading_ended;
							$insert[1]['loading_departure']				=	$OS_Details->loading_departure;
							$insert[1]['loading_quantity_volume']		=	$OS_Details->loading_quantity_volume;
							$insert[1]['unloading_arrival']				=	$OS_Details->unloading_arrival;
							$insert[1]['unloading_start']				=	$OS_Details->unloading_start;
							$insert[1]['unloading_ended']				=	$OS_Details->unloading_ended;
							$insert[1]['unloading_departure']			=	$OS_Details->unloading_departure;
							$insert[1]['unloading_quantity_volume']		=	$OS_Details->unloading_quantity_volume;
							$checkInsertDetails	=	$this->Mmm->dbInsert('ops_out_turn_summary_details',$insert[1],'Added details on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
							if($checkInsertDetails) {
								if(isset($OS_Attachments)) {
									for($x=0;$x<count($OS_Attachments);$x++) {
										$multiInsertAttach[$x]['out_turn_summary_id']	=	$last_id_inserted;
										$multiInsertAttach[$x]['document_name']			=	$this->Mmm->sanitize($OS_Attachments[$x]['document_name']);
									}
									$checkInsertAttach	=	$this->Mmm->multiInsert('ops_out_turn_summary_attachments',$multiInsertAttach,'Added atachments on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
								}
								else{
									//ok to proceed if no attachments
									$checkInsertAttach	=	true;
								}
							}
							else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
								$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
								die();
							}
							if($checkInsertAttach) {
								if(isset($OS_Output)) {
									$insert[2]['out_turn_summary_id']			=	$last_id_inserted;
									$insert[2]['shipper_number_of_bags']		=	$OS_Output->shipper_number_of_bags;
									$insert[2]['shipper_weight']				=	$OS_Output->shipper_weight;
									$insert[2]['consignee_number_of_bags']		=	$OS_Output->consignee_number_of_bags;
									$insert[2]['consignee_weight']				=	$OS_Output->consignee_weight;
									$insert[2]['variance_number_of_bags']		=	$OS_Output->variance_number_of_bags;
									$insert[2]['variance_weight']				=	$OS_Output->variance_weight;
									$insert[2]['percentage_number_of_bags']		=	$OS_Output->percentage_number_of_bags;
									$insert[2]['percentage_weight']				=	$OS_Output->percentage_weight;
									$insert[2]['good_number_of_bags']			=	$OS_Output->good_number_of_bags;
									$insert[2]['damaged_number_of_bags']		=	$OS_Output->damaged_number_of_bags;
									$insert[2]['total_number_of_bags']			=	$OS_Output->total_number_of_bags;
								}
								$checkInsertFinal	=	$this->Mmm->dbInsert("ops_out_turn_summary_output",$insert[2],"Added Final Output and Variance on Out-Turn Summary with Control No." . $control_number . " under " . $company_name);
							}
							else{
								$this->Abas->sysMsg("errmsg", "An error has occurred while saving the attachments for the Out-Turn Summary! Please try again.");
								$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
								die();
							}
						}
						elseif($checkInsert && $service_type =='Trucking' || $checkInsert && $service_type	=='Handling') {
							$OS_Deliveries	=	$this->Operation_model->getOutTurnSummaryDeliveries($_POST['out_turn_summary']);
							$ctr	=	0;
							foreach($OS_Deliveries as $row) {
								$XmultiInsert[$ctr]['sorting']							=	$row['sorting'];
								$XmultiInsert[$ctr]['out_turn_summary_id']				=	$last_id_inserted;
								$XmultiInsert[$ctr]['delivery_date']					=	$row['delivery_date'];
								$XmultiInsert[$ctr]['trucking_company']					=	$row['trucking_company'];
								$XmultiInsert[$ctr]['truck_plate_number']				=	$row['truck_plate_number'];
								$XmultiInsert[$ctr]['truck_driver']						=	$row['truck_driver'];
								$XmultiInsert[$ctr]['warehouse']						=	$row['warehouse'];
								$XmultiInsert[$ctr]['quantity']							=	$row['quantity'];
								$XmultiInsert[$ctr]['gross_weight']						=	$row['gross_weight'];
								$XmultiInsert[$ctr]['tare_weight']						=	$row['tare_weight'];
								$XmultiInsert[$ctr]['net_weight']						=	$row['net_weight'];
								$XmultiInsert[$ctr]['number_of_moves']					=	$row['number_of_moves'];
								$XmultiInsert[$ctr]['variety_item']						=	$row['variety_item'];
								$XmultiInsert[$ctr]['transaction']						=	$row['transaction'];
								$XmultiInsert[$ctr]['delivery_receipt_number']			=	$row['delivery_receipt_number'];
								$XmultiInsert[$ctr]['weighing_ticket_number']			=	$row['weighing_ticket_number'];
								$XmultiInsert[$ctr]['way_bill_number']					=	$row['way_bill_number'];
								$XmultiInsert[$ctr]['authority_to_load_number']			=	$row['authority_to_load_number'];
								$XmultiInsert[$ctr]['cargo_receipt_number']				=	$row['cargo_receipt_number'];
								$XmultiInsert[$ctr]['others']							=	$row['others'];
								$XmultiInsert[$ctr]['warehouse_issuance_form_number']	=	$row['warehouse_issuance_form_number'];
								$XmultiInsert[$ctr]['warehouse_receipt_form_number']	=	$row['warehouse_receipt_form_number'];
								$ctr++;
							}
							$checkInsertDeliveries	=	$this->Mmm->multiInsert('ops_out_turn_summary_deliveries',$XmultiInsert,'Added deliveries on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
						}
						elseif($checkInsert && $service_type	=='Lighterage' || $checkInsert && $service_type	=='Time Charter' || $checkInsert && $service_type	=='Towing') {
							$OS_Details =	$this->Operation_model->getOutTurnSummaryDetails($_POST['out_turn_summary']);
							$insert[3]['out_turn_summary_id']			=	$last_id_inserted;
							$insert[3]['shipper']						=	$OS_Details->shipper;
							$insert[3]['consignee']						=	$OS_Details->consignee;
							$insert[3]['vessel_id']						=	$OS_Details->vessel_id;
							$insert[3]['mother_vessel']					=	$OS_Details->mother_vessel;
							$insert[3]['voyage_number']					=	$OS_Details->voyage_number;
							$insert[3]['port_of_origin']				=	$OS_Details->port_of_origin;
							$insert[3]['port_of_destination']			=	$OS_Details->port_of_destination;
							$insert[3]['loading_arrival']				=	$OS_Details->loading_arrival;
							$insert[3]['loading_start']					=	$OS_Details->loading_start;
							$insert[3]['loading_ended']					=	$OS_Details->loading_ended;
							$insert[3]['loading_departure']				=	$OS_Details->loading_departure;
							$insert[3]['loading_quantity_volume']		=	$OS_Details->loading_quantity_volume;
							$insert[3]['unloading_arrival']				=	$OS_Details->unloading_arrival;
							$insert[3]['unloading_start']				=	$OS_Details->unloading_start;
							$insert[3]['unloading_ended']				=	$OS_Details->unloading_ended;
							$insert[3]['unloading_departure']			=	$OS_Details->unloading_departure;
							$insert[3]['unloading_quantity_volume']		=	$OS_Details->unloading_quantity_volume;
							$insert[3]['lighterage_receipt_number']		=	$OS_Details->lighterage_receipt_number;
							$insert[3]['trip_ticket_number']			=	$OS_Details->trip_ticket_number;
							$insert[3]['statement_of_facts_number']		=	$OS_Details->statement_of_facts_number;
							$insert[3]['barge_patron']					=	$OS_Details->barge_patron;
							$insert[3]['loading_batch_weight']			=	$OS_Details->loading_batch_weight;
							$insert[3]['unloading_batch_weight']		=	$OS_Details->unloading_batch_weight;
							$checkInsertDetails	=	$this->Mmm->dbInsert('ops_out_turn_summary_details',$insert[3],'Added details on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
						}
					}
					if($checkInsertDetails || $checkInsertFinal || $checkInsertDeliveries) {
						$this->Abas->sysNotif("Out-Turn Summary", $_SESSION['abas_login']['fullname']." has succesfully added Out-Turn Summary with Control No." . $control_number . " under " . $company_name,"Operations","info");
						$this->Abas->sysMsg("sucmsg","Added Out-Turn Summary with Control No." . $control_number . " under " . $company_name);
					}
					else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the details for the Out-Turn Summary! Please try again.");
						$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
						die();
					}
				}
				else{
					$this->Abas->sysMsg("errmsg", "Company not selected! Please try again.");
					$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
					die();
				}
				$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/listview");
			break;
			case "edit":
				$data['OS']	=	$this->Operation_model->getOutTurnSummary($id);
				if($data['OS']->type_of_service=="Shipping") {
					$data['OS_Details']		=	$this->Operation_model->getOutTurnSummaryDetails($id);
					$data['OS_Attachments']	=	$this->Operation_model->getOutTurnSummaryAttachments($id);
					$data['OS_Output']=	$this->Operation_model->getOutTurnSummaryOutput($id);
				}
				elseif($data['OS']->type_of_service=="Trucking" || $data['OS']->type_of_service=="Handling") {
					$data['OS_Deliveries']	=	$this->Operation_model->getOutTurnSummaryDeliveries($id);
				}
				elseif($data['OS']->type_of_service=="Lighterage" || $data['OS']->type_of_service=="Time Charter" || $data['OS']->type_of_service=="Towing") {
					$data['OS_Details']		=	$this->Operation_model->getOutTurnSummaryDetails($id);

					if($data['OS']->type_of_service=="Time Charter"){
						$sql = "SELECT id FROM statement_of_accounts WHERE out_turn_summary_id=".$id;
						$query = $this->db->query($sql);
						if($query){
							$result = $query->row();
							$data['OS']->soa_id = $result->id;
						}
					}
					
				}
				if($data['OS']->service_order_id!=0) {
					$data['SO']				=	$this->Operation_model->getServiceOrder($data['OS']->service_order_id);
					$data['SO_Details']		=	$this->Operation_model->getServiceOrderDetail($data['SO']->type,$data['OS']->service_order_id);
					$data['contract']		=	$this->Abas->getContract($data['SO']->service_contract_id);
				}
				else{
					$data['contract']		=	$this->Abas->getContract($data['OS']->service_contract_id);
				}
				$data['contracts']			=	$this->Abas->getContracts();
				$data['companies']			=	$this->Abas->getCompanies();
				$this->load->view('operation/out_turn_summary/form.php',$data);
			break;
			case "update":
				if(isset($_POST['company'])) {
					$OS						=	$this->Operation_model->getOutTurnSummary($id);
					$update	=	array();
					$update[0]['created_by']					=	$_SESSION['abas_login']['userid'];
					$update[0]['created_on']					=	date("Y-m-d H:i:s");
					$update[0]['remarks']						=	$this->Mmm->sanitize($_POST['remarks']);
					$update[0]['stat']							=	1;
					$update[0]['status']						=	"Draft";
					$update[0]['comments']						=	NULL;
					if(isset($_POST['reference_no_OS'])) {
						$update[0]['service_contract_id']		=	$this->Mmm->sanitize($_POST['reference_no_OS']);
					}
					$control_number	=	$OS->control_number;
					$company_name	=	$this->Abas->getCompany($OS->company_id)->name;
					$service_type	=	$OS->type_of_service;
					$checkUpdate	=	$this->Mmm->dbUpdate('ops_out_turn_summary',$update[0],$id,'Edited Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
					if($checkUpdate && $service_type	=='Shipping') {
						$this->db->query("DELETE FROM ops_out_turn_summary_details WHERE out_turn_summary_id=".$id);
						$insert[1]['out_turn_summary_id']			=	$id;
						$insert[1]['bill_of_lading_number']			=	isset($_POST['bol_number'])?$this->Mmm->sanitize($_POST['bol_number']):NULL;
						$insert[1]['quantity_per_bill_of_lading']	=	isset($_POST['qty_bol'])?$this->Mmm->sanitize($_POST['qty_bol']):NULL;
						$insert[1]['weight_per_bill_of_lading']		=	isset($_POST['weight_bol'])?$this->Mmm->sanitize($_POST['weight_bol']):NULL;
						$insert[1]['shipper']						=	isset($_POST['shipper'])?$this->Mmm->sanitize($_POST['shipper']):NULL;
						$insert[1]['consignee']						=	isset($_POST['consignee'])?$this->Mmm->sanitize($_POST['consignee']):NULL;
						$insert[1]['surveyor']						=	isset($_POST['surveyor'])?$this->Mmm->sanitize($_POST['surveyor']):NULL;
						$insert[1]['arrastre']						=	isset($_POST['arrastre'])?$this->Mmm->sanitize($_POST['arrastre']):NULL;
						$insert[1]['vessel_id']						=	isset($_POST['vessel_id'])?$this->Mmm->sanitize($_POST['vessel_id']):NULL;
						$insert[1]['mother_vessel']					=	isset($_POST['mother_vessel'])?$this->Mmm->sanitize($_POST['mother_vessel']):NULL;
						$insert[1]['voyage_number']					=	isset($_POST['voyage_number'])?$this->Mmm->sanitize($_POST['voyage_number']):NULL;
						$insert[1]['port_of_origin']				=	isset($_POST['port_origin'])?$this->Mmm->sanitize($_POST['port_origin']):NULL;
						$insert[1]['port_of_destination']			=	isset($_POST['port_destination'])?$this->Mmm->sanitize($_POST['port_destination']):NULL;
						$insert[1]['loading_arrival']				=	$this->Mmm->sanitize($_POST['loading_arrival_date']);
						$insert[1]['loading_start']					=	$this->Mmm->sanitize($_POST['loading_start_datetime']);
						$insert[1]['loading_ended']					=	$this->Mmm->sanitize($_POST['loading_ended_datetime']);
						$insert[1]['loading_departure']				=	$this->Mmm->sanitize($_POST['loading_departure_date']);
						$insert[1]['loading_quantity_volume']		=	$this->Mmm->sanitize($_POST['loading_quantity_volume']);
						$insert[1]['unloading_arrival']				=	$this->Mmm->sanitize($_POST['unloading_arrival_date']);
						$insert[1]['unloading_start']				=	$this->Mmm->sanitize($_POST['unloading_start_datetime']);
						$insert[1]['unloading_ended']				=	$this->Mmm->sanitize($_POST['unloading_ended_datetime']);
						$insert[1]['unloading_departure']			=	$this->Mmm->sanitize($_POST['unloading_departure_date']);
						$insert[1]['unloading_quantity_volume']		=	$this->Mmm->sanitize($_POST['unloading_quantity_volume']);
						$checkInsertDetails	=	$this->Mmm->dbInsert('ops_out_turn_summary_details',$insert[1],'Added details on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
						if($checkInsertDetails) {
							$this->db->query("DELETE FROM ops_out_turn_summary_attachments WHERE out_turn_summary_id=".$id);
							if(isset($_POST['attachments'])) {
								foreach($_POST['attachments'] as $ctr=>$val) {
									$multiInsertAttach[$ctr]['out_turn_summary_id']			=	$id;
									$multiInsertAttach[$ctr]['document_name']				=	$this->Mmm->sanitize($_POST['attachments'][$ctr]);
								}
								$checkInsertAttach	=	$this->Mmm->multiInsert('ops_out_turn_summary_attachments',$multiInsertAttach,'Added atachments on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
							}
							else{
								//ok to proceed if no attachements
								$checkInsertAttach	=	true;
							}
						}
						else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
							$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
							die();
						}
						if($checkInsertAttach) {
							$this->db->query("DELETE FROM ops_out_turn_summary_output WHERE out_turn_summary_id=".$id);
							if(isset($_POST['shipper_number_of_bags'])) {
								$insert[2]['out_turn_summary_id']			=	$id;
								$insert[2]['shipper_number_of_bags']		=	$this->Mmm->sanitize($_POST['shipper_number_of_bags']);
								$insert[2]['shipper_weight']				=	$this->Mmm->sanitize($_POST['shipper_weight']);
								$insert[2]['consignee_number_of_bags']		=	$this->Mmm->sanitize($_POST['consignee_number_of_bags']);
								$insert[2]['consignee_weight']				=	$this->Mmm->sanitize($_POST['consignee_weight']);
								$insert[2]['variance_number_of_bags']		=	$this->Mmm->sanitize($_POST['variance_number_of_bags']);
								$insert[2]['variance_weight']				=	$this->Mmm->sanitize($_POST['variance_weight']);
								$insert[2]['percentage_number_of_bags']		=	$this->Mmm->sanitize($_POST['percentage_number_of_bags']);
								$insert[2]['percentage_weight']				=	$this->Mmm->sanitize($_POST['percentage_weight']);
								$insert[2]['good_number_of_bags']			=	$this->Mmm->sanitize($_POST['good_number_of_bags']);
								$insert[2]['damaged_number_of_bags']		=	$this->Mmm->sanitize($_POST['damaged_number_of_bags']);
								$insert[2]['total_number_of_bags']			=	$this->Mmm->sanitize($_POST['total_number_of_bags']);
							}
							$checkInsertFinal	=	$this->Mmm->dbInsert("ops_out_turn_summary_output",$insert[2],"Added Final Output and Variance on Out-Turn Summary with Control No." . $control_number . " under " . $company_name);
						}
						else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
							$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
							die();
						}
					}
					elseif($checkUpdate && $service_type =='Trucking' || $checkUpdate && $service_type	=='Handling') {
						$this->db->query("DELETE FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$id);
						if(isset($_POST['sorting'])) {
							foreach($_POST['sorting'] as $ctr=>$val) {
								$multiInsert[$ctr]['sorting']							=	$this->Mmm->sanitize($_POST['sorting'][$ctr]);
								$multiInsert[$ctr]['out_turn_summary_id']				=	$id;
								$multiInsert[$ctr]['delivery_date']						=	$this->Mmm->sanitize($_POST['delivery_date'][$ctr]);
								$multiInsert[$ctr]['trucking_company']					=	isset($_POST['trucking_company'][$ctr])?$this->Mmm->sanitize($_POST['trucking_company'][$ctr]):NULL;
								$multiInsert[$ctr]['truck_plate_number']				=	isset($_POST['truck_plate_number'][$ctr])?$this->Mmm->sanitize($_POST['truck_plate_number'][$ctr]):NULL;
								$multiInsert[$ctr]['truck_driver']						=	isset($_POST['truck_driver'][$ctr])?$this->Mmm->sanitize($_POST['truck_driver'][$ctr]):NULL;
								$multiInsert[$ctr]['warehouse']							=	$this->Mmm->sanitize($_POST['warehouse'][$ctr]);
								$multiInsert[$ctr]['quantity']							=	$this->Mmm->sanitize($_POST['quantity'][$ctr]);
								$multiInsert[$ctr]['gross_weight']						=	$this->Mmm->sanitize($_POST['gross_weight'][$ctr]);
								$multiInsert[$ctr]['tare_weight']						=	isset($_POST['tare_weight'][$ctr])?$this->Mmm->sanitize($_POST['tare_weight'][$ctr]):NULL;
								$multiInsert[$ctr]['net_weight']						=	isset($_POST['net_weight'][$ctr])?$this->Mmm->sanitize($_POST['net_weight'][$ctr]):NULL;
								$multiInsert[$ctr]['number_of_moves']					=	isset($_POST['number_of_moves'][$ctr])?$this->Mmm->sanitize($_POST['number_of_moves'][$ctr]):NULL;
								$multiInsert[$ctr]['variety_item']						=	isset($_POST['variety_item'][$ctr])?$this->Mmm->sanitize($_POST['variety_item'][$ctr]):NULL;
								$multiInsert[$ctr]['transaction']						=	isset($_POST['transaction'][$ctr])?$this->Mmm->sanitize($_POST['transaction'][$ctr]):NULL;
								$multiInsert[$ctr]['delivery_receipt_number']			=	isset($_POST['dr_number'][$ctr])?$this->Mmm->sanitize($_POST['dr_number'][$ctr]):NULL;
								$multiInsert[$ctr]['weighing_ticket_number']			=	isset($_POST['wt_number'][$ctr])?$this->Mmm->sanitize($_POST['wt_number'][$ctr]):NULL;
								$multiInsert[$ctr]['way_bill_number']					=	isset($_POST['wb_number'][$ctr])?$this->Mmm->sanitize($_POST['wb_number'][$ctr]):NULL;
								$multiInsert[$ctr]['authority_to_load_number']			=	isset($_POST['atl_number'][$ctr])?$this->Mmm->sanitize($_POST['atl_number'][$ctr]):NULL;
								$multiInsert[$ctr]['cargo_receipt_number']				=	isset($_POST['cr_number'][$ctr])?$this->Mmm->sanitize($_POST['cr_number'][$ctr]):NULL;
								$multiInsert[$ctr]['others']							=	isset($_POST['others'][$ctr])?$this->Mmm->sanitize($_POST['others'][$ctr]):NULL;
								$multiInsert[$ctr]['warehouse_issuance_form_number']	=	isset($_POST['wif_number'][$ctr])?$this->Mmm->sanitize($_POST['wif_number'][$ctr]):NULL;
								$multiInsert[$ctr]['warehouse_receipt_form_number']		=	isset($_POST['wrf_number'][$ctr])?$this->Mmm->sanitize($_POST['wrf_number'][$ctr]):NULL;
							}
							$checkInsertDeliveries	=	$this->Mmm->multiInsert('ops_out_turn_summary_deliveries',$multiInsert,'Added deliveries on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
						}
						elseif($_FILES["uploaded_file"]["name"]!=NULL) {
							$target_dir		=	WPATH.'assets/uploads/operations/out_turn_summary/';
							$old_filename	=	explode(".", basename($_FILES["uploaded_file"]["name"]));
							$new_filename	=	round(microtime(true)) . '.' . end($old_filename);
							$target_file	=	$target_dir . $new_filename;
							$uploaded		=	move_uploaded_file($_FILES["uploaded_file"]["tmp_name"],$target_file);//import the file on assets/upload
							/*$config['upload_path'] = $target_dir;
							$config['allowed_types'] = '*';
							$config['file_name'] = $new_filename;
							$this->load->library('upload', $config);
							if (!$this->upload->do_upload('uploaded_file')) {
								$error = array('error' => $this->upload->display_errors());
								$_SESSION['warnmsg'] = $error['error'];
								$uploaded = FALSE;
							}
							else {
								$uploaded = TRUE;
							}*/
							//check if uploaded on server
							if($uploaded) {
								//Check if succesfully imported on db
								$imported	=	$this->import_out_turn_deliveries($service_type,$new_filename,$id);
								if($imported) {
									$checkInsertDeliveries	=	TRUE;
								}
								else{
									$checkInsertDeliveries=	FALSE;
									$this->Abas->sysNotif("Out-Turn Summary", "Out-turn Summary file was not successfully imported, please check if the file you are uploading contains no error and try again!","Operations","danger");
									$this->Abas->sysMsg("errmsg", "Out-turn Summary file was not successfully imported, please check if the file you are uploading contains no error and try again!");
									$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
									die();
								}
							}
							else{
								$checkInsertDeliveries=	FALSE;
								$this->Abas->sysNotif("Out-Turn Summary", "There was an error occured while uploading the Out-turn Summary File, please contact your administrator.!","Operations","danger");
								$this->Abas->sysMsg("errmsg", "There was an error occured while uploading the Out-turn Summary File, please contact your administrator.!");
								$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
								die();
							}
						}
					}
					elseif($checkUpdate && $service_type=='Lighterage' || $checkUpdate && $service_type=='Time Charter' || $checkUpdate && $service_type=='Towing') {
						$this->db->query("DELETE FROM ops_out_turn_summary_details WHERE out_turn_summary_id=".$id);
						$insert[3]['out_turn_summary_id']			=	$id;
						$insert[3]['shipper']						=	isset($_POST['shipper'])?$this->Mmm->sanitize($_POST['shipper']):NULL;
						$insert[3]['consignee']						=	isset($_POST['consignee'])?$this->Mmm->sanitize($_POST['consignee']):NULL;
						$insert[3]['vessel_id']						=	isset($_POST['vessel_id'])?$this->Mmm->sanitize($_POST['vessel_id']):NULL;
						$insert[3]['mother_vessel']					=	isset($_POST['mother_vessel'])?$this->Mmm->sanitize($_POST['mother_vessel']):NULL;
						$insert[3]['voyage_number']					=	isset($_POST['voyage_number'])?$this->Mmm->sanitize($_POST['voyage_number']):NULL;
						$insert[3]['port_of_origin']				=	isset($_POST['port_origin'])?$this->Mmm->sanitize($_POST['port_origin']):NULL;
						$insert[3]['port_of_destination']			=	isset($_POST['port_destination'])?$this->Mmm->sanitize($_POST['port_destination']):NULL;
						$insert[3]['loading_arrival']				=	$this->Mmm->sanitize($_POST['loading_arrival_date']);
						$insert[3]['loading_start']					=	$this->Mmm->sanitize($_POST['loading_start_datetime']);
						$insert[3]['loading_ended']					=	$this->Mmm->sanitize($_POST['loading_ended_datetime']);
						$insert[3]['loading_departure']				=	$this->Mmm->sanitize($_POST['loading_departure_date']);
						$insert[3]['loading_quantity_volume']		=	$this->Mmm->sanitize($_POST['loading_quantity_volume']);
						$insert[3]['unloading_arrival']				=	$this->Mmm->sanitize($_POST['unloading_arrival_date']);
						$insert[3]['unloading_start']				=	$this->Mmm->sanitize($_POST['unloading_start_datetime']);
						$insert[3]['unloading_ended']				=	$this->Mmm->sanitize($_POST['unloading_ended_datetime']);
						$insert[3]['unloading_departure']			=	$this->Mmm->sanitize($_POST['unloading_departure_date']);
						$insert[3]['unloading_quantity_volume']		=	$this->Mmm->sanitize($_POST['unloading_quantity_volume']);
						$insert[3]['lighterage_receipt_number']		=	$this->Mmm->sanitize($_POST['lighterage_receipt_no']);
						$insert[3]['trip_ticket_number']			=	$this->Mmm->sanitize($_POST['trip_ticket_no']);
						$insert[3]['statement_of_facts_number']		=	$this->Mmm->sanitize($_POST['statement_of_facts_no']);
						$insert[3]['barge_patron']					=	$this->Mmm->sanitize($_POST['barge_patron']);
						$insert[3]['loading_batch_weight']			=	$this->Mmm->sanitize($_POST['loading_batch_weight']);
						$insert[3]['unloading_batch_weight']		=	$this->Mmm->sanitize($_POST['unloading_batch_weight']);
						$checkInsertDetails	=	$this->Mmm->dbInsert('ops_out_turn_summary_details',$insert[3],'Added details on Out-Turn Summary with Control No.' . $control_number . ' under ' . $company_name);
					}
					else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
						$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
						die();
					}
					if($checkInsertDetails || $checkInsertFinal || $checkInsertDeliveries) {
						$this->Abas->sysNotif("Out-Turn Summary", $_SESSION['abas_login']['fullname']." has succesfully edited Out-Turn Summary with Control No." . $control_number . " under " . $company_name,"Operations","info");
						$this->Abas->sysMsg("sucmsg","Edited Out-Turn Summary with Control No." . $control_number . " under " . $company_name);
					}
					else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
						$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
						die();
					}
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Out-Turn Summary! Please try again.");
					$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
					die();
				}
				$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
			break;
			case "submit":
				$this->updateOutTurnSummaryStatus("For Verification",$id);
				$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
			break;
			case "verify":
				$this->updateOutTurnSummaryStatus("For Approval",$id);
				$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
			break;
			case "approve":
				$this->updateOutTurnSummaryStatus("Approved",$id);
				$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
			break;
			case "return":
				$this->updateOutTurnSummaryStatus("Draft",$id);
				$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
			break;
			case "cancel":
				$this->updateOutTurnSummaryStatus("Cancelled",$id);
				$this->Abas->redirect(HTTP_PATH."operation/out_turn_summary/view/".$id);
			break;
			case "print":
				require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
				$data['OS']					=	$this->Operation_model->getOutTurnSummary($id);
				if($data['OS']->type_of_service=="Shipping") {
					$data['OS_Details']		=	$this->Operation_model->getOutTurnSummaryDetails($id);
					$data['OS_Attachments']	=	$this->Operation_model->getOutTurnSummaryAttachments($id);
					$data['OS_Output']		=	$this->Operation_model->getOutTurnSummaryOutput($id);
				}
				elseif($data['OS']->type_of_service=="Trucking" || $data['OS']->type_of_service=="Handling") {
					$data['OS_Deliveries']	=	$this->Operation_model->getOutTurnSummaryDeliveries($id);
				}
				elseif($data['OS']->type_of_service=="Lighterage" || $data['OS']->type_of_service=="Time Charter" || $data['OS']->type_of_service=="Towing") {
					$data['OS_Details']		=	$this->Operation_model->getOutTurnSummaryDetails($id);
					if($data['OS']->service_order_id!=0) {
						$data['SO']			=	$this->Operation_model->getServiceOrder($data['OS']->service_order_id);
						$data['SO_Details']	=	$this->Operation_model->getServiceOrderDetail($data['SO']->type,$data['OS']->service_order_id);
						$data['contract']	=	$this->Abas->getContract($data['SO']->service_contract_id);
					}
				}
				if($data['OS']->service_contract_id!=0) {
					$data['contract']		=	$this->Abas->getContract($data['OS']->service_contract_id);
				}else{
					$data['SO']				=	$this->Operation_model->getServiceOrder($data['OS']->service_order_id);
					$data['SO_Details']		=	$this->Operation_model->getServiceOrderDetail($data['SO']->type,$data['OS']->service_order_id);
					$data['contract']		=	$this->Abas->getContract($data['SO']->service_contract_id);
				}

				$data['preview'] =	false;
				$this->load->view("operation/out_turn_summary/print.php",$data);
			break;

			case "preview";
				require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
				$data['OS'] =	$this->Operation_model->getOutTurnSummary($id);
				if($data['OS']->type_of_service=="Shipping") {
					$data['OS_Details']			=	$this->Operation_model->getOutTurnSummaryDetails($id);
					$data['OS_Attachments']		=	$this->Operation_model->getOutTurnSummaryAttachments($id);
					$data['OS_Output']			=	$this->Operation_model->getOutTurnSummaryOutput($id);
				}
				elseif($data['OS']->type_of_service=="Trucking" || $data['OS']->type_of_service=="Handling") {
					$data['OS_Deliveries']		=	$this->Operation_model->getOutTurnSummaryDeliveries($id);
				}
				elseif($data['OS']->type_of_service=="Lighterage" || $data['OS']->type_of_service=="Time Charter" || $data['OS']->type_of_service=="Towing") {
					$data['OS_Details']			=	$this->Operation_model->getOutTurnSummaryDetails($id);
				}
				if($data['OS']->service_contract_id!=0) {
					$data['contract']			=	$this->Abas->getContract($data['OS']->service_contract_id);
				}
				else{
					$data['SO']					=	$this->Operation_model->getServiceOrder($data['OS']->service_order_id);
					$data['SO_Details']			=	$this->Operation_model->getServiceOrderDetail($data['SO']->type,$data['OS']->service_order_id);
					$data['contract']			=	$this->Abas->getContract($data['SO']->service_contract_id);
				}
				$data['preview'] =	true;
				$this->load->view("operation/out_turn_summary/print.php",$data);
			break;
		}
	}
	public function setOutTurnSummaryComments($out_turn_summary_id) {
		if(isset($_POST['comments'])) {
			$update['comments']	=	$this->Mmm->sanitize($_POST['comments']);
			$this->Mmm->dbUpdate('ops_out_turn_summary',$update,$out_turn_summary_id,'Added comments on Out-Turn Summary with Transaction No. '.$service_order_id);
		}
	}
	private function updateOutTurnSummaryStatus($status,$out_turn_summary_id) {
		$out_turn_summary	=	$this->Operation_model->getOutTurnSummary($out_turn_summary_id);
		$control_number		=	$out_turn_summary->control_number;
		$company_name		=	$this->Abas->getCompany($out_turn_summary->company_id)->name;
		$contract			=	$this->Abas->getContract($out_turn_summary->service_contract_id);
		if($status=="Draft") {
			$action								=	"returned";
			$return								=	$out_turn_summary->times_returned_to_draft;
			$update['times_returned_to_draft']	=	$return+1;
		}
		elseif($status=="For Verification") {
			$action	=	"submitted for Verification";
			$update['submitted_by']	=	$_SESSION['abas_login']['userid'];
			$update['submitted_on']	=	date("Y-m-d H:i:s");
		}
		elseif($status=="For Approval") {
			$action					=	"submitted for Approval";
			$update['verified_by']	=	$_SESSION['abas_login']['userid'];
			$update['verified_on']	=	date("Y-m-d H:i:s");
		}
		elseif($status=="Approved") {
			$update['approved_by']	=	$_SESSION['abas_login']['userid'];
			$update['approved_on']	=	date("Y-m-d H:i:s");
		}
		else{
			$action	=	$status;
		}
		$update['status']	=	$status;
		$checkUpdate		=	$this->Mmm->dbUpdate('ops_out_turn_summary',$update,$out_turn_summary_id,'Updated status of Out-Turn Summary with Transaction Code No.' . $id . " to '".$status."'");
		if($checkUpdate) {
			$this->Abas->sysNotif("Out-Turn Summary", $_SESSION['abas_login']['fullname']." has ".strtolower($action)." Out-Turn Summary with Control No." . $control_number . " under " . $company_name,"Operations","info");
			$this->Abas->sysMsg("sucmsg", ucwords($action)." Out-Turn Summary with Control No." . $control_number . " under " . $company_name);
		}
		else{
			$this->Abas->sysMsg("errmsg", "An error has occurred while updating the status of Out-Turn Summary! Please try again.");
		}
	}
	public function get_contract_details($contract_id) {
		$data['contract']		=	$this->Abas->getContract($contract_id);
		echo json_encode( $data['contract'] );
	}
	public function get_contracts_by_company($company_id) {
		$data['contracts']	=	$this->Operation_model->getContractsByCompany($company_id);
		echo json_encode( $data['contracts'] );
	}
	public function get_vessels_by_company($company_id) {
		$data['vessels']		=	$this->Abas->getVesselsByCompany($company_id);
		echo json_encode( $data['vessels'] );
	}
	public function get_trucks_by_company($company_id) {
		$data['trucks']		=	$this->Abas->getTrucksByCompany($company_id);
		echo json_encode( $data['trucks'] );
	}
	public function get_service_orders_by_company($company_id) {
		$data['service_orders']	=	$this->Operation_model->getServiceOrdersByCompany($company_id);
		echo json_encode( $data['service_orders'] );
	}
	public function get_out_turn_summaries() {
		$data['out_turn_summaries']	=	$this->Operation_model->getOutTurnSummaries();
		echo json_encode( $data['out_turn_summaries'] );
	}
	public function get_out_turn_summary($out_turn_id) {
		$data['out_turn_summary']	=	$this->Operation_model->getOutTurnSummary($out_turn_id);
		echo json_encode( $data['out_turn_summary'] );
	}
	public function get_service_order($service_order_id) {
		$data['service_order']	=	$this->Operation_model->getServiceOrder($service_order_id);
		echo json_encode( $data['service_order'] );
	}
	public function set_comments($id) {
		$comments	=	$_POST['comments'];
		$data['commented']	=	$this->Operation_model->setOSComments($id,$comments);
	}
	public function check_contract_reference_no($contract_reference_no,$id) {
		$data['is_contract_exist']	=	$this->Operation_model->checkContractReferenceNo($contract_reference_no,$id);
		echo json_encode( $data['is_contract_exist'] );
	}
	public function import_out_turn_deliveries($type,$file,$out_turn_summary_id) {
		require_once WPATH.'assets/phpexcel/Classes/PHPExcel/IOFactory.php';
		$inputFileName=	WPATH.'/assets/uploads/operations/out_turn_summary/' . $file;
		//	Read Excel file
		try{
			$inputFileType=PHPExcel_IOFactory::identify($inputFileName);
			$objReader=PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel=$objReader->load($inputFileName);
		}
		catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			unlink($inputFileName);
		}
		//	Get worksheet dimensions
		if($type=="Trucking") {
			$sheet=$objPHPExcel->getSheet(0);	// select sheet Trucking
		}
		elseif($type=="Handling") {
			$sheet=$objPHPExcel->getSheet(1); // select sheet Handling
		}
		$headers=$objPHPExcel->getActiveSheet()->toArray("A5");
		$highestRow=$sheet->getHighestRow();
		$highestColumn=$sheet->getHighestColumn();
		//$highestColumnIndex	=	PHPExcel_Cell::columnIndexFromString($highestColumn);
		// Check if the correct template is being imported
		$correct_file	=	$sheet->getCell('A1')->getValue();
		//	Loop through each row of the worksheet in turn and insert it on db
		$multiInsert	=	array();
		$ctr=0;
		$start_row=3;
		$rowData=$sheet->rangeToArray("A" . $start_row . ":" . $highestColumn . $highestRow ,null,true,false);
		if($correct_file=="X12AgUpNNm32_89001") {//if trucking
			foreach($rowData as $ctr=>$val) {
				$multiInsert[$ctr]['sorting']							=	$ctr+1;
				$multiInsert[$ctr]['out_turn_summary_id']				=	$out_turn_summary_id;
				$multiInsert[$ctr]['delivery_date']						=	gmdate("Y-m-d", ($rowData[$ctr][1]	- 25569) * 86400);
				$multiInsert[$ctr]['truck_plate_number']				=	trim($rowData[$ctr][2]);
				$multiInsert[$ctr]['truck_driver']						=	trim($rowData[$ctr][3]);
				$multiInsert[$ctr]['trucking_company']					=	trim($rowData[$ctr][4]);
				$multiInsert[$ctr]['warehouse']							=	trim($rowData[$ctr][5]);
				$multiInsert[$ctr]['variety_item']						=	trim($rowData[$ctr][6]);
				$multiInsert[$ctr]['quantity']							=	$rowData[$ctr][7];
				$multiInsert[$ctr]['gross_weight']						=	$rowData[$ctr][8];
				$multiInsert[$ctr]['tare_weight']						=	$rowData[$ctr][9];
				$multiInsert[$ctr]['net_weight']						=	$rowData[$ctr][10];
				$multiInsert[$ctr]['transaction']						=	trim($rowData[$ctr][11]);
				$multiInsert[$ctr]['delivery_receipt_number']			=	$rowData[$ctr][12];
				$multiInsert[$ctr]['weighing_ticket_number']			=	$rowData[$ctr][13];
				$multiInsert[$ctr]['warehouse_issuance_form_number']	=	$rowData[$ctr][14];
				$multiInsert[$ctr]['warehouse_receipt_form_number']		=	$rowData[$ctr][15];
				$multiInsert[$ctr]['way_bill_number']					=	$rowData[$ctr][16];
				$multiInsert[$ctr]['authority_to_load_number']			=	$rowData[$ctr][17];
				$multiInsert[$ctr]['cargo_receipt_number']				=	$rowData[$ctr][18];
				$multiInsert[$ctr]['others']							=	$rowData[$ctr][19];
				$ctr++;
			}
			$this->Mmm->multiInsert("ops_out_turn_summary_deliveries",$multiInsert,"Imported Breakdown of Deliveries for Trucking.");
			unlink($inputFileName);
			return true;
		}
		elseif($correct_file=="We67002_3PKc451") {//if handling
			foreach($rowData as $ctr=>$val) {
				$multiInsert[$ctr]['sorting']							=	$ctr+1;
				$multiInsert[$ctr]['out_turn_summary_id']				=	$out_turn_summary_id;
				$multiInsert[$ctr]['delivery_date']						=	gmdate("Y-m-d", ($rowData[$ctr][1]	- 25569) * 86400);
				$multiInsert[$ctr]['warehouse']							=	$rowData[$ctr][2];
				$multiInsert[$ctr]['quantity']							=	$rowData[$ctr][3];
				$multiInsert[$ctr]['gross_weight']						=	$rowData[$ctr][4];
				$multiInsert[$ctr]['number_of_moves']					=	$rowData[$ctr][5];
				$multiInsert[$ctr]['variety_item']						=	$rowData[$ctr][6];
				$multiInsert[$ctr]['transaction']						=	$rowData[$ctr][7];
				$multiInsert[$ctr]['warehouse_issuance_form_number']	=	$rowData[$ctr][8];
				$multiInsert[$ctr]['warehouse_receipt_form_number']		=	$rowData[$ctr][9];
				$multiInsert[$ctr]['others']							=	$rowData[$ctr][10];
				$ctr++;
			}
			//	Get worksheet dimensions
			if($type=="Trucking") {
				$sheet=$objPHPExcel->getSheet(0);	// select sheet Trucking
			}
			elseif($type=="Handling") {
				$sheet=$objPHPExcel->getSheet(1); // select sheet Handling
			}
			$headers=$objPHPExcel->getActiveSheet()->toArray("A5");
			$highestRow=$sheet->getHighestRow();
			$highestColumn=$sheet->getHighestColumn();
			//$highestColumnIndex =	PHPExcel_Cell::columnIndexFromString($highestColumn);
			// Check if the correct template is being imported
			$correct_file =	$sheet->getCell('A1')->getValue();
			//	Loop through each row of the worksheet in turn and insert it on db
			$multiInsert =	array();
			$ctr=0;
			$start_row=3;
			$rowData=$sheet->rangeToArray("A" . $start_row . ":" . $highestColumn . $highestRow ,null,true,false);
			if($correct_file=="X12AgUpNNm32_89001") {//if trucking
				foreach($rowData as $ctr=>$val) {
					$multiInsert[$ctr]['sorting']							=	$ctr+1;
					$multiInsert[$ctr]['out_turn_summary_id']				=	$out_turn_summary_id;
					$multiInsert[$ctr]['delivery_date']						=	gmdate("Y-m-d", ($rowData[$ctr][1]	- 25569) * 86400);
					$multiInsert[$ctr]['truck_plate_number']				=	$rowData[$ctr][2];
					$multiInsert[$ctr]['truck_driver']						=	$rowData[$ctr][3];
					$multiInsert[$ctr]['trucking_company']					=	$rowData[$ctr][4];
					$multiInsert[$ctr]['warehouse']							=	$rowData[$ctr][5];
					$multiInsert[$ctr]['variety_item']						=	$rowData[$ctr][6];
					$multiInsert[$ctr]['quantity']							=	$rowData[$ctr][7];
					$multiInsert[$ctr]['gross_weight']						=	$rowData[$ctr][8];
					$multiInsert[$ctr]['tare_weight']						=	$rowData[$ctr][9];
					$multiInsert[$ctr]['net_weight']						=	$rowData[$ctr][10];
					$multiInsert[$ctr]['transaction']						=	$rowData[$ctr][11];
					$multiInsert[$ctr]['delivery_receipt_number']			=	$rowData[$ctr][12];
					$multiInsert[$ctr]['weighing_ticket_number']			=	$rowData[$ctr][13];
					$multiInsert[$ctr]['warehouse_issuance_form_number']	=	$rowData[$ctr][14];
					$multiInsert[$ctr]['warehouse_receipt_form_number']		=	$rowData[$ctr][15];
					$multiInsert[$ctr]['way_bill_number']					=	$rowData[$ctr][16];
					$multiInsert[$ctr]['authority_to_load_number']			=	$rowData[$ctr][17];
					$multiInsert[$ctr]['cargo_receipt_number']				=	$rowData[$ctr][18];
					$multiInsert[$ctr]['others']							=	$rowData[$ctr][19];
					$ctr++;
				}
				$this->Mmm->multiInsert("ops_out_turn_summary_deliveries",$multiInsert,"Imported Breakdown of Deliveries for Trucking.");
				unlink($inputFileName);
				return true;
			}
			elseif($correct_file=="We67002_3PKc451") {//if handling
				foreach($rowData as $ctr=>$val) {
					$multiInsert[$ctr]['sorting']							=	$ctr+1;
					$multiInsert[$ctr]['out_turn_summary_id']				=	$out_turn_summary_id;
					$multiInsert[$ctr]['delivery_date']						=	gmdate("Y-m-d", ($rowData[$ctr][1] - 25569) * 86400);
					$multiInsert[$ctr]['warehouse']							=	$rowData[$ctr][2];
					$multiInsert[$ctr]['quantity']							=	$rowData[$ctr][3];
					$multiInsert[$ctr]['gross_weight']						=	$rowData[$ctr][4];
					$multiInsert[$ctr]['number_of_moves']					=	$rowData[$ctr][5];
					$multiInsert[$ctr]['variety_item']						=	$rowData[$ctr][6];
					$multiInsert[$ctr]['transaction']						=	$rowData[$ctr][7];
					$multiInsert[$ctr]['warehouse_issuance_form_number']	=	$rowData[$ctr][8];
					$multiInsert[$ctr]['warehouse_receipt_form_number']		=	$rowData[$ctr][9];
					$multiInsert[$ctr]['others']							=	$rowData[$ctr][10];
					$ctr++;
				}
				$this->Mmm->multiInsert("ops_out_turn_summary_deliveries",$multiInsert,"Imported Breakdown of Deliveries for Handling.");
				unlink($inputFileName);
				return true;
			}
			else{
				return false;
			}
		}
	}
	public function vessel_monitoring() {$data=array();
		$this->Abas->checkPermissions("operations|vessel_monitoring");
		$data['vessels']	=	$this->Operation_model->getVesselsByActivity(false);
		$data['viewfile']	=	"operation/ship_monitoring.php";
		$this->load->view('gentlella_container.php',$data);
	}
	public function tool_registry() {$data=array();
		if(empty($_POST)) {
			$get_offices			=	false;
			$data['vessels']		=	$this->Abas->getVessels($get_offices);
			$mobile_numbers_sql		=	"SELECT DISTINCT(data_source) FROM sms_reports WHERE location IS NOT NULL AND location NOT LIKE '%loc%' AND location NOT LIKE '%unknown%' ORDER BY data_source DESC";
			$mobile_numbers			=	$this->db->query($mobile_numbers_sql);
			$data['data_sources']	=	$mobile_numbers->result_array();
			$data['viewfile']		=	"operation/tool_registry_form.php";
			$this->load->view('operation/tool_registry_form.php',$data);
		}
		else {
			$check_vessel				=	$this->Abas->getVessel($_POST['vessel']);
			if(empty($check_vessel)) {
				$this->Abas->sysMsg("warnmsg", "That vessel does not exist! Please try again.");
				$this->Abas->redirect(HTTP_PATH."operation/vessel_monitoring");
			}
			$insert['vessel_id']		=	$check_vessel->id;
			$insert['contract_id']		=	0;
			$insert['stat']				=	1;
			$insert['reference_id']		=	0;
			$insert['type']				=	0;
			$insert['mobile_number']	=	$this->Mmm->sanitize($_POST['mobile_number']);
			$insert['issued_to']		=	$this->Mmm->sanitize($_POST['issued_to']);
			$insert['registered_on']	=	date("Y-m-d H:i:s");
			$insert['registered_by']	=	$_SESSION['abas_login']['userid'];
			$check	=	$this->Mmm->dbInsert("ops_report_tool_registry", $insert, "New reporting tool registered to ".$insert['issued_to']." for ".$check_vessel->name);
			if($check) {
				$this->Abas->sysMsg("sucmsg", "Registration Complete!");
			}
			else {
				$this->Abas->sysMsg("warnmsg", "Registration Failed!");
			}
			$this->Abas->redirect(HTTP_PATH."operation/vessel_monitoring");
		}
	}

	public function truck_profiles($action,$truck_id=''){
		
		switch ($action) {
			case 'load':
				if(isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])) {
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$data	=	$this->Abas->createBSTable("trucks",$search,$limit,$offset,$order,$sort);

					if($data!=false){
						foreach($data['rows'] as $ctr=>$trucks) {
							$data['rows'][$ctr]['company_name']		=	"";
							if(!empty($trucks['company'])) {
								$company							=	$this->Abas->getCompany($trucks['company']);
								$data['rows'][$ctr]['company_name']	=	$company->name;
							}
							if(!empty($trucks['created_by'])) {
								$created_by							=	$this->Abas->getUser($trucks['created_by']);
								$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
							}
							if(!empty($trucks['modified_by'])) {
								$modified_by						=	$this->Abas->getUser($trucks['modified_by']);
								$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
							}
							if(!empty($trucks['modified'])) {
								$data['rows'][$ctr]['modified']	=	date("j F Y H:i", strtotime($trucks['modified']));
							}
							if($trucks['stat']==1){
								$data['rows'][$ctr]['status']	= "Active";
							}else{
								$data['rows'][$ctr]['status']	= "Inactive";
							}
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				}
			break;

			case 'listview':
				$data['viewfile']	=	"operation/assets/trucks/listview.php";
				$this->load->view("gentlella_container.php",$data);
			break;

			case 'view':
				$data['truck'] = $this->Abas->getTruck($truck_id);
				$data['company'] = $this->Abas->getCompany($data['truck'][0]['company']);
				$this->load->view("operation/assets/trucks/view.php",$data);
			break;
		}
			
		
		
	}

	public function service_contract_autocomplete_list($status,$company_id=null){
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		if($company_id!=null){
			$sql	=	"SELECT * FROM service_contracts WHERE id LIKE '%".$search."%' AND company_id=".$company_id." AND stat=".$status." ORDER BY id LIMIT 0,12";	
		}else{
			$sql	=	"SELECT * FROM service_contracts WHERE id LIKE '%".$search."%' AND stat=".$status." ORDER BY id LIMIT 0,12";
		}
		$sc	=	$this->db->query($sql);
		if($sc) {
			if($sc->row()) {
				$sc	=	$sc->result_array();
				$ret	=	array();
				foreach($sc as $ctr=>$i) {
					$ret[$ctr]['label']	=	"Service Contract Transaction Code No. ".$i['id']." | Control No. ".$i['control_number']. " (Ref. No.".$i['reference_no']." - ".$i['type'].")";
					$ret[$ctr]['value']	=	$i['id'];
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
	}
	public function voyage_report($action){
		switch ($action) {
			case 'filter':
				$data['vessels'] = $this->Abas->getVessels(FALSE);
				$this->load->view("operation/reports/voyage_filter.php",$data);
			break;
			case 'result':
				$date_from = $this->Mmm->sanitize($_POST['date_from']);
				$date_to = $this->Mmm->sanitize($_POST['date_to']);
				$vessel = $this->Mmm->sanitize($_POST['vessel']);
				$data['result'] = $this->Operation_model->getVoyage($vessel,$date_from,$date_to);
				$data['date_from'] = $date_from;
				$data['date_to'] = $date_to;
				$data['vessel'] = $this->Abas->getVessel($vessel,false);
				$data['viewfile'] = "operation/reports/voyage_report.php";
				$this->load->view("gentlella_container.php",$data);
			break;
		}
	}
	public function out_turn_summary_aging_report($action){

		switch ($action) {
			case 'filter':
				$this->load->view('operation/reports/out_turn_summary_aging_filter.php');
			break;

			case 'result':
				
				$data = array();
				$start_date = $this->Mmm->sanitize($_POST['date_from']);
				$end_date = $this->Mmm->sanitize($_POST['date_to']);

	 			$data['date_from'] = $start_date;
	 			$data['date_to'] = $end_date;

				$data['viewfile'] = 'operation/reports/out_turn_summary_aging_report.php';
				$this->load->view('gentlella_container.php',$data);
			break;
		}

	}

}
?>
