<?php 
	
	
	
	$accounting_entry = $this->Accounting_model->getJournalEntry($ap_voucher[0]['journal_id']);
	//$delivery_info = $this->Inventory_model->getDelivery($ap_voucher[0]['po_no']);
	
	//$payee = $ap_voucher[0]['payee']; //direct to the who will receive the payment (company or person)
	//var_dump($supplier['name']);exit;
 	//$company = $this->Accounting_model->getPoOwner($delivery_summary[0]['po_no']); 
	
	$terms_display = ($terms!='') ? 'block' : 'none';
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php if(ENVIRONMENT=="development") { echo "DEV - "; } ?>AVEGA Business Automation System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />

	<?php $this->load->view('includes_header'); ?>

<style>

#header{margin-top:30px}
#title{ font-size:18px; font-weight:600}
#sub-title{ font-size:16px;}
#ttype{ font-size:18px; font-weight:600; margin-top:20px}
#rr_no{ margin-top:-20px; float:right; font-weight:600;}
#receive_from{ margin-top:10px; float:left}
#date{ margin-top:10px; margin-right:100px;  float:right}
#po_no{ margin-top:30px;; margin-left:-100px; float:left}
#pr_no{ margin-top:30px; margin-right:-50px; float:right}
#si_no{ margin-top:50px; margin-left:-100px; float:left}
#dr_no{ margin-top:50px; margin-right:-50px; float:right}
#items{ margin-top:20px;}
#received_by{ margin-top:80px; float:left}
#inspected_by{ margin-top:80px; margin-left:200px; float:left}
#noted_by{ margin-top:-20px; margin-left:500px; float:left; width:150px}
#copy{ margin-top:0px; font-size:12px; font-weight:600; float:left; position:absolute}


</style>

</head>
<body style="background:#FFFFFF">

<div class="well" style="height:100px">
    <p style="font-size:12px">
    	Note: You may need to set the correct paper size before printing, you can set paper size in the "More settings" in the print window .
    </p>
   <p>
    <div  style="position:absolute; margin-top:5px; margin-left:620px">
        
        <button id="printMe" type="button" class="btn-sm btn-success" onClick=' $("#voucherPrint").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print </button>&nbsp;&nbsp;      
      
        <a href="<?php echo HTTP_PATH.'accounting/'.$return_url ?>">
        <button type="button" class="btn-sm btn-default"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Back</button>
        </a>
    </div>
    </p>
</div>

<br />


