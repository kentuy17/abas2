<?php

$content =  '<style type="text/css">
				 h1 { font-size:200%;text-align:center; }
				 h2 { font-size:150%;text-align:center; }	
				 h3 { font-size:100%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:160%;text-align:center;}
				 th { font-weight:bold;font-size:150%;text-align:center;vertical-align:middle;}
				  p { text-align:left;font-size:150%; }
				.bt { font-weight:bold; text-align:left font-size:190%;}
				.btx { font-weight:bold; text-align:right; font-size:190%}
			</style>
			<br>
		    <div>
				<table border="0">
					<tr>
						<td><img src="'. LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h2 class="text-left" style="text-align:left">'. $request['company']->name .'</h2>
			    			<h3 class="text-left" style="text-align:left">'. $request['company']->address.'</h3>
			    			<h3 class="text-left" style="text-align:left">'. $request['company']->telephone_no.'</h3>
						</td>
					</tr>
					<tr>
						<td colspan="5"><br><h1>Materials/Services Procurement Requisition Form</h1><br></td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="text-align:right"><b>Vessel/Department:</b></td>
						<td style="text-align:left"><u> '.$request['vessel_name'].'</u></td>
						<td style="text-align:right"><b>Date:</b></td>
						<td style="text-align:left"><u> '.date('m-d-Y',strtotime($request['added_on'])).'</u></td>
					</tr>
					<tr>
						<td style="text-align:right"><b>Priority Level:</b></td>
						<td style="text-align:left"><u> '.$request['priority'].'</u></td>
						<td style="text-align:right"><b>Control No.:</b></td>';
						if($request['reference_number']!=''){
							$content .= '<td style="text-align:left"><u> '.$request['control_number']." (".$request['reference_number'].')</u></td>';
						}else{
							$content .= '<td style="text-align:left"><u> '.$request['control_number'].'</u></td>';
						}

		$content .= '</tr>
					<tr>
						<td style="text-align:right"><b>Requisitioner:</b></td>
						<td style="text-align:left"><u> '.$request['requisitioner'].'</u></td>';
						
						if($request['truck_id']!=0){
							$truck = $this->Abas->getTruck($request['truck_id']);
							$content .= '<td style="text-align:right"><b>Truck Plate No.:</b></td> 
							<td style="text-align:left"><u> '.$truck[0]['plate_number'].'</u></td>';
						}		
						
		$content .=	'</tr>
				</table>
			</div>';

$content .= '<div><table border="1">
				<thead>
					<tr bgcolor="#000000" color="#FFFFFF">
	      				<th style="width:100px">Item Code</th>
	      				<th style="width:100px">Quantity</th>	
	      				<th style="width:120px">Unit</th>
	      				<th style="width:250px">Description</th>
	      				<th style="width:150px">Remark/Size</th>
	      			</tr>
      			</thead>
              	<tbody>';

              	$ctr=1;
				if($request['details']){
					foreach($request['details'] as $detail){
						$item = $this->Abas->getItemCategory($detail['item_details']['category']);
						if($item->category!='Service' && $detail['supplier_id']==0 && $detail['quantity']>0){
							$content .= "<tr>";
								$content .= "<td style=\"width:100px\">".$detail['item_details']['item_code']."</td>";
								$content .= "<td style=\"width:100px\">".$detail['quantity']."</td>";
								if($detail['packaging']==''){
									$content .= "<td style=\"width:120px\">".$detail['item_details']['unit']."</td>";
								}else{
									$content .= "<td style=\"width:120px\">".$detail['packaging']."</td>";
								}
								$content .= "<td style=\"width:250px\">".$detail['item_details']['item_name'].",".$detail['item_details']['brand']." ".$detail['item_details']['particular']."</td>";
								$content .= "<td style=\"width:150px\">".$detail['remark']."</td>";
							$content .= "</tr>";
							$ctr++;
						}
					}
					if($ctr==1){
						$content .= "<tr>";
							$content .= "<td colspan=\"5\">Not Applicable</td>";
						$content .= "</tr>";
					}
				}

$content .= '	</tbody>
	        </table></div>';


$content .= '<div><table border="1">
				<thead>
					<tr bgcolor="#000000" color="#FFFFFF">
						<th style="width:100px">Quantity</th>
	      				<th style="width:322px">Job Description</th>
	      				<th style="width:300px">Remark</th>
	      			</tr>
      			</thead>
              	<tbody>';
              	$ctr=1;
				if($request['details']){
					foreach($request['details'] as $detail){
						$item = $this->Abas->getItemCategory($detail['item_details']['category']);
						if($item->category=='Service' && $detail['supplier_id']==0){
							$content .= "<tr>";
								$content .= "<td style=\"width:100px\">".$detail['quantity']." ".$detail['item_details']['unit']."</td>";
								$content .= "<td style=\"width:320px\">".$detail['item_details']['item_name'].",".$detail['item_details']['brand']." ".$detail['item_details']['particular']."</td>";
								$content .= "<td style=\"width:300px\">".$detail['remark']."</td>";
							$content .= "</tr>";
							$ctr++;
						}
					}
					if($ctr==1){
						$content .= "<tr>";
							$content .= "<td colspan=\"3\">Not Applicable</td>";
						$content .= "</tr>";
					}
				}

$content .= '	</tbody>
	        </table></div>';


$content .= '<div>
				<table border="0">
					<tr><td style="text-align:left"><b>Purpose:</b></td></tr>
					<tr><td style="text-align:left"><u>'.$request['remark'].'</u></td></tr>
				</table>
			</div>';

				

$content .= '<div>
				<table border="0">
					<tr>
						<td style="text-align:left"><b>Prepared By:</b><br><br></td>
						<td style="text-align:left"><b>Verified By:</b><br><br></td>
						<td style="text-align:left"><b>Approved By:</b><br><br></td>
					</tr>
					<tr>
						<td style="text-align:left"><img src="'.LINK.'assets/images/digitalsignatures/'.$request['requested_by_signature'].'" /></td>
						<td style="text-align:left"></td>
						<td style="text-align:left"><img src="'.LINK.'assets/images/digitalsignatures/'.$request['details'][0]['request_approved_by']['signature'].'" /></td>
					</tr>
					<tr>
						<td style="text-align:left"><u>'.$request['requested_by_name'].'</u></td>
						<td style="text-align:left">____________________________</td>
						<td style="text-align:left"><u>'.$request['details'][0]['request_approved_by']['full_name'].'</u></td>
					</tr>
					<tr>
						<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($request["details"][0]['added_on'])).'</u></td>
						<td style="text-align:left">Date:____________________</td>
						<td style="text-align:left">Date:<u>'.date('j F Y',strtotime($request["details"][0]['request_approved_on'])).'</u></td>
					</tr>';

		
/*$content .=		'<tr>
						<td style="text-align:left"><b>Requisitioner</b><br><br><br></td>
						<td style="text-align:left"><b>Dept. Supervisor</b><br><br><br></td>
						<td style="text-align:left"><b>Dept. Manager</b><br><br><br></td>
					</tr>';*/

$content .=		'</table>
			</div>';



$data['orientation']		=	"P";
$data['pagetype']			=	"letter";
$data['title']				=	"Materials/Services Procurement Requisition Form No." . $request['control_number'];
$data['control_number']		=	"Transaction Code No." .$request['id'];
$data['content']			=	$content;


$this->load->view('pdf-container.php',$data);
?>

