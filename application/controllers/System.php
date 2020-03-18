<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class System extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			$this->load->database();
			$this->load->model("Mmm");
			$this->load->model("Abas");
			$this->output->enable_profiler(FALSE);
			define("SIDEMENU", "Administrator");
			if(!isset($_SESSION['abas_login'])) { header("location:home"); }
		}
		public function index () {
			$data['viewfile']	=	"echo.php";
			$this->load->view('gentlella_container.php',$data);
		}
		public function sys_info() {
			if($_SESSION['abas_login']['role']=="Administrator") {
				echo "<div class='panel-body' style='overflow-y: auto; height:500px;'><h2>Server Info:</h2>";
				echo "<pre>";
				print_r("<b>CodeIgniter Version:</b> ".CI_VERSION.'<br>');
				print_r("<b>Current User Session:</b><br>");
				print_r($_SESSION);
				print_r("<b>Server Specifications:</b><br>");
				print_r($_SERVER);
				echo "</pre>";
				echo "</div>";
			}
		}
		public function logs($action=""){$data=array ();
			if($_SESSION['abas_login']['role']=='Administrator'){
				$data['viewfile']=	"system_logs.php";
				$mainview="gentlella_container.php";
				if ($action=="json"){
					if (isset($_GET['order']) || isset($_GET['limit']) || isset($_GET['offset'])){
						$limit	=	isset($_GET['limit'])?$_GET['limit']:"";
						$offset	=	isset($_GET['offset'])?$_GET['offset']:"";
						$order	=	isset($_GET['order'])?$_GET['order']:"";
						$sort	=	isset($_GET['sort'])?$_GET['sort']:"";
						$search	=	isset($_GET['search'])?$_GET['search']:"";
						$data	=	$this->Abas->createBSTable("db_activity",$search,$limit,$offset,$order,$sort);
						foreach($data['rows'] as $ctr=>$activity) {
							$data['rows'][$ctr]['timestamp']	=	date("j F Y H:i:s", strtotime($activity['timestamp']));
						}
						header('Content-Type: application/json');
						echo json_encode($data);
						exit();
					}
				}
				$this->load->view($mainview,$data);
			}
		}
		public function db_encoding() {
			if($_SESSION['abas_login']['role']=="Administrator") {
				$this->Abas->checkPermissions("database|encoding");
				$data['viewfile']=	"db_encoding.php";
				$mainview="gentlella_container.php";
				$this->load->view($mainview,$data);
			}
		}
		public function query() {$data=array();
			if($_SESSION['abas_login']['role']!="Administrator") { header("location:".HTTP_PATH."index"); }
			$this->Abas->checkPermissions("database|query");
			//$this->Abas->sysMsg("warnmsg", "Please avoid using abbreviations in table/column names! [Columns with _num or _no should be renamed _number]");
			$result = NULL;
			if(isset($_POST['sql_query'])) {
				if(empty($_POST['query_purpose']) || empty($_POST['sql_query'])) {
					$this->Abas->sysMsg("errmsg", "Your submission has missing fields!");
					$this->Abas->redirect(HTTP_PATH."system/query");
				}
				$query	=	$this->Mmm->query($_POST['sql_query'], "Custom query: ".$_POST['query_purpose']);
				if($query){
					if(!is_bool($query)){
						$result = $query->result();
						$msg	=	"Query was successful!";
						$this->Abas->sysMsg("sucmsg", $msg);

					}else{
						$result = $_POST['sql_query'];
						if($query==0){
							$msg	=	"Query failed!";
							$this->Abas->sysMsg("warnmsg", $msg);
						}else{
							$msg	=	"Query was successful!";
							$this->Abas->sysMsg("sucmsg", $msg);
						}
					}
				}else{
					$msg	=	"Query failed!";
					$this->Abas->sysMsg("warnmsg", $msg);

				}
			}
			$data['viewfile']	=	"query.php";
			$data['result']	=	$result;
			$this->load->view("gentlella_container.php",$data);
		}
		public function open_change_log(){
			$logs = $this->Abas->readChangeLog();
			echo "<div class='panel-body' style='overflow-y: auto; height:500px;'><h2>Changelog:</h2><pre>".nl2br($logs['logs'])."</pre></div>";
		}
	}
?>