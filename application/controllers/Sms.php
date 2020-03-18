<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms extends CI_Controller {
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		// $this->load->database();
		$this->load->model("Abas");
		$this->load->model("Mmm");
		$this->load->model("Operation_model");
		$this->output->enable_profiler(FALSE);
	}

	public function index() {$data=array();
		if(!isset($_SESSION['abas_login'])) {
			$data['viewfile']		=	"login.php";
			if(isset($_SERVER['HTTP_REFERER'])) {
				if($_SERVER['HTTP_REFERER']==HTTP_PATH."home/index") {
					$_SESSION['msg']	=	"You are not logged in!";
				}
			}
		}
		else {
			$data['viewfile']		=	"dashboard.php";
		}
		$subj	=	"Email Test";
		$msg	=	"
			This is a test email
		";
		$this->Mmm->sendEmail("makati.it@avegabros.com", $subj, $msg);
		$this->load->view("container.php",$data);
	}
	public function report_sim() {$data=array();
		$this->load->view("operation/sms_report_simulator.php",$data);
	}
	public function receive() {$data=array();
		if($_POST['data_source']=="%PNUM") {
			http_response_code(405);
			die();
		}
		$this->Mmm->debug($_POST);
		echo json_encode($_POST);

		// add to sms_reports table

		$data['location']			=	$this->Mmm->sanitize($_POST['LOCATION']);
		$geolocation				=	$this->Abas->geolocate($_POST['LOCATION']);
		$geolocation				=	isset($geolocation['results'][0]['formatted_address']) ? $this->Mmm->sanitize($geolocation['results'][0]['formatted_address']) : null;
		$weather					=	$this->Abas->getWeather($_POST['LOCATION']);
		$weather					=	isset($weather['weather'][0]['description']) ? $this->Mmm->sanitize($weather['weather'][0]['description']) : null;
		$data['weather']			=	$weather;
		$data['formatted_address']	=	$geolocation;
		$data['raw_data']			=	!empty($_POST) ? json_encode($_POST) : "";
		$data['data_source']		=	isset($_POST['data_source']) ? $this->Mmm->sanitize($_POST['data_source']) : "";
		$data['received_on']		=	date("Y-m-d H:i:s");

		// $this->Abas->sysNotif("SMS Report", "New SMS report from ".$data['data_source'], "Administrator");

		//get vessel id from data source

		if($_POST['raw_data']=="AVS") {
			$vessel_id				=	$this->Operation_model->getToolsAssignedVessel($data['data_source']);
			$report_type="Vessel";
			$report_table="ops_report_vessel";
			$insert['vessel_id']	=	$vessel_id;
			$insert['report_date']	=	date("Y-m-d H:i:s");
			$insert['activity']		=	$this->Mmm->sanitize($_POST['ACTION']);
			$insert['qty']			=	$this->Mmm->sanitize($_POST['QUANTITY']);
			$insert['coordinates']	=	$this->Mmm->sanitize($_POST['LOCATION']); // coordinates
			$insert['location']		=	$geolocation; // name of location
			$insert['estimated_completion_time']	=	$this->Mmm->sanitize($_POST['COMPLETION']);
			$insert['message']		=	$this->Mmm->sanitize($_POST['REMARKS']);
			$insert['emergency']	=	$this->Mmm->sanitize($_POST['EMERGENCY']);
			$insert['stat']			=	1;
			$insert['data_source']	=	$data['data_source'];
		}
		elseif($_POST['raw_data']=="AVM") {
			$vessel_id				=	$this->Operation_model->getToolsAssignedVessel($data['data_source']);
			//insert to ops_report_vessel_maintenance
			$report_type="Vessel Maintenance";
			$report_table="ops_report_vessel_fuel";
			$insert['vessel_id']	=	$vessel_id;
			$insert['report_date']	=	date("Y-m-d H:i:s");
			$insert['fuel_reading']	=	$this->Mmm->sanitize($_POST['FUELONBOARD']);
			$insert['coordinates']	=	$this->Mmm->sanitize($_POST['LOCATION']); // coordinates
			$insert['location']		=	$geolocation; // name of location
			$insert['message']		=	$this->Mmm->sanitize($_POST['REPORT']);
			$insert['stat']			=	1;
			$insert['data_source']	=	$data['data_source'];
		}
		elseif($_POST['raw_data']=="PO") {
			//insert to ops_report_port_operation
			$report_type="Port Operation";
		}
		elseif($_POST['raw_data']=="WO") {
			//insert to ops_report_warehouse_operation
			$report_type="Warehouse Operation";


		}
		elseif($_POST['raw_data']=="PUR") {
			$report_type="Purchasing";
		}
		elseif($_POST['raw_data']=="TRU") {
			$report_type="Trucking";
		}
		elseif($_POST['raw_data']=="AVBTS") {
			$report_type="Tracking Signal";
		}
		elseif($_POST['raw_data']=="AVRSN") {
			$report_type	=	"Registration Success";
		}
		else { $report_type	=	"Unknown"; }

		$data['report_type']		=	$report_type;
		$check	=	$this->Mmm->dbInsert("sms_reports", $data, "SMS ".strtolower($report_type)." report");

		if($report_table != "") {
			$check	=	$this->Mmm->dbInsert($report_table, $insert, "SMS ".strtolower($report_type)." report");
			if($check==false) {
				$postcontent	=	"";
				if(!empty($_POST)) {
					foreach($_POST as $index=>$value) {
						$postcontent	.=	"[".$index."]=>'".$value."'<br/>";
					}
				}
				$subj	=	"SMS Report not distributed to report table";
				$msg	=	"
					<p>Report details are as follows:</p>
					<pre>\$_POST array:".$postcontent."</pre>
				";
				$this->Mmm->sendEmail("makati.it@avegabros.com", $subj, $msg);
			}
		}

		if(ENVIRONMENT=="development") {
			$subj	=	"SMS Report Received";
			$msg	=	"
				<p>Report details are as follows:</p>
				<pre>Report Type: ".$report_type."</pre>
				<pre>\$_POST array:";
				if(!empty($_POST)) {
					foreach($_POST as $index=>$value) {
						$msg	.=	"[".$index."]=>'".$value."'<br/>";
					}
				}
			$msg	.=	"
				</pre>
			";
			// $this->Mmm->sendEmail("makati.it@avegabros.com", $subj, $msg);
		}

		if($check == true) {
			echo "Your report has been submitted.";
		}
		else {
			echo "An error has occurred, please try again.";
		}
	}
}
?>
