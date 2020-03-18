<h2>Inventory Count Sheet</h2>
<div>
<?php

	if($audit->status=='Draft'){
		if(($this->Abas->checkPermissions("inventory|add_inventory_audit",FALSE))){
			echo '<a href="#" onclick="submitAudit('.$audit->id.');" class="btn btn-success exclude-pageload" target="">Submit</a>';

			echo '<a href="'.HTTP_PATH.'inventory/audit/edit/'.$audit->id.'" class="btn btn-warning exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Edit</a>';

			echo '<a href="#" onclick="cancelAudit('.$audit->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($audit->status=='For Verification'){
		if(($this->Abas->checkPermissions("inventory|verify_inventory_audit",FALSE))){
			echo '<a href="#" onclick="verifyAudit('.$audit->id.');" class="btn btn-success exclude-pageload" target="">Verify</a>';
			echo '<a href="#" onclick="returnAudit('.$audit->id.');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelAudit('.$audit->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($audit->status=='For Note'){
		if(($this->Abas->checkPermissions("inventory|note_inventory_audit",FALSE))){
			echo '<a href="#" onclick="noteAudit('.$audit->id.');" class="btn btn-success exclude-pageload" target="">Note</a>';
			echo '<a href="#" onclick="returnAudit('.$audit->id.');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelAudit('.$audit->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($audit->status=='For Approval'){
		if(($this->Abas->checkPermissions("inventory|approve_inventory_audit",FALSE))){
			echo '<a href="#" onclick="approveAudit('.$audit->id.');" class="btn btn-success exclude-pageload" target="">Approve</a>';
			echo '<a href="#" onclick="returnAudit('.$audit->id.');" class="btn btn-warning exclude-pageload">Return</a>';
			echo '<a href="#" onclick="cancelAudit('.$audit->id.');" class="btn btn-danger exclude-pageload">Cancel</a>';
		}
	}elseif($audit->status=='For Posting'){
		echo '<a href="'.HTTP_PATH.'inventory/audit/print_inventory_count_sheet/'.$audit->id.'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
		echo '<a href="#" onclick="postAudit('.$audit->id.');" class="btn btn-success exclude-pageload" target="">Marked as Posted</a>';
	}elseif($audit->status=='Posted'){
		echo '<a href="'.HTTP_PATH.'inventory/audit/print_inventory_count_sheet/'.$audit->id.'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
	}

		echo '<a href="'.HTTP_PATH.'/inventory/audit/listview" class="btn btn-dark force-pageload">Back</a>';	
?>
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"> 
				Transaction Code No. <?php echo $audit->id;?> | 
				Control No. <?php echo $audit->control_number; ?>
				<span class="pull-right">Status: <?php echo $audit->status;?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $audit->company_name; ?></h3>
		<h4 class="text-center"><?php echo $audit->company_address; ?></h3>
		<h4 class="text-center"><?php echo $audit->company_contact; ?></h4>
		
		<table class="table table-striped table-bordered">
			<tr><td>Type of Inventory:</td><td><?php echo $audit->category_name?></td></tr>
			<tr><td>Audit Date:</td><td><?php echo date("j F Y", strtotime($audit->audit_date))?></td></tr>
			<tr><td>Auditor(s):</td><td><?php echo $audit->audited_by?></td></tr>
			<tr><td>Audit Location:</td><td><?php echo $audit->location?></td></tr>
		</table>

		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($audit->created_on)); ?> by <?php echo $audit->created_by; ?></p>
		<?php 
		if($audit->verified_by!=''){
			echo '<p>Verified on '.date("h:i:s a j F Y", strtotime($audit->verified_on)).' by '.$audit->verified_by.'</p>';
		}
		if($audit->noted_by!=''){
			echo '<p>Noted on '.date("h:i:s a j F Y", strtotime($audit->noted_on)).' by '.$audit->noted_by.'</p>';
		}
		if($audit->approved_by!=''){
			echo '<p>Approved on '.date("h:i:s a j F Y", strtotime($audit->approved_on)).' by '.$audit->approved_by.'</p>';
		}
		if($audit->posted_by!=''){
			echo '<p>Posted on '.date("h:i:s a j F Y", strtotime($audit->posted_on)).' by '.$audit->posted_by.'</p>';
		}
		?>
	</div>

</div>

<div class="panel panel-primary">
	<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="">

	<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
		<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
		    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Count Sheet</h4>
		</a>
		 <div id="collapse1" class="panel-collapse" role="tabpanel" aria-labelledby="heading1">
			<div class="panel-body" style="overflow: auto">
				
				<?php if(($this->Abas->checkPermissions("inventory|count_inventory_audit",FALSE)) && $audit->status=='Draft'){ ?>
					<a href="<?php echo HTTP_PATH.'inventory/audit/print_manual_count_sheet/'.$audit->id?>" class="btn btn-info" target="_blank">Print Manual Count Sheet</a>
					
					<!--<a href="<?php //echo HTTP_PATH.'inventory/audit/add_item_count/'.$audit->id?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" data-keyboard="false">Add Actual Item Count</a>-->
				<?php } ?>
				<?php
					$attributes = array('id'=>'inventory_audit_details_form','role'=>'form');
					echo form_open_multipart(HTTP_PATH.'inventory/audit/update_item_count/'.$audit->id,$attributes);
					echo $this->Mmm->createCSRF();
				?>
					<table class="table table-striped table-bordered">
						<tr>
							<th rowspan='2'>#</th>
							<th rowspan='2'>Item Code</th>
							<th rowspan='2'>Item Description</th>
							<th rowspan='2'>Storage Location (Rack/Shelf No.)</th>
							<th rowspan='2'>Unit of Measure</th>
							<th colspan="4" style='text-align:center'>Quantity</th>
							<th rowspan='2'>Unit Cost</th>
							<th rowspan='2'>Total Cost</th>
							<th rowspan='2'>Remarks</th>
						</tr>
						<tr>
							<th rowspan='1'>Per Count</th>
							<th rowspan='1'>Per Books</th>
							<th rowspan='1'>Difference</th>
							<th rowspan='1'>Final</th>
						</tr>
						<?php 
							//if($audit->status=='Draft'){ 
							//	echo '<th></th>';
							//}
						?>
						<?php 
							$total_cost =0;
							$ctr = 1;
							foreach($audit_details as $row){
								if($audit->status=='Draft'){
									echo "<tr>";
									echo "<td><input type='hidden' name='inventory_qty_id[]' id='inventory_qty_id[]' value='".$row['inventory_quantity_id']."'>".$ctr."</td>";
									echo "<td><input type='hidden' name='item_id[]' id='item_id[]' value='".$row['item_id']."'>".$row['item_code']."</td>";
									echo "<td>".$row['item_description']."</td>";
									echo "<td><input type='text' class='form-control' name='shelf_number[]' id='shelf_number[]' value='".$row['shelf_number']."'></td>";
									echo "<td><input type='hidden' name='unit[]' id='unit[]' value='".$row['unit']."'>".$row['unit']."</td>";
									echo "<td><input type='number' class='form-control' name='counted_qty[]' id='counted_qty[]' value='".$row['counted_qty']."'></td>";
									echo "<td><input type='hidden' name='current_qty[]' id='current_qty[]' value='".$row['current_qty']."'>".$row['current_qty']."</td>";
									echo "<td>".($row['counted_qty'] - $row['current_qty'])."</td>";
									echo "<td>".$row['counted_qty']."</td>";
									echo "<td><input type='hidden' name='unit_price[]' id='unit_price[]' value='".$row['unit_price']."'>".number_format($row['unit_price'],2,'.',',')."</td>";
									$unit_cost = $row['unit_price']*$row['counted_qty'];
									echo "<td>".number_format($unit_cost,2,'.',',')."</td>";
									echo "<td><input type='text' class='form-control' name='remarks[]' id='remarks[]' value='".$row['remarks']."'></td>";
									//if($audit->status=='Draft'){
									//	echo "<td><a href='#' id='remove_row' onclick='removeItemCount(".$row['id'].");' class='btn btn-danger btn-xs exclude-pageload'>×</a></td>";
									//}
									echo "</tr>";
									$total_cost = $total_cost + $unit_cost;
									$ctr++;
								}else{
									echo "<tr>";
									echo "<td>".$ctr."</td>";
									echo "<td>".$row['item_code']."</td>";
									echo "<td>".$row['item_description']."</td>";
									echo "<td>".$row['shelf_number']."</td>";
									echo "<td>".$row['unit']."</td>";
									echo "<td>".$row['counted_qty']."</td>";
									echo "<td>".$row['current_qty']."</td>";
									echo "<td>".($row['counted_qty'] - $row['current_qty'])."</td>";
									echo "<td>".$row['counted_qty']."</td>";
									echo "<td>".number_format($row['unit_price'],2,'.',',')."</td>";
									$unit_cost = $row['unit_price']*$row['counted_qty'];
									echo "<td>".number_format($unit_cost,2,'.',',')."</td>";
									echo "<td>".$row['remarks']."</td>";
									echo "</tr>";
									$total_cost = $total_cost + $unit_cost;
									$ctr++;
								}
							}
						?>
				</form>
						<tr>
							<td colspan='10' style='text-align:right;font-weight: bold'>Total (PHP)</td><td><?php echo number_format($total_cost,2,'.',',')?></td>
							<?php if($audit->status=='Draft'){ ?>
								<td><a href="#" onclick="updateAuditDetails();" class="btn btn-success exclude-pageload pull-right">Update Count Sheet</a></td>
							<?php } ?>
						</tr>
					</table>
			</div>
		</div>
	</div>

	<div class="accordion" id="accordion2" role="tablist2" aria-multiselectable="true">
		<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion2" href="#collapse2" aria-expanded="true" aria-controls="collapse1">
		    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Cut-off Documents</h4>
		</a>
		 <div id="collapse2" class="panel-collapse" role="tabpanel" aria-labelledby="heading1">
			<div class="panel-body" style="overflow: auto">
				<table class="table table-striped table-bordered">
					<th>Document Name</th>
					<th>Last Used (Control No.)</th>
					<th>Date Last Used</th>
					<th>First Unused (Control No.)</th>
				<?php 
					foreach($audit_cutoff_documents as $row){
						echo "<tr>";
						echo "<td>".$row->document_name."</td>";
						echo "<td>".$row->last_used."</td>";
						echo "<td>".$row->date_last_used."</td>";
						echo "<td>".$row->first_unused."</td>";
						echo "</tr>";
					}
				?>
				</table>
			</div>
		</div>
	</div>

		
	</div>
</div>



<script>

function cancelAudit(id){

    	bootbox.confirm({
       					size: "medium",
       					 title: "Cancel Inventory Audit",
					    message: "Are you sure you want to cancel this Inventory Audit - Count Sheet? (This cannot be undone)",
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
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/audit/cancel/" + id;
					    	}
				
					    }
					});
    }
function returnAudit(id){

    	bootbox.confirm({
       					size: "medium",
       					 title: "Return Inventory Audit",
					    message: "Are you sure you want to return this Inventory Audit - Count Sheet to 'Draft' status?",
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
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/audit/return/" + id;
					    	}
				
					    }
					});
    }
function submitAudit(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Submit Inventory Audit",
					    message: "Are you sure you want to submit this Inventory Audit - Count Sheet?",
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
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/audit/submit/" + id;
					    	}
				
					    }
					});
    }
