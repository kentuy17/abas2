<?php

	//echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';
	$id = '';
	$wsr_no = '';
	$wsr_date = '';
	$waybill_no = '';
	$wsi_no = '';
	$reference_no = '';
	$from_location = '';
	$from_location_name = '';

	$to_location = '';
	$to_location_name = '';

	$region = '';
	$region_name = '';

	$transaction_type = '';
	$transaction_type_name = '';

	$truck_plate_no = '';
	$bags = '';
	$gross_weight = 0;
	$wsi_gross_weight = 0;
	$net_weight = 0;

	$variety = '';
	$age = '';
	$stock_condition = '';


	if(isset($wsr)){

		$id = $wsr->id;
		$wsr_no = $wsr->wsr_no;
		$wsr_date = $wsr->wsr_date;
		$waybill_no = $wsr->waybill_no;
		$wsi_no = $wsr->wsi_no;
		$reference_no = $wsr->reference_no;

		$from_location = $wsr->from_location;
		$f = $this->Operation_model->getTruckingLocation($wsr->from_location);
		$from_location_name = $f->name;

		$to_location = $wsr->to_location;
		$l = $this->Operation_model->getTruckingLocation($wsr->to_location);
		$to_location_name = $l->name;


		$region = $wsr->region;
		$r =  $this->Abas->getRegion($wsr->region);

		$region_name = $r[0]->name;

		$transaction_type = $wsr->transaction_type;
		$t =  $this->Operation_model->getTransactionType($wsr->transaction_type);
		$transaction_type_name = $t->transaction_name;


		$truck_plate_no = $wsr->truck_plate_no;
		$bags = $wsr->bags;
		$gross_weight = $wsr->gross_weight;
		$wsi_gross_weight = $wsr->wsi_gross_weight;
		$net_weight = $wsr->net_weight;

		$variety = $wsr->variety;
		$age = $wsr->age;
		$stock_condition = $wsr->stock_condition;


	}

	//for tab
	$r_tab_active = '';
	$r_in_active = '';
	$c_tab_active = '';
	$c_in_active = '';
	$v_tab_active = '';
	$v_in_active = '';

	if($tab == 'voucher_deliveries'){
		$c_tab_active = 'class="active"';
		$c_in_active = 'in active';
		}elseif($tab == 'services'){
		$v_tab_active = 'class="active"';
		$v_in_active = 'in active';
		}elseif($tab == 'cash_advance'){
		$r_tab_active = 'class="active"';
		$r_in_active = 'in active';
		}else{
		$r_tab_active = 'class="active"';
		$r_in_active = 'in active';
	}


?>

<?php $this->load->view('includes_header'); ?>

<script>
	$(document).ready(function() {
		$('#datatable-responsive').DataTable();
		$('#datatable-responsive1').DataTable();
		$('#datatable-responsive2').DataTable();
		$('#datatable-responsive3').DataTable();
	});

</script>
</head>

