<?php
	$return_no = "";
	$return_date = "";
	$return_by = "";
	$return_for = "";
	$id = "";
	$items = "";
	$location= $_SESSION['abas_login']['user_location'];
	$remark = "";
	if(isset($item)){
		$item_code = $item[0]['item_code'];
		$description = $item[0]['description'];
		$particular = $item[0]['particular'];
		$unit = $item[0]['unit'];
		$unit_cost = $item[0]['unit_price'];
		$id = $item[0]['id'];
		$category = $item[0]['category'];
		$classification = "";
		$classification_name = "";
		$reorder = $item[0]['reorder_level'];
		$qty = $item[0]['qty'];
		$location = $item[0]['location'];
		$stock_location = $item[0]['stock_location'];
	}
    $user_location = $_SESSION['abas_login']['user_location'];
?>

<script src="<?php echo LINK ?>assets/qr_code_scanner/jsqrcode-combined.js"></script>
<script src="<?php echo LINK ?>assets/qr_code_scanner/html5-qrcode.js"></script>

<script type="text/javascript">
		$(document).ready(function () {
				$( "#autocomplete" ).autocomplete({
					source: "<?php echo HTTP_PATH; ?>inventory/item_data",
					minLength: 2,
					search: function(event, ui) {
						toastr['info']('Loading, please wait...');
					},
					response: function(event, ui) {
						toastr.clear();
					},
					select: function( event, ui ) {
						$( "#autocomplete" ).val( ui.item.label );
						$( "#selitem" ).val( ui.item.value );
						$("#qty").focus();
						return false;
					}
				});
				 $( "#return_date" ).datepicker();
  
		});
		function delItem(id){
			var se = document.getElementById('sels').value;
			var loc = document.getElementById( 'location' ).value;

			de = se.replace(id, "");
			document.getElementById('sels').value = de;
			se2= document.getElementById('sels').value;

			$.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
				  { 'id':se2,'location':loc,'action':'del' },
					function(result) {
						$('#selected').html(result);
					}
			);
		}
		function submitMe(){
            var to_location = document.getElementById('to_location').value;
            var return_date = document.getElementById('return_date').value;
            var returned_from = document.getElementById('returned_from').value;
            var sitems = document.getElementById('sels').value;
           

            var msg = '';
            if(returned_from == ''){
                msg += 'Please enter returned from.<br>';
                document.getElementById('returned_from').focus();
            }
            if(to_location == ''){
                msg += 'Please select receiving warehouse.<br>';
                document.getElementById('to_location').focus();
            }if(return_date ==''){
                msg += 'Please select date of return.<br>';
            }if(sitems == ''){
                msg += 'Please enter items.<br>';
                document.getElementById('autocomplete').focus();
            }
            if(msg!=''){
                toastr['warning'](msg, "ABAS says:");
                return false;
            }else{
               document.forms['retForm'].submit();
            }
		}
</script>

<style>
#changeStatus {
	background-color: #DDD;
	float: left;
	position: absolute;
}
#changeStatus .form-group {
    margin: 15px;
}
.autocomplete {
    z-index: 5000;
}
.ui-autocomplete {
  z-index: 215000000 !important;
}
div.transbox {
  margin: 60px;
  border: 2px solid red;
  position: absolute;
}
</style>

