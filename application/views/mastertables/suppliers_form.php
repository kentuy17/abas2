<?php
	$form_action=	HTTP_PATH."mastertables/suppliers/insert";
	$title="Add New Supplier";

	$e	=	array(
	"name"=>"",
	"address"=>"",
	"contact_person"=>"",
	"telephone_no"=>"",
	"fax_no"=>"",
	"payment_terms"=>"",
	"email"=>"",
	"supplier_of"=>"",
	"category"=>"",
	"status"=>"",
	"tin"=>"",
	"vat_registered"=>"0",
	"vat_computation"=>"",
	"taxation_percentile"=>"0",
	"issues_reciepts"=>"0",
	"bank_name"=>"",
	"account_name"=>"",
	"bank_account_no"=>"",
	"type"=>""
	);

	if(isset($supplier)) {
		$form_action	=	HTTP_PATH."mastertables/suppliers/update/".$supplier['id'];
		$title			=	"Edit supplier ";
		$this->Mmm->debug($supplier);
		$e	=	$supplier;
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
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='mastertables_supplier_form' enctype='multipart/form-data'>

			<?php echo $this->Mmm->createCSRF(); ?>
			<div class='col-xs-12 col-sm-12'>
				<label for='name'>Name</label>
				<input type='text' id='name' name='name' placeholder='Name' class='form-control' value='<?php echo $e['name']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='address'>Address</label>
				<input type='text' id='address' name='address' placeholder='Address' class='form-control' value='<?php echo $e['address']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='email'>Email</label>
				<input type='text' id='email' name='email' placeholder='Email' class='form-control' value='<?php echo $e['email']; ?>' />
			</div>

			<div class='col-xs-12 col-sm-6'>
				<label for='telephone_no'>Telephone Number</label>
				<input type='text' id='telephone_no' name='telephone_no' placeholder='Telephone Number' class='form-control' value='<?php echo $e['telephone_no']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for="status">Status</label>
				<select name="status" class="form-control" id="status">
					<option value="">Choose One (Required)</option>
					<option <?php echo $e['status']=="Active" ? "selected" : ""; ?> value="Active">Active</option>
					<option <?php echo $e['status']=="Inactive" ? "selected" : ""; ?> value="Inactive">Inactive</option>
				</select>
			</div>
			<div class= 'col-xs-12 col-sm-6'>
				<label for='payment_terms'>Payment Terms</label>
				<input type='text' placeholder='Payment Terms' class='form-control'id='payment_terms' name='payment_terms' value='<?php echo $e['payment_terms']; ?>'/>
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='fax_no'>Fax Number</label>
				<input type='text' id='fax_no' name='fax_no' placeholder='Fax Number' class='form-control' value='<?php echo $e['fax_no']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='bank_name'>Bank</label>
				<input type='text' id='bank_name' name='bank_name' placeholder='Bank Name' class='form-control' value='<?php echo $e['bank_name']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='contact_person'>Contact Person</label>
				<input type='text' id='contact_person' name='contact_person' placeholder='Contact Person' class='form-control' value='<?php echo $e['contact_person']; ?>' />
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
				<label for='account_no'>Account Name</label>
				<input type='text' id='account_name' name='account_name' placeholder='Account Name' class='form-control' value='<?php echo $e['account_name']; ?>'/>
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='tin'>Tax Identification Number</label>
				<input type='text' id='tin' name='tin' placeholder='Tax Identification Number' class='form-control' value='<?php echo $e['tin']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for ='bank_account_no'>Account Number</label>
				<input type='text' id='bank_account_no' name='bank_account_no' placeholder='Bank Account Number' class='form-control' value='<?php echo $e['bank_account_no']; ?>' />
			</div>
			<div class='col-xs-6 col-sm-3'>
				<label for="vat_computation">VAT Computation</label>
				<select name="vat_computation" class="form-control" id="vat_computation">
					<option value="">Choose One (Required)</option>
					<option <?php echo $e['vat_computation']=="vatable" ? "selected" : ""; ?> value="vatable">VATable</option>
					<option <?php echo $e['vat_computation']=="non-vatable" ? "selected" : ""; ?> value="non-vatable">Non-VATable</option>
					<option <?php echo $e['vat_computation']=="vat-exempt" ? "selected" : ""; ?> value="vat-exempt">VAT-Exempt</option>
					<option <?php echo $e['vat_computation']=="zero-rated" ? "selected" : ""; ?> value="zero-rated">Zero-Rated</option>
				</select>
			</div>
			<div class='col-xs-12 col-sm-3'>
				<label for="taxation_percentile">Expanded Withholding Tax</label>
				<select name="taxation_percentile" class="form-control" id="taxation_percentile">
					<option value="">Choose One (Required)</option>
					<option <?php echo $e['taxation_percentile']==0 ? "selected" : ""; ?> value="0">0%</option>
					<option <?php echo $e['taxation_percentile']==1 ? "selected" : ""; ?> value="1">1%</option>
					<option <?php echo $e['taxation_percentile']==2 ? "selected" : ""; ?> value="2">2%</option>
					<option <?php echo $e['taxation_percentile']==5 ? "selected" : ""; ?> value="5">5%</option>
					<option <?php echo $e['taxation_percentile']==10 ? "selected" : ""; ?> value="10">10%</option>
				</select>
			</div>
			<div class= 'col-xs-12 col-sm-6'>
				<label for="type">Type</label>
				<select name="type" class="form-control" id="type">
					<option value="">Choose One (Required)</option>
					<option <?php echo $e['type']=="service" ? "selected" : ""; ?> value="service">Service</option>
					<option <?php echo $e['type']=="goods" ? "selected" : ""; ?> value="goods">Goods</option>
				</select>
			</div>
			<div class= 'col-xs-12 col-sm-6'>
				<label for="issues_reciepts">Reciepts</label>
				<select name="issues_reciepts" class="form-control" id="issues_reciepts">
					<option value="">Choose One (Required)</option>
					<option <?php echo $e['issues_reciepts']==1 ? "selected" : ""; ?> value="1">Issues reciepts</option>
					<option <?php echo $e['issues_reciepts']==0 ? "selected" : ""; ?> value="0">Does not issue reciepts</option>
				</select>
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' id='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>
</div>
<script>
	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function checkInput() {
		$("#btnSubmit").prop("disable", true);
		var msg="";
		//var patt1=/^[0-9]+$/i;
		var patt1=/^\d+(\.\d+)*$/i;
		var name=document.forms.mastertables_supplier_form.name.value;
		if (name==null || name=="" || name=="Account Name") {
			msg+="Account name is required! <br/>";
		}
		var address=document.forms.mastertables_supplier_form.address.value;
		if (address==null || address=="" || address=="Address") {
			msg+="Address is required! <br/>";
		}
		var contact_person=document.forms.mastertables_supplier_form.contact_person.value;
		if (contact_person==null || contact_person=="" || contact_person=="Contact Person") {
			msg+="Contact Person is required! <br/>";
		}
		var email=document.forms.mastertables_supplier_form.email.value;
		if (email!="") {
			if (validateEmail(email)==false) {
				msg+="Email is not valid! <br/>";
			}
		}
		var vat_computation=document.forms.mastertables_supplier_form.vat_computation.selectedIndex;
		if (vat_computation==null || vat_computation=="") {
			msg+="VAT Computation is required! <br/>";
		}
		var type=document.forms.mastertables_supplier_form.type.selectedIndex;
		if (type==null || type=="") {
			msg+="Type is required! <br/>";
		}
		var taxation_percentile=document.forms.mastertables_supplier_form.taxation_percentile.selectedIndex;
		if (taxation_percentile==null || taxation_percentile=="") {
			msg+="Expanded Withholding Tax is required! <br/>";
		}
		var tin=document.forms.mastertables_supplier_form.tin.value;
		if (tin==null || tin=="" || tin=="Tax Identification Number") {
			msg+="Tax Identification Number is required! <br/>";
		}
		if(msg!="") {
			$("#btnSubmit").prop("disable", false);
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("mastertables_supplier_form").submit();
			return true;
		}
	}
</script>

