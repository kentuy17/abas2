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
$column_width['reference']	=	"100px";
$column_width['memo']		=	"190px";
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
$daterange		=	array("start"=>$date_start, "finish"=>$date_finish);
$account		=	$this->Accounting_model->getAccount($_GET['account'],$daterange);
$this->Mmm->debug($account);
if($account==false) {
	$this->Abas->sysMsg("warnmsg", "Account not found!");
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
$tablecontents	=	'';
$sql			=	"SELECT * FROM ac_transaction_journal WHERE coa_id='".$account['id']."' AND posted_on>='".$date_start."' AND posted_on<='".$date_finish."' ".$company_query." ".$department_query." ".$vessel_query." ".$contract_query." ORDER BY posted_on ASC";
$entries		=	$this->db->query($sql);
if(!empty($entries)) {
	$grand_total_values	=	array('debit'=>0, 'credit'=>0);
	$header		=	'
		<div style="text-align:center;">
			<p style="font-size:10px">
			<div style="font-size:20px; font-weight:600"><strong>'.$company->name.'</strong></div>
			'.$company->address.'<br>
				'.(!empty($company->telephone_no)?'Tel. Number: '.$company->telephone_no:'').' '.(!empty($company->fax_no)?'Fax Number: '.$company->fax_no:'').'
			</p>
			<div style="font-size:18px; font-weight:600">'.$account['name'].'</div>
			<div>From '.date("j F Y",strtotime($date_start)).' to '.date("j F Y",strtotime($date_finish)).'</div>
		</div>
		<table border="0" cellpadding="1" style="font-size:10px;">
			<thead>
				<tr>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF; width:'.$column_width['date'].';">Date</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF; width:'.$column_width['reference'].';">Control Number</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF; width:'.$column_width['reference'].';">Transaction Code</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF; width:'.$column_width['memo'].';">Memo</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Other Party</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Department</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Vessel</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Account Code</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Debit</th>
					<th style="font-size:12px; text-align:center; background-color:#000; color:#FFF;">Credit</th>
				</tr>
			</thead>
		</table>
		';
	$pdf->writeHTMLCell(0, 0, '', '', $header, 0, 1, 0, true, '', true);
	$entries			=	$entries->result_array();
	$total_values		=	array('debit'=>$entry['debit_amount'], 'credit'=>$entry['credit_amount']);
	foreach($entries as $docctr=>$entry) {
		$entry			=	$this->Accounting_model->getJournalEntry($entry['id']);
		if($entry['stat']==1) {
			$is_soa				=	false;
			$is_payment			=	false;
			$is_receiving		=	false;
			$other_party		=	array("po_control_number"=>"", "supplier_name"=>"", "client_name"=>"", "po_id"=>"");
			$document_table		=	$entry['reference_table'];
			$document_table		=	$entry['reference_table'];
			$document			=	$this->db->query("SELECT * FROM ".$entry['reference_table']." WHERE id=".$entry['reference_id']);
			$document			=	(array)$document->row();
			if($entry['reference_table']=="ac_journal_vouchers") $document_table	=	"Journal Voucher";
			if($entry['reference_table']=="ac_vouchers") {
				$document_table		=	"Check Voucher";
				$is_payment			=	true;
				$document_table		=	"Check Voucher";
				$is_payment			=	true;
				$other_party		=	"Check number: ".$document['check_num'];
				if(strtolower($document['payee_type'])=="supplier") {
					if($document['payee']<>''){
						$payee			=	$this->db->query("SELECT name FROM suppliers WHERE id=".$document['payee']);
						$payee			=	(array)$payee->row();
						$other_party	.=	" for ".$payee['name'];
					}
				}
				elseif(strtolower($document['payee_type'])=="employee") {
					if($document['payee']<>''){
						$payee			=	$this->db->query("SELECT first_name, middle_name, last_name FROM hr_employees WHERE id=".$document['payee']);
						$payee			=	(array)$payee->row();
						$other_party	.=	" for ".$payee['last_name']." ".$payee['first_name']." ".$payee['middle_name'];
					}
				}
			}
			if($entry['reference_table']=="ac_ap_vouchers") {
				$document_table	=	"Accounts Payable Voucher";
				$is_delivery	=	true;
				$other_party_sql=	$this->db->query("SELECT s.name AS supplier_name, po.control_number AS po_control_number, po.id AS po_id FROM ac_transaction_journal AS e JOIN ac_ap_vouchers AS apv ON apv.id=e.reference_id JOIN inventory_po AS po ON apv.po_no=po.id JOIN suppliers AS s ON po.supplier_id=s.id WHERE e.id=".$entry['id']);
				$other_party	=	(array)$other_party_sql->row();
				$links			=	'<li><a href="'.HTTP_PATH.'purchasing/purchase_order/view/'.$other_party['po_id'].'" data-toggle="modal" data-target="#modalDialog"> Purchase Order</a></li>';
				$other_party	=	"PO# ".$other_party['po_control_number']." for ".$other_party['supplier_name'];
			}
			if($entry['reference_table']=="inventory_deliveries") {
				$document_table	=	"Receiving Report";
				$is_delivery	=	true;
				$other_party_sql=	$this->db->query("SELECT s.name AS supplier_name, d.po_no AS po_control_number FROM ac_transaction_journal AS e JOIN inventory_deliveries AS d ON d.id=e.reference_id JOIN suppliers AS s ON soa.id=d.supplier_id WHERE e.id=".$entry['id']);
				$other_party	=	(array)$other_party_sql->row();
				$other_party	=	"PO# ".$other_party['po_control_number']." for ".$other_party['supplier_name'];
			}
			if($entry['reference_table']=="statement_of_accounts") {
				$is_soa			=	true;
				$document_table	=	"Statement of Account";
				$other_party_sql=	$this->db->query("SELECT c.company AS client_name FROM ac_transaction_journal AS e JOIN statement_of_accounts AS soa ON soa.id=e.reference_id JOIN clients AS c ON c.id=soa.client_id WHERE e.id=".$entry['id']);
				$other_party	=	(array)$other_party_sql->row();
				$other_party	=	$other_party['client_name'];
			}
			if($entry['reference_table']=="payments") {
				$is_payment		=	true;
				$document_table	=	"Payment";
				$other_party_sql=	$this->db->query("SELECT c.company AS client_name FROM ac_transaction_journal AS e JOIN statement_of_accounts AS soa ON soa.id=e.reference_id JOIN clients AS c ON c.id=soa.client_id WHERE e.id=".$entry['id']);
				$other_party	=	(array)$other_party_sql->row();
				if(isset($other_party['client_name'])) {
					$other_party	=	$other_party['client_name'];
				}
			}
			$posted_on		=	date("m/d/Y",strtotime($entry['posted_on']));
			$title			=	$document." with control number ".$document['control_number'];
			$description	=	isset($entry['remark'])?$entry['remark']:$title;
			$total_values['debit']	=	$total_values['debit']+$entry['debit_amount'];
			$total_values['credit']	=	$total_values['credit']+$entry['credit_amount'];
			$balance		=	$total_values['debit']-$total_values['credit'];
			$tablecontents	=	'<table border="0" cellpadding="1" style="font-size:10px;">';
			$tablecontents	.=	'<tr>';
			$tablecontents	.=	'<th style="text-align:center; width:'.$column_width['date'].';">'.$posted_on.'</th>';
			$tablecontents	.=	'<th style="text-align:center; width:'.$column_width['reference'].';">'.$document_table.' '.(($document_table=="Check Voucher")?$document['voucher_number']:$document['control_number']).'</th>';
			$tablecontents	.=	'<th style="text-align:center; width:'.$column_width['reference'].';">'.$document_table.' '.$entry['reference_id'].'</th>';
			$tablecontents	.=	'<td style=" width:'.$column_width['memo'].';">'.$description.'</td>';
			$tablecontents	.=	!is_array($other_party)?'<td>'.$other_party.'</td>':'<td>-</td>';
			$tablecontents	.=	'<td>'.$entry['department']['name'].'</td>';
			$tablecontents	.=	'<td>'.$entry['vessel']['name'].'</td>';
			$tablecontents	.=	'<td>'.$entry['account_code'].'</td>';
			$tablecontents	.=	'<td style="text-align:right;">'.($entry['debit_amount']!=0?number_format($entry['debit_amount'],2):'-').'</td>';
			$tablecontents	.=	'<td style="text-align:right;">'.($entry['credit_amount']!=0?number_format($entry['credit_amount'],2):'-').'</td>';
			$tablecontents	.=	'</tr>';
			$pdf->writeHTMLCell(0, 0, '', '', $tablecontents, 0, 1, 0, true, '', true);
		}
	}
	$footer	=	'<table border=0>';
	$footer	.=	'<tr>';
	$footer	.=	'<th style="width:'.$column_width['date'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['reference'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['reference'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['memo'].';"></th>';
	$footer	.=	'<th></th>';
	$footer	.=	'<th></th>';
	$footer	.=	'<th></th>';
	$footer	.=	'<th></th>';
	$footer	.=	'<th style="font-size:10px; text-align:right;">'.number_format($total_values['debit'],2).'</th>';
	$footer	.=	'<th style="font-size:10px; text-align:right;">'.number_format($total_values['credit'],2).'</th>';
	$footer	.=	'</tr>';
	$footer	.=	'<tr>';
	$footer	.=	'<th style="width:'.$column_width['date'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['reference'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['reference'].';"></th>';
	$footer	.=	'<th style="width:'.$column_width['memo'].';"></th>';
	$footer	.=	'<th colspan="6" style="text-align:right;">==========================</th>';
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