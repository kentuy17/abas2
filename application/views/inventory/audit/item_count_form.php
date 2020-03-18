<style>
	div.transbox {
	  margin-top: 80px;
	  margin-left: 180px;
	  border: 2px solid red;
	  position: absolute;

	}
</style>
<div class="panel panel-info" >
	<div class="panel-heading" style="min-height">
	   <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeQR()">×</button>
	   <strong>Add Actual Item Count</strong>
	</div>
</div>

		<div class="panel-body">
				<div class="col-sm-12 col-xs-12">
					<button id="change" class="btn btn-warning btn-sm">Change camera</button>
	                <code>Start Scanning</code> <span class="feedback" style='font-size: 10px'></span>
	            </div>
				<div class="col-sm-12 col-xs-12 col-md-4" id="qr" style="width: 100%; height: 320px; border: 0px solid silver;border:thin  #CCCCCC solid;background-color: #eee">
				</div>
				<div class="transbox col-sm-12 col-xs-12 col-md-4 hidden" >
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<br><br><br><br><br><br><br><br><br><br>
                </div>

                <?php
					$attributes = array('id'=>'item_count_form','role'=>'form');
					echo form_open_multipart(HTTP_PATH.'inventory/audit/insert_item_count',$attributes);
					echo $this->Mmm->createCSRF();
				?>

				<div class='col-sm-3 col-xs-3'>
					<label>Item Code:</label>
					<input type='text' class='form-control' id='item_code' readonly>
					<input type='hidden' class='form-control' id='audit_id'  name='audit_id' value='<?php echo $audit->id;?>'>
					<input type='hidden' class='form-control' id='item_id' name='item_id'>
					<input type='hidden' class='form-control' id='category'  name='category' value='<?php echo $audit->type_of_inventory;?>'>
					<input type='hidden' class='form-control' id='location'  name='location' value='<?php echo $audit->location;?>'>
				</div>
				<div class='col-sm-9 col-xs-9'>
					<label>Item Description*:</label>
					<input type='text' class='form-control' id='item_name'>
				</div>
				
				<div class='col-sm-4 col-xs-4'>
					<label>Unit of Measurement:</label>
					<input type='text' class='form-control' id='unit' readonly>
				</div>
				<div class='col-sm-4 col-xs-4'>
					<label>Unit Cost:</label>
					<input type='text' class='form-control' id='unit_price' readonly>
				</div>
				<div class='col-sm-4 col-xs-4'>
					<label>Quantity Per Books:</label>
					<input type='number' class='form-control' id='quantity_per_book' name='quantity_per_book' readonly>
				</div>
				<div class='col-sm-4 col-xs-4'>
					<label>Quantity Per Count*:</label>
					<input type='number' class='form-control' id='quantity_per_count' name='quantity_per_count' required>
				</div>
				<div class='col-sm-8 col-xs-8'>
					<label>Shelf No./Location:</label>
					<input type='text' class='form-control' id='shelf_number' name='shelf_number' >
				</div>
				<div class='col-sm-12 col-xs-12'>
					<label>Remarks:</label>
					<input type='text' class='form-control' id='remarks' name='remarks' >
				</div>
		</div>
</div>
		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<span class='pull-right'>
				<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: validateForm()' />
				 <button type="button" class="btn btn-danger btn-m" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeQR()">Discard</button>
			</span>
		</div>
		<br><br><br>
</form>
<script src="<?php echo LINK ?>assets/qr_code_scanner/jsqrcode-combined.js"></script>
<script src="<?php echo LINK ?>assets/qr_code_scanner/html5-qrcode.js"></script>
<script type="text/javascript">

