<?php
Class Finance_model extends CI_Model{
	
	public function getBanks(){
            
			$sql = "SELECT * FROM ac_banks ORDER BY name ASC";
			
			$query = $this->db->query($sql);
			return $query->result_array();
	}
	public function getBank($id=''){
            
			if($id!=''){
			
				$sql = "SELECT * FROM ac_banks WHERE id = ".$id;			
				$query = $this->db->query($sql);
				
				if($query== TRUE){
					return $query->result_array();
				}
				return false;
			}else{
				return false;
			}	
	}
	
	
	//Voucher Funding
	function getVoucherForFunding() {

		$sql = "SELECT * FROM ac_vouchers WHERE stat = 1 AND status = 'For funding approval' ORDER BY voucher_date DESC";
		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	
	
	function getVoucherForApproval() {

		//$sql = "SELECT * FROM ac_vouchers WHERE stat = 1 AND status = 'For voucher approval' ORDER BY voucher_date DESC";
		$sql = "SELECT * FROM ac_vouchers WHERE stat = 1 AND status = 'For voucher approval' AND transaction_type = 'Cash Request' ORDER BY voucher_date DESC";
		
		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }

		return $details->result_array();
	}
	function getVoucherForRelease() {
		
			$sql = "SELECT * FROM ac_vouchers WHERE status = 'For releasing' ORDER BY voucher_date DESC";
			//$sql = "SELECT * FROM ac_vouchers WHERE stat = 1 ORDER BY voucher_date DESC";
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }

			$ret =  $details->result_array();	

			return $ret;

	}
	function getAvailableBalance($bankid) {
		
			//$sql = "SELECT * FROM ac_vouchers WHERE status = 'For releasing' ORDER BY voucher_date DESC";
			$sql = "SELECT * FROM ac_vouchers WHERE stat = 1 ORDER BY voucher_date DESC";
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }

			$ret =  $details->result_array();	

			return $ret;

	}	
	
	//WTAX/VAT/EWT Functions
	public function computeWtax($tax,$amount)	{
		
		$wtax = $tax * ($amount/100);			
		return $wtax;
		
	}
	public function computeVat($vat,$amount)	{
		
		$vtax = $vat * ($amount/100);					
		return $vtax;
		
	}
		
	//End of WTAX/VAT/EWT Functions

   //FUND FUNCTIONS
   public function getPettyCashFund($location=''){
   
   		
   		$total = 0;
		
   		$sql = "SELECT sum(amount) as totalPettyCash FROM `ac_cash_fund` WHERE type ='Petty Cash' and location = '".$location."'";		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		$pettyCash = $res[0]['totalPettyCash'];
		
		$sql1 = "SELECT sum(amount) as pettyOut FROM `ac_cash_advances` WHERE type='Petty Cash' and location = '".$location."'";		
		$query1 = $this->db->query($sql1);
		$res1 = $query1->result_array();	
		$pettyOut = $res1[0]['pettyOut'];
		
		$total = $pettyCash - $pettyOut;
		
		if($res1 == TRUE){
			return $total;
		}
   
   }
   public function getRevolvingFund($location=''){
   		
   		$sql = "SELECT sum(amount) as total FROM `ac_cash_fund` WHERE type ='Revolving Fund' and location = '".$location."'";		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		$total = $res[0]['total'];
		
		$sql1 = "SELECT sum(amount) as revolveOut FROM `ac_cash_advances` WHERE type='Revolving Fund' and location = '".$location."'";	
		$query1 = $this->db->query($sql1);
		$res1 = $query1->result_array();	
		$revolveOut = $res1[0]['revolveOut'];
		
		$t = $total - $revolveOut;
		
		if($res1 == TRUE){
			return $t;
		}
   
   }      
    public function getOperationalFund($location=''){
   		
   		$sql = "SELECT sum(amount) as total FROM `ac_cash_fund` WHERE type ='Operational Fund' and location = '".$location."'";	
		$query = $this->db->query($sql);
		$res = $query->result_array();
		$total = $res[0]['total'];
		
		$sql1 = "SELECT sum(amount) as operationOut FROM `ac_cash_advances` WHERE type='Operational Fund' and location = '".$location."'";	
		$query1 = $this->db->query($sql1);
		$res1 = $query1->result_array();	
		$operationOut = $res1[0]['operationOut'];
		
		$t = $total - $operationOut;
		
		if($res1 == TRUE){
			return $t;
		}
   
   }  
   public function getReturnedAmount($id=''){
   		
   		$ret = false;
		$sql = "SELECT c.amount as returned_amount
				FROM ac_cash_fund as c
				INNER JOIN ac_ca_liquidation as l
				ON c.ref_number=l.ca_id
				WHERE ref_number =".$id;		
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		
		if(!$query)	{ return false; }
		$ret =  $query->result_array();
		
		return $ret;
   
   }
   
   //END FUND FUNCTIONS
   
   //BANK RECON
   public function getIssuedChecks() {
   	
		$sql = "SELECT v.id, bank_id, account_no, name,  check_num, amount, pay_to, transaction_type, 
					voucher_number, voucher_date
				FROM `ac_vouchers` AS v
				INNER JOIN ac_banks AS b ON v.bank_id=b.id
				ORDER BY bank_id, voucher_date";
   
   		$details	=	$this->db->query($sql);
		if(!$details){ return false; }
		$ret =  $details->result_array();	
		return $ret;
		
   } 
   
   //END BANK RECON
	
	// Beginning of Accounting Management

	public function getCashAdvances($location='') { //show all table id
	
		//NOTE: this function requires user location to work properly
			$ret	=	NULL;
			
			if($location != ''){
				$sql 	= "SELECT * FROM ac_cash_advances WHERE stat = 1 AND location='".$location."' ORDER BY date_requested DESC";
				//var_dump($sql);
				$cash	=	$this->db->query($sql);		

				if($cash) {
					if($cash->row()) {
						$ret	=	$cash->result_array();
					}
				}
			}else{
				//if location is not available then log the use out
				//header('Location:' . HTTP_PATH . 'home/logout');
        		//die();
				
			}	
		return $ret;
	}
	
	public function getCashVoucher_ForFunding() { //not sure where this functionis used? Make your function label accurate.
		$ret		=	null;
			$cash	=	$this->db->query("SELECT * FROM ac_vouchers where status = 'For funding approval' AND transaction_type = 'Cash Request'");
			
		if($cash) {
			if($cash->row()) {
				$ret	=	$cash->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	
	public function getCashRequest_ForVoucher($location='') { 
			$ret	=	NULL;
	
			if($location != ''){
				$loc = " AND location ='".$location."'";
			}else{
				$loc = '';
			}
			$sql = "SELECT * FROM ac_cash_advances WHERE stat= 1 AND status != 'Released' ".$loc." ORDER BY date_requested DESC";			
			
			$cash	=	$this->db->query($sql);
			
		if($cash) {
			if($cash->row()) {
				$ret	=	$cash->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	
	public function getCashfunding() { //show all table id
		$ret		=	null;
			$cash	=	$this->db->query("SELECT * FROM ac_cash_advances where status = 'For funding approval'");

		if($cash) {
			if($cash->row()) {
				$ret	=	$cash->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	
	public function getforCash() { //show all table id
		$ret		=	null;
			$cash	=	$this->db->query("SELECT * FROM ac_cash_advances where status = 'For Approval'");

		if($cash) {
			if($cash->row()) {
				$ret	=	$cash->result_array();
			}
		}
		else {
			$ret	=	false;
		}
		return $ret;
	}
	
	public function getCashAdvance($id=''){ //show table per id

		if($id!=''){
			$sql = "SELECT * FROM ac_cash_advances WHERE id =".$id;
			//var_dump($sql);exit;
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
	}
	
	
	public function getCashVoucher(){ 


			$sql = "SELECT * FROM ac_cash_advances WHERE stat = 1";
			//var_dump($sql);exit;
			$query = $this->db->query($sql);

		if(!$query){ return false; }

		return $query->result_array();
	}
	
	
	public function getCashAdvanceByVoucherId($id=''){ 

		if($id!=''){
			$sql = "SELECT * FROM ac_cash_advances WHERE voucher_id =".$id;
			//var_dump($sql);exit;
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
	}
	
	
	public function getCashLiquidation($id=''){ 

		if($id!=''){
			$sql = "SELECT * FROM ac_ca_liquidation WHERE ca_id =".$id;
			//var_dump($sql);exit;
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
	}
	public function getTotalLiquidation($id=''){ 

		if($id!=''){
			$sql = "SELECT sum(amount) as total FROM ac_ca_liquidation WHERE ca_id =".$id;
			//var_dump($sql);exit;
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
	}

	

	public function getCashLiquidationReport($type=''){ //show table per id

		if($type!=''){
			/*
			switch ($type){
			
				case 'Petty Cash':
						$like = "SELECT * FROM ac_ca_liquidation WHERE type =".$id;
				break;
				
				case 'Revolving Fund':
				
				break;
				
				case 'Operations Fund':
				
				break;				
			
			}*/
			
			$sql = "SELECT l.date_liquidated, particular, l.amount, receipt_no, e.name as expense_type,  requested_by, l.department as used_for
					FROM `ac_ca_liquidation` AS l
					INNER JOIN ac_cash_advances AS c ON l.ca_id=c.id
					INNER JOIN ac_expense_classifications AS e ON e.id=l.type
					WHERE c.type LIKE '".$type."'";
			
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
		
	}


	public function getExpenseType($id=''){ 

		$ret = false;
		if($id!=''){
			$sql = "SELECT * FROM ac_expense_classifications WHERE id =".$id;
			//var_dump($sql);exit;
			$query = $this->db->query($sql);
			if(!$query){ return false; }
			$ret= $query->result_array();
		}
		

		return $ret;
	}

	public function getBanksFromCOA($cid='') {

			$sql = "SELECT a.id, a.code, b.name, b.account_name FROM ac_banks AS b INNER JOIN ac_accounts as a ON a.code=b.account_code WHERE a.id=".$cid;
			
			$details				=	$this->db->query($sql);
			if(!$details) 			{ return false; }

			$ret =  $details->result_array();

			return $ret;

	}

	public function getReleasedChecks($date_from,$date_to,$company=NULL,$payee_type=NULL,$payee=NULL){
		$append_company = "";
		$append_payee = "";
		if($company!=''){
			$append_company = " AND company_id=".$company;
		}
		if($payee_type=='supplier'){
			$append_payee = " AND payee_type='Supplier' AND payee=".$payee;
		}
		if($payee_type=='employee'){
			$append_payee = " AND payee_type='Employee' AND payee=".$payee;
		}
		$sql = "SELECT * FROM ac_vouchers WHERE status='Paid' AND released_date BETWEEN '".$date_from."' AND '".$date_to."'".$append_company.$append_payee;
		//$sql = "SELECT * FROM ac_vouchers WHERE status='Paid'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$row) {
				$company =	$this->Abas->getCompany($row->company_id);
				$result[$ctr]->company = $company->name;
				
				

				$bank		=	$this->Finance_model->getBanksFromCOA($row->bank_id);
				$result[$ctr]->bank	=	$bank[0]['name'];

				$created_by		=	$this->Abas->getEmployee($row->created_by);
				$result[$ctr]->created_by	=	$created_by['full_name'];
		
				$verified_by		=	$this->Abas->getUser($row->verified_by);
				$result[$ctr]->verfied_by	=	$verified_by['full_name'];

				$approved_by		=	$this->Abas->getUser($row->approved_by);
				$result[$ctr]->approved_by	=	$approved_by['full_name'];
			
			}
		}else{
			$result = NULL;
		}
		return $result;
	}


// Ending of Accounting Management
}


?>
