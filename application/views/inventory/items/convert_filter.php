<?php
	$companyoptions	=	"<option value=''>Select</option>";
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
	$location_options = "<option value=''>Select</option>";
	if(!empty($locations)) {
		foreach($locations as $location) {
			$location_options	.=	"<option value='".$location->location_name."'>".$location->location_name."</option>";
		}
	}
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		Filter: UOM Conversion History
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	</div>
</div>
	<div class="panel-body">
		<form action="<?php echo HTTP_PATH.'inventory/items/conversion_history'?>" method="POST" onsubmit="javascript: checkform();" enctype="multipart/form-data" name="fs_form" id="fs_form">
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="company" class="control-label">Company:</label>
				<select class="form-control" id="company_id" name="company_id">
					<?php echo $companyoptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="location" class="control-label">Location:</label>
				<select class="form-control" id="location" name="location">
					<?php echo $location_options; ?>
				</select>
			</div>
			<div class='col-sm-12 col-xs-12'>
				<label for='item'>Item</label>
				<input type='text' id="item" name='item' class='form-control md-input' value=''/>
				<input type='hidden' id='item_id' name='item_id' class='form-control md-input' value=''>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dstart" class="control-label">Date From:</label>
				<input type="date" name="dstart" id="dstart" class="form-control " value="<?php echo date("Y-m-")."01"; ?>" />
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dfinish" class="control-label">Date To:</label>
				<input type="date" name="dfinish" id="dfinish" class="form-control " value="<?php echo date("Y-m-d"); ?>" />
			</div>
	</div>
	<div class="modal-footer">
		<input type="button" onclick="javascript: checkform();" class="btn btn-success" value="Filter" />
		<input class="btn btn-danger" type="button" value="Cancel" data-dismiss="modal">
	</div>
	</form>
</div>
<script type="text/javascript">
var company_idx;
var locationx;
$('#location').change(function(){
	company_idx = $('#company_id').val();
	locationx = $('#location').val();
});
$( "#item" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>inventory/item_conversion_data/"+company_idx+"/"+locationx,
	minLength: 2,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$( "#item" ).val( ui.item.label );
		$( "#item_id" ).val( ui.item.value );
		return false;
	}
});

function checkform() {
	$(this).prop("disabled", true);
	var company = $('#company_id').val();
	var location = $('#location').val();
	var item = $('#item_id').val();
	var msg="";

	if(company==''){
		msg+="Company is required.<br>";
	}
	if(location==''){
		msg+="Location is required.<br>";
	}
	if(item==''){
		msg+="Item is required.<br>";
	}
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