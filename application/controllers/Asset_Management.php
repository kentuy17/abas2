<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_Management extends CI_Controller {

	public function __construct(){

		parent::__construct();

		date_default_timezone_set('Asia/Manila');
		session_start();

		//load libraries and helpers
		$this->load->helper('form');
		$this->load->helper('url_helper');
		$this->load->library('form_validation');

		//load models
		$this->load->model('Abas');
		$this->load->model('Mmm');
		$this->load->model("Asset_Management_model");
		$this->load->model("Inventory_model");
		$this->load->model("Purchasing_model");

		define('SIDEMENU','Asset Management'); // used by gentellela container for displaying links in sidemenu
		define('CONTROLLER','Asset_Management'); //set base controller path name
		define('VIEW','asset_management'); //set base base view path name

		//check if user is logged-in
		if(!isset($_SESSION['abas_login'])){ 
			$this->Abas->redirect(HTTP_PATH."home"); 
		}

	}

	
	public function index(){

		if($this->Abas->checkPermissions("asset_management|view_vessels",FALSE)){

			$for = "Vessel";

		}elseif($this->Abas->checkPermissions("asset_management|view_trucks",FALSE)){

			$for = "Truck";

		}else{
			$this->Abas->checkPermissions("asset_management",TRUE);
			$for = NULL;
		}		

		$this->listview('schedule_logs',$for);
	}

	
	public function add( $type = NULL, $for = NULL){

		$data = array();

		if(!empty($_POST)){//inserts data

			switch($type){

				case "WO":

					$control_number = $this->Abas->getNextSerialNumber('am_vessel_work_order',$_POST['company']);

					$insert = array();
					$insert['control_number']			=	$control_number;//$this->Mmm->sanitize($_POST['control_number']);
					$insert['company_id']				=	$this->Mmm->sanitize($_POST['company']);
					$insert['vessel_id']				=	$this->Mmm->sanitize($_POST['vessel']);
					$insert['location']					=	$this->Mmm->sanitize($_POST['location']);
					$insert['requisitioner']			=	$this->Mmm->sanitize($_POST['requisitioner']);
					$insert['designation']				=	$this->Mmm->sanitize($_POST['designation']);
					$insert['created_by']				=	$_SESSION['abas_login']['userid'];
					$insert['created_on']				=	date("Y-m-d H:i:s");
					$insert['status']					=	"Draft";

					$company_name = $this->Asset_Management_model->getCompany($this->Mmm->sanitize($_POST['company']));
					$vessel = $this->Asset_Management_model->getVessel($this->Mmm->sanitize($_POST['vessel']));
					$vessel_name = $vessel['name'];

					$checkInsert = $this->Mmm->dbInsert("am_vessel_work_order",$insert,"Added new WO for vessel - " . $vessel_name);

					if($checkInsert) {

						$multiInsert = array();

						$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('am_vessel_work_order');

						foreach($_POST['complaints'] as $ctr=>$val){
							$multiInsert[$ctr]['vessel_work_order_id']	=	$last_id_inserted;
							$multiInsert[$ctr]['complaint_particulars']	=	$this->Mmm->sanitize($_POST['complaints'][$ctr]);
							$multiInsert[$ctr]['status_remarks']	=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}
				
							$checkMultiInsert = $this->Mmm->multiInsert('am_vessel_work_order_details',$multiInsert,'Inserted details for WO of vessel '. $vessel_name ." with Control No.".$control_number . " under " . $company_name['name']);
						
							if($checkMultiInsert){
								
								$this->Abas->sysNotif("New WO", $_SESSION['abas_login']['fullname']." has created new Work Order for vessel - " . $vessel_name ." with WO No.".$control_number . " under " . $company_name['name'],"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Added new Work Order for vessel - " . $vessel_name ." with WO No.".$control_number . " under " . $company_name['name']);
							}else{
								$this->Asset_Management_model->deleteWO($last_id_inserted);
								$this->Asset_Management_model->deleteWODetails($last_id_inserted);
								$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
								$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/WO");
								die();
							}							
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/WO");
						die();
					}


					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/WO");

				break;

				case "TRMRF":

					$control_number = $this->Abas->getNextSerialNumber('am_truck_repairs',$_POST['company']);

					$insert = array();
					$insert['control_number']			=	$control_number;
					$insert['company_id']				=	$this->Mmm->sanitize($_POST['company']);
					$insert['truck_id']					=	$this->Mmm->sanitize($_POST['truck']);
					$insert['driver']					=	$this->Mmm->sanitize($_POST['driver']);	
					$insert['location']					=	$this->Mmm->sanitize($_POST['location']);
					$insert['priority']					=	$this->Mmm->sanitize($_POST['priority']);
					$insert['created_by']				=	$_SESSION['abas_login']['userid'];
					$insert['created_on']				=	date("Y-m-d H:i:s");
					$insert['status']					=	"Draft";

					$company_name = $this->Asset_Management_model->getCompany($this->Mmm->sanitize($_POST['company']));
					$truck = $this->Asset_Management_model->getTruck($this->Mmm->sanitize($_POST['truck']));
					$plate_number = $truck['plate_number'];

					$checkInsert = $this->Mmm->dbInsert("am_truck_repairs",$insert,"Added new TRMRF for truck - " . $plate_number);
					
					if($checkInsert) {

						$multiInsert = array();

						$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('am_truck_repairs');

						foreach($_POST['complaints'] as $ctr=>$val){
							$multiInsert[$ctr]['truck_repairs_id']	=	$last_id_inserted;
							$multiInsert[$ctr]['complaints']		=	$this->Mmm->sanitize($_POST['complaints'][$ctr]);
							$multiInsert[$ctr]['cause_corrections']	=	$this->Mmm->sanitize($_POST['cause_corrections'][$ctr]);
							$multiInsert[$ctr]['remarks']			=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}
				
							$checkMultiInsert = $this->Mmm->multiInsert('am_truck_repairs_details',$multiInsert,'Inserted details for TRMRF for truck - '. $plate_number ." with Control No.".$control_number . " under " . $company_name['name']);
						
							if($checkMultiInsert){
								
								$this->Abas->sysNotif("New TRMRF", $_SESSION['abas_login']['fullname']." has created new Truck Repairs and Maintenance Report Form for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " . $company_name['name'],"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Added new TRMRF for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " . $company_name['name']);
							}else{
								$this->Asset_Management_model->deleteTRMRF($last_id_inserted);
								$this->Asset_Management_model->deleteTRMRFDetails($last_id_inserted);
								$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
								$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/TRMRF");
								die();
							}							
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/TRMRF");
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/TRMRF");

				break;

				case "SRMSF":			

					$insert = array();

					$insert['WO_number']				=	$this->Mmm->sanitize($_POST['WO_number']);

					if($insert['WO_number']>0){
						$control_number = $this->Abas->getNextSerialNumber('am_vessel_evaluation',$_POST['company_x']);
						$insert['control_number']			=	$control_number;
						$insert['company_id']				=	$this->Mmm->sanitize($_POST['company_x']);
						$insert['vessel_id']				=	$this->Mmm->sanitize($_POST['vessel_x']);
					}else{
						$control_number = $this->Abas->getNextSerialNumber('am_vessel_evaluation',$_POST['company']);
						$insert['control_number']			=	$control_number;
						$insert['company_id']				=	$this->Mmm->sanitize($_POST['company']);
						$insert['vessel_id']				=	$this->Mmm->sanitize($_POST['vessel']);
					}
					
					$insert['dry_docking_date']			=	$this->Mmm->sanitize($_POST['dry_docking_date']);
					$insert['dry_docking_location']		=	$this->Mmm->sanitize($_POST['dry_docking_location']);		
					$insert['notes']					=	$this->Mmm->sanitize($_POST['notes']);
					$insert['created_by']				=	$_SESSION['abas_login']['userid'];
					$insert['created_on']				=	date("Y-m-d H:i:s");
					$insert['status']					=	"Draft";

					$company_name = $this->Asset_Management_model->getCompany($insert['company_id']);
					$vessel = $this->Asset_Management_model->getVessel($insert['vessel_id']);
					$vessel_name = $vessel['name'];

					$checkInsert = $this->Mmm->dbInsert("am_vessel_evaluation",$insert,"Added new SRMSF for vessel - " . $vessel_name);
					
					if($checkInsert) {

						$multiInsert = array();

						$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('am_vessel_evaluation');

						foreach($_POST['item_id'] as $ctr=>$val){
							$multiInsert[$ctr]['vessel_evaluation_id']	=	$last_id_inserted;
							$multiInsert[$ctr]['evaluation_item_id']	=	$this->Mmm->sanitize($_POST['item_id'][$ctr]);
							$multiInsert[$ctr]['evaluation_item_name']	=	$this->Mmm->sanitize($_POST['item_name'][$ctr]);
							$multiInsert[$ctr]['rating']				=	$this->Mmm->sanitize($_POST['rating'][$ctr]);
							$multiInsert[$ctr]['make']					=	$this->Mmm->sanitize($_POST['make'][$ctr]);
							$multiInsert[$ctr]['model']					=	$this->Mmm->sanitize($_POST['model'][$ctr]);
							$multiInsert[$ctr]['remarks']				=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}
				
							$checkMultiInsert = $this->Mmm->multiInsert('am_vessel_evaluation_details',$multiInsert,'Inserted details for SRMSF for vessel '. $vessel_name ." with Control No.".$control_number . " under " . $company_name['name']);
						
							if($checkMultiInsert){
								
								$this->Abas->sysNotif("New SRMSF", $_SESSION['abas_login']['fullname']." has created new Ship Repair and Maintenance Survey Form for vessel - " . $vessel_name ." with SRMSF No.".$control_number . " under " . $company_name['name'],"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Added new SRMSF for vessel - " . $vessel_name ." with SRMSF No.".$control_number . " under " . $company_name['name']);
							}else{
								$this->Asset_Management_model->deleteSRMSF($last_id_inserted);
								$this->Asset_Management_model->deleteSRMSFDetails($last_id_inserted);
								$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
								$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/SRMSF");
								die();
							}							
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/SRMSF");
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/SRMSF");

					
				break;

				case "MTDE":

					$insert = array();

					$insert['control_number']			=	$control_number;
					$insert['TRMRF_number']				=	$this->Mmm->sanitize($_POST['TRMRF_number']);

					if($insert['TRMRF_number']>0){
						$control_number = $this->Abas->getNextSerialNumber('am_truck_evaluation',$_POST['company_x']);
						$insert['control_number']			=	$control_number;
						$insert['company_id']				=	$this->Mmm->sanitize($_POST['company_x']);
						$insert['truck_id']					=	$this->Mmm->sanitize($_POST['truck_x']);
						$insert['driver']					=	$this->Mmm->sanitize($_POST['driver_x']);	
					}else{
						$control_number = $this->Abas->getNextSerialNumber('am_truck_evaluation',$_POST['company']);
						$insert['control_number']			=	$control_number;
						$insert['company_id']				=	$this->Mmm->sanitize($_POST['company']);
						$insert['truck_id']					=	$this->Mmm->sanitize($_POST['truck']);
						$insert['driver']					=	$this->Mmm->sanitize($_POST['driver']);	
					}

					$insert['notes']					=	$this->Mmm->sanitize($_POST['notes']);
					$insert['created_by']				=	$_SESSION['abas_login']['userid'];
					$insert['created_on']				=	date("Y-m-d H:i:s");
					$insert['status']					=	"Draft";



					$company_name = $this->Asset_Management_model->getCompany($insert['company_id']);
					$truck = $this->Asset_Management_model->getTruck($insert['truck_id']);
					$plate_number = $truck['plate_number'];

					$checkInsert = $this->Mmm->dbInsert("am_truck_evaluation",$insert,"Added new MTDE for truck - " . $plate_number);
					
					if($checkInsert) {

						$multiInsert = array();

						$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('am_truck_evaluation');

						foreach($_POST['item_id'] as $ctr=>$val){
							$multiInsert[$ctr]['truck_evaluation_id']	=	$last_id_inserted;
							$multiInsert[$ctr]['evaluation_item_id']	=	$this->Mmm->sanitize($_POST['item_id'][$ctr]);
							$multiInsert[$ctr]['evaluation_item_name']	=	$this->Mmm->sanitize($_POST['item_name'][$ctr]);
							$multiInsert[$ctr]['rating']				=	$this->Mmm->sanitize($_POST['rating'][$ctr]);
							$multiInsert[$ctr]['make']					=	$this->Mmm->sanitize($_POST['make'][$ctr]);
							$multiInsert[$ctr]['model']					=	$this->Mmm->sanitize($_POST['model'][$ctr]);
							$multiInsert[$ctr]['remarks']				=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}
				
							$checkMultiInsert = $this->Mmm->multiInsert('am_truck_evaluation_details',$multiInsert,'Inserted details for MTDE for truck - '. $plate_number." with Control No.".$control_number . " under " . $company_name['name']);
						
							if($checkMultiInsert){
								
								$this->Abas->sysNotif("New MTDE", $_SESSION['abas_login']['fullname']." has created new Motorpool Truck Diagnostic Evaluation for truck - " . $plate_number ." with MTDE No.".$control_number . " under " . $company_name['name'],"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Added new MTDE for truck - " . $plate_number ." with MTDE No.".$control_number . " under " . $company_name['name']);
							}else{
								$this->Asset_Management_model->deleteMTDE($last_id_inserted);
								$this->Asset_Management_model->deleteMTDEDetails($last_id_inserted);
								$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
								$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/MTDE");
								die();
							}							
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/MTDE");
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/MTDE");

				break;

				case "evaluation_items":

					$insert = array();

					$insert['item_index']		=	$this->Mmm->sanitize($_POST['index']);
					$insert['item_set']			=	$this->Mmm->sanitize($_POST['set']);
					$insert['item_sub_set']		=	$this->Mmm->sanitize($_POST['sub_set']);
					$insert['item_name']		=	$this->Mmm->sanitize($_POST['item_name']);
					$insert['type']				=	$this->Mmm->sanitize($_POST['type']);
					$insert['ask_spec']			=	$this->Mmm->sanitize($_POST['ask_spec']);
					$insert['enabled']			=	$this->Mmm->sanitize($_POST['enabled']);

					$indexing = "Succesfully added new Evaluation Item - " .$this->Mmm->sanitize($_POST['index']). "." .$this->Mmm->sanitize($_POST['set']). "." .$this->Mmm->sanitize($_POST['sub_set'])." for ".$insert['type']." Maintenance.";

					$checkInsert = $this->Mmm->dbInsert("am_evaluation_items",$insert,$indexing);		
					
					if($checkInsert) {
						$this->Abas->sysMsg("sucmsg",$indexing);
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/evaluation_items/".$this->Mmm->sanitize($_POST['type']));
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/evaluation_items/".$this->Mmm->sanitize($_POST['type']));

				break;

				case "BOM":

					$control_number = $this->Abas->getNextSerialNumber('am_bill_of_materials',$_POST['company']);

					$insert = array();

					$insert['control_number'] 		= $control_number;
					$insert['company_id'] 			= $this->Mmm->sanitize($_POST['company']);
					$insert['evaluation_id'] 		= $this->Mmm->sanitize($_POST['evaluation_form_no']);
					$insert['bom_type'] 			= $this->Mmm->sanitize($_POST['maintenance_type']);
					$insert['start_date_of_repair'] = $this->Mmm->sanitize($_POST['start_date_of_repair']);
					$insert['remarks'] 				= $this->Mmm->sanitize($_POST['remarks']);
					$insert['created_by'] 			= $_SESSION['abas_login']['userid'];
					$insert['created_on']			= date("Y-m-d H:i:s");
					$insert['status']				= "Draft";

					$company_name = $this->Asset_Management_model->getCompany($this->Mmm->sanitize($_POST['company']));
					$control_number = $this->Mmm->sanitize($_POST['control_number']);
					$type = $this->Mmm->sanitize($_POST['maintenance_type']);
					$company = $this->Mmm->sanitize($_POST['company']);
					$evaluation_id = $this->Mmm->sanitize($_POST['evaluation_form_no']);
					$evaluation = $this->Asset_Management_model->getEvaluationFormNumber($type,$company,$evaluation_id);	
					
					$checkInsert = $this->Mmm->dbInsert("am_bill_of_materials",$insert,"Added new BOM for ".$evaluation->maintenance_form . $evaluation->control_number . " under " . $company_name['name']);
					
					if($checkInsert){

						$multiInsertTasks = array();

						$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('am_bill_of_materials');

						foreach($_POST['tasks_no'] as $ctr=>$val){
							$multiInsertTasks[$ctr]['bill_of_materials_id']	=	$last_id_inserted;
							$multiInsertTasks[$ctr]['task_number']	=	$this->Mmm->sanitize($_POST['tasks_no'][$ctr]);
							$multiInsertTasks[$ctr]['scope_of_work']=	$this->Mmm->sanitize($_POST['scope_of_work'][$ctr]);
							$multiInsertTasks[$ctr]['total_area']	=	$this->Mmm->sanitize($_POST['total_area'][$ctr]);
							$multiInsertTasks[$ctr]['estimated_time_to_complete']	=	$this->Mmm->sanitize($_POST['estimated_time_to_complete'][$ctr]);
						}

						$checkMultiInsertTasks = $this->Mmm->multiInsert('am_bill_of_materials_tasks',$multiInsertTasks,'Inserted Tasks details for '.$evaluation->maintenance_form . $evaluation->control_number  ." with BOM No.".$control_number . " under " . $company_name['name']);

						foreach($_POST['labor_task_no'] as $ctr=>$val){
							$multiInsertLabor[$ctr]['bill_of_materials_id']	=	$last_id_inserted;
							$multiInsertLabor[$ctr]['task_numbers']	=	$this->Mmm->sanitize($_POST['labor_task_no'][$ctr]);
							$multiInsertLabor[$ctr]['job_description']=	$this->Mmm->sanitize($_POST['labor_job_description'][$ctr]);
							$multiInsertLabor[$ctr]['quantity']	=	$this->Mmm->sanitize($_POST['labor_quantity'][$ctr]);
							$multiInsertLabor[$ctr]['days_needed']	=	$this->Mmm->sanitize($_POST['labor_days_needed'][$ctr]);
							$multiInsertLabor[$ctr]['rate_per_day']	=	$this->Mmm->sanitize($_POST['labor_rate_per_day'][$ctr]);
						}

						$checkMultiInsertLabor = $this->Mmm->multiInsert('am_bill_of_materials_labor',$multiInsertLabor,'Inserted Labor details for '.$evaluation->maintenance_form . $evaluation->control_number  ." with BOM No.".$control_number . " under " . $company_name['name']);

						foreach($_POST['item_id'] as $ctr=>$val){
							$multiInsertMaterials[$ctr]['bill_of_materials_id']	=	$last_id_inserted;
							$multiInsertMaterials[$ctr]['item_id']	=	$this->Mmm->sanitize($_POST['item_id'][$ctr]);
							$multiInsertMaterials[$ctr]['item_description']	=	$this->Mmm->sanitize($_POST['item_description'][$ctr]);
							$multiInsertMaterials[$ctr]['item_size']	=	$this->Mmm->sanitize($_POST['item_size'][$ctr]);
							$multiInsertMaterials[$ctr]['item_unit_measurement']	=	$this->Mmm->sanitize($_POST['item_unit_measurement'][$ctr]);
							$multiInsertMaterials[$ctr]['warehouse_quantity']	=	$this->Mmm->sanitize($_POST['warehouse_quantity'][$ctr]);
							$multiInsertMaterials[$ctr]['warehouse_unit_cost']	=	$this->Mmm->sanitize($_POST['warehouse_unit_cost'][$ctr]);
							$multiInsertMaterials[$ctr]['purchase_quantity']	=	$this->Mmm->sanitize($_POST['purchase_quantity'][$ctr]);
							$multiInsertMaterials[$ctr]['quantity']	=	$this->Mmm->sanitize($_POST['item_quantity'][$ctr]);
							$multiInsertMaterials[$ctr]['unit_cost']	=	$this->Mmm->sanitize($_POST['item_unit_cost'][$ctr]);
						}


						$checkMultiInsertMaterials = $this->Mmm->multiInsert('am_bill_of_materials_supplies',$multiInsertMaterials,'Inserted Material and Supplies details for '.$evaluation->maintenance_form . $evaluation->control_number  ." with BOM No.".$control_number . " under " . $company_name['name']);

						foreach($_POST['tool_name'] as $ctr=>$val){
							$multiInsertTools[$ctr]['bill_of_materials_id']	=	$last_id_inserted;
							$multiInsertTools[$ctr]['tool_name']	=	$this->Mmm->sanitize($_POST['tool_name'][$ctr]);
							$multiInsertTools[$ctr]['quantity']	=	$this->Mmm->sanitize($_POST['tool_quantity'][$ctr]);
							$multiInsertTools[$ctr]['days_used']	=	$this->Mmm->sanitize($_POST['tool_estimated_days_used'][$ctr]);
						}

					
						$checkMultiInsertTools = $this->Mmm->multiInsert('am_bill_of_materials_tools',$multiInsertTools,'Inserted Tools and Equipment details for '.$evaluation->maintenance_form . $evaluation->control_number  ." with BOM No.".$control_number . " under " . $company_name['name']);


						if($checkMultiInsertTasks && $checkMultiInsertLabor && $checkMultiInsertMaterials && $checkMultiInsertTools){
							
								$this->Abas->sysNotif("New BOM", $_SESSION['abas_login']['fullname']." has created new Bill Of Materials for " . $evaluation->maintenance_form . $evaluation->control_number ." with BOM No.".$control_number . " under " . $company_name['name'],"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Added new BOM for " . $evaluation->maintenance_form . $evaluation->control_number ." with BOM No.".$control_number . " under " . $company_name['name']);

						}else{
							$this->Asset_Management_model->deleteBOM($last_id_inserted);
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/BOM/".$this->Mmm->sanitize($_POST['maintenance_type']));
							die();
						}
						

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/BOM/".$this->Mmm->sanitize($_POST['maintenance_type']));
						die();
					}


					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/BOM/".$this->Mmm->sanitize($_POST['maintenance_type']));

				break;

				case "schedule_logs":

					$insert = array();

					$insert['control_number'] 		= $this->Abas->getNextSerialNumber('am_schedule_logs',$_POST['company']);

					$insert['company_id'] 			= $this->Mmm->sanitize($_POST['company']);
					$insert['bill_of_materials_id'] = $this->Mmm->sanitize($_POST['bom_no']);
					$insert['asset_id'] 			= $this->Mmm->sanitize($_POST['asset_id']);
					$insert['type'] 				= $this->Mmm->sanitize($_POST['bom_type']);

					$insert['reference_number'] 	= $this->Mmm->sanitize($_POST['reference_no']);

					$insert['created_by'] 			= $_SESSION['abas_login']['userid'];
					$insert['created_on']			= date("Y-m-d H:i:s");
					$insert['status']				= "Draft";

					$company_name = $this->Asset_Management_model->getCompany($this->Mmm->sanitize($_POST['company']));
					$control_number = $insert['control_number'];
					$type = $this->Mmm->sanitize($insert['type']);

					if($type=='Vessel'){
						$asset_name = $this->Asset_Management_model->getVessel($insert['asset_id'])['name'];
					}else{
						$asset_name = $this->Asset_Management_model->getTruck($insert['asset_id'])['plate_number'];
					}	
					
					$checkInsert = $this->Mmm->dbInsert("am_schedule_logs",$insert,"Added new Schedule Log for ".$type. "(".$asset_name.") with Control No." . $control_number . " under " . $company_name['name']);
					
					if($checkInsert){
						$multiInsertTasks = array();

						$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('am_schedule_logs');

						foreach($_POST['task_id'] as $ctr=>$val){
							$multiInsertTasks[$ctr]['schedule_log_id']		=	$last_id_inserted;
							$multiInsertTasks[$ctr]['task_id']				=	$this->Mmm->sanitize($_POST['task_id'][$ctr]);
							$multiInsertTasks[$ctr]['bill_of_materials_id']	=	$this->Mmm->sanitize($_POST['bom_id'][$ctr]);
							$multiInsertTasks[$ctr]['personnel_in_charge']	=	$this->Mmm->sanitize($_POST['personnel_in_charge'][$ctr]);
							$multiInsertTasks[$ctr]['plan_start_date']		=	$this->Mmm->sanitize($_POST['plan_start_date'][$ctr]);
							$multiInsertTasks[$ctr]['plan_end_date']		=	NULL;
							$multiInsertTasks[$ctr]['actual_start_date']	=	$this->Mmm->sanitize($_POST['actual_start_date'][$ctr]);
							$multiInsertTasks[$ctr]['actual_end_date']		=	NULL;
							$multiInsertTasks[$ctr]['actual_work_duration']		=	$this->Mmm->sanitize($_POST['actual_work_duration'][$ctr]);
							$multiInsertTasks[$ctr]['percentage']		=	$this->Mmm->sanitize($_POST['percentage'][$ctr]);
							$multiInsertTasks[$ctr]['remarks']		=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}

						$checkMultiInsert = $this->Mmm->multiInsert("am_schedule_log_tasks",$multiInsertTasks,"Added tasks for Schedule Logs for ".$type. "(".$asset_name.") with Control No." . $control_number . " under " . $company_name['name']);

						if($checkMultiInsert){

							if($type=='Vessel'){
								$title = "New Dry Dock Schedule Log";
							}else{
								$title = "New Motorpool Repairs Schedule Log";
							}
							
							$this->Abas->sysNotif($title, $_SESSION['abas_login']['fullname']." has created new Schedule Logs for ".$type. "(".$asset_name.") with Control No." . $control_number . " under " . $company_name['name'],"Asset Management","info");

							$this->Abas->sysMsg("sucmsg", "Added new Schedule Logs for ".$type. "(".$asset_name.") with Control No." . $control_number . " under " . $company_name['name']);

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/schedule_logs/".$type);
							die();
						}
					}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/schedule_logs/".$type);
							die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/schedule_logs/".$type);

				break;

			}

		}
		else{// opens form to fill data

				$data = array();

				if(isset($for)){
					$data['for'] = $for;
				}

			switch($type){

				case "WO":

					$data['companies']		=	$this->Asset_Management_model->getCompanies();		
					
					$this->load->view(VIEW.'/repairs_and_maintenance/WO/form.php',$data);
					
				break;

				case "TRMRF":

					$data['companies']		=	$this->Asset_Management_model->getCompanies();		
					
					$this->load->view(VIEW.'/repairs_and_maintenance/TRMRF/form.php',$data);
					
				break;

				case "SRMSF":

					$data['WO']				=	$this->Asset_Management_model->getWOs();
					$data['companies']		=	$this->Asset_Management_model->getCompanies();		
					$data['index']['A']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('A','Vessel');
					$data['index']['B']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('B','Vessel');
					$data['index']['C']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('C','Vessel');
					$data['index']['D']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('D','Vessel');
					$data['index']['E']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('E','Vessel');
					$data['index']['F']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('F','Vessel');

					$data['steps'] 			= $this->Asset_Management_model->prepareSRMSF()['steps'];
					$data['ratings']		= $this->Asset_Management_model->prepareSRMSF()['ratings'];

					$this->load->view(VIEW.'/repairs_and_maintenance/SRMSF/form.php',$data);
					
				break;

				case "MTDE":

					$data['TRMRF']			=	$this->Asset_Management_model->getTRMRFs();
					$data['companies']		=	$this->Asset_Management_model->getCompanies();		
					$data['index']['A']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('A','Truck');
					$data['index']['B']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('B','Truck');
					$data['index']['C']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('C','Truck');
					$data['index']['D']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('D','Truck');
					$data['index']['E']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('E','Truck');

					$data['steps'] 			= $this->Asset_Management_model->prepareMTDE()['steps'];
					$data['ratings']		= $this->Asset_Management_model->prepareMTDE()['ratings'];

					$this->load->view(VIEW.'/repairs_and_maintenance/MTDE/form.php',$data);

				break;

				case "evaluation_items":

					$data['index'] = $this->Asset_Management_model->prepareEvaluationItems()['index'];

					$this->load->view(VIEW.'/repairs_and_maintenance/evaluation_items/form.php',$data);

				break;

				case "schedule_logs":

					$data['companies']		=	$this->Asset_Management_model->getCompanies();
					$this->load->view(VIEW.'/schedule_logs/form.php',$data);

				break;

				case "BOM":

					$data['companies']		=	$this->Asset_Management_model->getCompanies();

					$this->load->view(VIEW.'/bill_of_materials/form.php',$data);

				break;

			}
		}


	}


	public function edit( $type = NULL, $id = NULL ){
		if(!empty($_POST)){//update data

			switch($type){
				case "evaluation_items":
					$update = array();

					$update['item_index']		=	$this->Mmm->sanitize($_POST['index']);
					$update['item_set']			=	$this->Mmm->sanitize($_POST['set']);
					$update['item_sub_set']		=	$this->Mmm->sanitize($_POST['sub_set']);
					$update['item_name']		=	$this->Mmm->sanitize($_POST['item_name']);
					$update['type']				=	$this->Mmm->sanitize($_POST['type']);
					$update['ask_spec']			=	$this->Mmm->sanitize($_POST['ask_spec']);
					$update['enabled']			=	$this->Mmm->sanitize($_POST['enabled']);

					$indexing = "Successfully edited Evaluation Item - " .$this->Mmm->sanitize($_POST['index']). "." .$this->Mmm->sanitize($_POST['set']). "." .$this->Mmm->sanitize($_POST['sub_set'])." for ".$update['type']." Maintenance.";

					$checkUpdate = $this->Mmm->dbUpdate("am_evaluation_items",$update,$id,$indexing);		
					
					if($checkUpdate) {
						$this->Abas->sysMsg("sucmsg",$indexing);
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/evaluation_items/".$this->Mmm->sanitize($_POST['type']));
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/evaluation_items/".$this->Mmm->sanitize($_POST['type']));

				break;

				case "WO":

					$update = array();

					$WO = $this->Asset_Management_model->getWO($id);
					$company_name = $WO['company_name'];
					$vessel_name = $WO['vessel_name'];
					$control_number = $WO['control_number'];

					$update['control_number']			=	$control_number;
					$update['company_id']				=	$WO['company_id'];
					$update['vessel_id']				=	$WO['vessel_id'];
					$update['location']					=	$this->Mmm->sanitize($_POST['location']);
					$update['requisitioner']			=	$this->Mmm->sanitize($_POST['requisitioner']);
					$update['designation']				=	$this->Mmm->sanitize($_POST['designation']);
					$update['created_by']				=	$_SESSION['abas_login']['userid'];
					$update['created_on']				=	date("Y-m-d H:i:s");
					$update['status']					=	"Draft";

					$checkUpdate = $this->Mmm->dbUpdate("am_vessel_work_order",$update,$id,"Edited WO for vessel - " . $vessel_name);

					if($checkUpdate) {

						$this->Asset_Management_model->deleteWODetails($id);

						$multiInsert = array();

						foreach($_POST['complaints'] as $ctr=>$val){
							$multiInsert[$ctr]['vessel_work_order_id']	=	$id;
							$multiInsert[$ctr]['complaint_particulars']	=	$this->Mmm->sanitize($_POST['complaints'][$ctr]);
							$multiInsert[$ctr]['status_remarks']	=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}
				
							$checkMultiInsert = $this->Mmm->multiInsert('am_vessel_work_order_details',$multiInsert,'Edited details for WO of vessel '. $vessel_name .' with Control No. '.$control_number. ' under '. $company_name);
						
							if($checkMultiInsert){
								
								$this->Abas->sysNotif("Edited WO", $_SESSION['abas_login']['fullname']." has edited Work Order for vessel - " . $vessel_name ." with Control No. ".$control_number." under " . $company_name,"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", 'Edited details for WO of vessel '. $vessel_name ." with Control No. ".$control_number." under " . $company_name);
							}else{
								
								$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
								$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/WO/".$id);
								die();
							}							
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/WO/".$id);
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/WO/".$id);

				break;

				case "TRMRF":

					$update = array();
					
					$TRMRF = $this->Asset_Management_model->getTRMRF($id);
					$company_name = $TRMRF['company_name'];
					$plate_number = $TRMRF['plate_number'];
					$control_number = $TRMRF['control_number'];

					$update['control_number']			=	$control_number;
					$update['company_id']				=	$TRMRF['company_id'];
					$update['truck_id']					=	$this->Mmm->sanitize($_POST['truck']);
					$update['driver']					=	$this->Mmm->sanitize($_POST['driver']);	
					$update['location']					=	$this->Mmm->sanitize($_POST['location']);
					$update['priority']					=	$this->Mmm->sanitize($_POST['priority']);
					$update['created_by']				=	$_SESSION['abas_login']['userid'];
					$update['created_on']				=	date("Y-m-d H:i:s");
					$update['status']					=	"Draft";

					$checkUpdate = $this->Mmm->dbUpdate("am_truck_repairs",$update,$id,"Edited TRMRF for truck - " . $plate_number);
					
					if($checkUpdate) {

						$this->Asset_Management_model->deleteTRMRFDetails($id);

						$multiInsert = array();

						foreach($_POST['complaints'] as $ctr=>$val){
							$multiInsert[$ctr]['truck_repairs_id']	=	$id;
							$multiInsert[$ctr]['complaints']		=	$this->Mmm->sanitize($_POST['complaints'][$ctr]);
							$multiInsert[$ctr]['cause_corrections']	=	$this->Mmm->sanitize($_POST['cause_corrections'][$ctr]);
							$multiInsert[$ctr]['remarks']			=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}
				
							$checkMultiInsert = $this->Mmm->multiInsert('am_truck_repairs_details',$multiInsert,'Edited details for TRMRF for truck - '. $plate_number ." with Control No.".$control_number . " under " . $company_name);
						
							if($checkMultiInsert){
								
								$this->Abas->sysNotif("Edit TRMRF", $_SESSION['abas_login']['fullname']." has edited Truck Repairs and Maintenance Report Form for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " . $company_name,"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Edited TRMRF for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " . $company_name);
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
								$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/TRMRF/".$id);
								die();
							}							
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/TRMRF/".$id);
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/TRMRF/".$id);

				break;

				case "SRMSF":

					$update = array();

					$SRMSF = $this->Asset_Management_model->getSRMSF($id);
					$company_name = $SRMSF['company_name'];
					$vessel_name = $SRMSF['vessel_name'];
					$control_number = $SRMSF['control_number'];

					$update['WO_number']				=	$SRMSF['WO_number'];
					$update['control_number']			=	$control_number;
					$update['company_id']				=	$SRMSF['company_id'];
					$update['vessel_id']				=	$SRMSF['vessel_id'];
					$update['dry_docking_date']			=	$this->Mmm->sanitize($_POST['dry_docking_date']);
					$update['dry_docking_location']		=	$this->Mmm->sanitize($_POST['dry_docking_location']);		
					$update['notes']					=	$this->Mmm->sanitize($_POST['notes']);
					$update['created_by']				=	$_SESSION['abas_login']['userid'];
					$update['created_on']				=	date("Y-m-d H:i:s");
					$update['status']					=	"Draft";

					$checkUpdate = $this->Mmm->dbUpdate("am_vessel_evaluation",$update,$id,"Edited SRMSF for vessel - " . $vessel_name);
					
					if($checkUpdate) {

						$this->Asset_Management_model->deleteSRMSFDetails($id);

						$multiInsert = array();

						foreach($_POST['item_id'] as $ctr=>$val){
							$multiInsert[$ctr]['vessel_evaluation_id']	=	$id;
							$multiInsert[$ctr]['evaluation_item_id']	=	$this->Mmm->sanitize($_POST['item_id'][$ctr]);
							$multiInsert[$ctr]['evaluation_item_name']	=	$this->Mmm->sanitize($_POST['item_name'][$ctr]);
							$multiInsert[$ctr]['rating']				=	$this->Mmm->sanitize($_POST['rating'][$ctr]);
							$multiInsert[$ctr]['make']					=	$this->Mmm->sanitize($_POST['make'][$ctr]);
							$multiInsert[$ctr]['model']					=	$this->Mmm->sanitize($_POST['model'][$ctr]);
							$multiInsert[$ctr]['remarks']				=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}
				
							$checkMultiInsert = $this->Mmm->multiInsert('am_vessel_evaluation_details',$multiInsert,'Edited details for SRMSF for vessel '. $vessel_name ." with Control No.".$control_number . " under " . $company_name['name']);
						
							if($checkMultiInsert){
								
								$this->Abas->sysNotif("Edited SRMSF", $_SESSION['abas_login']['fullname']." has edited Ship Repair and Maintenance Survey Form for vessel - " . $vessel_name ." with Control No.".$control_number . " under " . $company_name['name'],"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Edited SRMSF for vessel - " . $vessel_name ." with Control No.".$control_number . " under " . $company_name['name']);
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
								$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/SRMSF/".$id);
								die();
							}							
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/SRMSF/".$id);
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/SRMSF/".$id);

				break;

				case "MTDE":

					$this->Asset_Management_model->deleteMTDEDetails($id);

					$update = array();

					$MTDE = $this->Asset_Management_model->getMTDE($id);
					$company_name = $MTDE['company_name'];
					$plate_number = $MTDE['plate_number'];
					$control_number = $MTDE['control_number'];

					$update['control_number']			=	$control_number;
					$update['TRMRF_number']				=	$MTDE['TRMRF_number'];
					$update['company_id']				=	$MTDE['company_id'];
					$update['truck_id']					=	$MTDE['truck_id'];
					$update['driver']					=	$MTDE['driver'];
					$update['notes']					=	$this->Mmm->sanitize($_POST['notes']);
					$update['created_by']				=	$_SESSION['abas_login']['userid'];
					$update['created_on']				=	date("Y-m-d H:i:s");
					$update['status']					=	"Draft";

					$checkUpdate = $this->Mmm->dbUpdate("am_truck_evaluation",$update,$id,"Edited MTDE for truck - " . $plate_number);
					
					if($checkUpdate) {

						$multiInsert = array();

						foreach($_POST['item_id'] as $ctr=>$val){
							$multiInsert[$ctr]['truck_evaluation_id']	=	$id;
							$multiInsert[$ctr]['evaluation_item_id']	=	$this->Mmm->sanitize($_POST['item_id'][$ctr]);
							$multiInsert[$ctr]['evaluation_item_name']	=	$this->Mmm->sanitize($_POST['item_name'][$ctr]);
							$multiInsert[$ctr]['rating']				=	$this->Mmm->sanitize($_POST['rating'][$ctr]);
							$multiInsert[$ctr]['make']					=	$this->Mmm->sanitize($_POST['make'][$ctr]);
							$multiInsert[$ctr]['model']					=	$this->Mmm->sanitize($_POST['model'][$ctr]);
							$multiInsert[$ctr]['remarks']				=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}
				
							$checkMultiInsert = $this->Mmm->multiInsert('am_truck_evaluation_details',$multiInsert,'Edited details for MTDE for truck - '. $plate_number." with Control No.".$control_number . " under " . $company_name);
						
							if($checkMultiInsert){
								
								$this->Abas->sysNotif("Edit MTDE", $_SESSION['abas_login']['fullname']." has edited Motorpool Truck Diagnostic Evaluation for truck - " . $plate_number ." with MTDE No.".$control_number . " under " . $company_name,"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Edited MTDE for truck - " . $plate_number ." with MTDE No.".$control_number . " under " . $company_name);
							}else{
								$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
								$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/MTDE/".$id);
								die();
							}							
					}
					else {
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/MTDE/".$id);
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/MTDE/".$id);

				break;

				case "schedule_logs":

					$update = array();

					$this->Asset_Management_model->deleteScheduleLogTasks($id);

					$schedule_log = $this->Asset_Management_model->getScheduleLog($id);
					$company_name = $schedule_log['company_name'];
					$company_id = $schedule_log['company_id'];
					$bom_id = $schedule_log['bill_of_materials_id'];
					$control_number = $schedule_log['control_number'];
					$type = $schedule_log['type'];
					$asset_name = $schedule_log['asset_name'];

					$update['company_id'] 			= $company_id;
					$update['bill_of_materials_id'] = $bom_id;
					$update['asset_id'] 			= $this->Mmm->sanitize($_POST['asset_id']);
					$update['type'] 				= $this->Mmm->sanitize($_POST['bom_type']);

					$update['reference_number'] 	= $this->Mmm->sanitize($_POST['reference_no']);
					$update['trial_date'] 	= $this->Mmm->sanitize($_POST['trial_date']);
					
					if($schedule_log['status']=="Draft"){
						$update['created_by'] 			= $_SESSION['abas_login']['userid'];
						$update['created_on']			= date("Y-m-d H:i:s");
						$update['status']				= "Draft";
					}else{
						$update['updated_by'] 			= $_SESSION['abas_login']['userid'];
						$update['updated_on']			= date("Y-m-d H:i:s");
						$update['status']				= "Final";
					}
					

					$checkUpdate = $this->Mmm->dbUpdate("am_schedule_logs",$update,$id,"Edited/Updated Schedule Log for ".$type. "(".$asset_name.") with Control No." . $control_number . " under " . $company_name['name']);
			

					if($checkUpdate){
						$multiInsertTasks = array();

						foreach($_POST['task_id'] as $ctr=>$val){
							$multiInsertTasks[$ctr]['schedule_log_id']		=	$id;
							$multiInsertTasks[$ctr]['task_id']				=	$this->Mmm->sanitize($_POST['task_id'][$ctr]);
							$multiInsertTasks[$ctr]['bill_of_materials_id']	=	$this->Mmm->sanitize($_POST['bom_id'][$ctr]);
							$multiInsertTasks[$ctr]['personnel_in_charge']	=	$this->Mmm->sanitize($_POST['personnel_in_charge'][$ctr]);
							$multiInsertTasks[$ctr]['plan_start_date']		=	$this->Mmm->sanitize($_POST['plan_start_date'][$ctr]);
							$multiInsertTasks[$ctr]['plan_end_date']		=	NULL;
							$multiInsertTasks[$ctr]['actual_start_date']	=	$this->Mmm->sanitize($_POST['actual_start_date'][$ctr]);
							$multiInsertTasks[$ctr]['actual_end_date']		=	NULL;
							$multiInsertTasks[$ctr]['actual_work_duration']		=	$this->Mmm->sanitize($_POST['actual_work_duration'][$ctr]);
							$multiInsertTasks[$ctr]['percentage']		=	$this->Mmm->sanitize($_POST['percentage'][$ctr]);
							$multiInsertTasks[$ctr]['remarks']		=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
						}

						$checkMultiInsert = $this->Mmm->multiInsert("am_schedule_log_tasks",$multiInsertTasks,"Edited/Updated tasks for Schedule Logs for ".$type. "(".$asset_name.") with Control No." . $control_number . " under " . $company_name['name']);

						if($checkMultiInsert){

							if($type=='Vessel'){
								$title = "Edited Dry Dock Schedule Log";
							}else{
								$title = "Edited Motorpool Repairs Schedule Log";
							}
							
							$this->Abas->sysNotif($title, $_SESSION['abas_login']['fullname']." has edited/updated Schedule Logs for ".$type. "(".$asset_name.") with Control No." . $control_number . " under " . $company_name['name'],"Asset Management","info");

							$this->Abas->sysMsg("sucmsg", "Edited/Updated Schedule Logs for ".$type. "(".$asset_name.") with Control No." . $control_number . " under " . $company_name['name']);

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/schedule_logs/".$id);
							die();
						}
					}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/schedule_logs/".$id);
							die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/schedule_logs/".$id);

				break;

				case "BOM":

					$update = array();

					$BOM = $this->Asset_Management_model->getBOM($id);
					$company_name = $BOM['company_name'];
					$control_number = $BOM['control_number'];
					$evaluation_id = $BOM['evaluation_id'];
					$company_id = $BOM['company_id'];
					$type = $BOM['bom_type'];

					$update['control_number'] 		= $control_number;
					$update['company_id'] 			= $company_id;
					$update['evaluation_id'] 		= $evaluation_id;
					$update['bom_type'] 			= $type;
					$update['start_date_of_repair'] = $this->Mmm->sanitize($_POST['start_date_of_repair']);
					$update['remarks'] 				= $this->Mmm->sanitize($_POST['remarks']);
					$update['created_by'] 			= $_SESSION['abas_login']['userid'];
					$update['created_on']			= date("Y-m-d H:i:s");
					$update['status']				= "Draft";
					
					$evaluation = $this->Asset_Management_model->getEvaluationFormNumber($type,$company_id,$evaluation_id);	
					
					$checkUpdate = $this->Mmm->dbUpdate("am_bill_of_materials",$update,$id,"Edited BOM for ".$evaluation->maintenance_form . $evaluation->control_number . " under " . $company_name);
					
					if($checkUpdate){

						$this->Asset_Management_model->deleteBOMDetails($id);

						$multiInsertTasks = array();

						foreach($_POST['tasks_no'] as $ctr=>$val){
							$multiInsertTasks[$ctr]['bill_of_materials_id']	=	$id;
							$multiInsertTasks[$ctr]['task_number']	=	$this->Mmm->sanitize($_POST['tasks_no'][$ctr]);
							$multiInsertTasks[$ctr]['scope_of_work']=	$this->Mmm->sanitize($_POST['scope_of_work'][$ctr]);
							$multiInsertTasks[$ctr]['total_area']	=	$this->Mmm->sanitize($_POST['total_area'][$ctr]);
							$multiInsertTasks[$ctr]['estimated_time_to_complete']	=	$this->Mmm->sanitize($_POST['estimated_time_to_complete'][$ctr]);
						}

						$checkMultiInsertTasks = $this->Mmm->multiInsert('am_bill_of_materials_tasks',$multiInsertTasks,'Edited Tasks details for '.$evaluation->maintenance_form . $evaluation->control_number  ." with BOM No.".$control_number . " under " . $company_name);

						foreach($_POST['labor_task_no'] as $ctr=>$val){
							$multiInsertLabor[$ctr]['bill_of_materials_id']	=	$id;
							$multiInsertLabor[$ctr]['task_numbers']	=	$this->Mmm->sanitize($_POST['labor_task_no'][$ctr]);
							$multiInsertLabor[$ctr]['job_description']=	$this->Mmm->sanitize($_POST['labor_job_description'][$ctr]);
							$multiInsertLabor[$ctr]['quantity']	=	$this->Mmm->sanitize($_POST['labor_quantity'][$ctr]);
							$multiInsertLabor[$ctr]['days_needed']	=	$this->Mmm->sanitize($_POST['labor_days_needed'][$ctr]);
							$multiInsertLabor[$ctr]['rate_per_day']	=	$this->Mmm->sanitize($_POST['labor_rate_per_day'][$ctr]);
						}

						$checkMultiInsertLabor = $this->Mmm->multiInsert('am_bill_of_materials_labor',$multiInsertLabor,'Edited Labor details for '.$evaluation->maintenance_form . $evaluation->control_number  ." with BOM No.".$control_number . " under " . $company_name);

						foreach($_POST['item_id'] as $ctr=>$val){
							$multiInsertMaterials[$ctr]['bill_of_materials_id']	=	$id;
							$multiInsertMaterials[$ctr]['item_id']	=	$this->Mmm->sanitize($_POST['item_id'][$ctr]);
							$multiInsertMaterials[$ctr]['item_description']	=	$this->Mmm->sanitize($_POST['item_description'][$ctr]);
							$multiInsertMaterials[$ctr]['item_size']	=	$this->Mmm->sanitize($_POST['item_size'][$ctr]);
							$multiInsertMaterials[$ctr]['item_unit_measurement']	=	$this->Mmm->sanitize($_POST['item_unit_measurement'][$ctr]);
							$multiInsertMaterials[$ctr]['warehouse_quantity']	=	$this->Mmm->sanitize($_POST['warehouse_quantity'][$ctr]);
							$multiInsertMaterials[$ctr]['warehouse_unit_cost']	=	$this->Mmm->sanitize($_POST['warehouse_unit_cost'][$ctr]);
							$multiInsertMaterials[$ctr]['purchase_quantity']	=	$this->Mmm->sanitize($_POST['purchase_quantity'][$ctr]);
							$multiInsertMaterials[$ctr]['quantity']	=	$this->Mmm->sanitize($_POST['item_quantity'][$ctr]);
							$multiInsertMaterials[$ctr]['unit_cost']	=	$this->Mmm->sanitize($_POST['item_unit_cost'][$ctr]);
						}


						$checkMultiInsertMaterials = $this->Mmm->multiInsert('am_bill_of_materials_supplies',$multiInsertMaterials,'Edited Material and Supplies details for '.$evaluation->maintenance_form . $evaluation->control_number  ." with BOM No.".$control_number . " under " . $company_name);

						foreach($_POST['tool_name'] as $ctr=>$val){
							$multiInsertTools[$ctr]['bill_of_materials_id']	=	$id;
							$multiInsertTools[$ctr]['tool_name']	=	$this->Mmm->sanitize($_POST['tool_name'][$ctr]);
							$multiInsertTools[$ctr]['quantity']	=	$this->Mmm->sanitize($_POST['tool_quantity'][$ctr]);
							$multiInsertTools[$ctr]['days_used']	=	$this->Mmm->sanitize($_POST['tool_estimated_days_used'][$ctr]);
						}

					
						$checkMultiInsertTools = $this->Mmm->multiInsert('am_bill_of_materials_tools',$multiInsertTools,'Edited Tools and Equipment details for '.$evaluation->maintenance_form . $evaluation->control_number  ." with BOM No.".$control_number . " under " . $company_name);


						if($checkMultiInsertTasks && $checkMultiInsertLabor && $checkMultiInsertMaterials && $checkMultiInsertTools){
							
								$this->Abas->sysNotif("Edit BOM", $_SESSION['abas_login']['fullname']." has edited Bill Of Materials for " . $evaluation->maintenance_form . $evaluation->control_number ." with BOM No.".$control_number . " under " . $company_name,"Asset Management","info");

								$this->Abas->sysMsg("sucmsg", "Edited BOM for " . $evaluation->maintenance_form . $evaluation->control_number ." with BOM No.".$control_number . " under " . $company_name);

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/BOM/".$id);
							die();
						}
						

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/BOM/".$id);
						die();
					}


					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/BOM/".$id);


				default:
			}

		}
		else{// opens the filled form to update data

			$data = array();

			if(isset($for)){
				$data['for'] = $for;
			}

			switch($type){

				case "evaluation_items":

					$data['index'] = $this->Asset_Management_model->prepareEvaluationItems()['index'];
					$data['evaluation_item'] = $this->Asset_Management_model->getEvaluationItem($id);

					$this->load->view(VIEW.'/repairs_and_maintenance/evaluation_items/form.php',$data);

				break;

				case "WO":
					
					$data['work_order']		=	$this->Asset_Management_model->getWO($id);
					$data['work_order_details']		=	$this->Asset_Management_model->getWODetails($id);

					$data['companies']		=	$this->Asset_Management_model->getCompanies();
					$data['vessels']		=	$this->Asset_Management_model->getVesselsByCompany($data['work_order']['company_id']);
					
					$this->load->view(VIEW.'/repairs_and_maintenance/WO/form.php',$data);

				break;

				case "TRMRF":

					$data['TRMRF']			=	$this->Asset_Management_model->getTRMRF($id);
					$data['TRMRF_details']	=	$this->Asset_Management_model->getTRMRFDetails($id);

					$data['companies']		=	$this->Asset_Management_model->getCompanies();
					$data['trucks']			=	$this->Asset_Management_model->getTrucksByCompany($data['TRMRF']['company_id']);	
					
					$this->load->view(VIEW.'/repairs_and_maintenance/TRMRF/form.php',$data);

				break;

				case "SRMSF":
					
					$data['SRMSF']		=	$this->Asset_Management_model->getSRMSF($id);
					$data['SRMSF_details']		=	$this->Asset_Management_model->getSRMSFDetails($id);

					$data['companies']		=	$this->Asset_Management_model->getCompanies();
					$data['vessels']		=	$this->Asset_Management_model->getVesselsByCompany($data['SRMSF']['company_id']);
					$data['vessel_measurements'] = $this->Asset_Management_model->getVesselMeasurements($data['SRMSF']['vessel_id']);

					$data['WO']				=	$this->Asset_Management_model->getWOs();
					$data['index']['A']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('A','Vessel');
					$data['index']['B']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('B','Vessel');
					$data['index']['C']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('C','Vessel');
					$data['index']['D']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('D','Vessel');
					$data['index']['E']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('E','Vessel');
					$data['index']['F']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('F','Vessel');

					$data['steps'] 			= $this->Asset_Management_model->prepareSRMSF()['steps'];
					$data['ratings']		= $this->Asset_Management_model->prepareSRMSF()['ratings'];
					
					$this->load->view(VIEW.'/repairs_and_maintenance/SRMSF/form.php',$data);

				break;

				case "MTDE":

					$data['MTDE']			=	$this->Asset_Management_model->getMTDE($id);
					$data['MTDE_details']	=	$this->Asset_Management_model->getMTDEDetails($id);

					$data['companies']		=	$this->Asset_Management_model->getCompanies();
					$data['trucks']			=	$this->Asset_Management_model->getTrucksByCompany($data['MTDE']['company_id']);	
					$data['truck_info']		=	$this->Asset_Management_model->getTruckInfo($data['MTDE']['truck_id']);	

					$data['TRMRF']			=	$this->Asset_Management_model->getTRMRFs();
					$data['index']['A']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('A','Truck');
					$data['index']['B']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('B','Truck');
					$data['index']['C']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('C','Truck');
					$data['index']['D']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('D','Truck');
					$data['index']['E']		=	$this->Asset_Management_model->getEvaluationItemsPerIndex('E','Truck');

					$data['steps'] 			= $this->Asset_Management_model->prepareMTDE()['steps'];
					$data['ratings']		= $this->Asset_Management_model->prepareMTDE()['ratings'];

					$this->load->view(VIEW.'/repairs_and_maintenance/MTDE/form.php',$data);

				break;

				case "schedule_logs":

					$data['companies']		=	$this->Asset_Management_model->getCompanies();
					$data['schedule_log'] = $this->Asset_Management_model->getScheduleLog($id); 
					$data['schedule_log_tasks'] = $this->Asset_Management_model->getScheduleLogTasks($id); 
					$data['bill_of_materials'] = $this->Asset_Management_model->getBOMByCompany($data['schedule_log']['type'],$data['schedule_log']['company_id']);

					if($data['schedule_log']['type']=='Vessel'){
						$data['vessel'] = $this->Asset_Management_model->getBOM($data['schedule_log']['bill_of_materials_id']);
						$data['vessel_measurement'] = $this->Asset_Management_model->getVesselMeasurements($data['schedule_log']['asset_id']);
					}else{
						$data['truck'] = $this->Asset_Management_model->getTruckInfo($data['schedule_log']['asset_id']);
					}

					$this->load->view(VIEW.'/schedule_logs/form.php',$data);

				break;

				case "BOM":
					
					$data['BOM']			=	$this->Asset_Management_model->getBOM($id);
					$data['BOM_tasks']		=	$this->Asset_Management_model->getBOMTasks($id);
					$data['BOM_labor']		=	$this->Asset_Management_model->getBOMLabor($id);
					$data['BOM_supplies']	=	$this->Asset_Management_model->getBOMSupplies($id);
					$data['BOM_tools']		=	$this->Asset_Management_model->getBOMTools($id);

					if($data['BOM']['bom_type']=='Vessel'){
						$data['vessel']			=	$this->Asset_Management_model->getSRMSF($data['BOM']['evaluation_id']);
					}else{
						$data['truck']			=	$this->Asset_Management_model->getMTDE($data['BOM']['evaluation_id']);
					}

					$data['companies']		=	$this->Asset_Management_model->getCompanies();

					$this->load->view(VIEW.'/bill_of_materials/form.php',$data);

				break;

			}

		}
	}
	
	public function delete( $type = NULL, $id = NULL ){
			switch($type){

				case "WO":

					$WO = $this->Asset_Management_model->getWO($id);

					$company_name = $WO['company_name'];
					$vessel_name = $WO['vessel_name'];
					$control_number = $WO['control_number'];

					$checkDelete = $this->Asset_Management_model->deleteWO($id);

					if($checkDelete){
						
						$checkDelete = $this->Asset_Management_model->deleteWODetails($id); 
						
						if($checkDelete){

							$this->Abas->sysNotif("Delete WO", $_SESSION['abas_login']['fullname']." has deleted Work Order for vessel " . $vessel_name ." with WO No.".$control_number . " under " .$company_name,"Asset Management","info");

							$this->Abas->sysMsg("sucmsg","Succesfully deleted WO for vessel - " . $vessel_name ." with WO No.".$control_number . " under " .$company_name);

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/WO");

				break;

				case "TRMRF":

					$TRMRF = $this->Asset_Management_model->getTRMRF($id);

					$company_name = $TRMRF['company_name'];
					$plate_number = $TRMRF['plate_number'];
					$control_number = $TRMRF['control_number'];

					$checkDelete = $this->Asset_Management_model->deleteTRMRF($id);

					if($checkDelete){
						
						$checkDelete = $this->Asset_Management_model->deleteTRMRFDetails($id); 
						
						if($checkDelete){

							$this->Abas->sysNotif("Delete TRMRF", $_SESSION['abas_login']['fullname']." has deleted Truck Repairs and Maintenance Report Form for truck " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name,"Asset Management","info");

							$this->Abas->sysMsg("sucmsg","Succesfully deleted TRMRF for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name);

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/TRMRF");

				break;

				case "SRMSF":

					$SRMSF = $this->Asset_Management_model->getSRMSF($id);

					$company_name = $SRMSF['company_name'];
					$vessel_name = $SRMSF['vessel_name'];
					$control_number = $SRMSF['control_number'];

					$checkDelete = $this->Asset_Management_model->deleteSRMSF($id);

					if($checkDelete){
						
						$checkDelete = $this->Asset_Management_model->deleteSRMSFDetails($id); 
						
						if($checkDelete){

							$this->Abas->sysNotif("Delete SRMSF", $_SESSION['abas_login']['fullname']." has deleted Ship Repair and Maintenance Survey Form for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name,"Asset Management","info");

							$this->Abas->sysMsg("sucmsg","Succesfully deleted SRMSF for vessel - " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name);

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/SRMSF");

				break;

				case "MTDE":

					$MTDE = $this->Asset_Management_model->getMTDE($id);

					$company_name = $MTDE['company_name'];
					$plate_number = $MTDE['plate_number'];
					$control_number = $MTDE['control_number'];

					$checkDelete = $this->Asset_Management_model->deleteMTDE($id);

					if($checkDelete){
						
						$checkDelete = $this->Asset_Management_model->deleteMTDEDetails($id); 
						
						if($checkDelete){

							$this->Abas->sysNotif("Delete MTDE", $_SESSION['abas_login']['fullname']." has deleted Motorpool Truck Diagnostic Evaluation for truck " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name,"Asset Management","info");

							$this->Abas->sysMsg("sucmsg","Succesfully deleted MTDE for truck - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name);

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/MTDE");

				break;

				case "evaluation_items":

					$item = $this->Asset_Management_model->getEvaluationItem($id);
					$checkIfUsed = $this->Asset_Management_model->checkEvaluationItemIfUsed($id,$item['type']);

					if($checkIfUsed==FALSE){
						
						$indexing = $item['item_index'] . "." . $item['item_set'] . "." . $item['item_sub_set'] . " for " . $item['type'] . " Maintenance.";

						$checkDelete = $this->Asset_Management_model->deleteEvaluationItem($id);

						if($checkDelete) {
							$this->Abas->sysMsg("sucmsg","Succesfully deleted Evaluation Item - ".$indexing);
						}
						else {
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							die();
						}

					}else{
						$this->Abas->sysNotif("ABAS says", "Cannot delete this Evaluation Item since it is already being used.", "Asset Management", "danger");
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/evaluation_items/".$item['type']);

				break;

				case "BOM":

					$BOM = $this->Asset_Management_model->getBOM($id);

					$company_name = $BOM['company_name'];
					$control_number = $BOM['control_number'];
					$evaluation_id = $BOM['evaluation_id'];
					$company_id = $BOM['company_id'];
					$type = $BOM['bom_type'];

					$evaluation = $this->Asset_Management_model->getEvaluationFormNumber($type,$company_id,$evaluation_id);

					$checkDelete = $this->Asset_Management_model->deleteBOM($id);

					if($checkDelete){
						
						$checkDelete = $this->Asset_Management_model->deleteBOMDetails($id); 
						
						if($checkDelete){

							$this->Abas->sysNotif("Delete BOM", $_SESSION['abas_login']['fullname']." has deleted Bill Of Materials for " . $evaluation->maintenance_form .$evaluation->control_number . " with BOM No." .$control_number . " under " .$company_name,"Asset Management","info");

							$this->Abas->sysMsg("sucmsg","Succesfully deleted BOM for " . $evaluation->maintenance_form .$evaluation->control_number . " with BOM No.".$control_number . " under " .$company_name);

						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
							die();
						}

					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
						die();
					}

					$this->Abas->redirect(HTTP_PATH.CONTROLLER."/listview/BOM/".$type);
					
				break;

				default:
			}
	}

	public function view( $type = NULL, $id = NULL, $for = NULL ){
		
		$data = array();

		if(isset($for)){
			$data['for'] = $for;
		}

		switch($type){

			case "WO":

				$data['WO'] 			= 	$this->Asset_Management_model->getWO($id);
				$data['WO_details'] 	= 	$this->Asset_Management_model->getWODetails($id);	

				$data['viewfile']	=	VIEW."/repairs_and_maintenance/WO/view.php";

			break;

			case "TRMRF":

				$data['TRMRF'] 			= 	$this->Asset_Management_model->getTRMRF($id);
				$data['TRMRF_details'] 	= 	$this->Asset_Management_model->getTRMRFDetails($id);	

				$data['viewfile']	=	VIEW."/repairs_and_maintenance/TRMRF/view.php";

			break;
			
			case "SRMSF":

				$data['SRMSF'] 			= 	$this->Asset_Management_model->getSRMSF($id);
				$data['SRMSF_details'] 	= 	$this->Asset_Management_model->getSRMSFDetails($id);	
				$data['steps'] 			= 	$this->Asset_Management_model->prepareSRMSF()['steps'];

				$data['viewfile']	=	VIEW."/repairs_and_maintenance/SRMSF/view.php";

			break;

			case "MTDE":

				$data['MTDE'] 			= 	$this->Asset_Management_model->getMTDE($id);
				$data['MTDE_details'] 	= 	$this->Asset_Management_model->getMTDEDetails($id);
				$data['steps'] 			= 	$this->Asset_Management_model->prepareMTDE()['steps'];

				$data['viewfile']	=	VIEW."/repairs_and_maintenance/MTDE/view.php";

			break;

			case "schedule_logs":

				$data['schedule_log'] = $this->Asset_Management_model->getScheduleLog($id); 
				$data['schedule_log_tasks'] = $this->Asset_Management_model->getScheduleLogTasks($id); 

				$data['viewfile']	=	VIEW."/schedule_logs/view.php";
					
			break;

			case "BOM":

				$data['BOM'] 			= 	$this->Asset_Management_model->getBOM($id);
				$data['BOM_tasks'] 		= 	$this->Asset_Management_model->getBOMTasks($id);
				$data['BOM_labor'] 		= 	$this->Asset_Management_model->getBOMLabor($id);
				$data['BOM_supplies'] 	= 	$this->Asset_Management_model->getBOMSupplies($id);
				$data['BOM_tools'] 		= 	$this->Asset_Management_model->getBOMTools($id);
				$data['schedule_log'] 	= $this->Asset_Management_model->getScheduleLogBOM($id);

				$data['viewfile']	=	VIEW."/bill_of_materials/view.php";
					
			break;

			default:
				$data['viewfile']	=	VIEW."/monitoring.php";
		}

		$this->load->view('gentlella_container.php',$data);

	}

	public function submit( $type = NULL, $id = NULL ){

		switch($type){

			case "WO":

				$WO = $this->Asset_Management_model->getWO($id);

				$company_name = $WO['company_name'];
				$vessel_name = $WO['vessel_name'];
				$control_number = $WO['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateWOStatus($id,'For Verification');

				if($checkUpdate){
					$this->Abas->sysNotif("Submit WO", $_SESSION['abas_login']['fullname']." has submitted Work Order for vessel " . $vessel_name ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully submitted WO for vessel " . $vessel_name ." with Control No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/WO/".$id);
				
			break;

			case "TRMRF":

				$TRMRF = $this->Asset_Management_model->getTRMRF($id);

				$company_name = $TRMRF['company_name'];
				$plate_number = $TRMRF['plate_number'];
				$control_number = $TRMRF['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateTRMRFStatus($id,'For Verification');

				if($checkUpdate){
					$this->Abas->sysNotif("Submit TRMRF", $_SESSION['abas_login']['fullname']." has submitted Truck Repairs and Maintenance Report Form for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully submitted TRMRF for vessel - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/TRMRF/".$id);
				
			break;
			
			case "SRMSF":

				$SRMSF = $this->Asset_Management_model->getSRMSF($id);

				$company_name = $SRMSF['company_name'];
				$vessel_name = $SRMSF['vessel_name'];
				$control_number = $SRMSF['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateSRMSFStatus($id,'For Verification');

				if($checkUpdate){
					$this->Abas->sysNotif("Submit SRMSF", $_SESSION['abas_login']['fullname']." has submitted Ship Repair and Maintenance Survey Form for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully submitted SRMSF for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/SRMSF/".$id);
				
			break;

			case "MTDE":

				$MTDE = $this->Asset_Management_model->getMTDE($id);

				$company_name = $MTDE['company_name'];
				$plate_number = $MTDE['plate_number'];
				$control_number = $MTDE['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateMTDEStatus($id,'For Verification');

				if($checkUpdate){
					$this->Abas->sysNotif("Submit MTDE", $_SESSION['abas_login']['fullname']." has submitted Motorpool Truck Diagnostic Evaluation for truck - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully submitted MTDE for vessel - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/MTDE/".$id);
				
			break;

			case "schedule_logs":

				$schedule_logs = $this->Asset_Management_model->getScheduleLog($id);

				$checkUpdate = $this->Asset_Management_model->updateScheduleLogStatus($id,'For Verification');

				if($checkUpdate){

					if($schedule_logs['type']=='Vessel'){

						$company_name = $schedule_logs['company_name'];
						$vessel_name = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Submit Schedule Log", $_SESSION['abas_login']['fullname']." has submitted Dry-Dock Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully submitted Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name);

					}elseif($schedule_logs['type']=='Truck'){

						$company_name = $schedule_logs['company_name'];
						$plate_number = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Submit Schedule Log", $_SESSION['abas_login']['fullname']." has submitted Motorpool Repairs and Maintenance Schedule Log for truck - " . $plate_number ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully submitted Schedule Log for vessel - " . $plate_number ." with Control No.".$control_number . " under " .$company_name);

					}

					
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}


				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/schedule_logs/".$id);
					
			break;

			case "BOM":

				$BOM = $this->Asset_Management_model->getBOM($id);

				$company_name = $BOM['company_name'];
				$control_number = $BOM['control_number'];
				$evaluation_id = $BOM['evaluation_id'];
				$company_id = $BOM['company_id'];
				$type = $BOM['bom_type'];

				$evaluation = $this->Asset_Management_model->getEvaluationFormNumber($type,$company_id,$evaluation_id);
				
				$checkUpdate = $this->Asset_Management_model->updateBOMStatus($id,'For Verification');

				if($checkUpdate){
					$this->Abas->sysNotif("Submit BOM", $_SESSION['abas_login']['fullname']." has submitted Bill Of Materials for " . $evaluation->maintenance_form.$evaluation->control_number ." with BOM No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully submitted BOM for " . $evaluation->maintenance_form.$evaluation->control_number ." with BOM No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/BOM/".$id);
					
			break;

			default:
				
		}

	}


	public function verify( $type = NULL, $id = NULL ){

		switch($type){

			case "WO":

				$WO = $this->Asset_Management_model->getWO($id);

				$company_name = $WO['company_name'];
				$vessel_name = $WO['vessel_name'];
				$control_number = $WO['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateWOStatus($id,'For Approval');

				if($checkUpdate){
					$this->Abas->sysNotif("Verify WO", $_SESSION['abas_login']['fullname']." has verified Work Order for vessel " . $vessel_name ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully verified WO for vessel " . $vessel_name ." with Control No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/WO/".$id);
				
			break;


			case "TRMRF":

				$TRMRF = $this->Asset_Management_model->getTRMRF($id);

				$company_name = $TRMRF['company_name'];
				$plate_number = $TRMRF['plate_number'];
				$control_number = $TRMRF['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateTRMRFStatus($id,'For Approval');

				if($checkUpdate){
					$this->Abas->sysNotif("Verify TRMRF", $_SESSION['abas_login']['fullname']." has verified Truck Repairs and Maintenance Report Form for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully verified TRMRF for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/TRMRF/".$id);
				
			break;
			
			case "SRMSF":

				$SRMSF = $this->Asset_Management_model->getSRMSF($id);

				$company_name = $SRMSF['company_name'];
				$vessel_name = $SRMSF['vessel_name'];
				$control_number = $SRMSF['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateSRMSFStatus($id,'For Approval');

				if($checkUpdate){
					$this->Abas->sysNotif("Verify SRMSF", $_SESSION['abas_login']['fullname']." has verified Ship Repair and Maintenance Survey Form for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully verified SRMSF for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/SRMSF/".$id);
				
			break;

			case "MTDE":

				$MTDE = $this->Asset_Management_model->getMTDE($id);

				$company_name = $MTDE['company_name'];
				$plate_number = $MTDE['plate_number'];
				$control_number = $MTDE['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateMTDEStatus($id,'For Approval');

				if($checkUpdate){
					$this->Abas->sysNotif("Verify MTDE", $_SESSION['abas_login']['fullname']." has verified Motorpool Truck Diagnostic Evaluation for truck - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully verified MTDE for truck - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/MTDE/".$id);
				
			break;

			case "schedule_logs":

				$schedule_logs = $this->Asset_Management_model->getScheduleLog($id);

				$checkUpdate = $this->Asset_Management_model->updateScheduleLogStatus($id,'For Approval');

				if($checkUpdate){

					if($schedule_logs['type']=='Vessel'){

						$company_name = $schedule_logs['company_name'];
						$vessel_name = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Verify Schedule Log", $_SESSION['abas_login']['fullname']." has verified Dry-Dock Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully verified Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name);

					}elseif($schedule_logs['type']=='Truck'){

						$company_name = $schedule_logs['company_name'];
						$plate_number = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Verify Schedule Log", $_SESSION['abas_login']['fullname']." has verified Motorpool Repairs and Maintenance Schedule Log for truck - " . $plate_number ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully verified Schedule Log for truck - " . $plate_number ." with Control No.".$control_number . " under " .$company_name);

					}

					
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}


				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/schedule_logs/".$id);
					
			break;

			case "BOM":

				$BOM = $this->Asset_Management_model->getBOM($id);

				$company_name = $BOM['company_name'];
				$control_number = $BOM['control_number'];
				$evaluation_id = $BOM['evaluation_id'];
				$company_id = $BOM['company_id'];
				$type = $BOM['bom_type'];

				$evaluation = $this->Asset_Management_model->getEvaluationFormNumber($type,$company_id,$evaluation_id);
				
				$checkUpdate = $this->Asset_Management_model->updateBOMStatus($id,'For Approval');

				if($checkUpdate){
					$this->Abas->sysNotif("Verify BOM", $_SESSION['abas_login']['fullname']." has verified Bill Of Materials for " . $evaluation->maintenance_form.$evaluation->control_number ." with BOM No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully verified BOM for " . $evaluation->maintenance_form.$evaluation->control_number ." with BOM No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/BOM/".$id);
					
			break;

			default:
				
		}

	}

	public function approve( $type = NULL, $id = NULL ){

		switch($type){

			case "WO":

				$WO = $this->Asset_Management_model->getWO($id);

				$company_name = $WO['company_name'];
				$vessel_name = $WO['vessel_name'];
				$control_number = $WO['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateWOStatus($id,'Approved');

				if($checkUpdate){
					$this->Abas->sysNotif("Approve WO", $_SESSION['abas_login']['fullname']." has approved Work Order for vessel " . $vessel_name ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully approved WO for vessel " . $vessel_name ." with Control No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/WO/".$id);
				
			break;


			case "TRMRF":

				$TRMRF = $this->Asset_Management_model->getTRMRF($id);

				$company_name = $TRMRF['company_name'];
				$plate_number = $TRMRF['plate_number'];
				$control_number = $TRMRF['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateTRMRFStatus($id,'Approved');

				if($checkUpdate){
					$this->Abas->sysNotif("Approve TRMRF", $_SESSION['abas_login']['fullname']." has approved Truck Repairs and Maintenance Report Form for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully approved TRMRF for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/TRMRF/".$id);
				
			break;
			
			case "SRMSF":

				$SRMSF = $this->Asset_Management_model->getSRMSF($id);

				$company_name = $SRMSF['company_name'];
				$vessel_name = $SRMSF['vessel_name'];
				$control_number = $SRMSF['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateSRMSFStatus($id,'Approved');

				if($checkUpdate){
					$this->Abas->sysNotif("Approve SRMSF", $_SESSION['abas_login']['fullname']." has approved Ship Repair and Maintenance Survey Form for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully approved SRMSF for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/SRMSF/".$id);
				
			break;

			case "MTDE":

				$MTDE = $this->Asset_Management_model->getMTDE($id);

				$company_name = $MTDE['company_name'];
				$plate_number = $MTDE['plate_number'];
				$control_number = $MTDE['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateMTDEStatus($id,'Approved');

				if($checkUpdate){
					$this->Abas->sysNotif("Approve MTDE", $_SESSION['abas_login']['fullname']." has approved Motorpool Truck Diagnostic Evaluation for truck - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully approved MTDE for truck - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/MTDE/".$id);
				
			break;

			case "schedule_logs":

				$schedule_logs = $this->Asset_Management_model->getScheduleLog($id);

				$checkUpdate = $this->Asset_Management_model->updateScheduleLogStatus($id,'Approved');

				if($checkUpdate){

					if($schedule_logs['type']=='Vessel'){

						$company_name = $schedule_logs['company_name'];
						$vessel_name = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Approve Schedule Log", $_SESSION['abas_login']['fullname']." has approved Dry-Dock Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully verified Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name);

					}elseif($schedule_logs['type']=='Truck'){

						$company_name = $schedule_logs['company_name'];
						$plate_number = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Approve Schedule Log", $_SESSION['abas_login']['fullname']." has approved Motorpool Repairs and Maintenance Schedule Log for truck - " . $plate_number ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully verified Schedule Log for truck - " . $plate_number ." with Control No.".$control_number . " under " .$company_name);

					}

					
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}


				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/schedule_logs/".$id);
					
			break;

			case "BOM":

				$BOM = $this->Asset_Management_model->getBOM($id);

				$company_name = $BOM['company_name'];
				$control_number = $BOM['control_number'];
				$evaluation_id = $BOM['evaluation_id'];
				$company_id = $BOM['company_id'];
				$type = $BOM['bom_type'];

				$evaluation = $this->Asset_Management_model->getEvaluationFormNumber($type,$company_id,$evaluation_id);
				
				$checkUpdate = $this->Asset_Management_model->updateBOMStatus($id,'Approved');

				if($checkUpdate){
					$this->Abas->sysNotif("Approve BOM", $_SESSION['abas_login']['fullname']." has approved Bill Of Materials for " . $evaluation->maintenance_form.$evaluation->control_number ." with BOM No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully approved BOM for " . $evaluation->maintenance_form.$evaluation->control_number ." with BOM No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/BOM/".$id);
					
			break;

			default:
				
		}

	}

	public function returnToDraft( $type = NULL, $id = NULL ){

		switch($type){

			case "schedule_logs":
				$schedule_logs = $this->Asset_Management_model->getScheduleLog($id);

				$checkUpdate = $this->Asset_Management_model->updateScheduleLogStatus($id,'Draft');

				if($checkUpdate){

					if($schedule_logs['type']=='Vessel'){

						$company_name = $schedule_logs['company_name'];
						$vessel_name = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Return Schedule Log", $_SESSION['abas_login']['fullname']." has been returned Dry-Dock Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name." to 'Draft' status.","Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully returned Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name. " to 'Draft'.");

					}elseif($schedule_logs['type']=='Truck'){

						$company_name = $schedule_logs['company_name'];
						$plate_number = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Return Schedule Log", $_SESSION['abas_login']['fullname']." has cancelled Motorpool Repairs and Maintenance Schedule Log for truck - " . $plate_number ." with Control No.".$control_number . " under " .$company_name. " to 'Draft' status.","Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully returned Schedule Log for vessel - " . $plate_number ." with Control No.".$control_number . " under " .$company_name. " to 'Draft'.");

					}

				}else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/schedule_logs/".$id);

				break;
		}
	}

	public function cancel( $type = NULL, $id = NULL ){

		switch($type){

			case "WO":

				$WO = $this->Asset_Management_model->getWO($id);

				$company_name = $WO['company_name'];
				$vessel_name = $WO['vessel_name'];
				$control_number = $WO['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateWOStatus($id,'Cancelled');

				if($checkUpdate){
					$this->Abas->sysNotif("Cancel WO", $_SESSION['abas_login']['fullname']." has cancelled Work Order for vessel " . $vessel_name ." with WO No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully cancelled WO for vessel " . $vessel_name ." with WO No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/WO/".$id);

			break;

			case "TRMRF":

				$TRMRF = $this->Asset_Management_model->getTRMRF($id);

				$company_name = $TRMRF['company_name'];
				$plate_number = $TRMRF['plate_number'];
				$control_number = $TRMRF['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateTRMRFStatus($id,'Cancelled');

				if($checkUpdate){
					$this->Abas->sysNotif("Cancel TRMRF", $_SESSION['abas_login']['fullname']." has cancelled Truck Repairs and Maintenance Report Form for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully cancelled TRMRF for truck - " . $plate_number ." with TRMRF No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/TRMRF/".$id);
				
			break;

			
			case "SRMSF":

				$SRMSF = $this->Asset_Management_model->getSRMSF($id);

				$company_name = $SRMSF['company_name'];
				$vessel_name = $SRMSF['vessel_name'];
				$control_number = $SRMSF['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateSRMSFStatus($id,'Cancelled');

				if($checkUpdate){
					$this->Abas->sysNotif("Cancel SRMSF", $_SESSION['abas_login']['fullname']." has cancelled Ship Repair and Maintenance Survey Form for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully cancelled SRMSF for vessel " . $vessel_name ." with SRMSF No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/SRMSF/".$id);

			break;

			case "MTDE":

				$MTDE = $this->Asset_Management_model->getMTDE($id);

				$company_name = $MTDE['company_name'];
				$plate_number = $MTDE['plate_number'];
				$control_number = $MTDE['control_number'];
				
				$checkUpdate = $this->Asset_Management_model->updateMTDEStatus($id,'Cancelled');

				if($checkUpdate){
					$this->Abas->sysNotif("Cancel MTDE", $_SESSION['abas_login']['fullname']." has cancelled Motorpool Truck Diagnostic Evaluation for truck - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully cancelled MTDE for truck - " . $plate_number ." with MTDE No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/MTDE/".$id);
				
			break;

			case "schedule_logs":

				$schedule_logs = $this->Asset_Management_model->getScheduleLog($id);

				$checkUpdate = $this->Asset_Management_model->updateScheduleLogStatus($id,'Cancelled');

				if($checkUpdate){

					if($schedule_logs['type']=='Vessel'){

						$company_name = $schedule_logs['company_name'];
						$vessel_name = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Submit Schedule Log", $_SESSION['abas_login']['fullname']." has cancelled Dry-Dock Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully cancelled Schedule Log for vessel - " . $vessel_name ." with Control No.".$control_number . " under " .$company_name);

					}elseif($schedule_logs['type']=='Truck'){

						$company_name = $schedule_logs['company_name'];
						$plate_number = $schedule_logs['asset_name'];
						$control_number = $schedule_logs['control_number'];

						$this->Abas->sysNotif("Submit Schedule Log", $_SESSION['abas_login']['fullname']." has cancelled Motorpool Repairs and Maintenance Schedule Log for truck - " . $plate_number ." with Control No.".$control_number . " under " .$company_name,"Asset Management","info");

						$this->Abas->sysMsg("sucmsg","Succesfully cancelled Schedule Log for vessel - " . $plate_number ." with Control No.".$control_number . " under " .$company_name);

					}

					
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}


				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/schedule_logs/".$id);
					
			break;

			case "BOM":

				$BOM = $this->Asset_Management_model->getBOM($id);

				$company_name = $BOM['company_name'];
				$control_number = $BOM['control_number'];
				$evaluation_id = $BOM['evaluation_id'];
				$company_id = $BOM['company_id'];
				$type = $BOM['bom_type'];

				$evaluation = $this->Asset_Management_model->getEvaluationFormNumber($type,$company_id,$evaluation_id);
				
				$checkUpdate = $this->Asset_Management_model->updateBOMStatus($id,'Cancelled');

				if($checkUpdate){
					$this->Abas->sysNotif("Cancel BOM", $_SESSION['abas_login']['fullname']." has cancelled Bill Of Materials for " . $evaluation->maintenance_form.$evaluation->control_number ." with BOM No.".$control_number . " under " .$company_name,"Asset Management","info");

					$this->Abas->sysMsg("sucmsg","Succesfully cancelled BOM for " . $evaluation->maintenance_form.$evaluation->control_number ." with BOM No.".$control_number . " under " .$company_name);
				}
				else{
					$this->Abas->sysMsg("errmsg", "An error has occurred! Please try again.");
					die();
				}

				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/view/BOM/".$id);
					
			break;

			default:
				
		}



	}

	public function prints( $type = NULL, $id = NULL ){

		require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

		switch($type){
			
			case "WO":
				
				$data['WO'] 			= 	$this->Asset_Management_model->getWO($id);
				$data['WO_details'] 	= 	$this->Asset_Management_model->getWODetails($id);

				$this->load->view(VIEW."/repairs_and_maintenance/WO/print.php",$data);

			break;

			case "TRMRF":
				
				$data['TRMRF'] 			= 	$this->Asset_Management_model->getTRMRF($id);
				$data['TRMRF_details'] 	= 	$this->Asset_Management_model->getTRMRFDetails($id);

				$this->load->view(VIEW."/repairs_and_maintenance/TRMRF/print.php",$data);

			break;

			case "SRMSF":
				
				$data['SRMSF'] 			= 	$this->Asset_Management_model->getSRMSF($id);
				$data['SRMSF_details'] 	= 	$this->Asset_Management_model->getSRMSFDetails($id);
				$data['steps']			= 	$this->Asset_Management_model->prepareSRMSF()['steps'];	

				$this->load->view(VIEW."/repairs_and_maintenance/SRMSF/print.php",$data);

			break;

			case "MTDE":

				$data['MTDE'] 			= 	$this->Asset_Management_model->getMTDE($id);
				$data['MTDE_details'] 	= 	$this->Asset_Management_model->getMTDEDetails($id);
				$data['steps']			= 	$this->Asset_Management_model->prepareMTDE()['steps'];

				$this->load->view(VIEW."/repairs_and_maintenance/MTDE/print.php",$data);
				
			break;

			case "BOM_Details":

				$data['BOM'] 			= 	$this->Asset_Management_model->getBOM($id);
				$data['BOM_tasks'] 		= 	$this->Asset_Management_model->getBOMTasks($id);
				$data['BOM_labor'] 		= 	$this->Asset_Management_model->getBOMLabor($id);
				$data['BOM_supplies'] 	= 	$this->Asset_Management_model->getBOMSupplies($id);
				$data['BOM_tools'] 		= 	$this->Asset_Management_model->getBOMTools($id);

				$data['summary'] 		=	FALSE;

				$this->load->view(VIEW."/bill_of_materials/print.php",$data);
					
			break;

			case "schedule_logs":

				$this->load->view(VIEW."/schedule_logs/print.php",$data);
					
			break;

			case "BOM_Summary":

				$data['BOM'] 			= 	$this->Asset_Management_model->getBOM($id);
				$data['BOM_tasks'] 		= 	$this->Asset_Management_model->getBOMTasks($id);
				$data['BOM_labor'] 		= 	$this->Asset_Management_model->getBOMLabor($id);
				$data['BOM_supplies'] 	= 	$this->Asset_Management_model->getBOMSupplies($id);
				$data['BOM_tools'] 		= 	$this->Asset_Management_model->getBOMTools($id);

				$data['summary'] 		=	TRUE;

				$this->load->view(VIEW."/bill_of_materials/print.php",$data);
					
			break;

			default:
				
		}

	}

	public function load( $table = NULL, $filter = NULL ){

		$data = array();

		if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

			$search	=	isset($_GET['search'])?$_GET['search']:"";
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";

			$search =	isset($filter)?$filter:"";
			$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);

			foreach($data['rows'] as $ctr=>$row){
				if(isset($row['company_id'])){
					$company		=	$this->Asset_Management_model->getCompany($row['company_id']);
					$data['rows'][$ctr]['company_id']	=	$company['name'];
				}
				if(isset($row['vessel_id'])){
					$vessel			=	$this->Asset_Management_model->getVessel($row['vessel_id']);
					$data['rows'][$ctr]['vessel_id']	=	$vessel['name'];
				}
				if(isset($row['truck_id'])){
					$truck			=	$this->Asset_Management_model->getTruck($row['truck_id']);
					$data['rows'][$ctr]['truck_id']	=	$truck['plate_number'];
				}
				if(isset($row['dry_docking_date'])){
					$data['rows'][$ctr]['dry_docking_date']	=	date("j F Y", strtotime($row['dry_docking_date']));
				}
				if(isset($row['start_date_of_repair'])){
					$data['rows'][$ctr]['start_date_of_repair']	=	date("j F Y", strtotime($row['start_date_of_repair']));
					if($row['id']<>''){
						$bom_amount = $this->Asset_Management_model->getBOMAmount($row['id']);
						if(isset($bom_amount)){
							$data['rows'][$ctr]['bom_amount'] = number_format($bom_amount,2,'.',',');
						}else{
							$data['rows'][$ctr]['bom_amount'] = "";
						}
					}
				}
				if(isset($row['evaluation_id'])){
					$evaluation	= $this->Asset_Management_model->getEvaluationFormNumber($row['bom_type'],$row['company_id'],$row['evaluation_id']);

					$data['rows'][$ctr]['evaluation_id'] = $evaluation->maintenance_form . $evaluation->control_number;
					$data['rows'][$ctr]['asset_name'] = $evaluation->asset_name;

				}
				if(isset($row['bill_of_materials_id'])){
			
					$bom = $this->Asset_Management_model->getBOM($row['bill_of_materials_id']);
					
					if($filter=="Vessel"){
						$eval = $this->Asset_Management_model->getSRMSF($bom['evaluation_id']);
						if($eval['WO_number']==0 || !isset($eval)){
							$report_form_no = "N/A";
						}else{
							$report_form_no = $eval['WO_number'];
						}
						$data['rows'][$ctr]['report_form_no'] = $report_form_no;
						$data['rows'][$ctr]['evaluation_form_no'] = $eval['control_number'];
						$data['rows'][$ctr]['bill_of_materials_no'] = $bom['control_number'];
						$data['rows'][$ctr]['asset_name'] = $this->Asset_Management_model->getVessel($row['asset_id'])['name'];
					
					}elseif($filter=="Truck"){
						$eval = $this->Asset_Management_model->getMTDE($bom['evaluation_id']);
						if($eval['TRMRF_number']==0 || !isset($eval)){
							$report_form_no = "N/A";
						}else{
							$report_form_no = $eval['TRMRF_number'];
						}
						$data['rows'][$ctr]['report_form_no'] = $report_form_no;
						$data['rows'][$ctr]['evaluation_form_no'] = $eval['control_number'];
						$data['rows'][$ctr]['bill_of_materials_no'] = $bom['control_number'];
						$data['rows'][$ctr]['asset_name'] = $this->Asset_Management_model->getTruck($row['asset_id'])['plate_number'];
					
					}
					

				}
				if(isset($row['created_on'])){
					$data['rows'][$ctr]['created_on']	=	date("j F Y h:i:s A", strtotime($row['created_on']));
				}
				if(isset($row['created_by'])){
					$created_by		=	$this->Asset_Management_model->getUser($row['created_by']);
					$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
				}
				if(isset($row['ask_spec'])){
					if($row['ask_spec']==1){
						$val = "Yes";
					}else{
						$val = "No";
					}
					$data['rows'][$ctr]['ask_spec']	= $val;
				}
				if(isset($row['enabled'])){
					if($row['enabled']==1){
						$val = "Yes";
					}else{
						$val = "No";
					}
					$data['rows'][$ctr]['enabled']	= $val;
				}
				if(isset($row['bill_of_materials_id'])){
					$overall_percentage		=	$this->Asset_Management_model->getScheduleLogOverallPercentage($row['id']);
					$data['rows'][$ctr]['overall_percentage']	=	number_format($overall_percentage,2,'.','')."%";
				}

			}
		}

		header('Content-Type: application/json');
		echo json_encode($data);

		exit();

	}

	public function listview( $type = NULL, $for = NULL ){

		$data = array();

		switch($type){

			case "WO":
				$this->Abas->checkPermissions("asset_management|view_vessels");
				$data['viewfile']	=	VIEW."/repairs_and_maintenance/WO/listview.php";
			break;

			case "TRMRF":
				$this->Abas->checkPermissions("asset_management|view_trucks");
				$data['viewfile']	=	VIEW."/repairs_and_maintenance/TRMRF/listview.php";
			break;
			
			case "SRMSF":
				$this->Abas->checkPermissions("asset_management|view_vessels");
				$data['viewfile']	=	VIEW."/repairs_and_maintenance/SRMSF/listview.php";
			break;

			case "MTDE":
				$this->Abas->checkPermissions("asset_management|view_trucks");
				$data['viewfile']	=	VIEW."/repairs_and_maintenance/MTDE/listview.php";
			break;

			case "evaluation_items":

				if($for=="Vessel"){
					$this->Abas->checkPermissions("asset_management|view_vessels");
				}elseif($for=="Truck"){
					$this->Abas->checkPermissions("asset_management|view_trucks");
				}

				$data['for']		=	$for;
				$data['viewfile']	=	VIEW."/repairs_and_maintenance/evaluation_items/listview.php";
			break;

			case "schedule_logs":

				if($for=="Vessel"){
					$this->Abas->checkPermissions("asset_management|view_vessels");
				}elseif($for=="Truck"){
					$this->Abas->checkPermissions("asset_management|view_trucks");
				}

				$data['for']		=	$for;
				$data['viewfile']	=	VIEW."/schedule_logs/listview.php";
			break;

			case "BOM":

				if($for=="Vessel"){
					$this->Abas->checkPermissions("asset_management|view_vessels");
				}elseif($for=="Truck"){
					$this->Abas->checkPermissions("asset_management|view_trucks");
				}

				$data['for']		=	$for;
				$data['viewfile']	=	VIEW."/bill_of_materials/listview.php";
			break;
		}



		$this->load->view('gentlella_container.php',$data);

	}

	public function view_gantt_chart($id){

		$data['schedule_log'] = $this->Asset_Management_model->getScheduleLog($id); 
		$data['schedule_log_tasks'] = $this->Asset_Management_model->getScheduleLogTasks($id); 

		$this->load->view(VIEW."/schedule_logs/gantt_chart.php",$data);
	}

	public function view_project_orders($schedule_log_id){

		$query = $this->db->query("SELECT DISTINCT bill_of_materials_id FROM am_schedule_log_tasks WHERE schedule_log_id=".$schedule_log_id);
		$boms = $query->result();
		$ctr=0;
	
		foreach($boms as $ctr=>$bom){
			$data['boms'][$ctr] =  $this->Asset_Management_model->getBOM($bom->bill_of_materials_id);
			$ctr++;
		}

		$data['schedule_log'] = $this->Asset_Management_model->getScheduleLog($schedule_log_id);

		$data['purchase_orders'] = $this->Asset_Management_model->getProjectOrders($schedule_log_id,'Purchase Orders');
		$data['job_orders'] 	 = $this->Asset_Management_model->getProjectOrders($schedule_log_id,'Job Orders');

		$data['viewfile']		 = VIEW."/schedule_logs/project_orders.php";
		$this->load->view("gentlella_container.php",$data);

	}

	//--------------For AJAX fields--------------------------------------------------------------
	public function vessel_measurements( $vessel_id ){
		$data['measurements'] = $this->Asset_Management_model->getVesselMeasurements($vessel_id);
		echo json_encode( $data['measurements'] );
	}

	public function vessels_by_company( $company_id ){
		$data['vessels'] = $this->Asset_Management_model->getVesselsByCompany($company_id);
		echo json_encode( $data['vessels'] );
	}

	public function truck_info( $truck_id ){
		$data['info'] = $this->Asset_Management_model->getTruckInfo($truck_id);
		echo json_encode( $data['info'] );
	}

	public function trucks_by_company( $company_id ){
		$data['trucks'] = $this->Asset_Management_model->getTrucksByCompany($company_id);
		echo json_encode( $data['trucks'] );
	}

	public function control_number_by_company($table_name, $company_id ){
		$data['control_number'] = $this->Abas->getNextSerialNumber($table_name,$company_id);
		echo json_encode( $data['control_number'] );
	}

	public function check_indexing( $type, $item_index, $item_set, $item_sub_set, $item_id){
		$data['chk_indexing'] = $this->Asset_Management_model->checkIndexing($type, $item_index, $item_set, $item_sub_set,$item_id);
		echo json_encode( $data['chk_indexing'] );
	}

	public function maintenance_form_by_company( $type, $company_id, $id = "" ){
		$data['ctrl_no_per_company'] = $this->Asset_Management_model->getEvaluationFormNumber($type,$company_id,$id);
		echo json_encode( $data['ctrl_no_per_company']);
	}

	public function bom_by_company( $type, $company_id, $id = "" ){
		$data['bom_by_company'] = $this->Asset_Management_model->getBOMByCompany($type,$company_id,$id);
		echo json_encode( $data['bom_by_company']);
	}

	public function bom_tasks_list( $id ){
		$data['bom_tasks_list'] = $this->Asset_Management_model->getBOMTasks($id);
		echo json_encode( $data['bom_tasks_list']);
	}


	public function maintenance_info( $type, $id ){
		if($type=="Vessel"){
			$data['repair_info'] = $this->Asset_Management_model->getSRMSF($id);
		}elseif($type=="Truck"){
			$data['repair_info'] = $this->Asset_Management_model->getMTDE($id);	
		}
		echo json_encode( $data['repair_info']);
	}


	public function bom_info( $type, $id ){

		$bom = $this->Asset_Management_model->getBOM($id);

		if($type=="Vessel"){
			$data['repair_info'] = $this->Asset_Management_model->getSRMSF($bom['evaluation_id']);
			$data['repair_info']['start_date_of_repair'] = $bom['start_date_of_repair'];
		}elseif($type=="Truck"){
			$data['repair_info'] = $this->Asset_Management_model->getMTDE($bom['evaluation_id']);	
			$data['repair_info']['start_date_of_repair'] = $bom['start_date_of_repair'];
		}
		echo json_encode( $data['repair_info']);
	}

	public function auto_complete_item_search(){
		$keyword = $this->Mmm->sanitize($_GET['term']);
		$data = $this->Asset_Management_model->getItemBySearch($keyword);
		echo json_encode($data);
	}

	public function inventory_item_info( $id ){
		$data['info'] = $this->Asset_Management_model->getInventoryItem($id);
		echo json_encode($data['info']);
	}

	public function check_bom( $id, $type ){
		$data['chk_bom'] = $this->Asset_Management_model->checkMaintenanceFormIfUsed($id,$type);
		echo json_encode($data);
	}

	public function work_order_info( $id ){
		$data['info']	=	$this->Asset_Management_model->getWO($id);
		echo json_encode($data['info']);
	}

	public function truck_repairs_info( $id ){
		$data['info']	=	$this->Asset_Management_model->getTRMRF($id);
		echo json_encode($data['info']);
	}

	public function check_schedule_log_bom($id){
		$data['chk_bom2'] = $this->Asset_Management_model->getScheduleLogBOM($id);
		echo json_encode($data);
	}

	//Fixed Asset Register
	public function fixed_asset_register($action,$id=NULL){
		$data = array();
		switch ($action) {
			case 'load':
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$data	=	$this->Abas->createBSTable('am_fixed_assets',$search,$limit,$offset,$order,$sort);

					foreach($data['rows'] as $ctr=>$row){
						if(isset($row['company_id'])){
							$company		=	$this->Abas->getCompany($row['company_id']);
							$data['rows'][$ctr]['company_name']	=	$company->name;
						}
						if(isset($row['department_id'])){
							$department		=	$this->Abas->getDepartment($row['department_id']);
							$data['rows'][$ctr]['department_name']	=	$department->name;
						}
						if(isset($row['location'])){
							$vessel		=	$this->Abas->getVessel($row['location']);
							$data['rows'][$ctr]['vessel_name']	=	$vessel->name;
						}
						if(isset($row['category_id'])){
							$category		=	$this->Inventory_model->getCategory($row['category_id']);
							$data['rows'][$ctr]['category_name']	=	$category->category;
						}
						if(isset($row['asset_code'])){
							$data['rows'][$ctr]['asset_code']	=	$row['asset_code']."-".$row['control_number'];
						}
						if(isset($row['created_on'])){
							$data['rows'][$ctr]['created_on']	=	date("j F Y h:i:s A", strtotime($row['created_on']));
						}
						if(isset($row['created_by'])){
							$created_by		=	$this->Abas->getUser($row['created_by']);
							$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
						}
						if(isset($row['modified_on'])){
							$data['rows'][$ctr]['modified_on']	=	date("j F Y h:i:s A", strtotime($row['modified_on']));
						}
						if(isset($row['modified_by'])){
							$modified_by		=	$this->Abas->getUser($row['modified_by']);
							$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
						}
						if($row['status']=='Assigned'){
							$accountable1 = $this->Asset_Management_model->getAccountableForIssuedAsset($row['id']);
							if($accountable1){
								$accountable2 = $this->Asset_Management_model->getAccountabilityForm($accountable1->accountability_id);
								$data['rows'][$ctr]['accountable_to']	=	$accountable2->requested_by;
							}
						}
					}
				}	
				
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			break;

			case 'listview':
				$data['viewfile'] = 'asset_management/fixed_asset_register/listview.php';
				$this->load->view('gentlella_container.php',$data);
			break;

			case 'add':
				$data['companies'] = $this->Abas->getCompanies();
				$data['locations']  = $this->Abas->getVessels();
				$data['departments']  = $this->Abas->getDepartments();
				$data['units']  = $this->Abas->getItemUnit();
				$data['categories']  = $this->Abas->getItemCategory();
				//$this->Mmm->debug($data);
				$this->load->view(VIEW.'/fixed_asset_register/form.php',$data);
			break;

			case 'insert':
				if($_POST['asset_code']){
					$picture ='';
					$insert = array();
					$item_id = $this->Mmm->sanitize($_POST['item_id']);
					if(isset($item_id)){
						$insert['item_id']			=   $this->Mmm->sanitize($_POST['item_id']);
					}else{
						$insert['item_id']			= 	0;
					}
					$insert['company_id']		=	$this->Mmm->sanitize($_POST['company']);
					$insert['item_name']		=	ucfirst($this->Mmm->sanitize($_POST['item_name']));
					$insert['particular']		=	ucfirst($this->Mmm->sanitize($_POST['particular']));
					$insert['unit']				=	$this->Mmm->sanitize($_POST['unit']);
					$insert['purchase_cost']	=	$this->Mmm->sanitize($_POST['unit_price']);
					$insert['category_id']		=	$this->Mmm->sanitize($_POST['category']);
					$insert['location']			=	$this->Mmm->sanitize($_POST['location']);
					$insert['department_id']	=	$this->Mmm->sanitize($_POST['department']);
					$insert['date_acquired']	=	$this->Mmm->sanitize($_POST['date_acquired']);
					$insert['useful_life']		=	$this->Mmm->sanitize($_POST['useful_life']);
					$insert['description']		=	ucfirst($this->Mmm->sanitize($_POST['description']));
					$insert['control_number']	=	$this->Abas->getNextSerialNumber('am_fixed_assets',$this->Mmm->sanitize($_POST['company']));
					$insert['asset_code']		=	$this->Mmm->sanitize($_POST['asset_code']);
					$insert['status']			=	"Unassigned";
					$insert['include_lapsing']			=	$_POST['include_lapsing'];
					$insert['created_by']		=	$_SESSION['abas_login']['userid'];
					$insert['created_on']		=	date("Y-m-d H:i:s");
					$insert['stat']				=	1;

					$checkInsert	=	$this->Mmm->dbInsert("am_fixed_assets",$insert,"Added New Fixed Asset (".$insert['item_name'].") with Asset Code:".$insert['asset_code'].'-'.$insert['control_number']);
					if($checkInsert){
						$this->Abas->sysNotif("Fixed Asset Register", "New Fixed Asset (".$insert['item_name'].") was succesfully added by ". $_SESSION['abas_login']['fullname']." with Asset Code: ".$insert['asset_code'].'-'.$insert['control_number'],'Asset Management',"info");
						$this->Abas->sysMsg("sucmsg", "A New Fixed Asset was succesfully added by ". $_SESSION['abas_login']['fullname']." with Asset Code: ".$insert['asset_code'].'-'.$insert['control_number']);
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/fixed_asset_register/listview");
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Fixed Asset! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/fixed_asset_register/listview");
						die();
					}
				}
			break;

			case 'edit':
				$data['asset']	=	$this->Asset_Management_model->getFixedAsset($id);
				$data['item'] = $this->Inventory_model->getItem($data['asset']->item_id);
				$data['department'] = $this->Abas->getDepartment($data['asset']->department_id);
				$data['category'] = $this->Inventory_model->getCategory($data['asset']->category_id);
				$data['departments']  = $this->Abas->getDepartments();
				$data['units']  = $this->Abas->getItemUnit();
				$data['categories']  = $this->Abas->getItemCategory();
				$data['companies'] = $this->Abas->getCompanies();
				$data['locations']  = $this->Abas->getVessels();
				$data['departments']  = $this->Abas->getDepartments();
				if($data['asset']->status=='Assigned'){
					$accountable1 = $this->Asset_Management_model->getAccountableForIssuedAsset($id);
					if($accountable1){
						$accountable2 = $this->Asset_Management_model->getAccountabilityForm($accountable1->accountability_id);
						$data['accountable_to']	=	$accountable2->requested_by . " | " .$accountable2->position;
					}else{
						$data['accountable_to']	= "--";
					}
				}
				$this->load->view(VIEW.'/fixed_asset_register/form.php',$data);
			break;

			case 'update':
				if($_POST['asset_code']){
					$asset	=	$this->Asset_Management_model->getFixedAsset($id);
					if($asset->status=="Unassigned"){
						$update = array();
						$item_name 					=	$this->Mmm->sanitize($_POST['item_name']);
						$item_id = $this->Mmm->sanitize($_POST['item_id']);
						if(isset($item_id)){
							$update['item_id']			=   $this->Mmm->sanitize($_POST['item_id']);
						}else{
							$update['item_id']			= 	0;
						}
						$update['company_id']		=	$this->Mmm->sanitize($_POST['company']);
						$update['item_name']		=	ucfirst($this->Mmm->sanitize($_POST['item_name']));
						$update['particular']		=	ucfirst($this->Mmm->sanitize($_POST['particular']));
						$update['unit']				=	$this->Mmm->sanitize($_POST['unit']);
						$update['purchase_cost']	=	$this->Mmm->sanitize($_POST['unit_price']);
						$update['category_id']		=	$this->Mmm->sanitize($_POST['category']);
						$update['company_id']		=	$this->Mmm->sanitize($_POST['company']);
						$update['location']			=	$this->Mmm->sanitize($_POST['location']);
						$update['department_id']	=	$this->Mmm->sanitize($_POST['department']);
						$update['date_acquired']	=	$this->Mmm->sanitize($_POST['date_acquired']);
						$update['useful_life']		=	$this->Mmm->sanitize($_POST['useful_life']);
						$update['description']		=	$this->Mmm->sanitize($_POST['description']);
						if($asset->company_id!=$update['company_id']){
							$update['control_number']	=	$this->Abas->getNextSerialNumber('am_fixed_assets',$this->Mmm->sanitize($_POST['company_id']));
						}else{
							$update['control_number']	= $asset->control_number;
						}
						$update['asset_code']		=	$this->Mmm->sanitize($_POST['asset_code']);
						$update['status']			=	"Unassigned";
						$update['modified_by']		=	$_SESSION['abas_login']['userid'];
						$update['modified_on']		=	date("Y-m-d H:i:s");
						$update['stat']				=	1;
						$update['include_lapsing']	=	$_POST['include_lapsing'];
						$config = array();
						$config['upload_path'] = WPATH .'assets'.DS.'uploads'.DS.'Asset_Management'.DS.'asset_images'.DS;
						$config['allowed_types'] = 'jpg';
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('picture')) {
							$error = array('error' => $this->upload->display_errors());
							$_SESSION['warnmsg'] = $error['error'];
						}
						else {
							$upload_data=$this->upload->data();
							$update['picture']	=	$upload_data['file_name'];
							$_SESSION['sucmsg']		=	"Asset image has been succesfully uploaded!";
						}

						$checkInsert	=	$this->Mmm->dbUpdate("am_fixed_assets",$update,$id,"Updated New Fixed Asset (".$item_name.") with Asset Code:".$update['asset_code'].'-'.$update['control_number']);
						if($checkInsert){
							$this->Abas->sysNotif("Fixed Asset Register", "Fixed Asset was succesfully updated by ". $_SESSION['abas_login']['fullname']." with Asset Code: ".$update['asset_code'].'-'.$update['control_number'],'Asset Management',"info");
							$this->Abas->sysMsg("sucmsg", "Fixed Asset was succesfully updated by ". $_SESSION['abas_login']['fullname']." with Asset Code: ".$update['asset_code'].'-'.$update['control_number']);
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/fixed_asset_register/listview");
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Fixed Asset! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/fixed_asset_register/listview");
							die();
						}
					}else{
						$this->Abas->sysMsg("warmsg", "You cannot edit this asset if it is already assigned.");
					}
				}
			break;

			case 'print':
				$data['asset']	=	$this->Asset_Management_model->getFixedAsset($id);
				$data['item'] = $this->Inventory_model->getItem($data['asset']->item_id);
				$data['department'] = $this->Abas->getDepartment($data['asset']->department_id);
				$data['location']  = $this->Abas->getVessel($data['asset']->location);
				$data['viewfile'] = VIEW.'/fixed_asset_register/print.php';
				$this->load->view('gentlella_container.php',$data);
			break;
		}
	}

	public function accountability_form($action,$id=NULL){
		$data = array();
		switch ($action) {
			case 'load':
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$data	=	$this->Abas->createBSTable('am_fixed_asset_accountability',$search,$limit,$offset,$order,$sort);

					foreach($data['rows'] as $ctr=>$row){
						if(isset($row['company_id'])){
							$company		=	$this->Abas->getCompany($row['company_id']);
							$data['rows'][$ctr]['company_name']	=	$company->name;
						}
						if(isset($row['requested_by'])){
							$requested_by		=	$this->Abas->getEmployee($row['requested_by']);
							$data['rows'][$ctr]['requested_by']	=	$requested_by['full_name'];
						}
						if(isset($row['requested_on'])){
							$data['rows'][$ctr]['requested_on']	=	date("j F Y h:i:s A", strtotime($row['requested_on']));
						}
						if(isset($row['created_on'])){
							$data['rows'][$ctr]['created_on']	=	date("j F Y h:i:s A", strtotime($row['created_on']));
						}
						if(isset($row['created_by'])){
							$created_by		=	$this->Abas->getUser($row['created_by']);
							$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
						}
						if(isset($row['modified_on'])){
							$data['rows'][$ctr]['modified_on']	=	date("j F Y h:i:s A", strtotime($row['modified_on']));
						}
						if(isset($row['modified_by'])){
							$modified_by		=	$this->Abas->getUser($row['modified_by']);
							$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
						}
						if(isset($row['verified_on'])){
							$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
						}
						if(isset($row['verified_by'])){
							$verified_by		=	$this->Abas->getUser($row['verified_by']);
							$data['rows'][$ctr]['verified_by']	=	$verified_by['full_name'];
						}
						if(isset($row['approved_on'])){
							$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
						}
						if(isset($row['approved_on'])){
							$approved_on		=	$this->Abas->getUser($row['approved_on']);
							$data['rows'][$ctr]['approved_on']	=	$approved_on['full_name'];
						}

						$num = $this->Asset_Management_model->getAccountabilityFormDetails($row['id']);
						$data['rows'][$ctr]['number_of_assigned_assets']	=	count($num);
					}
				}
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			break;

			case 'listview':
				$data['viewfile'] = 'asset_management/accountability_forms/listview.php';
				$this->load->view('gentlella_container.php',$data);
			break;

			case 'add':
				$data['companies'] = $this->Abas->getCompanies();
				$this->load->view(VIEW.'/accountability_forms/form.php',$data);
			break;

			case 'insert':
				if($_POST['company_id']){
					$insert = array();
					$insert['control_number']	=	$this->Abas->getNextSerialNumber('am_fixed_asset_accountability',$this->Mmm->sanitize($_POST['company_id']));
					$insert['company_id']		=	$this->Mmm->sanitize($_POST['company_id']);
					$insert['requested_by']		=	$this->Mmm->sanitize($_POST['requested_by']);
					$insert['requested_on']		=	$this->Mmm->sanitize($_POST['requested_on']);
					$requested_by               =   $_SESSION['abas_login']['fullname'];
					$insert['status']			=	"Draft";
					$insert['created_by']		=	$_SESSION['abas_login']['userid'];
					$insert['created_on']		=	date("Y-m-d H:i:s");
					$insert['stat']				=	1;
					$checkInsert	=	$this->Mmm->dbInsert("am_fixed_asset_accountability",$insert,"Added New Fixed Asset Accountability Form for ".$requested_by);
					if($checkInsert){
						$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('am_fixed_asset_accountability');
						$multiInsert = array();
						foreach($_POST['asset_id'] as $ctr=>$val){
							$multiInsert[$ctr]['accountability_id']	=	$last_id_inserted;
							$multiInsert[$ctr]['fixed_asset_id']	=	$this->Mmm->sanitize($_POST['asset_id'][$ctr]);
							$multiInsert[$ctr]['remarks']	=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
							$multiInsert[$ctr]['status']	=	"On-going Clearance";
							$multiInsert[$ctr]['stat']	=	1;
							
						}
						$checkMultiInsert = $this->Mmm->multiInsert('am_fixed_asset_accountability_details',$multiInsert,'Assigned Fixed Assets on Accountability Form of '.$requested_by);
						if($checkMultiInsert){
							$this->Abas->sysNotif("Accountability Form", "New Fixed Asset Accountability Form was successfully added by ".$requested_by,'Asset Management',"info");
							$this->Abas->sysMsg("sucmsg", "New Fixed Asset Accountability Form was added by ".$requested_by);
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/accountability_form/listview");
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Accountability Form! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/accountability_form/listview");
							die();
						}
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Accountability Form! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/accountability_form/listview");
						die();
					}
				}
			break;

			case 'view':
			    $this->Abas->checkPermissions("asset_management|view_accountability_form",TRUE);
				$data['AC']	=	$this->Asset_Management_model->getAccountabilityForm($id);
				$data['AC_details']	=	$this->Asset_Management_model->getAccountabilityFormDetails($id);
				$data['viewfile'] = 'asset_management/accountability_forms/view.php';
				$this->load->view('gentlella_container.php',$data);
			break;

			case 'edit':
				$data['AC']	=	$this->Asset_Management_model->getAccountabilityForm($id);
				$data['AC_details']	=	$this->Asset_Management_model->getAccountabilityFormDetails($id);
				$data['companies'] = $this->Abas->getCompanies();
				$this->load->view(VIEW.'/accountability_forms/form.php',$data);
			break;

			case 'update':
				if($_POST['company_id']){
					$update = array();
					$update['requested_by']		=	$this->Mmm->sanitize($_POST['requested_by']);
					$update['requested_on']		=	$this->Mmm->sanitize($_POST['requested_on']);
					$requested_by               =   $_SESSION['abas_login']['fullname'];
					$update['status']			=	"Draft";
					$update['modified_by']		=	$_SESSION['abas_login']['userid'];
					$update['modified_on']		=	date("Y-m-d H:i:s");
					$update['stat']				=	1;
					$checkUpdate	=	$this->Mmm->dbUpdate("am_fixed_asset_accountability",$update,$id,"Edited Fixed Asset Accountability Form of ".$requested_by);
					if($checkUpdate){
						$this->Mmm->query('DELETE FROM am_fixed_asset_accountability_details WHERE accountability_id='.$id,"Edited details of Accountability Form with TSCode No.".$id);
						$multiInsert = array();
						foreach($_POST['asset_id'] as $ctr=>$val){
							$multiInsert[$ctr]['accountability_id']	=	$id;
							$multiInsert[$ctr]['fixed_asset_id']	=	$this->Mmm->sanitize($_POST['asset_id'][$ctr]);
							$multiInsert[$ctr]['remarks']	=	$this->Mmm->sanitize($_POST['remarks'][$ctr]);
							$multiInsert[$ctr]['status']	=	"On-going Clearance";
							$multiInsert[$ctr]['stat']	=	1;
							
						}
						$checkMultiInsert = $this->Mmm->multiInsert('am_fixed_asset_accountability_details',$multiInsert,'Edited the assigned Fixed Assets on Accountability Form of '.$requested_by);
						if($checkMultiInsert){
							$this->Abas->sysNotif("Accountability Form", "Fixed Asset Accountability Form was succesfully edited by ".$requested_by,'Asset Management',"info");
							$this->Abas->sysMsg("sucmsg", "Fixed Asset Accountability Form was edited by ".$requested_by);
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/accountability_form/listview");
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Accountability Form! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/accountability_form/listview");
							die();
						}
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Accountability Form! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/accountability_form/listview");
						die();
					}
				}

			break;

			case 'print':
				$checkUpdate	=	"UPDATE am_fixed_asset_accountability_details SET status= IF(status='Cleared','Issued',status), date_issued= IF(date_issued=0,'".date('Y-m-d H:m:s')."',date_issued) WHERE accountability_id=".$id;
				$this->Mmm->query($checkUpdate,"Issued Assets from Accountability Forms TScode No.".$id);
				if($checkUpdate){
					require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
					$data['AC']	=	$this->Asset_Management_model->getAccountabilityForm($id);
					$data['AC_details']	=	$this->Asset_Management_model->getAccountabilityFormDetails($id);
					$this->load->view(VIEW.'/accountability_forms/print.php',$data);
				}
			break;

			case 'submit':
				$checkUpdate	=	"UPDATE am_fixed_asset_accountability SET status='For Verification' WHERE id=".$id;
				$this->Mmm->query($checkUpdate,"Submitted Accountability Form with TScode No.".$id);
				if($checkUpdate){
					$this->Abas->sysNotif("Fixed Asset Register", "Accountability Form with TScode No.".$id." was succesfully submitted by ". $_SESSION['abas_login']['fullname']. " for Verification.",'Asset Management',"info");
					$this->Abas->sysMsg("sucmsg","Accountability Form with TScode No.".$id." was submitted by ". $_SESSION['abas_login']['fullname']. " for Verification.");
				}
				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/accountability_form/listview");
			break;

			case 'cancel':
				$checkUpdate	=	"UPDATE am_fixed_asset_accountability SET status='Cancelled' WHERE id=".$id;
				$this->Mmm->query($checkUpdate,"Cancelled Accountability Form with TScode No.".$id);
				if($checkUpdate){
					$this->Abas->sysNotif("Accountability Form", "Accountability Form with TScode No.".$id." was succesfully cancelled by ". $_SESSION['abas_login']['fullname'],'Asset Management',"info");
					$this->Abas->sysMsg("sucmsg","Accountability Form with TScode No.".$id." was cancelled by ". $_SESSION['abas_login']['fullname']);
				}
				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/accountability_form/listview");
			break;

			case 'return':
				$comments = $_POST['comments'];
				$detail = $this->Asset_Management_model->getAccountabilityFormDetail($id);

				$checkUpdate1	=	"UPDATE am_fixed_asset_accountability_details SET status='Returned',date_returned='".date('Y-m-d H:m:s')."',received_by=".$_SESSION['abas_login']['userid'].",condition_of_returned_item='".$comments."' WHERE id=".$id;
				$this->Mmm->query($checkUpdate1,"Returned Assets from Accountability Forms");

				$checkUpdate2	=	"UPDATE am_fixed_assets SET status='Unassigned' WHERE id=".$detail->fixed_asset_id;
				$this->Mmm->query($checkUpdate2,"Returned Assets to Unassigned status");

				$fixed_asset = $this->Asset_Management_model->getFixedAsset($detail->fixed_asset_id);

				if($checkUpdate1 && $checkUpdate2){
					$this->Abas->sysNotif("Accountability Form", "Fixed Asset with asset code (".$fixed_asset->asset_code.") was succesfully returned by ". $_SESSION['abas_login']['fullname'],'Asset Management',"info");
					$this->Abas->sysMsg("sucmsg","Fixed Asset with asset code (".$fixed_asset->asset_code.") was returned by ". $_SESSION['abas_login']['fullname']);
				}
			break;

			case 'loss_damaged':
				$comments = $_POST['comments'];
				$detail = $this->Asset_Management_model->getAccountabilityFormDetail($id);

				$checkUpdate1	=	"UPDATE am_fixed_asset_accountability_details SET status='Loss/Damaged',date_returned='".date('Y-m-d H:m:s')."',received_by=".$_SESSION['abas_login']['userid'].",condition_of_returned_item='".$comments."' WHERE id=".$id;
				$this->Mmm->query($checkUpdate1,"Mark Asset as 'Loss/Damaged' status");

				$checkUpdate2	=	"UPDATE am_fixed_assets SET status='Loss/Damaged' WHERE id=".$detail->fixed_asset_id;
				$this->Mmm->query($checkUpdate2,"Mark Asset to 'Loss/Damaged' status");

				$fixed_asset = $this->Asset_Management_model->getFixedAsset($detail->fixed_asset_id);

				if($checkUpdate1 && $checkUpdate2){
					$this->Abas->sysNotif("Accountability Form", "Fixed Asset with asset code (".$fixed_asset->asset_code.") was marked as 'Loss/Damaged' by ". $_SESSION['abas_login']['fullname'],'Asset Management',"info");
					$this->Abas->sysMsg("sucmsg","Fixed Asset with asset code (".$fixed_asset->asset_code.") was marked as 'Loss/Damaged' by ". $_SESSION['abas_login']['fullname']);
				}
			break;
		}
	}

	public function disposal_slip($action,$id=NULL){
		$data = array();
		switch ($action) {
			case 'load':
				if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){

					$search	=	isset($_GET['search'])?$_GET['search']:"";
					$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
					$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
					$order	=	isset($_GET['order'])?$_GET['order']:"";
					$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
					$data	=	$this->Abas->createBSTable('am_fixed_asset_disposals',$search,$limit,$offset,$order,$sort);

					foreach($data['rows'] as $ctr=>$row){
						if(isset($row['company_id'])){
							$company		=	$this->Abas->getCompany($row['company_id']);
							$data['rows'][$ctr]['company_name']	=	$company->name;
						}
						if(isset($row['requested_by'])){
							$requested_by		=	$this->Abas->getEmployee($row['requested_by']);
							$data['rows'][$ctr]['requested_by']	=	$requested_by['full_name'];
						}
						if(isset($row['requested_on'])){
							$data['rows'][$ctr]['requested_on']	=	date("j F Y", strtotime($row['requested_on']));
						}
						if(isset($row['created_on'])){
							$data['rows'][$ctr]['created_on']	=	date("j F Y", strtotime($row['created_on']));
						}
						if(isset($row['created_by'])){
							$created_by		=	$this->Abas->getUser($row['created_by']);
							$data['rows'][$ctr]['created_by']	=	$created_by['full_name'];
						}
						if(isset($row['modified_on'])){
							$data['rows'][$ctr]['modified_on']	=	date("j F Y h:i:s A", strtotime($row['modified_on']));
						}
						if(isset($row['modified_by'])){
							$modified_by		=	$this->Abas->getUser($row['modified_by']);
							$data['rows'][$ctr]['modified_by']	=	$modified_by['full_name'];
						}
						if(isset($row['verified_on'])){
							$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
						}
						if(isset($row['verified_by'])){
							$verified_by		=	$this->Abas->getUser($row['verified_by']);
							$data['rows'][$ctr]['verified_by']	=	$verified_by['full_name'];
						}
						if(isset($row['approved_on'])){
							$data['rows'][$ctr]['verified_on']	=	date("j F Y h:i:s A", strtotime($row['verified_on']));
						}
						if(isset($row['approved_on'])){
							$approved_on		=	$this->Abas->getUser($row['approved_on']);
							$data['rows'][$ctr]['approved_on']	=	$approved_on['full_name'];
						}
					}
				}
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			break;

			case 'listview':
				$data['viewfile'] = 'asset_management/disposal_slips/listview.php';
				$this->load->view('gentlella_container.php',$data);
			break;

			case 'add':
				$data['companies'] = $this->Abas->getCompanies();
				$this->load->view(VIEW.'/disposal_slips/form.php',$data);
			break;

			case 'insert':
				if($_POST['company_id']){
					$insert = array();
					$insert['control_number']	=	$this->Abas->getNextSerialNumber('am_fixed_asset_disposals',$this->Mmm->sanitize($_POST['company_id']));
					$insert['company_id']		=	$this->Mmm->sanitize($_POST['company_id']);
					$insert['requested_by']		=	$this->Mmm->sanitize($_POST['requested_by']);
					$insert['requested_on']		=	$this->Mmm->sanitize($_POST['requested_on']);
					$insert['checked_by']		=	$this->Mmm->sanitize($_POST['checked_by']);
					$insert['checked_on']		=	$this->Mmm->sanitize($_POST['checked_on']);
					$requested_by               =   $_SESSION['abas_login']['fullname'];
					$insert['manner_of_disposal']		=	$this->Mmm->sanitize($_POST['manner_of_disposal']);
					if($insert['manner_of_disposal']=="Others" && $_POST['others']!=NULL){
						$insert['others']			=	$this->Mmm->sanitize($_POST['others']);
					}
					$insert['status']			=	"Draft";
					$insert['created_by']		=	$_SESSION['abas_login']['userid'];
					$insert['created_on']		=	date("Y-m-d H:i:s");
					$insert['stat']				=	1;
					$checkInsert	=	$this->Mmm->dbInsert("am_fixed_asset_disposals",$insert,"Added New Asset Disposal Slip by ".$requested_by);
					if($checkInsert){
						$last_id_inserted = $this->Asset_Management_model->getLastIDByTable('am_fixed_asset_disposals');
						$multiInsert = array();
						foreach($_POST['asset_id'] as $ctr=>$val){
							$multiInsert[$ctr]['disposal_id']	=	$last_id_inserted;
							$multiInsert[$ctr]['asset_id']	=	$this->Mmm->sanitize($_POST['asset_id'][$ctr]);
							$multiInsert[$ctr]['net_book_value']	=	$this->Mmm->sanitize($_POST['net_book_value'][$ctr]);
							$multiInsert[$ctr]['proceeds']	=	$this->Mmm->sanitize($_POST['actual_proceeds'][$ctr]);
							$multiInsert[$ctr]['reason_for_disposal']	=	$this->Mmm->sanitize($_POST['reason_for_disposal'][$ctr]);
							if($multiInsert[$ctr]['proceeds']>=$multiInsert[$ctr]['net_book_value']){
								$multiInsert[$ctr]['is_gain'] = TRUE;
							}else{
								$multiInsert[$ctr]['is_gain'] = FALSE;
							}
							$multiInsert[$ctr]['stat']	=	1;
						}
						$checkMultiInsert = $this->Mmm->multiInsert('am_fixed_asset_disposal_details',$multiInsert,'Added Fixed Asset(s) on Disposal Slip');
						if($checkMultiInsert){
							$this->Abas->sysNotif("Asset Disposal Slip", "New Asset Disposal Slip was successfuly added by ".$requested_by,'Asset Management',"info");
							$this->Abas->sysMsg("sucmsg", "New Asset Disposal Slip added by ".$requested_by);
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/disposal_slip/listview");
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Asset Disposal Slip! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/disposal_slip/listview");
							die();
						}
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Asset Disposal Slip! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/disposal_slip/listview");
						die();
					}
				}
			break;

			case 'view':
				$data['disposal']	=	$this->Asset_Management_model->getDisposalSlip($id);
				$data['disposal_details']	=	$this->Asset_Management_model->getDisposalSlipDetails($id);
				$data['viewfile'] = 'asset_management/disposal_slips/view.php';
				$this->load->view('gentlella_container.php',$data);
			break;

			case 'edit':
				$data['disposal']	=	$this->Asset_Management_model->getDisposalSlip($id);
				$data['disposal_details']	=	$this->Asset_Management_model->getDisposalSlipDetails($id);
				$data['companies'] = $this->Abas->getCompanies();
				$this->load->view(VIEW.'/disposal_slips/form.php',$data);
			break;

			case 'update':
				if($_POST['company_id']){
					$update = array();
					$update['requested_by']		=	$this->Mmm->sanitize($_POST['requested_by']);
					$update['requested_on']		=	$this->Mmm->sanitize($_POST['requested_on']);
					$update['checked_by']		=	$this->Mmm->sanitize($_POST['checked_by']);
					$update['checked_on']		=	$this->Mmm->sanitize($_POST['checked_on']);
					$requested_by               =   $_SESSION['abas_login']['fullname'];
					$update['manner_of_disposal']		=	$this->Mmm->sanitize($_POST['manner_of_disposal']);
					if($update['manner_of_disposal']=="Others" && $_POST['others']!=NULL){
						$update['others']			=	$this->Mmm->sanitize($_POST['others']);
					}else{
						$update['others']			=	NULL;
					}
					$update['status']			=	"Draft";
					$update['modified_by']		=	$_SESSION['abas_login']['userid'];
					$update['modified_on']		=	date("Y-m-d H:i:s");
					$update['stat']				=	1;
					$checkInsert	=	$this->Mmm->dbUpdate("am_fixed_asset_disposals",$update,$id,"Edited Asset Disposal Slip by ".$requested_by);
					if($checkInsert){
						$this->Mmm->query('DELETE FROM am_fixed_asset_disposal_details WHERE disposal_id='.$id,"Edited details of Disposal Slip with TSCode No.".$id);
						$multiInsert = array();
						foreach($_POST['asset_id'] as $ctr=>$val){
							$multiInsert[$ctr]['disposal_id']	=	$id;
							$multiInsert[$ctr]['asset_id']	=	$this->Mmm->sanitize($_POST['asset_id'][$ctr]);
							$multiInsert[$ctr]['net_book_value']	=	$this->Mmm->sanitize($_POST['net_book_value'][$ctr]);
							$multiInsert[$ctr]['proceeds']	=	$this->Mmm->sanitize($_POST['actual_proceeds'][$ctr]);
							$multiInsert[$ctr]['reason_for_disposal']	=	$this->Mmm->sanitize($_POST['reason_for_disposal'][$ctr]);
							if($multiInsert[$ctr]['proceeds']>=$multiInsert[$ctr]['net_book_value']){
								$multiInsert[$ctr]['is_gain'] = TRUE;
							}else{
								$multiInsert[$ctr]['is_gain'] = FALSE;
							}
							$multiInsert[$ctr]['stat']	=	1;
						}
						$checkMultiInsert = $this->Mmm->multiInsert('am_fixed_asset_disposal_details',$multiInsert,'Edited Fixed Asset(s) on Disposal Slip');
						if($checkMultiInsert){
							$this->Abas->sysNotif("Asset Disposal Slip", "Asset Disposal Slip was successfully edited by ".$requested_by,'Asset Management',"info");
							$this->Abas->sysMsg("sucmsg", "Asset Disposal Slip edited by ".$requested_by);
							$this->disposal_slip('view',$id);
						}else{
							$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Asset Disposal Slip! Please try again.");
							$this->Abas->redirect(HTTP_PATH.CONTROLLER."/disposal_slip/listview");
							die();
						}
					}else{
						$this->Abas->sysMsg("errmsg", "An error has occurred while saving the Asset Disposal Slip! Please try again.");
						$this->Abas->redirect(HTTP_PATH.CONTROLLER."/disposal_slip/listview");
						die();
					}
				}

			break;

			case 'print':
				require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
				$data['disposal']	=	$this->Asset_Management_model->getDisposalSlip($id);
				$data['disposal_details']	=	$this->Asset_Management_model->getDisposalSlipDetails($id);
				$this->load->view(VIEW.'/disposal_slips/print.php',$data);
			break;

			case 'submit':
				$checkUpdate	=	"UPDATE am_fixed_asset_disposals SET status='For Verification' WHERE id=".$id;
				$this->Mmm->query($checkUpdate,"Submitted Asset Disposal Slip with TScode No.".$id);
				if($checkUpdate){
					$this->Abas->sysNotif("Asset Disposal Slip", "Asset Disposal Slip with TScode No.".$id." was succesfully submitted by ". $_SESSION['abas_login']['fullname']. " for Verification.",'Asset Management',"info");
					$this->Abas->sysMsg("sucmsg","Asset Disposal Slip with TScode No.".$id." was submitted by ". $_SESSION['abas_login']['fullname']. " for Verification.");
				}
				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/disposal_slip/listview");
			break;

			case 'cancel':
				$checkUpdate	=	"UPDATE am_fixed_asset_disposals SET status='Cancelled' WHERE id=".$id;
				$this->Mmm->query($checkUpdate,"Cancelled  Asset Disposal Slip with TScode No.".$id);
				if($checkUpdate){
					$this->Abas->sysNotif("Asset Disposal Slip", " Asset Disposal Slip with TScode No.".$id." was succesfully cancelled by ". $_SESSION['abas_login']['fullname'],'Asset Management',"info");
					$this->Abas->sysMsg("sucmsg","Asset Disposal Slip with TScode No.".$id." was cancelled by ". $_SESSION['abas_login']['fullname']);
				}
				$this->Abas->redirect(HTTP_PATH.CONTROLLER."/disposal_slip/listview");
			break;
		}
	}

	public function get_category_code($category_id){
		$sql = "SELECT * FROM inventory_category WHERE id=".$category_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = "";
		}
		header('Content-Type: application/json');
		echo json_encode($result);
		exit();
	}

	public function autocomplete_capex() {
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT id,description,particular,unit,unit_price,category,picture FROM inventory_items WHERE description LIKE '%".$search."%' AND stat=1 AND type='Capex' ORDER BY description";
		$items	=	$this->db->query($sql);
		if($items) {
			if($items->row()) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					if(isset($i['id'])) {
						$ret[$ctr]['value']	=	$i['id'];
						$ret[$ctr]['label']	= $i['description'] . " | " .$i['particular'];
						$ret[$ctr]['item_name']	= $i['description'];
						$ret[$ctr]['particular']	= $i['particular'];
						$ret[$ctr]['unit']	= $i['unit'];
						$ret[$ctr]['picture']	= $i['picture'];
						$ret[$ctr]['unit_price']	= $i['unit_price'];
						$category = $this->Inventory_model->getCategory($i['category']);
						$ret[$ctr]['category_id']	= $category->id;
						$ret[$ctr]['category_name']	= $category->category;
						$ret[$ctr]['category_code']	= $category->code;
					}
				}
			}
			header('Content-Type: application/json');
			echo json_encode($ret);
			exit();
		}
	}
	public function autocomplete_asset($company_id) {
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT * FROM am_fixed_assets WHERE item_name LIKE '%".$search."%' AND company_id=".$company_id." AND status='Unassigned' AND stat=1 ORDER BY particular";
		$items	=	$this->db->query($sql);
		if($items) {
			$result	=	$items->result();
			$ret	=	array();
			foreach($result as $ctr=>$i) {
				$ret[$ctr]['value']	=	$i->id;
				$ret[$ctr]['label']	= $i->asset_code."-".$i->control_number . " | " .$i->item_name;
				$ret[$ctr]['particular']	= $i->particular;
			}
			header('Content-Type: application/json');
			echo json_encode($ret);
			exit();
		}
	}
	public function autocomplete_asset_lapsing($company_id) {
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT * FROM am_fixed_assets WHERE item_name LIKE '%".$search."%' AND company_id=".$company_id." AND stat=1 AND include_lapsing=1 ORDER BY particular";
		$items	=	$this->db->query($sql);
		if($items) {
			$result	=	$items->result();
			$ret	=	array();
			foreach($result as $ctr=>$i) {
				$ret[$ctr]['value']	=	$i->id;
				$ret[$ctr]['label']	= $i->asset_code."-".$i->control_number . " | " .$i->item_name;
				$ret[$ctr]['particular']	= $i->particular;
			}
			header('Content-Type: application/json');
			echo json_encode($ret);
			exit();
		}
	}
	public function autocomplete_asset_disposal($company_id) {
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT * FROM am_fixed_assets WHERE item_name LIKE '%".$search."%' AND company_id=".$company_id." AND (status='Unassigned' OR status='Loss/Damaged')  AND stat=1 ORDER BY particular";
		$items	=	$this->db->query($sql);
		if($items) {
			$items	=	$items->result();
			$ret	=	array();
			foreach($items as $ctr=>$i) {
				$ret[$ctr]['value']	=	$i->id;
				$ret[$ctr]['label']	= $i->asset_code."-".$i->control_number . " | " .$i->item_name;
				$ret[$ctr]['particular']	= $i->particular;
				$ret[$ctr]['unit']	= $i->unit;
				$ret[$ctr]['date_purchased']	= date('Y-m-d',strtotime($i->date_acquired));
				$ret[$ctr]['original_cost']	= $i->purchase_cost;
			}
			header('Content-Type: application/json');
			echo json_encode($ret);
			exit();
		}
	}

	public function autocomplete_employee(){
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT id, last_name, first_name, middle_name, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.') as full_name, position,department FROM hr_employees WHERE last_name LIKE '%".$search."%' OR first_name LIKE '%".$search."%' OR middle_name LIKE '%".$search."%' ORDER BY last_name LIMIT 0, 10";
		$items	=	$this->db->query($sql);
		if($items) {
			if($items->row()) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					
					$ret[$ctr]['label']	=	$i['full_name'];
					$ret[$ctr]['value']	=	$i['id'];

					$position = $this->Abas->getPosition($i['position']);
					$ret[$ctr]['position']	=	$position->name;

					$department = $this->Abas->getDepartment($i['department']);
					$ret[$ctr]['department']	=	$department->name;
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
	}
}
?>