<h2>Statement of Account</h2>
<?php

$total_charges = 0;
$total_payments = 0;
$grandtotal = 0;
$amount = 0;
$qty = 0;
$totalweight = 0;
$total_deductions =0;
$grandtotal_tax = 0;

if(!empty($soa['details'])) {
	if($soa['type']=="General") {

			$detailtable	=	"<table class='table table-striped table-bordered'>";
			$detailtable	.=	"<tr>";
				$detailtable	.=	"<th>Particular</th>";
				$detailtable	.=	"<th>Quantity</th>";
				$detailtable	.=	"<th>UOM</th>";
				$detailtable	.=	"<th>Rate</th>";
				$detailtable	.=	"<th>Payment</th>";
				$detailtable	.=	"<th>Charges</th>";
				$detailtable	.=	"<th>Balance</th>";
				$detailtable	.=	"</tr>";

				foreach($soa['details'] as $ctr=>$detail) {

					$quantity = "-";
					$unit_of_measurement = "-";
					$rate = "-";
					$payment = "-";
					$charges = "-";
					if($detail['quantity']>0){ 
							$quantity = number_format($detail['quantity'],4,'.',',');
					}
					if($detail['unit_of_measurement']!=""){ 
							$unit_of_measurement = $detail['unit_of_measurement'];
					}
					if($detail['rate']>0){ 
							$rate = number_format($detail['rate'],3,'.',',');
					}
					if($detail['payment']>0){
							$payment = number_format($detail['payment'],2,'.',',');
					}
					if($detail['charges']>0){
							$charges = number_format($detail['charges'],2,'.',',');
					}
					
						$detailtable	.=	"<tr>";
							$detailtable	.=	"<td>".$detail['particular']."</td>";
							$detailtable	.=	"<td>".$quantity."</td>";
							$detailtable	.=	"<td>".$unit_of_measurement."</td>";
							$detailtable	.=	"<td>".$rate."</td>";
							$detailtable	.=	"<td>".$payment."</td>";
							$detailtable	.=	"<td>".$charges."</td>";
							$detailtable	.=	"<td align=\"right\">".number_format($detail['balance'],2,'.',',')."</td>";
						$detailtable	.=	"</tr>";
						//$grandtotal = $grandtotal + $detail['balance'];
						$total_payments = $total_payments + $detail['payment'];
						$total_charges = $total_charges + $detail['charges'];
					
				}

					$grandtotal = $total_charges - $total_payments;

		$detailtable .= "<tr>
							<td colspan='5'></td>
							<td align=\"right\"><b>Total Balance</b></td>
							<td align=\"right\"><b>" . number_format($grandtotal,2,'.',',') . "</b></td>
						</tr>";

		$detailtable	.=	"</table>";

	}
	elseif($soa['type']=="With Out-Turn Summary") {

		foreach($soa['details'] as $ctr=>$detail) {
			$OS = $this->Operation_model->getOutTurnSummary($detail['out_turn_summary_id']);
		}

			$detailtable	=	"<table class='table table-striped table-bordered'>";
			$detailtable	.=	"<tr>";
		
				$detailtable	.=	"<th>Warehouse</th>";
				if($OS->type_of_service=="Trucking"){
					$detailtable	.=	"<th>Truck Co.</th>";
					$colspan = 5;
				}else{
					$colspan = 4;
				}
	
				if($OS->type_of_service=="Trucking"){
					if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
						$detailtable	.=	"<th><b>No. of Bags</b></th>";
						$detailtable	.=	"<th><b>MT</b></th>";
						$detailtable	.=	"<th><b>Rate / bag (50 kgs)</b></th>";
					}else{
						$detailtable	.=	"<th><b>Quantity</b></th>";
						$detailtable	.=	"<th><b>MT</b></th>";

						if($detail['empty_sacks']==true){
							$detailtable	.=	"<th>Rate / MT</th>";
						}else{
							$detailtable	.=	"<th>Rate / Km</th>";
						}
						
					}
					
				}else{
					
					if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
						$detailtable	.=	"<th><b>No. of Bags</b></th>";
						$detailtable	.=	"<th><b>MT</b></th>";
						$detailtable	.=	"<th>Rate / Move(s)</th>";
					}else{
						$detailtable	.=	"<th><b>Quantity</b></th>";
						$detailtable	.=	"<th><b>MT</b></th>";
						$detailtable	.=	"<th>Rate / Move(s)</th>";
					}
				}

				//if($detail['empty_sacks']==false){
					$detailtable	.=	"<th>Transaction</th>";
				//}
					
				$detailtable	.=	"<th>Amount</th>";
				$detailtable	.=	"</tr>";

				$totalbags =0;

				foreach($soa['details'] as $ctr=>$detail) {

					if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
						$weight = ($detail['total_weight']/1000);
						$bagqty = ($detail['total_weight']/50);
						if($OS->type_of_service=="Trucking"){
							$moves = "";
						}else{
							$moves = " / " . $detail['number_of_moves'];
						}
					}else{
						$bagqty = $detail['quantity'];
						$weight = ($detail['total_weight']/1000);
						$moves = " / " . $detail['number_of_moves'];
					}

						if($detail['empty_sacks']==true){
				   			$empty_sacks_qty = ($bagqty*0.09)/1000;
				   			$empty_sacks_amount =  $empty_sacks_qty*$detail['rate'];
				   			$xamount = $detail['amount'] - $empty_sacks_amount;
				   			//$colspan = 4;
				   		}else{
				   			$xamount = $detail['amount'];
				   		}

						$detailtable	.=	"<tr>";
							
							$detailtable	.=	"<td>".$detail['warehouse']. "</td>";
							if($OS->type_of_service=="Trucking"){
								$detailtable	.=	"<td>".$detail['trucking_company']."</td>";
							}
							$detailtable	.=	"<td align=\"right\">".number_format($bagqty,4,'.',',')."</td>";
							$detailtable	.=	"<td align=\"right\">".number_format($weight,4,'.',',')."</td>";

							if($detail['empty_sacks']==true){
								$detailtable	.=	"<td align=\"right\">P ".number_format($detail['rate'],3,'.',',')."</td>";
							}else{
								$detailtable	.=	"<td align=\"right\">P ".number_format($detail['rate'],3,'.',','). $moves ."</td>";
							}
							
							//if($detail['empty_sacks']==false){
								$detailtable	.=	"<td>".$detail['transaction']."</td>";
							//}

							$detailtable	.=	"<td align=\"right\">".number_format($xamount,2,'.',',')."</td>";
							$detailtable	.=	"</tr>";

						$qty = $qty + $detail['quantity'];
						$totalbags = $totalbags + $bagqty;
						$totalweight = $totalweight + $detail['total_weight'];
						$grandtotal = $grandtotal + $xamount;	

				}


		if($detail['tail_end_handling']==true){

			$tail_end_computation = ($totalbags*3.68);
			$detailtable .= "<tr>
								<td align=\"center\" colspan=\"5\"></td>
								<td align=\"right\"><b>".number_format($grandtotal,2,'.',',')."</b></td>
							</tr>";
			$detailtable .= "<tr>
								<td>TAIL END HANDLING</td>
								<td align=\"right\">".number_format($totalbags,4,'.',',')."</td>
								<td align=\"right\">".number_format(($totalweight/1000),4,'.',',')."</td>
								<td align=\"right\">P 3.68 / 1</td>
								<td></td>
								<td align=\"right\">".number_format($tail_end_computation,2,'.',',')."</td>
							</tr>";
		}else{
			$tail_end_computation = 0;
		}

		$detailtable .= "<tr>
							<td colspan='" . $colspan . "'></td>
							<td align=\"right\"><b>Total</b></td>
							<td align=\"right\"><b>" . number_format($grandtotal+$tail_end_computation,2,'.',',') . "</b></td>
						</tr>";

		$detailtable	.=	"</table>";

	}

		$detailtable .="<table class='table table-striped table-bordered'>";

		$SOA_amount = $this->Billing_model->getSOAAmount($soa['type'],$soa['id']);
		
		$vat_12_percent = $SOA_amount['vat_12_percent'];
		$vat_amount = $SOA_amount['vat_amount'];
		$vat_5_percent = $SOA_amount['vat_5_percent'];
		$wtax_15_percent = $SOA_amount['wtax_15_percent'];
		$wtax_2_percent = $SOA_amount['wtax_2_percent'];
		$wtax_1_percent = $SOA_amount['wtax_1_percent'];
		$total_tax = $SOA_amount['total_tax'];
		
		if($soa['vat_12_percent']==1){
			$detailtable .= "<tr>";

			if($soa['add_tax']==0){
				$detailtable .= "<td align=\"right\"><b>12% VAT</b></td>";
			}else{
				$detailtable .= "<td align=\"right\"><b>Add: 12%</b></td>";
			}
							
				$detailtable .= "<td align=\"right\"><b>" . number_format($vat_12_percent,2,'.',',') . "</b></td>
							</tr>";

			if($soa['add_tax']!=1){
				$detailtable .= "<tr>
								<td align=\"right\"><b>VATable Amount</b></td>
								<td align=\"right\"><b>" . number_format($vat_amount,2,'.',',') . "</b></td>
								</tr>";
			}
							
		}

		if($soa['vat_5_percent']==1){

			$detailtable .= "<tr>";

			if($soa['add_tax']==0){
				$detailtable .= "<td align=\"right\"><b>5% VAT</b></td>";
			}else{
				$detailtable .= "<td align=\"right\"><b>Add: 5%</b></td>";
			}
							
				$detailtable .= "<td align=\"right\"><b>" . number_format($vat_5_percent,2,'.',',') . "</b></td>
							</tr>";
							
		}
		if($soa['wtax_15_percent']==1){

			$detailtable .= "<tr>";

			if($soa['add_tax']==0){
				$detailtable .= "<td align=\"right\"><b>With-holding Tax (15%)</b></td>";
			}else{
				$detailtable .= "<td align=\"right\"><b>Add: 15%</b></td>";
			}	
							
				$detailtable .= "<td align=\"right\"><b>" . number_format($wtax_15_percent,2,'.',',') . "</b></td>
							</tr>";

		}
		if($soa['wtax_2_percent']==1){

			$detailtable .= "<tr>";

			if($soa['add_tax']==0){
				$detailtable .= "<td align=\"right\"><b>With-holding Tax (2%)</b></td>";
			}else{
				$detailtable .= "<td align=\"right\"><b>Add: 2%</b></td>";
			}	
							
				$detailtable .= "<td align=\"right\"><b>" . number_format($wtax_2_percent,2,'.',',') . "</b></td>
							</tr>";

		}
		if($soa['wtax_1_percent']==1){

			$detailtable .= "<tr>";

			if($soa['add_tax']==0){
				$detailtable .= "<td align=\"right\"><b>With-holding Tax (1%)</b></td>";
			}else{
				$detailtable .= "<td align=\"right\"><b>Add: 1%</b></td>";
			}
							
				$detailtable .= "<td align=\"right\"><b>" . number_format($wtax_1_percent,2,'.',',') . "</b></td>
							</tr>";

		}

	
		if($soa['add_tax']!=1){

			if($soa['vat_5_percent']==1 || $soa['wtax_15_percent']==1 || $soa['wtax_2_percent']==1 || $soa['wtax_1_percent']==1){

			$detailtable .= "<tr>
							<td align=\"right\"><b>Total Deductions</b></td>
							<td align=\"right\"><b>" . number_format($total_tax,2,'.',',') . "</b></td>
							</tr>";
			}

			$grandtotal_tax = $SOA_amount['grandtotal_less_tax'];


		}elseif($soa['add_tax']==1){

			
			$detailtable .= "<tr>
							<td align=\"right\"><b>Total Additional Charges</b></td>
							<td align=\"right\"><b>" . number_format($total_tax,2,'.',',') . "</b></td>
							</tr>";

			$grandtotal_tax = $SOA_amount['grandtotal_add_tax'];
		}
		
		$detailtable .= "</table>";

		$detailtable .= "<table class='table table-striped table-bordered'>
							<tr>
								<td style='text-align:right;font-size:14px;'><b>Total Amount Due</b></td>
								<td style='text-align:right;font-size:14px;'><center><b>PHP " . number_format($grandtotal_tax,2,'.',',') . "</b></center></td>
							</tr>
						</table>";

}
else{
	$detailtable	=	"<p>No details found!</p>";
}


