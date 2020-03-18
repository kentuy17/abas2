<h2>Disposal Slip</h2>
<div>
<?php
	if($disposal->status=='Draft'){
		if($this->Abas->checkPermissions("asset_management|add_disposal_slip",FALSE)){
			echo '<a href="#" onclick="submitDisposalSlip('.$disposal->id.');" class="btn btn-success exclude-pageload" target="">Submit</a>';
			
			echo '<a href="'.HTTP_PATH.'Asset_Management/disposal_slip/edit/'.$disposal->id.'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog">Edit</a>';

			echo '<a href="#" onclick="cancelDisposalSlip('.$disposal->id.');" class="btn btn-danger exclude-pageload" target="">Cancel</a>';

		}
	}elseif($disposal->status=='Approved'){
		if($this->Abas->checkPermissions("asset_management|add_disposal_slip",FALSE)){
			echo '<a href="'.HTTP_PATH.'Asset_Management/disposal_slip/print/'.$disposal->id.'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
		}
	}

		echo '<a href="'.HTTP_PATH.'Asset_Management/disposal_slip/listview" class="btn btn-dark force-pageload">Back</a>';
?>
	
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code No. <?php echo $disposal->id;?> |
				Control No. <?php echo $disposal->control_number; ?>
				<span class="pull-right">Status: <?php echo $disposal->status;?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $disposal->company_name; ?></h3>
		<h4 class="text-center"><?php echo $disposal->company_address; ?></h3>
		<h4 class="text-center"><?php echo $disposal->company_contact; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Requested By:</th>
				<td><?php echo $disposal->requested_by; ?></td>
			</tr>
			<tr>
				<th>Date Requested:</th>
				<td><?php echo  date("F d, Y", strtotime($disposal->requested_on)); ?></td>
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
				<th>Manner of Disposal:</th>
				<td>
					<?php 
						if($disposal->manner_of_disposal=="Others"){
							echo $disposal->manner_of_disposal. " (".$disposal->others.")"; 
						}else{
							echo $disposal->manner_of_disposal; 
						}
					?>
				</td>
			</tr>
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($disposal->created_on)); ?> by <?php echo $disposal->created_by; ?></p>
		<?php if($disposal->verified_by!=0){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($disposal->verified_on)); ?> by <?php echo $disposal->verified_by; ?></p>
		<?php } ?>
		<?php if($disposal->approved_by!=0){ ?>
		<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($disposal->approved_on)); ?> by <?php echo $disposal->approved_by; ?></p>
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
	</div>
</div>

<script type="text/javascript">

function submitDisposalSlip(id){
	bootbox.confirm({
		size: "small",
	    title: "Submit Asset Disposal Slip",
	    message: "Are you sure you want to submit this Asset Disposal Slip?",
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
	    		window.location.href = "<?php echo HTTP_PATH; ?>Asset_Management/disposal_slip/submit/" + id;
	    	}

	    }
	});
}

function cancelDisposalSlip(id){
	bootbox.confirm({
		size: "small",
	    title: "Cancel Asset Disposal Slip",
	    message: "Are you sure you want to cancel this Asset Disposal Slip? (This cannot be undone.)",
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
	    		window.location.href = "<?php echo HTTP_PATH; ?>Asset_Management/disposal_slip/cancel/" + id;
	    	}

	    }
	});
} 
</script>