<div id="voucherPrint" >

	<table style="width:800px; " >
            	 <tr>
                	<td colspan="3" align="right" style="font-size:10PX">TRANSACTION CODE: <?php echo strtoupper($ap_voucher[0]['id']) ?></td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center"><?php echo strtoupper($company->name) ?></td>
                </tr>
                <tr>
                	<td colspan="3" align="center"><?php echo $company->address ?></td>
                </tr>
                
                <tr>
                	
                    <td align="center" align="center" colspan="3">Telephone: <?php echo $company->telephone_no ?>   Fax: <?php echo $company->fax_no ?></td>
                    
                    
                </tr>
                <tr>           
                    <td align="right" colspan="3" >&nbsp;</td>                
                </tr>
                
                <tr>                	
                    <td align="center" id="title" colspan="3"><strong>ACCOUNTS PAYABLE VOUCHER</strong></td> 
                </tr>
                 <tr>           
                    <td align="right" colspan="3" ><strong style="color:#FF0000; font-size:16px">APV No: <?php echo $ap_voucher[0]['control_number'] ?></strong>&nbsp;</td>                
                </tr>
                <tr><td align="right" colspan="3" >&nbsp;</td></tr>
                <tr><td align="right" colspan="3" >&nbsp;</td></tr>
                <tr id="sub-title">
                	<td align="left"><strong>Payee: <?php echo $payee ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong>Date: <?php echo date('F j, Y',strtotime($entry[0]['posted_on']))?></strong></td>
                    
                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>PO Number: <?php echo $po_control_number; //$ap_voucher[0]['po_no']  ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong>Invoice #: <?php echo $ap_voucher[0]['invoice_no']  ?></strong></td>
                    
                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>RR #: <?php echo $rr_control_number; //$ap_voucher[0]['rr_no']  ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong style="display:<?php echo $terms_display ?>">Terms: <?php echo $terms  ?></strong></td>
                    
                </tr>
               
                
                <tr>           
                    <td align="right" colspan="3" >&nbsp;</td>                
                </tr>
                
            </table>
            <br>
            
            <div style="margin-top:0px; width:800px">
            <table id="datatable-responsive" style="margin-top:30px"  class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                              <thead id="sub-title" style="border:#CCCCCC thin solid; font-size:14px">
                                                <tr bgcolor="#F4F4F4">
                                                  <th width="20%" style="border:#CCCCCC thin solid">Account Code</th>
                                                  <th width="50%" style="border-right:#CCCCCC thin solid">Account Name</th>
                                                  <th width="15%" style="border-right:#CCCCCC thin solid">Debit</th>
                                                  <th width="15%">Credit</th>                                                  
                                                </tr>
                                              </thead>
                        
                        
                                              <tbody>
                                                <tr style="border-right:#CCCCCC thin solid; border:#CCCCCC thin solid">
                                                  
                                                  <td colspan="4" >
                                                   
                                                   <!-- Inner content -->
                                                   <div style="margin-top:30px; margin-bottom:50px">												     
                                                     <table width="98%" style="font-size:12px">
													 <?php 													 	
														$total_debit = 0;
														$total_credit = 0;
														
														foreach($entry as $a){													 
														
														//get account info
														$acnt = $this->Accounting_model->getAccount($a['coa_id']);
													 ?>      
                                                     
                                                         <tr>
                                                         	<td width="20%"><?php echo $acnt['code'] ?></td>
                                                            <td width="50%"><?php echo $acnt['name'] ?></td>
                                                            <td width="15%" align="right"><?php echo number_format($a['debit_amount'],2) ?></td>
                                                            <td width="15%" align="right"><?php echo number_format($a['credit_amount'],2) ?></td>
                                                         </tr>
                                                     
                                                     <?php	
																$total_debit = $total_debit + $a['debit_amount']; 
																$total_credit = $total_credit + $a['credit_amount']; 
													 	} 
													?>
                                                    	<tr>
                                                         	<td colspan="4">
                                                            <br>
                                                            <hr>
                                                            &nbsp;</td>
                                                            
                                                         </tr>
                                                        <tfoot>
                                                            <tr style="font-weight:600">
                                                                <td width="20%">&nbsp;</td>
                                                                <td width="50%" align="right">Total:&nbsp;</td>
                                                                <td width="15%" align="right"><?php echo number_format($total_debit,2) ?></td>
                                                                <td width="15%" align="right"><?php echo number_format($total_credit,2) ?></td>
                                                             </tr>
                                                         </tfoot>

                                                     </table>
                                                    </div>
                                                  </td>
                                                  
                                                </tr>
                                              		
                                                
                                                <tfoot>
                                               
                                                <tr>
                                                  
                                                  
                                                  <td class="a-left " style="font-size:16px; font-weight:600" align="center" colspan="2">												
                                                  	
                                                    <table>
                                                    	<tr>
                                                        <td></td>
                                                        <td></td>
                                                        </tr>
                                                    </table>
                                                  
                                                  	
                                                    
                                                  </td>                                          
                                                 
                                                  
                                                </tr>
                                                
                                              </tfoot>
                                                
                      </tbody>
                    </table>   
                                            
            </div>
            
             <div align="center" style="margin-top:50px; margin-left:30px; width:800px; ">
                <table style="margin-top:20px; font-size:12px; width:800px;" >
                	
                    <tr>
                    	<td>PREPARED BY:</td><td>CHECKED BY:</td><td>APPROVED BY:</td>
                    </tr>
                    <tr>
                    	<td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                    	<td>_________________________</td>
                        <td>_________________________</td>                        
                        <td>_________________________</td>
                    </tr>
                    <tr>
                    	<td>Accounting Staff</td>
                        <td>Accounting Analyst/Officer</td>                        
                        <td>Accounting Manager</td>
                    </tr>
                </table>
            
            </div>  
            
           
        	
            <br>


</div>



</body>
</html>