<?php
	
	$credit_account = PAYABLE_OTHERS;
	
	if($type == 'po'){
		
		$debit_account = AP_CLEARING;
		$supplier=$this->Abas->getSupplier($ap[0]['supplier_id']);
		$company=$this->Abas->getCompany($ap[0]['company']);
		
		$date=$ap[0]['tdate'];
		$rid = $ap[0]['rid']; 	//reference_id
		$tid = $ap[0]['tid'];	// transaction_id
		$journal_id = $ap[0]['id'];
		$payee = $supplier['name'];
		
		$append_title = 'Clearing';
		$date_title = 'Delivery Date';
		$ref_title = 'PO#';
		$ref_id = $ap[0]['po_no'];
		$purpose = $ap[0]['remark'];
		
	//$supplier=$supplier->result_array();
	//$supplier_id= $supplier['name'];
	//$this->Mmm->debug($supplier_id);
	  		$id = $ap[0]['reference_id'];
            $doc_rr = $ap[0]['doc_rr'];
            $doc_dr = $ap[0]['doc_dr'];
            $doc_po = $ap[0]['doc_po'];
            $is_cleared = $ap[0]['is_cleared'];

			$rr_chk = ($ap[0]['doc_rr'] == 1) ? 'checked="checked"' : '';
			$dr_chk = ($ap[0]['doc_dr'] == 1) ? 'checked="checked"' : '';
			$po_chk = ($ap[0]['doc_po'] == 1) ? 'checked="checked"' : '';
	}
	
	if($type == 'non-po'){
	
		//this comes from different form (RFP form) and does not have ap clearing info it is directed to APV
		//may have to review and revise later
		//$debit_account = 105;
		
		$company=$this->Abas->getCompany($ap[0]['company_id']);
		$request=$this->Accounting_model->getRfp_ForVoucher($ap[0]['id']);
		$date= $ap[0]['request_date'];
		$tid = '';
		$ref_id = $ap[0]['id'];
		$journal_id = '';
		$append_title = '';
		$ref_title = 'RFP#';
		$rid = $ap[0]['id'];
		$purpose = $ap[0]['purpose'];
		
		$date_title = 'Request Date';
		
		if($ap[0]['payee_type'] == 'Supplier'){
			$p = $this->Abas->getSupplier($ap[0]['payee']);
			$payee = $p['name'];
		}elseif($ap[0]['payee_type'] == 'Employee'){
			$p = $this->Abas->getEmployee($ap[0]['payee']);
			
			$payee = $p['full_name'];
		}else{
			$payee = $ap[0]['payee_others'];
		}
	}
	
	
	
	if($company){
		$company_name = $company->name;
	}else{
		$company_name = 'Company not found.';
	}
	//get account for presentation
	//$credit_ac = $this->Accounting_model->getAccount($credit_account);
	$ap_amount = 0 ;
	 if($ac_entries == TRUE){
                                                
			foreach($ac_entries as $v){
														
				if($v['credit_amount'] > 0){
					$ap_amount = number_format($v['credit_amount'],'2');
				}
				                               
            }
	}		
	
	
