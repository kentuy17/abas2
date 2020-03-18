<?php
$unit_options = "<option value=''>Select</option>";
if(!empty($units)) {
	foreach($units as $unit) {
		$unit_options	.=	"<option value='".$unit['unit']."'>".$unit['unit']."</option>";
	}
}

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $company) {
		$company_options	.=	"<option value='".$company->id."'>".$company->name."</option>";
	}
}

$location_options = "<option value=''>Select</option>";
if(!empty($locations)) {
	foreach($locations as $location) {
		$location_options	.=	"<option value='".$location->location_name."'>".$location->location_name."</option>";
	}
}

?>
<div class="panel panel-warning" >
	<div class="panel-heading" style="min-height">
	   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	   <strong>UOM Conversion</strong>
	</div>
</div>	
		<div class="panel-body">
			<?php echo $this->Mmm->createCSRF() ?>
			<div class="alert alert-warning alert-dismissible fade in" role="alert">
    			<strong>Note:</strong> Use this only if your converting the item from its biggest to smallest unit of measurement before issuance. (Eg. Box to Pieces, Roll to Meters, Gallon to Liters)
  	  		</div>
			<form class="form-horizontal" role="form" id="convertForm" name="convertForm"  action="<?php echo HTTP_PATH.'inventory/items/convert'; ?>" method="post">
				<div class='col-sm-12 col-xs-12'>
					<label for='company'>Company*</label>
					<select id="company_idx" name='company_idx' class='form-control'>
						<?php echo $company_options;?>
					</select>
				</div>
				<div class='col-sm-12 col-xs-12'>
					<label for='location'>Location*</label>
					<select id="location_namex" name='location_namex' class='form-control'>
						<?php echo $location_options;?>
					</select>
				</div>	
				<div class='col-sm-12 col-xs-12'>
					<label for='item'>Search Item to convert*</label>
					<input type='text' id="item" name='item' class='form-control md-input' value=''/>
					<input type='hidden' id='stock_item_id' name='stock_item_id' class='form-control md-input' value=''>
					<input type='hidden' id='stock_item_desc' name='stock_item_desc' class='form-control md-input' value=''>
					<input type='hidden' id='stock_item_particular' name='stock_item_particular' class='form-control md-input' value=''>
				</div>

				<div class='col-sm-4 col-xs-4'>
					<label for='qty'>Quantity on stock</label>
					<input type='number' id='stock_qty' name='stock_qty' class='form-control md-input' value='' readonly/>
				</div>

				<div class='col-sm-4 col-xs-4'>
					<label for='unit'>Unit</label>
					<input type='text' id='stock_unit' name='stock_unit' class='form-control md-input' value='' readonly/>
				</div>
		
				<div class='col-sm-4 col-xs-4'>
					<label for='stock_unit_price'>Unit Price</label>
					<input type='number' id='stock_unit_price' name='stock_unit_price' class='form-control md-input' value='' readonly/>
				</div>

				<div class='col-sm-12 col-xs-12'>
					<hr>
					<label for='unit'>Conversion:<br></label>
				</div>

				<div class='col-sm-12 col-xs-12'>
					<label for='item_name'>New Item Name* (Change only if necessary)</label>
					<input type='text' id='new_item_desc' name='new_item_desc' class='form-control md-input' value='' />
				</div>

				<div class='col-sm-12 col-xs-12'>
					<label for='particulars'>New Particulars* (Change only if necessary)</label>
					<input type='text' id='new_particulars' name='new_particulars' class='form-control md-input' value='' />
				</div>

				<div class='col-sm-6 col-xs-6'>
					<label for='qty_to_convert'>Quantity to convert from stock*</label>
					<input type='number' id='qty_to_convert' name='qty_to_convert' class='form-control md-input' value='' />
				</div>

				<div class='col-sm-6 col-xs-6'>
					<label for='qty_after_convert'>Quantity after convert* (New Qty)</label>
					<input type='number' id='qty_after_convert' name='qty_after_convert' class='form-control md-input' value=''/>
				</div>

				<div class='col-sm-6 col-xs-6'>
					<label for='price_after_covert'>Unit Price after convert</label>
					<input type='number' id='price_after_covert' name='price_after_covert' class='form-control md-input' value='' readonly/>
				</div>
		
				<div class='col-sm-6 col-xs-6'>
					<label for='unit_after_convert'>Unit after convert*</label>
					<select id='unit_after_convert' name='unit_after_convert' class='form-control md-input' value=''>
						<?php echo $unit_options?>
					</select>
				</div>
		</div>	
		<div class="modal-footer">
			<input type="button" class="btn btn-success btn-m" onclick="javascript:checkForm();" value="Convert"/>
			<input type="button" class="btn btn-danger btn-m" value="Cancel" data-dismiss="modal" />
		</div>
	</form>
