<?php
	$companyoptions	=	"";
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
	$yearoptions		=	"";
	for($x=date("Y"); $x>=date("Y")-10; $x--) {
		$yearoptions	.=	"<option value='".$x."'>".$x."</option>";
	}
?>
<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>Add Lapsing Schedule</text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>
<form role="form" id="lapsingForm" action="<?php echo HTTP_PATH.'accounting/lapsing_schedule/insert'; ?>" name="lapsingForm" method="post">
	<div class='panel-body'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-sm-12 col-xs-12">
				<label for="name">Company:*</label>
				<select class="form-control" name="company" id="company">
					<option value="" selected>Select</option>
					<?php echo $companyoptions; ?>
			   </select>
			</div>
			<div class="col-sm-12 col-xs-12">
				<label for="select-year">Year:*</label>
				<select class="form-control" name="year" id="select-year">
					<option value="" selected>Select</option>
					<?php echo $yearoptions; ?>
				</select>
			</div>
	</div>
	<div class='modal-footer'>
		<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: checkform()' />
		<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
	</div>
</form>

<script>
	function checkform() {
		var msg="";
		var company=document.getElementById("company").selectedIndex;
		if (company==null || company=="") {
			msg+="Company is required! <br/>";
		}
		var year=document.getElementById("select-year").selectedIndex;
		if (year==null || year=="") {
			msg+="Year is required! <br/>";
		}
		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			$('body').addClass('is-loading');
			$('#modalDialogNorm').modal('toggle');
			document.getElementById("lapsingForm").submit();
			return true;
		}
	}
</script>