<?php 
require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

		$table_header = null;
		$table_data = null;
		$vessel = null;
		$supplier = null;
		$ctr = 1;

		if(isset($history_type)){

			if($history_type=="issuance"){


				if(!empty($history_main)){
					foreach($history_main as $record){

						$table_data .= "<table>
											<thead>
											<tr>	
												<th><b>#</b></th>
												<th><b>Transaction Code</b></th>
												<th><b>Issuance Date</b></th>
												<th><b>MSIS No.</b></th>
												<th><b>Company</b></th>
												<th><b>Issued To</b></th>
												<th><b>Issued for</b></th>
												<th><b>Location</b></th>
												<th><b>Remarks</b></th>
											</tr>
											</thead>";

							$table_data .= "<tbody>
											<tr>";
									$table_data .= "<td>" . $ctr . "</td>";
									$table_data .= "<td>" . $record['id'] . "</td>";
									$table_data .= "<td>" . $record['issue_date'] . "</td>";

									$vessel = $this->Abas->getVessel($record['vessel_id']);
									if(!empty($vessel->name)){
										$vessel_name = $vessel->name;
										$vessel_company= $vessel->company;
									}else{
										$vessel_name="";
									}

									$company= $this->Abas->getCompany($vessel_company);
									if(!empty($company->name)){
										$company_name = $company->name;
									}else{
										$company_name ="";
									}

									$table_data .= "<td>" . $record['control_number'] . "</td>";
									$table_data .= "<td>" . $company_name. "</td>";
									$table_data .= "<td>" . $record['issued_to'] . "</td>";

									$table_data .= "<td>" . $vessel_name. "</td>";
									$table_data .= "<td>" . $record['from_location'] . "</td>";
									$table_data .= "<td>" . $record['remark'] . "</td>";
							$table_data .= "</tr>
											</tbody>
										</table>";

						  	$table_data .= "<table>
												<thead>
													<tr>
														<th><b>Item Code</b></th>
														<th><b>Description</b></th>
														<th><b>Quantity</b></th>
														<th><b>Unit</b></th>
														<th><b>Unit Price</b></th>
														<th><b>Amount</b></th>
													</tr>
												</thead>
												<tbody>";

													$amount=0;
													$grand_total=0;

													$history_details = $this->Inventory_model->getIssuanceDetails($record['id']);
													$record = array();
													foreach($history_details as $record){
														$item= array();
														$table_data .= "<tr>";
														
														$qty =($history_type=="delivery")?$record['quantity']:$record['qty'];

														$item = $this->Inventory_model->getItems($record['item_id']);
														$amount = $record['unit_price']*$qty;

														$table_data .= "<td>" . $item[0]['item_code'] . "</td>";
														$table_data .= "<td>" . $item[0]['description'] . "</td>";
														$table_data .= "<td>" . $qty . "</td>";
														$table_data .= "<td>" . $record['unit'] . "</td>";
														$table_data .= "<td>" . number_format($record['unit_price'],2,'.',',') . "</td>";
														$table_data .= "<td>" . number_format($amount,2,'.',',') . "</td>";
														$table_data .= "</tr>";
														$grand_total = $grand_total + $amount;
													}


								$table_data .=		"<tr>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td colspan='5' align='right'><b>Total Amount:</b></td>
														<td><b>" . number_format($grand_total,2,'.',',') . "</b></td>
													</tr>
												</tbody>
											</table>
											<br><br><br>";
						$ctr++;
					}
				}
				else{
					$table_data = "<table><tr><td> No matching records found </td></tr></table>";
				}

			}
			elseif($history_type=="delivery"){

				
				if(!empty($history_main)){
					foreach($history_main as $record){


						$table_data .= "<table>
											<thead>
												 <tr>
												 	<th><b>#</b></th>
												 	<th><b>Transaction Code</b></th>
													<th><b>Date</b></th>
													<th><b>RR No.</b></th>
													<th><b>Company</b></th>
													<th><b>Sales Invoice Number</b></th>
													<th><b>PO No.</b></th>
													<th><b>Supplier</b></th>
													<th><b>Amount</b></th>
													<th><b>Location</b></th>
													<th><b>Remarks</b></th>
												</tr>
											</thead>";

							$table_data .= "<tbody>
												<tr>";
												$table_data .= "<td>" . $ctr . "</td>";
												$table_data .= "<td>" . $record['id'] . "</td>";
												$table_data .= "<td>" . $record['tdate'] . "</td>";
												$table_data .= "<td>" . $record['control_number'] . "</td>";

												$company= $this->Abas->getCompany($record['company_id']);
												if(!empty($company->name)){
													$company_name = $company->name;
												}else{
													$company_name ="";
												}

												$table_data .= "<td>" . $company_name . "</td>";
												$table_data .= "<td>" . $record['sales_invoice_no'] . "</td>";
												$table_data .= "<td>" . $record['po_no'] . "</td>";

												$supplier  = $this->Abas->getSupplier($record['supplier_id']);

												$table_data .= "<td>" . $supplier['name'] . "</td>";
												$table_data .= "<td>" . number_format($record['amount'],2,".",",") . "</td>";
												$table_data .= "<td>" . $record['location'] . "</td>";
												$table_data .= "<td>" . $record['remark'] . "</td>";
							$table_data .= "	</tr>
											</tbody>
									  	</table>";

						$table_data .= "<table>
											<thead>
												<tr>
													<th><b>Item Code</b></th>
													<th><b>Description</b></th>
													<th><b>Quantity</b></th>
													<th><b>Unit</b></th>
													<th><b>Unit Price</b></th>
													<th><b>Amount</b></th>
												</tr>
											</thead>
											<tbody>";

												$amount=0;
												$grand_total=0;

												$history_details = $this->Inventory_model->getDeliveryDetails($record['id']);

												$record = array();
												foreach($history_details as $record){
													$item = array();
													$table_data .= "<tr>";
													
													$qty =($history_type=="delivery")?$record['quantity']:$record['qty'];

													$item = $this->Inventory_model->getItems($record['item_id']);
													$amount = $record['unit_price']*$qty;

													$table_data .= "<td>" . $item[0]['item_code'] . "</td>";
													$table_data .= "<td>" . $item[0]['description'] . "</td>";
													$table_data .= "<td>" . $qty . "</td>";
													$table_data .= "<td>" . $record['unit'] . "</td>";
													$table_data .= "<td>" . number_format($record['unit_price'],2,'.',',') . "</td>";
													$table_data .= "<td>" . number_format($amount,2,'.',',') . "</td>";
													$table_data .= "</tr>";
													$grand_total = $grand_total + $amount;
												}


							$table_data .=		"<tr>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td colspan='4'><b>Total Amount:</b></td>
													<td><b>" . number_format($grand_total,2,'.',',') . "</b></td>
												</tr>
											</tbody>
										</table><br><br><br>";

						$ctr++;
					}
				}
				else{
					$table_data = "<table><tr><td> No matching records found </td></tr></table>";
				}

			}
			elseif($history_type=="transfer"){

				if(!empty($history_main)){
					foreach($history_main as $record){


							$table_data = "<table>
												<thead>
													 <tr>
													 	<th><b>#</b></th>
													 	<th><b>Transaction Code</b></th>
													 	<th><b>Issued Date</b></th>
													 	<th><b>STR No.</b></th>
													 	<th><b>Company</b></th>
														<th><b>Transferred By</b></th>
														<th><b>From Warehouse</b></th>
														<th><b>To Warehouse</b></th>
														<th><b>Status</b></th>
														<th><b>Remarks</b></th>
													</tr>
												</thead>";
								$table_data .= "<tbody>
													<tr>";
										$table_data .= "<td>" . $ctr . "</td>";
										$table_data .= "<td>" . $record['id'] . "</td>";
										$table_data .= "<td>" . $record['transfer_date'] . "</td>";
										$table_data .= "<td>" . $record['control_number'] . "</td>";

											$company= $this->Abas->getCompany($record['company_id']);

										$table_data .= "<td>" . $company->name . "</td>";
										
										$table_data .= "<td>" . $record['transfered_by'] . "</td>";
										$table_data .= "<td>" . $record['from_location'] . "</td>";
										$table_data .= "<td>" . $record['to_location'] . "</td>";
										$table_data .= "<td>" . $record['remark'] . "</td>";


										if($record['is_received']==1){
											$table_data .= "<td>Received</td>";
										}else{
											$table_data .= "<td>For Receiving</td>";
										}
										
									$table_data .= "</tr>
												</tbody>
											</table>";

						$table_data .= "<table>
											<thead>
												<tr>
													<th><b>Item Code</b></th>
													<th><b>Description</b></th>
													<th><b>Quantity</b></th>
													<th><b>Unit</b></th>
													<th><b>Unit Price</b></th>
													<th><b>Amount</b></th>
												</tr>
											</thead>
											<tbody>";

												$amount=0;
												$grand_total=0;

												$history_details = $this->Inventory_model->getTransferDetails($record['id']);

												foreach($history_details as $record){
													$table_data .= "<tr>";
													
													$qty =($history_type=="delivery")?$record['quantity']:$record['qty'];

													$item = $this->Inventory_model->getItems($record['item_id']);
													$amount = $record['unit_price']*$qty;

													$table_data .= "<td>" . $item[0]['item_code'] . "</td>";
													$table_data .= "<td>" . $item[0]['description'] . "</td>";
													$table_data .= "<td>" . $qty . "</td>";
													$table_data .= "<td>" . $record['unit'] . "</td>";
													$table_data .= "<td>" . number_format($record['unit_price'],2,'.',',') . "</td>";
													$table_data .= "<td>" . number_format($amount,2,'.',',') . "</td>";
													$table_data .= "</tr>";
													$grand_total = $grand_total + $amount;
												}


							$table_data .=	"<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td colspan='4'><b>Total Amount:</b></td>
												<td><b>" . number_format($grand_total,2,'.',',') . "</b></td>
											</tr>
											</tbody>
									</table><br><br><br>";

						$ctr++;
					}
				}
				else{
					$table_data = "<table><tr><td> No matching records found </td></tr></table>";
				}
			}
			elseif($history_type=="return"){

				if(!empty($history_main)){
					foreach($history_main as $record){


							$table_data = "<table>
												<thead>
													 <tr>
													 	<th><b>#</b></th>
													 	<th><b>Transaction Code</b></th>
													 	<th><b>Return Date</b></th>
													 	<th><b>MSRS No.</b></th>
													 	<th><b>Company</b></th>
														<th><b>Returned From</b></th>
														<th><b>Returned To Warehouse</b></th>
														<th><b>Remarks</b></th>
													</tr>
												</thead>";
								$table_data .= "<tbody>
													<tr>";
										$table_data .= "<td>" . $ctr . "</td>";
										$table_data .= "<td>" . $record['id'] . "</td>";
										$table_data .= "<td>" . $record['return_date'] . "</td>";
										$table_data .= "<td>" . $record['control_number'] . "</td>";

											$company= $this->Abas->getCompany($record['company_id']);
											$vessel= $this->Abas->getVessel($record['return_from']);

										$table_data .= "<td>" . $company->name . "</td>";
										
										$table_data .= "<td>" . $vessel->name. "</td>";
										$table_data .= "<td>" . $record['return_to'] . "</td>";
										$table_data .= "<td>" . $record['remark'] . "</td>";


									$table_data .= "</tr>
												</tbody>
											</table>";

						$table_data .= "<table>
											<thead>
												<tr>
													<th><b>Item Code</b></th>
													<th><b>Description</b></th>
													<th><b>Quantity Returned</b></th>
													<th><b>From</b></th>
													<th><b>To</b></th>
													<th><b>Unit</b></th>
													<th><b>Unit Price</b></th>
													<th><b>Amount</b></th>
												</tr>
											</thead>
											<tbody>";

												$amount=0;
												$grand_total=0;

												$history_details = $this->Inventory_model->getReturnDetails($record['id']);

												foreach($history_details as $record){
													$table_data .= "<tr>";
													
													$qty =($history_type=="delivery")?$record['quantity']:$record['qty'];

													$item = $this->Inventory_model->getItems($record['item_id']);
													$amount = $record['unit_price']*$qty;

													$table_data .= "<td>" . $item[0]['item_code'] . "</td>";
													$table_data .= "<td>" . $item[0]['description'] . "</td>";
													$table_data .= "<td>" . $qty . "</td>";
													$table_data .= "<td>" . $record['old_qty'] . "</td>";
													$table_data .= "<td>" . ($record['old_qty'] + $qty) . "</td>";
													$table_data .= "<td>" . $record['unit'] . "</td>";
													$table_data .= "<td>" . number_format($record['unit_price'],2,'.',',') . "</td>";
													$table_data .= "<td>" . number_format($amount,2,'.',',') . "</td>";
													$table_data .= "</tr>";
													$grand_total = $grand_total + $amount;
												}


							$table_data .=	"<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td colspan='8'><b>Total Amount:</b></td>
												<td><b>" . number_format($grand_total,2,'.',',') . "</b></td>
											</tr>
											</tbody>
									</table><br><br><br>";

						$ctr++;
					}
				}
				else{
					$table_data = "<table><tr><td> No matching records found </td></tr></table>";
				}
			}

		}


		$data['orientation']		=	"L";
		$data['pagetype']			=	"letter";
		$data['title']				=	ucwords($history_type . ' Transaction History');
		$data['content']			=	'
										<div class="container">
											<style>
												td {font-size:120%;}
												th, thead {text-align: right; background-color:black; color:white}
												table, th, td { border: 1px solid black; height:10px; vertical-align:bottom;}
												.th {text-align: right;}
												h1 {text-align: center}
											</style>
											<div>
												<h1>' . ucwords($history_type . ' Transaction History') . '</h1>
												<br><hr>
											</div>
											
											 	' . $table_data . '
												
										</div>';
		unset($record);
		unset($item);
		unset($history_main);								
		$this->load->view('pdf-container.php',$data);

		?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php //echo $data['content'];?>
</body>
</html>			
				   