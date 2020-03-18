<h2>Crew Users</h2>

<table data-toggle="table" id="public_users-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."hr/public_users/json"; ?>" data-cache="false" data-side-pagination="server" data-sort-name="created_on" data-sort-order="desc" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th data-field="id" data-visible="false" data-align="center" data-sortable="true">ID</th>
			<th data-field="first_name" data-visible="true" data-align="center" data-sortable="true">First Name</th>
			<th data-field="last_name" data-visible="true" data-align="center" data-sortable="true">Last Name</th>
			<th data-field="middle_name" data-visible="true" data-align="center" data-sortable="true">Middle Name</th>
			<th data-field="birth_date" data-visible="true" data-align="center" data-sortable="true">Birth Date</th>
			<th data-field="email" data-visible="true" data-align="center" data-sortable="true">Email</th>
			<th data-field="employee_id" data-visible="false" data-align="center" data-sortable="true">Employee ID</th>
			<th data-field="validated_on" data-visible="false" data-align="center" data-sortable="true">Validated On</th>
			<th data-field="validated_by" data-visible="true" data-align="center" data-sortable="true">Validated by</th>
			<th data-field="created_on" data-visible="false" data-align="center" data-sortable="true">Created on</th>
			<th data-field="password_reset" data-visible="false" data-align="center" data-sortable="true">Password Reset Code</th>
			<th data-field="confirmation_code" data-visible="false" data-align="center" data-sortable="true">Confirmation Code</th>
			<th data-field="confirmed_on" data-visible="false" data-align="center" data-sortable="true">Confirmed on</th>
			<th data-field="operate" data-formatter="operateFormatter" data-halign="center" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>

<script>
	function operateFormatter(value, row, index) {
		if(row['employee_id']!=0) {
			return [
			'<div class="dropdown">',
				'<button class="btn btn-default dropdown-toggle" type="button" id="actionmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>',
				'<ul class="dropdown-menu" aria-labelledby="actionmenu">',
				'<li><a href="<?php echo HTTP_PATH.'hr/employee_profile/view/'; ?>'+row['best_guess']+'" data-toggle="modal" data-target="#modalDialog" title="Profile">View</a></li> ',
				'<li><a href="<?php echo HTTP_PATH.'hr/public_users/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit Password">Edit Password</a></li> ',
				'<li><a href="<?php echo HTTP_PATH.'hr/public_users/permissions/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Permissions">Permissions</a></li> ',
				'<li><a onclick="javascript: confirmResetPassword('+row['id']+');" title="Reset Password">Password Reset</a></li>',
					'<li><a onclick="javascript: confirmDeactivate('+row['id']+');" title="Deactivate Account">Deactivate</a></li>',
				'</ul>',
			'</div>'
			].join('');
		}
		else {
			return [
				'<a class="btn btn-default btn-xs btn-block" href="<?php echo HTTP_PATH.'hr/public_users/validate/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="validate">Search and Validate</a> '
			].join('');
		}
	}
	function confirmResetPassword(userid) {
		toastr.clear();
		toastr['warning']('<a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>hr/public_users/reset_password/'+userid+'">Reset Password</a>', "Are you sure?");
	}
	function confirmDeactivate(userid) {
		toastr.clear();
		toastr['warning']('<a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>hr/public_users/deactivate/'+userid+'">Deactivate Account and Revoke Permissions</a>', "Are you sure?");
	}

	$(function () {
		var $table = $('#public_users-table');
		$table.bootstrapTable();
	});
</script>
