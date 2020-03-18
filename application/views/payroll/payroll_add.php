<?php
	$companyoptions	=	"";
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
	$monthoptions		=	"";
	for($x=1; $x<=12; $x++) {
		$monthoptions	.=	"<option value='".date("M",strtotime("1970-".$x."-01"))."'>".date("F",strtotime("1970-".$x."-01"))."</option>";
	}
	$yearoptions		=	"";
	for($x=(date("Y")+1); $x>=2000; $x--) {
		$yearoptions	.=	"<option value='".$x."'>".$x."</option>";
	}
?>
<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>Add Payroll</text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>

<div class='panel-body'>
	<form role="form" id="payForm" action="<?php echo HTTP_PATH.'payroll/create'; ?>" name="payForm" method="post">
		<?php echo $this->Mmm->createCSRF(); ?>
		<div class="col-sm-12 col-xs-12">
			<label for="name">Company:*</label>
			<select class="form-control" name="company" id="company">
				<option value="" selected>Select</option>
				<?php echo $companyoptions; ?>
		   </select>
		</div>
		<div class="col-sm-6 col-xs-12">
			<label for="select-month">Month:*</label>
			<select class="form-control" name="month" id="select-month">
				<option value="" selected>Select</option>
				<?php echo $monthoptions; ?>
			</select>
		</div>
		<div class="col-sm-6 col-xs-12">
			<label for="select-period">Payroll Period:*</label>
			<select class="form-control" name="period" id="select-period">
				<option value="" selected>Select</option>
				<option value="1st-half">Beginning to 15th (1st-half)</option>
				<option value="2nd-half">15th to End of Month (2nd-half)</option>
			</select>
		</div>
		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<span class='pull-right'>
				<br>
				<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: checkform()' />
				<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
			</span>
		</div>
	</form>
</div>

<script>
	function checkform() {
		var msg="";
		var company=document.getElementById("company").selectedIndex;
		if (company==null || company=="") {
			msg+="Company is required! <br/>";
		}
		var month=document.getElementById("select-month").selectedIndex;
		if (month==null || month=="") {
			msg+="Month is required! <br/>";
		}
		var period=document.getElementById("select-period").selectedIndex;
		if (period==null || period=="") {
			msg+="Period is required! <br/>";
		}

		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			$('body').addClass('is-loading');
			$('#modalDialogNorm').modal('toggle');
			document.getElementById("payForm").submit();
			return true;
		}
	}
</script>

