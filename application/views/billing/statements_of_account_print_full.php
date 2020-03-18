<?php

	$grandtotal = 0;
	$total_deductions = 0;
	$grandtotal_tax = 0;
	$tail_end_computation =0;
	$amount = 0;
	$totalbags = 0;
	$totalweight = 0;
	$colspan = 0;
	$colspanX = 0;

	$detailtable	=	"<p>No details found!</p>";
	
	if(!empty($soa['details'])) {
		$detailtable	=	'<style type="text/css">
									.rt td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
									.rt th{text-align:center;font-family:Arial, sans-serif;font-size:9px;padding:5px 5px;}
									 h1 { font-size:250%;text-align:center; }
									 h2,h3 { text-align:center; }	
									 h5 span { border-bottom: double 3px; }
									 p { text-align:left;font-size:150%; }
									.tg  {border-collapse:collapse;border-spacing:0;}
									.tg td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
									.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;}
									.tg .tg-yw4l{vertical-align:top}
									.tg .tg-9hbo{font-weight:bold;vertical-align:top;horizontal-align:left}
									.underline {text-decoration:underline;border-bottom: 2px;font-weight:bold}
									.doubleUnderline {text-decoration:underline;text-decoration-style:double;}
									.bot {vertical-align:bottom;}
							</style>';
		
		$detailtable	.= "<table border=\"1\" cellpadding=\"3\" class=\"rt\" width=\"100%\">";

		$contenttitle = "Particulars:";
		$subtotal = null;
		$note = "<br><br><br><br>";
		
		if($soa['type']=="General") {

			$colspanX =6;

			$detailtable	.=	"<thead><tr>";
				$detailtable	.=	"<th style=\"width:223px\">PARTICULAR</th>";
				$detailtable	.=	"<th style=\"width:85px\">QUANTITY</th>";
				$detailtable	.=	"<th style=\"width:83px\">UOM</th>";
				$detailtable	.=	"<th style=\"width:83px\">RATE</th>";
				$detailtable	.=	"<th style=\"width:83px\">PAYMENT</th>";
				$detailtable	.=	"<th style=\"width:83px\">CHARGES</th>";
				$detailtable	.=	"<th style=\"width:83px\">BALANCE</th>";
			$detailtable	.=	"</tr></thead>";

			$taxes = $this->Billing_model->getSOAAmount($soa['type'],$soa['id']);

			foreach($soa['details'] as $ctr=>$detail) {

				$quantity = "-";
				$unit_of_measurement = "-";
				$rate = "-";
				$payment = "-";
				$charges = "-";
				if($detail['quantity']>0){ 
						$quantity = number_format($detail['quantity'],4,'.',',');
				}
				if($detail['unit_of_measurement']!=""){ 
						$unit_of_measurement = $detail['unit_of_measurement'];
				}
				if($detail['rate']>0){ 
						$rate = number_format($detail['rate'],3,'.',',');
				}
				if($detail['payment']>0){
						$payment = number_format($detail['payment'],2,'.',',');
				}
				if($detail['charges']>0){
						$charges = number_format($detail['charges'],2,'.',',');
				}
		
				$detailtable	.=	"<tbody><tr>";
					$detailtable	.=	"<td style=\"width:223px\">".strtoupper($detail['particular'])."</td>";
					$detailtable	.=	"<td style=\"width:85px\" align=\"right\">".$quantity."</td>";
					$detailtable	.=	"<td style=\"width:83px\" align=\"right\">".$unit_of_measurement."</td>";
					$detailtable	.=	"<td style=\"width:83px\" align=\"right\">".$rate."</td>";
					$detailtable	.=	"<td style=\"width:83px\" align=\"right\">".$payment."</td>";
					$detailtable	.=	"<td style=\"width:83px\" align=\"right\">".$charges."</td>";
					$detailtable	.=	"<td style=\"width:83px\" align=\"right\">".number_format($detail['balance'],2,'.',',')."</td>";
				$detailtable	.=	"</tr></tbody>";
				
			}

			$grandtotal = $detail['balance'];

			$notedby ="";

			if($_SESSION['abas_login']['user_location']=="Makati"){
				//Prepared by: Melanie/ Checked by: Maam Juliet / Noted by: Maam  Arlyn/ Sir Tony
				$preparedby = "<h4 text-align:left;><u>" . strtoupper($_SESSION['abas_login']['fullname']) . "</u></h4><br>Accounting Staff";
				$checkedby = "<h4 text-align:left;><u>____________________________</u></h4><br>Accounting Staff";
				$certifiedby = "<h4 text-align:left;><u>STEPHEN ALEXANDER P. VEGA</u></h4><br>Executive Assistant to SVP-Maketing and Cargo Operations";
				
				if($soa['services']=="Trucking" || $soa['services']=="Handling"){
					$approvedby = "<h4 text-align:left;><u>JACKIE ROSE REGIS</u></h4><br>General Manager";
				}else{
					$approvedby = "<h4 text-align:left;><u>ARLYN CAGADAS</u></h4><br>General Manager";
				}
				
			}
			else{

				$preparedby = "<h4 text-align:left;><u>" . strtoupper($_SESSION['abas_login']['fullname']) . "</u></h4><br>Accounting Staff";
				$checkedby = "<h4 text-align:left;><u>JESSA M. ROMEO</u></h4><br>Accounting Staff";
				if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
					$certifiedby = "<h4 text-align:left;><u>LORVINA A. TABOADA</u></h4><br>Operations Manager";
				}else{
					$certifiedby = "<h4 text-align:left;><u>CHRISTE E. BUTANAS</u></h4><br>Accounting Supervisor";
					if($soa['company']->name=="Avega Bros. Trucking Services Corp."){
						$notedby = "Noted by:<br><h4 text-align:left;><u>RONALD P. MASCARIÑAS</u></h4><br>Trucking Manager";
					}
				}
				$approvedby = "<h4 text-align:left;><u>MARK / STEPHEN ALEXANDER P. VEGA</u></h4><br>Management";

			}
			
		}
		elseif($soa['type']=="With Out-Turn Summary") {

			$taxes = $this->Billing_model->getSOAAmount($soa['type'],$soa['id']);

			foreach($soa['details'] as $ctr=>$detail) {
				$OS = $this->Operation_model->getOutTurnSummary($detail['out_turn_summary_id']);
			}

			$detailtable	.=	"<tr>";
				$detailtable	.=	"<th><b>WAREHOUSE</b></th>";
				if($OS->type_of_service=="Trucking"){
					$detailtable	.=	"<th><b>TRUCK CO.</b></th>";
				}

				if($OS->type_of_service=="Trucking"){
					if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
						$detailtable	.=	"<th><b>NO. OF BAGS</b></th>";
						$detailtable	.=	"<th><b>MT</b></th>";
						$detailtable	.=	"<th><b>RATE / BAG (50 kgs)</b></th>";
					}else{
						$detailtable	.=	"<th><b>QUANTITY</b></th>";
						$detailtable	.=	"<th><b>MT</b></th>";

						if($detail['empty_sacks']==true){
							$detailtable	.=	"<th><b>RATE / MT</b></th>";
						}else{
							$detailtable	.=	"<th><b>RATE / KM</b></th>";
						}
					}
					$colspan = 2;

					if($detail['empty_sacks']==true){
						//$colspanX =5;
						$colspanX =6;
					}else{
						$colspanX =6;
					}
					
				}else{

					if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
						$detailtable	.=	"<th><b>NO. OF BAGS</b></th>";
						$detailtable	.=	"<th><b>MT</b></th>";
						$detailtable	.=	"<th><b>RATE / MOVE(S)</b></th>";
					}else{
						$detailtable	.=	"<th><b>QUANTITY</b></th>";
						$detailtable	.=	"<th><b>MT</b></th>";
						$detailtable	.=	"<th><b>RATE / MOVE(S)</b></th>";
					}

					$colspan = 1;
					$colspanX =5;
				}

				//if($detail['empty_sacks']==false){
					$detailtable	.=	"<th><b>TRANSACTION</b></th>";
				//}

				$detailtable	.=	"<th><b>AMOUNT</b></th>";
				$detailtable	.=	"</tr>";

			
			foreach($soa['details'] as $ctr=>$detail) {

					if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
						$weight = ($detail['total_weight']/1000);
						$bagqty = ($detail['total_weight']/50);
						if($OS->type_of_service=="Trucking"){
							$moves = "";
						}else{
							$moves = " / " . $detail['number_of_moves'];
						}
					}else{
						$bagqty = $detail['quantity'];
						$weight = ($detail['total_weight']/1000);
						$moves = " / " . $detail['number_of_moves'];
					}


					if($detail['empty_sacks']==true){
			   			$empty_sacks_qty = ($bagqty*0.09)/1000;
			   			$empty_sacks_amount =  $empty_sacks_qty*$detail['rate'];
			   			$xamount = $detail['amount'] - $empty_sacks_amount;
			   			
			   		}else{
			   			$xamount = $detail['amount'];
			   		}

					$detailtable	.=	"<tr>";
						$detailtable	.=	"<td align=\"center\">".strtoupper($detail['warehouse'])."</td>";
						if($OS->type_of_service=="Trucking"){
							$detailtable	.=	"<td align=\"center\">".$detail['trucking_company']."</td>";
						}
					
						$detailtable	.=	"<td align=\"right\">".number_format($bagqty,4,'.',',')."</td>";
						$detailtable	.=	"<td align=\"right\">".number_format($weight,4,'.',',')."</td>";

						if($detail['empty_sacks']==true){
							$detailtable	.=	"<td align=\"right\">P ".number_format($detail['rate'],3,'.',',')."</td>";
						}else{
							$detailtable	.=	"<td align=\"right\">P ".number_format($detail['rate'],3,'.',','). $moves ."</td>";
						}

						//if($detail['empty_sacks']==false){
							$detailtable	.=	"<td align=\"center\">". strtoupper($detail['transaction'])."</td>";
						//}

						$detailtable	.=	"<td align=\"right\">".number_format($xamount,2,'.',',')."</td>";
						$detailtable	.=	"</tr>";

					$totalbags = $totalbags + $bagqty;
					$totalweight = $totalweight + $weight;
					$grandtotal = $grandtotal + $xamount;

			}

			if($detail['tail_end_handling']==true){
				$tail_end_computation = ($totalbags*3.68);
				$detailtable .= "<tr>
									<td align=\"center\" colspan=\"5\"></td>
									<td align=\"right\"><b>".number_format($grandtotal,2,'.',',')."</b></td>
								</tr>";
				$detailtable .= "<tr>
									<td align=\"center\" style=\"font-size:10px\">TAIL END HANDLING</td>
									<td align=\"right\">".number_format($totalbags,4,'.',',')."</td>
									<td align=\"right\">".number_format(($totalweight),4,'.',',')."</td>
									<td align=\"right\">P 3.68 / 1</td>
									<td></td>
									<td align=\"right\">".number_format($tail_end_computation,2,'.',',')."</td>
								</tr>";
			}else{
				$tail_end_computation = 0;
			}

			
			$contenttitle = "Computation:";

			/*$subtotal = "<td colspan=\"" . $colspan . "\"></td>
						 <td style=\"text-align:right;\"><span class=\"underline\">". number_format($totalbags,2,'.',',')."</span></td>
						 <td style=\"text-align:right;\"><span class=\"underline\">". number_format($totalweight,3,'.',',') ." </span></td>
						 <td colspan=\"1\"></td>";*/

			$subtotal = "<td colspan=\"" . $colspan . "\"></td>
						 <td style=\"text-align:right;\"><span class=\"underline\"></span></td>
						 <td style=\"text-align:right;\"><span class=\"underline\"></span></td>
						 <td colspan=\"1\"></td>";

			$note = "<p><h4>
			        ATTACHMENTS: BREAKDOWN OF DELIVERIES/WAREHOUSE STOCK ISSUE<br>
					 TERMS: FULL PAYMENT UPON SUBMISSION OF BILLING<br>
					 Please make all checks payable to " .  strtoupper($soa['company']->name) . "</h4></p>";

				
			if($_SESSION['abas_login']['user_location']=="Makati"){
				//Prepared by: Melanie/ Checked by: Maam Juliet / Noted by: Maam  Arlyn/ Sir Tony
				$preparedby = "<h4 text-align:left;><u>" . strtoupper($_SESSION['abas_login']['fullname']) . "</u></h4><br>Accounting Staff";
				$checkedby = "<h4 text-align:left;><u>____________________________</u></h4><br>Accounting Staff";
				$certifiedby = "<h4 text-align:left;><u>STEPHEN ALEXANDER P. VEGA</u></h4><br>Executive Assistant to SVP-Maketing and Cargo Operations";
				
				if($OS->type_of_service=="Trucking" || $OS->type_of_service=="Handling"){
					$approvedby = "<h4 text-align:left;><u>JACKIE ROSE REGIS</u></h4><br>General Manager";
				}else{
					$approvedby = "<h4 text-align:left;><u>ARLYN CAGADAS</u></h4><br>General Manager";
				}
				
			}
			else{

				$preparedby = "<h4 text-align:left;><u>" . strtoupper($_SESSION['abas_login']['fullname']) . "</u></h4><br>Accounting Staff";
				$checkedby = "<h4 text-align:left;><u>JESSA M. ROMEO</u></h4><br>Accounting Staff";
				if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
					$certifiedby = "<h4 text-align:left;><u>LORVINA A. TABOADA</u></h4><br>Operations Manager";
					$notedby = "";
				}else{
					$certifiedby = "<h4 text-align:left;><u>CHRISTE E. BUTANAS</u></h4><br>Accounting Supervisor";
					if($soa['company']->name=="Avega Bros. Trucking Services Corp."){
						$notedby = "Noted by:<br><h4 text-align:left;><u>RONALD P. MASCARIÑAS</u></h4><br>Trucking Manager";
					}
				}
				$approvedby = "<h4 text-align:left;><u>MARK / STEPHEN ALEXANDER P. VEGA</u></h4><br>Management";

			}
		}

			//$detailtable	.=	"</table>";

			//$grandtotal = $taxes['grandtotal'];/////HERE!!!!!!!

			$grandtotal_tax = $taxes['grandtotal_tax'];

			//$taxcomputation = "<table border=\"1\" cellpadding=\"3\" class=\"rt\">";

			$taxcomputation ="";

			if($soa['vat_12_percent']==1){
				$taxcomputation .= "<tr>";

				if($soa['add_tax']==0){
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">12% VAT</td>";
				}else{
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">Add: 12%</td>";	
				}
									
					$taxcomputation .= "<td align=\"right\">".number_format($taxes['vat_12_percent'],2,".",",")."</td>
									</tr>";

				if($soa['add_tax']==0){
					$taxcomputation .= "<tr>
									<td align=\"right\" colspan=\"" . $colspanX . "\">VATable Amount</td>
									<td align=\"right\">".number_format($taxes['vat_amount'],2,".",",")."</td>
									</tr>";
				}

			}
			if($soa['vat_5_percent']==1){
				$taxcomputation .= "<tr>";

				if($soa['add_tax']==0){
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">5% VAT</td>";
				}else{
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">Add: 5%</td>";
				}
									
					$taxcomputation .= "<td align=\"right\">".number_format($taxes['vat_5_percent'],2,".",",")."</td>
									</tr>";
			}
			if($soa['wtax_15_percent']==1){
				$taxcomputation .= "<tr>";

				if($soa['add_tax']==0){
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">With-holding Tax (15%)</td>";
				}else{
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">Add: 15%</td>";
				}
									
					$taxcomputation .= "<td align=\"right\">".number_format($taxes['wtax_15_percent'],2,".",",")."</td>
									</tr>";
			}
			if($soa['wtax_2_percent']==1){
				$taxcomputation .= "<tr>";

				if($soa['add_tax']==0){
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">With-holding Tax (2%)</td>";
				}else{
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">Add: 2%</td>";
				}
									
					$taxcomputation .= "<td align=\"right\">".number_format($taxes['wtax_2_percent'],2,".",",")."</td>
									</tr>";
			}
			if($soa['wtax_1_percent']==1){
				$taxcomputation .= "<tr>";

				if($soa['add_tax']==0){
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">With-holding Tax (1%)</td>";
				}else{
					$taxcomputation .= "<td align=\"right\" colspan=\"" . $colspanX . "\">Add: 1%</td>";
				}
									
					$taxcomputation .= "<td align=\"right\">".number_format($taxes['wtax_1_percent'],2,".",",")."</td>
									</tr>";
			}

			if($soa['add_tax']==0){
				if($soa['vat_5_percent']==1 || $soa['wtax_15_percent']==1 || $soa['wtax_2_percent']==1 || $soa['wtax_1_percent']==1){
					$taxcomputation .= "<tr>
										<td align=\"right\" colspan=\"" . $colspanX . "\">Total Deductions</td>
										<td align=\"right\"><b><u>".number_format($taxes['total_tax'],2,".",",")."</u></b></td>
										</tr>";
				}
			}
			elseif($soa['add_tax']==1){
				$taxcomputation .= "<tr>
										<td align=\"right\" colspan=\"" . $colspanX . "\">Total Additional Charges</td>
										<td align=\"right\"><b><u>".number_format($taxes['total_tax'],2,".",",")."</u></b></td>
										</tr>";
			}
			//$taxcomputation .= "</table>";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$data['orientation']		=	"P";
			$data['pagetype']			=	"letter";
			$data['title']				=	"Statement of Account - Control No." . $soa['control_number'];
			//$data['control_number']		=	"Transaction Code No." .$soa['id'];

			if($soa['type']=="With Out-Turn Summary"){
				$colspanY = 0;
			}else{
				$colspanY = 6;
			}

			if(isset($OS->id)){
				$out_turn_id = 'OS Transaction Code No.'.$OS->id;
			}else{
				$out_turn_id = '';
			}

			$content =	'
				
				<br><br><br>
				<table border="0">
					<tr>
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h2 color="red" style="font-size:230%">'.$soa['company']->name.'</h2>
							<h3>'.$soa['company']->address.'</h3>
							<h3>'.$soa['company']->telephone_no.'</h3>
						</td>
						<td>SOA Transaction Code No.'.$soa['id'].'<br>'.$out_turn_id;
			if(isset($soa['contract_reference_no'])){		
				if($soa['contract_reference_no']!=NULL){
					$content .=	'<br>Contract: '.$soa['contract_reference_no'];
					if(isset($mother_contract->reference_no)){
						$content .=	'<br>Mother Contract: '.$mother_contract->reference_no;
					}
				}
			}
					
			$content .=	'</td></tr>
				</table>
				
				<h1>BILLING SUMMARY</h1>
				
				<table border="0" cellspacing="5" class="tg" style="undefined;table-layout: fixed">
					  <tr>
						<th class="tg-yw4l">Account of:</th>
						<th class="tg-9hbo" colspan="2" align="left">'.$soa['client']['company'].'</th>
						<th class="tg-yw4l" align="right">Date:</th>
						<th class="tg-yw4l">'.date("j F Y", strtotime($soa['created_on'])).'</th>
					  </tr>
					  <tr>
						<td class="tg-yw4l">Attention:</td>
						<td class="tg-yw4l" colspan="2">' .  $soa['client']['lead_person'] . '</td>
						<td class="tg-yw4l" align="right">SOA Ref. No.:</td>
						<td class="tg-yw4l">' . $soa['reference_number'] .'</td>
					  </tr>
					  <tr>
						<td class="tg-yw4l">Address:</td>
						<td class="tg-yw4l">'.$soa['client']['address'].'</td>
						<td class="tg-yw4l">TIN: '.$soa['client']['tin_no'].' </td>
						<td class="tg-yw4l" align="right">Contact No.:</td>
						<td class="tg-yw4l">'. $soa['client']['contact_no'] . '/' . $soa['client']['fax_no'] .'</td>
					  </tr>
					  <tr>
					  	<td colspan="5">'. $soa['description'].'</td>
					  </tr>
				</table>
				
				<h3 align="left">'. $contenttitle .'</h3>

				'.$detailtable;
				
					
			$content .=	'<tr>'. 
						$subtotal;

			//if($detail['empty_sacks']==false){
				$content .=	'<td style="text-align:right;" colspan="'.$colspanY.'"></td>';
			//}

			$content .=	'<td style="text-align:right;" ><span class="underline">'. number_format($grandtotal+$tail_end_computation,2,'.',',') .'</span></td>
					    </tr>

				' .  $taxcomputation . '

				</table>
			
				<p>Amount in words: <font color="red">' . strtoupper($this->Mmm->chequeTextFormat(number_format($grandtotal_tax,2,'.',''))) .'</font></p>

				<table class="rt">
					<tr>
						<td>
							<h3 style="text-align:right;"> AMOUNT DUE: <span class="doubleUnderline"><b><font color="red">PHP  ' . number_format($grandtotal_tax,2,'.',','). '</font></b></span></h3>
						</td>
					</tr>
				</table>
				
				'.$note.'
				
				<table border="0" cellpadding="1" cellspacing="20" class="rt">
				  <thead>
					<tr>
						<td>
							Prepared by:
							<br>
							
							'. $preparedby .'
						</td>
						<td>
						</td>
							<td>
							Checked by:
							<br>
							
							' . $checkedby . '
						</td>
					</tr>
					<tr>
						<td>
							Certified by:
								<br>
								
							' . $certifiedby . '
						</td>
						<td>
							' . $notedby . '
						</td>
						<td>
							Approved by:
							<br>
							
							' . $approvedby . '
						</td>
					</tr>
				  </thead>
				</table>';

		//prepare content as multi-pages if with Out-Turn Summary
		if($soa['type']=="With Out-Turn Summary"){
			$data['content'][0]	= $content;
		}
		else{
			$data['content'] = $content;
		}

		if($soa['type']=="With Out-Turn Summary"){
			$ctr = 1;

		 	$OS = $this->Operation_model->getOutTurnSummary($detail['out_turn_summary_id']);
			//$OS_details = $this->Operation_model->getOutTurnSummaryDetails($detail['out_turn_summary_id']);
			$OS_deliveries = $this->Operation_model->getOutTurnSummaryDeliveries($detail['out_turn_summary_id']);

			if($OS->service_order_id!=0){
				$SO = $this->Operation_model->getServiceOrder($OS->service_order_id);
				$SO_Detail = $this->Operation_model->getServiceOrderDetail($SO->type,$SO->id);
			}

			foreach($soa['details'] as $detail) {

					$grandtotal_qty = 0;
					$grandtotal_weight = 0;
					$grandtotal_amount = 0;
					$flag_sub_total = false;

					$detailTable2 = "<div align=\"center\"><h1>" . $soa['company']->name . "</h1>";

					if($OS->type_of_service=="Handling"){
						$detailTable2 .= "<h2>Handling Services - Accomplishment Form</h2></div>";
					}
					elseif($OS->type_of_service=="Trucking"){
						$detailTable2 .= "<h2>Trucking Services  - Breakdown of Deliveries</h2></div>";
					}

					//if($OS->type_of_service=="Trucking"){
						$detailTable2 .="<table border=\"0\" cellspacing=\"2\" style=\"font-family:arial;font-size:12px;font-weight:bold;\">
										<tr>
											<td width=\"15%\">Consignee:</td>
											<td width=\"35%\">".$detail['consignee']."</td>
											<td width=\"15%\"align=\"right\">SOA Ref. No.:</td>
											<td width=\"35%\">".$soa['reference_number']."</td>
										</tr>
										<tr>
											<td>Vessel:</td>
											<td>". $detail['on_board_vessel']."</td>
											<td align=\"right\">Warehouse:</td>
											<td>".$detail['warehouse']."</td>
										</tr>
										<tr>
											<td>Commodity:</td>
											<td>".$detail['commodity_cargo']."</td>
											<td align=\"right\">BL No.:</td>
											<td>".$detail['bill_of_lading_number']."</td>
										</tr>
										<tr>
											<td>Destination:</td>
											<td>".$detail['destination']."</td>
											<td align=\"right\">AI No.:</td>
											<td>".$detail['authority_to_issue_number']."</td>
										</tr>
										</table><br><br>";
					//}elseif($OS->type_of_service=="Handling"){
						/*$detailTable2 .="<table border=\"0\" cellspacing=\"2\" style=\"font-family:arial;font-size:12px;font-weight:bold;\">
										<tr>
											<td align=\"center\">Warehouse:</td>
											<td align=\"left\">".$detail['warehouse']."</td>
											<td align=\"right\">SOA Ref. No.:</td>
											<td>".$soa['reference_number']."</td>
										</tr>
										</table><br><br>";
					}*/

		 
					$detailTable2 .= "<table border=\"0\" cellspacing=\"1\" style=\"font-family:arial;font-size:10px;\">";
						$detailTable2 .= "<thead><tr>";
							$detailTable2 .= "<th align=\"center\"><b>DATE</b></th>";

							if($detail['empty_sacks']==true){
								$detailTable2 .= "<td align=\"center\"><b>WAY BILL NO.</b></td>";
								$detailTable2 .= "<td align=\"center\"><b>DR NO.</b></td>";
							}else{
								$detailTable2 .= "<th align=\"center\"><b>____ DOC. NO.</b></th>";
							}

							if($OS->type_of_service=="Trucking"){
								$detailTable2 .= "<th align=\"center\"><b>PLATE NO.</b></th>";
								$detailTable2 .= "<th align=\"center\"><b>TRUCK CO.</b></th>";
							}

							$detailTable2 .= "<th align=\"center\"><b>VARIETY/ITEM</b></th>";

						  	if($detail['empty_sacks']==false){
								$detailTable2 .= "<th align=\"center\"><b>TRANSACTION</b></th>";
							 }

							$detailTable2 .= "<th align=\"center\"><b>QUANTITY</b></th>";
							$detailTable2 .= "<th align=\"center\"><b>GROSS KILOS</b></th>";

							 if($detail['empty_sacks']==true){
						       	$detailTable2 .= "<td align=\"center\"><b>TOTAL</b></td>";
						     }

						$detailTable2 .= "</tr></thead>";

					$qty=0;
					$gross_weight = 0;
					$total_amount = 0;

					$deliveries = $this->Operation_model->getOutTurnSummaryDeliveries($detail['out_turn_summary_id']);

					$row_ctr = 1;
					foreach($deliveries as $row){

						if(strlen($row['warehouse_issuance_form_number'])>3){
							$wf = $row['warehouse_issuance_form_number'];
						}elseif(strlen($row['warehouse_receipt_form_number'])>3){
							$wf = $row['warehouse_receipt_form_number'];
						}else{
							$wf = $row['others'];
						}

						if($OS->type_of_service=="Trucking"){
							if($detail['warehouse'] == $row['warehouse'] && $detail['transaction']==$row['transaction'] && $detail['trucking_company']==$row['trucking_company']){

								$detailTable2 .= "<tr>";
								$detailTable2 .= "<td align=\"center\">" . date('d-M-y',strtotime($row['delivery_date'])). "</td>";

								if($detail['empty_sacks']==true){
									$detailTable2 .= "<td align=\"center\">" . $row['way_bill_number'] . "</td>";
									$detailTable2 .= "<td align=\"center\">" . $row['delivery_receipt_number'] . "</td>";
								}else{
									$detailTable2 .= "<td align=\"center\">" . $wf . "</td>";
								}

								$detailTable2 .= "<td align=\"center\">" . $row['truck_plate_number']. "</td>";
								$detailTable2 .= "<td align=\"center\">" . $row['trucking_company']. "</td>";
	
								$detailTable2 .= "<td align=\"center\">" . $row['variety_item']. "</td>";

								if($detail['empty_sacks']==false){
									$detailTable2 .= "<td align=\"center\">" . $row['transaction']. "</td>";
								}

								$detailTable2 .= "<td align=\"center\">" . $row['quantity'] . "</td>";
								$detailTable2 .= "<td align=\"center\">" . $row['net_weight'] . "</td>";


								if($detail['empty_sacks']==true){
									$detailTable2 .= "<td align=\"center\">" . number_format(($row['net_weight']/1000)*$detail['rate'],3,'.',',') . "</td>";

									$total_amount = $total_amount + (($row['net_weight']/1000)*$detail['rate']);
									$grandtotal_amount = $grandtotal_amount+(($row['net_weight']/1000)*$detail['rate']);
								}


								$detailTable2 .= "</tr>";

								$gross_weight = $gross_weight+$row['net_weight'];
								$qty=$qty+$row['quantity'];

								$grandtotal_qty = $grandtotal_qty+$row['quantity'];
								$grandtotal_weight = $grandtotal_weight+$row['net_weight'];

								if($row_ctr==51){
									$detailTable2 .= "<tr>";
									$detailTable2 .= "<td style=\"text-align:right\" colspan=\"6\"><b>Sub-total</b></td>";
									$detailTable2 .= "<td align=\"center\"><b>" . number_format($qty,4,'.',',') . "</b></td>";
									$detailTable2 .= "<td align=\"center\"><b>" . number_format($gross_weight,4,'.',',') . "</b></td>";

									if($detail['empty_sacks']==true){
										$detailTable2 .=  "<td align=\"center\"><b>" . number_format($total_amount,2,'.',',') . "</b></td>";
									}

									$detailTable2 .= "</tr>";
									$qty = 0;
									$gross_weight = 0;
									$total_amount = 0;
									$flag_sub_total = true;
								}

								$row_ctr++;
							}
						}
						elseif($OS->type_of_service=="Handling"){
							if($detail['warehouse'] == $row['warehouse'] && $detail['transaction']==$row['transaction'] && $detail['number_of_moves']==$row['number_of_moves']){

								$detailTable2 .= "<tr>";
								$detailTable2 .= "<td align=\"center\">" . date('d-M-y',strtotime($row['delivery_date'])). "</td>";
								$detailTable2 .= "<td align=\"center\">" . $wf . "</td>";
								$detailTable2 .= "<td align=\"center\">" . $row['variety_item']. "</td>";
								$detailTable2 .= "<td align=\"center\">" . $row['transaction']. "</td>";
								$detailTable2 .= "<td align=\"center\">" . $row['quantity'] . "</td>";
								$detailTable2 .= "<td align=\"center\">" . $row['gross_weight'] . "</td>";
								$detailTable2 .= "</tr>";

								$gross_weight = $gross_weight+$row['gross_weight'];
								$qty=$qty+$row['quantity'];

								$grandtotal_qty = $grandtotal_qty+$row['quantity'];
								$grandtotal_weight = $grandtotal_weight+$row['gross_weight'];

								
								if($row_ctr==51){
									$detailTable2 .= "<tr>";
									$detailTable2 .= "<td style=\"text-align:right\" colspan=\"4\"><b>Sub-total</b></td>";
									$detailTable2 .= "<td align=\"center\"><b>" . number_format($qty,4,'.',',')  . "</b></td>";
									$detailTable2 .= "<td align=\"center\"><b>" . number_format($gross_weight,4,'.',',')  . "</b></td>";
									$detailTable2 .= "</tr>";
									$qty = 0;
									$gross_weight = 0;
									$flag_sub_total = true;
								}

								$row_ctr++;
					
							}
						
						}

					}

					

					if($OS->type_of_service=="Handling"){
						$colspan = 4;
					}
					elseif($OS->type_of_service=="Trucking"){
						$colspan = 6;
					}

					if($flag_sub_total==true){
						$detailTable2 .= "<tr>
										<td colspan=\"" . $colspan . "\" align=\"right\"><b>Sub-total</b></td>
										<td align=\"center\"><b>" . number_format($qty,4,'.',',')  . "</b></td>
										<td align=\"center\"><b>" . number_format($gross_weight,4,'.',',')  . "</b></td>";

						if($detail['empty_sacks']==true){
							$detailTable2 .=  "<td align=\"center\"><b>" . number_format($total_amount,2,'.',',') . "</b></td>";
						}		

						$detailTable2 .= "</tr>";
					}

					$detailTable2 .= "<tr>
										<td colspan=\"" . $colspan . "\" align=\"right\"><b>Grand-total</b></td>
										<td align=\"center\"><b>" . number_format($grandtotal_qty,4,'.',',')  . "</b></td>
										<td align=\"center\"><b>" . number_format($grandtotal_weight,4,'.',',')  . "</b></td>
							   		  ";

					if($detail['empty_sacks']==true){
						$detailTable2 .=  "<td align=\"center\"><b>" . number_format($grandtotal_amount,2,'.',',') . "</b></td>";
					}

					$detailTable2 .=  "</tr>";

					if($detail['empty_sacks']==true){

			   			$empty_sacks_qty = number_format(($grandtotal_qty*0.09)/1000,5,'.',',');
			   			$empty_sacks_weight = number_format($empty_sacks_qty*1000,2,'.',',');
			   			$empty_sacks_amount =  number_format($empty_sacks_qty*$detail['rate'],2,'.',',');

						$detailTable2 .= "<tr>
									      <td colspan=\"6\" align=\"right\"><b>Empty Sacks</b></td>
									      <td align=\"center\"><font color=\"red\"><b>(".$empty_sacks_qty.")</b></font></td>
									      <td align=\"center\"><font color=\"red\"><b>(".$empty_sacks_weight.")</b></font></td>
									       <td align=\"center\"><font color=\"red\"><b>(".$empty_sacks_amount.")</b></font></td>
									      </tr>";

					}

					$detailTable2 .= "</table>";		  
					
					if($OS->type_of_service=="Handling"){

						//$amount = number_format(($detail['rate']*$qty*$detail['number_of_moves']),2,'.',',');

						if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){

							$qty = ($grandtotal_weight/50);
							$amount = number_format($detail['amount'],2,'.',',');

							$detailTable2 .= "<br><br><table>
										<tr>
											<td align=\"right\"><h1>TOTAL AMOUNT: (".  $qty . ") Bags X Rate (" .  number_format($detail['rate'],3,'.',',') . ") X ) No. of Moves (" . $detail['number_of_moves'] . ") = <font color=\"red\"><u>PHP ". $amount . "</u></font></h1></td>
										</tr>
									</table>";
						}else{

							$qty = ($grandtotal_weight/1000);
							$amount = number_format($detail['amount'],2,'.',',');

							$detailTable2 .= "<br><br><table>
										<tr>
											<td align=\"right\"><h1>TOTAL AMOUNT: (".  $qty . ") MT X Rate (" .  number_format($detail['rate'],3,'.',',') . ") X ) No. of Moves (" . $detail['number_of_moves'] . ") = <font color=\"red\"><u>PHP ". $amount . "</u></font></h1></td>
										</tr>
									</table>";
						}


					}
					elseif($OS->type_of_service=="Trucking"){

						if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
							$mt = ($grandtotal_weight/50);
							//$amount = number_format(($gross_weight/50)*$detail['rate'],2,'.',',');

							$amount = number_format($detail['amount'],2,'.',',');

							$detailTable2 .= "<br><br><table>
											<tr>
												<td align=\"right\"><h1>TOTAL AMOUNT: (". $mt . ") Bags X (" . number_format($detail['rate'],3,'.',',') . ") Rate =
													<u>PHP ". $amount . "</u></h1>
												</td>
											</tr>
									</table>";

						}else{
							$mt = ($grandtotal_weight/1000);
							//$amount = number_format(($gross_weight/1000)*($detail['rate']*$detail['number_of_moves']),2,'.',',');

							if($detail['empty_sacks']==true){
								$amount = number_format($detail['amount']-$empty_sacks_amount,2,'.',',');
								$detailTable2 .= "<br><br><table>
												<tr>
													<td align=\"right\"><h1>TOTAL AMOUNT: (". $mt. ") MT X (" . number_format($detail['rate'],3,'.',',') . ") Rate - (" . $empty_sacks_amount . ") Empty Sacks =
														<u>PHP ". $amount . "</u></h1>
													</td>
												</tr>
										</table>";
							}else{
								$amount = number_format($detail['amount'],2,'.',',');
								$detailTable2 .= "<br><br><table>
												<tr>
													<td align=\"right\"><h1>TOTAL AMOUNT: (". $mt. ") MT X (" . number_format($detail['rate'],3,'.',',') . ") Rate X (" . $detail['number_of_moves'] . ") KM =
														<u>PHP ". $amount . "</u></h1>
													</td>
												</tr>
										</table>";
							}		


						}

						
					}
					

					$detailTable2 .= '<br><br><table border="0" cellpadding="1" cellspacing="30" style="font-family:arial;font-size:10px;">
										<tr>
											<td>
												Prepared by:
												<br>
												<br>
												'. $preparedby .'
											</td>
											<td>
												Checked by:
												<br>
												<br>
												' . $checkedby . '
											</td>
												<td>
												Certified by:
												<br>
												<br>
												<br>
												_____________________________
												<br>
												<br>
												Warehouse Supervisor
											</td>
										</tr>
									</table>';

					$data['content'][$ctr]	=	$detailTable2;
					$ctr++;
			
			}
		}
		

	}	

	$this->load->view('pdf-container.php',$data);

?>