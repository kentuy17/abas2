<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title">Approve Canvass<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2></div>
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
				<tr>
					<td><b>Department</b></td>
					<td><?php echo $department_name?></td>
				</tr>
				<tr>
					<td><b>Purpose</b></td>
					<td><?php echo $request['remark']?></td>
				</tr>
				<tr>
				<td><b>PR Approved By</b></td>
					<td><?php echo $rd[0]['request_approved_by']['full_name']?></td>
				</tr>
				<tr>
					<td><b>PR Approved On</b></td>
					<td><?php echo date("j F Y",strtotime($rd[0]['request_approved_on']))?></td>
				</tr>
			</table>
			<form name="canvass_approval_form" id="canvass_approval_form" method="post" action="<?php echo HTTP_PATH.'manager/canvass/save'; ?>">
		    	<?php echo $this->Mmm->createCSRF() ?>
				<ul class="list-unstyled timeline widget">
	               <?php
						$ctr = 0;
						if(isset($request_details)){
							foreach($request_details as $canvass){

								$sqlAppend = " AND item_id =".$canvass['item_id'];
								$request_item_detail = $this->Purchasing_model->getRequestDetails($canvass['request_id'],$sqlAppend);
							
								$item = $this->Inventory_model->getItem($canvass['item_id']);
						  
			                 	 echo '<li>
			                    		<div class="well">
			                        		<div class="block_content">
			                        			<strong>
			                                		Item: '.$item[0]['item_name'].','.$item[0]['brand']." ".$item[0]['particular'].'&nbsp;&nbsp;&nbsp;
			                                		Qty: '.$request_item_detail[0]['quantity'];
			                     if($canvass['packaging']==''){
			                    	 echo '&nbsp'.strtolower($item[0]['unit']);
			                     }else{
			                     	 echo '&nbsp'.strtolower($canvass['packaging']);
			                     }
			                     echo '</strong><div class="byline">
			                                <table id="datatable-responsive" style="margin-top:10px"  class="table table-striped table-bordered table-hover" cellspacing="0" width="120%">
			                                  <thead>
			                                    <tr>
			                                      <th width="5%" align="center">Select?</th>
			                                      <th width="40%">Supplier</th>
			                                      <th width="15%">Unit Price</th>
			                                      <th width="15%">Total</th>
			                                    </tr>
			                                  </thead>
			                                  <tbody>';

												//$itemCanvass = $this->Admin_model->getSupplierCanvass($canvass['item_id'],$canvass['request_id']);
			                                  $sqlAppend = " AND item_id = ".$canvass['item_id']." AND supplier_id <> 0 AND status !='Cancelled'";
			                                  $itemCanvass = $this->Purchasing_model->getRequestDetails($canvass['request_id'],$sqlAppend);
												if(!empty($itemCanvass)) {
													foreach($itemCanvass as $can){
														$sup = $this->Abas->getSupplier($can['supplier_id']);
														echo '<tr>
																<td align="center"><input type="radio" class="selects" name="item_'.$ctr.'" value="'.$can['id'].'"></td>
														  		<td>'.$sup['name'].'<br>Remarks: '.$can['remark'].'</td>
														  		<td>'.number_format($can['unit_price'],2).'</td>
														  		<td>'.number_format($can['unit_price'] * $request_item_detail[0]['quantity'],2).'</td>
															  </tr>';
													}
												}else{
													echo '<tr><td colspan="4">No canvass found</td></tr>';
												}

		                                    echo '</tbody>
		                                    </table>
		                                  </div>';
				                    echo '</div>
				                 	 </li>';
								$ctr++; 
							}
						}
				   ?>
	            </ul>
		  			<div>
			  			<input type="hidden" name="item_count" id="item_count" value="<?php echo $ctr ?>" />
	                    <input type="hidden" name="request_id" id="request_id" value="<?php echo $canvass['request_id'] ?>" />
		            </div>
		    </form> 
  		</div>
		<br>
  		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<span class='pull-right'>
				<?php 
					if($canvass['status']=='For canvass approval' || $canvass['status']=='For Canvass Approval'){
						$allowed = FALSE;
						if($this->Abas->checkPermissions("manager|canvass",false)){
							$allowed = TRUE;
						}
						if($allowed==TRUE){
							 echo '<button type="button" class="btn btn-success btn-m" onclick="javascript: submitCanvass('.$request['id'].')">Submit</button>';
						}
					}
					echo '<button type="button" class="btn btn-danger btn-m" data-dismiss="modal">Close</button>';
				?>
			</span>
		</div>
	</div>
	

<script type="text/javascript">

	function submitCanvass(id){

		var num_radio_check = $(':radio[class="selects"]:checked').length;
		var item_count = $('#item_count').val();

		if(num_radio_check != item_count){
			toastr["error"]("Please make sure to select a canvass per item.","ABAS Says");
		}else{

			bootbox.confirm({
		   					size: "small",
		   					title: "Approve Canvass",
						    message: "Are you sure you want to submit this Canvass for Ordering?",
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
						    		document.forms['canvass_approval_form'].submit();
						    	}
						    }
						});
		}
	
	}

	
</script>
