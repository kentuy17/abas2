<?php

	$added_by = $this->Abas->getUser($request_data['added_by']);
?>
<div class="modal-header table-responsive" style="background:#345369; color:#FFFFFF">
	<button type="button" class="close" data-dismiss="modal"><span>x</span></button>
    <h4 class="modal-title" id="myModalLabel"><strong>Request Details</strong></h4>
</div>
<div class="modal-body table-responsive">
  <div style="margin-top:5px">
		    <p>
          <div><strong>Control Number:</strong> <?php echo $request_data['control_number'] ?></div>
          <div><strong>Date Created:</strong> <?php echo date('F j, Y', strtotime($request_data['tdate'])) ?></div>
          <div><strong>Created by:</strong> <?php echo $added_by['first_name'] ?>    </div>
          <div><strong>Serve by:</strong> <?php echo $added_by['user_location'] ?></div>
          <div><strong>Requested by:</strong> <?php echo $request_data['requisitioner'] ?> for  <?php echo $request_data['vessel_name'] ?></div>
           <?php if(isset($request_data['approved_by_name'])){ 
            echo '<div><strong>Approved by:</strong>'.$request_data['approved_by_name'].'</div>'; } ?>
          <div><strong>Remark:</strong><?php echo $request_data['purpose'] ?> </div>
        </p>
        <hr />
        <div class="x_content" style="margin-top:-15px">
                  <div class="dashboard-widget-content">
                                    <table id="datatable-responsive" style="margin-top:10px"  class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="120%">
                                      <thead>
                                        <tr>
                                          <th width="10%" align="center">Itemcode</th>
                                          <td width="40%">Description</td>
                                          <th width="10%">Unit</th>
                                          <th width="10%" >Qty</th>
                                          <th width="15%">Status</th>
                                          <th width="20%">Assigned To</th>
                                        </tr>
                                      </thead>
                                      <tbody><?php
                      												foreach($request_data['details'] as $item){
                      												// if($can['supplier_id'] != 0){ // mmmaske: commented - this condition is implemented in Admin_model->getSupplierCanvass					 
                                              $assigned_to = $this->Abas->getUser($item['assigned_to']);
                      	
                      										?>
														<tr  style="color:#333333">
														  <td align="center">
															 <?php echo $item['item_details']['item_code'] ?>	
														  </td>
														  <td>
															<?php echo $item['item_details']['description'] ?>                      
														  </td>
														  <td>
															<?php echo $item['item_details']['unit']?>
														  </td>
														  <td align="right">
															<?php echo $item['quantity']?>
														  </td>
                                                          <td>
                                                          	<?php echo $item['status']?>
                                                          </td>
                                                          <td>
                                                          	<?php echo $assigned_to['first_name'] ?>
                                                          </td>
                                                          
														</tr>
										  
                                            
											<?php  } ?>
                                            


                                        
                                      </tbody>
                                    </table>

                          
                      </li>

                       

                  </div>
                </div>




    </div>
    <div>



    </div>

</div>

<div class="modal-footer" >

	
        
        <button type="button" class="btn btn-default btn-danger" data-dismiss="modal">Close</button>
	
</div