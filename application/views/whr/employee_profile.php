
<style>

	.col1{width:170px; font-weight:600}
	.col2{width:270px}
	.col3{width:200px; font-weight:600}
	.col4{width:250px}
#leaveApplication, #overtimeForm, #undertimeForm, #loanForm, #loanPaymentForm, #frmEmployeeHistory {
	background-color: #DDD;
	float: left;
	position: absolute;
	left:50%;
	padding:10px;
}
</style>

<?php
$employeeid=$lastname=$firstname=$middlename=$birthdate=$gender=$mobile=$email=$civilstat=$address=$city=$zipcode=$emergencycontactnum=$emergencycontactperson=$profilepic=$datehired=$position=$salarygrade=$department_name=$experience=$taxcode=$tin=$sssnum=$phnum=$pagibig=$bankacct=$elfrate=$leavecredits=$company_name=$vessel=$absences=$warehouse=$region="-";
$access_leave	=	$this->Abas->checkPermissions("employee_profile|leave",false);
$access_loan	=	$this->Abas->checkPermissions("employee_profile|loan",false);
$access_elf		=	$this->Abas->checkPermissions("employee_profile|elf",false);
$access_salary	=	$this->Abas->checkPermissions("employee_profile|salary",false);
$access_status	=	$this->Abas->checkPermissions("employee_profile|edit",false);
$access_ot		=	$this->Abas->checkPermissions("employee_profile|overtime",false);
$access_ut		=	$access_ot;

$display_img	=	LINK.'assets/images/icons/1689490804.png';
if(isset($employee_record)) {
	$e			=	$employee_record;
	$this->Mmm->debug($e);
	if($e['profile_pic']!="") {
		$display_img=	LINK.'assets/images/employeepic/'.$e['profile_pic'];
	}
	$employeeid				=	$e['employee_id'];
	$lastname				=	$e['last_name'];
	$firstname				=	$e['first_name'];
	$middlename				=	$e['middle_name'];
	$birthdate				=	$e['date_hired']!="0000-00-00 00:00:00" ? date("j F Y", strtotime($e['birth_date'])) : "";
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
	$datehired				=	$e['date_hired']!="0000-00-00 00:00:00" ? date("j F Y", strtotime($e['date_hired'])) : "";
	$position				=	$e['position'];
	$salarygrade			=	$e['salary_grade'];
	$department				=	$e['department'];
	$region					=	$e['region'];
	$warehouse				=	$e['warehouse'];
	$experience				=	$e['experience'];
	$taxcode				=	$e['tax_code'];
	$tin					=	$e['tin_num'];
	$sssnum					=	$e['sss_num'];
	$phnum					=	$e['ph_num'];
	$pagibig				=	$e['pagibig_num'];
	$bankacct				=	$e['bank_account_num'];
	$elfrate				=	$e['elf_rate'];
	if($elfrate!=''){	$elfrate=number_format($elfrate,2); }else{ $elfrate = 0;}

	$absences				=	$e['absences'];
	$leavecredits			=	$e['leave_credits'];
	$elfcontri				=	$e['total_elf_contribution'];
	$employee_id			=	$e['employee_id'];
	$employment_status		=	$e['employee_status'];
	$company_name			=	$e['company_name'];
	$department_name		=	$e['department_name'];
	$position_name			=	$e['position_name'];
	$warehouse_name			=	$e['warehouse_name'];
	$region_name			=	$e['region_name'];
	$vessel_id				=	$e['vessel_id'];
	if($e['modified_on'] == "" || $e['modified_on'] == "1970-01-01 00:00:00" || $e['modified_on'] == "0000-00-00 00:00:00" || $e['modified_on'] == null) {

	}
	else {
		$last_edited		=	date("j F Y",strtotime($e['modified_on']))." at ".date("h:i a",strtotime($e['modified_on']));
	}
	// $this->Mmm->debug($e);
}
$payroll_table	=	"<tr style='background:#FFF'><td align='center'>No payroll info found!</td></tr>";
$elf_table	=	"<tr style='background:#FFF'><td align='center'>No ELF info found!</td></tr>";

$vessel	=	"-";
if($vessel_id==99999) {
	$vessel		=	"Makati Office Based";
}
elseif($vessel_id==99998) {
	$vessel		=	"Cebu Office Based";
}
elseif($vessel_id==99997) {
	$vessel		=	"Tacloban Office Based";
}
elseif($vessel_id==9999) {
	$vessel		=	"Maintenance Crew";
}
else {
	$vesselq		=	$this->db->query("SELECT * FROM vessels WHERE id=".$vessel_id);
	if($vesselq!=false) {
		if($vesselq->row()) {
			$vessel	=	$vesselq->row();
			$vessel	=	$vessel->name;
		}
	}
}

$vesseloptions	=	"";
if(!empty($vessels)) {
	foreach($vessels as $s) {
		$vesseloptions	.=	"<option ".($vessel_id==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->name."</option>";
	}
}
if(!empty($payroll_info)) {
	$payroll_table	=	"";
	$elf_table		=	"";
	foreach($payroll_info AS $pi) {
	// $this->Mmm->debug($pi);
		$payroll_row	=	"<tr style='background:#FFF'><td align='center'>Payroll data error!</td></tr>";
		$elf_row		=	"<tr style='background:#FFF'><td align='center'>Payroll data error!</td></tr>";
		$summary		=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$pi->payroll_id);
		if($summary!=false) {
			if($summary->row()) {
				$payroll_row		=	"";
				$elf_row			=	"";
				$summary			=	$summary->row();
				$period				=	$summary->payroll_coverage." ".date("F Y", strtotime($summary->payroll_date."-01"));
				$gross_pay			=	$pi->salary+$pi->others;
				$total_deductions	=	-$pi->tax-$pi->sss_contri_ee-$pi->phil_health_contri-$pi->pagibig_contri-$pi->elf_contri;
				$loan_payment		=	0; //todo
				$net_pay			=	$gross_pay+$total_deductions;

				// $payroll_row	.=	"<tr class='viewPayroll' style='cursor:pointer; background:#FFF; text-align:right;' data-target='#payslip' data-toggle='modal' href='".HTTP_PATH."payroll_history/payslips/employee/".$pi->id."'>";
				$payroll_row	.=	"<tr class='viewPayroll' style='cursor:pointer; background:#FFF; text-align:right;' onClick='getPayslip(".$pi->id.")'>";
				$payroll_row	.=	"<td style='text-align:center;'>".$period."</td>";
				$payroll_row	.=	"<td>".$this->Abas->currencyFormat($gross_pay)."</td>";
				$payroll_row	.=	"<td>".$this->Abas->currencyFormat($total_deductions)."</td>";
				$payroll_row	.=	"<td>".$this->Abas->currencyFormat($loan_payment)."</td>";
				$payroll_row	.=	"<td>".$this->Abas->currencyFormat($net_pay)."</td>";
				$payroll_row	.=	"</tr>";

				$elf_row		.=	"<tr>";
				$elf_row		.=	"<td>".$period."</td>";
				$elf_row		.=	"<td>".$this->Abas->currencyFormat($pi->elf_contri)."</td>";
				$elf_row		.=	"</tr>";
			}
		}
		$payroll_table	.=	$payroll_row;
		$elf_table		.=	$elf_row;
	}
}

