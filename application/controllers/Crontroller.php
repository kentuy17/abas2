<?php defined('BASEPATH') OR exit('No direct script access allowed');
	################################################################
	################################################################
	##########                                             #########
	##########               Crontroller                   #########
	##########            (Cron Controller)                #########
	##########                                             #########
	##########              it@avegabros.com               #########
	##########                                             #########
	##########               February 2017                 #########
	##########                                             #########
	################################################################
	################################################################

	class Crontroller extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			// $this->load->database();
			$this->load->model("Abas");
			$this->load->model("Mmm");
			$this->output->enable_profiler(FALSE);
			define("SIDEMENU","Home");
			if(isset($_SESSION['failed_login_attempts'])) { if($_SESSION['failed_login_attempts'] > 5) { die("Maximum number of login attempts reached. Please stop."); } }
		}
		public function index() {
			// running in crontab at 17:30 everyday
			echo "<pre>Starting Crontroller->cronEmployeeStatus()!<br/><br/>";
			$this->employeeStatus();
			echo "</pre>";

			echo "<pre>Starting Crontroller->cleanNotifRecords()!<br/><br/>";
			$this->cleanNotifRecords();
			echo "</pre>";

			echo "<pre>Starting Crontroller->notifyForVesselCertificateExpiration()!<br/><br/>";
			$this->notifyForVesselCertificateExpiration();
			echo "</pre>";

			echo "<pre>Starting Crontroller->checkQueriesForErrorsRecurse()!<br/><br/>";
			$this->checkQueriesForErrorsRecurse();
			echo "</pre>";


		}
		public function employeeStatus() {
			$history	=	$this->db->query("SELECT * FROM hr_employment_history WHERE effectivity_date LIKE '".date("Y-m-d")."%' ORDER BY effectivity_date DESC");
			if(!$history) { $this->Abas->sysNotif('Automated HR', 'No automated employee status changes applied', "Human Resources", "info"); }
			$history	=	$history->result_array();
			foreach($history as $ctr=>$h) {
				$employee	=	$this->Abas->getEmployee($h['employee_id']);
				$link		=	"<a href='".HTTP_PATH."hr/employee_profile/view/".$employee['id']."' data-target='#modalDialog' data-toggle='modal'>".$employee['full_name']."</a>";
				if($h['value_changed']	==	"Employee Status")	$update['employee_status']	=	$h['to_val'];
				if($h['value_changed']	==	"Position")			$update['position']			=	$h['to_val'];
				if($h['value_changed']	==	"Vessel")			$update['vessel_id']		=	$h['to_val'];
				if($h['value_changed']	==	"Salary Grade")		$update['salary_grade']		=	$h['to_val'];
				$check		=	$this->Mmm->dbUpdate("hr_employees", $update, $employee['id'], "CRON: apply employee history for ".$employee['full_name']);
				if($check) {
					$this->Abas->sysNotif('Automated HR', 'Applied employee history for '.$link, "Human Resources", "success");
				}
				else {
					$this->Abas->sysNotif('Automated HR', 'Employee history application failure for '.$link, "Administrator", "error");
				}
			}
		}
		public function cleanNotifRecords() { // deletes old entries from notification_views to help reduce database backup size
			$threeDaysAgo	=	date("Y-m-d", strtotime("-3 days"));
			$lastWeek	=	date("Y-m-d", strtotime("-7 days"));
			$lastMonth	=	date("Y-m-d", strtotime("-30 days"));
			// $this->db->query("DELETE FROM notifications WHERE tdate<'".$lastweek."'"); // can be used for logging, not sure if this should be deleted
			$this->db->query("DELETE FROM notification_views WHERE tdate<'".$threeDaysAgo."'");
			$this->db->query("DELETE FROM notifications WHERE tdate<'".$lastMonth"'");
		}
		public function notifyForVesselCertificateExpiration() {
			$threshold	=	date("Y-m-d", strtotime("+3 months"));
			$certs			=	$this->db->query("SELECT * FROM vessel_certificates WHERE expiration_date<='".$threshold."'");
			if($certs!=false) {
				$this->Mmm->debug($certs->result_array());
			}
			$today		=	date("Y-m-d");
			$expiring	=	$this->db->query("SELECT * FROM vessel_certificates WHERE expiration_date BETWEEN '".$today."' AND '".$nextweek."' AND notification=0");
			if($expiring!=false) {
				if($expiring->row()) {
					$expiring	=	$expiring->result();
					foreach($expiring as $e) {
						$update['notification']	=	1;
						$this->Mmm->dbUpdate("vessel_certificates", $update, $e->id, "Notify for vessel certificate");
						$vessel	=	$e->vessel_id;
						$vessel	=	$this->db->query("SELECT * FROM vessels WHERE id=".$vessel);
						$vessel	=	$vessel->row();
						$subject=	"Vessel Certification: Expiration";
						$message=	"Vessel: ".$vessel->name." expiring on ".date("j F Y", strtotime($threshold))."!";
						$this->Mmm->sendEmail("it@avegabros.com", $subject, $message);
						if(ENVIRONMENT=="production") {
							$this->Mmm->sendEmail("compliance@avegabros.com",$subject,$message);
						}
					}
				}
			}
		}
		public function checkQueriesForErrors() {
			$queries	=	$this->db->query("SELECT `id`, `query` FROM `db_activity` WHERE `is_valid_sql` IS NULL LIMIT 10000");
			if(!$queries) { die(); }
			if(!$queries->row()) { die(); }
			$records	=	$queries->result_array();
			if(count($records)>0) {
				$successstring		=	"";
				$errorstring		=	"";
				foreach($records as $record) {
					$check			=	$this->db->query("EXPLAIN ".$record['query']);
					if(!$check) $errorstring	.=	"id=".$record['id']." OR ";
					else $successstring	.=	"id=".$record['id']." OR ";
				}
				$successstring	=	rtrim($successstring, " OR ");
				$errorstring	=	rtrim($errorstring, " OR ");
				$this->db->query("UPDATE db_activity SET is_valid_sql=0 WHERE ".$errorstring);
				$this->db->query("UPDATE db_activity SET is_valid_sql=1 WHERE ".$successstring);
				return true;
			}
			return false;
		}
		public function checkQueriesForErrorsRecurse() {
			$queries	=	$this->db->query("SELECT `id`, `query` FROM `db_activity` WHERE `is_valid_sql` IS NULL LIMIT 10000");
			if(!$queries) { die(); }
			if(!$queries->row()) { die(); }
			$records	=	$queries->result_array();
			if(count($records)>0) {
				$recurse	=	$this->checkQueriesForErrors();
				if($recurse) $this->checkQueriesForErrorsRecurse();
			}
		}
	}
?>

