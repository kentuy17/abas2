<style type="text/css">
	#modal-bot{
		padding-bottom: 15px;
	}
</style>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title">Add Leave Application
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<form action="<?=HTTP_PATH.'hr/leave/hr_process'?>" method="post">
<div class="panel-body">
	<div class="panel panel-info">
		<div class="panel-body">
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Employee:*</label>
				<input type="search" placeholder="Search" name="emp_auto_complete" id="emp_auto_complete" class="form-control" required>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Leave Type:*</label>
				<select class="form-control" name="type" required>
					<option></option>
					<?php foreach ($leave_types as $row) { ?>
						<option value="<?=$row->name?>"><?=$row->name?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
				<label>Date From:*</label>
				<input type="date" name="date_from" class="form-control" required>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
				<label>Date To:*</label>
				<input type="date" name="date_to" class="form-control" required>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Reason of Leave:*</label>
				<textarea class="form-control" name="reason" placeholder="Reason of Leave" required></textarea>
			</div>
		</div>
	</div>	
	<div class="col-xs-12 col-sm-12 col-lg-12"s>
		<br>
		<span class="pull-right">
			<input type="submit" value="Process" class="btn btn-success">
		</span>
	</div>
</div>
</form>

<script type="text/javascript">
	$( "#emp_auto_complete" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>hr/autocomplete_employee",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr["warning"]("Employee not found!", "ABAS Says");
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$('#emp_auto_complete').val( ui.item.label );
			return false;
		}
	});
</script>