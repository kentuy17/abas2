<?php 
	//var_dump($delivery_summary);
	//var_dump($delivery_detail);
	
	$supplier = $this->Abas->getSupplier($delivery_summary[0]['supplier_id']);
	//var_dump($supplier);
	$payto = $delivery_summary[0]['supplier_id']; //direct to the who will receive the payment (company or person)
	//var_dump($supplier['name']);exit;
?>

<div style="width:800px">
        <div class="panel panel-danger">
            <div class="panel-heading">
            	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><strong>Voucher Approval</strong></h4>
            </div>
            
        
        <div class="modal-body">
            
            
            <div style="margin-bottom:20px; font-size:16px">
                <div style="float:left; width:400px">
                    <div><strong>Voucher No.: <?php echo $delivery_summary[0]['voucher_id'] ?></strong></div>
                    <div><strong>Pay To: <?php echo $supplier['name'] ?></strong></div> 
                     <div><strong>Invoice No.: <?php echo $delivery_summary[0]['receipt_num'] ?></strong></div>    
                    </div>   
                  
                    
                </div>
                <div style="float:right; width:300px; margin-top:-15px; font-size:16px; margin-right:-20px">            
                    
                    <div><strong>Date: <?php echo date('F j, Y')  ?></strong></div>                                
                    <div><strong>PO No.: <?php echo $delivery_summary[0]['id'] ?></strong></div>
                   
                </div>
            
            </div>
            <br />
            <hr />
            
            <div style="margin-top:20px">
                <table id="datatable-responsive" style="margin-top:30px"  class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                              <thead>
                                                <tr>
                                                  
                                                  <th width="80%" style="border-right:#CCCCCC thin solid">Explanation of Payment</th>
                                                  <th width="20%">Amount</th>
                                                  
                                                </tr>
                                              </thead>
                        
                        
                                              <tbody>
                                                <?php 
													$gtotal = 0; 
													foreach($delivery_detail as $d){
													
														//get item info
														$item = $this->Inventory_model->getItem($d['item_id']);
												?>
                                                <tr>
                                                  <td style="border-right:#CCCCCC thin solid">
                                                  	
                                                    <div style="margin-top:30px; margin-bottom:50px">
                                                    	<span style="margin-left:30px"><?php echo $item[0]['description'] ?></span>
                                                        <span style="margin-left:120px"><?php echo $d['quantity'] ?></span>
                                                        <span style="margin-left:10px"><?php echo $d['unit'] ?></span>
                                                        
                                                        <span style="margin-left:30px">@ <?php echo $d['unit_price'] ?></span>
                                                    </div>
                                                  </td>
                                                  
                                                  <td>
                                                  	<div style="margin-top:30px; margin-bottom:50px">
                                                    	<?php 
															$line_total = $d['quantity'] * $d['unit_price'];
															$gtotal = $gtotal + $line_total;
															echo number_format($line_total,2);
														?>
                                                    </div>
                                                    </td>                                          
                                                  
                                                  
                                                </tr>
                                               <?php 
													}
												?>
                                                
                                                <tfoot>
                                                <tr>
                                                  
                                                  
                                                  <td align="right">Total: </td>                                          
                                                  <td width="10%">Php  <?php echo number_format($gtotal,2); ?></td>
                                                  
                                                </tr>
                                                <tr>
                                                  
                                                  
                                                  <td align="right">WTax: </td>                                          
                                                  <td width="10%">Php  <?php echo number_format($gtotal,2); ?></td>
                                                  
                                                </tr>
                                                <tr style="font-weight:600">
                                                  
                                                  
                                                  <td align="right">Grand Total: </td>                                          
                                                  <td width="10%">Php  <?php echo number_format($gtotal,2); ?></td>
                                                  
                                                </tr>
                                                <tr>
                                                  
                                                  
                                                  <th class="a-center ">Amount in Words: <?php echo $this->Mmm->chequeTextFormat($gtotal);?></th>                                          
                                                      <th width="10%"></th>
                                                  
                                                </tr>
                                              </tfoot>
                                                
                                              </tbody>
                                            </table>   
                                           
            </div>
            <div style="margin-left:20px">
                <span><strong>Remarks:</strong></span>
            
            </div>   
            
        </div>
        <form class="form-horizontal" role="form" name="vApprovalForm" id="vApprovalForm" 
        action="<?php echo HTTP_PATH.'admin/voucher_approve'; ?>" method="post">
        	 	<input type="hidden" name="amount" id="amount" value="<?php echo $gtotal; ?>">
           		<input type="hidden" name="payto" id="payto" value="<?php echo $payto; ?>">
               	<input type="hidden" name="delid" id="delid" value="<?php echo $delivery_summary[0]['id'] ?>">
                <input type="hidden" name="voucher_id" id="voucher_id" value="<?php echo $delivery_summary[0]['voucher_id']; ?>">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['abas_login']['userid']; ?>">
                
        </form>
        
       
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" onclick="document.forms['vApprovalForm'].submit();">Approve</button>
        </div> 
</div>