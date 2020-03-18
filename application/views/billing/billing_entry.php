

<?php

$employeeid=$lastname=$firstname=$middlename=$birthdate=$gender=$mobile=$email=$civilstat=$address=$city=$zipcode=$emergencycontactnum=$emergencycontactperson=$profilepic=$datehired=$position=$salarygrade=$department=$experience=$taxcode=$tin=$sssnum=$phnum=$pagibig=$bankacct=$elfrate=$company=$empstat=$datehired=$salarygradeid="";

$display_image	=	HTTP_PATH.'assets/images/icons/1689490804.png';
$formaction		=	HTTP_PATH.'Billing/insertBilling';
$require_img	=	false;
$access_leave	=	$this->Abas->checkPermissions("employee_profile|leave",false);
$access_loan	=	$this->Abas->checkPermissions("employee_profile|loan",false);
$access_elf		=	$this->Abas->checkPermissions("employee_profile|elf",false);
$access_salary	=	$this->Abas->checkPermissions("employee_profile|salary",false);

if(isset($employee_record)) {
	// echo "<pre>";print_r($employee_record);echo "</pre>";
	$e			=	$employee_record;
	$require_img=	false;

	if($e['profile_pic']!="") {
		$display_image	=	HTTP_PATH.'assets/images/employeepic/'.$e['profile_pic'];
	}

	$formaction		=	HTTP_PATH.'hr/employee_profile/update/'.$e['id'];
	$employeeid		=	$e['employee_id'];
	$lastname		=	$e['last_name'];
	$firstname		=	$e['first_name'];
	$middlename		=	$e['middle_name'];
	$birthdate		=	($e['birth_date']!="1970-01-01 00:00:00")?date("Y-m-d", strtotime($e['birth_date'])):"";
	$gender			=	$e['gender'];
	$mobile			=	$e['mobile'];
	$email			=	$e['email'];
	$civilstat		=	$e['civil_status'];
	$address		=	$e['address'];
	$city			=	$e['city'];
	$zipcode		=	$e['zipcode'];
	$emergencycontactnum	=	$e['emergency_contact_num'];
	$emergencycontactperson	=	$e['emergency_contact_person'];
	$profilepic		=	$e['profile_pic'];
	$datehired		=	($e['date_hired']!="1970-01-01 00:00:00")?date("Y-m-d", strtotime($e['date_hired'])):"";
	$position		=	$e['position'];
	$salarygrade	=	$e['salary_grade'];
	$salarygradeid	=	$e['salary_grade_id'];
	$department		=	$e['department'];
	$experience		=	$e['experience'];
	$taxcode		=	$e['tax_code'];
	$tin			=	$e['tin_num'];
	$sssnum			=	$e['sss_num'];
	$phnum			=	$e['ph_num'];
	$pagibig		=	$e['pagibig_num'];
	$bankacct		=	$e['bank_account_num'];
	$elfrate		=	$e['elf_rate'];
	$company		=	$e['company_id'];
	$empstat		=	$e['employee_status'];
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
		$salaryGradeoptions	.=	"<option ".($salarygradeid==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->grade." - P".$s->rate."</option>";
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


<form class="form-horizontal" role="form" id="billing_info" name="billing_info"  action="<?php echo $formaction; ?>" method="post" enctype='multipart/form-data'>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin-top:-20px">
		<div class="panel panel-default">
			<div class="panel-heading">
				<span style="float:right; margin-right:10px; margin-top:-15px">
					<input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:document.forms['billing_info'].submit();" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
					<input class="btn btn-default btn-sm"  value="Cancel" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:10px">
				</span>
				<div style="font-size:16px">
					<span class="glyphicon glyphicon-calculator"></span>Billing List&nbsp;&nbsp;
                </div>
			</div>
            <div class="panel-body">
            
            			<div class="form-group">
							<label for="user">Waybill Number:</label>
							<div>
							<input class="form-control input-sm" type="text" name="wb_no" id="wb_no"  /> 																	
                    		</div>
						</div>
						<div class="form-group">
							<label  for="pass">Servicer:</label>
							<div>
							<select class="form-control input-sm" name="servicer" id="servicer"  />
                            	<option></option>
                                
                                <option></option>
                                
                            </select>
							</div>
						</div>
                    
                    
            
            </div>
            
	</div>
</div>	
 </form>
    	
        <div class="panel panel-default" style="font-size:12px">
			
            
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					
					<div style="width:250px; margin-left:30px; float:left; display:table;">
						
						
                        <table class="table table-striped table-bordered table-hover table-condensed" data-toggle="table" data-url="" data-search="true"   data-height="600"  data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]">
                        <thead>
                            <tr>
                                <th data-field="cid" data-halign="center"  data-align="center">*</th>
                                <th data-field="sid" data-halign="center"  data-align="center">Waybill</th>
                                <th data-field="fullname"  data-halign="center">Service Type</th>
                                <th data-field="level"  data-halign="center">Servicer</th>
                                <th data-field="level"  data-halign="center">Status</th>
                                <th data-field="level"  data-halign="center">Remark</th>
                                
                                <th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >*</th>
                            </tr>
                        </thead>
                        
                        <tbody>
						          
                                  		                      			
										<tr>
											<td data-field="contract_id" data-halign="center"  data-align="center">
                                            
                                            #vStatus[i].contractId#</td>
                                            <td data-field="vessel_name" data-halign="center"  data-align="center">
                                            #vStatus[i].schedStat#
                                            #vStatus[i].vesselName#</td>
											<td data-field="activity"  data-halign="center">
                                            	<span style="font-size:14px">#vStatus[i].current_location#</span> <br>  
                                                <span style="font-size:11px; color:##990000">
                                                
                                                #vStatus[i].current_activity#</span>
                                                
                                                </td>
											<td data-field="content"  data-halign="center">
                                            	#vStatus[i].destination#<br>
                                                <span style="color:##000066">(#NumberFormat(vStatus[i].cargo_qty,',')# #vStatus[i].unit# of #vStatus[i].cargo_description#)</span></td>
											<td data-field="contract_cost"  data-halign="right" align="right">#vStatus[i].cargo_amount#</td>
											<td data-field="expenses"  data-halign="right" align="right">#vStatus[i].vessel_expenses#</tD>								
											<td data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" ><img src="libs/images/button_icons/small/zoom.png" style="cursor:pointer" title="View Ship Profile"
											class="btn btn-info btn-s" data-toggle="modal" data-target="##myModal"></td>
										</tr>            
                        
                        				
                        </body>
                        
                    </table>
                        
                        	
					</div>
					<!---end of div float left --->
					
				</div>
			</div>
		</div>
		
		
	</div>
	


<script type="text/javascript">

function submitWaybill(){
	
	alert('test');
	//document.forms['billing_info'].submit();
	
	//document.getElementById("billing_info").submit();

}


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
	/*$('#empstat').popover({
		html : true,
		title: function() {
			return $("#changeStatusTitle").html();
		},
		content: function() {
			return $("#changeStatus").html();
		},
		callback: function() {
			alert("callback");
			$('#change_effective_date').datepicker();
			$('#status_review_date').datepicker();
		}
	});*/

	effdate.value = "";
	revdate.value = "";
	remark.value = "";
	// $("#changeStatus").addClass("hide");
	if(currentstatus !== nextstatus) {
		if(changed == false) {
			toastr['info']("Effectivity date and remarks are required in the employment status form.", "ABAS says");
			changed == true;
		}
		$("#changeStatus").removeClass("hide");
		// show edit position div
	}
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
	var birth_date=document.getElementById("birth_date").value;
	if (birth_date==null || birth_date=="") {
		msg+="Birthdate is required! <br/>";
	}
	var gender=document.getElementById("gender").selectedIndex;
	if (gender==null || gender=="") {
		msg+="Gender is required! <br/>";
	}
	var mobile=document.getElementById("mobile").value;
	if (mobile==null || mobile=="") {
		msg+="Mobile is required! <br/>";
	}
	else if (!patt1.test(mobile.replace("+",""))) {
		msg+="Only numbers and the '+' sign are allowed in Mobile! <br/>";
	}
	var civil_status=document.getElementById("civil_status").selectedIndex;
	if (civil_status==null || civil_status=="") {
		msg+="Civil Status is required! <br/>";
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
	var address=document.getElementById("address").value;
	if (address==null || address=="") {
		msg+="Address is required! <br/>";
	}
	var city=document.getElementById("city").value;
	if (city==null || city=="") {
		msg+="City is required! <br/>";
	}
	var emergency_num=document.getElementById("emergency_num").value;
	if (emergency_num==null || emergency_num=="") {
		msg+="Emergency Contact Number is required! <br/>";
	}
	else if (!patt1.test(emergency_num.replace("+",""))) {
		msg+="Only numbers and the '+' sign are allowed in Emergency Contact Number! <br/>";
	}
	var emergency_contact_person=document.getElementById("emergency_contact_person").value;
	if (emergency_contact_person==null || emergency_contact_person=="") {
		msg+="Emergency Contact Person is required! <br/>";
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
	var middle_name=document.getElementById("middle_name").value;
	if (middle_name==null || middle_name=="") {
		msg+="Middle Name is required! <br/>";
	}
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
