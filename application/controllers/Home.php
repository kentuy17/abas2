<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Home extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			// $this->load->database();
			$this->load->model("Abas");
			$this->load->model("Inventory_model");
			$this->load->model("Mmm");
			$this->output->enable_profiler(FALSE);
			define("SIDEMENU","Home");
		}
		public function index() {$data=array();
			if(isset($_SESSION['failed_login_attempts'])) { if($_SESSION['failed_login_attempts'] > 500) { die("Maximum number of login attempts reached. Please stop."); } }
			if(!isset($_SESSION['abas_login'])) {
				$data['viewfile']		=	"login.php";
				$mainview				=	"responsive_container.php";
				//$this->load->view('login_new');
				if(isset($_SERVER['HTTP_REFERER'])) {
					if($_SERVER['HTTP_REFERER']==HTTP_PATH."home/index") {
						$this->Abas->sysMsg("msg","You are not logged in!");
					}
				}
			}
			else {
				$mainview	=	"gentlella_container.php";
				$data['viewfile']	=	"dashboard.php";
				if(!isset($_SESSION['abas_login']['changepass_required'])) {
					if(isset($_SESSION['abas_login']['role'])) {
						if($_SESSION['abas_login']['role'] == "Human Resources") {
							header("location:".HTTP_PATH."hr");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Payroll") {
							header("location:".HTTP_PATH."payroll");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Finance") {
							header("location:".HTTP_PATH."finance");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Accounting") {
							header("location:".HTTP_PATH."accounting");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Operations") {
							header("location:".HTTP_PATH."operation");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Inventory") {
							header("location:".HTTP_PATH."inventory");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Monitoring") {
							header("location:".HTTP_PATH."operation/monitoring");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Purchasing") {
							header("location:".HTTP_PATH."purchasing");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Asset Management") {
							header("location:".HTTP_PATH."Asset_Management");die();
						}
						elseif($_SESSION['abas_login']['role'] == "Compliance") {
							header("location:".HTTP_PATH."home/vessel_certs");die();
						}
						elseif ($_SESSION['abas_login']['role'] == "ESS") {
							header("location:".HTTP_PATH."Corporate_Services");die();
						}
					}
				}
				else {
					$this->Abas->sysMsg("warnmsg", "You are required to change your password before proceeding!");
					header("location:".HTTP_PATH."home/changepass");die();
				}
			}
			$this->load->view($mainview,$data);
		}

		public function request($action=''){
			if($action == 'submit'){
				$company_id = $_POST['company'];
				$department_id = $_POST['department'];
				$fname = $_POST['fname'];
				$lname = $_POST['lname'];
				$emp_id = $_POST['emp_id'];
				$email = $_POST['email'];
				$req = array(
					'company_id' => $company_id,
					'department_id' => $department_id,
					'first_name' => $fname,
					'last_name' => $lname,
					'company_emp_id' => $emp_id,
					'email' => $email,
					'request_date' => date('Y-m-d H:i:s'),
					'status' => 0
				);

				$this->Abas->insertOnly('user_request',$req);
				$this->Abas->sysMsg("sucmsg","Request successful! Your account credentials will be send in your email.");
				redirect(HTTP_PATH.'home/index');
			}else{
				$data['companies'] = $this->Abas->getItems('companies');
				$data['departments'] = $this->Abas->getItems('departments');

				$data['viewfile'] =	"register.php";				
			}
			$mainview =	"responsive_container.php";
			$this->load->view($mainview,$data);
		}

		public function logout() {$data=array();
			$_SESSION	=	array();
			header("location:index");
		}
		public function forced_logout() {
			$insert['session_id']	=	$_SESSION['uniqid'];
			$insert['user_id']		=	$_SESSION['abas_login']['userid'];
			$insert['created_on']	=	date("Y-m-d H:i:s");
			$check					=	$this->Mmm->dbInsert("logout_failures", $insert, $_SESSION['abas_login']['fullname']." has idled for more than the allowed time");
			$this->Abas->sysNotif("Logout failure", $_SESSION['abas_login']['fullname']." has idled for more than the allowed time and has been forcibly logged out.", "Administrator", "danger");
			if(!$check) {
				$this->Abas->sysNotif("Logout failure not recorded", "Logout failure for ".$_SESSION['abas_login']['fullname']." not incremented", "Administrator", "danger");
			}
			$_SESSION	=	array();
			$this->Abas->redirect(HTTP_PATH);
		}
		
		public function login() {$data=array();
			if(isset($_SESSION['failed_login_attempts'])) { if($_SESSION['failed_login_attempts'] > 5) { die("Maximum number of login attempts reached. Please stop."); } }
			if(!isset($_POST['uname']) && !isset($_POST['pword'])) { header("location:index"); }
			$uname	=	$this->Mmm->sanitize($_POST['uname']);
			$pword	=	$_POST['pword'];
			$pword	=	md5($pword);
			$check	=	$this->db->query("SELECT * FROM users WHERE username='".$uname."' AND password='".$pword."'");
			if($check->row()==true) {
				$check	=	$check->row();
				if($check->stat == 1) {
					if($check->password_reset!="") {
						$this->Mmm->query("UPDATE users SET password_reset=NULL WHERE id=".$check->id, "Cancel password reset of ".$uname." due to successful login");
					}
					$_SESSION['uniqid']		=	md5(uniqid());
					$user					=	$this->Mmm->query("SELECT * FROM users WHERE username='".$uname."' AND password='".$pword."'",$uname." login");
					$_SESSION['abas_login']	=	array("userid"=>$check->id, "username"=>$check->username, "user_location"=>$check->user_location, "role"=>$check->role, "fullname"=>$check->first_name." ".$check->middle_name." ".$check->last_name);

					$_SESSION['timestamp'] = time();
					
					$checkarchive			=	$this->db->query("SELECT * FROM password_history WHERE user_id=".$_SESSION['abas_login']['userid']." ORDER BY archived_on DESC LIMIT 1");
					if($checkarchive) {
						if($checkarchive->row()) {
							$checkarchive	=	$checkarchive->row();
						}
					}
					if(!isset($checkarchive->archived_on)) {
						$_SESSION['abas_login']['changepass_required']	=	true;
						$this->Abas->sysMsg("msg", $check->first_name.", you have not changed your password in a while. It's time to change it!");
						$this->Abas->redirect(HTTP_PATH."home/changepass");
					}
					$expiration_warning_period	=	strtotime($checkarchive->archived_on." +5 months");
					$password_expired			=	strtotime($checkarchive->archived_on." +6 months");
					if($expiration_warning_period <= strtotime(date("Y-m-d H:i:s")) && $password_expired > strtotime(date("Y-m-d H:i:s"))) {
						$this->Abas->sysMsg("msg", $check->first_name.", your password will expire at ".date("h:i A j F Y",$password_expired).". Click <a href='".HTTP_PATH."home/account' data-toggle='modal' data-target='#modalDialog' class'btn btn-success btn-xs'>HERE</a> to change it!");
					}
					if($password_expired <= strtotime(date("Y-m-d H:i:s"))) {
						$_SESSION['abas_login']['changepass_required']	=	true;
						$this->Abas->sysMsg("errmsg", "<img src='".HTTP_PATH."assets/images/HOcRhfr.png' style='width:100%;'/>");
						$this->Abas->redirect(HTTP_PATH."home/changepass");
					}
					if($_POST['pword']=="avegabros" || $check->passsword_reset!=null || $check->require_reset==true) {
						$_SESSION['abas_login']['changepass_required']	=	true;
						$this->Abas->sysMsg("msg", $check->first_name.", please change your password! Click <a href='".HTTP_PATH."home/account' data-toggle='modal' data-target='#modalDialog' class'btn btn-success btn-xs'>HERE</a> to change it!");
						$this->Abas->redirect(HTTP_PATH."home/changepass");
					}
				}
				else {
					$this->Abas->sysMsg("msg", $check->first_name.", your account has been disabled! Please contact the IT Department for details.");
					$check	=	$this->Mmm->query("SELECT * FROM users WHERE username='".$uname."' AND password='".$pword."'",$uname." disabled login");
				}
			}
			else {
				$check	=	$this->Mmm->query("SELECT * FROM users WHERE username='".$uname."' AND password='".$pword."'",$uname." failed login");
				$_SESSION['failed_login_attempts']	=	$_SESSION['failed_login_attempts']+1;
				if($_SESSION['failed_login_attempts']>1) { $this->Abas->sysMsg("warnmsg","You have ".($_SESSION['failed_login_attempts']-5)." login attempts remaining."); }
			}
			header("location:".HTTP_PATH."home");die();
		}

		public function home_test()
		{
			$this->load->view('test_login');
		}

		public function login_temp()
		{
			$user = $_POST['username'];
			$pass = $_POST['password'];

			$cond2 = [
				'username' => $user,
				'md5' 	   => md5($pass),
				'pass'	   => $pass
			];

			$cond = [
				'username' => $user,
				'password' => md5($pass),
			];

			$check = $this->Abas->getItemById('users',$cond);
			if(count($check) > 0){
				$this->Mmm->debug($check);
			}else{
				echo "wala<br>";
				$this->Mmm->debug($cond2);
			}
		}

		public function stop() {$data=array();
			// if(!isset($_SESSION['abas_login'])) { header("location:index"); }
			$mainview				=	"prohibited.php";
			$data['accessed_from']	=	isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
			$this->load->view($mainview,$data);
		}
		public function account() {$data=array();
			if(!isset($_SESSION['abas_login'])) { header("location:index"); }
			if(!empty($_POST)) {
				$pword			=	$_POST['password'];
				$pword2			=	$_POST['password2'];
				$check			=	$this->db->query("SELECT * FROM users WHERE id=".$_SESSION['abas_login']['userid']);
				$check			=	$check->row();
				$pwq			=	"";
				if(md5($_POST['oldpass'])!=$check->password) {
					$this->Abas->sysMsg('warnmsg',"Old password is incorrect!");
					$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
					$this->Abas->redirect(HTTP_PATH."home/changepass");
				}
				if($pword!="") {
					if($pword!=$pword2) {
						$this->Abas->sysMsg('warnmsg',"Passwords do not match!");
						$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
						$this->Abas->redirect(HTTP_PATH."home/changepass");
					}
					if(strlen($pword)<10) {
						$this->Abas->sysMsg('warnmsg',"Your password must be more than 10 characters!");
						$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
						$this->Abas->redirect(HTTP_PATH."home/changepass");
					}
					if($pword==$check->password) {
						$this->Abas->sysMsg('warnmsg',"You may not reuse your current password!");
						$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
						$this->Abas->redirect(HTTP_PATH."home/changepass");
					}
					$update['password']			=	md5($pword);
					$update['require_reset']	=	0;
				}
				if(is_numeric($_POST['notification_timeout'])) {
					$update['notification_timeout']		=	($_POST['notification_timeout']*1000);
				}
				$check_history	=	$this->db->query("SELECT * FROM password_history WHERE user_id=".$_SESSION['abas_login']['userid']." AND password='".$update['password']."'");
				$check_history	=	$check_history->result_array();
				if(count($check_history)>0) {
					$this->Abas->sysMsg('warnmsg',"You are attempting to use a password that has been previously used. Please try again with a different password.");
					$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
					$this->Abas->redirect(HTTP_PATH."home/changepass");
				}
				$do	=	$this->Mmm->dbUpdate("users",$update, $_SESSION['abas_login']['userid'], "Updated password");
				if($do==false) {
					$this->Abas->sysMsg('errmsg',"User not updated!");
					$this->Abas->redirect(HTTP_PATH);
				}
				$insert['user_id']		=	$check->id;
				$insert['password']		=	$check->password;
				$insert['archived_on']	=	date("Y-m-d H:i:s");
				$checkarchive			=	$this->Mmm->dbInsert("password_history", $insert, "Archived old password");
				if($checkarchive) $this->Abas->sysMsg("msg","Old password archived. You may not use any previous password again.");
				else $this->Abas->sysMsg("errmsg","Password archiving failed. The option to change your password back to the previous one is still open.");
				$this->Abas->sysMsg('sucmsg',"User updated! Please try logging in again with your new password.");
				unset($_SESSION['abas_login']);
				$this->Abas->redirect(HTTP_PATH);
			}
			$existing	=	$this->db->query("SELECT * FROM users WHERE id=".$_SESSION['abas_login']['userid']);
			$data['existing']	=	$existing->row();
			$data['view_full']	=	false;
			$data['locations']  = $this->Abas->getUserLocations();
			$this->load->view("account.php",$data);
		}
		public function account_details(){
			if(!isset($_SESSION['abas_login'])) { header("location:index"); }
			if(!empty($_POST)) {
				$oldpass		= 	$_POST['oldpass'];
				$pword			=	$_POST['password'];
				$pword2			=	$_POST['password2'];
				$check			=	$this->db->query("SELECT * FROM users WHERE id=".$_SESSION['abas_login']['userid']);
				$check			=	$check->row();
				$pwq			=	"";
				
				if($oldpass!=""){
					if(md5($oldpass)!=$check->password) {
						$this->Abas->sysMsg('warnmsg',"Old password is incorrect!");
						$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
						$this->Abas->redirect(HTTP_PATH."home/");
					}
					if($pword!="") {
						if($pword!=$pword2) {
							$this->Abas->sysMsg('warnmsg',"Passwords do not match!");
							$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
							$this->Abas->redirect(HTTP_PATH."home/");
						}
						if(strlen($pword)<10) {
							$this->Abas->sysMsg('warnmsg',"Your password must be more than 10 characters!");
							$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
							$this->Abas->redirect(HTTP_PATH."home/");
						}
						if($pword==$check->password) {
							$this->Abas->sysMsg('warnmsg',"You may not reuse your current password!");
							$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
							$this->Abas->redirect(HTTP_PATH."home/");
						}
						$update['password']			=	md5($pword);
						$update['require_reset']	=	0;
					}
				}

				if(isset($_POST['email'])) {
					$update['email']		=	$_POST['email'];
				}
				if(isset($_POST['user_location'])) {
					$update['user_location']		=	$_POST['user_location'];
				}
				if(is_numeric($_POST['notification_timeout'])) {
					$update['notification_timeout']		=	($_POST['notification_timeout']*1000);
				}

				/*$target_dir = WPATH.'assets/images/digitalsignatures/';
				if($_FILES["digital_signature"]){
					$attach = array();
					$old_filename = explode(".", basename($_FILES["digital_signature"]["name"]));
					$new_filename = round(microtime(true)). rand(999999,99999999) . '.' . end($old_filename);
					if(end($old_filename)!=""){
						$update["signature"] = $new_filename;
						$target_file = $target_dir . $new_filename;
						$uploaded = move_uploaded_file($_FILES["digital_signature"]["tmp_name"],$target_file);
					}else{
						$this->Abas->sysMsg('sucmsg',"User signature was updated!");
						$this->Abas->redirect(HTTP_PATH."home/");
					}
				}else{
					$update["signature"] = "blank.png";
				}*/

				$config = array();
				$config['upload_path'] = WPATH .'assets/images/digitalsignatures/';
				$config['allowed_types'] = 'gif|png|jpg|jpeg';
				$config['max_width']  = '1500';
				$config['max_height']  = '1500';
				$config['file_name'] = round(microtime(true)).rand(999999,99999999);
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('digital_signature')) {
					$error = array('error' => $this->upload->display_errors());
					$_SESSION['warnmsg'] = $error['error'];
				}
				else {
					$upload_data=$this->upload->data();
					$update["signature"]	=	$upload_data['file_name'];
				}
				
				$check_history	=	$this->db->query("SELECT * FROM password_history WHERE user_id=".$_SESSION['abas_login']['userid']." AND password='".$update['password']."'");
				$check_history	=	$check_history->result_array();
				if(count($check_history)>0) {
					$this->Abas->sysMsg('warnmsg',"You are attempting to use a password that has been previously used. Please try again with a different password.");
					$this->Abas->sysMsg('warnmsg',"Your password was not updated.");
					$this->Abas->redirect(HTTP_PATH."home/");
				}
				$do	=	$this->Mmm->dbUpdate("users",$update, $_SESSION['abas_login']['userid'], "Updated account details");
				if($do==false) {
					$this->Abas->sysMsg('warnmsg',"User not updated!");
					$this->Abas->redirect(HTTP_PATH."home/");
				}else{
					if($pword!="" && $pword2!="" && $oldpass!=""){
						$insert['user_id']		=	$check->id;
						$insert['password']		=	$check->password;
						$insert['archived_on']	=	date("Y-m-d H:i:s");
						$checkarchive			=	$this->Mmm->dbInsert("password_history", $insert, "Archived old password");
						if($checkarchive) $this->Abas->sysMsg("msg","Old password archived. You may not use any previous password again.");
						else $this->Abas->sysMsg("errmsg","Password archiving failed. The option to change your password back to the previous one is still open.");
						$this->Abas->sysMsg('sucmsg',"User updated! Please try logging in again with your new password.");
						unset($_SESSION['abas_login']);
						$this->Abas->redirect(HTTP_PATH);
					}else{
						$this->Abas->sysMsg('sucmsg',"User account details was updated!");
						$this->Abas->redirect(HTTP_PATH."home/");
					}
				}
				
			}
			$existing	=	$this->db->query("SELECT * FROM users WHERE id=".$_SESSION['abas_login']['userid']);
			$data['existing']	=	$existing->row();
			$data['view_full']	=	false;
			$data ['locations'] = $this->Abas->getUserLocations();
			$this->load->view("account.php",$data);
		}
		public function chat() {$data=array();
			if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH); }
			if(isset($_POST['text'])) {
				$message=	$_POST['text'];
				$name	=	!isset($_SESSION['abas_login']['username']) ? "Anonymous" : $_SESSION['abas_login']['username'];
				$fp		=	fopen(WPATH."assets/chat.txt", 'a');
				$write	=	"<pre>(".date("g:i A j F Y").") <b>".$name."</b>: ".stripslashes(htmlspecialchars($message))."</pre>".chr(13);
				fwrite($fp, $write);
				fclose($fp);
				$this->Abas->sysNotif("Avega Channel", $name." sent a message on ".date("g:i A j F Y"));
				$lastNotif	=	$this->db->query("SELECT MAX(id) AS id FROM notifications");
				$lastNotif	=	$lastNotif->row();
				$this->db->query("INSERT INTO notification_views (tdate, userid, notification_id) VALUES ('".date("Y-m-d H:i:s")."', '".$_SESSION['abas_login']['userid']."', '".$lastNotif->id."')");
			}
			else {
				die();
			}
		}
		public function changepass() {
			if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH); }
			$existing	=	$this->db->query("SELECT * FROM users WHERE id=".$_SESSION['abas_login']['userid']);
			$data['existing']	=	$existing->row();
			$data['viewfile']	=	"account.php";
			$data['view_full']	=	true;
			$data['locations']  = $this->Abas->getUserLocations();
			$this->load->view("responsive_container.php",$data);
		}
		public function autocomplete($table, $field){
			if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH); }
			$search	=	$this->Mmm->sanitize($_GET['term']);
			$search	=	str_replace(" ", "%", $search);
			$sql	=	"SELECT ".$field.",id FROM ".$table." WHERE ".$field." LIKE '%".$search."%' GROUP BY ".$field." ORDER BY ".$field." LIMIT 0, 10";
			$items	=	$this->db->query($sql);
			if($items) {
				if($items->row()) {
					$items	=	$items->result_array();
					$ret	=	array();
					foreach($items as $ctr=>$i) {
						// $ret['id']	=	$i['id'];
						$ret[$ctr]['label']	=	$i[$field];
						if(isset($i['id'])) {
							$ret[$ctr]['value']	=	$i['id'];
						}
					}
					header('Content-Type: application/json');
					echo json_encode($ret);
					exit();
				}
			}
		}
		#####################
		###   Temp        ###
		###   Functions   ###
		#####################
		public function getTable($table) {
			$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
			$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
			$order	=	isset($_GET['order'])?$_GET['order']:"";
			$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
			$search	=	isset($_GET['search'])?$_GET['search']:"";
			$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);
			if($data!=false) {
				header('Content-Type: application/json');
				echo json_encode($data);
				exit();
			}
			else {
				die("An unknown error has occurred! <pre>Error ". __class__ .":". __function__ .":". __line__ ."</pre>");
			}
		}
		public function encode($table="", $action="", $id="") {
			$this->Abas->checkPermissions("encoding|".$table);
			if(!isset($_SESSION['abas_login'])) { header("location:index"); }
			if($table!="") {
				$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."' AND TABLE_SCHEMA='".DBNAME."'");
				$tablefields			=	$tablefields->result();
				$data['table']			=	$table;
				$data['tablefields']	=	$tablefields;
				$viewfile				=	"temp/table_view.php";
				$mainview				=	"responsive_container.php";
				if($action=="add") {
					// $viewfile			=	"temp/table_form.php";
					$mainview			=	"temp/table_form.php";
				}
				elseif($action=="edit") {
					$data['recid']		=	$id;
					// $viewfile			=	"temp/table_form.php";
					$mainview			=	"temp/table_form.php";
				}
				elseif($action=="delete") {
					$delq				=	$this->db->query("DELETE FROM ".$table." WHERE id=".$id);
					if($delq==true) {
						$_SESSION['msg']	=	"Record was deleted!";
					}
					else {
						$_SESSION['errmsg']	=	"Record was not deleted!";
					}
				}
				elseif($action=="update") {
					foreach($tablefields as $tf) {
						if($tf->COLUMN_NAME=="id" || $tf->COLUMN_NAME=="created_by" || $tf->COLUMN_NAME=="created_on") {}
						elseif($tf->COLUMN_NAME=="password") { $update[$tf->COLUMN_NAME]	=	md5($_POST[$tf->COLUMN_NAME]); }
						elseif($tf->COLUMN_NAME=="modified_by") { $update[$tf->COLUMN_NAME]	=	$_SESSION['abas_login']['userid']; }
						elseif($tf->COLUMN_NAME=="modified_on") { $update[$tf->COLUMN_NAME]	=	date("Y-m-d H:i:s"); }
						else {
							$update[$tf->COLUMN_NAME]	=	$this->Mmm->sanitize($_POST[$tf->COLUMN_NAME]);
						}
					}
					$res	=	$this->Mmm->dbUpdate($table,$update,$id,"Encoder update ".$table." with id ".$id);
					if($res==true) {
						$this->Abas->sysMsg("sucmsg","Record Updated!");
					}
					else {
						$this->Abas->sysMsg("errmsg","Record Not Updated!");
					}
				}
				elseif($action=="insert") {
					foreach($tablefields as $tf) {
						if($tf->COLUMN_NAME=="id") {}else{
							$insert[$tf->COLUMN_NAME]	=	$this->Mmm->sanitize($_POST[$tf->COLUMN_NAME]);
						}
					}
					// $insert['stat']	=	1;
					$res	=	$this->Mmm->dbInsert($table,$insert,"Encoder insert record in ".$table);
					if($res==true) {
						$this->Abas->sysMsg("sucmsg","Record Updated!");
					}
					else {
						$this->Abas->sysMsg("errmsg","Record Not Updated!");
					}
				}
				elseif($action=="view") {
					$data['recid']		=	$id;
					$mainview			=	"temp/entry_view.php";
				}
				$data['viewfile']		=	$viewfile;
				$this->load->view($mainview,$data);
			}
			else {
				header("location:".HTTP_PATH."DBdisplay.php");
			}
		}
		public function txtmsg($number, $msg) {
			// number, msg
			$number	=	$this->Mmm->sanitize($number);
			$msg	=	$this->Mmm->sanitize($msg);
			$this->db->query("INSERT INTO clients (company, address) VALUES ('".$number."','".html_entity_decode($msg)."')");
		}
		public function geolocate() {
			$jsondata = $this->Abas->geolocate("10.339803839612163,123.95225150623813");
			$this->Mmm->debug($jsondata);
			$this->load->view("container.php",$data);
		}
		public function ajaxNotifs($seen=false) {
			if(!isset($_SESSION['abas_login'])) { header("location:index");die("<script>window.location = '".HTTP_PATH."';</script>"); }
			$return	=	array();

			if($seen==true) { 
				$this->Abas->getNotifications(); 
			}

			// gets all notifications within 10 seconds period
			if($_SESSION['abas_login']['role']=="Administrator") { // Admins get all notifications
				$notifs	=	$this->db->query("SELECT * FROM notifications WHERE tdate>='".date("Y-m-d H:i:s", strtotime("-10 seconds"))."' ORDER BY tdate DESC");
			}else{
				$notifs	=	$this->db->query("SELECT * FROM notifications WHERE tdate>='".date("Y-m-d H:i:s", strtotime("-10 seconds"))."' AND (audience='everyone' OR audience='".$_SESSION['abas_login']['role']."') ORDER BY tdate DESC");
			}
			if($notifs->row()) {
				$notifs	=	$notifs->result_array();
				if(!empty($notifs)) {
					foreach($notifs as $ctr=>$notif) {
						/*$views	=	$this->db->query("SELECT * FROM notification_views WHERE userid=".$_SESSION['abas_login']['userid']." AND notification_id=".$notif['id']);
						if($views) {
							$views=$views->result_array();
							if(count($views)>0) {
								unset($notifs[$ctr]);
							}
							else {
								// set notifications as 'viewed'
								$this->db->query("INSERT INTO notification_views (tdate, userid, notification_id) VALUES ('".date("Y-m-d H:i:s")."', '".$_SESSION['abas_login']['userid']."', '".$notif['id']."')");
						*/
								//format into JSON
								$return[$ctr]['type']		=	$notif['type'];
								$return[$ctr]['title']		=	$notif['title'];
								$return[$ctr]['content']	=	$notif['content'];
							//}
						//}
					}
				}
			}

			//display unviewed notifications
			//$this->Mmm->debug($notifs);
			//$this->Mmm->debug($return);
			echo json_encode($return);
			//echo json_encode($return, JSON_UNESCAPED_SLASHES);
		}
		public function forgot() {$data=array();
			if(isset($_POST['username'])) {
				$username	=	$this->Mmm->sanitize($_POST['username']);
				$check	=	$this->db->query("SELECT * FROM users WHERE username='".$username."'");
				if($check) {
					if($check->row()) {
						$user	=	(array)$check->row();
						$newkey	=	md5(microtime());
						$update['password_reset']	=	$newkey;
						$resetpass	=	$this->Mmm->dbUpdate("users", $update, $user['id'], $user['username']." requested password reset");
						if($resetpass) {
							$msg	=	"
								<p>Your password reset request has been received!</p>
								<p>You may use the link <a href='".HTTP_PATH."home/reset/".$newkey."'>".HTTP_PATH."home/reset/".$newkey."</a> to reset your password.</p>
								<p>If you are still unable to login, please contact the I.T. department.</p>
							";
							$this->Mmm->sendEmail($user['email'], "ABAS Account Password Reset", $msg);
							$this->Abas->sysMsg("sucmsg", "Your password has been reset! Please check your email for further instructions.");
							$this->Abas->redirect(HTTP_PATH);
						}
					}
					else {
						$this->Abas->sysMsg("warnmsg","User not found!");
						header("location:".HTTP_PATH."home/forgot");die("<script>window.location='".HTTP_PATH."home/forgot';</script>");
					}
				}
				else {
					$this->Abas->sysMsg("warnmsg","User not found!");
					header("location:".HTTP_PATH."home/forgot");die("<script>window.location='".HTTP_PATH."home/forgot';</script>");
				}
			}else{
				$this->load->view("forgot_password.php",$data);
			}
		}
		public function reset($code="") {$data=array();
			if($code=="") { $this->Abas->redirect(HTTP_PATH); }
			$user	=	$this->db->query("SELECT * FROM users WHERE password_reset='".$code."'");
			if(!$user) { $this->Abas->redirect(HTTP_PATH); }
			if(!$user->row()) { $this->Abas->redirect(HTTP_PATH); }
			$user	=	$user->result_array();
			if(isset($_POST['password']) && isset($_POST['password2'])) {
				if($_POST['password']==$_POST['password2']) {
					$password	=	md5($_POST['password']);
					$update		=	$this->Mmm->query("UPDATE users SET password='".$password."', password_reset=NULL WHERE password_reset='".$code."'", "Reset account ".$user['username']." via forgot password utility");
					if($update) {
						$this->Abas->sysMsg("sucmsg", "Your password has been reset! You may log in with your new password.");
						header("location:".HTTP_PATH);die("<script>window.location='".HTTP_PATH."';</script>");
					}
				}
			}
			$data['code']		=	$code;
			$data['viewfile']	=	"reset_password.php";
			$this->load->view("responsive_container.php",$data);
		}
		
		public function get_company_name($vessel_id){
			$vessel = $this->Abas->getVessel($vessel_id);
			if($vessel){
				$company = $this->Abas->getCompany($vessel->company);
				$data['company_id'] = $company->id;
				$data['company_name'] = $company->name;
				echo json_encode($data);
			}
		}

		public function myFunction(){
			$items = $this->Abas->getItems('users');
			$this->Mmm->debug($items);

		}

		
	}
?>