?>
<!---
<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
<script src="<?php echo LINK.'assets/toastr/toastr.js'; ?>"></script>  
---> 
<div class="panel panel-primary">
	<div class="panel panel-heading" style="min-height">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class "modal title">Accounts Payable <?php echo $append_title ?></h5>
	</div>
	
		<div class="panel-body">
			<div>
            <form method='POST' enctype="multipart/form-data" name="apForm" action="<?php echo HTTP_PATH.'accounting/ap_clear'; ?>">   
					
                    <span style="float:right">
                       
						<input type="button" class="btn btn-success " onclick="
                        	
                             	submitMe();
                                
                        " value="Submit"/>
                    </span>
                    
                <fieldset class="panel panel-default col-md-12">
                	
                    
				 	<legend style="font-size:12px; color:#3366CC; font-weight:600">TRANSACTION DETAILS</legend>
                	
                    <div>
                    	<div class="form-group">
                            <label for = 'department'><b>APV Date <span style="font-size:9px">(required)</span>: </b> </label>&nbsp;&nbsp;
                            <input class="input-sm" type="text" name="apv_date" id="apv_date" required />
                            
                        </div>
                       
                    </div>
				
                    <div style="float:left">
                       <div class="form-group">
                            <label for = 'department'><b>Company: </b> </label>&nbsp;&nbsp;<?php echo strtoupper($company_name); ?>
                            
                        </div>
                        
                        <div class = "form-group">
                            <label for= 'Payee'><b>Payee	:</b> </label>&nbsp;&nbsp;<?php echo $payee; ?>
                            
                        </div>
                        <div class="form-group">
                            <label for = 'amount'><b>Amount	:</b> </label>&nbsp;&nbsp;<?php echo $ap_amount; ?>
    
                        </div>
                        
                    </div> 
                     <div style="float:left; margin-left:30px">   
                         <div class="form-group">
                            <label for = 'transaction_date'><b><?php echo $date_title ?>	:</b> </label>&nbsp;&nbsp;<?php echo date('F j, Y',strtotime($date)); ?>
                           
                        </div>
                        <div class="form-group">
                            <label for = 'reference_no'><b><?php echo $ref_title ?>:</b> </label>&nbsp;&nbsp;<?php echo $ref_id; ?>
    
                        </div>
                        <div class="form-group">
                            <label for = 'remarks'><b>Remarks/Purpose:</label>&nbsp;&nbsp;<?php echo $purpose; ?>
    
                        </div>
                      </b>
                        <hr />                   
                        
                    </div>
                
               <div>&nbsp;
               	
                
                
                
                     
                     
                	
					<div class="form-group checkbox">
						
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" style="width:50%; margin-top:-50px">
                        	<thead>
                            	<th colspan="3"><strong>Check submitted document </strong></th>
                            </thead>
                            <tbody>
                            <tr>
                            	<td width="20%" align="center">
                              
                                <input type="checkbox" name="doc_dr" id="doc_dr" <?php echo $dr_chk ?> onclick="checkMe(this.id)" align="absmiddle" >
                                </td >
                                <td width="40%">Sales Invoice</td>
                                <td width="40%"><input type="file" name="dr_file" id="dr_file" /></td>
                            </tr>
                            <tr>
                            	<td align="center"> <input type="checkbox" name="doc_po" id="doc_po" <?php echo $po_chk ?> onclick="checkMe(this.id)" align="absmiddle"></td>
                                <td>Purchase Order</td>
                                <td><input type="file" name="dr_file" id="dr_file" /></td>
                            </tr>
                            <tr>
                            	<td align="center"><input type="checkbox" name="doc_rr" id="doc_rr" <?php echo $rr_chk ?>  onclick="checkMe(this.id)" align="absmiddle"></td>
                                <td>Receiving Report</td>
                                <td><input type="file" name="dr_file" id="dr_file" /></td>
                            </tr>
                            </tbody>
                        </table>
                        
                        <input type="hidden" name="doc_drv" id="doc_drv" value="<?php echo $doc_dr;  ?>" >
                        
                       
                       
                        <input type="hidden" name="doc_pov" id="doc_pov" value="<?php echo $doc_po;  ?>" >
                        
                        
                        <input type="hidden" name="doc_rrv" id="doc_rrv" value="<?php echo $doc_rr;  ?>" >
                        
					</div>
                    
					
                   
               		<div class="form-group">
						<span style="float:right">
                        <input type="hidden" name="transaction_id" value="<?php echo $tid;  ?>">
                        <input type="hidden" name="reference_id" value="<?php echo $rid;  ?>">
                        <input type="hidden" name="journal_id" value="<?php echo $journal_id;  ?>">
                        <input type="hidden" name="ttype" value="<?php echo $type;  ?>">
						
                        </span>
					</div>
                 
               </div>
             
			
			</fieldset>
			   
          
		 </form>	
         
           <!--- Start of accounting entry for COD and advance payment -->
			<div style="margin-top:0px">      
            
            	<ul class="nav nav-tabs">
					<li class="active">
						<a href="#accounting_entries" data-toggle="tab">Accounting Entries</a>
					</li>
					
				</ul>
				<div class="panel-default">
				
                    <div class="tab-content">
                        <div class="tab-pane active" id="accounting_entries">
    
                            <table id="datatable-responsive" style="margin-top:10px; float:left" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                          <thead>
                                            <tr>                                          
                                              
                                              <th width="38%">Account</th>                                          
                                              <th width="15%">Debit</th>                                          
                                              <th width="15%">Credit</th>
                                              <th width="20%">Memo</th>                                          
                                                                                     
                                            </tr>
                                          </thead>								
                                          <tbody>
                                             <?php 
                                                
                                                
                                            if($ac_entries == TRUE){
                                                
                                                foreach($ac_entries as $v){
                                                                                            
                                                    $account_name = $this->Accounting_model->getAccount($v['coa_id']);
                                                    
    
                                            ?>
                                            <tr>
                                              
                                             
                                              
                                              <td><?php echo $account_name['code'].'-'.$account_name['name'] ?></td>
                                              
                                              <td align="right"><?php echo number_format($v['debit_amount'],2) ?></td>
                                              <td align="right"><?php echo number_format($v['credit_amount'],'2') ?></td>
                                              <td><?php echo $v['remark'] ?></td>
                                              
                                              
                                              
                                            </tr>
                                            <?php 
                                                
                                                }
                                            }else{
                                                    
                                                    echo '<tr><td  colspan="6">No entries found</td></tr>';
                                            }
                                            ?>
    
    
                                           
    
    
                                          </tbody>
                                        </table>
                        </div>
            	</div>
            </div>
            </div>   
             <!--- end of accounting entry for COD and advance payment -->
         
            
<script>
$(document).ready(function () {
	$( "#debit_label" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>accounting/autocomplete_account",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			toastr.clear();
		},
		select: function( event, ui ) {
			$( "#debit_label" ).val( ui.item.label );
			$( "#debit" ).val( ui.item.value );
			return false;
		}
	});
	$( "#credit_label" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>accounting/autocomplete_account",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			toastr.clear();
		},
		select: function( event, ui ) {
			$( "#credit_label" ).val( ui.item.label );
			$( "#credit" ).val( ui.item.value );
			return false;
		}
	});
	
	 $( "#apv_date" ).datepicker();
});



	function submitMe(){

	
		var total_checked = $('input[type="checkbox"]:checked').length;
		var apv_date = document.getElementById('apv_date').value;
		

    // TOTAL CHECK BOXES
		var total_boxes = $('input[type="checkbox"]').length
	
		if(total_checked === total_boxes) {
				if(confirm('You are about to create AP Voucher, click Ok to continue.')){
					
					if(apv_date == ''){
						alert('Please select APV date.');
						return false;
					}else{
						document.forms['apForm'].submit();
					}	
				}
		} else {
			 alert('Make sure that all required documents is submitted.'); return false;
		}
	 
	}
</script>