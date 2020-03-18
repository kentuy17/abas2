<?php 
	//var_dump($canvass);
	//var_dump($request);

	//get request for
	//$ofc = $this->Abas->getVessel($request['vessel']);		
	//$company = $this->Abas->getCompany($po['company_id']);
	//$supplier = $this->Abas->getSupplier($po['supplier_id']);
?>


<div class="modal-header success table-responsive" style="background:#345369; color:#FFFFFF">
	<button type="button" class="close" data-dismiss="modal"><span>x</span></button>
    <h4 class="modal-title" id="myModalLabel"><strong>Purchase Order Approval</strong></h4>
</div>

<div class="modal-body table-responsive">	
   	
    <div style="margin-bottom:20px; font-size:14px">
    	<div style="float:left; ">
            <div style="font-size:16px"><strong>Company:</strong> <?php echo $po['company_name'] ?></div>
            <div><strong>Supplier:</strong> <?php echo $po['supplier_name'] ?></div>
            <div><strong>Remark:</strong>  <?php echo $po['remark']; ?></div>
           	<div><strong>Requested For:</strong>  <?php echo $request['vessel_name']; ?></div> 
            
            
        </div>
        <div style="float:right;">            
            <div><strong>PO Number:</strong> <?php echo $po['control_number'] ?></div>
            <div><strong>Date:</strong> <?php echo date('F j, Y', strtotime($po['tdate'])) ?></div>            
            <div><strong>Payment Term:</strong> <?php echo $po['payment_terms'] ?></div>
             <div><strong>Requisition Approved By:</strong>  <?php echo ($request['approved_by_name']=="")?$request['details'][0]['request_approved_by']['full_name']:$request['approved_by_name']; ?></div>
        </div>
    </div>
    <br /> <br /><br />
    <div style="margin-top:30px">

    	         
    	<table id="datatable-responsive" style="margin-top:10px"  class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0">
                                      <thead>
                                        <tr>
                                         
                                          <th>Item Code</th>
                                          <th>Description</th>
                                          <th class="a-center ">Qty</th>
                                          <th class="a-center ">Unit</th>                                          
                                          <th width="15%">Unit Price</th>
                                          <th width="10%">Amount</th>
                                        </tr>
                                      </thead>
                
                
                                      <tbody>
                                        <?php 
										
											//$this->Mmm->debug($po);
											$grand_total = 0;
											foreach($po['details'] as $po_detail){ 
												
												$item = $this->Inventory_model->getItem($po_detail['item_id']);
												//var_dump($request_detail['status']);					
												//$user = $this->Abas->getUser($request_detail['assigned_to']);
												//var_dump($user->username);
												$line_total = $po_detail['unit_price'] * $po_detail['quantity'];
										?>
                                        <tr>
                                         
                                          <td><?php echo $item[0]['item_code'] ?></td>
                                          <td><?php echo $item[0]['description'] ?></td>
                                          <td align="center"><?php echo $po_detail['quantity'] ?></td>
                                          <td><?php echo strtolower($po_detail['unit']) ?></td>                                          
                                          <td align="right"><?php echo number_format($po_detail['unit_price'],2) ?></td>                                          
                                          <td align="right"><?php echo number_format($line_total,2) ?></td>                                          
                                         
                                        </tr>
                                        <?php 
												$grand_total=$line_total + $grand_total;
											} 
										?>
                                        
                                        <tr style="font-weight:600">
                                         
                                          <td align="right" colspan="5">Total: </td>                                          
                                          <td align="right"><?php echo number_format($grand_total,2) ?></td>                                          
                                         
                                        
                                      </tbody>
                                    </table>  
                                     <form name="poForm" id="poForm" method="post" action="<?php echo HTTP_PATH.'admin/po_approve'; ?>">
										<?php echo $this->Mmm->createCSRF() ?>
                                                <input type="hidden" name="po_id" id="po_id" value="<?php echo $po['id'] ?>"  />
                                    </form>   
    </div>
    <div>
    	<span><strong>Served by:  <?php echo $po['details'][0]['item']['location']; ?></strong></span>
    
    </div>   
    
</div>

<div class="modal-footer">
	  <button type="button" class="btn btn-success" onclick="
    		if(confirm('You are about to approve this purchase order, click Ok to continue.')){
				document.forms['poForm'].submit();
			}else{
				return false;
			}	
    ">Approve</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    														<?php if(ENVIRONMENT!="development"){  ?>
                                                            <!---
                                                            <button type="button" class="btn btn-warning" onclick="														
                                                            	disapprove();
                                                           
                                                            ">Disapprove</button>
                                                            --->
															<?php  } ?>
  

</div>

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
			
			if(confirm('You are about to approve this purchase order, click Ok to continue.')){
				document.forms['poForm'].submit();
			}else{
				return false;
			}	
			
		}
	}
	

</script>
