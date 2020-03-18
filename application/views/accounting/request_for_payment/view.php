<h2>Request for Payment</h2>
<div>

<?php if($request[0]['status']=='For Voucher' || $request[0]['status']=='For voucher' || $request[0]['status']=='For releasing'){?>
	<a href="<?php echo HTTP_PATH.'accounting/request_for_payment/print/'.$request[0]['id']?>" class="btn btn-info exclude-pageload" target="_blank">Print</a>
<?php } ?>

<?php if($request[0]['status']<>'For releasing' && $request[0]['status']<>'Cancelled'){?>
	<input type="button" class="btn btn-danger btn-m exclude-pageload" onclick="javascript:cancelRFP(<?php echo $request[0]['id'];?>);" value="Cancel"/>
<?php } ?>

<a href="<?php echo HTTP_PATH.'accounting/request_for_payment/listview'?>" class="btn btn-dark exclude-pageload">Back</a>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"> 
			Transaction Code No. <?php echo $request[0]['id'];?> | 
			Control No. <?php echo $request[0]['control_number']; ?>
			<span class="pull-right">Status: <?php echo $request[0]['status'];?></span>
		</h3>
	</div>
	<div class="panel-body" style="overflow: auto">
		<h3 class="text-center"><?php echo $company->name; ?></h3>
		<h4 class="text-center"><?php echo $company->address; ?></h3>
		<h4 class="text-center"><?php echo $company->telephone_no; ?></h4>
		
			<?php 
				if($request[0]['status']=="Cancelled"){
					echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<label style="color:white;font-size:12px"><br><strong>Comments:</strong> '.$request[0]['remark']."</label>
						</div>";
				}
			?>
			
		<table class="table table-striped table-bordered">
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
		</table>
		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($request[0]['created_on'])); ?> by <?php echo $request[0]['created_by_name']['full_name']; ?></p>
		<?php if($request[0]['verified_by']!=0){?>
			<p>Verified On <?php echo date("h:i:s a j F Y", strtotime($request[0]['verified_on'])); ?> by <?php echo $request[0]['verified_by_name']['full_name']; ?></p>
		<?php } ?>
		<?php if($request[0]['approved_by']!=0){?>
			<p>Approved On <?php echo date("h:i:s a j F Y", strtotime($request[0]['approved_on'])); ?> by <?php echo $request[0]['approved_by_name']['full_name']; ?></p>
		<?php } ?>
	</div>
</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body" style="overflow: auto">
  		<table class="table table-striped table-bordered">
  			<thead>
  				<th>#</th>
  				<th>Particulars</th>
  				<th>Charge To</th>
  				<th>RFP Amount</th>
  				<th>With-holding Tax</th>
  				<th>VAT</th>
  				<th>Input Tax</th>
  				<th>W-Tax Expanded</th>
  				<th>Amount</th>
  				<?php if($request[0]['status']=='For Voucher' || $request[0]['status']=='For voucher' ){ ?>
  				<th>Manage</th>
  				<?php } ?>
  			</thead>
  			<tbody>
			<?php 
				if($request_details){
					$ctr = 1;
					$total_amount = 0;
					foreach($request_details as $detail){
						echo "<tr>";
							echo "<td>".$ctr."</td>";
							echo "<td>".$detail['particulars']."</td>";
							if($detail['charge_to']!=0){
								$charge_to = $this->Abas->getVessel($detail['charge_to']);
								echo "<td>".$charge_to->name."</td>";
							}else{
								echo "<td>--</td>";
							}
							if($detail['vat_amount']+$detail['input_tax_amount']==0){
								echo "<td>".number_format($detail['amount'],2,'.',',')."</td>";
							}else{
								echo "<td>".number_format($detail['vat_amount']+$detail['input_tax_amount'],2,'.',',')."</td>";
							}
							if($detail['wtax']!=''){
								echo "<td>".$detail['wtax']."</td>";
							}else{
								echo "<td>--</td>";
							}
							if($detail['vat_amount']!=''){
								echo "<td>".number_format($detail['vat_amount'],2,'.',',')."</td>";
							}else{
								echo "<td>--</td>";
							}
							if($detail['input_tax_amount']!=''){
								echo "<td>".number_format($detail['input_tax_amount'],2,'.',',')."</td>";
							}else{
								echo "<td>--</td>";
							}
							if($detail['wtax_amount']!=''){
								echo "<td>(".number_format($detail['wtax_amount'],2,'.',',').")</td>";
							}else{
								echo "<td>--</td>";
							}
							echo "<td>".number_format($detail['amount'],2,'.',',')."</td>";
							if(($request[0]['status']=='For Voucher' || $request[0]['status']=='For voucher') && $this->Abas->checkPermissions("accounting|add_request_for_payment",false)){
								echo "<td><a href='".HTTP_PATH."accounting/request_for_payment/edit_amount/".$detail['id']."' class='btn-xs btn-warning btn' data-toggle='modal' data-target='#modalDialogNorm' data-backdrop='static'>Add W-Tax</a></td>";
							}
						echo "</tr>";
						$total_amount = $total_amount + $detail['amount'];
						$ctr++;
					}
					echo "<tr>
							<td colspan='8' style='text-align:right'><b>Total Amount</b></td>
							<td><b>".number_format($total_amount,2,'.',',')."</b></td>
						</tr>";
				}else{
					echo "<tr>";
						echo "<td colspan='9'><center>No record found.</center></td>";
					echo "</tr>";
				}
			?>
			</tbody>
  		</table>
	</div>
</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Attachments</h3></div>
	<div class="panel-body" style="overflow: auto">
  		<table class="table table-striped table-bordered">
  			<thead>
  				<th>#</th>
  				<th>Document Name/Remarks</th>
  				<th>Attachment</th>
  			</thead>
  			<tbody>
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
			</tbody>
  		</table>
	</div>
</div>

<script type="text/javascript">
function cancelRFP(rfp_id){
	bootbox.prompt({
   					size: "medium",
				    title: "Are you sure you want to cancel this RFP? (Please provide comments below.)",
				    inputType: 'textarea',
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
				    	if(result==null || result==""){
				    		console.log("Do nothing");
				    	}else{
				    		$.ajax({
							     type:"POST",
							     url:"<?php echo HTTP_PATH;?>accounting/request_for_payment/cancel/"+rfp_id,
							     data: {comments:result}
							  });
				    		window.location.href = "<?php echo HTTP_PATH;?>accounting/request_for_payment/view/" + rfp_id;
				    	}
				    	
				    }
				});
}
</script>