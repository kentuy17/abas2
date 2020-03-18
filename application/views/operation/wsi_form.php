<?php
//echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';
	$id = '';
	$wsr_no = '';
	$issue_date = '';
	$waybill_no = '';
	$wsi_no = '';
	$reference_no = '';
	$from_location = '';
	$from_location_name = '';
	
	$to_location = '';
	$to_location_name = '';
	
	$region = '';
	$region_name = '';
	
	$transaction_type = '';
	$transaction_type_name = '';
	
	$truck_plate_no = '';
	$bags = '';
	$gross_weight = 0;
	$wsi_gross_weight = 0;
	$net_weight = 0;
	
	$variety = '';
	$age = '';
	$stock_condition = '';
	
 	$vessel_name= '';	
	$vessel_id= '';
	$voyage_no = '';
	
	$hauler = '';
	$hauler_name = '';

 if(isset($transaction)){
 	
	// var_dump($transaction);
 	
	$id = $transaction->id;
	$wsr_no = $transaction->wsr_no;
	$wsi_no = $transaction->wsi_no;
	$waybill_no = $transaction->waybill_no;
	
	$issue_date = ($transaction->issue_date !='')?date('m-d-Y',strtotime($transaction->issue_date)): '';
	$reference_no = $transaction->reference_no;
	
	$from_location = $transaction->from_location;
	$f = $this->Operation_model->getTruckingLocation($transaction->from_location);
	$from_location_name = $f->name;
	
	$to_location = $transaction->to_location;
	$l = $this->Operation_model->getTruckingLocation($transaction->to_location);
	$to_location_name = $l->name;
	
	
	$region = $transaction->region;	
	$region_name = ($transaction->region !='') ? $this->Abas->getRegion($transaction->region) : '';	;
	
	$transaction_type = $transaction->transaction_type;
	$t =  $this->Operation_model->getTransactionType($transaction->transaction_type);
	$transaction_type_name = $t->transaction_name;	
	
	
	$hauler = $transaction->service_provider;
	$h = $this->Operation_model->getServiceProvider($transaction->service_provider);
	$hauler_name = (isset($h)) ? $h->company_name : '';		
	
	$truck_plate_no = $transaction->truck_plate_no;
	$bags = $transaction->bags;
	
	$gross_weight = $transaction->gross_weight;	
	$net_weight = $transaction->net_weight;
	
	$variety = $transaction->variety;
	$age = $transaction->age;
	$stock_condition = $transaction->stock_condition;
	
	
	$v = $this->Abas->getVessel($transaction->vessel_id);		
	$vessel_name = (isset($v)) ? $v->name : '';
	$vessel_id= $transaction->vessel_id;
	$voyage_no = $transaction->voyage_no;
	
 }
