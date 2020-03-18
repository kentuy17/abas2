<?php
$company	=	$this->Abas->getCompany($summary->company_id);
$creator	=	$this->Abas->getUser($summary->created_by);
$approver	=	$this->Abas->getUser($summary->approved_by);
$table		=	"";
$payroll	=	array(
					"salary"=>0,
					"allowance"=>0,
					"absences"=>0,
					"ut"=>0,
					"regularOT"=>0,
					"restdayOT" => 0,
					"legalholidayOT" => 0,
					"legalholiday_restdayOT" => 0,
					"specialholidayOT" => 0,
					"specialholiday_restdayOT" => 0,
					"holidayOT"=>0,
					"nightDiff"=>0,
					"bonus"=>0,
					"others"=>0,
					"gross"=>0,
					"wtax"=>0,
					"sss"=>0,
					"ph"=>0,
					"pi"=>0,
					"sssloan"=>0,
					"piloan"=>0,
					"elfloan"=>0,
					"ca"=>0,
					"elf"=>0,
					"netpay"=>0
				);
if(!empty($details)) {
	// $old_dept		=	0;
	$old_vessel		=	0;
	
	$vessel_total	=	array();
	foreach($details AS $display) {
		$employee_details	=	$this->Abas->getEmployee($display['emp_id']);
		$vessel	=	$employee_details['vessel_id'];
		if($vessel!=$old_vessel) {
			$vessel	=	$this->Abas->getVessel($employee_details['vessel_id']);
			// $vessel_total[$vessel->id]	=	(isset($vessel_total[$vessel->id])?$vessel_total[$vessel->id]:0)+($display['salary']+$display['allowance']+$display['holiday_overtime_amount']+$display['regular_overtime_amount']+$display['bonus']);
			$vessel_total[$vessel->id]	=array(
										"salary"=>0,
										"allowance"=>0,
										"absences"=>0,
										"ut"=>0,
										"regularOT"=>0,
										"restdayOT" => 0,
										"legalholidayOT" => 0,
										"legalholiday_restdayOT" => 0,
										"specialholidayOT" => 0,
										"specialholiday_restdayOT" => 0,
										"holidayOT"=>0,
										"nightDiff"=>0,
										"bonus"=>0,
										"others"=>0,
										"gross"=>0,
										"wtax"=>0,
										"sss"=>0,
										"ph"=>0,
										"pi"=>0,
										"sssloan"=>0,
										"piloan"=>0,
										"elfloan"=>0,
										"ca"=>0,
										"elf"=>0,
										"netpay"=>0
									);
		}
	}

	$total_employees =0;
	$old_vessel		=	0;
	$vessel_label = 1;
	$vessel_row="";
	foreach($details AS $ctr=>$display) {
		$employee_details	=	$this->Abas->getEmployee($display['emp_id']);
		$color ="";
		$row	=	"";
			if($vessel_label==1){
				$row	.='<tr>
					<th class="text-center value-column">EID</th>
					<th class="text-center name-column">Name</th>
					<th class="text-center name-column">Position</th>
					<th class="text-center value-column">Basic</th>
					<th class="text-center value-column">Allowance</th>
					<th class="text-center value-column">Absences</th>
					<th class="text-center value-column">Late/UT</th>
					<th class="text-center value-column">Regular OT</th>
					<th class="text-center value-column">Rest Day OT</th>
					<th class="text-center value-column">Legal Holiday OT</th>
					<th class="text-center value-column">Legal Holiday on Rest Day OT</th>
					<th class="text-center value-column">Special Holiday OT</th>
					<th class="text-center value-column">Special Holiday on Rest Day OT</th>
					<th class="text-center value-column">Total Holiday OT</th>
					<th class="text-center value-column">Night Differential</th>
					<th class="text-center value-column">Bonus</th>
					<th class="text-center value-column">Adjustments/ Others</th>
					<th class="text-center value-column">Gross</th>
					<th class="text-center value-column">W-Tax</th>';

					if($summary->payroll_coverage == "2nd-half"){
						$row	.='<th class="text-center value-column">SSS</th>';
						$row	.='<th class="text-center value-column">PHIC</th>';
					}elseif($summary->payroll_coverage == "1st-half"){
						$row	.='<th class="text-center value-column">HMDF</th>';
					}

					$row	.='<th class="text-center value-column">SSS Loan</th>
							<th class="text-center value-column">HMDF Loan</th>
							<th class="text-center value-column">ELF Loan</th>
							<th class="text-center value-column">Cash Advance</th>
							<th class="text-center value-column">ELF Contri</th>
							<th class="text-center value-column">Net Pay</th>';
					if($summary->locked==FALSE) {
						$row .='<th class="text-center value-column">Manage</th>';
					}
					$row .= '</tr>';	
					$vessel_label=0;
			}

			if($display['net_pay'] <= 1000) { $color="background-color:#FFFF55;"; }
			if($display['net_pay'] <= 0) { $color="background-color:#FF5555;"; }
			$display['emp_id']		=	!empty($employee_details['employee_id'])?$employee_details['employee_id']:"-";
			$display['full_name']	=	!empty($employee_details['full_name'])?$employee_details['full_name']:"-";
			$display['position']	=	!empty($employee_details['position_name'])?$employee_details['position_name']:"-";
			$holiday_overtime = $display['legalholiday_overtime_amount']+$display['legalholiday_restday_overtime_amount']+$display['specialholiday_overtime_amount']+$display['specialholiday_restday_overtime_amount'];
			if($holiday_overtime==0){
				$holiday_overtime  =  $display['holiday_overtime_amount'];
			}
			$subtotal=	$display['salary']+$display['allowance']+$display['regular_overtime_amount']+$display['restday_overtime_amount']+ $holiday_overtime + $display['night_differential_amount'] + $display['bonus'] + $display['others'] - ($display['undertime_amount']+$display['absences_amount']);
			$netpay	=	$subtotal - ($display['tax'] + $display['sss_contri_ee'] + $display['phil_health_contri'] + $display['pagibig_contri'] + $display['sss_loan'] + $display['pagibig_loan'] + $display['elf_loan'] + $display['cash_advance'] + $display['elf_contri']);
			// $dept	=	$employee_details['department'];
			$vessel	=	$this->Abas->getVessel($employee_details['vessel_id']);
			$taxcode=	$employee_details['tax_code'];
			$loans	=	$display['sss_loan'] + $display['pagibig_loan'] + $display['cash_advance'];
			if($employee_details['vessel_id']!=$old_vessel) {
				$employeectr	=	0;
				// $old_dept	=	$employee_details['department'];
				$old_vessel	=	$employee_details['vessel_id'];
				$table.=	"<tr><td colspan='99' style='background-color:#CCCCCC; text-align:left; text-decoration:bold;'><span style='float:left; margin:0px;'>".$vessel->name."</span></td></tr>";
			}
			// $row	.=	"<tr href=".HTTP_PATH.'payroll_history/payslips/employee/'.$display['id']." class='' data-toggle='modal' data-target='#modalDialog' title='Payslip' style='".$color."cursor:pointer; font-size:10px;'>";

			$elf_deductions			=	$display['elf_contri']+$display['elf_loan'];
			$loan_deductions		=	$display['sss_loan']+$display['pagibig_loan']+$display['cash_advance'];

			$payroll['salary']		=	$payroll['salary'] + $display['salary'];
			$payroll['allowance']	=	$payroll['allowance'] + $display['allowance'];
			$payroll['absences']	=	$payroll['absences'] + $display['absences_amount'];
			$payroll['ut']			=	$payroll['ut'] + $display['undertime_amount'];
			$payroll['regularOT']	=	$payroll['regularOT'] + $display['regular_overtime_amount'];
			$payroll['restdayOT']	=	$payroll['restdayOT'] + $display['restday_overtime_amount'];
			$payroll['legalholidayOT']	=	$payroll['legalholidayOT'] + $display['legalholiday_overtime_amount'];
			$payroll['legalholiday_restdayOT']	=	$payroll['legalholiday_restdayOT'] + $display['legalholiday_restday_overtime_amount'];
			$payroll['specialholidayOT']	=	$payroll['specialholidayOT'] + $display['specialholiday_overtime_amount'];
			$payroll['specialholiday_restdayOT']	=	$payroll['specialholiday_restdayOT'] + $display['specialholiday_restday_overtime_amount'];
			$payroll['holidayOT']	=	$payroll['holidayOT'] + $holiday_overtime;
			$payroll['nightDiff']	=	$payroll['nightDiff'] + $display['night_differential_amount'];
			$payroll['bonus']		=	$payroll['bonus'] + $display['bonus'];
			$payroll['others']		=	$payroll['others'] + $display['others'];
			$payroll['gross']		=	$payroll['gross'] + $subtotal;
			$payroll['wtax']		=	$payroll['wtax'] + $display['tax'];
			$payroll['sss']			=	$payroll['sss'] + $display['sss_contri_ee'];
			$payroll['ph']			=	$payroll['ph'] + $display['phil_health_contri'];
			$payroll['pi']			=	$payroll['pi'] + $display['pagibig_contri'];
			$payroll['sssloan']		=	$payroll['sssloan'] + $display['sss_loan'];
			$payroll['piloan']		=	$payroll['piloan'] + $display['pagibig_loan'];
			$payroll['elfloan']		=	$payroll['elfloan'] + $display['elf_loan'];
			$payroll['ca']			=	$payroll['ca'] + $display['cash_advance'];
			$payroll['elf']			=	$payroll['elf'] + $display['elf_contri'];
			$payroll['netpay']		=	$payroll['netpay'] + $netpay;

			$vessel_total[$vessel->id]['salary']	+=	$display['salary'];
			$vessel_total[$vessel->id]['allowance']	+=	$display['allowance'];
			$vessel_total[$vessel->id]['absences']	+=	$display['absences_amount'];
			$vessel_total[$vessel->id]['ut']		+=	$display['undertime_amount'];
			$vessel_total[$vessel->id]['regularOT']	+=	$display['regular_overtime_amount'];
			$vessel_total[$vessel->id]['restdayOT']	+=	$display['restday_overtime_amount'];
			$vessel_total[$vessel->id]['legalholidayOT']	+=	$display['legalholiday_overtime_amount'];
			$vessel_total[$vessel->id]['legalholiday_restdayOT']	+=	$display['legalholiday_restday_overtime_amount'];
			$vessel_total[$vessel->id]['specialholidayOT']	+=	$display['specialholiday_overtime_amount'];
			$vessel_total[$vessel->id]['specialholiday_restdayOT']	+=	$display['specialholiday_restday_overtime_amount'];
			$vessel_total[$vessel->id]['holidayOT']	+=	$holiday_overtime;
			$vessel_total[$vessel->id]['nightDiff']	+=	$display['night_differential_amount'];
			$vessel_total[$vessel->id]['bonus']		+=	$display['bonus'];
			$vessel_total[$vessel->id]['others']	+=	$display['others'];
			$vessel_total[$vessel->id]['gross']		+=	$subtotal;
			$vessel_total[$vessel->id]['wtax']		+=	$display['tax'];
			$vessel_total[$vessel->id]['sss']		+=	$display['sss_contri_ee'];
			$vessel_total[$vessel->id]['ph']		+=	$display['phil_health_contri'];
			$vessel_total[$vessel->id]['pi']		+=	$display['pagibig_contri'];
			$vessel_total[$vessel->id]['sssloan']	+=	$display['sss_loan'];
			$vessel_total[$vessel->id]['piloan']	+=	$display['pagibig_loan'];
			$vessel_total[$vessel->id]['elfloan']	+=	$display['elf_loan'];
			$vessel_total[$vessel->id]['ca']		+=	$display['cash_advance'];
			$vessel_total[$vessel->id]['elf']		+=	$display['elf_contri'];
			$vessel_total[$vessel->id]['netpay']	+=	$netpay;

			$row	.=	"<tr>";
			$row	.=	"<td class='c-align value-column'>".$display['emp_id']."</td>";
			$row	.=	"<td class='c-align name-column'>".$display['full_name']."</td>";
			$row	.=	"<td class='l-align name-column'>".ucwords(strtolower($display['position']))."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['salary'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['allowance'])."</td>";
			$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['absences_amount']).")</td>";
			$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['undertime_amount']).")</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['regular_overtime_amount'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['restday_overtime_amount'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['legalholiday_overtime_amount'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['legalholiday_restday_overtime_amount'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['specialholiday_overtime_amount'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['specialholiday_restday_overtime_amount'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($holiday_overtime)."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['night_differential_amount'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['bonus'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($display['others'])."</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($subtotal)."</td>"; // gross pay
			$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['tax']).")</td>";
			if($summary->payroll_coverage == "2nd-half") {
				$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['sss_contri_ee']).")</td>";
				$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['phil_health_contri']).")</td>";
			}
			if($summary->payroll_coverage == "1st-half") {
				$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['pagibig_contri']).")</td>";
			}
			$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['sss_loan']).")</td>";
			$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['pagibig_loan']).")</td>";
			$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['elf_loan']).")</td>";
			$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['cash_advance']).")</td>";
			$row	.=	"<td class='value-column'>(".$this->Abas->currencyFormat($display['elf_contri']).")</td>";
			$row	.=	"<td class='value-column'>".$this->Abas->currencyFormat($netpay)."</td>";

			if($summary->locked==FALSE) {
				$row	.=	"<td class='value-column'><a class='btn btn-xs btn-block btn-warning' href='".HTTP_PATH."payroll_history/edit/".$display['id']."' data-toggle='modal' data-target='#modalDialog'>Edit</a></td>";
			}

			$row	.=	"</tr>";
			$employeectr++;

			if(isset($details[$ctr+1])) {
				$next_emp		=	$this->Abas->getEmployee($details[$ctr+1]['emp_id']);
				$next_vessel_id	=	$next_emp['vessel_id'];
				$next_vessel	=	$this->Abas->getVessel($next_vessel_id);
				if($next_vessel->id!=$old_vessel) { // next vessel
					$row	.=	"<tr style='font-size:10px;' align='right'>";
						$row	.=	"<td>No. of Employees: ".$employeectr."</td>";
						$row	.=	"<td colspan='2'>Sub-total:</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['salary'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['allowance'])."</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['absences']).")</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['ut']).")</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['regularOT'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['restdayOT'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['legalholidayOT'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['legalholiday_restdayOT'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['specialholidayOT'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['specialholiday_restdayOT'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['holidayOT'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['nightDiff'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['bonus'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['others'])."</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['gross'])."</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['wtax']).")</td>";
						if($summary->payroll_coverage == "2nd-half") {
							$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['sss']).")</td>";
							$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['ph']).")</td>";
						}
						if($summary->payroll_coverage == "1st-half") {
							$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['pi'])."</td>";
						}
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['sssloan']).")</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['piloan']).")</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['elfloan']).")</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['ca']).")</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['elf']).")</td>";
						$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['netpay'])."</td>";
					$row	.=	"</tr>";

					$vessel_label=1;
					$total_employees = $total_employees + $employeectr;
				}
			}
			else { // last vessel
				$row	.=	"<tr style='font-size:10px;' align='right'>";
					$row	.=	"<td>No. of Employees: ".$employeectr."</td>";
					$row	.=	"<td colspan='2'>Sub-total:</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['salary'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['allowance'])."</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['absences']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['ut']).")</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['regularOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['restdayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['legalholidayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['legalholiday_restdayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['specialholidayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['specialholiday_restdayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['holidayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['nightDiff'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['bonus'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['others'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['gross'])."</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['wtax']).")</td>";
					if($summary->payroll_coverage == "2nd-half") {
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['sss']).")</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['ph']).")</td>";
					}
					if($summary->payroll_coverage == "1st-half") {
						$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['pi']).")</td>";
					}
					$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['sssloan']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['piloan']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['elfloan']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['ca']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($vessel_total[$vessel->id]['elf']).")</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($vessel_total[$vessel->id]['netpay'])."</td>";
				$row	.=	"</tr>";

				$total_employees = $total_employees + $employeectr;

				$row	.=	"<tr style='background:#333333; color:#FFFFFF; font-size:11px;' align='right'>";
					$row	.=	"<td>Total Employees: ".$total_employees."</td>";
					$row	.=	"<td colspan='2'>Grand Total:</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['salary'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['allowance'])."</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['absences']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['ut']).")</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['regularOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['restdayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['legalholidayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['legalholiday_restdayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['specialholidayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['specialholiday_restdayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['holidayOT'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['nightDiff'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['bonus'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['others'])."</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['gross'])."</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['wtax']).")</td>";
					if($summary->payroll_coverage == "2nd-half") {
						$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['sss']).")</td>";
						$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['ph']).")</td>";
					}
					if($summary->payroll_coverage == "1st-half") {
						$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['pi']).")</td>";
					}
					$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['sssloan']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['piloan']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['elfloan']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['ca']).")</td>";
					$row	.=	"<td>(".$this->Abas->currencyFormat($payroll['elf']).")</td>";
					$row	.=	"<td>".$this->Abas->currencyFormat($payroll['netpay'])."</td>";
				$row	.=	"</tr>";
			}
			$table	.=	$row;
		// }
	}
}
else {
	$row	=	"<tr><td colspan='99'>No details found!</td></tr>";
}
?>


