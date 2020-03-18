<?php
	$totaldisplay	=	"";
	$tablecontents	=	'<tr><th colspan="6">No records found!</th></tr>';
	if(!empty($entries)) {
		$tablecontents	=	'';
		$entries		=	$entries->result_array();
		$grand_total_values	=	array('debit'=>0, 'credit'=>0);
		$total_values		=	array('debit'=>0, 'credit'=>0);
		foreach($entries as $entctr=>$entry) {
			$supplier_name = "-";
			$employee_name = "-";
			$entry				=	$this->Accounting_model->getJournalEntry($entry['id']);
			if($entry['stat']==1) {
				$is_soa				=	false;
				$is_payment			=	false;
				$is_receiving		=	false;
				$other_party		=	array("po_control_number"=>"", "supplier_name"=>"", "client_name"=>"", "po_id"=>"");
				$document_table		=	$entry['reference_table'];
				$created_on			=	date("j F Y",strtotime($entry['created_on']));
				$posted_on			=	date("j F Y",strtotime($entry['posted_on']));
				$posted_by			=	$this->Abas->getUser($entry['posted_by']);
				$document			=	array('control_number'=>'x', );
				$links				=	'<a href="'.HTTP_PATH.'home/encode/'.$entry['reference_table'].'/view/'.$entry['reference_id'].'" data-toggle="modal" data-target="#modalDialog" class="btn btn-xs btn-default"> Document</a>';
				if(!empty($entry['reference_table'])) {
					$document			=	$this->db->query("SELECT * FROM ".$entry['reference_table']." WHERE id=".$entry['reference_id']);
					$document			=	(array)$document->row();
					if($entry['reference_table']=="ac_journal_vouchers") {
						$document_table	=	"Journal Voucher";
						$sql = 'SELECT * FROM ac_transaction_journal WHERE transaction_id='.$entry['transaction_id'].' AND reference_table="ac_vouchers"';
						$query = $this->db->query($sql);
						if($query){
							$result = $query->result();
							foreach($result as $row){
								$sql2 = "SELECT * FROM ac_vouchers WHERE id=".$row->reference_id;	
								$query2 = $this->db->query($sql2);
								if($query2){
									$result2 = $query2->row();
									if($result2){
										if($result2->payee_type=='Employee'){
											if($result2->payee!=''){
												$employee = $this->Abas->getEmployee($result2->payee);
											}else{
												$employee = $this->Abas->getEmployee($result2->payee_others);
											}
											$other_party = "From Check Voucher: ".$row->reference_id."<br>Check number: ".$result2->check_num;
											$employee_name = $employee['full_name'];
										}
									}
								}
							}
						}
					}
					if($entry['reference_table']=="ac_vouchers") {
						$document_table		=	"Check Voucher";
						$is_payment			=	true;
						$other_party		=	"Check number: ".$document['check_num'];
						if(strtolower($document['payee_type'])=="supplier") {
							if($document['payee']<>''){
								$payee			=	$this->db->query("SELECT name FROM suppliers WHERE id=".$document['payee']);
								$payee			=	(array)$payee->row();
								$other_party	.=	" for ".$payee['name'];
								$supplier_name  = $payee['name'];
							}
						}
						elseif(strtolower($document['payee_type'])=="employee") {
							if($document['payee']<>''){
								$payee			=	$this->db->query("SELECT first_name, middle_name, last_name FROM hr_employees WHERE id=".$document['payee']);
								$payee			=	(array)$payee->row();
								$other_party	.=	" for ".$payee['last_name'].",".$payee['first_name']." ".$payee['middle_name'];
								$employee_name = $payee['last_name'].",".$payee['first_name']." ".$payee['middle_name'];
							}
						}
					}
					if($entry['reference_table']=="ac_ap_vouchers") {
						$document_table	=	"Accounts Payable Voucher";
						$is_delivery	=	true;
						$other_party_sql=	$this->db->query("SELECT s.name AS supplier_name, po.control_number AS po_control_number, po.id AS po_id FROM ac_transaction_journal AS e JOIN ac_ap_vouchers AS apv ON apv.id=e.reference_id JOIN inventory_po AS po ON apv.po_no=po.id JOIN suppliers AS s ON po.supplier_id=s.id WHERE e.id=".$entry['id']);
						$other_party	=	(array)$other_party_sql->row();
						$links			=	'<a href="'.HTTP_PATH.'purchasing/purchase_order/view/'.$other_party['po_id'].'" data-toggle="modal" data-target="#modalDialog" class="btn btn-xs btn-default"> Purchase Order</a>';
						$supplier_name  = $other_party['supplier_name'];
						$other_party	=	"PO# ".$other_party['po_control_number']." for ".$other_party['supplier_name'];
						
					}
					if($entry['reference_table']=="inventory_deliveries") {
						$document_table	=	"Receiving Report";
						$is_delivery	=	true;
						$other_party_sql=	$this->db->query("SELECT s.name AS supplier_name, d.po_no AS po_control_number FROM ac_transaction_journal AS e JOIN inventory_deliveries AS d ON d.id=e.reference_id JOIN suppliers AS s ON s.id=d.supplier_id WHERE e.id=".$entry['id']);
						$other_party	=	(array)$other_party_sql->row();
						$supplier_name  = $other_party['supplier_name'];
						$other_party	=	"PO# ".$other_party['po_control_number']." for ".$other_party['supplier_name'];
						
					}
					if($entry['reference_table']=="statement_of_accounts") {
						$is_soa			=	true;
						$document_table	=	"Statement of Account";
						$other_party_sql=	$this->db->query("SELECT c.company AS client_name FROM ac_transaction_journal AS e JOIN statement_of_accounts AS soa ON soa.id=e.reference_id JOIN clients AS c ON c.id=soa.client_id WHERE e.id=".$entry['id']);
						$other_party	=	(array)$other_party_sql->row();
						$other_party	=	$other_party['client_name'];
					}
					if($entry['reference_table']=="payments") {
						$is_payment		=	true;
						$document_table	=	"Payment";
						$other_party_sql=	$this->db->query("SELECT c.company AS client_name FROM ac_transaction_journal AS e JOIN payments AS p ON p.id=e.reference_id JOIN statement_of_accounts AS soa ON soa.id=p.soa_id JOIN clients AS c ON soa.client_id=c.id WHERE e.id=".$entry['id']);
						$other_party	=	(array)$other_party_sql->row();
						if(isset($other_party['client_name'])) {
							$other_party	=	$other_party['client_name'];
						}
					}
				}
				$title				=	$document_table." control number ".$document['control_number'];
				$description		=	isset($entry['remark'])?$entry['remark']:$title;
				$transaction		=	$this->Accounting_model->getTransaction($entry['transaction_id']);
				if($document_table	==	"Check Voucher"){
					$description		= $transaction['remark'];
				}
				if($entry['remark']==''){
					$description		= $transaction['remark'];
				}
				$view_link_modal	=	true;
				$balance_error		=	false;
				$transaction_button	=	'<a href="'.HTTP_PATH.'accounting/journal/view_transaction/'.$transaction['id'].'" class="btn btn-xs btn-info">View Related Entries</a>';
				$action_button		=	'
						<a href="'.HTTP_PATH.'accounting/journal/view_transaction/'.$transaction['id'].'" title="View Related Entries" class="btn btn-xs btn-default">Related Entries</a>
						'.$links.'';
				$total_values['debit']	=	$total_values['debit']+$entry['debit_amount'];
				$total_values['credit']	=	$total_values['credit']+$entry['credit_amount'];
				$balance				=	$total_values['debit'] - $total_values['credit'];
				$tablecontents	.=	'<tr>';
				if(!isset($company->id)) $tablecontents	.=	'<td class="text-center">'.$entry['company']['name'].'</th>';
				$tablecontents	.=	'<td class="text-center">'.$created_on.'</th>';
				$tablecontents	.=	'<td class="text-center">'.$posted_on.'</th>';
				$tablecontents	.=	'<td class="text-center">'.$posted_by['full_name'].'</th>';
				$tablecontents	.=	'<td class="text-center">'.$posted_by['user_location'].'</th>';
				$tablecontents	.=	'<td class="text-center">'.$document_table.' '.(($document_table=="Check Voucher")?$document['voucher_number']:$document['control_number']).'</th>';
				$tablecontents	.=	'<td class="text-center">'.$document_table.' '.$entry['reference_id'].'</th>';
				$tablecontents	.=	!is_array($other_party)?'<td>'.$other_party.'</td>':'<td>-</td>';
				if($account['id']=="76" ||$account['id']=="27" ||$account['id']=="13"){
					$tablecontents	.=	'<td>'.$supplier_name.'</td>';
				}elseif($account['id']=="12"){
					$tablecontents	.=	'<td>'.$employee_name.'</td>';
				}
				$tablecontents	.=	'<td>'.$entry['department']['name'].'</td>';
				$tablecontents	.=	'<td>'.$entry['vessel']['name'].'</td>';
				$tablecontents	.=	'<td>'.$description.'</td>';
				$tablecontents	.=	'<td class="text-right">'.($entry['debit_amount']!=0?number_format($entry['debit_amount'],2):'-').'</td>';
				$tablecontents	.=	'<td class="text-right">'.($entry['credit_amount']!=0?number_format($entry['credit_amount'],2):'-').'</td>';
				$tablecontents	.=	'<td class="text-right">'.($balance!=0?number_format($balance,2):'-').'</td>';
				$tablecontents	.=	'<td>'.$action_button.'</td>';
			}
		}
		$totaldisplay	=	'<div>';
		$totaldisplay	.=	'<h3>Grand Total</h3>';
		$totaldisplay	.=	'<p>Debit: '.number_format($total_values['debit'],2).'</p>';
		$totaldisplay	.=	'<p>Credit: '.number_format($total_values['credit'],2).'</p>';
		$totaldisplay	.=	'<p>Balance: '.number_format(($total_values['debit']-$total_values['credit']),2).'</p>';
		$totaldisplay	.=	'</div>';
	}
?>
<h2 class="text-center"><?php echo $company->name; ?></h2>
<h3 class="text-center"><?php echo ucwords($account['name']); ?></h3>
<?php echo $totaldisplay; ?>
<a href="<?php echo HTTP_PATH."accounting/subsidiary_ledger/print/?".http_build_query($_GET); ?>" class="btn btn-primary">Print</a>
<div style="overflow-x: auto;">
<table id="subsidiary_ledger" class="table table-bordered table-striped table-hover responsive" >
	<thead>
		<tr>
			<?php if(!isset($company->id)) echo "<th>Company</th>"; ?>
			<th>Created On</th>
			<th>Posted On</th>
			<th>Posted By</th>
			<th>Posted At</th>
			<th>Control Number</th>
			<th>Transaction Code</th>
			<th>Other Party</th>
			<?php if($account['id']=="76" ||$account['id']=="27"||$account['id']=="12"||$account['id']=="13"){ ?>
				<th>For</th>
			<?php } ?>
			<th>Department</th>
			<th>Vessel</th>
			<th>Particular</th>
			<th>Debit</th>
			<th>Credit</th>
			<th>Balance</th>
			<th>Trace</th>
		</tr>
	</thead>
	<?php echo $tablecontents; ?>
</table>
</div>