$employee_history_table	=	"<tr style='background:#FFF'><td align='center' colspan='99'>No previous info found!</td></tr>";
if(!empty($employee_history)) {
	$employee_history_table	=	"";
	foreach($employee_history as $eh) {
		// $this->Mmm->debug($eh);
		$positionName			=	$position_name;
		$effectivity_date		=	date("j F Y", strtotime($eh['effectivity_date']));
		$value_changed			=	$eh['value_changed'];
		$from_val				=	$eh['from_val'];
		$to_val					=	$eh['to_val'];
		$display				=	true;

		if($value_changed=="Salary Grade") {
			if($access_salary==false) {
				$display		=	false;
			}
			if($from_val!="") {
				$from_salgrade			=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$from_val);
				if($from_salgrade!=false) {
					$from_salgrade		=	$from_salgrade->row();
					$from_val			=	$from_salgrade->grade;
				}
			}

			if($to_val!="") {
				$to_salgrade			=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$to_val);
				if($to_salgrade != false) {
					$to_salgrade		=	$to_salgrade->row();
					$to_val				=	$to_salgrade->grade;
				}
			}
		}
		if($value_changed=="Position") {
			if($from_val!="") {
				$from_position			=	$this->db->query("SELECT * FROM positions WHERE id=".$from_val);
				if($from_position!=false) {
					$from_position		=	$from_position->row();
					$from_val			=	$from_position->name;
				}
			}

			$to_position			=	$this->db->query("SELECT * FROM positions WHERE id=".$to_val);
			$to_position			=	$to_position->row();
			$to_val					=	$to_position->name;
		}
		if($value_changed=="Vessel") {
			if($from_val!="") {
				$from_position			=	$this->db->query("SELECT * FROM vessels WHERE id=".$from_val);
				if($from_position!=false) {
					$from_position		=	$from_position->row();
					$from_val			=	isset($from_position->name) ? $from_position->name : $from_val;
					if($from_val == 99999) { $from_val = "Makati Office Based"; }
					if($from_val == 99998) { $from_val = "Cebu Office Based"; }
					if($from_val == 99997) { $from_val = "Tacloban Office Based"; }
					if($from_val == 99996) { $from_val = "Maintenance Crew"; }
				}
			}

			if($to_val!="") {
				$to_position	=	$this->db->query("SELECT * FROM vessels WHERE id=".$to_val);
				$to_position	=	$to_position->row();
				$to_val			=	isset($to_position->name) ? $to_position->name : $to_val;
				if($to_val == 99999) { $to_val = "Makati Office Based"; }
				if($to_val == 99998) { $to_val = "Cebu Office Based"; }
				if($to_val == 99997) { $to_val = "Tacloban Office Based"; }
				if($to_val == 99996) { $to_val = "Maintenance Crew"; }
			}
		}

		if($value_changed=="Company" && is_numeric($from_val)) {
			if($from_val!="") {
				$from_position			=	$this->db->query("SELECT * FROM companies WHERE id=".$from_val);
				if($from_position!=false) {
					$from_position		=	$from_position->row();
					$from_val			=	isset($from_position->name) ? $from_position->name : $from_val;
				}
			}

			$to_position			=	$this->db->query("SELECT * FROM companies WHERE id=".$to_val);
			if($to_position!=false) {
				$to_position	=	$to_position->row();
				$to_val			=	isset($to_position->name) ? $to_position->name : $to_val;
			}
		}



		if($display==true) {
			$employee_history_table	.=	"<tr style='text-align:center;'>";
			$employee_history_table	.=	"<td>".$effectivity_date."</td>";
			$employee_history_table	.=	"<td>".$value_changed."</td>";
			$employee_history_table	.=	"<td>".$from_val."</td>";
			$employee_history_table	.=	"<td>".$to_val."</td>";
			$employee_history_table	.=	"</tr>";
		}
	}
}
$ot_table	=	"<tr style='background:#FFF'><td align='center' colspan='99'>No overtime info found!</td></tr>";
if(!empty($overtimes)) {
	$ot_table	=	"";
	foreach($overtimes as $ot) {
		$ot_date			=	date("j F Y",strtotime($ot['ot_date']));
		$ot_time			=	$ot['ot_time'];
		// $ot_time			=	date("h:i:s",strtotime($ot['ot_time']));
		$rate				=	$ot['rate'];
		$reason				=	$ot['reason'];
		$approved			=	$ot['approved'];
		$computed			=	$ot['computed'];
		$color				=	"color:#FFFFFF;";
		$bgcolor			=	"background-color:#00AA00;";
		// $link				=	"<a class='manage-ot-link' href='".HTTP_PATH."whr/overtime/approve/".$e['id']."/".$ot['id']."'><button type='button' class='btn btn-primary btn-sm' style='cursor:pointer;'>Approve</button></a>";
		$link				=	"<a class='manage-ot-link' href='".HTTP_PATH."whr/overtime/cancel/".$e['id']."/".$ot['id']."'><button type='button' class='btn btn-primary btn-sm' style='cursor:pointer;'>Cancel</button></a>";
		$approved_indicator	=	"&#10060;";
		if($computed<>0) {
			$approved_indicator	=	"&#10004;";
		}
		if($approved<>0) {
			$bgcolor			=	"";
			$color				=	"";
			// $link				=	($computed==0) ? "<a class='manage-ot-link' href='".HTTP_PATH."whr/overtime/revoke/".$e['id']."/".$ot['id']."'><button type='button' class='btn btn-primary btn-sm' style='cursor:pointer;'>Revoke Approval</button></a>" : "";
		}

		$ot_table	.=	"<tr style='text-align:center; ".$color." ".$bgcolor."'>";
		$ot_table	.=	"<td>".$ot_date."</td>";
		$ot_table	.=	"<td>".$ot_time."</td>";
		$ot_table	.=	"<td>".$rate."</td>";
		$ot_table	.=	"<td>".$reason."</td>";
		$ot_table	.=	"<td>".$approved_indicator."</td>";
		$ot_table	.=	($computed==false)?"<td>".$link."</td>":"";
		$ot_table	.=	"</tr>";
	}
}
$ut_table	=	"<tr style='background:#FFF'><td align='center' colspan='99'>No undertime info found!</td></tr>";
if(!empty($undertimes)) {
	$ut_table	=	"";
	foreach($undertimes as $ut) {
		$ut_date			=	date("j F Y",strtotime($ut['ut_date']));
		$ut_time			=	$ut['ut_time'];
		$reason				=	$ut['reason'];
		$approved			=	$ut['approved'];
		$computed			=	$ut['computed'];
		$color				=	"color:#FFFFFF;";
		$bgcolor			=	"background-color:#00AA00;";
		// $link				=	"<a class='manage-ot-link' href='".HTTP_PATH."whr/undertime/approve/".$e['id']."/".$ut['id']."'><button type='button' class='btn btn-primary btn-sm' style='cursor:pointer;'>Approve</button></a>";
		$link				=	"<a class='manage-ot-link' href='".HTTP_PATH."whr/undertime/cancel/".$e['id']."/".$ut['id']."'><button type='button' class='btn btn-primary btn-sm' style='cursor:pointer;'>Approve</button></a>";
		$approved_indicator	=	"&#10060;";
		if($computed<>0) {
			$approved_indicator	=	"&#10004;";
		}
		if($approved<>0) {
			$bgcolor			=	"";
			$color				=	"";
			// $link				=	($computed==0) ? "<a class='manage-ot-link' href='".HTTP_PATH."whr/undertime/revoke/".$e['id']."/".$ut['id']."'><button type='button' class='btn btn-primary btn-sm' style='cursor:pointer;'>Revoke Approval</button></a>" : "";
		}

		$ut_table	.=	"<tr style='text-align:center; ".$color." ".$bgcolor."'>";
		$ut_table	.=	"<td>".$ut_date."</td>";
		$ut_table	.=	"<td>".$ut_time."</td>";
		$ut_table	.=	"<td>".$reason."</td>";
		$ut_table	.=	"<td>".$approved_indicator."</td>";
		$ut_table	.=	($computed==false)?"<td>".$link."</td>":"";
		$ut_table	.=	"</tr>";
	}
}
$loan_table	=	"<tr style='background:#FFF'><td align='center' colspan='99'>No loan info found!</td></tr>";
if(!empty($loans)) {
	$loan_table	=	"";
	foreach($loans as $l) {
		$loanType			=	$l['loan_type'];
		$loanDate			=	date("j F Y",strtotime($l['date_loan']));
		$loanDueDate		=	$l['due_date_loan']!="1970-01-01 00:00:00" ? date("j F Y",strtotime($l['due_date_loan'])) : "";
		$principal			=	$l['amount_loan'];
		$monthly			=	$l['monthly_amortization'];

		// payments
		$payments	=	$this->db->query("SELECT * FROM whr_loan_payments WHERE loan_id=".$l['id']);
		$total_paid	=	0;
		$loan_payment_table	=	"";
		if($payments!=false) {
			if($payments->row()) {
				$payments	=	$payments->result();
				foreach($payments as $ctr=>$p) {
					$loan_payment_table	.=	"<tr>";
					$loan_payment_table	.=	"<th>".($ctr+1)."</th>";
					$loan_payment_table	.=	"<th>Date Paid</th><td>".date("j F Y",strtotime($p->date_payment))."</td>";
					$loan_payment_table	.=	"<th>Amount Paid</th><td>".$p->amount."</td>";
					$loan_payment_table	.=	(ENVIRONMENT=="development")?"<th>Payroll</th><td>".$p->payroll_id."</td>":"";
					$loan_payment_table	.=	"</tr>";
					$total_paid	=	$total_paid + $p->amount;
				}
			}
		}
		// payments

		$loan_table	.=	"<tr style='text-align:center;'>";
		$loan_table	.=	"<td>".$loanType."</td>";
		$loan_table	.=	"<td>".$loanDate."</td>";
		$loan_table	.=	"<td>".$loanDueDate."</td>";
		$loan_table	.=	"<td>".$this->Abas->currencyFormat($monthly)."</td>";
		$loan_table	.=	"<td>".$this->Abas->currencyFormat($principal)."</td>";
		$loan_table	.=	"<td>".$this->Abas->currencyFormat($total_paid)."</td>"; //total payment
		$loan_table	.=	"<td>".$this->Abas->currencyFormat($principal - $total_paid)."</td>"; //remaining balance
		$loan_table	.=	"<td><a href='#' id='loan".$l['id']."' onclick='javascript:showPayment(".$l['id'].")'>Payments</a></td>"; //payment button
		$loan_table	.=	"</tr>";
		$loan_table	.=	"<tr id='payment".$l['id']."' class='hide'>";
		$loan_table	.=	"<td colspan='99'>";
			$loan_table	.=	"<table border=1>";
			$loan_table	.=	"<tr>";
			$loan_table	.=	"<td colspan='99'><a href='#' id='loan".$l['id']."' onclick='javascript:loanPayment(".$l['id'].")'>Manual Payment</a></td>";
			$loan_table	.=	$loan_payment_table;
			$loan_table	.=	"</tr>";
			$loan_table	.=	"</table>";
		$loan_table	.=	"</td>";
		$loan_table	.=	"</tr>";
	}
}

