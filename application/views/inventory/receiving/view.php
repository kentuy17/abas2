<h2>Receiving Report</h2>
<div>
<?php
	echo '<a href="'.HTTP_PATH.'inventory/receiving/print_rr/'.$RR[0]['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print RR</a>';
	if($RR[0]['is_issued']==1){
		echo '<a href="'.HTTP_PATH.'inventory/receiving/print_msis/'.$RR[0]['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print MSIS</a>';
	}
	echo '<a href="'.HTTP_PATH.'inventory/receiving/listview" class="btn btn-dark force-pageload">Back</a>';
?>
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code No. <?php echo $RR[0]['id'];?> |
				Control No. <?php echo $RR[0]['control_number']; ?>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $company->name; ?></h3>
		<h4 class="text-center"><?php echo $company->address; ?></h3>
		<h4 class="text-center"><?php echo $company->telephone_no." - ".$company->fax_no; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Received By:</th>
				<td><?php echo $RR[0]['received_by']; ?></td>
			</tr>
			<tr>
				<th>Date Received:</th>
				<td><?php echo  date("F d, Y", strtotime($RR[0]['tdate'])); ?></td>
			</tr>
			<tr>
				<th>Delivery Receipt:</th>
				<td><?php echo $RR[0]['delivery_no']; ?></td>
			</tr>
			<tr>
				<th>Sales Invoice:</th>
				<td><?php echo $RR[0]['sales_invoice_no']; ?></td>
			</tr>
			<tr>
				<th>Supplier:</th>
				<td><?php echo $supplier['name']; ?></td>
			</tr>
			<tr>
				<th>Amount:</th>
				<td><?php echo number_format($RR[0]['amount'],2,'.',','); ?></td>
			</tr>
			<tr>
				<th>Purchase Order Transaction Code No.:</th>
				<td><?php echo  $RR[0]['po_no']; ?></td>
			</tr>
			<tr>
				<th>Location:</th>
				<td><?php echo  $RR[0]['location']; ?></td>
			</tr>
			<tr>
				<th>Remark:</th>
				<td><?php echo  $RR[0]['remark']; ?></td>
			</tr>
		</table>
		<table class="table table-striped table-bordered">
			<?php if($RR[0]['is_issued']==1){ ?>
				<tr>
					<th>Direct Delivery?</th>
					<td><?php echo  'Yes'?></td>
				</tr>
				<tr>
					<th>MSIS Transaction Code No.:</th>
					<td><?php echo  $RR[0]['issuance_id']?></td>
				</tr>
			<?php }?>
			<?php if($RR[0]['notice_of_discrepancy_id']>0){ ?>
				<tr>
					<th>Notice of Discrepancy Transaction Code No.:</th>
					<td><?php echo $RR[0]['notice_of_discrepancy_id']?></td>
				</tr>
			<?php }?>
			<?php if($RR[0]['is_cleared']!=1){ ?>
				<tr>
					<th>Cleared in Accounting?</th>
					<td>
						<?php 
							if($RR[0]['is_cleared']==1){
								echo "Yes";
							}else{
								echo "No";
							}
						?>
					</td>
				</tr>
			<?php }?>
		</table>
		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($RR[0]['created_on'])); ?> by <?php echo $received_by['full_name']; ?></p>
		
	</div>

</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body" style="overflow-x: auto">
  		<table class="table table-striped table-bordered">
			<thead>
                <tr>
                  <th>#</th>
                  <th>Item Code</th>
                  <th>Item Name</th>
                  <th>Particulars</th>
                  <th>Quantity</th>
                  <th>Unit</th>
                  <th>Unit Price</th>
                  <th>Amount</th>
                  <th>Manage</th>
                </tr>
            </thead>
            <tbody>
              	<?php 
              		$ctr =1;
              		$total = 0;
              		foreach($RR_details as $row){
              			$item = $this->Inventory_model->getItem($row['item_id']);
              			echo "<tr>";
              				echo "<td>".$ctr."</td>";
              				echo "<td>".$item[0]['item_code']."</td>";
              				echo "<td>".$item[0]['item_name']."</td>";
              				echo "<td>".$item[0]['brand']." ".$item[0]['particular']."</td>";
              				echo "<td>".$row['quantity']."</td>";
              				echo "<td>".$row['unit']."</td>";
              				echo "<td>".number_format($row['unit_price'],2,'.',',')."</td>";
              				echo "<td>".number_format(($row['unit_price']*$row['quantity']),2,'.',',')."</td>";
              				echo "<td><a href='".HTTP_PATH."inventory/receiving/print_qr_code/".$row['delivery_id']."/".$row['item_id']."' class='btn btn-primary btn-xs btn-block' target='_blank'>QR Code</a></td>";
              			echo "</tr>";
              			$total = $total + ($row['unit_price']*$row['quantity']);
              			$ctr++;
              		}
              		echo "<tr>
              				  <td colspan='7' style='text-align:right'><b>Total Amount</b></td>
              				  <td><b>".number_format($total,2,'.',',')."</b></td>
              			  <tr>";
              	?>
			</tbody>
  		</table>
	</div>
</div>