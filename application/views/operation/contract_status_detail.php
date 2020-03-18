<?php
		
	var_dump($contract); exit;
	//var_dump($details);

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php if(ENVIRONMENT=="development") { echo "DEV - "; } ?>AVEGA Business Automation System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />

	<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap/css/bootstrap.min.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/global.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/style.css"; ?>" />

	<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"-->
	<link rel="stylesheet" href="<?php echo LINK; ?>assets/bootstrap-table-master/src/bootstrap-table.css">
	<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
	<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>

	<script src="<?php echo LINK.'assets/bootstrap/js/bootstrap.min.js'; ?>"></script>
	<script src="<?php echo LINK; ?>assets/bootstrap-table-master/src/bootstrap-table.js"></script>
	<script src="<?php echo LINK; ?>assets/toastr/toastr.js"></script>
	<script src="<?php echo LINK; ?>assets/stickUp.min.js"></script>



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


</style>

</head>
<body>

<div align="center" style="width:800px; margin-left:0px; margin-top:-20px">

    <div id="header">
    <div style="float:left; position:absolute; margin-top:0px"><img src="<?php echo LINK ?>assets/images/AvegaLogo.png" width="50px"></div>
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	
                
                <tr>
                	
                    <td align="center" id="title" colspan="3">
                    	CONTRACT STATUS REPORT
                        <br>&nbsp;
                    </td>
                    
                    
                </tr>
                <tr>
                	<td align="left">Client:<strong> <?php 
															//var_dump($summary->client);exit;
															$company = $this->Abas->getClient($summary->client);																														
															echo $company->company; 
															
														?></strong></td>
                    <td align="left"></td>
                    <td align="left">Date: <?php echo date('m-d-Y', strtotime($summary->date_created)) ?></td>
                    
                </tr>
                <tr>
                	<td align="left">Address.: <?php echo  $company->address;?></td>
                    <td align="left"></td>
                    <td align="left">REF NO.: <?php echo  $summary->reference_no; ?></td>
                    
                </tr>
                <tr>
                	<td align="left" colspan="2"><?php echo  $summary->remarks; ?></td>
                    <td align="left" >&nbsp;</td>
                    
                </tr>
                
            </table>
            
            <br>
            <?php if($summary->type == 'Handling'){ ?>
            
                    <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
                        <thead  style="font-weight:600; background:#333333; color:#FFFFFF">
                            <tr align="center" style="font-weight:600">
                            <td width="30%">WHSE</td>
                            <td width="10%">Gross Weight (kgs)</td>
                            <td width="10%">No. of Bags</td>
                            <td width="10%">Rate/Bag</td>
                            <td width="10%">No. of Moves</td>
                            <td width="15%">Amount</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 					
                                
                                $total_amount = 0;
                                $total_gw = 0;
                                $total_bags = 0;
                                
                                //set total lines for printing
                                $total_lines = 20;						
                                $ctr = count($details);
                                $remaining = $total_lines - $ctr; 
                                
                                foreach($details as $detail){ 
                                
                                    //get wsr info
                                    $wsrInfo = $this->Operation_model->getTransaction($detail['wsr_id']);
                                    
                                    //get warehouse name
                                    $from_location = $this->Operation_model->getTruckingLocation($wsrInfo->from_location);
                                    
                                    $no_bags = $wsrInfo->gross_weight / 50;
                                    //$qty = ($detail['qty'] != 0 || $detail['qty'] != '' ? $detail['qty'] : 0);
                                    //$unit_price = ($itemInfo[0]['unit_price'] != 0 || $itemInfo[0]['unit_price'] != '' ? $itemInfo[0]['unit_price'] : 0);
                                    $lineTotal = $no_bags * ($summary->rate * $summary->moves);
                                    //var_dump($lineTotal);exit;
                                ?>
                                <tr>
                                    <td align="left">&nbsp;&nbsp;<?php echo $from_location->name ?></td>
                                    <td align="right"><span style="margin-right:20px"><?php echo number_format($wsrInfo->gross_weight,0) ?></span></td>
                                    <td align="center"><?php echo number_format($no_bags,2); ?></td>
                                    <td align="center"><?php echo number_format($summary->rate,2); ?></td>
                                    <td align="center"><?php echo $summary->moves; ?></td>
                                    <td align="right"><span style="margin-right:20px"><?php echo number_format($lineTotal,2) ?></span></td>
                                </tr>
                                <?php 
                                
                                    $total_amount = $lineTotal + $total_amount;
                                    $total_gw = $wsrInfo->gross_weight + $total_gw;
                                    $total_bags = $no_bags + $total_bags;
                                
                                }
                                
                            for($i = 0; $i < $remaining;$i++){	
                            ?>
                            
                            
                            <tr>
                                <td align="center">&nbsp;</td>
                                <td></td>
                                <td align="center"></td>
                                <td align="center"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            <?php } ?>
                            <tr style="font-weight:600; background:#333333; color:#FFFFFF">
                                <td align="right">Total:&nbsp;</td>
                                <td align="right"><span style="margin-right:20px"><?php echo number_format($total_gw,0) ?></span></td>
                                <td align="center"><?php echo number_format($total_bags,2) ?></td>
                                <td align="center"></td>
                                <td align="right"></td>
                                <td align="right"><span style="margin-right:20px">Php <?php echo number_format($total_amount,2) ?></span></td>
                            </tr>
                            
                        </tbody>
                     </table>
                     
             <?php }  ?>
             
            
             <?php if($summary->type == 'Trucking'){ ?>
            
                    <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
                        <thead  style="font-weight:600; background:#333333; color:#FFFFFF">
                            <tr align="center" style="font-weight:600">
                            <td width="20%">WHSE</td>
                            <td width="15%">Date</td>
                            <td width="10%">WSR #</td>
                            <td width="10%">UR #</td>
                            <td width="10%">TRANSACTION</td>
                            <td width="10%">No. of Bags</td>
                            <td width="10%">Gross Weight (kgs)</td>                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php 					
                                
                                $total_amount = 0;
                                $total_gw = 0;
                                $total_bags = 0;
                                
                                //set total lines for printing
                                $total_lines = 20;						
                                $ctr = count($details);
                                $remaining = $total_lines - $ctr; 
                                
                                foreach($details as $detail){ 
                                
                                    //get wsr info
                                    $wsrInfo = $this->Operation_model->getTransaction($detail['wsr_id']);
                                    
                                    //get warehouse name
                                    $from_location = $this->Operation_model->getTruckingLocation($wsrInfo->from_location);
									
									$transType = $this->Operation_model->getTransactionType($wsrInfo->transaction_type);
                                    
                                    $no_bags = $wsrInfo->gross_weight / 50;
                                    //$qty = ($detail['qty'] != 0 || $detail['qty'] != '' ? $detail['qty'] : 0);
                                    //$unit_price = ($itemInfo[0]['unit_price'] != 0 || $itemInfo[0]['unit_price'] != '' ? $itemInfo[0]['unit_price'] : 0);
                                    $lineTotal = $no_bags * ($summary->rate * $summary->moves);
                                    //var_dump($lineTotal);exit;
                                ?>
                                <tr>
                                    <td align="left">&nbsp;&nbsp;<?php echo $from_location->name ?></td>
                                    <td align="right"><span style="margin-right:20px"><?php echo date('F j, Y', strtotime($wsrInfo->issue_date)) ?></span></td>
                                    <td align="center"><?php echo $wsrInfo->wsr_no; ?></td>
                                    <td align="center"></td>
                                    <td align="center"><?php echo $transType->transaction_name ?></td>
                                    <td align="center"><?php echo $wsrInfo->bags ?></td>
                                    <td align="right"><span style="margin-right:20px"><?php echo number_format($wsrInfo->gross_weight,2) ?></span></td>
                                </tr>
                                <?php 
                                
                                    $total_amount = $lineTotal + $total_amount;
                                    $total_gw = $wsrInfo->gross_weight + $total_gw;
                                    $total_bags = $no_bags + $total_bags;
                                
                                }
                                
                            for($i = 0; $i < $remaining;$i++){	
                            ?>
                            
                            
                            <tr>
                                <td align="center">&nbsp;</td>
                                <td></td>
                                <td align="center"></td>
                                <td align="center"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            <?php } ?>
                            <tr style="font-weight:600; background:#333333; color:#FFFFFF">
                                <td align="right">Total:&nbsp;</td>
                                <td align="right"><span style="margin-right:20px"><?php echo number_format($total_gw,0) ?></span></td>
                                <td align="center"><?php echo number_format($total_bags,2) ?></td>
                                <td align="center"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"><span style="margin-right:20px">Php <?php echo number_format($total_amount,2) ?></span></td>
                            </tr>
                            
                        </tbody>
                     </table>
                     
             <?php } ?>
             
             
             
             
             
                        
                   
            <div align="left" style="margin-left:20px; margin-top:30px">
                Amount in Words: 
            </div>
        	<table style="width:800px; margin-top:50px; font-weight:600" >
            	
                <tr>
                	<td align="left">Prepared by:</td>
                    <td align="left">Noted by:</td>
                    <td align="left">Approved by:</td>
                    
                </tr>
                <tr>
                	<td colspan="3" align="left">&nbsp;</td>         
                    
                </tr>
                <tr>
                	<td align="left">JACKIE ROSE REGIS</td>
                    <td align="left">JULIET A. ALCAZAREN</td>
                    <td align="left">ALEXANDER N. VEGA, JR.</td>
                    
                </tr>
                <tr>
                	<td colspan="3" align="left">&nbsp;</td>         
                    
                </tr>
                <tr>
                	<td colspan="3" align="right">&nbsp;</td>         
                    
                </tr>
                <tr>
                	<td colspan="3" align="left">
                    	<br><br><br>
                        <?php $timestamp = strtotime(date('Y-m-d h:m:s'))?>
                        (H-<?php echo $timestamp ?>)
                    </td>
                </tr>
                
            </table>
            
        </div>        
    </div>
    
    
    
</div>

<hr style="border-top: 1px dashed #8c8b8b"/>
    

<!--	
    <div id="header">
    <div id="title">AVEGA BROS INTEGRATED SHIPPING CORP.</div>
    <div>J. De Veyra St. NRA, Cebu City</div>
    <div id="ttype">RECEIVING REPORT</div>
    <div id="rr_no">09230</div>
    <div id="recieve_from">Received From:</div>
    <div id="date">Date:</div>
    <div id="po_no">PO No.:</div>
    <div id="pr_no">PR No.:</div>
    <div id="si_no">SI No.:</div>
    <div id="dr_no">DR No.:</div>
    <div id="items">
    put table here
    </div>
    <div id="received_by">Received by:</div>
    <div id="inspected_by">Inspected by:</div>
    <div id="noted_by">Noted by:</div>
    <div id="copy">Warehouse Copy</div>

-->


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
