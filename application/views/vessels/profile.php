<div class="panel panel-primary">

	<div class="panel-heading" style="min-height">
		View Vessel Profile
		 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	</div>
</div>

  	<div class="panel-body">
		
    	<ul class="nav nav-tabs" role="tablist">
			<li class="active" role="presentation"><a data-toggle="tab" href="#vinfo-tab" role="tab">General Info</a></li>
			<li role="presentation"><a data-toggle="tab" href="#vspec-tab" role="tab">Specifications</a></li>
			<li role="presentation"><a data-toggle="tab" href="#vcert-tab" role="tab">Certificates</a></li>
			<li role="presentation"><a data-toggle="tab" href="#vcrew-tab" role="tab">Crew</a></li>
			<li role="presentation"><a data-toggle="tab" href="#vpurc-tab" role="tab">Purchase Orders</a></li>
			<li role="presentation"><a data-toggle="tab" href="#voprexp-tab" role="tab">Operational Expenses</a></li>
			<!--<li><a data-toggle="tab" href="#vact-tab">Activity</a></li>
			<li><a data-toggle="tab" href="#vfuel-tab">Fuel Report</a></li>
			<li><a data-toggle="tab" href="#vvoy-tab">Voyages</a></li>-->
		</ul>

        <div class="tab-content">
        	<div role="tabpanel" class="tab-pane fade active in" id="vinfo-tab" aria-labelledby="vinfo-tab">
        		<div class="x_panel" style="overflow-x: scroll; max-height: 500px;overflow-y: scroll;">
		        	<table id="info-table" class="table table-bordered table-striped table-hover">
		        		<tr>
		        			<td style="width:400px"><img src="<?php echo LINK.'assets/uploads/operations/vessels/'.$vessel->photo_path;?>" width="400" height="400"><h2><center><?php echo $vessel->name;?></center></h2></td>
		        			<td>
		        				<?php
		        					echo "<table class='table table-bordered table-striped table-hover'>";
			        					echo "<tr><td colspan='2'><h2>".$company->name."</h2></td></tr>";
			        					echo "<tr><td><b>Former name: </b></td><td>".$vessel->ex_name."</td></tr>";
			        					echo "<tr><td><b>Price sold: </b></td><td>".$vessel->price_sold."</td></tr>";
			        					echo "<tr><td><b>Price paid: </b></td><td>".$vessel->price_paid."</td></tr>";
			        					echo "<tr><td><b>Year Built: </b></td><td>".$vessel->year_built."</td></tr>";
			        					echo "<tr><td><b>Builder: </b></td><td>".$vessel->builder."</td></tr>";
			        					echo "<tr><td><b>Place built: </b></td><td>".$vessel->place_built."</td></tr>";
			        					echo "<tr><td><b>Year last Dry-docked: </b></td><td>".$vessel->year_last_drydocked."</td></tr>";
			        					echo "<tr><td><b>Place last Dry-docked: </b></td><td>".$vessel->place_last_drydocked."</td></tr>";
			        					echo "<tr><td><b>Bank Account No.: </b></td><td>".$vessel->bank_account_num."</td></tr>";
			        					echo "<tr><td><b>Bank Account Name: </b></td><td>".$vessel->bank_account_name."</td></tr>";
			        					echo "<tr><td><b>Status: </b></td><td>".$vessel->status."</td></tr>";
		        					echo "</table>";
		        				?>
		        			</td>
		        		</tr>
		        	</table>
	      	    </div>
        	</div>

        	<div role="tabpanel" class="tab-pane fade" id="vspec-tab" aria-labelledby="vspec-tab">
    			<div class="x_panel" style="overflow-x: scroll; max-height: 500px;overflow-y: scroll;">
	        		<table id="spec-table" class="table table-bordered table-striped table-hover">
	        			<?php 
	        				echo "<tr><td><b>LOA</b></td><td>".$vessel->length_loa."</td></tr>";
	        				echo "<tr><td><b>LR</b></td><td>".$vessel->length_lr."</td></tr>";

	        				echo "<tr><td><b>LBP</b></td><td>".$vessel->length_lbp."</td></tr>";
	        				echo "<tr><td><b>Breadth</b></td><td>".$vessel->breadth."</td></tr>";
	        				echo "<tr><td><b>Depth</b></td><td>".$vessel->depth."</td></tr>";
	        				echo "<tr><td><b>Draft</b></td><td>".$vessel->draft."</td></tr>";
	        				echo "<tr><td><b>Jap DWT</b></td><td>".$vessel->jap_dwt."</td></tr>";
	        				echo "<tr><td><b>Bale Capacity</b></td><td>".$vessel->bale_capacity."</td></tr>";
	        				echo "<tr><td><b>Grain Capacity</b></td><td>".$vessel->grain_capacity."</td></tr>";
	        				echo "<tr><td><b>Hatch Size</b></td><td>".$vessel->hatch_size."</td></tr>";
	        				echo "<tr><td><b>Hatch Type</b></td><td>".$vessel->hatch_type."</td></tr>";
	        				echo "<tr><td><b>Phil DWT</b></td><td>".$vessel->phil_dwt."</td></tr>";
	        				echo "<tr><td><b>Gross Tonnage</b></td><td>".$vessel->gross_tonnage."</td></tr>";
	        				echo "<tr><td><b>Net Tonnage</b></td><td>".$vessel->net_tonnage."</td></tr>";
	        				echo "<tr><td><b>Main Engine</b></td><td>".$vessel->main_engine."</td></tr>";
	        				echo "<tr><td><b>Main Engine Rating</b></td><td>".$vessel->main_engine_rating."</td></tr>";
	        				echo "<tr><td><b>Main Engine Actual Rating</b></td><td>".$vessel->main_engine_actual_rating."</td></tr>";
	        				echo "<tr><td><b>Model Serial No.</b></td><td>".$vessel->model_serial_no."</td></tr>";
	        				echo "<tr><td><b>Est. Fuel Consumption</b></td><td>".$vessel->estimated_fuel_consumption."</td></tr>";
	        				echo "<tr><td><b>Bow Thrusters</b></td><td>".$vessel->bow_thrusters."</td></tr>";
	        				echo "<tr><td><b>Propeller</b></td><td>".$vessel->propeller."</td></tr>";
	        				echo "<tr><td><b>Call Sign</b></td><td>".$vessel->call_sign."</td></tr>";
	        				echo "<tr><td><b>IMO No.</b></td><td>".$vessel->imo_no."</td></tr>";
	        				echo "<tr><td><b>Monthly Amortization</b></td><td>".$vessel->monthly_amortization_no_of_months."</td></tr>";
	        				echo "<tr><td><b>TC Proeject Monthly Income</b></td><td>".$vessel->tc_proj_mo_income."</td></tr>";
	        				echo "<tr><td><b>HM Agreed Value</b></td><td>".$vessel->hm_agreed_value."</td></tr>";
	        				echo "<tr><td><b>Maiden Voyage</b></td><td>".$vessel->maiden_voyage."</td></tr>";
	        				echo "<tr><td><b>Replacement Cost</b></td><td>".$vessel->replacement_cost_new."</td></tr>";
	        				echo "<tr><td><b>Sound Value</b></td><td>".$vessel->sound_value."</td></tr>";
	        				echo "<tr><td><b>Market Value</b></td><td>".$vessel->market_value."</td></tr>";
	        			?>
		        	</table>
        		</div>
        	</div>

        	<div role="tabpanel" class="tab-pane fade" id="vcert-tab" aria-labelledby="vcert-tab">
    			<div class="x_panel" style="overflow-x: scroll; max-height: 500px;overflow-y: scroll;">
	        		 <table class="table table-bordered table-striped table-hover" data-url="" data-show-columns="true">
		        		<thead>
							<tr>
								<th>Doc Type</th>
								<th>Certificate Date</th>
								<th>Expiration Date</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$date_now=date('Y-m-d');

								foreach($certificates as $row){
									echo "<tr>";
										echo "<td>".$row->type."</td>";
										
										echo "<td>". date("j F Y", strtotime($row->cert_date))."</td>";

										if($date_now<$row->expiration_date){
											$status = "Active";
										}else{
											$status = "Expired";
										}

										echo "<td>". date("j F Y", strtotime($row->expiration_date))."</td>";

										echo "<td>".$status."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
		        	</table>
        		</div>
        	</div>

        	<div role="tabpanel" class="tab-pane fade" id="vcrew-tab" aria-labelledby="vcrew-tab">
    			<div class="x_panel" style="overflow-x: scroll; max-height: 500px;overflow-y: scroll;">
	        		 <table class="table table-bordered table-striped table-hover" data-url="" data-show-columns="true">
		        		<thead>
							<tr>
								<th>#</th>
								<th>Employee ID</th>
								<th>Name</th>
								<th>Mobile No.</th>
								<th>Emercency Contact No.</th>
								<th>Position</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$ctr=1;
								foreach($crew as $row){
									if($row->status!='Retired' && $row->status!='Resigned' && $row->status!='Inactive' && $row->status!='AWOL'){
										echo "<tr>";
											echo "<td>".$ctr."</td>";
											echo "<td>".$row->employee_id."</td>";
											echo "<td>". $row->last_name.", ". $row->first_name . " ".$row->middle_name."</td>";
											echo "<td>".$row->mobile."</td>";
											echo "<td>".$row->emergency_contact_num." ".$row->emergency_contact_person."</td>";
											echo "<td>". $row->position ."</td>";
											echo "<td>". $row->status ."</td>";
										echo "</tr>";
										$ctr++;
									}
								}
							?>
						</tbody>
		        	</table>
        		</div>
        	</div>

        	<div role="tabpanel" class="tab-pane fade" id="vpurc-tab" aria-labelledby="vpurc-tab">
    			<div class="x_panel" style="overflow-x: scroll; max-height: 500px;overflow-y: scroll;">
	        		 <table class="table table-bordered table-striped table-hover" data-url="" data-show-columns="true">
		        		<thead>
							<tr>
								<th>Purchase Order No.</th>
								<th>PO Created On</th>
								<th>Supplier</th>
								<th>Requested By</th>
								<th>Total Amount</th>
								<th>Status</th>
								<th>Manage</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$total_purchase_amount = 0;
								$total_served =0;
								$total_unserved =0;
								$total_po =0;
								$itemctr = 1;
								foreach($purchase_orders as $row){
									echo "<tr>";
										echo "<td>".$row->control_number."</td>";
										echo "<td>". date("j F Y", strtotime($row->added_on))."</td>";
										echo "<td>".$row->supplier_name."</td>";
										echo "<td>".$row->requisitioner."</td>";
										echo "<td>".number_format($row->amount,2,'.',',')."</td>";
										echo "<td>".$row->delivery_status."</td>";
										echo "<td><a class='btn btn-xs btn-primary exclude-pageload' onclick='javascript:showPO(".$itemctr.");''>View</a></td>";
									echo "</tr>";
									$total_purchase_amount  = $total_purchase_amount + $row->amount;
									if($row->delivery_status=='Served'){
										$total_served = $total_served+1;
									}else{
										$total_unserved = $total_unserved+1;
									}
									echo "<tr id='item".$itemctr."' class='hide'><td colspan='99'>";
										echo "<table class='table table-bordered table-striped table-hover'>";
											echo "<tr><th>Item</th><th>Unit</th><th>Price</th><th>Quantity</th><th>Total Price</th></tr>";
												$po_details = $row->po_details;
												foreach($po_details as $detail) {
													$item = $this->Inventory_model->getItem($detail['item_id']);
													echo	"<tr>";
														echo	"<td>".$item[0]['description'].",".$item[0]['particular']."</td>";
														echo	"<td>".$detail['unit']."</td>";
														echo	"<td>".number_format($detail['unit_price'],2)."</td>";
														echo	"<td>".$detail['quantity']."</td>";
														echo	"<td>".number_format($detail['unit_price']*$detail['quantity'],2)."</td>";
													echo	"</tr>";
												}
										echo	"</table>";
									echo	"</td></tr>";
									$itemctr++;
								}
								echo "<tr><td colspan='4' style='text-align:right'><b>Total Purchase Amount:</b></td><td>".number_format($total_purchase_amount,2,'.',',')."</td></tr>";
								echo "<tr><td colspan='4' style='text-align:right'><b>Total No. of PO:</b></td><td>".number_format(count($purchase_orders),0,'.',',')."</td></tr>";
								echo "<tr><td colspan='4' style='text-align:right'><b>Total Served PO:</b></td><td>".number_format($total_served,0,'.',',')."</td></tr>";
								echo "<tr><td colspan='4' style='text-align:right'><b>Total Unserved PO:</b></td><td>".number_format($total_unserved,0,'.',',')."</td></tr>";
							?>
						</tbody>
		        	</table>
        		</div>
        	</div>

        	<div role="tabpanel" class="tab-pane fade" id="voprexp-tab" aria-labelledby="voprexp-tab">
    			<div class="x_panel" style="overflow-x: scroll; max-height: 500px;overflow-y: scroll;">
	        		 <table class="table table-bordered table-striped table-hover" data-url="" data-show-columns="true">
		        		<thead>
							<tr>
								<th>Request Date</th>
								<th>Payee</th>
								<th>Purpose</th>
								<th>Reference No.</th>
								<th>Amount</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$total_amount = 0;
								foreach($operational_expenses as $row){
										echo "<tr>";
											echo "<td>". date("j F Y", strtotime($row->request_date))."</td>";
											echo "<td>". $row->payee ."</td>";
											echo "<td>". $row->purpose ."</td>";
											echo "<td>". $row->reference_id ."</td>";
											echo "<td>". number_format($row->amount,2,".",",") ."</td>";
											echo "<td>". $row->status ."</td>";
										echo "</tr>";
										$total_amount = $total_amount+$row->amount;
								}
								echo "<tr><td colspan='4' style='text-align:right'<b>Total Amount:</b></td><td>". number_format($total_amount,2,".",",") ."</td></tr>";
							?>
						</tbody>
		        	</table>
        		</div>
        	</div>

		</div>

	</div>
<script>
function showPO(itemid) {
	$("#item"+itemid).toggleClass('hide');
}
</script>