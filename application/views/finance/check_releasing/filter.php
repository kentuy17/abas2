
<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Released Checks
	</div>
</div>		
	
			<div class="modal-body">
				<form action="<?php echo HTTP_PATH .'finance/check_releasing/report'; ?>" method="POST" id="filter_form">
						<div class="col-xs-12 col-m-12 col-sm-6">
							<label for="date_from"> Date From:* </label>
							<input class="form-control input-sm" type="date" name="date_from" id="date_from" value="date_from"/>
						</div>
							<div class="col-xs-12 col-sm-6">
							<label for="date_to"> Date To:* </label>
						<input class="form-control input-sm" type="date" name="date_to" id="date_to" value="date_to"/>
						</div>

						<div class="col-xs-12 col-m-12 col-sm-12">
						<?php
								echo '<label>Company:</label>';
								echo '<select class="form-control" name="company" id="company">';
								echo '<option value="">Select</option>';
								foreach($companies as $company){
									echo '<option value="' . $company->id . '">' . $company->name . '</option>';
								}
								echo '</select>';
						?>
						</div>
						<div class="col-xs-12 col-m-12 col-sm-12">
						<?php
								echo '<label>Supplier:</label>';
								echo '<select class="form-control" name="supplier" id="supplier">';
								echo '<option value="">Select</option>';
								foreach($suppliers as $supplier){
									echo '<option value="' . $supplier['id'] . '">' . $supplier['name'] . '</option>';
								}
								echo '</select>';
						?>
						</div>
						<div class="col-xs-12 col-m-12 col-sm-12">
						<?php
								echo '<label>Employee:</label>';
								echo '<select class="form-control" name="employee" id="employee">';
								echo '<option value="">Select</option>';
								foreach($employees as $ctr=>$employee){
									echo '<option value="' . $employee['id'] . '">' . $employee['last_name'] . ', '. $employee['first_name'].' '. substr($employee['middle_name'],0,1).'.</option>';
								}
								echo '</select>';
						?>
						</div>
			</div>
			<div class="modal-footer">
				<input class="btn btn-danger  pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top::;">
				<input class="btn btn-success  pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:0px; margin-top:0;" onclick='javascript:checkautoform()'>
			</div>
			</form>

<script type="text/javascript">
function checkautoform() {
		var msg="";
		var date_from = document.getElementById("date_from").value;
		var date_to = document.getElementById("date_to").value

		if (date_from=="" || date_to=="") {
			msg ="Please supply both date from and to. <br/>";
		}

		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("filter_form").submit();
			return true;
		}
}
$('#supplier').change(function(){   
	$('#employee').val("");
});
$('#employee').change(function(){   
	$('#supplier').val("");
});
</script>