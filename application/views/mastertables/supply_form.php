<?php
	$form_action=	HTTP_PATH."mastertables/supply/insert";
	$title="Add New Client";

	$e	=	array(
	"company"=>"",
	"address"=>"",
	"city"=>"",
	"province"=>"",
	"country"=>"",
	"contact_no"=>"",
	"fax_no"=>"",
	"email"=>"",
	"website"=>"",
	"contact_person"=>"",
	"position"=>"",
	"lead_person"=>"",
	"stat"=>"",
	"tin_no"=>"",
	
	);

	if(isset($existing)) {
		$form_action	=	HTTP_PATH."mastertables/supply/update/".$existing['id'];
		$title			=	"Edit client ";
		$this->Mmm->debug($existing);
		$e	=	$existing;
	}
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h4 class="panel-title">
			<?php echo $title; ?>
		</h4>
	</div>
	<div class="panel-body">
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='mastertables_clients_form' enctype='multipart/form-data'>

			<?php echo $this->Mmm->createCSRF(); ?>
			<div class='col-xs-12 col-sm-12'>
				<label for='company'>Company</label>
				<input type='text' id='company' name='company' placeholder='company' class='form-control' value='<?php echo $e['company']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='address'>Address</label>
				<input type='text' id='address' name='address' placeholder='Address' class='form-control' value='<?php echo $e['address']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='city'>City</label>
				<input type='text' id='city' name='city' placeholder='city' class='form-control' value='<?php echo $e['city']; ?>' />
			</div>

			<div class='col-xs-12 col-sm-6'>
				<label for='province'>Province</label>
				<input type='text' id='province' name='province' placeholder='province' class='form-control' value='<?php echo $e['province']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='country'>Country</label>
				<input type='text' id='country' name='country' placeholder='country' class='form-control' value='<?php echo $e['country']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='contact_no'>Contact Number</label>
				<input type='text' id='contact_no' name='contact_no' placeholder='contact_no' class='form-control' value='<?php echo $e['contact_no']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='fax_no'>Fax Number</label>
				<input type='text' id='fax_no' name='fax_no' placeholder='fax_no' class=fax_no'form-control' value='<?php echo $e['fax_no']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='email'> Email</label>
				<input type='text' id='email' name='email' placeholder='Email' class='form-control' value='<?php echo $e['email']; ?>' />
			</div>
			<!--<div class='col-xs-12 col-sm-6'>
				<label for='parent_account'>Supplier of</label>
				<input type='text' id='address' name='address' placeholder='Address' class='form-control' value='<?php echo $e['address']; ?>' />
				</div>
				<div class='col-xs-12 col-sm-6'>
				<label for='name'>Name</label>
				<input type='text' id='name' name='name' placeholder='Name' class='form-control' value='<?php echo $e['name']; ?>' />
			</div>-->
			<div class='col-xs-12 col-sm-6'>
				<label for='website'>Website</label>
				<input type='text' id='website' name='website' placeholder='website' class='form-control' value='<?php echo $e['website']; ?>'/>
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='contact_person'>Contact Person</label>
				<input type='text' id='contact_person' name='contact_person' placeholder='Contact Person' class='form-control' value='<?php echo $e['contact_person']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for ='position'>Position</label>
				<input type='text' id='position' name='position' placeholder='position' class='form-control' value='<?php echo $e['position']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='lead_person'>Lead Person</label>
				<input type='text' id='lead_person' name='tin' placeholder='lead_person' class='form-control' value='<?php echo $e['lead_person']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='stat'>Stat</label>
				<input type='text' id='stat' name='stat' placeholder='stat' class='form-control' value='<?php echo $e['stat']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='tin_no'>Tax Identification Number</label>
				<input type='text' id='tin_no' name='tin' placeholder='Tax Identification Number' class='form-control' value='<?php echo $e['tin_no']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>

			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' id='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>
	</div>
</div>
<script>
	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function checkInput() {
		var msg="";
		//var patt1=/^[0-9]+$/i;
		var patt1=/^\d+(\.\d+)*$/i;
		var comapny=document.forms.mastertables_clients_form.company.value;
		if (company==null || company=="" || company=="Company Name") {
			msg+="Company name is required! <br/>";
		}
		var address=document.forms.mastertables_clients_form.address.value;
		if (address==null || address=="" || address=="Address") {
			msg+="Address is required! <br/>";
		}
		var city=document.forms.mastertables_clients_form.city.value;
		if (city==null || city=="" || city=="city") {
			msg+="City is required! <br/>";
		}
		var province=document.forms.mastertables_clients_form.province.value;
		if (province==null || province=="" || province=="Province") {
			msg+="Province is required! <br/>";
		}
		var country=document.forms.mastertables_clients_form.country.value;
		if (country==null || country=="" || country=="Country") {
			msg+="Country is required! <br/>";
		}
		var contact_no=document.forms.mastertables_clients_form.contact_no.value;
		if (contact_no==null || contact_no=="" || contact_no=="Contact_no") {
			msg+="Contact number is required! <br/>";
		}
		var fax_no=document.forms.mastertables_clients_form.fax_no.value;
		if (fax_no==null || fax_no=="" || fax_no=="Fax_no") {
			msg+="Fax number is required! <br/>";
		}
		var email=document.forms.mastertables_clients_form.email.value;
		if (email!="") {
			if (validateEmail(email)==false) {
				msg+="Email is not valid! <br/>";
			}
		}
		var website=document.forms.mastertables_clients_form.website.value;
		if (website==null || website=="" || website=="Website") {
			msg+="website is required! <br/>";
		}
		var contact_person=document.forms.mastertables_clients_form.contact_person.value;
		if (contact_person==null || contact_person=="" || contact_person=="Contact Person") {
			msg+="Contact Person is required! <br/>";
		}
		var position=document.forms.mastertables_clients_form.position.value;
		if (position==null || position=="" || position=="position") {
			msg+="Position is required! <br/>";
		}
		var lead_person=document.forms.mastertables_clients_form.lead_person.value;
		if (lead_person==null || lead_person=="" || lead_person=="Lead_person") {
			msg+="Lead_person is required! <br/>";
		}
		var stat=document.forms.mastertables_clients_form.stat.value;
		if (stat==null || stat=="" || stat=="Stat") {
			msg+="Stat is required! <br/>";
		}
		var tin_no=document.forms.mastertables_clients_form.tin_no.value;
		if (tin_no==null || tin_no=="" || tin_no=="Tax Identification Number") {
			msg+="Tax Identification Number is required! <br/>";
		}
		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("mastertables_supply_form").submit();
			return true;
		}
	}
</script>

