<?php

$content = "<style type=\"text/css\">
					.rt td{font-family:, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
					.pt{font-family:Calibri, sans-serif;font-size:25px;}
					.rtx td{font-family:Calibri, sans-serif;font-size:10px;padding:5px 5px;overflow:hidden;word-break:normal;}
					.rt th{text-align:center;font-family:Calibri, sans-serif;font-size:12px;padding:5px 5px;}
					 h1 { font-size:300%;text-align:center; }
					 h2,h3 { text-align:center; }	
					 h5 span { border-bottom: double 3px; }
					div.relative {
					    position: relative;
					    top: 2000px;
					}
			</style>";

$footer = "<style type=\"text/css\">
				.footer{
				    position: fixed;
				    top: -200px;
				    bottom: 20px;
				    width:80%;
				    margin: 0 0 0 -40%;
				    left:50%;
				}
			</style>";

if(isset($soa['contract_reference_no'])){
	$contract_reference_no = ' - '.$soa['contract_reference_no'];
}else{
	$contract_reference_no = '';
}
$content .= "<div class=\"relative\">
				<br><br><br><br><br><br><br><br><br><br><br><br><br>
				<table border=\"0\" cellspacing=\"5\" class=\"rt\">
					<tr>
						<td style=\"width:90px\"></td>
						<td style=\"width:300px\" align=\"left\"><br><br>".$soa['client']['company']."</td>
						<td style=\"width:120px\"></td>
						<td align=\"left\"><br><br>".date("j F Y", strtotime($soa['created_on']))."</td>
					</tr>
					<tr>
						<td></td>
						<td align=\"left\"><br>".$soa['client']['tin_no']."<br>".$soa['client']['address']."</td>
						<td></td>
						<td align=\"left\"><br>".$soa['reference_number'] . $contract_reference_no	."</td>
					</tr>
					
				</table>
			</div>
			<div>
				<br><br><br><br><br><br><br><br>
				<table border=\"0\" cellspacing=\"5\" class=\"rt\">
					<tr>
						<td style=\"width:40px\"></td>
						<td style=\"width:340px\"><h4>".$soa['description']."</h4></td>
					</tr>
				</table>
			</div>
			<div>
				<table border=\"0\" cellspacing=\"6\" class=\"rt\">";
				
				if($soa['type']=="General"){
					foreach($soa['details'] as $detail){


						$payment = "-";
						$charges = "-";
						
						if($detail['payment']>0){
								$payment = number_format($detail['payment'],2,'.',',');
						}
						if($detail['charges']>0){
								$charges = number_format($detail['charges'],2,'.',',');
						}
						

						$particular = $detail['particular'];
						$content .= "<tr>
										<td style=\"width:40px\"></td>
										<td style=\"width:350px\">".$particular."</td>
										<td align=\"right\" style=\"width:100px\">".$payment."</td>
										<td align=\"right\" style=\"width:100px\">".$charges."</td>";

						//as per request by trucking billing due to their client does not like if there is a balance sheet on the SOA and want it straight-forward				
						if($format=="data"){
							$content .= "<td align=\"right\" style=\"width:100px\">".number_format($detail['balance'],2,".",",")."</td>";
						}				
						
						$content .= "</tr>";
					}
				}
				
				$taxes = $this->Billing_model->getSOAAmount($soa['type'],$soa['id']);

				$grandtotal = $taxes['grandtotal'];
				if($soa['add_tax'] == 0){
					$grandtotal_tax = $taxes['grandtotal']-$taxes['total_tax'];
					
					if($taxes['total_tax']!=0){
						$pre = "LESS: ";
					}else{
						$pre = "";
					}
					
				}elseif($soa['add_tax'] == 1){
					$grandtotal_tax = $taxes['grandtotal']+$taxes['total_tax'];
					$pre = "ADD: ";
				}
			

if($soa['type']=="General"){
	$content .= "	<br>
				<tr>
					
					<td align=\"right\" colspan=\"2\">Total</td>
					<td></td>
					<td align=\"right\">".number_format($grandtotal,2,".",",")."</td>
				</tr>
				</table>
			</div>";
}else{
	$content .= "	<br>
		<tr>
			
			<td align=\"right\" colspan=\"2\">Total</td>
			<td></td>
			<td align=\"center\">".number_format($grandtotal,2,".",",")."</td>
		</tr>
		</table>
	</div>";
}



		

$content .= "<div>
				<table border=\"0\" cellspacing=\"5\" class=\"rt\">";

			if($soa['add_tax']==0 && $soa['vat_12_percent']==1 && $soa['vat_5_percent']==0 && $soa['wtax_15_percent']==0 &&  $soa['wtax_2_percent']==0 && $soa['wtax_1_percent']==0){
				$content .= "<tr>
								<td align=\"center\" style=\"width:150px\"><b></b></td>
							</tr>";
			}else{
				$content .= "<tr>
								<td align=\"center\" style=\"width:150px\"><b>".$pre."</b></td>
							</tr>";
			}

			if($soa['vat_12_percent']==1){
				$content  .= "<tr>";

				if($soa['add_tax']==0){
					$content  .= "<td align=\"right\" style=\"width:150px\">12% VAT</td>";
				}else{
					$content  .= "<td align=\"right\" style=\"width:150px\">12%</td>";
				}
								
				$content  .= "<td align=\"right\" style=\"width:100px\">".number_format($taxes['vat_12_percent'],2,".",",")."</td>
							</tr>";

				if($soa['add_tax']==0){
					$content  .= "<tr>
									<td align=\"right\" style=\"width:150px\">VATable Amount</td>
									<td align=\"right\" style=\"width:100px\">".number_format($taxes['vat_amount'],2,".",",")."</td>
								</tr>";
				}

			}
			if($soa['vat_5_percent']==1){
				$content .= "<tr>";

				if($soa['add_tax']==0){
					$content .= "<td align=\"right\" style=\"width:150px\">5% VAT</td>";
				}else{
					$content .= "<td align=\"right\" style=\"width:150px\">5%</td>";
				}
								
					$content .=	"<td align=\"right\" style=\"width:100px\">".number_format($taxes['vat_5_percent'],2,".",",")."</td>
							</tr>";
			}
			if($soa['wtax_15_percent']==1){
				$content .= "<tr>";

				if($soa['add_tax']==0){
					$content .= "<td align=\"right\" style=\"width:150px\">With-holding Tax (15%)</td>";
				}else{
					$content .= "<td align=\"right\" style=\"width:150px\">15%</td>";
				}
					
					$content .= "<td align=\"right\" style=\"width:100px\">".number_format($taxes['wtax_15_percent'],2,".",",")."</td>
							</tr>";
			}
			if($soa['wtax_2_percent']==1){
				$content .= "<tr>";

				if($soa['add_tax']==0){
					$content .= "<td align=\"right\" style=\"width:150px\">With-holding Tax (2%)</td>";
				}else{
					$content .= "<td align=\"right\" style=\"width:150px\">2%</td>";
				}
								
					$content .= "<td align=\"right\" style=\"width:100px\">".number_format($taxes['wtax_2_percent'],2,".",",")."</td>
							</tr>";
			}
			if($soa['wtax_1_percent']==1){
				$content .= "<tr>";

				if($soa['add_tax']==0){
					$content .= "<td align=\"right\" style=\"width:150px\">With-holding Tax (1%)</td>";
				}else{
					$content .= "<td align=\"right\" style=\"width:150px\">1%</td>";
				}
								
					$content .= "<td align=\"right\" style=\"width:100px\">".number_format($taxes['wtax_1_percent'],2,".",",")."</td>
							</tr>";
			}
			if($soa['add_tax']==0){
				if($soa['vat_5_percent']==1 || $soa['wtax_15_percent']==1 || $soa['wtax_2_percent']==1 || $soa['wtax_1_percent']==1){
				$content .= "<hr><tr>
								<td align=\"right\" style=\"width:150px\">Total Deductions:</td>
								<td align=\"right\" style=\"width:100px\">".number_format($taxes['total_tax'],2,".",",")."</td>
							</tr>";
				}
			}elseif($soa['add_tax']==1){
				$content .= "<hr><tr>
								<td align=\"right\" style=\"width:150px\">Total Additional Charges</td>
								<td align=\"right\" style=\"width:100px\">".number_format($taxes['total_tax'],2,".",",")."</td>
							</tr>";
			}
			
$content .= "</table>
			</div>";

$content .= "<div>
				<table border=\"0\" cellspacing=\"5\" class=\"rt\">
					<tr>
						<td colspan=\"4\" align=\"center\" style=\"width:455px;font-size:14px\"><h4>Total Amount Due:</h4></td>
						<td align=\"right\" style=\"width:200px;font-size:14px\"><h4>PHP ".number_format($grandtotal_tax,2,".",",")."</h4></td>
					</tr>
					<tr><td><br><br><br></td></tr>
					<tr>
						<td style=\"width:40px\"></td>
						<td colspan=\"6\"><p>Amount in words:". strtoupper($this->Mmm->chequeTextFormat($grandtotal_tax)) ."</p></td>
					</tr>
				</table>
			</div>";

if($_SESSION['abas_login']['user_location']=="Makati"){
	$footer .= "<div class=\"footer\">
					<table border=\"0\" cellspacing=\"12\" class=\"rtx\">
						<tr>
							<td  colspan=\"2\"></td>
							<td style=\"text-align:center\">STEPHEN ALEXANDER P. VEGA</td>
						</tr>
						<tr>
							<td><br><br><br><br></td>
						</tr>";
	if($soa['services']=="Trucking" || $soa['services']=="Handling"){
		$footer .=		"<tr>
							<td  colspan=\"2\"></td>
							<td style=\"text-align:center\">JACKIE ROSE REGIS</td>
						</tr>
						</table>
		             </div>";
	}else{
		$footer .=		"<tr>
							<td  colspan=\"2\"></td>
							<td style=\"text-align:center\">ARLYN CAGADAS</td>
						</tr>
						</table>
		             </div>";
	}
}else{
	$footer .= "<div class=\"footer\">
					<table border=\"0\" cellspacing=\"11\" class=\"rtx\">
						<tr>
							<td  colspan=\"2\"></td>
							<td  style=\"text-align:center\" class=\"pt\"><b><br><br><br>CHRISTE E. BUTANAS</b></td>
						</tr>
						<tr>
							<td><br><br>	</td>
						</tr>
						<tr>
							<td colspan=\"2\"></td>
							<td  style=\"text-align:center\" class=\"pt\"><b>MARK / STEPHEN ALEXANDER P. VEGA</b></td>
						</tr>
						<br>
						<tr>
							<td colspan=\"2\" style=\"text-align:right\">Prepared by:<br>Checked by:</td>
							<td  style=\"text-align:left\">
							<b>".strtoupper($_SESSION['abas_login']['fullname'])."<br>JESSA M. ROMEO</b>
							</td>
						</tr>
						
					</table>
	             </div>";
}			

$data['orientation']		=	"P";
$data['pagetype']			=	"letter";
$data['title']				=	"Statement of Account - Control No." . $soa['control_number'];
$data['control_number']		=	"Tran. Code No." .$soa['id'];
$data['content']			=	$content;	
$data['signatories']		=	"<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>".$footer;

$this->load->view('pdf-container.php',$data);

?>