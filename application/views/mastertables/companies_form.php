<?php
	$form_action	= HTTP_PATH."mastertables/companies/insert";
	$title="Add New Company";

	$e	=	array(
		"name"=>"",
		"address"=>"",
		"telephone_no"=>"",
		"fax_no"=>"",
		"company_tin"=>"",
		"is_top_20000"=>"",
		"created"=>"",
		"created_by"=>"",
		"modified"=>"",
		"modified_by"=>"",
		"stat"=>""
	);

	if (isset($existing)){
		$form_action= HTTP_PATH."mastertables/companies/update/".$existing['id'];
		$title="Edit Company";
		$e = $existing;
	}
?>
<div class="panel panel-primary">
	<div class= "panel-heading" style="min-height">
		<button type= "button" class ="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title"><?php echo $title; ?></h5>
	</div>
</div>
	<div class= "panel-body">
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='mastertables_companies_form' enctype='multipart/form-data'>
			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Name</label>
				<input type='text' id='company_name' class='form-control' name='company_name' placeholder='Name' value='<?php echo $e['name']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Address</label>
				<input type='text' id='address' class='form-control' name='address' placeholder='Address' value='<?php echo $e['address']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Telephone No.</label>
				<input type='text' id='telephone_no' class='form-control' name='telephone_no' placeholder='Telephone No.' value='<?php echo $e['telephone_no']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Fax No.</label>
				<input type='text' id='fax_no' class='form-control' name='fax_no' placeholder='Fax No.' value='<?php echo $e['fax_no']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>TIN</label>
				<input type='text' id='company_tin' class='form-control' name='company_tin' placeholder='Company TIN' value='<?php echo $e['company_tin']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Is top 20,000?</label>
				<input type='number' id='is_top_20000' class='form-control' name='is_top_20000' placeholder='1' value='<?php echo $e['is_top_20000']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Stat</label>
				<input type='number' id='stat' class='form-control' name='stat' placeholder='1' value='<?php echo $e['stat']; ?>'/>
			</div>

			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<div class="form-group col-xs-12 col-sm-12 pull-right">
				<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>
</div>
<script>
		function checkInput() {
			var msg="";
			var name=document.forms.mastertables_companies_form.company_name.value;
			var address=document.forms.mastertables_companies_form.address.value;
			var company_tin=document.forms.mastertables_companies_form.company_tin.value;
			var is_top_20000=document.forms.mastertables_companies_form.is_top_20000.value;
			var stat=document.forms.mastertables_companies_form.stat.value;
			if (name==null || name=="")	{
				msg+="Name is required! <br/>";
			}
			if (address==null || address=="")	{
				msg+="Address (To) is required! <br/>";
			}
			if (company_tin==null || company_tin=="")	{
				msg+="TIN is required! <br/>";
			}
			if (is_top_20000==null || is_top_20000=="")	{
				msg+="Is Top 20,000 is required! <br/>";
			}
			if (stat==null || stat=="")	{
				msg+="Stat is required! <br/>";
			}
			if(msg!="")	{
				toastr["warning"] (msg,"ABAS Says");
				return false;
			}
			else {
				document.getElementById("mastertables_companies_form").submit();
				return true;
			}
		}
</script>