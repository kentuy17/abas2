

<?php
$employeeid=$lastname=$firstname=$middlename=$birthdate=$gender=$mobile=$email=$civilstat=$address=$city=$zipcode=$emergencycontactnum=$emergencycontactperson=$profilepic=$datehired=$position=$salarygrade=$department=$experience=$taxcode=$tin=$sssnum=$phnum=$pagibig=$bankacct=$elfrate=$company=$empstat=$vessel_id=$salarygradeid=$region=$warehouse="";
$display_image	=	LINK.'assets/images/icons/1689490804.png';
$formaction		=	HTTP_PATH.'whr/employee_profile/insert';
$require_img	=	true;
$access_leave	=	$this->Abas->checkPermissions("employee_profile|leave",false);
$access_loan	=	$this->Abas->checkPermissions("employee_profile|loan",false);
$access_elf		=	$this->Abas->checkPermissions("employee_profile|elf",false);
$access_salary	=	$this->Abas->checkPermissions("employee_profile|salary",false);
$forced_edit	=	$this->Abas->checkPermissions("employee_profile|forced_editing",false);
$allow_edit		=	true;
if(isset($employee_record)) {
	// echo "<pre>";print_r($employee_record);echo "</pre>";
	$e			=	$employee_record;
	$require_img=	false;
	if($e['profile_pic']!="") {
		$display_image	=	LINK.'assets/images/employeepic/'.$e['profile_pic'];
	}
	if($forced_edit == false) {
		$allow_edit				=	false;
	}
	$formaction				=	HTTP_PATH.'whr/employee_profile/update/'.$e['id'];
	$employeeid				=	$e['employee_id']; // unable to automate this due to existing physical IDs that contain employee numbers
	$lastname				=	$e['last_name'];
	$firstname				=	$e['first_name'];
	$middlename				=	$e['middle_name'];
	$birthdate				=	($e['birth_date']!="0000-00-00 00:00:00")?date("Y-m-d", strtotime($e['birth_date'])):"";
	$gender					=	$e['gender'];
	$mobile					=	$e['mobile'];
	$email					=	$e['email'];
	$civilstat				=	$e['civil_status'];
	$address				=	$e['address'];
	$city					=	$e['city'];
	$zipcode				=	$e['zipcode'];
	$emergencycontactnum	=	$e['emergency_contact_num'];
	$emergencycontactperson	=	$e['emergency_contact_person'];
	$profilepic				=	$e['profile_pic'];
	$datehired				=	($e['date_hired']!="0000-00-00 00:00:00")?date("Y-m-d", strtotime($e['date_hired'])):"";
	$position				=	$e['position'];
	$salarygrade			=	$e['salary_grade'];
	$salarygradeid			=	$e['salary_grade_id'];
	$department				=	$e['department'];
	$experience				=	$e['experience'];
	$taxcode				=	$e['tax_code'];
	$tin					=	$e['tin_num'];
	$sssnum					=	$e['sss_num'];
	$phnum					=	$e['ph_num'];
	$pagibig				=	$e['pagibig_num'];
	$bankacct				=	$e['bank_account_num'];
	$elfrate				=	$e['elf_rate'];
	$company				=	$e['company_id'];
	$empstat				=	$e['employee_status'];
	$vessel_id				=	$e['vessel_id'];
	$region					=	$e['region'];
	$warehouse				=	$e['warehouse'];
}

