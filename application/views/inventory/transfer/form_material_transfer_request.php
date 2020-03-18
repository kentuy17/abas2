<?php
$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $c){
		$company_options	.=	"<option value='".$c->id."'>".$c->name." </option>";
	}
}
$location_options = "<option value=''>Select</option>";
if(!empty($locations)) {
	foreach($locations as $loc) {
		$location = $_SESSION['abas_login']['user_location'];
		if($loc->location_name!=$location){
			$location_options	.=	"<option value='".$loc->location_name."'>".$loc->location_name."</option>";
		}
	}
}
$vessel_code_options = "<option value=''>Select</option>";
if(!empty($vessels)) {
	foreach($vessels as $v){
		$vessel_code_options	.=	"<option value='".$v->id."'>".$v->name." </option>";
	}
}
?>
<style type="text/css">
	.parser_styl { opacity: 0; filter:alpha(opacity=0); }
</style>
<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Add Materials Transfer Request
		</h2>
	</div>
</div>

<form id='transfer_form' role='form' action='<?php echo HTTP_PATH."inventory/transfer/insert"?>' method='POST' enctype='multipart/form-data'>
	<div class='panel-body'>
		<div class='panel-group' id='MTRFormDivider' role='tablist' aria-multiselectable='true'>
			<div class='panel panel-info'>
				<div class='panel-heading' role='tab' id='general'>	
					<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#MTRGeneral' aria-expanded='true' aria-controls='MTRGeneral'>
					General Information
					<span class='glyphicon glyphicon-chevron-down pull-right'></span>
					</a>
				</div>
				<div id='MTRGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='MTRGeneral'>
					<div class='panel-body'>
						<div class='col-md-4 col-sm-12 col-xs-12'>
							<label>Transfer Request Date*</label>
								<input class="form-control input-sm" type="date" name="transfer_date" id="transfer_date" required />
						</div>
						<div class='col-md-8 col-sm-12 col-xs-12'>
							<label>Company*</label>
								<input type="text" class="form-control input-sm"  name="company_name" id="company_name" readonly/>
				            	<input type="hidden" class="form-control input-sm"  name="company_id" id="company_id"/>
						</div>
						<div class='col-md-4 col-sm-6 col-xs-12'>
							<label>Requested For*</label>
								<select class="form-control input-sm" name="requested_for" id="requested_for" required>
									<?php echo $vessel_code_options;?>
								</select>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
							<label>Requesting Warehouse</label>
								<input class="form-control input-sm" type="text" name="to_location" id="to_location" value="<?php echo $_SESSION['abas_login']['user_location']?>" readonly />
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
							<label>Issuing Warehouse*</label>
								<select class="form-control input-sm"name="from_location" id="from_location" required>
									<?php echo $location_options;?>
								</select>
						</div>
						<div class='col-md-12 col-sm-12 col-xs-12'>
							<label>Remarks</label>
							<textarea class="form-control input-sm" type="text" name="remark" id="remark" required></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='details'>	
				<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#MTRDetails' aria-expanded='true' aria-controls='MTRDetails'>
				Details
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>
			<div id='MTRDetails' class='panel-collapse collapse' role='tabpanel' aria-labelledby='MTRDetails'>
				<div class='panel-body'>
					<!--<div class='col-md-4 col-sm-4 col-xs-12'>
						<input  type="checkbox" name="scanner" id="scanner" value="1" onclick="
			                        if($('#scanner').is(':checked')) {
			                        	 $('#autocomplete_item').val('');
									     $('#item_quantity_id').val('');
									     $('#selected_item').val('');
									     $('#item_code').val('');
									     $('#item_name').val('');
									     $('#item_particular').val('');
									     $('#item_unit').val('');
									     $('#item_price' ).val('');
									     $('#item_price_history').val('');
									     $('#item_qty').val('');
									     $('#item_packaging').val('');
			                             $('#autocomplete_item').prop('readonly',true);
			                             $('#parser').focus();
			                             $('#continous').prop('disabled',false);
			                             $('#item_qty').prop('readonly',false);
			                        }else {
			                        	$('#autocomplete_item').prop('readonly',false);
			                        	$('#autocomplete_item').focus();
		                            	$('#parser').val('');
		                            	$('#continous').prop('checked',false);
		                            	$('#continous').prop('disabled',true);
		                            	$('#item_qty').prop('readonly',false);
			                        }             
				                "/>
			             <label>  Use QR Code Scanner?</label>		
					</div>
					<div class='col-md-8 col-sm-8 col-xs-12'>
						<input  type="checkbox" name="continous" id="continous" value="1" onclick="
			                        if($('#continous').is(':checked')) {
			                             $('#parser').focus();
			                             $('#item_qty').prop('readonly',true);
			                             $('#item_packaging').prop('disabled',true);
			                        }else {
			                        	$('#parser').focus();
			                        	$('#item_qty').prop('readonly',false);
			                        	$('#item_packaging').prop('disabled',false);
			                        }             
				                " disabled/>
			             <label>  Continous Scanning?</label>		
					</div>-->
					<div class='col-md-6 col-sm-4 col-xs-12'>
						<label>Item Name*</label>
						<input type="text" id="autocomplete_item" name="autocomplete_item" class="form-control">
						<input type="text" id="parser" name="parser" class="parser_styl" value=''>
						<input type="hidden" id="selected_item" name="selected_item" class="form-control">
						<input type="hidden" id="item_quantity_id" name="item_quantity_id" class="form-control">
						<input type="hidden" id="item_code" name="item_code" class="form-control">
						<input type="hidden" id="item_name" name="item_name" class="form-control">
						<input type="hidden" id="item_particular" name="item_particular" class="form-control">
						<input type="hidden" id="item_unit" name="item_unit" class="form-control">
						<input type="hidden" id="item_price" name="item_price" class="form-control">
						<input type="hidden" id="item_price_history" name="item_price_history" class="form-control">
					</div>
					<div class='col-md-2 col-sm-2 col-xs-12'>
						<label>Unit/Packaging*</label>
						<select id="item_packaging" name="item_packaging" class="form-control">
							<option value=''>Select</option>
						</select>
					</div>
					<div class='col-md-2 col-sm-4 col-xs-12'>
						<label>Qty Requested*</label>
						<input type="number" id="item_qty" name="item_qty" class="form-control">
					</div>
					<div class='col-md-2 col-sm-2 col-xs-12'>
						<label style='color:white'>XXXXX</label>
						<a class="btn btn-info btn-sm btn-block" id='addbtn' onclick="javascript:addItem();">Add</a>
					</div>
					<div class='clear-fix'><br><br><br>
					<br></div>
					
					<div class='col-xs-12 col-md-12' style='overflow-x: auto;'>
						<table id="table_items" name="table_items" data-toggle="table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<td>Item Code</td>
									<td>Item Name</td>
									<td>Particular</td>
									<td>Qty</td>
									<td>Unit</td>
									<td>Unit Price</td>
									<td>Line Total</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
								<tr id='row_item0' class='tbl_row_item'></tr>
								<tr id='row_item1' class='tbl_row_item'></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div class=' modal-footer'>
			<span class="pull-right">
				<input type="button" class="btn btn-success btn-m" onclick="javascript:checkForm();" value="Save"/>
				<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal" />
			</span>
		</div>
