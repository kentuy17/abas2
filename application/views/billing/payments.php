<div class="panel-group">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="<?php echo HTTP_PATH.'billing/payments/add'; ?>" class="btn btn-primary" data-toggle="modal" data-target="#modalDialog">Receieve Payment</a>
		</div>
		<div class="panel-body">
			<table data-toggle="table" id="users-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."billing/payments/"; ?>" data-cache="false" data-side-pagination="server" data-sort-name="received_on" data-sort-order="desc" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true">
				<thead>
				<tr>
					<th data-field="received_on" data-align="center" data-sortable="true" data-visible="true">Received On</th>
					<th data-field="company_name" data-align="center" data-sortable="false" data-visible="true">Company</th>
					<th data-field="payment_source" data-align="center" data-sortable="false" data-visible="true">Source</th>
					<th data-field="operate" data-formatter="managePayment" data-align="center" data-align="center" >Manage</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<script>
	function managePayment(value, row, index) {
		return [
			'<div class="dropdown">',
				'<button class="btn btn-default dropdown-toggle" type="button" id="actionmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>',
				'<ul class="dropdown-menu" aria-labelledby="actionmenu">',
					'<li><a href="<?php echo HTTP_PATH.'users/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit">Edit</a></li>',
					'<li><a href="<?php echo HTTP_PATH.'users/permissions/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit Permissions">Permissions</a></li>',
					'<li><a onclick="javascript: confirmResetPassword('+row['id']+');" title="Reset Password">Password Reset</a></li>',
					'<li><a onclick="javascript: confirmDeactivate('+row['id']+');" title="Deactivate Account">Deactivate</a></li>',
				'</ul>',
			'</div>'
		].join('');
	}
</script>
