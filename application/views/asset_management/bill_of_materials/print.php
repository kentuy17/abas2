<?php

$prepared_by			= "<u>".$BOM['created_by']."<br><br>Date:".date('j F Y',strtotime($BOM['created_on']))."</u>";
$verified_by			= "<u>".$BOM['verified_by']."<br><br>Date:".date('j F Y',strtotime($BOM['verified_on']))."</u>";
$warehouse_provided_by 	= "_______________________<br><br>Date:______________";
$purchasing_provided_by = "_______________________<br><br>Date:______________";
$approved_by 			= "<u>".$BOM['approved_by']."<br><br>Date:".date('j F Y',strtotime($BOM['approved_on']))."</u>";
$summary_prepared_by 	= "______________________________________________<br>Engineering Manager<br><br>Date:______________________";
$summary_noted_by 		= "______________________________________________<br>President<br><br>Date:______________________";

$content1 =  '<style type="text/css">
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
				<table>
					<tr>
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h1 class="text-center">'. $BOM['company_name'] .'</h1>
			    			<h3 class="text-center">'. $BOM['company_address'].'</h3>
			    			<h3 class="text-center">'. $BOM['company_contact'].'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="5">';
					if($summary==FALSE){	
						$content1 .= '<tr><td colspan="7" class="btx">BOM No.</td>';
						$content1 .= '<td colspan="1" class="btx" align="left">'.$BOM['control_number'].'</td></tr>
									 <tr>
										<td colspan="8"><h2>BILL OF MATERIALS</h2></td>
									 </tr>';
					}else{
						$content1 .= '<tr>
										<td colspan="8"><h2>BILL OF MATERIALS SUMMARY</h2></td>
									  </tr>';
					}
$content1 .= '			
				</table>
			</div>';

if($BOM['bom_type']=="Vessel"){

$content1 .= '<div>
				<table border="0" cellspacing="5">			   
			    	<tbody>
			    		<tr>
			    			<th colspan="2" style="text-align:right">Vessel Name:</th>
			    			<td colspan="6" style="text-align:left"><u>'.$BOM['vessel_name'].'</u></td>
			    			<th colspan="2" style="text-align:right">Date:</th>
			    			<td colspan="3" style="text-align:left"><u>'.date("j F Y h:m A", strtotime($BOM['created_on'])).'</u></td>
			    		</tr>
			    		<tr>
			    			<th colspan="2" style="text-align:right">Repair Date:</th>
			    			<td colspan="6" style="text-align:left"><u>'.date("j F Y",strtotime($BOM['start_date_of_repair'])).'</u></td>';

			    				if($summary==FALSE){
									$content1 .= '<th colspan="2" style="text-align:right">SRMSF No.:</th>';
			    					$content1 .='<td colspan="3" style="text-align:left"><u>'.$BOM['evaluation_form_no'].'</u></td>';
			    				}else{
			    					$content1 .= '<th colspan="2" style="text-align:right">BOM No.:</th>';
			    					$content1 .='<td colspan="3" style="text-align:left"><u>'.$BOM['control_number'].'</u></td>';
			    				}
$content1 .= '			</tr>
			    		<tr>
			    			<th colspan="2" style="text-align:right">Dry-Dock Loc:</th>
			    			<td colspan="6" style="text-align:left"><u>'.$BOM['dry_docking_location'].'</u></td>';
			    			if($summary==FALSE){
			    				$content1 .= '<th colspan="2" style="text-align:right">Work Order No.:</th>';
			    				$content1 .= '<td colspan="3" style="text-align:left"><u>'.$BOM['WO_number'].'</u></td>';
			    			}
$content1 .= '			</tr>
			    	</tbody>
				</table>
			  </div>';
}
elseif($BOM['bom_type']=="Truck"){

	$content1 .= '<div>
					<table border="0" cellspacing="5">
						<tbody>
							<tr>
								<th colspan="2" style="text-align:right">Driver\'s Name:</th>
								<td colspan="5" style="text-align:left"><u>'.$BOM['driver'].'</u></td>
								<th colspan="2" style="text-align:right">Date:</th>
								<td colspan="2" style="text-align:left"><u>'.date("j F Y h:m A", strtotime($BOM['created_on'])).'</u></td>
							</tr>
							<tr>
								<th colspan="2" style="text-align:right">Plate No.:</th>
								<td colspan="5" style="text-align:left"><u>'.$BOM['plate_number'].'</u></td>';
								if($summary==FALSE){
									$content1.='<th colspan="2" style="text-align:right">MTDE No.:</th>';
									$content1.='<td colspan="2" style="text-align:left"><u>'.$BOM['evaluation_form_no'].'</u></td>';
								}else{
									$content1.='<th colspan="2" style="text-align:right">BOM No.:</th>';
									$content1.='<td colspan="2" style="text-align:left"><u>'.$BOM['control_number'].'</u></td>';
								}
	$content1 .=	'		</tr>
							<tr>
								<th colspan="2" style="text-align:right">Model.:</th>
								<td colspan="5" style="text-align:left"><u>'.$BOM['make']. "-" . $BOM['model'] . " - " . $BOM['type'].'</u></td>';
								if($summary==FALSE){
									$content1.='<th colspan="2" style="text-align:right">TRMRF No.:</th>';
									$content1.='<td colspan="2" style="text-align:left"><u>'.$BOM['TRMRF_number'].'</u></td>';
								}
	$content1 .=	'	   </tr>
							<tr>
								<th colspan="2" style="text-align:right">Repair Date:</th>
			    				<td colspan="6" style="text-align:left"><u>'.date("j F Y",strtotime($BOM['start_date_of_repair'])).'</u></td>
							</tr>
						</tbody>
					</table>
				</div>';
}

