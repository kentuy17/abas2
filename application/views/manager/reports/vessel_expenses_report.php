<h2>Vessel Expense Report</h2>
<br>
<h4><?php echo $type." of ".$vessel->name."<br>" ?></h4>
<h4>From <?php echo date('F j, Y',strtotime($date_from)). " to " . date('F j, Y',strtotime($date_to)); ?></h4>
<div style="overflow-x: auto">
	<table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
	    <thead>
	        <tr>
	            <th>Posted On</th> 
	            <th>Account</th>                                   
	            <th>Particulars</th>
	            <th>Amount</th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php
	        	$total_expenses = 0;
		        if(count($expenses)>0){
			        foreach($expenses as $row){
			        	if($row->debit_amount<=0){
				        	echo '<tr>';
				        		echo '<td>'.date('F j, Y',strtotime($row->posted_on)).'</td>';

				        		$account = $this->Accounting_model->getAccount($row->coa_id);
				        		echo '<td>'.$account['name'].'</td>';

				        		if($row->reference_table=='inventory_issuance'){
				        			$inv = $this->Inventory_model->getIssuances($row->reference_id);
				        			$particulars = 'MSIS #'.$inv[0]['id'].' - '.$inv[0]['remark'];
				        			$line_amount = $row->debit_amount;
				        		}elseif($row->reference_table=='ac_vouchers'){
				        			$cv = $this->Accounting_model->getVoucher($row->reference_id);
				        			$particulars = 'Check Voucher #'.$cv['id'].' - '.$cv['remark'];
				        			$line_amount = $row->debit_amount * 1.12;
				        		}else{
				        			$particulars = $row->remark;
				        		}

				        		echo '<td>'.$particulars.'</td>';
				        		echo '<td style="text-align:right">'.number_format($line_amount,2,'.',',').'</td>';
				        	echo '</tr>';
				        	$total_expenses = $total_expenses + $line_amount;
				        }
			        }
			        echo '<tr>';
			        	echo '<td colspan="3" style="text-align:right"><b>Total Expenditures:</b></td>';
			        	echo '<td style="text-align:right"><b>'.number_format($total_expenses,2,'.',',').'</b></td>';
			        echo '</tr>';
		    	}else{
		    		 echo '<tr>';
			        	echo '<td colspan="4" style="text-align:center">No record found</td>';
			        echo '</tr>';
		    	}
			?>
	    </tbody>
	</table>
</div>