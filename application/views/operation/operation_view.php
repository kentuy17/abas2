<?php 

 //echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';
 
	//var_dump($contracts['rows'][0]);
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
            <?php $this->load->view('operation/side_menu'); ?>
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
                	
                  <a href="<?php echo HTTP_PATH.'home/' ?>">
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
              <span class="count_top"> 
              	<a class="like" href="<?php echo HTTP_PATH ?>operation/new_contract_form" data-toggle="modal" data-target="#modalDialog" title="Report">
              	<button type="button"><i class="fa fa-check"></i> New Contract</button>
              	</a>
              </span>
              <div class="count"></div>
              
            </div>
            
          </div>
          <!-- /top tiles -->

          <div class="row">
            
              
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Contracts</h2>
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
                    	<table data-toggle="table" id="invent-table" class="table table-striped table-hover table-responsive" data-cache="false" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr align="center">
												<th width="3%"  data-field="id" data-align="center" data-sortable="true" >ID</th>
                                                <th width="12%"  data-align="center" data-sortable="true">Contract Date</th>
                                                <th width="12%"  data-align="center" data-sortable="true">Reference #</th>
												<th  width="23%"  data-sortable="true">Contractor</th>
                                                <th  width="24%"   data-sortable="true">Client</th>
												<th width="15%"  >Type</th>
												
                                                <th width="10%"  data-align="center"    data-sortable="true">Status</th>                                            
												<th width="4%"  data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
											<tbody>
											<?php
												//var_dump($expenses);
												//exit;
											
											if($contracts){
												
												//$ctr = count($contracts);
												
												foreach($contracts as $contract){
																								
												$company_name = $this->Abas->getCompany($contract['company_id']);
												$client_name = $this->Abas->getClient($contract['client_id']);
												$client='';
												if($client_name){ $client = $client_name->company; };
												
											?>
												<tr>
													<td  align="center"><?php  echo $contract['id']; ?></td>                                                    
                                                    <td align="left"><?php echo date('F j, Y',strtotime($contract['date_effective'])); ?></td>
													<td  align="left"><?php echo $contract['reference_no']; ?></td>
                                                    <td  align="left"><?php echo $company_name->name; ?></td>
													<td align="left"><?php echo $client; ?></td>
													<td align="left"><?php echo $contract['type']; ?></td>
													
                                                    <td align="center"><?php echo $contract['status']; ?></td>                                                    
													<td align="center" style="font-size:12px; color:#00CC33">
                                                    	<a class="like" href="<?php echo HTTP_PATH ?>operation/new_contract_form/<?php  echo $contract['id']; ?>" data-toggle="modal" data-target="#modalDialog" title="WSR Form">
                                                        <i class="fa fa-pencil" ></i></a>
                                                    </td>
												</tr>
											<?php }											
											}else{
													echo "<tr><td  colspan='8'>No contracts found. </td></tr>";
											}
											 ?>
										</tbody>
									</table>
                    
                  	</div>
                </div>
              </div>
                    
                    
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
   <?php $this->load->view("includes_footer_scripts"); ?>  
    
</body>
</html>
