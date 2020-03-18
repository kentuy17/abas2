<?php
 
 if($request[0]['status']=='For Verification'){
 	$heading = "Verify Request for Payment";
 	$action = HTTP_PATH."manager/request_for_payment/verify/".$request[0]['id'];
 }else{
 	$heading = "Approve Request for Payment";
 	$action = HTTP_PATH."manager/request_for_payment/verify/".$request[0]['id'];
 }

?>
<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title"><?php echo $heading ?><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2></div>
</div>

	<div class="panel-body" style="overflow-x: auto" class='col-xs-12 col-sm-12 col-lg-12'>
		<table class="table table-striped table-bordered">
			<tr>
				<td colspan='2'><h4>Summary</h4></td>
			</tr>
			<tr>
				<td><b>Company</b></td>
				<td><?php echo $company->name; ?></td>
			</tr>
			<tr>
				<td><b>Requested Date</b></td>
				<td><?php echo date("j F Y",strtotime($request[0]['request_date']))?></td>
			</tr>
			<?php if($request[0]['payee_type']!="Supplier"): ?>
			<tr>
				<td><b>Requested By</b></td>
				<td><?php echo $request[0]['requested_by']?></td>
			</tr>
			<?php endif ?>
			<tr>
				<td><b>Payee Type</b></td>
				<td><?php echo $request[0]['payee_type']?></td>
			</tr>
			<tr>
				<td><b>Payment To</b></td>
				<td><?php echo $payee?></td>
			</tr>
			<tr>
				<td><b>Reference Document</b></td>
				<td><?php echo $request[0]['reference_document']?></td>
			</tr>
			<?php if($request[0]['reference_document']!="None"): ?>
				<tr>
					<td><b>Reference Transaction</b></td>
					<td><?php echo $request[0]['remark']?></td>
				</tr>
			<?php endif ?>
			<tr>
				<td><b>Purpose</b></td>
				<td><?php echo $request[0]['purpose']?></td>
			</tr>
			<tr>
				<td><b>Prepared By</b></td>
				<td><?php echo $request[0]['created_by_name']['full_name']?></td>
			</tr>
			<?php if($request[0]['status']=="For Approval"): ?>
			<tr>
				<td><b>Verified By</b></td>
				<td><?php echo $request[0]['verfied_by_name']['full_name']?></td>
			</tr>
			<?php endif ?>
		</table>

		<table class="table table-striped table-bordered">
			<tr>
				<td colspan='4'><h4>Details</h4></td>
			</tr>
			<th>#</th>
			<th>Particulars</th>
			<th>Charge To</th>
			<th>Amount</th>
			<?php 
				if($request_details){
					$ctr = 1;
					$total_amount = 0;
					foreach($request_details as $detail){
						$charge_to = $this->Abas->getVessel($detail['charge_to']);
						echo "<tr>";
							echo "<td>".$ctr."</td>";
							echo "<td>".$detail['particulars']."</td>";
							if($detail['charge_to']!=0){
								$charge_to = $this->Abas->getVessel($detail['charge_to']);
								echo "<td>".$charge_to->name."</td>";
							}else{
								echo "<td>--</td>";
							}
							echo "<td>".number_format($detail['amount'],2,'.',',')."</td>";
						echo "</tr>";
						$total_amount = $total_amount + $detail['amount'];
						$ctr++;
					}
					echo "<tr>
							<td colspan='3' style='text-align:right'><b>Total Amount</b></td>
							<td><b>".number_format($total_amount,2,'.',',')."</b></td>
						</tr>";
				}else{
					echo "<tr>";
						echo "<td colspan='4'><center>No record found.</center></td>";
					echo "</tr>";
				}
			?>
  		</table>

		<table class="table table-striped table-bordered">
			<tr>
				<td colspan='4'><h4>Attachments</h4></td>
			</tr>
			<th>#</th>
  			<th>File Name</th>
  			<th>Attachment</th>
			<?php 
				if($request_attachments){
					$ctr = 1;
					foreach($request_attachments as $attachment){
						echo "<tr>";
							echo "<td>".$ctr."</td>";
							echo "<td>".$attachment['file_name']."</td>";
							echo '<td><a href="'.HTTP_PATH.'../assets/uploads/accounting/request_for_payments/attachments/'.$attachment['file_path'].'" class="btn btn-xs btn-default" target="_blank">Download and View File</a></td>';
						echo "</tr>";
						
						$ctr++;
					}
				}else{
					echo "<tr>";
						echo "<td colspan='3'><center>No record found.</center></td>";
					echo "</tr>";
				}
			?>
  		</table>

  		
		<hr>
  		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<span class='pull-right'>
				<?php 

					if($request[0]['status']=='For Verification'){
						$allowed = FALSE;
						if($request[0]['amount']<=10000 && $this->Abas->checkPermissions("manager|verify_rfp_level_1",false)){
							$allowed = TRUE;
						}

						if($request[0]['amount']>10000 && $request[0]['amount']<=100000 && $this->Abas->checkPermissions("manager|verify_rfp_level_2",false)){
							$allowed = TRUE;
						}

						if($request[0]['amount']>100000 && $this->Abas->checkPermissions("manager|verify_rfp_level_3",false)){
							$allowed = TRUE;
						}

						if($allowed==TRUE){
							echo "<input type='button' value='Verify' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: verifyRFP(".$request[0]['id'].")' />";
							echo "<input type='button' value='Disapprove' name='btnCancel' class='btn btn-warning btn-m' onclick='javascript: cancelRFP(".$request[0]['id'].")' />";
						}
					}

					if($request[0]['status']=='For Approval'){
						$allowed = FALSE;
						if($request[0]['amount']<=10000 && $this->Abas->checkPermissions("manager|approve_rfp_level_1",false)){
							$allowed = TRUE;
						}

						if($request[0]['amount']>10000 && $request[0]['amount']<=100000 && $this->Abas->checkPermissions("manager|approve_rfp_level_2",false)){
							$allowed = TRUE;
						}

						if($request[0]['amount']>100000 && $this->Abas->checkPermissions("manager|approve_rfp_level_3",false)){
							$allowed = TRUE;
						}

						if($allowed==TRUE){
							echo "<input type='button' value='Approve' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: approveRFP(".$request[0]['id'].")' />";
							echo "<input type='button' value='Disapprove' name='btnCancel' class='btn btn-warning btn-m' onclick='javascript: cancelRFP(".$request[0]['id'].")' />";
						}
					}
					echo "<input type='button' value='Close' name='btnClose' class='btn btn-danger btn-m' data-dismiss='modal' />";
				?>
			</span>
		</div>
	</div>
	

<script type="text/javascript">

	function cancelRFP(id){

	bootbox.confirm({
   					size: "small",
   					title: "Cancel Request For Payment",
				    message: "Are you sure you want to disapprove/cancel this RFP? (note: this cannot be undone)",
				    buttons: {
				       confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				    	if(result){
				    		window.location.href = "<?php echo HTTP_PATH; ?>manager/request_for_payment/cancel/" + id;
				    	}
				    }
				});
	}
	
	function verifyRFP(id){

	bootbox.confirm({
   					size: "small",
   					title: "Verify Request For Payment",
				    message: "Are you sure you want to verify this RFP?",
				    buttons: {
				       confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				    	if(result){
				    		window.location.href = "<?php echo HTTP_PATH; ?>manager/request_for_payment/verify/" + id;
				    	}
				    }
				});
	}

	function approveRFP(id){

	bootbox.confirm({
   					size: "small",
   					title: "Approve Request For Payment",
				    message: "Are you sure you want to approve this RFP?",
				    buttons: {
				       confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				    	if(result){
				    			window.location.href = "<?php echo HTTP_PATH; ?>manager/request_for_payment/approve/" + id;
				    	}
				    }
				});
	}

</script>
