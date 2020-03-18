<style>
	div.transbox {
	  margin-top: 80px;
	  margin-left: 180px;
	  border: 2px solid red;
	  position: absolute;

	}
</style>
<div class="panel panel-default" >
	<div class="panel-heading" style="min-height">
	   <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeQR()">×</button>
	   <strong>QR Code Scanner</strong>
	</div>
</div>
		<div class="panel-body">
			<?php echo $this->Mmm->createCSRF() ?>
			
				<div class="col-sm-12 col-xs-12">
					<button id="change" class="btn btn-warning btn-sm">Change camera</button>
	                <code>Start Scanning</code> <span class="feedback" style='font-size: 10px'></span>
	            </div>
				<div class="col-sm-12 col-xs-12 col-md-4" id="qr" style="width: 100%; height: 320px; border: 0px solid silver;border:thin  #CCCCCC solid;background-color: #eee">
				</div>
				<div class='col-sm-12 col-xs-12'>
					<label>Company:</label>
					<input type='text' class='form-control' id='companyx' readonly>
				</div>
				<div class='col-sm-4 col-xs-12'>
					<label>Item Code:</label>
					<input type='text' class='form-control' id='item_code' readonly>
				</div>
				<div class='col-sm-8 col-xs-12'>
					<label>Brand:</label>
					<input type='text' class='form-control' id='brand' readonly>
				</div>
				<div class='col-sm-12 col-xs-12'>
					<label>Item Name:</label>
					<input type='text' class='form-control' id='item_name' readonly>
				</div>
				<div class='col-sm-12 col-xs-12'>
					<label>Particulars:</label>
					<input type='text' class='form-control' id='particular' readonly>
				</div>
				<div class='col-sm-4 col-xs-4'>
					<label>Unit:</label>
					<input type='text' class='form-control' id='unit' readonly>
				</div>
				<div class='col-sm-4 col-xs-4'>
					<label>Unit Price:</label>
					<input type='text' class='form-control' id='unit_price' readonly>
				</div>
				<div class='col-sm-4 col-xs-4'>
					<label>Quantity:</label>
					<input type='text' class='form-control' id='quantity' readonly>
				</div>
				<div class='col-sm-4 col-xs-4'>
					<label>Date Delivered:</label>
					<input type='text' class='form-control' id='delivery_date' readonly>
				</div>
				<div class='col-sm-8 col-xs-8'>
					<label>Location:</label>
					<input type='text' class='form-control' id='locationx' readonly>
				</div>
				<div class='col-sm-12 col-xs-12'>
					<label>Supplier:</label>
					<input type='text' class='form-control' id='supplier' readonly>
				</div>
		</div>
		<br><br>
	</div>
<script src="<?php echo LINK ?>assets/qr_code_scanner/jsqrcode-combined.js"></script>
<script src="<?php echo LINK ?>assets/qr_code_scanner/html5-qrcode.js"></script>
<script>
$(document).ready(function() {

	$("#item_code").val('');
    $("#item_name").val('');
    $("#brand").val('');
    $("#particular").val('');
    $("#delivery_date").val('');
    $("#supplier").val('');
    $("#unit_price").val('');
    $("#companyx").val('');
    $("#locationx").val('');

    $("code").html('Scanning QR');
    $('#qr').html5_qrcode(function(data){
             // do something when code is read
             $(".feedback").html('Code scanned as: ' +data);
             var item = data.split("-");

             $.ajax({
                 type:"POST",
                 url:"<?php echo HTTP_PATH;?>inventory/qr_code_scanner_data/"+item[0]+"/"+item[1],
                 success:function(data){
                    var inv = $.parseJSON(data);  
                    	$("#item_code").val('');
		    			$("#item_name").val('');
		    			$("#brand").val('');
					    $("#particular").val('');
					    $("#delivery_date").val('');
					    $("#supplier").val('');

	                    $("#item_code").val(inv['item'][0].item_code);
	                    $("#item_name").val(inv['item'][0].item_name);
	                    $("#brand").val(inv['item'][0].brand);
	                    $("#particular").val(inv['item'][0].particular);
	                    $("#unit").val(inv['item']['inventory_qty'][0].unit);
	                  	$("#unit_price").val(inv['item']['inventory_qty'][0].unit_price);

	                  	$("#locationx").val(inv['item']['inventory_qty'][0].location);
	                  	$("#companyx").val(inv['item']['company'].name);

	                   var quantity = (inv['item']['inventory_qty'][0].quantity - inv['item']['inventory_qty'][0].quantity_issued);
	                   $("#quantity").val(quantity);

                     if(inv['item']['delivery']!=undefined){
                     	$("#delivery_date").val(inv['item']['delivery'][0].tdate);
                     	$("#supplier").val(inv['item']['supplier'].name);
                     	
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
});



function closeQR(){
    $("#qr").html5_qrcode_stop();
}
</script>