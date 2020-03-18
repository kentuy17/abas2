<?php

require_once(WPATH.'application\views\header.php');


if(isset($_GET['c']) and $_GET['c'] != '' ){

	var_dump($_GET['c']);
	
	//get company employees
	
	
	/*get employee data
		1. Emp ID, Name
		2. Salary
		3. TaxCode
		4. TIN
		5. SSS#
		6. PH #
		7. Pagbibig #
		8. Bank Account #
		9. Position
		10. ELF contribution
		11. Loans
	*/
	
	
	//compute tax	
	
	//get sss contri
	
	//get philhealth contri
	
	//pagibig contri
		
	//check loan details
	
	//check unliquidated funds
	
	
	
	

}

?>
 
<style>

#content{ margin-top:-20px}

</style> 


<style type="text/css">
    /* Some custom styles to beautify this example */
    .demo-content{
        padding: 15px;
        font-size: 18px;
        
        background: #dbdfe5;
        margin-bottom:0px;
    }
    .demo-content.bg-alt{
        background: #abb1b8;
    }
	
	#heading{ min-height: 50px;}
</style>
 
        
        
        
         		<!--Payslip design 2-->
         		<div style="margin-left:30px"><h3>Payslip</h3></div>
                <div class = "panel panel-default" style="margin-left:30px;width:800px; height:351px; border:#999999 thin solid">
                   <div class = "panel-heading" style=" background:#CCC; color:#000">
                      <h3 class = "panel-title">
                         <strong>PAYSLIP - (November 1-15, 2015)</strong>
                         <span style="float:right; margin-right:10px"><strong>Avegabros Integrated Shipping Corp.</strong></span>
                      </h3>
                   </div>
                   
                   <div class = "panel-body" style="height:270px; width:100%">
                      
                      <table width="100%" height="10%" cellpadding="15px" cellspacing="5" style="margin-top:-10px">
                      	<tr height="10%">
                        	<strong>
                        	<td width="38%">Name: JUAN DELA CRUZ  (AV-091239)  </td>
                            <td width="32%">Department: MV Ligaya</td>
                            <td width="30%">Position: Captain</td>
                            </strong>
                        </tr>    
                      </table>  
                      <table width="100%" cellpadding="1px" cellspacing="5" border="1"  >
                            
                        			<thead style="background:#000; color:#FFFFFF;" >
                                      <tr >
                                        <th width="35%" class="text-center">Income</th>
                                        <th width="35%" class="text-center">Deductions</th>
                                        <th width="30%" class="text-center">Loans</th>
                                      </tr>
                                    </thead>
                        <tr>
                        	<td align="left" valign="top">
                            	
                                <table class="table table-condensed" style="font-size:12px">
                                    
                                    <tbody>
                                      <tr>
                                        <td>Basic Salary:</td>
                                        <td align="right"><?php echo '20,000.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Salary for this Pay Period:</td>
                                        <td align="right"><?php echo '10,000.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Overtime:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Allowance:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Bonus:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Others (Specify):</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Leave Credits:</td>
                                        <td align="right"><?php echo '15 days' ?></td>
                                        
                                      </tr>
                                      <tr style="font-weight:600">
                                        <td align="right"><strong>Total Income:</strong></td>
                                        <td align="right"><strong><?php echo '0.00' ?></strong></td>
                                        
                                      </tr>
                                    </tbody>
                                  </table>
                               
                            </td>
                            <td align="left" valign="top">
                            	<table class="table table-condensed" style="font-size:12px">
                                    
                                    <tbody>
                                      <tr>
                                        <td>Taxable Income:</td>
                                        <td align="right"><?php echo '8,000.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Withholding Tax:</td>
                                        <td align="right"><?php echo '1,500.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>SSS Contribution:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Philhealth Contribution:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Pagibig Contribution:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>ELF Contribution:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Absences/Tardiness:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr style="font-weight:600">
                                        <td align="right"><strong>Total Deductions:</strong></td>
                                        <td align="right"><strong><?php echo '0.00' ?></strong></td>
                                        
                                      </tr>
                                    </tbody>
                                  </table>
                                
                                
                            
                            </td>
                            <td align="left" valign="top">
                            	
                                <table class="table table-condensed" style="font-size:12px">
                                    
                                    <tbody>
                                      <tr>
                                        <td>ELF Loan (Balance):</td>
                                        <td align="right"><?php echo '8,000.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>ELF Loan Payment:</td>
                                        <td align="right"><?php echo '10,000.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>SSS (Salary Loan):</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>SSS Loan Payment:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Pagibig Loan:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Pagibig Loan Payment:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      <tr>
                                        <td>Others:</td>
                                        <td align="right"><?php echo '0.00' ?></td>
                                        
                                      </tr>
                                      
                                      <tr style="font-weight:600">
                                        <td align="right"><strong>Total Payments:</strong></td>
                                        <td align="right"><strong><?php echo '0.00' ?></strong></td>
                                        
                                      </tr>
                                    </tbody>
                                  </table>
                                
                            </td>
                        </tr>    
                        
                      </table>
                      
                      
                   </div>
                   
                   <div class = "panel-footer" style=" background:#CCC; color:#000">
                      <img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="25px" align="absmiddle">
                      <span style="font-weight:600; color:#000066">aps</span>
                      <span style="font-weight:600; color:#000; float:right; margin-right:20px">Net Pay:  Php 7,000.00</span>
                   </div>
                   
                </div>
         		<br /></br>
                 <br /></br>
        <!--end Payslip design 2-->
        
        		<!--Payroll Form design 1-->
         		<div style="margin-left:30px"><h3>Payroll</h3></div>
                <div class = "panel panel-default" style="margin-left:30px; width:95%; height:710px; border:#999999 thin solid">
                   <div class = "panel-heading" style=" background:#CCC; color:#000">
                      <h3 class = "panel-title">
                         <strong>PAYROLL</strong> - (Period: November 1-15, 2015)
                         <span style="float:right; margin-right:10px"><strong>Avegabros Integrated Shipping Corp.</strong></span>
                      </h3>
                   </div>
                   
                   <div class = "panel-body" style="height:570px; width:100%">
                   		<table class="table table-condensed table-bordered" style="font-size:12px">
                            
                        			<thead style="background:#000; color:#FFFFFF;" >
                                      <tr >
                                        <th width="5%" class="text-center">EID</th>
                                        <th width="10%" class="text-center">Name</th>
                                        <th width="8%" class="text-center">Position</th>
                                        <th width="5%" class="text-center">Salary</th>
                                        <th width="5%" class="text-center">Allowance</th>
                                        <th width="5%" class="text-center">Others</th>
                                        <th width="5%" class="text-center">Sub-Total</th>
                                        <th width="5%" class="text-center">W-Tax</th>
                                        <th width="5%" class="text-center">SSS</th>
                                        <th width="5%" class="text-center">PhilHealth</th>
                                        <th width="5%" class="text-center">Pagibig</th>
                                        <th width="5%" class="text-center">ELF</th>
                                        <th width="5%" class="text-center">Loan</th>
                                        <th width="5%" class="text-center">Net Pay</th>
                                        
                                      </tr>
                                    </thead>
                                    
                                    <tbody>
                                      <tr>
                                        <td>AVM-00292</td>
                                        <td align="left">Juan Dela Cruz</td>
                                        <td align="left">Captain</td>
                                        <td align="right">10,000.00</td>
                                        <td align="right">1,000.00</td>
                                        <td align="right">0.00</td>                                        
                                        <td align="right">11,000.00</td>
                                        <td align="right">1,500.00</td>
                                        <td align="right">500.00</td>
                                        <td align="right">100.00</td>
                                        <td align="right">100.00</td>
                                        <td align="right">500.00</td>
                                        <td align="right">2,000.00</td>
                                        <td align="right">6,700.00</td>
                                        
                                      </tr>
                                  
                                    </tbody>
                         </table>
                   
                  
                   
                   </div>
                  
                  
                   <div class = "panel-footer">
                      <div style="margin-bottom:10px">
                      	<table width="100%" cellpadding="1px" cellspacing="5"   >
                            
                        			<thead >
                                      <tr >
                                        <th width="35%" class="text-left">Prepared by:</th>
                                        <th width="35%" class="text-left">Checked by:</th>
                                        <th width="30%" class="text-left">Noted by:</th>
                                      </tr>
                                    </thead>
                                    <tbody >
                                      <tr >
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="30%" class="text-left">_________________________________</th>
                                      </tr>
                                      <tr >
                                        <th width="35%" class="text-left">Geraldine P. Rivera</th>
                                        <th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
                                        <th width="30%" class="text-left">Belma A. Hipolito / Janice D. Suyao</th>
                                      </tr>
                                    </tbody>
                       				
                        </table>
                      </div>
                      <div style="float:right; margin-top:-30px">
                      <img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="25px" align="absmiddle">
                      <span style="font-weight:600; color:#000066">aps</span>
                      <span style="font-weight:600; color:#000; float:right; margin-right:20px"></span>
                      </div>
                   </div>
               </div>
               
               <!--end Payroll Form design 1-->
        
             
        			<br /></br>
                 	<br /></br>
        
        		
                <!--TAX Report Form design 1-->
         		
                <div class = "panel panel-default" style="margin-left:30px; width:65%; height:1010px; border:#999999 thin solid">
                   <div class = "panel-heading" style=" background:#CCC; color:#000">
                      <h3 class = "panel-title">
                         
                         <span><strong><h4><img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   Avegabros Integrated Shipping Corp.</h4></strong></span>
                         <span style="float:right; margin-right:10px; margin-top:-50px"><h3>BIR</h3></span>
                      </h3>
                   </div>
                   
                   <div class = "panel-body" style="height:810px; width:100%">
                   		<div style="float:left;margin-left;0px"><h4>TAX Remitance Report</h4></div>
                        <div style="float:right; margin-right:10px"><h4>PAYROLL Period: November 1-15, 2015</h4></div>
                        <table class="table table-condensed table-bordered" style="font-size:12px">
                            
                        			<thead style="background:#000; color:#FFFFFF;" >
                                      <tr >
                                        <th width="5%" class="text-center">Employee ID</th>
                                        <th width="10%" class="text-center">Name</th>
                                        <th width="8%" class="text-center">TIN</th>
                                        <th width="5%" class="text-center">Tax Code</th>
                                        <th width="5%" class="text-center">Taxable Income</th>
                                        <th width="5%" class="text-center">Withholding Tax</th>
                                        
                                      </tr>
                                    </thead>
                                    
                                    <tbody>
                                      <tr>
                                        <td align="center">AVM-00292</td>
                                        <td align="left">Juan Dela Cruz</td>
                                        <td align="center">123456789</td>
                                        <td align="center">ME2</td>                                                                            
                                        <td align="right">11,000.00</td>
                                        <td align="right">1,500.00</td>
                                        
                                        
                                      </tr>
                                  
                                    </tbody>
                         </table>
                   
                  
                   
                   </div>
                  
                  
                   <div class = "panel-footer">
                      <div style="margin-bottom:10px">
                      	<table width="100%" cellpadding="1px" cellspacing="5"   >
                            
                        			<thead >
                                      <tr >
                                        <th width="35%" class="text-left">Prepared by:</th>
                                        <th width="35%" class="text-left">Checked by:</th>
                                        <th width="30%" class="text-left">Noted by:</th>
                                      </tr>
                                    </thead>
                                    <tbody >
                                      <tr >
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="30%" class="text-left">_________________________________</th>
                                      </tr>
                                      <tr >
                                        <th width="35%" class="text-left">Geraldine P. Rivera</th>
                                        <th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
                                        <th width="30%" class="text-left">Belma A. Hipolito / Janice D. Suyao</th>
                                      </tr>
                                    </tbody>
                       				
                        </table>
                      </div>
                      <div style="float:right; margin-top:10px">
                      <img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="25px" align="absmiddle">
                      <span style="font-weight:600; color:#000066">aps</span>
                      <span style="font-weight:600; color:#000; float:right; margin-right:20px"></span>
                      </div>
                   </div>
               </div>
               
               <!--end Tax Report Form design 1-->
               			<br /></br>
                 		<br /></br>
        
                
                <!--SSS Contribution Form design 1-->
         		
                <div class = "panel panel-default" style="margin-left:30px; width:65%; height:1010px; border:#999999 thin solid">
                   <div class = "panel-heading" style=" background:#CCC; color:#000">
                      <h3 class = "panel-title">
                         
                         <span><strong><h4><img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   Avegabros Integrated Shipping Corp.</h4></strong></span>
                      </h3>
                      <span style="float:right; margin-right:10px; margin-top:-50px"><h3>SSS</h3></span>
                   </div>
                   
                   <div class = "panel-body" style="height:810px; width:100%">
                   		<div style="float:left;margin-left;0px"><h4>SSS Contribution Remitance Report</h4></div>
                        <div style="float:right; margin-right:10px"><h4>PAYROLL Period: November 1-15, 2015</h4></div>
                        <table class="table table-condensed table-bordered" style="font-size:12px">
                            
                        			<thead style="background:#000; color:#FFFFFF;" >
                                      <tr >
                                        <th width="5%" class="text-center">Employee ID</th>
                                        <th width="10%" class="text-center">Name</th>
                                        <th width="8%" class="text-center">SSS #</th>
                                        <th width="5%" class="text-center">Salary</th>
                                        <th width="1%" class="text-center">Bracket</th>
                                        <th width="5%" class="text-center">ER</th>
                                        <th width="5%" class="text-center">EE</th>
                                        <th width="5%" class="text-center">Total</th>
                                        
                                      </tr>
                                    </thead>
                                    
                                    <tbody>
                                      <tr>
                                        <td align="center">AVM-00292</td>
                                        <td align="left">Juan Dela Cruz</td>
                                        <td align="center">123456789012</td>
                                        <td align="right">11,000.00</td>
                                        <td align="center">7</td>                                                                            
                                        <td align="right">220.00</td>
                                        <td align="right">280.00</td>
                                        <td align="right">500.00</td>
                                        
                                        
                                      </tr>
                                  
                                    </tbody>
                         </table>
                   
                  
                   
                   </div>
                  
                  
                   <div class = "panel-footer">
                      <div style="margin-bottom:10px">
                      	<table width="100%" cellpadding="1px" cellspacing="5"   >
                            
                        			<thead >
                                      <tr >
                                        <th width="35%" class="text-left">Prepared by:</th>
                                        <th width="35%" class="text-left">Checked by:</th>
                                        <th width="30%" class="text-left">Noted by:</th>
                                      </tr>
                                    </thead>
                                    <tbody >
                                      <tr >
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="30%" class="text-left">_________________________________</th>
                                      </tr>
                                      <tr >
                                        <th width="35%" class="text-left">Geraldine P. Rivera</th>
                                        <th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
                                        <th width="30%" class="text-left">Belma A. Hipolito / Janice D. Suyao</th>
                                      </tr>
                                    </tbody>
                       				
                        </table>
                      </div>
                      <div style="float:right; margin-top:10px">
                      <img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="25px" align="absmiddle">
                      <span style="font-weight:600; color:#000066">aps</span>
                      <span style="font-weight:600; color:#000; float:right; margin-right:20px"></span>
                      </div>
                   </div>
               </div>
               
               <!--end SSS Contribution Form design 1-->
               			<br /></br>
                 		<br /></br>
        
                
        		<!--PhilHealth Contribution Form design 1-->
         		
                <div class = "panel panel-default" style="margin-left:30px; width:65%; height:1010px; border:#999999 thin solid">
                   <div class = "panel-heading" style=" background:#CCC; color:#000">
                      <h3 class = "panel-title">
                         
                         <span><strong><h4><img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   Avegabros Integrated Shipping Corp.</h4></strong></span>
                      </h3>
                      <span style="float:right; margin-right:10px; margin-top:-50px"><h3>PhilHealth</h3></span>
                   </div>
                   
                   <div class = "panel-body" style="height:810px; width:100%">
                   		<div style="float:left;margin-left;0px"><h4>PhilHealth Contribution Remitance Report</h4></div>
                        <div style="float:right; margin-right:10px"><h4>PAYROLL Period: November 1-15, 2015</h4></div>
                        <table class="table table-condensed table-bordered" style="font-size:12px">
                            
                        			<thead style="background:#000; color:#FFFFFF;" >
                                      <tr >
                                        <th width="5%" class="text-center">Employee ID</th>
                                        <th width="10%" class="text-center">Name</th>
                                        <th width="8%" class="text-center">Policy #</th>
                                        <th width="5%" class="text-center">Salary</th>
                                        <th width="1%" class="text-center">Bracket</th>
                                        <th width="5%" class="text-center">PS</th>
                                        <th width="5%" class="text-center">ES</th>
                                        <th width="5%" class="text-center">Line Total</th>
                                        
                                      </tr>
                                    </thead>
                                    
                                    <tbody>
                                      <tr>
                                        <td>AVM-00292</td>
                                        <td align="left">Juan Dela Cruz</td>
                                        <td align="center">123456789232</td>
                                        <td align="right">20,000.00</td>
                                        <td align="center">7</td>
                                        <td align="right">150.00</td>
                                        <td align="right">150.00</td>
                                        <td align="right">300.00</td>
                                        
                                        
                                      </tr>
                                  
                                    </tbody>
                         </table>
                   
                  
                   
                   </div>
                  
                  
                   <div class = "panel-footer">
                      <div style="margin-bottom:10px">
                      	<table width="100%" cellpadding="1px" cellspacing="5"   >
                            
                        			<thead >
                                      <tr >
                                        <th width="35%" class="text-left">Prepared by:</th>
                                        <th width="35%" class="text-left">Checked by:</th>
                                        <th width="30%" class="text-left">Noted by:</th>
                                      </tr>
                                    </thead>
                                    <tbody >
                                      <tr >
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="30%" class="text-left">_________________________________</th>
                                      </tr>
                                      <tr >
                                        <th width="35%" class="text-left">Geraldine P. Rivera</th>
                                        <th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
                                        <th width="30%" class="text-left">Belma A. Hipolito / Janice D. Suyao</th>
                                      </tr>
                                    </tbody>
                       				
                        </table>
                      </div>
                      <div style="float:right; margin-top:10px">
                      <img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="25px" align="absmiddle">
                      <span style="font-weight:600; color:#000066">aps</span>
                      <span style="font-weight:600; color:#000; float:right; margin-right:20px"></span>
                      </div>
                   </div>
               </div>
               
               <!--end PhilHealth Contribution Form design 1-->
               			<br /></br>
                 		<br /></br>
        
        
        <!--Pagibig Contribution Form design 1-->
         		
                <div class = "panel panel-default" style="margin-left:30px; width:65%; height:1010px; border:#999999 thin solid">
                   <div class = "panel-heading" style=" background:#CCC; color:#000">
                      <h3 class = "panel-title">
                         
                         <span><strong><h4><img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   Avegabros Integrated Shipping Corp.</h4></strong></span>
                      </h3>
                      <span style="float:right; margin-right:10px; margin-top:-50px"><h3>PagIbig</h3></span>
                   </div>
                   
                   <div class = "panel-body" style="height:810px; width:100%">
                   		<div style="float:left;margin-left;0px"><h4>PagIbig Contribution Remitance Report</h4></div>
                        <div style="float:right; margin-right:10px"><h4>PAYROLL Period: November 1-15, 2015</h4></div>
                        <table class="table table-condensed table-bordered" style="font-size:12px">
                            
                        			<thead style="background:#000; color:#FFFFFF;" >
                                      <tr >
                                        <th width="5%" class="text-center">Employee ID</th>
                                        <th width="10%" class="text-center">Name</th>
                                        <th width="8%" class="text-center">PagIbig #</th>
                                        <th width="5%" class="text-right">Contribution</th>
                                        
                                        
                                      </tr>
                                    </thead>
                                    
                                    <tbody>
                                      <tr>
                                        <td>AVM-00292</td>
                                        <td align="left">Juan Dela Cruz</td>
                                        <td align="left">123456789</td>
                                        
                                        <td align="right">100.00</td>
                                        
                                        
                                      </tr>
                                  
                                    </tbody>
                         </table>
                   
                  
                   
                   </div>
                  
                  
                   <div class = "panel-footer">
                      <div style="margin-bottom:10px">
                      	<table width="100%" cellpadding="1px" cellspacing="5"   >
                            
                        			<thead >
                                      <tr >
                                        <th width="35%" class="text-left">Prepared by:</th>
                                        <th width="35%" class="text-left">Checked by:</th>
                                        <th width="30%" class="text-left">Noted by:</th>
                                      </tr>
                                    </thead>
                                    <tbody >
                                      <tr >
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="35%" class="text-left">_________________________________</th>
                                        <th width="30%" class="text-left">_________________________________</th>
                                      </tr>
                                      <tr >
                                        <th width="35%" class="text-left">Geraldine P. Rivera</th>
                                        <th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
                                        <th width="30%" class="text-left">Belma A. Hipolito / Janice D. Suyao</th>
                                      </tr>
                                    </tbody>
                       				
                        </table>
                      </div>
                      <div style="float:right; margin-top:10px">
                      <img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="25px" align="absmiddle">
                      <span style="font-weight:600; color:#000066">aps</span>
                      <span style="font-weight:600; color:#000; float:right; margin-right:20px"></span>
                      </div>
                   </div>
               </div>
               
               <!--end PhilHealth Contribution Form design 1-->
               			<br /></br>
                 		<br /></br>
        
        
        
        
                
				
                
          <div class="panel-footer success text-right" style="color:#000099"><strong>AVEGA<span style="color:#FF0000">iT</span>.2015</strong></div>
        </div>     
     
 <!-- Modal HTML -->
   
 	<div id="myModal1" class="modal fade">
        <div class="modal-dialog" style="width:800px">
            
            <div class="modal-content">
                <!-- Content will be loaded here from "remote.php" file -->
            </div>
        </div>
    </div>    


 <!-- PaySlip Design 1 -->
 
 
 
 
 <!-- PaySlip Design 1 -->


<script>
	
	function create(){
		
		var values = {};
		$.each($('#payForm').serializeArray(), function (i, field) {
			values[field.name] = field.value;
		});
		
		var cid = values['company'];
		
		window.location.href = 'http://localhost/abas/hr/payroll?c='+cid;
		//var c = $('#payForm').serialize();
		//alert(values['company']);
		//$( "#results" ).text( c );
		//window.location.href = 'http://localhost/abas/hr/payroll?cid';
		//$('#payForm').submit(function(e){
			//alert('test');
			//e.preventDefault();
			//var comp = this.company; //get the input named "title" of the form
			//alert(comp.value); //alerts the value of that input
		//});
		
    	
	
	}

</script>



</body>
</html>

