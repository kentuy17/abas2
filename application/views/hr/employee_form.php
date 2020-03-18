<?php
$employeeid=$lastname=$firstname=$middlename=$birthdate=$gender=$mobile=$email=$civilstat=$address=$city=$zipcode=$emergencycontactnum=$emergencycontactperson=$profilepic=$datehired=$position=$salarygrade=$department=$experience=$taxcode=$tin=$sssnum=$phnum=$pagibig=$bankacct=$elfrate=$allowance=$company=$empstat=$vessel_id=$salarygradeid=$dependent1=$dependent2=$dependent3=$dependent4=$d1_birthdate=$d2_birthdate=$d3_birthdate=$d4_birthdate=$group=$division=$dependents_edit=$section_id=$sub_section_id="";
$display_image	=	LINK.'assets/images/icons/1689490804.png';
$formaction		=	HTTP_PATH.'hr/employee_profile/insert';
$require_img	=	true;
$access_leave	=	$this->Abas->checkPermissions("human_resources|leave",false);
$access_loan	=	$this->Abas->checkPermissions("human_resources|loan",false);
$access_elf		=	$this->Abas->checkPermissions("human_resources|elf",false);
$view_salary	=	$this->Abas->checkPermissions("human_resources|salary_viewing",false);
$edit_salary	=	$this->Abas->checkPermissions("human_resources|salary_editing",false);
$forced_edit	=	$this->Abas->checkPermissions("human_resources|forced_editing",false);
$allow_edit		=	true;
$cancel_btn 	= "Discard";
if(isset($employee_record)) {
	$cancel_btn = "Cancel";
	// echo "<pre>";print_r($employee_record);echo "</pre>";
	$e			=	$employee_record;
	$require_img=	false;
	if($e['profile_pic']!="") {
		$display_image	=	LINK.'assets/images/employeepic/'.$e['profile_pic'];
	}
	if($forced_edit == false) {
		$allow_edit				=	false;
	}
	$formaction				=	HTTP_PATH.'hr/employee_profile/update/'.$e['id'];
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
	//$zipcode				=	$e['zipcode'];
	$emergencycontactnum	=	$e['emergency_contact_num'];
	$emergencycontactperson	=	$e['emergency_contact_person'];
	$profilepic				=	$e['profile_pic'];
	$datehired				=	($e['date_hired']!="0000-00-00 00:00:00")?date("Y-m-d", strtotime($e['date_hired'])):"";
	$position				=	$e['position'];
	$salarygrade			=	$e['salary_grade'];
	$salarygradeid			=	$e['salary_grade'];
	$department				=	$e['department'];
	$experience				=	$e['experience'];
	$taxcode				=	$e['tax_code'];
	$tin					=	$e['tin_num'];
	$sssnum					=	$e['sss_num'];
	$phnum					=	$e['ph_num'];
	$pagibig				=	$e['pagibig_num'];
	$bankacct				=	$e['bank_account_num'];
	$elfrate				=	$e['elf_rate'];
	$allowance				=	$e['allowance'];
	$company				=	$e['company_id'];
	$empstat				=	$e['employee_status'];
	$vessel_id				=	$e['vessel_id'];
	$section_id				= 	$e['section_id'];
	$group 					= 	$e['department_group'];
	$division 				=	$e['division_id'];
	$sub_section_id			=	$e['sub_section_id'];

	if(isset($dependents)){
		foreach($dependents as $row){
			$dependents_edit .= '<div class="row item-row-dependents command-row-dependents">
										<hr>
										<div class="col-lg-4 col-md-4 col-xs-4">
											<label for="dependent_first_name[]">First Name:</label>
											<input class="form-control input-sm" type="text" name="dependent_first_name[]" id="dependent_first_name[]" value="'.$row['first_name'].'" />
										</div>
										<div class="col-lg-4 col-md-4 col-xs-4">
											<label for="dependent_middle_name[]">Middle Name:</label>
											<input class="form-control input-sm" type="text" name="dependent_middle_name[]" id="dependent_middle_name[]" value="'.$row['middle_name'].'" />
										</div>
										<div class="col-lg-4 col-md-4 col-xs-4[]">
											<label for="dependent_last_name">Last Name:</label>
											<input class="form-control input-sm" type="text" name="dependent_last_name[]" id="dependent_last_name[]" value="'.$row['last_name'].'" />
										</div>
										<div class="col-lg-4 col-md-4 col-xs-4">
											<label for="dependent_birth_date[]">Birth Date:</label>
											<input class="form-control input-sm" type="date" name="dependent_birth_date[]" id="dependent_birth_date[]" value="'.$row['birth_date'].'" />
										</div>
										<div class="col-lg-7 col-md-7 col-xs-7">
											<label for="dependent_relationship[]">Relationship to Employee:</label>
											<input class="form-control input-sm" type="text" name="dependent_relationship[]" id="dependent_relationship[]" value="'.$row['dependent_relationship'].'" />
										</div>
										<a class="btn-remove-row-dependents btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
		       					 </div>';
		}
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
$secoptions	=	"";
$sections = $this->Hr_model->getSectionsByDepartment($department);
if(isset($sections)){
	foreach($sections as $sc) {
		$secoptions	.=	"<option ".($section_id==$sc->id?"SELECTED":"")." value='".$sc->id."'>".$sc->name."</option>";
	}
}

$subsecoptions	=	"";
$sub_sections = $this->Hr_model->getSubsectionsBySection($section_id);
if($sub_sections != ''){
	foreach($sub_sections as $ss) {
		$subsecoptions	.=	"<option ".($sub_section_id==$ss->id?"SELECTED":"")." value='".$ss->id."'>".$ss->name."</option>";
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
if($section_id==0 || $section_id=='' || $section_id==NULL){
	$sec_disabled = true;
}else{
	$sec_disabled = false;
}

if($sub_section_id==0 || $sub_section_id=='' || $sub_section_id==NULL){
	$sub_disabled = true;
}else{
	$sub_disabled = false;
}

$dependents = '<div class="row item-row-dependents command-row-dependents">
				<hr>
				<div class="col-lg-4 col-md-4 col-xs-4">
					<label for="dependent_first_name[]">First Name:</label>
					<input class="form-control input-sm" type="text" name="dependent_first_name[]" id="dependent_first_name[]" value="" />
				</div>
				<div class="col-lg-4 col-md-4 col-xs-4">
					<label for="dependent_middle_name[]">Middle Name:</label>
					<input class="form-control input-sm" type="text" name="dependent_middle_name[]" id="dependent_middle_name[]" value="" />
				</div>
				<div class="col-lg-4 col-md-4 col-xs-4[]">
					<label for="dependent_last_name">Last Name:</label>
					<input class="form-control input-sm" type="text" name="dependent_last_name[]" id="dependent_last_name[]" value="" />
				</div>
				<div class="col-lg-4 col-md-4 col-xs-4">
					<label for="dependent_birth_date[]">Birth Date:</label>
					<input class="form-control input-sm" type="date" name="dependent_birth_date[]" id="dependent_birth_date[]" value="" />
				</div>
				<div class="col-lg-7 col-md-7 col-xs-7">
					<label for="dependent_relationship[]">Relationship to Employee:</label>
					<input class="form-control input-sm" type="text" name="dependent_relationship[]" id="dependent_relationship[]" value="" />
				</div>
				<a class="btn-remove-row-dependents btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
	        </div>';

$dependents_append= trim(preg_replace('/\s+/',' ', $dependents));


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
	<?php //echo $this->Mmm->createCSRF(); ?>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
				<h2 class="panel-title">
					 <?php echo (!isset($employee_record))?"Add New":"Edit"; ?> Employee
					
				</h2>
			</div>

		</div>
		<div class='panel-body'>
			<div class="panel panel-info">
				<div class="panel-heading" role="tab" id="headingOne">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					Personal Information
					<span class="glyphicon glyphicon-chevron-down pull-right"></span>
					</a>
				</h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
					<div class="panel-body">
						<div class="col-lg-4 col-md-6 col-sm-12">
							<img class="center-block img-responsive" src="<?php echo $display_image; ?>" />
							<input class="center-block" type="file" name="picture" id="picture">
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="last_name">Last Name*:</label>
							<input class="form-control input-sm"  type="text" name="last_name" id="last_name" value="<?php echo $lastname; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="first_name">First Name*:</label>
							<input class="form-control input-sm" type="text" name="first_name" id="first_name" value="<?php echo $firstname; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="middle_name">Middle Name:</label>
							<input class="form-control input-sm" type="text" name="middle_name" id="middle_name" value="<?php echo $middlename; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="birth_date">Birth Date*:</label>
							<input class="form-control input-sm" type="date" name="birth_date" id="birth_date" value="<?php echo $birthdate; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="gender">Gender*:</label>
							<select class="form-control input-sm" name="gender" id="gender">
								<option></option>
								<option <?php echo ($gender=="Male" ? "SELECTED" : ""); ?> value="Male">Male</option>
								<option <?php echo ($gender=="Female" ? "SELECTED" : ""); ?> value="Female">Female</option>
							</select>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="mobile">Mobile #:</label>
							<input class="form-control input-sm" type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="email">Email:</label>
							<input class="form-control input-sm" type="text" name="email" id="email" value="<?php echo $email; ?>"  />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label  for="civil_status">Civil Status*:</label>
							<select class="form-control input-sm" name="civil_status" id="civil_status">
								<option></option>
								<option <?php echo ($civilstat=="Single" ? "SELECTED" : ""); ?> value="Single">Single</option>
								<option <?php echo ($civilstat=="Married" ? "SELECTED" : ""); ?> value="Married">Married</option>
								<option <?php echo ($civilstat=="Separated" ? "SELECTED" : ""); ?> value="Separated">Separated</option>
								<option <?php echo ($civilstat=="Widowed" ? "SELECTED" : ""); ?> value="Widowed">Widowed</option>
							</select>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="address">Address:</label>
							<input class="form-control input-sm" type="text" name="address" id="address" value="<?php echo $address; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="city">City/Province:</label>
							<input class="form-control input-sm" type="text" name="city" id="city" value="<?php echo $city; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="emergency_num">Emergency Contact #:</label>
							<input class="form-control input-sm" type="text" name="emergency_contact_num" id="emergency_num" value="<?php echo $emergencycontactnum; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="emergency_contact_person">Emergency Contact Person:</label>
							<input class="form-control input-sm" type="text" name="emergency_contact_person" id="emergency_contact_person" value="<?php echo $emergencycontactperson; ?>" />
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading" role="tab" id="headingDependents">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseDependents" aria-expanded="false" aria-controls="collapseDependents">
						Dependents
						<span class="glyphicon glyphicon-chevron-down pull-right"></span>
						</a>
					</h4>
				</div>
				<div id="collapseDependents" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingDependents">

					<div class='pull-right' style='float:left; margin-top:5px; margin-left:5px'>                          
						<a id='btn_add_row_dependents' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
						<a id='btn_remove_row_dependents' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
					</div>
					
					<div class='panel-body item-row-container-dependents'>
						<?php 
							if(!isset($dependents)){
								echo $dependents;
							}else{
								echo $dependents_edit;
							}	
						?>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading" role="tab" id="headingTwo">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						Work Information
						<span class="glyphicon glyphicon-chevron-down pull-right"></span>
						</a>
					</h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
					<div class="panel-body">
						
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="date_hired">Date Hired*:</label>
							<input class="form-control input-sm" type="date" name="date_hired" id="date_hired" value="<?php echo $datehired; ?>"/>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="company">Company*:</label>
							<select class="form-control input-sm" name="company_id" id="company">
								<option></option>
								<?php echo $companyoptions; ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="position">Employee ID:</label>
							<input class="form-control input-sm" type="text" name="employee_id" id="eid" value="<?php echo $employeeid; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="department">Division*:</label>
							<select class="form-control input-sm" name="division_id" id="division">
								<option></option>
								<?php foreach ($divisions as $row) { ?>
									<option value="<?=$row->id?>" <?=($division==$row->id ? "selected" : "")?>>
										<?=$row->name?>
									</option>
								<?php } ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="department">Group*:</label>
							<select class="form-control input-sm" name="group">
								<option></option>
								<option value="Sea-based" <?=($group=='Sea-based' ? 'SELECTED' : '')?>>Sea-based</option>
								<option value="Land-based" <?=($group=='Land-based' ? 'SELECTED' : '')?>>Land-based</option>
							</select>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="department">Department*:</label>
							<select class="form-control input-sm" name="department_id" id="department">
								<option></option>
								<?php echo $deptoptions; ?>
							</select>
						</div>
						<!-- start here -->
						
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="department">Section*:</label>
							<select class="form-control input-sm" name="section_id" id="section" <?=($sec_disabled ? "disabled"  : "")?>>
								<?php 
									echo $secoptions;
								?>
							</select>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label>Sub-section:</label>
							<select class="form-control input-sm" name="sub_section_id" id="sub_section" <?=($sub_disabled ? "disabled" : "")?>>
								<?php 
									echo $subsecoptions;
								 ?>
							</select>
						</div>
						<?php if(!isset($employee_record)){ ?>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="empstat">Employment Status*<span id="statusDetails" class="hide" title="Details"> ? </span>:</label>
							<select class="form-control input-sm" <?php echo (!$allow_edit)?"disabled=disabled":""; ?> name="empstat" id="empstat" >
								<option></option>
								<option <?php echo ($empstat=="Probationary" ? "SELECTED" : ""); ?> value="Probationary">Probationary</option>
								<option <?php echo ($empstat=="Regular" ? "SELECTED" : ""); ?> value="Regular">Regular</option>
								<option <?php echo ($empstat=="Casual" ? "SELECTED" : ""); ?> value="Casual">Casual</option>
								<option <?php echo ($empstat=="Fixed Term" ? "SELECTED" : ""); ?> value="Fixed Term">Fixed Term</option>
								<option <?php echo ($empstat=="Project-based" ? "SELECTED" : ""); ?> value="Project-based">Project-based</option>
								<option <?php echo ($empstat=="Part-time" ? "SELECTED" : ""); ?> value="Part-time">Part-time</option>
								<option <?php echo ($empstat=="Preventive Suspension" ? "SELECTED" : ""); ?> value="Preventive Suspension">Preventive Suspension</option>
								<option <?php echo ($empstat=="Suspended" ? "SELECTED" : ""); ?> value="Suspended">Suspended</option>
								<option <?php echo ($empstat=="Resigned" ? "SELECTED" : ""); ?> value="Resigned">Resigned</option>
								<option <?php echo ($empstat=="Retired" ? "SELECTED" : ""); ?> value="Retired">Retired</option>
								<option <?php echo ($empstat=="AWOL" ? "SELECTED" : ""); ?> value="AWOL">AWOL</option>
								<option <?php echo ($empstat=="Terminated" ? "SELECTED" : ""); ?> value="Terminated">Terminated</option>
							</select>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="position">Position*:</label>
							<select class="form-control input-sm" <?php echo (!$allow_edit)?"disabled=disabled":""; ?> name="position" id="position" >
								<option></option>
								<?php echo $positionoptions; ?>
							</select>
						</div>
							<?php if($edit_salary) { ?>
								<div class="col-lg-4 col-md-6 col-xs-12">
									<label for="salary_grade">Salary Grade:</label>
									<select class="form-control input-sm" <?php echo (!$allow_edit)?"disabled=disabled":""; ?> name="salary_grade" id="salary_grade" >
										<option></option>
										<?php echo $salaryGradeoptions; ?>
									</select>
								</div>
							<?php } ?>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="vessel">Assigned to*:</label>
							<select class="form-control input-sm" <?php echo (!$allow_edit)?"disabled=disabled":""; ?> name="vessel" id="vessel" >
								<option></option>
								<?php echo $vesseloptions; ?>
							</select>
						</div>
						<?php }?>
						
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label  for="tax_code">Tax Code*:</label>
							<select class="form-control input-sm" name="tax_code" id="tax_code" >
								<option></option>
								<?php echo $taxcodeoptions; ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="tin_num">Tax Identification Number (TIN)*:</label>
							<input class="form-control input-sm" type="text" name="tin_num" id="tin_num" value="<?php echo $tin; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="sss_num">SSS Number*:</label>
							<input class="form-control input-sm" type="text" name="sss_num" id="sss_num" value="<?php echo $sssnum; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="ph_num">PhilHealth Number*:</label>
							<input class="form-control input-sm" type="text" name="ph_num" id="ph_num" value="<?php echo $phnum; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="pagibig_num">Pag-ibig Number*:</label>
							<input class="form-control input-sm" type="text" name="pagibig_num" id="pagibig_num" value="<?php echo $pagibig; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="bank_account_num">Bank Account or ATM Number:</label>
							<input class="form-control input-sm" type="text" name="bank_account_num" id="bank_account_num" value="<?php echo $bankacct; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<!--<label for="elfrate">ELF Rate:</label>-->
							<input class="form-control input-sm" type="hidden" name="elf_rate" id="elfrate" value="<?php echo $elfrate; ?>" />
						</div>
						<div class="col-lg-4 col-md-6 col-xs-12">
							<label for="allowance">Monthly Allowance:</label>
							<input class="form-control input-sm" type="number" name="allowance" id="allowance" value="<?php echo $allowance; ?>" />
						</div>
						<div class="col-lg-12 col-md-12 col-xs-12">
							<label for="experience">Remarks/Experience:</label>
							<textarea class="form-control input-sm" rows="4" id="experience" name="experience"><?php echo $experience; ?></textarea>
						</div>
					</div>
				</div>
			<span class="pull-right">
				<br><br>
				<input class="btn btn-success btn-md" type="button" value="Save" onclick="checkform()" id="submitbtn">
				<input class="btn btn-danger btn-md" type="button" value="<?php echo $cancel_btn?>" data-dismiss="modal">
			</span>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">

$("#birth_dateX").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
	$(".dependent-birth-dateX").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});

$('#btn_remove_row_dependents').click(function(){
	$('.item-row-dependents:last').remove();
});
$(document).on('click', '.btn-remove-row-dependents', function() {
	$(this).parent().remove();
});
$('#btn_add_row_dependents').click(function(){
	$('.item-row-container-dependents').append('<?php echo $dependents_append; ?>');
});

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
};
$(".status-change-input").blur(function() {
	var effdate	=	$("#change_effective_date");
	var revdate	=	$("#status_review_date");
	var remark	=	$("#status_remarks");

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

$('#department').change(function(){   
  var department_id = $(this).val();
	  if(department_id!=""){
	  	$('#section').prop('disabled',false);
		  $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH;?>hr/set_sections/"+department_id,
		     success:function(data){
		        var sections = $.parseJSON(data);
		        $('#section').find('option').remove().end().append('<option value=""></option>').val('');
		        if(sections.length>0){
					for(var i = 0; i < sections.length; i++){
			       		var section = sections[i];
			       		var option = $('<option />');
					    option.attr('value', section.id).text(section.name);
					    $('#section').append(option);
			        }
			    }else{
			    	$('#section').prop('disabled',true);
			    	$('#sub_section').find('option').remove();
			    	$('#sub_section').prop('disabled',true);
			    }
		     } 	
		  });
	  }else{
	  		$('#section').prop('disabled',true);
	  		$('#sub_section').find('option').remove();
	  		$('#sub_section').prop('disabled',true);
	  }
});

$('#section').change(function(){   
  var section_id = $(this).val();
	  if(section_id!=""){
	  	$('#sub_section').prop('disabled',false);
		  $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH;?>hr/set_subsections/"+section_id,
		     success:function(data){
		        var subsections = $.parseJSON(data);
		        $('#sub_section').find('option').remove().end().append('<option value=""></option>').val('');
		        if(subsections.length>0){
					for(var i = 0; i < subsections.length; i++){
			       		var subsection = subsections[i];
			       		var option = $('<option />');
					    option.attr('value', subsection.id).text(subsection.name);
					    $('#sub_section').append(option);
			        }
		    	}else{
		    		$('#sub_section').prop('disabled',true);
		    	}
		     } 	
		  });
	  }else{
	  		$('#sub_section').prop('disabled',true);
	  }
});

