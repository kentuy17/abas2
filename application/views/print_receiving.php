<?php
		
	//NOTE: amount should be based on suppliers VAT computation total amount should be net of VAT	
	$company = $this->Abas->getCompany($po_info['company_id']);
	
	$comp_name = $company->name;
	$comp_address = $company->address;
?>



<!DOCTYPE html>
<html>
<head>
	<title><?php if(ENVIRONMENT=="development") { echo "DEV - "; } ?>AVega Business Automation System</title>
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

#watermark
{
    color:lightgrey;
    font-size:80px;
    float:center;
    transform:rotate(340deg);
    opacity:0.5;
    -webkit-transform:rotate(340deg);
    position: absolute;  
    left: 50px;
}

</style>

</head>
<body style="background:#FFFFFF">
<div  style="position:absolute; margin-top:20px; margin-left:700px">
	<button id="printMe" type="button" class="btn-xs btn-success" onClick=' $("#printThis").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print</button>&nbsp;&nbsp;
    <a href="<?php echo HTTP_PATH ?>inventory">
    <button type="button" class="btn-xs btn-default"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Back</button></div>
    </a>
<div align="center" style="width:800px; margin-left:0px; margin-top:-20px" id="printThis">
	
<?php

if($reprint==true){
    echo '<div id="watermark" style="top: 200px; z-index: 1;">REPRINTED COPY</div>';
    echo '<div id="watermark" style="top: 680px; z-index: 2;">REPRINTED COPY</div>';
}

?>
    
    <div id="header">
    
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="left">(WAREHOUSE COPY)</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center"><?php echo strtoupper($comp_name) ?></td>
                </tr>
                <tr>
                	<td colspan="3" align="center"><?php echo $comp_address ?></td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">&nbsp;
                    <span style="margin-right:10px; float:right">RR No.: <?php echo $summary[0]['control_number'] ?></span>
                    </td>
                </tr>
                
                <tr>
                	
                    <td align="center" id="title" colspan="3">
                   RECEIVING REPORT
                    
                    </td>
                    
                    
                </tr>
               
                <tr>
                	<td align="left">Received From:<strong> <?php 
															$supplier = $this->Inventory_model->getSuppliers($summary[0]['supplier_id']);
															//var_dump($company);
															echo $supplier[0]['name']; 
													?></strong></td>
                    <td align="left"></td>
                    <td align="left">Date: <?php echo date('F j, Y', strtotime($summary[0]['tdate'])) ?></td>
                    
                </tr>
                <tr>
                	<td align="left">PO No.: <?php echo $po_info['control_number'] ?></td>
                    <td align="left"></td>
                    <td align="left">PR NO.: <?php echo $po_info['request_id'] ?></td>
                    
                </tr>
                <tr>
                	<td align="left">SI No.: <?php echo $summary[0]['sales_invoice_no'] ?></td>
                    <td align="left"></td>
                    <td align="left">DR NO.: <?php echo $summary[0]['delivery_no'] ?></td>
                    
                </tr>
                
            </table>
           
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead style="background:#CCCCCC">
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
						
						$qty = ($detail['quantity'] != 0 || $detail['quantity'] != '' ? $detail['quantity'] : 0);
						$unit_price = ($detail['unit_price'] != 0 || $detail['unit_price'] != '' ? $detail['unit_price'] : 0);
						$lineTotal = $qty * $unit_price;
					?>
                    <tr>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $detail['quantity'] ?></td>
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
                    <?php } 
					
						$compute_tax = $this->Abas->computePurchaseTaxes($gtotal,$supplier[0]['id']);
						
						
					?>
                    <tr style="font-weight:600">
                        

                        <td align="right" colspan="5">Total:</td>
                        <td align="right"><?php echo number_format($gtotal,2, '.', ',') ?>&nbsp;</td>
                    </tr>
                    
                    
                </tbody>
                
            </table>
            
            
        	<table style="width:800px; margin-top:20px" >
            	
                <tr>
                	<td align="left">Received by:</td>
                    <td align="left">Inspected by:</td>
                    <td align="left">Noted by:</td>
                    
                </tr>
                
                
            </table>
            <br>
        </div>        
    </div>
    
    
    
