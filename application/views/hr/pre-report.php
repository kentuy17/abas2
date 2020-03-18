<?php
$structure	=	$this->Abas->getEmployee(43); // we just need the structure, disregard data
$boxes		=	"";
$disallow	=	array("id", "employee_id", "full_name", "first_name", "middle_name", "last_name", "telephone", "address2", "country", "position", "department", "vessel_id", "company_id", "stat", "elf_id", "input_by", "input_on", "modified_by", "modified_on", "salary_grade_id", "gender");
foreach($structure as $s=>$d) {
	if(!in_array($s, $disallow)) {
		$boxes	.=	'
		<p>
			<label for="'.$s.'">'.ucwords(str_replace("_"," ",$s)).'</label>
			<input type="checkbox" id="'.$s.'" name="'.$s.'" />
		</p>
		';
	}
}
?>

<style>#content{ margin-top:-20px; }</style>
<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="<?php echo HTTP_PATH. __class__ .'/employee_profile/add'; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="Add New Employee" style="cursor:pointer; float:right;">
				<img src="<?php echo HTTP_PATH.'assets/images/button_icons/24X24/user.png' ?>" align="absmiddle" style="border:#FF0000 thick" />
			</a>
			<a href="<?php echo HTTP_PATH."home/encode/departments"; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="Add Department" style="cursor:pointer; float:right; margin-right:10px">
				<img src="<?php echo HTTP_PATH.'assets/images/button_icons/24X24/chart.png' ?>" align="absmiddle" />
			</a>
			<a href="<?php echo HTTP_PATH."home/encode/positions"; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="Add Position" style="cursor:pointer; float:right; margin-right:10px">
				<img src="<?php echo HTTP_PATH.'assets/images/button_icons/24X24/chart_up.png' ?>" align="absmiddle" />
			</a>
			<a href="<?php echo HTTP_PATH."home/encode/positions"; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="Set Salary Grade" style="cursor:pointer; float:right; margin-right:10px">
				<img src="<?php echo HTTP_PATH.'assets/images/button_icons/24X24/calculator.png' ?>" align="absmiddle" />
			</a>
			<h4><strong><span style="background:#000099; color:#FFFFFF">HR</span><span style="background:#FF0000; color:#F4F4F4">iS</span></strong></h4>
		</div>
		<div class="panel-body">
			<form action="<?php echo HTTP_PATH; ?>printable/hr" method="POST">
				<p>Choose data to be included in the report</p>
				<div>
					<p>
						<label for="sortby">Sort By</label>
						<select id="sortby" name="sortby" />
							<option value="">Choose One</option>
							<option SELECTED=SELECTED value="dept">Department</option>
							<option value="company">Company</option>
							<option value="vessel">Vessel</option>
						</select>
					</p>
					<p>
						<label for="employee_id">Employee ID</label>
						<input type="checkbox" checked="checked" id="employee_id" name="employee_id" />
					</p>
					<p>
						<label for="full_name">Full Name</label>
						<input type="checkbox" checked="checked" id="full_name" name="full_name" />
					</p>
					<p>
						<label for="gender">Gender</label>
						<input type="checkbox" id="gender" name="gender" />
					</p>
					<?php echo $boxes; ?>
				</div>
				<div>
					<input type="submit" value="Generate Report" class="btn btn-primary btn-sm" />
				</div>
			</form>
		</div>
		<div class="panel-footer success text-right" style="color:#000099"><strong>AVEGA<span style="color:#FF0000">iT</span>.2015</strong></div>
	</div>
</div>
