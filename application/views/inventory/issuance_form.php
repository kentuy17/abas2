<?php
	//var_dump($vessels[0]->id); exit;
	//var_dump($units);exit;  ssi1@globe.com.ph, noli - 09178707140
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
		//var_dump($item[0]['id']);exit;
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
						// alert(ui.item.value);
						//alert(ui.item.value);
						$( "#autocomplete" ).val( ui.item.label );
						$( "#selitem" ).val( ui.item.value );
						//document.getElementById("autocomplete").value = ui.item.label;
						$("#qty").focus();
						return false;
					}
				});
				 $( "#request_date" ).datepicker();
  
		});

		
		function delItem(id){

			var se = document.getElementById('sels').value;
			var loc = document.getElementById( 'location' ).value;

			de = se.replace(id, "");

			document.getElementById('sels').value = de;
			se2= document.getElementById('sels').value;
			//alert(se2);

			$.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
				  { 'id':se2,'location':loc,'action':'del' },
						function(result) {
							//alert(de);
							// clear any message that may have already been written
							$('#selected').html(result);
						}
			);


		}




		function submitMe(){

			//var rno = document.getElementById('request_no').value;
			var rdate = document.getElementById('request_date').value;
			var issued_to = document.getElementById('issued_to').value;
			var issued_for = document.getElementById('issued_for').value;
			var issued_from = document.getElementById('location').value;
			var sitems = document.getElementById('sels').value;
            var msg = "";
			if(rdate == ''){
                msg += 'Please enter request date.<br>';
				document.getElementById('request_date').focus();
			}
            if(issued_to == ''){
				msg += 'Please enter recipient.<br>';
				document.getElementById('issued_to').focus();
			}
            if(issued_for == ''){
				msg += 'Please select to where the items will be use.<br>';
				document.getElementById('issued_for').focus();

			}
            if(issued_from == ''){
				msg += 'Please select issuing location.<br>';
				document.getElementById('location').focus();

			}
            if(sitems == ''){
				msg += 'Please enter item(s).<br>';
				document.getElementById('autocomplete').focus();
			}

            if(msg!=''){
                toastr['warning'](msg, "ABAS says:");
                return false;
            }else{
				document.forms['delForm'].submit();
                $("#qr").html5_qrcode_stop();
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
                    <div class="panel panel-danger" style="font-size:12px; width:850px; height:570px">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeQR()">×</button>
                            <strong>Issuance</strong>
                        </div>
                        <div class="panel-body" role="tab" >
                            <form class="form-horizontal" role="form" id="delForm" name="delForm"  action="<?php echo HTTP_PATH.'inventory/addIssuance'; ?>" method="post" enctype='multipart/form-data'>
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
                                    <!--<div class="row">
                                        <input class="btn btn-success btn-xs" type="button"  value="scan" id="scan">
                                        <input class="btn btn-success btn-xs disabled" type="button"  value="stop" id="stop">
                                        <input class="btn btn-success btn-xs disabled" type="button"  value="change cam" id="change">
                                    </div>-->
                                <div class="form-group">
                                    <label  for="payee">Issue Date:*</label>
                                    <div>
            
                                        <input class="form-control input-sm" type="text" name="request_date" id="request_date" value="<?php echo $request_date;  ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="voucher_date">Issued For:*</label>
                                    <div>
                                        <input type="hidden" name="company_id" id="company_id">
                                        <select class="form-control input-sm" name="issued_for" id="issued_for">
                                            <option value="<?php echo $issued_for; ?>"><?php echo $issued_for; ?></option>
                                            <option value="101">AVEGA TRUCKING</option>
                                            <?php
            
                                                foreach($vessels as $vessel){ ?>
                                            <option value="<?php echo $vessel->id; ?>"><?php echo $vessel->name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input class="form-control input-sm" type="hidden" name="location" id="location" value="<?php echo $location; ?>" />
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label for="voucher_no">Received By:*</label>
                                    <div>
                                        <input class="form-control input-sm" type="text" name="issued_to" id="issued_to" value="<?php echo $issued_to;  ?>" />
                                    </div>
                                </div>

					

					<!---
					<div class="form-group">
						<label  for="amount">Issued From (Source):</label>
						<div>
							<select class="form-control input-sm" name="location" id="location">
								<option value="<?php echo $location; ?>"><?php echo $location; ?></option>
								<option value="Tayud">Tayud</option>
								<option value="NRA">NRA</option>
								<option value="Makati">Makati</option>
								<option value="Tacloban">Tacloban</option>
								<option value="Direct Delivery">Direct Delivery</option>
							</select>
						</div>
					</div>
					--->
                                <div class="form-group">
                                    <label  for="payee">Purpose:</label>
                                    <div>
            
                                        <input class="form-control input-sm" type="text" name="remark" id="remark" value="<?php echo $remark;  ?>" />
                                        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                        <input type="text" id="selitem" name="selitem" />
                                        <input type="hidden" id="sels" name="sels" />
                                        <input type="hidden" id="is_avail" name="is_avail" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" name="gatepass" id="gatepass" value="1"/>
                                    <label>Include Gate Pass</label>
                                </div>

							</div>
                        	<div id="aqty"></div>                               
            
                       		</form>
                       		 <!-- End of left div-->
                            
                            <div style="width:200px; margin-left:200px; margin-top:-480px;">                               									
                                    <div class="jumbotron" style=" width:560px;height:410px; margin-top:0px; margin-left:35px; border:thin  #CCCCCC solid">
                                        <div style="width:560px; height:50px; margin-top:-30px; margin-left:10px; display:block">
                                        <label >Select Item:</label>
                                        <input id="autocomplete" class="ui-autocomplete-input" style="width: 350px" title="Select Items" onblur="">
                                        &nbsp;
                                        <label>Qty:</label> <input type="number" id="qty" name="qty" style="width:65px" onchange="
                                                            
                                                            var ids   = new Array();
            
                                                            var s = document.getElementById( 'selitem' ).value;
                                                            var its = document.getElementById( 'sels' ).value;
                                                            var loc = document.getElementById( 'location' ).value;
                                                            var company = document.getElementById( 'company_id' ).value;
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
                                                                      { 'id':s,'location':loc,'company_id':company,'qty':q },
                                                                            function(result) {
                                                                                //alert(result.returnValue);
                                                                                // clear any message that may have already been written
                                                                                //document.getElementById( 'is_avail' ).value = result;          
                                                                                //var is = result;
                                                                                var availqty = parseInt(result) + 1;
                                                                                if(parseInt(availqty) > parseInt(q)){
                                                                                    
                                                                                    vals = s+'|'+q+'|'+u_p+','+its;
                                                                                    ids.push(vals);
                            
                                                                                    document.getElementById( 'sels' ).value= ids;
                                                                                    document.getElementById( 'qty' ).value ='';
                                                                                     document.getElementById( 'unit_price' ).value ='';
                                                                                    document.getElementById( 'selitem' ).value ='';
                                                                                    document.getElementById( 'autocomplete' ).value ='';
                                                                                    document.getElementById( 'autocomplete' ).focus();
                            
                                                                                   // var sels = document.getElementById('sels').value;
                                                                                     //alert(ids);
                                                                                        $.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
                                                                                          { 'id':ids,'location':loc,'action':'issuance','unit_price':u_p },
                                                                                                function(result) {
                                                                                                    //alert(result);
                                                                                                    // clear any message that may have already been written
                                                                                                    $('#selected').html(result);
                                                                                                }
                                                                                        );
                                                                                    
                                                                                    
                                                                                }else{
                                                                                     toastr['warning']('Qty is not sufficient or is not available on this location or company.', 'ABAS says:');
                                                                                    return false;
                                                                                }                
                                                                                
                                                                            }
                                                                    );

                                                                var isa = document.getElementById( 'is_avail' ).value;
                                                                alert(is);
                                                                //alert(q);
                                                                if(isa > q){
            
                                                                    //do ajax call to add item
                                                                    vals = s+'|'+q+'|'+u_p+','+its;
                                                                    ids.push(vals);
            
                                                                    document.getElementById( 'sels' ).value= ids;
                                                                    document.getElementById( 'qty' ).value ='';
                                                                    document.getElementById( 'unit_price' ).value ='';
                                                                    document.getElementById( 'selitem' ).value ='';
                                                                    document.getElementById( 'autocomplete' ).value ='';
                                                                    document.getElementById( 'autocomplete' ).focus();
            
                                                                   // var sels = document.getElementById('sels').value;
                                                                     //alert(ids);
                                                                        $.post('<?php echo HTTP_PATH."inventory/getDelivery/"; ?>',
                                                                          { 'id':ids,'location':loc,'action':'issuance' },
                                                                                function(result) {
                                                                                    //alert(result);
                                                                                    // clear any message that may have already been written
                                                                                    $('#selected').html(result);
                                                                                }
                                                                        );
            
                                                                }else{
                                                                     toastr['warning']('Qty is not sufficient or is not available.', 'ABAS says:');
                                                                    return false;
                                                                }
                                                            }
                                        " />                                      						&nbsp;
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
                                    
                                    <div id="selected" style="width:540px;margin-top:-10px; margin-left:10px; height: 300px; overflow: auto" >
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
                                                        <td align="center"><?php  //echo  ?></td>
                                                        <td align="left"><?php  //echo  ?></td>
                                                        <td align="center"><?php // echo  ?></td>
                                                        <td align="center"><?php  //echo  ?></td>
                                                        <td align="right"><?php  //echo  ?></td>
                                                        <td align="right"><?php // echo  ?></td>
                                                         <td align="right"><?php // echo  ?></td>
                                                        <td align="center">
                                                            <a href="##">
                                                            <i class="graphicon graphicon-remove"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    //$total = $total + $lineTotal;
                                                   // }
                                               // }
                                            ?>
        
                                            <tr align="right">
                                                <td colspan="5"></td>
                                                <td>Total:</td>
                                                <td>Php <?php //echo number_format($total,2); ?></td>
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

$('#issued_for').change(function(){   
  var vessel_id = document.getElementById('issued_for').value;
  if(vessel_id!=""){
      $.ajax({
         type:"POST",
         url:"<?php echo HTTP_PATH;?>/inventory/get_company_id/"+vessel_id,
         success:function(data){
            var c = $.parseJSON(data);
            $('#company_id').val(c.company_id);
         }  
      });
  }
}); 
    
</script>