<body class="nav-md">

    <div class="container body">
		<div class="main_container">

			<div class="col-md-3 left_col">

				<div class="left_col scroll-view">
					<div class="navbar nav_title" style="border: 0;">

						<a href="<?php echo LINK ?>" class="site_title"><img src="<?php echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="45px" style="margin-top:-5px" align="absmiddle" class="img-circle"> <span>ABAS v1</span></a>
					</div>

					<div class="clearfix"></div>


					<!-- sidebar menu -->
					<?php $this->load->view('accounting/side_menu_accounting'); ?>
					<!-- /sidebar menu -->

					<!-- /menu footer buttons -->
					<?php $this->load->view('operation/footer_button'); ?>

					<!-- /menu footer buttons -->
				</div>

			</div>

			<!-- top navigation -->
			<div class="top_nav">


				<div class="nav_menu"  style="float:left; margin-left:0px; margin-top:0px">
					<!-- toggle side menu -->
					<div class="nav toggle">
						<a id="menu_toggle"><i class="fa fa-bars"></i></a>
					</div>

					<nav>

						<div class="nav" style="float:left; margin-left:0px; margin-top:5px">

							<h3>Accounting System</h3>

						</div>


						<ul class="nav navbar-nav navbar-right">

							<li>

								<a href="javascript:;">
								<div><i class="fa fa-sign-out pull-right" style="margin-top:8px"></i>  Log Out</div>

								</a>

							</li>
							<li >

								<a >
								<strong style="color:#FF0000">Notifications</strong>
								<i class="fa fa-envelope" style="color:#FF0000"></i>
								</a>

							</li>

						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
	<!-- /top navigation -->

	<!-- page content -->
	<div class="right_col" role="main">


		<!-- top tiles -->
		<div class="row tile_count" style="height:50px; color:#FF6600">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">

			</div>
		</div>
		<!-- /top tiles -->

		<div class="row">


            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
					<div class="x_title">
						<h2>Payables</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>

							<li><a class="close-link"><i class="fa fa-close"></i></a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">

						<ul class="nav nav-tabs bg-success table-responsive">
							<li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##purchasing">Purchasing <span class="badge"><?php echo count($voucher_deliveries) ?></span></a></li>
							<li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##services">Service Providers <span class="badge"><?php echo count($services) ?></span></a></li>
							<li <?php echo $v_tab_active; ?>><a data-toggle="tab" href="##cash_advance">Cash Requests  <span class="badge" ><?php echo count($cash_advance) ?></span></a> </li>
						</ul>

						<div  class="tab-content">

							<div id="purchasing" class="tab-pane fade <?php echo $r_in_active ?> table-responsive">
								<br>

								<table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
									<thead>
                                        <tr>
											<th>Date Delivered</th>
											<th>Voucher Number</th>
											<th>PO Number</th>
											<th>Supplier</th>
											<th>Amount</th>
											<th>Status</th>
											<th>Manage</th>
										</tr>
									</thead>


									<tbody>

                                        <?php


											if($voucher_deliveries == TRUE){
												foreach($voucher_deliveries as $v){

													$supplier = $this->Abas->getSupplier($v['supplier_id']);

													$vstat= 'For processing';
													$vnumber= '';
													if($v['voucher_id'] != ''){
														$voucher_status = $this->Accounting_model->getVoucherInfo($v['voucher_id']);
														//var_dump($voucher_status);
														$vstat = $voucher_status[0]['status'];
														$vnumber= $voucher_status[0]['voucher_number'];
													}
												?>
												<tr>

													<td><?php echo date('F j, Y', strtotime($v['tdate'])) ?></td>
													<td><?php echo $vnumber ?></td>
													<td><?php echo $v['po_no'] ?></td>

													<td><?php echo $supplier['name'] ?></td>
													<td><?php echo number_format($v['amount'],'2') ?></td>
													<td><?php echo $vstat; ?></td>
													<td align="center">
														<?php if($vstat == 'For processing'){ ?>
															<a class="like" href="<?php echo HTTP_PATH ?>accounting/voucher_form/<?php echo $v['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">
															<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
															</a>
														<?php 	} ?>

														<?php if($vstat == 'For voucher printing' || $vstat == 'For releasing'){ ?>
															<a class="like" href="<?php echo HTTP_PATH ?>accounting/print_voucher/<?php echo $vnumber ?>" data-toggle="modal" data-target="#modalDialog" title="Print">
															<button type="button" class="btn btn-success btn-xs" ><i class="glyphicon glyphicon-print"></i></button>
															</a>
														<?php 	} ?>

													</td>
												</tr>
												<?php

												}
												}else{

												echo '<tr><td  colspan="6">No voucher request found</td></tr>';
											}
										?>


									</tbody>
								</table>
							</div>

                            <div id="services" class="tab-pane fade <?php echo $c_in_active ?> table-responsive">
                            	Services
							</div>

                            <div id="cash_advance" class="tab-pane fade <?php echo $v_in_active ?> table-responsive">
								<br>
								<hr />
								<table id="datatable-responsive1" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
										<tr>
											<th>Date Requested</th>
											<th>Requested by</th>
											<th>Amount</th>
											<th>Purpose</th>
											<th>Department</th>
											<th>Type of Request</th>

											<th>Status</th>
											<th>Manage</th>
										</tr>
									</thead>
                                    <tbody>
										<?php
											if( !empty($cash_advance) ) {
												foreach ($cash_advance as $cash){

													//$c = $this->Finance_model->getCashAdvanceByVoucherId($cash['id']);
													$requested_by = $this->Abas->getEmployee($cash['requested_by']);
													$department  = $this->Abas->getDepartment($cash['department']);

													$vstat= 'For processing';
													$vnumber= '';
													if($cash['voucher_id'] != '' && $cash['voucher_id'] != 0){

														$voucher_info = $this->Accounting_model->getVoucherInfo($cash['voucher_id']);

														$vstat = $cash['status'];
														$vnumber= $voucher_info[0]['voucher_number'];
													}

													$department = (empty($department->name)) ? $department = '' : $department = $department->name;

												?>
												<tr>
													<?php $id = $cash['id']; ?>
													<input type="hidden" name="id" value="<?php echo $id;  ?>">
													<td><?php echo date('F j, Y', strtotime($cash['date_requested'])) ?></td>
													<td><?php echo $requested_by['full_name']; ?></td>
													<td align="right"><?php echo number_format($cash['amount'],2); ?></td>
													<td><?php echo $cash['purpose']; ?></td>
													<td><?php echo $department; ?></td>
													<td><?php echo $cash['type']; ?></td>

													<td><?php echo $vstat ?></td>
													<td align="center">

														<?php if($vstat == 'For processing'){ ?>
															<a class="like" href="<?php echo HTTP_PATH ?>accounting/voucher_CRform/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">
															<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
															</a>
														<?php 	} ?>

														<?php if($vstat == 'For voucher printing' || $vstat == 'For releasing'){ ?>
															<a class="like" href="<?php echo HTTP_PATH ?>accounting/print_cr_voucher/<?php echo $cash['voucher_id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Print">
															<button type="button" class="btn btn-success btn-xs" ><i class="glyphicon glyphicon-print"></i></button>
															</a>
														<?php 	} ?>
														<!---
															<a class="like" href="<?php echo HTTP_PATH ?>finance/cash_advance_info/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog">
															<button type="button" class="btn btn-primary btn-xs">For Funding</button>
															</a>
														--->
													</td>




												</td>
											</tr>
											<?php

											}
											}else{

											echo '<tr><td  colspan="6">No voucher request found</td></tr>';
										}
									?>
								</tbody>
							</table>

						</div>

					</div>
					<!-- end of tab--->

				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- /page content -->

<!-- footer content -->
<footer>
	<div class="pull-right">
		<strong>AVEGAiT2015</strong>
	</div>
	<div class="clearfix"></div>
</footer>
<!-- /footer content -->
</div>
</div>

<?php $this->load->view("includes_footer_scripts"); ?>
<script>
	$(document).ready(function() {
        $('#wsr_date').daterangepicker({
			singleDatePicker: true,
			calender_style: "picker_4"
			}, function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
		});
	});
</script>
</body>
</html>
