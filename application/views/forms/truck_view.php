<?php

$id = "";
//$name = "";


//if(isset($port)){
		//var_dump($item[0]['id']);exit;
		//$name = $port[0]['name'];
//}
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
            <?php $this->load->view('operation/side_menu_transaction'); ?>
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
                
                <h3>Avega Operation</h3>
                
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
                           <h2><a href="<?php echo HTTP_PATH ?>">Abas</a> >> <a href="<?php echo HTTP_PATH ?>operation">Operation</a> >> Service Provider</h2>
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

						    <a class="like" href="<?php echo HTTP_PATH ?>forms/truck_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
								<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add New Truck</button>
							</a>
							<hr />
				
                           <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="60%">
                              <thead>
                                 <tr>
                                    <th>Make</th>
                                    <th>Contact Person</th>
                                    <th>Model</th>
                                    <th>Plate Number</th>
                                    <th>Engine Number</th>
                                    <th>Chassis Number</th>
                                    <th>Type</th>
                                    <th>Color</th>
                                    <th>Date Acquired</th>
                                    <th>Registration Month</th>
                                    <th>Aquisition Cost</th>
                                    <th>Manage</th>
                                 </tr>
                              </thead>
                              <tbody>
							   <?php 
								if( !empty($truckList) ) {
                                    foreach ($truckList as $truck)
									
                                    {
										//var_dump($portList);exit;
									//echo (empty($bank)? "<h2 style='margin-top:30px;'><center>You have no Reservations!</center></h2>": ""); //result not empty	
                                    ?>
                                 <tr>
                                    <td><?php  echo $truck['make'] ?></td>
                                    <td><?php  echo $truck['contact_person'] ?></td>
                                    <td><?php  echo $truck['model'] ?></td>
                                    <td><?php  echo $truck['plate_number'] ?></td>
                                    <td><?php  echo $truck['engine_number'] ?></td>
                                    <td><?php  echo $truck['chassis_number'] ?></td>
                                    <td><?php  echo $truck['type'] ?></td>
                                    <td><?php  echo $truck['color'] ?></td>
                                    <td><?php  echo date('F j, Y', strtotime($truck['date_acquired'])) ?></td>
                                    <td><?php  echo $truck['registration_month'] ?></td>
                                    <td><?php  echo $truck['aquisition_cost'] ?></td>
									<td align="center">
                                       <a class="like" href="<?php echo HTTP_PATH ?>forms/truck_form/<?php echo $truck['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                       <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                       </a>
                                    </td>
                                 </tr>
								<?php } } ?>
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
