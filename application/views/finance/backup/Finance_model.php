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
	function getVoucherForRelease() {
		
			//$sql = "SELECT * FROM ac_vouchers WHERE status = 'For releasing' ORDER BY voucher_date DESC";
			$sql = "SELECT * FROM ac_vouchers WHERE stat = 1 ORDER BY voucher_date DESC";
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
	
	//End of WTAX/VAT/EWT Functions
	
	// Beginning of Accounting Management

	public function getCashAdvances() { //show all table id
		$ret		=	null;
			$cash	=	$this->db->query("SELECT * FROM ac_cash_advances");

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
			$query = $this->db->query($sql);
		}
		if(!$query){ return false; }

		return $query->result_array();
	}

// Ending of Accounting Management
}


?>
