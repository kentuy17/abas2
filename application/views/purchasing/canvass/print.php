<?php

//$this->Mmm->debug($CR);

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
				th {background-color: black;color: white; font-size: 140%; text-align:center}
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
					<td><img src=\"". LINK . "assets/images/AvegaLogo.jpg\" alt=\"Avega_Logo\" style=\"width:100px;height:80px;\"></td>
					<td colspan=\"8\">
						<h1 align=\"left\">  ".$CR['company']->name."</h1>
						<h2 align=\"left\">  ".$CR['company']->address."</h2>
						<h3 align=\"left\">  ".$CR['company']->telephone_no . " | " .$CR['company']->fax_no."</h3>
					</td>
				</tr>
				<tr>
					<td colspan=\"7\"></td>
					<td ><h1 align=\"right\"></h1></td>
				</tr>
			</table>
			<br>
			<h1 style=\"font-size:250%\">CANVASS REPORT</h1>";

$total_canvass_amount = 0;
foreach($CR['details'] as $canvass_item){
	$total_canvass_amount = $total_canvass_amount + $canvass_item['unit_price'];
}

if($total_canvass_amount>=10000){
	$manner = "Written Open Canvass";
}else{
	$manner = "Verbal/Phone Canvass";
}

$content .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"5px\">
				<tr>
					<td class=\"tdb\">Requisition No.</td>
					<td class=\"tdx\">:</td>
					<td class=\"tdc\">".$CR['id']."</td>
					<td class=\"tdb\">Date of Report</td>
					<td class=\"tdx\">:</td>
					<td class=\"tdc\">".date("j F Y")."</td>
				</tr>
				<tr>
					<td class=\"tdb\">Manner of Canvass</td>
					<td class=\"tdx\">:</td>
					<td class=\"tdc\">".$manner."</td>
					<td class=\"tdb\">Date of Canvass</td>
					<td class=\"tdx\">:</td>
					<td class=\"tdc\">".date("j F Y",strtotime($CR['details'][0]['canvass_approved_on']))."</td>
				</tr>
			</table>
			<br>
		    <table border=\"1\" cellspacing=\"1\" cellpadding=\"5px\">
				<thead>
					<tr>
						<th style=\"width:240px\">Item Description</th>
						<th style=\"width:60px\">Qty</th>
						<th style=\"width:60px\">Unit</th>
						<th style=\"width:250px\">Supplier Name</th>
						<th style=\"width:60px\">Unit Price</th>
						<th style=\"width:100px\">Total</th>
						<th style=\"width:100px\">Option</th>
						<th style=\"width:240px\">Remarks</th>
					</tr>
				</thead>
				<tbody>
					";

$suppliers ="";
$prev_supp = "";
foreach($CR['details'] as $canvass_item){
	if($canvass_item['unit_price']!=""){

		$supplier = $this->Abas->getSupplier($canvass_item['supplier_id']);
		if($canvass_item['status']=="unselected" || $canvass_item['status']=="For Canvass Approval"){
			$option = "Unselected";
		}elseif($canvass_item['status']=="Cancelled"){
			$option = "Cancelled";
		}else{
			$option = "Selected";
		}

		$content .= "	<tr>
							<td style=\"width:240px\">".$canvass_item['item_details']['description'].",".$canvass_item['item_details']['particular']."</td>
							<td style=\"width:60px\">".number_format($canvass_item['quantity'],2,'.',',')."</td>
							<td style=\"width:60px\">".$canvass_item['item_details']['unit']."</td>
							<td style=\"width:250px\">".$supplier['name']."</td>
							<td style=\"width:60px\">".$canvass_item['unit_price']."</td>
							<td style=\"width:100px\">".number_format($canvass_item['unit_price']*$canvass_item['quantity'],2,'.',',')."</td>
							<td style=\"width:100px\">".$option."</td>
							<td style=\"width:240px\">".$canvass_item['remark']."</td>	
						</tr>";
		if($prev_supp!=$supplier['name']){
			$prev_supp = $supplier['name'];
			$suppliers .= "	<tr>
							<td style=\"width:330px\">".$supplier['name']."</td>
							<td style=\"width:390px\">".$supplier['address']."</td>
							<td style=\"width:200px\">".$supplier['telephone_no']." / ".$supplier['fax_no']."</td>
							<td style=\"width:200px\">".$supplier['payment_terms']."</td>
						</tr>";
		}

	}
}

$content .= "
				</tbody>
			</table>
			<br><br><br>
			<table border=\"1\" cellspacing=\"1\"  cellpadding=\"5px\">
				<thead>
					<tr>
						<th style=\"width:330px\">Supplier Name</th>
						<th style=\"width:390px\">Address</th>
						<th style=\"width:200px\">Contact Number</th>
						<th style=\"width:200px\">Payment Terms</th>
					</tr>
				</thead>
				<tbody>".$suppliers;


$prepared_by = $this->Abas->getUser($_SESSION['abas_login']['userid']);

$content .= '</tbody>
			</table>
			<br><br><br>
			<div>
				<table border="0">
					<tr>
						<td style="text-align:left"><b>Prepared By:</b><br><br></td>
						<td style="text-align:left"><b>Verified By:</b><br><br></td>
						<td style="text-align:left"><b>Approved By:</b><br><br></td>
					</tr>
					<tr>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$prepared_by["signature"].'"/></td>
						<td></td>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$CR["details"][0]["canvass_approved_by"]["signature"].'"/></td>
					</tr>
					<tr>
						<td><u>'.$prepared_by["full_name"].'</u></td>
						<td>____________________________</td>
						<td><u>'.$CR["details"][0]["canvass_approved_by"]["full_name"].'</u></td>
					</tr>
					<tr>
						<td>Date:<u>'.date('j F Y',strtotime($CR["details"][0]['added_on'])).'</u></td>
						<td>Date:____________________</td>
						<td>Date:<u>'.date('j F Y',strtotime($CR["details"][0]['canvass_approved_on'])).'</u></td>
					</tr>
				</table>
			</div>';

$data['orientation']		=	"L";
$data['pagetype']			=	"Legal";
$data['title']				=	"Canvass Report - Requisition No." . $CR['id'];
$data['content'] 			= $content;


$this->load->view('pdf-container.php',$data);

?>
