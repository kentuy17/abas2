<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title">Generate Opex for Year: <?=date('Y')?>
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<form action="<?=HTTP_PATH.'corporate_services/generate_budget'?>" role="form" method="POST" id="request_for_payment_form" enctype="multipart/form-data">
		<div class="panel panel-info">
			<div class="panel-body" id="summary_container">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label>Department*</label>
					<select class="form-control" name="department_id" <?php if($this->Abas->checkPermissions("corporate_services|generate_all_budget",false)) echo ""; else echo "disabled" ?>>
						<?php foreach ($departments as $row) { ?>
							<option value="<?=$row->id?>" <?=($user->department == $row->id ? "SELECTED" : "")?>><?=$row->name?></option>
						<?php } ?>
					</select>
				</div>
				<br/><br/><br/><br/>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label>Company*</label>
					<select class="form-control" name="company_id" <?php if($this->Abas->checkPermissions("corporate_services|generate_all_budget",false)) echo ""; else echo "disabled" ?>>
						<?php foreach ($companies as $row) { ?>
							<option value="<?=$row->id?>" <?=($user->company_id == $row->id ? "SELECTED" : "")?>><?=$row->name?></option>
						<?php } ?>
					</select>
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