$leave_table	=	"<tr style='background:#FFF'><td align='center' colspan='99'>No leave info found!</td></tr>";
if(!empty($leaves)) {
	$leave_table	=	"";
	foreach($leaves as $l) {
		$leaveType			=	$l['leave_type'];
		$leaveDate			=	date("j F Y",strtotime($l['date_from']));
		$leaveEndDate		=	date("j F Y",strtotime($l['date_to']));
		$no_of_days			=	$l['no_of_days'];
		$reason				=	$l['reason'];

		$link			=	"<a href='".HTTP_PATH."whr/apply_leave/".$e['id']."/".$l['id']."'>Cancel</a>";
		$color			=	"background-color:". (($l['calculate']==0)? "AA9999" : "99AA99");
		$leave_table	.=	"<tr style='text-align:center; ".$color."'>";
		$leave_table	.=	"<td>".$leaveType."</td>";
		$leave_table	.=	"<td>".$leaveDate."</td>";
		$leave_table	.=	"<td>".$leaveEndDate."</td>";
		$leave_table	.=	"<td>".$no_of_days."</td>";
		$leave_table	.=	"<td>".$reason."</td>";
		// $leave_table	.=	"<td>".(($l['calculate']==0)? "No" : "Yes")."</td>";
		$leave_table	.=	"<td>".$link."</td>";
		$leave_table	.=	"</tr>";
	}
}
$positionoptions	=	"";
if(!empty($positions)) {
	foreach($positions as $p) {
		$positionoptions	.=	"<option ".($position==$p->id?"SELECTED":"")." value='".$p->id."'>".$p->name."</option>";
	}
}
$salaryGradeOptions	=	"";
if(!empty($salarygrades)) {
	foreach($salarygrades as $s) {
		$salaryGradeOptions	.=	"<option ".($salarygrade==$s->grade?"SELECTED":"")." value='".$s->id."'>".$s->grade." - P".$s->rate."</option>";
	}
}

