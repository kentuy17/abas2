<?php
	$employee['full_name']=$employee['id']="";
	if(isset($best_guess_id)){
		$employee	=	$this->Abas->getEmployee($best_guess_id);
	}
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h5>Validate User</h5>
	</div>
	<div class="panel-body">
		<form action="<?php echo HTTP_PATH."hr/public_users/submit/".$public_user_id; ?>" method="POST">
			<div class=" col-md-12">
				<div class="form-group col-sm-12">
					<label>Name:</label>
					<input type="text" id="user_search" name="name" class="form-control" value="<?php echo $employee['full_name']; ?>"/>
					<input type="text" class="hide" id="employee_id" name="employee_id" value="<?php echo $employee['id']; ?>"/>
				</div>
				<div class="form-group col-sm-12">
					<input type="submit" name="submit" class="form-control btn btn-primary" value="Validate"/>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$("#user_search").autocomplete({
		source: "<?php echo HTTP_PATH; ?>hr/employee_autocomplete_list",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			toastr.clear();
		},
		select: function( event, ui ) {
			$( "#user_search" ).val( ui.item.label );
			$( "#employee_id" ).val( ui.item.value );
			return false;
		}
	});
</script>
