<?php
	$form_action=	HTTP_PATH."mastertables/clients/insert";
	$title="Add New Client";
	$e	=	array(
		"company"=>"",
		"address"=>"",
		"city"=>"",
		"province"=>"",
		"country"=>"Philippines",
		"contact_no"=>"",
		"fax_no"=>"",
		"email"=>"",
		"website"=>"",
		"contact_person"=>"",
		"position"=>"",
		"lead_person"=>"",
		"stat"=>"1",
		"tin_no"=>"",
	);
	if(isset($existing)) {
		$form_action	=	HTTP_PATH."mastertables/clients/update/".$existing['id'];
		$title			=	"Edit client ";
		$this->Mmm->debug($existing);
		$e	=	$existing;
	}
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<button type= "button" class ="close" data-dismiss="modal">&times;</button>
		<h4 class="panel-title">
			<?php echo $title; ?>
		</h4>
	</div>
</div>
	<div class="panel-body">
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='mastertables_clients_form' enctype='multipart/form-data'>

			<?php echo $this->Mmm->createCSRF(); ?>
			<div class='col-xs-12 col-sm-12'>
				<label for='company'>Company Name</label>
				<input type='text' id='company' name='company' placeholder='Company Name' class='form-control' value='<?php echo $e['company']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='address'>Address</label>
				<input type='text' id='address' name='address' placeholder='Address' class='form-control' value='<?php echo $e['address']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='city'>City</label>
				<input type='text' id='city' name='city' placeholder='City' class='form-control' value='<?php echo $e['city']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='province'>Province</label>
				<input type='text' id='province' name='province' placeholder='Province' class='form-control' value='<?php echo $e['province']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='country'>Country</label>
				<input type='text' id='country' name='country' placeholder='Country' class='form-control' value='<?php echo $e['country']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='contact_no'>Contact Number</label>
				<input type='text' id='contact_no' name='contact_no' placeholder='Contact Number' class='form-control' value='<?php echo $e['contact_no']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='fax_no'>Fax Number</label>
				<input type='text' id='fax_no' name='fax_no' placeholder='Fax Number' class='form-control' value='<?php echo $e['fax_no']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='email'> Email</label>
				<input type='text' id='email' name='email' placeholder='Email Address' class='form-control' value='<?php echo $e['email']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='website'>Website</label>
				<input type='text' id='website' name='website' placeholder='Website' class='form-control' value='<?php echo $e['website']; ?>'/>
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='contact_person'>Contact Person</label>
				<input type='text' id='contact_person' name='contact_person' placeholder='Contact Person' class='form-control' value='<?php echo $e['contact_person']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for ='position'>Position</label>
				<input type='text' id='position' name='position' placeholder='Position' class='form-control' value='<?php echo $e['position']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='lead_person'>Lead Person</label>
				<input type='text' id='lead_person' name='lead_person' placeholder='Lead Person' class='form-control' value='<?php echo $e['lead_person']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='stat'>Status</label>
				<select id='stat' name='stat' placeholder='stat' class='form-control'>
					<option <?php echo ($e['stat']==1? "SELECTED":""); ?> value="1">Active</option>
					<option <?php echo ($e['stat']==0? "SELECTED":""); ?> value="0">Inactive</option>
				</select>
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='tin_no'>Tax Identification Number</label>
				<input type='text' id='tin_no' name='tin_no' placeholder='Tax Identification Number' class='form-control' value='<?php echo $e['tin_no']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>
	
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
		if (company==null || company=="" || company=="company") {
			msg+="Company name is required! <br/>";
		}
		var address=document.forms.mastertables_clients_form.address.value;
		if (address==null || address=="" || address=="address") {
			msg+="Address is required! <br/>";
		}
		var tin_no=document.forms.mastertables_clients_form.tin_no.value;
		if (tin_no==null || tin_no=="" || tin_no=="tin_no") {
			msg+="Tax Identification Number is required! <br/>";
		}
		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("mastertables_clients_form").submit();
			return true;
		}
	}
</script>

