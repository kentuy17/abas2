<?php	

$preparor = $this->Abas->getUser($report['created_by']);
$prepared_by	=	"<b>Prepared By:</b> <center><u>".strtoupper($preparor['full_name'])."</u><br><i>Cashier</i></center>";
$noted_by	=	"<b>Noted By:</b> <center>_______________________________<br><i>Finance Supervisor</i></center>";


if($_SESSION['abas_login']['user_location']=="Makati"){
	$verified_by	=	"<b>Verified By:</b> <center><u>ALEXANDER N. VEGA, JR.</u><br><i>SVP- Corporate Services</i></center>";
}else{
	$verified_by	=	"<b>Verified By:</b> <center>_______________________________<br><i>Finance Manager</i></center>";
}


$front_page =  '
			<style type="text/css">
				 h1 { font-size:220%;text-align:center; }
				 h2 { font-size:170%;text-align:center; }	
				 h3 { font-size:120%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:110%;}
				 th { font-weight:bold;font-size:150%;text-align:center;vertical-align:center}
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
				<table>
					<tr>
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h1 style="text-align:left">'. $report['company_name'] .'</h1>
			    			<h3 style="text-align:left">'. $report['company_address'].'</h3>
			    			<h3 style="text-align:left">'. $report['company_contact'].'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="1" cellpadding="1">
					<tr>
						<td colspan="9" class="btx">DCCRR No.</td>
						<td colspan="1" class="btx" align="left">'.$report['control_number'].'</td>
					</tr>
					<tr>
						<td colspan="10"><h2>DAILY CASH AND CHECKS RECEIVED REPORT<br></h2></td>
					</tr>
					<tr>
						<td colspan="10" style="text-align:center"><h2>Date: <u>'.$report['created_on'].'</u></h2><br></td>
					</tr>			

				</table>
			</div>

			<div>
				<table border="1" cellpadding="3" width="100%">
					<thead>
						<tr>
							<th rowspan="2" style="width:40px">#</th>
							<th rowspan="2" style="width:80px">OR / AR</th>
							<th rowspan="2" style="width:140px">Name of Customer</th>
							<th rowspan="2" style="width:80px">Amount of Cash</th>
							<th colspan="3" style="width:320px">Check or Bank Transfer/Deposit</th>
							<th rowspan="2" style="width:385px">Particulars</th>
							<th rowspan="2" style="width:80px">Total Amount</th>
						</tr>
						<tr>
							<th style="width:240px" colspan="2">Bank - Branch</th>
							<th style="width:80px">Amount</th>
						</tr>
	  				</thead>
	            	<tbody>';

	            	$beginning_balance = 0;
	            	$total_collection = 0;
	            	$total_deposits = 0;
	            	$ending_balance =0;
	            	$row_num = 1;
	            	$total = 0;

	            	//$this->Mmm->debug($report_details);
	            		
	 				if(!empty($report_details)){
	 					
	            		foreach($report_details as $row){

	            			$OR = "";
	 						$AR = "";

	            				$payment = (object)$this->Collection_model->getPayment($row->payment_id);		

		            			if($payment->mode_of_collection=='Cash'){

		            				if($row->official_receipt_id){
		            					$OR = "OR No.". $this->Collection_model->getORNumber($row->official_receipt_id)->control_number;
		            				}
		            				
		            				if($row->acknowledgement_receipt_id){
		            					$AR = "AR No.". $this->Collection_model->getARNumber($row->acknowledgement_receipt_id)->control_number;
		            				}

				            			$front_page .= '<tr>';
				            				$front_page .= '<td style="text-align:center;width:40px">'.$row_num.'</td>';
				            				$front_page .= '<td style="width:80px">'.$OR.' '.$AR.'</td>';
				            				$front_page .= '<td style="width:140px">'.$payment->payor.'</td>';
			            					$front_page .= '<td style="text-align:right;width:80px">'.number_format($row->cash_denomination,2,'.',',').' x '.number_format($row->cash_quantity,0,'.',',').'</td>';
			            					$front_page .= '<td style="width:240px;text-align:center" colspan="2">---</td>';
			            					$front_page .= '<td style="width:80px;text-align:center">---</td>';
				            				$front_page .= '<td style="width:385px">'.$payment->particulars.'</td>';
				            				$front_page .= '<td style="text-align:right;width:80px">'.number_format($row->cash_denomination*$row->cash_quantity,2,'.',',').'</td>';
				            			$front_page .= '</tr>';
				            			$row_num = $row_num +1;

				            			$total = $total + ($row->cash_denomination*$row->cash_quantity);
				            		
			            		}

			            		if($payment->mode_of_collection=='Check'){

		            				if($row->official_receipt_id){
		            					$OR = "OR No.". $this->Collection_model->getORNumber($row->official_receipt_id)->control_number;
		            				}
		            				
		            				if($row->acknowledgement_receipt_id){
		            					$AR = "AR No.". $this->Collection_model->getARNumber($row->acknowledgement_receipt_id)->control_number;
		            				}

			            				
				            			$front_page .= '<tr>';
				            				$front_page .= '<td style="text-align:center;width:40px">'.$row_num.'</td>';
				            				$front_page .= '<td style="width:80px">'.$OR.' '.$AR.'</td>';
				            				$front_page .= '<td style="width:140px">'.$payment->payor.'</td>';
					 			
			            					$front_page .= '<td style="width:80px;text-align:center">---</td>';
			            					$front_page .= '<td style="width:240px" colspan="2">'.$row->check_bank.'</td>';
			            					$front_page .= '<td style="width:80px;text-align:right;">'.number_format($row->check_amount,2,'.',',').'</td>';
				            				$front_page .= '<td style="width:385px">'.$payment->particulars.'</td>';
				            				$front_page .= '<td style="text-align:right;width:80px">'.number_format($row->check_amount,2,'.',',').'</td>';
				            			$front_page .= '</tr>';
				            			$row_num = $row_num +1;

				            			$total = $total +$row->check_amount;
			            		}

			            		if($payment->mode_of_collection=='Bank Deposit/Transfer'){

	        							if($row->official_receipt_id){
			            					$OR = "OR No.". $this->Collection_model->getORNumber($row->official_receipt_id)->control_number;
			            				}
			            				
			            				if($row->acknowledgement_receipt_id){
			            					$AR = "AR No.". $this->Collection_model->getARNumber($row->acknowledgement_receipt_id)->control_number;
			            				}

					            			$front_page .= '<tr>';
					            				$front_page .= '<td style="text-align:center;width:40px">'.$row_num.'</td>';
					            				$front_page .= '<td style="width:80px">'.$OR.'</td>';
					            				$front_page .= '<td style="width:140px">'.$payment->payor.'</td>';
				            					$front_page .= '<td style="width:80px;text-align:center">---</td>';
				            					$front_page .= '<td style="width:240px" colspan="2">'.$row->check_bank.'</td>';
				            					$front_page .= '<td style="text-align:right;width:80px">'.number_format($row->check_amount,2,'.',',').'</td>';

					            				$front_page .= '<td style="width:385px">'.$payment->particulars.'</td>';
					            				$front_page .= '<td style="text-align:right;width:80px">'.number_format($row->check_amount,2,'.',',').'</td>';
					            			$front_page .= '</tr>';
					            			$row_num = $row_num +1;

					            			$total = $total +$row->check_amount;
					         
			            			
			            		}
			            		
	            		}
	            	}else{
	      
	            		$front_page .= '<tr>';
            				$front_page .= '<td style="text-align:center;width:40px">---</td>';
            				$front_page .= '<td style="width:80px">---</td>';
            				$front_page .= '<td style="width:140px">---</td>';
	 			
        					$front_page .= '<td style="width:80px;text-align:center">---</td>';
        					$front_page .= '<td style="width:120px">---</td>';
        					$front_page .= '<td style="width:120px">---</td>';

        		
        					$front_page .= '<td style="text-align:right;width:80px">---</td>';

            				$front_page .= '<td style="width:385px">No Payment(s) Received</td>';
            				$front_page .= '<td style="text-align:right;width:80px">---</td>';
            			$front_page .= '</tr>';
            		
	            	}

	            	if($row_num==1){
	            		$front_page .= '<tr>';
            				$front_page .= '<td style="text-align:center;width:40px">---</td>';
            				$front_page .= '<td style="width:80px">---</td>';
            				$front_page .= '<td style="width:140px">---</td>';
	 			
        					$front_page .= '<td style="width:80px;text-align:center">---</td>';
        					$front_page .= '<td style="width:120px">---</td>';
        					$front_page .= '<td style="width:120px">---</td>';

        		
        					$front_page .= '<td style="text-align:right;width:80px">---</td>';

            				$front_page .= '<td style="width:385px">No Payment(s) Received</td>';
            				$front_page .= '<td style="text-align:right;width:80px">---</td>';
            			$front_page .= '</tr>';
	            	}


	  $beginning_balance 	=	$report['beginning_balance'];
	  $total_collection 	=	$report['total_collection'];
	  $total_deposits 		=	$report['total_deposits'];
	  $ending_balance	 	=  ($beginning_balance+$total_collection)-$total_deposits;
	            			
	   $front_page .=  '<tr>
	   					<td colspan="8" style="text-align:right">Total: PHP </td>
	   					<td style="text-align:right">'.number_format($total,2,'.',',').'</td>
	   				 </tr>
	   				</tbody>
				</table>
			</div>
			<div>
			
			<table border="0" cellpadding="0" width="100%">
				<tr>
					<td></td>
					<td>
						<table border="1" cellpadding="1" width="100%">
							<tbody>
							<tr>
								<td colspan="2"><b>SUMMARY</b></td>
							</tr>
							<tr>
								<td style="text-align:right"><b>  Beginning Balance</b></td>
								<td style="text-align:right">PHP '.number_format($beginning_balance,2,'.',',').'</td>
							</tr>
							<tr>
								<td style="text-align:right"><b>  Total Collection</b></td>
								<td style="text-align:right">PHP '.number_format($total_collection,2,'.',',').'</td>
							</tr>
							<tr>
								<td style="text-align:right"><b>  Total Deposits</b></td>
								<td style="text-align:right">PHP '.number_format($total_deposits,2,'.',',').'</td>
							</tr>
							<tr>
								<td style="text-align:right"><b>  Ending Balance</b></td>
								<td style="text-align:right">PHP '.number_format($ending_balance,2,'.',',').'</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>

			</div>';

