<?php
$company_id=$date_effective=$type=$rate=$quantity=$unit=$amount=$charterer=$reference_no=$sub_reference_no=$details=$charterer_id=$contract_type=$parent_id=$parent_contract="";
$action	=	HTTP_PATH."operation/contracts/insert";
$title	=	"Contract Entry";
$id='';
$client_id='';
$display = 'none';

$services = $this->Abas->getServices();


if(isset($contract)) {
	// $this->Mmm->debug($contract);
	$action	=	HTTP_PATH."operation/contracts/update/".$contract['id'];
	$title	=	"Contract Editing";
	$company_id		=	$contract['company_id'];
	$client_id	=	$contract['client_id'];
	if($getclient	=	$this->db->query("SELECT * FROM clients WHERE id=".$client_id)) {
		if($getclient=	$getclient->row()) {
			$client	=	$getclient->company;
		}
	}

	$date_effective	=	date("Y-m-d",strtotime($contract['date_effective']));
	$type			=	$contract['type'];
	$contract_type	=	$contract['contract_type'];
	$quantity		=	$contract['quantity'];
	$unit			=	$contract['unit'];
	$amount			=	$contract['amount'];
	$reference_no	=	$contract['reference_no'];
	$details		=	$contract['details'];
	$id				=	$contract['id'];
	$parent_id		=	$contract['parent_id'];

	$rate			=	$contract['rate'];
	
	if($parent_id !=0){
		//get parent contract info
		$parent_info = $this->Abas->getContract($parent_id);
		//get client
		$client_name = $this->Abas->getClient($parent_info['client_id']);
		$parent_contract = $client_name['company']." ( ".$parent_info['type']." - ".$parent_info['reference_no']." ) ".
		
		$display = 'block';
	}
	
	//$sub_reference_no	=	$contract['sub_reference_no'];
}

$companyoptions	=	"";
$companystring	=	"<select name='company' id='company' class='form-control'><option value=''>Choose One</option>";
// $this->Mmm->debug($companies);
// $this->Mmm->debug($clients);
if(!empty($companies)) {
	foreach($companies as $c) {
		$companyoptions	.=	"<option ".(($company_id==$c->id)?"selected":"")." value='".$c->id."'>".$c->name."</option>";
		// $companystring	.=	$v->name."||";
	}
}
// $companystring	=	rtrim($companystring,"||");
$companystring	.=	$companyoptions."</select>";
$vesseloptions	=	"<select name='company' id='company' class='form-control'><option value=''>Choose One</option>";
$vesselstring	=	"";
if(!empty($vessels)) {
	foreach($vessels as $v) {
		$vesseloptions	.=	"<option value='".$v->id."'>".$v->name."</option>";
		$vesselstring	.=	$v->name."||";
	}
}
$vesselstring	=	rtrim($vesselstring,"||");
$vesseloptions	.=	"</select>";
$clientoptions	=	"";
$clientstring	=	"";
if(!empty($clients)) {
	foreach($clients as $c) {
		$clientoptions	.=	"<option ".(($client_id==$c['id'])?"selected":"")." value='".$c['id']."'>".$c['company']."</option>";
		//$clientoptions	.=	"<option value='".$c['id']."'>".$c['company']."</option>";
		$clientstring	.=	$c['company']."||";
	}
}
$clientstring	=	rtrim($clientstring,"||");
$fields[]	=	array("caption"=>"Company", "name"=>"company", "class"=>"col-sm-12 col-lg-12", "datatype"=>"custom", "validation"=>"string", "value"=>$companystring);
$fields[]	=	array("caption"=>"Date Effective", "name"=>"date_effective", "class"=>"col-sm-6", "datatype"=>"text", "validation"=>"date", "value"=>$date_effective);
$fields[]	=	array("caption"=>"Charterer", "name"=>"charterer", "class"=>"col-sm-6", "datatype"=>"text", "validation"=>"string", "value"=>$charterer);
$fields[]	=	array("caption"=>"Type", "name"=>"type", "class"=>"col-sm-6", "datatype"=>"select", "validation"=>"string", "value"=>"Timecharter||Lighterage||General Charter||Trucking||Handling", "selected"=>$type);
$fields[]	=	array("caption"=>"Rate", "name"=>"rate", "class"=>"col-sm-6", "datatype"=>"text", "validation"=>"int", "value"=>$rate);
$fields[]	=	array("caption"=>"Quantity", "name"=>"quantity", "class"=>"col-sm-6", "datatype"=>"text", "validation"=>"int", "value"=>$quantity);
$fields[]	=	array("caption"=>"Unit", "name"=>"unit", "class"=>"col-sm-6", "datatype"=>"select", "validation"=>"str", "value"=>"Metric Ton||Bags||Kilograms", "selected"=>$unit);
$fields[]	=	array("caption"=>"Total Amount", "name"=>"amount", "class"=>"col-sm-6", "datatype"=>"text", "validation"=>"int", "value"=>$amount);
$fields[]	=	array("caption"=>"Reference Number", "name"=>"reference_no", "class"=>"col-sm-6", "datatype"=>"text", "validation"=>"text", "value"=>$reference_no);
$fields[]	=	array("caption"=>"Details", "name"=>"details", "class"=>"col-sm-12 col-lg-12", "datatype"=>"area", "validation"=>"string", "value"=>$details);

