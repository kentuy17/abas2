<?php 

$content = "<style type=\"text/css\">
				.rt td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
				.rt th{text-align:center;font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;}
				 h1 { font-size:180%;text-align:center; }
				 h2,h3 { text-align:center;font-size:130% }	
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
				.tdc {width:240px;}
				.tdx {width:20px;text-align:center}
				.tdb {font-weight:bold;width:100px;}
				.tdr {width:600px;}
				.tdv {width:300px;}
				 td {font-size:130%}
				.text-center {background-color:gray; text-align:center;}
			</style>";

$content .= "<br>
			<table border=\"0\" cellspacing=\"2\">
				<tr>
					<td><img src=\"". PDF_LINK . "assets/images/AvegaLogo.jpg\" alt=\"Avega_Logo\" style=\"width:320px;height:300px;\"></td>
					<td colspan=\"8\">
						<h1 align=\"left\">  ".$SO->company_name."</h1>
						<h2 align=\"left\">  ".$SO->company_address."</h2>
						<h3 align=\"left\">  ".$SO->company_contact."</h3>
					</td>
				</tr>
				<tr>
					<td colspan=\"7\"></td>
					<td ><h1 align=\"right\">No. ".$SO->control_number."</h1></td>
				</tr>
			</table>
			<br>
			<h1 style=\"font-size:250%\">SERVICE ORDER</h1>";

