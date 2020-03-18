<?php
	$query1	=	$this->db->query("SELECT id FROM inventory_po WHERE request_id=".$request['id']);
	if($query1){
		$po = $query1->row();
		if(isset($po->id)){
			$query2 =	$this->db->query("SELECT * FROM inventory_deliveries WHERE po_no=".$po->id);
			if($query2){
				$overall_status = "Delivered";
			}
		}else{
			$overall_status = $request['status'];
		}
	}

	$query3	=	$this->db->query("SELECT * FROM inventory_job_orders WHERE request_id=".$request['id']);
	if($query3){
		$jo = $query3->row();
		if(isset($jo->id)){
			$query4 =	$this->db->query("SELECT * FROM ac_request_payment WHERE reference_table='inventory_job_orders' AND reference_id=".$jo->id);
			if($query4){
				$overall_status_jo = "Delivered";
			}
		}else{
			$overall_status_jo = $request['status'];
		}
	}
?>
<h2>Materials/Services Request</h2>
<div>
<?php //if($request['status']=='For Canvassing'){
?>
	<a href="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/print/'.$request['id']?>" class="btn btn-info exclude-pageload" target="_blank">Print</a>
<?php //} ?>
<a href="<?php echo HTTP_PATH.'Corporate_Services/purchase_requests/listview'?>" class="btn btn-dark exclude-pageload">Back</a>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"> 
			Transaction Code No. <?php echo $request['id'];?> | 
			Control No. <?php echo $request['control_number']; ?>
			<!--<span class="pull-right">Overall Status: <?php //echo $overall_status;?>-->
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<h3 class="text-center"><?php echo $request['company']->name; ?></h3>
		<h4 class="text-center"><?php echo $request['company']->address; ?></h3>
		<h4 class="text-center"><?php echo $request['company']->telephone_no; ?></h4>
		<table class="table table-striped table-bordered">
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
				<td><b>Requisitioner</b></td>
				<td><?php echo $request['requisitioner']?></td>
			</tr>
			<tr>
				<td><b>Vessel/Office</b></td>
				<td><?php echo $request['vessel_name']?></td>
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
				<td><?php echo $request['department_name']?></td>
			</tr>
			<tr>
				<td><b>Priority</b></td>
				<td><?php echo $request['priority']?></td>
			</tr>
			<tr>
				<td><b>Approving Body</b></td>
				<td><?php 
					if(isset($request['approved_by_name'])){
						echo $request['approved_by_name'];
					}else{
						echo "Any Manager";
					}?></td>
			</tr>
			<tr>
				<td><b>Remark/Purpose</b></td>
				<td><?php echo $request['remark']?></td>
			</tr>
		</table>
		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($request['added_on'])); ?> by <?php echo $request['requested_by_name']; ?></p>
		<?php if(isset($request['details'][0]['request_approved_on'])){?>
			<p>Request Approved On <?php echo date("h:i:s a j F Y", strtotime($request['details'][0]['request_approved_on'])); ?> by <?php echo $request['details'][0]['request_approved_by']['full_name']; ?></p>
		<?php } ?>
	</div>
</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body">

		<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true"><!--start accordion-->
			
			<div class="panel panel-info">
			 	<a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="1">
			    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Materials</h4>
			    </a>
	            <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
	              	<div class="panel-body">
	              		<table class="table table-striped table-bordered">
	              			<thead>
	              				<th>#</th>
	              				<th>Item Code</th>
	              				<th>Description</th>
	              				<th>Quantity</th>
	              				<th>Unit/Packaging</th>
	              				<th>Remark</th>
	              				<th>Assigned To</th>
	              				<th>Approved By</th>
	              				<th>Status</th>
	              			</thead>
	              			<tbody>
							<?php 
								if($request['details']){
									$ctr = 1;
									foreach($request['details'] as $detail){
										$item = $this->Abas->getItemCategory($detail['item_details']['category']);
										if($item->category!='Service' && $detail['supplier_id']==0){
											echo "<tr>";
												echo "<td>".$ctr."</td>";
												echo "<td>".$detail['item_details']['item_code']."</td>";
												echo "<td>".$detail['item_details']['item_name'].",".$detail['item_details']['brand']." ".$detail['item_details']['particular']."</td>";
												echo "<td>".$detail['quantity']."</td>";
												if($detail['packaging']==''){
													echo "<td>".$detail['item_details']['unit']."</td>";
												}else{
													echo "<td>".$detail['packaging']."</td>";
												}
												echo "<td>".$detail['remark']."</td>";
												echo "<td>".$detail['request_assigned_to']['full_name']."</td>";
												echo "<td>".$detail['request_approved_by']['full_name']."</td>";
												if($overall_status=="Delivered" && $detail['status']!="Cancelled"){
													echo "<td>Delivered</td>";
												}else{
													echo "<td>".$detail['status']."</td>";
												}
											echo "</tr>";
											$ctr++;
										}
									}
									if($ctr==1){
										echo "<tr>";
											echo "<td colspan='9'><center>Not Applicable</center></td>";
										echo "</tr>";
									}
								}else{
									echo "<tr>";
										echo "<td colspan='9'><center>Not Applicable</center></td>";
									echo "</tr>";
								}
							?>
							</tbody>
	              		</table>
	              	</div>
	            </div>
			</div>


			<div class="panel panel-info">
				 <a class="panel-heading" role="tab" id="heading2" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="2">
			    <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Services</h4>
			    </a>
			    <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
	              	<div class="panel-body">
	              		<table class="table table-striped table-bordered">
							<thead>
								<th>#</th>
	              				<th>Job Description</th>
	              				<th>Quantity</th>
	              				<th>Remark</th>
	              				<th>Assigned To</th>
	              				<th>Approved By</th>
	              				<th>Status</th>
	              			</thead>
	              			<tbody>
							<?php
								if($request['details']){
									$ctr = 1;
									foreach($request['details'] as $detail){
										$item = $this->Abas->getItemCategory($detail['item_details']['category']);
										if($item->category=='Service' && $detail['supplier_id']==0){
											echo "<tr>";
												echo "<td>".$ctr."</td>";
												echo "<td>".$detail['item_details']['item_name'].",".$detail['item_details']['brand']." ".$detail['item_details']['particular']."</td>";
												echo "<td>".$detail['quantity']." ".$detail['item_details']['unit']."</td>";
												echo "<td>".$detail['remark']."</td>";
												echo "<td>".$detail['request_assigned_to']['full_name']."</td>";
												echo "<td>".$detail['request_approved_by']['full_name']."</td>";
												if($overall_status_jo=="Delivered" && $detail['status']!="Cancelled"){
													echo "<td>Delivered</td>";
												}else{
													echo "<td>".$detail['status']."</td>";
												}
											echo "</tr>";
											$ctr++;
										}
									}
									if($ctr==1){
										echo "<tr>";
											echo "<td colspan='7'><center>Not Applicable</center></td>";
										echo "</tr>";
									}
								}else{
									echo "<tr>";
										echo "<td colspan='7'><center>Not Applicable</center></td>";
									echo "</tr>";
								}
							?>
							</tbody>
	              		</table>
	              	</div>
	            </div>
			</div>
				
        </div><!--end accordion-->

	</div>
</div>