$form	=	$this->Mmm->createInput2($action, $title, $fields, "primary");


?>
<style>
.popover {
	max-width: 600px;
	width: auto;
}
</style>
<div class='panel panel-primary'>
	<div class='panel-heading'>Contract Entry</div>
	<div class='panel-body'>


        <form class="form-horizontal form-label-left" name="contract_entry" id="contract_entry" method="post" action="<?php echo $action; ?>">
					<?php echo $this->Mmm->createCSRF() ?>
                    <div style="float:left">

                        <div style="width:350px">

                          <div class="form-group">
                            <label for='company'>Company (Contractor):</label>

                              <select name='company' id='company' class='form-control' required="required">
                                    <option value=''></option>
                                    <?php echo $companyoptions; ?>
                                </select>


                          </div>
                          <div class="form-group">
                            <label for='client'>Client</label>
							<?php if($this->Abas->checkPermissions("encoding|clients", false)) : ?>
                                <a href="#" id="add_charterer" class="btn btn-default btn-xs" title="Add New Client">(+)</a>
                            <?php endif; ?>
                            <select name='client' id='client' class='form-control' required="required">
                                <option value=''></option>
                                <?php echo $clientoptions; ?>
                            </select>
                          </div>
                          <div class="form-group">
                          <label for='type'>Service Type</label>
                            <select id='type' name='type' class='form-control' required="required">
                                <option value='<?php echo $type; ?>'><?php echo $type; ?></option>
                                <?php foreach($services as $s){ ?>
                                <option  value='<?php echo $s->type; ?>'><?php echo $s->type; ?></option>
                                <?php } ?>
                            </select>
                          </div>
                          
						  <div class="form-group">
                            <div style="width:170px; float:left">
                                <label for='reference_no'>Contract Type:</label>
                                <select id='contract_type' name='contract_type' class='form-control' required="required">
                                    <option value='<?php echo $contract_type; ?>'><?php echo $contract_type; ?></option>
                                   
                                    <option  value='Mother Contract'>Mother Contract</option>
                                    <option  value='Sub Contract'>Sub Contract</option>
                                   
                                </select>
                            
                            </div>
                            <div style="width:170px; float:right">
                                <span id="parent_contract_list" style="display:<?php echo $display ?>">
                                <label for='reference_no'>Mother contract:</label>
                                <select id='parent_id' name='parent_id' class='form-control' required="required">
                                    <option value='<?php echo $parent_id; ?>'><?php echo $parent_contract; ?></option>
                                    <?php foreach($contracts as $c){ 
											
											//get client name
											$client = $this->Abas->getClient($c['client_id']);
									?>
                                    <option  value='<?php echo $c['id']; ?>'><?php echo $client['company']." (".$c['type']." -  Ref.# ".$c['reference_no']." )" ?></option>
                                     <?php } ?>
                                </select>
                                </span>
                               
                            </div>
                          </div>


                          
                      </div>
                 </div>

                 <div style="float:right; margin-right:5px">

                    <div style="width:370px">

                          <div class="form-group">                
                            
                            <div style="width:180px; float:left">
                               <label for='date_effective'>Contract Date</label>
                            <input type='text' id='date_effective' name='date_effective' required="required"  class="date-picker form-control col-md-7 col-xs-12" value='<?php echo $date_effective; ?>' />
                            	<span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                            	<script>$("#date_effective").datepicker({changeYear: true,yearRange: "-100:+10"});</script> 
                            </div>
                            <div style="width:180px; float:right">
                                <label for='reference_no'>Reference Number</label>
                                <input type='text' id='reference_no' name='reference_no' required="required"   class='form-control' value='<?php echo $reference_no; ?>' />
                            </div>
                          </div>
                          <div class="form-group">
                            <label for='quantity'>Quantity</label>
                        	<input type='number' id='quantity' name='quantity'  class='form-control' value='<?php echo $quantity; ?>' />
                          </div>
                          <div class="form-group">
                            <div style="width:180px; float:left">
                                <label for='unit'>Unit:</label>
                                <select id='unit' name='unit' class='form-control'>
                                    <option value=''>Choose One</option>
                                    <option <?php echo ($unit=="Metric Ton")?"SELECTED":""; ?> value='Metric Ton'>Metric Ton</option>
                                    <option <?php echo ($unit=="Bags")?"SELECTED":""; ?> value='Bags'>Bags</option>
                                    <option <?php echo ($unit=="Kilograms")?"SELECTED":""; ?> value='Kilograms'>Kilograms</option>
                                    <option <?php echo ($unit=="Days")?"SELECTED":""; ?> value='Days'>Days</option>
                                    <option <?php echo ($unit=="Months")?"SELECTED":""; ?> value='Months'>Months</option>
                                </select>
                            </div>
                            <div style="width:180px; float:right">
                            	<label for='amount'>Rate:</label>
                        		<input type='number' step='1' id='rate' name='rate'  placeholder='Rate' class='form-control' value='<?php echo $rate; ?>' />
                            </div>
                          </div>

                          <div class="form-group">
                            <label for='amount'>Contract Amount:</label>
                        	<input type='number' step='1' id='amount' name='amount'  placeholder='Contract Amount' class='form-control' value='<?php echo $amount; ?>' />
                          </div>
                	</div>
             	</div>

                <div class='col-sm-12 col-lg-12'>
                        <label for='cargo_details9'>Contract Details (optional)</label>
                        <textarea id='details' name='details' class='form-control' rows="7"><?php echo $details; ?></textarea>
                        <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $id; ?>">
                </div>
                
				

          </form>
          		<div class="form-group">

                    <div class="col-md-9 col-md-offset-9" style="margin-top:20px">
                      <a href="<?php echo HTTP_PATH.'operation' ?>"><button type="submit" class="btn btn-primary">Cancel</button></a>
                      <button type="button" class="btn btn-success" onclick=" javascript:checkautoform();">Save</button>
                    </div>
                </div>
	</div>
