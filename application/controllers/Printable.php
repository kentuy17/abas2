<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Printable extends CI_Controller {
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		$this->load->model("Hr_model");
		$this->load->model("Payroll_model");
		$this->load->model("Mmm");
		$this->output->enable_profiler(FALSE);
		// if(!isset($_SESSION['abas_login'])) { header("location:".HTTP_PATH."home"); }
		require(WPATH.'assets/fpdf/fpdf.php');
	}
	public function index() {
		$this->Abas->redirect(HTTP_PATH);
	}
	public function header($fpdf, $company_id) {
		$company		=	$this->Abas->getCompany($company_id);
		$company_name	=	"Avega Brothers Integrated Shipping Corp.";
		if($company != false) {
			$company_name	=	$company->name;
			// $this->Mmm->debug($company);
		}
		$fpdf->SetFont('Courier','B',11);
		$fpdf->MultiCell(195, 6, $company_name, 0, 'C');
		$fpdf->SetFont('Courier', '', 9);
		$fpdf->MultiCell(195, 5, 'MANILA OFFICE: 8070 Tanguile St. Cor. Estrella Ave.', 0, 'C');
		$fpdf->MultiCell(195, 5, 'San Antonio Village, Makati City', 0, 'C');
		$fpdf->MultiCell(195, 5, 'Tel Nos. 899-5879 / 899-5794 / 890-4484 Fax No. 897-3491', 0, 'C');
		$fpdf->MultiCell(195, 5, 'CEBU OFFICE: Avega Bros. Bldg., J. De Veyra St.', 0, 'C');
		$fpdf->MultiCell(195, 5, 'North Reclamation Area, Cebu City 6000', 0, 'C');
		$fpdf->MultiCell(195, 5, 'Tel No. (032) 260-0945 Telefax Nos. (032) 231-2526 / (032) 340-4937', 0, 'C');
		$fpdf->Ln();
		$fpdf->Ln();
	}
	public function pdfTable($fpdf, $header, $data){
		// Column widths

		$w = array(20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20);
		// Header
		// $this->Mmm->debug($header);
		for($i=0;$i<count($header);$i++) {
			$width	=	strlen($header[$i]['title']) * 2;
			$fpdf->Cell($width,7,$header[$i]['title'],1,0,'C');
		}
			$fpdf->Ln();
		// Data
		foreach($data as $num=>$arr) {
			foreach($arr as $row=>$val) {
				// $this->Mmm->debug($val);
				// $this->Mmm->debug($val);
				if($row != false) {
					$fpdf->Cell(20,6,$val,'LR');
				}
			}
			$fpdf->Ln();
		}
		// Closing line
		$fpdf->Cell(array_sum($w),0,'','T');
	}
	public function payroll ($form="", $record="") {
		$fpdf	=	new FPDF('L','mm',array(355.6,215.9));
		// $fpdf->control_no = 0; // ???
		$fpdf->AddPage();
		$fpdf->SetAutoPageBreak(true, 10);
		$fpdf->SetFont('Courier','B',9);
		if($form=="") {
			$this->header($fpdf, 1);
			$fpdf->MultiCell(195, 5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 0, 'C');
		}
		elseif($form=="summary") { // begin summary
			// get the relevant data! $record == payroll id
			$summary=$details=array();
			$display		=	false;
			if(is_numeric($record)) { // creates and sorts the data
				$summary	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$record);
				$details	=	$this->db->query("SELECT * FROM hr_payroll_details WHERE payroll_id=".$record);
				if($details!=false) {
					if($details->row()) {
						$summary	=	$summary->result_array();
						$details	=	$details->result_array();
						// $this->Mmm->debug($summary);
						$header		=	array("Emp. ID", "Name", "Position", "Salary", "Allwnce", "Others", "Subtotal", "W Tax", "SSS", "Ph", "Pi", "ELF", "Loan", "Net");
						foreach($details as $d) {
							$e		=	$this->Abas->getEmployee($d['emp_id']);
							// $this->Mmm->debug($d);
							$others		=	$d['overtime_amount']+$d['bonus']+$d['allowance'];
							$fullname	=	ucwords(strtolower($e['full_name']));
							$positionnme=	ucwords(strtolower($e['position_name']));

							$fullname	=	strlen($fullname) > 30 ? substr($fullname,0,27)."..." : $fullname;
							$positionnme=	strlen($positionnme) > 35 ? substr($positionnme,0,32)."..." : $positionnme;

							$subtotal	=	($d['salary']/2) + $others;
							$data[]	=	array(
											$e['employee_id'],
											$fullname,
											$positionnme,
											$d['salary']/2,
											$d['allowance'],
											$d['overtime_amount']+$d['bonus'],
											$others,
											$subtotal
										);
						}
						$display	=	true;
					}
				}
			}
			else {
				$this->Mmm->debug($record);
			}

			if($display==true) { // render the data
				$w = array(20, 60, 70, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15);
				for($i=0;$i<count($header);$i++)
					$fpdf->Cell($w[$i],7,$header[$i],1,0,'C');
				$fpdf->Ln();
				foreach($data as $row) { // rows
					for($ctr=0; $ctr<14; $ctr++) { // columns
						if(isset($row[$ctr])) {
							$fpdf->Cell($w[$ctr],6,$row[$ctr],'LR');
						}
					}
					$fpdf->Ln();
				}
				$fpdf->Cell(array_sum($w),0,'','T');
			}
		} // end summary
		elseif($form=="payslips") {

		}
		elseif($form=="bir") {

		}
		elseif($form=="sss") {

		}
		elseif($form=="ph") {

		}
		elseif($form=="pi") {

		}
		else {

		}



		$fpdf->Output(); // rock 'n' roll!

		// $data['viewfile']	=	"payroll/payroll_view.php";
		// $this->load->view('pdf-container.php',$data);
	}
	public function hr() {
		if(empty($_POST)) { // select which info to be displayed
			// $this->Mmm->debug($this->Abas->getEmployee(43));
			$data['viewfile']		=	"hr/pre-report.php";
			$this->load->view("container.php",$data);
		}
		else { // display PDF of info
			// $this->Mmm->debug($_POST);
			$sortby	=	$this->Mmm->sanitize(strtolower($_POST['sortby']));
			if($sortby=="vessel") {
				$column	=	"vessel_id";
				$sorttable	=	$this->Abas->getVessels();
			}
			elseif($sortby=="dept") {
				$column		=	"department";
				$sorttable	=	$this->Abas->getDepartments();
			}
			elseif($sortby=="company") {
				$column	=	"company_id";
				$sorttable	=	$this->Abas->getCompanies();
			}

			foreach($sorttable as $st) {
				$all_employees_per_sort	=	$this->db->query("SELECT id FROM hr_employees WHERE ".$column."=".$st->id." AND stat=1"); // all employees per sort
				if($all_employees_per_sort!=false) {
					if($all_employees_per_sort->row()) {
						$all_employees_per_sort	=	$all_employees_per_sort->result();
						// $this->Mmm->debug($all_employees_per_dept);
						foreach($all_employees_per_sort as $aeps) {
							// $aeps	=	$this->Abas->getEmployee($aeps->id);
							if($aeps!=false) {
								unset($_POST['sortby']);
								$fields	=	"";
								$x=0;
								foreach($_POST as $fn=>$on) {
									$fieldtitles[$x]['title']	=	$fn;
									$fieldtitles[$x]['width']	=	20;
									if($fn=="full_name") $fn =	"CONCAT(last_name , ', ' , first_name , ' ' , middle_name) AS full_name";
									$fields	.=	$fn.", ";
									$x++;
								}
								$fields	=	rtrim($fields, ", ");
								$query	=	"SELECT ".$fields." FROM hr_employees WHERE id=".$aeps->id."";
								$details=	$this->db->query($query);
								$details=	$details->result_array();
								// $this->Mmm->debug($details);
								$empdeets[]	=	$details[0];
							}
						}
					}
				}
			}
			$fpdf	=	new FPDF('P');
			$fpdf->AddPage();

			// $fpdf->SetAutoPageBreak(true, 10);

			$fpdf->Ln();
			$fpdf->SetXY(0,0);

			$fpdf->SetFont('Courier', '', 9);
			$fpdf->MultiCell(195, 5, '', 0, 'C');
			$this->pdfTable($fpdf, $fieldtitles, $empdeets);
			$fpdf->Output("employees", 'I');
		}
	}
	public function cheque() {
		if(!isset($_POST['payee'], $_POST['peso_amount'], $_POST['cheque_date'])) {
			$data['viewfile']		=	"printable/cheque_form.php";
			$this->load->view("container.php",$data);
		}
		else {
			$data['payee']			=	"***".iconv('UTF-8', 'windows-1252', $_POST['payee'])."***"; // encodes special unicode characters
			$data['peso_amount']	=	"**".$this->Abas->currencyFormat($this->Mmm->sanitize($_POST['peso_amount']))."**";
			$data['peso_word']		=	ucwords(str_replace("-"," ",$this->Mmm->chequeTextFormat($_POST['peso_amount'])));
			$data['cheque_date']	=	date("F j, Y",strtotime($_POST['cheque_date']));
			$data['cheque_type']	=	$_POST['cheque_type'];
			// $data['payee']			=	"***".$this->Mmm->sanitize("Juan Tomas")."***";
			// $data['peso_amount']	=	"**".$this->Abas->currencyFormat(999999.99)."**";
			// $data['peso_word']		=	$this->Mmm->chequeTextFormat(999999.99);
			// $data['cheque_date']	=	date("F j, Y",strtotime("01/06/2016"));
			$this->load->view("printable/cheque_printable.php",$data);
		}
	}
	public function ph_premium_payment_slip() {
		if(!isset($_POST['pin'], $_POST['member_name'], $_POST['member_type'], $_POST['period_from'], $_POST['period_to'], $_POST['amount_paid'])) {
			$data['viewfile']		=	"printable/ph_premium_payment_slip_form.php";
			$this->load->view("container.php",$data);
		}
		else {
			$data['pin']			=	$_POST['pin'];
			$data['business_name']	=	isset($_POST['business_name']) ? $_POST['business_name'] : "" ;
			$data['member_name']	=	iconv('UTF-8', 'windows-1252',$_POST['member_name']);
			$data['member_type']	=	$_POST['member_type'];
			$data['period_from']	=	date("Y-m-d", strtotime($_POST['period_from']));
			$data['period_to']		=	date("Y-m-d", strtotime($_POST['period_to']));
			$data['amount_paid']	=	number_format($_POST['amount_paid'], 2);

			// $data['pin']			=	"123456789ABC";
			// $data['business_name']	=	"Philippine Tramp Shipping Corporation";
			// $data['member_name']	=	"Mark Miguel Juat Maske";
			// $data['member_type']	=	"Voluntary";
			// $data['period_from']	=	"03/04/2016";
			// $data['period_to']		=	"03/04/2017";
			// $data['amount_paid']	=	99999.99;
			$this->load->view("printable/ph_premium_payment_slip_printable.php",$data);
		}
	}
}
?>