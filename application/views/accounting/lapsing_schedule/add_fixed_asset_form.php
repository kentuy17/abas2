<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>Add New Fixed-Asset</text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>
<form role="form" id="fixedAssetForm" action="<?php echo HTTP_PATH.'accounting/lapsing_schedule/insert_fixed_asset'; ?>" name="fixedAssetForm" method="post">
	<div class='panel-body'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<input type="hidden" id="lapsing_schedule_id" name="lapsing_schedule_id" class="form-control" value="<?php echo $lapsing_schedule->id?>">
			<input type="hidden" id="lapsing_schedule_year" name="lapsing_schedule_year" class="form-control" value="<?php echo $lapsing_schedule->year?>">
			<input type="hidden" id="lapsing_schedule_company" name="lapsing_schedule_company" class="form-control" value="<?php echo $lapsing_schedule->company_id?>">
			<div class="col-sm-12 col-xs-12">
				<label>Asset:*</label>
				<input type="text" id="fixed_asset_name" name="fixed_asset_name" class="form-control">
				<input type="hidden" id="fixed_asset_id" name="fixed_asset_id" class="form-control">
				<label>Particular:</label>
				<input type="text" id="fixed_asset_particular" name="fixed_asset_particular" class="form-control" readonly>
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
		var fixed_asset_id=$("#fixed_asset_id").val();
		if (fixed_asset_id==null || fixed_asset_id=="") {
			msg+="Please input the fixed-asset! <br/>";
		}
		if(msg!="") {
			toastr['error'](msg, "ABAS Says");
			return false;
		}
		else {
			$('body').addClass('is-loading');
			$('#modalDialogNorm').modal('toggle');
			document.getElementById("fixedAssetForm").submit();
			return true;
		}
	}
	var company = $("#lapsing_schedule_company").val();
	$( "#fixed_asset_name" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>Asset_Management/autocomplete_asset_lapsing/"+company,
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr["warning"]("Asset not found on that company!", "ABAS Says");
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$(this).prop("disabled", true);
			$( this ).val( ui.item.label );
			$( this ).next().val( ui.item.value );
			$( this ).next().next().next().val( ui.item.particular );
			return false;
		}
	});
</script>