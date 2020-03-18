<?php
   $id = "";
   $date_requested = "";
   $date_released = "";
   $requested_by = "";
   $purpose = "";
   $amount = "";
   $department = "";
   $type = "";
   
   if(isset($cash)){
   		//var_dump($item[0]['id']);exit;
   		$id = $cash[0]['id'];
   		$date_requested = $cash[0]['date_requested'];
   		$requested_by = $cash[0]['requested_by'];
   		$purpose = $cash[0]['purpose'];
   		$amount = $cash[0]['amount'];
   		$department = $cash[0]['department'];
   		$type = $cash[0]['type'];
   		$date_released = $cash[0]['date_released'];
		//var_dump($date_released);exit;

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
                    <h2>Cash Advance</h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">                    
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
	function myFunction() {
         location.reload();
         }s
    </script>
	
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
