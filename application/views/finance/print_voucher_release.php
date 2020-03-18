<?php
	
	
	

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
                	<td colspan="3" align="left">(THIS IS SYSTEM GENERATED DOCUMENT)</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center"><?php echo $company->name ?></td>
                </tr>
                
                <tr>
                	<td colspan="3" id="title" align="center">&nbsp;
                    
                    <!--- cash request table -->
                    		<div width="50%"><strong>BANK DEPOSIT ORDER</strong></div>
                            <div width="50%">
                            	<span style="margin-right:10px; margin-top:20px; float:right; font-size:12px">DATE.: <?php echo date("F j ,Y H:i:s");	 ?></span>
                            </div>
					<br>                        
                    <table background="#CCCCC" border="1px" cellpadding="5" cellspacing="5" width="100%" id="details">
                    	
						<tr>
                        	<td width="30%">&nbsp;&nbsp;Bank Name:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;<?php echo $payto['bank_name'] ?></td>
                        </tr> 
                        <tr>
                        	<td width="30%">&nbsp;&nbsp;Account Name:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;<?php $payto['name']  ?></td>
                        </tr>                    
                        <tr>
                        	<td width="30%">&nbsp;&nbsp;Account Number:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;<?php echo $payto['bank_account_no'] ?></td>
                        </tr>                    
                    	<tr>
                        	<td width="30%">&nbsp;&nbsp;Amount:</td>
                            <td width="70%">&nbsp;&nbsp;&nbsp;Php  <?php echo number_format($voucher[0]['amount'],2) ?></td>
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
                	<td align="left"><?php echo $_SESSION['abas_login']['fullname']; ?>:</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">Print Name & Signature</td>
                    
                </tr>
                
                
            </table>
            <br>
        </div>        
        
        
        <hr style="border-top: 1px dashed #8c8b8b"/>
        
         
        
        
        
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
