<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Collection_model extends CI_Model {

	public function getBankByCompany($company_id){
		$result	=	null;

		if(is_numeric($company_id)) {
			$query	=	$this->db->query("SELECT * FROM ac_banks WHERE company_id=".$company_id);
			if($query) {
				$result = $query->result();
			}
		}	
		return $result;	
	}

	public function getPayment($id){

		$sql = "SELECT * FROM payments WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = (array)$query->row();

			$received_by	=	$this->Abas->getUser($result['received_by']);
			$result['received_by'] = $received_by['full_name'];
			//$result['received_on'] = date("j F Y h:i A", strtotime($result['received_on']));

			$company	=	$this->Abas->getCompany($result['company_id']);
			$result['company_name'] = $company->name;
			$result['company_address'] = $company->address;
			$result['company_contact'] = $company->telephone_no;
			$result['company_tin'] = $company->company_tin;


		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getPaymentsBySOA($soa_id){

		$sql = "SELECT * FROM payments WHERE soa_id=".$soa_id. " AND status<>'Cancelled'";
		$query = $this->db->query($sql);

		if($query){
			$results = $query->result();
		}else{
			$results = NULL;
		}
		return $results;
	}

	public function getPaymentsToday($user_id,$company_id,$date=NULL){

		if($date==NULL){
			$date_now = date('Y-m-d');
		}else{
			$date_now = $date;
		}

		$sql = "SELECT * FROM payments WHERE DATE(received_on)='".$date_now."' AND received_by=".$user_id." AND company_id=".$company_id." AND status<>'Cancelled'"; 
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getCompaniesPerPaymentsToday($user_id,$date=NULL){
		
		if($date==NULL){
			$date_now = date('Y-m-d');
		}else{
			$date_now = $date;
		}

		$sql = "SELECT company_id FROM payments WHERE DATE(received_on)='".$date_now."' AND received_by=".$user_id ." AND status<>'Cancelled' GROUP BY company_id"; 
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getCashBreakdown($payment_id){

		$sql = "SELECT * FROM payments_cash_breakdown WHERE payment_id=".$payment_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getCheckBreakdown($payment_id){

		$sql = "SELECT * FROM payments_check_breakdown WHERE payment_id=".$payment_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getBankTransferBreakdown($payment_id){

		$sql = "SELECT * FROM payments_bank_transfer_breakdown WHERE payment_id=".$payment_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getBankAccountbyID($id){

		$sql = "SELECT * FROM ac_banks WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->row();
			$result->complete_account = $result->account_name . " - " . $result->name . " (" . $result->account_no . ")";
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function depositCashBreakdown($update_fields,$id){

		
		$sql = "UPDATE payments_cash_breakdown SET 
		        deposit_reference_number='".$update_fields['deposit_reference_number']."',
		        deposited_on='".$update_fields['deposit_date']."',
		        deposited_by='".$update_fields['deposited_by']."',
		        deposited_account='".$update_fields['deposited_account']."',
		        status='Deposited' WHERE payment_id=".$id;

		 $query = $this->db->query($sql);

		 if($query){
		 	$result = TRUE;
		 }else{
		 	$result = FALSE;
		 }
		 
		 return $result;

	}

	public function depositCheckBreakdown($update_fields,$id){

		if($update_fields['deposit_reference_number']!="" && $update_fields['deposit_date']!="" && $update_fields['deposited_by']!="" && $update_fields['deposited_account']!=""){
			$status = "Deposited";
		}else{
			$status = "For Deposit";
		}
		
		$sql = "UPDATE payments_check_breakdown SET 
		        deposit_reference_number='".$update_fields['deposit_reference_number']."',
		        deposited_on='".$update_fields['deposit_date']."',
		        deposited_by='".$update_fields['deposited_by']."',
		        deposited_account='".$update_fields['deposited_account']."',
		        status='".$status."' WHERE id=".$id;

		 $query = $this->db->query($sql);

		 if($query){
		 	$result = TRUE;
		 }else{
		 	$result = FALSE;
		 }
		 
		 return $result;

	}

	public function verifyCheckBreakdown($id){

		//verifies and flags if all Check payments are not yet deposited 
		$flag = FALSE;

		$sql = "SELECT * FROM payments_check_breakdown WHERE payment_id=".$id;
		$query = $this->db->query($sql);

		 if($query){

		 	$result = $query->result();
		 	//if more than 1 checks then validate if all are deposited if only one then no action
		 	if(count($result)>1){

			 	foreach($result as $row){
			 		if($row->status=="For Deposit" || $row->status=="Post-dated"){
			 			$flag = TRUE;
			 			break;
			 		}
			 	}
		 	}
	
		 }else{
		 	$flag  = NULL;
		 }
		 
		 return $flag;

	}

	public function getAcknowledgementReceipt($id){

		$sql = "SELECT * FROM acknowledgement_receipts WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = (array)$query->row();

			$received_by	=	$this->Abas->getUser($result['received_by']);
			$result['full_name'] = $received_by['full_name'];

			$company	=	$this->Abas->getCompany($result['company_id']);
			$result['company_name'] = $company->name;
			$result['company_address'] = $company->address;
			$result['company_contact'] = $company->telephone_no;

		}else{
			$result = NULL;
		}

		return $result;

	}


	public function getORNumber($id){

		$sql = "SELECT control_number FROM payments_official_receipts WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}

		return $result;

	}

	public function getOfficialReceipts($payment_id){

		$sql = "SELECT control_number FROM payments_official_receipts WHERE payment_id=".$payment_id;
		$query = $this->db->query($sql);
		
		if($query){
			$result = $query->result();

		}else{
			$result = NULL;
		}
		return $result;

	}


	public function getARNumber($id){

		$sql = "SELECT control_number FROM payments_acknowledgement_receipts WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}

		return $result;

	}

	public function getAcknowledgementReceipts($payment_id){

		$sql = "SELECT control_number FROM payments_acknowledgement_receipts WHERE payment_id=".$payment_id;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();

		}else{
			$result = NULL;
		}
		return $result;
	}

	public function setOfficialReceipt($company,$payment_id){
		$control_number = $this->Abas->getNextSerialNumber('payments_official_receipts',$company);
		$date_now = date('Y-m-d');
		$sql = "INSERT INTO payments_official_receipts (control_number,company_id,payment_id,issued_date) VALUES (".$control_number.",".$company.",".$payment_id.",'".$date_now."')";

		$query = $this->db->query($sql);

		if($query){
			$result = TRUE;
		}else{
			$result = FALSE;
		}
		return $result;
	}

	public function setAcknowledgementReceipt($company,$payment_id){
		$control_number = $this->Abas->getNextSerialNumber('payments_acknowledgement_receipts',$company);
		$date_now = date('Y-m-d');
		$sql = "INSERT INTO payments_acknowledgement_receipts (control_number,company_id,payment_id,issued_date) VALUES (".$control_number.",".$company.",".$payment_id.",'".$date_now."')";

		$query = $this->db->query($sql);

		if($query){
			$result = TRUE;
		}else{
			$result = FALSE;
		}
		return $result;
	}

	public function getOfficialReceiptByCheckNumber($check_number,$bank_name){

		$sql = "SELECT official_receipt_id FROM payments_check_breakdown WHERE check_number='".$check_number. "' AND bank_name='".$bank_name."'";
		$query = $this->db->query($sql);

		if($query){
			$result = $query->row();
		}else{
			$result = NULL;
		}
		return $result;
	}

	public function setORCheckBreakdown($payment_id,$or_id){

		$date_now = date('Y-m-d');

		$sql = "UPDATE payments_check_breakdown SET official_receipt_id =".$or_id.", status='For Deposit' WHERE status='Post-dated' AND acknowledgement_receipt_id<>0 AND check_date<='".$date_now."' AND payment_id=".$payment_id;

		$query = $this->db->query($sql);

		if($query){
			$result = TRUE;
		}else{
			$result = FALSE;
		}

		return $result;

	}

	public function setARCheckBreakdown($payment_id){

		$date_now = date('Y-m-d');

		$sql = "UPDATE payments_check_breakdown SET status='For Deposit' WHERE status='Post-dated' AND acknowledgement_receipt_id<>0 AND check_date<='".$date_now."' AND payment_id=".$payment_id;

		$query = $this->db->query($sql);

		if($query){
			$result = TRUE;
		}else{
			$result = FALSE;
		}

		return $result;

	}

	public function getDCCRRs($created_by=NULL,$date_now,$company=NULL){

		if($created_by==NULL){
			$sql = "SELECT * FROM payments_daily_report WHERE created_on='".$date_now."'";
		}else{
			if($company==NULL){
				$sql = "SELECT * FROM payments_daily_report WHERE created_on='".$date_now."' AND created_by=".$created_by;
			}else{
				$sql = "SELECT * FROM payments_daily_report WHERE company_id=".$company." AND created_on='".$date_now."' AND created_by=".$created_by;
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

	public function getDCCRR($id){

		$sql = "SELECT * FROM payments_daily_report WHERE id=".$id;
		$query = $this->db->query($sql);

		if($query){
			$result = (array)$query->row();

			$user	=	$this->Abas->getUser($result['created_by']);

			$result['full_name'] = $user['full_name'];

			$company	=	$this->Abas->getCompany($result['company_id']);
			$result['company_name'] = $company->name;
			$result['company_address'] = $company->address;
			$result['company_contact'] = $company->telephone_no;

		}else{
			$result = NULL;
		}

		return $result;

	}

	public function getDCCRRDetails($id,$group=false){

		if($group==TRUE){
			$sql = "SELECT * FROM payments_daily_report_details WHERE daily_report_id=".$id. " GROUP BY payment_id";
		}else{
			$sql = "SELECT * FROM payments_daily_report_details WHERE daily_report_id=".$id;
		}
		
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();

		}else{
			$result = NULL;
		}

		return $result;
	}


	public function getCompaniesPerPayments($user_id,$date=NULL){
		
		if($date==NULL){
			$date_now = date('Y-m-d');
		}else{
			$date_now = $date;
		}

		$sql = "SELECT company_id FROM payments WHERE DATE(received_on)='".$date_now."' AND received_by=".$user_id ." AND status<>'Cancelled' GROUP BY company_id";
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getPaymentsBy($user_id,$company_id,$date=NULL){

		if($date==NULL){
			$date_now = date('Y-m-d');

			$sql = "SELECT * FROM payments WHERE DATE(received_on)='".$date_now."' AND received_by=".$user_id." AND company_id=".$company_id. " AND status<>'Cancelled'";
		}else{
			$date_now = $date;

			$sql = "SELECT * FROM payments WHERE DATE(received_on)='".$date_now."' AND received_by=".$user_id." AND company_id=".$company_id. " AND status<>'Cancelled' UNION SELECT * FROM payments WHERE DATE(received_on)<'".$date_now."' AND received_by=".$user_id." AND company_id=".$company_id. " AND status='For Deposit'"; 
			
		}
		
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;
	}

	public function getDepositsBy($user_id,$company_id,$date=NULL){

		if($date==NULL){
			$date_now = date('Y-m-d');
		}else{
			$date_now = $date;	
		}

		$sql = "SELECT * FROM payments WHERE company_id=".$company_id." AND DATE(received_on)<='".$date_now."' AND received_by=".$user_id;
		
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}

		return $result;

	}

	public function getDCCRRTotalCollections($company_id,$user_id,$date=NULL){

		$total_collections = 0;

		$sql = "SELECT * FROM payments WHERE company_id=".$company_id." AND DATE(received_on)='".$date."' AND received_by=".$user_id." AND status<>'Cancelled'";
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
			foreach($result as $row){

				if($row->mode_of_collection=='Cash'){
					$breakdown = $this->Collection_model->getCashBreakdown($row->id);

					foreach($breakdown as $row2){
						if(date("Y-m-d", strtotime($row->received_on))==$date){
							$total_collections = $total_collections + $row2->amount;
						}
		        	}
				}

				if($row->mode_of_collection=='Check'){
					$breakdown = $this->Collection_model->getCheckBreakdown($row->id);
		        				
		        	foreach($breakdown as $row2){
		        		if(date("Y-m-d", strtotime($row->received_on))==$date){
		        			$total_collections = $total_collections + $row2->amount;
						}
		        	}
				}

				if($row->mode_of_collection=='Bank Deposit/Transfer'){
					$breakdown = $this->Collection_model->getBankTransferBreakdown($row->id);
		        				
		        	foreach($breakdown as $row2){
		        		if(date("Y-m-d", strtotime($row->received_on))==$date){
		        			$total_collections = $total_collections + $row2->amount;
						}
		        	}
				}
				
			}
		}

		return $total_collections;

	}

	public function getDCCRRTotalDeposits($company_id,$user_id,$date=NULL){

		$total_deposits = 0;

		$sql = "SELECT * FROM payments WHERE company_id=".$company_id." AND DATE(received_on)<='".$date."' AND received_by=".$user_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result();
			foreach($result as $row){

				if($row->mode_of_collection=='Cash'){
					$breakdown = $this->Collection_model->getCashBreakdown($row->id);

					foreach($breakdown as $row2){
						if(date("Y-m-d", strtotime($row2->deposited_on))==$date && $row2->status=='Deposited'){
							$total_deposits = $total_deposits + $row2->amount;
						}
		        	}
				}

				if($row->mode_of_collection=='Check'){
					$breakdown = $this->Collection_model->getCheckBreakdown($row->id);
		        				
		        	foreach($breakdown as $row2){
		        		if(date("Y-m-d", strtotime($row2->deposited_on))==$date && $row2->status=='Deposited'){
		        			$total_deposits = $total_deposits + $row2->amount;
						}
		        	}
				}

				if($row->mode_of_collection=='Bank Deposit/Transfer'){
					$breakdown = $this->Collection_model->getBankTransferBreakdown($row->id);
		        				
		        	foreach($breakdown as $row2){
		        		if(date("Y-m-d", strtotime($row2->deposited_on))==$date && $row2->status=='Deposited'){
		        			$total_deposits = $total_deposits + $row2->amount;
						}
		        	}
				}
				
			}
		}

		return $total_deposits;

	}

	public function getDCCRRBeginningBalance_old($company_id,$user_id,$control_number=NULL,$date=NULL){

		$beginning_balance = 0;
		
	
		$sql = "SELECT * FROM payments WHERE company_id=".$company_id." AND DATE(received_on)<'".$date."' AND received_by=".$user_id." AND status='For Deposit'";
		$query = $this->db->query($sql);

		$check_amount = 0;
		$cash_amount = 0;

		if($query){

			$result = $query->result();
			if($result){

				foreach($result as $row){

					$check_breakdown = $this->getCheckBreakdown($row->id);

					foreach($check_breakdown as $check_row){
						
							$check_amount = $check_amount + $check_row->amount;
						
					}

					$cash_breakdown =  $this->getCashBreakdown($row->id);

					foreach($cash_breakdown as $cash_row){
						$cash_amount = $cash_amount + $cash_row->amount;
					}

				}

				$beginning_balance =  $cash_amount + $check_amount;

				
			}
		}

		return $beginning_balance;
	}

	public function getDCCRRBeginningBalance($company_id,$user_id){

		$beginning_balance = 0;

		$sql = "SELECT ending_balance FROM payments_daily_report WHERE company_id=".$company_id." AND status='Active' AND created_by=".$user_id." ORDER BY control_number ASC LIMIT 1";

		$query = $this->db->query($sql);


		if($query){
			$result = $query->row();
			
			if($result){
				$beginning_balance = $result->ending_balance;	
			}
		}

		return $beginning_balance;
	}

	public function getDCCRREndingBalance($id){

		$ending_balance = 0;

		$data['report'] = $this->getDCCRR($id);

		$created_on = $data['report']['created_on'];
		$created_by = $data['report']['created_by'];
		$company_id = $data['report']['company_id'];

		$payments = $this->getPaymentsBy($created_by,$company_id,$created_on);

		if($payments){
			
			foreach($payments as $row){
				$ending_balance = $ending_balance + $row->net_amount; 
			}

		}

		return $ending_balance;
	}

}

/* End of file Collection_model */
/* Location: ./application/models/Collection_model */

?>