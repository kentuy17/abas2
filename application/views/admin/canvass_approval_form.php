<?php


	//var_dump($item[0]['item_code']);
?>

<script>


	function selectItems(el){

		var checkboxValues = [];
		//var sels = document.getElementById('approvedItems').value;
		$('input[name='+el+']:checked').map(function() {

				var v = $(this).val();
				//alert(v);
				//var iid = n;

				if(v == ''){
					alert('Please select item to approve.');
					return false;
				}else{

					//sels = v+'|'+iid;
					//sels = v;
					//checkboxValues.push(sels);
					//alert(sels);
					//document.getElementById('approvedItems').value = checkboxValues;
					document.getElementById(el).value = v;

				}
		});

		//alert(checkboxValues);
	}

	function submitForm(){
		
		
		
		
		document.forms['capprovalForm'].submit();
		//var radios = jQuery("input[type='radio']");
		//var chk = radios.filter(":checked");
		//var chk = $("input:radio:checked").val();

		//var aps =  $("form:radio:checked").val();
		//alert(chk);

		return false;


		//run this to make sure every seectd itms are included
		selectItems();

		//make sure somthing is selected
		var a = document.getElementById('approvedItems').value;

		if(a == ''){
			alert('Please select items to approve.');
			return false;
		}else{

			document.forms['capprovalForm'].submit();

		}
	}


</script>



<div class="modal-header table-responsive" style="background:#345369; color:#FFFFFF">
	<button type="button" class="close" data-dismiss="modal"><span>x</span></button>
    <h4 class="modal-title" id="myModalLabel"><strong>Canvass Approval</strong></h4>
</div>

<div class="modal-body table-responsive">


    <div style="margin-top:5px">
		<p>
        	<div>Requested by: <?php echo $csummary['requisitioner'] ?> for  <?php echo $csummary['vessel_name'] ?></div>
            <div>Remark: <?php echo $csummary['remark'] ?> </div>
        </p>


        <hr />

        <div class="x_content" style="margin-top:-15px">
                  <div class="dashboard-widget-content">

                    <form name="capprovalForm" id="capprovalForm" method="post" action="<?php echo HTTP_PATH.'admin/canvass_approve'; ?>">
					<?php echo $this->Mmm->createCSRF() ?>
                    <ul class="list-unstyled timeline widget">
                      <?php
						$ctr = 0;
						//var_dump($cdetail);
						if($cdetail == true){
						
						foreach($cdetail as $canvass){
							
							//get items from request
							$sqlAppend = " AND item_id =".$canvass['item_id'];
							$request = $this->Purchasing_model->getRequestDetails($canvass['request_id'],$sqlAppend);						
							//var_dump($request);
							//get item info
							$item = $this->Inventory_model->getItem($canvass['item_id']);
							//var_dump($request);
					  ?>
                      <li>
                        <div class="well">
                           <div class="block_content">
                            <div class="block_content">
                            <h5 class="title" style="color:#006633; background:#F4F4F4 repeat-y;">
                            	
                                <?php 	$addS = ''; 
										if($request[0]['quantity'] > 1){ $addS = 's';} ?>
                            
                               <strong>
							   		
                                    <div>Item: <a><?php echo $item[0]['description']." (".$item[0]['item_code'].")" ?></a> 
                                    &nbsp;&nbsp;&nbsp;
                                    	Qty: <a><?php  echo $request[0]['quantity']." ".strtolower($item[0]['unit']).$addS; ?></a>
                                    </div> 
                               </strong>
                            </h5>

                            <div class="byline"  style="color:#333333; font-size:14px">
                              

                            </div>
                            <div class="byline">

                                    <table id="datatable-responsive" style="margin-top:10px"  class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="120%">
                                      <thead>
                                        <tr>
                                          <th width="5%" align="center">*</th>
                                          <td width="40%">Supplier  (remark)</td>
                                          <th width="15%">Unit Price</th>
                                          <th width="15%">Total</th>
                                        </tr>
                                      </thead>


                                      <tbody>

                                        <?php
											
										
											
											$itemCanvass = $this->Admin_model->getSupplierCanvass($canvass['item_id'],$canvass['request_id']);
											//var_dump($itemCanvass);
											if(!empty($itemCanvass)) { // mmmaske: added

												foreach($itemCanvass as $can){
	
												// if($can['supplier_id'] != 0){ // mmmaske: commented - this condition is implemented in Admin_model->getSupplierCanvass
	
													$sup = $this->Abas->getSupplier($can['supplier_id']);
	
	
											?>
														<tr  style="color:#333333">
	
														  <td align="center">
															 <input type="radio" class="flat" name="<?php echo $canvass['item_id'] ?>"
																value="<?php echo $can['id'] //this is request_detail_id ?>"
															  onclick="selectItems(this.name)">
	
														  </td>
														  <td>
															<?php echo $sup['name'] ?>
                                                            
                                                            <?php if($can['remark']!=''){ ?>
                                                            <br />
                                                            <span style="color:#FF0000; font-weight:300">Remark: <?php echo $can['remark'] ?></span>
                                                            <?php } ?>
														  </td>
	
														  <td>
															<?php echo number_format($can['unit_price'],2)?>
														  </td>
														  <td>
															<?php echo number_format($can['unit_price'] * $request[0]['quantity'],2)?>
														  </td>
														</tr>
										   	<?php
													}

											}else{									
											
									   		?>
											<tr><td colspan="4">No canvass found</td></tr>
                                            
											<?php  } ?>
                                            


                                        <input type="hidden" name="<?php echo 'item'.$ctr ?>" id="<?php echo $canvass['item_id'] ?>" value="" onclick="alert(this.name)" />
                                      </tbody>
                                    </table>

                            </div>


                          </div>
                        </div>
                      </li>

                       <?php 
					   		
							
							
							$ctr++; }
							
							}
							
					  
					    ?>
                    </ul>

                     		<input type="hidden" name="item_count"  value="<?php echo $ctr ?>" />
                           <input type="hidden" name="request_id" value="<?php echo $cdetail[0]['request_id'] ?>" />
                     </form>

                  </div>
                </div>




    </div>
    <div>



    </div>

</div>

<div class="modal-footer" >

	
        <button type="button" class="btn btn-success" onclick="
                                                            var i = document.getElementsByName('item0')[0].value;
                                                            
                                                            if(i != ''){
                                                                if(confirm('You are about to approve selected canvass.')){
                                                                    document.forms['capprovalForm'].submit();
                                                                }else{
                                                                    return false;
                                                                }    
                                                            }    
                                                                "><i class="fa fa-check"></i> Approve</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
	
</div