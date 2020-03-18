<div class="modal-header success table-responsive" style="background:#345369; color:#FFFFFF">
	<button type="button" class="close" data-dismiss="modal"><span>x</span></button>
    <h4 class="modal-title" id="myModalLabel"><strong>Job Order Approval</strong></h4>
</div>

<div class="modal-body table-responsive">	
   	
    <div style="margin-bottom:20px; font-size:14px">
    	<div style="float:left; ">
            <div style="font-size:16px"><strong>Company:</strong> <?php echo $jo['company_name'] ?></div>
            <div><strong>Contractor:</strong> <?php echo $jo['supplier_name'] ?></div>
            <div><strong>Remark:</strong>  <?php echo $jo['remark']; ?></div>
              <div><strong>Requested For:</strong>  <?php echo $request['vessel_name']; ?></div> 
        </div>
        <div style="float:right;">            
            <div><strong>JO Number:</strong> <?php echo $jo['control_number'] ?></div>
            <div><strong>Date:</strong> <?php echo date('F j, Y', strtotime($jo['tdate'])) ?></div>            
            <div><strong>Payment Term:</strong> <?php echo $jo['payment_terms'] ?></div>
            <div><strong>Requisition Approved By:</strong>  <?php echo ($request['approved_by_name']=="")?$request['details'][0]['request_approved_by']['full_name']:$request['approved_by_name']; ?></div>
        </div>
    </div>
    <br><br><br />
    <div style="margin-top:30px">

    	<table id="datatable-responsive" style="margin-top:10px"  class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0">
                  <thead>
                  	<tr>
                        <th>Description</th>
                        <th class="a-center ">Qty</th>
                        <th class="a-center ">Unit</th>                                          
                        <th width="15%">Unit Price</th>
                        <th width="10%">Amount</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php 
						$grand_total = 0;
						foreach($jo_details as $ctr=>$row){ 
							$job = $this->Inventory_model->getItem($row->item_id);
							$line_total = $row->unit_price * $row->quantity;

              $sql = "SELECT remark FROM inventory_request_details WHERE request_id=".$jo['request_id']." AND item_id=".$row->item_id." AND unit_price is null";
              $query = $this->db->query($sql);
              $service_description = $query->row(); 

					?>
                    <tr>
                      <td><?php echo $job[0]['description']. ", ". $service_description->remark; ?></td>
                      <td align="center"><?php echo $row->quantity ?></td>
                      <td><?php echo strtolower($row->unit) ?></td>                                          
                      <td align="right"><?php echo number_format($row->unit_price,2) ?></td>
                      <td align="right"><?php echo number_format($line_total,2) ?></td>
                    </tr>
                    <?php 
							$grand_total=$line_total + $grand_total;
						} 
					?>
                    
                    <tr style="font-weight:600">
                     
                      <td align="right" colspan="4">Total: </td>                                          
                      <td align="right"><?php echo number_format($grand_total,2) ?></td>                                          
                    </tr>
                    
                  </tbody>
         </table>  
                 <form name="joForm" id="joForm" method="post" action="<?php echo HTTP_PATH.'admin/jo_approve'; ?>">
					<?php echo $this->Mmm->createCSRF() ?>
                    <input type="hidden" name="jo_id" id="jo_id" value="<?php echo $jo['id'] ?>"  />
                </form>   
    </div>
    <div>
    	<?php
    		$user_location = $this->Abas->getUser($jo['added_by']);
    	?>
    	<span><strong>Served by:  <?php echo $user_location['user_location']; ?></strong></span>
    </div>   
</div>

<div class="modal-footer">
	  <button type="button" class="btn btn-success" onclick="
    		if(confirm('You are about to approve this job order, click Ok to continue.')){
				document.forms['joForm'].submit();
			}else{
				return false;
			}	
    ">Approve</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>

<script type="text/javascript">

	
	function submitForm(){
			
			if(confirm('You are about to approve this job order, click Ok to continue.')){
				document.forms['joForm'].submit();
			}else{
				return false;
			}	
			
		}
	}
	

</script>
