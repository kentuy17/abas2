<?php 


 
 
 
	
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
                    <h2>Accounts Payable Clearing</h2>
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
                        <li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##purchasing">For Clearing <span class="badge"><?php echo count($voucher_deliveries) ?></span></a></li>
                        <!--
                        <li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##services">Cleared Payables <span class="badge"><?php //echo count($services) ?></span></a></li>
                        -->
                    </ul>
                    
                    <div  class="tab-content">
                        
                        <div id="purchasing" class="tab-pane fade <?php echo $r_in_active ?> table-responsive">
                     	<br>
                    	
                    	<table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>                                          
                                          <th>Date Delivered</th>                                          
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
												

										?>
                                        <tr>
                                          
                                          <td><?php echo date('F j, Y', strtotime($v['tdate'])) ?></td>
                                          
                                          <td><?php echo $v['po_no'] ?></td>
                                          
                                          <td><?php echo $supplier['name'] ?></td>
                                          <td><?php echo number_format($v['amount'],'2') ?></td>
                                          <td><?php echo $vstat; ?></td>
                                          <td align="center">
                                            
                                            <a class="like" href="<?php echo HTTP_PATH ?>accounting/ap_clearing_form/<?php echo $v['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                            </a>
                                         
                                            
                                            
                                            
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
