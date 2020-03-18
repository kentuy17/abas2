
<?php

	//var_dump($units);exit;  ssi1@globe.com.ph, noli - 09178707140
	$sales_invoice_no = "";
	$delivery_no = "";
	$delivery_date = "";
	$pono = "";
	$po_id ="";
	$supplier = "";
	$delivery_amount = "";
	$id = "";
	$items = "";
	$remark = "";
	$location= $_SESSION['abas_login']['user_location'];



	if(isset($item)){
		//var_dump($item[0]['id']);exit;
		$item_code = $item[0]['item_code'];
		$description = $item[0]['description'];
		$particular = $item[0]['particular'];
		$unit = $item[0]['unit'];
		$unit_cost = $item[0]['unit_price'];
		$id = $item[0]['id'];
		$category = $item[0]['category'];
		$classification = "";
		$classification_name = "";
		$reorder = $item[0]['reorder_level'];
		$qty = $item[0]['qty'];
		$location = $item[0]['location'];
		$stock_location = $item[0]['stock_location'];
	}



?>

<!--
<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
<script src="<?php echo LINK.'assets/toastr/toastr.js'; ?>"></script>   
--->


	<script type="text/javascript">

		$(document).ready(function () {

				$( "#autocomplete" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>inventory/item_data",
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
						$("#qty").focus();
						return false;
					}
				});
				
				$( "#pono" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>inventory/po_data",
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
						$( "#pono" ).val( ui.item.label );
						$( "#selpono" ).val( ui.item.value );
						$( "#po_id" ).val( ui.item.value );
						$( "#pono" ).prop("disabled", true);
						$('#is_notice_of_discrepancy').prop('checked', false);
						
						//var pid = ui.item.value;
						//alert(pid);
						//alert(pid);
						//do ajax here to retrieve po details
						/*
						$.post('<?php echo HTTP_PATH."inventory/getPoDetail/"; ?>',
							{ 'id':pid },
								function(result) {                                     
										
										$('#selected').html(result);
										
									}
						);
						*/
						//document.getElementById("autocomplete").value = ui.item.label;
						
						return false;
					}
				});
				
				
 
       $( "#delivery_date" ).datepicker();


       $('#is_notice_of_discrepancy').change(function() {

       		var po = document.getElementById('po_id').value;

       		 if(this.checked && po>0) {
	       		
		        $.ajax({
				     type:"POST",
				     url:"<?php echo HTTP_PATH?>/inventory/checkNoticeOfDiscrepancy/"+po,
				     success:function(data){
				        var nod = $.parseJSON(data);
				       
				        if(nod==null){
				        	msg = 'Notice of Discrepancy cannot be found for this PO.<br>';
				        	toastr['warning'](msg, "ABAS says:");
				        	$('#is_notice_of_discrepancy').prop('checked', false);
				        }

				     },
				     error: function (request, status, error) {
				        alert(request.responseText);
				     }

				  });
		    }

	     });

       });

		function addMe(id,qty){



		}

		function delItem(id){

			var se = document.getElementById('sels').value;
			var loc = document.getElementById( 'location' ).value;

			de = se.replace(id, "");

			document.getElementById('sels').value = de;
			se2= document.getElementById('sels').value;
			//alert(se2);

			$.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
				  { 'id':se2,'location':loc,'action':'del' },
						function(result) {
							//alert(de);
							// clear any message that may have already been written
							$('#selected').html(result);
						}
			);


		}


		function submitMe(){

			var sinum = document.getElementById('sales_invoice_no').value;
			
			var ddate = document.getElementById('delivery_date').value;			
			
			var loc = document.getElementById('location').value;
			
			var sitems = document.getElementById('sels').value;
			
			var po = document.getElementById('pono').value;
			
			var selpo = document.getElementById('selpono').value;
			
			//chk of items coming from manual entry or from po
			//if(sitems ==''){
				//sitems = document.getElementById('selspo').value;
			//}

			
			var msg = '';
			if(sinum == ''){
				msg += 'Please enter sales invoice number.<br>';
				document.getElementById('sales_invoice_no').focus();
			}
			if(ddate == ''){
				msg += 'Please enter delivery date.<br>';
				document.getElementById('delivery_date').focus();		
			}
			if(selpo == ''){				
				msg += 'Select PO from suggested list and press enter.<br>';
				document.getElementById('pono').focus();
			}
			if(sitems == ''){         
				msg += 'Please enter delivered items.<br>'; 
				document.getElementById('pono').focus(); 
		  	}

			if(msg!=''){
                toastr['warning'](msg, "ABAS says:");
                return false;
            }else{
				document.forms['delForm'].submit();
			}
		}

	</script>

