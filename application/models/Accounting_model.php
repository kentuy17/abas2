<?php
Class Accounting_model extends CI_Model{
	public function getAllExpenses($searchstring="", $limit="", $offset="", $order="", $sort="") {
		/*
		 *
		 * Creates a JSON array formatted to the bootstrap table
		 *
		 */
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='vessel_expenses' AND TABLE_SCHEMA='".DBNAME."'");
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
				$order	=	"ORDER BY ".($sort!=""?$sort:"ve.check_voucher_date")." ".$order;
			}
		}
		$searchfields	=	"";
		if($searchstring!="") {
			$searchfields	=	"";
			$searchfields	.=	"AND ve.check_voucher_no LIKE '%".$searchstring."%' "; // search only voucher number
			/* search all columns
			foreach($tablefields as $tf) {
				if($searchfields=="")  {
					$searchfields.="AND ";
				}
				else {
				$searchfields.="OR ";
				}
				$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' ";
			}
			//*/
		}
		$sql	=	"
			SELECT
				ve.*
			FROM vessel_expenses AS ve
			WHERE ve.status='Active'
			$searchfields $order $offset $limit
		";
		$total	=	"
			SELECT
				ve.*
			FROM vessel_expenses AS ve
			WHERE ve.status='Active'
			$searchfields
		";

		$all	=	$this->db->query($sql);
		$total	=	$this->db->query($total);
		$all	=	$all->result_array();

		if(!empty($all)) {
			foreach($all as $ctr=>$a) {
				$all[$ctr]['check_voucher_date']	=	($a['check_voucher_date'] != '0000-00-00' ? date("j F Y",strtotime($a['check_voucher_date'])) : '');
				$all[$ctr]['amount_in_php']			=	number_format($a['amount_in_php'],2);
			}
			$data	=	array("total"=>count($total->result_array()),"rows"=>$all); // creates array accdg to bootstrap tables
		}
		else {
			$data	=	false;
		}
		return $data;
	}
	public function view_all_expenses() {
		$data	=	$this->getAllExpenses();
		if($data!=false) {
			header('Content-Type: application/json');
			echo json_encode($data);
			exit();
		}
		else {
			$_SESSION['errmsg']	=	"An error has occurred! <pre>Error ". __class__ .":". __function__ .":". __line__ ."</pre>";
		}
	}
	public function getExpense($id) {
		$ret	=	null;
		if(is_numeric($id)) {
			$voucher	=	$this->db->query("SELECT * FROM vessel_expenses WHERE id=".$id);
			if($voucher) {
				if($voucher->row()) {
					$ret	=	(array)$voucher->row();
				}
			}
		}
		return $ret;
	}
	public function getExpenseReport($vessel='',$from_date='',$to_date='',$class='',$type='') {

		$sql = "SELECT * FROM vessel_expenses WHERE 1=1";

		if($vessel !=''){
			$sql .= " AND vessel_id =".$vessel;
		}
		if($from_date != '' || $to_date != ''){
			$sql .= " AND check_voucher_date between '".$from_date."' AND '".$to_date."'";
		}
		if($class !=''){
			$sql .= " AND expense_classification_id =".$class;
		}
		if($type !=''){
			$sql .= " AND include_on ='".$type."'";
		}

		$sql .= " ORDER BY check_voucher_date DESC ";

		//return $sql;

		$res = $this->db->query($sql);

		return $res->result_array();

	}
	public function getVoucherForApproval() {

		$sql = "SELECT * FROM ac_vouchers WHERE status = 'For voucher approval' AND transaction_type = 'Purchase Order'";
		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getVoucher($id) {
		$ret	=	null;
		if(is_numeric($id)) {
			$voucher	=	$this->db->query("SELECT * FROM ac_vouchers WHERE id=".$id);
			if($voucher) {
				if($voucher->row()) {
					$ret	=	(array)$voucher->row();
				}
			}
		}
		return $ret;
	}
	public function getVoucherAttachments($check_voucher_id){
		$sql = "SELECT * FROM ac_voucher_attachments WHERE check_voucher_id=".$check_voucher_id." AND stat=1";
		$details	=	$this->db->query($sql);
		if(!$details) { return false; }
		return $details->result_array();
	}
	public function getAPVoucher($id) {
		$ret	=	null;
		if(is_numeric($id)) {
			$voucher	=	$this->db->query("SELECT * FROM ac_ap_vouchers WHERE id=".$id);
			if($voucher) {
				if($voucher->row()) {
					$ret	=	$voucher->result_array();
				}
			}
		}
		return $ret;
	}
	public function getDeliveryByVoucherId($id='') {
		$ret = null;
		if($id!=''){
			$sql = "SELECT * FROM inventory_deliveries WHERE voucher_id = ".$id;
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }
			$ret =  $details->result_array();
		}
		return $ret;
	}
	public function getPoOwner($pono='') { // duplicate of getCompanyFromPo and of Purchasing_model->getPurchaseOrder?

		$ret = null;

		if($pono!=''){

			$sql = "SELECT name, address, telephone_no, fax_no, company_tin FROM `inventory_po` AS p
					INNER JOIN companies AS c ON p.company_id=c.id
					WHERE p.id = ".$pono;
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }

			$ret =  $details->result_array();

		}

		return $ret;

	}
	public function getVoucherForRelease() {

			//$sql = "SELECT * FROM ac_vouchers WHERE status = 'For releasing' ORDER BY voucher_date DESC";
			$sql = "SELECT * FROM ac_vouchers WHERE stat = 1 ORDER BY voucher_date DESC";
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }

			$ret =  $details->result_array();

			return $ret;

	}
	public function getBanksFromCOA($cid='') {

			//$sql = "SELECT * FROM ac_vouchers WHERE status = 'For releasing' ORDER BY voucher_date DESC";

			//$sql = "SELECT a.id, code, name FROM `ac_banks` AS x
				//		INNER JOIN ac_accounts as a on a.id=x.child_id
				//		WHERE parent_id = 4";
			$sql_append = '';
			if($cid!=''){
				$sql_append = "AND company_id =".$cid;
			}

			$sql = "SELECT a.id, a.code, b.name FROM ac_banks AS b INNER JOIN ac_accounts as a ON a.code=b.account_code
					WHERE 1=1 ".$sql_append;
			//var_dump($sql); exit;
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }

			$ret =  $details->result_array();

			return $ret;

	}
	public function getJournalEntry($id='') {
		$return	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM ac_transaction_journal WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$return	=	(array)$query->row();
					$code	=	array("business_unit"=>"00", "department"=>"00", "vessel"=>"000", "contract"=>"0000");
					$return['contract']		=	array("id"=>"");
					$return['department']	=	array("name"=>"", "accounting_code"=>"");
					$return['company']		=	array("name"=>"");
					$return['vessel']		=	array("name"=>"");
					$return['created_on']	=	date("j F Y, H:i:s", strtotime($return['created_on']));
					$account				=	$this->Accounting_model->getAccount($return['coa_id']);
					$return['account']		=	$account;
					$return['account_name']	=	$account['name'];
					$return['account_code']	=	"-";
					if(!empty($return['posted_by']))		{
						$posted_by	=		$this->Abas->getUser($return['posted_by']);
					}
					if(!empty($return['company_id']))		{
						$company	=		$this->Abas->getCompany($return['company_id']);
					}
					if(!empty($return['vessel_id']))		{
						$vessel			=	$this->Abas->getVessel($return['vessel_id']);
					}
					if(!empty($return['department_id']))	{
						$department		=	$this->Abas->getDepartment($return['department_id']);
					}
					if(!empty($return['contract_id']))		{
						$contract		=	$this->Abas->getContract($return['contract_id']);
					}
					if(isset($posted_by)) {
						$return['created_by']	=	$posted_by;
					}
					if(isset($company)) {
						if(!empty($company)) {
							$return['company']		=	(array)$company;
						}
					}
					if(isset($vessel)) {
						if(!empty($vessel)) {
							$return['vessel']		=	(array)$vessel;
							$code['vessel']			=	str_pad($vessel->id, 3, '0', STR_PAD_LEFT);
						}
					}
					if(isset($department)) {
						if(!empty($department)) {
							$return['department']	=	(array)$department;
							$code['department']		=	str_pad($department->accounting_code, 2, '0', STR_PAD_LEFT);
						}
					}
					if(isset($contract)) {
						if(!empty($contract)) {
							$return['contract']		=	$contract;
							$code['department']		=	str_pad($contract['reference_no'], 4, '0', STR_PAD_LEFT);
						}
					}
					if(!empty($account)) {
						$return['account_code']	=	$code['business_unit']."-".$code['department']."-".$code['vessel']."-".$code['contract']."-".$account['financial_statement_code']."-".$account['general_ledger_code'];
					}
					if(!empty($entry['created_on'])) {
						$data['rows'][$ctr]['created_on']	=	($return['created_on']=="0000-00-00 00:00:00") ? "" : date("j F Y H:i:s", strtotime($return['created_on']));
					}
				}
				else {
					$return	=	false;
				}
			}
			else {
				$return	=	false;
			}
		}
		else {
			$return	=	false;
		}
		return $return;
	}
	public function computeVat($amount) {
		return $amount-($amount/1.12);
	}
	public function computeTaxes($amount, $supplier_id) {

		if($amount<=0) {
			$this->Abas->sysMsg("errmsg","Amount cannot be less than 0!");
			return false;
		}
		$ret['base_amount']	=	$amount;
		$supplier	=	$this->Abas->getSupplier($supplier_id);

		if(!$supplier) {
			$this->Abas->sysMsg("errmsg","Supplier not found!");
			return false;
		}

		if(!is_numeric($supplier['taxation_percentile'])) {
			$this->Abas->sysMsg("errmsg","Supplier's taxation percentile is missing! Click <a href='".HTTP_PATH."mastertables/suppliers/edit/".$supplier['id']."'>HERE</a> to edit this supplier (".$supplier['name'].")");
			return false;
		}
		if($supplier['vat_computation']=='') {
			$this->Abas->sysMsg("errmsg","Supplier's VAT computation is missing! Click <a href='".HTTP_PATH."mastertables/suppliers/edit/".$supplier['id']."'>HERE</a> to edit this supplier (".$supplier['name'].")");
			return false;
		}
		if(strtolower($supplier['vat_computation'])=="non-vat") {
			$ret	=	array("base_amount"=>$amount, "after_vat"=>$amount, "after_etax"=>$amount, "vat"=>0, "etax"=>0);
		}
		else {
			$ret['vat']		=	$this->Accounting_model->computeVat($amount);
			$ret['etax']	=	0;
			if(strtolower($supplier['vat_computation'])=="inclusive") {
				$ret['base_amount']	=	$amount-$ret['vat'];
				$ret['etax']		=	($amount-$ret['vat'])*($supplier['taxation_percentile']/100);
			}
			elseif(strtolower($supplier['vat_computation'])=="exclusive") {
				$ret['base_amount']	=	$amount;
				$ret['etax']		=	($amount+$ret['vat'])*($supplier['taxation_percentile']/100);
			}
			$ret['after_vat']	=	$amount-$ret['vat'];
			$ret['after_etax']	=	$amount-$ret['etax'];
		}
		return $ret;
	}
	public function getExpenseClassifications(){
		$ret		=	NULL;
		$sql		=	"SELECT * FROM ac_expense_classifications";
		$details	=	$this->db->query($sql);
		if(!$details) { $ret = false; }
		else { $ret = $details->result_array(); }
		return $ret;
	}
	public function getExpenseClassification($id=''){
		$ret	=	NULL;
		if(is_numeric($id)){
			$sql		=	"SELECT * FROM ac_expense_classifications WHERE id=".$id;
			$details	=	$this->db->query($sql);
			if(!$details) { $ret = false; }
			else { $ret = $details->result_array(); }
		}
		return $ret;
	}
	public function getAllJournalTransactions($searchstring="", $limit="", $offset="", $order="DESC", $sort="") {
		/*
		 *
		 * Creates a JSON array formatted to the bootstrap table
		 *
		 */
		$tablefields			=	$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='hr_employees' AND TABLE_SCHEMA='".DBNAME."'");
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
				if($sort=="company_name") $sort="company_id";
				if($sort=="full_name") $sort="last_name";
				$order	=	"ORDER BY ".($sort!=""?$sort:"date_posted")." ".$order;
			}
		}
		$searchfields	=	"";
		if($searchstring!="") {
			$searchfields	=	"";
			foreach($tablefields as $tf) {
				if($searchfields=="")  {
					$searchfields.="AND ";
				}
				else {
				$searchfields.="OR ";
				}
				$searchfields	.=	"`".$tf->COLUMN_NAME."` LIKE '%".$searchstring."%' ";
			}
		}
		$sql	=	"
			SELECT
				*
			FROM ac_transaction_journal
			WHERE stat=1
			$searchfields $order $offset $limit
		";
		$total	=	"
			SELECT
				*
			FROM ac_transaction_journal
			WHERE stat=1
			$searchfields
		";

		$all	=	$this->db->query($sql);
		$total	=	$this->db->query($total);
		$all	=	$all->result_array();

		if(!empty($all)) {
			foreach($all as $ctr=>$a) {
				$a['account_code']		=	"-"; // taken from ac_accounts using ac_transaction_journal.coa_id
				$a['account_name']		=	"-"; // taken from ac_accounts using ac_transaction_journal.coa_id
				$a['poster_name']		=	"-"; // taken from users using ac_transaction_journal.posted_by
				$a['checker_name']		=	"-"; // taken from users using ac_transaction_journal.checked_by
				/* will company be specified in ac_transaction_journal?
				$a['company_name']		=	"-";
				if(!empty($a['company_id'])) {
					$company	=	$this->db->query("SELECT * FROM companies WHERE id=".$a['company_id']);
					if($company!=false) {
						$company		=	$company->row();
						$all[$ctr]['company_name']	=	isset($company->name) ? $company->name : $a['company_id'];
					}
				}
				//*/
				if(is_numeric($a['coa_id'])) {
					$coa	=	$this->db->query("SELECT * FROM ac_accounts WHERE id=".$a['coa_id']);
					if($coa) {
						if($coa->row()) {
							$coa	=	(array)$coa->row();
							$all[$ctr]['account_code']	=	$coa['code'];
							$all[$ctr]['account_name']	=	$coa['name'];
						}
					}
				}
				if(!empty($a['date_posted'])) {
					$all[$ctr]['date_posted']	=	($a['date_posted']=="0000-00-00 00:00:00") ? "" : date("j F Y", strtotime($a['date_posted']));
				}
				if(!empty($a['date_checked'])) {
					$all[$ctr]['date_checked']	=	($a['date_checked']=="0000-00-00 00:00:00") ? "" : date("j F Y", strtotime($a['date_checked']));
				}
			}
			$data	=	array("total"=>count($total->result_array()),"rows"=>$all); // creates array accdg to bootstrap tables
		}
		else {
			$data	=	false;
		}
		return $data;
	}
	public function getTransaction($id) {
		$ret	=	null;
		if(!is_numeric($id)) return $ret;
		$query	=	$this->db->query("SELECT * FROM ac_transactions WHERE id=".$id);
		if($query!=false) {
			if($query->row()) {
				$ret=	(array)$query->row();
				$ret['details']	=	$this->Accounting_model->getTransactionJournalEntries($id);
				$ret['company']	=	array();
				if(is_numeric($ret['company_id'])) {
					$ret['company']	=	$this->Abas->getCompany($ret['company_id']);
				}
				$ret['status']	=	null;
				$ret['status']			=	0;
				$ret['total_credit']	=	0;
				$ret['total_debit']		=	0;
				if(!empty($ret['details'])) {
					foreach($ret['details'] as $ctr=>$entry) {
						$ret['total_credit']	=	$ret['total_credit'] + $entry['credit_amount'];
						$ret['total_debit']		=	$ret['total_debit'] + $entry['debit_amount'];
						if($entry['stat']==1) $ret['status']=1;
					}
				}
			}
			else {
				$ret=	false;
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getTransactions() {
		$ret	=	null;
		$query	=	$this->db->query("SELECT * FROM ac_transactions");
		if($query!=false) {
			if($query->row()) {
				$ret=	$query->result_array();
			}
			else {
				$ret=	false;
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getTransactionJournalEntries($id) {
		$return	=	null;
		$query	=	$this->db->query("SELECT * FROM ac_transaction_journal WHERE transaction_id=".$id);
		if($query!=false) {
			if($query->row()) {
				$return=	$query->result_array();
				if(!empty($return)) {
					foreach($return as $ctr=>$entry) {
						$journal_entry	=	$this->Accounting_model->getJournalEntry($entry['id']);
						$return[$ctr]	=	$journal_entry;
					}
				}
			}
			else {
				$return=	false;
			}
		}
		else {
			$return=	false;
		}
		return $return;
	}
	public function getTransactionJournalEntriesByReference($reference_table,$reference_id){
		$return = null;
		$query = $this->db->query("SELECT * FROM ac_transaction_journal WHERE reference_table='".$reference_table."' AND reference_id=".$reference_id);
		if($query!=false) {
			if($query->row()) {
				$return=	$query->result_array();
				if(!empty($return)) {
					foreach($return as $ctr=>$entry) {
						$journal_entry	=	$this->Accounting_model->getJournalEntry($entry['id']);
						$return[$ctr]	=	$journal_entry;
					}
				}
			}
			else {
				$return=	false;
			}
		}
		else {
			$return=	false;
		}
		return $return;
	}
	public function getTransactionAttachments($id){
		$return	=	null;
		$query	=	$this->db->query("SELECT * FROM ac_transaction_attachments WHERE transaction_id=".$id);
		if($query!=false) {
			if($query->row()) {
				$return=	$query->result_array();
			}
			else {
				$return=	false;
			}
		}
		else {
			$return=	false;
		}
		return $return;
	}
	public function reconcileEntries($transaction_id, $coa_id) {
		$ret	=	false;

		$query	=	$this->db->query("SELECT * FROM ac_transaction_journal WHERE transaction_id=".$transaction_id." AND coa_id = ".$coa_id);
		if($query!=false) {
			if($query->row()) {

				$result =	$query->result_array();

				$ids = array();
				$ctr = 0;
				//loop to get ids
				foreach($result as $r){
					//get ID's
					$ids[$ctr] = $r['id'];
					$ctr++;
				}

				//loop again to update reconciling_id
				$ctr = $ctr - 1;
				$ctr2 = 0;
				foreach($result as $r){
					//do the switching of ID's

					$sql = "UPDATE ac_transaction_journal SET reconciling_id = ".$ids[$ctr]." WHERE id = ".$ids[$ctr2] ;
					$db = $this->db->query($sql);

					$ctr2++;
					$ctr=$ctr-1;
				}

				if($db){
					return TRUE;

				}
			}
			else {
				$ret=	false;
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getReconcilingEntries($reconciled_id){
		$sql = "SELECT * FROM ac_transaction_journal WHERE reconciling_id=".$reconciled_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}

		return $result;
	}
	public function newJournalEntry($entry=array()) {
		if(!is_array($entry)) { return false; }
		if(!isset($entry['debit_amount']) || !isset($entry['credit_amount'])) {
			$this->Abas->sysMsg("errmsg", "Amount not encoded!");
			return false;
		}
		$account						=	$this->Accounting_model->getAccount($entry['account']);
		if(!$account) {
			$this->Abas->sysMsg("errmsg", "Account not found!");
			return false;
		}
		$note							=	"";
		$stat							=	isset($entry['stat']) ? $this->Mmm->sanitize($entry['stat']) : 1;
		$insert['coa_id']				=	$this->Mmm->sanitize($account['id']);
		$insert['debit_amount']			=	$this->Mmm->sanitize($entry['debit_amount']);
		$insert['credit_amount']		=	$this->Mmm->sanitize($entry['credit_amount']);
		$insert['vessel_id']			=	$this->Mmm->sanitize($entry['vessel']);
		$insert['department_id']		=	$this->Mmm->sanitize($entry['department']);
		$insert['contract_id']			=	$this->Mmm->sanitize($entry['contract']);
		$insert['posted_by']			=	$_SESSION['abas_login']['userid'];
		$insert['created_on']			=	date("Y-m-d H:i:s");
		$insert['posted_on']			=	(isset($entry['posted_on'])) ? date("Y-m-d",strtotime($entry['posted_on'])) : date("Y-m-d H:i:s");
		$insert['stat']					=	$stat;
		if(!empty($entry)) {
			$insert['company_id']		=	isset($entry['company'])?$entry['company']:null;
			$insert['transaction_id']	=	isset($entry['transaction_id'])?$entry['transaction_id']:null;
			$insert['remark']			=	isset($entry['remark'])?$this->Mmm->sanitize($entry['remark']):null;
			$insert['reference_table']	=	isset($entry['reference_table'])?$entry['reference_table']:null;
			$insert['reference_id']		=	isset($entry['reference_id'])?$entry['reference_id']:null;
			$transaction				=	$this->Accounting_model->getTransaction($entry['transaction_id']);
			if(!empty($transaction)) {
				$note					.=	"- ".$transaction['remark'];
			}
			$note						.=	"- ".$insert['remark'];
		}
		$checkInsert	=	$this->Mmm->dbInsert("ac_transaction_journal", $insert, "New journal entry ".$note);
		return $checkInsert;
	}
	public function getTransactionTypes() {
		$ret	=	null;
		$sql = "SELECT * FROM ac_transaction_types ORDER BY name ASC";
		$query	=	$this->db->query($sql);
		if($query) {
			if($query->row()) {
				$ret=	$query->result_array();
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getTransactionType($id='') {
		$ret	=	null;
		if(is_numeric($id)) {
			$sql = "SELECT * FROM ac_transaction_types WHERE id=".$id." ORDER BY name ASC";
			$query	=	$this->db->query($sql);
			if($query) {
				$ret=	(array)$query->row();
			}
			else {
				$ret=	false;
			}
		}
		else {
			$ret=	false;
		}
		return $ret;
	}
	public function getAccountingEntries($tran_id='') { // duplicate of getTransactionJournalEntries?

		//$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND voucher_id IS NULL ORDER BY tdate DESC";
		if($tran_id != ''){
			$sq = " AND transaction_id ='".$tran_id."'";
		}else{
			$sq = '';
		}
		$sql = "SELECT * FROM ac_transaction_journal WHERE 1=1".$sq." ORDER BY created_on DESC";
		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getAPClearing($coa_id='') {

		//$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND voucher_id IS NULL ORDER BY tdate DESC";
		if($coa_id != ''){
			$sq = " AND coa_id ='".$coa_id."'";
		}else{
			$sq = '';
		}
		//$sql = "SELECT * FROM ap_transactions WHERE 1=1".$sq." ORDER BY created_on DESC";
		$sql = "SELECT *, t.id as tid
				FROM inventory_deliveries AS d
				INNER JOIN ac_transactions AS t
				ON d.id = t.reference_id
				WHERE d.is_cleared = 0		";
		/*
		 $sql = "	SELECT * FROM ap_transactions AS a
		 			INNER JOIN inventory_deliveries AS d
						ON d.id=a.reference_id WHERE is_cleared = 0 ".$sq." ORDER BY created_on DESC";
		*/

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getAPClearingInfo($id='') {

		//$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND voucher_id IS NULL ORDER BY tdate DESC";
		$ret = false;
		if($id != ''){

			//$sql = "SELECT * FROM ap_transactions WHERE coa_id = 283 AND transaction_id=".$id;
			$sql = "SELECT *, a.reference_id as rid, a.transaction_id as tid, a.company_id as company, d.po_no FROM ac_transaction_journal AS a
					INNER JOIN inventory_deliveries AS d
					ON a.reference_id=d.id
					WHERE a.id=".$id;

			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }
			$ret = $details->result_array();
		}

		return $ret;
	}
	public function getAPClearingDeliveries($location='') {

		//$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND voucher_id IS NULL ORDER BY tdate DESC";
		if($location != ''){
			$loc = " AND location ='".$location."'";
		}else{
			$loc = '';
		}
		$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND is_cleared = 0 ".$loc." ORDER BY tdate DESC";
		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getAPClearingDelivery($id='') {

		$ret = false;
		if($id != ''){
			$sql = "SELECT * FROM inventory_deliveries WHERE id=".$id;
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }
			$ret = $details->result_array();
		}

		return $ret;
	}
	public function getDeliveriesForVoucher($location='') {

		//$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND voucher_id IS NULL ORDER BY tdate DESC";
		if($location != ''){
			$loc = " AND location ='".$location."'";
		}else{
			$loc = '';
		}
		$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND is_cleared = 1 ".$loc." ORDER BY tdate DESC";

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getApprovedVoucher($location='') {

		//$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND voucher_id IS NULL ORDER BY tdate DESC";
		if($location != ''){
			$loc = " AND location ='".$location."'";
		}else{
			$loc = '';
		}
		//$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND is_cleared = 1 ".$loc." ORDER BY tdate DESC";
		$sql = "SELECT * FROM ac_vouchers AS v
				INNER JOIN inventory_deliveries AS d
					ON v.id = d.voucher_id
				WHERE status = 'For releasing' ".$loc." ORDER BY tdate DESC";

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getAP_vouchers($location='') {

		//$sql = "SELECT * FROM inventory_deliveries WHERE stat = 0 AND voucher_id IS NULL ORDER BY tdate DESC";
		if($location != ''){
			$loc = " AND location ='".$location."'";
		}else{
			$loc = '';
		}
			$sql = "SELECT v.id, coa_id, a.code, a.name, created_on, j.remark, posted_by, debit_amount, credit_amount, voucher_number, check_num, or_no, v.company_id from ac_vouchers AS v
					INNER JOIN ac_transaction_journal AS j
					ON v.id=transaction_id
					INNEr JOIN ac_accounts as a
					ON a.id = j.coa_id";

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getCompanyFromPO($poid='') { // duplicate of Purchasing_model->getPurchaseOrder?

		$ret= false;
		if($poid!=''){
			$sql = "SELECT c.id, name, address, telephone_no, fax_no FROM inventory_po AS p INNER JOIN companies AS c ON p.company_id=c.id WHERE p.id = ".$poid;
			$c = $this->db->query($sql);
			if(!$c){ return false; }

			$ret = $c->result_array();

		}

		return $ret;
	}
	public function getRequestPayments() {


		$sql = "SELECT * FROM ac_request_payment WHERE stat = 1 ORDER BY request_date DESC";

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getRequestPayment($id='') {
		$ret = false;
		if($id!=''){
			$sql = "SELECT * FROM ac_request_payment WHERE id=".$id;

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }
		return $details->result_array();
		}
		return $ret;
	}
	public function getRequestPaymentDetails($id='') {
		$ret = false;
		if($id!=''){
			$sql = "SELECT * FROM ac_request_payment_details WHERE request_payment_id=".$id;

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }
		return $details->result_array();
		}
		return $ret;
	}
	public function getRequestPaymentDetail($id='') {
		$ret = false;
		if($id!=''){
			$sql = "SELECT * FROM ac_request_payment_details WHERE id=".$id;

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }
		return $details->row();
		}
		return $ret;
	}
	public function getRequestPaymentAttachments($id='') {
		$ret = false;
		if($id!=''){
			$sql = "SELECT * FROM ac_request_payment_attachments WHERE request_payment_id=".$id;
		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }
		return $details->result_array();
		}
		return $ret;
	}
	public function getRFPsForVoucher() {

		$sql = "SELECT * FROM ac_request_payment WHERE stat = 1 AND status LIKE 'for voucher' ORDER BY request_date DESC";

		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	public function getRfp_ForVoucher($id='') {
		$ret = false;
		if($id!=''){
			$sql = "SELECT * FROM ac_request_payment WHERE id =".$id;

			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }

			$ret = $details->result_array();
		}
		return $ret;
	}
	public function calculateNodeValue($account_id) {
		$id				=	$account_id;
		$ret			=	0;
		$company_query	=	"";
		$root			=	$this->Accounting_model->getAccount($id);
		if ($root == null) {
			return 0;
		}
		if(isset($_POST['company'])) {
			$company		=	$this->Abas->getCompany($_POST['company']);
			$company_query	=	" AND company_id=".$company->id;
		}
		$temp	=	$this->db->query("SELECT SUM(credit_amount) AS total_credit, SUM(debit_amount) AS total_debit FROM ac_transaction_journal WHERE coa_id=".$id.$company_query);
		$temp	=	(array)$temp->row();
		$value['root']['debit']			=	$temp['total_debit'];
		$value['root']['credit']		=	$temp['total_credit'];
		$ret	=	$value;
		return $ret;
	}
	public function getAccount($id='', $daterange=array("start"=>"1970-01-01 00:00:00", "finish"=>"3000-12-31 00:00:00"), $company='') {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM ac_accounts WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
					$company_query=$date_range_query="";
					if($daterange['start']!="1970-01-01 00:00:00" && $daterange['finish']) {
						$start_report		=	date("Y-m-d",strtotime($daterange['start']))." 00:00:00";
						$finish_report		=	date("Y-m-d",strtotime($daterange['finish']))." 23:59:59";
						$date_range_query	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
					}
					if(is_numeric($company)) {
						$company		=	$this->Abas->getCompany($company);
						if($company) {
							if($company->id==1){
								$company_query  =  " AND (company_id=".$company->id." OR company_id=10) ";
							}else{
								$company_query  =  " AND company_id=".$company->id." ";
							}
						}
					}
					$ret['total_debit']		=	0;
					$ret['total_credit']	=	0;
					$ret['balance']			=	0;
					if($date_range_query!="") {
					$debit					=	$this->db->query("SELECT SUM(debit_amount) AS total FROM ac_transaction_journal WHERE stat=1 ".$date_range_query." AND coa_id=".$ret['id'].$company_query);
					$credit					=	$this->db->query("SELECT SUM(credit_amount) AS total FROM ac_transaction_journal WHERE stat=1 ".$date_range_query." AND coa_id=".$ret['id'].$company_query);
					$debit					=	(array)$debit->row();
					$credit					=	(array)$credit->row();
					$ret['total_debit']		=	$debit['total'];
					$ret['total_credit']	=	$credit['total'];
					$ret['balance']			=	$debit['total']-$credit['total'];
				}
			}
		}
		}
		return $ret;
	}
	public function getAccounts($daterange=array("start"=>"1970-01-01 00:00:00", "finish"=>"3000-12-31 00:00:00")) {
		$ret		=	null;
		$accounts	=	$this->db->query("SELECT id, financial_statement_code, general_ledger_code, classification, type FROM ac_accounts ORDER BY financial_statement_code ASC, general_ledger_code ASC");
		if($accounts) {
			if($accounts->row()) {
				$ret	=	$accounts->result_array();
				if(!empty($ret)) {
					foreach($ret as $ctr=>$account) {
						$ret[$ctr]	=	$this->Accounting_model->getAccount($account['id'], $daterange);
					}
				}
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	public function getLatestVoucherNumber() {
		$bir_hidden		=	$this->db->query("SELECT MAX(voucher_number) AS last_voucher_number FROM ac_vouchers WHERE bir_visible=0");
		$bir_visible	=	$this->db->query("SELECT MAX(voucher_number) AS last_voucher_number FROM ac_vouchers WHERE bir_visible=1");
		if($bir_hidden)		$bir_hidden		=	(array)$bir_hidden->row();
		if($bir_visible)	$bir_visible	=	(array)$bir_visible->row();
		$existing_voucher_numbers["bir_visible"]	=	!empty($bir_visible['last_voucher_number'])? $bir_visible['last_voucher_number']:"None";
		$existing_voucher_numbers["bir_hidden"]		=	!empty($bir_hidden['last_voucher_number'])? $bir_hidden['last_voucher_number']:"None";
		return $existing_voucher_numbers;
	}
	public function getJournalVoucher($id) {
		$ret	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM ac_journal_vouchers WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$ret=	(array)$query->row();
					$entries	=	json_decode($ret['journal_ids']);
					if(!empty($entries)) {
						foreach($entries as $ctr=>$entry) {
							$ret['journal_entries'][$ctr]	=	$this->Accounting_model->getJournalEntry($entry);
						}
					}
				}
			}
		}
		return $ret;
	}
	public function getFinancialStatementClassifications($include_general_ledger_accounts=false) {
		$ret		=	null;
		$accounts	=	$this->db->query("SELECT * FROM ac_financial_statement_labels ORDER BY code ASC");
		if(!$accounts) {
			return $ret;
		}
		if(!$accounts->row()) {
			return $ret;
		}
		$ret	=	$accounts->result_array();
		if(empty($ret)) {
			return $ret;
		}
		foreach($ret as $fsctr=>$fsaccount) {
			$general_ledger_accounts	=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code='".$fsaccount['code']."' ORDER BY general_ledger_code ASC");
			$ret[$fsctr]['total_debit']=$ret[$fsctr]['total_credit']=0; // initialize total values per financial statement label
			if($general_ledger_accounts) {
				if($general_ledger_accounts->row()) {
					$general_ledger_accounts	=	$general_ledger_accounts->result_array();
					if(!empty($general_ledger_accounts)) {
						foreach($general_ledger_accounts as $glctr=>$gl_account) {
							$gl_account					=	$this->Accounting_model->getAccount($gl_account['id']);
							if($include_general_ledger_accounts==true) {
								$ret[$fsctr]['general_ledger_accounts'][$glctr]	=	$gl_account;
							}
							// increment the total for each FS
							$ret[$fsctr]['total_credit']	=	$ret[$fsctr]['total_credit']+$gl_account['total_credit'];
							$ret[$fsctr]['total_debit']		=	$ret[$fsctr]['total_debit']+$gl_account['total_debit'];
						}
					}
				}
			}
		}
		return $ret;
	}
	public function getDepartmentIDByAccountingCode($accounting_code){
		$sql = "SELECT id FROM departments WHERE accounting_code=".$accounting_code;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getJournalTransactionIDByReference($reference_table,$reference_id){

		$sql = "SELECT transaction_id FROM ac_transaction_journal WHERE reference_table='".$reference_table."' AND reference_id=".$reference_id. " AND stat=1";
		$query = $this->db->query($sql);

		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getAccountReceivables($id){
		$sql = "SELECT * FROM ac_transactions WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function checkAccountsReceivablesIfPosted($reference_table,$reference_id){

		$sql = "SELECT id FROM ac_transactions WHERE reference_table='".$reference_table."' AND reference_id=".$reference_id;
		$query = $this->db->query($sql);

		$posted = FALSE;
		if($query){
			$result = $query->row();

			if($result){
				 if($this->getAccountReceivables($result->id)->stat==1){
					$posted = TRUE;
				 }
			}

		}else{
			$result = NULL;
		}

		return $posted;
	}
	public function getAccountsPayableSubsidiaryDeliveries($company,$vessel,$department,$supplier,$timestamp_filters) {
		$ret						=	false;
		$filter_parameters			=	array("company"=>"","vessel"=>"","department"=>"","contract"=>"","date_range"=>"");
		$filter_parameters['timestamp']									=	'del.tdate>="'.$timestamp_filters['start'].'" AND del.tdate<="'.$timestamp_filters['finish'].'" AND ';
		if(isset($company->id))		$filter_parameters['company']		=	'po.company_id='.$company->id." AND ";
		if(isset($vessel->id))		$filter_parameters['vessel']		=	'req.vessel_id='.$vessel->id." AND ";
		if(isset($department->id))	$filter_parameters['department']	=	'req.department_id='.$department->id." AND ";
		if(isset($supplier->id))	$filter_parameters['supplier']		=	'sup.id='.$supplier->id." AND ";
		$filter_parameters			=	implode(" ",$filter_parameters);
		$filter_parameters			=	rtrim($filter_parameters,"AND ");
		$filter_string				=	"WHERE ".$filter_parameters;
		// M-M-M-M-MONSTER QUERY!!!
		// This is to get relevant subsidiary ledger details from the $filter_queries variable above.
		$sql			=	"
		SELECT
			del.*,
			po.status AS po_status,
			po.control_number AS po_control_number,
			com.id AS company_id,
			com.name AS company_name,
			v.id AS vessel_id,
			sup.id AS suppplier_id,
			sup.name AS suppplier_name,
			apv.control_number AS apv_control_number
		FROM
			inventory_deliveries AS del
		JOIN inventory_po AS po
		ON del.po_no=po.id
		JOIN inventory_requests AS req
		ON po.request_id=req.id
		JOIN vessels AS v
		ON req.vessel_id=v.id
		JOIN companies AS com
		ON v.company=com.id
		JOIN suppliers AS sup
		ON po.supplier_id=sup.id
		JOIN ac_ap_vouchers AS apv
		ON del.id=apv.rr_no
		".$filter_string."
		ORDER BY sup.name ASC";
		$query		=	$this->db->query($sql);
		if($query) {
			if($query->row()) {
				$ret		=	$query->result_array();
				if(!empty($ret)) {
					foreach($ret as $docctr=>$document) {
						$ret[$docctr]['supplier']	=	$this->Abas->getSupplier($document['supplier_id']);
						$delivery_details			=	$this->Inventory_model->getDeliveryDetails($document['id']);
						if(!empty($delivery_details)) {
							foreach($delivery_details as $detailctr=>$detail) { // remove unneeded data to reduce memory usage
								unset($delivery_details[$detailctr]['stat']);
								unset($delivery_details[$detailctr]['delivery_id']);
								unset($delivery_details[$detailctr]['item_id']);
								unset($delivery_details[$detailctr]['quantity_issued']);
								unset($delivery_details[$detailctr]['item_detail']['stat']);
								unset($delivery_details[$detailctr]['item_detail']['reorder_level']);
								unset($delivery_details[$detailctr]['item_detail']['sub_category']);
								unset($delivery_details[$detailctr]['item_detail']['qty']);
								unset($delivery_details[$detailctr]['item_detail']['category']);
								unset($delivery_details[$detailctr]['item_detail']['location']);
								unset($delivery_details[$detailctr]['item_detail']['stock_location']);
								unset($delivery_details[$detailctr]['item_detail']['account_type']);
								unset($delivery_details[$detailctr]['item_detail']['requested']);
								unset($delivery_details[$detailctr]['item_detail']['particular']);
								unset($delivery_details[$detailctr]['item_detail']['quantity_issued']);
								unset($delivery_details[$detailctr]['item_detail']['discontinued']);
							}
							$ret[$docctr]['delivery_details']	=	$delivery_details;
						}
						$journal_entries						=	$this->db->query("SELECT id FROM ac_transaction_journal WHERE reference_table='inventory_deliveries' AND reference_id=".$document['id']);
						if($journal_entries) {
							if($journal_entries->row()) {
								$journal_entries		=	$journal_entries->result_array();
								if(!empty($journal_entries)) {
									$first_entry						=	$this->Accounting_model->getJournalEntry($journal_entries[0]['id']);
									$ret[$docctr]['transaction_id']		=	$first_entry['transaction_id'];
									$ret[$docctr]['created_by']			=	$first_entry['created_by'];
									unset($first_entry);
									foreach($journal_entries as $entryctr=>$entry) {
										$journal_entries[$entryctr]	=	$this->Accounting_model->getJournalEntry($entry['id']);
										unset($journal_entries[$entryctr]['company']);
										unset($journal_entries[$entryctr]['vessel']);
										unset($journal_entries[$entryctr]['contract']);
										unset($journal_entries[$entryctr]['department']);
										unset($journal_entries[$entryctr]['account']);
										unset($journal_entries[$entryctr]['created_by']);
									}
									$ret[$docctr]['journal_entries']	=	$journal_entries;
								}
							}
						}
						unset($journal_entries);
						unset($ret[$docctr]['delivery_no']);
						unset($ret[$docctr]['doc_si']);
						unset($ret[$docctr]['doc_po']);
						unset($ret[$docctr]['doc_dr']);
						unset($ret[$docctr]['doc_dr']);
					}
				}
			}
		}
		return $ret;
	}
	public function getAccountsReceivableSubsidiaryStatementsOfAccount($company,$contract,$client,$timestamp_filters) {
		$ret						=	false;
		$filter_parameters			=	array("company"=>"","vessel"=>"","department"=>"","contract"=>"","date_range"=>"");
		$filter_parameters['timestamp']									=	'created_on>="'.$timestamp_filters['start'].'" AND created_on<="'.$timestamp_filters['finish'].'" AND ';
		if(isset($company->id))		$filter_parameters['company']		=	'company_id='.$company->id." AND ";
		if(isset($contract->id))	$filter_parameters['contract']		=	'contract_id='.$contract->id." AND ";
		if(isset($client['id']))	$filter_parameters['client']		=	'client_id='.$client['id']." AND ";
		$filter_parameters			=	implode(" ",$filter_parameters);
		$filter_parameters			=	rtrim($filter_parameters,"AND ");
		$filter_string				=	"WHERE status LIKE 'Approved' AND ".$filter_parameters;
		$sql						=	"SELECT id FROM statement_of_accounts ".$filter_string;
		$query						=	$this->db->query($sql);
		if($query) {
			if($query->row()) {
				$ret		=	$query->result_array();
				if(!empty($ret)) {
					foreach($ret as $docctr=>$document) {
						$ret[$docctr]					=	$this->Billing_model->getStatementOfAccount($document['id']);
						$ret[$docctr]['amount']			=	$this->Billing_model->getSOAAmount($ret[$docctr]['type'], $document['id']);
						$ret[$docctr]['company_name']	=	$ret[$docctr]['company']->name;
						$ret[$docctr]['client_name']	=	$ret[$docctr]['client']['company'];
						unset($ret[$docctr]['company']);
						unset($ret[$docctr]['client']);
						$ret[$docctr]['journal_entries']		=	array();
						$entries						=	$this->db->query("SELECT id FROM ac_transaction_journal WHERE reference_table='statement_of_accounts' AND reference_id=".$document['id']." AND stat=1");
						if($entries) {
							if($entries->row()) {
								$entries				=	$entries->result_array();
								foreach($entries as $entryctr=>$entry) {
									$entry						=	$this->Accounting_model->getJournalEntry($entry['id']);
									unset($entry['contract']);
									unset($entry['department']);
									unset($entry['company']);
									unset($entry['vessel']);
									unset($entry['account']);
									$ret[$docctr]['journal_entries'][]	=	$entry;
								}
							}
						}
					}
				}
			}
		}
		return $ret;
	}

	///SUMMARY REPORT FOR OFFICIAL RECEIPT AND ACKNOWLEDGEMENT RECEIPT//////////////// 
   public function getPaymentReceipts($type,$date_from=NULL,$date_to=NULL,$company=NULL){ 
     switch($type){ 
      case "official_receipts": 
 
        if($date_from==NULL && $date_to==NULL && $company==NULL){ 
          $sql = "SELECT *,payments_official_receipts.control_number as or_num FROM payments INNER JOIN payments_official_receipts ON payments.id=payments_official_receipts.payment_id"; 
        }elseif($date_from!=NULL && $date_to!=NULL && $company==NULL){ 
          $sql = "SELECT *,payments_official_receipts.control_number as or_num FROM payments INNER JOIN payments_official_receipts ON payments.id=payments_official_receipts.payment_id WHERE payments_official_receipts.issued_date BETWEEN '".$date_from."' AND '".$date_to."'"; 
        }elseif($date_from!=NULL && $date_to!=NULL && $company!=NULL){ 
          $sql = "SELECT *,payments_official_receipts.control_number as or_num FROM payments INNER JOIN payments_official_receipts ON payments.id=payments_official_receipts.payment_id WHERE payments_official_receipts.issued_date BETWEEN '".$date_from."' AND '".$date_to."' AND payments_official_receipts.company_id =".$company; 
        }elseif($date_from==NULL && $date_to==NULL && $company!=NULL){ 
          $sql = "SELECT *,payments_official_receipts.control_number as or_num FROM payments INNER JOIN payments_official_receipts ON payments.id=payments_official_receipts.payment_id WHERE payments_official_receipts.company_id =".$company; 
        } 
 
      break; 
 
      case "acknowledgement_receipts": 
         
        if($date_from==NULL && $date_to==NULL && $company==NULL){ 
          $sql = "SELECT *,payments_acknowledgement_receipts.control_number as ar_num FROM payments INNER JOIN payments_acknowledgement_receipts ON payments.id=payments_acknowledgement_receipts.payment_id"; 
        }elseif($date_from!=NULL && $date_to!=NULL && $company==NULL){ 
          $sql = "SELECT *,payments_acknowledgement_receipts.control_number as ar_num FROM payments INNER JOIN payments_acknowledgement_receipts ON payments.id=payments_acknowledgement_receipts.payment_id WHERE payments_acknowledgement_receipts.issued_date BETWEEN '".$date_from."' AND '".$date_to."'"; 
        }elseif($date_from!=NULL && $date_to!=NULL && $company!=NULL){ 
          $sql = "SELECT *,payments_acknowledgement_receipts.control_number as ar_num FROM payments INNER JOIN payments_acknowledgement_receipts ON payments.id=payments_acknowledgement_receipts.payment_id WHERE payments_acknowledgement_receipts.issued_date BETWEEN '".$date_from."' AND '".$date_to."' AND payments_acknowledgement_receipts.company_id =".$company; 
        }elseif($date_from==NULL && $date_to==NULL && $company!=NULL){ 
          $sql = "SELECT *,payments_acknowledgement_receipts.control_number as ar_num FROM payments INNER JOIN payments_acknowledgement_receipts ON payments.id=payments_acknowledgement_receipts.payment_id WHERE payments_acknowledgement_receipts.company_id =".$company; 
        } 
 
      break; 
    } 
 
    $query = $this->db->query($sql); 
     
    if($query){ 
      $result = $query->result(); 
 
      foreach($result as $ctr=>$row){ 
        $result[$ctr]->company = $this->Abas->getCompany($row->company_id)->name; 
        $user = $this->Abas->getUser($row->received_by); 
        $result[$ctr]->issued_by = $user['full_name']; 
      } 
 
    }else{ 
      $result = NULL; 
    } 
     
    return $result; 
   } 

   public function getMaterialSuppliesIssuances($type,$date_from,$date_to,$location="",$filter=""){

			if($type=="MSIS" || $type=="MSIS_consolidated"){
				$table = "inventory_issuance_details";
				$table_inner_id = "issuance_id";
				$table_inner_join = "inventory_issuance";
				$table_inner_join_2 = "";
				$date_field = "issue_date";
				$loc_field = "from_location";
				$filter_field = "vessel_id";
			}


				if($date_from!="" && $date_to!=""){
				
					if($filter=="" && $location!=""){
						$sql = "SELECT * FROM " . $table . " INNER JOIN ".$table_inner_join." ON ".$table.".".$table_inner_id."=".$table_inner_join.".id WHERE " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . "' AND " . $loc_field . "='" . $location . "'";
					}

					if($filter!="" && $location!=""){
						$sql = "SELECT * FROM " . $table . " INNER JOIN ".$table_inner_join." ON ".$table.".".$table_inner_id."=".$table_inner_join.".id WHERE " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . "' AND " . $filter_field . "='" . $filter . "' AND " . $loc_field . "='" . $location . "'";
					}

					if($filter=="" && $location==""){
						$sql = "SELECT * FROM " . $table . " INNER JOIN ".$table_inner_join." ON ".$table.".".$table_inner_id."=".$table_inner_join.".id WHERE " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
					}

					if($filter!="" && $location==""){
						if($type=="MSIS"){
							$sql = "SELECT * FROM " . $table . " INNER JOIN ".$table_inner_join." ON ".$table.".".$table_inner_id."=".$table_inner_join.".id WHERE " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . "' AND " . $filter_field . "='" . $filter . "'";
						}
						//if consolidated per company
						elseif($type=="MSIS_consolidated"){
							//$sql = "SELECT ISS.*,ISD.*,(SELECT company from vessels WHERE id = ISS.vessel_id LIMIT 1) AS company_id 
									//FROM inventory_issuance_details ISD 
									//INNER JOIN inventory_issuance ISS ON ISD.issuance_id = ISS.id 
									//HAVING company_id=".$filter. " AND issue_date BETWEEN '" . $date_from . "' AND '" . $date_to . "' ORDER BY ISS.vessel_id ASC";

							if($filter==99999 || $filter==99998 || $filter==99997 || $filter==99996 || $filter==99995 || $filter==99993 || $filter==99990){
								$filter = 1;
							}elseif($filter==99994){
								$filter = 5;
							}elseif($filter==99992){
								$filter = 4;
							}elseif($filter==99991){
								$filter = 11;
							}elseif($filter==101){
								$filter = 5;
							}
							
							$sql = "SELECT ISS.*,ISD.*,
									IF((SELECT company from vessels WHERE id = ISS.vessel_id LIMIT 1) IS NULL,
									CASE ISS.vessel_id
										WHEN 99999 THEN 1
										WHEN 99998 THEN 1
										WHEN 99997 THEN 1
										WHEN 99996 THEN 1
										WHEN 99995 THEN 1
										WHEN 99993 THEN 1
										WHEN 99990 THEN 1
										WHEN 99994 THEN 5
										WHEN 99992 THEN 4
										WHEN 99991 THEN 11
										WHEN 101 THEN 5
									END,
									(SELECT company from vessels WHERE id = ISS.vessel_id LIMIT 1)) 
									AS company_id FROM inventory_issuance_details ISD 
									INNER JOIN inventory_issuance ISS ON ISD.issuance_id = ISS.id 
									HAVING company_id=".$filter. " AND issue_date BETWEEN '" . $date_from . "' AND '" . $date_to . "' ORDER BY ISS.vessel_id ASC, ISS.issue_date ASC, ISS.control_number";
						}

					}
		 
				}
				elseif($date_from=="" && $date_to==""){
				
					if($filter=="" && $location!=""){
						$sql = "SELECT * FROM " . $table . " INNER JOIN ".$table_inner_join." ON ".$table.".".$table_inner_id."=".$table_inner_join.".id WHERE " . $loc_field . "='" . $location . "'";
					}

					if($filter!="" && $location!=""){
						if($type=="MSIS"){
							$sql = "SELECT * FROM " . $table . " INNER JOIN ".$table_inner_join." ON ".$table.".".$table_inner_id."=".$table_inner_join.".id WHERE " . $loc_field . "='" . $location . "' AND " . $filter_field . "='" . $filter . "'";
						}
						elseif($type=="MSIS_consolidated"){
							if($filter==99999 || $filter==99998 || $filter==99997 || $filter==99996 || $filter==99995 || $filter==99993 || $filter==99990){
								$filter = 1;
							}elseif($filter==99994){
								$filter = 5;
							}elseif($filter==99992){
								$filter = 4;
							}elseif($filter==99991){
								$filter = 11;
							}elseif($filter==101){
								$filter = 5;
							}

							$sql = "SELECT ISS.*,ISD.*,
									IF((SELECT company from vessels WHERE id = ISS.vessel_id LIMIT 1) IS NULL,
									CASE ISS.vessel_id
										WHEN 99999 THEN 1
										WHEN 99998 THEN 1
										WHEN 99997 THEN 1
										WHEN 99996 THEN 1
										WHEN 99995 THEN 1
										WHEN 99993 THEN 1
										WHEN 99990 THEN 1
										WHEN 99994 THEN 5
										WHEN 99992 THEN 4
										WHEN 99991 THEN 11
										WHEN 101 THEN 5
									END,
									(SELECT company from vessels WHERE id = ISS.vessel_id LIMIT 1)) 
									AS company_id FROM inventory_issuance_details ISD 
									INNER JOIN inventory_issuance ISS ON ISD.issuance_id = ISS.id 
									HAVING company_id=".$filter. " AND from_location='".$location."' ORDER BY ISS.vessel_id ASC, ISS.issue_date ASC, ISS.control_number";

						}
					}

					if($filter=="" && $location==""){
						$sql = "SELECT * FROM " . $table . " INNER JOIN ".$table_inner_join." ON ".$table.".".$table_inner_id."=".$table_inner_join.".id";
					}

					if($filter!="" && $location==""){
						if($type=="MSIS"){
							$sql = "SELECT * FROM " . $table . " INNER JOIN ".$table_inner_join." ON ".$table.".".$table_inner_id."=".$table_inner_join.".id WHERE " . $filter_field . "='" . $filter . "'";
						}
						//if consolidated per company
						elseif($type=="MSIS_consolidated"){

							//$sql = "SELECT ISS.*,ISD.*,(SELECT company from vessels WHERE id = ISS.vessel_id LIMIT 1) AS company_id 
									//FROM inventory_issuance_details ISD 
									//INNER JOIN inventory_issuance ISS ON ISD.issuance_id = ISS.id 
									//HAVING company_id=".$filter. " ORDER BY ISS.vessel_id ASC";

							if($filter==99999 || $filter==99998 || $filter==99997 || $filter==99996 || $filter==99995 || $filter==99993 || $filter==99990){
								$filter = 1;
							}elseif($filter==99994){
								$filter = 5;
							}elseif($filter==99992){
								$filter = 4;
							}elseif($filter==99991){
								$filter = 11;
							}elseif($filter==101){
								$filter = 5;
							}

							$sql = "SELECT ISS.*,ISD.*,
									IF((SELECT company from vessels WHERE id = ISS.vessel_id LIMIT 1) IS NULL,
									CASE ISS.vessel_id
										WHEN 99999 THEN 1
										WHEN 99998 THEN 1
										WHEN 99997 THEN 1
										WHEN 99996 THEN 1
										WHEN 99995 THEN 1
										WHEN 99993 THEN 1
										WHEN 99990 THEN 1
										WHEN 99994 THEN 5
										WHEN 99992 THEN 4
										WHEN 99991 THEN 11
										WHEN 101 THEN 5
									END,
									(SELECT company from vessels WHERE id = ISS.vessel_id LIMIT 1)) 
									AS company_id FROM inventory_issuance_details ISD 
									INNER JOIN inventory_issuance ISS ON ISD.issuance_id = ISS.id 
									HAVING company_id=".$filter. " ORDER BY ISS.vessel_id ASC, ISS.issue_date ASC, ISS.control_number";

						}
					}

				}

			
			
			if($date_from!="" && $date_to=="" || $date_from=="" && $date_to!=""){
				$sql= null;
			}else{
				$query = $this->db->query($sql);



				if($query){
					$data['summary'] =$query->result_array();
				}
			}	

				//$this->Mmm->debug($sql);

				$data['type'] = $type;
				$data['date_from'] = $date_from;
				$data['date_to'] = $date_to;
				$data['location'] = $location;
				$data['filter'] = $filter;

				return $data;

	}
	public function getRFPForVessel($vessel_id){
		$sql = "SELECT * FROM ac_request_payment_details WHERE charge_to=".$vessel_id."";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$row){

				$sql2 = "SELECT * FROM ac_request_payment WHERE id=".$row->request_payment_id."";
				$query2 = $this->db->query($sql2);

				$result2 = $query2->row();

				if($result2->payee_type=='Supplier'){
					 $supplier = 	$this->Abas->getSupplier($result2->payee);
					 $result[$ctr]->payee = $supplier['name'];
				}else{
					 $employee = 	$this->Abas->getEmployee($result2->payee);
					 $result[$ctr]->payee = $employee['full_name'];
				}

				$result[$ctr]->status = $result2->status;
				$result[$ctr]->reference_id = $result2->reference_id;
				$result[$ctr]->purpose = $result2->purpose." | ". $row->particulars;
				$result[$ctr]->request_date = $result2->request_date;
			}
		}else{
			$result = null;
		}
		return $result;
	}

	public function getStatementOfIncome($start_date,$end_date,$company=NULL){
		$result = array();
		$company_query=$department_query=$vessel_query=$contract_query=$company_id="";
		
		if(is_numeric($company)){
			$company		=	$this->Abas->getCompany($company);
			if($company) {
				$company_id				=	$company->id;
				$company_query			=	' AND company_id='.$company->id;
				$company_account_code	=	str_pad($company->id, 3, '0', STR_PAD_LEFT);
			}
		}

		$daterange1	=	array("start"=>date("Y-m-d H:i:s", strtotime($start_date)), "finish"=>date("Y-m-d H:i:s", strtotime($end_date)));

		$financial_statement_accounts	=	$this->Accounting_model->getFinancialStatementClassifications(true);
		if(empty($financial_statement_accounts)) {
			$this->Abas->sysMsg("errmsg", "No account classifications were found!");
			$this->Abas->redirect($previous_page);
		}
		 // Revenues
		$revenues_balance			=	array("range1"=>0);
		$revenues_accounts['revenues']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code>=4141 AND code<=4148");
		$revenues_accounts['revenues']		=	$revenues_accounts['revenues']->result_array();

		$subcategory_total						=	array("range1"=>0);
		foreach($revenues_accounts['revenues'] as $revenues) {
			$gl_accounts						=	$this->db->query("SELECT id,financial_statement_code,general_ledger_code,name FROM ac_accounts WHERE financial_statement_code=".$revenues['code']);
			$gl_accounts						=	$gl_accounts->result_array();
			$revenues['range1']			=	array("balance"=>0);
			foreach($gl_accounts as $glctr=>$gl_account) {
			
				$start_report		=	date("Y-m-d",strtotime($daterange1['start']))." 00:00:00";
				$finish_report		=	date("Y-m-d",strtotime($daterange1['finish']))." 23:59:59";
				$date_range_query1	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";

			
				$values1			=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query1." AND coa_id=".$gl_account['id'].$company_query);
		
				$values1				=	(array)$values1->row();
				$revenues['range1']['balance']	=	(($revenues['range1']['balance']+($values1['total_debit']-$values1['total_credit']))*(-1));
				
			
			}
			$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$revenues['range1']['balance']);
		}
		unset($revenues_accounts['revenues']);
		$revenues_balance['range1']	+=	$subcategory_total['range1'];
		//echo "REVENUE: ".number_format($revenues_balance['range1'],2)."<br>";
		$result['revenue'] = $revenues_balance['range1'];
			
		
		/// Direct Costs
		$direct_costs_balance			=	array("range1"=>0);
		$direct_costs_report['direct_costs']=$direct_costs_report['display']='';
		$direct_costs_accounts['direct_costs']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code>=5156 AND code<=5160");
		$direct_costs_accounts['direct_costs']		=	$direct_costs_accounts['direct_costs']->result_array();

		$subcategory_total						=	array("range1"=>0);
		foreach($direct_costs_accounts['direct_costs'] as $direct_costs) {
			$gl_accounts						=	$this->db->query("SELECT id,financial_statement_code,general_ledger_code,name FROM ac_accounts WHERE financial_statement_code=".$direct_costs['code']);
			$gl_accounts						=	$gl_accounts->result_array();
			$direct_costs['range1']			=	array("balance"=>0);
			foreach($gl_accounts as $glctr=>$gl_account) {
				if($daterange1['start']!="1970-01-01 00:00:00" && $daterange1['finish']) {
					$start_report		=	date("Y-m-d",strtotime($daterange1['start']))." 00:00:00";
					$finish_report		=	date("Y-m-d",strtotime($daterange1['finish']))." 23:59:59";
					$date_range_query1	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
				}
				$values1				=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query1." AND coa_id=".$gl_account['id'].$company_query);
				
				$values1				=	(array)$values1->row();
				$direct_costs['range1']['balance']	=	$direct_costs['range1']['balance']+($values1['total_debit']-$values1['total_credit']);
				
			}
			$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$direct_costs['range1']['balance']);
		}
		unset($direct_costs_accounts['direct_costs']);
		$direct_costs_balance['range1']	+=	$subcategory_total['range1'];
		//echo "DIRECT COST: ".number_format($direct_costs_balance['range1'],2)."<br>";
		$result['direct_cost'] = $direct_costs_balance['range1'];
	
		// Operating Expenses
		$operating_expenses_balance				=	array("range1"=>0);
		
		$operating_expenses_report['expenses']=$operating_expenses_report['expenses']='';
		$operating_expenses_accounts['expenses']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code>=6166 AND code<=6195 AND code<>6183");
		$operating_expenses_accounts['expenses']		=	$operating_expenses_accounts['expenses']->result_array();
		$equityctr								=	0;
		$subcategory_total						=	array("range1"=>0);
		foreach($operating_expenses_accounts['expenses'] as $operating_expense) {
			$gl_accounts						=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code=".$operating_expense['code']);
			$gl_accounts						=	$gl_accounts->result_array();
			$operating_expense['range1']			=	array("balance"=>0);
			foreach($gl_accounts as $glctr=>$gl_account) {
				$account['range1']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange1, $company_id);
				$operating_expense['range1']['balance']	=	$operating_expense['range1']['balance']+($account['range1']['total_debit']-$account['range1']['total_credit']);
			}
			$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$operating_expense['range1']['balance']);
			$display_name	=	strlen($operating_expense['name']) > 30 ? substr($operating_expense['name'],0,30)."..." : $operating_expense['name'];
			
		}
		unset($operating_expenses_accounts['expenses']);
		$operating_expenses_balance['range1']	+=	$subcategory_total['range1'];
		//echo "OPR EXPENSE: ".number_format($operating_expenses_balance['range1'],2)."<br>";
		$result['operating_expense'] = $operating_expenses_balance['range1'];	
	
		// Others
		$other_income_balance				=	array("range1"=>0);
		// Interest Expense
		$other_interest_expense_report['interest_expense']=$other_interest_expense_report['interest_expense']='';
		$other_interest_expense_accounts['interest_expense']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '6183'");
		$other_interest_expense_accounts['interest_expense']		=	$other_interest_expense_accounts['interest_expense']->result_array();
		$subcategory_total						=	array("range1"=>0);
		foreach($other_interest_expense_accounts['interest_expense'] as $other_interest_expense) {
			$gl_accounts						=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code=6183");
			$gl_accounts						=	$gl_accounts->result_array();
			$other_interest_expense['range1']			=	array("balance"=>0);
			foreach($gl_accounts as $glctr=>$gl_account) {
				$account['range1']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange1, $company_id);
				$other_interest_expense['range1']['balance']	=	$other_interest_expense['range1']['balance']+($account['range1']['total_debit']-$account['range1']['total_credit']);
			}
			$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$other_interest_expense['range1']['balance']);
			$display_name	=	strlen($other_interest_expense['name']) > 30 ? substr($other_interest_expense['name'],0,30)."..." : $other_interest_expense['name'];
			
		}
		unset($other_interest_expense_accounts['interest_expense']);
		$other_income_balance['range1']	-=	$subcategory_total['range1'];
		//echo "INTREST EXPENSE: ".number_format($other_income_balance['range1'],2)."<br>";
			
		// Other Income
		$other_income_report['income']=$other_income_report['income']='';
		$other_income_accounts['income']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '4155'");
		$other_income_accounts['income']		=	$other_income_accounts['income']->result_array();
		$subcategory_total						=	array("range1"=>0);
		foreach($other_income_accounts['income'] as $other_income) {
			$gl_accounts						=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code=4155");
			$gl_accounts						=	$gl_accounts->result_array();
			$other_income['range1']			=	array("balance"=>0);
			foreach($gl_accounts as $glctr=>$gl_account) {
				$account['range1']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange1, $company_id);
				$other_income['range1']['balance']	=	$other_income['range1']['balance']+($account['range1']['total_debit']-$account['range1']['total_credit']);
			}
			$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$other_income['range1']['balance']);
			// special case: include GL7900 if GL7900 is balanced in favor of debit
			$gl7900			=	array("range1"=>$this->Accounting_model->getAccount(289, $daterange1, $company_id));
			$gl7900['range1']['balance']	=	$gl7900['range1']['total_debit']-$gl7900['range1']['total_credit'];
			if($gl7900['range1']['balance']>0) {
				$other_income['range1']['balance']	=	$other_income['range1']['balance']+$gl7900['range1']['balance'];
			}
			
			$display_name	=	strlen($other_income['name']) > 30 ? substr($other_income['name'],0,30)."..." : $other_income['name'];
		}
		unset($other_income_accounts['income']);
		$other_income_balance['range1']	+=	$subcategory_total['range1'];
		//echo "OTHR INCOME: ".number_format($other_income_balance['range1'],2)."<br>";
			
		// Other Expense
		$other_expense		=	array("range1"=>0);
		$other_expense['range1']		=	array("balance"=>0);
		$other_expense_report['expense']=$other_expense_report['expense']='';
		// special case: include GL7900 if GL7900 is balanced in favor of credit
		$gl7900			=	array("range1"=>$this->Accounting_model->getAccount(289, $daterange1, $company_id));
		$gl7900['range1']['balance']	=	$gl7900['range1']['total_debit']-$gl7900['range1']['total_credit'];
		if($gl7900['range1']['balance']<0) {
			$other_expense['range1']['balance']	=	$other_expense['range1']['balance']+$gl7900['range1']['balance'];
		}
		
		$other_expense['name']	=	$gl7900['range1']['name'];
		$display_name	=	strlen($other_expense['name']) > 30 ? substr($other_expense['name'],0,30)."..." : $other_expense['name'];
		
		$other_income_balance['range1']	-=	$subcategory_total['range1'];
		//echo "OTHR EXPENSE: ".number_format($other_income_balance['range1'],2)."<br>";


		/////////////////////////////////////////////////
		//TEMPORARY FIX FOR INCOME CALCULATION 02262018//
		////////////////////////////////////////////////
		$net_income_before_tax_r1 = abs($other_income['range1']['balance']) + abs($other_expense['range1']['balance']) + ($revenues_balance['range1']-$direct_costs_balance['range1']-$operating_expenses_balance['range1']) - $other_interest_expense['range1']['balance'];
		////////////////////////////////
		////////////////////////////////////////////////
		
		$result['net_income'] = $net_income_before_tax_r1;
		return $result;
		
	}
	public function getLapsingSchedule($id){
		$sql = "SELECT * FROM ac_lapsing_schedules WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();

			$company	=	$this->Abas->getCompany($result->company_id);
			$result->company_name = $company->name;
			$result->company_address = $company->address;
			$result->company_contact = $company->telephone_no;

			$created_by = $this->Abas->getUser($result->created_by);
			$result->created_by = $created_by['full_name'];

			$modified_by = $this->Abas->getUser($result->modified_by);
			$result->modified_by = $modified_by['full_name'];

		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getLapsingScheduleDetails($lapsing_id){
		$sql = "SELECT * FROM ac_lapsing_schedule_details WHERE stat=1 AND lapsing_schedule_id=".$lapsing_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$x) {
				$fixed_asset = $this->Asset_Management_model->getFixedAsset($result[$ctr]->fixed_asset_id);
				$category = $this->Inventory_model->getCategory($fixed_asset->category_id);
				$department = $this->Abas->getDepartment($fixed_asset->department_id);
				if($fixed_asset->item_id!=0){
					$item = $this->Inventory_model->getItem($fixed_asset->item_id);
					$result[$ctr]->item_id = $item[0]['id'];
					$result[$ctr]->item_name = $item[0]['item_name'];
					$result[$ctr]->item_particular = $item[0]['particular'];
				}else{
					$result[$ctr]->item_id = 0;
					$result[$ctr]->item_name = $fixed_asset->item_name;
					$result[$ctr]->item_particular =  $fixed_asset->particular;
				}
				$result[$ctr]->asset_code = $fixed_asset->asset_code."-".$fixed_asset->control_number;
				$result[$ctr]->category = $category->category;
				$result[$ctr]->department = $department->name;
				$result[$ctr]->date_acquired = date('F j, Y',strtotime($fixed_asset->date_acquired));
			}
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getLapsingScheduleDetail($id){
		$sql = "SELECT * FROM ac_lapsing_schedule_details WHERE stat=1 AND id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
			$fixed_asset = $this->Asset_Management_model->getFixedAsset($result->fixed_asset_id);
			$item = $this->Inventory_model->getItem($fixed_asset->item_id);
			$result->item_id = $item[0]['id'];
				$result->asset_code = $fixed_asset->asset_code."-".$fixed_asset->control_number;
				$result->item_name = $item[0]['item_name'];
				$result->item_particular = $item[0]['particular'];
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getPreviousEndAccumulatedDepreciation($company_id,$asset_id,$previous_year){
		$sql = "SELECT ad.end_accumulated_depreciation FROM ac_lapsing_schedules as al INNER JOIN ac_lapsing_schedule_details as ad ON al.id=ad.lapsing_schedule_id WHERE al.company_id=".$company_id." AND al.year=".$previous_year. " AND ad.fixed_asset_id=".$asset_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getPreviousEndNetBookValue($company_id,$asset_id,$previous_year){
		$sql = "SELECT ad.end_net_book_value FROM ac_lapsing_schedules as al INNER JOIN ac_lapsing_schedule_details as ad ON al.id=ad.lapsing_schedule_id WHERE al.company_id=".$company_id." AND al.year=".$previous_year. " AND ad.fixed_asset_id=".$asset_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function getComputedEndAccumulatedDepreciation($id){
		$sql = "SELECT * FROM ac_lapsing_schedule_details WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $row){
				$end_accumulated_depreciation = $row->begin_accumulated_depreciation + $row->january_depreciation + $row->february_depreciation + $row->march_depreciation + $row->april_depreciation + $row->may_depreciation + $row->june_depreciation + $row->july_depreciation + $row->august_depreciation + $row->september_depreciation + $row->october_depreciation + $row->november_depreciation +$row->december_depreciation;
			}
		}
		return $end_accumulated_depreciation;
	}
	public function getComputedEndNetBookValue($id){
		$sql = "SELECT * FROM ac_lapsing_schedule_details WHERE id=".$id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $row){
				$end_net_book_value = $row->begin_net_book_value - $row->january_depreciation - $row->february_depreciation - $row->march_depreciation - $row->april_depreciation - $row->may_depreciation - $row->june_depreciation - $row->july_depreciation - $row->august_depreciation - $row->september_depreciation - $row->october_depreciation - $row->november_depreciation - $row->december_depreciation;
			}
		}
		return $end_net_book_value;
	}
	public function getReceivingReportForClearing() {
		$sql="SELECT *, j.id as jid FROM ac_transaction_journal AS j INNER JOIN inventory_deliveries AS d ON j.reference_id=d.id WHERE coa_id=".AP_CLEARING." and j.reconciling_id IS NULL";
		$query=$this->db->query($sql);
		if(!$query){ return false; };
		return	$query->result_array();
	}
	public function getAccountingEntry($id='',$ref_table='') {
		$ret=false;
		if($id!=''){
			$sql="SELECT *, t.transaction_id as tid, t.id as jid FROM ac_transaction_journal AS t INNER JOIN ac_accounts AS a ON t.coa_id=a.id WHERE reference_id=$id AND reference_table='".$ref_table."'";
			$query=$this->db->query($sql);
			$ret= $query->result_array();
		}
		return $ret;
	}
	public function getAccountsPayableVoucher($apv_id){
		$sql = "SELECT * FROM ac_ap_vouchers WHERE id=".$apv_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getAccountsPayableWTax($apv_id){
		$sql = "SELECT * FROM ac_ap_voucher_wtax WHERE ap_voucher_id=".$apv_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getAccountsPayableAttachments($apv_id){
		$sql = "SELECT * FROM ac_ap_voucher_attachments WHERE ap_voucher_id=".$apv_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getExpandedTaxCodes($id=''){
		if($id!=''){
			$sql = "SELECT * FROM ac_expanded_wtax_codes WHERE id=".$id;
		}else{
			$sql = "SELECT * FROM ac_expanded_wtax_codes";
		}
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getAPVperSupplier($supplier_id='',$company_id=''){
		if($supplier_id==''){
			$sql = "SELECT (SELECT id FROM suppliers WHERE id=ac_ap_vouchers.payee) as supplier_id, count(*) as apv_count FROM ac_ap_vouchers WHERE (NOT EXISTS (SELECT * FROM ac_vouchers WHERE apv_no=ac_ap_vouchers.id AND transaction_type='po') AND ac_ap_vouchers.check_voucher_id='') GROUP BY ac_ap_vouchers.payee ORDER by ac_ap_vouchers.created_on ASC";

		}else{
			if($company_id==''){
				$sql = "SELECT (SELECT id FROM suppliers WHERE id=".$supplier_id.") as supplier_id, (SELECT name FROM companies WHERE id=ac_ap_vouchers.company_id) as company_name,(SELECT amount from inventory_deliveries WHERE id=ac_ap_vouchers.rr_no) as amount, ac_ap_vouchers.* FROM ac_ap_vouchers WHERE (NOT EXISTS (SELECT * FROM ac_vouchers WHERE apv_no=ac_ap_vouchers.id AND transaction_type='po') AND ac_ap_vouchers.check_voucher_id='') AND ac_ap_vouchers.payee=".$supplier_id." ORDER by ac_ap_vouchers.created_on ASC";
			}else{
				$sql = "SELECT (SELECT id FROM suppliers WHERE id=".$supplier_id.") as supplier_id, (SELECT name FROM companies WHERE id=ac_ap_vouchers.company_id) as company_name,(SELECT amount from inventory_deliveries WHERE id=ac_ap_vouchers.rr_no) as amount, ac_ap_vouchers.* FROM ac_ap_vouchers WHERE (NOT EXISTS (SELECT * FROM ac_vouchers WHERE apv_no=ac_ap_vouchers.id AND transaction_type='po') AND ac_ap_vouchers.check_voucher_id='') AND ac_ap_vouchers.payee=".$supplier_id." AND ac_ap_vouchers.company_id=".$company_id." ORDER by ac_ap_vouchers.created_on ASC";
			}
		}
	
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
}
?>
