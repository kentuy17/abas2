<h2>Notice of Discrepancy</h2>
<div>
<?php

	if($nod->status=='Draft'){
		if(($this->Abas->checkPermissions("inventory|add_notice_of_discrepancy",FALSE))){
			echo '<a href="#" onclick="submitNOD('.$nod->id.');" class="btn btn-success exclude-pageload" target="">Submit</a>';

			echo '<a href="'.HTTP_PATH.'inventory/notice_of_discrepancy/edit/'.$nod->id.'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Edit</a>';

			echo '<a href="#" onclick="cancelNOD('.$nod->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($nod->status=='For Verification'){
		if(($this->Abas->checkPermissions("inventory|verify_notice_of_discrepancy",FALSE))){
			echo '<a href="#" onclick="verifyNOD('.$nod->id.');" class="btn btn-success exclude-pageload" target="">Verify</a>';
			
			echo '<a href="#" onclick="cancelNOD('.$nod->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($nod->status=='For Level-1 Approval'){
		if(($this->Abas->checkPermissions("inventory|approve_notice_of_discrepancy_level_1",FALSE))){
			echo '<a href="#" onclick="approveNOD('.$nod->id.',1);" class="btn btn-success exclude-pageload" target="">Approve</a>';
		
			echo '<a href="#" onclick="cancelNOD('.$nod->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($nod->status=='For Level-2 Approval'){
		if(($this->Abas->checkPermissions("inventory|approve_notice_of_discrepancy_level_2",FALSE))){
			echo '<a href="#" onclick="approveNOD('.$nod->id.',2);" class="btn btn-success exclude-pageload" target="">Approve</a>';
			
			echo '<a href="#" onclick="cancelNOD('.$nod->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($nod->status=='For Level-3 Approval'){
		if(($this->Abas->checkPermissions("inventory|approve_notice_of_discrepancy_level_3",FALSE))){
			echo '<a href="#" onclick="approveNOD('.$nod->id.',3);" class="btn btn-success exclude-pageload" target="">Approve</a>';
			
			echo '<a href="#" onclick="cancelNOD('.$nod->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($nod->status=='Approved'){
		echo '<a href="'.HTTP_PATH.'inventory/notice_of_discrepancy/print/'.$nod->id.'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';

		echo '<a href="#" onclick="cancelNOD('.$nod->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
	}
		echo '<a href="'.HTTP_PATH.'/inventory/notice_of_discrepancy/listview" class="btn btn-dark force-pageload">Back</a>';	
?>
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"> 
				Transaction Code No. <?php echo $nod->id;?> | 
				Control No. <?php echo $nod->control_number; ?>
				<span class="pull-right">Status: <?php echo $nod->status;?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $nod->company_name; ?></h3>
		<h4 class="text-center"><?php echo $nod->company_address; ?></h3>
		<h4 class="text-center"><?php echo $nod->company_contact; ?></h4>
		
		<table class="table table-striped table-bordered">
			<tr><td>PO Details:</td><td><?php echo "PO #".$nod->po[0]['control_number']." (TS Code No. ".$nod->po[0]['id'].")"?></td></tr>
			<tr><td>Supplier:</td><td><?php echo $nod->supplier_name?></td></tr>
			<!--<tr><td>Reason of Discrepancy:</td><td><?php //echo $nod->reason_of_discrepancy?></td></tr>-->
			<tr><td>Date of Delivery:</td><td><?php echo date('j F Y',strtotime($nod->date_of_delivery))?></td></tr>
			<tr><td>Delivery Receipt No.:</td><td><?php echo $nod->delivery_receipt_number?></td></tr>
			<tr><td>Vehicle Plate No.:</td><td><?php echo $nod->vehicle_plate_number?></td></tr>
			<tr><td>Name of Driver:</td><td><?php echo $nod->name_of_driver?></td></tr>
			<tr><td>Other/Remarks:</td><td><?php echo $nod->other_remarks?></td></tr>
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($nod->created_on)); ?> by <?php echo $nod->full_name; ?></p>
		
		<?php if($nod->verified_by!=0){ ?>
		<p>Verified on <?php echo date("h:i:s a j F Y", strtotime($nod->verified_on)); ?> by <?php echo $nod->verified_by_full_name; ?></p>
		<?php } ?>

		<?php if($nod->level1_approved_by!=0){ ?>
		<p>Level 1 Approved By on <?php echo date("h:i:s a j F Y", strtotime($nod->level1_approved_on)); ?> by <?php echo $nod->approved_by_level1_full_name; ?></p>
		<?php } ?>

		<?php if($nod->level2_approved_by!=0){ ?>
		<p>Level 2 Approved By on <?php echo date("h:i:s a j F Y", strtotime($nod->level2_approved_on)); ?> by <?php echo $nod->approved_by_level2_full_name; ?></p>
		<?php } ?>

		<?php if($nod->level3_approved_by!=0){ ?>
		<p>Level 3 Approved By on <?php echo date("h:i:s a j F Y", strtotime($nod->level3_approved_on)); ?> by <?php echo $nod->approved_by_level3_full_name; ?></p>
		<?php } ?>

	</div>

</div>

<div class="panel panel-primary">
	<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body">
		<div  style="overflow-x: auto">
            <table class="table table-striped table-bordered">
            	<thead>
            		<th>Item Code</th>
            		<th>Item Description</th>
            		<th>UoM</th>
            		<th>Qty per PO</th>
            		<th>Qty per DR</th>
            		<th>Qty Received</th>
            		<th>Reason of Discrepancy</th>
            	</thead>
            	<tbody>
            		<?php
            			foreach($nod_details as $row){
            				echo "<tr>";
            				echo "<td>".$row['item_code']."</td>";
        					echo "<td>". $row['item_description']."</td>";
        					if($row['packaging']==''){
        						$um = $row['unit'];
        					}else{
        						$um = $row['packaging'];
        					}
        					echo "<td>".$um."</td>";
        					echo "<td>".$row['quantity_po']."</td>";
        					echo "<td>".$row['quantity_dr']."</td>";
        					echo "<td>".$row['quantity_received']."</td>";
        					echo "<td>".$row['remarks']."</td>";
            				echo "</tr>";
            			}
            		?>
            	</tbody>
            </table>
	     </div>
	</div>
</div>
<script>

function cancelNOD(id){

    	bootbox.confirm({
       					size: "medium",
       					 title: "Cancel Notice of discrepancy",
					    message: "Are you sure you want to cancel this Notice of Discrepancy? (This cannot be undone)",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {

					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/notice_of_discrepancy/cancel/" + id;
					    	}
				
					    }
					});
    }

function submitNOD(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Submit Notice of discrepancy",
					    message: "Are you sure you want to submit this Notice of Discrepancy?",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/notice_of_discrepancy/submit/" + id;
					    	}
				
					    }
					});
    }
function verifyNOD(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Verify Notice of discrepancy",
					    message: "Are you sure you want to verify this Notice of Discrepancy?",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/notice_of_discrepancy/verify/" + id;
					    	}
				
					    }
					});
    }
 function approveNOD(id,level){

    	bootbox.confirm({
       					size: "small",
					    title: "Approve Notice of discrepancy",
					    message: "Are you sure you want to approve this Notice of Discrepancy?",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/notice_of_discrepancy/approve_" +level+ "/" + id;
					    	}
				
					    }
					});
    }
 
</script>