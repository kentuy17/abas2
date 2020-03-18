<?php
	$companyoptions	=	"";
	$companies		=	$this->Abas->getCompanies();
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		Statement of Financial Position
	</div>
	<div class="panel-body">
		<form action="<?php echo HTTP_PATH."accounting/statement_of_income/report"; ?>" method="GET" onsubmit="javascript: checkform();" enctype="multipart/form-data" name="fs_form" id="fs_form">
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="company" class="control-label">Company</label>
				<select class="form-control" id="company" name="company">
					<option value="">-</option>
					<?php echo $companyoptions; ?>
				</select>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dstart" class="control-label">Date Range 1 From</label>
				<input type="text" name="dstart1" id="dstart1" class="form-control datepicker" value="<?php echo date("Y",strtotime("-1 year"))."-01-01"; ?>" />
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dfinish" class="control-label">Date Range 1 To</label>
				<input type="text" name="dfinish1" id="dfinish1" class="form-control datepicker" value="<?php echo date("Y",strtotime("-1 year"))."-12-31"; ?>" />
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dstart" class="control-label">Date Range 2 From</label>
				<input type="text" name="dstart2" id="dstart2" class="form-control datepicker" value="<?php echo date("Y")."-01-01"; ?>" />
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dfinish" class="control-label">Date Range 2 To</label>
				<input type="text" name="dfinish2" id="dfinish2" class="form-control datepicker" value="<?php echo date("Y")."-12-31"; ?>" />
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
	var datestart=document.forms.fs_form.dstart1.value;
	if (datestart==null || datestart=="" || datestart==0) {
		msg+="Date From is required! <br/>";
	}
	var datefinish=document.forms.fs_form.dfinish1.value;
	if (datefinish==null || datefinish=="" || datefinish==0) {
		msg+="Date To is required! <br/>";
	}
	var datestart=document.forms.fs_form.dstart2.value;
	if (datestart==null || datestart=="" || datestart==0) {
		msg+="Date From is required! <br/>";
	}
	var datefinish=document.forms.fs_form.dfinish2.value;
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