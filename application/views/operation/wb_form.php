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
 	
	//var_dump($transaction);exit;
 	
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
	<div class='panel-heading' style="font-size:14px"><strong>WAYBILL Entry</strong></div>
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
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Waybill Number:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" class="form-control" name="waybill_no" id="waybill_no" required="required" value="<?php echo $waybill_no ?>">
                              
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Transaction:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                             
                              <select class="form-control" name="transaction_type" id="transaction_type" required="required">
                              		<option value="<?php echo $transaction_type ?>"><?php echo $transaction_type_name ?></option>
                                    <?php foreach($transaction_types as $transaction){ ?>
                                    	<option value="<?php echo $transaction['id'] ?>"><?php echo $transaction['transaction_name'] ?></option>
                                    <?php } ?>
                              </select>
                              
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Date  Issued:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                           
                              <input type="date" name="issue_date" id="issue_date" class="date-picker form-control col-md-7 col-xs-12" required="required" value="<?php echo $issue_date ?>">
                              <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Vessel:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <select class="form-control" name="vessel" id="vessel">
                              	<option value="<?php echo $vessel_id ?>"><?php echo $vessel_name ?></option>
                                <?php foreach($vessels as $vessel){ ?>
                                <option value="<?php echo $vessel->id ?>"><?php echo $vessel->name ?></option>
                                <?php } ?>
                              </select>                     
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Voyage #:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" class="form-control" name="voyage_no" id="voyage_no" value="<?php echo $voyage_no ?>" >
                             
                            </div>
                          </div>
                          
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Loading At:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <select class="form-control" name="from_location" id="from_location" required="required">
                              	<option value="<?php echo $from_location ?>"><?php echo $from_location_name ?></option>
                                <?php foreach($warehouses as $wh){ ?>
                                <option value="<?php echo $wh['id'] ?>"><?php echo $wh['name'] ?></option>
                                <?php } ?>
                              </select>                     
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Unloading At:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <select class="form-control" name="to_location" id="to_location" required="required">
                              	<option value="<?php echo $to_location ?>"><?php echo $to_location_name ?></option>
                                <?php foreach($warehouses as $wh){ ?>
                                <option value="<?php echo $wh['id'] ?>"><?php echo $wh['name'] ?></option>
                                <?php } ?>
                              </select>  
                              
                              
                            </div>
                          </div>
                          
                          
                          
                      </div>
                 </div>
                 
                 <div style="float:right; margin-right:10px">
                    
                        <div style="width:570px">
                      	  	
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Number of Bags:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="number" class="form-control" name="bags" id="bags" required="required" value="<?php echo $bags ?>" >
                              <span class="form-control-feedback right" aria-hidden="true">bags</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Gross Weight:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" class="form-control" name="gross_weight" id="gross_weight" value="<?php echo $gross_weight ?>" >
                              <span class="form-control-feedback right" aria-hidden="true">kgs</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Net Weight:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" class="form-control" name="net_weight" id="net_weight" required="required" value="<?php echo $net_weight ?>">
                              <span class="form-control-feedback right" aria-hidden="true">kgs</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">WSR Number:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" class="form-control" name="wsr_no" id="wsr_no" value="<?php echo $wsr_no ?>" >
                              
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">WSI Number:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" class="form-control" name="wsi_no" id="wsi_no" value="<?php echo $wsr_no ?>" >
                              
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Hauler:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <select name="service_provider" id="service_provider" class="form-control" required="required">
                              
                              	<option value="<?php echo $hauler ?>"><?php echo $hauler_name ?></option>
                                <?php foreach($service_providers as $sp){ ?>
                                <option value="<?php echo $sp['id'] ?>"><?php echo $sp['company_name'] ?></option>
                                <?php } ?>
                              </select>  
                              
                              
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Truck Plate #:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" class="form-control" name="truck_plate_no" id="truck_plate_no" required="required" value="<?php echo $truck_plate_no ?>" >
                              
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Variety:</label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                              <input type="text" class="form-control" name="variety" id="variety" value="<?php echo $variety ?>" >
                              
                            </div>
                          </div>
                          
                      </div>
                  </div>    
                      
                      <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $id ?>">
                      <input type="hidden" class="form-control" name="type" id="type" value="WB">

                      <div class="form-group">
                      	
                        <div class="col-md-9 col-md-offset-9" style="margin-top:20px">
                          <a href="<?php HTTP_PATH.'operation/wsr_view' ?>"<button type="button" class="btn btn-primary">Cancel</button></a>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>
					</div>
                    </form>
                  </div>
                </div>
              </div>
       
	</div>
</div>
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