</div>

<hr style="border-top: 1px dashed #8c8b8b"/>

<div align="center" style="width:800px; margin-left:0px; margin-top:-20px">

    <div id="header">
    
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="left">(ACCOUNTING COPY)</td>
                </tr>
                 <tr>
                	<td colspan="3" id="title" align="center"><?php echo strtoupper($comp_name); ?></td>
                </tr>
                <tr>
                	<td colspan="3" align="center"><?php echo $comp_address; ?></td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">&nbsp;
                    <span style="margin-right:10px; float:right">RR No.: <?php echo $summary[0]['control_number'] ?></span>
                    </td>
                </tr>
                
                <tr>
                	
                    <td align="center" id="title" colspan="3">
                   RECEIVING REPORT
                    
                    </td>
                    
                    
                </tr>
               
                <tr>
                	<td align="left">Received From:<strong> <?php 
															$supplier = $this->Inventory_model->getSuppliers($summary[0]['supplier_id']);
															//var_dump($company);
															echo $supplier[0]['name']; 
													?></strong></td>
                    <td align="left"></td>
                    <td align="left">Date: <?php echo date('F j, Y', strtotime($summary[0]['tdate'])) ?></td>
                    
                </tr>
               <tr>
                	<td align="left">PO No.: <?php echo $po_info['control_number'] ?></td>
                    <td align="left"></td>
                    <td align="left">PR NO.: <?php echo $po_info['request_id'] ?></td>
                    
                </tr>
                <tr>
                	<td align="left">SI No.: <?php echo $summary[0]['sales_invoice_no'] ?></td>
                    <td align="left"></td>
                    <td align="left">DR NO.: <?php echo $summary[0]['delivery_no'] ?></td>
                    
                </tr>
                
            </table>
           
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	<thead style="background:#CCCCCC">
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
						
						$qty = ($detail['quantity'] != 0 || $detail['quantity'] != '' ? $detail['quantity'] : 0);
						$unit_price = ($detail['unit_price'] != 0 || $detail['unit_price'] != '' ? $detail['unit_price'] : 0);
						$lineTotal = $qty * $unit_price;
					?>
                    <tr>
                        <td align="center"><?php echo $itemInfo[0]['item_code'] ?></td>
                        <td>&nbsp;&nbsp;<?php echo $itemInfo[0]['description'] ?></td>
                        <td align="center"><?php echo $detail['quantity'] ?></td>
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
                   
                     <?php } 
					
						$compute_tax = $this->Abas->computePurchaseTaxes($gtotal,$supplier[0]['id']);
						
						
					?>
                    <tr style="font-weight:600">
                        

                        <td align="right" colspan="5">Total:</td>
                        <td align="right"><?php echo number_format($gtotal,2, '.', ',') ?>&nbsp;</td>
                    </tr>
                    
                    
                </tbody>
                
            </table>
            <div><br>
            <table style="width:500px; border:thin 1px #000000 solid; font-size:10px; display:none" border="1px" >
            	<tr align="center" bgcolor="#CCCCCC">
                	<td colspan="4">Accounting Entry</td>         
                </tr>
                <tr align="center">
                	<td >Account Code</td>
                    <td >Account Name</td>
                    <td >Debit</td>
                    <td >Credit</td>
                    
                </tr>
                <?php foreach($entry as $e){ ?>
                <tr>
                	<td align="left"><?php echo $e['code'] ?></td>
                    <td align="left"><?php echo $e['name'] ?></td>
                    <td align="right"><?php echo number_format($e['debit_amount'],2) ?></td>
                    <td align="right"><?php echo number_format($e['credit_amount'],2) ?></td>
                    
                </tr>               
                <?php } ?>
            </table>
            </div>
        	<table style="width:800px; margin-top:30px" >
            	
                <tr>
                	<td align="left">Received by:</td>
                    <td align="left">Inspected by:</td>
                    <td align="left">Noted by:</td>
                    
                </tr>
                
                
            </table>
            <br><br><br>
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
