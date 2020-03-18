<h2>Employees</h2>
<a href="<?php echo HTTP_PATH.'hr/employee_profile/add'; ?>" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" class="btn btn-success"> Add</a>
<a href="<?php echo HTTP_PATH.'hr'; ?>" class="btn btn-dark force-pageload">Back</a>

<table data-toggle="table" id="hr-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."hr/view_all_employees"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
	<thead>
		<tr>
			<th data-field="employee_id" data-align="center" data-sortable="true" data-filter-control="input">Employee ID</th>
			<th data-field="full_name" data-align="left" data-sortable="true" data-filter-control="input">Fullname</th>
			<th data-field="vessel_name" data-align="left" data-sortable="true" data-filter-control="select">Vessel/Office</th>
			<th data-field="company_name" data-align="left" data-sortable="true" data-filter-control="select">Company</th>
			<th data-field="department_name" data-align="left" data-filter-control="select">Department</th>
			<th data-field="position_name" data-align="left" data-filter-control="select">Position</th>
			<th data-field="date_hired" data-visible="false" data-align="center" data-sortable="true" data-filter-control="input">Date Hired</th>
			<th data-field="date_separated" data-visible="false" data-align="center" data-sortable="true" data-filter-control="input">Date Separated</th>
			<th data-field="employment_status" data-align="left" data-sortable="true" data-filter-control="select">Employment Status</th>
			<th data-field="employee_status" data-align="left" data-sortable="true" data-filter-control="select">Employee Status</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Details</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		return [
            '<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'hr/employee_profile/view'; ?>/'+ row['id'] +'">View</a>'
        ].join('');
    }
	$(function () {
        var $table = $('#hr-table');
        $table.bootstrapTable();
    });
</script>