<div style="float:left; margin-left:0px; height:400px">
        <div>
            <div class="panel panel-primary" style="font-size:12px; width:850px; height:620px">
                <div class="panel-heading" role="tab" id="headingOne">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeQR()">×</button>
                    <strong>Return</strong>
                </div>
                <div class="panel-body" role="tab" >
                    <form class="form-horizontal" role="form" id="retForm" name="retForm"  action="<?php echo HTTP_PATH.'inventory/addReturn'; ?>" method="post" enctype='multipart/form-data'>
                    <?php echo $this->Mmm->createCSRF() ?>
                    <div style="width:200px; margin-left:20px; float:left">
                        <div class="transbox">
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<br><br><br>
                        </div>
                            <div id="qr" style="display: inline-block; width: 200px; height: 180px; border: 0px solid silver;border:thin  #CCCCCC solid;background-color: #eee"></div>
                            <div class="row">
                                <div class="col-md-12 ">
                                    <code>Start Scanning</code> <span class="feedback" style='font-size: 10px'></span>
                                </div>
                            </div>
                            <!--<div class="row">
                                <input class="btn btn-success btn-xs" type="button"  value="scan" id="scan">
                                <input class="btn btn-success btn-xs disabled" type="button"  value="stop" id="stop">
                                <input class="btn btn-success btn-xs disabled" type="button"  value="change cam" id="change">
                            </div>-->
                             <div class="form-group">
                                <label>Returned From:</label>
                                <select class="form-control input-sm" name="returned_from" id="returned_from" required>
                                    <option value=""></option>
                                    <?php
                                        foreach($vessels as $vessel){
                                            echo '<option value="'.$vessel->id.'">'.$vessel->name.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" >
                                <label  for="amount">Return To Warehouse:</label>
                                 <select class="form-control input-sm" name="to_location" id="to_location" required>
                                    <option value=""></option>
                                    <?php
                                        foreach($locals as $loc){
                                            if($loc['location_name'] != 'Direct Delivery' ){
                                                echo '<option value="'.$loc['location_name'].'">'.$loc['location_name'].'</option>';
                                            }
                                        }
                                    ?>  
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date of Return:</label>
                                <input class="form-control input-sm" type="text" name="return_date" id="return_date" value="" required />
                            </div>
                            <div class="form-group">
                                <label>Returned by:</label>
                                <input class="form-control input-sm" type="text" name="return_by" id="return_by" value="" required />
                            </div>
                            
                            <div class="form-group">
                                <label>Remarks:</label>
                                <input class="form-control input-sm" type="text" name="remark" id="remark" />
                            </div>
                        </div>
                        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" id="selitem" name="selitem" />
                        <input type="hidden" id="sels" name="sels" />
                        <input type="hidden" id="unit_price" name="unit_price" />
               		</form>
               		 <!-- End of left div-->
                    
                    <div style="width:200px; margin-left:200px; margin-top:-520px;">                               									
                        <div class="jumbotron" style=" width:560px;height:460px; margin-top:20px; margin-left:35px; border:thin  #CCCCCC solid">
                            <div style="width:560px; height:50px; margin-top:-30px; margin-left:10px; display:block">
                            <label >Select Item:</label>
                            <input id="autocomplete" class="ui-autocomplete-input" style="width: 350px" title="Select Items" onblur="">
                            &nbsp;
                            <label>Qty:</label> <input type="number" id="qty" name="qty" style="width:65px" onchange="
                                                
                                var ids   = new Array();

                                var s = document.getElementById( 'selitem' ).value;
                                var its = document.getElementById( 'sels' ).value;
                                //var loc = document.getElementById( 'location' ).value;
                                var u_p = document.getElementById( 'unit_price' ).value;
                                var q = $( '#qty' ).val();
                                var loc = '';

                                if(s == ''){
                                    toastr['warning']('Please select item.', 'ABAS says:');
                                    document.getElementById( 'autocomplete' ).focus();
                                    return false;
                                }else if(q==''){
                                     toastr['warning']('Please enter quantity.', 'ABAS says:');
                                    document.getElementById( 'qty' ).focus();
                                    return false;
                                }else{

                                //insert             
                                    vals = s+'|'+q+'|'+u_p+','+its;
                                    ids.push(vals);

                                    document.getElementById( 'sels' ).value= ids;
                                    document.getElementById( 'qty' ).value ='';
                                    document.getElementById( 'unit_price' ).value ='';
                                    document.getElementById( 'selitem' ).value ='';
                                    document.getElementById( 'autocomplete' ).value ='';
                                    document.getElementById( 'autocomplete' ).focus();

                                    $.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
                                      { 'id':ids,'location':loc,'action':'issuance','unit_price':u_p },
                                            function(result) {
                                                $('#selected').html(result);
                                            }
                                    );
                                }
                            " />&nbsp;
                            <span style="display:none">
                            Unit Price: <input type="text" id="unit_price" name="unit_price"  style="width:95px" /></span>
                            &nbsp;
                            <input type="button" id="addMe" style="display:none" onclick="

                            var ids   = new Array();
                            var s = document.getElementById( 'selitem' ).value;
                            var its = document.getElementById( 'sels' ).value;
                            var q = $( '#qty' ).val();
                            var u_p = document.getElementById( 'unit_price' ).value;

                            if(s == ''){
                                toastr['warning']('Please select item.', 'ABAS says:');
                                return false;
                            }else if(q==''){
                                toastr['warning']('Please enter quantity.', 'ABAS says:');
                                return false;
                            }else{
                                //do ajax call to add item
                                vals = s+'|'+q+'|'+u_p','+its;
                                ids.push(vals);
                                document.getElementById( 'sels' ).value= ids;
                                document.getElementById( 'qty' ).value ='';
                                document.getElementById( 'selitem' ).value ='';
                                document.getElementById( 'autocomplete' ).value ='';
                                document.getElementById( 'autocomplete' ).focus();
                            }

                            " value="Add">
                        </div>
                            <div id="selected" style="width:540px;margin-top:-10px; margin-left:10px; height: 380px; overflow: auto" >
                                    <table id='datatable-responsive' style='font-size:11px' class='table table-bordered table-striped table-hover' cellspacing='0'>
                                        <thead>
                                            <tr>
                                                <th width='15%'>Item Code</th>
                                                <th width='15%'>Item Name</th>
                                                <th width='20%'>Particular</th>
                                                <th width='5%'>Qty</th>
                                                <th width='5%'>Unit</th>
                                                <th width='15%'>Unit Price</th>
                                                <th width='20%'>Line Total</th>
                                                <th width='5%'></th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td align="center"></td>
                                            <td align="left"></td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="right"></td>
                                            <td align="right"></td>
                                            <td align="right"></td>
                                            <td align="center">
                                                <a href="#">
                                                <i class="graphicon graphicon-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr align="right">
                                            <td colspan="5"></td>
                                            <td>Total:</td>
                                            <td>Php</td>
                                            <td></td>
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </div>
        <br>
		<span style="float:right; margin-right:40px; margin-top:-25px">
             <button id="change" class="btn btn-warning btn-m">Change camera</button>
			<input class="btn btn-success btn-m" type="button"  value="Save" onclick="javascript:submitMe()" id="submitbtn">
			<input type="button" class="btn btn-danger btn-m" value="Cancel" data-dismiss="modal" onclick="javascript:closeQR()"/>
		</span> 
	</div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("code").html('Scanning QR');
    $('#qr').html5_qrcode(function(data){
             // do something when code is read
             $(".feedback").html('Code scanned as: ' +data);
             var item = data.split(",");
             //set the item id
             $("#selitem").val(item[0]);
             //set the item unit price
             $("#unit_price").val(item[3]);

             $.ajax({
                 type:"POST",
                 url:"<?php echo HTTP_PATH;?>inventory/qr_selected_item/"+item[0],
                 success:function(data){
                    var item = $.parseJSON(data);   
                    $('#autocomplete').val(item[0].description + " (" + item[0].unit + ")");
                 }
             });

              document.getElementById( 'qty' ).focus();
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
    $("#change").on('click', function() {
        $("#qr").html5_qrcode_changeCamera();
    });
});

function closeQR(){
    $("#qr").html5_qrcode_stop();
}

</script>
