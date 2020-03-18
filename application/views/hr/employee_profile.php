<?php
	$employeeid=$lastname=$firstname=$middlename=$birthdate=$gender=$mobile=$email=$civilstat=$address=$city=$zipcode=$emergencycontactnum=$emergencycontactperson=$profilepic=$datehired=$dateseparated=$position=$section=$sub_section=$salarygrade=$department_name=$experience=$taxcode=$tin=$sssnum=$phnum=$pagibig_num=$bankacct=$elfrate=$leavecredits=$company_name=$vessel=$absences=$dependentstring=$employment_status="";
	$access_leave	=	$this->Abas->checkPermissions("human_resources|leave",false);
	$access_loan	=	$this->Abas->checkPermissions("human_resources|loan",false);
	$access_elf		=	$this->Abas->checkPermissions("human_resources|elf",false);
	$view_salary	=	$this->Abas->checkPermissions("human_resources|salary_viewing",false);
	$edit_salary	=	$this->Abas->checkPermissions("human_resources|salary_editing",false);
	$access_status	=	$this->Abas->checkPermissions("human_resources|edit",false);
	$access_ot		=	$this->Abas->checkPermissions("human_resources|overtime",false);
	$access_ut		=	$access_ot;

	$display_img	=	LINK.'assets/images/icons/1689490804.png';
	if(isset($employee_record)) {
		$e			=	$employee_record;
		// $this->Mmm->debug($e);
		if($e['profile_pic']!="") {
			$display_img=	LINK.'assets/images/employeepic/'.$e['profile_pic'];
		}
		$eid					=	$e['id'];
		$employeeid				=	$e['employee_id'];
		$lastname				=	$e['last_name'];
		$firstname				=	$e['first_name'];
		$middlename				=	$e['middle_name'];
		$birthdate				=	$e['birth_date']!="1970-01-01 00:00:00" ? date("j F Y", strtotime($e['birth_date'])) : "";

		if(!empty($dependents)) {
			$dependentstring	=	"";
			foreach($dependents as $d) {
				$dependentstring	.=	"<li>".ucwords(strtolower($d['last_name'].", ".$d['first_name']." ".$d['middle_name']))." (".$d['dependent_relationship'].") | "." Birthdate: ".date("j F Y", strtotime($d['birth_date']))."</li>";
			}
		}

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
		$datehired				=	$e['date_hired']!="1970-01-01 00:00:00" ? date("j F Y", strtotime($e['date_hired'])) : "";


		$out_of_company = $this->db->query("SELECT * FROM hr_employment_history WHERE employee_id=".$e['id']." AND (to_val='Resigned' OR to_val='Retired' OR to_val='Terminated' OR to_val='Separated') ORDER BY effectivity_date DESC LIMIT 1");
		if($out_of_company){
			if($row=$out_of_company->row()){
				$dateseparated = date('j F Y',strtotime($row->effectivity_date));
			}
		}
		$position				=	$e['position'];
		$salarygrade			=	$e['salary_grade'];
		$salaryrate				=	$e['salary_rate'];
		$group 					=	$e['department_group'];
		$division 				= 	($e['division_name']=='' ? "--":$e['division_name']);
		$department				=	$e['department'];
		$section 				= 	($e['section_name']=='' ? "--":$e['section_name']);
		$sub_section 			= 	($e['sub_section_name']=='' ? "--":$e['sub_section_name']);
		$experience				=	$e['experience'];
		$taxcode				=	$e['tax_code'];
		$tin					=	$e['tin_num'];
		$sssnum					=	$e['sss_num'];
		$phnum					=	$e['ph_num'];
		$pagibig_num			=	$e['pagibig_num'];
		$bankacct				=	$e['bank_account_num'];
		$remarks				=	$e['experience'];
		$elfrate				=	$e['elf_rate'];
		if($elfrate!='' && is_numeric($elfrate)){	$elfrate=number_format($elfrate,2); }else{ $elfrate = 0;}

		$absences				=	$e['absences'];
		$leavecredits			=	$e['leave_credits'];
		$elfcontri				=	$e['total_elf_contribution'];
		$employee_id			=	$e['employee_id'];

		$employment_status		=   $this->Hr_model->getLatestEmploymentStatus($e['id']);
		if($employment_status!=null){
			$employment_status	=	$employment_status->to_val;	
		}else{
			$employment_status	="";
		}	

		//if($e['employee_status']=='Retired' || $e['employee_status']=='Resigned' ||  $e['employee_status']=='Resigned' ||  $e['employee_status']=='Terminated' ||  $e['employee_status']=='AWOL' ||  $e['employee_status']=='On-leave'){
		if($employment_status=='Retired' || $employment_status=='Resigned' ||  $employment_status=='Resigned' ||  $employment_status=='Terminated' ||  $employment_status=='Separated' || $employment_status==""){
			$employee_status		=	"Inactive";
		}else{
			$employee_status		=	"Active";
		}		

		$company_name			=	$e['company_name'];
		$department_name		=	$e['department_name'];
		$position_name			=	$e['position_name'];
		$vessel_id				=	$e['vessel_id'];
		if($e['modified_on'] == "" || $e['modified_on'] == "1970-01-01 00:00:00" || $e['modified_on'] == "0000-00-00 00:00:00" || $e['modified_on'] == null) {

		}
		else {
			$last_edited		=	date("j F Y",strtotime($e['modified_on']))." at ".date("h:i a",strtotime($e['modified_on']));
		}
		// $this->Mmm->debug($e);
	}

	$total_table="";
	$payroll_table	=	"<tr class='text-center'><td align='center' colspan='99'>No Payroll record found!</td></tr>";
	$elf_table	=	"<tr class='text-center'><td align='center' colspan='99'>No ELF record found!</td></tr>";

	$vesselq	=	$this->Abas->getVessel($vessel_id);
	if(!empty($vesselq)) {
		$vessel	=	$vesselq->name;
	}
	else {
		$vessel	=	"-";
	}

	$vesseloptions	=	"";
	if(!empty($vessels)) {
		foreach($vessels as $s) {
			if($s->id != 0){
				$company = $this->Abas->getCompany($s->company);
				$vesseloptions	.=	"<option ".($vessel_id==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->name." (".$company->name.")</option>";	
			}
		}
	}
	if(!empty($payroll_info)) {
		$total_gross_salary =0;
		$grand_total_deductions=0;
		$total_loan_payment=0;
		$total_net_pay=0;
		$total_philhealth = 0;
		$total_pagibig=0;
		$total_sss=0;
		$total_tax=0;
		$total_table = "";
		$payroll_table	=	"";
		$elf_table		=	"";
		$total_row="";
		foreach($payroll_info AS $pi) {
			// $this->Mmm->debug($pi);
			$payroll_row	=	"";
			$elf_row		=	"";
			$summary		=	$this->db->query("SELECT * FROM hr_payroll WHERE id=".$pi->payroll_id);


			if($summary!=false) {
				if($summary->row()) {
					$philhealth = $pi->phil_health_contri;
					$pagibig = $pi->pagibig_contri;
					$sss = $pi->sss_contri_ee;
					$tax=$pi->tax;
					$total="<b>Total</b>";
					$total_ph ="PhilHealth :";
					$total_pibg = "Pag-ibig :";
					$total_ssscontri="SSS Contribution :";
					$total_tx="With-holding Tax :";
					$total_row ="";
					$payroll_row		=	"";
					$elf_row			=	"";
					$summary			=	$summary->row();
					$period				=	$summary->payroll_coverage." ".date("F Y", strtotime($summary->payroll_date."-01"));
					// $this->Mmm->debug(($pi->salary+$pi->regular_overtime_amount+$pi->holiday_overtime_amount+$pi->allowance)+$pi->bonus-($pi->absences_amount));
					$holiday_overtime = $pi->specialholiday_overtime_amount+$pi->specialholiday_restday_overtime_amount+$pi->legalholiday_overtime_amount+$pi->legalholiday_restday_overtime_amount;
					if($holiday_overtime==0){
						$holiday_overtime = $pi->holiday_overtime_amount;
					}
					$gross_pay			=	($pi->salary+$pi->regular_overtime_amount+$pi->restday_overtime_amount+$holiday_overtime+$pi->allowance)+$pi->bonus+$pi->others+$pi->night_differential_amount;

					$total_deductions	=	$pi->tax+$pi->sss_contri_ee+$pi->phil_health_contri+$pi->pagibig_contri+$pi->undertime_amount+$pi->absences_amount;
					$loan_payment		=	$pi->sss_loan + $pi->pagibig_loan + $pi->cash_advance; //todo
					$net_pay			=	$gross_pay-$total_deductions-$loan_payment-$pi->elf_contri;

					$total_gross_salary += $gross_pay;
					$grand_total_deductions += $total_deductions;
					$total_loan_payment += $loan_payment;
					$total_net_pay += $net_pay;

					$total_philhealth+= $philhealth;
					$total_pagibig+= $pagibig;
					$total_sss+= $sss;
					$total_tax+=$tax;

					// $payroll_row	.=	"<tr class='viewPayroll' style='cursor:pointer; background:#FFF; text-align:right;' data-target='#payslip' data-toggle='modal' href='".HTTP_PATH."payroll_history/payslips/employee/".$pi->id."'>";
					$payroll_row	.=	"<tr class='text-center cursor-pointer' >";
					$payroll_row	.=	"<td class='text-center;'>".$period."</td>";
					$payroll_row	.=	"<td>".$this->Abas->currencyFormat($gross_pay)."</td>";
					$payroll_row	.=	"<td>".$this->Abas->currencyFormat($total_deductions)."</td>";
					$payroll_row	.=	"<td>".$this->Abas->currencyFormat($loan_payment)."</td>";
					$payroll_row	.=	"<td>".$this->Abas->currencyFormat($net_pay)."</td>";
					//$payroll_row	.=	"<td><button onClick='getPayslip(".$pi->id.")' class='btn btn-info btn-xs btn-block'>View Payslip</button></td>";

					$payroll_row	.=	"<td><a href='".HTTP_PATH."payroll_history/payslip_printable/".$pi->id."/employee' class='btn btn-info btn-xs btn-block' target='_blank'>View Payslip</td>";
					//$payroll_row	.=	"<td>".$this->Abas->currencyFormat($sum)."</td>";
					$payroll_row	.=	"</tr>";



					$elf_row		.=	"<tr class='text-center'>";
					$elf_row		.=	"<td>".$period."</td>";
					$elf_row		.=	"<td>".$this->Abas->currencyFormat($pi->elf_contri)."</td>";
					$elf_row		.=	"</tr>";

					$total_row	.=	"<tr class= 'text-center'>";
					$total_row .= "<td class= 'text-center'>".$total."</td>";
					$total_row .= "<td><b>".$this->Abas->currencyFormat($total_gross_salary)."</b></td>";
					$total_row .= "<td><b>".$this->Abas->currencyFormat($grand_total_deductions)."</b></td>";
					$total_row .= "<td><b>".$this->Abas->currencyFormat($total_loan_payment)."</b></td>";
					$total_row .= "<td><b>".$this->Abas->currencyFormat($total_net_pay)."</b></td>";
					$total_row .="</tr>";

					$total_row .="<tr class= 'text-center'>";
					$total_row .= "<td class= 'text-center'><b>".$total_ph."</b></td>";
					$total_row .="<td>".$this->Abas->currencyFormat($total_philhealth)."</td>";
					$total_row .="</tr>";
					$total_row .="<tr class= 'text-center'>";
					$total_row .= "<td class= 'text-center'><b>".$total_pibg."</b></td>";
					$total_row .="<td>".$this->Abas->currencyFormat($total_pagibig)."</td>";
					$total_row .="</tr>";
					$total_row .="<tr class= 'text-center'>";
					$total_row .= "<td class= 'text-center'><b>".$total_ssscontri."</b></td>";
					$total_row .="<td>".$this->Abas->currencyFormat($total_sss)."</td>";
					$total_row .="</tr>";
					$total_row .="<tr class= 'text-center'>";
					$total_row .= "<td class= 'text-center'><b>".$total_tx."</b></td>";
					$total_row .="<td>".$this->Abas->currencyFormat($total_tax)."</td>";
					$total_row .="</tr>";
				}
			}

			$payroll_table	.=	$payroll_row;
			$elf_table		.=	$elf_row;
		}

		$total_table	.= $total_row;
	}

	$employee_history_table	=	"<tr class='text-center'><td align='center' colspan='99'>No Work History found!</td></tr>";
	if(!empty($employee_history)) {
		$employee_history_table	=	"";
		foreach($employee_history as $eh) {
			// $this->Mmm->debug($eh);
			$added_by				=	$this->Abas->getUser($eh['added_by']);
			$added_on				=	date("j F Y H:i:s", strtotime($eh['added_on']));
			$positionName			=	$position_name;
			$effectivity_date		=	($eh['effectivity_date']=="1970-01-01 00:00:00")?"":date("j F Y", strtotime($eh['effectivity_date']));
			$value_changed			=	$eh['value_changed'];
			$from_val				=	$eh['from_val'];
			$to_val					=	$eh['to_val'];
			$display				=	true;

			if($value_changed=="Salary Grade") {
				if($view_salary==false) {
					$display		=	false;
				}
				if($from_val!="" && $from_val!="-") {
					$from_salgrade			=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$from_val);
					if($from_salgrade!=false) {
						$from_salgrade		=	$from_salgrade->row();
						$from_val			=	$from_salgrade->grade;
					}
				}

				if($to_val!="" && $from_val!="-") {
					$to_salgrade			=	$this->db->query("SELECT * FROM salary_grades WHERE id=".$to_val);
					if($to_salgrade != false) {
						$to_salgrade		=	$to_salgrade->row();
						$to_val				=	$to_salgrade->grade;
					}
				}
			}
			if($value_changed=="Position") {
				if($from_val!="-" && $from_val!="") {
					$from_position			=	$this->db->query("SELECT * FROM positions WHERE id=".$from_val);
					if($from_position!=false) {
						$from_position		=	$from_position->row();
						$from_val			=	$from_position->name;
					}
				}

				if($to_val!="-" && $to_val!="") {
					$to_position			=	$this->db->query("SELECT * FROM positions WHERE id=".$to_val);
					if($to_position!=false) {
						if($to_position->row()) {
							$to_position			=	$to_position->row();
							$to_val					=	$to_position->name;
						}
					}
				}
			}
			if($value_changed=="Department") {
				if($from_val!="-" && $from_val!="") {
					$from_dept		=	$this->db->query("SELECT * FROM departments WHERE id=".$from_val);
					if($from_dept!=false) {
						$from_dept		=	$from_dept->row();
						if($from_dept){
							$from_val		=	$from_dept->name;
						}else{
							$from_val		=	"-";
						}
					}
				}

				if($to_val!="-" && $to_val!="") {
					$to_dept			=	$this->db->query("SELECT * FROM departments WHERE id=".$to_val);
					if($to_dept!=false) {
						if($to_dept->row()) {
							$to_dept			=	$to_dept->row();
							$to_val				=	$to_dept->name;
						}
					}
				}
			}
			if($value_changed=="Vessel" || $value_changed=="Assigned To") {
				if($from_val!="" && $from_val!="-") {
					$from_vessel			=	$this->Abas->getVessel($from_val);
					$from_val			=	isset($from_vessel->name) ? $from_vessel->name : $from_val;
				}

				if($to_val!="" && $to_val!="-") {
					$to_vessel			=	$this->Abas->getVessel($to_val);
					$to_val			=	isset($to_vessel->name) ? $to_vessel->name : $to_val;
				}
			}

			if($value_changed=="Company" && is_numeric($from_val)) {
				if($from_val!="" && $from_val!="-") {
					$from_position			=	$this->db->query("SELECT * FROM companies WHERE id=".$from_val);
					if($from_position!=false) {
						$from_position		=	$from_position->row();
						$from_val			=	isset($from_position->name) ? $from_position->name : $from_val;
					}
				}

				if($to_val!="" && $to_val!="-") {
					$to_position			=	$this->db->query("SELECT * FROM companies WHERE id=".$to_val);
					if($to_position!=false) {
						$to_position	=	$to_position->row();
						$to_val			=	isset($to_position->name) ? $to_position->name : $to_val;
					}
				}
			}
			if($value_changed=="Employment Status") {
				if($to_val=='Preventive Suspension' || $to_val=='Suspended' || $to_val=='On-leave'){
					$from_date = date('j F Y',strtotime($eh['from_date']));
					$to_date = date('j F Y',strtotime($eh['to_date']));
					$to_val = $to_val."<br> (From ".$from_date. " to ".$to_date.")";
				}
			}



			if($display==true) {
				$manage	=	"<a class='btn btn-xs btn-block btn-danger' href=".HTTP_PATH."hr/ehistory/delete/".$e['id']."/".$eh['id'].">Delete</a>";
				$employee_history_table	.=	"<tr class='text-center'>";
				$employee_history_table	.=	"<td>".$effectivity_date."</td>";
				$employee_history_table	.=	"<td>".$value_changed."</td>";
				$employee_history_table	.=	"<td>".$from_val."</td>";
				$employee_history_table	.=	"<td>".$to_val."</td>";
				$employee_history_table	.=	"<td>".(empty($added_by['username'])?"-":$added_by['username'])."</td>";
				// $employee_history_table	.=	"<td>".$added_on."</td>";
				$employee_history_table	.=	"<td>".$manage."</td>";
				$employee_history_table	.=	"</tr>";
			}
		}
	}
	$ot_table	=	"<tr class='text-center'><td align='center' colspan='99'>No Overtime record found!</td></tr>";
	if(!empty($overtimes)) {
		$ot_table	=	"";
		foreach($overtimes as $ot) {
			$ot_date			=	date("j F Y",strtotime($ot['ot_date']));
			$ot_time			=	$ot['ot_time'];
			// $ot_time			=	date("h:i:s",strtotime($ot['ot_time']));
			$rate				=	$ot['rate'];
			if($ot['type']==null){
				if($rate==25){
					$type = "Regular Day";
				}elseif($rate==30){
					$type = "Rest Day/Special Holiday";
				}elseif($rate==200){
					$type = "Legal Holiday";
				}
			}else{
				$type			=	$ot['type'];	
			}
			$reason				=	$ot['reason'];
			$approved			=	$ot['approved'];
			$computed			=	$ot['computed'];
			$color				=	"color:#FFFFFF;";
			$bgcolor			=	"background-color:#00AA00;";
			// $link				=	"<a class='manage-ot-link' href='".HTTP_PATH."hr/overtime/approve/".$e['id']."/".$ot['id']."'><button type='button' class='btn btn-primary btn-sm' style='cursor:pointer;'>Approve</button></a>";
			$link				=	"<a class='manage-ot-link' href='".HTTP_PATH."hr/overtime/cancel/".$e['id']."/".$ot['id']."'><button type='button' class='btn btn-danger btn-block btn-xs' style='cursor:pointer;'>Cancel</button></a>";
			$approved_indicator	=	"&#10060;";
			if($computed<>0) {
				$approved_indicator	=	"&#10004;";
			}
			if($approved<>0) {
				$bgcolor			=	"";
				$color				=	"";
				// $link				=	($computed==0) ? "<a class='manage-ot-link' href='".HTTP_PATH."hr/overtime/revoke/".$e['id']."/".$ot['id']."'><button type='button' class='btn btn-primary btn-sm' style='cursor:pointer;'>Revoke Approval</button></a>" : "";
			}

			$ot_table	.=	"<tr class='text-center' style='".$color." ".$bgcolor."'>";
			$ot_table	.=	"<td>".$ot_date."</td>";
			$ot_table	.=	"<td>".$ot_time."</td>";
			$ot_table	.=	"<td>".$rate."%</td>";
			$ot_table	.=	"<td>".$type."</td>";
			$ot_table	.=	"<td>".$reason."</td>";
			$ot_table	.=	"<td>".$approved_indicator."</td>";
			$ot_table	.=	($computed==false)?"<td>".$link."</td>":"";
			$ot_table	.=	"</tr>";
		}
	}
	$nd_table	=	"<tr class='text-center'><td align='center' colspan='99'>No Night Differential record found!</td></tr>";
	if(!empty($night_diffs)) {
		$nd_table	=	"";
		foreach($night_diffs as $nd) {
			$nd_date			=	date("j F Y",strtotime($nd['night_diff_date']));
			$nd_time			=	$nd['night_diff_hours'];

			$reason				=	$nd['reason'];
			$added_by			=	$nd['added_by'];
			$computed			=	$nd['is_computed'];
			$color				=	"color:#FFFFFF;";
			$bgcolor			=	"background-color:#00AA00;";
			
			$link				=	"<a class='manage-nd-link' href='".HTTP_PATH."hr/night_differential/cancel/".$e['id']."/".$nd['id']."'><button type='button' class='btn btn-danger btn-block btn-xs' style='cursor:pointer;'>Cancel</button></a>";
			$approved_indicator	=	"&#10060;";
			if($computed<>0) {
				$approved_indicator	=	"&#10004;";
			}
			if($added_by<>0) {
				$bgcolor			=	"";
				$color				=	"";
			}

			$nd_table	.=	"<tr class='text-center' style='".$color." ".$bgcolor."'>";
			$nd_table	.=	"<td>".$nd_date."</td>";
			$nd_table	.=	"<td>".$nd_time."</td>";
			$nd_table	.=	"<td>".$reason."</td>";
			$nd_table	.=	"<td>".$approved_indicator."</td>";
			$nd_table	.=	($computed==false)?"<td>".$link."</td>":"";
			$nd_table	.=	"</tr>";
		}
	}
	$bonus_table	=	"<tr class='text-center'><td align='center' colspan='99'>No Bonus record found!</td></tr>";
	if(!empty($bonuses)) {
		$bonus_table	=	"";
		foreach($bonuses as $bonus) {
			$bonus_date			=	date("j F Y",strtotime($bonus['release_date']));
			$bonus_amount		=	$bonus['amount'];
			$bonus_type			=	$bonus['type'];
			$bonus_remarks		=	$bonus['remarks'];
			$added_by			=	$bonus['added_by'];
			$added_on			=	$bonus['added_on'];
			$approved_by		=	$bonus['approved_by'];

			$color				=	"color:#FFFFFF;";
			$bgcolor			=	"background-color:#00AA00;";
			
			$link	=	"<a class='manage-bonus-link' href='".HTTP_PATH."hr/bonus/cancel/".$e['id']."/".$bonus['id']."'><button type='button' class='btn btn-danger btn-block btn-xs' style='cursor:pointer;'>Cancel</button></a>";

			$approved_indicator	=	"&#10060;";
			if($approved_by<>0) {
				$approved_indicator	=	"&#10004;";
			}
			if($added_by<>0) {
				$bgcolor			=	"";
				$color				=	"";
			}

			$bonus_table	.=	"<tr class='text-center'>";
				$bonus_table	.=	"<td>".$bonus_date."</td>";
				$bonus_table	.=	"<td>".number_format($bonus_amount,2,".",",")."</td>";
				$bonus_table	.=	"<td>".$bonus_type."</td>";
				$bonus_table	.=	"<td>".$bonus_remarks."</td>";
				$bonus_table	.=	"<td>".$approved_indicator."</td>";
				$bonus_table	.=	($approved_by==0)?"<td>".$link."</td>":"";
			$bonus_table	.=	"</tr>";

		}
	}
	$ut_table	=	"<tr class='text-center'><td align='center' colspan='99'>No Undertime record found!</td></tr>";
	if(!empty($undertimes)) {
		$ut_table	=	"";
		foreach($undertimes as $ut) {
			$link				=	"<a class='manage-ot-link' href='".HTTP_PATH."hr/undertime/cancel/".$e['id']."/".$ut['id']."'><button type='button' class='btn btn-danger btn-block btn-xs' style='cursor:pointer;'>Cancel</button></a>";
			$ut_date			=	date("j F Y",strtotime($ut['ut_date']));
			$ut_time			=	$ut['ut_time'];
			$reason				=	$ut['reason'];
			$approved			=	$ut['approved'];
			$computed			=	$ut['computed'];
			$color				=	"color:#FFFFFF;";
			$bgcolor			=	"background-color:#00AA00;";
			$approved_indicator	=	"&#10060;";
			if($computed<>0) {
				$approved_indicator	=	"&#10004;";
			}
			if($approved<>0) {
				$bgcolor			=	"";
				$color				=	"";
			}

			$ut_table	.=	"<tr class='text-center' style='".$color." ".$bgcolor."'>";
			$ut_table	.=	"<td>".$ut_date."</td>";
			$ut_table	.=	"<td>".$ut_time."</td>";
			$ut_table	.=	"<td>".$reason."</td>";
			$ut_table	.=	"<td>".$approved_indicator."</td>";
			$ut_table	.=	($computed==false)?"<td>".$link."</td>":"";
			$ut_table	.=	"</tr>";
		}
	}
	
	if(!empty($loans)) {
		$loan_table	=	"";
		foreach($loans as $l) {
			$loanRemark			=	$l['remark'];
			$loanType			=	$l['loan_type'];
			$loanDate			=	date("j F Y",strtotime($l['date_loan']));
			$loanDueDate		=	($l['due_date_loan']=="1970-01-01 00:00:00" || $l['due_date_loan']=="") ? "":date("j F Y",strtotime($l['due_date_loan']));
			$principal			=	$l['amount_loan'];
			$monthly			=	$l['monthly_amortization'];

			// payments
			$payments	=	$this->db->query("SELECT * FROM hr_loan_payments WHERE loan_id=".$l['id']);
			$total_paid	=	0;
			$loan_payment_table	=	"";
			if($payments!=false) {
				if($payments->row()) {
					$payments	=	$payments->result();
					foreach($payments as $ctr=>$p) {
						$loan_payment_table	.=	"<tr>";
						$loan_payment_table	.=	"<td>".($ctr+1)."</td>";
						$loan_payment_table	.=	"<td>".$p->id."</td>";
						$loan_payment_table	.=	"<td>".date("j F Y",strtotime($p->date_payment))."</td>";
						$loan_payment_table	.=	"<td>".$p->amount."</td>";
						$loan_payment_table	.=	(ENVIRONMENT=="development")?"<th>Payroll</th><td>".$p->payroll_id."</td>":"";
						$loan_payment_table	.=	"</tr>";
						$total_paid	=	$total_paid + $p->amount;
					}
				}
			}

			$loan_table	.=	"<tr class='text-center'>";
			$loan_table	.=	"<td>".$loanType."</td>";
			$loan_table	.=	"<td>".$loanDate."</td>";
			$loan_table	.=	"<td>".$loanDueDate."</td>";
			$loan_table	.=	"<td>".$loanRemark."</td>";
			$loan_table	.=	"<td>".$this->Abas->currencyFormat($monthly)."</td>";
			$loan_table	.=	"<td>".$this->Abas->currencyFormat($principal)."</td>";
			$loan_table	.=	"<td>".$this->Abas->currencyFormat($total_paid)."</td>"; //total payment
			$loan_table	.=	"<td>".$this->Abas->currencyFormat($principal - $total_paid)."</td>"; //remaining balance
			$loan_table	.=	"<td><button class='pull-right btn btn-info btn-xs btn-block' id='loan".$l['id']."' onclick='javascript:showPayment(".$l['id'].")'>View Payments</button></td>"; //payment button
			$loan_table	.=	"</tr>";

			$loan_table	.=	"<tr id='payment".$l['id']."' class='hide'>";
			$loan_table	.=	"<td colspan='99'>";
			$loan_table	.=	"<table class='table table-bordered table-striped table-hover'>";
			$loan_table	.=	"<tr>";
			$loan_table	.=	"<td colspan='99'>".$loanType." Loan Payments<button class='pull-right btn btn-success btn-xs' id='loan".$l['id']."' onclick='javascript:loanPayment(".$l['id'].")'>Add Loan Payment</button></td>";
			$loan_table	.=	"</tr>";
			$loan_table	.=	"<tr>";
			$loan_table	.=	"<td>#</td>";
			$loan_table	.=	"<td>Payment ID</td>";
			$loan_table	.=	"<td>Payment Date</td>";
			$loan_table	.=	"<td>Amount Paid</td>";
			$loan_table	.=	"</tr>";
			$loan_table	.=	$loan_payment_table;
			$loan_table	.=	"</table>";
			$loan_table	.=	"</td>";
			$loan_table	.=	"</tr>";

		}
	}else{
		$loan_table	=	"<tr class='text-center'><td align='center' colspan='99'>No Loan record found!</td></tr>";
	}

	$leave_table	=	"<tr class='text-center'><td colspan='99'>No Leave record found!</td></tr>";
	$num_leaves = 0;
	$num_absences = 0;
	if(!empty($leaves)) {
		$leave_table	=	"";
		$year_now = date('Y');
		foreach($leaves as $l) {
			$leaveType			=	$l['leave_type'];
			$leaveDate			=	date("j F Y",strtotime($l['date_from']));
			$leaveDateYear		=	date("Y",strtotime($l['date_from']));
			$leaveEndDate		=	date("j F Y",strtotime($l['date_to']));
			$no_of_days			=	$l['no_of_days'];
			$reason				=	$l['reason'];

			$link			=	"<a class='btn btn-danger btn-block btn-xs' href='".HTTP_PATH."hr/apply_leave/".$e['id']."/".$l['id']."'>Cancel</a>";
			$color			=	"background-color:". (($l['calculate']==0)? "AA9999" : "99AA99");
			$leave_table	.=	"<tr class='text-center' style='".$color."'>";
			$leave_table	.=	"<td>".$leaveType."</td>";
			$leave_table	.=	"<td>".$leaveDate."</td>";
			$leave_table	.=	"<td>".$leaveEndDate."</td>";
			$leave_table	.=	"<td>".$no_of_days."</td>";
			$leave_table	.=	"<td>".$reason."</td>";
			// $leave_table	.=	"<td>".(($l['calculate']==0)? "No" : "Yes")."</td>";
			$leave_table	.=	"<td>".$link."</td>";
			$leave_table	.=	"</tr>";
			if($leaveType!="Absence"  && $leaveDateYear==$year_now){
				$num_leaves = $num_leaves + $no_of_days;	
			}
			if($leaveType=="Absence" && $leaveDateYear==$year_now){
				$num_absences = $num_absences + 1;
			}
			
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

	$crew_movements = $this->Abas->getItems('hr_crew_movements',array('employee_id'=>$eid));
	foreach ($crew_movements as $ctr => $row) {
		$vessel_from = $this->Abas->getItemById('vessels',array('id'=>$row->vessel_from));
		$vessel_to = $this->Abas->getItemById('vessels',array('id'=>$row->vessel_to));
		$array[$ctr] = array(
			'id' => $row->id,
			'added_on' => $row->added_on,
			'vessel_from' => $vessel_from->name,
			'vessel_to' => $vessel_to->name,
			'added_by' => $row->added_by,
			'embarkation_start' => $row->embarkation_start,
			'embarkation_end' => $row->embarkation_end,
			'status' => ($row->stat == 1 ? 'Active' : 'Inactive'),
			'transfer_date' => $row->transfer_date
		);
		$crew_movements_array = $array;
	}

	$vessel_from = $this->Abas->lastItemByCol('hr_crew_movements',array('employee_id'=>$eid));
	if(isset($vessel_from)){
		$temp_assign = $this->Abas->getVessel($vessel_from->vessel_to)->name;	
	}else{
		$temp_assign = 'N/A';	
	}
?>

<div class="x-panel">
<h2>Employee Profile</h2>
	<?php echo '<a href="'.HTTP_PATH.'hr/employees" class="btn btn-dark force-pageload">Back</a>';?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<?php echo '<h3 class="panel-title">'.$eid.') '.$firstname." ".$lastname." y ".$middlename.'</h3>';?>
	</div>

	<br>
  	<div class="panel-body">
    	<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="##pinfo-tab">Personal & Work Info</a></li>
			<li><a data-toggle="tab" href="##ehist-tab">Work History</a></li>
			<?php if($view_salary) { ?><li><a data-toggle="tab" href="##phist-tab">Payroll</a></li><?php } ?>
			<?php if($view_salary) { ?><li><a data-toggle="tab" href="##bonus-tab">Bonus</a></li><?php } ?>
			<?php if($access_ot) { ?><li><a data-toggle="tab" href="##overtime-tab">Overtime</a></li><?php } ?>
			<?php if($access_ot) { ?><li><a data-toggle="tab" href="##nightdiff-tab">Night Differential</a></li><?php } ?>
			<?php if($access_ut) { ?><li><a data-toggle="tab" href="##undertime-tab">Undertime</a></li><?php } ?>
			<?php if($access_elf) { ?><!--<li><a data-toggle="tab" href="##elf-tab">ELF</a></li>--><?php } ?>
			<?php if($access_loan) { ?><li><a data-toggle="tab" href="##loans-tab">Loans</a></li><?php } ?>
			<?php if($access_leave) { ?><li><a data-toggle="tab" href="##leaves-tab">Leaves</a></li><?php } ?>
			<li><a data-toggle="tab" href="##accountability-tab">Fixed Assets</a></li>
			<li><a data-toggle="tab" href="##crew-movement-tab">Crew Movement</a></li>
		</ul>
        <div class="tab-content">
			<div id="pinfo-tab" class="tab-pane fade in active">
					<div class="panel-body">
						<?php
							echo '<span><a href="'.HTTP_PATH.'hr/employee_profile/edit/'.$eid.'" class="btn btn-warning btn-m exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit Info</a></span>';
							if(isset($last_edited)) {
								echo "<span class='pull-right'><small>Last edited on ".$last_edited."  &nbsp</small></span>";
							}
						?>
					</div>
						<div class="panel-body">
							<div class="col-sm-12 col-md-3">
								<img src="<?php echo $display_img; ?>" class="img-responsive img-thumbnail center-block"/>
							</div>
							<div class="col-sm-12 col-md-9 table-responsive">
								<table class="table table-bordered table-striped"  style="width:120%">
									<tbody>
										<tr>
											<td colspan="2"><b>Personal Information</b></td>
										</tr>
										<tr>
											<td align="left" class="col1">Employee ID:</td>
											<td align="left" class="col2"> <?php echo $employeeid!="" ? $employeeid :"";?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Firstname:</td>
											<td align="left" class="col2"><?php echo ucwords(strtolower($firstname)); ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Middlename:</td>
											<td align="left" class="col2"><?php echo ucwords(strtolower($middlename)); ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Lastname:</td>
											<td align="left" class="col2"><?php echo ucwords(strtolower($lastname)); ?></td>
										</tr>
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
											<td align="left" class="col2"><?php echo ucwords(strtolower($civilstat)); ?></td>
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
											<td align="left" class="col1">Address:</td>
											<td align="left" class="col2"><?php  echo  ucwords(strtolower($address)); ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Province:</td>
											<td align="left" class="col2"><?php  echo  ucwords(strtolower($city)); ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Emergency Contact Person:</td>
											<td align="left" class="col2"><?php echo ucwords(strtolower($emergencycontactperson)); ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Emergency Contact #:</td>
											<td align="left" class="col2"><?php echo $emergencycontactnum; ?></td>
										</tr>
										<tr>
											<td align="left" class="col1">Dependents:</td>
											<td align="left" class="col2">
												<ul>
													<?php echo $dependentstring; ?>
												</ul>
											</td>
										</tr>
									</tbody>
								</table>

								<div class="table-responsive">
								<table class="table table-bordered table-striped" >
									<tbody>
										<tr>
											<td colspan="4"><b>Work Information</b></td>
										</tr>
										<tr>
											<td align="left">Employment Status:</td>
											<td align="left"><?php echo $employment_status; ?></td>
											<td align="left">Employee Status:</td>
											<td align="left"><?php echo $employee_status;  ?></td>
										</tr>
										<tr>
											<td align="left">Company:</td>
											<td align="left"><?php echo $company_name; ?></td>
											<td align="left">Assigned To:</td>
											<td align="left"><?php echo $vessel; ?></td>
										</tr>
										<tr>
											<td align="left">Date Hired:</td>
											<td align="left"><?php echo $datehired; ?></td>
											<td align="left">Date Separated:</td>
											<td align="left"><?php echo $dateseparated; ?></td>
										</tr>
										
										<tr>
											<td align="left">Position:</td>
											<td align="left"><?php echo $position_name;?></td>
											<td align="left">Temp Assignment:</td>
											<td align="left"><?=$temp_assign?></td>
										</tr>
										<tr>
											<td align="left">Group:</td>
											<td align="left"><?=$group?></td>
											<td align="left">Division:</td>
											<td align="left"><?=$division?></td>
										</tr>
										<tr>
											<td align="left">Department:</td>
											<td align="left"><?=$department_name?></td>
											<td align="left">Section:</td>
											<td align="left"><?=$section?></td>
											
										</tr>
										<tr>
											<td align="left">Sub-section:</td>
											<td align="left"><?=$sub_section?></td>
											<td align="left">SSS #:</td>
											<td align="left"><?php echo $sssnum; ?></td>
										</tr>
										<tr>
											<td align="left">PhilHealth #:</td>
											<td align="left"><?php  echo $phnum; ?></td>
											<td align="left">Pag-ibig #:</td>
											<td align="left"><?php echo $pagibig_num; ?></td>
											<!--<td align="left">ELF Rate:</td>
											<td align="left"><?php //echo  $elfrate; ?></td>-->
										</tr>
										<tr>
											<td align="left">TIN:</td>
											<td align="left"><?php  echo $tin; ?></td>
											<td align="left">Tax Code:</td>
												<td align="left"><?php echo $taxcode;  ?></td>
										</tr>
										<tr>
											<?php if($view_salary){ ?>
												<td align="left">Salary Grade:</td>
												<td align="left"><?php echo $salarygrade; ?></td>
												
												<td align="left">Bank Account #:</td>
												<td align="left"><?php echo $bankacct;  ?></td>
											<?php } ?>
										</tr>
										<tr>
											<td align="left">Remarks/Experience:</td>
											<td align="left" colspan="3"><?php echo $remarks; ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
				</div>
			</div>
			<div id="ehist-tab" class="tab-pane fade">
				<div class="panel-body">
					<?php if($access_status): ?>
						<button class="btn btn-success btn-m" id="btnEmployeeHistory">Add Work History</button>
					<?php endif; ?>
				</div>
					<div class="panel-body" style="overflow-y: auto; height:500px;">
						<div id="frmEmployeeHistory" class="hide">
							<div class="panel panel-primary">
								<div id="employeeHistoryTitle" class="panel-heading">
									<strong>
										<span class="glyphicon glyphicon-building"></span>
										Add Work History
										<div class="pull-right">
											<button class="close" id="closeEmployee">&times;</button>
										</div>
									</strong>
								</div>
								<div class="panel-body">
									<form id="employeehistory_form" name="employeehistory_form"  action="<?php echo HTTP_PATH."hr/ehistory/insert/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
										<?php echo $this->Mmm->createCSRF(); ?>
										<div class="row">
											<div class="col-sm-12 col-md-6">
												Effectivity Date:
												<input type='text' id="effectivity_date" class="form-control input-sm" name="effectivity_date" value="<?php echo date("Y-m-d"); ?>" />
											</div>
											<script>
												$('#effectivity_date').datepicker();
											</script>
											<div class="col-sm-12 col-md-6">
												Position:
												<select name="position" class='form-control input-sm'>
													<option value=""></option>
													<?php echo $positionoptions; ?>
												</select>
											</div>
										</div>
										<?php if($access_status==true): ?>
										<div class="row">
											<div class="col-sm-12 col-md-6">
												Employment Status:
												<select class="form-control input-sm" name="empstat" id="empstat">
													<option></option>
													<option <?php echo ($employment_status=="Probationary" ? "SELECTED" : ""); ?> value="Probationary">Probationary</option>
													<option <?php echo ($employment_status=="Regular" ? "SELECTED" : ""); ?> value="Regular">Regular</option>
													<option <?php echo ($employment_status=="Casual" ? "SELECTED" : ""); ?> value="Casual">Casual</option>
													<option <?php echo ($employment_status=="Fixed Term" ? "SELECTED" : ""); ?> value="Fixed Term">Fixed Term</option>
													<option <?php echo ($employment_status=="Project-based" ? "SELECTED" : ""); ?> value="Project-based">Project-based</option>
													<option <?php echo ($employment_status=="Part-time" ? "SELECTED" : ""); ?> value="Part-time">Part-time</option>
													<option <?php echo ($employment_status=="On-leave" ? "SELECTED" : ""); ?> value="On-leave">On-leave</option>
													<option <?php echo ($employment_status=="AWOL" ? "SELECTED" : ""); ?> value="AWOL">AWOL</option>
													<option <?php echo ($employment_status=="Preventive Suspension" ? "SELECTED" : ""); ?> value="Preventive Suspension">Preventive Suspension</option>
													<option <?php echo ($employment_status=="Suspended" ? "SELECTED" : ""); ?> value="Suspended">Suspended</option>
													<option <?php echo ($employment_status=="Retired" ? "SELECTED" : ""); ?> value="Retired">Retired</option>
													<option <?php echo ($employment_status=="Terminated" ? "SELECTED" : ""); ?> value="Terminated">Terminated</option>
													<option <?php echo ($employment_status=="Resigned" ? "SELECTED" : ""); ?> value="Resigned">Resigned</option>
													<option <?php echo ($employment_status=="Separated" ? "SELECTED" : ""); ?> value="Separated">Separated</option>
												</select>
											</div>
											<?php if($edit_salary==true): ?>
											<div class="col-sm-12 col-md-6">
												Salary Grade:
												<select name="salgrade" class='form-control input-sm'>
													<option value=""></option>
													<?php echo $salaryGradeOptions; ?>
												</select>
											</div>
											<?php endif; ?>
										</div>
										<div class="row hidden" id='daterange'>
											<div class="col-sm-12 col-md-6">
												From:
												<input type="date" id="from_date" name="from_date" class='form-control input-sm'>
											</div>
											<div class="col-sm-12 col-md-6">
												To:
												<input type="date" id="to_date" name="to_date" class='form-control input-sm'>
											</div>
										</div>
										<?php endif; ?>
										<div class="row">
											<div class="col-sm-6">
												Assigned To:
												<select name="assignedto" class='form-control input-sm'>
													<option value=""></option>
													<?php echo $vesseloptions; ?>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												Apply Immediately:
												<input type="checkbox" name="apply_immediately" value='1' checked>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<span class='pull-right'>
												<input type='submit' class='btn btn-success btn-m' value='Save' />
												</span>
											</div>
										</div>
									</form>

								</div>
							</div>
						</div>
						<div class="table-responsive">
							<div class='btn btn-default btn-xs'><h4>Time in company: <?php echo $time_in_company;?></h4></div>
							<table  class="table table-striped table table-bordered table-hover" >
									<tr align="center">
										<td width="82">Effectivity Date</td>
										<td width="100">Value Changed</td>
										<td width="130">From</td>
										<td width="117">To</td>
										<td width="117">Added By</td>
										<td width="117">Manage</td>
									</tr>
									<?php
										echo $employee_history_table;
									?>
							</table>
						</div>
				</div>
			</div>
			<?php if($view_salary): ?>
				<div id="phist-tab" class="tab-pane fade">
						<div class="panel-body" style="overflow-y: auto; height:500px;">
							<?php 
								$rates = $this->Payroll_model->getRates($salaryrate,$e['vessel_id']);
								echo "<div class='btn btn-default btn-xs'><h4>  Rate per month: ".number_format($rates['monthly'],2,".",",")." (".$salarygrade.")  </h4></div>";
								echo "<div class='btn btn-default btn-xs'><h4>  Rate per day: ".number_format($rates['daily'],2,".",",")." </h4></div>";
								echo "<div class='btn btn-default btn-xs'><h4>  Rate per hour: ".number_format($rates['hourly'],2,".",",")." </h4></div>";
								echo "<div class='btn btn-default btn-xs'><h4>  Rate per minute: ".number_format($rates['per_min'],2,".",",")." </h4></div>";
							?>
							<div class="table-responsive">
								<table  class="table table-striped table-bordered table-hover">
									<thead class="text-center">
										<tr>
											<td width="100">Payroll Date</td>
											<td width="126">Gross Salary</td>
											<td width="130">Total Deductions</td>
											<td width="117">Loan Payment</td>
											<td width="82">Net Pay</td>
											<td width="100">Manage</td>
										</tr>
									</thead>
									<tbody>
										<?php
											echo $payroll_table;
											echo $total_table;
										?>
									</tbody>
								</table>
							</div>
					</div>
				</div>
					<div id="bonus-tab" class="tab-pane fade">
						<div class="panel-body">
							<button class="btn btn-success btn-m" id="bonusFormBtn">Add Bonus</button>
						</div>
						<div class="panel-body" style="overflow-y: auto; height:500px;">
							<!-- Night Differential form -->
							<div id="bonusForm" class="hide">
								<div class="panel panel-primary">
									<div id="bonusFormTitle" class="panel-heading">
										Add Bonus
										<div class="pull-right">
											<button class="close" id="closeBonus">&times;</button>
										</div>
									</div>
									<div class="panel-body">
										<form id="bonus_form" name="bonus_form"  action="<?php echo HTTP_PATH."hr/bonus/insert/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
											<?php echo $this->Mmm->createCSRF(); ?>
											<div class="col-sm-12 col-md-6">
												Release Date:
												<input type='text' id="bonus_date" class="form-control input-sm" name="bonus_date" value="" />
											</div>
											<script>
												$('#bonus_date').datepicker();
											</script>
											<div class="col-sm-12 col-md-6">
												Amount: <input type='number' class='form-control input-sm' id="bonus_amount" name="bonus_amount"/>
											</div>
											<div class="col-sm-12 col-md-6">
												Type: <select id="bonus_type" name="bonus_type" class='form-control input-sm'>
													<option></option>
													<option value="Bonus">Bonus</option>
													<option value="13th Month - Full">13th Month - Full</option>
													<option value="13th Month - 1st half">13th Month - 1st half</option>
													<option value="13th Month - 2nd half">13th Month - 2nd half</option>
												</select>
											</div>
											<div class="col-sm-12 col-md-12">
												Remarks: <input type='text' class='form-control input-sm' name="bonus_remarks"/>
											</div>
											<div class="col-sm-12 col-md-12">
												<br>
												<span class='pull-right'>
												<input type='button' class='btn btn-success btn-m' value='Save' onclick="javascript:checkBonusForm()">
												</span>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<table  class="table table-striped table-bordered table-hover" >
									<thead class="text-center">
										<tr>
											<td width="100">Release Date</td>
											<td width="126">Amount</td>
											<td width="117">Type</td>
											<td width="117">Remarks</td>
											<td width="117">Approved?</td>
											<td width="82">Manage</td>
										</tr>
									</thead>
									<tbody>
										<?php
											echo $bonus_table;
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
			<?php endif; ?>
			
			<?php if($access_ot): ?>
			<div id="overtime-tab" class="tab-pane fade">
				<div class="panel-body">
					<button class="btn btn-success btn-m" id="overtimeFormBtn">Add Overtime</button>
				</div>
				<div class="panel-body" style="overflow-y: auto; height:500px;">
					<!-- Overtime form -->
					<div id="overtimeForm" class="hide">
						<div class="panel panel-primary">
							<div id="overtimeFormTitle" class="panel-heading">
								Add Overtime
								<div class="pull-right">
									<button class="close" id="closeOvertime">&times;</button>
								</div>
							</div>
							<div class="panel-body">
								<form id="overtime_form" name="overtime_form"  action="<?php echo HTTP_PATH."hr/overtime/insert/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<?php echo $this->Mmm->createCSRF(); ?>
									<div class="col-sm-6">
										Date: <input type='text' id="ot_date" class='form-control input-sm' name="ot_date"/>
									</div>
									<script>
										$('#ot_date').datepicker();
									</script>
									<div class="col-sm-6">
										Time(hh:mm:ss): <input type='text' class='form-control input-sm' id="ot_time" name="ot_time"/>
									</div>
									<div class="col-sm-12">
										Type:
										<select name="ot_type" id='ot_type' class='form-control input-sm'>
											<option value=""></option>
											<option value="Regular Day">Regular Day - 25%</option>
											<option value="Rest Day">Rest Day - 30%</option>
											<option value="Legal Holiday">Legal Holiday - 200%</option>
											<option value="Legal Holiday on Rest Day">Legal Holiday on Rest Day - 260%</option>
											<option value="Special Holiday">Special Holiday - 30%</option>
											<option value="Special Holiday on Rest Day">Special Holiday on Rest Day - 50%</option>
										</select>
									</div>
									<div class="col-sm-12">
										Reason: <input type='text' class='form-control input-sm' name="ot_reason"/>
									</div>
									<div class="col-sm-12">
										<br>
										<span class='pull-right'>
										<input type='button' class='btn btn-success btn-m' value='Save' onclick="javascript:checkOvertimeForm()">
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table  class="table table-striped table-bordered table-hover" >
							<thead class="text-center">
								<tr>
									<td width="100">Overtime Date</td>
									<td width="126">Overtime Credit</td>
									<td width="130">Rate</td>
									<td width="130">Type</td>
									<td width="117">Reason</td>
									<td width="82">Computed?</td>
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
			<div id="nightdiff-tab" class="tab-pane fade">
				<div class="panel-body">
					<button class="btn btn-success btn-m" id="nightDiffFormBtn">Add Night Differential</button>
				</div>
				<div class="panel-body" style="overflow-y: auto; height:500px;">
					<!-- Night Differential form -->
					<div id="nightDiffForm" class="hide">
						<div class="panel panel-primary">
							<div id="nightDiffFormTitle" class="panel-heading">
								Add Night Differential
								<div class="pull-right">
									<button class="close" id="closeNightDiff">&times;</button>
								</div>
							</div>
							<div class="panel-body">
								<form id="nightdiff_form" name="nightdiff_form"  action="<?php echo HTTP_PATH."hr/night_differential/insert/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<?php echo $this->Mmm->createCSRF(); ?>
									<div class="col-sm-6">
										Date: <input type='text' id="nd_date" class='form-control input-sm' name="nd_date"/>
									</div>
									<script>
										$('#nd_date').datepicker();
									</script>
									<div class="col-sm-6">
										Time(hh:mm:ss): <input type='text' class='form-control input-sm' id="nd_time" name="nd_time"/>
									</div>
									<div class="col-sm-12">
										Reason: <input type='text' class='form-control input-sm' name="nd_reason"/>
									</div>
									<div class="col-sm-12">
										<br>
										<span class='pull-right'>
										<input type='button' class='btn btn-success btn-m' value='Save' onclick="javascript:checkNightDiffForm()">
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table  class="table table-striped table-bordered table-hover" >
							<thead class="text-center">
								<tr>
									<td width="100">Date</td>
									<td width="126">Night Differential Credit</td>
									<td width="117">Reason</td>
									<td width="82">Computed?</td>
									<td width="82">Manage</td>
								</tr>
							</thead>
							<tbody>
								<?php
									echo $nd_table;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div id="undertime-tab" class="tab-pane fade">
				<div class="panel-body">
					<button class="btn btn-success btn-m" id="undertimeFormBtn"> Add Undertime</button>
				</div>
				<div class="panel-body" style="overflow-y: auto; height:500px;">
					<!-- Undertime form -->
					<div id="undertimeForm" class="hide">
						<div class="panel panel-primary">
							<div id="undertimeFormTitle" class="panel-heading">
								Add Undertime
								<div class="pull-right">
									<button class="close" id="closeUndertime">&times;</button>
								</div>
							</div>
							<div class="panel-body">
								<form id="undertime_form" name="undertime_form"  action="<?php echo HTTP_PATH."hr/undertime/insert/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<?php echo $this->Mmm->createCSRF(); ?>
									<div class="col-sm-6">
										Date: <input type='text' id="ut_date" class='form-control input-sm' name="ut_date"/>
									</div>
									<script>
										$('#ut_date').datepicker();
									</script>
									<div class="col-sm-6">
										Time(hh:mm:ss): <input type='text' class='form-control input-sm' name="ut_time" id="ut_time"/>
									</div>
									<div class="col-sm-12">
										Reason: <input type='text' class='form-control input-sm' name="ut_reason"/>
									</div>
									<div class="col-sm-12">
										<br>
										<span class='pull-right'>
										<input type="button" class='btn btn-success btn-m' value="Save" onclick="javascript:checkUndertimeForm()">
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table  class="table table-striped table-bordered table-hover" >
							<thead class="text-center">
								<tr>
									<td width="100">Undertime Date</td>
									<td width="126">Undertime Credit</td>
									<td width="117">Reason</td>
									<td width="82">Computed?</td>
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
			<?php endif; ?>
			<?php if($access_elf): ?>
          	<!--<div id="elf-tab" class="tab-pane fade">
            	<div class="panel-body">
            		<button href="#" id="edit_elf_contri" class="btn btn-warning btn-m"> Edit Contribution
								</button>
				</div>
				<div class="panel-body" style="overflow-y: auto; height:500px;">
					<div id="elfContibutionForm" class="hide">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Edit Contribution
								<div class="pull-right">
									<button class="close" id="closeELFContribution">&times;</button>
								</div>
							</div>
							<div class="panel-body">
								<form id="employee_elf" name="employee_elf"  action="<?php //echo HTTP_PATH."hr/update_elf/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
								<?php //echo $this->Mmm->createCSRF(); ?>
									<div class="col-md-12">
										Total:
										<input type='number' class='form-control input-sm' name="total_elf_contribution" id="total_elf_contribution"/>
									</div>
									<div class="col-md-12">
										<br>
										<span class='pull-right'>
											<input type='button' id="save_elf" class='btn btn-success btn-m' value='Save' onclick="javascript:checkELFForm()" /></font>
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table  class="table table-striped table-bordered table-hover" >
							<thead class="text-center">
								<tr>
									<td width="100">Date</td>
									<td width="126">Contribution</td>
								</tr>
							</thead>
							<tbody>
								<?php //echo $elf_table; ?>
								<tr>
									<td align="center"><b>Total Contribution:</b></td>
									<td align="center"><?php //echo $this->Abas->currencyFormat($elfcontri). "&nbsp"; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>-->
            <?php endif; ?>
			<?php if($access_loan): ?>
            <div id="loans-tab" class="tab-pane fade">
            	<div class="panel-body">
					<button class="btn btn-success btn-m" id="apply_new_loan">Add Loan Application</button>
				</div>
				<div class="panel-body" style="overflow-y: auto; height:500px;">
					<div id="loanForm" class="hide">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Add Loan Application
								<div class="pull-right">
									<button class="close" id="closeLoan">&times;</button>
								</div>
							</div>
							<div class="panel-body">
								<form id="loan_form" name="loan_form"  action="<?php echo HTTP_PATH."hr/update_loan/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<?php echo $this->Mmm->createCSRF(); ?>
									<div class="col-sm-6">
										Loan Type:
										<select class='form-control input-sm' name='loanType' id='loanType'>
											<option></option>
											<!--<option value="ELF">ELF Loan</option>-->
											<option value="SSS">SSS Loan</option>
											<option value="PagIbig">Pag-Ibig Loan</option>
											<option value="Cash Advance">Cash Advance</option>
										</select>
									</div>
									<div class="col-sm-6">
										Principal: <input type='text' name='loanPrincipal' id='loanPrincipal' class='form-control input-sm'/>
									</div>
									<div class="col-sm-6">
										Monthly Amortization: <input type='text' name='loanAmortization' id='loanAmortization' class='form-control input-sm' />
									</div>
									<div class="col-sm-6">
										Date Loaned: <input type='text' name='loanDate' id='loanDate' class='form-control input-sm' />
										<script>
											$('#loanDate').datepicker();
										</script>
									</div>
									<div class="col-sm-6">
										Due Date: <input type='text' name='loanDueDate' id='loanDueDate' class='form-control input-sm' />
										<script>
											$('#loanDueDate').datepicker();
										</script>
									</div>
									<div class="col-sm-12">
										Remarks: <input type='text' name='loanRemark' id='loanRemark' class='form-control input-sm' />
									</div>
									<div class="col-sm-12">
										<br>
										<span class='pull-right'>
										<input type="button" class='btn btn-success btn-m' value="Save" onclick="javascript:checkLoanForm()">
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div id="loanPaymentForm" class="hide">
							<div class="panel panel-primary">
								<div class="panel-heading">
									Add Loan Payment
									<div class="pull-right">
										<button class="close" id="closeLoanPayment">&times;</button>
									</div>
								</div>
								<div class="panel-body">
									<form action="<?php echo HTTP_PATH; ?>hr/pay_loan/id_here" method="POST" id="frmLoanPayment">
										<?php echo $this->Mmm->createCSRF(); ?>
										<div class="col-sm-6">
											<label for="txtLoanPayDate">Payment Date</label>
											<input type="text" name="loanPayDate" id="txtLoanPayDate" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" />
											<script>$("#txtLoanPayDate").datepicker();</script>
										</div>
										<div class="col-sm-6">
											<label for="txtLoanPayAmt">Amount Paid</label>
											<input type="number" name="loanPayAmt" id="txtLoanPayAmt" class="form-control input-sm" />
										</div>
										<div class="col-sm-12">
											<br>
											<span class='pull-right'>
											<input type="button" class='btn btn-success btn-m' value="Save" onclick="javascript:checkLoanPaymentForm()">
											</span>
										</div>
									</form>
								</div>
							</div>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" border="1" data-cache="false">
							<thead class="text-center">
								<tr>
									<td width="100">Loan Type</td>
									<td width="126">Loan Date</td>
									<td width="130">Due Date</td>
									<td width="100">Remark</td>
									<td width="117">Monthly Amortization</td>
									<td width="117">Loaned Amount</td>
									<td width="82">Total Payment</td>
									<td width="82">Balance</td>
									<td width="82">Manage</td>
								</tr>
							</thead>
							<tbody>
								<?php echo $loan_table;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
            <?php endif; ?>
			<?php if($access_leave): ?>
            <div id="leaves-tab" class="tab-pane fade">
            	<div class="panel-body">
					<!--button class='btn btn-warning btn-m' id="leaveCreditBtn">Set Annual Leave Credits</button-->
					<button onclick="setCreditCode()" class="btn btn-warning btn-m">Set Leave Credit Code</button>
					<button class='btn btn-success btn-m' id="leaveApplicationBtn">Add Leave Application</button>	
				</div>
				<div class="panel-body" style="overflow-y: auto; height:500px;">
					<!-- Leave Credits Update form -->
					<div id="leaveForm" class="hide">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Set Annual Leave Credits
								<div class="pull-right">
									<button class="close" id="closeLeaveCredit">&times;</button>
								</div>
							</div>
							<div class="panel-body">
								<form id="leave_form" name="leave_form"  action="<?php echo HTTP_PATH."hr/update_leave/".$e['id']; ?>" method="post" enctype='multipart/form-data'>
									<?php echo $this->Mmm->createCSRF(); ?>
									<div class="col-md-12">Total:
										<input type='number' class='form-control input-sm' name="leave_credits" id="leave_credits"/>
									</div>
									<div class="col-md-12">
										<br>
										<span class='pull-right'>
											<input type='button' id="save_leave" class='btn btn-success btn-m' value='Save' onclick="javascript:checkLeaveCreditForm()" />
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- Leave form -->
					<div id="leaveApplication" class="hide">
						<div class="panel panel-primary">
							<div class="panel-heading" id="leaveApplicationTitle">
								Add Leave Application
								<div class="pull-right">
									<button class="close" id="closeLeave">&times;</button>
								</div>
							</div>
							<div class="panel-body">
								<form action="<?php echo HTTP_PATH."hr/apply_leave/".$e['id']; ?>" id="leave_application" name="leave_application" enctype='multipart/form-data' method="POST">
									<?php echo $this->Mmm->createCSRF(); ?>
									<div class="col-md-12">
										Leave Type:
										<select class='form-control input-sm' id="leave_type" name='leave_type'>
											<option></option>
											<option value="Vacation">Vacation Leave</option>
											<option value="Emergency">Emergency Leave</option>
											<option value="Sick">Sick Leave</option>
											<option value="Maternity">Maternity Leave</option>
											<option value="Paternity">Paternity Leave</option>
											<option value="Bereavement">Bereavement Leave</option>
											<option value="Absence">Absence</option>
											<option value="Other">Other</option>
										</select>
									</div>
									<div class="col-md-6">
										Start Date:
										<input id="leave_start" class="form-control input-sm" type="text" name="leave_start" />
									</div>
									<div class="col-md-6">
										End Date:
										<input id="leave_end" class="form-control input-sm" type="text" name="leave_end" />
									</div>
									<div class="col-md-6">
										Number of days:
										<input id="leave_limit" class="form-control input-sm" type="number" name="leave_limit" />
									</div>
									<div class="col-md-12">
										Reason:
										<input id="status_remarks" class="form-control input-sm" type="text" name="leave_reason" />
									</div>
									<div class="col-md-12">
										<br>
										<span class='pull-right'>
										<input type="button" id="save_leave_application" class='btn btn-success btn-m' value="Save" onclick="javascript:checkLeaveForm()">
									</span>
									</div>
									<script>
										$('#leave_start').datepicker(/*{minDate: 0}*/);
										$('#leave_end').datepicker(/*{minDate: 0}*/);
									</script>
								</form>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table  class="table table-striped table table-bordered table-hover" >
							<thead class="text-center">
								<tr>
									<td width="100">Leave Type</td>
									<td width="100">Date Start</td>
									<td width="126">Date End</td>
									<td width="126">No. of Days</td>
									<td width="126">Reason</td>
									<td width="126">Manage</td>
								</tr>
							</thead>
							<tbody>
								<?php echo $leave_table; ?>
								<tr>
									<td colspan="2" style="text-align:right">Leave Credit Code: </td>
									<?php $this->Mmm->debug($credit_code) ?>
									<td><?=$credit_code['code']?></td>
								</tr>
								<tr>
									<td colspan="2" style="text-align:right">Total Annual Leave Credits: </td>
									<td>
										<?php 
											if($credit_code){
												if(strtotime($credit_code['reg_date']) > time()){
													echo "FOR REGULAR ONLY";
												}else{
													echo $credit_code['credit'];	
												}
											}else{
												echo $leavecredits." days";
											}
										?>
									</td>
								</tr>
								<tr><td colspan="2" style="text-align:right">Total Filed Leaves for <?php echo date('Y');?>: </td><td><?php echo $num_leaves; ?> days </td></tr>
								<tr><td colspan="2" style="text-align:right">Total Absences for <?php echo date('Y');?>: </td><td><?php echo $num_absences; ?> days</td></tr>
								<tr><td colspan="2" style="text-align:right">Remaining Leave Credits for <?php echo date('Y');?>:</td><td><?php echo ($leavecredits-$num_leaves); ?> days</td></tr>
								<tr><td colspan="2" style="text-align:right">Unused Leave Credits from <?php echo date('Y')-1;?>:</td><td>
									<?php 
										$previous_year = date('Y')-1;
										$unused = $this->Hr_model->getUnusedLeaveCredits($employee_record['id'],$previous_year);
										echo $unused;
									?> 
									days</td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php endif; ?>
        	<div id="accountability-tab" class="tab-pane fade" style="overflow-x: auto">
				<div class="panel-body">
					<table  class="table table-striped table table-bordered table-hover" >
						<thead class="text-center">
							<tr>
							  <th>#</th>
							  <th>Fixed Asset Code</th>
			                  <th>Asset Name</th>
			                  <th>Description</th>
			                  <th>Remarks/Purpose</th>
			                  <th>Status</th>
			                  <th>Date Issued</th>
			                  <th>Date Returned</th>
			                  <th>Received By</th>
			                  <th>Condition</th>
			                  <th>Manage</th>
							</tr>
						</thead>
						<tbody>
							<?php
							    $ctr=1;
							    $ctr_issued=0;
							    $ctr_returned=0;
							    if(isset($fixed_assets)){
									foreach($fixed_assets as $asset){
										$fixed_asset = $this->Asset_Management_model->getFixedAsset($asset['fixed_asset_id']);
										$item = $this->Inventory_model->getItem($fixed_asset->item_id);
					                	echo "<tr>";
					                	echo "<td>".$ctr."</td>";
					                	echo "<td>".$fixed_asset->asset_code."</td>";
					                  	echo "<td>".$fixed_asset->item_name."</td>";
					                  	echo "<td>".$fixed_asset->particular."</td>";
					                  	echo "<td>".$asset['remarks']."</td>";
					                  	echo "<td>".$asset['status']."</td>";
					                  	if($asset['status']=="Issued"){
					                  		$ctr_issued++;
					                  	}
					                  	if($asset['status']=="Returned"){
					                  		$ctr_returned++;
					                  	}
					                  	if($asset['date_issued']!=0){
					                  		echo "<td>".date('F d, Y',strtotime($asset['date_issued']))."</td>";
					                  	}else{
					                  		echo "<td>-</td>";
					                  	}

					                  	if($asset['date_returned']!=0){
					                  		echo "<td>".date('F d, Y',strtotime($asset['date_returned']))."</td>";
					                  	}else{
					                  		echo "<td>-</td>";
					                  	}
					                  	if($asset['received_by']!=''){
					                  		$user = $this->Abas->getUser($asset['received_by']);
					                  		echo "<td>".$user['full_name']."</td>";
					                  	}else{
					                  		echo "<td>-</td>";
					                  	}
					                  	if($asset['condition_of_returned_item']!=''){
					                  		echo "<td>".$asset['condition_of_returned_item']."</td>";
					                  	}else{
					                  		echo "<td>-</td>";
					                  	}
					                  	echo "<td><a href='".HTTP_PATH."Asset_Management/accountability_form/view/".$asset['accountability_id']."' class='btn btn-info btn-xs btn-block' target='_blank'>View Accountability Form</a></td>";
					                  	echo "</tr>";
					                  	$ctr++;
					                }
					            }else{
					            	echo "<td colspan='11'><center>No Fixed Asset record found!</center></td>";
					            }
							?>
							<tr>
								<td colspan="3" style="text-align: right">No. of Issued Assets:</td>
								<td><?php echo $ctr_issued;?></td>
							</tr>
							<tr>
								<td colspan="3" style="text-align: right">No. of Returned Assets:</td>
								<td><?php echo $ctr_returned;?></td>
							</tr>
							<tr>
								<td colspan="3" style="text-align: right">Total Assets:</td>
								<td><?php echo $ctr-1;?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="crew-movement-tab" class="tab-pane fade" style="overflow-x: auto">
				<?php if(!isset($crew_movements_array)){
					$crew_movements_array = array();
				} ?>
				<div class="panel-body">
					<a class="btn btn-success" href="<?=HTTP_PATH.'hr/crew_movement/add/'.$eid?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Add Crew Movement</a>
					<a onclick="crewMovementConfirm(<?=$eid?>)" class="btn btn-dark">Transfer to Original</a>
					<br/><br/><br/>
					<table  class="table table-striped table table-bordered table-hover" >
						<thead class="text-center">
							<tr>
							  <th>#</th>
							  <th>Transfer Date</th>
							  <th>Assigned From</th>
							  <th>Assigned To</th>
							  <th>Embarkation Start</th>
							  <th>Embarkation End</th>
							  <th>Status</th>
			                  <th>Added by</th>
			                  <th>Manage</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($crew_movements_array as $ctr => $row) { ?>
							<tr>
								<td><?=$ctr+1?></td>
								<td><?=$this->Abas->dateFormat($row['transfer_date'])?></td>
								<td><?=$row['vessel_from']?></td>
								<td><?=$row['vessel_to']?></td>
								<td><?=$this->Abas->dateFormat($row['embarkation_start'])?></td>
								<td><?=$this->Abas->dateFormat($row['embarkation_end'])?></td>
								<td><?=$row['status']?></td>
								<td><?=$this->Abas->dateFormat($row['added_by'])?></td>
								<td>
									<a href="<?=HTTP_PATH.'hr/crew_movement/edit/'.$row['id']?>" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a> 
									<a href="<?=HTTP_PATH.'hr/crew_movement/delete/'.$row['id']?>" class="btn btn-danger btn-xs">Delete</a>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
</div>
</div>
	<script type="text/javascript">
		$('#myLeave').popover({
			html : true,
			title: function() {
				return $("#leaveTitle").html();
			},
			content: function() {
				return $("#leaveForm").html();
			}
		});
		$('#edit_elf_contri').popover({
			html : true,
			title: function() {
				return $("#myformIdorClassTitle").html();
			},
			content: function() {
				return $("#myFormIdorClassForm").html();
			}
		});
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
		$( "#closeEmployee" ).click(function() {
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

		$( "#closeLeave" ).click(function() {
			var x = $("#leaveApplication").attr("class");
			if(x=="hide") {
				$("#leaveApplication").removeClass("hide");
			}
			else {
				$("#leaveApplication").addClass("hide");
			}
		});
		$( "#closeELFContribution, #edit_elf_contri" ).click(function() {
			var x = $("#elfContibutionForm").attr("class");
			if(x=="hide") {
				$("#elfContibutionForm").removeClass("hide");
			}
			else {
				$("#elfContibutionForm").addClass("hide");
			}
		});
		/*$( "#closeLeaveCredit, #leaveCreditBtn" ).click(function() {
			var x = $("#leaveForm").attr("class");
			if(x=="hide") {
				$("#leaveForm").removeClass("hide");
			}
			else {
				$("#leaveForm").addClass("hide");
			}
		});*/

        $( "#myBack" ).click(function() {
			var x = $("#loanForm").attr("class");
			if(x=="hide") {
				$("#loanForm").removeClass("hide");
			}
			else {
				$("#loanForm").addClass("hide");
			}
		});
		$( "#apply_new_loan" ).click(function() {
			var x = $("#loanForm").attr("class");
			if(x=="hide") {
				$("#loanForm").removeClass("hide");
			}
			else {
				$("#loanForm").addClass("hide");
			}

		});
		$( "#closeLoan" ).click(function() {
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

		$( "#closeOvertime" ).click(function() {
			var x = $("#overtimeForm").attr("class");
			if(x=="hide") {
				$("#overtimeForm").removeClass("hide");
			}
			else {
				$("#overtimeForm").addClass("hide");
			}
		});
		$( "#nightDiffFormBtn" ).click(function() {
			var x = $("#nightDiffForm").attr("class");
			if(x=="hide") {
				$("#nightDiffForm").removeClass("hide");
			}
			else {
				$("#nightDiffForm").addClass("hide");
			}
		});
		$( "#bonusFormBtn" ).click(function() {
			var x = $("#bonusForm").attr("class");
			if(x=="hide") {
				$("#bonusForm").removeClass("hide");
			}
			else {
				$("#bonusForm").addClass("hide");
			}
		});
		$( "#closeNightDiff" ).click(function() {
			var x = $("#nightDiffForm").attr("class");
			if(x=="hide") {
				$("#nightDiffForm").removeClass("hide");
			}
			else {
				$("#nightDiffForm").addClass("hide");
			}
		});
		$( "#closeBonusDiff,#closeBonus" ).click(function() {
			var x = $("#bonusForm").attr("class");
			if(x=="hide") {
				$("#bonusForm").removeClass("hide");
			}
			else {
				$("#bonusForm").addClass("hide");
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
		$( "#closeUndertime" ).click(function() {
			var x = $("#undertimeForm").attr("class");
			if(x=="hide") {
				$("#undertimeForm").removeClass("hide");
			}
			else {
				$("#undertimeForm").addClass("hide");
			}
		});
		$( "#closeLoanPayment" ).click(function() {
			var x = $("#loanPaymentForm").attr("class");
			if(x=="hide") {
				$("#loanPaymentForm").removeClass("hide");
			}
			else {
				$("#loanPaymentForm").addClass("hide");
			}
		});
		$("#empstat").change(function(){
			var empstat = $(this).val();
			if(empstat=='Preventive Suspension' || empstat=='Suspended' || empstat=='On-leave'){
				$('#daterange').removeClass('row hidden').addClass('row');
			}else{
				$('#daterange').removeClass('row').addClass('row hidden');
			}
			$('#from_date').val('');
			$('#to_date').val('');
		});
		function setCreditCode(){
	    	bootbox.prompt({
	    		size: "small",
			    title: "Filter Leave Status!",
			    inputType: 'select',
			    inputOptions: [
			    {
			        text: 'Choose one...',
			        value: '',
			    },
			    <?php
			    	$leave_code = $this->Abas->getItems('leave_credits');
			    	foreach ($leave_code as $ctr => $row) {
			    		echo "{";
			    		echo "text: '$row->code',";
			    		echo "value: '$row->id',";
			    		echo "},";
			    	}
			    ?>
			    ],
			    callback: function (result) {
			    	console.log(result);
			    	if(result == ''){
			    		bootbox.hideAll();
			    	}else if(result != null){
			        	window.location.href = "<?=HTTP_PATH?>"+"hr/set_leave_credit/set/"+"<?=$e['id']?>/"+result;
			        }else{
			        	bootbox.hideAll();
			        }
			    }
			});
	    }
		function getPayslip(payslip_id) {
			toastr.clear();
			toastr['info']("This will bring you to the payslip. Continue? <a href='"+"<?php echo HTTP_PATH; ?>payroll_history/payslip_printable/"+payslip_id+"/employee"+"' class='btn btn-success' target='_new'>Yes</a>", "ABAS Says");
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
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
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
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
				document.getElementById("loan_form").submit();
				return true;
			}
		}

		function checkNightDiffForm() {
			var msg="";
			var patt1=/^[0-9]+$/i;
			// /*
			var nd_date=document.getElementById("nd_date").value;
			if (nd_date==null || nd_date=="") {
				msg+="Date is required! <br/>";
			}
			var nd_time=document.getElementById("nd_time").value;
			if (nd_time==null || nd_time=="") {
				msg+="Time is required! <br/>";
			}
			if(msg!="") {
				toastr['error'](msg, "You have missing input!");
				return false;
			}
			else {
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
				document.getElementById("nightdiff_form").submit();
				return true;
			}
		}

		function checkBonusForm() {
			var msg="";

			var bonus_date=document.getElementById("bonus_date").value;
			if (bonus_date==null || bonus_date=="") {
				msg+="Release Date is required! <br/>";
			}
			var bonus_amount=document.getElementById("bonus_amount").value;
			if (bonus_amount==null || bonus_amount=="") {
				msg+="Amount is required! <br/>";
			}
			var bonus_type=document.getElementById("bonus_type").value;
			if (bonus_type==null || bonus_type=="") {
				msg+="Type is required! <br/>";
			}
			if(msg!="") {
				toastr['error'](msg, "You have missing input!");
				return false;
			}
			else {
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
				document.getElementById("bonus_form").submit();
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
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
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
			var ot_type=document.getElementById("ot_type").selectedIndex;
			if (ot_type==null || ot_type=="") {
				msg+="Type is required! <br/>";
			}

			if(msg!="") {
				toastr['error'](msg, "You have missing input!");
				return false;
			}
			else {
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
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
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
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
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
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
				$('body').addClass('is-loading');
				$('#modalDialog').modal('toggle');
				document.getElementById("employee_elf").submit();
				return true;
			}
		}
		function confirmLeaveCancel(empid, leaveid) {
			toastr['warning']('<a class="btn btn-danger btn-sm" href="<?php echo HTTP_PATH; ?>hr/apply_leave/'+empid+'/'+leaveid+'">Cancel Leave</a>', "Are you sure?");
		}
		function confirmHistoryDelete(empid, historyid) {
			toastr['warning']('<a class="btn btn-danger btn-sm" href="<?php echo HTTP_PATH; ?>hr/ehistory/delete/'+empid+'/'+historyid+'">Delete History</a>', "Are you sure?");
		}

		function crewMovementConfirm(eid){
			bootbox.confirm(
	    	{
				size: "small",
			    title: "Confirm Transfer",
			    message: "Are you sure you want transfer this employee to his original assignement?",
			    buttons: {
			        confirm: {
			            label: 'Yes',
			            className: 'btn-success'
			        },
			        cancel: {
			            label: 'No',
			            className: 'btn-danger'
			        }
			    },
			    callback: function (result) {
			    	if(result == true){
			    		window.location.href = "<?=HTTP_PATH?>"+"hr/crew_movement/revert/"+eid;
			    	}
			    }
			});
		}
		
	</script>