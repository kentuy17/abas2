<?php
	$year = date('Y');
	//$i = 2010;
	$prev = $year - 1;
	$date_from = $prev.'-'.date('m-d');
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title">Generate Budget
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<form action="<?=HTTP_PATH.'budget/generate_budget'?>" role="form" method="POST" id="request_for_payment_form" enctype="multipart/form-data">
		<div class="panel panel-info">
			<div class="panel-body" id="summary_container">
				<div class="col-xs-6 col-sm-6 col-md-6">
					<label>Year to Generate*</label>
					<select class="form-control" name="target_year" required oninvalid="this.setCustomValidity('Please select year')" oninput="this.setCustomValidity('')">
						<option></option>
					<?php for($i = $year; $i <= 2030; $i++){ ?>
						<option value="<?=$i?>" <?=($i == $prev ? "SELECTED" : "")?>><?=$i?></option>
					<?php } ?>
					</select>
				</div>
				<!--div class="col-xs-6 col-sm-6 col-md-6">
					<label>Dummy*</label>
					<input type="date" name="years" id="datepicker" class="form-control">
				</div-->
				<br/><br/><br/><br/>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label>Department*</label>
					<select class="form-control" name="department_id" id="department_id"> <?php if($this->Abas->checkPermissions("manager|generate_all_budget",false)) echo ""; else echo "disabled" ?>>
						<option value="All">ALL DEPARTMENTS</option>
						<?php foreach ($departments as $row) { ?>
							<option value="<?=$row->id?>" <?=($user->department == $row->id ? "SELECTED" : "")?>><?=$row->name?></option>
						<?php } ?>
						<!--option value="0">GENERAL OPERATIONS</option-->
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label>Vessel*</label>
					<select class="form-control" name="vessel_id" id="vessel_id" disabled <?php if($this->Abas->checkPermissions("manager|generate_all_budget",false)) echo ""; else echo "disabled" ?> required>
						<option value=""></option>
						<option value="0">All</option>
						<?php foreach ($vessels as $row) { ?>
							<option value="<?=$row->id?>"><?=$row->name?></option>
						<?php } ?>
					</select>
				</div>
				<br/><br/><br/><br/>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label>Company*</label>
					<select class="form-control" name="company_id" <?php if($this->Abas->checkPermissions("manager|generate_all_budget",false)) echo ""; else echo "disabled" ?>>
					<?php foreach ($companies as $row) { ?>
						<option value="<?=$row->id?>" <?=($user->company_id == $row->id ? "SELECTED" : "")?>><?=$row->name?></option>
					<?php } ?>
					</select>
				</div>
				<br/><br/><br/><br/>
				<!--div class="col-xs-12 col-sm-12 col-md-12">
					<form name="myform">
						<label>Dummy</label>
						<select size="1" name="D1" onChange="dis_able()">
						  <option>Category A</option>
						  <option>Category B</option>
						  <option>Catagory C</option>
						  <option value="Others">Others</option>
						</select>
						<input disabled type="text" name="otherz" size="20" value="pls specify">
					</form>
				</div-->
				<div class="col-xs-6 col-sm-6 col-md-6">
					<label>From:*</label>
					<input type="date" class="form-control" value="<?=$date_from?>" name="reference_from">
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<label>To:*</label>
					<input type="date" class="form-control" value="<?=date('Y-m-d')?>" name="reference_to">
				</div>
			</div>
		</div>	
		<div class="col-xs-12 col-sm-12 col-lg-12"s>
			<br>
			<span class="pull-right">
				<input type="submit" value="Generate" class="btn btn-success btn-m"/>
				<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
			</span>
		</div>
	</form>
</div>


<script type="text/javascript">
	$('#department_id').change(function(){
		var x = $('#department_id').val();
		console.log(x);
		if(x == "14"){
			$('#vessel_id').prop('disabled',false);	
		}else{
			$('#vessel_id').prop('disabled',true);
			$('#vessel_id').val('SELECT');
		}
	});
</script>