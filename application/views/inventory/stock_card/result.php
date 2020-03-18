<h2>Stock Card</h2>
<h4><?php echo "For Item: ".$item[0]['description']. " (" .$item[0]['unit'].")<br>" ?></h4>
<div style="overflow-x: auto">
	<table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
	        <tr>
	        	<td rowspan='2'><b><center>#</center></b></td> 
	            <td rowspan='2'><b><center>Date of Transaction</center></b></td> 
	            <td colspan='2'><b><center>Receipt</center></b></td>                                   
	            <td colspan='2'><b><center>Issuance</center></b></td>
	            <td rowspan='2'><b><center>Balance</center></b></td>
	            <td rowspan='2'><b><center>Company</center></b></td>
	            <td rowspan='2'><b><center>Location</center></b></td>
	        </tr>
	        <tr>
	            <td>Ref. Document.</td>                                   
	            <td>Qty In</td>
	            <td>Ref. Document.</td>                                   
	            <td>Qty Out</td>
	        </tr>
	       <?php 
	       		$balance = 0;
	       		$ctr = 1;
	       		$ctr_receving  = 0;
	       		$total_receiving = 0;
	       		$ctr_issuance = 0;
	       		$total_issuance = 0;
	       		$company_receiving = array();
	       		$company_issuance = array();
	       		$company = array();
	       		$location = '';
	       		foreach($stock_in_out as $row){
	       			echo "<tr>";
	       				echo "<td>".$ctr."</td>";
	       				echo "<td>".date('F j, Y',strtotime($row->trans_date))."</td>";
	       				if($row->type=='Receiving'){
		       				echo "<td><a href='".HTTP_PATH."inventory/view_transaction_history_details/delivery/".$row->ref_id."' data-toggle='modal' data-target='#modalDialog'>RR - ".$row->ref_id."</a></td>";
		       				echo "<td>".$row->quantity."</td>";
		       				echo "<td>--</td>";
		       				echo "<td>--</td>";
		       				echo "<td>".($balance + $row->quantity)."</td>";
		       				$balance = $balance + $row->quantity;
		       				$total_receiving = $total_receiving + $row->quantity;
		       				$ctr_receving++;

		       				$delivery = $this->Inventory_model->getDelivery($row->ref_id);
		       				if(isset($company_receiving[$delivery[0]['company_id']])){
		       					$company_receiving[$delivery[0]['company_id']] = $company_receiving[$delivery[0]['company_id']] + $row->quantity;
		       				}else{
		       					$company_receiving[$delivery[0]['company_id']] = $row->quantity;
		       				}
		       				$company_name = $this->Abas->getCompany($delivery[0]['company_id']);
		       				$location = $delivery[0]['location'];
	       				}else{
	       					echo "<td>--</td>";
		       				echo "<td>--</td>";
	       					echo "<td><a href='".HTTP_PATH."inventory/view_transaction_history_details/issuance/".$row->ref_id."' data-toggle='modal' data-target='#modalDialog'>MSIS - ".$row->ref_id."</a></td>";
		       				echo "<td>".$row->quantity."</td>";
		       				echo "<td>".($balance - $row->quantity)."</td>";
		       				$balance = $balance - $row->quantity;
		       				$total_issuance = $total_issuance + $row->quantity;
		       				$ctr_issuance++;

		       				$issuance = $this->Inventory_model->getIssuances($row->ref_id);
		       				if($issuance[0]['company_id']==0){
		       					$vessel = $this->Abas->getVessel($issuance[0]['vessel_id']);
		       					if(isset($company_issuance[$vessel->company])){
		       						$company_issuance[$vessel->company] =  $company_issuance[$vessel->company] + $row->quantity;
		       					}else{
		       						$company_issuance[$vessel->company] = $row->quantity;
		       					}
		       					$company_name = $this->Abas->getCompany($vessel->company);
		       				}else{
		       					if(isset($company_issuance[$issuance[0]['company_id']])){
		       						$company_issuance[$issuance[0]['company_id']] =  $company_issuance[$issuance[0]['company_id']] + $row->quantity;
		       					}else{
		       						$company_issuance[$issuance[0]['company_id']] =  $row->quantity;
		       					}
		       					$company_name = $this->Abas->getCompany($issuance[0]['company_id']);
		       					$location = $issuance[0]['from_location'];
		       				}
	       				}
	       				echo "<td>".$company_name->name."</td>";
	       				echo "<td>".$location."</td>";
	       			echo "</tr>";
	       			$ctr++;
	       		}
	       ?>
	</table>
