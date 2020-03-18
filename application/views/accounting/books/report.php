<?php
	$tablecontents	=	'<tr><th colspan="6">No records found!</th></tr>';
	if(!empty($documents)) {
		$tablecontents	=	'';
		$grand_total_values	=	array('debit'=>0, 'credit'=>0);
		foreach($documents as $docctr=>$document) {
			if($_GET['journal_type']=="general") {
				if(isset($document['is_accounts_payable'])) {
					$entity				=	"Accounts Payable Voucher";
					$reference_table	=	"ac_ap_vouchers";
				}elseif(isset($document['is_material_issuance'])){
					$entity				=	"Materials and Supplies Issuance";
					$reference_table	=	"inventory_issuance";
				}
				else {
					$entity				=	"Journal Voucher";
					$reference_table	=	"ac_journal_vouchers";
				}
			}
			$total_values	=	array('debit'=>0, 'credit'=>0);
			$posted_on		=	date("j F Y",strtotime($document[$column_names['created_on']]));
			$title			=	$entity." with Transaction Code ".$document['id'];
			$description	=	isset($entries[0]['remark'])?$entries[0]['remark']:$title;
			if($reference_table=="ac_vouchers") { // disbursement journal
				$payee		=	"";
				if(strtolower($document['payee_type'])=="supplier") {
					$payee	=	$this->db->query("SELECT name FROM suppliers WHERE id=".$document['payee']);
					$payee	=	(array)$payee->row();
					$payee	=	$payee['name'];
				}
				elseif(strtolower($document['payee_type'])=="employee") {
					$payee	=	$this->db->query("SELECT first_name, middle_name, last_name FROM hr_employees WHERE id=".$document['payee']);
					$payee	=	(array)$payee->row();
					$payee	=	$payee['last_name']." ".$payee['first_name']." ".$payee['middle_name'];
				}
				$entries		=	$this->db->query("SELECT * FROM ac_transaction_journal WHERE reference_id=".$document['id']." AND reference_table='".$reference_table."' AND stat=1");
				$entries		=	$entries->result_array();
				$description	=	$entries[0]['remark'];
				$description	=	"Check Number ".$document['check_num']." - ".$description;
			}
			else {
				$entries		=	$this->db->query("SELECT id,transaction_id,debit_amount,credit_amount FROM ac_transaction_journal WHERE reference_id=".$document['id']." AND reference_table='".$reference_table."' AND stat=1");
				$entries		=	$entries->result_array();
			}
			if(!isset($view_link)) {
				$view_link_modal	=	true;
				$view_link			=	HTTP_PATH.'home/encode/'.$reference_table.'/view/';
			}
			else {
				$view_link_modal	=	false;
				//$view_link			=	HTTP_PATH.'home/encode/'.$reference_table.'/view/';
			}
			$document_button=	'<a href="'.$view_link.$document['id'].'" class="btn btn-xs btn-primary" '.($view_link_modal==true ? 'data-toggle="modal" data-target="#modalDialog"' : '').'>'.$entity.' control # '.$document['control_number'].'</a>';
			if(!empty($entries)) {
				$balance_error				=	false;
				$transaction				=	$this->Accounting_model->getTransaction($entries[0]['transaction_id']);
				$transaction_button			=	'<a href="'.HTTP_PATH.'accounting/journal/view_transaction/'.$entries[0]['transaction_id'].'" class="btn btn-xs btn-info">View Related Entries</a>';
				$tablerow					=	'';
				$action_button				=	'
				<div class="dropdown pull-left">
					<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="actionmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">View <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="actionmenu">
						<li><a href="'.HTTP_PATH.'accounting/journal/view_transaction/'.$transaction['id'].'" title="View Related Entries">Related Entries</a></li>
						<li><a href="'.$view_link.$document['id'].'" '.($view_link_modal==true ? 'data-toggle="modal" data-target="#modalDialog"' : '').' title="View Document">Document</a></li>
					</ul>
				</div>';
				$first_row = true;
				$count_entry_rows = count($entries);
				foreach($entries as $entryctr=>$entry) {
					$entry					=	$this->Accounting_model->getJournalEntry($entry['id']);
					if($entryctr==0) {
						$first_entry		=	$entry;
					}
					if($entry['transaction_id']==$first_entry['transaction_id']) {
						$total_values['debit']	=	$total_values['debit']+$entry['debit_amount'];
						$total_values['credit']	=	$total_values['credit']+$entry['credit_amount'];
						$tablerow				.=	'<tr>';
						if($first_row==true){
							$tablerow	.=	'<td rowspan="'.$count_entry_rows.'" class="text-center">'.$posted_on.'</td>';
							$tablerow	.=	'<td rowspan="'.$count_entry_rows.'" class="text-center">'.$entity.' control # '.(($reference_table=="ac_vouchers")?$document['voucher_number']:$document['control_number']).'</td>';
							$tablerow	.=	'<td rowspan="'.$count_entry_rows.'">'.$description.'</td>';
							if($reference_table=="ac_vouchers"){
								$tablerow	.=	'<td rowspan="'.$count_entry_rows.'">'.$document['payee_type'].'</td>';
								$tablerow	.=	'<td rowspan="'.$count_entry_rows.'">'.$payee.'</td>';
							}

						}
						$tablerow				.=	'<td>'.$entry['account_code'].'</td>';
						$tablerow				.=	'<td>'.$entry['account_name'].'</td>';
						$tablerow				.=	'<td class="text-right">'.($entry['debit_amount']!=0?number_format($entry['debit_amount'],2):'-').'</td>';
						$tablerow				.=	'<td class="text-right">'.($entry['credit_amount']!=0?number_format($entry['credit_amount'],2):'-').'</td>';
						if($first_row==true){
							$tablerow	.=	'<td><span class="col-xs-1">'.$action_button.'</span></td>';
						}
						$first_row=false;
						$tablerow				.=	'</tr>';
					}
				}
				$disbalanced	=	"";
				if(($total_values['debit']-$total_values['credit'])<>0) {
					$disbalanced="bg-danger";
				}
				$grand_total_values['debit']	=	$grand_total_values['debit']+$total_values['debit'];
				$grand_total_values['credit']	=	$grand_total_values['credit']+$total_values['credit'];
				$tablecontents	.=	$tablerow;
				$tablecontents	.=	'<tr>';
				if($reference_table=="ac_vouchers"){
					$tablecontents	.=	'<td class="'.$disbalanced.'" colspan="6"></td>';
				}else{
					$tablecontents	.=	'<td class="'.$disbalanced.'" colspan="4"></td>';
				}
				$tablecontents	.=	'<td class="'.$disbalanced.'"><span class="pull-right">Totals</span></td>';
				$tablecontents	.=	'<td class="text-right '.$disbalanced.'">'.($total_values['debit']!=0?number_format($total_values['debit'],2):'-').'</td>';
				$tablecontents	.=	'<td class="text-right '.$disbalanced.'">'.($total_values['credit']!=0?number_format($total_values['credit'],2):'-').'</td>';
				$tablecontents	.=	'</tr>';
			}
		}
		$tablecontents	.=	'<tr>';
		if($reference_table=="ac_vouchers"){
			$tablecontents	.=	'<th class="text-right" colspan="7">Grand Total</th>';
		}else{
			$tablecontents	.=	'<th class="text-right" colspan="5">Grand Total</th>';
		}
		$tablecontents	.=	'<th class="text-right">'.number_format($grand_total_values['debit'],2).'</th>';
		$tablecontents	.=	'<th class="text-right">'.number_format($grand_total_values['credit'],2).'</th>';
		//$tablecontents	.=	'<!--<th>'.number_format(($grand_total_values['debit']-$grand_total_values['credit']),2).'</th>-->';
		$tablecontents	.=	'</tr>';
	}
?>
<h2 class="text-center"><?php echo $company->name; ?></h2>
<h3 class="text-center"><?php echo ucwords($_GET['journal_type']); ?> Journal</h3>
<a href="<?php echo HTTP_PATH."accounting/books/print/?".http_build_query($_GET); ?>" class="btn btn-primary" target="_blank">Print</a>
<table id="book_of_accounts" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th>Posted On</th>
			<th>Reference</th>
			<th>Particulars</th>
			<?php if($reference_table=="ac_vouchers"){ ?>
			<th>Payee Type</th>
			<th>Payee Name</th>
			<?php } ?>
			<th>Account Code</th>
			<th>Account Title</th>
			<th>Debit</th>
			<th>Credit</th>
			<th></th>
		</tr>
	</thead>
	<?php echo $tablecontents; ?>
</table>