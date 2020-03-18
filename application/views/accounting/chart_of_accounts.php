<?php
$accounts	=	$this->Accounting_model->getAccounts();
foreach ($accounts as $ctr => $value) {
	$data[$ctr] = array(
		'id' => $value['id'],
		'financial_statement_code' => $value['financial_statement_code'],
		'general_ledger_code' => $value['general_ledger_code']
	);
}
//$this->Mmm->debug($accounts);


$rows		=	"<tr><th>No accounts found!</th></tr>";
if(!empty($accounts)) {
	$rows	=	"";
	foreach($accounts as $account) {
		$btn_edit=$btn_entries="";
		if($this->Abas->checkPermissions("accounting|edit_chart_of_accounts",false)) {
			$btn_edit		=	"<li><a data-toggle='modal' data-target='#modalDialog' href='".HTTP_PATH."accounting/chart_of_accounts/edit/".$account['id']."'>Edit</a></li>";
			$btn_entries	=	"<li><a href='".HTTP_PATH."accounting/chart_of_accounts/view_entries/".$account['id']."'>Journal Entries</a></li>";
		}
		$manage		=
			'<div class="dropdown">'.
				'<button class="btn btn-warning btn-xs dropdown-toggle" type="button" id="actionmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>'.
				'<ul class="dropdown-menu" aria-labelledby="actionmenu">'.
					$btn_edit.
					$btn_entries.
				'</ul>'.
			'</div>';
		$classification_var = "";
		if($account['classification'] != null){
			$classification = $this->Abas->getItemById('ac_accounts_classification',array('id'=>$account['classification']));	
			$classification_var = $classification->name;
		}
		

		$rows		.=	"<tr>";
		$rows		.=	"<td>".$account['financial_statement_code'].$account['general_ledger_code']."</td>";
		$rows		.=	"<td>".$account['financial_statement_code']."</td>";
		$rows		.=	"<td>".$account['general_ledger_code']."</td>";
		$rows		.=	"<td>".$account['name']."</td>";
		$rows		.=	"<td>".$classification_var."</td>";
		$rows		.=	"<td>".$account['type']."</td>";
		$rows		.=	"<td>".$account['sub_type']."</td>";
		$rows		.=	"<td>".$manage."</td>";
		$rows		.=	"</tr>";
	}
}
?>

	<?php if($this->Abas->checkPermissions("accounting|edit_chart_of_accounts", false)): ?>
		<a href="<?php echo HTTP_PATH.'accounting/chart_of_accounts/add'; ?>" data-toggle="modal" data-target="#modalDialog" title="Add New Account" class="btn btn-primary">
			<span class="glyphicon glyphicon glyphicon-plus"></span> Add Account
		</a>
		<!--a href="<?php echo HTTP_PATH.'accounting/accounts_classification'; ?>" class="btn btn-success">
			<span class="glyphicon glyphicon glyphicon-briefcase"></span> Classification
		</a-->
	<?php endif; ?>
	<table data-toggle="table" id="chart_of_accounts" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-search="true" data-page-list="[5, 10, 20, 50, 100]">
		<thead>
			<tr>
				<th data-sortable="false" data-visible="true" data-align="center">Account Code</th>
				<th data-sortable="false" data-visible="false" data-align="center">Financial Statement Code</th>
				<th data-sortable="false" data-visible="false" data-align="center">General Ledger Code</th>
				<th data-sortable="false" data-visible="true" data-align="left">Account Name</th>
				<th data-sortable="false" data-visible="true" data-align="left">Account Type</th>
				<th data-sortable="false" data-visible="true" data-align="left">Nature of Account</th>
				<th data-sortable="false" data-visible="false" data-align="left">Sub-type</th>
				<th data-sortable="false" data-visible="true" data-align="center">Manage</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $rows; ?>
		</tbody>
	</table>

<script>
	function showChildren(id) {
		$(".child"+id).toggleClass("hide");
	}
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})

	 $(document).ready(function() {

		$('#datatable-responsive').DataTable();

	});
</script>