?>

<div>
	<?php
		if($soa['status']=="Draft"){
			if($this->Abas->checkPermissions("finance|add_statement_of_account",FALSE)){
				echo "<a href='". HTTP_PATH."statements_of_account/change_status/" . $soa['type'] . "/" . $soa['id'] . "/submit' class='btn btn-success' target=''>Submit</a> ";
				echo "<a href='". HTTP_PATH."statements_of_account/edit/" . $soa['id'] ."' class='btn btn-warning exclude-pageload' data-toggle='modal' data-target='#modalDialog' data-backdrop='static'>Edit</a>";
				
				if($this->Abas->checkPermissions("finance|approve_statement_of_account",FALSE)){
					echo "<a href='#' class='btn btn-danger exclude-pageload' onclick='cancelSOA("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Cancel</a>";
				}
			}
		}
		elseif($soa['status']=="Pending for Approval"){
			if($this->Abas->checkPermissions("finance|approve_statement_of_account",FALSE)){
				echo "<a href='". HTTP_PATH."statements_of_account/change_status/" . $soa['type'] . "/" . $soa['id'] . "/approve' class='btn btn-success exclude-pageload'>Approve</a>";
				echo "<a href='#' class='btn btn-warning exclude-pageload' onclick='returnSOA("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Return</a>";
				echo "<a href='#' class='btn btn-danger exclude-pageload' onclick='cancelSOA("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Cancel</a>";
				
			}
		}
		elseif($soa['status']=="Approved"){
			if($this->Abas->checkPermissions("finance|add_statement_of_account",FALSE)){
				echo "<a href='". HTTP_PATH."statements_of_account/prints/full/".$soa['id']."' class='btn btn-info exclude-pageload' target='_blank'>Print Billing Summary</a>";
			

				if($soa['type']!="General"){
					echo "<a href='" . HTTP_PATH . "statements_of_account/prints/data/" .$soa['id']. "' class='btn btn-info exclude-pageload' target='_blank'>SOA Data Print</a>";
				}else{

					echo "<div class='btn-group' style='margin-top:-5px'>
							<button type='button' class='btn btn-info '>SOA Data Print</a></button>
						      <button type='button' class='btn btn-info dropdown-toggle exclude-pageload' data-toggle='dropdown' aria-expanded='false'>
						        <span class='caret'></span>
						        <span class='sr-only'>Toggle Dropdown</span>
						      </button>
						      <ul class='dropdown-menu' role='menu'>
									<li><a href='" . HTTP_PATH . "statements_of_account/prints/data/" .$soa['id']. "'  target='_blank'>With Balance</a></li>
									<li><a href='" . HTTP_PATH . "statements_of_account/prints/data_alter/" .$soa['id']. "'  target='_blank'>With-out Balance</a></li>
						      </ul></div>&nbsp";

				}

				echo "<a href='#' class='btn btn-success exclude-pageload' onclick='receivedByClient("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Mark as Received by Client</a>";
				//echo "<a href='". HTTP_PATH."statements_of_account/change_status/" . $soa['type'] . "/" . $soa['id'] . "/received' class='btn btn-success'>Mark as Received by Client</a>";

			}
			if($this->Abas->checkPermissions("finance|approve_statement_of_account",FALSE)){
				echo "<a href='#' class='btn btn-warning exclude-pageload' onclick='returnSOA("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Return</a>";
				echo "<a href='#' class='btn btn-danger exclude-pageload' onclick='cancelSOA("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Cancel</a>";
			}
		}
		elseif($soa['status']=="Waiting for Payment"){

			$remaining_balance = $grandtotal_tax - $this->Billing_model->getSOAPayments($soa['id'])->total_payments;
			$payments = $this->Billing_model->getSOAPayments($soa['id'])->total_payments;

			if($this->Abas->checkPermissions("finance|add_statement_of_account",FALSE)){
				
				//if(number_format($remaining_balance,2,".","")==0.00){
				if($payments>0){
					echo "<a href='". HTTP_PATH."statements_of_account/change_status/" . $soa['type'] . "/" . $soa['id'] . "/paid' class='btn btn-success'>Mark as Paid</a>";
				}
				//}
				
			}
			if($this->Abas->checkPermissions("finance|approve_statement_of_account",FALSE)){
				echo "<a href='#' class='btn btn-warning exclude-pageload' onclick='returnSOA("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Return</a>";
				echo "<a href='#' class='btn btn-danger exclude-pageload' onclick='cancelSOA("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Cancel</a>";
			}

			if($soa['type']=="General"){
				$type = "general";
			}elseif($soa['type']=="With Out-Turn Summary"){
				$type = "out-turn";
			}

			echo "<a href='". HTTP_PATH."statements_of_account/view_payments/".$type."/".$soa['id']."' class='btn btn-warning pull-right' data-toggle='modal' data-target='#modalDialog'>Remaining Balance: PHP ".number_format($remaining_balance,2,'.',',')."</a>";

			//if($this->Abas->checkPermissions("finance|approve_statement_of_account",FALSE)){
			//	echo "<a href='#' class='btn btn-danger' onclick='cancelSOA("."&#39".$soa['type']."&#39".",".$soa['id'].");'>Cancel</a>";
			//}
		}
		elseif($soa['status']=="Paid"){
			
			$remaining_balance = $grandtotal_tax - $this->Billing_model->getSOAPayments($soa['id'])->total_payments;

			if($soa['type']=="General"){
				$type = "general";
			}elseif($soa['type']=="With Out-Turn Summary"){
				$type = "out-turn";
			}

			echo "<a href='". HTTP_PATH."statements_of_account/view_payments/".$type."/".$soa['id']."' class='btn btn-warning pull-right' data-toggle='modal' data-target='#modalDialog'>Remaining Balance: PHP ".number_format($remaining_balance,2,'.',',')."</a>";

		}
			echo "<a href='". HTTP_PATH."statements_of_account/' class='btn btn-dark force-pageload' target=''>Back</a> ";
	?>

