<?php
$company_id=$date_effective=$type=$rate=$quantity=$unit=$amount=$charterer=$reference_no=$details=$charterer_id="";
$action	=	HTTP_PATH."operation/contracts/insert";
$title	=	"Contract Entry";
if(isset($contract)) {
	// $this->Mmm->debug($contract);
	$action	=	HTTP_PATH."operation/contracts/update/".$contract['id'];
	$title	=	"Contract Editing";
	$company_id		=	$contract['company_id'];
	$charterer_id	=	$contract['charterer'];
	if($getcharterer	=	$this->db->query("SELECT * FROM clients WHERE id=".$charterer_id)) {
		if($getcharterer=	$getcharterer->row()) {
			$charterer	=	$getcharterer->contact_person;
		}
	}
	$date_effective	=	date("Y-m-d",strtotime($contract['date_effective']));
	$type			=	$contract['type'];
	$rate			=	$contract['rate'];
	$quantity		=	$contract['quantity'];
	$unit			=	$contract['unit'];
	$amount			=	$contract['amount'];
	$reference_no	=	$contract['reference_no'];
	$details		=	$contract['details'];
}

$companyoptions	=	"";
$companystring	=	"<select name='company' id='company0' class='form-control'><option value=''>Choose One</option>";
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
$vesseloptions	=	"<select name='company' id='company0' class='form-control'><option value=''>Choose One</option>";
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
		$clientoptions	.=	"<option value='".$c['id']."'>".$c['company']."</option>";
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
		<form action='<?php echo $action; ?>' role='form' method='POST' id='contract_entry' onsubmit='javascript: checkform()' enctype='multipart/form-data'>
			<?php echo $this->Mmm->createCSRF() ?>
            <div>

                    <div class='singleinput col-sm-12 col-lg-12'>
                        <label for='company0'>Company</label>
                        <select name='company' id='company0' class='form-control'>
                            <option value=''>Choose One</option>
                            <?php echo $companyoptions; ?>
                        </select>
                    </div>
                    <div class='col-sm-6'>
                        <label for='date_effective1'>Contract Date</label>
                        <input type='text' id='date_effective1' name='date_effective'  placeholder='Date Effective' class='form-control' value='<?php echo $date_effective; ?>' />
                        <script>$("#date_effective1").datepicker({changeYear: true,yearRange: "-100:+10"});</script>
                    </div>
                    <div class='col-sm-6'>
                        <label for='charterer2'>Charterer</label>
                        <?php if($this->Abas->checkPermissions("encoding|clients", false)) : ?>
                            <a href="#" id="add_charterer" class="btn btn-default btn-xs" title="Add New Charterer">(+)</a>
                        <?php endif; ?>
                        <input type='text' id='charterer2' name='charterer'  placeholder='Charterer Contact Person' class='form-control' value='<?php echo $charterer; ?>' />
                        <input type='text' id='charterer_id' name='charterer_id' class='hide' value='<?php echo $charterer_id; ?>' />
                    </div>
                    <div class='col-sm-6'>
                        <label for='type3'>Type</label>
                        <select id='type3' name='type' class='form-control'>
                            <option value=''>Choose One</option>
                            <option <?php echo ($type=="Timecharter")?"SELECTED":""; ?> value='Timecharter'>Timecharter</option>
                            <option <?php echo ($type=="Lighterage")?"SELECTED":""; ?> value='Lighterage'>Lighterage</option>
                            <option <?php echo ($type=="General Charter")?"SELECTED":""; ?> value='General Charter'>General Charter</option>
                            <option <?php echo ($type=="Trucking")?"SELECTED":""; ?> value='Trucking'>Trucking</option>
                            <option <?php echo ($type=="Handling")?"SELECTED":""; ?> value='Handling'>Handling</option>
                            <option <?php echo ($type=="Rental")?"SELECTED":""; ?> value='Rental'>Rental</option>
                            <option <?php echo ($type=="Space Rental")?"SELECTED":""; ?> value='Space Rental'>Space Rental</option>
                            <option <?php echo ($type=="Storage")?"SELECTED":""; ?> value='Storage'>Storage</option>
                        </select>
                    </div>
                    <div class='col-sm-6'>
                        <label for='quantity5'>Quantity</label>
                        <input type='text' id='quantity5' name='quantity'  placeholder='Quantity' class='form-control' value='<?php echo $quantity; ?>' />
                    </div>
                    <div class='col-sm-6'>
                        <label for='unit6'>Unit</label>
                        <select id='unit6' name='unit' class='form-control'>
                            <option value=''>Choose One</option>
                            <option <?php echo ($unit=="Metric Ton")?"SELECTED":""; ?> value='Metric Ton'>Metric Ton</option>
                            <option <?php echo ($unit=="Bags")?"SELECTED":""; ?> value='Bags'>Bags</option>
                            <option <?php echo ($unit=="Kilograms")?"SELECTED":""; ?> value='Kilograms'>Kilograms</option>
                        </select>
                    </div>
                    <div class='col-sm-6'>
                        <label for='amount7'>Total Amount</label>
                        <input type='text' id='amount7' name='amount'  placeholder='Total Amount' class='form-control' value='<?php echo $amount; ?>' />
                    </div>
                    <div class='col-sm-6'>
                        <label for='reference_no8'>Reference Number</label>
                        <input type='text' id='reference_no8' name='reference_no'  placeholder='Reference Number' class='form-control' value='<?php echo $reference_no; ?>' />
                    </div>
                    <div class='col-sm-12 col-lg-12'>
                        <label for='cargo_details9'>Details</label>
                        <textarea id='details9' name='details' class='form-control'><?php echo $details; ?></textarea>
                    </div>
                    <div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
                    <div class='col-xs-12 col-sm-12 col-lg-12'>
                        <input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkautoform()' />
                    </div>

		</form>
	</div>
