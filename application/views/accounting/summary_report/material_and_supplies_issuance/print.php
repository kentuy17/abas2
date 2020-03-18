<?php 
require_once WPATH.'assets'.DS.'tcpdf'.DS.'tcpdf.php';

		$company_id = '';

		$table_header = null;
		$table_data = null;
		$vessel = null;
		$title = null;
		$grandtotal = 0;
		$ctr = 1;

		$table_data = "<style type='text/css'>
						 h1 { font-size:200%;text-align:center; }
						 h2 { font-size:150%;text-align:center; }	
						 h3 { font-size:100%;text-align:center; }
						 h5 { border-bottom: double 3px; }
						 td {font-size:130%;}
						 th { font-weight:bold;font-size:150%;text-align:center}
					</style>";

		if(isset($type)){

			if($type=="MSIS"){

				$title = "Materials and Supplies Issuances Summary Report";

				$table_data .= "<br><br><br><table border=\"1\" cellpadding=\"3\">
								<thead>
								<tr bgcolor=\"#c4c4c4\" color=\"#000000\">	
									<th style=\"width:30px\"><b>#</b></th>
									<th style=\"width:80px\"><b>Issuance Date</b></th>
									<th style=\"width:120px\"><b>Company</b></th>									
									<th style=\"width:40px\"><b>MSIS No.</b></th>
									<th style=\"width:110px\"><b>Issued To</b></th>
									<th style=\"width:110px\"><b>Issued For</b></th>
									<th style=\"width:60px\"><b>Location</b></th>
									<th style=\"width:180px\"><b>Item</b></th>
									<th style=\"width:40px\"><b>Qty</b></th>
									<th style=\"width:60px\"><b>Unit</b></th>
									<th style=\"width:60px\"><b>Unit Price</b></th>
									<th style=\"width:60px\"><b>Amount (PHP)</b></th>
								</tr>
								</thead>";


				if(!empty($summary)){
					foreach($summary as $record){

								$vessel_name="";
								$company_name="";
								$item_name = "";

								$vessel = $this->Abas->getVessel($record['vessel_id']);
								if(!empty($vessel->name)){
									$vessel_name = $vessel->name;
								}else{
									$vessel_name = "-";
								}


								if(!empty($vessel->name)){
									$company = $this->Abas->getCompany($vessel->company);
									if(!empty($company->name)){
										$company_name = $company->name;
									}else{
										$company_name = "-";
									}
								}
								else{
									$company_name = "-";
								}

								
								$item = $this->Inventory_model->getItem($record['item_id']);
								if(!empty($item[0]['description'])){
									if($item[0]['item_code']!=0){
										$item_name = $item[0]['item_code'] . "-" . $item[0]['description'];
									}
									else{
										$item_name = $item[0]['description'];
									}
								}else{
									$item_name="-";
								}

								$amount = ($record['unit_price'] * $record['qty']);

								$table_data .= "<tbody><tr>";
								$table_data .= "<td style=\"width:30px\">" . $ctr . "</td>";
								$table_data .= "<td style=\"width:80px\">" . date("j F Y", strtotime($record['issue_date'])) . "</td>";
								$table_data .= "<td style=\"width:120px\">" . $company_name . "</td>";
								$table_data .= "<td style=\"width:40px\">" . $record['control_number'] . "</td>";
								$table_data .= "<td style=\"width:110px\">" . $record['issued_to'] . "</td>";
								$table_data .= "<td style=\"width:110px\">" . $vessel_name . "</td>";
								$table_data .= "<td style=\"width:60px\">" . $record['from_location'] . "</td>";
								$table_data .= "<td style=\"width:180px\">" . $item_name . "</td>";
								$table_data .= "<td style=\"width:40px\">" . $record['qty'] . "</td>";
								$table_data .= "<td style=\"width:60px\">" . $record['unit'] . "</td>";
								$table_data .= "<td style=\"width:60px\">" . number_format($record['unit_price'],2,'.',',') . "</td>";
								$table_data .= "<td style=\"width:60px\">" . number_format($amount,2,'.',',')  . "</td>";
								$table_data .= "</tr>";

								$grandtotal = $grandtotal + $amount;

						$ctr++;
					}	
								$table_data .= "<tr>
								                  <td colspan=\"10\" align=\"right\"><h4>Total Amount:</h4></td>
								                  <td colspan=\"2\" align=\"center\"><h4>PHP".number_format($grandtotal,2,'.',',')."</h4></td>
								                </tr>";
								$table_data .= "</tbody></table>";

				}
				else{
					$table_data = "<table><tr><td colspan=\"12\"> No matching records found </td></tr></table>";
				}

			}
			elseif($type="MSIS_consolidated"){

				$title = "Material and Supplies Issuances Summary Report";

				
				if(!empty($summary)){

					$grandtotal = 0;

					$vessels = $this->Inventory_model->getUniqueValuesInArray($summary,'vessel_id');

					while(list($var,$vessel_id) = each($vessels)){
						
						$ctr=1;
						$subtotal = 0;

						$vessel = $this->Abas->getVessel($vessel_id);
						if(!empty($vessel->name)){
							$vessel_name = $vessel->name;
						}else{
							$vessel_name = "-";
						}

						$table_data .= "<br><br><table border=\"1\" cellpadding=\"3\">
										<thead>
										<tr bgcolor=\"#000000\" color=\"#FFFFFF\">
											<td colspan=\"11\"><font size=\'14\'><b>". $vessel_name ."</b></font></td>
										</tr>
										<tr bgcolor=\"#c4c4c4\" color=\"#000000\">	
											<th style=\"width:30px\"><b>#</b></th>
											<th style=\"width:110px\"><b>Issuance Date</b></th>									
											<th style=\"width:40px\"><b>MSIS No.</b></th>
											<th style=\"width:110px\"><b>Issued To</b></th>
											<th style=\"width:110px\"><b>Issued For</b></th>
											<th style=\"width:90px\"><b>Item Code</b></th>
											<th style=\"width:207px\"><b>Description</b></th>
											<th style=\"width:40px\"><b>Qty</b></th>
											<th style=\"width:70px\"><b>Unit</b></th>
											<th style=\"width:70px\"><b>Unit Price</b></th>
											<th style=\"width:70px\"><b>Line Total</b></th>
										</tr>
										</thead>
										<tbody>";

						foreach($summary as $record){

							if($vessel_id==$record['vessel_id']){

								//$this->Mmm->debug($per_vessel->name);

								$company_id = $record['company_id'];

								$vessel_name="";
								$item_code = "";
								$item_desc = "";

								$vessel = $this->Abas->getVessel($record['vessel_id']);
								if(!empty($vessel->name)){
									$vessel_name = $vessel->name;
								}else{
									$vessel_name = "-";
								}

								$item = $this->Inventory_model->getItem($record['item_id']);
								if(!empty($item[0]['description'])){
									
									$item_desc = $item[0]['description'];
									$item_code = $item[0]['item_code'];
									
								}else{
									$item_desc = "-";
									$item_code = "-";
								}

								$amount = ($record['unit_price'] * $record['qty']);
								
								$table_data .= "<tr><td style=\"width:30px\">".$ctr."</td>";
								$table_data .= "<td style=\"width:110px\">".date("j F Y", strtotime($record['issue_date']))."</td>";
								$table_data .= "<td style=\"width:40px\">".$record['control_number']."</td>";
								$table_data .= "<td style=\"width:110px\">".$record['issued_to']."</td>";
								$table_data .= "<td style=\"width:110px\">".$vessel_name."</td>";
								$table_data .= "<td style=\"width:90px\">". $item_code ."</td>";
								$table_data .= "<td style=\"width:207px\">". $item_desc ."</td>";
								$table_data .= "<td style=\"width:40px\">". $record['qty'] ."</td>";
								$table_data .= "<td style=\"width:70px\">". $record['unit']."</td>";
								$table_data .= "<td style=\"width:70px\">". number_format($record['unit_price'],2,'.',',')."</td>";
								$table_data .= "<td style=\"width:70px\">". number_format($amount,2,'.',',') ."</td>";
								$table_data .= "</tr>";

								$subtotal = $subtotal + $amount;

								$ctr++;
							}

							
						}

							$grandtotal = $grandtotal + $subtotal;

							$table_data .= "<tr>
							                  <td colspan=\"9\" align=\"right\"><h4>Sub-total:</h4></td>
							                  <td colspan=\"2\" align=\"center\"><h4>PHP".number_format($subtotal,2,'.',',')."</h4></td>
							                </tr>";
							$table_data .= "</tbody></table>";
							
					}

							$table_data .= "<br><br><table border=\"1\0\" cellpadding=\"3\">
											<tr>
							                  <td colspan=\"10\" align=\"right\"><font size=\"14px\">Grand Total Amount:</font></td>
							                  <td colspan=\"2\" align=\"center\"><font size=\"14px\">PHP".number_format($grandtotal,2,'.',',')."</font></td>
							                </tr>
							                </table>";
				}
				else{
					$table_data = "<table><tr><td colspan=\"11\"> No matching records found </td></tr></table>";
				}			

			}
			
		}


		$data['orientation']		=	"L";
		$data['pagetype']			=	"letter";
		$data['title']				=	ucwords('Material and Supplies Issuances Summary Report');
		$data['content']			=	'<div class="container">
											<style>
												td {font-size:120%;}
												th, thead {text-align: right;}
												table, th, td { border: 1px solid black; height:10px; vertical-align:bottom;}
												.th {text-align: right;}
												tx {text-align: left; font-size:120%;}
											</style>
											<div>';

		if($type=="MSIS_consolidated"){

			$company = $this->Abas->getCompany($company_id);
			
				$data['content']		.=		'<h1>' . $company->name . '</h1>
											 	 <h2>' . $company->address . '</h2>
											 	 <h3>' . $company->telephone_no .'</h3>';										
		}

				$date_from = isset($_GET['date_from'])?$_GET['date_from']:"";
				$date_to = isset($_GET['date_to'])?$_GET['date_to']:"";

				$data['content']	.=	'<h1>' . $title. '</h1>';

				if($date_from && $date_to){
					$data['content']	.= 	'<font size="9">Date: ' . date("j F Y", strtotime($date_from)) . '  to ' . date("j F Y", strtotime($date_to)) . '</font>';
				}

				$data['content']		.= '<hr>
											</div>
											' . $table_data . '
										</div>';
		unset($record);
		unset($item);
		unset($summary);								
		$this->load->view('pdf-container.php',$data);

		?>	   