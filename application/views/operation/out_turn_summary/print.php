<?php

$prepared_by			= "______________________________________________<br><b>Operations Staff</b><br>(Signature over Printed Name/Date)";
$verified_by			= "______________________________________________<br><b>Operations Supervisor</b><br>(Signature over Printed Name/Date)";
$approved_by 			= "_____________________________________________<br><b>Operations Manager</b><br>(Signature over Printed Name/Date)";

if($OS->type_of_service=="Shipping"){
	$a1="[ ]";
	$a2="[ ]";
	$a3="[ ]";
	$a4="[ ]";
	$a5="[ ]";;
	$a6="[ ]";
	$a7="[ ]";
	$a8="[ ]";
	$a9="[ ]";
	$a10="[ ]";
	$a11="[ ]";
	$a12="[ ]";
	$a13="[ ]";
	$a14="[ ]";
	$a15="[ ]";
	$a16="[ ]";
	$others="";

	foreach($OS_Attachments as $attachment){
		switch($attachment['document_name']){
			case "Out-Turn Report - Shipper":
				$a1="[x]";
			break;
			case "Out-Turn Report - Consignee":
				$a2="[x]";
			break;
			case "Out-Turn Report - Surveyor":
				$a3="[x]";
			break;
			case "Bill of Lading":
				$a4="[x]";
			break;
			case "Notice of Readiness - Loading":
				$a5="[x]";
			break;
			case "Notice of Completion - Loading":
				$a6="[x]";
			break;
			case "Notice of Readiness - Unloading":
				$a7="[x]";
			break;
			case "Notice of Completion - Unloading":
				$a8="[x]";
			break;
			case "Statement of Facts - Loading":
				$a9="[x]";
			break;
			case "Statement of Facts - Unloading":
				$a10="[x]";
			break;
			case "Mates Receipt":
				$a11="[x]";
			break;
			case "Mate's Receipt":
				$a11="[x]";
			break;
			case "Trip Ticket(s)":
				$a12="[x]";
			break;
			case "Certificate of Cargo - Delivery Receipt (CCR)":
				$a13="[x]";
			break;
			case "Complete Discharge Report and Unloading Certificate":
				$a14="[x]";
			break;
			case "Letter of Protest(s)":
				$a15="[x]";
			break;	
		} 
		if(preg_match('/Others:/',$attachment['document_name'])){
			$a16="[x]";
			$others=substr($attachment['document_name'],7);
		}
	}
}

$content = "<style type=\"text/css\">
				.rt td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
				.rt th{text-align:center;font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;}
				 h1 { font-size:180%;text-align:center; }
				 h2,h3 { text-align:center; }	
				 h5 span { border-bottom: double 3px; }
				 p { text-align:left;font-size:150%; }
				.tg  {border-collapse:collapse;border-spacing:0;}
				.tg td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
				.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;}
				.tg .tg-yw4l{vertical-align:top}
				.tg .tg-9hbo{font-weight:bold;vertical-align:top;horizontal-align:left}
				.tr {font-size:190%;font-weight:bold;vertical-align:top;horizontal-align:left}
				.underline {text-decoration:underline;border-bottom: 2px;font-weight:bold}
				.doubleUnderline {text-decoration:underline;text-decoration-style:double;}
				.bot {vertical-align:bottom;}
				.tda {width:20px; text-align:right}
				.tdb {width:230px; text-align:left}
				.tdc {width:190px;background-color:white; text-align:right}
				.tdn {width:190px;background-color:white; text-align:left}
				.tdp {width:120px;background-color:white; text-align:center}
				.tdo {width:120px;background-color:white; text-align:center; border-bottom-style: solid;border-bottom-width: 0.1;border-bottom-color: black}
				.tdf {width:850px;background-color:white; text-align:left; border-bottom-style: solid;border-bottom-width: 0.1;border-bottom-color: black}
				.tdv {width:230px;border-bottom-style: solid;border-bottom-width: 0.1;border-bottom-color: black}
				.tdm {width:130px;border-bottom-style: solid;border-bottom-width: 0.1;border-bottom-color: black}
				.tdx {width:250px;}
				.tdz {text-align:center}
				td {font-size:120%}
				.text-center {background-color:gray; text-align:center;}
			</style>";


