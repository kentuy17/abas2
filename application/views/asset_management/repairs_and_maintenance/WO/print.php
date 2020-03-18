<?php	

$prepared_by	=	"<u>".$WO['created_by']."<br>Date:".date('j F Y',strtotime($WO['created_on']))."</u>";
$verified_by	=	"<u>".$WO['verified_by']."<br>Date:".date('j F Y',strtotime($WO['verified_on']))."</u>";
$approved_by	=	"<u>".$WO['approved_by']."<br>Date:".date('j F Y',strtotime($WO['approved_on']))."</u>";

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
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h1 class="text-center">'. $WO['company_name'] .'</h1>
			    			<h3 class="text-center">'. $WO['company_address'].'</h3>
			    			<h3 class="text-center">'. $WO['company_contact'].'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="5">
					<tr>
						<td colspan="9" class="btx">WO No.</td>
						<td colspan="1" class="btx" align="left">'.$WO['control_number'].'</td>
					</tr>
					<tr>
						<td colspan="10"><h2>WORK ORDER<br></h2></td>
					</tr>				
					<tr>
						<td colspan="2" class="bt">Vessel Name:</td>
						<td colspan="4"><u>'.$WO['vessel_name'].'</u></td>
						<td colspan="2" class="bt">Date Created:</td>
						<td colspan="3"><u>'.date("j F Y h:m A", strtotime($WO['created_on'])).'</u></td>
					</tr>
					<tr>
						<td colspan="2" class="bt">Current Location:</td>
						<td colspan="4"><u>'.$WO['location'].'</u></td>
						<td colspan="2" class="bt">Requisitioner:</td>
						<td colspan="3"><u>'.$WO['requisitioner'].'</u></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="1" cellpadding="5" width="100%">
					<thead>
	                    <tr bgcolor="#000000" color="#FFFFFF">
	                      <th style="width:40px">#</th>
	                      <th style="width:330px">Complaint/Particulars</th>
	                      <th style="width:350px">Status/Remarks</th>
	                    </tr>
	                </thead>
	                <tbody>
	         ';  

	$ctr = 1;
    foreach($WO_details as $detail){
		
	  $content .=  '<tr>
	  				  <td style="width:40px;text-align:center">'.$ctr.'</td>
                      <td style="width:330px">'.$detail->complaint_particulars.'</td>
                      <td style="width:350px">'. $detail->status_remarks.'</td>
				    </tr>';

		$ctr++;
	}

$content .= '</tbody>
			</table>
			<br><br><br>
			<div>
				<table border="0" cellpadding="8" width="100%">
					<tr>
						<td style="font-weight:bold">Prepared by:<br><br></td>
						<td style="font-weight:bold">Approved by:<br><br></td>
					</tr>
					<tr>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$WO["created_by_signature"].'"></td>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$WO["approved_by_signature"].'"></td>
					</tr>
					<tr>
						<td><u>'.$WO["full_name"].'</u></td>
						<td><u>'.$WO["approved_by_full_name"].'</u></td>
					</tr>
					<tr>
						<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($WO["created_on"])).'</u></td>
						<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($WO["approved_on"])).'</u></td>
					</tr>
				</table>
			</div>';

$data['orientation']	=	"P";
$data['pagetype']		=	"letter";
$data['title']			=	"Vessel Work Order - Control No." . $WO['control_number'];
$data['content']		=	$content;
$data['control_number']		=	"Transaction Code No." . $WO['id'];

if($WO['status']=="Draft"){
	$data['watermark']	=	"DRAFT";
}

$this->load->view('pdf-container.php',$data);
?>