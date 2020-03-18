<?php
	$formaction= HTTP_PATH."vessels/profile";
	$formtitle= "Add Vessel";
	$e= array(
		"company"=>"",
		"photo_path"=>"",
		"name"=>"",
		"ex_name"=>"",
		"price_sold"=>"",
		"price_paid"=>"",
		"length_loa"=>"",
		"length_lr"=>"",
		"length_lbp"=>"",
		"breadth"=>"",
		"depth"=>"",
		"draft"=>"",
		"year_built"=>"",
		"builder"=>"",
		"place_built"=>"",
		"jap_dwt"=>"",
		"bale_capacity"=>"",
		"grain_capacity"=>"",
		"hatch_size"=>"",
		"hatch_type"=>"",
		"year_last_drydocked"=>"",
		"place_last_drydocked"=>"",
		"phil_dwt"=>"",
		"gross_tonnage"=>"",
		"net_tonnage"=>"",
		"main_engine"=>"",
		"main_engine_rating"=>"",
		"main_engine_actual_rating"=>"",
		"model_serial_no"=>"",
		"estimated_fuel_consumption"=>"",
		"bow_thrusters"=>"",
		"propeller"=>"",
		"call_sign"=>"",
		"imo_no"=>"",
		"monthly_amortization_no_of_months"=>"",
		"tc_proj_mo_income"=>"",
		"hm_agreed_value"=>"",
		"maiden_voyage"=>"",
		"replacement_cost_new"=>"",
		"sound_value"=>"",
		"market_value"=>"",
		"status"=>"",
		"created_by"=>"",
		"modified_by"=>"",
		"created"=>"",
		"modified"=>"",
		"bank_account_num"=>"",
		"bank_account_name"=>""
	);
	if (isset($existing)){
		$formaction= HTTP_PATH."vessels/update/".$existing['id'];
		$formtitle="Edit Vessel";
		$e=$existing;
		$this->Mmm->debug($e);
	}
$companies=$this->Abas->getCompanies();
$company_options= "";
if (!empty($companies)){
	foreach($companies as $company){
		$company_options .="<option ".($e['company']==$company->id?"SELECTED":"")." value='".$company->id."'>".$company->name."</option>";

		}
	}
