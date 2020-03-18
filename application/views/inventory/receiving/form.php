<?php
$vessel_code_options = "";
if(!empty($vessels)) {
	foreach($vessels as $v){
		$vessel_code_options	.=	"<option value='".$v->id."'>".$v->name." </option>";
	}
}

?>

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Add Receiving Report
		</h2>
	</div>
</div>

<form id='receiving_form' role='form' action='<?php echo HTTP_PATH."inventory/receiving/insert"?>' method='POST' enctype='multipart/form-data'>
	<div class='panel-body'>
		<div class='panel-group' id='RRFormDivider' role='tablist' aria-multiselectable='true'>
			<div class='panel panel-info'>
				<div class='panel-heading' role='tab' id='general'>	
					<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#RRGeneral' aria-expanded='true' aria-controls='RRGeneral'>
					General Information
					<span class='glyphicon glyphicon-chevron-down pull-right'></span>
					</a>
				</div>
				<div id='RRGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='RRGeneral'>
					<div class='panel-body'>
						<div class='col-md-3 col-sm-6 col-xs-12'>
							<label>PO Transaction Code No.*</label>
				            	<input type="text" class="form-control input-sm"  name="po_id" id="po_id" required/>
				            	<input type="hidden" class="form-control input-sm"  name="selected_po" id="selected_po" required/>
						</div>

						<div class='col-md-9 col-sm-12 col-xs-12'>
							<label>Company</label>
				            	<input type="text" class="form-control input-sm"  name="company" id="company" readonly/>
				            	<input type="hidden" class="form-control input-sm"  name="selected_company" id="selected_company"/>
						</div>

						<div class='col-md-3 col-sm-6 col-xs-12'>
							<label>Delivery Date*</label>
								<input class="form-control input-sm" type="date" name="delivery_date" id="delivery_date" required />
						</div>

						<div class='col-md-9 col-sm-12 col-xs-12'>
							<label>Supplier</label>
				            	<input type="text" class="form-control input-sm"  name="supplier" id="supplier" readonly/>
				            	<input type="hidden" class="form-control input-sm"  name="selected_supplier" id="selected_supplier"/>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12'>
							<label>Received By*</label>
								<input class="form-control input-sm" type="text" name="received_by" id="received_by" required/>
						</div>

						<div class='col-md-3 col-sm-4 col-xs-12'>
							<label>Sales Invoice No.*</label>
								<input class="form-control input-sm" type="text" name="sales_invoice_no" id="sales_invoice_no" required/>
						</div>

						<div class='col-md-3 col-sm-2 col-xs-12'>
							<label>Delivery Receipt No.*</label>
								<input class="form-control input-sm" type="text" name="delivery_no" id="delivery_no" required/>
						</div>

				        <div class='col-md-12 col-sm-12 col-xs-12'>
							<label>Remarks</label>
				            <textarea class="form-control input-sm" name="remarks" id="remarks"/>
				            <hr>
						</div>
						 
				        <div class='col-md-7 col-sm-7 col-xs-12'>

							<input  type="checkbox" name="direct_del" id="direct_del" value="1" 
								onclick="
				                        if($('#direct_del').is(':checked')) {
				                            $('#delivered_to').show();
				                        } else {
				                            $('#delivered_to').hide();
			                            	$('#issued_for').val('');
				                        }             
				                "/>
				             <label  for="direct_del">  Check if Direct Delivery</label>		
						</div>
				           
			            <div class='col-md-5 col-sm-5 col-xs-12' id="delivered_to" style="display:none">
			                <label for="issued_for">Delivered to*</label>
			                    <select class="form-control input-sm" name="issued_for" id="issued_for">
			                        <option value="">Select</option>
			                    </select>
			            </div>

				         <div class='col-md-7 col-sm-7 col-xs-12'>
							<input  type="checkbox" name="is_notice_of_discrepancy" id="is_notice_of_discrepancy" value="1"
			                 onclick="    
			                        if($('#is_notice_of_discrepancy').is(':checked')) {
			                            //$('#vehicle_div').show();
			                            //$('#driver_div').show();
			                            //$('#type_div').show();
			                            //$('#nod_div').show();
			                            if($('#po_id').val()==''){
			                            	$('#is_notice_of_discrepancy').prop('checked',false);
			                            }
			                        } else {
			                            //$('#vehicle_div').hide();
			                            //$('#driver_div').hide();
			                            //$('#type_div').hide();
			                            //$('#plate_no').val('');
			                            //$('#driver').val('');
			                            //$('.rd1').prop('checked', true );
			                            //$('.rd2').prop('checked', false );
			                            //$('.rd3').prop('checked', false );
			                            //$('.rd4').prop('checked', false );
			                            //$('#nod_div').hide();
			                            $('#nod_id').val('');
			                        }    
			                        "/>
			                <label for="notice_of_discrepancy"> Check if has Notice of Discrepancy</label>
			                <input type="hidden" id="nod_id" name="nod_id" class="form-control">
						</div>
						<!--<div class='col-md-5 col-sm-5 col-xs-12' id="nod_div" style="display:none">
								<label>NOD Transaction Code No.*</label>
								<select id="nod_id" name="nod_id" class='form-control'>
									<option value=''>Select</option>
								</select>
						</div>-->
			            <div class='col-md-4 col-sm-4 col-xs-12' id="vehicle_div" style="display:none">
								<label>Vehicle Plate No.*</label>
								<input type="text" id="plate_no" name="plate_no" class='form-control'>
						</div>
						<div class='col-md-8 col-sm-8 col-xs-12' id="driver_div" style="display:none">
							<label>Driver's Name*</label>
							<input type="text" id="driver" name="driver" class='form-control'>
						</div>
						<!--<div class='col-md-8 col-sm-8 col-xs-12' id="type_div" style="display:none">
							<label>Reason for Discrepancy</label><br>
							<input type="radio" name="reason" class="rd1" value='Under Delivery' checked>Under Delivery &nbsp  &nbsp
						 	<input type="radio" name="reason" class="rd2" value='Over Delivery'>Over Delivery &nbsp  &nbsp <br>
							<input type="radio" name="reason" class="rd3" value='Damaged'>Damaged &nbsp  &nbsp &nbsp  &nbsp &nbsp  &nbsp
							<input type="radio" name="reason" class="rd4" value='Not in conformity with specifications'>Not in conformity with specifications
						</div>-->
					</div>
				</div>
			</div>
		</div>
		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='details'>	
				<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#RRDetails' aria-expanded='true' aria-controls='RRDetails'>
				Details
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>
			<div id='RRDetails' class='panel-collapse collapse' role='tabpanel' aria-labelledby='RRDetails'>
				<div class='panel-body'>
					<div class='col-md-8 col-sm-6 col-xs-12'>
						<label>Item Name*</label>
						<input type="text" id="autocomplete_item" name="autocomplete_item" class="form-control">
						<input type="hidden" id="selected_item" name="selected_item" class="form-control">
						<input type="hidden" id="item_code" name="item_code" class="form-control">
						<input type="hidden" id="item_name" name="item_name" class="form-control">
						<input type="hidden" id="item_particular" name="item_particular" class="form-control">
						<input type="hidden" id="item_unit" name="item_unit" class="form-control">
						<input type="hidden" id="item_price" name="item_price" class="form-control">
					</div>
					<div class='col-md-2 col-sm-4 col-xs-12'>
						<label>Qty Received*</label>
						<input type="number" id="item_qty" name="item_qty" class="form-control">
					</div>
					<div class='col-md-2 col-sm-2 col-xs-12'>
						<label style='color:white'>XXXXX</label>
						<a class="btn btn-info btn-sm btn-block" onclick="javascript:addItem();">Add</a>
					</div>
					<div class='clear-fix'></div>
					<br><br><br><br><br>
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