</form>
<script type="text/javascript">
var i_check=1;
var values = [];

$( "#autocomplete_item" ).autocomplete({
	source: function(request, response) {
        $.getJSON(
            "<?php echo HTTP_PATH; ?>inventory/autocomplete_items_for_issuance/",
            { term:request.term, company:$('#company_id').val(),location:$('#from_location').val()}, 
            response
        );
    },
	minLength: 2,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$("#autocomplete_item" ).val( ui.item.label );
		$("#selected_item" ).val( ui.item.value );
		$("#item_quantity_id" ).val( ui.item.item_quantity_id );
		$("#item_code" ).val( ui.item.item_code );
		$("#item_name" ).val( ui.item.description );
		$("#item_particular" ).val( ui.item.particular );
		$("#item_unit" ).val( ui.item.unit );
		$("#item_price" ).val(ui.item.unit_price);
		var item_id = $("#selected_item" ).val();

		$.ajax({
		    type:"POST",
		    url:"<?php echo HTTP_PATH;?>/inventory/get_item_packaging/"+item_id,
		    success:function(data){

		    	 $('#item_packaging').find('option').remove().end();

		    	 $('#item_packaging').append('<option value="">Select</option>').val('');
		    	 $('#item_packaging').append('<option value="'+ui.item.unit+'" selected>'+ui.item.unit+'</option>').val(ui.item.unit);

		    	var packagings = data;
				for(var i = 0; i < packagings.length; i++){
		       		var pck = packagings[i];
		       		var option = $('<option />');
				    option.attr('value', pck.packaging).text(pck.packaging);
				    $('#item_packaging').append(option);
		        }
		         

		    }
    	});


		$("#item_qty").focus();
		return false;
	}
});

