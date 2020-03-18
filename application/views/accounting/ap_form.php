<?php
	/*
		This for should handle creation of vouchers coming from
		1. Purchase
		2. Request For Payment
	*/
	
	
	$grand_total		=	0;
	$withholding_tax	=	0;
	$value_added_tax	=	0;
	$bankoptions		=	"";
	$vesseloptions		=	"";
	$departmentoptions	=	"";
	$contractoptions	=	"";
	$voucher_number		=	"";
	$detailtable		= 	"";
	$payto				=	"";	
	$amount				=	0;
	$date_label			=	'';
	$payee_type			=	'';
	$transaction_id		=	'';
	////////////////////////////////////////////////////////////////
	//GENERATE VOUCHER NUMBER (have to transfer this to controller)		
	////////////////////////////////////////////////////////////////
	
	if(isset($company_id)){
		if(is_numeric($company_id)){
		
			$voucher_number_serial_sql	=	"SELECT count(voucher_number) as voucher_count FROM ac_vouchers WHERE 1=1 AND company_id=".$company_id;
		
		}else{
			$this->Abas->sysMsg("sucmsg", "Problem occured, missing company, please contact IT admin.");
			die();
		}
	}else{
		$this->Abas->sysMsg("errmsg", "Company not found, please contact admin!");
		exit;
	}
	
	
	//for po transaction only?
	
	$voucher_prefix		=	""; // make it as default
	
	if($ttype == 'po'){
		
	
		//var_dump($payee['issues_reciepts']);
		if($payee['issues_reciepts']==1) {
			//$voucher_number_serial_sql	.=	" AND bir_visible=1";
			$voucher_prefix				=	"";
			
		}
		else {
			$voucher_prefix				=	"W";
			//$voucher_number_serial_sql	.=	" AND bir_visible=0";
		}
	}
	
	
	$voucher_number					=	$this->db->query($voucher_number_serial_sql);	
	
	if($voucher_number) {		
		//var_dump($voucher_number->row()); exit;
		if($voucher_number=(array)$voucher_number->row()) {
			
			$x = 	(int)$voucher_number['voucher_count'] + 1;
			
			$voucher_number			=	$voucher_prefix.str_repeat('0', 8).$x;	
			
			//recheck if existing
			$sq = "SELECT * FROM ac_vouchers WHERE company_id=".$company_id." AND voucher_number = '".$voucher_number."'";
			
			$chk = $this->db->query($sq);
			
			if($chk->num_rows() > 0){
				//add count to voucher number
				$x = 	$x + 1;
				$voucher_number			=	$voucher_prefix.str_repeat('0', 8).$x;	
			}
			
		}
	}
	
	////////////////////////////////////////////////////////////////
	//END GENERATING VOUCHER
	////////////////////////////////////////////////////////////////
	
	if($ttype== 'non-po'){
		
		//NOTE: $apv_info is the request for payment info
		$date_label			=	'Request ';
		$amount				=	$apv_info[0]['amount'];
		$payee_type			=	$apv_info[0]['payee_type']; //needed in voucher table
		$labelTitle			= 	'RFP Number';
		$ref_no				= 	$apv_info[0]['id'];
		$remark				= 	$apv_info[0]['purpose'];
		//$transaction_id		= 	$ac_entries[0]['transaction_id']; //where to get this?
		$apv_no				= 	$apv_info[0]['id'];
		$date_created		= 	date('F j, Y',strtotime($apv_info[0]['request_date']));
		//$request_payment[0]['amount'];
		
		/*
		$detailtable		=	"<tr>";
			$detailtable	.=	"<td colspan='4'>".$request_payment[0]['particular']."</td>";
			$detailtable	.=	"<td align='right' colspan='1'>P".number_format($request_payment[0]['amount'],2)."&nbsp;</td>";
		$detailtable	.=	"</tr>";
		*/
	}
	else{
		/*FOR PURCHASE*/
		
		//get payable amount
		foreach($ac_entries as $payable){			
			if($payable['id']== TRADE_PAYABLE){				
				$amount				=	$payable['credit_amount'];				
			}			
		}
		$type 				= 	'po';
		$labelTitle			=	'PO Number';
		$ref_no				=	$ap_voucher[0]['po_no'];
		$payto				=	$ap_voucher[0]['payee'];
		$detailtable		=	"<tr><td colspan='99'>No information found!</td></tr>";
		//$amount				=	$ap_amount['credit_amount'];

		//$amount				=	$ap_amount['credit_amount']; 
		$remark				=   $ap_amount['remark'];
		$transaction_id		= 	$ap_amount['transaction_id']; //need to review this
		$date_label			=	'Delivery ';
		$date_created		= 	date('F j, Y',strtotime($ap_voucher[0]['date_created']));
		$apv_no				= 	$ap_voucher[0]['id'];
		
		if(!empty($delivery_detail)) {
			
			$detailtable	=	"";
			foreach($delivery_detail as $d){
				$item			=	$this->Inventory_model->getItem($d['item_id']);
				$item			=	$item[0];
				$line_total		=	$d['quantity'] * $d['unit_price'];
				$grand_total	=	$grand_total + $line_total;
				$detailtable	.=	"<tr>";
					$detailtable	.=	"<td>".$item['item_code']."</td>";
					$detailtable	.=	"<td>".$item['description']."</td>";
					$detailtable	.=	"<td>".$d['quantity']." ".$d['unit']."</td>";
					$detailtable	.=	"<td>P".$d['unit_price']."</td>";
					$detailtable	.=	"<td>P".number_format($line_total,2)."</td>";
				$detailtable	.=	"</tr>";
			}
			
			//vat_computation = Inclusive,Exclusive, Non-Vat
			if($payee['issues_reciepts']) {
				$withholding_tax=	0;
				if($payee['vat_computation']!='Non-Vat') {
					$value_added_tax	=	$grand_total * .12;
					if($payee['vat_computation']=="Inclusive") {
						$grand_total	=	$grand_total - $value_added_tax;
					}
				}
			}
			$total_after_tax	=	$grand_total+$value_added_tax+$withholding_tax;
		}
	}
	
	//trap if amount is missing might be that the record was not processed properly - need to track source of error
	if($amount < 1){
		$this->Abas->sysMsg("errmsg", "missing amount, please contact admin!");
		echo "Amount is missing, please contact admin.";
		exit;
	
	}
	// get latest voucher number
	$bir_hidden		=	$this->db->query("SELECT MAX(voucher_number) AS last_voucher_number FROM ac_vouchers WHERE bir_visible=0");
	$bir_visible	=	$this->db->query("SELECT MAX(voucher_number) AS last_voucher_number FROM ac_vouchers WHERE bir_visible=1");
	if($bir_hidden)		$bir_hidden		=	(array)$bir_hidden->row();
	if($bir_visible)	$bir_visible	=	(array)$bir_visible->row();
	$existing_voucher_numbers["bir_visible"]	=	!empty($bir_visible['last_voucher_number'])? $bir_visible['last_voucher_number']:"None";
	$existing_voucher_numbers["bir_hidden"]		=	!empty($bir_hidden['last_voucher_number'])? $bir_hidden['last_voucher_number']:"None";
	
	
	
	$rid = $ap_voucher[0]['id'];
	$journal_id = $ap_voucher[0]['id'];
	
	
	//TO HANDLE BANK ACCOUNTS
	/*END PURCHASE*/
	if(!empty($banks)) {
		foreach($banks as $b){
			$bankoptions	.=	"<option value='".$b['id']."'>".$b['name']." (".$b['code'].") </option>";
		}
	}
	if(!empty($vessels)) {
	
		foreach($vessels as $v){
				
			$vesseloptions	.=	"<option value='".$v->id."'>".$v->name." </option>";
		}
	}
	if(!empty($departments)) {
	
		foreach($departments as $d){
		
			$departmentoptions	.=	"<option value='".$d->accounting_code."'>".$d->name." </option>";
		}
	}
	
	if(!empty($contracts)) {
	
		foreach($contracts as $c){
		
			$contractoptions	.=	"<option value='".$c['id']."'>".$c['reference_no']." </option>";
		}
	}
	