<script type="text/javascript">
	$('#location_namex').change(function(){
		$("#item").prop("readonly", false);
		$("#item").val('');
		$("#stock_item_id").val('');
		$("#stock_item_desc").val('');
		$("#stock_item_particular").val('');
	});
	$( "#item" ).autocomplete({
			//source: "<?php //echo HTTP_PATH; ?>inventory/item_conversion_data/"+$('#company_idx').children('option:selected').val()+"/"+$('#location_namex').children('option:selected').val(),
			 source: function(request, response) {
	        $.ajax({
	            url: "<?php echo HTTP_PATH; ?>inventory/item_conversion_data",
	            dataType: "json",
	            data: {
	                term : request.term,
	                company_id : $('#company_idx').val(),
	                location: $('#location_namex').val()
	            },
	            success: function(data) {
	                response(data);
	            }
	        });
	    },
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				toastr.clear();
			},
			select: function( event, ui ) {
				$( "#item" ).val( ui.item.label );
				$( "#stock_item_id" ).val( ui.item.value );
				$( "#stock_item_desc" ).val( ui.item.description );	
				$( "#stock_item_particular" ).val( ui.item.particular );	
				$( "#new_item_desc" ).val( ui.item.description );	
				$( "#new_particulars" ).val( ui.item.particular );	
				$( "#stock_qty" ).val( ui.item.qty );
				$( "#stock_unit" ).val( ui.item.unit );
				$( "#stock_unit_price" ).val( ui.item.unit_price );
				return false;
			}
		});
	$('#qty_after_convert').change(function(){
		var stock_unit_price = $('#stock_unit_price').val();
		var qty_to_convert = $('#qty_to_convert').val();
		var qty_after_convert = $(this).val();
	    $("#price_after_covert").val((stock_unit_price * qty_to_convert) / qty_after_convert);
	});
	$('#item').change(function(){
		$(this).prop("readonly", true);
	});
	$('#qty_to_convert').change(function(){
		var stock_unit_price = $('#stock_unit_price').val();
		var qty_to_convert = $(this).val();
		var qty_after_convert = $('#qty_after_convert').val();
	    $("#price_after_covert").val((stock_unit_price * qty_to_convert) / qty_after_convert);
	});
	function checkForm(){
		var  company = $("#company_id").val();
		var  location = $("#location_name").val();
		var  price_after_covert = $("#price_after_covert").val();
		var  stock_unit = $("#stock_unit").val();
		var  unit_after_convert = $("#unit_after_convert").val();
		var  qty_to_convert = $("#qty_to_convert").val();
		var  qty_after_convert = $("#qty_after_convert").val();
		var  stock_qty = $("#stock_qty").val();
		var  stock_item_particular = $("#stock_item_particular").val();
		var  new_item_desc = $("#new_item_desc").val();
		var  new_particulars = $("#new_particulars").val();
		var msg = '';
		if(company==''){
			msg += 'Company is required.<br>';
		}
		if(location==''){
			msg += 'Location is required.<br>';
		}
		if(price_after_covert==0){
			msg += 'Unit Price should not be zero.<br> Please contact Purchasing Dept. to add the price of the item first before converting.<br>';
		}
		if(stock_unit == unit_after_convert && stock_item_particular==new_particulars){
			msg += 'You should not convert this item similar to its old unit of measurement.<br>';
		}
		if(unit_after_convert==''){
			msg += 'Please select unit after conversion.<br>';
		}
		if(new_item_desc == ''){
			msg += 'New Item Name should not be empty.<br>';
		}
		if(new_particulars == ''){
			msg += 'New Particulars should not be empty.<br>';
		}
		if(parseInt(qty_to_convert) > parseInt(stock_qty)){
			msg += 'Quantity to Convert cannot be more than the Quantity On Stock.<br>';
		}
		if(stock_qty == 0){
			msg += 'Cannot convert if the Quantity On Stock is zero.<br>';
		}
		if(qty_to_convert == 0){
			msg += 'Cannot convert if the Quantity to Convert is zero.<br>';
		}
		if(qty_after_convert == 0){
			msg += 'Cannot convert if the Quantity After Convert is zero.<br>';
		}
		if(msg!=''){
            toastr['warning'](msg, "ABAS says:");
            return false;
        }else{
			document.forms['convertForm'].submit();
		}
	}

</script>