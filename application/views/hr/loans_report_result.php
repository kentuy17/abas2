
<div style="width:1000px; margin-left:20px">
			<span style="float:right; margin-right:50px; margin-top:-5px">        
				<a class="like" href="<?php echo HTTP_PATH.'Hr'; ?>"  title="Back">
                	<< Back
                </a>
            </span>
    <table class="table table-striped table-hover" data-toggle="table" width="80%" style="font-size:12px;">
    	<thead>
        	
            <tr style="font-size:12px; font-weight:600; background:#333; color:#FFFFFF" align="center">            	
                <td width="7%">Employee ID</td>
                <td width="22%">Name</td>                               
                <td width="10%">Type</td>    
                <td width="15%">Loan Date</td>                
                <td width="15%">Loan Amount</td>                            
                <td width="5%">Status</td>                            
            </tr>
        </thead>
        
        <tbody>
        	<?php	
					$ctr = count($loans);
					//var_dump($ctr);
					//var_dump($loans);
					$total = 0;
					for($i = 0; $i < $ctr; $i++){
					
					
			?>
            <tr>
            	<td align="center"><?php echo $loans[$i]['employee_id']; ?></td>                
                <td align="left"><?php echo $loans[$i]['fullname']; ?></td>             
                <td align="left"><?php echo $loans[$i]['loan_type']; ?></td>            
                <td align="center"><?php echo date('F j, Y',strtotime($loans[$i]['date_loan'])); ?></td>            
                <td align="right"><?php echo number_format($loans[$i]['amount_loan'],2); ?></td>   
                <td align="center" ><?php echo $loans[$i]['stat']; ?></td>            
            </tr>
            <?php	
			
					}
			?>
        </tbody>
        <tfoot>
        	<tr style="font-size:16px; font-weight:600; background:#333; color:#FFFFFF">
            	<td align="right" colspan="6">              
                
               
                <a class="like" href="<?php echo HTTP_PATH.'Hr'; ?>"  title="Back">
                << Back
                </a>
                &nbsp; 
                </td>            
            </tr>
        </tfoot>
    </table>
    		
    <br><br>&nbsp;
</div>


