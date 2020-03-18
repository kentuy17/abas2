



<div align="center" style="width:800px; margin-left:40px; margin-top:40px">

    <div id="header">
    <div style="float:left; position:absolute; margin-top:0px"><img src="<?php echo LINK ?>assets/images/AvegaLogo.png" width="50px"></div>
    <div id="items">
    	<div id="table">
        	<table style="width:900px; " >
            	
                <tr>
                	<td colspan="3" id="title" align="center">AVEGA BROS INTEGRATED SHIPPING CORP.</td>
                </tr>
                <tr>
                	<td colspan="3" align="center">J. De Veyra St. NRA, Cebu City</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">&nbsp;</td>
                </tr>
                <tr>
                	
                    <td align="center" id="title" colspan="3" style="font-size:20px; font-weight:600">
                    	<strong>STATEMENT OF ACCOUNT</strong>
                        <br>&nbsp;
                       
 
                    </td>
                    
                    
                </tr>
                <tr>
                	<td align="left">Account of:<strong> <?php 
															
															$company = $this->Operation_model->getServiceProvider($summary->service_provider_id);
															//var_dump($company);exit;															
															echo $company->company_name; 
													?></strong>
                                                    
                                                    </td>
                    <td align="left"></td>
                    <td align="left" width="30%"><span style="margin-left:50px">Date: <?php echo date('m-d-Y', strtotime($summary->date_created)) ?></span></td>
                    
                </tr>
                <tr>
                	<td align="left">Address.: <?php //echo  $company->address;?></td>
                    <td align="left"></td>
                    <td align="left" width="30%"><span style="margin-left:50px">REF NO.: <?php echo  $summary->reference_no;?></span></td>
                    
                </tr>
                <tr>
                	<td align="left" colspan="2"><?php echo  $summary->remarks;?></td>
                    <td align="left" >&nbsp;</td>
                    
                </tr>
                
            </table>
            <br>
            
<?php 
	
	if($summary->type == 'Trucking'){ 
		
?>  
             <table style="width:900px; border:thin 1px #000000 solid; font-size:11px" border="1px">
            	<thead  style="font-weight:600; background:#333333; color:#FFFFFF">
                	<tr align="center" style="font-weight:600">
                        <td width="10%">Date</td>
                        <td width="8%">Truck #</td>
                        <td width="10%">Origin</td>
                        <td width="9%">WSR #</td>
                        <td width="10%">Variety</td>
                        <td width="10%">Destination</td>
                        <td width="8%">Weight (M/T)</td>
                        <td width="8%">No. of Bags</td>
                        <td width="10%">Rate / (M/T)</td>                  
                        <td width="10%">Amount</td>
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
						//var_dump($detail['rate']); exit;
						//get warehouse name
						$from_location = $this->Operation_model->getTruckingLocation($wsrInfo->from_location);
						$to_location = $this->Operation_model->getTruckingLocation($wsrInfo->to_location);
						
						$mt = $wsrInfo->net_weight / 1000;
						$no_bags = $wsrInfo->net_weight / 50;
						//$qty = ($detail['qty'] != 0 || $detail['qty'] != '' ? $detail['qty'] : 0);
						//$unit_price = ($itemInfo[0]['unit_price'] != 0 || $itemInfo[0]['unit_price'] != '' ? $itemInfo[0]['unit_price'] : 0);
						$lineTotal = $mt * $detail['rate'];
						
						
					?>
                    <tr>
                        <td align="center"><?php echo date('F j, Y', strtotime($wsrInfo->issue_date)) ?></td>
                        <td align="center"><?php echo $wsrInfo->truck_plate_no ?></td>
                        <td align="left">&nbsp;<?php echo $from_location->name ?></td>
                        <td align="center"><?php echo $wsrInfo->wsr_no; ?></td>
                        <td align="left">&nbsp;<?php echo $wsrInfo->variety; ?></td>
                        <td align="left"><?php echo $to_location->name ?></td>
                        <td align="right"><span style="margin-right:0px"><?php echo number_format($wsrInfo->net_weight,0); ?></span>&nbsp;</td>
                        <td align="right"><?php echo number_format($no_bags,0) ?>&nbsp;</td>
                        <td align="right"><?php echo number_format($detail['rate'],2) ?>&nbsp;</td>
                        <td align="right"><span style="margin-right:0px"><?php echo number_format($lineTotal,2) ?></span>&nbsp;</td>
                    </tr>
					<?php 
						
						$total_amount = $lineTotal + $total_amount;
						$total_gw = $wsrInfo->net_weight + $total_gw;
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
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right"></td>
                    </tr>
                    <?php } ?>
                    <tr style="font-weight:600; background:#333333; color:#FFFFFF">
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="right"></td>
                        <td align="right">Total:&nbsp;</td>
                        <td align="right"><?php echo number_format($total_gw,0) ?>&nbsp;</td>
                        <td align="right"><?php echo number_format($total_bags,0) ?>&nbsp;</td>
                        
                        <td align="right" colspan="2"><span style="margin-right:0px">Php <?php echo number_format($total_amount,2) ?></span>&nbsp;</td>
                    </tr>
                    
                </tbody>
                
            </table>
            

<?php } //end trucking ?>      

            <div align="left" style="margin-left:20px; margin-top:30px">
                Amount in Words: 
            </div>
        	<table style="width:800px; margin-top:50px; margin-left:50px;" >
            	
                <tr>
                	<td align="left">Prepared by:</td>
                    <td align="left">Noted by:</td>
                    <td align="left">Approved by:</td>
                    
                </tr>
                <tr>
                	<td colspan="3" align="left">&nbsp;</td>         
                    
                </tr>
                <tr>
                	<td colspan="3" align="right">&nbsp;</td>         
                    
                </tr>
                <tr style="font-weight:600" >
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
                	<td colspan="3" align="right">&nbsp;</td>         
                    
                </tr>
                <tr>
                	<td colspan="3" align="right">
                    	<button type="button" class="btn btn-success" style="margin-right:50px"><i class="fa fa-check"></i>  Approve</button>
                    </td>
                </tr>
                <tr>
                	<td colspan="3" align="right">&nbsp;</td>         
                    
                </tr>
            </table>
            
        </div>        
    </div>
    
    
    
</div>

<hr style="border-top: 1px dashed #8c8b8b"/>
 


</div>


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