$( "#po_id" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>inventory/po_data",
	minLength: 2,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$( "#po_id" ).val( ui.item.label );
		$( "#selected_po" ).val( ui.item.value );
		$( "#company" ).val( ui.item.company );
		$( "#selected_company" ).val( ui.item.company_id );
		$( "#supplier" ).val( ui.item.supplier );
		$( "#selected_supplier" ).val( ui.item.supplier_id );
		$( "#po_id" ).prop('readonly',true)
		return false;
	}
});

$( "#autocomplete_item" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>inventory/item_data",
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
		$("#item_code" ).val( ui.item.item_code );
		$("#item_name" ).val( ui.item.description );
		$("#item_particular" ).val( ui.item.particular );
		$("#item_unit" ).val( ui.item.unit );
		$("#item_qty").focus();
		return false;
	}
});

$( "#received_by" ).autocomplete({
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
		$('#received_by').val( ui.item.label );
		return false;
	}
});


$('#direct_del').change(function() {
	var company = $('#selected_company').val();
	$.ajax({
         type:"POST",
         url:"<?php echo HTTP_PATH;?>/inventory/get_vessels_by_company/"+company,
         success:function(data){

            var vessel_names = data;//$.parseJSON(data);

            $("#issued_for").find('option').remove().end().append('<option value="">Select</option>').val('');
            for(i in vessel_names){
                var vessel = vessel_names[i];
                var option = $('<option />');
                option.attr('value', vessel.id).text(vessel.name);
                $("#issued_for").append(option);
            }

         }
      });
});

