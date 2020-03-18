<?php
	$issuance_no = "";
	$request_date = "";
	$request_no = "";
	$issued_to = "";
	$issued_for = "";
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

                 $( "#transfer_date" ).datepicker();
  
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

            var from_location = document.getElementById('location').value;
            var to_location = document.getElementById('to_location').value;
            var transfer_date = document.getElementById('transfer_date').value;
            var transfered_by = document.getElementById('transfered_by').value;
            var sitems = document.getElementById('sels').value;
            var company = document.getElementById('company').value;

            var msg = '';
            if(company == ''){
                msg += 'Please select company.<br>';
                document.getElementById('company').focus();
            }if(from_location ==''){
                msg += 'Please select issuing warehouse.<br>';
            }if(to_location == ''){
                msg += 'Please select receiving warehouse.<br>';
                document.getElementById('to_location').focus();
            }if(transfer_date ==''){
                msg += 'Please select date of issaunce.<br>';
            }if(transfered_by == ''){
                msg += 'Please enter the name of requisitioner.<br>';
                document.getElementById('transfered_by').focus();
            }if(sitems == ''){
                msg += 'Please enter items.<br>';
                document.getElementById('autocomplete').focus();
            }if(from_location==to_location){
                msg += 'Please make sure the receiving warehouse is not the same with the issuing warehouse.<br>';
                document.getElementById('to_location').focus();
            }

            if(msg!=''){
                toastr['warning'](msg, "ABAS says:");
                return false;
            }else{
               document.forms['tranForm'].submit();
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
                    <div class="panel panel-info" style="font-size:12px; width:850px; height:655px">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeQR()">×</button>
                            <strong>Transfer</strong>
                        </div>
                        <div class="panel-body" role="tab" >
                            <form class="form-horizontal" role="form" id="tranForm" name="tranForm"  action="<?php echo HTTP_PATH.'inventory/addTransfer'; ?>" method="post" enctype='multipart/form-data'>
                            <?php echo $this->Mmm->createCSRF() ?>
                                <div style="width:200px; margin-left:20px; float:left">
                                    <div class="transbox">
                                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<br><br><br>
                                    </div>
                                        <div id="qr" style="display: inline-block; width: 200px; height: 180px; border: 0px solid silver;border:thin  #CCCCCC solid;background-color: #eee"></div>
                                        <div class="row">
                                            <div class="col-md-12 " >
                                                <code>Start Scanning</code> <span class="feedback" style='font-size: 10px'></span>
                                            </div>
                                        </div>
                                    <div class="form-group" >
                                        <label for="company">Company:</label>
                                        <select class="form-control input-sm" name="company" id="company" required>
                                            <option value="<?php echo $company_id; ?>"><?php echo $company_name; ?></option>
                                            <?php 
                                                foreach($companies as $company){
                                                    if($company->id != 10){ 
                                                        echo '<option value="'.$company->id.'">'.$company->name.'</option>';
                                                    }   
                                                }
                                            ?>  
                                        </select>
                                    </div>
                                    <div class="form-group" >
                                        <label  for="amount">From Warehouse (Issuing):</label>
                                        <input type="text" id="location" name="loc" value="<?php echo $user_location; ?>"  class="form-control input-sm" readonly required/>
                                    </div>
                                    <div class="form-group" >
                                        <label  for="amount">To Warehouse (Receiving):</label>
                                         <select class="form-control input-sm" name="to_location" id="to_location" required>
                                            <option value=""></option>
                                            <?php
                                                foreach($locals as $loc){
                                                    if($loc['location_name'] != $user_location ){
                                                        if($loc['location_name'] != 'Direct Delivery' ){
                                                            echo '<option value="'.$loc['location_name'].'">'.$loc['location_name'].'</option>';
                                                        }
                                                    }
                                                }
                                            ?>  
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label >Date of Issuance:</label>
                                        <input class="form-control input-sm" type="text" name="transfer_date" id="transfer_date" value="" required />
                                    </div>
                                     <div class="form-group">
                                        <label >Requested By:</label>
                                        <input class="form-control input-sm" type="text" name="transfered_by" id="transfered_by" required/>
                                    </div>
                                    <div class="form-group">
                                        <label  >Remarks:</label>
                                        <input class="form-control input-sm" type="text" name="remark" id="remark" />
                                    </div>
                                </div>

                                <input type="text" id="id" name="id" value="<?php echo $id; ?>" />
                                <input type="text" id="selitem" name="selitem" />
                                <input type="text" id="sels" name="sels" />
                                <input type="text" id="is_avail" name="is_avail" />
                                <input type="text" id="unit_price" name="unit_price" />
                       		</form>
                            
                            <div style="width:200px; margin-left:200px; margin-top:-580px;">                               									
                                    <div class="jumbotron" style=" width:560px;height:490px; margin-top:20px; margin-left:35px; border:thin  #CCCCCC solid">
                                        <div style="width:560px; height:50px; margin-top:-30px; margin-left:10px; display:block">
                                        <label >Select Item:</label>
                                        <input id="autocomplete" class="ui-autocomplete-input" style="width: 350px" title="Select Items" onblur="">
                                        &nbsp;
                                        <label>Qty:</label> <input type="number" id="qty" name="qty" style="width:65px" onchange="
                                                            
                                        var ids   = new Array();

                                        var s = document.getElementById( 'selitem' ).value;
                                        var its = document.getElementById( 'sels' ).value;
                                        var loc = document.getElementById( 'location' ).value;
                                        var u_p = document.getElementById( 'unit_price' ).value;
                                        var q = $( '#qty' ).val();

                                        if(s == ''){
                                            toastr['warning']('Please select item.', 'ABAS says:');
                                            document.getElementById( 'autocomplete' ).focus();
                                            return false;
                                        }else if(q==''){
                                             toastr['warning']('Please enter quantity.', 'ABAS says:');
                                            document.getElementById( 'qty' ).focus();
                                            return false;
                                        }else if(loc==''){
                                            toastr['warning']('Please select source location.', 'ABAS says:');
                                            document.getElementById( 'location' ).focus();
                                            return false;
                                        }else{

                                            //chk if qty is available
                                                
                                             $.post('<?php echo HTTP_PATH."inventory/chkQty/"; ?>',
                                                  { 'id':s,'location':loc,'qty':q,'company_id':company },
                                                        function(result) {
                                                            var availqty = parseInt(result);
                                                            if(parseInt(availqty) >= parseInt(q)){
                                                                
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
                                                                
                                                            }else{
                                                                 toastr['warning']('Qty is not sufficient or is not available.', 'ABAS says:');
                                                                return false;
                                                            }                
                                                            
                                                        }
                                                );

                                        }" />                                      						&nbsp;
                                        <span style="display:none">
                                        Unit Price: <input type="text" id="unit_price" name="unit_price"  style="width:95px" />														</span>
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
                                                vals = s+'|'+q+'|'+u_p','+its;                                                              						ids.push(vals);
                                                document.getElementById( 'sels' ).value= ids;
                                                document.getElementById( 'qty' ).value ='';
                                                document.getElementById( 'selitem' ).value ='';
                                                document.getElementById( 'autocomplete' ).value ='';
                                                document.getElementById( 'autocomplete' ).focus();
                                            }
                                            " value="Add">
                                    </div>

                                    <div id="log"></div>
                                    
                                    <div id="selected" style="width:540px;margin-top:-10px; margin-left:10px; height: 400px; overflow: auto" >
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
                                                            <a href="##">
                                                            <i class="graphicon graphicon-remove"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                            <tr align="right">
                                                <td colspan="5"></td>
                                                <td>Total:</td>
                                                <td>Php </td>
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
