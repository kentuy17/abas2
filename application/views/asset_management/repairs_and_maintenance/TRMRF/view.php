
<h2>Truck Repairs and Maintenance Report Form</h2>
<div>
<?php
	if($TRMRF['status']=='Draft'){
		if($this->Abas->checkPermissions("asset_management|add_truck_repairs_report",FALSE)){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/submit/TRMRF/'.$TRMRF['id'].'" class="btn btn-success exclude-pageload" target="">Submit</a>';

			echo '<a href="'.HTTP_PATH.CONTROLLER.'/edit/TRMRF/'.$TRMRF['id'].'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Edit</a>';

			echo '<a href="#" onclick="cancelTRMRF('.$TRMRF['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($TRMRF['status']=='For Verification'){
		if($this->Abas->checkPermissions("asset_management|verify_truck_repairs_report",FALSE)){
			echo '<a href="#" onclick="verifyTRMRF('.$TRMRF['id'].');" class="btn btn-success exclude-pageload" target="">Verify</a>';
			echo '<a href="#" onclick="cancelTRMRF('.$TRMRF['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($TRMRF['status']=='For Approval'){
		if($this->Abas->checkPermissions("asset_management|approve_truck_repairs_report",FALSE)){
			echo '<a href="#" onclick="approveTRMRF('.$TRMRF['id'].');" class="btn btn-success exclude-pageload" target="">Approve</a>';
			echo '<a href="#" onclick="cancelTRMRF('.$TRMRF['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($TRMRF['status']=='Final' || $TRMRF['status']=='Approved'){
		if($this->Abas->checkPermissions("asset_management|add_truck_repairs_report",FALSE)){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/prints/TRMRF/'.$TRMRF['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
		}
	}

		echo '<a href="'.HTTP_PATH.CONTROLLER.'/listview/TRMRF" class="btn btn-dark force-pageload">Back</a>';
?>
	
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code No. <?php echo $TRMRF['id']; ?> | 
				Control No. <?php echo $TRMRF['control_number']; ?>
				<span class="pull-right">Status: <?php echo $TRMRF['status'];?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $TRMRF['company_name']; ?></h3>
		<h4 class="text-center"><?php echo $TRMRF['company_address']; ?></h3>
		<h4 class="text-center"><?php echo $TRMRF['company_contact']; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Plate Number:</th>
				<td><?php echo $TRMRF['plate_number']; ?></td>
			</tr>
			<tr>
				<th>Make:</th>
				<td><?php echo $TRMRF['make']; ?></td>
			</tr>
			<tr>
				<th>Model:</th>
				<td><?php echo $TRMRF['model']; ?></td>
			</tr>
			<tr>
				<th>Engine Number:</th>
				<td><?php echo $TRMRF['engine_number']; ?></td>
			</tr>
			<tr>
				<th>Chassis Number:</th>
				<td><?php echo $TRMRF['chassis_number']; ?></td>
			</tr>
			<tr>
				<th>Current Location:</th>
				<td><?php echo $TRMRF['location']; ?></td>
			</tr>
			<tr>
				<th>Driver:</th>
				<td><?php echo $TRMRF['driver']; ?></td>
			</tr>
			<tr>
				<th>Priority:</th>
				<td><?php echo $TRMRF['priority']; ?></td>
			</tr>
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($TRMRF['created_on'])); ?> by <?php echo $TRMRF['created_by']; ?></p>
		<?php if($TRMRF['verified_by']!=NULL){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($TRMRF['verified_on'])); ?> by <?php echo $TRMRF['verified_by']; ?></p>
		<?php } ?>
		<?php if($TRMRF['approved_by']!=NULL){ ?>
		<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($TRMRF['approved_on'])); ?> by <?php echo $TRMRF['approved_by']; ?></p>
		<?php } ?>

	</div>

</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body">
  		<table class="table table-striped">
			<thead>
                <tr>
                   <th>#</th>
                  <th>Complaints</th>
                  <th>Cause and Corrections</th>
                  <th>Remarks</th>
                </tr>
            </thead>

            <tbody>
              <?php 
              	$ctr = 1;
                foreach($TRMRF_details as $detail){
              ?>
              	<tr>
              	  <th>
               		<?php echo $ctr; ?>
                  </th>
                  <th>
               		<?php echo $detail->complaints?>
                  </th>
                  <td>
                  	<?php echo $detail->cause_corrections;?>
                  </td>
                  <td>
                  	<?php echo $detail->remarks;?>
                  </td>
				</tr>
			 <?php 
			    $ctr++;
                }
              ?>				
			</tbody>

  		</table>
	</div>
</div>


<script>
function cancelTRMRF(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Cancel TRMRF",
					    message: "Are you sure you want to cancel this Truck Repairs and Maintenance Report Form? (This cannot be undone)",
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
					    	if(result==true){
					    		window.location.href = "../../cancel/TRMRF/" + id;
					    	}
				
					    }
					});
    }
    function verifyTRMRF(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Verify TRMRF",
					    message: "Are you sure you want to verify this Truck Repairs and Maintenance Report Form?",
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
					    	if(result==true){
					    		window.location.href = "../../verify/TRMRF/" + id;
					    	}
				
					    }
					});
    }
    function approveTRMRF(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Approve TRMRF",
					    message: "Are you sure you want to approve this Truck Repairs and Maintenance Report Form?",
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
					    	if(result==true){
					    		window.location.href = "../../approve/TRMRF/" + id;
					    	}
				
					    }
					});
    }
</script>