?>

 <div class="panel panel-primary">
	<div class="panel-heading">
		<div style="font-size:16px"><span class="glyphicon glyphicon-user"></span>  Employee Profile
        	<button type="button" class="close" data-dismiss="modal" onclick="javascript:window.location.reload()">
            <span class="glyphicon glyphicon-remove"></span>
        </div>
	</div>
  	<div class="panel-body">
  		<div style="margin-top:-20px">
        	<h3><span style="color:#000066; font-weight:600"><?php echo $lastname.", ".$firstname." ".$middlename; ?></span></h3>
            <h5><span style="color:#990000; font-weight:600">Employee ID: <?php echo $employeeid	 ?></span></h5>
			<?php if(isset($last_edited)) {
				echo "<small>Last edited on ".$last_edited."</small>";
			}?>
            <hr />
        </div>
    	<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="##pinfo-tab">Personal Info</a></li>
			<li><a data-toggle="tab" href="##winfo-tab">Work Info</a></li>
			<!-- li><a data-toggle="tab" href="##ehist-tab">Work History</a></li-->
			<?php /*if($access_salary) { ?><li><a data-toggle="tab" href="##phist-tab">Payroll</a></li><?php } ?>
			<?php if($access_ot) { ?><li><a data-toggle="tab" href="##overtime-tab">Overtime</a></li><?php } ?>
			<?php if($access_ut) { ?><li><a data-toggle="tab" href="##undertime-tab">Undertime</a></li><?php } ?>
			<?php if($access_elf) { ?><li><a data-toggle="tab" href="##elf-tab">ELF</a></li><?php } ?>
			<?php if($access_loan) { ?><li><a data-toggle="tab" href="##loans-tab">Loans</a></li><?php } ?>
			<?php if($access_leave) { ?><li><a data-toggle="tab" href="##leaves-tab">Leaves</a></li><?php } */ ?>
        </ul>
        <div class="tab-content">
			<div id="pinfo-tab" class="tab-pane fade in active">
				<div style="margin-left:0px; margin-top:50px">
					<div class="panel-group" style="margin-top:-50px">
						<div class="panel panel-danger">
							<div class="panel-heading">
								<span></span>Personal Information
							</div>
							<div class="panel-body">
								<div  style="float:right; border:1 solid; margin-left:570px; margin-top:0px; position:absolute">
									<img src="<?php echo $display_img; ?>" class = "img-responsive img-thumbnail" width="150px" height="150px" border="1" />
								</div>
								<table data-toggle="table" class="table table-striped table borderless " data-cache="false"  style="font-size:14px; border:#CCCCCC thin solid; width:550px">
									<tbody>
										<tr>
											<td align="left" class="col1">Birthdate:</td>
											<td align="left" class="col2"><?php echo $birthdate; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Gender:</td>
											<td align="left" class="col2"><?php echo $gender; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Civil Status:</td>
											<td align="left" class="col2"><?php echo $civilstat; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Contact Number:</td>
											<td align="left" class="col2"><?php echo $mobile; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Email Address:</td>
											<td align="left" class="col2"><?php echo $email; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Civil Status:</td>
											<td align="left" class="col2"><?php echo  $civilstat; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Address:</td>
											<td align="left" class="col2"><?php  echo $address.", ".$city; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Emergency Contact Person:</td>
											<td align="left" class="col2"><?php echo $emergencycontactperson; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Emergency Contact #:</td>
											<td align="left" class="col2"><?php echo $emergencycontactnum; ?></td>
										</tr>
									</tbody>
							  </table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="winfo-tab" class="tab-pane fade">
				<div class="panel-group" style="margin-top:-0px">
					<div class="panel panel-info">
						<div class="panel-heading">
							Work Information
						</div>
						<div class="panel-body">
							 <table data-toggle="table" class="table table-striped table borderless " data-cache="false"  style="font-size:14px; border:#CCCCCC thin solid; width:730px">
								<tbody>
									<tr>
										<td align="left" class="col3">Date Hired:</td>
										<td align="left" class="col2"><?php echo $datehired; ?></td>
										<td align="left" class="col1">ELF Rate:</td>
										<td align="left" class="col2"><?php echo  $elfrate; ?></td>
									</tr>
									<tr>
										<td align="left" class="col3">Company:</td>
										<td align="left" class="col2"><?php echo $company_name; ?></td>
										<td align="left" class="col1">TIN:</td>
										<td align="left" class="col2"><?php  echo $tin; ?></td>
									</tr>

									<tr>
										<td align="left" class="col3">Employment Status:</td>
										<td align="left" class="col2"><?php echo $employment_status;  ?></td>
										<td align="left" class="col1">SSS #:</td>
										<td align="left" class="col2"><?php echo $sssnum; ?></td>
									</tr>
									<!--tr>
										<td align="left" class="col3">Department:</td>
										<td align="left" class="col2"><?php echo $department_name; ?></td-->
									<tr>
										<td align="left" class="col3">Region:</td>
										<td align="left" class="col2"><?php echo $region_name; ?></td>
										<td align="left" class="col1">PhilHealth #:</td>
										<td align="left" class="col2"><?php  echo $phnum; ?></td>
									</tr>
									<tr>
										<td align="left" class="col3">Position:</td>
										<td align="left" class="col2"><?php echo $position_name;?></td>
										<td align="left" class="col1">Pag-ibig #:</td>
										<td align="left" class="col2"><?php echo $pagibig; ?></td>
									</tr>
									<?php if($access_salary): ?>
									<tr>
										<td align="left" class="col3">Salary Grade:</td>
										<td align="left" class="col2"><?php echo $salarygrade; ?></td>
										<td align="left" class="col1">Bank Account #:</td>
										<td align="left" class="col2"><?php echo $bankacct;  ?></td>
									</tr>
									<?php endif; ?>
									<!--tr>
										<td align="left" class="col3">Vessel:</td>
										<td align="left" class="col2"><?php echo $vessel; ?></td-->
									<tr>
										<td align="left" class="col3">Warehouse:</td>
										<td align="left" class="col2"><?php echo $warehouse_name; ?></td>
										<td align="left" class="col1" colspan="2"></td>
									</tr>
								</tbody>
							</table>
						</div>
                    </div>
            	</div>
			</div>



			<?php

			#########################################
			#########################################
			#######                           #######
			#######     Not needed for        #######
			#######       Warehouse           #######
			#######         HRIS              #######
			#######                           #######
			#########################################
			#########################################
			/*

			<div id="ehist-tab" class="tab-pane fade">
				<div class="panel-group" style="margin-top:-0px">
					<div class="panel panel-success">
						<div class="panel-heading">
							Employment History
							<?php if($access_status): ?>
							<div style="float:right;">
								<!--button type="button" class="btn btn-primary btn-sm" id="recommend" style="cursor:pointer; margin-top:-5px">Make recommendation</button-->
								<a href="#" id="btnEmployeeHistory">Add Action</a>
							</div>
							<?php endif; ?>
						</div>
						<div class="panel-body">

							<!-- Employment History form -->
							<div id="frmEmployeeHistory" class="hide">
								<div id="employeeHistoryTitle">
									<strong>
									<span class="glyphicon glyphicon-building"></span>
									New Employee History

									<div style= "float:right; >
   									 <span class="close-btn"><a href="#" id="myEmployee">X</a> </span>

									</div>
									</strong>
								</div>
								<form id="employeehistory_form" name="overtime_form"  action="<?php echo HTTP_PATH."whr/ehistory/insert/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<div>
										Effectivity Date: <input type='text' id="effectivity_date" class='span1' name="effectivity_date" style="width:100px" value="<?php echo date("Y-m-d"); ?>" />
									</div>
									<script>
										$('#effectivity_date').datepicker();
									</script>
									<div>
										Position:
										<select name="position" class='form-control input-sm' style="width:100px">
											<option value=""></option>
											<?php echo $positionoptions; ?>
										</select>
									</div>
									<?php if($access_status==true): ?>
									<div>
										Employment Status:
										<select class="form-control input-sm" name="empstat" id="empstat" style="width:100px">
											<option></option>
											<option <?php echo ($employment_status=="Inactive" ? "SELECTED" : ""); ?> value="Inactive">Inactive</option>
											<option <?php echo ($employment_status=="AWOL" ? "SELECTED" : ""); ?> value="AWOL">Absent W/O Leave</option>
											<option <?php echo ($employment_status=="Suspended" ? "SELECTED" : ""); ?> value="Suspended">Suspended</option>
											<option <?php echo ($employment_status=="Contractual" ? "SELECTED" : ""); ?> value="Contractual">Contractual</option>
											<option <?php echo ($employment_status=="Probationary" ? "SELECTED" : ""); ?> value="Probationary">Probationary</option>
											<option <?php echo ($employment_status=="Regular" ? "SELECTED" : ""); ?> value="Regular">Regular</option>
											<option <?php echo ($employment_status=="Resigned" ? "SELECTED" : ""); ?> value="Resigned">Resigned</option>
											<option <?php echo ($employment_status=="Retired" ? "SELECTED" : ""); ?> value="Retired">Retired</option>
											<option <?php echo ($employment_status=="Terminated" ? "SELECTED" : ""); ?> value="Terminated">Terminated</option>
										</select>
									</div>
									<?php endif; ?>
									<?php if($access_salary==true): ?>
									<div>
										Salary Grade:
										<select name="salgrade" class='form-control input-sm' style="width:100px">
											<option value=""></option>
											<?php echo $salaryGradeOptions; ?>
										</select>
									</div>
									<?php endif; ?>
									<div>
										Assigned To:
										<select name="assignedto" class='form-control input-sm' style="width:100px">
											<option value=""></option>
											<?php echo $vesseloptions; ?>
										</select>
									</div>
									<div style="margin-top:10px">
										<input type='submit' class='btn btn-primary btn-sm' value='Save' />
									</div>
								</form>
							</div>


							<table data-toggle="table" id="hr-paylist" class="table table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" style="font-size:12px; border:#CCCCCC thin solid">
								<thead style="color:#FFFFFF; background:#333333">
									<tr align="center">
										<td width="82">Effectivity Date</td>
										<td width="100">Value Changed</td>
										<td width="130">From</td>
										<td width="117">To</td>
									</tr>
								</thead>
								<tbody>
									<?php
									echo $employee_history_table;
									?>
								</tbody>
							</table>
						</div>
                    </div>
            	</div>
			</div>
			<?php if($access_salary): ?>
			<div id="phist-tab" class="tab-pane fade">
				<div class="panel-group" style="margin-top:-0px">
					<div class="panel panel-success">
						<div class="panel-heading">
							Payroll History
						</div>
						<div class="panel-body">
							<table data-toggle="table" id="hr-paylist" class="table table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" style="font-size:12px; border:#CCCCCC thin solid">
								<thead style="color:#FFFFFF; background:#333333">
									<tr align="center">
										<td width="100">Payroll Date</td>
										<td width="126">Gross Salary</td>
										<td width="130">Total Deductions</td>
										<td width="117">Loan Payment</td>
										<td width="82">Net Pay</td>
									</tr>
								</thead>
								<tbody>
									<?php
									echo $payroll_table;
									?>
								</tbody>
							</table>
						</div>
                    </div>
            	</div>
			</div>
			<?php endif; ?>
			<?php if($access_ot): ?>
			<div id="overtime-tab" class="tab-pane fade">
				<div class="panel-group" style="margin-top:-0px">
					<div class="panel panel-success">
						<div class="panel-heading">
							<span style="float:right; margin-top:-1px; font-size:12px">
								&nbsp;&nbsp;
								<span class="glyphicon glyphicons-alarm"></span>&nbsp;
								<a href="#" id="overtimeFormBtn">Overtime Application</a>
							</span>
							Overtime
						</div>
						<div class="panel-body">
							<!-- Overtime form -->
							<div id="overtimeForm" class="hide">
								<div id="overtimeFormTitle">
									<strong>
									<span class="glyphicon glyphicon-building"></span>
									Add Overtime

									<div style= "float:right; >
   									 	<span class="close-btn"><a href="#" id="myOver">X</a> </span>

									</div>
									</strong>
								</div>
								<form id="overtime_form" name="overtime_form"  action="<?php echo HTTP_PATH."whr/overtime/insert/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<div>
										Date: <input type='text' id="ot_date" class='span1' name="ot_date" style="width:100px" />
									</div>
									<script>
										$('#ot_date').datepicker();
									</script>
									<div>
										Time(hh:mm:ss): <input type='text' class='span1' id="ot_time" name="ot_time" style="width:100px" />
									</div>
									<div>
										Rate:
										<select name="ot_rate" id='ot_rate' class='span1' style="width:100px" >
											<option value="F"></option>
											<option value="25%">Regular day - 25%</option>
											<option value="30%">Sundays/Special Holidays - 30%</option>
											<option value="200%">Legal Holidays - 200%</option>
										</select>
									</div>
									<div>
										Reason: <input type='text' class='span1' name="ot_reason" style="width:100px" />
									</div>
									<div style="margin-top:10px">
										<input type='button' class='btn btn-primary btn-sm' value='Save' onclick="javascript:checkOvertimeForm()">
									</div>
								</form>
							</div>

							<table data-toggle="table" id="hr-paylist" class="table table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" style="font-size:12px; border:#CCCCCC thin solid">
								<thead style="color:#FFFFFF; background:#333333">
									<tr align="center">
										<td width="100">Overtime Date</td>
										<td width="126">Overtime Credit</td>
										<td width="130">Rate</td>
										<td width="117">Reason</td>
										<td width="82">Computed</td>
										<td width="82">Manage</td>
									</tr>
								</thead>
								<tbody>
									<?php
									echo $ot_table;
									?>
								</tbody>
							</table>
						</div>
                    </div>
            	</div>
			</div>
			<div id="undertime-tab" class="tab-pane fade">
				<div class="panel-group" style="margin-top:-0px">
					<div class="panel panel-success">
						<div class="panel-heading">
							<span style="float:right; margin-top:-1px; font-size:12px">
								&nbsp;&nbsp;
								<span class="glyphicon glyphicons-alarm"></span>&nbsp;
								<a href="#" id="undertimeFormBtn">Undertime Application</a>
							</span>
							Undertime
						</div>
						<div class="panel-body">
							<!-- Undertime form -->
							<div id="undertimeForm" class="hide">
								<div id="undertimeFormTitle">
									<strong>
									<span class="glyphicon glyphicon-building"></span>
									Add Undertime

									<div style= "float:right; >
   									 <span class="close-btn"><a href="#" id="myUnder">X</a> </span>
									</div>
									</strong>
								</div>
								<form id="undertime_form" name="undertime_form"  action="<?php echo HTTP_PATH."hr/undertime/insert/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<div>
										Date: <input type='text' id="ut_date" class='span1' name="ut_date" style="width:100px" />
									</div>
									<script>
										$('#ut_date').datepicker();
									</script>
									<div>
										Time(hh:mm:ss): <input type='text' class='span1' name="ut_time" id="ut_time" style="width:100px" />
									</div>
									<div>
										Reason: <input type='text' class='span1' name="ut_reason" style="width:100px" />
									</div>
									<div style="margin-top:10px">
										<input type="button" class='btn btn-primary btn-sm' value="Save" onclick="javascript:checkUndertimeForm()">
									</div>
								</form>
							</div>

							<table data-toggle="table" id="hr-paylist" class="table table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" style="font-size:12px; border:#CCCCCC thin solid">
								<thead style="color:#FFFFFF; background:#333333">
									<tr align="center">
										<td width="100">Undertime Date</td>
										<td width="126">Undertime Credit</td>
										<td width="117">Reason</td>
										<td width="82">Computed</td>
										<td width="82">Manage</td>
									</tr>
								</thead>
								<tbody>
									<?php
									echo $ut_table;
									?>
								</tbody>
							</table>
						</div>
                    </div>
            	</div>
			</div>
			<?php endif; ?>
			<?php if($access_elf): ?>
          	<div id="elf-tab" class="tab-pane fade">
            	<div class="panel-group" style="margin-top:-0px">
					<div class="panel panel-warning">
						<div class="panel-heading">
							ELF Contribution
							<span style="float:right; margin-right:10px; margin-top:-20px">Total Contribution: <span id="current_total_elf_contribution"><?php echo $this->Abas->currencyFormat($elfcontri); ?></span>
							&nbsp;
							<a href="#" id="myID">
							<span class="glyphicon glyphicon-list-alt" title="Set Total Contribution"></span>
							</a>

							</span>
						</div>
						<div class="panel-body">
						  <table data-toggle="table" id="hr-paylist" class="table table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" style="font-size:11px; border:#CCCCCC thin solid; width:400px; float:left">
								<thead style="color:#FFFFFF; background:#333333">
									<tr align="center">
										<td width="100">Date</td>
										<td width="126">Contribution</td>
									</tr>
								</thead>
								<tbody>
									<?php echo $elf_table; ?>
								</tbody>
							</table>
						</div>
                    </div>
            	</div>
            </div>
            <?php endif; ?>
			<?php if($access_loan): ?>
            <div id="loans-tab" class="tab-pane fade">
            	<div class="panel-group" style="margin-top:-0px;">
					<div class="panel panel-success">
						<div class="panel-heading">
							<span style="float:right; margin-top:-4px; font-size:12px ">
								&nbsp;&nbsp;
								<span class="glyphicon glyphicon-calculator"></span>&nbsp;
								<a href="#" id="myLoan">Apply New Loan</a>
							</span>
							Loans
						</div>
						<div class="panel-body">
							<!-- Loan application form -->
							<div id="loanForm" class="hide">


								<strong>
									<span class="glyphicon glyphicon-building"></span>
								Loan Application

									<div style= "float:right;">
   									 <span class="close-btn"><a href="#" id="myBack">X</a> </span>

									</div>
								</strong>

								<form id="loan_form" name="loan_form"  action="<?php echo HTTP_PATH."whr/update_loan/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<div>
										<br>
										Loan Type:
										<select class='span1' name='loanType' id='loanType'>
											<option></option>
											<option value="ELF">ELF Loan</option>
											<option value="SSS">SSS Loan</option>
											<option value="PagIbig">Pag-Ibig Loan</option>
											<option value="Cash Advance">Cash Advance</option>
										</select>
									</div>
									<div style="margin-top:5px">
										Principal: <input type='text' name='loanPrincipal' id='loanPrincipal' class='span1' style=width:150px" />
									</div>
									<div style="margin-top:5px">
										Monthly Amortization: <input type='text' name='loanAmortization' id='loanAmortization' class='span1' style="width:100px" />
									</div>
									<div style="margin-top:5px">
										Due Date: <input type='text' id="loan_date" name='loanDate' id='loanDate' class='span1' style="width:150px" />
										<script>
											$('#loan_date').datepicker();
										</script>
									</div>
									<div style="margin-top:5px">
										Remarks: <input type='text' id="loan_date" name='loanRemark' id='loanRemark' class='span1' style="width:150px" />
									</div>
									<div style="margin-top:10px">
										<input type="button" class='btn btn-primary btn-sm' value="Save" onclick="javascript:checkLoanForm()">
									</div>
								</form>
							</div>
							<div id="loanPaymentForm" class="hide">
								<form action="<?php echo HTTP_PATH; ?>hr/pay_loan/id_here" method="POST" id="frmLoanPayment">
									<strong>
									<span class="glyphicon glyphicon-building"></span>
									Loan Payment
									</strong>
									<div>
										<label for="txtLoanPayAmt">Amount Paid</label>
										<input type="text" name="loanPayAmt" id="txtLoanPayAmt" class="span1" />
									</div>
									<div>
										<label for="txtLoanPayDate">Payment Date</label>
										<input type="text" name="loanPayDate" id="txtLoanPayDate" class="span1" value="<?php echo date("Y-m-d"); ?>" />
										<script>$("#txtLoanPayDate").datepicker();</script>
									</div>
									<div>
										<input type="button"  value="Save" onclick="javascript:checkLoanPaymentForm()">
									</div>
								</form>
							</div>
							<table data-toggle="table" id="hr-paylist" class="table table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" style="font-size:12px; border:#CCCCCC thin solid">
								<thead style="color:#FFFFFF; background:#333333">
									<tr align="center">
										<td width="100">Loan Type</td>
										<td width="126">Loan Date</td>
										<td width="130">Loan Due Date</td>
										<td width="117">Monthly Amortization</td>
										<td width="117">Loaned Amount</td>
										<td width="82">Total Payment</td>
										<td width="82">Balance</td>
										<td width="82">-</td>
									</tr>
								</thead>
								<tbody>
									<?php echo $loan_table; ?>
							  </tbody>
						  </table>
						</div>
                	</div>
            	</div>
            </div>
            <?php endif; ?>
			<?php if($access_leave): ?>
            <div id="leaves-tab" class="tab-pane fade">
            	<div class="panel-group" style="margin-top:-0px">
					<div class="panel panel-info">
						<div class="panel-heading">
							<span style="float:right; margin-top:0px; margin-right:10px; color:#000066;">

								<a href="#" id="myLeave">Set Leave Credit</a>
								|
								<a href="#" id="leaveApplicationBtn">Apply For Leave</a>
							</span>
							<p>Leave Credits (<?php echo $leavecredits; ?> days)</p>
							<p>Absences (<?php echo $absences; ?> days)</p>
						</div>
						<div class="panel-body">
							<table data-toggle="table" id="hr-paylist" class="table table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" style="font-size:11px; border:#CCCCCC thin solid; width:600px; float:left">
								<thead style="color:#FFFFFF; background:#333333">
									<tr align="center">
										<td width="100">Leave Type</td>
										<td width="100">Date Start</td>
										<td width="126">Date End</td>
										<td width="126">Num. of Days</td>
										<td width="126">Reason</td>
										<td width="126">Manage</td>
									</tr>
								</thead>
								<tbody>
									<?php echo $leave_table; ?>
								</tbody>
							</table>
							<div id="leaveApplication" class="hide">
								<div id="leaveApplicationTitle" style="margin-bottom:20px">
									<strong>
									<span class="glyphicon glyphicon-building"></span>
									Leave Application

									<div style= "float:right; >
   									 <span class="close-btn"><a href="#" id="myLeft">X</a> </span>

									</div>
									</strong>
								</div>
								<div class="form-group">
									<form action="<?php echo HTTP_PATH."whr/apply_leave/".$e['id']; ?>" id="leave_application" name="leave_application" enctype='multipart/form-data' method="POST">
										<div>
											<label for="leave_type">Leave Type:</label>
											<select class='span1' id="leave_type" name='leave_type'>
												<option></option>
												<option value="Vacation">Vacation Leave</option>
												<option value="Emergency">Emergency Leave</option>
												<option value="Sick">Sick Leave</option>
												<option value="Maternity">Maternity Leave</option>
												<option value="Paternity">Paternity Leave</option>
												<option value="Bereavement">Bereavement Leave</option>
												<option value="Other">Other</option>
											</select>
										</div>
										<div>
											<label for="leave_start">Start Date:</label>
											<input id="leave_start" class="status-change-input form-control input-sm" type="text" name="leave_start" />
										</div>
										<div>
											<label for="leave_end">End Date:</label>
											<input id="leave_end" class="status-change-input form-control input-sm" type="text" name="leave_end" />
										</div>
										<div>
											<label for="leave_limit">Number of days</label>
											<input id="leave_limit" class="status-change-input form-control input-sm" type="text" name="leave_limit" />
										</div>
										<div>
											<label for="status_remarks">Reason:</label>
											<input id="status_remarks" class="status-change-input form-control input-sm" type="text" name="leave_reason" />
										</div>
                                        <br />
										<div align="right">
											<input type="button" id="save_leave_application" class='btn btn-primary btn-sm' value="Save" onclick="javascript:checkLeaveForm()">
										</div>
										<script>
											$('#leave_start').datepicker();
											$('#leave_end').datepicker();
										</script>
									</form>
								</div>
							</div>
						</div>
                	</div>
            	</div>
            </div>
			<?php endif; ?>
		</div>
	</div>
<?php if($access_leave) : ?>
<!-- Leave Update form -->
<div id="leaveTitle" class="hide">Update Leave Credit</div>
<div id="leaveForm" class="hide">
	<form id="leave_form" name="leave_form"  action="<?php echo HTTP_PATH."whr/update_leave/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
		<div>Total: <input type='text' class='span1' name="leave_credits" id="leave_credits" style="width:100px" /></div>
		<div style="margin-top:10px">
			<input type='button' id="save_leave" class='btn-small' value='Save' onclick="javascript:checkLeaveCreditForm()" />
		</div>
	</form>
</div>
<?php endif; ?>
<?php if($access_elf) : ?>
<!-- ELF Update form -->
<div id="myformIdorClassTitle" class="hide">Update Contribution</div>
<div id="myFormIdorClassForm" class="hide">
	<form id="employee_info" name="employee_info"  action="<?php echo HTTP_PATH."whr/update_elf/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
		<div>
			Total: <input type='text' class='span1' name="total_elf_contribution" id="total_elf_contribution" style="width:100px" />
		</div>
		<div style="margin-top:10px">
			<input type='button' class='btn-small' value='Save' onclick="javascript:checkELFForm()" />
		</div>
	</form>
</div>
<?php endif; ?>
<?php if($access_status) : ?>
<!-- Recommendation form -->
<div id="recommendationTitle" class="hide" style="background:#333333; color:#FFFFFF">
	<strong>
	<span class="glyphicon glyphicon-building"></span>
	Recommendation
	</strong>
</div>
<div id="recommendationForm" class="hide">
	<form id="recommendation_form" name="recommendation_form"  action="<?php echo HTTP_PATH."whr/recommend/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
		<div>
			Recommend to:
			<select class='span1' name='recommendation_type'>
				<option></option>
				<option value="Extend Contract">Extend Contract</option>
				<option value="Make Probationary">Make Probationary</option>
				<option value="Make Regular">Make Regular</option>
				<option value="Promote">Promote</option>
				<option value="Retire">Retire</option>
				<option value="Terminate">Terminate</option>
			</select>
		</div>
		<div style="margin-top:5px">
			Remarks: <input type='text' name="recommendation_remarks" class='span1' style="width:150px" />
		</div>
		<div style="margin-top:10px">
			<input type='submit' class='btn-small' value='Save' />
		</div>
	</form>
</div>
<?php endif; ?>


<script>
	$('#myLeave').popover({
		html : true,
		title: function() {
			return $("#leaveTitle").html();
		},
		content: function() {
			return $("#leaveForm").html();
		}
	});
	$('#myID').popover({
		html : true,
		title: function() {
			return $("#myformIdorClassTitle").html();
		},
		content: function() {
			return $("#myFormIdorClassForm").html();
		}
	});
	// $('#myLoan').popover({
		// html : true,
		// title: function() {
			// return $("#loanTitle").html();
		// },
		// content: function() {
			// return $("#loanForm").html();
		// }
	// });
	$('#recommend').popover({
		html : true,
		title: function() {
			return $("#recommendationTitle").html();
		},
		content: function() {
			return $("#recommendationForm").html();
		}
	});
	$( "#btnEmployeeHistory" ).click(function() {
		var x = $("#frmEmployeeHistory").attr("class");
		if(x=="hide") {
			$("#frmEmployeeHistory").removeClass("hide");
		}
		else {
			$("#frmEmployeeHistory").addClass("hide");
		}
	});
	$( "#myEmployee" ).click(function() {
		var x = $("#frmEmployeeHistory").attr("class");
		if(x=="hide") {
			$("#frmEmployeeHistory").removeClass("hide");
		}
		else {
			$("#frmEmployeeHistory").addClass("hide");
		}
	});
	$( "#leaveApplicationBtn" ).click(function() {
		var x = $("#leaveApplication").attr("class");
		if(x=="hide") {
			$("#leaveApplication").removeClass("hide");
		}
		else {
			$("#leaveApplication").addClass("hide");
		}
	});
	$( "#myLeft" ).click(function() {
		var x = $("#leaveApplication").attr("class");
		if(x=="hide") {
			$("#leaveApplication").removeClass("hide");
		}
		else {
			$("#leaveApplication").addClass("hide");
		}
	});
        $( "#myBack" ).click(function() {
		var x = $("#loanForm").attr("class");
		if(x=="hide") {
			$("#loanForm").removeClass("hide");
		}
		else {
			$("#loanForm").addClass("hide");
		}
	});
	$( "#myLoan" ).click(function() {
		var x = $("#loanForm").attr("class");
		if(x=="hide") {
			$("#loanForm").removeClass("hide");
		}
		else {
			$("#loanForm").addClass("hide");
		}

	});
	$( "#overtimeFormBtn" ).click(function() {
		var x = $("#overtimeForm").attr("class");
		if(x=="hide") {
			$("#overtimeForm").removeClass("hide");
		}
		else {
			$("#overtimeForm").addClass("hide");
		}
	});

	$( "#myOver" ).click(function() {
		var x = $("#overtimeForm").attr("class");
		if(x=="hide") {
			$("#overtimeForm").removeClass("hide");
		}
		else {
			$("#overtimeForm").addClass("hide");
		}
	});
	$( "#undertimeFormBtn" ).click(function() {
		var x = $("#undertimeForm").attr("class");
		if(x=="hide") {
			$("#undertimeForm").removeClass("hide");
		}
		else {
			$("#undertimeForm").addClass("hide");
		}
	});

	$( "#myUnder" ).click(function() {
		var x = $("#undertimeForm").attr("class");
		if(x=="hide") {
			$("#undertimeForm").removeClass("hide");
		}
		else {
			$("#undertimeForm").addClass("hide");
		}
	});
	function getPayslip(payslip_id) {
		$(".modal-content").html('<p class="loading-text">Loading Content...</p>');
		$.ajax({
			type: "POST",
			url: "<?php echo HTTP_PATH."payroll_history/payslips/employee/"; ?>"+payslip_id,
			success: function(data){
				$(".modal-content").html(data);
			},
			error: function(){
				toastr['error']("Failed to load page.","ABAS says");
			}
		});
	}
	function loanPayment(loanID) {
		var x = $("#loanPaymentForm").attr("class");
		if(x=="hide") {
			$("#loanPaymentForm").removeClass("hide");
			$("#frmLoanPayment").attr("action", "<?php echo HTTP_PATH; ?>hr/pay_loan/"+loanID);

		}
		else {
			$("#loanPaymentForm").addClass("hide");
		}
	}
	function showPayment(loanID) {
		var x = $("#payment"+loanID).attr("class");
		if(x=="hide") {
			$("#payment"+loanID).removeClass("hide");
		}
		else {
			$("#payment"+loanID).addClass("hide");
		}
	}

	function checkLoanPaymentForm() {
		var msg="";
		var patt1=/^[0-9]+$/i;
		// /*
		var txtLoanPayAmt=document.getElementById("txtLoanPayAmt").value;
		if (txtLoanPayAmt==null || txtLoanPayAmt=="") {
			msg+="Loan Payment Amount is required! <br/>";
		}
		var txtLoanPayDate=document.getElementById("txtLoanPayDate").value;
		if (txtLoanPayDate==null || txtLoanPayDate=="") {
			msg+="Loan Payment Date is required! <br/>";
		}

		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			document.getElementById("frmLoanPayment").submit();
			return true;
		}

	}
	function checkLoanForm() {
		var msg="";
		var patt1=/^[0-9]+$/i;
		// /*
		var loanType=document.getElementById("loanType").selectedIndex;
		if (loanType==null || loanType=="") {
			msg+="Loan Type is required! <br/>";
		}
		var loanPrincipal=document.getElementById("loanPrincipal").value;
		if (loanPrincipal==null || loanPrincipal=="") {
			msg+="Loan Principal is required! <br/>";
		}

		var loanAmortization=document.getElementById("loanAmortization").value;
		if (loanAmortization==null || loanAmortization=="") {
			msg+="Monthly Amortization is required! <br/>";
		}

		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			document.getElementById("loan_form").submit();
			return true;
		}
	}
	function checkUndertimeForm() {
		var msg="";
		var patt1=/^[0-9]+$/i;
		// /*
		var ut_date=document.getElementById("ut_date").value;
		if (ut_date==null || ut_date=="") {
			msg+="Date is required! <br/>";
		}
		var ut_time=document.getElementById("ut_time").value;
		if (ut_time==null || ut_time=="") {
			msg+="Time is required! <br/>";
		}

		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			document.getElementById("undertime_form").submit();
			return true;
		}

	}

	function checkOvertimeForm() {
		var msg="";
		var patt1=/^[0-9]+$/i;
		// /*
		var ot_date=document.getElementById("ot_date").value;
		if (ot_date==null || ot_date=="") {
			msg+="Date is required! <br/>";
		}
		var ot_time=document.getElementById("ot_time").value;
		if (ot_time==null || ot_time=="") {
			msg+="Time is required! <br/>";
		}
		var ot_rate=document.getElementById("ot_rate").selectedIndex;
		if (ot_rate==null || ot_rate=="") {
			msg+="Rate is required! <br/>";
		}


		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			document.getElementById("overtime_form").submit();
			return true;
		}

	}
	function checkLeaveForm() {
		var msg="";
		var patt1=/^[0-9]+$/i;
		// /*

		var leave_type=document.getElementById("leave_type").selectedIndex;
		if (leave_type==null || leave_type=="") {
			msg+="Leave type is required! <br/>";
		}
		var leave_start=document.getElementById("leave_start").value;
		if (leave_start==null || leave_start=="") {
			msg+="Leave Start is required! <br/>";
		}
		var leave_end=document.getElementById("leave_end").value;
		if (leave_end==null || leave_end=="") {
			msg+="Leave End is required! <br/>";
		}
		var leave_limit=document.getElementById("leave_limit").value;
		if (leave_limit==null || leave_limit=="") {
			msg+="Number of days is required! <br/>";
		}


		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			document.getElementById("leave_application").submit();
			return true;
		}

	}
	function checkLeaveCreditForm() {
		var msg="";
		var patt1=/^[0-9]+$/i;

		var leave_start=document.getElementById("leave_credits").value;
		if (leave_start==null || leave_start=="") {
			msg+="Leave Credits is empty! <br/>";
		}

		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			document.getElementById("leave_form").submit();
			return true;
		}
	}
	function checkELFForm() {
		var msg="";
		var patt1=/^[0-9]+$/i;

		var total_elf_contribution=document.getElementById("total_elf_contribution").value;
		if (total_elf_contribution==null || total_elf_contribution=="") {
			msg+="ELF Contribution is empty! <br/>";
		}

		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			document.getElementById("employee_info").submit();
			return true;
		}
	}
</script>
*/ ?>