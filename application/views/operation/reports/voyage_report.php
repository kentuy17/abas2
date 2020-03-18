<h2>Voyage Report</h2>

<h6>Vessel Name: <?php echo $vessel->name."<br>" ?></h6>
<h6>From period of <?php echo date('F j, Y',strtotime($date_from)). " to " . date('F j, Y',strtotime($date_to)); ?></h6>

<div>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="##summary-tab">Summary</a></li>
		<li><a data-toggle="tab" href="##details-tab">Details</a></li>
	</ul>

	<div class="tab-content">
		<div id="details-tab" class="tab-pane fade in">
			<table data-toggle="table" id="op-table" class="table table-bordered table-striped table-hover"  data-search="true" data-show-columns="true" data-filter-control="true" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
				<thead>
					<th>#</th>
					<th data-visible="false">Service Order TSCode No.</th>
					<th>Company</th>
					<th>Type of Service</th>
					<th>Contract Ref. No.</th>
					<th>Contract Amount</th>
					<th>Trip Ticket No.</th>
					<th>Bill of Lading No.</th>
					<th>Cargo Description</th>
					<th>Qty per BOL</th>
					<th>Wt per BOL</th>
					<th>Shipper</th>
					<th>Consignee</th>
					<th>Voyage No.</th>
					<th>Port Origin</th>
					<th>Port Destination</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Duration</th>
					<th>Client</th>
					<th data-visible="true">SOA TSCode No.</th>
					<th data-visible="true">Payment TSCode No.</th>
					<th>Billed Amount</th>
					<th>Collected Amount</th>
				</thead>
				<tbody>
					<?php
					$num_of_voyage = 1;
					$grand_total_soa_amount = 0;
					$grand_total_payment_amount = 0;
					$loading_dates = array();
					$loading_dates_label = array();
					$monthly_trips = array();
					$num_trips = array();
					$days = array();
					$cargo_description = '';
					$cargo_volume= array();
					$max_longest_trip= 0;
					$previous_diff = 0;
					foreach ($result as $row) {

						$SO = $this->Operation_model->getServiceOrder($row->service_order_id);
						
						if($SO){
							$loading_date = date('M Y',strtotime($row->loading_arrival));
							array_push($loading_dates,$loading_date);

							$loading_date_label = date('M d, Y',strtotime($row->loading_arrival));
							array_push($loading_dates_label,$loading_date_label);

							$contract = $this->Operation_model->getContract($SO->service_contract_id);
							echo "<tr>";
								$company = $this->Abas->getCompany($row->company_id);
								$vessel = $this->Abas->getVessel($row->vessel_id);
								echo "<td>".$num_of_voyage."</td>";
								echo "<td>".$row->service_order_id."</td>";
								echo "<td>".$company->name."</td>";
								echo "<td>".$row->type_of_service."</td>";
								echo "<td>".$contract['reference_no']."</td>";
								echo "<td>".number_format($contract['amount'],2,'.',',')."</td>";
								echo "<td>".$row->trip_ticket_number."</td>";
								echo "<td>".$row->bill_of_lading_number."</td>";
								echo "<td>".$SO->details->cargo_description."</td>";
								echo "<td>".number_format($row->quantity_per_bill_of_lading,2,'.',',')."</td>";
								echo "<td>".number_format($row->weight_per_bill_of_lading,2,'.',',')."</td>";
								echo "<td>".$row->shipper."</td>";
								echo "<td>".$row->consignee."</td>";
								echo "<td>".$row->voyage_number."</td>";
								echo "<td>".$row->port_of_origin."</td>";
								echo "<td>".$row->port_of_destination."</td>";
								echo "<td>".$row->loading_arrival."</td>";
								echo "<td>".$row->unloading_departure."</td>";
								$earlier = new DateTime($row->loading_arrival);
								$later = new DateTime($row->unloading_departure);
								$diff = $later->diff($earlier)->format("%a");
								echo "<td>".$diff."  days</td>";

								array_push($days,$diff);
								$cargo_description = $SO->details->cargo_description;
								array_push($cargo_volume,array("'".$cargo_description."'"=>$row->weight_per_bill_of_lading));

								if($diff>$previous_diff){
									$previous_diff = $diff;
									$max_longest_trip = $loading_date_label." (Contract Ref. No. ".$contract['reference_no'].")";
								}else{
									$min_shortest_trip = $loading_date_label." (Contract Ref. No. ".$contract['reference_no'].")";
								}

								$soas = $this->Operation_model->getSOAbyOutturn($row->out_turn_summary_id);
								$soa_id = "";
								$collection_id = "";
								$total_soa_amount = 0;
								$total_payment_amount = 0;
								if(isset($soas) && count($soas)>0){
									foreach($soas as $soa){
										$soa_id .= $soa->id."<br>";
											$soa_amount = $this->Billing_model->getSOAAmount($soa->type,$soa->id);
											$total_soa_amount = $total_soa_amount + $soa_amount['grandtotal_tax'];

											$client = $this->Abas->getClient($soa->client_id);
											$collections = $this->Collection_model->getPaymentsBySOA($soa->id);
											foreach($collections as $collection){
												$total_payment_amount = $total_payment_amount + $collection->net_amount;
												$collection_id .= $collection->id."<br>";
											}
									}
								}else{
									$soas = $this->Operation_model->getSOAbyOutturn($row->out_turn_summary_id,$contract['id']);
									foreach($soas as $soa){
										$soa_id .= $soa->id."<br>";
										$is_outturn = $this->Abas->like_match("%".$row->remarks."%",$soa->reference_number);
										if($is_outturn!== false){
											$soa_amount = $this->Billing_model->getSOAAmount($soa->type,$soa->id);
											$total_soa_amount = $total_soa_amount + $soa_amount['grandtotal_tax'];

											$client = $this->Abas->getClient($soa->client_id);
											$collections = $this->Collection_model->getPaymentsBySOA($soa->id);
											foreach($collections as $collection){
												$total_payment_amount = $total_payment_amount + $collection->net_amount;
												$collection_id .= $collection->id."<br>";
											}
										}
									}
									
								}	
								echo "<td>".$client['company']."</td>";
								echo "<td>".$soa_id."</td>";
								echo "<td>".$collection_id."</td>";
								echo "<td>".number_format($total_soa_amount,2,'.',',')."</td>";
								echo "<td>".number_format($total_payment_amount,2,'.',',')."</td>";
								//echo "<td><a class='btn btn-primary btn-xs' href='".HTTP_PATH."operation/out_turn_summary/view/".$row->out_turn_summary_id."' target='_blank'>View</a></td>";

							echo "</tr>";
							$grand_total_soa_amount = $grand_total_soa_amount + $total_soa_amount;
							$grand_total_payment_amount = $grand_total_payment_amount + $total_payment_amount;
							$num_of_voyage++;
						}
					}


					$monthly_trips = array_unique($loading_dates);
					$num_trips = array_count_values($loading_dates);

					?>


				</tbody>
			</table>
		</div>
		<div id="summary-tab" class="tab-pane fade in active">
			<br>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-sort-amount-desc"></i>
					</div>
					<div class="count"><?php echo number_format($num_of_voyage-1,0,'',',');?></div>
					<h3>Total No. of Trips</h3>
					<p>Voyage and Time-Charter</p>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-sort-amount-desc"></i>
					</div>
					<div class="count"><?php echo number_format($grand_total_soa_amount,2,'.',',');?></div>
					<h3>Total Billed Amount</h3>
					<p>Statement of Accounts</p>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-sort-amount-desc"></i>
					</div>
					<div class="count"><?php echo number_format($grand_total_payment_amount,2,'.',',');?></div>
					<h3>Total Collected Amount</h3>
					<p>Received Payments</p>
				</div>
			</div>
			<div id="line_chart" style="position: center; overflow-x: auto; width: 850px; height: 400px;"><br></div>
			<hr>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<table class="table table-bordered table-striped table-hover" >
					<tr><td><b>Month</b></td><td><b>No. of Trips</b></td>
					</tr>
					<?php 
						$sum = 0;
						$average = 0;
						$array_count = array();
						if(isset($monthly_trips)){
							for($ctr=0;$ctr<=999;$ctr++){
								if(isset($monthly_trips[$ctr]) && $monthly_trips[$ctr] !=''){
									echo "<tr><td>".$monthly_trips[$ctr]."</td><td>".$num_trips[$monthly_trips[$ctr]]."</td></tr>";
									array_push($array_count,$num_trips[$monthly_trips[$ctr]]);
								}
							}
							$sum = array_sum($num_trips);
							$average = $sum/count($array_count);
						}else{
							echo "<tr><td colspan='2'><center>No matching records found</center></td></tr>";
						}
					?>
				</table>

				<table class="table table-bordered table-striped table-hover" >
					<tr><td><b>Cargo</b></td><td><b>Volume</b></td>
					</tr>
					<?php 
						$volume = array();
						foreach ($cargo_volume as $key => $sub_array) {
						    foreach ($sub_array as $sub_key => $value) {
						        //If array key doesn't exists then create and initize first before we add a value.
						        //Without this we will have an Undefined index error.
						        if( ! array_key_exists($sub_key, $volume)) $volume[$sub_key] = 0;
						        //Add Value
						        $volume[$sub_key]+=$value;
						    }
						}
						if(isset($volume)){
							foreach($volume as $key=>$cargo){
								echo "<tr><td>".str_replace('\'', '',$key)."</td><td>".number_format($cargo,2,'.',',')."</td></tr>";
							}
						}else{
							echo "<tr><td colspan='2'><center>No matching records found</center></td></tr>";
						}
					?>
				</table>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-sort-amount-desc"></i>
					</div>
					<div class="count"><?php 
						if(!is_nan($average)){
							echo number_format($average,2,'.','');
						}else{
							echo "(none)";
						}
						?></div>
					<h3>Average Trip per Month</h3>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-sort-amount-desc"></i>
					</div>
					<div class="count">
						<?php
							if(count($days)>0){
								if(max($days)>0){
									echo max($days)." days";
								}else{
									echo "(none)";
								}
							}
						?>
					</div>
					<h3>Longest Trip</h3>
					<p>Date: <?php echo $max_longest_trip;?></p>
					<hr>
					<div class="count">
						<?php
							if(count($days)>0){
								if(min($days)>0){
									echo min($days)." days";
								}else{
									echo "(none)";
								}
							}
						?>
					</div>
					<h3>Shortest Trip</h3>
					<p>Date: <?php echo $min_shortest_trip;?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	// based on prepared DOM, initialize echarts instance
    var lineChart = echarts.init(document.getElementById('line_chart'));

    var loading_dates = <?php echo json_encode($loading_dates_label)?>;
    var days = <?php echo json_encode($days)?>;


    // specify chart configuration item and data
   option_line = {
   		title: {
        	left: 'center',
        	text: '',
    	},
	    tooltip: {
	        trigger: 'axis',
	        position: function (pt) {
	            return [pt[0], '10%'];
	        }
	    },
	    xAxis: {
	        type: 'category',
	        boundaryGap: false,
	        data: loading_dates
	    },
	    yAxis: {
	        type: 'value',
	        name: 'No. of Days'
	    },
	    dataZoom: [{
	        type: 'inside',
	        start: 0,
	        end: 100
	    }, {
	        start: 0,
	        end: 10,
	        handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
	        handleSize: '80%',
	        handleStyle: {
	            color: '#fff',
	            shadowBlur: 3,
	            shadowColor: 'rgba(0, 0, 0, 0.6)',
	            shadowOffsetX: 2,
	            shadowOffsetY: 2
	        }
	    }],
	    series: [{
	        data: days,
	        type: 'line',
	        areaStyle: {}
	    }]
	};
    // use configuration item and data specified to show chart
    lineChart.setOption(option_line);

</script>