/*$( "#is_notice_of_discrepancy" ).change(function(){
	if($("#is_notice_of_discrepancy").is(':checked')){
		$("#table_items > tbody").html("");
		$('#table_items').append('<tr class="tbl_row_item" id="row_item0"></tr>');
		$('#table_items').append('<tr class="tbl_row_item" id="row_item1"></tr>');
		i_check=1;
	}
});*/

$('#is_notice_of_discrepancy').change(function() {
	var po = $('#selected_po').val();
	if(this.checked && po>0) {
        $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH?>/inventory/checkNoticeOfDiscrepancy/"+po,
		     success:function(data){
		        if(data==null){
		        	msg = 'Approved Notice of Discrepancy cannot be found for this PO.<br>';
		        	toastr['warning'](msg, "ABAS says:");
		        	$('#is_notice_of_discrepancy').prop('checked', false);
		        }else{
		        	$('#nod_id').val(data.id);
		        	$("#table_items tbody").empty();
		        	$.ajax({
						type:"POST",
						url:"<?php echo HTTP_PATH;?>inventory/loadNoticeOfDiscrepancyItems/"+data.id,
						success:function(data){
							var table_data = $.parseJSON(data);
							var line_total = 0;
							var packaging;
							var qty_received =0;
						    $.each(table_data, function(idx, elem){

						    	if(elem.packaging==''){
						    		packaging = elem.unit;
						    	}else{
						    		packaging = elem.packaging;
						    	}


						       if(elem.remarks=='Over Delivery w/ Freebies'){

						    		line_total = parseFloat(elem.unit_price * elem.quantity_po).toFixed(2);

						    		$("#table_items").append("<tr><td><input type='hidden' id='item[]' name='item[]' class='item_x' value='"+elem.item_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+elem.unit+"'><input type='hidden' id='packaging[]' name='packaging[]' value='"+packaging+"'><input type='hidden' id='price[]' name='price[]' value='"+elem.unit_price+"'><input type='hidden' id='quantity[]' name='quantity[]' value='"+elem.quantity_po+"'>"+elem.item_code+"</td><td>"+elem.item_name+"</td><td>"+elem.item_particular+"</td><td>"+elem.quantity_po+"</td><td>"+packaging+"</td><td>"+formatNumber(elem.unit_price)+"</td><td>"+formatNumber(line_total)+"</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs disabled' id='"+elem.item_id+"'>×</a></td></tr>");
						    	

						    		var count_freebies = elem.quantity_received - elem.quantity_po;

								  	 $("#table_items").append("<tr><td><input type='hidden' id='item[]' name='item[]' class='item_x' value='"+elem.item_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+elem.unit+"'><input type='hidden' id='packaging[]' name='packaging[]' value='"+packaging+"'><input type='hidden' id='price[]' name='price[]' value='0'><input type='hidden' id='quantity[]' name='quantity[]' value='"+count_freebies+"'>"+elem.item_code+"</td><td>"+elem.item_name+"</td><td>"+elem.item_particular+"</td><td>"+count_freebies+"</td><td>"+packaging+"</td><td>(freebies)</td><td>0</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs disabled' id='"+elem.item_id+"'>×</a></td></tr>");
						    		

						    	}else{

						    		line_total = parseFloat(elem.unit_price * elem.quantity_received).toFixed(2);

						    		 $("#table_items").append("<tr><td><input type='hidden' id='item[]' name='item[]' class='item_x' value='"+elem.item_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+elem.unit+"'><input type='hidden' id='packaging[]' name='packaging[]' value='"+packaging+"'><input type='hidden' id='price[]' name='price[]' value='"+elem.unit_price+"'><input type='hidden' id='quantity[]' name='quantity[]' value='"+elem.quantity_received+"'>"+elem.item_code+"</td><td>"+elem.item_name+"</td><td>"+elem.item_particular+"</td><td>"+elem.quantity_received+"</td><td>"+packaging+"</td><td>"+formatNumber(elem.unit_price)+"</td><td>"+formatNumber(line_total)+"</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs disabled' id='"+elem.item_id+"'>×</a></td></tr>");
						    	}

						        values.push(elem.item_id);
						    });
						}
					});

		        }
		     },
		     error: function (request, status, error) {
		        alert(request.responseText);
		     }
		  });
	}else{
		$("#table_items tbody").empty();
		values = [];
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
	
    var item_idx = $('#selected_item').val();
    if(values.includes(item_idx)==true){
       msg+="You already added this item.<br/>";
       $('#autocomplete_item').val('');
	   $('#selected_item').val('');
	   $("#item_code" ).val('');
	   $("#item_name" ).val('');
	   $("#item_particular" ).val('');
	   $("#item_unit" ).val('');
	   $('#item_qty').val('');
    }

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}else{

		var item_id = $('#selected_item').val();
		var item_quantity = $('#item_qty').val();
		var item_code = $("#item_code" ).val();
		var item_name = $("#item_name" ).val();
		var item_particular = $("#item_particular" ).val();
		var item_unit = $("#item_unit" ).val();
		var po_id = $("#selected_po" ).val();
		var item_price;
		var packaging;

		$.post('<?php echo HTTP_PATH."inventory/checkPOItemPrice/"; ?>',
			{ 'pono':po_id, 'item_id':item_id },
			function(result) {
				item_price = parseFloat(result.unit_price).toFixed(2);
				if(result.packaging==''){
		     		packaging = item_unit;
		     	}else{
		     		packaging = result.packaging;
		     	}
			}
     	);

		var row_entry;

		 $.post('<?php echo HTTP_PATH."inventory/checkPOItem/"; ?>',
              { 'pono':po_id, 'qty':item_quantity, 'item_id':item_id },
                    function(result) {

                        if(result == 1){
                        	if(typeof item_price != 'undefined'){
                        		var line_total =  parseFloat(item_price*item_quantity).toFixed(2);
	                        	/*if($('#is_notice_of_discrepancy').is(':checked')){
	                        		row_entry = "<td><input type='hidden' id='item[]' name='item[]' class='item_x' value='"+item_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+item_unit+"'><input type='hidden' id='price[]' name='price[]' value='"+item_price+"'><input type='hidden' id='quantity[]' name='quantity[]' value='"+item_quantity+"'>"+item_code+"</td><td>"+item_name+"</td><td>"+item_particular+"</td><td>"+item_quantity+"</td><td>"+item_unit+"</td><td>"+formatNumber(item_price)+"</td><td>"+formatNumber(line_total)+"</td><td align='center'><select id='reason[]' name='reason[]'><option value='Ok'>Ok</option><option value='Under Delivery'>Under Delivery</option><option value='Over Delivery'>Over Delivery</option><option value='Damaged'>Damaged</option><option value='Not in cormity with specifications'>Not in conformity w/ spec</option></select></td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a></td>";
	                        	}else{*/
	                        		row_entry = "<td><input type='hidden' id='item[]' name='item[]' class='item_x' value='"+item_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+item_unit+"'><input type='hidden' id='packaging[]' name='packaging[]' value='"+packaging+"'><input type='hidden' id='price[]' name='price[]' value='"+item_price+"'><input type='hidden' id='quantity[]' name='quantity[]' value='"+item_quantity+"'>"+item_code+"</td><td>"+item_name+"</td><td>"+item_particular+"</td><td>"+item_quantity+"</td><td>"+packaging+"</td><td>"+formatNumber(item_price)+"</td><td>"+formatNumber(line_total)+"</td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs' id='"+item_id+"'>×</a></td>";
	                        	//}
	                        
                        		$('#row_item'+i_check).html(row_entry);
								$('#table_items').append('<tr class="tbl_row_item" id="row_item'+(i_check+1)+'"></tr>');
								i_check++; 
								values.push(item_id);
							}else{
							 toastr['warning']('Please try again.', 'ABAS says:');
							}
                        }else if(result == 0){
                        	/*var line_total =  parseFloat(item_price*item_quantity).toFixed(2);
                        	if($('#is_notice_of_discrepancy').is(':checked')){
                        		row_entry = "<td><input type='hidden' id='item[]' name='item[]' class='item_x' value='"+item_id+"'><input type='hidden' id='unit[]' name='unit[]' value='"+item_unit+"'><input type='hidden' id='price[]' name='price[]' value='"+item_price+"'><input type='hidden' id='quantity[]' name='quantity[]' value='"+item_quantity+"'>"+item_code+"</td><td>"+item_name+"</td><td>"+item_particular+"</td><td>"+item_quantity+"</td><td>"+item_unit+"</td><td>"+formatNumber(item_price)+"</td><td>"+formatNumber(line_total)+"</td><td align='center'><select id='reason[]' name='reason[]'><option value='Ok'>Ok</option><option value='Under Delivery'>Under Delivery</option><option value='Over Delivery'>Over Delivery</option><option value='Damaged'>Damaged</option><option value='Not in cormity with specifications'>Not in conformity w/ spec</option></select></td><td align='center'><a class='btn-remove-row btn btn-danger btn-xs'>×</a></td>";

                        		$('#row_item'+i_check).html(row_entry);
								$('#table_items').append('<tr class="tbl_row_item" id="row_item'+(i_check+1)+'"></tr>');
								i_check++; 

                            }else{*/
                            	 toastr['error']('Quantity entered exceeds the order or the remaining balance.', 'ABAS says:');
                            //}
                        }else if(result == 2){
                        
                             toastr['error']('Item entered is not included in this PO.', 'ABAS says:');
                        }else if(result == 3){
                        	
                             toastr['warning']('This item is already fully delivered in this PO.', 'ABAS says:');
                        }
          			}
          );

		$('#autocomplete_item').val('');
		$('#selected_item').val('');
		$("#item_code" ).val('');
		$("#item_name" ).val('');
		$("#item_particular" ).val('');
		$("#item_unit" ).val('');
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
	
function checkForm() {
	var msg="";

	var po = $('#selected_po').val();
	var delivery_date = $('#delivery_date').val();
	var sales_invoice_no = $('#sales_invoice_no').val();
	var received_by = $('#received_by').val();

	if($('#direct_del').is(':checked')) {
		var issued_for = $('#issued_for').val();
        if(issued_for==''){
        	msg+="Delivered to is required.<br/>";
        }
    }
	if(po==''){
		msg+="PO is required.<br/>";
	}
	if(delivery_date==''){
		msg+="Delivery Date is required.<br/>";
	}
	if(sales_invoice_no==''){
		msg+="Sales Invoice No. is required.<br/>";
	}
	if(received_by==''){
		msg+="Received By is required.<br/>";
	}

	/*if($('#is_notice_of_discrepancy').is(':checked')) {
		var vehicle = $('#plate_no').val();
		var driver = $('#driver').val();
		if(vehicle==''){
			msg+="Vehicle Plate No. is required.<br/>";
		}
		if(driver==''){
			msg+="Driver is required.<br/>";
		}
		var nod = $('#nod_id').val();
		if(nod==''){
			msg+="NOD No. is required.<br/>";
		}
	}*/

    var row_count =$("#table_items > tbody > tr").length;
	if (row_count<=1){
		msg+="Please add item(s) before submitting! <br/>";
	}

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Receiving",
			size: 'small',
		    message: "Are you sure you want to receive these items?",
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

			        document.getElementById("receiving_form").submit();
			        return true;
		    	}
		    }
		});

	}
}

</script>