<?php
	$companyoptions	=	"";
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
	$departmentoptions	=	"";
	$departments		=	$this->Abas->getDepartments();
	if(!empty($departments)) {
		foreach($departments as $d) {
			$departmentoptions	.=	"<option value='".$d->id."'>".$d->name."</option>";
		}
	}
	$vesseloptions	=	"";
	$vessels		=	$this->Abas->getVessels();
	if(!empty($vessels)) {
		foreach($vessels as $v) {
			$vesseloptions	.=	"<option value='".$v->id."'>".$v->name."</option>";
		}
	}
	$contractoptions	=	"";
	$contracts		=	$this->Abas->getContracts();
	if(!empty($contracts)) {
		foreach($contracts as $c) {
			$contractoptions	.=	"<option value='".$c['id']."'>".$c['reference_no']."</option>";
		}
	}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		Books of Accounts
	</div>
	<div class="panel-body">
		<form action="<?php echo HTTP_PATH."accounting/books/report"; ?>" method="GET" onsubmit="javascript: checkform();" enctype="multipart/form-data" name="fs_form" id="fs_form">
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label id="journal_type" class="control-label">Journal type</label>
				<select class="form-control" id="journal_type" name="journal_type">
					<option value="general">General</option>
					<option value="purchase">Purchase</option>
					<option value="sales">Sales</option>
					<option value="cash receipt">Cash Receipt</option>
					<option value="disbursement">Disbursement</option>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="company" class="control-label">Company</label>
				<select class="form-control" id="company" name="company">
					<option value="">-</option>
					<?php echo $companyoptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="department" class="control-label">Department</label>
				<select class="form-control" id="department" name="department">
					<option value="">-</option>
					<?php echo $departmentoptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="vessel" class="control-label">Vessel</label>
				<select class="form-control" id="vessel" name="vessel">
					<option value="">-</option>
					<?php echo $vesseloptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="contract" class="control-label">Contract</label>
				<select class="form-control" id="contract" name="contract">
					<option value="">-</option>
					<?php echo $contractoptions; ?>
				</select>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dstart" class="control-label">Date From</label>
				<input type="text" name="dstart" id="dstart" class="form-control datepicker" value="<?php echo date("Y-m-")."01"; ?>" />
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dfinish" class="control-label">Date To</label>
				<input type="text" name="dfinish" id="dfinish" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
			</div>
			<div class="clearfix"><br/></div>
			<div class="col-md-offset-1 col-md-10">
				<input type="button" onclick="javascript: checkform();" class="btn btn-primary btn-block" value="Generate Report" />
			</div>
		</form>
	</div>
</div>
<script>
$(".datepicker").datepicker();
function checkform() {
	$(this).prop("disabled", true);
	var msg="";
	//var patt1=/^[0-9]+$/i;
	var patt1=/^\d+(\.\d+)*$/i;
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
		$('body').addClass('is-loading');
		$('#modalDialog').modal('toggle');
		document.getElementById("fs_form").submit();
		return true;
	}
}
</script>