</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			Transaction Code: <?php echo $soa['id']; ?>
			| Control No. <?php echo $soa['control_number']; ?>
			<span class="pull-right">Status: <?php echo $soa['status']; ?></span>
		</h3>
	</div>
	<div class="panel-body">
		<h3 class="text-center"><?php echo $soa['company']->name; ?></h3>
		<h4 class="text-center"><?php echo $soa['company']->address; ?></h3>
		<h4 class="text-center"><?php echo $soa['company']->telephone_no; ?></h4>

		<?php 
			if($soa['comments']!="" || $soa['comments']!=NULL){
				echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    	<strong>Comments:</strong> '.$soa['comments'].'
                  	  </div>';
			}
		?>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Date</th>
				<td><?php echo date("j F Y", strtotime($soa['created_on'])); ?></td>
			</tr>
			<tr>
				<th>Contract Reference. No.</th>
				<td><?php echo isset($soa['contract_reference_no'])?$soa['contract_reference_no']:"-"; ?>
				<?php 
					if(isset($mother_contract->reference_no)) { 
						echo " | Mother Contract Ref No.". $mother_contract->reference_no; 
					} 
				?>
				</td>
			</tr>
			<tr>
				<th>Terms</th>
				<td><?php echo isset($soa['terms'])?$soa['terms']." days":"-"; ?></td>
			</tr>
			<tr>
				<th>Date Received By Client</th>
				<td><?php echo isset($soa['sent_to_client_on'])?date("j F Y",strtotime($soa['sent_to_client_on'])):"-"	; ?></td>
			</tr>
			<tr>
				<th>Reference No.</th>
				<td><?php echo $soa['reference_number']; ?></td>
			</tr>
			<tr>
				<th>Client</th>
				<td><?php echo $soa['client']['company']; ?></td>
			</tr>
			<tr>
				<th>TIN</th>
				<td><?php echo isset($soa['client']['tin_no'])?$soa['client']['tin_no']:"-"; ?></td>
			</tr>
			<tr>
				<th>Address</th>
				<td><?php echo isset($soa['client']['address'])?$soa['client']['address']:"-"; ?></td>
			</tr>
			<tr>
				<th>Contact No. / Fax No.</th>
				<td><?php echo isset($soa['client']['contact_no'])?$soa['client']['contact_no']:"-";?> / <?php isset($soa['client']['fax_no'])?$soa['client']['fax_no']:"-";?></td>
			</tr>
			<?php
				if($soa['services']!=""){
					echo "<tr>
							<th>Services</th>
							<td>" . ucwords($soa['services']) . "</td>
						  </tr>";
				}
			?>
			<tr>
				<th>Description</th>
				<td><?php echo $soa['description']?></td>
			</tr>
			<?php if(isset($soa['out_turn_summary_id'])){ ?>
				<tr>
					<th>Out-turn Summary Transaction Code No.</th>
					<td><?php echo $soa['out_turn_summary_id']?></td>
				</tr>
			<?php } ?>
		</table>
		
		<?php echo $detailtable; ?>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($soa['created_on'])); ?> by <?php echo $soa['created_by']['full_name']; ?></p>

	</div>

