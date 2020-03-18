<?php
	$companies		=	$this->Abas->getCompanies();
	$companyoptions	=	"";
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
	$vesseloptions	=	"";
	$vessels		=	$this->Abas->getVessels();
	if(!empty($vessels)) {
		foreach($vessels as $v) {
			$vesseloptions	.=	"<option value='".$v->id."'>".$v->name."</option>";
		}
	}
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		Filter: Purchase History 
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	</div>
</div>
	<div class="panel-body">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<b>For <?php echo $item['description']; ?></b>
			<hr>
		</div>
		<form action="<?php echo HTTP_PATH."inventory/purchase_report/result/".$item['id']; ?>" method="GET" onsubmit="javascript: checkform();" enctype="multipart/form-data" name="fs_form" id="fs_form">
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="company" class="control-label">Company:</label>
				<select class="form-control" id="company" name="company">
					<option value=""> All Companies</option>
					<?php echo $companyoptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="vessel" class="control-label">Vessel/Office:</label>
				<select class="form-control" id="vessel" name="vessel">
					<option value=""> All Vessels/Offices</option>
					<?php echo $vesseloptions; ?>
				</select>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dstart" class="control-label">Date From:</label>
				<input type="date" name="dstart" id="dstart" class="form-control" value="<?php echo date("Y-m-")."01"; ?>" />
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dfinish" class="control-label">Date To:</label>
				<input type="date" name="dfinish" id="dfinish" class="form-control" value="<?php echo date("Y-m-d"); ?>" />
			</div>
	</div>
	<div class="modal-footer">
		<input type="button" onclick="javascript: checkform();" class="btn btn-success" value="Filter" />
		<input class="btn btn-danger" type="button" value="Cancel" data-dismiss="modal">
	</div>
	</form>
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