?>
<style>
.popover {
	max-width: 600px;
	width: auto;
}
</style>
<div class='panel panel-primary'>
	<div class='panel-heading' style="font-size:14px"><strong>WSI Entry</strong></div>
	<div class='panel-body'>
		
        <br />
         <form class="form-horizontal form-label-left" name="wsrForm" method="post" action="<?php echo HTTP_PATH.'operation/addTransaction'; ?>">
					<?php echo $this->Mmm->createCSRF() ?>
                    <div style="float:left">
                    
                        <div style="width:500px">	
                          
                           <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Reference #:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" name="reference_no" id="reference_no" class="form-control" required="required" value="<?php echo $reference_no ?>">
                             
                            </div>
                          </div>
                           <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">District:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" name="reference_no" id="reference_no" class="form-control" required="required" value="<?php echo $reference_no ?>">
                             
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Warehouse:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <select class="form-control" name="from_location" id="from_location" required="required">
                              	<option value="<?php echo $from_location ?>"><?php echo $from_location_name ?></option>
                                <?php foreach($warehouses as $wh){ ?>
                                <option value="<?php echo $wh['id'] ?>"><?php echo $wh['name'] ?></option>
                                <?php } ?>
                              </select>                     
                            </div>
                          </div>
                         
                          <div class="form-group" style="display:none">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Transaction:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                             
                              <select class="form-control" name="transaction_type" id="transaction_type">
                              		<option value="Handling">Handling</option>
                                    <?php foreach($transaction_types as $transaction){ ?>
                                    	<option value="<?php echo $transaction['id'] ?>"><?php echo $transaction['transaction_name'] ?></option>
                                    <?php } ?>
                              </select>
                              
                            </div>
                          </div>                
                          
                      </div>
                 </div>
                 <div style="float:right; margin-right:10px">
                 </div>
                 
                  		<input type="hidden" class="form-control" name="id" id="id" value="<?php echo $id ?>">
                      	<input type="hidden" class="form-control" name="type" id="type" value="WSI">
                 </form>
                 
                  
                 <div style="margin-top:10px">   
                        <div style="width:570px">
                      	  	
                            <div style="width:200px; margin-left:0px; margin-top:-20px; float:left">
					<div class="jumbotron" style="width:900px; height:390px; margin-top:20px; margin-left:35px">
						<div style="width:95%; height:50px; margin-top:-30px; margin-left:20px">
							<table width="95%" cellpadding="10" cellspacing="10" border="5">
                            	<tr>
                                	<td width="25%">WSI #</td>
                                    <td width="10%">Gross Weight</td>
                                    <td width="10%">Bags</td>
                                    <td width="10%">Moves</td>
                                    <td width="20%">Variety</td>
                                    <td width="25%">Date</td>
                                </tr>
                                <tr>
                                	<td><input type="text" name="wsi_no" id="wsi_no" style="width:100px" /></td>
                                    <td><input type="text" name="gweight" id="gweight" /></td>
                                    <td><input type="text" name="bags" id="bags" /></td>
                                    <td><input type="text" name="moves" id="moves" /></td>
                                    <td><input type="text" name="variety" id="variety" /></td>
                                    <td><input type="text" name="wsi_date" id="wsi_date" /></td>
                                </tr>
                            </table>
                            
                            <label for="autocomplete">Select Item:</label>
							<input id="autocomplete" class="ui-autocomplete-input" title="Select Items" onblur="">
							Qty: <input type="number" id="qty" name="qty" style="width:65px" onchange="
												
                                                var ids   = new Array();

												var s = document.getElementById( 'selitem' ).value;
												var its = document.getElementById( 'sels' ).value;
												var loc = document.getElementById( 'location' ).value;

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
													alert('Please select source location.');
													document.getElementById( 'location' ).focus();
													return false;
												}else{

													//chk if qty is available
														
													 $.post('<?php echo HTTP_PATH."inventory/chkQty/"; ?>',
														  { 'id':s,'location':loc,'qty':q },
																function(result) {
																	//alert(result.returnValue);
																	// clear any message that may have already been written
																  	//document.getElementById( 'is_avail' ).value = result;          
                                                                    //var is = result;
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
																		return false;
                                                                    }                
                                                                    
																}
														);

													var isa = document.getElementById( 'is_avail' ).value;
													alert(is);
                                                    //alert(q);
													if(isa > q){

														//do ajax call to add item
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
														return false;
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

							<div id="log"></div>
							<div id="selected" style="width:540px;margin-top:20px; margin-left:10px">
								<table class='table table-striped table-bordered table-hover table-condensed' data-toggle='table' style='font-size:12px; '>
									<tr align='center' >
										<td width='15%'>Date</td>
										<td width='35%'>WSI #</td>
										<td width='5%'>Gross Weight</td>
										<td width='5%'>Bags</td>
										<td width='15%'>Moves</td>
										<td width='20%'>Variety</td>
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
                          
                      	</div>
                  </div>    
                      
                     

                      <div class="form-group">
                      	
                        <div class="col-md-9 col-md-offset-9" style="margin-top:20px">
                          <a href="<?php HTTP_PATH.'operation/wsr_view' ?>"<button type="button" class="btn btn-primary">Cancel</button></a>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>
					</div>
                    
                  </div>
                </div>
              </div>
       
	</div>
</div>
<script>
      $(document).ready(function() {
        $('#issue_date').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_4"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>


<script>
function validateEmail(email) {
	var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}
function validateRadio (radios)	{
	for (var i = 0; i < radios.length; i++)	{
		if (radios[i].checked) {return true;}
	}
	return false;
}
function checkautoform() {
	var msg="";
	//var patt1=/^[0-9]+$/i;
	var patt1=/^\d+(\.\d+)*$/i;
	var company0=document.forms.contract_entry.company0.value;
	if (company0==null || company0=="" || company0=="Company") {
		msg+="Company is required! <br/>";
	}
	var date_effective1=document.forms.contract_entry.date_effective1.value;
	if (date_effective1==null || date_effective1=="" || date_effective1=="Date Effective") {
		msg+="Date Effective is required!<br/>";
	}
	var charterer2=document.forms.contract_entry.charterer2.value;
	if (charterer2==null || charterer2=="" || charterer2=="Charterer") {
		msg+="Charterer is required! <br/>";
	}
	else {
		var chartererid=document.forms.contract_entry.charterer_id.value;
		if (chartererid==null || chartererid=="") {
			msg+="Please select a charterer from the dropdown! <br/>";
		}
	}
	var type3=document.forms.contract_entry.type.selectedIndex;
	if (type3==null || type3=="" || type3=="Type") {
		msg+="Type is required! <br/>";
	}
	var quantity5=document.forms.contract_entry.quantity5.value;
	if (quantity5==null || quantity5=="" || quantity5=="Quantity") {
		msg+="Quantity is required! <br/>";
	}
	else if (!patt1.test(quantity5)) {
		msg+="Only numbers are allowed in Quantity! <br/>";
	}
	var unit6=document.forms.contract_entry.unit.selectedIndex;
	if (unit6==null || unit6=="" || unit6=="Unit") {
		msg+="Unit is required! <br/>";
	}
	var amount7=document.forms.contract_entry.amount7.value;
	if (amount7==null || amount7=="" || amount7=="Total Amount") {
		msg+="Total Amount is required! <br/>";
	}
	else if (!patt1.test(amount7)) {
		msg+="Only numbers are allowed in Total Amount! <br/>";
	}
	var reference_no8=document.forms.contract_entry.reference_no8.value;
	var details9=document.forms.contract_entry.details9.value;
	if (details9==null || details9=="" || details9=="Details") {
		msg+="Details is required! <br/>";
	}
	if(msg!="") {
		toastr['warning'](msg,"ABAS Says");
		return false;
	}
	else {
		document.getElementById("contract_entry").submit(); return true;
	}
}
function checkchartererform() {
	var msg="";
	//var patt1=/^[0-9]+$/i;
	var patt1=/^\d+(\.\d+)*$/i;
	var company0=document.forms.charterer_entry.charterer_company.value;
	if (company0==null || company0=="" || company0=="Company") {
		msg+="Company is required! <br/>";
	}
	var charterer_contact_person=document.forms.charterer_entry.charterer_contact_person.value;
	if (charterer_contact_person==null || charterer_contact_person=="" || charterer_contact_person=="Contact Person") {
		msg+="Contact Person is required! <br/>";
	}
	if(msg!="") {
		toastr['warning'](msg,"ABAS Says");
		return false;
	}
	else {
		var dataString = {
			"company":document.forms.charterer_entry.charterer_company.value,
			"address":document.forms.charterer_entry.charterer_address.value,
			"city":document.forms.charterer_entry.charterer_city.value,
			"province":document.forms.charterer_entry.charterer_province.value,
			"country":document.forms.charterer_entry.charterer_country.value,
			"contact_no":document.forms.charterer_entry.charterer_contact_no.value,
			"fax_no":document.forms.charterer_entry.charterer_fax_no.value,
			"email":document.forms.charterer_entry.charterer_email.value,
			"website":document.forms.charterer_entry.charterer_website.value,
			"contact_person":document.forms.charterer_entry.charterer_contact_person.value,
			"position":document.forms.charterer_entry.charterer_position.value,
			"lead_person":document.forms.charterer_entry.charterer_lead_person.value,
			"tin_no":document.forms.charterer_entry.charterer_tin_no.value
		};
		toastr['info']("","Please wait...");
		$.ajax({
			type: "POST",
			url: "<?php echo HTTP_PATH; ?>home/encode/clients/insert ",
			data: dataString,
			cache: false,
			success: function(html) {
				toastr['success']("","Charterer Added!");
			},
			error: function(html) {
				toastr['error']("","Charterer Not Added!");
			}
		});
		$("#add_charterer").popover('hide');
	}
}
function numberAddCommas(val) {
	val = parseFloat(val).toFixed(2);
	var ret = val.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	if(ret=="NaN") {
		return "";
	}
	else {
		return ret;
	}
}
function numberRemoveCommas(nStr) {
	var ret = parseFloat(nStr.replace(/,/g,'')).toFixed(2);
	if(ret==="NaN") {
		return 0.00;
	}
	else {
		return ret;
	}
}
$(document).ready(function () {
	$( "#charterer2" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>home/autocomplete/clients/company",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr['warning']	=	"No charterer companies found";
            }
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$( this ).val( ui.item.label );
			$( "#charterer_id" ).val( ui.item.value );
			// taken from http://stackoverflow.com/questions/5918144/how-can-i-use-json-data-to-populate-the-options-of-a-select-box
			// populate select field from json array
			// $.each(json, function(i, value) {
				// $('#myselect').append($('<option>').text(value).attr('value', value));
			// });
			return false;
		}
	});
	$('#add_charterer').popover({
		html : true,
		title: function() {
		  return $("#add_charterer_head").html();
		},
		content: function() {
		  return $("#add_charterer_content").html();
		}
	});
});
</script>
