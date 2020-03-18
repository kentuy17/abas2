<h2>Accountability Form</h2>
<div>
<?php
	if($AC->status=='Draft'){
		if($this->Abas->checkPermissions("asset_management|add_accountability_form",FALSE)){
			echo '<a href="#" onclick="submitAccountabilityForm('.$AC->id.');" class="btn btn-success exclude-pageload" target="">Submit</a>';
			
			echo '<a href="'.HTTP_PATH.'Asset_Management/accountability_form/edit/'.$AC->id.'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog">Edit</a>';

			echo '<a href="#" onclick="cancelAccountabilityForm('.$AC->id.');" class="btn btn-danger exclude-pageload" target="">Cancel</a>';

		}
	}elseif($AC->status=='Approved'){
		if($this->Abas->checkPermissions("asset_management|add_accountability_form",FALSE)){
			echo '<a href="'.HTTP_PATH.'Asset_Management/accountability_form/print/'.$AC->id.'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
		}
	}

		echo '<a href="'.HTTP_PATH.'Asset_Management/accountability_form/listview" class="btn btn-dark force-pageload">Back</a>';
?>
	
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code No. <?php echo $AC->id;?> |
				Control No. <?php echo $AC->control_number; ?>
				<span class="pull-right">Status: <?php echo $AC->status;?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $AC->company_name; ?></h3>
		<h4 class="text-center"><?php echo $AC->company_address; ?></h3>
		<h4 class="text-center"><?php echo $AC->company_contact; ?></h4>

		<table class="table table-striped table-bordered">
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
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($AC->created_on)); ?> by <?php echo $AC->created_by; ?></p>
		<?php if($AC->verified_by!=0){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($AC->verified_on)); ?> by <?php echo $AC->verified_by; ?></p>
		<?php } ?>
		<?php if($AC->approved_by!=0){ ?>
		<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($AC->approved_on)); ?> by <?php echo $AC->approved_by; ?></p>
		<?php } ?>

	</div>

</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body" style="overflow-x: auto">
  		<table class="table table-striped table-bordered">
			<thead>
                <tr>
                  <th>#</th>
                  <th>Fixed Asset Code</th>
                  <th>Asset Name</th>
                  <th>Description</th>
                  <th>Remarks/Purpose</th>
                  <th>Status</th>
                  <th>Date Issued</th>
                  <th>Date Returned</th>
                  <th>Received By</th>
                  <th>Condition</th>
                  <th>Manage</th>
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
                  	if($asset->date_issued!=0){
                  		echo "<td>".date('F d, Y',strtotime($asset->date_issued))."</td>";
                  	}else{
                  		echo "<td>-</td>";
                  	}
                  	if($asset->date_returned!=0){
                  	echo "<td>".date('F d, Y',strtotime($asset->date_returned))."</td>";
                  	}else{
                  		echo "<td>-</td>";
                  	}
                  	if($asset->received_by!=''){
                  		echo "<td>".$asset->received_by."</td>";
                  	}else{
                  		echo "<td>-</td>";
                  	}
                  	if($asset->condition_of_returned_item!=''){
                  		echo "<td>".$asset->condition_of_returned_item."</td>";
                  	}else{
                  		echo "<td>-</td>";
                  	}
                  	if($asset->status=='Issued'){
                  		echo "<td><input type='button' value='Return' name='btnReturn' class='btn btn-warning btn-xs btn-block' onclick='javascript: returnAsset(".$AC->id.",".$asset->id.")' />";
                  		echo "<input type='button' value='Loss/Damaged' name='btnLoss' class='btn btn-danger btn-xs btn-block' onclick='javascript: damagedAsset(".$AC->id.",".$asset->id.")' /></td>";
                  	}else{
                  		echo "<td>-</td>";
                  	}
                  	echo "</tr>";
                  	$ctr++;
                }
              ?>
			</tbody>
  		</table>
	</div>
</div>

<script type="text/javascript">

function submitAccountabilityForm(id){
	bootbox.confirm({
		size: "small",
	    title: "Submit Accountability Form",
	    message: "Are you sure you want to submit this Accountability Form?",
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
	    		window.location.href = "<?php echo HTTP_PATH; ?>Asset_Management/accountability_form/submit/" + id;
	    	}

	    }
	});
}

function cancelAccountabilityForm(id){
	bootbox.confirm({
		size: "small",
	    title: "Cancel Accountability Form",
	    message: "Are you sure you want to cancel this Accountability Form? (This cannot be undone.)",
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
	    		window.location.href = "<?php echo HTTP_PATH; ?>Asset_Management/accountability_form/cancel/" + id;
	    	}

	    }
	});
} 

function returnAsset(ac_id,as_id){
	bootbox.prompt({
		size: "large",
		title: "Please provide details/condition of asset to return:",
    	inputType: 'textarea',
    	buttons: {
    		cancel: {
	           label: 'Cancel',
			   className: 'btn-danger'
	        },
	        confirm: {
	           label: 'Submit',
			   className: 'btn-success'
	        }
	    },
	    callback: function (result) {
	    	if(result==null || result==""){
				console.log("Do nothing");
			}else{
	    		$.ajax({
				     type:"POST",
				     url:"<?php echo HTTP_PATH;?>Asset_Management/accountability_form/return/"+as_id,
				     data: {comments:result}
				  });

	    		window.location.href = "<?php echo HTTP_PATH; ?>Asset_Management/accountability_form/view/" + ac_id;
	    	}
	    }
	});
}

function damagedAsset(ac_id,as_id){
	bootbox.prompt({
		size: "large",
		title: "Please provide details how was the asset been loss/damaged:",
    	inputType: 'textarea',
    	buttons: {
    		cancel: {
	           label: 'Cancel',
			   className: 'btn-danger'
	        },
	        confirm: {
	           label: 'Submit',
			   className: 'btn-success'
	        }
	    },
	    callback: function (result) {
	    	if(result==null || result==""){
				console.log("Do nothing");
			}else{
	    		$.ajax({
				     type:"POST",
				     url:"<?php echo HTTP_PATH;?>Asset_Management/accountability_form/loss_damaged/"+as_id,
				     data: {comments:result}
				  });
	    		window.location.href = "<?php echo HTTP_PATH; ?>Asset_Management/accountability_form/view/" + ac_id;
	    	}
	    }
	});
}
</script>