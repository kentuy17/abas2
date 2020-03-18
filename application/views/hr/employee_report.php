<?php
echo "<h2>Employees Summary Report</h2>";
$calculate_months_worked	=	true;
$employeetable	=	"";
$hired_date = "";
$separation_date = "";
$year_now = date('Y');
if(!empty($employees)) {
	foreach($employees as $ctr=>$employee) {
		$e	=	$this->Abas->getEmployee($employee['id']);
		$regularization_date	=	"0000-00-00 00:00:00";
		unset($employees[$ctr]);

		if($e['employee_status']<>'Resigned' && $e['employee_status']<>'Retired' && $e['employee_status']<>'Terminated' && $e['employee_status']<>'Separated'){
			$employee_status = "Active";
		}else{
			$employee_status = "Inactive";
		}

		$bd1 = new DateTime();
		$bd2 = new DateTime($e['birth_date']);
		$age = $bd1->diff($bd2);

		$d1 = new DateTime($e['date_hired']);
		$d2 = new DateTime($e['date_separated']);
		$diff = $d1->diff($d2);

		$years_difference	=	$diff->y." year(s) and ";
		$months_difference	=	$diff->m." month(s) and ";
		$days_difference	=	$diff->d." day(s)";

		$months_worked_row	=	"<td>".$years_difference.$months_difference.$days_difference."</td>";

		$employeetable	.=	"<tr>";
			$employeetable	.=	"<td>".$e['last_name']."</td>";
			$employeetable	.=	"<td>".$e['first_name']."</td>";
			$employeetable	.=	"<td>".$e['middle_name']."</td>";
			$employeetable	.=	"<td>".$e['company_name']."</td>";
			$employeetable	.=	"<td>".$e['position_name']."</td>";
			$employeetable	.=	"<td>".$e['employee_status']."</td>";
			$employeetable	.=	"<td>".$employee_status."</td>";
			$employeetable	.=	"<td>".$e['mobile']."</td>";
			$employeetable	.=	"<td>".$e['email']."</td>";
			$employeetable	.=	"<td>".$e['gender']."</td>";
			$employeetable	.=	"<td>".($e['birth_date']!="0000-00-00 00:00:00" && $e['birth_date']!="1970-01-01 00:00:00" ? date("j F Y", strtotime($e['birth_date'])) : "")."</td>";
			$employeetable	.=  "<td>".$age->y."</td>";
			$employeetable	.=	"<td>".$e['address']."</td>";
			$employeetable	.=	"<td>".$e['city']."</td>";
			$employeetable	.=	"<td>".$e['civil_status']."</td>";
			$employeetable	.=	"<td>".$e['department_group']."</td>";
			$employeetable	.=	"<td>".$e['division_name']."</td>";
			$employeetable	.=	"<td>".$e['department_name']."</td>";
			$employeetable	.=	"<td>".$e['section_name']."</td>";
			$employeetable	.=	"<td>".$e['sub_section_name']."</td>";
			$employeetable	.=	"<td>".$e['vessel_name']."</td>";
			$employeetable	.=	"<td>".$e['bank_account_num']."</td>";
			$employeetable	.=	"<td>".$e['tin_num']."</td>";
			$employeetable	.=	"<td>".$e['sss_num']."</td>";
			$employeetable	.=	"<td>".$e['ph_num']."</td>";
			$employeetable	.=	"<td>".$e['pagibig_num']."</td>";
			$employeetable	.=	"<td>".$e['tax_code']."</td>";
			$employeetable	.=	"<td>".($e['date_hired']!="0000-00-00 00:00:00" && $e['date_hired']!="1970-01-01 00:00:00" ? date("j F Y", strtotime($e['date_hired'])) : "--")."</td>";
			$employeetable	.=	"<td>".($separation_date!="0000-00-00 00:00:00" && $separation_date!="" ? date("j F Y", strtotime($separation_date)) : "--")."</td>";
			$employeetable	.=	"<td>".($e['date_regularized']!="0000-00-00 00:00:00" && $e['date_regularized']!=""? date("j F Y", strtotime($e['date_regularized'])) : "--")."</td>";
			if(($this->Abas->checkPermissions("human_resources|salary_viewing", false))) {
				$employeetable	.=	"<td>".$e['salary_grade']."</td>";
				$employeetable	.=	"<td>".$e['salary_rate']."</td>";
			}
			$employeetable	.=	$months_worked_row;

			$l = $this->Hr_model->getEmployeeLeaves($e['id'],$year_now);
			$ul = $this->Hr_model->getUnusedLeaveCredits($e['id'],$year_now-1);

			$employeetable	.=	"<td>".$e['leave_credits']." day(s)</td>";
			$employeetable	.=	"<td>".($e['leave_credits'] - $l['number_of_filed'])." day(s)</td>";
			$employeetable	.=	"<td>".$ul." day(s)</td>";
			$employeetable	.=	"<td>".$e['emergency_contact_person']."</td>";
			$employeetable	.=	"<td>".$e['emergency_contact_num']."</td>";
			$employeetable	.=	"<td><a class='btn btn-info btn-xs btn-block' href='".HTTP_PATH."hr/employee_profile/view/".$e['id']."'>View</a></td>";
		$employeetable	.=	"</tr>";
	}
}
?>

