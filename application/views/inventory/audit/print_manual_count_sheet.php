<?php

$content = "<style type='text/css'>
				 h1 { font-size:200%;text-align:left; }
				 h2 { font-size:150%;text-align:left; }	
				 h3 { font-size:120%;text-align:left; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:160%;text-align:center}
				 th { font-weight:bold;font-size:150%;text-align:center;vertical-align:middle;}
				  p { text-align:left;font-size:150%; }
				.bt { font-weight:bold; text-align:center font-size:210%;}
				.btx { vertical-align:center; text-align:center; font-size:130%;height:20px}
			</style>
			
			<h1>Manual Inventory Count Sheet </h1><br><h2>Company: ".$company->name."</h2><h3><br>For items with category <i>".$category_name->category."</i> at ".$audit->location."</h3>
			<table>
				<tr>
					<td class=\"bt\">Auditor's Name:_________________________________________________</td>
					<td class=\"bt\" style=\"text-align:right\">Date(s) of Count:_______________________________</td>
				</tr>
			</table>
			<br><br>
			<table border=\"1\" cell-spacing=\"10\">
				<thead>
					<tr>
						<td class=\"bt\" style=\"width:80px\">Item Code</td>
						<td class=\"bt\" style=\"width:220px\">Item Description</td>
						<td class=\"bt\" style=\"width:50px\">Unit</td>
						<td class=\"bt\" style=\"width:80px\">Unit Price</td>
						<td class=\"bt\" style=\"width:80px\">Qty per Books</td>
						<td class=\"bt\" style=\"width:80px\">Qty per Count</td>
						<td class=\"bt\" style=\"width:100px\">Storage Location</td>
						<td class=\"bt\" style=\"width:260px\">Remarks</td>
					</tr>
				</thead>
				<tbody>";

	foreach($items as $row){

		$stock_location = $row->stock_location;
		$item_qty = $row->quantity - $row->quantity_issued;

		$content .= "<tr>";
				$content .= "<td class=\"btx\" style=\"width:80px\">".$row->item_code."</td>";
				$content .= "<td class=\"btx\" style=\"width:220px\">".$row->description.", ".$row->brand." ".$row->particular."</td>";
				$content .= "<td class=\"btx\" style=\"width:50px\">".$row->unit."</td>";
				$content .= "<td class=\"btx\" style=\"width:80px\">".number_format($row->unit_price,2,'.',',')."</td>";
				$content .= "<td class=\"btx\" style=\"width:80px\"> ".$item_qty."</td>";
				$content .= "<td class=\"btx\" style=\"width:80px\"></td>";
				$content .= "<td class=\"btx\"  style=\"width:100px\">".$stock_location."</td>";
				$content .= "<td class=\"btx\" style=\"width:260px\"></td>";	
			$content .= "</tr>";
		
	}

$content .=	"</tbody>
			</table>";

$data['orientation']		=	"L";
$data['pagetype']			=	"letter";
$data['title']				=	"Manual Count Sheet";
$data['content']			=	$content;
$data['control_number']		=	"Transaction Code No." . $audit->id;

$this->load->view('pdf-container.php',$data);

?>