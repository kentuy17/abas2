<?php
	if($action == 'add'){
		$title = "Add Crew Movement";
		$transfer_date = "";
		$assigned_from = $vessel_from;
		$embarkation_start = "";
		$embarkation_end = "";
		$vessel_to = "";
		

	}elseif($action == 'edit'){
		$title = "Edit Crew Movement";
		$transfer_date = $item->transfer_date;
		$vessel_from = $item->vessel_from;
		$vessel_to = $item->vessel_to;
		$embarkation_start = $item->embarkation_start;
		$embarkation_end = $item->embarkation_end;
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
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Transfer Date:*</label>
					<input type="date" class="form-control" value="<?=$transfer_date?>" name="transfer_date" required>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
					<label>Assigned From:*</label>
					<select class="form-control" name="assigned_from" required>
						<option>-SELECT</option>
					<?php foreach ($vessels as $row) { ?>
						<option value="<?=$row->id?>" <?=($vessel_from==$row->id ? "selected" : "")?>>
							<?=$row->name?>
						</option>
					<?php } ?>
					</select>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
					<label>Assigned To:*</label>
					<select class="form-control" name="assigned_to" required>
						<option>-SELECT</option>
					<?php foreach ($vessels as $row) { ?>
						<option value="<?=$row->id?>" <?=($vessel_to==$row->id ? "selected" : "")?>><?=$row->name?></option>
					<?php } ?>
					</select>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
					<label>Embarkation Start:*</label>
					<input type="date" value="<?=$embarkation_start?>" name="embarkation_start" class="form-control">
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
					<label>Embarkation End:*</label>
					<input type="date" value="<?=$embarkation_end?>" name="embarkation_end" class="form-control">
				</div>
			</div>
		</div>	
		<div class="col-xs-12 col-sm-12 col-lg-12"s>
			<br>
			<span class="pull-right">
				<input type="submit" value="Submit" class="btn btn-success btn-m"/>
				<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
			</span>
		</div>
	</form>
</div>