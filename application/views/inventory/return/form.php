<?php
$vessel_code_options = "<option value=''></option>";
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
			Add Material & Supplies Return Slip
		</h2>
	</div>
</div>

<form id='return_form' role='form' action='<?php echo HTTP_PATH."inventory/return/insert"?>' method='POST' enctype='multipart/form-data'>
	<div class='panel-body'>
		<div class='panel-group' id='MSRSFormDivider' role='tablist' aria-multiselectable='true'>
			<div class='panel panel-info'>
				<div class='panel-heading' role='tab' id='general'>	
					<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#MSRSGeneral' aria-expanded='true' aria-controls='MSRSGeneral'>
					General Information
					<span class='glyphicon glyphicon-chevron-down pull-right'></span>
					</a>
				</div>
				<div id='MSRSGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='MSRSGeneral'>
					<div class='panel-body'>
						<div class='col-md-3 col-sm-12 col-xs-12'>
							<label>MSIS Transaction Code No.*</label>
								<input class="form-control input-sm" type="text" name="msis_no" id="msis_no" required />
								<input class="form-control input-sm" type="hidden" name="msis_id" id="msis_id" required />
						</div>
						<div class='col-md-9 col-sm-12 col-xs-12'>
							<label>Company</label>
				            	<input type="text" class="form-control input-sm"  name="company_name" id="company_name" readonly/>
				            	<input type="hidden" class="form-control input-sm"  name="company_id" id="company_id"/>
						</div>
						<div class='col-md-3 col-sm-12 col-xs-12'>
							<label>Return Date*</label>
								<input class="form-control input-sm" type="date" name="return_date" id="return_date" required />
						</div>
						<div class='col-md-3 col-sm-12 col-xs-12'>
							<label>Returned From</label>
								<select class="form-control input-sm" name="return_from" id="return_from" required readonly>
									<?php echo $vessel_code_options;?>
								</select>
						</div>
						<div class='col-md-2 col-sm-12 col-xs-12'>
							<label>Return To</label>
								<input class="form-control input-sm" type="text" name="return_to" id="return_to" value="<?php echo $_SESSION['abas_login']['user_location']?>" required readonly/>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
							<label>Returned By*</label>
								<input class="form-control input-sm" type="text" name="return_by" id="return_by" required />
						</div>
						<div class='col-md-12 col-sm-12 col-xs-12'>
							<label>Reason/Remarks*</label>
							<textarea class="form-control input-sm" type="text" name="remark" id="remark" required></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='details'>	
				<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#MSRSDetails' aria-expanded='true' aria-controls='MSRSDetails'>
				Details
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>
			<div id='MSRSDetails' class='panel-collapse collapse' role='tabpanel' aria-labelledby='MSRSDetails'>
				<div class='panel-body'>
					<div class='col-md-4 col-sm-4 col-xs-12'>
						<input  type="checkbox" name="scanner" id="scanner" value="1" onclick="
			                        if($('#scanner').is(':checked')) {
			                        	 $('#autocomplete_item').val('');
									     $('#item_id').val('');
									     $('#selected_item').val('');
									     $('#item_code').val('');
									     $('#item_name').val('');
									     $('#item_particular').val('');
									     $('#item_unit').val('');
									     $('#item_price' ).val('');
									     $('#item_price_history').val('');
									     $('#item_qty').val('');
									     $('#item_qty_issued').val('');
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
		                            	$('#item_packaging').prop('disabled',false);
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
					</div>
					<div class='col-md-6 col-sm-4 col-xs-12'>
						<label>Item Name*</label>
						<input type="text" id="autocomplete_item" name="autocomplete_item" class="form-control">
						<input type="text" id="parser" name="parser" class="parser_styl" value=''>
						<input type="hidden" id="item_id" name="item_id" class="form-control">
						<input type="hidden" id="selected_item" name="selected_item" class="form-control">
						<input type="hidden" id="item_code" name="item_code" class="form-control">
						<input type="hidden" id="item_name" name="item_name" class="form-control">
						<input type="hidden" id="item_particular" name="item_particular" class="form-control">
						<input type="hidden" id="item_unit" name="item_unit" class="form-control">
						<input type="hidden" id="item_price" name="item_price" class="form-control">
						<input type="hidden" id="item_qty_issued" name="item_qty_issued" class="form-control">
					</div>
					<div class='col-md-2 col-sm-2 col-xs-12'>
						<label>Unit/Packaging*</label>
						<select id="item_packaging" name="item_packaging" class="form-control">
							<option value=''>Select</option>
						</select>
					</div>
					<div class='col-md-2 col-sm-4 col-xs-12'>
						<label>Qty to Return*</label>
						<input type="number" id="item_qty" name="item_qty" class="form-control">
					</div>
					<div class='col-md-2 col-sm-2 col-xs-12'>
						<label style='color:white'>XXXXX</label>
						<a class="btn btn-info btn-sm btn-block" id='addbtn' onclick="javascript:addItem();">Add</a>
					</div>
					<div class='clear-fix'><br><br><br>
					<br><br><br></div>
					
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

$( "#msis_no" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>inventory/autocomplete_msis",
	minLength: 1,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		if (ui.content.length === 0) {
			toastr.clear();
			toastr['warning']	=	"No MSIS found!";
		}
		else {
			toastr.clear();
		}
	},
	select: function( event, ui ) {
		$( "#msis_no" ).val( ui.item.label );
		$( "#msis_id" ).val( ui.item.value );
		$( "#company_name" ).val('');
		$( "#company_name" ).val( ui.item.company_name );
		$( "#company_id" ).val('');
		$( "#company_id" ).val( ui.item.company_id );
		$( "#return_from" ).val('');
		$( "#return_from" ).val( ui.item.vessel_id );
		$( "#return_from" ).attr('readonly','readonly');
		$( this ).attr('readonly','readonly');
		return false;
	}
});

