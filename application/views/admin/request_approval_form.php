<?php 
	//var_dump($canvass);
	//var_dump($request);

	//get request for
	//$ofc = $this->Abas->getVessel($request['vessel']);		
	
?>

<script type="text/javascript">

$(document).ready(function() {
       
		
		$('#item').on('change', function() {
			var val = this.checked ? this.value : '';
			alert(val);
			//$('#show').html(val);
		});
		
});

function selectItems(){
		
		var checkboxValues = [];
		var sels = [];
		$('input[name=sid]:checked').map(function() {
				
				var v = $(this).val();								
				
				if(v == ''){
					alert('Please select item to approve.');					
					return false;	
				}else{
					
					sels = v;
					checkboxValues.push(sels);
					document.getElementById('approvedItems').value = checkboxValues;
					
				}	
		});
	
		//alert(checkboxValues);
	}
	
	function submitForm(){
	
		//run this to make sure every seectd itms are included
		selectItems();
		
		//make sure somthing is selected
		var a = document.getElementById('approvedItems').value;
		
		if(a == ''){
			alert('Please select items to approve.');
			return false;
		}else{
			
			if(confirm('You are about to approve selected request.')){
				document.forms['rapprovalForm'].submit();
			}else{
				return false;
			}	
			
		}
	}
	

</script>

<div class="modal-header success table-responsive" style="background:#345369; color:#FFFFFF">
	<button type="button" class="close" data-dismiss="modal"><span>x</span></button>
    <h4 class="modal-title" id="myModalLabel"><strong>Request Approval</strong></h4>
</div>

<div class="modal-body table-responsive">	
    
    <div style="margin-bottom:20px">
    	<div style="float:left; ">
            <div><strong>Requested By:</strong> <?php echo $request['requisitioner'] ?></div>
            <div><strong>Request For:</strong>  <?php echo $request['vessel_name']; ?></div>
            <div><strong>Purpose:</strong>  <?php echo $request['purpose'] ?></div>
            
        </div>
        <div style="float:right;">            
            <div><strong>Date Requested:</strong> <?php echo date('F j, Y', strtotime($request['tdate'])) ?></div>            
            <div><strong>Priority:</strong> <?php echo $request['priority'] ?></div>
        </div>
    </div>
    <br />
    <div style="margin-top:30px">
    	<table id="datatable-responsive" style="margin-top:10px"  class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0">
                                      <thead>
                                        <tr>
                                          <th class="a-center ">
                                              *                                          </th>
                                          <th>Item Code</th>
                                          <th>Description</th>
                                          <th class="a-center ">Qty</th>
                                          <th class="a-center ">Unit</th>                                          
                                          <th width="15%">Assigned To</th>
                                          <th width="10%" style="display:none">Notes</th>
                                        </tr>
                                      </thead>
                
                
                                      <tbody>
                                        <?php 
										
											//var_dump($request_details);
											foreach($request_details as $request_detail){ 
												
												$item = $this->Inventory_model->getItem($request_detail['item_id']);
												//var_dump($request_detail['status']);
												
												$checked = '';
												if(strtolower($request_detail['status']) != 'for request approval'){
													$checked = 'checked="checked"';
												}	
												
												$user = $this->Abas->getUser($request_detail['assigned_to']);
												//var_dump($user->username);
										?>
                                        <tr>
                                          <td class="a-center ">
                                          
                                              <input type="checkbox" name="sid" id="sid" class="flat" name="table_records" <?php echo $checked; ?>value="<?php echo $request_detail['id'] ?>" 
                                              		onclick="
                                                    			var checkboxValues = [];
                                                                var sels = [];
                                                                $('input[name=sid]:checked').map(function() {
                                                                        var v = $(this).val();                                                                       
                                                                        var qid = $(this).val();                                                                       
                                                                        sels = v;
                                                                        checkboxValues.push(sels);
                                                                        document.getElementById('approvedItems').value = checkboxValues;                         
                                                                });
                                                                
                                                    	">
                                            </td>
                                          <td><?php echo $item[0]['item_code'] ?></td>
                                          <td><?php echo $item[0]['description'].",".$request_detail['remark'] ?></td>
                                          <td><?php echo $request_detail['quantity'] ?></td>
                                          <td><?php echo strtolower($item[0]['unit']) ?></td>                                          
                                          <td><?php echo $user['username'] ?></td>                                          
                                          <td align="center" style="display:none">
                                            <a class="like" href="<?php echo HTTP_PATH ?>admin/item_note" data-toggle="modal" data-target="#modalDialog" title="View/Add Notes">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i> </button>
                                            </a>
                                          </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        
                                        
                                      </tbody>
                                    </table>  
                                    <form name="rapprovalForm" id="rapprovalForm" method="post" action="<?php echo HTTP_PATH.'admin/request_approve'; ?>">
                                    <?php echo $this->Mmm->createCSRF() ?>
                                        <input type="hidden" name="approvedItems" id="approvedItems" />
                                        <input type="hidden" name="rid" id="rid" value="<?php echo $request['id'] ?>" />                                      
                                        <input type="hidden" name="number_of_items" id="number_of_items" value="<?php echo count($request_details) ?>" />                                      
                                    </form> 
    </div>
    <div>
    	<span><strong>Remarks:  <?php echo $request['remark'] ?></strong></span>
    
    </div>   
    
</div>

<div class="modal-footer">
	 <button type="button" class="btn btn-success" onclick="submitForm();">Approve</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   

</div>

