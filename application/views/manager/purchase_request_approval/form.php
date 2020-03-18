<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title">Approve Material/Service Request<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2></div>
</div>

	<div class="panel-body">
		<div style="overflow-x: auto">
		<table class="table table-striped table-bordered ">
			<tr>
				<td colspan='2'><h4>Summary</h4></td>
			</tr>
			<tr>
				<td><b>Company</b></td>
				<td><?php echo $company_name; ?></td>
			</tr>
			<tr>
				<td><b>Project Reference No.</b></td>
				<td>
					<?php 
						if($request['reference_number']!=''){
							echo $request['reference_number'];
						}else{
							echo 'N/A';
						}			
					?>		
				</td>
			</tr>
			<tr>
				<td><b>Priority</b></td>
				<td><?php echo $request['priority']?></td>
			</tr>
			<tr>
				<td><b>Requested Date</b></td>
				<td><?php echo date("j F Y",strtotime($request['tdate']))?></td>
			</tr>
			<tr>
				<td><b>Requested By</b></td>
				<td><?php echo $request['requisitioner']?></td>
			</tr>
			<tr>
				<td><b>Vessel/Office</b></td>
				<td><?php echo $vessel_name?></td>
			</tr>
			<?php 
				if($request['truck_id']!=0){
					$truck = $this->Abas->getTruck($request['truck_id']);
					echo "<tr>
							<td><b>Truck</b></td>
							<td>".$truck[0]['plate_number']."</td>
						  </tr>";
				}			
			?>		
			<tr>
				<td><b>Department</b></td>
				<td><?php echo $department_name?></td>
			</tr>
			<tr>
				<td><b>Purpose</b></td>
				<td><?php echo $request['remark']?></td>
			</tr>
			<tr>
				<td><b>Authorized Approver</b></td>
				<td><?php echo $approver_name?></td>
			</tr>
		</table>
		<form name="request_approval_form" id="request_approval_form" method="post" action="<?php echo HTTP_PATH.'manager/purchase_requests/save'; ?>">
	    <?php echo $this->Mmm->createCSRF() ?>
			<table class="table table-striped table-bordered">
				<tr>
					<td colspan='10'><h4>Details</h4></td>
				</tr>
				<th>#</th>
				<th>Item Code</th>
				<th>Description</th>
				<th>Remarks</th>
				<th>Quantity</th>
				<th>Unit/Packaging</th>
				<th>Status</th>
				<th>Assigned To (Purchaser)</th>
				<th><center>Approve?</center></th>
				<th><center>Cancel?</center></th>
				<?php 
					if($request_details){
						$ctr = 1;
						foreach($request_details as $detail){
							$item = $this->Inventory_model->getItem($detail['item_id']);
							if($detail['assigned_to']!=""){
								$purchaser = $this->Abas->getUser($detail['assigned_to']);
								$purchaser = $purchaser['full_name'];
							}else{
								$purchaser = "Any";
							}
							echo "<tr>";
								$checked = '';
								$checked_cancelled = '';
								if(strtolower($detail['status']) != 'for request approval'){
									$checked = 'checked="checked" disabled';
									$checked_cancelled = 'disabled';
								}
								echo "<td>".$ctr."</td>";
								echo "<td>".$item[0]['item_code']."</td>";
								echo "<td>".$item[0]['item_name'].", ".$item[0]['brand']." ".$item[0]['particular']."</td>";
								echo "<td>".$detail['remark']."</td>";
								echo "<td>".number_format($detail['quantity'],2,'.',',')."</td>";
								if($detail['packaging']==''){
									echo "<td>".strtolower($item[0]['unit'])."</td>";
								}else{
									echo "<td>".strtolower($detail['packaging'])."</td>";
								}
								echo "<td>".ucwords($detail['status'])."</td>";
								echo "<td>".$purchaser."</td>";
								echo "<td><center><input type='checkbox' class='checkme' name='selected_id[]' id='selected_".$ctr."' onclick='javascript: checkApproveItem();' value='".$detail['id']."' ".$checked."></center></td>";
								echo "<td><center><input type='checkbox' class='checkmex' name='cancelled_id[]' id='cancelled_".$ctr."'onclick='javascript: checkCancelItem();' value='".$detail['id']."' ".$checked_cancelled."></center></td>";
							echo "</tr>";
							$ctr++;
						}
					}
				?>
	  		</table>
	  			<div>
		  			<input type="hidden" name="approved_items" id="approved_items" />
		            <input type="hidden" name="cancelled_items" id="cancelled_items" />
		            <input type="hidden" name="request_id" id="request_id" value="<?php echo $request['id'] ?>" />    
		            <input type="hidden" name="number_of_items" id="number_of_items" value="<?php echo count($request_details) ?>" />
	            </div>
	    	</form> 
  		</div>
		<hr>
  		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<span class='pull-right'>
				<?php 
					if($request['status']=='For request approval' || $request['status']=='For Request Approval'){
						$allowed = FALSE;
						if($this->Abas->checkPermissions("manager|requests",false)){
							$allowed = TRUE;
						}
						if($allowed==TRUE){
							 echo '<button type="button" class="btn btn-success btn-m" onclick="javascript: submitPR('.$request['id'].')">Submit</button>';
						}
					}
					 echo '<button type="button" class="btn btn-danger btn-m" data-dismiss="modal">Close</button>';
				?>
			</span>
		</div>
	</div>
	

<script type="text/javascript">

	function submitPR(id){

		var approved_items = $('#approved_items').val();
		var cancelled_items = $('#cancelled_items').val();

		if(approved_items=="" && cancelled_items==""){
			toastr["error"]("Please select at least one item to be approved.","ABAS Says");
		}else{
			bootbox.confirm({
		   					size: "small",
		   					title: "Approve/Cancel Purchase Request",
						    message: "Are you sure you want to submit this Purchase Request for Canvassing?",
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
						    		document.forms['request_approval_form'].submit();
						    	}
						    }
						});
		}
	}

	function checkApproveItem(){
		var checkboxValues = [];
        var sels = [];
        //$('input[name=selected_id]:checked').map(function() {
        $('.checkme').change(function() {
                var sels = $(this).val();
                var curr_id = $(this).attr("id").match(/\d+$/);
                $("#cancelled_"+curr_id).prop('checked', false); 
                checkboxValues.push(sels);
                document.getElementById('approved_items').value = checkboxValues;                         
        });
	}

	function checkCancelItem(){
		var checkboxValuesX = [];
        var selx = [];
        //$('input[name=cancelled_id]:checked').map(function() {
        $('.checkmex').change(function() {
                var selx = $(this).val();
                var curr_idx = $(this).attr("id").match(/\d+$/);
                $("#selected_"+curr_idx).prop('checked', false); 
                checkboxValuesX.push(selx);
                document.getElementById('cancelled_items').value = checkboxValuesX;                         
        });
	}


</script>
