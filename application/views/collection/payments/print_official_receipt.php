<?php	
if(isset($payment)){

if($payment['vat_type']=='VATable'){
	$vatable_sales = $payment['vatable_amount'];
	$vat_exempt = "--";
	$vat_zero_rated="--";
	$vat_amount = $payment['tax_12_percent'];
	$vat = $payment['tax_12_percent'];

	$percentage = 0;
	if($payment['tax_2_percent']){
		$percentage = $percentage + 0.02;
	}
	if($payment['tax_5_percent']){
		$percentage = $percentage + 0.05;
	}
	if($payment['tax_1_percent']){
		$percentage = $percentage + 0.01;
	}
	$vat_sales_inclusive = $payment['net_amount']/(1-(1/1.12)*$percentage);

	$vat_sales_inclusive = $payment['net_amount']+$payment['discount']+$payment['other_deductions']+$payment['tax_5_percent'] + $payment['tax_2_percent'] + $payment['tax_1_percent']+$vat;
	$total_amount = $vat_sales_inclusive-$vat;
}elseif($payment['vat_type']=='VAT Exempted'){
	$vat_exempt = $payment['net_amount'];
	$vatable_sales = "--";
	$vat_zero_rated="--";
	$vat_amount = "--";
	$vat_sales_inclusive = "--";
	$vat = "--";
	$total_amount = $payment['net_amount']+$payment['discount']+$payment['other_deductions']+$payment['tax_5_percent'] + $payment['tax_2_percent'] + $payment['tax_1_percent']+$vat;;
}elseif($payment['vat_type']=='VAT Zero Rated'){
	$vat_zero_rated = $payment['net_amount'];
	$vat_exempt = "--";
	$vatable_sales="--";
	$vat_amount = "--";
	$vat_sales_inclusive = "--";
	$vat = "--";
	$total_amount = $payment['net_amount']+$payment['discount']+$payment['other_deductions']+$payment['tax_5_percent'] + $payment['tax_2_percent'] + $payment['tax_1_percent']+$vat;;
}

$discount = $payment['discount'];
$total_due = $total_amount - $discount;
$witholding_tax = $payment['tax_5_percent'] + $payment['tax_2_percent'] + $payment['tax_1_percent'];
$other_deductions = $payment['other_deductions']; 
$total_amount_due = $total_amount-$witholding_tax-$other_deductions-$discount;


$mode = $payment['mode_of_collection'];
$sr_citizen_id = $payment['senior_citizen_id'];
$pwd_id = $payment['person_with_disability_id'];

if($mode=="Cash"){
	$mode_Cash="<u>X</u>";
	$mode_Check="_";
}
elseif($mode=="Check"){
	$mode_Cash="_";
	$mode_Check="<u>X</u>";

}else{
	$mode_Cash="";
	$mode_Check="";
}

$content =  '
			<style type="text/css">
				 h1 { font-size:200%;text-align:center; }
				 h2 { font-size:150%; }	
				 h3 { font-size:100%; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:120%;}
				 th { font-weight:bold;font-size:150%;text-align:center}
				  p { text-align:left;font-size:150%; }
				.bt { font-weight:bold; text-align:right}
				.btx { font-weight:bold; text-align:right; font-size:190%}
				.tg {border-collapse:collapse;border-spacing:0;}
				.tg td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
				.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;}
				.tg .tg-yw4l{vertical-align:top}
				.tg .tg-9hbo{font-weight:bold;vertical-align:top;horizontal-align:left}
				.underline {text-decoration:underline;border-bottom: 2px;font-weight:bold}
				.doubleUnderline {text-decoration:underline;text-decoration-style:double;}
				.bot {vertical-align:bottom;}
				.wx {font-style: italic;}
			</style>
				<br><br>

				<table border="0" >
				<tr>
				<td width="270px">
					<div class="col-sm-12 col-md-12">
					<table border="1" cellpadding="2">
						<tr>
							
							<td align="center" width="260px" colspan="2"><b>IN SETTLEMENT OF THE FOLLOWING:</b></td>
			
						</tr>
						<tr>
							<td align="center" width="150px">DESCRIPTION</td>
							<td align="center" width="110px">AMOUNT</td>
						</tr>
						<tr>
							<td> VATable Sales</td>
							<td align="right" class="wx">'.number_format($vatable_sales,2,".",",").'</td>
						</tr>
						<tr>
							<td> VAT Exempt Sales</td>
							<td align="right" class="wx">'.number_format($vat_exempt,2,".",",").'</td>
						</tr>
						<tr>
							<td> VAT Zero Rated Sales</td>
							<td align="right" class="wx">'.number_format($vat_zero_rated,2,".",",").'</td>
						</tr>
						<tr>
							<td> VAT Amount</td>
							<td align="right" class="wx">'.number_format($vat_amount,2,".",",").'</td>
						</tr>
						<tr>
							<td> Total Sales (VAT Inclusive)</td>
							<td align="right" class="wx">'.number_format($vat_sales_inclusive,2,".",",").'</td>
						</tr>
						<tr>
							<td> Less: VAT</td>
							<td align="right" class="wx">'.number_format($vat,2,".",",").'</td>
						</tr>
						<tr>
							<td> Total Amount</td>
							<td align="right" class="wx">'.number_format($total_amount,2,".",",").'</td>
						</tr>
						<tr>
							<td> Less: SC/PWD Discount</td>
							<td align="right" class="wx">'.number_format($discount,2,".",",").'</td>
						</tr>
						<tr>
							<td> Total Due</td>
							<td align="right" class="wx">'.number_format($total_due,2,".",",").'</td>
						</tr>
						<tr>
							<td> Less: Witholding Tax</td>
							<td align="right" class="wx">'.number_format($witholding_tax,2,".",",").'</td>
						</tr>
						<tr>
							<td> Less: Other Deductions</td>
							<td align="right" class="wx">'.number_format($other_deductions,2,".",",").'</td>
						</tr>
						<tr>
							<td> Total Amount Due</td>
							<td align="right" class="wx">'.number_format($total_amount_due,2,".",",").'</td>
						</tr>
						<tr>
							<td> '.$mode_Check.' Check</td>
							<td> '.$mode_Cash.' Cash</td>
						</tr>
						<tr>
							<td>Sr. Citizen TIN/ID:</td>
							<td>'.$sr_citizen_id.'</td>
						</tr>
						<tr>
							<td>OSCA/PWD ID No.<br>'.$pwd_id.'</td>
							<td>Signature<br></td>
						</tr>
					</table>
			</div>

			</td>
			<td>
					<table border="0" cellpadding="1.5" >
								<tr>
									<td align="right" width="108px"><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo" width="95px">	
									</td>
									<td width="340px" colspan="5">
										<h2>'. $payment['company_name'] .'</h2>
						    			<text>'. $payment['company_address'].'</text>
						    			<text><br>'. $payment['company_contact'].'</text>
						    			<text><br>VAT Reg. TIN: '. $payment['company_tin'].'</text>
									</td>
								</tr>
								<tr>
									<td colspan="5" align="right"><h4>OR No.</h4></td>
									<td ><h4>'.$OR[0]->control_number.'</h4></td>
								</tr>
								<tr>
									<td colspan="6"><h2 align="left">  OFFICIAL RECEIPT</h2></td>
								</tr>
								<tr>
									<td colspan="4" align="right">Date:</td>
									<td colspan="2" tyle="border-bottom: solid 1px black" class="wx">'.date('F j, Y').'</td>
								</tr>
								<tr>
									<td align="center">Received from:</td>
									<td colspan="5" style="border-bottom: solid 1px black" class="wx">'.$payment['payor'].'</td>
								</tr>
								<tr>
									<td colspan="3" style="border-bottom: solid 1px black"></td>
									<td align="right" align="center">with TIN:</td>
									<td colspan="2" style="border-bottom: solid 1px black" class="wx">'.$payment['TIN'].'</td>
								</tr>
								<tr>
									<td align="center">and address at</td>
									<td colspan="4" style="border-bottom: solid 1px black" class="wx">'.$payment['address'].'</td>
									<td>engaged in</td>
								</tr>
								<tr>
									<td align="center">Business Style of</td>
									<td colspan="4" style="border-bottom: solid 1px black" class="wx">'.$payment['business_style'].'</td>
									<td>the sum of</td>
								</tr>
								<tr>
									<td colspan="6" style="border-bottom: solid 1px black" align="center" class="wx">'.$this->Mmm->numberToWordsWithCents($payment['net_amount']).'</td>
								</tr>
								<tr>
						
									<td style="border-bottom: solid 1px black" colspan="3" align="center" class="wx">PHP '.number_format($payment['net_amount'],2,".",",").'</td>
									<td align="left" colspan="3" >in partial/full payment for:</td>
								</tr>		
								<tr>
									<td style="border-bottom: solid 1px black" colspan="6" class="wx">'.$payment['particulars'].'</td>
								</tr>
								<tr>
									<td colspan="3"></td>
									<td align="center" colspan="3"><br><br><br>________________________________</td>
								</tr>
								<tr>
									<td colspan="3"></td>
									<td align="center" colspan="3">Cashier/Authorized Representative</td>
								</tr>
							</table>
			</td>
			</tr>
			<table>
				';

$content = $content . 'Customer\'s Copy<div><hr style=\"border: 1px dashed black;\"></div>' . $content . 'Accounting Dept. Copy<div><hr style=\"border: 1px dashed black;\"></div>' . $content. 'Cashier\'s Copy<div><hr style=\"border: 1px dashed black;\" /></div>' ;

$data['orientation']	=	"P";
$data['pagetype']		=	"legal";
$data['title']			=	"Official Receipt - Control No." . $OR[0]->control_number;
$data['content']		=	$content;

$this->load->view('pdf-container.php',$data);
}

?>