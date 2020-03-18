<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title">Approve Accountability Form<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2></div>
</div>
	<div class="panel-body" style="overflow-x:auto">
		<table class="table table-striped table-bordered">
			<tr>
				<td colspan='2'><h4>Summary</h4></td>
			</tr>
			<tr>
				<th>Control No.:</th>
				<td><?php echo $AC->control_number; ?></td>
			</tr>
			<tr>
				<th>Company:</th>
				<td><?php echo $AC->company_name; ?></td>
			</tr>
			<tr>
				<th>Requested By:</th>
				<td><?php echo $AC->requested_by; ?></td>
			</tr>
			<tr>
				<th>Date Requested:</th>
				<td><?php echo  date("F d, Y", strtotime($AC->requested_on)); ?></td>
			</tr>
			<tr>
				<th>Position:</th>
				<td><?php echo $AC->position; ?></td>
			</tr>
			<tr>
				<th>Department:</th>
				<td><?php echo $AC->department; ?></td>
			</tr>
			<tr>
				<th>Status:</th>
				<td><?php echo $AC->status; ?></td>
			</tr>
		</table>
  		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<td colspan='6'><h4>Details</h4></td>
			  	</tr>
                <tr>
                  <th>#</th>
                  <th>Fixed Asset Code</th>
                  <th>Asset Name</th>
                  <th>Description</th>
                  <th>Remarks</th>
                  <th>Status</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $ctr=1;
                foreach($AC_details as $asset){
                	echo "<tr>";
                	echo "<td>".$ctr."</td>";
                	echo "<td>".$asset->asset_code."</td>";
                  	echo "<td>".$asset->item_name."</td>";
                  	echo "<td>".$asset->item_particular."</td>";
                  	echo "<td>".$asset->remarks."</td>";
                  	echo "<td>".$asset->status."</td>";
                  	echo "</tr>";
                  	$ctr++;
                }
              ?>
			</tbody>
  		</table>
  		<div class='modal-footer'>
  		<?php 
  			if($this->Abas->checkPermissions("manager|verify_accountability_form",false) && $AC->status=='For Verification'){
  				 echo "<input type='button' value='Verify' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: verifyAccountabilityForm(".$AC->id.")' />";
  			}
  			if($this->Abas->checkPermissions("manager|approve_accountability_form",false) && $AC->status=='For Approval'){
  				echo "<input type='button' value='Approve' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: approveAccountabilityForm(".$AC->id.")' />";
  			}
  			 echo "<input type='button' value='Disapprove' name='btnSubmit' class='btn btn-warning btn-m' onclick='javascript: cancelAccountabilityForm(".$AC->id.")' />";
  			 echo '<button class="btn btn-danger btn-m" data-dismiss="modal">Close</button>';
  		?>
  		</div>
  	</div>
<script type="text/javascript">
    function verifyAccountabilityForm(id){

	bootbox.confirm({
   					size: "small",
   					title: "Verify  Accountability Form",
				    message: "Are you sure you want to verify this Accountability Form?",
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
				    		window.location.href = "<?php echo HTTP_PATH; ?>manager/accountability_forms/verify/" + id;
				    	}
				    }
				});
	}

	function approveAccountabilityForm(id){

	bootbox.confirm({
   					size: "small",
   					title: "Approve  Accountability Form",
				    message: "Are you sure you want to approve this Accountability Form?",
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
				    		window.location.href = "<?php echo HTTP_PATH; ?>manager/accountability_forms/approve/" + id;
				    	}
				    }
				});
	}

	function cancelAccountabilityForm(id){

	bootbox.confirm({
   					size: "small",
   					title: "Cancel Accountability Form",
				    message: "Are you sure you want to cancel/disapprove this Accountability Form?",
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
				    		window.location.href = "<?php echo HTTP_PATH; ?>manager/accountability_forms/cancel/" + id;
				    	}
				    }
				});
	}

 
</script>