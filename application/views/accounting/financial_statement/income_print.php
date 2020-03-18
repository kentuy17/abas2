<?php

$previous_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:HTTP_PATH;

$company				=	(object)array("name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
$company_query=$department_query=$vessel_query=$contract_query=$company_id="";
if(isset($_GET['company'])) {
	if(is_numeric($_GET['company'])) {
		$company		=	$this->Abas->getCompany($_GET['company']);
		if($company) {
			$company_id				=	$company->id;
			$company_query			=	' AND company_id='.$company->id;
			$company_account_code	=	str_pad($company->id, 3, '0', STR_PAD_LEFT);
		}
	}
}
if(!isset($_GET['dstart1']) || !isset($_GET['dstart2']) || !isset($_GET['dfinish1']) || !isset($_GET['dfinish2'])) {
	$this->Abas->sysMsg("warnmsg", "Report date ranges not set!");
	$this->Abas->redirect($previous_page);
}
$daterange1	=	array("start"=>date("Y-m-d H:i:s", strtotime($_GET['dstart1'])), "finish"=>date("Y-m-d H:i:s", strtotime($_GET['dfinish1'])));
$daterange2	=	array("start"=>date("Y-m-d H:i:s", strtotime($_GET['dstart2'])), "finish"=>date("Y-m-d H:i:s", strtotime($_GET['dfinish2'])));
$financial_statement_accounts	=	$this->Accounting_model->getFinancialStatementClassifications(true);
if(empty($financial_statement_accounts)) {
	$this->Abas->sysMsg("errmsg", "No account classifications were found!");
	$this->Abas->redirect($previous_page);
}
$subtotal_style			=	'style="text-align:right;"';
$value_style			=	'style="text-align:right;"';
$title_style			=	'style=" font-weight:bold;"';
$spacer					=	'&nbsp; &nbsp; &nbsp;';
$report					=	'';
{ // Revenues
	$revenues_balance			=	array("range1"=>0, "range2"=>0);
	$revenues_report['revenues']=$revenues_report['display']='';
	$revenues_accounts['revenues']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code>=4141 AND code<=4149");
	$revenues_accounts['revenues']		=	$revenues_accounts['revenues']->result_array();
	$assetctr								=	0;
	$subcategory_total						=	array("range1"=>0, "range2"=>0);
	foreach($revenues_accounts['revenues'] as $revenues) {
		$gl_accounts						=	$this->db->query("SELECT id,financial_statement_code,general_ledger_code,name FROM ac_accounts WHERE financial_statement_code=".$revenues['code']);
		$gl_accounts						=	$gl_accounts->result_array();
		$revenues['range1']			=	array("balance"=>0);
		$revenues['range2']			=	array("balance"=>0);
		foreach($gl_accounts as $glctr=>$gl_account) {
			if($daterange1['start']!="1970-01-01 00:00:00" && $daterange1['finish']) {
				$start_report		=	date("Y-m-d",strtotime($daterange1['start']))." 00:00:00";
				$finish_report		=	date("Y-m-d",strtotime($daterange1['finish']))." 23:59:59";
				$date_range_query1	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
			}
			if($daterange1['start']!="1970-01-01 00:00:00" && $daterange2['finish']) {
				$start_report		=	date("Y-m-d",strtotime($daterange2['start']))." 00:00:00";
				$finish_report		=	date("Y-m-d",strtotime($daterange2['finish']))." 23:59:59";
				$date_range_query2	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
			}
			$values1				=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query1." AND coa_id=".$gl_account['id'].$company_query);
			$values2				=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query2." AND coa_id=".$gl_account['id'].$company_query);
			$values1				=	(array)$values1->row();
			$values2				=	(array)$values2->row();
			$revenues['range1']['balance']	=	(($revenues['range1']['balance']+($values1['total_debit']-$values1['total_credit']))*(-1));
			$revenues['range2']['balance']	=	(($revenues['range2']['balance']+($values2['total_debit']-$values2['total_credit']))*(-1));
			if(isset($_GET['gl_trace_mode'])) {
				if($_GET['gl_trace_mode']==1) {
					$revenues_report['display']			.=	'
					<tr>
						<td '.$subtotal_style.'>'.$gl_account['financial_statement_code'].'-'.$gl_account['general_ledger_code'].' '.$gl_account['name'].'</td>
						<td '.$subtotal_style.'>'.number_format(($values1['total_debit']-$values1['total_credit']),2).'</td>
						<td '.$subtotal_style.'>'.number_format(($values2['total_debit']-$values2['total_credit']),2).'</td>
					</tr>
					';
				}
			}
		}
		$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$revenues['range1']['balance'], "range2"=>$subcategory_total['range2']+$revenues['range2']['balance']);
	}
	unset($revenues_accounts['revenues']);
	$revenues_balance['range1']	+=	$subcategory_total['range1'];
	$revenues_balance['range2']	+=	$subcategory_total['range2'];
	$revenues_report['display']			.=	'
	<tr>
		<td '.$title_style.'>Revenues</td>
		<td '.$subtotal_style.'>'.number_format($subcategory_total['range1'],2).'</td>
		<td '.$subtotal_style.'>'.number_format($subcategory_total['range2'],2).'</td>
	</tr>
	';
}
{ // Direct Costs
	$direct_costs_balance			=	array("range1"=>0, "range2"=>0);
	$direct_costs_report['direct_costs']=$direct_costs_report['display']='';
	$direct_costs_accounts['direct_costs']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code>=5156 AND code<=5160");
	$direct_costs_accounts['direct_costs']		=	$direct_costs_accounts['direct_costs']->result_array();
	$assetctr								=	0;
	$subcategory_total						=	array("range1"=>0, "range2"=>0);
	foreach($direct_costs_accounts['direct_costs'] as $direct_costs) {
		$gl_accounts						=	$this->db->query("SELECT id,financial_statement_code,general_ledger_code,name FROM ac_accounts WHERE financial_statement_code=".$direct_costs['code']);
		$gl_accounts						=	$gl_accounts->result_array();
		$direct_costs['range1']			=	array("balance"=>0);
		$direct_costs['range2']			=	array("balance"=>0);
		foreach($gl_accounts as $glctr=>$gl_account) {
			if($daterange1['start']!="1970-01-01 00:00:00" && $daterange1['finish']) {
				$start_report		=	date("Y-m-d",strtotime($daterange1['start']))." 00:00:00";
				$finish_report		=	date("Y-m-d",strtotime($daterange1['finish']))." 23:59:59";
				$date_range_query1	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
			}
			if($daterange1['start']!="1970-01-01 00:00:00" && $daterange2['finish']) {
				$start_report		=	date("Y-m-d",strtotime($daterange2['start']))." 00:00:00";
				$finish_report		=	date("Y-m-d",strtotime($daterange2['finish']))." 23:59:59";
				$date_range_query2	=	" AND (posted_on>='".$start_report."' AND posted_on<='".$finish_report."') ";
			}
			$values1				=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query1." AND coa_id=".$gl_account['id'].$company_query);
			$values2				=	$this->db->query("SELECT SUM(debit_amount) AS total_debit, SUM(credit_amount) AS total_credit FROM ac_transaction_journal WHERE stat=1 ".$date_range_query2." AND coa_id=".$gl_account['id'].$company_query);
			$values1				=	(array)$values1->row();
			$values2				=	(array)$values2->row();
			$direct_costs['range1']['balance']	=	$direct_costs['range1']['balance']+($values1['total_debit']-$values1['total_credit']);
			$direct_costs['range2']['balance']	=	$direct_costs['range2']['balance']+($values2['total_debit']-$values2['total_credit']);
			if(isset($_GET['gl_trace_mode'])) {
				if($_GET['gl_trace_mode']==1) {
					$direct_costs_report['display']			.=	'
					<tr>
						<td '.$subtotal_style.'>'.$gl_account['financial_statement_code'].'-'.$gl_account['general_ledger_code'].' '.$gl_account['name'].'</td>
						<td '.$subtotal_style.'>'.number_format(($values1['total_debit']-$values1['total_credit']),2).'</td>
						<td '.$subtotal_style.'>'.number_format(($values2['total_debit']-$values2['total_credit']),2).'</td>
					</tr>
					';
				}
			}
		}
		$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$direct_costs['range1']['balance'], "range2"=>$subcategory_total['range2']+$direct_costs['range2']['balance']);
	}
	unset($direct_costs_accounts['direct_costs']);
	$direct_costs_balance['range1']	+=	$subcategory_total['range1'];
	$direct_costs_balance['range2']	+=	$subcategory_total['range2'];
	$direct_costs_report['display']			.=	'
	<tr>
		<td '.$title_style.'>Direct Cost</td>
		<td '.$subtotal_style.'>'.number_format($subcategory_total['range1'],2).'</td>
		<td '.$subtotal_style.'>'.number_format($subcategory_total['range2'],2).'</td>
	</tr>
	<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
	';
}
{ // Operating Expenses
	$operating_expenses_balance				=	array("range1"=>0, "range2"=>0);
	$operating_expenses_report['display']	=	'
	<tr><td colspan="3" '.$title_style.'>Operating Expenses</td></tr>
	';
	$operating_expenses_report['expenses']=$operating_expenses_report['expenses']='';
	$operating_expenses_accounts['expenses']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code>=6166 AND code<=6195 AND code<>6183");
	$operating_expenses_accounts['expenses']		=	$operating_expenses_accounts['expenses']->result_array();
	$equityctr								=	0;
	$subcategory_total						=	array("range1"=>0, "range2"=>0);
	foreach($operating_expenses_accounts['expenses'] as $operating_expense) {
		$gl_accounts						=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code=".$operating_expense['code']);
		$gl_accounts						=	$gl_accounts->result_array();
		$operating_expense['range1']			=	array("balance"=>0);
		$operating_expense['range2']			=	array("balance"=>0);
		foreach($gl_accounts as $glctr=>$gl_account) {
			$account['range1']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange1, $company_id);
			$account['range2']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange2, $company_id);
			$operating_expense['range1']['balance']	=	$operating_expense['range1']['balance']+($account['range1']['total_debit']-$account['range1']['total_credit']);
			$operating_expense['range2']['balance']	=	$operating_expense['range2']['balance']+($account['range2']['total_debit']-$account['range2']['total_credit']);
		}
		$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$operating_expense['range1']['balance'], "range2"=>$subcategory_total['range2']+$operating_expense['range2']['balance']);
		$display_name	=	strlen($operating_expense['name']) > 30 ? substr($operating_expense['name'],0,30)."..." : $operating_expense['name'];
		$operating_expenses_report['display']		.=	'
		<tr>
			<td>'.$spacer.$spacer.$display_name.'</td>
			<td '.$value_style.'>'.number_format($operating_expense['range1']['balance'],2).'</td>
			<td '.$value_style.'>'.number_format($operating_expense['range2']['balance'],2).'</td>
		</tr>
		';
	}
	unset($operating_expenses_accounts['expenses']);
	$operating_expenses_balance['range1']	+=	$subcategory_total['range1'];
	$operating_expenses_balance['range2']	+=	$subcategory_total['range2'];
	$operating_expenses_report['display']	.=	'
	<tr>
		<td>Sub-total</td>
		<td '.$subtotal_style.'>'.number_format($subcategory_total['range1'],2).'</td>
		<td '.$subtotal_style.'>'.number_format($subcategory_total['range2'],2).'</td>
	</tr>
	<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
	';
}
{ // Others
	$other_income_balance				=	array("range1"=>0, "range2"=>0);
	$other_income_report['display']	=	'
	<tr><td colspan="3" '.$title_style.'>Other Income/(Expense)</td></tr>
	';
	{ // Interest Expense
		$other_interest_expense_report['interest_expense']=$other_interest_expense_report['interest_expense']='';
		$other_interest_expense_accounts['interest_expense']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '6183'");
		$other_interest_expense_accounts['interest_expense']		=	$other_interest_expense_accounts['interest_expense']->result_array();
		$subcategory_total						=	array("range1"=>0, "range2"=>0);
		foreach($other_interest_expense_accounts['interest_expense'] as $other_interest_expense) {
			$gl_accounts						=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code=6183");
			$gl_accounts						=	$gl_accounts->result_array();
			$other_interest_expense['range1']			=	array("balance"=>0);
			$other_interest_expense['range2']			=	array("balance"=>0);
			foreach($gl_accounts as $glctr=>$gl_account) {
				$account['range1']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange1, $company_id);
				$account['range2']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange2, $company_id);
				$other_interest_expense['range1']['balance']	=	$other_interest_expense['range1']['balance']+($account['range1']['total_debit']-$account['range1']['total_credit']);
				$other_interest_expense['range2']['balance']	=	$other_interest_expense['range2']['balance']+($account['range2']['total_debit']-$account['range2']['total_credit']);
			}
			$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$other_interest_expense['range1']['balance'], "range2"=>$subcategory_total['range2']+$other_interest_expense['range2']['balance']);
			$display_name	=	strlen($other_interest_expense['name']) > 30 ? substr($other_interest_expense['name'],0,30)."..." : $other_interest_expense['name'];
			$other_income_report['display']		.=	'
			<tr>
				<td>'.$spacer.$spacer.$display_name.'</td>
				<td '.$value_style.'>'.number_format($other_interest_expense['range1']['balance'],2).'</td>
				<td '.$value_style.'>'.number_format($other_interest_expense['range2']['balance'],2).'</td>
			</tr>
			';
		}
		unset($other_interest_expense_accounts['interest_expense']);
		$other_income_balance['range1']	-=	$subcategory_total['range1'];
		$other_income_balance['range2']	-=	$subcategory_total['range2'];
	}
	{ // Other Income
		$other_income_report['income']=$other_income_report['income']='';
		$other_income_accounts['income']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '4155'");
		$other_income_accounts['income']		=	$other_income_accounts['income']->result_array();
		$subcategory_total						=	array("range1"=>0, "range2"=>0);
		foreach($other_income_accounts['income'] as $other_income) {
			$gl_accounts						=	$this->db->query("SELECT id FROM ac_accounts WHERE financial_statement_code=4155");
			$gl_accounts						=	$gl_accounts->result_array();
			$other_income['range1']			=	array("balance"=>0);
			$other_income['range2']			=	array("balance"=>0);
			foreach($gl_accounts as $glctr=>$gl_account) {
				$account['range1']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange1, $company_id);
				$account['range2']					=	$this->Accounting_model->getAccount($gl_account['id'], $daterange2, $company_id);
				$other_income['range1']['balance']	=	$other_income['range1']['balance']+($account['range1']['total_debit']-$account['range1']['total_credit']);
				$other_income['range2']['balance']	=	$other_income['range2']['balance']+($account['range2']['total_debit']-$account['range2']['total_credit']);
			}
			$subcategory_total					=	array("range1"=>$subcategory_total['range1']+$other_income['range1']['balance'], "range2"=>$subcategory_total['range2']+$other_income['range2']['balance']);
			// special case: include GL7900 if GL7900 is balanced in favor of debit
			$gl7900			=	array("range1"=>$this->Accounting_model->getAccount(289, $daterange1, $company_id),"range2"=>$this->Accounting_model->getAccount(289, $daterange2, $company_id));
			$gl7900['range1']['balance']	=	$gl7900['range1']['total_debit']-$gl7900['range1']['total_credit'];
			if($gl7900['range1']['balance']>0) {
				$other_income['range1']['balance']	=	$other_income['range1']['balance']+$gl7900['range1']['balance'];
			}
			$gl7900['range2']['balance']	=	$gl7900['range2']['total_debit']-$gl7900['range2']['total_credit'];
			if($gl7900['range2']['balance']>0) {
				$other_income['range2']['balance']	=	$other_income['range2']['balance']+$gl7900['range2']['balance'];
			}
			$display_name	=	strlen($other_income['name']) > 30 ? substr($other_income['name'],0,30)."..." : $other_income['name'];
			$other_income_report['display']		.=	'
			<tr>
				<td>'.$spacer.$spacer.$display_name.'</td>
				<td '.$value_style.'>'.number_format(abs($other_income['range1']['balance']),2).'</td>
				<td '.$value_style.'>'.number_format(abs($other_income['range2']['balance']),2).'</td>
			</tr>
			';
		}
		unset($other_income_accounts['income']);
		$other_income_balance['range1']	+=	$subcategory_total['range1'];
		$other_income_balance['range2']	+=	$subcategory_total['range2'];
	}
	{ // Other Expense //FOREX
		$other_expense		=	array("range1"=>0, "range2"=>0);
		$other_expense['range1'] = array("balance"=>0);
		$other_expense['range2'] = array("balance"=>0);
		$other_expense_report['expense']=$other_expense_report['expense']='';
		// special case: include GL7900 if GL7900 is balanced in favor of credit
		$gl7900			=	array("range1"=>$this->Accounting_model->getAccount(289, $daterange1, $company_id),"range2"=>$this->Accounting_model->getAccount(289, $daterange2, $company_id));
		$gl7900['range1']['balance']	=	$gl7900['range1']['total_debit']-$gl7900['range1']['total_credit'];
		if($gl7900['range1']['balance']<0) {
			$other_expense['range1']['balance']	=	$other_expense['range1']['balance']+$gl7900['range1']['balance'];
		}
		$gl7900['range2']['balance']	=	$gl7900['range2']['total_debit']-$gl7900['range2']['total_credit'];
		if($gl7900['range2']['balance']<0) {
			$other_expense['range2']['balance']	=	$other_expense['range2']['balance']+$gl7900['range2']['balance'];
		}
		$other_expense['name']	=	$gl7900['range1']['name'];
		$display_name	=	strlen($other_expense['name']) > 30 ? substr($other_expense['name'],0,30)."..." : $other_expense['name'];
		$other_income_report['display']		.=	'
		<tr>
			<td>'.$spacer.$spacer.'Other Expense</td>
			<td '.$value_style.'>'.number_format(abs($other_expense['range1']['balance']),2).'</td>
			<td '.$value_style.'>'.number_format(abs($other_expense['range2']['balance']),2).'</td>
		</tr>
		';
		$other_income_balance['range1']	-=	$subcategory_total['range1'];
		$other_income_balance['range2']	-=	$subcategory_total['range2'];
	}


	/////////////////////////////////////////////////
	//TEMPORARY FIX FOR INCOME CALCULATION 02262018//
	////////////////////////////////////////////////
	$net_income_before_tax_r1 = abs($other_income['range1']['balance']) + abs($other_expense['range1']['balance']) + ($revenues_balance['range1']-$direct_costs_balance['range1']-$operating_expenses_balance['range1']) - $other_interest_expense['range1']['balance'];

	$net_income_before_tax_r2 = abs($other_income['range2']['balance']) + abs($other_expense['range2']['balance']) + ($revenues_balance['range2']-$direct_costs_balance['range2']-$operating_expenses_balance['range2']) - $other_interest_expense['range2']['balance'];
	////////////////////////////////////////////////
	////////////////////////////////////////////////

	$other_income_report['display']	.=	'
	<tr>
		<td>'.$spacer.'Net Income Before Tax</td>
		<td '.$subtotal_style.'>'.number_format($net_income_before_tax_r1,2).'</td>
		<td '.$subtotal_style.'>'.number_format($net_income_before_tax_r2,2).'</td>
	</tr>
	<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
	';
}
{ // Provision for Income Tax
	$income_tax_balance				=	array("range1"=>0, "range2"=>0);
	$other_expense['range1']['balance'] =0;
	$other_expense['range2']['balance'] =0;
	$income_tax_report['display']	=	'
	<tr><td colspan="3" '.$title_style.'>Provision for Income Tax</td></tr>
	';
	{ // Current
		$tax_accounts['current']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '7196'");
		$tax_accounts['current']		=	$tax_accounts['current']->result_array();
		$subcategory_total						=	array("range1"=>0, "range2"=>0);
		// special case: gl7900 is current income tax account
		$gl7900			=	array("range1"=>$this->Accounting_model->getAccount(286, $daterange1, $company_id),"range2"=>$this->Accounting_model->getAccount(286, $daterange2, $company_id));
		$gl7900['range1']['balance']	=	$gl7900['range1']['total_debit']-$gl7900['range1']['total_credit'];
		//if($gl7900['range1']['balance']<0) {
			$other_expense['range1']['balance']	=	$other_expense['range1']['balance']+$gl7900['range1']['balance'];
		//}
		$gl7900['range2']['balance']	=	$gl7900['range2']['total_debit']-$gl7900['range2']['total_credit'];
		//if($gl7900['range2']['balance']<0) {
			$other_expense['range2']['balance']	=	$other_expense['range2']['balance']+$gl7900['range2']['balance'];
		//}
		$other_expense['name']	=	$gl7900['range1']['name'];
		$display_name	=	strlen($other_expense['name']) > 30 ? substr($other_expense['name'],0,30)."..." : $other_expense['name'];

		$income_tax_report['display']		.=	'
		<tr>
			<td>'.$spacer.$spacer.$display_name.'</td>
			<td '.$value_style.'>'.number_format($other_expense['range1']['balance'],2).'</td>
			<td '.$value_style.'>'.number_format($other_expense['range2']['balance'],2).'</td>
		</tr>
		';

		$income_tax_expense_current_r1 = $other_expense['range1']['balance'];
		$income_tax_expense_current_r2 = $other_expense['range2']['balance'];

		unset($tax_accounts['current']);
		$income_tax_balance['range1']	-=	$subcategory_total['range1'];
		$income_tax_balance['range2']	-=	$subcategory_total['range2'];
	}
	{ // Deferred
		$other_expense['range1']['balance'] =0;
		$other_expense['range2']['balance'] =0;
		$tax_accounts['current']		=	$this->db->query("SELECT * FROM ac_financial_statement_labels WHERE code LIKE '7196'");
		$tax_accounts['current']		=	$tax_accounts['current']->result_array();
		$subcategory_total						=	array("range1"=>0, "range2"=>0);
		// special case: GL7001 is deferred income tax account
		$gl7901			=	array("range1"=>$this->Accounting_model->getAccount(287, $daterange1, $company_id),"range2"=>$this->Accounting_model->getAccount(287, $daterange2, $company_id));
		$gl7901['range1']['balance']	=	$gl7901['range1']['total_debit']-$gl7901['range1']['total_credit'];
		//if($gl7901['range1']['balance']<0) {
			$other_expense['range1']['balance']	=	$other_expense['range1']['balance']+$gl7901['range1']['balance'];
		//}
		$gl7901['range2']['balance']	=	$gl7901['range2']['total_debit']-$gl7901['range2']['total_credit'];
		//if($gl7901['range2']['balance']<0) {
			$other_expense['range2']['balance']	=	$other_expense['range2']['balance']+$gl7901['range2']['balance'];
		//}
		$other_expense['name']	=	$gl7901['range1']['name'];
		$display_name	=	strlen($other_expense['name']) > 30 ? substr($other_expense['name'],0,30)."..." : $other_expense['name'];

		$income_tax_report['display']		.=	'
		<tr>
			<td>'.$spacer.$spacer.$display_name.'</td>
			<td '.$value_style.'>'.number_format($other_expense['range1']['balance'],2).'</td>
			<td '.$value_style.'>'.number_format($other_expense['range2']['balance'],2).'</td>
		</tr>
		';

		$income_tax_expense_deferred_r1 = $other_expense['range1']['balance'];
		$income_tax_expense_deferred_r2 = $other_expense['range2']['balance'];

		unset($tax_accounts['current']);
		$income_tax_balance['range1']	-=	$subcategory_total['range1'];
		$income_tax_balance['range2']	-=	$subcategory_total['range2'];
	}

	$income_tax_report['display']	.=	'
	<tr>
		<td></td>
		<td '.$subtotal_style.'>'.number_format($income_tax_expense_current_r1 + $income_tax_expense_deferred_r1,2).'</td>
		<td '.$subtotal_style.'>'.number_format($income_tax_expense_current_r2 + $income_tax_expense_deferred_r2,2).'</td>
	</tr>
	<tr><td colspan="3" style="text-decoration:underline;"><hr></td></tr>
	';
}
$content	=	'
<div style="text-align:center;">
	<h1>'.$company->name.'</h1>
	<h3>
		Statement of Income<br/>
		<span style="font-size:10px;">(In Philippine Peso)</span>
	</h3>