$("#item_qty").keypress(function(e) {
	if(e.which == 13) {
		 addItem();
		 $('#autocomplete_item').val('');
		 $('#selected_item').val('');
		 $("#item_code" ).val('');
		 $("#item_name" ).val('');
		 $("#item_particular" ).val('');
		 $("#item_unit" ).val('');
		 $("#item_packaging" ).val('');
		 $("#item_price" ).val('');
		 $('#item_price_history').val('');
		 $('#item_qty').val('');
    }
 });


function addItem(){

	var msg="";
	
	var item = $('#autocomplete_item').val();
	var qty = $('#item_qty').val();

	
	if(item==""){
		msg+="Item is required.<br>";
	}
	
	if(qty==""){
		msg+="Quantity is required.<br>";
	}
	
	var item_id_x = $('#item_quantity_id').val();
    if (values.indexOf(item_id_x) >= 0) {
       msg+="You already added this item.<br/>";
       $('#autocomplete_item').val('');
	   $('#selected_item').val('');
	   $("#item_code" ).val('');
	   $("#item_name" ).val('');
	   $("#item_particular" ).val('');
	   $("#item_unit" ).val('');
	   $("#item_packaging" ).val('');
	   $("#item_price" ).val('');
	   $('#item_price_history').val('');
	   $('#item_qty').val('');
    }
	
	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}else{

		var item_id = $('#selected_item').val();
		var item_quantity_id = $('#item_quantity_id').val();
		var item_quantity = $('#item_qty').val();
		var item_code = $("#item_code" ).val();
		var item_name = $("#item_name" ).val();
		var item_price = $("#item_price").val();
		var item_price_history = $('#item_price_history').val();
		var item_particular = $("#item_particular" ).val();
		var item_unit = $("#item_unit" ).val();
		var item_packaging = $("#item_packaging" ).val();
		var po_id = $("#selected_po" ).val();
		var company = $("#company_id").val();
		var row_entry;
		var from_location = $("#from_location").val();
		
		if(company!=''){
			var unitx;
			var packagingx;

			$.ajax({
				url:"<?php echo HTTP_PATH;?>/inventory/check_item_quantity/"+item_quantity_id+"/"+item_quantity+"/"+from_location,
			    type:"POST",
			    data: { unitx: '"'+item_unit+'"', packagingx: '"'+item_packaging+'"' },
			    success:function(data){
			       if(data==1){
			       	
						var line_total =  parseFloat(item_price*item_quantity).toFixed(2);
						
						$.ajax({
							url:"<?php echo HTTP_PATH;?>/inventory/get_item_quantity_for_issuance/"+item_quantity_id,
						    type:"POST",
						    data: { packagingx: '"'+item_packaging+'"' },
						    success:function(data){
						    		if(parseInt(data.quantity) >= parseInt(item_quantity)){
						    			item_price = data.unit_price;
						    			line_total =  parseFloat(item_price*item_quantity).toFixed(2);

						    			row_entry = "<td><input type='hidden' id='item[]' name='item[]' class='item_x' value='"+item_id+"'><input type='hidden' id='item_qty_id[]' name='item_qty_id[]' value='"+item_quantity_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+item_packaging+"'><input type='hidden' id='price_history_id[]' name='price_history_id[]' value='"+item_price_history+"'>"+item_code+"</td><td>"+item_name+"</td><td>"+item_particular+"</td><td><input type='text' class='qtyx' style='border: none;' id='quantity_"+i_check+"' name='quantity[]' value='"+item_quantity+"' readonly></td><td>"+item_packaging+"</td><td><input type='text' style='border: none;' id='price[]' name='price[]' value='"+item_price+"' readonly></td><td><input type='text' style='border: none;' id='line_total' value='"+formatNumber(line_total)+"' readonly></td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs' id='"+item_quantity_id+"'>×</a></td>";

											$('#row_item'+i_check).html(row_entry);
											$('#table_items').append('<tr class="tbl_row_item" id="row_item'+(i_check+1)+'"></tr>');
											i_check++; 

										values.push(item_quantity_id);
						    		}else{
						    			toastr['error']('Quantity entered exceeds remaining balance in the inventory.', 'ABAS says:');
						    		}
						    	
						    }
						});

			       }else if(data==2){
			       		toastr['error']('Quantity entered exceeds remaining balance in the inventory.', 'ABAS says:');
			       }else if(data==0){
			       		toastr['error']('This item is not available and has no inventory on this company and/or location.', 'ABAS says:');
			       }
			    }
			});
		}else{
			toastr['error']('Please select first the company.', 'ABAS says:');
		}

		$('#autocomplete_item').val('');
		$('#selected_item').val('');
		$("#item_code" ).val('');
		$("#item_name" ).val('');
		$("#item_particular" ).val('');
		$("#item_unit" ).val('');
		$("#item_packaging" ).val('');
		$("#item_price" ).val('');
		$('#item_price_history').val('');
		$('#item_qty').val('');
		
	}
}