</div>
<script>

function checkautoform() {
	var msg="";
		
	var company=document.getElementById('company').value;	
	if (company==null || company=="") {
		msg+="Company is required! <br/>";
	}
	
	var date_effective=document.forms.contract_entry.date_effective.value;
	if (date_effective==null || date_effective=="") {
		msg+="Contract date is required!<br/>";
	}
	
	var client=document.forms.contract_entry.client.value;
	if (client==null || client=="") {
		msg+="Client is required! <br/>";
	}
	
	var type=document.forms.contract_entry.type.selectedIndex;
	if (type==null || type=="") {
		msg+="Service Type is required! <br/>";
	}
	
	var contract_type=document.forms.contract_entry.contract_type.value;
	if (contract_type==null || contract_type=="") {
		msg+="Contract Type is required! <br/>";
	}else{	
		
		if (contract_type === 'Sub Contract'){			
			//require parent contract
			var parent_id = document.getElementById('parent_id').value;	
							
			if (parent_id==null || parent_id=="") {
				msg+="Mother Contract is required! <br/>";
			}
		}		
	}
	
	var quantity=document.forms.contract_entry.quantity.value;
	if (quantity==null || quantity=="") {
		msg+="Quantity is required! <br/>";
	}
	
	var unit1=document.getElementById('unit').value;	
	
	if (unit1==null || unit1=="")  {
		msg+="Unit is required! <br/>";
	}
	var amount=document.forms.contract_entry.amount.value;
	if (amount==null || amount=="" || amount=="Contract Amount") {
		msg+="Contract Amount is required! <br/>";
	}
	
	var reference_no=document.forms.contract_entry.reference_no.value;
	if (reference_no==null || reference_no=="") {
		msg+="Reference number is required! <br/>";
	}
	
	var details=document.forms.contract_entry.details.value;
	if (details==null || details=="" ) {
		msg+="Contract details is required! <br/>";
	}
	
	if(msg!="") {
		toastr['warning'](msg,"ABAS Says");
		return false;
	}else {
		document.forms['contract_entry'].submit(); return true;
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
	
	
	
	
	$('#contract_type').on('change', function() {
	  	if( this.value == 'Sub Contract' ){
			$('#parent_contract_list').show();
		}else{
			$('#parent_contract_list').hide();
		}
	})
	
});
</script>
