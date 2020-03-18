<?php 

 //echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';

	
	
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
          
          
         

          <div class="row">
            
              
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Releasing</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <br>
                    	
                    	<table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
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
                                            	<a class="like" href="<?php echo HTTP_PATH.'accounting/print_voucher/'.$v['id']; ?>" data-toggle="modal" data-target="#modalDialogSemiWide" title="View">
                                                <button type="button" class="btn btn-success btn-xs">
                                                <i class="glyphicon glyphicon-print"></i> Print</button>
                                            	</a>
                                                
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
