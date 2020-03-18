<?php 
	//var_dump($delivery_summary);
	//var_dump($delivery_detail);
	//var_dump($voucher);
	
	
	
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
#title{ font-size:20px; font-weight:600}
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
    	Please select the type of document to print, the documents are different in sizes 
        make sure to set print settings before printing.
    </p>
   <p>
    <div  style="position:absolute; margin-top:5px; margin-left:20px">
        <button id="printMe" type="button" class="btn-sm btn-success" onClick=' $("#voucherPrint").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print Voucher</button>&nbsp;&nbsp;
        <?php 
			if($ttype == 'po'){
				
				if($payee['tin']!=0){ 
		?>
        <!---
        <button id="printMe" type="button" class="btn-sm btn-success" onClick=' $("#taxPrint").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print 2307</button>&nbsp;&nbsp;-->
        <?php 
				} 
			} 
		?>
        <!---
        <button id="printMe" type="button" class="btn-xs btn-success" onClick=' $("#checkPrint").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print Check</button>&nbsp;&nbsp;
        --->
        <a href="<?php echo HTTP_PATH ?>accounting/ap_vouchers">
        <button type="button" class="btn-sm btn-default"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Back</button>
        </a>
    </div>
    </p>
</div>

<br />
<hr />
<br />

<div id="voucherPrint" >

	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="right" style="font-size:10PX">TRANSACTION CODE: <?php echo strtoupper($voucher['id']) ?></td>
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
                    <td align="center" id="title" colspan="3"><strong><?php echo strtoupper($voucher['type']) ?></strong></td> 
                </tr>
                 <tr>           
                    <td align="right" colspan="3" >&nbsp;</td>                
                </tr>
                <tr>
                	
                    
                    <td align="right" colspan="3" ></td>
                    
                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>Payee: <?php echo $payee_name ?></strong></td>
                    
                    <td align="right" colspan="2"><strong style="color:#FF0000">CV No: <?php echo strtoupper($voucher['voucher_number']) ?></strong></td>
                    
                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>Address: <?php echo $payee_address  ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong>Date: <?php echo date('F j, Y', strtotime($voucher['voucher_date']))  ?></strong></td>
                    
                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>TIN: <?php if($ttype=='po'){ echo $payee['tin'];}  ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong>
                    	<?php 
							if($ttype == 'po'){
							
									//get apv control number
									$apv_control_number = $this->Accounting_model->getAPVoucher($voucher['apv_no']);
						?>	
                        			APV No.: <?php  echo $apv_control_number[0]['control_number']  ?></strong></td>
                    	<?php 
							}
						?>
                        <?php 
							if($ttype == 'non-po'){
						?>	
                        			RFP No.: <?php  echo $rfp_info[0]['control_number']  ?></strong></td>
                    	<?php 
							}
						?>
                    
                </tr>
                
                <tr>           
                    <td align="right" colspan="3" ><hr></td>                
                </tr>
                
            </table>
            <br>
       
            
                <table id="datatable-responsive" style="margin-top:30px; width:800px;"  class="table table-striped dt-responsive nowrap" cellspacing="0">
                                              <thead id="sub-title">
                                                <tr bgcolor="#F4F4F4">
                                                  
                                                  <th style="border:#CCCCCC thin solid" align="center" colspan="2">Particulars</th>
                                                  
                                                  
                                                </tr>
                                              </thead>
                        
                        
                                              <tbody>
                                                <tr style="border:#CCCCCC thin solid">
                                                  <td style="border-right:#CCCCCC thin solid; font-size:14px" colspan="2">
                                                   <div style="margin-top:30px; margin-bottom:50px; margin-left:30px; width:60%; float:left;">
												   <?php echo $voucher['remark'] ?>
                                                  
                                                    </div>
                                                    <div style="margin-top:30px; margin-bottom:50px; float:right; margin-right:-95px; width:35%">
                                                     <?php  echo number_format($computed_amount['accounts_payable'],2) ?>
                                                     </div>
                                                  </td>
                                                  
                                                                                      
                                                  
                                                  
                                                </tr>
                                              	
                                                    	<tr style="font-size:14px; font-weight:600">
                                                        <td>
                                                        	
                                                            <span class="pull-left">Bank: <?php echo $bank['name'] ?></span>
                                                            
                                                        </td>
                                                        <td>
                                                        	<span class="pull-left">Check Number: <?php echo $voucher['check_num'] ?></span>&nbsp;&nbsp;
                                                        	<span class="pull-right">Check Date: <?php echo date('F j, Y', strtotime($voucher['check_date'])) ?></span>
                                                        </td>
                                                        
                                                        </tr>
                                                
                                              </tbody>  
                                                <tfoot>
                                                
                                               
                                                <tr>
                                                  
                                                  
                                                  <td class="a-left " style="font-size:16px; font-weight:600" align="center" colspan="2">												
                                                  	
                                                   
                                                  
                                                  	<table width="800px" style="font-size:12px" bgcolor="#CCCCCC" cellpadding="3" cellspacing="3" border="1px">
                                                    	<tr bgcolor="#F4F4F4" align="center">
                                                        	<td colspan="4">Account Distribution</td>
                                                        </tr>
                                                        <tr bgcolor="#FFFFFF" align="center">
                                                        	<td>Account Code</td>
                                                            <td>Account Name</td>
                                                            <td>Debit</td>
                                                            <td>Credit</td>
                                                        </tr>
                                                     <?php
                                                       if($ac_entries == TRUE){
											
														foreach($ac_entries as $v){
																						
														$account_name = $this->Accounting_model->getAccount($v['coa_id']);
														$dept_code = ($v['department_id']!= NULL ) ? $v['department_id'] : 00;
														$vessel_code= ($v['vessel_id']!= NULL ) ? $v['vessel_id'] : 000;
														$contract_code= ($v['contract_id']!= NULL )? $v['contract_id'] : 0000;
														$account_code = 	'00000'.sprintf('%02d',$dept_code).''.sprintf('%03d',$vessel_code).''.sprintf('%04d',$contract_code).'-'.$account_name['financial_statement_code'].''.$account_name['general_ledger_code'];
															
                                                        ?>
                                                        <tr>
                                                          
                                                         
                                                          
                                                          <td align="center"><?php echo $account_code ?></td>
                                                          <td>&nbsp;&nbsp;<?php echo $account_name['name'] ?></td>
                                                          
                                                          <td align="right"><?php echo number_format($v['debit_amount'],2) ?>&nbsp;&nbsp;</td>
                                                          <td align="right"><?php echo number_format($v['credit_amount'],'2') ?>&nbsp;&nbsp;</td>
                                                         
                                                          
                                                          
                                                          
                                                        </tr>
                                                        <?php 
                                                            
                                                            }
                                                        }else{
                                                                
                                                                echo '<tr><td  colspan="4">No entries found</td></tr>';
                                                        }
                                                        ?>
                                                    </table>
                                                    
                                                  </td>                                          
                                                 
                                                  
                                                </tr>
                                                 <tr>
                                                  
                                                  
                                                  <td class="a-center " style="font-size:16px; font-weight:600" align="center" colspan="2">
                                                  		<br />
                                                        Received the amount of: <?php echo $this->Mmm->chequeTextFormat($computed_amount['accounts_payable']);?>
                                                  </td>                                          
                                                 
                                                  
                                                </tr>
                                              </tfoot>
                                                
                      </tbody>
                    </table>   
                                            
           
            
             <div style="margin-top:50px; margin-left:10px">
                <table style="width:800px; margin-top:20px; font-size:12px" >
                	
                    <tr>
                    	<td>PREPARED BY:</td>
                        <td><?php echo strtoupper($_SESSION['abas_login']['fullname']); ?></td>
                        <td style="width:120px">&nbsp;</td>
                        <td>APPROVED BY:</td>
                        <td>_____________________________________________</td>
                        
                    </tr>
                    <tr>
                    	<td colspan="2">&nbsp;
                        
                         
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                        <?php 
							
							if($_SESSION['abas_login']['user_location'] == 'Makati'){
									echo '<div  style="float:left; margin-left:0px">	JPV&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;   
																						SNV&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
																						JNV&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;   
																						BNV<div>';
                                    echo '<div  style="float:left; margin-left:0px">  <br><br>  _____________________________________________ <br> STEPHEN ALEXANDER P. VEGA<div>';
							}
						?>
                        </td>
                    </tr>
                   <tr>
                    	<td colspan="5">
                        &nbsp;             
                        </td>
                        
                    </tr>
                    <tr>
                    	<td colspan="5">
                        &nbsp;             
                        </td>
                        
                    </tr>
                    <tr>
                    	<td>VERIFIED BY:</td>
                        <td>_________________________</td>
                        <td>&nbsp;</td>
                        <td>RECEIVED BY:</td>
                        <td>_________________________</td>
                    </tr>
                   
                     <tr>
                    	<td colspan="2">&nbsp;
                        
                        
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Print name & signature</td>
                    </tr>
                    <tr>
                    	<td></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>__________________________</td>
                    </tr>
                    <tr>
                    	<td></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>Date</td>
                    </tr>
                </table>
            
            </div>  
            
           
        	
            <br>