<style>
	.l-align { text-align:left; }
	.r-align { text-align:right; }
	.c-align { text-align:center; }
	#content{ margin-top:-20px}
    .demo-content{
        padding: 15px;
        font-size: 18px;
        background: #dbdfe5;
        margin-bottom:0px;
    }
    .demo-content.bg-alt{
        background: #abb1b8;
    }
	#heading{ min-height: 50px;}
	table tbody tr td {
		text-align:right;
	}
	.name-column {
		width:7.5%;
	}
	.value-column {
		width:4.72%;
	}

</style>

<h2>Payroll Summary</h2>

<?php 
	$status ='For Approval';
	if($summary->locked==true){ $status ='Approved'; ?>
		<?php if($this->Abas->checkPermissions("payroll|view",false)): ?>
			<a class="exclude-pageload" href="<?php echo HTTP_PATH."payroll_history/summary_printable/".$summary->id; ?>" target="_blank"><button  type="button" class="btn btn-info btn-m"> Print</button></a>
			<div class="btn-group" style="margin-top: -5px">
				  <button type="button" class="btn btn-success">Reports</a></button>
			      <button type="button" class="btn btn-success dropdown-toggle exclude-pageload" data-toggle="dropdown" aria-expanded="false">
			        <span class="caret"></span>
			        <span class="sr-only">Toggle Dropdown</span>
			      </button>
			      <ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo HTTP_PATH."payroll_history/payslip_printable/".$summary->id; ?>" target="_blank" class="exclude-pageload"  title="Payslips" style="cursor:pointer;"> Payslips</a></li>
						<li><a href="<?php echo HTTP_PATH."payroll_history/bir_printable/".$summary->id; ?>" target="_blank" class="exclude-pageload" style="cursor:pointer;"> BIR W-Tax Summary</a></li>
						<li><a href="<?php echo HTTP_PATH."payroll_history/bank_printable/".$summary->id; ?>" target="_blank" class="exclude-pageload" style="cursor:pointer;"> Bank Remitance</a></li>
						<li><a href="<?php echo HTTP_PATH."payroll_history/sss_printable/".$summary->id; ?>" target="_blank" class="exclude-pageload" style="cursor:pointer;"> SSS Summary</a></li>
						<li><a href="<?php echo HTTP_PATH."payroll_history/ph_printable/".$summary->id; ?>" target="_blank" class="exclude-pageload" style="cursor:pointer;"> PhilHealth Summary</a></li>
						<li><a href="<?php echo HTTP_PATH."payroll_history/pi_printable/".$summary->id; ?>" target="_blank" class="exclude-pageload" style="cursor:pointer;"> Pag-ibig Summary</a></li>
						<li><a href="<?php echo HTTP_PATH."payroll_history/rides_printable/".$summary->id; ?>" target="_blank" class="exclude-pageload" style="cursor:pointer;"> Rides Summary</a></li>
			      </ul>
			        &nbsp
			</div>
		<?php endif ?>
<?php }else{ ?>
	<?php if($this->Abas->checkPermissions("payroll|approve",false)): ?>
		<a class="btn btn-success exclude-pageload" onclick='javascript:confirmApprove(<?php echo $summary->id?>)'>Approve</a>
	<?php endif ?>
	<?php if($this->Abas->checkPermissions("payroll|add",false)): ?>
		<a href='<?php echo HTTP_PATH; ?>payroll_history/add' data-toggle='modal' data-target='#modalDialogNorm' data-backdrop="static"><button  type="button" class="btn btn-warning btn-m"> Add Employee to Payroll</button></a>
	<?php endif ?>
<?php }?>	
<a href="<?php echo HTTP_PATH?>payroll/" class="btn btn-dark force-pageload">Back</a>

