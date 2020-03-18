<?php
	$vesseloptions	=	"";
	$vessels		=	$this->Abas->getVessels();
	if(!empty($vessels)) {
		foreach($vessels as $c) {
			$vesseloptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
	$supplieroptions	=	"";
	$suppliers			=	$this->Abas->getSuppliers();
	if(!empty($suppliers)) {
		foreach($suppliers as $s) {
			$supplieroptions	.=	"<option value='".$s['id']."'>".$s['name']."</option>";
		}
	}
	$canvasseroptions	=	"";
	$canvassers			=	$this->Purchasing_model->getCanvassers();
	if(!empty($canvassers)) {
		foreach($canvassers as $ca) {
			$canvasseroptions	.=	"<option value='".$ca->user_id."'>".$ca->last_name.",".$ca->first_name." ".$ca->middle_name."</option>";
		}
	}
?>
<div class="panel panel-danger">
	<div class="panel-heading">
		Canvass History Report
	</div>
</div>
	<div class="panel-body">
		<form action="<?php echo HTTP_PATH."purchasing/canvass/report"; ?>" method="GET" onsubmit="javascript: checkform();" enctype="multipart/form-data" name="fs_form" id="fs_form">
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="vessel" class="control-label">Vessel/Office</label>
				<select class="form-control" id="vessel" name="vessel">
					<option value="">All Vessels/Offices</option>
					<?php echo $vesseloptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="canvassed_by" class="control-label">Canvassed by</label>
				<select class="form-control" id="canvassed_by" name="canvassed_by">
					<option value="">All Canvassers</option>
					<?php echo $canvasseroptions; ?>
				</select>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dstart" class="control-label">Canvass Date From</label>
				<input type="text" name="dstart" id="dstart" class="form-control datepicker" value="<?php echo date("Y-m-")."01"; ?>" />
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dfinish" class="control-label">Canvass Date To</label>
				<input type="text" name="dfinish" id="dfinish" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
			</div>
			<div class="clearfix"><br/>&nbsp</div>
			<div class="col-md-offset-1 col-md-10">
				<input type="button" onclick="javascript: checkform();" class="btn btn-success btn-block" value="Generate Report" />
			</div>
		</form>
	</div>

<script>
$(".datepicker").datepicker();
function checkform() {
	$(this).prop("disabled", true);
	var msg="";
	var datestart=document.forms.fs_form.dstart.value;
	if (datestart==null || datestart=="" || datestart==0) {
		msg+="Date From is required! <br/>";
	}
	var datefinish=document.forms.fs_form.dfinish.value;
	if (datefinish==null || datefinish=="" || datefinish==0) {
		msg+="Date To is required! <br/>";
	}
	if(msg!="") {
		$(this).prop("disabled", false);
		toastr["warning"](msg,"ABAS Says");
		return false;
	}
	else {
		$(this).prop("disabled", false);
		document.getElementById("fs_form").submit();
		return true;
	}
}
</script>