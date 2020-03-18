<?php 
   //for tab
   
   $r_tab_active = '';
   $r_in_active = '';
   $c_tab_active = '';
   $c_in_active = '';
   $v_tab_active = '';
   $v_in_active = '';
   $a_tab_active = '';
   $a_in_active = '';
   $ve_tab_active = '';
   $ve_in_active = '';
   $t_tab_active = '';
   $t_in_active = '';
   $cr_tab_active = '';
   $cr_in_active = '';
   $cl_tab_active = '';
   $cl_in_active = '';
   $co_tab_active = '';
   $co_in_active = '';
   
   if($tab == 'bank_accounts'){
   	$c_tab_active = 'class="active"';
   	$c_in_active = 'in active';
   }elseif($tab == 'vesselList'){
   	$ve_tab_active = 'class="active"';
   	$ve_in_active = 'in active';
   }elseif($tab == 'truckList'){
   	$t_tab_active = 'class="active"';
   	$t_in_active = 'in active';
   }elseif($tab == 'craneList'){
   	$cr_tab_active = 'class="active"';
   	$cr_in_active = 'in active';
   }elseif($tab == 'cash_advance'){
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }elseif($tab == 'supplier_accounts'){
   	$r_tab_active = 'class="active"';
   	$r_in_active = 'in active';
   }elseif($tab == 'service_provider'){
   	$a_tab_active = 'class="active"';
   	$a_in_active = 'in active';
   }elseif($tab == 'clientList'){
   	$cl_tab_active = 'class="active"';
   	$cl_in_active = 'in active';
   }elseif($tab == 'companyList'){
   	$co_tab_active = 'class="active"';
   	$co_in_active = 'in active';
   }else{
   	$c_tab_active = 'class="active"';
   	$c_in_active = 'in active';
   }
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <!-- Meta, title, CSS, favicons, etc. -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>ABAS-Operation Management System</title>
      <?php $this->load->view('includes_header'); ?>
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
                  <?php $this->load->view('finance/side_menu_finance'); ?>
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
                        <h3>Accounting</h3>
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
                           <h2>Accounts Management</h2>
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
                              <li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##bank_accounts">Bank Accounts <span class="badge"><?php echo count($bank_accounts) ?></span></a></li>
                              <li <?php echo $v_tab_active; ?>><a data-toggle="tab" href="##cash_advance">Cash Advance <span class="badge"><?php echo count($cash_advance) ?></span></a></li>
                              <li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##supplier_accounts">Supplier Accounts  <span class="badge" ><?php echo count($supplier_accounts) ?></span></a> </li>
                              <li <?php echo $a_tab_active; ?>><a data-toggle="tab" href="##service_provider">Service Provider  <span class="badge" ><?php echo count($service_provider) ?></span></a> </li>
                              <li <?php echo $cl_tab_active; ?>><a data-toggle="tab" href="##clientList">Clients  <span class="badge" ><?php echo count($clientList) ?></span></a> </li>
                           </ul>
                           <div  class="tab-content">
                              <div id="bank_accounts" class="tab-pane fade <?php echo $c_in_active ?>">
                                 <br>
                                 <a class="like" href="<?php echo HTTP_PATH ?>accounting/bank_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
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
                                             <a class="like" href="<?php echo HTTP_PATH ?>accounting/bank_form/<?php echo $bank['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher" data-keyboard="false" data-backdrop="static">
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
                              <div id="cash_advance" class="tab-pane fade <?php echo $v_in_active ?> table-responsive">
                                 <br>
                                 <a class="like" href="<?php echo HTTP_PATH ?>accounting/cash_form" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                 <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add Cash Advance</button>
                                 </a>
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
                                          <th>Date Released</th>
                                          <th>Status</th>
                                          <th>Manage</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php 
										if( !empty($cash_advance) ) {
                                          foreach ($cash_advance as $cash){	
                                          ?>
                                       <tr>
                                          <td><?php echo date('F j, Y', strtotime($cash['date_requested'])) ?></td>
                                          <td><?php echo $cash['requested_by']; ?></td>
                                          <td><?php echo $cash['amount']; ?></td>
                                          <td><?php echo $cash['purpose']; ?></td>
                                          <td><?php echo $cash['department']; ?></td>
                                          <td><?php echo $cash['type']; ?></td>
                                          <td><?php echo date('F j, Y', strtotime($cash['date_released'])) ?></td>
                                          <td><?php echo $cash['status'] ?></td>
                                          <td align="center">
                                             <a class="like" href="<?php echo HTTP_PATH ?>accounting/cash_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">
                                             <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                             </a>
                                             <a class="like" href="<?php echo HTTP_PATH ?>accounting/liquidation_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog">
                                             <button type="button" class="btn btn-info btn-xs">Liquidate</button>
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
                                 <br>
								   <a class="like" href="<?php echo HTTP_PATH ?>forms/supplier_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
								   <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add Supplier</button>
								   </a>
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
                                          <th>Manage</th>
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
										  <td align="center">
										   <a class="like" href="<?php echo HTTP_PATH ?>forms/supplier_form/<?php echo $supplier['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher" data-keyboard="false" data-backdrop="static">
										   <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
										   </a>
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
                                 <br>

									<a class="like" href="<?php echo HTTP_PATH ?>forms/vessel_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
										<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add New Service Provider</button>
									</a>
									<hr />
                                 <table id="datatable-responsive3" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Company Name</th>
                                          <th>Contact Person</th>
                                          <th>Contact</th>
                                          <th>Address</th>
                                          <th>Region</th>
                                          <th>Manage</th>
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
										  <td align="center">
										   <a class="like" href="<?php echo HTTP_PATH ?>forms/service_provider_form/<?php echo $service['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
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
							 
							  <div id="clientList" class="tab-pane fade <?php echo $cl_in_active ?>">
							  <br>
						    <a class="like" href="<?php echo HTTP_PATH ?>forms/client_form" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
								<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add New Client</button>
							</a>
							<hr />
				
                           <table id="datatable-responsive7" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="70%">
                              <thead>
                                 <tr>
                                    <th>Company</th>
                                    <th>Address</th>
									<th>City</th>
									<th>Province</th>
									<th>Country</th>
									<th>Contact No</th>
									<th>Fax No</th>
									<th>Email</th>
									<th>Website</th>
									<th>Contact Person</th>
									<th>Position</th>
									<th>Lead Person</th>
									<th>TIN No</th>
                                    <th>Manage</th>
                                 </tr>
                              </thead>
                              <tbody>
							   <?php 
								if( !empty($clientList) ) {
                                    foreach ($clientList as $client)
									
                                    {
										//var_dump($portList);exit;
									//echo (empty($bank)? "<h2 style='margin-top:30px;'><center>You have no Reservations!</center></h2>": ""); //result not empty	
                                    ?>
                                 <tr>
                                    <td><?php  echo $client['company'] ?></td>
									<td><?php  echo $client['address'] ?></td>
									<td><?php  echo $client['city'] ?></td>
									<td><?php  echo $client['province'] ?></td>
									<td><?php  echo $client['country'] ?></td>
									<td><?php  echo $client['contact_no'] ?></td>
									<td><?php  echo $client['fax_no'] ?></td>
									<td><?php  echo $client['email'] ?></td>
									<td><?php  echo $client['website'] ?></td>
									<td><?php  echo $client['contact_person'] ?></td>
									<td><?php  echo $client['position'] ?></td>
									<td><?php  echo $client['lead_person'] ?></td>
									<td><?php  echo $client['tin_no'] ?></td>
									<td align="center">
                                       <a class="like" href="<?php echo HTTP_PATH ?>forms/client_form/<?php echo $client['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                       <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                       </a>
                                    </td>
                                 </tr>
								<?php } } ?>
                              </tbody>
                           </table>
							  </div>
							<div id="companyList" class="tab-pane fade <?php echo $co_in_active ?>">
							  <br>
						    <a class="like" href="<?php echo HTTP_PATH ?>forms/company_form" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
								<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add New Company</button>
							</a>
							<hr />
				
                           <table id="datatable-responsive8" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="70%">
                              <thead>
                                 <tr>
                                    <th>Company</th>
                                    <th>Address</th>
									<th>Telephone No</th>
									<th>Fax No</th>
                                    <th>Manage</th>
                                 </tr>
                              </thead>
                              <tbody>
							   <?php 
								if( !empty($companyList) ) {
                                    foreach ($companyList as $company)
									
                                    {
										//var_dump($portList);exit;
									//echo (empty($bank)? "<h2 style='margin-top:30px;'><center>You have no Reservations!</center></h2>": ""); //result not empty	
                                    ?>
                                 <tr>
                                    <td><?php  echo $company['name'] ?></td>
									<td><?php  echo $company['address'] ?></td>
									<td><?php  echo $company['telephone_no'] ?></td>
									<td><?php  echo $company['fax_no'] ?></td>
									<td align="center">
                                       <a class="like" href="<?php echo HTTP_PATH ?>forms/company_form/<?php echo $company['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                       <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                       </a>
                                    </td>
                                 </tr>
								<?php } } ?>
                              </tbody>
                           </table>
							  </div>													  
                           </div>
                           <!-- end of tab--->   
                        </div>
                     </div>
                  </div>
               </div>
               <?php 
                  //$cash	=	$this->db->query('ALTER TABLE `suppliers` ADD `bank_name` VARCHAR(20) NOT NULL AFTER `modified`, ADD `bank_account_no` VARCHAR(20) NOT NULL AFTER `bank_name`');
                  //var_dump($cash);exit;
                  ?>
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

<!-- /Datatables -->
    <script>
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();

        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();
        $('#datatable-responsive1').DataTable();
        $('#datatable-responsive2').DataTable();
        $('#datatable-responsive3').DataTable();
        $('#datatable-responsive4').DataTable();
        $('#datatable-responsive5').DataTable();
        $('#datatable-responsive6').DataTable();
        $('#datatable-responsive7').DataTable();
        $('#datatable-responsive8').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
          'order': [[ 1, 'asc' ]],
          'columnDefs': [
            { orderable: false, targets: [0] }
          ]
        });
        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

        TableManageButtons.init();
      });
    </script>