
<div style="width:1300px; margin-left:20px">
			<span style="float:left; margin-right:50px; margin-top:-15px">        
				<a class="like" href="<?php echo HTTP_PATH.'Accounting'; ?>"  title="Back">
                	<button>
                    	<span class="glyphicon glyphicon-hand-left" style="color:#FF0000"></span>  Back
                    </button>
                </a>
                &nbsp;&nbsp;
                <a class="like" href="#"  title="Print" onclick="window.print();">
                	<button>
                    	<span class="glyphicon glyphicon-print" style="color:#00FF00"></span>  Print
                    </button>
                </a>
                &nbsp;&nbsp;
                <a class="like" href="<?php echo HTTP_PATH.'Accounting'; ?>"  title="Export" style="display:none">
                	<button >
                    	<span class="glyphicon glyphicon-export" style="color:#FF9933"></span>  Export
                    </button>
                </a>
            </span>
            <br />
    <table class="table table-striped table-hover" data-toggle="table" width="80%">
    	<thead>
        	
            <tr style="font-size:12px; font-weight:600; background:#333; color:#FFFFFF" align="center">            	
                <td width="10%">Voucher Date</td>
                <td width="8%">Voucher No.</td>
               
                <td width="50%">Particular</td>
                <td width="5%">Amount</td>
                <td width="10%">Reference No</td>            
            </tr>
        </thead>
        
        <tbody>
        	<?php	
					$ctr = count($ex_report);
					$total = 0;
					for($i = 0; $i < $ctr; $i++){
			?>
            <tr>
            	<td align="left"><?php	echo $ex_report[$i]['check_voucher_date'] != '0000-00-00' ? date("M j\, Y ",strtotime($ex_report[$i]['check_voucher_date'])) : ''; ?></td>                
                <td align="left"><?php	echo $ex_report[$i]['check_voucher_no']; ?></td>
               
                <td align="left"><?php	echo $ex_report[$i]['particulars']; ?></td>
                <td align="right"><?php	echo number_format($ex_report[$i]['amount_in_php'],2); ?></td>
                <td align="left"><?php	echo $ex_report[$i]['reference_no']; ?></td>            
            </tr>
            <?php	
				$total = $total + $ex_report[$i]['amount_in_php'];
					}
			?>
        </tbody>
        <tfoot>
        	<tr style="font-size:16px; font-weight:600; background:#333; color:#FFFFFF">
            	<td align="right" colspan="3">Total:</td>                
                
                <td align="right"><?php	echo number_format($total,2); ?></td>
                <td align="center">&nbsp;
               	
                </td>            
            </tr>
        </tfoot>
    </table>
    		
    <br><br>&nbsp;
</div>


