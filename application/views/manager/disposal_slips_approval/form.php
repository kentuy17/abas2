<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title">Approve Disposal Slip<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2></div>
</div>
	<div class="panel-body" style="overflow-x:auto">
		<table class="table table-striped table-bordered">
			<tr>
				<td colspan='2'><h4>Summary</h4></td>
			</tr>
			<tr>
				<th>Control No.:</th>
				<td><?php echo $disposal->control_number; ?></td>
			</tr>
			<tr>
				<th>Company:</th>
				<td><?php echo $disposal->company_name; ?></td>
			</tr>
			<tr>
				<th>Requested By:</th>
				<td><?php echo $disposal->requested_by; ?></td>
			</tr>
			<tr>
				<th>Date Requested:</th>
				<td><?php echo  date("F d, Y", strtotime($disposal->requested_on)); ?></td>
			</tr>
			<tr>
				<th>Position:</th>
				<td><?php echo $disposal->position; ?></td>
			</tr>
			<tr>
				<th>Department:</th>
				<td><?php echo $disposal->department; ?></td>
			</tr>
			<tr>
				<th>Checked By:</th>
				<td><?php echo $disposal->checked_by; ?></td>
			</tr>
			<tr>
				<th>Date Checked:</th>
				<td><?php echo  date("F d, Y", strtotime($disposal->checked_on)); ?></td>
			</tr>
			<tr>
				<th>Status:</th>
				<td><?php echo $disposal->status; ?></td>
			</tr>
		</table>
  		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<td colspan='10'><h4>Details</h4></td>
			  	</tr>
                <tr>
                  <th>#</th>
                  <th>Fixed Asset Code</th>
                  <th>Asset Name</th>
                  <th>Description</th>
                  <th>Date Purchased</th>
                  <th>Original Cost</th>
                  <th>Net Book Value</th>
                  <th>Expected or Actual Proceeds</th>
                  <th>Gain or Loss?</th>
                  <th>Reason for Disposal</th>
                </tr>
            </thead>
            <tbody>
              <?php 
               $ctr=1;
                foreach($disposal_details as $asset){
                	echo "<tr>";
                	echo "<td>".$ctr."</td>";
                	echo "<td>".$asset->asset_code."</td>";
                  	echo "<td>".$asset->item_name."</td>";
                  	echo "<td>".$asset->item_particular."</td>";
                  	echo "<td>".date('F d, Y',strtotime($asset->date_purchased))."</td>";
                  	echo "<td>".number_format($asset->original_cost,2,'.',',')."</td>";
                  	echo "<td>".number_format($asset->net_book_value,2,'.',',')."</td>";
                  	echo "<td>".number_format($asset->proceeds,2,'.',',')."</td>";
                  	if($asset->is_gain==0){
                  		echo "<td>Loss</td>";
                  	}else{
                  		echo "<td>Gain</td>";
                  	}
                  	echo "<td>".$asset->reason_for_disposal."</td>";
                  	echo "</tr>";
                  	$ctr++;
                }
              ?>
			</tbody>
  		</table>
  		<div class='modal-footer'>
  		<?php 
  			if($this->Abas->checkPermissions("manager|verify_disposal_slip",false) && $disposal->status=='For Verification'){
  				 echo "<input type='button' value='Verify' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: verifyDisposalSlip(".$disposal->id.")' />";
  			}
  			if($this->Abas->checkPermissions("manager|approve_disposal_slip",false) && $disposal->status=='For Approval'){
  				echo "<input type='button' value='Approve' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: approveDisposalSlip(".$disposal->id.")' />";
  			}
  			 echo "<input type='button' value='Disapprove' name='btnSubmit' class='btn btn-warning btn-m' onclick='javascript: cancelDisposalSlip(".$disposal->id.")' />";
  			 echo '<button class="btn btn-danger btn-m" data-dismiss="modal">Close</button>';
  		?>
  		</div>
  	</div>
<script type="text/javascript">
    function verifyDisposalSlip(id){

	bootbox.confirm({
   					size: "small",
   					title: "Verify Disposal Slip",
				    message: "Are you sure you want to verify this Disposal Slip?",
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
				    		window.location.href = "<?php echo HTTP_PATH; ?>manager/disposal_slips/verify/" + id;
				    	}
				    }
				});
	}

	function approveDisposalSlip(id){

	bootbox.confirm({
   					size: "small",
   					title: "Approve Disposal Slip",
				    message: "Are you sure you want to approve this Disposal Slip?",
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
				    		window.location.href = "<?php echo HTTP_PATH; ?>manager/disposal_slips/approve/" + id;
				    	}
				    }
				});
	}

	function cancelDisposalSlip(id){

	bootbox.confirm({
   					size: "small",
   					title: "Cancel Disposal Slip",
				    message: "Are you sure you want to cancel/disapprove this Disposal Slip?",
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
				    		window.location.href = "<?php echo HTTP_PATH; ?>manager/disposal_slips/cancel/" + id;
				    	}
				    }
				});
	}

 
</script>