</div>

<?php



if($soa['type']=="With Out-Turn Summary"){

echo '<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Breakdown</h3></div>
		<div class="panel-body">
        <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
			<div class="panel panel-info">';

			$i=1;
			

		 	$OS = $this->Operation_model->getOutTurnSummary($detail['out_turn_summary_id']);
			//$OS_details = $this->Operation_model->getOutTurnSummaryDetails($detail['out_turn_summary_id']);
			$OS_deliveries = $this->Operation_model->getOutTurnSummaryDeliveries($detail['out_turn_summary_id']);
			
			if($OS->service_order_id!=0){
				$SO = $this->Operation_model->getServiceOrder($OS->service_order_id);
				$SO_Detail = $this->Operation_model->getServiceOrderDetail($SO->type,$SO->id);
			}
			
			foreach($soa['details'] as $detail){

	           echo '<a class="panel-heading" role="tab" id="heading'.$i.'" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" aria-expanded="true" aria-controls="collapse'.$i.'">
	              	<span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Warehouse: ' . $detail['warehouse']. '</h4></a>
	           		 <div id="collapse'.$i.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'.$i.'">
		              	<div class="panel-body" style="overflow: auto">';
		  					
		  				//if($OS->type_of_service=="Trucking"){


			  				 echo '<table border="0" cellspacing="2" class="table table-striped table-bordered">
									<tr>
										<td><b>Consignee:</b></td>
										<td>'.$detail['consignee'].'</td>
										<td align="right"><b>Transaction:</b></td>
										<td>'.$OS->type_of_service.'</td>
									</tr>
									<tr>
										<td><b>Vessel:</b></td>
										<td>'. $detail['on_board_vessel'] .'</td>
										<td align="right"><b>Warehouse:</b></td>
										<td>'.$detail['warehouse'].'</td>
									</tr>
									<tr>
										<td><b>Commodity/Cargo:</b></td>
										<td>'.$detail['commodity_cargo'].'</td>
										<td align="right"><b>BL No.:</b></td>
										<td>'.$detail['bill_of_lading_number'].'</td>
									</tr>
									<tr>
										<td><b>Destination:</b></td>
										<td>'.$detail['destination'].'</td>
										<td align="right"><b>AI No.:</b></td>
										<td>'.$detail['authority_to_issue_number'].'</td>
									</tr>
							  </table>';
						//}

								  echo '<table border="1" cellspacing="1" class="table table-striped table-bordered">
									    <tr>
										    <td align="center"></td>
											<td align="center"><b>Date</b></td>';

										if($detail['empty_sacks']==true){
											echo '<td align="center"><b>Way Bill No.</b></td>';
											echo '<td align="center"><b>Delivery Receipt No.</b></td>';
										}else{
											echo '<td align="center"><b>Doc. No.</b></td>';
										}

										if($OS->type_of_service=="Trucking"){
											echo '<td align="center"><b>Plate No.</b></td>';
											echo '<td align="center"><b>Truck Co.</b></td>';
										}

									  echo'<td align="center"><b>Variety/Item</b></td>';

									  if($detail['empty_sacks']==false){
										   echo'<td align="center"><b>Transaction</b></td>';
									   }
										
									   echo'<td align="center"><b>Quantity</b></td>
										   <td align="center"><b>Gross Kilos</b></td>';

								       if($detail['empty_sacks']==true){
								       	    echo '<td align="center"><b>Total</b></td>';
								       }

									   echo '</tr>';

										$qty=0;
										$gross_weight = 0;
										$total = 0;

										$deliveries = $this->Operation_model->getOutTurnSummaryDeliveries($detail['out_turn_summary_id']);
										
										foreach($deliveries as $row){

											if(strlen($row['warehouse_issuance_form_number'])>3){
												$wf = $row['warehouse_issuance_form_number'];
											}elseif(strlen($row['warehouse_receipt_form_number'])>3){
												$wf = $row['warehouse_receipt_form_number'];
											}else{
												$wf = $row['others'];
											}


											if($OS->type_of_service=="Trucking"){

												if($detail['warehouse'] == $row['warehouse'] && $detail['transaction']==$row['transaction'] && $detail['trucking_company']==$row['trucking_company']){

												   echo '<tr> 
												   		 <td align="center"><input type="checkbox"></td>
														 <td align="center">' . date("d-M-y",strtotime($row['delivery_date'])). '</td>';
												   
												   	if($detail['empty_sacks']==true){
												   		echo '<td align="center">' . $row['way_bill_number'] . '</td>';
												   		echo '<td align="center">' . $row['delivery_receipt_number'] . '</td>';
												   	}else{
												   		echo '<td align="center">' . $wf . '</td>';
												   	}

													echo '<td align="center">' . $row['truck_plate_number']. '</td>
													      <td align="center">' . $row['trucking_company']. '</td>';
									
													echo '<td align="center">' . $row['variety_item']. '</td>';

													if($detail['empty_sacks']==false){
														  echo '<td align="center">' . $row['transaction']. '</td>';
													}

													echo '<td align="center">' . $row['quantity'] . '</td>
														  <td align="center">' . $row['net_weight'] . '</td>';

													if($detail['empty_sacks']==true){
														echo '<td align="center">' . number_format(($row['net_weight']/1000)*$detail['rate'],3,'.',',') . '</td>';

														$total = $total + (($row['net_weight']/1000)*$detail['rate']);
													}

													echo '</tr>';

												$gross_weight = $gross_weight+$row['net_weight'];
												$qty=$qty+$row['quantity'];

												}
											}elseif($OS->type_of_service=="Handling"){
												if($detail['warehouse'] == $row['warehouse'] && $detail['transaction']==$row['transaction'] && $detail['number_of_moves']==$row['number_of_moves']){
												   echo '<tr>
												   		 <td align="center"><input type="checkbox"></td>
														 <td align="center">' . date("d-M-y",strtotime($row['delivery_date'])). '</td>
												         <td align="center">' . $wf .  '</td>';
													echo '<td align="center">' . $row['variety_item']. '</td>
														  <td align="center">' . $row['transaction']. '</td>
														  <td align="center">' . $row['quantity'] . '</td>';
													echo '<td align="center">' . $row['gross_weight'] . '</td>';
													
													echo '</tr>';

												$gross_weight = $gross_weight+$row['gross_weight'];
												$qty=$qty+$row['quantity'];

												}
											}
											
										}


									if($OS->type_of_service=="Handling"){
										$colspan = 5;
									}
									elseif($OS->type_of_service=="Trucking"){
										$colspan = 7;
									}


									echo '<tr>
										  <td colspan="' . $colspan . '" align="right">Total</td>
										  <td align="center"><b>' . number_format($qty,4,'.',',') . '</b></td>
										  <td align="center"><b>' . number_format($gross_weight,4,'.',',') . '</b></td>';

									if($detail['empty_sacks']==true){
										echo '<td align="center"><b>' . number_format($total,4,'.',',') . '</b></td>';
									}

							   		echo '</tr>';

							   		if($detail['empty_sacks']==true){

							   			$empty_sacks_qty = number_format(($qty*0.09)/1000,5,'.',',');
							   			$empty_sacks_weight = number_format($empty_sacks_qty*1000,2,'.',',');
							   			$empty_sacks_amount =  number_format($empty_sacks_qty*$detail['rate'],2,'.',',');

										echo '<tr>
										      <td colspan="' . $colspan . '" align="right">Empty Sacks</td>
										      <td align="center"><font color="red"><b>('.$empty_sacks_qty.')</b></font></td>
										      <td align="center"><font color="red"><b>('.$empty_sacks_weight.')</b></font></td>
										       <td align="center"><font color="red"><b>('.$empty_sacks_amount.')</b></font></td>
										      </tr>';
	
									}

									echo "</table>";

								if($OS->type_of_service=="Handling"){

									//$amount = number_format(($detail['rate']*$qty*$detail['number_of_moves']),2,'.',',');

									if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){

										$qty = ($gross_weight/50);
										$amount = number_format($detail['amount'],2,'.',',');

										echo '<table class="table table-striped table-bordered">
											<tr>
												<td align="right">TOTAL AMOUNT: ('.  $qty . ') Bags X (' . number_format($detail['rate'],3,'.',',') . ') Rate  X (' . $detail['number_of_moves'] . ') No. of Moves =
												</td>
												<td>
													<center><b>PHP '. $amount . '</b></center>	
												</td>
											</tr>
										  </table>';

									}else{

										$qty = ($gross_weight/1000);
										$amount = number_format($detail['amount'],2,'.',',');

										echo '<table class="table table-striped table-bordered">
											<tr>
												<td align="right">TOTAL AMOUNT: ('.  $qty . ') MT X (' . number_format($detail['rate'],3,'.',',') . ') Rate  X (' . $detail['number_of_moves'] . ') No. of Moves =
												</td>
												<td>
													<center><b>PHP '. $amount . '</b></center>	
												</td>
											</tr>
										  </table>';
									}
								

								}
								elseif($OS->type_of_service=="Trucking"){

									if(strpos($soa['client']['company'], "NFA") !== false || strpos($soa['client']['company'], "National") !== false){
										$mt = ($gross_weight/50);
										//$amount = number_format(($gross_weight/50)*$detail['rate'],2,'.',',');

										$amount = number_format($detail['amount'],2,'.',',');

										echo '<table class="table table-striped table-bordered">
											<tr>
												<td align="right">TOTAL AMOUNT: ('.  $mt . ') bags X (' . number_format($detail['rate'],3,'.',',') . ') Rate =
												</td>
												<td>
													<center><b>PHP '. $amount . '</b></center>
												</td>
											</tr>
										  </table>';

									}else{
										$mt = ($gross_weight/1000);
										//$amount = number_format(($gross_weight/1000)*($detail['rate']*$detail['number_of_moves']),2,'.',',');

										if($detail['empty_sacks']==true){
											$amount = number_format($detail['amount']-$empty_sacks_amount,2,'.',',');
											echo '<table class="table table-striped table-bordered">
											<tr>
												<td align="right">TOTAL AMOUNT: ('.  $mt . ') MT X (' . number_format($detail['rate'],3,'.',',') . ') Rate - (' . $empty_sacks_amount . ') Empty Sacks =
												</td>
												<td>
													<center><b>PHP '. $amount . '</b></center>
												</td>
											</tr>
										  </table>';
										}else{
											$amount = number_format($detail['amount'],2,'.',',');
											echo '<table class="table table-striped table-bordered">
											<tr>
												<td align="right">TOTAL AMOUNT: ('.  $mt . ') MT X (' . number_format($detail['rate'],3,'.',',') . ') Rate X (' . $detail['number_of_moves'] . ') KM =
												</td>
												<td>
													<center><b>PHP '. $amount . '</b></center>
												</td>
											</tr>
										  </table>';
										}
									}
									
									
								}

				echo '</div>
				</div>';
					
	            $i++;
			}
         
