<?php

$content =  '<style type="text/css">
				 h1 { font-size:200%;text-align:center; }
				 h2 { font-size:150%;text-align:center; }	
				 h3 { font-size:100%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:160%;text-align:center;}
				 th { font-weight:bold;font-size:150%;text-align:center;vertical-align:middle;}
				  p { text-align:left;font-size:150%; }
				.bt { font-weight:bold; text-align:left font-size:190%;}
				.btx { font-weight:bold; text-align:right; font-size:190%}
			</style>
			<br>
		    <div>
				<table border="0">
					<tr>
						<td><img src="'. LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h2 class="text-left" style="text-align:left">'. $company->name .'</h2>
			    			<h3 class="text-left" style="text-align:left">'. $company->address.'</h3>
			    			<h3 class="text-left" style="text-align:left">'. $company->telephone_no.'</h3>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="text-align:right"><b>Control No.:</b></td>
						<td style="text-align:left"> '.$request[0]['control_number'].'</td>
					</tr>
					<tr>
						<td colspan="12"><br><h1>Request for Payment</h1><br></td>
					</tr>
					<tr>
						<td colspan="4" style="text-align:right"><br><b>Date:<br></b></td>
						<td style="text-align:left"> '.date('m-d-Y',strtotime($request[0]['request_date'])).'</td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="text-align:left"><b>Payment To:</b></td>
						<td colspan="7" style="text-align:left"><u> '.$payee.'</u></td>
					</tr>';
		if($request[0]['reference_document']!="None"){
			$reference = $request[0]['remark'];
		}else{
			$reference = "None";
		}
$content .=			'<tr>
						<td style="text-align:left"><b>Reference:</b></td>
						<td colspan="7" style="text-align:left"><u> '.$reference .'</u></td>
					</tr>
					<tr>
						<td style="text-align:left"><b>Purpose:</b></td>
						<td colspan="7" style="text-align:left"><u> '.$request[0]['purpose'].'</u></td>
					</tr>
				</table>
			</div>';

$content .= '<div><table border="1">
				<thead>
					<tr bgcolor="#000000" color="#FFFFFF">
	      				<th style="width:50px">#</th>
	      				<th style="width:440px">Particulars</th>	
	      				<th style="width:100px">Amount</th>
	      				<th style="width:132px">Charge To</th>
	      			</tr>
      			</thead>
              	<tbody>';

              	$ctr=1;
              	$total_amount=0;
				if($request_details){
					foreach($request_details as $detail){
						$content .= "<tr>";
							$content .= "<td style=\"width:50px\">".$ctr."</td>";
							$content .= "<td style=\"width:440px\">".$detail['particulars']."</td>";
							$content .= "<td style=\"width:100px\">".number_format($detail['amount'],2,'.',',')."</td>";
							if($detail['charge_to']!=0){
								$charge_to = $this->Abas->getVessel($detail['charge_to']);
								$content .=  "<td style=\"width:132px\">".$charge_to->name."</td>";
							}else{
								$content .=  "<td style=\"width:132px\">--</td>";
							}
						$content .= "</tr>";
						$total_amount = $total_amount + $detail['amount'];
						$ctr++;
					}
						$content .= "<tr>
										<td colspan=\"2\" style=\"text-align:right\"><b>Total Amount: </b></td>
										<td><b>".number_format($total_amount,2,'.',',')."</b></td>
									</tr>";
				}else{
					$content .= "<tr>";
						$content .= "<td colspan=\"5\">Not Applicable</td>";
					$content .= "</tr>";
				}

$content .= '	</tbody>
	        </table></div>';

$content .= '<div><br><br><br>
				<table border="0">
					<tr>
						<td style="text-align:left"><b>Requested By:</b><br><br></td>
						<td style="text-align:left"><b>Verified By:</b><br><br></td>
						<td style="text-align:left"><b>Approved By:</b><br><br></td>
					</tr>';

$content .=			'<tr>
						<td style="text-align:left"><img src="'.LINK.'assets/images/digitalsignatures/'.$request[0]['created_by_name']['signature'].'" /></td>
						<td style="text-align:left"><img src="'.LINK.'assets/images/digitalsignatures/'.$request[0]['verified_by_name']['signature'].'" /></td>
						<td style="text-align:left"><img src="'.LINK.'assets/images/digitalsignatures/'.$request[0]['approved_by_name']['signature'].'" /></td>
					</tr>';
$content .=			'<tr>
						<td style="text-align:left"><u>'.$request[0]['created_by_name']['full_name'].'</u></td>
						<td style="text-align:left"><u>'.$request[0]['verified_by_name']['full_name'].'</u></td>
						<td style="text-align:left"><u>'.$request[0]['approved_by_name']['full_name'].'</u></td>
					</tr>	
				</table>
			</div>';



$data['orientation']		=	"P";
$data['pagetype']			=	"letter";
$data['title']				=	"Request for Payment No." . $request[0]['control_number'];
$data['control_number']		=	"Transaction Code No." .$request[0]['id'];
$data['content']			=	$content;

$this->load->view('pdf-container.php',$data);
?>

