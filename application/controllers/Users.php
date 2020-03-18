<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends CI_Controller {
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Mmm");
		$this->load->model("Abas");
		define("SIDEMENU","Users");
		$this->output->enable_profiler(FALSE);
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
	}

	public function index () {
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
		$data['viewfile']	=	"users/users_view.php";
		$this->load->view('gentlella_container.php',$data);
	}
	public function load(){
		$table	= 'users';
		$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
		$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
		$order	=	isset($_GET['order'])?$_GET['order']:"";
		$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
		$search	=	isset($_GET['search'])?$_GET['search']:"";
		$data	=	$this->Abas->createBSTable($table,$search,$limit,$offset,$order,$sort);
		if($data!=false) {
			foreach($data['rows'] as $ctr=>$user) {
				if($user['stat']==1) {
					$data['rows'][$ctr]['stat']	= "Activated";
				}else{
					$data['rows'][$ctr]['stat']	= "Deactivated";	
				}
			}
			header('Content-Type: application/json');
			echo json_encode($data);
			exit();
		}
		else {
			die("An unknown error has occurred! <pre>Error ". __class__ .":". __function__ .":". __line__ ."</pre>");
		}
	}
	public function add ($id="") {$data=array();
		$this->Abas->checkPermissions("users|". __function__ );
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
		$mainview		=	"users/users_form.php";
		$this->load->view($mainview,$data);
	}
	public function edit($id) {$data=array();
		$this->Abas->checkPermissions("users|edit");
		if(isset($id)) {
			$check	=	$this->db->query("SELECT * FROM users WHERE id=$id");
			$data['hr_name'] = $this->Abas->getEmpNameWithId($id);
			if($check->row()) {
				$check	=	$check->row();
				$data['existing']	=	$check;
			}
			else { $this->Abas->sysMsg("errmsg","Record not found"); }
		}
		else { $this->Abas->sysMsg("errmsg","ID not set"); }
		$mainview	=	"users/users_form.php";
		$this->load->view($mainview,$data);
	}
	public function delete($id) {$data=array();
		/*
		$this->Abas->checkPermissions("users|". __function__ );
		if(isset($id)) {
			$this->Mmm->query("DELETE FROM users WHERE id=$id");
			$res			=	$this->db->query("SELECT * FROM users WHERE id=$id");
			$_SESSION['msg']	=	(count($res)==1)?"User has successfully been deleted!":"An error has occurred, please try again.";
		}
		else {
			$data['errmsg']	=	"ID not set";
		}
		*/
		$this->Abas->sysMsg("errmsg","Delete function disabled!");
		$this->Abas->redirect(HTTP_PATH."users/");
	}
	public function insert() {
		$this->Abas->checkPermissions("users|". __function__ );
		if(isset($_POST['username'],$_POST['email'])) {
			$fname			=	$this->Mmm->sanitize($_POST['first_name']);
			$mname			=	$this->Mmm->sanitize($_POST['middle_name']);
			$lname			=	$this->Mmm->sanitize($_POST['last_name']);
			$uname			=	$this->Mmm->sanitize($_POST['username']);
			$role			=	$this->Mmm->sanitize($_POST['role']);
			$eml			=	$this->Mmm->sanitize($_POST['email']);
			$user_location	=	$this->Mmm->sanitize($_POST['user_location']);
			/* signature upload */
			//$config['upload_path'] = WPATH .'assets'.DS.'images'.DS.'digitalsignatures'.DS;
			$config['upload_path'] = WPATH.'assets/images/digitalsignatures/';
			$config['allowed_types'] = 'jpg|png';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('picture')) {
				$error = array('error' => $this->upload->display_errors());
				$this->Abas->sysMsg("errmsg","Signature error: ".$error['error']);
			}
			else {
				$upload_data=$this->upload->data();
				$insert['signature']	=	$upload_data['file_name'];
				$this->Abas->sysMsg("sucmsg","Signature was uploaded!");
			}
			/* signature upload */
			$plaintext	=	md5(microtime());
			$pword		=	md5($plaintext);
			$insert['first_name']			=	$fname;
			$insert['middle_name']			=	$mname;
			$insert['last_name']			=	$lname;
			$insert['username']				=	$uname;
			$insert['role']					=	$role;
			$insert['stat']					=	1;
			$insert['password']				=	$pword;
			$insert['email']				=	$eml;
			$insert['user_location']		=	$user_location;
			$insert['require_reset']		=	1;
			$insert['notification_timeout']	=	10000;
			$insert['created']				=	date("Y-m-d H:i:s");
			$checkinsert			=	$this->Mmm->dbInsert("users",$insert, "Add new user");
			if($checkinsert){
				$this->Abas->sysMsg("sucmsg","New user added!");
				$check				=	$this->db->query("SELECT MAX(id) FROM users");
				$check				=	$check->row();
				$archive['user_id']	=	$check->id;
				$archive['password']=
				$checkarchive		=	$this->Mmm->dbInsert("password_history", $archive, "Archive initial password for ".$insert['username']);
				$subject			=	"ABAS account is now ready!";
				$msg				=	"<p>Hello ".$insert['first_name']." ".$insert['last_name']."!</p>";
				$msg				.=	"<p>You can now access ABAS at ".HTTP_PATH.". Your login information are as follows:</p>";
				$msg				.=	"<p>Username: ".$insert['username']."</p>";
				$msg				.=	"<p>Temporary Password: ".$plaintext."</p>";
				$msg				.=	"<p>You will be asked again to log-in using these information, and we will require you to immediately change the temporary password. Please do so, and use a secure password that you will not easily forget. Alpha-numeric and at least 10 chars. long (eg. r0B0r3X_$uP4h).</p>";
				$msg				.=	"<p>Please be informed also that sharing of passwords to other employees and non-employees is strictly prohibited. Failure to practice confidentially will result to disciplinary action.</p>";
				$msg				.=	($checkarchive)?"<p>Your new password will expire in ".strtotime($insert['created']	." +6 months").".</p>":"";
				$checkmail			=	$this->Mmm->sendEmail($insert['email'], $subject, $msg);
				$this->Abas->sysMsg("msg","An email has been sent to ".$insert['email']." containing the user's login information.");
			}
			else { $this->Abas->sysMsg("errmsg","User not added! Please try again."); }
		}
		else { $this->Abas->sysMsg("errmsg","Your input is incomplete!"); }
		$this->Abas->redirect(HTTP_PATH."users/");
	}
	public function update($id) {
		$this->Abas->checkPermissions("users|". __function__ );
		if(is_numeric($id)) {
			$fname			=	$this->Mmm->sanitize($_POST['first_name']);
			$mname			=	$this->Mmm->sanitize($_POST['middle_name']);
			$lname			=	$this->Mmm->sanitize($_POST['last_name']);
			$uname			=	$this->Mmm->sanitize($_POST['username']);
			// $oldpword		=	$this->Mmm->sanitize($_POST['oldpass']);
			// $pword			=	$this->Mmm->sanitize($_POST['password']);
			// $pword2			=	$this->Mmm->sanitize($_POST['confirm']);
			$eml			=	$this->Mmm->sanitize($_POST['email']);
			$role			=	$this->Mmm->sanitize($_POST['role']);
			$user_location	=	$this->Mmm->sanitize($_POST['user_location']);
			//Modfied
			$name 			= 	$_POST['employee'];
			$after			= 	substr($name, strpos($name, '(') + 1);
			$hr_id 			= 	strtok($after, ')');

			$check			=	$this->db->query("SELECT * FROM users WHERE id=$id");
			$check			=	$check->row();
			if(!empty($check)) {
				/* signature upload */
				$config['upload_path'] = WPATH .'assets'.DS.'images'.DS.'digitalsignatures'.DS;
				$config['allowed_types'] = 'jpg|png';
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('picture')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->Abas->sysMsg("errmsg",$error['error']);
				}
				else {
					$upload_data=$this->upload->data();
					$update['signature']	=	$upload_data['file_name'];
					$this->Abas->sysMsg("sucmsg","Image uploaded!");
				}
				/* signature upload */
				$update['username']		=	$uname;
				$update['first_name']	=	$fname;
				$update['middle_name']	=	$mname;
				$update['last_name']	=	$lname;
				$update['email']		=	$eml;
				$update['role']			=	$role;
				$update['user_location']=	$user_location;
				$update['modified']		=	date("Y-m-d H:i:s");
				$checkupdate	=	$this->Mmm->dbUpdate("users",$update, $id, "Update ABAS account of ".$uname);
				$this->Abas->updateItem('hr_employees',array('user_id'=>$id),array('id'=>$hr_id));
				if($checkupdate) { 
					$this->Abas->sysMsg("sucmsg","User updated!"); 
				}else { 
					$this->Abas->sysMsg("errmsg","User not updated! Please try again."); 
				}
			}
		}else { 
			$this->Abas->sysMsg("errmsg","Invalid ID!"); 
		}
		$this->Abas->redirect(HTTP_PATH."users/");
	}
	public function permissions($id) {$data=array();
		$this->Abas->checkPermissions("users|". __function__ );
		if($id!="") {
			$check	=	$this->db->query("SELECT * FROM users_permissions WHERE user_id=".$id);
			$user	=	$this->db->query("SELECT * FROM users WHERE id=".$id);
			if($user) {
				$check	=	$check->result();
				$user	=	$user->row();
				$data['user']		=	$user;
				$data['existing']	=	$check;
			}
			else {
				$this->Abas->sysMsg("errmsg","Record not found");
			}
		}
		else {
			$this->Abas->sysMsg("errmsg","ID not set");
		}
		$mainview	=	"users/permissions_form.php";
		$this->load->view($mainview,$data);
	}
	public function update_permissions($id) {
		$this->Abas->checkPermissions("users|permissions");
		if(is_numeric($id)) {
			if(count($_POST)>0) {
				$userid	=	$this->Mmm->sanitize($id);
				$del	=	$this->db->query("DELETE FROM users_permissions WHERE user_id=".$userid);
				$insq	=	"INSERT INTO users_permissions (user_id, page) VALUES ";
				foreach($_POST as $module => $functions) {
					foreach($functions as $fi=>$f) {
						$userid		=	$userid;
						$page		=	$this->Mmm->sanitize($module."|".$_POST[$module][$fi]);
						$insq	.=	"(";
						$insq		.=	"'".$userid."', ";
						$insq		.=	"'".$page."'";
						$insq	.=	"), ";
						if($_POST[$module][$fi]=="add") {
							$insq	.=	"(";
							$insq		.=	"'".$userid."', ";
							$insq		.=	"'".$module."|insert'";
							$insq	.=	"), ";
						}
						elseif($_POST[$module][$fi]=="edit") {
							$insq	.=	"(";
							$insq		.=	"'".$userid."', ";
							$insq		.=	"'".$module."|update'";
							$insq	.=	"), ";
						}
					}
				}
				$insq	=	rtrim($insq, ", ");
				$this->Mmm->query($insq, "Update user permissions");
				// echo $insq;
				$this->Abas->sysMsg("sucmsg","Permissions updated!");
				$this->Abas->redirect(HTTP_PATH."users/");
			}
			else {
				$this->Abas->sysMsg("warnmsg","User permissions unchanged.");
				$this->Abas->redirect(HTTP_PATH."users/");
			}
		}
		else {
			$this->Abas->sysMsg("errmsg","Invalid user ID!");
			$this->Abas->redirect(HTTP_PATH."users/");
		}
	}
	public function reset_password($id) {
		$this->Abas->checkPermissions("users|reset_password");
		$user	=	$this->Abas->getUser($id);
		$newpass = 'avegabros';
		if($user) {
			$resetkey	=	 substr(md5(microtime()),rand(0,26),8);
			$newpass = md5($newpass);
			$check	=	$this->Mmm->query("UPDATE users SET password='".$newpass."' WHERE id=".$id, "Reset password for ".$user['username']);
			if($check) { 
				$this->Abas->sysMsg('sucmsg', 'Password for '.$user['username'].' has been reset to \"avegabros\"'); 
			}
			else{ 
				$this->Abas->sysMsg("errmsg", "Password for ".$user['username']." has not been reset! Please try again."); 
			}
		}
		else { $this->Abas->sysMsg("errmsg", "User not found!"); }
		$this->Abas->redirect(HTTP_PATH."users/");
	}
	public function deactivate($id) {
		$this->Abas->checkPermissions("users|deactivate_account");
		$user	=	$this->Abas->getUser($id);
		if($user) {
			$check	=	$this->Mmm->query("UPDATE users SET stat='0' WHERE id='".$id."'", "Deactivate ".$user['username']);
			$check	=	$this->Mmm->query("DELETE FROM users_permissions WHERE user_id='".$id."'", "Revoke permissions for ".$user['username']);
			if($check) { $this->Abas->sysMsg("sucmsg", "".$user['username']." has been deactivated!"); }
			else { $this->Abas->sysMsg("errmsg", "".$user['username']." has NOT been deactivated! Please try again."); }
		}
		else { $this->Abas->sysMsg("errmsg", "User not found!"); }
		$this->Abas->redirect(HTTP_PATH."users/");
	}
	public function activate($id) {
		$this->Abas->checkPermissions("users|deactivate_account");
		$user	=	$this->Abas->getUser($id);
		if($user) {
			$check	=	$this->Mmm->query("UPDATE users SET stat='1' WHERE id='".$id."'", "Activate ".$user['username']);
			
			if($check) { $this->Abas->sysMsg("sucmsg", "".$user['username']." has been activated!"); }
			else { $this->Abas->sysMsg("errmsg", "".$user['username']." has NOT been activated! Please try again."); }
		}
		else { $this->Abas->sysMsg("errmsg", "User not found!"); }
		$this->Abas->redirect(HTTP_PATH."users/");
	}
	public function summary_report($action){
		if(!empty($_POST)){
			$data = array();
			$username = $_POST['username'];
			$role = $_POST['role'];
			$location = $_POST['location'];
			$date_from = $_POST['date_from'];
			$date_to = $_POST['date_to'];
			$status = $_POST['status'];
			$require_reset = $_POST['require_reset'];
			$activity_from = $_POST['activity_from'];
			$activity_to = $_POST['activity_to'];
			if($action=='result'){
				$sql_append = '';
				if($username!=""){
					$sql_append .= ' AND username LIKE "%'.$username.'%"';
				}
				if($role!=""){
					$sql_append .= ' AND role="'.$role.'"';
				}
				if($location!=""){
					$sql_append .= ' AND user_location="'.$location.'"';
				}
				if($date_from && $date_to){
					$sql_append .= ' AND created BETWEEN "'.$date_from.'" AND "'.$date_to.'"';
				}
				if($status!=""){
					$sql_append .= ' AND stat='.$status;
				}
				if($require_reset!=""){
					$sql_append .= ' AND require_reset='.$require_reset;
				}
				$sql = "SELECT * FROM users WHERE 1=1".$sql_append;
				$query = $this->db->query($sql);
				if($query){
					$result = $query->result_array();
					$data['users'] = $result;
					foreach($result as $ctr=>$a) {
						if($activity_from!="" && $activity_to!=""){
							$sql2 = "SELECT * FROM db_activity WHERE action LIKE '%".$a['username']."%' AND timestamp BETWEEN '".$activity_from."' AND '".$activity_to."' ORDER BY timestamp DESC";
						}else{
							$ago = date('Y-m-d', strtotime('today - 30 days'));
							$sql2 = "SELECT * FROM db_activity WHERE action LIKE '%".$a['username']."%' AND timestamp >='".$ago."' ORDER BY timestamp DESC";
						}
						$query2 = $this->db->query($sql2);
						if($query2){
							$data['users'][$ctr]['activities'] = $query2->result_array();
						}

						$sqlperm = "SELECT * FROM users_permissions WHERE user_id=".$a['id'];
						$queryperm = $this->db->query($sqlperm);
						if($queryperm){
							$data['users'][$ctr]['permissions'] = $queryperm->result_array();
						}
					}
					$data['viewfile']	=	"users/report.php";
					$this->load->view('gentlella_container.php',$data);
				}


			}elseif($action=='filter'){
				$this->load->view('/users/filter.php');
			}
		}else{
			$this->load->view('/users/filter.php');
		}
	}

	public function autocomplete_employee(){
		$search	=	$this->Mmm->sanitize($_GET['term']);
		$search	=	str_replace(" ", "%", $search);
		$sql	=	"SELECT id, last_name, first_name, middle_name, concat(last_name,', ',first_name,' ', LEFT(middle_name, 1),'.','(',id,')') as full_name, position,department FROM hr_employees WHERE last_name LIKE '%".$search."%' OR first_name LIKE '%".$search."%' OR middle_name LIKE '%".$search."%' ORDER BY last_name LIMIT 0, 10";
		$items	=	$this->db->query($sql);
		if($items) {
			if($items->row()) {
				$items	=	$items->result_array();
				$ret	=	array();
				foreach($items as $ctr=>$i) {
					
					$ret[$ctr]['label']	=	$i['full_name'];
					$ret[$ctr]['value']	=	$i['id'];
				}
				header('Content-Type: application/json');
				echo json_encode($ret);
				exit();
			}
		}
	}		

	public function myFunction()
	{
		$name = $_POST['employee'];
		$after = substr($name, strpos($name, "(") + 1);
		$id = strtok($after, ')');

		$this->Mmm->debug($id);
	}

}

?>
