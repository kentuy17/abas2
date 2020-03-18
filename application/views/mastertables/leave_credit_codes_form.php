<?php
	$form_action = HTTP_PATH.'mastertables/leave_credit_codes/insert';
	$title = "Add New Tax Code";

	$array = [
		'id' => '',
		'code' => '',
		'tenurity' => '',
		'vacation_leave' => '',
		'sick_leave' => '',
		'vl_inc' => '',
		'sl_inc' => '',
		'max_vl' => '',
		'max_sl' => '',
		'description' => '',
		'base_credit' => '',
		'is_office_based' => ''
	];
	$e = (object) $array;

	if($action == 'view'){
		$form_action = HTTP_PATH.'mastertables/leave_credit_codes/update/'.$item->id;
		$title = "Edit Leave Credit Code";
		$e = $item;
	}
?>
<div class="panel panel-primary">
	<div class= "panel-heading" style="min-height">
		<button type= "button" class ="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title"><?=$title?></h5>
	</div>
</div>
<div class= "panel-body">
	<form action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
		<div class="form-group col-xs-12 col-sm-6">
			<label>Code</label>
			<input type="text" name="code" class="form-control" value="<?=$e->code?>" placeholder="Enter Code" required>
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Tenurity</label>
			<select class="form-control" name="tenurity">
				<option></option>
				<option value="ANY" <?=$e->tenurity=="ANY" ? "selected" : ""?>>Any</option>
				<option value="NEW" <?=$e->tenurity=="NEW" ? "selected" : ""?>>New Employee</option>
				<option value="OLD" <?=$e->tenurity=="OLD" ? "selected" : ""?>>Old Employee</option>
			</select>
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Vacation Leave</label>
			<input type="number" name="vacation_leave" class="form-control" placeholder="0" value="<?=$e->vacation_leave?>">
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Sick Leave</label>
			<input type="number" name="sick_leave" class="form-control" placeholder="0" value="<?=$e->sick_leave?>">
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Annual VL Increment</label>
			<input type="number" name="vl_inc" step="0.01" class="form-control" placeholder="0" value="<?=$e->vl_inc?>">
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Annual SL Increment</label>
			<input type="number" name="sl_inc" step="0.01" class="form-control" placeholder="0" value="<?=$e->sl_inc?>">
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Max VL Credits</label>
			<input type="number" name="max_vl" class="form-control" placeholder="0" value="<?=$e->max_vl?>">
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Max SL Credits</label>
			<input type="number" name="max_sl" class="form-control" placeholder="0" value="<?=$e->max_sl?>">
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Base Credit</label>
			<input type="number" name="base_credit" class="form-control" placeholder="0" value="<?=$e->base_credit?>">
		</div>
		<div class="form-group col-xs-12 col-sm-6">
			<label>Is office-based</label>
			<select class="form-control" name="is_office_based" required>
				<option <?=$e->is_office_based == "" ? "selected" : ""?>></option>
				<option value="1" <?=$e->is_office_based ? "selected" : ""?>>Yes</option>
				<option value="0" <?=$e->is_office_based == "0" ? "selected" : ""?>>No</option>
			</select>
		</div>
		<div class="form-group col-xs-12 col-sm-12">
			<label>Description</label>
			<textarea class="form-control" name="description"><?=$e->description?></textarea>
		</div>
		<div class="col-xs-12 col-sm-12 col-lg-12 clearfix"><br/></div>
		<div class="col-xs-12 col-sm-12 col-lg-12">
			<div class="form-group col-xs-12 col-sm-12 pull-right">
			<input type="submit" value="Submit" class="btn btn-primary btn-block"/>
		</div>
	</form>
</div>
