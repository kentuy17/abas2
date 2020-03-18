<?php
	$formaction= HTTP_PATH."mastertables/trucks/insert";
	$formtitle= "Add Truck";
	$e= array();
	if (isset($existing)){
		$formaction= HTTP_PATH."mastertables/trucks/update/".$existing['id'];
		$formtitle="Edit Truck";
		$e=$existing;
		$this->Mmm->debug($e);
	}
$company=$this->Abas->getCompanies();
$company_options= "";
if (!empty($company)){
	foreach($company as $c){
		$company_options .="<option ".($e['company']==$c->id?"SELECTED":"")." value='".$c->id."'>".$c->name."</option>";

		}
	}
?>
<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title"><?php echo $formtitle; ?>
		</h5>
	</div>
</div>
	<div class="panel-body">
		<form action = "<?php echo $formaction; ?>" method='POST' id='mastertables_truck_form' role='form' enctype='multipart/form-data'>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='company'>Company</label>
				<select class='form-control' name='company'id='company'>
					<option>Choose One</option>
					<?php echo $company_options; ?>
				</select>
			</div>
			
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='make'>Make</label>
				<input type='text' class='form-control' id='make' name='make' value='<?php echo $e['make']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='photo_path'>Photo</label>
				<input type='file' id='photo_path' name='photo_path' accept='.jpeg,.jpg,.png'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='model'>Model</label>
				<input type='text' class='form-control' id='model' name='model'value='<?php echo $e['model']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='plate_number'>Plate Number</label>
				<input type='text' class='form-control' id='plate_number' name='plate_number' value='<?php echo $e['plate_number']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='engine_number'>Engine Number</label>
				<input type='text' class='form-control' id='engine_number' name='engine_number' value='<?php echo $e['engine_number']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='chassis_number'>Chassis Number</label>
				<input type='text' class='form-control' id='chassis_number' name='chassis_number' value='<?php echo $e['chassis_number']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='type'>Vehicle Type/Description</label>
				<input type='text' class='form-control' id='vehicle_type' name='vehicle_type' value='<?php echo $e['type']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='color'>Color</label>
				<input type='text' class='form-control' id='color' name='color' value='<?php echo $e['color']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='date_acquired'>Date Acquired</label>
				<input type='date' class='form-control' id='date_acquired' name='date_acquired' value='<?php echo $e['date_acquired']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='registration_month'>Registration Month</label>
				<input type='text' class='form-control' id='registration_month' name='registration_month' value="<?php echo $e['registration_month']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='acquisition_cost'>Aquisition Cost</label>
				<input type='number' class='form-control' id='acquisition_cost' name='acquisition_cost' value="<?php echo $e['aquisition_cost']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='status'>Status</label>
				<select class='form-control' id='status' name='status'>
					<option value=''>Choose One</option>
					<option <?php echo $e['stat']=="1" ? "selected='selected'":""; ?> value='1'>Active</option>
					<option <?php echo $e['stat']=="0" ? "selected='selected'":""; ?> value='0'>Inactive</option>
				</select>
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' id='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>
</div>
<script>$(".datepicker").datepicker({dateFormat: "yy-mm-dd"});</script>
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
		var company=document.forms.mastertables_truck_form.company.selectedIndex;
		if (company==null || company=="") {
			msg+="Company is required! <br/>";
		}
		var make=document.forms.mastertables_truck_form.make.value;
		if (make==null || make=="") {
			msg+="Truck Make is required! <br/>";
		}
		var model=document.forms.mastertables_truck_form.model.value;
		if (model==null || model=="") {
			msg+="Truck Model is required! <br/>";
		}
		var plate_no=document.forms.mastertables_truck_form.plate_number.value;
		if (plate_no==null || plate_no=="") {
			msg+="Plate Number is required! <br/>";
		}
		var type=document.forms.mastertables_truck_form.vehicle_type.value;
		if (type==null || type=="") {
			msg+="Vehicle Type/Description is required! <br/>";
		}
		var registration_month=document.forms.mastertables_truck_form.registration_month.value;
		if (registration_month==null || registration_month=="") {
			msg+="Registration Month is required! <br/>";
		}
		var status=document.forms.mastertables_truck_form.status.selectedIndex;
		if (status==null || status=="") {
			msg+="Status is required! <br/>";
		}
		if(msg!="") {
			$("#btnSubmit").prop("disable", false);
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("mastertables_truck_form").submit();
			return true;
		}
	}
</script>