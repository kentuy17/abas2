<style type='text/css'>
	 h1 { font-size:270%;text-align:center; }
	 h2 { font-size:210%;text-align:left; }	
	 h3 { font-size:150%;text-align:left; }
	 h5 { border-bottom: double 3px; }
	 td {font-size:130%;text-align:center}
	 th { font-weight:bold;font-size:150%;text-align:center}
</style>
<h2>Alphalist Report</h2>
<?php
echo "<h3>Company: ".$company['details']->name."<br>Year: ".$year."</h3>";
?>
<div style='overflow-x: auto'>
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th rowspan='2'>#</th>
				<th rowspan='2'>Vessel/Office</th>
				<th rowspan='2'>Employee Name</th>
				<th rowspan='2'>TIN</th>
				<th rowspan='2'>Date of Birth</th>
				<th rowspan='2'>Address</th>
				<th rowspan='2'>Date Hired</th>
				<th rowspan='2'>Date Separated</th>
				<th rowspan='2'>Active or Inactive?</th>
				<th colspan='7'>Total Amounts (Jan to Dec)</th>
				<th rowspan='2'>13th Month</th>
				<th rowspan='2'>Remaining Leaves</th>
				<th rowspan='2'>Leave Conversion</th>
				<th rowspan='2'>MW or NMW (P386)</th>
				<th rowspan='2'>Employment Status</th>
			</tr>
			<tr>
				<th>Basic Salary</th>
				<th>SSS (Emp Share)</th>
				<th>HDMF (Emp Share)</th>
				<th>PH (Emp Share)</th>
				<th>Tax With-held</th>
				<th>Absences</th>
				<th>Undertime &<br>Tardiness</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(count($employee['details'])>0){
				for($ctr=0;$ctr<count($employee['details']);$ctr++){
					$emp = $employee['details'][$ctr];

					$total_basic_salary = 0;
					$total_sss_contri = 0;
					$total_hdmf_contri = 0;
					$total_ph_contri = 0;
					$total_wtax = 0;
					$thirteenth_month = 0;
					$leave_credits = 0;
					$total_absences = 0;
					$total_undertime = 0;
					if($emp['employee_status']=='Resigned' || $emp['employee_status']=='Retired' || $emp['employee_status']=='Terminated' || $emp['employee_status']=='Separated'){
						$status = 'Inactive';
						$employment_history = $this->Hr_model->getLatestEmploymentStatus($emp['id']);
						if($employment_history->to_val=='Resigned' || $employment_history->to_val=='Retired' || $employment_history->to_val=='Terminated' || $employment_history->to_val=='Separated'){
							$separation_date = date('F j, Y',strtotime($employment_history->effectivity_date));
						}else{
							$separation_date = '--';
						}
					}else{
						$status = 'Active';
						$separation_date = '--';
					}
					echo "<tr>";
						echo "<td>".($ctr+1)."</td>";
						echo "<td>".$emp['vessel_name']."</td>";
						echo "<td>".$emp['last_name'].", ".$emp['first_name']." ".$emp['middle_name']."</td>";
						echo "<td>".$emp['tin_num']."</td>";
						echo "<td>".date('F j, Y',strtotime($emp['birth_date']))."</td>";
						echo "<td>".$emp['address']."</td>";
						$date_hired = $emp['date_hired'];
						echo "<td>".date('F j, Y',strtotime($date_hired))."</td>";
						echo "<td>".$separation_date."</td>";
						echo "<td>".$status."</td>";

						foreach($employee['payroll'][$ctr] as $pay){
							$total_basic_salary = $total_basic_salary + $pay->salary;
							$total_sss_contri = $total_sss_contri + $pay->sss_contri_ee;
							$total_hdmf_contri = $total_hdmf_contri + $pay->pagibig_contri;
							$total_ph_contri = $total_ph_contri + $pay->phil_health_contri;
							$total_wtax= $total_wtax + $pay->tax;
							$leave_credits = $pay->leave_credits;
							$total_absences = $total_absences + $pay->absences_amount;
							$total_undertime = $total_undertime+ $pay->undertime_amount;
						}
						$thirteenth_month = ($total_basic_salary-($total_absences + $total_undertime))/12;

						if($emp['vessel_id']>=99994){//land based
							$daily_wage = ($emp['salary_rate']/26.08);
						}else{//sea based
							$daily_wage = ($emp['salary_rate']/30);
						}
						if($leave_credits>0){
							$leave_conversion = $daily_wage * $leave_credits;
						}else{
							$leave_conversion = 0;
							$leave_credits = 0;
						}

						if($emp['salary_rate']<=14000){
							$P386 = "MW";
						}else{
							$P386 = "NMW";
						}

						echo "<td>".number_format($total_basic_salary,2,'.',',')."</td>";
						echo "<td>".number_format($total_sss_contri,2,'.',',')."</td>";
						echo "<td>".number_format($total_hdmf_contri,2,'.',',')."</td>";
						echo "<td>".number_format($total_ph_contri,2,'.',',')."</td>";
						echo "<td>".number_format($total_wtax,2,'.',',')."</td>";
						echo "<td>".number_format($total_absences,2,'.',',')."</td>";
						echo "<td>".number_format($total_undertime,2,'.',',')."</td>";

						echo "<td>".number_format($thirteenth_month,2,'.',',')."</td>";
						echo "<td>".$leave_credits."</td>";
						echo "<td>".number_format($leave_conversion,2,'.',',')."</td>";
						echo "<td>".$P386."</td>";

						if($status=='Inactive'){
							$employment_status = "Separated";
						}else{
							$hired_year = date('Y',strtotime($date_hired));
							if($hired_year < $year){
								$employment_status = "Employee worked the whole year";
							}else{
								$employment_status = "With Prev. Employer";
							}
						}
						echo "<td>".$employment_status."</td>";

					echo "</tr>";
				}
			}else{
				echo "<tr><td colspan='21'>No record found</td></tr>";
			}
			?>
		</tbody>
	</table>
</div>