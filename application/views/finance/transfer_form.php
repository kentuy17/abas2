

<?php
	//var_dump($vessels[0]->id); exit;
	//var_dump($units);exit;  ssi1@globe.com.ph, noli - 09178707140

	$id = "";
	$items = "";

	$issuance_no = "";
	$transfered_by = "";
	$from_location= "";
	$to_location= "";
	$remark = "";

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


	
	$user_location = $_SESSION['abas_login']['user_location'];

?>



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
		});

		function addMe(id,qty){



		}

		function delItem(id){

			var se = document.getElementById('sels').value;
			var loc = document.getElementById( 'loc' ).value;

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

			var from_location = document.getElementById('loc').value;
			var to_location = document.getElementById('to_location').value;
			var transfered_by = document.getElementById('transfered_by').value;
			var sitems = document.getElementById('sels').value;

			if(from_location ==''){
				alert('Problem with the inventory origin. Please consult administrator');				
				return false;
			}else if(to_location == ''){
				alert('Please select transfer location.');
				document.getElementById('to_location').focus();
				return false;
			}else if(transfered_by == ''){
				alert('Please enter transfer by.');
				document.getElementById('transfered_by').focus();
				return false;
			}else if(sitems == ''){
				alert('Please enter items.');
				document.getElementById('autocomplete').focus();
				return false;
			}else{
				document.forms['tranForm'].submit();
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


</style>

<!--
<link rel="stylesheet" href="awesomplete.css" />
<script src="awesomplete.js" async></script>
-->



<form class="form-horizontal" role="form" id="tranForm" name="tranForm"  action="<?php echo HTTP_PATH.'inventory/addTransfer'; ?>" method="post" enctype='multipart/form-data'>
								<?php echo $this->Mmm->createCSRF() ?>

								<div style="width:850px; float:left; margin-left:0px; margin-top:55px">
									<div>
										<div class="panel panel-warning" style="font-size:12px; width:850px; height:545px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Item Transfer&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab" >
												<div style="width:200px; margin-left:20px; float:left">

													

                                                    <div class="form-group">
														<label  for="amount">To (Destination):</label>
														<div>
															<select class="form-control input-sm" name="to_location" id="to_location">
																<option value="<?php echo $to_location; ?>"><?php echo $to_location; ?></option>
																<?php 
																	
																	foreach($locals as $loc){ 
																		
																		if($loc['location_name'] != $user_location){														
																
																?>	
																	
                                                                    
                                                                	<option value="<?php echo $loc['location_name'] ?>">
																		<?php echo $loc['location_name'] ?>
                                                                    </option>
                                                                    
                                                                <?php 	
																		} 
																
																	} 
																
																?>	
															</select>
														</div>
													</div>

                                                    <div class="form-group">
														<label for="voucher_no">Transfered By:</label>
														<div>
															<input class="form-control input-sm" type="text" name="transfered_by" id="transfered_by" value="<?php echo $transfered_by;  ?>" />
														</div>
													</div>

                                                    <div class="form-group">
														<label  for="payee">Purpose:</label>
														<div>

                                                            <input class="form-control input-sm" type="text" name="remark" id="remark" value="<?php echo $remark;  ?>" />
														</div>
													</div>


												</div>
                                                	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                                    <input type="hidden" id="selitem" name="selitem" />
                                                    <input type="hidden" id="sels" name="sels" />
                                                    <input type="hidden" id="is_avail" name="is_avail" />
                                                    <input type="hidden" id="loc" name="loc" value="<?php echo $user_location; ?>" />
                                                    

                                           </form>
												<div style="width:200px; margin-left:0px; margin-top:-20px; float:left">                               						<div class="jumbotron" style="width:560px; height:390px; margin-top:20px; margin-left:35px">
                                                		<div style="width:560px; height:50px; margin-top:-30px; margin-left:20px">

                                                            Select Item:<input id="autocomplete" title="Select Items">

                                                            &nbsp;
                                                           Qty: <input type="number" id="qty" name="qty" style="width:65px" onchange="
												
                                                var ids   = new Array();

												var s = document.getElementById( 'selitem' ).value;
												var its = document.getElementById( 'sels' ).value;
												var loc = document.getElementById( 'loc' ).value;

												var q = $( '#qty' ).val();

												if(s == ''){
													alert('Please select item.');
													document.getElementById( 'autocomplete' ).focus();
													return false;
												}else if(q==''){
													alert('Please enter quantity.');
													document.getElementById( 'qty' ).focus();
													return false;
												}else if(loc==''){
													alert('Please select transfer location.');
													document.getElementById( 'to_location' ).focus();
													return false;
												}else{

													//chk if qty is available
														
													 $.post('<?php echo HTTP_PATH."inventory/chkQty/"; ?>',
														  { 'id':s,'location':loc,'qty':q },
																function(result) {
																	//alert(result);
                                                                    //alert(q);
                                                                    
                                                    				if(parseInt(result) > parseInt(q)){
                                                                    	
                                                                        vals = s+'|'+q+','+its;
                                                                        ids.push(vals);
                
                                                                        document.getElementById( 'sels' ).value= ids;
                                                                        document.getElementById( 'qty' ).value ='';
                                                                        document.getElementById( 'selitem' ).value ='';
                                                                        document.getElementById( 'autocomplete' ).value ='';
                                                                        document.getElementById( 'autocomplete' ).focus();
                
                                                                       // var sels = document.getElementById('sels').value;
                                                                         //alert(ids);
                                                                            $.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
                                                                              { 'id':ids,'location':loc,'action':'issuance' },
                                                                                    function(result) {
                                                                                        //alert(result);
                                                                                        // clear any message that may have already been written
                                                                                        $('#selected').html(result);
                                                                                    }
                                                                            );
                                                                        
                                                                        
                                                                    }else{
                                                                    	alert('Qty is not sufficient or is not available.');
                                                                        document.getElementById( 'qty' ).value ='';
                                                                        document.getElementById( 'selitem' ).value ='';
                                                                        document.getElementById( 'autocomplete' ).value ='';
                                                                        document.getElementById( 'autocomplete' ).focus();
																		return false;
                                                                    }                
                                                                    
																}
														);

													
												}
							" />                                    					&nbsp;
                                                            <span style="display:none">
                                                            Unit Price: <input type="text" id="unit_price" name="unit_price"  style="width:95px" />														</span>
                                                            &nbsp;
                                                            <input type="button" id="addMe" style="display:none" onclick="

                                                                                var ids   = new Array();
                                                                                var s = document.getElementById( 'selitem' ).value;
                                                                                var its = document.getElementById( 'sels' ).value;
                                                                                var q = $( '#qty' ).val();

                                                                                if(s == ''){
                                                                                	alert('Please select item.');
                                                                                    return false;
                                                                                }else if(q==''){
                                                                                	alert('Please enter quantity.');
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


                                                        	<div id="selected" style="width:540px;margin-top:-10px; margin-left:10px">
                                                                <table class='table table-striped table-bordered table-hover table-condensed' data-toggle='table' style='font-size:12px; '>
                                                                    <tr align='center'>
                                                                        <td width='15%'>Item Code</td>
                                                                        <td width='35%'>Item Name</td>
                                                                        <td width='5%'>Qty</td>
                                                                        <td width='5%'>Unit</td>
                                                                        <td width='15%'>Unit Price</td>
                                                                        <td width='20%'>Line Total</td>
                                                                        <td width='5%'>*</td>

                                                                    </tr>
                                                                    <?php

                                                                    ?>
                                                                            <tr>
                                                                                <td align="center"><?php  //echo  ?></td>
                                                                                <td align="left"><?php  //echo  ?></td>
                                                                                <td align="center"><?php // echo  ?></td>
                                                                                <td align="center"><?php  //echo  ?></td>
                                                                                <td align="right"><?php  //echo  ?></td>
                                                                                <td align="right"><?php // echo  ?></td>
                                                                                <td align="center">
                                                                                	<a href="##">
                                                                                	<i class="graphicon graphicon-remove">x</i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                    <?php
                                                                            //$total = $total + $lineTotal;
                                                                           // }
                                                                       // }
                                                                    ?>

                                                                    <tr align="right">
                                                                        <td colspan="4"></td>
                                                                        <td>Total:</td>
                                                                        <td>Php <?php //echo number_format($total,2); ?></td>
                                                                        <td></td>
                                                                    </tr>

                                                                </table>

                                                        </div>
                                                	</div>

												</div>


												<span style="float:right; margin-right:40px; margin-top:400px">
													<input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:submitMe()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
													<input class="btn btn-danger btn-sm"  value="Cancel" onclick="javascript:newEntry()" style="width:100px; margin-left:10px; margin-top:10px">
												</span>

											</div>
										</div>
									</div>