if($SO->type=="Shipping"){
	$content .=	"<table border=\"0\" cellspacing=\"3\">
					<tr>
						<td class=\"tdb\">Contract Ref. No.</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['reference_no']."</td>
						<td class=\"tdb\">Date Needed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO->date_needed))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Service Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->type."</td>
						<td class=\"tdb\">Cargo Description</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->cargo_description."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Vessel</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->vessel."</td>
						<td class=\"tdb\">Quantity/Volume</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->quantity." ".$SO_detail->unit."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Port of Loading</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->from_location."</td>
						<td class=\"tdb\">Client</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['client']['company']."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Port of Discharge</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->to_location."</td>
					</tr>
				</table>";
}
elseif($SO->type=="Lighterage"){
	$content .=	"<table border=\"0\" cellspacing=\"3\">
					<tr>
						<td class=\"tdb\">Contract Ref. No.</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['reference_no']."</td>
						<td class=\"tdb\">Date Needed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO->date_needed))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Service Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->type."</td>
						<td class=\"tdb\">Cargo Description</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->cargo_description."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Servicing Vessel:</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->vessel."</td>
						<td class=\"tdb\">Quantity/Weight</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->quantity." ".$SO_detail->unit."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Source/Vessel</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->source_vessel."</td>
						<td class=\"tdb\">Client</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['client']['company']."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Source Location</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->vessel_location."</td>
						<td class=\"tdb\">Discharge Location</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->discharge_location."</td>
					</tr>
				</table>";
}
elseif($SO->type=="Time Charter"){
	$content .=	"<table border=\"0\" cellspacing=\"3\">
					<tr>
						<td class=\"tdb\">Contract Ref. No.</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['reference_no']."</td>
						<td class=\"tdb\">Date Needed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO->date_needed))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Service Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->type."</td>
						<td class=\"tdb\">Cargo Description</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->cargo_description."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Vessel</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->vessel."</td>
						<td class=\"tdb\">Duration</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->quantity." ".$SO_detail->unit."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Start Location</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->start_location."</td>
						<td class=\"tdb\">Client</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['client']['company']."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Start Date-Time</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y H:m A", strtotime($SO_detail->start_datetime))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">End Location</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->end_location."</td>
					</tr>
					<tr>
						<td class=\"tdb\">End Date-Time</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y H:m A", strtotime($SO_detail->end_datetime))."</td>
					</tr>
				</table>";
}
elseif($SO->type=="Towing"){
	$content .=	"<table border=\"0\" cellspacing=\"3\">
					<tr>
						<td class=\"tdb\">Contract Ref. No.</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['reference_no']."</td>
						<td class=\"tdb\">Date Needed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO->date_needed))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Service Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->type."</td>
						<td class=\"tdb\">Cargo Description</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->cargo_description."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Servicing Vessel</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->vessel."</td>
						<td class=\"tdb\">Duration</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->quantity." ".$SO_detail->unit."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Craft Towed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->craft_towed."</td>
						<td class=\"tdb\">Client</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['client']['company']."</td>
					</tr>
					<tr>
						<td class=\"tdb\">From Location</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->from_location."</td>
						<td class=\"tdb\">To Location</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->to_location."</td>
					</tr>
				</table>";
}
elseif($SO->type=="Trucking"){

	//$drop_off_points = explode(" | ",$SO_detail->to_location);

	if($SO_detail->drop_off_point_1!=""){
		$drop_off_point_1 = $SO_detail->drop_off_point_1;
		$drop_off_quantity_1 = $SO_detail->drop_off_quantity_1." ".$SO_detail->unit;
	}else{
		$drop_off_point_1 = "NA";
		$drop_off_quantity_1 = "NA";
	}
	if($SO_detail->drop_off_point_2!=""){
		$drop_off_point_2 = $SO_detail->drop_off_point_2;
		$drop_off_quantity_2 = $SO_detail->drop_off_quantity_2." ".$SO_detail->unit;
	}else{
		$drop_off_point_2 = "NA";
		$drop_off_quantity_2 = "NA";
	}
	if($SO_detail->drop_off_point_3!=""){
		$drop_off_point_3 = $SO_detail->drop_off_point_3;
		$drop_off_quantity_3 = $SO_detail->drop_off_quantity_3." ".$SO_detail->unit;
	}else{
		$drop_off_point_3 = "NA";
		$drop_off_quantity_3 = "NA";
	}
	if($SO_detail->drop_off_point_4!=""){
		$drop_off_point_4 = $SO_detail->drop_off_point_4;
		$drop_off_quantity_4 = $SO_detail->drop_off_quantity_4." ".$SO_detail->unit;
	}else{
		$drop_off_point_4 = "NA";
		$drop_off_quantity_4 = "NA";
	}

	$content .=	"<table border=\"0\" cellspacing=\"3\">
					<tr>
						<td class=\"tdb\">Contract Ref. No.</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['reference_no']."</td>
						<td class=\"tdb\">Date Needed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO->date_needed))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Service Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->type."</td>
						<td class=\"tdb\">Loading Point</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->from_location."</td>
						
					</tr>
					<tr>
						<td class=\"tdb\">Client</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['client']['company']."</td>
						<td class=\"tdb\">Cargo Description</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->cargo_description."</td>	
						
					</tr>
					<tr>
						<td class=\"tdb\">Drop-off Point 1</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$drop_off_point_1."</td>
						<td class=\"tdb\">Quantity</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$drop_off_quantity_1."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Drop-off Point 2</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$drop_off_point_2."</td>
						<td class=\"tdb\">Quantity</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$drop_off_quantity_2."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Drop-off Point 3</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$drop_off_point_3."</td>
						<td class=\"tdb\">Quantity</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$drop_off_quantity_3."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Drop-off Point 4</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$drop_off_point_4."</td>
						<td class=\"tdb\">Quantity</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$drop_off_quantity_4."</td>
					</tr>
				</table>";
}
elseif($SO->type=="Handling"){
	$content .=	"<table border=\"0\" cellspacing=\"3\">
					<tr>
						<td class=\"tdb\">Contract Ref. No.</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['reference_no']."</td>
						<td class=\"tdb\">Date Needed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO->date_needed))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Service Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->type."</td>
						<td class=\"tdb\">Cargo Description</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->cargo_description."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Client</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['client']['company']."</td>
						<td class=\"tdb\">Quantity/Volume</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->quantity." ".$SO_detail->unit."</td>
					</tr>
				</table>";
}elseif($SO->type=="Storage"){
	$content .=	"<table border=\"0\" cellspacing=\"3\">
					<tr>
						<td class=\"tdb\">Contract Ref. No.</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['reference_no']."</td>
						<td class=\"tdb\">Date Needed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO->date_needed))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Service Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->type."</td>
						<td class=\"tdb\">Cargo Description</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->cargo_description."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Client</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['client']['company']."</td>
						<td class=\"tdb\">Storage Location</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->storage_location."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Quantity/Volume</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->quantity." ".$SO_detail->unit."</td>
						<td class=\"tdb\">Start Date</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO_detail->start_date))."</td>
					</tr>
					<tr>
						<td class=\"tdb\"></td>
						<td class=\"tdx\"></td>
						<td class=\"tdc\"></td>
						<td class=\"tdb\">End Date</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO_detail->end_date))."</td>
					</tr>
				</table>";
}elseif($SO->type=="Equipment Rental"){
	$content .=	"<table border=\"0\" cellspacing=\"3\">
					<tr>
						<td class=\"tdb\">Contract Ref. No.</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['reference_no']."</td>
						<td class=\"tdb\">Date Needed</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y", strtotime($SO->date_needed))."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Service Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->type."</td>
						<td class=\"tdb\">Equipment Name/Type</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->equipment_name."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Client</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO->contract['client']['company']."</td>
						<td class=\"tdb\">From</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->from_location."</td>
					</tr>
					<tr>
						<td class=\"tdb\">Quantity</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->quantity." ".$SO_detail->unit."</td>
						<td class=\"tdb\">To</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".$SO_detail->to_location."</td>
					</tr>
					<tr>
						<td class=\"tdb\"></td>
						<td class=\"tdx\"></td>
						<td class=\"tdc\"></td>
						<td class=\"tdb\">Start Date/Time</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y H:m A", strtotime($SO_detail->start_date))."</td>
					</tr>
					<tr>
						<td class=\"tdb\"></td>
						<td class=\"tdx\"></td>
						<td class=\"tdc\"></td>
						<td class=\"tdb\">End Date/TIme</td>
						<td class=\"tdx\">:</td>
						<td class=\"tdc\">".date("j F Y H:m A", strtotime($SO_detail->end_date))."</td>
					</tr>
				</table>";
}


