<?php
// echo WPATH.'tcpdf'.DS.'tcpdf.php';
require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
// $this->load->library('Pdf'); // Let's begin.
$width = 330.2;
$height = 215.9;
if(isset($pagetype)){
	if($pagetype=="letter") {
		$width = 215.9;
		$height = 279.4;
	}
}
$pagelayout = array($width, $height); //  or array($height, $width)
$pdf = new TCPDF('L', PDF_UNIT, $pagelayout, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(' ');
$pdf->SetTitle(' ');
$pdf->SetSubject(' ');
$pdf->SetKeywords(' ');

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
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
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->SetFont('dejavusans', '', 10, '', true);
$pdf->AddPage();
// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
$previous_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:HTTP_PATH;
if(empty($_GET)) {
	$this->Abas->redirect($previous_page);
}
require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';
$column_width['date']		=	"60px";
$column_width['reference']	=	"65px";
$column_width['memo']		=	"300px";
$mainview				=	"pdf-container.php";
$company				=	(object)array("name"=>"Avega Group of Companies", "address"=>"","telephone_no"=>"", "fax_no"=>"");
$company_query=$department_query=$vessel_query=$contract_query="";
$business_unit_code=$department_account_code="00";
$company_account_code=$vessel_account_code="000";
$contract_account_code="0000";
if(!isset($_GET['dstart']) || !isset($_GET['dfinish'])) {
	$this->Abas->sysMsg("warnmsg", "No report date selected!");
	$this->Abas->redirect($previous_page);
}
$date_start		=	date("Y-m-d", strtotime($_GET['dstart']))." 00:00:00";
$date_finish	=	date("Y-m-d", strtotime($_GET['dfinish']))." 23:59:59";
if(!isset($_GET['journal_type'])) {
	$this->Abas->sysMsg("warnmsg", "No report type selected!");
	$this->Abas->redirect($previous_page);
}
if(isset($_GET['company'])) {
	if(is_numeric($_GET['company'])) {
		$company		=	$this->Abas->getCompany($_GET['company']);
		if($company) {
			$company_query			=	' AND company_id='.$company->id;
			$company_account_code	=	str_pad($company->id, 3, '0', STR_PAD_LEFT);
		}
	}
}
if(isset($_GET['department'])) {
	if(is_numeric($_GET['department'])) {
		$department			=	$this->Abas->getDepartment($_GET['department']);
		if($department) {
			$department_query			=	' AND department_id='.$department->id;
			$department_account_code	=	str_pad($department->accounting_code, 2, '0', STR_PAD_LEFT);
		}
	}
}
if(isset($_GET['vessel'])) {
	if(is_numeric($_GET['vessel'])) {
		$vessel			=	$this->Abas->getVessel($_GET['vessel']);
		if($vessel){
			$vessel_query			=	' AND vessel_id='.$vessel->id;
			$vessel_account_code	=	str_pad($vessel->id, 3, '0', STR_PAD_LEFT);
		}
	}
}
if(isset($_GET['contract'])) {
	if(is_numeric($_GET['contract'])) {
		$contract				=	$this->Abas->getContract($_GET['contract']);
		$contract_query			=	' AND contract_id='.$contract['id'];
		$contract_account_code	=	str_pad($contract['reference_no'], 4, '0', STR_PAD_LEFT);
	}
}
$entity				=	"Unknown Entity";
$column_names		=	array("created_on"=>"Unknown");
if($_GET['journal_type']=="general") {
	$entity						=	"JV";
	$jvsql						=	"SELECT * FROM ac_journal_vouchers WHERE posted_on>='".$date_start."' AND posted_on<='".$date_finish."' ".$company_query;
	$apvsql						=	"SELECT * FROM ac_ap_vouchers WHERE date_created>='".$date_start."' AND date_created<='".$date_finish."' ".$company_query;
	$issuancesql						=	"SELECT id,issue_date,control_number FROM inventory_issuance WHERE issue_date>='".$date_start."' AND issue_date<='".$date_finish."' ".$company_query;
	$jvdocuments				=	$this->db->query($jvsql);
	$apvdocuments				=	$this->db->query($apvsql);
	$issuancedocuments			=	$this->db->query($issuancesql);
	$jvdocuments				=	$jvdocuments->result_array();
	$apvdocuments				=	$apvdocuments->result_array();
	$issuancedocuments			=	$issuancedocuments->result_array();
	$documents					=	$jvdocuments;
	// merge apvdocuments and jvdocuments and issuancedocuments
	$jvctr						=	count($jvdocuments);
	if(!empty($apvdocuments)) {
		foreach($apvdocuments as $apv) {
			$jvctr++;
			$documents[$jvctr]	=	array(
										"id"=>$apv['id'],
										"posted_on"=>$apv['date_created'],
										"control_number"=>$apv['control_number'],
										"is_accounts_payable"=>true,
									);
		}
	}
	$issuancectr						=	count($documents);
	if(!empty($issuancedocuments)) {
		foreach($issuancedocuments as $msis) {
			$issuancectr++;
			$documents[$issuancectr]	=	array(
										"id"=>$msis['id'],
										"posted_on"=>$msis['issue_date'],
										"control_number"=>$msis['control_number'],
										"is_material_issuance"=>true,
									);
		}
	}
}
else {
	if($_GET['journal_type']=="purchase") {
		$entity						=	"RR";
		$reference_table			=	"inventory_deliveries";
		$column_names['created_on']	=	"tdate";
	}
	elseif($_GET['journal_type']=="sales") {
		$entity						=	"Statement of Account";
		$reference_table			=	"statement_of_accounts";
		$column_names['created_on']	=	"created_on";
		$documents					=	"Statement of account";
	}
	elseif($_GET['journal_type']=="cash receipt") {
		$entity						=	"Payment Reciept";
		$reference_table			=	"payments";
		$column_names['created_on']	=	"received_on";
		$documents					=	"reciepts";
	}
	elseif($_GET['journal_type']=="disbursement") {
		$entity						=	"CV";
		$reference_table			=	"ac_vouchers";
		$column_names['created_on']	=	"voucher_date";
	}
	else {
		$this->Abas->sysMsg("warnmsg","Invalid report type - ".$_GET['journal_type']);
		$this->Abas->redirect($previous_page);
	}
	$sql			=	"SELECT * FROM ".$reference_table." WHERE ".$column_names['created_on'].">='".$date_start."' AND ".$column_names['created_on']."<='".$date_finish."' ".$company_query." ORDER BY ".$column_names['created_on']." ASC";
	$documents		=	$this->db->query($sql);
	$documents			=	$documents->result_array();
}
$tablecontents	=	'';
if($documents && !isset($column_names['created_on'])) {
	$column_names['created_on']	=	"created_on";
}

if(!empty($documents)) {
	$grand_total_values	=	array('debit'=>0, 'credit'=>0);
	$header		=	'
		<div style="text-align:center;">
			<p style="font-size:10px">
			<div style="font-size:20px; font-weight:600"><strong>'.$company->name.'</strong></div>
			'.$company->address.'<br>
				'.(!empty($company->telephone_no)?'Tel. Number: '.$company->telephone_no:'').' '.(!empty($company->fax_no)?'Fax Number: '.$company->fax_no:'').'
			</p>
			<div style="font-size:18px; font-weight:600">'.ucwords($_GET['journal_type']).' Journal</div>
			<div>From '.date("j F Y",strtotime($date_start)).' to '.date("j F Y",strtotime($date_finish)).'</div>
		</div>
		<table border="0" cellpadding="1" style="font-size:10px;">
			<thead>
				<tr>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF; width:'.$column_width['date'].';">Date</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF; width:'.$column_width['reference'].';">Reference</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF; width:'.$column_width['memo'].';">Memo</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Account Code</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Account Title</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Debit</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Credit</th>
				</tr>
			</thead>
		</table>
		';
	$pdf->writeHTMLCell(0, 0, '', '', $header, 0, 1, 0, true, '', true);
	foreach($documents as $docctr=>$document) {
		$document_total				=	array("debit"=>0, "credit"=>0);
		if($_GET['journal_type']=="general") {
			if(isset($document['is_accounts_payable'])) {
				$entity				=	"APV";
				$reference_table	=	"ac_ap_vouchers";
			}elseif(isset($document['is_material_issuance'])){
				$entity				=	"MSIS";
				$reference_table	=	"inventory_issuance";
			}
			else {
				$entity				=	"JV";
				$reference_table	=	"ac_journal_vouchers";
			}
		}
		if($reference_table=="ac_vouchers") { // disbursement journal
			$payee		=	"";
			if(strtolower($document['payee_type'])=="supplier") {
				$payee	=	$this->db->query("SELECT name FROM suppliers WHERE id=".$document['payee']);
				$payee	=	(array)$payee->row();
				$payee	=	" for ".$payee['name'];
			}
			elseif(strtolower($document['payee_type'])=="employee") {
				$payee	=	$this->db->query("SELECT first_name, middle_name, last_name FROM hr_employees WHERE id=".$document['payee']);
				$payee	=	(array)$payee->row();
				$payee	=	" for ".$payee['last_name']." ".$payee['first_name']." ".$payee['middle_name'];
			}
			$description	=	"Check Number ".$document['check_num'].$payee." - ".$description;
		}
		$entries		=	$this->db->query("SELECT * FROM ac_transaction_journal WHERE reference_id=".$document['id']." AND reference_table='".$reference_table."' AND stat=1");
		$entries		=	$entries->result_array();
		if(!empty($entries)) {
			$first_entry	=	$this->Accounting_model->getJournalEntry($entries[0]['id']);
			//$posted_on		=	date("m-d-Y",strtotime($document[$column_names['created_on']]));
			$title			=	$entity." with Transaction Code ".$document['id'];
			$description	=	isset($entries[0]['remark'])?$entries[0]['remark']:$title;
			$total_values	=	array('debit'=>$first_entry['debit_amount'], 'credit'=>$first_entry['credit_amount']);
			if(!empty($entries)) {
				$tablerows	=	'';
				$first_entry						=	$this->Accounting_model->getJournalEntry($entries[0]['id']);
				foreach($entries as $entryctr=>$entry) {
					$entry							=	$this->Accounting_model->getJournalEntry($entry['id']);
					if($entry['transaction_id']==$first_entry['transaction_id']) {
						$posted_on		=	date("m/d/Y",strtotime($entry['created_on']));
						$document_total['debit']	=	$document_total['debit']+$entry['debit_amount'];
						$document_total['credit']	=	$document_total['credit']+$entry['credit_amount'];
						$total_values['debit']		=	$total_values['debit']+$entry['debit_amount'];
						$total_values['credit']		=	$total_values['credit']+$entry['credit_amount'];
						if($entryctr!=0) {
							$tablerows	.=	'<tr>';
							$tablerows	.=	'<td>'.$entry['account_code'].'</td>';
							$tablerows	.=	'<td>'.$entry['account_name'].'</td>';
							$tablerows	.=	'<td style="text-align:right;">'.($entry['debit_amount']!=0?number_format($entry['debit_amount'],2):'-').'</td>';
							$tablerows	.=	'<td style="text-align:right;">'.($entry['credit_amount']!=0?number_format($entry['credit_amount'],2):'-').'</td>';
							$tablerows	.=	'</tr>';
						}
					}
				}
				
			}
			$disbalanced	=	"";
			if(number_format($document_total['debit'],2)!=number_format($document_total['credit'],2)) $disbalanced="background-color:#FF0000;";
			$grand_total_values['debit']	=	$grand_total_values['debit']+$document_total['debit'];
			$grand_total_values['credit']	=	$grand_total_values['credit']+$document_total['credit'];
			$tablecontents	=	'<table border="0" cellpadding="1" style="font-size:10px; '.$disbalanced.'">';
			$tablecontents	.=	'<tr>';
			$tablecontents	.=	'<th rowspan="'.(count($entries)).'" style="text-align:center; width:'.$column_width['date'].';">'.$posted_on.'</th>';
			$tablecontents	.=	'<th rowspan="'.(count($entries)).'" style="text-align:center; width:'.$column_width['reference'].';">'.$entity.' #'.$document['control_number'].'</th>';
			$tablecontents	.=	'<td rowspan="'.(count($entries)).'" style=" width:'.$column_width['memo'].';">'.$description.'</td>';
			unset($entries[0]);
			$tablecontents	.=	'<td>'.$first_entry['account_code'].'</td>';
			$tablecontents	.=	'<td>'.$first_entry['account_name'].'</td>';
			$tablecontents	.=	'<td style="text-align:right;">'.($first_entry['debit_amount']!=0?number_format($first_entry['debit_amount'],2):'-').'</td>';
			$tablecontents	.=	'<td style="text-align:right;">'.($first_entry['credit_amount']!=0?number_format($first_entry['credit_amount'],2):'-').'</td>';
			$tablecontents	.=	'</tr>';
			$tablecontents	.=	$tablerows;
			
			$tablecontents	.=	'</table>';
			$pdf->writeHTMLCell(0, 0, '', '', $tablecontents, 0, 1, 0, true, '', true);
		}
		else {
			$tablecontents	=	"No entries found!";
		}
	}
	$footer	=	'<table border=0>';
	$footer	.=	'<tr>';
	$footer	.=	'<th style="width:'.$column_width['date'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['reference'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['memo'].';"></th>';
	$footer	.=	'<th></th>';
	$footer	.=	'<th></th>';
	$footer	.=	'<th style="font-size:10px; text-align:right;">'.number_format($grand_total_values['debit'],2).'</th>';
	$footer	.=	'<th style="font-size:10px; text-align:right;">'.number_format($grand_total_values['credit'],2).'</th>';
	$footer	.=	'</tr>';
	$footer	.=	'<tr>';
	$footer	.=	'<th style="width:'.$column_width['date'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['reference'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['memo'].';"></th>';
	$footer	.=	'<th></th>';
	$footer	.=	'<th></th>';
	$footer	.=	'<th colspan="2" style="text-align:right;">==============================</th>';
	$footer	.=	'</tr>';

	$footer	.=	'</table>';
	$pdf->writeHTMLCell(0, 0, '', '', $footer, 0, 1, 0, true, '', true);
}
else {
	$footer	.=	'<table><tr><th>No records found!</th></tr></table>';
}

while (ob_get_level())
ob_end_clean();
header("Content-Encoding: None", true);
// flush();
$pdf->Output('report.pdf', 'I');
?>