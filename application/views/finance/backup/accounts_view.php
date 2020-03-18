<?php 
   //for tab
   
   $r_tab_active = '';
   $r_in_active = '';
   $c_tab_active = '';
   $c_in_active = '';
   $v_tab_active = '';
   $v_in_active = '';
   
   if($tab == 'bank_accounts'){
   	$c_tab_active = 'class="active"';
   	$c_in_active = 'in active';
   }elseif($tab == 'cash_advance'){
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }elseif($tab == 'supplier_accounts'){
   	$r_tab_active = 'class="active"';
   	$r_in_active = 'in active';
   }else{
   	$r_tab_active = 'class="active"';
   	$r_in_active = 'in active';
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
                        <h3>Finance</h3>
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
                           </ul>
                           <div  class="tab-content">
                              <div id="bank_accounts" class="tab-pane fade <?php echo $c_in_active ?> table-responsive">
                                 <br>
                                 <a class="like" href="<?php echo HTTP_PATH ?>forms/bank_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
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
                                          if( !empty($bankList) ) {
                                              foreach ($bankList as $bank){										
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
                                             <a class="like" href="<?php echo HTTP_PATH ?>forms/bank_form/<?php echo $bank['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher" data-keyboard="false" data-backdrop="static">
                                             <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                             </a>
                                          </td>
                                          <?php } } ?>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                              <div id="cash_advance" class="tab-pane fade <?php echo $v_in_active ?> table-responsive">
                                 <br>
                                 <a class="like" href="<?php echo HTTP_PATH ?>finance/cash_form" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                 <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Submit</button>
                                 </a>
                                 <hr />
                                 <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Date Requested</th>
                                          <th>Requested by</th>
                                          <th>Amount</th>
                                          <th>Purpose</th>
                                          <th>Department</th>
                                          <th>Type of Request</th>
                                          <th>Date Released</th>
                                          <th>Manage</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php 
                                          if( !empty($cashList) ) {
                                          foreach ($cashList as $cash){	
                                          ?>
                                       <tr>
                                          <input type="hidden" value="<?php echo $id; ?>">
                                          <td><?php echo date('F j, Y', strtotime($cash['date_requested'])) ?></td>
                                          <td><?php echo $cash['requested_by']; ?></td>
                                          <td><?php echo $cash['amount']; ?></td>
                                          <td><?php echo $cash['purpose']; ?></td>
                                          <td><?php echo $cash['department']; ?></td>
                                          <td><?php echo $cash['type']; ?></td>
                                          <td><?php echo date('F j, Y', strtotime($cash['date_released'])) ?></td>
                                          <td align="center">
                                             <a class="like" href="<?php echo HTTP_PATH ?>finance/cash_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">
                                             <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                             </a>
                                          </td>
                                       </tr>
                                       <?php 
                                          } }
                                          ?>
                                    </tbody>
                                 </table>
                              </div>
                              <div id="supplier_accounts" class="tab-pane fade <?php echo $r_in_active ?> table-responsive">
                                 Supplier Accounts
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