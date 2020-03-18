<?php
	$tablecontents	=	'<tr><th colspan="6">No records found!</th></tr>';
	if(!empty($entries)) {
		$tablecontents	=	'';
		$entries		=	$entries->result_array();
		$grand_total_values	=	array('debit'=>0, 'credit'=>0);
		$total_values		=	array('debit'=>0, 'credit'=>0);
		foreach($entries as $entctr=>$entry) {
			$entry				=	$this->Accounting_model->getJournalEntry($entry['id']);
			if($entry['stat']==1) {
				$document_table		=	$entry['reference_table'];
				$posted_on			=	date("j F Y",strtotime($entry['posted_on']));
				$posted_by			=	$this->Abas->getUser($entry['posted_by']);
				$document			=	array('control_number'=>'x', );
				if(!empty($entry['reference_table'])) {
					$document			=	$this->db->query("SELECT * FROM ".$entry['reference_table']." WHERE id=".$entry['reference_id']);
					$document			=	(array)$document->row();
					if($entry['reference_table']=="ac_journal_vouchers") $document_table	=	"Journal Voucher";
					if($entry['reference_table']=="ac_vouchers") $document_table	=	"Check Voucher";
					if($entry['reference_table']=="inventory_deliveries") $document_table	=	"Receiving Report";
					if($entry['reference_table']=="statement_of_accounts") $document_table	=	"Statement of Account";
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
				$view_link			=	HTTP_PATH.'home/encode/'.$entry['reference_table'].'/view/'.$entry['reference_id'];
				$document_button	=	'<a href="'.$view_link.'" class="btn btn-xs btn-primary" '.($view_link_modal==true ? 'data-toggle="modal" data-target="#modalDialog"' : '').'>'.$entry['reference_table'].' transaction code '.$entry['reference_id'].'</a>';
				$balance_error		=	false;
				$transaction_button	=	'<a href="'.HTTP_PATH.'accounting/journal/view_transaction/'.$transaction['id'].'" class="btn btn-xs btn-info">View Related Entries</a>';
				$action_button		=	'
				<div class="dropdown pull-left">
					<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="actionmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">View <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="actionmenu">
						<li><a href="'.HTTP_PATH.'accounting/journal/view_transaction/'.$transaction['id'].'" title="View Related Entries">Related Entries</a></li>
						<li><a href="'.$view_link.'" '.($view_link_modal==true ? 'data-toggle="modal" data-target="#modalDialog"' : '').' title="View Document">Document</a></li>
					</ul>
				</div>';
				$total_values['debit']	=	$total_values['debit']+$entry['debit_amount'];
				$total_values['credit']	=	$total_values['credit']+$entry['credit_amount'];
				$balance				=	$total_values['debit'] - $total_values['credit'];
				$tablecontents	.=	'<tr>';
				$tablecontents	.=	'<td class="text-center">'.$posted_on.'<br/>by '.$posted_by['full_name'].'</td>';
				$tablecontents	.=	'<td class="text-center">'.$document_table.' '.(($document_table=="Check Voucher")?$document['voucher_number']:$document['control_number']).'</td>';
				$tablecontents	.=	'<td>'.$entry['account_code'].'</td>';
				$tablecontents	.=	'<td>'.$entry['account_name'].'</td>';
				$tablecontents	.=	'<td>'.$description.'</td>';
				$tablecontents	.=	'<td class="text-right">'.($entry['debit_amount']!=0?number_format($entry['debit_amount'],2):'-').'</td>';
				$tablecontents	.=	'<td class="text-right">'.($entry['credit_amount']!=0?number_format($entry['credit_amount'],2):'-').'</td>';
				$tablecontents	.=	'<td class="text-right">'.($balance!=0?number_format($balance,2):'-').'</td>';

				$tablecontents	.=	'<td class="text-right"><span class="col-xs-1">'.$action_button.'</span></td>';
			}
		}
		$tablecontents	.=	'<tr>';
		$tablecontents	.=	'<th class="text-right" colspan="5">Grand Total</th>';
		$tablecontents	.=	'<th class="text-right">'.number_format($total_values['debit'],2).'</th>';
		$tablecontents	.=	'<th class="text-right">'.number_format($total_values['credit'],2).'</th>';
		$tablecontents	.=	'<th>'.number_format(($total_values['debit']-$total_values['credit']),2).'</th>';
		$tablecontents	.=	'</tr>';
	}
?>
<h2 class="text-center"><?php echo $company->name; ?></h2>
<h3 class="text-center"><?php echo ucwords($account['name']); ?></h3>
<a href="<?php echo HTTP_PATH."accounting/general_ledger/print/?".http_build_query($_GET); ?>" class="btn btn-primary">Print</a>
<table id="book_of_accounts" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th>Posted On</th>
			<th>Control Number</th>
			<th>Account Code</th>
			<th>Account Title</th>
			<th>Memo</th>
			<th>Debit</th>
			<th>Credit</th>
			<th>Balance</th>
			<th></th>
		</tr>
	</thead>
	<?php echo $tablecontents; ?>
</table>