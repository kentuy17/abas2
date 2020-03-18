<?php
$data_source_options	=	"";
if(isset($data_sources)) {
	if(!empty($data_sources)) {
		foreach($data_sources as $data_source) {
			$data_source_options	.=	"<option value='".$data_source['data_source']."'>".$data_source['data_source']."</option>";
		}
	}
}
$vessel_options	=	"";
if(isset($vessels)) {
	if(!empty($vessels)) {
		foreach($vessels as $v) {
			$vessel_options	.=	"<option value='".$v->id."'>".$v->name."</option>";
		}
	}
}
?>
<div class="panel-group">
	<div class="panel panel-default">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					Report Tool Registration
					<button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
				</h4>
			</div>
			<div class="panel-body">
				<form name="tool_registry_form" id="tool_registry_form" action="<?php echo HTTP_PATH.'operation/tool_registry'; ?>" method="post">
					<?php echo $this->Mmm->createCSRF() ?>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<label for="issued_to">Issued To (name):</label>
						<input class="form-control input-sm" type="text" name="issued_to" id="issued_to" value="<?php  ?>" />
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<label for="mobile_no">
							Mobile Number (with existing location data)
						</label>
						<select class="form-control input-sm" name="mobile_number" id="mobile_number">
							<option></option>
							<?php echo $data_source_options; ?>
						</select>
					</div>
					<div class="col-lg-4 col-sm-12">
						<label for="vessel">Select Vessel (optional):</label>
						<select class="form-control input-sm" name="vessel" id="vessel">
							<option></option>
							<?php echo $vessel_options; ?>
						</select>
					</div>
					<div class="clearfix">&nbsp;</div>
					<div class="col-lg-6">
						<input class="btn btn-success btn-sm btn-block" type="button"  value="Save" onclick="javascript:submitForm()" id="submitbtn">
					</div>
					<div class="col-lg-6">
						<input class="btn btn-danger btn-sm btn-block"  value="Cancel" data-dismiss="modal">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>

function validateEmail(email) {
	var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
	}
	function validateRadio (radios)	{
		for (var i = 0; i < radios.length; i++)	{
			if (radios[i].checked) {return true;}
		}
		return false;
	}
function submitForm() {
	var msg="";
	var patt1=/^[0-9]+$/i;
	// /*
	var issued_to=document.getElementById("issued_to").value;
	if (issued_to==null || issued_to=="") {
		msg+="Issued To is required! <br/>";
	}
	var mobile_number=document.getElementById("mobile_number").selectedIndex;
	if (mobile_number==null || mobile_number=="") {
		msg+="Mobile Number is required! <br/>";
	}
	var vessel=document.getElementById("vessel").selectedIndex;
	if (vessel==null || vessel=="") {
		msg+="Vessel is required! <br/>";
	}
	if(msg!="") {
		toastr['warning'](msg,"ABAS says");
		return false;
	}
	else {
		document.getElementById("tool_registry_form").submit();
		return true;
	}

}
</script>
