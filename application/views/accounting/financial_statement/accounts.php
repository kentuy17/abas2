<?php
// $this->Mmm->debug($_POST);
######################################################
######################################################
#####                                            #####
#####   There is surely a better way to do this  #####
#####    but I haven't found or thought of one   #####
#####                                            #####
#####          BACKUP BEFORE EDITING             #####
#####                                            #####
#####                                            #####
#####               GOOD LUCK                    #####
#####                                            #####
#####                (sorry)                     #####
#####                                            #####
######################################################
######################################################
function chartOfAccountsParents($input) {
	/*
	 * $return_type may only be 'select' or 'table' (select returns the options inside a select tag used in forms, table to display the chart of accounts)
	 * $general_accounts are the highest/most shallow in the heirarchy (i think what is called the general ledger)
	 *
	 * the function will recursively walk through each child account to get all of them
	 *
	 */
	$ci =& get_instance();
	$company_code		=	'000';
	$department_code	=	'00';
	$vessel_code		=	'000';
	$contract_code		=	'0000';
	if(isset($_POST['company'])) {
		if(is_numeric($_POST['company'])) {
			$company			=	$ci->Abas->getCompany($_POST['company']);
			if($company) {
				$company_code	=	$company->accounting_code;
			}
		}
	}

	if(isset($_POST['department'])) {
		if(is_numeric($_POST['department'])) {
			$department				=	$ci->Abas->getDepartment($_POST['department']);
			if($department) {
				$department_code	=	$department->accounting_code;
			}
		}
	}

	if(isset($_POST['vessel'])) {
		if(is_numeric($_POST['vessel'])) {
			$vessel				=	$ci->Abas->getVessel($_POST['vessel']);
			if($vessel){
				$vessel_code	=	$vessel->accounting_code;
			}
		}
	}

	if(isset($_POST['contract'])) {
		if(is_numeric($_POST['contract'])) {
			$contract		=	$ci->Abas->getContract($_POST['contract']);
			$contract_code	=	$contract['reference_number'];
		}
	}

	$ret_table			=	'';
	$widths['code']		=	"200px";
	$widths['name']		=	"300px";
	$widths['debit']	=	"150px";
	$widths['credit']	=	"150px";
	$general_accounts	=	$ci->db->query('SELECT * FROM ac_account_xref WHERE parent_id=0');
	$general_accounts	=	$general_accounts->result_array();
	if(!empty($general_accounts)) {
		foreach($general_accounts as $ga_ctr=>$ga) {
			$ret_row	=	'';
			// $accdata	=	$ci->db->query('SELECT * FROM ac_accounts WHERE id='.$ga['child_id']);
			$accdata	=	$ci->Accounting_model->getAccount($ga['child_id']);
			$parent		=	$ci->db->query('SELECT * FROM ac_account_xref WHERE child_id='.$ga['child_id']);
			$children	=	$ci->db->query('SELECT * FROM ac_account_xref WHERE parent_id='.$ga['child_id']);
			$value		=	calculateNodeValue($_POST, $ga['child_id']);
			$debit_value	=	($value['root']['debit']+$value['children']['debit']);
			$credit_value	=	($value['root']['credit']+$value['children']['credit']);
			if(!empty($accdata)) {
				$parent		=	(array)$parent->row();
				$parent		=	$ci->Accounting_model->getAccount($parent['id']);
				$children	=	$children->result_array();
				$ret_row	.=	'<tr>';
				$ret_row	.=	'<td style="width:'.$widths['code'].';">';
				$ret_row	.=	$company_code.'00'.$department_code.$vessel_code.$contract_code.$accdata['financial_statement_code'].$accdata['general_ledger_code'];
				$ret_row	.=	'</td>';
				$ret_row	.=	'<td style="width:'.$widths['name'].';">';
				$ret_row	.=	$accdata['name'];
				$ret_row	.=	'</td>';
				$ret_row	.=	'<td style="text-align:right; width:'.$widths['debit'].'">';
				$ret_row	.=	number_format(($value['root']['debit']+$value['children']['debit']),2);
				$ret_row	.=	'</td>';
				$ret_row	.=	'<td style="text-align:right; width:'.$widths['credit'].'">';
				$ret_row	.=	number_format(($value['root']['credit']+$value['children']['credit']),2);
				$ret_row	.=	'</td>';
				$ret_row	.=	'</tr>';
				if($credit_value==0 && $debit_value==0) {
					unset($ret_row);
				}
				else {
					$ret_table	.=	$ret_row;
				}
				if(!empty($children)) {
					foreach($children as $child_ctr=>$c) {
						$ret_table	.=	chartOfAccountsWalk($input, $c['child_id'],'->');
					}
				}
			}
		}
	}
	$ret='<table border="1" cellpadding="1">';
		$ret.='<tr>';
			$ret.='<th style="width:'.$widths['code'].'; text-align:center;">Account Number</th>';
			$ret.='<th style="width:'.$widths['name'].'; text-align:center;">Account Name</th>';
			$ret.='<th style="width:'.$widths['debit'].'text-align:center;">Debit</th>';
			$ret.='<th style="width:'.$widths['credit'].'text-align:center;">Credit</th>';
		$ret.='</tr>';
		$ret.=$ret_table;
		$ret.='</table>';
	return $ret;
}
function chartOfAccountsWalk($input, $child_id, $add='->') {
	$ci =& get_instance();
	$company_code		=	'000';
	$department_code	=	'00';
	$vessel_code		=	'000';
	$contract_code		=	'0000';
	if(isset($_POST['company'])) {
		if(is_numeric($_POST['company'])) {
			$company			=	$ci->Abas->getCompany($_POST['company']);
			if($company) {
				$company_code	=	str_pad($company->accounting_code, 3, '0', STR_PAD_LEFT);
			}
		}
	}

	if(isset($_POST['department'])) {
		if(is_numeric($_POST['department'])) {
			$department				=	$ci->Abas->getDepartment($_POST['department']);
			if($department) {
				$department_code	=	str_pad($department->accounting_code, 2, '0', STR_PAD_LEFT);
			}
		}
	}

	if(isset($_POST['vessel'])) {
		if(is_numeric($_POST['vessel'])) {
			$vessel				=	$ci->Abas->getVessel($_POST['vessel']);
			if($vessel){
				$vessel_code	=	str_pad($vessel->accounting_code, 3, '0', STR_PAD_LEFT);
			}
		}
	}

	if(isset($_POST['contract'])) {
		if(is_numeric($_POST['contract'])) {
			$contract		=	$ci->Abas->getContract($_POST['contract']);
			$contract_code	=	str_pad($contract['reference_number'], 4, '0', STR_PAD_LEFT);
		}
	}
	$widths['code']		=	"200px";
	$widths['name']		=	"300px";
	$widths['debit']	=	"150px";
	$widths['credit']	=	"150px";
	$ret_table	=	'';
	$ret_row	=	'';
	// $accdata	=	$ci->db->query('SELECT * FROM ac_accounts WHERE id='.$child_id);
	$accdata	=	$ci->Accounting_model->getAccount($child_id);
	$parent		=	$ci->db->query('SELECT * FROM ac_account_xref WHERE child_id='.$child_id);
	$children	=	$ci->db->query('SELECT * FROM ac_account_xref WHERE parent_id='.$child_id);
	$value		=	calculateNodeValue($_POST, $child_id);
	$debit_value	=	($value['root']['debit']+$value['children']['debit']);
	$credit_value	=	($value['root']['credit']+$value['children']['credit']);

	$parent		=	(array)$parent->row();
	$parent		=	$ci->Accounting_model->getAccount($parent['id']);
	$children	=	$children->result_array();
	$ret_row	.=	'<tr>';
	$ret_row	.=	'<td style="width:'.$widths['code'].';">';
	$ret_row	.=	$company_code.'00'.$department_code.$vessel_code.$contract_code.$accdata['financial_statement_code'].$accdata['general_ledger_code'];
	$ret_row	.=	'</td>';
	$ret_row	.=	'<td style="width:'.$widths['name'].';">';
	$ret_row	.=	$accdata['name'];
	$ret_row	.=	'</td>';
	$ret_row	.=	'<td style="width:'.$widths['debit'].'; text-align:right;">';
	$ret_row	.=	number_format(($value['root']['debit']+$value['children']['debit']),2);
	$ret_row	.=	'</td>';
	$ret_row	.=	'<td style="width:'.$widths['credit'].'; text-align:right;">';
	$ret_row	.=	number_format(($value['root']['credit']+$value['children']['credit']),2);
	$ret_row	.=	'</td>';
	$ret_row	.=	'</tr>';
	if($credit_value==0 && $debit_value==0) {
		unset($ret_row);
	}
	else {
		$ret_table	.=	$ret_row;
		if(!empty($children)) {
			foreach($children as $child_ctr=>$c) {
				$ret_table.=	chartOfAccountsWalk($input, $c['child_id'],'->'.$add);
			}
		}
	}
	$ret=$ret_table;
	return $ret;
}
function calculateNodeValue($input, $account_id) {
	$ci =& get_instance();
	$id					=	$account_id;
	$ret				=	0;
	$company_query		=	'';
	$contract_query		=	'';
	$vessel_query		=	'';
	$department_query	=	'';
	$root				=	$ci->Accounting_model->getAccount($id);
	if ($root == null) {
		return 0;
	}
	if(isset($_POST['company'])) {
		if(is_numeric($_POST['company'])) {
			$company		=	$ci->Abas->getCompany($_POST['company']);
			if($company) {
				$company_query	=	' AND company_id='.$company->id;
			}
		}
	}

	if(isset($_POST['department'])) {
		if(is_numeric($_POST['department'])) {
			$department			=	$ci->Abas->getDepartment($_POST['department']);
			if($department) {
				$department_query	=	' AND department_id='.$department->id;
			}
		}
	}

	if(isset($_POST['vessel'])) {
		if(is_numeric($_POST['vessel'])) {
			$vessel			=	$ci->Abas->getVessel($_POST['vessel']);
			if($vessel){
				$vessel_query	=	' AND vessel_id='.$vessel->id;
			}
		}
	}

	if(isset($_POST['contract'])) {
		if(is_numeric($_POST['contract'])) {
			$contract		=	$ci->Abas->getContract($_POST['contract']);
			$contract_query	=	' AND contract_id='.$contract['id'];
		}
	}
	$temp	=	$ci->db->query('SELECT SUM(credit_amount) AS total_credit, SUM(debit_amount) AS total_debit FROM ac_transaction_journal WHERE coa_id='.$id.$company_query.$department_query.$vessel_query.$contract_query);
	$temp	=	(array)$temp->row();
	$value['root']['debit']			=	$temp['total_debit'];
	$value['root']['credit']		=	$temp['total_credit'];
	$value['children']['debit']		=	0;
	$value['children']['credit']	=	0;
	$children						=	$ci->Accounting_model->getAccountChildren($id);
	if($children) {
		foreach($children as $c) {
			$child = calculateNodeValue($input, $c['id']);
			$value['children']['debit']		=	$value['children']['debit'] + $child['root']['debit'] + $child['children']['debit'];
			$value['children']['credit']	=	$value['children']['credit'] + $child['root']['credit'] + $child['children']['credit'];
		}
	}
	$ret	=	$value;
	return $ret;
}
// echo chartOfAccountsParents($_POST);
$content	=	chartOfAccountsParents($_POST);
$pagetype	=	'letter';
$orientation=	'L';




//include(WPATH."application/views/pdf-container.php");
//*
$width = 330.2;
$height = 215.9;
if(isset($pagetype)){
	if($pagetype=='letter') {
		$width = 215.9;
		$height = 279.4;
	}
}
if(!isset($orientation)) {
	$orientation	=	'P';
}
if(!isset($content)) {
	$rand1		=	rand(200, 800);
	$rand2		=	rand(200, 800);
	$pic		=	'<img src="http://placekitten.com/'.$rand1.'/'.$rand2.'" class="center" width="'.$rand1.'" height="'.$rand2.'" />';
	$content	=	$pic;
}

$pagelayout = array($width, $height); //  or array($height, $width)
$pdf = new TCPDF($orientation, PDF_UNIT, $pagelayout, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle('');
$pdf->SetSubject('');
$pdf->SetKeywords('');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, 5, 5);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
// $pdf->SetFont('dejavusans', '', 6, '', true);
// $pdf->SetFont('helvetica', '', 6, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print

// Print text using writeHTMLCell()
// die($content);
$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('printable.pdf', 'I');

//*/
?>