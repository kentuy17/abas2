<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Users
	</div>
</div>
		<div class="panel-body">
			<form action="<?php echo HTTP_PATH .'users/summary_report/result'?>" method="POST" id="filter_form">
				<div class="col-xs-12 col-sm-12">
				<label for="username">Username: </label>
				<input class="form-control input-sm" type="text" name="username" id="username"/>
				</div>
				<div class="col-xs-12 col-sm-12">
				<label for="">User Added</label>
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_from">From: </label>
				<input class="form-control input-sm" type="date" name="date_from" id="date_from" value="date_from"/>
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_to">To: </label>
				<input class="form-control input-sm" type="date" name="date_to" id="date_to" value="date_to"/>
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_to">Role: </label>
					<select name='role' id='role' class="form-control input-sm">
						<option></option>
						<option value="Administrator">Administrator</option>
						<option value="Human Resources">Human Resources</option>
						<option value="Payroll">Payroll</option>
	                    <option value="Accounting">Accounting</option>
	                    <option value="Operations">Operations</option>
	                    <option value="Inventory">Inventory</option>
	                    <option value="Purchasing">Purchasing</option>
	                    <option value="Finance">Finance</option>
	                    <option value="Asset Management">Asset Management</option>
	                    <option value="Compliance">Compliance</option>
					</select>	
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_to">Location: </label>
					<select name='location' id='location' class="form-control input-sm">
						<option></option>
						<option value="Tayud">Tayud</option>
						<option value="NRA">NRA(Cebu)</option>
						<option value="Makati">Makati</option>
					</select>	
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_to">Status: </label>
					<select name='status' id='status' class="form-control input-sm">
						<option></option>
						<option value="1">Activated</option>
						<option value="0">Deactivated</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_to">Require Password Reset?: </label>
					<select name='require_reset' id='require_reset' class="form-control input-sm">
						<option></option>
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>	
				</div>
				
				<div class="col-xs-12 col-sm-12">
					<hr>
				<label for="">User Activity (If not set, system will automatically pick-up activities from last 30 days)</label>
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_from">From: </label>
				<input class="form-control input-sm" type="date" name="activity_from" id="activity_from" value="activity_from"/>
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_to">To: </label>
				<input class="form-control input-sm" type="date" name="activity_to" id="activity_to" value="activity_to"/>
				</div>
				<div class="col-xs-12 col-sm-12">
					<input class="btn btn-danger pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
					<input class="btn btn-success pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:30px; margin-top:20px;" onclick='javascript:submitForm()'>
				</div>
			</form>
		</div>
		<br>

<script type="text/javascript">

function submitForm() {
		
	$('body').addClass('is-loading'); 
	$('#modalDialog').modal('toggle'); 
	
	document.getElementById("filter_form").submit();
	return true;
}
</script>