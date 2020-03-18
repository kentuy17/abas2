<?php
$width = 192;
$height = 96;
$pagelayout = array($width, $height);
$pdf = new TCPDF(L, PDF_UNIT, $pagelayout, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("Item: ".$item[0]['item_code']);

//sets visibility of header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->SetFont('dejavusans', '', 18, '', true);
// set style for barcode
$style = array(
    'padding' => '0',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);
 $html = "
        <h1>".$item[0]['item_code']."</h1>
        <p><b>".$item[0]['item_name'].", ".$item[0]['brand']." ".$item[0]['particular']."</b> (".$qr_data[0]->unit.")</p>
        <p>".$company->abbreviation." RR No.: ".$delivery[0]['id']." - ".date('Y/m/d',strtotime($delivery[0]['tdate']))."</p>";

$qty = $qr_data[0]->quantity;
if($qty>10){
    $qty = 10;
}
for($x=1;$x<=$qty;$x++){
    // add a page
    $pdf->AddPage();
    // QRCODE,H : QR-CODE Low error correction
    $pdf->write2DBarcode($qr_data[0]->item_id."-".$qr_data[0]->id, 'QRCODE,H', 10, 10, 150, 150, $style, 'N');
    $pdf->writeHTMLCell(0, 0, 75, 10, $html, 0, 1, 0, true, '', true);
}
ob_end_clean();
$pdf->Output('QR_Code.pdf', 'I');
?>