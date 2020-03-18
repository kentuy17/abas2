
<h2>Motorpool Truck Diagnostic Evaluation</h2>
<div>
<?php
	if($MTDE['status']=='Draft'){
		if($this->Abas->checkPermissions("asset_management|add_truck_evaluation_form",FALSE)){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/submit/MTDE/'.$MTDE['id'].'" class="btn btn-success exclude-pageload" target="">Submit</a>';

			echo '<a href="'.HTTP_PATH.CONTROLLER.'/edit/MTDE/'.$MTDE['id'].'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Edit</a>';

			echo '<a href="#" onclick="cancelMTDE('.$MTDE['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($MTDE['status']=='For Verification'){
		if($this->Abas->checkPermissions("asset_management|verify_truck_evaluation_form",FALSE)){
			echo '<a href="#" onclick="verifyMTDE('.$MTDE['id'].');" class="btn btn-success exclude-pageload" target="">Verify</a>';
			echo '<a href="#" onclick="cancelMTDE('.$MTDE['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($MTDE['status']=='For Approval'){
		if($this->Abas->checkPermissions("asset_management|approve_truck_evaluation_form",FALSE)){
			echo '<a href="#" onclick="approveMTDE('.$MTDE['id'].');" class="btn btn-success exclude-pageload" target="">Approve</a>';
			echo '<a href="#" onclick="cancelMTDE('.$MTDE['id'].');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($MTDE['status']=='Final' || $MTDE['status']=='Approved'){
		if($this->Abas->checkPermissions("asset_management|add_truck_evaluation_form",FALSE)){
			echo '<a href="'.HTTP_PATH.CONTROLLER.'/prints/MTDE/'.$MTDE['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
		}
	}

		echo '<a href="'.HTTP_PATH.CONTROLLER.'/listview/MTDE" class="btn btn-dark force-pageload">Back</a>';
?>
	
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code No. <?php echo $MTDE['id'];?> |
				Control No. <?php echo $MTDE['control_number']; ?>
				<span class="pull-right">Status: <?php echo $MTDE['status'];?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $MTDE['company_name']; ?></h3>
		<h4 class="text-center"><?php echo $MTDE['company_address']; ?></h3>
		<h4 class="text-center"><?php echo $MTDE['company_contact']; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>TRMRF Number:</th>
				<td><?php echo $MTDE['TRMRF_number']; ?></td>
			</tr>
			<tr>
				<th>Plate-Number:</th>
				<td><?php echo $MTDE['plate_number']; ?></td>
			</tr>
			<tr>
				<th>Driver's Name:</th>
				<td><?php echo $MTDE['driver']; ?></td>
			</tr>
			<tr>
				<th>Make:</th>
				<td><?php echo $MTDE['make']; ?></td>
			</tr>
			<tr>
				<th>Model:</th>
				<td><?php echo $MTDE['model']; ?></td>
			</tr>
			<tr>
				<th>Engine Number:</th>
				<td><?php echo $MTDE['engine_number']; ?></td>
			</tr>
			<tr>
				<th>Chassis Number:</th>
				<td><?php echo $MTDE['chassis_number']; ?></td>
			</tr>
			<tr>
				<th>Type:</th>
				<td><?php echo $MTDE['type']; ?></td>
			</tr>
			<tr>
				<th>Notes/Other/General Remarks:</th>
				<td><?php echo $MTDE['notes']; ?></td>
			</tr>
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($MTDE['created_on'])); ?> by <?php echo $MTDE['created_by']; ?></p>
		<?php if($MTDE['verified_by']!=NULL){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($MTDE['verified_on'])); ?> by <?php echo $MTDE['verified_by']; ?></p>
		<?php } ?>
		<?php if($MTDE['approved_by']!=NULL){ ?>
		<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($MTDE['approved_on'])); ?> by <?php echo $MTDE['approved_by']; ?></p>
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
				                        foreach($MTDE_details as $item){
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
function cancelMTDE(id){

	//use &#39 for passing single qoute string parameter

    	bootbox.confirm({
       					size: "small",
					    title: "Cancel MTDE",
					    message: "Are you sure you want to cancel this Motorpool Truck Diagnostic Evaluation? (This cannot be undone)",
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
					    		window.location.href = "../../cancel/MTDE/" + id;
					    	}
				
					    }
					});
    }

    function verifyMTDE(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Verify MTDE",
					    message: "Are you sure you want to verify this Motorpool Truck Diagnostic Evaluation?",
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
					    		window.location.href = "../../verify/MTDE/" + id;
					    	}
				
					    }
					});
    }
    function approveMTDE(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Approve MTDE",
					    message: "Are you sure you want to approve this Motorpool Truck Diagnostic Evaluation?",
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
					    		window.location.href = "../../approve/MTDE/" + id;
					    	}
				
					    }
					});
    }
</script>