</div>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
	<div class="tile-stats">
		<div class="icon"><i class="fa fa-sort-amount-desc"></i>
		</div>
		<div class="count">
			<?php
				$qty = 0;
				//$qty = $item_qty[0]['nra_qty'] + $item_qty[0]['tayud_qty'] + $item_qty[0]['mkt_qty'];
				$quantity_stock = $this->Inventory_model->getItemQuantityAllCompany($item[0]['id']);
		 		$qty = ($quantity_stock[0]->sum_received - $quantity_stock[0]->sum_issued);
				echo $qty;
			?>
		</div>
		<h3>Balance (Inventory)</h3>
	</div>
</div>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
	<div class="tile-stats">
		<div class="icon"><i class="fa fa-sort-amount-desc"></i>
		</div>
		<div class="count">
			<?php
				echo $balance;
			?>
		</div>
		<h3>Balance (Stock In-Out)</h3>
	</div>
</div>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
	<div class="tile-stats">
		<div class="icon"><i class="fa fa-sort-amount-desc"></i>
		</div>
		<div class="count">
			<?php
				$variance = 0;
				$variance = $qty - $balance;
				if($variance<0){
					$variance = "(".abs($variance).")";
					echo '<span style="color:red">'.$variance.'</span>';
				}else{
					$variance = $variance;
					echo $variance;
				}
			?>
		</div>
		<h3>Variance</h3>
	</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
	<div class="tile-stats">
		<div class="icon"><i class="fa fa-sort-amount-desc"></i>
		</div>
		<div class="count">
			<?php
			    $average_receiving = 0;
			    if($ctr_receving<>0){
			    	$average_receiving = ($total_receiving/$ctr_receving);
				}
				echo number_format($average_receiving,2,'.',',');
			?>
		</div>
		<h3>Avg Receiving Qty</h3>
	</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
	<div class="tile-stats">
		<div class="icon"><i class="fa fa-sort-amount-desc"></i>
		</div>
		<div class="count">
			<?php
				$average_issuance = 0;
				if($ctr_issuance<>0){
			    	$average_issuance = ($total_issuance/$ctr_issuance);
				}
				echo number_format($average_issuance,2,'.',',');
			?>
		</div>
		<h3>Avg Issuance Qty</h3>
	</div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<hr>
	<h4>Summary of Stock-on-Hand per Company</h4>
	<table class='table table-striped table-bordered table-responsive'>
		<thead>
			<th>Company</th>
			<th>Qty Received</th>
			<th>Qty Issued</th>
			<th>Remaining Stock</th>
		</thead>
		<tbody>
			<?php
				$total_receving = 0;
				$total_issuance = 0;
				foreach($companies as $company){
					$qty_receiving = 0;
					$qty_issuance = 0;
					if(isset($company_receiving[$company->id]) || isset($company_issuance[$company->id])){
						echo "<tr>";
							echo "<td>".$company->name."</td>";
							if(isset($company_receiving[$company->id])){
								$qty_receiving = $company_receiving[$company->id];
								echo "<td>".$qty_receiving."</td>";
								$total_receving = $total_receving + $company_receiving[$company->id];
							}else{
								echo "<td>--</td>";
							}
							if(isset($company_issuance[$company->id])){
								$qty_issuance = $company_issuance[$company->id];
								echo "<td>".$qty_issuance."</td>";
								$total_issuance = $total_issuance + $company_issuance[$company->id];
							}else{
								echo "<td>--</td>";
							}
							$qty_on_hand = ($qty_receiving - $qty_issuance);
							//if($qty_on_hand < 0){
							//	echo "<td>0</td>";
							//}else{
								echo "<td>".$qty_on_hand."</td>";
							//}
						echo "</tr>";
					}
				}
				echo "<tr>";
					echo "<td style='text-align:right'>Total</td>";
					echo "<td>".$total_receving."</td>";
					echo "<td>".$total_issuance."</td>";
					echo "<td>".($total_receving - $total_issuance)."</td>";
				echo "</tr>";
			?>
		</tbody>
	</table>
</div>