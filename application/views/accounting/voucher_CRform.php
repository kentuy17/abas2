<?php 
	//var_dump($delivery_summary);
	//var_dump($cash_advance[0]['requested_by']); exit;
	$requested_by = $this->Abas->getEmployee($cash_advance[0]['requested_by']);
	$department  = $this->Abas->getDepartments($cash_advance[0]['department']);
	//$payto = $delivery_summary[0]['supplier_id']; //direct to the who will receive the payment (company or person)
	//var_dump($supplier['name']);exit;
?>

<script>

	function computeTax(){
	
		var w = document.getElementById('wtax').value;
		var a = document.getElementById('amount').value;
		var wtax = '0.00';
		var gtotal = '0.00';
		
		if(w != ''){
			wtax = w * a / 100;
			gtotal = a - wtax;
		}
		
		$("#wtax_span").html(wtax);
		$("#gtotal_afterTax").html('Php  '+gtotal);
		
		return wtax;		
		
		//update element gtotal and wtax
		
	}
	
	function computeVat(){
	
		var v = document.getElementById('vat').value;
		var a = document.getElementById('amount').value;
		var vat = '0.00';
		var gtotal = '0.00';
		
		if(v != ''){
			vat = v * a / 100;
			gtotal = a - vat;
		}
		
		$("#vat_span").html(vat);
		$("#gtotal_afterTax").html('Php  '+gtotal);
		
		return vat;		
		
		
	}

</script>

