
<h2>Vessel Work Order</h2>
<div>
<?php
	if($WO['status']=='Draft'){
		if($this->Abas->checkPermissions("asset_management|add_vessel_work_order",FALSE)){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/submit/WO/'.$WO['id'].'" class="btn btn-success exclude-pageload" target="">Submit</a>';

			echo '<a href="'.HTTP_PATH.CONTROLLER.'/edit/WO/'.$WO['id'].'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Edit</a>';

			echo '<a href="#" onclick="cancelWO('.$WO['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($WO['status']=='For Verification'){
		if($this->Abas->checkPermissions("asset_management|verify_vessel_work_order",FALSE)){
			echo '<a href="#" onclick="verifyWO('.$WO['id'].');" class="btn btn-success exclude-pageload" target="">Verify</a>';
			echo '<a href="#" onclick="cancelWO('.$WO['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($WO['status']=='For Approval'){
		if($this->Abas->checkPermissions("asset_management|approve_vessel_work_order",FALSE)){
			echo '<a href="#" onclick="approveWO('.$WO['id'].');" class="btn btn-success exclude-pageload" target="">Approve</a>';
			echo '<a href="#" onclick="cancelWO('.$WO['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($WO['status']=='Final' || $WO['status']=='Approved'){
		if($this->Abas->checkPermissions("asset_management|add_vessel_work_order",FALSE)){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/prints/WO/'.$WO['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
		}
	}

		echo '<a href="'.HTTP_PATH.CONTROLLER.'/listview/WO" class="btn btn-dark force-pageload">Back</a>';
?>
	
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"> 
				Transaction Code No. <?php echo $WO['id']; ?> |
				Control No. <?php echo $WO['control_number']; ?>
				<span class="pull-right">Status: <?php echo $WO['status'];?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $WO['company_name']; ?></h3>
		<h4 class="text-center"><?php echo $WO['company_address']; ?></h3>
		<h4 class="text-center"><?php echo $WO['company_contact']; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Vessel Name:</th>
				<td><?php echo $WO['vessel_name']; ?></td>
			</tr>
			<tr>
				<th>Current Location:</th>
				<td><?php echo $WO['location']; ?></td>
			</tr>
			<tr>
				<th>Requisitioner:</th>
				<td><?php echo $WO['requisitioner']; ?></td>
			</tr>
			<tr>
				<th>Designation:</th>
				<td><?php echo $WO['designation']; ?></td>
			</tr>
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($WO['created_on'])); ?> by <?php echo $WO['created_by']; ?></p>
		<?php if($WO['verified_by']!=NULL){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($WO['verified_on'])); ?> by <?php echo $WO['verified_by']; ?></p>
		<?php } ?>
		<?php if($WO['approved_by']!=NULL){ ?>
		<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($WO['approved_on'])); ?> by <?php echo $WO['approved_by']; ?></p>
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
                  <th>Complaint/Particulars</th>
                  <th>Status/Remarks</th>
                </tr>
            </thead>

            <tbody>
              <?php 
              	$ctr = 1;
                foreach($WO_details as $detail){
              ?>
              	<tr>
              	  <th>
               		<?php echo $ctr; ?>
                  </th>
                  <th>
               		<?php echo $detail->complaint_particulars?>
                  </th>
                  <td>
                  	<?php echo $detail->status_remarks;?>
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
function cancelWO(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Cancel WO",
					    message: "Are you sure you want to cancel this Vessel Work Order? (This cannot be undone)",
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
					    		window.location.href = "../../cancel/WO/" + id;
					    	}
				
					    }
					});
    }

 function verifyWO(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Verify WO",
					    message: "Are you sure you want to verify this Vessel Work Order?",
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
					    		window.location.href = "../../verify/WO/" + id;
					    	}
				
					    }
					});
    }
function approveWO(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Approve WO",
					    message: "Are you sure you want to approve this Vessel Work Order?",
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
					    		window.location.href = "../../approve/WO/" + id;
					    	}
				
					    }
					});
    }
</script>