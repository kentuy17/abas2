<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		session_start();
		$this->load->database();
		$this->load->model("Abas");
		$this->load->model("Operation_model");
		$this->load->model("Billing_model");
		$this->load->model("Purchasing_model");
		$this->load->model("Inventory_model");
		$this->load->model("Asset_Management_model");
		$this->load->model("Mmm");
		$this->output->enable_profiler(FALSE);
		//var_dump($_SESSION);exit;
		if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH."home"); }
		$this->Abas->checkPermissions("database|tools");
	}
	public function index()	{
			
		$methods =  get_class_methods($this);

		foreach($methods as $method){
			echo $method.'<br>';
		}
		
	}
	public function import_excel()	{$data=array();
			
				$filename ='trucks.csv';
      			$pathToFile = LINK.'imports/' . $filename;  			
               	
				 $file = fopen($pathToFile, "r");
				 $data = "3 STAR,NCR|
				A. Ong Trucking,NCR|
				ABAS,NCR|
				AC5 TRUCKING,NCR|
				ACRO,NCR|
				ALIGRE,NCR|
				Alinapon,NCR|
				ALNOR,NCR|
				AMG,NCR|
				APRI,NCR|
				BALMES,NCR|
				BILLHILL,NCR|
				BOSTON Trucking,NCR|
				CA�EDO,NCR|
				CB. ROMERO,NCR|
				Clavacio,NCR|
				Dagar Trucking,NCR|
				DIDA TRUCKING,NCR|
				DJR TRUCKING,NCR|
				EVGalvez Trucking,NCR|
				EVL TRUCKING,NCR|
				FLJS TRUCKING,NCR|
				FRV Trucking,NCR|
				GMC,NCR|
				Goldmines,NCR|
				GRM TRUCKING,NCR|
				GUILLEN,NCR|
				H&E TRCUKING,NCR|
				J&J,NCR|
				J.Mendoza Trucking,NCR|
				J.Padilla Trucking,NCR|
				JEFT TRUCKING,NCR|
				JPB Trucking,NCR|
				LEGAS,NCR|
				M.JAMES,NCR|
				M.LAYSON,NCR|
				Menruz Trucking,NCR|
				MKLW,NCR|
				MSK TRUCKING,NCR|
				MUTYA TRUCKING,NCR|
				PGMC Trucking,NCR|
				POWER BENDD,NCR|
				QUILTRANS,NCR|
				R.Mendoza Trucking,NCR|
				RASI Trucking,NCR|
				RECABO,NCR|
				RZF Trucking,NCR|
				SGM Trucking,NCR|
				T.C.Cabande Trucking,NCR|
				TABORA,NCR|
				Taurus Trucking,NCR|
				TNT Trucking,NCR|
				TOMS,NCR|
				Unibee Trucking,NCR|
				UPFC Trucking,NCR|
				Valdez Trucking,NCR|
				WJT TRUCKING,NCR|
				Ariel Macayan,CEBU|
				Aspire,CEBU|
				Avega BrosTrucking,CEBU|
				Cebu North Transport / Edwin Casas,CEBU|
				Domrich Trucking,CEBU|
				FernandezTrucking,CEBU|
				Fulgen Trucking,CEBU|
				Golden 88 Trucking,CEBU|
				Maximo Boyles Trucking,CEBU|
				MM Trucking,CEBU|
				Oriente Cargo Transport Services / Jimmy Mirhan,CEBU|
				Seroje Trucking / Fernando A. Seroje,CEBU|
				Super 8 Trucking / Marcos o. Hao,CEBU|
				UTC Trucking / Edilfonso Uy,CEBU|
				Yahweh Cargo Transport Services /Flordeliza,CEBU|
				Sumalinog,CEBU|
				Dexter Navarro,CEBU|
				ML Rancher,CEBU|
				AP Hardware,CEBU|
				29th of february / Simplicio Pilotos,CEBU|
				Anthony Menchavez,CEBU
							
								 ";
								
				 $exp = explode('|',$data);
                 $ctr =  count($exp);
				 
				 for($i=1;$i<$ctr;$i++){
				 	$rec = explode(',',$exp[$i]);
					//var_dump($rec[0]);exit;
					
					$company = $rec[0];
					$address = $rec[1];					
					
					//echo $rec[0].'|'.$rec[1].'<br>';
					//$address = $rec[1];					
					$sql = "INSERT INTO service_providers(id,company_name,address,stat) VALUES(0,'$company','$address',1)";
					
					$db = $this->db->query($sql);
					var_dump($db);
				 }   
				
				var_dump('oks');exit;

	}
	public function import_beginning_balances_from_file() {
			if(ENVIRONMENT!="development") $this->Abas->redirect(HTTP_PATH);
			if(!isset($_SESSION['abas_login'])) $this->Abas->redirect(HTTP_PATH);
			$created_on	=	date("Y-m-d H:i:s");
			$posted_on	=	date("Y-m-d H:i:s", strtotime("31 December 2016 23:59:59"));
			$filename	=	WPATH."beginning_balances_2017.csv";
			$rowctr		=	0;
			$sql		=	"";
			$continue	=	false;
			if (($handle=fopen($filename, "r")) !== FALSE) {
				$continue	=	true;
				$this->Mmm->debug("File found, proceeding with validation");
				while (($row=fgetcsv($handle, 1000, ",")) !== FALSE) {
					/* $row array is as follows:
					 * [0] => Company
					 * [1] => Company ID
					 * [2] => Memo/Remark/Particular/Description
					 * [3] => Amount
					 * [4] => ABAS FS Code
					 * [5] => ABAS GL Code
					 */
					$num	=	count($row);
					if($rowctr>0 && $continue==true) {
						$account	=	$this->db->query("SELECT * FROM ac_accounts WHERE financial_statement_code=".$row[4]." AND general_ledger_code=".$row[5]."");
						if(!$account) {
							$continue	=	"false";
							$this->Mmm->debug("Account with code ".$row[4]."-".$row[5]." on row ".($rowctr+1)." not found! Import aborted!");
						}
						if(!$account->row()) {
							$continue	=	"false";
							$this->Mmm->debug("Account with code ".$row[4]."-".$row[5]." on row ".($rowctr+1)." not found! Import aborted!");
						}
						if($row[3]==0 || !is_numeric($row[3])) {
							$continue	=	"false";
							$this->Mmm->debug("Amount not set for row ".($rowctr+1)."! Import aborted.");
						}
						$account	=	(array)$account->row();
						$company	=	$this->Abas->getCompany($row[1]);
						if(!$company) {
							$continue	=	"false";
							$this->Mmm->debug("Company not found for row ".($rowctr+1)."! Import aborted.");
						}
						$debit_amount	=	($row[3]>0) ? $row[3] : 0;
						$credit_amount	=	($row[3]<0) ? abs($row[3]) : 0;
						$transactions[$company->id][]	=	array("company_id"=>$company->id, "debit_amount"=>$debit_amount, "credit_amount"=>$credit_amount, "coa_id"=>$account['id'], "remark"=>$row[2], "created_on"=>$created_on, "posted_on"=>$posted_on);
					}
					$rowctr++;
				}
				fclose($handle);
			}
			if($continue==true) {
				$all_successful					=	true;
				$this->Mmm->debug("Validation complete, proceeding with SQL");
				if(!empty($transactions)) {
					foreach($transactions as $transaction_company=>$transaction) {
						$company				=	$this->Abas->getCompany($transaction_company);
						$transaction_sql		=	"INSERT INTO `ac_transactions` (date, remark, stat, company_id, created_on) VALUES ('".$posted_on."', 'Ending Balances for ".$company->name." for 2016', '1', '".$company->id."', '".date("Y-m-d H:i:s")."')";
						//$this->Mmm->debug($transaction_sql);
						$this->db->query($transaction_sql);
						$current_transaction	=	$this->db->query("SELECT MAX(id) AS id FROM ac_transactions");
						$current_transaction	=	$current_transaction->row();
						foreach($transaction as $entryctr=>$entry) {
							$entry_sql			=	"INSERT INTO `ac_transaction_journal` (coa_id, company_id, debit_amount, credit_amount, transaction_id, posted_on, posted_by, stat, created_on, remark) VALUES ('".$entry['coa_id']."', '".$transaction_company."', '".$entry['debit_amount']."', '".$entry['credit_amount']."', '".$current_transaction->id."', '".$entry['posted_on']."', '".$_SESSION['abas_login']['userid']."', '1', '".date("Y-m-d H:i:s")."', '".$this->Mmm->sanitize($entry['remark'])."')";
							$check			=	$this->db->query($entry_sql);
							if(isset($check)) {
								if(!$check) {
									$this->Mmm->debug("SQL Error for query ".$entryctr." - ".$entry_sql);
									$all_successful	=	false;
								}
							}
						}
					}
				}
			}
			if($all_successful) {
				$this->Mmm->debug("All entries successfully added to ABAS!");
			}
			else {
				$this->Mmm->debug("The computer tried its best but something still went wrong");
			}
	}
	public function fix_account_number() {
		if(ENVIRONMENT!="development") { $this->Abas->redirect(HTTP_PATH); }
		$coa	=	$this->db->query("SELECT * FROM ac_accounts");
		$coa	=	$coa->result_array();
		foreach($coa as $account) {
			$fs_code	=	substr($account['code'], 6, 4);
			$gl_code	=	substr($account['code'], 10, 4);
			$sql		=	"UPDATE ac_accounts SET financial_statement_code=".$fs_code.", general_ledger_code=".$gl_code." WHERE id=".$account['id'];
			$this->Mmm->debug($sql);
			$this->db->query($sql);
		}
	}
	public function clean_supplier_tin() {
		if(ENVIRONMENT!="development") {
			$this->Abas->sysMsg("errmsg", "This can only be accessed from the development server!");
			$this->Abas->redirect(HTTP_PATH);
		}
		$suppliers	=	$this->db->query("SELECT * FROM suppliers WHERE tin LIKE '%-%'");
		$suppliers	=	$suppliers->result_array();
		foreach($suppliers as $supplier) {
			$tin		=	str_replace("-", "", $this->Mmm->sanitize($supplier['tin']));
			$tin		=	str_replace(" ", "", $this->Mmm->sanitize($tin));
			$tin		=	str_replace("/", "", $this->Mmm->sanitize($tin));
			$update		=	"UPDATE suppliers SET tin=".$tin." WHERE id=".$supplier['id'];
			$this->db->query($update);
		}
	}
	public function randomize_supplier_required_data() {
		if(ENVIRONMENT!="development") {
			$this->Abas->sysMsg("errmsg", "This can only be accessed from the development server!");
			$this->Abas->redirect(HTTP_PATH);
		}
		$suppliers	=	$this->db->query("SELECT id FROM suppliers");
		if($suppliers) {
			if($suppliers=$suppliers->result_array()) {
				$checkAllQueries			=	true;
				foreach($suppliers as $supplier) {
					$vat_choices			=	array("non-vat","inclusive","exclusive");
					$taxation_choices		=	array("1","2","5","10");
					$vat_computation		=	$vat_choices[rand(0,2)];
					$taxation_percentile	=	$taxation_choices[rand(0,3)];
					$updateSQL				=	"UPDATE suppliers SET vat_computation='".$vat_computation."' AND taxation_percentile='".$taxation_percentile."' AND issues_reciepts='".rand(0,1)."' AND payment_terms='".rand(0,30)."' WHERE id=".$supplier['id'].";";
					$this->Mmm->debug($updateSQL);
					$check					=	$this->db->query($updateSQL);
					if($check==false) {
						$checkAllQueries	=	false;
					}
				}
				if($checkAllQueries) { $this->Abas->sysMsg("errmsg", "Not all suppliers were updated!"); }
				else { $this->Abas->sysMsg("sucmsg", "All suppliers were updated!"); }
			} else { $this->Abas->sysMsg("errmsg", "Suppliers not found!"); }
		} else { $this->Abas->sysMsg("errmsg", "Suppliers not found!"); }
	}
	public function randomize_salaries() { // randomly sets salary grade and tax code for all employees
		if(ENVIRONMENT == "development") { // Only works on dev to avoid erasing the prod db data (!!)
			$salgrades	=	$this->db->query("SELECT id FROM salary_grades");
			$salgrades	=	$salgrades->result();
			$taxcodes	=	$this->db->query("SELECT * FROM tax_codes");
			$taxcodes	=	$taxcodes->result();
			// echo "<pre>";print_r($taxcodes);echo "</pre>";
			$all_employees	=	$this->Hr_model->getAllEmployees();
			foreach($all_employees['rows'] as $ae) {
				// $testvalues					=	array(47,22,34);
				// $random_salgrade_index		=	floor(rand(0,(count($testvalues)-1)));
				$random_salgrade_index		=	floor(rand(0,(count($salgrades)-1)));
				$random_taxcode_index		=	floor(rand(0,(count($taxcodes)-1)));
				$update['tax_code']			=	$taxcodes[$random_taxcode_index]->tax_code;
				$update['salary_grade']		=	$salgrades[$random_salgrade_index]->id;
				// $update['salary_grade']		=	$salgrades[$random_salgrade_index];
				// $random						=	rand(0,6); // russian roulette
				// if($random == rand(0,6)) {
					$this->Mmm->dbUpdate("hr_employees", $update, $ae['id']);
				// }
			}
			$this->Abas->sysMsg("sucmsg", "Payroll data randomized!");
		}
		else {
			$this->Abas->sysMsg("msg", "Temp is only accessible in development mode!");
		}
		header("location:".HTTP_PATH);
	}

	public function payroll_reversal() {
		if(ENVIRONMENT != "development") { $this->Abas->redirect(HTTP_PATH); }
		$payroll_id	=	0;
		$requestor	=	"";
		$reason		=	"";
		$continue	=	false;

		if($continue	==	true) {
			$action_msg	=	"Delete payroll request of (".$requestor.") due to (".$reason.")";
			$payroll	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$payroll_id);
			$payroll	=	(array)$payroll->row();
			if(empty($payroll)) {
				die("Payroll not found!");
			}
			$this->Mmm->query("UPDATE hr_undertime SET computed=0 WHERE computed_on LIKE '".$payroll['created_on']."'", $action_msg);
			$this->Mmm->query("UPDATE hr_overtime SET computed=0 WHERE computed_on LIKE '".$payroll['created_on']."'", $action_msg);
			$this->Mmm->query("DELETE FROM hr_loan_payments WHERE payroll_id=".$payroll_id, $action_msg);
			$this->Mmm->query("DELETE FROM hr_payroll WHERE id=".$payroll_id, $action_msg);
			$this->Mmm->query("DELETE FROM hr_payroll_details WHERE payroll_id=".$payroll_id, $action_msg);
		}
		else {
			die("Continue variable not set. Aborting reversal!");
		}
	}
	public function convert_po_etax_to_percentage() {
		// if(ENVIRONMENT!="development") {
			// $this->Abas->sysMsg("errmsg", "This can only be accessed from the development server!");
			// $this->Abas->redirect(HTTP_PATH);
		// }
		$pos	=	$this->db->query("SELECT * FROM inventory_po");
		$pos	=	$pos->result_array();
		foreach($pos as $poctr=>$po) {
			$details=	$this->db->query("SELECT * FROM inventory_po_details WHERE po_id=".$po['id']);
			$details=	$details->result_array();
			$total	=	0;
			foreach($details as $dctr=>$detail) {
				$total	=	$total+($detail['unit_price'] * $detail['quantity']);
			}
			if($total!=$po['amount']) {
				echo "<pre>".$po['id']." amount not equal to computed details"."</pre>";
			}
			else {
				$percentage	=	(($po['extended_tax']/$total)*100);
				if($percentage > 0 && $percentage < 1) {
					$percentage	=	1;
				}
				// $this->db->query("UPDATE inventory_po SET extended_tax=".$percentage." WHERE id=".$detail['id']);
				echo "<pre>".$po['extended_tax']."</br>".$percentage."</pre>";
			}
		}
	}
	public function clean_request_detail_status() {
		if(ENVIRONMENT!="development") {
			//*
			$this->Abas->sysMsg("errmsg", "This may only be run on development servers!");
			$this->Abas->redirect(HTTP_PATH);
			//*/
		}
		$requests	=	$this->db->query("SELECT id FROM inventory_requests");
		$requests	=	$requests->result_array();
		foreach($requests as $request) {
			$request=	$this->Purchasing_model->getRequest($request['id']);
			if(strtolower($request['status'])=='for canvassing') {
				echo "<pre>";
				echo "Request".$request['id'];
				echo "<br/>RequestStatus: ".$request['status'];
				$details=	$this->db->query("SELECT * FROM inventory_request_details WHERE request_id=".$request['id']." AND supplier_id=0");
				$details=	$details->result_array();
				foreach($details as $detail) {
					$this->Mmm->debug($detail);
					$itemUnitPrice	=	0;
					$hasApproved=	false;
					$canvasses	=	$this->db->query("SELECT * FROM inventory_request_details WHERE request_id=".$request['id']." AND supplier_id<>0 AND item_id=".$detail['item_id']);
					$canvasses	=	$canvasses->result_array();
					$status		=	$this->Purchasing_model->getLowestRequestStatus($canvasses);
					echo "<br/>&rarr;&rarr;Detail: ".$detail['id']." : ".$detail['status'];
					echo "<br/>&rarr;&rarr;ProjectedDetailStatus: ".$status;
					if(strtolower($detail['status'])!='cancelled') {
						if(!empty($canvasses)) {
							foreach($canvasses as $canvass) {
								echo "<br/>&rarr;&rarr;&rarr;&rarr;Canvass: ".$canvass['id']." : ".$canvass['status'];
								if(strtolower($canvass['status'])=='for delivery') {
									$hasApproved=true;
									$itemUnitPrice	=	$canvass['unit_price'];
								}
							}
							if(strtolower($detail['status'])=="for canvassing" && $itemUnitPrice!=0) {
								if($hasApproved) {
									$updatesql	=	"UPDATE inventory_request_details SET status='for delivery' WHERE id=".$detail['id'];
								}
								else {
									if(strtolower($canvass['status'])!='cancelled') {
										$updatesql	=	"UPDATE inventory_request_details SET status='".$canvass['status']."' WHERE id=".$detail['id'];
									}
								}
								echo "<br/>".$updatesql;
								// $this->db->query($updatesql);
							}
						}
						else {
							if(strtolower($detail['status'])!="for canvassing") {
								$updatesql	=	"UPDATE inventory_request_details SET status='for canvassing' WHERE id=".$detail['id'];
								echo "<br/>".$updatesql;
								// $this->db->query($updatesql);
							}
						}
					}
					unset($detail);
				}
				echo "</pre>";
			}
		}
	}
	public function clean_non_integrated_withholding_tax_purchase_orders() {
		if(ENVIRONMENT!="development") {
			//*
			$this->Abas->sysMsg("errmsg", "This may only be run on development servers!");
			$this->Abas->redirect(HTTP_PATH);
			//*/
		}
		$pos	=	$this->db->query("SELECT * FROM inventory_po WHERE extended_tax='1' AND company_id<>1");
		$pos	=	$pos->result_array();
		$ctr	=	0;
		foreach($pos as $ctr=>$po) {
			echo $sql	=	"UPDATE inventory_po SET extended_tax='0' WHERE id=".$po['id'];
			// $this->db->query($sql);
		}
		echo "Affected ".$ctr." rows!";
	}
	public function copy_tables() {
		if(ENVIRONMENT!="development") $this->Abas->redirect(HTTP_PATH);
		$origin_database		=	"avegabro_abas";
		$destination_database	=	"avegabro_abas_staging";
		$database_tables=	$this->db->query("SHOW TABLES");
		$database_tables=	$database_tables->result_array(); // to be used for full database copying
		// $accounting_tables	=	array("ac_account_xref", "ac_accounts", "ac_ap_vouchers", "ac_bank_accounts", "ac_bank_memos", "ac_banks", "ac_ca_liquidation", "ac_cash_advances", "ac_cash_fund", "ac_expense_classifications", "ac_journal_vouchers", "ac_request_payment", "ac_transaction_journal", "ac_transaction_types", "ac_transactions", "ac_vat_input", "ac_voucher_approvals", "ac_vouchers");
		// $inventory_tables	=	array("inventory_audit", "inventory_audit_details", "inventory_category", "inventory_deliveries", "inventory_delivery_details", "inventory_issuance", "inventory_issuance_details", "inventory_items", "inventory_location", "inventory_location_name", "inventory_po", "inventory_po_details", "inventory_price_history", "inventory_request_details", "inventory_requests", "inventory_transfer", "inventory_transfer_details", "inventory_unit", );
		foreach($database_tables as $table) {
			if($table['Tables_in_abas']!="users" && $table['Tables_in_abas']!="users_permissions") { // omit users and user_permissions to allow existing accounts to login
				echo "
					drop table ".$destination_database.".".$table['Tables_in_abas'].";<br/>
					CREATE TABLE ".$destination_database.".".$table['Tables_in_abas']." SELECT * FROM ".$origin_database.".".$table['Tables_in_abas'].";<br/>
					ALTER TABLE ".$destination_database.".".$table['Tables_in_abas']." ADD PRIMARY KEY (id);<br/>
					ALTER TABLE ".$destination_database.".".$table['Tables_in_abas']." MODIFY COLUMN id INT auto_increment;<br/>
					<br/>
				";
			}
		}
	}
	public function correct_journal_voucher_references() {
		$year =  date("Y");
		$vouchers	=	$this->db->query("SELECT * FROM ac_journal_vouchers WHERE YEAR(created_on)=".$year);
		$vouchers	=	$vouchers->result_array();
		$ctr_success = 0;
		$ctr_failed =0;
		foreach($vouchers as $voucher) {
			$entries=	json_decode($voucher['journal_ids']);
			foreach($entries as $entry) {
				$update = $this->db->query("UPDATE ac_transaction_journal SET reference_table='ac_journal_vouchers', reference_id=".$voucher['id']." WHERE id=".$entry);
				if($update){
					echo "Succesfully updated reference of journal voucher detail with id: ".$entry."<br>";
					$ctr_success++;
				}else{
					echo "Failed to update journal voucher detail with id: ".$entry."<br>";
					$ctr_failed++;
				}
			}
		}
		echo "Success:".$ctr_success."<br>";
		echo "Failed:".$ctr_failed."<br>";
	}
	public function hide_apprentices() {
		if(ENVIRONMENT!="development") $this->Abas->redirect(HTTP_PATH);
		$apprentice_positions	=	$this->db->query("SELECT * FROM positions WHERE name LIKE '%apprentice%'");
		$apprentice_positions	=	$apprentice_positions->result_array();
		if(!empty($apprentice_positions)) {
			foreach($apprentice_positions as $apprentice_position) {
				$employees			=	$this->db->query("SELECT * FROM hr_employees WHERE position=".$apprentice_position['id']);
				$employees			=	$employees->result_array();
				if(!empty($employees)) {
					foreach($employees as $employee) {
						$this->db->query("UPDATE hr_employees SET stat=0 WHERE id=".$employee['id']);
						$e=$this->Abas->getEmployee($employee['id']);
						$this->Mmm->debug($e);
					}
				}
			}
		}
	}
	public function update_posted_on_field_for_journal_vouchers() {
		if(ENVIRONMENT!="development") $this->Abas->redirect(HTTP_PATH);
		$journal_vouchers		=	$this->db->query("SELECT * FROM ac_journal_vouchers");
		$journal_vouchers		=	$journal_vouchers->result_array();
		if(!empty($journal_vouchers)) {
			foreach($journal_vouchers as $journal_voucher) {
				if($journal_voucher['posted_on']=='') {
					$this->db->query("UPDATE ac_journal_vouchers SET posted_on='".$journal_voucher['created_on']."' WHERE id=".$journal_voucher['id']);
					$this->Mmm->debug($journal_voucher);
				}
			}
		}
	}
	public function correct_statement_of_account_entry_dates($year=NULL) {
		//Note: add only a parameter year if the SOA was created from previous year but was posted the next year
		$this->load->model("Accounting_model");
		if($year==NULL){
			$year =  date("Y");
		}
		$entries	=	$this->db->query("SELECT id FROM ac_transaction_journal WHERE reference_table='statement_of_accounts' AND YEAR(created_on)=".$year);
		$entries	=	$entries->result_array();
		foreach($entries as $entry) {
			$entry	=	$this->Accounting_model->getJournalEntry($entry['id']);
			$soa	=	$this->db->query("SELECT * FROM statement_of_accounts WHERE id=".$entry['reference_id']);
			$soa	=	(array)$soa->row();
			$sql	=	"UPDATE ac_transaction_journal SET posted_on='".$soa['created_on']."' WHERE id=".$entry['id'];
			 $query	=	$this->db->query($sql);
			if($query) {
				echo "Entry ID".$entry['id']." with reference ID".$entry['reference_id']." was updated the posted on date to ".$soa['created_on']."<br>";
				$ctr++;
			}
		}
		echo "<br>Updated Total SOA Entries: ".$ctr;
	}
	public function correct_payments_entry_dates($year=NULL){
		//Note: add only a parameter year if the payment was created from previous year but was posted the next year
		$this->load->model("Accounting_model");
		if($year==NULL){
			$year =  date("Y");
		}
		$entries	=	$this->db->query("SELECT id FROM ac_transaction_journal WHERE reference_table='payments' AND stat=1 AND YEAR(created_on)=".$year);
		$entries	=	$entries->result_array();
		$ctr = 0;
		foreach($entries as $row) {
			$entry	=	$this->Accounting_model->getJournalEntry($row['id']);
			$payment	=	$this->db->query("SELECT * FROM payments WHERE id=".$entry['reference_id']);
			$payment	=	(array)$payment->row();
			$sql	=	"UPDATE ac_transaction_journal SET posted_on='".$payment['received_on']."' WHERE id=".$entry['id'];			
			$query	=	$this->db->query($sql);
			if($query) {
				echo "Entry ID".$entry['id']." with reference ID".$entry['reference_id']." was updated the posted on date to ".$payment['received_on']."<br>";
				$ctr++;
			}
		} 
		echo "<br>Updated Total Payment Entries: ".$ctr;
	}
	public function correct_issuance_entry_dates($year=NULL){
		//Note: add only a parameter year if the issuance was created from previous year but was posted the next year
		$this->load->model("Accounting_model");
		if($year==NULL){
			$year =  date("Y");
		}
		$entries	=	$this->db->query("SELECT id FROM ac_transaction_journal WHERE reference_table='inventory_issuance' AND stat=1 AND YEAR(created_on)=".$year);
		$entries	=	$entries->result_array();
		$ctr=0;
		foreach($entries as $rows) {
			$entry	=	$this->Accounting_model->getJournalEntry($rows['id']);
			$issuance	=	$this->db->query("SELECT * FROM inventory_issuance WHERE id=".$entry['reference_id']);
			$issuance	=	(array)$issuance->row();
			$sql	=	"UPDATE ac_transaction_journal SET posted_on='".$issuance['issue_date']."' WHERE id=".$entry['id'];
			$query	=	$this->db->query($sql);
			if($query){
				echo "Entry ID".$entry['id']." with reference ID".$entry['reference_id']." was updated the posted on date to ".$issuance['issue_date']."<br>";
				$ctr++;
			}
		} 
		echo "<br>Updated Total Issuance Entries: ".$ctr;
	}
	public function correct_payroll_entry_dates($year=NULL){
		//Note: add only a parameter year if the payroll was created from previous year but was posted the next year
		$this->load->model("Accounting_model");
		if($year==NULL){
			$year =  date("Y");
		}
		$entries	=	$this->db->query("SELECT id FROM ac_transaction_journal WHERE reference_table='hr_payroll' AND stat=1 AND YEAR(created_on)=".$year);
		$entries	=	$entries->result_array();
		$ctr=0;
		foreach($entries as $rows) {
			$entry	=	$this->Accounting_model->getJournalEntry($rows['id']);
			$payroll	=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$entry['reference_id']);
			$payroll	=	(array)$payroll->row();
			$sql	=	"UPDATE ac_transaction_journal SET posted_on='".$payroll['created_on']."' WHERE id=".$entry['id'];
			$query	=	$this->db->query($sql);
			if($query){
				echo "Entry ID".$entry['id']." with reference ID".$entry['reference_id']." was updated the posted on date to ".$payroll['created_on']."<br>";
				$ctr++;
			}
		} 
		echo "<br>Updated Total Payroll Entries: ".$ctr;
	}
	public function accounts_receivable_clearing(){
		$sql0 = "SELECT * FROM statement_of_accounts WHERE exists(SELECT ac_transactions.id FROM ac_transactions WHERE ((ac_transactions.reference_table = 'statement_of_accounts') AND (statement_of_accounts.id = ac_transactions.reference_id)));";
		$query0 = $this->db->query($sql0);
		if($query0){
			$result0 = $query0->result();
			$ctr0=0;
			foreach($result0 as $row0){
				$sql3 = "UPDATE statement_of_accounts SET is_cleared=1 WHERE id=".$row0->id;
				$query3 = $this->db->query($sql3);
				if($query3){
					$ctr0++;
				}
			}
			echo "Succesfully cleared SOA records: ".$ctr0;
		}
	}
	public function accounts_collection_clearing(){
		$sql = "SELECT * FROM payments WHERE (exists(SELECT ac_transaction_journal.id FROM ac_transaction_journal WHERE ((ac_transaction_journal.reference_table = 'payments') AND (payments.id = ac_transaction_journal.reference_id))));";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			$ctr=0;
			foreach($result as $row){
				$sql2 = "UPDATE payments SET is_cleared=1 WHERE id=".$row->id;
				$query2 = $this->db->query($sql2);
				if($query2){
					$ctr++;
				}
			}
			echo "Succesfully cleared payment records: ".$ctr;
		}

	}
	public function uploadInventoryCount(){
		require_once WPATH.'assets/phpexcel/Classes/PHPExcel/IOFactory.php';
		$inputFileName=	WPATH.'assets/uploads/inventory/load_inventory_count_05_30_2018.xlsx';
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
		$sheet=$objPHPExcel->getSheet(0);	// select sheet 'inventory_items'
		$headers=$objPHPExcel->getActiveSheet()->toArray("A2");
		$highestRow=$sheet->getHighestRow();
		$highestColumn=$sheet->getHighestColumn();

		$multiInsert	=	array();
		$ctr=0;
		$start_row=2;
		$rowData=$sheet->rangeToArray("A" . $start_row . ":" . $highestColumn . $highestRow ,null,true,false);

		$item_id = ($this->Abas->getLastIDByTable('inventory_items'))+1;

			foreach($rowData as $ctr=>$val) {
				$multiInsert[$ctr]['id']								=	$item_id;
				$multiInsert[$ctr]['item_code']							=	$this->Mmm->sanitize($rowData[$ctr][1]);
				$multiInsert[$ctr]['asset_code']						=	null;
				$multiInsert[$ctr]['description']						=	$this->Mmm->sanitize($rowData[$ctr][2]);
				$multiInsert[$ctr]['particular']						=	$this->Mmm->sanitize($rowData[$ctr][3]);
				$multiInsert[$ctr]['unit']								=	$this->Mmm->sanitize($rowData[$ctr][4]);
				$multiInsert[$ctr]['unit_price']						=	$this->Mmm->sanitize($rowData[$ctr][5]);
				$multiInsert[$ctr]['reorder_level']						=	$this->Mmm->sanitize($rowData[$ctr][6]);
				$multiInsert[$ctr]['discontinued']						=	null;
				$multiInsert[$ctr]['sub_category']						=	$this->Mmm->sanitize($rowData[$ctr][8]);
				$multiInsert[$ctr]['stat']								=	$this->Mmm->sanitize($rowData[$ctr][9]);
				$multiInsert[$ctr]['qty']								=	$this->Mmm->sanitize($rowData[$ctr][10]);
				$multiInsert[$ctr]['category']							=	$this->Mmm->sanitize($rowData[$ctr][11]);
				$multiInsert[$ctr]['location']							=	$this->Mmm->sanitize($rowData[$ctr][12]);
				$multiInsert[$ctr]['stock_location']						=	$this->Mmm->sanitize($rowData[$ctr][13]);
				$multiInsert[$ctr]['account_type']						=	$this->Mmm->sanitize($rowData[$ctr][14]);
				$multiInsert[$ctr]['requested']							=	$this->Mmm->sanitize($rowData[$ctr][15]);
				$multiInsert[$ctr]['created_on']						=	gmdate("Y-m-d", ($rowData[$ctr][16]	- 25569) * 86400);
				$multiInsert[$ctr]['created_by']						=	$this->Mmm->sanitize($rowData[$ctr][17]);
				
				$qty = $this->Mmm->sanitize($rowData[$ctr][10]);
				$this->Mmm->query('INSERT INTO inventory_location (item_id,tayud_qty,nra_qty,mkt_qty,tac_qty,direct_qty) VALUES ('.$item_id.','.$qty.',0,0,0,0)','Added qty for tayud location');
				$ctr++;
				$item_id++;
			}
			$uploaded = $this->Mmm->multiInsert("inventory_items",$multiInsert,"Imported Inventory items from May 30,2018 Audit Count.");
			if($uploaded){
				echo "Successfully uploaded: ".$ctr." records";
			}else{
				echo "Not Successful.";
			}
	}
	public function uploadSalaryGrade(){
		require_once WPATH.'assets/phpexcel/Classes/PHPExcel/IOFactory.php';
		$inputFileName=	WPATH.'assets/uploads/hr/sg_update_2018_SEP_11.xlsx';
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
		$sheet=$objPHPExcel->getSheet(0);	// select sheet 'inventory_items'
		$headers=$objPHPExcel->getActiveSheet()->toArray("A2");
		$highestRow=$sheet->getHighestRow();
		$highestColumn=$sheet->getHighestColumn();

		
	}

		public function generateOutTurnAuditReport(){

			/*$sql = "SELECT ops_out_turn_summary.id as out_turn_id, ops_out_turn_summary.service_order_id, service_orders.service_contract_id, ops_out_turn_summary.created_on AS out_turn_prep_date, ops_out_turn_summary_details.unloading_ended as out_turn_unloading_date FROM ops_out_turn_summary INNER JOIN ops_out_turn_summary_details ON ops_out_turn_summary.id = ops_out_turn_summary_details.out_turn_summary_id INNER JOIN service_orders ON service_orders.id = ops_out_turn_summary.service_order_id WHERE ops_out_turn_summary.type_of_service='Shipping' AND ops_out_turn_summary.status='Approved'";

			$query = $this->db->query($sql);

			if($query){
				$result = $query->result();

				echo "<table border='1'>";
				echo "<tr>";
					echo "<td>#</td>";
					echo "<td>Out Turn ID</td>";
					echo "<td>Service Order ID</td>";
					echo "<td>Contract ID</td>";
					echo "<td>Out Turn Prep Date</td>";
					echo "<td>Out Turn Unloading Date</td>";
				echo "</tr>";
				$ctr=1;
				foreach($result as $row){
					echo "<tr>";
						echo "<td>".$ctr."</td>";
						echo "<td>".$row->out_turn_id."</td>";
						echo "<td>".$row->service_order_id."</td>";
						echo "<td>".$row->service_contract_id."</td>";
						echo "<td>".$row->out_turn_prep_date."</td>";
						echo "<td>".$row->out_turn_unloading_date."</td>";
					echo "</tr>";
					$ctr++;
				}
				echo "</table>";

			}*/

			$sql = "SELECT * FROM ops_out_turn_summary WHERE status='Approved'";
			$query = $this->db->query($sql);
			if($query){

				echo "<table border='1'>";
				echo "<tr>";
					echo "<td>#</td>";
					echo "<td>Company</td>";
					echo "<td>Out Turn ID</td>";
					echo "<td>Service Order ID</td>";
					echo "<td>Contract ID</td>";
					echo "<td>Contract Ref. No.</td>";
					echo "<td>Type of Service</td>";
					echo "<td>Out Turn Unloading Date</td>";
					echo "<td>Out Turn Prep Date</td>";
					echo "<td>Out Turn Status</td>";
				echo "</tr>";


				$result = $query->result();
				$ctr = 1;
				foreach($result as $row){
					$sql2 = "SELECT * FROM service_orders WHERE id=".$row->service_order_id;
					$query2 = $this->db->query($sql2);
					$result2 = $query2->row();

					$sql3 = "SELECT * FROM service_contracts WHERE id=".$result2->service_contract_id;
					$query3 = $this->db->query($sql3);
					$result3 = $query3->row();

					$sql4 = "SELECT * FROM ops_out_turn_summary_details WHERE out_turn_summary_id=".$row->id;
					$query4 = $this->db->query($sql4);
					$result4 = $query4->row();

					$company = $this->Abas->getCompany($row->company_id);

					echo "<tr>";
						echo "<td>".$ctr."</td>";
						echo "<td>".$company->name."</td>";
						echo "<td>".$row->id."</td>";
						echo "<td>".$result2->id."</td>";
						echo "<td>".$result3->id."</td>";
						echo "<td>".$result3->reference_no."</td>";
						echo "<td>".$row->type_of_service."</td>";

						if($row->type_of_service=='Trucking' || $row->type_of_service=='Handling'){
							$sqlx 	=	"SELECT MAX(delivery_date) as max_date FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$row->id;
							$queryx = $this->db->query($sqlx);
							$resultx = $queryx->row();

							echo "<td>".$resultx->max_date."</td>";

						}else{
							echo "<td>".$result4->unloading_ended."</td>";
						}

						echo "<td>".$row->created_on."</td>";
						echo "<td>".$row->status."</td>";
					echo "</tr>";
					$ctr++;

				}

				

			}
		}

		public function generateSOAAuditReport(){

			/*$sql = "SELECT statement_of_accounts.id as soa_id,statement_of_accounts.created_on as soa_prep_date, statement_of_accounts.sent_to_client_on, statement_of_accounts.reference_number, statement_of_accounts.contract_id as soa_contract_id,statement_of_accounts.terms, payments.received_on, payments.net_amount FROM statement_of_accounts INNER JOIN payments ON statement_of_accounts.id=payments.soa_id  WHERE statement_of_accounts.services LIKE '%Shipping%'";

			$query = $this->db->query($sql);

			if($query){
				$result = $query->result();

				echo "<table border='1'>";
				echo "<tr>";
					echo "<td>#</td>";
					echo "<td>SOA ID</td>";
					echo "<td>SOA Contact ID</td>";
					echo "<td>SOA Ref No.</td>";
					echo "<td>SOA Prep Date</td>";
					echo "<td>SOA Sent To Client</td>";
					echo "<td>SOA Terms</td>";
					echo "<td>Payment Received Date</td>";
					echo "<td>Amount Collected</td>";
				echo "</tr>";
				$ctr=1;
				foreach($result as $row){
					echo "<tr>";
						echo "<td>".$ctr."</td>";
						echo "<td>".$row->soa_id."</td>";
						echo "<td>".$row->soa_contract_id."</td>";
						echo "<td>".$row->reference_number."</td>";
						echo "<td>".$row->soa_prep_date."</td>";
						echo "<td>".$row->sent_to_client_on."</td>";
						echo "<td>".$row->terms."</td>";
						echo "<td>".$row->received_on."</td>";
						echo "<td>".$row->net_amount."</td>";
					echo "</tr>";
					$ctr++;
				}
				echo "</table>";

			}*/

				$sql5 = "SELECT * FROM statement_of_accounts WHERE (status='Approved' OR status='Waiting for Payment' OR status='Paid')";
				$query5 = $this->db->query($sql5);
				
				echo "<table border='1'>";
				echo "<tr>";
					echo "<td>#</td>";
					echo "<td>Company</td>";
					echo "<td>SOA ID</td>";
					echo "<td>SOA Contact ID</td>";
					echo "<td>SOA Ref No.</td>";
					echo "<td>SOA Prep Date</td>";
					echo "<td>SOA Sent To Client</td>";
					echo "<td>SOA Terms</td>";
					echo "<td>SOA Status</td>";
					echo "<td>SOA Amount</td>";
					echo "<td>Payment Received Date</td>";
					echo "<td>Amount Collected</td>";
				echo "</tr>";

				if($query5){
					$result5 = $query5->result();
					$ctr = 1;
					foreach($result5 as $row){

						$sql6 = "SELECT * FROM payments WHERE soa_id=".$row->id;
						$query6 = $this->db->query($sql6);
						$result6 = $query6->row();

						$company = $this->Abas->getCompany($row->company_id);

						$soa_amount = $this->Billing_model->getSOAAmount($row->type,$row->id);

						echo "<tr>";
						echo "<td>".$ctr."</td>";
						echo "<td>".$company->name."</td>";
						echo "<td>".$row->id."</td>";
						echo "<td>".$row->contract_id."</td>";
						echo "<td>".$row->reference_number."</td>";
						echo "<td>".$row->created_on."</td>";
						echo "<td>".$row->sent_to_client_on."</td>";
						echo "<td>".$row->terms."</td>";
						echo "<td>".$row->status."</td>";
						echo "<td>".$soa_amount['grandtotal_tax']."</td>";
						echo "<td>".$result6->received_on."</td>";
						echo "<td>".$result6->net_amount."</td>";
						echo "</tr>";
						$ctr++;
					}
				}
		}
		
		public function updateEmployementStatus($old_status,$new_status){
			$sql = "SELECT * FROM hr_employees WHERE employee_status='".$old_status."'";
			$query = $this->db->query($sql);
			if($query){
				$result = $query->result();
				$ctr=0;
				foreach($result as $row){
					$now = date("Y-m-d");
					$sql2 = "UPDATE hr_employees SET employee_status='".$new_status."' WHERE id=".$row->id;
					$query2 = $this->db->query($sql2);
					if($query2){
						$sql3 = "INSERT INTO hr_employment_history (employee_id,effectivity_date,value_changed,from_val,to_val,from_date,to_date,stat,added_on,added_by) VALUES (".$row->id.",'".$now."','Employee Status','".$old_status."','".$new_status."','0000-00-00','0000-00-00',1,'".$now."',80)";
						$query3 = $this->db->query($sql3);
						if($query3){
							echo "Updated status of ".$row->last_name. ", ".$row->first_name." ".$row->middle_name. " from ".$old_status. " to ". $new_status.".<br>";
						}
					}
					$ctr++;
				}
				echo "Total records updated: ".$ctr;
			}

		}

	 public function setControlNumbers($table){
	 	$sql = "SELECT * FROM companies WHERE stat=1";
	 	$query = $this->db->query($sql);
	 	if($query){
			$result = $query->result();
				foreach($result as $row){
					$sql2 =  "SELECT * FROM ".$table." WHERE company_id=".$row->id;
					$query2 = $this->db->query($sql2);
					if($query2){
						$result2 = $query2->result();
						$ctr=1;
						foreach($result2 as $row2){
							$sql3 = "UPDATE ".$table." SET control_number='".$ctr."' WHERE id=".$row2->id;
							$query3 = $this->db->query($sql3);
							$ctr++;	
						}
					}
					
				}
		}
	 }

	 public function getDisbalanceEntries($date_start,$date_end){
	 	echo "<h1>List of Disbalance Transactions</h1>";
	 	$sql = "SELECT id, transaction_id, sum(debit_amount) as debit, sum(credit_amount) as credit , sum(debit_amount-credit_amount) as difference, stat FROM `ac_transaction_journal` WHERE posted_on>='".$date_start."' AND posted_on<='".$date_end."' GROUP BY transaction_id ORDER BY difference DESC";
	 	$query = $this->db->query($sql);
	 	if($query){
	 		$transactions = $query->result();
	 		echo "<table border='1'>";
	 		echo "<tr>
	 				<th>#</th>
	 				<th>Transaction ID</th>
	 				<th>Debit</th>
	 				<th>Credit</th>
	 				<th>Difference</th>
	 				<th>Stat</th>
	 			 </tr>";
	 			 $ctr=1;
	 		foreach($transactions as $detail){
				if($detail->difference!=0){
		 			echo "<tr>";
		 			echo "<td>".$ctr."</td>";
		 			echo "<td>".$detail->transaction_id."</td>";
		 			echo "<td>".number_format($detail->debit,2,".",",")."</td>";
		 			echo "<td>".number_format($detail->credit,2,".",",")."</td>";
		 			echo "<td>".number_format($detail->difference,2,".",",")."</td>";
		 			echo "<td>".$detail->stat."</td>";
		 			echo "</tr>";
		 			$ctr++;
	 			}
	 		}
	 		echo "</table>";
	 	}
	 }

	 public function generateRequisitionAuditReport($date_from,$date_to){
	 	$result = $this->Purchasing_model->getRequests(" AND tdate BETWEEN '".$date_from."' AND '".$date_to."'");
	 	
	 	echo "<table border='1'>";
			 	echo "<thead>";
				 	echo "<th>Transaction Code No.</th>";
				 	echo "<th>Requisition No.</th>";
				 	echo "<th>Company</th>";
				 	echo "<th>Vessel/Office</th>";
				 	echo "<th>Requestioner</th>";
				 	echo "<th>Department</th>";
				 	echo "<th>Requested On</th>";
				 	echo "<th>Approved By</th>";
				 	echo "<th>Approved On</th>";
				 	echo "<th>Created By</th>";
				 	echo "<th>Created On</th>";
				 	echo "<th>Priority</th>";
				 	echo "<th>Status</th>";
		 		echo "</thead>";
			 	echo "<tbody>";
				 	foreach($result as $row){
				 		echo "<tr>";
				 			echo "<td>".$row['id']."</td>";
				 			echo "<td>".$row['control_number']."</td>";
				 			echo "<td>".$row['company']->name."</td>";
				 			echo "<td>".$row['vessel_name']."</td>";
				 			echo "<td>".$row['requisitioner']."</td>";
				 			echo "<td>".$row['department_name']."</td>";
				 			echo "<td>".date('Y-m-d',strtotime($row['tdate']))."</td>";
				 			echo "<td>".$row['approved_by_name']."</td>";
				 			echo "<td>".date('Y-m-d H:m:s',strtotime($row['approved_on']))."</td>";
				 			echo "<td>".$row['requested_by_name']."</td>";
				 			echo "<td>".date('Y-m-d H:m:s',strtotime($row['added_on']))."</td>";
				 			echo "<td>".$row['priority']."</td>";
				 			echo "<td>".$row['status']."</td>";
				 		echo "</tr>";
				 	}
			 	echo "</tbody>";
	 	echo "</table>";
	 }
	 public function generateInventoryAuditSummary($audit_date){
	 	echo "<h2>Inventory Count Date: ".$audit_date."</h2>";
	 	$sql = "SELECT * FROM inventory_audit WHERE audit_date='".$audit_date. "' AND status<>'Cancelled'";
	 	$query = $this->db->query($sql);
	 	if($query){
	 		$result = $query->result();
	 			echo "<table border='1'>";
					echo "<tr>";
						echo "<td>#</td>";
						echo "<td>Item ID</td>";
						echo "<td>Item Code</td>";
						echo "<td>Item Name</td>";
						echo "<td>Particulars</td>";
						echo "<td>Item Category</td>";
						echo "<td>Unit of Measurement</td>";
						echo "<td>Qty Before Count (Per ABAS)</td>";
						echo "<td>Qty Per Count</td>";
						echo "<td>Difference</td>";
					echo "</tr>";
					$ctr = 1;
		 			foreach($result as $row){
		 				$sql2 = "SELECT * FROM inventory_audit_details WHERE audit_id=".$row->id;
		 				$query2 = $this->db->query($sql2);
		 				if($query2){
		 					$result_details = $query2->result();
		 					foreach($result_details as $detail){
		 						echo "<tr>";
		 							echo "<td>".$ctr."</td>";
		 							$item = $this->Inventory_model->getItem($detail->item_id);
		 							echo "<td>".$detail->item_id."</td>";
		 							echo "<td>".$item[0]['item_code']."</td>";
		 							echo "<td>".$item[0]['item_name']."</td>";
		 							echo "<td>".$item[0]['particular']."</td>";
		 							$category = $this->Inventory_model->getCategory($item[0]['category']);
		 							echo "<td>".$category->category."</td>";
		 							echo "<td>".$item[0]['unit']."</td>";
		 							echo "<td>".$detail->current_qty."</td>";
		 							echo "<td>".$detail->counted_qty."</td>";
		 							$difference = $detail->current_qty - $detail->counted_qty;
		 							echo "<td>".number_format($difference,2,'.',',')."</td>";
		 						echo "</tr>";
		 						$ctr++;
		 					} 
		 				}
		 			}
	 		echo "</table>";
	 	}
	 }
	 public function addCheckVoucherIDtoAPV(){
	 	$sql = "SELECT * FROM ac_vouchers;";
	 	$query = $this->db->query($sql);
	 	if($query){
	 		$cvs = $query->result();
	 		$ctr_success =0;
	 		$ctr_failed  =0;
	 		$ctr = 1;
	 		echo "<table border=''1>";
	 		foreach($cvs as $cv){
	 			if($cv->transaction_type=='po'){
		 			echo "<tr>";
			 			$sql2 = "UPDATE ac_ap_vouchers SET check_voucher_id=".$cv->id." WHERE id=".$cv->apv_no;
			 			$query2 = $this->db->query($sql2);
			 			if($query2){
			 				echo "<td>".$ctr."</td>";
			 				echo "<td>Added CV ID ".$cv->id." to APV ID ".$cv->apv_no."</td>";
			 				$ctr_success++;
			 			}else{
			 				echo "<td>".$ctr."</td>";
			 				echo "<td>Failure for CV ID ".$cv->id." with APV ID ".$cv->apv_no."</td>";
			 				$ctr_failed++;
			 			}
		 			echo "</tr>";
	 				$ctr++;
	 			}
	 		}
	 		echo "</table>";
	 		echo "Succesfully changed: ".$ctr_success."<br>";
	 		echo "Failed to change: ".$ctr_failed;
	 	}
	 }
	 public function noStockInOutItems(){
	 	$sql = "SELECT * FROM inventory_items";
	 	$query = $this->db->query($sql);
	 	if($query){
	 		$items = $query->result();
	 		$ctr=1;
	 		echo "<table border='1'>";
	 		echo "<tr>";
	 			echo "<td>#</td>";
	 			echo "<td>Item ID</td>";
	 			echo "<td>Item Name</td>";
	 			echo "<td>Item Particular</td>";
	 			echo "<td>Unit</td>";
	 			echo "<td>Quantity</td>";
	 			echo "<td>Active?</td>";
	 		echo "</tr>";
	 		foreach($items as $item){
	 			$has_rr = true;
	 			$has_iss = true;
	 			$sql2 = "SELECT * FROM inventory_delivery_details WHERE item_id=".$item->id;
	 			$query2 = $this->db->query($sql2);
	 			if($query2){
	 				$rr_item = $query2->result();
	 				if(count($rr_item)==0){
 						$has_rr = false;
	 				}
	 			}

	 			$sql3 = "SELECT * FROM inventory_issuance_details WHERE item_id=".$item->id;
	 			$query3 = $this->db->query($sql3);
	 			if($query3){
	 				$iss_item = $query3->result();
	 				if(count($iss_item)==0){
	 					$has_iss = false;
	 				}
	 			}

	 			if($has_rr == false && $has_iss == false){
	 				$qty = $this->Inventory_model->getItemQty($item->id);
	 				$total_qty = $qty[0]['tayud_qty'] + $qty[0]['mkt_qty'] + $qty[0]['nra_qty'];
	 				if($total_qty<>0){
	 					echo "<tr>";
 							echo "<td>".$ctr."</td>";
 							echo "<td>".$item->id."</td>";
 							echo "<td>".$item->description."</td>";
 							echo "<td>".$item->particular."</td>";
 							echo "<td>".$item->unit."</td>";
 							echo "<td>".$total_qty."</td>";
 							echo "<td>".$item->stat."</td>";
 						echo "</tr>";
	 					$ctr++;
	 				}
	 			}
	 		}
	 		echo "</table>";
	 	}
	 }

	 public function inventoryInOutSummary($start_date,$end_date){

	 	echo "<h2>Stock In-Out of items for the period of ".date('F j, Y',strtotime($start_date))." until ".date('F j, Y',strtotime($end_date))."</h2>";

	 	echo "<table border='1'>";
 		echo "<tr>";
 			echo "<td>#</td>";
 			echo "<td>Item ID</td>";
 			echo "<td>Item Name</td>";
 			echo "<td>Particular</td>";
 			echo "<td>Unit</td>";
 			echo "<td>Quantity Received</td>";
 			echo "<td>Quantity Issued</td>";
 			echo "<td>Difference</td>";
 			echo "<td>RR Reference</td>";
 			echo "<td>MSIS Reference</td>";
 		echo "</tr>";

	 	$sql = "SELECT * FROM (SELECT 'Receiving' AS type, inventory_deliveries.tdate AS trans_date,inventory_deliveries.remark AS remark,idel.delivery_id AS ref_id,idel.item_id AS item_id,idel.unit AS unit,idel.unit_price AS unit_price,idel.quantity AS quantity FROM (inventory_delivery_details idel JOIN inventory_deliveries ON(idel.delivery_id = inventory_deliveries.id)) UNION SELECT 'Issuance' AS type, inventory_issuance.issue_date AS trans_date,inventory_issuance.remark AS remark,iiss.issuance_id AS ref_id,iiss.item_id AS item_id,iiss.unit AS unit,iiss.unit_price AS unit_price,iiss.qty AS quantity FROM (inventory_issuance_details iiss JOIN inventory_issuance on(iiss.issuance_id = inventory_issuance.id))) data WHERE trans_date BETWEEN '".$start_date."' AND '".$end_date."'  GROUP BY item_id ORDER BY item_id, unit ASC";
	 	$query = $this->db->query($sql);

	 	if($query){

	 		$stockinout = $query->result();
	 		$ctr=1;

	 		foreach($stockinout as $row){

	 			$quantity_received = 0;
	 			$quantity_issued = 0;
	 			$rr_ids = array();
	 			$msis_ids = array();
	 			
	 			$sql_deliveries = "SELECT *,inventory_deliveries.id AS ref_id FROM inventory_deliveries INNER JOIN inventory_delivery_details ON inventory_deliveries.id = inventory_delivery_details.delivery_id WHERE inventory_delivery_details.item_id=".$row->item_id." AND  inventory_deliveries.tdate BETWEEN '".$start_date."' AND '".$end_date."' ";
		 		$query_deliveries = $this->db->query($sql_deliveries);
		 		if($query_deliveries){
		 			$deliveries = $query_deliveries->result();
		 			foreach($deliveries as $delivery){
		 				array_push($rr_ids,$delivery->ref_id);
		 				$quantity_received = $quantity_received + $delivery->quantity;
		 			}
		 		}

		 		$sql_issuances = "SELECT *, inventory_issuance.id AS ref_id FROM inventory_issuance INNER JOIN inventory_issuance_details ON inventory_issuance.id = inventory_issuance_details.issuance_id WHERE inventory_issuance_details.item_id=".$row->item_id." AND  inventory_issuance.issue_date BETWEEN '".$start_date."' AND '".$end_date."' ";
	 			$query_issuances = $this->db->query($sql_issuances);
	 			if($query_issuances){
	 				$issuances = $query_issuances->result();
		 			foreach($issuances as $issuance){
		 				array_push($msis_ids,$issuance->ref_id);
		 				$quantity_issued = $quantity_issued + $issuance->qty;
		 			}
	 			}

	 			$item = $this->Inventory_model->getItem($row->item_id);

		 		echo "<tr>";
					echo "<td>".$ctr."</td>";
					echo "<td>".$row->item_id."</td>";
					echo "<td>".$item[0]['item_name']."</td>";
					echo "<td>".$item[0]['particular']."</td>";
					echo "<td>".$item[0]['unit']."</td>";
					echo "<td>".$quantity_received."</td>";
					echo "<td>".$quantity_issued."</td>";
					echo "<td>".($quantity_received - $quantity_issued)."</td>";
					echo "<td>";
						foreach($rr_ids as $rr){
							echo'<a href="'.HTTP_PATH .'/inventory/view_transaction_history_details/delivery/'.$rr.'" class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modalDialog">'.$rr.'</a><br>';
						}
					echo "</td>";
					echo "<td>";
						foreach($msis_ids as $msis){
							echo'<a href="'.HTTP_PATH .'/inventory/view_transaction_history_details/issuance/'.$msis.'" class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modalDialog">'.$msis.'</a><br>';
						}
					echo "</td>";
				echo "</tr>";
				$ctr++;
				
			}
		}	
		echo "</table>";
	}
	public function generateTruckingRepairsAudit($date_from,$date_to){
		$sql	=	"SELECT * FROM am_truck_repairs WHERE created_on BETWEEN '".$date_from."' AND '".$date_to."'";
		$query	=	$this->db->query($sql);
		if($query){
			$TRMRF = $query->result();

			echo "<table border='1'>";
			echo "<thead>";
				echo "<td>Transaction Code No.</td>";
				echo "<td>Control No.</td>";
				echo "<td>Company</td>";
				echo "<td>Plate No.</td>";
				echo "<td>Driver</td>";
				echo "<td>Current Location</td>";
				echo "<td>Priority</td>";
				echo "<td>Created By</td>";
				echo "<td>Created On</td>";
				echo "<td>Status</td>";
				echo "<td>Details<br>(Cause-Correction-Remarks)</td>";
			echo "</thead><tbody>";

			foreach($TRMRF as $row1){
				$details = $this->Asset_Management_model->getTRMRFDetails($row1->id);
				echo "<tr>";
					echo "<td>".$row1->id."</td>";
					echo "<td>".$row1->control_number."</td>";
					$company = $this->Abas->getCompany($row1->company_id);
					$truck = $this->Asset_Management_model->getTruck($row1->truck_id);
					echo "<td>".$company->name."</td>";
					echo "<td>".$truck['plate_number']."</td>";
					echo "<td>".$row1->driver."</td>";
					echo "<td>".$row1->location."</td>";
					echo "<td>".$row1->priority."</td>";
					$created_by = $this->Abas->getUser($row1->created_by);
					echo "<td>".$created_by['full_name']."</td>";
					echo "<td>".$row1->created_on."</td>";
					echo "<td>".$row1->status."</td>";
					echo "<td>";
						foreach($details as $row2){
							echo "<li>".$row2->complaints."-".$row2->cause_corrections."-".$row2->remarks."</li>";
						}
					echo "</td>";
				echo "</tr>";
			}
			
			echo "</tbody></table>";
		}
	}
	public function generateOutturnToBillingAging($from_date, $to_date){

		$this->load->model("Accounting_model");
		
		$table = "<table border='1'>";
		$table .= "<tr>";
			$table .= "<td rowspan='2'>#</td>";
			$table .= "<td rowspan='2'>Out-turn ID</td>";
			$table .= "<td rowspan='2'>Control No.</td>";
			$table .= "<td rowspan='2'>Company</td>";
			$table .= "<td rowspan='2'>Contract Ref. No.</td>";
			$table .= "<td rowspan='2'>Client</td>";
			$table .= "<td rowspan='2'>Service Type</td>";
			$table .= "<td rowspan='2'>Out-Turn Status</td>";
			$table .= "<td rowspan='2'>Unloading/Delivery Date Ended</td>";
			$table .= "<td rowspan='2'>Out-Turn Created On</td>";
			$table .= "<td rowspan='2'>Out-Turn Created By</td>";
			$table .= "<td rowspan='2'>No. of times edited</td>";
			$table .= "<td rowspan='2'>SOA ID</td>";
			$table .= "<td rowspan='2'>SOA Created On</td>";
			$table .= "<td rowspan='2'>SOA Amount</td>";
			$table .= "<td rowspan='2'>SOA Status</td>";
			$table .= "<td rowspan='2'>Date Received By Client</td>";
			$table .= "<td rowspan='2'>GL Posted On</td>";
			$table .= "<td colspan='2'>Aging</td>";
		$table .= "</tr>";
		$table .= "<tr>";
			$table .= "<td>From unload to out-turn</td>";
			$table .= "<td>From out-turn to billing</td>";
		$table .= "</tr>";
		$sql_os = "SELECT * FROM ops_out_turn_summary WHERE status<>'Cancelled' AND created_on BETWEEN '".$from_date."' AND '".$to_date."'";
		$query_os = $this->db->query($sql_os);
		if($query_os){
			$result_os = $query_os->result();
			$ctr = 1;
			foreach($result_os as $os){
				$table .= "<tr>";

					$created_on = $os->created_on;
					$created_by = $this->Abas->getUser($os->created_by);
					$created_by = $created_by['full_name'];
					$company = $this->Abas->getCompany($os->company_id);
					if($os->service_order_id!=0){
						$so = $this->Operation_model->getServiceOrder($os->service_order_id);
						$contract = $so->contract;
					}else{
						$contract =	$this->Abas->getContract($os->service_contract_id);
					}

					if($os->type_of_service=='Trucking' || $os->type_of_service=='Handling'){
						$sql_del = "SELECT * FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$os->id." ORDER BY delivery_date DESC LIMIT 1";
						$query_del = $this->db->query($sql_del);
						if($query_del){
							$result_del = $query_del->row();
							$unloading_ended = $result_del->delivery_date;
						}
					}else{
						$detail = $this->Operation_model->getOutTurnSummaryDetails($os->id);
						if($detail->unloading_departure!=0){
							$unloading_ended = $detail->unloading_departure;
						}else{
							$unloading_ended = $detail->unloading_ended;
						}
					}

					$table .= "<td>".$ctr."</td>";
					$table .= "<td>".$os->id."</td>";
					$table .= "<td>".$os->control_number."</td>";
					$table .= "<td>".$company->name."</td>";
					$table .= "<td>".$contract['reference_no']."</td>";
					$table .= "<td>".$contract['client']['company']."</td>";
					$table .= "<td>".$os->type_of_service."</td>";
					$table .= "<td>".$os->status."</td>";
					$table .= "<td>".date('F j, Y',strtotime($unloading_ended))."</td>";
					$table .= "<td>".date('F j, Y',strtotime($created_on))."</td>";
					$table .= "<td>".$created_by."</td>";
					if($os->times_returned_to_draft>0){
						$edited_count = $os->times_returned_to_draft;
					}else{
						$edited_count = 0;
					}
					$table .= "<td>".$edited_count."</td>";

					$soa = $this->Operation_model->getSOAbyOutturn($os->id);
					if(isset($soa[0]->id)){
						$table .= "<td>".$soa[0]->id."</td>";
						$table .= "<td>".date('F j, Y',strtotime($soa[0]->created_on))."</td>";
						$soa_amount = $this->Billing_model->getSOAAmount($soa[0]->type,$soa[0]->id);
						$soa_amount = $soa_amount['grandtotal_tax'];

						$table .= "<td>".number_format($soa_amount,2,'.',',')."</td>";
						$table .= "<td>".$soa[0]->status."</td>";
						if(isset($soa[0]->sent_to_client_on) && $soa[0]->status<>'Draft'){
							$table .= "<td>".date('F j, Y',strtotime($soa[0]->sent_to_client_on))."</td>";
						}else{
							$table .= "<td>--</td>";
						}

						$entries = $this->Accounting_model->getTransactionJournalEntriesByReference('statement_of_accounts',$soa[0]->id);
						if(isset($entries[0]['posted_on'])){
							$table .= "<td>".date('F j, Y',strtotime($entries[0]['posted_on']))."</td>";
						}else{
							$table .= "<td>--</td>";
						}

					}else{
						$table .= "<td>--</td>";
						$table .= "<td>--</td>";
						$table .= "<td>--</td>";
						$table .= "<td>--</td>";
						$table .= "<td>--</td>";
						$table .= "<td>--</td>";
					}		
				
					$os_date = new DateTime($created_on);
					$unload_date = new DateTime($unloading_ended);
					$difference = $unload_date->diff($os_date);
					$table .= "<td>".$difference->m." months & ".$difference->d." days</td>";

					if(isset($soa[0]->id)){
						$os_date = new DateTime($created_on);
						$soa_date = new DateTime($soa[0]->created_on);
						$differencex = $os_date->diff($soa_date);
						$table .= "<td>".$differencex->m." months & ".$differencex->d." days</td>";
					}else{
						$table .= "<td>--</td>";
					}

				$ctr++;
				$table .= "</tr>";
			}
		}
		$table .= "</table>";

		echo $table;
	}
	public function inventory_master_list(){

		$sql = "SELECT inventory_items.*, inventory_location.tayud_qty as abas_qty,inventory_category.category as category_name FROM inventory_items INNER JOIN inventory_location ON inventory_location.item_id = inventory_items.id INNER JOIN inventory_category ON inventory_category.id = inventory_items.category WHERE inventory_items.stat=1 AND inventory_category.category <>'Service'";

		$query = $this->db->query($sql);
		if($query){
			$items = $query->result();
			$ctr = 1;
			echo "<table border='1'>";
			echo "<tr>";
				echo "<td>#</td>";
				echo "<td>Item ID</td>";
				echo "<td>Item Code</td>";
				echo "<td>Generic Name</td>";
				echo "<td>Particular (Brand | Specs)</td>";
				echo "<td>Category</td>";
				echo "<td>ABAS Quantity</td>";
				echo "<td>Unit</td>";
				echo "<td>Actual Count</td>";
				echo "<td>Variance</td>";
				echo "<td>Company</td>";
				echo "<td>Rack Location</td>";
			echo "</tr>";
			foreach($items as $item){
				echo "<tr>";
					echo "<td>".$ctr."</td>";
					echo "<td>".$item->id."</td>";
					echo "<td>".$item->item_code."</td>";
					echo "<td>".$item->description."</td>";
					echo "<td>".$item->particular."</td>";
					echo "<td>".$item->category_name."</td>";
					echo "<td>".$item->abas_qty."</td>";
					echo "<td>".$item->unit."</td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
				echo "</tr>";
				$ctr++;
			}
			echo "</table>";
		}

	}
	public function setBrandsFromInventory(){
		$sql = "SELECT * FROM inventory_items WHERE stat=1";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			$ctr = 1;
			foreach($result as $row){
				$item_arr = array();
				$pos = strpos($row->particular,'|');
				if($pos!==false){
					$item_arr = explode('|',$row->particular);
					$brand = addslashes($item_arr[0]);
					$particular = addslashes($item_arr[1]);
					$sql2="UPDATE inventory_items SET brand='".$brand."', particular='".$particular."' WHERE id=".$row->id;
					$query2 = $this->db->query($sql2);
					if($query2){
						echo $ctr.") ".$row->particular." ==> ".$brand."<br>";
						$ctr++;
					}
				}
			}
		}
	}
	public function setUnitPricesOfInventory(){
		$sql = "SELECT * FROM inventory_quantity WHERE unit_price=0 AND stat=1";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $row){
				$sql2 = "SELECT * from inventory_items WHERE id=".$row->item_id;
				$query2 = $this->db->query($sql2);
				if($query2){
					$result2 = $query2->row();
					$sql3 = "UPDATE inventory_quantity SET unit_price=".$result2->unit_price." WHERE id=".$row->id;
					$query3 = $this->db->query($sql3);
					if($query3){
						echo "Set Unit price (".$result2->unit_price.") of Item ".$result2->description.",".$result2->particular." with INV QTY ID ".$row->id."<br>";
					}else{
						echo "Error @ ID ".$row->id."<br>";
					}
				}else{
					echo "Error @ ID ".$row->id."<br>";
				}	
			}
		}
	}
}	
?>