echo 		'</div>
			</div>
        </div>
</div>';
}
?>

<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>

<script>

function receivedByClient(type,id){
	bootbox.prompt({
	    title: "Select the actual date when the client received the SOA.",
	    inputType: 'date',
	    callback: function (result) {

	        if(result==null || result==""){
	    		console.log("Do nothing");
	    	}else{

	    		window.location.href = "../change_status/" + type + "/" + id + "/received/"+result;
	    		
	    	}
	    }
	});
}

function cancelSOA(type,id){

	bootbox.prompt({
   					size: "large",
				    title: "Are you sure you want to cancel this Statement of Account? (Please provide comments below.)",
				    inputType: 'textarea',
				    buttons: {
				        confirm: {
				            label: '<i class="fa fa-check"></i> Yes'
				        },
				        cancel: {
				            label: '<i class="fa fa-times"></i> No'
				        }
				    },
				    callback: function (result) {
				    	if(result==null || result==""){
				    		console.log("Do nothing");
				    	}else{
				    		window.location.href = "../change_status/" + type + "/" + id + "/cancel";
				    		$.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH;?>statements_of_account/set_comments/"+id,
							     data: {comments:result}
							  });
				    	}
				    	
				    }
				});
}

function returnSOA(type,id){

	bootbox.prompt({
   					size: "large",
				    title: "Are you sure you want to return this Statement of Account? (Please provide comments below.)",
				    inputType: 'textarea',
				    buttons: {
				        confirm: {
				            label: '<i class="fa fa-check"></i> Yes'
				        },
				        cancel: {
				            label: '<i class="fa fa-times"></i> No'
				        }
				    },
				    callback: function (result) {
				    	if(result==null || result==""){
				    		console.log("Do nothing");
				    	}else{
				    		window.location.href = "../change_status/" + type + "/" + id + "/return";

				    		 $.ajax({
								     type:"POST",
								     url:"<?php echo HTTP_PATH;?>statements_of_account/set_comments/"+id,
								     data: {comments:result}
								  });
				    	}
				    	
				    }
				});
}
</script>