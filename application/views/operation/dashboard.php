<?php 

 //echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';
 
		
?>

    
    <?php $this->load->view('includes_header'); ?>


<script type="text/javascript">

		$(document).ready(function () {
			
				$( "#autocomplete" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>operation/wsr_data",
					minLength: 2,
					search: function(event, ui) {
						toastr['info']('Loading, please wait...');
					},
					response: function(event, ui) {
						toastr.clear();
					},
					select: function( event, ui ) {
						// alert(ui.item.value);
						//alert(ui.item.value);
						$( "#autocomplete" ).val( ui.item.label );
						$( "#selitem" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						//$("#qty").focus();
						return false;
					}
				});
				
				$('#wsr_entry').click(function(){
    					$("#wb").hide();
						$("#autocomplete").val('');
						$("#autocomplete").focus();
						$("#wsr").show();
				});

				$('#wb_entry').click(function(){
    					$("#wsr").hide();
						$("#autocomplete2").focus();
						$("#wb").show();
				});
				
		});
		
		$(document).ready(function () {
			
				$( "#autocomplete2" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>operation/waybill_data",
					minLength: 2,
					search: function(event, ui) {
						toastr['info']('Loading, please wait...');
					},
					response: function(event, ui) {
						toastr.clear();
					},
					select: function( event, ui ) {
						// alert(ui.item.value);
						//alert(ui.item.value);
						$( "#autocomplete" ).val( ui.item.label );
						$( "#selitem" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						//$("#qty").focus();
						return false;
					}
				});
		});

		

		function delItem(id){
			//alert(id); exit;
			var se = document.getElementById('sels').value;	
			//alert(se); 
			de = se.replace(id, "");
			
			document.getElementById('sels').value = de;
			//se2= document.getElementById('sels').value;
			

			$.post('<?php echo HTTP_PATH."operation/getSelectedWsr/"; ?>',
				  { 'id':de,'action':'del' },
						function(result) {
							//alert(de);
							// clear any message that may have already been written
							$('#selected').html(result);
						}
			);


		}




		function submitMe(){

			var sp = document.getElementById('client').value;
			var r = document.getElementById('reference_no').value;
			var s = document.getElementById('sels').value;
			var rt = document.getElementById('rate').value;
			

			if(sp ==''){
				alert('Please select client.');
				document.getElementById('client').focus();
				return false;
			}else if(r == ''){
				alert('Please enter reference number.');
				document.getElementById('reference_no').focus();
				return false;
			}else if(rt == ''){
				alert('Please enter rate.');
				document.getElementById('rate').focus();
				return false;
			}else if(s == ''){
				alert('Please select WSRs.');
				document.getElementById('autocomplete').focus();
				return false;			
			}else{
				document.forms['wsrForm'].submit();
			}
		}
	
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
            <?php $this->load->view('operation/side_menu_dashboard_ops'); ?>
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
        <div class="right_col" role="main"  style="height:710px">
          
          
          <!-- top tiles -->
          
          <!-- /top tiles -->

          <div class="row">
            
              
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Manager's Dashboard</h2>
                    		                  	
                                
                      		
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                    </ul>
                    <div class="clearfix"></div>
                   
                  <div class="x_content">
                    <br>
                    
                    
					<!-- Tab Content here --->
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="##request">Request for Payment</a></li>
                        <li><a data-toggle="tab" href="##canvass">Collectibles</a></li>
                        <li><a data-toggle="tab" href="##contract_tab">Contract Status</a></li>                        
                    </ul>
                    <div class="tab-content">
                        <div id="request" class="tab-pane fade in active">
                            <br>
                           
                            <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-hover table-bordered dt-responsive nowrap" data-pagination="true"  data-page-size="5"  data-search="true" cellspacing="0" width="100%">
                                <thead>
                                   <th width="5%">Billing #</th>
                                    <th width="15%">Date Created</th>
                                    <th width="10%">Reference #</th>
                                    <th width="35%">Service Provider</th>
                                    <th width="15%">Transaction Type</th>                                    
                                    <th width="15%">Amount</th>
                                    <th width="10%">Manage</th>
                                </thead>
                                <tbody>
                                    <?php
                                   	
									if(count($ap_billings)){
										foreach($ap_billings as $ap_bill){
										
										//get service provider name
										$sp_name = $this->Operation_model->getServiceProvider($ap_bill['service_provider_id']);
										
                                    ?>
                                    <tr>
                                      <td><?php echo $ap_bill['id'] ?></td>
                                      <td><?php echo date('F j, Y', strtotime($ap_bill['date_created'])) ?></td>
                                      <td><?php echo $ap_bill['reference_no'] ?></td>
                                      <td><?php echo $sp_name->company_name ?></td>
                                      <td><?php echo $ap_bill['type'] ?></td>
                                      <td align="right"><?php echo number_format($ap_bill['amount'],2) ?>&nbsp;</td>
                                      <td align="center">
                                        
                                       
                                        
                                        <a class="like" href="<?php echo HTTP_PATH."operation/ap_approval_form/".$ap_bill['id']; ?>" data-toggle="modal" data-target="#modalDialogSemiWide" title="View">
                                            <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-search"></i> View</button>
                                        </a>
                                      
                                        </tr>
                                        <?php } 
										}else{
											echo '<tr><td  colspan="6">No records found</td></tr>';
										}
										?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                            <div id="canvass" class="tab-pane fade">
                                
                            		<br>
                             <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-hover table-bordered dt-responsive nowrap" data-pagination="true"  data-page-size="5"  data-search="true" cellspacing="0" width="100%">
                                <thead>
                                   <th width="5%">Billing #</th>
                                    <th width="15%">Date Created</th>
                                    <th width="10%">Reference #</th>
                                    <th width="35%">Client</th>
                                    <th width="15%">Transaction Type</th>                                    
                                    <th width="15%">Amount</th>
                                    <th width="10%">Status</th>
                                </thead>
                                <tbody>
                                    <?php
                                   	
									if(count($ar_billings)){
										foreach($ar_billings as $ar_bill){
										
										//get service provider name
										$sp_name = $this->Abas->getClient($ar_bill['client']);
										
                                    ?>
                                    <tr>
                                      <td><?php echo $ar_bill['id'] ?></td>
                                      <td><?php echo date('F j, Y', strtotime($ar_bill['date_created'])) ?></td>
                                      <td><?php echo $ar_bill['reference_no'] ?></td>
                                      <td><?php echo $sp_name->company ?></td>
                                      <td><?php echo $ar_bill['type'] ?></td>
                                      <td align="right"><?php echo number_format($ar_bill['amount'],2) ?>&nbsp;</td>
                                      <td align="center"></td>
                                        
                                       
                                        
                                                                         
                                        </tr>
                                        <?php } 
										}else{
											echo '<tr><td  colspan="6">No records found</td></tr>';
										}
										?>
                                        
                                    </tbody>
                                </table>
                                
                            </div>
                            
                            <div id="contract_tab" class="tab-pane fade">
                                
                            		<br>
                             <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-hover table-bordered dt-responsive nowrap" data-pagination="true"  data-page-size="5"  data-search="true" cellspacing="0" width="100%">
                                <thead>
                                   
                                    <th width="10%">Date</th>
                                    <th width="8%">Reference #</th>  
                                    <th width="8%">Sub-Reference #</th>                                    
                                    <th width="20%">Client</th>
                                    <th width="12%">Transaction Type</th>                                    
                                    <th width="10%">Qty</th>                                    
                                    <th width="10%">Qty Served</th>
                                    <th width="10%">Balance</th>
                                    <th width="20%">Status</th>
                                </thead>
                                <tbody>
                                    <?php
                                   	
									
									if(count($contracts)){
										foreach($contracts as $con){
										
										//get client name
										$client = $this->Abas->getClient($con['client_id']);
										$transactions = $this->Operation_model->getBagsByReference($con['sub_reference_no']);
										
										$balance = 0;	
										$total_weight = 0;	
										$bags_served = 0;
										
										if($transactions){											
											//get balance	
											$bags_served = $transactions->total_bags;	
																
											$balance = (int)$con['quantity'] - (int)$bags_served;										
											
										} 
										
										//status (will replace to efficient status later)
										$status = 'Not yet started';
										$disply = 'none';
										if($bags_served > 0){
											$status = 'On-going...';
											$disply = 'block';
										}elseif($bags_served == $con['quantity']){
											$status = 'Finished';
										}
										
                                    ?>
                                   
                                    <tr>
                                      <td><?php echo date('F j, Y', strtotime($con['date_effective'])) ?></td>
                                     
                                      <td><?php echo $con['reference_no'] ?></td>
                                      <td><?php echo $con['sub_reference_no'] ?></td>
                                      <td><?php echo $client->company ?></td>
                                      <td><?php echo $con['type'] ?></td>
                                      <td align="right"><?php echo number_format($con['quantity'],0).' '.$con['unit'] ?></td>                                                                        
                                      <td align="right"><?php echo number_format($bags_served,0) ?></td>
                                      <td align="right"><?php echo number_format($balance,0) ?></td>
                                      <td align="left">
									  <a href="<?php echo HTTP_PATH."operation/contract_status_detail/".$con['id']; ?>" style="display:<?php echo $disply ?>; float:left; margin-top:10px; color:#00CC33" title="View report" data-toggle="modal" data-target="#modalDialogSemiWide">
                                       <i class="fa fa-search"></i>
                                       </a>    
									  <span style="float:left; margin-left:10px">
										<?php echo $status ?>
                                       </span>
                                      </td>
                                        
                                       
                                        
                                                                         
                                        </tr>
                                    
									<?php 
										}
										 
									}else{
										echo '<tr><td  colspan="6">No records found</td></tr>';
									}
                                    ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                            
                            
                      	</div>    
                      	<!-- end Tab Content here --->    
                          
                                             
                     
                      
                 </div>
                
                 
                    
                      
            
            </div>
          </div>
        </div>
        <!-- /page content -->

        
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
