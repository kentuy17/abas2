<?php
	
	
	//var_dump($summary['delivery_date']);
	var_dump($result);
	exit;

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
#title{ font-size:16px; font-weight:600}
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
    
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="left">(WAREHOUSE COPY)</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">AVEGA BROS INTEGRATED SHIPPING CORP.</td>
                </tr>
                <tr>
                	<td colspan="3" align="center">J. De Veyra St. NRA, Cebu City</td>
                </tr>
                
                <tr>
                	
                    <td align="center" id="title" colspan="3">MATERIALS & SUPPLIES ISSUANCE SLIP</td>
                    
                    
                </tr>
                <tr>
                	
                    
                    <td align="right" colspan="3" ><strong>MSIS No.: <?php echo $summary[0]['id'] ?></strong></td>
                    
                </tr>
                <tr>
                	<td align="left">Issued To:<strong> <?php 
															//$company = $this->Inventory_model->getSuppliers($summary[0]['supplier_id']);
															echo $summary[0]['issued_to']; 
														?></strong></td>
                    <td align="left"></td>
                    <td align="right">Date: <?php echo date('m-d-Y', strtotime($summary[0]['issue_date'])) ?></td>
                    
                </tr>
                <tr>
                	<td align="left">Department/Vessel: <?php 
																//echo $summary[0]['to_location'] 
																$vessel = $this->Inventory_model->getVessels($summary[0]['issued_for']);
																//var_dump($vessel);
																echo $vessel[0]['name'];
														?></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    
                </tr>
               
                
            </table>
            <br>
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead>
                	<tr align="center" style="font-weight:600">
                    <td width="15%">Item Code</td>
                    <td width="50%">Description</td>
                    <td width="5%">Qty</td>
                    <td width="5%">Unit</td>
                    <td width="10%">Unit Price</td>
                    <td width="15%">Line Total</td>
                    </tr>
                </thead>
                <tbody>
                	<?php 
						
						$total_lines = 10;						
						$gtotal = 0;
						$ctr = count($details);
						$remaining = $total_lines - $ctr; 
						
						foreach($details as $detail){ 
						
						//get item info
						$itemInfo = $this->Inventory_model->getItems($detail['item_id']);
						
						//line total
						
						$qty = ($detail['qty'] != 0 || $detail['qty'] != '' ? $detail['qty'] : 0);
						$unit_price = ($itemInfo[0]['unit_price'] != 0 || $itemInfo[0]['unit_price'] != '' ? $itemInfo[0]['unit_price'] : 0);
						$lineTotal = $qty * $unit_price;
					?>
                    <tr>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $detail['qty'] ?></td>
                        <td align="center"><?php echo $detail['unit'] ?></td>
                        <td align="right"><?php echo number_format($unit_price, 2, '.', ','); ?>&nbsp;</td>
                        <td align="right"><?php echo number_format($lineTotal,2, '.', ',') ?>&nbsp;</td>
                    </tr>
					<?php 
						
						$gtotal = $lineTotal + $gtotal;
						
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
                    <tr style="font-weight:600">
                        <td align="center">&nbsp;</td>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="right">Total:</td>
                        <td align="right"><?php echo number_format($gtotal,2, '.', ',') ?>&nbsp;</td>
                    </tr>
                    
                </tbody>
                
            </table>
            <div style="margin-top:10px; font-weight:600" align="left">Purpose: &nbsp;<?php echo $summary[0]['remark'] ?></div>
        	<table style="width:800px; margin-top:20px; font-size:12px" >
            	
                <tr>
                	<td align="left">Requested by:_____________________</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="right">Released by:______________________</td>
                    
                </tr>
                <tr>
                	<td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left">&nbsp;</td>
                    
                </tr>
                <tr>
                	<td align="left">Approved by:_____________________</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="right">REceived by:______________________</td>
                    
                </tr>
                
                
            </table>
            <br>
        </div>        
    </div>
    
    
    
</div>

<hr style="border-top: 1px dashed #8c8b8b"/>

<div align="center" style="width:800px; margin-left:0px">

    <div id="header">
    
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="left">(ACCOUNTING COPY)</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">AVEGA BROS INTEGRATED SHIPPING CORP.</td>
                </tr>
                <tr>
                	<td colspan="3" align="center">J. De Veyra St. NRA, Cebu City</td>
                </tr>
                
                <tr>
                	
                    <td align="center" id="title" colspan="3">MATERIALS & SUPPLIES ISSUANCE SLIP</td>
                    
                    
                </tr>
                <tr>
                	
                    
                    <td align="right" colspan="3" ><strong>MSIS No.: <?php echo $summary[0]['id'] ?></strong></td>
                    
                </tr>
                <tr>
                	<td align="left">Issued To:<strong> <?php 
															//$company = $this->Inventory_model->getSuppliers($summary[0]['supplier_id']);
															echo $summary[0]['issued_to']; 
														?></strong></td>
                    <td align="left"></td>
                    <td align="right">Date: <?php echo date('m-d-Y', strtotime($summary[0]['issue_date'])) ?></td>
                    
                </tr>
                <tr>
                	<td align="left">Department/Vessel: <?php 
																//echo $summary[0]['po_no'] 
																$vessel = $this->Inventory_model->getVessels($summary[0]['issued_for']);
																//var_dump($vessel);
																echo $vessel[0]['name'];
														?></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    
                </tr>
               
                
            </table>
            <br>
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead>
                	<tr align="center" style="font-weight:600">
                    <td width="15%">Item Code</td>
                    <td width="50%">Description</td>
                    <td width="5%">Qty</td>
                    <td width="5%">Unit</td>
                    <td width="10%">Unit Price</td>
                    <td width="15%">Line Total</td>
                    </tr>
                </thead>
                <tbody>
                	<?php 
						
						$total_lines = 10;						
						$gtotal = 0;
						$ctr = count($details);
						$remaining = $total_lines - $ctr; 
						
						foreach($details as $detail){ 
						
						//get item info
						$itemInfo = $this->Inventory_model->getItems($detail['item_id']);
						
						//line total
						
						$qty = ($detail['qty'] != 0 || $detail['qty'] != '' ? $detail['qty'] : 0);
						$unit_price = ($itemInfo[0]['unit_price'] != 0 || $itemInfo[0]['unit_price'] != '' ? $itemInfo[0]['unit_price'] : 0);
						$lineTotal = $qty * $unit_price;
					?>
                    <tr>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $detail['qty'] ?></td>
                        <td align="center"><?php echo $detail['unit'] ?></td>
                        <td align="right"><?php echo number_format($unit_price, 2, '.', ','); ?>&nbsp;</td>
                        <td align="right"><?php echo number_format($lineTotal,2, '.', ',') ?>&nbsp;</td>
                    </tr>
					<?php 
						
						$gtotal = $lineTotal + $gtotal;
						
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
                    <tr style="font-weight:600">
                        <td align="center">&nbsp;</td>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="right">Total:</td>
                        <td align="right"><?php echo number_format($gtotal,2, '.', ',') ?>&nbsp;</td>
                    </tr>
                    
                </tbody>
                
            </table>
            <div style="margin-top:10px; font-weight:600" align="left">Purpose: &nbsp;<?php echo $summary[0]['remark'] ?></div>
        	<table style="width:800px; margin-top:20px; font-size:12px" >
            	
                <tr>
                	<td align="left">Requested by:_____________________</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="right">Approved by:______________________</td>
                    
                </tr>
                <tr>
                	<td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left">&nbsp;</td>
                    
                </tr>
                <tr>
                	<td align="left">Released by:_____________________</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="right">Received by:______________________</td>
                    
                </tr>
                
                
            </table>
            <br>
        </div>        
    </div>
    
    
    
</div>




<div align="center" style="width:800px; margin-left:0px; margin-top:-30px">

    <div id="header">
    
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="left">(RECEIVING COPY)</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">AVEGA BROS INTEGRATED SHIPPING CORP.</td>
                </tr>
                <tr>
                	<td colspan="3" align="center">J. De Veyra St. NRA, Cebu City</td>
                </tr>
                
                <tr>
                	
                    <td align="center" id="title" colspan="3">MATERIALS & SUPPLIES ISSUANCE SLIP</td>
                    
                    
                </tr>
                <tr>
                	
                    
                    <td align="right" colspan="3" ><strong>MSIS No.: <?php echo $summary[0]['id'] ?></strong></td>
                    
                </tr>
                <tr>
                	<td align="left">Issued To:<strong> <?php 
															//$company = $this->Inventory_model->getSuppliers($summary[0]['supplier_id']);
															echo $summary[0]['issued_to']; 
														?></strong></td>
                    <td align="left"></td>
                    <td align="right">Date: <?php echo date('m-d-Y', strtotime($summary[0]['issue_date'])) ?></td>
                    
                </tr>
                <tr>
                	<td align="left">Department/Vessel: <?php 
																//echo $summary[0]['po_no'] 
																$vessel = $this->Inventory_model->getVessels($summary[0]['issued_for']);
																//var_dump($vessel);
																echo $vessel[0]['name'];
														?></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    
                </tr>
               
                
            </table>
            <br>
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead>
                	<tr align="center" style="font-weight:600">
                    <td width="15%">Item Code</td>
                    <td width="50%">Description</td>
                    <td width="5%">Qty</td>
                    <td width="5%">Unit</td>
                    <td width="10%">Unit Price</td>
                    <td width="15%">Line Total</td>
                    </tr>
                </thead>
                <tbody>
                	<?php 
						
						$total_lines = 10;						
						$gtotal = 0;
						$ctr = count($details);
						$remaining = $total_lines - $ctr; 
						
						foreach($details as $detail){ 
						
						//get item info
						$itemInfo = $this->Inventory_model->getItems($detail['item_id']);
						
						//line total
						
						$qty = ($detail['qty'] != 0 || $detail['qty'] != '' ? $detail['qty'] : 0);
						$unit_price = ($itemInfo[0]['unit_price'] != 0 || $itemInfo[0]['unit_price'] != '' ? $itemInfo[0]['unit_price'] : 0);
						$lineTotal = $qty * $unit_price;
					?>
                    <tr>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $detail['qty'] ?></td>
                        <td align="center"><?php echo $detail['unit'] ?></td>
                        <td align="right"><?php echo number_format($unit_price, 2, '.', ','); ?>&nbsp;</td>
                        <td align="right"><?php echo number_format($lineTotal,2, '.', ',') ?>&nbsp;</td>
                    </tr>
					<?php 
						
						$gtotal = $lineTotal + $gtotal;
						
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
                    <tr style="font-weight:600">
                        <td align="center">&nbsp;</td>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="right">Total:</td>
                        <td align="right"><?php echo number_format($gtotal,2, '.', ',') ?>&nbsp;</td>
                    </tr>
                    
                </tbody>
                
            </table>
            <div style="margin-top:10px; font-weight:600" align="left">Purpose: &nbsp;<?php echo $summary[0]['remark'] ?></div>
        	<table style="width:800px; margin-top:20px; font-size:12px" >
            	
                <tr>
                	<td align="left">Requested by:_____________________</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="right">Approved by:______________________</td>
                    
                </tr>
                <tr>
                	<td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left">&nbsp;</td>
                    
                </tr>
                <tr>
                	<td align="left">Released by:_____________________</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="right">Received by:______________________</td>
                    
                </tr>
                
                
            </table>
            <br>
        </div>        
    </div>
    
    
    
</div>


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