?>
<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class "modal-title"><?php echo $formtitle; ?>
		</h5>
	</div>
	<div class="panel-body">
		<form action = "<?php echo $formaction; ?>" method='POST' id='mastertables_vessel_form' role='form'>
			<div class='form-group col-xs-12 col-sm-12'>
				<label for='company'>Company</label>
				<select class='form-control' name='company'id='company'>
					<option>Choose One</option>
					<?php echo $company_options; ?>
				</select>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='name'>Name</label>
				<input type='text' class='form-control' id='name' name='name' value='<?php echo $e['name']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='ex_name'>Ex Name</label>
				<input type='text' class='form-control' id='ex_name' name='ex_name'value='<?php echo $e['ex_name']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='price_sold'>Price Sold</label>
				<input type='text' class='form-control' id='price_sold' name='price_sold' value='<?php echo $e['price_sold']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='price_paid'>Price Paid</label>
				<input type='text' class='form-control' id='price_paid' name='price_paid' value='<?php echo $e['price_paid']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='length_loa'>Length LOA</label>
				<input type='text' class='form-control' id='length_loa' name='length_loa' value='<?php echo $e['length_loa']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='length_lr'>Length LR</label>
				<input type='text' class='form-control' id='length_lr' name='length_lr' value='<?php echo $e['length_lr']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='length_lbp'>Length LBP</label>
				<input type='text' class='form-control' id='length_lbp' name='length_lbp' value='<?php echo $e['length_lbp']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='breadth'>Breadth</label>
				<input type='text' class='form-control' id='breadth' name='breadth' value='<?php echo $e['breadth']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='depth'>Depth</label>
				<input type='text' class='form-control' id='depth' name='depth' value="<?php echo $e['depth']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='draft'>Draft</label>
				<input type='text' class='form-control' id='draft' name='draft' value="<?php echo $e['draft']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='year_built'>Year Built</label>
				<input type='text' class='form-control' id='year_built' name='year_built' value="<?php echo $e['year_built']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='builder'>Builder</label>
				<input type='text' class='form-control' id='builder' name='builder' value="<?php echo $e['builder'];; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='place_built'>Place Built</label>
				<input type='text' class='form-control' id='place_built' name='place_built' value="<?php echo $e['place_built']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='jap_dwt'>Jap DWT</label>
				<input type='text' class='form-control' id='jap_dwt' name='jap_dwt' value="<?php echo $e['jap_dwt']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='bale_capacity'>Bale Capacity</label>
				<input type='text' class='form-control' id='bale_capacity' name='bale_capacity' value="<?php echo $e['bale_capacity']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='grain_capacity'>Grain Capacity</label>
				<input type='text' class='form-control' id='grain_capacity' name='grain_capacity' value="<?php echo $e['grain_capacity']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='hatch_size'>Hatch Size</label>
				<input type='text' class='form-control' id='hatch_size' name='hatch_size' value="<?php echo $e['hatch_size'] ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='hatch_type'>Hatch Type</label>
				<input type='text' class='form-control' id='hatch_type' name='hatch_type' value="<?php echo $e['hatch_type']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='year_last_drydocked'>Year Last Drydocked</label>
				<input type='text' class='form-control' id='year_last_drydocked' name='year_last_drydocked' value="<?php echo $e['year_last_drydocked']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='place_last_drydocked'>Place Last Drydocked</label>
				<input type='text' class='form-control' id='place_last_drydocked' name='place_last_drydocked' value="<?php echo $e['place_last_drydocked']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='phil_dwt'>Phil DWT</label>
				<input type='text' class='form-control' id='phil_dwt' name='phil_dwt'  value="<?php echo $e['phil_dwt']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='gross_tonnage'>Gross Tonnage</label>
				<input type='text' class='form-control' id='gross_tonnage' name='gross_tonnage' value="<?php echo $e['gross_tonnage']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='net_tonnage'>Net Tonnage</label>
				<input type='text' class='form-control' id='net_tonnage' name='net_tonnage' value="<?php echo $e['net_tonnage']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='main_engine'>Main Engine</label>
				<input type='text' class='form-control' id='main_engine' name='main_engine' value="<?php echo $e['main_engine']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='main_engine_rating'>Main Engine Rating</label>
				<input type='text' class='form-control' id='main_engine_rating' name='main_engine_rating' value="<?php echo $e['main_engine_rating']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='main_engine_actual_rating'>Main Engine Actual Rating</label>
				<input type='text' class='form-control' id='main_engine_actual_rating' name='main_engine_actual_rating' value="<?php echo $e['main_engine_actual_rating']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='model_serial_no'>Model Serial No</label>
				<input type='text' class='form-control' id='model_serial_no' name='model_serial_no' value="<?php echo $e['model_serial_no']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='estimated_fuel_consumption'>Estimated Fuel Consumption</label>
				<input type='text' class='form-control' id='estimated_fuel_consumption' name='estimated_fuel_consumption' value="<?php echo $e['estimated_fuel_consumption']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='bow_thrusters'>Bow Thrusters</label>
				<input type='text' class='form-control' id='bow_thrusters' name='bow_thrusters' value="<?php echo $e['bow_thrusters']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='propeller'>Propeller</label>
				<input type='text' class='form-control' id='propeller' name='propeller' value="<?php echo $e['propeller']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='call_sign'>Call Sign</label>
				<input type='text' class='form-control' id='call_sign' name='call_sign' value="<?php echo $e['call_sign']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='imo_no'>IMO No</label>
				<input type='text' class='form-control' id='imo_no' name='imo_no' value="<?php echo $e['imo_no']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='monthly_amortization_no_of_months'>Monthly Amortization Number of Months</label>
				<input type='text' class='form-control' id='monthly_amortization_no_of_months' name='monthly_amortization_no_of_months' value="<?php echo $e['monthly_amortization_no_of_months']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='tc_proj_mo_income'>TC Project Monthly Income</label>
				<input type='text' class='form-control' id='tc_proj_mo_income' name='tc_proj_mo_income' value="<?php echo $e['tc_proj_mo_income']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='hm_agreed_value'>HM Agreed Value</label>
				<input type='text' class='form-control' id='hm_agreed_value' name='hm_agreed_value' value="<?php echo $e['hm_agreed_value']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='maiden_voyage'>Maiden Voyage</label>
				<input type='text' class='form-control datepicker' id='maiden_voyage' name='maiden_voyage' value="<?php echo $e['maiden_voyage']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='replacement_cost_new'>Replacement Cost New</label>
				<input type='text' class='form-control' id='replacement_cost_new' name='replacement_cost_new' value="<?php echo $e['replacement_cost_new']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='sound_value'>Sound Value</label>
				<input type='text' class='form-control' id='sound_value' name='sound_value' value="<?php echo $e['sound_value']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='market_value'>Market Value</label>
				<input type='text' class='form-control' id='market_value' name='market_value' value="<?php echo $e['market_value']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='status'>Status</label>
				<select class='form-control' id='status' name='status'/>
					<option value=''>Choose One</option>
					<option <?php echo $e['status']=="Active" ? "selected='selected'":""; ?> value='Active'>Active</option>
					<option <?php echo $e['status']=="Inactive" ? "selected='selected'":""; ?> value='Inactive'>Inactive</option>
				</select>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='bank_account_num'>Bank Account Number</label>
				<input type='text' class='form-control' id='bank_account_num' name='bank_account_num' value="<?php echo $e['bank_account_num']; ?>"/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for ='bank_account_name'>Bank Account Name</label>
				<input type='text' class='form-control' id='bank_account_name' name='bank_account_name' value="<?php echo $e['bank_account_name']; ?>"/>
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' id='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>
	</div>
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
		var company=document.forms.mastertables_vessel_form.company.selectedIndex;
		if (company==null || company=="") {
			msg+="Company is required! <br/>";
		}
		var name=document.forms.mastertables_vessel_form.name.value;
		if (name==null || name=="" || name=="Account Name") {
			msg+="Vessel name is required! <br/>";
		}
		var status=document.forms.mastertables_vessel_form.status.selectedIndex;
		if (status==null || status=="") {
			msg+="Status is required! <br/>";
		}
		if(msg!="") {
			$("#btnSubmit").prop("disable", false);
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("mastertables_vessel_form").submit();
			return true;
		}
	}
</script>