</div>
	<table border="0" cellpadding="1" style="width:100%;">
		<tr>
			<td>&nbsp;</td>
			<td style="text-align:right;">'.date("Y",strtotime($daterange1['finish'])).'</td>
			<td style="text-align:right;">'.date("Y",strtotime($daterange2['finish'])).'</td>
		</tr>
		<tr><td colspan="3" style="text-decoration:underline;"><hr/></td></tr>
		<tr><td colspan="3"></td></tr>
		'.$revenues_report['display'].'
		'.$direct_costs_report['display'].'
		<tr>
			<td '.$title_style.'>Gross Income</td>
			<td '.$subtotal_style.'>'.number_format(($revenues_balance['range1']-$direct_costs_balance['range1']),2).'</td>
			<td '.$subtotal_style.'>'.number_format(($revenues_balance['range2']-$direct_costs_balance['range2']),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-align:right;">====================================================================================================</td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		'.$operating_expenses_report['display'].'
		<tr>
			<td '.$title_style.'>Net Operating Income</td>
			<td '.$subtotal_style.'>'.number_format(($revenues_balance['range1']-$direct_costs_balance['range1']-$operating_expenses_balance['range1']),2).'</td>
			<td '.$subtotal_style.'>'.number_format(($revenues_balance['range2']-$direct_costs_balance['range2']-$operating_expenses_balance['range2']),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-align:right;">====================================================================================================</td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		'.$other_income_report['display'].'
		'.$income_tax_report['display'].'
		<tr>
			<td '.$title_style.'>Net Income</td>
			<td '.$subtotal_style.'>'.number_format(($net_income_before_tax_r1 - $income_tax_expense_current_r1 - $income_tax_expense_deferred_r1),2).'</td>
			<td '.$subtotal_style.'>'.number_format(($net_income_before_tax_r2 - $income_tax_expense_current_r2 - $income_tax_expense_deferred_r2),2).'</td>
		</tr>
		<tr><td colspan="3" style="text-align:right;">====================================================================================================</td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
			<td '.$title_style.'>Earnings Per Share</td>
			<td '.$subtotal_style.'>???</td>
			<td '.$subtotal_style.'>???</td>
		</tr>
		<tr><td colspan="3" style="text-align:right;">====================================================================================================</td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
	</table>
';

echo $content;
?>