<?php	

$preparor = $this->Abas->getUser($CV['created_by']);
$prepared_by	=	"<center><u>".strtoupper($preparor['full_name'])."</u><br><i>Accounting Staff</i></center>";
//$verified_by	=	"<center>______________________<br><i>Accounting Analyst/Officer</i></center>";
$verified_by	=	"<center>______________________<br><br></center>";
if($_SESSION['abas_login']['user_location']=='Makati'){
	$approved_by	=	"<center>______________________<br><i>BNV&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SNV</i></center>";
	$recorded_by	=	"<center>______________________<br><i>JNV&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SPV</i></center>";
}else{
	//$approved_by	=	"<center>______________________<br><i>Accounting Manager</i></center>";
	$approved_by	=	"<center>______________________<br><br></center>";
	//$recorded_by	=	"<center>______________________<br><i>Accounting Staff</i></center>";
	$recorded_by	=	"<center>______________________<br><br></center>";
}

$page =  '
			<style type="text/css">
				 h1 { font-size:220%;text-align:center; }
				 h2 { font-size:170%;text-align:center; }	
				 h3 { font-size:120%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td { font-size:100%;}
				 th { background-color:#5d5d5d;color:#FFFFFF;font-weight:bold;font-size:120%;text-align:center;vertical-align:center}
				  p { text-align:left;font-size:150%; }
				.bt { font-size:110%; font-weight:bold; text-align:left}
				.btg { font-size:110%; text-decoration: underline; text-align:left}
				.btx { font-weight:bold; text-align:right; font-size:130%}
				.tg {border-collapse:collapse;border-spacing:0;}
				.tg td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
				.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;}
				.tg .tg-yw4l{vertical-align:top}
				.tg .tg-9hbo{font-weight:bold;vertical-align:top;horizontal-align:left}
				.underline {text-decoration:underline;border-bottom: 2px;font-weight:bold}
				.doubleUnderline {text-decoration:underline;text-decoration-style:double;}
				.bot {vertical-align:bottom;}
			</style>
		    <div>
				<table>
					<tr>
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h1 style="font-size:140%; text-align:left">'. $company->name .'</h1>
			    			<h3 style="font-size:110%;text-align:left">'. $company->address.'</h3>
			    			<h3 style="font-size:110%; text-align:left">'. $company->telephone_no.'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="9" class="btx">CV No.</td>
						<td colspan="1" class="btx" align="left">'.$CV['control_number'].'</td>
					</tr>
					<tr>
						<td colspan="10"><h2>CHECK VOUCHER<br></h2></td>
					</tr>
					<tr>
						<td class="bt" colspan="8" style="text-align:right">Date:</td>
						<td class="btg"colspan="2" >'.date('F j, Y',strtotime($CV['voucher_date'])).'</td>
					</tr>
				</table>
			</div>

			<div>
				<table border="0" cellpadding="3" width="100%">
					<tr>
						<td class="bt">Pay to:</td>
						<td class="btg" colspan="5">'.$payee_name.'</td>
					</tr>
	            	<tr>
	            		<td class="bt">Amount in words:</td>
	            		<td class="btg" colspan="5">'.$this->Mmm->chequeTextFormat($CV['amount']).'</td>
	            	</tr>
	            	<tr>
	            		<td class="bt">Amount in figures:</td>
	            		<td class="btg" colspan="5">'.number_format($CV['amount'],2,'.',',').'</td>
	            	</tr>
	            	<tr>
	            		<td class="bt">Check No.</td>
	            		<td class="btg" colspan="5">'.$CV['check_num'].'</td>
	            	</tr>
				</table>
				<br><br>
				<table style="border:2px black solid;" cellpadding="2" cellspacing="1" width="100%">
					<tr>
						<th style="width:575px">Particulars</th>
						<th style="width:140px">Amount</th>
					</tr>';

				$apv_ids = array();
				if($CV['transaction_type']=='po'){

					$page .= '<tr>
						<td colspan="2">'.$CV['remark'].'<br></td>
					</tr>';

					if($CV['apv_no']==''){
						$apv_ids = explode(',',$CV['multi_apv_no']);

					}else{
						$apv_ids[0] = $CV['apv_no'];
					}
					foreach($apv_ids as $apv_id){
						$apv = $this->Accounting_model->getAccountsPayableVoucher($apv_id);
						$apv_entries = $this->Accounting_model->getAccountingEntry($apv_id,'ac_ap_vouchers');
						$amount =0;
						foreach($apv_entries as $row){
							if($row['coa_id'] == TRADE_PAYABLE){//get Trade receivale amount
								$amount = $row['credit_amount'];
							}
						}

						$page .= '<tr>
									<td style="text-align:center">APV No. '.$apv[0]['control_number'].'</td>
									<td style="text-align:center">'.number_format($amount,2,'.',',').'</td>
								</tr>';
					}
				}elseif($CV['transaction_type']=='non-po'){
					$page .= '<tr>
									<td style="text-align:center">'.$CV['remark'].'</td>
									<td style="text-align:center">'.number_format($CV['amount'],2,'.',',').'</td>
								</tr>';
				}
		 $page .= 	'
				</table>
				<br><br>
				<table border="1" cellpadding="8" width="100%">
					<thead>
						<tr>
							<th>Account Code</th>
							<th>Account Name</th>
							<th>Debit</th>
							<th>Credit</th>
						</tr>
					</thead>
					<tbody>';

					$total_debit = 0;
					$total_credit = 0;
					foreach($CV_entries as $entries){
						$account = $this->Accounting_model->getAccount($entries['coa_id']);
							$page .= '<tr>';
								$page .= '<td style="text-align:center">'.$account['code'].'</td>';
								$page .= '<td>'.$account['name'].'</td>';
								$page .= '<td style="text-align:right">'.number_format($entries['debit_amount'],2,'.',',').'</td>';
								$page .= '<td style="text-align:right">'.number_format($entries['credit_amount'],2,'.',',').'</td>';
							$page .= '</tr>';
							$total_debit = $total_debit + $entries['debit_amount'];
							$total_credit = $total_credit + $entries['credit_amount'];
					}
			  $page .=  '<tr>
							<td colspan="2" style="text-align:right">Total</td>
							<td style="text-align:right"><b>'.number_format($total_debit,2,'.',',').'</b></td>
							<td style="text-align:right"><b>'.number_format($total_credit,2,'.',',').'</b></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div>
				<table border="0" cellpadding="3" width="100%">
					<tr>
						<td>
							<table>
								<tr>
									<td style="font-size:12px"><b>Prepared By:</b> </td>
									<td style="font-size:12px"><b>Verified By:</b> </td>
								</tr>
								<tr align="center">
									<td style="font-size:12px"><br><br>'.$prepared_by.'</td>
									<td style="font-size:12px"><br><br>'.$verified_by.'</td>
								</tr>
								<tr>
									<td style="font-size:12px"><b>Approved By:</b> </td>';
				if($_SESSION['abas_login']['user_location']=='Makati'){
			 		$page .= '<td style="font-size:12px"><b></b> </td>';
			 	}else{
			 		//$page .= '<td style="font-size:12px"><b>Recorded By:</b> </td>';
			 		$page .= '<td style="font-size:12px"><b></b> </td>';
			 	}

			 $page .=			'</tr>
								<tr align="center">
									<td style="font-size:12px"><br><br>'.$approved_by.'</td>
									<td style="font-size:12px"><br><br>'.$recorded_by.'</td>
								</tr>
							</table>
						</td>
						<td>
							<table style="border:2px black solid;" cell-spacing="10" cell-spacing="5">
								<tr>
									<td style="font-size:9px"><br><br>Received from '.$company->name.' the sum of PESOS <br>
										<u>'.$this->Mmm->chequeTextFormat($CV['amount']).'</u><br>
										(PHP <u>'.number_format($CV['amount'],2,'.',',').'</u>) 
										in full/partial settlement of the above amount.
									</td>
								</tr>
								<tr>
									<td style="text-align:center"><br><br>___________________________________________</td>
								</tr>
								<tr>
									<td style="text-align:center;font-size:9px">Payee Signature</td>
								</tr>
								<tr>
									<td style="text-align:center"><br><br>___________________________________________</td>
								</tr>
								<tr>
									<td style="text-align:center;font-size:9px">Check No.</td>
								</tr>
								<tr>
									<td style="text-align:center"><br><br>___________________________________________</td>
								</tr>
								<tr>
									<td style="text-align:center;font-size:9px">Date Received<br></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>';
	       

$data['orientation']	=	"P";
if($papersize=='legal'){
	$data['pagetype']		=	"legal";
}
else{
	$data['pagetype']		=	"letter";
}
$data['title']			=	"Check Voucher - Control No." . $CV['control_number'];
$data['control_number']	=	"Transaction Code: " .$CV['id'];
$data['content']		=	$page;

$this->load->view('pdf-container.php',$data);

?>