<?php

$content =  '<style type="text/css">
				 h1 { font-size:200%;text-align:left; }
				 h2 { font-size:150%;text-align:center; }	
				 h3 { font-size:100%;text-align:left; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:160%;}
				 th { font-weight:bold;font-size:150%;text-align:center}
				  p { text-align:left;font-size:150%; }
				.bt { font-weight:bold; text-align:center}
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
							<h1 class="text-left">'. $company->name .'</h1>
			    			<h3 class="text-left">'. $company->address.'</h3>
			    			<h3 class="text-left">'. $company->telephone_no.'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="5">
					<tr>
						<td colspan="10"><h2>MONTHLY INVENTORY REPORT<br></h2></td>
					</tr>				
					<tr>
						<td colspan="2" style="text-align:right"><b>For the month of:</b></td>
						<td colspan="4" style="text-align:left"><u>'.date('F-Y',strtotime($report->created_on)).'</u></td>
					</tr>
				</table>
			</div>';  

$content .= '<table border="1" cellpadding="5" width="100%">
				<thead>
					<tr>
						<td class="bt" style="width:80px" rowspan="2">Product/Inventory  Code</td>
						<td class="bt" style="width:190px" rowspan="2">Item Description</td>
						<td class="bt" style="width:80px" rowspan="2">Unit</td>
						<td class="bt" style="width:260px" colspan="3">Location <br>(See Note 1)</td>
						<td class="bt" style="width:100px" rowspan="2">Inventory Evaluation Method <br>(See Note 2)</td>
						<td class="bt" style="width:80px" rowspan="2">Unit Price</td>
						<td class="bt" style="width:80px" rowspan="2">Quantity in Stock</td>
						<td class="bt" style="width:100px" rowspan="2">Unit of Measurement <br>(in weight or volume)</td>
						<td class="bt" style="width:80px" rowspan="2">Total <br>Weight or Volume</td>
						<td class="bt" style="width:80px" rowspan="2">Total Cost</td>
					</tr>
					<tr>
						<td class="bt" style="width:70px" rowspan="1">Address</td>
						<td class="bt" style="width:50px" rowspan="1">Code</td>
						<td class="bt" style="width:140px" rowspan="1">Remarks</td>
					</tr>
				</thead>
				<tbody>';
	$total_cost =0;
	foreach($report_details as $row){
		$item = $this->Inventory_model->getItem($row->item_id);
		$content .= '<tr>';
			$content .= '<td style="width:80px">'.$item[0]['item_code'].'</td>';
			$content .= '<td style="width:190px">'.$item[0]['description'].'</td>';
			$content .= '<td style="width:80px">'.$row->unit.'</td>';
			$content .= '<td style="width:70px">'.$row->location.'</td>';
			$content .= '<td style="width:50px"></td>';
			$content .= '<td style="width:140px"></td>';
			$content .= '<td style="width:100px"></td>';
			$content .= '<td style="width:80px;text-align:right">'.number_format($row->unit_price,2,".",",").'</td>';
			$content .= '<td style="width:80px">'.$row->quantity.'</td>';
			$content .= '<td style="width:100px"></td>';
			$content .= '<td style="width:80px"></td>';
			$unit_cost = $row->unit_price*$row->quantity;
			$content .= '<td style="width:80px;text-align:right">'.number_format($unit_cost,2,".",",").'</td>';
		$content .= "</tr>";
		$total_cost = $total_cost + $unit_cost;
	}

	$content .= '<tr>
					<td colspan="11" style="text-align:right"><b>Total</b></td>
					<td style="text-align:right"><b>PHP '.number_format($total_cost,2,".",",").'</b></td>
				</tr></tbody>
			</table><br><br>';

	$content1 = '<table border="1" cellpadding="1.5">
					<tr>
						<td colspan="11" style="text-align:left"><b>Note 1</b></td>
				    </tr>
				    <tr>
						<td style="width:20px"><b>A.</b></td>
						<td colspan="10" style="width:1107px">Includes all goods whether Company has title thereto or not, provided these goods are actually situtated in location/address. Include also goods out on consignment, though not physically present are nonetheless owned by the Company.</td>
				    </tr>
				    <tr>
						<td style="width:180px"><b>B.</b> Use the following codes:</td>
						<td colspan="10" style="width:947px"><b>CH</b> - Goods held under consignment by the Company (indicate name of consigner in the Remarks column) 
							<br><b>CO</b> - Goods out on consignment held by other entity (indicate name of consignor in the Remarks column)
						    <br><b>H</b> - Goods held which are owned by other parties (indicate name of other party in the Remarks column)
						    <br><b>O</b> - Goods on hand owned by the Company
						    </td>
				    </tr>
				    <tr>
						<td colspan="11" style="text-align:left"><b>Note 2</b></td>
				    </tr>
				    <tr>
						<td colspan="10">Indicate Costing Method applied, e.g., Standard Costing, FIFO, Weighted Average, Specific Identification, etc.</td>
				    </tr>
			     </table>';

$content1 .= '<br><br><br><table border="0" cellpadding="8" width="100%">
					<thead>
						<tr>
							<td style="font-weight:bold">Prepared by:<br></td>
							<td style="font-weight:bold">Noted by:<br></td>
							<td style="font-weight:bold">Received by:<br></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:center">___________________________________________________________________________</td>
							<td style="text-align:center">___________________________________________________________________________</td>
							<td style="text-align:center">___________________________________________________________________________</td>
						</tr>
						<tr>
							<td style="text-align:center">Warehouse Staff <br> <i>Signature over printed name</i></td>
							<td style="text-align:center">Warehouse Supervisor <br> <i>Signature over printed name</i></td>
							<td style="text-align:center">Accounting Staff <br> <i>Signature over printed name</i></td>
						</tr>
						<tr>
							<td style="text-align:center">Date:_________________________</td>
							<td style="text-align:center">Date:_________________________</td>
							<td style="text-align:center">Date:_________________________</td>
						</tr>
					</tbody>
				</table>';

if($report->status!='Active'){
	$data['watermark'] = "Void";
}
$data['orientation']	=	"L";
$data['pagetype']		=	"legal";
$data['title']			=	"Monthly Inventory Report";
$data['content'][0]		=	$content;
$data['content'][1]		=	"<br><br><br>".$content1;
$data['control_number']		=	"Transaction Code No." . $report->id;

$this->load->view('pdf-container.php',$data);

?>
