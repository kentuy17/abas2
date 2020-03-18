<?php
	if($action == 'add')
	{
		$title = 'Leave Application';
		$type = '';
		$payed = '3';
		$date_from = '';
		$date_to = '';
		$reason = '';
		$address = '';
		$contact_no = '';
		$approver_id = '';
	}
	elseif($action == 'edit' or $action == 'view')
	{
		$title = 'Edit Leave Application';
		$type = $item->type;
		$payed = $item->is_with_pay;
		$date_from = $item->date_from;
		$date_to = $item->date_to;
		$reason = $item->reason;
		$address = $item->address;
		$contact_no = $item->contact_number;
		$approver_id = $item->approver_id;
		$employee = array();

		if($action == 'view'){
			$submit = '';
		}
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
					<label>Leave Type: <?=$type?>*</label>
					<select class="form-control" name="type" required>
						<option></option>
						<?php foreach ($leave_types as $row) {  ?>
							<option value="<?=$row->value?>" <?=$row->value==$type?"SELECTED":""?>>
								<?=$row->name?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
					<label>Date From:*</label>
					<input type="date" name="date_from" value="<?=$date_from?>" class="form-control" required>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
					<label>Date To:*</label>
					<input type="date" name="date_to" value="<?=$date_to?>" class="form-control" required>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Reason of Leave:*</label>
					<textarea class="form-control" name="reason" placeholder="Reason of Leave" required><?=$reason?></textarea>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Address while on Leave:</label>
					<input type="text" name="address" value="<?=$address?>" class="form-control" placeholder="Address while on Leave">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Contact Number while on Leave:</label>
					<input type="number" name="contact_no" value="<?=$contact_no?>" class="form-control" placeholder="Contact Number while on Leave">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Approver*:</label>
					<select class="form-control" name="approver">
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