$regionoptions	=	"";
if(!empty($regions)) {
	foreach($regions as $s) {
		$regionoptions	.=	"<option ".($region==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->name."</option>";
	}
}
$warehouseoptions	=	"";
if(!empty($warehouses)) {
	foreach($warehouses as $s) {
		$warehouseoptions	.=	"<option ".($warehouse==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->name."</option>";
	}
}
$companyoptions	=	"";
if(!empty($companies)) {
	foreach($companies as $c) {
		$companyoptions	.=	"<option ".($company==$c->id?"SELECTED":"")." value='".$c->id."'>".$c->name."</option>";
	}
}
$taxcodeoptions	=	"";
if(!empty($taxcodes)) {
	foreach($taxcodes as $c) {
		$taxcodeoptions	.=	"<option ".($taxcode==$c->tax_code?"SELECTED":"")." value='".$c->tax_code."'>".$c->tax_code."</option>";
	}
}
$positionoptions	=	"";
if(!empty($positions)) {
	foreach($positions as $p) {
		$positionoptions	.=	"<option ".($position==$p->id?"SELECTED":"")." value='".$p->id."'>".$p->name."</option>";
	}
}
$deptoptions	=	"";
if(!empty($departments)) {
	foreach($departments as $d) {
		$deptoptions	.=	"<option ".($department==$d->id?"SELECTED":"")." value='".$d->id."'>".$d->name."</option>";
	}
}
$salaryGradeoptions	=	"";
if(!empty($salarygrades)) {
	foreach($salarygrades as $s) {
		// $salaryGradeoptions	.=	"<option ".($salarygradeid==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->grade." - P".$s->rate."</option>";
		$salaryGradeoptions	.=	"<option ".($salarygradeid==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->grade."</option>";
	}
}
$vesseloptions	=	"";
if(!empty($vessels)) {
	//$vesseloptions	.=	"<option ".($vessel_id==99999?"SELECTED":"")." value='99999'>Makati Office Based</option>";
	//$vesseloptions	.=	"<option ".($vessel_id==99998?"SELECTED":"")." value='99998'>Cebu Office Based</option>";
	foreach($vessels as $s) {
		$vesseloptions	.=	"<option ".($vessel_id==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->name."</option>";
	}
}
?>
<style>
#changeStatus {
	background-color: #DDD;
	float: left;
	position: absolute;
}
#changeStatus .form-group {
    margin: 15px;
}
</style>


<form class="form-horizontal" role="form" id="employee_info" name="employee_info"  action="<?php echo $formaction; ?>" method="post" enctype='multipart/form-data'>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span style="float:right; margin-right:10px; margin-top:-15px">
					<input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:checkform()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
					<input class="btn btn-default btn-sm"  value="Cancel" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:10px">
				</span>
				<div style="font-size:16px">
					<span class="glyphicon glyphicon-user"></span><?php echo (!isset($employee_record))?"New":"Edit"; ?> Employee&nbsp;&nbsp;
                </div>
			</div>
		</div>
		<div class="panel panel-default" style="font-size:12px">
			<div class="panel-heading" role="tab" id="headingOne">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				Personal Information
				</a>
			</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<div style="float:right; border:1 solid; margin-right:100px; display:table;">
						<img src="<?php echo $display_image; ?>" width="150px" border="2" style="border:#000000 thick"  />
						<div>
							Attached Picture: <input type="file" name="picture" id="picture">
						</div>
					</div>
					<div style="width:250px; margin-left:30px; float:left; display:table;">
						<div class="form-group">
							<label for="last_name">Last Name:</label>
							<input class="form-control input-sm"  type="text" name="last_name" id="last_name" value="<?php echo $lastname; ?>" />
						</div>
						<div class="form-group">
							<label for="first_name">First Name:</label>
							<div>
								<input class="form-control input-sm" type="text" name="first_name" id="first_name" value="<?php echo $firstname; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="middle_name">Middle Name:</label>
							<div>
								<input class="form-control input-sm" type="text" name="middle_name" id="middle_name" value="<?php echo $middlename; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="birth_date">Birth Date:</label>
							<div>
								<input class="form-control input-sm" type="text" name="birth_date" id="birth_date" value="<?php echo $birthdate; ?>" />
								<script>$("#birth_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});</script>
							</div>
						</div>
						<div class="form-group">
							<label for="gender">Gender:</label>
							<div>
								<select class="form-control input-sm" name="gender" id="gender">
									<option></option>
									<option <?php echo ($gender=="Male" ? "SELECTED" : ""); ?> value="Male">Male</option>
									<option <?php echo ($gender=="Female" ? "SELECTED" : ""); ?> value="Female">Female</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="mobile">Mobile #:</label>
							<div>
								<input class="form-control input-sm" type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="email">Email:</label>
							<div>
								<input class="form-control input-sm" type="text" name="email" id="email" value="<?php echo $email; ?>"  />
							</div>
						</div>
						<div class="form-group">
							<label  for="civil_status">Civil Status:</label>
							<div>
								<!--input class="form-control" type="text" name="civil_status" id="civil_status" value="<?php echo $civilstat; ?>" /-->
								<select class="form-control input-sm" name="civil_status" id="civil_status">
									<option></option>
									<option <?php echo ($civilstat=="Single" ? "SELECTED" : ""); ?> value="Single">Single</option>
									<option <?php echo ($civilstat=="Married" ? "SELECTED" : ""); ?> value="Married">Married</option>
									<option <?php echo ($civilstat=="Separated" ? "SELECTED" : ""); ?> value="Separated">Separated</option>
									<option <?php echo ($civilstat=="Widowed" ? "SELECTED" : ""); ?> value="Widowed">Widowed</option>
								</select>
							</div>
						</div>
					</div>
					<!---end of div float left --->
					<!---div float right --->
					<div style="width:250px; margin-right:130px; float:right; margin-top:25px">
						<div class="form-group">
							<label for="address">Address:</label>
							<div>
							<input class="form-control input-sm" type="text" name="address" id="address" value="<?php echo $address; ?>" /> 											</div>
						</div>
						<div class="form-group">
							<label  for="city">City/Province:</label>
							<div>
							<input class="form-control input-sm" type="text" name="city" id="city" value="<?php echo $city; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label  for="zip">Zip Code:</label>
							<div>
							<input class="form-control input-sm" type="text" name="zip" id="zip" value="<?php echo $zipcode; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label  for="emergency_num">Emergency Contact #:</label>
							<div>
								<input class="form-control input-sm" type="text" name="emergency_num" id="emergency_num" value="<?php echo $emergencycontactnum; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label  for="emergency_contact_person">Emergency Contact Person:</label>
							<div>
								<input class="form-control input-sm" type="text" name="emergency_contact_person" id="emergency_contact_person" value="<?php echo $emergencycontactperson; ?>" />
							</div>
						</div>
					</div>
					<!---end div float right --->
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingTwo">
				<h4 class="panel-title">
					<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					Work Information
					</a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				<div class="panel-body">
					<div style="width:250px; margin-left:30px; float:left;">
						<div class="form-group">
							<label for="empstat">Employee Status<span id="statusDetails" class="hide" title="Details"> ? </span>:</label>
							<div>
								<!--select onchange="javascript: empStatChange()" class="form-control input-sm" name="empstat" id="empstat" -->
								<select class="form-control input-sm" name="empstat" id="empstat" >
									<option></option>
									<option <?php echo ($empstat=="Inactive" ? "SELECTED" : ""); ?> value="Inactive">Inactive</option>
									<option <?php echo ($empstat=="AWOL" ? "SELECTED" : ""); ?> value="AWOL">Absent W/O Leave</option>
									<option <?php echo ($empstat=="Suspended" ? "SELECTED" : ""); ?> value="Suspended">Suspended</option>
									<option <?php echo ($empstat=="Contractual" ? "SELECTED" : ""); ?> value="Contractual">Contractual</option>
									<option <?php echo ($empstat=="Probationary" ? "SELECTED" : ""); ?> value="Probationary">Probationary</option>
									<option <?php echo ($empstat=="Regular" ? "SELECTED" : ""); ?> value="Regular">Regular</option>
									<option <?php echo ($empstat=="Resigned" ? "SELECTED" : ""); ?> value="Resigned">Resigned</option>
									<option <?php echo ($empstat=="Retired" ? "SELECTED" : ""); ?> value="Retired">Retired</option>
									<option <?php echo ($empstat=="Terminated" ? "SELECTED" : ""); ?> value="Terminated">Terminated</option>
								</select>
							</div>
						</div>

						<div id="changeStatus" class="hide">
							<div id="changeStatusTitle">
								<strong>
								<span class="glyphicon glyphicon-building"></span>
								From <?php echo $empstat; ?> to <span id="nextstatus">[next status]</span>
								</strong>
							</div>
							<div class="form-group">
								<div>
									<label for="change_effective_date">Date effective:</label>
									<input id="change_effective_date" class="status-change-input form-control input-sm" type="text" name="change_effective_date" />
								</div>
								<div>
									<label for="status_review_date">Review on:</label>
									<input id="status_review_date" class="status-change-input form-control input-sm" type="text" name="status_review_date" />
								</div>
								<div>
									<label for="status_remarks">Remarks:</label>
									<input id="status_remarks" class="status-change-input form-control input-sm" type="text" name="status_remarks" />
								</div>
								<script>
									$('#change_effective_date').datepicker({dateFormat: "yy-mm-dd", minDate: 0});
									$('#status_review_date').datepicker({dateFormat: "yy-mm-dd", minDate: 0});
								</script>
							</div>
						</div>

						<div class="form-group">
							<label for="date_hired">Date Hired:</label>
							<input class="form-control input-sm" type="text" name="date_hired" id="date_hired" value="<?php echo $datehired; ?>"/>
							<script>$("#date_hired").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});</script>
						</div>
						<div class="form-group">
							<label for="date_hired">Company:</label>
							<div>
								<select class="form-control input-sm" name="company" id="company" >
									<option></option>
									<?php echo $companyoptions; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="position">Position:</label>
							<div>
								<select class="form-control input-sm" name="position" id="position" >
									<option></option>
									<?php echo $positionoptions; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="region">Region:</label>
							<div>
								<select class="form-control input-sm" name="region" id="region" >
									<option></option>
									<?php echo $regionoptions; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="warehouse">Warehouse:</label>
							<div>
								<select class="form-control input-sm" name="warehouse" id="warehouse" >
									<option></option>
									<?php echo $warehouseoptions; ?>
								</select>
							</div>
						</div>
						<?php if($access_salary) { ?>
							<div class="form-group">
								<label for="salary_grade">Salary Grade:</label>
								<div>
									<select class="form-control input-sm" name="salary_grade" id="salary_grade" >
										<option></option>
										<?php echo $salaryGradeoptions; ?>
									</select>
								</div>
							</div>
						<?php } ?>
						<div class="form-group">
							<label for="department">Department:</label>
							<div>
								<select class="form-control input-sm" name="department" id="department" >
									<option></option>
									<?php echo $deptoptions; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="vessel">Assigned to:</label>
							<div>
								<select class="form-control input-sm" name="vessel" id="vessel" >
									<option></option>
									<?php echo $vesseloptions; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="experience">Experience:</label>
							<div>
								<textarea class="form-control input-sm" rows="4" id="experience" name="experience"><?php echo $experience; ?></textarea>
							</div>
						</div>

					</div>
					<div style="width:250px; margin-right:100px; float:right;">
						<div class="form-group">
							<label for="position">Employee ID:</label>
							<div>
								<input class="form-control input-sm" type="text" name="eid" id="eid" value="<?php echo $employeeid; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label  for="tax_code">Tax Code:</label>
							<div>
								<select class="form-control input-sm" name="tax_code" id="tax_code" >
									<option></option>
									<?php echo $taxcodeoptions; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label  for="tin_num">Tax Identification Number (TIN):</label>
							<div>
								<input class="form-control input-sm" type="text" name="tin_num" id="tin_num" value="<?php echo $tin; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label  for="sss_num">SSS Number:</label>
							<div>
								<input class="form-control input-sm" type="text" name="sss_num" id="sss_num" value="<?php echo $sssnum; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label  for="ph_num">PhilHealth Number:</label>
							<div>
								<input class="form-control input-sm" type="text" name="ph_num" id="ph_num" value="<?php echo $phnum; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label  for="pagibig_num">Pagibig Number:</label>
							<div>
								<input class="form-control input-sm" type="text" name="pagibig_num" id="pagibig_num" value="<?php echo $pagibig; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label  for="bank_account_num">Bank Account Number:</label>
							<div>
								<input class="form-control input-sm" type="text" name="bank_account_num" id="bank_account_num" value="<?php echo $bankacct; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="elfrate">ELF Rate:</label>
							<div>
								<input class="form-control input-sm" type="text" name="elfrate" id="elfrate" value="<?php echo $elfrate; ?>" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--
	<div id="changeStatusTitle" class="hide">
		<strong>
		<span class="glyphicon glyphicon-building"></span>
		From <?php echo $empstat; ?> to <span id="nextstatus">[next status]</span>
		</strong>
	</div>
	<div id="changeStatus" class="hide">
		<div class="form-group">
			<div>
				<label for="change_effective_date">Date effective:</label>
				<input id="change_effective_date" class="form-control input-sm" type="text" name="change_effective_date" />
			</div>
			<div>
				<label for="status_review_date">Review on:</label>
				<input id="status_review_date" class="form-control input-sm" type="text" name="status_review_date" />
			</div>
			<div>
				<label for="status_remarks">Remarks:</label>
				<input id="status_remarks" class="form-control input-sm" type="text" name="status_remarks" />
			</div>
		</div>
	</div>
	-->
</form>


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

var changed = false;
function empStatChange() {
	var currentstatus	=	"<?php echo $empstat; ?>";
	var e 				=	document.getElementById("empstat");
	var nextstatus		=	e.options[e.selectedIndex].value;
	var effdate	=	$("#change_effective_date");
	var revdate	=	$("#status_review_date");
	var remark	=	$("#status_remarks");
	$("#nextstatus").html(nextstatus);
	effdate.value = "";
	revdate.value = "";
	remark.value = "";
	/*
	$("#changeStatus").addClass("hide");
	if(currentstatus !== nextstatus) {
		if(changed == false) {
			toastr['info']("Effectivity date and remarks are required in the employment status form.", "ABAS says");
			changed == true;
		}
		$("#changeStatus").removeClass("hide");
		// show edit position div
	}
	*/
};
$(".status-change-input").blur(function() {
	var effdate	=	$("#change_effective_date");
	var revdate	=	$("#status_review_date");
	var remark	=	$("#status_remarks");
	/*
	alert(
		"Remark:"+remark.val()+"\n"+
		"Effdate:"+effdate.val()+"\n"+
		"Revdate:"+revdate.val()+"\n"
	);
	//*/
	if(remark.val()!=null && effdate.val()!=null && remark.val()!="" && effdate.val()!="") {
		toastr['success']("Form filled up!", "ABAS says");
		$("#changeStatus").addClass("hide");
		$("#statusDetails").removeClass("hide");
		return true;
	}
	else {
		return false;
	}
});
$( "#statusDetails" ).hover(
	function() {
		$("#changeStatus").removeClass( "hide" );
	}, function() {
		$( "#changeStatus" ).addClass( "hide" );
	}
);
function checkform() {

	var msg="";
	var patt1=/^[0-9]+$/i;
	// /*
	var last_name=document.getElementById("last_name").value;
	if (last_name==null || last_name=="") {
		msg+="Last Name is required! <br/>";
	}
	var first_name=document.getElementById("first_name").value;
	if (first_name==null || first_name=="") {
		msg+="First Name is required! <br/>";
	}
	var company=document.getElementById("company").selectedIndex;
	if (company==null || company=="") {
		msg+="Company is required! <br/>";
	}
	// var birth_date=document.getElementById("birth_date").value;
	// if (birth_date==null || birth_date=="") {
		// msg+="Birthdate is required! <br/>";
	// }
	var gender=document.getElementById("gender").selectedIndex;
	if (gender==null || gender=="") {
		msg+="Gender is required! <br/>";
	}
	// var mobile=document.getElementById("mobile").value;
	// if (mobile==null || mobile=="") {
		// msg+="Mobile is required! <br/>";
	// }
	// else if (!patt1.test(mobile.replace("+",""))) {
		// msg+="Only numbers and the '+' sign are allowed in Mobile! <br/>";
	// }
	// var civil_status=document.getElementById("civil_status").selectedIndex;
	// if (civil_status==null || civil_status=="") {
		// msg+="Civil Status is required! <br/>";
	// }
	// var address=document.getElementById("address").value;
	// if (address==null || address=="") {
		// msg+="Address is required! <br/>";
	// }
	// var city=document.getElementById("city").value;
	// if (city==null || city=="") {
		// msg+="City is required! <br/>";
	// }
	// var emergency_num=document.getElementById("emergency_num").value;
	// if (emergency_num==null || emergency_num=="") {
		// msg+="Emergency Contact Number is required! <br/>";
	// }
	// else if (!patt1.test(emergency_num.replace("+",""))) {
		// msg+="Only numbers and the '+' sign are allowed in Emergency Contact Number! <br/>";
	// }
	// var emergency_contact_person=document.getElementById("emergency_contact_person").value;
	// if (emergency_contact_person==null || emergency_contact_person=="") {
		// msg+="Emergency Contact Person is required! <br/>";
	// }

	var tin_num=document.getElementById("tin_num").value;
	if (tin_num!="") {
		if (!patt1.test(tin_num)) {
			msg+="Only numbers are allowed in TIN! <br/>";
		}
		else {
			if (tin_num.length!=9) {
				msg+="Tax Identification Number requires 9 characters! <br/>";
			}
		}
	}
	var sss_num=document.getElementById("sss_num").value;
	if (sss_num!="") {
		if (!patt1.test(sss_num)) {
			msg+="Only numbers are allowed in SSS Number! <br/>";
		}
		else {
			if (sss_num.length!=10) {
				msg+="SSS Number requires 10 characters! <br/>";
			}
		}
	}
	var email=document.getElementById("email").value;
	if (email!="") {
		if (validateEmail(email)==false) {
			msg+="Email is not valid! <br/>";
		}
	}
	var ph_num=document.getElementById("ph_num").value;
	if (ph_num!="") {
		if (!patt1.test(ph_num)) {
			msg+="Only numbers are allowed in PhilHealth Number! <br/>";
		}
		else {
			if (ph_num.length!=12) {
				msg+="PhilHealth Number requires 12 characters! <br/>";
			}
		}
	}

	//*/

	/*
	<?php if($require_img==true) : ?>
	var picture=document.getElementById("picture").value;
	if (picture==null || picture=="") {
		msg+="Picture is required! <br/>";
	}
	<?php endif ?>
	var empstat=document.getElementById("empstat").selectedIndex;
	if (empstat==null || empstat=="") {
			msg+="Employee Status is required! <br/>";
	}
	var date_hired=document.getElementById("date_hired").value;
	if (date_hired==null || date_hired=="") {
		msg+="Date Hired is required! <br/>";
	}
	var position=document.getElementById("position").selectedIndex;
	if (position==null || position=="") {
		msg+="Position is required! <br/>";
	}
	var salary_grade=document.getElementById("salary_grade").selectedIndex;
	if (salary_grade==null || salary_grade=="") {
		msg+="Salary Grade is required! <br/>";
	}
	var department=document.getElementById("department").selectedIndex;
	if (department==null || department=="") {
		msg+="Department is required! <br/>";
	}
	var pagibig_num=document.getElementById("pagibig_num").value;
	if (pagibig_num!=null || pagibig_num!="") {
		if (pagibig_num!=null || pagibig_num!="") {
			msg+="Pag-Ibig Number is required! <br/>";
		}
	}
	else if (!patt1.test(pagibig_num)) {
		msg+="Only numbers are allowed in Pag-Ibig Number! <br/>";
	}
	// var middle_name=document.getElementById("middle_name").value;
	// if (middle_name==null || middle_name=="") {
		// msg+="Middle Name is required! <br/>";
	// }
	var email=document.getElementById("email").value;
	if (email==null || email=="") {
		msg+="Email is required! <br/>";
	}
	else if (validateEmail(email)==false) {
		msg+="Value in Email is not valid! <br/>";
	}
	var zip=document.getElementById("zip").value;
	if (zip==null || zip=="") {
		msg+="Zip Code is required! <br/>";
	}
	else if (!patt1.test(mobile)) {
		msg+="Only numbers are allowed in Zip Code! <br/>";
	}
	var experience=document.getElementById("experience").value;
	if (experience==null || experience=="") {
		msg+="Experience is required! <br/>";
	}
	var tax_code=document.getElementById("tax_code").value;
	if (tax_code==null || tax_code=="") {
		msg+="Tax Code is required! <br/>";
	}
	var bank_account_num=document.getElementById("bank_account_num").value;
	if (bank_account_num==null || bank_account_num=="") {
		msg+="Bank Account Number is required! <br/>";
	}
	else if (!patt1.test(bank_account_num)) {
		msg+="Only numbers are allowed in Bank Account Number! <br/>";
	}
	//*/
	if(msg!="") {
		toastr['error'](msg, "You have missing input!");
		return false;
	}
	else {
		document.getElementById("employee_info").submit();
		return true;
	}

}
// $(document).ready(function () {
    // $("input#submit").click(function(){
        // $.ajax({
            // type: "POST",
            // url: "billing.cfm", //process to mail
            // data: $('form.contact').serialize(),
            // success: function(msg){
                // $("#thanks").html(msg) //hide button and show thank you
               	// $("#form-content").modal('hide'); //hide popup
            // },
            // error: function(){

				// alert("failure");
            // }
        // });
    // });
// });
