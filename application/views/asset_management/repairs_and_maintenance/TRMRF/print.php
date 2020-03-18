<?php	

$prepared_by	=	"<u>".$TRMRF['created_by']."<br>>Date:".date('j F Y',strtotime($TRMRF['created_on']))."</u>";
$verified_by	=	"<u>".$TRMRF['verified_by']."<br>Date:".date('j F Y',strtotime($TRMRF['verified_on']))."</u>";
$approved_by	=	"<u>".$TRMRF['approved_by']."<br>Date:".date('j F Y',strtotime($TRMRF['approved_on']))."</u>";

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
							<h1 class="text-center">'. $TRMRF['company_name'] .'</h1>
			    			<h3 class="text-center">'. $TRMRF['company_address'].'</h3>
			    			<h3 class="text-center">'. $TRMRF['company_contact'].'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="5">
					<tr>
						<td colspan="9" class="btx">TRMRF No.</td>
						<td colspan="1" class="btx" align="left">'.$TRMRF['control_number'].'</td>
					</tr>
					<tr>
						<td colspan="10"><h2>TRUCK REPAIRS AND MAINTENANCE REPORT FORM<br></h2></td>
					</tr>				
					<tr>
						<td colspan="2" class="bt">Driver:</td>
						<td colspan="3"><u>'.$TRMRF['driver'].'</u></td>
						<td colspan="2" class="bt">Date Created:</td>
						<td colspan="3"><u>'.date("j F Y h:m A", strtotime($TRMRF['created_on'])).'</u></td>
					</tr>
					<tr>
						<td colspan="2" class="bt">Plate Number:</td>
						<td colspan="3"><u>'.$TRMRF['plate_number'].'</u></td>
						<td colspan="2" class="bt">Engine Number:</td>
						<td colspan="3"><u>'.$TRMRF['engine_number'].'</u></td>
					</tr>
					<tr>
						<td colspan="2" class="bt">Make:</td>
						<td colspan="3"><u>'.$TRMRF['make'].'</u></td>
						<td colspan="2" class="bt">Chassis Number:</td>
						<td colspan="3"><u>'.$TRMRF['chassis_number'].'</u></td>
					</tr>
					<tr>
						<td colspan="2" class="bt">Model:</td>
						<td colspan="3"><u>'.$TRMRF['model'].'</u></td>
						<td colspan="2" class="bt">Current Location:</td>
						<td colspan="3"><u>'.$TRMRF['location'].'</u></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="1" cellpadding="5" width="100%">
					<thead>
	                    <tr bgcolor="#000000" color="#FFFFFF">
	                      <th style="width:40px">#</th>
	                      <th style="width:230px">Complaints</th>
	                      <th style="width:225px">Cause and Corrections</th>
	                      <th style="width:225px">Remarks</th>
	                    </tr>
	                </thead>
	                <tbody>
	         ';  

	$ctr = 1;
    foreach($TRMRF_details as $detail){
		
	  $content .=  '<tr>
	  				  <td style="width:40px;text-align:center">'.$ctr.'</td>
                      <td style="width:230px">'.$detail->complaints.'</td>
                      <td style="width:225px">'.$detail->cause_corrections.'</td>
                      <td style="width:225px">'. $detail->remarks.'</td>
				    </tr>';

		$ctr++;
	}

$content .=  '  </tbody>
				</table>
			</div>
			<div>

				<table border="0" cellpadding="8" width="100%">
					<thead>
						<tr>
							<td style="font-weight:bold">Prepared by:<br><br></td>
							<td style="font-weight:bold">Verified by:<br><br></td>
							<td style="font-weight:bold">Approved by:<br><br></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$TRMRF["created_by_signature"].'"></td>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$TRMRF["verified_by_signature"].'"></td>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$TRMRF["approved_by_signature"].'"></td>
						</tr>
						<tr>
							<td>'.$prepared_by.'</td>
							<td>'.$verified_by.'</td>
							<td>'.$approved_by.'</td>
						</tr>
					</tbody>
				</table>
			</div>';

$data['orientation']	=	"P";
$data['pagetype']		=	"letter";
$data['title']			=	"Truck Repairs and Maintenance Report Form - Control No." . $TRMRF['control_number'];
$data['content']		=	$content;
$data['control_number']		=	"Transaction Code No." . $TRMRF['id'];

if($TRMRF['status']=="Draft"){
	$data['watermark']	=	"DRAFT";
}

$this->load->view('pdf-container.php',$data);
?>