?>
<script type="text/javascript">

		$(document).ready(function () {

				$( "#check_date" ).datepicker();
				$( "#voucher_date" ).datepicker();  
	   
	   	});
</script>

<div class="panel panel-primary">
	<div class="panel panel-heading" style="min-height">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class "modal title">Check Voucher</h5>
	</div>
	<div class="panel-body">
			<form class="form-horizontal" role="form" name="create_voucher" id="create_voucher" action="<?php echo HTTP_PATH.'accounting/add_voucher/'.$ap_voucher[0]['id']; ?>" method="post">
            
            <div style="width:30%; float:right">
            	<div class="col-xs-6">
					<input type="button" class="btn btn-default btn-block" data-dismiss="modal" value="Cancel" />
				</div>
				<div class="col-xs-6">
                	
					<input type='button' value='Create' name='btnSubmit' class='btn btn-primary btn-block' onclick='  javascript:checkInput(); ' />
				</div>
            </div>
            
            <div style="width:99%;">
                <div class="col-md-6" style="margin-top:55px;">
                    <div class="form-group panel panel-default col-xs-12" style="height:365px">
                        <legend><h5 style="color:#006699; font-weight:600"><?php echo strtoupper($ttype)?> TRANSACTION </h5></legend>
                    
                    
                     	<div class="form-group col-xs-12">                
                            <div class="form-group">
                                <label for = 'department'><b>Company: </b> </label>&nbsp;&nbsp;<?php echo $company_name; ?>
                                
                            </div>
                            <div class="form-group">
                                <label for = 'transaction_date'><b><?php echo $date_label ?> Date	:</b> </label>&nbsp;&nbsp;<?php echo $date_created; ?>
                                
                            </div>
                            <div class = "form-group">
                                <label for= 'Payee'><b>Payee	:</b> </label>&nbsp;&nbsp;<?php  echo $payee_name; ?>
                                
                            </div>
                            <div class = "form-group">
                                <label for= 'Payee'><b>Payable Amount	:</b> </label>&nbsp;&nbsp;<?php echo number_format($amount,2); ?>
                                
                            </div>
                            <div class="form-group">
                                <label for = 'reference_no'><b><?php echo $labelTitle ?>:</b> </label>&nbsp;&nbsp;<?php echo $ref_no; ?>
                                
                            </div>
                            <div class="form-group">
                                <label for = 'remarks'><b>Remarks/Purpose:</label>&nbsp;&nbsp;<?php echo $remark; ?>
                                
                            </div>              
                    	</div>
                     
                	</div>
                </div>    
                <div class="col-md-6" style="margin-top:15px;" >
                    <div class="form-group panel panel-default col-xs-12">
                        <legend><h5 style="color:#006699; font-weight:600">Enter Check Voucher Information</h5></legend>
                    
                    
                    <input type="hidden" name="apv_no" id="apv_no" value="<?php echo $apv_no; ?>">
                    <input type="hidden" name="payee" id="payee" value="<?php echo $payee['id']; ?>">
                    <input type="hidden" name="del_id" id="del_id" value="<?php echo $ap_voucher[0]['rr_no']; ?>">
                    <input type="hidden" name="company_id" id="company_id" value="<?php echo $company_id; ?>">
                    <input type="hidden" name="type" id="type" value="<?php echo $ttype; ?>">
                    <input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo $transaction_id ?>">	
                    <input type="hidden" name="amount" id="amount" value="<?php echo $amount ?>">	
                    <?php echo $this->Mmm->createCSRF() ?>
                     
                     
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xs-6 col-sm-6">
                        <label for="check_no">Voucher Date: </label>
                        <input class="input-sm" type="text" placeholder="" name="voucher_date" id="voucher_date" style="width:100%">
                        </div>
                        <div class="col-xs-6 col-sm-6">
                        <label data-toggle="tooltip" data-placement="right" title="Visible: <?php echo $existing_voucher_numbers['bir_visible']; ?> ---------- Hidden: <?php echo $existing_voucher_numbers['bir_hidden']; ?>" for="voucher_no">Voucher Number: </label>
                        <input class="form-control input-sm" type="text" disabled="disabled" placeholder="Voucher Number" name="voucher_no_display" id="voucher_no_display" value="<?php echo $voucher_number; ?>">
                        <input class="form-control input-sm" type="hidden" placeholder="Voucher Number" name="voucher_no" id="voucher_no" value="<?php echo $voucher_number; ?>">
                        </div>
                        &nbsp;
                    </div>
                     
                        <!--
                     
                     <div class="form-group col-xs-12">
                        <label data-toggle="tooltip" data-placement="right" title="Visible: <?php echo $existing_voucher_numbers['bir_visible']; ?> ---------- Hidden: <?php echo $existing_voucher_numbers['bir_hidden']; ?>" for="voucher_no">Voucher Number: </label>
                        <input class="form-control input-sm" type="text" disabled="disabled" placeholder="Voucher Number" name="voucher_no_display" id="voucher_no_display" value="<?php echo $voucher_number; ?>">
                        <input class="form-control input-sm" type="hidden" placeholder="Voucher Number" name="voucher_no" id="voucher_no" value="<?php echo $voucher_number; ?>">
                    </div>
                     --->
                     <div class="form-group col-xs-12" style="display:none">
                        <label>Voucher Type:</label>
                        <select class="form-control input-sm" name="voucher_type1" id="voucher_type1">
                            <option value="">Choose One</option>
                            <option value="Check Voucher">Check</option>
                            <option value="Cash Voucher">Cash</option>
                            <!--
                            <option value="Disbursement Voucher">Disbursement</option>
                            <option value="Debit Memo">Debit Memo</option>
                            <option value="Credit Memo">Credit Memo</option>
                            --->
                        </select>
                    </div>
                        <input type="hidden" name="voucher_type" id="voucher_type" value="Check Voucher">
                        <input type="hidden" name="reference_to_table" id="reference_to_table" value="<?php echo $ref_no ?>" />
                        <input type="hidden" name="payee_type" id="payee_type" value="<?php echo $payee_type ?>" />
                        <input type="hidden" name="sel_accounts" id="sel_accounts" />
                        
                    
                    <div class="form-group col-xs-12">
                        <label for="bank">Credit to Bank: </label>
                        <select class="form-control input-sm" name="bank" id="bank">
                            <option value="">Select credit account</option>
                            <?php echo $bankoptions; ?>
                        </select>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xs-6 col-sm-6">
                        <label for="check_no">Check Number: </label>
                        <input class="input-sm" type="text" placeholder="Check Number" name="check_no" id="check_no" style="width:100%">
                        </div>
                        <div class="col-xs-6 col-sm-6">
                        <label for="check_no">Check Date: </label>
                        <input class="form-control input-sm" type="text" placeholder="Check Date" name="check_date" id="check_date" style="width:100%">
                        </div>
                        &nbsp;
                    </div>
                    
                    <div class="col-xs-12 col-sm-6" style="display:none">
                        <label for="wtax">Witholding Tax: </label>
                        <input class="form-control input-sm numeric-only" type="text" placeholder="Withholding Tax" name="wtax" id="wtax" value="<?php echo $withholding_tax; ?>">
                    </div>
                    <div class="col-xs-12 col-sm-6" style="display:none">
                        <label for="vat">Value Added Tax: </label>
                        <input class="form-control input-sm numeric-only" type="text" placeholder="Value Added Tax" name="vat" id="vat" value="<?php echo $value_added_tax; ?>">
                    </div>
                    <div class="col-xs-12">
                        <label for="remark">Particular: </label>
                        <textarea name="remark" class="form-control input-sm" id="remark" rows="3"><?php echo $remark ?></textarea>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix"><br/></div>
                    
                
                        				<input type="hidden" id="total_entry"  name="total_entry" value="0"/>
                                        
                                        <input type="hidden" id="total_credit"  name="total_credit" value="0"/>
                                       <input type="hidden" id="total_debit"  name="total_debit" value="0"/>
                                        <input type="hidden" id="total_credit_amount"  name="total_credit_amount" value="0"/>
                     
                        
                       
                    </div>
    
               </div>
			</div>	
					
 			</form>  
	</div> 		
           
           <?php if($ttype=='non-po'){ ?>
           <div class="panel-body" style="width:94%; margin-top:-10px">

				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#accounting_entries" data-toggle="tab" style="color:#003399"><b>Enter Accounting Entries:</b></a>
					</li>
					
				</ul>
                
                <div class="tab-content">
						<div class="tab-pane active" id="accounting_entries">
							<div style="margin-top:10px; margin-left:0px; background:#F4F4F4">
                        	
                            <table style="width:90%; margin-left:20px; border-bottom:solid thin">
                            	<tr>
                                	<td width="10%">Department:</td>
                                    <td width="15%">Vessel:</td>
                                    <td width="15%">Contract:</td>
                                    <td width="30%">GL Account:</td>
                                    <td width="20%">Amount:</td>
                                    <td width="10%">Debit/Credit</td>
                                    
                                </tr>
                                <tr>
                                	<td><select class="form-control input-sm" name="department_code" id="department_code"  style="width:60px">
                                            <option value="00">00</option>
                                            <?php echo $departmentoptions; ?>
                                        </select>
                            		</td>
                                    <td><select name="vessel_code" id="vessel_code"  style="width:50px" class="form-control input-sm">
                                            <option value="000">000</option>
                                            <?php echo $vesseloptions; ?>
                                        </select></td>
                                    <td><select name="contract_code" id="contract_code"  style="width:70px" class="form-control input-sm">
                                            <option value="0000">0000</option>
                                            <?php echo $contractoptions; ?>
                                        </select></td>
                                    <td><input type="text" id="account_label" class="ui-autocomplete-input account_label form-control input-sm" name="account_label[]" style="width:250px" placeholder="Account Number (Autocomplete)" />
                                		<input type="hidden" id="debit_account" class="debit_account" name="debit_account" /> </td>
                                    <td><input type="text" id="entry_amount"  name="entry_amount" style="width:100px" class="form-control input-sm"></td>
                                    <td>
                                    	<select name="entry_type" id="entry_type" class="form-control input-sm">
                                        	<option></option>
                                            <option value="debit">Debit</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                    	
                                    </td>
                                    <td>
                                    	
                                    	<button onclick="addEntry()">Add</button>
                                    </td>
                                    
                                </tr>
                                <tr>
                                	<td>Memo:</td>
                                	<td colspan="6"><br><input type="text" id="memo" name="memo" class="form-control"/></td>
                                </tr>
                            </table>
                            
                             
                            </div>
                            
                            
                        </div>
                        
                        </div>
                        
                        <div id="entry_view">
						<table id="datatable-responsive" style="margin-top:10px; float:left" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>                                          
                                          <th width="15%">Account Code</th>                                          
                                          <th width="15%">Account Name</th> 
                                          <th width="15%">Debit</th>                                          
                                          <th width="15%">Credit</th> 
                                          <th width="45%">Memo</th>
                                          <th width="10%">*</th>                                       
                                        </tr>
                                      </thead>								
                                      <tbody>
                                        <tr>
                                          <td></td>
                                          <td align="center"></td>
                                          <td align="right"></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                        </tr>
                                      </tbody>
                        </table>
                        </div>
					</div>
                </div>
          </div>       
            <?php } ?> 
            <?php if(isset($ac_entries)){ ?>
            <div class="panel-body" style="width:94%; margin-top:-10px">

				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#accounting_entries" data-toggle="tab">Accounting Entries</a>
					</li>
					<li><a href="#tdetail" data-toggle="tab">Transaction Detail</a>
					</li>
				</ul>
				<div class="panel-default">
				
				<div class="tab-content">
					<div class="tab-pane active" id="accounting_entries">

						<table id="datatable-responsive" style="margin-top:10px; float:left" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>                                          
                                          <th width="12%">Date Posted</th> 
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
                                          
                                          <td><?php echo date('F j, Y', strtotime($v['created_on'])) ?></td>
                                          
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
					<div class="tab-pane" id="tdetail">
							<table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>                                          
                                          <th width="12%">Date Posted</th> 
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
                                          
                                          <td><?php echo date('F j, Y', strtotime($v['created_on'])) ?></td>
                                          
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
			<?php }?>
		</div>
	</div>
