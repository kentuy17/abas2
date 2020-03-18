<?php

require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

$previous_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:HTTP_PATH;
$company				=	(object)array("id"=>null,"name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
$company_query=$department_query=$vessel_query=$contract_query=$company_id="";
;
if(isset($_GET['company'])) {
	if(is_numeric($_GET['company'])) {
		$company		=	$this->Abas->getCompany($_GET['company']);
		if($company) {
			$company_id				=	$company->id;
			if($company_id==1){
				$company_query			=	' AND (company_id='.$company->id.' OR company_id=10)';
			}else{
				$company_query			=	' AND company_id='.$company->id;
			}
			$company_account_code	=	str_pad($company->id, 3, '0', STR_PAD_LEFT);
		}
	}
}
if(!isset($_GET['dstart']) || !isset($_GET['dfinish'])) {
	$this->Abas->sysMsg("warnmsg", "Report date ranges not set!");
	$this->Abas->redirect($previous_page);
}
$daterange	=	array("start"=>date("Y-m-d H:i:s", strtotime($_GET['dstart'])), "finish"=>date("Y-m-d H:i:s", strtotime($_GET['dfinish'])));
$financial_statement_accounts	=	$this->Accounting_model->getFinancialStatementClassifications(true);
if(empty($financial_statement_accounts)) {
	$this->Abas->sysMsg("errmsg", "No account classifications were found!");
	$this->Abas->redirect($previous_page);
}
$subtotal_style			=	'style="text-align:right;"';
$value_style			=	'style="text-align:right;"';
$title_style			=	'style="font-size:14px; font-weight:bold;"';
$spacer					=	'&nbsp; &nbsp; &nbsp; &nbsp;';
$report					=	'';
{ // Assets
	$total_balance			=	0;
	{ // Current Assets
		$asset_report['display']=	'
		<tr><td colspan="3" '.$title_style.'>Assets</td></tr>
		<tr>
			<td>Current Assets</td>
			<td colspan="2"></td>
		</tr>
		';
		$asset_report['current_assets']=$asset_report['current_assets']='';
		$asset_accounts['current_assets']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '11__' AND code<>'1100' ORDER BY code ASC");
		$asset_accounts['current_assets']		=	$asset_accounts['current_assets']->result_array();
		$assetctr								=	0;
		$subcategory_total						=	0;
		foreach($asset_accounts['current_assets'] as $current_asset) {
			$gl_accounts						=	$this->db->query("SELECT id,general_ledger_code FROM ac_accounts WHERE financial_statement_code=".$current_asset['code']);
			$gl_accounts						=	$gl_accounts->result_array();
			$current_asset_balance				=	0;
			foreach($gl_accounts as $glctr=>$gl_account) {
				if($daterange['start']!="1970-01-01 00:00:00" && $daterange['finish']) {
					$start_report		=	date("Y-m-d",strtotime($daterange['start']))." 00:00:00";
					$finish_report		=	date("Y-m-d",strtotime($daterange['finish']))." 23:59:59";
					$date_range_query	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
				}
				$values					=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query." AND coa_id=".$gl_account['id'].$company_query);
				$values					=	(array)$values->row();
				if(isset($_GET['gl_trace_mode'])) {
				if($_GET['gl_trace_mode']==1) {
					$asset_report['display']		.=	'
					<tr>
						<td>'.$spacer.$spacer.$spacer.$gl_account['general_ledger_code'].'</td>
						<td '.$value_style.'>'.number_format((($values['total_debit']-$values['total_credit'])),2).'</td>
					</tr>
					';
				}
				}
				$current_asset_balance	=	$current_asset_balance+($values['total_debit']-$values['total_credit']);
			}
			$subcategory_total					=	$subcategory_total+$current_asset_balance;
			$asset_report['display']		.=	'
			<tr>
				<td>'.$spacer.$spacer.$current_asset['name'].'</td>
				<td '.$value_style.'>'.number_format(abs($current_asset_balance),2).'</td>
			</tr>
			';
		}
		unset($asset_accounts['current_assets']);
		$total_balance	+=	$subcategory_total;
		$asset_report['display']			.=	'
		<tr>
			<td>'.$spacer.'Current Assets</td>
			<td '.$subtotal_style.'>'.number_format(abs($subcategory_total),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-decoration:underline;"><hr/></td></tr>
		';
	}
	{ // Non-current Assets
		$asset_report['display']			.=	'
		<tr>
			<td>Non-Current Assets</td>
			<td colspan="2"></td>
		</tr>
		';
		$asset_accounts['non_current_assets']	=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '12__' AND code<>'1200' ORDER BY code ASC");
		$asset_accounts['non_current_assets']	=	$asset_accounts['non_current_assets']->result_array();
		$subcategory_total						=	0;
		foreach($asset_accounts['non_current_assets'] as $non_current_asset) {
			$gl_accounts						=	$this->db->query("SELECT id, general_ledger_code FROM ac_accounts WHERE financial_statement_code=".$non_current_asset['code']);
			$gl_accounts						=	$gl_accounts->result_array();
			$non_current_asset_balance			=	0;
			foreach($gl_accounts as $glctr=>$gl_account) {
				if($daterange['start']!="1970-01-01 00:00:00" && $daterange['finish']) {
					$start_report		=	date("Y-m-d",strtotime($daterange['start']))." 00:00:00";
					$finish_report		=	date("Y-m-d",strtotime($daterange['finish']))." 23:59:59";
					$date_range_query	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
				}
				$values					=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query." AND coa_id=".$gl_account['id'].$company_query);
				$values					=	(array)$values->row();
				if(isset($_GET['gl_trace_mode'])) {
				if($_GET['gl_trace_mode']==1) {
					$asset_report['display']		.=	'
					<tr>
						<td>'.$spacer.$spacer.$spacer.$gl_account['general_ledger_code'].'</td>
						<td '.$value_style.'>'.number_format((($values['total_debit']-$values['total_credit'])),2).'</td>
					</tr>
					';
				}
				}
				$non_current_asset_balance		=	$non_current_asset_balance+($values['total_debit']-$values['total_credit']);
			}
			$subcategory_total					=	$subcategory_total+$non_current_asset_balance;
			$asset_report['display']		.=	'
			<tr>
				<td>'.$spacer.$spacer.$non_current_asset['name'].'</td>
				<td '.$value_style.'>'.number_format(abs($non_current_asset_balance),2).'</td>
			</tr>
			';
		}
		unset($asset_accounts['non_current_assets']);
		$total_balance	+=	$subcategory_total;
		$asset_report['display']			.=	'
		<tr>
			<td>'.$spacer.'Non-Current Assets</td>
			<td '.$subtotal_style.'>'.number_format(abs($subcategory_total),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
		<tr>
			<td>Total Assets</td>
			<td '.$subtotal_style.'>'.number_format(abs($total_balance),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-align:center;font-size:8px;">======================================================================================================================================================</td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		';
	}
}
{// Liabilities
	$total_balance			=	0;
	{ // Current Liabilities
		$liabilities_report['display']=	'
		<tr><td colspan="3" '.$title_style.'>Liabilities and Stockholder\'s Equity</td></tr>
		<tr>
			<td>Current Liabilities</td>
			<td colspan="2"></td>
		</tr>
		';
		$liabilities_report['current_liabilities']=$liabilities_report['current_liabilities']='';
		$liabilities_accounts['current_liabilities']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '21__' AND code NOT LIKE '___0'");
		$liabilities_accounts['current_liabilities']		=	$liabilities_accounts['current_liabilities']->result_array();
		$liabilitiesctr										=	0;
		$subcategory_total									=	0;
		foreach($liabilities_accounts['current_liabilities'] as $current_liabilities) {
			$gl_accounts						=	$this->db->query("SELECT id,general_ledger_code FROM ac_accounts WHERE financial_statement_code=".$current_liabilities['code']);
			$gl_accounts						=	$gl_accounts->result_array();
			$current_liabilities_balance		=	0;
			foreach($gl_accounts as $glctr=>$gl_account) {
				if($daterange['start']!="1970-01-01 00:00:00" && $daterange['finish']) {
					$start_report				=	date("Y-m-d",strtotime($daterange['start']))." 00:00:00";
					$finish_report				=	date("Y-m-d",strtotime($daterange['finish']))." 23:59:59";
					$date_range_query			=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
				}
				$values							=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query." AND coa_id=".$gl_account['id'].$company_query);
				$values							=	(array)$values->row();
				if(isset($_GET['gl_trace_mode'])) {
				if($_GET['gl_trace_mode']==1) {
					$liabilities_report['display']		.=	'
					<tr>
						<td>'.$spacer.$spacer.$spacer.$gl_account['general_ledger_code'].'</td>
						<td '.$value_style.'>'.number_format((($values['total_debit']-$values['total_credit'])),2).'</td>
					</tr>
					';
				}
				}
				$current_liabilities_balance	=	$current_liabilities_balance+($values['total_debit']-$values['total_credit']);
			}
			if($current_liabilities['code']==2121 && !isset($ap_clearing)) { // adds hardcoded map of 71997902
				$ap_clearing						=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code='7199' AND general_ledger_code='7902'");
				$ap_clearing						=	(array)$ap_clearing->row();
				$ap_clearing						=	$this->Accounting_model->getAccount($ap_clearing['id'], $daterange, $company_id);
				if(isset($_GET['gl_trace_mode'])) {
				if($_GET['gl_trace_mode']==1) {
					$liabilities_report['display']		.=	'
					<tr>
						<td>'.$spacer.$spacer.$spacer.$ap_clearing['general_ledger_code'].'</td>
						<td '.$value_style.'>'.number_format((($ap_clearing['total_debit']-$ap_clearing['total_credit'])),2).'</td>
					</tr>
					';
				}
				}
				$current_liabilities_balance		+=	($ap_clearing['total_debit']-$ap_clearing['total_credit']);
			}
			$subcategory_total					=	$subcategory_total+$current_liabilities_balance;
			$liabilities_report['display']		.=	'
			<tr>
				<td>'.$spacer.$spacer.$current_liabilities['name'].'</td>
				<td '.$value_style.'>'.number_format(abs($current_liabilities_balance),2).'</td>
			</tr>
			';
		}
		unset($liabilities_accounts['current_liabilities']);
		$total_balance	+=	$subcategory_total;
		$liabilities_report['display']			.=	'
		<tr>
			<td>'.$spacer.'Current Liabilities</td>
			<td '.$subtotal_style.'>'.number_format(abs($subcategory_total),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
		';
	}
	{ // Non-current Liabilities
		$liabilities_report['display']			.=	'
		<tr>
			<td>Non-Current Liabilities</td>
			<td colspan="2"></td>
		</tr>
		';
		$liabilities_accounts['non_current_liabilities']	=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '22__' AND code NOT LIKE '___0'");
		$liabilities_accounts['non_current_liabilities']	=	$liabilities_accounts['non_current_liabilities']->result_array();
		$subcategory_total									=	0;
		foreach($liabilities_accounts['non_current_liabilities'] as $non_current_liabilities) {
			$gl_accounts									=	$this->db->query("SELECT id,general_ledger_code FROM ac_accounts WHERE financial_statement_code=".$non_current_liabilities['code']);
			$gl_accounts									=	$gl_accounts->result_array();
			$non_current_liabilities_balance				=	0;
			foreach($gl_accounts as $glctr=>$gl_account) {
				if($daterange['start']!="1970-01-01 00:00:00" && $daterange['finish']) {
					$start_report		=	date("Y-m-d",strtotime($daterange['start']))." 00:00:00";
					$finish_report		=	date("Y-m-d",strtotime($daterange['finish']))." 23:59:59";
					$date_range_query	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
				}
				$values								=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query." AND coa_id=".$gl_account['id'].$company_query);
				$values								=	(array)$values->row();
				if(isset($_GET['gl_trace_mode'])) {
				if($_GET['gl_trace_mode']==1) {
					$liabilities_report['display']		.=	'
					<tr>
						<td>'.$spacer.$spacer.$spacer.$gl_account['general_ledger_code'].'</td>
						<td '.$value_style.'>'.number_format((($values['total_debit']-$values['total_credit'])),2).'</td>
					</tr>
					';
				}
				}
				$non_current_liabilities_balance	=	$non_current_liabilities_balance+($values['total_debit']-$values['total_credit']);
			}
			$subcategory_total					=	$subcategory_total+$non_current_liabilities_balance;
			$liabilities_report['display']		.=	'
			<tr>
				<td>'.$spacer.$spacer.$non_current_liabilities['name'].'</td>
				<td '.$value_style.'>'.number_format(abs($non_current_liabilities_balance),2).'</td>
			</tr>
			';
		}
		unset($liabilities_accounts['non_current_liabilities']);
		$total_balance	+=	$subcategory_total;
		$liabilities_report['display']			.=	'
		<tr>
			<td>'.$spacer.'Non-Current Liabilities</td>
			<td '.$subtotal_style.'>'.number_format(abs($subcategory_total),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
		<tr>
			<td>Total Liabilities</td>
			<td '.$subtotal_style.'>'.number_format(abs($total_balance),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-align:center;font-size:8px;">======================================================================================================================================================</td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		';
	}
}
{// Equity
	$equity_report['display']=	'
	<tr><td colspan="3">Stockholder\'s Equity</td></tr>
	';
	$equity_report['current_equity']=$equity_report['current_equity']='';
	$equity_accounts['current_equity']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '3___' AND code NOT LIKE '___0'");
	$equity_accounts['current_equity']		=	$equity_accounts['current_equity']->result_array();
	$equityctr								=	0;
	$subcategory_total						=	0;
	foreach($equity_accounts['current_equity'] as $current_equity) {
		$gl_accounts						=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code=".$current_equity['code']);
		$gl_accounts						=	$gl_accounts->result_array();
		$current_equity_balance				=	0;
		foreach($gl_accounts as $glctr=>$gl_account) {
			$account						=	$this->Accounting_model->getAccount($gl_account['id'], $daterange, $company_id);
			if(isset($_GET['gl_trace_mode'])) {
			if($_GET['gl_trace_mode']==1) {
				$equity_report['display']		.=	'
				<tr>
					<td>'.$spacer.$spacer.$spacer.$account['general_ledger_code'].'</td>
					<td '.$value_style.'>'.number_format(abs(($account['total_debit']-$account['total_credit'])),2).'</td>
				</tr>
				';
			}
			}
			$current_equity_balance			=	$current_equity_balance+($account['total_debit']-$account['total_credit']);
		}
		if($current_equity['code']==3237) { // adds hardcoded map of 71997901 and 71997903
			$income_and_expense_summary	=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code='7199' AND general_ledger_code='7901'");
			$opening_account_balance	=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code='7199' AND general_ledger_code='7903'");
			$income_and_expense_summary	=	(array)$income_and_expense_summary->row();
			$opening_account_balance	=	(array)$opening_account_balance->row();
			$income_and_expense_summary	=	$this->Accounting_model->getAccount($income_and_expense_summary['id'], $daterange, $company_id);
			$opening_account_balance	=	$this->Accounting_model->getAccount($opening_account_balance['id'], $daterange, $company_id);
			if(isset($_GET['gl_trace_mode'])) {
			if($_GET['gl_trace_mode']==1) {
				$equity_report['display']		.=	'
				<tr>
					<td>'.$spacer.$spacer.$spacer.$income_and_expense_summary['general_ledger_code'].'</td>
					<td '.$value_style.'>'.number_format((($income_and_expense_summary['total_debit']-$income_and_expense_summary['total_credit'])),2).'</td>
				</tr>
				';
				$equity_report['display']		.=	'
				<tr>
					<td>'.$spacer.$spacer.$spacer.$opening_account_balance['general_ledger_code'].'</td>
					<td '.$value_style.'>'.number_format((($opening_account_balance['total_debit']-$opening_account_balance['total_credit'])),2).'</td>
				</tr>
				';
			}
			}
			$current_equity_balance		+=	($income_and_expense_summary['total_debit']-$income_and_expense_summary['total_credit']);
			$current_equity_balance		+=	($opening_account_balance['total_debit']-$opening_account_balance['total_credit']);
		}

		

		$subcategory_total				=	$subcategory_total+$current_equity_balance;
		$equity_report['display']		.=	'
		<tr>
			<td>'.$spacer.$spacer.$current_equity['name'].'</td>
			<td '.$value_style.'>'.number_format(abs($current_equity_balance),2).'</td>
		</tr>
		';
	}
	unset($equity_accounts['current_equity']);
	if(!is_numeric($_GET['company']) || $_GET['company']==""){
		$company_id=NULL;
	}else{
		$company_id=$_GET['company'];
	}
	$statementofincome = $this->Accounting_model->getStatementOfIncome($start_report,$finish_report,$company_id);
	$net_income = $statementofincome['net_income'];
	//if($net_income>=0){//if net income is not loss then add to stock holder equity
		//$subcategory_total = $subcategory_total+$net_income;
		//$net_income_final = number_format($net_income,2);
	//}else{//if net income is loss then deduct to stock holder equity
	//	$subcategory_total = $subcategory_total-$net_income;
	//	$net_income_final = "(".number_format(abs($net_income),2).")";
	//}

	$total_balance	+=	$subcategory_total;

	$equity_report['display']			.=	'
	<tr>
		<td>'.$spacer.$spacer.'Net Income</td>
		<td '.$subtotal_style.'>'.number_format($net_income,2).'</td>
	</tr>
	<tr>
		<td>'.$spacer.'Total Stockholder\'s Equity</td>
		<td '.$subtotal_style.'>'.number_format(abs($subcategory_total)+$net_income,2).'</td>
	</tr>
	
	<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
	<tr>
		<td>Total Liabilities and Stockholder\'s Equity</td>
		<td '.$subtotal_style.'>'.number_format(abs($total_balance)+$net_income,2).'</td>
	</tr>
	<tr><td colspan="3" style="text-align:center;font-size:8px;">======================================================================================================================================================</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	';
}
$content	=	'
<div style="text-align:center;">
	<div style="font-size:20px; font-weight:600"><strong>'.$company->name.'</strong></div>
	<div style="font-size:15px; font-weight:600">
		Statement of Financial Position<br/>
		<span style="font-size:10px;">(In Philippine Peso)</span>
	</div>
</div>
	<table border="0" cellpadding="1" style="font-size:12px;">
		<tr>
			<td>&nbsp;</td>
			<td style="text-align:center;">'.date("j F Y",strtotime($daterange['start'])).' to '.date("j F Y",strtotime($daterange['finish'])).'</td>
		</tr>
		<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
		'.$asset_report['display'].'
		<tr><td colspan="3"></td></tr>
		'.$liabilities_report['display'].'
		<tr><td colspan="3"></td></tr>
		'.$equity_report['display'].'
	</table>
';


$data['orientation']		=	"P";
$data['pagetype']			=	"legal";
$data['title']				=	'Statement of Financial Position';
$data['content']			=	$content;

$this->load->view('pdf-container.php',$data);
?>