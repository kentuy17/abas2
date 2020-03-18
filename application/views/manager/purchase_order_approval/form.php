<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title">Approve Purchase Order<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2></div>
</div>

	<div class="panel-body">
		<div style="overflow-x: auto">
		<table class="table table-striped table-bordered ">
			<tr>
				<td colspan='2'><h4>Summary</h4></td>
			</tr>
			<tr>
				<td><b>Company</b></td>
				<td><?php echo $purchase_order['company_name']; ?></td>
			</tr>
			<tr>
				<td><b>Priority</b></td>
				<td><?php echo $request['priority']?></td>
			</tr>
			<tr>
				<td><b>Supplier</b></td>
				<td><?php echo $purchase_order['supplier_name']?></td>
			</tr>
			<tr>
				<td><b>Payment Term</b></td>
				<td><?php echo $purchase_order['payment_terms']?></td>
			</tr>
			<tr>
				<td><b>Delivery On</b></td>
				<td><?php echo date("j F Y",strtotime($purchase_order['deliver_on']))?></td>
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
				<td><?php echo $purchase_order['vessel_name']?></td>
			</tr>
			<tr>
				<td><b>Department</b></td>
				<td><?php echo $purchase_order['department_name']?></td>
			</tr>
			<tr>
				<td><b>Purpose</b></td>
				<td><?php echo $purchase_order['remark']?></td>
			</tr>
			<tr>
				<td><b>PR Approved By</b></td>
				<td><?php echo $request_details[0]['request_approved_by']['full_name']?></td>
			</tr>
			<tr>
				<td><b>PR Approved On</b></td>
				<td><?php echo date("j F Y",strtotime($request_details[0]['request_approved_on']))?></td>
			</tr>
			<tr>
				<td><b>Canvass Approved By</b></td>
				<td><?php echo $request_details[0]['canvass_approved_by']['full_name']?></td>
			</tr>
			<tr>
				<td><b>Canvass Approved On</b></td>
				<td><?php echo date("j F Y",strtotime($request_details[0]['canvass_approved_on']))?></td>
			</tr>
		</table>
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
				<th>Unit Price</th>
				<th>Amount</th>
				<th>View (6 months ago)</th>
				<?php 
				$total_amount = 0;
					if($purchase_order['details']){
						$ctr = 1;
						foreach($purchase_order['details']as $detail){
							$item = $this->Inventory_model->getItem($detail['item_id']);
							echo "<tr>";
								echo "<td>".$ctr."</td>";
								echo "<td>".$item[0]['item_code']."</td>";
								echo "<td>".$item[0]['item_name'].", ".$item[0]['brand']." ".$item[0]['particular']."</td>";
								echo "<td>".$detail['remarks']."</td>";
								echo "<td>".number_format($detail['quantity'],2,'.',',')."</td>";
								if($detail['packaging']==''){
									echo "<td>".strtolower($item[0]['unit'])."</td>";
								}else{
									echo "<td>".strtolower($detail['packaging'])."</td>";
								}
								echo "<td>".number_format($detail['unit_price'],2,'.',',')."</td>";
								echo "<td>".number_format(($detail['unit_price']*$detail['quantity']),2,'.',',')."</td>";
								echo "<td><a class='btn btn-xs btn-primary exclude-pageload' onclick='javascript:showPurchases(".$ctr.");''><span class='glyphicon glyphicon-chevron-down'></span> Purchase History</a></td>";
								echo "<tr id='item".$ctr."' class='hide'><td colspan='99'>";
								echo "<table class='table table-bordered table-striped table-hover'>";
									echo "<tr>";
										echo "<th>PO No.</th>";
										echo "<th>Supplier</th>";
										echo "<th>Vessel/Office</th>";
										echo "<th>Purchased On</th>";
										echo "<th>Remark</th>";
										echo "<th>Amount</th>";
										echo "<th>Manage</th>";
									echo "</tr>";
									//get purchases of this item for the past 6 months
									$date_last_8_weeks = date('Y-m-d', strtotime('-6 month'));
									$date_today = date('Y-m-d');
									$purchase_history = $this->Purchasing_model->getPurchaseOrderHistory($item[0]['id'],$purchase_order['company_id'],$date_last_8_weeks,$date_today);
									if($purchase_history){
										$has_po = 0;
										foreach($purchase_history as $history) {
											$request = $this->Purchasing_model->getRequest($history->request_id);
											if($request['vessel_name']== $purchase_order['vessel_name']){
												$has_po++;
												echo "<tr>";
													echo "<td>".$history->control_number." (TSCode No.".$history->id.")</td>";
													echo "<td>".$history->supplier_name."</td>";
													echo "<td>".$request['vessel_name']."</td>";
													echo "<td>".date('j F Y',strtotime($history->added_on))."</td>";
													echo "<td>".$history->remark."</td>";
													echo "<td>".number_format($history->amount,2,'.',',')."</td>";
													echo "<td><a class='btn btn-info btn-xs btn-block force-pageload' target='_blank' href='".HTTP_PATH."purchasing/requisition/view/".$request['id']."'>View</a></td>";
												echo "</tr>";
											}
										}
										if($has_po == 0){
												echo "<tr><td colspan='7'><center>No record found</center></td></tr>";
										}
									}else{
										echo "<tr><td colspan='7'><center>No record found</center></td></tr>";
									}
								    echo "</table>";
								echo "</tr>";
							echo "</tr>";
							$total_amount = $total_amount + ($detail['unit_price']*$detail['quantity']);
							$ctr++;
						}
						//echo "<tr>";
						//	echo "<td colspan='7' style='text-align:right'><b>Total Amount:</b></td>";
						//	echo "<td>".number_format($total_amount,2,'.',',')."</td>";
						//echo "</tr>";

						$supplierdata		=	$this->Abas->getSupplier($purchase_order['supplier_id']);
						$vat				=	0;
						$vatable_purchases	=	0;
						$gross_purchases	=	0;
						$vat				=	0;
						$etax				=	0;
						$approver			=	"";
						$vatable_purchases	=	$total_amount;
						$grand_total		=	$total_amount;
						if($supplierdata['issues_reciepts']==1) {
							$gross_purchases	=	$total_amount-$purchase_order['discount'];
							if(strtolower($supplierdata['vat_computation'])=='vatable') {
								$vat				=	(($total_amount-$purchase_order['discount'])-(($total_amount-$purchase_order['discount'])/1.12));
								$vatable_purchases	=	($total_amount-$purchase_order['discount'])-$vat;
							}
							$etax				=	($vatable_purchases*($purchase_order['extended_tax']/100));
							$etax_percentage	=	0;
							if($purchase_order['extended_tax']>0) {
								$etax_percentage=	$purchase_order['extended_tax'];
								$grand_total	=	$gross_purchases-$purchase_order['extended_tax'];
							}
						}
						$grand_total		=	$gross_purchases-$etax-$purchase_order['discount'];

						echo '<tr><td class="text-right" colspan=7>Gross Amount</td><td>'.number_format($gross_purchases,2).'</td></tr>';
						echo '<tr><td class="text-right" colspan=7>VATable Purchases</td><td>'.number_format($vatable_purchases,2).'</td></tr>';
						echo '<tr><td class="text-right" colspan=7>12% VAT</td><td>'.number_format($vat,2).'</td></tr>';
						echo '<tr><td class="text-right" colspan=7>Withholding Tax - Expanded</td><td>('.number_format($etax,2).')</td></tr>';
						echo '<tr><td class="text-right" colspan=7><b>Total Amount Payable</b></td><td>'. number_format($grand_total,2).'</td></tr>';

						if($purchase_order['file_path']!=''){
							echo "<tr>";
								echo "<td colspan='7' style='text-align:right'><b>Addtl. Attachment:</b></td>";
								echo "<td><a href='".HTTP_PATH."../assets/uploads/purchasing/purchase_order/attachments/".$purchase_order['file_path']."' class='btn btn-xs btn-default' target='_blank'>Download and View File</a></td>";
							echo "</tr>";
						}
					}
				?>
	  		</table>
  		</div>
		<hr>
  		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<span class='pull-right'>
				<?php 
					if($purchase_order['status']=='For Purchase Order Approval' || $purchase_order['status']=='For purchase order approval'){
						$allowed = FALSE;
						if($this->Abas->checkPermissions("manager|purchase_orders",false)){
							if($total_amount<=50000 && $this->Abas->checkPermissions("purchasing|approve_low_amount_po",false)){
								$allowed = TRUE;
							}
							if($total_amount>50000 && $total_amount<=150000 && $this->Abas->checkPermissions("purchasing|approve_medium_amount_po",false)){
								$allowed = TRUE;
							}
							if($total_amount>150000 && $this->Abas->checkPermissions("purchasing|approve_high_amount_po",false)){
								$allowed = TRUE;
							}
						}
						if($allowed==TRUE){
							 echo '<button type="button" class="btn btn-success btn-m" onclick="javascript: submitPO('.$purchase_order['id'].')">Approve</button>';
							  echo '<button type="button" class="btn btn-warning btn-m" onclick="javascript: cancelPO('.$purchase_order['id'].')">Disapprove</button>';
						}
					}
					 echo '<button type="button" class="btn btn-danger btn-m" data-dismiss="modal">Close</button>';
				?>
			</span>
		</div>
	</div>
	

<script type="text/javascript">

	function showPurchases(itemid) {
		$("#item"+itemid).toggleClass('hide');
	}

	function submitPO(id){

			bootbox.confirm({
		   					size: "small",
		   					title: "Approve Purchase Order",
						    message: "Are you sure you want to approve this Purchase Order?",
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
						    		window.location.href = "<?php echo HTTP_PATH; ?>manager/purchase_orders/save/" + id;
						    	}
						    }
						});

	}

	function cancelPO(id){

			bootbox.confirm({
		   					size: "small",
		   					title: "Cancel Purchase Order",
						    message: "Are you sure you want to disapprove/cancel this Purchase Order?",
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
						    		window.location.href = "<?php echo HTTP_PATH; ?>manager/purchase_orders/cancel/" + id;
						    	}
						    }
						});

	}

</script>