<?php 

	
	$c = $this->Finance_model->getCashAdvanceByVoucherId($cash_advance[0]['id']);
	
	$requested_by = $this->Abas->getEmployee($c[0]['requested_by']);
	$department  = $this->Abas->getDepartments($c[0]['department']);
	
	$payto = $requested_by['full_name']; //direct to the who will receive the payment (company or person)
	
 	//get employee company
	$company = $this->Abas->getCompany($requested_by['company_id']);
	
	
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
#title{ font-size:22px; font-weight:600}
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

<div class="well" style="height:120px">
    <p style="font-size:16px">
    	Please select the type of document to print, the documents are different in sizes 
        make sure to set print settings before printing.
        
        <div  style="position:absolute; margin-top:30px; margin-right:100px">
            <button id="printMe" type="button" class="btn-xl btn-success" onClick=' $("#voucherPrint").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print Voucher</button>&nbsp;&nbsp;     
            
          
            <a href="<?php echo HTTP_PATH ?>accounting/voucher_view">
            <button type="button" class="btn-xl btn-default"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Back</button>
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
                	<td colspan="3" id="title" align="center"><?php echo strtoupper($company->name) ; ?></td>
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
                    <td align="center" id="title" colspan="3"><strong>CHECK VOUCHER<?php //echo strtoupper($c[0]['type']) ?></strong></td> 
                </tr>
                 <tr>           
                    <td align="right" colspan="3" >&nbsp;</td>                
                </tr>
                <tr>
                	
                    
                    <td align="right" colspan="3" ></td>
                    
                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>Pay To: <?php echo $payto; ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong style="color:#FF0000">Voucher No: <?php  echo strtoupper($cash_advance[0]['voucher_number']) ?></strong></td>
                    
                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>Check Number: <?php echo $cash_advance[0]['check_num']  ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong>Date: <?php echo date('F j, Y')  ?></strong></td>
                    
                </tr>
                 <tr id="sub-title">
                	<td align="left"><strong>Department: <?php echo $department[0]->name ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong>Request No.: <?php echo $c[0]['id'] ?></strong></td>
                    
                </tr>
                <tr>           
                    <td align="right" colspan="3" ><hr></td>                
                </tr>
                
            </table>
            <br>
            
            <div style="margin-top:-30px">
                <table id="datatable-responsive" style="margin-top:30px"  class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                              <thead id="sub-title">
                                                <tr bgcolor="#F4F4F4">
                                                  
                                                  <th width="80%" style="border-right:#CCCCCC thin solid">Explanation of Payment</th>
                                                  <th width="20%">Amount</th>
                                                  
                                                </tr>
                                              </thead>
                        
                        
                                              <tbody>
                                                <tr>
                                                  <td style="border-right:#CCCCCC thin solid" colspan="2">
                                                   <div style="margin-top:30px; margin-bottom:50px; font-size:12px">
												     <table width="95%" cellpadding="10" cellspacing="10">
												   <?php 
													$gtotal = 0;
													$wtax = 0;
													$vat = 0;
													
															
													?>	
                                                   
                                                 
                                                   		<tr>
                                                    		<td width="10%"><?php echo $c[0]['type'] ?></td>
                                                            <td width="30%" align="left"><?php echo $c[0]['purpose'] ?></td>
                                                            
                                                            
                                                            <td width="20%" align="right"><?php echo number_format($c[0]['amount'],2); ?></td>
														                                                    
                                                    
                                                    	
                                                    	</tr>
                                                                                          
                                                   
                                                    
                                                    </table>             
                                                    </div>
                                                  </td>
                                                  
                                                                                      
                                                  
                                                  
                                                </tr>
                                              		
                                                
                                                <tfoot>
                                                
                                                <tr style="font-size:14px; font-weight:600">                                      
                                                   	<td align="right">
                                                  		<span>Total:</span><br /> 
                                                 	</td>                                          
                                                  	<td width="10%" align="right">                                                 
                                                  		<span style=" width:100%; margin-right:35px">Php  <?php echo number_format($c[0]['amount'],2); ?>&nbsp;</span><br />
                                                    	<br />                                                 
                                                  	</td>                                                  
                                                </tr>
                                                <tr style="display:block; font-size:16px; font-weight:600">                                       
                                                  	<th class="a-center ">
                                                  		Amount in Words: 
                                                  		<span id="amount_words">
												  			<?php echo $this->Mmm->chequeTextFormat($c[0]['amount']); ?>
                                                        </span>
                                                	</th>                                                  
	                                              	<th width="10%"></th>                                                  
                                                </tr>
                                                
                                               
                                              </tfoot>
                                                
                      </tbody>
                    </table>   
                                            
            </div>
            
             <div align="center" style="margin-top:50px; margin-left:30px">
                <table style="width:800px; margin-top:20px; font-size:12px" >
                	
                    <tr>
                    	<td>PREPARED BY:</td>
                        <td>_________________________</td>
                        <td>CHECKED BY:</td>
                        <td>_________________________</td>
                    </tr>
                    <tr>
                    	<td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                    	<td>APPROVED BY:</td>
                        <td>_________________________</td>
                        <td></td>
                        <td>_________________________</td>
                    </tr>
                </table>
            
            </div>  
            
           
        	
            <br>


</div>



<div id="checkPrint" >
Check printing not yet ready.

</div>


</body>
</html>