$content .= "
			<table border=\"0\" cellspacing=\"3\" width=\"100%\">
					<tr>
						<td><img src=\"". PDF_LINK . "assets/images/AvegaLogo.jpg\" alt=\"Avega_Logo\"></td>
						<td colspan=\"8\">
							<h1 style=\"text-align:left;\">  ".$OS->company_name."</h1>
							<h2 style=\"text-align:left;\">  ".$OS->company_address."</h2>
							<h3 style=\"text-align:left;\">  ".$OS->company_contact."</h3>
						</td>
					</tr>
					<tr>
						<td colspan=\"7\"></td>
						<td ><h2 style=\"text-align:right;\">No. ".$OS->control_number."</h2></td>
					</tr>
			</table>
			<h1 style=\"font-size:260%\">OUT-TURN SUMMARY</h1>";


$mother_contract = $this->Operation_model->getMotherContract($contract['id']);
if($mother_contract==NULL){
	$mother_contract = "--";
}else{
	$mother_contract = $mother_contract->reference_no;
}



if($OS->type_of_service=="Shipping"){

if($SO->control_number==NULL){
	$service_order_no = "--";
}else{
	$service_order_no = $SO->control_number;
}

$data['pagetype']			=	"legal";

$content .="<table border=\"0\">
				<tr>
					<td style=\"width:880px\">
						<table border=\"0\" cellpadding=\"1.5\">
							<tr>
								<td class=\"tdc\"><b>Contract Ref. No.:  </b></td>
								<td class=\"tdv\">  ". $contract['reference_no'] ."</td>
								<td class=\"tdc\"><b>Port of Origin:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->port_of_origin."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Service Order No:  </b></td>
								<td class=\"tdv\">  ". $service_order_no  ."</td>
								<td class=\"tdc\"><b>Port of Discharge:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->port_of_destination."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Type of Service:  </b></td>
								<td class=\"tdv\">  ". $OS->type_of_service ."</td>
								<td class=\"tdn\" colspan=\"2\"><b>     Loading  </b></td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Bill of Lading No.:  </b></td>
								<td class=\"tdv\">  " . $OS_Details->bill_of_lading_number ."</td>
								<td class=\"tdc\"><b>Date of Arrival:  </b></td>
								<td class=\"tdv\">  ".date('j F Y', strtotime($OS_Details->loading_arrival))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Cargo Description:  </b></td>
								<td class=\"tdv\">  ". $SO_Details->cargo_description ."</td>
								<td class=\"tdc\"><b>Loading Start Date/Time:  </b></td>
								<td class=\"tdv\">  ".date('j F Y h:i:s a', strtotime($OS_Details->loading_start))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Quantity/Volume per BOL:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->quantity_per_bill_of_lading."</td>
								<td class=\"tdc\"><b>Loading Ended Date/Time:  </b></td>
								<td class=\"tdv\">  ".date('j F Y h:i:s a', strtotime($OS_Details->loading_ended))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Total Cargo Weight per BOL:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->weight_per_bill_of_lading."</td>
								<td class=\"tdc\"><b>Date of Departure:  </b></td>
								<td class=\"tdv\">  ".date('j F Y', strtotime($OS_Details->loading_departure))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Shipper:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->shipper."</td>
								<td class=\"tdc\"><b>Quantity/Volume:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->loading_quantity_volume."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Consignee:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->consignee."</td>
								<td class=\"tdn\" colspan=\"2\"><b>     Unloading  </b></td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Surveyor:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->surveyor."</td>
								<td class=\"tdc\"><b>Date of Arrival:  </b></td>
								<td class=\"tdv\">  ".date('j F Y', strtotime($OS_Details->unloading_arrival))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Arrastre:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->arrastre."</td>
								<td class=\"tdc\"><b>Unloading Start Date/Time:  </b></td>
								<td class=\"tdv\">  ".date('j F Y h:i:s a', strtotime($OS_Details->unloading_start))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Local Vessel:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->vessel_name."</td>
								<td class=\"tdc\"><b>Unloading Ended Date/Time:  </b></td>
								<td class=\"tdv\">  ".date('j F Y h:i:s a', strtotime($OS_Details->unloading_ended))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Voyage Number:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->voyage_number."</td>
								<td class=\"tdc\"><b>Date of Departure:  </b></td>
								<td class=\"tdv\">  ".date('j F Y', strtotime($OS_Details->unloading_departure))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Mother Vessel:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->mother_vessel."</td>
								<td class=\"tdc\"><b>Quantity/Volume:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->unloading_quantity_volume."</td>
							</tr>
						</table>
					</td>";

	  $content .=  "<td style=\"width:250px\" rowspan=\"2\">
						<table border=\"0\" cellpadding=\"1.5\">
							<tr>
								<td class=\"tdx\" style=\"background-color:white;color:Black\" align=\"left\" colspan=\"2\"><b>Mother Contract Ref. No.:</b> <u>".$mother_contract."</u></td>
							</tr>
							<tr>
								<td class=\"tdx\" style=\"background-color:white;color:Black\" align=\"left\" colspan=\"2\"><b>   Attachments</b></td>
							</tr>
							<tr>
								<td class=\"tda\">".$a1."</td>
								<td class=\"tdb\">Out-Turn Report - Shipper</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a2."</td>
								<td class=\"tdb\">Out-Turn Report - Consignee</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a3."</td>
								<td class=\"tdb\">Out-Turn Report - Surveyor</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a4."</td>
								<td class=\"tdb\">Bill of Lading</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a5."</td>
								<td class=\"tdb\">Notice of Readiness - Loading</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a6."</td>
								<td class=\"tdb\">Notice of Completion - Loading</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a7."</td>
								<td class=\"tdb\">Notice of Readiness - Unloading</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a8."</td>
								<td class=\"tdb\">Notice of Completion - Unloading</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a9."</td>
								<td class=\"tdb\">Statement of Facts - Loading</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a10."</td>
								<td class=\"tdb\">Statement of Facts - Unloading</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a11."</td>
								<td class=\"tdb\">Mate's Receipt</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a12."</td>
								<td class=\"tdb\">Trip Ticket(s)</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a13."</td>
								<td class=\"tdb\">Certificate of Cargo - Delivery Receipt (CCR)</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a14."</td>
								<td class=\"tdb\">Discharge Report & Unloading Certificate</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a15."</td>
								<td class=\"tdb\">Letter of Protest(s)</td>
							</tr>
							<tr>
								<td class=\"tda\">".$a16."</td>
								<td class=\"tdb\">Others:".$others."</td>
							</tr>";

	  $content .= 	   "</table>
				   </td>
				   </tr>";
					
	  $content .= "<tr><td>
	  				<br><br>
					<table border=\"0\" cellpadding=\"1.5\">
						<tr>
							<td style=\"background-color:white;color:Black\" align=\"left\"><b>Final Output & Variance</b></td>
						</tr>
						<tr>
							<td class=\"tdp\"> </td>
							<td class=\"tdp\" style=\"background-color:white\"><b>  Quantity</b></td>
							<td class=\"tdp\" style=\"background-color:white\"><b>  Weight</b></td>
							<td class=\"tdp\">.</td>
							<td class=\"tdp\" style=\"background-color:white\"><b>  Quantity</b></td>
						</tr>
						<tr>
							<td class=\"tdp\" style=\"background-color:white;text-align:left\"><b>  Cargo Declared Per:</b></td>
							<td class=\"tdp\"> </td>
							<td class=\"tdp\"> </td>
							<td class=\"tdp\" style=\"background-color:white;text-align:left\" colspan=\"2\"><b>  Accounted For:</b></td>
							<td class=\"tdp\"> </td>
						</tr>

						<tr>
							<td class=\"tdp\" align=\"right\">Shipper</td>
							<td class=\"tdo\"> ".$OS_Output->shipper_number_of_bags."</td>
							<td class=\"tdo\"> ".$OS_Output->shipper_weight."</td>
							<td class=\"tdp\" align=\"right\">Good</td>
							<td class=\"tdo\"> ".$OS_Output->good_number_of_bags."</td>
						</tr>
						<tr>
							<td class=\"tdp\" align=\"right\">Consignee</td>
							<td class=\"tdo\"> ".$OS_Output->consignee_number_of_bags."</td>
							<td class=\"tdo\"> ".$OS_Output->consignee_weight."</td>
							<td class=\"tdp\" align=\"right\">Damaged/Loss</td>
							<td class=\"tdo\"> ".$OS_Output->damaged_number_of_bags."</td>
						</tr>
						<tr>
							<td class=\"tdp\" align=\"right\">Variance</td>
							<td class=\"tdo\"> ".$OS_Output->variance_number_of_bags."</td>
							<td class=\"tdo\"> ".$OS_Output->variance_weight."</td>
							<td class=\"tdp\" align=\"right\">Total</td>
							<td class=\"tdo\" colspan=\"2\"> ".$OS_Output->total_number_of_bags."</td>
						</tr>
						<tr>
							<td class=\"tdp\" align=\"right\">Percentage</td>
							<td class=\"tdo\"> ".$OS_Output->percentage_number_of_bags . "%"."</td>
							<td class=\"tdo\"> ".$OS_Output->percentage_weight . "%"."</td>
							<td class=\"tdp\" colspan=\"2\"></td>
						</tr>
	        		</table>";
$content .=  "</td>
			</tr>";
			            
				
		$content .=	"<br>
					<tr>
						<td>
					     <table border=\"0\" cellpadding=\"1.5\">
							<tr>
								<td class=\"tdc\" style=\"background-color:white;text-align:right;\" align=\"right\"><b>  Remarks: </b></td>
							";
								if($OS->remarks!=""){
									 $content .= "<td class=\"tdf\">  ".$OS->remarks."</td>";
								}else{
									 $content .= "<td class=\"tdf\">No Remarks</td>";
								}
		$content .= "		</tr>
						</table>
						</td>
					</tr>
		</table>";

}
elseif($OS->type_of_service=="Trucking" || $OS->type_of_service=="Handling"){

if(isset($SO)){
	$service_order_control_number = $SO->control_number;	
}else{
	$service_order_control_number = "--";
}


$data['pagetype']			=	"legal";	

		$content .= "<br><br><br><br>
						<table width=\"100%\">
							<tr>
								<td align=\"center\"><b>Contract Ref. No.:</b></td>
								<td class=\"tdm\">".$contract['reference_no']."</td>
								<td align=\"center\"><b>Mother Contract Ref No.:  </b></td>
								<td class=\"tdm\">  ". $mother_contract."</td>
								<td align=\"center\"><b>Service Order No.:</b></td>
								<td class=\"tdm\">".$service_order_control_number."</td>
								<td align=\"center\"><b>Type of Service:</b></td>
								<td class=\"tdm\">".$OS->type_of_service."</td>
							</tr>
						</table>";

		$content .= "<h2 style=\"text-align:left;\">Breakdown</h2>";

		$content .=  "<table border=\"1\" cellpadding=\"3\" width=\"100%\">
						 <thead><tr>";

            			if($OS->type_of_service=='Trucking'){
            				$content .=   "<th class=\"text-center\">Trip #</th>
						                    <th class=\"text-center\">Date</th>
						                    <th class=\"text-center\">Truck Plate No.</th>
						                    <th class=\"text-center\">Name of Truck Driver</th>
						                    <th class=\"text-center\">Trucking Co.</th>
						                    <th class=\"text-center\">Warehouse/Consignee</th>
						                    <th class=\"text-center\">Item Description</th>
						                    <th class=\"text-center\">Quantity</th>
						                    <th class=\"text-center\">Gross Wt.</th>
						                    <th class=\"text-center\">Tare Wt.</th>
						                    <th class=\"text-center\">Net Wt.</th>
						                    <th class=\"text-center\">Transaction</th>
						                    <th class=\"text-center\">DR No.</th>
						                    <th class=\"text-center\">WT No.</th>
						                    <th class=\"text-center\">WIF No.</th>
						                    <th class=\"text-center\">WRF No.</th>
						                    <th class=\"text-center\">WB No.</th>
						                    <th class=\"text-center\">ATL No.</th>
						                    <th class=\"text-center\">CR No.</th>
						                    <th class=\"text-center\">Others</th>";
            			}
            			
            			elseif($OS->type_of_service=='Handling'){
            				$content .=   "<th class=\"text-center\">#</th>
						                    <th class=\"text-center\">Date</th>
						                    <th class=\"text-center\">Warehouse</th>
						                    <th class=\"text-center\">Quantity</th>
						                    <th class=\"text-center\">Weight</th>
						                    <th class=\"text-center\">No. of Moves</th>
						                    <th class=\"text-center\">Variety</th>
						                    <th class=\"text-center\">Transaction</th>
						                    <th class=\"text-center\">WIF No.</th>
						                    <th class=\"text-center\">WRF No.</th>
						                    <th class=\"text-center\">Others</th>";
            			}
            			

		    $content .=    "</tr></thead>
				           		 <tbody>";
									
										$ctr=1;
										$total_bags =0;
										$total_gross_wt = 0;
										$total_tare_wt = 0;
										$toyal_net_wt = 0;
										foreach($OS_Deliveries as $delivery){

					            			if($OS->type_of_service=='Trucking'){
					            				$content .=   "<tr>
					            							 <td class=\"tdz\">".$ctr."</td>
															 <td class=\"tdz\">".$delivery['delivery_date']."</td>
															 <td class=\"tdz\">".$delivery['truck_plate_number']."</td>
															 <td class=\"tdz\">".$delivery['truck_driver']."</td>
											    			 <td class=\"tdz\">".$delivery['trucking_company']."</td>
											    			 <td class=\"tdz\">".$delivery['warehouse']."</td>
											    			 <td class=\"tdz\">".$delivery['variety_item']."</td>
											    			 <td class=\"tdz\">".number_format($delivery['quantity'],4,'.',',')."</td>
											    			 <td class=\"tdz\">".number_format($delivery['gross_weight'],4,'.',',')."</td>
											    			 <td class=\"tdz\">".number_format($delivery['tare_weight'],4,'.',',')."</td>
											    			 <td class=\"tdz\">".number_format($delivery['net_weight'],4,'.',',')."</td>
											    			 <td class=\"tdz\">".$delivery['transaction']."</td>
											    			 <td class=\"tdz\">".$delivery['delivery_receipt_number']."</td>
											    			 <td class=\"tdz\">".$delivery['weighing_ticket_number']."</td>
											    			 <td class=\"tdz\">".$delivery['warehouse_issuance_form_number']."</td>
											    			 <td class=\"tdz\">".$delivery['warehouse_receipt_form_number']."</td>
											    			  <td class=\"tdz\">".$delivery['way_bill_number']."</td>
											    			 <td class=\"tdz\">".$delivery['authority_to_load_number']."</td>
											    			 <td class=\"tdz\">".$delivery['cargo_receipt_number']."</td>
											    			 <td class=\"tdz\">".$delivery['others']."</td>
					            				       </tr>";
					            			}elseif($OS->type_of_service=='Handling'){
					            				$content .=  "<tr>	
															 <td class=\"tdz\">".$ctr."</td>
															 <td class=\"tdz\">".$delivery['delivery_date']."</td>
											    			 <td class=\"tdz\">".$delivery['warehouse']."</td>
											    			 <td class=\"tdz\">".number_format($delivery['quantity'],4,'.',',')."</td>
											    			 <td class=\"tdz\">".number_format($delivery['gross_weight'],4,'.',',')."</td>		
											    			 <td class=\"tdz\">".$delivery['number_of_moves']."</td>
											    			 <td class=\"tdz\">".$delivery['variety_item']."</td>
											    			 <td class=\"tdz\">".$delivery['transaction']."</td>
											    			 <td class=\"tdz\">".$delivery['warehouse_issuance_form_number']."</td>
											    			 <td class=\"tdz\">".$delivery['warehouse_receipt_form_number']."</td>
											    			 <td class=\"tdz\">".$delivery['others']."</td>
					            					  </tr>";
					            			}
					            	
					            			$total_bags = $total_bags + $delivery['quantity'];
											$total_gross_wt = $total_gross_wt + $delivery['gross_weight'];
											$total_tare_wt = $total_tare_wt + $delivery['tare_weight'];
											$toyal_net_wt = $toyal_net_wt + $delivery['net_weight'];

					            			$ctr++;
										}
			if($OS->type_of_service=='Handling'){
					$content .= "<tr>
									<td colspan=\"3\"></td>
									<td class=\"tdz\"><b>".number_format($total_bags,4,'.',',')."</b></td>
									<td class=\"tdz\"><b>".number_format($total_gross_wt,4,'.',',')."</b></td>
								</tr>";
			}elseif($OS->type_of_service=='Trucking'){
					$content .= "
								<tr>
									<td colspan=\"7\"></td>
									<td class=\"tdz\"><b>".number_format($total_bags,4,'.',',')."</b></td>
									<td class=\"tdz\"><b>".number_format($total_gross_wt,4,'.',',')."</b></td>
									<td class=\"tdz\"><b>".number_format($total_tare_wt,4,'.',',')."</b></td>
									<td class=\"tdz\"><b>".number_format($toyal_net_wt,4,'.',',')."</b></td>
								</tr>";
			}

			$content .= "</tbody>
			        </table>";

		    $content .=	"<br><br><div>
						     <table border=\"0\" cellpadding=\"1.5\" width=\"100%\">
								<tr>
									<td class=\"tdc\" style=\"background-color:white;text-align:left;\" align=\"left\"><b>  Remarks: </b></td>
								</tr>
							<tr>";
								if($OS->remarks!=""){
									 $content .= "<td class=\"tdf\">  ".$OS->remarks."</td>";
								}else{
									 $content .= "<td class=\"tdf\">No Remarks</td>";
								}
		$content .= "		</tr>
							</table>
						</div>";
}elseif($OS->type_of_service=="Lighterage" || $OS->type_of_service=="Time Charter"){

$data['pagetype']			=	"letter";

$content .="<h2></h2><br><br>";

$label = "Mother Vessel:";

if($SO->control_number==NULL){
	$service_order_no = "--";
}else{
	$service_order_no = $SO->control_number;
}

$content .="<table border=\"0\">
				<tr>
					<td style=\"width:1280px\">
						<table border=\"0\" cellpadding=\"1.5\">
							<tr>
								<td class=\"tdc\"><b>Contract Ref. No.:  </b></td>
								<td class=\"tdv\">  ". $contract['reference_no'] ."</td>
								<td class=\"tdc\"><b>Mother Contract Ref. No.:  </b></td>
								<td class=\"tdv\">  ". $mother_contract ."</td>
								
							</tr>
							<tr>
								<td class=\"tdc\"><b>Service Order No:  </b></td>
								<td class=\"tdv\">  ". $service_order_no ."</td>
								<td class=\"tdc\"><b>Vessel/Tugboat/Barge Name:  </b></td>
								<td class=\"tdv\">  ".$SO->details->vessel."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Type of Service:  </b></td>
								<td class=\"tdv\">  ". $OS->type_of_service ."</td>
								<td class=\"tdc\"><b>Barge Patron.:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->barge_patron."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Lighter Receipt No.:  </b></td>
								<td class=\"tdv\">  " . $OS_Details->lighterage_receipt_number ."</td>
								<td class=\"tdc\"><b>Voyage No.:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->voyage_number."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Trip Ticket No.:  </b></td>
								<td class=\"tdv\">  " . $OS_Details->trip_ticket_number ."</td>
								<td class=\"tdc\"><b>".$label." </b></td>
								<td class=\"tdv\">  ".$OS_Details->mother_vessel."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Statement of Facts Ref.:  </b></td>
								<td class=\"tdv\">  " . $OS_Details->statement_of_facts_number ."</td>
								<td class=\"tdc\"><b>Shipper:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->shipper."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Cargo Description:  </b></td>
								<td class=\"tdv\">  " . $SO_Details->cargo_description ."</td>
								<td class=\"tdc\"><b>Consignee:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->consignee."</td>
							</tr>
							<tr>
								<td class=\"tdc\" style=\"font-size:12px\"><br><br><b>Loading Point</b></td>
								<td class=\"tdc\"></td>
								<td class=\"tdc\" style=\"font-size:12px\"><br><br><b>Unloading Point</b></td>
								<td class=\"tdc\"></td>
							</tr>
							<tr>
								<td class=\"tdc\" ><b>Location: </b></td>
								<td class=\"tdv\"> " . $OS_Details->port_of_origin ."</td>
								<td class=\"tdc\"><b>Location: </b></td>
								<td class=\"tdv\"> " . $OS_Details->port_of_destination ."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Date of Arrival: </b></td>
								<td class=\"tdv\"> " . date('j F Y', strtotime($OS_Details->loading_arrival)) ."</td>
								<td class=\"tdc\"><b>Date of Arrival: </b></td>
								<td class=\"tdv\"> " . date('j F Y', strtotime($OS_Details->unloading_arrival)) ."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Date and Time Started: </b></td>
								<td class=\"tdv\"> " . date('j F Y h:i:s a', strtotime($OS_Details->loading_start)) ."</td>
								<td class=\"tdc\"><b>Date and Time Started: </b></td>
								<td class=\"tdv\"> " . date('j F Y h:i:s a', strtotime($OS_Details->unloading_start)) ."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Date and Time Ended: </b></td>
								<td class=\"tdv\"> " . date('j F Y h:i:s a', strtotime($OS_Details->loading_ended)) ."</td>
								<td class=\"tdc\"><b>Date and Time Ended: </b></td>
								<td class=\"tdv\"> " . date('j F Y h:i:s a', strtotime($OS_Details->unloading_ended))."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Quantity: </b></td>
								<td class=\"tdv\"> " . $OS_Details->loading_quantity_volume ."</td>
								<td class=\"tdc\"><b>Quantity: </b></td>
								<td class=\"tdv\"> " . $OS_Details->unloading_quantity_volume."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Batch Weight: </b></td>
								<td class=\"tdv\"> " . $OS_Details->loading_batch_weight ."</td>
								<td class=\"tdc\"><b>Batch Weight: </b></td>
								<td class=\"tdv\"> " . $OS_Details->unloading_batch_weight."</td>
							</tr>
							<tr>
								<td class=\"tdc\" style=\"font-size:12px\"><br><br><b>Variance</b></td>
								<td class=\"tdc\"></td>
								<td class=\"tdc\"><br><br></td>
								<td class=\"tdc\"></td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Quantity: </b></td>
								<td class=\"tdv\"> " . ($OS_Details->loading_quantity_volume - $OS_Details->unloading_quantity_volume) ."</td>
								<td class=\"tdc\"></td>
								<td class=\"tdc\"></td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Batch Weight: </b></td>
								<td class=\"tdv\"> " . ($OS_Details->loading_batch_weight - $OS_Details->unloading_batch_weight)  ."</td>
								<td class=\"tdc\"></td>
								<td class=\"tdc\"></td>
							</tr>
							
						</table>
					</td></tr>";

		$content .=	"<br><br>
					<tr>
						<td>
					     <table border=\"0\" cellpadding=\"1.5\" width=\"100%\">
							<tr>
								<td class=\"tdc\" style=\"background-color:white;text-align:left;\" align=\"left\"><b>  Remarks: </b></td>
							</tr>
							<tr>";
								if($OS->remarks!=""){
									 $content .= "<td class=\"tdf\">  ".$OS->remarks."</td>";
								}else{
									 $content .= "<td class=\"tdf\">No Remarks</td>";
								}
		$content .= "		</tr>
						</table>
						</td>
					</tr>
		</table>";

}


