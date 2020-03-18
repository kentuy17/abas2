
<div style="width:800px; margin-left:20px">
			<span style="float:right; margin-right:50px; margin-top:-5px">        
				<a class="like" href="<?php echo HTTP_PATH.'Hr'; ?>"  title="Back">
                	<< Back
                </a>
            </span>
    <table class="table table-striped table-hover" data-toggle="table" width="100%" style="font-size:12px;">
    	<thead>
        	
            <tr style="font-size:12px; font-weight:600; background:#333; color:#FFFFFF" align="center">            	
                <td width="20%">Employee ID</td>
                <td width="55%">Name</td>    
                <td width="55%">Date Hired</td>                               
                <td width="25%">Total Contribution</td>                                                
            </tr>
        </thead>
        
        <tbody>
        	<?php	
					$ctr = count($elf);
					$total = 0;
					for($i = 0; $i < $ctr; $i++){
					
					//get position and department
					//$department = $this->Abas->getDepartments($employees[$i]['department']);
					//$position = $this->Abas->getPositions($employees[$i]['position']);
					//var_dump($loans);exit;
					//if($department){echo $department['name'];}
					
					
					//compute balance
					
			?>
            <tr>
            	<td align="center"><?php echo $elf[$i]['employee_id']; ?></td>                
                <td align="left"><?php echo $elf[$i]['fullname']; ?></td>
               <td align="left">&nbsp;&nbsp;&nbsp;<?php echo date('F j, Y',strtotime($elf[$i]['date_hired'])); ?></td>
                <td align="right"><?php echo number_format($elf[$i]['total_elf_contribution'],2); ?></td>
                
               
                
                
            </tr>
            <?php	
					$total = $total + $elf[$i]['total_elf_contribution'];
			
					}
			?>
        </tbody>
        <tfoot>
        	<tr style="font-size:16px; font-weight:600; background:#333; color:#FFFFFF">
            	<td align="right" colspan="3">&nbsp;Total: </td>                
                
                <td align="right">&nbsp;<?php echo number_format($total,2); ?></td>
                
            </tr>
        </tfoot>
    </table>
    		<a class="like" href="<?php echo HTTP_PATH.'Hr'; ?>"  title="Back">
                << Back
                </a>
    <br><br>&nbsp;
</div>


