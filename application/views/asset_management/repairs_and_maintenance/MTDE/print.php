<?php	

$prepared_by	=	"<u>".$MTDE['created_by']."<br><br>Date:".date('j F Y',strtotime($MTDE['created_on']))."</u>";
$assessed_by	=	"_______________________<br>Technician<br>Date:______________";
$verified_by	=	"<u>".$MTDE['verified_by']."</u><br>Chief Mechanic<br>Date:<u>".date('j F Y',strtotime($MTDE['verified_on']))."</u>";

$content1 =  '
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
							<h1 class="text-center">'. $MTDE['company_name'] .'</h1>
			    			<h3 class="text-center">'. $MTDE['company_address'].'</h3>
			    			<h3 class="text-center">'. $MTDE['company_contact'].'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="5">
					<tr>
						<td colspan="7" class="btx">MTDE No.</td>
						<td colspan="1" class="btx" align="left">'.$MTDE['control_number'].'</td>
					</tr>
					<tr>
						<td colspan="8"><h2>MOTORPOOL TRUCK DIAGNOSTIC EVALUATION<br></h2></td>
					</tr>				
					<tr>
						<td colspan="1" class="bt">Driver\'s Name:</td>
						<td colspan="4"><u>'.$MTDE['driver'].'</u></td>
						<td colspan="1" class="bt">Date Created:</td>
						<td colspan="2"><u>'.date("j F Y h:m A", strtotime($MTDE['created_on'])).'</u></td>
					</tr>
					<tr>
						<td colspan="1" class="bt">Plate No.:</td>
						<td colspan="4"><u>'.$MTDE['plate_number'].'</u></td>
						<td colspan="1" class="bt">TRMRF No.:</td>
						<td colspan="2"><u>'.$MTDE['TRMRF_number'].'</u></td>
					</tr>
					<tr>
						<td colspan="1" class="bt">Make:</td>
						<td colspan="4"><u>'.$MTDE['make'].'</u></td>
						<td colspan="1" class="bt">Engine No.:</td>
						<td colspan="2"><u>'.$MTDE['engine_number'].'</u></td>

					</tr>
					<tr>
						<td colspan="1" class="bt">Model:</td>
						<td colspan="4"><u>'.$MTDE['model'].'</u></td>
						<td colspan="1" class="bt">Chassis No.:</td>
						<td colspan="2"><u>'.$MTDE['chassis_number'].'</u></td>
					</tr>
					<tr>
						<td colspan="8">
						<b>INSTRUCTION:</b><i>  All items must undergo Preventive Maintenance at 30 days or as necessary and so indicated by manufacturer.</i>
						</td>
					</tr>
				</table>
			</div>

			<div>
				<table border="1" cellpadding="5" width="100%">
				 <col style="width:50%">
  				 <col style="width:10%">
  				 <col style="width:40%">
					<thead>
	                    <tr bgcolor="#000000" color="#FFFFFF">
	                      <th style="width:400px">Item/Particulars</th>
	                      <th style="width:110px">Rating</th>
	                      <th style="width:212px">Remarks</th>
	                    </tr>
	                </thead>
	                <tbody>
	         ';  

foreach($steps as $step){
	
	$content1 .=  '<tr bgcolor="#c4c4c4" color="#000000">
					<td colspan="6" style="font-weight:bold"> '.$step[0].' - '.$step[1].'</td>
				  </tr>';

    foreach($MTDE_details as $item){
		if($item->index==$step[0]){

			  $item_make = $item->make;
			  if($item_make !="N/A"){
			  	$item_make = "Make: ".$item->make."<br>";
			  }else{
			  	$item_make = "";
			  }
			  $item_model = $item->model;
			  if($item_model!="N/A"){
			  	$item_model = "Model: ".$item->model."<br>";
			  }else{
			  	$item_model = "";
			  }

			  $content1 .=  '<tr>
		                      <td style="width:400px">  '. $item->index.".".$item->set.".".$item->sub_set ." - ". $item->evaluation_item_name .'</td>
		                      <td style="width:110px" align="center">'. $item->rating .'</td>
		                      <td style="width:212px">'. $item_make . $item_model . $item->remarks .'</td>
						    </tr>';
		}
	}
	
}

$content1 .=  '  </tbody>
				</table>
			</div>';

$content2 =	'<style type="text/css">	 
				 td {font-size:160%;}
			</style>
			<br><br><div>
				<table border="1" cellpadding="2" width="100%">
					<tr bgcolor="#000000" color="#FFFFFF">
						<td style="font-weight:bold"> Notes/Others/General Remarks:</td>
					</tr>
					<tr>
						<td>'.$MTDE['notes'].'</td>
					</tr>
				</table>
			</div>
			<div>
				<table border="0" cellpadding="8" width="100%">
					<tr>
						<td style="font-weight:bold">Prepared by:<br><br></td>
						<td style="font-weight:bold">Assessed by:<br><br></td>
						<td style="font-weight:bold">Verified by:<br><br></td>
					</tr>
					<tr>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$MTDE["created_by_signature"].'"></td>
						<td></td>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$MTDE["verified_by_signature"].'"></td>
					</tr>
					<tr>
						<td>'.$prepared_by.'</td>
						<td>'.$assessed_by.'</td>
						<td>'.$verified_by.'</td>
					</tr>

				</table>
			</div>
			';

$data['orientation']		=	"P";
$data['pagetype']			=	"letter";
$data['title']				=	"Motorpool Truck Diagnostic Evaluation - Control No." . $MTDE['control_number'];
$data['content'][0]			=	$content1;
$data['content'][1]			=	$content2;
$data['control_number']		=	"Transaction Code No." . $MTDE['id'];

if($MTDE['status']=="Draft"){
	$data['watermark']	=	"DRAFT";
}

$this->load->view('pdf-container.php',$data);
?>