<style>
#changeStatus {
	background-color: #DDD;
	float: left;
	position: absolute;
}
#changeStatus .form-group {
    margin: 15px;


}

.autocomplete {
    z-index: 5000;
}

.ui-autocomplete {
  z-index: 215000000 !important;
}
.ui-autocomplete {
  z-index:2147483647;
}

</style>

<!--
<link rel="stylesheet" href="awesomplete.css" />
<script src="awesomplete.js" async></script>
-->

							<div style="float:left; margin-left:0px; height:400px">
							<form class="form-horizontal" role="form" id="delForm" name="delForm"  action="<?php echo HTTP_PATH.'inventory/addDelivery'; ?>" method="post">		
                                    <div>
										<div class="panel panel-success" style="font-size:12px; width:850px;">
											<div class="panel-heading" role="tab" id="headingOne">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
												<strong>Receiving&nbsp;</strong>
											</div>

								
                                
											<div class="panel-body" role="tab" >
                                            	
                                                
								<?php echo $this->Mmm->createCSRF() ?>
												<div style="width:200px; margin-left:15px; float:left">
													<div class="form-group">
														<label  for="payee">PO Transaction Code#*:</label>
														<div>

                                                            <input type="text" class="form-control input-sm"  name="pono" id="pono" value="<?php echo $pono;  ?>" required/>
                                                           <input type="hidden" id="po_id" name="po_id" class='form-control' value="<?php echo $po_id?>" required>
														</div>
													</div>
													 <div class="form-group">
														<label for="voucher_no">Delivery Date*:</label>
														<div>
															<input class="form-control input-sm" type="text" name="delivery_date" id="delivery_date" value="<?php echo $delivery_date;  ?>" required />
														</div>
													</div>
													
                                                    <div class="form-group">
														<label for="voucher_no">Sales Invoice Number*:</label>
														<div>
															<input class="form-control input-sm" type="text" name="sales_invoice_no" id="sales_invoice_no" value="<?php echo $sales_invoice_no;  ?>" required/>
														</div>
													</div>
                                                   <div class="form-group">
														<label for="voucher_no">Delivery Receipt Number:</label>
														<div>
															<input class="form-control input-sm" type="text" name="delivery_no" id="delivery_no" value="<?php echo $delivery_no;  ?>" required/>
														</div>
													</div>
                                                    <div class="form-group">
														<label  for="payee">Remarks:</label>
														<div>

                                                            <input class="form-control input-sm" type="text" name="remark" id="remark" value="<?php echo $remark;  ?>" />
														</div>
													</div>
                                                    <div class="form-group">
														<input  type="checkbox" name="direct_del" id="direct_del" 
                                                        
                                                        onclick="                                                    		

                                                                if($('#direct_del').is(':checked')) {
                                                                    $('#delivered_to').show();
                                                                } else {
                                                                    $('#delivered_to').hide();
                                                                }             
                                                        
                                                        "/>&nbsp;&nbsp;
                                                        	<label  for="direct_del">  Check if direct delivery</label>
														

                                                            
														
													</div>
                                                    
                                                    <div class="form-group" id="delivered_to" style="display:none">
                                                        <label for="issued_for">Delivered to:</label>
                                                        <div>
                                
                                                            <select class="form-control input-sm" name="issued_for" id="issued_for">
                                                                <option></option>
                                                                <option value="101">AVEGA TRUCKING</option>
                                                                <?php
                                
                                                                    foreach($vessels as $vessel){ ?>
                                                                <option value="<?php echo $vessel->id; ?>"><?php echo $vessel->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            
                                                        </div>
                                                    </div>

                                                     <div class="form-group">
														<input  type="checkbox" name="is_notice_of_discrepancy" id="is_notice_of_discrepancy" value="1"
                                                         onclick="    
                                                                if($('#is_notice_of_discrepancy').is(':checked')) {
                                                                    $('#notice_discrepancy').show();
                                                                } else {
                                                                    $('#notice_discrepancy').hide();
                                                                }    
                                                                "/>
                                                        &nbsp;&nbsp;
                                                        <label for="notice_of_discrepancy"> Notice of Discrepancy</label>
													</div>

													 <!--<div class="form-group" id="notice_discrepancy" style="display:none">
                                                        <input type="text" id="notice_of_discrepancy_id" name="notice_of_discrepancy_id" class="form-control input-sm" readonly>
                                                    </div>-->
                                                    
													<div class="form-group" style="display:none">
														<label  for="payee">Supplier:</label>
														<div>

                                                            <input class="form-control input-sm" type="text" name="supplier" id="supplier" value="<?php echo $supplier;  ?>" />
                                                           

														</div>
													</div>

												</div>
                                                 			<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                                            <input type="hidden" id="selitem" name="selitem" />
                                                            <input type="hidden" id="selpono" name="selpono" />
                                                            <input type="hidden" id="sels" name="sels" />
                                                            <input type="hidden" id="location" name="location" value="<?php echo $location ?>" />   
                                                                                       
                                                <!-- End of left div-->
                                                	
                                                    
                                           		
												<div style="width:200px; margin-left:210px; margin-top:-10px;">                               									
                                                	<div class="jumbotron" style=" width:560px;height:390px; margin-top:20px; margin-left:35px; border:thin  #CCCCCC solid">
                                                		<div style="width:560px; height:50px; margin-top:-30px; margin-left:10px; display:block">
                                                            <label>Select Item:&nbsp</label><input id="autocomplete" style="width: 350px" title="Select Items">
                                                            &nbsp;
                                                            <label>Qty:</label> <input type="number" id="qty" name="qty" style="width:65px" onkeypress="
                                                            	if(event.keyCode == 13){
        				var ids   = new Array();
                        var s = document.getElementById( 'selitem' ).value;
                        var its = document.getElementById( 'sels' ).value;
                        var q = this.value;
                        var loc = document.getElementById( 'location' ).value;
                        var po = document.getElementById( 'po_id' ).value;

                        if(s == ''){
                        	toastr['warning']('Please select item.', 'ABAS says:');
                            return false;
                        }else if(q==''){
                        	toastr['warning']('Please enter quantity.', 'ABAS says:');
                            return false;
                        }else{
                        	                                                                                   
                             //Check if item is already added
                               	var arString = new Array();
                                
                                var arString = its.split(',');
                               	
                                if(arString.length > 1){

                                	
                                    for(i=0;i < arString.length;  i++){                                                                                   
                                		
                                		var p = arString[i].substring(0, arString[i].indexOf('|'))

                                		if(p==s){
                                    		toastr['warning']('Sorry, but you have already added that on the list.', 'ABAS says:');
                                            this.value = '';
                                            document.getElementById('autocomplete').value ='';
                                            document.getElementById('autocomplete').focus();
                                            return false;     
                                		}
                                          	
                                    }
                                    
                                    
                                }
                                                                                                                                                                                                                                                                            
                               //Check if item entered is in PO
                               $.post('<?php echo HTTP_PATH."inventory/checkPOItem/"; ?>',
                                      { 'id':ids,'pono':po, 'qty':q, 'item_id':s },
                                            function(result) {
                                                //
                                                //alert(result);
                                                // clear any message that may have already been written
                                                if(result == 1){
                                                
                                                    vals = s+'|'+q+','+its;
                                                    ids.push(vals);

                                                    document.getElementById( 'sels' ).value= ids;
                                                                                                           
                                                    $.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
                                                      { 'id':ids,'pono':po,'location':loc,'action':'receive' },
                                                            function(result) {
                                                                //alert(result);
                                                                // clear any message that may have already been written
                                                                $('#selected').html(result);
                                                            }
                                                    );
                                                }else if(result == 0){

                                                	if($('#is_notice_of_discrepancy').is(':checked')){
	                                                  
	                                                   vals = s+'|'+q+','+its;
	                                                    ids.push(vals);

	                                                    document.getElementById( 'sels' ).value= ids;
	                                                                                                           
	                                                    $.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
	                                                      { 'id':ids,'pono':po,'location':loc,'action':'receive' },
	                                                            function(result) {
	                                                                //alert(result);
	                                                                // clear any message that may have already been written
	                                                                $('#selected').html(result);
	                                                            }
	                                                    );
	                                                }else{
	                                                	 toastr['warning']('Quantity entered exceeds quantity ordered or balance.', 'ABAS says:');

	                                                }

                                                    //  return false;
                                                
                                                }else if(result == 2){
                                                     toastr['warning']('Item entered is not included in this PO.', 'ABAS says:');
                                                    //return false;
                                                
                                                }else if(result == 3){
                                                     toastr['warning']('This item is fully delivered.', 'ABAS says:');
                                                    //return false;
                                                
                                                }
                                                
                                                
                                                document.getElementById( 'qty' ).value ='';
                                                document.getElementById( 'selitem' ).value ='';
                                                document.getElementById( 'autocomplete' ).value ='';
                                                document.getElementById( 'autocomplete' ).focus();

                                                                                                                                        });
                           
                           
                           
                                                                                }
                                                                }
                                                            " />                                      						&nbsp;
                                                            <span style="display:none">
                                                            Unit Price: <input type="text" id="unit_price" name="unit_price"  style="width:95px" />														</span>
                                                            &nbsp;
                                                            <input type="button" id="addMe" style="display:none" onclick="

                                                                                var ids   = new Array();
                                                                                var s = document.getElementById( 'selitem' ).value;
                                                                                var its = document.getElementById( 'sels' ).value;
                                                                                var q = $( '#qty' ).val();

                                                                                if(s == ''){
                                                                                	 toastr['warning']('Please select item.', 'ABAS says:');
                                                                                    return false;
                                                                                }else if(q==''){
                                                                                	toastr['warning']('Please enter quantity.', 'ABAS says:');
                                                                                    return false;
                                                                                }else{
                                                                                	//do ajax call to add item
                                                                                	vals = s+'|'+q+','+its;                                                              						ids.push(vals);
                                                                                    document.getElementById( 'sels' ).value= ids;
                                                                                    document.getElementById( 'qty' ).value ='';
                                                                                    document.getElementById( 'selitem' ).value ='';
                                                                                    document.getElementById( 'autocomplete' ).value ='';
                                                                                    document.getElementById( 'autocomplete' ).focus();
                                                                                }


                                                                                " value="Add">
                                                        </div>


                                                        	<div id="selected" style="width:540px;margin-top:-10px; margin-left:10px; height: 300px; overflow: auto">
                                                                <table id='datatable-responsive' style='font-size:11px' class='table table-bordered table-striped table-hover' cellspacing='0'>
                                                                    <thead>
                                                                        <tr>
                                                                            <th width='15%'>Item Code</th>
                                                                            <th width='15%'>Item Name</th>
                                                                            <th width='20%'>Particular</th>
                                                                            <th width='5%'>Qty</th>
                                                                            <th width='5%'>Unit</th>
                                                                            <th width='15%'>Unit Price</th>
                                                                            <th width='20%'>Line Total</th>
                                                                            <th width='5%'></th>
                                                                        </tr>
                                                                    </thead>	
								
                                                                    <?php

                                                                    ?>
                                                                            <tr>
                                                                                <td align="center"><?php  //echo  ?></td>
                                                                                <td align="left"><?php  //echo  ?></td>
                                                                                <td align="center"><?php // echo  ?></td>
                                                                                <td align="center"><?php  //echo  ?></td>
                                                                                <td align="right"><?php  //echo  ?></td>
                                                                                <td align="right"><?php  //echo  ?></td>
                                                                                <td align="right"><?php // echo  ?></td>
                                                                                <td align="center">
                                                                                	<a href="##">
                                                                                	<i class="graphicon graphicon-remove"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                    <?php
                                                                            //$total = $total + $lineTotal;
                                                                           // }
                                                                       // }
                                                                    ?>

                                                                    <tr align="right">
                                                                        <td colspan="5"></td>
                                                                        <td>Total:</td>
                                                                        <td>Php <?php //echo number_format($total,2); ?></td>
                                                                        <td></td>
                                                                    </tr>

                                                                </table>

                                                        </div>
                                                	</div>
</form>   
												</div>
   
												<br>
												<span style="float:right; margin-right:40px; margin-top:-20px">
													<input class="btn btn-success btn-m" type="button"  value="Save" onclick="javascript:submitMe()" id="submitbtn" >
													<input type="button" class="btn btn-danger btn-m" value="Cancel" data-dismiss="modal" />
												</span>
												
                                                
                                                
                                                
											</div>
										</div>
									</div>
                                
