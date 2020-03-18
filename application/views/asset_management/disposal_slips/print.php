<?php	
$created_by	=	"<u>".$disposal->created_by."<br></u>Date: <u>".date('j F Y',strtotime($disposal->created_on))."</u>";
$checked_by	=	"<u>".$disposal->checked_by."<br></u>Date: <u>".date('j F Y',strtotime($disposal->checked_on))."</u>";
$verified_by	=	"<u>".$disposal->verified_by."<br></u>Date: <u>".date('j F Y',strtotime($disposal->verified_on))."</u>";
$approved_by	=	"<u>".$disposal->approved_by."<br></u>Date: <u>".date('j F Y',strtotime($disposal->approved_on))."</u>";

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
				<table border="0">
					<tr>
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td >
							<h1 class="text-center">'. $disposal->company_name .'</h1>
			    			<h4 class="text-center">'. $disposal->company_address.'</h4>
			    			<h4 class="text-center">'. $disposal->company_contact.'</h4>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table>
					<tr>
						<td colspan="13"><h2>ASSET DISPOSAL SLIP<br></h2></td>
					</tr>
				</table>
				<table border="1" cellpadding="2">
					<tr>
						<th colspan="2">Requested By:</th>
						<td colspan="6"> '.$disposal->requested_by.'</td>
						<th colspan="1">Date:</th>
						<td colspan="4"> '.date("F d, Y", strtotime($disposal->requested_on)).'</td>
					</tr>
					<tr>
						<th colspan="2">Department:</th>
						<td colspan="6"> '.$disposal->department.'</td>
						<th colspan="1">Control No:</th>
						<td colspan="4"> '.$disposal->control_number.'</td>
					</tr>
				</table>
			</div>';
			$sale = "[  ] Sale";
			$scrap = "[  ] Scrapped";
			$donate = "[  ] Donation";
			$trade = "[  ] Trade-in";
			$others = "[  ] Others, specify:";
			switch ($disposal->manner_of_disposal) {
				case 'Sale':
					$sale = "[x] Sale";
					break;
				case 'Scrapped':
					$scrap = "[x] Scrapped";
					break;
				case 'Donation':
					$donate = "[x] Donation";
					break;
				case 'Trade-in':
					$trade = "[x] Trade-in";
					break;
				case 'Others':
					$others = "[x] Others, specify: <u>".$disposal->others."</u>";
					break;
			}
		
$content .= '<div>
				<table border="1" cellpadding="2">
					<tr>
						<th colspan="5">Manner of Disposal</th>
					</tr>
					<tr>
						<td>'.$sale.'</td>
						<td>'.$scrap.'</td>
						<td>'.$donate.'</td>
						<td>'.$trade.'</td>
						<td>'.$others.'</td>
					</tr>
				</table>
			</div>
			
			<div>
				<table border="1" cellpadding="5">
					<thead>
	                  <tr>
	                      <th colspan="4">Description</th>
		                  <th colspan="3">Fixed Asset Code</th>
		                  <th colspan="2">Date Purchased</th>
		                  <th colspan="2">Original Cost</th>
		                  <th colspan="2">Net Book Value</th>
		                  <th colspan="2">Expected or Actual Proceeds</th>
		                  <th colspan="2">Gain or Loss?</th>
		                  <th colspan="5">Reason for Disposal</th>
	                  </tr>
	                </thead>
	                <tbody>
	         ';  

			foreach($disposal_details as $asset){
                	$content .=  "<tr>";
                	$content .=  "<td colspan=\"4\">".$asset->item_name." - ".$asset->item_particular."</td>";
                	$content .=  "<td colspan=\"3\">".$asset->asset_code."</td>";
                  	$content .=  "<td colspan=\"2\">".date('F d, Y',strtotime($asset->date_purchased))."</td>";
                  	$content .=  "<td colspan=\"2\">".number_format($asset->original_cost,2,'.',',')."</td>";
                  	$content .=  "<td colspan=\"2\">".number_format($asset->net_book_value,2,'.',',')."</td>";
                  	$content .=  "<td colspan=\"2\">".number_format($asset->proceeds,2,'.',',')."</td>";
                  	if($asset->is_gain==0){
                  		$content .=  "<td colspan=\"2\">Loss</td>";
                  	}else{
                  		$content .=  "<td colspan=\"2\">Gain</td>";
                  	}
                    $content .=  "<td colspan=\"5\">".$asset->reason_for_disposal."</td>";
                  	$content .=  "</tr>";
             }

$content .=  '  </tbody>
				</table>
			</div>';



$content .=	'<br>
			<div>
				<table border="0" cellpadding="8">
					<tr>
						<td style="font-weight:bold">Initiated by:</td>
						<td style="font-weight:bold">Checked by:</td>
						<td style="font-weight:bold">Verified by:</td>
						<td style="font-weight:bold">Approved by:</td>
					</tr>
					<tr>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$disposal->created_by_signature.'" width="100" height="100"/></td>
						<td><br><br><br>_______________________</td>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$disposal->verified_by_signature.'" width="100" height="100"/></td>
						<td><img src="'.LINK.'assets/images/digitalsignatures/'.$disposal->approved_by_signature.'" width="100" height="100"/></td>
						<td></td>
					</tr>
					<tr>
						<td>'.$created_by.'</td>
						<td>'.$checked_by.'</td>
						<td>'.$verified_by.'</td>
						<td>'.$approved_by.'</td>
					</tr>
				</table>
			</div>';

$data['orientation']		=	"L";
$data['pagetype']			=	"legal";
$data['title']				=	"Asset Disposal Slip - Control No." . $disposal->control_number;
$data['content'][0]			=	$content."<br><i>( Admin Department Copy )</i>";
$data['content'][1]			=	$content."<br><i>( Finance Department Copy )</i>";
$data['control_number']		=	"Transaction Code No." . $disposal->id;


$this->load->view('pdf-container.php',$data);
?>