if($summary==FALSE){
	$total_days = 0;
	$tasks_details = '';
	foreach($BOM_tasks as $task){

		$total_days = $total_days + $task->estimated_time_to_complete;

		$tasks_details .='<tr>
							<td style="width:80px">'.$task->task_number.'</td>
							<td style="width:482px;text-align:left">'.$task->scope_of_work.'</td>
							<td style="width:80px">'.$task->total_area.'</td>
							<td style="width:80px">'.$task->estimated_time_to_complete.'</td>
						 </tr>';
	}
	/*$tasks_details .=	'<tr>
						 	<td colspan="3" style="font-weight:bold;font-size:14px;text-align:right">Total ETC (No. of Days):</td>
						 	<td style="font-weight:bold;font-size:14px">'.$total_days.'</td>
						 </tr>';*/

	$total_cost = 0;
	$grand_total = 0;
	$labor_details = '';
	foreach($BOM_labor as $labor){

		$total_cost = ($labor->days_needed * $labor->rate_per_day) * $labor->quantity;
		$grand_total = $grand_total + $total_cost;

		$labor_details .='<tr>
							<td style="width:80px">'.$labor->quantity.'</td>
							<td style="width:302px;text-align:left">'.$labor->job_description.'</td>
							<td style="width:80px">'.$labor->task_numbers.'</td>
							<td style="width:100px">'.$labor->days_needed.'</td>
							<td style="width:80px">'.$labor->rate_per_day.'</td>
							<td style="width:80px">'.number_format($total_cost,2,'.',',').'</td>
						 </tr>';
	}

	$labor_details .=	'<tr>
						 	<td colspan="3" style="font-weight:bold;font-size:14px;text-align:right">Total Amount:</td>
						 	<td colspan="4" style="font-weight:bold;font-size:14px;">PHP'.number_format($grand_total,2,'.',',').'</td>
						 </tr>';

	$total_cost = 0;
	$grand_total = 0;
	$materials_details = '';
	foreach($BOM_supplies as $item){

		$quantity_for_purchase = ($item->quantity)-($item->warehouse_quantity);
						
		if($quantity_for_purchase<=0){
			$total_cost = ($item->quantity) * ($item->warehouse_unit_cost);
			$purchase_quantity = "-";
			$purchase_unit_cost = "-";
		}else{
			$calc_wh_cost = ($item->warehouse_quantity) * ($item->warehouse_unit_cost);
			$calc_ps_cost = ($item->unit_cost) * $quantity_for_purchase;
			$total_cost =  $calc_wh_cost + $calc_ps_cost;
			$purchase_quantity = $quantity_for_purchase;
			$purchase_unit_cost = $item->unit_cost;
		}

		$grand_total = $grand_total + $total_cost;

		if(isset($item->item_code)){
			if($item->item_code=="-"){
				$item_name = $item->item_description;
			}else{
				$item_name = $item->item_code . " - " . $item->item_description;
			}
			$item_size = $item->item_size;
			$item_um = $item->item_unit;
			$item_wh_qty = $item->warehouse_quantity;
			$item_wh_uc = $item->warehouse_unit_cost;
		}

		$materials_details .='<tr>
				            <td style="width:60px">'.$item->quantity.'</td>
							<td style="width:262px;text-align:left">'.$item_name.'</td>
							<td style="width:60px">'.$item_size.'</td>
							<td style="width:60px">'.$item_um.'</td>
							<td style="width:50px">'.$item_wh_qty.'</td>
							<td style="width:50px">'.$item_wh_uc.'</td>
							<td style="width:50px">'.$purchase_quantity.'</td>
							<td style="width:50px">'.$purchase_unit_cost.'</td>
							<td style="width:80px">'.number_format($total_cost,2,'.',',').'</td>
						 </tr>
						 ';
	}

	$materials_details .='<tr>
						 	<td colspan="5" style="font-weight:bold;font-size:14px;text-align:right">Total Amount:</td>
						 	<td colspan="4" style="font-weight:bold;font-size:14px;">PHP'.number_format($grand_total,2,'.',',').'</td>
						 </tr>';

	$tools_details = '';
	foreach($BOM_tools as $tool){
		$tools_details .='<tr>
							<td style="width:80px">'.$tool->quantity.'</td>
							<td style="width:542px;text-align:left">'.$tool->tool_name.'</td>
							<td style="width:100px">'.$tool->days_used.'</td>
						 </tr>';
	}

	$content1 .='<div>
					<table border="1" cellpadding="5" width="100%">
		
						<tr bgcolor="#000000" color="#FFFFFF">
							<th colspan="4" class="bt">Tasks Description</th>
						</tr>
						<thead>
			                <tr bgcolor="#c4c4c4" color="#000000">
			                  <th style="width:80px">Task No.</th>
			                  <th style="width:482px">Scope of Work</th>
			                  <th style="width:80px">Total Area</th>
			                  <th style="width:80px">ETC</th>
			                </tr>
			             </thead>
			                <tbody>
			                	'.$tasks_details.'
			                </tbody>
					</table>
				</div>

				<div>
					<table border="1" cellpadding="5" width="100%">
						<tr bgcolor="#000000" color="#FFFFFF">
							<th colspan="4" class="bt">Labor</th>
						</tr>
						<thead>
			                <tr bgcolor="#c4c4c4" color="#000000">
			                  <th style="width:80px">Qty</th>
			                  <th style="width:302px">Job Description</th>
			                  <th style="width:80px">Task No(s).</th>
			                  <th style="width:100px">Days Needed</th>
			                  <th style="width:80px">Rate/Day</th>
			                  <th style="width:80px">Total Cost</th>
			                </tr>
			            </thead>
			                <tbody>
			                	'.$labor_details.'
			                </tbody>   
					</table>
				</div>

				<div>
					<table border="1" cellpadding="5" width="100%">
						<tr bgcolor="#000000" color="#FFFFFF">
							<th colspan="4" class="bt">Materials and Supplies</th>
						</tr>
						<thead>
							<tr bgcolor="#c4c4c4" color="#000000">
								<th rowspan="2" style="width:60px">Qty Needed</th>
								<th rowspan="2" style="width:262px">Item Name & Description</th>
								<th rowspan="2" style="width:60px">Size</th>
								<th rowspan="2" style="width:60px">UM</th>
								<th colspan="2" style="width:100px">Warehouse</th>
								<th colspan="2" style="width:100px">For Purchasing</th>
								<th rowspan="2" style="width:80px">Total Cost</th>
							</tr>
			                <tr bgcolor="#c4c4c4" color="#000000">
			                  <th>Qty</th>
			                  <th>UC</th>
			                  <th>Qty</th>
			                  <th>UC</th>	                  
			                </tr>
			            </thead>
			                <tbody>
			                	'.$materials_details.'
			                </tbody>          
					</table>
				</div>

				<div>
					<table border="1" cellpadding="5" width="100%">
						<tr bgcolor="#000000" color="#FFFFFF">
							<th colspan="4" class="bt">Tools and Equipment</th>
						</tr>
						<thead>
			                <tr bgcolor="#c4c4c4" color="#000000">
			                  <th style="width:80px">Qty</th>
			                  <th style="width:542px">Particulars</th>
			                  <th style="width:100px">Est. Days Used</th>
			                </tr>
			            </thead>
			                <tbody>
			                	'.$tools_details.'
			                </tbody>   
					</table>
				</div>
				';

	$content2 = '
				<br><br>
				<style type="text/css">
					 h1 { font-size:200%;text-align:center; }
					 h2 { font-size:150%;text-align:center; }	
					 h3 { font-size:100%;text-align:center; }
					 h5 { border-bottom: double 3px; }
					 td {font-size:160%;}
					 th { font-weight:bold;font-size:150%;text-align:center;vertical-align:middle;}
					  p { text-align:left;font-size:150%; }
					.bt { font-weight:bold; text-align:left font-size:190%;}
					.btx { font-weight:bold; text-align:right; font-size:190%}
				</style>

				<div>
					<table border="1" cellpadding="5" width="100%">
						<tr bgcolor="#000000" color="#FFFFFF">
							<th class="bt">Remarks/Others/Notes</th>
						</tr>
		                <tr>
		                  <td>'.$BOM['remarks'].'</td>
		                </tr>  
					</table>
				</div>
				<div>
					<table border="0" cellpadding="8" width="100%">
						<tr>
							<td style="font-weight:bold">Prepared by:<br><br></td>
							<td style="font-weight:bold">Warehouse Data provided by:<br><br></td>
							<td style="font-weight:bold">Approved by:<br><br></td>
						</tr>
						<tr>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$BOM["created_by_signature"].'" width="100" height="100"></td>
							<td></td>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$BOM["approved_by_signature"].'" width="100" height="100"></td>
						</tr>
						<tr>
							<td>'.$prepared_by.'</td>
							<td>'.$warehouse_provided_by.'</td>
							<td>'.$approved_by.'</td>
						</tr>
						<tr>
							<td style="font-weight:bold"><br><br><br><br>Verified by:<br><br></td>
							<td style="font-weight:bold"><br><br><br><br>Purchasing Data provided by:<br><br></td>
						</tr>
						<tr>
							<td><img src="'.LINK.'assets/images/digitalsignatures/'.$BOM["verified_by_signature"].'" width="100" height="100"></td>
							<td></td>
						</tr>
						<tr>
							<td>'.$verified_by.'</td>
							<td>'.$purchasing_provided_by.'</td>
						</tr>

					</table>
				</div>';



	$data['orientation']		=	"P";
	$data['pagetype']			=	"letter";
	$data['title']				=	"Bill Of Materials No." . $BOM['control_number'];
	$data['content'][0]			=	$content1;
	$data['content'][1]			=	$content2;
	$data['control_number']		=	"Transaction Code No." . $BOM['id'];

}
elseif($summary==TRUE){

$total_days = 0;
$tasks_details = '';
foreach($BOM_tasks as $task){

	$total_days = $total_days + $task->estimated_time_to_complete;

	$tasks_details .='<tr>
						<td style="width:747px">'.$task->scope_of_work.'</td>
						<td style="width:100px">'.$task->total_area.'</td>
						<td style="width:100px">'.$task->estimated_time_to_complete.'</td>
					 </tr>';
}
	$tasks_details .='<tr>
					 	<td colspan="2" style="font-weight:bold;font-size:14px;text-align:right">Total ETC (No. of Days):</td>
					 	<td style="font-weight:bold;font-size:14px">'.$total_days.'</td>
					 </tr>';

$grand_total_labor = 0;
$expense_details = '';
foreach($BOM_labor as $labor){

	$total_cost = ($labor->days_needed * $labor->rate_per_day) * $labor->quantity;
	$grand_total_labor = $grand_total_labor + $total_cost;
	
}
	

$grand_total_wh_materials = 0;
$grand_total_pr_materials = 0;
$expense_details = '';
foreach($BOM_supplies as $item){

		$quantity_for_purchase = ($item->quantity)-($item->warehouse_quantity);
						
		if($quantity_for_purchase<=0){
			$total_cost = ($item->quantity) * ($item->warehouse_unit_cost);
			$grand_total_wh_materials = $grand_total_wh_materials + $total_cost;
		}else{
			$calc_wh_cost = ($item->warehouse_quantity) * ($item->warehouse_unit_cost);
			$calc_ps_cost = ($item->unit_cost) * $quantity_for_purchase;
			$total_cost =  $calc_wh_cost + $calc_ps_cost;
			$grand_total_pr_materials = $grand_total_pr_materials + $total_cost;
		}
		
	
}
	
if($grand_total_labor > 0){
	$expense_details .='<tr>
						  <td style="width:747px">Labor</td>
						  <td style="width:200px">'.number_format($grand_total_labor,2,'.',',').'</td>
					    </tr>';
}

if($grand_total_wh_materials > 0){
	$expense_details .='<tr>
						  <td style="width:747px">Materials and Supplies (Warehouse)</td>
						  <td style="width:200px">'.number_format($grand_total_wh_materials,2,'.',',').'</td>
					    </tr>';
}

if($grand_total_pr_materials > 0){
	$expense_details .='<tr>
						  <td style="width:747px">Materials and Supplies (For Purchasing)</td>
						  <td style="width:200px">'.number_format($grand_total_pr_materials,2,'.',',').'</td>
					    </tr>';				    
}

	$expense_details .='<tr>
					 	<td style="font-weight:bold;font-size:14px;text-align:right">Grand Total Amount:</td>
					 	<td style="font-weight:bold;font-size:14px">PHP'.number_format($grand_total_labor+$grand_total_wh_materials+$grand_total_pr_materials,2,'.',',').'</td>
					 </tr>';

$content1 .= '<div>
					<table border="1" cellpadding="5" width="100%">
		
						<tr bgcolor="#000000" color="#FFFFFF">
							<th colspan="4" class="bt">Scope of Work</th>
						</tr>
						<thead>
			                <tr bgcolor="#c4c4c4" color="#000000">
			                  <th style="width:747px">Work Description</th>
			                  <th style="width:100px">Total Area</th>
			                  <th style="width:100px">ETC</th>
			                </tr>
			             </thead>
			                <tbody>
			                	'.$tasks_details.'
			                </tbody>
					</table>
			   </div>
			   <div>
					<table border="1" cellpadding="5" width="100%">
		
						<tr bgcolor="#000000" color="#FFFFFF">
							<th colspan="4" class="bt">Expense Estimate</th>
						</tr>
						<thead>
			                <tr bgcolor="#c4c4c4" color="#000000">
			                  <th style="width:747px">Particulars</th>
			                  <th style="width:200px">Total Amount</th>
			                </tr>
			             </thead>
			                <tbody>
			                	'.$expense_details.'
			                </tbody>
					</table>
			   </div>';

$content1 .=   '<div>
					<table border="0" cellpadding="5" width="100%">
						<thead>
							<tr>
								<td style="font-weight:bold;text-align:left">Prepared by:</td>
								<td style="font-weight:bold;text-align:left">Noted by:</td>
							</tr>
							<tr>
								<td><img src="'.LINK.'assets/images/digitalsignatures/'.$BOM["created_by_signature"].'" width="100" height="100"></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td style="text-align:center">'.$summary_prepared_by.'</td>
								<td style="text-align:center">'.$summary_noted_by.'</td>
							</tr>
						</thead>
					</table>
				</div>';


$data['orientation']		=	"L";
$data['pagetype']			=	"letter";
$data['title']				=	"Summary for Bill Of Materials No." . $BOM['control_number'];
$data['content']			=	$content1;

}

if($BOM['status']=="Draft"){
	$data['watermark']	=	"DRAFT";
}

$this->load->view('pdf-container.php',$data);
?>