$( "#autocomplete_item" ).autocomplete({
	source: function(request, response) {
        $.getJSON(
            "<?php echo HTTP_PATH; ?>inventory/autocomplete_items_for_return/",
            { term:request.term, company:$('#company_id').val(),msis:$('#msis_id').val()}, 
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
		$('#item_id').val( ui.item.value  );
		$("#selected_item" ).val( ui.item.value );
		$("#item_code" ).val( ui.item.item_code );
		$("#item_name" ).val( ui.item.description );
		$("#item_particular" ).val( ui.item.particular );
		$("#item_unit" ).val( ui.item.unit );
		$("#item_price" ).val( ui.item.unit_price );
		$("#item_qty_issued" ).val( ui.item.quantity_issued );
		var item_id = $("#item_id" ).val();

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

$( "#return_by" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>inventory/autocomplete_employee",
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
			$('#return_by').val( ui.item.label );
			return false;
		}
	});

$('#return_from').change(function(){   
  var return_from = $('#return_from').val();
   $( this ).attr('readonly','readonly');
   $( "#msis_no" ).attr('readonly','readonly');
  if(return_from!=""){

	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>/home/get_company_name/"+return_from,
	     success:function(data){
	        var c = $.parseJSON(data);
	        $('#company_name').val(c.company_name);
	        $('#company_id').val(c.company_id);
	     } 	
	  });
  }
});


$('#parser').change(function(){
	var arr = new Array();
	var temp = $(this).val();
	var msis_id = $('#msis_id').val();
	arr = temp.split("-");
	$("#item_id" ).val(arr[0]);
	$("#selected_item" ).val(arr[1]);
	var scanned_item = $("#item_id").val();

	$.ajax({
	    type:"POST",
	    url:"<?php echo HTTP_PATH;?>/inventory/check_item_issued/"+msis_id+"/"+scanned_item,
	    success:function(data){
	    	if(data[0].item_id!=0){
				$("#autocomplete_item" ).val( data[0].item_code+" | "+data[0].item_name+","+data[0].particular+" ("+data[0].unit+", PHP "+data[0].unit_price+")");
				$("#item_id" ).val( data[0].item_id );
				$("#item_code" ).val( data[0].item_code );
				$("#item_name" ).val( data[0].item_name );
				$("#item_particular" ).val( data[0].particular );
				$("#item_unit" ).val( data[0].unit );
				$("#item_price" ).val( data[0].unit_price );
				$("#item_qty_issued" ).val( data[0].quantity_issued );

				 $('#item_packaging').find('option').remove().end();

		    	 $('#item_packaging').append('<option value="">Select</option>').val('');
		    	 $('#item_packaging').append('<option value="'+data[0].unit+'" selected>'+data[0].unit+'</option>').val(data[0].unit);

				$.ajax({
				    type:"POST",
				    url:"<?php echo HTTP_PATH;?>/inventory/get_item_packaging/"+data[0].item_id,
				    success:function(data){

				    	var packagings = data;
						for(var i = 0; i < packagings.length; i++){
				       		var pck = packagings[i];
				       		var option = $('<option />');
						    option.attr('value', pck.packaging).text(pck.packaging);
						    $('#item_packaging').append(option);
				        }


				    }
		    	});

				$('#item_qty').focus();
				if($('#continous').is(':checked')) {
				  	$('#item_qty').val(1);
				  	addItem();
			    }
			}else{
				toastr['error']('Item was not included on that MSIS.', 'ABAS says:');
			}
		}
	});
  	
});


