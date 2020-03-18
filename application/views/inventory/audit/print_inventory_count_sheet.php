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
							<h1 class="text-left">'. $audit->company_name .'</h1>
			    			<h3 class="text-left">'. $audit->company_address.'</h3>
			    			<h3 class="text-left">'. $audit->company_contact.'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="5">
					<tr>
						<td colspan="9" style="text-align:right"><b>Control No.</b></td>
						<td colspan="1"  align="left"><b>'.$audit->control_number.'</b></td>
					</tr>
					<tr>
						<td colspan="10"><h2>INVENTORY COUNT SHEET<br></h2></td>
					</tr>				
					<tr>
						<td colspan="2" style="text-align:right"><b>Location:</b></td>
						<td colspan="4" style="text-align:left"><u>'.$audit->location.'</u></td>
						<td colspan="2" style="text-align:right"><b>Date:</b></td>
						<td colspan="3" style="text-align:left"><u>'.date("Y-m-d", strtotime($audit->audit_date)).'</u></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:right"><b>Type of Inventory:</b></td>
						<td colspan="4" style="text-align:left"><u>'.$category_name->category.'</u></td>
						<td colspan="2" style="text-align:right"><b>Audited By:</b></td>
						<td colspan="4" style="text-align:left"><u>'.$audit->audited_by.'</u></td>
					</tr>
					
				</table>
			</div>';  

$content .= '<table border="1" cellpadding="5" width="100%">
				<thead>
					<tr>
						<td class="bt" style="width:80px" rowspan="2">Item Code</td>
						<td class="bt" style="width:190px" rowspan="2">Item Description</td>
						<td class="bt" style="width:80px" rowspan="2">Shelf No.</td>
						<td class="bt" style="width:100px" rowspan="2">Unit of Measurement</td>
						<td class="bt" style="width:280px" colspan="4">Quantity</td>
						<td class="bt" style="width:80px" rowspan="2">Unit Cost</td>
						<td class="bt" style="width:80px" rowspan="2">Total Cost</td>
						<td class="bt" style="width:240px" rowspan="2">Remarks</td>
					</tr>
					<tr>
						<td class="bt" style="width:70px" rowspan="1">Per Count</td>
						<td class="bt" style="width:70px" rowspan="1">Per Books</td>
						<td class="bt" style="width:70px" rowspan="1">Difference</td>
						<td class="bt" style="width:70px" rowspan="1">Final</td>
					</tr>
				</thead>
				<tbody>';

	$total_cost =0;

	foreach($audit_details as $row){

		$content .= '<tr>';
			$content .= '<td style="width:80px">'.$row['item_code'].'</td>';
			$content .= '<td style="width:190px">'.$row['item_description'].'</td>';
			$content .= '<td style="width:80px">'.$row['shelf_number'].'</td>';
			$content .= '<td style="width:100px">'.$row['unit'].'</td>';
			$content .= '<td style="width:70px">'.$row['counted_qty'].'</td>';
			$content .= '<td style="width:70px">'.$row['current_qty'].'</td>';
			$content .= '<td style="width:70px">'.($row['counted_qty'] - $row['current_qty']).'</td>';
			$content .= '<td style="width:70px">'.$row['counted_qty'].'</td>';
			$content .= '<td style="width:80px;text-align:right">'.number_format($row['unit_price'],2,".",",").'</td>';
			$unit_cost = $row['unit_price']*$row['counted_qty'];
			$content .= '<td style="width:80px;text-align:right">'.number_format($unit_cost,2,".",",").'</td>';
			$content .= '<td style="width:240px">'.$row['remarks'].'</td>';
		$content .= "</tr>";
		$total_cost = $total_cost + $unit_cost;
	}

	$content .= '<tr>
					<td colspan="9" style="text-align:right"><b>Total</b></td>
					<td style="text-align:right"><b>PHP '.number_format($total_cost,2,".",",").'</b></td>
				</tr></tbody>
			</table>';

$content .=	'<br><br><br><table border="1" cellpadding="5" width="100%">
				<tr>
					<td colspan="4"><b>Cut-off Documents</b></td>
				</tr>
				<tr>
					<td><b>Document Name</b></td>
					<td><b>Last Used (Control No.)</b></td>
					<td><b>Date Last Used</b></td>
					<td><b>First Unused (Control No.)</b></td>
				</tr>';

foreach($audit_cutoff_documents as $row){
	$content .= '<tr>';
		$content .= '<td>'.$row->document_name.'</td>';
		$content .= '<td>'.$row->last_used.'</td>';
		$content .= '<td>'.$row->date_last_used.'</td>';
		$content .= '<td>'.$row->first_unused.'</td>';
	$content .= "</tr>";	
}

$content .= '</table>';

$content .= '<br><br><br><table border="0" cellpadding="8" width="100%">
					<thead>
						<tr>
							<td style="font-weight:bold">Prepared by:<br></td>
							<td style="font-weight:bold">Verified by:<br></td>
							<td style="font-weight:bold">Noted by:<br></td>
							<td style="font-weight:bold">Recorded & Posted by:<br></td>
							<td style="font-weight:bold">Conforme:<br></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$audit->created_by_signature.'" width="100" height="100"></td>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$audit->verified_by_signature.'" width="100" height="100"></td>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$audit->noted_by_signature.'" width="100" height="100"></td>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$audit->approved_by_signature.'" width="100" height="100"></td>
							<td></td>
						</tr>
						<tr>
							<td><u>'.$audit->created_by.'</u></td>
							<td><u>'.$audit->verified_by.'</u></td>
							<td><u>'.$audit->noted_by.'</u></td>
							<td><u>'.$audit->approved_by.'</u></td>
							<td><u>______________________________</u></td>
						</tr>
						<tr>
							<td>Date:<u> '.date('Y-m-d',strtotime($audit->created_on)).'</u></td>
							<td>Date:<u>  '.date('Y-m-d',strtotime($audit->verified_on)).'</u></td>
							<td>Date:<u>  '.date('Y-m-d',strtotime($audit->noted_on)).'</u></td>
							<td>Date:<u>  '.date('Y-m-d',strtotime($audit->approved_on)).'</u></td>
							<td>Date:_________________________</td>
						</tr>
					</tbody>
				</table>';

$data['orientation']	=	"L";
$data['pagetype']		=	"legal";
$data['title']			=	"Inventory Count Sheet - Control No." . $audit->control_number;
$data['content'][0]		=	$content;
$data['content'][1]		=	$content;
$data['content'][2]		=	$content;
$data['control_number']		=	"Transaction Code No." . $audit->id;

$this->load->view('pdf-container.php',$data);

?>
