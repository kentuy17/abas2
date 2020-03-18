<?php	

$prepared_by	=	"<center><u><b>".$_SESSION['abas_login']['fullname']."</b></u><br>Cashier/Authorized Signatory<br><br>Date:________________</center>";

$content =  '
			<style type="text/css">
				 h1 { font-size:200%;text-align:center; }
				 h2 { font-size:150%;text-align:center; }	
				 h3 { font-size:100%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:160%;}
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
			</style>
			<br>
		    <div>
				<table>
					<tr>
						<td><!--<img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo">--></td>
						<td colspan="4">
							<h1 class="text-center">'. $acknowledgement['company_name'] .'</h1>
			    			<h3 class="text-center">'. $acknowledgement['company_address'].'</h3>
			    			<h3 class="text-center">'. $acknowledgement['company_contact'].'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="5">
					<tr>
						<td colspan="9" class="btx">AR No.</td>
						<td colspan="1" class="btx" align="left">'.$acknowledgement['control_number'].'</td>
					</tr>
					<tr>
						<td colspan="10"><h2>ACKNOWLEDGEMENT RECEIPT<br></h2></td>
					</tr>				
					<tr>
						<td colspan="2" class="bt">Received from:</td>
						<td colspan="4"><u>'.$acknowledgement['received_from'].'</u></td>
						<td colspan="2" class="bt">Date:</td>
						<td colspan="3"><u>'.date("j F Y h:m A", strtotime($acknowledgement['received_on'])).'</u></td>
					</tr>
					<tr>
						<td colspan="2" class="bt">TIN:</td>
						<td colspan="4"><u>'.$acknowledgement['TIN'].'</u></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="1" cellpadding="5" width="100%">
					<thead>
						<tr bgcolor="#000000" color="#FFFFFF">';

	                    if($acknowledgement['type']=='Cash'){

	                     $content .= '<th style="width:40px">#</th>
			                      <th style="width:225px">Denomination</th>
			                      <th style="width:225px">Quantity</th>
			                      <th style="width:225px">Amount</th>';

			                $breakdown = $this->Collection_model->getARCashBreakdown($acknowledgement['id']);

	                    }else{

	                     $content .= '<th style="width:40px">#</th>
			                      <th style="width:140px">Bank</th>
			                      <th style="width:140px">Branch</th>
			                      <th style="width:140px">Check No.</th>
			                      <th style="width:120px">Check Date</th>
			                      <th style="width:140px">Amount</th>';

			                $breakdown = $this->Collection_model->getARCheckBreakdown($acknowledgement['id']);
	                    }

	  $content .=    '	</tr>
	  				</thead>
	            	<tbody>';
	   

	$ctr = 1;
	$total = 0;
    foreach($breakdown as $detail){
		
		if($acknowledgement['type']=='Cash'){
			  $content .=  '<tr>
			  				  <td style="width:40px;text-align:center">'.$ctr.'</td>
		                      <td style="width:225px;text-align:center">'. number_format($detail->denomination,2,'.',',').'</td>
		                      <td style="width:225px;text-align:center">'. $detail->quantity.'</td>
		                      <td style="width:225px" align="right">'. number_format($detail->amount,2,'.',',').'</td>
						    </tr>';

			 $col = 3;
		}else{
			  $content .=  '<tr>
			  				  <td style="width:40px;text-align:center">'.$ctr.'</td>
		                      <td style="width:140px">'. $detail->bank_name.'</td>
		                      <td style="width:140px">'. $detail->bank_branch.'</td>
		                      <td style="width:140px">'. $detail->check_number.'</td>
		                      <td style="width:120px">'. $detail->check_date.'</td>
		                      <td style="width:140px" align="right">'. number_format($detail->amount,2,'.',',').'</td>
						    </tr>';
			  $col = 5;
		}

	  $total = $total + $detail->amount;

	$ctr++;
	}

	/*$content .= "<tr align=\"right\">
	 				<td colspan=\"5\">Checks:</td>
	 				<td>" . number_format($total,2,'.',',') . "</td>
	 			  </tr>";
	$content .= "<tr align=\"right\">
	 				<td colspan=\"5\">Cash:</td>
	 				<td>" . number_format($total,2,'.',',') . "</td>
	 			  </tr>";*/

	$content .= "<tr align=\"right\">
	 				<td colspan=\"".$col."\">Total:</td>
	 				<td>PHP " . number_format($total,2,'.',',') . "</td>
	 			  </tr>";
	$content .= "<tr>
	 				<td colspan=\"6\">Purpose of payment/Remarks: <u>" .$acknowledgement['remarks']. "</u></td>
	 			 </tr>";

$content .=  '  </tbody>
				</table>
			</div>
			<div>

				<table border="0" cellpadding="8" width="100%">
					<br><br><br>
					<tr align="center">
						<td>'.$prepared_by.'</td>
					</tr>
				</table>
			</div>';

$data['orientation']	=	"P";
$data['pagetype']		=	"letter";
$data['title']			=	"Acknowledgement Receipt - Control No." . $acknowledgement['control_number'];
$data['content']		=	$content;
$data['control_number']		=	"Transaction Code No." . $acknowledgement['id'];

$this->load->view('pdf-container.php',$data);

?>