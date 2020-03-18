
<?php
function write_pin($fpdf, $pin) {
	$pin	=	str_split($pin);
	$fpdf->SetXY(32, 22);
	$fpdf->Write(0, $pin[0], 0, 'C');
	$fpdf->SetXY(38, 22);
	$fpdf->Write(0, $pin[1], 0, 'C');
	$fpdf->SetXY(48, 22);
	$fpdf->Write(0, $pin[2], 0, 'C');
	$fpdf->SetXY(55, 22);
	$fpdf->Write(0, $pin[3], 0, 'C');
	$fpdf->SetXY(61, 22);
	$fpdf->Write(0, $pin[4], 0, 'C');
	$fpdf->SetXY(68, 22);
	$fpdf->Write(0, $pin[5], 0, 'C');
	$fpdf->SetXY(74, 22);
	$fpdf->Write(0, $pin[6], 0, 'C');
	$fpdf->SetXY(81, 22);
	$fpdf->Write(0, $pin[7], 0, 'C');
	$fpdf->SetXY(88, 22);
	$fpdf->Write(0, $pin[8], 0, 'C');
	$fpdf->SetXY(94, 22);
	$fpdf->Write(0, $pin[9], 0, 'C');
	$fpdf->SetXY(101, 22);
	$fpdf->Write(0, $pin[10], 0, 'C');
	$fpdf->SetXY(112, 22);
	$fpdf->Write(0, $pin[11], 0, 'C');
}
function write_from_date($fpdf, $period_from) {
	$period_from_mont	=	date("m",strtotime($period_from));
	$period_from_mont	=	str_split($period_from_mont);
	$period_from_year	=	date("y",strtotime($period_from));
	$period_from_year	=	str_split($period_from_year);
	// echo "<pre>from"; print_r($period_from_year); echo "</pre>";
	$fpdf->SetXY(33, 65);
	$fpdf->Write(0, $period_from_mont[0], 0, 'C');
	$fpdf->SetXY(39, 65);
	$fpdf->Write(0, $period_from_mont[1], 0, 'C');
	$fpdf->SetXY(47, 65);
	$fpdf->Write(0, $period_from_year[0], 0, 'C');
	$fpdf->SetXY(53, 65);
	$fpdf->Write(0, $period_from_year[1], 0, 'C');
}
function write_to_date($fpdf, $period_to) {
	$period_to_mont	=	date("m",strtotime($period_to));
	$period_to_mont	=	str_split($period_to_mont);
	$period_to_year	=	date("y",strtotime($period_to));
	$period_to_year	=	str_split($period_to_year);
	// echo "<pre>to"; print_r($period_to_year); echo "</pre>";
	$fpdf->SetXY(66, 65);
	$fpdf->Write(0, $period_to_mont[0], 0, 'C');
	$fpdf->SetXY(72, 65);
	$fpdf->Write(0, $period_to_mont[1], 0, 'C');
	$fpdf->SetXY(79, 65);
	$fpdf->Write(0, $period_to_year[0], 0, 'C');
	$fpdf->SetXY(86, 65);
	$fpdf->Write(0, $period_to_year[1], 0, 'C');
}


$fpdf	=	new FPDF('P');
$fpdf->AddPage();

$fpdf->SetAutoPageBreak(true, 10);
$fpdf->SetFont('Times', '', 12);
$fpdf->Rect(0,0,150,100,'');
$fpdf->Image(WPATH.'assets/images/ph_premium_payment_slip.jpg', 0, 0, 0, 0, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);

write_pin($fpdf, $pin);

$fpdf->SetXY(47, 30);
$fpdf->Write(0, $business_name, 0, 'C');

$fpdf->SetXY(34, 37);
$fpdf->Write(0, $member_name, 0, 'C');

if($member_type=="OFW") {
	$fpdf->SetXY(35, 52);$fpdf->Write(0, 'X', 0, 'C');
}
if($member_type=="Voluntary") {
	$fpdf->SetXY(16, 52);$fpdf->Write(0, 'X', 0, 'C');
}
if($member_type=="Sponsor") {
	$fpdf->SetXY(49, 52);$fpdf->Write(0, 'X', 0, 'C');
}
if($member_type=="Private") {
	$fpdf->SetXY(68, 52);$fpdf->Write(0, 'X', 0, 'C');
}
if($member_type=="Government") {
	$fpdf->SetXY(84, 52);$fpdf->Write(0, 'X', 0, 'C');
}

write_from_date($fpdf, $period_from);
write_to_date($fpdf, $period_to);

$fpdf->SetXY(40, 80);$fpdf->Write(0, $amount_paid, 0, 'C');

// $fpdf->SetXY($x_date, $y_date);
// $fpdf->Write(0, $cheque_date, 0, 'C');
// $fpdf->SetXY($x_payee, $y_payee);
// if(strlen($payee) > 25) {$fpdf->SetFont('Times', '', 10);}
// $fpdf->Write(0, $payee, 0, 'C');
// if(strlen($payee) > 25) {$fpdf->SetFont('Times', '', 12);}
// $fpdf->SetXY($x_p_amt, $y_p_amt);
// $fpdf->Write(0, $peso_amount, 0, 'C');
// $fpdf->SetXY($x_p_wrd, $y_p_wrd);
// if(strlen($peso_word) > 33) {$fpdf->SetFont('Times', '', 10);}
// $fpdf->Write(0, $peso_word, 0, 'C');
// if(strlen($peso_word) > 33) {$fpdf->SetFont('Times', '', 12);}

$fpdf->Output();
?>