function verifyAudit(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Verify Inventory Audit",
					    message: "Are you sure you want to verify this Inventory Audit - Count Sheet?",
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
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/audit/verify/" + id;
					    	}
				
					    }
					});
    }
 function noteAudit(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Note Inventory Audit",
					    message: "Are you sure you want to note this Inventory Audit - Count Sheet?",
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
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/audit/note/" + id;
					    	}
				
					    }
					});
    }
 function approveAudit(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Approve Inventory Audit",
					    message: "Are you sure you want to approve this  Inventory Audit - Count Sheet?",
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
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/audit/approve/"+id;
					    	}
				
					    }
					});
    }
 function postAudit(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Post Inventory Audit",
					    message: "Are you sure you want to post this  Inventory Audit - Count Sheet? This will also update the quantity recorded in the Inventory List.",
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
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/audit/post/"+id;
					    	}
				
					    }
					});
    }
 function removeItemCount(id){

    	bootbox.confirm({
       					size: "small",
					    title: "Remove Item Count",
					    message: "Are you sure you want to remove this item count?",
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
					    		window.location.href = "<?php echo HTTP_PATH;?>inventory/audit/remove_item_count/" + id;
					    	}
				
					    }
					});
    }
 function updateAuditDetails(){
	bootbox.confirm({
   					size: "small",
				    title: "Update Inventory Audit",
				    message: "Are you sure you want to update these Inventory Count details?",
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
				    		document.forms['inventory_audit_details_form'].submit();
				    	}
			
				    }
				});
}
</script>