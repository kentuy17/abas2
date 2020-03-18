<?php
	
	
	
	$requested_by = $this->Abas->getEmployee($cash_advance[0]['requested_by']);
	$department  = $this->Abas->getDepartments($cash_advance[0]['department']);
	$returned_amount = $this->Finance_model->getReturnedAmount($cash_advance[0]['id']);
	$ramount =0;
	if(count($returned_amount) > 0){
	var_dump(count($returned_amount));
		
		$ramount = number_format($returned_amount[0]['returned_amount'],2);
	
	}

?>



<!DOCTYPE html>
<html>
<head>
	<title><?php if(ENVIRONMENT=="development") { echo "DEV - "; } ?>AVEGA Business Automation System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />

	<?php //$this->load->view('includes_header'); ?>
    
    


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
    <a href="#">
    
    <button type="button" class="close" data-dismiss="modal" ><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Back</button></div>
    </a>
<div align="center" style="width:800px; margin-left:0px; margin-top:-20px" id="printThis"> 
    
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="left">(SYSTEM GENERATED COPY)</td>
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
                    		<div width="50%"><strong>CASH LIQUIDATION REPORT</strong></div>
                            <div width="50%">Date: <?php echo date('m-d-Y'); ?></span>
                            </div>
					<br> 
                           
                    <table background="#CCCCC" border="1px" cellpadding="5" cellspacing="5" width="100%" id="details">
                    	
						<tr>
                        	<td width="30%">&nbsp;&nbsp;Requested By:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;<?php echo $requested_by['full_name'] ?></td>
                        </tr> 
                        <tr>
                        	<td width="30%">&nbsp;&nbsp;Request Type:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;<?php echo $cash_advance[0]['type'] ?></td>
                        </tr>                    
                        <tr>
                        	<td width="30%">&nbsp;&nbsp;Purpose:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;<?php echo $cash_advance[0]['purpose'] ?></td>
                        </tr>                    
                    	<tr>
                        	<td width="30%">&nbsp;&nbsp;Amount:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;Php  <?php echo number_format($cash_advance[0]['amount'],2) ?></td>
                        </tr>                    
                    	<tr>
                        	<td width="30%">&nbsp;&nbsp;Returned Amount:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;Php  <?php echo $ramount ?></td>
                        </tr> 
                    </table>
                    
                    
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
                            
                            $usedfor = $this->Abas->getVessel($l['department']);
                            $expense_type = $this->Finance_model->getExpenseType($l['type']);
                            
                            $for = ($l['department']!=null) ? $usedfor->name : '' ;    
                            $type = (isset($expense_type)) ? $expense_type[0]['name'] : ''; 
                            
                         ?>
                        <tr>
                            <td align="center" width="10%"><?php echo date('m-d-Y', strtotime($l['date_liquidated'])) ?></td>                            
                            <td align="left" width="30%">&nbsp;<?php echo $l['particular'] ?></td>                            
                            <td align="right" width="10%"><?php echo number_format($l['amount'],2) ?>&nbsp;</td>                            
                            <td align="center" width="10%"><?php echo $l['receipt_no'] ?></td>                            
                            <td width="15%">&nbsp;<?php echo $type ?></td>                            
                            <td width="20%">&nbsp;<?php echo $for ?></td>
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
                	<td align="left">Released by:</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">Received by:</td>
                    
                </tr>
                <tr>
                	<td colspan="3">&nbsp;</td>
                
                    
                </tr>
                
                <tr>
                	<td align="left">Jinky Velasquez:</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">Print Name & Signature</td>
                    
                </tr>
                
                
            </table>
            <br>
        </div>        
        
      
        
        
  
    
    
</div>



</div>



<table>

</table>


</div>

</body>
<script>
<?php $this->Abas->display_messages(); ?>
</script>
<script>
// resets the modal upon close so there is no need to write new markup for each modal dialog
$('body').on('hidden.bs.modal', '.modal', function () {
	$(this).removeData('bs.modal');
	$(".modal-content").html("<p class='loading-text'>Loading Content...</p>");
});

function showNotifications() {
	// toastr['info']("This is a notification");
	<?php if(isset($_SESSION['abas_login'])) echo $this->Abas->getNotifications(); ?>
}

$(function() {
	var	$window = $(window),
	$body = $('body');

	// Disable animations/transitions until the page has loaded.
	$body.addClass('is-loading');

	$window.on('load', function() {
		window.setTimeout(function() {
			$body.removeClass('is-loading');
		}, 0);
	});
});
</script>
</html>