<table data-toggle="table" id="hr-table" class="table table-bordered table-striped table-hover" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
	<thead>
		<tr>
			<th data-align="center" data-visible="true" data-sortable="true">Lastname</th>
			<th data-align="center" data-visible="true" data-sortable="true">Firstname</th>
			<th data-align="center" data-visible="true" data-sortable="true">Middlename</th>
			<th data-align="center" data-visible="true" data-sortable="true">Company</th>
			<th data-align="center" data-visible="true" data-sortable="true">Position</th>
			<th data-align="center" data-visible="true" data-sortable="true">Employement Status</th>
			<th data-align="center" data-visible="true" data-sortable="true">Employee Status</th>
			<th data-align="center" data-visible="true" data-sortable="false">Mobile No.</th>
			<th data-align="center" data-visible="true" data-sortable="true">Email</th>
			<th data-align="center" data-visible="true" data-sortable="true">Gender</th>
			<th data-align="center" data-visible="true" data-sortable="true">Birthdate</th>
			<th data-align="center" data-visible="true" data-sortable="true">Age</th>
			<th data-align="center" data-visible="true" data-sortable="true">Address</th>
			<th data-align="center" data-visible="true" data-sortable="true">City/Province</th>
			<th data-align="center" data-visible="true" data-sortable="true">Civil Status</th>
			<th data-align="center" data-visible="true" data-sortable="true">Group</th>
			<th data-align="center" data-visible="true" data-sortable="true">Division</th>
			<th data-align="center" data-visible="true" data-sortable="true">Department</th>
			<th data-align="center" data-visible="true" data-sortable="true">Section</th>
			<th data-align="center" data-visible="true" data-sortable="true">Sub-section</th>
			<th data-align="center" data-visible="true" data-sortable="true">Vessel/Office</th>
			<th data-align="center" data-visible="true" data-sortable="false">ATM/Bank Account No.</th>
			<th data-align="center" data-visible="true" data-sortable="false">TIN</th>
			<th data-align="center" data-visible="true" data-sortable="false">SSS</th>
			<th data-align="center" data-visible="true" data-sortable="false">PhilHealth</th>
			<th data-align="center" data-visible="true" data-sortable="false">PagIbig</th>
			<th data-align="center" data-visible="true" data-sortable="true">Tax Code</th>
			<th data-align="center" data-visible="true" data-sortable="true">Date Hired</th>
			<th data-align="center" data-visible="true" data-sortable="true">Date Separated</th>
			<th data-align="center" data-visible="true" data-sortable="true">Date Regularized</th>
			<?php if($this->Abas->checkPermissions("human_resources|salary_viewing", false)): ?>
				<th data-align="center" data-visible="true" data-sortable="true">Salary Grade</th>
				<th data-align="center" data-visible="true" data-sortable="true">Salary Rate</th>
			<?php endif; ?>
			<th data-align="center" data-visible="true" data-sortable="true">Time in company</th>
			<th data-align="center" data-visible="true" data-sortable="true">Annual Leave Credits</th>
			<th data-align="center" data-visible="true" data-sortable="true">Remaining Leave Credits for <?php echo $year_now;?></th>
			<th data-align="center" data-visible="true" data-sortable="true">Unused Leave Credits from <?php echo $year_now-1;?></th>
			<th data-align="center" data-visible="true" data-sortable="true">Emergency Contact Person</th>
			<th data-align="center" data-visible="true" data-sortable="true">Emergency Contact No.</th>
			<th>Manage</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $employeetable; ?>
	</tbody>
</table>