<?php
	$tablecontents	=	'<tr><th colspan="6">No records found!</th></tr>';
	$reference_table=	'ac_journal_vouchers';
	$entity			=	'Journal Voucher';
	$column_names['created_on']	=	"created_on";
	if(!empty($voucher)) {
		$company		=	$this->Abas->getCompany($voucher['company_id']);
		$tablecontents	=	'';
		$entries		=	$this->db->query("SELECT * FROM ac_transaction_journal WHERE reference_id=".$voucher['id']." AND reference_table='".$reference_table."'");
		$entries		=	$entries->result_array();
		$posted_on		=	date("j F Y",strtotime($voucher['posted_on']));
		$title			=	$entity." with Transaction Code ".$voucher['id'];
		$description	=	isset($entries[0]['remark'])?$entries[0]['remark']:$title;
		$voucher_button=	'<a href="'.HTTP_PATH.'home/encode/'.$reference_table.'/view/'.$voucher['id'].'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalDialog">'.$entity.' control # '.$voucher['control_number'].'</a>';
		$total_values	=	array('debit'=>0, 'credit'=>0);
		if(!empty($entries)) {
			$transaction	=	$this->Accounting_model->getTransaction($entries[0]['transaction_id']);
			$transaction_button	=	'<a href="'.HTTP_PATH.'accounting/journal/view_transaction/'.$transaction['id'].'" class="btn btn-xs btn-info">View Related Entries</a>';
			$tablecontents	.=	'<tr>';
			$tablecontents	.=	'<th class="text-center">'.$posted_on.'</th>';
			$tablecontents	.=	'<th class="text-center">'.$entity.' control # '.$voucher['control_number'].'</th>';
			$tablecontents	.=	'<td colspan="4"><span class="col-xs-10">'.$description.'</span><span class="col-xs-2">'.$transaction_button.'</span></td>';
			$tablecontents	.=	'</tr>';
			foreach($entries as $entryctr=>$entry) {
				$entry			=	$this->Accounting_model->getJournalEntry($entry['id']);
				$total_values['debit']	=	$total_values['debit']+$entry['debit_amount'];
				$total_values['credit']	=	$total_values['credit']+$entry['credit_amount'];
				$tablecontents	.=	'<tr>';
				$tablecontents	.=	'<td colspan="2"></td>';
				$tablecontents	.=	'<td>'.$entry['account_code'].'</td>';
				$tablecontents	.=	'<td>'.$entry['account_name'].'</td>';
				$tablecontents	.=	'<td class="text-right">'.($entry['debit_amount']!=0?$entry['debit_amount']:'-').'</td>';
				$tablecontents	.=	'<td class="text-right">'.($entry['credit_amount']!=0?$entry['credit_amount']:'-').'</td>';
				$tablecontents	.=	'</tr>';
			}
			$tablecontents	.=	'<tr>';
			$tablecontents	.=	'<td colspan="3"></td>';
			$tablecontents	.=	'<td><span class="pull-right">Totals</span></td>';
			$tablecontents	.=	'<td class="text-right">'.($total_values['debit']!=0?number_format($total_values['debit'],2):'-').'</td>';
			$tablecontents	.=	'<td class="text-right">'.($total_values['credit']!=0?number_format($total_values['credit'],2):'-').'</td>';
			$tablecontents	.=	'</tr>';
		}else{
			//temporary fix to add the references to some jvs that was not able to set its reference due to reason unknown
			$query=	$this->db->query("SELECT * FROM ac_journal_vouchers WHERE id=".$voucher['id']);
			if($query){
				$voucher	=	$query->row();
				$entries=	json_decode($voucher->journal_ids);
				foreach($entries as $entry) {
					$update = $this->db->query("UPDATE ac_transaction_journal SET reference_table='ac_journal_vouchers', reference_id=".$voucher->id." WHERE id=".$entry);
				}
			}
			header("Refresh:0");
		}
	}
?>
<?php if($this->Abas->checkPermissions("accounting|approve_journal_vouchers",false) && $voucher['approved_on']=="" && $voucher['disapproved_on']==""): ?>
	<a class="btn btn-md btn-success exclude-pageload" onclick="javascript:approveVoucher();">Approve Voucher</a>
	<a class="btn btn-md btn-danger exclude-pageload" onclick="javascript:disapproveVoucher();">Disapprove Voucher</a>
	<a class="btn btn-md btn-warning" href="<?php echo HTTP_PATH."accounting/journal/print_voucher/".$voucher['id']; ?>">Print Voucher</a>
<?php endif; ?>
<a class="btn btn-md btn-default" href="<?php echo HTTP_PATH."accounting/journal/view_vouchers"; ?>">Back to Journal Vouchers</a>
<h2 class="text-center"><?php echo $company->name; ?></h2>
<table id="book_of_accounts" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th>Posted On</th>
			<th>Reference</th>
			<th>Account Code</th>
			<th>Account Title</th>
			<th>Debit</th>
			<th>Credit</th>
		</tr>
	</thead>
	<?php echo $tablecontents; ?>
	<tr>
		<th class="text-right" colspan="4">Total</th>
		<th class="text-right"><?php echo number_format($total_values['debit'],2); ?></th>
		<th class="text-right"><?php echo number_format($total_values['credit'],2); ?></th>
	</tr>
</table>
<script>
<?php if($this->Abas->checkPermissions("accounting|approve_journal_vouchers",false)): ?>
function approveVoucher() {
	toastr.clear();
	toastr['warning']('This will mark the voucher as "approved", and all entries will now be journalized. <a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>accounting/journal/approve_voucher/<?php echo $voucher['id']; ?>">Continue</a>', "Are you sure?");
}
function disapproveVoucher() {
	toastr.clear();
	toastr['warning']('This will mark the voucher as "disapproved", and all entries will not be journalized. <a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>accounting/journal/disapprove_voucher/<?php echo $voucher['id']; ?>">Continue</a>', "Are you sure?");
}
<?php endif; ?>
</script>