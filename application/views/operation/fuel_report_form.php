<?php
	//var_dump($ports); exit;
	//var_dump($units);exit;  ssi1@globe.com.ph, noli - 09178707140
	$issuance_no = "";
	$request_date = "";
	$request_no = "";
	$issued_to = "";
	$issued_for = "";
	$id = "";
	$items = "";
	$location= $_SESSION['abas_login']['user_location'];
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



?>







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



<form class="form-horizontal" role="form" id="repForm" name="repForm"  action="<?php echo HTTP_PATH.'operation/addFuelReport'; ?>" method="post" enctype='multipart/form-data'>
<?php echo $this->Mmm->createCSRF() ?>

<div style="width:850px; float:left; margin-left:0px; margin-top:55px">
	<div>
		<div class="panel panel-success" style="font-size:12px; width:850px; height:545px">
			<div class="panel-heading" role="tab" id="headingOne">
				<strong>Vessel Fuel Report</strong>
			</div>
			<div class="panel-body" role="tab" >
				<div style="width:200px; margin-left:20px; float:left">

					
					<div class="form-group">
						<label  for="payee">Select Vessel:</label>
						<div>

							<select class="form-control input-sm" name="vessel" id="vessel">
								
								<option></option>
								<?php

									foreach($vessels as $vessel){ ?>
								<option value="<?php echo $vessel->id; ?>"><?php echo $vessel->name; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="voucher_date">Origin:</label>
						<div>

							<select class="form-control input-sm" name="from_port" id="from_port">
								<option value="<?php //echo $issued_for; ?>"><?php //echo $issued_for; ?></option>
								<option></option>
								<?php

									foreach($ports as $port){ ?>
								<option value="<?php echo $port->from_port; ?>"><?php echo $port->from_port; ?></option>
								<?php } ?>
							</select>
							
						</div>
					</div>
                    <div class="form-group" >
						<label for="voucher_no">Destination:</label>
						<div>
							<select class="form-control input-sm" name="to_port" id="to_port">
								<option value="<?php //echo $issued_for; ?>"><?php //echo $issued_for; ?></option>
								<option></option>
								<?php

									foreach($ports as $port){ ?>
								<option value="<?php echo $port->from_port; ?>"><?php echo $port->from_port; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					

					<!---
					<div class="form-group">
						<label  for="amount">Issued From (Source):</label>
						<div>
							<select class="form-control input-sm" name="location" id="location">
								<option value="<?php //echo $location; ?>"><?php //echo $location; ?></option>
								<option value="Tayud">Tayud</option>
								<option value="NRA">NRA</option>
								<option value="Makati">Makati</option>
								<option value="Tacloban">Tacloban</option>
								<option value="Direct Delivery">Direct Delivery</option>
							</select>
						</div>
					</div>
					--->
					<div class="form-group">
						<label  for="payee">Voyage No.:</label>
						<div>

							<input class="form-control input-sm" type="number" name="voyage_no" id="voyage_no" value="<?php //echo $remark;  ?>" />
						</div>
					</div>

                    <div class="form-group">
						<label  for="payee">Remark:</label>
						<div>

							<input class="form-control input-sm" type="text" name="remark" id="remark" value="<?php //echo $remark;  ?>" />
						</div>
					</div>


				</div>
				

		  
				<div style="width:200px; margin-left:0px; margin-top:-20px; float:left">
					<div class="jumbotron" style="width:560px; height:390px; margin-top:20px; margin-left:35px">
						<div style="width:560px; height:50px; margin-top:-30px; margin-left:20px">
							<label for="autocomplete">Fuel Reading (in liters):</label>
							<input id="fuel_reading" name="fuel_reading" type="number" title="Fuel Reading" style="width:100px">
							
                              	                  
                           		
                            
    						<div>
                                <div class="radio-inline" style="width:400px" align="left">
                                  <label><input type="radio" name="ftype" id="ftype" value="departure">Departure</label>
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <label><input type="radio" name="ftype" id="ftype" value="arrival">Arrival</label>
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <label><input type="radio" name="ftype" id="ftype" value="portops">Port Operation</label>
                                 
                                  <span style="position:absolute; margin-right:-140px; margin-top:-20px">
                                    <input class="btn btn-success btn-xs" type="button"  value="Submit" 
                                    	onclick="
                                        		
                                                var v = document.getElementById('vessel').value;                                        
                                                var fport = document.getElementById('from_port').value;
                                                var tport = document.getElementById('to_port').value;			
                                                var voy = document.getElementById('voyage_no').value;
                                                var fread = document.getElementById('fuel_reading').value;
                                                var ftype = $( 'input:checked' ).val()
                                                                                              
                                                if(v ==''){
                                                    alert('Please select vessel');
                                                    document.getElementById('vessel').focus();
                                                    return false;
                                                }else if(fport == ''){
                                                    alert('Please select origin.');
                                                    document.getElementById('from_port').focus();
                                                    return false;
                                                }else if(tport == ''){
                                                    alert('Please select destination.');
                                                    document.getElementById('to_port').focus();
                                                    return false;
                                                }else if(voy == ''){
                                                    alert('Please enter voyage number.');
                                                    document.getElementById('voyage_no').focus();
                                                    return false;
                                                }else if(fread == ''){
                                                    alert('Please enter fuel reading.');
                                                    document.getElementById('fuel_reading').focus();
                                                    return false;
                                                }else if(ftype == ''){
                                                    alert('Please select type of reading.');
                                                    document.getElementById('type').focus();
                                                    return false;				
                                                }else{
                                                    document.forms['repForm'].submit();
                                                }
                                        
                                        
                                        " id="submitbtn" style="width:70px; margin-left:30px; margin-top:10px">
                                    
                                </span>   
                                </div>
                            </div>    
                            
                        
                           
												
							
						</div>

							<div id="log"></div>
							
					</div>

				</div>

 </form>
				

			</div>
		</div>
	</div>


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

			var v = document.getElementById('vessel').value;
			var fport = document.getElementById('from_port').value;
			var tport = document.getElementById('to_port').value;			
			var voy = document.getElementById('voyage_no').value;
			var fread = document.getElementById('fuel_reading').value;
			var type = document.getElementById('type').value;

			if(v ==''){
				alert('Please select vessel');
				document.getElementById('vessel').focus();
				return false;
			}else if(fport == ''){
				alert('Please select origin.');
				document.getElementById('from_port').focus();
				return false;
			}else if(tport == ''){
				alert('Please select destination.');
				document.getElementById('to_port').focus();
				return false;
			}else if(voy == ''){
				alert('Please enter voyage number.');
				document.getElementById('voyage_no').focus();
				return false;
			}else if(fread == ''){
				alert('Please enter fuel reading.');
				document.getElementById('fuel_reading').focus();
				return false;
			}else if(type == ''){
				alert('Please select type of reading.');
				document.getElementById('type').focus();
				return false;				
			}else{
				document.forms['repForm'].submit();
			}
		}
	
</script>
