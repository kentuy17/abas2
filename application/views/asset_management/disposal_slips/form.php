<?php
	$detailform = "";
	$detailformEdit = "";
	$requested_by="";
	$requested_by_id = "";
	$requested_on="";
	$position="";
	$department="";
	$checked_by="";
	$checked_by_id = "";
	$checked_on="";
	$checked_by_position= "";
	$checked_by_department="";
	$disabled = "";
	$manner = "";
	$others = "";

	$title = "Add Disposal Slip";
	$disposaltion = HTTP_PATH."Asset_Management/disposal_slip/insert";

	$company_options = "<option value=''>Select</option>";
	if(!empty($companies)) {
		foreach($companies as $option) {
			if(isset($disposal)){
					$company_options	.=	"<option ".($disposal->company_id==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
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
					<label>Date Purchased:</label>
					<input type='date' id='date_purchased[]' name='date_purchased[]'  class='form-control' value='' readonly/>
					<label>Original Cost:</label>
					<input type='number' id='original_cost[]' name='original_cost[]'  class='form-control' value='' readonly/>
					<label>Net Book Value:*</label>
					<input type='number' id='net_book_value[]' name='net_book_value[]'  class='form-control' value='' required/>
				</div>
				<div class='col-sm-11 col-xs-11'>
					<label>Expected Actual Proceeds:*</label>
					<input type='number' id='actual_proceeds[]' name='actual_proceeds[]'  class='form-control' value='' required/>
				</div>
				<div class='col-sm-11 col-xs-11'>
					<label>Reason for Disposal:</label>
					<textarea id='reason_for_disposal[]' name='reason_for_disposal[]' class='form-control'></textarea>
				</div>
				<div class='col-sm-12 col-xs-12'>
				<hr>
				</div>
				<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
			</div>
			";

$detailform_append= trim(preg_replace('/\s+/',' ', $detailform));


if(isset($disposal)){

	$requested_by_id = $disposal->requested_by_id;
	$requested_by= strtoupper($disposal->requested_by);
	$requested_on=$disposal->requested_on;
	$position= $disposal->position;
	$department=$disposal->department;
	$checked_by=strtoupper($disposal->checked_by);
	$checked_by_id = $disposal->checked_by_id;
	$checked_on=$disposal->checked_on;
	$checked_by_position= $disposal->checked_by_position;
	$checked_by_department=$disposal->checked_by_department;
	$disabled = "readonly";
	$manner = $disposal->manner_of_disposal;
	$others = $disposal->others;
	$others = $disposal->others;

	$title = "Edit Disposal Slip";
	$disposaltion = HTTP_PATH."Asset_Management/disposal_slip/update/".$disposal->id;

	if(isset($disposal_details)){

		foreach($disposal_details as $detail){
			$detailformEdit	.=	"
				<div class='row asset-row command-row-assets'>
					<div class='col-sm-11 col-xs-11'>
						<label>Asset:*</label>
						<input type='text' id='asset_name[]' name='asset_name[]' class='asset_name form-control' value='".$detail->asset_code ." | ". $detail->item_name."' required readonly/>
						<input type='hidden' id='asset_id[]' name='asset_id[]' class='asset_id form-control' value='".$detail->asset_id."' readonly/>
						<label>Item Description:</label>
						<input type='text' id='particular[]' name='particular[]'  class='form-control' value='".$detail->item_particular."' readonly/>
						<label>Date Purchased:</label>
						<input type='date' id='date_purchased[]' name='date_purchased[]'  class='form-control' value='".$detail->date_purchased."' readonly/>
						<label>Original Cost:</label>
						<input type='number' id='original_cost[]' name='original_cost[]'  class='form-control' value='".$detail->original_cost."'readonly/>
						<label>Net Book Value:*</label>
						<input type='number' id='net_book_value[]' name='net_book_value[]'  class='form-control' value='".$detail->net_book_value."' required/>
					</div>
					<div class='col-sm-11 col-xs-11'>
						<label>Expected Actual Proceeds:</label>
						<input type='number' id='actual_proceeds[]' name='actual_proceeds[]'  class='form-control' value='".$detail->proceeds."' required/>
					</div>
					<div class='col-sm-11 col-xs-11'>
						<label>Reason for Disposal:</label>
						<textarea id='reason_for_disposal[]' name='reason_for_disposal[]' class='form-control'>".$detail->reason_for_disposal."</textarea>
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
<form class="form-horizontal" role="form" id="disposalSlip" name="disposalSlip" action="<?php echo $disposaltion; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF() ?>
		<div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="headingOne">
				<strong><?php echo $title;?></strong>
				 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
		</div>
		<div class="panel-body" role="tab">
			<div class="panel-group" id="disposalSlipDivider" role="tablist" aria-multiselectable="true">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="summary">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#disposalSlipDivider" href="#accountabilitySummary" aria-expanded="true" aria-controls="accountabilitySummary">
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
							<div class='col-sm-8 col-xs-12'>
								<label>Checked By:*</label>
								<input class="form-control" type="text" required name="checked_by_name" id="checked_by_name" value="<?php echo $checked_by;?>">
								<input class="form-control" type="hidden" name="checked_by" required id="checked_by" value="<?php echo $checked_by_id;?>">
							</div>
							<div class='col-sm-4 col-xs-12'>
								<label>Date Checked:*</label>
								<input class="form-control" type="date" name="checked_on" id="checked_on" value="<?php echo $checked_on;?>" required>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Position:</label>
								<input class="form-control" type="text" name="checked_by_position" id="checked_by_position" value="<?php echo $checked_by_position;?>" readonly>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Department:</label>
								<input class="form-control" type="text" name="checked_by_department" id="checked_by_department" value="<?php echo $checked_by_department;?>" readonly>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Manner of Disposal:*</label>
								<br>
									<input type="radio" value='Sale' <?php if($manner=="Sale"){ echo "checked";}?> name="manner_of_disposal" class="manner_of_disposal">Sale &nbsp  &nbsp
								 	<input type="radio" value='Scrapped' <?php if($manner=="Scrapped"){ echo "checked";}?>  name="manner_of_disposal" class="manner_of_disposal">Scrapped &nbsp  &nbsp
									<input type="radio" value='Donation' <?php if($manner=="Donation"){ echo "checked";}?>  name="manner_of_disposal" class="manner_of_disposal">Donation &nbsp  &nbsp
									<input type="radio" value='Trade-in' <?php if($manner=="Trade-in"){ echo "checked";}?>  name="manner_of_disposal" class="manner_of_disposal">Trade-in &nbsp  &nbsp
									<input type="radio"  value='Others' <?php if($manner=="Others"){ echo "checked";}?>  name="manner_of_disposal" class="manner_of_disposal">Others &nbsp  &nbsp
									<input class="form-control" type="hidden" id="manner" value="<?php echo $manner;?>" required>
							</div>
							<div class='col-sm-6 col-xs-12'>
								 <?php if($manner=="Others"){ ?>
								 	<div class="specify" id="specify">
										<label>Specify:</label>
										<input class="form-control" type="text" name="others" id="others" value="<?php echo $others;?>">
									</div>
								 <?php }else{ ?>
								 	<div class="specify hidden" id="specify">
										<label>Specify:</label>
										<input class="form-control" type="text" name="others" id="others" value="">
									</div>
								 <?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel-group" id="disposalSlipDivider" role="tablist" aria-multiselectable="true">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="details">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#disposalSlipDivider" href="#accountabilitySummary" aria-expanded="true" aria-controls="accountabilityDetails">
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
								if(!isset($disposal)){
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

	$( "#checked_by_name" ).autocomplete({
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
			$('#checked_by_name').val( ui.item.label );
			$('#checked_by').val( ui.item.value );
			$('#checked_by_position').val( ui.item.position );
			$('#checked_by_department').val( ui.item.department );
			return false;
		}
	});

	$('#company_id').change(function(){  
		var company = $('#company_id').val();
		$('#company_id').attr('readonly', true)
		$( ".asset_name" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>Asset_Management/autocomplete_asset_disposal/"+company,
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
				$( this ).next().next().next().next().next().val( ui.item.date_purchased );
				$( this ).next().next().next().next().next().next().next().val( ui.item.original_cost );
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
			source: "<?php echo HTTP_PATH; ?>Asset_Management/autocomplete_asset_disposal/"+company,
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
				$( this ).next().next().next().next().next().val( ui.item.date_purchased );
				$( this ).next().next().next().next().next().next().next().val( ui.item.original_cost );
				return false;
			}
		});
	});
	$('.manner_of_disposal').change(function(){  
		var manner = $(this).val();
		if(manner=='Others'){
			$('#specify').removeClass();
			$('#manner').val('Others');
		}else{
			$('#manner').val(manner);
			$('#others').val('');
			$('#specify').addClass('hidden');
		}
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
			$("#disposalSlip").submit();
			return true;
		}

	}
</script>