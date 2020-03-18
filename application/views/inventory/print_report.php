<?php
	
	
	//var_dump($report);
	//var_dump($location);
	//exit;

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
                	<td colspan="3" align="left">&nbsp;</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">AVEGA BROS INTEGRATED SHIPPING CORP.</td>
                </tr>
                <tr>
                	<td colspan="3" align="center">J. De Veyra St. NRA, Cebu City</td>
                </tr>
                <tr>
                	
                    <td align="center"  colspan="3">&nbsp;</td>
                    
                    
                </tr>
                <tr>
                	
                    <td align="center" id="title" colspan="3"><?php echo $title  ?></td>
                    
                    
                </tr>
                <tr>
                	<td align="left"><strong>&nbsp;
															
															
														</strong></td>
                    <td align="left"></td>
                    <td align="right"></td>
                    
                </tr>
                <?php if(isset($location) && $location != ''){  ?>
                <tr>
                	<td align="left"><strong> SITE/BRANCH: <?php echo $location;  ?>
															
															
														</strong></td>
                    <td align="left"></td>
                    <td align="right"></td>
                    
                </tr>
               <?php } ?>
               
               <?php if(isset($supplier) && $supplier[0]['name'] != ''){ 
			   			
						//var_dump($supplier[0]['name']); exit;
			   ?>
               <tr>
                	<td align="left"><strong> SUPPLIER: <?php echo $supplier[0]['name']; ?>
															
															
														</strong></td>
                    <td align="left"></td>
                    <td align="right"></td>
                    
                </tr>
               
               <?php } ?>
               
               <?php 
				//for issuance
				
					if(isset($vessel) && $vessel[0]['name'] != ''){ 
				
				?>
                
                <tr>
                	<td align="left"><strong> DEPT./VESSEL: <?php echo $vessel[0]['name']; ?>
															
															
														</strong></td>
                    <td align="left"></td>
                    <td align="right"></td>
                    
                </tr>
               
               <?php } ?>
               
               <?php 
				//for issuance
				
					if(isset($company)){ 
				
				?>
                 <tr>
                	<td align="left"><strong> COMPANY: <?php  echo $company->name; ?>
															
															
														</strong></td>
                    <td align="left"></td>
                    <td align="right"></td>
                    
                </tr>
                <?php } ?>
                
               <?php 
				//for issuance
				
					if(isset($from_date)){ 
				
				?>
                <tr>
                	<td align="left"><strong>FROM:  <?php 	if(isset($from_date)){ 
																echo date('F j, Y', strtotime($from_date)); 
															};	?>
                                            &nbsp;&nbsp;
                                            TO:  <?php if(isset($to_date)){ 
															echo date('F j, Y', strtotime($to_date));
														
														}	?>           
                                                        </strong></td>
                    <td align="left"></td>
                    <td align="right"><?php //echo date('m-d-Y') ?></td>
                    
                </tr>
                <?php } ?>
            </table>
            <br>
            
            <?php 
				if($type == 'RECEIVING'){ 
			?>
            
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead>
                	<tr align="center" style="font-weight:600">
                    <td width="10%">Date Received</td>
                    <td width="7%">RR #</td>
                    <td width="7%">PO #</td>
                    <td width="8%">DR/INV #</td>
                    <td width="8%">Item Code</td>
                    <td width="30%">Description</td>
                    <td width="7%">Qty</td>
                    <td width="10%">Unit</td>                    
                    <td width="10%">Unit Price</td>                    
                    <td width="25%">Amount</td>                    
                    </tr>
                </thead>
                <tbody>
                	<?php 
						$grandTotal = 0;
						$total_lines = 40;						
						$gtotal = 0;
						$ctr = count($report);
						$remaining = $total_lines - $ctr; 
						
						foreach($report as $rep){ 
						
						//get item info
						$itemInfo = $this->Inventory_model->getItems($rep['item_id']);
						
						//line total
						$lineTotal = $itemInfo[0]['unit_price'] * $rep['qty'];
						
					?>
                    <tr>
                        <td align="center"><?php echo date('m-d-Y', strtotime($rep['delivery_date'])) ?></td>
                        <td align="center"><?php echo $rep['id'] ?></td>
                        <td align="center"><?php echo $rep['delivery_receipt_no'] ?></td>
                        <td align="center"><?php echo $rep['po_no'] ?></td>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $rep['qty'] ?></td>
                        <td align="center"><?php echo $itemInfo[0]['unit'] ?></td>
                        <td align="right"><?php echo number_format($itemInfo[0]['unit_price'],2) ?>&nbsp;</td>
                        <td align="right"><?php echo number_format($lineTotal,2) ?>&nbsp;</td>
                        
                    </tr>
					<?php 
						
						$grandTotal = $grandTotal + $lineTotal;
						
						}
						
					for($i = 0; $i < $remaining;$i++){	
					?>
                    
                    
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>

                        
                    </tr>
                    <?php } ?>
                    <tr style="font-weight:600">
                        <td align="center">&nbsp;</td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="left">&nbsp;Total Items: <?php echo $ctr; ?></td>
                        
                        <td align="right"></td>
                        <td align="right">&nbsp;</td>
                        <td align="right"></td>
                        <td align="right"><?php echo number_format($grandTotal,2) ?>&nbsp;</td>
                    </tr>
                    
                </tbody>
                
            </table>
           
            <?php } ?>
            
            <?php 
				if($type == 'ISSUANCE'){ 
				
				//var_dump($report);
			?>
            
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead>
                	<tr align="center" style="font-weight:600">
                    <td width="10%">Date Released</td>
                    <td width="7%">IS No.</td>
                    <td width="8%">DEPT./VESSEL</td>
                    <td width="10%">Item Code</td>
                    <td width="30%">Description</td>
                    <td width="7%">Qty</td>
                    <td width="10%">Unit</td>                    
                    <td width="10%">Unit Price</td>                    
                    <td width="25%">Amount</td>                    
                    </tr>
                </thead>
                <tbody style="font-size:11px">
                	<?php 
						$grandTotal = 0;
						$total_lines = 40;						
						$gtotal = 0;
						$ctr = count($report);
						$remaining = $total_lines - $ctr; 
						
						foreach($report as $rep){ 
						
						//get item info
						$itemInfo = $this->Inventory_model->getItems($rep['item_id']);
						$lineTotal = $itemInfo[0]['unit_price'] * $rep['qty'];
						//line total
						
						if($rep['issued_for'] == 101){
							$vessel_n = 'Avega Integrated';
						}else{
							$vessel_name = $this->Inventory_model->getVessels($rep['issued_for']);	
							$vessel_n = $vessel_name[0]['name'];
						}	
						
					?>
                    <tr>
                        <td align="center"><?php echo date('m-d-Y', strtotime($rep['issue_date'])) ?></td>
                        <td align="center"><?php echo $rep['id'] ?></td>
                        <td align="left"><?php echo $vessel_n; ?></td>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $rep['qty'] ?></td>
                        <td align="center"><?php echo $itemInfo[0]['unit'] ?></td>
                        <td align="right"><?php echo number_format($itemInfo[0]['unit_price'],2) ?></td>
                        <td align="right"><?php echo number_format($lineTotal,2) ?></td>
                        
                    </tr>
					<?php 
						
						$grandTotal = $grandTotal + $lineTotal;
						
						}
						
					for($i = 0; $i < $remaining;$i++){	
					?>
                    
                    
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                    </tr>
                    <?php } ?>
                    <tr style="font-weight:600">
                        <td align="center">&nbsp;</td>
                        <td align="center"></td>
                        <td align="center"></td>                        
                        <td align="center"></td>
                        <td align="left">&nbsp;Total Items: <?php echo $ctr; ?></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="right"></td>
                        <td align="right"><?php echo number_format($grandTotal,2) ?>&nbsp;</td>
                    </tr>
                    
                </tbody>
                
            </table>
           
            <?php } ?>
            
            
            
			
			<?php 
				if($type == 'TRANSFER'){ 
			?>
            
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead>
                	<tr align="center" style="font-weight:600">
                    <td width="13%">Date Tranfered</td>
                    <td width="12%">TR #</td>
                    <td width="10%">Item Code</td>
                    <td width="40%">Description</td>
                    <td width="10%">Qty</td>
                    <td width="15%">Unit</td>                    
                    </tr>
                </thead>
                <tbody>
                	<?php 
						
						$total_lines = 40;						
						$gtotal = 0;
						$ctr = count($report);
						$remaining = $total_lines - $ctr; 
						
						foreach($report as $rep){ 
						
						//get item info
						$itemInfo = $this->Inventory_model->getItems($rep['item_id']);
						
						//line total
						
						
					?>
                    <tr>
                        <td align="center"><?php echo date('m-d-Y', strtotime($rep['delivery_date'])) ?></td>
                        <td align="center"><?php echo $rep['delivery_receipt_no'] ?></td>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $rep['qty'] ?></td>
                        <td align="center"><?php echo $itemInfo[0]['unit'] ?></td>
                        
                    </tr>
					<?php 
						
						
						
						}
						
					for($i = 0; $i < $remaining;$i++){	
					?>
                    
                    
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        
                    </tr>
                    <?php } ?>
                    <tr style="font-weight:600">
                        <td align="center">&nbsp;</td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="left">&nbsp;Total Items: <?php echo $ctr; ?></td>
                        
                        <td align="right"></td>
                        <td align="right">&nbsp;</td>
                    </tr>
                    
                </tbody>
                
            </table>
           
            <?php } ?>
           
           
           
           <?php 
				if($type == 'COUNT'){ 
			?>
            
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead>
                	<tr align="center" style="font-weight:600">                    
                    <td width="5%">#</td>
                    <td width="8%">Item Code</td>
                    <td width="30%">Item Description</td>
                    <td width="10%">Brand</td>
                    <td width="7%">Rack No.</td>
                    <td width="7%">Qty on Hand</td>
                    <td width="7%">Actual Count</td>
                    <td width="7%">Variance</td>
                    <td width="35%">Remarks</td>                    
                    
                    </tr>
                </thead>
                <tbody>
                	<?php 
						
						$total_lines = 40;						
						$gtotal = 0;
						$ctr = count($report);
						$remaining = $total_lines - $ctr; 
						$count = 1;
						foreach($report as $rep){ 
						
						//get item info
						$itemInfo = $this->Inventory_model->getItems($rep['item_id']);
						
						//line total
						$linetotal = $rep['tayud_qty'] + $rep['mkt_qty'] + $rep['nra_qty'];
						$locQty = 0;
						//direct to qty in location
						if($location == 'Tayud'){
							$locQty = $rep['tayud_qty'];
						}elseif($location == 'Makati'){
							$locQty = $rep['mkt_qty'];
						}elseif($location == 'NRA'){
							$locQty = $rep['nra_qty'];
						}
						
					?>
                    <tr>
                        
                        <td align="center"><?php echo $count; ?></td>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $rep['brand'] ?></td>
                         <td align="center"><?php echo $rep['stock_location'] ?></td>
                          <td align="center"><?php echo $locQty ?></td>
                          <td align="center">&nbsp;</td>
                          <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        
                    </tr>
					<?php 
						
						$count++;
						
						}
						
					for($i = 0; $i < $remaining;$i++){	
					?>
                    
                    
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        
                    </tr>
                    <?php } ?>
                    <tr style="font-weight:600">
                        <td align="center">&nbsp;</td>
                        <td align="center"></td>
                        
                        <td align="left">&nbsp;Total Items: <?php echo $ctr; ?></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="right"></td>
                        <td align="right">&nbsp;</td>
                    </tr>
                    
                </tbody>
                
            </table>
           
            <?php } ?>
           	
			<?php 
				if($type == 'PURCHASE ORDER'){ 
			?>
            	
                <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead>
                	<tr align="center" style="font-weight:600">                    
                    <td width="3%">#</td>
                    <td width="10%">PO Date</td>
                    <td width="8%">PO No.</td>
                    <td width="17%">Company</td>
                    <td width="8%">Item Code</td>
                    <td width="25%">Description</td>
                    <td width="5%">Qty</td>
                    <td width="7%">Unit</td>                    
                    <td width="8%">Unit Price</td>                    
                    <td width="25%">Amount</td>            
                    
                    </tr>
                </thead>
                <tbody>
                	<?php 
						
						$total_lines = 40;						
						$gtotal = 0;
						$ctr = count($report);
						$remaining = $total_lines - $ctr; 
						$count = 1;
						foreach($report as $rep){ 
						
						//get item info
						$itemInfo = $this->Inventory_model->getItems($rep['item_id']);
						
						//line total
						$lineTotal = $rep['qty'] * $rep['unit_price'];
						//$locQty = 0;
						
						
						$comp_name = $this->Abas->getCompany($rep['company_id']);		
						
					?>
                    <tr>
                        <td align="center"><?php echo $count ?></td>
                       	<td align="center"><?php echo date('F d, Y', strtotime($rep['po_date'])) ?></td>
                        <td align="center"><?php echo $rep['po_no'] ?></td>
                        <td align="left"><?php echo $comp_name->name; ?></td>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $rep['qty'] ?></td>
                        <td align="center"><?php echo $itemInfo[0]['unit'] ?></td>
                        <td align="right"><?php echo number_format($rep['unit_price'],2) ?>&nbsp;</td>
                        <td align="right"><?php echo number_format($lineTotal,2) ?>&nbsp;</td>
                        
                    </tr>
					<?php 
						
						$count++;
						$gtotal = $gtotal + $lineTotal;
						}
						
					for($i = 0; $i < $remaining;$i++){	
					?>
                    
                    
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="right"></td>
                        <td align="right">&nbsp;</td>
                        
                    </tr>
                    <?php } ?>
                    <tr style="font-weight:600">
                        <td align="center">&nbsp;</td>
                        
                        <td align="right" colspan="9">Grand Total:&nbsp;&nbsp;Php &nbsp;<?php echo number_format($gtotal,2); ?>&nbsp; </td>
                        
                    </tr>
                    
                </tbody>
                
            </table>
                
                
            
            <?php } ?>
            
           
        	<table style="width:800px; margin-top:40px; font-size:12px" >
            	<?php 
				if($type == 'COUNT'){ 
				?>
                <tr>
                	<td align="left">Counted By:_____________________</td>
                    <td align="center"><span  style="margin-right:30px">Verified By:_____________________</span></td>
                    <td align="left"></td>
                    <td align="right">Noted By:______________________</td>
                    
                </tr>
                <tr>
                	<td align="center"><span  style="margin-right:0px">Warehouseman</span></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left">&nbsp;</td>
                    
                </tr>
                <?php 
				}else{
				?>
                <tr>
                	<td align="left">Prepared by:_____________________</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="right">Checked by:______________________</td>
                    
                </tr>
                <?php 
					}
				?>
                <tr>
                	<td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left">&nbsp;</td>
                    
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