</div>
<div id="add_charterer_head" class="hide">
	Add Charterer
</div>
<div id="add_charterer_content" class="hide">
	<form action="<?php echo HTTP_PATH; ?>" role='form' method='POST' id='charterer_entry' onsubmit='javascript: checkform()' enctype='multipart/form-data'>
		<div class='col-sm-12'>
			<label for='charterer_company'>Company</label>
			<input type='text' id='charterer_company' name='charterer_company'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-12'>
			<label for='charterer_contact_person'>Contact Person</label>
			<input type='text' id='charterer_contact_person' name='charterer_contact_person'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-12'>
			<label for='charterer_address'>Address</label>
			<input type='text' id='charterer_address' name='charterer_address'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_city'>City</label>
			<input type='text' id='charterer_city' name='charterer_city'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_province'>Province</label>
			<input type='text' id='charterer_province' name='charterer_province'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_country'>Country</label>
			<input type='text' id='charterer_country' name='charterer_country'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_contact_no'>Contact Number</label>
			<input type='text' id='charterer_contact_no' name='charterer_contact_no'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_fax_no'>Fax Number</label>
			<input type='text' id='charterer_fax_no' name='charterer_fax_no'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_email'>Email</label>
			<input type='text' id='charterer_email' name='charterer_email'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_website'>Website</label>
			<input type='text' id='charterer_website' name='charterer_website'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_position'>Position</label>
			<input type='text' id='charterer_position' name='charterer_position'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_lead_person'>Lead Person</label>
			<input type='text' id='charterer_lead_person' name='charterer_lead_person'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-6'>
			<label for='charterer_tin_no'>TIN Number</label>
			<input type='text' id='charterer_tin_no' name='charterer_tin_no'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkchartererform()' />
		</div>
		<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
	</form>
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
			url: "<?php echo HTTP_PATH; ?>home/encode/clients/insert",
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
