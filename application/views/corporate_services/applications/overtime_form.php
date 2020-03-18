<?php
	if($action == 'add')
	{
		$title = 'Overtime Application';
		$time_from = '';
		$time_to = '';
		$reason = '';
		$address = '';
		$contact_no = '';
		$approver_id = '';
		$render_date = '';
	}
	elseif($action == 'edit' or $action == 'view')
	{
		$title = 'Edit Overtime Application';
		$time_from = $item->time_from;
		$time_to = $item->time_to;
		$reason = $item->reason;
		$approver_id = $item->approver_id;
		$employee = array();
		$render_date = $item->render_date;
	}
?>
<style type="text/css">
	#modal-bot{
		padding-bottom: 15px;
	}
</style>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title"><?=$title?>
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<form action="<?=$submit?>" role="form" method="POST" enctype="multipart/form-data">
		<div class="panel panel-info">
			<div class="panel-body" id="summary_container">
				<?php if($employee != null and $action == 'add') { ?>
					<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
						<label>Name:</label>
						<input type="search" name="emp_auto_complete" id="emp_auto_complete" placeholder="Search" class="form-control">
					</div>	
				<?php } ?>
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Date to Render:*</label>
					<input type="date" name="render_date" value="<?=$render_date?>" class="form-control" <?=$action=='view' ? "readonly" : ""?> required>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
					<label>Time From:*</label>
					<input type="time" name="time_from" value="<?=$time_from?>" class="form-control" <?=$action=='view' ? "readonly" : ""?> required>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
					<label>Time To:*</label>
					<input type="time" name="time_to" value="<?=$time_to?>" class="form-control" <?=$action=='view' ? "readonly" : ""?> required>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Reason of Leave:*</label>
					<textarea class="form-control" name="reason" placeholder="Reason of Leave" <?=$action=='view' ? "readonly" : ""?> required><?=$reason?></textarea>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Approver*:</label>
					<select class="form-control" name="approver" required <?=$action=='view' ? "disabled" : ""?>>
						<option></option>
						<?php foreach ($approver as $ctr => $row) { ?>
							<option value="<?=$row['emp_id']?>" <?=$approver_id==$row['emp_id']?"SELECTED":""?>>
								<?=$row['name']?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>	
		<div class="col-xs-12 col-sm-12 col-lg-12"s>
			<br>
			<span class="pull-right">
				<?php if($action == 'edit' or $action == 'add') { ?>
					<input type="submit" value="Submit" class="btn btn-success btn-m"/>
					<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
				<?php }else{ ?>
					<input type="button" class="btn btn-danger btn-m" value="Close" data-dismiss="modal">
				<?php } ?>
				
			</span>
		</div>
	</form>
</div>


<script type="text/javascript">
	$( "#emp_auto_complete" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>Corporate_Services/autocomplete_employee",
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