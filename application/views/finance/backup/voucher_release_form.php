<?php 
	//var_dump($delivery_summary);
	//var_dump($delivery_detail);
	//var_dump($voucher);
	
	$supplier = $this->Abas->getSupplier($delivery_summary[0]['supplier_id']);
	$payto = $delivery_summary[0]['supplier_id']; //direct to the who will receive the payment (company or person)
	//var_dump($supplier['name']);exit;
?>

<div style="width:800px">
        <div class="panel panel-danger">
            <div class="panel-heading">
            	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><strong>Voucher</strong></h4>
            </div>
            
        
        <div class="modal-body">
            
            
            <div style="margin-bottom:20px">
                <div>
            			<form class="form-horizontal" role="form" name="vForm" id="vForm" action="<?php echo HTTP_PATH.'accounting/release_voucher'; ?>" method="post">
               			<table width="300px" cellspacing="25">
                        	<tr>
                            	<td><strong>Official Receipt Number: </strong></td>
                                <td><input type="text" name="or_no" id="or_no" ></td>
                            </tr>             
                            
                            
                        </table>
                        		
                               	<input type="hidden" name="voucher_id" id="voucher_id" value="<?php echo $delivery_summary[0]['voucher_id'] ?>">
                        </form>
                         	<div style="margin-left:300px; margin-top:-23px">
                            
			            	<button type="button" class="btn btn-success btn-xs" onclick="document.forms['vForm'].submit();">Submit</button>
                            </div>
                </div>
                <hr />
                <div style="font-size:14px">
                <div style="float:left; width:400px; margin-top:10px">
                    <div><strong>Voucher No.: <?php echo $delivery_summary[0]['voucher_id'] ?></strong></div>
                    <div><strong>Invoice No.: <?php echo $delivery_summary[0]['receipt_num'] ?></strong></div>
                    <div><strong>Check Number: <?php echo $voucher[0]['check_num']  ?></strong></div>                    
                    
                </div>
                <div style="float:right; width:300px; margin-top:10px">            
                    
                    <div><strong>Date: <?php echo date('F j, Y')  ?></strong></div>            
                    <div><strong>Pay To: <?php echo $supplier['name'] ?></strong></div>            
                    <div><strong>PO No.: <?php echo $delivery_summary[0]['id'] ?></strong></div>
                    
                </div>
            	</div>
            </div>
            
            <br /><br /><br />
            <div style="margin-top:10px">
                <table id="datatable-responsive" style="margin-top:30px"  class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                              <thead>
                                                <tr>
                                                  
                                                  <th width="80%" style="border-right:#CCCCCC thin solid">Explanation of Payment</th>
                                                  <th width="20%">Amount</th>
                                                  
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
                                                <tr>
                                                  
                                                  
                                                   <td align="right">
                                                  	<span>Total:</span><br /> 
                                                    <span>WTax:</span> <br />
                                                    <span>VAT:</span> <br />
                                                    <span>Grand Total:</span> 
                                                    </td>                                          
                                                  <td width="10%" align="right">
                                                  
                                                  	<span style=" width:100%; margin-right:35px">Php  <?php echo number_format($gtotal,2); ?>&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:35px">  <?php echo number_format($wtax,2); ?>&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:35px">  <?php echo number_format($vat,2); ?>&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:35px; font-weight:600">Php  <?php echo number_format($gtotal_afterTax,2); ?>&nbsp;</span><br />
                                                  
                                                  </td>
                                                  
                                                </tr>
                                                <tr>
                                                  
                                                  
                                                  <th class="a-center ">Amount in Words: <?php echo $this->Mmm->chequeTextFormat($gtotal_afterTax);?></th>                                          
                                                      <th width="10%"></th>
                                                  
                                                </tr>
                                              </tfoot>
                                                
                                              </tbody>
                                            </table>   
                                            
            </div>
            <div>
                <span><strong>Remarks:</strong></span>
            
            </div>   
            
        </div>
        
        
        
        <div class="modal-footer">
           
        </div> 
</div>