function validateForm(){
	var qty_per_count = $('#quantity_per_count').val();
	var qty_flag = 0;
	var item_flag = 0;
	var audit_flag = 0;
	var msg='';
	var audit_id = $('#audit_id').val();
	var item_id = $('#item_id').val();

	 $.ajax({
         type:"POST",
         url:"<?php echo HTTP_PATH;?>inventory/checkItemIfAudited/"+audit_id+"/"+item_id,
         success:function(data){
	 		if(data == 1){
	 			audit_flag =1;
	 			msg += 'You already added this item on the count sheet.<br>';
	 		}
         }
     });

     if(item_id==0 || item_id=='') {
		item_flag = 1;
		msg += 'Please select an item.<br>';
	}

	if(qty_per_count<0 || qty_per_count=='') {
		qty_flag = 1;
		msg += 'Please provide actual quantity per count.<br>';
	}


	if(qty_flag==0 && audit_flag==0 && item_flag==0) {
    	$('body').addClass('is-loading'); 
		$('#modalDialogNorm').modal('toggle'); 
		document.getElementById("item_count_form").submit();
		return true;
	}else{
		toastr['error'](msg, "ABAS says:");
	}
}

$(document).ready(function() {

	var category = $('#category').val();
	var location = $('#location').val();

	$("#item_code").val('');
    $("#item_name").val('');
    $("#particular").val('');
    $("#delivery_date").val('');
    $("#supplier").val('');
    $("#unit_price").val('');
    $("#quantity_per_book").val('');
    $("#quantity_per_count").val('');
    $("#shelf_number").val('');

    $("code").html('Scanning QR');
    $('#qr').html5_qrcode(function(data){
             // do something when code is read
             $(".feedback").html('Code scanned as: ' +data);
             var item = data.split(",");

             $("#unit_price").val(item[1]);

             if(item[2]==undefined){
             	item[2] = '0';
             }

             $.ajax({
                 type:"POST",
                 url:"<?php echo HTTP_PATH;?>inventory/qr_code_read_delivery_data/"+item[0]+"/"+item[2]+"/"+location,
                 success:function(data){
                    var item = $.parseJSON(data);  
       					$("#item_id").val('');
                    	$("#item_code").val('');
		    			$("#item_name").val('');
					    $("#unit").val('');
					    $("#unit_price").val('');
					    $("#quantity_per_book").val('');
					    $("#quantity_per_count").val('');
					    $("#shelf_number").val('');
					    $("#remarks").val('');

					    if(category==item[0].category){
						    $("#item_id").val(item[0].id);
		                    $("#item_code").val(item[0].item_code);
		                    $("#item_name").val(item[0].description);
		                    $("#particular").val(item[0].particular);
		                    $("#unit").val(item[0].unit);
		                     $("#unit_price").val(item[0].unit_price);
		                    $("#quantity_per_book").val(item['quantity']);
	                    }else{
	                    	toastr.clear();
							toastr["warning"]("Item not included on this type of inventory!", "ABAS Says");
	                    }
                 }
             });

              $("code").html('QR Scanned');
        },
        function(error){
            //show read errors 
            $(".feedback").html('Error: ' +error)
            $("code").html('Scanning QR');
        }, function(videoError){
            //the video stream could be opened
            $(".feedback").html('Video Error');
            $("code").html('Scanning QR');
        }
    );
    $("#stop").on('click', function() {
		$("#qr").html5_qrcode_stop();
		$("code").html('Start Scanning');


	});
	$("#change").on('click', function() {
		$("#qr").html5_qrcode_changeCamera();
	});

	
	$( "#item_name" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>home/autocomplete_item/"+category+"/"+location,
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				if (ui.content.length === 0) {
					toastr.clear();
					toastr["warning"]("Item not found on that category!", "ABAS Says");
				}
				else {
					toastr.clear();
				}
			},
			select: function( event, ui ) {
				
				$("#item_code").val(ui.item.item_code);
				$("#item_name" ).val( ui.item.label );
				$("#item_id" ).val( ui.item.value );
				$("#quantity_per_book" ).val( ui.item.qty );
				$("#unit").val(ui.item.unit);
				$("#unit_price").val(ui.item.unit_price);
				return false;
			}
		});
		 $( "#request_date" ).datepicker();
});




function closeQR(){
    $("#qr").html5_qrcode_stop();
}


</script>