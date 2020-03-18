<?php 
	//var_dump($delivery_summary);
	//var_dump($voucher);
	$payto = '';
	$refid ='';
	$po_no = '';
	$c = '';	
	$delid = '';
	$voucher_id = $voucher[0]['id'];
	
	if($voucher[0]['transaction_type'] == 'Purchase Order'){
		
		$supplier = $this->Abas->getSupplier($delivery_summary[0]['supplier_id']);
		
		$payto = $supplier['name']; //direct to the who will receive the payment (company or person)
		
		$refid = 'Invoice No.: '.$delivery_summary[0]['receipt_num'];
		$po_no = 'PO No.: '.$delivery_summary[0]['po_no'];
		
		//for reference
		$delid = $delivery_summary[0]['id'];
														
		
	}
	
	if($voucher[0]['transaction_type'] == 'Cash Request'){
		
		$c = $this->Finance_model->getCashAdvanceByVoucherId($voucher[0]['id']);
		//for reference
		$delid = $c[0]['id'];
																								
		$requested_by = $this->Abas->getEmployee($c[0]['requested_by']);  
		$department = $this->Abas->getDepartments($c[0]['department']);  
		//var_dump($supplier);
		$payto = $requested_by['full_name'];
		$refid = 'Department.: '.$department[0]->name;
		//direct to the who will receive the payment (company or person)
		//var_dump($supplier['name']);exit;
		
		$po_no = 'Request No.:  '.$c[0]['id'];
		//$po_no = $delivery_summary[0]['id'];
		
	}
	
?>

