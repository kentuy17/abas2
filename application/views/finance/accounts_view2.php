<?php


 $r_tab_active = '';
   $r_in_active = '';
   $c_tab_active = '';
   $c_in_active = '';
   $v_tab_active = '';
   $v_in_active = '';
   $a_tab_active = '';
   $a_in_active = '';
   $p_tab_active = '';
   $p_in_active = '';

   if($tab == 'bank_accounts'){
   	$c_tab_active = 'class="active"';
   	$c_in_active = 'in active';
   }elseif($tab == 'cash_advance'){
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }elseif($tab == 'supplier_accounts'){
   	$r_tab_active = 'class="active"';
   	$r_in_active = 'in active';
   }elseif($tab == 'service_provider'){
   	$a_tab_active = 'class="active"';
   	$a_in_active = 'in active';
	}elseif($tab == 'voucher'){
   	$p_tab_active = 'class="active"';
   	$p_in_active = 'in active';
   }else{
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }



?>
<?php $this->load->view('includes_header'); ?>

<script>
 $(document).ready(function() {
 $('#datatable-responsive').DataTable();
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
            <?php $this->load->view('inventory/side_menu_inventory'); ?>
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

                <h3>Inventory System</h3>

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
                    <h2>Inventory Items</h2>
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
                               <li <?php echo $v_tab_active; ?>><a data-toggle="tab" href="##cash_advance">Cash Requests <span class="badge"><?php echo count($cash_advance) ?></span></a></li>
                               <li <?php echo $p_tab_active; ?>><a data-toggle="tab" href="##vouchers">Purchase Payments <span class="badge"><?php echo count($vouchers) ?></span></a></li>
                              <li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##bank_accounts">Bank Accounts <span class="badge"><?php echo count($bank_accounts) ?></span></a></li>
                              <li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##supplier_accounts">Supplier Accounts  <span class="badge" ><?php echo count($supplier_accounts) ?></span></a> </li>
                              <li <?php echo $a_tab_active; ?>><a data-toggle="tab" href="##service_provider">Service Provider  <span class="badge" ><?php echo count($service_provider) ?></span></a> </li>
                           </ul>
                           
                           <div  class="tab-content">
                           
                              <div id="cash_advance" class="tab-pane fade <?php echo $v_in_active ?> table-responsive">
                                 <br>
                                 <a class="like" href="<?php echo HTTP_PATH ?>finance/cash_form" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                 <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> New Cash Request</button>
                                 </a>

                                 <a class="like" href="<?php echo HTTP_PATH ?>finance/add_fund_form" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                 <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add Funding</button>
                                 </a>
                                 
                                 
                                 <a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH ?>finance/liquidation_report_form" data-toggle="modal" data-target="#modalDialog" >
                                 <i class="glyphicon glyphicon-plus"></i> Liquidation Report
                                 </a>
                                 
                                 <?php 
								 
								 	$petty = $this->Finance_model->getPettyCashFund($location); 
									$revolving = $this->Finance_model->getRevolvingFund($location); 
									$operational = $this->Finance_model->getOperationalFund($location); 
									?>
                                 <span style="float:right; font-size:14px">
                                 	<strong>Remaining Funds:</strong><br>
                                    <span style="color:#009900">Petty Cash:&nbsp;&nbsp;	<?php  echo number_format($petty,2) ?>&nbsp;&nbsp;&nbsp;</span>			
                                    <span style="color:#0033CC">Revolving Fund:&nbsp;&nbsp;<?php  echo number_format($revolving,2) ?>&nbsp;&nbsp;&nbsp;	</span>
                                    <span style="color:#CC6600">Operations Fund:&nbsp;&nbsp;<?php  echo number_format($operational,2) ?>&nbsp;&nbsp;&nbsp;	</span>
                                 </span>
                                 <hr />
                                <table id="datatable-responsive1" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Date Requested</th>
                                          <th>Requested by</th>
                                          <th>Purpose</th>
                                          <th>Amount</th>

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

										  	$requested_by = $this->Abas->getEmployee($cash['requested_by']);
											$department  = $this->Abas->getDepartment($cash['department']);

											//var_dump($department->name);										
											$date_released = (empty($cash['date_released'])) ? $date_released = '' : $date_released = date('F j, Y', strtotime($cash['date_released'])); 
											
											$department = (empty($department->name)) ? $department = '' : $department = $department->name; 

                                          ?>
                                       <tr>
                                          <td><?php echo date('F j, Y', strtotime($cash['date_requested'])) ?></td>
                                          <td><?php echo $requested_by['full_name']; ?></td>
                                          <td><?php echo $cash['purpose']; ?></td>
                                          <td align="right"><?php echo number_format($cash['amount'],2); ?></td>

                                          <td><?php echo $department ?></td>
                                          <td><?php echo $cash['type']; ?></td>

                                          <td>
										  	<?php echo $cash['status'] ?>
                                          </td>
                                          <td align="center">
                                             <?php if($cash['status'] == 'For releasing'){  ?>
                                             	<a class="like" href="<?php echo HTTP_PATH ?>finance/cash_release_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Release">
                                             	<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-share"></i></button>
                                             	</a>
                                             <?php } ?>

                                             <?php if($cash['status'] == 'For funding approval'){  ?>

                                                <a class="like" href="<?php echo HTTP_PATH ?>finance/cash_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-remote="<?php echo HTTP_PATH ?>finance/cash_form/<?php echo $cash['id'] ?>" title="Create Voucher">
                                             	<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                             	</a>

                                             <?php } ?>

                                             <?php if($cash['status'] == 'Released'){  ?>
                                             		<a class="like" href="<?php echo HTTP_PATH ?>finance/liquidation_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-load-url="<?php echo HTTP_PATH ?>finance/liquidation_form/<?php echo $cash['id'] ?>">
                                             <button type="button" class="btn btn-info btn-xs">Liquidate</button>
                                             </a>
                                             <?php } ?>

                                             <?php if($cash['status'] == 'Liquidated'){  ?>
                                             	<a class="like" href="<?php echo HTTP_PATH ?>finance/print_liquidation/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Print">
                                             	<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-print"></i></button>
                                             	</a>
                                             <?php } ?>

                                          </td>
                                       </tr>
										<?php

											}
										}else{

												echo '<tr><td  colspan="6">No data found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
                              </div>

                              <!--- Voucher for release -->
                              <div id="vouchers" class="tab-pane fade <?php echo $p_in_active ?> table-responsive">
                                 <br>
                               <table id="datatable-responsive4" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                         <th>Voucher Date</th>
                                          <th>Voucher No.</th>
                                          <th>SI No.</th>
                                          <th>PO Number</th>
                                          <th>Pay To</th>
                                          <th>Amount</th>
                                          <th>Status</th>
                                          <th>Manage</th>
                                        </tr>
                                      </thead>


                                       <tbody>

                                        <?php

										if($vouchers == TRUE){

											foreach($vouchers as $v){

												$payto_name = $this->Abas->getSupplier($v['pay_to']);
												$po_detail = $this->Inventory_model->getDeliveryInfoByVoucherId($v['id']);

												$receipt = '';
												$po_no = '';

												if($po_detail){
													$receipt = $po_detail[0]['receipt_num'];
													$po_no = $po_detail[0]['po_no'];

												}
												//check if there is 2 aprrovers on this voucher
												//$no_approver = $this->Admin_model->checkVoucherApprover($v['id']);

										?>
                                        <tr>
                                          <td><?php echo date('F j, Y',strtotime($v['voucher_date'])) ?></td>
                                          <td><?php echo $v['voucher_number'] ?></td>
                                          <td><?php echo $receipt ?></td>
                                          <td><?php echo $po_no ?></td>
                                          <td><?php echo $payto_name['name'] ?></td>
                                          <td><?php echo number_format($v['amount'],2) ?></td>
                                          <td><?php echo $v['status']?></td>
                                          <td align="center">
                                            <?php

											if($v['status'] == 'For releasing'){
											?>
												<a class="like" href="<?php echo HTTP_PATH.'accounting/voucher_release_form/'.$v['id']; ?>" data-toggle="modal" data-target="#modalDialog" title="View">
                                                <button type="button" class="btn btn-primary btn-xs">
                                                <i class="glyphicon glyphicon-search"></i> View</button>
                                            	</a>

											<?php }else{ ?>
                                            	<!--
                                                <a class="like" href="<?php echo HTTP_PATH.'accounting/print_voucher/'.$v['id']; ?>" data-toggle="modal" data-target="#modalDialogSemiWide" title="View">
                                                <button type="button" class="btn btn-success btn-xs">
                                                <i class="glyphicon glyphicon-print"></i> Print</button>
                                            	</a>
                                                -->
                                            <?php } ?>

                                          </td>
                                        </tr>
										<?php
											}

										}else{
											echo '<tr><td  colspan="6">No canvass found</td></tr>';
										} ?>


                                      </tbody>
                                    </table>
                              </div>




                              <div id="bank_accounts" class="tab-pane fade <?php echo $c_in_active ?>">
                                 <br>
                                 <a class="like" href="<?php echo HTTP_PATH ?>finance/bank_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
                                 <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add Bank</button>
                                 </a>
                                 <hr />
                                 <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Account Name</th>
                                          <th>Account Number</th>
                                          <th>Account Type</th>
                                          <th>Currency</th>
                                          <th>Contact Person</th>
                                          <th>Contact Number</th>
                                          <th>Fax Number</th>
                                          <th>Email</th>
                                          <th>Manage</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                          if( !empty($bank_accounts) ) {
                                              foreach ($bank_accounts as $bank){
                                          ?>
                                       <tr>
                                          <td><?php  echo $bank['name'] ?></td>
                                          <td><?php  echo $bank['account_name'] ?></td>
                                          <td><?php  echo $bank['account_no'] ?></td>
                                          <td><?php  echo $bank['account_type'] ?></td>
                                          <td><?php  echo $bank['currency'] ?></td>
                                          <td><?php  echo $bank['contact_person'] ?></td>
                                          <td><?php  echo $bank['contact_no'] ?></td>
                                          <td><?php  echo $bank['fax_no'] ?></td>
                                          <td><?php  echo $bank['email'] ?></td>
                                          <td align="center">
                                             <a class="like" href="<?php echo HTTP_PATH ?>finance/bank_form/<?php echo $bank['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher" data-keyboard="false" data-backdrop="static">
                                             <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                             </a>
                                          </td>
                                       </tr>
									   <?php

											}
										}else{

												echo '<tr><td  colspan="6">No data found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
                              </div>

                              <div id="supplier_accounts" class="tab-pane fade <?php echo $r_in_active ?> table-responsive">
                                 <hr />
                                <table id="datatable-responsive2" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Address</th>
                                          <th>Contact Person</th>
                                          <th>Telephone Number</th>
                                          <th>Fax Number</th>
                                          <th>Email</th>
                                          <th>Bank Name</th>
                                          <th>Bank Account Number</th>
                                          <th>TIN</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                          if( !empty($supplier_accounts) ) {
                                              foreach ($supplier_accounts as $supplier){
                                          //var_dump($supplierList);exit;
                                          ?>
                                       <tr>
                                          <td><?php  echo $supplier['name'] ?></td>
                                          <td><?php  echo $supplier['address'] ?></td>
                                          <td><?php  echo $supplier['contact_person'] ?></td>
                                          <td><?php  echo $supplier['telephone_no'] ?></td>
                                          <td><?php  echo $supplier['fax_no'] ?></td>
                                          <td><?php  echo $supplier['email'] ?></td>
                                          <td><?php  echo $supplier['bank_name'] ?></td>
                                          <td><?php  echo $supplier['bank_account_no'] ?></td>
                                          <td><?php  echo $supplier['tin'] ?></td>
                                       </tr>
									   <?php

											}
										}else{

												echo '<tr><td  colspan="6">No data found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
                              </div>
                              <div id="service_provider" class="tab-pane fade <?php echo $a_in_active ?> table-responsive">
                                 <hr />
                                 <table id="datatable-responsive3" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Company Name</th>
                                          <th>Contact Person</th>
                                          <th>Contact</th>
                                          <th>Address</th>
                                          <th>Region</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                          if( !empty($service_provider) ) {
                                                                      foreach ($service_provider as $service)

                                                                      {
                                          		//var_dump($portList);exit;
                                          	//echo (empty($bank)? "<h2 style='margin-top:30px;'><center>You have no Reservations!</center></h2>": ""); //result not empty
                                                                      ?>
                                       <tr>
                                          <td><?php  echo $service['company_name'] ?></td>
                                          <td><?php  echo $service['contact_person'] ?></td>
                                          <td><?php  echo $service['contact'] ?></td>
                                          <td><?php  echo $service['address'] ?></td>
                                          <td><?php  echo $service['region'] ?></td>
                                       </tr>
									   <?php

											}
										}else{

												echo '<tr><td  colspan="6">No data found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
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







	$('input').on('click', function(){
	  var valeur = 0;
	  $('input:checked').each(function(){
		   if ( $(this).attr('value') > valeur )
		   {
			   valeur =  $(this).attr('value');
		   }
	  });
	  $('.progress-bar').css('width', valeur+'%').attr('aria-valuenow', valeur);
	});

	function newEntry(){
		window.location.assign("<?php echo HTTP_PATH.'Inventory' ?>")
		document.forms['itemForm'].reset();
	}

	function submitMe(){

		var id = document.getElementById('id').value;
		var i = document.getElementById('item_code').value;
		var d = document.getElementById('description').value;
		var p = document.getElementById('particular').value;
		var u = document.getElementById('unit').value;
		var uc = document.getElementById('unit_cost').value;
		var q = document.getElementById('qty').value;


		//if(id !== ''){

			/*
			alert('Editing is not allowed');
			return false;
		}else{*/

			if(i == ''){
				alert('Please enter Item Code');
				document.getElementById('item_code').focus();
				return false;
			}else if(d == ''){
				alert('Please enter Description');
				document.getElementById('description').focus();
				return false;
			}else if(u == ''){
				alert('Please select unit');
				document.getElementById('unit').focus();
				return false;
			}else if(q == ''){
				alert('Please enter quantity on hand');
				document.getElementById('qty').focus();
				return false;
			}else{
				document.forms['itemForm'].submit();
			}

		//}


	}

	function createReport(){

		document.forms['expenseReport'].submit();

	}

</script>
</body>
</html>
