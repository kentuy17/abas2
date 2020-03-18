
<?php
$fpdf	=	new FPDF('P');
$fpdf->AddPage();

$fpdf->SetAutoPageBreak(true, 10);
$fpdf->SetFont('Times', '', 12);

$x_date		=	0;
$y_date		=	0;
$x_payee	=	0;
$y_payee	=	0;
$x_p_amt	=	0;
$y_p_amt	=	0;
$x_p_wrd	=	0;
$y_p_wrd	=	0;
if($cheque_type=="BPI") {
	$x_date		=	157;
	$y_date		=	14;
	$x_payee	=	35;
	$y_payee	=	20;
	$x_p_amt	=	165;
	$y_p_amt	=	22;
	$x_p_wrd	=	20;
	$y_p_wrd	=	26;
}
elseif($cheque_type=="EastWest") {
	$x_date		=	163;
	$y_date		=	3;
	$x_payee	=	35;
	$y_payee	=	11;
	$x_p_amt	=	163;
	$y_p_amt	=	11;
	$x_p_wrd	=	20;
	$y_p_wrd	=	16;
}
elseif($cheque_type=="EastWest-Arlyn") {
	$x_date		=	154;
	$y_date		=	12;
	$x_payee	=	35;
	$y_payee	=	21;
	$x_p_amt	=	163;
	$y_p_amt	=	19;
	$x_p_wrd	=	20;
	$y_p_wrd	=	25;
}
elseif($cheque_type=="EastWest-VS") {
	$x_date		=	155;
	$y_date		=	16;
	$x_payee	=	28;
	$y_payee	=	21;
	$x_p_amt	=	163;
	$y_p_amt	=	21;
	$x_p_wrd	=	20;
	$y_p_wrd	=	26;
}
elseif($cheque_type=="UnionBank-SV") {
	$x_date		=	150;
	$y_date		=	27;
	$x_payee	=	35;
	$y_payee	=	34;
	$x_p_amt	=	150;
	$y_p_amt	=	34;
	$x_p_wrd	=	20;
	$y_p_wrd	=	41;
}
elseif($cheque_type=="UnionBank-AV") {
	$x_date		=	160;
	$y_date		=	10;
	$x_payee	=	35;
	$y_payee	=	20;
	$x_p_amt	=	163;
	$y_p_amt	=	20;
	$x_p_wrd	=	20;
	$y_p_wrd	=	26;
}
elseif($cheque_type=="PNB") {
	$x_date		=	160;
	$y_date		=	8;
	$x_payee	=	35;
	$y_payee	=	21;
	$x_p_amt	=	163;
	$y_p_amt	=	18;
	$x_p_wrd	=	20;
	$y_p_wrd	=	26;
}
elseif($cheque_type=="Bangkok Bank") {
	$x_date		=	160;
	$y_date		=	10;
	$x_payee	=	35;
	$y_payee	=	18;
	$x_p_amt	=	163;
	$y_p_amt	=	19;
	$x_p_wrd	=	20;
	$y_p_wrd	=	23;
}


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

// $fpdf->AliasNbPages();
$fpdf->Output();
?>