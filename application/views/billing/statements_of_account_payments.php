<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">Payments</h2>
	</div>
</div>

<div class="panel-body">		
		<div >
			<table class='table table-bordered table-striped table-hover'>
				<thead>
					<tr>
						<th>#</th>
						<th>Payment Date</th>
						<th>OR No.</th>
						<th>Mode of Payment</th>
						<th>Amount Paid</th>
					</tr>
				</thead>
					<tbody>
							<?php 
								if(isset($payments)){
									if(count($payments)>0){ 
									
									$total = 0;
									$ctr = 1;
										foreach($payments as $payment){

											$OR = $this->Collection_model->getOfficialReceipts($payment->id);
											$arr = array();
											foreach($OR as $num){
												$arr[] = $num->control_number;
											}
											$OR_str = implode(', ',$arr);

											echo "<tr>";
											echo "<td>".$ctr."</td>";
											echo "<td>".date('j F Y h:m a', strtotime($payment->received_on))."</td>";
											echo "<td>".$OR_str."</td>";
											echo "<td>".$payment->mode_of_collection."</td>";
											echo "<td align='right'>".number_format($payment->net_amount,2,'.',',')."</td>";
											echo "</tr>";
											$ctr++;
											$total = $total + $payment->net_amount;
										}
										echo "<tr>";
										echo "<td colspan='4' style='font-weight:bold;text-align:right'>Total</td>";
										echo "<td align='right'>PHP ".number_format($total,2,'.',',')."</td>";
										echo "</tr>";

										echo "<tr>";
										echo "<td colspan='4' style='font-weight:bold;text-align:right'>Amount Due</td>";
										echo "<td align='right'>PHP ".number_format($soa_amount,2,'.',',')."</td>";
										echo "</tr>";

										echo "<tr>";
										echo "<td colspan='4' style='font-weight:bold;text-align:right'>Remaining Balance</td>";
										echo "<td align='right'>PHP ".number_format($soa_amount-$total,2,'.',',')."</td>";
										echo "</tr>";
									}else{
										echo "<tr><td colspan='5'><center>No Payment(s) has been recieved yet.</center></td></tr>";
									}
							}
							?>		
					</tbody>
			</table>
		</div>
</div>