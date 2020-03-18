

<style>

#header{margin-top:30px}
#title{ font-size:14px; font-weight:600}
#ttype{ font-size:16px; font-weight:600; margin-top:20px}
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

<div class="panel panel-primary" >
        <div class="panel-heading" style="min-height">
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
           Stock Transfer Receipt - Receiving Form
        </div>
    </div>
<div id="table" class="panel-body" >


        	<table style="width:550px; " >
            	
                <tr>
                	<td colspan="3" id="title" align="left">Company: <?php echo $company->name ?></td>
                    <td align="right" colspan="3" ><strong>MTR No.: <?php echo $summary[0]['control_number'] ?></strong></td>
                </tr>
                <tr>

                     <td align="left"><br>Transfer Date: <?php echo date('m-d-Y', strtotime($summary[0]['transfer_date'])) ?></td>
                </tr>
                <tr>
                	<td align="left">Requested By:<strong> <?php 
															echo $summary[0]['transfered_by']; 
														?></strong></td>
                    <td align="left"></td>
                   
                    
                </tr>
                <tr>
                	<td align="left">Requesting Warehouse: <?php 
																echo $summary[0]['to_location'] 
														?></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    
                </tr>
                <tr>
                	<td align="left" colspan="3">Remarks:&nbsp;<?php echo $summary[0]['remark'] ?></td>

                </tr>
               
            </table>
            <br>
            <div class="panel-body">
            <table border="1px" class="table table-bordered table-striped table-hover">
            	<thead>
                	<tr align="center" style="font-weight:600">
                    <td width="15%">Item Code</td>
                    <td width="50%">Description</td>
                    <td width="5%">Qty</td>
                    <td width="5%">Unit</td>
                    <!---
                    <td width="10%">Unit Price</td>
                    <td width="15%">Line Total</td>
                    --->
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
                        <!---
                        <td align="right"><?php echo number_format($unit_price, 2, '.', ','); ?>&nbsp;</td>
                        <td align="right"><?php echo number_format($lineTotal,2, '.', ',') ?>&nbsp;</td>
                        --->
                    </tr>
					<?php 
						
						$gtotal = $lineTotal + $gtotal;
						
						}
						
					?>
                   
                </tbody>
                
            </table>
            </div>
            <div class="panel" align="center">
            	<input type="checkbox" name="confirmation" id="confirmation" />&nbspI hereby confirm that I have received the item/s in good and usable condition.
            </div>
           <form class="form-inline" role="form" id="transferForm" name="transferForm" action="<?php echo HTTP_PATH.'inventory/addTransferReceiving'; ?>" method="post"
           style=" float:left; margin-top:20px; margin-left:40px;">	
       			<div class="form-group">
                    <label for="receiving_remark">Remarks:</label>
                    <input class="form-control input-sm" type="text" name="receiving_remark" id="receiving_remark" style="width:300px"  />
                    <input type="hidden" name="transfer_id" id="transfer_id" value="<?php echo $summary[0]['id'] ?>"  />
                </div>      
            </form>
            <div>
                <br>
            	&nbsp&nbsp&nbsp&nbsp<button class="btn btn-sm btn-success" onclick="receiveTransfer()">Mark as Received</button>
            </div>
            <br>
        </div>    
  
<script>
	
	function receiveTransfer(){
		
		if($('#confirmation').is(':checked')){	
			document.forms['transferForm'].submit();
		}else{
            toastr['warning']("Please confirm first to continue.", "ABAS says:");
			return false;
		}
	}

</script>      