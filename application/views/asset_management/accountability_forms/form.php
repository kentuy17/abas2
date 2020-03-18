<?php
	$detailform = "";
	$detailformEdit = "";
	$requested_by="";
	$requested_by_id = "";
	$requested_on="";
	$position="";
	$department="";
	$disabled = "";

	$title = "Add Accountability Form";
	$action = HTTP_PATH."Asset_Management/accountability_form/insert";

	$company_options = "<option value=''>Select</option>";
	if(!empty($companies)) {
		foreach($companies as $option) {
			if(isset($AC)){
					$company_options	.=	"<option ".($AC->company_id==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
			}
			else{
				$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
		unset($option);
	}

	$detailform	=	"
			<div class='row asset-row command-row-assets'>
				<div class='col-sm-11 col-xs-11'>
					<label>Asset:*</label>
					<input type='text' id='asset_name[]' name='asset_name[]' class='asset_name form-control' value='' required/>
					<input type='hidden' id='asset_id[]' name='asset_id[]' class='asset_id form-control' value='' readonly/>
					<label>Item Description:</label>
					<input type='text' id='particular[]' name='particular[]'  class='form-control' value='' readonly/>
				</div>
				<div class='col-sm-11 col-xs-111'>
					<label>Remark/Purpose:</label>
					<textarea id='remarks[]' name='remarks[]' class='form-control' value=''></textarea>
				</div>
				<div class='col-sm-12 col-xs-12'>
				<hr>
				</div>
				<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
			</div>
			";

$detailform_append= trim(preg_replace('/\s+/',' ', $detailform));


if(isset($AC)){

	$requested_by_id = $AC->requested_by_id;
	$requested_by= $AC->requested_by;
	$requested_on=$AC->requested_on;
	$position= $AC->position;
	$department=$AC->department;
	$disabled = "readonly";

	$title = "Edit Accountability Form";
	$action = HTTP_PATH."Asset_Management/accountability_form/update/".$AC->id;

	if(isset($AC_details)){

		foreach($AC_details as $detail){
			$detailformEdit	.=	"
				<div class='row asset-row command-row-assets'>
					<div class='col-sm-11 col-xs-11'>
						<label>Asset:*</label>
						<input type='text' id='asset_name[]' name='asset_name[]' class='asset_name form-control' value='".$detail->asset_code ." | ". $detail->item_name."' required readonly/>
						<input type='hidden' id='asset_id[]' name='asset_id[]' class='asset_id form-control' value='".$detail->fixed_asset_id."' readonly/>
						<label>Item Description:</label>
						<input type='text' id='particular[]' name='particular[]'  class='form-control' value='".$detail->item_particular."' readonly/>
					</div>
					<div class='col-sm-11 col-xs-111'>
						<label>Remark/Purpose:</label>
						<textarea id='remarks[]' name='remarks[]' class='form-control'>".$detail->remarks."</textarea>
					</div>
					<div class='col-sm-12 col-xs-12'>
					<hr>
					</div>
					<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
				</div>
				";
		}

	}

}

?>
<style type="text/css">
	.trx { font-size:130%;vertical-align:middle;text-align:center;font-weight:bold;color:#ffffff; }
	.trp { text-align:right;font-weight:bold;color:#000000 }
	.tre { text-align:left;font-style:italic;color:#000000 }
</style>
<form class="form-horizontal" role="form" id="accountabilityForm" name="accountabilityForm" action="<?php echo $action; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF() ?>
		<div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="headingOne">
				<strong><?php echo $title;?></strong>
				 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
		</div>
		<div class="panel-body" role="tab">
			<div class="panel-group" id="accountabilityFormDivider" role="tablist" aria-multiselectable="true">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="summary">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accountabilityFormDivider" href="#accountabilitySummary" aria-expanded="true" aria-controls="accountabilitySummary">
							General Info
							<span class="glyphicon glyphicon-chevron-down pull-right"></span>
							</a>
						</h4>
					</div>
					<div id="accountabilitySummary" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="summary">
						<div class="panel-body" id="summary_container">
							<div class='col-sm-12 col-xs-12'>
								<label>Company:*</label>
								<select id='company_id' name='company_id' class='form-control' required <?php echo $disabled;?>>
									<?php echo $company_options; ?>
								</select>
							</div>
							<div class='col-sm-8 col-xs-12'>
								<label>Requested By:*</label>
								<input class="form-control" type="text" required name="requested_by_name" id="requested_by_name" value="<?php echo $requested_by;?>">
								<input class="form-control" type="hidden" name="requested_by" required id="requested_by" value="<?php echo $requested_by_id;?>">
							</div>
							<div class='col-sm-4 col-xs-12'>
								<label>Date Requested:*</label>
								<input class="form-control" type="date" name="requested_on" id="requested_on" value="<?php echo $requested_on;?>" required>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Position:</label>
								<input class="form-control" type="text" name="position" id="position" value="<?php echo $position;?>" readonly>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Department:</label>
								<input class="form-control" type="text" name="department" id="department" value="<?php echo $department;?>" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel-group" id="accountabilityFormDivider" role="tablist" aria-multiselectable="true">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="details">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accountabilityFormDivider" href="#accountabilitySummary" aria-expanded="true" aria-controls="accountabilityDetails">
							Details
							<span class="glyphicon glyphicon-chevron-down pull-right"></span>
							</a>
						</h4>
					</div>
					<div id="accountabilitySummary" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="details">
						<br>
						<div class="pull-right">
							<a id="btn_add_row" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
							<a id="btn_remove_row" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
						</div>
						<div class="clearfix"><br/></div>
						<div class="panel-body asset-row-container" id='asset_container'>
							<?php 
								if(!isset($AC)){
									echo $detailform;	
								}else{
									echo $detailformEdit;
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<?php if($this->Abas->checkPermissions("asset_management|add_fixed_asset_register",false)): ?>
			<input class="btn btn-success" type="button" value="Save" onclick="javascript:submitMe()" id="submitbtn">
			<?php endif; ?>
			<input class="btn btn-danger" type="button" value="Discard" data-dismiss="modal">
		</div>
</form>
<script type="text/javascript">
	$( "#requested_by_name" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>Asset_Management/autocomplete_employee",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr["warning"]("Employee not found on that company!", "ABAS Says");
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$('#requested_by_name').val( ui.item.label );
			$('#requested_by').val( ui.item.value );
			$('#position').val( ui.item.position );
			$('#department').val( ui.item.department );
			return false;
		}
	});
	$('#company_id').change(function(){  
		var company = $('#company_id').val();
		$('#company_id').attr('readonly', true)
		$( ".asset_name" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>Asset_Management/autocomplete_asset/"+company,
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
	});	
	$("#btn_remove_row").click(function(){
		$('.asset-row:last').remove();
	});
	$(document).on('click', '.btn-remove-row', function() {
		$(this).parent().remove();
	});
	$("#btn_add_row").click(function(){
		var company = $('#company_id').val();
		$('.asset-row-container').append("<?php echo $detailform_append; ?>");
		$( ".asset_name" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>Asset_Management/autocomplete_asset/"+company,
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
	});

	function submitMe(){
		
		var msg="";
		var summary_flag=0;
		var summary_inputs = $('#summary_container').find('input').filter('[required]');
		var detail_divs = document.getElementsByClassName('asset-row'); 
    	var detail_inputs = $('.asset-row-container').find('input').filter('[required]');
    	var detail_flag=0;
    	var no_detail_flag=0;
		for(var x = 0; x < summary_inputs.length; x++){
        	if (summary_inputs[x].value==""){
            	summary_flag=1;
            }
        }
    	if(detail_divs.length > 0){
	        for(var y = 0; y < detail_inputs.length; y++){
	        	if (detail_inputs[y].value=="" || detail_inputs[y].value==0 ){
	            	detail_flag=1;
	            }
	        }
	    }else{
	    	no_detail_flag =1;
	    }

        var values = [];
	    $('.asset_id').each(
	      function() {
	        if (values.indexOf(this.value) >= 0) {
	           msg+="You have inputted one or more assets that are similar! Kindly double-check.<br/>";
	        }else {
	           values.push(this.value);
	        }
	      }
	    );

	    if(summary_flag==1){
        	msg+="Please fill-out all required fields (*) in General Info Tab!<br/>";
    	}
	    if(detail_flag==1){
        	msg+="Please fill-out all required fields (*) in Fixed Assets Tab!<br/>";
    	}
    	if(no_detail_flag==1){
        	msg+="Please add at least one(1) asset to be assigned in Fixed Assets Tab!<br/>";
    	}
		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else{
			$('body').addClass('is-loading');
			$('#modalDialog').modal('toggle');
			$("#accountabilityForm").submit();
			return true;
		}

	}
</script>