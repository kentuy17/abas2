<?php
$previous_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:HTTP_PATH;
$content		=	'';
$tablecontents	=	'';
$total_values	=	array('debit'=>0,'credit'=>0);
foreach($accounts as $account) {
	if($account['debit_total']!=0 || $account['credit_total']!=0) {
		$total_values['debit']	+=	$account['debit_total'];
		$total_values['credit']	+=	$account['credit_total'];
		$tablecontents	.=	'
		<tr>
			<td style="font-size:10px; text-align:center;">'.$account['financial_statement_code'].'-'.$account['general_ledger_code'].'</td>
			<td style="font-size:10px; text-align:left;">'.$account['name'].'</td>
			<td style="font-size:10px; text-align:right;">'.number_format($account['debit_total'],2).'</td>
			<td style="font-size:10px; text-align:right;">'.number_format($account['credit_total'],2).'</td>
			<td style="font-size:10px; text-align:center;"><a class="btn btn-default btn-xs" href="'.HTTP_PATH.'accounting/subsidiary_ledger/report?account='.$account['id'].'&'.http_build_query($_GET).'">View Breakdown</a></td>
		</tr>';
	}
}
$beginning_balance_form	=	"";
?>
<div style="text-align:center;">
	<p style="font-size:10px">
		<div style="font-size:20px; font-weight:600"><strong><?php echo $company->name; ?></strong></div>
		<?php echo $company->address; ?><br>
		<?php echo (!empty($company->telephone_no)?'Tel. Number'.$company->telephone_no:'').' '.(!empty($company->fax_no)?'Fax Number: '.$company->fax_no:''); ?>
	</p>
	<div style="font-size:18px; font-weight:600">Trial Balance</div>
	<div>From <?php echo date("j F Y",strtotime($_GET['dstart'])).' to '.date("j F Y",strtotime($_GET['dfinish'])); ?></div>
	<?php if(is_numeric($_GET['company']) && $this->Abas->checkPermissions("accounting|summarize_entries",false)): ?>
		<button id="confirm_save" class="btn btn-xs btn-info" onclick="javascript:confirmSave()">Summarize Entries</button>
		<div class="summary_form hide panel panel-info col-xs-offset-5 col-xs-2">
			<form action='<?php echo HTTP_PATH; ?>accounting/summarize_entries' method='POST'>
				<input type='hidden' name='company' value='<?php echo $company->id; ?>' /><input type='hidden' name='dstart' value='<?php echo $_GET['dstart']; ?>' /><input type='hidden' name='dfinish' value='<?php echo $_GET['dfinish']; ?>' />
				<div class='panel-heading'>Summarize Entries</div>
				<div class='panel-body'>
					<label for='posted_on'>Post Entries On:</label>
					<input id='posted_on' class='form-control' type='text' name='post_on' value='<?php echo date("Y-m-d"); ?>' placeholder='Post On' />
					<script>$('#posted_on').datepicker({dateFormat: 'yy-mm-dd'});</script>
					<label for='remarks'>Remarks/Memo/Particular:</label>
					<input id='remarks' class='form-control' type='text' name='remarks' placeholder='Remarks' />
					<input type='submit' class='btn btn-xs btn-primary' value='Submit' />
				</div>
			</form>
		</div>
	<?php endif; ?>
</div>
<table border="0" cellpadding="1" class="table table-bordered">
	<thead>
		<tr>
			<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Account Code</th>
			<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Account Title</th>
			<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Debit</th>
			<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Credit</th>
			<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Breakdown</th>
		</tr>
	</thead>
	<?php echo $tablecontents; ?>
	<tr>
		<th colspan="2"></th>
		<th style="font-size:12px; text-align:right;"><?php echo number_format($total_values['debit'],2); ?></th>
		<th style="font-size:12px; text-align:right;"><?php echo number_format(abs($total_values['credit']),2); ?></th>
	</tr>
</table>
<script>
function confirmSave() {
	$("#confirm_save").toggleClass("hide");
	$(".summary_form").toggleClass("hide");
}
</script>