</div>



<?php 
	exit;
	if($ttype=='po'){
	//var_dump($payee['tin']); exit;	
		if(strlen($payee['tin'])>2 ){
			
			$tin = explode('-',$payee['tin']); 
			$tin1 = $tin[0];
?>
<div id="taxPrint" >



<div style="position:absolute; margin-top:110px; margin-left:135px; font-size:14px"><?php echo date('m         -         d          -          y')?></div>
<div style="position:absolute; margin-top:110px; margin-left:445px; font-size:14px"><?php echo date('m         -         d          -          y')?></div>

<div style="position:absolute; margin-top:155px; margin-left:155px; font-size:14px"><?php echo $tin[0];?></div>
<div style="position:absolute; margin-top:155px; margin-left:220px; font-size:14px"><?php echo $tin[1];?></div>
<div style="position:absolute; margin-top:155px; margin-left:290px; font-size:14px"><?php echo $tin[2];?></div>
<div style="position:absolute; margin-top:155px; margin-left:370px; font-size:14px"><?php echo $tin[3];?></div>


<div style="position:absolute; margin-top:185px; margin-left:155px; font-size:13px"><?php echo $payee['name'];?></div>
<div style="position:absolute; margin-top:220px; margin-left:155px; font-size:13px"><?php echo $payee['address'];?></div>

<?php var_dump($ac_entries); ?>
<div style="position:absolute; margin-top:445px; margin-left:35px; font-size:12px">Income payment subject</div>
<div style="position:absolute; margin-top:445px; margin-left:600px; font-size:13px"><?php echo number_format($gtotal,2); ?></div>
<div style="position:absolute; margin-top:445px; margin-left:755px; font-size:13px"><?php echo number_format($wtax,2); ?></div>

<!-- Grand total --->
<div style="position:absolute; margin-top:980px; margin-left:600px; font-size:13px"><?php echo number_format($gtotal,2); ?></div>
<div style="position:absolute; margin-top:980px; margin-left:755px; font-size:13px"><?php echo number_format($wtax,2); ?></div>


<img src="<?php echo LINK; ?>assets/images/2307a.jpg" width="1000px">

</div>

<?php } } ?>

<div id="checkPrint" style="display:none" >

Check printing not yet ready.

</div>


</body>
</html>