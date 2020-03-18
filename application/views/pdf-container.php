<?php
$width = 330.2;
$height = 215.9;
if(isset($pagetype)){
	if($pagetype=="letter") {
		$width = 215.9;
		$height = 279.4;
	}
}
if(!isset($orientation)) {
	$orientation	=	"P";
}
if(isset($viewfile)) {
	$content = file_get_contents($viewfile, FILE_USE_INCLUDE_PATH);
}
if(!isset($content)) {
	$rand1		=	rand(200, 800);
	$rand2		=	rand(200, 800);
	$pic		=	"<img src='http://placekitten.com/".$rand1."/".$rand2."' class='center' width='".$rand1."' height='".$rand2."' />";
	$content	=	$pic;
}
if(!isset($title)) {
	$title = "";
}

$pagelayout = array($width, $height); //  or array($height, $width)
$pdf = new TCPDF($orientation, PDF_UNIT, $pagelayout, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle($title);
$pdf->SetSubject('');
$pdf->SetKeywords('');

//sets visibility of header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);

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
$pdf->SetFont('helvetica', '', 6, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
//$pdf->AddPage();


// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print

// Print text using writeHTMLCell()
// die($content);
//$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);

//Checks if $content is multi-pages
if(count($content)>1){
	for($ctr=0;$ctr<count($content);$ctr++){
		$pdf->AddPage();
		$pdf->writeHTMLCell(0, 0, '', '', $content[$ctr], 0, 1, 0, true, '', true);
	}
}
else{
	$pdf->AddPage();
	$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);

}


if(isset($watermark)){
	$num_pages = $pdf->getNumPages();
	for($ctr=1;$ctr<=$num_pages;$ctr++){
		// Simple watermark
		// This will set it to page one and lay over anything written before it on the first page
		$pdf->setPage($ctr);
		// Get the page width/height
		$myPageWidth = $pdf->getPageWidth();
		$myPageHeight = $pdf->getPageHeight();
		// Find the middle of the page and adjust.
		$myX = ( $myPageWidth / 2 ) - 75;
		$myY = ( $myPageHeight / 2 ) + 25;
		// Set the transparency of the text to really light
		$pdf->SetAlpha(0.5);
		// Rotate 45 degrees and write the watermarking text
		$pdf->StartTransform();
		$pdf->Rotate(45, $myX, $myY);
		$pdf->SetFont("aealarabiya", "", 125);
		$pdf->Text($myX, $myY,$watermark); 
		$pdf->StopTransform();
		// Reset the transparency to default
		$pdf->SetAlpha(1);
	}
}

if(isset($control_number)){
	$num_pages = $pdf->getNumPages();
	for($ctr=1;$ctr<=$num_pages;$ctr++){
		$pdf->setPage($ctr);
		$pdf->StartTransform();
		$pdf->SetFont("aealarabiya", "B", 9);
		$pdf->Text($pdf->getPageWidth()-50,5,$control_number); 
		$pdf->StopTransform();
	}
}

if(isset($signatories)){

	$pdf->StartTransform();	
	$pdf->writeHTML($signatories, true, false, true, false, '');
	$pdf->StopTransform();
	
}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
ob_end_clean();
$pdf->Output('printable.pdf', 'I');


?>