<h2>Stock Level</h2>
<h5>Company: <?php echo $company_name;?></h5>
<h5>Location: <?php echo $location?></h5>
<h5>As of <?php echo date('F j,Y') ?></h5>
<table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
<?php 
		echo "<tr>";
			echo "<th>#</th>";
			echo "<th>Item Code</th>";
			echo "<th>Item Name</th>";
			echo "<th>Brand</th>";
			echo "<th>Particular</th>";
			echo "<th>Unit</th>";
			echo "<th>Qty on Stock</th>";
			echo "<th>Reorder Point</th>";
			echo "<th>Percentage</th>";
			echo "<th>Stock Level</th>";
		echo "</tr>";

		$ctr=1;

 		foreach($summary as $row){

 			if($row->reorder_level<>0){
		 		echo "<tr>";
					echo "<td>".$ctr."</td>";
					echo "<td>".$row->item_code."</td>";
					echo "<td>".$row->description.",".$row->particular."</td>";
					echo "<td>".$row->brand."</td>";
					echo "<td>".$row->particular."</td>";
					echo "<td>".$row->unit."</td>";
					$quantity_stock = $row->total_quantity_received -  $row->total_quantity_issued;
					echo "<td>".number_format($quantity_stock,2,'.','')."</td>";
					echo "<td>".$row->reorder_level."</td>";

					$percentage =  number_format((($quantity_stock)/$row->reorder_level)*100,2,'.','');
					if($percentage<=20){
						$level = "Very Low";
					}elseif($percentage<=100){
						$level = "Low";
					}elseif($percentage>100){
						$level = "Normal";
					}

					echo "<td>".$percentage."%</td>";
					echo "<td>".$level."</td>";

				echo "</tr>";
				$ctr++;
			}
		}	
		if($ctr==1){
			echo "<tr><td colspan='10'><center>No record found.</center></td></tr>";
		}
?>
</table>