<div style="width:800px" class="printMe">
        <div class="panel panel-danger">
            <div class="panel-heading">
            	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><strong>Voucher</strong></h4>
            </div>
            
        
        <div class="modal-body">
            
            <form class="form-horizontal" role="form" name="vForm" id="vForm" action="<?php echo HTTP_PATH.'accounting/add_voucher'; ?>" method="post">
            <?php echo $this->Mmm->createCSRF() ?>
            <div style="margin-bottom:40px">
                <div style="float:left; width:400px;margin-bottom:40px">
                    
                    <div>
                    	<table width="300px" cellspacing="25">
                        	<tr>
                            	<td><strong>Vouche No.: </strong></td>
                                <td><input class="form-control input-sm" type="text" name="voucher_no" id="voucher_no" ></td>
                            </tr>
                            <tr>
                            	<td><strong>Voucher Type.:</strong></td>
                                <td>						<select class="form-control input-sm" name="voucher_type" id="voucher_type">
                                                                    <option value=""></option>
                                                                    <option value="Check Voucher">Check Voucher</option>
                                                                    <option value="Cash Voucher">Cash Voucher</option>
                                                                    <option value="Disbursement Voucher">Disbursement Voucher</option>
                                                              </select></td>
                            </tr>
                            
                            <tr>
                            	<td><strong>Bank: </strong></td>
                                <td>
                                	<select class="form-control input-sm" name="bank" id="bank">
                                                                    <option value=""></option>
                                                                    <?php foreach($banks as $b){ ?>
                                                                     <option value="<?php echo $b['id'] ?>"><?php echo $b['name']." (".$b['account_no'].")" ?></option>
                                                                    <?php } ?>
                                                                    
                                                                    
                                                              </select>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                            	<td><strong>Check No.: </strong></td>
                                <td> <input class="form-control input-sm" type="text" name="check_no" id="check_no"></td>
                            </tr>
                            <tr>
                            	<td><strong>Witholding Tax: </strong></td>
                                <td> <input class="form-control input-sm" type="text" name="wtax" id="wtax" onblur="computeTax();"></td>
                            </tr>
                            <tr>
                            	<td><strong>VAT: </strong></td>
                                <td> <input class="form-control input-sm" type="text" name="vat" id="vat" onblur="computeVat();"></td>
                            </tr>
                            <tr style="display:none">
                            	<td><strong>Credit (Account): </strong></td>
                                <td> 						<select name="credit_account" id="credit_account">
                                                                    <option value=""></option>
                                                                    <option value="4101100">Local ICHS</option>
                                                                    <option value="4101120">Time Charter</option>
                                                                    <option value="4101140">Voyage Charter</option>
                                                            </select>
                                                              <select name="credit_account_child" id="credit_account_child">
                                                                    <option value=""></option>
                                                                    <option value="4101100">MV Daniel</option>
                                                                    <option value="4101120">MV Mark</option>
                                                                    <option value="4101140">Barge Anika</option>
                                                            </select>
                                                              </td>
                            </tr>
                             <tr style="display:none">
                            	<td><strong>Debit (Account): </strong></td>
                                <td> 						<select name="debit_account" id="debit_account">
                                                                    <option value=""></option>
                                                                    <option value="4101100">Local ICHS</option>
                                                                    <option value="4101120">Time Charter</option>
                                                                    <option value="4101140">Voyage Charter</option>
                                                            </select>
                                                              <select name="debit_account_child" id="debit_account_child">
                                                                    <option value=""></option>
                                                                    <option value="4101100">MV Daniel</option>
                                                                    <option value="4101120">MV Mark</option>
                                                                    <option value="4101140">Barge Anika</option>
                                                            </select>
                                                              </td>
                            </tr>
                        </table>
                        
                    </div>   
                  
                    
                </div>
               
                <div style="float:right; width:300px; margin-top:30px">                                
                    <div><strong>Date: <?php echo date('F j, Y')  ?></strong></div>            
                    <div><strong>Pay To: <?php echo $requested_by['full_name'] ?></strong></div>            
                    <div><strong>Department: <?php echo $department[0]->name ?></strong></div>            
                    <div><strong>Request No.: <?php echo $cash_advance[0]['id'] ?></strong></div>
                    
                </div>
            
            </div>
            
            <br /><br /><br /><br />
            <br /><br /><br /><br />
            <div style="margin-top:50px">
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
													
															
													?>	
                                                   
                                                 
                                                   		<tr>
                                                    		<td width="10%"><?php echo $cash_advance[0]['type'] ?></td>
                                                            <td width="30%" align="left"><?php echo $cash_advance[0]['purpose'] ?></td>
                                                            
                                                            
                                                            <td width="20%" align="right"><?php echo number_format($cash_advance[0]['amount'],2); ?></td>
														                                                    
                                                    
                                                    	
                                                    	</tr>
                                                                                          
                                                   
                                                    
                                                    </table>            
                                                    </div>
                                                  </td>
                                                  
                                                                                      
                                                  
                                                  
                                                </tr>
                                             	
                                                
                                                
                                                <tfoot>
                                                <tr>                                      
                                                   	<td align="right">
                                                  		<span>Total:</span><br /> 
                                                 	</td>                                          
                                                  	<td width="10%" align="right">                                                 
                                                  		<span style=" width:100%; margin-right:35px">Php  <?php echo number_format($cash_advance[0]['amount'],2); ?>&nbsp;</span><br />
                                                    	<br />                                                 
                                                  	</td>                                                  
                                                </tr>
                                                <tr style="display:none">                                       
                                                  	<th class="a-center ">
                                                  		Amount in Words: 
                                                  		<span id="amount_words">
												  			<?php echo $this->Mmm->chequeTextFormat($cash_advance[0]['amount']); ?>
                                                        </span>
                                                	</th>                                                  
	                                              	<th width="10%"></th>                                                  
                                                </tr>
                                                
                                              </tfoot>
                                                
                                              </tbody>
                                            </table>   
                                            <input type="hidden" name="amount" id="amount" value="<?php echo $cash_advance[0]['amount'] ?>">
                                            <input type="hidden" name="payto" id="payto" value="<?php echo $cash_advance[0]['requested_by'] ?>">
                                            <input type="hidden" name="delid" id="delid" value="<?php echo $cash_advance[0]['id'] ?>">
                                            <input type="hidden" name="request_type" id="request_type" value="Cash Request">
            </div>
            <div>
                <span><strong>Remarks:</strong></span>
            
            </div>   
            
        </div>
        </form>
        
        
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success"                 
                        onclick="

                        	var v = document.getElementById('voucher_no').value;
                           
                            var vt = document.getElementById('voucher_type').value;
                            var b = document.getElementById('bank').value;
                            var c = document.getElementById('check_no').value;                            
                            
                            if(v == ''){
                            	alert('Please enter voucher number.');
                                document.getElementById('voucher_no').focus;
                                return false;
                            }else if(vt == ''){
                            	alert('Please select voucher type.');
                                document.getElementById('voucher_type').focus;
                                return false;
                            }else if(b == ''){
                            	alert('Please select bank.');
                                document.getElementById('bank').focus;
                                return false;
                            }else if(c == ''){
                            	alert('Please enter check number.');
                                document.getElementById('check_no').focus;
                                return false;
                            }else{    
                        		document.forms['vForm'].submit();
                        	}
                        ">Create</button>     
           
        </div> 
</div>