<?php
$entries_table	=	"<tr><td colspan='99'>No data found!</td></tr>";
$total_values	=	array("debit"=>0, "credit"=>0);
if(!empty($transaction['details'])) {
	$entries_table		=	"";
	foreach($transaction['details'] as $entry) {
		$entry			=	$this->Accounting_model->getJournalEntry($entry['id']);
		//$this->Mmm->debug($entry);
		$account		=	$this->Accounting_model->getAccount($entry['coa_id']);
		$reconcile_btn	=	"<a class='btn btn-block  btn-xs btn-default' href='".HTTP_PATH."accounting/journal/reconciliation/".$entry['id']."' data-toggle='modal' data-target='#modalDialog'>Reconcile</a>";
		$reference_btn	=	"<a class='btn btn-block  btn-xs btn-primary' href='".HTTP_PATH."home/encode/".$entry['reference_table']."/view/".$entry['reference_id']."' data-toggle='modal' data-target='#modalDialog'>Reference</a>";
		$edit_btn	=	"<a class='btn btn-xs btn-block btn-warning' href='".HTTP_PATH."accounting/transactions/edit_entry/".$entry['id']."' data-toggle='modal' data-target='#modalDialogNorm'>Edit</a>";
		$total_values['debit']	=	$total_values['debit']+$entry['debit_amount'];
		$total_values['credit']	=	$total_values['credit']+$entry['credit_amount'];
		$business_unit_code	=	00;
		$department_code	=	(isset($entry['department']->accounting_code)?sprintf("%02d", $entry['department']->accounting_code):"00");
		$vessel_code		=	(isset($entry['vessel']->id)?sprintf("%03d", $entry['vessel']->id):"000");
		$contract_code		=	(isset($entry['contract']['reference_no'])?sprintf("%04d", $entry['contract']['reference_no']):"00");
		$account_code		=	$business_unit_code.$department_code.$vessel_code.$contract_code.$account['financial_statement_code'].$account['general_ledger_code'];
		$entries_table	.=	"<tr>";
			$entries_table	.=	"<td>".date("j F Y h:m A",strtotime($entry['posted_on']))."</td>";
			$entries_table	.=	"<td>".(isset($entry['department']['name'])?$entry['department']['name']:"")."</td>";
			$entries_table	.=	"<td>".(isset($entry['vessel']['name'])?$entry['vessel']['name']:"")."</td>";
			$entries_table	.=	"<td>".(isset($entry['contract']['id'])?$entry['contract']['id']:"")."</td>";
			$entries_table	.=	"<td>".$entry['account_code']."</td>";
			$entries_table	.=	"<td>".$entry['account_name']."</td>";
			$entries_table	.=	"<td>".$entry['reference_table']."</td>";
			$entries_table	.=	"<td>".$entry['reference_id']."</td>";
			$entries_table	.=	"<td>".$entry['remark']."</td>";
			$entries_table	.=	"<td>".(isset($entry['created_by']['full_name'])?$entry['created_by']['full_name']:"")."</td>";
			$entries_table	.=	"<td>".date("j F Y h:m A",strtotime($entry['created_on']))."</td>";
			$entries_table	.=	"<td>".$entry['debit_amount']."</td>";
			$entries_table	.=	"<td>".$entry['credit_amount']."</td>";
			$entries_table	.=	"<td>";
				if(ENVIRONMENT=="development") $entries_table	.=	$reconcile_btn;
				$entries_table	.=	$reference_btn;
				if($this->Abas->checkPermissions("accounting|edit_transaction_journal_entries",false)){
					$entries_table	.=	$edit_btn;
				}
			$entries_table	.=	"</td>";
		$entries_table	.=	"</tr>"; 
	}
	$entries_table .="<tr >
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><h4><b>Total</b></h4></td>
						<td></td>
						<td></td>
						<td><h4>".$total_values['debit']."</h4></td>
						<td><h4>".$total_values['credit']."</h4></td>
						<td></td>
					</tr>";
}
$back_action	=	HTTP_PATH."accounting/transactions";
if(isset($_SERVER['HTTP_REFERER']) && substr_count($_SERVER['HTTP_REFERER'],"insert")==false) {
	$back_action=	$_SERVER['HTTP_REFERER'];
}
if(isset($_SERVER['HTTP_REFERER']) && substr_count($_SERVER['HTTP_REFERER'],"view_transaction")>0) {
	$back_action	=	HTTP_PATH."accounting/transactions";
}
?>
<a href="<?php echo $back_action; ?>" class="btn btn-warning">Back</a>
<a href="<?php echo HTTP_PATH.'accounting/transactions/add_entry/'.$transaction['id']; ?>" data-toggle="modal" data-target="#modalDialog" class="btn btn-info" >
	<span class="glyphicon glyphicon glyphicon-briefcase"></span> Add Entry To This Transaction
</a>
<h3><?php echo $transaction['remark']; ?></h3>
<table data-toggle="table" id="transaction-entries-table" class="table table-bordered table-striped table-hover" data-pagination="false" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" showFooter='true'>
	<thead>
		<tr>
			<th data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Date Posted</th>
			<th data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Department</th>
			<th data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Vessel</th>
			<th data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Contract</th>
			<th data-visible="true" data-align="center" data-sortable="true" class="col-md-1">Account Code</th>
			<th data-visible="true" data-align="left" data-sortable="true" class="col-md-2">Account Name</th>
			<th data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Reference Table</th>
			<th data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Reference ID</th>
			<th data-visible="true" data-align="left" data-sortable="false" class="col-md-2">Particulars</th>
			<th data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Posted by</th>
			<th data-visible="false" data-align="left" data-sortable="false" class="col-md-1">Date Created</th>
			<th data-field="debit" data-visible="true" data-align="right" data-sortable="true" class="col-md-1" data-footer-formatter="sumFormatter">Debit</th>
			<th data-visible="true" data-align="right" data-sortable="true" class="col-md-1">Credit</th>
			<th data-halign="center" data-align="center" class="col-md-1">Manage</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $entries_table; ?>
	</tbody>
</table>
<script>
<?php if($total_values['credit']!=$total_values['debit']): ?>
toastr['warnmsg']("Your debit and credit values are unbalanced!","ABAS Says");
<?php endif; ?>
$(function () {
	var $table = $('#transaction-entries-table');
	$table.bootstrapTable();
});
</script>