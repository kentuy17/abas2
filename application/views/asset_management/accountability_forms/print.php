<?php	
$created_by	=	"<u>".$AC->created_by."<br><br>Date:".date('j F Y',strtotime($AC->created_on))."</u>";
$verified_by	=	"<u>".$AC->verified_by."</u><br><br>Date:<u>".date('j F Y',strtotime($AC->verified_on))."</u>";
$approved_by	=	"<u>".$AC->approved_by."<br><br>Date:".date('j F Y',strtotime($AC->approved_on))."</u>";
$received_by	=	"<u>".$AC->requested_by."<br><br>Date:".date('j F Y')."</u>";

$content =  '
			<style type="text/css">
				 h1 { font-size:200%;text-align:center; }
				 h2 { font-size:150%;text-align:center; }	
				 h3 { font-size:100%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:160%;}
				 th { font-weight:bold;font-size:150%;text-align:left;background-color:#ddd}
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
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h1 class="text-center">'. $AC->company_name .'</h1>
			    			<h3 class="text-center">'. $AC->company_address.'</h3>
			    			<h3 class="text-center">'. $AC->company_contact.'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table>
					<tr>
						<td colspan="8"><h2>ACCOUNTABILITY FORM<br></h2></td>
					</tr>
				</table>
				<table border="1" cellpadding="2">
					<tr>
						<th colspan="2">Name of Personnel:</th>
						<td colspan="6"> '.$AC->requested_by.'</td>
					</tr>
					<tr>
						<th colspan="1">Position:</th>
						<td colspan="4"> '.$AC->position.'</td>
						<th colspan="1">AF Control No.:</th>
						<td colspan="4"> '.$AC->control_number.'</td>
					</tr>
					<tr>
						<th colspan="1">Department:</th>
						<td colspan="4"> '.$AC->department.'</td>
						<th colspan="1">Date:</th>
						<td colspan="4"> '.date("F d, Y", strtotime($AC->requested_on)).'</td>
					</tr>
				</table>
			</div>
			

			<div>
				<table border="1" cellpadding="5" width="100%">
				 <col style="width:50%">
  				 <col style="width:10%">
  				 <col style="width:40%">
					<thead>
	                  <tr>
		                  <th>Fixed Asset Code</th>
		                  <th>Item Description</th>
		                  <th>Location</th>
		                  <th>Remarks/Purpose</th>
	                  </tr>
	                </thead>
	                <tbody>
	         ';  

foreach($AC_details as $asset){
	$content .= "<tr>";
	$content .= "<td>".$asset->asset_code."</td>";
  	$content .= "<td>".$asset->item_name.", ".$asset->item_particular."</td>";
  	$content .= "<td>".$asset->location."</td>";
  	$content .= "<td>".$asset->remarks."</td>";
  	$content .= "</tr>";
}

$content .=  '  </tbody>
				</table>
			</div>';

$content .=  '<div>
				<table>
					<tr>
						<td style="text-align:center"><h4>ACKNOWLEDGEMENT</h4></td>
					</tr>
					<tr>
						<td style="text-align:center"><hr></td>
					</tr>
					<tr>
						<td style="text-align:center">This is to acknowledge that I am accountable for the above item(s). I understand that I will pay or replace the same unit(s) in case of
loss or damage due to my fault or negligence. In case of resignation, separation or transfer, I will turnover these items before
issuance of my clearance. Also, I will follow the rules and regulations imposed by the Company and I will be liable for any
consequences that may arise for not complying with the set rules and regulations.<br><hr></td>
					</tr>
				</table>
			</div>';

$content .=	'<style type="text/css">	 
				 td {font-size:160%;}
			</style>
			<br><br>
			<div>
				<table border="0" cellpadding="8" width="100%">
					<tr>
						<td style="font-weight:bold">Prepared by:<br><br></td>
						<td style="font-weight:bold">Verified by:<br><br></td>
						<td style="font-weight:bold">Approved by:<br><br></td>
						<td style="font-weight:bold">Received by:<br><br></td>
					</tr>
					<tr>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$AC->created_by_signature.'"/></td>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$AC->verified_by_signature.'"/></td>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$AC->approved_by_signature.'"/></td>
						<td></td>
					</tr>
					<tr>
						<td>'.$created_by.'</td>
						<td>'.$verified_by.'</td>
						<td>'.$approved_by.'</td>
						<td>'.$received_by.'</td>
					</tr>

				</table>
				
			</div>';


$content2 =  '<style type="text/css">
				 h1 { font-size:200%;text-align:center; }
				 h2 { font-size:150%;text-align:center; }	
				 h3 { font-size:100%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:160%;}
				 th { font-weight:bold;font-size:150%;text-align:left;background-color:#ddd}
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
			<div>
				<table border="0" cellpadding="8" width="100%">
					<tr>
						<td style="text-align:center"><h4>ACCOUNTABILITY CLEARANCE</h4></td>
					</tr>
					<tr>
						<td><i>This is to certify that I have returned the following item(s) in good and working condition:</i></td>
					</tr>
				</table>
				<table border="1" cellpadding="5" width="100%">
				
					<thead>
	                  <tr>
		                  <th>Fixed Asset Code</th>
		                  <th>Item Description</th>
		                  <th>Date Returned<br>(Date & Signature)</th>
		                  <th>Item Received By<br>(Name & Signature)</th>
	                  </tr>
	                </thead>
	                <tbody>';

	                $num_returned =0;
	                foreach($AC_details as $asset){
	                	if($asset->status=='Returned'){
							$content2 .= "<tr>";
							$content2 .= "<td>".$asset->asset_code."</td>";
						  	$content2 .= "<td>".$asset->item_name.", ".$asset->item_particular."</td>";
						  	$content2 .= "<td>".date("F d, Y", strtotime($asset->date_returned))."</td>";
						  	$content2 .= "<td>".$_SESSION['abas_login']['fullname']."</td>";
						  	$content2 .= "</tr>";
						  	$num_returned++;
						}
					}

$content2 .=  '	</tbody>
				</table>
			</div>';

$data['orientation']		=	"P";
$data['pagetype']			=	"letter";
$data['title']				=	"Accountability Form - Control No." . $AC->control_number.$num_returned;

if($num_returned>0){
	$data['content'][0]			=	$content;
	$data['content'][1]			=	$content2;
}else{
	$data['content']			=	$content;
}

$data['control_number']		=	"Transaction Code No." . $AC->id;

$this->load->view('pdf-container.php',$data);
?>