$content .=	"<br><br><br>
			 <table border=\"0\">
				<tr>
					<td class=\"tdb\">Remarks</td>
					<td class=\"tdx\">:</td>
					<td class=\"tdr\">".$SO->remarks."</td>
				</tr>
			 </table>";



$content .= '<br><br>
			<div>
				<table border="0">
					<tr>
						<td class=\"tdv\"><b>Prepared By:</b></td>
					<td class=\"tdv\"><b>Verified and Approved By:</b></td>
					</tr>
					<tr>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$SO->created_by_signature.'" width="80" height="80"/></td>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$SO->approved_by_signature.'" width="80" height="80"/></td>
					</tr>
					<tr>
						<td><u>'.$SO->full_name.'</u></td>
						<td><u>'.$SO->approved_by_full_name.'</u></td>
					</tr>
					<tr>
						<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($SO->created_on)).'</u></td>
						<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($SO->approved_on)).'</u></td>
					</tr>
				</table>
			</div>';

$data['orientation']		=	"L";
$data['pagetype']			=	"letter";
$data['title']				=	"Service Order No." . $SO->control_number;
$data['control_number']		=	"Transaction Code No." .$SO->id;
$data['content'][0]			=	$content."<br><i>Marketing Dept. Copy</i><br><hr>";
$data['content'][1]		=	$content."<br><i>Operations Dept. Copy</i><br><hr>";

$this->load->view('pdf-container.php',$data);
?>