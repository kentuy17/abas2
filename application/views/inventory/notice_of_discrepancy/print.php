<?php
$UD = "";
$OD = "";
$DD = "";
$ND = "";
if(isset($nod)){
foreach($nod_details as $detail){
	switch ($detail['remarks']) {
		case 'Under Delivery':
			$UD = "X";
			$OD = "";
			$DD = "";
			$ND = "";
			break;
		case 'Over Delivery':
			$UD = "";
			$OD = "X";
			$DD = "";
			$ND = "";
			break;
		case 'Over Delivery w/ Freebies':
			$UD = "";
			$OD = "X";
			$DD = "";
			$ND = "";
			break;
		case 'Damaged':
			$UD = "";
			$OD = "";
			$DD = "X";
			$ND = "";
			break;
		case 'Not in conformity with specifications':
			$UD = "";
			$OD = "";
			$DD = "";
			$ND = "X";
			break;
	}
}

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
				.bordered, .n-bordered{
					border: 1px solid black;
					border-collapse: collapse;	
					text-align:center;
					width:40px;
				}
			</style>
			<br>
		    <div>
				<table>
					<tr>
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h1 class="text-center">'. $nod->company_name .'</h1>
			    			<h3 class="text-center">'. $nod->company_address.'</h3>
			    			<h3 class="text-center">'. $nod->company_contact.'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="6">
					<tr>
						<td colspan="9" class="btx">ND No.</td>
						<td colspan="1" class="btx" align="left">'.$nod->control_number.'</td>
					</tr>
					<tr>
						<td colspan="10"><h2>NOTICE OF DISCREPANCY<br></h2></td>
					</tr>				
					<tr>
						<td colspan="2" class="bt">Supplier:</td>
						<td colspan="4"><u>'.$nod->supplier_name.'</u></td>
						<td colspan="2" class="bt">Date of Delivery:</td>
						<td colspan="3"><u>'.date("Y-m-d", strtotime($nod->date_of_delivery)).'</u></td>
					</tr>
					<tr>
						<td colspan="2" class="bt">PO No.:</td>
						<td colspan="4"><u>'.$nod->po[0]['control_number'].'(TS Code No.'.$nod->purchase_order_id.')</u></td>
						<td colspan="2" class="bt">Vehicle Plate No.:</td>
						<td colspan="3"><u>'.$nod->vehicle_plate_number.'</u></td>
					</tr>
					<tr>
						<td colspan="2" class="bt">DR No.:</td>
						<td colspan="4"><u>'.$nod->delivery_receipt_number.'</u></td>
						<td colspan="2" class="bt">Name of Driver:</td>
						<td colspan="3"><u>'.$nod->name_of_driver.'</u></td>
					</tr>
				</table>
			</div>
			<div>
				 <h1 style="text-align:left;">Reason of Discrepancy:</h1><br>
				 <table border="0" cellspacing="5" cellpadding="5" width="100%">
				 	<tr>
				 		<td class="bordered">'.$UD.'</td>
				 		<td style="width:200px">Under Delivery</td>
				 		<td class="bordered">'.$OD.'</td>
				 		<td style="width:200px">Over Delivery</td>
				 		<td class="bordered">'.$DD.'</td>
				 		<td style="width:160px">Damaged</td>
				 	</tr>
				 	<tr>
				 		<td class="bordered">'.$ND.'</td>
				 		<td style="width:300px">Not in conformity with specifications</td>
				 	</tr>
				 </table>
			</div>
			<div>
			 <h1 style="text-align:left;">Details:</h1><br>
				<table border="1" cellpadding="5" width="100%">
					<thead>
	                    <tr bgcolor="#d4d4d4">
	                      <th style="width:250px" >Item Description</th>
	                      <th style="width:80px" >UoM</th>
	                      <th style="width:80px">Per PO</th>
	                      <th style="width:80px">Per DR</th>
	                      <th style="width:80px">Received</th>
	                      <th style="width:152px" >Remarks</th>
	                    </tr>	
	                </thead>
	                <tbody>

	         ';  

	$ctr = 1;
    foreach($nod_details as $detail){
	  $content .=  '<tr>
	  				  <td style="width:250px">'.$detail['item_description'].'</td>
	  				  <td style="width:80px">'.$detail['item_unit'].'</td>
	  				  <td style="width:80px">'.$detail['quantity_po'].'</td>
	  				  <td style="width:80px">'.$detail['quantity_dr'].'</td>
	  				  <td style="width:80px">'.$detail['quantity_received'].'</td>
	  				  <td style="width:152px">'.$detail['remarks'].'</td>
				    </tr>';
	  $ctr++;
	}

$content .=  '  </tbody>
				</table>
			</div>
			<div>
				<h1 style="text-align:left;">Other/Remarks:</h1><br>
				<table cellpadding="10">
					<tr><td class="bordered" width="100%" style="text-align:left">'.$nod->other_remarks.'</td></tr>
				</table>
			</div>
			<div>
				
				<table border="0" cellpadding="8" width="100%">
					<thead>
						<tr>
							<td style="font-weight:bold">Prepared by:</td>
							<td style="font-weight:bold">Verified by:</td>
							<td style="font-weight:bold">Conformed and Acknowledged by:</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$prepared_by_details['signature'].'" width="100" height="100"></td>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$verified_by_details['signature'].'" width="100" height="100"></td>
						</tr>
						<tr>
							<td><u>'.$prepared_by_details['full_name'].'</u></td>
							<td><u>'.$verified_by_details['full_name'].'</u></td>
							<td><u>_________________________________</u></td>
						</tr>
						<tr>
							<td>Date:<u>'.date('j F Y',strtotime($nod->created_on)).'</u></td>
							<td>Date:<u>'.date('j F Y').'</u></td>
							<td>Date:_____________________________</td>
						</tr>
					</tbody>
				</table>

				<table border="0" cellpadding="8" width="100%">
						<tr>
							<td style="font-weight:bold">Approved by:</td>
							';

								if($nod->level2_approved_by!=0){
									$content .= '<td style="font-weight:bold">Approved by:</td>';
								}else{
									$content .=	'<td></td>';
								}

								if($nod->level3_approved_by!=0){
									$content .= '<td style="font-weight:bold">Approved by:</td>';
								}else{
									$content .=	'<td></td>';
								}

$content .= 		'</tr>
						<tr>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$level1_approved_by_details['signature'].'" width="100" height="100"></td>';

						if($nod->level2_approved_by!=0){
							$content .= '<td><img src="'.LINK.'assets/images/digitalsignatures/'.$level2_approved_by_details['signature'].'" width="100" height="100"></td>';
						}else{
							$content .=	'<td></td>';
						}

						if($nod->level3_approved_by!=0){
							$content .= '<td><img src="'.LINK.'assets/images/digitalsignatures/'.$level3_approved_by_details['signature'].'" width="100" height="100"></td>';
						}else{
							$content .=	'<td></td>';
						}


$content .= 		'</tr>
						<tr>
							<td><u>'.$level1_approved_by_details['full_name'].'</u></td>
							<td><u>'.$level2_approved_by_details['full_name'].'</u></td>';
						if($nod->level3_approved_by!=0){
							$content .=	'<td><u>'.$level3_approved_by_details['full_name'].'</u></td>';
						}else{
							$content .=	'<td></td>';
						}

$content .=			'</tr>
						<tr>
							<td>Date:<u>'.date('j F Y').'</u></td>';

							if($nod->level2_approved_by!=0){
								$content .=	'<td>Date:<u>'.date('j F Y').'</u></td>';
							}
							if($nod->level3_approved_by!=0){
								$content .=	'<td>Date:<u>'.date('j F Y').'</u></td>';
							}
$content .=			'</tr>
				</table>
			</div>';

$data['orientation']	=	"P";
$data['pagetype']		=	"legal";
$data['title']			=	"Notice of Discrepancy - Control No." . $nod->control_number;
$data['content']		=	$content;
$data['control_number']		=	"Transaction Code No." . $nod->id;

$this->load->view('pdf-container.php',$data);
}
?>