function checkform() {

	var msg="";
	var patt1=/^[0-9]+$/i;
	var last_name=document.getElementById("last_name").value;
	if (last_name==null || last_name=="") {
		msg+="Last Name is required! <br/>";
	}
	var first_name=document.getElementById("first_name").value;
	if (first_name==null || first_name=="") {
		msg+="First Name is required! <br/>";
	}
	
	var birth_date=document.getElementById("birth_date").value;
	if (birth_date==null || birth_date=="") {
		msg+="Birthdate is required! <br/>";
	}
	var gender=document.getElementById("gender").selectedIndex;
	if (gender==null || gender=="") {
		msg+="Gender is required! <br/>";
	}

	var civil_status=document.getElementById("civil_status").selectedIndex;
	if (civil_status==null || civil_status=="") {
		msg+="Civil Status is required! <br/>";
	}

	var date_hired=document.getElementById("date_hired").value;
	if (date_hired==null || date_hired=="") {
		msg+="Date Hired is required! <br/>";
	}

	if($("empstat").is(":visible")){
		var company=document.getElementById("company").selectedIndex;
		if (company==null || company=="") {
			msg+="Company is required! <br/>";
		}
		var department=document.getElementById("department").selectedIndex;
		if (department==null || department=="") {
			msg+="Department is required! <br/>";
		}
		var empstat=document.getElementById("empstat").selectedIndex;
		if (empstat==null || empstat=="") {
				msg+="Employment Status is required! <br/>";
		}

		var position=document.getElementById("position").selectedIndex;
		if (position==null || position=="") {
			msg+="Position is required! <br/>";
		}
		var salary_grade=document.getElementById("salary_grade").selectedIndex;
		if (salary_grade==null || salary_grade=="") {
			msg+="Salary Grade is required! <br/>";
		}

		var assigned_to=document.getElementById("vessel").selectedIndex;
		if (assigned_to==null || assigned_to=="") {
			msg+="Assigned To is required! <br/>";
		}
	}

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
	}else{
		msg+="Tax Identification Number is required! <br/>";
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
	}else{
		msg+="SSS Number is required! <br/>";
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
	}else{
		msg+="PhilHealth Number is required! <br/>";
	}
	var pagibig_num=document.getElementById("pagibig_num").value;
	if (pagibig_num==null || pagibig_num=="") {
			msg+="Pag-ibig Number is required! <br/>";
	}

	if(msg!="") {
		toastr['error'](msg, "You have missing input!");
		return false;
	}
	else {
		$('body').addClass('is-loading');
		$('#modalDialog').modal('toggle');
		document.getElementById("employee_info").submit();
		return true;
	}

}
	

</script>