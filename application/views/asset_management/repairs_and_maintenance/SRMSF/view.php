
<h2>Ship Repairs and Maintenance Survey Form</h2>
<div>
<?php
	if($SRMSF['status']=='Draft'){
		if($this->Abas->checkPermissions("asset_management|add_vessel_evaluation_form",FALSE)){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/submit/SRMSF/'.$SRMSF['id'].'" class="btn btn-success exclude-pageload" target="">Submit</a>';

			echo '<a href="'.HTTP_PATH.CONTROLLER.'/edit/SRMSF/'.$SRMSF['id'].'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Edit</a>';

			echo '<a href="#" onclick="cancelSRMSF('.$SRMSF['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($SRMSF['status']=='For Verification'){
		if($this->Abas->checkPermissions("asset_management|verify_vessel_evaluation_form",FALSE)){
			echo '<a href="#" onclick="verifySRMSF('.$SRMSF['id'].');" class="btn btn-success exclude-pageload">Verify</a>';
			echo '<a href="#" onclick="cancelSRMSF('.$SRMSF['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($SRMSF['status']=='For Approval'){
		if($this->Abas->checkPermissions("asset_management|approve_vessel_evaluation_form",FALSE)){
			echo '<a href="#" onclick="approveSRMSF('.$SRMSF['id'].');" class="btn btn-success exclude-pageload">Approve</a>';
			echo '<a href="#" onclick="cancelSRMSF('.$SRMSF['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($SRMSF['status']=='Final' || $SRMSF['status']=='Approved'){
		if($this->Abas->checkPermissions("asset_management|add_vessel_evaluation_form",FALSE)){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/prints/SRMSF/'.$SRMSF['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
		}
	}

		echo '<a href="'.HTTP_PATH.CONTROLLER.'/listview/SRMSF" class="btn btn-dark force-pageload">Back</a>';
?>
	
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"> 
				Transaction Code No. <?php echo $SRMSF['id']; ?> |
				Control No. <?php echo $SRMSF['control_number']; ?>
				<span class="pull-right">Status: <?php echo $SRMSF['status'];?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $SRMSF['company_name']; ?></h3>
		<h4 class="text-center"><?php echo $SRMSF['company_address']; ?></h3>
		<h4 class="text-center"><?php echo $SRMSF['company_contact']; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Work Order No.:</th>
				<td><?php echo $SRMSF['WO_number']; ?></td>
			</tr
			<tr>
				<th>Vessel Name:</th>
				<td><?php echo $SRMSF['vessel_name']; ?></td>
			</tr>
			<tr>
				<th>Dry-Docking Date:</th>
				<td><?php echo date("j F Y", strtotime($SRMSF['dry_docking_date'])); ?></td>
			</tr>
			<tr>
				<th>Dry-Docking Location:</th>
				<td><?php echo $SRMSF['dry_docking_location']; ?></td>
			</tr>
			<tr>
				<th>Length Overall:</th>
				<td><?php echo $SRMSF['length_loa']; ?></td>
			</tr>
			<tr>
				<th>Breadth:</th>
				<td><?php echo $SRMSF['breadth']; ?></td>
			</tr>
			<tr>
				<th>Depth:</th>
				<td><?php echo $SRMSF['depth']; ?></td>
			</tr>
			<tr>
				<th>Gross Tonnage:</th>
				<td><?php echo $SRMSF['gross_tonnage']; ?></td>
			</tr>
			<tr>
				<th>Notes/Other/General Remarks:</th>
				<td><?php echo $SRMSF['notes']; ?></td>
			</tr>
		</table>
		
		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($SRMSF['created_on'])); ?> by <?php echo $SRMSF['created_by']; ?></p>
		<?php if($SRMSF['verified_by']!=NULL){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($SRMSF['verified_on'])); ?> by <?php echo $SRMSF['verified_by']; ?></p>
		<?php } ?>
		<?php if($SRMSF['approved_by']!=NULL){ ?>
		<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($SRMSF['approved_on'])); ?> by <?php echo $SRMSF['approved_by']; ?></p>
		<?php } ?>

	</div>

</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Evaluation Result</h3></div>
	<div class="panel-body">

		<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true"><!--start accordion-->
			
			<div class="panel panel-info">

			 <?php
			 	foreach($steps as $step){
			 ?>
			 	<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $step[0];?>" aria-expanded="true" aria-controls="collapse<?php echo $step[0];?>">
			    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">
			    <?php echo $step[0]." - ".$step[1];?></h4>
			    </a>

			            <div id="collapse<?php echo $step[0];?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $step[0];?>">

			              	<div class="panel-body">

			              		<table class="table table-striped">
									<thead>
				                        <tr>
				                          <th>Index</th>
				                          <th>Item/Particulars</th>
				                          <th>Rating</th>
				                          <th>Make</th>
				                          <th>Model</th>
				                          <th>Remarks</th>
				                        </tr>
				                    </thead>

				                    <tbody>
				                      <?php 
				                        foreach($SRMSF_details as $item){
				                        	if($item->index==$step[0]){
				                      ?>
				                      	<tr>
				                          <th>
				                       		<?php echo $item->index.".".$item->set.".".$item->sub_set;?>
				                          </th>
				                          <td>
				                          	<?php echo $item->evaluation_item_name;?>
				                          </td>
				                          <td>
				                          	<?php echo $item->rating;?>
				                          </td>
				                          <td>
				                          	<?php echo ($item->make!="N/A")?$item->make:"-";?>
				                          </td>
				                          <td>
				                          	<?php echo ($item->model!="N/A")?$item->model:"-";?>
				                          </td>
				                          <td>
				                          	<?php echo $item->remarks;?>
				                          </td>
										</tr>					
				                      <?php
				                      		}
				                        }
				                      ?>
									</tbody>

			              		</table>

			              	</div>

			            </div>
			 <?php
			 	}
			 ?>

			</div>
				
        </div><!--end accordion-->

	</div>
</div>


<script>
function cancelSRMSF(id){

	//use &#39 for passing single qoute string parameter

    	bootbox.confirm({
       					size: "small",
					    title: "Cancel SRMSF",
					    message: "Are you sure you want to cancel this Ship Repair and Maintenanance Survey Form? (This cannot be undone)",
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
					    		window.location.href = "../../cancel/SRMSF/" + id;
					    	}
				
					    }
					});
    }
function verifySRMSF(id){

	//use &#39 for passing single qoute string parameter

    	bootbox.confirm({
       					size: "small",
					    title: "Verify SRMSF",
					    message: "Are you sure you want to verify this Ship Repair and Maintenanance Survey Form?",
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
					    		window.location.href = "../../verify/SRMSF/" + id;
					    	}
				
					    }
					});
    }

function approveSRMSF(id){

	//use &#39 for passing single qoute string parameter

    	bootbox.confirm({
       					size: "small",
					    title: "Approve SRMSF",
					    message: "Are you sure you want to approve this Ship Repair and Maintenanance Survey Form?",
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
					    		window.location.href = "../../approve/SRMSF/" + id;
					    	}
				
					    }
					});
    }
</script>