</div>

<script>
	
	$( ".account_label" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>accounting/autocomplete_account",
	minLength: 2,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$( ".account_label" ).val( ui.item.label );
		$( ".debit_account" ).val( ui.item.value );
		return false;
	}
});

	
	$('[data-toggle="tooltip"]').tooltip();
	$(".numeric-only").keydown(function (e) {
		console.log(e);
		if (
			$.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || // Allow: backspace, delete, tab, escape, enter and .
			(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || // Allow: Ctrl+A, Command+A
			(e.keyCode >= 35 && e.keyCode <= 40) // Allow: home, end, left, right, down, up
		) {
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
	
	function checkInput() {
		var msg="";
		var type = $('#ttype').val();
		var credit = $('#total_credit').val();
		var debit = $('#total_debit').val();
		//var patt1=/^[0-9]+$/i;
		//var patt1=/^\d+(\.\d+)*$/i;
		var voucher_number=document.forms.create_voucher.voucher_no.value;
		if (voucher_number==null || voucher_number=="" || voucher_number=="Voucher Number") {
			msg+="Voucher number is required! <br/>";
		}
		var voucher_date=document.forms.create_voucher.voucher_date.value;
		if (voucher_date==null || voucher_date=="") {
			msg+="Voucher date is required! <br/>";
		}
		
		var voucher_type=document.forms.create_voucher.voucher_type.value;
		if (voucher_type==null || voucher_type=="" || voucher_type=="Voucher Type") {
			msg+="Voucher type is required! <br/>";
		}
		var bank=document.forms.create_voucher.bank.value;
		
		if (bank==null || bank=="" || bank=="Bank") {
			msg+="Bank is required! <br/>";
		}
		var check_number=document.forms.create_voucher.check_no.value;
		if (check_number==null || check_number=="" || check_number=="Check Number") {
			msg+="Check number is required! <br/>";
		}
		var check_date=document.forms.create_voucher.check_date.value;
		if (check_date==null || check_date=="") {
			msg+="Check date is required! <br/>";
		}
		var remark=document.forms.create_voucher.remark.value;
		if (remark==null || remark=="") {
			msg+="Please enter particular.<br/>";
		}
		//for non-po transaction only
		var type=document.forms.create_voucher.type.value;
		if (type=="non-po") {
						
			var sel_accounts=document.forms.create_voucher.sel_accounts.value;
			if (sel_accounts==null || sel_accounts=="") {
				msg+="Please enter debit account.<br/>";
			}
			
		}
						
		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			
			if(type == 'non-po'){
			
				//credit = Math.round(credit * 100) / 100;
				//debit = Math.round(debit * 100) / 100;
				
				
				//alert(credit);
				cre = formatNumber(credit);
				deb = formatNumber(debit);
				//alert(cre);
				//alert(deb);
				if(cre === deb){
				
					if(confirm("You are about to create Check Voucher, click Ok to continue.")){
					
						document.getElementById("create_voucher").submit();
						return true;
					
					}
				
				}else{
					
					alert('Sorry your entry is not balance, please check and try again.');
					return false;
				
				}
			//PO
			}else{
			
				if(confirm("You are about to create Check Voucher, click Ok to continue.")){
					
						document.getElementById("create_voucher").submit();
						return true;
					
					}
			
			}
		}
	}
	//user for rounding number
	function roundToTwo(num) {    
		return +(Math.round(num + "e+2")  + "e-2");
	}
	
	
	//use to limit amount to 2 digits only
	function formatNumber(num) {
	  num = String(num);
	  if(num.indexOf('.') !== -1) {
		var numarr = num.split(".");
		if (numarr.length == 1) {
		  return Number(num);
		}
		else {
		  return Number(numarr[0]+"."+numarr[1].charAt(0)+numarr[1].charAt(1));
		}
	  }
	  else {
		return Number(num);
	  }  
	}
	function addEntry(){
		
		var total_amount = $('#total_entry').val();
		var total_payable = $('#amount').val(); //credit to bank
		
		var total_credit = $('#total_credit').val();
		var total_debit = $('#total_debit').val();
		
		var dept = $('#department_code').val();
		var vess = $('#vessel_code').val();
		var con = $('#contract_code').val();
		var acct = $('#debit_account').val();
		var acts = $('#sel_accounts').val();
		var amt = $('#entry_amount').val();
		var entry_type = $('#entry_type').val();
		var memo = $('#memo').val();
		var comp = 0;
		//var remark =  $('#remark').val();
		var ent = new Array();
		//alert(acts);
		
		if(acct == ''){
			alert('Please select GL account.');
			
			return false;
		}else if(amt == ''){ 
			alert('Please enter amount.');
			document.getElementById( 'entry_amount' ).focus();
			return false;	
		}else if(entry_type == ''){ 
			alert('Please select if debit or credit.');
			document.getElementById( 'entry_type' ).focus();
			return false;		
		}else{
			//have to get total amount against amount to be paid
			/*
			total_amount =  parseFloat(total_amount) + parseInt(amt);
			if(total_amount > total_payable){
				alert('Warning, total amount exceeded the amount payable.');
				return false;
			}
			*/
			
			//use check to see that credit and debit is balance
			//tcredit = parseFloat(total_payable);
			if(entry_type == 'credit'){
				if(total_credit == 0){
					total_credit = parseFloat(total_payable);
				}
				total_credit = parseFloat(total_credit) + parseFloat(amt);
			}else{			
				total_debit = parseFloat(total_debit) + parseFloat(amt);
			}
			
			
			
			//temporary fix
			if(total_credit == 0){
				total_credit = parseFloat(total_payable);
			}
			
			tcredit = roundNumber(total_credit,2);
			total_debit = roundNumber(total_debit,2);
			document.getElementById( 'total_credit_amount' ).value = tcredit;
			document.getElementById( 'total_credit' ).value = tcredit;
			document.getElementById( 'total_debit' ).value = total_debit;
			//document.getElementById( 'total_credit' ).value = total_credit.toFixed(2);
			//document.getElementById( 'total_debit' ).value = total_debit.toFixed(2);
			
			
			total_amount = parseFloat(total_amount) + parseFloat(amt);
			document.getElementById( 'total_entry' ).value = total_amount; 
			
			/*
			if(parseFloat(total_debit) !== parseFloat(tcredit)){
				alert('Warning, total amount exceeded the amount payable.');
				return false;
			}*/
			
			vals = dept+'|'+vess+'|'+con+'|'+acct+'|'+amt+'|'+entry_type+'|'+memo+','+acts;
			ent.push(vals);
			
			document.getElementById( 'sel_accounts' ).value = ent;
			
			$.post('<?php echo HTTP_PATH."accounting/voucher_entries/"; ?>',
			  { 'id':ent,'company':comp,'action':'receive' },
					function(result) {
						
						// clear any message that may have already been written
						
						document.getElementById( 'department_code' ).value ='00';
						document.getElementById( 'vessel_code' ).value ='000';
						document.getElementById( 'account_label' ).value ='';
						document.getElementById( 'contract_code' ).value ='0000';
						document.getElementById( 'entry_amount' ).value ='';
						document.getElementById( 'entry_type' ).value ='';
						document.getElementById( 'memo' ).value ='';

						$('#entry_view').html(result);

					}
			);
		
		}
		
		
		  
	
	}
	
	function roundNumber(num, dec) {
	   var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	   return result;
	}

	
	
	
		function delEntry(id){
 			
			//get total debit, credit
			var total_credit = $('#total_credit').val();
			var total_debit = $('#total_debit').val();
			
			//get amount and type
			id.split('|');
			var char =  id[id.length - 1];
			
			//alert(char);
			var myString = 'asd/f/df/xc/asd/test.jpg'
			var parts    = id.split('|');
			var t   = parts[parts.length - 1];
			var type = t.replace( ',' , "");			
			var v   = parts[parts.length - 2];
			//alert(type);
			//alert(v);
			
			if(type == 'debit'){
				//deduct amount
				total_debit = parseFloat(total_debit) - parseFloat(v);
				document.getElementById('total_debit').value = total_debit;
			}else{
				
				total_credit = parseFloat(total_credit) - parseFloat(v);
				document.getElementById('total_credit').value = total_credit;
			}
			
			var acts = $('#sel_accounts').val();			
			de = acts.replace(id, "");
			comp = 0;
			//alert(de);
			document.getElementById('sel_accounts').value = de;
			se2=  document.getElementById('sel_accounts').value;
			
			

			$.post('<?php echo HTTP_PATH."accounting/voucher_entries/"; ?>',
				   { 'id':se2,'action':'del' },
						function(result) {
							//alert(result);
							// clear any message that may have already been written
							
							$('#entry_view').html(result);
						}
			)
		}
	
</script>