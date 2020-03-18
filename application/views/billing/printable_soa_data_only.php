<?php
// $this->Mmm->debug($soa);
$fpdf	=	new FPDF('P');
$fpdf->AddPage();

$fpdf->SetAutoPageBreak(true, 10);
$fpdf->SetFont('Times', '', 12);
$x_client_name		=	30;
$y_client_name		=	40;
$fpdf->SetXY($x_client_name, $y_client_name);
$fpdf->Write(0, $soa['client']['company'], 0, 'C');

$x_client_tin		=	30;
$y_client_tin		=	45;
$fpdf->SetXY($x_client_tin, $y_client_tin);
$fpdf->Write(0, $soa['client']['tin_no'], 0, 'C');

$x_client_address	=	30;
$y_client_address	=	50;
$fpdf->SetXY($x_client_address, $y_client_address);
$fpdf->Write(0, $soa['client']['address'], 0, 'C');

$x_date				=	150;
$y_date				=	40;
$fpdf->SetXY($x_date, $y_date);
$fpdf->Write(0, date("j F Y",strtotime($soa['created_on'])), 0, 'C');

$x_reference_number	=	150;
$y_reference_number	=	45;
$fpdf->SetXY($x_reference_number, $y_reference_number);
$fpdf->Write(0, $soa['reference_number'], 0, 'C');

$x_particular_base	=	20;
$y_particular_base	=	100;
$fpdf->SetXY($x_particular_base, $y_particular_base);
$fpdf->Write(0, $soa['details'][0]['particular'], 0, 'C');

$x_payment_base		=	120;
$y_payment_base		=	100;
$fpdf->SetXY($x_payment_base, $y_payment_base);
$fpdf->Write(0, $soa['details'][0]['payment'], 0, 'C');

$x_charges_base		=	140;
$y_charges_base		=	100;
$fpdf->SetXY($x_charges_base, $y_charges_base);
$fpdf->Write(0, $soa['details'][0]['charges'], 0, 'C');

$x_balance_base		=	160;
$y_balance_base		=	100;
$fpdf->SetXY($x_balance_base, $y_balance_base);
$fpdf->Write(0, $soa['details'][0]['balance'], 0, 'C');

/*foreach($soa['details'] as $ctr=>$detail) {
	$fpdf->SetXY($x_reference_number, $y_reference_number);
	$fpdf->Write(0, $detail['particular'], 0, 'C');
}*/



/*
$fpdf->SetXY($x_date, $y_date);
$fpdf->Write(0, $cheque_date, 0, 'C');
$fpdf->SetXY($x_payee, $y_payee);
if(strlen($payee) > 25) {$fpdf->SetFont('Times', '', 10);}
$fpdf->Write(0, $payee, 0, 'C');
if(strlen($payee) > 25) {$fpdf->SetFont('Times', '', 12);}
$fpdf->SetXY($x_p_amt, $y_p_amt);
$fpdf->Write(0, $peso_amount, 0, 'C');
$fpdf->SetXY($x_p_wrd, $y_p_wrd);
if(strlen($peso_word) > 33) {$fpdf->SetFont('Times', '', 10);}
$fpdf->Write(0, $peso_word, 0, 'C');
if(strlen($peso_word) > 33) {$fpdf->SetFont('Times', '', 12);}
//*/
// $fpdf->AliasNbPages();
$fpdf->Output();
?>