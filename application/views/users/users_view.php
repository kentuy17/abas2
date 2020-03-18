<h2>User Accounts</h2>
<a href="<?php echo HTTP_PATH.'users/add'; ?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialog" title="Add New User">Add 
</a>
<a href="<?php echo HTTP_PATH.'users'; ?>" class="btn btn-dark force-pageload">Refresh</a>
<div>
<table data-toggle="table" id="users-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."users/load"; ?>" data-cache="false" data-side-pagination="server"  data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="id" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
	<tr>
		<th data-field="id" data-align="center" data-sortable="true" data-visible="true">User ID</th>
		<th data-field="username" data-align="center" data-sortable="true" data-visible="true">Username</th>
		<th data-field="first_name" data-align="center" data-sortable="true" data-visible="true">First Name</th>
		<th data-field="middle_name" data-align="center" data-sortable="true" data-visible="true">Middle Name</th>
		<th data-field="last_name" data-align="center" data-sortable="true" data-visible="true">Last Name</th>
		<th data-field="email" data-align="left" data-sortable="true" data-visible="false">Email</th>
		<th data-field="role" data-align="left" data-sortable="true" data-visible="true">Role</th>
		<th data-field="user_location" data-align="left" data-sortable="true" data-visible="true">Location</th>
		<th data-field="stat" data-align="left" data-sortable="true" data-visible="true">Status</th>
		<th data-field="signature" data-align="left" data-sortable="true" data-visible="false">Signature File</th>
		<th data-field="operate" data-formatter="manageUsers" data-align="center" data-align="center" >Manage</th>
	</tr>
	</thead>
</table>
</div>
<script>
	function manageUsers(value, row, index) {
		return [
			'<div class="dropdown">',
				'<button class="btn btn-warning btn-xs dropdown-toggle" type="button" id="actionmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Edit <span class="caret"></span></button>',
				'<ul class="dropdown-menu" aria-labelledby="actionmenu">',
					'<li><a href="<?php echo HTTP_PATH.'users/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit Details">Details</a></li>',
					'<li><a href="<?php echo HTTP_PATH.'users/permissions/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit Permissions">Permissions</a></li>',
					'<li><a onclick="javascript: confirmResetPassword('+row['id']+');" title="Reset Password">Password Reset</a></li>',
					'<li><a onclick="javascript: confirmActivate('+row['id']+');" title="Activate Account">Activate</a></li>',
					'<li><a onclick="javascript: confirmDeactivate('+row['id']+');" title="Deactivate Account">Deactivate</a></li>',
				'</ul>',
			'</div>'
		].join('');
	}
	
	 function confirmResetPassword(id){
    	bootbox.confirm({
       					size: "small",
					    title: "Reset Password",
					    message: "Are you sure you want to reset this user's password?",
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
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH; ?>users/reset_password/" + id;
					    	}
				
					    }
					});
    }

	function confirmActivate(id){
    	bootbox.confirm({
       					size: "small",
					    title: "Activate Account",
					    message: "Are you sure you want to activate this user's account?",
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
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH; ?>users/activate/" + id;
					    	}
				
					    }
					});
    }

	function confirmDeactivate(id){
    	bootbox.confirm({
       					size: "small",
					    title: "Deactivate Account",
					    message: "Are you sure you want to deactivate this user's account?",
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
					    	if(result==true){
					    		window.location.href = "<?php echo HTTP_PATH; ?>users/deactivate/" + id;
					    	}
				
					    }
					});
    }

	$(function () {
		var $table = $('#users-table');
		$table.bootstrapTable();
	});
</script>
