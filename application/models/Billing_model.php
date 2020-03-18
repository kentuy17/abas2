<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {

	//Statement of Account/////////////////////////////////////////////////////////////////////////////////
	public function getVesselByCompany($company_id){
		$result = null;
		$sql = "SELECT id, name FROM vessels WHERE status='Active' AND company=".$company_id." ORDER BY name ASC";
		$query	=	$this->db->query($sql);

		if($query){
			$result = $query->result();
		}
		return $result;
	}
	public function getStatementOfAccount($id) {
		$result	=	null;
		if(is_numeric($id)) {
			$query	=	$this->db->query("SELECT * FROM statement_of_accounts WHERE id=".$id);
			if($query!=false) {
				if($query->row()) {
					$result=	(array)$query->row();
					$result['details']	=	array();
					$result['company']	=	$this->Abas->getCompany($result['company_id']);
					$result['client']	=	$this->Abas->getClient($result['client_id']);

					if($result['created_by']) {
						$result['created_by']	=	$this->Abas->getUser($result['created_by']);
					}
					if($result['created_on']) {
						$result['created_on']	=	$result['created_on'];//date("j F Y h:i A", strtotime($result['created_on']));
					}

					if($result['contract_id']){
						$contract	= $this->Abas->getContract($result['contract_id']);
						$result['contract_reference_no'] = $contract['reference_no'];
					}

					$out_turn_type		=	$this->db->query("SELECT * FROM statement_of_account_cargo_out_turn WHERE soa_id=".$id." ORDER BY warehouse ASC, trucking_company ASC,transaction ASC,number_of_moves ASC");
					$general_type	=	$this->db->query("SELECT * FROM statement_of_account_details WHERE soa_id=".$id." ORDER BY sorting ASC");

					if($out_turn_type->row() && !$general_type->row()) { // NFA SOA
						$result['details']	=	$out_turn_type->result_array();
					}
					elseif(!$out_turn_type->row() && $general_type->row()) { // Avega SOA
						$result['details']	=	$general_type->result_array();
					}
					elseif(!$out_turn_type->row() && !$general_type->row()) { // No details found
						$result['details']	=	"";
					}


				}
			}
		}
		return $result;
	}
	public function getStatementsOfAccount() {
		$result					=	null;
		$sql = "SELECT * FROM statement_of_accounts WHERE status='Active'";
		$requests				=	$this->db->query($sql);
		if(!$requests) 			{ return null; }
		if(!$requests->row())	{ return null; }
		$requests				=	$requests->result_array();
		if(!empty($requests)) {
			foreach($requests as $ctr=>$request) {
				$requests[$ctr]	=	$this->Purchasing_model->getRequest($request['id']);
			}
		}
		$result					=	$requests;
		return $result;
	}
	public function getStatementOfAccountsByStatus($status) {
		$sql = "SELECT * FROM statement_of_accounts WHERE status='".$status."'";
		$query =	$this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getServices($category=null){
		$result = null;

		if($category==null){
			$sql	=	"SELECT * FROM services WHERE stat=1;";
			$query	=	$this->db->query($sql);

			if($query){
				$result = $query->result();
			}
		}
		else{
			$sql	=	"SELECT * FROM services WHERE category='".$category."' WHERE stat=1;";

			$query	=	$this->db->query($sql);

			if($query){
				$result = $query->row();
			}
		}
		
		return $result;
	}
	public function getSOAAging($date_sent,$days_limit){
		$result = null;

		$date_sent_plus_days_limit = date("Y-m-d",strtotime($date_sent.$days_limit));
		$date_now = date("Y-m-d");

		if($date_now > $date_sent_plus_days_limit){

			$date_aging1	=	new DateTime ($date_sent_plus_days_limit);
			$date_aging2	=	new DateTime ($date_now);

			$date_diff = $date_aging2->diff($date_aging1);
			$result['aging'] = $date_diff->format('%a day(s)');
			$result['num_days'] = $date_diff->format('%a'); 

		}else{
			$result['aging'] = "-";
			$result['num_days'] = "-"; 
		}

		$result['due'] = date("j F Y",strtotime($date_sent_plus_days_limit));

		return $result;

	}
	public function getSOACOR($soa_id){

		$sql = "SELECT * FROM statement_of_account_cargo_out_turn WHERE soa_id=".$soa_id;
		$query = $this->db->query($sql);

		if($query){
			$result = $query->result_array();
		}else{
			$result = NULL;
		}

		return $result;
	}
	public function setSOABreakdown($file,$soa_id,$soa_detail_id){

		//imports the uploaded SOA breakdown file into database 
		require_once WPATH.'assets/phpexcel/Classes/PHPExcel/IOFactory.php';

		$inputFileName= WPATH.'/assets/uploads/' . $file;
		
		//  Read Excel file
		try{
		    $inputFileType=PHPExcel_IOFactory::identify($inputFileName);
		    $objReader=PHPExcel_IOFactory::createReader($inputFileType);
		    $objPHPExcel=$objReader->load($inputFileName);
		}
		catch(Exception $e){
		    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		//  Get worksheet dimensions
		$sheet=$objPHPExcel->getSheet(0); 
		$headers=$objPHPExcel->getActiveSheet()->toArray("A5");
		$highestRow=$sheet->getHighestRow(); 
		$highestColumn=$sheet->getHighestColumn();
		//$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

		// Check if the correct template is being imported
		$correct_file = $sheet->getCell('F1')->getValue();
		$total_bags = $sheet->getCell('H1')->getOldCalculatedValue();
		$total_weight = $sheet->getCell('H2')->getOldCalculatedValue();

		if($correct_file=="2W3211e9x883aB91Np470019FJX" && $total_bags>0 && $total_weight>0){

			//  Loop through each row of the worksheet in turn and insert it on db
			for($row=5;$row<=$highestRow;$row++){ 
			   
			    $rowData=$sheet->rangeToArray("A" . $row . ":" . $highestColumn . $row,null,true,false);

    			$insert['soa_id'] = $soa_id;
    			$insert['soa_nfa_detail_id'] = $soa_detail_id;
    			//convert to unix date;
    			$insert['date_of_transaction'] = gmdate("Y-m-d", ($rowData[0][0]  - 25569) * 86400);
    			$insert['wsi_number'] = $rowData[0][1];
    			$insert['plate_number'] = $rowData[0][2];
    			$insert['trucking_company'] = $rowData[0][3];
    			$insert['variety'] = $rowData[0][4];
    			$insert['transaction'] = $rowData[0][5];
    			$insert['number_of_bags'] = $rowData[0][6];
    			$insert['net_weight'] = $rowData[0][7];

    			$this->Mmm->dbInsert("statement_of_account_nfa_breakdown",$insert,"Imported NFA Breakdown for SOA Detail ID#" . $soa_detail_id);
			}
			return true;

		}else{
			return false;
		}

	}
	//End of Statement of Account//////////////////////////////////////////////////////////////////////////

	//Payments////////////////////////////////////////////////////////////////////////////////////////////
	public function getSOAAmount($type,$id){
			$result = null;

			$total_charges = 0;
			$total_payments = 0;

			$grandtotal = 0;
			$total_tax = 0;
			$grandtotal_less_tax = 0;
			$grandtotal_add_tax = 0;
			$rateperbags = 0;
			$moves = 0;
			$amount = 0;
			$bagqty = 0;

			$vat_12_percent = 0;
			$vat_amount = 0;
			$vat_5_percent = 0;
			$wtax_15_percent = 0;
			$wtax_2_percent = 0;
			$wtax_1_percent = 0;

			$soa =	$this->getStatementOfAccount($id);

			foreach($soa['details'] as $detail){

				if($type=="With Out-Turn Summary"){

					if($detail['empty_sacks']==true){
			   			$empty_sacks_qty = ($detail['quantity']*0.09)/1000;
			   			$empty_sacks_amount =  $empty_sacks_qty*$detail['rate'];
			   			$xamount = $detail['amount'] - $empty_sacks_amount;
			   		}else{


				   		if($detail['tail_end_handling']==true){
				   			if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
		   						$bagqty = $bagqty + ($detail['total_weight']/50);
		   					}else{
		   						$bagqty = $bagqty + $detail['quantity'];
		   					}
		   				}

			   			$xamount = $detail['amount'];
			   		}

					$grandtotal = $grandtotal + $xamount;
				}
				elseif($type=="General"){
						$total_payments = $total_payments + $detail['payment'];
						$total_charges = $total_charges + $detail['charges'];
				}

			}


			if($type=="General"){
				$grandtotal = $total_charges - $total_payments;
			}elseif($type=="With Out-Turn Summary"){
				if($detail['tail_end_handling']==true){
					$grandtotal = $grandtotal + ($bagqty*3.68);
				}
			}


			if($soa['vat_12_percent']==1){
				if($soa['add_tax']!=1){
					$vat_12_percent =  ($grandtotal/1.12)*0.12;
					$vat_amount = ($grandtotal/1.12);
				}elseif($soa['add_tax']==1){
					$vat_12_percent =  ($grandtotal*0.12);
				}
			}

			if($soa['vat_5_percent']==1){

				if($soa['vat_12_percent']==1  && $soa['add_tax']!=1){
					$vat_amount = ($grandtotal/1.12);
					$vat_5_percent = ($vat_amount*0.05);
				}else{
					$vat_5_percent = ($grandtotal*0.05);
				}
							
			}
			if($soa['wtax_15_percent']==1){

				if($soa['vat_12_percent']==1  && $soa['add_tax']!=1){
					$vat_amount = ($grandtotal/1.12);
					$wtax_15_percent = ($vat_amount*0.15);
				}else{
					$wtax_15_percent = ($grandtotal*0.15);
				}

			}
			if($soa['wtax_2_percent']==1){

				if($soa['vat_12_percent']==1  && $soa['add_tax']!=1){
					$vat_amount = ($grandtotal/1.12);
					$wtax_2_percent = ($vat_amount*0.02);
				}else{
					$wtax_2_percent = ($grandtotal*0.02);
				}

			}
			if($soa['wtax_1_percent']==1){

				if($soa['vat_12_percent']==1 && $soa['add_tax']!=1){
					$vat_amount = ($grandtotal/1.12);
					$wtax_1_percent = ($vat_amount*0.01);
				}else{
					$wtax_1_percent = ($grandtotal*0.01);
				}

			}

			
			if($soa['add_tax']!=1){
				if($soa['vat_5_percent']==1 || $soa['wtax_15_percent']==1 || $soa['wtax_2_percent']==1 || $soa['wtax_1_percent']==1){
					$total_tax = $vat_5_percent + $wtax_15_percent + $wtax_2_percent + $wtax_1_percent;
				}
			}elseif($soa['add_tax']==1){
				$total_tax = $vat_12_percent + $vat_5_percent + $wtax_15_percent + $wtax_2_percent + $wtax_1_percent;
			}
							
			
			$result['vat_12_percent'] = $vat_12_percent;
			$result['vat_amount'] = $vat_amount;
			$result['vat_5_percent'] = $vat_5_percent;
			$result['wtax_15_percent'] = $wtax_15_percent;
			$result['wtax_2_percent'] = $wtax_2_percent;
			$result['wtax_1_percent'] = $wtax_1_percent;
			$result['total_tax'] = $total_tax;
			$result['grandtotal'] = $grandtotal;
			$result['grandtotal_less_tax'] = $grandtotal-$total_tax;
			$result['grandtotal_add_tax'] = $grandtotal+$total_tax;

			if($soa['add_tax']!=1){
				$result['grandtotal_tax'] = $result['grandtotal_less_tax'];
			}elseif($soa['add_tax']==1){
				$result['grandtotal_tax'] = $result['grandtotal_add_tax'];
			}

			unset($soa);
			return $result;

	}
	public function getSOAPayments($id){

		$sql = "SELECT SUM(net_amount) as total_payments FROM payments WHERE soa_id=".$id. " AND status<>'Cancelled'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->row();

			$soa_type = $this->getStatementOfAccount($id);
			$soa_amount = $this->getSOAAmount($soa_type['type'],$id);

			$result->remaining_balance = number_format($soa_amount['grandtotal_tax'] - $result->total_payments,2,'.','');

		}else{
			$result = 0;
		}
		return $result;
	}
	public function getSOAPayment($soa_id){
		$result	=	null;
		if(is_numeric($soa_id)) {
			$query	=	$this->db->query("SELECT * FROM payments WHERE soa_id=".$soa_id. " AND status<>'Cancelled'");
			if($query!=false) {
				if($query->row()) {
					$result=	(array)$query->row();
					$result['company']	=	$this->Abas->getCompany($result['company_id']);
					$result['client']	=	$this->Abas->getClient($result['payor']);
					$result['bank_account']	=	$this->Abas->getBank($result['bank_account']);
				}
			}
		}
		return $result;
	}
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

	public function setSOAComments($id,$comments){
		$comments = $this->Mmm->sanitize($comments);
		$sql = "UPDATE statement_of_accounts SET comments='".$comments."' WHERE id=".$id;
		$query	=	$this->db->query($sql);
		if($query){
			$result = TRUE;
		}else{
			$result = FALSE;
		}
		return $result;
	}

	public function getUnitOfMeasurements($keyword){


		$search = $keyword;
		$search	=	str_replace(" ", "%", $search);

		$sql = "SELECT unit FROM inventory_unit WHERE unit LIKE '%".$search."%' AND stat=1 ORDER BY unit LIMIT 0, 10";
		$query = $this->db->query($sql);

		if($query){
			$query = $query->result_array();
			$result = array();
			foreach($query as $ctr=>$item){
				$result[$ctr]['label']	=	$item['unit'];
			}
		}else{
			$result = NULL;
		}

		return $result;
	}
	
	//End of Payments//////////////////////////////////////////////////////////////////////////////////
}

/* End of file Billing_model.php */
/* Location: ./application/models/Billing_model.php */

?>