function formatNumber (num) {
	return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

$(document).on('click', '.btn-remove-row', function() {
	 $(this).closest('tr').remove();
	 var to_remove = $(this).attr('id');
	 const filteredItems = values.filter(function(item) {
	     return item !== to_remove;
	 })
	 values = filteredItems;
});

$('#requested_for').change(function(){   
  var requested_for = $('#requested_for').val();
   $( this ).attr('readonly','readonly');
  if(requested_for!=""){
	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>/home/get_company_name/"+requested_for,
	     success:function(data){
	        var c = $.parseJSON(data);
	        $('#company_name').val(c.company_name);
	        $('#company_id').val(c.company_id);
	     } 	
	  });
  }
});
	
function checkForm() {
	var msg="";

	var transfer_date = $('#transfer_date').val();
	var company = $('#company_id').val();
	var from_location = $('#from_location').val();
	var received_by = $('#issued_to').val();


	if(transfer_date==''){
		msg+="Transfer Date is required.<br/>";
	}
	if(company==''){
		msg+="Company is required.<br/>";
	}
	if(from_location==''){
		msg+="Issuing Warehouse is required.<br/>";
	}

    var row_count =$("#table_items > tbody > tr").length;
	if (row_count<=2){
		msg+="Please add item(s) before submitting! <br/>";
	}

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Transfer",
			size: 'small',
		    message: "Are you sure you want to request these following items for transfer?",
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-success'
		        },
		        cancel: {
		            label: 'No',
		            className: 'btn-danger'
		        }
		    },
		    callback: function (result) {
		    	if(result){

		    		$('body').addClass('is-loading'); 
					$('#modalDialog').modal('toggle'); 

			        document.getElementById("transfer_form").submit();
			        return true;
		    	}
		    }
		});

	}
}
</script>