<div>
        <div class="panel panel-danger ">
            <div class="panel-heading table-responsive">
            	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><strong>Voucher Approval</strong></h4>
            </div>
            
        
        <div class="modal-body">
            
            
            <div style="margin-bottom:20px; font-size:14px">
                <div style="float:left; width:40%">
                    <div><strong>Voucher No.: <?php echo $voucher[0]['voucher_number'] ?></strong></div>
                    <div><strong>Pay To: <?php echo $payto ?></strong></div> 
                     <div><strong><?php echo $refid ?></strong></div>    
                    </div>   
                  
                    
                </div>
                <div style="float:right; width:30%; margin-top:-15px;  margin-right:-20px">            
                    
                    <div><strong>Date: <?php echo date('F j, Y')  ?></strong></div>                                
                    <div><strong><?php echo $po_no ?></strong></div>
                   
                </div>
            
           </div>
           
            <br />
           
            
            <div style="margin-top:20px">
                <table id="datatable-responsive" style="margin-top:30px"  class="table table-striped dt-responsive nowrap table-responsive" cellspacing="0" width="100%">
                                              <thead>
                                                <tr>
                                                  
                                                  <th width="70%" style="border-right:#CCCCCC thin solid">Explanation of Payment</th>
                                                  <th width="30%">Amount</th>
                                                  
                                                </tr>
                                              </thead>
                        
                        
                                              <tbody>
                                               <tr>
                                                  <td style="border-right:#CCCCCC thin solid" colspan="2">
                                                   <div style="margin-top:30px; margin-bottom:50px">
												     <table width="95%" cellpadding="10" cellspacing="10">
												   <?php 
													$gtotal = 0;
													$wtax = 0;
													$vat = 0;
													$delid = '';
													
													if($voucher[0]['transaction_type'] == 'Purchase Order'){
														
														
														foreach($delivery_detail as $d){
															//get item info
															$item = $this->Inventory_model->getItem($d['item_id']);
															
															$line_total = $d['quantity'] * $d['unit_price'];
															$gtotal = $gtotal + $line_total;															
														?>	                                      
															<tr>
																<td width="10%"><?php echo $item[0]['item_code'] ?></td>
																<td width="30%" align="left"><?php echo $item[0]['description'] ?></td>
																<td width="10%" align="right"><?php echo $d['quantity'] ?>&nbsp;&nbsp;</td>
																<td width="5%" align="left"><?php echo $d['unit'] ?></td>
																<td width="10%" align="right">@ <?php echo $d['unit_price'] ?></td>
																<td width="20%" align="right"><?php echo number_format($line_total,2); ?></td>	
															</tr>                                  
														 <?php 					
															}
															
														}
													 ?>
                                                     
                                                     
													 <?php 
																										
													if($voucher[0]['transaction_type'] == 'Cash Request'){
													
																													
															$gtotal = $c[0]['amount'];														
														?>	                                      
															
                                                   		<tr>
                                                    		<td width="10%"><?php echo $c[0]['type'] ?></td>
                                                            <td width="30%" align="left"><?php echo $c[0]['purpose'] ?></td> 
                                                            <td width="20%" align="right"><?php echo number_format($gtotal,2); ?></td>                                       
                                                    	
                                                    	</tr>                              
													<?php 					
														}													
														
													 ?>	 
													                                                     
                                                     
                                                    </table>            
                                                    </div>
                                                  </td>
                                                  
                                                                                      
                                                  
                                                  
                                                </tr>
                                              		<?php
												  		//compute tax
														
														if($voucher[0]['vtax']!=''){															
															//compute vat 
															$vat = $this->Accounting_model->computeVat($voucher[0]['vtax'],$gtotal);														
														}
														
														if($voucher[0]['wtax']!=''){															
															//compute vat 
															$wtax = $this->Accounting_model->computeWTax($voucher[0]['wtax'],$gtotal);														
														}
														
														$gtotal_afterTax = ($gtotal - $vat) - $wtax;
																												
												  	?>                                                  
                                                
                                                <tfoot>
                                                
                                                <?php
                                                //needed in purchase order
												if($voucher[0]['transaction_type'] == 'Purchase Order'){
												?>
                                                <tr>
                                                  
                                                  <td  align="right">
                                                  	<span>Total:</span><br /> 
                                                    <span>WTax:</span> <br />
                                                    <span>VAT:</span> <br />
                                                    <span>Grand Total:</span> 
                                                    </td>                                          
                                                  <td width="60%" align="right">
                                                  
                                                  	<span style=" width:100%; margin-right:15%">Php  <?php echo number_format($gtotal,2); ?>&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:15%">  <?php echo number_format($wtax,2); ?>&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:15%">  <?php echo number_format($vat,2); ?>&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:15%; font-weight:600">Php  <?php echo number_format($gtotal_afterTax,2); ?>&nbsp;</span><br />
                                                  
                                                  </td>
                                                  
                                                </tr>
                                               
                                                <tr>                                      
                                                  <th class="a-center">Amount in Words: <?php echo $this->Mmm->chequeTextFormat($gtotal_afterTax);?></th>                                          
                                                  <th width="30%"></th>                                                 
                                                </tr>
                                               	<?php
                                                }
												?>
                                                
                                                <?php
                                                //for cash requests
												if($voucher[0]['transaction_type'] == 'Cash Request'){
												?>
                                                <tr>                                      
                                                   	<td align="right">
                                                  		<span>Total:</span><br /> 
                                                 	</td>                                          
                                                  	<td width="10%" align="right">                                                 
                                                  		<span style=" width:100%; margin-right:35px">Php  <?php echo number_format($gtotal,2); ?>&nbsp;</span><br />
                                                    	<br />                                                 
                                                  	</td>                                                  
                                                </tr>
                                                <tr style="display:none">                                       
                                                  	<th class="a-center ">
                                                  		Amount in Words: 
                                                  		<span id="amount_words">
												  			<?php echo $this->Mmm->chequeTextFormat($gtotal); ?>
                                                        </span>
                                                	</th>                                                  
	                                              	<th width="10%"></th>                                                  
                                                </tr>
                                               <?php
                                                }
												?>
                                                 
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
        		<?php echo $this->Mmm->createCSRF() ?>
        	 	<input type="hidden" name="amount" id="amount" value="<?php echo $gtotal; ?>">
           		<input type="hidden" name="payto" id="payto" value="<?php echo $payto; ?>">
               	<input type="hidden" name="delid" id="delid" value="<?php echo $delid ?>">
                <input type="hidden" name="voucher_id" id="voucher_id" value="<?php echo $voucher_id ?>">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['abas_login']['userid']; ?>">
                
        </form>
        
       
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" onclick="
            														if(confirm('You are about to approve this voucher.')){
                                                                    	document.forms['vApprovalForm'].submit();
                                                                     }else{
                                                                     	return false;
                                                                     }   
                                                                    
                                                                    ">Approve</button>
        </div> &nbsp;
        <br />
</div>