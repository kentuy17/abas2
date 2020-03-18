

<?php

	//var_dump($units);exit;  ssi1@globe.com.ph, noli - 09178707140
	$delivery_no = "";
	$delivery_date = "";
	$pono = "";
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

			var dno = document.getElementById('delivery_no').value;
			var ddate = document.getElementById('delivery_date').value;
			var supp = document.getElementById('supplier').value;
			var loc = document.getElementById('location').value;
			var sitems = document.getElementById('sels').value;

			if(dno ==''){
				alert('Please enter delivery receipt no.');
				document.getElementById('delivery_no').focus();
				return false;
			}else if(ddate == ''){
				alert('Please enter delivery date.');
				document.getElementById('delivery_date').focus();
				return false;
			}else if(supp == ''){
				alert('Please select supplier.');
				document.getElementById('supplier').focus();
				return false;
			}else if(loc == ''){
				alert('Please select delivery location.');
				document.getElementById('location').focus();
				return false;
			}else if(sitems == ''){
				alert('Please enter delivered items.');
				document.getElementById('autocomplete').focus();
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



<form class="form-horizontal" role="form" id="delForm" name="delForm"  action="<?php echo HTTP_PATH.'inventory/addDelivery'; ?>" method="post">
								<!--- waybill activity --->

								<div style="width:850px; float:left; margin-left:0px; margin-top:55px">
									<div>
										<div class="panel panel-success" style="font-size:12px; width:850px; height:545px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Receiving&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab" >
												<div style="width:200px; margin-left:20px; float:left">
													<div class="form-group">
														<label for="voucher_no">Delivery Receipt No.:</label>
														<div>
															<input class="form-control input-sm" type="text" name="delivery_no" id="delivery_no" value="<?php echo $delivery_no;  ?>" />
														</div>
													</div>
                                                    <div class="form-group">
														<label for="voucher_no">Delivery Date:</label>
														<div>
															<input class="form-control input-sm" type="date" name="delivery_date" id="delivery_date" value="<?php echo $delivery_date;  ?>" />
														</div>
													</div>
													<div class="form-group">
														<label  for="payee">PO#:</label>
														<div>

                                                            <input class="form-control input-sm"  name="pono" id="pono" value="<?php echo $pono;  ?>" />
														</div>
													</div>

													<div class="form-group">
														<label for="voucher_date">Supplier/Transferer:</label>
														<div>
															<select class="form-control input-sm" name="supplier" id="supplier">
																<option value="<?php echo $supplier; ?>"><?php echo $supplier; ?></option>
                                                                <option value="0001">Avegabros Integrated</option>
																<?php foreach($suppliers as $sup){ ?>
                                                                <option value="<?php echo $sup['id']; ?>"><?php echo $sup['name']; ?></option>
                                                                <?php } ?>
															</select>
                                                            <input class="form-control input-sm" type="hidden" name="location" id="location" value="<?php echo $location; ?>" />
														</div>
													</div>
													<div class="form-group" style="display:none">
														<label  for="amount">Delivery Location:</label>
														<div>
															<!---
                                                            <select class="form-control input-sm" name="location" id="location">
																<option value="<?php echo $location; ?>"><?php echo $location; ?></option>
																<option value="Tayud">Tayud</option>
                                                                <option value="NRA">NRA</option>
                                                                <option value="Makati">Makati</option>
                                                                <option value="Tacloban">Tacloban</option>
                                                                <option value="Direct Delivery">Direct Delivery</option>
															</select>
                                                            --->
														</div>
													</div>
                                                    <div class="form-group">
														<label  for="payee">Remark:</label>
														<div>

                                                            <input class="form-control input-sm" type="text" name="remark" id="remark" value="<?php echo $remark;  ?>" />
														</div>
													</div>


												</div>
                                                	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                                 	<input type="hidden" id="selitem" name="selitem" />
                                                    <input type="hidden" id="sels" name="sels" />

                                           </form>
												<div style="width:200px; margin-left:0px; margin-top:-20px; float:left">                               						<div class="jumbotron" style="width:560px; height:390px; margin-top:20px; margin-left:35px">
                                                		<div style="width:560px; height:50px; margin-top:-30px; margin-left:20px">

                                                            Select Item:<input id="autocomplete" title="Select Items">

                                                            &nbsp;
                                                            Qty: <input type="number" id="qty" name="qty" style="width:65px" onchange="
                                                            	 				var ids   = new Array();
                                                                                var s = document.getElementById( 'selitem' ).value;
                                                                                var its = document.getElementById( 'sels' ).value;
                                                                                var q = $( '#qty' ).val();
                                                                                var loc = document.getElementById( 'location' ).value;

                                                                                if(s == ''){
                                                                                	alert('Please select item.');
                                                                                    return false;
                                                                                }else if(q==''){
                                                                                	alert('Please enter quantity.');
                                                                                    return false;
                                                                                }else{
                                                                                	//do ajax call to add item
                                                                                	vals = s+'|'+q+','+its;
                                                                                    ids.push(vals);

                                                                                    document.getElementById( 'sels' ).value= ids;
                                                                                    document.getElementById( 'qty' ).value ='';
                                                                                    document.getElementById( 'selitem' ).value ='';
                                                                                    document.getElementById( 'autocomplete' ).value ='';
                                                                                    document.getElementById( 'autocomplete' ).focus();

                                                                                   //var sels = document.getElementById('sels').value;
                                                                                    //alert(ids);
                                                                                    $.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
                                                                                          { 'id':ids,'location':loc,'action':'receive' },
                                                                                                function(result) {
                                                                                                    //alert(result);
                                                                                                    // clear any message that may have already been written
                                                                                                    $('#selected').html(result);
                                                                                                }
                                                                                        );
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
													<input class="btn btn-default btn-sm"  value="Cancel" onclick="javascript:newEntry()" style="width:100px; margin-left:10px; margin-top:10px">
												</span>

											</div>
										</div>
									</div>


