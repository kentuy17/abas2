<style>#content{ margin-top:-20px; }</style>
<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-heading" style="border-bottom:#CCCCCC thin solid">
			<a href="<?php echo HTTP_PATH.'whr/employee_profile/add'; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="Add New Employee" style="cursor:pointer; float:right;">
				<img src="<?php echo LINK.'assets/images/button_icons/24X24/user.png' ?>" align="absmiddle" style="border:#FF0000 thick" />
			</a>
			<?php if($this->Abas->checkPermissions("encoding|departments",false)): ?>
			<a href="<?php echo HTTP_PATH."home/encode/departments"; ?>" class="" target="_new" title="Add Department" style="cursor:pointer; float:right; margin-right:10px">
				<img src="<?php echo LINK.'assets/images/button_icons/24X24/chart.png' ?>" align="absmiddle" />
			</a>
			<?php endif; ?>
			<?php if($this->Abas->checkPermissions("encoding|positions",false)): ?>
			<a href="<?php echo HTTP_PATH."home/encode/positions"; ?>" class="" target="_new" title="Add Position" style="cursor:pointer; float:right; margin-right:10px">
				<img src="<?php echo LINK.'assets/images/button_icons/24X24/chart_up.png' ?>" align="absmiddle" />
			</a>
			<?php endif; ?>


			<a href="<?php echo HTTP_PATH."hr"; ?>" class="" target="_new" title="Human Resources" style="cursor:pointer; float:right; margin-right:10px">
				<button><span class="glyphicon glyphicon-list-alt"></span> Human Resources</button>
			</a>

			<h4><strong><span style="background:#000099; color:#FFFFFF">HR</span><span style="background:#FF0000; color:#F4F4F4">iS</span></strong></h4>
		</div>
		<div class="panel-body">
			<table data-toggle="table" id="hr-table" class="table table-striped table-hover" data-url="<?php echo HTTP_PATH."whr/view_all_employees"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" style="font-size:12px">
				<thead>
					<tr>
						<th data-field="employee_id" data-align="center" data-sortable="true">Employee ID</th>
						<th data-field="full_name" data-align="left" data-sortable="true">Name</th>
						<th data-field="date_hired" data-visible="false" data-align="center" data-sortable="true">Date Hired</th>
						<th data-field="birth_date" data-visible="false" data-align="center" data-sortable="true">Birth Date</th>
						<th data-field="gender" data-visible="false" data-align="center" data-sortable="true">Gender</th>
						<th data-field="employee_status" data-align="left" data-sortable="true">Status</th>
						<th data-field="vessel_name" data-visible="false" data-align="left">Vessel/Office</th>
						<th data-field="region_name" data-sortable="true" data-align="left">Region</th>
						<th data-field="warehouse_name" data-align="left">Warehouse</th>
						<th data-field="company_name" data-align="left" data-sortable="true">Company</th>
						<th data-field="department_name" data-visible="false" data-align="left">Department</th>
						<th data-field="position_name" data-visible="false" data-align="left">Position</th>
						<th data-field="mobile" data-visible="false" data-align="left">Mobile Number</th>
						<th data-field="email" data-visible="false" data-align="left" data-sortable="true">Email</th>
						<th data-field="tax_code" data-visible="false" data-align="left">Tax Code</th>
						<th data-field="sss_num" data-visible="false" data-align="left">SSS Num</th>
						<th data-field="ph_num" data-visible="false" data-align="left">PhilHealth</th>
						<th data-field="pagibig_num" data-visible="false" data-align="left">Pag-Ibig</th>
						<th data-field="tin_num" data-visible="false" data-align="left">TIN</th>
						<th data-field="leave_credits" data-visible="false" data-align="left">Leaves</th>
						<?php if($this->Abas->checkPermissions("employee_profile|elf",false)): ?>
							<th data-field="elf_rate" data-visible="false" data-align="left" data-sortable="true">ELF Rate</th>
							<th data-field="total_elf_contribution" data-visible="false" data-align="left" data-sortable="true">ELF Contribution</th>
						<?php endif; ?>
						<?php if($this->Abas->checkPermissions("employee_profile|salary",false)): ?>
							<th data-field="bank_account_num" data-visible="false" data-align="left">Bank Account Num</th>
							<th data-field="salary_grade" data-visible="false" data-align="left" data-sortable="true">Salary Grade</th>
						<?php endif; ?>
						<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
					</tr>
				</thead>
			</table>
		</div>
		<div class="panel-footer success text-right" style="color:#000099"><strong>AVEGA<span style="color:#FF0000">iT</span>.2015</strong></div>
	</div>
</div>
 <!-- Modal HTML -->
<script>
	function operateFormatter(value, row, index) {
		id = row['id']; //alert(id);
		return [
            '<a class="like" href="<?php echo HTTP_PATH.'whr/employee_profile/view/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Profile">',
                '<i class="glyphicon glyphicon-list-alt"></i> View',
            '</a><br/>',
            '<a class="edit ml10" href="<?php echo HTTP_PATH.'whr/employee_profile/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit">',
                '<i class="glyphicon glyphicon-edit"></i> Edit',
            '</a> '
        ].join('');
    }
	window.operateEvents = {
        'click .like': function (e, value, row, index) {
            p = row["sid"];
			var wid = 940;
			var leg = 680;
			var left = (screen.width/2)-(wid/2);
            var top = (screen.height/2)-(leg/2);
            // window.open('studProfile.cfm?pid='+p,'popuppage','width='+wid+',toolbar=0,resizable=1,location=no,scrollbars=no,height='+leg+',top='+top+',left='+left);
        },
        'click .edit': function (e, value, row, index) {
			p = row["sid"];
			// addForm(p);
        }
    };
	$(function () {
        var $table = $('#hr-table');
        $table.bootstrapTable();
    });
</script>