$("#item_qty").keypress(function(e) {
	if(e.which == 13) {
		 addItem();
		 $('#autocomplete_item').val('');
		 $("#item_id" ).val('');
		 $('#selected_item').val('');
		 $("#item_code" ).val('');
		 $("#item_name" ).val('');
		 $("#item_particular" ).val('');
		 $("#item_unit" ).val('');
		 $("#item_packaging" ).val('');
		 $("#item_price" ).val('');
		 $('#item_price_history').val('');
		 $('#item_qty').val('');
      	 $('#parser').val('');
      	 $('#parser').focus();
    }
 });

function addItem(){

	var msg="";
	
	var item = $('#autocomplete_item').val();
	var qty = $('#item_qty').val();
	var qty_issued = $('#item_qty_issued').val();

	if($('#scanner').is(':checked')) {
	}else{
		if(item==""){
			msg+="Item is required.<br>";
		}
	}
	if(qty==""){
		msg+="Quantity is required.<br>";
	}

	//if(qty>qty_issued){
	//	msg+="Quantity to return is more than the quantity issued.<br>";
	//}
	
	if($('#scanner').is(':checked')) {
		 $('#parser').val('');
		 $('#parser').focus();
	}else{
		var item_id_x = $('#item_id').val();
	    if (values.indexOf(item_id_x) >= 0) {
	       msg+="You already added this item.<br/>";
	       $('#autocomplete_item').val('');
	       $('#item_id').val('');
		   $('#selected_item').val('');
		   $("#item_code" ).val('');
		   $("#item_name" ).val('');
		   $("#item_particular" ).val('');
		   $("#item_unit" ).val('');
		   $("#item_packaging" ).val('');
		   $("#item_price" ).val('');
		   $('#item_price_history').val('');
		   $('#item_qty').val('');
		   $('#item_qty_issued').val('');
	    }
	 }

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}else{

		var msis_id = $('#msis_id').val();
		var item_id = $('#item_id').val();
		var item_quantity = $('#item_qty').val();
		var item_code = $("#item_code" ).val();
		var item_name = $("#item_name" ).val();
		var item_price = $("#item_price").val();
		var item_particular = $("#item_particular" ).val();
		var item_unit = $("#item_unit" ).val();
		var item_packaging = $("#item_packaging" ).val();
		var po_id = $("#selected_po" ).val();
		var company = $("#company_id").val();
		var row_entry;
		
		if(company!=''){
			var unitx;
			var packagingx;

			if($('#continous').is(':checked')) {
                item_packaging = item_unit;
            }

			var line_total =  parseFloat(item_price*item_quantity).toFixed(2);

			if($('#scanner').is(':checked')) {
				var new_item = true;
				var qty_index = 0;
				$('.item_x').each(
			      function() { //gets the index of the item row if already has similar item added
			      	var val_item_qty = $(this).val();
			      	if(item_id == val_item_qty){
			      		var idx = $(this).get(0).id
		      			new_item = false;
		      			qty_index = idx;
			      	}
			      });
				if(new_item==true){

					$.ajax({
						url:"<?php echo HTTP_PATH;?>/inventory/get_item_issued/"+msis_id+"/"+item_id,
					    type:"POST",
					    data: { packagingx: '"'+item_packaging+'"' },
					    success:function(data){
					    		
			    			var price_before_convert = item_price*qty_issued;
			    			item_price = data.unit_price;
							line_total =  item_price*item_quantity;
							
							if(line_total <= price_before_convert){

								row_entry = "<td><input type='hidden' class='item_x' id='"+i_check+"' name='item[]' value='"+item_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+item_packaging+"'>"+item_code+"</td><td>"+item_name+"</td><td>"+item_particular+"</td><td><input type='text' class='qtyx' style='border: none;' id='quantity_"+i_check+"' name='quantity[]' value='"+item_quantity+"' readonly></td><td>"+item_packaging+"</td><td><input type='text' style='border: none;' id='price[]' name='price[]' value='"+item_price+"' readonly></td><td><input type='text' style='border: none;' id='line_total' value='"+formatNumber(line_total)+"' readonly></td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs' id='"+item_id+"'>×</a></td>";

								$('#row_item'+i_check).html(row_entry);
								$('#table_items').append('<tr class="tbl_row_item" id="row_item'+(i_check+1)+'"></tr>');
								i_check++; 

								values.push(item_id);
							}else{
				       			toastr['error']('Quantity to return is more than the quantity issued.', 'ABAS says:');
				       			$('#parser').val('');
								$('#parser').focus();
				       	 	}
								
						}
					});

				}else{

					var old_qty = $('#quantity_'+qty_index).val();
					var new_qty;
					if($('#continous').is(':checked')) {
						new_qty = parseFloat(old_qty) + 1;
					}else{
						new_qty = parseFloat(old_qty) + parseFloat(item_quantity);
					}

					$.ajax({
					    type:"POST",
					    url:"<?php echo HTTP_PATH;?>/inventory/get_item_issued/"+msis_id+"/"+item_id,
					     data: { packagingx: '"'+item_packaging+'"' },
					    success:function(data){
					    	var price_before_convert = item_price*qty_issued;
					    	var line_total =  parseFloat(data.unit_price*new_qty).toFixed(2);

					    	if(line_total <= price_before_convert){
					    		$('#quantity_'+qty_index).val(new_qty);
								$('#line_total_'+qty_index).val(line_total);
								values.push(item_quantity_id);
					    	}else{
					       		toastr['error']('Quantity to return is more than the quantity issued.', 'ABAS says:');
					       		$('#parser').val('');
								$('#parser').focus();
					        }
					    }
					});
					
				}
			}else{
				$.ajax({
					url:"<?php echo HTTP_PATH;?>/inventory/get_item_issued/"+msis_id+"/"+item_id,
				    type:"POST",
				    data: { packagingx: '"'+item_packaging+'"' },
				    success:function(data){

				    	var price_before_convert = item_price*qty_issued;
		    			item_price = data.unit_price;
						line_total =  item_price*item_quantity;
						
						if(line_total <= price_before_convert){
			    			row_entry = "<td><input type='hidden' class='item_x' id='"+i_check+"' name='item[]' value='"+item_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+item_packaging+"'>"+item_code+"</td><td>"+item_name+"</td><td>"+item_particular+"</td><td><input type='text' class='qtyx' style='border: none;' id='quantity_"+i_check+"' name='quantity[]' value='"+item_quantity+"' readonly></td><td>"+item_packaging+"</td><td><input type='text' style='border: none;' id='price[]' name='price[]' value='"+item_price+"' readonly></td><td><input type='text' style='border: none;' id='line_total' value='"+formatNumber(line_total)+"' readonly></td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs' id='"+item_id+"'>×</a></td>";

								$('#row_item'+i_check).html(row_entry);
								$('#table_items').append('<tr class="tbl_row_item" id="row_item'+(i_check+1)+'"></tr>');
								i_check++; 

							values.push(item_id);
						}else{
				       		toastr['error']('Quantity to return is more than the quantity issued.', 'ABAS says:');
				        }
			    		
				    }
				});
			} 
		}else{
			toastr['error']('Please select first the company.', 'ABAS says:');
		}

		$('#autocomplete_item').val('');
		$('#item_id').val('');
		$('#item_quantity_id').val('');
		$('#selected_item').val('');
		$("#item_code" ).val('');
		$("#item_name" ).val('');
		$("#item_particular" ).val('');
		$("#item_unit" ).val('');
		$("#item_packaging" ).val('');
		$("#item_price" ).val('');
		$('#item_price_history').val('');
		$('#item_qty').val('');
		$('#item_qty_issued').val('');

		if($('#scanner').is(':checked')) {
			$('#parser').val('');
			$('#parser').focus();
		}
		
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
	
function checkForm() {
	var msg="";

	var msis_id = $('#msis_id').val();
	var return_date = $('#return_date').val();
	var return_from = $('#return_from').val();
	var return_to = $('#return_to').val();
	var return_by = $('#return_by').val();

	if(msis_id==''){
		msg+="MSIS is required.<br/>";
	}
	if(return_date==''){
		msg+="Return Date is required.<br/>";
	}
	if(return_from==''){
		msg+="Returned From is required.<br/>";
	}
	if(return_to==''){
		msg+="Return To is required.<br/>";
	}
	if(return_by==''){
		msg+="Returned By is required.<br/>";
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
			title: "Return",
			size: 'small',
		    message: "Are you sure you want to return these items?",
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

			        document.getElementById("return_form").submit();
			        return true;
		    	}
		    }
		});

	}
}
</script>