elseif($OS->type_of_service=="Towing"){

$data['pagetype']			=	"letter";

$label = "Craft Towed:";

if($SO->control_number==NULL){
	$service_order_no = "--";
}else{
	$service_order_no = $SO->control_number;
}

$content .="<table border=\"0\">
				<tr>
					<td style=\"width:1280px\">
						<table border=\"0\" cellpadding=\"1.5\">
							<tr>
								<td class=\"tdc\"><b>Contract Ref. No.:  </b></td>
								<td class=\"tdv\">  ". $contract['reference_no'] ."</td>
								<td class=\"tdc\"><b>Mother Contract Ref. No.:  </b></td>
								<td class=\"tdv\">  ". $mother_contract ."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Service Order No:  </b></td>
								<td class=\"tdv\">  ". $service_order_no ."</td>
								<td class=\"tdc\"><b>Master/Patron.:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->barge_patron."</td>	
							</tr>
							<tr>
								<td class=\"tdc\"><b>Type of Service:  </b></td>
								<td class=\"tdv\">  ". $OS->type_of_service ."</td>
								<td class=\"tdc\"><b>Cargo Description:  </b></td>
								<td class=\"tdv\">  " . $SO_Details->cargo_description ."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Trip Ticket No.:  </b></td>
								<td class=\"tdv\">  " . $OS_Details->trip_ticket_number ."</td>
								<td class=\"tdc\"><b>Voyage No.:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->voyage_number."</td>
							</tr>
							<tr>
								<td class=\"tdc\"><b>Servicing Vessel:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->vessel_name."</td>
								<td class=\"tdc\"><b>".$label." </b></td>
								<td class=\"tdv\">  ".$OS_Details->mother_vessel."</td>
								
							</tr>
							<tr>
								<td class=\"tdc\"><b>Account/Customer:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->shipper."</td>
								<td class=\"tdc\"><b>Consignee:  </b></td>
								<td class=\"tdv\">  ".$OS_Details->consignee."</td>
							</tr>
							<tr>
								<td class=\"tdc\" style=\"font-size:12px\"><br><br><b>Departure Terminal</b></td>
								<td class=\"tdc\"></td>
								<td class=\"tdc\" style=\"font-size:12px\"><br><br><b>Arrival Terminal</b></td>
								<td class=\"tdc\"></td>
							</tr>
							
							<tr>
								<td class=\"tdc\"><b>Date and Time Started: </b></td>
								<td class=\"tdv\"> " . date('j F Y h:i:s a', strtotime($OS_Details->loading_start)) ."</td>
								<td class=\"tdc\"><b>Date and Time Ended: </b></td>
								<td class=\"tdv\"> " . date('j F Y h:i:s a', strtotime($OS_Details->unloading_ended))."</td>
							</tr>
							<tr>
								<td class=\"tdc\" ><b>Location: </b></td>
								<td class=\"tdv\"> " . $OS_Details->port_of_origin ."</td>
								<td class=\"tdc\"><b>Location: </b></td>
								<td class=\"tdv\"> " . $OS_Details->port_of_destination ."</td>
							</tr>
							
						</table>
					</td></tr>";

		$content .=	"<br><br>
					<tr>
						<td>
					     <table border=\"0\" cellpadding=\"1.5\" width=\"100%\">
							<tr>
								<td class=\"tdc\" style=\"background-color:white;text-align:left;\" align=\"left\"><b>  Remarks: </b></td>
							</tr>
							<tr>";
								if($OS->remarks!=""){
									 $content .= "<td class=\"tdf\">  ".$OS->remarks."</td>";
								}else{
									 $content .= "<td class=\"tdf\">No Remarks</td>";
								}
		$content .= "		</tr>
						</table>
						</td>
					</tr>
		</table>";

}


		$content .= '<table border="0">
							<tr>
								<td style="text-align:left"><b>Prepared By:</b></td>
								<td style="text-align:left"><b>Verified By:</b></td>
								<td style="text-align:left"><b>Approved By:</b></td>
							</tr>
							<tr>
								<td><img src="'.LINK.'assets/images/digitalsignatures/'.$OS->created_by_signature.'" style="width:90px;height:60px;"/></td>
								<td><img src="'.LINK.'assets/images/digitalsignatures/'.$OS->verified_by_signature.'" style="width:90px;height:60px;"/></td>
								<td><img src="'.LINK.'assets/images/digitalsignatures/'.$OS->approved_by_signature.'" style="width:90px;height:60px;"/></td>
							</tr>
							<tr>
								<td><u>'.$OS->full_name.'</u></td>
								<td><u>'.$OS->verified_by_name.'</u></td>
								<td><u>'.$OS->approved_by_name.'</u></td>
							</tr>
							<tr>
								<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($OS->created_on)).'</u></td>
								<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($OS->verified_on)).'</u></td>
								<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($OS->approved_on)).'</u></td>
							</tr>
						</table>
					';

$data['orientation']		=	"L";
$data['title']				=	"Out-Turn Summary Control No." . $OS->control_number;
$data['control_number']		=	"Transaction Code No." .$OS->id;
$data['content']			=	$content;


if($preview==false){
	$this->load->view('pdf-container.php',$data);	
}else{
	echo "<!DOCTYPE html>
	<html>
	<head>
		<title>Out-Turn Summary Control No." . $OS->control_number."</title>
	</head>
	<body>
	<span style='position:absolute;right:50px'>Transaction Code No." .$OS->id."</span>".
	$content."
	</body>
	</html>";
}

?>

