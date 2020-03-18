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
						$( "#autocomplete2" ).val( ui.item.label );
						$( "#selitem" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						//$("#qty").focus();
						return false;
					}
				});
				
				$( "#autocomplete3" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>operation/wsi_data",
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
						$( "#autocomplete3" ).val( ui.item.label );
						$( "#selitem" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						//$("#qty").focus();
						return false;
					}
				});
				
				
				$('#wsr_entry').click(function(){
    					$("#wsi").hide();
						$("#wb").hide();
						$("#autocomplete").val('');
						$("#autocomplete").focus();
						$("#wsr").show();
				});

				$('#wb_entry').click(function(){
						$("#wsi").hide();
    					$("#wsr").hide();
						$("#autocomplete2").val('');
						$("#autocomplete2").focus();
						$("#wb").show();
				});
				
				$('#wsi_entry').click(function(){
    					$("#wsr").hide();
						$("#wb").hide();
						$("#autocomplete3").val('');
						$("#autocomplete3").focus();
						$("#wsi").show();
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
          
          <!-- /top tiles -->

          <div class="row">
            
              
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Statemet of Account</h2>
                    		                  	
                                
                      		
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                    </ul>
                    <div class="clearfix"></div>
                   
                  <div class="x_content">
                    <br>
                    
                    
					<form class="form-horizontal form-label-left" name="wsrForm" method="post" action="<?php echo HTTP_PATH.'operation/addSOA'; ?>">
                    <?php echo $this->Mmm->createCSRF() ?>
                    <div style="float:left">
                    
                    	
                        <div style="width:400px">	
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Select Billing Type:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                             
                              <select class="form-control" name="billing_type" id="billing_type" required="required">
                              		<option></option>                           
                                    <option value="Trucking">Trucking</option>                           
                                    <option value="Handling">Handling</option>                           
                                    <option value="Voyage">Voyage</option>                           
                              </select>
                              
                            </div>
                           
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Client:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                             
                              <select class="form-control" name="client" id="client" required="required">
                              		<option></option>
                                    <?php foreach($clients as $client){ ?>
                                    	<option value="<?php echo $client['id'] ?>"><?php echo $client['company'] ?></option>
                                    <?php } ?>
                              </select>
                              
                            </div>
                           
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Entry Type:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                             	<label class="radio-inline">
                                  <input type="radio" name="entry_type" id="wsr_entry">WSR
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="entry_type" id="wsi_entry">WSI
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="entry_type" id="wb_entry">Waybill
                                </label>
                                
                             	
                              
                            </div>
                           
                          </div>
                          
                          
                          
                                             
                      </div>
                      
                 </div>
                
                 <div style="float:right; margin-right:280px">
                    
                         <div class="form-group">
                            	<label class="control-label col-md-3 col-sm-3 col-xs-3">Reference #:</label>
                            	<div class="col-md-9 col-sm-9 col-xs-9">
                              	<input type="text" name="reference_no" id="reference_no" class="form-control" required="required">  
                                </div>
                           </div>
                          <div class="form-group">
                            	<label class="control-label col-md-3 col-sm-3 col-xs-3">Rate:</label>
                            	<div class="col-md-9 col-sm-9 col-xs-9">
                              	<input type="text" name="rate" id="rate" class="form-control" required="required">  
                                </div>
                           </div>
                           <div class="form-group">
                            	<label class="control-label col-md-3 col-sm-3 col-xs-3">Moves :</label>
                            	<div class="col-md-9 col-sm-9 col-xs-9">
                              	<input type="text" name="moves" id="moves" class="form-control" value="0">  (for handling only)
                                </div>
                           </div>
                           
                        <div style="width:450px; margin-top:25px">
                      	 	 
                  		</div>
                                     
                    
                          
                         
                 </div>    
                  <input type="hidden" id="sels" name="sels">
                 </form>
                 			<div style="margin-top:0px; margin-right:300px; float:right">
                                  
                                  <button type="submit" class="btn btn-success" onClick="submitMe();">Submit</button>
                             </div>
                 <div class="form-group" style="width:810px;">
                 	
                    <div style="float:left; width:400px" id="wsr">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">Enter WSR #:</label>  
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                <input type="text" id="autocomplete" class="ui-autocomplete-input form-control" style="background:#00CCFF" 
                                		onKeyPress="
                                        	if(event.keyCode == 13){
                                            	
                                                var ids   = new Array();
                                                var w = document.getElementById('selitem').value;
                                                var its = document.getElementById( 'sels' ).value;
                                                
                                                if(w !== ''){
                                                
                                                	if (its.indexOf(w) == -1) {
                                                        	//array.push(newItem);
                                                        	vals = w+','+its;
                                                            //alert(vals);
                                                            ids.push(vals);
                                                            document.getElementById( 'sels' ).value= ids;
                                                            
                                                             $.post('<?php echo HTTP_PATH."operation/getSelectedWsr/"; ?>',
                                                              { 'id':ids,'action':'wsr' },
                                                                    function(result) {
                                                                        //alert(result);                                                            
                                                                        
                                                                        $('#selected').html(result);
                                                                        // clear any message that may have already been written
                                                                        document.getElementById('autocomplete').value = '';
                                                                        document.getElementById('selecteditem').value = '';
                                                                    }
                                                            );
                                                    }   
                                                    else {
                                                        alert('WSR already selected.');
                                                         document.getElementById('autocomplete').value = '';
                                                         document.getElementById('selecteditem').value = '';
                                                    }
                                                
                                                	
                                                    
                                                }else{
                                                	alert('Please enter WSR Number.');
                                                }
                                            }
                                        "
                                	>
                                	<input type="hidden" id="selitem" name="selitem">
                                 	
                                    <br>
                            	</div>
                 	</div>
                    
                    <div style="float:left; width:400px; display:none" id="wsi">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">Enter WSI #:</label>  
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                <input type="text" id="autocomplete3" class="ui-autocomplete-input form-control" style="background:#00CCFF" 
                                		onKeyPress="
                                        	if(event.keyCode == 13){
                                            	
                                                var ids   = new Array();
                                                var w = document.getElementById('selitem').value;
                                                var its = document.getElementById( 'sels' ).value;
                                                
                                                if(w !== ''){
                                                
                                                	if (its.indexOf(w) == -1) {
                                                        	//array.push(newItem);
                                                        	vals = w+','+its;
                                                            //alert(vals);
                                                            ids.push(vals);
                                                            document.getElementById( 'sels' ).value= ids;
                                                            
                                                             $.post('<?php echo HTTP_PATH."operation/getSelectedWsr/"; ?>',
                                                              { 'id':ids,'action':'wsi' },
                                                                    function(result) {
                                                                        //alert(result);                                                            
                                                                        
                                                                        $('#selected').html(result);
                                                                        // clear any message that may have already been written
                                                                        document.getElementById('autocomplete3').value = '';
                                                                        document.getElementById('selecteditem').value = '';
                                                                    }
                                                            );
                                                    }   
                                                    else {
                                                        alert('WSI already selected.');
                                                         document.getElementById('autocomplete').value = '';
                                                         document.getElementById('selecteditem').value = '';
                                                    }
                                                
                                                	
                                                    
                                                }else{
                                                	alert('Please enter WSR Number.');
                                                }
                                            }
                                        "
                                	>
                                	<input type="hidden" id="selitem" name="selitem">
                                 	
                                    <br>
                            	</div>
                 	</div>
                    
                   <div style="float:left; width:400px; display:none" id="wb">
                    		
                            	<label class="control-label col-md-3 col-sm-3 col-xs-3">Waybill #:</label>  
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                <input type="text" id="autocomplete2" class="ui-autocomplete-input form-control" style="background:#00CCFF" 
                                		onKeyPress="
                                        	if(event.keyCode == 13){
                                            	
                                                var ids   = new Array();
                                                var w = document.getElementById('selitem').value;
                                                var its = document.getElementById( 'sels' ).value;
                                                
                                                if(w !== ''){
                                                
                                                	if (its.indexOf(w) == -1) {
                                                        	//array.push(newItem);
                                                        	vals = w+','+its;
                                                            //alert(vals);
                                                            ids.push(vals);
                                                            document.getElementById( 'sels' ).value= ids;
                                                            
                                                             $.post('<?php echo HTTP_PATH."operation/getSelectedWsr/"; ?>',
                                                              { 'id':ids,'action':'wb' },
                                                                    function(result) {
                                                                        //alert(result);                                                            
                                                                        
                                                                        $('#selected').html(result);
                                                                        // clear any message that may have already been written
                                                                        document.getElementById('autocomplete2').value = '';
                                                                        document.getElementById('selecteditem').value = '';
                                                                    }
                                                            );
                                                    }   
                                                    else {
                                                        alert('Waybill already selected.');
                                                         document.getElementById('autocomplete2').value = '';
                                                         document.getElementById('selecteditem').value = '';
                                                    }
                                                
                                                	
                                                    
                                                }else{
                                                	alert('Please enter Waybill Number.');
                                                }
                                            }
                                        "
                                	>
                                    <br>
                                    </div> 
                    </div>
                 </div>
                   
                 <div style="width:900px;" >&nbsp;
                 			
                            
                           
                                
                            <div id="selected">
                  			<table data-toggle="table" id="wsr-table" class="table table-striped table-hover table-responsive" data-cache="false"   style="font-size:12px">
										<thead>
											<tr style="background:#000000; color:#FFFFFF">
												<th width="4%">*</th>
                                                <th width="6%" >WSR #</th>
                                                <th width="14%" >WSR Date</th>
                                                <th width="12%" >Reference #</th>
												<th  width="15%">Issued From</th>
                                                <th  width="17%">Delivered To</th>
												<th width="8%" >No. Bags</th>
                                                <th width="15%" align="center" ><div style="margin-left:30px;">Gross Wt</div></th>    												
                                                <th width="15%">Net Wt</th>                                            
												
											</tr>
										</thead>
											<tbody>
                                            	
                                                <tr>
													<td align="center" style="font-size:12px; color:#00CC33">
                                                    	<a class="like" href="<?php echo HTTP_PATH ?>operation/wsr_form" data-toggle="modal" data-target="#modalDialog" title="WSR Form">
                                                        <i class="fa fa-close" ></i></a>
                                                    </td>
                                                    <td  align="left"></td>                                                    
                                                    <td align="left"></td>
													<td  align="left"></td>
                                                    <td  align="left"></td>
													<td align="left"></td>
													<td align="center"></td>
													<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
                                                    <td align="right"></td>                                                    
													
												</tr>
                                               
                                            </tbody>
                                            <tfoot>
                                            	<tr>
                                                	<td colspan="6" align="right">Totals:&nbsp;&nbsp;</td>                                                    
                                                    <td align="center">bags</td>
                                                    <td align="right">gw&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                    <td align="right">nw</td>
                                                    
                                                </tr>
                                            </tfoot>
                                            
                        </table>                
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
