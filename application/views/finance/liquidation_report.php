<?php
	
	
	
	//$requested_by = $this->Abas->getEmployee($cash_advance[0]['requested_by']);
	//$department  = $this->Abas->getDepartments($cash_advance[0]['department']);
	

?>



<!DOCTYPE html>
<html>
<head>
	<title><?php if(ENVIRONMENT=="development") { echo "DEV - "; } ?>AVEGA Business Automation System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />

	<?php $this->load->view('includes_header');?>
    
    


<style>

#header{margin-top:30px}
#title{ font-size:18px; font-weight:600}
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

#details{ font-size:12px; font-weight:600}


</style>

</head>
<body style="background:#FFFFFF">
<div  style="position:absolute; margin-top:20px; margin-left:700px">
	<button id="printMe" type="button" class="btn-xs btn-success" onClick=' $("#printThis").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print</button>&nbsp;&nbsp;
    <a href="<?php echo HTTP_PATH ?>finance/accounts_view/##cash_advance">
    <button type="button" class="btn-xs btn-default"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Back</button></div>
    </a> 
	
    <div align="center" style="width:800px; margin-left:0px; margin-top:-20px" id="printThis"> 
    
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="left"></td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">AVEGA BROS INTEGRATED SHIPPING CORP.</td>
                </tr>
                <tr>
                	<td colspan="3" align="center">J. De Veyra St. NRA, Cebu City</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">&nbsp;
                    
                    <!--- cash request table -->
                    		<div width="50%"><strong><?php echo strtoupper($type) ?> LIQUIDATION REPORT</strong></div>
                            <div width="50%">
                            	<span style="margin-right:10px; margin-top:-30px; float:right">Date: <?php echo date('m-d-Y') ?></span>
                            </div>
					<br>                        
                    <table background="#CCCCC" border="1px" cellpadding="5" cellspacing="5" width="100%" id="details">
                    	
						<tr align="center">
                        	<td>Date Liquidated</td>                            
                            <td>Particular</td>
                            <td>Amount</td>
                            <td>Receipt #</td>
                            <td>Expense Class</td>
                            <td>Use for</td>
                        </tr> 
                        
                        <?php
							
							$total = 0;
							foreach($liquidation as $l){
							
							$usedfor = $this->Abas->getVessel($l['used_for']);
						 ?>
                        <tr>
                        	<td align="center" width="10%"><?php echo date('m-d-Y', strtotime($l['date_liquidated'])) ?></td>                            
                            <td align="left" width="30%">&nbsp;<?php echo $l['particular'] ?></td>                            
                            <td align="right" width="10%"><?php echo number_format($l['amount'],2) ?>&nbsp;</td>                            
                            <td align="center" width="10%"><?php echo $l['receipt_no'] ?></td>                            
                            <td width="15%">&nbsp;<?php echo $l['expense_type'] ?></td>                            
                            <td width="20%"><?php echo $usedfor->name ?></td>
                        </tr> 
                        <?php 
							$total = $total + $l['amount'];
						} 
						
						?>               
                    	
                         <tr>
                        	<td colspan="3" align="right">Total:&nbsp;&nbsp; <?php echo number_format($total,2) ?>&nbsp;</td>                            
                            <td colspan="3"></td>
                            
                        </tr> 
                    </table>
                    
                    
                    
                    
                    
                    
                    </td>
                </tr>
                
                                  
                </tbody>
                
            </table>

        	<table style="width:800px; margin-top:20px" >
            	
                <tr>
                	<td align="left">Submitted by:</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">Noted by:</td>
                    
                </tr>
                <tr>
                	<td colspan="3">&nbsp;</td>
                
                    
                </tr>
                
                <tr>
                	<td align="left">Jinky Velasquez:</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">Juliet Alcazaren</td>
                    
                </tr>
                
                
            </table>

            <br>
            
        </div>        
        
        
        <hr style="border-top: 1px dashed #8c8b8b"/>
        
        <div id="table">
        	
            <br>
            
        </div>        
    
    
    
	</div>



</div>
</div>


</body>

</html>