$breakdown =  '
				<style type="text/css">
					 h1 { font-size:200%;text-align:center; }
					 h2 { font-size:150%;text-align:center; }	
					 h3 { font-size:100%;text-align:center; }
					 h5 { border-bottom: double 3px; }
					 td {font-size:110%;}
					 th { font-weight:bold;font-size:150%;text-align:center;vertical-align:center}
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
				<br><br>
				<div>
					<table border="0" cellpadding="5" width="100%">
						<tr>
							<td colspan="2" ><h2 style="text-align:left">BREAKDOWN:</h2></td>
						</tr>
						<tr>
							<td>
								<table border="1" cellpadding="5" width="100%">
									<thead>
										<tr>
											<td colspan="2"><b>CHECK ON HAND</b></td>
										</tr>
										<tr>
											<td>Bank</td>
											<td>Amount</td>
										</tr>
									</thead>
									<tbody>
									  ';


									$total_checks = 0;
									$total_cash = 0;
									$oneK=$fiveH=$twoH=$oneH=$fifty=$twenty=$ten=$five=$one=$fiftyC=$twentyfiveC=$tenC=$fiveC=$oneC=0; 
									foreach($report_details as $detail){
										
										if($detail->payment_mode=="Check" AND ($detail->payment_status=="For Deposit" OR $detail->payment_status=="Post-dated")){
											
											$breakdown .= '<tr>';
				            				$breakdown .= '<td>'.$detail->check_bank.'</td>';
				            				$breakdown .= '<td style="text-align:right">'.number_format($detail->check_amount,2,'.',',').'</td>';
					            			$breakdown .= '</tr>';

					            			$total_checks = $total_checks + $detail->check_amount;
											
										}elseif($detail->payment_mode=="Cash" AND ($detail->payment_status=="For Deposit" OR $detail->payment_status=="Post-dated")){

											switch($detail->cash_denomination){
		        								case '1000.00': $oneK += $detail->cash_quantity; break;
		        								case '500.00': $fiveH += $detail->cash_quantity; break;
		        								case '200.00': $twoH += $detail->cash_quantity; break;
		        								case '100.00': $oneH += $detail->cash_quantity; break;
		        								case '50.00': $fifty += $detail->cash_quantity; break;
		        								case '20.00': $twenty += $detail->cash_quantity; break;
		        								case '10.00': $ten += $detail->cash_quantity; break;
		        								case '5.00': $five += $detail->cash_quantity; break;
		        								case '1.00': $one += $detail->cash_quantity; break;
		        								case '0.50': $fiftyC += $detail->cash_quantity; break;
		        								case '0.25': $twentyfiveC += $detail->cash_quantity; break;
		        								case '0.10': $tenC += $detail->cash_quantity; break;
		        								case '0.05': $fiveC += $detail->cash_quantity; break;
		        								case '0.01': $oneC += $detail->cash_quantity; break;
		        							}

		        							$total_cash = $total_cash + ($detail->cash_denomination * $detail->cash_quantity);
										}
									}


						$breakdown .=  '				
										<tr>
											<td style="text-align:right">Total Check</td>
											<td style="text-align:right">PHP '.number_format($total_checks,2,'.',',').'</td>
										</tr>
									</tbody>
								</table>
							</td>
						
							<td>
								<table border="1" cellpadding="5" width="100%">
									<thead>
										<tr>
											<td colspan="4"><b>CASH ON HAND</b></td>
										</tr>
										<tr>
											<td style="text-align:center"><b>Bills</b></td>
											<td style="text-align:center">Quantity</td>
											<td style="text-align:center">Amount</td>
											<td style="text-align:center">Total</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="text-align:right">1,000.00</td>
											<td style="text-align:right">'.$oneK.'</td>
											<td style="text-align:right">'.number_format($oneK*1000,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($oneK*1000,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">500.00</td>
											<td style="text-align:right">'.$fiveH.'</td>
											<td style="text-align:right">'.number_format($fiveH*500,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($fiveH*500,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">200.00</td>
											<td style="text-align:right">'.$twoH.'</td>
											<td style="text-align:right">'.number_format($twoH*200,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($twoH*200,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">100.00</td>
											<td style="text-align:right">'.$oneH.'</td>
											<td style="text-align:right">'.number_format($oneH*100,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($oneH*100,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">50.00</td>
											<td style="text-align:right">'.$fifty.'</td>
											<td style="text-align:right">'.number_format($fifty*50,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($fifty*50,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">20.00</td>
											<td style="text-align:right">'.$twenty.'</td>
											<td style="text-align:right">'.number_format($twenty*20,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($twenty*20,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:center"><b>Coins</b></td>
											<td colspan="3"></td>
										</tr>
										<tr>
											<td style="text-align:right">10.00</td>
											<td style="text-align:right">'.$ten.'</td>
											<td style="text-align:right">'.number_format($ten*10,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($ten*10,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">5.00</td>
											<td style="text-align:right">'.$five.'</td>
											<td style="text-align:right">'.number_format($five*5,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($five*5,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">1.00</td>
											<td style="text-align:right">'.$one.'</td>
											<td style="text-align:right">'.number_format($one*1,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($one*1,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">0.50</td>
											<td style="text-align:right">'.$fiftyC.'</td>
											<td style="text-align:right">'.number_format($fiftyC*0.50,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($fiftyC*0.50,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">0.25</td>
											<td style="text-align:right">'.$twentyfiveC.'</td>
											<td style="text-align:right">'.number_format($twentyfiveC*0.25,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($twentyfiveC*0.25,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">0.10</td>
											<td style="text-align:right">'.$tenC.'</td>
											<td style="text-align:right">'.number_format($tenC*0.10,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($tenC*0.10,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">0.05</td>
											<td style="text-align:right">'.$fiveC.'</td>
											<td style="text-align:right">'.number_format($fiveC*0.05,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($fiveC*0.05,2,'.',',').'</td>
										</tr>
										<tr>
											<td style="text-align:right">0.01</td>
											<td style="text-align:right">'.$oneC.'</td>
											<td style="text-align:right">'.number_format($oneC*0.01,2,'.',',').' =</td>
											<td style="text-align:right">'.number_format($oneC*0.01,2,'.',',').'</td>
										</tr>
										<tr>
											<td colspan="3" style="text-align:right">Total Cash On Hand</td>
											<td style="text-align:right">PHP '.number_format($total_cash,2,'.',',').'</td>
										</tr>
										<tr>
											<td colspan="3" style="text-align:right">Total Check On Hand</td>
											<td style="text-align:right">PHP '.number_format($total_checks,2,'.',',').'</td>
										</tr>
										<tr>
											<td colspan="3" style="text-align:right"><b>Ending Balance</b></td>
											<td style="text-align:right">PHP '.number_format($total_checks+$total_cash,2,'.',',').'</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</table>
					<br><br><br><br><br><br><br><br>
					<table border="0" cellpadding="8" width="100%">
						<tr align="center">
							<td style="font-size:12px">'.$prepared_by.'</td>
							<td style="font-size:12px">'.$noted_by.'</td>
							<td style="font-size:12px">'.$verified_by.'</td>
						</tr>
					</table>

				</div>';
	       
if($report['status']!='Active'){
	$data['watermark'] = "Void";
}
$data['orientation']	=	"L";
$data['pagetype']		=	"legal";
$data['title']			=	"Daily Cash and Checks Received Report - Control No." . $report['control_number'];
$data['control_number']	=	"Transaction Code: " .$report['id'];
$data['content'][0]		=	$front_page;
$data['content'][1]		=	$breakdown;

$this->load->view('pdf-container.php',$data);

?>