<div class = "panel panel-primary">
	<div class = "panel-heading">
		<h3 class = "panel-title">
			<span style="float:left">
				Transaction Code No. <?php echo $summary->id; ?> | Control No. <?php echo $summary->control_number; ?>
			</span>
			<span class="pull-right">Status: <?php echo $status?></span>
		</h3>
		<br>
	</div>
	<div class = "panel-body">
		<h3 class="text-center"><?php echo $company->name; ?></h3>
		<h4 class="text-center"><?php echo $company->address; ?></h3>
		<h4 class="text-center">(Pay Period: <?php echo $summary->payroll_coverage." - ".date("F Y",strtotime($summary->payroll_date."-01")); ?>)</h4>
		<div class="table-responsive">
			<table class="table table-condensed table-bordered table-hover table-condensed table-striped" style="font-size:11px">
				<tbody>
					<?php
					echo $table;
					?>
				</tbody>
			 </table>
			 <p>Created on <?php echo date("h:i:s a j F Y", strtotime($summary->created_on)); ?> by <?php echo $creator['full_name']; ?></p>
			 <?php if($summary->locked==true): ?>
			 	<p>Approved on <?php echo date("h:i:s a j F Y", strtotime($summary->approved_on)); ?> by <?php echo $approver['full_name']; ?></p>
			 <?php endif ?>
		</div>
	</div>
</div>
<script>
	jQuery(function($) {
		$(document).ready( function() {
			$('.payroll-table-head').stickUp();
		});
	});

	$( "#printMe" ).on( "click", function() { //the print button has the class .print-now
		event.preventDefault(); // prevent normal button action
	   $('.btn-lg').removeClass('btn-lg'); // remove the form-control class
		window.print(); // print the page
		$('button').addClass('btn-lg'); // return the class after printing
	});
	function printReport(site){

		var site = '';
		var wid = 1020;
		var leg = 540;
		var left = (screen.width/2)-(wid/2);
		var top = (screen.height/2)-(leg/2);
		window.open(site,'popuppage','width='+wid+',toolbar=0,resizable=1,location=no,scrollbars=no,height='+leg+',top='+top+',left='+left);

	}

	function confirmApprove(id){

	bootbox.confirm({
   					size: "small",
   					title: "Payroll Summary",
				    message: "Are you sure you want to approve this Payroll?",
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
				    	if(result){
				    		window.location.href = "<?php echo HTTP_PATH; ?>payroll_history/approve/"+id;
				    	}
				    }
				});
	}
</script>
