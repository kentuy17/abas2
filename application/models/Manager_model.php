<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manager_model extends CI_Model{
	
	public function getMaterialAgingRequests() {
		$sql = "SELECT DISTINCT(request_id), i.tdate, datediff(sysdate(), i.tdate ) - 7 as aging, requisitioner, vessel_id, department_id, priority, i.remark as purpose, control_number, d.status, i.id as rid, i.added_by  
				FROM inventory_request_details as d 
				INNER JOIN inventory_requests as i ON d.request_id = i.id 
				WHERE d.status = 'For request approval' OR d.status = 'For canvass approval' OR d.status = 'For canvassing' 
				ORDER BY priority ASC, aging DESC";
		$details				=	$this->db->query($sql);
		if(!$details) 			{ return false; }
		if(!$details->row())	{ return false; }
		return	$details->result_array();
	}

	public function getVesselExpenses($vessel,$type,$date_from,$date_to){
		if($type=='Dry-docking'){
			$sql =	"SELECT * FROM ac_transaction_journal WHERE stat=1 AND posted_on>='".$date_from."' AND posted_on<='".$date_to."' AND coa_id=336 AND vessel_id=".$vessel." AND stat=1 ORDER BY transaction_id";
		}elseif($type=='Emergency Repairs'){
			$sql =	"SELECT * FROM ac_transaction_journal WHERE stat=1 AND posted_on>='".$date_from."' AND posted_on<='".$date_to."' AND coa_id>=153 AND coa_id<=160 AND vessel_id=".$vessel." ORDER BY transaction_id";
		}elseif($type=='Operational Expenses'){
			$sql =	"SELECT * FROM ac_transaction_journal INNER JOIN ac_accounts ON ac_accounts.id=ac_transaction_journal.coa_id WHERE ac_transaction_journal.stat=1 AND ac_accounts.financial_statement_code=5156 AND posted_on>='".$date_from."' AND posted_on<='".$date_to."' AND vessel_id=".$vessel." ORDER BY transaction_id";
		}		
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getJobOrderExpenseSummary($vessel,$date_from,$date_to){
		$sql = 'SELECT inventory_job_orders.*,inventory_requests.vessel_id FROM inventory_job_orders INNER JOIN inventory_requests ON inventory_job_orders.request_id = inventory_requests.id WHERE inventory_job_orders.stat=1 AND inventory_job_orders.tdate >="'.$date_from.'" AND inventory_job_orders.tdate <="'.$date_to.'" AND inventory_requests.vessel_id='.$vessel. " AND inventory_job_orders.status <>'Cancelled';";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$request) {
				$supplier = $this->Abas->getSupplier($request->supplier_id);
				$supplier_id = $supplier['id'];
				$supplier_name = $supplier['name'];
				$result[$ctr]->supplier_name = $supplier_name;
				$company = $this->Abas->getCompany($request->company_id);
				$result[$ctr]->company_name = $company->name;
				$result[$ctr]->details = $this->Purchasing_model->getJobOrderDetails($request->id);
			}
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getPurchaseOrderExpenseSummary($vessel,$date_from,$date_to){
		$sql = 'SELECT inventory_po.*,inventory_requests.vessel_id FROM inventory_po INNER JOIN inventory_requests ON inventory_po.request_id = inventory_requests.id WHERE inventory_po.stat=1 AND inventory_po.tdate >="'.$date_from.'" AND inventory_po.tdate <="'.$date_to.'" AND inventory_requests.vessel_id='.$vessel. " AND inventory_po.status <>'Cancelled';";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$request) {
				$supplier = $this->Abas->getSupplier($request->supplier_id);
				$supplier_id = $supplier['id'];
				$supplier_name = $supplier['name'];
				$result[$ctr]->supplier_name = $supplier_name;
				$company = $this->Abas->getCompany($request->company_id);
				$result[$ctr]->company_name = $company->name;
				$result[$ctr]->details = $this->Purchasing_model->getPurchaseOrderDetails($request->id);
			}
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getIssuanceExpenseSummary($vessel,$date_from,$date_to){
		$sql = 'SELECT * FROM inventory_issuance WHERE issue_date >="'.$date_from.'" AND issue_date <="'.$date_to.'" AND vessel_id='.$vessel;
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			foreach($result as $ctr=>$issuance) {
				$vessel = $this->Abas->getVessel($issuance->vessel_id);
				$company = $this->Abas->getCompany($vessel->company);
				$result[$ctr]->company_name = $company->name;
				$result[$ctr]->details = $this->Inventory_model->getIssuanceDetails($issuance->id);
			}
		}else{
			$result = NULL;
		}
		return $result;
	}
	public function getBOMAmountByVessel($vessel,$date_from,$date_to){
		$sql = "SELECT am_bill_of_materials.*, am_vessel_evaluation.dry_docking_date FROM am_bill_of_materials INNER JOIN am_vessel_evaluation ON  am_bill_of_materials.evaluation_id=am_vessel_evaluation.id WHERE (am_bill_of_materials.status='Approved' OR am_bill_of_materials.status='Final') AND am_vessel_evaluation.vessel_id=".$vessel." AND am_bill_of_materials.start_date_of_repair BETWEEN '".$date_from. "' AND '".$date_to."'";
		$query = $this->db->query($sql);
		if($query){
			$result = $query->result();
			$labor_amount = 0;
			$supplies_amount = 0;
			foreach($result as $ctr=>$bom) {
				$labors = $this->Asset_Management_model->getBOMLabor($bom->id);
				foreach($labors as $labor){
					$labor_amount = $labor_amount + (($labor->rate_per_day * $labor->quantity)*$labor->days_needed);
				}
				$supplies = $this->Asset_Management_model->getBOMSupplies($bom->id);
				foreach($supplies as $item){
					$quantity_for_purchase = ($item->quantity)-($item->warehouse_quantity);
					if($quantity_for_purchase<=0){
						$total_cost = ($item->quantity) * ($item->warehouse_unit_cost);
					}else{
						$calc_wh_cost = $item->warehouse_quantity * $item->warehouse_unit_cost;
						$calc_ps_cost = $item->unit_cost * $quantity_for_purchase;
						$total_cost =  $calc_wh_cost + $calc_ps_cost;
					}
                	$supplies_amount = $supplies_amount + $total_cost;
				}
			}
			$result['grand_total_amount'] = $labor_amount + $supplies_amount;
		}else{
			$result = NULL;
		}
		return $result;

	}

	public function getRFP($action = FALSE){
		//$id = $_SESSION['abas_login']['userid'];
		$id = 5;
		if($action == 'approved_by_me') {
			$sql = "SELECT * FROM ac_request_payment WHERE approved_by = $id ORDER BY id DESC";
		} elseif ($action == 'verified_by_me') {
			$sql = "SELECT * FROM ac_request_payment WHERE verified_by = $id ORDER BY id DESC";
		} else {
			$sql = "SELECT * FROM ac_request_payment WHERE verified_by = $id or approved_by = $id ORDER BY id DESC";
		}

		$query = $this->db->query($sql);
		return $query->result();
	}

}
?>