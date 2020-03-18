<h2>Materials & Supplies Return Slip</h2>
<div>
<?php
	echo '<a href="'.HTTP_PATH.'inventory/return/print_msrs/'.$MSRS[0]['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print MSRS</a>';
	echo '<a href="'.HTTP_PATH.'inventory/return/listview" class="btn btn-dark force-pageload">Back</a>';
?>
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code No. <?php echo $MSRS[0]['id'];?> |
				Control No. <?php echo $MSRS[0]['control_number']; ?>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $company->name; ?></h3>
		<h4 class="text-center"><?php echo $company->address; ?></h3>
		<h4 class="text-center"><?php echo $company->telephone_no." - ".$company->fax_no; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Date Returned:</th>
				<td><?php echo  date("F d, Y", strtotime($MSRS[0]['return_date'])); ?></td>
			</tr>
			<tr>
				<th>Returned By:</th>
				<td><?php echo ucwords($MSRS[0]['return_by']); ?></td>
			</tr>
			<tr>
				<th>Returned From:</th>
				<td><?php echo $vessel->name; ?></td>
			</tr>
			<tr>
				<th>Returned To:</th>
				<td><?php echo  $MSRS[0]['return_to']; ?></td>
			</tr>
			<tr>
				<th>Remark:</th>
				<td><?php echo  $MSRS[0]['remark']; ?></td>
			</tr>
		</table>
		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($MSRS[0]['created_on'])); ?> by <?php echo $created_by['full_name']; ?></p>
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
                </tr>
            </thead>
            <tbody>
              	<?php 
              		$ctr =1;
              		$total = 0;
              		foreach($MSRS_details as $row){
              			$item = $this->Inventory_model->getItem($row['item_id']);
              			echo "<tr>";
              				echo "<td>".$ctr."</td>";
              				echo "<td>".$item[0]['item_code']."</td>";
              				echo "<td>".$item[0]['item_name']."</td>";
              				echo "<td>".$item[0]['brand']." ".$item[0]['particular']."</td>";	
              				echo "<td>".$row['qty']."</td>";
              				echo "<td>".$row['unit']."</td>";
              				echo "<td>".number_format($row['unit_price'],2,'.',',')."</td>";
              				echo "<td>".number_format(($row['unit_price']*$row['qty']),2,'.',',')."</td>";
              			echo "</tr>";
              			$total = $total + ($row['unit_price']*$row['qty']);
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